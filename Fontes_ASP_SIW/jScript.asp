<%
REM =========================================================================
REM Rotina de cabeÁalho
REM -------------------------------------------------------------------------
Sub Cabecalho
   ShowHTML "<HTML>"
end sub
REM -------------------------------------------------------------------------
REM Final da rotina de cabeÁalho
REM =========================================================================

REM =========================================================================
REM Rotina de rodapÈ
REM -------------------------------------------------------------------------
Sub Rodape
   ShowHTML "<HR>"
   'ShowHTML "<CENTER><FONT FACE=""ARIAL"" SIZE=1>Um produto da <A HREF=""http://www.sbpi.com.br"" TARGET=""_BLANK"">SBPI&reg;</A> Sociedade Brasileira para a Pesquisa em Inform·tica.<br>D˙vidas, sugest&otilde;es ou problemas: encaminhar mensagem para <A HREF=""mailto:sbpi@sbpi.com.br""><img src=images/icone/mailto.gif border=0> <i>sbpi@sbpi.com.br</i></A><br>1996-2001 <A HREF=""http://www.sbpi.com.br"" TARGET=""_BLANK"">SBPI&reg;</a> todos os direitos reservados.</font></center"
   ShowHTML "</BODY>"
   ShowHTML "</HTML>"
end sub
REM -------------------------------------------------------------------------
REM Final da rotina de rodapÈ
REM =========================================================================

' FunÁ„o para crÌtica da hora
Sub VerfHora
    Response.Write "function VerfHora(Datac) {" & VbCrLf
    Response.Write "   var numero = '0123456789:';" & VbCrLf
    Response.Write "   var conta=0;" & VbCrLf
    Response.Write "   var i=0;" & VbCrLf
    Response.Write "   if (Datac.length==4){" & VbCrLf
    Response.Write "     var nHora = parseFloat(Datac.substring(0,1));" & VbCrLf
    Response.Write "     var nMin = parseFloat(Datac.substring(2,4));" & VbCrLf
    Response.Write "   }" & VbCrLf
    Response.Write "   else if (Datac.length==5){" & VbCrLf
    Response.Write "     var nHora = parseFloat(Datac.substring(0,2));" & VbCrLf
    Response.Write "     var nMin = parseFloat(Datac.substring(3,5));" & VbCrLf
    Response.Write "   }" & VbCrLf
    Response.Write "   if (Datac.length==0){" & VbCrLf
    Response.Write "      return (true);" & VbCrLf
    Response.Write "   }" & VbCrLf
    Response.Write "   else if ((Datac.length!=5) && (Datac.length!=4)){" & VbCrLf
    Response.Write "      alert('O formato da hora È HH:MM.');" & VbCrLf
    Response.Write "      return (false);" & VbCrLf
    Response.Write "   }" & VbCrLf
    Response.Write "   if (nHora<0 || nHora>23){" & VbCrLf
    Response.Write "       alert('Hora inv·lida !');" & VbCrLf
    Response.Write "       return (false);" & VbCrLf
    Response.Write "   }" & VbCrLf
    Response.Write "   if (nMin<0 || nMin>59){" & VbCrLf
    Response.Write "       alert('Hora inv·lida !');" & VbCrLf
    Response.Write "       return (false);" & VbCrLf
    Response.Write "   }" & VbCrLf
    Response.Write "    for (i=0;i<Datac.length;i++) {" & VbCrLf
    Response.Write "        if (numero.indexOf(Datac.charAt(i))==-1){" & VbCrLf
    Response.Write "           alert('O formato da hora È HH:MM.');" & VbCrLf
    Response.Write "           return (false);" & VbCrLf
    Response.Write "        }" & VbCrLf
    Response.Write "        if (Datac.charAt(i)==':') {" & VbCrLf
    Response.Write "           conta = conta + 1;" & VbCrLf
    Response.Write "        }" & VbCrLf
    Response.Write "    }" & VbCrLf
    Response.Write "    if (conta >1 && conta<1){" & VbCrLf
    Response.Write "       alert('O formato da hora È HH:MM.');" & VbCrLf
    Response.Write "       return (false);" & VbCrLf
    Response.Write "    }" & VbCrLf
    Response.Write "   return (true);" & VbCrLf
    Response.Write "}" & VbCrLf
End Sub

' Imprime uma linha HTML
Sub ShowHtml(Line)
  Response.Write Line & VbCrLf
End Sub

' Abre a tag SCRIPT
Sub ScriptOpen(Language)
  Response.Write VbCrLf + "<SCRIPT LANGUAGE='" & Language & "'><!--" & VbCrLf
End Sub

' Encerra a tag SCRIPT
Sub ScriptClose
  Response.Write "--></SCRIPT>" & VbCrLf
End Sub

' Abre a funÁ„o de validaÁ„o de formul·rios
Sub ValidateOpen(FunctionName)
  Response.Write "function " & FunctionName & " (theForm)" & VbCrLf & "{" & VbCrLf
End Sub

' Encerra a funÁ„o de validaÁ„o de formul·rios
Sub ValidateClose
  Response.Write "  return (true); " & VbCrLf
  Response.Write "} " & VbCrLf
End Sub

' C·lculo de mÛdulo
Sub Modulo
  Response.Write "  function modulo (dividendo,divisor) { " & VbCrLf
  Response.Write "    var quociente = 0; " & VbCrLf
  Response.Write "    var ModN = 0; " & VbCrLf
  Response.Write "    quociente = Math.floor(dividendo/divisor); " & VbCrLf
  Response.Write "    ModN = dividendo - (divisor*quociente); " & VbCrLf
  Response.Write "    return divisor - ModN; " & VbCrLf
  Response.Write "  } " & VbCrLf
End Sub


' Rotina auxiliar ‡ de verificaÁ„o de datas
Sub CheckBranco
  Response.Write "  function checkbranco(elemento){ " & VbCrLf
  Response.Write "    var flagbranco = true " & VbCrLf
  Response.Write "    //alert( 'elemento = ' + elemento) " & VbCrLf
  Response.Write "    for (i=0;i < elemento.length;i++){ " & VbCrLf
  Response.Write "        //alert('elemento.charat( ' + i + ') = ' + elemento.charAt(i) ) " & VbCrLf
  Response.Write "        if (elemento.charAt(i) != ' '){ " & VbCrLf
  Response.Write "            flagbranco = false " & VbCrLf
  Response.Write "        } " & VbCrLf
  Response.Write "    } " & VbCrLf
  Response.Write "    //alert('valor de flagbranco = ' + flagbranco) " & VbCrLf
  Response.Write "    return flagbranco " & VbCrLf
  Response.Write "  } " & VbCrLf
End Sub

Sub CompValor (Valor1, DisplayName1, Operator, Valor2, DisplayName2)
  Dim w_Operator
  Select Case Operator 
         Case "==" w_Operator = " igual a "
         Case "!=" w_Operator = " diferente de "
         Case ">"  w_Operator = " maior que "
         Case "<"  w_Operator = " menor que "
         Case ">=" w_Operator = " maior ou igual a "
         Case "=>" w_Operator = " maior ou igual a "
         Case "<=" w_Operator = " menor ou igual a "
         Case "=<" w_Operator = " menor ou igual a "
  End Select
  Response.Write "  var V1 = theForm." & Valor1 & ".value; " & VbCrLf
  If InStr("1234567890", Mid(Valor2,1,1)) = 0 then
    Response.Write "   var V2 = theForm." & Valor2 & ".value;" & VbCrLf
  Else
    Response.Write "   var V2 = '" & Valor2 & "';" & VbCrLf
  end if
  Response.Write "  if (V1.length != 0 && V2.length != 0) { " & VbCrLf
  Response.Write "     V1 = V1.toString().replace(/\$|\./g,''); " & VbCrLf
  Response.Write "     V2 = V2.toString().replace(/\$|\./g,''); " & VbCrLf
  Response.Write "     V1 = V1.toString().replace(',','.'); " & VbCrLf
  Response.Write "     V2 = V2.toString().replace(',','.'); " & VbCrLf
  'Response.Write "     alert('V1='+V1+'\nV2='+V2+'\nv1='+v1+'\nv2='+v2); " & VbCrLf
  Response.Write "     if (isNaN(V1)) { " & VbCrLf
  Response.Write "        alert('" & DisplayName1 & " n„o È um valor v·lido!.'); " & VbCrLf
  Response.Write "        theForm." & Valor1 & ".focus(); " & VbCrLf
  Response.Write "        return false; " & VbCrLf
  Response.Write "     } " & VbCrLf
  Response.Write "     if (isNaN(V2)) { " & VbCrLf
  Response.Write "        alert('" & DisplayName2 & " n„o È um valor v·lido!.'); " & VbCrLf
  If InStr("1234567890", Mid(Valor2,1,1)) = 0 then
     Response.Write "        theForm." & Valor2 & ".focus(); " & VbCrLf
  Else
     Response.Write "        theForm." & Valor1 & ".focus(); " & VbCrLf
  end if
  Response.Write "        return false; " & VbCrLf
  Response.Write "     } " & VbCrLf
  Response.Write "     var v1 = parseFloat(V1);" & VbCrLf
  Response.Write "     var v2 = parseFloat(V2);" & VbCrLf
  'Response.Write "     alert('V1='+V1+'\nV2='+V2+'\nv1='+v1+'\nv2='+v2); " & VbCrLf
  Response.Write "     if (!(v1 " & Operator & " v2)) { " & VbCrLf
  Response.Write "        alert('" & DisplayName1 & " deve ser " &w_Operator & DisplayName2 & ".'); " & VbCrLf
  Response.Write "        theForm." & Valor1 & ".focus(); " & VbCrLf
  Response.Write "        return false; " & VbCrLf
  Response.Write "     } " & VbCrLf
  Response.Write "  } " & VbCrLf
End Sub

' Rotina de comparaÁ„o de datas
Sub CompData (Date1, DisplayName1, Operator, Date2, DisplayName2)
  Dim w_Operator
  Select Case Operator 
         Case "==" w_Operator = " igual a "
         Case "!=" w_Operator = " diferente de "
         Case ">"  w_Operator = " maior que "
         Case "<"  w_Operator = " menor que "
         Case ">=" w_Operator = " maior ou igual a "
         Case "=>" w_Operator = " maior ou igual a "
         Case "<=" w_Operator = " menor ou igual a "
         Case "=<" w_Operator = " menor ou igual a "
  End Select
  Response.Write "  var D1 = theForm." & Date1 & ".value; " & VbCrLf
  If InStr("1234567890", Mid(Date2,1,1)) = 0 then
    Response.Write "   var D2 = theForm." & Date2 & ".value;" & VbCrLf
  Else
    Response.Write "   var D2 = '" & Date2 & "';" & VbCrLf
  end if
  Response.Write "  if (D1.length != 0 && D2.length != 0) { " & VbCrLf
  Response.Write "   var d1; " & VbCrLf
  Response.Write "   var m1; " & VbCrLf
  Response.Write "   var a1; " & VbCrLf
  Response.Write "   var h1; " & VbCrLf
  Response.Write "   var d2; " & VbCrLf
  Response.Write "   var m2; " & VbCrLf
  Response.Write "   var a2; " & VbCrLf
  Response.Write "   var h2; " & VbCrLf
  Response.Write "   var Data1; " & VbCrLf
  Response.Write "   var Data2; " & VbCrLf
  Response.Write "   if (D1.length == 17) { " & VbCrLf
  Response.Write "      d1 = D1.substr(0,2); " & VbCrLf
  Response.Write "      m1 = D1.substr(3,2); " & VbCrLf
  Response.Write "      a1 = D1.substr(6,4); " & VbCrLf
  Response.Write "      h1 = D1.substr(12,2) + D1.substr(15,2); " & VbCrLf
  Response.Write "      d2 = D2.substr(0,2); " & VbCrLf
  Response.Write "      m2 = D2.substr(3,2); " & VbCrLf
  Response.Write "      a2 = D2.substr(6,4); " & VbCrLf
  Response.Write "      h2 = D2.substr(12,2) + D2.substr(15,2); " & VbCrLf
  Response.Write "      Data1 = a1 + m1 + d1 + h1; " & VbCrLf
  Response.Write "      Data2 = a2 + m2 + d2 + h2; " & VbCrLf
  Response.Write "   } " & VbCrLf
  Response.Write "   if (D1.length == 10) { " & VbCrLf
  Response.Write "      d1 = D1.substr(0,2); " & VbCrLf
  Response.Write "      m1 = D1.substr(3,2); " & VbCrLf
  Response.Write "      a1 = D1.substr(6,4); " & VbCrLf
  Response.Write "      d2 = D2.substr(0,2); " & VbCrLf
  Response.Write "      m2 = D2.substr(3,2); " & VbCrLf
  Response.Write "      a2 = D2.substr(6,4); " & VbCrLf
  Response.Write "      Data1 = a1 + m1 + d1; " & VbCrLf
  Response.Write "      Data2 = a2 + m2 + d2; " & VbCrLf
  Response.Write "   } " & VbCrLf
  Response.Write "   if (D1.length == 7) { " & VbCrLf
  Response.Write "      d1 = '01'; " & VbCrLf
  Response.Write "      m1 = D1.substr(0,2); " & VbCrLf
  Response.Write "      a1 = D1.substr(3,6); " & VbCrLf
  Response.Write "      d2 = '01'; " & VbCrLf
  Response.Write "      m2 = D2.substr(0,2); " & VbCrLf
  Response.Write "      a2 = D2.substr(3,7); " & VbCrLf
  Response.Write "      Data1 = a1 + m1 + d1; " & VbCrLf
  Response.Write "      Data2 = a2 + m2 + d2; " & VbCrLf
  Response.Write "   } " & VbCrLf
  'Response.Write "alert('d1: ' + d1 + ' - m1: ' + m1 + ' - a1: ' + a1 + ' - Data1: ' + Data1 + '\nd2: ' + d2 + ' - m2: ' + m2 + ' - a2: ' + a2 + ' - Data2: ' + Data2); " & VbCrLf
  Response.Write "   if (!(Data1 " & Operator & " Data2)) { " & VbCrLf
  Response.Write "      alert('" & DisplayName1 & " deve ser " &w_Operator & DisplayName2 & ".'); " & VbCrLf
  Response.Write "      theForm." & Date1 & ".focus(); " & VbCrLf
  Response.Write "      return (false); " & VbCrLf
  Response.Write "   } " & VbCrLf
  Response.Write " } " & VbCrLf
End Sub

Sub toMoney
 Response.Write " function toMoney(campo, fmt) { " & VbCrLf
 Response.Write "  num = campo.toString().replace(/\$|\,/g,''); " & VbCrLf
 Response.Write "  if (isNaN(num)) { " & VbCrLf
 Response.Write "    return false;} " & VbCrLf
 Response.Write "  if (fmt.toUpperCase() == 'US') { " & VbCrLf
 Response.Write "   cents = Math.floor((num*100+0.5)%100); " & VbCrLf
 Response.Write "   num = Math.floor((num*100+0.5)/100).toString(); " & VbCrLf
 Response.Write "   if(cents < 10) cents = '0' + cents; " & VbCrLf
 Response.Write "   for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++) " & VbCrLf
 Response.Write "   num = num.substring(0,num.length-(4*i+3)) + "," +num.substring(num.length-(4*i+3)); " & VbCrLf
 Response.Write "   return (num + '.' + cents); " & VbCrLf
 Response.Write "  } " & VbCrLf
 Response.Write "  if (fmt.toUpperCase() == 'BR') { " & VbCrLf
 Response.Write "   cents = Math.floor((num*100+0.5)%100); " & VbCrLf
 Response.Write "   num = Math.floor((num*100+0.5)/100).toString(); " & VbCrLf
 Response.Write "   if(cents < 10) cents = '0' + cents; " & VbCrLf
 Response.Write "   for (var i = 0; i < Math.floor((num.length-(1+i))/3); i++) " & VbCrLf
 Response.Write "   num = num.substring(0,num.length-(4*i+3)) + '.' +num.substring(num.length-(4*i+3)); " & VbCrLf
 Response.Write "   return (num + ',' + cents); " & VbCrLf
 Response.Write "  } " & VbCrLf
 Response.Write "  return false; " & VbCrLf
 Response.Write " } " & VbCrLf
End Sub
 
Sub DecodeDate
  Response.Write "function LeapYear(intYear) { " & VbCrLf
  Response.Write " if (intYear % 100 == 0) { " & VbCrLf
  Response.Write "  if (intYear % 400 == 0) { return true; } " & VbCrLf
  Response.Write " } " & VbCrLf
  Response.Write "  else { " & VbCrLf
  Response.Write " if ((intYear % 4) == 0) { return true; } " & VbCrLf
  Response.Write " } " & VbCrLf
  Response.Write " return false; " & VbCrLf
  Response.Write " } " & VbCrLf
  Response.Write " function DecodeDate(date) { " & VbCrLf
  Response.Write "  var day, month, year; " & VbCrLf
  Response.Write "  if (date.length < 10) return false; " & VbCrLf
  Response.Write "  day = date.substr(0, 2); " & VbCrLf
  Response.Write "  month = date.substr(3, 2); " & VbCrLf
  Response.Write "  year = date.substr(6, 4); " & VbCrLf
  Response.Write "  if (parseInt(month) == 4 || parseInt(month) == 6 || parseInt(month) == 9 || parseInt(month) == 11) { " & VbCrLf
  Response.Write "   if (parseInt(day) == 31) return false; } " & VbCrLf
  Response.Write "  if (LeapYear(parseInt(year))) { " & VbCrLf
  Response.Write "    if (parseInt(day) > 29) return false;  " & VbCrLf
  Response.Write "    else { " & VbCrLf
  Response.Write "          if (parseInt(day) > 28) return false; " & VbCrLf
  Response.Write "         } " & VbCrLf
  Response.Write "   } " & VbCrLf
  Response.Write "  return (new Date(parseInt(year), parseInt(month) - 1, parseInt(day))); " & VbCrLf
  Response.Write " } " & VbCrLf         
End Sub

Sub FormataData
 Response.Write "function FormataData(campo, teclapres) { " & VbCrLf
 Response.Write "    var tecla = teclapres.keyCode; " & VbCrLf
 Response.Write "    vr = campo.value; " & VbCrLf
 Response.Write "    vr = vr.replace( '/', '' ); " & VbCrLf
 Response.Write "    vr = vr.replace( '/', '' ); " & VbCrLf
 Response.Write "    tam = vr.length + 1; " & VbCrLf
 Response.Write "    if (tecla == 8 ) tam = tam - 1 ; " & VbCrLf
 Response.Write "    if ( tecla != 9 && tecla != 8 ) { " & VbCrLf
 Response.Write "       if ( tecla == 8 || tecla >= 48 && tecla <= 57 || tecla >= 96 && tecla <= 105 ) { " & VbCrLf
 Response.Write "           if ( tam <= 2 ) campo.value = vr ; " & VbCrLf
 Response.Write "           if ( tam > 2 && tam < 5 ) campo.value = vr.substr( 0, 2 ) + '/' + vr.substr( 2, tam ); " & VbCrLf
 Response.Write "           if ( tam >= 5 && tam <= 10 ) campo.value = vr.substr( 0, 2 ) + '/' + vr.substr( 2, 2 ) + '/' + vr.substr( 4, tam );  " & VbCrLf
 Response.Write "      } " & VbCrLf
 Response.Write "   } " & VbCrLf
 Response.Write "} " & VbCrLf
End Sub

Sub FormataMat
 Response.Write "function FormataMat (campo,teclapres) { " & VbCrLf
 Response.Write "    var tecla = teclapres.keyCode; " & VbCrLf
 Response.Write "    vr = campo.value; " & VbCrLf
 Response.Write "    vr = vr.replace( '-', '' ); " & VbCrLf
 Response.Write "    tam = vr.length; " & VbCrLf
 Response.Write "    if (tam < 9 && tecla != 7){ tam = vr.length + 1 ; } " & VbCrLf
 Response.Write "    if (tecla == 7 ){    tam = tam - 1 ; } " & VbCrLf
 Response.Write "    if ( tecla == 7 || tecla >= 48 && tecla <= 57 || tecla >= 96 && tecla <= 105 ){ " & VbCrLf
 Response.Write "         if ( (tam > 8) && (tam <= 10) ){ " & VbCrLf
 Response.Write "             campo.value = vr.substr( 0, tam - 1 ) + '-' + vr.substr( tam - 1, tam ) ; } " & VbCrLf
 Response.Write "    } " & VbCrLf
 Response.Write "} " & VbCrLf
End Sub


Sub CriticaNumero
 Response.Write "function CriticaNumero(campo, teclapres) { " & VbCrLf
 Response.Write "    var tecla = teclapres.keyCode; " & VbCrLf
  Response.Write "     alert(tecla); " & VbCrLf
 'Response.Write "    var numero = '0123456789'; "& VbCrLf
 'Response.Write "    vr = campo.value; " & VbCrLf
 'Response.Write "   if (numero.indexOf(vr)==-1){ " & VbCrLf
 'Response.Write "     alert('O Campo n„o È v·lido !'); " & VbCrLf
 'Response.Write "     return false;" & VbCrLf
 'Response.Write "   }" & VbCrLf 
 Response.Write " }" & VbCrLf 
End Sub

Sub DaysLeft
 Response.Write " function DaysLeft(date) { " & VbCrLf
 Response.Write "  var now = date.getDate(); " & VbCrLf
 Response.Write "  var year = date.getYear(); " & VbCrLf
 Response.Write "  if (year < 2000) year += 1900; // Y2K fix " & VbCrLf
 Response.Write "  var month = date.getMonth(); " & VbCrLf
 Response.Write "  var monarr = new Array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31); " & VbCrLf
 Response.Write "  if (((year % 4 == 0) && (year % 100 != 0)) || (year % 400 == 0)) monarr[1] = '29'; " & VbCrLf
 Response.Write "  return (monarr[month]-now); " & VbCrLf
 Response.Write " } " & VbCrLf
End Sub

Sub FormataValor
 ShowHTML "function FormataValor(campo, maximo, tammax, teclapres) {"
 ShowHTML "    var tecla = teclapres.keyCode;"
 ShowHTML "    ant_vr = campo.value;"
 ShowHTML "    vr = campo.value;"
 ShowHTML "    vr = vr.replace( ',', '' );"
 ShowHTML "    vr = vr.replace( '.', '' );"
 ShowHTML "    vr = vr.replace( '.', '' );"
 ShowHTML "    vr = vr.replace( '.', '' );"
 ShowHTML "    vr = vr.replace( '.', '' );"
 ShowHTML "    tam = vr.length + 1;"
 ShowHTML "    if (tam < tammax && tecla != 8){ tam = vr.length + 1 ; }"
 ShowHTML "    if (tecla == 8 ) { tam = tam - 1 ; }"
 ShowHTML "    if (tecla == 8 && tam < 1) { vr.value = ''; return true; }"
 ShowHTML "    if ( tecla == 8 || tecla >= 48 && tecla <= 57 || tecla >= 96 && tecla <= 105 ){"
 ShowHTML "        if ( tam <= tammax ){ campo.value = vr ; } "
 ShowHTML "         if ( (tam > tammax) && (tam <= (tammax + 3)) ){"
 ShowHTML "             campo.value = vr.substr( 0, tam - tammax ) + ',' + vr.substr( tam - tammax, tam ) ; }"
 ShowHTML "         if ( (tam >= (tammax+4)) && (tam <= (tammax + 6)) ){"
 ShowHTML "             campo.value = vr.substr( 0, tam - (tammax + 3) ) + '.' + vr.substr( tam - (tammax + 3), 3 ) + ',' + vr.substr( tam - tammax, tam ) ; }"
 ShowHTML "         if ( (tam >= (tammax + 7)) && (tam <= (tammax + 9)) ){"
 ShowHTML "             campo.value = vr.substr( 0, tam - (tammax + 6) ) + '.' + vr.substr( tam - (tammax + 6), 3 ) + '.' + vr.substr( tam - (tammax + 3), 3 ) + ',' + vr.substr( tam - tammax, tam ) ; }"
 ShowHTML "         if ( (tam >= (tammax + 10)) && (tam <= (tammax + 12)) ){"
 ShowHTML "             campo.value = vr.substr( 0, tam - (tammax + 9) ) + '.' + vr.substr( tam - (tammax + 9), 3 ) + '.' + vr.substr( tam - (tammax + 6), 3 ) + '.' + vr.substr( tam - (tammax + 3), 3 ) + ',' + vr.substr( tam - tammax, tam ) ; }"
 ShowHTML "         if ( (tam >= (tammax + 13)) && (tam <= (tammax + 15)) ){"
 ShowHTML "             campo.value = vr.substr( 0, tam - (tammax + 12) ) + '.' + vr.substr( tam - (tammax + 12), 3 ) + '.' + vr.substr( tam - (tammax + 9), 3 ) + '.' + vr.substr( tam - (tammax + 6), 3 ) + '.' + vr.substr( tam - (tammax + 3), 3 ) + ',' + vr.substr( tam - tammax, tam ) ;}"
 ShowHTML "      if ( campo.value.length + 1 > maximo ) { campo.value = ant_vr.substr(0, maximo -1); }"
 ShowHTML "    }"
 ShowHTML "}"
End Sub

Sub FormataCPF
 Response.Write "function FormataCPF (campo,teclapres) { " & VbCrLf
 Response.Write "    var tecla = teclapres.keyCode; " & VbCrLf
 Response.Write "    vr = campo.value; " & VbCrLf
 Response.Write "    vr = vr.replace( '-', '' ); " & VbCrLf
 Response.Write "    vr = vr.replace( '.', '' ); " & VbCrLf
 Response.Write "    vr = vr.replace( '.', '' ); " & VbCrLf
 Response.Write "    tam = vr.length; " & VbCrLf
 Response.Write "    if (tam < 11 && tecla != 8){ tam = vr.length + 1 ; } " & VbCrLf
 Response.Write "    if (tecla == 8 ){    tam = tam - 1 ; } " & VbCrLf
 Response.Write "    if ( tecla == 8 || tecla >= 48 && tecla <= 57 || tecla >= 96 && tecla <= 105 ){ " & VbCrLf
 Response.Write "        if ( tam <= 2 ){ " & VbCrLf
 Response.Write "             campo.value = vr ; } " & VbCrLf
 Response.Write "         if ( (tam > 2) && (tam <= 5) ){ " & VbCrLf
 Response.Write "             campo.value = vr.substr( 0, tam - 2 ) + '-' + vr.substr( tam - 2, tam ) ; } " & VbCrLf
 Response.Write "         if ( (tam >= 6) && (tam <= 8) ){ " & VbCrLf
 Response.Write "             campo.value = vr.substr( 0, tam - 5 ) + '.' + vr.substr( tam - 5, 3 ) + '-' + vr.substr( tam - 2, tam ) ; } " & VbCrLf
 Response.Write "         if ( (tam >= 9) && (tam <= 11) ){ " & VbCrLf
 Response.Write "             campo.value = vr.substr( 0, tam - 8 ) + '.' + vr.substr( tam - 8, 3 ) + '.' + vr.substr( tam - 5, 3 ) + '-' + vr.substr( tam - 2, tam ) ; } " & VbCrLf
 Response.Write "    } " & VbCrLf
 Response.Write "} " & VbCrLf
End Sub

Sub FormataCNPJ
 Response.Write "function FormataCNPJ (campo,teclapres) { " & VbCrLf
 Response.Write "    var tecla = teclapres.keyCode; " & VbCrLf
 Response.Write "    vr = campo.value; " & VbCrLf
 Response.Write "    vr = vr.replace( '-', '' ); " & VbCrLf
 Response.Write "    vr = vr.replace( '/', '' ); " & VbCrLf
 Response.Write "    vr = vr.replace( '.', '' ); " & VbCrLf
 Response.Write "    vr = vr.replace( '.', '' ); " & VbCrLf
 Response.Write "    tam = vr.length; " & VbCrLf
 Response.Write "    if (tam < 14 && tecla != 8){ tam = vr.length + 1 ; } " & VbCrLf
 Response.Write "    if (tecla == 8 ){    tam = tam - 1 ; } " & VbCrLf
 Response.Write "    if ( tecla == 8 || tecla >= 48 && tecla <= 57 || tecla >= 96 && tecla <= 105 ){ " & VbCrLf
 Response.Write "        if ( tam <= 2 ){ " & VbCrLf
 Response.Write "             campo.value = vr ; } " & VbCrLf
 Response.Write "         if ( (tam > 2) && (tam <= 5) ){ " & VbCrLf
 Response.Write "             campo.value = vr.substr( 0, 2 ) + '.' + vr.substr( 2, tam ) ; } " & VbCrLf
 Response.Write "         if ( (tam >= 6) && (tam <= 8) ){ " & VbCrLf
 Response.Write "             campo.value = vr.substr( 0, 2 ) + '.' + vr.substr( 2, 3 ) + '.' + vr.substr( 5, tam ) ; } " & VbCrLf
 Response.Write "         if ( (tam >= 9) && (tam <= 12) ){ " & VbCrLf
 Response.Write "             campo.value = vr.substr( 0, 2 ) + '.' + vr.substr( 2, 3 ) + '.' + vr.substr( 5, 3 ) + '/' + vr.substr( 8, tam ) ; } " & VbCrLf
 Response.Write "         if ( (tam >= 13) && (tam <= 14) ){ " & VbCrLf
 Response.Write "             campo.value = vr.substr( 0, 2 ) + '.' + vr.substr( 2, 3 ) + '.' + vr.substr( 5, 3 ) + '/' + vr.substr( 8, 4 ) + '-' + vr.substr( 12, tam ) ; } " & VbCrLf
 Response.Write "    } " & VbCrLf
 Response.Write "} " & VbCrLf
End Sub

Sub FormataCEP
 Response.Write "function FormataCEP (campo,teclapres) { " & VbCrLf
 Response.Write "    var tecla = teclapres.keyCode; " & VbCrLf
 Response.Write "    vr = campo.value; " & VbCrLf
 Response.Write "    vr = vr.replace( '.', '' ); " & VbCrLf
 Response.Write "    vr = vr.replace( '-', '' ); " & VbCrLf
 Response.Write "    tam = vr.length; " & VbCrLf
 Response.Write "    if (tam < 2 && tecla != 8){ tam = vr.length + 1 ; } " & VbCrLf
 Response.Write "    if (tecla == 8 ){    tam = tam - 1 ; } " & VbCrLf
 Response.Write "    if ( tecla == 8 || tecla >= 48 && tecla <= 57 || tecla >= 96 && tecla <= 105 ){ " & VbCrLf
 Response.Write "         if ( (tam <= 5) ){ campo.value = vr ; } " & VbCrLf
 Response.Write "         else { campo.value = vr.substr( 0, 5 )  + '-' + vr.substr( 5, tam ); } " & VbCrLf
 Response.Write "    } " & VbCrLf
 Response.Write "} " & VbCrLf
End Sub

Sub FormataDataHora
 Response.Write "function FormataDataHora(campo, teclapres) { " & VbCrLf
 Response.Write "    var tecla = teclapres.keyCode; " & VbCrLf
 Response.Write "    vr = campo.value; " & VbCrLf
 Response.Write "    vr = vr.replace( ':', '' ); " & VbCrLf
 Response.Write "    vr = vr.replace( ',', '' ); " & VbCrLf
 Response.Write "    vr = vr.replace( ' ', '' ); " & VbCrLf
 Response.Write "    vr = vr.replace( '/', '' ); " & VbCrLf
 Response.Write "    vr = vr.replace( '/', '' ); " & VbCrLf
 Response.Write "    tam = vr.length + 1; " & VbCrLf
 Response.Write "    if (tecla == 8 ){    tam = tam - 1 ; } " & VbCrLf
 Response.Write "    if ( tecla != 9 && tecla != 8 ){ " & VbCrLf
 Response.Write "    if ( tecla == 8 || tecla >= 48 && tecla <= 57 || tecla >= 96 && tecla <= 105 ){ " & VbCrLf
 Response.Write "        if ( tam <= 2 ){ " & VbCrLf
 Response.Write "             campo.value = vr ; } " & VbCrLf
 Response.Write "        if ( tam > 2 && tam < 5 ) " & VbCrLf
 Response.Write "            campo.value = vr.substr( 0, 2 ) + '/' + vr.substr( 2, tam ); " & VbCrLf
 Response.Write "        if ( tam >= 5 && tam < 10 ) " & VbCrLf
 Response.Write "            campo.value = vr.substr( 0, 2 ) + '/' + vr.substr( 2, 2 ) + '/' + vr.substr( 4, tam );  " & VbCrLf
 Response.Write "        if ( tam >=10 && tam <= 11 ) " & VbCrLf
 Response.Write "            campo.value = vr.substr( 0, 2 ) + '/' + vr.substr( 2, 2 ) + '/' + vr.substr( 4, 4 ) + ', ' + vr.substr( 8, tam );  " & VbCrLf
 Response.Write "        if ( tam >=12 ) " & VbCrLf
 Response.Write "            campo.value = vr.substr( 0, 2 ) + '/' + vr.substr( 2, 2 ) + '/' + vr.substr( 4, 4 ) + ', ' + vr.substr( 8, 2 ) + ':' + vr.substr( 10, tam );  " & VbCrLf
 Response.Write "    } " & VbCrLf
 Response.Write "  } " & VbCrLf
 Response.Write "} " & VbCrLf
End Sub

Sub FormataDataMA
 Response.Write "function FormataDataMA(campo, teclapres) { " & VbCrLf
 Response.Write "    var tecla = teclapres.keyCode; " & VbCrLf
 Response.Write "    vr = campo.value; " & VbCrLf
 Response.Write "    vr = vr.replace( '/', '' ); " & VbCrLf
 Response.Write "    tam = vr.length + 1; " & VbCrLf
 Response.Write "    if (tecla == 8 ){    tam = tam - 1 ; } " & VbCrLf
 Response.Write "    if ( tecla == 8 || tecla >= 48 && tecla <= 57 || tecla >= 96 && tecla <= 105 ){ " & VbCrLf
 Response.Write "        if ( tam <= 2 ) campo.value = vr ;  " & VbCrLf
 Response.Write "        if ( tam > 2 ) campo.value = vr.substr( 0, 2 ) + '/' + vr.substr( 2, tam ); " & VbCrLf
 Response.Write "    } " & VbCrLf
 Response.Write "} " & VbCrLf
End Sub

' Abre a tag SCRIPT
Sub Validate(VariableName, DisplayName, DataType, ValueRequired, MinimumLength, MaximumLength, AllowLetters, AllowDigits)
  Dim checkOK
  If Instr("SELECT,HIDDEN",uCase(DataType)) = 0 Then
     Response.Write "  while (theForm." & VariableName & ".value.substring(0,1) == ' ') {" & VbCrLf
     Response.Write "     theForm." & VariableName & ".value = theForm." & VariableName & ".value.substring(1,theForm." & VariableName & ".value.length);" & VbCrLf
     Response.Write "  }" & VbCrLf
  End If
  If ValueRequired > "" Then
    If uCase(DataType) = "SELECT" Then
      Response.Write "  if (theForm." & VariableName & ".selectedIndex < 1)" & VbCrLf
    Else
      Response.Write "  if (theForm." & VariableName & ".value == '')" & VbCrLf
    End If
    Response.Write "  {" & VbCrLf
    Response.Write "    alert('Favor informar um valor para o campo " & DisplayName & "');" & VbCrLf
    If uCase(DataType) <> "HIDDEN" Then Response.Write "    theForm." & VariableName & ".focus();" & VbCrLf End If
    Response.Write "    return (false);" & VbCrLf
    Response.Write "  }" & VbCrLf
    Response.Write VbCrLf
  End If
  IF MinimumLength > "" Then
    Response.Write "  if (theForm." & VariableName & ".value.length < " & MinimumLength & " && theForm." & VariableName & ".value != '')" & VbCrLf
    Response.Write "  {" & VbCrLf
    Response.Write "    alert('Favor digitar pelo menos " & MinimumLength & " posiÁıes no campo " & DisplayName & "');" & VbCrLf
    If uCase(DataType) <> "HIDDEN" Then Response.Write "    theForm." & VariableName & ".focus();" & VbCrLf End If
    Response.Write "    return (false);" & VbCrLf
    Response.Write "  }" & VbCrLf
    Response.Write VbCrLf
  End If
  If MaximumLength > "" Then
    Response.Write "  if (theForm." & VariableName & ".value.length > " & MaximumLength & " && theForm." & VariableName & ".value != '')" & VbCrLf
    Response.Write "  {" & VbCrLf
    Response.Write "    alert('Favor digitar no m·ximo " & MaximumLength & " posiÁıes no campo " & DisplayName & "');" & VbCrLf
    If uCase(DataType) <> "HIDDEN" Then Response.Write "    theForm." & VariableName & ".focus();" & VbCrLf End If
    Response.Write "    return (false);" & VbCrLf
    Response.Write "  }" & VbCrLf
    Response.Write VbCrLf
  End If
  If AllowLetters > "" OR AllowDigits > "" Then
    checkOK = ""
    If AllowLetters > "" Then
      If AllowLetters = "1" Then
        checkOK = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz¿¡¬√«»… ÃÕŒ“”‘’Ÿ⁄€‹¿»Ã“Ÿ¬ Œ‘€‡·‚„ÁÈÍÌÓÛÙı˙˚¸‡ËÏÚÏ‚ÍÓÙ˚0123456789-,.()-:;[]{}*&%$#@!/∫™?<>|+=_\""\' "
      Else
        checkOK = checkOK & AllowLetters
      End If   
    End If
    If AllowDigits > "" Then
      If AllowDigits = "1" Then
        checkOK = checkOK & "0123456789-.,/: "
      Else
        checkOK = checkOK & AllowDigits
      End If   
    End If
    Response.Write "  var checkOK = '" & checkOK & "';" & VbCrLf
    Response.Write "  var checkStr = theForm." & VariableName & ".value;" & VbCrLf
    Response.Write "  var allValid = true;" & VbCrLf
    Response.Write "  for (i = 0;  i < checkStr.length;  i++)" & VbCrLf
    Response.Write "  {" & VbCrLf
    Response.Write "    ch = checkStr.charAt(i);" & VbCrLf
    'Response.Write "    alert('Letra=' + ch + '\nCÛdigo: ' + checkStr.charCodeAt(i));" & VbCrLf
    Response.Write "    if ((checkStr.charCodeAt(i) != 13) && (checkStr.charCodeAt(i) != 10) && (checkStr.charAt(i) != '\\')) {" & VbCrLf
    Response.Write "       for (j = 0;  j < checkOK.length;  j++) {" & VbCrLf
    Response.Write "         if (ch == checkOK.charAt(j))" & VbCrLf
    Response.Write "           break;" & VbCrLf
    Response.Write "       } " & VbCrLf
    Response.Write "       if (j == checkOK.length)" & VbCrLf
    Response.Write "       {" & VbCrLf
    Response.Write "         allValid = false;" & VbCrLf
    Response.Write "         break;" & VbCrLf
    Response.Write "       }" & VbCrLf
    Response.Write "    } " & VbCrLf
    Response.Write "  }" & VbCrLf
    Response.Write "  if (!allValid)" & VbCrLf
    Response.Write "  {" & VbCrLf
    If AllowLetters > "" AND AllowDigits > "" Then
      Response.Write "    alert('VocÍ digitou caracteres inv·lidos no campo " & DisplayName & ".');" & VbCrLf
    ElseIf AllowLetters > "" AND AllowDigits = "" Then
      Response.Write "    alert('Favor digitar apenas letras no campo " & DisplayName & ".');" & VbCrLf
    ElseIf AllowLetters = "" AND AllowDigits > "" Then
      Response.Write "    alert('Favor digitar apenas n˙meros no campo " & DisplayName & ".');" & VbCrLf
    End If
    If uCase(DataType) <> "HIDDEN" Then Response.Write "    theForm." & VariableName & ".focus();" & VbCrLf End If
    Response.Write "    return (false);" & VbCrLf
    Response.Write "  }" & VbCrLf
  End If
  If uCase(DataType) = "CGC" or uCase(DataType) = "CNPJ" Then
    checkOK = ""
    Response.Write _
    "    var allValid = true;" & VbCrLf & _
    "    var soma = 0;" & VbCrLf & _
    "    var D1 = 0;" & VbCrLf & _
    "    var D2 = 0;" & VbCrLf & _
    "    var checkStr = theForm." & VariableName & ".value;" & VbCrLf & _
    "    checkStr = checkStr.replace('.','');" & VbCrLf & _
    "    checkStr = checkStr.replace('.','');" & VbCrLf & _
    "    checkStr = checkStr.replace('.','');" & VbCrLf & _
    "    checkStr = checkStr.replace('/','');" & VbCrLf & _
    "    checkStr = checkStr.replace('-','');" & VbCrLf & _
    "    for (i = 1;  i < 13;  i++)" & VbCrLf & _
    "    {" & VbCrLf & _
    "      if (i < 5) { soma = soma + (checkStr.charAt(i-1)*(6-i)); }" & VbCrLf & _
    "      else { soma = soma + (checkStr.charAt(i-1)*(14-i)); }" & VbCrLf & _
    "    }" & VbCrLf & _
    "    D1 = modulo(soma,11)" & VbCrLf & _
    "    if (D1 > 9) { D1 = 0}" & VbCrLf & _
    "    soma = 0;" & VbCrLf & _
    "    for (i = 1;  i < 14;  i++)" & VbCrLf & _
    "    {" & VbCrLf & _
    "      if (i < 6) { soma = soma + (checkStr.charAt(i-1)*(7-i)); }" & VbCrLf & _
    "      else { soma = soma + (checkStr.charAt(i-1)*(15-i)); }" & VbCrLf & _
    "    }" & VbCrLf & _
    "    D2 = modulo(soma,11)" & VbCrLf & _
    "    if (D2 > 9) { D2 = 0}" & VbCrLf & _
    "    if (D1 == checkStr.charAt(13-1) && D2 == checkStr.charAt(14-1)) { allValid = true}" & VbCrLf & _
    "    else { allValid = false }" & VbCrLf & _
    "    if (!allValid) {" & VbCrLf & _
    "       alert('" & DisplayName & " inv·lido.');" & VbCrLf & _
    "       theForm." & VariableName & ".focus();" & VbCrLf & _
    "       return (false);" & VbCrLf & _
    "    }" & VbCrLf 
  ElseIf uCase(DataType) = "CPF" Then
    checkOK = ""
    Response.Write _
    "    var igual = 0;" & VbCrLf & _
    "    var allValid = true;" & VbCrLf & _
    "    var soma = 0;" & VbCrLf & _
    "    var D1 = 0;" & VbCrLf & _
    "    var D2 = 0;" & VbCrLf & _
    "    var checkStr = theForm." & VariableName & ".value;" & VbCrLf & _
    "    checkStr = checkStr.replace('.','');" & VbCrLf & _
    "    checkStr = checkStr.replace('.','');" & VbCrLf & _
    "    checkStr = checkStr.replace('-','');" & VbCrLf & _
    "    if (checkStr.length == 11) {DV = checkStr.substr(9,2);}" & VbCrLf & _
    "    else {DV = checkStr.substr(6,2);}" & VbCrLf & _
    "    igual = 0;" & VbCrLf & _
    "    if (checkStr.length == 11) {" & VbCrLf & _
    "        for (i = 1;  i < 10;  i++)" & VbCrLf & _
    "        {" & VbCrLf & _
    "          soma = soma + (checkStr.charAt(i-1)*(11-i));" & VbCrLf & _
    "          if (checkStr.charAt(i) != checkStr.charAt(i-1)) igual = 1" & VbCrLf & _
    "        }" & VbCrLf & _
    "    }" & VbCrLf & _
    "    else {" & VbCrLf & _
    "        for (i = 1;  i < 7;  i++)" & VbCrLf & _
    "        {" & VbCrLf & _
    "          soma = soma + (checkStr.charAt(i-1)*(8-i));" & VbCrLf & _
    "          if (checkStr.charAt(i) != checkStr.charAt(i-1)) igual = 1" & VbCrLf & _
    "        }" & VbCrLf & _
    "    }" & VbCrLf & _
    "    if (igual == 0 && checkStr > '') {" & VbCrLf & _
    "       alert('" & DisplayName & " inv·lido.');" & VbCrLf & _
    "       theForm." & VariableName & ".focus();" & VbCrLf & _
    "       return (false);" & VbCrLf & _
    "    }" & VbCrLf & _
    "    D1 = modulo(soma,11);" & VbCrLf & _
    "    if (D1 > 9) { D1 = 0}" & VbCrLf & _
    "    soma = 0;" & VbCrLf & _
    "    if (checkStr.length == 11) {" & VbCrLf & _
    "        for (i = 1;  i < 11;  i++)" & VbCrLf & _
    "        {" & VbCrLf & _
    "          soma = soma + (checkStr.charAt(i-1)*(12-i));" & VbCrLf & _
    "        }" & VbCrLf & _
    "    }" & VbCrLf & _
    "    else {" & VbCrLf & _
    "        for (i = 1;  i < 8;  i++)" & VbCrLf & _
    "        {" & VbCrLf & _
    "          soma = soma + (checkStr.charAt(i-1)*(9-i));" & VbCrLf & _
    "        }" & VbCrLf & _
    "    }" & VbCrLf & _
    "    D2 = modulo(soma,11)" & VbCrLf & _
    "    if (D2 > 9) { D2 = 0}" & VbCrLf & _
    "    if ((D1 == DV.substr(0,1)) && (D2 == DV.substr(1,1))) { allValid = true}" & VbCrLf & _
    "    else { allValid = false }" & VbCrLf & _
    "    if (!allValid && checkStr > '') {" & VbCrLf & _
    "       alert('" & DisplayName & " inv·lido.');" & VbCrLf & _
    "       theForm." & VariableName & ".focus();" & VbCrLf & _
    "       return (false);" & VbCrLf & _
    "    }" & VbCrLf & _
    "    if (igual == 0 && checkStr > '') {" & VbCrLf & _
    "       alert('" & DisplayName & " inv·lido.');" & VbCrLf & _
    "       theForm." & VariableName & ".focus();" & VbCrLf & _
    "       return (false);" & VbCrLf & _
    "    }" & VbCrLf 
  ElseIf uCase(DataType) = "VALOR" Then
     Response.Write _
     "  var V1 = theForm." & VariableName & ".value; " & VbCrLf & _
     "  if (V1.length != 0) { " & VbCrLf & _
     "     V1 = V1.toString().replace(/\$|\./g,''); " & VbCrLf & _
     "     V1 = V1.toString().replace(',','.'); " & VbCrLf & _
     "     if (isNaN(V1)) { " & VbCrLf & _
     "        alert('" & DisplayName & " n„o È um valor v·lido!.'); " & VbCrLf & _
     "        theForm." & VariableName & ".focus(); " & VbCrLf & _
     "        return false; " & VbCrLf & _
     "     } " & VbCrLf & _
     "  } " & VbCrLf
  ElseIf uCase(DataType) = "DATA" Then
    Response.Write _
    "    var checkStr = theForm." & VariableName & ".value;" & VbCrLf & _
    "    var err=0;" & VbCrLf & _
    "    var psj=0;" & VbCrLf & _
    "    if (checkStr.length != 0) {" & VbCrLf & _
    "       if (!checkbranco(checkStr))" & VbCrLf & _
    "       {" & VbCrLf & _
    "           if (checkStr.length != 10) err=1" & VbCrLf & _
    "           dia = checkStr.substring(0, 2);" & VbCrLf & _
    "           barra1 = checkStr.substring(2, 3);" & VbCrLf & _
    "           mes = checkStr.substring(3, 5);" & VbCrLf & _
    "           barra2 = checkStr.substring(5, 6);" & VbCrLf & _
    "            ano = checkStr.substring(6, 10);" & VbCrLf & _
    "            //verificaÁıes b·sicas" & VbCrLf & _
    "            if (mes<1 || mes>12) err = 1;" & VbCrLf & _
    "            if (barra1 != '/') err = 1;" & VbCrLf & _
    "            if (dia<1 || dia>31) err = 1;" & VbCrLf & _
    "            if (barra2 != '/') err = 1;" & VbCrLf & _
    "            if (ano<1900 || ano>2900) err = 1;" & VbCrLf & _
    "            //verificaÁıes avanÁadas" & VbCrLf & _
    "            // mÍs com 30 dias" & VbCrLf & _
    "            if (mes==4 || mes==6 || mes==9 || mes==11){" & VbCrLf & _
    "               if (dia==31) err=1;" & VbCrLf & _
    "            }" & VbCrLf & _
    "            // fevereiro e ano bissexto" & VbCrLf & _
    "            if (mes==2){" & VbCrLf & _
    "                var g=parseInt(ano/4);" & VbCrLf & _
    "                if (isNaN(g)) {" & VbCrLf & _
    "                    err=1;" & VbCrLf & _
    "                }" & VbCrLf & _
    "                if (dia>29) err=1;" & VbCrLf & _
    "                if (dia==29 && ((ano/4)!=parseInt(ano/4))) err=1;" & VbCrLf & _
    "            }" & VbCrLf & _
    "       }" & VbCrLf & _
    "       else" & VbCrLf & _
    "       {" & VbCrLf & _
    "           err=1;" & VbCrLf & _
    "       }" & VbCrLf & _
    "    }" & VbCrLf & _
    "    if (err==1){" & VbCrLf & _
    "       alert('Campo " & DisplayName & " inv·lido.');" & VbCrLf & _
    "       theForm." & VariableName & ".focus();" & VbCrLf & _
    "       return (false);" & VbCrLf & _
    "    }" & VbCrLf 
  ElseIf uCase(DataType) = "DATADM" Then
    Response.Write _
    "    var checkStr = theForm." & VariableName & ".value;" & VbCrLf & _
    "    var err=0;" & VbCrLf & _
    "    var psj=0;" & VbCrLf & _
    "    if (checkStr.length != 0) {" & VbCrLf & _
    "       if (!checkbranco(checkStr))" & VbCrLf & _
    "       {" & VbCrLf & _
    "           if (checkStr.length != 10) err=1" & VbCrLf & _
    "           dia = checkStr.substring(0, 2);" & VbCrLf & _
    "           barra1 = checkStr.substring(2, 3);" & VbCrLf & _
    "           mes = checkStr.substring(3, 5);" & VbCrLf & _
    "            //verificaÁıes b·sicas" & VbCrLf & _
    "            if (mes<1 || mes>12) err = 1;" & VbCrLf & _
    "            if (barra1 != '/') err = 1;" & VbCrLf & _
    "            if (dia<1 || dia>31) err = 1;" & VbCrLf & _
    "            //verificaÁıes avanÁadas" & VbCrLf & _
    "            // mÍs com 30 dias" & VbCrLf & _
    "            if (mes==4 || mes==6 || mes==9 || mes==11){" & VbCrLf & _
    "               if (dia==31) err=1;" & VbCrLf & _
    "            }" & VbCrLf & _
    "            // fevereiro e ano bissexto" & VbCrLf & _
    "            if (mes==2){" & VbCrLf & _
    "                var g=parseInt(ano/4);" & VbCrLf & _
    "                if (isNaN(g)) {" & VbCrLf & _
    "                    err=1;" & VbCrLf & _
    "                }" & VbCrLf & _
    "                if (dia>29) err=1;" & VbCrLf & _
    "                if (dia==29 && ((ano/4)!=parseInt(ano/4))) err=1;" & VbCrLf & _
    "            }" & VbCrLf & _
    "       }" & VbCrLf & _
    "       else" & VbCrLf & _
    "       {" & VbCrLf & _
    "           err=1;" & VbCrLf & _
    "       }" & VbCrLf & _
    "    }" & VbCrLf & _
    "    if (err==1){" & VbCrLf & _
    "       alert('Campo " & DisplayName & " inv·lido.');" & VbCrLf & _
    "       theForm." & VariableName & ".focus();" & VbCrLf & _
    "       return (false);" & VbCrLf & _
    "    }" & VbCrLf 
  ElseIf uCase(DataType) = "DATAMA" Then
    Response.Write _
    "var checkStr = theForm." & VariableName & ".value;" & VbCrLf & _
    "var err=0;" & VbCrLf & _
    "var psj=0;" & VbCrLf & _
    "if (checkStr.length > 0) {" & VbCrLf & _
    "   if (!checkbranco(checkStr))" & VbCrLf & _
    "   {" & VbCrLf & _
    "       if (checkStr.length != 7) err=1" & VbCrLf & _
    "         mes = checkStr.substring(0, 2)" & VbCrLf & _
    "         barra2 = checkStr.substring(2, 3)" & VbCrLf & _
    "         ano = checkStr.substring(3, 7)" & VbCrLf & _
    "         if (mes<1 || mes>12) err = 1" & VbCrLf & _
    "         if (barra2 != '/') err = 1" & VbCrLf & _
    "         if (ano<1900 || ano>2900) err = 1" & VbCrLf & _
    "   }" & VbCrLf & _
    "   else" & VbCrLf & _
    "   {" & VbCrLf & _
    "       err=1" & VbCrLf & _
    "   }" & VbCrLf & _
    "}" & VbCrLf & _
    "if (err==1){" & VbCrLf & _
    "   alert('Campo " & DisplayName & " inv·lido.');" & VbCrLf & _
    "   theForm." & VariableName & ".focus();" & VbCrLf & _
    "   return (false);" & VbCrLf & _
    "}" & VbCrLf 
  End If
End Sub
%>