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
    ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
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
REM Rotina de montagem do menu
REM -------------------------------------------------------------------------
Sub ExibeDocs1
   
   Dim w_descricao
   
   ShowHTML "<HTML>"
   ShowHTML "<HEAD>"
   ShowHTML "<script language=JavaScript>"
   ShowHTML "function clickHandler() {"
   ShowHTML "  var targetId, srcElement, targetElement;"
   ShowHTML "  srcElement = window.event.srcElement;"
   ShowHTML "  if (srcElement.className == ""Outline"") {"
   ShowHTML "     targetId = srcElement.id + ""details"";"
   ShowHTML "     targetElement = document.all(targetId);"
   ShowHTML "     if (targetElement.style.display == ""none"") {"
   ShowHTML "        targetElement.style.display = """";"
   ShowHTML "     } else {"
   ShowHTML "        targetElement.style.display = ""none"";"
   ShowHTML "     }"
   ShowHTML "  }"
   ShowHTML "}"
   ShowHTML "document.onclick = clickHandler;"
   ShowHTML "</script>"
   ShowHTML "<style>"
   ShowHTML "<// a { color: ""#000000""; text-decoration: ""none""; } "
   ShowHTML "    a:hover { color:""#000000""; text-decoration: ""underline""; }"
   ShowHTML "    .SS{text-decoration:none;} "
   ShowHTML "    .SS:HOVER{text-decoration: ""underline"";} "
   ShowHTML "//></style>"
   ShowHTML "</HEAD>"
   ShowHTML "<BASEFONT FACE=""Verdana, Helvetica, Sans-Serif"" SIZE=""2"">"
   ' Decide se montará o body do menu principal ou o body do sub-menu de uma opção a partir do valor de w_sq_pagina
   DB_GetCustomerData RS, Session("p_cliente")
   ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
   Response.Write "<BODY topmargin=0 bgcolor=""#FFFFFF"" BACKGROUND=""" & conFileVirtual & Session("p_cliente") & "/img/" & RS("fundo") & """ BGPROPERTIES=""FIXED"" text=""#000000"" link=""#000000"" vlink=""#000000"" alink=""#FF0000"" "
   If Request("SG") = "" Then
      DB_GetLinkData RS, Session("p_cliente"), "MESA"
      If Not RS.EOF Then
         If RS("IMAGEM") > "" Then
            ShowHTML "onLoad='javascript:top.content.location=""" & RS("LINK") & "&P1="&RS("P1")&"&P2="&RS("P2")&"&P3="&RS("P3")&"&P4="&RS("P4")&"&TP=<img src="&RS("IMAGEM")&" BORDER=0>"&RS("nome")&"&SG="&RS("SIGLA")&"""'> "
         Else
            ShowHTML "onLoad='javascript:top.content.location=""" & RS("LINK") & "&P1="&RS("P1")&"&P2="&RS("P2")&"&P3="&RS("P3")&"&P4="&RS("P4")&"&TP=<img src="&w_ImagemPadrao&" BORDER=0>"&RS("nome")&"&SG="&RS("SIGLA")&"""'> "
         End If
      Else
         ShowHTML ">"
      End If
      DesconectaBD
   Else
      If O = "L" Then
         DB_GetLinkData RS, Session("p_cliente"), Request("SG")
         RS.Sort = "ordem"
         ShowHTML "onLoad='javascript:top.content.location=""" & RS("LINK") & "&R=" & Request("R") & "&P1="&RS("P1")&"&P2="&RS("P2")&"&P3="&RS("P3")&"&P4="&RS("P4")&"&TP="&Request("TP")&" - "&RS("nome")&"&SG="&RS("SIGLA")&"&O="&Request("O") & MontaFiltro("GET") & """;'>"
         DesconectaBD
      Else
         DB_GetLinkDataParent RS, Session("p_cliente"), Request("SG")
         RS.Sort = "ordem"
         If Request("w_cgccpf") > "" Then
            ShowHTML "onLoad='javascript:top.content.location=""" & RS("LINK") & "&R=" & Request("R") & "&P1="&RS("P1")&"&P2="&RS("P2")&"&P3="&RS("P3")&"&P4="&RS("P4")&"&TP="&Request("TP")&" - "&RS("nome")&"&SG="&RS("SIGLA")&"&O="&Request("O")&"&w_cgccpf="&Request("w_cgccpf")& MontaFiltro("GET") & """;'>"
         Else
            ShowHTML "onLoad='javascript:top.content.location=""" & RS("LINK") & "&R=" & Request("R") & "&P1="&RS("P1")&"&P2="&RS("P2")&"&P3="&RS("P3")&"&P4="&RS("P4")&"&TP="&Request("TP")&" - "&RS("nome")&"&SG="&RS("SIGLA")&"&O="&Request("O")&"&w_chave="&Request("w_chave")&"&w_menu="&RS("menu_pai")& MontaFiltro("GET") & """;'>"
         End If
         DesconectaBD
      End If
   End If
   ShowHTML "  <b><CENTER><table border=0 cellpadding=0 height=""80"" width=""100%"">"
   ShowHTML "      <tr><td width=""100%"" valign=""center"" align=""center"">"
   DB_GetCustomerData RS, Session("p_cliente")
   ShowHTML "         <img src=""" & conFileVirtual & Session("p_cliente") & "/img/" & RS("logo1") & """ vspace=""0"" hspace=""0"" border=""1""></td></tr>"
   DesconectaBD
   ShowHTML "      <tr><td height=1><tr><td height=1 bgcolor=""#000000"">"
   ShowHTML "      <tr><td colspan=2 width=""100%""><table border=0 width=""100%"" cellpadding=0 cellspacing=0><tr valign=""top"">"
   ShowHTML "          <td><font size=1>Usuário:<b>" & Session("Nome_resumido") & "</b></TD>"
   ShowHTML "          <td align=""right""><a class=""hl"" href=""Help.asp?par=Menu&TP=<img src=images/Folder/hlp.gif border=0> SIW - Visão Geral&SG=MESA&O=L"" target=""content"" title=""Exibe informações sobre os módulos do sistema.""><img src=""images/Folder/hlp.gif"" border=0></a></TD>"
   ShowHTML "          </table>"
   ShowHTML "      <tr><td height=1><tr><td height=2 bgcolor=""#000000"">"
   ShowHTML "      </table></CENTER>"
   ShowHTML "  <table border=0 cellpadding=0 height=""80"" width=""100%""><tr><td nowrap><font size=1><b>"
   
   If Request("SG") = "" or (Request("SG") > "" and O = "L") Then
      DB_GetLinkDataUser RS, Session("p_cliente"), Session("sq_pessoa"), "IS NULL"
      w_contOut = 0
      While Not RS.EOF
         w_Titulo = RS("nome")
         If cDbl(RS("Filho")) > 0 Then
            w_contOut = w_contOut + 1
            ShowHTML "<font size=1><span id=Out" & w_contOut & " class=Outline style=""cursor: hand; ""><div align=""left"" id=Out" & w_contOut & " class=Outline style=""cursor: hand; ""><img src=""images/folder/FolderClose.gif"" border=0 align=""center"" id=Out" & w_contOut & " class=Outline> " & RS("NOME") & "</div></span>"
            ShowHTML "   <div id=Out" & w_contOut & "details style=""display:None; position:relative; left:12;""><font size=1>"
            DB_GetLinkDataUser RS1, Session("p_cliente"), Session("sq_pessoa"), RS("sq_menu")
            While Not RS1.EOF
               w_Titulo = w_Titulo & " - " & RS1("NOME")
               If cDbl(RS1("Filho")) > 0 Then
                  w_contOut = w_contOut + 1
                  ShowHTML "<span id=Out" & w_contOut & " class=Outline style=""cursor: hand; ""><div align=""left"" id=Out" & w_contOut & " class=Outline style=""cursor: hand; ""><img src=""images/folder/FolderClose.gif"" border=0 align=""center"" id=Out" & w_contOut & " class=Outline> " & RS1("NOME") & "</div></span>"
                  ShowHTML "   <div id=Out" & w_contOut & "details style=""display:None; position:relative; left:12;""><font size=1>"
                  DB_GetLinkDataUser RS2, Session("p_cliente"), Session("sq_pessoa"), RS1("sq_menu")
                  While Not RS2.EOF
                     w_Titulo = w_Titulo & " - " & RS2("NOME")
                     If cDbl(RS2("Filho")) > 0 Then
                        w_contOut = w_contOut + 1
                        ShowHTML "<span id=Out" & w_contOut & " class=Outline style=""cursor: hand; ""><div align=""left"" id=Out" & w_contOut & " class=Outline style=""cursor: hand; ""> <img src=""images/folder/FolderClose.gif"" border=0 align=""center"" id=Out" & w_contOut & " class=Outline> " & RS2("NOME") & "</div></span>"
                        ShowHTML "   <div id=Out" & w_contOut & "details style=""display:None; position:relative; left:12;""><font size=1>"
                        DB_GetLinkDataUser RS3, Session("p_cliente"), Session("sq_pessoa"), RS2("sq_menu")
                        While Not RS3.EOF
                           w_Titulo = w_Titulo & " - " & RS3("NOME")
                           If RS3("IMAGEM") > "" Then
                              w_Imagem = RS3("IMAGEM")
                           Else
                              w_Imagem = w_ImagemPadrao
                           End If
                           If RS3("externo") = "S" Then
                              ShowHTML "    <img src=""" & w_Imagem & """ border=0 align=""center""> <A class=""ss"" HREF=""" & replace(RS3("LINK"),"@files",conFileVirtual & Session("p_cliente")) & """ TARGET=""" & RS3("target") & """>" & RS3("NOME") & "</A><BR>"
                           Else
                              ShowHTML "    <img src=""" & w_Imagem & """ border=0 align=""center""> <A class=""ss"" HREF=""" & RS3("LINK") & "&P1="&RS3("P1")&"&P2="&RS3("P2")&"&P3="&RS3("P3")&"&P4="&RS3("P4")&"&TP=<img src="&w_Imagem&" BORDER=0>"&w_Titulo&"&SG="&RS3("SIGLA")&""" TARGET=""" & RS3("target") & """>" & RS3("NOME") & "</A><BR>"
                           End If
                           w_Titulo = Replace(w_Titulo, " - "&RS3("NOME"), "")
                           RS3.MoveNext
                        Wend
                        ShowHTML "   </font></div>"
                     Else
                        If RS2("IMAGEM") > "" Then
                           w_Imagem = RS2("IMAGEM")
                        Else
                           w_Imagem = w_ImagemPadrao
                        End If
                        If RS2("externo") = "S" Then
                           ShowHTML "    <img src=""" & w_Imagem & """ border=0 align=""center""> <A class=""ss"" HREF=""" & replace(RS2("LINK"),"@files",conFileVirtual & Session("p_cliente")) & """ TARGET=""" & RS2("target") & """>" & RS2("NOME") & "</A><BR>"
                        Else
                           ShowHTML "    <img src=""" & w_Imagem & """ border=0 align=""center""> <A class=""ss"" HREF=""" & RS2("LINK") & "&P1="&RS2("P1")&"&P2="&RS2("P2")&"&P3="&RS2("P3")&"&P4="&RS2("P4")&"&TP=<img src="&w_Imagem&" BORDER=0>"&w_Titulo&"&SG="&RS2("SIGLA")&""" TARGET=""" & RS2("target") & """>" & RS2("NOME") & "</A><BR>"
                        End If
                     End If
                     w_Titulo = Replace(w_Titulo, " - "&RS2("NOME"), "")
                     RS2.MoveNext
                  Wend
                  ShowHTML "   </font></div>"
               Else
                  If RS1("IMAGEM") > "" Then
                     w_Imagem = RS1("IMAGEM")
                  Else
                     w_Imagem = w_ImagemPadrao
                  End If
                  If RS1("externo") = "S" Then
                     If RS1("LINK") > "" Then
                        ShowHTML "    <img src=""" & w_Imagem & """ border=0 align=""center""> <A class=""ss"" HREF=""" & replace(RS1("LINK"),"@files",conFileVirtual & Session("p_cliente")) & """ TARGET=""" & RS1("target") & """>" & RS1("NOME") & "</A><BR>"
                     Else
                        ShowHTML "    <img src=""" & w_Imagem & """ border=0 align=""center""> " & RS1("NOME") & "<BR>"
                     End If
                  Else
                     ShowHTML "    <img src=""" & w_Imagem & """ border=0 align=""center""> <A class=""ss"" HREF=""" & RS1("LINK") & "&P1="&RS1("P1")&"&P2="&RS1("P2")&"&P3="&RS1("P3")&"&P4="&RS1("P4")&"&TP=<img src="&w_Imagem&" BORDER=0>"&w_Titulo&"&SG="&RS1("SIGLA")&""" TARGET=""" & RS1("target") & """>" & RS1("NOME") & "</A><BR>"
                  End If
               End If
               w_Titulo = Replace(w_Titulo, " - "&RS1("NOME"), "")
               RS1.MoveNext
            Wend
            ShowHTML "   </font></div>"
         Else
            If RS("IMAGEM") > "" Then
               w_Imagem = RS("IMAGEM")
            Else
               w_Imagem = w_ImagemPadrao
            End If
            If RS("externo") = "S" Then
               ShowHTML "    <img src=""" & w_Imagem & """ border=0 align=""center""> <A class=""ss"" HREF=""" & replace(RS("LINK"),"@files",conFileVirtual & Session("p_cliente")) & """ TARGET=""" & RS("target") & """>" & RS("NOME") & "</A><BR>"
            Else
               ShowHTML "    <img src=""" & w_Imagem & """ border=0 align=""center""> <A class=""ss"" HREF=""" & RS("LINK") & "&P1="&RS("P1")&"&P2="&RS("P2")&"&P3="&RS("P3")&"&P4="&RS("P4")&"&TP=<img src="&w_Imagem&" BORDER=0>"&w_Titulo&"&SG="&RS("SIGLA")&""" TARGET=""" & RS("target") & """>" & RS("NOME") & "</A><BR>"
            End If
         End If
         RS.MoveNext
      Wend
   Else ' Se for montagem de sub-menu para uma opção do menu principal
      ' Se for passado o número do documento, ele é apresentado na tela, ao invés da descrição
      If Request("w_documento") > "" Then
         w_descricao = Request("w_documento")
      Else
         DB_GetLinkData RS, Session("p_cliente"), Request("SG")
         w_descricao = RS("NOME")
         DesconectaBD
      End If
      ShowHTML "<font size=2><span id=Out" & w_contOut & " class=Outline style=""cursor: hand; ""><div align=""left"" id=Out" & w_contOut & " class=Outline style=""cursor: hand; ""><img src=""images/folder/FolderClose.gif"" border=0 align=""center""> " & w_descricao & "</div></span></font>"
      ShowHTML "   <div id=Out" & w_contOut & "details style=""position:relative; left:12;""><font size=1>"
      DB_GetLinkSubMenu RS, Session("p_cliente"), Request("SG")
      While Not RS.EOF
         w_Titulo = Request("TP") & " - " & RS("NOME")
         If RS("IMAGEM") > "" Then
            w_Imagem = RS("IMAGEM")
         Else
            w_Imagem = w_ImagemPadrao
         End If
         If RS("externo") = "S" Then
            ShowHTML "    <img src=""" & w_Imagem & """ border=0 align=""center""> <A class=""ss"" HREF=""" & replace(RS("LINK"),"@files",conFileVirtual & Session("p_cliente")) & """ TARGET=""" & RS("target") & """>" & RS("NOME") & "</A><BR>"
         Else
            If Request("w_cgccpf") > "" Then
               ShowHTML "    <img src=""" & w_Imagem & """ border=0 align=""center""> <A class=""ss"" HREF=""" & RS("LINK") & "&R="&Request("R")&"&P1="&RS("P1")&"&P2="&RS("P2")&"&P3="&RS("P3")&"&P4="&RS("P4")&"&TP="&w_Titulo&"&SG="&RS("SIGLA")&"&O=L&w_cgccpf="&Request("w_cgccpf")& MontaFiltro("GET") & """ TARGET=""" & RS("target") & """>" & RS("NOME") & "</A><BR>"
            ElseIf Request("w_sq_acordo") > "" Then
               ShowHTML "    <img src=""" & w_Imagem & """ border=0 align=""center""> <A class=""ss"" HREF=""" & RS("LINK") & "&R="&Request("R")&"&P1="&RS("P1")&"&P2="&RS("P2")&"&P3="&RS("P3")&"&P4="&RS("P4")&"&TP="&w_Titulo&"&SG="&RS("SIGLA")&"&O=L&w_sq_acordo="&Request("w_sq_acordo")&"&w_menu="&RS("menu_pai")&""" TARGET=""" & RS("target") & """>" & RS("NOME") & "</A><BR>"
            Else
               ShowHTML "    <img src=""" & w_Imagem & """ border=0 align=""center""> <A class=""ss"" HREF=""" & RS("LINK") & "&R="&Request("R")&"&P1="&RS("P1")&"&P2="&RS("P2")&"&P3="&RS("P3")&"&P4="&RS("P4")&"&TP="&w_Titulo&"&SG="&RS("SIGLA")&"&O="&Request("O")&"&w_chave="&Request("w_chave")&"&w_menu="&RS("menu_pai")& MontaFiltro("GET") & """ TARGET=""" & RS("target") & """>" & RS("NOME") & "</A><BR>"
            End If
         End If
         If Request("O") = "I" Then 
            RS.MoveLast
         End If
         RS.MoveNext 
      Wend
      DesconectaBD
      ShowHTML "   </font></div>"
      DB_GetLinkData RS, Session("p_cliente"), Request("SG")
      ShowHTML "  <br><font size=1><img src=""" & w_Imagem & """ border=0 align=""center""> <A class=""ss"" HREF=""" & w_pagina & par & "&O=L&R=" & Request("R") & "&SG=" & RS("sigla") & "&TP=" & RemoveTP(Request("TP")) &"&P1="&RS("P1")&"&P2="&RS("P2")&"&P3="&RS("P3")&"&P4="&RS("P4") & MontaFiltro("GET") & """>Nova consulta</A><BR>"
      DesconectaBD
      ShowHTML "  <br><font size=1><img src=""images/folder/SheetLittle.gif"" border=0 align=""center""> <A class=""ss"" HREF=""Menu.asp?par=ExibeDocs"">Menu</A></font><BR>"
   End If
   ShowHTML "  <br><br><font size=1><img src=""images/folder/SheetLittle.gif"" border=0 align=""center""> <A class=""ss"" HREF=""Menu.asp?par=Sair"" TARGET=""_top"" onClick=""return(confirm('Confirma saída do sistema?'));"">Sair do sistema</A></font><BR>"
   ShowHTML "      </table>"
   ShowHTML "</BODY>"
   ShowHTML "</HTML>"
   
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
  ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
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
  ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
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
       ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
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

