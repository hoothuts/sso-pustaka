<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PDF Flipbook</title>
    <link href="<?php echo base_url() ?>assets/readbook/select2/select2.min.css" rel="stylesheet" />
    <link href="<?php echo base_url() ?>assets/readbook/bootstrap5.3.3/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f0f0f0;
        }

        #flipbookContainer {
            width: 100%;
            flex: 1;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: transform 0.5s ease;
        }

        #flipbook {
            width: 100%;
            height: 100%;
            max-width: 100%;
            max-height: 100%;
            transform-origin: top center;
        }

        .page {
            width: 100%;
            height: 100%;
        }

        canvas {
            width: 100%;
            height: auto;
        }

        .controls {
            margin-top: 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            transition: opacity 0.3s ease;
        }

        .controls.hidden {
            opacity: 0;
            pointer-events: none;
        }

        .controls button {
            margin: 0 10px;
            font-size: 16px;
            cursor: pointer;
        }

        .controls select {
            width: 200px;
        }

        #currentPageInput {
            width: 80px;
            text-align: center;
            margin-right: 10px;
        }

        .zoom-button {
            position: fixed;
            top: 10px;
            right: 10px;
            background: url('<?php echo base_url() ?>assets/readbook/zoom-in.png') no-repeat center center;
            background-size: cover;
            width: 70px;
            height: 70px;
            border: none;
            cursor: pointer;
            z-index: 1000;
        }

        .zoom-button.zoomed {
            background: url('<?php echo base_url() ?>assets/readbook/zoom-out.png') no-repeat center center;
            background-size: cover;
        }
    </style>
</head>

<body oncontextmenu="return false">
    <button id="zoomButton" class="zoom-button"></button>

    <div id="flipbookContainer">
        <div id="flipbook">
            <!-- Isi flipbook akan dimuat di sini -->
        </div>
    </div>
    <div class="controls">
        <button id="firstPage" type="button" class="btn btn-outline-info">First</button>
        <button id="prevPage" type="button" class="btn btn-primary text-white">Previous</button>
        <input type="text" id="currentPageInput" readonly>
        <select id="pageSelect">
            <option></option>
        </select>
        <button id="nextPage" type="button" class="btn btn-primary text-white">Next</button>
        <button id="lastPage" type="button" class="btn btn-outline-info">Last</button>
    </div>
    <script src="<?php echo base_url() ?>assets/readbook/JQuery/jquery-3.7.1.min.js"></script>
    <script src="<?php echo base_url() ?>assets/readbook/pdfjs/build/pdf.min.js"></script>
    <script src="<?php echo base_url() ?>assets/readbook/turn4js/lib/turn.min.js"></script>
    <script src="<?php echo base_url() ?>assets/readbook/select2/select2.min.js"></script>
    <script src="<?php echo base_url() ?>assets/readbook/blockui/jquery.blockUI.js"></script>
    <script src="<?php echo base_url() ?>assets/readbook/bootstrap5.3.3/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const flipbookContainer = document.getElementById('flipbookContainer');
            const flipbook = document.getElementById('flipbook');
            const controls = document.querySelector('.controls');
            const zoomButton = document.getElementById('zoomButton');
            let scale = 1;
            let isZoomedIn = false;
            let isDragging = false;
            let startX, startY, scrollLeft, scrollTop;

            function toggleZoom() {
                if (isZoomedIn) {
                    scale = 1;
                    isZoomedIn = false;
                    controls.classList.remove('hidden');
                    zoomButton.classList.remove('zoomed');
                } else {
                    scale = 1.7;
                    isZoomedIn = true;
                    controls.classList.add('hidden');
                    zoomButton.classList.add('zoomed');
                }
                updateZoom();
            }

            function updateZoom() {
                flipbook.style.transition = 'transform 0.5s ease';
                flipbook.style.transform = `scale(${scale})`;
                flipbook.style.transformOrigin = 'top center';
                if (scale === 1) {
                    flipbookContainer.style.cursor = 'default';
                    flipbookContainer.removeEventListener('mousedown', startDragging);
                    document.removeEventListener('mouseup', endDragging);
                    document.removeEventListener('mousemove', drag);
                } else {
                    flipbookContainer.style.cursor = 'move';
                    flipbookContainer.addEventListener('mousedown', startDragging);
                    flipbookContainer.scrollLeft = (flipbook.scrollWidth - flipbookContainer.clientWidth) / 2;
                    flipbookContainer.scrollTop = (flipbook.scrollHeight - flipbookContainer.clientHeight) / 2;
                }
            }

            function startDragging(e) {
                isDragging = true;
                flipbookContainer.style.cursor = 'grabbing';
                startX = e.pageX - flipbookContainer.offsetLeft;
                startY = e.pageY - flipbookContainer.offsetTop;
                scrollLeft = flipbookContainer.scrollLeft;
                scrollTop = flipbookContainer.scrollTop;
                document.addEventListener('mouseup', endDragging);
                document.addEventListener('mousemove', drag);
            }

            function endDragging() {
                isDragging = false;
                flipbookContainer.style.cursor = 'move';
            }

            function drag(e) {
                if (!isDragging) return;
                e.preventDefault();
                const x = e.pageX - flipbookContainer.offsetLeft;
                const y = e.pageY - flipbookContainer.offsetTop;
                const walkX = x - startX;
                const walkY = y - startY;
                flipbookContainer.scrollLeft = scrollLeft - walkX;
                flipbookContainer.scrollTop = scrollTop - walkY;
            }

            // Mendeteksi dua kali klik pada buku
            flipbookContainer.addEventListener('dblclick', toggleZoom);
            zoomButton.addEventListener('click', toggleZoom);

            // Menambahkan event listener untuk keyboard
            document.addEventListener('keydown', function(e) {
                if (!isZoomedIn) { // Hanya menjalankan navigasi jika tidak dalam mode zoom
                    if (e.key === 'ArrowRight') {
                        $('#flipbook').turn('next');
                    } else if (e.key === 'ArrowLeft') {
                        $('#flipbook').turn('previous');
                    }
                }
            });
        });

        (async () => {
            var {
                pdfjsLib
            } = globalThis;
            pdfjsLib.GlobalWorkerOptions.workerSrc = '<?php echo base_url() ?>assets/readbook/pdfjs/build/pdf.worker.min.js';

            $.blockUI({
                message: '<h4>Mohon Tunggu...</h4>'
            });

            try {
                const pdfURL = '<?php echo $namafile ?>';
                const pdf = await pdfjsLib.getDocument(pdfURL).promise;
                const numPages = pdf.numPages;
                const flipbook = document.getElementById('flipbook');
                const pageSelect = document.getElementById('pageSelect');
                const currentPageInput = document.getElementById('currentPageInput');
                currentPageInput.value = '1';

                for (let i = 1; i <= numPages; i++) {
                    const page = await pdf.getPage(i);
                    const viewport = page.getViewport({
                        scale: 1.5
                    });
                    const canvas = document.createElement('canvas');
                    const context = canvas.getContext('2d');

                    canvas.height = viewport.height;
                    canvas.width = viewport.width;

                    await page.render({
                        canvasContext: context,
                        viewport: viewport
                    }).promise;

                    const pageDiv = document.createElement('div');
                    pageDiv.classList.add('page');
                    pageDiv.appendChild(canvas);
                    flipbook.appendChild(pageDiv);

                    const option = document.createElement('option');
                    option.value = i;
                    option.text = `Page ${i}`;
                    pageSelect.appendChild(option);
                }

                function resizeFlipbook() {
                    const flipbook = $('#flipbook');
                    const windowWidth = $(window).width();
                    const windowHeight = $(window).height();
                    const controlsHeight = $('.controls').outerHeight(true);
                    const availableHeight = windowHeight - controlsHeight;

                    let flipbookWidth, flipbookHeight;
                    if (windowWidth / availableHeight > 1.5) {
                        flipbookHeight = availableHeight;
                        flipbookWidth = flipbookHeight * 1.5;
                    } else {
                        flipbookWidth = windowWidth;
                        flipbookHeight = flipbookWidth / 1.5;
                    }

                    flipbook.turn('size', flipbookWidth, flipbookHeight);
                }

                $('#flipbook').turn({
                    autoCenter: true,
                    when: {
                        turning: function(e, page, view) {
                            const currentPage = view[0] + 1;
                            const totalPages = view.length;
                            if (view[0] > 0) {
                                if (view[0] == numPages) {
                                    currentPageInput.value = view[0];
                                } else {
                                    currentPageInput.value = `${view[0]}-${view[0] + 1}`;
                                }
                            } else {
                                const actualPage = Math.ceil(page / 2);
                                currentPageInput.value = actualPage;
                            }
                        }
                    }
                });

                $(window).resize(resizeFlipbook);
                resizeFlipbook();

                $('#pageSelect').select2({
                    placeholder: 'Select a page',
                });

                document.getElementById('firstPage').addEventListener('click', () => {
                    $('#flipbook').turn('page', 1);
                });

                document.getElementById('prevPage').addEventListener('click', () => {
                    $('#flipbook').turn('previous');
                });

                document.getElementById('nextPage').addEventListener('click', () => {
                    $('#flipbook').turn('next');
                });

                document.getElementById('lastPage').addEventListener('click', () => {
                    $('#flipbook').turn('page', numPages);
                });

                $('#pageSelect').on('change', function(event) {
                    if ($(this).val() == null || $(this).val() == '') {
                        console.log('kosong');
                    } else {
                        const page = parseInt($(this).val(), 10);
                        $('#flipbook').turn('page', page);
                    }
                });
            } catch (error) {
                console.error('Error during loading PDF:', error);
            } finally {
                $.unblockUI();
            }
        })();
    </script>
</body>

</html>