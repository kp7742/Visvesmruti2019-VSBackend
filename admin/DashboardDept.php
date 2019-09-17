<?php
session_start();

require_once '../api/global.php';
require_once '../api/utils.php';
require_once '../api/VSLogin.php';
require_once '../api/VSEvents.php';
require_once '../api/VSAdmins.php';
require_once '../api/VSSession.php';
require_once '../api/VSEventReg.php';
require_once '../api/VSParticipants.php';

if (!isset($_SESSION['login_user']) || !isset($_SESSION['apikey'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET["Logout"]) || $GLOBALS['Maintenance'] == true) {
    session_unset();
    header("Location: index.php");
    exit();
}

$email = $_SESSION['login_user'];
$apikey = $_SESSION['apikey'];
$admin = getAdmin($email);

/*$logindata = getLoginByToken($apikey);
if (empty($logindata)) {
    header("Location: index.php");
    exit();
}*/

if ($admin[0]["EventID"] != null || $admin[0]["Department"] == "All") {
    header("Location: index.php");
    exit();
}
$department = $admin[0]["Department"];

$eventid = 0;
if (isset($_GET["evid"])) {
    $eventid = $_GET["evid"];
}

$totalunpaid = 0;
$totalpaid = 0;
$totalcounts = 0;
$totalteam = 0;
$eventlist = array();
$teamarr = array();

$events = getAllEvents();
foreach ($events as $event) {
    if ($department != $event["EVDepartment"]) {
        continue;
    }

    array_push($eventlist, $event);

    if ($eventid != 0 && $eventid != $event["EVID"]) {
        continue;
    }

    $evententries = getEventEntryByE($event["EVID"]);
    $eventprice = $event["EVPrice"];

    foreach ($evententries as $entry) {
        if ($event["isTeamEvent"] == 1) {
            if ($entry["isTeamLeader"] == 1) {
                $teamentries = getEventEntryByCode($entry["ERCode"]);
                $totalprice = ($event["isSinglePrice"] == 1) ? $eventprice : ($eventprice * count($teamentries));
                if ($entry["isPaid"] == 1) {
                    $totalpaid += $totalprice;
                } else {
                    $totalunpaid += $totalprice;
                }
                $totalcounts += count($teamentries);
                $totalteam++;
                array_push($teamarr, $teamentries);
            }
        } else {
            if ($entry["isPaid"] == 1) {
                $totalpaid += $eventprice;
            } else {
                $totalunpaid += $eventprice;
            }
            $totalcounts++;
        }
    }
}
$totalfees = $totalpaid + $totalunpaid;
?>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Dashboard | Visvesmruti - Admin Panel</title>
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
    <!-- adminpro icon CSS
		============================================ -->
    <link rel="stylesheet" href="css/adminpro-custon-icon.css">
    <!-- meanmenu icon CSS
		============================================ -->
    <link rel="stylesheet" href="css/meanmenu.min.css">
    <!-- mCustomScrollbar CSS
		============================================ -->
    <link rel="stylesheet" href="css/jquery.mCustomScrollbar.min.css">
    <!-- animate CSS
		============================================ -->
    <link rel="stylesheet" href="css/animate.css">
    <!-- jvectormap CSS
		============================================ -->
    <link rel="stylesheet" href="css/jvectormap/jquery-jvectormap-2.0.3.css">
    <!-- normalize CSS
		============================================ -->
    <link rel="stylesheet" href="css/data-table/bootstrap-table.css">
    <link rel="stylesheet" href="css/data-table/bootstrap-editable.css">
    <!-- normalize CSS
		============================================ -->
    <link rel="stylesheet" href="css/normalize.css">
    <!-- charts CSS
		============================================ -->
    <link rel="stylesheet" href="css/c3.min.css">
    <!-- style CSS
		============================================ -->
    <link rel="stylesheet" href="css/style.css">
    <!-- responsive CSS
		============================================ -->
    <link rel="stylesheet" href="css/responsive.css">
    <!-- modernizr JS
		============================================ -->
    <script src="js/vendor/modernizr-2.8.3.min.js"></script>
    <script type="text/javascript">const filename = "VSData-<?php echo $department . "-" . ($eventid > 0 ? getEventByID($eventid)[0]["EVName"] : "All");?>"</script>
</head>

<body>
<!-- Header top area start-->
<div class="header-top-area">
    <div class="container">
        <div class="row">
            <div class="col-lg-2 col-md-3 col-sm-6 col-xs-12">
                <div class="admin-logo">
                    <a href=""><img src="img/logo/log.png" alt=""/>
                    </a>
                </div>
            </div>
            <div class="col-lg-7 col-md-5 col-sm-0 col-xs-12">
                <div class="header-top-menu">
                    <ul class="nav navbar-nav mai-top-nav">
                        <li class="nav-item"><a href="index.php" class="nav-link">Home</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a href="#" data-toggle="dropdown" role="button" aria-expanded="false"
                               class="nav-link dropdown-toggle">View Lists
                                <span class="angle-down-topmenu"><i class="fa fa-angle-down"></i></span>
                            </a>
                            <div role="menu" class="dropdown-menu animated flipInX">
                                <a href="Participants.php" class="dropdown-item">Participants List</a>
                                <a href="Events.php" class="dropdown-item">Events List</a>
                            </div>
                        </li>
                        <li class="nav-item dropdown">
                            <a href="#" data-toggle="dropdown" role="button" aria-expanded="false"
                               class="nav-link dropdown-toggle">Events <span class="angle-down-topmenu"><i
                                            class="fa fa-angle-down"></i></span></a>
                            <div role="menu" class="dropdown-menu animated flipInX">
                                <a href="DashboardDept.php" class="dropdown-item">All</a>
                                <?php
                                foreach ($eventlist as $event) {
                                    echo '<a href="DashboardDept.php?evid=' . $event["EVID"] . '" class="dropdown-item">' . $event["EVName"] . '</a>';
                                }
                                ?>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
            <div class="col-lg-3 col-md-9 col-sm-6 col-xs-12">
                <div class="header-right-info">
                    <ul class="nav navbar-nav mai-top-nav header-right-menu">
                        <li class="nav-item">
                            <a href="#" data-toggle="dropdown" role="button" aria-expanded="false"
                               class="nav-link dropdown-toggle">
                                <span class="admin-name">
                                    <?php echo $admin[0]['Name'] . "</br>" . $department . " Department" . ($eventid > 0 ? "</br>" . getEventByID($eventid)[0]["EVName"] : ""); ?>
                                </span>
                                <span class="angle-down-topmenu"><i class="fa fa-angle-down"></i></span>
                            </a>
                            <ul role="menu" class="dropdown-header-top author-log dropdown-menu animated flipInX">
                                <li><a href="DashboardDept.php?Logout">Log Out</a></li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- income order visit user Start -->
<div class="income-order-visit-user-area mg-t-40">
    <div class="container">
        <div class="row">
            <div class="col-lg-3">
                <div class="income-dashone-total shadow-reset nt-mg-b-30">
                    <div class="income-title">
                        <div class="main-income-head">
                            <h2>Registration</h2>
                            <div class="main-income-phara">
                                <p>Till Now</p>
                            </div>
                        </div>
                    </div>
                    <div class="income-dashone-pro">
                        <div class="income-rate-total">
                            <div class="price-adminpro-rate">
                                <h3><span class="counter"><?php echo $totalcounts; ?></span></h3>
                            </div>
                        </div>
                        <div class="income-range">
                            <p>Total Registration<?php echo($totalteam > 0 ? ' (' . $totalteam . ' Teams)' : ''); ?></p>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="income-dashone-total shadow-reset nt-mg-b-30">
                    <div class="income-title">
                        <div class="main-income-head">
                            <h2>Fee Collected</h2>
                            <div class="main-income-phara order-cl">
                                <p>Till Now</p>
                            </div>
                        </div>
                    </div>
                    <div class="income-dashone-pro">
                        <div class="income-rate-total">
                            <div class="price-adminpro-rate">
                                <h3><span>&#8377; </span><span class="counter"><?php echo $totalpaid; ?></span></h3>
                            </div>
                        </div>
                        <div class="income-range order-cl">
                            <p>Fee Collected</p>
                        </div>

                        <div class="clear"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="income-dashone-total shadow-reset nt-mg-b-30">
                    <div class="income-title">
                        <div class="main-income-head">
                            <h2>Fee Due</h2>
                            <div class="main-income-phara visitor-cl">
                                <p>Till Now</p>
                            </div>
                        </div>
                    </div>
                    <div class="income-dashone-pro">
                        <div class="income-rate-total">
                            <div class="price-adminpro-rate">
                                <h3><span>&#8377; </span><span class="counter"><?php echo $totalunpaid; ?></span></h3>
                            </div>
                        </div>
                        <div class="income-range visitor-cl">
                            <p>Fee remain to Collect</p>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="income-dashone-total shadow-reset nt-mg-b-30">
                    <div class="income-title">
                        <div class="main-income-head">
                            <h2>Total Amount</h2>
                            <div class="main-income-phara low-value-cl">
                                <p>Till Now</p>
                            </div>
                        </div>
                    </div>
                    <div class="income-dashone-pro">
                        <div class="income-rate-total">
                            <div class="price-adminpro-rate">
                                <h3><span>&#8377; </span><span class="counter"><?php echo $totalfees; ?></span></h3>
                            </div>
                        </div>
                        <div class="income-range low-value-cl">
                            <p>Total Amount to Collect</p>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Data table area Start-->
<div class="admin-dashone-data-table-area mg-b-40">
    <div class="container  tab-content">
        <div class="row tab-pane in active" id="all">
            <div class="col-lg-12">
                <div class="sparkline8-list shadow-reset">
                    <div class="sparkline8-hd">
                        <div class="main-sparkline8-hd">
                            <h1>Participants List</h1>
                            <div class="sparkline8-outline-icon">
                                <span class="sparkline8-collapse-link"><i class="fa fa-chevron-up"></i></span>
                                <span class="sparkline8-collapse-close"><i class="fa fa-times"></i></span>
                            </div>
                        </div>
                    </div>
                    <div class="sparkline8-graph">
                        <div class="datatable-dashv1-list custom-datatable-overright">
                            <table id="table" data-toggle="table" data-pagination="false" data-search="true"
                                   data-show-columns="true" data-show-pagination-switch="false"
                                   data-show-refresh="false"
                                   data-key-events="true" data-resizable="true" data-cookie="true"
                                   data-cookie-id-table="saveId" data-show-export="true" data-click-to-select="true"
                                   data-toolbar="#toolbar">
                                <thead>
                                <tr>
                                    <th data-field="id">Event ID</th>
                                    <th data-field="name">Participant Name</th>
                                    <th data-field="email">Email</th>
                                    <th data-field="phone">Phone</th>
                                    <th data-field="college">College</th>
                                    <th data-field="department">Department</th>
                                    <th data-field="semester">Semester</th>
                                    <th data-field="fee">Fee Paid</th>
                                    <th data-field="teamleader">TeamLeader</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $events = getAllEvents();
                                foreach ($events as $event) {
                                    if ($department != $event["EVDepartment"]) {
                                        continue;
                                    }

                                    if ($eventid != 0 && $eventid != $event["EVID"]) {
                                        continue;
                                    }

                                    echo "<tr>" .
                                        "<td>-</td>" .
                                        "<td>" . $event["EVDepartment"] . "</td>" .
                                        "<td>" . $event["EVName"] . "</td>" .
                                        "<td>-</td>" .
                                        "<td>-</td>" .
                                        "<td>-</td>" .
                                        "<td>-</td>" .
                                        "<td>-</td>" .
                                        "<td>-</td>" .
                                        "</tr>";
                                    echo "<tr>" .
                                        "<td>-</td>" .
                                        "<td>-</td>" .
                                        "<td>-</td>" .
                                        "<td>-</td>" .
                                        "<td>-</td>" .
                                        "<td>-</td>" .
                                        "<td>-</td>" .
                                        "<td>-</td>" .
                                        "<td>-</td>" .
                                        "</tr>";

                                    $totalunpaid = 0;
                                    $totalpaid = 0;
                                    $totalcounts = 0;
                                    $totalteam = 0;

                                    $evententries = getEventEntryByE($event["EVID"]);
                                    $eventprice = $event["EVPrice"];

                                    foreach ($evententries as $entry) {
                                        if ($event["isTeamEvent"] == 1) {
                                            if ($entry["isTeamLeader"] == 1) {
                                                $teamentries = getEventEntryByCode($entry["ERCode"]);
                                                $totalprice = ($event["isSinglePrice"] == 1) ? $eventprice : ($eventprice * count($teamentries));
                                                if ($entry["isPaid"] == 1) {
                                                    $totalpaid += $totalprice;
                                                } else {
                                                    $totalunpaid += $totalprice;
                                                }
                                                $totalcounts += count($teamentries);
                                                $totalteam++;
                                                foreach ($teamentries as $team) {
                                                    $parti = getParticipantByID($team["PID"]);
                                                    echo "<tr>" .
                                                        "<td>" . $team["ERCode"] . "</td>" .
                                                        "<td>" . $parti[0]["FirstName"] . " " . $parti[0]["LastName"] . "</td>" .
                                                        "<td>" . $parti[0]["EMail"] . "</td>" .
                                                        "<td>" . $parti[0]["Mobile"] . "</td>" .
                                                        "<td>" . $parti[0]["College"] . "</td>" .
                                                        "<td>" . $parti[0]["Department"] . "</td>" .
                                                        "<td>" . $parti[0]["Semester"] . "</td>" .
                                                        '<td class="datatable-ct"><i class="' . ($team["isPaid"] == 1 ? 'fa fa-check' : 'fa fa-times') . '"></i></td>' .
                                                        '<td>' . ($team["isTeamLeader"] == 1 ? 'Yes' : '') . '</td>' .
                                                        "</tr>";
                                                }
                                                echo "<tr>" .
                                                    "<td>-</td>" .
                                                    "<td>-</td>" .
                                                    "<td>-</td>" .
                                                    "<td>-</td>" .
                                                    "<td>-</td>" .
                                                    "<td>-</td>" .
                                                    "<td>-</td>" .
                                                    "<td>-</td>" .
                                                    "<td>-</td>" .
                                                    "</tr>";
                                            }
                                        } else {
                                            if ($entry["isPaid"] == 1) {
                                                $totalpaid += $eventprice;
                                            } else {
                                                $totalunpaid += $eventprice;
                                            }
                                            $totalcounts++;
                                            $parti = getParticipantByID($entry["PID"]);
                                            echo "<tr>" .
                                                "<td>" . $entry["ERCode"] . "</td>" .
                                                "<td>" . $parti[0]["FirstName"] . " " . $parti[0]["LastName"] . "</td>" .
                                                "<td>" . $parti[0]["EMail"] . "</td>" .
                                                "<td>" . $parti[0]["Mobile"] . "</td>" .
                                                "<td>" . $parti[0]["College"] . "</td>" .
                                                "<td>" . $parti[0]["Department"] . "</td>" .
                                                "<td>" . $parti[0]["Semester"] . "</td>" .
                                                '<td class="datatable-ct"><i class="' . ($entry["isPaid"] == 1 ? 'fa fa-check' : 'fa fa-times') . '"></i></td>' .
                                                '<td>-</td>' .
                                                "</tr>";
                                        }
                                    }
                                    echo "<tr>" .
                                        "<td>Total Registration</td>" .
                                        "<td>" . $totalcounts . ($totalteam > 0 ? ' (' . $totalteam . ' Teams)' : '') . "</td>" .
                                        "<td>Fee Collected</td>" .
                                        "<td>" . $totalpaid . "</td>" .
                                        "<td>Fee Uncollected</td>" .
                                        "<td>" . $totalunpaid . "</td>" .
                                        "<td>Total Amount</td>" .
                                        "<td>" . ($totalpaid + $totalunpaid) . "</td>" .
                                        "<td>-</td>" .
                                        "</tr>";
                                    echo "<tr>" .
                                        "<td>-</td>" .
                                        "<td>-</td>" .
                                        "<td>-</td>" .
                                        "<td>-</td>" .
                                        "<td>-</td>" .
                                        "<td>-</td>" .
                                        "<td>-</td>" .
                                        "<td>-</td>" .
                                        "<td>-</td>" .
                                        "</tr>";
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Data table area End-->
<!-- jquery
    ============================================ -->
<script src="js/vendor/jquery-1.11.3.min.js"></script>
<!-- bootstrap JS
    ============================================ -->
<script src="js/bootstrap.min.js"></script>
<!-- meanmenu JS
    ============================================ -->
<script src="js/jquery.meanmenu.js"></script>
<!-- mCustomScrollbar JS
    ============================================ -->
<script src="js/jquery.mCustomScrollbar.concat.min.js"></script>
<!-- sticky JS
    ============================================ -->
<script src="js/jquery.sticky.js"></script>
<!-- scrollUp JS
    ============================================ -->
<script src="js/jquery.scrollUp.min.js"></script>
<!-- scrollUp JS
    ============================================ -->
<script src="js/wow/wow.min.js"></script>
<!-- counterup JS
    ============================================ -->
<script src="js/counterup/jquery.counterup.min.js"></script>
<script src="js/counterup/waypoints.min.js"></script>
<script src="js/counterup/counterup-active.js"></script>
<!-- jvectormap JS
    ============================================ -->
<script src="js/jvectormap/jquery-jvectormap-2.0.2.min.js"></script>
<script src="js/jvectormap/jquery-jvectormap-world-mill-en.js"></script>
<script src="js/jvectormap/jvectormap-active.js"></script>
<!-- peity JS
    ============================================ -->
<script src="js/peity/jquery.peity.min.js"></script>
<script src="js/peity/peity-active.js"></script>
<!-- sparkline JS
    ============================================ -->
<script src="js/sparkline/jquery.sparkline.min.js"></script>
<script src="js/sparkline/sparkline-active.js"></script>
<!-- flot JS
    ============================================ -->
<script src="js/flot/jquery.flot.js"></script>
<script src="js/flot/jquery.flot.tooltip.min.js"></script>
<script src="js/flot/jquery.flot.spline.js"></script>
<script src="js/flot/jquery.flot.resize.js"></script>
<script src="js/flot/jquery.flot.pie.js"></script>
<script src="js/flot/jquery.flot.symbol.js"></script>
<script src="js/flot/jquery.flot.time.js"></script>
<script src="js/flot/dashtwo-flot-active.js"></script>
<!-- data table JS
    ============================================ -->
<script src="js/data-table/bootstrap-table.js"></script>
<script src="js/data-table/tableExport.js"></script>
<script src="js/data-table/data-table-active.js"></script>
<script src="js/data-table/bootstrap-table-editable.js"></script>
<script src="js/data-table/bootstrap-editable.js"></script>
<script src="js/data-table/bootstrap-table-resizable.js"></script>
<script src="js/data-table/colResizable-1.5.source.js"></script>
<script src="js/data-table/bootstrap-table-export.js"></script>
<!-- main JS
    ============================================ -->
<script src="js/main.js"></script>
</body>

</html>