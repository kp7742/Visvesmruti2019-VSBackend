<?php
session_start();

require_once '../api/global.php';
require_once '../api/utils.php';
require_once '../api/VSAdmins.php';
require_once '../api/VSLogin.php';
require_once '../api/VSSession.php';

function Logs($message, $aid = null, $apikey = null)
{
    addSessionLog($aid, $apikey, $message);
}

$alertevent = false;
$alertmsg = "";

if ($GLOBALS['Maintenance'] == true) {
    $alertevent = true;
    $alertmsg = "Server is in Maintenance!";
} else if (isset($_SESSION['login_user']) && isset($_SESSION['apikey'])) {
    $email = $_SESSION['login_user'];
    $apikey = $_SESSION['apikey'];
    $admin = getAdmin($email);

    /*$logindata = getLoginByToken($apikey);
    if (empty($logindata)) {
        Logs("Admin Panel Login: Already Login in Another Device", $admin[0]['AID'], $apikey);
        session_unset();
        $alertevent = true;
        $alertmsg = "You Already Login in Another Device!";
    }*/

    Logs("Admin Panel Login: Login Back Successfully", $admin[0]['AID'], $apikey);
    if ($admin[0]["Department"] == "All") {
        header("Location: DashboardAdmins.php");
    } else if ($admin[0]["EventID"] == null) {
        header("Location: DashboardDept.php");
    } else {
        header("Location: DashboardEvent.php");
    }
    exit();
} else if (isset($_POST['email']) && isset($_POST['pass'])) {
    $email = $_POST['email'];
    $admin = getAdmin($email);
    if (!empty($admin)) {
        if ($admin[0]['isFaculty'] == 1) {
            if (password_verify($_POST['pass'], $admin[0]['Password'])) {
                $adminid = $admin[0]['AID'];
                $apikey = genApiKey();

                $login = getLogin($adminid);
                if (!empty($login)) {
                    updateLogin($apikey, $currtimestamp, $adminid);
                } else {
                    addLogin($adminid, $apikey);
                }

                Logs("Admin Panel Login: Login Successfully", $adminid, $apikey);

                $_SESSION['login_user'] = $email;
                $_SESSION['apikey'] = $apikey;

                if ($admin[0]["Department"] == "All") {
                    header("Location: DashboardAdmins.php");
                } else if ($admin[0]["EventID"] == null) {
                    header("Location: DashboardDept.php");
                } else {
                    header("Location: DashboardEvent.php");
                }
                exit();
            } else {
                Logs("Admin Panel Login: " . $email . " Entered Wrong Password");
                $alertevent = true;
                $alertmsg = "You Entered Wrong Password!";
            }
        } else {
            Logs("Admin Panel Login: " . $email . " is Not Faculty");
            $alertevent = true;
            $alertmsg = "You Need Faculty Access!";
        }
    } else {
        Logs("Admin Panel Login: " . $email . " is Not Registered");
        $alertevent = true;
        $alertmsg = "Your is Email Not Registered!";
    }
}
?>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Login | Visvesmruti Admin Panel</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- favicon
		============================================ -->
    <!-- <link rel="shortcut icon" type="image/x-icon" href="img/favicon.ico"> -->
    <!-- Google Fonts
		============================================ -->
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,700,700i,800" rel="stylesheet">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <!-- Bootstrap CSS
		============================================ -->
    <link rel="stylesheet" href="css/font-awesome.min.css">
    <!-- form CSS
		============================================ -->
    <link rel="stylesheet" href="css/form.css">
    <!-- style CSS
		============================================ -->
    <link rel="stylesheet" href="css/style.css">
    <!-- responsive CSS
		============================================ -->
    <link rel="stylesheet" href="css/responsive.css">
</head>

<body>
<!-- login Start-->
<div class="login-form-area mg-t-40 mg-b-40">
    <div class="container">
        <div class="row">
            <div class="col-lg-4"></div>
            <form class="adminpro-form" id="adminlogin" action="" method="post">
                <div class="col-lg-4">
                    <div class="login-bg">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="logo">
                                    <a href="index.html"><img src="img/logo/logo.png" alt=""/>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="login-title">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="login-input-head">
                                    <p>E-mail</p>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="login-input-area">
                                    <input id="email" type="email" name="email"/>
                                    <i class="fa fa-envelope login-user" aria-hidden="true"></i>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4">
                                <div class="login-input-head">
                                    <p>Password</p>
                                </div>
                            </div>
                            <div class="col-lg-8">
                                <div class="login-input-area">
                                    <input id="pass" type="password" name="pass"/>
                                    <i class="fa fa-lock login-user"></i>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4"></div>
                            <div class="col-lg-8">
                                <div class="login-button-pro">
                                    <button type="submit" class="login-button login-button-lg">Log in</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- login End-->
<!-- jquery
    ============================================ -->
<script src="js/vendor/jquery-1.11.3.min.js"></script>
<!-- bootstrap JS
    ============================================ -->
<script src="js/bootstrap.min.js"></script>
<!-- form validate JS
    ============================================ -->
<script src="js/jquery.form.min.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="js/form-active.js"></script>
<!-- main JS
    ============================================ -->
<script src="js/main.js"></script>
<?php
if ($alertevent) {
    echo '<script type="text/javascript">alert("' . $alertmsg . '")</script>';
}
?>
</body>

</html>