<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Geral.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_EO.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Cliente.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Seguranca.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Link.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /GR_Contratos.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Consultas gerenciais do módulo de contratos
REM Mail     : alex@sbpi.com.br
REM Criacao  : 03/06/2005, 12:15
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
Dim w_Assinatura
Dim p_ativo, p_solicitante, p_prioridade, p_unidade, p_proponente, p_ordena, p_agrega, p_tamanho
Dim p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_tipo, p_projeto, p_atividade
Dim p_chave, p_objeto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc
Dim w_troca,w_cor, w_filter, w_cliente, w_usuario, w_menu
Dim w_sq_pessoa, w_pag, w_linha, w_nm_quebra, w_qt_quebra, w_filtro
Dim ul,File
Dim t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_acima, t_custo
Dim t_totsolic, t_totcad, t_tottram, t_totconc, t_totatraso, t_totaviso, t_totvalor, t_totacima, t_totcusto
Dim w_dir, w_dir_volta
Set RS = Server.CreateObject("ADODB.RecordSet")
Set RS1 = Server.CreateObject("ADODB.RecordSet")
Set RS2 = Server.CreateObject("ADODB.RecordSet")
Set RS_menu = Server.CreateObject("ADODB.RecordSet")
Set RS = Server.CreateObject("ADODB.RecordSet")
Set RS1 = Server.CreateObject("ADODB.RecordSet")
Set RS2 = Server.CreateObject("ADODB.RecordSet")
Set RS_menu = Server.CreateObject("ADODB.RecordSet")

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
p_objeto          = uCase(Request("p_objeto"))
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
p_tamanho          = uCase(Request("p_tamanho"))
  
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

w_Pagina     = "GR_Contratos.asp?par="
w_Dir        = "mod_ac/"
w_dir_volta  = "../"  
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
Set p_objeto      = Nothing 
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
Set p_tamanho     = Nothing

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
Set w_dir         = Nothing
Set w_dir_volta   = Nothing

REM =========================================================================
REM Pesquisa gerencial
REM -------------------------------------------------------------------------
Sub Gerencial
  Dim w_chave, w_fase_cad, w_fase_exec, w_fase_conc

  If O = "L" or O = "V" or O = "W" Then
     w_filtro = ""
     If p_projeto > ""  Then 
        DB_GetSolicData RS, p_projeto, "PJGERAL"
        w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Projeto <td><font size=1>[<b><A class=""HL"" HREF=""Projeto.asp?par=Visual&O=L&w_chave=" & p_projeto & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Exibe as informações do projeto."">" & RS("titulo") & "</a></b>]"
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
     If p_proponente  > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Outra parte <td><font size=1>[<b>" & p_proponente & "</b>]"                     End If
     If p_objeto      > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Detalhamento <td><font size=1>[<b>" & p_objeto & "</b>]"                        End If
     If p_palavra     > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Código interno <td><font size=1>[<b>" & p_palavra & "</b>]"                     End If
     If p_ini_i       > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Início vigência <td><font size=1>[<b>" & p_ini_i & "-" & p_ini_f & "</b>]"      End If
     If p_fim_i       > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Término vigência <td><font size=1>[<b>" & p_fim_i & "-" & p_fim_f & "</b>]"     End If
     If p_atraso      = "S" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Código externo <td><font size=1>[<b>" & p_atraso & "</b>]"                      End If
     If w_filtro > "" Then w_filtro = "<table border=0><tr valign=""top""><td><font size=1><b>Filtro:</b><td nowrap><font size=1><ul>" & w_filtro & "</ul></tr></table>"                    End If
     Response.Write RS_Menu("sigla")
     Response.End()
     Select case p_agrega
        Case "GRDMPROJ"
           DB_GetSolicList RS1, P2, w_usuario, RS_Menu("sigla"), 4, _
                p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
                p_unidade, p_prioridade, p_ativo, p_proponente, _
                p_chave, p_objeto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
                p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, p_projeto, p_atividade, null, null
           w_TP = TP & " - Por projeto"
           RS1.Filter = "sq_solic_pai <> null"
           RS1.sort = "nm_projeto"
        Case "GRDMPROP"
           DB_GetSolicList RS1, P2, w_usuario, RS_Menu("sigla"), 4, _
                p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
                p_unidade, p_prioridade, p_ativo, p_proponente, _
                p_chave, p_objeto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
                p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, p_projeto, p_atividade, null, null
           w_TP = TP & " - Pela outra parte"
           RS1.Filter = "outra_parte <> null"
           RS1.sort = "nm_outra_parte_resumido"
        Case "GRDMRESP"
           DB_GetSolicList RS1, P2, w_usuario, RS_Menu("sigla"), 4, _
                p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
                p_unidade, p_prioridade, p_ativo, p_proponente, _
                p_chave, p_objeto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
                p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, p_projeto, p_atividade, null, null
           w_TP = TP & " - Por responsável"
           RS1.sort = "nm_solic"
        Case "GRDMRESPATU"
           DB_GetSolicList RS1, P2, w_usuario, RS_Menu("sigla"), 4, _
                p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
                p_unidade, p_prioridade, p_ativo, p_proponente, _
                p_chave, p_objeto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
                p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, p_projeto, p_atividade, null, null
           w_TP = TP & " - Por executor"
           RS1.Filter = "executor <> null"
           RS1.sort = "nm_exec"
        Case "GRDMCC"
           DB_GetSolicList RS1, P2, w_usuario, RS_Menu("sigla"), 4, _
                p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
                p_unidade, p_prioridade, p_ativo, p_proponente, _
                p_chave, p_objeto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
                p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, p_projeto, p_atividade, null, null
           w_TP = TP & " - Por classificação"
           RS1.Filter = "sq_cc <> null"
           RS1.sort = "sg_cc"
        Case "GRDMSETOR"
           DB_GetSolicList RS1, P2, w_usuario, RS_Menu("sigla"), 4, _
                p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
                p_unidade, p_prioridade, p_ativo, p_proponente, _
                p_chave, p_objeto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
                p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, p_projeto, p_atividade, null, null
           w_TP = TP & " - Por setor responsável"
           RS1.sort = "nm_unidade_resp"
        Case "GRDMPRIO" 
           w_TP = TP & " - Por prioridade"
           DB_GetSolicList RS1, P2, w_usuario, RS_Menu("sigla"), 4, _
                p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
                p_unidade, p_prioridade, p_ativo, p_proponente, _
                p_chave, p_objeto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
                p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, p_projeto, p_atividade, null, null
           RS1.sort = "nm_prioridade"
        Case "GRDMLOCAL" 
           w_TP = TP & " - Por UF"
           DB_GetSolicList RS1, P2, w_usuario, RS_Menu("sigla"), 4, _
                p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
                p_unidade, p_prioridade, p_ativo, p_proponente, _
                p_chave, p_objeto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
                p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, p_projeto, p_atividade, null, null
           RS1.sort = "co_uf"
        Case "GRDMAREA" 
           w_TP = TP & " - Por área envolvida"
           DB_GetSolicGRA RS1, P2, w_usuario, RS_Menu("sigla"), 4, _
                p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
                p_unidade, p_prioridade, p_ativo, p_proponente, _
                p_chave, p_objeto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
                p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, p_projeto, p_atividade
           RS1.sort = "nm_envolv"
        Case "GRDMINTER" 
           w_TP = TP & " - Por interessado"
           DB_GetSolicGRI RS1, P2, w_usuario, RS_Menu("sigla"), 4, _
                p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
                p_unidade, p_prioridade, p_ativo, p_proponente, _
                p_chave, p_objeto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
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
        Validate "p_chave", "Número do contrato", "", "", "1", "18", "", "0123456789"
        Validate "p_proponente", "Outra parte", "", "", "2", "90", "1", ""
        Validate "p_palavra", "Código interno", "", "", "3", "90", "1", "1"
        Validate "p_atraso", "Código externo", "", "", "1", "90", "1", "1"
        Validate "p_objeto", "Objeto", "", "", "2", "90", "1", "1"
        Validate "p_prazo", "Dias para a data limite", "", "", "1", "2", "", "0123456789"
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
        ValidateClose
        ScriptClose
     Else
        ShowHTML "<TITLE>" & w_TP & "</TITLE>"
     End If
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     ShowHTML "</HEAD>"
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
          ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a>"
        Else
          ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>F</u>iltrar (Inativo)</a>"
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
         DB_GetTramiteList RS2, P2, null, null
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
                    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & MontaOrdemEtapa(RS1("sq_projeto_etapa")) & " - " & RS1("nm_etapa")
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
              If w_nm_quebra <> RS1("nm_outra_parte_resumido") Then
                 If w_qt_quebra > 0 Then
                    ImprimeLinha t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_custo, t_acima, w_chave
                    w_linha = w_linha + 2
                 End If
                 If O <> "W" or (O = "W" and w_linha <= 25) Then
                    ' Se for geração de MS-Word, coloca a nova quebra somente se não estourou o limite
                    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("nm_outra_parte_resumido")
                 End If
                 w_nm_quebra       = RS1("nm_outra_parte_resumido")
                 w_chave           = RS1("nm_outra_parte_resumido")
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
              Case "GRDMPROP"    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("nm_outra_parte_resumido")
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
        If Nvl(RS1("conclusao"),"") = "" Then
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
           If cDbl(RS1("valor")) < cDbl(Nvl(RS1("valor_atual"),0)) Then
              t_acima    = t_acima + 1
              t_totacima = t_totacima + 1
           End If
        End If
        t_solic    = t_solic + 1
        t_valor    = t_valor + Nvl(cDbl(RS1("valor")),0)
        t_custo    = t_custo + cDbl(Nvl(RS1("valor_atual"),0))
        
        t_totvalor = t_totvalor + Nvl(cDbl(RS1("valor")),0)
        t_totcusto = t_totcusto + cDbl(Nvl(RS1("valor_atual"),0))
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
       ShowHTML "<tr><td align=""center""><IMG SRC=""" & conPHP4 & w_dir & "geragrafico.php?p_genero=M&p_objeto=" & RS_Menu("nome") & "&p_tipo="&SG&"&p_grafico=Barra&p_tot="&t_totsolic&"&p_cad="&t_totcad&"&p_tram="&t_tottram&"&p_conc="&t_totconc&"&p_atraso="&t_totatraso&"&p_aviso="&t_totaviso&"&p_acima="&t_totacima&""">"
       ShowHTML "<tr><td align=""center"" height=20>"
       If (t_totcad + t_tottram) > 0 Then
          ShowHTML "<tr><td align=""center""><IMG SRC=""" & conPHP4 & w_dir & "geragrafico.php?p_genero=M&p_objeto=" & RS_Menu("nome") & "&p_tipo="&SG&"&p_grafico=Pizza&p_tot="&t_totsolic&"&p_cad="&t_totcad&"&p_tram="&t_tottram&"&p_conc="&t_totconc&"&p_atraso="&t_totatraso&"&p_aviso="&t_totaviso&"&p_acima="&t_totacima&""">"
       End If
    End If
    
  ElseIf Instr("P",O) > 0 Then
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    AbreForm "Form", w_dir & w_Pagina & par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"

    ' Exibe parâmetros de apresentação
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td align=""center"" valign=""top""><table border=0 width=""90%"" cellspacing=0>"
    ShowHTML "         <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Parâmetros de Apresentação</td>"
    ShowHTML "         <tr valign=""top""><td colspan=2><table border=0 width=""100%"" cellpadding=0 cellspacing=0><tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b><U>A</U>gregar por:<br><SELECT ACCESSKEY=""O"" " & w_Disabled & " class=""STS"" name=""p_agrega"" size=""1"">"
    If p_agrega = "GRDMAREA"    Then ShowHTML "          <option value=""GRDMAREA"" selected>Área envolvida"        Else ShowHTML "          <option value=""GRDMAREA"">Área envolvida"     End If
    If RS_menu("solicita_cc") = "S" Then
       If p_agrega = "GRDMCC"   Then ShowHTML "          <option value=""GRDMCC"" selected>Classificação"           Else ShowHTML "          <option value=""GRDMCC"">Classificação"        End If
    End If
    If p_agrega = "GRDMINTER"   Then ShowHTML "          <option value=""GRDMINTER"" selected>Interessado"          Else ShowHTML "          <option value=""GRDMINTER"">Interessado"       End If
    If p_agrega = "GRDMPRIO"    Then ShowHTML "          <option value=""GRDMPRIO"" selected>Prioridade"            Else ShowHTML "          <option value=""GRDMPRIO"">Prioridade"         End If
    If p_agrega = "GRDMRESPATU" Then ShowHTML "          <option value=""GRDMRESPATU"" selected>Executor"           Else ShowHTML "          <option value=""GRDMRESPATU"">Executor"        End If
    If p_agrega = "GRDMPROP"    Then ShowHTML "          <option value=""GRDMPROP"" selected>Outra parte"           Else ShowHTML "          <option value=""GRDMPROP"">Outra parte"        End If
    If p_agrega = "GRDMPROJ"    Then ShowHTML "          <option value=""GRDMPROJ"" selected>Projeto"               Else ShowHTML "          <option value=""GRDMPROJ"">Projeto"            End If
    If Nvl(p_agrega,"GRDMRESP") = "GRDMRESP" Then ShowHTML "          <option value=""GRDMRESP"" selected>Responsável" Else ShowHTML "          <option value=""GRDMRESP"">Responsável"     End If
    If p_agrega = "GRDMSETOR"   Then ShowHTML "          <option value=""GRDMSETOR"" selected>Setor responsável"    Else ShowHTML "          <option value=""GRDMSETOR"">Setor responsável" End If
    If p_agrega = "GRDMLOCAL"   Then ShowHTML "          <option value=""GRDMLOCAL"" selected>UF"                   Else ShowHTML "          <option value=""GRDMLOCAL"">UF"                End If
    ShowHTML "          </select></td>"
    MontaRadioNS "<b>Inibe exibição do gráfico?</b>", p_tipo, "p_tipo"
    MontaRadioSN "<b>Limita tamanho do detalhamento?</b>", p_tamanho, "p_tamanho"
    ShowHTML "           </table>"
    ShowHTML "         </tr>"
    ShowHTML "         <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Critérios de Busca</td>"

    ' Se a opção for ligada ao módulo de projetos, permite a seleção do projeto  e da etapa
    ShowHTML "      <tr><td colspan=2><table border=0 width=""90%"" cellspacing=0><tr valign=""top"">"
    DB_GetLinkData RS, w_cliente, "PJCAD"
    DB_GetLinkData RS, w_cliente, "PJCAD"
    SelecaoProjeto "Pro<u>j</u>eto:", "J", "Selecione o projeto do contrato na relação.", p_projeto, w_usuario, RS("sq_menu"), "p_projeto", "PJLIST", null
    DesconectaBD
    ShowHTML "      </tr>"
    ShowHTML "          </table>"

    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    If RS_menu("solicita_cc") = "S" Then
       ShowHTML "      <tr><td colspan=2><table border=0 width=""90%"" cellspacing=0><tr valign=""top"">"
       SelecaoCC "C<u>l</u>assificação:", "C", "Selecione um dos itens relacionados.", p_sqcc, null, "p_sqcc", "SIWSOLIC"
       ShowHTML "          </table>"
    End If
    ShowHTML "      <tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Número do c<U>o</U>ntrato:<br><INPUT ACCESSKEY=""O"" " & w_Disabled & " class=""sti"" type=""text"" name=""p_chave"" size=""18"" maxlength=""18"" value=""" & p_chave & """></td>"
    ShowHTML "          <td><font size=""1""><b>O<U>u</U>tra parte:<br><INPUT ACCESSKEY=""U"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_proponente"" size=""25"" maxlength=""90"" value=""" & p_proponente & """></td>"
    ShowHTML "      <tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Có<U>d</U>igo interno:<br><INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""sti"" type=""text"" name=""p_palavra"" size=""18"" maxlength=""18"" value=""" & p_palavra & """></td>"
    ShowHTML "          <td><font size=""1""><b>Có<U>d</U>igo externo:<br><INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""sti"" type=""text"" name=""p_atraso"" size=""18"" maxlength=""18"" value=""" & p_atraso & """></td>"
    ShowHTML "      <tr valign=""top"">"
    SelecaoPessoa "Respo<u>n</u>sável:", "N", "Selecione o responsável na relação.", p_solicitante, null, "p_solicitante", "USUARIOS"
    SelecaoUnidade "<U>S</U>etor responsável:", "S", null, p_unidade, null, "p_unidade", null, null
    ShowHTML "      <tr valign=""top"">"
    SelecaoPessoa "E<u>x</u>ecutor:", "X", "Selecione o executor da demanda na relação.", p_usu_resp, null, "p_usu_resp", "USUARIOS"
    SelecaoUnidade "<U>S</U>etor atual:", "S", "Selecione a unidade onde a demanda se encontra na relação.", p_uorg_resp, null, "p_uorg_resp", null, null
    ShowHTML "      <tr>"
    SelecaoPais "<u>P</u>aís:", "P", null, p_pais, null, "p_pais", null, "onChange=""document.Form.action='" & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.target=''; document.Form.w_troca.value='p_regiao'; document.Form.submit();"""
    SelecaoRegiao "<u>R</u>egião:", "R", null, p_regiao, p_pais, "p_regiao", null, "onChange=""document.Form.action='" & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.target=''; document.Form.w_troca.value='p_uf'; document.Form.submit();"""
    ShowHTML "      <tr>"
    SelecaoEstado "E<u>s</u>tado:", "S", null, p_uf, p_pais, "N", "p_uf", null, "onChange=""document.Form.action='" & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.target=''; document.Form.w_troca.value='p_cidade'; document.Form.submit();"""
    SelecaoCidade "<u>C</u>idade:", "C", null, p_cidade, p_pais, p_uf, "p_cidade", null, null
    ShowHTML "      <tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>O<U>b</U>jeto:<br><INPUT ACCESSKEY=""B"" " & w_Disabled & " class=""sti"" type=""text"" name=""p_objeto"" size=""25"" maxlength=""90"" value=""" & p_objeto & """></td>"
    ShowHTML "          <td><font size=""1""><b>Dias para <U>t</U>érmino da vigência:<br><INPUT ACCESSKEY=""T"" " & w_Disabled & " class=""sti"" type=""text"" name=""p_prazo"" size=""2"" maxlength=""2"" value=""" & p_prazo & """></td>"
    ShowHTML "      <tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Iní<u>c</u>io vigência entre:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""p_ini_i"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_ini_i & """ onKeyDown=""FormataData(this,event);""> e <input " & w_Disabled & " accesskey=""C"" type=""text"" name=""p_ini_f"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_ini_f & """ onKeyDown=""FormataData(this,event);""></td>"
    ShowHTML "          <td><font size=""1""><b>Fi<u>m</u> vigência entre:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""p_fim_i"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_fim_i & """ onKeyDown=""FormataData(this,event);""> e <input " & w_Disabled & " accesskey=""T"" type=""text"" name=""p_fim_f"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_fim_f & """ onKeyDown=""FormataData(this,event);""></td>"
    ShowHTML "      <tr valign=""top"">"
    SelecaoFaseCheck "Recuperar fases:", "S", null, p_fase, P2, "p_fase", null, null
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
       Case "GRDMCC"      ShowHTML "          <td><font size=""1""><b>Classificação</font></td>"
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
    If Session("interno") = "S" Then
       ShowHTML "          <td><font size=""1""><b>$ Prev.</font></td>"
       ShowHTML "          <td><font size=""1""><b>$ Real</font></td>"
       ShowHTML "          <td><font size=""1""><b>Real > Previsto</font></td>"
    End If
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
    If Session("interno") = "S" Then
       ShowHTML "          <td align=""right""><font size=""1"">" & FormatNumber(p_valor,2) & "&nbsp;</font></td>"
       ShowHTML "          <td align=""right""><font size=""1"">" & FormatNumber(p_custo,2) & "&nbsp;</font></td>"
       If p_acima > 0  Then ShowHTML "          <td align=""right""><font size=""1"" color=""red""><b>" & FormatNumber(p_acima,0) & "&nbsp;</font></td>"  Else ShowHTML "          <td align=""right""><font size=""1""><b>" & p_acima & "&nbsp;</font></td>"  End If
    End If
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
  Select Case Par
    Case "GERENCIAL"
       Gerencial
    Case Else
       Cabecalho
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

