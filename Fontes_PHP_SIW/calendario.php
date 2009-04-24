<?php 
// Get values from query string 
include_once('constants.inc');

if(isset($_GET["day"]))   $day = $_GET["day"];     else $day = date("j");
if(isset($_GET["month"])) $month = $_GET["month"]; else $month = date("m");
if(isset($_GET["year"]))  $year = $_GET["year"];   else $year = date("Y");
if(isset($_GET["sel"]))   $sel = $_GET["sel"];     else $sel = "";
if(isset($_GET["what"]))  $what = $_GET["what"];   else $what = "";
if(isset($_GET["field"])) $field = $_GET["field"]; else $field = "";
if(isset($_GET["form"]))  $form = $_GET["form"];   else $form = "";

$currentTimeStamp = strtotime("$year-$month-$day"); 
$monthName = date("m", $currentTimeStamp); 
switch($monthName) {
    case 01: $monthName = "Janeiro"; break;
    case 02: $monthName = "Fevereiro"; break;
    case 03: $monthName = "Março"; break;
    case 04: $monthName = "Abril"; break;
    case 05: $monthName = "Maio"; break;
    case 06: $monthName = "Junho"; break;
    case 07: $monthName = "Julho"; break;
    case "08": $monthName = "Agosto"; break;
    case "09": $monthName = "Setembro"; break;
    case 10: $monthName = "Outubro"; break;
    case 11: $monthName = "Novembro"; break;
    case 12: $monthName = "Dezembro"; break;
}
$numDays = date("t", $currentTimeStamp); 
$counter = 0; 

/*$numEventsThisMonth = 0; 
$hasEvent = false; 
$todaysEvents = "";*/ 
?> 
<html> 
<head> 
<title>MyCalendar</title> 
<link rel="stylesheet" type="text/css" href="calendar.css"> 
<script language="javascript"> 
    function goLastMonth (month,year,form,field) { 
        // If the month is January, decrement the year. 
        if (month == 1) { 
            --year; 
            month = 13; 
        }        
        document.location.href = 'calendario.php?month='+(month-1)+'&year='+year+'&form='+form+'&field='+field; 
    } 
    
    function goNextMonth (month,year,form,field) { 
        // If the month is December, increment the year. 
        if(month == 12) { 
            ++year; 
            month = 0; 
        }
        document.location.href = 'calendario.php?month='+(month+1)+'&year='+year+'&form='+form+'&field='+field; 
    } 
    
    function sendToForm(val,field,form) { 
        // Send back the date value to the form caller. 
        eval("opener.document." + form + "." + field + ".value='" + val + "'"); 
        window.close(); 
    } 
</script> 
</head> 
<body style="margin:0px 0px 0px 0px" class="body"> 
<table width='200' border='0' cellspacing='0' cellpadding='0' class="body"> 
    <tr> 
        <td width='25' colspan='1' align='left' bgcolor='#DAEABD'>
        <input  type='button' class='button' value=' < ' onClick='<?php echo "goLastMonth($month,$year,\"$form\",\"$field\")"; ?>'> 
        </td> 
        <td width='125' align="center" colspan='5' bgcolor="#DAEABD"> 
        <span class='title'><?php echo $monthName . " / " . $year; ?></span><br> 
        </td> 
        <td width='25' colspan='1' align='right' bgcolor='#DAEABD'> 
        <input type='button' class='button' value=' > ' onClick='<?php echo "goNextMonth($month,$year,\"$form\",\"$field\")"; ?>'> 
        </td> 
    </tr> 
    <tr bgcolor="#DAEABD"> 
        <td class='head' align="center" width='25'>D</td> 
        <td class='head' align="center" width='25'>S</td> 
        <td class='head' align="center" width='25'>T</td> 
        <td class='head' align="center" width='25'>Q</td> 
        <td class='head' align="center" width='25'>Q</td> 
        <td class='head' align="center" width='25'>S</td> 
        <td class='head' align="center" width='25'>S</td> 
    </tr> 
    <tr> 
<?php 
    for($i = 1; $i < $numDays+1; $i++, $counter++) { 
        $timeStamp = strtotime("$year-$month-$i"); 
        if($i == 1) { 
            // Workout when the first day of the month is 
            $firstDay = date("w", $timeStamp); 
            
            for($j = 0; $j < $firstDay; $j++, $counter++) 
            echo "<td> </td>"; 
        } 
        
        if($counter % 7 == 0) 
            echo "</tr><tr>";
                
        echo "<td bgcolor='#ffffff' align='center' width='25' ><a class='buttonbar' href='#' onclick=\"sendToForm('".sprintf("%02d/%02d/%04d", $i, $month, $year)."','$field','$form');\"><font>$i</font></a></td>"; 
    } 
?> 
    </tr> 
</table> 
</body> 
</html>