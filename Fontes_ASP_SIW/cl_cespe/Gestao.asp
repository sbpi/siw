<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Geral.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Gerencial.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Cliente.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Seguranca.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Link.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_EO.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/mod_ac/DB_Contrato.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DML_Projeto.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DML_Solic.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE FILE="VisualProjeto.asp" -->
<!-- #INCLUDE FILE="VisualFormulario.asp" -->
<!-- #INCLUDE FILE="ValidaProjeto.asp" -->
<!-- #INCLUDE FILE="DML_Projeto.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/mod_pd/DB_Viagem.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/cp_upload/_upload.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /Gestao.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Gerencia o módulo de projetos
REM Mail     : alex@sbpi.com.br
REM Criacao  : 15/10/2003 12:25
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
Dim dbms, sp, RS, RS1, RS_menu, RS2, RS3
Dim P1, P2, P3, P4, TP, SG
Dim R, O, w_Cont, w_Reg, w_Pagina, w_Disabled, w_TP, w_classe, w_submenu, w_filtro, w_copia
Dim w_Assinatura
Dim p_ativo, p_solicitante, p_prioridade, p_unidade, p_proponente, p_ordena
Dim p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_projeto, p_atividade
Dim p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc
Dim w_troca,w_cor, w_filter, w_cliente, w_usuario, w_gestor, w_menu
Dim w_sq_pessoa
Dim ul,File
Dim w_dir, w_dir_volta, UploadId
Set RS  = Server.CreateObject("ADODB.RecordSet")
Set RS1 = Server.CreateObject("ADODB.RecordSet")
Set RS2 = Server.CreateObject("ADODB.RecordSet")
Set RS3 = Server.CreateObject("ADODB.RecordSet")
Set RS_Menu = Server.CreateObject("ADODB.RecordSet")

w_troca            = Request("w_troca")
w_copia            = Request("w_copia")
p_projeto          = uCase(Request("p_projeto"))
p_atividade        = uCase(Request("p_atividade"))
p_ativo            = uCase(Request("p_ativo"))
p_solicitante      = uCase(Request("p_solicitante"))
p_prioridade       = uCase(Request("p_prioridade"))
p_unidade          = uCase(Request("p_unidade"))
p_proponente       = uCase(Request("p_proponente"))
p_ordena           = uCase(Request("p_ordena"))
p_ini_i            = uCase(Request("p_ini_i"))
p_ini_f            = uCase(Request("p_ini_f"))
p_fim_i            = uCase(Request("p_fim_i"))
p_fim_f            = uCase(Request("p_fim_f"))
p_atraso           = uCase(Request("p_atraso"))
p_chave            = uCase(Request("p_chave"))
p_assunto          = uCase(Request("p_assunto"))
p_pais             = uCase(Request("p_pais"))
p_regiao           = uCase(Request("p_regiao"))
p_uf               = uCase(Request("p_uf"))
p_cidade           = uCase(Request("p_cidade"))
p_usu_resp         = uCase(Request("p_usu_resp"))
p_uorg_resp        = uCase(Request("p_uorg_resp"))
p_palavra          = uCase(Request("p_palavra"))
p_prazo            = uCase(Request("p_prazo"))
p_fase             = uCase(Request("p_fase"))
p_sqcc             = uCase(Request("p_sqcc"))
  
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

w_Pagina     = "gestao.asp?par="
w_Dir        = "cl_cespe/"
w_dir_volta  = "../"  
w_Disabled   = "ENABLED"

If SG="PJVISUAL" and O = "A" Then
   O = "L"
ElseIf O = "" Then 
   ' Se for acompanhamento, entra na filtragem
   If P1 = 3 Then O = "P" Else O = "L" End If
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

w_cliente  = RetornaCliente() ' Retorna o código do cliente para o usuário logado
w_usuario  = RetornaUsuario() ' Retorna o código do usuário logado
w_menu     = RetornaMenu(w_cliente, "PJCADP") ' Retorna o código do menu

Set ul            = New ASPForm

If Request("UploadID") > "" Then
   UploadID = Request("UploadID")
Else
   UploadID = ul.NewUploadID
End If

If InStr(uCase(Request.ServerVariables("http_content_type")),"MULTIPART/FORM-DATA") > 0 Then  
   Server.ScriptTimeout = 2000
   ul.SizeLimit = &HA00000
   If UploadID > 0 then
      ul.UploadID = UploadID
   End If
   P1           = ul.Texts.Item("P1")
   P2           = ul.Texts.Item("P2")
   P3           = ul.Texts.Item("P3")
   P4           = ul.Texts.Item("P4")
   TP           = ul.Texts.Item("TP")
   R            = uCase(ul.Texts.Item("R"))
   w_Assinatura = uCase(ul.Texts.Item("w_Assinatura"))
   
   
   If InStr(SG, "ANEXO") > 0 or InStr(SG, "PARC") > 0 or InStr(SG, "REPR") > 0 Then
      If InStr("IG",O) = 0 and Request("w_chave_aux") = "" Then O = "L" End If  
   ElseIf InStr(SG, "ENVIO") > 0 Then
      O = "V"  
   ElseIf O = "" Then  
      ' Se for acompanhamento, entra na filtragem  
      If P1 = 3 Then O = "P" Else O = "L" End If  
   End If  
   
End If

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
Set w_dir         = Nothing
Set w_dir_volta   = Nothing
Set w_copia       = Nothing
Set w_filtro      = Nothing
Set w_menu        = Nothing
Set w_usuario     = Nothing
Set w_gestor      = Nothing
Set w_cliente     = Nothing
Set w_filter      = Nothing
Set w_cor         = Nothing
Set ul            = Nothing
Set File          = Nothing
Set w_sq_pessoa   = Nothing
Set w_troca       = Nothing
Set w_submenu     = Nothing
Set w_reg         = Nothing
Set p_ini_i       = Nothing
Set p_ini_f       = Nothing
Set p_fim_i       = Nothing
Set p_fim_f       = Nothing
Set p_atraso      = Nothing
Set p_unidade     = Nothing
Set p_prioridade  = Nothing
Set p_solicitante = Nothing
Set p_ativo       = Nothing
Set p_proponente  = Nothing
Set p_projeto     = Nothing
Set p_atividade   = Nothing
Set p_ordena      = Nothing
Set p_chave       = Nothing 
Set p_assunto     = Nothing 
Set p_pais        = Nothing 
Set p_regiao      = Nothing 
Set p_uf          = Nothing 
Set p_cidade      = Nothing 
Set p_usu_resp    = Nothing 
Set p_uorg_resp   = Nothing 
Set p_palavra     = Nothing 
Set p_prazo       = Nothing 
Set p_fase        = Nothing
Set p_sqcc        = Nothing

Set RS            = Nothing
Set RS1           = Nothing
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

REM =========================================================================
REM Rotina de visualização resumida dos registros
REM -------------------------------------------------------------------------
Sub Inicial
  Dim w_titulo, w_total, w_parcial
  
  If O = "L" Then
     If Instr(uCase(R),"GR_") > 0 Then
        w_filtro = ""
        If p_projeto > ""  Then 
           DB_GetSolicData RS, p_projeto, "PJGERAL"
           w_filtro = w_filtro & "<tr valign=""top""><TD align=""right"">Projeto <TD>[<b>" & RS("titulo") & "</b>]"
        End If
        If p_sqcc > ""  Then 
           DB_GetCCData RS, p_sqcc
           w_filtro = w_filtro & "<tr valign=""top""><TD align=""right"">Classificação <TD>[<b>" & RS("nome") & "</b>]"
        End If
        If p_chave       > ""  Then w_filtro = w_filtro & "<tr valign=""top""><TD align=""right"">Demanda nº <TD>[<b>" & p_chave & "</b>]" End If
        If p_prazo       > ""  Then w_filtro = w_filtro & " <tr valign=""top""><TD align=""right"">Prazo para conclusão até<TD>[<b>" & FormatDateTime(DateAdd("d",p_prazo,Date()),1) & "</b>]" End If
        If p_solicitante > ""  Then
           DB_GetPersonData RS, w_cliente, p_solicitante, null, null
           w_filtro = w_filtro & "<tr valign=""top""><TD align=""right"">Gerente <TD>[<b>" & RS("nome_resumido") & "</b>]"
        End If
        If p_unidade     > ""  Then 
           DB_GetUorgData RS, p_unidade
           w_filtro = w_filtro & "<tr valign=""top""><TD align=""right"">Unidade responsável <TD>[<b>" & RS("nome") & "</b>]"
        End If
        If p_usu_resp > ""  Then
           DB_GetPersonData RS, w_cliente, p_usu_resp, null, null
           w_filtro = w_filtro & "<tr valign=""top""><TD align=""right"">Executor <TD>[<b>" & RS("nome_resumido") & "</b>]"
        End If
        If p_uorg_resp > ""  Then 
           DB_GetUorgData RS, p_uorg_resp
           w_filtro = w_filtro & "<tr valign=""top""><TD align=""right"">Unidade atual <TD>[<b>" & RS("nome") & "</b>]"
        End If
        If p_pais > ""  Then 
           DB_GetCountryData RS, p_pais
           w_filtro = w_filtro & "<tr valign=""top""><TD align=""right"">País <TD>[<b>" & RS("nome") & "</b>]"
        End If
        If p_regiao > ""  Then 
           DB_GetRegionData RS, p_regiao
           w_filtro = w_filtro & "<tr valign=""top""><TD align=""right"">Região <TD>[<b>" & RS("nome") & "</b>]"
        End If
        If p_uf > ""  Then 
           DB_GetStateData RS, p_pais, p_uf
           w_filtro = w_filtro & "<tr valign=""top""><TD align=""right"">Estado <TD>[<b>" & RS("nome") & "</b>]"
        End If
        If p_cidade > ""  Then 
           DB_GetCityData RS, p_cidade
           w_filtro = w_filtro & "<tr valign=""top""><TD align=""right"">Cidade <TD>[<b>" & RS("nome") & "</b>]"
        End If
        If p_prioridade  > ""  Then w_filtro = w_filtro & "<tr valign=""top""><TD align=""right"">Prioridade <TD>[<b>" & RetornaPrioridade(p_prioridade) & "</b>]" End If
        If p_proponente  > ""  Then w_filtro = w_filtro & "<tr valign=""top""><TD align=""right"">Proponente <TD>[<b>" & p_proponente & "</b>]"                    End If
        If p_assunto     > ""  Then w_filtro = w_filtro & "<tr valign=""top""><TD align=""right"">Título <TD>[<b>" & p_assunto & "</b>]"                           End If
        If p_palavra     > ""  Then w_filtro = w_filtro & "<tr valign=""top""><TD align=""right"">Palavras-chave <TD>[<b>" & p_palavra & "</b>]"                   End If
        If p_ini_i       > ""  Then w_filtro = w_filtro & "<tr valign=""top""><TD align=""right"">Início <TD>[<b>" & p_ini_i & "-" & p_ini_f & "</b>]"             End If
        If p_fim_i       > ""  Then w_filtro = w_filtro & "<tr valign=""top""><TD align=""right"">Fim <TD>[<b>" & p_fim_i & "-" & p_fim_f & "</b>]"                End If
        If p_atraso      = "S" Then w_filtro = w_filtro & "<tr valign=""top""><TD align=""right"">Situação <TD>[<b>Apenas atrasadas</b>]"                          End If
        If w_filtro      > ""  Then w_filtro = "<table border=0><tr valign=""top""><TD><b>Filtro:</b><TD nowrap><ul>" & w_filtro & "</ul></tr></table>"            End If
     End If
     
     DB_GetLinkData RS, w_cliente, "PJCAD"
     DB_GetSolicList rs, RS("sq_menu"), w_usuario, "PJCAD", 5, _
        p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
        p_unidade, p_prioridade, p_ativo, p_proponente, _
        p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
        p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, p_projeto, p_atividade, null, null
     If p_ordena > "" Then RS.sort = p_ordena Else RS.sort = "fim, prioridade" End If
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>" & conSgSistema & " - Listagem de projetos</TITLE>"
  If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""30; URL=" & replace(MontaURL("MESA"),w_dir,"") & """>" End If
  Estrutura_CSS w_cliente

  ScriptOpen "Javascript"
  CheckBranco
  FormataData
  ValidateOpen "Validacao"
  If Instr("CP",O) > 0 Then
     If P1 <> 1 or O = "C" Then ' Se não for cadastramento ou se for cópia
        Validate "p_chave", "Número do projeto", "", "", "1", "18", "", "0123456789"
        Validate "p_prazo", "Dias para a data fim", "", "", "1", "2", "", "0123456789"
        Validate "p_proponente", "Proponente", "", "", "2", "90", "1", ""
        Validate "p_assunto", "Título", "", "", "2", "90", "1", "1"
        Validate "p_palavra", "Palavras-chave", "", "", "2", "90", "1", "1"
        Validate "p_ini_i", "Início de", "DATA", "", "10", "10", "", "0123456789/"
        Validate "p_ini_f", "Início até", "DATA", "", "10", "10", "", "0123456789/"
        ShowHTML "  if ((theForm.p_ini_i.value != '' && theForm.p_ini_f.value == '') || (theForm.p_ini_i.value == '' && theForm.p_ini_f.value != '')) {"
        ShowHTML "     alert ('Informe ambas as datas de início ou nenhuma delas!');"
        ShowHTML "     theForm.p_ini_i.focus();"
        ShowHTML "     return false;"
        ShowHTML "  }"
        CompData "p_ini_i", "Início de", "<=", "p_ini_f", "Início até"
        Validate "p_fim_i", "Fim de", "DATA", "", "10", "10", "", "0123456789/"
        Validate "p_fim_f", "Fim até", "DATA", "", "10", "10", "", "0123456789/"
        ShowHTML "  if ((theForm.p_fim_i.value != '' && theForm.p_fim_f.value == '') || (theForm.p_fim_i.value == '' && theForm.p_fim_f.value != '')) {"
        ShowHTML "     alert ('Informe ambas as datas finais ou nenhuma delas!');"
        ShowHTML "     theForm.p_fim_i.focus();"
        ShowHTML "     return false;"
        ShowHTML "  }"
        CompData "p_fim_i", "Fim de", "<=", "p_fim_f", "Fim até"
     End If
     Validate "P4", "Linhas por página", "1", "1", "1", "4", "", "0123456789"
  End If
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_Troca > "" Then ' Se for recarga da página
     BodyOpen "onLoad='document.Form." & w_Troca & ".focus();'"
  ElseIf O = "I" Then
     BodyOpen "onLoad='document.Form.w_smtp_server.focus();'"
  ElseIf O = "A" Then
     BodyOpen "onLoad='document.Form.w_nome.focus();'"
  ElseIf O = "E" Then
     BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
  ElseIf InStr("CP",O) > 0 Then
     If P1 <> 1 or O = "C" Then ' Se for cadastramento
        BodyOpen "onLoad='document.Form.p_chave.focus()';"
     Else
        BodyOpen "onLoad='document.Form.p_ordena.focus()';"
     End if
  Else
     BodyOpen "onLoad=document.focus();"
  End If
  ShowHTML "<center>"
  Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  
  If w_filtro > "" Then ShowHTML w_filtro End If
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ShowHTML "<tr><TD>"
    ShowHTML "    <TD align=""right""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><TD align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <TD rowspan=2><b>" & LinkOrdena("Nº","sq_siw_solicitacao") & "</font></td>"
    ShowHTML "          <TD rowspan=2><b>" & LinkOrdena("Proponente","nm_prop_res") & "</font></td>"
    ShowHTML "          <TD rowspan=2><b>" & LinkOrdena("Título","titulo") & "</font></td>"
    ShowHTML "          <TD colspan=2><b>Evento</font></td>"
    ShowHTML "          <TD rowspan=2><b>" & LinkOrdena("Valor","valor") & "</font></td>"
    ShowHTML "          <TD rowspan=2><b>Fase atual</font></td>"
    ShowHTML "          <TD rowspan=2><b>Operações</font></td>"
    ShowHTML "        </tr>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <TD><b>" & LinkOrdena("De","inicio_real") & "</font></td>"
    ShowHTML "          <TD><b>" & LinkOrdena("Até","fim_real") & "</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><TD colspan=7 align=""center""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      w_parcial       = 0
      While Not RS.EOF and RS.AbsolutePage = P3
        DB_GetSolicData RS1, RS("sq_siw_solicitacao"), Mid(SG,1,2)&"GERAL"
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"" align=""left"">"
        ShowHTML "        <TD nowrap>"
        ShowHTML "        <A class=""hl"" HREF=""" & w_dir & w_Pagina & "Visual&R=" & w_Pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exibe as informações deste registro."">" & RS("sq_siw_solicitacao") & "&nbsp;</a>"
        If Nvl(RS("outra_parte"),"nulo") <> "nulo" Then
           ShowHTML "        <TD>" & ExibePessoa(w_dir_volta, w_cliente, RS("outra_parte"), TP, RS("nm_prop_res")) & "</td>"
        Else
           ShowHTML "        <TD align=""center"">---</td>"
        End If
        ' Verifica se foi enviado o parâmetro p_tamanho = N. Se chegou, o assunto deve ser exibido sem corte.
        ' Este parâmetro é enviado pela tela de filtragem das páginas gerenciais
        If Request("p_tamanho") = "N" Then
           ShowHTML "        <TD>" & Nvl(RS("titulo"),"-") & "</td>"
        Else
           If Len(Nvl(RS("titulo"),"-")) > 50 Then w_titulo = Mid(Nvl(RS("titulo"),"-"),1,50) & "..." Else w_titulo = Nvl(RS("titulo"),"-") End If
           If RS("sg_tramite") = "CA" Then
              ShowHTML "        <TD title=""" & replace(replace(replace(RS("titulo"), "'", "\'"), """", "\'"),VbCrLf,"\n") & """><strike>" & w_titulo & "</strike></td>"
           Else
              ShowHTML "        <TD title=""" & replace(replace(replace(RS("titulo"), "'", "\'"), """", "\'"),VbCrLf,"\n") & """>" & w_titulo & "</td>"
           End IF
        End If
        ShowHTML "        <TD align=""center"">&nbsp;" & Nvl(FormataDataEdicao(RS("inicio_real")),"---") & "</td>"
        ShowHTML "        <TD align=""center"">&nbsp;" & Nvl(FormataDataEdicao(RS("fim_real")),"---") & "</td>"
        ' Mostra os valor se o usuário for interno e não for cadastramento nem mesa de trabalho
        If RS("sg_tramite") = "AT" Then
           ShowHTML "        <TD align=""right"">" & FormatNumber(RS("custo_real"),2) & "</td>"
           w_parcial = w_parcial + cDbl(RS("custo_real"))
        Else
           ShowHTML "        <TD align=""right"">" & FormatNumber(RS("valor"),2) & "</td>"
           w_parcial = w_parcial + cDbl(RS("valor"))
        End If
        ShowHTML "        <TD>" & RS("nm_tramite") & "</td>"
        ShowHTML "        <TD align=""top"" nowrap>"
        If cDbl(Nvl(RS("or_tramite"),"")) < 5 Then 
           ShowHTML "          <A class=""hl"" HREF=""javascript:location.href=this.location.href;"" onClick=""window.open('" & w_pagina & "Informar&R=" & w_dir & w_Pagina & "Informar&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" &TP& " - Informar&SG=" & SG & MontaFiltro("GET") & "','Apoio','toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes');"" title=""Informar os apoios do projeto."">Informar</A>&nbsp" 
        End If
        If cDbl(Nvl(RS("or_tramite"),"")) < 5 Then
           ShowHTML "          <A class=""hl"" HREF=""javascript:location.href=this.location.href;"" onClick=""window.open('" & w_Pagina & "Passagens&R=" & w_Pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" &TP& " - Passagens&SG=" & SG & MontaFiltro("GET") & "','Passagens','toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes');"" title=""Informar passagens do projeto."">Passagens</A>&nbsp" 
        End If
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend

      ' Coloca o valor parcial apenas se a listagem ocupar mais de uma página
      If RS.PageCount > 1 Then
         ShowHTML "        <tr bgcolor=""" & conTrBgColor & """>"
         ShowHTML "          <TD colspan=5 align=""right""><b>Total desta página&nbsp;</font></td>"
         ShowHTML "          <TD align=""right""><b>" & FormatNumber(w_parcial,2) & "&nbsp;</font></td>"
         ShowHTML "          <TD colspan=2>&nbsp;</font></td>"
         ShowHTML "        </tr>"
      End If
      
      ' Se for a última página da listagem, soma e exibe o valor total
      If P3 = RS.PageCount Then
         RS.MoveFirst
         While Not RS.EOF
           If RS("sg_tramite") = "AT" Then
              w_total = w_total + cDbl(RS("custo_real"))
           Else
              w_total = w_total + cDbl(RS("valor"))
           End If
             RS.MoveNext
         Wend
         ShowHTML "        <tr bgcolor=""" & conTrBgColor & """>"
         ShowHTML "          <TD colspan=5 align=""right""><b>Total da listagem&nbsp;</font></td>"
         ShowHTML "          <TD align=""right""><b>" & FormatNumber(w_total,2) & "&nbsp;</font></td>"
         ShowHTML "          <TD colspan=2>&nbsp;</font></td>"
         ShowHTML "        </tr>"
      End If
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    ShowHTML "<tr><TD colspan=3>"
    ShowHTML "  <br><p>"
    ShowHTML "  <b>Orientações:</b>"
    ShowHTML "  <ul>"
    ShowHTML "  <li> Clique sobre o número do projeto, localizado na primeira coluna da tabela, para ver os dados e o andamento do projeto."
    ShowHTML "  <li> Para cada projeto na tabela acima, clique na opção ""Proponente"" para complementar seus dados. Faça o mesmo na opção ""Preposto""."
    ShowHTML "  <li> Após a aprovação do projeto, use a opção ""Passagens"" para inserir os dados das pessoas que irão receber passagens."
    ShowHTML "  <li> Após a aprovação do projeto, use a opção ""Desconto"" para solicitar desconto de 25% nas passagens emitidas pela VARIG."
    ShowHTML "  <li> Acesse constantemente esta página para consultar informações atualizadas sobre os projetos."
    ShowHTML "  </ul>"
    ShowHTML "  </p>"
    ShowHTML "<tr><TD align=""center"" colspan=3>"
    If R > "" Then
       MontaBarra w_dir & w_pagina&par&"&R="&R&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG&"&w_copia="&w_copia, RS.PageCount, P3, P4, RS.RecordCount
    Else
       MontaBarra w_dir & w_pagina&par&"&R="&w_Pagina&par&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG&"&w_copia="&w_copia, RS.PageCount, P3, P4, RS.RecordCount
    End If
    ShowHTML "</tr>"    
    DesConectaBD     
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
    Estrutura_Texto_Fecha
    Estrutura_Fecha
  Estrutura_Fecha
  Estrutura_Fecha
  Rodape

  Set w_titulo = Nothing
End Sub
REM =========================================================================
REM Fim da tabela de projetos
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de cadastramento de passagens
REM -------------------------------------------------------------------------
Sub Passagens

  Dim w_chave, w_chave_aux, w_sq_pessoa, w_nome, w_sq_pessoa_pai, w_nome_resumido
  Dim w_cpf, w_rg_numero, w_rg_emissor, w_rg_emissao
  Dim w_passaporte_numero, w_sq_pais_passaporte, w_nm_pais_passaporte
  Dim w_sq_pessoa_telefone, w_ddd, w_nr_telefone
  Dim w_sq_pessoa_fax, w_nr_fax
  Dim w_sq_cidade_origem, w_co_uf_origem, w_sq_pais_origem
  Dim w_sq_cidade_destino, w_co_uf_destino, w_sq_pais_destino
  Dim w_data_saida, w_data_volta, w_reserva, w_bilhete, w_trechos
  Dim w_sq_viagem, w_valor, w_or_tramite
  Dim w_sexo, w_nm_cidade_origem, w_nm_cidade_destino
  Dim w_botao
  
  Dim w_troca, i, w_erro, w_como_funciona, w_cor, w_disabled

  If O = "" Then O = "L" End If

  w_erro            = ""
  w_troca           = Request("w_troca")
  w_chave           = Request("w_chave")
  w_chave_aux       = Request("w_chave_aux")
  w_cpf             = uCase(Request("w_cpf"))
  w_sq_pessoa       = Request("w_sq_pessoa")
  w_sq_viagem       = Request("w_sq_viagem")
  w_or_tramite      = Request("w_or_tramite")
  w_botao           = uCase(Request("w_botao"))
  'ExibeVariaveis
  
  ' Recupera os dados do projeto
  DB_GetSolicData RS1, w_chave, Mid(SG,1,2)&"GERAL"
  w_or_tramite = Nvl(RS1("or_tramite"),0)

  ' Verifica se há necessidade de recarregar os dados da tela a partir
  ' da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  If w_troca > "" Then ' Se for recarga da página
     w_chave                = Request("w_chave")
     w_chave_aux            = Request("w_chave_aux")
     w_nome                 = Request("w_nome")
     w_nome_resumido        = Request("w_nome_resumido")
     w_sexo                 = Request("w_sexo")
     w_sq_pessoa_pai        = Request("w_sq_pessoa_pai")
     w_rg_numero            = Request("w_rg_numero")
     w_rg_emissor           = Request("w_rg_emissor")
     w_rg_emissao           = FormataDataEdicao(Request("w_rg_emissao"))
     w_passaporte_numero    = Request("w_passaporte_numero")
     w_sq_pais_passaporte   = Request("w_sq_pais_passaporte")
     w_nm_pais_passaporte   = Request("w_nm_pais_passaporte")
     w_sq_pessoa_telefone   = Request("w_sq_pessoa_telefone")
     w_ddd                  = Request("w_ddd")
     w_nr_telefone          = Request("w_nr_telefone")
     w_sq_pessoa_fax        = Request("w_sq_pessoa_fax")
     w_nr_fax               = Request("w_nr_fax")
     w_sq_pais_origem       = Request("w_sq_pais_origem")
     w_co_uf_origem         = Request("w_co_uf_origem")
     w_sq_cidade_origem     = Request("w_sq_cidade_origem")
     w_sq_pais_destino      = Request("w_sq_pais_destino")
     w_co_uf_destino        = Request("w_co_uf_destino")
     w_sq_cidade_destino    = Request("w_sq_cidade_destino")
     w_data_saida           = FormataDataEdicao(Request("w_data_saida"))
     w_data_volta           = FormataDataEdicao(Request("w_data_volta"))
     w_valor                = FormatNumber(cDbl(Nvl(w_valor,0)),2)
     w_reserva              = Request("w_reserva")
     w_bilhete              = Request("w_bilhete")
     w_trechos              = Request("w_trechos")

  Else
     If O = "L" Then
        ' Recupera as passagens e viajantes
        DB_GetViagemBenef RS, w_chave, w_cliente, null, null, null, null, null, null, null
        RS.Sort = "nm_pessoa"
     ElseIf Instr(w_botao,"Alterar") = 0 and Instr(w_botao,"Procurar") = 0 and (O = "A" or w_sq_pessoa > "" or w_cpf > "") Then
        ' Recupera os dados do beneficiário em co_pessoa
        DB_GetViagemBenef RS, w_chave, w_cliente, w_sq_pessoa, null, w_cpf, null, null, null, null
        If Not RS.EOF Then
           w_sq_pessoa            = RS("sq_pessoa")
           w_nome                 = RS("nm_pessoa")
           w_nome_resumido        = RS("nome_resumido")
           w_sexo                 = RS("sexo")
           w_sq_pessoa_pai        = RS("sq_pessoa_pai")
           w_cpf                  = RS("cpf")
           w_rg_numero            = RS("rg_numero")
           w_rg_emissor           = RS("rg_emissor")
           w_passaporte_numero    = RS("passaporte_numero")
           w_sq_pais_passaporte   = RS("sq_pais_passaporte")
           w_nm_pais_passaporte   = RS("nm_pais_passaporte")
           w_rg_emissao           = FormataDataEdicao(RS("rg_emissao"))
           w_sq_pessoa_telefone   = RS("sq_pessoa_telefone")
           w_ddd                  = RS("ddd")
           w_nr_telefone          = RS("nr_telefone")
           w_sq_pessoa_fax        = RS("sq_pessoa_fax")
           w_nr_fax               = RS("nr_fax")
           w_sq_pais_origem       = RS("sq_pais_origem")
           w_co_uf_origem         = RS("co_uf_origem")
           w_sq_cidade_origem     = RS("origem")
           w_sq_pais_destino      = RS("sq_pais_destino")
           w_co_uf_destino        = RS("co_uf_destino")
           w_sq_cidade_destino    = RS("destino")
           w_data_saida           = FormataDataEdicao(RS("saida"))
           w_data_volta           = FormataDataEdicao(RS("retorno"))
           w_valor                = FormatNumber(cDbl(Nvl(RS("valor"),0)),2)
           w_reserva              = RS("reserva")
           w_bilhete              = RS("bilhete")
           w_trechos              = RS("trechos")
           w_sq_viagem            = RS("sq_viagem")
           w_nm_cidade_origem     = RS("nm_cidade_origem")
           w_nm_cidade_destino    = RS("nm_cidade_destino")
           O                      = "A"
        Else
           DB_GetBenef RS, w_cliente, w_sq_pessoa, w_cpf, null, null, null, null, null
           If Not RS.EOF Then
              w_sq_pessoa            = RS("sq_pessoa")
              w_nome                 = RS("nm_pessoa")
              w_nome_resumido        = RS("nome_resumido")
              w_sexo                 = RS("sexo")
              w_cpf                  = RS("cpf")
              w_rg_numero            = RS("rg_numero")
              w_rg_emissor           = RS("rg_emissor")
              w_rg_emissao           = FormataDataEdicao(RS("rg_emissao"))
              w_sq_pessoa_telefone   = RS("sq_pessoa_telefone")
              w_passaporte_numero    = RS("passaporte_numero")
              w_sq_pais_passaporte   = RS("sq_pais_passaporte")
              w_nm_pais_passaporte   = RS("nm_pais_passaporte")
              w_ddd                  = RS("ddd")
              w_nr_telefone          = RS("nr_telefone")
              w_sq_pessoa_fax        = RS("sq_pessoa_fax")
              w_nr_fax               = RS("nr_fax")
           End If
        End If
        DesconectaBD
     End If
  End If  
  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>" & conSgSistema & " - Listagem de projetos</TITLE>"
  If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & replace(MontaURL("MESA"),w_dir,"") & """>" End If
  Estrutura_CSS w_cliente

  ' Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  ' tratando as particularidades de cada serviço
  If O <> "L" Then
     ScriptOpen "JavaScript"
     Modulo
     FormataCPF
     checkBranco
     FormataData
     FormataValor
     ValidateOpen "Validacao"
     If w_cpf = "" or Instr(w_botao,"Procurar") > 0 or Instr(w_botao,"Alterar") > 0 Then ' Se o beneficiário ainda não foi selecionado
        ShowHTML "  if (theForm.Botao.value == ""Procurar"") {"
        Validate "w_nome", "Nome", "", "1", "4", "20", "1", ""
        ShowHTML "  theForm.Botao.value = ""Procurar"";"
        ShowHTML "}"
        ShowHTML "else if (theForm.Botao.value == ""Selecionar"") {"
        Validate "w_cpf", "CPF", "CPF", "1", "10", "14", "", "0123456789-."
        ShowHTML "  theForm.w_sq_pessoa.value = '';"
        ShowHTML "}"
        ShowHTML "else { theForm.w_cpf.value = 'GERAR'; }"
        ShowHTML "  theForm.Botao[0].disabled=true;"
        ShowHTML "  theForm.Botao[1].disabled=true;"
        ShowHTML "  theForm.Botao[2].disabled=true;"
     ElseIf O = "I" or O = "A" Then
        Validate "w_nome", "Nome", "1", 1, 5, 60, "1", "1"
        Validate "w_nome_resumido", "Nome resumido", "1", 1, 2, 15, "1", "1"
        Validate "w_sexo", "Sexo", "SELECT", 1, 1, 1, "MF", ""
        If len(w_cpf) = 14 Then
           Validate "w_rg_numero", "Identidade", "1", 1, 2, 30, "1", "1"
           Validate "w_rg_emissao", "Data de emissão", "", "", 10, 10, "", "0123456789/"
           Validate "w_rg_emissor", "Órgão expedidor", "1", 1, 2, 30, "1", "1"
           ShowHTML "  if ((theForm.w_passaporte_numero.value != ''  && theForm.w_sq_pais_passaporte.value == '') || (theForm.w_passaporte_numero.value == '' && theForm.w_sq_pais_passaporte.value != '')) {"
           ShowHTML "     alert ('Favor informar o número do passaporte e o país emissor do passaporte!');"
           ShowHTML "     theForm.w_passaporte_numero.focus();"
           ShowHTML "     if (theForm.w_passaporte_numero.value != ''){"
           Validate "w_passaporte_numero", "Passaporte", "1", "1", 3, 15, "1", "1"
           ShowHTML "     }"
           ShowHTML "     if (theForm.w_sq_pais_passaporte.value != ''){"
           Validate "w_sq_pais_passaporte", "País emissor do Passaporte", "SELECT", 1, 1, 1, "1", "1"
           ShowHTML "     }"
           ShowHTML "     return false;"
           ShowHTML "  }"
        Else
           Validate "w_rg_numero", "Identidade", "1", "", 2, 30, "1", "1"
           Validate "w_rg_emissao", "Data de emissão", "", "", 10, 10, "", "0123456789/"
           Validate "w_rg_emissor", "Órgão expedidor", "1", "", 2, 30, "1", "1"
           Validate "w_passaporte_numero", "Passaporte", "1", "1", 3, 15, "1", "1"
           Validate "w_sq_pais_passaporte", "País emissor do Passaporte", "SELECT", 1, 1, 1, "1", "1"
        End If
        Validate "w_ddd", "DDD", "1", "1", 3, 4, "", "0123456789"
        Validate "w_nr_telefone", "Telefone", "1", 1, 7, 25, "1", "1"
        If cDbl(w_or_tramite) < 5 Then
           Validate "w_data_saida", "Data de saída", "DATA", "1", "10", "10", "", "0123456789/"
           Validate "w_data_volta", "Data de retorno", "DATA", "1", "10", "10", "", "0123456789/"
           CompData "w_data_saida", "Data de Saida", "<=", "w_data_volta", "Data de retorno"
           Validate "w_sq_pais_origem", "País de origem", "SELECT", 1, 1, 10, "1", "1"
           Validate "w_co_uf_origem", "UF de origem", "SELECT", 1, 1, 10, "1", "1"
           Validate "w_sq_cidade_origem", "Cidade de origem", "SELECT", 1, 1, 10, "", "1"
           Validate "w_sq_pais_destino", "País de destino", "SELECT", 1, 1, 10, "1", "1"
           Validate "w_co_uf_destino", "UF de destino", "SELECT", 1, 1, 10, "1", "1"
           Validate "w_sq_cidade_destino", "Cidade de destino", "SELECT", 1, 1, 10, "", "1"
           Validate "w_trechos", "Trechos", "1", "1", 5, 70, "1", "1"
        End If
        ShowHTML "  theForm.Botao[0].disabled=true;"
        ShowHTML "  theForm.Botao[1].disabled=true;"
     End If
     ValidateClose
     ScriptClose
     ShowHTML "</HEAD>"
  End If
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If InStr("IA",O) > 0 and (w_cpf = "" or Instr(w_botao,"Alterar") > 0 or Instr(w_botao,"Procurar") > 0) Then ' Se o beneficiário ainda não foi selecionado
     If Instr(w_botao,"Procurar") > 0 Then ' Se está sendo feita busca por nome
        BodyOpen "onLoad='document.focus()';"
     Else
        BodyOpen "onLoad='document.Form.w_cpf.focus()';"
     End If
  ElseIf w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf InStr("IA",O) > 0 Then
     BodyOpen "onLoad='document.Form.w_nome.focus()';"
  Else
     BodyOpen "onLoad='document.focus()';"
  End If
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  ShowHTML "<tr><td colspan=3 bgcolor=""#FAEBD7""><table border=1 width=""100%""><tr><td>"
  ShowHTML "    <TABLE WIDTH=""100%"" CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
  ShowHTML "      <tr><td colspan=""2""><font size=""1"">Projeto: <b>" & uCase(RS1("titulo")) & " (" & w_chave & ")</font></b></td>"
  ShowHTML "      <tr valign=""top"">"
  DB_GetBenef RS2, w_cliente, Nvl(RS1("outra_parte"),0), null, null, null, Nvl(RS1("sq_tipo_pessoa"),0), null, null
  If  cDbl(Nvl(RS1("sq_tipo_pessoa"),0)) = 1 Then
     ShowHTML "          <td><font size=""1"">Proponente:<b>" & RS2("nome_resumido") & "(" & RS2("cpf")& ")</b></td>"
  ElseIf  cDbl(Nvl(RS1("sq_tipo_pessoa"),0)) = 2 Then
     ShowHTML "          <td><font size=""1"">Proponente:<b>" & RS2("nome_resumido") & "(" & RS2("cnpj")& ")</b></td>"
  End If
  RS2.Close
  ShowHTML "          <td><font size=""1"">Fase atual:<b>" & RS1("nm_tramite") & "</b></td>"
  ShowHTML "    </TABLE>"
  ShowHTML "</TABLE>"
  ShowHTML "  <tr><td>&nbsp;"

  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "<tr>"
    If (RS.RecordCount < cDbl(Nvl(RS1("limite_passagem"),0))) and cDbl(w_or_tramite) < 5 Then
       ShowHTML "    <td><font size=""2""><a accesskey=""I"" class=""ss"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_or_tramite=" & w_or_tramite &  """><u>I</u>ncluir</a>&nbsp;"
    Else
       ShowHTML "    <td><font size=""2"">&nbsp;"
    End If
    ShowHTML "    <td align=""right""><font size=""1""><b>Passagens disponíveis: " & (cDbl(Nvl(RS1("limite_passagem"),0)) - cDbl(RS.RecordCount))
    ShowHTML "    <td align=""right""><font size=""1""><b>Passagens cadastradas: " & RS.RecordCount
    ShowHTML "<tr><td colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>CPF</font></td>"
    ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
    ShowHTML "          <td><font size=""1""><b>DDD</font></td>"
    ShowHTML "          <td><font size=""1""><b>Telefone</font></td>"
    ShowHTML "          <td><font size=""1""><b>Ida</font></td>"
    ShowHTML "          <td><font size=""1""><b>Volta</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=8 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("cpf") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("nome_resumido") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(RS("ddd"),"---") & "</td>"
        ShowHTML "        <td><font size=""1"">" & Nvl(RS("nr_telefone"),"---") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(FormataDataEdicao(RS("saida")),"---") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(FormataDataEdicao(RS("retorno")),"---") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        If w_or_tramite <> "EE" Then
           ' O representante pode alterar os dados cadastrais dos viajantes e emitir o formulário
           ' enquanto o projeto não entrar em execução
           If cDbl(w_or_tramite) < 5 Then
              ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & w_chave & "&w_chave_aux=" & w_cliente & "&w_sq_pessoa=" & Rs("sq_pessoa") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_sq_viagem=" & RS("sq_viagem") & "&w_or_tramite=" & w_or_tramite & """>Alterar</A>&nbsp"
              ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & "VisualForm&R=" & w_Pagina & par & "&O=L&w_chave=" & w_chave & "&w_chave_aux=" & w_cliente & "&w_sq_pessoa=" & Rs("sq_pessoa") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exibe as informações deste registro."" & target=""_blank"">Formulário</a>"
           End If
           ' O representante pode excluir viajantes enquanto o projeto não entrar em execução
           If cDbl(w_or_tramite) < 5 Then
              ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & "GRAVA&R=" & w_Pagina & par & "&O=E&w_chave=" & w_chave & "&w_chave_aux=" & w_cliente & "&w_sq_pessoa=" & Rs("sq_pessoa") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & "PJPSG" & "&w_sq_viagem=" & RS("sq_viagem") & """ onClick=""return confirm('Confirma a exclusão do registro?');"">Excluir</A>&nbsp"
           End If
        Else
           ShowHTML "          ---&nbsp"
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
  ElseIf Instr("IA",O) > 0 Then
    If w_cpf = "" or Instr(w_botao,"Alterar") > 0 or Instr(w_botao,"Procurar") > 0 Then ' Se o beneficiário ainda não foi selecionado
       ShowHTML "<FORM action=""" & w_dir & w_Pagina & par & """ method=""POST"" name=""Form"" onSubmit=""return(Validacao(this));"">"
    Else
       ShowHTML "<FORM action=""" & w_dir & w_Pagina & "Grava"" method=""POST"" name=""Form"" onSubmit=""return(Validacao(this));"">"
    End If
    ShowHTML "<INPUT type=""hidden"" name=""P1"" value=""" & P1 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P2"" value=""" & P2 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P3"" value=""" & P3 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P4"" value=""" & P4 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""TP"" value=""" & TP & """>"
    ShowHTML "<INPUT type=""hidden"" name=""SG"" value=""PJPSG"">"
    ShowHTML "<INPUT type=""hidden"" name=""R"" value=""" & w_pagina & par & """>"
    ShowHTML "<INPUT type=""hidden"" name=""O"" value=""" & O &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_botao"" value=""" & w_botao & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_cliente &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_pessoa"" value=""" & w_sq_pessoa &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_viagem"" value=""" & w_sq_viagem &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_or_tramite"" value=""" & w_or_tramite &""">"

    If w_cpf = "" or InStr(w_botao, "Alterar") > 0 or Instr(w_botao,"Procurar") > 0 Then
       w_nome = Request("w_nome")
       If InStr(w_botao, "Alterar") > 0 Then
          w_cpf  = ""
          w_nome = ""
       End If
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
       ShowHTML "    <table border=""0"">"
       ShowHTML "        <tr><td colspan=4><font size=2>Informe o CPF e clique no botão ""Selecionar"" para continuar.</TD>"
       ShowHTML "        <tr><td colspan=4><font size=1><b><u>C</u>PF:<br><INPUT ACCESSKEY=""C"" TYPE=""text"" class=""sti"" NAME=""w_cpf"" VALUE=""" & w_cpf & """ SIZE=""14"" MaxLength=""14"" onKeyDown=""FormataCPF(this, event);"">"
       ShowHTML "            <INPUT class=""stb"" TYPE=""submit"" NAME=""Botao"" VALUE=""Selecionar"" onClick=""Botao.value=this.value; w_botao.value=Botao.value;"">"
       ShowHTML "        <tr><td colspan=4><font size=2>Se a pessoa não tem CPF e o sistema ainda não gerou um código para ela, clique no botão abaixo. Menores, indígenas e estrangeiros sem CPF, que ainda não tenham seu código gerado pelo sistema enquadram-se nesta situação. Se o sistema já gerou um código para a pessoa, informe-o no campo CPF, acima.</TD>"
       ShowHTML "        <tr><td colspan=4><INPUT class=""stb"" TYPE=""submit"" NAME=""Botao"" VALUE=""Pessoa sem CPF nem código gerado pelo sistema"" onClick=""Botao.value=this.value; w_botao.value=Botao.value;"">"
       ShowHTML "        <tr><td colspan=4><p>&nbsp</p>"
       ShowHTML "        <tr><td colspan=4 heigth=1 bgcolor=""#000000"">"
       ShowHTML "        <tr><td colspan=4>"
       ShowHTML "             <font size=1><b><u>P</u>rocurar pelo nome:</b> (Informe qualquer parte do nome SEM ACENTOS)<br><INPUT ACCESSKEY=""P"" TYPE=""text"" class=""sti"" NAME=""w_nome"" VALUE=""" & w_nome & """ SIZE=""20"" MaxLength=""20"">"
       ShowHTML "              <INPUT class=""stb"" TYPE=""submit"" NAME=""Botao"" VALUE=""Procurar"" onClick=""Botao.value=this.value; w_botao.value=Botao.value; document.Form.action='" & w_dir & w_Pagina & par &"'"">"
       ShowHTML "      </table>"
       If w_nome > "" Then
          DB_GetViagemBenef RS, null, w_cliente, null, null, w_cpf, w_nome, null, null, null
          RS.Sort = "nm_pessoa"
          ShowHTML "<tr><td colspan=3>"
          ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
          ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
          ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
          ShowHTML "          <td><font size=""1""><b>Nome resumido</font></td>"
          ShowHTML "          <td><font size=""1""><b>CPF</font></td>"
          ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
          ShowHTML "        </tr>"
          If RS.EOF Then
              ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=4 align=""center""><font  size=""1""><b>Não há pessoas que contenham o texto informado.</b></td></tr>"
          Else
            While Not RS.EOF
              ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
              ShowHTML "        <td><font  size=""1"">" & RS("nm_pessoa") & "</td>"
              ShowHTML "        <td><font  size=""1"">" & RS("nome_resumido") & "</td>"
              ShowHTML "        <td align=""center""><font  size=""1"">" & Nvl(RS("cpf"),"---") & "</td>"
              ShowHTML "        <td nowrap><font size=""1"">"
              ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_pagina & par & "&R=" & R & "&O=I&w_cpf=" & RS("cpf") & "&w_or_tramite=" & w_or_tramite & "&w_sq_pessoa=" & RS("sq_pessoa") & "&w_chave=" & w_chave & "&w_botao=Selecionar&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Selecionar</A>&nbsp"
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
       End If
    Else
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""left"">"
       ShowHTML "    <table width=""97%"" border=""0"">"
       ShowHTML "      <tr><td colspan=""2"" align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Identificação</td></td></tr>"
       ShowHTML "      <tr><td colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td colspan=""2""><table border=""0"" width=""100%"">"
       ShowHTML "          <tr valign=""top"">"
       If w_sq_pessoa > "" or len(w_cpf) = 14 Then
          ShowHTML "          <td><font size=1>CPF:</font><br><b><font size=2>" & w_cpf
          ShowHTML "              <INPUT type=""hidden"" name=""w_cpf"" value=""" & w_cpf & """>"
       Else
          ShowHTML "          <td><font size=1>CPF:</font><br><b><font size=2>(Geração automática)"
          ShowHTML "              <INPUT type=""hidden"" name=""w_cpf"" value=""GERAR"">"
       End If
       ShowHTML "          <tr valign=""top"">"
       ShowHTML "             <td><font size=""1""><b><u>N</u>ome completo:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""40"" MAXLENGTH=""60"" VALUE=""" & w_nome & """></td>"
       ShowHTML "             <td><font size=""1""><b><u>N</u>ome resumido:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome_resumido"" class=""sti"" SIZE=""15"" MAXLENGTH=""15"" VALUE=""" & w_nome_resumido & """></td>"
       SelecaoSexo "Se<u>x</u>o:", "X", null, w_sexo, null, "w_sexo", null, null
       ShowHTML "          <tr valign=""top"">"
       ShowHTML "          <td align=""left""><font size=""1""><b><u>I</u>dentidade:</b><br><input " & w_Disabled & " accesskey=""I"" type=""text"" name=""w_rg_numero"" class=""sti"" SIZE=""14"" MAXLENGTH=""80"" VALUE=""" & w_rg_numero & """></td>"
       ShowHTML "          <td><font size=""1""><b>Data de <u>e</u>missão:</b><br><input " & w_Disabled & " accesskey=""E"" type=""text"" name=""w_rg_emissao"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_rg_emissao & """ onKeyDown=""FormataData(this,event);""></td>"
       ShowHTML "          <td><font size=""1""><b>Ór<u>g</u>ão emissor:</b><br><input " & w_Disabled & " accesskey=""G"" type=""text"" name=""w_rg_emissor"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & w_rg_emissor & """></td>"
       ShowHTML "          <tr valign=""top"">"
       ShowHTML "          <td><font size=""1""><b>Passapo<u>r</u>te:</b><br><input " & w_Disabled & " accesskey=""R"" type=""text"" name=""w_passaporte_numero"" class=""sti"" SIZE=""15"" MAXLENGTH=""15"" VALUE=""" & w_passaporte_numero & """></td>"
       SelecaoPais "País e<u>m</u>issor do passaporte:", "M", null, w_sq_pais_passaporte, null, "w_sq_pais_passaporte", null, null
       ShowHTML "          </table>"

       ShowHTML "      <tr><td colspan=""2"" align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Telefones</td></td></tr>"
       ShowHTML "      <tr><td colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
       ShowHTML "          <td><font size=""1""><b><u>D</u>DD:</b><br><input " & w_Disabled & " accesskey=""D"" type=""text"" name=""w_ddd"" class=""sti"" SIZE=""4"" MAXLENGTH=""4"" VALUE=""" & w_ddd & """></td>"
       ShowHTML "          <td><font size=""1""><b>Te<u>l</u>efone:</b><br><input " & w_Disabled & " accesskey=""L"" type=""text"" name=""w_nr_telefone"" class=""sti"" SIZE=""20"" MAXLENGTH=""40"" VALUE=""" & w_nr_telefone & """></td>"
       ShowHTML "          <td title=""Se for informar um número de fax, informe-o neste campo.""><font size=""1""><b>Fa<u>x</u>:</b><br><input " & w_Disabled & " accesskey=""X"" type=""text"" name=""w_nr_fax"" class=""sti"" SIZE=""20"" MAXLENGTH=""20"" VALUE=""" & w_nr_fax & """></td>"
       ShowHTML "          </table>"
       ShowHTML "      <tr><td colspan=""2"" align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Dados da reserva</td></td></tr>"
       ShowHTML "      <tr><td colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
       If cDbl(w_or_tramite) < 5 Then
          ShowHTML "          <td><font size=""1""><b>D<u>a</u>ta de saída:</b><br><input " & w_Disabled & " accesskey=""A"" type=""text"" name=""w_data_saida"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_data_saida & """ onKeyDown=""FormataData(this,event);"" title=""Informe a data de saida.""></td>"
          ShowHTML "          <td><font size=""1""><b>Data de <u>r</u>etorno:</b><br><input " & w_Disabled & " accesskey=""R"" type=""text"" name=""w_data_volta"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_data_volta & """ onKeyDown=""FormataData(this,event);"" title=""Informe a data de volta.""></td>"
          ShowHTML "      <tr valign=""top"">"
          SelecaoPais   "País de ori<u>g</u>em:", "G", null, w_sq_pais_origem, null, "w_sq_pais_origem", "NOMEBRASIL", "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_co_uf_origem'; document.Form.submit();"""
          SelecaoEstado "E<u>s</u>tado de origem:", "S", null, w_co_uf_origem, w_sq_pais_origem, "N", "w_co_uf_origem", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_sq_cidade_origem'; document.Form.submit();"""
          SelecaoCidade "<u>C</u>idade de origem:", "C", null, w_sq_cidade_origem, w_sq_pais_origem, w_co_uf_origem, "w_sq_cidade_origem", null, null
          ShowHTML "      <tr valign=""top"">"
          SelecaoPais   "País de des<u>t</u>ino:", "T", null, w_sq_pais_destino, null, "w_sq_pais_destino", "BRASILFRANCA", "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_co_uf_destino'; document.Form.submit();"""
          SelecaoEstado "Estad<u>o</u> de destino:", "O", null, w_co_uf_destino, w_sq_pais_destino, "N", "w_co_uf_destino", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_sq_cidade_destino'; document.Form.submit();"""
          SelecaoCidade "<u>C</u>idade de destino:", "C", null, w_sq_cidade_destino, w_sq_pais_destino, w_co_uf_destino, "w_sq_cidade_destino", null, null    
          ShowHTML "      <tr><td colspan=""3""><font size=""1""><b>Trec<u>h</u>os:</b><br><input " & w_Disabled & " accesskey=""H"" type=""text"" name=""w_trechos"" class=""sti"" SIZE=""70"" MAXLENGTH=""100"" VALUE=""" & w_trechos & """></td>"
       Else
          ShowHTML "          <td><font size=1>Data de saída:</font><br><b><font size=2>" & w_data_saida
          ShowHTML "              <INPUT type=""hidden"" name=""w_data_saida"" value=""" & w_data_saida & """>"
          ShowHTML "          <td><font size=1>Data de retorno:</font><br><b><font size=2>" & w_data_volta
          ShowHTML "              <INPUT type=""hidden"" name=""w_data_volta"" value=""" & w_data_volta & """>"
          ShowHTML "      <tr valign=""top"">"
          ShowHTML "          <td><font size=1>Cidade de origem:</font><br><b><font size=2>" & w_nm_cidade_origem
          ShowHTML "              <INPUT type=""hidden"" name=""w_sq_cidade_origem"" value=""" & w_sq_cidade_origem & """>"
          ShowHTML "          <td><font size=1>Cidade de destino:</font><br><b><font size=2>" & w_nm_cidade_destino
          ShowHTML "              <INPUT type=""hidden"" name=""w_sq_cidade_destino"" value=""" & w_sq_cidade_destino & """>"
          ShowHTML "      <tr valign=""top"">"
          ShowHTML "          <td><font size=1>Trechos:</font><br><b><font size=2>" & w_trechos
          ShowHTML "              <INPUT type=""hidden"" name=""w_trechos"" value=""" & w_trechos & """>"
       End If
       ShowHTML "      </table>" 
       ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000""></TD></TR>"
       ShowHTML "      <tr><td align=""center"" colspan=""3"">"
       ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Gravar"">"
       ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=L';"" name=""Botao"" value=""Cancelar"">"
       ShowHTML "          </td>"
       ShowHTML "      </tr>"
       ShowHTML "    </table>"
       ShowHTML "    </TD>"
       ShowHTML "</tr>"
    End If
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

  Set w_botao               = Nothing
  Set w_chave               = Nothing 
  Set w_chave_aux           = Nothing 
  Set w_sq_pessoa           = Nothing 
  Set w_nome                = Nothing 
  Set w_nome_resumido       = Nothing
  Set w_sexo                = Nothing
  Set w_sq_pessoa_pai       = Nothing 
  Set w_cpf                 = Nothing 
  Set w_rg_numero           = Nothing 
  Set w_rg_emissor          = Nothing 
  Set w_rg_emissao          = Nothing
  Set w_passaporte_numero   = Nothing
  Set w_sq_pais_passaporte  = Nothing
  Set w_nm_pais_passaporte  = Nothing
  Set w_data_saida          = Nothing
  Set w_data_volta          = Nothing
  Set w_sq_pais_origem      = Nothing
  Set w_co_uf_origem        = Nothing
  Set w_sq_cidade_origem    = Nothing
  Set w_sq_pais_destino     = Nothing
  Set w_co_uf_destino       = Nothing
  Set w_sq_cidade_destino   = Nothing
  Set w_nm_cidade_origem    = Nothing
  Set w_nm_cidade_destino   = Nothing
  Set w_reserva             = Nothing
  Set w_trechos             = Nothing
  Set w_bilhete             = Nothing
  Set w_or_tramite          = Nothing
  Set w_troca               = Nothing 
  Set i                     = Nothing 
  Set w_erro                = Nothing 
  Set w_como_funciona       = Nothing 
  Set w_cor                 = Nothing 
  Set w_disabled            = Nothing
End Sub
REM =========================================================================
REM Fim da tela de passagens
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de apoios
REM -------------------------------------------------------------------------
Sub Informar
  Dim w_chave, w_chave_aux, w_sq_menu, w_sq_unidade
  
  Dim w_sq_tipo_apoio, w_entidade, w_descricao, w_valor
  
  
  Dim w_troca, i, w_erro
  
  w_Chave           = Request("w_Chave")
  w_chave_aux       = Request("w_chave_aux")
  w_troca           = Request("w_troca")

  ' Recupera os dados do projeto
  DB_GetSolicData RS1, w_chave, Mid(SG,1,2)&"GERAL"
  
  If w_troca > "" Then ' Se for recarga da página
     w_sq_tipo_apoio           = Request("w_sq_tipo_apoio") 
     w_entidade                = Request("w_entidade") 
     w_descricao               = Request("w_descricao") 
     w_valor                   = Request("w_valor") 
  ElseIf O = "L" Then
     ' Recupera todos os registros para a listagem
     DB_GetSolicApoioList RS, w_chave, null, null
     RS.Sort = "sq_solic_apoio"
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
     ' Recupera os dados do endereço informado
     DB_GetSolicApoioList RS, w_chave, w_chave_aux, null
     w_chave_aux              = RS("sq_solic_apoio")
     w_sq_tipo_apoio          = RS("sq_tipo_apoio") 
     w_entidade               = RS("entidade")
     w_descricao              = RS("descricao")
     w_valor                  = FormatNumber(cDbl(Nvl(RS("valor"),0)),2)
     DesconectaBD
  End If

  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente  
  If InStr("IAEGCP",O) > 0 Then
     ScriptOpen "JavaScript"
     CheckBranco
     FormataValor

     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_sq_tipo_apoio", "Tipo de apoio", "SELECT", 1, 1, 10, "1", "1"
        Validate "w_entidade", "Entidade", "1", "1", 3, 50, "1", "1"
        Validate "w_valor", "Valor", "VALOR", "1", 4, 18, "", "0123456789.,"
        Validate "w_descricao", "Descrição", "1", "", 3, 200, "1", "1"
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
  ElseIf O = "I" Then
     BodyOpen "onLoad='document.Form.w_sq_tipo_apoio.focus()';"
  Else
     BodyOpen "onLoad='document.focus()';"
  End If
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  ShowHTML "<tr><td colspan=3 bgcolor=""#FAEBD7""><table border=1 width=""100%""><tr><td>"
  ShowHTML "    <TABLE WIDTH=""100%"" CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
  ShowHTML "      <tr><td colspan=""2""><font size=""1"">Projeto: <b>" & uCase(RS1("titulo")) & " (" & w_chave & ")</font></b></td>"
  ShowHTML "      <tr valign=""top"">"
  DB_GetBenef RS2, w_cliente, Nvl(RS1("outra_parte"),0), null, null, null, Nvl(RS1("sq_tipo_pessoa"),0), null, null
  If  cDbl(Nvl(RS1("sq_tipo_pessoa"),0)) = 1 Then
     ShowHTML "          <td><font size=""1"">Proponente:<b>" & RS2("nome_resumido") & "(" & RS2("cpf")& ")</b></td>"
  ElseIf  cDbl(Nvl(RS1("sq_tipo_pessoa"),0)) = 2 Then
     ShowHTML "          <td><font size=""1"">Proponente:<b>" & RS2("nome_resumido") & "(" & RS2("cnpj")& ")</b></td>"
  End If
  RS2.Close
  ShowHTML "          <td><font size=""1"">Fase atual:<b>" & RS1("nm_tramite") & "</b></td>"
  ShowHTML "    </TABLE>"
  ShowHTML "</TABLE>"
  ShowHTML "  <tr><td>&nbsp;"
  RS1.Close
  If O = "L" Then
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""ss"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_menu=" & w_menu & "&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "                         <a accesskey=""F"" class=""ss"" href=""javascript:window.close(); opener.location.reload(); opener.focus();""><u>F</u>echar</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Tipo de apoio</font></td>"
    ShowHTML "          <td><font size=""1""><b>Entidade</font></td>"
    ShowHTML "          <td><font size=""1""><b>Valor</font></td>"
    ShowHTML "          <td><font size=""1""><b>Inclusão/Alteração</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=4 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & RS("nm_tipo_apoio") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("Entidade") & "</td>"
        ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(RS("valor"),2) & "&nbsp;&nbsp;</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & FormataDataEdicao(RS("ultima_atualizacao")) & "(" & RS("nome_resumido") &")</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_menu=" & w_menu & "&w_chave=" & w_chave & "&w_chave_aux=" & Rs("sq_solic_apoio") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & "GRAVA&R=" & w_Pagina & par & "&O=E&w_menu=" & w_menu & "&w_chave=" & w_chave & "&w_chave_aux=" & Rs("sq_solic_apoio") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ onClick=""return confirm('Confirma a exclusão do registro?');"">Excluir</A>&nbsp"
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
  ElseIf Instr("IAEV",O) > 0 Then
    If InStr("EV",O) Then
       w_Disabled = " DISABLED "
    End If
    AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"PJINFO",R,O
    ShowHTML MontaFiltro("POST")
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_copia"" value=""" & w_copia &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_chave_aux &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_data_hora"" value=""" & RS_menu("data_hora") &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & RS_menu("sq_menu") &""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td valign=""top"" align=""center"" bgcolor=""#D0D0D0""><font size=""2""><b>Apoio</td></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td><font size=1>Identificação do status do projeto.</font></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"    
    SelecaoTipoApoio "<u>T</u>ipo de apoio:", "T", null, w_sq_tipo_apoio, null, "w_sq_tipo_apoio", "ATIVO", null
    ShowHTML "      <tr><td><font size=""1""><b><u>E</u>ntidade:<br><input " & w_Disabled & " accesskey=""E"" type=""text"" name=""w_entidade"" class=""sti"" SIZE=""50"" MAXLENGTH=""50"" VALUE=""" & w_entidade & """></td>"
    ShowHTML "          <td><b><u>V</u>alor:</b><br><input " & w_Disabled & " accesskey=""V"" type=""text"" name=""w_valor"" class=""sti"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_valor & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o valor do patrocínio ou financiamento.""></td>"
    ShowHTML "      <tr><td valign=""top"" colspan=2><b><u>D</u>escrição:</b><br><textarea " & w_Disabled & " accesskey=""D"" name=""w_descricao"" class=""sti"" ROWS=5 cols=75 title=""Escreva um texto que descreva o patrocinio ou financiamento"">" & w_descricao & "</TEXTAREA></td>"
    ShowHTML "      </table>"
    
    ShowHTML "      <tr><td align=""center""><hr>"
    If O = "E" Then
       ShowHTML "   <input class=""stb"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       If O = "I" Then
          ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Incluir"">"
       Else
          ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Atualizar"">"
       End If
    End If
    ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_menu=" & w_menu & "&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=L';"" name=""Botao"" value=""Cancelar"">"
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

  Set w_chave                   = Nothing 
  Set w_chave_aux               = Nothing 
  Set w_sq_tipo_apoio           = Nothing 
  Set w_entidade                = Nothing
  Set w_valor                   = Nothing
  Set w_descricao               = Nothing
  
  Set w_troca               = Nothing 
  Set i                     = Nothing 
  Set w_erro                = Nothing
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de visualização
REM -------------------------------------------------------------------------
Sub Visual

  Dim w_chave, w_Erro, w_logo, w_tipo

  w_chave           = Request("w_chave")
  w_tipo            = uCase(Trim(Request("w_tipo")))

  ' Recupera o logo do cliente a ser usado nas listagens
  DB_GetCustomerData RS, w_cliente
  If RS("logo") > "" Then
     w_logo = "\img\logo" & Mid(RS("logo"),Instr(RS("logo"),"."),30)
  End If
  DesconectaBD
  
  If w_tipo = "WORD" Then
     Response.ContentType = "application/msword"
  Else 
     Cabecalho
  End If

  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>" & conSgSistema & " - Visualização de projeto</TITLE>"
  ShowHTML "</HEAD>"  
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_tipo <> "WORD" Then
     BodyOpenClean "onLoad='document.focus()'; "
  End If
  ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "EMBED") & """><TD ALIGN=""RIGHT""><B><FONT SIZE=4 COLOR=""#000000"">"
  ShowHTML "Visualização de Projeto"
  ShowHTML "</FONT><TR><TD ALIGN=""RIGHT""><B><font COLOR=""#000000"">" & DataHora() & "</B>"
  If w_tipo <> "WORD" Then
     ShowHTML "&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Imprimir"" SRC=""images/impressora.jpg"" onClick=""window.print();"">"
     ShowHTML "&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Gerar word"" SRC=""images/word.gif"" onClick=""window.open('" & w_pagina & "Visual&R=" & w_pagina & par & "&O=L&w_chave=" & w_chave & "&w_tipo=word&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=1&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") &"','VisualAcaoWord','menubar=yes resizable=yes scrollbars=yes');"">"
  End If
  ShowHTML "</FONT></B></TD></TR></TABLE>"
  ShowHTML "<HR>"
  If w_tipo > "" and w_tipo <> "WORD" Then
     ShowHTML "<center><B>Clique <a class=""hl"" href=""javascript:history.back(1);"">aqui</a> para voltar à tela anterior</font></b></center>"
  End If

  ' Chama a rotina de visualização dos dados do projeto, na opção "Listagem"
  ShowHTML VisualProjeto(w_chave, "L", w_usuario, P1, P4)

  If w_tipo > "" and w_tipo <> "WORD" Then
     ShowHTML "<center><B>Clique <a class=""hl"" href=""javascript:history.back(1);"">aqui</a> para voltar à tela anterior</font></b></center>"
  End If

  If w_tipo <> "WORD" Then
     ShowHTML "</body>"
     ShowHTML "</html>"
  End If

  Set w_tipo                = Nothing 
  Set w_erro                = Nothing 
  Set w_logo                = Nothing 
  Set w_chave               = Nothing

End Sub
REM =========================================================================
REM Fim da rotina de visualização
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de visualização
REM -------------------------------------------------------------------------
Sub VisualForm

  Dim w_chave, w_Erro, w_logo, w_tipo, w_sq_pessoa

  w_chave           = Request("w_chave")
  w_tipo            = uCase(Trim(Request("w_tipo")))
  w_sq_pessoa       = Request("w_sq_pessoa")

  ' Recupera o logo do cliente a ser usado nas listagens
  DB_GetCustomerData RS, w_cliente
  If RS("logo") > "" Then
     w_logo = "\img\logo" & Mid(RS("logo"),Instr(RS("logo"),"."),30)
  End If
  DesconectaBD
  
  If w_tipo = "WORD" Then
     Response.ContentType = "application/msword"
  Else 
     Cabecalho
  End If

  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>" & conSgSistema & " - Formulário de Consessão de Passagens</TITLE>"
  ShowHTML "</HEAD>"  
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_tipo <> "WORD" Then
     BodyOpenClean "onLoad='document.focus()'; "
  End If
  ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "EMBED") & """><TD ALIGN=""RIGHT""><B><FONT SIZE=4 COLOR=""#000000"">"
  
  If w_tipo <> "WORD" Then
     ShowHTML "Concessão de Passagens"
     ShowHTML "</FONT><TR><TD ALIGN=""RIGHT""><B><font COLOR=""#000000"">" & DataHora() & "</B>"
     ShowHTML "&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Imprimir"" SRC=""images/impressora.jpg"" onClick=""window.print();"">"
     ShowHTML "&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Gerar word"" SRC=""images/word.gif"" onClick=""window.open('" & w_pagina & "VisualForm&R=" & w_pagina & par & "&O=L&w_chave=" & w_chave & "&w_tipo=word&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=1&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") &"','VisualAcaoWord','menubar=yes resizable=yes scrollbars=yes');"">"
  End If
  ShowHTML "</FONT></B></TD></TR></TABLE>"
  ShowHTML "<HR>"
  If w_tipo > "" and w_tipo <> "WORD" Then
     ShowHTML "<center><B>Clique <a class=""hl"" href=""javascript:history.back(1);"">aqui</a> para voltar à tela anterior</font></b></center>"
  End If

  ' Chama a rotina de visualização dos dados do projeto, na opção "Listagem"
  ShowHTML VisualFormulario(w_chave, "L", w_usuario, w_sq_pessoa, P1, P4)

  If w_tipo > "" and w_tipo <> "WORD" Then
     ShowHTML "<center><B>Clique <a class=""hl"" href=""javascript:history.back(1);"">aqui</a> para voltar à tela anterior</font></b></center>"
  End If

  If w_tipo <> "WORD" Then
     ShowHTML "</body>"
     ShowHTML "</html>"
  End If

  Set w_tipo                = Nothing 
  Set w_erro                = Nothing 
  Set w_logo                = Nothing 
  Set w_chave               = Nothing
  Set w_sq_pessoa           = Nothing

End Sub
REM =========================================================================
REM Fim da rotina de visualização
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de preparação para envio de e-mail relativo a projetos
REM Finalidade: preparar os dados necessários ao envio automático de e-mail
REM Parâmetro: p_solic: número de identificação da solicitação. 
REM            p_tipo:  1 - Inclusão
REM                     2 - Tramitação
REM                     3 - Conclusão
REM -------------------------------------------------------------------------
Sub SolicMail(p_solic, p_tipo)

  Dim w_cab, w_html, w_texto, w_solic, RSM, w_resultado, w_destinatarios
  Dim w_assunto, w_assunto1, l_solic, w_nome
  
  l_solic         = p_solic
  w_destinatarios = ""
  w_resultado     = ""
  
  w_html = "<HTML>" & VbCrLf
  w_html = w_html & BodyOpenMail(null) & VbCrLf
  w_html = w_html & "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">" & VbCrLf
  w_html = w_html & "<tr bgcolor=""" & conTrBgColor & """><TD align=""center"">" & VbCrLf
  w_html = w_html & "    <table width=""97%"" border=""0"">" & VbCrLf
  If p_tipo = 1 Then
     w_html = w_html & "      <tr valign=""top""><TD align=""center""><b>INCLUSÃO DE PROJETO</b></font><br><br><TD></tr>" & VbCrLf
  ElseIf p_tipo = 2 Then
     w_html = w_html & "      <tr valign=""top""><TD align=""center""><b>TRAMITAÇÃO DE PROJETO</b></font><br><br><TD></tr>" & VbCrLf
  ElseIf p_tipo = 3 Then
     w_html = w_html & "      <tr valign=""top""><TD align=""center""><b>CONCLUSÃO DE PROJETO</b></font><br><br><TD></tr>" & VbCrLf
  End IF
  w_html = w_html & "      <tr valign=""top""><TD><b><font color=""#BC3131"">ATENÇÃO</font>: Esta é uma mensagem de envio automático. Não responda esta mensagem.</b></font><br><br><TD></tr>" & VbCrLf


  ' Recupera os dados do projeto
  DB_GetSolicData RSM, p_solic, "PJGERAL"
  
  w_nome = "Projeto " & RSM("titulo") & " (" & RSM("sq_siw_solicitacao") & ")"

  w_html = w_html & VbCrLf & "<tr bgcolor=""" & conTrBgColor & """><TD align=""center"">"
  w_html = w_html & VbCrLf & "    <table width=""99%"" border=""0"">"
  w_html = w_html & VbCrLf & "      <tr><TD>Projeto: <b>" & RSM("titulo") & " (" & RSM("sq_siw_solicitacao") & ")</b></font></td>"
      
  ' Identificação do projeto
  w_html = w_html & VbCrLf & "      <tr><TD valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><b>EXTRATO DO PROJETO</td>"
  ' Se a classificação foi informada, exibe.
  If Not IsNull(RSM("sq_cc")) Then
     w_html = w_html & VbCrLf & "      <tr><TD valign=""top"">Classificação:<br><b>" & RSM("cc_nome") & " </b></td>"
  End If
  w_html = w_html & VbCrLf & "      <tr><TD valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
  w_html = w_html & VbCrLf & "          <tr valign=""top"">"
  w_html = w_html & VbCrLf & "          <TD>Gerente:<br><b>" & RSM("nm_sol") & "</b></td>"
  w_html = w_html & VbCrLf & "          <TD>Unidade responsável:<br><b>" & RSM("nm_unidade_resp") & "</b></td>"
  w_html = w_html & VbCrLf & "          <tr valign=""top"">"
  w_html = w_html & VbCrLf & "          <TD>Início:<br><b>" & FormataDataEdicao(RSM("inicio")) & " </b></td>"
  w_html = w_html & VbCrLf & "          <TD>Fim:<br><b>" & FormataDataEdicao(RSM("fim")) & " </b></td>"
  w_html = w_html & VbCrLf & "          <TD>Prioridade:<br><b>" & RetornaPrioridade(RSM("prioridade")) & " </b></td>"
  w_html = w_html & VbCrLf & "          </table>"

  ' Informações adicionais
  If Nvl(RSM("descricao"),"") > "" Then 
     If Nvl(RSM("descricao"),"") > "" Then w_html = w_html & VbCrLf & "      <tr><TD valign=""top"">Resultados do projeto:<br><b>" & CRLF2BR(RSM("descricao")) & " </b></td>" End If
  End If

  w_html = w_html & VbCrLf & "    </table>"
  w_html = w_html & VbCrLf & "</tr>"

  ' Dados da conclusão do projeto, se ela estiver nessa situação
  If RSM("concluida") = "S" and Nvl(RSM("data_conclusao"),"") > "" Then
     w_html = w_html & VbCrLf & "      <tr><TD valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><b>DADOS DA CONCLUSÃO</td>"
     w_html = w_html & VbCrLf & "      <tr><TD valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
     w_html = w_html & VbCrLf & "          <tr valign=""top"">"
     w_html = w_html & VbCrLf & "          <TD>Início da execução:<br><b>" & FormataDataEdicao(RSM("inicio_real")) & " </b></td>"
     w_html = w_html & VbCrLf & "          <TD>Término da execução:<br><b>" & FormataDataEdicao(RSM("fim_real")) & " </b></td>"
     w_html = w_html & VbCrLf & "          </table>"
     w_html = w_html & VbCrLf & "      <tr><TD valign=""top"">Nota de conclusão:<br><b>" & CRLF2BR(RSM("nota_conclusao")) & " </b></td>"
  End If

  If p_tipo = 2 Then ' Se for tramitação
     ' Encaminhamentos
     DB_GetSolicLog RS, p_solic, null, "LISTA"
     RS.Sort = "data desc"
     w_html = w_html & VbCrLf & "      <tr><TD valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><b>ÚLTIMO ENCAMINHAMENTO</td>"
     w_html = w_html & VbCrLf & "      <tr><TD valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
     w_html = w_html & VbCrLf & "          <tr valign=""top"">"
     w_html = w_html & VbCrLf & "          <TD>De:<br><b>" & RS("responsavel") & "</b></td>"
     w_html = w_html & VbCrLf & "          <TD>Para:<br><b>" & RS("destinatario") & "</b></td>"
     w_html = w_html & VbCrLf & "          <tr valign=""top""><TD colspan=2>Despacho:<br><b>" & CRLF2BR(Nvl(RS("despacho"),"---")) & " </b></td>"
     w_html = w_html & VbCrLf & "          </table>"
     
     ' Configura o destinatário da tramitação como destinatário da mensagem
     DB_GetPersonData RS, w_cliente, RS("sq_pessoa_destinatario"), null, null
     w_destinatarios = RS("email") & "; "
     
     DesconectaBD
  End If

  w_html = w_html & VbCrLf & "      <tr><TD valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><b>OUTRAS INFORMAÇÕES</td>"
  DB_GetCustomerSite RS, Session("p_cliente")
  w_html = w_html & "      <tr valign=""top""><TD>" & VbCrLf
  w_html = w_html & "         Para acessar o sistema use o endereço: <b><a class=""ss"" href=""" & RS("logradouro") & """ target=""_blank"">" & RS("Logradouro") & "</a></b></li>" & VbCrLf
  w_html = w_html & "      </font></td></tr>" & VbCrLf
  DesconectaBD

  w_html = w_html & "      <tr valign=""top""><TD>" & VbCrLf
  w_html = w_html & "         Dados da ocorrência:<br>" & VbCrLf
  w_html = w_html & "         <ul>" & VbCrLf
  w_html = w_html & "         <li>Responsável: <b>" & Session("nome") & "</b></li>" & VbCrLf
  w_html = w_html & "         <li>Data do servidor: <b>" & FormatDateTime(Date(),1) & ", " & Time() & "</b></li>" & VbCrLf
  w_html = w_html & "         <li>IP de origem: <b>" & Request.ServerVariables("REMOTE_HOST") & "</b></li>" & VbCrLf
  w_html = w_html & "         </ul>" & VbCrLf
  w_html = w_html & "      </font></td></tr>" & VbCrLf
  w_html = w_html & "    </table>" & VbCrLf
  w_html = w_html & "</td></tr>" & VbCrLf
  w_html = w_html & "</table>" & VbCrLf
  w_html = w_html & "</BODY>" & VbCrLf
  w_html = w_html & "</HTML>" & VbCrLf

  ' Recupera o e-mail do gerente
  DB_GetPersonData RS, w_cliente, RSM("solicitante"), null, null
  If Instr(w_destinatarios,RS("email") & "; ") = 0 Then w_destinatarios = w_destinatarios & RS("email") & "; " End If
  DesconectaBD
  
  ' Recupera o e-mail do titular e do substituto pelo setor responsável
  DB_GetUorgResp RS, RSM("sq_unidade")
  If Instr(w_destinatarios,RS("email_titular") & "; ") = 0    and Nvl(RS("email_titular"),"nulo") <> "nulo"    Then w_destinatarios = w_destinatarios & RS("email_titular") & "; "    End If
  If Instr(w_destinatarios,RS("email_substituto") & "; ") = 0 and Nvl(RS("email_substituto"),"nulo") <> "nulo" Then w_destinatarios = w_destinatarios & RS("email_substituto") & "; " End If
  DesconectaBD
  
  ' Prepara os dados necessários ao envio
  DB_GetCustomerData RS, Session("p_cliente")
  If p_tipo = 1 or p_tipo = 3 Then ' Inclusão ou Conclusão
     If p_tipo = 1 Then w_assunto = "Inclusão - " & w_nome Else w_assunto = "Conclusão - " & w_nome End If
  ElseIf p_tipo = 2 Then ' Tramitação
     w_assunto = "Tramitação - " & w_nome
  End If
  DesconectaBD

  If w_destinatarios > "" Then
     ' Executa o envio do e-mail
     w_resultado = EnviaMail(w_assunto, w_html, w_destinatarios)
  End If
        
  ' Se ocorreu algum erro, avisa da impossibilidade de envio
  If w_resultado > "" Then 
     ScriptOpen "JavaScript"
     ShowHTML "  alert('ATENÇÃO: não foi possível proceder o envio do e-mail.\n" & w_resultado & "');" 
     ScriptClose
  End If

  Set RSM                      = Nothing
  Set w_html                   = Nothing
  Set p_solic                  = Nothing
  Set w_destinatarios          = Nothing
  Set w_assunto                = Nothing
End Sub
REM =========================================================================
REM Fim da rotina da preparação para envio de e-mail
REM -------------------------------------------------------------------------

REM =========================================================================
REM Procedimento que executa as operações de BD
REM -------------------------------------------------------------------------
Public Sub Grava
  Dim p_sq_endereco_unidade
  Dim p_modulo
  Dim w_Null
  Dim w_chave_nova
  Dim w_mensagem
  Dim FS, F1
  Dim w_cpf, w_sq_pessoa, w_o

  ShowHTML "<HTML>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  BodyOpen "onLoad=document.focus();"
  
  AbreSessao    
  Select Case SG
     Case "PJPSG"
        ' Verifica se a Assinatura Eletrônica é válida
        If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
           w_assinatura = "" Then
 
           'ExibeVariaveis
           w_o         = O
           w_cpf       = uCase(Request("w_cpf"))
           w_sq_pessoa = Request("w_sq_pessoa")
           
           ' Se o usuário tiver solicitado a geração de CPF.
           If w_cpf = "GERAR" Then
              
              ' Verifica se o passaporte informado já está associado a outra pessoa
              ' Se estiver, o sistema irá alterar os dados da pessoa existente pelos dados
              ' informados no formulário.
              DB_GetBenef RS, w_cliente, null, null, null, null, 1, Request("w_passaporte_numero"), Request("w_sq_pais_passaporte")
              If Not RS.EOF Then
                 ' Se o passaporte foi encontrado, recupera o CPF e o código da pessoa
                 w_cpf       = RS("cpf")
                 w_sq_pessoa = RS("sq_pessoa")

                 ' Verifica se a pessoa já é beneficiária deste projeto.
                 ' Se for, configura a operação para alteração.
                 DB_GetViagemBenef RS1, Request("w_chave"), w_cliente, w_sq_pessoa, null, w_cpf, null, null, null, null
                 If Not RS1.EOF Then w_o = "A" End If
              Else
                 ' Gera CPF
                 w_cpf = GeraCpfEspecial(1)
              End If
           ElseIf Nvl(Request("w_passaporte_numero"),"") > "" Then
              ' Verifica se o passaporte informado já está associado a outra pessoa
              ' Se estiver e o código da pessoa existente for diferente da pessoa indicada
              ' no formulário, o sistema apresentará mensagem de erro e não realizará a alteração.
              DB_GetBenef RS, w_cliente, null, null, null, null, 1, Request("w_passaporte_numero"), Request("w_sq_pais_passaporte")
              If Not RS.EOF Then
                 If cDbl(Request("w_sq_pessoa")) <> cDbl(RS("sq_pessoa")) Then
                    ScriptOpen "JavaScript"
                    ShowHTML "  alert('Passaporte já associado a " & RS("nm_pessoa") & "');"
                    ShowHTML "  history.back(1);"
                    ScriptClose
                    Response.End()
                    Exit Sub
                 End If
              End If
           End If
           
           DML_PutViagemBenef w_o, null, Request("w_chave"), Request("w_chave_aux"), _
               w_sq_pessoa, w_cpf, Request("w_nome"), _
               Request("w_nome_resumido"), Request("w_sexo"),  Request("w_rg_numero"), Request("w_rg_emissao"), Request("w_rg_emissor"), _
               Request("w_ddd"), Request("w_nr_telefone"), Request("w_nr_fax"), _
               Request("w_passaporte_numero"), Request("w_sq_pais_passaporte"), Request("w_data_saida"), _
               Request("w_data_volta"), Request("w_valor"), Request("w_sq_cidade_origem"), Request("w_sq_cidade_destino"), _
               Request("w_reserva"), Request("w_bilhete"), Request("w_trechos"), Request("w_sq_viagem")
              
           ScriptOpen "JavaScript"
           ShowHTML "  location.href='" & R & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
           ScriptClose
        Else
           ScriptOpen "JavaScript"
           ShowHTML "  alert('Assinatura Eletrônica inválida!');"
           ShowHTML "  history.back(1);"
           ScriptClose
        End If
    Case "PJINFO"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          
          'ExibeVariaveis
          DML_PutSolicApoio O, Request("w_chave"), Request("w_chave_aux"), Request("w_sq_tipo_apoio"), _
                            Request("w_entidade"), Request("w_descricao"), Request("w_valor"), w_usuario
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & "PJCAD" & MontaFiltro("GET") & "';"
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

  Set w_sq_pessoa           = Nothing
  Set w_o                   = Nothing
  Set w_cpf                 = Nothing
  Set FS                    = Nothing
  Set w_Mensagem            = Nothing
  Set w_chave_nova          = Nothing
  Set p_sq_endereco_unidade = Nothing
  Set p_modulo              = Nothing
  Set w_Null                = Nothing
End Sub
REM -------------------------------------------------------------------------
REM Fim do procedimento que executa as operações de BD
REM =========================================================================

REM =========================================================================
REM Rotina principal
REM -------------------------------------------------------------------------
Sub Main
  Select Case Par
    Case "INICIAL"       Inicial
    Case "PASSAGENS"     Passagens
    Case "INFORMAR"      Informar
    Case "VISUAL"        Visual
    Case "VISUALFORM"    VisualForm
    Case "GRAVA"         Grava
    Case Else
       Cabecalho
       ShowHTML "<HEAD>"
       ShowHTML "<TITLE>" & conSgSistema & " - Listagem de projetos</TITLE>"
       ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & replace(MontaURL("MESA"),w_dir,"") & """>"
       Estrutura_CSS w_cliente
       ShowHTML "</HEAD>"
       ShowHTML "<BASE HREF=""" & conRootSIW & """>"

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
  End Select
End Sub
REM =========================================================================
REM Fim da rotina principal
REM -------------------------------------------------------------------------
%>