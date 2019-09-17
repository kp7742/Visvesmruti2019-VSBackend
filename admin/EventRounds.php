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
require_once '../api/VSEventRounds.php';

function Logs($message, $aid = null, $apikey = null)
{
    addSessionLog($aid, $apikey, $message);
}

if (!isset($_SESSION['login_user']) || !isset($_SESSION['apikey'])) {
    header("Location: index.php");
    exit();
}

if (isset($_GET["Logout"]) || $GLOBALS['Maintenance'] == true) {
    session_unset();
    header("Location: index.php");
    exit();
}

$alertevent = false;
$alertmsg = "";

$email = $_SESSION['login_user'];
$apikey = $_SESSION['apikey'];
$admin = getAdmin($email)[0];

/*$logindata = getLoginByToken($apikey);
if (empty($logindata)) {
    header("Location: index.php");
    exit();
}*/

if ($admin["Department"] != "Computer" || $admin["EventID"] == null) {
    header("Location: index.php");
    exit();
}

$event = getEventByID($admin["EventID"])[0];
$eventregs = getEventEntryByE($event["EVID"]);
$coords = getAllAdmins();

if (isset($_POST["r1lock"])) {
    foreach ($eventregs as $entry) {
        $rounddat = getRoundDetails($entry["ERID"]);
        if (!empty($rounddat)) {
            if ($rounddat[0]["isLockedRound1"] == 0) {
                $alertevent = true;
                setRound1Lock(true, $entry["ERID"]);
            }
        }
    }
    if ($alertevent) {
        Logs("Locked Round 1 Winners", $admin["AID"], $apikey);
        $alertmsg = "Round1 Winner Selection is Locked";
    }
}

if (isset($_POST["r1select"]) && isset($_POST["code"])) {
    $events = getEventEntryByCode($_POST["code"]);
    foreach ($events as $entry) {
        $rounddat = getRoundDetails($entry["ERID"]);
        if (!empty($rounddat)) {
            if ($rounddat[0]["isWinRound1"] == 0) {
                $alertevent = true;
                setWonRounds(($rounddat[0]["WonRounds"] + 1), $entry["ERID"]);
                setRound1Winner(true, $entry["ERID"]);
            }
        }
    }
    if ($alertevent) {
        $parti = getParticipantByID($events[0]["PID"])[0];
        Logs("Set " . $parti["EMail"] . " As Round 1 Winners", $admin["AID"], $apikey);
        if ($events[0]["isTeam"] == 0) {
            $alertmsg = $parti["FirstName"] . " " . $parti["LastName"] . " is Round1 Winner";
        } else {
            $alertmsg = $parti["FirstName"] . " " . $parti["LastName"] . "'s Team is Round1 Winner";
        }
    }
}

if (isset($_POST["r2lock"])) {
    foreach ($eventregs as $entry) {
        $rounddat = getRoundDetails($entry["ERID"]);
        if (!empty($rounddat)) {
            if ($rounddat[0]["isLockedRound2"] == 0) {
                $alertevent = true;
                setRound2Lock(true, $entry["ERID"]);
            }
        }
    }
    if ($alertevent) {
        Logs("Locked Round 2 Winners", $admin["AID"], $apikey);
        $alertmsg = "Round2 Winner Selection is Locked";
    }
}

if (isset($_POST["r2select"]) && isset($_POST["code"])) {
    $events = getEventEntryByCode($_POST["code"]);
    foreach ($events as $entry) {
        $rounddat = getRoundDetails($entry["ERID"]);
        if (!empty($rounddat)) {
            if ($rounddat[0]["isWinRound2"] == 0) {
                $alertevent = true;
                setWonRounds(($rounddat[0]["WonRounds"] + 1), $entry["ERID"]);
                setRound2Winner(true, $entry["ERID"]);
            }
        }
    }
    if ($alertevent) {
        $parti = getParticipantByID($events[0]["PID"])[0];
        Logs("Set " . $parti["EMail"] . " As Round 2 Winners", $admin["AID"], $apikey);
        if ($events[0]["isTeam"] == 0) {
            $alertmsg = $parti["FirstName"] . " " . $parti["LastName"] . " is Round2 Winner";
        } else {
            $alertmsg = $parti["FirstName"] . " " . $parti["LastName"] . "'s Team is Round2 Winner";
        }
    }
}

if (isset($_POST["r3lock"])) {
    foreach ($eventregs as $entry) {
        $rounddat = getRoundDetails($entry["ERID"]);
        if (!empty($rounddat)) {
            if ($rounddat[0]["isLockedRound3"] == 0) {
                $alertevent = true;
                setRound3Lock(true, $entry["ERID"]);
            }
        }
    }
    if ($alertevent) {
        Logs("Locked Round 3 Winners", $admin["AID"], $apikey);
        $alertmsg = "Round3 Winner Selection is Locked";
    }
}

if (isset($_POST["r3select"]) && isset($_POST["code"])) {
    $events = getEventEntryByCode($_POST["code"]);
    foreach ($events as $entry) {
        $rounddat = getRoundDetails($entry["ERID"]);
        if (!empty($rounddat)) {
            if ($rounddat[0]["isWinRound3"] == 0) {
                $alertevent = true;
                setWonRounds(($rounddat[0]["WonRounds"] + 1), $entry["ERID"]);
                setRound3Winner(true, $entry["ERID"]);
            }
        }
    }
    if ($alertevent) {
        $parti = getParticipantByID($events[0]["PID"])[0];
        Logs("Set " . $parti["EMail"] . " As Round 3 Winners", $admin["AID"], $apikey);
        if ($events[0]["isTeam"] == 0) {
            $alertmsg = $parti["FirstName"] . " " . $parti["LastName"] . " is Round3 Winner";
        } else {
            $alertmsg = $parti["FirstName"] . " " . $parti["LastName"] . "'s Team is Round3 Winner";
        }
    }
}

if (isset($_POST["r4lock"])) {
    foreach ($eventregs as $entry) {
        $rounddat = getRoundDetails($entry["ERID"]);
        if (!empty($rounddat)) {
            if ($rounddat[0]["isLockedRound4"] == 0) {
                $alertevent = true;
                setRound4Lock(true, $entry["ERID"]);
            }
        }
    }
    if ($alertevent) {
        Logs("Locked Round 2 Winners", $admin["AID"], $apikey);
        $alertmsg = "Round4 Winner Selection is Locked";
    }
}

if (isset($_POST["r4select"]) && isset($_POST["code"])) {
    $events = getEventEntryByCode($_POST["code"]);
    foreach ($events as $entry) {
        $rounddat = getRoundDetails($entry["ERID"]);
        if (!empty($rounddat)) {
            if ($rounddat[0]["isWinRound4"] == 0) {
                $alertevent = true;
                setWonRounds(($rounddat[0]["WonRounds"] + 1), $entry["ERID"]);
                setRound4Winner(true, $entry["ERID"]);
            }
        }
    }
    if ($alertevent) {
        $parti = getParticipantByID($events[0]["PID"])[0];
        Logs("Set " . $parti["EMail"] . " As Round 4 Winners", $admin["AID"], $apikey);
        if ($events[0]["isTeam"] == 0) {
            $alertmsg = $parti["FirstName"] . " " . $parti["LastName"] . " is Round4 Winner";
        } else {
            $alertmsg = $parti["FirstName"] . " " . $parti["LastName"] . "'s Team is Round4 Winner";
        }
    }
}

$total = 0;
$unattended = 0;
$attended = 0;
$r1w = 0;
$r2w = 0;
$r3w = 0;
$r4w = 0;

foreach ($eventregs as $entry) {
    $rounddat = getRoundDetails($entry["ERID"]);
    if (empty($rounddat)) {
        addRoundsDetails($entry["ERID"]);
    }
    if ($entry["isAttended"] == 1) {
        $attended++;
    } else {
        $unattended++;
    }
    $rounddat = getRoundDetails($entry["ERID"])[0];
    if ($rounddat["isWinRound1"] == 1) {
        $r1w++;
    }
    if ($rounddat["isWinRound2"] == 1) {
        $r2w++;
    }
    if ($rounddat["isWinRound3"] == 1) {
        $r3w++;
    }
    if ($rounddat["isWinRound4"] == 1) {
        $r4w++;
    }
}
?>
<!doctype html>
<html class="no-js" lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Events Rounds | Visvesmruti - Admin Panel</title>
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
    <script type="text/javascript">const filename = "VSData-<?php echo $admin["Department"] . "-" . $event["EVName"];?>"</script>
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
                        <li class="nav-item"><a data-toggle="tab" href="#all" class="nav-link">View Participants</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a href="#" data-toggle="dropdown" role="button" aria-expanded="false"
                               class="nav-link dropdown-toggle">Rounds
                                <span class="angle-down-topmenu">
                                    <i class="fa fa-angle-down"></i>
                                </span>
                            </a>
                            <div role="menu" class="dropdown-menu animated flipInX">
                                <?php
                                for ($i = 1; $i <= $event["EVRounds"]; $i++) {
                                    echo '<a data-toggle="tab" href="#round' . $i . '" class="dropdown-item">Round ' . $i . '</a>';
                                }
                                ?>
                            </div>
                        </li>
                        <li class="nav-item"><a data-toggle="tab" href="#coords" class="nav-link">Coordinators</a>
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
                                    <?php echo $admin['Name'] . "</br>" . $event["EVName"]; ?>
                                    <span class="angle-down-topmenu"><i class="fa fa-angle-down"></i></span>
                                </span>
                            </a>
                            <ul role="menu" class="dropdown-header-top author-log dropdown-menu animated flipInX">
                                <li><a href="EventRounds.php?Logout">Log Out</a></li>
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
                                <h3><span class="counter"><?php echo count($eventregs); ?></span></h3>
                            </div>
                        </div>
                        <div class="income-range">
                            <p>Total Registration</p>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="income-dashone-total shadow-reset nt-mg-b-30">
                    <div class="income-title">
                        <div class="main-income-head">
                            <h2>Not Attended</h2>
                            <div class="main-income-phara">
                                <p>Till Now</p>
                            </div>
                        </div>
                    </div>
                    <div class="income-dashone-pro">
                        <div class="income-rate-total">
                            <div class="price-adminpro-rate">
                                <h3><span class="counter"><?php echo $unattended; ?></span></h3>
                            </div>
                        </div>
                        <div class="income-range">
                            <p>Total UnAttended</p>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="income-dashone-total shadow-reset nt-mg-b-30">
                    <div class="income-title">
                        <div class="main-income-head">
                            <h2>Attended</h2>
                            <div class="main-income-phara">
                                <p>Till Now</p>
                            </div>
                        </div>
                    </div>
                    <div class="income-dashone-pro">
                        <div class="income-rate-total">
                            <div class="price-adminpro-rate">
                                <h3><span class="counter"><?php echo $attended; ?></span></h3>
                            </div>
                        </div>
                        <div class="income-range">
                            <p>Total Attended</p>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-3">
                <div class="income-dashone-total shadow-reset nt-mg-b-30">
                    <div class="income-title">
                        <div class="main-income-head">
                            <h2>Round1 Winners</h2>
                            <div class="main-income-phara">
                                <p>Till Now</p>
                            </div>
                        </div>
                    </div>
                    <div class="income-dashone-pro">
                        <div class="income-rate-total">
                            <div class="price-adminpro-rate">
                                <h3><span class="counter"><?php echo $r1w; ?></span></h3>
                            </div>
                        </div>
                        <div class="income-range">
                            <p>Total Round1 Winners</p>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
            <div <?php if ($event["EVRounds"] < 2) {
                echo 'style="display: none;"';
            } ?> class="col-lg-3">
                <div class="income-dashone-total shadow-reset nt-mg-b-30">
                    <div class="income-title">
                        <div class="main-income-head">
                            <h2>Round2 Winners</h2>
                            <div class="main-income-phara">
                                <p>Till Now</p>
                            </div>
                        </div>
                    </div>
                    <div class="income-dashone-pro">
                        <div class="income-rate-total">
                            <div class="price-adminpro-rate">
                                <h3><span class="counter"><?php echo $r2w; ?></span></h3>
                            </div>
                        </div>
                        <div class="income-range">
                            <p>Total Round2 Winners</p>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
            <div <?php if ($event["EVRounds"] < 3) {
                echo 'style="display: none;"';
            } ?> class="col-lg-3">
                <div class="income-dashone-total shadow-reset nt-mg-b-30">
                    <div class="income-title">
                        <div class="main-income-head">
                            <h2>Round3 Winners</h2>
                            <div class="main-income-phara">
                                <p>Till Now</p>
                            </div>
                        </div>
                    </div>
                    <div class="income-dashone-pro">
                        <div class="income-rate-total">
                            <div class="price-adminpro-rate">
                                <h3><span class="counter"><?php echo $r3w; ?></span></h3>
                            </div>
                        </div>
                        <div class="income-range">
                            <p>Total Round3 Winners</p>
                        </div>
                        <div class="clear"></div>
                    </div>
                </div>
            </div>
            <div <?php if ($event["EVRounds"] < 4) {
                echo 'style="display: none;"';
            } ?> class="col-lg-3">
                <div class="income-dashone-total shadow-reset nt-mg-b-30">
                    <div class="income-title">
                        <div class="main-income-head">
                            <h2>Round4 Winners</h2>
                            <div class="main-income-phara">
                                <p>Till Now</p>
                            </div>
                        </div>
                    </div>
                    <div class="income-dashone-pro">
                        <div class="income-rate-total">
                            <div class="price-adminpro-rate">
                                <h3><span class="counter"><?php echo $r4w; ?></span></h3>
                            </div>
                        </div>
                        <div class="income-range">
                            <p>Total Round4 Winners</p>
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
                            <h1>All Participants List</h1>
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
                                    <th data-field="round">Won Rounds</th>
                                    <th data-field="attend">Attended</th>
                                    <th data-field="won1">Win Round 1</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $btnshow = false;
                                foreach ($eventregs as $entry) {
                                    if ($event["isTeamEvent"] == 1) {
                                        if ($entry["isTeamLeader"] == 1) {
                                            $teamentries = getEventEntryByCode($entry["ERCode"]);
                                            foreach ($teamentries as $team) {
                                                $rounddat = getRoundDetails($team["ERID"])[0];
                                                $parti = getParticipantByID($team["PID"])[0];

                                                echo "<tr>" .
                                                    "<td>" . $team["ERCode"] . "</td>" .
                                                    "<td>" . $parti["FirstName"] . " " . $parti["LastName"] . "</td>" .
                                                    "<td>" . $parti["EMail"] . "</td>" .
                                                    "<td>" . $parti["Mobile"] . "</td>" .
                                                    "<td>" . $parti["College"] . "</td>" .
                                                    "<td>" . $parti["Department"] . "</td>" .
                                                    "<td>" . $parti["Semester"] . "</td>" .
                                                    "<td>" . $rounddat["WonRounds"] . "</td>" .
                                                    '<td class="datatable-ct"><i class="' .
                                                    ($team["isAttended"] == 1 ? 'fa fa-check' : 'fa fa-times') . '"></i></td>';

                                                $btnshow = true;
                                                if ($team["isTeamLeader"] == 1 && $team["isAttended"] == 1 &&
                                                    $rounddat["isWinRound1"] == 0 && $rounddat["isLockedRound1"] == 0) {
                                                    echo '<td><form action="" method="post">
                                                              <input type="hidden" name="code" value="' . $team["ERCode"] . '">
                                                              <input type="submit" name="r1select" value="Win Round1">
                                                              </form></td>';
                                                } else {
                                                    echo "<td>-</td>";
                                                }

                                                echo "</tr>";
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
                                                "<td>-</td>" .
                                                "</tr>";
                                        }
                                    } else {
                                        $rounddat = getRoundDetails($entry["ERID"])[0];
                                        $parti = getParticipantByID($entry["PID"])[0];

                                        echo "<tr>" .
                                            "<td>" . $entry["ERCode"] . "</td>" .
                                            "<td>" . $parti["FirstName"] . " " . $parti["LastName"] . "</td>" .
                                            "<td>" . $parti["EMail"] . "</td>" .
                                            "<td>" . $parti["Mobile"] . "</td>" .
                                            "<td>" . $parti["College"] . "</td>" .
                                            "<td>" . $parti["Department"] . "</td>" .
                                            "<td>" . $parti["Semester"] . "</td>" .
                                            "<td>" . $rounddat["WonRounds"] . "</td>" .
                                            '<td class="datatable-ct"><i class="' .
                                            ($entry["isAttended"] == 1 ? 'fa fa-check' : 'fa fa-times') . '"></i></td>';

                                        $btnshow = true;
                                        if ($entry["isAttended"] == 1 &&
                                            $rounddat["isWinRound1"] == 0 && $rounddat["isLockedRound1"] == 0) {
                                            echo '<td><form action="" method="post">
                                                  <input type="hidden" name="code" value="' . $entry["ERCode"] . '">
                                                  <input type="submit" name="r1select" value="Win Round1">
                                                  </form></td>';
                                        } else {
                                            echo "<td>-</td>";
                                        }

                                        echo "</tr>";
                                    }
                                }
                                if ($btnshow) {
                                    echo "<tr>" .
                                        "<td>-</td>" .
                                        "<td>-</td>" .
                                        "<td>-</td>" .
                                        '<td><form action="" method="post">
                                            <input type="submit" name="r1lock" value="Lock Round1 Winners">
                                            </form></td>' .
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
        <div class="row tab-pane in" id="round1">
            <div class="col-lg-12">
                <div class="sparkline8-list shadow-reset">
                    <div class="sparkline8-hd">
                        <div class="main-sparkline8-hd">
                            <h1>Round1 Winners List</h1>
                            <div class="sparkline8-outline-icon">
                                <span class="sparkline8-collapse-link"><i class="fa fa-chevron-up"></i></span>
                                <span><i class="fa fa-wrench"></i></span>
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
                                    <?php if ($event["EVRounds"] > 1) {
                                        echo '<th data-field="won2">Win Round 2</th>';
                                    } ?>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $btnshow = false;
                                foreach ($eventregs as $entry) {
                                    if ($event["isTeamEvent"] == 1) {
                                        if ($entry["isTeamLeader"] == 1) {
                                            $drawline = false;
                                            $teamentries = getEventEntryByCode($entry["ERCode"]);
                                            foreach ($teamentries as $team) {
                                                $rounddat = getRoundDetails($team["ERID"])[0];
                                                if ($rounddat["isWinRound1"] == 1 && $rounddat["isLockedRound1"] == 1) {
                                                    $drawline = true;
                                                    $parti = getParticipantByID($team["PID"])[0];

                                                    echo "<tr>" .
                                                        "<td>" . $team["ERCode"] . "</td>" .
                                                        "<td>" . $parti["FirstName"] . " " . $parti["LastName"] . "</td>" .
                                                        "<td>" . $parti["EMail"] . "</td>" .
                                                        "<td>" . $parti["Mobile"] . "</td>" .
                                                        "<td>" . $parti["College"] . "</td>" .
                                                        "<td>" . $parti["Department"] . "</td>" .
                                                        "<td>" . $parti["Semester"] . "</td>";

                                                    if ($event["EVRounds"] > 1) {
                                                        $btnshow = true;
                                                        if ($team["isTeamLeader"] == 1 && $rounddat["isWinRound2"] == 0 && $rounddat["isLockedRound2"] == 0) {
                                                            echo '<td><form action="" method="post">
                                                                      <input type="hidden" name="code" value="' . $team["ERCode"] . '">
                                                                      <input type="submit" name="r2select" value="Win Round2">
                                                                      </form></td>';
                                                        } else {
                                                            echo "<td>-</td>";
                                                        }
                                                    }

                                                    echo "</tr>";
                                                }
                                            }
                                            if ($drawline) {
                                                echo "<tr>" .
                                                    "<td>-</td>" .
                                                    "<td>-</td>" .
                                                    "<td>-</td>" .
                                                    "<td>-</td>" .
                                                    "<td>-</td>" .
                                                    "<td>-</td>" .
                                                    "<td>-</td>";
                                                if ($event["EVRounds"] > 1) {
                                                    echo '<td>-</td>';
                                                }
                                                echo "</tr>";
                                            }
                                        }
                                    } else {
                                        $rounddat = getRoundDetails($entry["ERID"])[0];
                                        if ($rounddat["isWinRound1"] == 1 && $rounddat["isLockedRound1"] == 1) {
                                            $parti = getParticipantByID($entry["PID"])[0];

                                            echo "<tr>" .
                                                "<td>" . $entry["ERCode"] . "</td>" .
                                                "<td>" . $parti["FirstName"] . " " . $parti["LastName"] . "</td>" .
                                                "<td>" . $parti["EMail"] . "</td>" .
                                                "<td>" . $parti["Mobile"] . "</td>" .
                                                "<td>" . $parti["College"] . "</td>" .
                                                "<td>" . $parti["Department"] . "</td>" .
                                                "<td>" . $parti["Semester"] . "</td>";

                                            if ($event["EVRounds"] > 1) {
                                                $btnshow = true;
                                                if ($rounddat["isWinRound2"] == 0 && $rounddat["isLockedRound2"] == 0) {
                                                    echo '<td><form action="" method="post">
                                                      <input type="hidden" name="code" value="' . $entry["ERCode"] . '">
                                                      <input type="submit" name="r2select" value="Win Round2">
                                                      </form></td>';
                                                } else {
                                                    echo "<td>-</td>";
                                                }
                                            }

                                            echo "</tr>";
                                        }
                                    }
                                }
                                if ($btnshow) {
                                    echo "<tr>" .
                                        "<td>-</td>" .
                                        "<td>-</td>" .
                                        "<td>-</td>" .
                                        '<td><form action="" method="post">
                                            <input type="submit" name="r2lock" value="Lock Round2 Winners">
                                            </form></td>' .
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
        <div class="row tab-pane in" id="round2">
            <div class="col-lg-12">
                <div class="sparkline8-list shadow-reset">
                    <div class="sparkline8-hd">
                        <div class="main-sparkline8-hd">
                            <h1>Round2 Winners List</h1>
                            <div class="sparkline8-outline-icon">
                                <span class="sparkline8-collapse-link"><i class="fa fa-chevron-up"></i></span>
                                <span><i class="fa fa-wrench"></i></span>
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
                                    <?php if ($event["EVRounds"] > 2) {
                                        echo '<th data-field="won3">Win Round 3</th>';
                                    } ?>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $btnshow = false;
                                foreach ($eventregs as $entry) {
                                    if ($event["isTeamEvent"] == 1) {
                                        if ($entry["isTeamLeader"] == 1) {
                                            $drawline = false;
                                            $teamentries = getEventEntryByCode($entry["ERCode"]);
                                            foreach ($teamentries as $team) {
                                                $rounddat = getRoundDetails($team["ERID"])[0];
                                                if ($rounddat["isWinRound2"] == 1 && $rounddat["isLockedRound2"] == 1) {
                                                    $drawline = true;
                                                    $parti = getParticipantByID($team["PID"])[0];

                                                    echo "<tr>" .
                                                        "<td>" . $team["ERCode"] . "</td>" .
                                                        "<td>" . $parti["FirstName"] . " " . $parti["LastName"] . "</td>" .
                                                        "<td>" . $parti["EMail"] . "</td>" .
                                                        "<td>" . $parti["Mobile"] . "</td>" .
                                                        "<td>" . $parti["College"] . "</td>" .
                                                        "<td>" . $parti["Department"] . "</td>" .
                                                        "<td>" . $parti["Semester"] . "</td>";

                                                    if ($event["EVRounds"] > 2) {
                                                        $btnshow = true;
                                                        if ($team["isTeamLeader"] == 1 && $rounddat["isWinRound3"] == 0 && $rounddat["isLockedRound3"] == 0) {
                                                            echo '<td><form action="" method="post">
                                                                      <input type="hidden" name="code" value="' . $team["ERCode"] . '">
                                                                      <input type="submit" name="r3select" value="Win Round3">
                                                                      </form></td>';
                                                        } else {
                                                            echo "<td>-</td>";
                                                        }
                                                    }

                                                    echo "</tr>";
                                                }
                                            }
                                            if ($drawline) {
                                                echo "<tr>" .
                                                    "<td>-</td>" .
                                                    "<td>-</td>" .
                                                    "<td>-</td>" .
                                                    "<td>-</td>" .
                                                    "<td>-</td>" .
                                                    "<td>-</td>" .
                                                    "<td>-</td>";
                                                if ($event["EVRounds"] > 2) {
                                                    echo '<td>-</td>';
                                                }
                                                echo "</tr>";
                                            }
                                        }
                                    } else {
                                        $rounddat = getRoundDetails($entry["ERID"])[0];
                                        if ($rounddat["isWinRound2"] == 1 && $rounddat["isLockedRound2"] == 1) {
                                            $parti = getParticipantByID($entry["PID"])[0];

                                            echo "<tr>" .
                                                "<td>" . $entry["ERCode"] . "</td>" .
                                                "<td>" . $parti["FirstName"] . " " . $parti["LastName"] . "</td>" .
                                                "<td>" . $parti["EMail"] . "</td>" .
                                                "<td>" . $parti["Mobile"] . "</td>" .
                                                "<td>" . $parti["College"] . "</td>" .
                                                "<td>" . $parti["Department"] . "</td>" .
                                                "<td>" . $parti["Semester"] . "</td>";

                                            if ($event["EVRounds"] > 2) {
                                                $btnshow = true;
                                                if ($rounddat["isWinRound3"] == 0 && $rounddat["isLockedRound3"] == 0) {
                                                    echo '<td><form action="" method="post">
                                                          <input type="hidden" name="code" value="' . $entry["ERCode"] . '">
                                                          <input type="submit" name="r3select" value="Win Round3">
                                                          </form></td>';
                                                } else {
                                                    echo "<td>-</td>";
                                                }
                                            }

                                            echo "</tr>";
                                        }
                                    }
                                }
                                if ($btnshow) {
                                    echo "<tr>" .
                                        "<td>-</td>" .
                                        "<td>-</td>" .
                                        "<td>-</td>" .
                                        '<td><form action="" method="post">
                                                <input type="submit" name="r3lock" value="Lock Round3 Winners">
                                                </form></td>' .
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
        <div class="row tab-pane in" id="round3">
            <div class="col-lg-12">
                <div class="sparkline8-list shadow-reset">
                    <div class="sparkline8-hd">
                        <div class="main-sparkline8-hd">
                            <h1>Round3 Winners List</h1>
                            <div class="sparkline8-outline-icon">
                                <span class="sparkline8-collapse-link"><i class="fa fa-chevron-up"></i></span>
                                <span><i class="fa fa-wrench"></i></span>
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
                                    <?php if ($event["EVRounds"] > 3) {
                                        echo '<th data-field="won4">Win Round 4</th>';
                                    } ?>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                $btnshow = false;
                                foreach ($eventregs as $entry) {
                                    if ($event["isTeamEvent"] == 1) {
                                        if ($entry["isTeamLeader"] == 1) {
                                            $drawline = false;
                                            $teamentries = getEventEntryByCode($entry["ERCode"]);
                                            foreach ($teamentries as $team) {
                                                $rounddat = getRoundDetails($team["ERID"])[0];
                                                if ($rounddat["isWinRound3"] == 1 && $rounddat["isLockedRound3"] == 1) {
                                                    $drawline = true;
                                                    $parti = getParticipantByID($team["PID"])[0];

                                                    echo "<tr>" .
                                                        "<td>" . $team["ERCode"] . "</td>" .
                                                        "<td>" . $parti["FirstName"] . " " . $parti["LastName"] . "</td>" .
                                                        "<td>" . $parti["EMail"] . "</td>" .
                                                        "<td>" . $parti["Mobile"] . "</td>" .
                                                        "<td>" . $parti["College"] . "</td>" .
                                                        "<td>" . $parti["Department"] . "</td>" .
                                                        "<td>" . $parti["Semester"] . "</td>";

                                                    if ($event["EVRounds"] > 3) {
                                                        $btnshow = true;
                                                        if ($team["isTeamLeader"] == 1 && $rounddat["isWinRound4"] == 0 && $rounddat["isLockedRound4"] == 0) {
                                                            echo '<td><form action="" method="post">
                                                                      <input type="hidden" name="code" value="' . $team["ERCode"] . '">
                                                                      <input type="submit" name="r4select" value="Win Round4">
                                                                      </form></td>';
                                                        } else {
                                                            echo "<td>-</td>";
                                                        }
                                                    }

                                                    echo "</tr>";
                                                }
                                            }
                                            if ($drawline) {
                                                echo "<tr>" .
                                                    "<td>-</td>" .
                                                    "<td>-</td>" .
                                                    "<td>-</td>" .
                                                    "<td>-</td>" .
                                                    "<td>-</td>" .
                                                    "<td>-</td>" .
                                                    "<td>-</td>";
                                                if ($event["EVRounds"] > 3) {
                                                    echo '<td>-</td>';
                                                }
                                                echo "</tr>";
                                            }
                                        }
                                    } else {
                                        $rounddat = getRoundDetails($entry["ERID"])[0];
                                        if ($rounddat["isWinRound3"] == 1 && $rounddat["isLockedRound3"] == 1) {
                                            $parti = getParticipantByID($entry["PID"])[0];

                                            echo "<tr>" .
                                                "<td>" . $entry["ERCode"] . "</td>" .
                                                "<td>" . $parti["FirstName"] . " " . $parti["LastName"] . "</td>" .
                                                "<td>" . $parti["EMail"] . "</td>" .
                                                "<td>" . $parti["Mobile"] . "</td>" .
                                                "<td>" . $parti["College"] . "</td>" .
                                                "<td>" . $parti["Department"] . "</td>" .
                                                "<td>" . $parti["Semester"] . "</td>";

                                            if ($event["EVRounds"] > 3) {
                                                $btnshow = true;
                                                if ($rounddat["isWinRound4"] == 0 && $rounddat["isLockedRound4"] == 0) {
                                                    echo '<td><form action="" method="post">
                                                              <input type="hidden" name="code" value="' . $entry["ERCode"] . '">
                                                              <input type="submit" name="r4select" value="Win Round4">
                                                              </form></td>';
                                                } else {
                                                    echo "<td>-</td>";
                                                }
                                            }

                                            echo "</tr>";
                                        }
                                    }
                                }
                                if ($btnshow) {
                                    echo "<tr>" .
                                        "<td>-</td>" .
                                        "<td>-</td>" .
                                        "<td>-</td>" .
                                        '<td><form action="" method="post">
                                            <input type="submit" name="r4lock" value="Lock Round4 Winners">
                                            </form></td>' .
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
        <div class="row tab-pane in" id="round4">
            <div class="col-lg-12">
                <div class="sparkline8-list shadow-reset">
                    <div class="sparkline8-hd">
                        <div class="main-sparkline8-hd">
                            <h1>Round4 Winners List</h1>
                            <div class="sparkline8-outline-icon">
                                <span class="sparkline8-collapse-link"><i class="fa fa-chevron-up"></i></span>
                                <span><i class="fa fa-wrench"></i></span>
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
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($eventregs as $entry) {
                                    if ($event["isTeamEvent"] == 1) {
                                        if ($entry["isTeamLeader"] == 1) {
                                            $drawline = false;
                                            $teamentries = getEventEntryByCode($entry["ERCode"]);
                                            foreach ($teamentries as $team) {
                                                $rounddat = getRoundDetails($team["ERID"])[0];
                                                if ($rounddat["isWinRound4"] == 1 && $rounddat["isLockedRound4"] == 1) {
                                                    $parti = getParticipantByID($team["PID"])[0];

                                                    echo "<tr>" .
                                                        "<td>" . $team["ERCode"] . "</td>" .
                                                        "<td>" . $parti["FirstName"] . " " . $parti["LastName"] . "</td>" .
                                                        "<td>" . $parti["EMail"] . "</td>" .
                                                        "<td>" . $parti["Mobile"] . "</td>" .
                                                        "<td>" . $parti["College"] . "</td>" .
                                                        "<td>" . $parti["Department"] . "</td>" .
                                                        "<td>" . $parti["Semester"] . "</td>" .
                                                        "</tr>";
                                                }
                                            }
                                            if ($drawline) {
                                                echo "<tr>" .
                                                    "<td>-</td>" .
                                                    "<td>-</td>" .
                                                    "<td>-</td>" .
                                                    "<td>-</td>" .
                                                    "<td>-</td>" .
                                                    "<td>-</td>" .
                                                    "<td>-</td>" .
                                                    "</tr>";
                                            }
                                        }
                                    } else {
                                        $rounddat = getRoundDetails($entry["ERID"])[0];
                                        if ($rounddat["isWinRound4"] == 1 && $rounddat["isLockedRound4"] == 1) {
                                            $parti = getParticipantByID($entry["PID"])[0];
                                            $paid = $entry["isPaid"];

                                            echo "<tr>" .
                                                "<td>" . $entry["ERCode"] . "</td>" .
                                                "<td>" . $parti["FirstName"] . " " . $parti["LastName"] . "</td>" .
                                                "<td>" . $parti["EMail"] . "</td>" .
                                                "<td>" . $parti["Mobile"] . "</td>" .
                                                "<td>" . $parti["College"] . "</td>" .
                                                "<td>" . $parti["Department"] . "</td>" .
                                                "<td>" . $parti["Semester"] . "</td>" .
                                                "</tr>";
                                        }
                                    }
                                }
                                ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row tab-pane in" id="coords">
            <div class="col-lg-12">
                <div class="sparkline8-list shadow-reset">
                    <div class="sparkline8-hd">
                        <div class="main-sparkline8-hd">
                            <h1>Event Coordinators List</h1>
                            <div class="sparkline8-outline-icon">
                                <span class="sparkline8-collapse-link"><i class="fa fa-chevron-up"></i></span>
                                <span><i class="fa fa-wrench"></i></span>
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
                                    <th data-field="id">ID</th>
                                    <th data-field="name">Name</th>
                                    <th data-field="email">Email</th>
                                    <th data-field="phone">Phone</th>
                                    <th data-field="fees">Fees Collected</th>
                                </tr>
                                </thead>
                                <tbody>
                                <?php
                                foreach ($coords as $coord) {
                                    if ($coord["isCoordinator"] == 1 && $coord["EventID"] == $event["EVID"]) {
                                        echo "<tr>" .
                                            "<td>" . $coord["AID"] . "</td>" .
                                            "<td>" . $coord["Name"] . "</td>" .
                                            "<td>" . $coord["EMail"] . "</td>" .
                                            "<td>" . $coord["Mobile"] . "</td>" .
                                            "<td>" . $coord["TotalFeeCollected"] . "</td>" .
                                            "</tr>";
                                    }
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
<?php
if ($alertevent) {
    echo '<script type="text/javascript">alert("' . $alertmsg . '")</script>';
}
?>
</body>

</html>