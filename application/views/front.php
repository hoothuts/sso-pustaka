<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html dir="ltr" lang="en">

<head>
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <meta http-equiv="content-type" content="text/html; charset=UTF-8" />

  <title>Sistem Informasi Perpustakaan POLITEKNIK KEMENKES RIAU</title>

  <link href="<?php echo base_url(); ?>assets/front/images/favicon lib.png" rel="shortcut icon" type="image/png">
  <link href="<?php echo base_url(); ?>assets/front/images/favicon lib.png" rel="apple-touch-icon">

  <link href="<?php echo base_url(); ?>assets/front/css/bootstrap.min.css" rel="stylesheet" type="text/css">
  <link href="<?php echo base_url(); ?>assets/front/css/jquery-ui.min.css" rel="stylesheet" type="text/css">
  <link href="<?php echo base_url(); ?>assets/front/css/animate.css" rel="stylesheet" type="text/css">
  <link href="<?php echo base_url(); ?>assets/front/css/css-plugin-collections.css" rel="stylesheet" />
  <link id="menuzord-menu-skins" href="<?php echo base_url(); ?>assets/front/css/menuzord-skins/menuzord-rounded-boxed.css" rel="stylesheet" />
  <link href="<?php echo base_url(); ?>assets/front/css/style-main.css" rel="stylesheet" type="text/css">
  <link href="<?php echo base_url(); ?>assets/front/css/preloader.css" rel="stylesheet" type="text/css">
  <link href="<?php echo base_url(); ?>assets/front/css/custom-bootstrap-margin-padding.css" rel="stylesheet" type="text/css">
  <link href="<?php echo base_url(); ?>assets/front/css/responsive.css" rel="stylesheet" type="text/css">
  <link href="<?php echo base_url(); ?>assets/front/css/colors/theme-skin-color-set-1.css" rel="stylesheet" type="text/css">

  <link href="<?php echo base_url(); ?>assets/front/js/revolution-slider/css/settings.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url(); ?>assets/front/js/revolution-slider/css/layers.css" rel="stylesheet" type="text/css" />
  <link href="<?php echo base_url(); ?>assets/front/js/revolution-slider/css/navigation.css" rel="stylesheet" type="text/css" />

  <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/front/css/kc.fab.css" />

  <script src="<?php echo base_url(); ?>assets/front/js/jquery-2.2.4.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/front/js/jquery-ui.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/front/js/bootstrap.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/front/js/jquery-plugin-collection.js"></script>
  <script src="<?php echo base_url(); ?>assets/front/js/revolution-slider/js/jquery.themepunch.tools.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/front/js/revolution-slider/js/jquery.themepunch.revolution.min.js"></script>

  <link href="<?php echo base_url(); ?>assets/front/css/custom.css" rel="stylesheet" type="text/css" />

</head>

<body class="">
  <audio id="chat-notif-sound" src="<?php echo base_url(); ?>assets/media/notif_sound.wav" preload="auto"></audio>

  <div id="wrapper" class="clearfix">

    <?php include 'front-header.php'; ?>

    <div class="main-content">
      <?php if ($page_content) include 'front-content.php'; ?>
    </div>
      
    <footer id="footer" class="footer-modern">

        <div class="container pt-70 pb-40">
            <div class="row">

                  <!-- ABOUT -->
                  <div class="col-sm-6 col-md-3">
                      <img class="mb-15" src="<?= base_url(); ?>assets/media/Home.png" style="max-width:150px;">
                      <p>
                          Sistem Informasi Perpustakaan dikembangkan untuk memudahkan pelayanan dan pengelolaan perpustakaan.
                      </p>

                      <div class="footer-social mt-15">
                          <a href="https://www.facebook.com/perpustakaan.poltekkeskemenkesriau"><i class="fa fa-facebook"></i></a>
                          <a href="#"><i class="fa fa-twitter"></i></a>
                          <a href="mailto:librarypolkesri@pkr.ac.id"><i class="fa fa-envelope"></i></a>
                      </div>
                  </div>

                  <!-- BERITA TERBARU -->
                  <div class="col-sm-6 col-md-3">
                      <h5 class="widget-title">Berita Terbaru</h5>

                      <?php
                      $artikel = $this->Md_artikel->getNewArtikelLimit(2);
                      if ($artikel && count($artikel) > 0):
                          foreach ($artikel as $art):
                              $media = $this->Md_media->getMediaById($art->media_id);
                              $img = (!empty($media) ? $media[0]['judul'] : 'artikel-small.jpg');

                              $judul = strlen($art->judul) > 60 ? substr($art->judul, 0, 60) . '...' : $art->judul;
                              ?>

                              <div class="footer-post">
                                  <img src="<?= base_url(); ?>uploads/small/small_<?= $img; ?>">
                                  <div class="footer-post-content">
                                      <a href="<?= base_url(); ?>home/read/<?= $art->artikel_id; ?>" class="footer-post-title">
        <?= $judul; ?>
                                      </a>
                                  </div>
                              </div>

        <?php
    endforeach;
endif;
?>
                  </div>

                  <!-- MENU -->
                  <div class="col-sm-6 col-md-3">
                      <h5 class="widget-title">Menu</h5>
                      <ul class="footer-menu">
                          <li><a href="<?= base_url(); ?>">Home</a></li>
                          <li><a href="<?= base_url(); ?>home/about">Profil</a></li>
                          <li><a href="<?= base_url(); ?>home/digital_book">Digital Book</a></li>
                          <li><a href="<?= base_url(); ?>home/kontak">Kontak</a></li>
                      </ul>
                  </div>

                  <!-- KONTAK -->
                  <div class="col-sm-6 col-md-3">
                      <h5 class="widget-title">Kontak</h5>
                      <ul class="footer-contact">
                          <li><i class="fa fa-phone"></i> (0761)36581</li>
                          <li><i class="fa fa-envelope"></i> librarypolkesri@pkr.ac.id</li>
                          <li><i class="fa fa-map-marker"></i> Jl. Melur No.103 Pekanbaru</li>
                      </ul>
                  </div>

            </div>
        </div>

        <!-- BOTTOM -->
        <div class="footer-bottom-modern text-center pt-15 pb-15">
            <p class="m-0">
                © 2017 Sistem Informasi Perpustakaan Poltekkes Riau |
                Powered by <a href="https://nusacore.com/">Nusa Core</a>
            </p>
        </div>

    </footer>

    <div class="chat-bubble" id="btn-toggle-chat" title="Tanya Pustakawan">
      <i class="fa fa-comments"></i>
    </div>

    <div class="chat-window" id="chat-window">
      <div class="chat-header">
        <span><i class="fa fa-commenting"></i> Tanya Pustakawan</span>
        <div>
          <button id="btn-finish-chat" class="btn btn-xs btn-danger" style="font-size: 10px; margin-right: 5px; <?php echo $this->session->userdata('chat_session_id') ? '' : 'display:none'; ?>">
            Akhiri Chat
          </button>
          <span style="cursor:pointer" id="btn-close-chat">&times;</span>
        </div>
      </div>

      <div id="chat-reg-section" class="form-chat-reg" style="padding:20px; <?php echo $this->session->userdata('chat_session_id') ? 'display:none' : ''; ?>">
          
        <p class="text-center"><b>Halo!</b><br>Ada yang bisa kami bantu?</p>
        <input type="text" id="chat_name" class="form-control" placeholder="Nama Lengkap">
        <input type="email" id="chat_email" class="form-control" placeholder="Email">
        <input type="text" id="chat_phone" class="form-control" placeholder="Nomor WhatsApp">
        <button class="btn btn-primary btn-block btn-sm" onclick="startChatSession()">Mulai Chat</button>
        <p class="text-center" style="font-size:11px;">Layanan dibuka Pada Jam Operasional Perpustakaan</p>
      </div>

      <div id="chat-main-section" style="<?php echo $this->session->userdata('chat_session_id') ? '' : 'display:none'; ?>">
        <div class="chat-body" id="chat-display-area">
        </div>
        <div class="chat-footer">
          <div class="input-group">
            <input type="file" id="chat_file_input" style="display:none" onchange="sendUserFile()">

            <span class="input-group-btn">
              <button class="btn btn-default btn-sm" onclick="$('#chat_file_input').click()">
                <i class="fa fa-paperclip"></i>
              </button>
            </span>

            <input type="text" id="chat_message_input" class="form-control input-sm" placeholder="Tulis pesan...">

            <span class="input-group-btn">
              <button class="btn btn-primary btn-sm" id="btn-send-message" onclick="sendUserMessage()">Kirim</button>
            </span>
          </div>
        </div>
      </div>
    </div>

    <div class="kc_fab_wrapper"></div>
    <a class="scrollToTop" href="#"><i class="fa fa-angle-up"></i></a>

  </div>

  <script src="<?php echo base_url(); ?>assets/front/js/custom.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <?php if ($page_name != 'penelusuran_buku' && $page_name != 'digital_book' && $page_name != 'readartikel') : ?>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/front/js/revolution-slider/js/extensions/revolution.extension.actions.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/front/js/revolution-slider/js/extensions/revolution.extension.navigation.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/front/js/revolution-slider/js/extensions/revolution.extension.slideanims.min.js"></script>
  <?php endif; ?>

  <script type="text/javascript" src="<?php echo base_url(); ?>assets/front/js/kc.fab.min.js"></script>
  <script src="https://www.google.com/recaptcha/api.js?render=<?= $this->config->item('recaptcha_site_key') ?>"></script>    
  <script type="text/javascript">
    var currentSessionId = "<?php echo $this->session->userdata('chat_session_id'); ?>";
    var pollingInterval;
    var lastAdminMsgCount = 0; // Untuk melacak pesan baru dari admin

    $(document).ready(function() {
      // Logic Tombol WA
      var numb = [];
      $.ajax({
        method: 'POST',
        url: '<?php echo base_url() ?>home/kontak/getWhatsappReady/',
        dataType: 'json',
        success: function(resp) {
          if (resp.length > 0) {
            numb.push({
              "bgcolor": "#349b34",
              "icon": "<i class='fa fa-whatsapp'>"
            });
            for (var i = 0; i < resp.length; i++) {
              numb.push({
                "url": "https://api.whatsapp.com/send?phone=" + resp[i]['hp'],
                "bgcolor": "#5bc43e",
                "icon": "<i class='fa fa-whatsapp'></i>",
                "target": "_blank",
                "title": "Admin Perpus"
              });
            }
            $('.kc_fab_wrapper').kc_fab(numb);
          }
        }
      });
      $('#btn-toggle-chat').click(function() {
        // Dummy play untuk membuka blokir autoplay browser
        var sound = document.getElementById('chat-notif-sound');
        sound.play().then(() => {
          sound.pause();
          sound.currentTime = 0;
        }).catch(() => {});

        $('#chat-window').toggle();
        if (currentSessionId) {
          fetchMessages(true);
          startPolling();
        }
      });
      // --- LOGIC LIVE CHAT ---
      // $('#btn-toggle-chat').click(function() {
      //   $('#chat-window').toggle();
      //   if (currentSessionId) {
      //     fetchMessages(true);
      //     startPolling();
      //   }
      // });

      $('#btn-close-chat').click(function() {
        $('#chat-window').hide();
        stopPolling();
      });

      $('#btn-finish-chat').click(function() {
        if (confirm("Apakah Anda yakin ingin mengakhiri sesi chat ini?")) {
          $.ajax({
            url: '<?php echo base_url("Home/akhiri_chat"); ?>',
            type: 'POST',
            success: function(response) {
              var res = JSON.parse(response);
              if (res.status == 'success') {
                alert("Terima kasih! Sesi chat telah berakhir.");
                resetChatUI();
                stopPolling();
              }
            }
          });
        }
      });

      $('#chat_message_input').keypress(function(e) {
        if (e.which == 13) sendUserMessage();
      });

      if (currentSessionId) {
        fetchMessages(true);
        startPolling();
      }
    });

    function startChatSession() {
      var name = $('#chat_name').val();
      var email = $('#chat_email').val();
      var phone = $('#chat_phone').val();

      if (!name || !email || !phone) {
        alert('Silakan lengkapi data Anda.');
        return;
      }

      var emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
      if (!emailPattern.test(email)) {
        alert('Format email tidak valid.');
        $('#chat_email').focus();
        return;
      }

      // Ambil token reCAPTCHA dulu, baru kirim data
      grecaptcha.ready(function () {
        grecaptcha.execute('<?= $this->config->item('recaptcha_site_key') ?>', { action: 'submit_chat' })
          .then(function (token) {

              $.post('<?php echo base_url("Home/start_chat"); ?>', {
                  name:             name,
                  email:            email,
                  phone:            phone,
                  recaptcha_token:  token  // ← tambahan token
              }, function (res) {
                  var data = JSON.parse(res);
                  if (data.status == 'success') {
                      currentSessionId = data.session_id;
                      $('#chat-reg-section').hide();
                      $('#chat-main-section').show();
                      $('#btn-finish-chat').show();
                      fetchMessages(true);
                      startPolling();
                  } else {
                      alert(data.message);
                  }
              });

              // Suara notif tetap sama
              var sound = document.getElementById('chat-notif-sound');
              sound.play().then(function () {
                  sound.pause();
                  sound.currentTime = 0;
              }).catch(function (e) {});

          });
      });
    }

    function sendUserMessage() {
      var msg = $('#chat_message_input').val();
      if (!msg || !currentSessionId) return;

      var formData = new FormData();
      formData.append('session_id', currentSessionId);
      formData.append('message', msg);
      formData.append('sender_type', 'user');

      executeSend(formData);
      $('#chat_message_input').val('');
    }

    function sendUserFile() {
      var fileInput = document.getElementById('chat_file_input');

      // Jika user membatalkan pemilihan file
      if (fileInput.files.length === 0) return;

      var file = fileInput.files[0];
      var fileSize = file.size; // Ukuran file dalam bytes
      var maxSize = 2 * 1024 * 1024; // 2MB (2 * 1024 KB * 1024 Bytes)

      // --- VALIDASI UKURAN FILE ---
      if (fileSize > maxSize) {
        // Tampilkan SweetAlert
        Swal.fire({
          icon: 'error', // Ikon merah silang
          title: 'File Terlalu Besar', // Judul
          text: 'Maksimal ukuran file adalah 2MB!', // Pesan
          timer: 3000, // 3000ms = 3 detik
          showConfirmButton: false, // Hilangkan tombol OK agar clean
          toast: true, // Opsional: Tampil kecil di pojok (seperti notif HP)
          position: 'top-end' // Opsional: Posisi notifikasi
        });

        fileInput.value = ''; // Reset input agar file batal terkirim
        return;
      }
      // ----------------------------

      var formData = new FormData();
      formData.append('session_id', currentSessionId);
      formData.append('sender_type', 'user');
      formData.append('file_lampiran', file);

      executeSend(formData);
      fileInput.value = ''; // Reset input setelah berhasil dikirim ke fungsi execute
    }

    function executeSend(formData) {
      $.ajax({
        url: '<?php echo base_url("Home/send"); ?>',
        type: 'POST',
        data: formData,
        processData: false, // Penting untuk pengiriman file
        contentType: false, // Penting untuk pengiriman file
        dataType: 'json', // Pastikan menerima response dalam format JSON
        success: function(response) {
          // Cek jika server mengirimkan status error (misal: "filetype not allowed")
          if (response.status === 'error') {
            Swal.fire({
              icon: 'error',
              title: 'Gagal Mengirim',
              text: response.message,
              position: 'top-end', // Posisi di kanan atas
              showConfirmButton: false, // Hilangkan tombol konfirmasi
              timer: 3000, // Waktu 3 detik (3000ms)
              toast: true, // Mengaktifkan mode "toast" (notifikasi baris)
              timerProgressBar: true, // (Opsional) Menampilkan bar waktu berjalan di bawah
              didOpen: (toast) => { // (Opsional) Berhenti hitung mundur jika mouse diarahkan
                toast.addEventListener('mouseenter', Swal.stopTimer)
                toast.addEventListener('mouseleave', Swal.resumeTimer)
              }
            });
          } else {
            // Jika sukses, muat ulang pesan
            fetchMessages();
          }
        },
        error: function(xhr, status, error) {
          // Menangani error jaringan atau error 500 dari server
          Swal.fire({
            icon: 'error',
            title: 'Kesalahan Sistem',
            text: 'Terjadi kesalahan saat menghubungi server. Silakan coba lagi.',
            confirmButtonColor: '#007bff'
          });
        }
      });
    }

    function fetchMessages(isFirstLoad = false) {
      if (!currentSessionId) return;

      $.get('<?php echo base_url("Home/load_messages/"); ?>' + currentSessionId, function(res) {
        var data = JSON.parse(res);
        var messages = data.messages;
        var html = '';
        var currentAdminMsgCount = 0;

        // 1. Loop untuk menampilkan pesan seperti biasa
        messages.forEach(function(m) {
          if (m.sender_type == 'admin') currentAdminMsgCount++;

          var pos = (m.sender_type == 'user') ? 'msg-user' : 'msg-admin';
          var content = '';
          var fileName = m.message;
          var fileExt = fileName.split('.').pop().toLowerCase();
          var isImage = ['jpg', 'jpeg', 'png', 'gif'].includes(fileExt);
          var isDocument = ['pdf', 'doc', 'docx', 'xls', 'xlsx', 'zip'].includes(fileExt);

          if (isImage) {
            var filePath = '<?php echo base_url("assets/media/lampiran_chat/"); ?>' + fileName;
            content = '<a href="' + filePath + '" target="_blank"><img src="' + filePath + '" style="max-width:100%; border-radius:10px;"></a>';
          } else if (isDocument) {
            var filePath = '<?php echo base_url("assets/media/lampiran_chat/"); ?>' + fileName;
            content = '<div style="padding: 10px; background: rgba(0,0,0,0.05); border-radius: 5px;"><i class="fa fa-file-text-o"></i> <a href="' + filePath + '" target="_blank" style="color:inherit; font-weight:bold;">' + fileName + '</a></div>';
          } else {
            content = m.message;
          }

          html += '<div class="msg-wrap ' + pos + '"><div class="msg-text">' + content + '</div><div class="msg-time">' + m.created_at + '</div></div>';
        });

        // Masukkan pesan ke area display
        $('#chat-display-area').html(html);

        // 2. CEK STATUS SESI: Jika "tidak aktif"
        if (data.status_sesi === "tidak aktif") {
          // Matikan input dan tombol kirim
          $('#chat_message_input').prop('disabled', true).attr('placeholder', 'Sesi telah berakhir...');
          $('#btn-send-message').prop('disabled', true);
          $('.chat-footer button').prop('disabled', true); // Matikan juga tombol lampiran

          // Tambahkan pesan penutup dan tombol mulai kembali di paling bawah chat
          var endHtml = `
        <div class="text-center" style="margin-top: 20px; padding: 15px; border-top: 1px dashed #ccc; background: #f9f9f9; border-radius: 8px;">
          <p style="font-size: 12px; color: #666; margin-bottom: 10px;">
            <i class="fa fa-info-circle"></i> Sesi ini telah diakhiri oleh <b>Pustakawan</b>.
          </p>
          <button class="btn btn-primary btn-xs" onclick="resetChatUI()" style="border-radius: 20px; padding: 5px 15px;">
            Mulai Sesi Baru
          </button>
        </div>
      `;
          $('#chat-display-area').append(endHtml);

          // Scroll ke paling bawah agar tombol terlihat
          $('#chat-display-area').scrollTop($('#chat-display-area')[0].scrollHeight);

          // Hentikan polling
          stopPolling();
          return;
        }

        // --- Logika notifikasi suara & scroll normal (jika sesi masih aktif) ---
        if (!isFirstLoad && currentAdminMsgCount > lastAdminMsgCount) {
          var sound = document.getElementById('chat-notif-sound');
          if (sound) {
            sound.currentTime = 0;
            sound.play().catch(function(e) {});
          }
        }
        lastAdminMsgCount = currentAdminMsgCount;

        if (isFirstLoad) {
          $('#chat-display-area').scrollTop($('#chat-display-area')[0].scrollHeight);
        }
      });
    }

    function resetChatUI() {
      currentSessionId = null;
      lastAdminMsgCount = 0;
      $('#chat-main-section').hide();
      $('#chat-reg-section').show();
      $('#chat-display-area').html('');
      $('#chat_message_input').prop('disabled', false).attr('placeholder', 'Tulis pesan...');
      $('#btn-send-message').prop('disabled', false);
      $('#btn-finish-chat').hide();
    }

    function startPolling() {
      if (!pollingInterval) {
        pollingInterval = setInterval(function() {
          fetchMessages(false);
        }, 2000);
      }
    }

    function stopPolling() {
      clearInterval(pollingInterval);
      pollingInterval = null;
    }
  </script>
</body>

</html>