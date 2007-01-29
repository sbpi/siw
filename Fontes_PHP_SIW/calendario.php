<?
header('Expires: '.-1500);
session_start();
include_once('constants.inc');

function Nvl($expressao,$valor) { if (!isset($expressao) || $expressao=='') return $valor; else return $expressao; } 

function FormataDataEdicao($w_dt_grade) { 
  if (nvl($w_dt_grade,'')>'') {
    return date('d/m/Y',$w_dt_grade); 
  } else {
    return null;
  }
}

if ($_GET['vData']>'') $w_data = $_GET['vData']; else $w_data = FormataDataEdicao(time());

if ($p_cliente_session==6601) {
  $w_url     = str_replace('/sgpa/','',$conRootSIW).$conFileVirtual.$_SESSION['P_CLIENTE'];
  $w_caminho = 'sgpa';
} else {
  $w_url     = str_replace('/siw/','',$conRootSIW).$conFileVirtual.$_SESSION['P_CLIENTE'];
  $w_caminho = 'siw';
} 
?>
<html>
<head>
<title>calendário</title>


<script language="javascript"> 
  function ShowDateSelected() 
  {
    window.close(); 
    sDate = document.frmCalendario.txtData.value;  
    opener.document.<? echo $_GET['nmForm']; ?>.<? echo $_GET['nmCampo']; ?>.value = sDate;
    opener.focus();
    //alert(sDate);
  }
</script>

</head>
<BASE HREF="<? echo $w_url; ?>/">
<body onLoad="this.focus()">

<FORM NAME="frmCalendario">
<applet code=ccalendar.class name=ccalendar MAYSCRIPT archive="/<? echo $w_caminho; ?>/cp_calendar/ccalendar.jar" width=235 height=190 id="cal" VIEWASTEXT>
<param name="color_fond" value="F7F7F7">
<param name="color_full" value = "F7F7F7">
<param name="color_case" value="FFFFFF">
<param name="color_Comment" value="D0EBC7">
<param name="color_empty" value="D0EBC7">

<param name="field" value="txtData">
<param name="form" value="frmCalendario">
<param name="format" value="d/m/y">
<param name="day1" value="Domingo">
<param name="day2" value="Segunda-feira">
<param name="day3" value="Terça-feira">
<param name="day4" value="Quarta-feira">
<param name="day5" value="Quinta-feira">
<param name="day6" value="Sexta-feira">
<param name="day7" value="Sábado">
<param name="month1" value="Janeiro">
<param name="month2" value="Fevereiro">
<param name="month3" value="Março">
<param name="month4" value="Abril">
<param name="month5" value="Maio">
<param name="month6" value="Junho">
<param name="month7" value="Julho">
<param name="month8" value="Agosto">
<param name="month9" value="Setembro">
<param name="month10" value="Outubro">
<param name="month11" value="Novembro">
<param name="month12" value="Dezembro">
<param name="dinit" value="<? echo str_replace("/","",$w_data); ?>">
</applet>
<INPUT NAME="txtData" value="<? echo $w_data; ?>" size="10" style="font-family: VAR; font-size: 8pt">
<input type="button" value="Atribuir" name="btnOK" onClick="ShowDateSelected()"  style="font-family: Verdana; font-size: 8pt">
</FORM>
</body>
</html>
