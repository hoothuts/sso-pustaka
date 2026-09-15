<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <title>Sistem Informasi Perpustakaan POLITEKNIK KESEHATAN RIAU</title>
    <meta name="description" content="Latest updates and statistic charts">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <script src="https://ajax.googleapis.com/ajax/libs/webfont/1.6.16/webfont.js"></script>
    <script>
        WebFont.load({
            google: {"families": ["Poppins:300,400,500,600,700", "Roboto:300,400,500,600,700"]},
            active: function () { sessionStorage.fonts = true; }
        });
    </script>

    <link href="<?php echo base_url(); ?>assets/vendors/base/vendors.bundle.css" rel="stylesheet" type="text/css" />
    <link href="<?php echo base_url(); ?>assets/demo/default/base/style.bundle.css" rel="stylesheet" type="text/css" />
    <link rel="shortcut icon" href="<?php echo base_url(); ?>assets/media/favicon.png" />

    <!-- reCAPTCHA v3 -->
    <script src="https://www.google.com/recaptcha/api.js?render=<?php echo $recaptcha_site_key; ?>"></script>
</head>
<body class="m--skin- m-header--fixed m-header--fixed-mobile m-aside-left--enabled m-aside-left--skin-dark m-aside-left--offcanvas m-footer--push m-aside--offcanvas-default">
    <div class="m-grid m-grid--hor m-grid--root m-page">
        <div class="m-login m-login--singin m-login--5" id="m_login"
             style="background-image: url(<?php echo base_url(); ?>assets/app/media/img/bg/bg-3.jpg);">

            <!-- Panel Kiri -->
            <div class="m-login__wrapper-1 m-portlet-full-height"
                 style="background-image: url(<?php echo base_url(); ?>assets/app/media/img/bg/bg-left.png); background-repeat: no-repeat;">
                <div class="m-login__wrapper-1-1">
                    <div class="m-login__contanier">
                        <div class="m-login__content">
                            <div class="m-login__logo">
                                <a href="#">
                                    <img src="<?php echo base_url(); ?>assets/media/Home.png">
                                </a>
                            </div>
                            <div class="m-login__desc">
                                Login ke Perpustakaan Politeknik Kesehatan Riau
                            </div>
                        </div>
                    </div>
                    <div class="m-login__border"><div></div></div>
                </div>
            </div>

            <!-- Panel Kanan -->
            <div class="m-login__wrapper-2 m-portlet-full-height">
                <div class="m-login__contanier">
                    <div class="m-login__signin">
                        <div class="m-login__head">
                            <h3 class="m-login__title">Login To Your Account</h3>
                        </div>

                        <!-- Flash Message -->
                        <?php if ($this->session->flashdata('flash_message')): ?>
                            <div class="alert alert-danger" style="margin-bottom:15px; font-size:13px;">
                                <?php echo $this->session->flashdata('flash_message'); ?>
                            </div>
                        <?php endif; ?>

                        <form class="m-login__form m-form"
                              action="<?php echo base_url(); ?>home/login2"
                              method="post"
                              id="login_form">

                            <!-- reCAPTCHA token -->
                            <input type="hidden" name="recaptcha_token" id="recaptcha_token">

                            <!-- Honeypot — disembunyikan via CSS -->
                            <input type="text" name="form_botcheck" value=""
                                   style="position:absolute; left:-9999px; opacity:0; height:0;"
                                   tabindex="-1" autocomplete="off">

                            <div class="form-group m-form__group">
                                <input class="form-control m-input"
                                       type="text"
                                       placeholder="Username"
                                       name="username"
                                       autocomplete="off"
                                       maxlength="50"
                                       required>
                            </div>
                            <div class="form-group m-form__group">
                                <input class="form-control m-input m-login__form-input--last"
                                       type="password"
                                       placeholder="Password"
                                       name="password"
                                       maxlength="100"
                                       required>
                            </div>

                            <div class="m-login__form-action">
                                <button id="m_login_signin_submit"
                                        type="submit"
                                        class="btn btn-focus m-btn m-btn--pill m-btn--custom m-btn--air m-login__btn">
                                    Sign In
                                </button>
                            </div>
                        </form>

                        <div class="text-center" style="margin-top:20px;">
                            <p>Atau masuk dengan akun terpusat:</p>
                            <a href="<?php echo $this->config->item('sso_base_url'); ?>/redirect.php?app=<?php echo $this->config->item('sso_app_key'); ?>" class="btn btn-primary btn-lg">
                                Login dengan PKR SSO
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="<?php echo base_url(); ?>assets/vendors/base/vendors.bundle.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/demo/default/base/scripts.bundle.js" type="text/javascript"></script>

    <script>
    document.getElementById('login_form').addEventListener('submit', function (e) {
        e.preventDefault();
        var form = this;
        var btn  = document.getElementById('m_login_signin_submit');

        btn.disabled  = true;
        btn.innerText = 'Memproses...';

        grecaptcha.ready(function () {
            grecaptcha.execute('<?php echo $recaptcha_site_key; ?>', { action: 'login' })
                      .then(function (token) {
                          document.getElementById('recaptcha_token').value = token;
                          form.submit();
                      });
        });
    });
    </script>
</body>
</html>