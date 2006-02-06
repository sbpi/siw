<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Geral.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Seguranca.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Gerencial.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /Gerencial.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Emite relatórios gerenciais de demandas
REM Mail     : alex@sbpi.com.br
REM Criacao  : 03/03/2003, 13:50
REM Versao   : 1.0.0.0
REM Local    : Brasília - DF
REM -------------------------------------------------------------------------
REM
REM Parâmetros recebidos:
REM    R (referência) = usado na rotina de gravação, com conteúdo igual ao parâmetro T
REM    O (operação)   = L   : Listagem
REM                   = P   : Filtragem
REM                   = V   : Geração de gráfico
REM                   = W   : Geração de documento no formato MS-Word (Office 2003)

' Verifica se o usuário está autenticado
If Session("LogOn") <> "Sim" Then
   EncerraSessao
End If

' Declaração de variáveis
Dim dbms, sp, RS, RS1, RS2, RS_menu
Dim P1, P2, P3, P4, TP, SG, FS, w_file
Dim R, O, w_Cont, w_Reg, w_Pagina, w_Disabled, w_TP, w_classe
Dim w_Assinatura, w_dir
Dim p_ativo, p_solicitante, p_prioridade, p_unidade, p_proponente, p_ordena, p_agrega
Dim p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_tipo, p_projeto, p_atividade
Dim p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc
Dim w_troca,w_cor, w_filter, w_cliente, w_usuario, w_menu
Dim w_sq_pessoa, w_pag, w_linha, w_nm_quebra, w_qt_quebra, w_filtro
Dim ul,File
Dim t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_acima, t_custo
Dim t_totsolic, t_totcad, t_tottram, t_totconc, t_totatraso, t_totaviso, t_totvalor, t_totacima, t_totcusto

w_troca            = Request("w_troca")
p_projeto          = uCase(Request("p_projeto"))
p_atividade        = uCase(Request("p_atividade"))
p_tipo             = uCase(Request("p_tipo"))
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
p_agrega           = uCase(Request("p_agrega"))
  
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

w_Pagina     = "Gerencial.asp?par="
w_dir        = "fbaj/"
w_Disabled   = "ENABLED"

If O = "" Then O = "P" End If

Select Case O
  Case "V" 
     w_TP = TP & " - Gráfico"
  Case "P" 
     w_TP = TP & " - Filtragem"
  Case Else
     w_TP = TP & " - Listagem"
End Select

w_cliente         = RetornaCliente()
w_usuario         = RetornaUsuario()
w_menu            = P2

' Recupera a configuração do serviço
DB_GetMenuData RS_menu, w_menu

Main

FechaSessao

Set t_valor       = Nothing
Set t_acima       = Nothing
Set t_totvalor    = Nothing
Set t_totacima    = Nothing
Set t_aviso       = Nothing
Set t_solic       = Nothing
Set t_cad         = Nothing
Set t_tram        = Nothing
Set t_conc        = Nothing
Set t_atraso      = Nothing
Set w_filtro      = Nothing
Set w_qt_quebra   = Nothing
Set w_nm_quebra   = Nothing
Set w_linha       = Nothing
Set w_pag         = Nothing
Set w_menu        = Nothing
Set w_usuario     = Nothing
Set w_cliente     = Nothing
Set w_filter      = Nothing
Set w_cor         = Nothing
Set ul            = Nothing
Set File          = Nothing
Set w_sq_pessoa   = Nothing
Set w_troca       = Nothing
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
Set p_tipo        = Nothing
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
Set p_agrega      = Nothing

Set RS            = Nothing
Set RS1           = Nothing
Set RS2           = Nothing
Set RS_menu       = Nothing
Set Par           = Nothing
Set P1            = Nothing
Set P2            = Nothing
Set P3            = Nothing
Set P4            = Nothing
Set TP            = Nothing
Set SG            = Nothing
Set FS            = Nothing
Set w_file        = Nothing
Set R             = Nothing
Set O             = Nothing
Set w_Classe      = Nothing
Set w_Cont        = Nothing
Set w_Pagina      = Nothing
Set w_Disabled    = Nothing
Set w_TP          = Nothing
Set w_Assinatura  = Nothing

REM =========================================================================
REM Pesquisa gerencial
REM -------------------------------------------------------------------------
Sub Gerencial
  Dim w_chave, w_fase_cad, w_fase_exec, w_fase_conc

  If O = "L" or O = "V" or O = "W" Then
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
        w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Centro de custos <td><font size=1>[<b>" & RS("nome") & "</b>]"
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
     If p_ini_i       > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Data recebimento <td><font size=1>[<b>" & p_ini_i & "-" & p_ini_f & "</b>]"     End If
     If p_fim_i       > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Limite conclusão <td><font size=1>[<b>" & p_fim_i & "-" & p_fim_f & "</b>]"     End If
     If p_atraso      = "S" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Situação <td><font size=1>[<b>Apenas atrasadas</b>]"                            End If
     If w_filtro > "" Then w_filtro = "<table border=0><tr valign=""top""><td><font size=1><b>Filtro:</b><td nowrap><font size=1><ul>" & w_filtro & "</ul></tr></table>"                    End If

     Select case p_agrega
        Case "GRDMETAPA"
           DB_GetSolicList RS1, P2, w_usuario, p_agrega, 4, _
                p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
                p_unidade, p_prioridade, p_ativo, p_proponente, _
                p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
                p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, p_projeto, p_atividade, null, null
           w_TP = TP & " - Por etapa de projeto"
           RS1.sort = "nm_etapa"
        Case "GRDMPROJ"
           DB_GetSolicList RS1, P2, w_usuario, p_agrega, 4, _
                p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
                p_unidade, p_prioridade, p_ativo, p_proponente, _
                p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
                p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, p_projeto, p_atividade, null, null
           w_TP = TP & " - Por projeto"
           RS1.sort = "nm_projeto"
        Case "GRDMPROP"
           DB_GetSolicList RS1, P2, w_usuario, p_agrega, 4, _
                p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
                p_unidade, p_prioridade, p_ativo, p_proponente, _
                p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
                p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, p_projeto, p_atividade, null, null
           w_TP = TP & " - Por proponente"
           RS1.Filter = "proponente <> null"
           RS1.sort = "proponente"
        Case "GRDMRESP"
           DB_GetSolicList RS1, P2, w_usuario, p_agrega, 4, _
                p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
                p_unidade, p_prioridade, p_ativo, p_proponente, _
                p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
                p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, p_projeto, p_atividade, null, null
           w_TP = TP & " - Por responsável"
           RS1.sort = "nm_solic"
        Case "GRDMRESPATU"
           DB_GetSolicList RS1, P2, w_usuario, p_agrega, 4, _
                p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
                p_unidade, p_prioridade, p_ativo, p_proponente, _
                p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
                p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, p_projeto, p_atividade, null, null
           w_TP = TP & " - Por executor"
           RS1.Filter = "executor <> null"
           RS1.sort = "nm_exec"
        Case "GRDMCC"
           DB_GetSolicList RS1, P2, w_usuario, p_agrega, 4, _
                p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
                p_unidade, p_prioridade, p_ativo, p_proponente, _
                p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
                p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, p_projeto, p_atividade, null, null
           w_TP = TP & " - Por centro de custos"
           RS1.sort = "sg_cc"
        Case "GRDMSETOR"
           DB_GetSolicList RS1, P2, w_usuario, p_agrega, 4, _
                p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
                p_unidade, p_prioridade, p_ativo, p_proponente, _
                p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
                p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, p_projeto, p_atividade, null, null
           w_TP = TP & " - Por setor responsável"
           RS1.sort = "nm_unidade_resp"
        Case "GRDMPRIO" 
           w_TP = TP & " - Por prioridade"
           DB_GetSolicList RS1, P2, w_usuario, p_agrega, 4, _
                p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
                p_unidade, p_prioridade, p_ativo, p_proponente, _
                p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
                p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, p_projeto, p_atividade, null, null
           RS1.sort = "nm_prioridade"
        Case "GRDMLOCAL" 
           w_TP = TP & " - Por UF"
           DB_GetSolicList RS1, P2, w_usuario, p_agrega, 4, _
                p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
                p_unidade, p_prioridade, p_ativo, p_proponente, _
                p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
                p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, p_projeto, p_atividade, null, null
           RS1.sort = "co_uf"
        Case "GRDMAREA" 
           w_TP = TP & " - Por área envolvida"
           DB_GetSolicGRA RS1, P2, w_usuario, p_agrega, 4, _
                p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
                p_unidade, p_prioridade, p_ativo, p_proponente, _
                p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
                p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, p_projeto, p_atividade
           RS1.sort = "nm_envolv"
        Case "GRDMINTER" 
           w_TP = TP & " - Por interessado"
           DB_GetSolicGRI RS1, P2, w_usuario, p_agrega, 4, _
                p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
                p_unidade, p_prioridade, p_ativo, p_proponente, _
                p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
                p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, p_projeto, p_atividade
           RS1.sort = "nm_inter"
     End Select
  End If
  
  If O = "W" Then
     HeaderWord null
     w_pag   = 1
     w_linha = 0
     CabecalhoWord w_cliente, w_TP, w_pag
     If w_filtro > "" Then ShowHTML w_filtro End If
  Else
     Cabecalho
     ShowHTML "<HEAD>"
     If O = "P" Then
        ScriptOpen "Javascript"
        CheckBranco
        FormataData
        ValidateOpen "Validacao"
        Validate "p_chave", "Número da demanda", "", "", "1", "18", "", "0123456789"
        Validate "p_prazo", "Dias para a data limite", "", "", "1", "2", "", "0123456789"
        Validate "p_proponente", "Proponente externo", "", "", "2", "90", "1", ""
        Validate "p_assunto", "Nome", "", "", "2", "90", "1", "1"
        Validate "p_palavra", "Palavras-chave", "", "", "2", "90", "1", "1"
        Validate "p_ini_i", "Emissão inicial", "DATA", "", "10", "10", "", "0123456789/"
        Validate "p_ini_f", "Emissão final", "DATA", "", "10", "10", "", "0123456789/"
        ShowHTML "  if ((theForm.p_ini_i.value != '' && theForm.p_ini_f.value == '') || (theForm.p_ini_i.value == '' && theForm.p_ini_f.value != '')) {"
        ShowHTML "     alert ('Informe ambas as datas de emissão ou nenhuma delas!');"
        ShowHTML "     theForm.p_ini_i.focus();"
        ShowHTML "     return false;"
        ShowHTML "  }"
        CompData "p_ini_i", "Emissão inicial", "<=", "p_ini_f", "Emissão final"
        Validate "p_fim_i", "Validade inicial", "DATA", "", "10", "10", "", "0123456789/"
        Validate "p_fim_f", "Validade final", "DATA", "", "10", "10", "", "0123456789/"
        ShowHTML "  if ((theForm.p_fim_i.value != '' && theForm.p_fim_f.value == '') || (theForm.p_fim_i.value == '' && theForm.p_fim_f.value != '')) {"
        ShowHTML "     alert ('Informe ambas as datas de validade ou nenhuma delas!');"
        ShowHTML "     theForm.p_fim_i.focus();"
        ShowHTML "     return false;"
        ShowHTML "  }"
        CompData "p_fim_i", "Validade inicial", "<=", "p_fim_f", "Validade final"
        If SG = "PROJETO" Then
           ShowHTML "  if (theForm.p_agrega[theForm.p_agrega.selectedIndex].value == 'GRDMETAPA' && theForm.p_projeto.selectedIndex == 0) {"
           ShowHTML "     alert ('A agregação por etapa exige a seleção de um projeto!');"
           ShowHTML "     theForm.p_projeto.focus();"
           ShowHTML "     return false;"
           ShowHTML "  }"
        End If
        ValidateClose
        ScriptClose
     Else
        ShowHTML "<TITLE>" & w_TP & "</TITLE>"
     End If
     ShowHTML "</HEAD>"
     ShowHTML "<BASE HREF=""" & Request.ServerVariables("server_name") & "/siw/"">"
     If w_Troca > "" Then ' Se for recarga da página
        BodyOpen "onLoad='document.Form." & w_Troca & ".focus();'"
     ElseIf InStr("P",O) > 0 Then
        If P1 = 1 Then ' Se for cadastramento
           BodyOpen "onLoad='document.Form.p_ordena.focus()';"
        Else
           BodyOpen "onLoad='document.Form.p_agrega.focus()';"
        End if
     Else
        BodyOpenClean "onLoad=document.focus();"
     End If
     If O = "L" Then
        ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
        ShowHTML "<HR>"
        If w_filtro > "" Then ShowHTML w_filtro End If
     Else
        ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
        ShowHTML "<HR>"
     End If
  End If

  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" or O = "W" Then
    If O = "L" Then
       ShowHTML "<tr><td><font size=""1"">"
       If MontaFiltro("GET") > "" Then
          ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_dir & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a>"
        Else
          ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_dir & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>F</u>iltrar (Inativo)</a>"
       End If
    End IF
    ImprimeCabecalho
    If RS1.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=10 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      If O = "L" Then
         ShowHTML "<SCRIPT LANGUAGE=""JAVASCRIPT"">"
         ShowHTML "  function lista (filtro, cad, exec, conc, atraso) {"
         ShowHTML "    if (filtro != -1) {"
         Select case p_agrega
            Case "GRDMETAPA"   ShowHTML "      document.Form.p_atividade.value=filtro;"
            Case "GRDMPROJ"    ShowHTML "      document.Form.p_projeto.value=filtro;"
            Case "GRDMPROP"    ShowHTML "      document.Form.p_proponente.value=filtro;"
            Case "GRDMRESP"    ShowHTML "      document.Form.p_solicitante.value=filtro;"
            Case "GRDMRESPATU" ShowHTML "      document.Form.p_usu_resp.value=filtro;"
            Case "GRDMCC"      ShowHTML "      document.Form.p_sqcc.value=filtro;"
            Case "GRDMSETOR"   ShowHTML "      document.Form.p_unidade.value=filtro;"
            Case "GRDMPRIO"    ShowHTML "      document.Form.p_prioridade.value=filtro;"
            Case "GRDMLOCAL"   ShowHTML "      document.Form.p_uf.value=filtro;"
            Case "GRDMAREA"    ShowHTML "      document.Form.p_area.value=filtro;"
            Case "GRDMINTER"   ShowHTML "      document.Form.p_inter.value=filtro;"
         End Select
         ShowHTML "    }"
         Select case p_agrega
            Case "GRDMETAPA"   ShowHTML "    else document.Form.p_atividade.value='" & Request("p_atividade")& "';"
            Case "GRDMPROJ"    ShowHTML "    else document.Form.p_projeto.value='" & Request("p_projeto")& "';"
            Case "GRDMPROP"    ShowHTML "    else document.Form.p_proponente.value=""" & Request("p_proponente")& """;"
            Case "GRDMRESP"    ShowHTML "    else document.Form.p_solicitante.value='" & Request("p_solicitante")& "';"
            Case "GRDMRESPATU" ShowHTML "    else document.Form.p_usu_resp.value='" & Request("p_usu_resp")& "';"
            Case "GRDMCC"      ShowHTML "    else document.Form.p_sqcc.value='" & Request("p_sqcc")& "';"
            Case "GRDMSETOR"   ShowHTML "    else document.Form.p_unidade.value='" & Request("p_unidade")& "';"
            Case "GRDMPRIO"    ShowHTML "    else document.Form.p_prioridade.value='" & Request("p_prioridade")& "';"
            Case "GRDMLOCAL"   ShowHTML "    else document.Form.p_uf.value='" & Request("p_uf")& "';"
            Case "GRDMAREA"    ShowHTML "    else document.Form.p_area.value='" & Request("p_area")& "';"
            Case "GRDMINTER"   ShowHTML "    else document.Form.p_inter.value='" & Request("p_inter")& "';"
         End Select
         DB_GetTramiteList RS2, P2, null
         RS2.Sort = "ordem"
         w_fase_exec = ""
         While Not RS2.EOF
            If RS2("sigla") = "CI" Then
               w_fase_cad = RS2("sq_siw_tramite")
            ElseIf RS2("sigla") = "AT" Then
               w_fase_conc = RS2("sq_siw_tramite")
            ElseIf RS2("ativo") = "S" Then
               w_fase_exec = w_fase_exec & "," & RS2("sq_siw_tramite")
            End If
            RS2.MoveNext
         Wend
         ShowHTML "    if (cad >= 0) document.Form.p_fase.value=" & w_fase_cad & ";"
         ShowHTML "    if (exec >= 0) document.Form.p_fase.value='" & Mid(w_fase_exec,2,100) & "';"
         ShowHTML "    if (conc >= 0) document.Form.p_fase.value=" & w_fase_conc & ";"
         ShowHTML "    if (cad==-1 && exec==-1 && conc==-1) document.Form.p_fase.value='" & Request("p_fase") & "'; "
         ShowHTML "    if (atraso >= 0) document.Form.p_atraso.value='S'; else document.Form.p_atraso.value='" & Request("p_atraso") & "'; "
         ShowHTML "    document.Form.submit();"
         ShowHTML "  }"
         ShowHTML "</SCRIPT>"
         DB_GetMenuData RS2, P2
         AbreForm "Form", RS2("link"), "POST", "return(Validacao(this));", "Lista",3,P2,RS2("P3"),null,w_TP,RS2("sigla"),w_pagina & par,"L"
         ShowHTML MontaFiltro("POST")
         Select case p_agrega
            Case "GRDMETAPA"   If Request("p_atividade") = ""   Then ShowHTML "<input type=""Hidden"" name=""p_atividade"" value="""">"     End If
            Case "GRDMPROJ"    If Request("p_projeto") = ""     Then ShowHTML "<input type=""Hidden"" name=""p_projeto"" value="""">"       End If
            Case "GRDMPROP"    If Request("p_proponente") = ""  Then ShowHTML "<input type=""Hidden"" name=""p_proponente"" value="""">"    End If
            Case "GRDMRESP"    If Request("p_solicitante") = "" Then ShowHTML "<input type=""Hidden"" name=""p_solicitante"" value="""">"   End If  
            Case "GRDMRESPATU" If Request("p_usu_resp") = ""    Then ShowHTML "<input type=""Hidden"" name=""p_usu_resp"" value="""">"      End If
            Case "GRDMCC"      If Request("p_sqcc") = ""        Then ShowHTML "<input type=""Hidden"" name=""p_sqcc"" value="""">"          End If
            Case "GRDMSETOR"   If Request("p_unidade") = ""     Then ShowHTML "<input type=""Hidden"" name=""p_unidade"" value="""">"       End If
            Case "GRDMPRIO"    If Request("p_prioridade") = ""  Then ShowHTML "<input type=""Hidden"" name=""p_prioridade"" value="""">"    End If
            Case "GRDMLOCAL"   If Request("p_uf") = ""          Then ShowHTML "<input type=""Hidden"" name=""p_uf"" value="""">"            End If
            Case "GRDMAREA"    If Request("p_area") = ""        Then ShowHTML "<input type=""Hidden"" name=""p_area"" value="""">"          End If
            Case "GRDMINTER"   If Request("p_inter") = ""       Then ShowHTML "<input type=""Hidden"" name=""p_inter"" value="""">"         End If
         End Select
      End If
  
      RS1.PageSize      = P4
      RS1.AbsolutePage  = P3
      w_nm_quebra       = ""
      w_qt_quebra       = 0
      t_solic           = 0
      t_cad             = 0
      t_tram            = 0
      t_conc            = 0
      t_atraso          = 0
      t_aviso           = 0
      t_valor           = 0
      t_acima           = 0
      t_custo           = 0
      t_totcusto        = 0
      t_totsolic        = 0
      t_totcad          = 0
      t_tottram         = 0
      t_totconc         = 0
      t_totatraso       = 0
      t_totaviso        = 0
      t_totvalor        = 0
      t_totacima        = 0
      While Not RS1.EOF
        Select Case p_agrega
           Case "GRDMETAPA"
              If w_nm_quebra <> RS1("nm_etapa") Then
                 If w_qt_quebra > 0 Then
                    ImprimeLinha t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_custo, t_acima, w_chave
                    w_linha = w_linha + 2
                 End If
                 If O <> "W" or (O = "W" and w_linha <= 25) Then
                    ' Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("nm_etapa") & " (Etapa: " & MontaOrdemEtapa(RS1("sq_projeto_etapa")) & ")"
                 End If
                 w_nm_quebra       = RS1("nm_etapa")
                 w_chave           = RS1("sq_projeto_etapa")
                 w_qt_quebra       = 0
                 t_solic           = 0
                 t_cad             = 0
                 t_tram            = 0
                 t_conc            = 0
                 t_atraso          = 0
                 t_aviso           = 0
                 t_valor           = 0
                 t_acima           = 0
                 t_custo           = 0
                 w_linha           = w_linha + 1
              End If
           Case "GRDMPROJ"
              If w_nm_quebra <> RS1("nm_projeto") Then
                 If w_qt_quebra > 0 Then
                    ImprimeLinha t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_custo, t_acima, w_chave
                    w_linha = w_linha + 2
                 End If
                 If O <> "W" or (O = "W" and w_linha <= 25) Then
                    ' Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("nm_projeto")
                 End If
                 w_nm_quebra       = RS1("nm_projeto")
                 w_chave           = RS1("sq_solic_pai")
                 w_qt_quebra       = 0
                 t_solic           = 0
                 t_cad             = 0
                 t_tram            = 0
                 t_conc            = 0
                 t_atraso          = 0
                 t_aviso           = 0
                 t_valor           = 0
                 t_acima           = 0
                 t_custo           = 0
                 w_linha           = w_linha + 1
              End If
           Case "GRDMPROP"
              If w_nm_quebra <> RS1("proponente") Then
                 If w_qt_quebra > 0 Then
                    ImprimeLinha t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_custo, t_acima, w_chave
                    w_linha = w_linha + 2
                 End If
                 If O <> "W" or (O = "W" and w_linha <= 25) Then
                    ' Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("proponente")
                 End If
                 w_nm_quebra       = RS1("proponente")
                 w_chave           = RS1("proponente")
                 w_qt_quebra       = 0
                 t_solic           = 0
                 t_cad             = 0
                 t_tram            = 0
                 t_conc            = 0
                 t_atraso          = 0
                 t_aviso           = 0
                 t_valor           = 0
                 t_acima           = 0
                 t_custo           = 0
                 w_linha           = w_linha + 1
              End If
           Case "GRDMRESP"
              If w_nm_quebra <> RS1("nm_solic") Then
                 If w_qt_quebra > 0 Then
                    ImprimeLinha t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_custo, t_acima, w_chave
                    w_linha = w_linha + 2
                 End If
                 If O <> "W" or (O = "W" and w_linha <= 25) Then
                    ' Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("nm_solic")
                 End If
                 w_nm_quebra       = RS1("nm_solic")
                 w_chave           = RS1("solicitante")
                 w_qt_quebra       = 0
                 t_solic           = 0
                 t_cad             = 0
                 t_tram            = 0
                 t_conc            = 0
                 t_atraso          = 0
                 t_aviso           = 0
                 t_valor           = 0
                 t_acima           = 0
                 t_custo           = 0
                 w_linha           = w_linha + 1
              End If
           Case "GRDMRESPATU"
              If w_nm_quebra <> RS1("nm_exec") Then
                 If w_qt_quebra > 0 Then
                    ImprimeLinha t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_custo, t_acima, w_chave
                    w_linha = w_linha + 2
                 End If
                 If O <> "W" or (O = "W" and w_linha <= 25) Then
                    ' Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("nm_exec")
                 End If
                 w_nm_quebra       = RS1("nm_exec")
                 w_chave           = RS1("executor")
                 w_qt_quebra       = 0
                 t_solic           = 0
                 t_cad             = 0
                 t_tram            = 0
                 t_conc            = 0
                 t_atraso          = 0
                 t_aviso           = 0
                 t_valor           = 0
                 t_acima           = 0
                 t_custo           = 0
                 w_linha           = w_linha + 1
              End If
           Case "GRDMCC"
              If w_nm_quebra <> RS1("sg_cc") Then
                 If w_qt_quebra > 0 Then
                    ImprimeLinha t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_custo, t_acima, w_chave
                    w_linha = w_linha + 2
                 End If
                 If O <> "W" or (O = "W" and w_linha <= 25) Then
                    ' Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("sg_cc")
                 End If
                 w_nm_quebra       = RS1("sg_cc")
                 w_chave           = RS1("sq_cc")
                 w_qt_quebra       = 0
                 t_solic           = 0
                 t_cad             = 0
                 t_tram            = 0
                 t_conc            = 0
                 t_atraso          = 0
                 t_aviso           = 0
                 t_valor           = 0
                 t_acima           = 0
                 t_custo           = 0
                 w_linha           = w_linha + 1
              End If
           Case "GRDMSETOR"
              If w_nm_quebra <> RS1("nm_unidade_resp") Then
                 If w_qt_quebra > 0 Then
                    ImprimeLinha t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_custo, t_acima, w_chave
                    w_linha = w_linha + 2
                 End If
                 If O <> "W" or (O = "W" and w_linha <= 25) Then
                    ' Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("nm_unidade_resp")
                 End If
                 w_nm_quebra       = RS1("nm_unidade_resp")
                 w_chave           = RS1("sq_unidade_resp")
                 w_qt_quebra       = 0
                 t_solic           = 0
                 t_cad             = 0
                 t_tram            = 0
                 t_conc            = 0
                 t_atraso          = 0
                 t_aviso           = 0
                 t_valor           = 0
                 t_acima           = 0
                 t_custo           = 0
                 w_linha           = w_linha + 1
              End If
           Case "GRDMPRIO"
              If w_nm_quebra <> RS1("nm_prioridade") Then
                 If w_qt_quebra > 0 Then 
                    ImprimeLinha t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_custo, t_acima, w_chave
                    w_linha = w_linha + 2
                 End If
                 If O <> "W" or (O = "W" and w_linha <= 25) Then
                    ' Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("nm_prioridade")
                 End If
                 w_nm_quebra       = RS1("nm_prioridade")
                 w_chave           = RS1("prioridade")
                 w_qt_quebra       = 0
                 t_solic           = 0
                 t_cad             = 0
                 t_tram            = 0
                 t_conc            = 0
                 t_atraso          = 0
                 t_aviso           = 0
                 t_valor           = 0
                 t_acima           = 0
                 t_custo           = 0
                 w_linha           = w_linha + 1
              End If
           Case "GRDMLOCAL"
              If w_nm_quebra <> RS1("co_uf") Then
                 If w_qt_quebra > 0 Then
                    ImprimeLinha t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_custo, t_acima, w_chave
                    w_linha = w_linha + 2
                 End If
                 If O <> "W" or (O = "W" and w_linha <= 25) Then
                    ' Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("co_uf")
                 End If
                 w_nm_quebra       = RS1("co_uf")
                 w_chave           = RS1("co_uf")
                 w_qt_quebra       = 0
                 t_solic           = 0
                 t_cad             = 0
                 t_tram            = 0
                 t_conc            = 0
                 t_atraso          = 0
                 t_aviso           = 0
                 t_valor           = 0
                 t_acima           = 0
                 t_custo           = 0
                 w_linha           = w_linha + 1
              End If
           Case "GRDMAREA"
              If w_nm_quebra <> RS1("nm_envolv") Then
                 If w_qt_quebra > 0 Then
                    ImprimeLinha t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_custo, t_acima, w_chave
                    w_linha = w_linha + 2
                 End If
                 If O <> "W" or (O = "W" and w_linha <= 25) Then
                    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("nm_envolv")
                 End If
                 w_nm_quebra       = RS1("nm_envolv")
                 w_chave           = RS1("sq_unidade")
                 w_qt_quebra       = 0
                 t_solic           = 0
                 t_cad             = 0
                 t_tram            = 0
                 t_conc            = 0
                 t_atraso          = 0
                 t_aviso           = 0
                 t_valor           = 0
                 t_acima           = 0
                 t_custo           = 0
                 w_linha           = w_linha + 1
              End If
           Case "GRDMINTER"
              If w_nm_quebra <> RS1("nm_inter") Then
                 If w_qt_quebra > 0 Then
                    ImprimeLinha t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_custo, t_acima, w_chave
                    w_linha = w_linha + 2
                 End If
                 If O <> "W" or (O = "W" and w_linha <= 25) Then
                    ' Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("nm_inter")
                 End If
                 w_nm_quebra       = RS1("nm_inter")
                 w_chave           = RS1("sq_unidade")
                 w_qt_quebra       = 0
                 t_solic           = 0
                 t_cad             = 0
                 t_tram            = 0
                 t_conc            = 0
                 t_atraso          = 0
                 t_aviso           = 0
                 t_valor           = 0
                 t_acima           = 0
                 t_custo           = 0
                 w_linha           = w_linha + 1
              End If
        End Select
        If O = "W" and w_linha > 25 Then ' Se for geração de MS-Word, quebra a página
           ShowHTML "    </table>"
           ShowHTML "  </td>"
           ShowHTML "</tr>"
           ShowHTML "</table>"
           ShowHTML "</center></div>"
           ShowHTML "    <br style=""page-break-after:always"">"
           w_linha = 0
           w_pag   = w_pag + 1
           CabecalhoWord w_cliente, w_TP, w_pag
           If w_filtro > "" Then ShowHTML w_filtro End If
           ShowHTML "<div align=center><center>"
           ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
           ImprimeCabecalho
           Select Case p_agrega
              Case "GRDMETAPA"   ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("nm_etapa")
              Case "GRDMPROJ"    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("nm_projeto")
              Case "GRDMPROP"    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("proponente")
              Case "GRDMRESP"    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("nm_solic")
              Case "GRDMRESPATU" ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("nm_exec")
              Case "GRDMCC"      ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("sg_cc")
              Case "GRDMSETOR"   ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("nm_unidade_resp")
              Case "GRDMPRIO"    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("nm_prioridade")
              Case "GRDMLOCAL"   ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("co_uf")
              Case "GRDMAREA"    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("nm_envolv")
              Case "GRDMINTER"   ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("nm_inter")
           End Select
           w_linha = w_linha + 1
        End If
        If RS1("concluida") = "N" Then
           If RS1("fim") < Date() Then
              t_atraso    = t_atraso + 1
              t_totatraso = t_totatraso + 1
           ElseIf RS1("aviso_prox_conc") = "S" and (RS1("aviso") <= Date()) Then
              t_aviso    = t_aviso + 1
              t_totaviso = t_totaviso + 1
           End IF

           If cDbl(RS1("or_tramite")) = 1 Then
              t_cad    = t_cad + 1
              t_totcad = t_totcad + 1
           Else
             t_tram    = t_tram + 1
             t_tottram = t_tottram + 1
           End If
        Else
           t_conc    = t_conc + 1
           t_totconc = t_totconc + 1
           If cDbl(RS1("valor")) < cDbl(RS1("custo_real")) Then
              t_acima    = t_acima + 1
              t_totacima = t_totacima + 1
           End If
        End If
        t_solic    = t_solic + 1
        t_valor    = t_valor + Nvl(cDbl(RS1("valor")),0)
        t_custo    = t_custo + Nvl(cDbl(RS1("custo_real")),0)
        
        t_totvalor = t_totvalor + Nvl(cDbl(RS1("valor")),0)
        t_totcusto = t_totcusto + Nvl(cDbl(RS1("custo_real")),0)
        t_totsolic = t_totsolic + 1
        w_qt_quebra = w_qt_quebra + 1
        RS1.MoveNext
      wend
      ImprimeLinha t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_custo, t_acima, w_chave

      ShowHTML "      <tr bgcolor=""#DCDCDC"" valign=""top"" align=""right"">"
      ShowHTML "          <td><font size=""1""><b>Totais</font></td>"
      ImprimeLinha t_totsolic, t_totcad, t_tottram, t_totconc, t_totatraso, t_totaviso, t_totvalor, t_totcusto, t_totacima, -1
    End If
    ShowHTML "      </FORM>"
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    
    If RS1.RecordCount > 0 and p_tipo = "N" Then ' Coloca o gráfico somente se o usuário desejar
       ShowHTML "<tr><td align=""center"" height=20>"
       ShowHTML "<tr><td align=""center""><IMG SRC=""geragrafico.php?p_tipo="&SG&"&p_grafico=Barra&p_tot="&t_totsolic&"&p_cad="&t_totcad&"&p_tram="&t_tottram&"&p_conc="&t_totconc&"&p_atraso="&t_totatraso&"&p_aviso="&t_totaviso&"&p_acima="&t_totacima&""">"
       ShowHTML "<tr><td align=""center"" height=20>"
       If (t_totcad + t_tottram) > 0 Then
          ShowHTML "<tr><td align=""center""><IMG SRC=""geragrafico.php?p_tipo="&SG&"&p_grafico=Pizza&p_tot="&t_totsolic&"&p_cad="&t_totcad&"&p_tram="&t_tottram&"&p_conc="&t_totconc&"&p_atraso="&t_totatraso&"&p_aviso="&t_totaviso&"&p_acima="&t_totacima&""">"
       End If
    End If
    
  ElseIf Instr("P",O) > 0 Then
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    AbreForm "Form", w_Pagina & par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"

    ' Exibe parâmetros de apresentação
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td align=""center"" valign=""top""><table border=0 width=""90%"" cellspacing=0>"
    ShowHTML "         <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Parâmetros de Apresentação</td>"
    ShowHTML "         <tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b><U>A</U>gregar por:<br><SELECT ACCESSKEY=""O"" " & w_Disabled & " class=""STS"" name=""p_agrega"" size=""1"">"
    If p_agrega = "GRCLA"   Then ShowHTML "          <option value=""GRCLA"" selected>Classificação dos Hostels"        Else ShowHTML "          <option value=""GRCLA"">Classificação dos Hostels"     End If
    If p_agrega = "GRCON"   Then ShowHTML "          <option value=""GRCON"" selected>Conhece Albergue da Juventude"    Else ShowHTML "          <option value=""GRCON"">Conhece Albergue da Juventude" End If
    If p_agrega = "GRDEST"  Then ShowHTML "          <option value=""GRDEST"" selected>Destino da viagem"               Else ShowHTML "          <option value=""GRDEST"">Destino da viagem"            End If
    If p_agrega = "GRESC"   Then ShowHTML "          <option value=""GRESC"" selected>Escolaridade"                     Else ShowHTML "          <option value=""GRESC"">Escolaridade"                  End If
    If p_agrega = "GRUF"    Then ShowHTML "          <option value=""GRUF"" selected>Estado"                            Else ShowHTML "          <option value=""GRUF"">Estado"                         End If
    If p_agrega = "GRFAIXA" Then ShowHTML "          <option value=""GRFAIXA"" selected>Faixa etária"                   Else ShowHTML "          <option value=""GRFAIXA"">Faixa etária"                End If
    If p_agrega = "GRMOT"   Then ShowHTML "          <option value=""GRMOT"" selected>Motivo da viagem"                 Else ShowHTML "          <option value=""GRMOT"">Motivo da viagem"              End If
    If p_agrega = "grsexo"  Then ShowHTML "          <option value=""grsexo"" selected>Sexo"                            Else ShowHTML "          <option value=""grsexo"">Sexo"                         End If
    ShowHTML "          </select></td>"
    'MontaRadioNS "<b>Inibe exibição do gráfico?</b>", p_tipo, "p_tipo"
    ShowHTML "         </tr>"
    ShowHTML "         <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Critérios de Busca</td>"

    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "      <tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b><u>E</u>missão entre:</b><br><input " & w_Disabled & " accesskey=""E"" type=""text"" name=""p_ini_i"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_ini_i & """ onKeyDown=""FormataData(this,event);""> e <input " & w_Disabled & " accesskey=""C"" type=""text"" name=""p_ini_f"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_ini_f & """ onKeyDown=""FormataData(this,event);""></td>"
    ShowHTML "          <td><font size=""1""><b>Término da validade en<u>t</u>re:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""p_fim_i"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_fim_i & """ onKeyDown=""FormataData(this,event);""> e <input " & w_Disabled & " accesskey=""T"" type=""text"" name=""p_fim_f"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_fim_f & """ onKeyDown=""FormataData(this,event);""></td>"
    ShowHTML "      <tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Exibe somente carteiras vencidas?</b><br>"
    If p_atraso = "S" Then
       ShowHTML "              <input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_atraso"" value=""S"" checked> Sim <input " & w_Disabled & " class=""STR"" class=""STR"" type=""radio"" name=""p_atraso"" value=""N""> Não"
    Else
       ShowHTML "              <input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_atraso"" value=""S""> Sim <input " & w_Disabled & " class=""STR"" class=""STR"" type=""radio"" name=""p_atraso"" value=""N"" checked> Não"
    End If
    ShowHTML "          <td><font size=""1""><b>Dias para a data limi<U>t</U>e:<br><INPUT ACCESSKEY=""T"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_prazo"" size=""2"" maxlength=""2"" value=""" & p_prazo & """></td>"
    ShowHTML "      <tr valign=""top"">"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_assunto"" size=""25"" maxlength=""50"" value=""" & p_assunto & """></td>"
    SelecaoSexo "Se<u>x</u>o:", "X", null, p_solicitante, null, "p_solicitante", null, null
    ShowHTML "      <tr>"
    SelecaoClassificacao "<u>C</u>lassificação:", "C", null, p_prioridade, null, "p_prioridade", null, null
    SelecaoDestino "<u>D</u>estino:", "D", null, p_proponente, null, "p_proponente", null, null
    ShowHTML "      <tr valign=""top"">"
    SelecaoMotivo_Viagem "M<u>o</u>otivo da viagem:", "O", null, p_usu_resp, null, "p_usu_resp", null, null
    SelecaoForma_Conhece "C<U>o</U>mo conheceu o movimento:", "C", null, p_uorg_resp, null, "p_uorg_resp", null, null
    ShowHTML "      <tr valign=""top"">"
    SelecaoPais "<u>P</u>aís:", "P", null, p_pais, null, "p_pais", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.target=''; document.Form.w_troca.value='p_regiao'; document.Form.submit();"""
    SelecaoRegiao "<u>R</u>egião:", "R", null, p_regiao, p_pais, "p_regiao", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.target=''; document.Form.w_troca.value='p_uf'; document.Form.submit();"""
    ShowHTML "      <tr>"
    SelecaoEstado "E<u>s</u>tado:", "S", null, p_uf, p_pais, "N", "p_uf", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.target=''; document.Form.w_troca.value='p_cidade'; document.Form.submit();"""
    SelecaoCidade "<u>C</u>idade:", "C", null, p_cidade, p_pais, p_uf, "p_cidade", null, null
    ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""2"">"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Exibir"" onClick=""javascript:document.Form.O.value='L';"">"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Gerar Word"" onClick=""javascript:document.Form.O.value='W'; document.Form.target='Word'"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
    ShowHTML "</table>"
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_fase_cad    = Nothing
  Set w_fase_exec   = Nothing
  Set w_fase_conc   = Nothing
  Set w_chave       = Nothing
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de impressao do cabecalho
REM -------------------------------------------------------------------------
Sub ImprimeCabecalho
    ShowHTML "<tr><td align=""center"">"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""#DCDCDC"" align=""center"">"
    Select case p_agrega
       Case "GRDMETAPA"   ShowHTML "          <td><font size=""1""><b>Etapa</font></td>"
       Case "GRDMPROJ"    ShowHTML "          <td><font size=""1""><b>Projeto</font></td>"
       Case "GRDMPROP"    ShowHTML "          <td><font size=""1""><b>Proponente</font></td>"
       Case "GRDMRESP"    ShowHTML "          <td><font size=""1""><b>Responsável</font></td>"
       Case "GRDMRESPATU" ShowHTML "          <td><font size=""1""><b>Executor</font></td>"
       Case "GRDMCC"      ShowHTML "          <td><font size=""1""><b>Centro de custos</font></td>"
       Case "GRDMSETOR"   ShowHTML "          <td><font size=""1""><b>Setor responsável</font></td>"
       Case "GRDMPRIO"    ShowHTML "          <td><font size=""1""><b>Prioridade</font></td>"
       Case "GRDMLOCAL"   ShowHTML "          <td><font size=""1""><b>UF</font></td>"
       Case "GRDMAREA"    ShowHTML "          <td><font size=""1""><b>Área envolvida</font></td>"
       Case "GRDMINTER"   ShowHTML "          <td><font size=""1""><b>Interessado</font></td>"
    End Select
    ShowHTML "          <td><font size=""1""><b>Total</font></td>"
    ShowHTML "          <td><font size=""1""><b>Cad.</font></td>"
    ShowHTML "          <td><font size=""1""><b>Exec.</font></td>"
    ShowHTML "          <td><font size=""1""><b>Conc.</font></td>"
    ShowHTML "          <td><font size=""1""><b>Atraso</font></td>"
    ShowHTML "          <td><font size=""1""><b>Aviso</font></td>"
    ShowHTML "          <td><font size=""1""><b>$ Prev.</font></td>"
    ShowHTML "          <td><font size=""1""><b>$ Real</font></td>"
    ShowHTML "          <td><font size=""1""><b>Real > Previsto</font></td>"
    ShowHTML "        </tr>"
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de impressao da linha resumo
REM -------------------------------------------------------------------------
Sub ImprimeLinha (p_solic, p_cad, p_tram, p_conc, p_atraso, p_aviso, p_valor, p_custo, p_acima, p_chave)
    If O = "L"                  Then ShowHTML "          <td align=""right""><font size=""1""><a class=""hl"" href=""javascript:lista('" & p_chave & "', -1, -1, -1, -1);"" onMouseOver=""window.status='Exibe as demandas.'; return true"" onMouseOut=""window.status=''; return true"">" & FormatNumber(p_solic,0) & "</a>&nbsp;</font></td>"                  Else ShowHTML "          <td align=""right""><font size=""1"">" & FormatNumber(p_solic,0) & "&nbsp;</font></td>" End If
    If p_cad > 0    and O = "L" Then ShowHTML "          <td align=""right""><a class=""hl"" href=""javascript:lista('" & p_chave & "', 0, -1, -1, -1);"" onMouseOver=""window.status='Exibe as demandas.'; return true"" onMouseOut=""window.status=''; return true""><font size=""1"">" & FormatNumber(p_cad,0) & "</a>&nbsp;</font></td>"                     Else ShowHTML "          <td align=""right""><font size=""1"">" & FormatNumber(p_cad,0) & "&nbsp;</font></td>"   End If
    If p_tram > 0   and O = "L" Then ShowHTML "          <td align=""right""><a class=""hl"" href=""javascript:lista('" & p_chave & "', -1, 0, -1, -1);"" onMouseOver=""window.status='Exibe as demandas.'; return true"" onMouseOut=""window.status=''; return true""><font size=""1"">" & FormatNumber(p_tram,0) & "</a>&nbsp;</font></td>"                    Else ShowHTML "          <td align=""right""><font size=""1"">" & FormatNumber(p_tram,0) & "&nbsp;</font></td>"  End If
    If p_conc > 0   and O = "L" Then ShowHTML "          <td align=""right""><a class=""hl"" href=""javascript:lista('" & p_chave & "', -1, -1, 0, -1);"" onMouseOver=""window.status='Exibe as demandas.'; return true"" onMouseOut=""window.status=''; return true""><font size=""1"">" & FormatNumber(p_conc,0) & "</a>&nbsp;</font></td>"                    Else ShowHTML "          <td align=""right""><font size=""1"">" & FormatNumber(p_conc,0) & "&nbsp;</font></td>"  End If
    If p_atraso > 0 and O = "L" Then ShowHTML "          <td align=""right""><a class=""hl"" href=""javascript:lista('" & p_chave & "', -1, -1, -1, 0);"" onMouseOver=""window.status='Exibe as demandas.'; return true"" onMouseOut=""window.status=''; return true""><font size=""1"" color=""red""><b>" & FormatNumber(p_atraso,0) & "</a>&nbsp;</font></td>" Else ShowHTML "          <td align=""right""><font size=""1""><b>" & p_atraso & "&nbsp;</font></td>"             End If
    If p_aviso > 0  and O = "L" Then ShowHTML "          <td align=""right""><font size=""1"" color=""red""><b>" & FormatNumber(p_aviso,0) & "&nbsp;</font></td>"  Else ShowHTML "          <td align=""right""><font size=""1""><b>" & p_aviso & "&nbsp;</font></td>"  End If
    ShowHTML "          <td align=""right""><font size=""1"">" & FormatNumber(p_valor,2) & "&nbsp;</font></td>"
    ShowHTML "          <td align=""right""><font size=""1"">" & FormatNumber(p_custo,2) & "&nbsp;</font></td>"
    If p_acima > 0  Then ShowHTML "          <td align=""right""><font size=""1"" color=""red""><b>" & FormatNumber(p_acima,0) & "&nbsp;</font></td>"  Else ShowHTML "          <td align=""right""><font size=""1""><b>" & p_acima & "&nbsp;</font></td>"  End If
    ShowHTML "        </tr>"
End Sub
REM =========================================================================
REM Fim da rotina
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
    Case "INICIAL"
       Gerencial
    Case Else
       Cabecalho
       ShowHTML "<BASE HREF=""" & Request.ServerVariables("server_name") & "/siw/"">"
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

