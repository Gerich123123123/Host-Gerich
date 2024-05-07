<?php
session_start();

require('engine/config.php');

// Проверяем, авторизован ли пользователь
if(isset($_COOKIE['session_id']) && $_COOKIE['session_id'] == session_id()) {
    header('Location: /');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
    
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title><?php echo $panelname?> | Магазин скриптов SAMP!</title>
    <link rel="icon" type="image/x-icon" href="/engine/img/logo.ico">
    <link href="/engine/vendor/fontawesome-free/css/all.min.css" rel="stylesheet" type="text/css">
    <link rel="stylesheet" href="/engine/alert/style.css" />
    <script src="/engine/alert/cute-alert.js"></script>
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">
    <link href="/engine/css/sb-admin-2-dark.min.css" rel="stylesheet">
    <link href="/engine/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>

<body class="bg-gradient-primary">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-10 col-lg-12 col-md-9">
                <div class="card o-hidden border-0 shadow-lg my-5">
                    <div class="card-body p-0">
                        <div class="row">
                            <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                                <div class="col-lg-6">
                                    <div class="p-5">
                                        <div class="text-center"><h1 class="h4 text-blue-900 mb-4">Авторизация</h1></div>
                                        <form method="post" id="form-auth" class="user">
                                            <div class="form-group">
                                                <input type="email" class="form-control form-control-user" id="email" name="email" aria-describedby="emailHelp" placeholder="Электронная почта">
                                            </div>
                                            <div class="form-group">
                                                <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Пароль">
                                            </div>
                                            <div class="form-group">
                                                <div class="custom-control custom-checkbox small">
                                                    <input type="checkbox" class="custom-control-input" id="RememberMe" name="RememberMe">
                                                    <label class="custom-control-label" for="RememberMe">Запомнить меня</label>
                                                </div>
                                            </div>
                                            <div class="card card-body" style="margin-bottom: 1rem;padding: 1rem;">
                                                <center>
                                                    <div class="g-recaptcha" data-sitekey="<?php echo $siteckey_captcha ?>"></div>
                                                </center>
                                            </div>
                                            <button type="button" class="btn btn-primary btn-user btn-block" onclick="Authorization()">Войти</button>
                                        </form>
                                        <hr>
                                        <div class="text-center">
                                            <a class="small" href="registration">Регистрация</a>
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
    <style>
    .bg-login-image {
        background: url(<?php echo $imagelink?>);
        background-position: center;
        background-size: cover
    }
    </style>

<script>
    function Authorization() {
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: '/includes/request/authorization.php',
            data: $('#form-auth').serialize(),
            cache: false,
            success: function (data) {
                data = $.parseJSON(data);
                console.log(data);
                switch (data.status) {
                    case 'error':
                        new cuteToast({
                            type: 'error',
                            message: data.error,
                            timer: 5000
                        });
                        $('button[type=submit]').prop('disabled', false);
                        break;
                    case 'success':
                        new cuteToast({
                            type: 'success',
                            message: data.success,
                            timer: 2500
                        });
                        setInterval(() => {
                            window.location = "/";
                        }, 2600);
                        break;
                }
            }
        });
    }
</script>

    <script src="/engine/vendor/jquery/jquery.min.js"></script>
    <script src="/engine/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/engine/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="/engine/js/sb-admin-2.min.js"></script>
    <script src="/engine/vendor/chart.js/Chart.min.js"></script>
    <script src="/engine/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="/engine/vendor/datatables/dataTables.bootstrap4.js"></script>
    <script src="/engine/vendor/datatables/dataTables.bootstrap4.min.js"></script>
</body>
</html>