<!-- #INCLUDE FILE="constants.inc" -->
<%
Function Nvl(expressao,valor)
   If IsNull(expressao) or expressao = "" Then
      Nvl = valor
   Else
      Nvl = expressao
   End If
End Function

Function FormataDataEdicao(w_dt_grade)
  Dim l_dt_grade
  l_dt_grade = Nvl(w_dt_grade,"")
  If l_dt_grade > "" Then
     If Len(l_dt_grade) < 10 Then
        If Right(Mid(l_dt_grade,1,2),1) = "/" Then
           l_dt_grade = "0"&l_dt_grade
        End If
        If Len(l_dt_grade) < 10 and Right(Mid(l_dt_grade,4,2),1) = "/" Then
           l_dt_grade = Left(l_dt_grade,3)&"0"&Right(l_dt_grade,6)
        End If 
     End If
  Else
     l_dt_grade = ""
  End If

  FormataDataEdicao = l_dt_grade

  Set l_dt_grade       = Nothing
End Function

Dim w_data, w_url, w_caminho

If Request.QueryString("vData") > "" Then
   w_data = Request.QueryString("vData")
Else
   w_data = FormataDataEdicao(Date())
End If

If Session("p_cliente") = 6601 Then
   w_url     = replace(conRootSIW,"/sgpa/","") & conFileVirtual & Session("p_cliente")
   w_caminho = "sgpa"
Else
   w_url     = replace(conRootSIW,"/siw/","") & conFileVirtual & Session("p_cliente")
   w_caminho = "siw"
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
<BASE HREF="<%=w_url%>/">
<body onLoad="document.focus()">

<FORM NAME="frmCalendario">
<applet code=ccalendar.class name=ccalendar MAYSCRIPT archive="/<%=w_caminho%>/cp_calendar/ccalendar.jar" width=235 height=190 id="cal" VIEWASTEXT>
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