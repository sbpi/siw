<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE FILE="Constants.inc" -->
<!-- #INCLUDE FILE="DB_Geral.asp" -->
<!-- #INCLUDE FILE="DB_Cliente.asp" -->
<!-- #INCLUDE FILE="DB_Seguranca.asp" -->
<!-- #INCLUDE FILE="DB_Link.asp" -->
<!-- #INCLUDE FILE="DB_EO.asp" -->
<!-- #INCLUDE FILE="DML_Solic.asp" -->
<!-- #INCLUDE FILE="DML_Projeto.asp" -->
<!-- #INCLUDE FILE="jScript.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="VisualProjeto.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /Projeto.asp
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
Dim dbms, sp, RS, RS1, RS2, RS3, RS4, RS_menu
Dim P1, P2, P3, P4, TP, SG
Dim R, O, w_Cont, w_Reg, w_Pagina, w_Disabled, w_TP, w_classe, w_submenu, w_filtro, w_copia
Dim w_Assinatura
Dim p_ativo, p_solicitante, p_prioridade, p_unidade, p_proponente, p_ordena
Dim p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_projeto, p_atividade
Dim p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc
Dim w_troca,w_cor, w_filter, w_cliente, w_usuario, w_menu
Dim w_sq_pessoa
Dim ul,File
Dim w_dir, w_dir_volta
Set RS  = Server.CreateObject("ADODB.RecordSet")
Set RS1 = Server.CreateObject("ADODB.RecordSet")
Set RS2 = Server.CreateObject("ADODB.RecordSet")
Set RS3 = Server.CreateObject("ADODB.RecordSet")
Set RS4 = Server.CreateObject("ADODB.RecordSet")
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

w_Pagina     = "Projeto.asp?par="
w_Disabled   = "ENABLED"

If SG="PJRECURSO" or SG="PJETAPA" or SG = "PJINTERESS" or SG = "PJAREAS" or SG = "PJANEXO" Then
   If O <> "I" and Request("w_chave_aux") = "" Then O = "L" End If
ElseIf SG = "PJENVIO" Then 
   O = "V" 
ElseIf SG="PJVISUAL" and O = "A" Then 
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

w_cliente         = RetornaCliente()
w_usuario         = RetornaUsuario()
If SG <> "ETAPAREC" Then 
   w_menu         = RetornaMenu(w_cliente, SG) 
Else
   w_menu         = RetornaMenu(w_cliente, Request("w_SG")) 
End If

If InStr(uCase(Request.ServerVariables("http_content_type")),"MULTIPART/FORM-DATA") > 0 Then  
   ' Cria o objeto de upload
   Set ul       = Nothing
   Set ul       = Server.CreateObject("Dundas.Upload.2")
   ul.SaveToMemory  

   P1           = ul.Form("P1")
   P2           = ul.Form("P2")
   P3           = ul.Form("P3")
   P4           = ul.Form("P4")
   TP           = ul.Form("TP")
   R            = uCase(ul.Form("R"))
   w_Assinatura = uCase(ul.Form("w_Assinatura"))
End If

' Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
If SG <> "ETAPAREC" Then 
   DB_GetLinkSubMenu RS, Session("p_cliente"), SG
Else
   DB_GetLinkSubMenu RS, Session("p_cliente"), Request("w_SG")
End IF
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

Set w_dir         = Nothing
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
           w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Projeto <td><font size=1>[<b>" & RS("titulo") & "</b>]"
        End If
        If p_atividade > ""  Then 
           DB_GetSolicEtapa RS, p_projeto, p_atividade, "REGISTRO"
           w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Etapa <td><font size=1>[<b>" & RS("titulo") & "</b>]"
        End If
        If p_sqcc > ""  Then 
           DB_GetCCData RS, p_sqcc
           w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Classificação <td><font size=1>[<b>" & RS("nome") & "</b>]"
        End If
        If p_chave       > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Demanda nº <td><font size=1>[<b>" & p_chave & "</b>]" End If
        If p_prazo       > ""  Then w_filtro = w_filtro & " <tr valign=""top""><td align=""right""><font size=1>Prazo para conclusão até<td><font size=1>[<b>" & FormatDateTime(DateAdd("d",p_prazo,Date()),1) & "</b>]" End If
        If p_solicitante > ""  Then
           DB_GetPersonData RS, w_cliente, p_solicitante, null, null
           w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Responsável <td><font size=1>[<b>" & RS("nome_resumido") & "</b>]"
        End If
        If p_unidade     > ""  Then 
           DB_GetUorgData RS, p_unidade
           w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Unidade responsável <td><font size=1>[<b>" & RS("nome") & "</b>]"
        End If
        If p_usu_resp > ""  Then
           DB_GetPersonData RS, w_cliente, p_usu_resp, null, null
           w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Executor <td><font size=1>[<b>" & RS("nome_resumido") & "</b>]"
        End If
        If p_uorg_resp > ""  Then 
           DB_GetUorgData RS, p_uorg_resp
           w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Unidade atual <td><font size=1>[<b>" & RS("nome") & "</b>]"
        End If
        If p_pais > ""  Then 
           DB_GetCountryData RS, p_pais
           w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>País <td><font size=1>[<b>" & RS("nome") & "</b>]"
        End If
        If p_regiao > ""  Then 
           DB_GetRegionData RS, p_regiao
           w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Região <td><font size=1>[<b>" & RS("nome") & "</b>]"
        End If
        If p_uf > ""  Then 
           DB_GetStateData RS, p_pais, p_uf
           w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Estado <td><font size=1>[<b>" & RS("nome") & "</b>]"
        End If
        If p_cidade > ""  Then 
           DB_GetCityData RS, p_cidade
           w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Cidade <td><font size=1>[<b>" & RS("nome") & "</b>]"
        End If
        If p_prioridade  > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Prioridade <td><font size=1>[<b>" & RetornaPrioridade(p_prioridade) & "</b>]"   End If
        If p_proponente  > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Proponente <td><font size=1>[<b>" & p_proponente & "</b>]"                      End If
        If p_assunto     > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Assunto <td><font size=1>[<b>" & p_assunto & "</b>]"                            End If
        If p_palavra     > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Palavras-chave <td><font size=1>[<b>" & p_palavra & "</b>]"                     End If
        If p_ini_i       > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Recebimento <td><font size=1>[<b>" & p_ini_i & "-" & p_ini_f & "</b>]"          End If
        If p_fim_i       > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Conclusão <td><font size=1>[<b>" & p_fim_i & "-" & p_fim_f & "</b>]"            End If
        If p_atraso      = "S" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Situação <td><font size=1>[<b>Apenas atrasadas</b>]"                            End If
        If w_filtro > "" Then w_filtro = "<table border=0><tr valign=""top""><td><font size=1><b>Filtro:</b><td nowrap><font size=1><ul>" & w_filtro & "</ul></tr></table>"                    End If
     End If

     DB_GetLinkData RS, w_cliente, "PJCAD"
     If w_copia > "" Then ' Se for cópia, aplica o filtro sobre todas as demandas visíveis pelo usuário
        DB_GetSolicList rs, RS("sq_menu"), w_usuario, SG, 3, _
           p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
           p_unidade, p_prioridade, p_ativo, p_proponente, _
           p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
           p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, p_projeto, p_atividade, null, null
     Else
        DB_GetSolicList rs, RS("sq_menu"), w_usuario, SG, P1, _
           p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
           p_unidade, p_prioridade, p_ativo, p_proponente, _
           p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
           p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, p_projeto, p_atividade, null, null
     End If
        
     If p_ordena > "" Then RS.sort = p_ordena Else RS.sort = "fim, prioridade" End If
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & MontaURL("MESA") & """>" End If
  ShowHTML "<TITLE>" & conSgSistema & " - Listagem de projetos</TITLE>"
  ScriptOpen "Javascript"
  CheckBranco
  FormataData
  ValidateOpen "Validacao"
  If Instr("CP",O) > 0 Then
     If P1 <> 1 or O = "C" Then ' Se não for cadastramento ou se for cópia
        Validate "p_chave", "Número do projeto", "", "", "1", "18", "", "0123456789"
        Validate "p_prazo", "Dias para a data limite", "", "", "1", "2", "", "0123456789"
        Validate "p_proponente", "Proponente externo", "", "", "2", "90", "1", ""
        Validate "p_assunto", "Assunto", "", "", "2", "90", "1", "1"
        Validate "p_palavra", "Palavras-chave", "", "", "2", "90", "1", "1"
        Validate "p_ini_i", "Recebimento inicial", "DATA", "", "10", "10", "", "0123456789/"
        Validate "p_ini_f", "Recebimento final", "DATA", "", "10", "10", "", "0123456789/"
        ShowHTML "  if ((theForm.p_ini_i.value != '' && theForm.p_ini_f.value == '') || (theForm.p_ini_i.value == '' && theForm.p_ini_f.value != '')) {"
        ShowHTML "     alert ('Informe ambas as datas de recebimento ou nenhuma delas!');"
        ShowHTML "     theForm.p_ini_i.focus();"
        ShowHTML "     return false;"
        ShowHTML "  }"
        CompData "p_ini_i", "Recebimento inicial", "<=", "p_ini_f", "Recebimento final"
        Validate "p_fim_i", "Conclusão inicial", "DATA", "", "10", "10", "", "0123456789/"
        Validate "p_fim_f", "Conclusão final", "DATA", "", "10", "10", "", "0123456789/"
        ShowHTML "  if ((theForm.p_fim_i.value != '' && theForm.p_fim_f.value == '') || (theForm.p_fim_i.value == '' && theForm.p_fim_f.value != '')) {"
        ShowHTML "     alert ('Informe ambas as datas de conclusão ou nenhuma delas!');"
        ShowHTML "     theForm.p_fim_i.focus();"
        ShowHTML "     return false;"
        ShowHTML "  }"
        CompData "p_fim_i", "Conclusão inicial", "<=", "p_fim_f", "Conclusão final"
     End If
     Validate "P4", "Linhas por página", "1", "1", "1", "4", "", "0123456789"
  End If
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  If w_Troca > "" Then ' Se for recarga da página
     BodyOpenClean "onLoad='document.Form." & w_Troca & ".focus();'"
  ElseIf O = "I" Then
     BodyOpenClean "onLoad='document.Form.w_smtp_server.focus();'"
  ElseIf O = "A" Then
     BodyOpenClean "onLoad='document.Form.w_nome.focus();'"
  ElseIf O = "E" Then
     BodyOpenClean "onLoad='document.Form.w_assinatura.focus()';"
  ElseIf InStr("CP",O) > 0 Then
     If P1 <> 1 or O = "C" Then ' Se for cadastramento
        BodyOpenClean "onLoad='document.Form.p_chave.focus()';"
     Else
        BodyOpenClean "onLoad='document.Form.p_ordena.focus()';"
     End if
  Else
     BodyOpenClean "onLoad=document.focus();"
  End If
    Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  If w_filtro > "" Then ShowHTML w_filtro End If
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ShowHTML "<tr><td><font size=""2"">"
    If P1 = 1 and w_copia = "" Then ' Se for cadastramento e não for resultado de busca para cópia
       If w_submenu > "" Then
          DB_GetLinkSubMenu RS1, w_cliente, Request("SG")
          ShowHTML "<tr><td><font size=""1"">"
          ShowHTML "    <a accesskey=""I"" class=""SS"" href=""" & w_Pagina & "Geral&R=" & w_Pagina & par & "&O=I&SG=" & RS1("sigla") & "&w_menu=" & w_menu & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;"
          ShowHTML "    <a accesskey=""C"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=C&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>C</u>opiar</a>"
       Else
          ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;"
       End If
    End If
    If Instr(uCase(R),"GR_") = 0 and P1 <> 6 Then
       If w_copia > "" Then ' Se for cópia
          If MontaFiltro("GET") > "" Then
             ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=C&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a>"
          Else
             ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=C&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>F</u>iltrar (Inativo)</a>"
          End If
       Else
          If MontaFiltro("GET") > "" Then
             ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a>"
          Else
             ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>F</u>iltrar (Inativo)</a>"
          End If
       End If
    End If
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td rowspan=2><font size=""1""><b>" & LinkOrdena("Nº","sq_siw_solicitacao") & "</font></td>"
    ShowHTML "          <td rowspan=2><font size=""1""><b>" & LinkOrdena("Responsável","nm_solic") & "</font></td>"
    If Session("interno") = "S" Then ShowHTML "          <td rowspan=2><font size=""1""><b>" & LinkOrdena("Executor","nm_exec") & "</font></td>" End If
    If P1 = 1 or P1 = 2 Then ' Se for cadastramento ou mesa de trabalho
       ShowHTML "          <td rowspan=2><font size=""1""><b>" & LinkOrdena("Projeto","titulo") & "</font></td>"
       ShowHTML "          <td colspan=2><font size=""1""><b>Execução</font></td>"
    Else
       ShowHTML "          <td rowspan=2><font size=""1""><b>" & LinkOrdena("Proponente","proponente") & "</font></td>"
       ShowHTML "          <td rowspan=2><font size=""1""><b>" & LinkOrdena("Projeto","titulo") & "</font></td>"
       ShowHTML "          <td colspan=2><font size=""1""><b>Execução</font></td>"
       If Session("interno") = "S" Then ShowHTML "          <td rowspan=2><font size=""1""><b>" & LinkOrdena("Valor","valor") & "</font></td>" End If
       ShowHTML "          <td rowspan=2><font size=""1""><b>" & LinkOrdena("Fase atual","nm_tramite") & "</font></td>"
    End If
    If Session("interno") = "S" Then ShowHTML "          <td rowspan=2><font size=""1""><b>Operações</font></td>" End If
    ShowHTML "        </tr>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("De","inicio") & "</font></td>"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Até","fim") & "</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=10 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      w_parcial       = 0
      While Not RS.EOF and RS.AbsolutePage = P3
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td nowrap><font size=""1"">"
        If RS("concluida") = "N" Then
           If RS("fim") < Date() Then
              ShowHTML "           <img src=""" & conImgAtraso & """ border=0 width=15 heigth=15 align=""center"">"
           ElseIf RS("aviso_prox_conc") = "S" and (RS("aviso") <= Date()) Then
              ShowHTML "           <img src=""" & conImgAviso & """ border=0 width=15 height=15 align=""center"">"
           Else
              ShowHTML "           <img src=""" & conImgNormal & """ border=0 width=15 height=15 align=""center"">"
           End IF
        Else
           If RS("fim") < Nvl(RS("fim_real"),RS("fim")) Then
              ShowHTML "           <img src=""" & conImgOkAtraso & """ border=0 width=15 heigth=15 align=""center"">"
           Else
              ShowHTML "           <img src=""" & conImgOkNormal & """ border=0 width=15 height=15 align=""center"">"
           End IF
        End If
        ShowHTML "        <A class=""HL"" HREF=""" & w_Pagina & "Visual&R=" & w_Pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exibe as informações deste registro."">" & RS("sq_siw_solicitacao") & "&nbsp;</a>"
        ShowHTML "        <td><font size=""1"">" & ExibePessoa(null, w_cliente, RS("solicitante"), TP, RS("nm_solic")) & "</td>"
        If Session("interno") = "S" Then
           If Nvl(RS("nm_exec"),"---") > "---" Then
              ShowHTML "        <td><font size=""1"">" & ExibePessoa(null, w_cliente, RS("executor"), TP, RS("nm_exec")) & "</td>"
           Else
              ShowHTML "        <td><font size=""1"">---</td>"
           End IF
        End If
        If (P1 <> 1 and P1 <> 2) or (P1 = 3 and Session("interno") = "N") Then ' Se não for cadastramento nem mesa de trabalho
           ShowHTML "        <td><font size=""1"">" & Nvl(RS("proponente"),"---") & "</td>"
        End If
        ' Verifica se foi enviado o parâmetro p_tamanho = N. Se chegou, o assunto deve ser exibido sem corte.
        ' Este parâmetro é enviado pela tela de filtragem das páginas gerenciais
        If Request("p_tamanho") = "N" Then
           ShowHTML "        <td><font size=""1"">" & Nvl(RS("titulo"),"-") & "</td>"
        Else
           If Len(Nvl(RS("titulo"),"-")) > 50 Then w_titulo = Mid(Nvl(RS("titulo"),"-"),1,50) & "..." Else w_titulo = Nvl(RS("titulo"),"-") End If
           If RS("sg_tramite") = "CA" Then
              ShowHTML "        <td title=""" & Server.HTMLEncode(RS("titulo")) & """><font size=""1""><strike>" & Server.HTMLEncode(w_titulo) & "</strike></td>"
           Else
              ShowHTML "        <td title=""" & Server.HTMLEncode(RS("titulo")) & """><font size=""1"">" & Server.HTMLEncode(w_titulo) & "</td>"
           End IF
        End If
        ShowHTML "        <td align=""center""><font size=""1"">&nbsp;" & FormataDataEdicao(RS("inicio")) & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">&nbsp;" & FormataDataEdicao(RS("fim")) & "</td>"
        ' Mostra o valor se o usuário for interno e não for cadastramento nem mesa de trabalho
        If P1 <> 1 and P1 <> 2 Then 
           If Session("interno") = "S" Then
              If RS("sg_tramite") = "AT" Then
                 ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(RS("custo_real"),2) & "&nbsp;</td>"
                 w_parcial = w_parcial + cDbl(RS("custo_real"))
              Else
                 ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(RS("valor"),2) & "&nbsp;</td>"
                 w_parcial = w_parcial + cDbl(RS("valor"))
              End If
           End If
           ShowHTML "        <td nowrap><font size=""1"">" & RS("nm_tramite") & "</td>"
        End If
        If Session("interno") = "S" Then 
           ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
           If P1 <> 3 Then ' Se não for acompanhamento
              If w_copia > "" Then ' Se for listagem para cópia
                 DB_GetLinkSubMenu RS1, w_cliente, Request("SG")
                 ShowHTML "          <a accesskey=""I"" class=""HL"" href=""" & w_Pagina & "Geral&R=" & w_Pagina & par & "&O=I&SG=" & RS1("sigla") & "&w_menu=" & w_menu & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&w_copia=" & RS("sq_siw_solicitacao") & MontaFiltro("GET") & """>Copiar</a>&nbsp;"
              ElseIf P1 = 1 Then ' Se for cadastramento
                 If w_submenu > "" Then
                    ShowHTML "          <A class=""HL"" HREF=""Menu.asp?par=ExibeDocs&O=A&w_chave=" & RS("sq_siw_solicitacao") & "&R=" & w_Pagina & par & "&SG=" & SG & "&TP=" & TP & "&w_documento=Nr. " & RS("sq_siw_solicitacao") & MontaFiltro("GET") & """ title=""Altera as informações cadastrais do projeto"" TARGET=""menu"">Alterar</a>&nbsp;"
                 Else
                    ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & RS("sq_siw_solicitacao") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Altera as informações cadastrais do projeto"">Alterar</A>&nbsp"
                 End If
                 ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & "Excluir&R=" & w_Pagina & par & "&O=E&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exclusão do projeto."">Excluir</A>&nbsp"
              ElseIf P1 = 2 or P1 = 6 Then ' Se for execução ou consulta de usuário externo
                 If cDbl(w_usuario) = cDbl(RS("executor")) Then
                    If cDbl(Nvl(RS("solicitante"),0)) = cDbl(w_usuario) or _
                       cDbl(Nvl(RS("titular"),0))     = cDbl(w_usuario) or _
                       cDbl(Nvl(RS("substituto"),0))  = cDbl(w_usuario) or _
                       cDbl(Nvl(RS("tit_exec"),0))    = cDbl(w_usuario) or _
                       cDbl(Nvl(RS("subst_exec"),0))  = cDbl(w_usuario) _
                    Then
                       ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & "AtualizaEtapa&R=" & w_Pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Atualiza as etapas do projeto."" target=""Etapas"">Etapas</A>&nbsp"
                    End If
                    ' Coloca as operações dependendo do trâmite
                    If RS("sg_tramite") = "EA" Then
                       ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & "Anotacao&R=" & w_Pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Registra anotações para o projeto, sem enviá-la."">Anotar</A>&nbsp"
                       ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & "Envio&R=" & w_Pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Envia o projeto para outro responsável."">Enviar</A>&nbsp"
                    ElseIf RS("sg_tramite") = "EE" Then
                       ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & "Anotacao&R=" & w_Pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Registra anotações para o projeto, sem enviá-la."">Anotar</A>&nbsp"
                       ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & "Envio&R=" & w_Pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Envia o projeto para outro responsável."">Enviar</A>&nbsp"
                       ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & "Concluir&R=" & w_Pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Conclui a execução do projeto."">Concluir</A>&nbsp"
                    End If
                 Else
                    ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & "AtualizaEtapa&R=" & w_Pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Atualiza as etapas do projeto."" target=""Etapas"">Etapas</A>&nbsp"
                    If Session("interno") = "S" Then ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & "Envio&R=" & w_Pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Envia o projeto para outro responsável."">Enviar</A>&nbsp" End If
                 End If
              End If
           Else
              If cDbl(Nvl(RS("solicitante"),0)) = cDbl(w_usuario) or _
                 cDbl(Nvl(RS("titular"),0))     = cDbl(w_usuario) or _
                 cDbl(Nvl(RS("substituto"),0))  = cDbl(w_usuario) or _
                 cDbl(Nvl(RS("resp_etapa"),0))  > cDbl(0) _
              Then
                 ' Se o usuário for responsável por um projeto ou titular/substituto do setor responsável, 
                 ' pode enviar.
                 If cDbl(Nvl(RS("solicitante"),0)) = cDbl(w_usuario) or _
                    cDbl(Nvl(RS("titular"),0))     = cDbl(w_usuario) or _
                    cDbl(Nvl(RS("substituto"),0))  = cDbl(w_usuario) _
                 Then
                    ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & "envio&R=" & w_Pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Envia o projeto para outro responsável."">Enviar</A>&nbsp"
                 End If
              End If

              ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & "AtualizaEtapa&R=" & w_Pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Atualiza as etapas do projeto."" target=""Etapas"">Etapas</A>&nbsp"

           End If
           ShowHTML "        </td>"
        End If
        ShowHTML "      </tr>"
        RS.MoveNext
      wend

      ' Mostra os valor se o usuário for interno e não for cadastramento nem mesa de trabalho
      If P1 <> 1 and P1 <> 2 and Session("interno") = "S" Then 
         ' Coloca o valor parcial apenas se a listagem ocupar mais de uma página
         If RS.PageCount > 1 Then
            ShowHTML "        <tr bgcolor=""" & conTrBgColor & """>"
            ShowHTML "          <td colspan=7 align=""right""><font size=""1""><b>Total desta página&nbsp;</font></td>"
            ShowHTML "          <td align=""right""><font size=""1""><b>" & FormatNumber(w_parcial,2) & "&nbsp;</font></td>"
            ShowHTML "          <td colspan=2><font size=""1"">&nbsp;</font></td>"
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
            ShowHTML "          <td colspan=7 align=""right""><font size=""1""><b>Total da listagem&nbsp;</font></td>"
            ShowHTML "          <td align=""right""><font size=""1""><b>" & FormatNumber(w_total,2) & "&nbsp;</font></td>"
            ShowHTML "          <td colspan=2><font size=""1"">&nbsp;</font></td>"
            ShowHTML "        </tr>"
         End If
      End If
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    ShowHTML "<tr><td align=""center"" colspan=3>"
    If R > "" Then
       MontaBarra w_pagina&par&"&R="&R&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG&"&w_copia="&w_copia, RS.PageCount, P3, P4, RS.RecordCount
    Else
       MontaBarra w_pagina&par&"&R="&w_Pagina&par&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG&"&w_copia="&w_copia, RS.PageCount, P3, P4, RS.RecordCount
    End If
    ShowHTML "</tr>"    
    DesConectaBD     
  ElseIf Instr("CP",O) > 0 Then
    If P1 <> 1 Then 
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ElseIf O = "C" Then ' Se for cópia
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Para selecionar o projeto que deseja copiar, informe nos campos abaixo os critérios de seleção e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    End If
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td align=""center"" valign=""top""><table border=0 width=""90%"" cellspacing=0>"
    AbreForm "Form", w_Pagina & par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    If O = "C" Then ' Se for cópia, cria parâmetro para facilitar a recuperação dos registros
       ShowHTML "<INPUT type=""hidden"" name=""w_copia"" value=""OK"">"
    End If
    If P1 <> 1 or O = "C" Then ' Se não for cadastramento ou se for cópia
       ' Recupera dados da opção Projetos
       ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
       ShowHTML "      <tr>"
       DB_GetLinkData RS, w_cliente, "PJCAD"
       SelecaoProjeto "Pro<u>j</u>eto:", "J", "Selecione o projeto da atividade na relação.", p_projeto, w_usuario, RS("sq_menu"), "p_projeto", "PJLIST", null
       DesconectaBD
       ShowHTML "      </tr>"
       If RS_menu("solicita_cc") = "S" Then
          ShowHTML "      <tr>"
          SelecaoCC "C<u>l</u>assificação:", "L", "Selecione um dos itens relacionados.", p_sqcc, null, "p_sqcc", "SIWSOLIC"
          ShowHTML "      </tr>"
       End If
       ShowHTML "          </table>"
       
       ShowHTML "      <tr valign=""top"">"
       ShowHTML "          <td valign=""top""><font size=""1""><b>Número da <U>d</U>emanda:<br><INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_chave"" size=""18"" maxlength=""18"" value=""" & p_chave & """></td>"
       ShowHTML "          <td valign=""top""><font size=""1""><b>Dias para a data limi<U>t</U>e:<br><INPUT ACCESSKEY=""T"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_prazo"" size=""2"" maxlength=""2"" value=""" & p_prazo & """></td>"
       ShowHTML "      <tr valign=""top"">"
       SelecaoPessoa "Respo<u>n</u>sável:", "N", "Selecione o responsável pelo projeto na relação.", p_solicitante, null, "p_solicitante", "USUARIOS"
       SelecaoUnidade "<U>S</U>etor responsável:", "S", null, p_unidade, null, "p_unidade", null, null
       ShowHTML "      <tr valign=""top"">"
       SelecaoPessoa "Responsável atua<u>l</u>:", "L", "Selecione o responsável atual pelo projeto na relação.", p_usu_resp, null, "p_usu_resp", "USUARIOS"
       SelecaoUnidade "<U>S</U>etor atual:", "S", "Selecione a unidade onde o projeto se encontra na relação.", p_uorg_resp, null, "p_uorg_resp", null, null
       ShowHTML "      <tr>"
       SelecaoPais "<u>P</u>aís:", "P", null, p_pais, null, "p_pais", null, "onChange=""document.Form.action='" & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_regiao'; document.Form.submit();"""
       SelecaoRegiao "<u>R</u>egião:", "R", null, p_regiao, p_pais, "p_regiao", null, "onChange=""document.Form.action='" & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_uf'; document.Form.submit();"""
       ShowHTML "      <tr>"
       SelecaoEstado "E<u>s</u>tado:", "S", null, p_uf, p_pais, "N", "p_uf", null, "onChange=""document.Form.action='" & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_cidade'; document.Form.submit();"""
       SelecaoCidade "<u>C</u>idade:", "C", null, p_cidade, p_pais, p_uf, "p_cidade", null, null
       ShowHTML "      <tr>"
       SelecaoPrioridade "<u>P</u>rioridade:", "P", "Informe a prioridade deste projeto.", p_prioridade, null, "p_prioridade", null, null
       ShowHTML "          <td valign=""top""><font size=""1""><b>Propo<U>n</U>ente externo:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_proponente"" size=""25"" maxlength=""90"" value=""" & p_proponente & """></td>"
       ShowHTML "      <tr>"
       ShowHTML "          <td valign=""top""><font size=""1""><b>A<U>s</U>sunto:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_assunto"" size=""25"" maxlength=""90"" value=""" & p_assunto & """></td>"
       ShowHTML "          <td valign=""top"" colspan=2><font size=""1""><b>Pala<U>v</U>ras-chave:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_palavra"" size=""25"" maxlength=""90"" value=""" & p_palavra & """></td>"
       ShowHTML "      <tr>"
       ShowHTML "          <td valign=""top""><font size=""1""><b>Data de re<u>c</u>ebimento entre:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""p_ini_i"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_ini_i & """ onKeyDown=""FormataData(this,event);""> e <input " & w_Disabled & " accesskey=""C"" type=""text"" name=""p_ini_f"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_ini_f & """ onKeyDown=""FormataData(this,event);""></td>"
       ShowHTML "          <td valign=""top""><font size=""1""><b>Limi<u>t</u>e para conclusão entre:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""p_fim_i"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_fim_i & """ onKeyDown=""FormataData(this,event);""> e <input " & w_Disabled & " accesskey=""T"" type=""text"" name=""p_fim_f"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_fim_f & """ onKeyDown=""FormataData(this,event);""></td>"
       If O <> "C" Then ' Se não for cópia
          ShowHTML "      <tr>"
          ShowHTML "          <td valign=""top""><font size=""1""><b>Exibe somente projetos em atraso?</b><br>"
          If p_atraso = "S" Then
             ShowHTML "              <input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_atraso"" value=""S"" checked> Sim <br><input " & w_Disabled & " class=""STR"" class=""STR"" type=""radio"" name=""p_atraso"" value=""N""> Não"
          Else
             ShowHTML "              <input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_atraso"" value=""S""> Sim <br><input " & w_Disabled & " class=""STR"" class=""STR"" type=""radio"" name=""p_atraso"" value=""N"" checked> Não"
          End If
          SelecaoFaseCheck "Recuperar fases:", "S", null, p_fase, P2, "p_fase", null, null
       End If
    End If
    ShowHTML "      <tr>"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY=""O"" " & w_Disabled & " class=""STS"" name=""p_ordena"" size=""1"">"
    If p_Ordena="ASSUNTO" Then
       ShowHTML "          <option value=""assunto"" SELECTED>Assunto<option value=""inicio"">Data de recebimento<option value="""">Data limite para conclusão<option value=""nm_tramite"">Fase atual<option value=""prioridade"">Prioridade<option value=""proponente"">Proponente externo"
    ElseIf p_Ordena="INICIO" Then
       ShowHTML "          <option value=""assunto"">Assunto<option value=""inicio"" SELECTED>Data de recebimento<option value="""">Data limite para conclusão<option value=""nm_tramite"">Fase atual<option value=""prioridade"">Prioridade<option value=""proponente"">Proponente externo"
    ElseIf p_Ordena="NM_TRAMITE" Then
       ShowHTML "          <option value=""assunto"">Assunto<option value=""inicio"">Data de recebimento<option value="""">Data limite para conclusão<option value=""nm_tramite"" SELECTED>Fase atual<option value=""prioridade"">Prioridade<option value=""proponente"">Proponente externo"
    ElseIf p_Ordena="PRIORIDADE" Then
       ShowHTML "          <option value=""assunto"">Assunto<option value=""inicio"">Data de recebimento<option value="""">Data limite para conclusão<option value=""nm_tramite"">Fase atual<option value=""prioridade"" SELECTED>Prioridade<option value=""proponente"">Proponente externo"
    ElseIf p_Ordena="PROPONENTE" Then
       ShowHTML "          <option value=""assunto"">Assunto<option value=""inicio"">Data de recebimento<option value="""">Data limite para conclusão<option value=""nm_tramite"">Fase atual<option value=""prioridade"">Prioridade<option value=""proponente"" SELECTED>Proponente externo"
    Else
       ShowHTML "          <option value=""assunto"">Assunto<option value=""inicio"">Data de recebimento<option value="""" SELECTED>Data limite para conclusão<option value=""nm_tramite"">Fase atual<option value=""prioridade"">Prioridade<option value=""proponente"">Proponente externo"
    End If
    ShowHTML "          </select></td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""STI"" type=""text"" name=""P4"" size=""4"" maxlength=""4"" value=""" & P4 & """></td></tr>"
    ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""2"">"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
    If O = "C" Then ' Se for cópia
       ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"" name=""Botao"" value=""Abandonar cópia"">"
    Else
       ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"" name=""Botao"" value=""Remover filtro"">"
    End If
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
REM Rotina dos dados gerais
REM -------------------------------------------------------------------------
Sub Geral
  Dim w_sq_unidade_resp, w_titulo, w_prioridade, w_aviso, w_dias
  Dim w_inicio_real, w_fim_real, w_concluida, w_proponente
  Dim w_data_conclusao, w_nota_conclusao, w_custo_real, w_sqcc
  Dim w_acordo, w_vincula_contrato, w_vincula_viagem
  
  Dim w_chave, w_chave_pai, w_chave_aux, w_sq_menu, w_sq_unidade
  Dim w_sq_tramite, w_solicitante, w_cadastrador, w_executor, w_descricao
  Dim w_justificativa, w_inicio, w_fim, w_inclusao, w_ultima_alteracao
  Dim w_conclusao, w_valor, w_opiniao, w_data_hora, w_pais, w_uf, w_cidade, w_palavra_chave
  
  Dim w_troca, i, w_erro, w_como_funciona, w_cor, w_readonly

  w_chave           = Request("w_chave")
  w_readonly        = ""
  w_erro            = ""
  w_troca           = Request("w_troca")

  ' Verifica se o cliente tem o módulo de acordos contratado
  DB_GetSiwCliModLis RS, w_cliente, null
  RS.Filter = "sigla='AC'"
  If Not RS.EOF Then w_acordo = "S" Else w_acordo = "N" End If
  DesconectaBD
  
  ' Verifica se há necessidade de recarregar os dados da tela a partir
  ' da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  If w_troca > "" Then ' Se for recarga da página
     w_proponente          = Request("w_proponente") 
     w_sq_unidade_resp     = Request("w_sq_unidade_resp") 
     w_titulo              = Request("w_titulo") 
     w_prioridade          = Request("w_prioridade") 
     w_aviso               = Request("w_aviso") 
     w_dias                = Request("w_dias") 
     w_inicio_real         = Request("w_inicio_real") 
     w_fim_real            = Request("w_fim_real") 
     w_concluida           = Request("w_concluida") 
     w_data_conclusao      = Request("w_data_conclusao") 
     w_nota_conclusao      = Request("w_nota_conclusao") 
     w_custo_real          = Request("w_custo_real") 
     w_vincula_contrato    = Request("w_vincula_contrato") 
     w_vincula_viagem      = Request("w_vincula_viagem") 
  
     w_chave               = Request("w_chave") 
     w_chave_pai           = Request("w_chave_pai") 
     w_chave_aux           = Request("w_chave_aux") 
     w_sq_menu             = Request("w_sq_menu") 
     w_sq_unidade          = Request("w_sq_unidade") 
     w_sq_tramite          = Request("w_sq_tramite") 
     w_solicitante         = Request("w_solicitante") 
     w_cadastrador         = Request("w_cadastrador") 
     w_executor            = Request("w_executor") 
     w_descricao           = Request("w_descricao") 
     w_justificativa       = Request("w_justificativa") 
     w_inicio              = Request("w_inicio") 
     w_fim                 = Request("w_fim") 
     w_inclusao            = Request("w_inclusao") 
     w_ultima_alteracao    = Request("w_ultima_alteracao") 
     w_conclusao           = Request("w_conclusao") 
     w_valor               = Request("w_valor") 
     w_opiniao             = Request("w_opiniao") 
     w_data_hora           = Request("w_data_hora") 
     w_pais                = Request("w_pais") 
     w_uf                  = Request("w_uf") 
     w_cidade              = Request("w_cidade") 
     w_palavra_chave       = Request("w_palavra_chave") 
     w_sqcc                = Request("w_sqcc") 
  Else
     If InStr("AEV",O) > 0 or w_copia > "" Then
        ' Recupera os dados do projeto
        If w_copia > "" Then
           DB_GetSolicData RS, w_copia, SG
        Else
           DB_GetSolicData RS, w_chave, SG
        End If
        If RS.RecordCount > 0 Then 
           w_proponente          = RS("proponente") 
           w_sq_unidade_resp     = RS("sq_unidade_resp") 
           w_titulo             = RS("titulo") 
           w_prioridade          = RS("prioridade") 
           w_aviso               = RS("aviso_prox_conc") 
           w_dias                = RS("dias_aviso") 
           w_inicio_real         = RS("inicio_real") 
           w_fim_real            = RS("fim_real") 
           w_concluida           = RS("concluida") 
           w_data_conclusao      = RS("data_conclusao") 
           w_nota_conclusao      = RS("nota_conclusao") 
           w_custo_real          = RS("custo_real") 
           w_vincula_contrato    = RS("vincula_contrato") 
           w_vincula_viagem      = RS("vincula_viagem") 
  
           w_chave_pai           = RS("sq_solic_pai") 
           w_chave_aux           = null
           w_sq_menu             = RS("sq_menu") 
           w_sq_unidade          = RS("sq_unidade") 
           w_sq_tramite          = RS("sq_siw_tramite") 
           w_solicitante         = RS("solicitante") 
           w_cadastrador         = RS("cadastrador") 
           w_executor            = RS("executor") 
           w_descricao           = RS("descricao") 
           w_justificativa       = RS("justificativa") 
           w_inicio              = FormataDataEdicao(RS("inicio"))
           w_fim                 = FormataDataEdicao(RS("fim"))
           w_inclusao            = RS("inclusao") 
           w_ultima_alteracao    = RS("ultima_alteracao") 
           w_conclusao           = RS("conclusao") 
           w_valor               = FormatNumber(RS("valor"),2)
           w_opiniao             = RS("opiniao") 
           w_data_hora           = RS("data_hora") 
           w_sqcc                = RS("sq_cc") 
           w_pais                = RS("sq_pais") 
           w_uf                  = RS("co_uf") 
           w_cidade              = RS("sq_cidade_origem") 
           w_palavra_chave       = RS("palavra_chave") 
           DesconectaBD
        End If

     End If
     
  End If  
  Cabecalho
  ShowHTML "<HEAD>"
  ' Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  ' tratando as particularidades de cada serviço
  ScriptOpen "JavaScript"
  CheckBranco
  FormataData
  FormataDataHora
  FormataValor
  ValidateOpen "Validacao"
  If O = "I" or O = "A" Then
     ShowHTML "  if (theForm.Botao.value == ""Troca"") { return true; }"
     Validate "w_titulo", "titulo", "1", 1, 5, 100, "1", "1"
     If RS_menu("solicita_cc") = "S" Then
        Validate "w_sqcc", "Classificação", "SELECT", 1, 1, 18, "", "0123456789"
     End If
     Validate "w_solicitante", "Solicitante", "HIDDEN", 1, 1, 18, "", "0123456789"
     Validate "w_sq_unidade_resp", "Setor responsável", "HIDDEN", 1, 1, 18, "", "0123456789"
     Validate "w_prioridade", "Prioridade", "SELECT", 1, 1, 1, "", "0123456789"
     Select Case RS_menu("data_hora")
        Case 1
           Validate "w_fim", "Limite para conclusão", "DATA", 1, 10, 10, "", "0123456789/"
        Case 2
           Validate "w_fim", "Limite para conclusão", "DATAHORA", 1, 17, 17, "", "0123456789/"
        Case 3
           Validate "w_inicio", "Data de recebimento", "DATA", 1, 10, 10, "", "0123456789/"
           Validate "w_fim", "Limite para conclusão", "DATA", 1, 10, 10, "", "0123456789/"
           CompData "w_inicio", "Data de recebimento", "<=", "w_fim", "Limite para conclusão"
        Case 4
           Validate "w_inicio", "Data de recebimento", "DATAHORA", 1, 17, 17, "", "0123456789/,: "
           Validate "w_fim", "Limite para conclusão", "DATAHORA", 1, 17, 17, "", "0123456789/,: "
           CompData "w_inicio", "Data de recebimento", "<=", "w_fim", "Limite para conclusão"
     End Select
     Validate "w_valor", "Orçamento disponível", "VALOR", "1", 4, 18, "", "0123456789.,"
     Validate "w_palavra_chave", "Palavras-chave", "", "", 2, 90, "1", "1"
     Validate "w_proponente", "Proponente externo", "", "", 2, 90, "1", "1"
     Validate "w_pais", "País", "SELECT", 1, 1, 18, "", "0123456789"
     Validate "w_uf", "Estado", "SELECT", 1, 1, 3, "1", "1"
     Validate "w_cidade", "Cidade", "SELECT", 1, 1, 18, "", "0123456789"
     If RS_menu("descricao") = "S" Then
        Validate "w_descricao", "Resultados do projeto", "1", 1, 5, 2000, "1", "1"
     End If
     If RS_menu("justificativa") = "S" Then
        Validate "w_justificativa", "Recomendações superiores", "1", 1, 5, 2000, "1", "1"
     End If
     Validate "w_dias", "Dias de alerta", "1", "", 1, 2, "", "0123456789"
     ShowHTML "  if (theForm.w_aviso[0].checked) {"
     ShowHTML "     if (theForm.w_dias.value == '') {"
     ShowHTML "        alert('Informe a partir de quantos dias antes da data limite você deseja ser avisado de sua proximidade!');"
     ShowHTML "        theForm.w_dias.focus();"
     ShowHTML "        return false;"
     ShowHTML "     }"
     ShowHTML "  }"
     ShowHTML "  else {"
     ShowHTML "     theForm.w_dias.value = '';"
     ShowHTML "  }"
  End If
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  If w_troca > "" Then
     BodyOpenClean "onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf Instr("EV",O) > 0 Then
     BodyOpenClean "onLoad='document.focus()';"
  Else
     BodyOpenClean "onLoad='document.Form.w_titulo.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<table align=""center"" border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If Instr("IAEV",O) > 0 Then
    If w_pais = "" Then
       ' Carrega os valores padrão para país, estado e cidade
       DB_GetCustomerData RS, w_cliente
       w_pais   = RS("sq_pais")
       w_uf     = RS("co_uf")
       w_cidade = RS("sq_cidade_padrao")
       DesconectaBD
    End If
  
    If InStr("EV",O) Then
       w_Disabled = " DISABLED "
       If O = "V" Then
          w_Erro = Validacao(w_sq_solicitacao, sg)
       End If
    End If

    AbreForm "Form", w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
    ShowHTML MontaFiltro("POST")
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_copia"" value=""" & w_copia &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_data_hora"" value=""" & RS_menu("data_hora") &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & RS_menu("sq_menu") &""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td valign=""top"" align=""center"" bgcolor=""#D0D0D0""><font size=""2""><b>Identificação</td></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td><font size=1>Os dados deste bloco serão utilizados para identificação do projeto, bem como para o controle de sua execução.</font></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>T</u>ítulo:</b><br><INPUT ACCESSKEY=""T"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_titulo"" size=""90"" maxlength=""100"" value=""" & w_titulo & """ title=""Informe um título para o projeto.""></td>"
    If RS_menu("solicita_cc") = "S" Then
       ShowHTML "          <tr>"
       SelecaoCC "C<u>l</u>assificação:", "L", "Selecione um dos itens relacionados.", w_sqcc, null, "w_sqcc", "SIWSOLIC"
       ShowHTML "          </tr>"
    End If
    ShowHTML "      <tr><td><table border=0 width=""100%"" cellspacing=0>"
    SelecaoPessoa "Respo<u>n</u>sável:", "N", "Selecione o responsável pelo projeto na relação.", w_solicitante, null, "w_solicitante", "USUARIOS"
    SelecaoUnidade "<U>S</U>etor responsável:", "S", "Selecione o setor responsável pela execução do projeto", w_sq_unidade_resp, null, "w_sq_unidade_resp", null, null
    SelecaoPrioridade "<u>P</u>rioridade:", "P", "Informe a prioridade deste projeto.", w_prioridade, null, "w_prioridade", null, null
    ShowHTML "          <tr valign=""top"">"
    Select Case RS_menu("data_hora")
       Case 1
          ShowHTML "              <td valign=""top""><font size=""1""><b>Limi<u>t</u>e para conclusão:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_fim"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_fim & """ onKeyDown=""FormataData(this,event);"" title=""Data limite para que a execução do projeto esteja concluído.""></td>"
       Case 2
          ShowHTML "              <td valign=""top""><font size=""1""><b>Limi<u>t</u>e para conclusão:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_fim"" class=""STI"" SIZE=""17"" MAXLENGTH=""17"" VALUE=""" & w_fim & """ onKeyDown=""FormataDataHora(this,event);"" title=""Data/hora limite para que a execução do projeto esteja concluído.""></td>"
       Case 3
          ShowHTML "              <td valign=""top""><font size=""1""><b>Data de re<u>c</u>ebimento:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_inicio"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & Nvl(w_inicio,FormataDataEdicao(Date())) & """ onKeyDown=""FormataData(this,event);"" title=""Data de recebimento da solicitação.""></td>"
          ShowHTML "              <td valign=""top""><font size=""1""><b>Limi<u>t</u>e para conclusão:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_fim"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_fim & """ onKeyDown=""FormataData(this,event);"" title=""Data limite para que a execução do projeto esteja concluído.""></td>"
       Case 4
          ShowHTML "              <td valign=""top""><font size=""1""><b>Data de re<u>c</u>ebimento:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_inicio"" class=""STI"" SIZE=""17"" MAXLENGTH=""17"" VALUE=""" & w_inicio & """ onKeyDown=""FormataDataHora(this,event);"" title=""Data/hora de recebimento da solicitação.""></td>"
          ShowHTML "              <td valign=""top""><font size=""1""><b>Limi<u>t</u>e para conclusão:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_fim"" class=""STI"" SIZE=""17"" MAXLENGTH=""17"" VALUE=""" & w_fim & """ onKeyDown=""FormataDataHora(this,event);"" title=""Data/hora limite para que a execução do projeto esteja concluído.""></td>"
    End Select
    ShowHTML "              <td><font size=""1""><b>O<u>r</u>çamento disponível:</b><br><input " & w_Disabled & " accesskey=""O"" type=""text"" name=""w_valor"" class=""STI"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_valor & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o orçamento disponível para execução do projeto, ou zero se não for o caso.""></td>"
    ShowHTML "          </table>"
    ShowHTML "      <tr><td><font size=""1""><b>Pa<u>l</u>avras-chave:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_palavra_chave"" size=""90"" maxlength=""90"" value=""" & w_palavra_chave & """ title=""Se desejar, informe palavras-chave adicionais aos campos informados e que permitam a identificação deste projeto.""></td>"
    ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Identificação do proponente</td></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td><font size=1>Os dados deste bloco identificam o proponente externo e sua localização, sendo utilizados para consultas gerenciais por distribuição geográfica.</font></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td><font size=""1""><b>Nome do proponent<u>e</u> externo:<br><INPUT ACCESSKEY=""E"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_proponente"" size=""90"" maxlength=""90"" value=""" & w_proponente & """ title=""Proponente externo do projeto. Preencha apenas se houver.""></td>"
    ShowHTML "      <tr><td><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "      <tr valign=""top"">"
    SelecaoPais "<u>P</u>aís:", "P", null, w_pais, null, "w_pais", null, "onChange=""document.Form.action='" & w_pagina & par & "'; document.Form.w_troca.value='w_uf'; document.Form.submit();"""
    SelecaoEstado "E<u>s</u>tado:", "S", null, w_uf, w_pais, "N", "w_uf", null, "onChange=""document.Form.action='" & w_pagina & par & "'; document.Form.w_troca.value='w_cidade'; document.Form.submit();"""
    SelecaoCidade "<u>C</u>idade:", "C", null, w_cidade, w_pais, w_uf, "w_cidade", null, null
    ShowHTML "          </table>"
    If RS_menu("descricao") = "S" or RS_menu("justificativa") = "S" or w_acordo = "S" Then
       ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td valign=""top"" align=""center"" bgcolor=""#D0D0D0""><font size=""2""><b>Informações adicionais</td></td></tr>"
       ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td><font size=1>Os dados deste bloco visam orientar os executores do projeto.</font></td></tr>"
       ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       If RS_menu("descricao") = "S" Then
          ShowHTML "      <tr><td><font size=""1""><b>Res<u>u</u>ltados do projeto:</b><br><textarea " & w_Disabled & " accesskey=""U"" name=""w_descricao"" class=""STI"" ROWS=5 cols=75 title=""Descreva os resultados esperados após a execução do projeto."">" & w_descricao & "</TEXTAREA></td>"
       End If
       If RS_menu("justificativa") = "S" Then
          ShowHTML "      <tr><td><font size=""1""><b><u>R</u>ecomendações superiores:</b><br><textarea " & w_Disabled & " accesskey=""R"" name=""w_justificativa"" class=""STI"" ROWS=5 cols=75 title=""Relacione as recomendações a serem seguidas na execução do projeto."">" & w_justificativa & "</TEXTAREA></td>"
       End If
       If w_acordo = "S" Then
          ShowHTML "      <tr><td><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
          MontaRadioNS "<b>Permite a vinculação de acordos?</b>", Nvl(w_vincula_contrato,"N"), "w_vincula_contrato"
          MontaRadioNS "<b>Permite a vinculação de viagens?</b>", Nvl(w_vincula_viagem,"N"), "w_vincula_viagem"
          ShowHTML "          </table>"
       End If
    End If
    ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td valign=""top"" align=""center"" bgcolor=""#D0D0D0""><font size=""2""><b>Alerta de atraso</td></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td><font size=1>Os dados abaixo indicam como deve ser tratada a proximidade da data limite para conclusão do projeto.</font></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td><table border=""0"" width=""100%"">"
    ShowHTML "          <tr valign=""top"">"
    MontaRadioNS "<b>Emite alerta?</b>", w_aviso, "w_aviso"
    ShowHTML "              <td><font size=""1""><b>Quantos <U>d</U>ias antes da data limite?<br><INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_dias"" size=""2"" maxlength=""2"" value=""" & w_dias & """ title=""Número de dias para emissão do alerta de proximidade da data limite para conclusão do projeto.""></td>"
    ShowHTML "          </table>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></TD></TR>"

    ' Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
    ShowHTML "      <tr><td align=""center"">"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Gravar"">"
    If O = "I" Then
       DB_GetMenuData RS, w_menu
       ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & R & "&w_copia=" & w_copia & "&O=L&SG=" & RS("sigla") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & MontaFiltro("GET") & "';"" name=""Botao"" value=""Cancelar"">"
    End If
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    'ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_acordo              = Nothing 
  Set w_vincula_contrato    = Nothing 
  Set w_vincula_viagem      = Nothing 
  Set w_proponente          = Nothing 
  Set w_sq_unidade_resp     = Nothing 
  Set w_titulo              = Nothing 
  Set w_prioridade          = Nothing 
  Set w_aviso               = Nothing 
  Set w_dias                = Nothing 
  Set w_inicio_real         = Nothing 
  Set w_fim_real            = Nothing 
  Set w_concluida           = Nothing 
  Set w_data_conclusao      = Nothing 
  Set w_nota_conclusao      = Nothing 
  Set w_custo_real          = Nothing 
  
  Set w_chave               = Nothing 
  Set w_chave_pai           = Nothing 
  Set w_chave_aux           = Nothing 
  Set w_sq_menu             = Nothing 
  Set w_sq_unidade          = Nothing 
  Set w_sq_tramite          = Nothing 
  Set w_solicitante         = Nothing 
  Set w_cadastrador         = Nothing 
  Set w_executor            = Nothing 
  Set w_descricao           = Nothing 
  Set w_justificativa       = Nothing 
  Set w_inicio              = Nothing 
  Set w_fim                 = Nothing 
  Set w_inclusao            = Nothing 
  Set w_ultima_alteracao    = Nothing 
  Set w_conclusao           = Nothing 
  Set w_valor               = Nothing 
  Set w_opiniao             = Nothing 
  Set w_data_hora           = Nothing 
  Set w_sqcc                = Nothing 
  Set w_pais                = Nothing 
  Set w_uf                  = Nothing 
  Set w_cidade              = Nothing 
  Set w_palavra_chave       = Nothing 
  
  Set w_troca               = Nothing 
  Set i                     = Nothing 
  Set w_erro                = Nothing 
  Set w_como_funciona       = Nothing 
  Set w_cor                 = Nothing 

End Sub

REM ------------------------------------------------------------------------- 
REM Rotina de anexos 
REM ------------------------------------------------------------------------- 
Sub Anexos 
  Dim w_chave, w_chave_pai, w_chave_aux, w_nome, w_descricao, w_caminho 
    
  Dim w_troca, i, w_erro 
    
  w_Chave           = Request("w_Chave") 
  w_chave_aux       = Request("w_chave_aux") 
  w_troca           = Request("w_troca") 
    
  If w_troca > "" Then ' Se for recarga da página 
     w_nome                = Request("w_nome") 
     w_descricao           = Request("w_descricao") 
     w_caminho             = Request("w_caminho") 
  ElseIf O = "L" Then 
     ' Recupera todos os registros para a listagem 
     DB_GetSolicAnexo RS, w_chave, null, w_cliente 
     RS.Sort = "nome" 
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then 
     ' Recupera os dados do endereço informado 
     DB_GetSolicAnexo RS, w_chave, w_chave_aux, w_cliente 
     w_nome                 = RS("nome") 
     w_descricao            = RS("descricao") 
     w_caminho              = RS("chave_aux") 
     DesconectaBD 
  End If 
    
  Cabecalho 
  ShowHTML "<HEAD>" 
  If InStr("IAEP",O) > 0 Then 
     ScriptOpen "JavaScript" 
     ValidateOpen "Validacao" 
     If InStr("IA",O) > 0 Then 
        Validate "w_nome", "Título", "1", "1", "1", "255", "1", "1" 
        Validate "w_descricao", "Descrição", "1", "1", "1", "1000", "1", "1" 
        If O = "I" Then 
           Validate "w_caminho", "Arquivo", "", "1", "5", "255", "1", "1" 
        End If 
     End If 
     ShowHTML "  theForm.Botao[0].disabled=true;" 
     ShowHTML "  theForm.Botao[1].disabled=true;" 
     ValidateClose 
     ScriptClose 
  End If 
  ShowHTML "</HEAD>" 
  If w_troca > "" Then 
     BodyOpenClean "onLoad='document.Form." & w_troca & ".focus()';" 
  ElseIf O = "I" Then 
     BodyOpenClean "onLoad='document.Form.w_nome.focus()';" 
  ElseIf O = "A" Then 
     BodyOpenClean "onLoad='document.Form.w_descricao.focus()';" 
  Else 
     BodyOpenClean "onLoad='document.focus()';" 
  End If 
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>" 
  ShowHTML "<HR>" 
  ShowHTML "<div align=center><center>" 
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">" 
  If O = "L" Then 
    AbreSessao 
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem 
    ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;" 
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount 
    ShowHTML "<tr><td align=""center"" colspan=3>" 
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>" 
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">" 
    ShowHTML "          <td><font size=""1""><b>Projeto</font></td>" 
    ShowHTML "          <td><font size=""1""><b>Descrição</font></td>" 
    ShowHTML "          <td><font size=""1""><b>Tipo</font></td>" 
    ShowHTML "          <td><font size=""1""><b>KB</font></td>" 
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>" 
    ShowHTML "        </tr>" 
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem 
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>" 
    Else 
      ' Lista os registros selecionados para listagem 
      While Not RS.EOF 
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If 
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">" 
        ShowHTML "        <td><font size=""1"">" & LinkArquivo("HL", w_cliente, RS("chave_aux"), "_blank", "Clique para exibir o arquivo em outra janela.", RS("nome"), null) & "</td>" 
        ShowHTML "        <td><font size=""1"">" & Nvl(RS("descricao"),"---") & "</td>" 
        ShowHTML "        <td><font size=""1"">" & RS("tipo") & "</td>" 
        ShowHTML "        <td align=""right""><font size=""1"">" & Round(cDbl(RS("tamanho"))/1024,1) & "&nbsp;</td>" 
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">" 
        ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & w_chave & "&w_chave_aux=" & Rs("chave_aux") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp" 
        ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_chave=" & w_chave & "&w_chave_aux=" & Rs("chave_aux") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp" 
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
    ShowHTML "<FORM action=""" & w_pagina & "Grava&SG="&SG&"&O="&O&""" name=""Form"" onSubmit=""return(Validacao(this));"" enctype=""multipart/form-data"" method=""POST"">" 
    ShowHTML "<INPUT type=""hidden"" name=""P1"" value=""" & P1 & """>" 
    ShowHTML "<INPUT type=""hidden"" name=""P2"" value=""" & P2 & """>" 
    ShowHTML "<INPUT type=""hidden"" name=""P3"" value=""" & P3 & """>" 
    ShowHTML "<INPUT type=""hidden"" name=""P4"" value=""" & P4 & """>" 
    ShowHTML "<INPUT type=""hidden"" name=""TP"" value=""" & TP & """>" 
    ShowHTML "<INPUT type=""hidden"" name=""R"" value=""" & R & """>" 
    
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>" 
    ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_chave_aux & """>" 
    ShowHTML "<INPUT type=""hidden"" name=""w_atual"" value=""" & w_caminho & """>" 
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">" 
    
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">" 
    ShowHTML "    <table width=""97%"" border=""0"">" 
    
    If O = "I" or O = "A" Then 
       DB_GetCustomerData RS, w_cliente 
       ShowHTML "      <tr><td align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""2""><b><font color=""#BC3131"">ATENÇÃO</font>: o tamanho máximo aceito para o arquivo é de " & cDbl(RS("upload_maximo"))/1024 & " KBytes</b>.</font></td>" 
       ShowHTML "<INPUT type=""hidden"" name=""w_upload_maximo"" value=""" & RS("upload_maximo") & """>" 
    End If 
    
    ShowHTML "      <tr><td><font size=""1""><b><u>T</u>ítulo:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_nome"" class=""STI"" SIZE=""75"" MAXLENGTH=""255"" VALUE=""" & w_nome & """ title=""'OBRIGATÓRIO. Informe um título para o arquivo.""></td>" 
    ShowHTML "      <tr><td><font size=""1""><b><u>D</u>escrição:</b><br><textarea " & w_Disabled & " accesskey=""D"" name=""w_descricao"" class=""STI"" ROWS=5 cols=65 title=""'OBRIGATÓRIO. Descreva a finalidade do arquivo."">" & w_descricao & "</TEXTAREA></td>" 
    ShowHTML "      <tr><td><font size=""1""><b>A<u>r</u>quivo:</b><br><input " & w_Disabled & " accesskey=""R"" type=""file"" name=""w_caminho"" class=""STI"" SIZE=""80"" MAXLENGTH=""100"" VALUE="""" title=""'OBRIGATÓRIO. Clique no botão ao lado para localizar o arquivo. Ele será transferido automaticamente para o servidor."">" 
    If w_caminho > "" Then 
       ShowHTML "              <b>" & LinkArquivo("SS", w_cliente, w_caminho, "_blank", "Clique para exibir o arquivo atual.", "Exibir", null) & "</b>" 
    End If 
    ShowHTML "      <tr><td align=""center""><hr>" 
    If O = "E" Then 
       ShowHTML "   <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"" onClick=""return confirm('Confirma a exclusão do registro?');"">" 
    Else 
       If O = "I" Then 
          ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Incluir"">" 
       Else 
          ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Atualizar"">" 
       End If 
    End If 
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=L';"" name=""Botao"" value=""Cancelar"">" 
    ShowHTML "          </td>" 
    ShowHTML "      </tr>" 
    ShowHTML "    </table>" 
    ShowHTML "    </TD>" 
    ShowHTML "</tr>" 
    ShowHTML "</FORM>" 
  Else 
    ScriptOpen "JavaScript" 
    ShowHTML " alert('Opção não disponível');" 
    'ShowHTML " history.back(1);" 
    ScriptClose 
  End If 
  ShowHTML "</table>" 
  ShowHTML "</center>" 
  Rodape 
    
  Set w_chave           = Nothing 
  Set w_chave_pai       = Nothing 
  Set w_chave_aux       = Nothing 
  Set w_nome            = Nothing 
  Set w_descricao       = Nothing 
  Set w_caminho         = Nothing 
    
  Set w_troca           = Nothing 
  Set i                 = Nothing 
  Set w_erro            = Nothing 
End Sub 

REM =========================================================================
REM Rotina de etapas do projeto
REM -------------------------------------------------------------------------
Sub Etapas
  Dim w_chave, w_chave_pai, w_chave_aux, w_titulo, w_ordem, w_descricao
  Dim w_inicio, w_fim, w_inicio_real, w_fim_real, w_perc_conclusao, w_orcamento
  Dim w_sq_pessoa, w_sq_unidade, w_vincula_atividade
  
  Dim w_troca, i, w_texto
  
  w_Chave           = Request("w_Chave")
  w_Chave_pai       = Request("w_Chave_pai")
  w_chave_aux       = Request("w_chave_aux")
  w_troca           = Request("w_troca")
  
  If w_troca > "" Then ' Se for recarga da página
     w_ordem                = Request("w_ordem")
     w_titulo               = Request("w_titulo")
     w_descricao            = Request("w_descricao")    
     w_inicio               = Request("w_inicio")    
     w_fim                  = Request("w_fim")    
     w_inicio_real          = Request("w_inicio_real")    
     w_fim_real             = Request("w_fim_real")    
     w_perc_conclusao       = Request("w_perc_conclusao")    
     w_orcamento            = Request("w_orcamento")    
     w_sq_pessoa            = Request("w_sq_pessoa")    
     w_sq_unidade           = Request("w_sq_unidade")    
     w_vincula_atividade    = Request("w_vincula_atividade")    
  ElseIf O = "L" Then
     ' Recupera todos os registros para a listagem
     DB_GetSolicEtapa RS, w_chave, null, "LISTA"
     RS.Sort = "ordem"
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
     ' Recupera os dados do endereço informado
     DB_GetSolicEtapa RS, w_chave, w_chave_aux, "REGISTRO"
     w_chave_pai            = RS("sq_etapa_pai")
     w_titulo               = RS("titulo")
     w_ordem                = RS("ordem")
     w_descricao            = RS("descricao")    
     w_inicio               = RS("inicio_previsto")
     w_fim                  = RS("fim_previsto")
     w_inicio_real          = RS("inicio_real")
     w_fim_real             = RS("fim_real")
     w_perc_conclusao       = RS("perc_conclusao")
     w_orcamento            = RS("orcamento")
     w_sq_pessoa            = RS("sq_pessoa")
     w_sq_unidade           = RS("sq_unidade")
     w_vincula_atividade    = RS("vincula_atividade")    
     DesconectaBD
  ElseIf Nvl(w_sq_pessoa,"") = "" Then
     ' Se a etapa não tiver responsável atribuído, recupera o responsável pelo projeto
     DB_GetSolicData RS, w_chave, "PJGERAL"
     
     w_sq_pessoa            = RS("solicitante")
     w_sq_unidade           = RS("sq_unidade_resp")
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     modulo
     checkbranco
     formatadata
     FormataValor
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_ordem", "Tipo de visão", "SELECT", "1", "1", "10", "", "1"
        Validate "w_titulo", "Título", "", "1", "2", "100", "1", "1"
        Validate "w_descricao", "Descricao", "", "1", "2", "2000", "1", "1"
        Validate "w_ordem", "Ordem", "1", "1", "1", "3", "", "0123456789"
        Validate "w_chave_pai", "Subordinação", "SELECT", "", "1", "10", "", "1"
        Validate "w_inicio", "Início previsto", "DATA", "1", "10", "10", "", "0123456789/"
        Validate "w_fim", "Fim previsto", "DATA", "1", "10", "10", "", "0123456789/"
        CompData "w_inicio", "Início previsto", "<=", "w_fim", "Fim previsto"
        Validate "w_orcamento", "Orçamento disponível", "VALOR", "1", "4", "18", "", "0123456789.,"
        Validate "w_perc_conclusao", "Percentual de conclusão", "", "1", "1", "3", "", "0123456789"
        Validate "w_sq_pessoa", "Responsável", "HIDDEN", "", "1", "10", "", "1"
        Validate "w_sq_unidade", "Setor responsável", "HIDDEN", "", "1", "10", "", "1"
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  If w_troca > "" Then
     BodyOpenClean "onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf O = "L" or O = "E" Then
     BodyOpenClean "onLoad='document.focus()';"
  Else
     BodyOpen "onLoad='document.Form.w_titulo.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    AbreSessao
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td rowspan=2><font size=""1""><b>Etapa</font></td>"
    ShowHTML "          <td rowspan=2><font size=""1""><b>Título</font></td>"
    ShowHTML "          <td rowspan=2><font size=""1""><b>Responsável</font></td>"
    ShowHTML "          <td rowspan=2><font size=""1""><b>Setor</font></td>"
    ShowHTML "          <td colspan=2><font size=""1""><b>Execução</font></td>"
    ShowHTML "          <td rowspan=2><font size=""1""><b>Conc.</font></td>"
    ShowHTML "          <td rowspan=2><font size=""1""><b>Ativ.</font></td>"
    ShowHTML "          <td rowspan=2><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>De</font></td>"
    ShowHTML "          <td><font size=""1""><b>Até</font></td>"
    ShowHTML "        </tr>"
    ' Recupera as etapas principais
    DB_GetSolicEtapa RS, w_chave, null, "LSTNULL"
    RS.Sort = "ordem"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=9 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      While Not RS.EOF
        ShowHtml EtapaLinha(w_chave, Rs("sq_projeto_etapa"), Rs("titulo"), Rs("nm_resp"), Rs("sg_setor"), Rs("inicio_previsto"), Rs("fim_previsto"), Rs("perc_conclusao"), RS("qt_ativ"), "<b>", "S", "PROJETO")
        
        ' Recupera as etapas vinculadas ao nível acima
        DB_GetSolicEtapa RS1, w_chave, RS("sq_projeto_etapa"), "LSTNIVEL"
        RS1.Sort = "ordem"
        While Not RS1.EOF
          ShowHTML EtapaLinha(w_chave, RS1("sq_projeto_etapa"), RS1("titulo"), RS1("nm_resp"), RS1("sg_setor"), RS1("inicio_previsto"), RS1("fim_previsto"), RS1("perc_conclusao"), RS1("qt_ativ"), null, "S", "PROJETO")

          ' Recupera as etapas vinculadas ao nível acima
          DB_GetSolicEtapa RS2, w_chave, RS1("sq_projeto_etapa"), "LSTNIVEL"
          RS2.Sort = "ordem"
          While Not RS2.EOF
            ShowHTML EtapaLinha(w_chave, RS2("sq_projeto_etapa"), RS2("titulo"), RS2("nm_resp"), RS2("sg_setor"), RS2("inicio_previsto"), RS2("fim_previsto"), RS2("perc_conclusao"), RS2("qt_ativ"), null, "S", "PROJETO")

            ' Recupera as etapas vinculadas ao nível acima
            DB_GetSolicEtapa RS3, w_chave, RS2("sq_projeto_etapa"), "LSTNIVEL"
            RS3.Sort = "ordem"
            While Not RS3.EOF
              ShowHTML EtapaLinha(w_chave, RS3("sq_projeto_etapa"), RS3("titulo"), RS3("nm_resp"), RS3("sg_setor"), RS3("inicio_previsto"), RS3("fim_previsto"), RS3("perc_conclusao"), RS3("qt_ativ"), null, "S", "PROJETO")

              ' Recupera as etapas vinculadas ao nível acima
              DB_GetSolicEtapa RS4, w_chave, RS3("sq_projeto_etapa"), "LSTNIVEL"
              RS4.Sort = "ordem"
              While Not RS4.EOF
                ShowHTML EtapaLinha(w_chave, RS4("sq_projeto_etapa"), RS4("titulo"), RS4("nm_resp"), RS4("sg_setor"), RS4("inicio_previsto"), RS4("fim_previsto"), RS4("perc_conclusao"), RS("qt_ativ"), null, "S", "PROJETO")
                RS4.MoveNext
              wend

              RS3.MoveNext
            wend

            RS2.MoveNext
          wend

          RS1.MoveNext
        wend
        
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
    AbreForm "Form", w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_chave_aux & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_programada"" value=""N"">"
    ShowHTML "<INPUT type=""hidden"" name=""w_cumulativa"" value=""N"">"
    ShowHTML "<INPUT type=""hidden"" name=""w_quantidade"" value=""0"">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr><td><font size=""1""><b>Tít<u>u</u>lo:</b><br><input " & w_Disabled & " accesskey=""U"" type=""text"" name=""w_titulo"" class=""STI"" SIZE=""90"" MAXLENGTH=""90"" VALUE=""" & w_titulo & """ title=""Informe um título para a etapa.""></td>"
    ShowHTML "      <tr><td><font size=""1""><b><u>D</u>escrição:</b><br><textarea " & w_Disabled & " accesskey=""D"" name=""w_descricao"" class=""STI"" ROWS=5 cols=75 title=""Descreva os objetivos da etapa e os resultados esperados após sua execução."">" & w_descricao & "</TEXTAREA></td>"
    ShowHTML "      <tr>"
    SelecaoEtapa "Eta<u>p</u>a superior:", "P", "Se necessário, indique a etapa superior a esta.", w_chave_pai, w_chave, w_chave_aux, "w_chave_pai", "Pesquisa", null
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
    ' Recupera o número de ordem das outras opções irmãs à selecionada
    DB_GetEtapaOrder RS, w_chave, w_chave_pai
    If Not RS.EOF Then
       w_texto = "<b>Nºs de ordem em uso para esta subordinação:</b>:<br>" & _
                 "<table border=1 width=100% cellpadding=0 cellspacing=0>" & _
                 "<tr><td align=center><b><font size=1>Ordem" & _
                 "    <td><b><font size=1>Descrição"
       While Not RS.EOF
          w_texto = w_texto & "<tr><td valign=top align=center><font size=1>" & RS("ordem") & "<td valign=top><font size=1>" & RS("titulo")
          RS.MoveNext
       Wend
       w_texto = w_texto & "</table>"
    Else
       w_texto = "Não há outros números de ordem subordinados a esta etapa."
    End If
    ShowHTML "              <td align=""left""><font size=""1""><b><u>O</u>rdem:<br><INPUT ACCESSKEY=""O"" TYPE=""TEXT"" CLASS=""STI"" NAME=""w_ordem"" SIZE=3 MAXLENGTH=3 VALUE=""" & w_ordem & """ " & w_Disabled & " ONMOUSEOVER=""popup1('" & Replace(w_texto,CHR(13)&CHR(10),"<BR>") & "','white')""; ONBLUR=""kill()""></td>"
    ShowHTML "              <td><font size=""1""><b>Previsão iní<u>c</u>io:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_inicio"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & FormataDataEdicao(Nvl(w_inicio,Date())) & """ onKeyDown=""FormataData(this,event);"" title=""Data prevista para início da etapa.""></td>"
    ShowHTML "              <td><font size=""1""><b>Previsão <u>t</u>érmino:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_fim"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & FormataDataEdicao(w_fim) & """ onKeyDown=""FormataData(this,event);"" title=""Data prevista para término da etapa.""></td>"
    ShowHTML "          <tr valign=""top"">"
    ShowHTML "              <td><font size=""1""><b>Orça<u>m</u>ento previsto:</b><br><input " & w_Disabled & " accesskey=""M"" type=""text"" name=""w_orcamento"" class=""STI"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & FormatNumber(w_orcamento,2) & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Orçamento previsto para execução desta etapa.""></td>"
    ShowHTML "              <td align=""left""><font size=""1""><b>Percentual de co<u>n</u>clusão:<br><INPUT ACCESSKEY=""N"" TYPE=""TEXT"" CLASS=""STI"" NAME=""w_perc_conclusao"" SIZE=3 MAXLENGTH=3 VALUE=""" & nvl(w_perc_conclusao,0) & """ " & w_Disabled & " ONMOUSEOVER=""popup1('Indique o percentual de conclusão já atingido por essa etapa.','white')""; ONBLUR=""kill()""></td>"
    MontaRadioSN "<b>Permite vinculação de atividades?</b>", w_vincula_atividade, "w_vincula_atividade"
    ShowHTML "          </table>"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    SelecaoPessoa "Respo<u>n</u>sável pela etapa:", "N", "Selecione o responsável pela etapa na relação.", w_sq_pessoa, null, "w_sq_pessoa", "USUARIOS"
    SelecaoUnidade "<U>S</U>etor responsável pela etapa:", "S", "Selecione o setor responsável pela execução da etapa", w_sq_unidade, null, "w_sq_unidade", null, null
    ShowHTML "          <tr>"
    ShowHTML "      <tr>"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td align=""center"" colspan=4><hr>"
    If O = "E" Then
       ShowHTML "   <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       If O = "I" Then
          ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Incluir"">"
       Else
          ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Atualizar"">"
       End If
    End If
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=L';"" name=""Botao"" value=""Cancelar"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    'ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_inicio              = Nothing 
  Set w_fim                 = Nothing 
  Set w_perc_conclusao      = Nothing 
  Set w_orcamento           = Nothing
  Set w_sq_pessoa           = Nothing
  Set w_sq_unidade          = Nothing
  Set w_vincula_atividade   = Nothing

  Set w_chave               = Nothing 
  Set w_chave_pai           = Nothing 
  Set w_chave_aux           = Nothing 
  Set w_titulo              = Nothing 
  Set w_ordem               = Nothing 
  Set w_descricao           = Nothing 
  
  Set w_troca               = Nothing 
  Set i                     = Nothing 
  Set w_texto               = Nothing
End Sub
REM =========================================================================
REM Fim da tela de etapas do projeto
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de atualização das etapas do projeto
REM -------------------------------------------------------------------------
Sub AtualizaEtapa
  Dim w_chave, w_chave_pai, w_chave_aux, w_titulo, w_ordem, w_descricao
  Dim w_inicio, w_fim, w_inicio_real, w_fim_real, w_perc_conclusao, w_orcamento
  Dim w_ultima_atualizacao, w_sq_pessoa_atualizacao, w_situacao_atual
  Dim w_sq_pessoa, w_sq_unidade, w_vincula_atividade, w_cabecalho, w_fase, w_p2, w_fases
  Dim RS1, RS2, RS3, RS4
  
  Dim w_troca, i, w_texto
  
  w_Chave           = Request("w_Chave")
  w_Chave_pai       = Request("w_Chave_pai")
  w_chave_aux       = Request("w_chave_aux")
  w_troca           = Request("w_troca")
  
  DB_GetSolicData RS, w_chave, "PJGERAL"
  w_cabecalho = "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font  size=""2""><b>Projeto: " & RS("titulo") & " (" & w_chave & ")</td><tr><td><font size=1><br></td></tr>"
  
  ' Configura uma variável para testar se as etapas podem ser atualizadas.
  ' Projetos concluídos ou cancelados não podem ter permitir a atualização.
  If Nvl(RS("sg_tramite"),"--") = "EE" Then
     w_fase = "S"
  Else
     w_fase = "N"
  End If
  DesconectaBD

  If w_troca > "" Then ' Se for recarga da página
     w_ordem                = Request("w_ordem")
     w_titulo               = Request("w_titulo")
     w_descricao            = Request("w_descricao")    
     w_inicio               = Request("w_inicio")    
     w_fim                  = Request("w_fim")    
     w_inicio_real          = Request("w_inicio_real")    
     w_fim_real             = Request("w_fim_real")    
     w_perc_conclusao       = Request("w_perc_conclusao")    
     w_orcamento            = Request("w_orcamento")    
     w_sq_pessoa            = Request("w_sq_pessoa")    
     w_sq_unidade           = Request("w_sq_unidade")    
     w_vincula_atividade    = Request("w_vincula_atividade")    
  ElseIf O = "L" Then
     ' Recupera todos os registros para a listagem
     DB_GetSolicEtapa RS, w_chave, null, "LISTA"
     RS.Sort = "ordem"

    ' Recupera o código da opção de menu  a ser usada para listar as atividades
     w_p2 = ""
     If Not RS.EOF Then
        While Not RS.EOF
           If cDbl(Nvl(RS("P2"),0)) > cDbl(0) Then
              w_p2 = RS("P2")
              RS.MoveLast
           End If
           RS.MoveNext
        Wend
        RS.MoveFirst
     End If
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
     ' Recupera os dados do endereço informado
     DB_GetSolicEtapa RS, w_chave, w_chave_aux, "REGISTRO"
     w_chave_pai            = RS("sq_etapa_pai")
     w_titulo               = RS("titulo")
     w_ordem                = RS("ordem")
     w_descricao            = RS("descricao")    
     w_inicio               = RS("inicio_previsto")
     w_fim                  = RS("fim_previsto")
     w_inicio_real          = RS("inicio_real")
     w_fim_real             = RS("fim_real")
     w_perc_conclusao       = RS("perc_conclusao")
     w_orcamento            = RS("orcamento")
     w_sq_pessoa            = RS("sq_pessoa")
     w_sq_unidade           = RS("sq_unidade")
     w_vincula_atividade    = RS("vincula_atividade")
     w_ultima_atualizacao   = RS("ultima_atualizacao")
     w_sq_pessoa_atualizacao= RS("sq_pessoa_atualizacao")
     w_situacao_atual       = RS("situacao_atual")
     DesconectaBD
  ElseIf Nvl(w_sq_pessoa,"") = "" Then
     ' Se a etapa não tiver responsável atribuído, recupera o responsável pelo projeto
     DB_GetSolicData RS, w_chave, "PJGERAL"
     
     w_sq_pessoa            = RS("solicitante")
     w_sq_unidade           = RS("sq_unidade_resp")
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>" & conSgSistema & " - Etapas de projeto</TITLE>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     FormataValor
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_perc_conclusao", "Percentual de conclusão", "", "1", "1", "3", "", "0123456789"
        Validate "w_situacao_atual", "Situação atual", "", "", "2", "4000", "1", "1"
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  If w_troca > "" Then
     BodyOpenClean "onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf O = "I" or O = "A" Then
     BodyOpenClean "onLoad='document.Form.w_perc_conclusao.focus()';"
  Else
     BodyOpenClean "onLoad='document.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & Mid(w_TP,1, Instr(w_TP,"-")-1) & "- Etapas" & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  ShowHTML w_cabecalho
  If O = "L" Then
    AbreSessao
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "<tr><td><font size=""2"">"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td rowspan=2><font size=""1""><b>Etapa</font></td>"
    ShowHTML "          <td rowspan=2><font size=""1""><b>Título</font></td>"
    ShowHTML "          <td rowspan=2><font size=""1""><b>Responsável</font></td>"
    ShowHTML "          <td rowspan=2><font size=""1""><b>Setor</font></td>"
    ShowHTML "          <td colspan=2><font size=""1""><b>Execução</font></td>"
    ShowHTML "          <td rowspan=2><font size=""1""><b>Conc.</font></td>"
    ShowHTML "          <td rowspan=2><font size=""1""><b>Ativ.</font></td>"
    ShowHTML "          <td rowspan=2><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>De</font></td>"
    ShowHTML "          <td><font size=""1""><b>Até</font></td>"
    ShowHTML "        </tr>"
    ' Recupera as etapas principais
    DB_GetSolicEtapa RS, w_chave, null, "LSTNULL"
    RS.Sort = "ordem"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=9 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else

      ' Monta função JAVASCRIPT para fazer a chamada para a lista de atividades
      If w_p2 > "" Then
         ShowHTML "<SCRIPT LANGUAGE=""JAVASCRIPT"">"
         ShowHTML "  function lista (projeto, etapa) {"
         ShowHTML "    document.Form.p_projeto.value=projeto;"
         ShowHTML "    document.Form.p_atividade.value=etapa;"
         ShowHTML "    document.Form.p_agrega.value='GRDMETAPA';"
         DB_GetTramiteList RS1, w_P2, null
         RS1.Sort = "ordem"
         ShowHTML "    document.Form.p_fase.value='';"
         w_fases = ""
         While Not RS1.EOF
            If RS1("sigla") <> "CA" Then
               w_fases = w_fases & "," & RS1("sq_siw_tramite")
            End If
            RS1.MoveNext
         Wend
         ShowHTML "    document.Form.p_fase.value='" & Mid(w_fases,2,100) & "';"
         ShowHTML "    document.Form.submit();"
         ShowHTML "  }"
         ShowHTML "</SCRIPT>"
         DB_GetMenuData RS1, w_p2
         AbreForm "Form", RS1("link"), "POST", "return(Validacao(this));", "Atividades",3,w_P2,1,null,w_TP,RS1("sigla"),w_pagina & par,"L"
         ShowHTML MontaFiltro("POST")
         ShowHTML "<input type=""Hidden"" name=""p_projeto"" value="""">"
         ShowHTML "<input type=""Hidden"" name=""p_atividade"" value="""">"
         ShowHTML "<input type=""Hidden"" name=""p_agrega"" value="""">"
         ShowHTML "<input type=""Hidden"" name=""p_fase"" value="""">"
      End If

      While Not RS.EOF
        If cDbl(Nvl(RS("tit_exec"),0))    = cDbl(w_usuario) or _
           cDbl(Nvl(RS("sub_exec"),0))    = cDbl(w_usuario) or _
           cDbl(Nvl(RS("titular"),0))     = cDbl(w_usuario) or _
           cDbl(Nvl(RS("substituto"),0))  = cDbl(w_usuario) or _
           cDbl(Nvl(RS("solicitante"),0)) = cDbl(w_usuario) or _
           cDbl(Nvl(RS("executor"),0))    = cDbl(w_usuario) or _
           cDbl(Nvl(RS("sq_pessoa"),0))   = cDbl(w_usuario) _
        Then
           ShowHtml EtapaLinha(w_chave, Rs("sq_projeto_etapa"), Rs("titulo"), RS("nm_resp"), RS("sg_setor"), Rs("inicio_previsto"), Rs("fim_previsto"), Rs("perc_conclusao"), RS("qt_ativ"), "<b>", w_fase, "ETAPA")
        Else
           ShowHtml EtapaLinha(w_chave, Rs("sq_projeto_etapa"), Rs("titulo"), RS("nm_resp"), RS("sg_setor"), Rs("inicio_previsto"), Rs("fim_previsto"), Rs("perc_conclusao"), RS("qt_ativ"), "<b>", "N", "ETAPA")
        End If
        
        ' Recupera as etapas vinculadas ao nível acima
        DB_GetSolicEtapa RS1, w_chave, RS("sq_projeto_etapa"), "LSTNIVEL"
        RS1.Sort = "ordem"
        While Not RS1.EOF
         If cDbl(Nvl(RS1("tit_exec"),0))    = cDbl(w_usuario) or _
            cDbl(Nvl(RS1("sub_exec"),0))    = cDbl(w_usuario) or _
            cDbl(Nvl(RS1("titular"),0))     = cDbl(w_usuario) or _
            cDbl(Nvl(RS1("substituto"),0))  = cDbl(w_usuario) or _
            cDbl(Nvl(RS1("solicitante"),0)) = cDbl(w_usuario) or _
            cDbl(Nvl(RS1("executor"),0))    = cDbl(w_usuario) or _
            cDbl(Nvl(RS1("sq_pessoa"),0))   = cDbl(w_usuario) _
          Then
             ShowHTML EtapaLinha(w_chave, RS1("sq_projeto_etapa"), RS1("titulo"), RS1("nm_resp"), RS1("sg_setor"), RS1("inicio_previsto"), RS1("fim_previsto"), RS1("perc_conclusao"), RS1("qt_ativ"), null, w_fase, "ETAPA")
          Else
             ShowHTML EtapaLinha(w_chave, RS1("sq_projeto_etapa"), RS1("titulo"), RS1("nm_resp"), RS1("sg_setor"), RS1("inicio_previsto"), RS1("fim_previsto"), RS1("perc_conclusao"), RS1("qt_ativ"), null, "N", "ETAPA")
          End If

          ' Recupera as etapas vinculadas ao nível acima
          DB_GetSolicEtapa RS2, w_chave, RS1("sq_projeto_etapa"), "LSTNIVEL"
          RS2.Sort = "ordem"
          While Not RS2.EOF
            If cDbl(Nvl(RS2("tit_exec"),0))    = cDbl(w_usuario) or _
               cDbl(Nvl(RS2("sub_exec"),0))    = cDbl(w_usuario) or _
               cDbl(Nvl(RS2("titular"),0))     = cDbl(w_usuario) or _
               cDbl(Nvl(RS2("substituto"),0))  = cDbl(w_usuario) or _
               cDbl(Nvl(RS2("solicitante"),0)) = cDbl(w_usuario) or _
               cDbl(Nvl(RS2("executor"),0))    = cDbl(w_usuario) or _
               cDbl(Nvl(RS2("sq_pessoa"),0))   = cDbl(w_usuario) _
            Then
               ShowHTML EtapaLinha(w_chave, RS2("sq_projeto_etapa"), RS2("titulo"), RS2("nm_resp"), RS2("sg_setor"), RS2("inicio_previsto"), RS2("fim_previsto"), RS2("perc_conclusao"), RS2("qt_ativ"), null, w_fase, "ETAPA")
            Else
               ShowHTML EtapaLinha(w_chave, RS2("sq_projeto_etapa"), RS2("titulo"), RS2("nm_resp"), RS2("sg_setor"), RS2("inicio_previsto"), RS2("fim_previsto"), RS2("perc_conclusao"), RS2("qt_ativ"), null, "N", "ETAPA")
            End If

            ' Recupera as etapas vinculadas ao nível acima
            DB_GetSolicEtapa RS3, w_chave, RS2("sq_projeto_etapa"), "LSTNIVEL"
            RS3.Sort = "ordem"
            While Not RS3.EOF
              If cDbl(Nvl(RS3("tit_exec"),0))    = cDbl(w_usuario) or _
                 cDbl(Nvl(RS3("sub_exec"),0))    = cDbl(w_usuario) or _
                 cDbl(Nvl(RS3("titular"),0))     = cDbl(w_usuario) or _
                 cDbl(Nvl(RS3("substituto"),0))  = cDbl(w_usuario) or _
                 cDbl(Nvl(RS3("solicitante"),0)) = cDbl(w_usuario) or _
                 cDbl(Nvl(RS3("executor"),0))    = cDbl(w_usuario) or _
                 cDbl(Nvl(RS3("sq_pessoa"),0))   = cDbl(w_usuario) _
              Then
                 ShowHTML EtapaLinha(w_chave, RS3("sq_projeto_etapa"), RS3("titulo"), RS3("nm_resp"), RS3("sg_setor"), RS3("inicio_previsto"), RS3("fim_previsto"), RS3("perc_conclusao"), RS3("qt_ativ"), null, w_fase, "ETAPA")
              Else
                 ShowHTML EtapaLinha(w_chave, RS3("sq_projeto_etapa"), RS3("titulo"), RS3("nm_resp"), RS3("sg_setor"), RS3("inicio_previsto"), RS3("fim_previsto"), RS3("perc_conclusao"), RS3("qt_ativ"), null, "N", "ETAPA")
              End If

              ' Recupera as etapas vinculadas ao nível acima
              DB_GetSolicEtapa RS4, w_chave, RS3("sq_projeto_etapa"), "LSTNIVEL"
              RS4.Sort = "ordem"
              While Not RS4.EOF
                If cDbl(Nvl(RS4("tit_exec"),0))    = cDbl(w_usuario) or _
                   cDbl(Nvl(RS4("sub_exec"),0))    = cDbl(w_usuario) or _
                   cDbl(Nvl(RS4("titular"),0))     = cDbl(w_usuario) or _
                   cDbl(Nvl(RS4("substituto"),0))  = cDbl(w_usuario) or _
                   cDbl(Nvl(RS4("solicitante"),0)) = cDbl(w_usuario) or _
                   cDbl(Nvl(RS4("executor"),0))    = cDbl(w_usuario) or _
                   cDbl(Nvl(RS4("sq_pessoa"),0))   = cDbl(w_usuario) _
                Then
                   ShowHTML EtapaLinha(w_chave, RS4("sq_projeto_etapa"), RS4("titulo"), RS4("nm_resp"), RS4("sg_setor"), RS4("inicio_previsto"), RS4("fim_previsto"), RS4("perc_conclusao"), RS4("qt_ativ"), null, w_fase, "ETAPA")
                Else
                   ShowHTML EtapaLinha(w_chave, RS4("sq_projeto_etapa"), RS4("titulo"), RS4("nm_resp"), RS4("sg_setor"), RS4("inicio_previsto"), RS4("fim_previsto"), RS4("perc_conclusao"), RS4("qt_ativ"), null, "N", "ETAPA")
                End If
                RS4.MoveNext
              wend

              RS3.MoveNext
            wend

            RS2.MoveNext
          wend

          RS1.MoveNext
        wend
        
        RS.MoveNext
      wend
      ShowHTML "      </FORM>"
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
    AbreForm "Form", w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,w_pagina&par,O
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_chave_aux & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_perc_ant"" value=""" & w_perc_conclusao & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_exequivel"" value=""N"">"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"

    ShowHTML "<tr><td align=""center"" bgcolor=""#FAEBD7""><table border=1 width=""100%""><tr><td>"
    ShowHTML "    <TABLE WIDTH=""100%"" CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "      <tr><td><font size=""1"">Etapa:<b><br><font size=2>" & MontaOrdemEtapa(w_chave_aux) & ". " & w_titulo & "</font></font></td>"
    ShowHTML "      <tr><td><font size=""1"">Descrição:<b><br>" & w_descricao & "</td>"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
    ShowHTML "              <td><font size=""1"">Previsão início:<b><br>" & FormataDataEdicao(Nvl(w_inicio,Date())) & "</td>"
    ShowHTML "              <td><font size=""1"">Previsão término:<b><br>" & FormataDataEdicao(w_fim) & "</td>"
    ShowHTML "              <td><font size=""1"">Orçamento previsto:<b><br>" & FormatNumber(w_orcamento,2) & "</td>"
    ShowHTML "          <tr valign=""top"">"
    DB_GetPersonData RS, w_cliente, w_sq_pessoa, null, null
    ShowHTML "              <td><font size=""1"">Responsável pela etapa:<b><br>" & RS("nome_resumido") & "</td>"
    DesconectaBD
    DB_GetUorgData RS, w_sq_unidade
    ShowHTML "              <td><font size=""1"">Setor responsável pela etapa:<b><br>" & RS("nome") & " (" & RS("sigla") & ")</td>"
    DesconectaBD
    ShowHTML "              <td><font size=""1"">Permite vinculação de atividades:<b><br>"
    If w_vincula_atividade = "S" Then ShowHTML "                  Sim" Else ShowHTML "                  Não" End If
    DB_GetPersonData RS, w_cliente, w_sq_pessoa_atualizacao, null, null
    ShowHTML "      <tr><td colspan=3><font size=""1"">Criação/última atualização:<b><br><font size=1>" & FormataDataEdicao(w_ultima_atualizacao) & "</b>, feita por <b>" & RS("nome_resumido") & " (" & RS("sigla") & ")</b></font></font></td>"
    DesconectaBD
    ShowHTML "          </table>"
    ShowHTML "    </TABLE>"
    ShowHTML "</table>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    If O = "V" Then
       ShowHTML "      <tr><td><font size=""1"">Percentual de conlusão:<br><b>" & nvl(w_perc_conclusao,0) & "%</b></td>"
       ShowHTML "      <tr><td valign=""top""><font size=""1"">Situação atual da etapa:<b><br>" & Nvl(w_situacao_atual,"---") & "</td>"
    Else
       ShowHTML "      <tr><td><font size=""1""><b>Percentual de co<u>n</u>clusão:<br><INPUT ACCESSKEY=""N"" TYPE=""TEXT"" CLASS=""STI"" NAME=""w_perc_conclusao"" SIZE=3 MAXLENGTH=3 VALUE=""" & nvl(w_perc_conclusao,0) & """ " & w_Disabled & " title=""Indique o percentual de conclusão já atingido por essa etapa.""></td>"
       ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>S</u>ituação atual da etapa:</b><br><textarea " & w_Disabled & " accesskey=""S"" name=""w_situacao_atual"" class=""STI"" ROWS=5 cols=75 title=""Descreva a situação em a etapa encontra-se."">" & w_situacao_atual & "</TEXTAREA></td>"
    End If
    ShowHTML "      <tr>"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td align=""center"" colspan=4><hr>"
    If P1 = 10 Then
       ShowHTML "            <input class=""STB"" type=""button"" onClick=""window.close();"" name=""Botao"" value=""Fechar"">"
    Else
       If O = "A" Then
          ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Atualizar"">"
       End If
       ShowHTML "            <input class=""STB"" type=""button"" onClick=""history.back(1);"" name=""Botao"" value=""Voltar"">"
    End If
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    'ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set RS1                       = Nothing 
  Set RS2                       = Nothing 
  Set RS3                       = Nothing 
  Set RS4                       = Nothing 
  Set w_inicio                  = Nothing 
  Set w_fim                     = Nothing 
  Set w_perc_conclusao          = Nothing 
  Set w_orcamento               = Nothing
  Set w_sq_pessoa               = Nothing
  Set w_sq_unidade              = Nothing
  Set w_vincula_atividade       = Nothing
  Set w_ultima_atualizacao      = Nothing
  Set w_sq_pessoa_atualizacao   = Nothing
  Set w_situacao_atual          = Nothing
  Set w_fase                    = Nothing
  Set w_p2                      = Nothing

  Set w_chave                   = Nothing 
  Set w_chave_pai               = Nothing 
  Set w_chave_aux               = Nothing 
  Set w_titulo                  = Nothing 
  Set w_ordem                   = Nothing 
  Set w_descricao               = Nothing 
  
  Set w_troca                   = Nothing 
  Set i                         = Nothing 
  Set w_texto                   = Nothing
End Sub
REM =========================================================================
REM Fim da tela de atualização das etapas do projeto
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de recursos do projeto
REM -------------------------------------------------------------------------
Sub Recursos
  Dim w_chave, w_chave_pai, w_chave_aux, w_nome, w_tipo, w_descricao, w_finalidade
  
  Dim w_troca, i, w_texto
  
  w_Chave           = Request("w_Chave")
  w_Chave_pai       = Request("w_Chave_pai")
  w_chave_aux       = Request("w_chave_aux")
  w_troca           = Request("w_troca")
  
  If w_troca > "" Then ' Se for recarga da página
     w_nome            = Request("w_nome")
     w_tipo            = Request("w_tipo")    
     w_descricao       = Request("w_descricao")    
     w_finalidade      = Request("w_finalidade")    
  ElseIf O = "L" Then
     ' Recupera todos os registros para a listagem
     DB_GetSolicRecurso RS, w_chave, null, "LISTA"
     RS.Sort = "TIPO, NOME"
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
     ' Recupera os dados do endereço informado
     DB_GetSolicRecurso RS, w_chave, w_chave_aux, "REGISTRO"
     w_nome            = RS("nome")
     w_tipo            = RS("tipo")
     w_descricao       = RS("descricao")
     w_finalidade      = RS("finalidade")
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_nome", "Nome", "", "1", "2", "100", "1", "1"
        Validate "w_tipo", "Tipo do recurso", "SELECT", "1", "1", "10", "", "1"
        Validate "w_descricao", "Descricao", "", "", "2", "2000", "1", "1"
        Validate "w_finalidade", "Finalidade", "", "", "2", "2000", "1", "1"
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  If w_troca > "" Then
     BodyOpenClean "onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf O = "I" or O = "A" Then
     BodyOpenClean "onLoad='document.Form.w_nome.focus()';"
  Else
     BodyOpenClean "onLoad='document.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    AbreSessao
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Tipo</font></td>"
    ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
    ShowHTML "          <td><font size=""1""><b>Finalidade</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"

    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & RetornaTipoRecurso(RS("tipo")) & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("nome") & "</td>"
        ShowHTML "        <td><font size=""1"">" & CRLF2BR(Nvl(RS("finalidade"),"---")) & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & w_chave & "&w_chave_aux=" & Rs("sq_projeto_recurso") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & "GRAVA&R=" & w_Pagina & par & "&O=E&w_chave=" & w_chave & "&w_chave_aux=" & Rs("sq_projeto_recurso") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ onClick=""return confirm('Confirma a exclusão do registro?');"">Excluir</A>&nbsp"
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
    AbreForm "Form", w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_chave_aux & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>N</u>ome:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""STI"" SIZE=""90"" MAXLENGTH=""100"" VALUE=""" & w_nome & """ title=""Informe o nome do recurso.""></td>"
    ShowHTML "      <tr>"
    SelecaoTipoRecurso "<u>T</u>ipo:", "T", "Selecione o tipo deste recurso.", w_tipo, null, "w_tipo", null, null
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>D</u>escrição:</b><br><textarea " & w_Disabled & " accesskey=""D"" name=""w_descricao"" class=""STI"" ROWS=5 cols=75 title=""Descreva, se necessário, características deste recurso (conhecimentos, habilidades, perfil, capacidade etc)."">" & w_descricao & "</TEXTAREA></td>"
    ShowHTML "      <tr>"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>F</u>inalidade:</b><br><textarea " & w_Disabled & " accesskey=""F"" name=""w_finalidade"" class=""STI"" ROWS=5 cols=75 title=""Descreva, se necessário, a finalidade deste recurso para o projeto (funções desempenhadas, papel, objetivos etc)."">" & w_finalidade & "</TEXTAREA></td>"
    ShowHTML "      <tr>"
    ShowHTML "      <tr><td align=""center"" colspan=4><hr>"
    If O = "E" Then
       ShowHTML "   <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       If O = "I" Then
          ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Incluir"">"
       Else
          ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Atualizar"">"
       End If
    End If
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=L';"" name=""Botao"" value=""Cancelar"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    'ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_nome            = Nothing 
  Set w_tipo            = Nothing 
  Set w_descricao       = Nothing 
  Set w_finalidade      = Nothing

  Set w_chave           = Nothing 
  Set w_chave_pai       = Nothing 
  Set w_chave_aux       = Nothing 
  
  Set w_troca           = Nothing 
  Set i                 = Nothing 
  Set w_texto           = Nothing
End Sub
REM =========================================================================
REM Fim da tela de recursos do projeto
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de alteração dos recursos da etapa
REM -------------------------------------------------------------------------
Sub EtapaRecursos

  Dim w_chave, w_chave_pai, w_chave_aux
  Dim w_troca
  Dim w_texto
  Dim w_cont, w_contaux
  
  w_troca       = Request("w_troca")
  w_chave       = Request("w_chave")
  w_chave_aux   = Request("w_chave_aux")
  w_chave_pai   = Request("w_chave_pai")

  DB_GetSolicEtpRec RS, w_chave_aux, null
  RS.Sort = "tipo, nome"
  Cabecalho
  ShowHTML "<HEAD>"
  ScriptOpen "JavaScript"
  ValidateOpen "Validacao"
  'ShowHTML "  for (i = 0; i < theForm.w_recurso.length; i++) {"
  'ShowHTML "      if (theForm.w_recurso[i].checked) break;"
  'ShowHTML "      if (i == theForm.w_recurso.length-1) {"
  'ShowHTML "         alert('Você deve selecionar pelo menos um recurso!');"
  'ShowHTML "         return false;"
  'ShowHTML "      }"
  'ShowHTML "  }"
  ShowHTML "  theForm.Botao[0].disabled=true;"
  ShowHTML "  theForm.Botao[1].disabled=true;"
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  BodyOpenClean "onLoad=document.focus();"
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  ShowHTML "<tr><td align=""center"" bgcolor=""#FAEBD7""><table border=1 width=""100%""><tr><td>"
  ShowHTML "    <TABLE WIDTH=""100%"" CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
  ShowHTML "        <tr valign=""top"">"
  ShowHTML "          <td><font size=""1"">Etapa:<br><b>" & MontaOrdemEtapa(w_chave_aux) & " - " & RS("titulo") & "</font></td>"
  ShowHTML "          <td><font size=""1"">Início:<br> <b>" & FormataDataEdicao(RS("inicio_previsto")) & "</font></td>"
  ShowHTML "          <td><font size=""1"">Término:<br><b>" & FormataDataEdicao(RS("fim_previsto")) & "</font></td>"
  ShowHTML "        <tr colspan=3><td><font size=""1"">Descrição:<br><b>" & CRLF2BR(RS("descricao")) & "</font></td></tr>"
  ShowHTML "    </TABLE>"
  ShowHTML "</table>"
  ShowHTML "<tr><td align=""right""><font size=""1"">&nbsp;"
  AbreForm "Form", w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"ETAPAREC",R,O
  ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_chave_aux & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_sg"" value=""" & Request("w_sg") & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_recurso"" value="""">"
  ShowHTML "<tr><td><font size=""1""><ul><b>Informações:</b><li>Indique abaixo quais recursos estarão alocados a esta etapa do projeto.<li>A princípio, uma etapa não tem nenhum recurso alocado.<li>Para remover um recurso, desmarque o quadrado ao seu lado.</ul>"
  ShowHTML "<tr><td align=""center"" colspan=3>"
  ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
  ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
  ShowHTML "          <td><font size=""1""><b>&nbsp;</font></td>"
  ShowHTML "          <td><font size=""1""><b>Tipo</font></td>"
  ShowHTML "          <td><font size=""1""><b>Recurso</font></td>"
  ShowHTML "          <td><font size=""1""><b>Finalidade</font></td>"
  ShowHTML "        </tr>"
  If RS.EOF Then
      ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font  size=""2""><b>Não foram encontrados registros.</b></td></tr>"
  Else
    While Not RS.EOF
      If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
      ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
      If cDbl(Nvl(RS("existe"),0)) > 0 Then
         ShowHTML "        <td align=""center""><font  size=""1""><input type=""checkbox"" name=""w_recurso"" value=""" & RS("sq_projeto_recurso") & """ checked></td>"
      Else
         ShowHTML "        <td align=""center""><font  size=""1""><input type=""checkbox"" name=""w_recurso"" value=""" & RS("sq_projeto_recurso") & """></td>"
      End If
      ShowHTML "        <td align=""left""><font  size=""1"">" & RetornaTipoRecurso(RS("tipo")) & "</td>"
      ShowHTML "        <td align=""left""><font  size=""1"">" & RS("nome") & "</td>"
      ShowHTML "        <td align=""left""><font  size=""1"">" & CRLF2BR(Nvl(RS("finalidade"),"---")) & "</td>"
      ShowHTML "      </tr>"
      RS.MoveNext
    wend
  End If
  ShowHTML "      </center>"
  ShowHTML "    </table>"
  ShowHTML "  </td>"
  ShowHTML "</tr>"
  DesConectaBD
  ShowHTML "      <tr><td align=""center""><font size=1>&nbsp;"
  ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000"">"
  ShowHTML "      <tr><td align=""center"">"
  ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Gravar"">"
  ShowHTML "            <input class=""STB"" type=""button"" onClick=""history.back(1);"" name=""Botao"" value=""Cancelar"">"
  ShowHTML "          </td>"
  ShowHTML "      </tr>"
  ShowHTML "</table>"
  ShowHTML "</center>"
  ShowHTML "</FORM>"
  Rodape

  Set w_chave           = Nothing 
  Set w_chave_pai       = Nothing 
  Set w_chave_aux       = Nothing 
  Set w_troca           = Nothing
  Set w_texto           = Nothing
  Set w_cont            = Nothing
  Set w_contaux         = Nothing

End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de interessados
REM -------------------------------------------------------------------------
Sub Interessados
  Dim w_chave, w_chave_pai, w_chave_aux, w_nome, w_tipo_visao, w_envia_email
  
  Dim w_troca, i, w_erro
  
  w_Chave           = Request("w_Chave")
  w_chave_aux       = Request("w_chave_aux")
  w_troca           = Request("w_troca")
  
  If w_troca > "" Then ' Se for recarga da página
     w_tipo_visao           = Request("w_tipo_visao")
     w_envia_email          = Request("w_envia_email")    
  ElseIf O = "L" Then
     ' Recupera todos os registros para a listagem
     DB_GetSolicInter RS, w_chave, null, "LISTA"
     RS.Sort = "nome_resumido"
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
     ' Recupera os dados do endereço informado
     DB_GetSolicInter RS, w_chave, w_chave_aux, "REGISTRO"
     w_nome                 = RS("nome_resumido")
     w_tipo_visao           = RS("tipo_visao")
     w_envia_email          = RS("envia_email")    
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     modulo
     checkbranco
     formatadata
     FormataCEP
     FormataValor
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_chave_aux", "Pessoa", "HIDDEN", "1", "1", "10", "", "1"
        Validate "w_tipo_visao", "Tipo de visão", "SELECT", "1", "1", "10", "", "1"
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  If w_troca > "" Then
     BodyOpenClean "onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf O = "I" Then
     BodyOpenClean "onLoad='document.Form.w_chave_aux.focus()';"
  Else
     BodyOpenClean "onLoad='document.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    AbreSessao
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Pessoa</font></td>"
    ShowHTML "          <td><font size=""1""><b>Visao</font></td>"
    ShowHTML "          <td><font size=""1""><b>Envia e-mail</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & ExibePessoa(null, w_cliente, RS("sq_pessoa"), TP, RS("nome") & " (" & RS("lotacao") & ")") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RetornaTipoVisao(RS("tipo_visao")) & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & Replace(Replace(RS("envia_email"),"S","Sim"),"N","Não") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & w_chave & "&w_chave_aux=" & Rs("sq_pessoa") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & "GRAVA&R=" & w_Pagina & par & "&O=E&w_chave=" & w_chave & "&w_chave_aux=" & Rs("sq_pessoa") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ onClick=""return confirm('Confirma a exclusão do registro?');"">Excluir</A>&nbsp"
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
    AbreForm "Form", w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
    If O = "I" Then
       SelecaoPessoa "<u>P</u>essoa:", "N", "Selecione o interessado na relação.", w_chave_aux, null, "w_chave_aux", "USUARIOS"
    Else
       ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_chave_aux &""">"
       ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Pessoa:</b><br>" & w_nome & "</td>"
    End If
    SelecaoTipoVisao "<u>T</u>ipo de visão:", "T", "Selecione o tipo de visão que o interessado terá deste projeto.", w_tipo_visao, null, "w_tipo_visao", null, null
    ShowHTML "          </table>"
    ShowHTML "      <tr>"
    MontaRadioNS "<b>Envia e-mail ao interessado quando houver encaminhamento?</b>", w_envia_email, "w_envia_email"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td align=""center"" colspan=4><hr>"
    If O = "E" Then
       ShowHTML "   <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       If O = "I" Then
          ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Incluir"">"
       Else
          ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Atualizar"">"
       End If
    End If
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=L';"" name=""Botao"" value=""Cancelar"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    'ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_chave           = Nothing 
  Set w_chave_pai       = Nothing 
  Set w_chave_aux       = Nothing 
  Set w_nome            = Nothing 
  Set w_tipo_visao      = Nothing 
  Set w_envia_email     = Nothing 
  
  Set w_troca           = Nothing 
  Set i                 = Nothing 
  Set w_erro            = Nothing
End Sub
REM =========================================================================
REM Fim da tela de interessados
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de áreas envolvidas
REM -------------------------------------------------------------------------
Sub Areas
  Dim w_chave, w_chave_pai, w_chave_aux, w_nome, w_papel, w_envia_email
  
  Dim w_troca, i, w_erro
  
  w_Chave           = Request("w_Chave")
  w_chave_aux       = Request("w_chave_aux")
  w_troca           = Request("w_troca")
  
  If w_troca > "" Then ' Se for recarga da página
     w_papel                = Request("w_papel")
  ElseIf O = "L" Then
     ' Recupera todos os registros para a listagem
     DB_GetSolicAreas RS, w_chave, null, "LISTA"
     RS.Sort = "nome"
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
     ' Recupera os dados do endereço informado
     DB_GetSolicAreas RS, w_chave, w_chave_aux, "REGISTRO"
     w_nome                 = RS("nome")
     w_papel                = RS("papel")
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     modulo
     checkbranco
     formatadata
     FormataCEP
     FormataValor
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_chave_aux", "Área/Instituição", "HIDDEN", "1", "1", "10", "", "1"
        Validate "w_papel", "Papel desempenhado", "", "1", "1", "2000", "1", "1"
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  If w_troca > "" Then
     BodyOpenClean "onLoad='document.Form." & w_troca & ".focus()';"
  Else
     BodyOpenClean "onLoad='document.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    AbreSessao
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Área/Instituição</font></td>"
    ShowHTML "          <td><font size=""1""><b>Papel</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & RS("nome") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("papel") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & w_chave & "&w_chave_aux=" & Rs("sq_unidade") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & "GRAVA&R=" & w_Pagina & par & "&O=E&w_chave=" & w_chave & "&w_chave_aux=" & Rs("sq_unidade") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ onClick=""return confirm('Confirma a exclusão do registro?');"">Excluir</A>&nbsp"
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
    AbreForm "Form", w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
    If O = "I" Then
       SelecaoUnidade "<U>Á</U>rea/Instituição:", "A", null, w_chave_aux, null, "w_chave_aux", null, null
    Else
       ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_chave_aux &""">"
       ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Área/Instituição:</b><br>" & w_nome & "</td>"
    End If
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>P</u>apel desempenhado:</b><br><textarea " & w_Disabled & " accesskey=""P"" name=""w_papel"" class=""STI"" ROWS=5 cols=75 title=""Descreva o papel desempenhado pela área ou instituição na execução do projeto."">" & w_papel & "</TEXTAREA></td>"
    ShowHTML "          </table>"
    ShowHTML "      <tr><td align=""center"" colspan=4><hr>"
    If O = "E" Then
       ShowHTML "   <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       If O = "I" Then
          ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Incluir"">"
       Else
          ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Atualizar"">"
       End If
    End If
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=L';"" name=""Botao"" value=""Cancelar"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    'ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_chave           = Nothing 
  Set w_chave_pai       = Nothing 
  Set w_chave_aux       = Nothing 
  Set w_nome            = Nothing 
  Set w_papel           = Nothing 
  
  Set w_troca           = Nothing 
  Set i                 = Nothing 
  Set w_erro            = Nothing
End Sub
REM =========================================================================
REM Fim da tela de áreas envolvidas
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
  
  Cabecalho

  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>" & conSgSistema & " - Visualização de projeto</TITLE>"
  ShowHTML "</HEAD>"  
  BodyOpenClean "onLoad='document.focus()'; "
  ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" SRC=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "EMBED") & """><TD ALIGN=""RIGHT""><B><FONT SIZE=4 COLOR=""#000000"">"
  ShowHTML "Visualização de Projeto"
  ShowHTML "</FONT><TR><TD ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B></TD></TR>"
  ShowHTML "</FONT></B></TD></TR></TABLE>"
  ShowHTML "<HR>"
  If w_tipo > "" Then
     ShowHTML "<center><B><font size=1>Clique <a class=""HL"" href=""javascript:history.back(1);"">aqui</a> para voltar à tela anterior</font></b></center>"
  End If

  ' Chama a rotina de visualização dos dados do projeto, na opção "Listagem"
  ShowHTML VisualProjeto(w_chave, O, w_usuario)

  If w_tipo > "" Then
     ShowHTML "<center><B><font size=1>Clique <a class=""HL"" href=""javascript:history.back(1);"">aqui</a> para voltar à tela anterior</font></b></center>"
  End If

  Rodape

  Set w_tipo                = Nothing 
  Set w_erro                = Nothing 
  Set w_logo                = Nothing 
  Set w_chave               = Nothing

End Sub
REM =========================================================================
REM Fim da rotina de visualização
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de exclusão
REM -------------------------------------------------------------------------
Sub Excluir

  Dim w_chave, w_chave_pai, w_chave_aux, w_observacao
  
  Dim w_troca, i, w_erro
  
  w_Chave           = Request("w_Chave")
  w_chave_aux       = Request("w_chave_aux")
  w_troca           = Request("w_troca")
  
  If w_troca > "" Then ' Se for recarga da página
     w_observacao     = Request("w_observacao")
  End If

  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & MontaURL("MESA") & """>"
  If InStr("E",O) > 0 Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     If P1 <> 1 Then ' Se não for encaminhamento
        ShowHTML "  theForm.Botao[0].disabled=true;"
        ShowHTML "  theForm.Botao[1].disabled=true;"
     Else
        ShowHTML "  theForm.Botao.disabled=true;"
     End If
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  If w_troca > "" Then
     BodyOpenClean "onLoad='document.Form." & w_troca & ".focus()';"
  Else
     BodyOpenClean "onLoad='document.Form.w_assinatura.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"

  ' Chama a rotina de visualização dos dados do projeto, na opção "Listagem"
  ShowHTML VisualProjeto(w_chave, "V", w_usuario)

  ShowHTML "<HR>"
  AbreForm "Form", w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"PJGERAL",R,O
  ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & w_menu & """>"
  DB_GetSolicData RS, w_chave, "PJGERAL"
  ShowHTML "<INPUT type=""hidden"" name=""w_tramite"" value=""" & RS("sq_siw_tramite") & """>"
  DesconectaBD

  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "  <table width=""97%"" border=""0"">"
  ShowHTML "      <tr><td align=""LEFT"" colspan=4><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""STI"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
  ShowHTML "    <tr><td align=""center"" colspan=4><hr>"
  ShowHTML "      <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
  ShowHTML "      <input class=""STB"" type=""button"" onClick=""history.back(1);"" name=""Botao"" value=""Abandonar"">"
  ShowHTML "      </td>"
  ShowHTML "    </tr>"
  ShowHTML "  </table>"
  ShowHTML "  </TD>"
  ShowHTML "</tr>"
  ShowHTML "</FORM>"
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_chave           = Nothing 
  Set w_chave_pai       = Nothing 
  Set w_chave_aux       = Nothing 
  Set w_observacao      = Nothing 
  
  Set w_troca           = Nothing 
  Set i                 = Nothing 
  Set w_erro            = Nothing
End Sub
REM =========================================================================
REM Fim da rotina de exclusão
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de tramitação
REM -------------------------------------------------------------------------
Sub Encaminhamento

  Dim w_chave, w_chave_pai, w_chave_aux, w_destinatario, w_despacho, w_tramite
  Dim w_sg_tramite, w_novo_tramite
  
  Dim w_troca, i, w_erro
  
  w_Chave           = Request("w_Chave")
  w_chave_aux       = Request("w_chave_aux")
  w_troca           = Request("w_troca")
  
  If w_troca > "" Then ' Se for recarga da página
     w_tramite      = Request("w_tramite")
     w_destinatario = Request("w_destinatario")
     w_novo_tramite = Request("w_novo_tramite")
     w_despacho     = Request("w_despacho")
  Else
     DB_GetSolicData RS, w_chave, "PJGERAL"
     w_tramite      = RS("sq_siw_tramite")
     w_novo_tramite = RS("sq_siw_tramite")
     DesconectaBD
  End If
  
  ' Recupera a sigla do trâmite desejado, para verificar a lista de possíveis destinatários.
  DB_GetTramiteData RS, w_novo_tramite
  w_sg_tramite   = RS("sigla")
  DesconectaBD

  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & MontaURL("MESA") & """>"
  If InStr("V",O) > 0 Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     Validate "w_destinatario", "Destinatário", "HIDDEN", "1", "1", "10", "", "1"
     Validate "w_despacho", "Despacho", "", "1", "1", "2000", "1", "1"
     Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     If P1 <> 1 Then ' Se não for encaminhamento
        ShowHTML "  theForm.Botao[0].disabled=true;"
        ShowHTML "  theForm.Botao[1].disabled=true;"
     Else
        ShowHTML "  theForm.Botao.disabled=true;"
     End If
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  If w_troca > "" Then
     BodyOpenClean "onLoad='document.Form." & w_troca & ".focus()';"
  Else
     BodyOpenClean "onLoad='document.Form.w_destinatario.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"

  ' Chama a rotina de visualização dos dados do projeto, na opção "Listagem"
  ShowHTML VisualProjeto(w_chave, "V", w_usuario)

  ShowHTML "<HR>"
  AbreForm "Form", w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"PJENVIO",R,O
  ShowHTML MontaFiltro("POST")
  ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & w_menu & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_tramite"" value=""" & w_tramite & """>"

  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "  <table width=""97%"" border=""0"">"
  ShowHTML "    <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
  If P1 <> 1 Then ' Se não for cadastramento
     SelecaoFase "<u>F</u>ase do projeto:", "F", "Se deseja alterar a fase atual do projeto, selecione a fase para a qual deseja enviá-lo.", w_novo_tramite, w_menu, "w_novo_tramite", null, "onChange=""document.Form.action='" & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='w_destinatario'; document.Form.submit();"""
     ' Se for envio para o cadastramento, exibe apenas as pessoas autorizadas a fazê-lo.
     If w_sg_tramite = "CI" Then
        SelecaoSolicResp "<u>D</u>estinatário:", "D", "Selecione um destinatário para o projeto na relação.", w_destinatario, w_chave, w_novo_tramite, w_novo_tramite, "w_destinatario", "CADASTRAMENTO"
     Else
        SelecaoPessoa "<u>D</u>estinatário:", "D", "Selecione um destinatário para o projeto na relação.", w_destinatario, null, "w_destinatario", "USUARIOS"
     End If
  Else
     SelecaoFase "<u>F</u>ase do projeto:", "F", "Se deseja alterar a fase atual do projeto, selecione a fase para a qual deseja enviá-lo.", w_novo_tramite, w_menu, "w_novo_tramite", null, null
     SelecaoPessoa "<u>D</u>estinatário:", "D", "Selecione um destinatário para o projeto na relação.", w_destinatario, null, "w_destinatario", "USUARIOS"
  End If
  ShowHTML "    <tr><td valign=""top"" colspan=2><font size=""1""><b>D<u>e</u>spacho:</b><br><textarea " & w_Disabled & " accesskey=""E"" name=""w_despacho"" class=""STI"" ROWS=5 cols=75 title=""Descreva o papel desempenhado pela área ou instituição na execução do projeto."">" & w_despacho & "</TEXTAREA></td>"
  ShowHTML "      </table>"
  ShowHTML "      <tr><td align=""LEFT"" colspan=4><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""STI"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
  ShowHTML "    <tr><td align=""center"" colspan=4><hr>"
  ShowHTML "      <input class=""STB"" type=""submit"" name=""Botao"" value=""Enviar"">"
  If P1 <> 1 Then ' Se não for cadastramento
     ' Volta para a listagem
     DB_GetMenuData RS, w_menu
     ShowHTML "      <input class=""STB"" type=""button"" onClick=""location.href='" & RS("link") & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & rs("sigla") & MontaFiltro("GET") & "';"" name=""Botao"" value=""Abandonar"">"
     DesconectaBD
  End If
  ShowHTML "      </td>"
  ShowHTML "    </tr>"
  ShowHTML "  </table>"
  ShowHTML "  </TD>"
  ShowHTML "</tr>"
  ShowHTML "</FORM>"
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_tramite         = Nothing 
  Set w_chave           = Nothing 
  Set w_chave_pai       = Nothing 
  Set w_chave_aux       = Nothing 
  Set w_destinatario    = Nothing 
  Set w_despacho        = Nothing 
  
  Set w_troca           = Nothing 
  Set i                 = Nothing 
  Set w_erro            = Nothing
End Sub
REM =========================================================================
REM Fim da rotina de encaminhamento
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de anotação
REM -------------------------------------------------------------------------
Sub Anotar

  Dim w_chave, w_chave_pai, w_chave_aux, w_observacao
  
  Dim w_troca, i, w_erro
  
  w_Chave           = Request("w_Chave")
  w_chave_aux       = Request("w_chave_aux")
  w_troca           = Request("w_troca")
  
  If w_troca > "" Then ' Se for recarga da página
     w_observacao     = Request("w_observacao")
  End If

  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & MontaURL("MESA") & """>"
  If InStr("V",O) > 0 Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     Validate "w_observacao", "Anotação", "", "1", "1", "2000", "1", "1"
     Validate "w_caminho", "Arquivo", "", "", "5", "255", "1", "1"
     Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     If P1 <> 1 Then ' Se não for encaminhamento
        ShowHTML "  theForm.Botao[0].disabled=true;"
        ShowHTML "  theForm.Botao[1].disabled=true;"
     Else
        ShowHTML "  theForm.Botao.disabled=true;"
     End If
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  If w_troca > "" Then
     BodyOpenClean "onLoad='document.Form." & w_troca & ".focus()';"
  Else
     BodyOpenClean "onLoad='document.Form.w_observacao.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"

  ' Chama a rotina de visualização dos dados do projeto, na opção "Listagem"
  ShowHTML VisualProjeto(w_chave, "V", w_usuario)

  ShowHTML "<HR>"
  ShowHTML "<FORM action=""" & w_pagina & "Grava&SG=PJENVIO&O="&O&"&w_menu="&w_menu&""" name=""Form"" onSubmit=""return(Validacao(this));"" enctype=""multipart/form-data"" method=""POST"">"
  ShowHTML "<INPUT type=""hidden"" name=""P1"" value=""" & P1 & """>"
  ShowHTML "<INPUT type=""hidden"" name=""P2"" value=""" & P2 & """>"
  ShowHTML "<INPUT type=""hidden"" name=""P3"" value=""" & P3 & """>"
  ShowHTML "<INPUT type=""hidden"" name=""P4"" value=""" & P4 & """>"
  ShowHTML "<INPUT type=""hidden"" name=""TP"" value=""" & TP & """>"
  ShowHTML "<INPUT type=""hidden"" name=""R"" value=""" & R & """>"

  ShowHTML MontaFiltro("POST")
  ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & w_menu & """>"  
  DB_GetSolicData RS, w_chave, "PJGERAL"
  ShowHTML "<INPUT type=""hidden"" name=""w_tramite"" value=""" & RS("sq_siw_tramite") & """>"
  DesconectaBD

  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "  <table width=""97%"" border=""0"">"
  ShowHTML "    <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
  DB_GetCustomerData RS, w_cliente  
  ShowHTML "      <tr><td align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""2""><b><font color=""#BC3131"">ATENÇÃO</font>: o tamanho máximo aceito para o arquivo é de " & cDbl(RS("upload_maximo"))/1024 & " KBytes</b>.</font></td>"
  ShowHTML "<INPUT type=""hidden"" name=""w_upload_maximo"" value=""" & RS("upload_maximo") & """>"  
  ShowHTML "      <tr><td valign=""top""><font size=""1""><b>A<u>n</u>otação:</b><br><textarea " & w_Disabled & " accesskey=""N"" name=""w_observacao"" class=""STI"" ROWS=5 cols=75 title=""'Redija a anotação desejada."">" & w_observacao & "</TEXTAREA></td>"  
  ShowHTML "      <tr><td><font size=""1""><b>A<u>r</u>quivo:</b><br><input " & w_Disabled & " accesskey=""R"" type=""file"" name=""w_caminho"" class=""STI"" SIZE=""80"" MAXLENGTH=""100"" VALUE="""" title=""'OPCIONAL. Se desejar anexar um arquivo, clique no botão ao lado para localizá-lo. Ele será transferido automaticamente para o servidor."">"  
  ShowHTML "      </table>"
  ShowHTML "      <tr><td align=""LEFT"" colspan=4><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""STI"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
  ShowHTML "    <tr><td align=""center"" colspan=4><hr>"
  ShowHTML "      <input class=""STB"" type=""submit"" name=""Botao"" value=""Gravar"">"
  ShowHTML "      <input class=""STB"" type=""button"" onClick=""history.back(1);"" name=""Botao"" value=""Abandonar"">"
  ShowHTML "      </td>"
  ShowHTML "    </tr>"
  ShowHTML "  </table>"
  ShowHTML "  </TD>"
  ShowHTML "</tr>"
  ShowHTML "</FORM>"
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_chave           = Nothing 
  Set w_chave_pai       = Nothing 
  Set w_chave_aux       = Nothing 
  Set w_observacao      = Nothing 
  
  Set w_troca           = Nothing 
  Set i                 = Nothing 
  Set w_erro            = Nothing
End Sub

REM =========================================================================
REM Rotina de anotação
REM -------------------------------------------------------------------------
Sub Anotar12

  Dim w_chave, w_chave_pai, w_chave_aux, w_observacao
  
  Dim w_troca, i, w_erro
  
  w_Chave           = Request("w_Chave")
  w_chave_aux       = Request("w_chave_aux")
  w_troca           = Request("w_troca")
  
  If w_troca > "" Then ' Se for recarga da página
     w_observacao     = Request("w_observacao")
  End If

  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & MontaURL("MESA") & """>"
  If InStr("V",O) > 0 Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     Validate "w_observacao", "Anotação", "", "1", "1", "2000", "1", "1"
     Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     If P1 <> 1 Then ' Se não for encaminhamento
        ShowHTML "  theForm.Botao[0].disabled=true;"
        ShowHTML "  theForm.Botao[1].disabled=true;"
     Else
        ShowHTML "  theForm.Botao.disabled=true;"
     End If
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  If w_troca > "" Then
     BodyOpenClean "onLoad='document.Form." & w_troca & ".focus()';"
  Else
     BodyOpenClean "onLoad='document.Form.w_observacao.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"

  ' Chama a rotina de visualização dos dados do projeto, na opção "Listagem"
  ShowHTML VisualProjeto(w_chave, "V", w_usuario)

  ShowHTML "<HR>"
  AbreForm "Form", w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"PJENVIO",R,O
  ShowHTML MontaFiltro("POST")
  ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & w_menu & """>"
  DB_GetSolicData RS, w_chave, "PJGERAL"
  ShowHTML "<INPUT type=""hidden"" name=""w_tramite"" value=""" & RS("sq_siw_tramite") & """>"
  DesconectaBD

  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "  <table width=""97%"" border=""0"">"
  ShowHTML "    <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
  ShowHTML "    <tr><td valign=""top""><font size=""1""><b>A<u>n</u>otação:</b><br><textarea " & w_Disabled & " accesskey=""N"" name=""w_observacao"" class=""STI"" ROWS=5 cols=75 title=""Redija a anotação desejada."">" & w_observacao & "</TEXTAREA></td>"
  ShowHTML "      </table>"
  ShowHTML "      <tr><td align=""LEFT"" colspan=4><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""STI"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
  ShowHTML "    <tr><td align=""center"" colspan=4><hr>"
  ShowHTML "      <input class=""STB"" type=""submit"" name=""Botao"" value=""Gravar"">"
  ShowHTML "      <input class=""STB"" type=""button"" onClick=""history.back(1);"" name=""Botao"" value=""Abandonar"">"
  ShowHTML "      </td>"
  ShowHTML "    </tr>"
  ShowHTML "  </table>"
  ShowHTML "  </TD>"
  ShowHTML "</tr>"
  ShowHTML "</FORM>"
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_chave           = Nothing 
  Set w_chave_pai       = Nothing 
  Set w_chave_aux       = Nothing 
  Set w_observacao      = Nothing 
  
  Set w_troca           = Nothing 
  Set i                 = Nothing 
  Set w_erro            = Nothing
End Sub
REM =========================================================================
REM Fim da rotina de anotação
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de conclusão
REM -------------------------------------------------------------------------
Sub Concluir

  Dim w_chave, w_chave_pai, w_chave_aux, w_destinatario
  Dim w_inicio_real, w_fim_real, w_nota_conclusao, w_custo_real
  
  Dim w_troca, i, w_erro
  
  w_Chave           = Request("w_Chave")
  w_chave_aux       = Request("w_chave_aux")
  w_troca           = Request("w_troca")
  
  If w_troca > "" Then ' Se for recarga da página
     w_inicio_real         = Request("w_inicio_real") 
     w_fim_real            = Request("w_fim_real") 
     w_concluida           = Request("w_concluida") 
     w_data_conclusao      = Request("w_data_conclusao") 
     w_nota_conclusao      = Request("w_nota_conclusao") 
     w_custo_real          = Request("w_custo_real") 
  End If

  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & MontaURL("MESA") & """>"
  If InStr("V",O) > 0 Then
     ScriptOpen "JavaScript"
     CheckBranco
     FormataData
     FormataDataHora
     FormataValor
     ValidateOpen "Validacao"
     Select Case RS_menu("data_hora")
        Case 1
           Validate "w_fim_real", "Término da execução", "DATA", 1, 10, 10, "", "0123456789/"
        Case 2
           Validate "w_fim_real", "Término da execução", "DATAHORA", 1, 17, 17, "", "0123456789/"
        Case 3
           Validate "w_inicio_real", "Início da execução", "DATA", 1, 10, 10, "", "0123456789/"
           Validate "w_fim_real", "Término da execução", "DATA", 1, 10, 10, "", "0123456789/"
           CompData "w_inicio_real", "Início da execução", "<=", "w_fim_real", "Término da execução"
           CompData "w_fim_real", "Término da execução", "<=", FormataDataEdicao(FormatDateTime(Date(),2)), "data atual"
        Case 4
           Validate "w_inicio_real", "Início da execução", "DATAHORA", 1, 17, 17, "", "0123456789/,: "
           Validate "w_fim_real", "Término da execução", "DATAHORA", 1, 17, 17, "", "0123456789/,: "
           CompData "w_inicio_real", "Início da execução", "<=", "w_fim_real", "Término da execução"
     End Select
     Validate "w_custo_real", "Custo real", "VALOR", "1", 4, 18, "", "0123456789.,"
     Validate "w_nota_conclusao", "Nota de conclusão", "", "1", "1", "2000", "1", "1"
     Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     If P1 <> 1 Then ' Se não for encaminhamento
        ShowHTML "  theForm.Botao[0].disabled=true;"
        ShowHTML "  theForm.Botao[1].disabled=true;"
     Else
        ShowHTML "  theForm.Botao.disabled=true;"
     End If
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  If w_troca > "" Then
     BodyOpenClean "onLoad='document.Form." & w_troca & ".focus()';"
  Else
     BodyOpenClean "onLoad='document.Form.w_inicio_real.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"

  ' Chama a rotina de visualização dos dados do projeto, na opção "Listagem"
  ShowHTML VisualProjeto(w_chave, "V", w_usuario)

  ShowHTML "<HR>"
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "  <table width=""97%"" border=""0"">"
  ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
  ShowHTML "          <tr>"
  
  ' Verifica se o projeto tem etapas em aberto e avisa o usuário caso isso ocorra.
  DB_GetSolicEtapa RS, w_chave, null, "LISTA"
  w_cont = 0
  While NOT RS.EOF
     If cDbl(RS("perc_conclusao")) <> 100 Then
        w_cont = w_cont + 1
     End If
     RS.MoveNext
  Wend
  If w_cont > 0 Then
     ScriptOpen "JavaScript"
     ShowHTML "  alert('ATENÇÃO: das " & RS.RecordCount & " etapas deste projeto, " & w_cont & " não têm 100% de conclusão!\n\nAinda assim você poderá concluir este projeto.');"
     ScriptClose
  End If
  DesconectaBD

  AbreForm "Form", w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"PJCONC",R,O
  ShowHTML MontaFiltro("POST")
  ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & w_menu & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_concluida"" value=""S"">"
  DB_GetSolicData RS, w_chave, "PJGERAL"
  ShowHTML "<INPUT type=""hidden"" name=""w_tramite"" value=""" & RS("sq_siw_tramite") & """>"
  DesconectaBD
  Select Case RS_menu("data_hora")
     Case 1
        ShowHTML "              <td valign=""top""><font size=""1""><b><u>T</u>érmino da execução:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_fim_real"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_fim_real & """ onKeyDown=""FormataData(this,event);"" title=""Informe a data de término da execução do projeto.""></td>"
     Case 2
        ShowHTML "              <td valign=""top""><font size=""1""><b><u>T</u>érmino da execução:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_fim_real"" class=""STI"" SIZE=""17"" MAXLENGTH=""17"" VALUE=""" & w_fim_real & """ onKeyDown=""FormataDataHora(this,event);"" title=""Informe a data/hora de término da execução do projeto.""></td>"
     Case 3
        ShowHTML "              <td valign=""top""><font size=""1""><b>Iní<u>c</u>io da execução:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_inicio_real"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_inicio_real & """ onKeyDown=""FormataData(this,event);"" title=""Informe a data/hora de início da execução do projeto.""></td>"
        ShowHTML "              <td valign=""top""><font size=""1""><b><u>T</u>érmino da execução:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_fim_real"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_fim_real & """ onKeyDown=""FormataData(this,event);"" title=""Informe a data de término da execução do projeto.""></td>"
     Case 4
        ShowHTML "              <td valign=""top""><font size=""1""><b>Iní<u>c</u>io da execução:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_inicio_real"" class=""STI"" SIZE=""17"" MAXLENGTH=""17"" VALUE=""" & w_inicio_real & """ onKeyDown=""FormataDataHora(this,event);"" title=""Informe a data/hora de início da execução do projeto.""></td>"
        ShowHTML "              <td valign=""top""><font size=""1""><b><u>T</u>érmino da execução:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_fim_real"" class=""STI"" SIZE=""17"" MAXLENGTH=""17"" VALUE=""" & w_fim_real & """ onKeyDown=""FormataDataHora(this,event);"" title=""Informe a data de término da execução do projeto.""></td>"
  End Select
  ShowHTML "              <td valign=""top""><font size=""1""><b>Custo <u>r</u>eal:</b><br><input " & w_Disabled & " accesskey=""O"" type=""text"" name=""w_custo_real"" class=""STI"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_custo_real & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o orçamento disponível para execução do projeto, ou zero se não for o caso.""></td>"
  ShowHTML "          </table>"
  ShowHTML "    <tr><td valign=""top""><font size=""1""><b>Nota d<u>e</u> conclusão:</b><br><textarea " & w_Disabled & " accesskey=""E"" name=""w_nota_conclusao"" class=""STI"" ROWS=5 cols=75 title=""Descreva o quanto o projeto atendeu aos resultados esperados."">" & w_nota_conclusao & "</TEXTAREA></td>"
  ShowHTML "      <tr><td align=""LEFT"" colspan=4><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""STI"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
  ShowHTML "    <tr><td align=""center"" colspan=4><hr>"
  ShowHTML "      <input class=""STB"" type=""submit"" name=""Botao"" value=""Concluir"">"
  If P1 <> 1 Then ' Se não for cadastramento
     ShowHTML "      <input class=""STB"" type=""button"" onClick=""history.back(1);"" name=""Botao"" value=""Abandonar"">"
  End If
  ShowHTML "      </td>"
  ShowHTML "    </tr>"
  ShowHTML "  </table>"
  ShowHTML "  </TD>"
  ShowHTML "</tr>"
  ShowHTML "</FORM>"
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_chave           = Nothing 
  Set w_chave_pai       = Nothing 
  Set w_chave_aux       = Nothing 
  Set w_destinatario    = Nothing 
  Set w_nota_conclusao  = Nothing 
  
  Set w_troca           = Nothing 
  Set i                 = Nothing 
  Set w_erro            = Nothing
End Sub
REM =========================================================================
REM Fim da rotina de conclusão
REM -------------------------------------------------------------------------

REM =========================================================================
REM Gera uma linha de apresentação da tabela de etapas
REM -------------------------------------------------------------------------
Function EtapaLinha (p_chave,  p_chave_aux, p_titulo, p_resp,  p_setor, _
                     p_inicio, p_fim,       p_perc,   p_ativ,  p_destaque, _
                     p_oper,   p_tipo)
  Dim l_html, RsQuery, l_recurso, l_row
  l_recurso = ""
  
  DB_GetSolicEtpRec RSQuery, p_chave_aux, null
  RSQuery.Filter = "existe <> null"
  If Not RSQuery.EOF Then
     l_recurso = l_recurso & VbCrLf & "      <tr bgcolor=w_cor valign=""top""><td colspan=3><table border=0 width=""100%""><tr><td><font size=""1"">Recurso(s): "
     While not RsQuery.EOF
        l_recurso = l_recurso & VbCrLf & RSQuery("nome") & "; "
        RSQuery.MoveNext
     Wend
     l_recurso = l_recurso & VbCrLf & "      </tr></td></table></td></tr>"
  End If
  RSQuery.Close()
  
  If l_recurso > "" Then l_row = "rowspan=2" Else l_row = "" End If

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
  l_html = l_html & VbCrLf & " " & ExibeEtapa("V", RS("sq_siw_solicitacao"), p_chave_aux, "Volta", 10, MontaOrdemEtapa(p_chave_aux), TP, SG) & "</td>"
  'l_html = l_html & VbCrLf & "<A class=""HL"" HREF=""" & w_pagina & "AtualizaEtapa&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_chave_aux=" &p_chave_aux& "&w_tipo=Volta&P1=10&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Visualização das etapas do projeto."" target=""_blank"">" & MontaOrdemEtapa(p_chave_aux) & "</A>&nbsp"
  l_html = l_html & VbCrLf & "        <td><font size=""1"">" & p_destaque & p_titulo & "</b>"
  l_html = l_html & VbCrLf & "        <td><font size=""1"">" & ExibePessoa(null, w_cliente, RS("sq_pessoa"), TP, p_resp) & "</b>"
  l_html = l_html & VbCrLf & "        <td><font size=""1"">" & p_setor & "</b>"
  l_html = l_html & VbCrLf & "        <td align=""center"" " & l_row & "><font size=""1"">" & FormataDataEdicao(p_inicio) & "</td>"
  l_html = l_html & VbCrLf & "        <td align=""center"" " & l_row & "><font size=""1"">" & FormataDataEdicao(p_fim) & "</td>"
  l_html = l_html & VbCrLf & "        <td nowrap align=""right"" " & l_row & "><font size=""1"">" & p_perc & " %</td>"
  If cDbl(p_ativ) > cDbl(0) Then
     l_html = l_html & VbCrLf & "        <td align=""center"" " & l_row & " title=""Número de atividades ligadas a esta estapa. Clique sobre o número para exibir APENAS as atividades que você tem acesso.""><font size=""1""><a class=""hl"" href=""javascript:lista('" & p_chave & "','" & p_chave_aux & "');"" onMouseOver=""window.status='Exibe APENAS as atividades que você tem acesso.'; return true"" onMouseOut=""window.status=''; return true"">" & p_ativ & "</a></td>"
  Else
     l_html = l_html & VbCrLf & "        <td align=""center"" " & l_row & "><font size=""1"">" & p_ativ & "</td>"
  End If
  If p_oper = "S" Then
     l_html = l_html & VbCrLf & "        <td align=""top"" nowrap " & l_row & "><font size=""1"">"
     ' Se for listagem de etapas no cadastramento do projeto, exibe operações de alteração, exclusão e recursos
     If p_tipo = "PROJETO" Then
        l_html = l_html & VbCrLf & "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & p_chave & "&w_chave_aux=" & p_chave_aux & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Alterar"">Alt</A>&nbsp"
        l_html = l_html & VbCrLf & "          <A class=""HL"" HREF=""" & w_Pagina & "GRAVA&R=" & w_Pagina & par & "&O=E&w_chave=" & p_chave & "&w_chave_aux=" & p_chave_aux & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ onClick=""return confirm('Confirma a exclusão do registro?');"" title=""Excluir"">Excl</A>&nbsp"
        l_html = l_html & VbCrLf & "          <A class=""HL"" HREF=""" & w_Pagina & "EtapaRecurso&R=" & w_Pagina & par & "&O=A&w_chave=" & p_chave & "&w_chave_aux=" & p_chave_aux & "&w_menu=" & w_menu & "&w_sg=" & SG & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - Recursos&SG=" & SG & """ title=""Recursos da etapa"">Rec</A>&nbsp"
     ' Caso contrário, é listagem de atualização de etapas. Neste caso, coloca apenas a opção de alteração
     Else
        l_html = l_html & VbCrLf & "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & p_chave & "&w_chave_aux=" & p_chave_aux & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Atualiza dados da etapa"">Atualizar</A>&nbsp"
     End If
     l_html = l_html & VbCrLf & "        </td>"
  Else
     If p_tipo = "ETAPA" Then
        l_html = l_html & VbCrLf & "        <td align=""top"" nowrap " & l_row & "><font size=""1"">"
        l_html = l_html & VbCrLf & "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=V&w_chave=" & p_chave & "&w_chave_aux=" & p_chave_aux & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Atualiza dados da etapa"">Exibir</A>&nbsp"
        l_html = l_html & VbCrLf & "        </td>"
     End If
  End If
  l_html = l_html & VbCrLf &  "      </tr>"
  If l_recurso > "" Then l_html = l_html & VbCrLf &  replace(l_recurso, "w_cor", w_cor) End If
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
REM Gera uma linha de apresentação da tabela de etapas
REM -------------------------------------------------------------------------
Function EtapaLinhaAtiv (p_chave,  p_chave_aux, p_titulo, p_resp,  p_setor, _
                         p_inicio, p_fim,       p_perc,   p_ativ,  p_destaque, _
                         p_oper,   p_tipo,      p_assunto)
  Dim l_html, RsQuery, l_recurso, l_row, l_col, RSAtiv, l_ativ
  l_recurso = ""
  l_ativ    = ""
  l_row     = 1 
  l_col     = 1
  
  DB_GetSolicEtpRec RSQuery, p_chave_aux, null
  RSQuery.Filter = "existe <> null"
  If Not RSQuery.EOF Then
     l_recurso = l_recurso & VbCrLf & "      <tr bgcolor=w_cor valign=""top""><td colspan=7><font size=1>Recurso(s): "
     While not RsQuery.EOF
        l_recurso = l_recurso & VbCrLf & RSQuery("nome") & "; "
        RSQuery.MoveNext
     Wend
  End If
  RSQuery.Close()
  
  ' Recupera as atividades que o usuário pode ver
  DB_GetSolicList RSAtiv, w_menu, w_usuario, SG, 3, _
     null, null, null, null, null, null, _
     null, null, null, null, _
     null, null, null, null, null, null, null, _
     null, null, null, null, null, p_chave, p_chave_aux, null, null

  If l_recurso > "" Then l_row = l_row + 1 End If
  If p_ativ    > "" Then 
     l_row = l_row + RSAtiv.RecordCount
     l_col = 2
  End If

  If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
  l_html = l_html & VbCrLf & "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
  l_html = l_html & VbCrLf & "        <td nowrap rowspan=" & l_row & "><font size=""1"">"
  If p_fim < Date() and cDbl(p_perc) < 100 Then
     l_html = l_html & VbCrLf & "           <img src=""" & conImgAtraso & """ border=0 width=15 height=15 align=""center"">"
  ElseIf cDbl(p_perc) < 100 Then
     l_html = l_html & VbCrLf & "           <img src=""" & conImgNormal & """ border=0 width=15 height=15 align=""center"">"
  Else
     l_html = l_html & VbCrLf & "           <img src=""" & conImgOkNormal & """ border=0 width=15 height=15 align=""center"">"
  End IF
  l_html = l_html & VbCrLf & " " & ExibeEtapa("V", RS("sq_siw_solicitacao"), p_chave_aux, "Volta", 10, MontaOrdemEtapa(p_chave_aux), TP, SG) & "</td>"
  'l_html = l_html & VbCrLf & "<A class=""HL"" HREF=""" & w_pagina & "AtualizaEtapa&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_chave_aux=" &p_chave_aux& "&w_tipo=Volta&P1=10&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Visualização das etapas do projeto."" target=""_blank"">" & MontaOrdemEtapa(p_chave_aux) & "</A>&nbsp"
  l_html = l_html & VbCrLf & "        <td><font size=""1"">" & p_destaque & p_titulo & "</b>"
  l_html = l_html & VbCrLf & "        <td><font size=""1"">" & ExibePessoa(null, w_cliente, RS("sq_pessoa"), TP, p_resp) & "</b>"
  l_html = l_html & VbCrLf & "        <td><font size=""1"">" & p_setor & "</b>"
  l_html = l_html & VbCrLf & "        <td align=""center"" ><font size=""1"">" & FormataDataEdicao(p_inicio) & "</td>"
  l_html = l_html & VbCrLf & "        <td align=""center"" ><font size=""1"">" & FormataDataEdicao(p_fim) & "</td>"
  l_html = l_html & VbCrLf & "        <td nowrap align=""right"" ><font size=""1"">" & p_perc & " %</td>"
  l_html = l_html & VbCrLf & "        <td align=""center"" ><font size=""1"">" & p_ativ & "</td>"
  If p_oper = "S" Then
     l_html = l_html & VbCrLf & "        <td align=""top"" nowrap rowspan=" & l_row & "><font size=""1"">"
     ' Se for listagem de etapas no cadastramento do projeto, exibe operações de alteração, exclusão e recursos
     If p_tipo = "PROJETO" Then
        l_html = l_html & VbCrLf & "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & p_chave & "&w_chave_aux=" & p_chave_aux & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Alterar"">Alt</A>&nbsp"
        l_html = l_html & VbCrLf & "          <A class=""HL"" HREF=""" & w_Pagina & "GRAVA&R=" & w_Pagina & par & "&O=E&w_chave=" & p_chave & "&w_chave_aux=" & p_chave_aux & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ onClick=""return confirm('Confirma a exclusão do registro?');"" title=""Excluir"">Excl</A>&nbsp"
        l_html = l_html & VbCrLf & "          <A class=""HL"" HREF=""" & w_Pagina & "EtapaRecurso&R=" & w_Pagina & par & "&O=A&w_chave=" & p_chave & "&w_chave_aux=" & p_chave_aux & "&w_menu=" & w_menu & "&w_sg=" & SG & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - Recursos&SG=" & SG & """ title=""Recursos da etapa"">Rec</A>&nbsp"
     ' Caso contrário, é listagem de atualização de etapas. Neste caso, coloca apenas a opção de alteração
     Else
        l_html = l_html & VbCrLf & "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & p_chave & "&w_chave_aux=" & p_chave_aux & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Atualiza dados da etapa"">Atualizar</A>&nbsp"
     End If
     l_html = l_html & VbCrLf & "        </td>"
  Else
     If p_tipo = "ETAPA" Then
        l_html = l_html & VbCrLf & "        <td align=""top"" nowrap rowspan=" & l_row & "><font size=""1"">"
        l_html = l_html & VbCrLf & "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=V&w_chave=" & p_chave & "&w_chave_aux=" & p_chave_aux & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Atualiza dados da etapa"">Exibir</A>&nbsp"
        l_html = l_html & VbCrLf & "        </td>"
     End If
  End If

  'Listagem das tarefas da etapa  
  If Not RSAtiv.EOF Then
     While not RSAtiv.EOF
        l_ativ = l_ativ & VbCrLf & "<tr bgcolor=w_cor valign=""top"">"
        l_ativ = l_ativ & VbCrLf & "  <td><font size=""1"">"
        If RSAtiv("concluida") = "N" Then
           If RSAtiv("fim") < Date() Then
              l_ativ = l_ativ & VbCrLf & "   <img src=""" & conImgAtraso & """ border=0 width=15 heigth=15 align=""center"">"
           ElseIf RSAtiv("aviso_prox_conc") = "S" and (RSAtiv("aviso") <= Date()) Then
              l_ativ = l_ativ & VbCrLf & "   <img src=""" & conImgAviso & """ border=0 width=15 height=15 align=""center"">"
           Else
              l_ativ = l_ativ & VbCrLf & "   <img src=""" & conImgNormal & """ border=0 width=15 height=15 align=""center"">"
           End If
        Else
           If RSAtiv("fim") < Nvl(RSAtiv("fim_real"),RSAtiv("fim")) Then
              l_ativ = l_ativ & VbCrLf & "   <img src=""" & conImgOkAtraso & """ border=0 width=15 heigth=15 align=""center"">"
           Else
              l_ativ = l_ativ & VbCrLf & "   <img src=""" & conImgOkNormal & """ border=0 width=15 height=15 align=""center"">"
           End If
        End If
        l_ativ = l_ativ & VbCrLf & "  <A class=""HL"" HREF=""ProjetoAtiv.asp?par=Visual&R=ProjetoAtiv.asp?par=Visual&O=L&w_chave=" & RSAtiv("sq_siw_solicitacao") & "&w_tipo=&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exibe as informações deste registro."" target=""blank"">" & RSAtiv("sq_siw_solicitacao") & "</a>"
        If Len(Nvl(RSAtiv("assunto"),"-")) > 50 and uCase(p_assunto) <> "COMPLETO" Then 
           l_ativ = l_ativ & VbCrLf & " - " & Mid(Nvl(RSAtiv("assunto"),"-"),1,50) & "..."
        Else
           l_ativ = l_ativ & VbCrLf & " - " & Nvl(RSAtiv("assunto"),"-")
        End If
        l_ativ = l_ativ & VbCrLf & "     <td><font size=""1"">" & ExibePessoa(null, w_cliente, RSAtiv("solicitante"), TP, RSAtiv("nm_resp")) & "</td>"
        l_ativ = l_ativ & VbCrLf & "     <td><font size=""1"">" & RSAtiv("sg_unidade_resp") & "</td>"
        l_ativ = l_ativ & VbCrLf & "     <td align=""center""><font size=""1"">" & Nvl(FormataDataEdicao(RSAtiv("inicio")),"-") & "</td>"
        l_ativ = l_ativ & VbCrLf & "     <td align=""center""><font size=""1"">" & Nvl(FormataDataEdicao(RSAtiv("fim")),"-") & "</td>"
        l_ativ = l_ativ & VbCrLf & "     <td colspan=2 nowrap><font size=""1"">" & RSAtiv("nm_tramite") & "</td>"
        RSAtiv.MoveNext
     Wend
     '
  End If         
  If p_ativ > "" Then
     l_recurso = l_recurso & VbCrLf & "      </tr></td>"
     l_ativ = l_ativ & VbCrLf & "            </td></tr>"
  ElseIf l_recurso > "" Then
     l_recurso = l_recurso & VbCrLf & "      </tr></td></table></td></tr>"
  End If   
  RSAtiv.close
  l_html = l_html & VbCrLf &  "      </tr>"
  If l_recurso > "" Then l_html = l_html & VbCrLf &  replace(l_recurso, "w_cor", w_cor) End If
  If l_ativ    > "" Then l_html = l_html & VbCrLf &  replace(l_ativ, "w_cor", w_cor)    End If
  
  EtapaLinhaAtiv = l_html

  Set RsQuery   = Nothing
  Set l_row     = Nothing
  Set l_recurso = Nothing
  Set l_html    = Nothing
End Function
REM =========================================================================
REM Final da rotina
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
  w_html = w_html & "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">" & VbCrLf
  w_html = w_html & "    <table width=""97%"" border=""0"">" & VbCrLf
  If p_tipo = 1 Then
     w_html = w_html & "      <tr valign=""top""><td align=""center""><font size=2><b>INCLUSÃO DE PROJETO</b></font><br><br><td></tr>" & VbCrLf
  ElseIf p_tipo = 2 Then
     w_html = w_html & "      <tr valign=""top""><td align=""center""><font size=2><b>TRAMITAÇÃO DE PROJETO</b></font><br><br><td></tr>" & VbCrLf
  ElseIf p_tipo = 3 Then
     w_html = w_html & "      <tr valign=""top""><td align=""center""><font size=2><b>CONCLUSÃO DE PROJETO</b></font><br><br><td></tr>" & VbCrLf
  End IF
  w_html = w_html & "      <tr valign=""top""><td><font size=2><b><font color=""#BC3131"">ATENÇÃO</font>: Esta é uma mensagem de envio automático. Não responda esta mensagem.</b></font><br><br><td></tr>" & VbCrLf


  ' Recupera os dados do projeto
  DB_GetSolicData RSM, p_solic, "PJGERAL"
  
  w_nome = "Projeto " & RSM("titulo") & " (" & RSM("sq_siw_solicitacao") & ")"

  w_html = w_html & VbCrLf & "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  w_html = w_html & VbCrLf & "    <table width=""99%"" border=""0"">"
  w_html = w_html & VbCrLf & "      <tr><td><font size=2>Projeto: <b>" & RSM("titulo") & " (" & RSM("sq_siw_solicitacao") & ")</b></font></td>"
      
  ' Identificação do projeto
  w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>EXTRATO DO PROJETO</td>"
  ' Se a classificação foi informada, exibe.
  If Not IsNull(RSM("sq_cc")) Then
     w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Classificação:<br><b>" & RSM("cc_nome") & " </b></td>"
  End If
  w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
  w_html = w_html & VbCrLf & "          <tr valign=""top"">"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Responsável:<br><b>" & RSM("nm_sol") & "</b></td>"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Unidade responsável:<br><b>" & RSM("nm_unidade_resp") & "</b></td>"
  w_html = w_html & VbCrLf & "          <tr valign=""top"">"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Data de recebimento:<br><b>" & FormataDataEdicao(RSM("inicio")) & " </b></td>"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Limite para conclusão:<br><b>" & FormataDataEdicao(RSM("fim")) & " </b></td>"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Prioridade:<br><b>" & RetornaPrioridade(RSM("prioridade")) & " </b></td>"
  w_html = w_html & VbCrLf & "          </table>"

  ' Informações adicionais
  If Nvl(RSM("descricao"),"") > "" Then 
     If Nvl(RSM("descricao"),"") > "" Then w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Resultados do projeto:<br><b>" & CRLF2BR(RSM("descricao")) & " </b></td>" End If
  End If

  w_html = w_html & VbCrLf & "    </table>"
  w_html = w_html & VbCrLf & "</tr>"

  ' Dados da conclusão do projeto, se ela estiver nessa situação
  If RSM("concluida") = "S" and Nvl(RSM("data_conclusao"),"") > "" Then
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>DADOS DA CONCLUSÃO</td>"
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
     w_html = w_html & VbCrLf & "          <tr valign=""top"">"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Início da execução:<br><b>" & FormataDataEdicao(RSM("inicio_real")) & " </b></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Término da execução:<br><b>" & FormataDataEdicao(RSM("fim_real")) & " </b></td>"
     w_html = w_html & VbCrLf & "          </table>"
     w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Nota de conclusão:<br><b>" & CRLF2BR(RSM("nota_conclusao")) & " </b></td>"
  End If

  If p_tipo = 2 Then ' Se for tramitação
     ' Encaminhamentos
     DB_GetSolicLog RS, p_solic, null, "LISTA"
     RS.Sort = "data desc"
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>ÚLTIMO ENCAMINHAMENTO</td>"
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
     w_html = w_html & VbCrLf & "          <tr valign=""top"">"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">De:<br><b>" & RS("responsavel") & "</b></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Para:<br><b>" & RS("destinatario") & "</b></td>"
     w_html = w_html & VbCrLf & "          <tr valign=""top""><td colspan=2><font size=""1"">Despacho:<br><b>" & CRLF2BR(Nvl(RS("despacho"),"---")) & " </b></td>"
     w_html = w_html & VbCrLf & "          </table>"
     
     ' Configura o destinatário da tramitação como destinatário da mensagem
     DB_GetPersonData RS, w_cliente, RS("sq_pessoa_destinatario"), null, null
     w_destinatarios = RS("email") & "; "
     
     DesconectaBD
  End If

  w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>OUTRAS INFORMAÇÕES</td>"
  DB_GetCustomerSite RS, Session("p_cliente")
  w_html = w_html & "      <tr valign=""top""><td><font size=2>" & VbCrLf
  w_html = w_html & "         Para acessar o sistema use o endereço: <b><a class=""SS"" href=""" & RS("logradouro") & """ target=""_blank"">" & RS("Logradouro") & "</a></b></li>" & VbCrLf
  w_html = w_html & "      </font></td></tr>" & VbCrLf
  DesconectaBD

  w_html = w_html & "      <tr valign=""top""><td><font size=2>" & VbCrLf
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

  ' Recupera o e-mail do responsável
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
  Dim FS, F1, w_file

  Cabecalho
  ShowHTML "</HEAD>"
  BodyOpenClean "onLoad=document.focus();"
  
  AbreSessao    
  Select Case SG
    Case "PJGERAL"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          DML_PutProjetoGeral O, _
              Request("w_chave"), Request("w_menu"), Session("lotacao"), Request("w_solicitante"), _
              Request("w_proponente"), Session("sq_pessoa"), null, Request("w_sqcc"), _
              Request("w_descricao"), Request("w_justificativa"), Request("w_inicio"), _
              Request("w_fim"), Request("w_valor"), Request("w_data_hora"), _
              Request("w_sq_unidade_resp"), Request("w_titulo"), Request("w_prioridade"), _
              Request("w_aviso"), Request("w_dias"), Request("w_cidade"), Request("w_palavra_chave"), _
              Request("w_vincula_contrato"), Request("w_vincula_viagem"), null, null, null, null, null, _
              w_chave_nova, w_copia
          
          If O = "I" Then
             ' Envia e-mail comunicando a inclusão
             SolicMail Nvl(Request("w_chave"), w_chave_nova) ,1

             ' Recupera os dados para montagem correta do menu
             DB_GetMenuData RS1, w_menu
             ScriptOpen "JavaScript"
             ShowHTML "  parent.menu.location='Menu.asp?par=ExibeDocs&O=A&w_chave=" & w_chave_nova & "&w_documento=Nr. " & w_chave_nova & "&R=" & R & "&SG=" & RS1("sigla") & "&TP=" & TP & MontaFiltro("GET") & "';"

          ElseIf O = "E" Then
             ScriptOpen "JavaScript"
             ShowHTML "  location.href='" & R & "&O=L&R=" & R & "&SG=PJCAD&w_menu=" & w_menu & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & MontaFiltro("GET") & "';"
          Else
             ' Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link
             DB_GetLinkData RS1, Session("p_cliente"), SG
             ScriptOpen "JavaScript"
             ShowHTML "  location.href='" & RS1("link") & "&O=" & O & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
          End If
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "PJETAPA"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then

          DML_PutProjetoEtapa o, Request("w_chave"), Request("w_chave_aux"), Request("w_chave_pai"), _
             Request("w_titulo"), Request("w_descricao"), Request("w_ordem"), Request("w_inicio"), _
             Request("w_fim"), Request("w_perc_conclusao"), Request("w_orcamento"), _
             Request("w_sq_pessoa"), Request("w_sq_unidade"), Request("w_vincula_atividade"), w_usuario, _
             Request("w_programada"), Request("w_cumulativa"), Request("w_quantidade"), null
          
          ScriptOpen "JavaScript"
          ' Recupera a sigla do serviço pai, para fazer a chamada ao menu
          DB_GetLinkData RS, Session("p_cliente"), SG
          ShowHTML "  location.href='" & RS("link") & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
          DesconectaBD
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "PJCAD"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then

          DML_PutAtualizaEtapa Request("w_chave"), Request("w_chave_aux"), w_usuario, Request("w_perc_conclusao"), Request("w_situacao_atual"), _
                               Request("w_exequivel"), null, null
          
          ScriptOpen "JavaScript"
          ' Recupera a sigla do serviço pai, para fazer a chamada ao menu
          ShowHTML "  location.href='" & R & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "PJRECURSO"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then

          DML_PutProjetoRec O, Request("w_chave"), Request("w_chave_aux"), Request("w_nome"), Request("w_tipo"), Request("w_descricao"), Request("w_finalidade")
          
          ScriptOpen "JavaScript"
          ' Recupera a sigla do serviço pai, para fazer a chamada ao menu
          DB_GetLinkData RS, Session("p_cliente"), SG
          ShowHTML "  location.href='" & RS("link") & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
          DesconectaBD
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "ETAPAREC"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then

           ' Inicialmente, desativa a opção em todos os endereços
           DML_PutSolicEtpRec "E", Request("w_chave_aux"), null
           
           ' Em seguida, ativa apenas para os endereços selecionados
           For w_cont = 1 To Request.Form("w_recurso").Count
              If Request("w_recurso")(w_cont) > "" Then
                 DML_PutSolicEtpRec "I", Request("w_chave_aux"), Request("w_recurso")(w_cont)
              End If
           Next

          ScriptOpen "JavaScript"
          ' Recupera a sigla do serviço pai, para fazer a chamada ao menu
          DB_GetLinkData RS, Session("p_cliente"), Request("w_SG")
          ShowHTML "  location.href='" & RS("link") & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & RS("sigla") & "';"
          DesconectaBD
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "PJINTERESS"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then

          DML_PutProjetoInter O, Request("w_chave"), Request("w_chave_aux"), Request("w_tipo_visao"), Request("w_envia_email")
          
          ScriptOpen "JavaScript"
          ' Recupera a sigla do serviço pai, para fazer a chamada ao menu
          DB_GetLinkData RS, Session("p_cliente"), SG
          ShowHTML "  location.href='" & RS("link") & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
          DesconectaBD
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "PJANEXO"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then

          ' Se foi feito o upload de um arquivo  
          If ul.Files("w_caminho").OriginalPath > "" Then  
             ' Verifica se o tamanho das fotos está compatível com  o limite de 100KB.  
             If ul.Files("w_caminho").Size > ul.Form("w_upload_maximo") Then  
                ScriptOpen("JavaScript")  
                ShowHTML "  alert('Atenção: o tamanho máximo do arquivo não pode exceder " & ul.Form("w_upload_maximo")/1024 & " KBytes!');"  
                ShowHTML "  history.back(1);"  
                ScriptClose  
                Response.End()  
                exit sub  
             End If  
    
             ' Se já há um nome para o arquivo, mantém  
             w_file = nvl(ul.Form("w_atual"),ul.GetUniqueName())  
             ul.Files("w_caminho").SaveAs(conFilePhysical & w_cliente & "\" & w_file)  
          Else  
             w_file = ""  
          End If  
    
          ' Se for exclusão e houver um arquivo físico, deve remover o arquivo do disco.  
          If O = "E" and ul.Form("w_atual") > "" Then  
             ul.FileDelete(conFilePhysical & w_cliente & "\" & ul.Form("w_atual"))  
          End If  
    
          DML_PutSolicArquivo O, _  
              w_cliente, ul.Form("w_chave"), ul.Form("w_chave_aux"), ul.Form("w_nome"), ul.Form("w_descricao"), _  
              w_file, ul.Files("w_caminho").Size, ul.Files("w_caminho").ContentType  
          
          ScriptOpen "JavaScript"
          ' Recupera a sigla do serviço pai, para fazer a chamada ao menu 
          DB_GetLinkData RS, Session("p_cliente"), SG 
          ShowHTML "  location.href='" & RS("link") & "&O=L&w_chave=" & ul.Form("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';" 
          DesconectaBD
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If       
    Case "PJAREAS"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then

          DML_PutProjetoAreas O, Request("w_chave"), Request("w_chave_aux"), Request("w_papel")
          
          ScriptOpen "JavaScript"
          ' Recupera a sigla do serviço pai, para fazer a chamada ao menu
          DB_GetLinkData RS, Session("p_cliente"), SG
          ShowHTML "  location.href='" & RS("link") & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
          DesconectaBD
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "PJENVIO"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          If InStr(uCase(Request.ServerVariables("http_content_type")),"MULTIPART/FORM-DATA") > 0 Then 
             ' Se foi feito o upload de um arquivo 
             If ul.Files("w_caminho").OriginalPath > "" Then 
                ' Verifica se o tamanho das fotos está compatível com  o limite de 100KB. 
                If ul.Files("w_caminho").Size > ul.Form("w_upload_maximo") Then 
                   ScriptOpen("JavaScript") 
                   ShowHTML "  alert('Atenção: o tamanho máximo do arquivo não pode exceder " & ul.Form("w_upload_maximo")/1024 & " KBytes!');" 
                   ShowHTML "  history.back(1);" 
                   ScriptClose 
                   Response.End() 
                   exit sub 
                End If 
    
                ' Se já há um nome para o arquivo, mantém 
                w_file = nvl(ul.Form("w_atual"),ul.GetUniqueName()) 
                ul.Files("w_caminho").SaveAs(conFilePhysical & w_cliente & "\" & w_file) 
             Else 
                w_file = "" 
             End If 
    
             DML_PutProjetoEnvio w_menu, ul.Form("w_chave"), w_usuario, ul.Form("w_tramite"), _ 
                 ul.Form("w_novo_tramite"), "N", ul.Form("w_observacao"), ul.Form("w_destinatario"), ul.Form("w_despacho"), _ 
                 w_file, ul.Files("w_caminho").Size, ul.Files("w_caminho").ContentType, ExtractFileName(ul.Files("w_caminho").OriginalPath) 
    
             ScriptOpen "JavaScript" 
             ' Volta para a listagem 
             DB_GetMenuData RS, w_menu 
             ShowHTML "  location.href='" & RS("link") & "&O=L&w_chave=" & ul.Form("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & RemoveTP(TP) & "&SG=" & rs("sigla") & MontaFiltroUpload(ul.Form) & "';" 
             DesconectaBD 
             ScriptClose 
          Else
             DB_GetSolicData RS, Request("w_chave"), "PJGERAL"
             If cDbl(RS("sq_siw_tramite")) <> cDbl(Request("w_tramite")) Then
                ScriptOpen "JavaScript"
                ShowHTML "  alert('ATENÇÃO: Outro usuário já encaminhou este projeto para outra fase de execução!');"
                ScriptClose
             Else
                DML_PutProjetoEnvio Request("w_menu"), Request("w_chave"), w_usuario, Request("w_tramite"), Request("w_novo_tramite"), "N", Request("w_observacao"), Request("w_destinatario"), Request("w_despacho"), null, null, null
                
                ' Envia e-mail comunicando a tramitação
                If Request("w_novo_tramite") > "" Then
                   SolicMail Request("w_chave"),2
                End If
           
                If P1 = 1 Then ' Se for envio da fase de cadastramento, remonta o menu principal
                   ' Recupera os dados para montagem correta do menu
                   DB_GetMenuData RS, w_menu
                   ScriptOpen "JavaScript"
                   ShowHTML "  parent.menu.location='Menu.asp?par=ExibeDocs&O=L&R=" & R & "&SG=" & RS("sigla") & "&TP=" & RemoveTP(RemoveTP(TP)) & MontaFiltro("GET") & "';"
                   ScriptClose
                   DesconectaBD
                Else
                   ' Volta para a listagem
                   DB_GetMenuData RS, w_menu
                   ScriptOpen "JavaScript"
                   ShowHTML "  location.href='" & RS("link") & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & RemoveTP(TP) & "&SG=" & rs("sigla") & MontaFiltro("GET") & "';"
                   ScriptClose
                   DesconectaBD
                End If
             End If
          End If
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "PJCONC"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then

          DB_GetSolicData RS, Request("w_chave"), "PJGERAL"
          If cDbl(RS("sq_siw_tramite")) <> cDbl(Request("w_tramite")) Then
             ScriptOpen "JavaScript"
             ShowHTML "  alert('ATENÇÃO: Outro usuário já encaminhou este projeto para outra fase de execução!');"
             ScriptClose
          Else
             DML_PutProjetoConc Request("w_menu"), Request("w_chave"), w_usuario, Request("w_tramite"), Request("w_inicio_real"), Request("w_fim_real"), Request("w_nota_conclusao"), Request("w_custo_real")
          
             ' Envia e-mail comunicando a conclusão
             SolicMail Request("w_chave"),3

             ScriptOpen "JavaScript"
             ' Volta para a listagem
             DB_GetMenuData RS, w_menu
             ShowHTML "  location.href='" & RS("link") & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & rs("sigla") & MontaFiltro("GET") & "';"
             DesconectaBD
             ScriptClose
          End If
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

  Set w_file                = Nothing
  Set FS                    = Nothing
  Set w_Mensagem            = Nothing
  Set w_chave_nova          = Nothing
  Set p_sq_endereco_unidade = Nothing
  Set p_modulo              = Nothing
  Set w_Null                = Nothing
End Sub

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
    Case "INICIAL"         Inicial
    Case "GERAL"           Geral
    Case "ANEXO"           Anexos  
    Case "ETAPA"           Etapas
    Case "RECURSO"         Recursos
    Case "ETAPARECURSO"    EtapaRecursos
    Case "INTERESS"        Interessados
    Case "AREAS"           Areas
    Case "VISUAL"          Visual
    Case "EXCLUIR"         Excluir
    Case "ENVIO"           Encaminhamento
    Case "ANOTACAO"        Anotar
    Case "CONCLUIR"        Concluir
    Case "ATUALIZAETAPA"   AtualizaEtapa
    Case "GRAVA"           Grava
    Case Else
       Cabecalho
       BodyOpenClean "onLoad=document.focus();"
       ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
       ShowHTML "<HR>"
       ShowHTML "<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src=""images/icone/underc.gif"" align=""center""> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>"
       Rodape
  End Select
End Sub

%>

