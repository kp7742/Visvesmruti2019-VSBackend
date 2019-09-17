<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once 'utils.php';
require_once 'VSEventReg.php';
require_once 'VSEvents.php';
require_once 'VSParticipants.php';

if(isset($_GET['code'])){
    $fcode = $_GET['code'];
    $evententry = getEventEntryByFCode($fcode);
    if(!empty($evententry)){
        $parti = getParticipantByID($evententry[0]["PID"]);
        if(!empty($parti)){
            $event = getEventByID($evententry[0]["EVID"]);
            if(!empty($event)){
                $pdf = new FPDI();

                $pdf->AddPage();
                $pdf->setSourceFile('certificates/template.pdf');
                $pdf->useTemplate($pdf->importPage(1), null, null, 0, 0, true);
                $pdf->SetTextColor(0, 0, 0);

                $pdf->SetFont('arial', '', 21);
                $pdf->SetXY(32, 95);
                $pdf->Cell(240, 0, $parti[0]["FirstName"]." ".$parti[0]["LastName"], 0, 2, 'C');

                $pdf->SetXY(0, 107);
                $pdf->SetFont('arial', '', 17);
                $pdf->Cell(195, 0, $parti[0]["College"], 0, 2, 'C');

                $pdf->SetXY(79, 107);
                $pdf->SetFont('arial', '', 17);
                $pdf->Cell(250, 0, $parti[0]["Department"], 0, 2, 'C');

                $pdf->SetXY(78, 122);
                $pdf->SetFont('arial', '', 14.8);
                $pdf->Cell(250, 0, $event[0]["EVName"], 0, 2, 'C');

                $pdf->Output($parti[0]["FirstName"].'_VS2K19.pdf', 'I');
            } else {
                PlainDie("Not Found1");
            }
        } else {
            PlainDie("Not Found2");
        }
    } else {
        PlainDie("Not Found3");
    }
} else {
    PlainDie("Not Found4");
}