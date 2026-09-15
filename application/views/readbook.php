<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Sistem Perpustakaan - Responsive PDF Reader</title>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf_viewer.min.css">

    <style>
        :root {
            --primary-blue: #007bff;
            --dark-bg: #323639;
        }

        body {
            margin: 0;
            background-color: var(--dark-bg);
            font-family: 'Segoe UI', Roboto, sans-serif;
            overflow-x: hidden;
            user-select: text;
            -webkit-user-select: text;
        }

        /* Container Utama yang Fleksibel */
        #pdf-main-container {
            width: 100%;
            display: flex;
            flex-direction: column;
            align-items: center;
            padding: 10px 0;
            box-sizing: border-box;
        }

        /* Container per Halaman */
        .page-container {
            position: relative;
            margin-bottom: 15px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.5);
            background-color: white;
            max-width: 100%;
            /* Pastikan tidak keluar layar */
            overflow: hidden;
        }

        canvas {
            display: block;
            width: 100% !important;
            /* Paksa canvas mengikuti lebar kontainer */
            height: auto !important;
        }

        .textLayer {
            position: absolute;
            left: 0;
            top: 0;
            right: 0;
            bottom: 0;
            overflow: hidden;
            opacity: 0.2;
            line-height: 1.0;
        }

        /* Tombol Translate Melayang */
        #translate-btn {
            position: absolute;
            display: none;
            background: var(--primary-blue);
            color: white;
            padding: 12px 20px;
            border-radius: 30px;
            cursor: pointer;
            z-index: 10001;
            font-size: 14px;
            font-weight: bold;
            border: 2px solid white;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.4);
            touch-action: manipulation;
        }

        /* Pop-up Hasil Translate yang Responsif */
        #translate-popup {
            position: fixed;
            display: none;
            bottom: 0;
            /* Menempel di bawah untuk mobile */
            right: 0;
            left: 0;
            width: 100%;
            background: white;
            border-radius: 20px 20px 0 0;
            /* Rounded hanya atas di mobile */
            box-shadow: 0 -5px 25px rgba(0, 0, 0, 0.3);
            z-index: 10002;
            padding: 20px;
            box-sizing: border-box;
            animation: slideUp 0.3s ease-out;
            max-height: 70vh;
            /* Agar tidak menutupi seluruh layar */
            overflow-y: auto;
        }

        /* Mode Desktop untuk Pop-up */
        @media (min-width: 768px) {
            #translate-popup {
                bottom: 20px;
                right: 20px;
                left: auto;
                width: 380px;
                border-radius: 15px;
            }
        }

        @keyframes slideUp {
            from {
                transform: translateY(100%);
            }

            to {
                transform: translateY(0);
            }
        }

        .lang-badge {
            display: inline-block;
            background: #e8f0fe;
            color: #1967d2;
            padding: 4px 10px;
            border-radius: 6px;
            font-size: 11px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        #loading-info {
            color: white;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
        }

        @media print {
            body {
                display: none !important;
            }
        }
    </style>
</head>

<body oncontextmenu="return false;">

    <div id="loading-info">
        <h3>Memuat Dokumen...</h3>
        <p>Optimasi tampilan mobile aktif</p>
    </div>

    <div id="pdf-main-container"></div>

    <button id="translate-btn">🌐 Terjemahkan</button>

    <div id="translate-popup">
        <div style="display:flex; justify-content: space-between; align-items: center; margin-bottom: 12px;">
            <strong style="color: #333;">Terjemahan</strong>
            <button onclick="closeTranslate()" style="cursor:pointer; border:none; background:#f0f0f0; border-radius:50%; width:35px; height:35px; font-weight:bold;">✕</button>
        </div>
        <div id="translate-result">
            <div id="original-text-preview" style="font-size: 12px; color: #777; font-style: italic; margin-bottom: 10px; max-height: 60px; overflow-y: auto; border-left: 3px solid #ddd; padding-left: 8px;"></div>
            <div id="detected-language-container"></div>
            <div id="actual-result" style="color: #222; line-height: 1.6; font-size: 15px;"></div>
        </div>
    </div>

    <script>
        const langMap = {
            'en': 'Inggris',
            'fr': 'Prancis',
            'de': 'Jerman',
            'es': 'Spanyol',
            'it': 'Italia',
            'ja': 'Jepang',
            'ko': 'Korea',
            'zh-CN': 'Mandarin',
            'ar': 'Arab',
            'nl': 'Belanda',
            'ru': 'Rusia',
            'pt': 'Portugis',
            'id': 'Indonesia',
            'ms': 'Melayu',
            'tr': 'Turki'
        };

        const pdfData = '<?php echo $pdf_base64; ?>';
        const pdfjsLib = window['pdfjs-dist/build/pdf'];
        pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/3.4.120/pdf.worker.min.js';

        let selectedText = "";
        const container = document.getElementById('pdf-main-container');
        const translateBtn = document.getElementById('translate-btn');

        async function renderPDF() {
            try {
                const loadingTask = pdfjsLib.getDocument(pdfData);
                const pdf = await loadingTask.promise;
                document.getElementById('loading-info').style.display = 'none';

                for (let i = 1; i <= pdf.numPages; i++) {
                    const page = await pdf.getPage(i);

                    // --- PERBAIKAN RESPONSIVE SCALE ---
                    const unscaledViewport = page.getViewport({
                        scale: 1
                    });

                    // Gunakan 95% dari lebar window agar ada margin kecil di kiri kanan
                    const availableWidth = window.innerWidth * 0.95;
                    const dynamicScale = availableWidth / unscaledViewport.width;

                    // Batasi scale maksimal agar di desktop tidak terlalu besar (opsional)
                    const finalScale = dynamicScale > 1.5 ? 1.5 : dynamicScale;

                    const viewport = page.getViewport({
                        scale: finalScale
                    });

                    const pageDiv = document.createElement('div');
                    pageDiv.className = 'page-container';
                    pageDiv.style.width = viewport.width + 'px';
                    pageDiv.style.height = viewport.height + 'px';
                    container.appendChild(pageDiv);

                    const canvas = document.createElement('canvas');
                    const context = canvas.getContext('2d');
                    canvas.height = viewport.height;
                    canvas.width = viewport.width;
                    pageDiv.appendChild(canvas);

                    await page.render({
                        canvasContext: context,
                        viewport: viewport
                    }).promise;

                    const textContent = await page.getTextContent();
                    const textLayerDiv = document.createElement('div');
                    textLayerDiv.className = 'textLayer';
                    pageDiv.appendChild(textLayerDiv);

                    pdfjsLib.renderTextLayer({
                        textContent: textContent,
                        container: textLayerDiv,
                        viewport: viewport,
                        textDivs: []
                    });
                }
            } catch (error) {
                console.error("Error:", error);
                document.getElementById('loading-info').innerText = "Gagal memuat dokumen.";
            }
        }

        renderPDF();

        // Logika Seleksi
        document.addEventListener('selectionchange', () => {
            const selection = window.getSelection();
            const text = selection.toString().trim();
            if (text.length > 0) selectedText = text;
        });

        const handleShowButton = (e) => {
            setTimeout(() => {
                const selection = window.getSelection();
                const text = selection.toString().trim();

                if (text.length > 2) {
                    const x = e.pageX || (e.changedTouches ? e.changedTouches[0].pageX : 0);
                    const y = e.pageY || (e.changedTouches ? e.changedTouches[0].pageY : 0);

                    translateBtn.style.display = 'block';
                    // Mencegah tombol keluar dari sisi kanan layar
                    const btnWidth = 130;
                    const posX = (x + btnWidth > window.innerWidth) ? window.innerWidth - btnWidth - 10 : x;

                    translateBtn.style.left = posX + 'px';
                    translateBtn.style.top = (y - 70) + 'px';
                } else if (e.target !== translateBtn) {
                    translateBtn.style.display = 'none';
                }
            }, 50);
        };

        document.addEventListener('mouseup', handleShowButton);
        document.addEventListener('touchend', handleShowButton);

        const executeTranslate = async (e) => {
            e.preventDefault();
            e.stopPropagation();

            if (!selectedText) return;

            const popup = document.getElementById('translate-popup');
            const resultDiv = document.getElementById('actual-result');
            const langContainer = document.getElementById('detected-language-container');

            popup.style.display = 'block';
            document.getElementById('original-text-preview').innerText = `"${selectedText}"`;
            langContainer.innerHTML = "";
            resultDiv.innerHTML = "<span style='color: #888;'>⏳ Menerjemahkan...</span>";

            translateBtn.style.display = 'none';

            const url = `https://translate.googleapis.com/translate_a/single?client=gtx&sl=auto&tl=id&dt=t&q=${encodeURIComponent(selectedText)}`;

            try {
                const response = await fetch(url);
                const data = await response.json();
                let translatedText = "";
                data[0].forEach(obj => {
                    if (obj[0]) translatedText += obj[0];
                });

                const detectedCode = data[2];
                const languageName = langMap[detectedCode] || detectedCode.toUpperCase();

                langContainer.innerHTML = `<span class="lang-badge">Terdeteksi: Bahasa ${languageName}</span>`;
                resultDiv.innerText = translatedText;
            } catch (err) {
                resultDiv.innerText = "Gagal mengambil terjemahan.";
            }
        };

        translateBtn.addEventListener('mousedown', executeTranslate);
        translateBtn.addEventListener('touchstart', executeTranslate);

        function closeTranslate() {
            document.getElementById('translate-popup').style.display = 'none';
            window.getSelection().removeAllRanges();
        }
    </script>
</body>

</html>