<?php
session_start();
require('engine/config.php');

$email = $_SESSION['email'];
$pass = $_SESSION['pass'];

$query = $con->prepare("SELECT * FROM users WHERE Email = ? AND Password = ?");
$query->execute([$email, $pass]);
$user = $query->fetch(PDO::FETCH_ASSOC);

if(!$user || $_SESSION['auth'] == false)
{
    header('Location: /authorization');
    exit();       
}
$tquery = $con->prepare("SELECT * FROM transactions WHERE UserID = ?");
$tquery->execute([$user['ID']]);
$transactions = $tquery->fetchAll(PDO::FETCH_ASSOC);

$lquery = $con->prepare("SELECT * FROM log_activity WHERE UserID = ?");
$lquery->execute([$user['ID']]);
$log_activity = $lquery->fetchAll(PDO::FETCH_ASSOC);
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
    <?php
    if($user['Theme'] == 0) echo('<link href="/engine/css/sb-admin-2.min.css" rel="stylesheet">');
    else if($user['Theme'] == 1) echo('<link href="/engine/css/sb-admin-2-dark.min.css" rel="stylesheet">');
    ?>
    <link href="/engine/vendor/datatables/dataTables.bootstrap4.min.css" rel="stylesheet">
</head>
<body id="page-top">
    <div id="wrapper">
        <?php include ('includes/modules/sidebar.php'); ?>
        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                <?php include ('includes/modules/topbar.php'); ?>
                <div class="container-fluid">
                    <h1 class="h3 mb-4 text-gray-800">Профиль</h1>
                    <div class="row">
                        <div class="col-lg-3 mb-4">
                            <div class="card shadow mb-4">
                                <div class="card-header py-3"><h6 class="m-0 font-weight-bold text-primary">Информация</h6></div>
                                <div class="card-body">
                                    <div class="text-center mb-1"><img class="img-profile rounded-circle" src="/engine/img/avatars/<?php echo $user['Avatar']?>.png" width="100" height="100"></div>
                                    <p><center><a class="btn btn-primary btn-sm" href="#" data-toggle="modal" data-target="#Avatar">Выбрать аватар</a><hr class="sidebar-divider">
                                    <strong><span><?php echo $user['FirstName']?></span> <span><?php echo $user['LastName']?></span> (ID: <?php echo $user['ID']?>)</strong><br>
                                    E-Mail: <strong><span><?php echo $user['Email']?></span></strong><br>
                                    Баланс: <strong><span><?php echo $user['Balance']?> </span></strong>рублей
                                    </center>
                                    <p><center><a class="btn btn-success btn-sm" href="#" data-toggle="modal" data-target="#PayModal">Пополнить баланс</a></center> </div>
                                </div>
                            </div>
                            <div class="col-lg-9 mb-4">
                                <div class="card shadow mb-4">
                                    <div class="card-header">
                                        <div class="card-toolbar">
                                            <ul class="nav nav-tabs nav-bold nav-tabs-line">
                                                <li class="nav-item mr-3">
                                                    <a class="nav-link active" data-toggle="tab" href="#settings">
                                                        <span class="nav-icon"><i class="fas fa-cog"></i></span>
                                                        <span class="nav-text font-size-lg">Настройки</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item mr-3">
                                                    <a class="nav-link " data-toggle="tab" href="#payments">
                                                        <span class="nav-icon"><i class="fas fa-retweet"></i></span>
                                                        <span class="nav-text font-size-lg">Транзакции</span>
                                                    </a>
                                                </li>
                                                <li class="nav-item mr-3">
                                                    <a class="nav-link " data-toggle="tab" href="#log_activity">
                                                        <span class="nav-icon"><i class="fas fa-door-open"></i></span>
                                                        <span class="nav-text font-size-lg">Попытки входа</span>
                                                    </a>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="card-body">
                                            <div class="tab-content">
                                                <div class="tab-pane fade show active" id="settings" role="tabpanel">
                                                    <form method="post" id="editprofile">
                                                    Имя
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="FirstName" name="FirstName" value="<?php echo $user['FirstName']?>" placeholder="Введите имя">
                                                    </div>
                                                    Фамилия
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="LastName" name="LastName" value="<?php echo $user['LastName']?>" placeholder="Введите фамилию">
                                                    </div>
                                                    <div class="form-group">
                                                    <label>Тема</label>
                                                        <select class="form-select" id="Theme" name="Theme">
                                                            <option value="0" />Светлая</option>
                                                            <option value="1" />Темная</option>
                                                        </select>
                                                    </div>
                                                    <script type="text/javascript">
                                                        document.getElementById('Theme').value = "<?php echo $user['Theme']?>";
                                                    </script>
                                                    <div class="form-group">
                                                        <div class="custom-control custom-checkbox">
                                                            <input type="checkbox" class="custom-control-input" id="EditPassword" name="EditPassword" onChange="TogglePassword()">
                                                            <label class="custom-control-label" for="EditPassword">Сменить пароль</label>
                                                        </div>
                                                    </div>
                                                    Пароль
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="Password" name="Password" placeholder="Введите пароль" disabled>
                                                    </div>
                                                    Повторите пароль
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" id="RepeatPassword" name="RepeatPassword" placeholder="Подтверждение пароля" disabled>
                                                    </div>
                                                    <button type="button" class="btn btn-primary btn-block" onclick="EditProfile()">Сохранить</button>
                                                </form>
                                            </div>
                                            <div class="tab-pane fade " id="payments" role="tabpanel">
                                                <div class="table-responsive">
                                                    <table id="example" class="table table-striped table-bordered" style="width:100%">
                                                        <thead>
                                                            <tr>
                                                                <th>ID</th>
                                                                <th>Тип</th>
                                                                <th>Сумма</th>
                                                                <th>Дата</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                        <?php foreach($transactions as $data): ?>
                                                        <tr>
                                                            <td><?php echo $data['ID'] ?></td>
                                                            <td>
                                                                <?php 
                                                                    if($data['Status'] == 1) echo('<span class="badge badge-success" data-toggle="tooltip" data-placement="right" title>Пополнение баланса</span>');
                                                                ?>
                                                            </td>
                                                            <td><?php echo $data['Amount'] ?> рублей</td>
                                                            <td><?php echo $data['Date'] ?></td>
                                                        </tr>
                                                        <? endforeach ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                            <div class="tab-pane fade" id="log_activity" role="tabpanel">
												<div class="table-responsive">
                                                    <table id="example2" class="table table-striped table-bordered" style="width:100%">
														<thead>
															<tr>
																<th>ID</th>
																<th>IP</th>
																<th>Статус</th>
																<th>Дата</th>
															</tr>
													    </thead>
                                                        <tbody>
                                                        <?php foreach($log_activity as $data): ?>
                                                        <tr>
                                                            <td><?php echo $data['ID'] ?></td>
                                                            <td><?php echo $data['IP'] ?></td>
                                                            <td>
                                                                <?php 
                                                                    if($data['Status'] == 0) echo('<span class="badge badge-danger" data-toggle="tooltip" data-placement="right" title>Неверный пароль</span>');
                                                                    else if($data['Status'] == 1) echo('<span class="badge badge-success" data-toggle="tooltip" data-placement="right" title>Успешный вход</span>');
                                                                    else if($data['Status'] == 2) echo('<span class="badge badge-warning" data-toggle="tooltip" data-placement="right" title>Выход с аккаунта</span>');
                                                                ?>
                                                            </td>
                                                            <td><?php echo $data['Date'] ?></td>
                                                        </tr>
                                                        <?php endforeach ?>
                                                        </tbody>
													</table>
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
            <?php include ('includes/modules/footer.php'); ?>
        </div>
    </div>
    <?php include ('includes/modules/logout.php'); ?>

    <div class="modal fade" id="Avatar" tabindex="-1" role="dialog" aria-labelledby="AvatarLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="AvatarLabel">Выбрать аватар</h5>
                        <button class="close" type="button" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-1">
                        <button type="button" class="btn" onclick="SelectAvatar(0)" id="btnSelectAvatar"><img id="AvatarSelector_0" class="img-profile rounded-circle" src="/engine/img/avatars/0.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(1)" id="btnSelectAvatar"><img id="AvatarSelector_1" class="img-profile rounded-circle" src="/engine/img/avatars/1.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(2)" id="btnSelectAvatar"><img id="AvatarSelector_2" class="img-profile rounded-circle" src="/engine/img/avatars/2.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(3)" id="btnSelectAvatar"><img id="AvatarSelector_3" class="img-profile rounded-circle" src="/engine/img/avatars/3.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(4)" id="btnSelectAvatar"><img id="AvatarSelector_4" class="img-profile rounded-circle" src="/engine/img/avatars/4.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(5)" id="btnSelectAvatar"><img id="AvatarSelector_5" class="img-profile rounded-circle" src="/engine/img/avatars/5.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(6)" id="btnSelectAvatar"><img id="AvatarSelector_6" class="img-profile rounded-circle" src="/engine/img/avatars/6.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(7)" id="btnSelectAvatar"><img id="AvatarSelector_7" class="img-profile rounded-circle" src="/engine/img/avatars/7.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(8)" id="btnSelectAvatar"><img id="AvatarSelector_8" class="img-profile rounded-circle" src="/engine/img/avatars/8.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(9)" id="btnSelectAvatar"><img id="AvatarSelector_9" class="img-profile rounded-circle" src="/engine/img/avatars/9.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(10)" id="btnSelectAvatar"><img id="AvatarSelector_10" class="img-profile rounded-circle" src="/engine/img/avatars/10.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(11)" id="btnSelectAvatar"><img id="AvatarSelector_11" class="img-profile rounded-circle" src="/engine/img/avatars/11.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(12)" id="btnSelectAvatar"><img id="AvatarSelector_12" class="img-profile rounded-circle" src="/engine/img/avatars/12.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(13)" id="btnSelectAvatar"><img id="AvatarSelector_13" class="img-profile rounded-circle" src="/engine/img/avatars/13.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(14)" id="btnSelectAvatar"><img id="AvatarSelector_14" class="img-profile rounded-circle" src="/engine/img/avatars/14.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(15)" id="btnSelectAvatar"><img id="AvatarSelector_15" class="img-profile rounded-circle" src="/engine/img/avatars/15.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(16)" id="btnSelectAvatar"><img id="AvatarSelector_16" class="img-profile rounded-circle" src="/engine/img/avatars/16.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(17)" id="btnSelectAvatar"><img id="AvatarSelector_17" class="img-profile rounded-circle" src="/engine/img/avatars/17.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(18)" id="btnSelectAvatar"><img id="AvatarSelector_18" class="img-profile rounded-circle" src="/engine/img/avatars/18.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(19)" id="btnSelectAvatar"><img id="AvatarSelector_19" class="img-profile rounded-circle" src="/engine/img/avatars/19.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(20)" id="btnSelectAvatar"><img id="AvatarSelector_20" class="img-profile rounded-circle" src="/engine/img/avatars/20.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(21)" id="btnSelectAvatar"><img id="AvatarSelector_21" class="img-profile rounded-circle" src="/engine/img/avatars/21.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(22)" id="btnSelectAvatar"><img id="AvatarSelector_22" class="img-profile rounded-circle" src="/engine/img/avatars/22.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(23)" id="btnSelectAvatar"><img id="AvatarSelector_23" class="img-profile rounded-circle" src="/engine/img/avatars/23.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(24)" id="btnSelectAvatar"><img id="AvatarSelector_24" class="img-profile rounded-circle" src="/engine/img/avatars/24.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(25)" id="btnSelectAvatar"><img id="AvatarSelector_25" class="img-profile rounded-circle" src="/engine/img/avatars/25.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(26)" id="btnSelectAvatar"><img id="AvatarSelector_26" class="img-profile rounded-circle" src="/engine/img/avatars/26.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(27)" id="btnSelectAvatar"><img id="AvatarSelector_27" class="img-profile rounded-circle" src="/engine/img/avatars/27.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(28)" id="btnSelectAvatar"><img id="AvatarSelector_28" class="img-profile rounded-circle" src="/engine/img/avatars/28.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(29)" id="btnSelectAvatar"><img id="AvatarSelector_29" class="img-profile rounded-circle" src="/engine/img/avatars/29.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(30)" id="btnSelectAvatar"><img id="AvatarSelector_30" class="img-profile rounded-circle" src="/engine/img/avatars/30.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(31)" id="btnSelectAvatar"><img id="AvatarSelector_31" class="img-profile rounded-circle" src="/engine/img/avatars/31.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(32)" id="btnSelectAvatar"><img id="AvatarSelector_32" class="img-profile rounded-circle" src="/engine/img/avatars/32.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(33)" id="btnSelectAvatar"><img id="AvatarSelector_33" class="img-profile rounded-circle" src="/engine/img/avatars/33.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(34)" id="btnSelectAvatar"><img id="AvatarSelector_34" class="img-profile rounded-circle" src="/engine/img/avatars/34.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(35)" id="btnSelectAvatar"><img id="AvatarSelector_35" class="img-profile rounded-circle" src="/engine/img/avatars/35.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(36)" id="btnSelectAvatar"><img id="AvatarSelector_36" class="img-profile rounded-circle" src="/engine/img/avatars/36.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(37)" id="btnSelectAvatar"><img id="AvatarSelector_37" class="img-profile rounded-circle" src="/engine/img/avatars/37.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(38)" id="btnSelectAvatar"><img id="AvatarSelector_38" class="img-profile rounded-circle" src="/engine/img/avatars/38.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(39)" id="btnSelectAvatar"><img id="AvatarSelector_39" class="img-profile rounded-circle" src="/engine/img/avatars/39.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(40)" id="btnSelectAvatar"><img id="AvatarSelector_40" class="img-profile rounded-circle" src="/engine/img/avatars/40.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(41)" id="btnSelectAvatar"><img id="AvatarSelector_41" class="img-profile rounded-circle" src="/engine/img/avatars/41.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(42)" id="btnSelectAvatar"><img id="AvatarSelector_42" class="img-profile rounded-circle" src="/engine/img/avatars/42.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(43)" id="btnSelectAvatar"><img id="AvatarSelector_43" class="img-profile rounded-circle" src="/engine/img/avatars/43.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(44)" id="btnSelectAvatar"><img id="AvatarSelector_44" class="img-profile rounded-circle" src="/engine/img/avatars/44.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(45)" id="btnSelectAvatar"><img id="AvatarSelector_45" class="img-profile rounded-circle" src="/engine/img/avatars/45.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(46)" id="btnSelectAvatar"><img id="AvatarSelector_46" class="img-profile rounded-circle" src="/engine/img/avatars/46.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(47)" id="btnSelectAvatar"><img id="AvatarSelector_47" class="img-profile rounded-circle" src="/engine/img/avatars/47.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(48)" id="btnSelectAvatar"><img id="AvatarSelector_48" class="img-profile rounded-circle" src="/engine/img/avatars/48.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(49)" id="btnSelectAvatar"><img id="AvatarSelector_49" class="img-profile rounded-circle" src="/engine/img/avatars/49.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(50)" id="btnSelectAvatar"><img id="AvatarSelector_50" class="img-profile rounded-circle" src="/engine/img/avatars/50.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(51)" id="btnSelectAvatar"><img id="AvatarSelector_51" class="img-profile rounded-circle" src="/engine/img/avatars/51.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(52)" id="btnSelectAvatar"><img id="AvatarSelector_52" class="img-profile rounded-circle" src="/engine/img/avatars/52.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(53)" id="btnSelectAvatar"><img id="AvatarSelector_53" class="img-profile rounded-circle" src="/engine/img/avatars/53.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(54)" id="btnSelectAvatar"><img id="AvatarSelector_54" class="img-profile rounded-circle" src="/engine/img/avatars/54.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(55)" id="btnSelectAvatar"><img id="AvatarSelector_55" class="img-profile rounded-circle" src="/engine/img/avatars/55.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(56)" id="btnSelectAvatar"><img id="AvatarSelector_56" class="img-profile rounded-circle" src="/engine/img/avatars/56.png" width="100" height="100"></button><button type="button" class="btn" onclick="SelectAvatar(57)" id="btnSelectAvatar"><img id="AvatarSelector_57" class="img-profile rounded-circle" src="/engine/img/avatars/57.png" width="100" height="100"></button>
                    </div>
                    <br>
                    <button type="button" class="btn btn-primary btn-block" onclick="SaveAvatar()" id="btnSaveAvatar">Сохранить</button>
                </div>
            </div>
        </div>
    </div>

    <script>
	function TogglePassword() 
	{
		const status = $('#EditPassword').is(':checked');
		if(status) 
		{
			$('#Password').prop('disabled', false);
			$('#RepeatPassword').prop('disabled', false);
		} 
		else 
		{
			$('#Password').prop('disabled', true);
			$('#RepeatPassword').prop('disabled', true);
		}
	}
    function EditProfile()
    {
        const checkbox = $('#EditPassword').is(':checked');

		event.preventDefault();
		$.ajax({
			type: 'POST',
            url: '/includes/request/editprofile.php',
			data: $('#editprofile').serialize()+'&edit_password='+checkbox,
			cache: false,
			success: function(data) 
			{ 
				if(data.search('#Ошибка') != -1) 
				{
					data = data.replace('#Ошибка', ''); 
                    new cuteToast({
                        type: 'error', 
                        message: data,
                        timer: 5000
                    })
				  	event.preventDefault();
				  	return false;             
				}
				else
				{
                    new cuteToast({
                        type: 'success', 
                        message: 'Изменения успешно сохранены!',
                        timer: 2500
                    })                        
					setInterval(() => { window.location = "/profile" }, 2600);
				}
			}
		});    
    }
    var selected_avatar = -1;
	function SelectAvatar(avatar)
	{
		if(selected_avatar != -1) document.getElementById('AvatarSelector_'+selected_avatar).style="";
		selected_avatar = avatar;
		document.getElementById('AvatarSelector_'+avatar).style="border: 3px solid #1CC88A;";
		return 1;
	}
    function SaveAvatar()
    {
		event.preventDefault();
		$.ajax({
			type: 'POST',
            url: '/includes/request/editprofile.php',
			data: 'action=edit_avatar&AvatarID='+selected_avatar,
			cache: false,
			success: function(data) 
			{ 
				if(data.search('#Ошибка') != -1) 
				{
					data = data.replace('#Ошибка', ''); 
                    new cuteToast({
                        type: 'error', 
                        message: data,
                        timer: 5000
                    })
				  	event.preventDefault();
				  	return false;             
				}
				else
				{
                    new cuteToast({
                        type: 'success', 
                        message: 'Изменения успешно сохранены!',
                        timer: 2500
                    })                        
					setInterval(() => { window.location = "/profile" }, 2600);
				}
			}
		});    
    }
    </script>
    <script src="/engine/vendor/jquery/jquery.min.js"></script>
    <script src="/engine/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="/engine/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="/engine/vendor/datatables/dataTables.bootstrap4.js"></script>
    <script src="/engine/vendor/datatables/dataTables.bootstrap4.min.js"></script>
    <script src="/engine/vendor/jquery-easing/jquery.easing.min.js"></script>
    <script src="/engine/js/sb-admin-2.min.js"></script>
    <script src="/engine/vendor/chart.js/Chart.min.js"></script>
    <script src="/engine/js/demo/chart-area-demo.js"></script>
    <script src="/engine/js/demo/chart-pie-demo.js"></script>
    <script>
    $(document).ready( function () {
        var table = $('#example').DataTable({
            language: {
            url: "//cdn.datatables.net/plug-ins/1.12.1/i18n/ru.json"
            }
        });
    });
    $(document).ready( function () {
        var table = $('#example2').DataTable({
            language: {
            url: "//cdn.datatables.net/plug-ins/1.12.1/i18n/ru.json"
            }
        });
    });
    </script>
</body>
</html>