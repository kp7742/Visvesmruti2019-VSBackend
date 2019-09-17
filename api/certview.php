<?php
//api url filter
if (strpos($_SERVER['REQUEST_URI'], "pdfview.php")) {
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
    if(!empty($evententry) && $evententry[0]["isPaid"] == 1 && $evententry[0]["isAttended"] == 1) {
        $parti = getParticipantByID($evententry[0]["PID"]);
        if(!empty($parti)){
            $event = getEventByID($evententry[0]["EVID"]);
            if(!empty($event)){
                $fpath = 'certificates/' . $fcode . '_certificate.pdf';
                if(file_exists($fpath)){
                    header("Content-type: application/pdf");
                    header("Content-Disposition: inline; filename=".$parti[0]["FirstName"]."_".
                        preg_replace('/\s+/', '_', $event[0]["EVName"]).'_Certificate_VS2K19.pdf');
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
require_once 'VSEvents.php';
require_once 'VSParticipants.php';

if(isset($_GET['code'])){
    $fcode = $_GET['code'];
    $evententry = getEventEntryByFCode($fcode);
    if(!empty($evententry) && $evententry[0]["isAttended"] == 1 && $evententry[0]["isAttended"] == 1){
        $parti = getParticipantByID($evententry[0]["PID"]);
        if(!empty($parti)){
            $event = getEventByID($evententry[0]["EVID"]);
            if(!empty($event)){
                $pdf = new FPDI();

                $pdf->AddPage();
                $pdf->setSourceFile('certificates/template.pdf');
                $pdf->useTemplate($pdf->importPage(1), null, null, 0, 0, true);
                $pdf->SetTextColor(0, 0, 0);

                $pdf->SetFont('courier', '', 57);
                $pdf->SetXY(200, 242);
                $pdf->Cell(240, 0, $parti[0]["FirstName"]." ".$parti[0]["LastName"], 0, 2, 'C');

                $pdf->SetXY(15, 270);
                $college = $parti[0]["College"];
                if(strlen($college) > 20){
                    $pdf->SetFont('courier', '', 32);
                    $pdf->Cell(290, 0, $college, 0, 2, 'C');
                } else {
                    $pdf->SetFont('courier', '', 57);
                    $pdf->Cell(240, 0, $college, 0, 2, 'C');
                }

                $pdf->SetFont('courier', '', 57);
                $pdf->SetXY(330, 270);
                $pdf->Cell(240, 0, $parti[0]["Department"], 0, 2, 'C');

                $pdf->SetXY(330, 298);
                $pdf->Cell(240, 0, $event[0]["EVName"], 0, 2, 'C');

                $pdf->Output($parti[0]["FirstName"]."_".$event[0]["EVName"].'_Certificate_VS2K19.pdf', 'I');
            } else {
                PlainDie("Not Found!");
            }
        } else {
            PlainDie("Not Found!!");
        }
    } else {
        PlainDie("Not Found!!!");
    }
} else {
    PlainDie("Not Found!!!!");
}*/