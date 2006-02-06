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
<!-- #INCLUDE FILE="VisualTabela.asp" -->
<!-- #INCLUDE FILE="ValidaProjeto.asp" -->
<!-- #INCLUDE FILE="DML_Projeto.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/mod_pd/DB_Viagem.asp" -->
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
REM -------------------------------------------------------------------------
REM Observações: (por Egisberto Vicente da Silva)
REM 
REM Quando a margareth (CESPE) ligar informando algum problema na seleção da UF, tire o comentário 
REM da linha 1129 (selecaoPais) e comente a linha 1087 (<INPUT type=""hidden"" name=""w_pais"")
REM 
REM Como anteriormente alguns projetos permitiam a seleção de país dierente de "Brasil", ocorreram problemas na altera-
REM ção das "UFs".

' Verifica se o usuário está autenticado
If Session("LogOn") <> "Sim" Then
   EncerraSessao
End If

' Declaração de variáveis
Dim dbms, sp, RS, RS1, RS_menu
Dim P1, P2, P3, P4, TP, SG
Dim R, O, w_Cont, w_Reg, w_Pagina, w_Disabled, w_TP, w_classe, w_submenu, w_filtro, w_copia
Dim w_Assinatura
Dim p_ativo, p_solicitante, p_prioridade, p_unidade, p_proponente, p_ordena
Dim p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_projeto, p_atividade
Dim p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc
Dim w_troca,w_cor, w_filter, w_cliente, w_usuario, w_gestor, w_menu
Dim w_sq_pessoa
Dim ul,File
Dim w_dir, w_dir_volta
Set RS  = Server.CreateObject("ADODB.RecordSet")
Set RS1 = Server.CreateObject("ADODB.RecordSet")
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
w_Dir        = "cl_cespe/"
w_dir_volta  = "../"  
w_Disabled   = "ENABLED"

If SG = "PJREPRES" Then
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

w_cliente  = RetornaCliente() ' Retorna o código do cliente para o usuário logado
w_usuario  = RetornaUsuario() ' Retorna o código do usuário logado
w_menu     = RetornaMenu(w_cliente, SG) ' Retorna o código do menu

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
        If p_prioridade  > ""  Then w_filtro = w_filtro & "<tr valign=""top""><TD align=""right"">Prioridade <TD>[<b>" & RetornaPrioridade(p_prioridade) & "</b>]"   End If
        If p_proponente  > ""  Then w_filtro = w_filtro & "<tr valign=""top""><TD align=""right"">Proponente <TD>[<b>" & p_proponente & "</b>]"                      End If
        If p_assunto     > ""  Then w_filtro = w_filtro & "<tr valign=""top""><TD align=""right"">Projeto <TD>[<b>" & p_assunto & "</b>]"                            End If
        If p_palavra     > ""  Then w_filtro = w_filtro & "<tr valign=""top""><TD align=""right"">Palavras-chave <TD>[<b>" & p_palavra & "</b>]"                     End If
        If p_ini_i       > ""  Then w_filtro = w_filtro & "<tr valign=""top""><TD align=""right"">Início <TD>[<b>" & p_ini_i & "-" & p_ini_f & "</b>]"     End If
        If p_fim_i       > ""  Then w_filtro = w_filtro & "<tr valign=""top""><TD align=""right"">Fim <TD>[<b>" & p_fim_i & "-" & p_fim_f & "</b>]"     End If
        If p_atraso      = "S" Then w_filtro = w_filtro & "<tr valign=""top""><TD align=""right"">Situação <TD>[<b>Apenas atrasadas</b>]"                            End If
        If w_filtro > "" Then w_filtro = "<table border=0><tr valign=""top""><TD><b>Filtro:</b><TD nowrap><ul>" & w_filtro & "</ul></tr></table>"                    End If
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
  ShowHTML "<TITLE>" & conSgSistema & " - Listagem de projetos</TITLE>"
  If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & replace(MontaURL("MESA"),w_dir,"") & """>" End If
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
    If P1 = 1 and w_copia = "" Then ' Se for cadastramento e não for resultado de busca para cópia
       If w_submenu > "" Then
          DB_GetLinkSubMenu RS1, w_cliente, Request("SG")
          ShowHTML "<tr><TD>"
          ShowHTML "    <a accesskey=""I"" class=""ss"" href=""" & w_dir & w_Pagina & "Geral&R=" & w_Pagina & par & "&O=I&SG=" & RS1("sigla") & "&w_menu=" & w_menu & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;"
          ShowHTML "    <a accesskey=""C"" class=""ss"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=C&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>C</u>opiar</a>"
       Else
          ShowHTML "<tr><TD><a accesskey=""I"" class=""ss"" href=""" & w_dir & w_Pagina & "Geral&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;"
       End If
    End If
    If Instr(uCase(R),"GR_") = 0 Then
       If w_copia > "" Then ' Se for cópia
          If MontaFiltro("GET") > "" Then
             ShowHTML "                         <a accesskey=""F"" class=""ss"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=C&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a>"
          Else
             ShowHTML "                         <a accesskey=""F"" class=""ss"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=C&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>F</u>iltrar (Inativo)</a>"
          End If
       Else
          If MontaFiltro("GET") > "" Then
             ShowHTML "                         <a accesskey=""F"" class=""ss"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a>"
          Else
             ShowHTML "                         <a accesskey=""F"" class=""ss"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>F</u>iltrar (Inativo)</a>"
          End If
       End If
    End If
    ShowHTML "    <TD align=""right""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><TD align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <TD rowspan=2><b>" & LinkOrdena("Nº","sq_siw_solicitacao") & "</font></td>"
    ShowHTML "          <TD rowspan=2><b>" & LinkOrdena("Responsável","nm_solic") & "</font></td>"
    If not (P1 = 1 or P1 = 2) Then ' Se não for cadastramento nem mesa de trabalho
       ShowHTML "          <TD rowspan=2><b>" & LinkOrdena("Executor","nm_exec") & "</font></td>"
    End If
    ShowHTML "          <TD rowspan=2><b>" & LinkOrdena("Proponente","nm_prop_res") & "</font></td>"
    If P1 = 1 or P1 = 2 Then ' Se for cadastramento ou mesa de trabalho
       ShowHTML "          <TD rowspan=2><b>" & LinkOrdena("Projeto","titulo") & "</font></td>"
       ShowHTML "          <TD colspan=2><b>Execução</font></td>"
    Else
       ShowHTML "          <TD rowspan=2><b>" & LinkOrdena("Projeto","titulo") & "</font></td>"
       ShowHTML "          <TD colspan=2><b>Execução</font></td>"
       If Session("interno") = "S" Then ShowHTML "          <TD rowspan=2><b>" & LinkOrdena("Valor","valor") & "</font></td>" End If
       ShowHTML "          <TD rowspan=2><b>" & LinkOrdena("Fase atual","nm_tramite") & "</font></td>"
    End If
    ShowHTML "          <TD rowspan=2><b>Operações</font></td>"
    ShowHTML "        </tr>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <TD><b>" & LinkOrdena("De","inicio") & "</font></td>"
    ShowHTML "          <TD><b>" & LinkOrdena("Até","fim") & "</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><TD colspan=10 align=""center""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      w_parcial       = 0
      While Not RS.EOF and RS.AbsolutePage = P3
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"" align=""left"">"
        ShowHTML "        <TD nowrap>"
        If RS("concluida") = "N" Then
           If RS("fim") < Date() Then
              ShowHTML "           <img src=""" & conImgAtraso & """ border=0 width=10 height=10 align=""center"">"
           ElseIf RS("aviso_prox_conc") = "S" and (RS("aviso") <= Date()) Then
              ShowHTML "           <img src=""" & conImgAviso & """ border=0 width=10 height=10 align=""center"">"
           Else
              ShowHTML "           <img src=""" & conImgNormal & """ border=0 width=10 height=10 align=""center"">"
           End IF
        Else
           If RS("fim") < Nvl(RS("fim_real"),RS("fim")) Then
              ShowHTML "           <img src=""" & conImgOkAtraso & """ border=0 width=10 height=10 align=""center"">"
           Else
              ShowHTML "           <img src=""" & conImgOkNormal & """ border=0 width=10 height=10 align=""center"">"
           End IF
        End If
        ShowHTML "        <A class=""hl"" HREF=""" & w_dir & w_Pagina & "Visual&R=" & w_Pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exibe as informações deste registro."">" & RS("sq_siw_solicitacao") & "&nbsp;</a>"
        ShowHTML "        <TD>" & ExibePessoa(w_dir_volta, w_cliente, RS("solicitante"), TP, RS("nm_solic")) & "</td>"
        If not (P1 = 1 or P1 = 2) Then ' Se não for cadastramento nem mesa de trabalho
           If Nvl(RS("nm_exec"),"---") > "---" Then
              ShowHTML "        <TD>" & ExibePessoa(w_dir_volta, w_cliente, RS("executor"), TP, RS("nm_exec")) & "</td>"
           Else
              ShowHTML "        <TD>---</td>"
           End IF
        End If
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
        ShowHTML "        <TD align=""center"">&nbsp;" & Nvl(FormataDataEdicao(RS("inicio")),"---") & "</td>"
        ShowHTML "        <TD align=""center"">&nbsp;" & Nvl(FormataDataEdicao(RS("fim")),"---") & "</td>"
        ' Mostra os valor se o usuário for interno e não for cadastramento nem mesa de trabalho
        If P1 <> 1 and P1 <> 2 Then 
           If Session("interno") = "S" Then
              If RS("sg_tramite") = "AT" Then
                 ShowHTML "        <TD align=""right"">" & FormatNumber(RS("custo_real"),2) & "</td>"
                 w_parcial = w_parcial + cDbl(RS("custo_real"))
              Else
                 ShowHTML "        <TD align=""right"">" & FormatNumber(RS("valor"),2) & "</td>"
                 w_parcial = w_parcial + cDbl(RS("valor"))
              End If
           End If
           ShowHTML "        <TD nowrap>" & RS("nm_tramite") & "</td>"
        End If
        ShowHTML "        <TD align=""top"" nowrap>"
        If P1 <> 3 Then ' Se não for acompanhamento
           If w_copia > "" Then ' Se for listagem para cópia
              DB_GetLinkSubMenu RS1, w_cliente, Request("SG")
              ShowHTML "          <a accesskey=""I"" class=""hl"" href=""" & w_dir & w_Pagina & "Geral&R=" & w_Pagina & par & "&O=I&SG=" & RS1("sigla") & "&w_menu=" & w_menu & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&w_copia=" & RS("sq_siw_solicitacao") & MontaFiltro("GET") & """>Copiar</a>&nbsp;"
           ElseIf P1 = 1 Then ' Se for cadastramento
              ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & "Geral&R=" & w_Pagina & par & "&O=A&w_chave=" & RS("sq_siw_solicitacao") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - " & RS("sq_siw_solicitacao") & "&SG=" & SG & MontaFiltro("GET") & """ title=""Altera as informações cadastrais do projeto"">Alterar</A>&nbsp"
              ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & "Excluir&R=" & w_Pagina & par & "&O=E&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - " & RS("sq_siw_solicitacao") & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exclusão do projeto."">Excluir</A>&nbsp"
              ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & "Envio&R=" & w_Pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - " & RS("sq_siw_solicitacao") & "&SG=" & SG & MontaFiltro("GET") & """ title=""Envia o projeto para outra pessoa ou fase."">Enviar</A>&nbsp"
           ElseIf P1 = 2 Then ' Se for execução
              If cDbl(w_usuario) = cDbl(Nvl(RS("executor"),0)) Then
                 ' Coloca as operações dependendo do trâmite
                 If Nvl(RS("sg_tramite"),"00") = "AP" Then
                    ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & "Informar&R=" & w_Pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - " & RS("sq_siw_solicitacao") & "&SG=" & SG & MontaFiltro("GET") & """ title=""Informa dados adicionais sobre o projeto, sem enviá-lo."">Informar</A>&nbsp"
                 End If
                 ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & "Anotacao&R=" & w_Pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - " & RS("sq_siw_solicitacao") & "&SG=" & SG & MontaFiltro("GET") & """ title=""Registra anotações para o projeto, sem enviá-lo."">Anotar</A>&nbsp"
                 ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & "Envio&R=" & w_Pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - " & RS("sq_siw_solicitacao") & "&SG=" & SG & MontaFiltro("GET") & """ title=""Envia o projeto para outro gerente."">Enviar</A>&nbsp"
                 'If Instr("CI,EE", Nvl(RS("sg_tramite"),"00")) = 0 Then
                 '   ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & "Anotacao&R=" & w_Pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - " & RS("sq_siw_solicitacao") & "&SG=" & SG & MontaFiltro("GET") & """ title=""Registra anotações para o projeto, sem enviá-lo."">Anotar</A>&nbsp"
                 '   ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & "Envio&R=" & w_Pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - " & RS("sq_siw_solicitacao") & "&SG=" & SG & MontaFiltro("GET") & """ title=""Envia o projeto para outro gerente."">Enviar</A>&nbsp"
                 If RS("sg_tramite") = "EX" Then
                    ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & "VarigMail&R=" & w_Pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - " & RS("sq_siw_solicitacao") & "&SG=" & SG & MontaFiltro("GET") & """ title=""Envia e-mail para Varig contendo a lista de passageiros."">Varig(E-mail)</A>&nbsp"
                 ElseIf RS("sg_tramite") = "EE" Then
                    ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & "Concluir&R=" & w_Pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - " & RS("sq_siw_solicitacao") & "&SG=" & SG & MontaFiltro("GET") & """ title=""Conclui a execução do projeto."">Concluir</A>&nbsp"
                 End If
              Else
                 ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & "Envio&R=" & w_Pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - " & RS("sq_siw_solicitacao") & "&SG=" & SG & MontaFiltro("GET") & """ title=""Envia o projeto para outro gerente."">Enviar</A>&nbsp"
              End If
           End If
        Else
           If cDbl(Nvl(RS("solicitante"),0)) = cDbl(w_usuario) or _
              cDbl(Nvl(RS("titular"),0))     = cDbl(w_usuario) or _
              cDbl(Nvl(RS("substituto"),0))  = cDbl(w_usuario) _
           Then
              ' Se o usuário for gerente de um projeto ou titular/substituto da unidade responsável, 
              ' pode enviar.
              If cDbl(Nvl(RS("solicitante"),0)) = cDbl(w_usuario) or _
                 cDbl(Nvl(RS("titular"),0))     = cDbl(w_usuario) or _
                 cDbl(Nvl(RS("substituto"),0))  = cDbl(w_usuario) _
              Then
                 ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & "envio&R=" & w_Pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Envia o projeto para outro gerente."">Enviar</A>&nbsp"
              End If
           Else
              ShowHTML "          ---"
           End If
        End If
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend

      ' Mostra os valor se o usuário for interno e não for cadastramento nem mesa de trabalho
      If P1 <> 1 and P1 <> 2 and Session("interno") = "S" Then 
         ' Coloca o valor parcial apenas se a listagem ocupar mais de uma página
         If RS.PageCount > 1 Then
            ShowHTML "        <tr bgcolor=""" & conTrBgColor & """>"
            ShowHTML "          <TD colspan=7 align=""right""><b>Total desta página&nbsp;</font></td>"
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
            ShowHTML "          <TD colspan=7 align=""right""><b>Total da listagem&nbsp;</font></td>"
            ShowHTML "          <TD align=""right""><b>" & FormatNumber(w_total,2) & "&nbsp;</font></td>"
            ShowHTML "          <TD colspan=2>&nbsp;</font></td>"
            ShowHTML "        </tr>"
         End If
      End If
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    ShowHTML "<tr><TD align=""center"" colspan=3>"
    If R > "" Then
       MontaBarra w_dir & w_pagina&par&"&R="&R&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG&"&w_copia="&w_copia, RS.PageCount, P3, P4, RS.RecordCount
    Else
       MontaBarra w_dir & w_pagina&par&"&R="&w_Pagina&par&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG&"&w_copia="&w_copia, RS.PageCount, P3, P4, RS.RecordCount
    End If
    ShowHTML "</tr>"    
    DesConectaBD     
  ElseIf Instr("CP",O) > 0 Then
    If P1 <> 1 Then 
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><TD><div align=""justify"">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ElseIf O = "C" Then ' Se for cópia
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><TD><div align=""justify"">Para selecionar o projeto que deseja copiar, informe nos campos abaixo os critérios de seleção e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    End If
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><TD>"
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><TD valign=""top""><table border=0 width=""90%"" cellspacing=0>"
    AbreForm "Form", w_dir & w_Pagina & par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    If O = "C" Then ' Se for cópia, cria parâmetro para facilitar a recuperação dos registros
       ShowHTML "<INPUT type=""hidden"" name=""w_copia"" value=""OK"">"
    End If
    If P1 <> 1 or O = "C" Then ' Se não for cadastramento ou se for cópia
       ' Recupera dados da opção Projetos
       ShowHTML "      <tr><TD valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
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
       ShowHTML "          <TD valign=""top""><b>Número da <U>d</U>emanda:<br><INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""sti"" type=""text"" name=""p_chave"" size=""18"" maxlength=""18"" value=""" & p_chave & """></td>"
       ShowHTML "          <TD valign=""top""><b>Dias para a data limi<U>t</U>e:<br><INPUT ACCESSKEY=""T"" " & w_Disabled & " class=""sti"" type=""text"" name=""p_prazo"" size=""2"" maxlength=""2"" value=""" & p_prazo & """></td>"
       ShowHTML "      <tr valign=""top"">"
       SelecaoPessoa "Gere<u>n</u>te:", "N", "Selecione o gerente do projeto na relação.", p_solicitante, null, "p_solicitante", "USUARIOS"
       SelecaoUnidade "<U>U</U>nidade responsável:", "U", null, p_unidade, null, "p_unidade", null, null
       ShowHTML "      <tr valign=""top"">"
       SelecaoPessoa "Responsável atua<u>l</u>:", "L", "Selecione o responsável atual pelo projeto na relação.", p_usu_resp, null, "p_usu_resp", "USUARIOS"
       SelecaoUnidade "<U>U</U>nidade atual:", "U", "Selecione a unidade onde o projeto se encontra na relação.", p_uorg_resp, null, "p_uorg_resp", null, null
       ShowHTML "      <tr>"
       SelecaoPais "<u>P</u>aís:", "P", null, p_pais, null, "p_pais", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_regiao'; document.Form.submit();"""
       SelecaoRegiao "<u>R</u>egião:", "R", null, p_regiao, p_pais, "p_regiao", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_uf'; document.Form.submit();"""
       ShowHTML "      <tr>"
       SelecaoEstado "E<u>s</u>tado:", "S", null, p_uf, p_pais, "N", "p_uf", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_cidade'; document.Form.submit();"""
       SelecaoCidade "<u>C</u>idade:", "C", null, p_cidade, p_pais, p_uf, "p_cidade", null, null
       ShowHTML "      <tr>"
       SelecaoPrioridade "<u>P</u>rioridade:", "P", "Informe a prioridade deste projeto.", p_prioridade, null, "p_prioridade", null, null
       ShowHTML "          <TD valign=""top""><b>Propo<U>n</U>ente:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""sti"" type=""text"" name=""p_proponente"" size=""25"" maxlength=""90"" value=""" & p_proponente & """></td>"
       ShowHTML "      <tr>"
       ShowHTML "          <TD valign=""top""><b><U>T</U>ítulo:<br><INPUT ACCESSKEY=""T"" " & w_Disabled & " class=""sti"" type=""text"" name=""p_assunto"" size=""25"" maxlength=""90"" value=""" & p_assunto & """></td>"
       ShowHTML "          <TD valign=""top"" colspan=2><b>Pala<U>v</U>ras-chave:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""sti"" type=""text"" name=""p_palavra"" size=""25"" maxlength=""90"" value=""" & p_palavra & """></td>"
       ShowHTML "      <tr>"
       ShowHTML "          <TD valign=""top""><b>Iní<u>c</u> entre:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""p_ini_i"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_ini_i & """ onKeyDown=""FormataData(this,event);""> e <input " & w_Disabled & " accesskey=""C"" type=""text"" name=""p_ini_f"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_ini_f & """ onKeyDown=""FormataData(this,event);""></td>"
       ShowHTML "          <TD valign=""top""><b>Fi<u>m</u> entre:</b><br><input " & w_Disabled & " accesskey=""F"" type=""text"" name=""p_fim_i"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_fim_i & """ onKeyDown=""FormataData(this,event);""> e <input " & w_Disabled & " accesskey=""T"" type=""text"" name=""p_fim_f"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_fim_f & """ onKeyDown=""FormataData(this,event);""></td>"
       If O <> "C" Then ' Se não for cópia
          ShowHTML "      <tr>"
          ShowHTML "          <TD valign=""top""><b>Exibe somente projetos em atraso?</b><br>"
          If p_atraso = "S" Then
             ShowHTML "              <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_atraso"" value=""S"" checked> Sim <br><input " & w_Disabled & " class=""str"" class=""str"" type=""radio"" name=""p_atraso"" value=""N""> Não"
          Else
             ShowHTML "              <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_atraso"" value=""S""> Sim <br><input " & w_Disabled & " class=""str"" class=""str"" type=""radio"" name=""p_atraso"" value=""N"" checked> Não"
          End If
          SelecaoFaseCheck "Recuperar fases:", "S", null, p_fase, P2, "p_fase", null, null
       End If
    End If
    ShowHTML "      <tr>"
    ShowHTML "          <TD valign=""top""><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY=""O"" " & w_Disabled & " class=""sts"" name=""p_ordena"">"
    If p_Ordena="ASSUNTO" Then
       ShowHTML "          <option value=""assunto"" SELECTED>Projeto<option value=""inicio"">Início<option value="""">Data fim<option value=""nm_tramite"">Fase atual<option value=""prioridade"">Prioridade<option value=""proponente"">Proponente"
    ElseIf p_Ordena="INICIO" Then
       ShowHTML "          <option value=""assunto"">Projeto<option value=""inicio"" SELECTED>Início<option value="""">Data fim<option value=""nm_tramite"">Fase atual<option value=""prioridade"">Prioridade<option value=""proponente"">Proponente"
    ElseIf p_Ordena="NM_TRAMITE" Then
       ShowHTML "          <option value=""assunto"">Projeto<option value=""inicio"">Início<option value="""">Data fim<option value=""nm_tramite"" SELECTED>Fase atual<option value=""prioridade"">Prioridade<option value=""proponente"">Proponente"
    ElseIf p_Ordena="PRIORIDADE" Then
       ShowHTML "          <option value=""assunto"">Projeto<option value=""inicio"">Início<option value="""">Data fim<option value=""nm_tramite"">Fase atual<option value=""prioridade"" SELECTED>Prioridade<option value=""proponente"">Proponente"
    ElseIf p_Ordena="PROPONENTE" Then
       ShowHTML "          <option value=""assunto"">Projeto<option value=""inicio"">Início<option value="""">Data fim<option value=""nm_tramite"">Fase atual<option value=""prioridade"">Prioridade<option value=""proponente"" SELECTED>Proponente"
    Else
       ShowHTML "          <option value=""assunto"">Projeto<option value=""inicio"">Início<option value="""" SELECTED>Data fim<option value=""nm_tramite"">Fase atual<option value=""prioridade"">Prioridade<option value=""proponente"">Proponente"
    End If
    ShowHTML "          </select></td>"
    ShowHTML "          <TD valign=""top""><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""sti"" type=""text"" name=""P4"" size=""4"" maxlength=""4"" value=""" & P4 & """></td></tr>"
    ShowHTML "      <tr><TD align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><TD align=""center"" colspan=""2"">"
    ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
    If O = "C" Then ' Se for cópia
       ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & RemoveTP(TP) & "&SG=" & SG & "';"" name=""Botao"" value=""Abandonar cópia"">"
    Else
       ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"" name=""Botao"" value=""Remover filtro"">"
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
  Dim w_acordo, w_vincula_contrato, w_vincula_viagem, w_sq_tipo_pessoa
  Dim w_cnpj, w_cpf, w_sq_prop, w_sq_prop_atual, w_sq_rep, w_nm_prop
  Dim w_nm_rep, w_nm_prop_res, w_nm_rep_res, w_sexo
  
  Dim w_chave, w_chave_pai, w_chave_aux, w_sq_menu, w_sq_unidade
  Dim w_sq_tramite, w_solicitante, w_cadastrador, w_executor, w_descricao
  Dim w_justificativa, w_inicio, w_fim, w_inclusao, w_ultima_alteracao
  Dim w_conclusao, w_valor, w_opiniao, w_data_hora, w_pais, w_uf, w_cidade, w_palavra_chave
  
  Dim w_troca, i, w_erro, w_como_funciona, w_cor, w_readonly, w_email, w_limite_passagem

  w_chave           = Request("w_chave")
  w_readonly        = ""
  w_erro            = ""
  w_troca           = Request("w_troca")

  ' Verifica se o cliente tem o módulo de acordos contratado
  DB_GetSiwCliModLis RS, w_cliente, null
  RS.Filter = "sigla='AC'"
  If Not RS.EOF Then w_acordo = "S" Else w_acordo = "N" End If
  'DesconectaBD
  
  ' Verifica se há necessidade de recarregar os dados da tela a partir
  ' da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  If w_troca > "" Then ' Se for recarga da página
     If w_troca = "w_nm_prop" Then
        If Request("w_sq_tipo_pessoa") = 1 Then
           ' Recupera os dados do proponente
           DB_GetBenef RS, w_cliente, null, Request("w_cpf"), null, null, 1, null, null
           If RS.RecordCount > 0 Then 
              w_cpf                 = RS("cpf")
              w_sq_prop             = RS("sq_pessoa")
              w_nm_prop             = RS("nm_pessoa")
              w_nm_prop_res         = RS("nome_resumido")
              w_sexo                = RS("sexo")
              w_email               = RS("email")
           Else
              w_cpf                 = Request("w_cpf") 
              w_sq_prop             = ""
              w_nm_prop             = ""
              w_nm_prop_res         = ""
              w_sexo                = ""
              w_email               = ""
           End If
           'DesconectaBD
        Else
           ' Recupera os dados do proponente
           DB_GetBenef RS, w_cliente, null, null, Request("w_cnpj"), null, 2, null, null
           If RS.RecordCount > 0 Then 
              w_cnpj                = RS("cnpj")
              w_sq_prop             = RS("sq_pessoa")
              w_nm_prop             = RS("nm_pessoa")
              w_nm_prop_res         = RS("nome_resumido")
              w_email               = RS("email")
           Else
              w_cnpj                = Request("w_cnpj")
              w_sq_prop             = ""
              w_nm_prop             = ""
              w_nm_prop_res         = ""
              w_email               = ""
           End If
           'DesconectaBD
           w_cpf                 = Request("w_cpf")
           w_sq_rep              = Request("w_sq_rep")
           w_nm_rep              = Request("w_nm_rep")
           w_nm_rep_res          = Request("w_nm_rep_res")
           w_sexo                = Request("w_sexo")
           w_email               = Request("w_email")
        End If
     ElseIf w_troca = "w_nm_rep" Then
        ' Recupera os dados do proponente
        DB_GetBenef RS, w_cliente, null, Request("w_cpf"), null, null, 1, null, null
        If RS.RecordCount > 0 Then 
           w_cpf                 = RS("cpf")
           w_sq_rep              = RS("sq_pessoa")
           w_nm_rep              = RS("nm_pessoa")
           w_nm_rep_res          = RS("nome_resumido")
           w_sexo                = RS("sexo")
           w_email               = RS("email")
        Else
           w_cpf                 = Request("w_cpf")
           w_sq_rep              = ""
           w_nm_rep              = ""
           w_nm_rep_res          = ""
           w_sexo                = ""
           w_email               = ""
        End If
        'DesconectaBD
        w_cnpj                = Request("w_cnpj")
        w_sq_prop             = Request("w_sq_prop")
        w_nm_prop             = Request("w_nm_prop")
        w_nm_prop_res         = Request("w_nm_prop_res")
        w_email               = Request("w_email")
     Else
        w_cpf                 = Request("w_cpf")
        w_cnpj                = Request("w_cnpj")
        w_sq_prop             = Request("w_sq_prop")
        w_nm_prop             = Request("w_nm_prop")
        w_nm_prop_res         = Request("w_nm_prop_res")
        w_sq_rep              = Request("w_sq_rep")
        w_nm_rep              = Request("w_nm_rep")
        w_nm_rep_res          = Request("w_nm_rep_res")
        w_sexo                = Request("w_sexo")
        w_email               = Request("w_email")
     End If

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
     w_sq_tipo_pessoa      = Request("w_sq_tipo_pessoa") 
  
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
     w_email               = Request("w_email")
     w_limite_passagem     = Request("w_limite_passagem")  
  Else
     If InStr("AEV",O) > 0 or w_copia > "" Then
        ' Recupera os dados do projeto
        If w_copia > "" Then
           DB_GetSolicData RS, w_copia, Mid(SG,1,2)&"GERAL"
        Else
           DB_GetSolicData RS, w_chave, Mid(SG,1,2)&"GERAL"
        End If
        If RS.RecordCount > 0 Then
           w_proponente          = RS("proponente")
           w_sq_unidade_resp     = RS("sq_unidade_resp")
           w_titulo              = RS("titulo")
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
           w_sq_tipo_pessoa      = RS("sq_tipo_pessoa")
           w_sq_prop             = RS("outra_parte")
           w_sq_prop_atual       = RS("outra_parte")
  
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
           w_valor               = FormatNumber(Nvl(RS("valor"),0),2)
           w_opiniao             = RS("opiniao")
           w_data_hora           = RS("data_hora")
           w_sqcc                = RS("sq_cc")
           w_pais                = RS("sq_pais")
           w_uf                  = RS("co_uf")
           w_cidade              = RS("sq_cidade_origem")
           w_palavra_chave       = RS("palavra_chave")
           w_limite_passagem     = RS("limite_passagem")
        End If
        'DesconectaBD

        ' Recupera os dados do proponente
        DB_GetBenef RS, w_cliente, Nvl(w_sq_prop,0), null, null, null, null, null, null
        If RS.RecordCount > 0 Then 
           w_cnpj                = RS("cnpj")
           w_cpf                 = RS("cpf")
           w_nm_prop             = RS("nm_pessoa")
           w_nm_prop_res         = RS("nome_resumido")
           w_sexo                = RS("sexo")
           w_email               = RS("email")
        Else
           w_cnpj                = ""
           w_cpf                 = ""
           w_nm_prop             = ""
           w_nm_prop_res         = ""
           w_sexo                = ""
           w_email               = ""
        End If
        'DesconectaBD

        If cDbl(Nvl(w_sq_tipo_pessoa,0)) = 2 Then
           ' Recupera os representantes do acordo pelo proponente
           DB_GetAcordoRep RS, w_chave, w_cliente, null, null
           If RS.RecordCount > 0 Then 
              w_cpf                 = RS("cpf")
              w_sq_rep              = RS("sq_pessoa")
              w_nm_rep              = RS("nm_pessoa")
              w_nm_rep_res          = RS("nome_resumido")
              w_sexo                = RS("sexo")
              w_email               = RS("email")
           Else
              w_cpf                 = ""
              w_sq_rep              = ""
              w_nm_rep              = ""
              w_nm_rep_res          = ""
              w_sexo                = ""
              w_email               = ""
           End If
           'DesconectaBD
        End If

     End If
     
  End If  
  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>" & conSgSistema & " - Listagem de projetos</TITLE>"
  If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & replace(MontaURL("MESA"),w_dir,"") & """>" End If
  Estrutura_CSS w_cliente

  ' Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  ' tratando as particularidades de cada serviço
  ScriptOpen "JavaScript"
  CheckBranco
  FormataData
  FormataDataHora
  FormataValor
  modulo
  FormataCPF
  FormataCNPJ
  ValidateOpen "Validacao"
  If O = "I" or O = "A" Then
     ShowHTML "  if (theForm.Botao.value == ""Troca"") { return true; }"
     Validate "w_titulo", "titulo", "1", 1, 5, 100, "1", "1"
     Validate "w_palavra_chave", "Nº do Pronac", "", "", 2, 20, "1", "1"
     Validate "w_descricao", "Resumo do projeto", "1", 1, 5, 2000, "1", "1"
     'Validate "w_justificativa", "Local do evento", "1", 1, 5, 2000, "1", "1"
     'Validate "w_inicio_real", "Início do evento", "DATA", 1, 10, 10, "", "0123456789/"
     'Validate "w_fim_real", "Fim do evento", "DATA", 1, 10, 10, "", "0123456789/"
     'CompData "w_inicio_real", "Início do evento", "<=", "w_fim_real", "Fim do evento"
     'Validate "w_inicio", "Início do projeto", "DATA", 1, 10, 10, "", "0123456789/"
     'Validate "w_fim", "Fim do projeto", "DATA", 1, 10, 10, "", "0123456789/"
     'CompData "w_inicio", "Início do projeto", "<=", "w_fim", "Fim do projeto"
     'CompData "w_inicio", "Início do projeto", "<=", "w_inicio_real", "Início do evento"
     'CompData "w_fim", "Fim do projeto", ">", "w_fim_real", "Fim do evento"
     Validate "w_sqcc", "Classificação", "SELECT", 1, 1, 18, "", "0123456789"
     'Validate "w_valor", "Orçamento previsto", "VALOR", "1", 4, 18, "", "0123456789.,"
     Validate "w_sq_tipo_pessoa", "Tipo de pessoa", "SELECT", 1, 1, 18, "", "0123456789"
     Validate "w_uf", "UF do proponente", "SELECT", 1, 2, 2, "1", ""
     If cDbl(Nvl(w_sq_tipo_pessoa,0)) = 1 Then ' Se pessoa física
        Validate "w_cpf", "CPF", "CPF", "1", "14", "14", "", "0123456789-."
        Validate "w_nm_prop", "Nome do proponente", "1", 1, 5, 60, "1", "1"
        Validate "w_nm_prop_res", "Nome resumido do proponente", "1", 1, 2, 15, "1", "1"
        Validate "w_sexo", "Sexo", "SELECT", 1, 1, 1, "MF", ""
     ElseIf cDbl(Nvl(w_sq_tipo_pessoa,0)) = 2 Then ' Se pessoa jurídica
        Validate "w_cnpj", "CNPJ", "CNPJ", "1", "18", "18", "", "0123456789/-."
        Validate "w_nm_prop", "Nome do proponente", "1", 1, 5, 60, "1", "1"
        Validate "w_nm_prop_res", "Nome resumido do proponente", "1", 1, 2, 15, "1", "1"
        Validate "w_cpf", "CPF", "CPF", "1", "14", "14", "", "0123456789-."
        Validate "w_nm_rep", "Nome do representante", "1", 1, 5, 60, "1", "1"
        Validate "w_nm_rep_res", "Nome resumido do representante", "1", 1, 2, 15, "1", "1"
        Validate "w_sexo", "Sexo", "SELECT", 1, 1, 1, "MF", ""
     End If
     Validate "w_email", "E-Mail do representante", "1", "1", 4, 60, "1", "1"
     'Validate "w_dias", "Dias de alerta", "1", "", 1, 2, "", "0123456789"
     Validate "w_solicitante", "Gerente", "HIDDEN", 1, 1, 18, "", "0123456789"
     Validate "w_sq_unidade_resp", "Unidade responsável", "HIDDEN", 1, 1, 18, "", "0123456789"
     ShowHTML "  if (theForm.w_vincula_contrato[1].checked == true && theForm.w_vincula_viagem[1].checked == true) {"
     ShowHTML "     alert ('Os campos Exige a vinculação de contratos ? e Permite a vinculação de passagens ? não podem ser, os dois, nulos!');" 
     ShowHTML "     return false;"
     ShowHTML "  }"
     'ShowHTML "  if (theForm.w_aviso[0].checked) {"
     'ShowHTML "     if (theForm.w_dias.value == '') {"
     'ShowHTML "        alert('Informe a partir de quantos dias antes da data fim você deseja ser avisado de sua proximidade!');"
     'ShowHTML "        theForm.w_dias.focus();"
     'ShowHTML "        return false;"
     'ShowHTML "     }"
     'ShowHTML "  }"
     'ShowHTML "  else {"
     'ShowHTML "     theForm.w_dias.value = '';"
     'ShowHTML "  }"
     'ShowHTML "  var w_data, w_data1, w_data2;"
     'ShowHTML "  w_data = theForm.w_inicio.value;"
     'ShowHTML "  w_data = w_data.substr(3,2) + '/' + w_data.substr(0,2) + '/' + w_data.substr(6,4);"
     'ShowHTML "  w_data1  = new Date(Date.parse(w_data));"
     'ShowHTML "  w_data = '" & FormataDataEdicao(Date()) & "';"
     'ShowHTML "  w_data = w_data.substr(3,2) + '/' + w_data.substr(0,2) + '/' + w_data.substr(6,4);"
     'ShowHTML "  w_data2= new Date(Date.parse(w_data));"
     'ShowHTML "  var MinMilli = 1000 * 60;"
     'ShowHTML "  var HrMilli = MinMilli * 60;"
     'ShowHTML "  var DyMilli = HrMilli * 24;"
     'ShowHTML "  var Days = Math.round(Math.abs((w_data1 - w_data2) / DyMilli));"
     'ShowHTML "  if (Days <= 45) theForm.w_prioridade.value = 0; "
     'ShowHTML "  else if (Days <= 60) theForm.w_prioridade.value = 1; "
     'ShowHTML "  else theForm.w_prioridade.value = 2 "
     Validate "w_limite_passagem", "Limite de passagens", "", "1", "1", "18", "", "1"
  End If
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_troca > "" Then
     BodyOpen " onLoad=""javascript:document.Form." & w_troca & ".focus();"""
  ElseIf Instr("EV",O) > 0 Then
     BodyOpen "onLoad='document.focus()';"
  Else
     BodyOpen "onLoad='document.Form.w_titulo.focus()';"
  End If
  ShowHTML "<center>"
  Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  
  ShowHTML "<table align=""center"" border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If Instr("IAEV",O) > 0 Then
    If w_pais = "" Then
       ' Carrega os valores padrão para país, estado e cidade
       DB_GetCustomerData RS, w_cliente
       w_pais   = RS("sq_pais")
       w_uf     = RS("co_uf")
       w_cidade = RS("sq_cidade_padrao")
       'DesconectaBD
    End If
  
    If InStr("EV",O) Then w_Disabled = " DISABLED " End If

    AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"PJCAD",R,O
    ShowHTML MontaFiltro("POST")
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_copia"" value=""" & w_copia &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_data_hora"" value=""" & RS_menu("data_hora") &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & RS_menu("sq_menu") &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_prop"" value=""" & w_sq_prop &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_rep"" value=""" & w_sq_rep &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_prop_atual"" value=""" & w_sq_prop_atual &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_prioridade"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_pais"" value=""" & w_pais &""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><TD>"
    ShowHTML "    <table width=""100%"" border=""0"">"
    ShowHTML "      <tr><TD align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><TD align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><TD valign=""top"" align=""center"" bgcolor=""#D0D0D0""><b>Identificação</td></td></tr>"
    ShowHTML "      <tr><TD align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><TD>Os dados deste bloco serão utilizados para identificação do projeto, bem como para o controle de sua execução.</font></td></tr>"
    ShowHTML "      <tr><TD align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><TD><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
    ShowHTML "          <TD><b><u>T</u>ítulo:</b><br><INPUT ACCESSKEY=""T"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_titulo"" size=""90"" maxlength=""100"" value=""" & w_titulo & """ title=""Informe um título para o projeto.""></td>"
    ShowHTML "          <td><b>Nº do Pro<u>n</u>ac:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_palavra_chave"" size=""20"" maxlength=""20"" value=""" & w_palavra_chave & """ title=""Se existir, informe o número do Pronac relativo a este projeto.""></td>"
    ShowHTML "          </table>"
    ShowHTML "      <tr><TD><b>Re<u>s</u>umo do projeto:</b><br><textarea " & w_Disabled & " accesskey=""S"" name=""w_descricao"" class=""sti"" ROWS=5 cols=75 title=""Informe o resumo deste projeto."">" & w_descricao & "</TEXTAREA></td>"
    'ShowHTML "      <tr><TD><b><u>L</u>ocal do evento:</b><br><textarea " & w_Disabled & " accesskey=""L"" name=""w_justificativa"" class=""sti"" ROWS=5 cols=75 title=""Informe o local ou os locais de realização do evento."">" & w_justificativa & "</TEXTAREA></td>"
    ShowHTML "      <tr>"
    ShowHTML "      <tr><TD><table border=0 width=""100%"" cellspacing=0>"
    'ShowHTML "          <tr valign=""top"">"
    'ShowHTML "              <TD title=""Informe as datas de início e término do evento."">"
    'ShowHTML "                 <b>Período de realização do evento:</b><br>"
    'ShowHTML "                 De <input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_inicio_real"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_inicio_real & """ onKeyDown=""FormataData(this,event);"" title=""Data de início do projeto."">"
    'ShowHTML "                 a <input " & w_Disabled & " accesskey=""M"" type=""text"" name=""w_fim_real"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_fim_real & """ onKeyDown=""FormataData(this,event);"" title=""Data de término do projeto."">"
    'ShowHTML "              <TD title=""Informe o período de execução do projeto, contemplando as atividades administrativas."">"
    'ShowHTML "                 <b>Período do projeto:</b><br>"
    'ShowHTML "                 De <input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_inicio"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & Nvl(w_inicio,FormataDataEdicao(Date())) & """ onKeyDown=""FormataData(this,event);"" title=""Data de início do projeto."">"
    'ShowHTML "                 a <input " & w_Disabled & " accesskey=""M"" type=""text"" name=""w_fim"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_fim & """ onKeyDown=""FormataData(this,event);"" title=""Data de término do projeto."">"
    'ShowHTML "              <TD><b>O<u>r</u>çamento previsto:</b><br><input " & w_Disabled & " accesskey=""O"" type=""text"" name=""w_valor"" class=""sti"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_valor & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o orçamento previsto para execução do projeto, ou zero se não for o caso.""></td>"
    'ShowHTML "          </table>"
    ShowHTML "      <tr>"
    SelecaoCC "C<u>l</u>assificação:", "L", "Selecione um dos itens relacionados.", w_sqcc, null, "w_sqcc", "SIWSOLIC"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><TD align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><TD align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><TD align=""center"" bgcolor=""#D0D0D0""><b>Identificação do proponente</td></td></tr>"
    ShowHTML "      <tr><TD align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><TD>Os dados deste bloco identificam o proponente.</font></td></tr>"
    ShowHTML "      <tr><TD align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><TD><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "        <tr valign=""top"">"
    SelecaoTipoPessoa "Proponent<u>e</u> é pessoa:", "T", "Selecione na lista o tipo de pessoa que será indicada como proponente.", w_sq_tipo_pessoa, w_cliente, "w_sq_tipo_pessoa", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_sq_tipo_pessoa'; document.Form.submit();"""
    'SelecaoPais "<u>P</u>aís do proponente:", "P", null, w_pais, null, "w_pais", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_uf'; document.Form.submit();"""
    SelecaoEstado "<u>U</u>F do proponente:", "U", null, w_uf, w_pais, "N", "w_uf", null, null
    If cDbl(Nvl(w_sq_tipo_pessoa,0)) = 1 Then ' Se pessoa física
       ShowHTML "        <tr valign=""top"">"
       ShowHTML "            <TD><b><u>C</u>PF do proponente:<br><INPUT ACCESSKEY=""C"" TYPE=""text"" class=""sti"" NAME=""w_cpf"" VALUE=""" & w_cpf & """ SIZE=""14"" MaxLength=""14"" onKeyDown=""FormataCPF(this, event);"" onBlur=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_nm_prop'; document.Form.submit();"">"
       ShowHTML "            <TD><b><u>N</u>ome completo:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nm_prop"" class=""sti"" SIZE=""45"" MAXLENGTH=""60"" VALUE=""" & w_nm_prop & """></td>"
       ShowHTML "            <TD><b><u>N</u>ome resumido:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nm_prop_res"" class=""sti"" SIZE=""15"" MAXLENGTH=""15"" VALUE=""" & w_nm_prop_res & """></td>"
       SelecaoSexo "Se<u>x</u>o:", "X", null, w_sexo, null, "w_sexo", null, null
    ElseIf  cDbl(Nvl(w_sq_tipo_pessoa,0)) = 2 Then
       ShowHTML "        <tr valign=""top"">"
       ShowHTML "            <TD><b><u>C</u>NPJ do proponente:<br><INPUT ACCESSKEY=""C"" TYPE=""text"" class=""sti"" NAME=""w_cnpj"" VALUE=""" & w_cnpj & """ SIZE=""18"" MaxLength=""18"" onKeyDown=""FormataCNPJ(this, event);"" onBlur=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_nm_prop'; document.Form.submit();"">"
       ShowHTML "            <TD><b><u>N</u>ome completo:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nm_prop"" class=""sti"" SIZE=""45"" MAXLENGTH=""60"" VALUE=""" & w_nm_prop & """></td>"
       ShowHTML "            <TD><b><u>N</u>ome resumido:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nm_prop_res"" class=""sti"" SIZE=""15"" MAXLENGTH=""15"" VALUE=""" & w_nm_prop_res & """></td>"
       ShowHTML "        <tr valign=""top"">"
       ShowHTML "            <TD><b><u>C</u>PF do representante:<br><INPUT ACCESSKEY=""C"" TYPE=""text"" class=""sti"" NAME=""w_cpf"" VALUE=""" & w_cpf & """ SIZE=""14"" MaxLength=""14"" onKeyDown=""FormataCPF(this, event);"" onBlur=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_nm_rep'; document.Form.submit();"">"
       ShowHTML "            <TD><b><u>N</u>ome completo:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nm_rep"" class=""sti"" SIZE=""45"" MAXLENGTH=""60"" VALUE=""" & w_nm_rep & """></td>"
       ShowHTML "            <TD><b><u>N</u>ome resumido:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nm_rep_res"" class=""sti"" SIZE=""15"" MAXLENGTH=""15"" VALUE=""" & w_nm_rep_res & """></td>"
       SelecaoSexo "Se<u>x</u>o:", "X", null, w_sexo, null, "w_sexo", null, null
    End If
    ShowHTML "          <tr><TD colspan=4 title=""Se o representante informar um e-mail institucional, informe-o neste campo.""><b>e-<u>M</u>ail do representante:</b><br><input " & w_Disabled & " accesskey=""M"" type=""text"" name=""w_email"" class=""sti"" SIZE=""40"" MAXLENGTH=""50"" VALUE=""" & w_email & """></td>"
    ShowHTML "          </table>"
    ShowHTML "      </tr>"
    If RS_menu("descricao") = "S" or RS_menu("justificativa") = "S" or w_acordo = "S" Then
       ShowHTML "      <tr><TD align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><TD align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><TD valign=""top"" align=""center"" bgcolor=""#D0D0D0""><b>Informações adicionais</td></td></tr>"
       ShowHTML "      <tr><TD align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><TD>Os dados deste bloco visam orientar os executores do projeto.</font></td></tr>"
       ShowHTML "      <tr><TD align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><TD><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
       SelecaoPessoa "Gere<u>n</u>te:", "N", "Selecione o gerente do projeto na relação.", w_solicitante, null, "w_solicitante", "Gerente"
       ShowHTML "          <TD colspan=2><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
       SelecaoUnidade "<U>U</U>nidade responsável:", "S", "Selecione a unidade responsável pela execução do projeto", w_sq_unidade_resp, null, "w_sq_unidade_resp", null, null
       ShowHTML "          </table>"
       ShowHTML "          </table>"
       If w_acordo = "S" Then
          ShowHTML "      <tr><TD><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
          MontaRadioSN "<b>Exige a vinculação de contratos?</b>", w_vincula_contrato, "w_vincula_contrato"
          MontaRadioSN "<b>Permite a vinculação de viagens?</b>", w_vincula_viagem, "w_vincula_viagem"
          ShowHTML "      <tr><td><font size=""1""><b><u>L</u>imite de passagens:<br><input " & w_Disabled & " accesskey=""L"" type=""text"" name=""w_limite_passagem"" class=""sti"" SIZE=""5"" MAXLENGTH=""18"" VALUE=""" & w_limite_passagem & """></td>"
          ShowHTML "          </table>"
       End If
    End If
    'ShowHTML "      <tr><TD align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
    'ShowHTML "      <tr><TD align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    'ShowHTML "      <tr><TD valign=""top"" align=""center"" bgcolor=""#D0D0D0""><b>Alerta de atraso</td></td></tr>"
    'ShowHTML "      <tr><TD align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    'ShowHTML "      <tr><TD>Os dados abaixo indicam como deve ser tratada a proximidade da Data fim do projeto.</font></td></tr>"
    'ShowHTML "      <tr><TD align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    'ShowHTML "      <tr><TD><table border=""0"" width=""100%"">"
    'ShowHTML "          <tr valign=""top"">"
    'MontaRadioNS "<b>Emite alerta?</b>", w_aviso, "w_aviso"
    'ShowHTML "              <TD><b>Quantos <U>d</U>ias antes da data limite?<br><INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_dias"" size=""2"" maxlength=""2"" value=""" & w_dias & """ title=""Número de dias para emissão do alerta de proximidade da Data fim do projeto.""></td>"
    ShowHTML "          </table>"
    ShowHTML "      <tr><TD align=""center"" height=""1"" bgcolor=""#000000""></TD></TR>"

    ' Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
    ShowHTML "      <tr><TD align=""center"">"
    ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Gravar"">"
    If O = "I" Then
       DB_GetMenuData RS, w_menu
       ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & R & "&w_copia=" & w_copia & "&O=L&SG=" & RS("sigla") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & MontaFiltro("GET") & "';"" name=""Botao"" value=""Cancelar"">"
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
    Estrutura_Texto_Fecha
    Estrutura_Fecha
  Estrutura_Fecha
  Estrutura_Fecha
  Rodape

  Set w_sexo                = Nothing 
  Set w_cnpj                = Nothing 
  Set w_cpf                 = Nothing 
  Set w_sq_prop             = Nothing 
  Set w_sq_prop_atual       = Nothing 
  Set w_sq_rep              = Nothing 
  Set w_nm_prop             = Nothing 
  Set w_nm_rep              = Nothing 
  Set w_nm_prop_res         = Nothing 
  Set w_nm_rep_res          = Nothing
  Set w_sq_tipo_pessoa      = Nothing
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
REM =========================================================================
REM Fim da rotina de dados gerais
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de cadastramento do proponente
REM -------------------------------------------------------------------------
Sub OutraParte

  Dim w_chave, w_chave_aux, w_sq_pessoa, w_pessoa_atual, w_nome, w_nome_resumido, w_sq_pessoa_pai
  Dim w_sq_tipo_pessoa, w_nm_tipo_pessoa, w_sq_tipo_vinculo, w_nm_tipo_vinculo, w_interno, w_vinculo_ativo
  Dim w_sq_banco, w_sq_agencia, w_operacao, w_nr_conta
  Dim w_sq_pessoa_telefone, w_ddd, w_nr_telefone, w_email
  Dim w_sq_pessoa_fax, w_nr_fax, w_sq_pessoa_celular, w_nr_celular
  Dim w_sq_pessoa_endereco, w_logradouro, w_complemento, w_bairro, w_cep
  Dim w_sq_cidade, w_co_uf, w_sq_pais, w_pd_pais
  Dim w_cpf, w_nascimento, w_rg_numero, w_rg_emissor, w_rg_emissao, w_passaporte_numero
  Dim w_sq_pais_passaporte, w_sexo
  Dim w_cnpj, w_inscricao_estadual
  
  Dim w_troca, i, w_erro, w_como_funciona, w_cor, w_disabled

  If O = "" Then O = "P" End If

  w_erro            = ""
  w_troca           = Request("w_troca")
  w_chave           = Request("w_chave")
  w_chave_aux       = Request("w_chave_aux")
  w_cpf             = Request("w_cpf")
  w_cnpj            = Request("w_cnpj")
  w_sq_pessoa       = Request("w_sq_pessoa")
  w_pessoa_atual    = Request("w_pessoa_atual")

  DB_GetSolicData RS, w_chave, "PJGERAL"
  If w_sq_pessoa = "" and Instr(Request("botao"),"Selecionar") = 0 Then
     If Not RS.EOF Then
        w_sq_pessoa    = RS("outra_parte")
        w_pessoa_atual = RS("outra_parte")
     End If
  End If
  If Not RS.EOF Then
     w_sq_tipo_pessoa = RS("sq_tipo_pessoa")
  End If
  DesconectaBD
  If cDbl(Nvl(w_sq_pessoa,0)) = 0 Then O = "I" Else O = "A" End If
  
  ' Verifica se há necessidade de recarregar os dados da tela a partir
  ' da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  If w_troca > "" Then ' Se for recarga da página
     w_chave                = Request("w_chave")
     w_chave_aux            = Request("w_chave_aux")
     w_nome                 = Request("w_nome")
     w_nome_resumido        = Request("w_nome_resumido")
     w_sq_pessoa_pai        = Request("w_sq_pessoa_pai")
     w_nm_tipo_pessoa       = Request("w_nm_tipo_pessoa")
     w_sq_tipo_vinculo      = Request("w_sq_tipo_vinculo")
     w_nm_tipo_vinculo      = Request("w_nm_tipo_vinculo")
     w_sq_banco             = Request("w_sq_banco")
     w_sq_agencia           = Request("w_sq_agencia")
     w_operacao             = Request("w_operacao")
     w_nr_conta             = Request("w_nr_conta")
     w_interno              = Request("w_interno")
     w_vinculo_ativo        = Request("w_vinculo_ativo")
     w_sq_pessoa_telefone   = Request("w_sq_pessoa_telefone")
     w_ddd                  = Request("w_ddd")
     w_nr_telefone          = Request("w_nr_telefone")
     w_sq_pessoa_celular    = Request("w_sq_pessoa_celular")
     w_nr_celular           = Request("w_nr_celular")
     w_sq_pessoa_fax        = Request("w_sq_pessoa_fax")
     w_nr_fax               = Request("w_nr_fax")
     w_email                = Request("w_email")
     w_sq_pessoa_endereco   = Request("w_sq_pessoa_endereco")
     w_logradouro           = Request("w_logradouro")
     w_complemento          = Request("w_complemento")
     w_bairro               = Request("w_bairro")
     w_cep                  = Request("w_cep")
     w_sq_cidade            = Request("w_sq_cidade")
     w_co_uf                = Request("w_co_uf")
     w_sq_pais              = Request("w_sq_pais")
     w_pd_pais              = Request("w_pd_pais")
     w_cpf                  = Request("w_cpf")
     w_nascimento           = Request("w_nascimento")
     w_rg_numero            = Request("w_rg_numero")
     w_rg_emissor           = Request("w_rg_emissor")
     w_rg_emissao           = Request("w_rg_emissao")
     w_passaporte_numero    = Request("w_passaporte_numero")
     w_sq_pais_passaporte   = Request("w_sq_pais_passaporte")
     w_sexo                 = Request("w_sexo")
     w_cnpj                 = Request("w_cnpj")
     w_inscricao_estadual   = Request("w_inscricao_estadual")
  Else
     If Instr(Request("botao"),"Alterar") = 0 and Instr(Request("botao"),"Procurar") = 0 and (O = "A" or w_sq_pessoa > "" or w_cpf > "" or w_cnpj > "") Then
        ' Recupera os dados do beneficiário em co_pessoa
        DB_GetBenef RS, w_cliente, w_sq_pessoa, w_cpf, w_cnpj, null, w_sq_tipo_pessoa, null, null
        If Not RS.EOF Then
           w_sq_pessoa            = RS("sq_pessoa")
           w_nome                 = RS("nm_pessoa")
           w_nome_resumido        = RS("nome_resumido")
           w_sq_pessoa_pai        = RS("sq_pessoa_pai")
           w_nm_tipo_pessoa       = RS("nm_tipo_pessoa")
           w_sq_tipo_vinculo      = RS("sq_tipo_vinculo")
           w_nm_tipo_vinculo      = RS("nm_tipo_vinculo")
           w_sq_banco             = RS("sq_banco")
           w_sq_agencia           = RS("sq_agencia")
           w_operacao             = RS("operacao")
           w_nr_conta             = RS("nr_conta")
           w_interno              = RS("interno")
           w_vinculo_ativo        = RS("vinculo_ativo")
           w_sq_pessoa_telefone   = RS("sq_pessoa_telefone")
           w_ddd                  = RS("ddd")
           w_nr_telefone          = RS("nr_telefone")
           w_sq_pessoa_celular    = RS("sq_pessoa_celular")
           w_nr_celular           = RS("nr_celular")
           w_sq_pessoa_fax        = RS("sq_pessoa_fax")
           w_nr_fax               = RS("nr_fax")
           w_email                = RS("email")
           w_sq_pessoa_endereco   = RS("sq_pessoa_endereco")
           w_logradouro           = RS("logradouro")
           w_complemento          = RS("complemento")
           w_bairro               = RS("bairro")
           w_cep                  = RS("cep")
           w_sq_cidade            = RS("sq_cidade")
           w_co_uf                = RS("co_uf")
           w_sq_pais              = RS("sq_pais")
           w_pd_pais              = RS("pd_pais")
           w_cpf                  = RS("cpf")
           w_nascimento           = FormataDataEdicao(RS("nascimento"))
           w_rg_numero            = RS("rg_numero")
           w_rg_emissor           = RS("rg_emissor")
           w_rg_emissao           = FormataDataEdicao(RS("rg_emissao"))
           w_passaporte_numero    = RS("passaporte_numero")
           w_sq_pais_passaporte   = RS("sq_pais_passaporte")
           w_sexo                 = RS("sexo")
           w_cnpj                 = RS("cnpj")
           w_inscricao_estadual   = RS("inscricao_estadual")
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
  ScriptOpen "JavaScript"
  Modulo
  FormataCPF
  FormataCNPJ
  FormataCEP
  CheckBranco
  FormataData
  ValidateOpen "Validacao"
  If (w_cpf = "" and w_cnpj = "") or Instr(Request("botao"),"Procurar") > 0 or Instr(Request("botao"),"Alterar") > 0 Then ' Se o beneficiário ainda não foi selecionado
     ShowHTML "  if (theForm.Botao.value == ""Procurar"") {"
     Validate "w_nome", "Nome", "", "1", "4", "20", "1", ""
     ShowHTML "  theForm.Botao.value = ""Procurar"";"
     ShowHTML "}"
     ShowHTML "else {"
     If cDbl(w_sq_tipo_pessoa) = 1 Then
        Validate "w_cpf", "CPF", "CPF", "1", "14", "14", "", "0123456789-."
     Else
        Validate "w_cnpj", "CNPJ", "CNPJ", "1", "18", "18", "", "0123456789/-."
     End If
     ShowHTML "  theForm.w_sq_pessoa.value = '';"
     ShowHTML "}"
  ElseIf O = "I" or O = "A" Then
     ShowHTML "  if (theForm.Botao.value.indexOf('Alterar') >= 0) { return true; }"
     Validate "w_nome", "Nome", "1", 1, 5, 60, "1", "1"
     Validate "w_nome_resumido", "Nome resumido", "1", 1, 2, 15, "1", "1"
     If cDbl(w_sq_tipo_pessoa) = 1 Then
        Validate "w_nascimento", "Data de Nascimento", "DATA", 1, 10, 10, "", 1
        Validate "w_sexo", "Sexo", "SELECT", 1, 1, 1, "MF", ""
        Validate "w_rg_numero", "Identidade", "1", 1, 2, 30, "1", "1"
        Validate "w_rg_emissao", "Data de emissão", "1", 1, 10, 10, "", "0123456789/"
        Validate "w_rg_emissor", "Órgão expedidor", "1", 1, 2, 30, "1", "1"
        Validate "w_passaporte_numero", "Passaporte", "1", "", 1, 20, "1", "1"
        Validate "w_sq_pais_passaporte", "País emissor", "SELECT", "", 1, 10, "1", "1"
     Else
        Validate "w_inscricao_estadual", "Inscrição estadual", "1", "", 2, 20, "1", "1"
     End If
     Validate "w_logradouro", "Logradouro", "1", 1, 4, 60, "1", "1"
     Validate "w_complemento", "Complemento", "1", "", 2, 20, "1", "1"
     Validate "w_bairro", "Bairro", "1", "", 2, 30, "1", "1"
     Validate "w_sq_pais", "País", "SELECT", 1, 1, 10, "1", "1"
     Validate "w_co_uf", "UF", "SELECT", 1, 1, 10, "1", "1"
     Validate "w_sq_cidade", "Cidade", "SELECT", 1, 1, 10, "", "1"
     If Nvl(w_pd_pais,"S") = "S" then
        Validate "w_cep", "CEP", "1", "", 9, 9, "", "0123456789-"
     Else
        Validate "w_cep", "CEP", "1", 1, 5, 9, "", "0123456789"
     End If
     Validate "w_ddd", "DDD", "1", "1", 3, 4, "", "0123456789"
     Validate "w_nr_telefone", "Telefone", "1", 1, 7, 25, "1", "1"
     Validate "w_nr_fax", "Fax", "1", "", 7, 25, "1", "1"
     Validate "w_nr_celular", "Celular", "1", "", 7, 25, "1", "1"
     If cDbl(w_sq_tipo_pessoa) = 1 Then
        Validate "w_email", "E-Mail", "1", "1", 4, 60, "1", "1"
     Else
        Validate "w_email", "E-Mail", "1", "", 4, 60, "1", "1"
     End If
     Validate "w_sq_banco", "Banco", "SELECT", "", 1, 10, "1", "1"
     Validate "w_sq_agencia", "Agencia", "SELECT", "", 1, 10, "1", "1"
     Validate "w_operacao", "Operação", "1", "", 1, 6, "", "0123456789"
     Validate "w_nr_conta", "Número da conta", "1", "", 2, 30, "ZXAzxa", "0123456789-"
     ShowHTML "  if (theForm.w_sq_banco.selectedIndex != 0 || theForm.w_sq_agencia.selectedIndex != 0 || theForm.w_operacao.value != '' || theForm.w_nr_conta.value != '') {"
     ShowHTML "     if (theForm.w_sq_banco.selectedIndex == 0 || theForm.w_sq_agencia.selectedIndex == 0 || theForm.w_nr_conta.value == '') {"
     ShowHTML "        alert('Informe os dados bancários completos ou não informe nenhum campo relativos a eles!');"
     ShowHTML "        return false;"
     ShowHTML "     }"
     ShowHTML "  }"
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
  End If
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If (w_cpf = "" and w_cnpj = "") or Instr(Request("botao"),"Alterar") > 0 or Instr(Request("botao"),"Procurar") > 0 Then ' Se o beneficiário ainda não foi selecionado
     If Instr(Request("botao"),"Procurar") > 0 Then ' Se está sendo feita busca por nome
        BodyOpen "onLoad='document.focus()';"
     Else
        If cDbl(w_sq_tipo_pessoa) = 1 Then
           BodyOpen "onLoad='document.Form.w_cpf.focus()';"
        Else
           BodyOpen "onLoad='document.Form.w_cnpj.focus()';"
        End IF
     End If
  ElseIf w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  Else
     BodyOpen "onLoad='document.Form.w_nome.focus()';"
  End If
  ShowHTML "<center>"
  Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  
  
  ShowHTML "<table align=""center"" border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If Instr("IA",O) > 0 Then
    If (w_cpf = "" and w_cnpj = "") or Instr(Request("botao"),"Alterar") > 0 or Instr(Request("botao"),"Procurar") > 0 Then ' Se o beneficiário ainda não foi selecionado
       ShowHTML "<FORM action=""" & w_dir & w_Pagina & par & """ method=""POST"" name=""Form"" onSubmit=""return(Validacao(this));"">"
    Else
       ShowHTML "<FORM action=""" & w_dir & w_Pagina & "Grava"" method=""POST"" name=""Form"" onSubmit=""return(Validacao(this));"">"
    End If
    ShowHTML "<INPUT type=""hidden"" name=""P1"" value=""" & P1 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P2"" value=""" & P2 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P3"" value=""" & P3 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P4"" value=""" & P4 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""TP"" value=""" & TP & """>"
    ShowHTML "<INPUT type=""hidden"" name=""SG"" value=""" & SG & """>"
    ShowHTML "<INPUT type=""hidden"" name=""R"" value=""" & w_pagina & par & """>"
    ShowHTML "<INPUT type=""hidden"" name=""O"" value=""" & O &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_cliente &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_pessoa"" value=""" & w_sq_pessoa &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_pessoa_atual"" value=""" & w_pessoa_atual &""">"

    If (w_cpf = "" and w_cnpj = "") or InStr(Request("botao"), "Alterar") > 0 or Instr(Request("botao"),"Procurar") > 0 Then
       w_nome = Request("w_nome")
       If InStr(Request("botao"), "Alterar") > 0 Then
          w_cpf  = ""
          w_cnpj = ""
          w_nome = ""
       End If
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><TD>"
       ShowHTML "    <table border=""0"">"
       ShowHTML "        <tr><TD colspan=4>Informe os dados abaixo e clique no botão ""Selecionar"" para continuar.</TD>"
       If cDbl(w_sq_tipo_pessoa) = 1 Then
          ShowHTML "        <tr><TD colspan=4><b><u>C</u>PF:<br><INPUT ACCESSKEY=""C"" TYPE=""text"" class=""sti"" NAME=""w_cpf"" VALUE=""" & w_cpf & """ SIZE=""14"" MaxLength=""14"" onKeyDown=""FormataCPF(this, event);"">"
       Else
          ShowHTML "        <tr><TD colspan=4><b><u>C</u>NPJ:<br><INPUT ACCESSKEY=""C"" TYPE=""text"" class=""sti"" NAME=""w_cnpj"" VALUE=""" & w_cnpj & """ SIZE=""18"" MaxLength=""18"" onKeyDown=""FormataCNPJ(this, event);"">"
       End IF
       ShowHTML "            <INPUT class=""stb"" TYPE=""submit"" NAME=""Botao"" VALUE=""Selecionar"">"
       ShowHTML "        <tr><TD colspan=4><p>&nbsp</p>"
       ShowHTML "        <tr><TD colspan=4 heigth=1 bgcolor=""#000000"">"
       ShowHTML "        <tr><TD colspan=4>"
       ShowHTML "             <b><u>P</u>rocurar pelo nome:</b> (Informe qualquer parte do nome SEM ACENTOS)<br><INPUT ACCESSKEY=""P"" TYPE=""text"" class=""sti"" NAME=""w_nome"" VALUE=""" & w_nome & """ SIZE=""20"" MaxLength=""20"">"
       ShowHTML "              <INPUT class=""stb"" TYPE=""submit"" NAME=""Botao"" VALUE=""Procurar"" onClick=""Botao.value=this.value; document.Form.action='" & w_dir & w_Pagina & par &"'"">"
       ShowHTML "      </table>"
       If w_nome > "" Then
          DB_GetBenef RS, w_cliente, null, null, null, w_nome, w_sq_tipo_pessoa, null, null
          ShowHTML "<tr><TD align=""center"" colspan=3>"
          ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
          ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
          ShowHTML "          <TD><b>Nome</font></td>"
          ShowHTML "          <TD><b>Nome resumido</font></td>"
          If cDbl(w_sq_tipo_pessoa) = 1 Then
             ShowHTML "          <TD><b>CPF</font></td>"
          Else
             ShowHTML "          <TD><b>CNPJ</font></td>"
          End If
          ShowHTML "          <TD><b>Operações</font></td>"
          ShowHTML "        </tr>"
          If RS.EOF Then
              ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><TD colspan=4 align=""center""><font ><b>Não há pessoas que contenham o texto informado.</b></td></tr>"
          Else
            While Not RS.EOF
              ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
              ShowHTML "        <TD><font >" & RS("nm_pessoa") & "</td>"
              ShowHTML "        <TD><font >" & RS("nome_resumido") & "</td>"
              If cDbl(w_sq_tipo_pessoa) = 1 Then
                 ShowHTML "        <TD align=""center""><font >" & Nvl(RS("cpf"),"---") & "</td>"
              Else
                 ShowHTML "        <TD align=""center""><font >" & Nvl(RS("cnpj"),"---") & "</td>"
              End If
              ShowHTML "        <TD nowrap>"
              If cDbl(w_sq_tipo_pessoa) = 1 Then
                 ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_pagina & par & "&R=" & R & "&O=A&w_cpf=" & RS("cpf") & "&w_sq_pessoa=" & RS("sq_pessoa") & "&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Selecionar</A>&nbsp"
              Else
                 ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_pagina & par & "&R=" & R & "&O=A&w_cnpj=" & RS("cnpj") & "&w_sq_pessoa=" & RS("sq_pessoa") & "&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Selecionar</A>&nbsp"
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
          DesConectaBD     
       End If
    Else
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><TD align=""center"">"
       ShowHTML "    <table width=""97%"" border=""0"">"
       ShowHTML "      <tr><TD colspan=""2"" align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><TD colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><TD colspan=""2"" align=""center"" bgcolor=""#D0D0D0""><b>Identificação</td></td></tr>"
       ShowHTML "      <tr><TD colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><TD colspan=""2""><table border=""0"" width=""100%"">"
       ShowHTML "          <tr valign=""top"">"
       If cDbl(w_sq_tipo_pessoa) = 1 Then
          ShowHTML "          <TD>CPF:</font><br><b>" & w_cpf
          ShowHTML "              <INPUT type=""hidden"" name=""w_cpf"" value=""" & w_cpf & """>"
       Else
          ShowHTML "          <TD>CNPJ:</font><br><b>" & w_cnpj
          ShowHTML "              <INPUT type=""hidden"" name=""w_cnpj"" value=""" & w_cnpj & """>"
       End IF
       ShowHTML "          <tr valign=""top"">"
       ShowHTML "             <TD><b><u>N</u>ome completo:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""45"" MAXLENGTH=""60"" VALUE=""" & w_nome & """></td>"
       ShowHTML "             <TD><b><u>N</u>ome resumido:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome_resumido"" class=""sti"" SIZE=""15"" MAXLENGTH=""15"" VALUE=""" & w_nome_resumido & """></td>"
       ShowHTML "          </table>"
       If cDbl(w_sq_tipo_pessoa) = 1 Then
          ShowHTML "      <tr><TD colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
          ShowHTML "          <tr valign=""top"">"
          SelecaoSexo "Se<u>x</u>o:", "X", null, w_sexo, null, "w_sexo", null, null
          ShowHTML "          <TD><b>Da<u>t</u>a de nascimento:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_nascimento"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_nascimento & """ onKeyDown=""FormataData(this,event);""></td>"
          ShowHTML "          <tr valign=""top"">"
          ShowHTML "          <TD><b><u>I</u>dentidade:</b><br><input " & w_Disabled & " accesskey=""I"" type=""text"" name=""w_rg_numero"" class=""sti"" SIZE=""14"" MAXLENGTH=""80"" VALUE=""" & w_rg_numero & """></td>"
          ShowHTML "          <TD><b>Data de <u>e</u>missão:</b><br><input " & w_Disabled & " accesskey=""E"" type=""text"" name=""w_rg_emissao"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_rg_emissao & """ onKeyDown=""FormataData(this,event);""></td>"
          ShowHTML "          <TD><b>Ór<u>g</u>ão emissor:</b><br><input " & w_Disabled & " accesskey=""G"" type=""text"" name=""w_rg_emissor"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & w_rg_emissor & """></td>"
          ShowHTML "          <tr valign=""top"">"
          ShowHTML "          <TD><b>Passapo<u>r</u>te:</b><br><input " & w_Disabled & " accesskey=""R"" type=""text"" name=""w_passaporte_numero"" class=""sti"" SIZE=""15"" MAXLENGTH=""15"" VALUE=""" & w_passaporte_numero & """></td>"
          SelecaoPais "<u>P</u>aís emissor do passaporte:", "P", null, w_sq_pais_passaporte, null, "w_sq_pais_passaporte", null, null
          ShowHTML "          </table>"
       Else
          ShowHTML "      <tr><TD><b><u>I</u>nscrição estadual:</b><br><input " & w_Disabled & " accesskey=""I"" type=""text"" name=""w_inscricao_estadual"" class=""sti"" SIZE=""20"" MAXLENGTH=""20"" VALUE=""" & w_inscricao_estadual & """></td>"
       End If
       ShowHTML "      <tr><TD colspan=""2"" align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><TD colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       If cDbl(w_sq_tipo_pessoa) = 1 Then
          ShowHTML "      <tr><TD colspan=""2"" align=""center"" bgcolor=""#D0D0D0""><b>Endereço comercial, Telefones e e-Mail</td></td></tr>"
       Else
          ShowHTML "      <tr><TD colspan=""2"" align=""center"" bgcolor=""#D0D0D0""><b>Endereço principal, Telefones e e-Mail</td></td></tr>"
       End If
       ShowHTML "      <tr><TD colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><TD colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
       ShowHTML "          <TD><b>En<u>d</u>ereço:</b><br><input " & w_Disabled & " accesskey=""D"" type=""text"" name=""w_logradouro"" class=""sti"" SIZE=""35"" MAXLENGTH=""50"" VALUE=""" & w_logradouro & """></td>"
       ShowHTML "          <TD><b>C<u>o</u>mplemento:</b><br><input " & w_Disabled & " accesskey=""O"" type=""text"" name=""w_complemento"" class=""sti"" SIZE=""20"" MAXLENGTH=""20"" VALUE=""" & w_complemento & """></td>"
       ShowHTML "          <TD><b><u>B</u>airro:</b><br><input " & w_Disabled & " accesskey=""B"" type=""text"" name=""w_bairro"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & w_bairro & """></td>"
       ShowHTML "          <tr valign=""top"">"
       SelecaoPais "<u>P</u>aís:", "P", null, w_sq_pais, null, "w_sq_pais", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_co_uf'; document.Form.submit();"""
       SelecaoEstado "E<u>s</u>tado:", "S", null, w_co_uf, w_sq_pais, "N", "w_co_uf", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_sq_cidade'; document.Form.submit();"""
       SelecaoCidade "<u>C</u>idade:", "C", null, w_sq_cidade, w_sq_pais, w_co_uf, "w_sq_cidade", null, null
       ShowHTML "          </table>"
       If Nvl(w_pd_pais,"S") = "S" then
          ShowHTML "          <tr><TD><b>C<u>E</u>P:</b><br><input " & w_Disabled & " accesskey=""E"" type=""text"" name=""w_cep"" class=""sti"" SIZE=""9"" MAXLENGTH=""9"" VALUE=""" & w_cep & """ onKeyDown=""FormataCEP(this,event);""></td>"
       Else
          ShowHTML "          <tr><TD><b>C<u>E</u>P:</b><br><input " & w_Disabled & " accesskey=""E"" type=""text"" name=""w_cep"" class=""sti"" SIZE=""9"" MAXLENGTH=""9"" VALUE=""" & w_cep & """></td>"
       End IF
       ShowHTML "      <tr><TD colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
       ShowHTML "          <TD><b><u>D</u>DD:</b><br><input " & w_Disabled & " accesskey=""D"" type=""text"" name=""w_ddd"" class=""sti"" SIZE=""4"" MAXLENGTH=""4"" VALUE=""" & w_ddd & """></td>"
       ShowHTML "          <TD><b>Te<u>l</u>efone:</b><br><input " & w_Disabled & " accesskey=""L"" type=""text"" name=""w_nr_telefone"" class=""sti"" SIZE=""20"" MAXLENGTH=""40"" VALUE=""" & w_nr_telefone & """></td>"
       ShowHTML "          <TD title=""Se o proponente informar um número de fax, informe-o neste campo.""><b>Fa<u>x</u>:</b><br><input " & w_Disabled & " accesskey=""X"" type=""text"" name=""w_nr_fax"" class=""sti"" SIZE=""20"" MAXLENGTH=""20"" VALUE=""" & w_nr_fax & """></td>"
       ShowHTML "          <TD title=""Se o proponente informar um celular institucional, informe-o neste campo.""><b>C<u>e</u>lular:</b><br><input " & w_Disabled & " accesskey=""E"" type=""text"" name=""w_nr_celular"" class=""sti"" SIZE=""20"" MAXLENGTH=""20"" VALUE=""" & w_nr_celular & """></td>"
       ShowHTML "          <tr><TD colspan=4 title=""Se o proponente informar um e-mail institucional, informe-o neste campo.""><b>e-<u>M</u>ail:</b><br><input " & w_Disabled & " accesskey=""M"" type=""text"" name=""w_email"" class=""sti"" SIZE=""40"" MAXLENGTH=""50"" VALUE=""" & w_email & """></td>"
       ShowHTML "          </table>"
       ShowHTML "      <tr><TD colspan=""2"" align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><TD colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><TD colspan=""2"" align=""center"" bgcolor=""#D0D0D0""><b>Dados bancários</td></td></tr>"
       ShowHTML "      <tr><TD colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><TD colspan=""2"">Informe os dados bancários do proponente, a serem utilizados para pagamentos feitos a ele.</td></tr>"
       ShowHTML "      <tr><TD colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><TD colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
       ShowHTML "      <tr valign=""top"">"
       SelecaoBanco "<u>B</u>anco:", "B", "Selecione o banco onde deverão ser feitos os pagamentos referentes ao acordo.", w_sq_banco, null, "w_sq_banco", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_sq_agencia'; document.Form.submit();"""
       SelecaoAgencia "A<u>g</u>ência:", "A", "Selecione a agência onde deverão ser feitos os pagamentos referentes ao acordo.", w_sq_agencia, Nvl(w_sq_banco,-1), "w_sq_agencia", null, null
       ShowHTML "      <tr valign=""top"">"
       ShowHTML "          <TD title=""Alguns bancos trabalham com o campo \'Operação\', além do número da conta. A Caixa Econômica Federal é um exemplo. Se for o caso,informe a operação neste campo; caso contrário, deixe-o em branco.""><b>O<u>p</u>eração:</b><br><input " & w_Disabled & " accesskey=""O"" type=""text"" name=""w_operacao"" class=""sti"" SIZE=""6"" MAXLENGTH=""6"" VALUE=""" & w_operacao & """></td>"
       ShowHTML "          <TD title=""Informe o número da conta bancária, colocando o dígito verificador, se existir, separado por um hífen. Exemplo: 11214-3. Se o banco não trabalhar com dígito verificador, informe apenas números. Exemplo: 10845550.""><b>Número da con<u>t</u>a:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_nr_conta"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & w_nr_conta & """></td>"
       ShowHTML "          </table>"
    
       ShowHTML "      <tr><TD align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000""></TD></TR>"
       ShowHTML "      <tr><TD align=""center"" colspan=""3"">"
       ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Gravar"" onClick=""Botao.value=this.value;"">"
       ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Alterar proponente"" onClick=""Botao.value=this.value; document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.submit();"">"
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
    Estrutura_Texto_Fecha
    Estrutura_Fecha
  Estrutura_Fecha
  Estrutura_Fecha
  Rodape

  Set w_chave               = Nothing 
  Set w_chave_aux           = Nothing 
  Set w_sq_pessoa           = Nothing 
  Set w_nome                = Nothing 
  Set w_nome_resumido       = Nothing 
  Set w_sq_pessoa_pai       = Nothing 
  Set w_sq_tipo_pessoa      = Nothing 
  Set w_nm_tipo_pessoa      = Nothing 
  Set w_sq_tipo_vinculo     = Nothing 
  Set w_nm_tipo_vinculo     = Nothing 
  Set w_interno             = Nothing 
  Set w_vinculo_ativo       = Nothing 
  Set w_sq_banco            = Nothing 
  Set w_sq_agencia          = Nothing 
  Set w_operacao            = Nothing 
  Set w_nr_conta            = Nothing 
  Set w_sq_pessoa_telefone  = Nothing 
  Set w_ddd                 = Nothing 
  Set w_nr_telefone         = Nothing 
  Set w_email               = Nothing 
  Set w_sq_pessoa_endereco  = Nothing 
  Set w_logradouro          = Nothing 
  Set w_complemento         = Nothing 
  Set w_bairro              = Nothing 
  Set w_cep                 = Nothing 
  Set w_sq_cidade           = Nothing 
  Set w_co_uf               = Nothing 
  Set w_sq_pais             = Nothing 
  Set w_pd_pais             = Nothing 
  Set w_cpf                 = Nothing 
  Set w_nascimento          = Nothing 
  Set w_rg_numero           = Nothing 
  Set w_rg_emissor          = Nothing 
  Set w_rg_emissao          = Nothing 
  Set w_passaporte_numero   = Nothing 
  Set w_sq_pais_passaporte  = Nothing 
  Set w_sexo                = Nothing 
  Set w_cnpj                = Nothing 
  Set w_inscricao_estadual  = Nothing 
  Set w_troca               = Nothing 
  Set i                     = Nothing 
  Set w_erro                = Nothing 
  Set w_como_funciona       = Nothing 
  Set w_cor                 = Nothing 
  Set w_disabled            = Nothing
End Sub
REM =========================================================================
REM Fim da tela de proponente
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de cadastramento da preposto
REM -------------------------------------------------------------------------
Sub Preposto

  Dim w_chave, w_chave_aux, w_sq_pessoa, w_nome, w_nome_resumido, w_sq_pessoa_pai
  Dim w_sq_tipo_pessoa, w_nm_tipo_pessoa, w_sq_tipo_vinculo, w_nm_tipo_vinculo, w_interno, w_vinculo_ativo
  Dim w_cpf, w_rg_numero, w_rg_emissor, w_rg_emissao, w_sexo
  
  Dim w_troca, i, w_erro, w_como_funciona, w_cor, w_disabled

  If O = "" Then O = "P" End If

  w_erro            = ""
  w_troca           = Request("w_troca")
  w_chave           = Request("w_chave")
  w_chave_aux       = Request("w_chave_aux")
  w_cpf             = Request("w_cpf")
  w_sq_pessoa       = Request("w_sq_pessoa")
  
  DB_GetSolicData RS, w_chave, "PJGERAL"
  If w_sq_pessoa = "" and Instr(Request("botao"),"Selecionar") = 0 Then
     If Not RS.EOF Then
        w_sq_pessoa = RS("preposto")
     End If
  End If
  If Not RS.EOF Then
     w_sq_tipo_pessoa = RS("sq_tipo_pessoa")
  End If
  DesconectaBD
  If cDbl(Nvl(w_sq_pessoa,0)) = 0 Then O = "I" Else O = "A" End If
  
  ' Se acordo com pessoa física, não permite a inclusão dos dados do preposto
  If cDbl(w_sq_tipo_pessoa) = 1 Then
     Cabecalho
     ShowHTML "<HEAD>"
     ShowHTML "<TITLE>" & conSgSistema & " - Listagem de projetos</TITLE>"
     If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & replace(MontaURL("MESA"),w_dir,"") & """>" End If
     Estrutura_CSS w_cliente
     ShowHTML "   Projetos cujo proponente seja pessoa física não permitem a indicação do preposto."
     ShowHTML "</td></tr>"
     ShowHTML "</table>"
     Rodape
     Response.End()
     Exit Sub
  End If
  
  ' Verifica se há necessidade de recarregar os dados da tela a partir
  ' da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  If w_troca > "" Then ' Se for recarga da página
     w_chave                = Request("w_chave")
     w_chave_aux            = Request("w_chave_aux")
     w_nome                 = Request("w_nome")
     w_nome_resumido        = Request("w_nome_resumido")
     w_sexo                 = Request("w_sexo")
     w_sq_pessoa_pai        = Request("w_sq_pessoa_pai")
     w_nm_tipo_pessoa       = Request("w_nm_tipo_pessoa")
     w_sq_tipo_vinculo      = Request("w_sq_tipo_vinculo")
     w_nm_tipo_vinculo      = Request("w_nm_tipo_vinculo")
     w_interno              = Request("w_interno")
     w_vinculo_ativo        = Request("w_vinculo_ativo")
     w_rg_numero            = Request("w_rg_numero")
     w_rg_emissor           = Request("w_rg_emissor")
     w_rg_emissao           = Request("w_rg_emissao")
  Else
     If Instr(Request("botao"),"Alterar") = 0 and Instr(Request("botao"),"Procurar") = 0 and (O = "A" or w_sq_pessoa > "" or w_cpf > "") Then
        ' Recupera os dados do beneficiário em co_pessoa
        DB_GetBenef RS, w_cliente, w_sq_pessoa, w_cpf, null, null, null, null, null
        If Not RS.EOF Then
           w_sq_pessoa            = RS("sq_pessoa")
           w_nome                 = RS("nm_pessoa")
           w_nome_resumido        = RS("nome_resumido")
           w_sexo                 = RS("sexo")
           w_sq_pessoa_pai        = RS("sq_pessoa_pai")
           w_nm_tipo_pessoa       = RS("nm_tipo_pessoa")
           w_sq_tipo_vinculo      = RS("sq_tipo_vinculo")
           w_nm_tipo_vinculo      = RS("nm_tipo_vinculo")
           w_interno              = RS("interno")
           w_vinculo_ativo        = RS("vinculo_ativo")
           w_cpf                  = RS("cpf")
           w_rg_numero            = RS("rg_numero")
           w_rg_emissor           = RS("rg_emissor")
           w_rg_emissao           = FormataDataEdicao(RS("rg_emissao"))
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
  ScriptOpen "JavaScript"
  Modulo
  FormataCPF
  CheckBranco
  FormataData
  ValidateOpen "Validacao"
  If w_cpf = "" or Instr(Request("botao"),"Procurar") > 0 or Instr(Request("botao"),"Alterar") > 0 Then ' Se o beneficiário ainda não foi selecionado
     ShowHTML "  if (theForm.Botao.value == ""Procurar"") {"
     Validate "w_nome", "Nome", "", "1", "4", "20", "1", ""
     ShowHTML "  theForm.Botao.value = ""Procurar"";"
     ShowHTML "}"
     ShowHTML "else {"
     Validate "w_cpf", "CPF", "CPF", "1", "14", "14", "", "0123456789-."
     ShowHTML "  theForm.w_sq_pessoa.value = '';"
     ShowHTML "}"
  ElseIf O = "I" or O = "A" Then
     ShowHTML "  if (theForm.Botao.value.indexOf('Alterar') >= 0) { return true; }"
     Validate "w_nome", "Nome", "1", 1, 5, 60, "1", "1"
     Validate "w_nome_resumido", "Nome resumido", "1", 1, 2, 15, "1", "1"
     Validate "w_sexo", "Sexo", "SELECT", 1, 1, 1, "MF", ""
     Validate "w_rg_numero", "Identidade", "1", 1, 2, 30, "1", "1"
     Validate "w_rg_emissao", "Data de emissão", "", "", 10, 10, "", "0123456789/"
     Validate "w_rg_emissor", "Órgão expedidor", "1", 1, 2, 30, "1", "1"
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
  End If
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_cpf = "" or Instr(Request("botao"),"Alterar") > 0 or Instr(Request("botao"),"Procurar") > 0 Then ' Se o beneficiário ainda não foi selecionado
     If Instr(Request("botao"),"Procurar") > 0 Then ' Se está sendo feita busca por nome
        BodyOpen "onLoad='document.focus()';"
     Else
        BodyOpen "onLoad='document.Form.w_cpf.focus()';"
     End If
  ElseIf w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  Else
     BodyOpen "onLoad='document.Form.w_nome.focus()';"
  End If
  ShowHTML "<center>"
  Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  
  
  ShowHTML "<table align=""center"" border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If Instr("IA",O) > 0 Then
    If w_cpf = "" or Instr(Request("botao"),"Alterar") > 0 or Instr(Request("botao"),"Procurar") > 0 Then ' Se o beneficiário ainda não foi selecionado
       ShowHTML "<FORM action=""" & w_dir & w_Pagina & par & """ method=""POST"" name=""Form"" onSubmit=""return(Validacao(this));"">"
    Else
       ShowHTML "<FORM action=""" & w_dir & w_Pagina & "Grava"" method=""POST"" name=""Form"" onSubmit=""return(Validacao(this));"">"
    End If
    ShowHTML "<INPUT type=""hidden"" name=""P1"" value=""" & P1 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P2"" value=""" & P2 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P3"" value=""" & P3 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P4"" value=""" & P4 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""TP"" value=""" & TP & """>"
    ShowHTML "<INPUT type=""hidden"" name=""SG"" value=""" & SG & """>"
    ShowHTML "<INPUT type=""hidden"" name=""R"" value=""" & w_pagina & par & """>"
    ShowHTML "<INPUT type=""hidden"" name=""O"" value=""" & O &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_cliente &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_pessoa"" value=""" & w_sq_pessoa &""">"

    If w_cpf = "" or InStr(Request("botao"), "Alterar") > 0 or Instr(Request("botao"),"Procurar") > 0 Then
       w_nome = Request("w_nome")
       If InStr(Request("botao"), "Alterar") > 0 Then
          w_cpf  = ""
          w_nome = ""
       End If
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><TD>"
       ShowHTML "    <table border=""0"">"
       ShowHTML "        <tr><TD colspan=4>Informe os dados abaixo e clique no botão ""Selecionar"" para continuar.</TD>"
       ShowHTML "        <tr><TD colspan=4><b><u>C</u>PF:<br><INPUT ACCESSKEY=""C"" TYPE=""text"" class=""sti"" NAME=""w_cpf"" VALUE=""" & w_cpf & """ SIZE=""14"" MaxLength=""14"" onKeyDown=""FormataCPF(this, event);"">"
       ShowHTML "            <INPUT class=""stb"" TYPE=""submit"" NAME=""Botao"" VALUE=""Selecionar"">"
       ShowHTML "        <tr><TD colspan=4><p>&nbsp</p>"
       ShowHTML "        <tr><TD colspan=4 heigth=1 bgcolor=""#000000"">"
       ShowHTML "        <tr><TD colspan=4>"
       ShowHTML "             <b><u>P</u>rocurar pelo nome:</b> (Informe qualquer parte do nome SEM ACENTOS)<br><INPUT ACCESSKEY=""P"" TYPE=""text"" class=""sti"" NAME=""w_nome"" VALUE=""" & w_nome & """ SIZE=""20"" MaxLength=""20"">"
       ShowHTML "              <INPUT class=""stb"" TYPE=""submit"" NAME=""Botao"" VALUE=""Procurar"" onClick=""Botao.value=this.value; document.Form.action='" & w_dir & w_Pagina & par &"'"">"
       ShowHTML "      </table>"
       If w_nome > "" Then
          DB_GetBenef RS, w_cliente, null, null, null, w_nome, 1, null, null ' Recupera apenas pessoas físicas
          ShowHTML "<tr><TD align=""center"" colspan=3>"
          ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
          ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
          ShowHTML "          <TD><b>Nome</font></td>"
          ShowHTML "          <TD><b>Nome resumido</font></td>"
          ShowHTML "          <TD><b>CPF</font></td>"
          ShowHTML "          <TD><b>Operações</font></td>"
          ShowHTML "        </tr>"
          If RS.EOF Then
              ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><TD colspan=4 align=""center""><font ><b>Não há pessoas que contenham o texto informado.</b></td></tr>"
          Else
            While Not RS.EOF
              ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
              ShowHTML "        <TD><font >" & RS("nm_pessoa") & "</td>"
              ShowHTML "        <TD><font >" & RS("nome_resumido") & "</td>"
              ShowHTML "        <TD align=""center""><font >" & Nvl(RS("cpf"),"---") & "</td>"
              ShowHTML "        <TD nowrap>"
              ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_pagina & par & "&R=" & R & "&O=A&w_cpf=" & RS("cpf") & "&w_sq_pessoa=" & RS("sq_pessoa") & "&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Selecionar</A>&nbsp"
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
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><TD>"
       ShowHTML "    <table width=""97%"" border=""0"">"
       ShowHTML "      <tr><TD colspan=""2"" align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><TD colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><TD colspan=""2"" align=""center"" bgcolor=""#D0D0D0""><b>Identificação</td></td></tr>"
       ShowHTML "      <tr><TD colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><TD colspan=""2""><table border=""0"" width=""100%"">"
       ShowHTML "          <tr valign=""top"">"
       ShowHTML "          <TD>CPF:</font><br><b>" & w_cpf
       ShowHTML "              <INPUT type=""hidden"" name=""w_cpf"" value=""" & w_cpf & """>"
       ShowHTML "          <tr valign=""top"">"
       ShowHTML "             <TD><b><u>N</u>ome completo:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""40"" MAXLENGTH=""60"" VALUE=""" & w_nome & """></td>"
       ShowHTML "             <TD><b><u>N</u>ome resumido:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome_resumido"" class=""sti"" SIZE=""15"" MAXLENGTH=""15"" VALUE=""" & w_nome_resumido & """></td>"
       SelecaoSexo "Se<u>x</u>o:", "X", null, w_sexo, null, "w_sexo", null, null
       ShowHTML "          <tr valign=""top"">"
       ShowHTML "          <TD><b><u>I</u>dentidade:</b><br><input " & w_Disabled & " accesskey=""I"" type=""text"" name=""w_rg_numero"" class=""sti"" SIZE=""14"" MAXLENGTH=""80"" VALUE=""" & w_rg_numero & """></td>"
       ShowHTML "          <TD><b>Data de <u>e</u>missão:</b><br><input " & w_Disabled & " accesskey=""E"" type=""text"" name=""w_rg_emissao"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_rg_emissao & """ onKeyDown=""FormataData(this,event);""></td>"
       ShowHTML "          <TD><b>Ór<u>g</u>ão emissor:</b><br><input " & w_Disabled & " accesskey=""G"" type=""text"" name=""w_rg_emissor"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & w_rg_emissor & """></td>"
       ShowHTML "          </table>"
    
       ShowHTML "      <tr><TD align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000""></TD></TR>"
       ShowHTML "      <tr><TD align=""center"" colspan=""3"">"
       ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Gravar"" onClick=""Botao.value=this.value;"">"
       ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Alterar preposto"" onClick=""Botao.value=this.value; document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.submit();"">"
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
    Estrutura_Texto_Fecha
    Estrutura_Fecha
  Estrutura_Fecha
  Estrutura_Fecha
  Rodape

  Set w_chave               = Nothing 
  Set w_chave_aux           = Nothing 
  Set w_sq_pessoa           = Nothing 
  Set w_nome                = Nothing 
  Set w_nome_resumido       = Nothing 
  Set w_sq_pessoa_pai       = Nothing 
  Set w_sq_tipo_pessoa      = Nothing 
  Set w_nm_tipo_pessoa      = Nothing 
  Set w_sq_tipo_vinculo     = Nothing 
  Set w_nm_tipo_vinculo     = Nothing 
  Set w_interno             = Nothing 
  Set w_vinculo_ativo       = Nothing 
  Set w_cpf                 = Nothing 
  Set w_rg_numero           = Nothing 
  Set w_rg_emissor          = Nothing 
  Set w_rg_emissao          = Nothing 
  Set w_sexo                = Nothing 
  Set w_troca               = Nothing 
  Set i                     = Nothing 
  Set w_erro                = Nothing 
  Set w_como_funciona       = Nothing 
  Set w_cor                 = Nothing 
  Set w_disabled            = Nothing
End Sub
REM =========================================================================
REM Fim da tela de preposto
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de cadastramento de representantes
REM -------------------------------------------------------------------------
Sub Representante

  Dim w_chave, w_chave_aux, w_sq_pessoa, w_nome, w_nome_resumido, w_sq_pessoa_pai
  Dim w_sq_tipo_pessoa, w_nm_tipo_pessoa, w_sq_tipo_vinculo, w_nm_tipo_vinculo, w_interno, w_vinculo_ativo
  Dim w_cpf, w_rg_numero, w_rg_emissor, w_rg_emissao, w_passaporte_numero
  Dim w_sq_pessoa_telefone, w_ddd, w_nr_telefone, w_email
  Dim w_sq_pessoa_fax, w_nr_fax, w_sq_pessoa_celular, w_nr_celular
  Dim w_sexo
  
  Dim w_troca, i, w_erro, w_como_funciona, w_cor, w_disabled

  If O = "" Then O = "L" End If

  w_erro            = ""
  w_troca           = Request("w_troca")
  w_chave           = Request("w_chave")
  w_chave_aux       = Request("w_chave_aux")
  w_cpf             = Request("w_cpf")
  w_sq_pessoa       = Request("w_sq_pessoa")
  
  DB_GetSolicData RS, w_chave, "PJGERAL"
  If Not RS.EOF Then
     w_sq_tipo_pessoa = RS("sq_tipo_pessoa")
  End If
  DesconectaBD

  ' Se acordo com pessoa física, não permite a inclusão dos dados do preposto
  If cDbl(w_sq_tipo_pessoa) = 1 Then
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     BodyOpen "onLoad='document.focus()';"
     ShowHTML "<center>"
     Estrutura_Topo_Limpo
     Estrutura_Menu
     Estrutura_Corpo_Abre
     Estrutura_Texto_Abre
  
     
     ShowHTML "<table align=""center"" border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
     ShowHTML "<tr bgcolor=""" & conTrBgColor & """><TD align=""center""><font size=2 color=""red"">"
     ShowHTML "   Projetos cujo proponente seja pessoa física não permitem a indicação de representantes."
     ShowHTML "</td></tr>"
     ShowHTML "</table>"
     Rodape
     Response.End()
     Exit Sub
  End If
  
  ' Verifica se há necessidade de recarregar os dados da tela a partir
  ' da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  If w_troca > "" Then ' Se for recarga da página
     w_chave                = Request("w_chave")
     w_chave_aux            = Request("w_chave_aux")
     w_nome                 = Request("w_nome")
     w_nome_resumido        = Request("w_nome_resumido")
     w_sexo                 = Request("w_sexo")
     w_sq_pessoa_pai        = Request("w_sq_pessoa_pai")
     w_nm_tipo_pessoa       = Request("w_nm_tipo_pessoa")
     w_sq_tipo_vinculo      = Request("w_sq_tipo_vinculo")
     w_nm_tipo_vinculo      = Request("w_nm_tipo_vinculo")
     w_interno              = Request("w_interno")
     w_vinculo_ativo        = Request("w_vinculo_ativo")
     w_rg_numero            = Request("w_rg_numero")
     w_rg_emissor           = Request("w_rg_emissor")
     w_rg_emissao           = Request("w_rg_emissao")
     w_sq_pessoa_telefone   = Request("w_sq_pessoa_telefone")
     w_ddd                  = Request("w_ddd")
     w_nr_telefone          = Request("w_nr_telefone")
     w_sq_pessoa_celular    = Request("w_sq_pessoa_celular")
     w_nr_celular           = Request("w_nr_celular")
     w_sq_pessoa_fax        = Request("w_sq_pessoa_fax")
     w_nr_fax               = Request("w_nr_fax")
     w_email                = Request("w_email")
  Else
     If O = "L" Then
        ' Recupera os representantes do acordo pelo proponente
        DB_GetAcordoRep RS, w_chave, w_cliente, null, null
        RS.Sort = "nm_pessoa"
     ElseIf Instr(Request("botao"),"Alterar") = 0 and Instr(Request("botao"),"Procurar") = 0 and (O = "A" or w_sq_pessoa > "" or w_cpf > "") Then
        ' Recupera os dados do beneficiário em co_pessoa
        DB_GetBenef RS, w_cliente, w_sq_pessoa, w_cpf, null, null, null, null, null
        If Not RS.EOF Then
           w_sq_pessoa            = RS("sq_pessoa")
           w_nome                 = RS("nm_pessoa")
           w_nome_resumido        = RS("nome_resumido")
           w_sexo                 = RS("sexo")
           w_sq_pessoa_pai        = RS("sq_pessoa_pai")
           w_nm_tipo_pessoa       = RS("nm_tipo_pessoa")
           w_sq_tipo_vinculo      = RS("sq_tipo_vinculo")
           w_nm_tipo_vinculo      = RS("nm_tipo_vinculo")
           w_interno              = RS("interno")
           w_vinculo_ativo        = RS("vinculo_ativo")
           w_cpf                  = RS("cpf")
           w_rg_numero            = RS("rg_numero")
           w_rg_emissor           = RS("rg_emissor")
           w_rg_emissao           = FormataDataEdicao(RS("rg_emissao"))
           w_sq_pessoa_telefone   = RS("sq_pessoa_telefone")
           w_ddd                  = RS("ddd")
           w_nr_telefone          = RS("nr_telefone")
           w_sq_pessoa_celular    = RS("sq_pessoa_celular")
           w_nr_celular           = RS("nr_celular")
           w_sq_pessoa_fax        = RS("sq_pessoa_fax")
           w_nr_fax               = RS("nr_fax")
           w_email                = RS("email")
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
     ValidateOpen "Validacao"
     If w_cpf = "" or Instr(Request("botao"),"Procurar") > 0 or Instr(Request("botao"),"Alterar") > 0 Then ' Se o beneficiário ainda não foi selecionado
        ShowHTML "  if (theForm.Botao.value == ""Procurar"") {"
        Validate "w_nome", "Nome", "", "1", "4", "20", "1", ""
        ShowHTML "  theForm.Botao.value = ""Procurar"";"
        ShowHTML "}"
        ShowHTML "else {"
        Validate "w_cpf", "CPF", "CPF", "1", "14", "14", "", "0123456789-."
        ShowHTML "  theForm.w_sq_pessoa.value = '';"
        ShowHTML "}"
     ElseIf O = "I" or O = "A" Then
        Validate "w_nome", "Nome", "1", 1, 5, 60, "1", "1"
        Validate "w_nome_resumido", "Nome resumido", "1", 1, 2, 15, "1", "1"
        Validate "w_sexo", "Sexo", "SELECT", 1, 1, 1, "MF", ""
        Validate "w_rg_numero", "Identidade", "1", 1, 2, 30, "1", "1"
        Validate "w_rg_emissao", "Data de emissão", "", "", 10, 10, "", "0123456789/"
        Validate "w_rg_emissor", "Órgão expedidor", "1", 1, 2, 30, "1", "1"
        Validate "w_ddd", "DDD", "1", "1", 3, 4, "", "0123456789"
        Validate "w_nr_telefone", "Telefone", "1", 1, 7, 25, "1", "1"
        Validate "w_nr_fax", "Fax", "1", "", 7, 25, "1", "1"
        Validate "w_nr_celular", "Celular", "1", "", 7, 25, "1", "1"
        Validate "w_email", "E-Mail", "1", "1", 4, 60, "1", "1"
        ShowHTML "  theForm.Botao[0].disabled=true;"
        ShowHTML "  theForm.Botao[1].disabled=true;"
     End If
     ValidateClose
     ScriptClose
     ShowHTML "</HEAD>"
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  End If
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If InStr("IA",O) > 0 and (w_cpf = "" or Instr(Request("botao"),"Alterar") > 0 or Instr(Request("botao"),"Procurar") > 0) Then ' Se o beneficiário ainda não foi selecionado
     If Instr(Request("botao"),"Procurar") > 0 Then ' Se está sendo feita busca por nome
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
  ShowHTML "<center>"
  Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  
  
  ShowHTML "<table align=""center"" border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "<tr><TD><a accesskey=""I"" class=""ss"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <TD align=""right""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><TD align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <TD><b>CPF</font></td>"
    ShowHTML "          <TD><b>Nome</font></td>"
    ShowHTML "          <TD><b>DDD</font></td>"
    ShowHTML "          <TD><b>Telefone</font></td>"
    ShowHTML "          <TD><b>Fax</font></td>"
    ShowHTML "          <TD><b>Celular</font></td>"
    ShowHTML "          <TD><b>e-Mail</font></td>"
    ShowHTML "          <TD><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><TD colspan=8 align=""center""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <TD align=""center"">" & RS("cpf") & "</td>"
        ShowHTML "        <TD>" & RS("nome_resumido") & "</td>"
        ShowHTML "        <TD align=""center"">" & Nvl(RS("ddd"),"---") & "</td>"
        ShowHTML "        <TD>" & Nvl(RS("nr_telefone"),"---") & "</td>"
        ShowHTML "        <TD>" & Nvl(RS("nr_fax"),"---") & "</td>"
        ShowHTML "        <TD>" & Nvl(RS("nr_celular"),"---") & "</td>"
        ShowHTML "        <TD>" & Nvl(RS("email"),"---") & "</td>"
        ShowHTML "        <TD align=""top"" nowrap>"
        ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & w_chave & "&w_chave_aux=" & w_cliente & "&w_sq_pessoa=" & Rs("sq_pessoa") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & "GRAVA&R=" & w_Pagina & par & "&O=E&w_chave=" & w_chave & "&w_chave_aux=" & w_cliente & "&w_sq_pessoa=" & Rs("sq_pessoa") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ onClick=""return confirm('Confirma a exclusão do registro?');"">Excluir</A>&nbsp"
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
    If w_cpf = "" or Instr(Request("botao"),"Alterar") > 0 or Instr(Request("botao"),"Procurar") > 0 Then ' Se o beneficiário ainda não foi selecionado
       ShowHTML "<FORM action=""" & w_dir & w_Pagina & par & """ method=""POST"" name=""Form"" onSubmit=""return(Validacao(this));"">"
    Else
       ShowHTML "<FORM action=""" & w_dir & w_Pagina & "Grava"" method=""POST"" name=""Form"" onSubmit=""return(Validacao(this));"">"
    End If
    ShowHTML "<INPUT type=""hidden"" name=""P1"" value=""" & P1 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P2"" value=""" & P2 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P3"" value=""" & P3 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""P4"" value=""" & P4 & """>"
    ShowHTML "<INPUT type=""hidden"" name=""TP"" value=""" & TP & """>"
    ShowHTML "<INPUT type=""hidden"" name=""SG"" value=""" & SG & """>"
    ShowHTML "<INPUT type=""hidden"" name=""R"" value=""" & w_pagina & par & """>"
    ShowHTML "<INPUT type=""hidden"" name=""O"" value=""" & O &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_cliente &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_pessoa"" value=""" & w_sq_pessoa &""">"

    If w_cpf = "" or InStr(Request("botao"), "Alterar") > 0 or Instr(Request("botao"),"Procurar") > 0 Then
       w_nome = Request("w_nome")
       If InStr(Request("botao"), "Alterar") > 0 Then
          w_cpf  = ""
          w_nome = ""
       End If
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><TD>"
       ShowHTML "    <table border=""0"">"
       ShowHTML "        <tr><TD colspan=4>Informe os dados abaixo e clique no botão ""Selecionar"" para continuar.</TD>"
       ShowHTML "        <tr><TD colspan=4><b><u>C</u>PF:<br><INPUT ACCESSKEY=""C"" TYPE=""text"" class=""sti"" NAME=""w_cpf"" VALUE=""" & w_cpf & """ SIZE=""14"" MaxLength=""14"" onKeyDown=""FormataCPF(this, event);"">"
       ShowHTML "            <INPUT class=""stb"" TYPE=""submit"" NAME=""Botao"" VALUE=""Selecionar"">"
       ShowHTML "        <tr><TD colspan=4><p>&nbsp</p>"
       ShowHTML "        <tr><TD colspan=4 heigth=1 bgcolor=""#000000"">"
       ShowHTML "        <tr><TD colspan=4>"
       ShowHTML "             <b><u>P</u>rocurar pelo nome:</b> (Informe qualquer parte do nome SEM ACENTOS)<br><INPUT ACCESSKEY=""P"" TYPE=""text"" class=""sti"" NAME=""w_nome"" VALUE=""" & w_nome & """ SIZE=""20"" MaxLength=""20"">"
       ShowHTML "              <INPUT class=""stb"" TYPE=""submit"" NAME=""Botao"" VALUE=""Procurar"" onClick=""Botao.value=this.value; document.Form.action='" & w_dir & w_Pagina & par &"'"">"
       ShowHTML "      </table>"
       If w_nome > "" Then
          DB_GetBenef RS, w_cliente, null, null, null, w_nome, 1, null, null ' Recupera apenas pessoas físicas
          RS.Sort = "nm_pessoa"
          ShowHTML "<tr><TD align=""center"" colspan=3>"
          ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
          ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
          ShowHTML "          <TD><b>Nome</font></td>"
          ShowHTML "          <TD><b>Nome resumido</font></td>"
          ShowHTML "          <TD><b>CPF</font></td>"
          ShowHTML "          <TD><b>Operações</font></td>"
          ShowHTML "        </tr>"
          If RS.EOF Then
              ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><TD colspan=4 align=""center""><font ><b>Não há pessoas que contenham o texto informado.</b></td></tr>"
          Else
            While Not RS.EOF
              ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
              ShowHTML "        <TD><font >" & RS("nm_pessoa") & "</td>"
              ShowHTML "        <TD><font >" & RS("nome_resumido") & "</td>"
              ShowHTML "        <TD align=""center"">" & Nvl(RS("cpf"),"---") & "</td>"
              ShowHTML "        <TD nowrap>"
              ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_pagina & par & "&R=" & R & "&O=I&w_cpf=" & RS("cpf") & "&w_sq_pessoa=" & RS("sq_pessoa") & "&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Selecionar</A>&nbsp"
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
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><TD align=""center"">"
       ShowHTML "    <table width=""97%"" border=""0"">"
       ShowHTML "      <tr><TD colspan=""2"" align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><TD colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><TD colspan=""2"" align=""center"" bgcolor=""#D0D0D0""><b>Identificação</td></td></tr>"
       ShowHTML "      <tr><TD colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><TD colspan=""2""><table border=""0"" width=""100%"">"
       ShowHTML "          <tr valign=""top"">"
       ShowHTML "          <TD>CPF:</font><br><b>" & w_cpf
       ShowHTML "              <INPUT type=""hidden"" name=""w_cpf"" value=""" & w_cpf & """>"
       ShowHTML "          <tr valign=""top"">"
       ShowHTML "             <TD><b><u>N</u>ome completo:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""40"" MAXLENGTH=""60"" VALUE=""" & w_nome & """></td>"
       ShowHTML "             <TD><b><u>N</u>ome resumido:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome_resumido"" class=""sti"" SIZE=""15"" MAXLENGTH=""15"" VALUE=""" & w_nome_resumido & """></td>"
       SelecaoSexo "Se<u>x</u>o:", "X", null, w_sexo, null, "w_sexo", null, null
       ShowHTML "          <tr valign=""top"">"
       ShowHTML "          <TD><b><u>I</u>dentidade:</b><br><input " & w_Disabled & " accesskey=""I"" type=""text"" name=""w_rg_numero"" class=""sti"" SIZE=""14"" MAXLENGTH=""80"" VALUE=""" & w_rg_numero & """></td>"
       ShowHTML "          <TD><b>Data de <u>e</u>missão:</b><br><input " & w_Disabled & " accesskey=""E"" type=""text"" name=""w_rg_emissao"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_rg_emissao & """ onKeyDown=""FormataData(this,event);""></td>"
       ShowHTML "          <TD><b>Ór<u>g</u>ão emissor:</b><br><input " & w_Disabled & " accesskey=""G"" type=""text"" name=""w_rg_emissor"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & w_rg_emissor & """></td>"
       ShowHTML "          </table>"

       ShowHTML "      <tr><TD colspan=""2"" align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><TD colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><TD colspan=""2"" align=""center"" bgcolor=""#D0D0D0""><b>Telefones e e-Mail</td></td></tr>"
       ShowHTML "      <tr><TD colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><TD colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
       ShowHTML "          <TD><b><u>D</u>DD:</b><br><input " & w_Disabled & " accesskey=""D"" type=""text"" name=""w_ddd"" class=""sti"" SIZE=""4"" MAXLENGTH=""4"" VALUE=""" & w_ddd & """></td>"
       ShowHTML "          <TD><b>Te<u>l</u>efone:</b><br><input " & w_Disabled & " accesskey=""L"" type=""text"" name=""w_nr_telefone"" class=""sti"" SIZE=""20"" MAXLENGTH=""40"" VALUE=""" & w_nr_telefone & """></td>"
       ShowHTML "          <TD title=""Se o representante informar um número de fax, informe-o neste campo.""><b>Fa<u>x</u>:</b><br><input " & w_Disabled & " accesskey=""X"" type=""text"" name=""w_nr_fax"" class=""sti"" SIZE=""20"" MAXLENGTH=""20"" VALUE=""" & w_nr_fax & """></td>"
       ShowHTML "          <TD title=""Se o representante informar um celular institucional, informe-o neste campo.""><b>C<u>e</u>lular:</b><br><input " & w_Disabled & " accesskey=""E"" type=""text"" name=""w_nr_celular"" class=""sti"" SIZE=""20"" MAXLENGTH=""20"" VALUE=""" & w_nr_celular & """></td>"
       ShowHTML "          <tr><TD colspan=4><b>e-<u>M</u>ail:</b><br><input " & w_Disabled & " accesskey=""M"" type=""text"" name=""w_email"" class=""sti"" SIZE=""40"" MAXLENGTH=""50"" VALUE=""" & w_email & """></td>"
       ShowHTML "          </table>"
    
       ShowHTML "      <tr><TD align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000""></TD></TR>"
       ShowHTML "      <tr><TD align=""center"" colspan=""3"">"
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
    Estrutura_Texto_Fecha
    Estrutura_Fecha
  Estrutura_Fecha
  Estrutura_Fecha
  Rodape

  Set w_chave               = Nothing 
  Set w_chave_aux           = Nothing 
  Set w_sq_pessoa           = Nothing 
  Set w_nome                = Nothing 
  Set w_nome_resumido       = Nothing 
  Set w_sq_pessoa_pai       = Nothing 
  Set w_sq_tipo_pessoa      = Nothing 
  Set w_nm_tipo_pessoa      = Nothing 
  Set w_sq_tipo_vinculo     = Nothing 
  Set w_nm_tipo_vinculo     = Nothing 
  Set w_interno             = Nothing 
  Set w_vinculo_ativo       = Nothing 
  Set w_cpf                 = Nothing 
  Set w_rg_numero           = Nothing 
  Set w_rg_emissor          = Nothing 
  Set w_rg_emissao          = Nothing 
  Set w_sexo                = Nothing 
  Set w_troca               = Nothing 
  Set i                     = Nothing 
  Set w_erro                = Nothing 
  Set w_como_funciona       = Nothing 
  Set w_cor                 = Nothing 
  Set w_disabled            = Nothing
End Sub
REM =========================================================================
REM Fim da tela de representantes
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina das informações adicionais
REM -------------------------------------------------------------------------
Sub Informar
  
  Dim w_chave, w_chave_pai, w_chave_aux, w_sq_menu, w_sq_unidade
  
  Dim w_sq_pais, w_co_uf, w_sq_cidade, w_inicio, w_fim, w_limite_passagem
  Dim w_projeto_inicio, w_projeto_fim
  
  Dim w_troca, i, w_erro, w_como_funciona, w_cor, w_readonly

  w_chave           = Request("w_chave")
  w_readonly        = ""
  w_erro            = ""
  w_troca           = Request("w_troca")

  ' Verifica se há necessidade de recarregar os dados da tela a partir
  ' da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  If w_troca > "" Then ' Se for recarga da página

     w_chave                   = Request("w_chave") 
     w_chave_pai               = Request("w_chave_pai") 
     w_chave_aux               = Request("w_chave_aux") 
     w_sq_menu                 = Request("w_sq_menu") 
     w_sq_unidade              = Request("w_sq_unidade") 
     w_sq_pais                 = Request("w_sq_pais") 
     w_co_uf                   = Request("w_co_uf") 
     w_sq_cidade               = Request("w_sq_cidade")
     w_inicio                  = Request("w_inicio") 
     w_fim                     = Request("w_fim") 
     w_limite_passagem         = Request("w_limite_passagem")  
     w_projeto_inicio          = Request("w_projeto_inicio")
     w_projeto_fim             = Request("w_projeto_fim")    
  Else
     If InStr("AEV",O) > 0 Then
        DB_GetSolicData RS, w_chave, Mid(SG,1,2)&"GERAL"
        If RS.RecordCount > 0 Then 
           w_chave_pai              = RS("sq_solic_pai") 
           w_chave_aux              = null
           w_sq_menu                = RS("sq_menu") 
           w_sq_unidade             = RS("sq_unidade") 
           w_sq_pais                = RS("pais_evento") 
           w_co_uf                  = RS("uf_evento") 
           w_sq_cidade              = RS("cidade_evento")
           w_inicio                 = FormataDataEdicao(RS("inicio_real"))
           w_fim                    = FormataDataEdicao(RS("fim_real"))
           w_limite_passagem        = RS("limite_passagem")
           w_projeto_inicio         = FormataDataEdicao(RS("inicio"))
           w_projeto_fim            = FormataDataEdicao(RS("fim"))
           DesconectaBD
        End If

     End If
  End If  
  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>" & conSgSistema & " - Listagem de projetos</TITLE>"
  If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & replace(MontaURL("MESA"),w_dir,"") & """>" End If
  Estrutura_CSS w_cliente
  ' Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  ' tratando as particularidades de cada serviço
  ScriptOpen "JavaScript"
  CheckBranco
  FormataData
  FormataDataHora
  ValidateOpen "Validacao"
  ShowHTML "  if (theForm.Botao.value == ""Troca"") { return true; }"
  Validate "w_sq_pais", "País do evento", "SELECT", 1, 1, 10, "1", "1"
  Validate "w_co_uf", "UF do evento", "SELECT", 1, 1, 10, "1", "1"
  Validate "w_sq_cidade", "Cidade do evento", "SELECT", 1, 1, 10, "", "1"
  Validate "w_inicio", "Início do evento", "DATA", "1", "10", "10", "", "0123456789/"
  Validate "w_fim", "Fim do evento", "DATA", "1", "10", "10", "", "0123456789/"
  CompData "w_inicio", "Início do evento", "<=", "w_fim", "Fim do evento"
  CompData "w_inicio", "Início do evento", ">=", "w_projeto_inicio", "Início do projeto"
  CompData "w_fim", "Fim do evento", "<=", "w_projeto_fim", "Fim do projeto"
  Validate "w_limite_passagem", "Limite de passagens", "", "1", "1", "18", "", "1"
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf Instr("EV",O) > 0 Then
     BodyOpen "onLoad='document.focus()';"
  Else
     BodyOpen "onLoad='document.Form.w_descricao.focus()';"
  End If
   Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  ShowHTML "<table align=""center"" border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If Instr("IAEV",O) > 0 Then  
  '  If InStr("EV",O) Then
  '     w_Disabled = " DISABLED "
  '     If O = "V" Then
  '        w_Erro = Validacao(w_sq_solicitacao, sg)
  '     End If
  '  End If

    AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"PJINFO",R,O
    ShowHTML MontaFiltro("POST")
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_copia"" value=""" & w_copia &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_data_hora"" value=""" & RS_menu("data_hora") &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & RS_menu("sq_menu") &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_projeto_inicio"" value=""" & w_projeto_inicio &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_projeto_fim"" value=""" & w_projeto_fim &""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td valign=""top"" align=""center"" bgcolor=""#D0D0D0""><font size=""2""><b>Informações adicionais</td></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td><font size=1>Identificação do evento e roteiro de viagem.</font></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"    
    SelecaoPais   "<u>P</u>aís do evento:", "P", null, w_sq_pais, null, "w_sq_pais", "nome='França'", "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_co_uf'; document.Form.submit();"""
    SelecaoEstado "E<u>s</u>tado do evento:", "S", null, w_co_uf, w_sq_pais, "N", "w_co_uf", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_sq_cidade'; document.Form.submit();"""
    SelecaoCidade "<u>C</u>idade do evento:", "C", null, w_sq_cidade, w_sq_pais, w_co_uf, "w_sq_cidade", null, null
    ShowHTML "      <tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b><u>I</u>nício do evento:</b><br><input " & w_Disabled & " accesskey=""I"" type=""text"" name=""w_inicio"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_inicio & """ onKeyDown=""FormataData(this,event);"" title=""Informe a data de inicio do evento.""></td>"
    ShowHTML "          <td><font size=""1""><b><u>F</u>im do evento:</b><br><input " & w_Disabled & " accesskey=""F"" type=""text"" name=""w_fim"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_fim & """ onKeyDown=""FormataData(this,event);"" title=""Informe a data de fim do evento.""></td>"
    ShowHTML "      <tr><td><font size=""1""><b><u>L</u>imite de passagens:<br><input " & w_Disabled & " accesskey=""L"" type=""text"" name=""w_limite_passagem"" class=""sti"" SIZE=""5"" MAXLENGTH=""18"" VALUE=""" & w_limite_passagem & """></td>"
    ShowHTML "      </table>"
     
    ' Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000""></TD></TR>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Gravar"">"
    If P1 <> 1 Then ' Se não for cadastramento
       ' Volta para a listagem
       DB_GetMenuData RS, w_menu
       ShowHTML "      <input class=""stb"" type=""button"" onClick=""location.href='" & replace(RS("link"),w_dir,"") & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & RemoveTP(TP) & "&SG=" & rs("sigla") & MontaFiltro("GET") & "';"" name=""Botao"" value=""Abandonar"">"
       DesconectaBD
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
  Estrutura_Texto_Fecha
    Estrutura_Fecha
  Estrutura_Fecha
  Estrutura_Fecha
  Rodape

  Set w_chave                   = Nothing 
  Set w_chave_pai               = Nothing 
  Set w_chave_aux               = Nothing 
  Set w_sq_menu                 = Nothing 
  Set w_sq_pais                 = Nothing 
  Set w_co_uf                   = Nothing 
  Set w_sq_cidade               = Nothing
  Set w_inicio                  = Nothing
  Set w_fim                     = Nothing
  Set w_limite_passagem         = Nothing
  Set w_projeto_inicio          = Nothing
  Set w_projeto_fim             = Nothing
  
  Set w_troca                   = Nothing 
  Set i                         = Nothing 
  Set w_erro                    = Nothing 
  Set w_como_funciona           = Nothing 
  Set w_cor                     = Nothing 

End Sub
REM =========================================================================
REM Fim da rotina de informações adicionais
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
     w_logo = "files\" & w_cliente & "\img\logo" & Mid(RS("logo"),Instr(RS("logo"),"."),30)
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
  ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" SRC=""" & w_logo & """><TD ALIGN=""RIGHT""><B><FONT SIZE=4 COLOR=""#000000"">"
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
  ShowHTML "<TITLE>" & conSgSistema & " - Listagem de projetos</TITLE>"
  If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & replace(MontaURL("MESA"),w_dir,"") & """>" End If
  Estrutura_CSS w_cliente

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
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  Else
     BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
  End If
  ShowHTML "<center>"
  Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  
  
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"

  ' Chama a rotina de visualização dos dados do projeto, na opção "Listagem"
  ShowHTML VisualProjeto(w_chave, "V", w_usuario, P1, P4)

  ShowHTML "<HR>"
  AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"PJCAD",R,O
  ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & w_menu & """>"
  DB_GetSolicData RS, w_chave, "PJGERAL"
  ShowHTML "<INPUT type=""hidden"" name=""w_tramite"" value=""" & RS("sq_siw_tramite") & """>"
  DesconectaBD

  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><TD align=""center"">"
  ShowHTML "  <table width=""97%"" border=""0"">"
  ShowHTML "      <tr><TD align=""LEFT"" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
  ShowHTML "    <tr><TD align=""center"" colspan=4><hr>"
  ShowHTML "      <input class=""stb"" type=""submit"" name=""Botao"" value=""Excluir"">"
  ShowHTML "      <input class=""stb"" type=""button"" onClick=""history.back(1);"" name=""Botao"" value=""Abandonar"">"
  ShowHTML "      </td>"
  ShowHTML "    </tr>"
  ShowHTML "  </table>"
  ShowHTML "  </TD>"
  ShowHTML "</tr>"
  ShowHTML "</FORM>"
  ShowHTML "</table>"
    Estrutura_Texto_Fecha
    Estrutura_Fecha
  Estrutura_Fecha
  Estrutura_Fecha
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
  w_erro            = ""
  
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
  w_sg_tramite = RS("sigla")
  DesconectaBD

  ' Se for envio, executa verificações nos dados da solicitação
  If O = "V" Then w_erro = ValidaProjeto(w_cliente, w_chave, "PJGERAL", null, null, null, w_tramite) End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>" & conSgSistema & " - Listagem de projetos</TITLE>"
  If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & replace(MontaURL("MESA"),w_dir,"") & """>" End If
  Estrutura_CSS w_cliente

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
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  Else
     BodyOpen "onLoad='document.Form.w_destinatario.focus()';"
  End If
  ShowHTML "<center>"
  Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  
  
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"

  ' Chama a rotina de visualização dos dados do projeto, na opção "Listagem"
  ShowHTML VisualProjeto(w_chave, "V", w_usuario, P1, P4)

  ShowHTML "<tr><TD>"
  ShowHTML "  <table width=""100%"" border=""0"">"
  ShowHTML "    <tr><TD colspan=""2""><HR>"
  
  AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"PJENVIO",R,O
  ShowHTML MontaFiltro("POST")
  ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & w_menu & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_tramite"" value=""" & w_tramite & """>"
 
  ShowHTML "    <tr><TD valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
  If P1 <> 1 Then ' Se não for cadastramento
     If Mid(Nvl(w_erro,"-"),1,1) <> "0" and RetornaGestor(w_chave, w_usuario) = "S" Then
        SelecaoFase "<u>F</u>ase do projeto:", "F", "Se deseja alterar a fase atual do projeto, selecione a fase para a qual deseja enviá-lo.", w_novo_tramite, w_tramite, "w_novo_tramite", w_chave, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='w_destinatario'; document.Form.submit();"""
     Else
        SelecaoFase "<u>F</u>ase do projeto:", "F", "Se deseja alterar a fase atual do projeto, selecione a fase para a qual deseja enviá-lo.", w_novo_tramite, w_tramite, "w_novo_tramite", "ERRO", "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='w_destinatario'; document.Form.submit();"""
     End If
     ' Se for envio para o cadastramento, exibe apenas as pessoas autorizadas a fazê-lo.
     If w_sg_tramite = "CI" Then
        SelecaoSolicResp "<u>D</u>estinatário:", "D", "Selecione um destinatário para o projeto na relação.", w_destinatario, w_chave, w_novo_tramite, w_novo_tramite, "w_destinatario", "CADASTRAMENTO"
     Else
        SelecaoSolicResp "<u>D</u>estinatário:", "D", "Selecione um destinatário para o projeto na relação.", w_destinatario, w_chave, w_novo_tramite, w_novo_tramite, "w_destinatario", "USUARIOS"
     End If
  Else
     If Mid(Nvl(w_erro,"-"),1,1) <> "0" and RetornaGestor(w_chave, w_usuario) = "S" Then
        SelecaoFase "<u>F</u>ase do projeto:", "F", "Se deseja alterar a fase atual do projeto, selecione a fase para a qual deseja enviá-lo.", w_novo_tramite, w_tramite, "w_novo_tramite", w_chave, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_destinatario'; document.Form.submit();"""
     Else
        SelecaoFase "<u>F</u>ase do projeto:", "F", "Se deseja alterar a fase atual do projeto, selecione a fase para a qual deseja enviá-lo.", w_novo_tramite, w_tramite, "w_novo_tramite", "ERRO", "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_destinatario'; document.Form.submit();"""
     End If
     SelecaoSolicResp "<u>D</u>estinatário:", "D", "Selecione um destinatário para o projeto na relação.", w_destinatario, w_chave, w_novo_tramite, w_novo_tramite, "w_destinatario", "USUARIOS"
  End If
  ShowHTML "    <tr><TD valign=""top"" colspan=2><b>D<u>e</u>spacho:</b><br><textarea " & w_Disabled & " accesskey=""E"" name=""w_despacho"" class=""sti"" ROWS=5 cols=75 title=""Escreva um texto que oriente o destinatário sobre o que é esperado dele neste projeto."">" & w_despacho & "</TEXTAREA></td>"
  ShowHTML "      </table>"
  ShowHTML "      <tr><TD align=""LEFT"" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
  ShowHTML "    <tr><TD align=""center"" colspan=4><hr>"
  ShowHTML "      <input class=""stb"" type=""submit"" name=""Botao"" value=""Enviar"">"
  If P1 <> 1 Then ' Se não for cadastramento
     ' Volta para a listagem
     DB_GetMenuData RS, w_menu
     ShowHTML "      <input class=""stb"" type=""button"" onClick=""location.href='" & replace(RS("link"),w_dir,"") & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & RemoveTP(TP) & "&SG=" & rs("sigla") & MontaFiltro("GET") & "';"" name=""Botao"" value=""Abandonar"">"
     DesconectaBD
  End If
  ShowHTML "      </td>"
  ShowHTML "    </tr>"
  ShowHTML "  </table>"
  ShowHTML "  </TD>"
  ShowHTML "</tr>"
  ShowHTML "</FORM>"
  ShowHTML "</table>"
    Estrutura_Texto_Fecha
    Estrutura_Fecha
  Estrutura_Fecha
  Estrutura_Fecha
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

  Dim w_chave, w_chave_pai, w_chave_aux, w_observacao, w_caminho
  
  Dim w_troca, i, w_erro
  
  w_Chave           = Request("w_Chave")
  w_chave_aux       = Request("w_chave_aux")
  w_troca           = Request("w_troca")
  
  If w_troca > "" Then ' Se for recarga da página
     w_observacao     = Request("w_observacao")
  End If

  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>" & conSgSistema & " - Listagem de projetos</TITLE>"
  If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & replace(MontaURL("MESA"),w_dir,"") & """>" End If
  Estrutura_CSS w_cliente

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
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  Else
     BodyOpen "onLoad='document.Form.w_observacao.focus()';"
  End If
  ShowHTML "<center>"
  Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  
  
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"

  ' Chama a rotina de visualização dos dados do projeto, na opção "Listagem"
  ShowHTML VisualProjeto(w_chave, "V", w_usuario, P1, P4)

  ShowHTML "<HR>"
  ShowHTML "<FORM action=""" & w_dir & w_pagina & "Grava&SG=PJENVIO&O="&O&""" name=""Form"" onSubmit=""return(Validacao(this));"" enctype=""multipart/form-data"" method=""POST"">" 
  ShowHTML "<INPUT type=""hidden"" name=""P1"" value=""" & P1 & """>" 
  ShowHTML "<INPUT type=""hidden"" name=""P2"" value=""" & P2 & """>" 
  ShowHTML "<INPUT type=""hidden"" name=""P3"" value=""" & P3 & """>" 
  ShowHTML "<INPUT type=""hidden"" name=""P4"" value=""" & P4 & """>" 
  ShowHTML "<INPUT type=""hidden"" name=""TP"" value=""" & TP & """>" 
  ShowHTML "<INPUT type=""hidden"" name=""R"" value=""" & R & """>"
  
  ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & w_menu & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_atual"" value=""" & w_caminho & """>"
  DB_GetSolicData RS, w_chave, "PJGERAL"
  ShowHTML "<INPUT type=""hidden"" name=""w_tramite"" value=""" & RS("sq_siw_tramite") & """>"
  DesconectaBD

  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><TD align=""center"">"
  ShowHTML "  <table width=""97%"" border=""0"">"
  ShowHTML "    <tr><TD valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
  DB_GetCustomerData RS, w_cliente  
  ShowHTML "      <tr><td align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""2""><b><font color=""#BC3131"">ATENÇÃO</font>: o tamanho máximo aceito para o arquivo é de " & cDbl(RS("upload_maximo"))/1024 & " KBytes</b>.</font></td>"
  ShowHTML "<INPUT type=""hidden"" name=""w_upload_maximo"" value=""" & RS("upload_maximo") & """>"
  ShowHTML "    <tr><TD valign=""top""><b>A<u>n</u>otação:</b><br><textarea " & w_Disabled & " accesskey=""N""               name=""w_observacao"" class=""sti"" ROWS=5 cols=75 title=""Redija a anotação desejada."">" & w_observacao & "</TEXTAREA></td>"
  ShowHTML "      <tr><td><font size=""1""><b>A<u>r</u>quivo:</b><br><input " & w_Disabled & " accesskey=""R"" type=""file"" name=""w_caminho""    class=""sti"" SIZE=""80"" MAXLENGTH=""100"" title=""OPCIONAL. Se desejar anexar um arquivo, clique no botão ao lado para localizá-lo. Ele será transferido automaticamente para o servidor."">"  
  ShowHTML "      </table>"
  ShowHTML "      <tr><TD align=""LEFT"" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
  ShowHTML "    <tr><TD align=""center"" colspan=4><hr>"
  ShowHTML "      <input class=""stb"" type=""submit"" name=""Botao"" value=""Gravar"">"
  ShowHTML "      <input class=""stb"" type=""button"" onClick=""history.back(1);"" name=""Botao"" value=""Abandonar"">"
  ShowHTML "      </td>"
  ShowHTML "    </tr>"
  ShowHTML "  </table>"
  ShowHTML "  </TD>"
  ShowHTML "</tr>"
  ShowHTML "</FORM>"
  ShowHTML "</table>"
    Estrutura_Texto_Fecha
    Estrutura_Fecha
  Estrutura_Fecha
  Estrutura_Fecha
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
  Dim w_tramite
  
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

  
  ' Recupera a sigla do trâmite desejado, para verificar a lista de possíveis destinatários.
  DB_GetSolicData RS, w_chave, "PJGERAL"
  w_tramite      = RS("sq_siw_tramite")
  DesconectaBD

  ' Se for envio, executa verificações nos dados da solicitação
  If O = "V" Then w_erro = ValidaProjeto(w_cliente, w_chave, "PJGERAL", null, null, null, w_tramite) End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>" & conSgSistema & " - Listagem de projetos</TITLE>"
  If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & replace(MontaURL("MESA"),w_dir,"") & """>" End If
  Estrutura_CSS w_cliente

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
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  Else
     BodyOpen "onLoad='document.Form.w_inicio_real.focus()';"
  End If
  ShowHTML "<center>"
  Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"

  ' Chama a rotina de visualização dos dados do projeto, na opção "Listagem"
  ShowHTML VisualProjeto(w_chave, "V", w_usuario, P1, P4)

  ShowHTML "<HR>"
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><TD align=""center"">"
  ShowHTML "  <table width=""97%"" border=""0"">"
  ShowHTML "      <tr><TD valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
  ShowHTML "          <tr>"
  
  AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"PJCONC",R,O
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
        ShowHTML "              <TD valign=""top""><b>Fi<u>m</u> da execução:</b><br><input " & w_Disabled & " accesskey=""F"" type=""text"" name=""w_fim_real"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_fim_real & """ onKeyDown=""FormataData(this,event);"" title=""Informe a data de término da execução do projeto.""></td>"
     Case 2
        ShowHTML "              <TD valign=""top""><b>Fi<u>m</u> da execução:</b><br><input " & w_Disabled & " accesskey=""F"" type=""text"" name=""w_fim_real"" class=""sti"" SIZE=""17"" MAXLENGTH=""17"" VALUE=""" & w_fim_real & """ onKeyDown=""FormataDataHora(this,event);"" title=""Informe a data/hora de término da execução do projeto.""></td>"
     Case 3
        ShowHTML "              <TD valign=""top""><b>Iní<u>c</u>io da execução:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_inicio_real"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_inicio_real & """ onKeyDown=""FormataData(this,event);"" title=""Informe a data/hora de início da execução do projeto.""></td>"
        ShowHTML "              <TD valign=""top""><b>Fi<u>m</u> da execução:</b><br><input " & w_Disabled & " accesskey=""M"" type=""text"" name=""w_fim_real"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_fim_real & """ onKeyDown=""FormataData(this,event);"" title=""Informe a data de término da execução do projeto.""></td>"
     Case 4
        ShowHTML "              <TD valign=""top""><b>Iní<u>c</u>io da execução:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_inicio_real"" class=""sti"" SIZE=""17"" MAXLENGTH=""17"" VALUE=""" & w_inicio_real & """ onKeyDown=""FormataDataHora(this,event);"" title=""Informe a data/hora de início da execução do projeto.""></td>"
        ShowHTML "              <TD valign=""top""><b>Fi<u>m</u> da execução:</b><br><input " & w_Disabled & " accesskey=""F"" type=""text"" name=""w_fim_real"" class=""sti"" SIZE=""17"" MAXLENGTH=""17"" VALUE=""" & w_fim_real & """ onKeyDown=""FormataDataHora(this,event);"" title=""Informe a data de término da execução do projeto.""></td>"
  End Select
  ShowHTML "              <TD valign=""top""><b>Custo <u>r</u>eal:</b><br><input " & w_Disabled & " accesskey=""O"" type=""text"" name=""w_custo_real"" class=""sti"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_custo_real & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o orçamento previsto para execução do projeto, ou zero se não for o caso.""></td>"
  ShowHTML "          </table>"
  ShowHTML "    <tr><TD valign=""top""><b>Nota d<u>e</u> conclusão:</b><br><textarea " & w_Disabled & " accesskey=""E"" name=""w_nota_conclusao"" class=""sti"" ROWS=5 cols=75 title=""Descreva o quanto o projeto atendeu aos resultados esperados."">" & w_nota_conclusao & "</TEXTAREA></td>"
  ShowHTML "      <tr><TD align=""LEFT"" colspan=4><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
  ShowHTML "    <tr><TD align=""center"" colspan=4><hr>"
  ShowHTML "      <input class=""stb"" type=""submit"" name=""Botao"" value=""Concluir"">"
  If P1 <> 1 Then ' Se não for cadastramento
     ShowHTML "      <input class=""stb"" type=""button"" onClick=""history.back(1);"" name=""Botao"" value=""Abandonar"">"
  End If
  ShowHTML "      </td>"
  ShowHTML "    </tr>"
  ShowHTML "  </table>"
  ShowHTML "  </TD>"
  ShowHTML "</tr>"
  ShowHTML "</FORM>"
  ShowHTML "</table>"
    Estrutura_Texto_Fecha
    Estrutura_Fecha
  Estrutura_Fecha
  Estrutura_Fecha
  Rodape

  Set w_tramite         = Nothing 
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
REM Rotina da página de fale conosco
REM -------------------------------------------------------------------------
Sub varigMail
   Dim w_tabela, w_count, RSQuery, w_TrBgColor
   Dim FS, F1, w_linha, w_caminho, w_assunto, w_mensagem, w_para
   
   Cabecalho
   ShowHTML "<HEAD>"
   ShowHTML "<TITLE>" & conSgSistema & " - Listagem de projetos</TITLE>"
   If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & replace(MontaURL("MESA"),w_dir,"") & """>" End If
   Estrutura_CSS w_cliente
   ScriptOpen "JavaScript"
   ValidateOpen "Validacao"
   Validate "w_para",         "Para",  "", "1",   2,    80, "1", "1"
   Validate "w_assunto",   "assunto",  "", "1",   2,    80, "1", "1"
   Validate "w_caminho",   "Arquivo",  "", "1", "5", "255", "1", "1"
   Validate "w_mensagem", "Mensagem", "1",  1,   5,  2000, "1", "1"
   ValidateClose
   ScriptClose
   ShowHTML "</HEAD>"
   Estrutura_Topo_Limpo
   Estrutura_Menu
   Estrutura_Corpo_Abre
   Estrutura_Texto_Abre
   
   DB_GetSolicData RS, Request("w_chave"), "PJGERAL"
   ShowHTML "        <A class=""hl"" HREF=""" & w_Pagina & "VisualTabela&R=" & w_Pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=WORD&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exibe as informações deste registro."" target=""_blank"">Clique aqui para visualizar os dados dos viajantes&nbsp;</a>"
   ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
   ShowHTML "<br>"
   ShowHTML "<p>Preencha o formulário abaixo para enviar o e-mail.</p>"
   ShowHTML "<table border=0 width=""100%"" cellspacing=0><tr bgcolor=""" & conTrBgColor & """><td style=""border: 1px solid rgb(0,0,0);"">"
   ShowHTML "<table border=0 width=""90%"" cellspacing=0>"
   ShowHTML "<FORM action=""" & w_pagina & "Envia&O="&O&"&w_menu="&w_menu&""" name=""Form"" onSubmit=""return(Validacao(this));"" enctype=""multipart/form-data"" method=""POST"">"
   ShowHTML " <tr><td colspan=2><b><u>P</u>ara:</b><br><INPUT class=""sti"" type=""text"" name=""w_para"" accesskey=""P"" size=""65"" maxlength=""80""></td>"
   ShowHTML " <tr><td colspan=2><b><u>A</u>ssunto:</b><br><INPUT class=""sti"" type=""text"" name=""w_assunto"" accesskey=""A"" size=""65"" maxlength=""80""></td>"
   ShowHTML " <tr valign=""top"" >"
   ShowHTML "      <tr><td><font size=""1""><b>A<u>r</u>quivo:</b><br><input " & w_Disabled & " accesskey=""R"" type=""file"" name=""w_caminho"" class=""sti"" SIZE=""80"" MAXLENGTH=""100"" VALUE=""ANEXO"" title=""'OPCIONAL. Se desejar anexar um arquivo, clique no botão ao lado para localizá-lo. Ele será transferido automaticamente para o servidor."">"  
   ShowHTML " <tr><td colspan=2><b>Mensagem:</b><br><textarea name=""w_mensagem"" class=""sti"" ROWS=15 cols=100></TEXTAREA></td>"
   ShowHTML " <tr><td colspan=2 align=center><input class=""stb"" type=""submit"" name=""Botao"" value=""Enviar mensagem"">"
   ShowHTML " </table>"
   ShowHTML "</FORM>"
   ShowHTML " </table>"
     Estrutura_Texto_Fecha
    Estrutura_Fecha
   Estrutura_Fecha
   Estrutura_Fecha
   Rodape
   
   Set w_tabela    = Nothing
   Set w_count     = Nothing
   Set RSQuery     = Nothing
   Set w_TrBgColor = Nothing
   
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------


REM =========================================================================
REM Rotina de preparação para envio de e-mail
REM -------------------------------------------------------------------------
Sub Envia
   Dim w_atua, w_file
   Dim w_html, w_resultado, w_assunto
   
   w_resultado = ""
    
   BodyOpenMail(null) & VbCrLf & _
   w_html & _
   "</BODY>" & VbCrLf & _
   "</HTML>" & VbCrLf
   ' Trata o recebimento de upload ou dados 
   If InStr(uCase(Request.ServerVariables("http_content_type")),"MULTIPART/FORM-DATA") > 0 Then
      ' Se foi feito o upload de um arquivo 
      If ul.Files("w_caminho").OriginalPath > "" Then 
         ' Verifica se o tamanho das fotos está compatível com  o limite de 100KB. 
         If ul.Files("w_caminho").Size > 5000024 Then
            ScriptOpen("JavaScript") 
            ShowHTML "  alert('Atenção: o tamanho máximo do arquivo não pode exceder " & 5000024/1024 & " KBytes!');" 
            ShowHTML "  history.back(1);" 
            ScriptClose
            Response.End()
            exit sub 
         End If
         ul.Files("w_caminho").SaveAs(conFilePhysical & w_cliente & "\" & ul.GetFileName(ul.Files("w_caminho").OriginalPath))
      Else 
         w_file = "" 
      End If 
   End If
   
   w_resultado = EnviaMail2(ul.Form("w_para"))
   ' Se ocorreu algum erro, avisa da impossibilidade de envio
   If w_resultado > "" Then
      ScriptOpen "JavaScript"
      ShowHTML "  alert('ATENÇÃO: não foi possível proceder o envio do e-mail.\n" & w_resultado & "');" 
      ShowHTML "  history.back(1);"
      ScriptClose
   Else
      ul.FileDelete (conFilePhysical & w_cliente & "\" & ul.GetFileName(ul.Files("w_caminho").OriginalPath))
      ScriptOpen "JavaScript"
      ShowHTML "  alert('O e-mail foi enviado com sucesso.\n');"
      ShowHTML "  history.back(1);"
      ScriptClose
   End If
   Set w_html      = Nothing
   Set w_resultado = Nothing
   Set w_assunto   = Nothing
End Sub
REM =========================================================================
REM Fim da rotina da preparação para envio de e-mail
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de visualização
REM -------------------------------------------------------------------------
Sub VisualTabela

  Dim w_chave, w_Erro, w_logo, w_tipo

  w_chave           = Request("w_chave")
  w_tipo            = uCase(Trim(Request("w_tipo")))

  ' Recupera o logo do cliente a ser usado nas listagens
  DB_GetCustomerData RS, w_cliente
  If RS("logo") > "" Then
     w_logo = "files\" & w_cliente & "\img\logo" & Mid(RS("logo"),Instr(RS("logo"),"."),30)
  End If
  DesconectaBD
  
  If w_tipo = "WORD" Then
     Response.ContentType = "application/msword"
  Else 
     Cabecalho
  End If

  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>" & conSgSistema & " - Visualização da Lista de Viajantes</TITLE>"
  ShowHTML "</HEAD>"  
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_tipo <> "WORD" Then
     BodyOpenClean "onLoad='document.focus()'; "
  End If
  ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" SRC=""" & w_logo & """><TD ALIGN=""RIGHT""><B><FONT SIZE=0 COLOR=""#000000"">"
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
  ShowHTML VisualTabelaWord(w_chave)

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
  ElseIf p_tipo = 4 Then
     w_html = w_html & "      <tr valign=""top""><TD align=""center""><b>Lista de Viajantes</b></font><br><br><TD></tr>" & VbCrLf     
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
  If p_tipo <> 4 Then
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
  ElseIf p_tipo = 4 Then ' Se for envio de e-mail para varig
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
  Dim FS, F1, w_file

  ShowHTML "<HTML>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  BodyOpen "onLoad=document.focus();"
  
  AbreSessao    
  Select Case SG
    Case "PJCAD"
       ' Verifica se a Assinatura Eletrônica é válida
       'ExibeVariaveis
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          
          If O = "E" Then
             DML_PutProjetoGeral O, _
                 Request("w_chave"), Request("w_menu"), Session("lotacao"), Request("w_solicitante"), _
                 Request("w_proponente"), Session("sq_pessoa"), null, Request("w_sqcc"), _
                 Request("w_descricao"), Request("w_justificativa"), Request("w_inicio"), _
                 Request("w_fim"), Nvl(Request("w_valor"),0), Request("w_data_hora"), _
                 Request("w_sq_unidade_resp"), Request("w_titulo"), Request("w_prioridade"), _
                 Nvl(Request("w_aviso"),"N"), Nvl(Request("w_dias"),"0"), Request("w_cidade"), Request("w_palavra_chave"), _
                 Request("w_vincula_contrato"), Request("w_vincula_viagem"), null, null, null, null, _
                 Request("w_sq_tipo_pessoa"), w_chave_nova, w_copia
          Else
             DB_GetCityList RS, Request("w_pais"), Request("w_uf")
             RS.Filter = "capital='Sim'"
             DML_PutProjetoGeral O, _
                 Request("w_chave"), Request("w_menu"), Session("lotacao"), Request("w_solicitante"), _
                 Request("w_proponente"), Session("sq_pessoa"), null, Request("w_sqcc"), _
                 Request("w_descricao"), Request("w_justificativa"), Request("w_inicio"), _
                 Request("w_fim"), Nvl(Request("w_valor"),0), Request("w_data_hora"), _
                 Request("w_sq_unidade_resp"), Request("w_titulo"), Request("w_prioridade"), _
                 Nvl(Request("w_aviso"),"N"), Nvl(Request("w_dias"),"0"), RS("sq_cidade"), Request("w_palavra_chave"), _
                 Request("w_vincula_contrato"), Request("w_vincula_viagem"), null, null, null, null, _
                 Request("w_sq_tipo_pessoa"), w_chave_nova, w_copia
             
             If Request("w_sq_tipo_pessoa") = 1 Then ' Se pessoa física
                DML_PutProjetoOutra _
                                Request("O"),                    SG,                              Nvl(Request("w_chave"), w_chave_nova), _
                                w_cliente,                       Request("w_sq_prop"),            Request("w_cpf"), _
                                null,                            Request("w_nm_prop"),            Request("w_nm_prop_res"), _
                                Request("w_sexo"),               null,                            null, _
                                null,                            null,                            null, _
                                null,                            null,                            null, _
                                null,                            null,                            RS("sq_cidade"), _
                                null,                            null,                            null, _
                                null,                            null,                            null, _
                                null,                            null,                            null, _
                                Request("w_sq_prop_atual")

                DML_PutProjetoRep _
                                Request("O"),                    SG,                              Nvl(Request("w_chave"), w_chave_nova), _
                                w_cliente,                       Request("w_sq_prop"),            Request("w_cpf"), _
                                Request("w_nm_prop"),            Request("w_nm_prop_res"),        Request("w_sexo"), _
                                null,                            null,                            null, _
                                null,                            null,                            null, _
                                null,                            Request("w_email")
                                
                DML_PutInformar _
                                Nvl(Request("w_chave"), w_chave_nova), Request("w_sq_cidade"), _
                                null, null, Request("w_limite_passagem")
             ElseIf Request("w_sq_tipo_pessoa") = 2 Then ' Se pessoa jurídica
                DML_PutProjetoOutra _
                                Request("O"),                    SG,                              Nvl(Request("w_chave"), w_chave_nova), _
                                w_cliente,                       Request("w_sq_prop"),            null, _
                                Request("w_cnpj"),               Request("w_nm_prop"),            Request("w_nm_prop_res"), _
                                null,                            null,                            null, _
                                null,                            null,                            null, _
                                null,                            null,                            null, _
                                null,                            null,                            RS("sq_cidade"), _
                                null,                            null,                            null, _
                                null,                            null,                            null, _
                                null,                            null,                            null, _
                                Request("w_sq_prop_atual")
                    
                DML_PutProjetoRep _
                                Request("O"),                    SG,                              Nvl(Request("w_chave"), w_chave_nova), _
                                w_cliente,                       Request("w_sq_rep"),             Request("w_cpf"), _
                                Request("w_nm_rep"),             Request("w_nm_rep_res"),         Request("w_sexo"), _
                                null,                            null,                            null, _
                                null,                            null,                            null, _
                                null,                            Request("w_email")
                                
                DML_PutProjetoPreposto _
                                Request("O"),                    SG,                              Nvl(Request("w_chave"), w_chave_nova), _
                                w_cliente,                       Request("w_sq_rep"),             Request("w_cpf"), _
                                Request("w_nm_rep"),             Request("w_nm_rep_res"),         Request("w_sexo"), _
                                null,                            null,                            null
                                
                DML_PutInformar _
                                Nvl(Request("w_chave"), w_chave_nova), Request("w_sq_cidade"), _
                                null, null, Request("w_limite_passagem")                                
             End If
             DesconectaBD
          End If
          'If O = "I" Then
             ' Envia e-mail comunicando a inclusão
          '   SolicMail Nvl(Request("w_chave"), w_chave_nova) ,1

             ' Recupera os dados para montagem correta do menu
          '   DB_GetMenuData RS1, w_menu
          '   ScriptOpen "JavaScript"
          '   ShowHTML "  parent.menu.location='../Menu.asp?par=ExibeDocs&O=A&w_chave=" & w_chave_nova & "&w_documento=Nr. " & w_chave_nova & "&R=" & R & "&SG=" & RS1("sigla") & "&TP=" & TP & MontaFiltro("GET") & "';"

          'ElseIf O = "E" Then
             ScriptOpen "JavaScript"
             ShowHTML "  location.href='" & R & "&O=L&R=" & R & "&SG=PJCAD&w_menu=" & w_menu & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & RemoveTP(TP) & MontaFiltro("GET") & "';"
          'Else
          '   ' Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link
          '   DB_GetLinkData RS1, Session("p_cliente"), SG
          '   ScriptOpen "JavaScript"
          '   ShowHTML "  location.href='" & replace(RS1("link"),w_dir,"") & "&O=" & O & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
          'End If
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
          
          ' Trata o recebimento de upload ou dados 
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
                 w_file, ul.Files("w_caminho").Size, ul.Files("w_caminho").ContentType 
    
             ScriptOpen "JavaScript" 
             ' Volta para a listagem 
             DB_GetMenuData RS, w_menu
             ShowHTML "  location.href='" & replace(RS("link"),w_dir,"") & "&O=L&w_chave=" & ul.Form("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & RemoveTP(TP) & "&SG=" & rs("sigla") & MontaFiltroUpload(ul.Form) & "';" 
             'ShowHTML "  location.href='" & replace(RS("link"),w_dir,"") & "&O=L&w_menu=" & Request("w_menu") &"&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & RemoveTP(TP) & "&SG=" & rs("sigla") & MontaFiltro("GET") & "';"
             DesconectaBD 
             ScriptClose 
          Else 
             DML_PutProjetoEnvio Request("w_menu"), Request("w_chave"), w_usuario, Request("w_tramite"), _ 
                 Request("w_novo_tramite"), "N", Request("w_observacao"), Request("w_destinatario"), Request("w_despacho"), _ 
                 null, null, null 
    
             ' Envia e-mail comunicando a tramitação
             SolicMail Request("w_chave"),2

             If P1 = 1 Then ' Se for envio da fase de cadastramento, remonta o menu principal
                ' Recupera os dados para montagem correta do menu
                DB_GetMenuData RS, w_menu
                ScriptOpen "JavaScript"
                ShowHTML "  location.href='" & replace(RS("link"),w_dir,"") & "&O=L&w_menu=" & Request("w_menu") &"&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & RemoveTP(TP) & "&SG=" & rs("sigla") & MontaFiltro("GET") & "';"
                ScriptClose
                DesconectaBD
             Else
                ScriptOpen "JavaScript" 
                ' Volta para a listagem 
                DB_GetMenuData RS, Request("w_menu") 
                ShowHTML "  location.href='" & replace(RS("link"),w_dir,"") & "&O=L&w_menu=" & Request("w_menu") &"&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & RemoveTP(TP) & "&SG=" & rs("sigla") & MontaFiltro("GET") & "';"
                DesconectaBD 
                ScriptClose 
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
             ShowHTML "  location.href='" & replace(RS("link"),w_dir,"") & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & RemoveTP(TP) & "&SG=" & rs("sigla") & MontaFiltro("GET") & "';"
             DesconectaBD
             ScriptClose
          End If
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "PJOUTRA"
     ' Verifica se a Assinatura Eletrônica é válida
     If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then
 
        'ExibeVariaveis
        DML_PutProjetoOutra Request("O"),                    SG,                              Request("w_chave"), _
                            Request("w_chave_aux"),          Request("w_sq_pessoa"),          Request("w_cpf"), _
                            Request("w_cnpj"),               Request("w_nome"),               Request("w_nome_resumido"), _
                            Request("w_sexo"),               Request("w_nascimento"),         Request("w_rg_numero"), _
                            Request("w_rg_emissao"),         Request("w_rg_emissor"),         Request("w_passaporte_numero"), _
                            Request("w_sq_pais_passaporte"), Request("w_inscricao_estadual"), Request("w_logradouro"), _
                            Request("w_complemento"),        Request("w_bairro"),             Request("w_sq_cidade"), _
                            Request("w_cep"),                Request("w_ddd"),                Request("w_nr_telefone"), _
                            Request("w_nr_fax"),             Request("w_nr_celular"),         Request("w_email"), _
                            Request("w_sq_agencia"),         Request("w_operacao"),           Request("w_nr_conta"), _
                            Request("w_pessoa_atual")
              
        ScriptOpen "JavaScript"
        ShowHTML "  location.href='" & R & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & RemoveTP(TP) & "&SG=" & SG & MontaFiltro("GET") & "';"
        ScriptClose
     Else
        ScriptOpen "JavaScript"
        ShowHTML "  alert('Assinatura Eletrônica inválida!');"
        ShowHTML "  history.back(1);"
        ScriptClose
     End If
    Case "PJREPRES"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then 
 
          'ExibeVariaveis
          DML_PutProjetoRep Request("O"),            SG,                         Request("w_chave"), _
                            Request("w_chave_aux"),  Request("w_sq_pessoa"),     Request("w_cpf"), _
                            Request("w_nome"),       Request("w_nome_resumido"), Request("w_sexo"), _
                            Request("w_rg_numero"),  Request("w_rg_emissao"),    Request("w_rg_emissor"), _
                            Request("w_ddd"),        Request("w_nr_telefone"),   Request("w_nr_fax"), _
                            Request("w_nr_celular"), Request("w_email")

          ' Se for inclusão de representante, assume que ele também é o preposto.
          If O = "I" and Request("w_sq_tipo_pessoa") = 2 Then
             DML_PutProjetoPreposto Request("O"),           SG,                         Request("w_chave"), _
                                    Request("w_chave_aux"), Request("w_sq_pessoa"),     Request("w_cpf"), _
                                    Request("w_nome"),      Request("w_nome_resumido"), Request("w_sexo"), _
                                    Request("w_rg_numero"), Request("w_rg_emissao"),    Request("w_rg_emissor")
          End If

          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & RemoveTP(TP) & "&SG=" & SG & MontaFiltro("GET") & "';"
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
          DML_PutInformar Request("w_chave"), Request("w_sq_cidade"), _
                                 Request("w_inicio"), Request("w_fim"), Request("w_limite_passagem")
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & "PJCAD" & MontaFiltro("GET") & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "GDPANEXO"
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
    Case Else
       ScriptOpen "JavaScript"
       ShowHTML "  alert('Bloco de dados não encontrado: " & SG & "');"
       ShowHTML "  history.back(1);"
       ScriptClose
  End Select

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
    Case "GERAL"         Geral
    Case "OUTRAPARTE"    OutraParte
    Case "PREPOSTO"      Preposto
    Case "REPRESENTANTE" Representante
    Case "INFORMAR"      Informar
    Case "VISUAL"        Visual
    Case "VISUALTABELA"  VisualTabela
    Case "EXCLUIR"       Excluir
    Case "ENVIO"         Encaminhamento
    Case "ANOTACAO"      Anotar
    Case "VARIGMAIL"     VarigMail
    Case "ENVIA"         Envia
    Case "CONCLUIR"      Concluir
    Case "GRAVA"         Grava
    Case Else
       Cabecalho
       ShowHTML "<HEAD>"
       ShowHTML "<TITLE>" & conSgSistema & " - Listagem de projetos</TITLE>"
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
  End Select
End Sub
REM =========================================================================
REM Fim da rotina principal
REM -------------------------------------------------------------------------
%>

