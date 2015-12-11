<?php
//set correct content-type-header
header('Content-type: text/calendar; charset=utf-8');
header('Content-Disposition: inline; filename=calendar.ics');

$date = '20150415';
$startTime = '1300';
$endTime = '1500';
$subject = 'Cotizacion';
$desc = 'Reunion con el cliente';

$ical = "BEGIN:VCALENDAR
VERSION:2.0
PRODID:-//hacksw/handcal//NONSGML v1.0//EN
BEGIN:VEVENT
UID:" . md5(uniqid(mt_rand(), true)) . "example.com
DTSTAMP:" . gmdate('Ymd') . 'T' . gmdate('His') . "Z
DTSTART:" . $date . "T" . $startTime . "00Z
DTEND:" . $date . "T" . $endTime . "00Z
SUMMARY:" . $subject . "
DESCRIPTION:" . $desc . "
END:VEVENT
END:VCALENDAR";


echo $ical;
exit;
?>
