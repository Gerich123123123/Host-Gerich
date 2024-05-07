<?php
session_start();

require('engine/config.php');

if($_SESSION['auth'] == true) {
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
        <div class="card o-hidden border-0 shadow-lg my-5">
            <div class="card-body p-0">
                <div class="row">
                    <div class="col-lg-5 d-none d-lg-block bg-register-image"></div>
                        <div class="col-lg-7">
                            <div class="p-5">
                                <div class="text-center"><h1 class="h4 text-blue-900 mb-4">Регистрация</h1></div>
                                <form method="post" id="form-reg" class="user">
                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <input type="text" class="form-control form-control-user" id="firstname" name="firstname" placeholder="Имя">
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="text" class="form-control form-control-user" id="lastname" name="lastname" placeholder="Фамилия">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <input type="email" class="form-control form-control-user" id="email" name="email" placeholder="Электронная почта">
                                    </div>
                                    <div class="form-group row">
                                        <div class="col-sm-6 mb-3 mb-sm-0">
                                            <input type="password" class="form-control form-control-user" id="password" name="password" placeholder="Пароль">
                                        </div>
                                        <div class="col-sm-6">
                                            <input type="password" class="form-control form-control-user" id="conpassw" name="conpassw" placeholder="Подтверждения пароля">
                                        </div>
                                    </div>
                                    <div class="card card-body" style="margin-bottom: 1rem;padding: 1rem;">
                                        <center>
                                            <div class="g-recaptcha" data-sitekey="<?php echo $siteckey_captcha ?>"></div>
                                        </center>
                                    </div>
                                    <button type="button" class="btn btn-primary btn-user btn-block" onclick="Registration()">Зарегистрироватся</button>
                                </form>
                                <hr>
                                <div class="text-center">
                                    <a class="small" href="authorization">Авторизация</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <style>
    .bg-register-image {
        background: url(<?php echo $imagelink?>);
        background-position: center;
        background-size: cover
    }
    </style>
    <script>
    function Registration() {
        event.preventDefault();
        $.ajax({
            type: 'POST',
            url: '/includes/request/registration.php',
            data: $('#form-reg').serialize(),
            cache: false,
            success: function(data) { 
                data = $.parseJSON(data);
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
					setInterval(() => { window.location = "/authorization" }, 2600);
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