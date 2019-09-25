<?php
//api url filter
if (strpos($_SERVER['REQUEST_URI'], "api.php")) {
    require_once 'utils.php';
    PlainDie();
}

date_default_timezone_set("Asia/Calcutta");

require_once __DIR__ . '/vendor/autoload.php';
require_once 'global.php';
require_once 'utils.php';
require_once 'mailer.php';
require_once 'VSAdmins.php';
require_once 'VSLogin.php';
require_once 'VSSession.php';
require_once 'VSEvents.php';
require_once 'VSEventReg.php';
require_once 'VSParticipants.php';

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;

function Logs($message)
{
    if (isset($_POST['apikey'])) {
        $apikey = $_POST['apikey'];
        $logindata = getLoginByToken($apikey);
        if (!empty($logindata)) {
            addSessionLog($logindata[0]["AID"], $apikey, $message);
        } else {
            addSessionLog(null, $apikey, $message);
        }
    } else {
        addSessionLog(null, null, $message);
    }
}

/*sendSMS("9998897742",
    "Congratulations! You are Selected for Round 2 of Event Placement Drive\n\n".
    "Please Come At LAB-2 in Computer Department at Sharp 2:00pm");*/
function sendSMS($number, $message){
    return false;
}

function QRGen($data, $fcode)
{
    $qrCode = new QrCode($data);
    $qrCode->setSize(480);
    $qrCode->setWriterByName('png');
    $qrCode->setMargin(20);
    $qrCode->setEncoding('UTF-8');
    $qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevel(ErrorCorrectionLevel::HIGH));
    $qrCode->setForegroundColor(['r' => 104, 'g' => 40, 'b' => 104, 'a' => 0]);
    $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
    $qrCode->setLabel($data, 30, null, null, ['t' => 0, 'r' => 0, 'b' => 20, 'l' => 0,]);
    $qrCode->setRoundBlockSize(true);
    $qrCode->setValidateResult(false);
    $qrCode->writeFile("qrcodes/" . $fcode . "_qr.png");
}

function PDFGen($name, $college, $department, $eventname, $fcode)
{
    $pdf = new FPDI();

    $pdf->AddPage();
    $pdf->setSourceFile('certificates/template.pdf');
    $pdf->useTemplate($pdf->importPage(1), null, null, 0, 0, true);
    $pdf->SetTextColor(0, 0, 0);

    $pdf->SetXY(200, 242);
    $pdf->SetFont('courier', '', 57);
    $pdf->Cell(240, 0, $name, 0, 2, 'C');

    $pdf->SetXY(15, 270);
    if (strlen($college) > 20) {
        $pdf->SetFont('courier', '', 32);
        $pdf->Cell(290, 0, $college, 0, 2, 'C');
    } else {
        $pdf->SetFont('courier', '', 57);
        $pdf->Cell(240, 0, $college, 0, 2, 'C');
    }

    $pdf->SetXY(330, 270);
    $pdf->SetFont('courier', '', 57);
    $pdf->Cell(240, 0, $department, 0, 2, 'C');

    $pdf->SetXY(330, 298);
    $pdf->SetFont('courier', '', 57);
    $pdf->Cell(240, 0, $eventname, 0, 2, 'C');

    $pdf->Output('certificates/' . $fcode . '_certificate.pdf', 'F');
}

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST');
header("Access-Control-Allow-Headers: Content-Type");
header('Content-type: application/json');

$currtimestamp = date('Y-m-d H:i:s');

$result = array();
$result['Status'] = "Failed";
$result['Code'] = 404;
$result['Message'] = "Error";

if($GLOBALS['Maintenance'] == true){
    $result['Message'] = "Server in Maintenance";
    echo toJson($result);
    die();
}

//Admin Registration
if ($GLOBALS['AdminAdding'] == true && isset($_GET['RegisterAdmin']) && isset($_POST['email']) && isset($_POST['pass']) &&
    isset($_POST['name']) && isset($_POST['department']) && isset($_POST['mobile']) && isset($_POST['type'])) {
    $email = $_POST['email'];
    $pass = $_POST['pass'];
    $name = $_POST['name'];
    $dept = $_POST['department'];
    $mobile = $_POST['mobile'];

    $user = getAdmin($email);
    if (!empty($user)) {
        Logs("RegisterAdmin: " . $email . " Failed to Register as Admin");
        $result['Message'] = "Already Registered";
    } else {
        addAdmin($email, password_hash($pass, PASSWORD_DEFAULT), $name, $dept, str_replace("+91","",$mobile),
            $_POST['type'] == 3, $_POST['type'] == 2, $_POST['type'] == 1);
        Logs("RegisterAdmin: " . $email . " Become Admin");
        $result['Status'] = "Success";
        $result['Code'] = 200;
        $result['Message'] = "Registration Done";
    }
}

//Admin Login
if ($GLOBALS['AdminApiLogin'] == true && isset($_GET['Login']) && isset($_POST['email']) && isset($_POST['pass'])) {
    $email = $_POST['email'];
    $admin = getAdmin($email);
    if (!empty($admin)) {
        if (password_verify($_POST['pass'], $admin[0]['Password'])) {
            $adminid = $admin[0]['AID'];
            $apikey = genApiKey();

            $login = getLogin($adminid);
            if (!empty($login)) {
                updateLogin($apikey, $currtimestamp, $adminid);
            } else {
                addLogin($adminid, $apikey);
            }

            if($admin[0]['isCoordinator'] == 1 || $admin[0]['isFaculty'] == 1 || $admin[0]['isCampaigner'] == 1){
                Logs("Admin Login: " . $email . " is Login Successful");
                $result['Status'] = "Success";
                $result['Code'] = 200;
                $result['ApiToken'] = $apikey;
                $result['Name'] = $admin[0]['Name'];
                $result['Type'] = "UNK";
                $result['Department'] = "UNK";
                $result['Event'] = "UNK";
                if($admin[0]['isCoordinator'] == 1 || $admin[0]['isFaculty'] == 1){
                    $result['Type'] = ($admin[0]['isFaculty'] == 1) ? "Faculty" : "Coordinator";
                    $eventinfo = getEventByID($admin[0]['EventID']);
                    $result['Department'] = $admin[0]['Department'];
                    $result['Event'] = $eventinfo[0]['EVName'];
                } else if($admin[0]['isCampaigner'] == 1){
                    $result['Type'] = "Campaigner";
                }
                $result['Message'] = "Login Done";
            } else {
                Logs("Admin Login: " . $email . "'s Unauthorised Access");
                $result['Message'] = "Unauthorised Access";
            }
        } else {
            Logs("Admin Login: " . $email . " Entered Wrong Password");
            $result['Message'] = "Password is Wrong";
        }
    } else {
        Logs("Admin Login: " . $email . " is Not Registered");
        $result['Message'] = "Email is Not Registered";
    }
}

//Login Check
if ($GLOBALS['AdminApiLogin'] == true && isset($_GET['LoginCheck']) && isset($_POST['apikey'])) {
    $apikey = $_POST['apikey'];

    $logindata = getLoginByToken($apikey);
    if (!empty($logindata)) {
        $admin = getAdminByID($logindata[0]["AID"]);
        Logs("Login Check: Login Session Checked");
        $result['Status'] = "Success";
        $result['Code'] = 200;
        $result['Name'] = $admin[0]['Name'];
        $result['Type'] = "UNK";
        $result['Department'] = "UNK";
        $result['Event'] = "UNK";
        if($admin[0]['isCoordinator'] == 1 || $admin[0]['isFaculty'] == 1){
            $result['Type'] = ($admin[0]['isFaculty'] == 1) ? "Faculty" : "Coordinator";
            $eventinfo = getEventByID($admin[0]['EventID']);
            if(!empty($eventinfo)) {
                $result['Department'] = $admin[0]['Department'];
                $result['Event'] = $eventinfo[0]['EVName'];
            }
        } else if($admin[0]['isCampaigner'] == 1){
            $result['Type'] = "Campaigner";
        }
        $result['Message'] = "Valid Login";
    } else {
        Logs("Login Check: Unauthorised Access");
        $result['Message'] = "Unauthorised Access!";
    }
}

//Participant Registration
if ($GLOBALS['AdminApiAccess'] == true && isset($_GET['Register']) && isset($_POST['email']) && isset($_POST['fname']) && isset($_POST['lname']) && isset($_POST['college'])
    && isset($_POST['department']) && isset($_POST['semester']) && isset($_POST['mobile']) && isset($_POST['gender'])) {
    $email = $_POST['email'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $college = $_POST['college'];
    $dept = $_POST['department'];
    $sem = $_POST['semester'];
    $mobile = str_replace("+91","", $_POST['mobile']);
    $gender = $_POST['gender'];

    if(strpos($email, '@gmail.com') !== false || strpos($email, '@yahoo.com') !== false
        || strpos($email, '@yahoo.in') !== false){
        $user = getParticipant($email);
        if ($email == "" || !empty($user)) {
            Logs("Participant Registration: " . $email . " Already Registered");
            $result['Message'] = "Already Registered";
        } else {
            addParticipant($email, $fname, $lname, $college, $dept, $sem, $mobile, $gender);

            Logs("Participant Registration: " . $email . " Registration Done");

            sendSMS($mobile, "Hurray! Registration Done..\n\n".
                "We heartily welcome you to the technology festival at R.N.G. Patel Institute of Technology.\n\n".
                "You can start participating in your desired events\n\n".
                "by visiting our website https://visvesmruti.tech");

            sendMail($email, "Hurray! Registration Done..",
                "We heartily welcome you to the technology festival at R.N.G. Patel Institute of Technology.\n\n".
                "You can start participating in your desired events\n".
                "by visiting our website https://visvesmruti.tech");

            $result['Status'] = "Success";
            $result['Code'] = 200;
            $result['Message'] = "Registration Done";
        }
    } else {
        Logs("Participant Registration: " . $email . " is Not Valid Email");
        $result['Message'] = $email . " is Not Valid Email";
    }
}

//Event Registration
if ($GLOBALS['AdminApiAccess'] == true && isset($_GET['EventRegister']) && isset($_POST['teamlength']) && isset($_POST['eventcode'])) {
    $eventcode = $_POST['eventcode'];
    $teamlength = $_POST['teamlength'];
    $teamarr = array();
    $isready = true;
    $isteamleader = true;

    $admin = null;
    if (isset($_POST['apikey'])) {
        $logindata = getLoginByToken($_POST['apikey']);
        if (!empty($logindata)) {
            $admin = $logindata[0]["AID"];
        }
    }

    $event = getEventByCode($eventcode);
    if (!empty($event)) {
        if($event[0]["isTeamEvent"] == 1){
            if ($teamlength >= $event[0]["MinMembers"] && $teamlength <= $event[0]["MaxMembers"]) {
                for ($i = 0; $i < $teamlength; $i++) {
                    if (isset($_POST['email' . $i])) {
                        $email = $_POST['email' . $i];
                        if(!in_array($email, $teamarr)){
                            $parti = getParticipant($email);
                            if (!empty($parti)) {
                                $eventreg = getEventEntryByP($parti[0]["PID"], $event[0]["EVID"]);
                                if (empty($eventreg)) {
                                    $teamarr[$i] = $email;
                                } else {
                                    Logs("Event Registration: Team Member " . $email . " is Already Registered in Another Team");
                                    $result['Message'] = "Team Member " . $email . " is Already Registered in Another Team";
                                    $isready = false;
                                    break;
                                }
                            } else {
                                Logs("Event Registration: Team Member " . $email . " is Not Registered Yet");
                                $result['Message'] = "Team Member " . $email . " is Not Registered Yet";
                                $isready = false;
                                break;
                            }
                        } else {
                            Logs("Event Registration: Team Member " . $email . " is Already in Team List");
                            $result['Message'] = "Team Member " . $email . " is Already in Team List";
                            $isready = false;
                            break;
                        }
                    } else {
                        Logs("Event Registration: Team Member(" . $i . ")'s Email is Not Provided");
                        $result['Message'] = "Team Member(" . $i . ")'s Email is Not Provided";
                        $isready = false;
                        break;
                    }
                }
                if ($isready) {
                    $erentrycode = genID(implode($teamarr), $eventcode);
                    $fcode = genFileKey();

                    //QRCode Generation
                    QRGen($erentrycode, $fcode);

                    foreach ($teamarr as $email) {
                        $parti = getParticipant($email);

                        addRegistration($parti[0]["PID"], $event[0]["EVID"], $erentrycode,
                            ($isteamleader ? $fcode : genFileKey()), $admin, true, $isteamleader);

                        $isteamleader = false;

                        Logs("Event Registration: " . $email . " has Participate in " . $event[0]["EVName"]);

                        sendSMS($parti[0]["Mobile"],
                            "Thank You For Participating in " . $event[0]["EVName"] . " Of " .
                            $event[0]["EVDepartment"] . " Department\n\n".
                            "Your Team Leader is " . $teamarr[0] . "\n\n".
                            "Your Unique Event ID: " . $erentrycode . "\n\n".
                            "Your QR Code Link: " . $GLOBALS['URL'] . "/api/qr/" . $fcode . "\n\n" .
                            "Please Note that If you haven't Paid Fees During Participation, " .
                            "You need to Pay at Event Venue");

                        //QRCode Sending
                        sendMailWithAttach($email, "Yo! Participation Done..",
                            "Thank You For Participating in " . $event[0]["EVName"] . " Of " . $event[0]["EVDepartment"] .
                            " Department.\nYour Team Leader is " . $teamarr[0] . ", Here is your QR Code for Attendance.\n" .
                            $GLOBALS['URL'] . "/api/qr/" . $fcode . "\n\n" .
                            "Please Note that If you haven't Paid Fees During Participation, " .
                            "You need to Pay at Event Venue",
                            $parti[0]["FirstName"] . '_' .
                            preg_replace('/\s+/', '_', $event[0]["EVName"]) . $GLOBALS['QR_Postfix'],
                            "qrcodes/" . $fcode . "_qr.png");
                    }

                    $result['Status'] = "Success";
                    $result['Code'] = 200;
                    $result['ERCode'] = $erentrycode;
                    $result['Amount'] = ($event[0]["isSinglePrice"] == 1) ? $event[0]["EVPrice"] : ($event[0]["EVPrice"] * count($teamarr));
                    $result['Message'] = "Team Event Registration Done";
                }
            } else {
                Logs("Event Registration: Got Wrong Number of Email, " . $teamlength . "/" . $event[0]["MaxMembers"]);
                if($event[0]["MinMembers"] == $event[0]["MaxMembers"]){
                    $result['Message'] = "This Event Needs Exact ". $event[0]["MinMembers"] . " Registered Participants";
                } else {
                    $result['Message'] = "This Event Needs ". $event[0]["MinMembers"] . " To " . $event[0]["MaxMembers"] . " Registered Participants";
                }
            }
        } else {
            if (isset($_POST['email0'])) {
                $email = $_POST['email0'];
                $parti = getParticipant($email);
                if (!empty($parti)) {
                    $eventreg = getEventEntryByP($parti[0]["PID"], $event[0]["EVID"]);
                    if (empty($eventreg)) {
                        $erentrycode = genID($email, $eventcode);
                        $fcode = genFileKey();

                        addRegistration($parti[0]["PID"], $event[0]["EVID"], $erentrycode, $fcode, $admin);

                        Logs("Event Registration: " . $email . " has Participate in " . $event[0]["EVName"]);

                        //QRCode Generation
                        QRGen($erentrycode, $fcode);

                        sendSMS($parti[0]["Mobile"],
                            "Thank You For Participating in " . $event[0]["EVName"] . " Of " .
                            $event[0]["EVDepartment"] . " Department\n\n".
                            "Your Unique Event ID: " . $erentrycode . "\n\n".
                            "Your QR Code Link: " . $GLOBALS['URL'] . "/api/qr/" . $fcode . "\n\n" .
                            "Please Note that If you haven't Paid Fees During Participation, " .
                            "You need to Pay at Event Venue");

                        //QRCode Sending
                        sendMailWithAttach($email, "Yo! Participation Done..",
                            "Thank You For Participating in " . $event[0]["EVName"] .
                            ".\nHere is your QR Code for Attendance.\n" . $GLOBALS['URL'] . "/api/qr/" . $fcode . "\n\n" .
                            "Please Note that If you haven't Paid Fees During Participation, " .
                            "You need to Pay at Event Venue",
                            $parti[0]["FirstName"] . '_' .
                            preg_replace('/\s+/', '_', $event[0]["EVName"]) . $GLOBALS['QR_Postfix'],
                            "qrcodes/" . $fcode . "_qr.png");

                        $result['Status'] = "Success";
                        $result['Code'] = 200;
                        $result['ERCode'] = $erentrycode;
                        $result['Amount'] = $event[0]["EVPrice"];
                        $result['Message'] = "Event Registration Done";
                    } else {
                        Logs("Event Registration: " . $email . " already Participated in This Event");
                        $result['Message'] = "You already Participated in This Event";
                    }
                } else {
                    Logs("Event Registration: " . $email . " is Not Registered Yet");
                    $result['Message'] = "Email is Not Registered Yet";
                }
            } else {
                Logs("Event Registration: Email Not Provided");
                $result['Message'] = "Email Not Provided";
            }
        }
    } else {
        Logs("Event Registration: Wrong Event Selected");
        $result['Message'] = "Wrong Event";
    }
}

//Set Paid
if ($GLOBALS['AdminApiAccess'] == true && isset($_GET['EventPaid']) && isset($_POST['apikey']) && isset($_POST['ercode'])) {
    $apikey = $_POST['apikey'];
    $ercode = $_POST['ercode'];

    $logindata = getLoginByToken($apikey);
    if (!empty($logindata)) {
        $admin = getAdminByID($logindata[0]["AID"]);
        $eventry = getEventEntryByCode($ercode);
        if (!empty($eventry)) {
            $event = getEventByID($eventry[0]["EVID"]);
            if ($eventry[0]["isTeam"] == 1) {
                $teamleader = getParticipantByID($eventry[0]["PID"]);
                if ($eventry[0]["isPaid"] == 0) {
                    $totalprice = ($event[0]["isSinglePrice"] == 1) ? $event[0]["EVPrice"] : ($event[0]["EVPrice"] * count($eventry));
                    setPaid(true, $logindata[0]["AID"], "Cash", $currtimestamp, $ercode);
                    IncAdminPaidByID($admin[0]["TotalFeeCollected"] + $totalprice, $logindata[0]["AID"]);

                    Logs("Set Paid: " . $teamleader[0]["EMail"] . "'s Team has Paid " . $totalprice . "₹ for Event " . $event[0]["EVName"]);

                    $result['Status'] = "Success";
                    $result['Code'] = 200;
                    $result['Message'] = "Team " . $teamleader[0]["EMail"] . " has Paid " . $totalprice . "₹ Successfully";
                } else {
                    Logs("Set Paid: " . $teamleader[0]["EMail"] . "'s Team has Already Paid for Event " . $event[0]["EVName"]);
                    $result['Message'] = "Your Team has Already Paid for This Event!";
                }
            } else {
                $parti = getParticipantByID($eventry[0]["PID"]);
                if ($eventry[0]["isPaid"] == 0) {
                    setPaid(true, $logindata[0]["AID"], "Cash", $currtimestamp, $ercode);
                    IncAdminPaidByID($admin[0]["TotalFeeCollected"] + $event[0]["EVPrice"], $logindata[0]["AID"]);

                    Logs("Set Paid: " . $parti[0]["EMail"] . " has Paid " . $event[0]["EVPrice"] . "₹ for Event " . $event[0]["EVName"]);

                    $result['Status'] = "Success";
                    $result['Code'] = 200;
                    $result['Message'] = "Participant " . $parti[0]["EMail"] . " has Paid " . $event[0]["EVPrice"] . "₹ Successfully";
                } else {
                    Logs("Set Paid: " . $parti[0]["EMail"] . " has Already Paid for Event " . $event[0]["EVName"]);
                    $result['Message'] = "You have Already Paid for This Event!";
                }
            }
        } else {
            Logs("Set Paid: Invalid Event Entry Code " . $ercode);
            $result['Message'] = "Invalid Event Entry Code";
        }
    } else {
        Logs("Set Paid: Unauthorised Access");
        $result['Message'] = "UnAuthorised Access";
    }
}

//Set Attendance
if ($GLOBALS['AdminApiAccess'] == true && isset($_GET['EventAttend']) && isset($_POST['apikey']) && isset($_POST['ercode'])) {
    $apikey = $_POST['apikey'];
    $ercode = $_POST['ercode'];

    $logindata = getLoginByToken($apikey);
    if (!empty($logindata)) {
        $admin = getAdminByID($logindata[0]["AID"]);
        if($admin[0]["isCoordinator"] == 1){
            $eventry = getEventEntryByCode($ercode);
            if (!empty($eventry)) {
                $event = getEventByID($eventry[0]["EVID"]);
                if($eventry[0]["EVID"] == $admin[0]["EventID"]){
                    if ($eventry[0]["isTeam"] == 1) {
                        $teamleader = getParticipantByID($eventry[0]["PID"]);
                        if ($eventry[0]["isAttended"] == 0) {
                            if ($eventry[0]["isPaid"] == 1) {
                                setAttended(true, $logindata[0]["AID"], $currtimestamp, $ercode);

                                foreach ($eventry as $evparti) {
                                    $parti = getParticipantByID($evparti["PID"]);

                                    Logs("Set Attendance: " . $parti[0]["EMail"] . " have Attended This Event " . $event[0]["EVName"]);

                                    sendSMS($parti[0]["Mobile"],
                                        "Hurray! You have attended Your Participated Event, " . $event[0]["EVName"] . " Of " .
                                        $event[0]["EVDepartment"] . " Department\n\n");

                                    sendMail($parti[0]["EMail"], "Hurray! Attendance Done..",
                                        "Hurray! You have attended Your Participated Event, " . $event[0]["EVName"] . " Of " .
                                        $event[0]["EVDepartment"] . " Department\n\n");
                                }
                            } else {
                                Logs("Set Attendance: " . $teamleader[0]["EMail"] . "'s Team not Attended This Event " .
                                    $event[0]["EVName"] . " Because Payment Not Done Yet");
                            }

                            $result['Status'] = "Success";
                            $result['Code'] = 200;
                            $result['Name'] = $parti[0]["FirstName"] . " " . $parti[0]["LastName"] . "'s Team";
                            $result['Event'] = $event[0]["EVName"];
                            $result['EMail'] = $parti[0]["EMail"];
                            $result['isTeam'] = $eventry[0]["isTeam"];
                            $result['IsPaid'] = $eventry[0]["isPaid"];
                            $result['Message'] = "Your Team has Successfully Attend This Event!";
                        } else {
                            Logs("Set Attendance: " . $teamleader[0]["EMail"] . "'s Team has already Attended, Event " . $event[0]["EVName"]);
                            $result['Message'] = "Your Team has Already Attended This Event!";
                        }
                    } else {
                        $parti = getParticipantByID($eventry[0]["PID"]);
                        if ($eventry[0]["isAttended"] == 0) {
                            if ($eventry[0]["isPaid"] == 1) {
                                setAttended(true, $logindata[0]["AID"], $currtimestamp, $ercode);

                                Logs("Set Attendance: " . $parti[0]["EMail"] . " have Attended This Event " . $event[0]["EVName"]);

                                sendSMS($parti[0]["Mobile"],
                                    "Hurray! You have attended Your Participated Event, " . $event[0]["EVName"] . " Of " .
                                    $event[0]["EVDepartment"] . " Department");

                                sendMail($parti[0]["EMail"], "Hurray! Attendance Done..",
                                    "Hurray! You have attended Your Participated Event, " . $event[0]["EVName"] . " Of " .
                                    $event[0]["EVDepartment"] . " Department");
                            } else {
                                Logs("Set Attendance: " . $parti[0]["EMail"] . " not Attended This Event " .
                                    $event[0]["EVName"] . " Because Payment Not Done Yet");
                            }

                            $result['Status'] = "Success";
                            $result['Code'] = 200;
                            $result['Name'] = $parti[0]["FirstName"] . " " . $parti[0]["LastName"];
                            $result['Event'] = $event[0]["EVName"];
                            $result['EMail'] = $parti[0]["EMail"];
                            $result['isTeam'] = $eventry[0]["isTeam"];
                            $result['IsPaid'] = $eventry[0]["isPaid"];
                            $result['Message'] = "You have Successfully Attend This Event!";
                        } else {
                            Logs("Set Attendance: " . $parti[0]["EMail"] . " has already Attended, Event " . $event[0]["EVName"]);
                            $result['Message'] = "You have Already Attended This Event!";
                        }
                    }
                } else {
                    Logs("Set Attendance: Only Event Coordinator Can Set Attendance for This Participant");
                    $result['Message'] = "Only Event Coordinator Can Set Attendance for This Participant";
                }
            } else {
                Logs("Set Attendance: Invalid Event Entry Code " . $ercode);
                $result['Message'] = "Invalid Event Entry Code";
            }
        } else {
            Logs("Set Attendance: Unauthorised Access");
            $result['Message'] = "UnAuthorised Access";
        }
    } else {
        Logs("Set Attendance: Unauthorised Access");
        $result['Message'] = "UnAuthorised Access";
    }
}

//Campaigner Details
if (isset($_GET['CampaignerDetails']) && isset($_POST['apikey'])) {
    $apikey = $_POST['apikey'];

    $logindata = getLoginByToken($apikey);
    if (!empty($logindata)) {
        $admin = getAdminByID($logindata[0]["AID"]);
        if($admin[0]['isCampaigner'] == 1){
            Logs("Campaigner Details: Campaigner Data Gotcha");
            $result['Status'] = "Success";
            $result['Code'] = 200;
            $result['Name'] = $admin[0]['Name'];
            $result['EMail'] = $admin[0]['EMail'];
            $result['Amount'] = $admin[0]['TotalFeeCollected'];
            $result['Message'] = "Got Data";
        } else {
            Logs("Campaigner Details: Unauthorised Access");
            $result['Message'] = "Unauthorised Access!";
        }
    } else {
        Logs("Campaigner Details: Unauthorised Access");
        $result['Message'] = "Unauthorised Access!";
    }
}

//Participants List
if (isset($_GET['ParticipantsList']) && isset($_POST['apikey'])) {
    $apikey = $_POST['apikey'];
    $data = array();

    $logindata = getLoginByToken($apikey);
    if (!empty($logindata)) {
        $entries = getAllRegistrations();
        $admin = getAdminByID($logindata[0]["AID"]);
        foreach($entries as $eventry){
            if(($admin[0]["	isCampaigner"] == 1 && $logindata[0]["AID"] == $eventry["RegAdmin"])
                || ($admin[0]["isCoordinator"] == 1 && $admin[0]["EventID"] == $eventry["EVID"])) {
                $parti = getParticipantByID($eventry["PID"]);
                $event = getEventByID($eventry["EVID"]);

                $dataitem = [
                    "Name" => $parti[0]["FirstName"] . " " . $parti[0]["LastName"],
                    "College" => $parti[0]["College"],
                    "Mobile" => $parti[0]["Mobile"],
                    "Semester" => $parti[0]["Semester"],
                    "EMail" => $parti[0]["EMail"],
                    "Event" => $event[0]["EVName"],
                ];

                array_push($data, $dataitem);
            }
        }
        Logs("Participants List: Participants Data Gotcha");
        $result['Status'] = "Success";
        $result['Code'] = 200;
        $result['Data'] = $data;
        $result['Message'] = "Got Data";
    } else {
        Logs("Participants List: Unauthorised Access");
        $result['Message'] = "Unauthorised Access!";
    }
}

//Certificate Data
if (isset($_GET['Certificate']) && isset($_POST['email']) && isset($_POST['mobile'])) {
    $parti = getParticipant($_POST['email']);
    if (!empty($parti)) {
        if($parti[0]["Mobile"] == $_POST['mobile']){
            $eventregs = getEventEntryByPID($parti[0]["PID"]);
            if(!empty($eventregs)){
                $evdata = array();
                foreach ($eventregs as $reg){
                    $event = getEventByID($reg["EVID"])[0];
                    array_push($evdata, [
                        "EventName" => $event["EVName"],
                        "EventDept" => $event["EVDepartment"],
                        "URL" => "https://visvesmruti.tech/api/cert/" . $reg["FCode"]
                    ]);
                }
                $result['Status'] = "Success";
                $result['Code'] = 200;
                $result['Data'] = $evdata;
                $result['Message'] = "You have " . count($eventregs) . " Certificates";
            } else {
                $result['Message'] = "You didn't Participated in Any Event, so No Certificate!";
            }
        } else {
            $result['Message'] = "Your Mobile Number is Not Registered Before!";
        }
    } else {
        $result['Message'] = "Your Email is Not Registered Before!";
    }
}

echo toJson($result);