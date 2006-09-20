<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Geral.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Tabela_SIW.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Cliente.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Seguranca.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes_Valida.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/mod_dc/Funcoes.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/mod_dc/DB_Dicionario.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/mod_dc/DB_Tabelas.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/cp_upload/_upload.asp" -->
<!-- #INCLUDE FILE="DB_SIGPLAN.asp" -->
<!-- #INCLUDE FILE="DML_SIGPLAN.asp" -->
<!-- #INCLUDE FILE="DML_SIAFI.asp" -->
<!-- #INCLUDE FILE="DML_XML.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Tabelas.asp" -->
<!-- #INCLUDE FILE="DML_Tabelas.asp" -->
<%
Server.ScriptTimeout = 3600
Response.Expires = -1500
REM =========================================================================
REM  /sigplan.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Rotinas de integração com o SIGPLAN/MP
REM Mail     : alex@sbpi.com.br
REM Criacao  : 15/04/2005, 15:46
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
Dim w_Assinatura, w_caminho
Dim w_troca,w_cor, w_filter, w_cliente, w_usuario, w_menu, w_dir, w_chave, w_dir_volta, UploadID
Dim w_sq_pessoa, w_ano, w_sq_modulo
Dim ul,File
Dim p_sq_modulo, p_nome, p_tipo, p_formato, p_dt_ini, p_dt_fim, p_ref_ini, p_ref_fim
Dim F1, w_erro, w_name(500), w_param(500), w_resultado, w_atributo(500)
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
Par           = ucase(Request("Par"))
w_Pagina      = "sigplan.asp?par="
w_Dir         = "mod_is/"
w_dir_volta   = "../"  
w_Disabled    = "ENABLED"

SG              = ucase(Request("SG"))
O               = uCase(Request("O"))
w_cliente       = RetornaCliente()
w_usuario       = RetornaUsuario()
w_menu          = RetornaMenu(w_cliente, SG)
w_ano           = RetornaAno()
DB_GetSiwCliModLis RS, w_cliente, null, "IS"
w_sq_modulo = RS("sq_modulo")
DesconectaBD

Set ul            = New ASPForm

If Request("UploadID") > "" Then
   UploadID = Request("UploadID")
Else
   UploadID = ul.NewUploadID
End If    

' Configura o caminho para gravação física de arquivos
w_caminho = conFilePhysical & w_cliente & "\"

If InStr(uCase(Request.ServerVariables("http_content_type")),"MULTIPART/FORM-DATA") > 0 Then
   Server.ScriptTimeout = 2000
   ul.SizeLimit = &HA00000
   If UploadID > 0 then
      ul.UploadID = UploadID
   End If

   w_troca          = ul.Texts.Item("w_troca")
   p_sq_modulo      = uCase(ul.Texts.Item("p_sq_modulo"))
   p_nome           = uCase(ul.Texts.Item("p_nome"))
   p_tipo           = uCase(ul.Texts.Item("p_tipo"))
   p_formato        = uCase(ul.Texts.Item("p_formato"))
   p_dt_ini         = ul.Texts.Item("p_dt_ini")
   p_dt_fim         = ul.Texts.Item("p_dt_fim")
   p_ref_ini        = ul.Texts.Item("p_ref_ini")
   p_ref_fim        = ul.Texts.Item("p_ref_fim")

   P1               = ul.Texts.Item("P1")
   P2               = ul.Texts.Item("P2")
   P3               = ul.Texts.Item("P3")
   P4               = ul.Texts.Item("P4")
   TP               = ul.Texts.Item("TP")
   R                = uCase(ul.Texts.Item("R"))
   w_Assinatura     = uCase(ul.Texts.Item("w_Assinatura"))
Else
   w_troca          = Request("w_troca")
   p_nome           = uCase(Request("p_nome"))
   p_tipo           = uCase(Request("p_tipo"))
   p_formato        = uCase(Request("p_formato"))
   p_sq_modulo      = uCase(Request("p_sq_modulo"))
   p_dt_ini         = Request("p_dt_ini")
   p_dt_fim         = Request("p_dt_fim")
   p_ref_ini        = Request("p_ref_ini")
   p_ref_fim        = Request("p_ref_fim")

   P1               = Nvl(Request("P1"),0)
   P2               = Nvl(Request("P2"),0)
   P3               = cDbl(Nvl(Request("P3"),1))
   P4               = cDbl(Nvl(Request("P4"),conPagesize))
   TP               = Request("TP")
   R                = uCase(Request("R"))
   w_Assinatura     = uCase(Request("w_Assinatura"))
End If

If O = "" Then 
   If par ="REL_PPA" or par = "REL_INICIATIVA" Then
      O = "P"
   Else 
      O = "L"
   End If
End If

If P1 = 1 Then p_tipo = "I" Else p_tipo = "E" End If

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
  Case "O" 
     w_TP = TP & " - Orientações"
  Case Else
     w_TP = TP & " - Listagem"
End Select

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

Set UploadID      = Nothing
Set F1            = Nothing
Set p_sq_modulo   = Nothing
Set p_nome        = Nothing
Set p_tipo        = Nothing
Set p_formato     = Nothing
Set p_dt_ini      = Nothing
Set p_dt_fim      = Nothing
Set p_ref_ini     = Nothing
Set p_ref_fim     = Nothing
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
Set w_caminho     = Nothing
Set w_sq_modulo   = Nothing

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
REM Rotina de importação de arquivos físicos para atualização de dados financeiros
REM -------------------------------------------------------------------------
Sub Inicial
  Dim w_sq_esquema, w_nome, w_descricao, w_tipo, w_ativo, w_formato, w_ws_servidor 
  Dim w_ws_url, w_ws_acao, w_ws_mensagem, w_no_raiz
  
  w_sq_esquema      = Request("w_sq_esquema")
  w_troca           = Request("w_troca")
  
  If w_troca > "" Then ' Se for recarga da página
     w_sq_esquema   = Request("w_sq_esquema") 
     w_nome         = Request("w_nome") 
     w_descricao    = Request("w_descricao") 
     w_tipo         = Request("w_tipo") 
     w_ativo        = Request("w_ativo") 
     w_formato      = Request("w_formato") 
     w_ws_servidor  = Request("w_ws_servidor")
     w_ws_url       = Request("w_ws_url") 
     w_ws_acao      = Request("w_ws_acao") 
     w_ws_mensagem  = Request("w_ws_mensagem") 
     w_no_raiz      = Request("w_no_raiz")
  ElseIf O = "L" Then
     ' Recupera todos os ws_url para a listagem
     DB_GetEsquema RS, w_cliente, null, null, w_sq_modulo, p_nome, p_tipo, p_formato, p_dt_ini, p_dt_fim, p_ref_ini, p_ref_fim
     RS.Sort = "nome"
  ElseIf Instr("AE",O) > 0 Then
     ' Recupera todos os ws_url para a listagem
     DB_GetEsquema RS, w_cliente, null, w_sq_esquema, null, null, null, null, null, null, null, null
     w_sq_esquema   = RS("sq_esquema") 
     w_nome         = RS("nome") 
     w_descricao    = RS("descricao") 
     w_tipo         = RS("tipo") 
     w_ativo        = RS("ativo") 
     w_formato      = RS("formato") 
     w_ws_servidor  = RS("ws_servidor")
     w_ws_url       = RS("ws_url") 
     w_ws_acao      = RS("ws_acao") 
     w_ws_mensagem  = RS("ws_mensagem") 
     w_no_raiz      = RS("no_raiz")
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     CheckBranco
     FormataData
     ValidateOpen "Validacao"
     If InStr("IAE",O) > 0 Then
        If InStr("IA",O) > 0 Then
           Validate "w_nome", "Nome", "1", "1", 3, 60, "1", "1"
           Validate "w_descricao", "Descricao", "1", "", 3, 500, "1", "1"
           Validate "w_no_raiz", "Nó raiz", "1", "1", 3, 50, "1", "1"
           If P1 = 2 Then
              Validate "w_formato", "Formato", "SELECT", 1, 1, 10, "1", "1"
           End If
           If w_formato = "W" Then
              Validate "w_ws_servidor", "Servidor", "1", "1", 3, 100, "1", "1"
              Validate "w_ws_url", "URL", "1", "1", 3, 100, "1", "1"
              Validate "w_ws_acao", "Ação", "1", "1", 3, 100, "1", "1"
             Validate "w_ws_mensagem", "Mensagem", "1", "1", 3, 4000, "1", "1"        
           End If
        End If
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf Instr("IA",O) > 0 Then
     BodyOpen "onLoad='document.Form.w_nome.focus()';"
  ElseIf Instr("E",O) > 0 Then
     BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
  Else
     BodyOpen ""
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    AbreSessao
    ' Exibe a quantidade de ws_url apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "<tr><td><font size=""1"">"
    ShowHTML "        <a accesskey=""I"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_sq_esquema=" & w_sq_esquema & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "        <a accesskey=""O"" class=""SS"" href=""" & w_dir & w_Pagina & "Help&R=" & w_Pagina & par & "&O=O&w_sq_esquema=" & w_sq_esquema & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ target=""help""><u>O</u>rientações</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td rowspan=2><font size=""1""><b>" & LinkOrdena("Nome", "nome") & "</font></td>"
    'ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Formato", "nm_formato") & "</font></td>"
    'ShowHTML "          <td rowspan=2><font size=""1""><b>" & LinkOrdena("Raiz", "no_raiz") & "</font></td>"
    'ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Ativo", "nm_ativo") & "</font></td>"
    'ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Tabelas", "qtd_tabela") & "</font></td>"
    ShowHTML "          <td colspan=2><font size=""1""><b>Data</font></td>"
    ShowHTML "          <td colspan=3><font size=""1""><b>Registros</font></td>"
    ShowHTML "          <td rowspan=2><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Ocorrência", "data_ocorrencia") & "</font></td>"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Referência", "data_referencia") & "</font></td>"
    ShowHTML "          <td><font size=""1""><b>Total</font></td>"
    ShowHTML "          <td><font size=""1""><b>Aceitos</font></td>"
    ShowHTML "          <td><font size=""1""><b>Rejeitados</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados ws_url, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os ws_url selecionados para listagem
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        If P1 = 1 Then
           ShowHTML "        <td><font size=""1"">" & RS("nome") & "</td>"
        Else
           ShowHTML "        <td><font size=""1"">" & LinkArquivo("HL", w_cliente, RS("nome") & ".xml", "_blank", "Exibe os dados do arquivo importado.", RS("nome"), null) & "&nbsp;</td>"
        End If
        'ShowHTML "        <td><font size=""1"">" & RS("nm_formato") & "</td>"
        'ShowHTML "        <td><font size=""1"">" & RS("no_raiz") & "</td>"
        'ShowHTML "        <td align=""center""><font size=""1"">" & RS("nm_ativo") & "</td>"
        'ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(RS("qtd_tabela"),0) & "</td>"
        If Nvl(RS("data_ocorrencia"),"") > "" Then
           ShowHTML "        <td align=""center""><font size=""1"">" & FormataDataEdicao(RS("data_ocorrencia")) & "</td>"
        Else
           ShowHTML "        <td align=""center""><font size=""1"">---</td>"
        End If
        If Nvl(RS("data_referencia"),"") > "" Then
           ShowHTML "        <td align=""center""><font size=""1"">" & Mid(FormataDataEdicao(RS("data_referencia")),1,len(FormataDataEdicao(RS("data_referencia")))-3) & "</td>"
        Else
           ShowHTML "        <td align=""center""><font size=""1"">---</td>"
        End If
        If cDbl(Nvl(RS("processados"),0)) > 0 Then
           ShowHTML "        <td align=""right""><font size=""1"">" & LinkArquivo("HL", w_cliente, RS("chave_recebido") , "_blank", "Exibe os dados do arquivo importado.", Nvl(RS("processados"),0), null) & "&nbsp;</td>"
        Else
           ShowHTML "        <td align=""right""><font size=""1"">" & Nvl(RS("processados"),0) & "&nbsp;</td>"
        End If
        ShowHTML "        <td align=""right""><font size=""1"">" & (cDbl(Nvl(RS("processados"),0)) - cDbl(Nvl(RS("rejeitados"),0))) & "&nbsp;</td>"
        If cDbl(Nvl(RS("rejeitados"),0)) > 0 Then
           ShowHTML "        <td align=""right""><font size=""1"">" & LinkArquivo("HL", w_cliente, RS("chave_result") , "_blank", "Exibe o registro da importação.", Nvl(RS("rejeitados"),0), null) & "&nbsp;</td>"
        Else
           ShowHTML "        <td align=""right""><font size=""1"">" & Nvl(RS("rejeitados"),0) & "&nbsp;</td>"
        End If
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_esquema=" & RS("sq_esquema") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Altera as informações do esquema"">Alterar</A>&nbsp"
        If Nvl(RS("sq_ocorrencia"),"") > "" Then
           ShowHTML "          <A class=""hl"" onClick=""alert('Este esquema possui ocorrências, para desabilita-lo, inative-o!');""title=""Exclui o esquema"">Excluir</A>&nbsp"
        Else
           ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_esquema=" & RS("sq_esquema") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exclui o esquema"">Excluir</A>&nbsp"
        End If
        ShowHTML "          <A class=""hl"" HREF=""javascript:location.href=this.location.href;"" onClick=""window.open('" & w_pagina & "Tabela&R=" & w_dir & w_Pagina & "Tabela&O=L&w_sq_esquema=" & RS("sq_esquema") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" &TP& " - Tabelas&SG=ISSIGTAB&w_menu=" & w_menu & MontaFiltro("GET") & "','Tabelas','toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes');"" title=""Relaciona as tabelas que compõem o esquema"">Tabelas</A>&nbsp" 
        'ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_pagina & "TABELA&R=" & w_Pagina & par & "&O=L&w_sq_esquema=" & RS("sq_esquema") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Relaciona as tabelas que compõem o esquema"">Tabelas</A>&nbsp"
        If cDbl(Nvl(RS("qtd_tabela"),0)) > 0 Then
           If P1 = 1 Then
              ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_pagina & "IMPORTACAO&R=" & w_Pagina & par & "&O=I&w_sq_esquema=" & RS("sq_esquema") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_menu=" & w_menu & MontaFiltro("GET") & """ title=""Importa a partir da definição do esquema"">Importar</A>&nbsp"
           Else
              ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_pagina & "EXPORTACAO&R=" & w_Pagina & par & "&O=I&w_sq_esquema=" & RS("sq_esquema") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_menu=" & w_menu & MontaFiltro("GET") & """ title=""Exporta a partir da definição do esquema"" onClick=""return(confirm('Confirma geração do arquivo de exportação?'))"">Exportar</A>&nbsp"
           End If
        End If
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    DesconectaBD
  ElseIf Instr("IAE",O) > 0 Then
    If InStr("E",O) Then w_Disabled = " DISABLED " End If
    AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,1,P4,TP,SG,R,O
    
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_esquema"" value=""" & w_sq_esquema & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_tipo"" value=""" & p_tipo & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & w_menu & """>"
    If P1 = 2 Then
       ShowHTML "<INPUT type=""hidden"" name=""w_formato"" value=""A"">"
    End If
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    
    ShowHTML "      <tr><td><font size=""1""><b><u>N</u>ome:<br><INPUT ACCESSKEY=""N"" TYPE=""TEXT"" CLASS=""sti"" NAME=""w_nome"" SIZE=60 MAXLENGTH=60 VALUE=""" & w_nome & """ " & w_Disabled & " title=""Nome do esquema.""></td>"
    ShowHTML "      <tr><td><font size=""1""><b><U>D</U>escrição:<br><TEXTAREA ACCESSKEY=""D"" class=""sti"" name=""w_descricao"" rows=3 cols=80 " & w_Disabled & " title=""Descreva sucintamente a finalidade deste esquema."">" & w_descricao & "</textarea></td>"
    ShowHTML "      <tr><td><table border=0 width=""100%"" cellpadding=0 cellspacing=0><tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Nó <u>r</u>aiz:<br><INPUT ACCESSKEY=""R"" TYPE=""TEXT"" CLASS=""sti"" NAME=""w_no_raiz"" SIZE=50 MAXLENGTH=50 VALUE=""" & w_no_raiz & """ " & w_Disabled & " title=""Informe o nome do nó raiz do documento XML.""></td>"
    If P1 = 1 Then
       SelecaoFormato "Formato", "F", null, w_formato, null, "w_formato", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_formato'; document.Form.submit();"""
    End If
    If not w_formato = "W" Then
       MontaRadioSN "<b>Ativo?</b>", w_ativo, "w_ativo"
       ShowHTML "              </table>"
    Else
       ShowHTML "              </table>"
       ShowHTML "      <tr><td><font size=""1""><b><u>S</u>ervidor:<br><INPUT ACCESSKEY=""S"" TYPE=""TEXT"" CLASS=""sti"" NAME=""w_ws_servidor"" SIZE=70 MAXLENGTH=100 VALUE=""" & w_ws_servidor & """ " & w_Disabled & " title=""Informe o nome do servidor onde o Web Service está instalado.""></td>"
       ShowHTML "      <tr><td><font size=""1""><b><u>U</u>RL:<br><INPUT ACCESSKEY=""U"" TYPE=""TEXT"" CLASS=""sti"" NAME=""w_ws_url"" SIZE=70 MAXLENGTH=100 VALUE=""" & w_ws_url & """ " & w_Disabled & " title=""Informe a URL para execução do Web Service.""></td>"
       ShowHTML "      <tr><td><font size=""1""><b>A<u>ç</u>ão:<br><INPUT ACCESSKEY=""C"" TYPE=""TEXT"" CLASS=""sti"" NAME=""w_ws_acao"" SIZE=70 MAXLENGTH=100 VALUE=""" & w_ws_acao & """ " & w_Disabled & " title=""Informe a ação que deseja executar no Web Service.""></td>"
       ShowHTML "      <tr><td><font size=""1""><b><U>M</U>ensagem:<br><TEXTAREA ACCESSKEY=""M"" class=""sti"" name=""w_ws_mensagem"" rows=10 cols=80 " & w_Disabled & " title=""Escreva o envelope da mensagem a ser enviada ao Web Service."">" & w_ws_mensagem & "</textarea></td>"
       ShowHTML "      <tr>"
       MontaRadioSN "<b>Ativo?</b>", w_ativo, "w_ativo"
    End If
    ShowHTML "      <tr><td align=""LEFT""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
    
    ShowHTML "      <tr><td align=""center""><hr>"
    If O = "E" Then
       ShowHTML "          <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       ShowHTML "          <input class=""STB"" type=""submit"" name=""Botao"" value=""Gravar"">"
    End If
    ShowHTML "          <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=L';"" name=""Botao"" value=""Cancelar"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_sq_esquema  = Nothing 
  Set w_nome        = Nothing 
  Set w_descricao   = Nothing 
  Set w_tipo        = Nothing 
  Set w_ativo       = Nothing 
  Set w_formato     = Nothing 
  Set w_ws_servidor = Nothing
  Set w_ws_url      = Nothing 
  Set w_ws_acao     = Nothing 
  Set w_ws_mensagem = Nothing 
  Set w_no_raiz     = Nothing
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de inclusão de tabelas no esquema
REM -------------------------------------------------------------------------
Sub Tabela
  Dim w_sq_esquema, w_sq_esquema_tabela, w_sq_tabela, w_ordem, w_elemento, w_atual
  Dim  p_nome, p_sq_sistema, p_sq_usuario, p_sq_tabela_tipo

  
  w_sq_esquema_tabela = Request("w_sq_esquema_tabela")
  w_sq_esquema        = Request("w_sq_esquema")
  w_troca             = Request("w_troca")
  p_nome              = Request("p_nome")
  p_sq_sistema        = Request("p_sq_sistema")
  p_sq_usuario        = Request("p_sq_usuario")
  p_sq_tabela_tipo    = Request("p_sq_tabela_tipo")
  If w_troca > "" Then ' Se for recarga da página
     w_ordem             = Request("w_ordem") 
     w_elemento          = Request("w_elemento") 
     p_nome              = Request("p_nome")
     p_sq_sistema        = Request("p_sq_sistema")
     p_sq_usuario        = Request("p_sq_usuario")
     p_sq_tabela_tipo    = Request("p_sq_tabela_tipo")
  ElseIf O = "L" Then
     ' Recupera todos os ws_url para a listagem
     DB_GetEsquemaTabela RS, null, w_sq_esquema, null
     RS.Sort = "ordem, nm_tabela, or_coluna"
  ElseIf Instr("I",O) > 0 Then
     DB_GetTabela RS, w_cliente, null, w_sq_esquema, p_sq_sistema, p_sq_usuario, p_sq_tabela_tipo, p_nome, SG
     RS.Sort = "sg_sistema, nm_usuario, nome"
  ElseIf Instr("A",O) > 0 Then
     ' Recupera todos os ws_url para a listagem
     DB_GetEsquemaTabela RS, null, w_sq_esquema, w_sq_esquema_tabela
     w_ordem               = RS("ordem") 
     w_elemento            = RS("elemento") 
  End If
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAPM",O) > 0 Then
     ScriptOpen "JavaScript"
     If InStr("I",O) > 0 Then
        ShowHTML "  function valor(p_indice) {"
        ShowHTML "    if (document.Form.w_sq_tabela(p_indice).checked) { "
        ShowHTML "       document.Form.w_ordem(p_indice).disabled=false; "
        ShowHTML "       document.Form.w_elemento(p_indice).disabled=false; "
        ShowHTML "       document.Form.w_elemento(p_indice).focus(); "
        ShowHTML "    } else {"
        ShowHTML "       document.Form.w_ordem(p_indice).disabled=true; "
        ShowHTML "       document.Form.w_elemento(p_indice).disabled=true; "
        ShowHTML "       document.Form.w_ordem(p_indice).value=''; "
        ShowHTML "       document.Form.w_elemento(p_indice).value=''; "
        ShowHTML "    }"
        ShowHTML "  }"
        ShowHTML "  function MarcaTodos() {"
        ShowHTML "    if (document.Form.w_sq_tabela.value==undefined) "
        ShowHTML "       for (i=0; i < document.Form.w_sq_tabela.length; i++) {"
        ShowHTML "         document.Form.w_sq_tabela[i].checked=true;"
        ShowHTML "         document.Form.w_ordem[i].disabled=false;"
        ShowHTML "         document.Form.w_elemento[i].disabled=false;"
        ShowHTML "       } "
        ShowHTML "    else document.Form.w_sq_tabela.checked=true;"
        ShowHTML "  }"
        ShowHTML "  function DesmarcaTodos() {"
        ShowHTML "    if (document.Form.w_sq_tabela.value==undefined) "
        ShowHTML "       for (i=0; i < document.Form.w_sq_tabela.length; i++) {"
        ShowHTML "         document.Form.w_sq_tabela[i].checked=false;"
        ShowHTML "         document.Form.w_ordem[i].disabled=true;"
        ShowHTML "         document.Form.w_elemento[i].disabled=true;"
        ShowHTML "         document.Form.w_ordem[i].value=''; "
        ShowHTML "         document.Form.w_elemento[i].value=''; "
        ShowHTML "       } "
        ShowHTML "    "
        ShowHTML "    else document.Form.w_sq_tabela.checked=false;"
        ShowHTML "  }"       
     End If
     CheckBranco
     FormataData
     ValidateOpen "Validacao"
     If InStr("IAP",O) > 0 Then
        If InStr("P",O) > 0 Then
           ShowHTML "  if (theForm.p_nome.value=='' && theForm.p_sq_sistema.selectedIndex==0 && theForm.p_sq_usuario.selectedIndex==0 && theForm.p_sq_tabela_tipo.selectedIndex==0) {"
           ShowHTML "     alert('Você deve escolher pelo menos um critério de filtragem!');"
           ShowHTML "     return false;"
           ShowHTML "  }"
           Validate "P4", "Linhas por página", "1", "1", "1", "4", "", "0123456789"
        ElseIf InStr("I",O) > 0 Then
           ShowHTML "  var i; "
           ShowHTML "  var w_erro=true; "
           ShowHTML "  if (theForm.w_sq_tabela.value==undefined) {"
           ShowHTML "     for (i=0; i < theForm.w_sq_tabela.length; i++) {"
           ShowHTML "       if (theForm.w_sq_tabela[i].checked) w_erro=false;"
           ShowHTML "     }"
           ShowHTML "  }"
           ShowHTML "  else {"
           ShowHTML "     if (theForm.w_sq_tabela.checked) w_erro=false;"
           ShowHTML "  }"
           ShowHTML "  if (w_erro) {"
           ShowHTML "    alert('Você deve informar pelo menos uma tabela!'); "
           ShowHTML "    return false;"
           ShowHTML "  }"
           ShowHTML "  for (i=0; i < theForm.w_sq_tabela.length; i++) {"
           ShowHTML "    if((theForm.w_sq_tabela[i].checked)&&(theForm.w_elemento[i].value=='')){"
           ShowHTML "      alert('Para todas as tabelas selecionadas vc deve informar o elemento da tabela!'); "
           ShowHTML "      return false;"           
           ShowHTML "    }"              
           ShowHTML "  }"
           ShowHTML "  for (i=0; i < theForm.w_sq_tabela.length; i++) {"
           ShowHTML "    if((theForm.w_sq_tabela[i].checked)&&(theForm.w_ordem[i].value=='')){"
           ShowHTML "      alert('Para todas as tabelas selecionadas vc deve informar a ordem da tabela para a importação do esquema!'); "
           ShowHTML "      return false;"
           ShowHTML "    }"              
           ShowHTML "  }"
        ElseIf InStr("A",O) > 0 Then
           Validate "w_elemento", "Elemento", "1", "1", 2, 50, "1", "1"
           Validate "w_ordem", "Ordem", "1", "1", 1, 18, "", "0123456789"
        End If
        
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     If O = "P" Then
        ShowHTML "  theForm.Botao[2].disabled=true;"
     End If
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf Instr("P",O) > 0 Then
     BodyOpen "onLoad='document.Form.p_sq_sistema.focus()';"
  Else
     BodyOpen "onLoad='document.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  DB_GetEsquema RS1, w_cliente, null, w_sq_esquema, null, null, null, null, null, null, null, null
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  ShowHTML "<tr><td colspan=3 bgcolor=""#FAEBD7""><table border=1 width=""100%""><tr><td>"
  ShowHTML "    <TABLE WIDTH=""100%"" CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
  ShowHTML "      <tr><td colspan=""3""><font size=""1"">Esquema: <b>" & RS1("nome") & "</font></b></td>"
  ShowHTML "      <tr><td colspan=""3""><font size=""1"">Descrição: <b>" & Nvl(RS1("Descricao"),"---") & "</font></b></td>"
  ShowHTML "      <tr valign=""top"">"
  ShowHTML "          <td><font size=""1"">Tipo: <b>" & RS1("nm_tipo") & "</b></td>"
  ShowHTML "          <td><font size=""1"">Formato: <b>" & RS1("nm_formato") & "</b></td>"
  ShowHTML "          <td><font size=""1"">Ativo: <b>" & RS1("nm_ativo") & "</b></td>"
  RS1.Close
  If Instr("AM",O) > 0 Then
     DB_GetEsquemaTabela RS1, null, w_sq_esquema, w_sq_esquema_tabela
      ShowHTML "      <tr><td colspan=""3""><font size=""1"">Tabela: <b>" & Nvl(RS1("nm_tabela"),"---") & "</font></b></td>"
      RS1.Close
  End If
  ShowHTML "    </TABLE>"
  ShowHTML "</TABLE>"
  ShowHTML "</TABLE>"
  ShowHTML "<tr><td>&nbsp"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
     'Listagem das tabelas do esquema
    AbreSessao
    ' Exibe a quantidade de ws_url apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "<tr><td><font size=""1"">"
    ShowHTML "        <a accesskey=""I"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&w_sq_esquema=" & w_sq_esquema & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_menu=" & w_menu & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "        <a accesskey=""F"" class=""ss"" href=""javascript:window.close(); opener.location.reload(); opener.focus();""><u>F</u>echar</a>&nbsp;"
    'ShowHTML "        <a accesskey=""O"" class=""SS"" href=""" & w_dir & w_Pagina & "Help&R=" & w_Pagina & par & "&O=O&w_sq_esquema=" & w_sq_esquema & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ target=""help""><u>O</u>rientações</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td rowspan=""1"" colspan=""2""><font size=""1""><b>Tabelas</font></td>"
    ShowHTML "          <td rowspan=""1"" colspan=""2""><font size=""1""><b>Campos</font></td>"
    ShowHTML "          <td rowspan=""2""><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Ordem", "ordem") & "</font></td>"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Nome", "nm_tabela") & "</font></td>"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Ordem", "or_coluna") & "</font></td>"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Nome", "Campo_externo") & "</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados ws_url, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=5 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os ws_url selecionados para listagem
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        If w_atual <> RS("nm_tabela") Then
           ShowHTML "        <td rowspan=""" & RS("qtd_coluna")& """ align=""center""><font size=""1"">" & RS("ordem") & "</td>"
           ShowHTML "        <td rowspan=""" & RS("qtd_coluna")& """><font size=""1"">" & RS("nm_tabela") & "</td>"
        End If
        ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(RS("or_coluna"),"---") & "</td>"
        ShowHTML "        <td><font size=""1"">" & Nvl(RS("campo_externo"),"---") & "</td>"
        If w_atual <> RS("nm_tabela") Then
           ShowHTML "        <td rowspan=""" & RS("qtd_coluna")& """><font size=""1"">"
           ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_esquema=" & RS("sq_esquema") & "&w_sq_esquema_tabela=" & RS("sq_esquema_tabela")& "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_menu=" & w_menu & MontaFiltro("GET") & """ title=""Altera a os dados da tabela deste esquema"">Alterar</A>&nbsp"
           ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_pagina & "Grava" & "&R=" & w_Pagina & par & "&O=E&w_sq_esquema=" & RS("sq_esquema") & "&w_sq_esquema_tabela=" & RS("sq_esquema_tabela")& "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_menu=" & w_menu & MontaFiltro("GET") & """ title=""Exclui a tabela deste esquema"" onClick=""return confirm('Confirma a exclusão do registro?');"">Excluir</A>&nbsp"
           ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_pagina & "MAPEAMENTO&R=" & w_Pagina & par & "&O=I&w_sq_esquema=" & RS("sq_esquema") & "&w_sq_esquema_tabela=" & RS("sq_esquema_tabela")& "&w_sq_tabela=" & RS("sq_tabela") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_menu=" & w_menu &  MontaFiltro("GET") & """ title=""Relaciona os campos da tabela"">Mapear</A>&nbsp"
        End If
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        w_atual = RS("nm_tabela")
        RS.MoveNext
      wend
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    DesconectaBD
  ElseIf Instr("P",O) > 0 Then
     'Filtro para inclusão de um tabela no esquema
     ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
     ShowHTML "    <table width=""100%"" border=""0"">"
     AbreForm "Form", w_dir & R, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"I"
     ShowHTML "<INPUT type=""hidden"" name=""w_sq_esquema"" value=""" & w_sq_esquema & """>"
     ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & w_menu & """>"
     ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">" 

     ShowHTML "  <tr bgcolor=""" & conTrBgColor & """><td colspan=2><div align=""justify""><font size=2><b><ul>Instruções</b>:<li>Informe os parâmetros desejados para recuperar a lista de tabelas.<li>Quando a relação de tabelas for exibida, selecione as tabelas desejadas clicando sobre a caixa ao lado do nome.<li>Você pode informar o nome de uma tabela , selecionar as tabelas de um sistema, ou ainda as tabelas de um usuário.<li>Após informar os parâmetros desejados, clique sobre o botão <i>Aplicar filtro</i>.</ul><hr><b>Filtro</b></div>"
     ShowHTML "  <tr bgcolor=""" & conTrBgColor & """><td colspan=2>"
     ShowHTML "    <table width=""100%"" border=""0"">"
     ShowHTML "      <tr>"
     SelecaoSistema "<u>S</u>istema:", "S", null, p_sq_sistema, w_cliente, "p_sq_sistema", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_sq_usuario'; document.Form.submit();"""
     SelecaoUsuario "<u>U</u>suário:", "S", null, w_cliente, p_sq_usuario, Nvl(p_sq_sistema,0), "p_sq_usuario", null, null
     ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>N</u>ome:</b><br><input " & w_disabled & " accesskey=""N"" type=""text"" name=""p_nome"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & p_nome & """></td>"
     SelecaoTipoTabela "<u>T</u>ipo:", "T", null, p_sq_tabela_tipo, null, "p_sq_tabela_tipo", null, null
     ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""sti"" type=""text"" name=""P4"" size=""4"" maxlength=""4"" value=""" & P4 & """></td></tr>"
     ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
     ShowHTML "      <tr><td align=""center"" colspan=""3"">"
     ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
     ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_sq_esquema=" & w_sq_esquema & "&R=" & R &  "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&O=P&SG=" & SG & "&w_menu=" & w_menu & "';"" name=""Botao"" value=""Limpar campos"">"
     ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_sq_esquema=" & w_sq_esquema & "&R=" & R &  "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_menu=" & w_menu & "&O=L';"" name=""Botao"" value=""Cancelar"">"
     ShowHTML "          </td>"
     ShowHTML "      </tr>"    
     ShowHTML "    </table>"
     ShowHTML "    </TD>"
     ShowHTML "</tr>"
     ShowHTML "</form>"
  ElseIf Instr("I",O) > 0 Then
     'Rotina de escolha e gravação de tabelas para o esquema
     AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
     ShowHTML "<INPUT type=""hidden"" name=""w_sq_esquema"" value=""" & w_sq_esquema & """>"
     ShowHTML "<INPUT type=""hidden"" name=""w_sq_tabela"" value="""">"
     ShowHTML "<INPUT type=""hidden"" name=""w_ordem"" value="""">"
     ShowHTML "<INPUT type=""hidden"" name=""w_elemento"" value="""">"
     ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & w_menu & """>"
       
     ShowHTML "<tr><td><font size=""1"">"
     ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a></font>"
     ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
     ShowHTML "<tr><td align=""center"" colspan=3>"
     ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     ShowHTML "          <tr bgcolor=""" & conTrBgColor & """ align=""center"" valign=""top"">"
     ShowHTML "            <td width=""70""NOWRAP><font size=""2""><U ID=""INICIO"" STYLE=""cursor:hand;"" CLASS=""hl"" onClick=""javascript:MarcaTodos();"" TITLE=""Marca todos os itens da relação""><IMG SRC=""images/NavButton/BookmarkAndPageActivecolor.gif"" BORDER=""1"" width=""15"" height=""15""></U>&nbsp;"
     ShowHTML "                                      <U STYLE=""cursor:hand;"" CLASS=""hl"" onClick=""javascript:DesmarcaTodos();"" TITLE=""Desmarca todos os itens da relação""><IMG SRC=""images/NavButton/BookmarkAndPageInactive.gif"" BORDER=""1"" width=""15"" height=""15""></U>"
     ShowHTML "          <td><font size=""1""><b>Sistema</b></font></td>"
     ShowHTML "          <td><font size=""1""><b>Tabela</b></font></td>"
     ShowHTML "          <td><font size=""1""><b>Descrição</b></font></td>"      
     ShowHTML "          <td><font size=""1""><b>Elemento</b></font></td>"
     ShowHTML "          <td><font size=""1""><b>Ordem</b></font></td>"
     If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
     Else
        ' Lista os registros selecionados para listagem
        rs.PageSize     = P4
        rs.AbsolutePage = P3
        w_cont = 0
        While Not RS.EOF and RS.AbsolutePage = P3
           w_cont = w_cont + 1
           If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
           ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
           ShowHTML "        <td align=""center""><input type=""checkbox"" name=""w_sq_tabela"" value=""" & RS("chave") & """ onClick=""valor(" & w_cont & ");"">"
           ShowHTML "        <td><font size=""1"">" & RS("sg_sistema") & "</td>"
           ShowHTML "        <td><font size=""1"">" & lCase(RS("nm_usuario")&"."&RS("nome")) & "</td>"
           ShowHTML "        <td><font size=""1"">" & RS("descricao") & "</td>"
           ShowHTML "        <td><font size=""1""><input disabled type=""text"" name=""w_elemento"" class=""sti"" SIZE=""20"" MAXLENGTH=""50"" VALUE=""" & w_elemento & """></td>"
           ShowHTML "        <td><font size=""1""><input disabled type=""text"" name=""w_ordem"" class=""sti"" SIZE=""4"" MAXLENGTH=""4"" VALUE=""" & w_ordem & """></td>"
           ShowHTML "      </tr>"
           RS.MoveNext
        Wend
     End If
     ShowHTML "      </center>"
     ShowHTML "    </table>"
     ShowHTML "<tr><td align=""center"" colspan=""3""><font size=""1"">"
     ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Gravar"">"
     ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_sq_esquema=" & w_sq_esquema & "&R=" & R &  "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_menu=" & w_menu & "&O=L';"" name=""Botao"" value=""Cancelar"">"
     ShowHTML "  </td>"
     ShowHTML "</FORM>"
     ShowHTML "<tr><td align=""center"" colspan=3>"
     If R > "" Then
        MontaBarra w_dir&w_pagina&par&"&R="&R&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG&"&w_chave="&w_chave, RS.PageCount, P3, P4, RS.RecordCount
     Else
        MontaBarra w_dir&w_pagina&par&"&R="&w_Pagina&par&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG&"&w_chave="&w_chave, RS.PageCount, P3, P4, RS.RecordCount
     End If
     ShowHTML "</tr>"
     DesconectaBD
  ElseIf Instr("A",O) > 0 Then
     'Rotina para alteração do dados da tabela de um esquema
     ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
     ShowHTML "    <table width=""100%"" border=""0"">"
     AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,O
     ShowHTML "<INPUT type=""hidden"" name=""w_sq_esquema"" value=""" & w_sq_esquema & """>"
     ShowHTML "<INPUT type=""hidden"" name=""w_sq_esquema_tabela"" value=""" & w_sq_esquema_tabela & """>"
     ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
     ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & w_menu & """>" 

     ShowHTML "  <tr bgcolor=""" & conTrBgColor & """><td colspan=2>"
     ShowHTML "    <table width=""100%"" border=""0"">"
     ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>E</u>lemento:</b><br><input " & w_disabled & " accesskey=""E"" type=""text"" name=""w_elemento"" class=""sti"" SIZE=""30"" MAXLENGTH=""50"" VALUE=""" & w_elemento & """></td>"
     ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>O</u>rdem:</b><br><input " & w_disabled & " accesskey=""O"" type=""text"" name=""w_ordem"" class=""sti"" SIZE=""4"" MAXLENGTH=""4"" VALUE=""" & w_ordem & """></td>"
     ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
     ShowHTML "      <tr><td align=""center"" colspan=""3"">"
     ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Gravar"">"
     ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_sq_esquema=" & w_sq_esquema & "&R=" & R &  "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_menu=" & w_menu & "&O=L';"" name=""Botao"" value=""Cancelar"">"
     ShowHTML "          </td>"
     ShowHTML "      </tr>"    
     ShowHTML "    </table>"
     ShowHTML "    </TD>"
     ShowHTML "</tr>"
     ShowHTML "</form>" 
  Else
     ScriptOpen "JavaScript"
     ShowHTML " alert('Opção não disponível');"
     ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_sq_esquema          = Nothing 
  Set w_sq_esquema_tabela   = Nothing 
  Set w_sq_tabela           = Nothing 
  Set w_ordem               = Nothing 
  Set w_elemento            = Nothing 
  Set w_atual               = Nothing 
  Set p_nome                = Nothing 
  Set p_sq_sistema          = Nothing 
  Set p_sq_usuario          = Nothing 
  Set p_sq_tabela_tipo      = Nothing 
  
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de inclusão de tabelas no esquema
REM -------------------------------------------------------------------------
Sub Mapeamento
  Dim w_sq_esquema_atributo, w_sq_esquema, w_sq_esquema_tabela, w_sq_tabela
  Dim w_sq_coluna, w_ordem, w_campo_externo
  
  w_sq_esquema_atributo = Request("w_sq_esquema_atributo")
  w_sq_esquema_tabela   = Request("w_sq_esquema_tabela")
  w_sq_esquema          = Request("w_sq_esquema")
  w_sq_tabela           = Request("w_sq_tabela")
  w_sq_coluna           = Request("w_sq_coluna")
  w_troca               = Request("w_troca")

  If w_troca > "" Then ' Se for recarga da página
     w_ordem             = Request("w_ordem") 
     w_campo_externo     = Request("w_campo_externo") 
  ElseIf O = "I" Then
     ' Recupera todos os ws_url para a listagem
     Response.Write w_cliente & ", " & w_sq_tabela
     DB_GetColuna RS, w_cliente, null, w_sq_tabela, null, null, null, null, w_sq_esquema_tabela
     RS.Sort = "ordem, nm_coluna"
  End If
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("I",O) > 0 Then
     ScriptOpen "JavaScript"
     If InStr("I",O) > 0 Then
        ShowHTML "  function valor(p_indice) {"
        ShowHTML "    if (document.Form.w_sq_coluna(p_indice).checked) { "
        ShowHTML "       document.Form.w_ordem(p_indice).disabled=false; "
        ShowHTML "       document.Form.w_campo_externo(p_indice).disabled=false; "
        ShowHTML "       document.Form.w_campo_externo(p_indice).focus(); "
        ShowHTML "    } else {"
        ShowHTML "       document.Form.w_ordem(p_indice).disabled=true; "
        ShowHTML "       document.Form.w_campo_externo(p_indice).disabled=true; "
        ShowHTML "       document.Form.w_ordem(p_indice).value=''; "
        ShowHTML "       document.Form.w_campo_externo(p_indice).value=''; "
        ShowHTML "    }"
        ShowHTML "  }"
        ShowHTML "  function MarcaTodos() {"
        ShowHTML "    if (document.Form.w_sq_coluna.value==undefined) "
        ShowHTML "       for (i=0; i < document.Form.w_sq_coluna.length; i++) {"
        ShowHTML "         document.Form.w_sq_coluna[i].checked=true;"
        ShowHTML "         document.Form.w_ordem[i].disabled=false;"
        ShowHTML "         document.Form.w_campo_externo[i].disabled=false;"
        ShowHTML "       } "
        ShowHTML "    else document.Form.w_sq_coluna.checked=true;"
        ShowHTML "  }"
        ShowHTML "  function DesmarcaTodos() {"
        ShowHTML "    if (document.Form.w_sq_coluna.value==undefined) "
        ShowHTML "       for (i=0; i < document.Form.w_sq_coluna.length; i++) {"
        ShowHTML "         document.Form.w_sq_coluna[i].checked=false;"
        ShowHTML "         document.Form.w_ordem[i].disabled=true;"
        ShowHTML "         document.Form.w_campo_externo[i].disabled=true;"
        ShowHTML "         document.Form.w_ordem[i].value=''; "
        ShowHTML "         document.Form.w_campo_externo[i].value=''; "
        ShowHTML "       } "
        ShowHTML "    "
        ShowHTML "    else document.Form.w_sq_coluna.checked=false;"
        ShowHTML "  }"       
     End If
     CheckBranco
     FormataData
     ValidateOpen "Validacao"
     If InStr("I",O) > 0 Then
        If InStr("I",O) > 0 Then
           ShowHTML "  var i; "
           ShowHTML "  var w_erro=true; "
           ShowHTML "  if (theForm.w_sq_coluna.value==undefined) {"
           ShowHTML "     for (i=0; i < theForm.w_sq_coluna.length; i++) {"
           ShowHTML "       if (theForm.w_sq_coluna[i].checked) w_erro=false;"
           ShowHTML "     }"
           ShowHTML "  }"
           ShowHTML "  else {"
           ShowHTML "     if (theForm.w_sq_coluna.checked) w_erro=false;"
           ShowHTML "  }"
           ShowHTML "  if (w_erro) {"
           ShowHTML "    alert('Você deve informar pelo menos uma coluna!'); "
           ShowHTML "    return false;"
           ShowHTML "  }"
           ShowHTML "  for (i=0; i < theForm.w_sq_coluna.length; i++) {"
           ShowHTML "    if((theForm.w_sq_coluna[i].checked)&&(theForm.w_campo_externo[i].value=='')){"
           ShowHTML "      alert('Para todas as colunas selecionadas vc deve informar o campo externo(XML) da coluna!'); "
           ShowHTML "      return false;"           
           ShowHTML "    }"              
           ShowHTML "  }"
           ShowHTML "  for (i=0; i < theForm.w_sq_coluna.length; i++) {"
           ShowHTML "    if((theForm.w_sq_coluna[i].checked)&&(theForm.w_ordem[i].value=='')){"
           ShowHTML "      alert('Para todas as colunas selecionadas vc deve informar a ordem da coluna para a importação do esquema!'); "
           ShowHTML "      return false;"
           ShowHTML "    }"              
           ShowHTML "  }"
        End If
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  Else
     BodyOpen "onLoad='document.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  DB_GetEsquema RS1, w_cliente, null, w_sq_esquema, null, null, null, null, null, null, null, null
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  ShowHTML "<tr><td colspan=3 bgcolor=""#FAEBD7""><table border=1 width=""100%""><tr><td>"
  ShowHTML "    <TABLE WIDTH=""100%"" CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
  ShowHTML "      <tr><td colspan=""3""><font size=""1"">Esquema: <b>" & RS1("nome") & "</font></b></td>"
  ShowHTML "      <tr><td colspan=""3""><font size=""1"">Descrição: <b>" & Nvl(RS1("Descricao"),"---") & "</font></b></td>"
  ShowHTML "      <tr valign=""top"">"
  ShowHTML "          <td><font size=""1"">Tipo: <b>" & RS1("nm_tipo") & "</b></td>"
  ShowHTML "          <td><font size=""1"">Formato: <b>" & RS1("nm_formato") & "</b></td>"
  ShowHTML "          <td><font size=""1"">Ativo: <b>" & RS1("nm_ativo") & "</b></td>"
  RS1.Close
  DB_GetEsquemaTabela RS1, null, w_sq_esquema, w_sq_esquema_tabela
  ShowHTML "      <tr><td colspan=""3""><font size=""1"">Tabela: <b>" & Nvl(RS1("nm_tabela"),"---") & "</font></b></td>"
  RS1.Close
  ShowHTML "    </TABLE>"
  ShowHTML "</TABLE>"
  ShowHTML "</TABLE>"
  ShowHTML "<tr><td>&nbsp"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If Instr("I",O) > 0 Then
     'Rotina de escolha e gravação das colunas para a tabela
     AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"ISSIGMAP",R,O
     ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & w_menu & """>"
     ShowHTML "<INPUT type=""hidden"" name=""w_sq_esquema"" value=""" & w_sq_esquema & """>"
     ShowHTML "<INPUT type=""hidden"" name=""w_sq_esquema_tabela"" value=""" & w_sq_esquema_tabela & """>"
     ShowHTML "<INPUT type=""hidden"" name=""w_sq_coluna"" value="""">"
     ShowHTML "<INPUT type=""hidden"" name=""w_ordem"" value="""">"
     ShowHTML "<INPUT type=""hidden"" name=""w_campo_externo"" value="""">"
       
     ShowHTML "<tr><td><font size=""1"">"
     ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
     ShowHTML "<tr><td align=""center"" colspan=3>"
     ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     ShowHTML "          <tr bgcolor=""" & conTrBgColor & """ align=""center"" valign=""top"">"
     ShowHTML "            <td width=""70""NOWRAP><font size=""2""><U ID=""INICIO"" STYLE=""cursor:hand;"" CLASS=""hl"" onClick=""javascript:MarcaTodos();"" TITLE=""Marca todos os itens da relação""><IMG SRC=""images/NavButton/BookmarkAndPageActivecolor.gif"" BORDER=""1"" width=""15"" height=""15""></U>&nbsp;"
     ShowHTML "                                      <U STYLE=""cursor:hand;"" CLASS=""hl"" onClick=""javascript:DesmarcaTodos();"" TITLE=""Desmarca todos os itens da relação""><IMG SRC=""images/NavButton/BookmarkAndPageInactive.gif"" BORDER=""1"" width=""15"" height=""15""></U>"
     ShowHTML "          <td><font size=""1""><b>Coluna</b></font></td>"
     ShowHTML "          <td><font size=""1""><b>Descricao</b></font></td>"
     ShowHTML "          <td><font size=""1""><b>Tipo</b></font></td>"      
     ShowHTML "          <td><font size=""1""><b>Obrig.</b></font></td>" 
     ShowHTML "          <td><font size=""1""><b>Campo externo</b></font></td>"
     ShowHTML "          <td><font size=""1""><b>Ordem</b></font></td>"
     If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
     Else
        ' Lista os registros selecionados para listagem
        w_disabled = "disabled"
        w_cont = 0 
        While Not RS.EOF
           w_cont = w_cont + 1
           If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
           ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
           DB_GetEsquemaAtributo RS1, null, w_sq_esquema_tabela, null, RS("chave")
           If Not RS1.EOF Then
              ShowHTML "        <td align=""center""><input type=""checkbox"" name=""w_sq_coluna"" value=""" & RS("chave") & """ onClick=""valor(" & w_cont & ");"" CHECKED>"
              w_ordem = RS1("ordem")
              w_campo_externo = RS1("campo_externo")
              w_disabled = ""
           Else
              ShowHTML "        <td align=""center""><input type=""checkbox"" name=""w_sq_coluna"" value=""" & RS("chave") & """ onClick=""valor(" & w_cont & ");"">"
           End If
           RS1.Close
           ShowHTML "        <td><font size=""1"">" & RS("nm_coluna") & "</td>"
           ShowHTML "        <td><font size=""1"">" & Nvl(RS("descricao"),"---") & "</td>"
           ShowHTML "        <td nowrap><font size=""1"">" & RS("nm_coluna_tipo") & " ("
           If uCase(RS("nm_coluna_tipo")) = "NUMERIC" Then
              ShowHTML Nvl(RS("precisao"), RS("tamanho")) & "," & Nvl(RS("escala"),0)
           Else
              ShowHTML RS("tamanho")
           End If
           ShowHTML ")</td>"
           ShowHTML "        <td align=""center""><font size=""1"">" & RS("obrigatorio") & "</td>"
           ShowHTML "        <td><font size=""1""><input " & w_disabled & " type=""text"" name=""w_campo_externo"" class=""sti"" SIZE=""20"" MAXLENGTH=""30"" VALUE=""" & w_campo_externo & """></td>"
           ShowHTML "        <td><font size=""1""><input " & w_disabled & " type=""text"" name=""w_ordem"" class=""sti"" SIZE=""4"" MAXLENGTH=""4"" VALUE=""" & w_ordem & """></td>"
           ShowHTML "      </tr>"
           w_ordem         = ""
           w_campo_externo = ""
           w_disabled      = "disabled"
           RS.MoveNext
        Wend
     End If
     ShowHTML "      </center>"
     ShowHTML "    </table>"
     ShowHTML "<tr><td align=""center"" colspan=""3""><font size=""1"">"
     ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Gravar"">"
     ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & "Tabela" & "&w_sq_esquema=" & w_sq_esquema & "&R=" & R &  "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_menu=" & w_menu & "&O=L';"" name=""Botao"" value=""Cancelar"">"
     ShowHTML "  </td>"
     ShowHTML "</FORM>"
     DesconectaBD
  Else
     ScriptOpen "JavaScript"
     ShowHTML " alert('Opção não disponível');"
     ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_sq_esquema          = Nothing 
  Set w_sq_esquema_tabela   = Nothing 
  Set w_sq_tabela           = Nothing
  Set w_sq_esquema_atributo = Nothing  
  Set w_sq_coluna           = Nothing  
  Set w_ordem               = Nothing 
  Set w_campo_externo       = Nothing 
  
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de importação de arquivos físicos para atualização a partir do SIGPLAN
REM -------------------------------------------------------------------------
Sub Importacao
  Dim w_sq_esquema, w_caminho, w_upload_maximo, w_data_arquivo
  
  w_sq_esquema      = Request("w_sq_esquema")
  w_troca           = Request("w_troca")
  
  DB_GetCustomerData RS, w_cliente
  w_upload_maximo = RS("upload_maximo")
  DesconectaBD
        
  If O = "I" Then
     ' Recupera todos os ws_url para a listagem
     DB_GetEsquema RS, w_cliente, null, w_sq_esquema, null, null, null, null, null, null, null, null
  ElseIf Instr("AE",O) > 0 Then

  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     CheckBranco
     FormataDataHora
     FormataData
     ProgressBar w_dir_volta, UploadID           
     ValidateOpen "Validacao"
     If InStr("I",O) > 0 Then
        Validate "w_data_arquivo", "Data e hora", "DATAHORA", "1", "17", "17", "", "0123456789 /:,"
        Validate "w_caminho", "Arquivo de dados", "1", "1", "1", "255", "1", "1"
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ShowHTML "if (theForm.w_caminho.value != '') {return ProgressBar();}"     
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf Instr("I",O) > 0 Then
     If RS("formato") = "A" Then
        BodyOpen "onLoad='document.Form.w_data_arquivo.focus()';"
     ElseIf RS("formato") = "W" Then
        BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
     End If
  ElseIf Instr("E",O) > 0 Then
     BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
  Else
     BodyOpen "onLoad='document.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then

  ElseIf Instr("I",O) > 0 Then
    If not RS("formato") = "W" Then
       ShowHTML "<FORM action=""" & w_dir & w_pagina & "Grava&SG=IMPARQ&O="&O&"&w_menu="&w_menu&"&UploadID="&UploadID&""" name=""Form"" onSubmit=""return(Validacao(this));"" enctype=""multipart/form-data"" method=""POST"">"
       ShowHTML "<INPUT type=""hidden"" name=""P1"" value=""" & P1 & """>"
       ShowHTML "<INPUT type=""hidden"" name=""P2"" value=""" & P2 & """>"
       ShowHTML "<INPUT type=""hidden"" name=""P3"" value=""" & P3 & """>"
       ShowHTML "<INPUT type=""hidden"" name=""P4"" value=""" & P4 & """>"
       ShowHTML "<INPUT type=""hidden"" name=""TP"" value=""" & TP & """>"
       ShowHTML "<INPUT type=""hidden"" name=""R"" value=""" & R & """>"
    Else
       AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,1,P4,TP,"IMPWEB",R,O
       ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & w_menu & """>"
    End If

    ShowHTML "<INPUT type=""hidden"" name=""w_sq_esquema"" value=""" & w_sq_esquema & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_tipo"" value=""" & p_tipo & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    
    ShowHTML "      <tr><td bgcolor=""#FAEBD7""><table border=1 width=""100%""><tr><td>"
    ShowHTML "      <TABLE WIDTH=""100%"" CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "      <tr><td><font size=""1"">Nome:<b> " & RS("Nome")& "</b></td>"
    ShowHTML "      <tr><td><font size=""1"">Descrição:<b> " & RS("Descricao") & "</b></td>"
    ShowHTML "      <tr><td><table border=0 width=""100%"" cellpadding=0 cellspacing=0><tr valign=""top"">"
    ShowHTML "          <td><font size=""1"">Nó raiz:<b> " & RS("no_raiz") & "</b></td>"
    ShowHTML "          <td><font size=""1"">Formato:<b> " & RS("nm_formato") & "</b></td>"    
    If not RS("formato") = "W" Then
       ShowHTML "       <td><font size=""1"">Ativo:<b> " & RS("nm_ativo") & "</b></td>"           
       ShowHTML "              </table>"
    Else
       ShowHTML "              </table>"
       ShowHTML "      <tr><td><font size=""1"">Servidor:<b> " & RS("ws_servidor") & "</b></td>"
       ShowHTML "      <tr><td><font size=""1"">URL:<b> " & RS("ws_url")& "</b></td>"
       ShowHTML "      <tr><td><font size=""1"">Ação:<b> " & RS("ws_acao") & "</b></td>"
       ShowHTML "      <tr><td><font size=""1"">Mensagem:<b> " & RS("ws_mensagem") & "</b></td>"
       ShowHTML "      <tr>"
       ShowHTML "       <td><font size=""1"">Ativo:<b>" & RS("nm_ativo") & "</b></td>"
    End If
    ShowHTML "    </TABLE>"
    ShowHTML "</TABLE>"
    If RS("formato") = "A" Then
       ShowHTML "      <tr><td align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""2""><b><font color=""#BC3131"">ATENÇÃO</font>: o tamanho máximo aceito para o arquivo é de " & cDbl(w_upload_maximo)/1024 & " KBytes</b>.</font></td>"
       ShowHTML "<INPUT type=""hidden"" name=""w_upload_maximo"" value=""" & w_upload_maximo & """>"
       ShowHTML "      <tr><td><font size=""1""><b><u>D</u>ata/hora extração:</b><br><input " & w_Disabled & " accesskey=""D"" type=""text"" name=""w_data_arquivo"" class=""sti"" SIZE=""17"" MAXLENGTH=""17"" VALUE=""" & w_data_arquivo & """  onKeyDown=""FormataDataHora(this, event);"" title=""OBRIGATÓRIO. Informe a data e hora da extração do aquivo. Digite apenas números. O sistema colocará os separadores automaticamente.""></td>"
       ShowHTML "      <tr><td><font size=""1""><b>A<u>r</u>quivo:</b><br><input accesskey=""R"" type=""file"" name=""w_caminho"" class=""STI"" SIZE=""80"" MAXLENGTH=""100"" VALUE="""" title=""OBRIGATÓRIO. Clique no botão ao lado para localizar o arquivo. Ele será transferido automaticamente para o servidor."">" 
    End If
    ShowHTML "      <tr><td align=""LEFT""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
    
    ShowHTML "      <tr><td align=""center""><hr>"
    ShowHTML "          <input class=""STB"" type=""submit"" name=""Botao"" value=""Importar"">"
    ShowHTML "          <input class=""stb"" type=""button"" onClick=""location.href='" & R & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & "ISSIGIMP" & "&O=L';"" name=""Botao"" value=""Cancelar"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
    DesconectaBD
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_sq_esquema  = Nothing
  Set w_caminho     = Nothing  
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de exportação de arquivos físicos para atualização do SIGPLAN
REM -------------------------------------------------------------------------
Sub Exportacao
  Dim w_sq_esquema, w_no_raiz, w_atual, w_campos, w_from, w_where, j, k, l, w_valor
  Dim FS, F1, w_arquivo_processamento
  Dim w_sql(20)
  Dim w_elemento(20)
  Dim w_atributo(20, 100)
  Dim w_campo(20, 100)
  Dim i(100)
 
  w_sq_esquema      = Request("w_sq_esquema")
  
  ' Recupera os dados do esquema selecionado
  DB_GetEsquema RS, w_cliente, null, w_sq_esquema, null, null, null, null, null, null, null, null

  ' Recupera cada uma das tabelas referenciadas pelo esquema
  DB_GetEsquemaTabela RS1, null, w_sq_esquema, null
  RS1.Sort = "ordem, nm_tabela, or_coluna"

  If RS1.EOF Then
     Cabecalho
     ShowHTML "<Body>"
     ScriptOpen "JavaScript"
     ShowHTML "  alert('Não foram informadas tabelas para o esquema informado');"
     ShowHTML "  window.close();"
     ScriptClose
     ShowHTML "</Body>"
     ShowHTML "</html>"
  Else
     w_where  = " where cliente = " & w_cliente & VbCrLf & _
                "   and ano     = " & RetornaAno()

     w_atual  = ""
     w_cont   = 0
     j        = 0
     while not RS1.EOF
        If RS1("elemento") <> w_atual Then
           If w_cont > 0 Then 
              i(w_cont) = j 
              w_campos = Mid(w_campos, 3, len(w_campos))
              w_sql(w_cont) = "select " & w_campos & VbCrLf & _
                              w_from & VbCrLf & _
                              w_where & VbCrLf & _
                              " order by " & w_campos
           End If
           w_cont = w_cont + 1
           w_elemento(w_cont) = RS1("elemento")
           w_atual = RS1("elemento")
           j = 0

           w_campos = ""
           w_from   = "  from " & strschema_is & RS1("nm_tabela")
        End If
        j = j + 1
        w_atributo(w_cont, j) = RS1("campo_externo")
        w_campo(w_cont, j)    = RS1("cl_nome")
        w_campos              = w_campos & ", " & RS1("cl_nome")
        RS1.MoveNext
     wend
     i(w_cont) = j
     
     w_campos = Mid(w_campos, 3, len(w_campos))
     w_sql(w_cont) = "select " & w_campos & VbCrLf & _
                     w_from & VbCrLf & _
                     w_where & VbCrLf & _
                     " order by " & w_campos
     
     ' Gera o arquivo de exportação
     Set FS = CreateObject("Scripting.FileSystemObject")

     ' Configura o nome dos arquivo recebido e do arquivo registro
     w_arquivo_processamento = RS("nome") & ".xml"

     Set F1 = FS.CreateTextFile(w_caminho & w_arquivo_processamento, true, true)

              
              
     F1.WriteLine "<?xml version=""1.0"" encoding=""Unicode""?>"
     F1.WriteLine "<" & RS("no_raiz") & " xmlns:xsd=""http://www.w3.org/2001/XMLSchema"" xmlns:xsi=""http://www.w3.org/2001/XMLSchema-instance"" xmlns=""http://www.sigplan.gov.br/xml/"">"

     ' Processa cada um dos esquemas recuperados
     for j = 1 to w_cont
        RS2.Open w_sql(j), dbms
        While Not RS2.EOF
           F1.WriteLine "  <" & w_elemento(j) & ">"
           ' Processa cada um dos atributos recuperados
           for k = 1 to i(j)
              ' Se o valor do banco for nulo, exporta tag fechada; 
              ' caso contrário, exporta tag abre/fecha contendo o valor
              If Nvl(RS2(w_campo(j, k)).Value,"") > "" Then
                 ' O bloco de IFs abaixo executa transformações nos dados para o formato esperado pelo SIGPLAN
                 If RS2(w_campo(j, k)).Type = adVarchar and RS2(w_campo(j, k)).DefinedSize = 1 Then
                    If RS2(w_campo(j, k)).Value = "S" Then w_valor = "true" Else w_valor = "false" End If
                 Elseif RS2(w_campo(j, k)).Type = adNumeric Then
                    ' Se o valor for igual a zero, exporta 0;
                    ' caso contrário, verifica o número de decimais e exporta o valor 
                    ' com nenhuma ou com 4 decimais, usando o ponto como separador de decimais
                    If cDbl(RS2(w_campo(j, k)).Value) <> cDbl(0) Then
                       If RS2(w_campo(j, k)).NumericScale > 0 Then
                          w_valor = Replace(cStr(FormatNumber(RS2(w_campo(j, k)).Value, 4, true, false, false)),",",".")
                       Else
                          w_valor = Replace(cStr(FormatNumber(RS2(w_campo(j, k)).Value, 0, true, false, false)),",",".")
                       End If
                    Else
                       w_valor = "0"
                    End If
                 Elseif RS2(w_campo(j, k)).Type = adDate Then
                    w_valor = FormataDataXML(RS2(w_campo(j, k)).Value)
                 Else
                    w_valor = RS2(w_campo(j, k)).Value
                 End If
                 F1.WriteLine "    <" & w_atributo(j, k) & ">" & w_valor & "</" & w_atributo(j, k) & ">"
              End If
           next
           F1.WriteLine "  </" & w_elemento(j) & ">"
           RS2.MoveNext
        Wend
        RS2.Close
     next
     F1.WriteLine "</" & RS("no_raiz") & ">"
     F1.Close

     ' Grava o resultado da importação no banco de dados
     'DML_PutDcOcorrencia O, _
     '    w_sq_esquema, w_cliente,   w_usuario,     ul.Texts.Item("w_data_arquivo"), _
     '    w_nome_recebido, _
     '    w_arquivo_processamento, w_tamanho_recebido,  w_tipo_recebido, _
     '    w_arquivo_registro,      w_arquivo_rejeicao, w_tamanho_registro, w_tipo_registro, _
     '    w_reg,       w_erro
     
     Cabecalho
     ShowHTML "<Body>"
     ScriptOpen "JavaScript"
     ShowHTML "  alert('Exportação concluída com sucesso!');"
     ShowHTML "  history.back(1);"
     ScriptClose
     ShowHTML "</Body>"
     ShowHTML "</html>"

  End If
  DesconectaBd
  RS1.Close
  
  Set w_sq_esquema  = Nothing
  Set w_no_raiz     = Nothing
  Set w_atual       = Nothing
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de teste de acesso ao Web Service do SIGPLAN
REM -------------------------------------------------------------------------
Sub Teste
  Dim w_ws_servidor, w_ws_url, w_ws_acao, w_ws_mensagem, w_no, w_elemento, w_arquivo, w_texto
  Dim w_cd_resposta, w_resposta, xmlHTTP, i, j, k
  
  Set xmlHTTP    = server.CreateObject("Msxml2.ServerXMLHTTP.3.0")
  Set w_ws_mensagem = Server.CreateObject("Msxml2.DOMDocument.3.0")
  Set w_arquivo  = Server.CreateObject("Msxml2.DOMDocument.3.0")
  Set w_resposta = Server.CreateObject("Msxml2.DOMDocument.3.0")

  w_ws_servidor = "www.sigplan.gov.br"
  w_ws_url      = "/xml/acompanhamento.asmx"
  w_ws_acao     = "//www.sigplan.gov.br/retornaDadosPrograma"
  w_ws_mensagem.async = false
  
  w_ws_mensagem.loadXML  "<?xml version=""1.0"" encoding=""utf-8""?>" & VbCrLf & _
                       "<soap:Envelope xmlns:xsi=""//www.w3.org/2001/XMLSchema-instance"" " & VbCrLf & _
                       "xmlns:xsd=""//www.w3.org/2001/XMLSchema"" " & VbCrLf & _
                       "xmlns:soap=""//schemas.xmlsoap.org/soap/envelope/"">" & VbCrLf & _
                       "  <soap:Body>" & VbCrLf & _
                       "    <retornaDadosPrograma xmlns=""//www.sigplan.gov.br/"">" & VbCrLf & _
                       "      <usuario>alex</usuario>" & VbCrLf & _
                       "      <senha>senha_alex</senha>" & VbCrLf & _
                       "      <ORGCod>36000</ORGCod>" & VbCrLf & _
                       "      <PRGAno>2005</PRGAno>" & VbCrLf & _
                       "      <PRGCod>0016</PRGCod>" & VbCrLf & _
                       "    </retornaDadosPrograma>" & VbCrLf & _
                       "  </soap:Body>" & VbCrLf & _
                       "</soap:Envelope>" & VbCrLf
  
   w_arquivo.async = false
   If w_arquivo.load("c:\inetpub\wwwroot\seppir\trabalho\infrasig\carga_ppa\Revisao\NaturezaPPA.xml") = false Then
      Response.Write "pau"
   Else
      Response.Write "ok"
   End If
   
   set w_no = w_arquivo.documentElement.selectSingleNode("//NaturezaPPA")

   for i = 0 to w_no.ChildNodes.length - 1 
      ShowHTML "<table><tr> "
      set w_texto = w_no.selectSingleNode(w_no.ChildNodes.item(i).nodename)
      set w_elemento = w_no.childNodes.item(i).childNodes
      for j = 0 to w_elemento.length-1
         with w_elemento
            ShowHTML "<td>" & "//" & w_no.nodename  & "/" & w_texto.nodename  & "/" & .item(j).nodename & " = '" & .item(j).text & "'</td>"
         end with
      next
      ShowHTML "</tr></table>"
   next

  ShowHTML "      </tr>"
  ShowHTML "<HTML>"
  ShowHTML "<TITLE>SOAP-Toolkit 2.0 Sample 1</TITLE>"
  ShowHTML "<BODY>"
  ShowHTML "  <P><H2>Test if the connection can be made to the server and a valid response is returned</H2></P> "

  xmlHTTP.open "POST" , "//" & w_ws_servidor & w_ws_url, false
  xmlHTTP.setRequestHeader "Content-Type", "text/xml; charset=utf-8"
  xmlHTTP.setRequestHeader "SOAPAction", w_ws_acao
  xmlHTTP.send w_ws_mensagem
 
  ' Check status of the http request (200 = succes)
  if xmlHTTP.status = 200 then
    w_cd_resposta = xmlHTTP.responseText
   else
    ShowHTML "<HR>Failed to get SOAP response : " & xmlHTTP.status
    Response.End()
  end if
  
  w_resposta = xmlHTTP.responsetext
  ShowHTML w_cd_resposta

  Set w_ws_servidor    = Nothing
  Set w_ws_url         = Nothing
  Set w_ws_acao        = Nothing
  Set w_ws_mensagem    = Nothing
  Set w_cd_resposta = Nothing
  Set w_resposta = Nothing
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Exibe orientações sobre o processo de importação
REM -------------------------------------------------------------------------
Sub Help
  Cabecalho
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  BodyOpen "onLoad='document.focus()';"
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""90%"">"
  ShowHTML "<tr valign=""top"">"
  ShowHTML "  <td><font size=2>"
  ShowHTML "    <p align=""justify"">Esta tela tem o objetivo de atualizar os dados orçamentários e financeiros"
  ShowHTML "        da tabela de programas e ações do PPA, através da importação de arquivo extraído do SIAFI."
  ShowHTML "    <p align=""justify"">A atualização está restrita aos dados sobre dotação autorizada, total empenhado e total liquidado."
  ShowHTML "    <p align=""justify"">Para ser executada corretamente, a importação deve cumprir os passos abaixo."
  ShowHTML "    <ol>"
  ShowHTML "    <p align=""justify""><b>FASE 1 - Preparação do arquivo a ser importado:</b><br></p>"
  ShowHTML "      <li>Use o módulo extrator do SIAFI para obter uma planilha Excel (extensão XLS), <u>exatamente igual</u> à exibida neste"
  ShowHTML "          " & LinkArquivo("HL", w_cliente, "SIAFI_exemplo.xls" , "ExemploSIAFI", null, "exemplo", null) & ";"
  ShowHTML "      <li>Abra a planilha gerada no passo anterior com o Excel e use a opção ""Arquivo -> Salvar como"". Escolha o nome que desejar"
  ShowHTML "          para o arquivo e, na lista ""Salvar como tipo"", escolha a opção ""<b>CSV (Separado por vírgulas) (*.csv)</b>""; "
  ShowHTML "      <li> Feche o "
  ShowHTML "          Excel e renomeie a extensão do arquivo, de CSV para TXT. Após cumprir este passo, você deverá ter um arquivo com extensão TXT, como o deste "
  ShowHTML "          " & LinkArquivo("HL", w_cliente, "SIAFI_exemplo.TXT" , "ExemploSIAFI", null, "exemplo", null) & ";"
  ShowHTML "    <p align=""justify""><b>FASE 2 - Importação do arquivo e atualização dos dados:</b><br></p>"
  ShowHTML "      <li>Na tela anterior, clique sobre a operação ""Incluir"";"
  ShowHTML "      <li>Quando a tela de inclusão for apresentada, preencha o formulário seguindo as instruções disponíveis em cada campo "
  ShowHTML "          (passe o mouse sobre o campo desejado para o sistema exibir a instrução de preenchimento);"
  ShowHTML "      <li>Aguarde o término da importação e atualização dos dados. O sistema irá, numa única execução, transferir o arquivo "
  ShowHTML "          selecionado para o servidor, ler cada uma das suas linhas, verificar se os dados estão corretos e, em caso positivo, "
  ShowHTML "          atualizar os campos. Este processamento pode demorar alguns minutos. Não clique em nenhum botão até o sistema voltar para "
  ShowHTML "          para a listagem das importações já executadas;"
  ShowHTML "    <p align=""justify""><b>FASE 3 - Verificação do arquivo de registro:</b><br></p>"
  ShowHTML "      <li>Verifique se ocorreu erro na importação de alguma linha do arquivo de origem. Na lista de importações, existem três colunas: "
  ShowHTML "          ""Registros"" indica o número total de linhas do arquivo, ""Importados"" indica o número de linhas que atendeu às condições de importação "
  ShowHTML "          e que geraram atualização nos dados existentes, ""Rejeitados"" indica o número de linhas que foram descartadas pela validação; "
  ShowHTML "      <li>Verifique cada linha descartada pela rotina de importação. Clique sobre a operação ""Registro"" na coluna ""Operações"" e verifique "
  ShowHTML "          os erros detectados em cada uma das linhas descartadas. O conteúdo do arquivo é similar ao deste "
  ShowHTML "          " & LinkArquivo("HL", w_cliente, "SIAFI_registro.TXT" , "ExemploSIAFI", null, "exemplo", null) & ";"
  ShowHTML "      <li>Se desejar, gere um novo arquivo somente com as linhas descartadas, corrija os erros e faça uma nova importação."
  ShowHTML "    </ol>"
  ShowHTML "    <p align=""justify""><b>Observações:</b><br></p>"
  ShowHTML "    <ul>"
  ShowHTML "      <li>Para restringir a importação às linhas que realmente são úteis, abra o arquivo obtido no passo (3) com o Bloco de Notas (Notepad) "
  ShowHTML "          e remova as linhas que não disserem respeito aos programas e ações do PPA, não esquecendo de salvá-lo;"
  ShowHTML "      <li>Uma vez concluída uma importação, não há necessidade de você manter em seu computador/disquete o arquivo utilizado. O sistema "
  ShowHTML "          grava no servidor uma cópia do arquivo usado pela importação e uma cópia do arquivo de registro;"
  ShowHTML "      <li>Toda importação registra os dados de quem a executou, de quando ela foi executada, bem como os arquivos de origem e de registro; "
  ShowHTML "      <li>Não há como cancelar uma importação, nem de reverter os valores existentes antes da sua execução. Assim, certifique-se de que o "
  ShowHTML "          arquivo de origem está correto e que a importação deve realmente ser executada."
  ShowHTML "    </ul>"
  ShowHTML "    <p align=""justify""><b>Verificações dos dados:</b><br></p>"
  ShowHTML "    <ul>"
  ShowHTML "      <p align=""justify"">Uma linha do arquivo origem só gera atualização da tabela de programas e ações do PPA se atender aos seguintes critérios:</p>"
  ShowHTML "      <li>O código do programa deve estar na segunda posição da linha e deve conter 4 posições númericas;"
  ShowHTML "      <li>A código da ação deve estar na quarta posição da linha e deve conter entre 4 e 5 posições, sendo que as quatro primeiras são números;"
  ShowHTML "           e a quinta posição deve ser uma letra maiúscula "
  ShowHTML "      <li>A dotação autorizada deve estar na sexta posição da linha e deve estar na notação brasileira de valor (separador de milhar = ponto e de decimal = vírgula);"
  ShowHTML "      <li>O total empenhado deve estar na sétima posição da linha e deve estar na notação brasileira de valor (separador de milhar = ponto e de decimal = vírgula);"
  ShowHTML "      <li>O total liquidado deve estar na sétima posição da linha e deve estar na notação brasileira de valor (separador de milhar = ponto e de decimal = vírgula);"
  ShowHTML "      <li>O sistema só atualizará a tabela se encontrar um, e apenas um registro com o mesmo código de ação e programa;"
  ShowHTML "      <li>Cada posição da linha é separada pelo caracter ponto-e-vírgula;"
  ShowHTML "      <li>Os valores de cada posição <u>não</u> devem estar entre aspas simples nem duplas. Ex: <b>;1606;...</b> é válido, mas <b>;""1606"";...</b> e <b>;'1606';...</b> são inválidos; "
  ShowHTML "      <p align=""justify"">Qualquer situação diferente das relacionadas acima causará a rejeição da linha.</p>"
  ShowHTML "    <ul>"
  ShowHTML "  </td>"
  ShowHTML "</tr>"
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Procedimento que executa as operações de BD
REM -------------------------------------------------------------------------
Public Sub Grava
  CONST ForReading = 1, ForWriting = 2, ForAppend = 8
  CONST TristateUsedefault = -2 'Abre o arquivo usando o sistema default
  CONST TristateTrue = -1 'Abre o arquivo como Unicode
  CONST TristateFalse = 0 'Abre o arquivo como ASCII

  Dim w_Null, w_ws_mensagem, FS, F2, w_linha, w_chave_nova
  Dim w_arquivo_processamento, w_tamanho_recebido, w_tipo_recebido, w_nome_recebido
  Dim w_arquivo_registro, w_arquivo_rejeicao, w_tamanho_registro, w_tipo_registro, w_nome_registro
  Dim w_registros, w_importados, w_rejeitados, w_situacao, w_result, w_maximo, field
  Dim i, j, w_atual
  Dim w_no, w_texto,w_elemento, w_limite
  
  Dim w_campo, w_unidade, w_programa, w_ws_acao, w_dotacao, w_empenhado, w_liquidado

  Cabecalho
  ShowHTML "</HEAD>"
  BodyOpenClean "onLoad=document.focus();"
  
  AbreSessao    
  Select Case SG
    Case "IMPARQ"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          Set FS = CreateObject("Scripting.FileSystemObject")
          ' Verifica se o tamanho das fotos está compatível com  o limite de 100KB.
          If ul.State = 0 Then
             w_maximo     = ul.Texts.Item("w_upload_maximo")
             For Each Field in ul.Files.Items
                If Field.Length > 0 Then
                   ' Verifica se o tamanho das fotos está compatível com  o limite de 100KB. 
                   If cDbl(Field.Length) > cDbl(w_maximo) Then 
                      ScriptOpen("JavaScript") 
                      ShowHTML "  alert('Atenção: o tamanho máximo do arquivo não pode exceder " & cDbl(w_maximo)/1024 & " KBytes!');" 
                      ShowHTML "  history.back(1);" 
                      ScriptClose 
                      Response.End() 
                      exit sub 
                   End If 
                    
                   ' Configura o nome dos arquivo recebido e do arquivo registro
                   w_arquivo_processamento = replace(FS.GetTempName(),".tmp",Mid(Field.FileName,Instr(Field.FileName,"."),30))
                   w_tamanho_recebido = Field.Length
                   w_tipo_recebido    = Field.ContentType
                   w_nome_recebido    = Field.FileName
                   Field.SaveAs conFilePhysical & w_cliente & "\" & w_arquivo_processamento
                   w_arquivo_rejeicao = replace(w_arquivo_processamento,Mid(w_arquivo_processamento,Instr(w_arquivo_processamento,"."),30),"") & "r.txt"
                End If
             Next                 
             
             ' Gera o arquivo registro da importação
             Set FS = CreateObject("Scripting.FileSystemObject")
             Set F1 = FS.CreateTextFile(w_caminho & w_arquivo_rejeicao)
          
             'Abre o arquivo recebido para gerar o arquivo registro
             Set F2 = Server.CreateObject("Msxml2.DOMDocument.3.0")
             F2.async = False
             If F2.load (conFilePhysical & w_cliente & "\" & w_arquivo_processamento) Then
             
                ' Recupera os dados do esquema a ser importado
                DB_GetEsquema RS, w_cliente, null, ul.Texts.Item("w_sq_esquema"), w_sq_modulo, null, null, null, null, null, null, null
                
                ' Verifica se o nó raiz consta do arquivo
                If F2.selectNodes(RS("no_raiz")).length = 0 Then
                   ScriptOpen("JavaScript")
                   ShowHTML "  alert('Atenção: Nó raiz não localizado no arquivo XML!');"
                   ShowHTML "  history.back(1);"
                   ScriptClose
                   Response.End()
                   exit sub
                Else
                   set w_no = F2.documentElement.selectSingleNode(RS("no_raiz"))
                   
                   ' Recupera cada uma das tabelas referenciadas pelo esquema
                   DB_GetEsquemaTabela RS1, null, ul.Texts.Item("w_sq_esquema"), null
                   RS1.Sort = "ordem, nm_tabela, or_coluna"
                   w_reg  = 0
                   w_erro = 0
                   While Not RS1.EOF
                      ' Recupera cada um dos campos referenciados pelo elemento
                      DB_GetEsquemaAtributo RS2, null, RS1("sq_esquema_tabela"), null, null
                      RS2.Sort = "ordem"
                      w_cont = 0
                      While Not RS2.EOF                     
                         w_cont = w_cont + 1
                         w_atributo(w_cont) = RS2("campo_externo")
                         RS2.MoveNext
                      Wend
                      w_limite = w_cont
                      RS2.Close
                      If w_atual <> RS1("nm_tabela") Then
                         set w_elemento = w_no.selectNodes(RS("no_raiz")&"/"&RS1("elemento"))
                         with w_elemento
                            for i = 0 to .length - 1 
                               w_cont  = 1
                               w_reg   = w_reg + 1
                               for j = 0 to .item(i).childNodes.length - 1
                                  ' Recupera cada um dos campos referenciados pelo elemento
                                  'DB_GetEsquemaAtributo RS2, null, RS1("sq_esquema_tabela"), null, null
                                  'RS2.Filter = "ordem=" & j+1 & " and campo_externo='" & .item(i).childNodes.item(j).nodename & "'"
                                  'If Not RS2.EOF Then
                                     'If RS2("campo_externo") = .item(i).childNodes.item(j).nodename Then
                                         ' Valida o campo
                                         w_campo = .item(i).childNodes.item(j).text
                                         'If RS2("obrigatorio") = "S" Then
                                         '   w_result = fValidate(1, w_campo, RS2("nm_coluna"), "", 1, 1, cStr(RS2("tamanho")), "1", "1")
                                         'Else
                                         '   w_result = fValidate(1, w_campo, RS2("nm_coluna"), "", "", 1, cStr(RS2("tamanho")), "1", "1")
                                         'End If
                                         'If w_result > "" Then 
                                         '   F1.WriteLine RS2("nm_coluna") & " E => " & .item(i).childNodes.item(j).nodename & " = '" & .item(i).childNodes.item(j).text & "' "
                                         '   w_erro = 1
                                         'Else
                                            w_name(w_cont) = .item(i).childNodes.item(j).nodename
                                            If w_atributo(w_cont) = w_name(w_cont) Then
                                               If uCase(w_campo) = "TRUE" Then
                                                  w_param(w_cont) = "S"
                                               ElseIf uCase(w_campo) = "FALSE" Then
                                                  w_param(w_cont) = "N"
                                               Else
                                                  w_param(w_cont) = w_campo
                                               End If
                                            Else
                                               w_param(w_cont) = ""
                                               j = j - 1
                                            End If 
                                            w_cont     = w_cont + 1
                                         'End If
                                     'End If
                                  'End If
                                  If w_cont > w_limite Then
                                     Exit For
                                  End If
                               next
                               'Response.Write RS1("nm_tabela")
                               'Response.End
                               w_resultado = ""
                               select case RS1("nm_tabela")
                                  case "IS_PPA_ESFERA"          DML_PutXMLEsfera                w_resultado, w_param(1), w_param(2), "S" : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_PPA_PERIODICIDADE"   DML_PutXMLPeriodicidade_PPA     w_resultado, w_param(1), w_param(2), "S" : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_PPA_UNIDADE_MEDIDA"  DML_PutXMLUnidade_Medida_PPA    w_resultado, w_param(1), w_param(2), "S" : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_PPA_ORGAO"           DML_PutXMLOrgao_PPA             w_resultado, w_param(1), w_param(2), w_param(3), w_param(4), "S" : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_PPA_ORGAO_SIORG"     DML_PutXMLOrgao_Siorg_PPA       w_resultado, w_param(1), w_param(2), w_param(3), w_param(4), w_param(5), "S" : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_PPA_UNIDADE"         DML_PutXMLUnidade_PPA           w_resultado, w_param(1), w_param(2), w_param(3), w_param(4), w_param(5) : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_PPA_TIPO_ACAO"       DML_PutXMLTipo_Acao_PPA         w_resultado, w_param(1), w_param(2), "S" : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_PPA_TIPO_DESPESA"    DML_PutXMLTipo_Despesa          w_resultado, w_param(1), w_param(2), "S" : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_TIPO_ATUALIZACAO"    DML_PutXMLTipo_Atualizacao      w_resultado, w_param(1), w_param(2), "S" : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_PPA_TIPO_PROGRAMA"   DML_PutXMLTipo_Programa_PPA     w_resultado, w_param(1), w_param(2), "S" : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_TIPO_INCLUSAO_ACAO"  DML_PutXMLTipo_Inclusao_Acao    w_resultado, w_param(1), w_param(2), "S" : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_PPA_NATUREZA"        DML_PutXMLNatureza              w_resultado, w_param(1), w_param(2), w_param(3), "S" : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_PPA_FUNCAO"          DML_PutXMLFuncao                w_resultado, w_param(1), w_param(2), "S" : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_PPA_SUBFUNCAO"       DML_PutXMLSubFuncao             w_resultado, w_param(1), w_param(2), w_param(3)  : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_PPA_FONTE"           DML_PutXMLFonte_PPA             w_resultado, w_param(1), w_param(2), w_param(3), w_param(4) : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_REGIAO"              DML_PutXMLREGIAO                w_resultado, w_param(1), w_param(2), w_param(3), w_param(4) : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_MUNICIPIO"           DML_PutXMLMunicipio             w_resultado, w_param(1), w_param(2), w_param(3) : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_PPA_PRODUTO"         DML_PutXMLProduto_PPA           w_resultado, w_param(1), w_param(2), "S" : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_PPA_PROGRAMA"        DML_PutXMLPrograma_PPA          w_resultado, w_cliente,  w_ano,      w_param(1), w_param(2), w_param(3), w_param(4), w_param(5), w_param(6), w_param(7), w_param(8), w_param(9), w_param(10), w_param(11), w_param(12), w_param(13), w_param(14), w_param(15), w_param(16), w_param(17), w_param(18) : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_PPA_INDICADOR"       DML_PutXMLIndicador_PPA         w_resultado, w_cliente,  w_ano,      w_param(1), w_param(2), w_param(3), w_param(4), w_param(5), w_param(6), w_param(7), w_param(8), w_param(9), w_param(10), w_param(11), w_param(12), w_param(13), w_param(14), w_param(15), w_param(16), w_param(17), w_param(18), w_param(19), w_param(20), w_param(21), w_param(22), w_param(23), w_param(24), w_param(25), w_param(26) : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_PPA_ACAO"            DML_PutXMLAcao_PPA              w_resultado, w_cliente,  w_ano,      w_param(1), w_param(2), w_param(3), w_param(4), w_param(5), w_param(6), w_param(7), w_param(8), w_param(9), w_param(10), w_param(11), w_param(12), w_param(13), w_param(14), w_param(15), w_param(16), w_param(17), w_param(18), w_param(19), w_param(20), w_param(21), w_param(22), w_param(23), w_param(24), w_param(25), w_param(26), w_param(27), w_param(28), w_param(29), w_param(30), w_param(31), w_param(32), w_param(33), w_param(34), w_param(35), w_param(36), w_param(37), w_param(38), w_param(39), w_param(40), w_param(41), w_param(42), w_param(43), w_param(44), w_param(45) : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_PPA_LOCALIZADOR"     DML_PutXMLLocalizador_PPA       w_resultado, w_cliente,  w_ano,      w_param(1), w_param(2), w_param(3), w_param(4), w_param(5), w_param(6), w_param(7), w_param(8), w_param(9), w_param(10), w_param(11), w_param(12), w_param(13), w_param(14), w_param(15), w_param(16), w_param(17), w_param(18), w_param(19), w_param(20), w_param(21), w_param(22) : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_PPA_DADO_FISICO"     DML_PutXMLDadoFisico_PPA        w_resultado,  w_cliente,  w_ano,      w_param(1), w_param(2), w_param(3), w_param(4), w_param(5), w_param(6), w_param(7), w_param(8), w_param(9), w_param(10), w_param(11) : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_PPA_DADO_FINANCEIRO" DML_PutXMLDadoFinanceiro_PPA    w_resultado, w_cliente,  w_ano,      w_param(1), w_param(2), w_param(3), w_param(4), w_param(5), w_param(6), w_param(7), w_param(8), w_param(9), w_param(10), w_param(11), w_param(12), w_param(13) : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_SIG_BASE_GEOGRAFICA" DML_PutXMLBase_Geografica       w_resultado, w_param(1), w_param(2), "S" : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_SIG_FONTE"           DML_PutXMLFonte_SIG             w_resultado, w_param(1), w_param(2), w_param(3), w_param(4), w_param(5), "S" : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_SIG_OPCAO_ESTRAT"    DML_PutXMLOpcao_Estrat          w_resultado, w_param(1), w_param(2), "S" : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_SIG_PERIODICIDADE"   DML_PutXMLPeriodicidade         w_resultado, w_param(1), w_param(2), "S" : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_SIG_PRODUTO"         DML_PutXMLProduto_SIG           w_resultado, w_param(1), w_param(2), "S" : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_SIG_TIPO_ACAO"       DML_PutXMLTipo_Acao             w_resultado, w_param(1), w_param(2), "S" : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_SIG_TIPO_ORGAO"      DML_PutXMLTipo_Orgao_SIG        w_resultado, w_param(1), w_param(2), "S" : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_SIG_TIPO_PROGRAMA"   DML_PutXMLTipo_Programa         w_resultado, w_param(1), w_param(2), "S" : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_SIG_TIPO_RESTRICAO"  DML_PutXMLTipo_Restricao        w_resultado, w_param(1), w_param(2), "S" : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_SIG_TIPO_SITUACAO"   DML_PutXMLTipo_Situacao         w_resultado, w_param(1), w_param(2), w_param(3), "S" : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_SIG_UNIDADE_MEDIDA"  DML_PutXMLUnidade_Medida_SIG    w_resultado, w_param(1), w_param(2), w_param(3), "S" : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_SIG_MACRO_OBJETIVO"  DML_PutXMLMacro_Objetivo        w_resultado, w_param(1), w_param(2), w_param(3), "S" : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_SIG_ORGAO"           DML_PutXMLOrgao_SIG             w_resultado, w_param(1), w_param(2), w_param(3), w_param(4), "---", "S" : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_SIG_UNIDADE"         DML_PutXMLUnidade_SIG           w_resultado, w_param(1), w_param(2), w_param(3), w_param(4), w_param(5), w_param(6) : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_SIG_PROGRAMA"        DML_PutXMLPrograma_SIG          w_resultado, w_cliente,  w_param(1), w_param(2), w_param(3), w_param(4), w_param(5), w_param(6), w_param(7), w_param(8), w_param(9), w_param(10), w_param(11), w_param(12), w_param(13), w_param(14), w_param(15), w_param(16), w_param(17), w_param(18), w_param(19), w_param(20), w_param(21), w_param(22), w_param(23), w_param(24), w_param(25), w_param(26), w_param(27), w_param(28), w_param(29), w_param(30), w_param(31) : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_SIG_INDICADOR"       DML_PutXMLIndicador_SIG         w_resultado, w_cliente,  w_param(1), w_param(2), w_param(3), w_param(4), w_param(5), w_param(6), w_param(7), w_param(8), w_param(9), w_param(10), w_param(11), w_param(12), w_param(13), w_param(14), w_param(15), w_param(16), w_param(17), w_param(18), w_param(19), w_param(20), w_param(21), w_param(22), w_param(23), w_param(24), w_param(25) : If w_resultado > "" Then RegistraErro : End If                               
                                  case "IS_SIG_ACAO"   
                                     If w_param(38) = "N" Then 'Despreza se for restos a pagar
                                        DML_PutXMLAcao_SIG              w_resultado, w_cliente,  w_param(1), w_param(2), w_param(3), w_param(4), w_param(5), w_param(6), mid(w_param(7),3), w_param(8), w_param(9), w_param(10), w_param(11), w_param(12), w_param(13), w_param(14), w_param(15), w_param(16), w_param(17), w_param(18), w_param(19), w_param(20), w_param(21), w_param(22), w_param(23), w_param(24), w_param(25), w_param(26), w_param(27), w_param(28), w_param(29), w_param(30), w_param(31), w_param(32), w_param(33), w_param(34), w_param(35), w_param(36), w_param(37), w_param(38), w_param(39), w_param(40), w_param(41), w_param(42), w_param(43), w_param(44) : If w_resultado > "" Then RegistraErro : End If
                                     End If
                                  case "IS_SIG_DADO_FISICO"     DML_PutXMLDadoFisico_SIG        w_resultado, w_cliente,  w_param(1), w_param(2), w_param(3), w_param(4), w_param(5), w_param(6), w_param(7), w_param(8), w_param(9), w_param(10), w_param(11), w_param(12), w_param(13), w_param(14), w_param(15), w_param(16), w_param(17), w_param(18), w_param(19), w_param(20), w_param(21), w_param(22), w_param(23), w_param(24), w_param(25), w_param(26), w_param(27), w_param(28), w_param(29), w_param(30), w_param(31), w_param(32), w_param(33), w_param(34), w_param(35), w_param(36), w_param(37), w_param(38), w_param(39), w_param(40), w_param(41), w_param(42), w_param(43), w_param(44), w_param(45), w_param(46), w_param(47) : If w_resultado > "" Then RegistraErro : End If
                                  case "IS_SIG_DADO_FINANCEIRO" DML_PutXMLDadoFinanceiro_SIG    w_resultado, w_cliente,  w_param(1), w_param(2), w_param(3), w_param(4), w_param(5), w_param(6), w_param(7), w_param(8), w_param(9), w_param(10), w_param(11), w_param(12), w_param(13), w_param(14), w_param(15), w_param(16), w_param(17), w_param(18), w_param(19), w_param(20), w_param(21), w_param(22), w_param(23), w_param(24), w_param(25), w_param(26), w_param(27), w_param(28), w_param(29), w_param(30), w_param(31), w_param(32), w_param(33), w_param(34), w_param(35), w_param(36), w_param(37), w_param(38), w_param(39), w_param(40), w_param(41), w_param(42), w_param(43), w_param(44), w_param(45), w_param(46), w_param(47), w_param(48) : If w_resultado > "" Then RegistraErro : End If
                               end select
                            next
                         End With
                      End If
                      w_atual = RS1("nm_tabela")
                      RS1.MoveNext
                   Wend
                   Set F2 = Nothing
                   F1.WriteLine "     Registros lidos: " & w_reg
                   F1.WriteLine "   Registros aceitos: " & w_reg - w_erro
                   F1.WriteLine "Registros rejeitados: " & w_erro
                   F1.Close
                   
                   w_arquivo_registro = "Arquivoregistro"&Mid(w_arquivo_rejeicao,Instr(w_arquivo_rejeicao,"."),30)
                   Set F1 = FS.GetFile(w_caminho & w_arquivo_rejeicao)
                   w_tamanho_registro = F1.size
                   w_tipo_registro    = ""
                   ' Grava o resultado da importação no banco de dados
                   DML_PutDcOcorrencia O, _
                       ul.Texts.Item("w_sq_esquema"), w_cliente,   w_usuario,     ul.Texts.Item("w_data_arquivo"), _
                       w_nome_recebido, _
                       w_arquivo_processamento, w_tamanho_recebido,  w_tipo_recebido, _
                       w_arquivo_registro,      w_arquivo_rejeicao, w_tamanho_registro, w_tipo_registro, _
                       w_reg,       w_erro,    w_nome_recebido, w_arquivo_registro
                End If
             Else
                ScriptOpen("JavaScript")
                ShowHTML "  alert('Atenção: arquivo XML não pode ser carregado!');"
                ShowHTML "  history.back(1);"
                ScriptClose
                Response.End()
                exit sub
             End If
          Else
             ScriptOpen "JavaScript" 
             ShowHTML "  alert('ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!');" 
             ScriptClose 
          End If
          
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&w_chave=" & ul.Texts.Item("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & "ISSIGIMP" & MontaFiltro("UL") & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "ISSIGIMP"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          
          'ExibeVariaveis
          DML_PutEsquema O, w_cliente, Request("w_sq_esquema"), w_sq_modulo, Request("w_nome"), Request("w_descricao"), Request("w_tipo"), _
                            Request("w_ativo"), Request("w_formato"), Request("w_ws_servidor"), Request("w_ws_url"), _
                            Request("w_ws_acao"), Request("w_ws_mensagem"), Request("w_no_raiz")
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=L&w_sq_esquema=" & Request("w_sq_esquema") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
          ScriptClose
      Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "ISSIGEXP"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          
          'ExibeVariaveis
          DML_PutEsquema O, w_cliente, Request("w_sq_esquema"), w_sq_modulo, Request("w_nome"), Request("w_descricao"), Request("w_tipo"), _
                            Request("w_ativo"), Request("w_formato"), Request("w_ws_servidor"), Request("w_ws_url"), _
                            Request("w_ws_acao"), Request("w_ws_mensagem"), Request("w_no_raiz")
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=L&w_sq_esquema=" & Request("w_sq_esquema") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
          ScriptClose
      Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "ISSIGTAB"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          
          'ExibeVariaveis
          If O = "I" Then
             For i = 1 To Request.Form("w_sq_tabela").Count
                 If Request.Form("w_sq_tabela")(i) > "" Then
                    DML_PutEsquemaTabela O, null, Request("w_sq_esquema"), Request.Form("w_sq_tabela")(i), Request.Form("w_ordem")(i),  Request.Form("w_elemento")(i)
                 End If
             Next
          ElseIf O = "A" Then
             DML_PutEsquemaTabela O, Request("w_sq_esquema_tabela"), Request("w_sq_esquema"), null, Request("w_ordem"), Request("w_elemento")
          ElseIf O = "E" Then
             DML_PutEsquemaTabela O, Request("w_sq_esquema_tabela"), null, null, null, null
          End If
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=L&w_sq_esquema=" & Request("w_sq_esquema") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_menu=" & w_menu &  MontaFiltro("GET") & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "ISSIGMAP"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          
          'ExibeVariaveis
          DML_PutEsquemaAtributo "E", null, Request("w_sq_esquema_tabela"), null, null, null
          If O = "I" Then
             For i = 1 To Request.Form("w_sq_coluna").Count
                 If Request.Form("w_sq_coluna")(i) > "" Then
                    DML_PutEsquemaAtributo O, null, Request("w_sq_esquema_tabela"), Request.Form("w_sq_coluna")(i), Request.Form("w_ordem")(i),  Request.Form("w_campo_externo")(i)
                 End If
             Next
          End If
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & w_pagina & "Tabela" & "&O=L&w_sq_esquema=" & Request("w_sq_esquema") & "&w_sq_esquema_tabela=" & Request("w_sq_esquema_tabela") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & "ISSIGIMP" & MontaFiltro("GET") & "';"
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

  Set w_no                    = Nothing 
  Set w_texto                 = Nothing 
  Set w_elemento              = Nothing 
  Set w_result                = Nothing 
  Set w_arquivo_processamento = Nothing 
  Set w_tamanho_recebido      = Nothing 
  Set w_tipo_recebido         = Nothing
  Set w_arquivo_registro      = Nothing 
  Set w_arquivo_rejeicao      = Nothing 
  Set w_tamanho_registro      = Nothing 
  Set w_tipo_registro         = Nothing
  Set w_registros             = Nothing
  Set w_importados            = Nothing
  Set w_rejeitados            = Nothing
  Set w_situacao              = Nothing
  Set w_chave_nova            = Nothing
  Set F2                      = Nothing
  Set w_linha                 = Nothing
  Set FS                      = Nothing
  Set w_ws_mensagem           = Nothing
  Set w_Null                  = Nothing
End Sub
REM -------------------------------------------------------------------------
REM Fim do procedimento que executa as operações de BD
REM =========================================================================

REM =========================================================================
REM Rotina de registro dos erros
REM -------------------------------------------------------------------------
Sub RegistraErro
  Dim i, j
  j = 0
  For Each i IN w_name
     If i > "" Then F1.WriteLine i & ": [" & w_param(j) & "]" End If
     j = j + 1
  Next
  w_erro = w_erro + 1
  F1.WriteLine w_resultado
  F1.WriteLine "------------------------------------------------------------------------"
  Set i  = Nothing
End Sub

REM =========================================================================
REM Rotina principal
REM -------------------------------------------------------------------------
Sub Main
  Select Case Par
    Case "INICIAL"      Inicial
    Case "TESTE"        Teste
    Case "HELP"         Help
    Case "GRAVA"        Grava
    Case "TABELA"       Tabela
    Case "MAPEAMENTO"   Mapeamento
    Case "IMPORTACAO"   Importacao
    Case "EXPORTACAO"   Exportacao
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