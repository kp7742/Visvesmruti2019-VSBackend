<?php
//api url filter
if (strpos($_SERVER['REQUEST_URI'], "qrview.php")) {
    require_once 'utils.php';
    PlainDie();
}

require_once 'utils.php';
require_once 'global.php';
require_once 'VSEventReg.php';
require_once 'VSEvents.php';
require_once 'VSParticipants.php';

if($GLOBALS['Maintenance'] == false && isset($_GET['code'])){
    $fcode = $_GET['code'];
    $evententry = getEventEntryByFCode($fcode);
    if(!empty($evententry)) {
        $parti = getParticipantByID($evententry[0]["PID"]);
        if(!empty($parti)){
            $event = getEventByID($evententry[0]["EVID"]);
            if(!empty($event)){
                $fpath = "qrcodes/" . $fcode . "_qr.png";
                if(file_exists($fpath)){
                    header("Content-type: image/png");
                    header("Content-Disposition: inline; filename=".$parti[0]["FirstName"]."_".
                        preg_replace('/\s+/', '_', $event[0]["EVName"]).'_QR_VS2K19.png');
                    @readfile($fpath);
                } else {
                    PlainDie("Not Found");
                }
            } else {
                PlainDie("Not Found");
            }
        } else {
            PlainDie("Not Found");
        }
    } else {
        PlainDie("Not Found");
    }
} else {
    PlainDie("Not Found");
}

/*
require_once __DIR__ . '/vendor/autoload.php';
require_once 'utils.php';
require_once 'VSEventReg.php';

use Endroid\QrCode\ErrorCorrectionLevel;
use Endroid\QrCode\QrCode;

if(isset($_GET['code'])){
    $fcode = $_GET['code'];
    $evententry = getEventEntryByFCode($fcode);
    if(!empty($evententry)){
        $erentrycode = $evententry[0]["ERCode"];

        $qrCode = new QrCode($erentrycode);
        $qrCode->setSize(460);
        $qrCode->setWriterByName('png');
        $qrCode->setMargin(20);
        $qrCode->setEncoding('UTF-8');
        $qrCode->setErrorCorrectionLevel(new ErrorCorrectionLevel(ErrorCorrectionLevel::HIGH));
        $qrCode->setForegroundColor(['r' => 104, 'g' => 40, 'b' => 104, 'a' => 0]);
        $qrCode->setBackgroundColor(['r' => 255, 'g' => 255, 'b' => 255, 'a' => 0]);
        $qrCode->setLabel($erentrycode, 30, null, null, ['t' => 0,'r' => 0,'b' => 20,'l' => 0,]);
        $qrCode->setRoundBlockSize(true);
        $qrCode->setValidateResult(false);

        header('Content-Type: '.$qrCode->getContentType());
        echo $qrCode->writeString();
    } else {
        PlainDie("Not Found!");
    }
} else {
    PlainDie("Not Found!!");
}*/