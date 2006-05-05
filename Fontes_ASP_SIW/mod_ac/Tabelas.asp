<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Geral.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Cliente.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Seguranca.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Tabelas.asp" -->
<!-- #INCLUDE FILE="DML_Tabelas.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /Tabelas.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Gerenciar tabelas básicas do módulo	
REM Mail     : Beto@sbpi.com.br
REM Criacao  : 23/01/2005 11:00
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
Dim dbms, sp, RS, RS1, RS2, RS3, RS4, RS_menu
Dim P1, P2, P3, P4, TP, SG
Dim R, O, w_Cont, w_Reg, w_Pagina, w_Disabled, w_TP, w_classe, w_submenu, w_filtro, w_copia
Dim w_Assinatura
Dim w_troca,w_cor, w_filter, w_cliente, w_usuario, w_menu, w_dir, w_chave, w_dir_volta
Dim w_sq_pessoa
Dim ul,File
Dim w_pag, w_linha
Set RS  = Server.CreateObject("ADODB.RecordSet")
Set RS1 = Server.CreateObject("ADODB.RecordSet")
Set RS2 = Server.CreateObject("ADODB.RecordSet")
Set RS3 = Server.CreateObject("ADODB.RecordSet")
Set RS4 = Server.CreateObject("ADODB.RecordSet")
Set RS_Menu = Server.CreateObject("ADODB.RecordSet")

w_troca            = Request("w_troca")
w_copia            = Request("w_copia")
  
Private Par

AbreSessao

' Carrega variáveis locais com os dados dos parâmetros recebidos
Par          = ucase(Request("Par"))
P1           = Nvl(Request("P1"),0)
P2           = Nvl(Request("P2"),0)
P3           = cDbl(Nvl(Request("P3"),1))
P4           = cDbl(Nvl(Request("P4"),conPagesize))
TP           = Request("TP")
SG           = ucase(Request("SG"))
R            = uCase(Request("R"))
O            = uCase(Request("O"))
w_Assinatura = uCase(Request("w_Assinatura"))

w_Pagina     = "Tabelas.asp?par="
w_Dir        = "mod_ac/"
w_dir_volta  = "../"  
w_Disabled   = "ENABLED"

If O = "" Then 
   If par ="REL_PPA" _
      or par = "REL_INICIATIVA" _ 
      or par = "REL_SINTETICO_IP" _
      or par = "REL_SINTETICO_PPA" Then
      O = "P"
   Else 
      O = "L"
   End If
End If

Select Case O
  Case "I" 
     w_TP = TP & " - Inclusão"
  Case "A" 
     w_TP = TP & " - Alteração"
  Case "E" 
     w_TP = TP & " - Exclusão"
  Case "P" 
     w_TP = TP & " - Filtragem"
  Case "C"
     w_TP = TP & " - Cópia"
  Case "V" 
     w_TP = TP & " - Envio"
  Case "H" 
     w_TP = TP & " - Herança"
  Case Else
     w_TP = TP & " - Listagem"
End Select

w_cliente         = RetornaCliente()
w_usuario         = RetornaUsuario()
w_menu            = RetornaMenu(w_cliente, SG)

' Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
DB_GetLinkSubMenu RS, Session("p_cliente"), SG
If RS.RecordCount > 0 Then
   w_submenu = "Existe"
Else
   w_submenu = ""
End If
DesconectaBD

' Recupera a configuração do serviço
If P2 > 0 Then DB_GetMenuData RS_menu, P2 Else DB_GetMenuData RS_menu, w_menu End If
If RS_menu("ultimo_nivel") = "S" Then
   ' Se for sub-menu, pega a configuração do pai
   DB_GetMenuData RS_menu, RS_menu("sq_menu_pai")
End If

Main

FechaSessao

Set w_chave       = Nothing
Set w_copia       = Nothing
Set w_filtro      = Nothing
Set w_menu        = Nothing
Set w_usuario     = Nothing
Set w_cliente     = Nothing
Set w_filter      = Nothing
Set w_cor         = Nothing
Set ul            = Nothing
Set File          = Nothing
Set w_sq_pessoa   = Nothing
Set w_troca       = Nothing
Set w_submenu     = Nothing
Set w_reg         = Nothing

Set RS            = Nothing
Set RS1           = Nothing
Set RS2           = Nothing
Set RS3           = Nothing
Set RS4           = Nothing
Set RS_menu       = Nothing
Set Par           = Nothing
Set P1            = Nothing
Set P2            = Nothing
Set P3            = Nothing
Set P4            = Nothing
Set TP            = Nothing
Set SG            = Nothing
Set R             = Nothing
Set O             = Nothing
Set w_Classe      = Nothing
Set w_Cont        = Nothing
Set w_Pagina      = Nothing
Set w_Disabled    = Nothing
Set w_TP          = Nothing
Set w_Assinatura  = Nothing
Set w_dir         = Nothing
Set w_dir_volta   = Nothing

REM =========================================================================
REM Rotina de tipos de acordo
REM -------------------------------------------------------------------------
Sub TipoAcordo
  Dim w_texto
  Dim w_troca
  Dim w_ContOut
  Dim w_Titulo
  Dim w_Imagem
  Dim w_marcado
  
  Dim w_sq_tipo_acordo, w_sq_tipo_acordo_pai, w_nome, w_sigla, w_ativo
  Dim w_pessoa_fisica, w_pessoa_juridica
  Dim w_modalidade, w_prazo_indeterminado
  Dim w_heranca, RS1, RS2, RS3
  
  w_Imagem          = "images/folder/SheetLittle.gif"
  w_troca           = Request("w_troca")
  w_heranca         = Request("w_heranca")
  w_sq_tipo_acordo  = Request("w_sq_tipo_acordo")
  
  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>" & conSgSistema & " - Tipos de Acordo</TITLE>"
  If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & replace(MontaURL("MESA"),w_dir,"") & """>" End If
  Estrutura_CSS w_cliente

  If O = "" Then O="L" End If  
  If O <> "L" Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     If O <> "P" and O <> "H" Then
        If w_heranca > "" or (O <> "I" and w_troca = "") Then
        
           ' Se for herança, atribui a chave da opção selecionada para w_sq_tipo_acordo
           If w_heranca > "" Then w_sq_tipo_acordo = w_heranca End If
           DB_GetAgreeType RS, w_sq_tipo_acordo, null, w_cliente, "ALTERA"
           w_sq_tipo_acordo_pai       = RS("sq_tipo_acordo_pai")
           w_nome                     = RS("nome")
           w_sigla                    = RS("sigla")
           w_ativo                    = RS("ativo")
           w_pessoa_juridica          = RS("pessoa_juridica")
           w_pessoa_fisica            = RS("pessoa_fisica")
           w_prazo_indeterminado      = RS("prazo_indeterm")
           w_modalidade               = RS("modalidade")
           DesconectaBD
        ElseIf O = "A" Then
           DB_GetAgreeType RS, w_sq_tipo_acordo, null, w_cliente, "ALTERA"
           w_sq_tipo_acordo_pai       = RS("sq_tipo_acordo_pai")
           w_nome                     = RS("nome")
           w_sigla                    = RS("sigla")
           w_ativo                    = RS("ativo")
           w_pessoa_juridica          = RS("pessoa_juridica")
           w_pessoa_fisica            = RS("pessoa_fisica")
           w_prazo_indeterminado      = RS("prazo_indeterm")
           w_modalidade               = RS("modalidade")
           DesconectaBD
        ElseIf w_troca > "" Then
           w_sq_tipo_acordo_pai       = Request("w_sq_tipo_acordo_pai")
           w_nome                     = Request("w_nome")
           w_pessoa_fisica            = Request("w_pessoa_fisica")
           w_cliente                  = Request("w_cliente")
           w_sigla                    = Request("w_sigla")
           w_ativo                    = Request("w_ativo")
           w_pessoa_fisica            = Request("w_pessoa_fisica")
           w_prazo_indeterminado      = Request("w_prazo_indeterminado")
           w_modalidade               = Request("w_modalidade")
        End If
        If O = "I" or O = "A" Then
           Validate "w_nome", "Nome", "1", "1", "5", "60", "1", "1"
           Validate "w_sigla", "Sigla", "1", "1", "2", "10", "1", "1"
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
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
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
   Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
     ShowHTML "      <tr><td><font size=""2""><a accesskey=""I"" class=""ss"" href=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=I&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
     ShowHTML "      <tr><td height=""1"" bgcolor=""#000000"">"
     ShowHTML "      <tr><td><font size=2><b>"
     DB_GetAgreeType RS, null, null, w_cliente, "PAI"
     w_ContOut = 0
     While Not RS.EOF
        w_Titulo = RS("sigla")
        w_ContOut = w_ContOut + 1
        If cDbl(RS("Filho")) > 0 Then
           ShowHTML "<A HREF=#""" & RS("sq_tipo_acordo") & """></A>"
           ShowHTML "<font size=2><span><div align=""left""><img src=""images/folder/FolderClose.gif"" border=0 align=""center""> " & RS("nome") & "<font size=1>"
           If RS("ativo") = "S" Then w_classe="HL" Else w_classe="LH" End If
           ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_tipo_acordo=" & RS("sq_tipo_acordo") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Altera as informações deste tipo de acordos"">Alterar</A>&nbsp"
           If RS("ativo") = "S" Then
              ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=D&w_sq_tipo_acordo=" & RS("sq_tipo_acordo") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Impede que este tipo de acordos seja associado a novos registros"">Desativar</A>&nbsp"
           Else
              ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=T&w_sq_tipo_acordo=" & RS("sq_tipo_acordo") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Permite que este tipo de acordos seja associado a novos registros"">Ativar</A>&nbsp"
           End If
           ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_tipo_acordo=" & RS("sq_tipo_acordo") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Exclui o tipo de acordos"">Excluir</A>&nbsp"
           ShowHTML "       </div></span></font></font>"
           ShowHTML "   <div style=""position:relative; left:12;""><font size=1>"
           DB_GetAgreeType RS1, RS("sq_tipo_acordo"), null, w_cliente, "FILHO"
           While Not RS1.EOF
              w_Titulo = w_Titulo & " - " & RS1("nome")
              If cDbl(RS1("Filho")) > 0 Then
                 w_ContOut = w_ContOut + 1
                 ShowHTML "<A HREF=#""" & RS1("sq_tipo_acordo") & """></A>"
                 ShowHTML "<font size=1><span><div align=""left""><img src=""images/folder/FolderClose.gif"" border=0 align=""center""> " & RS1("nome") & "<font size=1>"
                 If RS1("ativo") = "S" Then w_classe="HL" Else w_classe="LH" End If
                 ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_tipo_acordo=" & RS1("sq_tipo_acordo") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Altera as informações deste tipo de acordos"">Alterar</A>&nbsp"
                 If RS1("ativo") = "S" Then
                    ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=D&w_sq_tipo_acordo=" & RS1("sq_tipo_acordo") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Impede que este tipo de acordos seja associado a novos registros"">Desativar</A>&nbsp"
                 Else
                    ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=T&w_sq_tipo_acordo=" & RS1("sq_tipo_acordo") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Permite que este tipo de acordos seja associado a novos registros"">Ativar</A>&nbsp"
                 End If
                 ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_tipo_acordo=" & RS1("sq_tipo_acordo") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Exclui o tipo de acordos"">Excluir</A>&nbsp"
                 ShowHTML "       </div></span></font></font>"
                 ShowHTML "   <div style=""position:relative; left:12;""><font size=1>"
                 DB_GetAgreeType RS2, RS1("sq_tipo_acordo"), null, w_cliente, "FILHO"
                 While Not RS2.EOF
                    w_Titulo = w_Titulo & " - " & RS2("nome")
                    If cDbl(RS2("Filho")) > 0 Then
                       w_ContOut = w_ContOut + 1
                       ShowHTML "<A HREF=#""" & RS2("sq_tipo_acordo") & """></A>"
                       ShowHTML "<font size=1><span><div align=""left""><img src=""images/folder/FolderClose.gif"" border=0 align=""center""> " & RS2("nome") & "<font size=1>"
                       If RS2("ativo") = "S" Then w_classe="HL" Else w_classe="LH" End If
                       ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_tipo_acordo=" & RS2("sq_tipo_acordo") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Altera as informações deste tipo de acordos"">Alterar</A>&nbsp"
                       If RS2("ativo") = "S" Then
                          ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=D&w_sq_tipo_acordo=" & RS2("sq_tipo_acordo") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Impede que este tipo de acordos seja associado a novos registros"">Desativar</A>&nbsp"
                       Else
                          ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=T&w_sq_tipo_acordo=" & RS2("sq_tipo_acordo") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Permite que este tipo de acordos seja associado a novos registros"">Ativar</A>&nbsp"
                       End If
                       ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_tipo_acordo=" & RS2("sq_tipo_acordo") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Exclui o tipo de acordos"">Excluir</A>&nbsp"
                       ShowHTML "       </div></span></font></font>"
                       ShowHTML "   <div style=""position:relative; left:12;""><font size=1>"
                       DB_GetAgreeType RS3, RS2("sq_tipo_acordo"), null, w_cliente, "FILHO"
                       While Not RS3.EOF
                          w_Titulo = w_Titulo & " - " & RS3("nome")
                          ShowHTML "<A HREF=#""" & RS3("sq_tipo_acordo") & """></A>"
                          ShowHTML "    <img src=""" & w_Imagem & """ border=0 align=""center""> " & RS3("nome")
                          If RS3("ativo") = "S" Then w_classe="HL" Else w_classe="LH" End If
                          ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_tipo_acordo=" & RS3("sq_tipo_acordo") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Altera as informações deste tipo de acordos"">Alterar</A>&nbsp"
                          If RS3("ativo") = "S" Then
                             ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=D&w_sq_tipo_acordo=" & RS3("sq_tipo_acordo") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Impede que este tipo de acordos seja associado a novos registros"">Desativar</A>&nbsp"
                          Else
                             ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=T&w_sq_tipo_acordo=" & RS3("sq_tipo_acordo") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Permite que este tipo de acordos seja associado a novos registros"">Desativar</A>&nbsp"
                          End If
                          ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_tipo_acordo=" & RS3("sq_tipo_acordo") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Exclui o tipo de acordos"">Excluir</A>&nbsp"
                          ShowHTML "    <BR>"
                          w_Titulo = Replace(w_Titulo, " - "&RS3("nome"), "")
                          RS3.MoveNext
                       Wend
                       ShowHTML "   </div></font>"
                    Else
                       ShowHTML "    <img src=""" & w_Imagem & """ border=0 align=""center""> " & RS2("nome")
                       If RS2("ativo") = "S" Then w_classe="HL" Else w_classe="LH" End If
                       ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_tipo_acordo=" & RS2("sq_tipo_acordo") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Altera as informações deste tipo de acordos"">Alterar</A>&nbsp"
                       If RS2("ativo") = "S" Then
                          ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=D&w_sq_tipo_acordo=" & RS2("sq_tipo_acordo") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Impede que este tipo de acordos seja associado a novos registros"">Desativar</A>&nbsp"
                       Else
                          ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=T&w_sq_tipo_acordo=" & RS2("sq_tipo_acordo") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Permite que este tipo de acordos seja associado a novos registros"">Ativar</A>&nbsp"
                       End If
                       ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_tipo_acordo=" & RS2("sq_tipo_acordo") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Exclui o tipo de acordos"">Excluir</A>&nbsp"
                       ShowHTML "    <BR>"
                    End If
                    w_Titulo = Replace(w_Titulo, " - "&RS2("nome"), "")
                    RS2.MoveNext
                 Wend
                 ShowHTML "   </div></font>"
               Else
                 ShowHTML "    <img src=""" & w_Imagem & """ border=0 align=""center""> " & RS1("nome")
                 If RS1("ativo") = "S" Then w_classe="HL" Else w_classe="LH" End If
                 ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_tipo_acordo=" & RS1("sq_tipo_acordo") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Altera as informações deste tipo de acordos"">Alterar</A>&nbsp"
                 If RS1("ativo") = "S" Then
                    ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=D&w_sq_tipo_acordo=" & RS1("sq_tipo_acordo") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Impede que este tipo de acordos seja associado a novos registros"">Desativar</A>&nbsp"
                 Else
                    ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=T&w_sq_tipo_acordo=" & RS1("sq_tipo_acordo") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Permite que este tipo de acordos seja associado a novos registros"">Ativar</A>&nbsp"
                 End If
                 ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_tipo_acordo=" & RS1("sq_tipo_acordo") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Exclui o tipo de acordos"">Excluir</A>&nbsp"
                 ShowHTML "    <BR>"
              End If
              w_Titulo = Replace(w_Titulo, " - "&RS1("nome"), "")
              RS1.MoveNext
           Wend
           ShowHTML "   </div></font>"
        Else
           ShowHTML "    <img src=""" & w_Imagem & """ border=0 align=""center""><font size=2> " & RS("nome") & "<font size=1>"
           If RS("ativo") = "S" Then w_classe="HL" Else w_classe="LH" End If
           ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_tipo_acordo=" & RS("sq_tipo_acordo") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Altera as informações deste tipo de acordos"">Alterar</A>&nbsp"
           If RS("ativo") = "S" Then
              ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=D&w_sq_tipo_acordo=" & RS("sq_tipo_acordo") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Impede que este tipo de acordos seja associado a novos registros"">Desativar</A>&nbsp"
           Else
              ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=T&w_sq_tipo_acordo=" & RS("sq_tipo_acordo") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Permite que este tipo de acordos seja associado a novos registros"">Ativar</A>&nbsp"
           End If
           ShowHTML "       <A class=""" & w_classe & """ HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_tipo_acordo=" & RS("sq_tipo_acordo") & "&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Exclui o tipo de acordos"">Excluir</A>&nbsp"
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
        ShowHTML "      <tr><td><font size=""2""><a accesskey=""H"" class=""ss"" href=""#"" onClick=""window.open('" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=H&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_sq_tipo_acordo="&w_sq_tipo_acordo&"','heranca','top=70 left=100 width=500 height=200 toolbar=no status=yes');""><u>H</u>erdar dados</a>&nbsp;"
        ShowHTML "      <tr><td height=""1"" bgcolor=""#000000"">"
     End If
     AbreForm "Form", w_dir&w_Pagina&"Grava", "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,w_pagina & par,O
     ShowHTML "<INPUT type=""hidden"" name=""w_cliente"" value=""" & w_cliente & """>"
     ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
     ShowHTML "<INPUT type=""hidden"" name=""w_sq_tipo_acordo"" value=""" & w_sq_tipo_acordo & """>"
     ShowHTML "      <tr><td valign=""top""><table width=""100%"" border=0><tr valign=""top"">"
     ShowHTML "          <td><font size=""1""><b><u>N</u>ome:<br><INPUT ACCESSKEY=""D"" TYPE=""TEXT"" class=""sti"" NAME=""w_nome"" SIZE=40 MAXLENGTH=60 VALUE=""" & w_nome & """ " & w_Disabled & " TITLE=""Nome do tipo de acordo.""></td>"
     SelecaoTipoAcordo "<u>S</u>ubordinação:", "S", "Se esta opção estiver subordinada a outra já existente, informe qual.", w_sq_tipo_acordo_pai, w_sq_tipo_acordo, w_cliente, "w_sq_tipo_acordo_pai", "SUBORDINACAO", null
     ShowHTML "          </table>"
     ShowHTML "      <tr><td valign=""top""><table width=""100%"" border=0><tr valign=""top"">"
     ShowHTML "          <td valign=""top""><font size=""1""><b>S<u>i</u>gla:<br><INPUT ACCESSKEY=""S"" TYPE=""TEXT"" class=""sti"" NAME=""w_sigla"" SIZE=10 MAXLENGTH=10 VALUE=""" & w_sigla & """ " & w_Disabled & " TITLE=""Informe a sigla desejada para o tipo de acordo.""></td>"
     ShowHTML "          <td valign=""top"" TITLE=""Informe \'Sim\' se este tipo de acordo aplicar-se a pessoas fisicas.""><font size=""1""><b>Pessoa física?</b><br>"
     If w_pessoa_fisica = "S" or w_pessoa_fisica = "" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_pessoa_fisica"" value=""S"" checked> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_pessoa_fisica"" value=""N""> Não"
     Else
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_pessoa_fisica"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_pessoa_fisica"" value=""N"" checked> Não"    
     End If
     ShowHTML "          <td valign=""top"" TITLE=""Informe \'Sim\' se este tipo de acordo aplicar-se a pessoas jurídicas.""><font size=""1""><b>Pessoa jurídica?</b><br>"
     If w_pessoa_juridica = "S" or w_pessoa_juridica = "" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_pessoa_juridica"" value=""S"" checked> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_pessoa_juridica"" value=""N""> Não"
     Else
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_pessoa_juridica"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_pessoa_juridica"" value=""N"" checked> Não"    
     End If
     ShowHTML "      <tr valign=""top"">"
     ShowHTML "          <td valign=""top"" TITLE=""Selecione a modalidade deste tipo de acordo dentre as apresentadas.""><font size=""1""><b>Modalidade:</b><br>"
     If w_modalidade = "Q" or w_modalidade = "" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_modalidade"" value=""Q"" checked> Aquisição<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_modalidade"" value=""A""> Arrendamento<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_modalidade"" value=""E""> Emprego<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_modalidade"" value=""F""> Fornecimento<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_modalidade"" value=""I""> Parceria institucional<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_modalidade"" value=""P""> Permissão"
     ElseIf w_modalidade = "A" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_modalidade"" value=""Q""> Aquisição<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_modalidade"" value=""A"" checked> Arrendamento<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_modalidade"" value=""E""> Emprego<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_modalidade"" value=""F""> Fornecimento<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_modalidade"" value=""I""> Parceria institucional<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_modalidade"" value=""P""> Permissão"
     ElseIf w_modalidade = "E" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_modalidade"" value=""Q""> Aquisição<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_modalidade"" value=""A""> Arrendamento<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_modalidade"" value=""E"" checked> Emprego<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_modalidade"" value=""F""> Fornecimento<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_modalidade"" value=""I""> Parceria institucional<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_modalidade"" value=""P""> Permissão"
     ElseIf w_modalidade = "F" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_modalidade"" value=""Q""> Aquisição<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_modalidade"" value=""A""> Arrendamento<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_modalidade"" value=""E""> Emprego<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_modalidade"" value=""F"" checked> Fornecimento<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_modalidade"" value=""I""> Parceria institucional<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_modalidade"" value=""P""> Permissão"
     ElseIf w_modalidade = "P" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_modalidade"" value=""Q""> Aquisição<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_modalidade"" value=""A""> Arrendamento<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_modalidade"" value=""E""> Emprego<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_modalidade"" value=""F""> Fornecimento<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_modalidade"" value=""I""> Parceria institucional<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_modalidade"" value=""P"" checked> Permissão"
     Else
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_modalidade"" value=""Q""> Aquisição<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_modalidade"" value=""A""> Arrendamento<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_modalidade"" value=""E""> Emprego<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_modalidade"" value=""F""> Fornecimento<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_modalidade"" value=""I"" checked> Parceria institucional<br><input " & w_Disabled & " class=""str"" type=""radio"" name=""w_modalidade"" value=""P""> Permissão"
     End If
     ShowHTML "          <td valign=""top"" TITLE=""Informe \'Sim\' se este tipo de acordo tiver prazo indeterminado.""><font size=""1""><b>Prazo indeterminado?</b><br>"
     If w_prazo_indeterminado = "S" or w_prazo_indeterminado = "" Then
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_prazo_indeterminado"" value=""S"" checked> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_prazo_indeterminado"" value=""N""> Não"
     Else
        ShowHTML "                 <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_prazo_indeterminado"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_prazo_indeterminado"" value=""N"" checked> Não"    
     End If

     If O = "I" Then
        ShowHTML "          <tr><td height=""30""><font size=""1""><b>Ativo?</b><br>"
        If w_ativo = "S" Then
           ShowHTML "              <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_ativo"" value=""S"" checked> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_ativo"" value=""N""> Não"
        Else
           ShowHTML "              <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_ativo"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""w_ativo"" value=""N"" checked> Não"    
        End If
     End If
     
     ShowHTML "      </td></tr>"
     ShowHTML "      <tr><td><font size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
     ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
     ShowHTML "      <tr><td colspan=""3""><input class=""stb"" type=""submit"" name=""Botao"" value=""Gravar"">&nbsp;"
     ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & R & "&O=L&w_cliente=" & w_cliente & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"" name=""Botao"" value=""Cancelar"">"
     ShowHTML "</FORM>"
     ShowHTML "  </table>"
  ElseIf O = "H" Then
    AbreForm "Form", R, "POST", "return(Validacao(this));", "content", P1,P2,P3,P4,TP,SG,R,"I"
    ShowHTML "<INPUT type=""hidden"" name=""w_cliente"" value=""" & w_cliente & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_tipo_acordo"" value=""" & w_sq_tipo_acordo & """>"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Selecione, na relação, a opção a ser utilizada como origem de dados.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td width=""100%"" align=""center"">"
    ShowHTML "    <table align=""center"" border=""0"">"
    ShowHTML "      <tr><td valign=""top""><table border=0 cellspacing=0 cellpadding=0>"
    ShowHTML "      <tr>"
    SelecaoTipoAcordo "<u>O</u>rigem:", "O", "Selecione na lista o tipo de acordo que deseja herdar.", w_heranca, null, w_cliente, "w_heranca", "HERANCA", null
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
    Estrutura_Fecha
  Estrutura_Fecha
  Estrutura_Fecha
  Rodape

  Set RS1                       = Nothing 
  Set RS2                       = Nothing 
  Set RS3                       = Nothing 
  Set w_heranca                 = Nothing 
  Set w_sq_tipo_acordo          = Nothing 
  Set w_sq_tipo_acordo_pai      = Nothing 
  Set w_nome                    = Nothing
  Set w_sigla                   = Nothing 
  Set w_modalidade              = Nothing
  Set w_prazo_indeterminado     = Nothing 
  Set w_pessoa_fisica           = Nothing 
  Set w_pessoa_juridica         = Nothing 
  Set w_ativo                   = Nothing 
  Set w_Imagem                  = Nothing
  Set w_Titulo                  = Nothing
  Set w_ContOut                 = Nothing
  Set w_troca                   = Nothing
  Set w_texto                   = Nothing
End Sub
REM =========================================================================
REM Fim da rotina de tipos de acordo
REM -------------------------------------------------------------------------

REM =========================================================================
REM Procedimento que executa as operações de BD
REM -------------------------------------------------------------------------
Public Sub Grava
  Dim p_sq_endereco_unidade
  Dim p_modulo
  Dim w_Null
  Dim w_mensagem
  Dim FS, F1
  Dim w_chave_nova

  Cabecalho
  ShowHTML "</HEAD>"
  BodyOpen "onLoad=document.focus();"
  
  AbreSessao    
  Select Case SG
    Case "TIPOACORDO"
     ' Verifica se a Assinatura Eletrônica é válida
     If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then

        DML_PutAgreeType O, _
           Request("w_sq_tipo_acordo"),       Request("w_sq_tipo_acordo_pai"), Request("w_cliente"), _
           Request("w_Nome"),                 Request("w_sigla"),              Request("w_modalidade"), _
           Request("w_prazo_indeterminado"),  Request("w_pessoa_juridica"),    Request("w_pessoa_fisica"), _
           Request("w_ativo")

        ScriptOpen "JavaScript"
        ShowHTML "  location.href='" & R & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
        ScriptClose
     Else
        ScriptOpen "JavaScript"
        ShowHTML "  alert('Assinatura Eletrônica inválida!');"
        ShowHTML "  history.back(1);"
        ScriptClose
     End If
    Case Else
       ScriptOpen "JavaScript"
       ShowHTML "  alert('Bloco de dados não encontrado: " & SG & "');"
       ShowHTML "  history.back(1);"
       ScriptClose
  End Select

  Set w_chave_nova          = Nothing
  Set FS                    = Nothing
  Set w_Mensagem            = Nothing
  Set p_sq_endereco_unidade = Nothing
  Set p_modulo              = Nothing
  Set w_Null                = Nothing
End Sub
REM -------------------------------------------------------------------------
REM Fim do procedimento que executa as operações de BD
REM =========================================================================


REM =========================================================================
REM Gera uma linha de apresentação da tabela de etapas
REM -------------------------------------------------------------------------
Function EtapaLinha (p_chave, p_chave_aux, p_titulo, p_word, p_programada, _
                     p_unidade_medida, p_quantidade, p_fim, p_perc, p_oper, p_tipo)
  Dim l_html, RsQuery, l_recurso, l_row

  If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
  l_html = l_html & VbCrLf & "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
  l_html = l_html & VbCrLf & "        <td nowrap " & l_row & "><font size=""1"">"
  If p_fim < Date() and cDbl(p_perc) < 100 Then
     l_html = l_html & VbCrLf & "           <img src=""" & conImgAtraso & """ border=0 width=15 height=15 align=""center"">"
  ElseIf cDbl(p_perc) < 100 Then
     l_html = l_html & VbCrLf & "           <img src=""" & conImgNormal & """ border=0 width=15 height=15 align=""center"">"
  Else
     l_html = l_html & VbCrLf & "           <img src=""" & conImgOkNormal & """ border=0 width=15 height=15 align=""center"">"
  End IF
  If p_word <> "WORD" Then
     l_html = l_html & VbCrLf & "<A class=""hl"" HREF=""#"" onClick=""window.open('Projeto.asp?par=AtualizaEtapa&O=V&w_chave=" & p_chave & "&w_chave_aux=" & p_chave_aux & "&w_tipo=Volta&P1=10&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "','Meta','width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no'); return false;"" title=""Clique para exibir os dados!"">" & p_titulo & "</A>"
  Else
     l_html = l_html & VbCrLf & "        " & p_titulo & "</td>"
  End if
  l_html = l_html & VbCrLf & "        <td align=""center""><font size=""1"">" & RetornaSimNao(p_programada) & "</b>"
  l_html = l_html & VbCrLf & "        <td align=""center"" " & l_row & "><font size=""1"">" & FormataDataEdicao(p_fim) & "</td>"
  l_html = l_html & VbCrLf & "        <td nowrap " & l_row & "><font size=""1"">" & Nvl(p_unidade_medida, "---") & " </td>"
  l_html = l_html & VbCrLf & "        <td nowrap align=""right"" " & l_row & "><font size=""1"">" & p_quantidade & "</td>"
  l_html = l_html & VbCrLf & "        <td nowrap align=""right"" " & l_row & "><font size=""1"">" & p_perc & " %</td>"
  l_html = l_html & VbCrLf &  "      </tr>"

  EtapaLinha = l_html

  Set RsQuery   = Nothing
  Set l_row     = Nothing
  Set l_recurso = Nothing
  Set l_html    = Nothing
End Function
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina principal
REM -------------------------------------------------------------------------
Sub Main
  Select Case Par
    Case "TIPOACORDO"        TipoAcordo
    Case "REL_PPA"           Rel_PPA
    Case "REL_SINTETICO_PPA" Rel_Sintetico_PPA
    Case "GRAVA"             Grava
    Case Else
       Cabecalho
       ShowHTML "<HEAD>"
       ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & replace(MontaURL("MESA"),w_dir,"") & """>"
       Estrutura_CSS w_cliente
       ShowHTML "</HEAD>"

       BodyOpen "onLoad=document.focus();"
       ShowHTML "<center>"
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
    
    
    
'       Cabecalho
'       ShowHTML "<BASE HREF=""" & conRootSIW & """>"
'       BodyOpen "onLoad=document.focus();"
'       ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
'       ShowHTML "<HR>"
'       ShowHTML "<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src=""images/icone/underc.gif"" align=""center""> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>"
'       Rodape
  End Select
End Sub
REM =========================================================================
REM Fim da rotina principal
REM -------------------------------------------------------------------------
%>