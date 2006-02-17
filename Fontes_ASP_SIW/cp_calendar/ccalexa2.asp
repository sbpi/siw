<!-- #include virtual="/siw/constants.inc" -->
<!-- #include virtual="/siw/Funcoes.asp" -->
<%
Dim w_data

If Nvl(Request.QueryString("vData"),"") > "" Then
   w_data = Request.QueryString("vData")
Else
   w_data = FormataDataEdicao(Date())
End If
%>
<html>
<head>
<title>calendário</title>


<script language="javascript"> 
  function ShowDateSelected() 
  {
    window.close(); 
    sDate = document.frmCalendario.txtData.value;  
    opener.document.<% = Request.QueryString("nmForm") %>.<% = Request.QueryString("nmCampo") %>.value = sDate;
    opener.focus();
    //alert(sDate);
  }
</script>

</head>
<BASE HREF="<%=replace(conRootSIW & conFileVirtual,"/siw/","")%><%=Session("p_cliente")%>/">
<body onLoad="document.focus()">

<FORM NAME="frmCalendario">
<applet code=ccalendar.class name=ccalendar MAYSCRIPT archive="/siw/cp_calendar/ccalendar.jar" width=235 height=190 id="cal" VIEWASTEXT>
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
<param name="dinit" value="<%=replace(w_data,"/","")%>">
</applet>
<INPUT NAME="txtData" value="<%=w_data%>" size="10" style="font-family: VAR; font-size: 8pt">
<input type="button" value="Atribuir" name="btnOK" onClick="ShowDateSelected()"  style="font-family: Verdana; font-size: 8pt">
</FORM>
</body>
</html>