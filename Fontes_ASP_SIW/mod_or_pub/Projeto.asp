<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Geral.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Cliente.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Seguranca.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Link.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_EO.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DML_Projeto.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DML_Solic.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE FILE="VisualProjeto.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Tabelas.asp" -->
<!-- #INCLUDE FILE="DML_Tabelas.asp" -->
<!-- #INCLUDE FILE="DB_SIAFI.asp" -->
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
Dim R, O, w_Cont, w_Reg, w_pagina, w_Disabled, w_TP, w_classe, w_submenu, w_filtro, w_copia
Dim w_Assinatura, w_SG
Dim p_ativo, p_solicitante, p_prioridade, p_unidade, p_proponente, p_ordena
Dim p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_projeto, p_atividade
Dim p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc
Dim p_sq_acao_ppa, p_sq_orprioridade
Dim w_troca,w_cor, w_filter, w_cliente, w_usuario, w_menu, w_dir, w_dir_volta
Dim w_sq_pessoa
Dim ul,File
Set RS  = Server.CreateObject("ADODB.RecordSet")
Set RS1 = Server.CreateObject("ADODB.RecordSet")
Set RS2 = Server.CreateObject("ADODB.RecordSet")
Set RS3 = Server.CreateObject("ADODB.RecordSet")
Set RS4 = Server.CreateObject("ADODB.RecordSet")
Set RS_Menu = Server.CreateObject("ADODB.RecordSet")

Private Par

AbreSessao

' Carrega variáveis locais com os dados dos parâmetros recebidos
Par          = ucase(Request("Par"))
w_pagina     = "Projeto.asp?par="
w_Dir        = "mod_or_pub/"  
w_dir_volta  = "../"  
w_Disabled   = "ENABLED"

SG           = ucase(Request("SG"))
O            = uCase(Request("O"))
w_cliente    = RetornaCliente()
w_usuario    = RetornaUsuario()
w_menu       = RetornaMenu(w_cliente, SG)

If InStr(uCase(Request.ServerVariables("http_content_type")),"MULTIPART/FORM-DATA") > 0 Then  
   ' Cria o objeto de upload  
   Set ul       = Nothing  
   Set ul       = Server.CreateObject("Dundas.Upload.2")  
   ul.SaveToMemory  
   
   w_troca            = ul.Form("w_troca")
   w_copia            = ul.Form("w_copia")
   p_projeto          = uCase(ul.Form("p_projeto"))
   p_atividade        = uCase(ul.Form("p_atividade"))
   p_ativo            = uCase(ul.Form("p_ativo"))
   p_solicitante      = uCase(ul.Form("p_solicitante"))
   p_prioridade       = uCase(ul.Form("p_prioridade"))
   p_unidade          = uCase(ul.Form("p_unidade"))
   p_proponente       = uCase(ul.Form("p_proponente"))
   p_ordena           = uCase(ul.Form("p_ordena"))
   p_ini_i            = uCase(ul.Form("p_ini_i"))
   p_ini_f            = uCase(ul.Form("p_ini_f"))
   p_fim_i            = uCase(ul.Form("p_fim_i"))
   p_fim_f            = uCase(ul.Form("p_fim_f"))
   p_atraso           = uCase(ul.Form("p_atraso"))
   p_chave            = uCase(ul.Form("p_chave"))
   p_assunto          = uCase(ul.Form("p_assunto"))
   p_pais             = uCase(ul.Form("p_pais"))
   p_regiao           = uCase(ul.Form("p_regiao"))
   p_uf               = uCase(ul.Form("p_uf"))
   p_cidade           = uCase(ul.Form("p_cidade"))
   p_usu_resp         = uCase(ul.Form("p_usu_resp"))
   p_uorg_resp        = uCase(ul.Form("p_uorg_resp"))
   p_palavra          = uCase(ul.Form("p_palavra"))
   p_prazo            = uCase(ul.Form("p_prazo"))
   p_fase             = uCase(ul.Form("p_fase"))
   p_sqcc             = uCase(ul.Form("p_sqcc"))
   p_sq_acao_ppa      = uCase(ul.Form("p_sq_acao_ppa"))
   p_sq_orprioridade  = uCase(ul.Form("p_sq_orprioridade"))
   
   P1                 = Nvl(ul.Form("P1"),0)
   P2                 = Nvl(ul.Form("P2"),0)
   P3                 = cDbl(Nvl(ul.Form("P3"),1))
   P4                 = cDbl(Nvl(ul.Form("P4"),conPagesize))
   TP                 = ul.Form("TP")
   R                  = uCase(ul.Form("R"))
   w_Assinatura       = uCase(ul.Form("w_Assinatura"))
   w_SG               = uCase(ul.Form("w_SG"))
   
Else

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
   p_sq_acao_ppa      = uCase(Request("p_sq_acao_ppa"))
   p_sq_orprioridade  = uCase(Request("p_sq_orprioridade"))
   
   P1           = Nvl(Request("P1"),0)
   P2           = Nvl(Request("P2"),0)
   P3           = cDbl(Nvl(Request("P3"),1))
   P4           = cDbl(Nvl(Request("P4"),conPagesize))
   TP           = Request("TP")
   R            = uCase(Request("R"))
   w_Assinatura = uCase(Request("w_Assinatura"))
   w_SG         = uCase(Request("w_SG"))
  

   If SG="ORRECURSO" or SG="ORETAPA" or SG = "ORINTERESS" or SG = "ORAREAS" or SG = "ORRESP" or SG = "ORPANEXO" Then
      If O <> "I" and O <> "E" and Request("w_chave_aux") = "" Then O = "L" End If
   ElseIf SG = "ORENVIO" Then 
      O = "V"
   ElseIf SG = "ORFINANC" and P1 = 5 Then
      O = "L"
      P1 = ""
   ElseIf O = "" Then 
      ' Se for acompanhamento, entra na filtragem
      If P1 = 3 Then O = "P" Else O = "L" End If
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

'If SG <> "ETAPAREC" Then 
'   w_menu         = RetornaMenu(w_cliente, SG) 
'Else
'   w_menu         = RetornaMenu(w_cliente, w_SG) 
'End If

' Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
'If SG <> "ETAPAREC" Then 
'   DB_GetLinkSubMenu RS, Session("p_cliente"), SG
'Else
'   DB_GetLinkSubMenu RS, Session("p_cliente"), w_SG
'End If

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
Set p_sq_acao_ppa = Nothing
Set p_sq_orprioridade = Nothing

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
Set w_pagina      = Nothing
Set w_Disabled    = Nothing
Set w_TP          = Nothing
Set w_Assinatura  = Nothing
Set w_dir         = Nothing
Set w_dir_volta   = Nothing

REM =========================================================================
REM Rotina de visualização resumida dos registros
REM -------------------------------------------------------------------------
Sub Inicial

  Dim w_titulo, w_total, w_parcial

  If O = "L" Then
     If Instr(uCase(R),"GR_") > 0 Then
        w_filtro = ""
        If p_projeto > ""  Then 
           DB_GetSolicData RS, p_projeto, "ORGERAL"
           w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Ação <td><font size=1>[<b>" & RS("titulo") & "</b>]"
        End If
        If p_atividade > ""  Then 
           DB_GetSolicEtapa RS, p_projeto, p_atividade, "REGISTRO"
           w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Etapa <td><font size=1>[<b>" & RS("titulo") & "</b>]"
        End If
        If p_sq_acao_ppa > ""  Then 
           DB_GetAcaoPPA RS, p_sq_acao_ppa, w_cliente, null, null, null, null, null, null, null, null
           w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Ação PPA <td><font size=1>[<b>" & RS("nome") & " (" & RS("codigo") & ")" & "</b>]"
        End If
        If p_sq_orprioridade > ""  Then 
           DB_GetOrPrioridade RS, null, w_cliente, p_sq_orprioridade, null, null, null
           w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Iniciativa Prioritária <td><font size=1>[<b>" & RS("nome") & "</b>]"
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
        'If p_prioridade  > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Prioridade <td><font size=1>[<b>" & RetornaPrioridade(p_prioridade) & "</b>]"   End If
        If p_proponente  > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Parcerias externas<td><font size=1>[<b>" & p_proponente & "</b>]"                      End If
        If p_assunto     > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Assunto <td><font size=1>[<b>" & p_assunto & "</b>]"                            End If
        If p_palavra     > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Parcerias internas <td><font size=1>[<b>" & p_palavra & "</b>]"                     End If
        If p_ini_i       > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Data recebimento <td><font size=1>[<b>" & p_ini_i & "-" & p_ini_f & "</b>]"     End If
        If p_fim_i       > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Limite conclusão <td><font size=1>[<b>" & p_fim_i & "-" & p_fim_f & "</b>]"     End If
        If p_atraso      = "S" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Situação <td><font size=1>[<b>Apenas atrasadas</b>]"                            End If
        If w_filtro > "" Then w_filtro = "<table border=0><tr valign=""top""><td><font size=1><b>Filtro:</b><td nowrap><font size=1><ul>" & w_filtro & "</ul></tr></table>"                    End If
     End If
     
     DB_GetLinkData RS, w_cliente, "ORCAD"
     
     If w_copia > "" Then ' Se for cópia, aplica o filtro sobre todas as demandas visíveis pelo usuário
        DB_GetSolicList RS, RS("sq_menu"), w_usuario, SG, 3, _
           p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
           p_unidade, p_prioridade, p_ativo, p_proponente, _
           p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
           p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, p_projeto, p_atividade, p_sq_acao_ppa, p_sq_orprioridade
     Else
        DB_GetSolicList RS, RS("sq_menu"), w_usuario, SG, P1, _
           p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
           p_unidade, p_prioridade, p_ativo, p_proponente, _
           p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
           p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, p_projeto, p_atividade, p_sq_acao_ppa, p_sq_orprioridade
        Select case Request("p_agrega")
           Case "GRPRRESPATU"
              RS.Filter = "executor <> null"
        End Select
     End If
        
     If p_ordena > "" Then RS.sort = p_ordena Else RS.sort = "fim, prioridade" End If
  End If

  Cabecalho
  ShowHTML "<HEAD>"
  If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=../" & MontaURL("MESA") & """>" End If
  ShowHTML "<TITLE>" & conSgSistema & " - Listagem de ações</TITLE>"
  ScriptOpen "Javascript"
  CheckBranco
  FormataData
  ValidateOpen "Validacao"
  If Instr("CP",O) > 0 Then
     If P1 <> 1 or O = "C" Then ' Se não for cadastramento ou se for cópia
        Validate "p_chave", "Número da ação", "", "", "1", "18", "", "0123456789"
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
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  If w_filtro > "" Then ShowHTML w_filtro End If
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ShowHTML "<tr><td><font size=""2"">"
    If P1 = 1 and w_copia = "" Then ' Se for cadastramento e não for resultado de busca para cópia
       If w_submenu > "" Then
          DB_GetLinkSubMenu RS1, w_cliente, Request("SG")
          ShowHTML "<tr><td><font size=""1"">"
          ShowHTML "    <a accesskey=""I"" class=""SS"" href=""" & w_dir & w_pagina & "Geral&R=" & w_pagina & par & "&O=I&SG=" & RS1("sigla") & "&w_menu=" & w_menu & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;"
          ShowHTML "    <a accesskey=""C"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=C&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>C</u>opiar</a>"
       Else
          ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;"
       End If
    End If
    If Instr(uCase(R),"GR_") = 0 Then
       If w_copia > "" Then ' Se for cópia
          If MontaFiltro("GET") > "" Then
             ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=C&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a>"
          Else
             ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=C&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>F</u>iltrar (Inativo)</a>"
          End If
       Else
          If MontaFiltro("GET") > "" Then
             ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a>"
          Else
             ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>F</u>iltrar (Inativo)</a>"
          End If
       End If
    End If
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td rowspan=2><font size=""1""><b>Nº</font></td>"
    ShowHTML "          <td rowspan=2><font size=""1""><b>Responsável</font></td>"
    ShowHTML "          <td rowspan=2><font size=""1""><b>Executor</font></td>"
    If P1 = 1 or P1 = 2 Then ' Se for cadastramento ou mesa de trabalho
       ShowHTML "          <td rowspan=2><font size=""1""><b>Título</font></td>"
       ShowHTML "          <td colspan=2><font size=""1""><b>Execução</font></td>"
    Else
       ShowHTML "          <td rowspan=2><font size=""1""><b>Parcerias</font></td>"
       ShowHTML "          <td rowspan=2><font size=""1""><b>Título</font></td>"
       ShowHTML "          <td colspan=2><font size=""1""><b>Execução</font></td>"
       ShowHTML "          <td rowspan=2><font size=""1""><b>Valor</font></td>"
       ShowHTML "          <td rowspan=2><font size=""1""><b>Fase atual</font></td>"
    End If
    ShowHTML "          <td rowspan=2><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>De</font></td>"
    ShowHTML "          <td><font size=""1""><b>Até</font></td>"
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
        ShowHTML "        <A class=""HL"" HREF=""" & w_dir & w_pagina & "Visual&R=" & w_pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=2&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exibe as informações deste registro."">" & RS("sq_siw_solicitacao") & "&nbsp;</a>"
        
        ShowHTML "        <td><font size=""1"">" & ExibePessoa("../", w_cliente, RS("solicitante"), TP, RS("nm_solic")) & "</A></td>"
        If Nvl(RS("nm_exec"),"---") > "---" Then
           ShowHTML "        <td><font size=""1"">" & ExibePessoa("../", w_cliente, RS("executor"), TP, RS("nm_exec")) & "</td>"
        Else
           ShowHTML "        <td><font size=""1"">---</td>"
        End IF
        If P1 <> 1 and P1 <> 2 Then ' Se não for cadastramento nem mesa de trabalho
           ShowHTML "        <td><font size=""1"">" & Nvl(RS("proponente"),"---") & "</td>"
        End If
        ' Verifica se foi enviado o parâmetro p_tamanho = N. Se chegou, o assunto deve ser exibido sem corte.
        ' Este parâmetro é enviado pela tela de filtragem das páginas gerenciais
        If Request("p_tamanho") = "N" Then
           ShowHTML "        <td><font size=""1"">" & Nvl(RS("titulo"),"-") & "</td>"
        Else
           If Len(Nvl(RS("titulo"),"-")) > 50 Then w_titulo = Mid(Nvl(RS("titulo"),"-"),1,50) & "..." Else w_titulo = Nvl(RS("titulo"),"-") End If
           If RS("sg_tramite") = "CA" Then
              ShowHTML "        <td ONMOUSEOVER=""popup('" & replace(replace(replace(RS("titulo"), "'", "\'"), """", "\'"),VbCrLf,"\n") & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><strike>" & w_titulo & "</strike></td>"
           Else
              ShowHTML "        <td ONMOUSEOVER=""popup('" & replace(replace(replace(RS("titulo"), "'", "\'"), """", "\'"),VbCrLf,"\n") & "','white')""; ONMOUSEOUT=""kill()""><font size=""1"">" & w_titulo & "</td>"
           End IF
        End If
        ShowHTML "        <td align=""center""><font size=""1"">&nbsp;" & FormataDataEdicao(RS("inicio")) & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">&nbsp;" & FormataDataEdicao(RS("fim")) & "</td>"
        If P1 <> 1 and P1 <> 2 Then ' Se não for cadastramento nem mesa de trabalho
           If RS("sg_tramite") = "AT" Then
              ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(RS("custo_real"),2) & "&nbsp;</td>"
              w_parcial = w_parcial + cDbl(RS("custo_real"))
           Else
              ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(RS("valor"),2) & "&nbsp;</td>"
              w_parcial = w_parcial + cDbl(RS("valor"))
           End If
           ShowHTML "        <td nowrap><font size=""1"">" & RS("nm_tramite") & "</td>"
        End If
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        If P1 <> 3 Then ' Se não for acompanhamento
           If w_copia > "" Then ' Se for listagem para cópia
              DB_GetLinkSubMenu RS1, w_cliente, Request("SG")
              ShowHTML "          <a accesskey=""I"" class=""HL"" href=""" & w_dir & w_pagina & "Geral&R=" & w_pagina & par & "&O=I&SG=" & RS1("sigla") & "&w_menu=" & w_menu & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&w_copia=" & RS("sq_siw_solicitacao") & MontaFiltro("GET") & """>Copiar</a>&nbsp;"
           ElseIf P1 = 1 Then ' Se for cadastramento
              If w_submenu > "" Then
                 ShowHTML "          <A class=""HL"" HREF=""Menu.asp?par=ExibeDocs&O=A&w_chave=" & RS("sq_siw_solicitacao") & "&R=" & w_Pagina & par & "&SG=" & SG & "&TP=" & TP & "&w_documento=Nr. " & RS("sq_siw_solicitacao") & MontaFiltro("GET") & """ title=""Altera as informações cadastrais da ação"" TARGET=""menu"">Alterar</a>&nbsp;"
              Else
                 ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & RS("sq_siw_solicitacao") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Altera as informações cadastrais da ação"">Alterar</A>&nbsp"
              End If
              ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "Excluir&R=" & w_pagina & par & "&O=E&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exclusão da ação."">Excluir</A>&nbsp"
           ElseIf P1 = 2 Then ' Se for execução
              If cDbl(w_usuario) = cDbl(RS("executor")) Then
                 If cDbl(Nvl(RS("solicitante"),0)) = cDbl(w_usuario) or _
                    cDbl(Nvl(RS("titular"),0))     = cDbl(w_usuario) or _
                    cDbl(Nvl(RS("substituto"),0))  = cDbl(w_usuario) or _
                    cDbl(Nvl(RS("tit_exec"),0))    = cDbl(w_usuario) or _
                    cDbl(Nvl(RS("executor"),0))    = cDbl(w_usuario) or _
                    cDbl(Nvl(RS("subst_exec"),0))  = cDbl(w_usuario) _
                 Then
                    ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "AtualizaEtapa&R=" & w_pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Atualiza as metas físicas da ação."" target=""Metas"">Metas</A>&nbsp"
                 End If
                 ' Coloca as operações dependendo do trâmite
                 If RS("sg_tramite") = "EA" Then
                    ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "Anotacao&R=" & w_pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Registra anotações para a ação, sem enviá-la."">Anotar</A>&nbsp"
                    ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "Envio&R=" & w_pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Envia a ação para outro responsável."">Enviar</A>&nbsp"
                 ElseIf RS("sg_tramite") = "EE" Then
                    ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "Anotacao&R=" & w_pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Registra anotações para a ação, sem enviá-la."">Anotar</A>&nbsp"
                    ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "Envio&R=" & w_pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Envia a ação para outro responsável."">Enviar</A>&nbsp"
                    ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "Concluir&R=" & w_pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Conclui a execução da ação."">Concluir</A>&nbsp"
                 End If
              Else
                 ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "AtualizaEtapa&R=" & w_pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Atualiza as metas físicas da ação."" target=""Metas"">Metas</A>&nbsp"
                 ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "Envio&R=" & w_pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Envia a ação para outro responsável."">Enviar</A>&nbsp"
              End If
           End If
        Else
           If cDbl(Nvl(RS("solicitante"),0)) = cDbl(w_usuario) or _
              cDbl(Nvl(RS("titular"),0))     = cDbl(w_usuario) or _
              cDbl(Nvl(RS("substituto"),0))  = cDbl(w_usuario) or _
              cDbl(Nvl(RS("resp_etapa"),0))  > cDbl(0)         or _
              cDbl(Nvl(RS("tit_exec"),0))    = cDbl(w_usuario) or _
              cDbl(Nvl(RS("subst_exec"),0))  = cDbl(w_usuario) _
           Then
              ' Se o usuário for responsável por uma ação, titular/substituto do setor responsável 
              ' ou titular/substituto da unidade executora,
              ' pode enviar.
              If cDbl(Nvl(RS("solicitante"),0)) = cDbl(w_usuario) or _
                 cDbl(Nvl(RS("titular"),0))     = cDbl(w_usuario) or _
                 cDbl(Nvl(RS("substituto"),0))  = cDbl(w_usuario) or _
                 cDbl(Nvl(RS("tit_exec"),0))    = cDbl(w_usuario) or _
                 cDbl(Nvl(RS("subst_exec"),0))  = cDbl(w_usuario) _
              Then
                 ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "envio&R=" & w_pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Envia a ação para outro responsável."">Enviar</A>&nbsp"
              End If
           End If

           ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "AtualizaEtapa&R=" & w_pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Atualiza as metas físicas da ação."" target=""Metas"">Metas</A>&nbsp"

        End If
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend

      If P1 <> 1 and P1 <> 2 Then ' Se não for cadastramento nem mesa de trabalho
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
       MontaBarra w_dir&w_pagina&par&"&R="&R&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG&"&w_copia="&w_copia, RS.PageCount, P3, P4, RS.RecordCount
    Else
       MontaBarra w_dir&w_pagina&par&"&R="&w_pagina&par&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG&"&w_copia="&w_copia, RS.PageCount, P3, P4, RS.RecordCount
    End If
    ShowHTML "</tr>"    
    DesConectaBD     
  ElseIf Instr("CP",O) > 0 Then
    If P1 <> 1 Then 
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ElseIf O = "C" Then ' Se for cópia
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Para selecionar a ação que deseja copiar, informe nos campos abaixo os critérios de seleção e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    End If
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td align=""center"" valign=""top""><table border=0 width=""90%"" cellspacing=0>"
    AbreForm "Form", w_dir & w_pagina & par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    If O = "C" Then ' Se for cópia, cria parâmetro para facilitar a recuperação dos registros
       ShowHTML "<INPUT type=""hidden"" name=""w_copia"" value=""OK"">"
    End If
    If P1 <> 1 or O = "C" Then ' Se não for cadastramento ou se for cópia
       ' Recupera dados da opçãa açãos
       ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
       ShowHTML "      <tr>"
       DB_GetLinkData RS, w_cliente, "ORCAD"
       SelecaoProjeto "Pro<u>j</u>eto:", "J", "Selecione a ação da atividade na relação.", p_projeto, w_usuario, RS("sq_menu"), "p_projeto", "ORLIST", null
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
       SelecaoPessoa "Respo<u>n</u>sável:", "N", "Selecione o responsável pela ação na relação.", p_solicitante, null, "p_solicitante", "USUARIOS"
       SelecaoUnidade "<U>S</U>etor responsável:", "S", null, p_unidade, null, "p_unidade", null, null
       ShowHTML "      <tr valign=""top"">"
       SelecaoPessoa "Responsável atua<u>l</u>:", "L", "Selecione o responsável atual pela ação na relação.", p_usu_resp, null, "p_usu_resp", "USUARIOS"
       SelecaoUnidade "<U>S</U>etor atual:", "S", "Selecione a unidade onde a ação se encontra na relação.", p_uorg_resp, null, "p_uorg_resp", null, null
       ShowHTML "      <tr>"
       SelecaoPais "<u>P</u>aís:", "P", null, p_pais, null, "p_pais", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_regiao'; document.Form.submit();"""
       SelecaoRegiao "<u>R</u>egião:", "R", null, p_regiao, p_pais, "p_regiao", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_uf'; document.Form.submit();"""
       ShowHTML "      <tr>"
       SelecaoEstado "E<u>s</u>tado:", "S", null, p_uf, p_pais, "N", "p_uf", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_cidade'; document.Form.submit();"""
       SelecaoCidade "<u>C</u>idade:", "C", null, p_cidade, p_pais, p_uf, "p_cidade", null, null
       ShowHTML "      <tr>"
       SelecaoPrioridade "<u>P</u>rioridade:", "P", "Informe a prioridade desta ação.", p_prioridade, null, "p_prioridade", null, null
       ShowHTML "          <td valign=""top""><font size=""1""><b>Propo<U>n</U>ente externo:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_proponente"" size=""25"" maxlength=""90"" value=""" & p_proponente & """></td>"
       ShowHTML "      <tr>"
       ShowHTML "          <td valign=""top""><font size=""1""><b>A<U>s</U>sunto:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_assunto"" size=""25"" maxlength=""90"" value=""" & p_assunto & """></td>"
       ShowHTML "          <td valign=""top"" colspan=2><font size=""1""><b>Pala<U>v</U>ras-chave:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_palavra"" size=""25"" maxlength=""90"" value=""" & p_palavra & """></td>"
       ShowHTML "      <tr>"
       ShowHTML "          <td valign=""top""><font size=""1""><b>Data de re<u>c</u>ebimento entre:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""p_ini_i"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_ini_i & """ onKeyDown=""FormataData(this,event);""> e <input " & w_Disabled & " accesskey=""C"" type=""text"" name=""p_ini_f"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_ini_f & """ onKeyDown=""FormataData(this,event);""></td>"
       ShowHTML "          <td valign=""top""><font size=""1""><b>Limi<u>t</u>e para conclusão entre:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""p_fim_i"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_fim_i & """ onKeyDown=""FormataData(this,event);""> e <input " & w_Disabled & " accesskey=""T"" type=""text"" name=""p_fim_f"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_fim_f & """ onKeyDown=""FormataData(this,event);""></td>"
       If O <> "C" Then ' Se não for cópia
          ShowHTML "      <tr>"
          ShowHTML "          <td valign=""top""><font size=""1""><b>Exibe somente ações em atraso?</b><br>"
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
  Rodape

  Set w_titulo = Nothing
End Sub
REM =========================================================================
REM Fim da tabela de ações
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina dos dados gerais
REM -------------------------------------------------------------------------
Sub Geral
  Dim w_sq_unidade_resp, w_titulo, w_prioridade, w_aviso, w_dias
  Dim w_inicio_real, w_fim_real, w_concluida, w_proponente
  Dim w_data_conclusao, w_nota_conclusao, w_custo_real, w_sqcc
  Dim w_sq_acao_ppa, w_sq_orprioridade, w_selecionada_mpog, w_selecionada_relevante
  
  Dim w_chave, w_chave_pai, w_chave_aux, w_sq_menu, w_sq_unidade
  Dim w_sq_tramite, w_solicitante, w_cadastrador, w_executor
  Dim w_inicio, w_fim, w_inclusao, w_ultima_alteracao
  Dim w_conclusao, w_valor, w_opiniao, w_data_hora, w_pais, w_uf, w_cidade, w_palavra_chave
  Dim w_descricao, w_justificativa
  
  Dim w_troca, i, w_erro, w_como_funciona, w_cor, w_readonly

  w_chave           = Request("w_chave")
  w_readonly        = ""
  w_erro            = ""
  w_troca           = Request("w_troca")

  ' Verifica se há necessidade de recarregar os dados da tela a partir
  ' da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  If w_troca > "" Then ' Se for recarga da página
     w_proponente              = Request("w_proponente") 
     w_sq_unidade_resp         = Request("w_sq_unidade_resp") 
     w_titulo                  = Request("w_titulo") 
     w_prioridade              = Request("w_prioridade") 
     w_aviso                   = Request("w_aviso") 
     w_dias                    = Request("w_dias") 
     w_inicio_real             = Request("w_inicio_real") 
     w_fim_real                = Request("w_fim_real") 
     w_concluida               = Request("w_concluida") 
     w_data_conclusao          = Request("w_data_conclusao") 
     w_nota_conclusao          = Request("w_nota_conclusao") 
     w_custo_real              = Request("w_custo_real") 
  
     w_chave                   = Request("w_chave") 
     w_chave_pai               = Request("w_chave_pai") 
     w_chave_aux               = Request("w_chave_aux") 
     w_sq_menu                 = Request("w_sq_menu") 
     w_sq_unidade              = Request("w_sq_unidade") 
     w_sq_tramite              = Request("w_sq_tramite") 
     w_solicitante             = Request("w_solicitante") 
     w_cadastrador             = Request("w_cadastrador") 
     w_executor                = Request("w_executor") 
     w_inicio                  = Request("w_inicio") 
     w_fim                     = Request("w_fim") 
     w_inclusao                = Request("w_inclusao") 
     w_ultima_alteracao        = Request("w_ultima_alteracao") 
     w_conclusao               = Request("w_conclusao") 
     w_valor                   = Request("w_valor") 
     w_opiniao                 = Request("w_opiniao") 
     w_data_hora               = Request("w_data_hora") 
     w_pais                    = Request("w_pais") 
     w_uf                      = Request("w_uf") 
     w_cidade                  = Request("w_cidade") 
     w_palavra_chave           = Request("w_palavra_chave") 
     w_sqcc                    = Request("w_sqcc") 
     w_sq_acao_ppa             = Request("w_sq_acao_ppa") 
     w_sq_orprioridade         = Request("w_sq_orprioridade")
     w_descricao               = Request("w_descricao")
     w_justificativa           = Request("w_justificativa")
     If w_sq_acao_ppa > "" Then
        DB_GetAcaoPPA RS, w_sq_acao_ppa, w_cliente, null, null, null, null, null, null, null, null
        w_selecionada_mpog        = RS("selecionada_mpog")
        w_selecionada_relevante   = RS("selecionada_relevante")
        w_titulo                  = RS("nome")
     ElseIf w_sq_orprioridade > "" Then
        DB_GetOrPrioridade RS, null , w_cliente, w_sq_orprioridade, null, null, null
        w_titulo                  = RS("nome")
     End If
  Else
     If InStr("AEV",O) > 0 or w_copia > "" Then
        ' Recupera os dados da ação
        If w_copia > "" Then
           DB_GetSolicData RS, w_copia, SG
        Else
           DB_GetSolicData RS, w_chave, SG
        End If
        If RS.RecordCount > 0 Then 
           w_proponente             = RS("proponente") 
           w_sq_unidade_resp        = RS("sq_unidade_resp") 
           w_titulo                 = RS("titulo") 
           w_prioridade             = RS("prioridade") 
           w_aviso                  = RS("aviso_prox_conc") 
           w_dias                   = RS("dias_aviso") 
           w_inicio_real            = RS("inicio_real") 
           w_fim_real               = RS("fim_real") 
           w_concluida              = RS("concluida") 
           w_data_conclusao         = RS("data_conclusao") 
           w_nota_conclusao         = RS("nota_conclusao") 
           w_custo_real             = RS("custo_real") 
  
           w_chave_pai              = RS("sq_solic_pai") 
           w_chave_aux              = null
           w_sq_menu                = RS("sq_menu") 
           w_sq_unidade             = RS("sq_unidade") 
           w_sq_tramite             = RS("sq_siw_tramite") 
           w_solicitante            = RS("solicitante") 
           w_cadastrador            = RS("cadastrador") 
           w_executor               = RS("executor") 
           w_inicio                 = FormataDataEdicao(RS("inicio"))
           w_fim                    = FormataDataEdicao(RS("fim"))
           w_inclusao               = RS("inclusao") 
           w_ultima_alteracao       = RS("ultima_alteracao") 
           w_conclusao              = RS("conclusao") 
           w_valor                  = FormatNumber(RS("valor"),2)
           w_opiniao                = RS("opiniao") 
           w_data_hora              = RS("data_hora") 
           w_sqcc                   = RS("sq_cc") 
           w_sq_acao_ppa            = RS("sq_acao_ppa")
           w_sq_orprioridade        = RS("sq_orprioridade")
           w_selecionada_mpog       = RS("mpog_ppa")
           w_selecionada_relevante  = RS("relev_ppa")
           w_pais                   = RS("sq_pais") 
           w_uf                     = RS("co_uf") 
           w_cidade                 = RS("sq_cidade_origem") 
           w_palavra_chave          = RS("palavra_chave") 
           w_descricao              = RS("descricao")
           w_justificativa          = RS("justificativa")  
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
     'Validate "w_titulo", "Ação", "1", 1, 5, 100, "1", "1"
     If RS_menu("solicita_cc") = "S" Then
        Validate "w_sqcc", "Classificação", "SELECT", 1, 1, 18, "", "0123456789"
     End If
     'Validate "w_sq_orprioridade", "Iniciativa prioritária", "SELECT", "", 1, 18, "", "0123456789"
     'Validate "w_sq_acao_ppa", "Ação PPA", "SELECT", "", 1, 18, "", "0123456789"
     ShowHTML "  if (theForm.w_sq_acao_ppa.selectedIndex==0 && theForm.w_sq_orprioridade.selectedIndex==0) {"
     ShowHTML "     alert('Informe a iniciativa prioritária e/ou a ação do PPA!');"
     ShowHTML "     theForm.w_sq_orprioridade.focus();"
     ShowHTML "     return false;"
     ShowHTML "  }"
     Validate "w_solicitante", "Responsável monitoramento", "HIDDEN", 1, 1, 18, "", "0123456789"
     Validate "w_sq_unidade_resp", "Setor responsável", "HIDDEN", 1, 1, 18, "", "0123456789"
     Select Case RS_menu("data_hora")
        Case 1
           Validate "w_fim", "Fim previsto", "DATA", 1, 10, 10, "", "0123456789/"
        Case 2
           Validate "w_fim", "Fim previsto", "DATAHORA", 1, 17, 17, "", "0123456789/"
        Case 3
           Validate "w_inicio", "Início previsto", "DATA", 1, 10, 10, "", "0123456789/"
           Validate "w_fim", "Fim previsto", "DATA", 1, 10, 10, "", "0123456789/"
           CompData "w_inicio", "Data de recebimento", "<=", "w_fim", "Limite para conclusão"
        Case 4
           Validate "w_inicio", "Início previsto", "DATAHORA", 1, 17, 17, "", "0123456789/,: "
           Validate "w_fim", "Fim previsto", "DATAHORA", 1, 17, 17, "", "0123456789/,: "
           CompData "w_inicio", "Início previsto", "<=", "w_fim", "Limite para conclusão"
     End Select
     Validate "w_valor", "Recurso programado", "VALOR", "1", 4, 18, "", "0123456789.,"
     Validate "w_proponente", "Parcerias externas", "", "", 2, 90, "1", "1"
     Validate "w_palavra_chave", "Parcerias internas", "", "", 2, 90, "1", "1"
     'Validate "w_pais", "País", "SELECT", 1, 1, 18, "", "0123456789"
     'Validate "w_uf", "Estado", "SELECT", 1, 1, 3, "1", "1"
     'Validate "w_cidade", "Cidade", "SELECT", 1, 1, 18, "", "0123456789"
     'Validate "w_dias", "Dias de alerta", "1", "", 1, 2, "", "0123456789"
     'ShowHTML "  if (theForm.w_aviso[0].checked) {"
     'ShowHTML "     if (theForm.w_dias.value == '') {"
     'ShowHTML "        alert('Informe a partir de quantos dias antes da data limite você deseja ser avisado de sua proximidade!');"
     'ShowHTML "        theForm.w_dias.focus();"
     'ShowHTML "        return false;"
     'ShowHTML "     }"
     'ShowHTML "  }"
     'ShowHTML "  else {"
     'ShowHTML "     theForm.w_dias.value = '';"
     'ShowHTML "  }"
  End If
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf Instr("EV",O) > 0 Then
     BodyOpen "onLoad='document.focus()';"
  Else
     BodyOpen "onLoad='document.Form.w_titulo.focus()';"
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

    AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
    ShowHTML MontaFiltro("POST")
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_copia"" value=""" & w_copia &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_data_hora"" value=""" & RS_menu("data_hora") &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & RS_menu("sq_menu") &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_prioridade"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_aviso"" value=""S"">"
    ShowHTML "<INPUT type=""hidden"" name=""w_descricao"" value=""" & w_descricao & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_justificativa"" value=""" & w_justificativa & """>"
    'Passagem da cidade padrão como brasília, pelo retidara do impacto geográfico da tela
    DB_GetCustomerData RS, w_cliente
    ShowHTML "<INPUT type=""hidden"" name=""w_cidade"" value=""" & RS("sq_cidade_padrao") &""">"
    DesconectaBD

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td valign=""top"" align=""center"" bgcolor=""#D0D0D0""><font size=""2""><b>Identificação</td></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td><font size=1>Os dados deste bloco serão utilizados para identificação da ação, bem como para o controle de sua execução.</font></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    If w_sq_acao_ppa > "" Then
       ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>A</u>ção:</b><br><INPUT READONLY ACCESSKEY=""A"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_titulo"" size=""90"" maxlength=""100"" value=""" & w_titulo & """ ></td>"
    Else
       ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>A</u>ção:</b><br><INPUT ACCESSKEY=""A"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_titulo"" size=""90"" maxlength=""100"" value=""" & w_titulo & """ ></td>"
    End If
    If RS_menu("solicita_cc") = "S" Then
       ShowHTML "          <tr>"
       SelecaoCC "C<u>l</u>assificação:", "L", "Selecione um dos itens relacionados.", w_sqcc, null, "w_sqcc", "SIWSOLIC"
       ShowHTML "          </tr>"
    End If
    ShowHTML "          <tr>"
    SelecaoOrPrioridade "<u>I</u>niciativa prioritária:", "I", null, w_sq_orprioridade, null, "w_sq_orprioridade", "VINCULACAO", "onchange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_sq_acao_ppa'; document.Form.submit();"""
    ShowHTML "          </tr>"
    ShowHTML "          <tr>"
    If O = "I" or w_sq_acao_ppa = "" Then
       SelecaoAcaoPPA "Ação <u>P</u>PA:", "P", null, w_sq_acao_ppa, null, "w_sq_acao_ppa", "IDENTIFICACAO", "onchange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_solicitante'; document.Form.submit();"""
    Else
       SelecaoAcaoPPA "Ação <u>P</u>PA:", "P", null, w_sq_acao_ppa, null, "w_sq_acao_ppa", null, "disabled"
       ShowHTML "<INPUT type=""hidden"" name=""w_sq_acao_ppa"" value=""" & w_sq_acao_ppa &""">"
    End If
    ShowHTML "          </tr>"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    MontaRadioNS "<b>Selecionada pelo MP?</b>", w_selecionada_mpog, "w_selecionada_mpog"
    MontaRadioNS "<b>SE/MS?</b>", w_selecionada_relevante, "w_selecionada_relevante"
    ShowHTML "          </table>"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    SelecaoPessoa "Respo<u>n</u>sável monitoramento:", "N", "Selecione o responsável pelo monitoramento da ação na relação.", w_solicitante, null, "w_solicitante", "USUARIOS"
    SelecaoUnidade "<U>S</U>etor responsável monitoramento:", "S", "Selecione o setor responsável pelo monitoramento da execução da ação", w_sq_unidade_resp, null, "w_sq_unidade_resp", null, null
    'SelecaoPrioridade "<u>P</u>rioridade:", "P", "Informe a prioridade desta ação.", w_prioridade, null, "w_prioridade", null, null
    ShowHTML "          </table>"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "          <tr>"
    Select Case RS_menu("data_hora")
       Case 1
          ShowHTML "              <td valign=""top""><font size=""1""><b><u>F</u>im previsto:</b><br><input " & w_Disabled & " accesskey=""F"" type=""text"" name=""w_fim"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_fim & """ onKeyDown=""FormataData(this,event);""></td>"
       Case 2
          ShowHTML "              <td valign=""top""><font size=""1""><b><u>F</u>im previsto:</b><br><input " & w_Disabled & " accesskey=""F"" type=""text"" name=""w_fim"" class=""STI"" SIZE=""17"" MAXLENGTH=""17"" VALUE=""" & w_fim & """ onKeyDown=""FormataDataHora(this,event);""></td>"
       Case 3
          ShowHTML "              <td valign=""top""><font size=""1""><b>Iní<u>c</u>io previsto:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_inicio"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & Nvl(w_inicio,FormataDataEdicao(Date())) & """ onKeyDown=""FormataData(this,event);""></td>"
          ShowHTML "              <td valign=""top""><font size=""1""><b><u>F</u>im previsto:</b><br><input " & w_Disabled & " accesskey=""F"" type=""text"" name=""w_fim"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_fim & """ onKeyDown=""FormataData(this,event);""></td>"
       Case 4
          ShowHTML "              <td valign=""top""><font size=""1""><b>Iní<u>c</u>io previsto:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_inicio"" class=""STI"" SIZE=""17"" MAXLENGTH=""17"" VALUE=""" & w_inicio & """ onKeyDown=""FormataDataHora(this,event);""></td>"
          ShowHTML "              <td valign=""top""><font size=""1""><b><u>F</u>im previsto:</b><br><input " & w_Disabled & " accesskey=""F"" type=""text"" name=""w_fim"" class=""STI"" SIZE=""17"" MAXLENGTH=""17"" VALUE=""" & w_fim & """ onKeyDown=""FormataDataHora(this,event);""></td>"
    End Select
    ShowHTML "              <td valign=""top""><font size=""1""><b><u>R</u>ecurso programado:</b><br><input " & w_Disabled & " accesskey=""O"" type=""text"" name=""w_valor"" class=""STI"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_valor & """ onKeyDown=""FormataValor(this,18,2,event);""></td>"
    ShowHTML "          </table>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Parc<u>e</u>rias externas:<br><INPUT ACCESSKEY=""E"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_proponente"" size=""90"" maxlength=""90"" value=""" & w_proponente & """ ONMOUSEOVER=""popup('Parceria externa da ação. Preencha apenas se houver.','white')""; ONMOUSEOUT=""kill()""></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>P</u>arcerias internas:<br><INPUT ACCESSKEY=""P"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_palavra_chave"" size=""90"" maxlength=""90"" value=""" & w_palavra_chave & """ ONMOUSEOVER=""popup('Se desejar, informe palavras-chave adicionais aos campos informados e que permitam a identificação desta ação.','white')""; ONMOUSEOUT=""kill()""></td>"
    'ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
    'ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    'ShowHTML "      <tr><td valign=""top"" align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Impacto geográfico</td></td></tr>"
    'ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    'ShowHTML "      <tr><td><font size=1>Os dados deste bloco identificam o local onde a ação causará efeito. Se abrangência nacional, indique Brasília-DF. Se abrangência estadual, indique a capital do estado.</font></td></tr>"
    'ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    'ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    'ShowHTML "      <tr>"
    'SelecaoPais "<u>P</u>aís:", "P", null, w_pais, null, "w_pais", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_uf'; document.Form.submit();"""
    'SelecaoEstado "E<u>s</u>tado:", "S", null, w_uf, w_pais, "N", "w_uf", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_cidade'; document.Form.submit();"""
    'SelecaoCidade "<u>C</u>idade:", "C", null, w_cidade, w_pais, w_uf, "w_cidade", null, null
    'ShowHTML "          </table>"
    'ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
    'ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    'ShowHTML "      <tr><td valign=""top"" align=""center"" bgcolor=""#D0D0D0""><font size=""2""><b>Alerta de atraso</td></td></tr>"
    'ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    'ShowHTML "      <tr><td><font size=1>Os dados abaixo indicam como deve ser tratada a proximidade da data limite para conclusão da ação.</font></td></tr>"
    'ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    'ShowHTML "      <tr><td><table border=""0"" width=""100%"">"
    'ShowHTML "          <tr>"
    'MontaRadioNS "<b>Emite alerta?</b>", w_aviso, "w_aviso"
    'ShowHTML "              <td valign=""top""><font size=""1""><b>Quantos <U>d</U>ias antes da data limite?<br><INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_dias"" size=""2"" maxlength=""2"" value=""" & w_dias & """ ONMOUSEOVER=""popup('Número de dias para emissão do alerta de proximidade da data limite para conclusão da ação.','white')""; ONMOUSEOUT=""kill()""></td>"
    'ShowHTML "          </table>"
    'ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000""></TD></TR>"

    ' Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
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

  Set w_selecionada_mpog        = Nothing 
  Set w_selecionada_relevante   = Nothing 
  Set w_sq_acao_ppa             = Nothing 
  Set w_sq_orprioridade         = Nothing 
  Set w_proponente              = Nothing 
  Set w_sq_unidade_resp         = Nothing 
  Set w_titulo                  = Nothing 
  Set w_prioridade              = Nothing 
  Set w_aviso                   = Nothing 
  Set w_dias                    = Nothing 
  Set w_inicio_real             = Nothing 
  Set w_fim_real                = Nothing 
  Set w_concluida               = Nothing 
  Set w_data_conclusao          = Nothing 
  Set w_nota_conclusao          = Nothing 
  Set w_custo_real              = Nothing 
  
  Set w_chave                   = Nothing 
  Set w_chave_pai               = Nothing 
  Set w_chave_aux               = Nothing 
  Set w_sq_menu                 = Nothing 
  Set w_sq_unidade              = Nothing 
  Set w_sq_tramite              = Nothing 
  Set w_solicitante             = Nothing 
  Set w_cadastrador             = Nothing 
  Set w_executor                = Nothing 
  Set w_inicio                  = Nothing 
  Set w_fim                     = Nothing 
  Set w_inclusao                = Nothing 
  Set w_ultima_alteracao        = Nothing 
  Set w_conclusao               = Nothing 
  Set w_valor                   = Nothing 
  Set w_opiniao                 = Nothing 
  Set w_data_hora               = Nothing 
  Set w_sqcc                    = Nothing 
  Set w_pais                    = Nothing 
  Set w_uf                      = Nothing 
  Set w_cidade                  = Nothing 
  Set w_palavra_chave           = Nothing 
  
  Set w_troca                   = Nothing 
  Set i                         = Nothing 
  Set w_erro                    = Nothing 
  Set w_como_funciona           = Nothing 
  Set w_cor                     = Nothing 

End Sub
REM =========================================================================
REM Fim da rotina de dados gerais
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina das informações adicionais
REM -------------------------------------------------------------------------
Sub InfoAdic
  
  Dim w_chave, w_chave_pai, w_chave_aux, w_sq_menu, w_sq_unidade
  
  Dim w_descricao, w_justificativa, w_ds_acao, w_problema, w_publico_alvo
  Dim w_estrategia, w_indicadores, w_objetivo
  
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
     w_descricao               = Request("w_descricao") 
     w_justificativa           = Request("w_justificativa") 
     w_ds_acao                 = Request("w_ds_acao")
     w_problema                = Request("w_problema") 
     w_publico_alvo            = Request("w_publico_alvo") 
     w_estrategia              = Request("w_estrategia")  
     w_indicadores             = Request("w_indicadores")
     w_objetivo                = Request("w_objetivo")
  Else
     If InStr("AEV",O) > 0 or w_copia > "" Then
        ' Recupera os dados da ação
        If w_copia > "" Then
           DB_GetSolicData RS, w_copia, SG
        Else
           DB_GetSolicData RS, w_chave, SG
        End If
        If RS.RecordCount > 0 Then 
           w_chave_pai              = RS("sq_solic_pai") 
           w_chave_aux              = null
           w_sq_menu                = RS("sq_menu") 
           w_sq_unidade             = RS("sq_unidade") 
           w_descricao              = RS("descricao") 
           w_justificativa          = RS("justificativa") 
           w_ds_acao                = RS("ds_acao")
           w_problema               = RS("problema")
           w_publico_alvo           = RS("publico_alvo")
           w_estrategia             = RS("estrategia")
           w_indicadores            = RS("indicadores")
           w_objetivo               = RS("objetivo")
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
     Validate "w_problema", "Situação problema", "1", "", 5, 2000, "1", "1"
     Validate "w_objetivo", "Objetivo da ação", "1", "", 5, 2000, "1", "1"
     Validate "w_ds_acao", "Descrição da ação", "1", "", 5, 2000, "1", "1"
     Validate "w_publico_alvo", "Publico alvo", "1", "", 5, 2000, "1", "1"
     If RS_menu("descricao") = "S" Then
        Validate "w_descricao", "Resultados da ação", "1", "", 5, 2000, "1", "1"
     End If
     Validate "w_estrategia", "Estratégia de implantação", "1", "", 5, 2000, "1", "1"
     Validate "w_indicadores", "Indicadores de desempenho", "1", "", 5, 2000, "1", "1"
     If RS_menu("justificativa") = "S" Then
        Validate "w_justificativa", "Observações", "1", "", 5, 2000, "1", "1"
     End If
  End If
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
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<table align=""center"" border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If Instr("IAEV",O) > 0 Then  
    If InStr("EV",O) Then
       w_Disabled = " DISABLED "
       If O = "V" Then
          w_Erro = Validacao(w_sq_solicitacao, sg)
       End If
    End If

    AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
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
    ShowHTML "      <tr><td valign=""top"" align=""center"" bgcolor=""#D0D0D0""><font size=""2""><b>Informações adicionais</td></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td><font size=1>Os dados deste bloco visam orientar os executores da ação.</font></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Situação <u>p</u>roblema:</b><br><textarea " & w_Disabled & " accesskey=""P"" name=""w_problema"" class=""STI"" ROWS=5 cols=75 ONMOUSEOVER=""popup('Destacar os elementos essenciais que explicam a situação-problema (determinantes/causas).','white')""; ONMOUSEOUT=""kill()"">" & w_problema & "</TEXTAREA></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>O</u>bjetivo da ação:</b><br><textarea " & w_Disabled & " accesskey=""O"" name=""w_objetivo"" class=""STI"" ROWS=5 cols=75 ONMOUSEOVER=""popup('Descreva o objetivo a ser alcançado com a execução desta ação.','white')""; ONMOUSEOUT=""kill()"">" & w_objetivo & "</TEXTAREA></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>D</u>escrição da ação:</b><br><textarea " & w_Disabled & " accesskey=""D"" name=""w_ds_acao"" class=""STI"" ROWS=5 cols=75 ONMOUSEOVER=""popup('Destacar os elementos essenciais que compõem e explicam a ação (tarefas).','white')""; ONMOUSEOUT=""kill()"">" & w_ds_acao & "</TEXTAREA></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Pú<u>b</u>lico alvo :</b><br><textarea " & w_Disabled & " accesskey=""B"" name=""w_publico_alvo"" class=""STI"" ROWS=5 cols=75 ONMOUSEOVER=""popup('Especifique os segmentos da sociedade aos quais o programa se destina e que se beneficiam direta e legitimamente com sua execução. Exemplos: crianças desnutridas de 6 a 23 meses de idade; gestantes de risco nutricional; grupos vulnerávei e os obesos.','white')""; ONMOUSEOUT=""kill()"">" & w_publico_alvo & "</TEXTAREA></td>"
    If RS_menu("descricao") = "S" Then
       ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Res<u>u</u>ltados da ação:</b><br><textarea " & w_Disabled & " accesskey=""U"" name=""w_descricao"" class=""STI"" ROWS=5 cols=75 ONMOUSEOVER=""popup('Indicar os principais resultados qeu se pretende alcançar nos sistemas de gestão e na saúde da população em consequência da execução da ação.','white')""; ONMOUSEOUT=""kill()"">" & w_descricao & "</TEXTAREA></td>"
    End If
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>E</u>estrategia de implantação:</b><br><textarea " & w_Disabled & " accesskey=""E"" name=""w_estrategia"" class=""STI"" ROWS=5 cols=75 ONMOUSEOVER=""popup('Indicar os meios a empregar ou métodos a seguir com a finalidade de implementar a ação. Relacionar mecanismos e instrumentos disponíveis ou a serem constituídos e a forma de execução. Relacionar as parcerias e responsabilidades e os mecanismos utilizados no monitoramento.','white')""; ONMOUSEOUT=""kill()"">" & w_estrategia & "</TEXTAREA></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>I</u>ndicadores de desempenho:</b><br><textarea " & w_Disabled & " accesskey=""I"" name=""w_indicadores"" class=""STI"" ROWS=5 cols=75 ONMOUSEOVER=""popup('Indicar os parâmetros que medem a diferença entre a situação atual e a situação desejada. É geralmente apresentado como uma relação ou taxa entre variáveis relevantes para quantificar o processo ou os resultados alcançados com a execução da ação. Mede o trabalho realizado.','white')""; ONMOUSEOUT=""kill()"">" & w_indicadores & "</TEXTAREA></td>"
    If RS_menu("justificativa") = "S" Then
       ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Obse<u>r</u>vações:</b><br><textarea " & w_Disabled & " accesskey=""R"" name=""w_justificativa"" class=""STI"" ROWS=5 cols=75 ONMOUSEOVER=""popup('Informar fatos ou situações que sejam relevantes para uma melhor compreensão da ação e/ou descrever situações que não tenham sido descritas em outros campos do formulário e que devam ser consideradas para a viabilidade da mesma. Indicar as fragilidades já identificadas.','white')""; ONMOUSEOUT=""kill()"">" & w_justificativa & "</TEXTAREA></td>"
    End If
    
    ' Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
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

  Set w_chave                   = Nothing 
  Set w_chave_pai               = Nothing 
  Set w_chave_aux               = Nothing 
  Set w_sq_menu                 = Nothing 
  Set w_descricao               = Nothing 
  Set w_justificativa           = Nothing 
  Set w_ds_acao                 = Nothing
  Set w_problema                = Nothing
  Set w_publico_alvo            = Nothing
  Set w_estrategia              = Nothing
  Set w_indicadores             = Nothing
  Set w_objetivo                = Nothing
  
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
REM Rotina das outras iniciativas
REM -------------------------------------------------------------------------
Sub Iniciativas
  
  Dim w_chave, w_chave_pai, w_chave_aux, w_sq_menu, w_sq_unidade
  
  Dim w_outras_iniciativas, w_nm_ppa_pai, w_cd_ppa_pai, w_nm_ppa, w_cd_ppa, w_nm_pri, w_cd_pri
  Dim w_sq_orprioridade
  
  Dim w_troca, i, w_erro, w_como_funciona, w_cor, w_readonly

  w_chave           = Request("w_chave")
  w_readonly        = ""
  w_erro            = ""
  w_troca           = Request("w_troca")

  
  DB_GetSolicData RS, w_chave, SG
  If RS.RecordCount > 0 Then 
     w_chave_pai              = RS("sq_solic_pai") 
     w_chave_aux              = null
     w_sq_menu                = RS("sq_menu") 
     w_sq_unidade             = RS("sq_unidade") 
     w_nm_ppa_pai             = RS("nm_ppa_pai")
     w_cd_ppa_pai             = RS("cd_ppa_pai")
     w_nm_ppa                 = RS("nm_ppa")
     w_cd_ppa                 = RS("cd_ppa")
     w_nm_pri                 = RS("nm_pri")
     w_cd_pri                 = RS("cd_pri")
     w_sq_orprioridade        = RS("sq_orprioridade")
     DesconectaBD
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    ShowHTML " history.back(1);"
    ScriptClose
  End If
  If cDbl(Nvl(w_sq_orprioridade,0)) = 0 Then
    ScriptOpen "JavaScript"
    ShowHTML " alert('Para inserir outras iniciativas, cadastre a iniciativa prioritária primeiro!');"
    ShowHTML " history.back(1);"
    ScriptClose
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
  ShowHTML "  theForm.Botao.disabled=true;"
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
 
  BodyOpen "onLoad='document.Form.focus()';"
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<table align=""center"" border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  
  AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
  ShowHTML MontaFiltro("POST")
  ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave &""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_data_hora"" value=""" & RS_menu("data_hora") &""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & RS_menu("sq_menu") &""">"

  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "    <table width=""97%"" border=""0"">"
  ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
  ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
  ShowHTML "      <tr><td valign=""top"" align=""center"" bgcolor=""#D0D0D0""><font size=""2""><b>Outras iniciativas</td></td></tr>"
  ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
  ShowHTML "      <tr><td><font size=1>Os dados deste bloco visa informar as outras iniciativas da ação.</font></td></tr>"
  ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
  If w_cd_ppa > "" Then
     ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Programa PPA: </b><br>" &w_cd_ppa_pai& " - " & w_nm_ppa_pai & " </b>"
     ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Ação PPA: </b><br>" &w_cd_ppa& " - " & w_nm_ppa & " </b>"      
  End If
  If w_sq_orprioridade > "" Then
     ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Iniciativa prioritária: </b><br>" & w_nm_pri & " </b>"      
  End If
  
  DB_GetOrPrioridadeList RS, w_chave, w_cliente, w_sq_orprioridade
  ShowHTML "      <tr><td valign=""top""><br>"
  ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Selecione outras iniciativas prioritárias as quais a ação está relacionada:</b>"  
  While Not RS.EOF  
     If cDbl(Nvl(RS("Existe"),0)) > 0 Then
        ShowHTML "      <tr><td valign=""top""><font size=""1"">&nbsp;&nbsp;&nbsp;<input type=""checkbox"" name=""w_outras_iniciativas"" value="""&RS("chave")&""" checked>" &RS("nome")& "</td>"
     Else
        ShowHTML "      <tr><td valign=""top""><font size=""1"">&nbsp;&nbsp;&nbsp;<input type=""checkbox"" name=""w_outras_iniciativas"" value="""&RS("chave")&""">" &RS("nome")& "</td>"
     End If
     RS.MoveNext
  wend  
  ShowHTML "      <tr><td align=""center"" colspan=""3"">"
  ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Gravar"">"
  ShowHTML "          </td>"
  ShowHTML "      </tr>"
  ShowHTML "    </table>"
  ShowHTML "    </TD>"
  ShowHTML "</tr>"
  ShowHTML "</FORM>"
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_chave                   = Nothing 
  Set w_chave_pai               = Nothing 
  Set w_chave_aux               = Nothing 
  Set w_sq_menu                 = Nothing 
  Set w_outras_iniciativas      = Nothing 
  
  Set w_troca                   = Nothing 
  Set i                         = Nothing 
  Set w_erro                    = Nothing 
  Set w_como_funciona           = Nothing 
  Set w_cor                     = Nothing 

End Sub
REM =========================================================================
REM Fim da rotina de outras iniciativas
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de financiamento
REM -------------------------------------------------------------------------
Sub Financiamento
  Dim w_chave, w_chave_pai, w_chave_aux, w_sq_acao_ppa, w_obs_financ
  
  Dim w_troca, i, w_erro
  
  w_Chave           = Request("w_Chave")
  w_chave_aux       = Request("w_chave_aux")
  w_troca           = Request("w_troca")

  If w_troca > "" Then ' Se for recarga da página
     w_sq_acao_ppa = Request("w_sq_acao_ppa")   
     w_obs_financ   = Request("w_obs_financ")
  ElseIf O = "L" Then
     ' Recupera todos os registros para a listagem
     DB_GetFinancAcaoPPA RS, w_chave, w_cliente, null
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
     ' Recupera os dados do financiamento
     DB_GetFinancAcaoPPA RS, w_chave, w_cliente, Request("w_sq_acao_ppa")
     w_sq_acao_ppa = RS("sq_acao_ppa")
     w_obs_financ  = RS("observacao")
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
        Validate "w_sq_acao_ppa", "Ação PPA", "SELECT", "1", "1", "10", "", "1"
        Validate "w_obs_financ", "Observações", "1", "", 5, 2000, "1", "1"
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
     BodyOpen "onLoad='document.Form.w_sq_acao_ppa.focus()';"
  Else
     BodyOpen "onLoad='document.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    AbreSessao
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Código</font></td>"
    ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & RS("cd_ppa_pai")& "." & RS("cd_ppa") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("nome") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_pagina & par & "&O=A&w_chave=" & w_chave & "&w_sq_acao_ppa=" &RS("sq_acao_ppa")&"&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_pagina & par & "&O=E&w_chave=" & w_chave & "&w_sq_acao_ppa=" &RS("sq_acao_ppa")&"&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
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
    AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_data_hora"" value=""" & RS_menu("data_hora") &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & RS_menu("sq_menu") &""">"
    DB_GetSolicData RS, w_chave, SG
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    If RS("sq_acao_ppa") > "" Then
       ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Programa PPA: </b><br>" &RS("cd_ppa_pai")& " - " & RS("nm_ppa_pai") & " </b>"
       ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Ação PPA: </b><br>" &RS("cd_ppa")& " - " & RS("nm_ppa") & " </b>"      
    End If
    If RS("sq_orprioridade") > "" Then
       ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Iniciativa prioritária: </b><br>" & RS("nm_pri") & " </b>"      
    End If
    DesconectaBD
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
    If O = "I" Then
       SelecaoAcaoPPA "Ação <u>P</u>PA:", "P", null, w_sq_acao_ppa, w_chave, "w_sq_acao_ppa", "FINANCIAMENTO", null
    Else
       ShowHTML "<INPUT type=""hidden"" name=""w_sq_acao_ppa"" value=""" & w_sq_acao_ppa &""">"
       SelecaoAcaoPPA "Ação <u>P</u>PA:", "P", null, w_sq_acao_ppa, w_chave, "w_sq_acao_ppa", null, "disabled"
    End If
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Obse<u>r</u>vações:</b><br><textarea " & w_Disabled & " accesskey=""R"" name=""w_obs_financ"" class=""STI"" ROWS=5 cols=75 ONMOUSEOVER=""popup('Informar fatos ou situações que sejam relevantes para uma melhor compreensão do financiamento da ação.','white')""; ONMOUSEOUT=""kill()"">" & w_obs_financ & "</TEXTAREA></td>"
    ShowHTML "      <tr><td align=""center"" colspan=4><hr>"
    If O = "E" Then
       ShowHTML "   <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
    ElseIf O = "I" Then
       ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Incluir"">"
    ElseIf O = "A" Then   
       ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Alterar"">"
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
  Set w_sq_acao_ppa     = Nothing 
  Set w_obs_financ      = Nothing
  
  Set w_troca           = Nothing 
  Set i                 = Nothing 
  Set w_erro            = Nothing
End Sub
REM =========================================================================
REM Fim da tela de financiamento
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina dos responsaveis
REM -------------------------------------------------------------------------
Sub Responsaveis
  Dim w_chave, w_chave_pai, w_chave_aux, w_nome, w_tipo
  Dim w_responsavel, w_email, w_telefone, w_label, w_codigo
  Dim w_sq_orprioridade, w_sq_acao_ppa_pai, w_sq_acao_ppa
  Dim w_nome_pai, w_codigo_pai
  
  Dim w_troca, i, w_erro
  
  w_Chave               = Request("w_Chave")
  w_chave_aux           = Request("w_chave_aux")
  w_sq_acao_ppa         = Request("w_sq_acao_ppa")
  w_sq_acao_ppa_pai     = Request("w_sq_acao_ppa_pai")
  w_sq_orprioridade     = Request("w_sq_orprioridade")
  
  If O = "L" Then
     ' Recupera todos os registros para a listagem
     DB_GetSolicData RS, w_chave, SG
  ElseIf InStr("A",O) > 0 Then
     If w_sq_acao_ppa_pai > "" Then
        w_tipo = 1
        DB_GetAcaoPPA RS, w_sq_acao_ppa_pai, w_cliente, null, null, null, null, null, null, null, null
     ElseIf w_sq_acao_ppa > "" Then
        w_tipo = 2
        DB_GetAcaoPPA RS, w_sq_acao_ppa, w_cliente, null, null, null, null, null, null, null, null
     ElseIf w_sq_orprioridade > "" Then
        w_tipo = 3
        DB_GetOrPrioridade RS, null, w_cliente, w_sq_orprioridade, null, null, null
     End If
     'DB_GetSolicData RS, w_chave, SG
     If Not RS.EOF Then
        w_responsavel          = RS("responsavel")
        w_telefone             = RS("telefone")
        w_email                = RS("email")
        w_nome                 = RS("nome")
        w_codigo               = RS("codigo")
        If w_tipo = 2 then     
           w_nome_pai             = RS("nm_acao_pai")
           w_codigo_pai           = RS("cd_pai") 
        End If 
     End If
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("A",O) > 0 Then
     ScriptOpen "JavaScript"
     modulo
     checkbranco
     formatadata
     FormataCEP
     FormataValor
     ValidateOpen "Validacao"
     If InStr("A",O) > 0 Then
        Validate "w_responsavel", "Responsável", "", "1", "3", "60", "1", "1"
        Validate "w_telefone", "Telenfone", "1", "", "7", "14", "1", "1"
        Validate "w_email", "Email", "", "", "3", "60", "1", "1"
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  BodyOpen "onLoad='document.focus()';"
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    AbreSessao
    ShowHTML "<tr><td align=""center"" colspan=3>&nbsp;"
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Tipo</font></td>"
    ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
       If Not IsNull(RS("sq_acao_ppa")) Then
          If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
          ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
          ShowHTML "        <td><font size=""1"">Programa PPA</td>"
          ShowHTML "        <td><font size=""1"">" & RS("nm_ppa_pai") & "</td>"
          ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
          ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" &w_chave& "&w_sq_acao_ppa_pai=" & RS("sq_acao_ppa_pai") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_chave_aux=" &RS("sq_acao_ppa")& """>Gerente Executivo</A>&nbsp"
          ShowHTML "        </td>"
          ShowHTML "      </tr>"
          If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
          ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
          ShowHTML "        <td><font size=""1"">Ação PPA</td>"
          ShowHTML "        <td><font size=""1"">" & RS("nm_ppa") & "</td>"
          ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
          ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" &w_chave& "&w_sq_acao_ppa=" & RS("sq_acao_ppa") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_chave_aux=" &RS("sq_siw_solicitacao")& """>Coordenador</A>&nbsp"
          ShowHTML "        </td>"
          ShowHTML "      </tr>"
       End If
       If Not IsNull(RS("sq_orprioridade")) Then
          If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
          ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
          ShowHTML "        <td><font size=""1"">Iniciativa</td>"
          ShowHTML "        <td><font size=""1"">" & RS("nm_pri") & "</td>"
          ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
          ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" &w_chave& "&w_sq_orprioridade=" & RS("sq_orprioridade") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_chave_aux=" &RS("sq_orprioridade")&  """>Responsável</A>&nbsp"
          ShowHTML "        </td>"
          ShowHTML "      </tr>"
       End If
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    DesconectaBD
  ElseIf Instr("A",O) > 0 Then
    AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave& """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_tipo"" value=""" & w_tipo& """>"
    If w_tipo = 1 Then
       w_label = "Programa PPA"
       w_chave_aux = w_sq_acao_ppa_pai
    ElseIf w_tipo= 2 Then
       w_label = "Ação PPA"
       w_chave_aux = w_sq_acao_ppa
    ElseIf w_tipo = 3 Then
       w_label = "Iniciativa prioritária"
       w_chave_aux = w_sq_orprioridade
    End If
    ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_chave_aux & """>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    If w_tipo = 2 Then
       ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Programa PPA: </b>" &w_codigo_pai& " - " & w_nome_pai & " </b>" 
    End If
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>" &w_label& ": </b>" 
    If Not w_tipo = 3 Then 
       ShowHTML "" &w_codigo& " - " 
    End If 
    ShowHTML "" & w_nome & "</td>"
    If w_tipo = 1 Then
       ShowHTML "      <tr><td><font size=""1""><b><u>G</u>erente executivo:</b><br><input " & w_Disabled & " accesskey=""G"" type=""text"" name=""w_responsavel"" class=""STI"" SIZE=""50"" MAXLENGTH=""60"" VALUE=""" & w_responsavel & """ ONMOUSEOVER=""popup('Informe um gerente executivo.','white')""; ONMOUSEOUT=""kill()""></td>"
    ElseIf w_tipo = 2 Then
       ShowHTML "      <tr><td><font size=""1""><b><u>C</u>oordenador:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_responsavel"" class=""STI"" SIZE=""50"" MAXLENGTH=""60"" VALUE=""" & w_responsavel & """ ONMOUSEOVER=""popup('Informe um coordenador.','white')""; ONMOUSEOUT=""kill()""></td>"
    ElseIf w_tipo = 3 Then
       ShowHTML "      <tr><td><font size=""1""><b>Res<u>p</u>onsável:</b><br><input " & w_Disabled & " accesskey=""P"" type=""text"" name=""w_responsavel"" class=""STI"" SIZE=""50"" MAXLENGTH=""60"" VALUE=""" & w_responsavel & """ ONMOUSEOVER=""popup('Informe um responsável.','white')""; ONMOUSEOUT=""kill()""></td>"
    End If
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>T</u>elefone:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_telefone"" class=""STI"" SIZE=""15"" MAXLENGTH=""14"" VALUE=""" & w_telefone & """></td>"
    ShowHTML "      <tr><td><font size=""1""><b>E<u>m</u>ail:</b><br><input " & w_Disabled & " accesskey=""M"" type=""text"" name=""w_email"" class=""STI"" SIZE=""50"" MAXLENGTH=""60"" VALUE=""" & w_email & """ ONMOUSEOVER=""popup('Informe o email do responsável.','white')""; ONMOUSEOUT=""kill()""></td>"
    ShowHTML "      <tr><td align=""center"" colspan=4><hr>"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Gravar"">"
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
  Set w_responsavel     = Nothing
  Set w_telefone        = Nothing
  Set w_email           = Nothing
  Set w_codigo          = Nothing
  
  Set w_troca           = Nothing 
  Set i                 = Nothing 
  Set w_erro            = Nothing
End Sub
REM =========================================================================
REM Fim da tela de responsáveis
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de etapas da ação
REM -------------------------------------------------------------------------
Sub Etapas
  Dim w_chave, w_chave_pai, w_chave_aux, w_titulo, w_ordem, w_descricao
  Dim w_inicio, w_fim, w_inicio_real, w_fim_real, w_perc_conclusao, w_orcamento
  Dim w_sq_pessoa, w_sq_unidade, w_vincula_atividade, w_quantidade, w_cumulativa
  Dim w_unidade_medida, w_programada
  
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
     w_unidade_medida       = Request("w_unidade_medida")    
     w_quantidade           = Request("w_quantidade")
     w_cumulativa           = Request("w_cumulativa")
     w_programada           = Request("w_programada")
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
     w_unidade_medida       = RS("unidade_medida")    
     w_quantidade           = RS("quantidade")
     w_cumulativa           = RS("cumulativa")
     w_programada           = RS("programada")
     DesconectaBD
  ElseIf Nvl(w_sq_pessoa,"") = "" Then
     ' Se a etapa não tiver responsável atribuído, recupera o responsável pela ação
     DB_GetSolicData RS, w_chave, "ORGERAL"
     
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
        Validate "w_quantidade", "Quantitativo programado", "", "1", "2", "18", "", "1"
        Validate "w_unidade_medida", "Unidade de medida", "", "1", "2", "100", "1", "1"
        Validate "w_descricao", "Descricao", "", "1", "2", "2000", "1", "1"
        Validate "w_ordem", "Ordem", "1", "1", "1", "3", "", "0123456789"
        'Validate "w_chave_pai", "Subordinação", "SELECT", "", "1", "10", "", "1"
        Validate "w_inicio", "Início previsto", "DATA", "1", "10", "10", "", "0123456789/"
        Validate "w_fim", "Fim previsto", "DATA", "1", "10", "10", "", "0123456789/"
        CompData "w_inicio", "Início previsto", "<=", "w_fim", "Fim previsto"
        'Validate "w_orcamento", "Recurso programado", "VALOR", "1", "4", "18", "", "0123456789.,"
        'Validate "w_perc_conclusao", "Percentual de conclusão", "", "1", "1", "3", "", "0123456789"
        Validate "w_sq_pessoa", "Responsável", "SELECT", "", "1", "10", "", "1"
        Validate "w_sq_unidade", "Setor responsável", "SELECT", "", "1", "10", "", "1"
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
     BodyOpen "onLoad='document.Form.w_titulo.focus()';"
  Else
     BodyOpen "onLoad='document.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    AbreSessao
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Metas</font></td>"
    'ShowHTML "          <td><font size=""1""><b>Produto</font></td>"
    'ShowHTML "          <td rowspan=2><font size=""1""><b>Responsável</font></td>"
    'ShowHTML "          <td rowspan=2><font size=""1""><b>Setor</font></td>"
    ShowHTML "          <td><font size=""1""><b>Execução até</font></td>"
    ShowHTML "          <td><font size=""1""><b>Conc.</font></td>"
    'ShowHTML "          <td rowspan=2><font size=""1""><b>Ativ.</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    'ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    'ShowHTML "          <td><font size=""1""><b>De</font></td>"
    'ShowHTML "          <td><font size=""1""><b>Até</font></td>"
    'ShowHTML "        </tr>"
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
    AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_chave_aux & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_pessoa"" value=""" & w_sq_pessoa & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_unidade"" value=""" & w_sq_unidade & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_orcamento"" value=""0,00"">"
    ShowHTML "<INPUT type=""hidden"" name=""w_vincula_atividade"" value=""N"">"
    ShowHTML "<INPUT type=""hidden"" name=""w_perc_conclusao"" value=""0"">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr><td><font size=""1""><b>Prod<u>u</u>to:</b><br><input " & w_Disabled & " accesskey=""U"" type=""text"" name=""w_titulo"" class=""STI"" SIZE=""90"" MAXLENGTH=""90"" VALUE=""" & w_titulo & """ ONMOUSEOVER=""popup('Bem ou serviço que resulta da ação, destinado ao público-alvo ou o investimento para a produção deste bem ou serviço. Para cada ação deve haver um só produto. Em situações especiais, expressa a quantidade de beneficiários atendidos pela ação.','white')""; ONMOUSEOUT=""kill()""></td>"
    ShowHTML "     <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
    MontaRadioNS "<b>Meta LOA?</b>", w_programada, "w_programada"
    MontaRadioNS "<b>Meta cumulativa?</b>", w_cumulativa, "w_cumulativa"
    ShowHTML "         </table></td></tr>"
    ShowHTML "     <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
    ShowHTML "         <tr><td align=""left""><font size=""1""><b><u>Q</u>uantitativo:<br><INPUT ACCESSKEY=""Q"" TYPE=""TEXT"" CLASS=""STI"" NAME=""w_quantidade"" SIZE=5 MAXLENGTH=18 VALUE=""" & w_quantidade & """ " & w_Disabled & "></td>"
    ShowHTML "             <td align=""left""><font size=""1""><b><u>U</u>nidade de medida:<br><INPUT ACCESSKEY=""U"" TYPE=""TEXT"" CLASS=""STI"" NAME=""w_unidade_medida"" SIZE=15 MAXLENGTH=30 VALUE=""" & w_unidade_medida & """ " & w_Disabled & "></td>"
    ShowHTML "         </table></td></tr>"

    ShowHTML "      <tr><td><font size=""1""><b><u>E</u>specificação do produto:</b><br><textarea " & w_Disabled & " accesskey=""E"" name=""w_descricao"" class=""STI"" ROWS=5 cols=75 ONMOUSEOVER=""popup('Expresse as características do produto acabado visando sua melhor identificação.','white')""; ONMOUSEOUT=""kill()"">" & w_descricao & "</TEXTAREA></td>"
    'ShowHTML "      <tr>"
    'SelecaoEtapa "Me<u>t</u>a superior:", "T", "Se necessário, indique a meta superior a esta.", w_chave_pai, w_chave, w_chave_aux, "w_chave_pai", "Pesquisa", null
    'ShowHTML "      </tr>"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
    ' Recupera o número de ordem das outras opções irmãs à selecionada
    DB_GetEtapaOrder RS, w_chave, w_chave_pai
    If Not RS.EOF Then
       w_texto = "<b>Nºs de ordem em uso para esta subordinação:</b><br>" & _
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
    ShowHTML "              <td><font size=""1""><b>Previsão iní<u>c</u>io:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_inicio"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & FormataDataEdicao(Nvl(w_inicio,Date())) & """ onKeyDown=""FormataData(this,event);"" ONMOUSEOVER=""popup('Data prevista para início da meta.','white')""; ONMOUSEOUT=""kill()""></td>"
    ShowHTML "              <td><font size=""1""><b>Previsão <u>t</u>érmino:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_fim"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & FormataDataEdicao(w_fim) & """ onKeyDown=""FormataData(this,event);"" ONMOUSEOVER=""popup('Data prevista para término da meta.','white')""; ONMOUSEOUT=""kill()""></td>"
    'ShowHTML "          <tr valign=""top"">"
    'ShowHTML "              <td><font size=""1""><b>Orça<u>m</u>ento previsto:</b><br><input " & w_Disabled & " accesskey=""M"" type=""text"" name=""w_orcamento"" class=""STI"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & FormatNumber(w_orcamento,2) & """ onKeyDown=""FormataValor(this,18,2,event);"" ONMOUSEOVER=""popup('Recurso programado para execução desta etapa.','white')""; ONMOUSEOUT=""kill()""></td>"
    'ShowHTML "              <td align=""left""><font size=""1""><b>Percentual de co<u>n</u>clusão:<br><INPUT ACCESSKEY=""N"" TYPE=""TEXT"" CLASS=""STI"" NAME=""w_perc_conclusao"" SIZE=3 MAXLENGTH=3 VALUE=""" & nvl(w_perc_conclusao,0) & """ " & w_Disabled & " ONMOUSEOVER=""popup('Informe o percentual de conclusão atual da meta.','white')""; ONMOUSEOUT=""kill()""></td>"
    'MontaRadioSN "<b>Permite vinculação de atividades?</b>", w_vincula_atividade, "w_vincula_atividade"
    ShowHTML "          </table>"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    'SelecaoPessoa "Respo<u>n</u>sável pela etapa:", "N", "Selecione o responsável pela etapa na relação.", w_sq_pessoa, null, "w_sq_pessoa", "USUARIOS"
    'SelecaoUnidade "<U>S</U>etor responsável pela etapa:", "S", "Selecione o setor responsável pela execução da etapa", w_sq_unidade, null, "w_sq_unidade", null, null
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
REM Fim da tela de etapas da ação
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de atualização das etapas da ação
REM -------------------------------------------------------------------------
Sub AtualizaEtapa
  Dim w_chave, w_chave_pai, w_chave_aux, w_titulo, w_ordem, w_descricao
  Dim w_inicio, w_fim, w_inicio_real, w_fim_real, w_perc_conclusao, w_orcamento
  Dim w_ultima_atualizacao, w_sq_pessoa_atualizacao, w_situacao_atual
  Dim w_programada, w_cumulativa, w_quantidade, w_unidade_medida, w_nm_programada, w_nm_cumulativa
  Dim w_exequivel, w_justificativa_inex, w_outras_medidas
  Dim w_execucao_fisica, w_execucao_financeira(), w_referencia()
  Dim w_quantitativo_1, w_quantitativo_2, w_quantitativo_3, w_quantitativo_4, w_quantitativo_5, w_quantitativo_6
  Dim w_quantitativo_7, w_quantitativo_8, w_quantitativo_9, w_quantitativo_10, w_quantitativo_11, w_quantitativo_12
  Dim w_referencia_1, w_referencia_2, w_referencia_3, w_referencia_4, w_referencia_5, w_referencia_6
  Dim w_referencia_7, w_referencia_8, w_referencia_9, w_referencia_10, w_referencia_11, w_referencia_12
  Dim w_sq_pessoa, w_sq_unidade, w_vincula_atividade, w_cabecalho, w_fase, w_p2, w_fases
  Dim RS1, RS2, RS3, RS4
  Dim w_tipo
  
  Dim w_troca, i, w_texto
  
  w_Chave           = Request("w_Chave")
  w_Chave_pai       = Request("w_Chave_pai")
  w_chave_aux       = Request("w_chave_aux")
  w_troca           = Request("w_troca")
  w_tipo            = uCase(trim(Request("w_tipo")))
  
  DB_GetSolicData RS, w_chave, "ORGERAL"
  w_cabecalho = "      <tr><td valign=""top"" colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font  size=""2""><b>Ação: " & RS("titulo") & " (" & w_chave & ")</td></tr>"
  
  ' Configura uma variável para testar se as etapas podem ser atualizadas.
  ' Ações concluídas ou canceladas não podem ter permitir a atualização.
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
     w_unidade_medida       = Request("w_unidade_medida")    
     w_quantidade           = Request("w_quantidade")
     w_cumulativa           = Request("w_cumulativa")
     w_programada           = Request("w_programada")
     for i = 0 to i = 12 
        w_execucao_fisica[i]     = Request("w_execucao_fisica[i]")
        w_execucao_financeira[i] = Request("w_execucao_financeira[i]")
     next
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
     w_unidade_medida       = RS("unidade_medida")    
     w_quantidade           = RS("quantidade")
     w_cumulativa           = RS("cumulativa")
     w_programada           = RS("programada")
     w_exequivel            = RS("exequivel")
     w_justificativa_inex   = RS("justificativa_inexequivel")
     w_outras_medidas       = RS("outras_medidas")
     w_nm_programada        = RS("nm_programada")
     w_nm_cumulativa        = RS("nm_cumulativa")
     DesconectaBD
     DB_GetEtapaMensal RS, w_chave_aux
     If Not RS.EOF Then
        While Not RS.EOF 
           Select Case Month(cDate(RS("referencia")))
              Case  1 w_quantitativo_1  = RS("execucao_fisica")
              Case  2 w_quantitativo_2  = RS("execucao_fisica")
              Case  3 w_quantitativo_3  = RS("execucao_fisica")
              Case  4 w_quantitativo_4  = RS("execucao_fisica")
              Case  5 w_quantitativo_5  = RS("execucao_fisica")
              Case  6 w_quantitativo_6  = RS("execucao_fisica")
              Case  7 w_quantitativo_7  = RS("execucao_fisica")
              Case  8 w_quantitativo_8  = RS("execucao_fisica")
              Case  9 w_quantitativo_9  = RS("execucao_fisica")
              Case 10 w_quantitativo_10 = RS("execucao_fisica")
              Case 11 w_quantitativo_11 = RS("execucao_fisica")
              Case 12 w_quantitativo_12 = RS("execucao_fisica")
           End Select
           RS.MoveNext
        Wend
     End If
     DesconectaBD
  ElseIf Nvl(w_sq_pessoa,"") = "" Then
     ' Se a etapa não tiver responsável atribuído, recupera o responsável pela ação
     DB_GetSolicData RS, w_chave, "ORGERAL"
     
     w_sq_pessoa            = RS("solicitante")
     w_sq_unidade           = RS("sq_unidade_resp")
  End If
  If w_tipo = "WORD" Then
      Response.ContentType = "application/msword"
  Else
     Cabecalho
  End If
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>" & conSgSistema & " - Meta da ação</TITLE>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     FormataValor
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_quantitativo_1", "Quantitativo de Janeiro", "", "", "1", "10", "", "0123456789"
        Validate "w_quantitativo_2", "Quantitativo de Fevereiro", "", "", "1", "10", "", "0123456789"
        Validate "w_quantitativo_3", "Quantitativo de Março", "", "", "1", "10", "", "0123456789"
        Validate "w_quantitativo_4", "Quantitativo de Abril", "", "", "1", "10", "", "0123456789"
        Validate "w_quantitativo_5", "Quantitativo de Maio", "", "", "1", "10", "", "0123456789"
        Validate "w_quantitativo_6", "Quantitativo de Junho", "", "", "1", "10", "", "0123456789"
        Validate "w_quantitativo_7", "Quantitativo de Julho", "", "", "1", "10", "", "0123456789"
        Validate "w_quantitativo_8", "Quantitativo de Agosto", "", "", "1", "10", "", "0123456789"
        Validate "w_quantitativo_9", "Quantitativo de Setembro", "", "", "1", "10", "", "0123456789"
        Validate "w_quantitativo_10", "Quantitativo de Outubro", "", "", "1", "10", "", "0123456789"
        Validate "w_quantitativo_11", "Quantitativo de Novembro", "", "", "1", "10", "", "0123456789"
        Validate "w_quantitativo_12", "Quantitativo de Dezembro", "", "", "1", "10", "", "0123456789"
        Validate "w_situacao_atual", "Situação atual", "", "", "2", "4000", "1", "1"
        ShowHTML "  if (theForm.w_exequivel[1].checked && theForm.w_justificativa_inex.value == '') {"
        ShowHTML "     alert ('Justifique porque a meta não será cumprida!');"
        ShowHTML "     theForm.w_justificativa_inex.focus();"
        ShowHTML "     return false;"
        ShowHTML "  } else { if (theForm.w_exequivel[0].checked) "
        ShowHTML "     theForm.w_justificativa_inex.value = '';"
        ShowHTML "   }"
        ShowHTML "  if (theForm.w_exequivel[1].checked && theForm.w_outras_medidas.value == '') {"
        ShowHTML "     alert ('Indique quais são as medidas necessárias para o cumprimento da meta!');"
        ShowHTML "     theForm.w_outras_medidas.focus();"
        ShowHTML "     return false;"
        ShowHTML "  } else { if (theForm.w_exequivel[0].checked) "
        ShowHTML "     theForm.w_outras_medidas.value = '';"
        ShowHTML "   }"
        Validate "w_justificativa_inex", "Justificativa", "", "", "2", "4000", "1", "1"
        Validate "w_outras_medidas", "Medidas", "", "", "2", "4000", "1", "1"
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
  ElseIf O = "I" or O = "A" Then
     BodyOpen "onLoad='document.Form.focus()';"
  Else
     BodyOpen "onLoad='document.focus()';"
  End If
  'ShowHTML "<B><FONT COLOR=""#000000"">" & Mid(w_TP,1, Instr(w_TP,"-")-1) & "- Metas" & "</FONT></B>"
  'ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "  <table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  ShowHTML      w_cabecalho
  If w_tipo <> "WORD" and O = "V" Then
     ShowHTML "<tr><td align=""right""colspan=""2"">"
     ShowHTML "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Imprimir"" SRC=""images/impressora.jpg"" onClick=""window.print();"">"
     ShowHTML "&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Gerar word"" SRC=""images/word.gif"" onClick=""window.open('Projeto.asp?par=AtualizaEtapa&O=V&w_chave=" & w_chave & "&w_chave_aux=" & w_chave_aux & "&w_tipo=WORD&P1=10&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "','MetaWord','width=600, height=350, top=65, left=65, menubar=yes, scrollbars=yes, resizable=yes, status=no');"">"
     ShowHTML "</td></tr>"
  End If
  If O = "L" Then
    AbreSessao
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "  <tr><td colspan=""2""><font size=""3""></td>"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount & "</td></tr>"
    ShowHTML "  <tr><td align=""center"" colspan=""3"">"
    ShowHTML "      <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Metas</font></td>"
    ShowHTML "          <td><font size=""1""><b>Execução até</font></td>"
    ShowHTML "          <td><font size=""1""><b>Conc.</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    ' Recupera as etapas principais
    DB_GetSolicEtapa RS, w_chave, null, "LSTNULL"
    RS.Sort = "ordem"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "    <tr bgcolor=""" & conTrBgColor & """><td colspan=4 align=""center""><font size=""2""><b>Não foi encontrado nenhum registro.</b></td></tr>"
    Else
      While Not RS.EOF
        If cDbl(Nvl(RS("tit_exec"),0))    = cDbl(w_usuario) or _
           cDbl(Nvl(RS("sub_exec"),0))    = cDbl(w_usuario) or _
           cDbl(Nvl(RS("titular"),0))     = cDbl(w_usuario) or _
           cDbl(Nvl(RS("substituto"),0))  = cDbl(w_usuario) or _
           cDbl(Nvl(RS("executor"),0))    = cDbl(w_usuario) or _
           cDbl(Nvl(RS("solicitante"),0)) = cDbl(w_usuario) or _
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
          If cDbl(Nvl(RS1("titular"),0)) = cDbl(w_usuario) or cDbl(Nvl(RS1("substituto"),0)) = cDbl(w_usuario) or cDbl(Nvl(RS1("sq_pessoa"),0)) = cDbl(w_usuario) Then
             ShowHTML EtapaLinha(w_chave, RS1("sq_projeto_etapa"), RS1("titulo"), RS1("nm_resp"), RS1("sg_setor"), RS1("inicio_previsto"), RS1("fim_previsto"), RS1("perc_conclusao"), RS1("qt_ativ"), null, w_fase, "ETAPA")
          Else
             ShowHTML EtapaLinha(w_chave, RS1("sq_projeto_etapa"), RS1("titulo"), RS1("nm_resp"), RS1("sg_setor"), RS1("inicio_previsto"), RS1("fim_previsto"), RS1("perc_conclusao"), RS1("qt_ativ"), null, "N", "ETAPA")
          End If

          ' Recupera as etapas vinculadas ao nível acima
          DB_GetSolicEtapa RS2, w_chave, RS1("sq_projeto_etapa"), "LSTNIVEL"
          RS2.Sort = "ordem"
          While Not RS2.EOF
            If cDbl(Nvl(RS2("titular"),0)) = cDbl(w_usuario) or cDbl(Nvl(RS2("substituto"),0)) = cDbl(w_usuario) or cDbl(Nvl(RS2("sq_pessoa"),0)) = cDbl(w_usuario) Then
               ShowHTML EtapaLinha(w_chave, RS2("sq_projeto_etapa"), RS2("titulo"), RS2("nm_resp"), RS2("sg_setor"), RS2("inicio_previsto"), RS2("fim_previsto"), RS2("perc_conclusao"), RS2("qt_ativ"), null, w_fase, "ETAPA")
            Else
               ShowHTML EtapaLinha(w_chave, RS2("sq_projeto_etapa"), RS2("titulo"), RS2("nm_resp"), RS2("sg_setor"), RS2("inicio_previsto"), RS2("fim_previsto"), RS2("perc_conclusao"), RS2("qt_ativ"), null, "N", "ETAPA")
            End If

            ' Recupera as etapas vinculadas ao nível acima
            DB_GetSolicEtapa RS3, w_chave, RS2("sq_projeto_etapa"), "LSTNIVEL"
            RS3.Sort = "ordem"
            While Not RS3.EOF
              If cDbl(Nvl(RS3("titular"),0)) = cDbl(w_usuario) or cDbl(Nvl(RS3("substituto"),0)) = cDbl(w_usuario) or cDbl(Nvl(RS3("sq_pessoa"),0)) = cDbl(w_usuario) Then
                 ShowHTML EtapaLinha(w_chave, RS3("sq_projeto_etapa"), RS3("titulo"), RS3("nm_resp"), RS3("sg_setor"), RS3("inicio_previsto"), RS3("fim_previsto"), RS3("perc_conclusao"), RS3("qt_ativ"), null, w_fase, "ETAPA")
              Else
                 ShowHTML EtapaLinha(w_chave, RS3("sq_projeto_etapa"), RS3("titulo"), RS3("nm_resp"), RS3("sg_setor"), RS3("inicio_previsto"), RS3("fim_previsto"), RS3("perc_conclusao"), RS3("qt_ativ"), null, "N", "ETAPA")
              End If

              ' Recupera as etapas vinculadas ao nível acima
              DB_GetSolicEtapa RS4, w_chave, RS3("sq_projeto_etapa"), "LSTNIVEL"
              RS4.Sort = "ordem"
              While Not RS4.EOF
                If cDbl(Nvl(RS4("titular"),0)) = cDbl(w_usuario) or cDbl(Nvl(RS4("substituto"),0)) = cDbl(w_usuario) or cDbl(Nvl(RS4("sq_pessoa"),0)) = cDbl(w_usuario) Then
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
    If w_tipo <> "WORD" Then
       AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,w_pagina&par,O
       ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
       ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_chave_aux & """>"
       ShowHTML "<INPUT type=""hidden"" name=""w_perc_ant"" value=""" & w_perc_conclusao & """>"
       ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
       ShowHTML "<INPUT type=""hidden"" name=""w_cumulativa"" value=""" & w_cumulativa & """>"
       ShowHTML "<INPUT type=""hidden"" name=""w_quantidade"" value=""" & w_quantidade & """>"
       ShowHTML "<INPUT type=""hidden"" name=""w_referencia_1"" value=""01/01/2004"">"
       ShowHTML "<INPUT type=""hidden"" name=""w_referencia_2"" value=""01/02/2004"">"
       ShowHTML "<INPUT type=""hidden"" name=""w_referencia_3"" value=""01/03/2004"">"
       ShowHTML "<INPUT type=""hidden"" name=""w_referencia_4"" value=""01/04/2004"">"
       ShowHTML "<INPUT type=""hidden"" name=""w_referencia_5"" value=""01/05/2004"">"
       ShowHTML "<INPUT type=""hidden"" name=""w_referencia_6"" value=""01/06/2004"">"
       ShowHTML "<INPUT type=""hidden"" name=""w_referencia_7"" value=""01/07/2004"">"
       ShowHTML "<INPUT type=""hidden"" name=""w_referencia_8"" value=""01/08/2004"">"
       ShowHTML "<INPUT type=""hidden"" name=""w_referencia_9"" value=""01/09/2004"">"
       ShowHTML "<INPUT type=""hidden"" name=""w_referencia_10"" value=""01/10/2004"">"
       ShowHTML "<INPUT type=""hidden"" name=""w_referencia_11"" value=""01/11/2004"">"
       ShowHTML "<INPUT type=""hidden"" name=""w_referencia_12"" value=""01/12/2004"">"
    End If
    ShowHTML "    <tr><td align=""center"" bgcolor=""#FAEBD7"" colspan=""2"">"
    ShowHTML "      <table border=1 width=""100%"">"
    ShowHTML "        <tr><td valign=""top"" colspan=""2"">"    
    ShowHTML "          <TABLE border=0 WIDTH=""100%"" CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "            <tr><td colspan=""2""><font size=""1"">Meta:<b><br><font size=2>" & MontaOrdemEtapa(w_chave_aux) & ". " & w_titulo & "</font></td></tr>"
    ShowHTML "            <tr><td colspan=""2""><font size=""1"">Descrição:<b><br>" & w_descricao & "</td></tr>"
    ShowHTML "            <tr><td valign=""top"" colspan=""2"">"
    ShowHTML "              <table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
    ShowHTML "                <td><font size=""1"">Meta LOA?<b><br>" & w_nm_programada& "</td>"
    ShowHTML "                <td><font size=""1"">Meta cumulativa:<b><br>" & w_nm_cumulativa & "</td></tr>"
    ShowHTML "              </table></td></tr>"
    ShowHTML "            <tr><td valign=""top"" colspan=""2"">"
    ShowHTML "              <table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
    ShowHTML "                <td><font size=""1"">Quantitativo:<b><br>" & w_quantidade& "</td>"
    ShowHTML "                <td><font size=""1"">Unidade de medida:<b><br>" & Nvl(w_unidade_medida,"---") & "</td></tr>"
    ShowHTML "              </table></td></tr>"
    ShowHTML "            <tr><td valign=""top"" colspan=""2"">"
    ShowHTML "              <table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
    ShowHTML "                <td><font size=""1"">Previsão início:<b><br>" & FormataDataEdicao(Nvl(w_inicio,Date())) & "</td>"
    ShowHTML "                <td><font size=""1"">Previsão término:<b><br>" & FormataDataEdicao(w_fim) & "</td></tr>"
    ShowHTML "                <tr valign=""top"">"
    DB_GetPersonData RS, w_cliente, w_sq_pessoa, null, null
    ShowHTML "                  <td><font size=""1"">Responsável pela meta:<b><br>" & RS("nome_resumido") & "</td>"
    DesconectaBD
    DB_GetUorgData RS, w_sq_unidade
    ShowHTML "                  <td><font size=""1"">Setor responsável pela meta:<b><br>" & RS("nome") & " (" & RS("sigla") & ")</td></tr>"
    DesconectaBD
    DB_GetPersonData RS, w_cliente, w_sq_pessoa_atualizacao, null, null
    ShowHTML "                <tr><td colspan=""2""><font size=""1"">Criação/última atualização:<b><br><font size=1>" & FormataDataEdicao(w_ultima_atualizacao) & "</b>, feita por <b>" & RS("nome_resumido") & " (" & RS("sigla") & ")</b></font></td></tr>"
    DesconectaBD
    ShowHTML "              </table></td></tr>"
    ShowHTML "          </TABLE>"
    ShowHTML "      </table>"
    ShowHTML "    <tr bgcolor=""" & conTrBgColor & """><td align=""center"" colspan=""2"">"
    ShowHTML "      <table width=""100%"" border=""0"">"
    If O = "V" Then
       ShowHTML "     <tr><td valign=""top"">"
       ShowHTML "       <table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
       ShowHTML "         <tr><td>&nbsp<td><font size=""1""><br><b>Quantitativo realizado</b></td>"
       ShowHTML "             <td>&nbsp<td><font size=""1""><br><b>Quantitativo realizado</b></td>"
       ShowHTML "         <tr><td width=""10%"" align=""right""><font size=""1""><b>Janeiro:"
       ShowHTML "             <td width=""30%""><font size=""1"">" & Nvl(w_quantitativo_1,"---") & "</td>"
       ShowHTML "             <td width=""20%"" align=""right""><font size=""1""><b>Julho:"
       ShowHTML "             <td><font size=""1"">"& Nvl(w_quantitativo_7,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Fevereiro:"
       ShowHTML "             <td><font size=""1"">"& Nvl(w_quantitativo_2,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1""><b>Agosto:"
       ShowHTML "             <td><font size=""1"">" & Nvl(w_quantitativo_8,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Março:"
       ShowHTML "             <td><font size=""1"">" & Nvl(w_quantitativo_3,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1""><b>Setembro:"
       ShowHTML "             <td><font size=""1"">" & Nvl(w_quantitativo_9,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Abril:"
       ShowHTML "             <td><font size=""1"">" & Nvl(w_quantitativo_4,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1""><b>Outubro:"
       ShowHTML "             <td><font size=""1"">" & Nvl(w_quantitativo_10,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Maio:"
       ShowHTML "             <td><font size=""1"">" & Nvl(w_quantitativo_5,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1""><b>Novembro:"
       ShowHTML "             <td><font size=""1"">" & Nvl(w_quantitativo_11,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Junho:"
       ShowHTML "             <td><font size=""1"">" & Nvl(w_quantitativo_6,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1""><b>Dezembro:"
       ShowHTML "             <td><font size=""1"">" & Nvl(w_quantitativo_12,"---") & "</td>"
       ShowHTML "       </table>"
       ShowHTML "     <tr><td><font size=""1"">Percentual de conlusão:<br><b>" & nvl(w_perc_conclusao,0) & "%</b></td>"
       ShowHTML "     <tr><td valign=""top""><font size=""1"">Situação atual da meta:<b><br>" & Nvl(w_situacao_atual,"---") & "</td>"
       ShowHTML "     <tr><td valign=""top""><font size=""1"">Justificar os motivos casso de não cumprimento da meta:<b><br>" & Nvl(w_justificativa_inex,"---") & "</td>"
       ShowHTML "     <tr><td valign=""top""><font size=""1"">Quais medidas necessárias para o cumprimento da meta:<b><br>" & Nvl(w_outras_medidas,"---") & "</td>"
    Else
       ShowHTML "     <tr><td><font size=""1"">Percentual de conlusão:<br><b>" & nvl(w_perc_conclusao,0) & "%</b></td>"
       ShowHTML "     <tr><td valign=""top"" colspan=""1"">"
       ShowHTML "       <table border=0 width=""100%"" cellspacing=0>"
       ShowHTML "         <tr><td>&nbsp<td><font size=""1""><br><b>Quantitativo realizado</b></td>"
       ShowHTML "             <td>&nbsp<td><font size=""1""><br><b>Quantitativo realizado</b></td>"
       ShowHTML "         <tr><td width=""8%"" align=""right""><font size=""1""><b>Janeiro:"
       ShowHTML "             <td width=""15%""><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_quantitativo_1"" SIZE=10 MAXLENGTH=18 VALUE=""" &w_quantitativo_1 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td width=""5%"" align=""right""><font size=""1""><b>Julho:"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_quantitativo_7"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_quantitativo_7 & """ " & w_Disabled & "></td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Fevereiro:"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_quantitativo_2"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_quantitativo_2 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td align=""right""><font size=""1""><b>Agosto:"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_quantitativo_8"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_quantitativo_8 & """ " & w_Disabled & "></td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Março:"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_quantitativo_3"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_quantitativo_3 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td align=""right""><font size=""1""><b>Setembro:"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_quantitativo_9"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_quantitativo_9 & """ " & w_Disabled & "></td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Abril:"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_quantitativo_4"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_quantitativo_4 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td align=""right""><font size=""1""><b>Outubro:"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_quantitativo_10"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_quantitativo_10 & """ " & w_Disabled & "></td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Maio:"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_quantitativo_5"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_quantitativo_5 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td align=""right""><font size=""1""><b>Novembro:"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_quantitativo_11"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_quantitativo_11 & """ " & w_Disabled & "></td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Junho:"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_quantitativo_6"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_quantitativo_6 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td align=""right""><font size=""1""><b>Dezembro:"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_quantitativo_12"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_quantitativo_12 & """ " & w_Disabled & " ></td>"
       ShowHTML "       </table>"
       ShowHTML "     <tr><td valign=""top""><font size=""1""><b><u>S</u>ituação atual da meta:</b><br><textarea " & w_Disabled & " accesskey=""S"" name=""w_situacao_atual"" class=""STI"" ROWS=5 cols=75 ONMOUSEOVER=""popup('Descreva a situação em a etapa encontra-se.','white')""; ONMOUSEOUT=""kill()"">" & w_situacao_atual & "</TEXTAREA></td>"
       ShowHTML "     <tr valign=""top"">"
       MontaRadioSN "<b>A meta será cumprida?</b>", w_exequivel, "w_exequivel"
       ShowHTML "     </tr>"
       ShowHTML "     <tr><td valign=""top""><font size=""1""><b><u>J</u>ustificar os motivos casso de não cumprimento da meta:</b><br><textarea " & w_Disabled & " accesskey=""J"" name=""w_justificativa_inex"" class=""STI"" ROWS=5 cols=75>" & w_justificativa_inex & "</TEXTAREA></td>"
       ShowHTML "     <tr><td valign=""top""><font size=""1""><b><u>Q</u>uais medidas necessárias para o cumprimento da meta?</b><br><textarea " & w_Disabled & " accesskey=""Q"" name=""w_outras_medidas"" class=""STI"" ROWS=5 cols=75>" & w_outras_medidas & "</TEXTAREA></td>"
    End If
    ShowHTML "        <tr><td align=""center""><hr>"
    If w_tipo <> "WORD" Then
       If O = "A" Then
          ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Atualizar"">"
       End If
       If P1 = 10 Then
          ShowHTML "            <input class=""STB"" type=""button"" onClick=""window.close();"" name=""Botao"" value=""Fechar"">"
       Else
          ShowHTML "            <input class=""STB"" type=""button"" onClick=""history.back(-1);"" name=""Botao"" value=""Voltar"">"
       End If
    End If
    ShowHTML "            </td>"
    ShowHTML "        </tr>"
    ShowHTML "      </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    If w_tipo <> "WORD" Then
       ShowHTML "</FORM>"
    End If
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    'ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  If w_tipo <> "WORD" Then
     Rodape
  End If
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
REM Fim da tela de atualização das etapas da ação
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de recursos da ação
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
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf O = "I" or O = "A" Then
     BodyOpen "onLoad='document.Form.w_nome.focus()';"
  Else
     BodyOpen "onLoad='document.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    AbreSessao
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
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
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & w_chave & "&w_chave_aux=" & Rs("sq_projeto_recurso") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "GRAVA&R=" & w_pagina & par & "&O=E&w_chave=" & w_chave & "&w_chave_aux=" & Rs("sq_projeto_recurso") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ onClick=""return confirm('Confirma a exclusão do registro?');"">Excluir</A>&nbsp"
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
    AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_chave_aux & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>N</u>ome:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""STI"" SIZE=""90"" MAXLENGTH=""100"" VALUE=""" & w_nome & """ ONMOUSEOVER=""popup('Informe o nome do recurso.','white')""; ONMOUSEOUT=""kill()""></td>"
    ShowHTML "      <tr>"
    SelecaoTipoRecurso "<u>T</u>ipo:", "T", "Selecione o tipo deste recurso.", w_tipo, null, "w_tipo", null, null
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>D</u>escrição:</b><br><textarea " & w_Disabled & " accesskey=""D"" name=""w_descricao"" class=""STI"" ROWS=5 cols=75 ONMOUSEOVER=""popup('Descreva, se necessário, características deste recurso (conhecimentos, habilidades, perfil, capacidade etc).','white')""; ONMOUSEOUT=""kill()"">" & w_descricao & "</TEXTAREA></td>"
    ShowHTML "      <tr>"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>F</u>inalidade:</b><br><textarea " & w_Disabled & " accesskey=""F"" name=""w_finalidade"" class=""STI"" ROWS=5 cols=75 ONMOUSEOVER=""popup('Descreva, se necessário, a finalidade deste recurso para a ação (funções desempenhadas, papel, objetivos etc).','white')""; ONMOUSEOUT=""kill()"">" & w_finalidade & "</TEXTAREA></td>"
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
REM Fim da tela de recursos da ação
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
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  BodyOpen "onLoad=document.focus();"
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
  AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"ETAPAREC",R,O
  ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_chave_aux & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_sg"" value=""" & Request("w_sg") & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_recurso"" value="""">"
  ShowHTML "<tr><td><font size=""1""><ul><b>Informações:</b><li>Indique abaixo quais recursos estarão alocados a esta etapa da ação.<li>A princípio, uma etapa não tem nenhum recurso alocado.<li>Para remover um recurso, desmarque o quadrado ao seu lado.</ul>"
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
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf O = "I" Then
     BodyOpen "onLoad='document.Form.w_chave_aux.focus()';"
  Else
     BodyOpen "onLoad='document.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    AbreSessao
    ShowHTML "      <tr><td colspan=3><font size=1>Usuários que terão acesso à visualização dos dados desta ação.</font></td></tr>"
    ShowHTML "      <tr><td colspan=3 align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    If P1 <> 4 Then 
       ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    Else
       DB_GetSolicData RS1, w_chave, "ORVISUAL"
       ShowHTML "<tr><td colspan=3 align=""center"" bgcolor=""#FAEBD7""><table border=1 width=""100%""><tr><td>"
       ShowHTML "    <TABLE WIDTH=""100%"" CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
       ShowHTML "        <tr valign=""top"">"
       If RS1("sq_acao_ppa") > "" Then
          ShowHTML "          <td><font size=""1""><b>Ação PPA: </b><br>" & RS1("nm_ppa") & " (" &RS1("cd_ppa")& "." & RS1("cd_ppa_pai")& ")</b>"      
       End If
       If RS1("sq_orprioridade") > "" Then
          ShowHTML "        <td><font size=""1""><b>Iniciativa prioritária: </b><br>" & RS1("nm_pri") & " </b>"      
       End If
       ShowHTML "    </TABLE>"
       ShowHTML "</table>"
       ShowHTML "<tr><td colspan=3>&nbsp;"
       ShowHTML "<tr><td colspan=2><font size=""2""><a accesskey=""F"" class=""SS"" href=""javascript:window.close();""><u>F</u>echar</a>&nbsp;"
    End If
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Pessoa</font></td>"
    ShowHTML "          <td><font size=""1""><b>Visao</font></td>"
    ShowHTML "          <td><font size=""1""><b>Envia e-mail</font></td>"
    If P1 <> 4 Then
       ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    End If
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & ExibePessoa("../", w_cliente, RS("sq_pessoa"), TP, RS("nome") & " (" & RS("lotacao") & ")") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RetornaTipoVisao(RS("tipo_visao")) & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & Replace(Replace(RS("envia_email"),"S","Sim"),"N","Não") & "</td>"
        If P1 <> 4 Then
           ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
           ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & w_chave & "&w_chave_aux=" & Rs("sq_pessoa") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
           ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "GRAVA&R=" & w_pagina & par & "&O=E&w_chave=" & w_chave & "&w_chave_aux=" & Rs("sq_pessoa") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ onClick=""return confirm('Confirma a exclusão do registro?');"">Excluir</A>&nbsp"
           ShowHTML "        </td>"
        End If
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
    AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
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
    SelecaoTipoVisao "<u>T</u>ipo de visão:", "T", "Selecione o tipo de visão que o interessado terá desta ação.", w_tipo_visao, null, "w_tipo_visao", null, null
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
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  Else
     BodyOpen "onLoad='document.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    AbreSessao
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
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
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & w_chave & "&w_chave_aux=" & Rs("sq_unidade") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "GRAVA&R=" & w_pagina & par & "&O=E&w_chave=" & w_chave & "&w_chave_aux=" & Rs("sq_unidade") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ onClick=""return confirm('Confirma a exclusão do registro?');"">Excluir</A>&nbsp"
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
    AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
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
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>P</u>apel desempenhado:</b><br><textarea " & w_Disabled & " accesskey=""P"" name=""w_papel"" class=""STI"" ROWS=5 cols=75 ONMOUSEOVER=""popup('Descreva o papel desempenhado pela área ou instituição na execução da ação.','white')""; ONMOUSEOUT=""kill()"">" & w_papel & "</TEXTAREA></td>"
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
     w_logo = conFileVirtual & w_cliente & "\img\logo" & Mid(RS("logo"),Instr(RS("logo"),"."),30)
  End If
  DesconectaBD
  
  If w_tipo = "WORD" Then
     Response.ContentType = "application/msword"
  Else 
     Cabecalho
  End If

  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>" & conSgSistema & " - Visualização de Ação</TITLE>"
  ShowHTML "</HEAD>"  
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_tipo <> "WORD" Then
     BodyOpenClean "onLoad='document.focus()'; "
  End If
  ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" SRC=""" & w_logo & """><TD ALIGN=""RIGHT""><B><FONT SIZE=4 COLOR=""#000000"">"
  If P1 = 1 Then
     ShowHTML "Iniciativas Prioritárias do Governo <BR> Relatório Geral por Ação"
  ElseIf P1 = 2 Then
     ShowHTML "Plano Plurianual 2004 - 2007 <BR> Relatório Geral por Ação"
  Else
     ShowHTML "Visualização de Ação"
  End If
  ShowHTML "</FONT><TR><TD ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
  If w_tipo <> "WORD" Then
     ShowHTML "&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Imprimir"" SRC=""images/impressora.jpg"" onClick=""window.print();"">"
     ShowHTML "&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Gerar word"" SRC=""images/word.gif"" onClick=""window.open('" & w_pagina & "Visual&R=" & w_pagina & par & "&O=L&w_chave=" & w_chave & "&w_tipo=word&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=1&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") &"','VisualAcaoWord','menubar=yes resizable=yes scrollbars=yes');"">"
  End If
  ShowHTML "</TD></TR>"
  ShowHTML "</FONT></B></TD></TR></TABLE>"
  ShowHTML "<HR>"
  If w_tipo > "" and w_tipo <> "WORD" Then
     ShowHTML "<center><B><font size=1>Clique <a class=""HL"" href=""javascript:history.back(1);"">aqui</a> para voltar à tela anterior</font></b></center>"
  End If
  
  ' Chama a rotina de visualização dos dados da ação, na opção "Listagem"
  ShowHTML VisualProjeto(w_chave, "L", w_usuario, P1, P4)

  
  If w_tipo > "" and w_tipo <> "WORD" Then
     ShowHTML "<center><B><font size=1>Clique <a class=""HL"" href=""javascript:history.back(1);"">aqui</a> para voltar à tela anterior</font></b></center>"
  End If

  If w_tipo <> "WORD" Then
     Rodape
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
  ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=../" & MontaURL("MESA") & """>"
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
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"

  ' Chama a rotina de visualização dos dados da ação, na opção "Listagem"
  ShowHTML VisualProjeto(w_chave, "V", w_usuario, P1, P4)

  ShowHTML "<HR>"
  AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"ORGERAL",R,O
  ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & w_menu & """>"
  DB_GetSolicData RS, w_chave, "ORGERAL"
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
     w_caminho              = RS("caminho") 
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
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_troca > "" Then 
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';" 
  ElseIf O = "I" Then 
     BodyOpen "onLoad='document.Form.w_nome.focus()';" 
  ElseIf O = "A" Then 
     BodyOpen "onLoad='document.Form.w_descricao.focus()';" 
  Else 
     BodyOpen "onLoad='document.focus()';" 
  End If 
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>" 
  ShowHTML "<HR>" 
  ShowHTML "<div align=center><center>" 
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">" 
  If O = "L" Then 
    AbreSessao 
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem 
    ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;" 
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount 
    ShowHTML "<tr><td align=""center"" colspan=3>" 
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>" 
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">" 
    ShowHTML "          <td><font size=""1""><b>Título</font></td>" 
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
        ShowHTML "        <td><font size=""1""><a class=""HL"" href=""" & conFileVirtual & w_cliente & "/" & RS("caminho") & """ target=""_blank"" title=""Clique para exibir o arquivo em outra janela."">" & RS("nome") & "</a></td>" 
        ShowHTML "        <td><font size=""1"">" & Nvl(RS("descricao"),"---") & "</td>" 
        ShowHTML "        <td><font size=""1"">" & RS("tipo") & "</td>" 
        ShowHTML "        <td align=""right""><font size=""1"">" & Round(cDbl(RS("tamanho"))/1024,1) & "&nbsp;</td>" 
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">" 
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_pagina & par & "&O=A&w_chave=" & w_chave & "&w_chave_aux=" & Rs("chave_aux") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp" 
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_pagina & par & "&O=E&w_chave=" & w_chave & "&w_chave_aux=" & Rs("chave_aux") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp" 
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
    ShowHTML "<FORM action=""" & w_dir & w_pagina & "Grava&SG="&SG&"&O="&O&""" name=""Form"" onSubmit=""return(Validacao(this));"" enctype=""multipart/form-data"" method=""POST"">" 
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
    
    ShowHTML "      <tr><td><font size=""1""><b><u>T</u>ítulo:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_nome"" class=""STI"" SIZE=""75"" MAXLENGTH=""255"" VALUE=""" & w_nome & """ ONMOUSEOVER=""popup('OBRIGATÓRIO. Informe um título para o arquivo.','white')""; ONMOUSEOUT=""kill()""></td>" 
    ShowHTML "      <tr><td><font size=""1""><b><u>D</u>escrição:</b><br><textarea " & w_Disabled & " accesskey=""D"" name=""w_descricao"" class=""STI"" ROWS=5 cols=65 ONMOUSEOVER=""popup('OBRIGATÓRIO. Descreva a finalidade do arquivo.','white')""; ONMOUSEOUT=""kill()"">" & w_descricao & "</TEXTAREA></td>" 
    ShowHTML "      <tr><td><font size=""1""><b>A<u>r</u>quivo:</b><br><input " & w_Disabled & " accesskey=""R"" type=""file"" name=""w_caminho"" class=""STI"" SIZE=""80"" MAXLENGTH=""100"" VALUE="""" ONMOUSEOVER=""popup('OBRIGATÓRIO. Clique no botão ao lado para localizar o arquivo. Ele será transferido automaticamente para o servidor.','white')""; ONMOUSEOUT=""kill()"">" 
    If w_caminho > "" Then 
       ShowHTML "              <b><a class=""SS"" href=""" & conFileVirtual & w_cliente & "/" & w_caminho & """ target=""_blank"" title=""Clique para exibir o arquivo atual."">Exibir</a></b>" 
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
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_pagina & par & "&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=L';"" name=""Botao"" value=""Cancelar"">" 
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
REM Fim da tela de anexos 
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
     DB_GetSolicData RS, w_chave, "ORGERAL"
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
  ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=../" & MontaURL("MESA") & """>"
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
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"

  ' Chama a rotina de visualização dos dados da ação, na opção "Listagem"
  ShowHTML VisualProjeto(w_chave, "V", w_usuario, P1, P4)

  ShowHTML "<HR>"
  AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"ORENVIO",R,O
  ShowHTML MontaFiltro("POST")
  ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & w_menu & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_tramite"" value=""" & w_tramite & """>"

  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "  <table width=""97%"" border=""0"">"
  ShowHTML "    <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
  If P1 <> 1 Then ' Se não for cadastramento
     SelecaoFase "<u>F</u>ase da ação:", "F", "Se deseja alterar a fase atual da ação, selecione a fase para a qual deseja enviá-la.", w_novo_tramite, w_menu, "w_novo_tramite", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='w_destinatario'; document.Form.submit();"""
     ' Se for envio para o cadastramento, exibe apenas as pessoas autorizadas a fazê-lo.
     If w_sg_tramite = "CI" Then
        SelecaoSolicResp "<u>D</u>estinatário:", "D", "Selecione, na relação, um destinatário para a ação.", w_destinatario, w_chave, w_novo_tramite, w_novo_tramite, "w_destinatario", "CADASTRAMENTO"
     Else
        SelecaoPessoa "<u>D</u>estinatário:", "D", "Selecione, na relação, um destinatário para a ação.", w_destinatario, null, "w_destinatario", "USUARIOS"
     End If
  Else
     SelecaoFase "<u>F</u>ase da ação:", "F", "Se deseja alterar a fase atual da ação, selecione a fase para a qual deseja enviá-la.", w_novo_tramite, w_menu, "w_novo_tramite", null, null
     SelecaoPessoa "<u>D</u>estinatário:", "D", "Selecione, na relação, um destinatário para a ação.", w_destinatario, null, "w_destinatario", "USUARIOS"
  End If
  ShowHTML "    <tr><td valign=""top"" colspan=2><font size=""1""><b>D<u>e</u>spacho:</b><br><textarea " & w_Disabled & " accesskey=""E"" name=""w_despacho"" class=""STI"" ROWS=5 cols=75 ONMOUSEOVER=""popup('Descreva o papel desempenhado pela área ou instituição na execução da ação.','white')""; ONMOUSEOUT=""kill()"">" & w_despacho & "</TEXTAREA></td>"
  ShowHTML "      </table>"
  ShowHTML "      <tr><td align=""LEFT"" colspan=4><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""STI"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
  ShowHTML "    <tr><td align=""center"" colspan=4><hr>"
  ShowHTML "      <input class=""STB"" type=""submit"" name=""Botao"" value=""Enviar"">"
  If P1 <> 1 Then ' Se não for cadastramento
     ' Volta para a listagem
     DB_GetMenuData RS, w_menu
     ShowHTML "      <input class=""STB"" type=""button"" onClick=""location.href='" & replace(RS("link"),w_dir,"") & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & rs("sigla") & MontaFiltro("GET") & "';"" name=""Botao"" value=""Abandonar"">"
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
  ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=../" & MontaURL("MESA") & """>"
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
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  Else
     BodyOpen "onLoad='document.Form.w_observacao.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"

  ' Chama a rotina de visualização dos dados da ação, na opção "Listagem"
  ShowHTML VisualProjeto(w_chave, "V", w_usuario, P1, P4)

  ShowHTML "<HR>"
  AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"ORENVIO",R,O
  ShowHTML MontaFiltro("POST")
  ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & w_menu & """>"
  DB_GetSolicData RS, w_chave, "ORGERAL"
  ShowHTML "<INPUT type=""hidden"" name=""w_tramite"" value=""" & RS("sq_siw_tramite") & """>"
  DesconectaBD

  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "  <table width=""97%"" border=""0"">"
  ShowHTML "    <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
  ShowHTML "    <tr><td valign=""top""><font size=""1""><b>A<u>n</u>otação:</b><br><textarea " & w_Disabled & " accesskey=""N"" name=""w_observacao"" class=""STI"" ROWS=5 cols=75 ONMOUSEOVER=""popup('Redija a anotação desejada.','white')""; ONMOUSEOUT=""kill()"">" & w_observacao & "</TEXTAREA></td>"
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
  ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=../" & MontaURL("MESA") & """>"
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
     Validate "w_custo_real", "Recurso executado", "VALOR", "1", 4, 18, "", "0123456789.,"
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
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"

  ' Chama a rotina de visualização dos dados da ação, na opção "Listagem"
  ShowHTML VisualProjeto(w_chave, "V", w_usuario, P1, P4)

  ShowHTML "<HR>"
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "  <table width=""97%"" border=""0"">"
  ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
  ShowHTML "          <tr>"
  
  ' Verifica se a ação tem etapas em aberto e avisa o usuário caso isso ocorra.
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
     ShowHTML "  alert('ATENÇÃO: das " & RS.RecordCount & " etapas desta ação, " & w_cont & " não têm 100% de conclusão!\n\nAinda assim você poderá concluir esta ação.');"
     ScriptClose
  End If
  DesconectaBD

  AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"ORCONC",R,O
  ShowHTML MontaFiltro("POST")
  ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & w_menu & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_concluida"" value=""S"">"
  DB_GetSolicData RS, w_chave, "ORGERAL"
  ShowHTML "<INPUT type=""hidden"" name=""w_tramite"" value=""" & RS("sq_siw_tramite") & """>"
  DesconectaBD
  Select Case RS_menu("data_hora")
     Case 1
        ShowHTML "              <td valign=""top""><font size=""1""><b><u>T</u>érmino da execução:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_fim_real"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_fim_real & """ onKeyDown=""FormataData(this,event);"" ONMOUSEOVER=""popup('Informe a data de término da execução da ação.','white')""; ONMOUSEOUT=""kill()""></td>"
     Case 2
        ShowHTML "              <td valign=""top""><font size=""1""><b><u>T</u>érmino da execução:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_fim_real"" class=""STI"" SIZE=""17"" MAXLENGTH=""17"" VALUE=""" & w_fim_real & """ onKeyDown=""FormataDataHora(this,event);"" ONMOUSEOVER=""popup('Informe a data/hora de término da execução da ação.','white')""; ONMOUSEOUT=""kill()""></td>"
     Case 3
        ShowHTML "              <td valign=""top""><font size=""1""><b>Iní<u>c</u>io da execução:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_inicio_real"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_inicio_real & """ onKeyDown=""FormataData(this,event);"" ONMOUSEOVER=""popup('Informe a data/hora de início da execução da ação.','white')""; ONMOUSEOUT=""kill()""></td>"
        ShowHTML "              <td valign=""top""><font size=""1""><b><u>T</u>érmino da execução:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_fim_real"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_fim_real & """ onKeyDown=""FormataData(this,event);"" ONMOUSEOVER=""popup('Informe a data de término da execução da ação.','white')""; ONMOUSEOUT=""kill()""></td>"
     Case 4
        ShowHTML "              <td valign=""top""><font size=""1""><b>Iní<u>c</u>io da execução:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_inicio_real"" class=""STI"" SIZE=""17"" MAXLENGTH=""17"" VALUE=""" & w_inicio_real & """ onKeyDown=""FormataDataHora(this,event);"" ONMOUSEOVER=""popup('Informe a data/hora de início da execução da ação.','white')""; ONMOUSEOUT=""kill()""></td>"
        ShowHTML "              <td valign=""top""><font size=""1""><b><u>T</u>érmino da execução:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_fim_real"" class=""STI"" SIZE=""17"" MAXLENGTH=""17"" VALUE=""" & w_fim_real & """ onKeyDown=""FormataDataHora(this,event);"" ONMOUSEOVER=""popup('Informe a data de término da execução da ação.','white')""; ONMOUSEOUT=""kill()""></td>"
  End Select
  ShowHTML "              <td valign=""top""><font size=""1""><b><u>R</u>ecurso executado:</b><br><input " & w_Disabled & " accesskey=""O"" type=""text"" name=""w_custo_real"" class=""STI"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_custo_real & """ onKeyDown=""FormataValor(this,18,2,event);"" ONMOUSEOVER=""popup('Informe o recurso utilizado para execução da ação, ou zero se não for o caso.','white')""; ONMOUSEOUT=""kill()""></td>"
  ShowHTML "          </table>"
  ShowHTML "    <tr><td valign=""top""><font size=""1""><b>Nota d<u>e</u> conclusão:</b><br><textarea " & w_Disabled & " accesskey=""E"" name=""w_nota_conclusao"" class=""STI"" ROWS=5 cols=75 ONMOUSEOVER=""popup('Descreva o quanto a ação atendeu aos resultados esperados.','white')""; ONMOUSEOUT=""kill()"">" & w_nota_conclusao & "</TEXTAREA></td>"
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
                     p_inicio, p_fim,       p_perc,   p_word,  p_destaque, _
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
  End If
  If cDbl(p_word) = 1 Then
     l_html = l_html & VbCrLf & "        <td><font size=""1"">" & p_destaque & p_titulo & "</b>"
  Else
     l_html = l_html & VbCrLf & "<A class=""HL"" HREF=""#"" onClick=""window.open('Projeto.asp?par=AtualizaEtapa&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_chave_aux=" & p_chave_aux & "&w_tipo=Volta&P1=10&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "','Meta','width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no'); return false;"" title=""Clique para exibir os dados!"">" & p_destaque & p_titulo & "</A>"
  End If
  l_html = l_html & VbCrLf & "        <td align=""center"" " & l_row & "><font size=""1"">" & FormataDataEdicao(p_fim) & "</td>"
  l_html = l_html & VbCrLf & "        <td nowrap align=""right"" " & l_row & "><font size=""1"">" & p_perc & " %</td>"
  If p_oper = "S" Then
     l_html = l_html & VbCrLf & "        <td align=""top"" nowrap " & l_row & "><font size=""1"">"
     ' Se for listagem de etapas no cadastramento da ação, exibe operações de alteração, exclusão e recursos
     If p_tipo = "PROJETO" Then
        l_html = l_html & VbCrLf & "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & p_chave & "&w_chave_aux=" & p_chave_aux & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Alterar"">Alt</A>&nbsp"
        l_html = l_html & VbCrLf & "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "GRAVA&R=" & w_pagina & par & "&O=E&w_chave=" & p_chave & "&w_chave_aux=" & p_chave_aux & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ onClick=""return confirm('Confirma a exclusão do registro?');"" title=""Excluir"">Excl</A>&nbsp"
     ' Caso contrário, é listagem de atualização de etapas. Neste caso, coloca apenas a opção de alteração
     Else
        l_html = l_html & VbCrLf & "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & p_chave & "&w_chave_aux=" & p_chave_aux & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Atualiza dados da etapa"">Atualizar</A>&nbsp"
     End If
     l_html = l_html & VbCrLf & "        </td>"
  Else
     If p_tipo = "ETAPA" Then
        l_html = l_html & VbCrLf & "        <td align=""top"" nowrap " & l_row & "><font size=""1"">"
        l_html = l_html & VbCrLf & "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=V&w_chave=" & p_chave & "&w_chave_aux=" & p_chave_aux & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Atualiza dados da etapa"">Exibir</A>&nbsp"
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
     w_html = w_html & "      <tr valign=""top""><td align=""center""><font size=2><b>INCLUSÃO DE AÇÃO</b></font><br><br><td></tr>" & VbCrLf
  ElseIf p_tipo = 2 Then
     w_html = w_html & "      <tr valign=""top""><td align=""center""><font size=2><b>TRAMITAÇÃO DE AÇÃO</b></font><br><br><td></tr>" & VbCrLf
  ElseIf p_tipo = 3 Then
     w_html = w_html & "      <tr valign=""top""><td align=""center""><font size=2><b>CONCLUSÃO DE AÇÃO</b></font><br><br><td></tr>" & VbCrLf
  End IF
  w_html = w_html & "      <tr valign=""top""><td><font size=2><b><font color=""#BC3131"">ATENÇÃO</font>: Esta é uma mensagem de envio automático. Não responda esta mensagem.</b></font><br><br><td></tr>" & VbCrLf


  ' Recupera os dados da ação
  DB_GetSolicData RSM, p_solic, "PJGERAL"
  
  w_nome = "Ação " & RSM("titulo")

  w_html = w_html & VbCrLf & "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  w_html = w_html & VbCrLf & "    <table width=""99%"" border=""0"">"
  w_html = w_html & VbCrLf & "      <tr><td><font size=2>Ação: <b>" & RSM("titulo") & "</b></font></td>"
      
  ' Identificação da ação
  w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>EXTRATO DA AÇÃO</td>"
  ' Se a classificação foi informada, exibe.
  If Not IsNull(RSM("sq_cc")) Then
     w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Classificação:<br><b>" & RSM("cc_nome") & " </b></td>"
  End If
  w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
  w_html = w_html & VbCrLf & "          <tr valign=""top"">"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Responsável pelo monitoramento:<br><b>" & RSM("nm_sol") & "</b></td>"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Unidade responsável pelo monitoramento:<br><b>" & RSM("nm_unidade_resp") & "</b></td>"
  w_html = w_html & VbCrLf & "          <tr valign=""top"">"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Data de recebimento:<br><b>" & FormataDataEdicao(RSM("inicio")) & " </b></td>"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Limite para conclusão:<br><b>" & FormataDataEdicao(RSM("fim")) & " </b></td>"
  w_html = w_html & VbCrLf & "          </table>"

  ' Informações adicionais
  If Nvl(RSM("descricao"),"") > "" Then 
     If Nvl(RSM("descricao"),"") > "" Then w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Resultados da ação:<br><b>" & CRLF2BR(RSM("descricao")) & " </b></td>" End If
  End If

  w_html = w_html & VbCrLf & "    </table>"
  w_html = w_html & VbCrLf & "</tr>"

  ' Dados da conclusão da ação, se ela estiver nessa situação
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
  Dim w_quantitativo_total, w_perc_conclusao, i
  Dim p_modulo
  Dim w_Null
  Dim w_chave_nova
  Dim w_mensagem
  Dim FS, F1, w_file

  Cabecalho
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  BodyOpen "onLoad=document.focus();"
  
  AbreSessao    
  Select Case SG
    Case "ORGERAL"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          
          'Recupera 10% dos dias de prazo da tarefa, para emitir o alerta  
          Dim w_dias
          DB_Get10PercentDays RS,Request("w_inicio"), Request("w_fim")
          w_dias = RS("dias")
          DesconectaBD
          'No caso de mudança da ação PPA, os regitros de outras iniciativas devem se apagadas. Caso a ação PPA seja
          'nula, deve-se apagar todas os registros e caso seja outra ação deve-se apagar aquela ação das outras iniciativas, caso exista.
          If Request("w_sq_orprioridade") = "" Then
             DML_PutProjetoOutras "E", Request("w_chave"), null
          Else
             DML_PutProjetoOutras "E", Request("w_chave"), Request("w_sq_prioridade")
          End If
          DML_PutProjetoGeral O, _
              Request("w_chave"), Request("w_menu"), Session("lotacao"), Request("w_solicitante"), Request("w_proponente"), _
              Session("sq_pessoa"), null, Request("w_sqcc"),Request("w_descricao"), Request("w_justificativa"), Request("w_inicio"), Request("w_fim"), Request("w_valor"), _
              Request("w_data_hora"), Request("w_sq_unidade_resp"), Request("w_titulo"), Request("w_prioridade"), Request("w_aviso"), w_dias, _
              Request("w_cidade"), Request("w_palavra_chave"), _
              null, null, null, null, null, null, null, _
              Request("w_sq_acao_ppa"), Request("w_sq_orprioridade"), Request("w_selecionada_mpog"), Request("w_selecionada_relevante"), null, _
              w_chave_nova, w_copia
          
          ScriptOpen "JavaScript"
          If O = "I" Then
             ' Envia e-mail comunicando a inclusão
             SolicMail Nvl(Request("w_chave"), w_chave_nova) ,1
             
             ' Recupera os dados para montagem correta do menu
             DB_GetMenuData RS1, w_menu
             ShowHTML "  parent.menu.location='../Menu.asp?par=ExibeDocs&O=A&w_chave=" & w_chave_nova & "&w_documento=Nr. " & w_chave_nova & "&R=" & R & "&SG=" & RS1("sigla") & "&TP=" & TP & MontaFiltro("GET") & "';"
          ElseIf O = "E" Then
             ShowHTML "  location.href='" & R & "&O=L&R=" & R & "&SG=ORCAD&w_menu=" & w_menu & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & MontaFiltro("GET") & "';"
          Else
             ' Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link
             DB_GetLinkData RS1, Session("p_cliente"), SG
             ShowHTML "  location.href='" & replace(RS1("link"),w_dir,"") & "&O=" & O & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
          End If
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "ORINFO"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then

          DML_PutProjetoInfo _
              Request("w_chave"), Request("w_descricao"), Request("w_justificativa"), Request("w_problema"), _
              Request("w_ds_acao"), Request("w_publico_alvo"), Request("w_estrategia"), Request("w_indicadores"), Request("w_objetivo")
          
          ScriptOpen "JavaScript"
          If O = "I" Then
             ' Recupera os dados para montagem correta do menu
             DB_GetMenuData RS1, w_menu
             ShowHTML "  parent.menu.location='../Menu.asp?par=ExibeDocs&O=A&w_chave=" & w_chave_nova & "&w_documento=Nr. " & w_chave_nova & "&R=" & R & "&SG=" & RS1("sigla") & "&TP=" & TP & MontaFiltro("GET") & "';"
          ElseIf O = "E" Then
             ShowHTML "  location.href='" & R & "&O=L&R=" & R & "&SG=ORCAD&w_menu=" & w_menu & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & MontaFiltro("GET") & "';"
          Else
             ' Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link
             DB_GetLinkData RS1, Session("p_cliente"), SG
             ShowHTML "  location.href='" & replace(RS1("link"),w_dir,"") & "&O=" & O & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
          End If
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "OROUTRAS"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          DML_PutProjetoOutras "E", Request("w_chave"), null
          For w_cont = 1 to Request.Form("w_outras_iniciativas").Count
             If Request("w_outras_iniciativas")(w_cont) > "" Then
                DML_PutProjetoOutras "I", Request("w_chave"), Request("w_outras_iniciativas")(w_cont)
             End If
          Next
          ScriptOpen "JavaScript"
          If O = "I" Then
             ' Recupera os dados para montagem correta do menu
             DB_GetMenuData RS1, w_menu
             ShowHTML "  parent.menu.location='../Menu.asp?par=ExibeDocs&O=A&w_chave=" & w_chave_nova & "&w_documento=Nr. " & w_chave_nova & "&R=" & R & "&SG=" & RS1("sigla") & "&TP=" & TP & MontaFiltro("GET") & "';"
          ElseIf O = "E" Then
             ShowHTML "  location.href='" & R & "&O=L&R=" & R & "&SG=ORCAD&w_menu=" & w_menu & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & MontaFiltro("GET") & "';"
          Else
             ' Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link
             DB_GetLinkData RS1, Session("p_cliente"), SG
             ShowHTML "  location.href='" & replace(RS1("link"),w_dir,"") & "&O=" & O & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
          End If
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "ORFINANC"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          DML_PutProjetoFinancAcao O, Request("w_chave"), Request("w_sq_acao_ppa"), Request("w_obs_financ")
          ScriptOpen "JavaScript"
          ' Recupera a sigla do serviço pai, para fazer a chamada ao menu
          DB_GetLinkData RS, Session("p_cliente"), SG
          ShowHTML "  location.href='" & replace(RS("link"),w_dir,"") & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
          DesconectaBD
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "ORRESP"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then

          DML_PutRespAcao Request("w_chave_aux"), Request("w_responsavel"), Request("w_telefone"), Request("w_email"), Request("w_tipo")
          
          ScriptOpen "JavaScript"
          ' Recupera a sigla do serviço pai, para fazer a chamada ao menu
          DB_GetLinkData RS, Session("p_cliente"), SG
          ShowHTML "  location.href='" & replace(RS("link"),w_dir,"") & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
          DesconectaBD
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If    
    
    Case "ORETAPA"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          DML_PutProjetoEtapa o, Request("w_chave"), Request("w_chave_aux"), Request("w_chave_pai"), _
             Request("w_titulo"), Request("w_descricao"), Request("w_ordem"), Request("w_inicio"), _
             Request("w_fim"), Request("w_perc_conclusao"), Request("w_orcamento"), _
             Request("w_sq_pessoa"), Request("w_sq_unidade"), Request("w_vincula_atividade"), w_usuario, _
             Request("w_programada"),Request("w_cumulativa"),Request("w_quantidade"),Request("w_unidade_medida")
          
          ScriptOpen "JavaScript"
          ' Recupera a sigla do serviço pai, para fazer a chamada ao menu
          DB_GetLinkData RS, Session("p_cliente"), SG
          ShowHTML "  location.href='" & replace(RS("link"),w_dir,"") & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
          DesconectaBD
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "ORCAD"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          
          ' Verifica se a meta é cumulativa ou não para o calculo do percentual de conclusão
          If Request("w_cumulativa") = "S" Then
             i = 1
             ' Faz a varredura do campos de quantidade e irá armazenar o percentual de conclusão do ultimo mês atualizazado
             While i < 13 
                If cDbl(Nvl(Request("w_quantitativo_"&i&""),0)) > 0 Then
                   w_perc_conclusao = (Request("w_quantitativo_"&i&"")*100)/Request("w_quantidade")
                End If
                i = i + 1
             wend   
          Else
             'Se não for cumulativa faz o percentual de conclusão com todos os valores do formulário
             w_quantitativo_total = cDbl(Nvl(Request("w_quantitativo_1"),0)) + cDbl(Nvl(Request("w_quantitativo_2"),0)) + cDbl(Nvl(Request("w_quantitativo_3"),0)) + cDbl(Nvl(Request("w_quantitativo_4"),0)) + _
                                    cDbl(Nvl(Request("w_quantitativo_5"),0)) + cDbl(Nvl(Request("w_quantitativo_6"),0)) + cDbl(Nvl(Request("w_quantitativo_7"),0)) + cDbl(Nvl(Request("w_quantitativo_8"),0)) + _
                                    cDbl(Nvl(Request("w_quantitativo_9"),0)) + cDbl(Nvl(Request("w_quantitativo_10"),0)) + cDbl(Nvl(Request("w_quantitativo_11"),0)) + cDbl(Nvl(Request("w_quantitativo_12"),0))
             If cDbl(Nvl(Request("w_quantidade"),0)) > 0 Then
                w_perc_conclusao = (w_quantitativo_total*100)/cDbl(Request("w_quantidade"))
             End If
          End If
          DML_PutAtualizaEtapa Request("w_chave"), Request("w_chave_aux"), w_usuario, Nvl(w_perc_conclusao,0), Request("w_situacao_atual"), _
                               Request("w_exequivel"), Request("w_justificativa_inex"), Request("w_outras_medidas")
          i = 1
          ' Gravação da execução física e feita mês por mês
          DML_PutEtapaMensal "E", Request("w_chave_aux"), Request("w_quantitativo_"&i&""), Request("w_referencia_"&i&"")
          While i < 13 
             If cDbl(Nvl(Request("w_quantitativo_"&i&""),0)) > 0 Then
                DML_PutEtapaMensal "I", Request("w_chave_aux"), Request("w_quantitativo_"&i&""), Request("w_referencia_"&i&"")
             End If
             i = i + 1
          wend   
          
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
    Case "ORRECURSO"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then

          DML_PutProjetoRec O, Request("w_chave"), Request("w_chave_aux"), Request("w_nome"), Request("w_tipo"), Request("w_descricao"), Request("w_finalidade")
          
          ScriptOpen "JavaScript"
          ' Recupera a sigla do serviço pai, para fazer a chamada ao menu
          DB_GetLinkData RS, Session("p_cliente"), SG
          ShowHTML "  location.href='" & replace(RS("link"),w_dir,"") & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
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
          ShowHTML "  location.href='" & replace(RS("link"),w_dir,"") & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & RS("sigla") & "';"
          DesconectaBD
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "ORINTERESS"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then

          DML_PutProjetoInter O, Request("w_chave"), Request("w_chave_aux"), Request("w_tipo_visao"), Request("w_envia_email")
          
          ScriptOpen "JavaScript"
          ' Recupera a sigla do serviço pai, para fazer a chamada ao menu
          DB_GetLinkData RS, Session("p_cliente"), SG
          ShowHTML "  location.href='" & replace(RS("link"),w_dir,"") & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
          DesconectaBD
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "ORAREAS"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then

          DML_PutProjetoAreas O, Request("w_chave"), Request("w_chave_aux"), Request("w_papel")
          
          ScriptOpen "JavaScript"
          ' Recupera a sigla do serviço pai, para fazer a chamada ao menu
          DB_GetLinkData RS, Session("p_cliente"), SG
          ShowHTML "  location.href='" & replace(RS("link"),w_dir,"") & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
          DesconectaBD
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
     Case "ORPANEXO"
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
          ShowHTML "  location.href='" & replace(RS("link"),w_dir,"") & "&O=L&w_chave=" & ul.Form("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';" 
          DesconectaBD
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "ORENVIO"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then

          DB_GetSolicData RS, Request("w_chave"), "ORGERAL"
          If cDbl(RS("sq_siw_tramite")) <> cDbl(Request("w_tramite")) Then
             ScriptOpen "JavaScript"
             ShowHTML "  alert('ATENÇÃO: Outro usuário já encaminhou esta ação para outra fase de execução!');"
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
                ShowHTML "  parent.menu.location='../Menu.asp?par=ExibeDocs&O=L&R=" & R & "&SG=" & RS("sigla") & "&TP=" & RemoveTP(RemoveTP(TP)) & MontaFiltro("GET") & "';"
                ScriptClose
                DesconectaBD
             Else
                ScriptOpen "JavaScript"
                ' Volta para a listagem
                DB_GetMenuData RS, w_menu
                ShowHTML "  location.href='" & replace(RS("link"),w_dir,"") & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & RemoveTP(TP) & "&SG=" & rs("sigla") & MontaFiltro("GET") & "';"
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
    Case "ORCONC"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then

          DB_GetSolicData RS, Request("w_chave"), "PJGERAL"
          If cDbl(RS("sq_siw_tramite")) <> cDbl(Request("w_tramite")) Then
             ScriptOpen "JavaScript"
             ShowHTML "  alert('ATENÇÃO: Outro usuário já encaminhou esta ação para outra fase de execução!');"
             ScriptClose
          Else
             DML_PutProjetoConc Request("w_menu"), Request("w_chave"), w_usuario, Request("w_tramite"), Request("w_inicio_real"), Request("w_fim_real"), Request("w_nota_conclusao"), Request("w_custo_real")
             
             ' Envia e-mail comunicando a conclusão
             SolicMail Request("w_chave"),3
             
             ScriptOpen "JavaScript"
             ' Volta para a listagem
             DB_GetMenuData RS, w_menu
             ShowHTML "  location.href='" & replace(RS("link"),w_dir,"") & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & rs("sigla") & MontaFiltro("GET") & "';"
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
  ' Verifica se o usuário tem lotação e localização
  If (len(Session("LOTACAO")&"") = 0 or len(Session("LOCALIZACAO")&"") = 0) and Session("LogOn") = "Sim" Then
    ScriptOpen "JavaScript"
    ShowHTML " alert('Você não tem lotação ou localização definida. Entre em contato com o RH!'); "
    ShowHTML " top.location.href='Default.asp'; "
    ScriptClose
   Exit Sub
  End If

  Select Case Par
    Case "INICIAL"
       Inicial
    Case "GERAL"
       Geral
    Case "INFOADIC"
       InfoAdic
    Case "OUTRAS"
       Iniciativas
    Case "FINANC"
       Financiamento
    Case "RESP"
       Responsaveis
    Case "ETAPA"
       Etapas
    Case "RECURSO"
       Recursos
    Case "ETAPARECURSO"
       EtapaRecursos
    Case "INTERESS"
       Interessados
    Case "AREAS"
       Areas
    Case "VISUAL"
       Visual
    Case "VISUALE"
       VisualE
    Case "EXCLUIR"
       Excluir
    Case "ENVIO"
       Encaminhamento
    Case "ANEXO"
       Anexos
    Case "ANOTACAO"
       Anotar
    Case "CONCLUIR"
       Concluir
    Case "ATUALIZAETAPA"
       AtualizaEtapa
    Case "GRAVA"
       Grava
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

