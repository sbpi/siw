<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE FILE="Constants.inc" -->
<!-- #INCLUDE FILE="jScript.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Geral.asp" -->
<!-- #INCLUDE FILE="DB_EO.asp" -->
<!-- #INCLUDE FILE="DB_Seguranca.asp" -->
<!-- #INCLUDE FILE="DML_EO.asp" -->
<%
Response.Expires = 0
REM =========================================================================
REM  /EO.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Estrutura organizacional
REM Mail     : alex@sbpi.com.br
REM Criacao  : 30/07/2001 08:05PM
REM Versao   : 1.0.0.0
REM Local    : Brasília - DF
REM -------------------------------------------------------------------------
REM
REM Parâmetros recebidos:
REM    R (referência) = usado na rotina de gravação, com conteúdo igual ao parâmetro T
REM    O (operação)   = I   : Inclusão
REM                   = A   : Alteração
REM                   = C   : Cancelamento
REM                   = E   : Exclusão
REM                   = L   : Listagem
REM                   = P   : Pesquisa
REM                   = D   : Detalhes
REM                   = N   : Nova solicitação de envio

' Verifica se o usuário está autenticado
If Session("LogOn") <> "Sim" Then
   EncerraSessao
End If

' Declaração de variáveis
Dim dbms, sp, RS
Dim P1, P2, P3, P4, TP, SG
Dim R, O, w_Cont, w_Pagina, w_Disabled, w_TP
Dim w_Assinatura, w_cliente, w_filter, w_cor
Dim w_dir, w_dir_volta, w_submenu, w_menu
Private Par

AbreSessao

' Carrega variáveis locais com os dados dos parâmetros recebidos
Par          = ucase(Request("Par"))
P1           = Request("P1")
P2           = Request("P2")
P3           = Request("P3")
P4           = Request("P4")
TP           = Request("TP")
SG           = ucase(Request("SG"))
R            = uCase(Request("R"))
O            = uCase(Request("O"))
w_Assinatura = uCase(Request("w_Assinatura"))
w_Pagina     = "eo.asp?par="
w_Disabled   = "ENABLED"

If O = "" Then O = "L" End If

Select Case O
  Case "I" 
     w_TP = TP & " - Inclusão"
  Case "A" 
     w_TP = TP & " - Alteração"
  Case "E" 
     w_TP = TP & " - Exclusão"
  Case "P" 
     w_TP = TP & " - Filtragem"
  Case Else
     w_TP = TP & " - Listagem"
End Select

' Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
' caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
w_cliente = RetornaCliente()
w_menu    = RetornaMenu(w_cliente, SG) 

Main

FechaSessao

Set w_cliente   = Nothing
Set w_menu      = Nothing

Set w_dir       = Nothing
Set w_dir_volta = Nothing
Set w_cor       = Nothing
Set RS          = Nothing
Set Par         = Nothing
Set P1          = Nothing
Set P2          = Nothing
Set P3          = Nothing
Set P4          = Nothing
Set TP          = Nothing
Set SG          = Nothing
Set R           = Nothing
Set O           = Nothing
Set w_Cont      = Nothing
Set w_Pagina    = Nothing
Set w_Disabled  = Nothing
Set w_TP        = Nothing
Set w_Assinatura= Nothing


REM =========================================================================
REM Rotina de montagem das unidades
REM -------------------------------------------------------------------------
Sub Unidade
   Dim w_ContOut, w_contImg
   Dim RS1, RS2, RS3, RS4, RS5, RS6, RS7, RS8, RS9
   Dim w_Titulo
   Dim w_Imagem
   Dim w_nome,w_assinatura,w_sigla
   Dim w_ordem,w_Informal,w_Vinculada,w_Adm_Central
   Dim w_sq_unidade_pagadora,w_ativo,w_sq_unidade,w_codigo
   Dim w_sq_Unidade_Gestora,w_sq_area_atuacao,w_sq_unidade_pai
   Dim w_sq_pessoa_endereco,w_sq_tipo_unidade,w_Unidade_Gestora, w_unidade_pagadora
   Dim w_email
   Dim w_libera_edicao
   
   DB_GetMenuData RS, w_menu
   w_libera_edicao = RS("libera_edicao")


   cabecalho
     
   ShowHTML "<HEAD>"
   Estrutura_CSS w_cliente
   ShowHTML "  <script src=""" & conRootSIW & "cp_menu/xPandMenu.js""></script>"
   If Instr("IAE",O) > "" Then
      ScriptOpen "JavaScript"
      ValidateOpen "Validacao"
      If InStr("IA",O) > 0 Then
         Validate "w_nome", "Nome", "1", "1", "3", "50", "1", "1"
         Validate "w_sigla", "Sigla", "1", "1", "1", "20", "1", "1"
         Validate "w_ordem", "Ordem", "1", "1", "1", "2", "", "1"
         Validate "w_codigo", "Código", "1", "", "1", "15", "", "1"
         Validate "w_email", "e-Mail", "1", "", "3", "60", "1", "1"
         Validate "w_sq_tipo_unidade", "Tipo da unidade", "SELECT", "1", "1", "18", "", "1"
         Validate "w_sq_area_atuacao", "Área de atuação", "SELECT", "1", "1", "18", "", "1"
         Validate "w_sq_pessoa_endereco", "Endereço unidade", "SELECT", "1", "1", "10", "", "1"
         Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
      ElseIf O = "E" Then
         Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
         ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
         ShowHTML "     { return (true); }; "
         ShowHTML "     { return (false); }; "
      End If
      ShowHTML "  theForm.Botao[0].disabled=true;"
      ShowHTML "  theForm.Botao[1].disabled=true;"
      ValidateClose
      ScriptClose
   End If
   ShowHTML "</HEAD>"
   if InStr("IAE",O) > 0 Then
      If O = "E" Then
        BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
      Else
        BodyOpen "onLoad='document.Form.w_nome.focus()';"
      End If   
   Else
      BodyOpen "onLoad=document.focus();"
   End If
   Estrutura_Topo_Limpo
   Estrutura_Menu
   Estrutura_Corpo_Abre
   Estrutura_Texto_Abre
   ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
   If InStr("L",O) > 0 Then   
      w_Imagem = conRootSIW & "images/ballw.gif"
      ShowHTML "<tr><td>"
      If w_libera_edicao = "S" Then
         ShowHTML "<a accesskey=""I"" class=""ss"" href=""" & w_Pagina & par & "&TP=" & TP & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
      End If
      ShowHTML "<tr><td colspan=3>"
      ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""0"" CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
      DB_GetUorgList RS, Session("p_cliente"), null, "IS NULL", null, null, null
      RS.Sort = "Ordem"
      
      if RS.EOF then           
         ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td align=""center""><font size=""2""><b>Estrutura organizacional inexistente.</b></td></tr>"
      else
         ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td valign=""center"">"
         w_ContOut = 0
         w_ContImg = 0
         ShowHTML "<div id=""container"">"
         ShowHTML "<ul id=""XRoot"" class=""XtreeRoot"">"
         While Not RS.EOF
            w_ContImg = w_ContImg + 1
            w_ContOut = w_ContOut + 1
            ShowHTML "<li id=""Xnode"" class=""Xnode"" nowrap><span onClick=""xSwapImg(document.getElementById('Ximg" & w_contImg & "'),'" & w_imagem & "','" & w_imagem & "');xMenuShowHide(document.getElementById('Xtree" & w_contOut & "'));""><img id=""Ximg" & w_contImg & """ src=""" & w_imagem & """ border=""0"">&nbsp;" & RS("NOME") & "</span> "
            If w_libera_edicao = "S" Then
               ShowHTML "<A class=""Xlink"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_unidade=" & RS("sq_unidade") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
               ShowHTML "<A class=""Xlink"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_unidade=" & RS("sq_unidade") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"           
            End If
            ShowHTML "<a class=""Xlink"" href=""#"" onclick=""window.open('" & w_pagina & "Localizacao&P1=" & P1 &"&P2=" & P2 &"&P3=" & P3 &"&P4=" & P4 &"&TP=" & TP & " - Localização&O=L&SG=LUORG&w_sq_unidade=" & RS("sq_unidade") & "','Local','toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes');"">Locais</a>&nbsp"
            ShowHTML "<a class=""Xlink"" href=""#"" onclick=""window.open('" & w_pagina & "Responsavel&P1=" & P1 &"&P2=" & P2 &"&P3=" & P3 &"&P4=" & P4 &"&TP=" & TP &" - Responsáveis&O=L&SG=RESPONSAVEL&w_sq_unidade=" & RS("sq_unidade") & "','Responsaveis','toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes');"">Responsáveis</a>"
            ShowHTML "</li>"
            ShowHTML "   <ul id=""Xtree" & w_contOut & """ class=""Xtree"" style=""display:true;"">"
            DB_GetUorgList RS1, Session("p_cliente"), RS("sq_unidade"), "FILHO", null, null, null
            RS1.Sort = "Ordem"
            While Not RS1.EOF
               w_ContImg = w_ContImg + 1
               w_ContOut = w_ContOut + 1
               ShowHTML "   <li id=""Xnode"" class=""Xnode""><span onClick=""xSwapImg(document.getElementById('Ximg" & w_contImg & "'),'" & w_imagem & "','" & w_imagem & "');xMenuShowHide(document.getElementById('Xtree" & w_contOut & "'));""><img id=""Ximg" & w_contImg & """ src=""" & w_imagem & """ border=""0"">&nbsp;" & RS1("NOME") & "</span> "
               If w_libera_edicao = "S" Then
                  ShowHTML " <A class=""Xlink"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_unidade=" & RS1("sq_unidade") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - Localização&SG=" & SG & """>Alterar</A>&nbsp"
                  ShowHTML " <A class=""Xlink"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_unidade=" & RS1("sq_unidade") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
               End If
               ShowHTML "<a class=""Xlink"" href=""#"" onclick=""window.open('" & w_pagina & "Localizacao&P1=" & P1 &"&P2=" & P2 &"&P3=" & P3 &"&P4=" & P4 &"&TP=" & TP &" - Localização&O=L&SG=LUORG&w_sq_unidade=" & RS1("sq_unidade") & "','Local','toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes')"">Locais</a>&nbsp"
               ShowHTML "<a class=""Xlink"" href=""#"" onclick=""window.open('" & w_pagina & "Responsavel&P1=" & P1 &"&P2=" & P2 &"&P3=" & P3 &"&P4=" & P4 &"&TP=" & TP &" - Responsáveis&O=L&SG=RESPONSAVEL&w_sq_unidade=" & RS1("sq_unidade") & "','Responsaveis','toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes');"">Responsáveis</a>&nbsp"
               ShowHTML "   </li>"
               ShowHTML "      <ul id=""Xtree" & w_contOut & """ class=""Xtree"" style=""display:none;"">"
               DB_GetUorgList RS2, Session("p_cliente"), RS1("sq_unidade"), "FILHO", null, null, null
               RS2.Sort = "Ordem"
               While Not RS2.EOF         
                 w_ContImg = w_ContImg + 1
                 w_ContOut = w_ContOut + 1
                 ShowHTML "         <li id=""Xnode"" class=""Xnode""><span onClick=""xSwapImg(document.getElementById('Ximg" & w_contImg & "'),'" & w_imagem & "','" & w_imagem & "');xMenuShowHide(document.getElementById('Xtree" & w_contOut & "'));""><img id=""Ximg" & w_contImg & """ src=""" & w_imagem & """ border=""0"">&nbsp;" & RS2("NOME") & "</span> "
                 If w_libera_edicao = "S" Then
                    ShowHTML " <A class=""Xlink"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_unidade=" & RS2("sq_unidade") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
                    ShowHTML " <A class=""Xlink"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_unidade=" & RS2("sq_unidade") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
                 End If
                 ShowHTML "<a class=""Xlink"" href=""#"" onclick=""window.open('" & w_pagina & "Localizacao&P1=" & P1 &"&P2=" & P2 &"&P3=" & P3 &"&P4=" & P4 &"&TP=" & TP & " - Localização&O=L&SG=LUORG&w_sq_unidade=" & RS2("sq_unidade") & "','Local','toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes')"">Locais</a>&nbsp"
                 ShowHTML "<a class=""Xlink"" href=""#"" onclick=""window.open('" & w_pagina & "Responsavel&P1=" & P1 &"&P2=" & P2 &"&P3=" & P3 &"&P4=" & P4 &"&TP=" & TP &" - Responsáveis&O=L&SG=RESPONSAVEL&w_sq_unidade=" & RS2("sq_unidade") & "','Responsaveis','toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes');"">Responsáveis</a>&nbsp"
                 ShowHTML "         </li>"
                 ShowHTML "            <ul id=""Xtree" & w_contOut & """ class=""Xtree"" style=""display:none;"">"
                 DB_GetUorgList RS3, Session("p_cliente"), RS2("sq_unidade"), "FILHO", null, null, null
                 RS3.Sort = "Ordem"
                 While Not RS3.EOF
                    w_ContImg = w_ContImg + 1
                    w_ContOut = w_ContOut + 1
                    ShowHTML "            <li id=""Xnode"" class=""Xnode""><span onClick=""xSwapImg(document.getElementById('Ximg" & w_contImg & "'),'" & w_imagem & "','" & w_imagem & "');xMenuShowHide(document.getElementById('Xtree" & w_contOut & "'));""><img id=""Ximg" & w_contImg & """ src=""" & w_imagem & """ border=""0"">&nbsp;" & RS3("NOME") & "</span> "
                    If w_libera_edicao = "S" Then
                       ShowHTML " <A class=""Xlink"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_unidade=" & RS3("sq_unidade") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
                       ShowHTML " <A class=""Xlink"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_unidade=" & RS3("sq_unidade") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
                    End If
                    ShowHTML "<a class=""Xlink"" href=""#"" onclick=""window.open('" & w_pagina & "Localizacao&P1=" & P1 &"&P2=" & P2 &"&P3=" & P3 &"&P4=" & P4 &"&TP=" & TP &" - Localização&O=L&SG=LUORG&w_sq_unidade=" & RS3("sq_unidade") & "','Local','toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes')"">Locais</a>&nbsp"
                    ShowHTML "<a class=""Xlink"" href=""#"" onclick=""window.open('" & w_pagina & "Responsavel&P1=" & P1 &"&P2=" & P2 &"&P3=" & P3 &"&P4=" & P4 &"&TP=" & TP &" - Responsáveis&O=L&SG=RESPONSAVEL&w_sq_unidade=" & RS3("sq_unidade") & "','Responsaveis','toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes');"">Responsáveis</a>&nbsp"
                    ShowHTML "            </li>"
                    ShowHTML "               <ul id=""Xtree" & w_contOut & """ class=""Xtree"" style=""display:none;"">"
                    DB_GetUorgList RS4, Session("p_cliente"), RS3("sq_unidade"), "FILHO", null, null, null
                    RS4.Sort = "Ordem"
                    While Not RS4.EOF
                       w_ContImg = w_ContImg + 1
                       w_ContOut = w_ContOut + 1
                       ShowHTML "               <li id=""Xnode"" class=""Xnode""><span onClick=""xSwapImg(document.getElementById('Ximg" & w_contImg & "'),'" & w_imagem & "','" & w_imagem & "');xMenuShowHide(document.getElementById('Xtree" & w_contOut & "'));""><img id=""Ximg" & w_contImg & """ src=""" & w_imagem & """ border=""0"">&nbsp;" & RS4("NOME") & "</span> "
                       If w_libera_edicao = "S" Then
                          ShowHTML " <A class=""Xlink"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_unidade=" & RS4("sq_unidade") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
                          ShowHTML " <A class=""Xlink"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_unidade=" & RS4("sq_unidade") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
                       End If
                       ShowHTML "<a class=""Xlink"" href=""#"" onclick=""window.open('" & w_pagina & "Localizacao&P1=" & P1 &"&P2=" & P2 &"&P3=" & P3 &"&P4=" & P4 &"&TP=" & TP &" - Localização&O=L&SG=LUORG&w_sq_unidade=" & RS4("sq_unidade") & "','Local','toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes')"">Locais</a>&nbsp"
                       ShowHTML "<a class=""Xlink"" href=""#"" onclick=""window.open('" & w_pagina & "Responsavel&P1=" & P1 &"&P2=" & P2 &"&P3=" & P3 &"&P4=" & P4 &"&TP=" & TP &" - Responsáveis&O=L&SG=RESPONSAVEL&w_sq_unidade=" & RS4("sq_unidade") & "','Responsaveis','toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes');"">Responsáveis</a>&nbsp"
                       ShowHTML "               </li>"
                       ShowHTML "                  <ul id=""Xtree" & w_contOut & """ class=""Xtree"" style=""display:none;"">"
                       DB_GetUorgList RS5, Session("p_cliente"), RS4("sq_unidade"), "FILHO", null, null, null
                       RS5.Sort = "Ordem"
                       While Not RS5.EOF
                          w_ContImg = w_ContImg + 1
                          w_ContOut = w_ContOut + 1
                          ShowHTML "                  <li id=""Xnode"" class=""Xnode""><span onClick=""xSwapImg(document.getElementById('Ximg" & w_contImg & "'),'" & w_imagem & "','" & w_imagem & "');xMenuShowHide(document.getElementById('Xtree" & w_contOut & "'));""><img id=""Ximg" & w_contImg & """ src=""" & w_imagem & """ border=""0"">&nbsp;" & RS5("NOME") & "</span> "
                          If w_libera_edicao = "S" Then
                             ShowHTML " <A class=""Xlink"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_unidade=" & RS5("sq_unidade") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
                             ShowHTML " <A class=""Xlink"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_unidade=" & RS5("sq_unidade") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
                          End If
                          ShowHTML "<a class=""Xlink"" href=""#"" onclick=""window.open('" & w_pagina & "Localizacao&P1=" & P1 &"&P2=" & P2 &"&P3=" & P3 &"&P4=" & P4 &"&TP=" & TP &" - Localização&O=L&SG=LUORG&w_sq_unidade=" & RS5("sq_unidade") & "','Local','toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes')"">Locais</a>&nbsp"
                          ShowHTML "<a class=""Xlink"" href=""#"" onclick=""window.open('" & w_pagina & "Responsavel&P1=" & P1 &"&P2=" & P2 &"&P3=" & P3 &"&P4=" & P4 &"&TP=" & TP &" - Responsáveis&O=L&SG=RESPONSAVEL&w_sq_unidade=" & RS5("sq_unidade") & "','Responsaveis','toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes');"">Responsáveis</a>&nbsp"
                          ShowHTML "                  </li>"
                          ShowHTML "                     <ul id=""Xtree" & w_contOut & """ class=""Xtree"" style=""display:none;"">"
                          DB_GetUorgList RS6, Session("p_cliente"), RS5("sq_unidade"), "FILHO", null, null, null
                          RS6.Sort = "Ordem"
                          While Not RS6.EOF
                             w_ContImg = w_ContImg + 1
                             w_ContOut = w_ContOut + 1
                             ShowHTML "                     <li id=""Xnode"" class=""Xnode""><span onClick=""xSwapImg(document.getElementById('Ximg" & w_contImg & "'),'" & w_imagem & "','" & w_imagem & "');xMenuShowHide(document.getElementById('Xtree" & w_contOut & "'));""><img id=""Ximg" & w_contImg & """ src=""" & w_imagem & """ border=""0"">&nbsp;" & RS6("NOME") & "</span> "
                             If w_libera_edicao = "S" Then
                                ShowHTML " <A class=""Xlink"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_unidade=" & RS6("sq_unidade") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
                                ShowHTML " <A class=""Xlink"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_unidade=" & RS6("sq_unidade") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
                             End If
                             ShowHTML "<a class=""Xlink"" href=""#"" onclick=""window.open('" & w_pagina & "Localizacao&P1=" & P1 &"&P2=" & P2 &"&P3=" & P3 &"&P4=" & P4 &"&TP=" & TP &" - Localização&O=L&SG=LUORG&w_sq_unidade=" & RS6("sq_unidade") & "','Local','toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes')"">Locais</a>&nbsp"
                             ShowHTML "<a class=""Xlink"" href=""#"" onclick=""window.open('" & w_pagina & "Responsavel&P1=" & P1 &"&P2=" & P2 &"&P3=" & P3 &"&P4=" & P4 &"&TP=" & TP &" - Responsáveis&O=L&SG=RESPONSAVEL&w_sq_unidade=" & RS6("sq_unidade") & "','Responsaveis','toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes');"">Responsáveis</a>&nbsp"
                             ShowHTML "                     </li>"
                             ShowHTML "                        <ul id=""Xtree" & w_contOut & """ class=""Xtree"" style=""display:none;"">"
                             DB_GetUorgList RS7, Session("p_cliente"), RS6("sq_unidade"), "FILHO", null, null, null
                             RS7.Sort = "Ordem"
                             While Not RS7.EOF
                                w_ContImg = w_ContImg + 1
                                w_ContOut = w_ContOut + 1
                                ShowHTML "                        <li id=""Xnode"" class=""Xnode""><span onClick=""xSwapImg(document.getElementById('Ximg" & w_contImg & "'),'" & w_imagem & "','" & w_imagem & "');xMenuShowHide(document.getElementById('Xtree" & w_contOut & "'));""><img id=""Ximg" & w_contImg & """ src=""" & w_imagem & """ border=""0"">&nbsp;" & RS7("NOME") & "</span> "
                                If w_libera_edicao = "S" Then
                                   ShowHTML " <A class=""Xlink"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_unidade=" & RS7("sq_unidade") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
                                   ShowHTML " <A class=""Xlink"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_unidade=" & RS7("sq_unidade") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
                                End If
                                ShowHTML "<a class=""Xlink"" href=""#"" onclick=""window.open('" & w_pagina & "Localizacao&P1=" & P1 &"&P2=" & P2 &"&P3=" & P3 &"&P4=" & P4 &"&TP=" & TP &" - Localização&O=L&SG=LUORG&w_sq_unidade=" & RS7("sq_unidade") & "','Local','toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes')"">Locais</a>&nbsp"
                                ShowHTML "<a class=""Xlink"" href=""#"" onclick=""window.open('" & w_pagina & "Responsavel&P1=" & P1 &"&P2=" & P2 &"&P3=" & P3 &"&P4=" & P4 &"&TP=" & TP &" - Responsáveis&O=L&SG=RESPONSAVEL&w_sq_unidade=" & RS7("sq_unidade") & "','Responsaveis','toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes');"">Responsáveis</a>&nbsp"
                                ShowHTML "                        </li>"
                                ShowHTML "                           <ul id=""Xtree" & w_contOut & """ class=""Xtree"" style=""display:none;"">"
                                DB_GetUorgList RS8, Session("p_cliente"), RS7("sq_unidade"), "FILHO", null, null, null
                                RS8.Sort = "Ordem"
                                While Not RS8.EOF
                                   w_ContImg = w_ContImg + 1
                                   w_ContOut = w_ContOut + 1
                                   ShowHTML "                           <li id=""Xnode"" class=""Xnode""><span onClick=""xSwapImg(document.getElementById('Ximg" & w_contImg & "'),'" & w_imagem & "','" & w_imagem & "');xMenuShowHide(document.getElementById('Xtree" & w_contOut & "'));""><img id=""Ximg" & w_contImg & """ src=""" & w_imagem & """ border=""0"">&nbsp;" & RS8("NOME") & "</span> "
                                   If w_libera_edicao = "S" Then
                                      ShowHTML " <A class=""Xlink"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_unidade=" & RS8("sq_unidade") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
                                      ShowHTML " <A class=""Xlink"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_unidade=" & RS8("sq_unidade") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
                                   End If
                                   ShowHTML "<a class=""Xlink"" href=""#"" onclick=""window.open('" & w_pagina & "Localizacao&P1=" & P1 &"&P2=" & P2 &"&P3=" & P3 &"&P4=" & P4 &"&TP=" & TP &" - Localização&O=L&SG=LUORG&w_sq_unidade=" & RS8("sq_unidade") & "','Local','toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes')"">Locais</a>&nbsp"
                                   ShowHTML "<a class=""Xlink"" href=""#"" onclick=""window.open('" & w_pagina & "Responsavel&P1=" & P1 &"&P2=" & P2 &"&P3=" & P3 &"&P4=" & P4 &"&TP=" & TP &" - Responsáveis&O=L&SG=RESPONSAVEL&w_sq_unidade=" & RS8("sq_unidade") & "','Responsaveis','toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes');"">Responsáveis</a>&nbsp"
                                   ShowHTML "                           </li>"
                                   ShowHTML "                              <ul id=""Xtree" & w_contOut & """ class=""Xtree"" style=""display:none;"">"
                                   DB_GetUorgList RS9, Session("p_cliente"), RS8("sq_unidade"), "FILHO", null, null, null
                                   RS9.Sort = "Ordem"
                                   While Not RS9.EOF
                                      w_ContImg = w_ContImg + 1
                                      w_ContOut = w_ContOut + 1
                                      ShowHTML "                              <li id=""Xnode"" class=""Xnode""><span onClick=""xSwapImg(document.getElementById('Ximg" & w_contImg & "'),'" & w_imagem & "','" & w_imagem & "');xMenuShowHide(document.getElementById('Xtree" & w_contOut & "'));""><img id=""Ximg" & w_contImg & """ src=""" & w_imagem & """ border=""0"">&nbsp;" & RS9("NOME") & "</span> "
                                      If w_libera_edicao = "S" Then
                                         ShowHTML " <A class=""Xlink"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_unidade=" & RS9("sq_unidade") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
                                         ShowHTML " <A class=""Xlink"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_unidade=" & RS9("sq_unidade") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
                                      End If
                                      ShowHTML "<a class=""Xlink"" href=""#"" onclick=""window.open('" & w_pagina & "Localizacao&P1=" & P1 &"&P2=" & P2 &"&P3=" & P3 &"&P4=" & P4 &"&TP=" & TP &" - Localização&O=L&SG=LUORG&w_sq_unidade=" & RS9("sq_unidade") & "','Local','toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes')"">Locais</a>&nbsp"
                                      ShowHTML "<a class=""Xlink"" href=""#"" onclick=""window.open('" & w_pagina & "Responsavel&P1=" & P1 &"&P2=" & P2 &"&P3=" & P3 &"&P4=" & P4 &"&TP=" & TP &" - Responsáveis&O=L&SG=RESPONSAVEL&w_sq_unidade=" & RS9("sq_unidade") & "','Responsaveis','toolbar=no,width=780,height=350,top=30,left=10,scrollbars=yes,resizable=yes');"">Responsáveis</a>&nbsp"
                                      ShowHTML "                              </li>"
                                      RS9.MoveNext
                                   wend
                                   RS9.close
                                   RS8.MoveNext
                                wend
                                RS8.close
                                ShowHTML "                        </ul>"
                                RS7.MoveNext
                             wend
                             RS7.close
                             ShowHTML "                     </ul>"
                             RS6.MoveNext
                          wend
                          RS6.close
                          ShowHTML "                  </ul>"
                          RS5.MoveNext
                       wend
                       RS5.close
                       ShowHTML "               </ul>"
                       RS4.MoveNext
                    wend
                    RS4.close
                    ShowHTML "            </ul>"
                    RS3.MoveNext
                 wend
                 RS3.close
                 ShowHTML "         </ul>"
                 RS2.MoveNext
              wend
              RS2.close
              ShowHTML "      </ul>"
              RS1.MoveNext
           wend
           RS1.close
           ShowHTML "   </ul>"
           RS.MoveNext
        Wend
        ShowHTML "</ul>"
        ShowHTML "</span>"
      end if
      ShowHTML "    </table>"
      DesconectaBD
   'INCLUSÃO
   elseif InStr("EIA",O) > 0 Then
      If O = "E" Then
         w_Disabled = "DISABLED"
      End If        
      If InStr("EA",O) > 0 Then
         w_sq_unidade = Request("w_sq_unidade")
         DB_GetUorgData RS, w_sq_unidade
         w_nome                = RS("nome")           
         w_sigla               = RS("sigla")
         w_ordem               = RS("ordem")
         w_Informal            = RS("Informal")
         w_Vinculada           = RS("Vinculada")
         w_Adm_Central         = RS("Adm_Central")
         w_sq_Unidade_Gestora  = RS("sq_unidade_gestora")
         w_sq_unidade_pagadora = RS("sq_unid_pagadora")
         w_sq_area_atuacao     = RS("sq_area_atuacao")
         w_sq_unidade_pai      = RS("sq_unidade_pai")
         w_sq_pessoa_endereco  = RS("sq_pessoa_endereco")
         w_sq_tipo_unidade     = RS("sq_tipo_unidade")
         w_Unidade_Gestora     = RS("Unidade_Gestora")
         w_ativo               = RS("ativo")           
         w_codigo              = RS("codigo")
         w_unidade_pagadora    = RS("Unidade_Pagadora")
         w_email               = RS("email")
      end if
      AbreForm "Form", w_Pagina&"Grava", "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,R,O
      ShowHTML "<INPUT type=""hidden"" name=""w_sq_unidade"" value=""" & w_sq_unidade &""">"

      ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
      ShowHTML "    <table width=""90%"" border=""0"">"
      ShowHTML "      <tr><td valign=""top""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_nome"" size=""50"" maxlength=""50"" value=""" & w_nome & """></td>"
      ShowHTML "      <tr align=""left""><td valign=""top""><table width=""100%"" cellpadding=0 cellspacing=0><tr>"
      ShowHTML "        <td valign=""top""><b><U>S</U>igla:<br><INPUT ACCESSKEY=""S"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_sigla"" size=""20"" maxlength=""20"" value=""" & w_sigla & """></td>"
      ShowHTML "        <td valign=""top""><b><U>O</U>rdem:<br><INPUT ACCESSKEY=""O"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_ordem"" size=""2"" maxlength=""2"" value=""" & w_ordem & """></td>"
      ShowHTML "        <td valign=""top""><b><U>C</U>ódigo:<br><INPUT ACCESSKEY=""C"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_codigo"" size=""15"" maxlength=""15"" value=""" & w_codigo & """></td>"
      ShowHTML "      </tr></table></td></tr>"
      ShowHTML "      <tr><td valign=""top""><b><U>e</U>-Mail:<br><INPUT ACCESSKEY=""E"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_email"" size=""60"" maxlength=""60"" value=""" & w_email & """></td></tr>"
      ShowHTML "      <tr>"
      SelecaoTipoUnidade "<u>T</u>ipo Unidade:", "T", null, w_sq_tipo_unidade, Session("p_cliente"), "w_sq_tipo_unidade", null
      SelecaoEOAreaAtuacao "Á<u>r</u>ea Atuação:", "R", null, w_sq_area_atuacao, Session("p_cliente"), "w_sq_area_atuacao", null
      ShowHTML "      </tr>"
      ShowHTML "      <tr>"
      SelecaoUnidadePai "Unidade <u>p</u>ai:", "P", null, w_sq_unidade_pai, O, Session("p_cliente"), w_sq_unidade, "w_sq_unidade_pai", null
      ShowHTML "      </tr>"
      ShowHTML "      <tr>"
      SelecaoUnidadeGest "Unidade <u>g</u>estora:", "G", null, w_sq_unidade_gestora, w_sq_unidade, "w_sq_unidade_gestora", null
      ShowHTML "      </tr>"
      ShowHTML "      <tr>"
      SelecaoUnidadePag "Unidade p<u>a</u>gadora:", "A", null, w_sq_unidade_pagadora, w_sq_unidade, "w_sq_unidade_pagadora", null
      ShowHTML "      </tr>"
      ShowHTML "      <tr>"
      SelecaoEndereco "En<u>d</u>ereço principal:", "d", null, w_sq_pessoa_endereco, w_cliente, "w_sq_pessoa_endereco", "FISICO"
      ShowHTML "      </tr>"
      ShowHTML "      <tr align=""left""><td valign=""top""><table width=""100%"" cellpadding=0 cellspacing=0><tr>"
      MontaRadioNS "<b>Informal:</b>", w_Informal, "w_Informal"
      MontaRadioNS "<b>Vinculada:</b>", w_Vinculada, "w_Vinculada"
      MontaRadioSN "<b>Adm. Central:</b>", w_Adm_Central, "w_Adm_Central"
      ShowHTML "      </tr>"
      ShowHTML "      <tr align=""left"">"
      MontaRadioNS "<b>Unidade Gestora:</b>", w_Unidade_Gestora, "w_Unidade_Gestora"
      MontaRadioNS "<b>Unidade Pagadora:</b>", w_unidade_pagadora, "w_unidade_pagadora"
      ShowHTML "      </tr></table></td></tr>"
      MontaRadioSN "<b>Ativo:</b>", w_Ativo, "w_Ativo"
      ShowHTML "      <tr><td valign=""top""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
      ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
      ShowHTML "      <tr><td align=""center"" colspan=""3"">"
      If O = "E" Then
         ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Excluir"">"
      Else
         ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Gravar"">"
      End If
      ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & RemoveTP(TP) & "&SG=" & SG & "';"" name=""Botao"" value=""Cancelar"">"
      ShowHTML "          </td>"
      ShowHTML "      </tr>"
      ShowHTML "    </table>"
      ShowHTML "    </TD>"
      ShowHTML "</tr>"
      ShowHTML "</FORM>"
   end if
   ShowHTML "</table>"
   ShowHTML "</center>"
     Estrutura_Texto_Fecha
     Estrutura_Fecha
   Estrutura_Fecha
   Estrutura_Fecha
   Rodape
   
   Set w_libera_edicao       = Nothing
   Set w_Imagem              = Nothing
   Set w_Titulo              = Nothing
   Set w_ContOut             = Nothing
   Set RS1                   = Nothing
   Set RS2                   = Nothing
   Set RS3                   = Nothing
   Set RS4                   = Nothing
   Set RS5                   = Nothing
   Set RS6                   = Nothing
   Set w_nome                = Nothing
   Set w_assinatura          = Nothing
   Set w_nome                = Nothing
   Set w_sigla               = Nothing
   Set w_ordem               = Nothing
   Set w_Informal            = Nothing
   Set w_Vinculada           = Nothing
   Set w_Adm_Central         = Nothing
   Set w_sq_Unidade_Gestora  = Nothing
   Set w_sq_unidade_pagadora = Nothing
   Set w_ativo               = Nothing
   Set w_sq_unidade          = Nothing
   Set w_codigo              = Nothing
   Set RS                    = Nothing   
   Set w_sq_area_atuacao     = Nothing
   Set w_sq_unidade_pai      = Nothing
   Set w_sq_pessoa_endereco  = Nothing
   Set w_sq_tipo_unidade     = Nothing
   Set w_Unidade_Gestora     = Nothing
   Set w_unidade_pagadora    = Nothing
   Set w_email               = Nothing
End Sub

REM =========================================================================
REM Rotina da tabela de localização
REM -------------------------------------------------------------------------
Sub Localizacao

  Dim w_sq_localizacao  
  Dim w_sq_pessoa_endereco
  Dim w_sq_unidade,w_nome,w_fax,w_telefone
  Dim w_ramal,w_telefone2,w_ativo, w_nome_unidade
    
  w_sq_unidade = Request("w_sq_unidade")     
  DB_GetUorgList RS, w_cliente, w_sq_unidade, null, null, null, null
  w_nome_unidade = RS("nome")
  DesconectaBD
  If O = "L" Then
    DB_GetaddressList RS, w_cliente, w_sq_unidade, "LISTALOCALIZACAO", null
  ElseIf (O = "A" or O = "E") Then  
     w_sq_localizacao = Request("w_sq_localizacao")  
      DB_GetaddressList RS, w_cliente, w_sq_localizacao, "LOCALIZACAO", null
      w_sq_localizacao      = RS("sq_localizacao")
     w_sq_pessoa_endereco  = RS("sq_pessoa_endereco")
     w_sq_unidade          = RS("sq_unidade")
     w_nome                = RS("nome") 
     w_fax                 = RS("fax") 
     w_telefone            = RS("telefone")
     w_ramal               = RS("ramal") 
     w_telefone2           = RS("telefone2")
     w_ativo               = RS("ativo")       
     DesconectaBD     
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_sq_pessoa_endereco", "Endereço", "SELECT", "1", "1", "18", "", "1"
        Validate "w_nome", "Localização", "1", "1", "3", "30", "1", "1"
        Validate "w_telefone", "Telefone", "1", "", "1", "12", "", "1"
        Validate "w_ramal", "Ramal", "1", "", "1", "6", "", "1"
        Validate "w_fax", "Fax", "1", "", "1", "12", "", "1"
        Validate "w_telefone2", "Telefone", "1", "", "1", "12", "", "1"        
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     ElseIf O = "E" Then
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
        ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
        ShowHTML "     { return (true); }; "
        ShowHTML "     { return (false); }; "     
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ShowHTML "  theForm.Botao[2].disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ShowHTML "<TITLE>" & conSgSistema & " - Localizações</TITLE>"
  ShowHTML "</HEAD>"
  If InStr("IAE",O) > 0 Then
     If O = "E" Then
        BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
     Else        
        BodyOpen "onLoad='document.Form.w_sq_pessoa_endereco.focus()';"        
     End If  
  Else
     BodyOpen "onLoad=document.focus();"
  End If
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  ShowHTML "<tr><td colspan=4 align=""center""><font size=""2""><b>" & w_nome_unidade & "&nbsp;"
  If O = "L" Then
    ShowHTML "<tr><td><a accesskey=""I"" class=""ss"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_sq_unidade=" & w_sq_unidade & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <a accesskey=""F"" class=""ss"" href=""#"" onClick=""opener.focus(); window.close();""><u>F</u>echar</a>&nbsp;"
    ShowHTML "    <td align=""right""><b>Registros: " & RS.RecordCount    
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><b>Localização</td>"
    ShowHTML "          <td><b>Cidade</td>"
    ShowHTML "          <td><b>Telefone</td>"
    ShowHTML "          <td><b>Ramal</td>"
    ShowHTML "          <td><b>Ativo</td>"
    ShowHTML "          <td><b>Operações</td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      While Not RS.EOF
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
        ShowHTML "        <td align=""left"">" & RS("nome") & "</td>"
        ShowHTML "        <td align=""left"">" & RS("cidade") & "</td>"
        ShowHTML "        <td align=""center"">" & RS("telefone") & "&nbsp;</td>"
        ShowHTML "        <td align=""center"">" & RS("ramal") & "&nbsp;</td>"
        ShowHTML "        <td align=""center"">" & RS("ativo") & "</td>"
        ShowHTML "        <td align=""top"" nowrap>"
        ShowHTML "          <A class=""hl"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_localizacao=" & RS("sq_localizacao") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_sq_unidade=" & w_sq_unidade & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""hl"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_localizacao=" & RS("sq_localizacao") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_sq_unidade=" & w_sq_unidade & """>Excluir</A>&nbsp"
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    DesConectaBD     
  ElseIf Instr("IAE",O) > 0 Then
    If O = "E" Then
       w_Disabled = "DISABLED"
    End If
    AbreForm "Form", w_Pagina&"Grava", "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_localizacao"" value=""" & w_sq_localizacao &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_unidade"" value=""" & w_sq_unidade &""">"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
    ShowHTML "    <table width=""100%"" border=""0"">"
    ShowHTML "      <tr>"
    SelecaoEndereco "En<u>d</u>ereço:", "D", null, w_sq_pessoa_endereco, w_cliente, "w_sq_pessoa_endereco", "FISICO"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td valign=""top""><b><U>L</U>ocalização:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_nome"" size=""30"" maxlength=""30"" value=""" & w_nome & """></td>"
    ShowHTML "      <tr><td valign=""top""><table width=""100%"" cellpadding=0 cellspacing=0><tr valign=""top"">"
    ShowHTML "          <td><b><U>T</U>elefone:<br><INPUT ACCESSKEY=""T"" " & w_Disabled & " class=""sti"" name=""w_telefone"" size=""12"" maxlength=""12"" value=""" & w_telefone & """></INPUT></td>"
    ShowHTML "          <td><b><U>R</U>amal:<br><INPUT ACCESSKEY=""R"" " & w_Disabled & " class=""sti"" name=""w_ramal"" size=""6"" maxlength=""6"" value=""" & w_ramal & """></INPUT></td>"
    ShowHTML "          <td><b><U>F</U>ax:<br><INPUT ACCESSKEY=""F"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_fax"" size=""12"" maxlength=""12"" value=""" & w_fax & """></td>"
    ShowHTML "          <td><b>T<U>e</U>lefone 2:<br><INPUT ACCESSKEY=""E"" " & w_Disabled & " class=""sti"" name=""w_telefone2"" size=""12"" maxlength=""12"" value=""" & w_telefone2 & """></INPUT></td>"
    ShowHTML "          </table>"
    ShowHTML "      <tr>"
    MontaRadioSN "<b>Ativo:</b>", w_Ativo, "w_Ativo"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td valign=""top""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    If O = "E" Then
       ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Gravar"">"
    End If
    ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & RemoveTP(TP) & "&SG=" & SG & "&w_sq_unidade=" & w_sq_unidade & "';"" name=""Botao"" value=""Cancelar"">"
    ShowHTML "            <input class=""stb"" type=""button"" onClick=""opener.focus(); window.close();"" name=""Botao"" value=""Fechar"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"  
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
    Estrutura_Texto_Fecha

  Set w_sq_localizacao       = Nothing  
  Set w_sq_pessoa_endereco   = Nothing  
  Set w_sq_unidade           = Nothing  
  Set w_nome                 = Nothing  
  Set w_fax                  = Nothing  
  Set w_telefone             = Nothing  
  Set w_ramal                = Nothing   
  Set w_telefone2            = Nothing  
  Set w_ativo                = Nothing  
  Set w_nome_unidade         = Nothing  
  
End Sub
REM =========================================================================
REM Fim da tabela de localização
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina da tabela de responsavel
REM -------------------------------------------------------------------------
Sub Responsavel

  Dim w_sq_unidade,p_sq_unidade,p_sq_pessoa,w_nome_unidade  
  Dim w_sq_pessoa,w_sq_pessoa_substituto
  Dim w_inicio_titular, w_inicio_substituto
  Dim w_fim_titular, w_fim_substituto
   
  SG = "RESPONSAVEL"
         
  w_sq_unidade = Request("w_sq_unidade")
  p_sq_pessoa  = Request("p_sq_pessoa")  
  DB_GetUorgList RS, w_cliente, w_sq_unidade, null, null, null, null
  w_nome_unidade = RS("nome")
  DesconectaBD
  If O = "L" Then
     DB_GetUorgResp RS, w_sq_unidade
  ElseIf (O = "A" or O = "E") Then  
     DB_GetUorgResp RS, w_sq_unidade
      w_sq_pessoa            = RS("titular2")
      w_sq_pessoa_substituto = RS("substituto2")     
      w_inicio_titular       = FormatDateTime(Nvl(RS("inicio_titular"),Date()))
      If RS("inicio_substituto") > "" Then w_inicio_substituto    = FormatDateTime(RS("inicio_substituto")) End If
      DesconectaBD   
  ElseIf O = "I" Then
     w_inicio_titular = FormatDateTime(Date())  
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     CheckBranco
     FormataData
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_sq_pessoa", "Pessoa titular", "SELECT", "1", "1", "10", "", "1"
        Validate "w_inicio_titular", "Início titular", "DATA", "1", "10", "10", "", "0123456789/"
        Validate "w_fim_titular", "Início titular", "DATA", "", "10", "10", "", "0123456789/"
        CompData "w_inicio_titular", "Início titular", "<=", "w_fim_titular", "Início titular"
        Validate "w_sq_pessoa_substituto", "Pessoa substituto", "SELECT", "", "1", "10", "", "1"
        Validate "w_inicio_substituto", "Início substituto", "DATA", "", "10", "10", "", "0123456789/"
        Validate "w_fim_substituto", "Início substituto", "DATA", "", "10", "10", "", "0123456789/"
        CompData "w_inicio_substituto", "Início substituto", "<=", "w_fim_substituto", "Início substituto"
        ShowHTML "  if (theForm.w_sq_pessoa_substituto.selectedIndex > 0 && theForm.w_inicio_substituto.value == '') {"
        ShowHTML "     alert('Informe a data de início do substituto!');"
        ShowHTML "     theForm.w_inicio_substituto.focus();"
        ShowHTML "     return false;"
        ShowHTML "  }"
        ShowHTML "  else {"
        ShowHTML "     if (theForm.w_sq_pessoa_substituto.selectedIndex == 0) {"
        ShowHTML "        theForm.w_inicio_substituto.value = '';"
        ShowHTML "        theForm.w_fim_substituto.value = '';"
        ShowHTML "     }"
        ShowHTML "  }"
        ShowHTML "  if (theForm.w_sq_pessoa(theForm.w_sq_pessoa.selectedIndex).value == theForm.w_sq_pessoa_substituto(theForm.w_sq_pessoa_substituto.selectedIndex).value) { "
        ShowHTML "     alert('A mesma pessoa não pode ser indicada para titular e substituto de uma unidade!');"
        ShowHTML "     theForm.w_sq_pessoa_substituto.focus();"
        ShowHTML "     return false;"
        ShowHTML "  }"
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     ElseIf O = "E" Then
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
        ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
        ShowHTML "     { return (true); }; "
        ShowHTML "     { return (false); }; "     
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ShowHTML "  theForm.Botao[2].disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ShowHTML "<TITLE>" & conSgSistema & " - Responsáveis</TITLE>"
  ShowHTML "</HEAD>"
  If InStr("IAE",O) > 0 Then
     If O = "E" Then
        BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
     Else        
        BodyOpen "onLoad='document.Form.w_sq_pessoa.focus()';"
     End If  
  Else
     BodyOpen "onLoad=document.focus();"
  End If
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  ShowHTML "<tr><td colspan=4 align=center><font size=""2""><b>" & w_nome_unidade & "&nbsp;"
  If O = "L" Then
    ShowHTML "<tr><td><a accesskey=""I"" class=""ss"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_sq_unidade=" & w_sq_unidade & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <a accesskey=""F"" class=""ss"" href=""#"" onClick=""opener.focus(); window.close();""><u>F</u>echar</a>&nbsp;"
    ShowHTML "    <td align=""right""><b>Registros: " & RS.RecordCount    
    ShowHTML "<tr><td colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><b>Titular</td>"
    ShowHTML "          <td><b>Substituto</td>"    
    ShowHTML "          <td><b>Operações</td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=3 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      If cDbl(Nvl(RS("titular2"),0)) = 0 and cDbl(Nvl(RS("substituto2"),0)) = 0 Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=3 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
      Else
        While Not RS.EOF
          ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
          ShowHTML "        <td align=""left"">" & RS("titular1") & "</td>"
          ShowHTML "        <td align=""left"">" & RS("substituto1") & "</td>"        
          ShowHTML "        <td align=""top"" nowrap>"
          ShowHTML "          <A class=""hl"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_unidade=" & w_sq_unidade & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_sq_pessoa= " & p_sq_pessoa & """>Alterar</A>&nbsp"
          ShowHTML "          <A class=""hl"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_unidade=" & w_sq_unidade & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_sq_pessoa= " & p_sq_pessoa & """>Excluir</A>&nbsp"
          ShowHTML "        </td>"
          ShowHTML "      </tr>"
          RS.MoveNext
        wend
      End If
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    DesConectaBD     
  ElseIf Instr("IAE",O) > 0 Then
    If O = "E" Then
       w_Disabled = "DISABLED"
    End If
    AbreForm "Form", w_Pagina&"Grava", "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<FORM action=""" & w_Pagina & "Grava"" method=""POST"" name=""Form"" onSubmit=""return(Validacao(this));"">"
    ShowHTML "<INPUT type=""hidden"" name=""w_titular_ant"" value=""" & w_sq_pessoa &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_substituto_ant"" value=""" & w_sq_pessoa_substituto &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_unidade"" value=""" & w_sq_unidade &""">"
    
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
    ShowHTML "    <table width=""100%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top"" colspan=3><font color=""#FF0000""><b>ATENÇÃO: antes de alterar o titular ou o substituto da unidade, informe a data de término da responsabilidade do ocupante atual, grave e entre novamente na opção de alteração.</b></td></tr>"
    ShowHTML "      <tr>"
    SelecaoUsuUnid "<u>T</u>itular:", "T", null, w_sq_pessoa, null, "w_sq_pessoa", O
    ShowHTML "          <td valign=""top""><b>A partir <U>d</U>e:<br><INPUT TYPE=""TEXT"" ACCESSKEY=""D"" " & w_Disabled & " class=""sti"" name=""w_inicio_titular"" size=""10"" maxlength=""10"" value=""" & FormataDataEdicao(w_inicio_titular) & """ onKeyDown=""FormataData(this,event);"">"
    ShowHTML "          <td valign=""top""><b>A<U>t</U>é:<br><INPUT TYPE=""TEXT"" ACCESSKEY=""T"" " & w_Disabled & " class=""sti"" name=""w_fim_titular"" size=""10"" maxlength=""10"" value=""" & w_fim_titular & """ onKeyDown=""FormataData(this,event);"">"
    ShowHTML "      </tr>"
    ShowHTML "      <tr>"
    SelecaoUsuUnid "<u>S</u>ubstituto:", "S", null, w_sq_pessoa_substituto, null, "w_sq_pessoa_substituto", O
    ShowHTML "          <td valign=""top""><b>A partir <U>d</U>e:<br><INPUT TYPE=""TEXT"" ACCESSKEY=""D"" " & w_Disabled & " class=""sti"" name=""w_inicio_substituto"" size=""10"" maxlength=""10"" value=""" & FormataDataEdicao(w_inicio_substituto) & """ onKeyDown=""FormataData(this,event);"">"
    ShowHTML "          <td valign=""top""><b>A<U>t</U>é:<br><INPUT TYPE=""TEXT"" ACCESSKEY=""T"" " & w_Disabled & " class=""sti"" name=""w_fim_substituto"" size=""10"" maxlength=""10"" value=""" & w_fim_substituto & """ onKeyDown=""FormataData(this,event);"">"
    ShowHTML "      <tr><td valign=""top"" colspan=3><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    If O = "E" Then
       ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Gravar"">"
    End If
    ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & RemoveTP(TP) & "&SG=" & SG & "&w_sq_unidade=" & w_sq_unidade & "';"" name=""Botao"" value=""Cancelar"">"
    ShowHTML "            <input class=""stb"" type=""button"" onClick=""opener.focus(); window.close();"" name=""Botao"" value=""Fechar"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Estrutura_Texto_Fecha

  Set w_inicio_titular        = Nothing    
  Set w_fim_titular           = Nothing    
  Set w_inicio_substituto     = Nothing    
  Set w_fim_substituto        = Nothing    
  Set w_sq_unidade            = Nothing    
  Set p_sq_pessoa             = Nothing  
  Set w_sq_pessoa             = Nothing  
  Set w_sq_pessoa_substituto  = Nothing  
  Set w_nome_unidade          = Nothing  
  
End Sub
REM =========================================================================
REM Fim da tabela de responsavel
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de busca das unidades da organização
REM -------------------------------------------------------------------------
Sub BuscaUnidade
 
  Dim w_nome, w_cliente, ChaveAux, restricao, campo, w_sigla
  
  w_nome     = UCase(Request("w_nome"))
  w_sigla    = UCase(Request("w_sigla"))
  w_cliente  = Request("w_cliente")
  ChaveAux   = Request("ChaveAux")
  restricao  = Request("restricao")
  campo      = Request("campo")
  
  DB_GetUorgList RS, w_cliente, ChaveAux, "ATIVO", w_nome, w_sigla, null
  RS.Sort = "nome,co_uf"
  
  Cabecalho
  ShowHTML "<TITLE>Seleção de unidade</TITLE>"
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente
  ScriptOpen "JavaScript"
  ShowHTML "  function volta(l_nome, l_sigla, l_chave) {"
  ShowHTML "     opener.Form." & campo & "_nm" & ".value=l_nome.replace('\'','\\\'') + ' (' + l_sigla + ')';"
  ShowHTML "     opener.Form." & campo & ".value=l_chave;"
  ShowHTML "     opener.Form." & campo & "_nm.focus();"
  ShowHTML "     window.close();"
  ShowHTML "     opener.focus();"
  ShowHTML "   }"
  If RS.RecordCount > 100 or (w_nome > "" or w_sigla > "") Then
     ValidateOpen "Validacao"
     Validate "w_nome", "Nome", "1", "", "4", "30", "1", "1"
     Validate "w_sigla", "Sigla", "1", "", "2", "20", "1", "1"
     ShowHTML "  if (theForm.w_nome.value == '' && theForm.w_sigla.value == '') {"
     ShowHTML "     alert ('Informe um valor para o nome ou para a sigla!');"
     ShowHTML "     theForm.w_nome.focus();"
     ShowHTML "     return false;"
     ShowHTML "  }"
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
  End If
  ScriptClose
  ShowHTML "</HEAD>"
  If RS.RecordCount > 100 or (w_nome > "" or w_sigla > "")  Then
     BodyOpen "onLoad='document.Form.w_nome.focus();'"
  Else
     BodyOpen "onLoad='document.focus()';"
  End If
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
  ShowHTML "    <table width=""100%"" border=""0"">"
  If RS.RecordCount > 100 or (w_nome > "" or w_sigla > "") Then
     AbreForm  "Form", w_Pagina & "BuscaUnidade", "POST", "return(Validacao(this))", null, P1, P2, P3, P4, TP, SG, null, null
     ShowHTML "<INPUT type=""hidden"" name=""w_cliente"" value=""" & w_cliente &""">"
     ShowHTML "<INPUT type=""hidden"" name=""ChaveAux"" value=""" & ChaveAux &""">"
     ShowHTML "<INPUT type=""hidden"" name=""retricao"" value=""" & restricao &""">"
     ShowHTML "<INPUT type=""hidden"" name=""campo"" value=""" & campo &""">"
  
     ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2><b><ul>Instruções</b>:<li>Informe parte do nome da unidade.<li>Quando a relação for exibida, selecione a unidade desejada clicando sobre a caixa ao seu lado.<li>Após informar o nome da unidade, clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Cancelar</i>, a procura é cancelada.</ul></div>"
     ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
     ShowHTML "    <table width=""100%"" border=""0"">"
     ShowHTML "      <tr><td valign=""top""><b>Parte do <U>n</U>ome da unidade:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_nome"" size=""50"" maxlength=""50"" value=""" & w_nome & """>"
     ShowHTML "      <tr><td valign=""top""><b><U>S</U>igla  da unidade:<br><INPUT ACCESSKEY=""S"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_sigla"" size=""20"" maxlength=""20"" value=""" & w_sigla & """>"
  
     ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
     ShowHTML "      <tr><td align=""center"" colspan=""3"">"
     ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
     ShowHTML "            <input class=""stb"" type=""button"" name=""Botao"" value=""Cancelar"" onClick=""window.close(); opener.focus();"">"
     ShowHTML "          </td>"
     ShowHTML "      </tr>"
     ShowHTML "    </table>"
     ShowHTML "    </TD>"
     ShowHTML "</tr>"
     ShowHTML "</form>"
     If w_nome > "" or w_sigla > "" Then
        ShowHTML "<tr><td align=""right""><b>Registros: " & RS.RecordCount
        ShowHTML "<tr><td>"
        ShowHTML "    <TABLE WIDTH=""100%"" border=0>"
        If RS.EOF Then
           ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=5 align=""center""><b>Não foram encontrados registros.</b></td></tr>"
        Else
           ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td>"
           ShowHTML "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
           ShowHTML "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
           ShowHTML "            <td><b>Sigla</td>"
           ShowHTML "            <td><b>Nome</td>"
           ShowHTML "            <td><b>Endereço</td>"
           ShowHTML "            <td><b>Cidade</td>"
           ShowHTML "            <td><b>Operações</td>"
           ShowHTML "          </tr>"
           While Not RS.EOF
              If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
              ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
              ShowHTML "            <td align=""center"">" & RS("sigla") & "</td>"
              ShowHTML "            <td>" & RS("nome") & "</td>"
              ShowHTML "            <td>" & RS("logradouro") & "</td>"
              ShowHTML "            <td>" & RS("nm_cidade") & "-" & RS("co_uf") & "</td>"
              ShowHTML "            <td><a class=""ss"" href=""#"" onClick=""javascript:volta('" & RS("nome") & "', '" & RS("sigla") & "', " & RS("sq_unidade") & ");"">Selecionar</a>"
              RS.MoveNext
           wend
           ShowHTML "        </table></tr>"
           ShowHTML "      </center>"
           ShowHTML "    </table>"
           ShowHTML "  </td>"
          ShowHTML "</tr>"
        End If
        DesConectaBD	 
     End If
  Else
     ShowHTML "<tr><td align=""right""><b>Registros: " & RS.RecordCount
     ShowHTML "<tr><td colspan=6>"
     ShowHTML "    <TABLE WIDTH=""100%"" border=0>"
     If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=5 align=""center""><b>Não foram encontrados registros.</b></td></tr>"
     Else
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td>"
        ShowHTML "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        ShowHTML "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        ShowHTML "            <td><b>Sigla</td>"
        ShowHTML "            <td><b>Nome</td>"
        ShowHTML "            <td><b>Endereço</td>"
        ShowHTML "            <td><b>Cidade</td>"
        ShowHTML "            <td><b>Operações</td>"
        ShowHTML "          </tr>"
        While Not RS.EOF
           If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
           ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
           ShowHTML "            <td align=""center"">" & RS("sigla") & "</td>"
           ShowHTML "            <td>" & RS("nome") & "</td>"
           ShowHTML "            <td>" & RS("logradouro") & "</td>"
           ShowHTML "            <td>" & RS("nm_cidade") & "-" & RS("co_uf") & "</td>"
           ShowHTML "            <td><a class=""ss"" href=""#"" onClick=""javascript:volta('" & RS("nome") & "', '" & RS("sigla") & "', " & RS("sq_unidade") & ");"">Selecionar</a>"
           RS.MoveNext
        wend
        ShowHTML "        </table></tr>"
        ShowHTML "      </center>"
        ShowHTML "    </table>"
        ShowHTML "  </td>"
        ShowHTML "</tr>"
     End If
     DesConectaBD	 
  End If
  ShowHTML "    </table>"
  ShowHTML "    </TD>"
  ShowHTML "</tr>"
  ShowHTML "</FORM>"  
  ShowHTML "</table>"
  ShowHTML "</center>"
  Estrutura_Texto_Fecha

  Set w_nome                = Nothing
  Set w_sigla               = Nothing
      
End Sub
REM =========================================================================
REM Fim da rotina de busca de área do conhecimento
REM -------------------------------------------------------------------------

REM =========================================================================
REM Procedimento que executa as operações de BD
REM -------------------------------------------------------------------------
Public Sub Grava

  Dim w_Chave
  
  Cabecalho
  ShowHTML "</HEAD>"
  BodyOpen "onLoad=document.focus();"
  
  AbreSessao    
  Select Case SG
    Case "EOUORG"  
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then

          DML_EOUnidade O, _
                Request("w_sq_unidade"), Request("w_sq_tipo_unidade"), Request("w_sq_area_atuacao"), Request("w_sq_unidade_gestora"), _
                Request("w_sq_unidade_pai"), Request("w_sq_unidade_pagadora"), Request("w_sq_pessoa_endereco"), _
                Request("w_ordem"),Request("w_email"),Request("w_codigo"), w_cliente, Request("w_nome"), _
                Request("w_sigla"),Request("w_informal"),Request("w_vinculada"),Request("w_adm_central"), _
                Request("w_unidade_gestora"),Request("w_unidade_pagadora"),Request("w_ativo")
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & RemoveTP(TP) & "&SG=" & SG & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "LUORG"         
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          DML_EOLocal O, _
                   Request("w_sq_localizacao"), Request("w_sq_pessoa_endereco"), Request("w_sq_unidade"), _
                   Request("w_nome"), Request("w_fax"), Request("w_telefone"), Request("w_ramal"),_
                   Request("w_telefone2"), Request("w_ativo")          
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & RemoveTP(TP) & "&w_sq_unidade=" & Request("w_sq_unidade") & "&SG=" & SG & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "RESPONSAVEL"  'CADASTRO DE REPONSÁVEL
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          DML_EOResp O, _
                   Request("w_sq_unidade"), Request("w_fim_substituto"), Request("w_sq_pessoa_substituto"), Request("w_inicio_substituto"), _
                   Request("w_fim_titular"), Request("w_sq_pessoa"), Request("w_inicio_titular")
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & RemoveTP(TP) & "&SG=" & SG & "&w_sq_unidade=" & Request("w_sq_unidade") & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
       
    End Select
    
    Set w_Chave = Nothing 
End Sub
REM -------------------------------------------------------------------------
REM Fim do procedimento que executa as operações de BD
REM =========================================================================

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
    Case "UORG"           Unidade
    Case "BUSCAUNIDADE"   BuscaUnidade
    Case "BUSCALCUNIDADE" BuscaLcUnidade
    Case "LOCALIZACAO"    Localizacao
    Case "RESPONSAVEL"    Responsavel
    Case "GRAVA"          Grava
    Case Else
       Cabecalho
       BodyOpen "onLoad=document.focus();"
       Estrutura_Topo_Limpo
       Estrutura_Menu
       Estrutura_Corpo_Abre
       Estrutura_Texto_Abre
       ShowHTML "<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src=""images/icone/underc.gif"" align=""center""> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>"
         Estrutura_Texto_Fecha
         Estrutura_Fecha
       Estrutura_Fecha
       Estrutura_Fecha
       Rodape
  End Select
End Sub
REM =========================================================================
REM Fim da rotina principal
REM -------------------------------------------------------------------------
%>