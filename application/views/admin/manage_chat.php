<style>
    /* --- CSS DASAR LAYOUT --- */
    #sound-permission-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(255, 255, 255, 0.98);
        z-index: 10001;
        display: none;
        align-items: center;
        justify-content: center;
    }

    .permission-box {
        text-align: center;
        padding: 50px;
        background: white;
        border-radius: 15px;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.1);
        border: 1px solid #ebedf2;
    }

    .unread-badge {
        position: absolute;
        top: 18px;
        right: 15px;
        background-color: #f4516c;
        color: white;
        font-size: 10px;
        font-weight: bold;
        min-width: 18px;
        height: 18px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    /* --- BALON CHAT & INFORMASI BACA --- */
    .m-messenger__message-content {
        padding: 10px 15px 25px 15px !important;
        border-radius: 12px;
        font-size: 13px;
        position: relative;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.1);
        min-width: 120px;
    }

    /* Pastikan balon chat fleksibel mengikuti ukuran gambar */
    .m-messenger__message-content {
        max-width: 100% !important;
        word-wrap: break-word;
    }

    /* Gambar otomatis menyesuaikan lebar maksimal balon chat */
    .m-messenger__message-content img {
        max-width: 250px;
        /* Ukuran maksimal preview gambar */
        height: auto;
        border: 1px solid rgba(0, 0, 0, 0.1);
        transition: transform 0.2s;
    }

    .m-messenger__message-content img:hover {
        transform: scale(1.02);
        /* Efek zoom tipis saat kursor di atas gambar */
    }

    .m-messenger__message-time {
        position: absolute;
        bottom: 5px;
        right: 10px;
        font-size: 9px !important;
        opacity: 0.7;
    }

    .read-info-icon {
        position: absolute;
        top: 5px;
        right: 8px;
        color: #a2a5b9;
        cursor: help;
        font-size: 12px;
        display: inline-block;
        opacity: 1 !important;
    }

    .read-info-tooltip {
        visibility: hidden;
        width: 180px;
        background-color: #333;
        color: #fff;
        text-align: left;
        border-radius: 6px;
        padding: 8px 12px;
        position: absolute;
        z-index: 100;
        bottom: 150%;
        left: 50%;
        transform: translateX(-50%);
        opacity: 0;
        transition: opacity 0.3s;
        font-size: 10px;
        line-height: 1.4;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
    }

    .read-info-tooltip::after {
        content: "";
        position: absolute;
        top: 100%;
        left: 50%;
        margin-left: -5px;
        border-width: 5px;
        border-style: solid;
        border-color: #333 transparent transparent transparent;
    }

    .read-info-icon:hover .read-info-tooltip {
        visibility: visible;
        opacity: 1;
    }

    .chat-wrapper {
        display: flex;
        height: calc(100vh - 250px);
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 15px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .chat-aside {
        width: 300px;
        background: #fafafa;
        border-right: 1px solid #ebedf2;
        display: flex;
        flex-direction: column;
    }

    .user-list-container {
        overflow-y: auto;
        flex: 1;
    }

    .user-item {
        padding: 15px;
        border-bottom: 1px solid #f0f0f0;
        cursor: pointer;
        position: relative;
        transition: 0.2s;
        padding-left: 20px !important;
    }

    .user-item.active {
        background: #eef1ff;
        border-left: 4px solid #5867dd;
    }

    .chat-content {
        flex: 1;
        display: flex;
        flex-direction: column;
        background: #fff;
    }

    .chat-history-container {
        flex: 1;
        padding: 25px;
        background: #f4f5f8;
        overflow-y: auto;
        display: flex;
        flex-direction: column;
    }

    .m-messenger__message {
        margin-bottom: 20px;
        display: flex;
        flex-direction: column;
        max-width: 70%;
    }

    .m-messenger__message--in {
        align-self: flex-start;
    }

    .m-messenger__message--out {
        align-self: flex-end;
    }

    .m-messenger__message--in .m-messenger__message-content {
        background: #fff;
        border-bottom-left-radius: 0;
        color: #333;
    }

    .m-messenger__message--out .m-messenger__message-content {
        background: #5867dd;
        color: #fff;
        border-bottom-right-radius: 0;
    }

    .admin-name-label {
        display: block;
        font-size: 10px;
        font-weight: bold;
        margin-bottom: 3px;
        color: #eef1ff;
        text-transform: capitalize;
    }

    .chat-date-divider {
        text-align: center;
        margin: 20px 0;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .chat-date-divider span {
        background: #e1f5fe;
        color: #4f5b62;
        padding: 5px 15px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
        text-transform: uppercase;
    }

    .status-indicator {
        height: 10px;
        width: 10px;
        border-radius: 50%;
        display: inline-block;
        position: absolute;
        top: 15px;
        left: 5px;
    }

    .status-online {
        background-color: #2ecc71;
        box-shadow: 0 0 5px rgba(46, 204, 113, 0.6);
    }

    .status-offline {
        background-color: #bdc3c7;
    }

    /* Gambar di dalam balon chat */
    .m-messenger__message-content img {
        max-width: 100%;
        height: auto;
        border-radius: 8px;
        display: block;
        margin-top: 5px;
        cursor: pointer;
    }

    /* Link file jika bukan gambar */
    .file-link-admin {
        color: #fff !important;
        text-decoration: underline;
        font-size: 11px;
    }

    .file-link-user {
        color: #5867dd !important;
        text-decoration: underline;
        font-size: 11px;
    }
</style>

<div id="sound-permission-overlay">
    <div class="permission-box">
        <i class="flaticon-chat-1" style="font-size: 80px; color: #5867dd;"></i>
        <h3 class="mt-4">Sistem Monitoring Chat</h3>
        <p class="text-muted mb-4">Klik tombol di bawah untuk mengizinkan browser <br>membunyikan suara saat ada pesan baru masuk.</p>
        <button type="button" class="btn btn-primary m-btn--pill m-btn--air btn-lg" onclick="unlockAudio()">
            <i class="fa fa-volume-up"></i> AKTIFKAN NOTIFIKASI SUARA
        </button>
    </div>
</div>

<audio id="notifChatSound" src="<?= base_url('assets/media/notif_sound.wav') ?>" preload="auto"></audio>

<div class="m-grid__item m-grid__item--fluid m-wrapper">
    <div class="m-content">
        <div class="m-portlet m-portlet--mobile">
            <div class="m-portlet__body" style="padding:0">
                <div class="chat-wrapper">
                    <div class="chat-aside">
                        <div style="padding:15px; border-bottom: 1px solid #ebedf2;">
                            <input type="text" class="form-control m-input m-input--pill" placeholder="Cari Pelanggan..." onkeyup="filterUser(this.value)">
                        </div>
                        <div class="user-list-container" id="user-list-ajax"></div>
                    </div>
                    <div class="chat-content">
                        <div id="no-chat-selected" style="margin: auto; text-align: center; color: #ccc;">
                            <i class="flaticon-chat-1" style="font-size: 100px; display: block; margin-bottom: 10px;"></i>
                            <h5>Pilih pelanggan untuk membalas pesan</h5>
                        </div>
                        <div id="active-chat-box" style="display:none; flex-direction: column; height: 100%;">
                            <div style="padding: 10px 25px; border-bottom: 1px solid #ebedf2; background: #fff;">
                                <div style="display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <h5 style="margin:0; font-weight:bold;">
                                            <span id="target-user-name"></span>
                                            <span id="target-user-phone" class="text-muted" style="font-size: 13px; font-weight: normal;"></span>
                                        </h5>
                                        <div id="target-session-start" class="text-muted" style="font-size: 11px; margin-top: 2px;"></div>
                                    </div>
                                    <button id="btn-admin-finish" class="btn btn-outline-danger btn-sm m-btn m-btn--pill" onclick="finishSessionAdmin()">
                                        <i class="fa fa-times-circle"></i> Akhiri Sesi
                                    </button>
                                </div>
                                <div id="session-ended-info" style="display:none; margin-top: 5px; font-size: 11px; color: #f4516c; background: #fff1f3; padding: 5px 10px; border-radius: 4px; border: 1px solid #ffe2e5;">
                                    <i class="fa fa-info-circle"></i> Sesi ini telah diakhiri pada <b id="end-time"></b> oleh <b id="end-by"></b>
                                </div>
                            </div>
                            <div class="chat-history-container" id="chat-display"></div>
                            <div style="padding: 15px 25px; border-top: 1px solid #ebedf2;">
                                <div class="input-group">
                                    <input type="file" id="admin_file_input" style="display:none" onchange="submitReply(true)">

                                    <div class="input-group-prepend">
                                        <button class="btn btn-secondary" type="button" onclick="$('#admin_file_input').click()">
                                            <i class="fa fa-paperclip"></i>
                                        </button>
                                    </div>

                                    <input type="text" id="reply_input" class="form-control m-input" placeholder="Tulis pesan balasan...">

                                    <div class="input-group-append">
                                        <button class="btn btn-primary" onclick="submitReply(false)">Kirim</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    var activeIdSesi = null;
    var totalUnreadSebelumnya = 0;
    var audioUnlocked = false;
    var jumlahPesanAktifSebelumnya = 0;
    var userSessionData = {};

    function mainkanSuaraNotif() {
        if (audioUnlocked) {
            var sound = document.getElementById('notifChatSound');
            sound.currentTime = 0;
            sound.play().catch(e => {
                $('#sound-permission-overlay').fadeIn();
            });
        }
    }

    function unlockAudio() {
        var sound = document.getElementById('notifChatSound');
        sound.play().then(() => {
            audioUnlocked = true;
            localStorage.setItem('chat_audio_permission', 'granted');
            $('#sound-permission-overlay').fadeOut();
            updateUserList();
        }).catch(err => {
            console.log("Izin audio gagal:", err);
        });
    }

    $(document).ready(function() {
        if (localStorage.getItem('chat_audio_permission') === 'granted') {
            audioUnlocked = true;
            $('#sound-permission-overlay').hide();
            updateUserList();
        } else {
            $('#sound-permission-overlay').css('display', 'flex');
        }

        setInterval(function() {
            if (audioUnlocked) updateUserList();
        }, 5000);
        setInterval(function() {
            if (audioUnlocked) refreshChat();
        }, 2000);

        $('#reply_input').keypress(function(e) {
            if (e.which == 13) submitReply();
        });
    });

    function updateUserList() {
        $.get('<?= base_url("admin/manage_chat/list_users_ajax") ?>', function(resp) {
            var users = (typeof resp === 'string') ? JSON.parse(resp) : resp;
            var html = '';
            var totalUnreadSekarang = 0;

            users.forEach(function(u) {
                userSessionData[u.chatsesi_id] = u;

                var isActive = (activeIdSesi == u.chatsesi_id) ? 'active' : '';
                var statusClass = (u.status_sesi == 'aktif') ? 'status-online' : 'status-offline';
                var dot = '<span class="status-indicator ' + statusClass + '"></span>';

                var unreadCount = parseInt(u.jumlah_unread) || 0;
                totalUnreadSekarang += unreadCount;

                // 1. Buat badge jika ada unread
                var badge = (unreadCount > 0) ? '<span class="unread-badge">' + unreadCount + '</span>' : '';

                // 2. Berikan margin-right 30px jika ada badge agar tidak tabrakan
                var badgeSpacing = (unreadCount > 0) ? 'margin-right: 30px;' : '';

                var tglMulaiDisplay = '';
                if (u.tgl_buat) {
                    var dStart = new Date(u.tgl_buat);
                    tglMulaiDisplay = dStart.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'short'
                    }) + ', ' + dStart.toLocaleTimeString('id-ID', {
                        hour: '2-digit',
                        minute: '2-digit'
                    });
                }

                html += '<div class="user-item ' + isActive + '" onclick="openChat(' + u.chatsesi_id + ')">' +
                    dot +
                    '<b>' + u.nama + '</b>' +
                    badge + // Badge tetap absolute di pojok kanan
                    '<span style="float: right; font-size: 10px; color: #a2a5b9; font-weight: 500; ' + badgeSpacing + '">' +
                    tglMulaiDisplay +
                    '</span>' +
                    '<br>' +
                    '<small class="text-muted">' + u.email + '</small></div>';
            });

            if (totalUnreadSekarang > totalUnreadSebelumnya) {
                mainkanSuaraNotif();
            }
            totalUnreadSebelumnya = totalUnreadSekarang;

            $('#user-list-ajax').html(html);
        });
    }

    function openChat(id) {
        activeIdSesi = id;
        jumlahPesanAktifSebelumnya = 0; // Reset agar tidak bunyi saat pertama buka chat

        var data = userSessionData[id];
        $('#target-user-name').text(data.nama);
        $('#target-user-phone').text(' (' + (data.no_hp || '-') + ')');
        console.log(data.tgl_buat);
        if (data.tgl_buat) {
            var date = new Date(data.tgl_buat);
            var options = {
                day: 'numeric',
                month: 'short',
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            };
            var formattedDate = date.toLocaleDateString('id-ID', options);
            $('#target-session-start').html('<i class="fa fa-calendar-alt"></i> Dimulai: ' + formattedDate);
        } else {
            $('#target-session-start').text('dd');
        }

        if (data.status_sesi === 'tidak aktif') {
            $('#btn-admin-finish').hide();
            $('#reply_input').prop('disabled', true).attr('placeholder', 'Sesi telah berakhir...');
            $('#end-time').text(data.tgl_akhir);
            var textAkhir = (data.diakhiri_oleh === 'admin') ? 'Admin (' + data.diakhiri_idsysuser + ')' : 'Pelanggan';
            $('#end-by').text(textAkhir);
            $('#session-ended-info').fadeIn();
        } else {
            $('#btn-admin-finish').show();
            $('#reply_input').prop('disabled', false).attr('placeholder', 'Tulis pesan balasan...');
            $('#session-ended-info').hide();
        }

        $('.user-item').removeClass('active');
        // Cari element berdasarkan attribute onclick yang dinamis
        $('#user-list-ajax .user-item').each(function() {
            if ($(this).attr('onclick').includes(id)) $(this).addClass('active');
        });

        $('#no-chat-selected').hide();
        $('#active-chat-box').css('display', 'flex');
        refreshChat();
    }

    function refreshChat() {
        if (!activeIdSesi) return;

        var currentData = userSessionData[activeIdSesi];
        var d = $('#chat-display');

        // 1. Simpan status scroll sebelum update (toleransi ketat 20px)
        var isAtBottom = (d.scrollTop() + d.innerHeight() >= d[0].scrollHeight - 20);
        var isFirstLoad = (jumlahPesanAktifSebelumnya === 0);

        if (currentData && currentData.status_sesi === 'tidak aktif') {
            // ... (logika status sesi tetap sama) ...
            $('#btn-admin-finish').hide();
            $('#reply_input').prop('disabled', true);
            $('#session-ended-info').fadeIn();
        }

        $.get('<?= base_url("admin/manage_chat/load_chat/") ?>' + activeIdSesi, function(resp) {
            var msgs = (typeof resp === 'string') ? JSON.parse(resp) : resp;
            var html = '';
            var lastDate = "";

            // 2. Bangun string HTML di memori (jangan masukkan ke DOM dulu)
            msgs.forEach(function(m) {
                var parts = m.created_at.split(' ');
                var dateOnly = parts[0];
                var timeOnly = parts[1] ? parts[1].substring(0, 5) : "";

                if (dateOnly !== lastDate) {
                    var dObj = new Date(dateOnly);
                    var formattedDate = dObj.toLocaleDateString('id-ID', {
                        day: 'numeric',
                        month: 'short',
                        year: 'numeric'
                    });
                    html += '<div class="chat-date-divider"><span>' + formattedDate + '</span></div>';
                    lastDate = dateOnly;
                }

                var pos = (m.sender_type == 'user') ? 'in' : 'out';
                var content = '';

                if (m.is_file == '1') {
                    var fileName = m.message;
                    var fileExt = fileName.split('.').pop().toLowerCase();
                    var filePath = '<?= base_url("assets/media/lampiran_chat/") ?>' + fileName;
                    var linkStyle = (pos == 'out') ? 'color:#fff;' : 'color:#5867dd;';

                    if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExt)) {
                        content = '<a href="' + filePath + '" target="_blank"><img src="' + filePath + '" style="max-width: 100%; height: auto; border-radius: 8px;"></a>';
                    } else {
                        content = '<i class="fa fa-file"></i> <a href="' + filePath + '" target="_blank" style="' + linkStyle + ' text-decoration:underline;">' + fileName + '</a>';
                    }
                } else {
                    content = m.message;
                }

                var infoHover = (pos == 'in') ? `
                <div class="read-info-icon"><i class="fa fa-exclamation-circle"></i>
                    <div class="read-info-tooltip">
                        <strong>Dibaca oleh:</strong> ${m.readby || 'Belum dibaca'} <br>
                        <strong>Waktu:</strong> ${m.tgl_read || '-'}
                    </div>
                </div>` : '';

                var adminName = (m.sender_type === 'admin' && m.sendby) ? '<span class="admin-name-label">' + m.sendby + '</span>' : '';

                html += '<div class="m-messenger__message m-messenger__message--' + pos + '">' +
                    '<div class="m-messenger__message-content">' +
                    adminName + content + infoHover +
                    '<span class="m-messenger__message-time">' + timeOnly + '</span>' +
                    '</div></div>';
            });

            // --- KUNCI: HANYA UPDATE JIKA KONTEN BERBEDA ---
            // Jika HTML baru sama persis dengan yang ada di layar, jangan lakukan apa-apa.
            // Ini akan menjaga posisi scroll Anda 100% tetap diam.
            if (d.html() !== html) {

                // Logika Suara Notif (hanya jika pesan bertambah)
                if (msgs.length > jumlahPesanAktifSebelumnya && !isFirstLoad) {
                    if (msgs[msgs.length - 1].sender_type === 'user') mainkanSuaraNotif();
                }
                jumlahPesanAktifSebelumnya = msgs.length;

                // Masukkan HTML ke container
                d.html(html);

                // 3. Hanya scroll ke bawah jika diizinkan
                if (isAtBottom || isFirstLoad) {
                    d.scrollTop(d.prop("scrollHeight"));
                }
            }
        });
    }

    function submitReply(isFileType = false) {
        var msg = $('#reply_input').val();
        var fileInput = document.getElementById('admin_file_input');

        if (!activeIdSesi) return;

        // Jika bukan kirim file dan pesan kosong, hentikan
        if (!isFileType && !msg) return;

        var formData = new FormData();
        formData.append('chatsesi_id', activeIdSesi);

        if (isFileType) {
            // Jika user batal memilih file
            if (fileInput.files.length === 0) return;

            var file = fileInput.files[0];
            var maxSize = 2 * 1024 * 1024; // 2MB

            // --- VALIDASI UKURAN FILE ---
            if (file.size > maxSize) {
                Swal.fire({
                    icon: 'error',
                    title: 'File Terlalu Besar',
                    text: 'Maksimal ukuran file attachment adalah 2MB!',
                    timer: 3000, // Hilang dalam 3 detik
                    showConfirmButton: false, // Tidak perlu tombol OK
                    toast: true, // Opsional: Tampil ala notifikasi
                    position: 'top-end' // Opsional: Posisi di pojok kanan atas
                });

                fileInput.value = ''; // Reset input file agar tidak ikut terkirim
                return; // Hentikan proses
            }
            // ----------------------------

            formData.append('file_reply', file);
        } else {
            formData.append('message', msg);
        }

        $.ajax({
            url: '<?= base_url("admin/manage_chat/reply") ?>',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(resp) {
                $('#reply_input').val('');
                $('#admin_file_input').val(''); // Reset file input setelah sukses kirim

                // Refresh chat & scroll ke bawah
                var container = $('#chat-display');
                // Kita force scroll sedikit agar fungsi refreshChat mendeteksi "isAtBottom"
                container.scrollTop(container.prop("scrollHeight"));
                refreshChat();
            }
        });
    }

    function finishSessionAdmin() {
        if (!activeIdSesi) return;
        if (confirm("Apakah Anda yakin ingin mengakhiri sesi chat ini?")) {
            $.post('<?= base_url("admin/manage_chat/finish_session") ?>', {
                chatsesi_id: activeIdSesi
            }, function(resp) {
                if (resp.status == 'success') {
                    alert('Sesi berhasil diakhiri.');
                    updateUserList();
                    refreshChat();
                }
            });
        }
    }

    function filterUser(val) {
        var value = val.toLowerCase();
        $("#user-list-ajax .user-item").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    }
</script>