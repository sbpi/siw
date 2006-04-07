<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Link.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Geral.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /menu.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Monta o menu usando a programação visual do CESPE
REM Mail     : alex@sbpi.com.br
REM Criacao  : 15/02/2005 12:38
REM Versao   : 1.0.0.0
REM Local    : Brasília - DF
REM -------------------------------------------------------------------------
REM
' Verifica se o usuário está autenticado
If Session("LogOn") <> "Sim" Then
   EncerraSessao
End If

' Declaração de variáveis
Dim dbms, sp, RS, Par
Dim P1, P2, P3, P4, TP, SG, R, O, w_TP, w_Pagina
Dim w_cont, w_cont1, w_cont2, w_cont3, w_cont4, w_cont5
Dim RS1, RS2, RS3
Dim w_Titulo
Dim w_Imagem
Dim w_ImagemPadrao
Dim w_dir, w_dir_volta, w_cliente
Set RS  = Server.CreateObject("ADODB.RecordSet")
Set RS1 = Server.CreateObject("ADODB.RecordSet")
Set RS2 = Server.CreateObject("ADODB.RecordSet")
Set RS3 = Server.CreateObject("ADODB.RecordSet")

AbreSessao

' Carrega variáveis locais com os dados dos parâmetros recebidos
Par        = ucase(Request("Par"))
P1           = Request("P1")
P2           = Request("P2")
P3           = Request("P3")
P4           = Request("P4")
TP           = Request("TP")
SG           = ucase(Request("SG"))
R            = uCase(Request("R"))
O            = uCase(Request("O"))
w_Pagina     = "Menu.asp?par="
w_dir        = "cl_cespe/"
w_ImagemPadrao = "images/folder/SheetLittle.gif"

If O = "" and par = "TROCASENHA" Then O = "A" End If

Select Case O
  Case "I" 
     w_TP = TP & " - Inclusão"
  Case "A" 
     w_TP = TP & " - Alteração"
  Case "E" 
     w_TP = TP & " - Exclusão"
  Case "V" 
     w_TP = TP & " - Envio"
  Case "P" 
     w_TP = TP & " - Filtragem"
  Case Else
     w_TP = TP
End Select

w_cliente = RetornaCliente()

Main

FechaSessao


Set w_cliente       = Nothing
Set w_dir           = Nothing
Set w_dir_volta     = Nothing
Set RS              = Nothing
Set Par             = Nothing
Set P1              = Nothing
Set P2              = Nothing
Set P3              = Nothing
Set P4              = Nothing
Set TP              = Nothing
Set w_TP            = Nothing
Set SG              = Nothing
Set R               = Nothing
Set O               = Nothing
Set w_Pagina        = Nothing
Set w_ImagemPadrao  = Nothing
Set w_Imagem        = Nothing
Set w_Titulo        = Nothing
Set w_cont          = Nothing
Set w_cont1         = Nothing
Set w_cont2         = Nothing
Set w_cont3         = Nothing
Set w_cont4         = Nothing
Set w_cont5         = Nothing
Set RS1             = Nothing
Set RS2             = Nothing
Set RS3             = Nothing

REM =========================================================================
REM Rotina de montagem do menu
REM -------------------------------------------------------------------------
Sub ExibeDocs
   
    Dim w_descricao
   
    Cabecalho
    ShowHTML "<HEAD>"
    ShowHTML "<TITLE>Ano do Brasil na França</TITLE>"
    Estrutura_CSS w_cliente
    ShowHTML "<META content=""MSHTML 6.00.2800.1491"" name=GENERATOR>"
    ShowHTML "</HEAD>"
    ShowHTML "<BASE HREF=""" & conRootSIW & """>"
    ' Decide se montará o body do menu principal ou o body do sub-menu de uma opção a partir do valor de w_sq_pagina
    DB_GetCustomerData RS, Session("p_cliente")
    Response.Write "<BODY "
    If Request("SG") = "" Then
       DB_GetLinkData RS, Session("p_cliente"), "MESA"
       If Not RS.EOF Then
          If RS("IMAGEM") > "" Then
             ShowHTML "onLoad='javascript:location.href=""" & RS("LINK") & "&P1="&RS("P1")&"&P2="&RS("P2")&"&P3="&RS("P3")&"&P4="&RS("P4")&"&TP=<img src="&RS("IMAGEM")&" BORDER=0>"&RS("nome")&"&SG="&RS("SIGLA")&"""'> "
          Else
             ShowHTML "onLoad='javascript:location.href=""" & RS("LINK") & "&P1="&RS("P1")&"&P2="&RS("P2")&"&P3="&RS("P3")&"&P4="&RS("P4")&"&TP=<img src="&w_ImagemPadrao&" BORDER=0>"&RS("nome")&"&SG="&RS("SIGLA")&"""'> "
          End If
       Else
          ShowHTML ">"
       End If
       DesconectaBD
    Else
       If O = "L" Then
          DB_GetLinkData RS, Session("p_cliente"), Request("SG")
          RS.Sort = "ordem"
          ShowHTML "onLoad='javascript:location.href=""" & RS("LINK") & "&R=" & Request("R") & "&P1="&RS("P1")&"&P2="&RS("P2")&"&P3="&RS("P3")&"&P4="&RS("P4")&"&TP="&Request("TP")&" - "&RS("nome")&"&SG="&RS("SIGLA")&"&O="&Request("O") & MontaFiltro("GET") & """;'>"
          DesconectaBD
       Else
          DB_GetLinkDataParent RS, Session("p_cliente"), Request("SG")
          RS.Sort = "ordem"
          If Request("w_cgccpf") > "" Then
             ShowHTML "onLoad='javascript:location.href=""" & RS("LINK") & "&R=" & Request("R") & "&P1="&RS("P1")&"&P2="&RS("P2")&"&P3="&RS("P3")&"&P4="&RS("P4")&"&TP="&Request("TP")&" - "&RS("nome")&"&SG="&RS("SIGLA")&"&O="&Request("O")&"&w_cgccpf="&Request("w_cgccpf")& MontaFiltro("GET") & """;'>"
          Else
             ShowHTML "onLoad='javascript:location.href=""" & RS("LINK") & "&R=" & Request("R") & "&P1="&RS("P1")&"&P2="&RS("P2")&"&P3="&RS("P3")&"&P4="&RS("P4")&"&TP="&Request("TP")&" - "&RS("nome")&"&SG="&RS("SIGLA")&"&O="&Request("O")&"&w_chave="&Request("w_chave")&"&w_menu="&RS("menu_pai")& MontaFiltro("GET") & """;'>"
          End If
          DesconectaBD
       End If
    End If
    ShowHTML "<center>"
    Estrutura_Topo_Limpo
      Estrutura_Menu
      Estrutura_Corpo_Abre
      Estrutura_Fecha
    Estrutura_Fecha
    Estrutura_Fecha
    Rodape
   Set w_descricao = Nothing
End Sub
REM =========================================================================
REM Fim da rotina de montagem do menu
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de troca de senha ou assinatura eletrônica
REM -------------------------------------------------------------------------
Sub TrocaSenha
  Dim w_texto
  Dim w_minimo
  Dim w_maximo
  Dim w_vigencia
  Dim w_aviso
  
  DB_GetCustomerData RS, Session("p_cliente")
  w_minimo   = cDbl(RS("tamanho_min_senha"))
  w_maximo   = cDbl(RS("TAMANHO_MAX_SENHA"))
  w_vigencia = cDbl(RS("DIAS_VIG_SENHA"))
  w_aviso    = cDbl(RS("DIAS_AVISO_EXPIR"))
  DesconectaBD
  
  If P1 = 1 Then  w_texto = "Senha de Acesso" Else w_texto = "Assinatura Eletrônica" End If
  Cabecalho
  ShowHTML "<HEAD>"
  ScriptOpen "JavaScript"
  ValidateOpen "Validacao"
  
  Validate "w_atual", w_texto & " atual", "1", "1", w_minimo, w_maximo, "1", "1"
  Validate "w_nova", "Nova " & w_texto, "1", "1", w_minimo, w_maximo, "1", "1"
  Validate "w_conf", "Confirmação da " & w_texto & " atual", "1", "1", w_minimo, w_maximo, "1", "1"
  ShowHTML "  if (theForm.w_atual.value == theForm.w_nova.value) { "
  ShowHTML "     alert('A nova " & w_texto & " deve ser diferente da atual!');"
  ShowHTML "     theForm.w_nova.value='';"
  ShowHTML "     theForm.w_conf.value='';"
  ShowHTML "     theForm.w_nova.focus();"
  ShowHTML "     return false;"
  ShowHTML "  }"
  ShowHTML "  if (theForm.w_nova.value != theForm.w_conf.value) { "
  ShowHTML "     alert('Favor informar dois valores iguais para a nova " & w_texto & "!');"
  ShowHTML "     theForm.w_nova.value='';"
  ShowHTML "     theForm.w_conf.value='';"
  ShowHTML "     theForm.w_nova.focus();"
  ShowHTML "     return false;"
  ShowHTML "  }"
  ShowHTML "  var checkStr = theForm.w_nova.value;"
  ShowHTML "  var temLetra = false;"
  ShowHTML "  var temNumero = false;"
  ShowHTML "  var checkOK = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz';"
  ShowHTML "  for (i = 0;  i < checkStr.length;  i++)"
  ShowHTML "  {"
  ShowHTML "    ch = checkStr.charAt(i);"
  ShowHTML "    for (j = 0;  j < checkOK.length;  j++)"
  ShowHTML "      if (ch == checkOK.charAt(j)) temLetra = true;"
  ShowHTML "  }"
  ShowHTML "  var checkOK = '0123456789';"
  ShowHTML "  for (i = 0;  i < checkStr.length;  i++)"
  ShowHTML "  {"
  ShowHTML "    ch = checkStr.charAt(i);"
  ShowHTML "    for (j = 0;  j < checkOK.length;  j++)"
  ShowHTML "      if (ch == checkOK.charAt(j)) temNumero = true;"
  ShowHTML "  }"
  ShowHTML "  if (!(temLetra && temNumero))"
  ShowHTML "  {"
  ShowHTML "    alert('A nova " & w_texto & " deve conter letras e números.');"
  ShowHTML "    theForm.w_nova.value='';"
  ShowHTML "    theForm.w_conf.value='';"
  ShowHTML "    theForm.w_nova.focus();"
  ShowHTML "    return (false);"
  ShowHTML "  }"
  ShowHTML "  theForm.Botao[0].disabled=true;"
  ShowHTML "  theForm.Botao[1].disabled=true;"
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  BodyOpen "onLoad='document.Form.w_atual.focus();'"
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  AbreForm "Form", w_Pagina&"Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,w_pagina&par,O
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "    <table width=""80%"" border=""0"">"
  ShowHTML "      <tr><td valign=""top""><font size=""1"">Usuário:<br><b>" & Session("NOME") & " (" & Session("USERNAME") & ")</b></td>"
  DB_GetUserData rs, Session("p_cliente"), Session("username")
  If P1 = 1 Then ' Se for troca de senha de acesso
     ShowHTML "      <tr><td valign=""top""><font size=""1"">Ultima troca de " & w_texto & ":<br><b>" & FormatDateTime(RS("ultima_troca_senha"),1) & ", " & FormatDateTime(RS("ultima_troca_senha"),3) & "</b></td>"
     ShowHTML "      <tr><td valign=""top""><font size=""1"">Expiração da " & w_texto & " atual ocorrerá em:<br><b>" & FormatDateTime(RS("ultima_troca_senha")+w_vigencia,1) & ", " & FormatDateTime(RS("ultima_troca_senha")+w_vigencia,3) & "</b></td>"
     ShowHTML "      <tr><td valign=""top""><font size=""1"">Você será convidado a trocar sua " & w_texto & " a partir de:<br><b>" & FormatDateTime(RS("ultima_troca_senha")+w_vigencia-w_aviso,1) & ", " & FormatDateTime(RS("ultima_troca_senha")+w_vigencia-w_aviso,3) & "</b></td>"
  ElseIf P1 = 2 Then ' Se for troca de assinatura eletrônica
     ShowHTML "      <tr><td valign=""top""><font size=""1"">Ultima troca de " & w_texto & ":<br><b>" & FormatDateTime(RS("ultima_troca_assin"),1) & ", " & FormatDateTime(RS("ultima_troca_assin"),3) & "</b></td>"
     ShowHTML "      <tr><td valign=""top""><font size=""1"">Expiração da " & w_texto & " atual ocorrerá em:<br><b>" & FormatDateTime(RS("ultima_troca_assin")+w_vigencia,1) & ", " & FormatDateTime(RS("ultima_troca_assin")+w_vigencia,3) & "</b></td>"
     ShowHTML "      <tr><td valign=""top""><font size=""1"">Você será convidado a trocar sua " & w_texto & " a partir de:<br><b>" & FormatDateTime(RS("ultima_troca_assin")+w_vigencia-w_aviso,1) & ", " & FormatDateTime(RS("ultima_troca_assin")+w_vigencia-w_aviso,3) & "</b></td>"
  End If
  DesconectaBD
  ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000""></TD></TR>"
  ShowHTML "      <tr><td valign=""top""><font size=""1""><b>" & w_texto & " <U>a</U>tual:<br><INPUT ACCESSKEY=""A"" class=""sti"" type=""password"" name=""w_atual"" size=""" & w_maximo & """ maxlength=""" & w_maximo & """></td>"
  ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>N</U>ova " & w_texto & ":<br><INPUT ACCESSKEY=""N"" class=""sti"" type=""password"" name=""w_nova"" size=""" & w_maximo & """ maxlength=""" & w_maximo & """></td>"
  ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>R</U>edigite nova " & w_texto & ":<br><INPUT ACCESSKEY=""R"" class=""sti"" type=""password"" name=""w_conf"" size=""" & w_maximo & """ maxlength=""" & w_maximo & """></td>"
  ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000""></TD></TR>"

  ShowHTML "      <tr><td align=""center"" colspan=""3"">"
  ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Grava nova " & w_texto & """>"
  ShowHTML "            <input class=""stb"" type=""reset"" name=""Botao"" value=""Limpar campos"" onClick='document.Form.w_atual.focus();'>"
  ShowHTML "          </td>"
  ShowHTML "      </tr>"
  ShowHTML "    </table>"
  ShowHTML "    </TD>"
  ShowHTML "</tr>"
  ShowHTML "</FORM>"
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_texto   = Nothing
  Set w_minimo  = Nothing
  Set w_maximo  = Nothing
  Set w_vigencia= Nothing
  Set w_aviso   = Nothing
End Sub
REM =========================================================================
REM Fim de troca de senha ou assinatura eletrônica
REM -------------------------------------------------------------------------

REM =========================================================================
REM Procedimento que executa as operações de BD
REM -------------------------------------------------------------------------
Public Sub Grava

  Cabecalho
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  BodyOpen "onLoad=document.focus();"
  
  AbreSessao
  
  Select Case SG

  Case "SGSENHA"
    If VerificaSenhaAcesso(Session("Username"),uCase(Request("w_atual"))) Then
       DB_UpdatePassword Session("p_cliente"), Session("sq_pessoa"), Request("w_nova"), "PASSWORD"
       ScriptOpen "JavaScript"
       ShowHTML "  alert('Senha de Acesso alterada com sucesso!');"
       ScriptClose
    Else
       ScriptOpen "JavaScript"
       ShowHTML "  alert('Senha de Acesso atual inválida!');"
       ShowHTML "  history.back(1);"
       ScriptClose
    End If
  Case "SGASSINAT"
    If VerificaAssinaturaEletronica(Session("Username"),uCase(Request("w_atual"))) Then
       DB_UpdatePassword Session("p_cliente"), Session("sq_pessoa"), Request("w_nova"), "SIGNATURE"
       ScriptOpen "JavaScript"
       ShowHTML "  alert('Assinatura Eletrônica alterada com sucesso!');"
       ScriptClose
    Else
       ScriptOpen "JavaScript"
       ShowHTML "  alert('Assinatura Eletrônica atual inválida!');"
       ShowHTML "  history.back(1);"
       ScriptClose
    End If
  Case Else
    ScriptOpen "JavaScript"
    ShowHTML "  alert('Bloco de dados não encontrado: " & SG & "');"
    ShowHTML "  history.back(1);"
    ScriptClose
  End Select
  ScriptOpen "JavaScript"
  ShowHTML "  location.href='" & R & "&O=A&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
  ScriptClose

End Sub
REM -------------------------------------------------------------------------
REM Fim do procedimento que executa as operações de BD
REM =========================================================================

REM =========================================================================
REM Rotina de encerramento da sessão
REM -------------------------------------------------------------------------
Sub Sair
  DB_GetCustomerSite RS, Session("p_cliente")
  Session.Abandon
  ScriptOpen "JavaScript"
  ShowHTML "  top.location.href='" & RS("logradouro") & "';"
  ScriptClose
  DesconectaBD
End Sub
REM =========================================================================
REM Fim da rotina principal
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina principal
REM -------------------------------------------------------------------------
Sub Main
  ' Verifica se o usuário tem lotação e localização
  If (len(Session("LOTACAO")&"") = 0 or len(Session("LOCALIZACAO")&"") = 0) and Session("LogOn") = "Sim" Then
    ScriptOpen "JavaScript"
    ShowHTML " alert('Você não tem lotação ou localização definida. Entre em contato com o RH!'); "
    ShowHTML " top.location.href='Default.asp'; "
    ScriptClose
   Exit Sub
  End If

  Select Case Par
    Case "GRAVA"
       Grava
    Case "TROCASENHA"
       TrocaSenha
    Case "FRAMES"
       Frames
    Case "EXIBEDOCS"
       ExibeDocs
    Case "SAIR"
       Sair
    Case Else
       Cabecalho
       ShowHTML "<BASE HREF=""" & conRootSIW & """>"
       BodyOpen "onLoad=document.focus();"
       ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
       ShowHTML "<HR>"
       ShowHTML "<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src=""images/icone/underc.gif"" align=""center""> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>"
       Rodape
  End Select
End Sub
REM =========================================================================
REM Fim da rotina principal
REM -------------------------------------------------------------------------
%>

