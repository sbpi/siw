<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE FILE="Constants.inc" -->
<!-- #INCLUDE FILE="jScript.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Geral.asp" -->
<!-- #INCLUDE FILE="DML_Tabelas_Financeiras.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /Tabela_Financeiras.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Gerencia a atualização das tabelas de localização
REM Mail     : alex@sbpi.com.br
REM Criacao  : 19/03/2003, 16:35
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
Dim dbms, sp, RS, RS1, RS2, RS3
Dim P1, P2, P3, P4, TP, SG
Dim R, O, w_Cont, w_Pagina, w_Disabled, w_TP, w_troca, w_cor
Dim w_ContOut
Dim w_Titulo
Dim w_Imagem
Dim w_ImagemPadrao
Dim w_Assinatura, w_Cliente, w_Classe, w_filter
Dim w_dir, w_dir_volta, w_submenu
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
w_troca      = Request("w_troca")
w_Assinatura = uCase(Request("w_Assinatura"))
w_Pagina     = "Tabela_Financeiras.asp?par="
w_Disabled   = "ENABLED"

If P3 = "" Then P3 = 1           Else P3 = cDbl(P3) End If
If P4 = "" Then P4 = conPageSize Else P4 = cDbl(P4) End If

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
  Case "D" 
     w_TP = TP & " - Desativar"
  Case "T" 
     w_TP = TP & " - Ativar"
  Case "H" 
     w_TP = TP & " - Herança"
  Case Else
     w_TP = TP & " - Listagem"
End Select

w_cliente         = RetornaCliente()
  
Main

FechaSessao

Set w_filter        = Nothing
Set w_dir           = Nothing
Set w_dir_volta     = Nothing
Set w_cor           = Nothing
Set w_classe        = Nothing
Set w_cliente       = Nothing

Set RS              = Nothing
Set RS1             = Nothing
Set RS2             = Nothing
Set RS3             = Nothing
Set Par             = Nothing
Set P1              = Nothing
Set P2              = Nothing
Set P3              = Nothing
Set P4              = Nothing
Set TP              = Nothing
Set SG              = Nothing
Set R               = Nothing
Set O               = Nothing
Set w_ImagemPadrao  = Nothing
Set w_Imagem        = Nothing
Set w_Titulo        = Nothing
Set w_ContOut       = Nothing
Set w_Cont          = Nothing
Set w_Pagina        = Nothing
Set w_Disabled      = Nothing
Set w_TP            = Nothing
Set w_troca         = Nothing
Set w_Assinatura    = Nothing

REM =========================================================================
REM Rotina de centros de custo
REM -------------------------------------------------------------------------
Sub CentroCusto
  Dim w_texto
  Dim w_troca
  Dim w_ContOut
  Dim w_Titulo
  Dim w_Imagem
  Dim w_ImagemPadrao
  Dim RS1, RS2, RS3
  Dim w_marcado
  
  Dim w_sq_cc, w_sq_cc_pai, w_nome, w_sigla, w_ativo, w_descricao
  Dim w_regular, w_receita
  Dim w_heranca
  
  w_ImagemPadrao         = "images/folder/SheetLittle.gif"
  w_troca                = Request("w_troca")
  w_heranca              = Request("w_heranca")
  w_sq_cc                = Request("w_sq_cc")
  
  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente
  Response.Write w_heranca
  If O <> "L" Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     If O <> "P" and O <> "H" Then
        If w_heranca > "" or (O <> "I" and w_troca = "") Then
        
           ' Se for herança, atribui a chave da opção selecionada para w_sq_cc
           If w_heranca > "" Then w_sq_cc = w_heranca End If
        
           DB_GetCCData RS, w_sq_cc
           w_sq_cc_pai                = RS("sq_cc_pai")
           w_nome                     = RS("nome")
           w_sigla                    = RS("sigla")
           w_descricao                = RS("descricao")
           w_ativo                    = RS("ativo")
           w_receita                  = RS("receita")
           w_regular                  = RS("regular")
           DesconectaBD
        ElseIf w_troca > "" Then
           w_sq_cc_pai                = Request("w_sq_cc_pai")
           w_nome                     = Request("w_nome")
           w_descricao                = Request("w_descricao")
           w_regular                  = Request("w_regular")
           w_cliente                  = Request("w_cliente")
           w_sigla                    = Request("w_sigla")
           w_ativo                    = Request("w_ativo")
           w_regular                  = Request("w_regular")
        End If
        If O = "I" or O = "A" Then
           Validate "w_nome", "Nome", "1", "1", "5", "60", "1", "1"
           Validate "w_descricao", "Descrição", "1", "1", "5", "500", "1", "1"
           Validate "w_sigla", "Sigla", "1", "1", "2", "20", "1", "1"
        End If
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     ElseIf O = "H" Then
        Validate "w_heranca", "Origem dos dados", "SELECT", "1", "1", "10", "", "1"
        ShowHTML "  if (confirm('Confirma herança dos dados da opção selecionada?')) {"
        ShowHTML "     window.close(); "
        ShowHTML "     opener.focus(); "
        ShowHTML "     return true; "
        ShowHTML "  } "
        ShowHTML "  else { return false; } "
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ShowHTML "<style> "
  ShowHTML " .lh{text-decoration:none;font:Arial;color=""#FF0000""} "
  ShowHTML " .lh:HOVER{text-decoration: underline;} "
  ShowHTML "</style> "
  ShowHTML "</HEAD>"
  If w_Troca > "" Then
     BodyOpen "onLoad=document.Form." & w_troca & ".focus();"
  ElseIf O = "I" or O = "A" Then
     BodyOpen "onLoad=document.Form.w_nome.focus();"
  ElseIf O = "H" Then
     BodyOpen "onLoad=document.Form.w_heranca.focus();"
  ElseIf O = "L" Then
     BodyOpen "onLoad=document.focus();"
  Else
     BodyOpen "onLoad=document.Form.w_assinatura.focus();"
  End If
  If O <> "H" Then
     Estrutura_Topo_Limpo
     Estrutura_Menu
    Estrutura_Corpo_Abre
  End IF
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
  ShowHTML "    <table width=""99%"" border=""0"">"
  If O = "L" Then
     ShowHTML "      <tr><td><font size=""2""><a accesskey=""I"" class=""ss"" href=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=I&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=CT_CC""><u>I</u>ncluir</a>&nbsp;"
     ShowHTML "      <tr><td height=""1"" bgcolor=""#000000"">"
     ShowHTML "      <tr><td><font size=2><b>"
     DB_GetCCTree RS, w_cliente, "IS NULL"
     w_ContOut = 0
     While Not RS.EOF
        w_Titulo = RS("sigla")
        w_ContOut = w_ContOut + 1
        If cDbl(RS("Filho")) > 0 Then
           ShowHTML "<A HREF=#""" & RS("sq_cc") & """></A>"
           ShowHTML "<font size=2><span><div align=""left""><img src=""images/folder/FolderClose.gif"" border=0 align=""center""> " & RS("sigla") & "<font size=1>"
           If RS("ativo") = "S" Then w_classe="hl" Else w_classe="lh" End If
           ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_cc=" & RS("sq_cc") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=CT_CC"" title=""Altera as informações deste centro de custos"">Alterar</A>&nbsp"
           If RS("ativo") = "S" Then
              ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=D&w_sq_cc=" & RS("sq_cc") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=CT_CC"" title=""Impede que este centro de custos seja associado a novos registros"">Desativar</A>&nbsp"
           Else
              ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=T&w_sq_cc=" & RS("sq_cc") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=CT_CC"" title=""Permite que este centro de custos seja associado a novos registros"">Ativar</A>&nbsp"
           End If
           ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_cc=" & RS("sq_cc") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=CT_CC"" title=""Exclui o centro de custos"">Excluir</A>&nbsp"
           ShowHTML "       </div></span></font></font>"
           ShowHTML "   <div style=""position:relative; left:12;""><font size=1>"
           DB_GetCCTree RS1, w_cliente, RS("sq_cc")
           While Not RS1.EOF
              w_Titulo = w_Titulo & " - " & RS1("sigla")
              If cDbl(RS1("Filho")) > 0 Then
                 w_ContOut = w_ContOut + 1
                 ShowHTML "<A HREF=#""" & RS1("sq_cc") & """></A>"
                 ShowHTML "<font size=1><span><div align=""left""><img src=""images/folder/FolderClose.gif"" border=0 align=""center""> " & RS1("sigla") & "<font size=1>"
                 If RS1("ativo") = "S" Then w_classe="hl" Else w_classe="lh" End If
                 ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_cc=" & RS1("sq_cc") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=CT_CC"" title=""Altera as informações deste centro de custos"">Alterar</A>&nbsp"
                 If RS1("ativo") = "S" Then
                    ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=D&w_sq_cc=" & RS1("sq_cc") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=CT_CC"" title=""Impede que este centro de custos seja associado a novos registros"">Desativar</A>&nbsp"
                 Else
                    ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=T&w_sq_cc=" & RS1("sq_cc") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=CT_CC"" title=""Permite que este centro de custos seja associado a novos registros"">Ativar</A>&nbsp"
                 End If
                 ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_cc=" & RS1("sq_cc") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=CT_CC"" title=""Exclui o centro de custos"">Excluir</A>&nbsp"
                 ShowHTML "       </div></span></font></font>"
                 ShowHTML "   <div style=""position:relative; left:12;""><font size=1>"
                 DB_GetCCTree RS2, w_cliente, RS1("sq_cc")
                 While Not RS2.EOF
                    w_Titulo = w_Titulo & " - " & RS2("sigla")
                    If cDbl(RS2("Filho")) > 0 Then
                       w_ContOut = w_ContOut + 1
                       ShowHTML "<A HREF=#""" & RS2("sq_cc") & """></A>"
                       ShowHTML "<font size=1><span><div align=""left""><img src=""images/folder/FolderClose.gif"" border=0 align=""center""> " & RS2("sigla") & "<font size=1>"
                       If RS2("ativo") = "S" Then w_classe="hl" Else w_classe="lh" End If
                       ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_cc=" & RS2("sq_cc") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=CT_CC"" title=""Altera as informações deste centro de custos"">Alterar</A>&nbsp"
                       If RS2("ativo") = "S" Then
                          ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=D&w_sq_cc=" & RS2("sq_cc") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=CT_CC"" title=""Impede que este centro de custos seja associado a novos registros"">Desativar</A>&nbsp"
                       Else
                          ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=T&w_sq_cc=" & RS2("sq_cc") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=CT_CC"" title=""Permite que este centro de custos seja associado a novos registros"">Ativar</A>&nbsp"
                       End If
                       ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_cc=" & RS2("sq_cc") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=CT_CC"" title=""Exclui o centro de custos"">Excluir</A>&nbsp"
                       ShowHTML "       </div></span></font></font>"
                       ShowHTML "   <div style=""position:relative; left:12;""><font size=1>"
                       DB_GetCCTree RS3, w_cliente, RS2("sq_cc")
                       While Not RS3.EOF
                          w_Titulo = w_Titulo & " - " & RS3("sigla")
                          ShowHTML "<A HREF=#""" & RS3("sq_cc") & """></A>"
                          ShowHTML "    <img src=""" & w_Imagem & """ border=0 align=""center""> " & RS3("sigla")
                          If RS3("ativo") = "S" Then w_classe="hl" Else w_classe="lh" End If
                          ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_cc=" & RS3("sq_cc") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=CT_CC"" title=""Altera as informações deste centro de custos"">Alterar</A>&nbsp"
                          If RS3("ativo") = "S" Then
                             ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=D&w_sq_cc=" & RS3("sq_cc") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=CT_CC"" title=""Impede que este centro de custos seja associado a novos registros"">Desativar</A>&nbsp"
                          Else
                             ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=T&w_sq_cc=" & RS3("sq_cc") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=CT_CC"" title=""Permite que este centro de custos seja associado a novos registros"">Desativar</A>&nbsp"
                          End If
                          ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_cc=" & RS3("sq_cc") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=CT_CC"" title=""Exclui o centro de custos"">Excluir</A>&nbsp"
                          ShowHTML "    <BR>"
                          w_Titulo = Replace(w_Titulo, " - "&RS3("sigla"), "")
                          RS3.MoveNext
                       Wend
                       ShowHTML "   </font></div>"
                    Else
                       ShowHTML "    <img src=""" & w_Imagem & """ border=0 align=""center""> " & RS2("sigla")
                       If RS2("ativo") = "S" Then w_classe="hl" Else w_classe="lh" End If
                       ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_cc=" & RS2("sq_cc") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=CT_CC"" title=""Altera as informações deste centro de custos"">Alterar</A>&nbsp"
                       If RS2("ativo") = "S" Then
                          ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=D&w_sq_cc=" & RS2("sq_cc") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=CT_CC"" title=""Impede que este centro de custos seja associado a novos registros"">Desativar</A>&nbsp"
                       Else
                          ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=T&w_sq_cc=" & RS2("sq_cc") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=CT_CC"" title=""Permite que este centro de custos seja associado a novos registros"">Ativar</A>&nbsp"
                       End If
                       ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_cc=" & RS2("sq_cc") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=CT_CC"" title=""Exclui o centro de custos"">Excluir</A>&nbsp"
                       ShowHTML "    <BR>"
                    End If
                    w_Titulo = Replace(w_Titulo, " - "&RS2("sigla"), "")
                    RS2.MoveNext
                 Wend
                 ShowHTML "   </font></div>"
               Else
                 w_Imagem = w_ImagemPadrao
                 ShowHTML "    <img src=""" & w_Imagem & """ border=0 align=""center""> " & RS1("sigla")
                 If RS1("ativo") = "S" Then w_classe="hl" Else w_classe="lh" End If
                 ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_cc=" & RS1("sq_cc") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=CT_CC"" title=""Altera as informações deste centro de custos"">Alterar</A>&nbsp"
                 If RS1("ativo") = "S" Then
                    ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=D&w_sq_cc=" & RS1("sq_cc") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=CT_CC"" title=""Impede que este centro de custos seja associado a novos registros"">Desativar</A>&nbsp"
                 Else
                    ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=T&w_sq_cc=" & RS1("sq_cc") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=CT_CC"" title=""Permite que este centro de custos seja associado a novos registros"">Ativar</A>&nbsp"
                 End If
                 ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_cc=" & RS1("sq_cc") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=CT_CC"" title=""Exclui o centro de custos"">Excluir</A>&nbsp"
                 ShowHTML "    <BR>"
              End If
              w_Titulo = Replace(w_Titulo, " - "&RS1("sigla"), "")
              RS1.MoveNext
           Wend
           ShowHTML "   </font></div>"
        Else
           w_Imagem = w_ImagemPadrao
           ShowHTML "    <img src=""" & w_Imagem & """ border=0 align=""center""><font size=2> " & RS("sigla") & "<font size=1>"
           If RS("ativo") = "S" Then w_classe="hl" Else w_classe="lh" End If
           ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_cc=" & RS("sq_cc") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=CT_CC"" title=""Altera as informações deste centro de custos"">Alterar</A>&nbsp"
           If RS("ativo") = "S" Then
              ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=D&w_sq_cc=" & RS("sq_cc") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=CT_CC"" title=""Impede que este centro de custos seja associado a novos registros"">Desativar</A>&nbsp"
           Else
              ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=T&w_sq_cc=" & RS("sq_cc") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=CT_CC"" title=""Permite que este centro de custos seja associado a novos registros"">Ativar</A>&nbsp"
           End If
           ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_cc=" & RS("sq_cc") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=CT_CC"" title=""Exclui o centro de custos"">Excluir</A>&nbsp"
           ShowHTML "    <BR>"
        End If
        RS.MoveNext
     Wend
     If w_contOut = 0 Then ' Se não achou registros
        ShowHTML "<font size=2>Não foram encontrados registros."
     End If
  ElseIf O <> "H" Then
     If O <> "I" and O <> "A" Then w_Disabled = "disabled" End If
     ' Se for inclusão de nova opção, permite a herança dos dados de outra, já existente.
     If O = "I" Then
        ShowHTML "      <tr><td><font size=""2""><a accesskey=""H"" class=""ss"" href=""#"" onClick=""window.open('" & w_pagina & par & "&R=" & w_Pagina & "CENTROCUSTO&O=H&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_sq_cc="&w_sq_cc&"','heranca','top=70,left=10,width=780,height=200,toolbar=no,status=no,scrollbars=no');""><u>H</u>erdar dados</a>&nbsp;"
        ShowHTML "      <tr><td height=""1"" bgcolor=""#000000"">"
     End If
     AbreForm "Form", w_Pagina&"Grava", "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,w_pagina&par,O
     ShowHTML "<INPUT type=""hidden"" name=""w_cliente"" value=""" & w_cliente & """>"
     ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
     ShowHTML "<INPUT type=""hidden"" name=""w_sq_cc"" value=""" & w_sq_cc & """>"
     ShowHTML "      <tr><td valign=""top""><table width=""100%"" border=0><tr valign=""top"">"
     ShowHTML "          <td><font size=""1""><b><u>N</u>ome:<br><INPUT ACCESSKEY=""D"" TYPE=""TEXT"" CLASS=""sti"" NAME=""w_nome"" SIZE=40 MAXLENGTH=60 VALUE=""" & w_nome & """ " & w_Disabled & " title=""Nome do centro de custo.""></td>"
     ' Recupera a lista de opções
     If O <> "I" and O <> "H" Then ' Se for alteração, não deixa vincular a opção a ela mesma, nem a seus filhos
        SelecaoCCSubordination "<u>S</u>ubordinação:", "S", "Se esta opção estiver subordinada a outra já existente, informe qual.", w_sq_cc, w_sq_cc_pai, "w_sq_cc_pai", "PARTE", null
     Else
        SelecaoCCSubordination "<u>S</u>ubordinação:", "S", "Se esta opção estiver subordinada a outra já existente, informe qual.", w_sq_cc, w_sq_cc_pai, "w_sq_cc_pai", "TODOS", null
     End If
     ShowHTML "          </table>"
     ShowHTML "      <tr><td valign=""top"" colspan=3><font size=""1""><b><U>D</U>escricao:<br><TEXTAREA ACCESSKEY=""C"" class=""sti"" name=""w_descricao"" rows=5 cols=80 title=""Descreva sucintamente o centro de custo."" " & w_disabled & ">" & w_descricao & "</textarea></td>"
     ShowHTML "      <tr><td valign=""top""><table width=""100%"" border=0><tr valign=""top"">"
     ShowHTML "          <td valign=""top""><font size=""1""><b>S<u>i</u>gla:<br><INPUT ACCESSKEY=""S"" TYPE=""TEXT"" CLASS=""sti"" NAME=""w_sigla"" SIZE=20 MAXLENGTH=20 VALUE=""" & w_sigla & """ " & w_Disabled & " title=""Informe a sigla desejada para o centro de custo.""></td>"
     ShowHTML "          <td valign=""top"" title=""Informe \'Sim\' se este centro de custo for regular, e \'Não\' se ele for extra-orçamentário.""><font size=""1""><b>Regular?</b><br>"
     If w_regular = "S" or w_regular = "" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_regular"" value=""S"" checked> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_regular"" value=""N""> Não"
     Else
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_regular"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_regular"" value=""N"" checked> Não"    
     End If
     ShowHTML "          <td valign=""top"" title=""Informe \'Sim\' se este centro de custo for relativo a receitas, e \'Não\' se ele for relativo a despesas.""><font size=""1""><b>Receita?</b><br>"
     If w_receita = "S" or w_receita = "" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_receita"" value=""S"" checked> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_receita"" value=""N""> Não"
     Else
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_receita"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_receita"" value=""N"" checked> Não"    
     End If
     ShowHTML "          </table>"

     If O = "I" Then
        ShowHTML "      <tr align=""left"">"
        MontaRadioSN "Ativo?", w_ativo, "w_ativo"
        ShowHTML "      </tr>"
     End If
     
     ShowHTML "      </td></tr>"
     ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
     ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
     ShowHTML "      <tr><td align=""center"" colspan=""3""><input class=""stb"" type=""submit"" name=""Botao"" value=""Gravar"">&nbsp;"
     ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & R & "&O=L&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"" name=""Botao"" value=""Cancelar"">"
     ShowHTML "</FORM>"
  ElseIf O = "H" Then
    AbreForm "Form", R, "POST", "return(Validacao(this));", "content",P1,P2,P3,P4,TP,SG,R,"I"
    ShowHTML "<INPUT type=""hidden"" name=""w_cliente"" value=""" & w_cliente & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_cc"" value=""" & w_sq_cc & """>"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Selecione, na relação, a opção a ser utilizada como origem de dados.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td width=""100%"">"
    ShowHTML "    <table align=""center"" border=""0"">"
    ShowHTML "      <tr><td valign=""top""><table border=0 cellspacing=0 cellpadding=0>"
    ShowHTML "      <tr>"
    SelecaoCC "<u>O</u>rigem:", "O", "Selecione na lista o centro de custo a ser usado como origem de dados.", w_heranca, null, "w_heranca", null
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td align=""center""><font size=1>&nbsp;"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"">"
    ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Herdar"">"
    ShowHTML "            <input class=""stb"" type=""button"" onClick=""window.close(); opener.focus();"" name=""Botao"" value=""Cancelar"">"
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
  ShowHTML "    </table>"
  ShowHTML "    </TD>"
  ShowHTML "</tr>"
  ShowHTML "</table>"
  ShowHTML "</center>"
    Estrutura_Texto_Fecha
  If O <> "H" Then
       Estrutura_Fecha
     Estrutura_Fecha
     Estrutura_Fecha
     Rodape
  End If

  Set w_heranca                 = Nothing 
  Set w_sq_cc                   = Nothing 
  Set w_sq_cc_pai               = Nothing 
  Set w_descricao               = Nothing 
  Set w_regular                 = Nothing 
  Set w_sigla                   = Nothing 
  Set w_ativo                   = Nothing 
  Set w_regular                 = Nothing 
  Set RS1                       = Nothing
  Set RS2                       = Nothing
  Set RS3                       = Nothing
  Set w_ImagemPadrao            = Nothing
  Set w_Imagem                  = Nothing
  Set w_Titulo                  = Nothing
  Set w_ContOut                 = Nothing
  Set w_troca                   = Nothing
  Set w_nome                    = Nothing
  Set w_texto                   = Nothing
End Sub
REM =========================================================================
REM Fim da rotina de centros de custo
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina da tabela de agências
REM -------------------------------------------------------------------------
Sub Agencia

  Dim w_sq_agencia
  Dim w_sq_banco, p_sq_banco
  Dim w_codigo
  Dim w_ativo, p_ativo
  Dim w_padrao
  Dim w_nome, p_nome
  Dim p_Ordena

  p_sq_banco    = uCase(Request("p_sq_banco"))
  p_nome        = uCase(Request("p_nome"))
  p_ativo       = uCase(Request("p_ativo"))
  p_ordena      = uCase(Request("p_ordena"))
  
  If p_sq_banco = "" Then O = "P" End If
  
  If w_troca > "" Then
     w_sq_agencia        = Request("w_sq_agencia")
     w_sq_banco          = Request("w_sq_banco")
     w_codigo            = Request("w_codigo")
     w_ativo             = Request("w_ativo")
     w_padrao            = Request("w_padrao")
     w_nome              = Request("w_nome")
  ElseIf O = "L" Then
     DB_GetBankHouseList RS, p_sq_banco, p_nome, p_ordena
  ElseIf O = "A" or O = "E" Then
     w_sq_agencia = Request("w_sq_agencia")
     DB_GetBankHouseData RS, w_sq_agencia
     w_sq_banco          = RS("sq_banco")
     w_codigo            = RS("codigo")
     w_ativo             = RS("ativo")
     w_padrao            = RS("padrao")
     w_nome              = RS("nome")
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_sq_banco", "Banco", "SELECT", "1", "1", "18", "1", "1"
        Validate "w_codigo", "Código", "1", "1", "4", "4", "", "0123456789"
        Validate "w_nome", "Nome", "1", "1", "3", "60", "1", "1"
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     ElseIf O = "E" Then
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
        ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
        ShowHTML "     { return (true); }; "
        ShowHTML "     { return (false); }; "
     ElseIf O="P" Then
        Validate "p_sq_banco", "UF", "SELECT", "", "1", "3", "1", "1"
        Validate "p_nome", "nome", "1", "", "3", "50", "1", "1"
        Validate "P4", "Linhas por página", "1", "1", "1", "4", "", "0123456789"
        ShowHTML "  if (theForm.p_sq_banco.selectedIndex==0 || theForm.p_nome.value=='') {"
        ShowHTML "     alert('Informe o banco e parte do nome da agência!');"
        ShowHTML "     theForm.p_sq_banco.focus;"
        ShowHTML "     return false;"
        ShowHTML "   }"
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  If w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf InStr("IAE",O) > 0 Then
     If O = "E" Then
        BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
     Else
        BodyOpen "onLoad='document.Form.w_sq_banco.focus()';"
     End If
  ElseIf InStr("P",O) > 0 Then
     BodyOpen "onLoad='document.Form.p_sq_banco.focus()';"
  Else
     BodyOpen "onLoad=document.focus();"
  End If
  Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""ss"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_sq_banco=" & p_sq_banco & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """><u>I</u>ncluir</a>&nbsp;"
    If p_sq_banco & p_nome & p_ativo & p_Ordena > "" Then
       ShowHTML "                         <a accesskey=""F"" class=""ss"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_sq_banco=" & p_sq_banco & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a></font>"
    Else
       ShowHTML "                         <a accesskey=""F"" class=""ss"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_sq_banco=" & p_sq_banco & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """><u>F</u>iltrar (Inativo)</a>"
    End If
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """>"
    ShowHTML "          <td><font size=""1""><b>Chave</font></td>"
    ShowHTML "          <td><font size=""1""><b>Banco</font></td>"
    ShowHTML "          <td><font size=""1""><b>Código</font></td>"
    ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
    ShowHTML "          <td><font size=""1""><b>Ativo</font></td>"
    ShowHTML "          <td><font size=""1""><b>Padrão</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF and RS.AbsolutePage = P3
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("sq_agencia") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("sq_banco") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("codigo") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("nome") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("ativo") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("padrao") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""hl"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_agencia=" & RS("sq_agencia") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_sq_banco=" & p_sq_banco & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""hl"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_agencia=" & RS("sq_agencia") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_sq_banco=" & p_sq_banco & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """>Excluir</A>&nbsp"
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    ShowHTML "<tr><td align=""center"" colspan=3>"
    MontaBarra w_pagina&par&"&R="&w_Pagina&par&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG, RS.PageCount, P3, P4, RS.RecordCount
    ShowHTML "</tr>"
    DesconectaBD     
  ElseIf Instr("IAE",O) > 0 Then
    If O = "E" Then w_Disabled = "DISABLED" End If
    AbreForm "Form", w_Pagina&"Grava", "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_sq_banco"" value=""" & p_sq_banco &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_nome"" value=""" & p_nome &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_ativo"" value=""" & p_ativo &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_ordena"" value=""" & p_ordena &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_agencia"" value=""" & w_sq_agencia &""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
    ShowHTML "    <table width=""100%"" border=""0"">"
    ShowHTML "      <tr>"
    SelecaoBanco "<u>B</u>anco:", "B", null, w_sq_banco, null, "w_sq_banco", null, null
    ShowHTML "      </tr>"
    ShowHTML "      <tr align=""left""><td valign=""top""><table width=""100%"" cellpadding=0 cellspacing=0><tr><td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>C</U>ódigo:<br><INPUT ACCESSKEY=""C"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_codigo"" size=""4"" maxlength=""4"" value=""" & w_codigo & """></td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_nome"" size=""40"" maxlength=""40"" value=""" & w_nome & """></td>"
    ShowHTML "      </tr></table></td></tr>"
    ShowHTML "      <tr>"
    MontaRadioNS "Padrão?", w_padrao, "w_padrao"
    ShowHTML "      </tr>"
    ShowHTML "      <tr align=""left"">"
    MontaRadioSN "Ativo?", w_ativo, "w_ativo"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    If O = "E" Then
       ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Gravar"">"
    End If
    ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & "&O=L&p_sq_banco=" & p_sq_banco & "';"" name=""Botao"" value=""Cancelar"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  ElseIf Instr("P",O) > 0 Then
    AbreForm "Form", w_Pagina&par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
    ShowHTML "    <table width=""100%"" border=""0"">"
    ShowHTML "      <tr align=""left"">"
    SelecaoBanco "<u>B</u>anco:", "B", null, p_sq_banco, null, "p_sq_banco", null, null
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""sti"" type=""text"" name=""p_nome"" size=""40"" maxlength=""40"" value=""" & p_nome & """></td>"
    ShowHTML "      <tr align=""left"">"
    MontaRadioSN "Ativo?", p_ativo, "p_ativo"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY=""O"" " & w_Disabled & " class=""sts"" name=""p_ordena"" size=""1"">"
    If p_Ordena="NOME" Then
       ShowHTML "          <option value="""">Código<option value=""nome"" SELECTED>Nome<option value=""ativo"">Ativo"
    ElseIf p_Ordena="ATIVO" Then
       ShowHTML "          <option value="""">Código<option value=""nome"">Nome<option value=""ativo"" SELECTED>Ativo"
    Else
       ShowHTML "          <option value="""" SELECTED>Código<option value=""nome"">Nome<option value=""ativo"">Ativo"
    End If
    ShowHTML "          </select></td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""sti"" type=""text"" name=""P4"" size=""4"" maxlength=""4"" value=""" & P4 & """></td>"
    ShowHTML "      </table>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
    ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&p_sq_banco=" & p_sq_banco & "&SG=" & SG & "';"" name=""Botao"" value=""Remover filtro"">"
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
    Estrutura_Fecha
  Estrutura_Fecha
  Estrutura_Fecha
  Rodape

  Set w_sq_agencia  = Nothing
  Set w_sq_banco    = Nothing
  Set w_codigo      = Nothing
  Set w_ativo       = Nothing
  Set w_padrao      = Nothing
  Set w_nome        = Nothing

  Set p_ativo       = Nothing
  Set p_sq_banco    = Nothing
  Set p_nome        = Nothing
  Set p_Ordena      = Nothing

End Sub
REM =========================================================================
REM Fim da tabela de agências
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina da tabela de bancos
REM -------------------------------------------------------------------------
Sub Banco

  Dim w_sq_banco
  Dim w_nome, p_nome
  Dim w_ativo, p_ativo
  Dim w_codigo, p_codigo
  Dim w_padrao
  Dim p_Ordena

  p_nome             = uCase(Request("p_nome"))
  p_ativo            = uCase(Request("p_ativo"))
  p_ordena           = uCase(Request("p_ordena"))
  p_codigo           = uCase(Request("p_codigo"))
  
  If O = "L" Then
     DB_GetBankList RS
     If p_nome & p_codigo & p_ativo > "" Then
        w_filter = ""
        If p_nome > ""   Then w_filter = w_filter & " and nome   like '*" & p_nome & "*'" End If
        If p_codigo > "" Then w_filter = w_filter & " and codigo = '" & p_codigo & "'"    End If
        If p_ativo > ""  Then w_filter = w_filter & " and ativo  = '" & p_ativo & "'"     End If
        RS.Filter = Mid(w_filter,6,255)
     End If
     If p_ordena > "" Then RS.sort = p_ordena Else RS.sort = "codigo" End If
  ElseIf O = "A" or O = "E" Then
     w_sq_banco = Request("w_sq_banco")
     DB_GetBankData RS, w_sq_banco
     w_nome                 = RS("nome")
     w_padrao               = RS("padrao")
     w_codigo               = RS("codigo")
     w_ativo                = RS("ativo")
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_codigo", "Código", "1", "1", "3", "3", "", "1"
        Validate "w_nome", "Nome", "1", "1", "3", "30", "1", "1"
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     ElseIf O = "E" Then
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
        ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
        ShowHTML "     { return (true); }; "
        ShowHTML "     { return (false); }; "
     ElseIf O="P" Then
        Validate "p_codigo", "Código", "1", "", "3", "3", "", "0123456789"
        Validate "p_nome", "nome", "1", "", "3", "30", "1", "1"
        Validate "P4", "Linhas por página", "1", "1", "1", "4", "", "0123456789"
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  If InStr("IAE",O) > 0 Then
     If O = "E" Then
        BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
     Else
        BodyOpen "onLoad='document.Form.w_codigo.focus()';"
     End If
  ElseIf InStr("P",O) > 0 Then
     BodyOpen "onLoad='document.Form.p_codigo.focus()';"
  Else
     BodyOpen "onLoad=document.focus();"
  End If
  Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""ss"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_codigo=" & p_codigo & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """><u>I</u>ncluir</a>&nbsp;"
    If p_nome & p_codigo & p_ativo & p_Ordena > "" Then
       ShowHTML "                         <a accesskey=""F"" class=""ss"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_codigo=" & p_codigo & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a></font>"
    Else
       ShowHTML "                         <a accesskey=""F"" class=""ss"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_codigo=" & p_codigo & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """><u>F</u>iltrar (Inativo)</a>"
    End If
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""2""><b>Chave</font></td>"
    ShowHTML "          <td><font size=""2""><b>Código</font></td>"
    ShowHTML "          <td><font size=""2""><b>Nome</font></td>"
    ShowHTML "          <td><font size=""2""><b>Ativo</font></td>"
    ShowHTML "          <td><font size=""2""><b>Padrão</font></td>"
    ShowHTML "          <td><font size=""2""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF and RS.AbsolutePage = P3
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("sq_banco") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("codigo") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("nome") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("ativo") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("padrao") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""hl"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_banco=" & RS("sq_banco") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_codigo=" & p_codigo & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""hl"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_banco=" & RS("sq_banco") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_codigo=" & p_codigo & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """>Excluir</A>&nbsp"
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    ShowHTML "<tr><td align=""center"" colspan=3>"
    MontaBarra w_pagina&par&"&R="&w_Pagina&par&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG, RS.PageCount, P3, P4, RS.RecordCount
    ShowHTML "</tr>"
    DesConectaBD     
  ElseIf Instr("IAE",O) > 0 Then
    If O = "E" Then
       w_Disabled = "DISABLED"
    End If
    AbreForm "Form", w_Pagina&"Grava", "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""p_nome"" value=""" & p_nome &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_codigo"" value=""" & p_codigo &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_ativo"" value=""" & p_ativo &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_ordena"" value=""" & p_ordena &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_banco"" value=""" & w_sq_banco &""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
    ShowHTML "    <table width=""100%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>C</U>ódigo:<br><INPUT ACCESSKEY=""C"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_codigo"" size=""3"" maxlength=""3"" value=""" & w_codigo & """></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_nome"" size=""30"" maxlength=""30"" value=""" & w_nome & """></td>"
    ShowHTML "      <tr align=""left"">"
    MontaRadioNS "Padrão?", w_padrao, "w_padrao"
    ShowHTML "      </tr>"
    ShowHTML "      <tr align=""left"">"
    MontaRadioSN "Ativo?", w_ativo, "w_ativo"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    If O = "E" Then
       ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Gravar"">"
    End If
    ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_codigo=" & p_codigo & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & "';"" name=""Botao"" value=""Cancelar"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  ElseIf Instr("P",O) > 0 Then
    AbreForm "Form", w_Pagina&par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
    ShowHTML "    <table width=""100%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>C</U>ódigo:<br><INPUT ACCESSKEY=""C"" " & w_Disabled & " class=""sti"" type=""text"" name=""p_codigo"" size=""3"" maxlength=""3"" value=""" & p_codigo & """></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""sti"" type=""text"" name=""p_nome"" size=""50"" maxlength=""50"" value=""" & p_nome & """></td>"
    ShowHTML "      <tr align=""left"">"
    MontaRadioSN "Ativo?", p_ativo, "p_ativo"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY=""O"" " & w_Disabled & " class=""sts"" name=""p_ordena"" size=""1"">"
    If p_Ordena="NOME" Then
       ShowHTML "          <option value="""">Chave<option value=""nome"" SELECTED>Nome<option value=""codigo"">Código<option value=""ativo"">Ativo"
    ElseIf p_Ordena="CODIGO" Then
       ShowHTML "          <option value="""">Chave<option value=""nome"">Nome<option value=""codigo"" SELECTED>Código<option value=""ativo"">Ativo"
    ElseIf p_Ordena="ATIVO" Then
       ShowHTML "          <option value="""">Chave<option value=""nome"">Nome<option value=""codigo"">Código<option value=""ativo"" SELECTED>Ativo"
    Else
       ShowHTML "          <option value="""" SELECTED>Chave<option value=""nome"">Nome<option value=""codigo"">Código<option value=""ativo"">Ativo"
    End If
    ShowHTML "          </select></td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""sti"" type=""text"" name=""P4"" size=""4"" maxlength=""4"" value=""" & P4 & """></td>"
    ShowHTML "      </table>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
    ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"" name=""Botao"" value=""Remover filtro"">"
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
    Estrutura_Fecha
  Estrutura_Fecha
  Estrutura_Fecha
  Rodape

  Set w_sq_banco         = Nothing
  Set w_codigo           = Nothing
  Set w_nome             = Nothing
  Set w_padrao           = Nothing
  Set w_ativo            = Nothing
  Set p_nome             = Nothing
  Set p_codigo           = Nothing
  Set p_ativo            = Nothing
  Set p_ordena           = Nothing

End Sub
REM =========================================================================
REM Fim da tabela de bancos
REM -------------------------------------------------------------------------

REM =========================================================================
REM Procedimento que executa as operações de BD
REM -------------------------------------------------------------------------
Public Sub Grava

  Dim p_codigo
  Dim p_sq_banco
  Dim p_padrao
  Dim p_nome
  Dim p_ativo
  Dim p_ordena
  Dim w_Null

  Cabecalho
  ShowHTML "</HEAD>"
  BodyOpen "onLoad=document.focus();"
  
  AbreSessao    
  Select Case SG
    Case "CT_CC"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          DML_CTCC O, _
                   Request("w_sq_cc"),     Request("w_sq_cc_pai"), w_cliente,            Request("w_nome"), _
                   Request("w_descricao"), Request("w_sigla"),     Request("w_receita"), Request("w_regular"), _
                   Request("w_ativo")
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_codigo=" & p_codigo & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "COBANCO"
       p_nome            = uCase(Request("p_nome"))
       p_codigo          = uCase(Request("p_codigo"))
       p_ativo           = uCase(Request("p_ativo"))
       p_ordena          = uCase(Request("p_ordena"))
  
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          DML_COBANCO O, _
                   Request("w_sq_banco"),  Request("w_nome"), Request("w_codigo"), _
                   Request("w_padrao"),    Request("w_ativo")
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_codigo=" & p_codigo & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "COAGENCIA"
       p_nome            = uCase(Request("p_nome"))
       p_sq_banco        = uCase(Request("p_sq_banco"))
       p_ativo           = uCase(Request("p_ativo"))
       p_ordena          = uCase(Request("p_ordena"))
  
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          DML_COAGENCIA O, _
                   Request("w_sq_agencia"),  Request("w_sq_banco"),  Request("w_nome"), _
                   Request("w_codigo"),      Request("w_padrao"),    Request("w_ativo")
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_sq_banco=" & p_sq_banco & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
  End Select

  Set p_sq_banco        = Nothing
  Set p_codigo          = Nothing
  Set p_padrao          = Nothing
  Set p_nome            = Nothing
  Set p_ativo           = Nothing
  Set p_ordena          = Nothing
  Set w_Null            = Nothing
End Sub
REM -------------------------------------------------------------------------
REM Fim do procedimento que executa as operações de BD
REM =========================================================================

REM =========================================================================
REM Rotina principal
REM -------------------------------------------------------------------------
Sub Main
  ' Verifica se o usuário tem lotação e localização
  Select Case Par
    Case "CENTROCUSTO"
       CentroCusto
    Case "AGENCIA"
       Agencia
    Case "BANCO"
       Banco
    Case "GRAVA"
       Grava
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

