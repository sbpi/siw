<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Geral.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Cliente.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Seguranca.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Link.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_EO.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DML_Solic.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DML_Projeto.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/cp_upload/_upload.asp" -->
<!-- #INCLUDE FILE="DB_Geral.asp" -->
<!-- #INCLUDE FILE="DML_Acao.asp" -->
<!-- #INCLUDE FILE="VisualAcao.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Tabelas.asp" -->
<!-- #INCLUDE FILE="DML_Tabelas.asp" -->
<!-- #INCLUDE FILE="DB_SIAFI.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /Acao.asp
REM ------------------------------------------------------------------------
REM Nome     : Celso Miguel Lago Filho 
REM Descricao: Gerencia o m�dulo de a��es
REM Mail     : celso@sbpi.com.br
REM Criacao  : 29/12/2004 16:30
REM Versao   : 1.0.0.0
REM Local    : Bras�lia - DF
REM -------------------------------------------------------------------------
REM
REM Par�metros recebidos:
REM    R (refer�ncia) = usado na rotina de grava��o, com conte�do igual ao par�metro T
REM    O (opera��o)   = I   : Inclus�o
REM                   = A   : Altera��o
REM                   = C   : Cancelamento
REM                   = E   : Exclus�oSUB 
REM                   = L   : Listagem
REM                   = P   : Pesquisa
REM                   = D   : Detalhes
REM                   = N   : Nova solicita��o de envio

' Verifica se o usu�rio est� autenticado
If Session("LogOn") <> "Sim" Then
   EncerraSessao
End If

' Declara��o de vari�veis
Dim dbms, sp, RS, RS1, RS2, RS3, RS4, RS_menu
Dim P1, P2, P3, P4, TP, SG
Dim R, O, w_Cont, w_Reg, w_pagina, w_Disabled, w_TP, w_classe, w_submenu, w_filtro, w_copia
Dim w_Assinatura, w_SG
Dim p_ativo, p_solicitante, p_prioridade, p_unidade, p_proponente, p_ordena
Dim p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_acao, p_atividade
Dim p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc
Dim p_sq_acao_ppa, p_sq_isprojeto, p_qtd_restricao
Dim w_troca,w_cor, w_filter, w_cliente, w_usuario, w_menu, w_dir, w_dir_volta, UploadID
Dim w_sq_pessoa, w_ano
Dim ul,File
Set RS  = Server.CreateObject("ADODB.RecordSet")
Set RS1 = Server.CreateObject("ADODB.RecordSet")
Set RS2 = Server.CreateObject("ADODB.RecordSet")
Set RS3 = Server.CreateObject("ADODB.RecordSet")
Set RS4 = Server.CreateObject("ADODB.RecordSet")
Set RS_Menu = Server.CreateObject("ADODB.RecordSet")

Private Par

AbreSessao

' Carrega vari�veis locais com os dados dos par�metros recebidos
Par          = ucase(Request("Par"))
w_pagina     = "Acao.asp?par="
w_Dir        = "mod_is/"  
w_dir_volta  = "../"  
w_Disabled   = "ENABLED"

SG           = ucase(Request("SG"))
O            = uCase(Request("O"))
w_cliente    = RetornaCliente()
w_usuario    = RetornaUsuario()
w_menu       = RetornaMenu(w_cliente, SG)
w_ano        = RetornaAno()

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
   w_troca            = ul.Texts.Item("w_troca")
   w_copia            = ul.Texts.Item("w_copia")
   p_acao             = uCase(ul.Texts.Item("p_acao"))
   p_atividade        = uCase(ul.Texts.Item("p_atividade"))
   p_ativo            = uCase(ul.Texts.Item("p_ativo"))
   p_solicitante      = uCase(ul.Texts.Item("p_solicitante"))
   p_prioridade       = uCase(ul.Texts.Item("p_prioridade"))
   p_unidade          = uCase(ul.Texts.Item("p_unidade"))
   p_proponente       = uCase(ul.Texts.Item("p_proponente"))
   p_ordena           = uCase(ul.Texts.Item("p_ordena"))
   p_ini_i            = uCase(ul.Texts.Item("p_ini_i"))
   p_ini_f            = uCase(ul.Texts.Item("p_ini_f"))
   p_fim_i            = uCase(ul.Texts.Item("p_fim_i"))
   p_fim_f            = uCase(ul.Texts.Item("p_fim_f"))
   p_atraso           = uCase(ul.Texts.Item("p_atraso"))
   p_chave            = uCase(ul.Texts.Item("p_chave"))
   p_assunto          = uCase(ul.Texts.Item("p_assunto"))
   p_pais             = uCase(ul.Texts.Item("p_pais"))
   p_regiao           = uCase(ul.Texts.Item("p_regiao"))
   p_uf               = uCase(ul.Texts.Item("p_uf"))
   p_cidade           = uCase(ul.Texts.Item("p_cidade"))
   p_usu_resp         = uCase(ul.Texts.Item("p_usu_resp"))
   p_uorg_resp        = uCase(ul.Texts.Item("p_uorg_resp"))
   p_palavra          = uCase(ul.Texts.Item("p_palavra"))
   p_prazo            = uCase(ul.Texts.Item("p_prazo"))
   p_fase             = uCase(ul.Texts.Item("p_fase"))
   p_sqcc             = uCase(ul.Texts.Item("p_sqcc"))
   p_sq_acao_ppa      = uCase(ul.Texts.Item("p_sq_acao_ppa"))
   p_sq_isprojeto     = uCase(ul.Texts.Item("p_sq_isprojeto"))
   p_qtd_restricao    = uCase(ul.Texts.Item("p_qtd_restricao"))
   
   P1                 = Nvl(ul.Texts.Item("P1"),0)
   P2                 = Nvl(ul.Texts.Item("P2"),0)
   P3                 = cDbl(Nvl(ul.Texts.Item("P3"),1))
   P4                 = cDbl(Nvl(ul.Texts.Item("P4"),conPagesize))
   TP                 = ul.Texts.Item("TP")
   R                  = uCase(ul.Texts.Item("R"))
   w_Assinatura       = uCase(ul.Texts.Item("w_Assinatura"))
   w_SG               = uCase(ul.Texts.Item("w_SG"))
   
Else

   w_troca            = Request("w_troca")
   w_copia            = Request("w_copia")
   p_acao             = uCase(Request("p_acao"))
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
   p_sq_isprojeto     = uCase(Request("p_sq_isprojeto"))
   p_qtd_restricao    = uCase(Request("p_qtd_restricao"))
   
   P1           = Nvl(Request("P1"),0)
   P2           = Nvl(Request("P2"),0)
   P3           = cDbl(Nvl(Request("P3"),1))
   P4           = cDbl(Nvl(Request("P4"),conPagesize))
   TP           = Request("TP")
   R            = uCase(Request("R"))
   w_Assinatura = uCase(Request("w_Assinatura"))
   w_SG         = uCase(Request("w_SG"))
  

   If SG="ISMETA" or SG = "ISACINTERE" or SG = "ISACRESP" or SG = "ISACANEXO" or _
      SG = "ISACPROFIN" or  SG = "ISACRESTR" Then
      If O <> "I" and O <> "E" and Request("w_chave_aux") = "" Then O = "L" End If
   ElseIf SG = "ISACENVIO" Then 
      O = "V"
   ElseIf O = "" Then 
      ' Se for acompanhamento, entra na filtragem
      If P1 = 3 Then O = "P" Else O = "L" End If
   End If

End If

Select Case O
  Case "I" 
     w_TP = TP & " - Inclus�o"
  Case "A" 
     w_TP = TP & " - Altera��o"
  Case "E" 
     w_TP = TP & " - Exclus�o"
  Case "P" 
     w_TP = TP & " - Filtragem"
  Case "C"
     w_TP = TP & " - C�pia"
  Case "V" 
     w_TP = TP & " - Envio"
  Case "H" 
     w_TP = TP & " - Heran�a"
  Case Else
     If par="BUSCAACAO" Then
        w_TP = TP & " - Busca a��o"
     Else
        w_TP = TP & " - Listagem"
     End If
End Select

DB_GetLinkSubMenu RS, Session("p_cliente"), SG
If RS.RecordCount > 0 Then
   w_submenu = "Existe"
Else
   w_submenu = ""
End If
DesconectaBD

' Recupera a configura��o do servi�o
If P2 > 0 Then DB_GetMenuData RS_menu, P2 Else DB_GetMenuData RS_menu, w_menu End If
If RS_menu("ultimo_nivel") = "S" Then
   ' Se for sub-menu, pega a configura��o do pai
   DB_GetMenuData RS_menu, RS_menu("sq_menu_pai")
End If

Main

FechaSessao

Set UploadID      = Nothing
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
Set p_acao        = Nothing
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
Set p_sq_isprojeto = Nothing
Set p_qtd_restricao = Nothing

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
Set w_ano         = Nothing

REM =========================================================================
REM Rotina de visualiza��o resumida dos registros
REM -------------------------------------------------------------------------
Sub Inicial

  Dim w_titulo, w_total, w_parcial
  If O = "L" Then
     If Instr(uCase(R),"GR_") > 0 Then
        w_filtro = ""
        If p_acao > "" and  p_sq_acao_ppa = "" Then 
           DB_GetSolicData_IS RS, p_acao, "ISACGERAL"
           w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>A��o <td><font size=1>[<b>" & RS("titulo") & "</b>]"
        End If
        'If p_atividade > ""  Then 
        '   DB_GetSolicMeta_IS RS, p_projeto, p_atividade, "REGISTRO", null, null, null, null, null, null, null, null, null
        '   w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Meta <td><font size=1>[<b>" & RS("titulo") & "</b>]"
        'End If
        If p_sq_acao_ppa > ""  Then 
           DB_GetAcaoPPA_IS RS, w_cliente, w_ano, Mid(p_sq_acao_ppa,1,4), Mid(p_sq_acao_ppa,5,4), null, Mid(p_sq_acao_ppa,13,17), null, null, null
           w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>A��o <td><font size=1>[<b>" & RS("cd_unidade") & "." & RS("cd_programa") & "." & RS("cd_acao") & " - " & RS("descricao_acao") & " (" & RS("ds_unidade") & ")</b>]"
        End If
        If p_sq_isprojeto > ""  Then 
           DB_GetProjeto_IS RS, p_sq_isprojeto, w_cliente, null, null, null, null, null, null, null, null, null, null, null, null
           w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Programa interno<td><font size=1>[<b>" & RS("nome") & "</b>]"
        End If
        If p_chave       > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Demanda n� <td><font size=1>[<b>" & p_chave & "</b>]" End If
        If p_prazo       > ""  Then w_filtro = w_filtro & " <tr valign=""top""><td align=""right""><font size=1>Prazo para conclus�o at�<td><font size=1>[<b>" & FormatDateTime(DateAdd("d",p_prazo,Date()),1) & "</b>]" End If
        If p_solicitante > ""  Then
           DB_GetPersonData RS, w_cliente, p_solicitante, null, null
           w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Respons�vel <td><font size=1>[<b>" & RS("nome_resumido") & "</b>]"
        End If
        If p_unidade     > ""  Then 
           DB_GetUorgData RS, p_unidade
           w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>�rea planejamento <td><font size=1>[<b>" & RS("nome") & "</b>]"
        End If
        If p_usu_resp > ""  Then
           DB_GetPersonData RS, w_cliente, p_usu_resp, null, null
           w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Executor <td><font size=1>[<b>" & RS("nome_resumido") & "</b>]"
        End If
        If p_uorg_resp > ""  Then 
           DB_GetUorgData RS, p_uorg_resp
           w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Unidade atual <td><font size=1>[<b>" & RS("nome") & "</b>]"
        End If
        If p_proponente  > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Parcerias externas<td><font size=1>[<b>" & p_proponente & "</b>]"                      End If
        If p_assunto     > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Assunto <td><font size=1>[<b>" & p_assunto & "</b>]"                            End If
        If p_palavra     > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Parcerias internas <td><font size=1>[<b>" & p_palavra & "</b>]"                     End If
        If p_ini_i       > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Data recebimento <td><font size=1>[<b>" & p_ini_i & "-" & p_ini_f & "</b>]"     End If
        If p_fim_i       > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Limite conclus�o <td><font size=1>[<b>" & p_fim_i & "-" & p_fim_f & "</b>]"     End If
        If p_atraso      = "S" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Situa��o <td><font size=1>[<b>Apenas atrasadas</b>]"                            End If
        If p_qtd_restricao = "S" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Situa��o <td><font size=1>[<b>Apenas a��es com restri��o</b>]"                            End If
        If w_filtro > "" Then w_filtro = "<table border=0><tr valign=""top""><td><font size=1><b>Filtro:</b><td nowrap><font size=1><ul>" & w_filtro & "</ul></tr></table>"                    End If
     End If
     
     DB_GetLinkData RS, w_cliente, "ISACAD"
     
     If w_copia > "" Then ' Se for c�pia, aplica o filtro sobre todas as demandas vis�veis pelo usu�rio
        DB_GetSolicList_IS RS, RS("sq_menu"), w_usuario, Nvl(Request("p_agrega"),SG), 3, _
           p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
           p_unidade, p_prioridade, p_qtd_restricao, p_proponente, _
           p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
           p_uorg_resp, p_palavra, p_prazo, p_fase, p_projeto, p_atividade, null, p_sq_acao_ppa, p_sq_isprojeto, null, w_ano
     Else
        DB_GetSolicList_IS RS, RS("sq_menu"), w_usuario, Nvl(Request("p_agrega"),SG), P1, _
           p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
           p_unidade, p_prioridade, p_qtd_restricao, p_proponente, _
           p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
           p_uorg_resp, p_palavra, p_prazo, p_fase, p_acao, p_atividade, null, Mid(p_sq_acao_ppa,5,4), p_sq_isprojeto, Mid(p_sq_acao_ppa,9,4), w_ano
     End If
     If p_ordena > "" Then RS.sort = p_ordena Else RS.sort = "fim, prioridade" End If
  End If

  Cabecalho
  ShowHTML "<HEAD>"
  If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=../" & MontaURL("MESA") & """>" End If
  ShowHTML "<TITLE>" & conSgSistema & " - Listagem de a��es</TITLE>"
  ScriptOpen "Javascript"
  CheckBranco
  FormataData
  ValidateOpen "Validacao"
  If Instr("CP",O) > 0 Then
     If P1 <> 1 or O = "C" Then ' Se n�o for cadastramento ou se for c�pia
        'Validate "p_chave", "N�mero da a��o", "", "", "1", "18", "", "0123456789"
        Validate "p_prazo", "Dias para a data limite", "", "", "1", "2", "", "0123456789"
        Validate "p_proponente", "Proponente externo", "", "", "2", "90", "1", ""
        'Validate "p_assunto", "Assunto", "", "", "2", "90", "1", "1"
        Validate "p_palavra", "Palavras-chave", "", "", "2", "90", "1", "1"
        Validate "p_ini_i", "Recebimento inicial", "DATA", "", "10", "10", "", "0123456789/"
        Validate "p_ini_f", "Recebimento final", "DATA", "", "10", "10", "", "0123456789/"
        ShowHTML "  if ((theForm.p_ini_i.value != '' && theForm.p_ini_f.value == '') || (theForm.p_ini_i.value == '' && theForm.p_ini_f.value != '')) {"
        ShowHTML "     alert ('Informe ambas as datas de recebimento ou nenhuma delas!');"
        ShowHTML "     theForm.p_ini_i.focus();"
        ShowHTML "     return false;"
        ShowHTML "  }"
        CompData "p_ini_i", "Recebimento inicial", "<=", "p_ini_f", "Recebimento final"
        Validate "p_fim_i", "Conclus�o inicial", "DATA", "", "10", "10", "", "0123456789/"
        Validate "p_fim_f", "Conclus�o final", "DATA", "", "10", "10", "", "0123456789/"
        ShowHTML "  if ((theForm.p_fim_i.value != '' && theForm.p_fim_f.value == '') || (theForm.p_fim_i.value == '' && theForm.p_fim_f.value != '')) {"
        ShowHTML "     alert ('Informe ambas as datas de conclus�o ou nenhuma delas!');"
        ShowHTML "     theForm.p_fim_i.focus();"
        ShowHTML "     return false;"
        ShowHTML "  }"
        CompData "p_fim_i", "Conclus�o inicial", "<=", "p_fim_f", "Conclus�o final"
     End If
     Validate "P4", "Linhas por p�gina", "1", "1", "1", "4", "", "0123456789"
  End If
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_Troca > "" Then ' Se for recarga da p�gina
     BodyOpen "onLoad='document.Form." & w_Troca & ".focus();'"
  ElseIf O = "I" Then
     BodyOpen "onLoad='document.Form.w_smtp_server.focus();'"
  ElseIf O = "A" Then
     BodyOpen "onLoad='document.Form.w_nome.focus();'"
  ElseIf O = "E" Then
     BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
  ElseIf InStr("CP",O) > 0 Then
     If P1 <> 1 or O = "C" Then ' Se for cadastramento
        BodyOpen "onLoad='document.Form.p_sq_acao_ppa.focus()';"
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
    If P1 = 1 and w_copia = "" Then ' Se for cadastramento e n�o for resultado de busca para c�pia
       If w_submenu > "" Then
          DB_GetLinkSubMenu RS1, w_cliente, Request("SG")
          ShowHTML "<tr><td><font size=""1"">"
          ShowHTML "    <a accesskey=""I"" class=""SS"" href=""" & w_dir & w_pagina & "Geral&R=" & w_pagina & par & "&O=I&SG=" & RS1("sigla") & "&w_menu=" & w_menu & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;"
          'ShowHTML "    <a accesskey=""C"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=C&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>C</u>opiar</a>"
       Else
          ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;"
       End If
    End If
    If Instr(uCase(R),"GR_") = 0 Then
       If w_copia > "" Then ' Se for c�pia
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
    ShowHTML "          <td rowspan=2><font size=""1""><b>" & LinkOrdena("A��o","cd_acao_completa") & "</font></td>"
    ShowHTML "          <td rowspan=2><font size=""1""><b>" & LinkOrdena("Respons�vel","nm_solic") & "</font></td>"
    If P1 <> 2 Then ShowHTML "          <td rowspan=2><font size=""1""><b>" & LinkOrdena("Usu�rio atual", "nm_exec") & "</font></td>" End If
    If P1 = 1 or P1 = 2 Then ' Se for cadastramento ou mesa de trabalho
       ShowHTML "          <td rowspan=2><font size=""1""><b>" & LinkOrdena("T�tulo","titulo") & "</font></td>"
       ShowHTML "          <td colspan=2><font size=""1""><b>Execu��o</font></td>"
    Else
       'ShowHTML "          <td rowspan=2><font size=""1""><b>" & LinkOrdena("Parcerias","proponente") & "</font></td>"
       ShowHTML "          <td rowspan=2><font size=""1""><b>" & LinkOrdena("T�tulo","titulo") & "</font></td>"
       ShowHTML "          <td colspan=2><font size=""1""><b>Execu��o</font></td>"
       ShowHTML "          <td rowspan=2><font size=""1""><b>" & LinkOrdena("Valor","valor") & "</font></td>"
       ShowHTML "          <td rowspan=2><font size=""1""><b>" & LinkOrdena("Fase atual","nm_tramite") & "</font></td>"
    End If
    ShowHTML "          <td rowspan=2><font size=""1""><b>Opera��es</font></td>"
    ShowHTML "        </tr>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("De","inicio") & "</font></td>"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("At�","fim") & "</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=10 align=""center""><font size=""2""><b>N�o foram encontrados registros.</b></td></tr>"
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
        If Not RS.EOF Then
           If RS("cd_acao") > "" Then
              ShowHTML "        <A class=""HL"" HREF=""" & w_dir & w_pagina & "Visual&R=" & w_pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=2&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exibe as informa��es deste registro."">" & RS("cd_acao_completa") & "</a>"
           Else
              ShowHTML "        <A class=""HL"" HREF=""" & w_dir & w_pagina & "Visual&R=" & w_pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=2&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exibe as informa��es deste registro."">" & RS("sq_siw_solicitacao") & "</a>"
           End If
        Else
           ShowHTML "        <A class=""HL"" HREF=""" & w_dir & w_pagina & "Visual&R=" & w_pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=2&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exibe as informa��es deste registro."">" & RS("cd_acao_completa") & "</a>"
        End If
        ShowHTML "        <td><font size=""1"">" & ExibePessoa("../", w_cliente, RS("solicitante"), TP, RS("nm_solic")) & "</A></td>"
        If P1 <> 2 Then ' Se for mesa de trabalho, n�o exibe o executor, pois j� � o usu�rio logado
           If Nvl(RS("nm_exec"),"---") > "---" Then
              ShowHTML "        <td><font size=""1"">" & ExibePessoa("../", w_cliente, RS("executor"), TP, RS("nm_exec")) & "</td>"
           Else
              ShowHTML "        <td><font size=""1"">---</td>"
           End If
        End If
        If P1 <> 1 and P1 <> 2 Then ' Se n�o for cadastramento nem mesa de trabalho
           'ShowHTML "        <td><font size=""1"">" & Nvl(RS("proponente"),"---") & "</td>"
        End If
        ' Verifica se foi enviado o par�metro p_tamanho = N. Se chegou, o assunto deve ser exibido sem corte.
        ' Este par�metro � enviado pela tela de filtragem das p�ginas gerenciais
        If Request("p_tamanho") = "N" Then
           ShowHTML "        <td><font size=""1"">" & Nvl(RS("titulo"),"-") & "</td>"
        Else
           If Len(Nvl(RS("titulo"),"-")) > 50 Then w_titulo = Mid(Nvl(RS("titulo"),"-"),1,50) & "..." Else w_titulo = Nvl(RS("titulo"),"-") End If
           If RS("sg_tramite") = "CA" Then
              ShowHTML "        <td title=""" & replace(replace(replace(RS("titulo"), "'", "\'"), """", "\'"),VbCrLf,"\n") & """><font size=""1""><strike>" & w_titulo & "</strike></td>"
           Else
              ShowHTML "        <td title=""" & replace(replace(replace(RS("titulo"), "'", "\'"), """", "\'"),VbCrLf,"\n") & """><font size=""1"">" & w_titulo & "</td>"
           End IF
        End If
        ShowHTML "        <td align=""center""><font size=""1"">&nbsp;" & FormataDataEdicao(RS("inicio")) & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">&nbsp;" & FormataDataEdicao(RS("fim")) & "</td>"
        If P1 <> 1 and P1 <> 2 Then ' Se n�o for cadastramento nem mesa de trabalho
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
        If P1 <> 3 and P1 <> 5 Then ' Se n�o for acompanhamento
           If w_copia > "" Then ' Se for listagem para c�pia
              DB_GetLinkSubMenu RS1, w_cliente, Request("SG")
              'ShowHTML "          <a accesskey=""I"" class=""HL"" href=""" & w_dir & w_pagina & "Geral&R=" & w_pagina & par & "&O=I&SG=" & RS1("sigla") & "&w_menu=" & w_menu & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&w_copia=" & RS("sq_siw_solicitacao") & MontaFiltro("GET") & """>Copiar</a>&nbsp;"
           ElseIf P1 = 1 Then ' Se for cadastramento
              If w_submenu > "" Then
                 ShowHTML "          <A class=""HL"" HREF=""Menu.asp?par=ExibeDocs&O=A&w_chave=" & RS("sq_siw_solicitacao") & "&R=" & w_Pagina & par & "&SG=" & SG & "&TP=" & TP & "&w_documento=Nr. " & RS("sq_siw_solicitacao") & MontaFiltro("GET") & """ title=""Altera as informa��es cadastrais da a��o"" TARGET=""menu"">Alterar</a>&nbsp;"
              Else
                 ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & RS("sq_siw_solicitacao") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Altera as informa��es cadastrais da a��o"">Alterar</A>&nbsp"
              End If
              ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "Excluir&R=" & w_pagina & par & "&O=E&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exclus�o da a��o."">Excluir</A>&nbsp"
              ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "Envio&R=" & w_pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Encaminhamento da a��o."">Enviar</A>&nbsp"
           ElseIf P1 = 2 Then ' Se for execu��o
              If cDbl(w_usuario) = cDbl(Nvl(RS("executor"),0)) Then
                 If cDbl(Nvl(RS("solicitante"),0)) = cDbl(w_usuario) or _
                    cDbl(Nvl(RS("titular"),0))     = cDbl(w_usuario) or _
                    cDbl(Nvl(RS("substituto"),0))  = cDbl(w_usuario) or _
                    cDbl(Nvl(RS("tit_exec"),0))    = cDbl(w_usuario) or _
                    cDbl(Nvl(RS("executor"),0))    = cDbl(w_usuario) or _
                    cDbl(Nvl(RS("subst_exec"),0))  = cDbl(w_usuario) _
                 Then
                    ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "AtualizaMeta&R=" & w_pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Atualiza as metas f�sicas da a��o."" target=""Metas"">Metas</A>&nbsp"
                    ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "Restricao&R=" & w_pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & "ISACRESTR" & MontaFiltro("GET") & """ title=""Atualiza as restricoes da a��o."" target=""Restricoes"">Rest</A>&nbsp"
                 End If
                 ' Coloca as opera��es dependendo do tr�mite
                 If RS("sg_tramite") = "EA" Then
                    ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "Anotacao&R=" & w_pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Registra anota��es para a a��o, sem envi�-la."">Anotar</A>&nbsp"
                    ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "Envio&R=" & w_pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Envia a a��o para outro respons�vel."">Enviar</A>&nbsp"
                 ElseIf RS("sg_tramite") = "EE" Then
                    ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "Anotacao&R=" & w_pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Registra anota��es para a a��o, sem envi�-la."">Anotar</A>&nbsp"
                    ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "Envio&R=" & w_pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Envia a a��o para outro respons�vel."">Enviar</A>&nbsp"
                    ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "Concluir&R=" & w_pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Conclui a execu��o da a��o."">Concluir</A>&nbsp"
                 End If
              Else
                 ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "AtualizaMeta&R=" & w_pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Atualiza as metas f�sicas da a��o."" target=""Metas"">Metas</A>&nbsp"
                 ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "Restricao&R=" & w_pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & "ISACRESTR" & MontaFiltro("GET") & """ title=""Atualiza as restricoes da a��o."" target=""Restricoes"">Rest</A>&nbsp"
                 ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "Envio&R=" & w_pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Envia a a��o para outro respons�vel."">Enviar</A>&nbsp"
              End If
           End If
        Else
           ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "AtualizaMeta&R=" & w_pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Atualiza as metas f�sicas da a��o."" target=""Metas"">Metas</A>&nbsp"
           ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "Restricao&R=" & w_pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & "ISACRESTR" & MontaFiltro("GET") & """ title=""Atualiza as restricoes da a��o."" target=""Restricoes"">Rest</A>&nbsp"
           If cDbl(Nvl(RS("solicitante"),0)) = cDbl(w_usuario) or _
              cDbl(Nvl(RS("titular"),0))     = cDbl(w_usuario) or _
              cDbl(Nvl(RS("substituto"),0))  = cDbl(w_usuario) or _
              cDbl(Nvl(RS("resp_etapa"),0))  > cDbl(0)         or _
              cDbl(Nvl(RS("tit_exec"),0))    = cDbl(w_usuario) or _
              cDbl(Nvl(RS("subst_exec"),0))  = cDbl(w_usuario) _
           Then
              ' Se o usu�rio for respons�vel por uma a��o, titular/substituto do setor respons�vel 
              ' ou titular/substituto da unidade executora,
              ' pode enviar.
              If cDbl(Nvl(RS("solicitante"),0)) = cDbl(w_usuario) or _
                 cDbl(Nvl(RS("titular"),0))     = cDbl(w_usuario) or _
                 cDbl(Nvl(RS("substituto"),0))  = cDbl(w_usuario) or _
                 cDbl(Nvl(RS("tit_exec"),0))    = cDbl(w_usuario) or _
                 cDbl(Nvl(RS("subst_exec"),0))  = cDbl(w_usuario) _
              Then
                 ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "envio&R=" & w_pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Envia a a��o para outro respons�vel."">Enviar</A>&nbsp"
              End If
           End If
        End If
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend

      If P1 <> 1 and P1 <> 2 Then ' Se n�o for cadastramento nem mesa de trabalho
         ' Coloca o valor parcial apenas se a listagem ocupar mais de uma p�gina
         If RS.PageCount > 1 Then
            ShowHTML "        <tr bgcolor=""" & conTrBgColor & """>"
            ShowHTML "          <td colspan=6 align=""right""><font size=""1""><b>Total desta p�gina&nbsp;</font></td>"
            ShowHTML "          <td align=""right""><font size=""1""><b>" & FormatNumber(w_parcial,2) & "&nbsp;</font></td>"
            ShowHTML "          <td colspan=2><font size=""1"">&nbsp;</font></td>"
            ShowHTML "        </tr>"
         End If
      
         ' Se for a �ltima p�gina da listagem, soma e exibe o valor total
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
            ShowHTML "          <td colspan=6 align=""right""><font size=""1""><b>Total da listagem&nbsp;</font></td>"
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
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>"
    ElseIf O = "C" Then ' Se for c�pia
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Para selecionar a a��o que deseja copiar, informe nos campos abaixo os crit�rios de sele��o e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>"
    End If
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td align=""center"" valign=""top""><table border=0 width=""90%"" cellspacing=0>"
    AbreForm "Form", w_dir & w_pagina & par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    If O = "C" Then ' Se for c�pia, cria par�metro para facilitar a recupera��o dos registros
       ShowHTML "<INPUT type=""hidden"" name=""w_copia"" value=""OK"">"
    End If
    If P1 <> 1 or O = "C" Then ' Se n�o for cadastramento ou se for c�pia
       ' Recupera dados da op��o das a��es
       DB_GetLinkData RS, w_cliente, "ISACAD"
       ShowHTML "      <tr valign=""top""><td colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
       ShowHTML "      <tr valign=""top"">"
       SelecaoAcaoPPA "<u>A</u>��o PPA:", "A", null, w_cliente, w_ano, null, null, p_sq_acao_ppa, null, "p_sq_acao_ppa", null, null, null, w_menu
       ShowHTML "      </tr>"
       ShowHTML "      <tr valign=""top"">"
       SelecaoIsProjeto "<u>P</u>rograma interno:", "P", null, p_sq_isprojeto, null, "p_sq_isprojeto", null, null
       ShowHTML "      </tr>"
       ShowHTML "      </table>"
       DesconectaBD
       ShowHTML "      <tr valign=""top"">"
       ShowHTML "          <td valign=""top""><font size=""1""><b>Dias para a data limi<U>t</U>e:<br><INPUT ACCESSKEY=""T"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_prazo"" size=""2"" maxlength=""2"" value=""" & p_prazo & """></td>"
       ShowHTML "      <tr valign=""top"">"
       SelecaoPessoa "Respo<u>n</u>s�vel:", "N", "Selecione o respons�vel pela a��o na rela��o.", p_solicitante, null, "p_solicitante", "USUARIOS"
       SelecaoUnidade "<U>�</U>rea planejamento:", "A", null, p_unidade, null, "p_unidade", null, null
       ShowHTML "      <tr valign=""top"">"
       SelecaoPessoa "Respons�vel atua<u>l</u>:", "L", "Selecione o respons�vel atual pela a��o na rela��o.", p_usu_resp, null, "p_usu_resp", "USUARIOS"
       SelecaoUnidade "<U>S</U>etor atual:", "S", "Selecione a unidade onde a a��o se encontra na rela��o.", p_uorg_resp, null, "p_uorg_resp", null, null
       ShowHTML "      <tr>"
       'ShowHTML "          <td valign=""top""><font size=""1""><b>A<U>s</U>sunto:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_assunto"" size=""25"" maxlength=""90"" value=""" & p_assunto & """></td>"
       ShowHTML "          <td valign=""top""><font size=""1""><b>Par<U>c</U>erias externas:<br><INPUT ACCESSKEY=""P"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_proponente"" size=""25"" maxlength=""90"" value=""" & p_proponente & """></td>"
       ShowHTML "          <td valign=""top""><font size=""1""><b>Par<U>c</U>erias internas:<br><INPUT ACCESSKEY=""C"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_palavra"" size=""25"" maxlength=""90"" value=""" & p_palavra & """></td>"
       ShowHTML "      <tr>"
       ShowHTML "          <td valign=""top""><font size=""1""><b>Data de re<u>c</u>ebimento entre:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""p_ini_i"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_ini_i & """ onKeyDown=""FormataData(this,event);"" title=""Usar formato dd/mm/aaaa""> e <input " & w_Disabled & " accesskey=""C"" type=""text"" name=""p_ini_f"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_ini_f & """ onKeyDown=""FormataData(this,event);"" title=""Usar formato dd/mm/aaaa""></td>"
       ShowHTML "          <td valign=""top""><font size=""1""><b>Limi<u>t</u>e para conclus�o entre:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""p_fim_i"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_fim_i & """ onKeyDown=""FormataData(this,event);"" title=""Usar formato dd/mm/aaaa""> e <input " & w_Disabled & " accesskey=""T"" type=""text"" name=""p_fim_f"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_fim_f & """ onKeyDown=""FormataData(this,event);"" title=""Usar formato dd/mm/aaaa""></td>"
       If O <> "C" Then ' Se n�o for c�pia
          ShowHTML "      <tr>"
          ShowHTML "          <td valign=""top""><font size=""1""><b>Exibe somente a��es em atraso?</b><br>"
          If p_atraso = "S" Then
             ShowHTML "              <input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_atraso"" value=""S"" checked> Sim <br><input " & w_Disabled & " class=""STR"" class=""STR"" type=""radio"" name=""p_atraso"" value=""N""> N�o"
          Else
             ShowHTML "              <input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_atraso"" value=""S""> Sim <br><input " & w_Disabled & " class=""STR"" class=""STR"" type=""radio"" name=""p_atraso"" value=""N"" checked> N�o"
          End If
          SelecaoFaseCheck "Recuperar fases:", "S", null, p_fase, P2, "p_fase", null, null
       End If
    End If
    ShowHTML "      <tr>"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>O</U>rdena��o por:<br><SELECT ACCESSKEY=""O"" " & w_Disabled & " class=""STS"" name=""p_ordena"" size=""1"">"
    If p_Ordena="INICIO" Then
       ShowHTML "          <option value=""inicio"" SELECTED>Data de recebimento<option value="""">Data limite para conclus�o<option value=""nm_tramite"">Fase atual"
    ElseIf p_Ordena="NM_TRAMITE" Then
       ShowHTML "          <option value=""inicio"">Data de recebimento<option value="""">Data limite para conclus�o<option value=""nm_tramite"" SELECTED>Fase atual"
    Else
       ShowHTML "          <option value=""inicio"">Data de recebimento<option value="""" SELECTED>Data limite para conclus�o<option value=""nm_tramite"">Fase atual"
    End If
    ShowHTML "          </select></td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>L</U>inhas por p�gina:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""STI"" type=""text"" name=""P4"" size=""4"" maxlength=""4"" value=""" & P4 & """></td></tr>"
    ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""2"">"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
    If O = "C" Then ' Se for c�pia
       ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"" name=""Botao"" value=""Abandonar c�pia"">"
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
    ShowHTML " alert('Op��o n�o dispon�vel');"
    ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  Rodape

  Set w_titulo = Nothing
End Sub
REM =========================================================================
REM Fim da tabela de a��es
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina dos dados gerais
REM -------------------------------------------------------------------------
Sub Geral
  Dim w_sq_unidade_resp, w_titulo, w_prioridade, w_aviso, w_dias
  Dim w_inicio_real, w_fim_real, w_concluida, w_proponente
  Dim w_data_conclusao, w_nota_conclusao, w_custo_real, w_sqcc
  Dim w_sq_acao_ppa, w_sq_isprojeto, w_selecao_mp, w_selecao_se
  
  Dim w_chave, w_chave_pai, w_chave_aux, w_sq_menu, w_sq_unidade
  Dim w_sq_tramite, w_solicitante, w_cadastrador, w_executor
  Dim w_inicio, w_fim, w_inclusao, w_ultima_alteracao
  Dim w_conclusao, w_valor, w_opiniao, w_data_hora, w_pais, w_uf, w_cidade, w_palavra_chave
  Dim w_descricao, w_justificativa, w_sq_unidade_adm
  Dim w_subacao
  
  Dim w_troca, i, w_erro, w_como_funciona, w_cor, w_readonly

  w_chave           = Request("w_chave")
  w_readonly        = ""
  w_erro            = ""
  w_troca           = Request("w_troca")

  ' Verifica se h� necessidade de recarregar os dados da tela a partir
  ' da pr�pria tela (se for recarga da tela) ou do banco de dados (se n�o for inclus�o)
  If w_troca > "" Then ' Se for recarga da p�gina
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
     w_sq_isprojeto            = Request("w_sq_isprojeto")
     w_descricao               = Request("w_descricao")
     w_justificativa           = Request("w_justificativa")
     w_selecao_mp              = Request("w_selecao_mp")
     w_selecao_se              = Request("w_selecao_se")
     w_sq_unidade_adm          = Request("w_sq_unidade_adm")
     
     If w_sq_acao_ppa > "" Then
        DB_GetAcaoPPA_IS RS, w_cliente, w_ano, Mid(w_sq_acao_ppa,1,4), Mid(w_sq_acao_ppa,5,4), null, Mid(w_sq_acao_ppa,13,17), null, null, null
        w_titulo                  = Mid(RS("descricao_acao"),1,69) & " - " & Mid(RS("ds_unidade"),1,28)
     ElseIf w_sq_isprojeto > "" Then
        DB_GetProjeto_IS RS, w_sq_isprojeto, w_cliente, null, null, null, null, null, null, null, null, null, null, null, null
        w_titulo                  = RS("nome")
     End If
  Else
     If InStr("AEV",O) > 0 or w_copia > "" Then
        ' Recupera os dados da a��o
        If w_copia > "" Then
           DB_GetSolicData_IS RS, w_copia, SG
        Else
           DB_GetSolicData_IS RS, w_chave, SG
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
           w_sq_acao_ppa            = RS("cd_ppa_pai")&RS("cd_acao")&RS("cd_subacao")&RS("cd_unidade")
           w_sq_isprojeto           = RS("sq_isprojeto")
           w_selecao_mp             = RS("mpog_ppa")
           w_selecao_se             = RS("relev_ppa")
           w_pais                   = RS("sq_pais") 
           w_uf                     = RS("co_uf") 
           w_cidade                 = RS("sq_cidade_origem") 
           w_palavra_chave          = RS("palavra_chave") 
           w_descricao              = RS("descricao")
           w_justificativa          = RS("justificativa") 
           w_sq_unidade_adm         = RS("sq_unidade_adm") 
           DesconectaBD
        End If

     End If
     
  End If  
  Cabecalho
  ShowHTML "<HEAD>"
  ' Monta o c�digo JavaScript necess�rio para valida��o de campos e preenchimento autom�tico de m�scara,
  ' tratando as particularidades de cada servi�o
  ScriptOpen "JavaScript"
  CheckBranco
  FormataData
  FormataDataHora
  FormataValor
  ValidateOpen "Validacao"
  If O = "I" or O = "A" Then
     ShowHTML "  if (theForm.Botao.value == ""Troca"") { return true; }"
     Validate "w_titulo", "A��o", "1", 1, 5, 100, "1", "1"
     ShowHTML "  if (theForm.w_sq_acao_ppa.selectedIndex==0 && theForm.w_sq_isprojeto.selectedIndex==0) {"
     ShowHTML "     alert('Informe a iniciativa priorit�ria e/ou a a��o do PPA!');"
     ShowHTML "     theForm.w_sq_isprojeto.focus();"
     ShowHTML "     return false;"
     ShowHTML "  }"
     Validate "w_sq_unidade_adm", "Unidade administrativa", "HIDDEN", 1, 1, 18, "", "0123456789"
     Validate "w_solicitante", "Respons�vel monitoramento", "HIDDEN", 1, 1, 18, "", "0123456789"
     Validate "w_sq_unidade_resp", "�rea de planejamento", "SELECT", 1, 1, 18, "", "0123456789"
     Validate "w_inicio", "In�cio previsto", "DATA", 1, 10, 10, "", "0123456789/"
     Validate "w_fim", "Fim previsto", "DATA", 1, 10, 10, "", "0123456789/"
     CompData "w_inicio", "Data de recebimento", "<=", "w_fim", "Limite para conclus�o"
     'Validate "w_valor", "Recurso programado", "VALOR", "1", 4, 18, "", "0123456789.,"
     'If w_sq_acao_ppa > "" Then
     '   CompValor "w_valor", "Recurso programado", ">", "0,00", "zero"
     'End If
     Validate "w_proponente", "Parcerias externas", "", "", 2, 90, "1", "1"
     Validate "w_palavra_chave", "Parcerias internas", "", "", 2, 90, "1", "1"
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
       ' Carrega os valores padr�o para pa�s, estado e cidade
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
    ShowHTML "<INPUT type=""hidden"" name=""w_valor"" value=""0,00"">"
    
    'Passagem da cidade padr�o como bras�lia, pelo retidara do impacto geogr�fico da tela
    DB_GetCustomerData RS, w_cliente
    ShowHTML "<INPUT type=""hidden"" name=""w_cidade"" value=""" & RS("sq_cidade_padrao") &""">"
    DesconectaBD

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td valign=""top"" align=""center"" bgcolor=""#D0D0D0""><font size=""2""><b>Identifica��o</td></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td><font size=1>Os dados deste bloco ser�o utilizados para identifica��o da a��o, bem como para o controle de sua execu��o.</font></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    If w_sq_acao_ppa > "" Then
       ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>A</u>��o:</b><br><INPUT READONLY ACCESSKEY=""A"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_titulo"" size=""90"" maxlength=""100"" value=""" & w_titulo & """ ></td>"
    Else
       ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>A</u>��o:</b><br><INPUT ACCESSKEY=""A"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_titulo"" size=""90"" maxlength=""100"" value=""" & w_titulo & """ ></td>"
    End If
    ShowHTML "          <tr>"
    If O = "I" or w_sq_acao_ppa = "" Then
       SelecaoIsProjeto "<u>P</u>rograma interno:", "P", null, w_sq_isprojeto, null, "w_sq_isprojeto", "CADASTRAMENTO", "onchange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_sq_acao_ppa'; document.Form.submit();"""
    Else
       SelecaoIsProjeto "<u>P</u>rograma interno:", "P", null, w_sq_isprojeto, null, "w_sq_isprojeto", null, "onchange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_sq_unidade_adm'; document.Form.submit();"""
    End If
    ShowHTML "          </tr>"
    ShowHTML "          <tr>"
    If O = "I" or w_sq_acao_ppa = "" Then
       SelecaoAcaoPPA "A��o <u>P</u>PA:", "P", null, w_cliente, w_ano, Mid(w_sq_acao_ppa,1,4), Mid(w_sq_acao_ppa,5,4), Mid(w_sq_acao_ppa,9,4),  Mid(w_sq_acao_ppa,13,5), "w_sq_acao_ppa", "IDENTIFICACAO", "onchange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_sq_acao_ppa'; document.Form.submit();""", null, w_menu
    Else
       SelecaoAcaoPPA "A��o <u>P</u>PA:", "P", null, w_cliente, w_ano, Mid(w_sq_acao_ppa,1,4), Mid(w_sq_acao_ppa,5,4), Mid(w_sq_acao_ppa,9,4),  Mid(w_sq_acao_ppa,13,5), "w_sq_acao_ppa", null, "disabled", null, w_menu
       ShowHTML "<INPUT type=""hidden"" name=""w_sq_acao_ppa"" value=""" & w_sq_acao_ppa &""">"
    End If
    ShowHTML "          </tr>"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
    SelecaoUnidade_IS "<U>U</U>nidade administrativa:", "u", "Selecione a unidade administrativa respons�vel pela a��o.", w_sq_unidade_adm, null, "w_sq_unidade_adm", null, "ADMINISTRATIVA"
    ShowHTML "      <tr valign=""top"">"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
    MontaRadioNS "<b>Selecionada pelo SPI/MP?</b>", w_selecao_mp, "w_selecao_mp"
    MontaRadioNS "<b>Selecionada pelo SE/SEPPIR?</b>", w_selecao_se, "w_selecao_se"
    ShowHTML "      </table></td></tr>"
    ShowHTML "      <tr valign=""top"">"
    SelecaoPessoa "Respo<u>n</u>s�vel monitoramento:", "N", "Selecione o nome da pessoa respons�vel pelas informa��es no SISPLAM.", w_solicitante, null, "w_solicitante", "USUARIOS"
    ShowHTML "      <tr valign=""top"">"
    SelecaoUnidade_IS "<U>�</U>rea de planejamento:", "A", "Selecione a �rea da secretaria ou �rg�o respons�vel pela a��o.", w_sq_unidade_resp, null, "w_sq_unidade_resp", null, "PLANEJAMENTO"
    ShowHTML "          </table>"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "          <tr>"
    If w_sq_acao_ppa > "" Then
       ShowHTML "              <td valign=""top""><font size=""1""><b>In�<u>c</u>io previsto:</b><br><input readonly " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_inicio"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & Nvl(w_inicio, "01/01/"&w_ano)& """ onKeyDown=""FormataData(this,event);"" title=""Usar formato dd/mm/aaaa""></td>"
       ShowHTML "              <td valign=""top""><font size=""1""><b><u>F</u>im previsto:</b><br><input readonly " & w_Disabled & " accesskey=""F"" type=""text"" name=""w_fim"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & Nvl(w_fim, "31/12/"&w_ano)& """ onKeyDown=""FormataData(this,event);"" title=""Usar formato dd/mm/aaaa""></td>"
    Else
       ShowHTML "              <td valign=""top""><font size=""1""><b>In�<u>c</u>io previsto:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_inicio"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & Nvl(w_inicio, "01/01/"&w_ano)& """ onKeyDown=""FormataData(this,event);"" title=""Usar formato dd/mm/aaaa""></td>"
       ShowHTML "              <td valign=""top""><font size=""1""><b><u>F</u>im previsto:</b><br><input " & w_Disabled & " accesskey=""F"" type=""text"" name=""w_fim"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & Nvl(w_fim, "31/12/"&w_ano) & """ onKeyDown=""FormataData(this,event);"" title=""Usar formato dd/mm/aaaa""></td>"    
    End If
    'ShowHTML "              <td valign=""top""><font size=""1""><b><u>R</u>ecurso programado:</b><br><input " & w_Disabled & " accesskey=""O"" type=""text"" name=""w_valor"" class=""STI"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_valor & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o recurso programado para a execu��o da a��o.""></td>"
    ShowHTML "          </table>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Parc<u>e</u>rias externas:<br><INPUT ACCESSKEY=""E"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_proponente"" size=""90"" maxlength=""90"" value=""" & w_proponente & """ title=""Informar quais s�o os parceiros externos na execu��o da a��o (campo opcional).""></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>P</u>arcerias internas:<br><INPUT ACCESSKEY=""P"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_palavra_chave"" size=""90"" maxlength=""90"" value=""" & w_palavra_chave & """ title=""Informar quais s�o os parceiros internos na execu��o da a��o (campo opcional).""></td>"

    ' Verifica se poder� ser feito o envio da solicita��o, a partir do resultado da valida��o
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
    ShowHTML " alert('Op��o n�o dispon�vel');"
    ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_selecao_mp              = Nothing 
  Set w_selecao_se              = Nothing 
  Set w_sq_acao_ppa             = Nothing 
  Set w_sq_isprojeto            = Nothing 
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
REM Rotina de cadastramento do recurso programado
REM -------------------------------------------------------------------------
Sub RecursoProgramado
  Dim w_sq_unidade_resp, w_titulo, w_prioridade, w_aviso, w_dias
  Dim w_inicio_real, w_fim_real, w_concluida, w_proponente
  Dim w_data_conclusao, w_nota_conclusao, w_custo_real
  Dim w_sq_acao_ppa, w_sq_isprojeto, w_selecao_mp, w_selecao_se
  
  Dim w_chave, w_chave_pai, w_chave_aux, w_sq_menu, w_sq_unidade
  Dim w_sq_tramite, w_solicitante, w_cadastrador, w_executor
  Dim w_inicio, w_fim, w_inclusao, w_ultima_alteracao
  Dim w_conclusao, w_valor, w_opiniao, w_data_hora, w_pais, w_uf, w_cidade, w_palavra_chave
  Dim w_descricao, w_justificativa, w_sq_unidade_adm
  Dim w_subacao

  
  Dim w_troca, i, w_erro, w_como_funciona, w_cor, w_readonly

  w_chave           = Request("w_chave")
  w_readonly        = ""
  w_erro            = ""
  w_troca           = Request("w_troca")
  
  If InStr("A",O) > 0 or w_copia > "" Then
     ' Recupera os dados da a��o
     DB_GetSolicData_IS RS, w_chave, SG
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
        w_sq_acao_ppa            = RS("cd_ppa_pai")&RS("cd_acao")&RS("cd_subacao")&RS("cd_unidade")
        w_sq_isprojeto           = RS("sq_isprojeto")
        w_selecao_mp             = RS("mpog_ppa")
        w_selecao_se             = RS("relev_ppa")
        w_pais                   = RS("sq_pais") 
        w_uf                     = RS("co_uf") 
        w_cidade                 = RS("sq_cidade_origem") 
        w_palavra_chave          = RS("palavra_chave") 
        w_descricao              = RS("descricao")
        w_justificativa          = RS("justificativa") 
        w_sq_unidade_adm         = RS("sq_unidade_adm")         
     End If
  End If  
  Cabecalho
  ShowHTML "<HEAD>"
  ' Monta o c�digo JavaScript necess�rio para valida��o de campos e preenchimento autom�tico de m�scara,
  ' tratando as particularidades de cada servi�o
  ScriptOpen "JavaScript"
  CheckBranco
  FormataData
  FormataDataHora
  FormataValor
  ValidateOpen "Validacao"
  If O = "A" Then
     Validate "w_valor", "Recurso programado", "VALOR", "1", 4, 18, "", "0123456789.,"
     CompValor "w_valor", "Recurso programado", ">", "0,00", "zero"
     Validate "w_assinatura", "Assinatura Eletr�nica", "1", "1", "6", "30", "1", "1"
  ElseIf O = "P" Then
     Validate "w_chave", "A��o PPA", "SELECT", "1", 1, 18, "", "0123456789"
  End If
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If Instr("A",O) > 0 Then
     BodyOpen "onLoad='document.Form.w_valor.focus()';"
  Else
     BodyOpen "onLoad='document.Form.w_chave.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<table align=""center"" border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If Instr("A",O) > 0 Then
    AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
    ShowHTML MontaFiltro("POST")
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_copia"" value=""" & w_copia &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & RS_menu("sq_menu") &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_descricao"" value=""" & w_descricao & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_justificativa"" value=""" & w_justificativa & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_proponente"" value=""" & w_proponente & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_unidade_resp"" value=""" & w_sq_unidade_resp & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_titulo"" value=""" & w_titulo & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_prioridade"" value=""" & w_prioridade & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_aviso"" value=""" & w_aviso & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_dias"" value=""" & w_dias & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_inicio_real"" value=""" & w_inicio_real & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_fim_real"" value=""" & w_fim_real & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_concluida"" value=""" & w_concluida & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_data_conclusao"" value=""" & w_data_conclusao & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_nota_conclusao"" value=""" & w_nota_conclusao & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_custo_real"" value=""" & w_custo_real & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave_pai"" value=""" & w_chave_pai & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_menu"" value=""" & w_sq_menu & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_unidade"" value=""" & w_sq_unidade & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_tramite"" value=""" & w_sq_tramite & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_solicitante"" value=""" & w_solicitante & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_cadastrador"" value=""" & w_cadastrador & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_executor"" value=""" & w_executor & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_inicio"" value=""" & w_inicio & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_fim"" value=""" & w_fim & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_inclusao"" value=""" & w_inclusao & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_ultima_alteracao"" value=""" & w_ultima_alteracao & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_conclusao"" value=""" & w_conclusao & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_opiniao"" value=""" & w_opiniao & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_data_hora"" value=""" & w_data_hora & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_selecao_mp"" value=""" & w_selecao_mp & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_selecao_se"" value=""" & w_selecao_se & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_palavra_chave"" value=""" & w_palavra_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_unidade_adm"" value=""" & w_sq_unidade_adm & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_acao_ppa"" value=""" & w_sq_acao_ppa & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_isprojeto"" value=""" & w_sq_isprojeto & """>"
    
    'Passagem da cidade padr�o como bras�lia, pelo retidara do impacto geogr�fico da tela
    DB_GetCustomerData RS1, w_cliente
    ShowHTML "<INPUT type=""hidden"" name=""w_cidade"" value=""" & RS1("sq_cidade_padrao") &""">"
    RS1.Close

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"

   ShowHTML "    <table width=""99%"" border=""0"">"
   ShowHTML "      <tr valign=""top""><td colspan=""2""><font size=2>A��o: <b>" & RS("titulo") & "</b></font></td></tr>"
    
    ' Identifica��o da a��o
   ShowHTML "      <tr valign=""top""><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Identifica��o</td>"
    
    ' Se a a��o no PPA for informada, exibe.
    If Not IsNull(RS("cd_acao")) Then
      ShowHTML "   <tr valign=""top"" bgcolor=""#D0D0D0""><td colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
      ShowHTML "     <tr valign=""top""><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
      ShowHTML "       <tr bgcolor=""#D0D0D0""><td colspan=""1"" nowrap><font size=""1"">Unidade:<br><b>" & RS("cd_unidade") & " - " & RS("ds_unidade") & " </b></td>"
      ShowHTML "        <td><font size=""1"">�rg�o:<br><b>" & RS("cd_orgao") & " - " & RS("nm_orgao") & " </b></td></tr>"
      ShowHTML "     </table></td></tr>"
      ShowHTML "      <tr bgcolor=""#D0D0D0""><td colspan=""2""><font size=""1"">Programa PPA:<br><b>" & RS("cd_ppa_pai") & " - " & RS("nm_ppa_pai") & "</b></td></tr>"
      ShowHTML "      <tr bgcolor=""#D0D0D0""><td colspan=""1""><font size=""1"">A��o PPA:<br><b>" & RS("cd_acao") & " - " & RS("nm_ppa") & " </b></td>"
      ShowHTML "        <td valign=""top"" nowrap><font size=""1"">Recurso programado:<br><b>" & FormatNumber(RS("valor"),2) & " </b></td>"
      ShowHTML "   </table>"
    End If        
    ' Se a programa interno for informado, exibe.
    If Not IsNull(RS("sq_isprojeto")) Then
      ShowHTML "      <tr><td valign=""top"" colspan=""1""><font size=""1"">Programa interno:<br><b>" & RS("nm_pri")
       If Not IsNull(RS("cd_pri")) Then 
         ShowHTML " (" & RS("cd_pri") & ")" 
       End If
       If IsNull(RS("cd_acao")) Then
         ShowHTML "          <td><font size=""1"">Recurso programado:<br><b>" & FormatNumber(RS("valor"),2) & " </b></td>"
       End If
    End If
   ShowHTML "  <tr><td colspan=""2""><font size=""1"">Unidade Administrativa:<br><b>" & ExibeUnidade("../", w_cliente, RS("nm_unidade_adm"), RS("sq_unidade_adm"), TP) & "</b></td>"
   ShowHTML "   <tr valign=""top""><td colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
   ShowHTML "     <tr valign=""top"">"
    If RS("mpog_ppa") = "S" Then
      ShowHTML "    <td><font size=""1"">Selecionada SPI/MP:<br><b>Sim</b></td>"
    Else
      ShowHTML "    <td><font size=""1"">Selecionada SPI/MP:<br><b>N�o</b></td>"
    End If
    If RS("relev_ppa") = "S" Then
      ShowHTML "    <td><font size=""1"">Selecionada SE/SEPPIR:<br><b>Sim</b></td>"
    Else
      ShowHTML "    <td><font size=""1"">Selecionada SE/SEPPIR:<br><b>N�o</b></td>"
    End If
   ShowHTML "     <tr valign=""top"">"
   ShowHTML "    <td><font size=""1"">Respons�vel monitoramento:<br><b>" & ExibePessoa("../", w_cliente, RS("solicitante"), TP, RS("nm_sol")) & "</b></td>"
   ShowHTML "    <td><font size=""1"">�rea planejamento:<br><b>" & ExibeUnidade("../", w_cliente, RS("nm_unidade_resp"), RS("sq_unidade"), TP) & "</b></td>"
    If Not IsNull(RS("cd_acao")) Then
       DB_GetAcaoPPA_IS RS1, w_cliente, w_ano, RS("cd_ppa_pai"), RS("cd_acao"), null, RS("cd_unidade"), null, null, null
      ShowHTML "     <tr valign=""top"">"
      ShowHTML "       <td><font size=""1"">Fun��o:<br><b>" & RS1("ds_funcao") & " </b></td>"
      ShowHTML "       <td><font size=""1"">Subfun��o:<br><b>" & RS1("ds_subfuncao") & " </b></td>"
      ShowHTML "     <tr valign=""top"">"
      ShowHTML "       <td><font size=""1"">Esfera:<br><b>" & RS1("ds_esfera") & " </b></td>"
      ShowHTML "       <td><font size=""1"">Tipo de a��o:<br><b>" & RS1("nm_tipo_acao") & " </b></td>"    
       RS1.Close
    End If
   ShowHTML "     <tr valign=""top"">"
   ShowHTML "       <td><font size=""1"">In�cio previsto:<br><b>" & FormataDataEdicao(RS("inicio")) & " </b></td>"
   ShowHTML "       <td><font size=""1"">Fim previsto:<br><b>" & FormataDataEdicao(RS("fim")) & " </b></td>"
   ShowHTML "     </table>"
   ShowHTML "     <tr valign=""top""><td colspan=""2""><font size=""1"">Parcerias externas:<br><b>" & CRLF2BR(Nvl(RS("proponente"),"---")) & " </b></td>"
   ShowHTML "     <tr valign=""top""><td colspan=""2""><font size=""1"">Parcerias internas:<br><b>" & CRLF2BR(Nvl(RS("palavra_chave"),"---")) & " </b></td>"
     
    ' Responsaveis
    If RS("nm_gerente_programa") > "" or RS("nm_gerente_executivo") > "" or RS("nm_gerente_adjunto") > "" or RS("resp_ppa") > "" or RS("resp_pri") > ""  Then  
      ShowHTML "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Respons�veis</td>" 
    End If
    If RS("nm_gerente_programa") > "" or RS("nm_gerente_executivo") > "" or RS("nm_gerente_adjunto") > "" Then  
      ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
       If Not IsNull(RS("nm_gerente_programa")) Then           
         ShowHTML "      <tr><td valign=""top""><font size=""1"">Gerente do programa:<br><b>" & RS("nm_gerente_programa") & " </b></td>"
          If Not IsNull(RS("fn_gerente_programa")) Then
            ShowHTML "          <td><font size=""1"">Telefone:<br><b>" & RS("fn_gerente_programa") & " </b></td>"
          End If
          If Not IsNull(RS("em_gerente_programa")) Then
            ShowHTML "          <td><font size=""1"">Email:<br><b>" & RS("em_gerente_programa") & " </b></td>"
          End If
       End If
       If Not IsNull(RS("nm_gerente_executivo")) Then
         ShowHTML "      <tr><td valign=""top""><font size=""1"">Gerente executivo do programa:<br><b>" & RS("nm_gerente_executivo") & " </b></td>"
          If Not IsNull(RS("fn_gerente_executivo")) Then
            ShowHTML "          <td><font size=""1"">Telefone:<br><b>" & RS("fn_gerente_executivo") & " </b></td>"
          End If
          If Not IsNull(RS("em_gerente_executivo")) Then
            ShowHTML "          <td><font size=""1"">Email:<br><b>" & RS("em_gerente_executivo") & " </b></td>"
          End If
       End If
       If Not IsNull(RS("nm_gerente_adjunto")) Then
         ShowHTML "      <tr><td valign=""top""><font size=""1"">Gerente executivo adjunto:<br><b>" & RS("nm_gerente_adjunto") & " </b></td>"
          If Not IsNull(RS("fn_gerente_adjunto")) Then
            ShowHTML "          <td><font size=""1"">Telefone:<br><b>" & RS("fn_gerente_adjunto") & " </b></td>"
          End If
          If Not IsNull(RS("em_gerente_adjunto")) Then
            ShowHTML "          <td><font size=""1"">Email:<br><b>" & RS("em_gerente_adjunto") & " </b></td>"
          End If
       End If
      ShowHTML "          </table>"
    End If
    If Not IsNull(RS("resp_ppa")) Then
      ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
      ShowHTML "        <tr><td valign=""top""><font size=""1"">Coordenador:<br><b>" & RS("resp_ppa") & " </b></td>"
       If Not IsNull(RS("fone_ppa")) Then
         ShowHTML "          <td><font size=""1"">Telefone:<br><b>" & RS("fone_ppa") & " </b></td>"
       End If
       If Not IsNull(RS("mail_ppa")) Then
         ShowHTML "          <td><font size=""1"">Email:<br><b>" & RS("mail_ppa") & " </b></td>"
       End If
      ShowHTML "          </table>"
    End If
    If Not IsNull(RS("resp_pri")) Then
      ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
      ShowHTML "        <tr><td valign=""top""><font size=""1"">Respons�vel pela a��o:<br><b>" & RS("resp_pri") & " </b></td>"
       If Not IsNull(RS("fone_pri")) Then
         ShowHTML "         <td><font size=""1"">Telefone:<br><b>" & RS("fone_pri") & " </b></td>"
       End If
       If Not IsNull(RS("mail_pri")) Then
         ShowHTML "            <td><font size=""1"">Email:<br><b>" & RS("mail_pri") & " </b></td>"
       End If
      ShowHTML "           </table>"
    End If
    If Not IsNull(RS("cd_acao")) Then
       ShowHTML "        <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Programa��o financeira</td>"
       If cDbl(RS("cd_tipo_acao")) <> 3 Then
          DB_GetPPADadoFinanc_IS RS1, RS("cd_acao"), RS("cd_unidade"), w_ano, w_cliente, "VALORFONTEACAO"
          If RS1.EOF Then
             ShowHTML "                      <tr><td valign=""top"" colspan=""2""><font size=""1""><DD><b>Nao existe nenhum valor para esta a��o</b></DD></td>"
          Else
             w_cor = ""
             ShowHTML "                      <tr><td valign=""top"" colspan=""2""><font size=""1"">Fonte: SIGPLAN/MP - PPA 2004-2007</td>"
             If cDbl(RS("cd_tipo_acao")) = 1 Then
                ShowHTML "                   <tr><td valign=""top"" colspan=""2""><font size=""1"">Realizado at� 2004: <b>" & FormatNumber(Nvl(RS("valor_ano_anterior"),0),2) & "</b></td>"
                ShowHTML "                   <tr><td valign=""top"" colspan=""2""><font size=""1"">Justificativa da repercus�o financeira sobre o custeio da Uni�o: <b>" & Nvl(RS("reperc_financeira"),"---") & "</b></td>"
                ShowHTML "                   <tr><td valign=""top"" colspan=""2""><font size=""1"">Valor estimado da repercuss�o financeira por ano (R$ 1,00): <b>" & FormatNumber(Nvl(RS("valor_reperc_financeira"),0),2) & "</b></td>"
             End If
             ShowHTML "                      <tr><td valign=""top"" colspan=""2""><font size=""1""><b>A��o: </b>" & RS1("cd_unidade") & "." & RS("cd_programa") & "." & RS1("cd_acao") & " - " & RS1("descricao_acao") & "</td>"
             ShowHTML "                      <tr><td valign=""top"" align=""center"">"
             ShowHTML "                        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
             ShowHTML "                          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
             ShowHTML "                            <td><font size=""1""><b>Fonte</font></td>"
             ShowHTML "                            <td><font size=""1""><b>2004*</font></td>"
             ShowHTML "                            <td><font size=""1""><b>2005**</font></td>"
             ShowHTML "                            <td><font size=""1""><b>2006</font></td>"
             ShowHTML "                            <td><font size=""1""><b>2007</font></td>"
             ShowHTML "                            <td><font size=""1""><b>2008</font></td>"
             ShowHTML "                            <td><font size=""1""><b>Total 2004-2008</font></td>"
             ShowHTML "                          </tr>"
             While Not RS1.EOF 
                If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
                ShowHTML "                       <tr bgcolor=""" & w_cor & """ valign=""top"">"
                ShowHTML "                         <td><font size=""1"">" & RS1("nm_fonte")& "</td>"
                ShowHTML "                         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_1"),0.00)))& "</td>"
                ShowHTML "                         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_2"),0.00)))& "</td>"
                ShowHTML "                         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_3"),0.00)))& "</td>"
                ShowHTML "                         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_4"),0.00)))& "</td>"
                ShowHTML "                         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_5"),0.00)))& "</td>"
                ShowHTML "                         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_total"),0.00)))& "</td>"
                ShowHTML "                       </tr>"
                RS1.MoveNext
             wend
             RS1.Close
             DB_GetPPADadoFinanc_IS RS1, RS("cd_acao"), RS("cd_unidade"), w_ano, w_cliente, "VALORTOTALACAO"
             ShowHTML"      <tr><td valign=""top"" align=""right""><font size=""1""><b>Totais </td>"
             If RS1.EOF Then
                ShowHTML "          <td valign=""top"" colspan=""6""><font size=""1""><DD><b>Nao existe nenhum valor para esta a��o</b></DD></td>"
             Else
                If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
                ShowHTML "         <td align=""center""><font size=""1""><b>" & FormatNumber(cDbl(Nvl(RS1("valor_ano_1"),0.00)))& "</td>"
                ShowHTML "         <td align=""center""><font size=""1""><b>" & FormatNumber(cDbl(Nvl(RS1("valor_ano_2"),0.00)))& "</td>"
                ShowHTML "         <td align=""center""><font size=""1""><b>" & FormatNumber(cDbl(Nvl(RS1("valor_ano_3"),0.00)))& "</td>"
                ShowHTML "         <td align=""center""><font size=""1""><b>" & FormatNumber(cDbl(Nvl(RS1("valor_ano_4"),0.00)))& "</td>"
                ShowHTML "         <td align=""center""><font size=""1""><b>" & FormatNumber(cDbl(Nvl(RS1("valor_ano_5"),0.00)))& "</td>"
                ShowHTML "         <td align=""center""><font size=""1""><b>" & FormatNumber(cDbl(Nvl(RS1("valor_total"),0.00)))& "</td>"
                ShowHTML "       </tr>"
                ShowHTML "       </table>"  
             End If
          End If   
          RS1.Close
          ShowHTML "<tr><td valign=""top"" colspan=""2""><font size=""1"">* Valor Lei Or�ament�ria Anual - LOA 2004 + Cr�ditos</td>"
          ShowHTML "<tr><td valign=""top"" colspan=""2""><font size=""1"">** Valor do Projeto de Lei Or�ament�ria Anual - PLOA 2005</td>"    
       End If
       ' Recupera todos os registros para a listagem
       DB_GetFinancAcaoPPA_IS RS1, w_chave, w_cliente, w_ano, null, null, null
       ' Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
       If Not RS1.EOF Then ' Se n�o foram selecionados registros, exibe mensagem
          ShowHTML "<tr><td colspan=""2"" align=""center"">"
          ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
          ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
          ShowHTML "          <td><font size=""1""><b>C�digo</font></td>"
          ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
          ShowHTML "        </tr>"
          w_cor = ""
          ' Lista os registros selecionados para listagem
          While Not RS1.EOF
             If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
             ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
             ShowHTML "        <td><font size=""1"">" & RS1("cd_programa")& "." & RS1("cd_acao") & "." & RS1("cd_unidade")& "</td>"
             ShowHTML "        <td><font size=""1"">" & RS1("descricao_acao") & "</td>"
             ShowHTML "      </tr>"
             RS1.MoveNext
          wend
          ShowHTML "          </table>"   
       End If
    Else
       ' Recupera todos os registros para a listagem
       DB_GetFinancAcaoPPA_IS RS1, w_chave, w_cliente, w_ano, null, null, null
       ' Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
       If Not RS1.EOF Then ' Se n�o foram selecionados registros, exibe mensagem
          ShowHTML "        <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Programa��o financeira</td>"
          ShowHTML "<tr><td colspan=""2"" align=""center"">"
          ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
          ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
          ShowHTML "          <td><font size=""1""><b>C�digo</font></td>"
          ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
          ShowHTML "        </tr>"
          w_cor = ""
          ' Lista os registros selecionados para listagem
          While Not RS1.EOF
             If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
             ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
             ShowHTML "        <td><font size=""1"">" & RS1("cd_unidade") & "." & RS1("cd_programa")& "." & RS1("cd_acao") & "</td>"
             ShowHTML "        <td><font size=""1"">" & RS1("descricao_acao") & "</td>"
             ShowHTML "      </tr>"
             RS1.MoveNext
          wend
          ShowHTML "          </table>"   
       End If
    End If
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>R</u>ecurso programado:</b><br><input " & w_Disabled & " accesskey=""O"" type=""text"" name=""w_valor"" class=""STI"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_valor & """ onKeyDown=""FormataValor(this,18,2,event);""></td>"
    ShowHTML "      <tr><td><font size=""1""><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
    ShowHTML "      <tr><td align=""center"" colspan=""2""><hr>"
    ShowHTML "      <tr><td align=""center"" colspan=""2"">"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Gravar"">"
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_pagina & par & "&O=P&SG=" & SG & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & MontaFiltro("GET") & "';"" name=""Botao"" value=""Cancelar"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  ElseIf Instr("P",O) > 0 Then
    AbreForm "Form", w_dir & w_Pagina & par, "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,"A"
    ShowHTML "<INPUT type=""hidden"" name=""w_cliente"" value=""" & w_cliente & """>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    DB_GetLinkData RS, w_cliente, "ISACAD"
    SelecaoAcao "A��<u>o</u>:", "O", "Selecione a a��o na rela��o.", w_cliente, w_ano, null, null, null, null, "w_chave", "ACAO", null, null
    'SelecaoProjeto "A��<u>o</u>:", "O", "Selecione a a��o na rela��o.", w_chave, w_usuario, RS("sq_menu"), "w_chave", "PJLIST", null
    DesconectaBD
    ShowHTML "      <tr><td align=""center"">"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"    
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Op��o n�o dispon�vel');"
    ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_selecao_mp              = Nothing 
  Set w_selecao_se              = Nothing 
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
  Set w_sq_acao_ppa             = Nothing 
  
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
REM Rotina dos responsaveis
REM -------------------------------------------------------------------------
Sub Responsaveis

  Dim w_chave, w_chave_aux, w_nome, w_tipo, w_nome_pai, w_codigo_pai
  Dim w_responsavel, w_email, w_telefone, w_label, w_codigo
  Dim w_sq_isprojeto, w_programa, w_acao, w_unidade
  
  Dim w_troca, i, w_erro
  
  w_Chave               = Request("w_Chave")
  w_Chave_aux           = Request("w_Chave_aux")
  w_programa            = Request("w_programa")
  w_acao                = Request("w_acao")
  w_unidade             = Request("w_unidade")
  w_sq_isprojeto        = Request("w_sq_isprojeto")
  w_nome_pai            = Request("w_nome_pai")
  w_codigo_pai          = Request("w_codigo_pai")
  
  If O = "L" Then
     ' Recupera todos os registros para a listagem
     DB_GetSolicData_IS RS, w_chave, SG
  ElseIf InStr("A",O) > 0 Then
     If w_programa > "" and w_acao > "" and w_unidade > "" Then
        w_tipo = 1
        DB_GetAcaoPPA_IS RS, w_cliente, w_ano, w_programa, w_acao, null, w_unidade, null, null, null
     ElseIf w_sq_isprojeto > "" Then
        w_tipo = 2
        DB_GetProjeto_IS RS, w_sq_isprojeto, w_cliente, null, null, null, null, null, null, null, null, null, null, null, null
     End If

     If Not RS.EOF Then
        w_responsavel          = RS("responsavel")
        w_telefone             = RS("telefone")
        w_email                = RS("email")
        If w_tipo = 1 then     
           w_nome                 = RS("descricao_acao")& " - " & RS("ds_unidade")
           w_codigo               = RS("cd_acao")&"."&RS("cd_unidade")
           w_nome_pai             = RS("ds_programa")
           w_codigo_pai           = RS("cd_programa") 
        ElseIf w_tipo = 2 Then
           w_nome                 = RS("nome")
           w_codigo               = RS("codigo")
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
        Validate "w_responsavel", "Coordenador", "", "1", "3", "60", "1", "1"
        Validate "w_telefone", "Telenfone", "1", "", "7", "20", "1", "1"
        Validate "w_email", "Email", "", "", "3", "60", "1", "1"
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If O = "A" Then
     BodyOpen "onLoad='document.Form.w_responsavel.focus()';"
  Else
     BodyOpen "onLoad='document.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ShowHTML "<tr><td align=""center"" colspan=3>&nbsp;"
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Tipo</font></td>"
    ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
    ShowHTML "          <td><font size=""1""><b>Opera��es</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se n�o foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7 align=""center""><font size=""2""><b>N�o foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
       If Not (IsNull(RS("cd_ppa_pai")) and IsNull(RS("cd_acao")) and IsNull(RS("cd_unidade")))Then 
          If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
          ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
          ShowHTML "        <td><font size=""1"">A��o PPA</td>"
          ShowHTML "        <td><font size=""1"">" & RS("cd_unidade") & "." & RS("cd_programa") & "." & RS("cd_acao") & " - " & RS("nm_ppa") & "</td>"
          ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
          ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" &w_chave & "&w_programa=" & RS("sq_acao_ppa_pai") & "&w_acao=" & RS("cd_acao")& "&w_unidade=" & RS("cd_unidade") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_chave_aux=" &RS("sq_siw_solicitacao")& """>Coordenador</A>&nbsp"
          ShowHTML "        </td>"
          ShowHTML "      </tr>"
       End If
       If Not IsNull(RS("sq_isprojeto")) Then
          If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
          ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
          ShowHTML "        <td><font size=""1"">Programa interno</td>"
          ShowHTML "        <td><font size=""1"">" & RS("nm_pri") & "</td>"
          ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
          ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" &w_chave& "&w_sq_isprojeto=" & RS("sq_isprojeto") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_chave_aux=" &RS("sq_isprojeto")&  """>Respons�vel</A>&nbsp"
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
    If w_tipo= 1 Then
       w_label = "A��o PPA"
       w_chave_aux = w_chave
    ElseIf w_tipo = 2 Then
       w_label = "Programa interno"
       w_chave_aux = w_sq_isprojeto
    End If
    ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_chave_aux & """>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    If w_tipo = 1 Then
       ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Programa PPA: </b>" &w_codigo_pai& " - " & w_nome_pai & " </b>" 
    End If
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>" &w_label& ": </b>" 
    If Not w_tipo = 2 Then 
       ShowHTML "" &w_codigo& " - " 
    End If 
    ShowHTML "" & w_nome & "</td>"
    If w_tipo = 1 Then
       ShowHTML "      <tr><td><font size=""1""><b><u>C</u>oordenador:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_responsavel"" class=""STI"" SIZE=""50"" MAXLENGTH=""60"" VALUE=""" & w_responsavel & """ title=""Informe o nome do coordenador da a��o.""></td>"
    ElseIf w_tipo = 2 Then
       ShowHTML "      <tr><td><font size=""1""><b>Res<u>p</u>ons�vel:</b><br><input " & w_Disabled & " accesskey=""P"" type=""text"" name=""w_responsavel"" class=""STI"" SIZE=""50"" MAXLENGTH=""60"" VALUE=""" & w_responsavel & """ title=""Informe o nome do respons�vel da a��o.""></td>"
    End If
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>T</u>elefone:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_telefone"" class=""STI"" SIZE=""15"" MAXLENGTH=""14"" VALUE=""" & w_telefone & """ title=""Informe o telefone do coordenador da a��o""></td>"
    ShowHTML "      <tr><td><font size=""1""><b>E<u>m</u>ail:</b><br><input " & w_Disabled & " accesskey=""M"" type=""text"" name=""w_email"" class=""STI"" SIZE=""50"" MAXLENGTH=""60"" VALUE=""" & w_email & """ title=""Informe o e-mail do coordenador da a��o.""></td>"  
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
    ShowHTML " alert('Op��o n�o dispon�vel');"
    ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_chave           = Nothing 
  Set w_chave_aux       = Nothing 
  Set w_nome            = Nothing 
  Set w_responsavel     = Nothing
  Set w_telefone        = Nothing
  Set w_email           = Nothing
  Set w_codigo          = Nothing
  Set w_programa        = Nothing
  Set w_acao            = Nothing
  Set w_unidade         = Nothing
  
  Set w_troca           = Nothing 
  Set i                 = Nothing 
  Set w_erro            = Nothing
End Sub
REM =========================================================================
REM Fim da tela de respons�veis
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de cadastramento da programa��o qualitativa
REM -------------------------------------------------------------------------
Sub ProgramacaoQualitativa
  
  
  Dim w_chave, w_sq_menu
  
  Dim w_problema, w_objetivo, w_publico_alvo, w_estrategia, w_sistematica
  Dim w_finalidade, w_descricao_ppa
  Dim w_metodologia, w_observacao, w_cd_acao, w_ds_acao, w_cd_programa, w_ds_programa, w_cd_unidade
  
  Dim w_troca, w_erro, w_cor, w_readonly

  w_chave           = Request("w_chave")
  w_readonly        = ""
  w_erro            = ""
  w_troca           = Request("w_troca")

  ' Verifica se h� necessidade de recarregar os dados da tela a partir
  ' da pr�pria tela (se for recarga da tela) ou do banco de dados (se n�o for inclus�o)
  If w_troca > "" Then ' Se for recarga da p�gina

     w_chave                   = Request("w_chave") 
     w_sq_menu                 = Request("w_sq_menu") 
     w_problema                = Request("w_problema") 
     w_objetivo                = Request("w_objetivo") 
     w_publico_alvo            = Request("w_publico_alvo") 
     w_estrategia              = Request("w_estrategia") 
     w_sistematica             = Request("w_sistematica") 
     w_metodologia             = Request("w_metodologia") 
     w_observacao              = Request("w_observacao") 
     w_cd_acao                 = Request("w_cd_acao")
  Else
     If InStr("AEV",O) > 0 or w_copia > "" Then
        DB_GetSolicData_IS RS, w_chave, SG
        If RS.RecordCount > 0 Then 
           w_sq_menu                = RS("sq_menu")  
           w_problema               = RS("problema")
           w_finalidade             = RS("finalidade")
           w_objetivo               = RS("objetivo")
           w_descricao_ppa          = RS("descricao_ppa")
           w_publico_alvo           = RS("publico_alvo")
           w_estrategia             = RS("estrategia")
           w_sistematica            = RS("sistematica")
           w_metodologia            = RS("metodologia")
           If RS("justificativa") <> "" Then
              w_observacao          = RS("justificativa")
           Else
              w_observacao          = RS("observacao_ppa")
           End If
           w_cd_acao                = Nvl(RS("cd_acao"),"")
           w_ds_acao                = Nvl(RS("nm_ppa"),"")
           w_cd_programa            = Nvl(RS("cd_programa"),"")
           w_ds_programa            = Nvl(RS("nm_ppa_pai"),"")
           w_cd_unidade             = Nvl(RS("cd_unidade"),"")
           DesconectaBD
        End If
     End If     
  End If  
  Cabecalho
  ShowHTML "<HEAD>"
  ' Monta o c�digo JavaScript necess�rio para valida��o de campos e preenchimento autom�tico de m�scara,
  ' tratando as particularidades de cada servi�o
  ScriptOpen "JavaScript"
  CheckBranco
  FormataData
  FormataDataHora
  FormataValor
  ValidateOpen "Validacao"
  If O = "I" or O = "A" Then
     ShowHTML "  if (theForm.Botao.value == ""Troca"") { return true; }"
     Validate "w_problema", "Justificativa", "1", "", 5, 2000, "1", "1"
     Validate "w_objetivo", "Objetivo espec�fico", "1", "", 5, 2000, "1", "1"
     Validate "w_publico_alvo", "P�blico_alvo", "1", "", 5, 2000, "1", "1"
     Validate "w_estrategia", "Sistem�ticas e estrat�gias", "1", "", 5, 2000, "1", "1"
     Validate "w_sistematica", "Sistem�tica a ser adotada", "1", "", 5, 2000, "1", "1"
     'Validate "w_metodologia", "Metodologias de avalia��o", "1", "", 5, 2000, "1", "1"
     Validate "w_observacao", "Observa��es", "1", "", 5, 4000, "1", "1"
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
     BodyOpen "onLoad='document.Form.w_problema.focus()';"
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
    ShowHTML "      <tr><td valign=""top"" align=""center"" bgcolor=""#D0D0D0""><font size=""2""><b>Programa��o qualitativa</td></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td><font size=1>Os dados deste bloco visam orientar os executores do programa.</font></td></tr>"
    If w_cd_programa > "" and w_cd_acao > "" and w_cd_unidade > "" Then
       ShowHTML "      <tr><td><font size=""1"">Programa Cod� " & w_cd_programa & " - " & w_ds_programa & "</td>"
       ShowHTML "      <tr><td><font size=""1"">A��o Cod� " & w_cd_unidade & "." & w_cd_acao & " - " & w_ds_acao & "</td>"
    End If
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    If w_cd_acao > "" Then
       ShowHTML "      <tr><td valign=""top""><div align=""justify""><font size=""1"">Objetivo:<br><b>" & Nvl(w_finalidade,"---")& "</b></div></td>"    
    End If
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>J</u>ustificativa:</b><br><textarea " & w_Disabled & " accesskey=""P"" name=""w_problema"" class=""STI"" ROWS=5 cols=75 title=""Descri��o do problema que a a��o tem por objetivo enfrentar, abordando o diagn�stico e as causas da situa��o-problema para a qual a a��o foi proposta; alertando quanto �s conseq��ncias da n�o implementa��o da a��o; e informando a exist�ncia de condicionantes favor�veis ou desfavor�veis � a��o."">" & w_problema & "</TEXTAREA></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>O</u>bjetivo espec�fico:</b><br><textarea " & w_Disabled & " accesskey=""O"" name=""w_objetivo"" class=""STI"" ROWS=5 cols=75 title=""Informar, de forma  detalhada e espec�fica, o resultado que se quer alcan�ar, ou seja, a transforma��o ou mudan�a da realidade concreta a qual a a��o se prop�s modificar."">" & w_objetivo & "</TEXTAREA></td>"
    If w_cd_acao > "" Then
       ShowHTML "      <tr><td><div align=""justify""><font size=""1"">Descri��o da a��o:<br><b>" & Nvl(w_descricao_ppa,"---")& "</b></div></td>"
    End If
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>P<u>�</u>blico alvo:</b><br><textarea " & w_Disabled & " accesskey=""U"" name=""w_publico_alvo"" class=""STI"" ROWS=5 cols=75 title=""Especifique os segmentos da sociedade aos quais a a��o se destina e que se beneficiam direta e legitimamente com sua execu��o. S�o os grupos de pessoas, comunidades, institui��es ou setores que ser�o atingidos diretamente pelos resultados da a��o."">" & w_publico_alvo & "</TEXTAREA></td>"
    If w_cd_acao > "" Then
       DB_GetSolicData_IS RS, w_chave, SG
       ShowHTML "      <tr><td valign=""top"" colspan=""2""><div align=""justify""><font size=""1"">Origem da a��o:<br><b>" & Nvl(RS("nm_tipo_inclusao_acao"),"---")& "</b></div></td>"
       ShowHTML "      <tr><td valign=""top"" colspan=""2""><div align=""justify""><font size=""1"">Base legal:<br><b>" & Nvl(RS("base_legal"),"---")& "</b></div></td>"
       ShowHTML "      <tr><td valign=""top"" colspan=""2""><font size=""1"">Forma de implementa��o:<br><b>" 
       If cDbl(RS("cd_tipo_acao")) = 1 or cDbl(RS("cd_tipo_acao")) = 2 Then 
          If RS("direta") = "S" Then
             ShowHTML "       direta"
          End If
          If RS("descentralizada") = "S" Then
             ShowHTML "       descentralizada"
          End If
          If RS("linha_credito") = "S" Then
             ShowHTML "       linha de cr�dito"
          End If
       ElseIf cDbl(RS("cd_tipo_acao")) = 4 Then
          If RS("transf_obrigatoria") = "S" Then
             ShowHTML "       transfer�ncia obrigat�ria"
          End If
          If RS("transf_voluntaria") = "S" Then
             ShowHTML "       transfer�ncia volunt�ria"
          End If
          If RS("transf_outras") = "S" Then
             ShowHTML "        outras"
          End If
       End If
       ShowHTML "            </b></td>"
       ShowHTML "      <tr><td valign=""top"" colspan=""2""><div align=""justify""><font size=""1"">Detalhamento da implementa��o:<br><b>" & Nvl(RS("detalhamento"),"---")& "</b></div></td>"
       DesconectaBD
    End If
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Sistem�tica e <u>e</u>strat�gias a serem adotadas para o monitoramento da a��o:</b><br><textarea " & w_Disabled & " accesskey=""E"" name=""w_estrategia"" class=""STI"" ROWS=5 cols=75 title=""Descreva a sistem�tica e as estrat�gias que ser�o adotadas para o monitoramento da a��o, informando, inclusive as ferramentas que ser�o utilizadas."">" & w_estrategia & "</TEXTAREA></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>S</u>istem�tica e metodologias a serem adotadas para avalia��o da a��o:</b><br><textarea " & w_Disabled & " accesskey=""S"" name=""w_sistematica"" class=""STI"" ROWS=5 cols=75 title=""Descreva a sistem�tica e as metodologias que ser�o adotadas para a avalia��o da a��o, informando, inclusive as ferramentas que ser�o utilizadas."">" & w_sistematica & "</TEXTAREA></td>"
    'ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>M</u>etodologias de avalia��o a serem utilizadas:</b><br><textarea " & w_Disabled & " accesskey=""M"" name=""w_metodologia"" class=""STI"" ROWS=5 cols=75 title=""Descreva as metodologias de avalia��o a serem utilizadas."">" & w_metodologia & "</TEXTAREA></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>O<u>b</u>serva��es:</b><br><textarea " & w_Disabled & " accesskey=""B"" name=""w_observacao"" class=""STI"" ROWS=5 cols=75 title=""Informe as observa��es pertinentes (campo n�o obrigat�rio)."">" & w_observacao & "</TEXTAREA></td>"
    ' Verifica se poder� ser feito o envio da solicita��o, a partir do resultado da valida��o
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
    ShowHTML " alert('Op��o n�o dispon�vel');"
    ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_chave                   = Nothing 
  Set w_sq_menu                 = Nothing 
  Set w_problema                = Nothing 
  Set w_objetivo                = Nothing
  Set w_publico_alvo            = Nothing 
  Set w_estrategia              = Nothing 
  Set w_sistematica             = Nothing 
  Set w_metodologia             = Nothing   
  Set w_observacao              = Nothing
  
  Set w_troca                   = Nothing 
  Set w_erro                    = Nothing 
  Set w_cor                     = Nothing
  Set w_readonly                = Nothing  

End Sub
REM =========================================================================
REM Fim da rotina de programa��o qualitativa
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de etapas da a��o
REM -------------------------------------------------------------------------
Sub Metas
  Dim w_chave, w_chave_aux, w_titulo, w_ordem, w_descricao
  Dim w_inicio, w_fim, w_inicio_real, w_fim_real, w_perc_conclusao, w_orcamento
  Dim w_sq_pessoa, w_sq_unidade, w_quantidade, w_cumulativa
  Dim w_unidade_medida, w_programada, w_cd_subacao
  Dim w_cron_ini_1, w_cron_ini_2, w_cron_ini_3, w_cron_ini_4, w_cron_ini_5, w_cron_ini_6
  Dim w_cron_ini_7, w_cron_ini_8, w_cron_ini_9, w_cron_ini_10, w_cron_ini_11, w_cron_ini_12
  Dim w_previsto_acao_1, w_previsto_acao_2, w_previsto_acao_3, w_previsto_acao_4, w_previsto_acao_5, w_previsto_acao_6
  Dim w_previsto_acao_7, w_previsto_acao_8, w_previsto_acao_9, w_previsto_acao_10, w_previsto_acao_11, w_previsto_acao_12

  Dim w_troca, i, w_texto
  
  w_Chave           = Request("w_Chave")
  w_chave_aux       = Request("w_chave_aux")
  w_troca           = Request("w_troca")
  
  If w_troca > "" Then ' Se for recarga da p�gina
     w_ordem                = Request("w_ordem")
     w_titulo               = Request("w_titulo")
     w_descricao            = Request("w_descricao")    
     w_inicio               = Request("w_inicio")    
     w_fim                  = Request("w_fim")    
     w_inicio_real          = Request("w_inicio_real")    
     w_fim_real             = Request("w_fim_real")    
     w_perc_conclusao       = Request("w_perc_conclusao")    
     w_orcamento            = Request("w_orcamento")    
     w_unidade_medida       = Request("w_unidade_medida")    
     w_quantidade           = Request("w_quantidade")
     w_cumulativa           = Request("w_cumulativa")
     w_programada           = Request("w_programada")
     w_cd_subacao           = Request("w_cd_subacao")
     w_cron_ini_1           = Request("w_cron_ini_1")
     w_cron_ini_2           = Request("w_cron_ini_2")
     w_cron_ini_3           = Request("w_cron_ini_3")
     w_cron_ini_4           = Request("w_cron_ini_4")
     w_cron_ini_5           = Request("w_cron_ini_5")
     w_cron_ini_6           = Request("w_cron_ini_6")
     w_cron_ini_7           = Request("w_cron_ini_7")
     w_cron_ini_8           = Request("w_cron_ini_8")
     w_cron_ini_9           = Request("w_cron_ini_9")
     w_cron_ini_10          = Request("w_cron_ini_10")
     w_cron_ini_11          = Request("w_cron_ini_11")
     w_cron_ini_12          = Request("w_cron_ini_12")      
     w_previsto_acao_1      = Request("w_previsto_acao_1")
     w_previsto_acao_2      = Request("w_previsto_acao_2")
     w_previsto_acao_3      = Request("w_previsto_acao_3")
     w_previsto_acao_4      = Request("w_previsto_acao_4")
     w_previsto_acao_5      = Request("w_previsto_acao_5")
     w_previsto_acao_6      = Request("w_previsto_acao_6")
     w_previsto_acao_7      = Request("w_previsto_acao_7")
     w_previsto_acao_8      = Request("w_previsto_acao_8")
     w_previsto_acao_9      = Request("w_previsto_acao_9")
     w_previsto_acao_10     = Request("w_previsto_acao_10")
     w_previsto_acao_11     = Request("w_previsto_acao_11")
     w_previsto_acao_12     = Request("w_previsto_acao_12")         
  ElseIf O = "L" Then
     ' Recupera todos os registros para a listagem
     DB_GetSolicMeta_IS RS, w_chave, null, "LISTA", null, null, null, null, null, null, null, null, null
     RS.Sort = "ordem"
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
     ' Recupera os dados do endere�o informado
     DB_GetSolicMeta_IS RS, w_chave, w_chave_aux, "REGISTRO", null, null, null, null, null, null, null, null, null
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
     w_unidade_medida       = RS("unidade_medida")    
     w_quantidade           = FormatNumber(cDbl(Nvl(RS("quantidade"),0)),2)
     w_cumulativa           = RS("cumulativa")
     w_programada           = RS("programada")
     w_cd_subacao           = RS("cd_subacao")
     w_cron_ini_1           = Nvl(RS("cron_ini_mes_1"),"")
     w_cron_ini_2           = Nvl(RS("cron_ini_mes_2"),"")
     w_cron_ini_3           = Nvl(RS("cron_ini_mes_3"),"")
     w_cron_ini_4           = Nvl(RS("cron_ini_mes_4"),"")
     w_cron_ini_5           = Nvl(RS("cron_ini_mes_5"),"")
     w_cron_ini_6           = Nvl(RS("cron_ini_mes_6"),"")
     w_cron_ini_7           = Nvl(RS("cron_ini_mes_7"),"")
     w_cron_ini_8           = Nvl(RS("cron_ini_mes_8"),"")
     w_cron_ini_9           = Nvl(RS("cron_ini_mes_9"),"")
     w_cron_ini_10          = Nvl(RS("cron_ini_mes_10"),"")
     w_cron_ini_11          = Nvl(RS("cron_ini_mes_11"),"")
     w_cron_ini_12          = Nvl(RS("cron_ini_mes_12"),"")   
     w_previsto_acao_1      = FormatNumber(cDbl(Nvl(RS("valor_ini_1"),0)),2)
     w_previsto_acao_2      = FormatNumber(cDbl(Nvl(RS("valor_ini_2"),0)),2)
     w_previsto_acao_3      = FormatNumber(cDbl(Nvl(RS("valor_ini_3"),0)),2)
     w_previsto_acao_4      = FormatNumber(cDbl(Nvl(RS("valor_ini_4"),0)),2)
     w_previsto_acao_5      = FormatNumber(cDbl(Nvl(RS("valor_ini_5"),0)),2)
     w_previsto_acao_6      = FormatNumber(cDbl(Nvl(RS("valor_ini_6"),0)),2)
     w_previsto_acao_7      = FormatNumber(cDbl(Nvl(RS("valor_ini_7"),0)),2)
     w_previsto_acao_8      = FormatNumber(cDbl(Nvl(RS("valor_ini_8"),0)),2)
     w_previsto_acao_9      = FormatNumber(cDbl(Nvl(RS("valor_ini_9"),0)),2)
     w_previsto_acao_10     = FormatNumber(cDbl(Nvl(RS("valor_ini_10"),0)),2)
     w_previsto_acao_11     = FormatNumber(cDbl(Nvl(RS("valor_ini_11"),0)),2)
     w_previsto_acao_12     = FormatNumber(cDbl(Nvl(RS("valor_ini_12"),0)),2)
     DesconectaBD
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
        'Validate "w_ordem", "Tipo de vis�o", "SELECT", "1", "1", "10", "", "1"
        If Nvl(w_cd_subacao,"") = "" Then
           Validate "w_titulo", "Produto", "", "1", "2", "100", "1", "1"
           Validate "w_unidade_medida", "Unidade de medida", "", "1", "2", "100", "1", "1"
           Validate "w_quantidade", "Quantitativo programado", "VALOR", "1", "1", "18", "", "0123456789,."
           CompValor "w_quantidade", "Quantitativo programado", ">", "0,00", "zero"
        End If
        Validate "w_descricao", "Especifica��o do produto", "1", "1", "2", "2000", "1", "1"
        Validate "w_ordem", "Ordem", "1", "1", "1", "3", "", "0123456789"
        Validate "w_inicio", "In�cio previsto", "DATA", "1", "10", "10", "", "0123456789/"
        Validate "w_fim", "Fim previsto", "DATA", "1", "10", "10", "", "0123456789/"
        CompData "w_inicio", "In�cio previsto", "<=", "w_fim", "Fim previsto"
        If Nvl(w_cd_subacao,"") = "" Then
           Validate "w_cron_ini_1", "Quantitativo previsto de Janeiro", "", "", "1", "10", "", "0123456789"
           Validate "w_cron_ini_2", "Quantitativo previsto de Fevereiro", "", "", "1", "10", "", "0123456789"
           Validate "w_cron_ini_3", "Quantitativo previsto de Mar�o", "", "", "1", "10", "", "0123456789"
           Validate "w_cron_ini_4", "Quantitativo previsto de Abril", "", "", "1", "10", "", "0123456789"
           Validate "w_cron_ini_5", "Quantitativo previsto de Maio", "", "", "1", "10", "", "0123456789"
           Validate "w_cron_ini_6", "Quantitativo previsto de Junho", "", "", "1", "10", "", "0123456789"
           Validate "w_cron_ini_7", "Quantitativo previsto de Julho", "", "", "1", "10", "", "0123456789"
           Validate "w_cron_ini_8", "Quantitativo previsto de Agosto", "", "", "1", "10", "", "0123456789"
           Validate "w_cron_ini_9", "Quantitativo previsto de Setembro", "", "", "1", "10", "", "0123456789"
           Validate "w_cron_ini_10", "Quantitativo previsto de Outubro", "", "", "1", "10", "", "0123456789"
           Validate "w_cron_ini_11", "Quantitativo previsto de Novembro", "", "", "1", "10", "", "0123456789"
           Validate "w_cron_ini_12", "Quantitativo previsto de Dezembro", "", "", "1", "10", "", "0123456789"
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
     BodyOpen "onLoad='document.Form.w_titulo.focus()';"
  Else
     BodyOpen "onLoad='document.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    DB_GetSolicData_IS RS1, w_chave, SG
    If Nvl(RS1("cd_programa"),"") > "" and Nvl(RS1("cd_acao"),"") > "" and Nvl(RS1("cd_unidade"),"") > "" Then
       ShowHTML "      <tr><td colspan=""2""><font size=""1"">Programa Cod� " & RS1("cd_programa") & " - " & RS1("nm_ppa_pai") & "</td>"
       ShowHTML "      <tr><td colspan=""2""><font size=""1"">A��o Cod� " & RS1("cd_unidade") & "." & RS1("cd_acao") & " - " & RS1("nm_ppa") & "</td>"
    End If
    RS1.Close
    ' Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
    ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Produtos</font></td>"
    ShowHTML "          <td><font size=""1""><b>PPA</font></td>"
    ShowHTML "          <td><font size=""1""><b>Data conclus�o</font></td>"
    ShowHTML "          <td><font size=""1""><b>Executado</font></td>"
    ShowHTML "          <td><font size=""1""><b>Opera��es</font></td>"
    ShowHTML "        </tr>"
    ' Recupera as etapas principais
    DB_GetSolicMeta_IS RS, w_chave, null, "LSTNULL", null, null, null, null, null, null, null, null, null
    RS.Sort = "ordem"
    If RS.EOF Then ' Se n�o foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=9 align=""center""><font size=""2""><b>N�o foram encontrados registros.</b></td></tr>"
    Else
      While Not RS.EOF
        ShowHTML Metalinha(w_chave, Rs("sq_meta"), Rs("titulo"), Rs("nm_resp"), Rs("sg_setor"), Rs("inicio_previsto"), Rs("fim_previsto"), Rs("perc_conclusao"), null, "<b>", "S", "PROJETO", RS("cd_subacao"))        
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
    ShowHTML "<INPUT type=""hidden"" name=""w_orcamento"" value=""0,00"">"
    ShowHTML "<INPUT type=""hidden"" name=""w_perc_conclusao"" value=""0"">"
    If w_cd_subacao > "" Then
       ShowHTML "<INPUT type=""hidden"" name=""w_titulo"" value=""" & w_titulo & """>"
       ShowHTML "<INPUT type=""hidden"" name=""w_unidade_medida"" value=""" & w_unidade_medida & """>"
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
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    If w_cd_subacao > "" Then
       w_Disabled = "DISABLED"
    End If
    ShowHTML "      <tr><td><font size=""1""><b>Prod<u>u</u>to:</b><br><input " & w_Disabled & " accesskey=""U"" type=""text"" name=""w_titulo"" class=""STI"" SIZE=""90"" MAXLENGTH=""90"" VALUE=""" & w_titulo & """ title=""Informe o bem ou servi�o que resulta da a��o, destinado ao p�blico-alvo ou o investimento para a produ��o deste bem ou servi�o. Em situa��es especiais, expressa a quantidade de benefici�rios atendidos pela a��o.""></td>"
    If w_cd_subacao > "" Then
       w_Disabled = ""
    End If    
    ShowHTML "     <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
    MontaRadioNS "<b>Meta cumulativa?</b>", w_cumulativa, "w_cumulativa"
    MontaRadioNS "<b>Meta do PNPIR?</b>", w_programada, "w_programada"
    ShowHTML "         </table></td></tr>"
    If w_cd_subacao > "" Then
       w_Disabled = "DISABLED"
    End If
    ShowHTML "     <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
    ShowHTML "         <tr><td align=""left""><font size=""1""><b><u>Q</u>uantitativo:<br><input accesskey=""Q"" type=""text"" name=""w_quantidade"" class=""STI"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_quantidade & """ " & w_Disabled & " onKeyDown=""FormataValor(this,18,2,event);"" title=""Indicar a quantidade da meta da a��o programada para determinado per�odo de tempo.""></td>"    
    ShowHTML "             <td align=""left""><font size=""1""><b><u>U</u>nidade de medida:<br><INPUT ACCESSKEY=""U"" TYPE=""TEXT"" CLASS=""STI"" NAME=""w_unidade_medida"" SIZE=15 MAXLENGTH=30 VALUE=""" & w_unidade_medida & """ " & w_Disabled & " title=""Informar o padr�o escolhido para mensura��o da rela��o adotada como meta.""></td>"
    ShowHTML "         </table></td></tr>"

    ShowHTML "      <tr><td><font size=""1""><b><u>E</u>specifica��o do produto:</b><br><textarea  accesskey=""E"" name=""w_descricao"" class=""STI"" ROWS=5 cols=75 title=""Descrever as caracter�sticas do produto acabado visando sua melhor identifica��o."">" & w_descricao & "</TEXTAREA></td>"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
    ShowHTML "              <td align=""left""><font size=""1""><b><u>O</u>rdem:<br><INPUT ACCESSKEY=""O"" TYPE=""TEXT"" CLASS=""STI"" NAME=""w_ordem"" SIZE=3 MAXLENGTH=3 VALUE=""" & w_ordem & """></td>"
    ShowHTML "              <td><font size=""1""><b>Previs�o in�<u>c</u>io:</b><br><input accesskey=""C"" type=""text"" name=""w_inicio"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & FormataDataEdicao(Nvl(w_inicio,Date())) & """ onKeyDown=""FormataData(this,event);"" title=""Data prevista para in�cio da meta."" title=""Usar formato dd/mm/aaaa""></td>"
    ShowHTML "              <td><font size=""1""><b>Previs�o <u>t</u>�rmino:</b><br><input accesskey=""T"" type=""text"" name=""w_fim"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & FormataDataEdicao(w_fim) & """ onKeyDown=""FormataData(this,event);"" title=""Data prevista para t�rmino da meta."" title=""Usar formato dd/mm/aaaa""></td>"
    ShowHTML "          </table>"
    If w_cd_subacao > "" Then
       w_Disabled = ""
       ShowHTML "     <tr><td valign=""top"" colspan=""1"">"
       ShowHTML "       <table border=0 width=""40%"" cellspacing=0>"
       ShowHTML "         <tr><td>&nbsp<td title=""Informe o meta programada m�s a m�s, nos campos abaixo.""><font size=""1""><br><b>Quantitativo programado</b></td>"
       ShowHTML "             <td><font size=""1""><br><b>Financeiro programado</b></td>"
       ShowHTML "         <tr><td width=""4%"" align=""right""><font size=""1""><b>Janeiro:"
       ShowHTML "             <td width=""8%""><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_cron_ini_1"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_cron_ini_1 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td width=""5%"" align=""right""><font size=""1"">"& Nvl(w_previsto_acao_1,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Fevereiro:"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_cron_ini_2"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_cron_ini_2 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td align=""right""><font size=""1"">"& Nvl(w_previsto_acao_2,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Mar�o:"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_cron_ini_3"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_cron_ini_3 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td align=""right""><font size=""1"">"& Nvl(w_previsto_acao_3,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Abril:"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_cron_ini_4"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_cron_ini_4 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td align=""right""><font size=""1"">"& Nvl(w_previsto_acao_4,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Maio:"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_cron_ini_5"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_cron_ini_5 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td align=""right""><font size=""1"">"& Nvl(w_previsto_acao_5,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Junho:"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_cron_ini_6"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_cron_ini_6 & """ " & w_Disabled & " ></td>"
       ShowHTML "             <td align=""right""><font size=""1"">"& Nvl(w_previsto_acao_6,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Julho:"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_cron_ini_7"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_cron_ini_7 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td align=""right""><font size=""1"">"& Nvl(w_previsto_acao_7,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Agosto:"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_cron_ini_8"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_cron_ini_8 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td align=""right""><font size=""1"">"& Nvl(w_previsto_acao_8,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Setembro:"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_cron_ini_9"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_cron_ini_9 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td align=""right""><font size=""1"">"& Nvl(w_previsto_acao_9,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Outubro:"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_cron_ini_10"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_cron_ini_10 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td align=""right""><font size=""1"">"& Nvl(w_previsto_acao_10,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Novembro:"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_cron_ini_11"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_cron_ini_11 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td align=""right""><font size=""1"">"& Nvl(w_previsto_acao_11,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Dezembro:"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_cron_ini_12"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_cron_ini_12 & """ " & w_Disabled & " ></td>"
       ShowHTML "             <td align=""right""><font size=""1"">"& Nvl(w_previsto_acao_12,"---") & "</td>"
       ShowHTML "       </table>"    
    End If
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
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
    ShowHTML " alert('Op��o n�o dispon�vel');"
    ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_inicio              = Nothing 
  Set w_fim                 = Nothing 
  Set w_perc_conclusao      = Nothing 
  Set w_orcamento           = Nothing
  Set w_programada          = Nothing
  Set w_unidade_medida      = Nothing
  Set w_cumulativa          = Nothing
  Set w_quantidade          = Nothing
  Set w_sq_pessoa           = Nothing
  Set w_sq_unidade          = Nothing
  Set w_cd_subacao          = Nothing

  Set w_chave               = Nothing 
  Set w_chave_aux           = Nothing 
  Set w_titulo              = Nothing 
  Set w_ordem               = Nothing 
  Set w_descricao           = Nothing 
  
  Set w_troca               = Nothing 
  Set i                     = Nothing 
  Set w_texto               = Nothing
End Sub
REM =========================================================================
REM Fim da tela de metas da a��o
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de atualiza��o das metas da a��o
REM -------------------------------------------------------------------------
Sub AtualizaMeta
  Dim w_chave, w_chave_aux, w_titulo, w_ordem, w_descricao
  Dim w_inicio, w_fim, w_inicio_real, w_fim_real, w_perc_conclusao, w_orcamento
  Dim w_ultima_atualizacao, w_sq_pessoa_atualizacao, w_situacao_atual
  Dim w_programada, w_cumulativa, w_quantidade, w_unidade_medida, w_nm_programada, w_nm_cumulativa
  Dim w_exequivel, w_justificativa_inex, w_outras_medidas
  Dim w_realizado, w_revisado(), w_referencia()
  Dim w_realizado_1, w_realizado_2, w_realizado_3, w_realizado_4, w_realizado_5, w_realizado_6
  Dim w_realizado_7, w_realizado_8, w_realizado_9, w_realizado_10, w_realizado_11, w_realizado_12
  Dim w_revisado_1, w_revisado_2, w_revisado_3, w_revisado_4, w_revisado_5, w_revisado_6
  Dim w_cron_ini_7, w_cron_ini_8, w_cron_ini_9, w_cron_ini_10, w_cron_ini_11, w_cron_ini_12  
  Dim w_cron_ini_1, w_cron_ini_2, w_cron_ini_3, w_cron_ini_4, w_cron_ini_5, w_cron_ini_6
  Dim w_revisado_7, w_revisado_8, w_revisado_9, w_revisado_10, w_revisado_11, w_revisado_12    
  Dim w_referencia_1, w_referencia_2, w_referencia_3, w_referencia_4, w_referencia_5, w_referencia_6
  Dim w_referencia_7, w_referencia_8, w_referencia_9, w_referencia_10, w_referencia_11, w_referencia_12
  Dim w_sq_pessoa, w_sq_unidade, w_vincula_atividade, w_cabecalho, w_fase, w_p2, w_fases
  Dim w_tipo, w_cd_subacao, w_real_acao_1, w_real_acao_2, w_real_acao_3, w_real_acao_4, w_real_acao_5
  Dim w_real_acao_6, w_real_acao_7, w_real_acao_8, w_real_acao_9, w_real_acao_10, w_real_acao_11, w_real_acao_12
  Dim w_aprovado_acao, w_autorizado_acao, w_realizado_acao, w_desc_subacao, w_desc_acao
  
  Dim w_troca, i, w_texto
  
  w_Chave           = Request("w_Chave")
  w_chave_aux       = Request("w_chave_aux")
  w_troca           = Request("w_troca")
  w_tipo            = uCase(trim(Request("w_tipo")))
  
  DB_GetSolicData_IS RS, w_chave, "ISACGERAL"

  ' Configura uma vari�vel para testar se as metas podem ser atualizadas.
  ' A��es conclu�das ou canceladas n�o podem ter permitir a atualiza��o.
  w_desc_acao = Nvl(RS("titulo"),"")
  If Nvl(RS("sg_tramite"),"--") = "EE" Then
     w_fase = "S"
  Else
     w_fase = "N"
  End If
  DesconectaBD
 
  If w_troca > "" Then ' Se for recarga da p�gina
     w_ordem                = Request("w_ordem")
     w_titulo               = Request("w_titulo")
     w_descricao            = Request("w_descricao")
     w_desc_subacao         = Request("w_desc_subacao") 
     w_desc_subacao         = Request("w_desc_acao")
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
     w_aprovado_acao        = Request("w_aprovado_acao")
     w_autorizado_acao      = Request("w_autorizado_acao")
     w_realizado_acao       = Request("w_realizado_acao")
     for i = 0 to i = 12 
        w_realizado[i]     = Request("w_realizado[i]")
        w_revisado[i]      = Request("w_revisado[i]")
     next
  ElseIf O = "L" Then
     ' Recupera todos os registros para a listagem
     DB_GetSolicMeta_IS RS, w_chave, null, "LISTA", null, null, null, null, null, null, null, null, null
     RS.Sort = "ordem"

  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
     ' Recupera os dados do endere�o informado
     DB_GetSolicMeta_IS RS, w_chave, w_chave_aux, "REGISTRO", null, null, null, null, null, null, null, null, null
     w_titulo               = RS("titulo")
     w_ordem                = RS("ordem")
     w_descricao            = RS("descricao")
     w_desc_subacao         = RS("descricao_subacao")
     w_inicio               = RS("inicio_previsto")
     w_fim                  = RS("fim_previsto")
     w_inicio_real          = RS("inicio_real")
     w_fim_real             = RS("fim_real")
     w_perc_conclusao       = RS("perc_conclusao")
     w_orcamento            = RS("orcamento")
     w_sq_pessoa            = RS("sq_pessoa")
     w_sq_unidade           = RS("sq_unidade")
     w_ultima_atualizacao   = RS("ultima_atualizacao")
     w_situacao_atual       = RS("situacao_atual")
     w_unidade_medida       = RS("unidade_medida")    
     w_quantidade           = FormatNumber(cDbl(Nvl(RS("quantidade"),0)),2)
     w_cumulativa           = RS("cumulativa")
     w_programada           = RS("programada")
     w_exequivel            = RS("exequivel")
     w_justificativa_inex   = RS("justificativa_inexequivel")
     w_outras_medidas       = RS("outras_medidas")
     w_nm_programada        = RS("nm_programada")
     w_nm_cumulativa        = RS("nm_cumulativa")
     w_cd_subacao           = RS("cd_subacao")
     w_real_acao_1          = FormatNumber(cDbl(Nvl(RS("real_mes_1"),0)),2)
     w_real_acao_2          = FormatNumber(cDbl(Nvl(RS("real_mes_2"),0)),2)
     w_real_acao_3          = FormatNumber(cDbl(Nvl(RS("real_mes_3"),0)),2)
     w_real_acao_4          = FormatNumber(cDbl(Nvl(RS("real_mes_4"),0)),2)
     w_real_acao_5          = FormatNumber(cDbl(Nvl(RS("real_mes_5"),0)),2)
     w_real_acao_6          = FormatNumber(cDbl(Nvl(RS("real_mes_6"),0)),2)
     w_real_acao_7          = FormatNumber(cDbl(Nvl(RS("real_mes_7"),0)),2)
     w_real_acao_8          = FormatNumber(cDbl(Nvl(RS("real_mes_8"),0)),2)
     w_real_acao_9          = FormatNumber(cDbl(Nvl(RS("real_mes_9"),0)),2)
     w_real_acao_10         = FormatNumber(cDbl(Nvl(RS("real_mes_10"),0)),2)
     w_real_acao_11         = FormatNumber(cDbl(Nvl(RS("real_mes_11"),0)),2)
     w_real_acao_12         = FormatNumber(cDbl(Nvl(RS("real_mes_12"),0)),2)
     w_cron_ini_1           = RS("cron_ini_mes_1")
     w_cron_ini_2           = RS("cron_ini_mes_2")
     w_cron_ini_3           = RS("cron_ini_mes_3")
     w_cron_ini_4           = RS("cron_ini_mes_4")
     w_cron_ini_5           = RS("cron_ini_mes_5")
     w_cron_ini_6           = RS("cron_ini_mes_6")
     w_cron_ini_7           = RS("cron_ini_mes_7")
     w_cron_ini_8           = RS("cron_ini_mes_8")
     w_cron_ini_9           = RS("cron_ini_mes_9")
     w_cron_ini_10          = RS("cron_ini_mes_10")
     w_cron_ini_11          = RS("cron_ini_mes_11")
     w_cron_ini_12          = RS("cron_ini_mes_12")
     w_aprovado_acao        = FormatNumber(cDbl(Nvl(RS("previsao_ano"),0)),2)
     w_autorizado_acao      = FormatNumber(cDbl(Nvl(RS("atual_ano"),0)),2)
     w_realizado_acao       = FormatNumber(cDbl(Nvl(RS("real_ano"),0)),2)
     If RS("cd_acao") > "" Then
        w_cabecalho = "      <tr><td valign=""top"" colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font  size=""2""><b>A��o: " & w_desc_acao & " (" & RS("cd_unidade") & "." & RS("cd_programa") & "." & RS("cd_acao") & "." & RS("cd_subacao") & ")</td></tr>"
     Else
        w_cabecalho = "      <tr><td valign=""top"" colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font  size=""2""><b>A��o: " & w_desc_acao & " (" & w_chave & ")</td></tr>"
     End If
     DesconectaBD
     DB_GetMetaMensal_IS RS, w_chave_aux
     If Not RS.EOF Then
        While Not RS.EOF 
           Select Case Month(cDate(RS("referencia")))
              Case  1 w_realizado_1  = RS("realizado")
                      w_revisado_1   = RS("revisado")
              Case  2 w_realizado_2  = RS("realizado")
                      w_revisado_2   = RS("revisado")
              Case  3 w_realizado_3  = RS("realizado")
                      w_revisado_3   = RS("revisado")
              Case  4 w_realizado_4  = RS("realizado")
                      w_revisado_4   = RS("revisado")
              Case  5 w_realizado_5  = RS("realizado")
                      w_revisado_5   = RS("revisado")
              Case  6 w_realizado_6  = RS("realizado")
                      w_revisado_6   = RS("revisado")
              Case  7 w_realizado_7  = RS("realizado")
                      w_revisado_7   = RS("revisado")
              Case  8 w_realizado_8  = RS("realizado")
                      w_revisado_8   = RS("revisado")
              Case  9 w_realizado_9  = RS("realizado")
                      w_revisado_9   = RS("revisado")
              Case 10 w_realizado_10 = RS("realizado")
                      w_revisado_10  = RS("revisado")
              Case 11 w_realizado_11 = RS("realizado")
                      w_revisado_11  = RS("revisado")
              Case 12 w_realizado_12 = RS("realizado")
                      w_revisado_12  = RS("revisado")
           End Select
           RS.MoveNext
        Wend
     End If
     DesconectaBD
  ElseIf Nvl(w_sq_pessoa,"") = "" Then
     ' Se a meta n�o tiver respons�vel atribu�do, recupera o respons�vel pela a��o
     DB_GetSolicData_IS RS, w_chave, "ISACGERAL"
     w_sq_pessoa            = RS("solicitante")
     w_sq_unidade           = RS("sq_unidade_resp")
  End If
  If w_tipo = "WORD" Then
      Response.ContentType = "application/msword"
  Else
     Cabecalho
  End If
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>" & conSgSistema & " - Meta da a��o</TITLE>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     FormataValor
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_realizado_1", "Quantitativo realizado de Janeiro", "", "", "1", "10", "", "0123456789"
        Validate "w_realizado_2", "Quantitativo realizado de Fevereiro", "", "", "1", "10", "", "0123456789"
        Validate "w_realizado_3", "Quantitativo realizado de Mar�o", "", "", "1", "10", "", "0123456789"
        Validate "w_realizado_4", "Quantitativo realizado de Abril", "", "", "1", "10", "", "0123456789"
        Validate "w_realizado_5", "Quantitativo realizado de Maio", "", "", "1", "10", "", "0123456789"
        Validate "w_realizado_6", "Quantitativo realizado de Junho", "", "", "1", "10", "", "0123456789"
        Validate "w_realizado_7", "Quantitativo realizado de Julho", "", "", "1", "10", "", "0123456789"
        Validate "w_realizado_8", "Quantitativo realizado de Agosto", "", "", "1", "10", "", "0123456789"
        Validate "w_realizado_9", "Quantitativo realizado de Setembro", "", "", "1", "10", "", "0123456789"
        Validate "w_realizado_10", "Quantitativo realizado de Outubro", "", "", "1", "10", "", "0123456789"
        Validate "w_realizado_11", "Quantitativo realizado de Novembro", "", "", "1", "10", "", "0123456789"
        Validate "w_realizado_12", "Quantitativo realizado de Dezembro", "", "", "1", "10", "", "0123456789"
        Validate "w_revisado_1", "Quantitativo revisado de Janeiro", "", "", "1", "10", "", "0123456789"
        Validate "w_revisado_2", "Quantitativo revisado de Fevereiro", "", "", "1", "10", "", "0123456789"
        Validate "w_revisado_3", "Quantitativo revisado de Mar�o", "", "", "1", "10", "", "0123456789"
        Validate "w_revisado_4", "Quantitativo revisado de Abril", "", "", "1", "10", "", "0123456789"
        Validate "w_revisado_5", "Quantitativo revisado de Maio", "", "", "1", "10", "", "0123456789"
        Validate "w_revisado_6", "Quantitativo revisado de Junho", "", "", "1", "10", "", "0123456789"
        Validate "w_revisado_7", "Quantitativo revisado de Julho", "", "", "1", "10", "", "0123456789"
        Validate "w_revisado_8", "Quantitativo revisado de Agosto", "", "", "1", "10", "", "0123456789"
        Validate "w_revisado_9", "Quantitativo revisado de Setembro", "", "", "1", "10", "", "0123456789"
        Validate "w_revisado_10", "Quantitativo revisado de Outubro", "", "", "1", "10", "", "0123456789"
        Validate "w_revisado_11", "Quantitativo revisado de Novembro", "", "", "1", "10", "", "0123456789"
        Validate "w_revisado_12", "Quantitativo revisado de Dezembro", "", "", "1", "10", "", "0123456789"        
        Validate "w_situacao_atual", "Situa��o atual", "", "", "2", "4000", "1", "1"
        ShowHTML "  if (theForm.w_exequivel[1].checked && theForm.w_justificativa_inex.value == '') {"
        ShowHTML "     alert ('Justifique porque a meta n�o ser� cumprida!');"
        ShowHTML "     theForm.w_justificativa_inex.focus();"
        ShowHTML "     return false;"
        ShowHTML "  } else { if (theForm.w_exequivel[0].checked) "
        ShowHTML "     theForm.w_justificativa_inex.value = '';"
        ShowHTML "   }"
        ShowHTML "  if (theForm.w_exequivel[1].checked && theForm.w_outras_medidas.value == '') {"
        ShowHTML "     alert ('Indique quais s�o as medidas necess�rias para o cumprimento da meta!');"
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
  ShowHTML "<div align=center><center>"
  ShowHTML "  <table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  ShowHTML      w_cabecalho
  If w_tipo <> "WORD" and O = "V" Then
     ShowHTML "<tr><td align=""right""colspan=""2"">"
     ShowHTML "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Imprimir"" SRC=""images/impressora.jpg"" onClick=""window.print();"">"
     ShowHTML "&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Gerar word"" SRC=""images/word.gif"" onClick=""window.open('Acao.asp?par=AtualizaMeta&O=V&w_chave=" & w_chave & "&w_chave_aux=" & w_chave_aux & "&w_tipo=WORD&P1=10&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "','MetaWord','width=600, height=350, top=65, left=65, menubar=yes, scrollbars=yes, resizable=yes, status=no');"">"
     ShowHTML "</td></tr>"
  End If
  If O = "L" Then
    ' Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
    ShowHTML "  <tr><td colspan=""2""><font size=""3""></td>"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount & "</td></tr>"
    ShowHTML "  <tr><td align=""center"" colspan=""3"">"
    ShowHTML "      <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Produtos</font></td>"
    ShowHTML "          <td><font size=""1""><b>PPA</font></td>"
    ShowHTML "          <td><font size=""1""><b>Data conclus�o</font></td>"
    ShowHTML "          <td><font size=""1""><b>Executado</font></td>"
    ShowHTML "          <td><font size=""1""><b>Opera��es</font></td>"
    ShowHTML "        </tr>"
    ' Recupera as metas
    DB_GetSolicMeta_IS RS, w_chave, null, "LSTNULL", null, null, null, null, null, null, null, null, null
    RS.Sort = "ordem"
    If RS.EOF Then ' Se n�o foram selecionados registros, exibe mensagem
        ShowHTML "    <tr bgcolor=""" & conTrBgColor & """><td colspan=5 align=""center""><font size=""2""><b>N�o foi encontrado nenhum registro.</b></td></tr>"
    Else
      While Not RS.EOF
        If cDbl(Nvl(RS("tit_exec"),0))    = cDbl(w_usuario) or _
           cDbl(Nvl(RS("sub_exec"),0))    = cDbl(w_usuario) or _
           cDbl(Nvl(RS("titular"),0))     = cDbl(w_usuario) or _
           cDbl(Nvl(RS("substituto"),0))  = cDbl(w_usuario) or _
           cDbl(Nvl(RS("executor"),0))    = cDbl(w_usuario) or _
           cDbl(Nvl(RS("solicitante"),0)) = cDbl(w_usuario) _
        Then
           ShowHtml metalinha(w_chave, Rs("sq_meta"), Rs("titulo"), RS("nm_resp"), RS("sg_setor"), Rs("inicio_previsto"), Rs("fim_previsto"), Rs("perc_conclusao"), null, "<b>", w_fase, "ETAPA", RS("cd_subacao"))
        Else
           ShowHtml metalinha(w_chave, Rs("sq_meta"), Rs("titulo"), RS("nm_resp"), RS("sg_setor"), Rs("inicio_previsto"), Rs("fim_previsto"), Rs("perc_conclusao"), null, "<b>", "N", "ETAPA", RS("cd_subacao"))
        End If
        
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
    If Nvl(w_desc_subacao,"") > "" Then
       ShowHTML "            <tr><td colspan=""2""><font size=""1"">Suba��o:<b><br><font size=2>" & w_desc_subacao & "</font></td></tr>"
    End If
    If Nvl(trim(w_descricao),"") > "" Then
       ShowHTML "            <tr><td colspan=""2""><font size=""1"">Especifica��o do produto:<b><br>" & w_descricao & "</td></tr>"
    End If
    ShowHTML "            <tr><td valign=""top"" colspan=""2"">"
    ShowHTML "              <table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
    If w_cd_subacao > "" Then
       ShowHTML "                <td><font size=""1"">Meta PPA:<b><br>Sim</td>"
    Else
       ShowHTML "                <td><font size=""1"">Meta PPA:<b><br>N�o</td>"
    End If
    ShowHTML "                <td><font size=""1"">Meta cumulativa:<b><br>" & w_nm_cumulativa & "</td></tr>"
    ShowHTML "                <td><font size=""1"">Meta do PNPIR:<b><br>" & w_nm_programada & "</td></tr>"
    ShowHTML "              </table></td></tr>"
    ShowHTML "            <tr><td valign=""top"" colspan=""2"">"
    ShowHTML "              <table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
    ShowHTML "                <td><font size=""1"">Quantitativo:<b><br>" & w_quantidade& "</td>"
    ShowHTML "                <td><font size=""1"">Unidade de medida:<b><br>" & Nvl(w_unidade_medida,"---") & "</td></tr>"
    ShowHTML "              </table></td></tr>"
    ShowHTML "            <tr><td valign=""top"" colspan=""2"">"
    ShowHTML "              <table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
    ShowHTML "                <td><font size=""1"">Valor aprovado da a��o:<b><br>" & w_aprovado_acao & "</td>"
    ShowHTML "                <td><font size=""1"">Valor autorizado da a��o:<b><br>" & w_autorizado_acao & "</td>"
    ShowHTML "                <td><font size=""1"">Valor realizado da a��o:<b><br>" & w_realizado_acao & "</td></tr>"
    ShowHTML "              </table></td></tr>"    
    ShowHTML "            <tr><td valign=""top"" colspan=""2"">"
    ShowHTML "              <table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
    ShowHTML "                <td><font size=""1"">Previs�o in�cio:<b><br>" & FormataDataEdicao(Nvl(w_inicio,Date())) & "</td>"
    ShowHTML "                <td><font size=""1"">Previs�o t�rmino:<b><br>" & FormataDataEdicao(w_fim) & "</td></tr>"
    ShowHTML "                <tr valign=""top"">"
    DB_GetPersonData RS, w_cliente, w_sq_pessoa, null, null
    ShowHTML "                  <td><font size=""1"">Respons�vel pela meta:<b><br>" & RS("nome_resumido") & "</td>"
    DesconectaBD
    DB_GetUorgData RS, w_sq_unidade
    ShowHTML "                  <td><font size=""1"">Setor respons�vel pela meta:<b><br>" & RS("nome") & " (" & RS("sigla") & ")</td></tr>"
    DesconectaBD
    DB_GetPersonData RS, w_cliente, w_sq_pessoa_atualizacao, null, null
    ShowHTML "                <tr><td colspan=""2""><font size=""1"">Cria��o/�ltima atualiza��o:<b><br><font size=1>" & FormataDataEdicao(w_ultima_atualizacao) '& "</b>, feita por <b>" & RS("nome_resumido") & " (" & RS("sigla") & ")</b></font></td></tr>"
    DesconectaBD
    ShowHTML "              </table></td></tr>"
    ShowHTML "          </TABLE>"
    ShowHTML "      </table>"
    ShowHTML "    <tr bgcolor=""" & conTrBgColor & """><td align=""center"" colspan=""2"">"
    ShowHTML "      <table width=""100%"" border=""0"">"
    If O = "V" Then
       ShowHTML "     <tr><td valign=""top"">"
       ShowHTML "       <table border=1 width=""100%"" cellspacing=0><tr valign=""top"">"
       ShowHTML "         <tr><td>&nbsp<td><font size=""1""><br><b>Quantitativo inicial</b></td>"
       ShowHTML "             <td><font size=""1""><br><b>Quantitativo revisado</b></td>"
       ShowHTML "             <td><font size=""1""><br><b>Quantitativo realizado</b></td>"
       ShowHTML "             <td><font size=""1""><br><b>Financeiro realizado</b></td>"
       ShowHTML "         <tr><td width=""10%"" align=""right""><font size=""1""><b>Janeiro:"
       ShowHTML "             <td align=""right"" width=""30%""><font size=""1"">" & Nvl(w_cron_ini_1,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1"">"& Nvl(w_revisado_1,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1"">"& Nvl(w_realizado_1,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1"">"& Nvl(w_real_acao_1,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Fevereiro:"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_cron_ini_2,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_revisado_2,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_realizado_2,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_real_acao_2,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Mar�o:"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_cron_ini_3,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_revisado_3,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_realizado_3,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_real_acao_3,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Abril:"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_cron_ini_4,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_revisado_4,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_realizado_4,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_real_acao_4,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Maio:"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_cron_ini_5,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_revisado_5,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_realizado_5,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_real_acao_5,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Junho:"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_cron_ini_6,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_revisado_6,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_realizado_6,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_real_acao_6,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Julho:"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_cron_ini_7,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_revisado_7,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_realizado_7,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_real_acao_7,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Agosto:"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_cron_ini_8,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_revisado_8,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_realizado_8,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_real_acao_8,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Setembro:"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_cron_ini_9,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_revisado_9,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_realizado_9,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_real_acao_9,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Outubro:"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_cron_ini_10,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_revisado_10,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_realizado_10,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_real_acao_10,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Novembro:"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_cron_ini_11,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_revisado_11,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_realizado_11,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_real_acao_11,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Dezembro:"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_cron_ini_12,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_revisado_12,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_realizado_12,"---") & "</td>"
       ShowHTML "             <td align=""right""><font size=""1"">" & Nvl(w_real_acao_12,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Total:"
       ShowHTML "             <td align=""right""><font size=""1""><b>" & cDbl(Nvl(w_cron_ini_1,0))+cDbl(Nvl(w_cron_ini_2,0))+cDbl(Nvl(w_cron_ini_3,0))+cDbl(Nvl(w_cron_ini_4,0))+cDbl(Nvl(w_cron_ini_5,0))+cDbl(Nvl(w_cron_ini_6,0))+cDbl(Nvl(w_cron_ini_7,0))+cDbl(Nvl(w_cron_ini_8,0))+cDbl(Nvl(w_cron_ini_9,0))+cDbl(Nvl(w_cron_ini_10,0))+cDbl(Nvl(w_cron_ini_11,0))+cDbl(Nvl(w_cron_ini_12,0)) & "</td>"       
       ShowHTML "             <td align=""right""><font size=""1""><b>" & cDbl(Nvl(w_revisado_1,0))+cDbl(Nvl(w_revisado_2,0))+cDbl(Nvl(w_revisado_3,0))+cDbl(Nvl(w_revisado_4,0))+cDbl(Nvl(w_revisado_5,0))+cDbl(Nvl(w_revisado_6,0))+cDbl(Nvl(w_revisado_7,0))+cDbl(Nvl(w_revisado_8,0))+cDbl(Nvl(w_revisado_9,0))+cDbl(Nvl(w_revisado_10,0))+cDbl(Nvl(w_revisado_11,0))+cDbl(Nvl(w_revisado_12,0)) & "</td>"
       ShowHTML "             <td align=""right""><font size=""1""><b>" & cDbl(Nvl(w_realizado_1,0))+cDbl(Nvl(w_realizado_2,0))+cDbl(Nvl(w_realizado_3,0))+cDbl(Nvl(w_realizado_4,0))+cDbl(Nvl(w_realizado_5,0))+cDbl(Nvl(w_realizado_6,0))+cDbl(Nvl(w_realizado_7,0))+cDbl(Nvl(w_realizado_8,0))+cDbl(Nvl(w_realizado_9,0))+cDbl(Nvl(w_realizado_10,0))+cDbl(Nvl(w_realizado_11,0))+cDbl(Nvl(w_realizado_12,0)) & "</td>"
       ShowHTML "             <td align=""right""><font size=""1""><b>" & FormatNumber(cDbl(Nvl(w_real_acao_1,0))+cDbl(Nvl(w_real_acao_2,0))+cDbl(Nvl(w_real_acao_3,0))+cDbl(Nvl(w_real_acao_4,0))+cDbl(Nvl(w_real_acao_5,0))+cDbl(Nvl(w_real_acao_6,0))+cDbl(Nvl(w_real_acao_7,0))+cDbl(Nvl(w_real_acao_8,0))+cDbl(Nvl(w_real_acao_9,0))+cDbl(Nvl(w_real_acao_10,0))+cDbl(Nvl(w_real_acao_11,0))+cDbl(Nvl(w_real_acao_12,0)),2) & "</td>"
       ShowHTML "       </table>"
       ShowHTML "     <tr><td><font size=""1"">Percentual de conlus�o:<br><b>" & nvl(w_perc_conclusao,0) & "%</b></td>"
       ShowHTML "     <tr><td valign=""top""><font size=""1"">Situa��o atual da meta:<b><br>" & Nvl(w_situacao_atual,"---") & "</td>"
       ShowHTML "     <tr><td valign=""top""><font size=""1"">Justificar os motivos em caso de n�o cumprimento da meta:<b><br>" & Nvl(w_justificativa_inex,"---") & "</td>"
       ShowHTML "     <tr><td valign=""top""><font size=""1"">Quais medidas necess�rias para o cumprimento da meta:<b><br>" & Nvl(w_outras_medidas,"---") & "</td>"
    Else
       ShowHTML "     <tr><td><font size=""1"">Percentual de conlus�o:<br><b>" & nvl(w_perc_conclusao,0) & "%</b></td>"
       ShowHTML "     <tr><td valign=""top"" colspan=""1"">"
       ShowHTML "       <table border=0 width=""40%"" cellspacing=0>"
       ShowHTML "         <tr><td>&nbsp<td><font size=""1""><br><b>Quantitativo inicial</b></td>"
       ShowHTML "             <td title=""Em caso de revis�o da meta programada, os novos valores devem ser informados, m�s a m�s, nestes campos.""><font size=""1""><br><b>Quantitativo revisado</b></td>"
       ShowHTML "             <td title=""Em caso de revis�o da meta programada, os novos valores devem ser informados, m�s a m�s, nestes campos.""><font size=""1""><br><b>Quantitativo realizado</b></td>"
       ShowHTML "             <td><font size=""1""><br><b>Financeiro realizado</b></td>"
       ShowHTML "         <tr><td width=""4%"" align=""right""><font size=""1""><b>Janeiro:"
       ShowHTML "             <td width=""5%"" align=""right""><font size=""1"">"& Nvl(w_cron_ini_1,"---") & "</td>"
       ShowHTML "             <td width=""8%""><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_revisado_1"" SIZE=10 MAXLENGTH=18 VALUE=""" &w_revisado_1 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td width=""8%""><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_realizado_1"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_realizado_1 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td width=""5%"" align=""right""><font size=""1"">"& Nvl(w_real_acao_1,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Fevereiro:"
       ShowHTML "             <td align=""right""><font size=""1"">"& Nvl(w_cron_ini_2,"---") & "</td>"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_revisado_2"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_revisado_2 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_realizado_2"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_realizado_2 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td align=""right""><font size=""1"">"& Nvl(w_real_acao_2,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Mar�o:"
       ShowHTML "             <td align=""right""><font size=""1"">"& Nvl(w_cron_ini_3,"---") & "</td>"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_revisado_3"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_revisado_3 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_realizado_3"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_realizado_3 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td align=""right""><font size=""1"">"& Nvl(w_real_acao_3,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Abril:"
       ShowHTML "             <td align=""right""><font size=""1"">"& Nvl(w_cron_ini_4,"---") & "</td>"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_revisado_4"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_revisado_4 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_realizado_4"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_realizado_4 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td align=""right""><font size=""1"">"& Nvl(w_real_acao_4,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Maio:"
       ShowHTML "             <td align=""right""><font size=""1"">"& Nvl(w_cron_ini_5,"---") & "</td>"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_revisado_5"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_revisado_5 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_realizado_5"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_realizado_5 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td align=""right""><font size=""1"">"& Nvl(w_real_acao_5,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Junho:"
       ShowHTML "             <td align=""right""><font size=""1"">"& Nvl(w_cron_ini_6,"---") & "</td>"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_revisado_6"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_revisado_6 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_realizado_6"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_realizado_6 & """ " & w_Disabled & " ></td>"
       ShowHTML "             <td align=""right""><font size=""1"">"& Nvl(w_real_acao_6,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Julho:"
       ShowHTML "             <td align=""right""><font size=""1"">"& Nvl(w_cron_ini_7,"---") & "</td>"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_revisado_7"" SIZE=10 MAXLENGTH=18 VALUE=""" &w_revisado_7 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_realizado_7"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_realizado_7 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td align=""right""><font size=""1"">"& Nvl(w_real_acao_7,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Agosto:"
       ShowHTML "             <td align=""right""><font size=""1"">"& Nvl(w_cron_ini_8,"---") & "</td>"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_revisado_8"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_revisado_8 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_realizado_8"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_realizado_8 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td align=""right""><font size=""1"">"& Nvl(w_real_acao_8,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Setembro:"
       ShowHTML "             <td align=""right""><font size=""1"">"& Nvl(w_cron_ini_9,"---") & "</td>"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_revisado_9"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_revisado_9 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_realizado_9"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_realizado_9 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td align=""right""><font size=""1"">"& Nvl(w_real_acao_9,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Outubro:"
       ShowHTML "             <td align=""right""><font size=""1"">"& Nvl(w_cron_ini_10,"---") & "</td>"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_revisado_10"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_revisado_10 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_realizado_10"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_realizado_10 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td align=""right""><font size=""1"">"& Nvl(w_real_acao_10,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Novembro:"
       ShowHTML "             <td align=""right""><font size=""1"">"& Nvl(w_cron_ini_11,"---") & "</td>"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_revisado_11"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_revisado_11 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_realizado_11"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_realizado_11 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td align=""right""><font size=""1"">"& Nvl(w_real_acao_11,"---") & "</td>"
       ShowHTML "         <tr><td align=""right""><font size=""1""><b>Dezembro:"
       ShowHTML "             <td align=""right""><font size=""1"">"& Nvl(w_cron_ini_12,"---") & "</td>"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_revisado_12"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_revisado_12 & """ " & w_Disabled & "></td>"
       ShowHTML "             <td><font size=""1""><INPUT TYPE=""TEXT"" CLASS=""STI"" NAME=""w_realizado_12"" SIZE=10 MAXLENGTH=18 VALUE=""" & w_realizado_12 & """ " & w_Disabled & " ></td>"
       ShowHTML "             <td align=""right""><font size=""1"">"& Nvl(w_real_acao_12,"---") & "</td>"
       ShowHTML "       </table>"
       ShowHTML "     <tr><td valign=""top""><font size=""1""><b><u>S</u>itua��o atual da meta:</b><br><textarea " & w_Disabled & " accesskey=""S"" name=""w_situacao_atual"" class=""STI"" ROWS=5 cols=75 title=""Descreva, de maneria sucinta, qual � a situa��o atual da meta."">" & w_situacao_atual & "</TEXTAREA></td>"
       ShowHTML "     <tr valign=""top"">"
       MontaRadioSN "<b>A meta ser� cumprida?</b>", w_exequivel, "w_exequivel"
       ShowHTML "     </tr>"
       ShowHTML "     <tr><td valign=""top""><font size=""1""><b><u>J</u>ustificar os motivos em caso de n�o cumprimento da meta:</b><br><textarea " & w_Disabled & " accesskey=""J"" name=""w_justificativa_inex"" class=""STI"" ROWS=5 cols=75 title=""Informe os motivos que inviabilizam o cumprimento da meta."">" & w_justificativa_inex & "</TEXTAREA></td>"
       ShowHTML "     <tr><td valign=""top""><font size=""1""><b><u>Q</u>uais medidas necess�rias para o cumprimento da meta?</b><br><textarea " & w_Disabled & " accesskey=""Q"" name=""w_outras_medidas"" class=""STI"" ROWS=5 cols=75 title=""Descreva quais s�o as medidas que devem ser adotadas para  que a tendencia de n�o cumprimento da meta programada possa ser revertida."">" & w_outras_medidas & "</TEXTAREA></td>"
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
    ShowHTML " alert('Op��o n�o dispon�vel');"
    ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  If w_tipo <> "WORD" Then
     Rodape
  End If
  Set RS1                       = Nothing 
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
  Set w_cd_subacao              = Nothing
  Set w_chave                   = Nothing 
  Set w_chave_aux               = Nothing 
  Set w_titulo                  = Nothing 
  Set w_ordem                   = Nothing 
  Set w_descricao               = Nothing
  Set w_desc_subacao            = Nothing 
  Set w_desc_acao               = Nothing  
  Set w_aprovado_acao           = Nothing 
  Set w_autorizado_acao         = Nothing 
  Set w_realizado_acao          = Nothing  
  
  Set w_troca                   = Nothing 
  Set i                         = Nothing 
  Set w_texto                   = Nothing
End Sub
REM =========================================================================
REM Fim da tela de atualiza��o das metas da a��o
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina da programa��o or�ament�ria financeira
REM -------------------------------------------------------------------------
Sub Financiamento
  Dim w_chave, w_chave_aux, w_obs_financ
  
  Dim w_troca, i, w_erro
  
  w_Chave           = Request("w_Chave")
  w_chave_aux       = Request("w_chave_aux")
  w_troca           = Request("w_troca")

  If w_troca > "" Then ' Se for recarga da p�gina  
     w_obs_financ      = Request("w_obs_financ")
  ElseIf O = "L" Then
    ' Recupera os dados da a��o para verificar o tipo de programa��o financeira da mesma.
    DB_GetSolicData_IS RS, w_chave, SG
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
     ' Recupera os dados do financiamento
     DB_GetFinancAcaoPPA_IS RS, w_chave, w_cliente, w_ano, Mid(w_chave_aux,1,4), Mid(w_chave_aux,5,4), Mid(w_chave_aux,9,4)
     w_obs_financ  = RS("observacao")
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     modulo
     checkbranco
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_chave_aux", "A��o PPA", "SELECT", "1", "1", "18", "1", "1"
        Validate "w_obs_financ", "Observa��es", "1", "", 5, 2000, "1", "1"
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_troca > "" Then
     BodyOpen"onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf O = "I" Then
     BodyOpen "onLoad='document.Form.w_chave_aux.focus()';"
  Else
     BodyOpenClean "onLoad='document.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
      DB_GetSolicData_IS RS1, w_chave, SG
      If Nvl(RS1("cd_programa"),"") > "" and Nvl(RS1("cd_acao"),"") > "" and Nvl(RS1("cd_unidade"),"") > "" Then
         ShowHTML "      <tr><td colspan=""2""><font size=""1"">Programa Cod� " & RS1("cd_programa") & " - " & RS1("nm_ppa_pai") & "</td>"
         ShowHTML "      <tr><td colspan=""2""><font size=""1"">A��o Cod� " & RS1("cd_unidade") & "." & RS1("cd_acao") & " - " & RS1("nm_ppa") & "</td>"
         ShowHTML "      <tr><td colspan=""2""><font size=""1"">&nbsp</td>"
      End If
      RS1.Close
      If Not IsNull(RS("cd_acao")) Then
       If cDbl(RS("cd_tipo_acao")) <> 3 Then
          ' Exibe os dados da programa��o financeira desssa a��o
          DB_GetPPADadoFinanc_IS RS1, RS("cd_acao"), RS("cd_unidade"), w_ano, w_cliente, "VALORFONTEACAO"
          If RS1.EOF Then
             ShowHTML "      <tr><td valign=""top""><font size=""1""><DD><b>Nao existe nenhum valor para esta a��o</b></DD></td>"
          Else
             w_cor = ""
             ShowHTML "      <tr><td valign=""top""><font size=""1"">Fonte: SIGPLAN/MP - PPA 2004-2007</td>"
             ShowHTML "      <tr><td valign=""top""><font size=""1"">Tipo de or�amento: <b>" & RS1("nm_orcamento") & "</b></td>"
             If cDbl(RS("cd_tipo_acao")) = 1 Then
                ShowHTML "      <tr><td valign=""top""><font size=""1"">Realizado at� 2004: <b>" & FormatNumber(Nvl(RS("valor_ano_anterior"),0),2) & "</b></td>"
                ShowHTML "      <tr><td valign=""top""><font size=""1"">Justificativa da repercus�o financeira sobre o custeio da Uni�o: <b>" & Nvl(RS("reperc_financeira"),"---") & "</b></td>"
                ShowHTML "      <tr><td valign=""top""><font size=""1"">Valor estimado da repercuss�o financeira por ano (R$ 1,00): <b>" & FormatNumber(Nvl(RS("valor_reperc_financeira"),0),2) & "</b></td>"
             End If
             ShowHTML "      <tr><td valign=""top""><font size=""1"">Valor por fonte: </td>"
             ShowHTML "      <tr><td align=""center"" colspan=""2"">"
             ShowHTML "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
             ShowHTML "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
             ShowHTML "            <td><font size=""1""><b>Fonte</font></td>"
             ShowHTML "            <td><font size=""1""><b>2004*</font></td>"
             ShowHTML "            <td><font size=""1""><b>2005**</font></td>"
             ShowHTML "            <td><font size=""1""><b>2006</font></td>"
             ShowHTML "            <td><font size=""1""><b>2007</font></td>"
             ShowHTML "            <td><font size=""1""><b>2008</font></td>"
             ShowHTML "            <td><font size=""1""><b>Total</font></td>"
             ShowHTML "          </tr>"
             While Not RS1.EOF 
                If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
                ShowHTML "       <tr bgcolor=""" & w_cor & """ valign=""top"">"
                ShowHTML "         <td><font size=""1"">" & RS1("nm_fonte")& "</td>"
                ShowHTML "         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_1"),0.00)))& "</td>"
                ShowHTML "         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_2"),0.00)))& "</td>"
                ShowHTML "         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_3"),0.00)))& "</td>"
                ShowHTML "         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_4"),0.00)))& "</td>"
                ShowHTML "         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_5"),0.00)))& "</td>"
                ShowHTML "         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_total"),0.00)))& "</td>"
                ShowHTML "       </tr>"
                RS1.MoveNext
             wend
             ShowHTML "          </table>"   
          End If
          RS1.Close
          DB_GetPPADadoFinanc_IS RS1, RS("cd_acao"), RS("cd_unidade"), w_ano, w_cliente, "VALORTOTALACAO"
          ShowHTML "      <tr><td valign=""top""><font size=""1"">Valor total: </td>"
          If RS1.EOF Then
             ShowHTML "      <tr><td valign=""top""><font size=""1""><DD><b>Nao existe nenhum valor para esta a��o</b></DD></td>"
          Else
             w_cor = ""
             ShowHTML "      <tr><td align=""center"" colspan=""2"">"
             ShowHTML "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
             ShowHTML "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
             ShowHTML "            <td><font size=""1""><b>2004*</font></td>"
             ShowHTML "            <td><font size=""1""><b>2005**</font></td>"
             ShowHTML "            <td><font size=""1""><b>2006</font></td>"
             ShowHTML "            <td><font size=""1""><b>2007</font></td>"
             ShowHTML "            <td><font size=""1""><b>2008</font></td>"
             ShowHTML "            <td><font size=""1""><b>Total</font></td>"
             ShowHTML "          </tr>"
             If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
             ShowHTML "       <tr bgcolor=""" & w_cor & """ valign=""top"">"
             ShowHTML "         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_1"),0.00)))& "</td>"
             ShowHTML "         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_2"),0.00)))& "</td>"
             ShowHTML "         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_3"),0.00)))& "</td>"
             ShowHTML "         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_4"),0.00)))& "</td>"
             ShowHTML "         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_5"),0.00)))& "</td>"
             ShowHTML "         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_total"),0.00)))& "</td>"
             ShowHTML "       </tr>"
             ShowHTML "       </table>"  
             ShowHTML "          <tr><td valign=""top"" colspan=""2""><font size=""1"">* Valor Lei Or�ament�ria Anual - LOA 2004 + Cr�ditos</td>"
             ShowHTML "          <tr><td valign=""top"" colspan=""2""><font size=""1"">** Valor do Projeto de Lei Or�ament�ria Anual - PLOA 2005</td>"    

          End If
          RS1.Close
          DesconectaBD
          ' Recupera todos os registros para a listagem
          DB_GetFinancAcaoPPA_IS RS, w_chave, w_cliente, w_ano, null, null, null
          ' Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
          ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
          ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
          ShowHTML "<tr><td align=""center"" colspan=3>"
          ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
          ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
          ShowHTML "          <td><font size=""1""><b>C�digo</font></td>"
          ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
          ShowHTML "          <td><font size=""1""><b>Opera��es</font></td>"
          ShowHTML "        </tr>"
          If RS.EOF Then ' Se n�o foram selecionados registros, exibe mensagem
             ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7 align=""center""><font size=""2""><b>N�o foram encontrados registros.</b></td></tr>"
          Else
             w_cor = ""
             ' Lista os registros selecionados para listagem
             While Not RS.EOF
                If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
                ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
                ShowHTML "        <td><font size=""1"">" & RS("cd_programa")& "." & RS("cd_acao") & "." & RS("cd_unidade")& "</td>"
                ShowHTML "        <td><font size=""1"">" & RS("descricao_acao") & "</td>"
                ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
                ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_pagina & par & "&O=A&w_chave=" & w_chave & "&w_chave_aux=" &RS("sq_acao_ppa")&"&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
                ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_pagina & par & "&O=E&w_chave=" & w_chave & "&w_chave_aux=" &RS("sq_acao_ppa")&"&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
                ShowHTML "        </td>"
                ShowHTML "      </tr>"
                RS.MoveNext
             wend
          End If
       Else
          ShowHTML "      <tr><td valign=""top""><font size=""1""><DD><b>Nao existe progra��o financeira para esta a�ao, pois � uma a��o nao or�ament�ria.</b></DD></td>"
       End If
    Else
       ' Recupera todos os registros para a listagem
       DB_GetFinancAcaoPPA_IS RS, w_chave, w_cliente, w_ano, null, null, null
       ' Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
       ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
       ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
       ShowHTML "<tr><td align=""center"" colspan=3>"
       ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
       ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
       ShowHTML "          <td><font size=""1""><b>C�digo</font></td>"
       ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
       ShowHTML "          <td><font size=""1""><b>Opera��es</font></td>"
       ShowHTML "        </tr>"
       If RS.EOF Then ' Se n�o foram selecionados registros, exibe mensagem
          ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7 align=""center""><font size=""2""><b>N�o foram encontrados registros.</b></td></tr>"
       Else
          w_cor = ""
          ' Lista os registros selecionados para listagem
          While Not RS.EOF
             If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
             ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
             ShowHTML "        <td><font size=""1"">" & RS("cd_unidade") & "." & RS("cd_programa")& "." & RS("cd_acao") & "</td>"
             ShowHTML "        <td><font size=""1"">" & RS("descricao_acao") & "</td>"
             ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
             ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_pagina & par & "&O=A&w_chave=" & w_chave & "&w_chave_aux=" &RS("sq_acao_ppa")&"&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
             ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_pagina & par & "&O=E&w_chave=" & w_chave & "&w_chave_aux=" &RS("sq_acao_ppa")&"&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
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
    DB_GetSolicData_IS RS1, w_chave, SG
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    If Nvl(RS1("cd_acao"),"") > "" Then
       ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Programa PPA: </b><br>" &RS1("cd_ppa_pai")& " - " & RS1("nm_ppa_pai") & " </b>"
       ShowHTML "      <tr><td valign=""top""><font size=""1""><b>A��o PPA: </b><br>(" &RS1("cd_unidade") & "." & RS1("cd_acao")& ") - " & RS1("nm_ppa") & " </b>"      
    End If
    If Nvl(RS1("sq_isprojeto"),"") > "" Then
       ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Programa interno: </b><br>" & RS1("nm_pri") & " </b>"      
    End If
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
    If O = "I" Then
       SelecaoAcaoPPA "A��o <u>P</u>PA:", "P", "Selecionar, quando for o caso, outra a��o do PPA que contribua para o financiamento da a��o que est� sendo cadastrada.", w_cliente, w_ano, RS1("cd_programa"), RS1("cd_acao"), null, RS1("cd_unidade"), "w_chave_aux", "FINANCIAMENTO", null, w_chave, w_menu
    Else
       SelecaoAcaoPPA "A��o <u>P</u>PA:", "P", "Selecionar, quando for o caso, outra a��o do PPA que contribua para o financiamento da a��o que est� sendo cadastrada.", w_cliente, w_ano, Mid(w_chave_aux,1,4), Mid(w_chave_aux,5,4), Mid(w_chave_aux,9,4),  Mid(w_chave_aux,13,5), "w_chave_aux", null, "disabled", null, w_menu
       ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_chave_aux &""">"
    End If
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Obse<u>r</u>va��es:</b><br><textarea " & w_Disabled & " accesskey=""R"" name=""w_obs_financ"" class=""STI"" ROWS=5 cols=75 title=""Informar fatos ou situa��es que sejam relevantes para uma melhor compreens�o do financiamento da a��o."">" & w_obs_financ & "</TEXTAREA></td>"
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
    RS1.Close
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Op��o n�o dispon�vel');"
    ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_chave           = Nothing 
  Set w_chave_aux       = Nothing 
  Set w_obs_financ      = Nothing
  
  Set w_troca           = Nothing 
  Set i                 = Nothing 
  Set w_erro            = Nothing
End Sub
REM =========================================================================
REM Fim da tela de financiamento
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de restri��es da a��o
REM -------------------------------------------------------------------------
Sub Restricoes
  Dim w_chave, w_chave_aux, w_sq_isprojeto, w_cd_tipo_restricao
  Dim w_cd_tipo_inclusao, w_cd_competencia, w_inclusao
  Dim w_descricao, w_providencia, w_superacao, w_relatorio, w_tempo_habil
  Dim w_observacao_monitor, w_observacao_controle, w_cd_subacao, w_nm_tipo_restricao
  Dim w_acesso, w_cabecalho, w_tipo, w_cd_programa, w_cd_acao, w_cd_unidade, w_ds_acao, w_ds_programa
  
  Dim w_troca, i, w_texto
  
  w_Chave           = Request("w_Chave")
  w_chave_aux       = Request("w_chave_aux")
  w_troca           = Request("w_troca")
  w_sq_isprojeto    = Request("w_sq_isprojeto")
  w_tipo            = Request("w_tipo") 
  w_cd_subacao      = Request("w_cd_subacao")
  
  DB_GetSolicData_IS RS, w_chave, SG
  w_cd_programa  = RS("cd_programa")
  w_cd_acao      = RS("cd_acao")
  w_cd_subacao   = RS("cd_subacao")
  w_cd_unidade   = RS("cd_unidade")
  w_sq_isprojeto = RS("sq_isprojeto")
  w_ds_acao      = RS("nm_ppa")
  w_ds_programa  = RS("nm_ppa_pai")
  
  If cDbl(Nvl(RS("tit_exec"),0))    = cDbl(w_usuario) or _
     cDbl(Nvl(RS("subst_exec"),0))  = cDbl(w_usuario) or _
     cDbl(Nvl(RS("titular"),0))     = cDbl(w_usuario) or _
     cDbl(Nvl(RS("substituto"),0))  = cDbl(w_usuario) or _
     cDbl(Nvl(RS("executor"),0))    = cDbl(w_usuario) or _
    (cDbl(Nvl(RS("cadastrador"),0)) = cDbl(w_usuario) and P1 < 2) or _
     cDbl(Nvl(RS("solicitante"),0)) = cDbl(w_usuario)  _
  Then
     If Nvl(RS("inicio_real"),"") > "" Then
        w_acesso = 0
     Else
        w_acesso = 1
     End If
  Else
     w_acesso = 0 
  End If
  w_cabecalho = "      <tr><td valign=""top"" colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font  size=""2""><b>A��o: " & RS("titulo") & " (" & RS("cd_unidade") & "." & RS("cd_programa") & "." & RS("cd_acao") & ")</td></tr>"
  DesconectaBD
  
  If w_troca > "" Then ' Se for recarga da p�gina
     w_cd_tipo_restricao    = Request("w_cd_tipo_restricao")
     w_cd_tipo_inclusao     = Request("w_cd_tipo_inclusao")
     w_cd_competecia        = Request("w_cd_competecia")
     w_inclusao             = Request("w_inclusao")
     w_descricao            = Request("w_descricao")
     w_providencia          = Request("w_providencia")    
     w_superacao            = Request("w_superacao")    
     w_relatorio            = Request("w_relatorio")    
     w_tempo_habil          = Request("w_tempo_habil")    
     w_observacao_controle  = Request("w_observacao_controle")        
     w_observacao_monitor   = Request("w_observacao_monitor")
     w_cd_programa          = Request("w_cd_programa")
     w_cd_acao              = Request("w_cd_acao")
     w_cd_unidade           = Request("w_cd_unidade")
  ElseIf O = "L" Then
     ' Recupera todos os registros para a listagem
     DB_GetRestricao_IS RS, SG, w_chave, null
     RS.Sort = "inclusao desc"
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
     DB_GetRestricao_IS RS, SG, w_chave, w_chave_aux
     w_cd_subacao           = RS("cd_subacao")
     w_cd_tipo_restricao    = RS("cd_tipo_restricao")
     w_cd_tipo_inclusao     = RS("cd_tipo_inclusao")
     w_cd_competencia       = RS("cd_competencia")
     w_inclusao             = FormataDataEdicao(RS("inclusao"))
     w_descricao            = RS("descricao")
     w_providencia          = RS("providencia")
     w_superacao            = FormataDataEdicao(RS("superacao"))
     w_relatorio            = RS("relatorio")
     w_tempo_habil          = RS("tempo_habil")
     w_observacao_controle  = RS("observacao_controle")
     w_observacao_monitor   = RS("observacao_monitor")
     w_nm_tipo_restricao    = RS("nm_tp_restricao")
     DesconectaBD
  End If
  
  If w_tipo = "WORD" Then
     Response.ContentType = "application/msword"
  Else
     Cabecalho
  End If
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     modulo
     checkbranco
     formatadata
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        DB_GetPPALocalizador_IS RS, w_cliente, w_ano, w_cd_programa, w_cd_acao, w_cd_unidade, null
        If RS.RecordCount > 1 Then
           Validate "w_cd_localizador", "Localizador", "SELECT", "1", "1", "18", "1", "1"
        End If
        DesconectaBD
        Validate "w_cd_tipo_restricao", "Tipo da restri��o", "SELECT", "1", "1", "18", "", "1"
        'Validate "w_cd_tipo_inclusao", "Tipo de inclus�o", "", "", "1", "2", "1", "1"
        'Validate "w_cd_competencia", "Compet�ncia", "", "", "1", "2", "1", "1"
        Validate "w_descricao", "Descri��o", "", "1", "3", "4000", "1", "1"
        Validate "w_providencia", "Provid�ncia", "", "", "3", "4000", "1", "1"
        'Validate "w_superacao", "Supera��o", "DATA", "", 10, 10, "", "0123456789/"
        'Validate "w_observacao_controle", "Observa��o controle", "", "", "3", "4000", "1", "1"
        'Validate "w_observacao_monitor", "Observa��o monitor", "", "", "3", "4000", "1", "1"
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
     BodyOpen "onLoad='document.Form.w_cd_tipo_restricao.focus()';"
  Else
     BodyOpen "onLoad='document.focus()';"
  End If
  If O = "V" Then
     ShowHTML "<div align=center><center>"
     ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
     ShowHTML w_cabecalho
  Else
     ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
     ShowHTML "<HR>"
     ShowHTML "<div align=center><center>"
     ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  End If
  If O = "L" Then
    If Nvl(w_cd_programa,"") > "" and Nvl(w_cd_acao,"") > "" and Nvl(w_cd_unidade,"") > "" Then
       ShowHTML "      <tr><td colspan=""2""><font size=""1"">Programa Cod� " & w_cd_programa & " - " & w_ds_programa & "</td>"
       ShowHTML "      <tr><td colspan=""2""><font size=""1"">A��o Cod� " & w_cd_unidade & "." & w_cd_acao & " - " & w_ds_acao & "</td>"
    End If
    ' Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
    If cDbl(w_acesso) = 1 Then
       ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave &  "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    Else
       ShowHTML "<tr><td><font size=""2"">&nbsp;"
    End If
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Descri��o</font></td>"
    ShowHTML "          <td><font size=""1""><b>Tipo restricao</font></td>"
    'ShowHTML "          <td><font size=""1""><b>Tipo inclus�o</font></td>"
    ShowHTML "          <td><font size=""1""><b>Inclus�o</font></td>"
    'ShowHTML "          <td><font size=""1""><b>Supera��o</font></td>"
    ShowHTML "          <td><font size=""1""><b>Opera��es</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se n�o foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=9 align=""center""><font size=""2""><b>N�o foram encontrados registros.</b></td></tr>"
    Else
       While Not RS.EOF
          If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
          ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
          ShowHTML "        <td><font size=""1""><A class=""HL"" HREF=""#"" onClick=""window.open('" & w_pagina & par & "&R=" & w_Pagina & par & "&O=V&w_chave=" & w_chave & "&w_chave_aux=" & RS("sq_restricao") & "&P1=10&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "','Restricao','width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no'); return false;"" title=""Clique para exibir os dados!"">" &RS("descricao") & "</A></td>"
          ShowHTML "        <td><font size=""1"">" & RS("nm_tp_restricao") & "</td>"
          'ShowHTML "        <td align=""center""><font size=""1"">" & NVL(RS("cd_tipo_inclusao"),"---") & "</td>"
          ShowHTML "        <td align=""center""><font size=""1"">" & FormataDataEdicao(RS("inclusao"))& "</td>"
          'ShowHTML "        <td align=""center""><font size=""1"">" & NVL(FormataDataEdicao(RS("superacao")),"---")& "</td>"
          ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
          If cDbl(w_acesso) = 1 Then
             ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & w_chave & "&w_chave_aux=" & RS("sq_restricao") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
             ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "GRAVA&R=" & w_pagina & par & "&O=E&w_chave=" & w_chave & "&w_chave_aux=" & RS("sq_restricao") & "&w_descricao=" & RS("descricao")& "&w_providencia=" & RS("providencia") & "&w_cd_tipo_restricao=" & RS("cd_tipo_restricao") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ onClick=""return confirm('Confirma a exclus�o do registro?');"">Excluir</A>&nbsp"
          Else
             ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=V&w_chave=" & w_chave & "&w_chave_aux=" & RS("sq_restricao") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Exibir</A>&nbsp"
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
    If InStr("E",O) Then
       w_Disabled = " DISABLED "
    End If
    AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_chave_aux & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_data_hora"" value=""" & RS_menu("data_hora") &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & RS_menu("sq_menu") &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_relatorio"" value=""S"">"
    ShowHTML "<INPUT type=""hidden"" name=""w_tempo_habil"" value=""N"">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_isprojeto"" value=""" & w_sq_isprojeto & """>"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "  <table width=""97%"" border=""0"">"
    DB_GetPPALocalizador_IS RS, w_cliente, w_ano, w_cd_programa, w_cd_acao, w_cd_unidade, null
    If RS.RecordCount > 1 Then
       ShowHTML "    <tr valign=""top"" >"
       SelecaoLocalizador_IS "<U>L</U>ocalizador:", "L", "Selecione o localizador da restri��o", w_cd_subacao, w_cd_programa, w_cd_acao, w_cd_unidade, "w_cd_subacao", null, null
    Else
       ShowHTML "<INPUT type=""hidden"" name=""w_cd_subacao"" value=""" & w_cd_subacao &""">"
    End If
    ShowHTML "    <tr valign=""top"" >"
    DesconectaBD
    SelecaoTPRestricao_IS "<U>T</U>ipo de restri��o:", "T", "Selecione o tipo de restri��o", w_cd_tipo_restricao, "w_cd_tipo_restricao", null, null
    'ShowHTML "                        <td><font size=""1""><b>Tipo de <u>I</u>nclus�o:</b><br><input " & w_Disabled & " accesskey=""I"" type=""text"" name=""w_cd_tipo_inclusao"" class=""STI"" SIZE=""3"" MAXLENGTH=""2"" VALUE=""" & w_cd_tipo_inclusao & """ title=""Informe o tipo de inclus�o da restri��o.""></td>"
    'ShowHTML "    <tr valign=""top"" ><td><font size=""1""><b><u>C</u>ompet�ncia:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_cd_competencia"" class=""STI"" SIZE=""3"" MAXLENGTH=""2"" VALUE=""" & w_cd_competencia & """ title=""Informe a compet�ncia da restri��o.""></td>"
    'ShowHTML "                        <td><font size=""1""><b><u>S</u>upera��o:</b><br><input " & w_Disabled & " accesskey=""S"" type=""text"" name=""w_superacao"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_superacao & """ onKeyDown=""FormataData(this,event);"" title=""Data de supera��o da restri��o.""></td>"
    'ShowHTML "    <tr valign=""top"">"
    'MontaRadioSN "<b>Relat�rio?</b>", w_relatorio, "w_relatorio"
    'MontaRadioSN "<b>Tempo h�bil?</b>", w_tempo_habil, "w_tempo_habil"
    ShowHTML "    <tr><td colspan=2><font size=""1""><b><u>D</u>escri��o:</b><br><textarea " & w_Disabled & " accesskey=""D"" name=""w_descricao"" class=""STI"" ROWS=5 cols=75 title=""Descreva os fatores que podem prejudicar o andamento da a��o. As restri��es podem ser administrativas, ambientais, de auditoria, de licita��es, financeiras, institucuionais, pol�ticas, tecnol�gicas, judiciais, etc. Cada tipo de restri��o deve ser inserido separadamente."">" & w_descricao & "</TEXTAREA></td>"
    ShowHTML "    <tr><td colspan=2><font size=""1""><b><u>P</u>rovid�ncia:</b><br><textarea " & w_Disabled & " accesskey=""P"" name=""w_providencia"" class=""STI"" ROWS=5 cols=75 title=""Informe as provid�ncias que devem ser tomadas para a supera��o da restri��o."">" & w_providencia & "</TEXTAREA></td>"
    'ShowHTML "    <tr><td colspan=2><font size=""1""><b>O<u>b</u>serva��o de controle:</b><br><textarea " & w_Disabled & " accesskey=""b"" name=""w_observacao_controle"" class=""STI"" ROWS=5 cols=75 title=""Observa��es de controle."">" & w_observacao_controle & "</TEXTAREA></td>"
    'ShowHTML "    <tr><td colspan=2><font size=""1""><b><u>O</u>bserva��o do monitor:</b><br><textarea " & w_Disabled & " accesskey=""O"" name=""w_observacao_monitor"" class=""STI"" ROWS=5 cols=75 title=""Observa��es do monitor."">" & w_observacao_monitor & "</TEXTAREA></td>"
    ShowHTML "    <tr><td align=""center"" colspan=2><hr>"
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
   ElseIf O = "V" Then
     If w_tipo <> "WORD" and O = "V" Then
        ShowHTML "<tr><td align=""right""colspan=""2"">"
        ShowHTML "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Imprimir"" SRC=""images/impressora.jpg"" onClick=""window.print();"">"
        ShowHTML "&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Gerar word"" SRC=""images/word.gif"" onClick=""window.open('" & w_pagina & par & "&O=V&w_chave=" & w_chave & "&w_chave_aux=" & w_chave_aux & "&w_cd_programa=" & w_cd_programa&  "&w_tipo=WORD&P1=10&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "','RestricaoWord','width=600, height=350, top=65, left=65, menubar=yes, scrollbars=yes, resizable=yes, status=no');"">"
        ShowHTML "</td></tr>"
     End If
     ShowHTML "    <tr><td align=""center"" bgcolor=""#FAEBD7"" colspan=""2"">"
     ShowHTML "      <table border=1 width=""100%"">"
     ShowHTML "        <tr><td valign=""top"" colspan=""2"">"    
     ShowHTML "          <TABLE border=0 WIDTH=""100%"" CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     DB_GetPPALocalizador_IS RS, w_cliente, w_ano, w_cd_programa, w_cd_acao, w_cd_unidade, null
     If RS.RecordCount > 1 Then
        DB_GetPPALocalizador_IS RS, w_cliente, w_ano, w_cd_programa, w_cd_acao, w_cd_unidade, w_cd_subacao
        ShowHTML "            <tr><td colspan=""2""><font size=""1"">Localizador:<b><br><font size=2>" & Nvl(RS("nome"),"---") & "</font></td></tr>"
     End If
     DesconectaBD
     ShowHTML "            <tr><td colspan=""2""><font size=""1"">Descri��o da restri��o:<b><br><font size=2>" & Nvl(w_descricao,"---") & "</font></td></tr>"
     ShowHTML "            <tr><td><font size=""1"">Tipo de restri��o<b><br>" & Nvl(w_nm_tipo_restricao,"---")& "</td>"
     'ShowHTML "                <td><font size=""1"">Tipo de inclus�o:<b><br>" & w_cd_tipo_inclusao & "</td></tr>"
     'ShowHTML "            <tr><td><font size=""1"">Compet�ncia:<b><br>" & Nvl(w_cd_competencia,"---") & "</td>"
     'ShowHTML "                <td><font size=""1"">Data supera��o:<b><br>" & Nvl(w_superacao,"---") & "</td></tr>"
     'ShowHTML "            <tr><td><font size=""1"">Relat�rio?<b><br>" & RetornaSimNao(w_relatorio) & "</td>"
     'ShowHTML "                <td><font size=""1"">Tempo h�bil?<b><br>" & RetornaSimNao(w_tempo_habil) & "</td></tr>"
     ShowHTML "            <tr><td><font size=""1"">Data inclus�o:<b><br>" & Nvl(FormataDataEdicao(w_inclusao),"---") & "</td>"    
     ShowHTML "          </TABLE>"
     ShowHTML "      </table>"
     ShowHTML "    <tr bgcolor=""" & conTrBgColor & """><td align=""center"" colspan=""2"">"
     ShowHTML "      <table width=""100%"" border=""0"">"
     ShowHTML "     <tr><td valign=""top""><font size=""1""><b>Provid�ncia:</b><br>" & Nvl(w_providencia,"---") & "</td>"
     'ShowHTML "     <tr><td valign=""top""><font size=""1"">&nbsp;</td>"
     'ShowHTML "     <tr><td valign=""top""><font size=""1""><b>Observa��o de controle:</b><br>" & Nvl(w_observacao_controle,"---") & "</td>"
     'ShowHTML "     <tr><td valign=""top""><font size=""1"">&nbsp;</td>"
     'ShowHTML "     <tr><td valign=""top""><font size=""1""><b>Observa��o do monitor:</b><br>" & Nvl(w_observacao_monitor,"---") & "</td>"
     'ShowHTML "     <tr><td valign=""top""><font size=""1"">&nbsp;</td>"
     ShowHTML "           </table></td></tr>"
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
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Op��o n�o dispon�vel');"
    ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape
  
  Set w_cd_subacao          = Nothing
  Set w_cd_programa         = Nothing
  Set w_cd_acao             = Nothing
  Set w_cd_unidade          = Nothing
  Set w_cd_tipo_restricao   = Nothing 
  Set w_cd_tipo_inclusao    = Nothing 
  Set w_cd_competencia      = Nothing  
  Set w_descricao           = Nothing 
  Set w_providencia         = Nothing 
  Set w_superacao           = Nothing 
  Set w_relatorio           = Nothing 
  Set w_tempo_habil         = Nothing 
  Set w_observacao_monitor  = Nothing 
  Set w_observacao_controle = Nothing
  Set w_nm_tipo_restricao   = Nothing
  Set w_acesso              = Nothing
  Set w_cabecalho           = Nothing
  Set w_tipo                = Nothing
  
  Set w_chave               = Nothing 
  Set w_chave_aux           = Nothing 
  
  Set w_troca               = Nothing 
  Set i                     = Nothing 
  Set w_texto               = Nothing
End Sub
REM =========================================================================
REM Fim da tela de restri��es da a��o
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
  
  If w_troca > "" Then ' Se for recarga da p�gina
     w_tipo_visao           = Request("w_tipo_visao")
     w_envia_email          = Request("w_envia_email")    
  ElseIf O = "L" Then
     ' Recupera todos os registros para a listagem
     DB_GetSolicInter RS, w_chave, null, "LISTA"
     RS.Sort = "nome_resumido"
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
     ' Recupera os dados do endere�o informado
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
        Validate "w_tipo_visao", "Tipo de vis�o", "SELECT", "1", "1", "10", "", "1"
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
    ShowHTML "      <tr><td colspan=3><font size=1>Usu�rios que devem receber emails dos encaminhamentos desta a��o.</font></td></tr>"
    ShowHTML "      <tr><td colspan=3 align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ' Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
    If P1 <> 4 Then 
       ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    Else
       DB_GetSolicData_IS RS1, w_chave, "ISACVISUAL"
       ShowHTML "<tr><td colspan=3 align=""center"" bgcolor=""#FAEBD7""><table border=1 width=""100%""><tr><td>"
       ShowHTML "    <TABLE WIDTH=""100%"" CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
       ShowHTML "        <tr valign=""top"">"
       If RS1("cd_acao") > "" Then
          ShowHTML "          <td><font size=""1""><b>A��o PPA: </b><br>" & RS1("cd_unidade") & "." & RS1("cd_ppa_pai")& "." & RS1("cd_acao") & " - "  & RS1("nm_ppa") &  "</b>"      
       End If
       If RS1("sq_isprojeto") > "" Then
          ShowHTML "        <td><font size=""1""><b>Programa interno: </b><br>" & RS1("nm_pri") & " </b>"      
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
    'ShowHTML "          <td><font size=""1""><b>Visao</font></td>"
    ShowHTML "          <td><font size=""1""><b>Envia e-mail</font></td>"
    If P1 <> 4 Then
       ShowHTML "          <td><font size=""1""><b>Opera��es</font></td>"
    End If
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se n�o foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7 align=""center""><font size=""2""><b>N�o foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & ExibePessoa("../", w_cliente, RS("sq_pessoa"), TP, RS("nome") & " (" & RS("lotacao") & ")") & "</td>"
        'ShowHTML "        <td><font size=""1"">" & RetornaTipoVisao(RS("tipo_visao")) & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & Replace(Replace(RS("envia_email"),"S","Sim"),"N","N�o") & "</td>"
        If P1 <> 4 Then
           ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
           'ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & w_chave & "&w_chave_aux=" & Rs("sq_pessoa") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
           ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "GRAVA&R=" & w_pagina & par & "&O=E&w_chave=" & w_chave & "&w_chave_aux=" & Rs("sq_pessoa") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ onClick=""return confirm('Confirma a exclus�o do registro?');"">Excluir</A>&nbsp"
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
    ShowHTML "<INPUT type=""hidden"" name=""w_tipo_visao"" value=""0"">"
    ShowHTML "<INPUT type=""hidden"" name=""w_envia_email"" value=""S"">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
    If O = "I" Then
       SelecaoPessoa "<u>P</u>essoa:", "N", "Selecione a pessoa que deve receber e-mails com informa��es sobre a a��o.", w_chave_aux, null, "w_chave_aux", "USUARIOS"
    Else
       ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_chave_aux &""">"
       ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Pessoa:</b><br>" & w_nome & "</td>"
    End If
    ShowHTML "          </table>"
    'ShowHTML "      <tr>"
    'MontaRadioNS "<b>Envia e-mail ao interessado quando houver encaminhamento?</b>", w_envia_email, "w_envia_email"
    'ShowHTML "      </tr>"
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
    ShowHTML " alert('Op��o n�o dispon�vel');"
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

REM ------------------------------------------------------------------------- 
REM Rotina de anexos 
REM ------------------------------------------------------------------------- 
Sub Anexos 
  Dim w_chave, w_chave_pai, w_chave_aux, w_nome, w_descricao, w_caminho 
    
  Dim w_troca, i, w_erro 
    
  w_Chave           = Request("w_Chave") 
  w_chave_aux       = Request("w_chave_aux") 
  w_troca           = Request("w_troca") 
    
  If w_troca > "" Then ' Se for recarga da p�gina 
     w_nome                = Request("w_nome") 
     w_descricao           = Request("w_descricao") 
     w_caminho             = Request("w_caminho") 
  ElseIf O = "L" Then 
     ' Recupera todos os registros para a listagem 
     DB_GetSolicAnexo RS, w_chave, null, w_cliente 
     RS.Sort = "nome" 
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then 
     ' Recupera os dados do endere�o informado 
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
     ProgressBar w_dir_volta, UploadID         
     ValidateOpen "Validacao" 
     If InStr("IA",O) > 0 Then 
        Validate "w_nome", "T�tulo", "1", "1", "1", "255", "1", "1" 
        Validate "w_descricao", "Descri��o", "1", "1", "1", "1000", "1", "1" 
        If O = "I" Then 
           Validate "w_caminho", "Arquivo", "", "1", "5", "255", "1", "1" 
        End If 
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
    DB_GetSolicData_IS RS1, w_chave, SG
    If Nvl(RS1("cd_programa"),"") > "" and Nvl(RS1("cd_acao"),"") > "" and Nvl(RS1("cd_unidade"),"") > "" Then
       ShowHTML "      <tr><td colspan=""2""><font size=""1"">Programa Cod� " & RS1("cd_programa") & " - " & RS1("nm_ppa_pai") & "</td>"
       ShowHTML "      <tr><td colspan=""2""><font size=""1"">A��o Cod� " & RS1("cd_unidade") & "." & RS1("cd_acao") & " - " & RS1("nm_ppa") & "</td>"
    End If
    RS1.Close
    ' Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem 
    ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;" 
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount 
    ShowHTML "<tr><td align=""center"" colspan=3>" 
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>" 
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">" 
    ShowHTML "          <td><font size=""1""><b>T�tulo</font></td>" 
    ShowHTML "          <td><font size=""1""><b>Descri��o</font></td>" 
    ShowHTML "          <td><font size=""1""><b>Tipo</font></td>" 
    ShowHTML "          <td><font size=""1""><b>KB</font></td>" 
    ShowHTML "          <td><font size=""1""><b>Opera��es</font></td>" 
    ShowHTML "        </tr>" 
    If RS.EOF Then ' Se n�o foram selecionados registros, exibe mensagem 
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7 align=""center""><font size=""1""><b>N�o foram encontrados registros.</b></td></tr>" 
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
    ShowHTML "<FORM action=""" & w_dir & w_pagina & "Grava&SG="&SG&"&O="&O&"&UploadID="&UploadID&""" name=""Form"" onSubmit=""return(Validacao(this));"" enctype=""multipart/form-data"" method=""POST"">" 
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
       ShowHTML "      <tr><td align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""2""><b><font color=""#BC3131"">ATEN��O</font>: o tamanho m�ximo aceito para o arquivo � de " & cDbl(RS("upload_maximo"))/1024 & " KBytes</b>.</font></td>" 
       ShowHTML "<INPUT type=""hidden"" name=""w_upload_maximo"" value=""" & RS("upload_maximo") & """>" 
    End If 
    
    ShowHTML "      <tr><td><font size=""1""><b><u>T</u>�tulo:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_nome"" class=""STI"" SIZE=""75"" MAXLENGTH=""255"" VALUE=""" & w_nome & """ title=""Informe o t�ulo do arquivo.""></td>" 
    ShowHTML "      <tr><td><font size=""1""><b><u>D</u>escri��o:</b><br><textarea " & w_Disabled & " accesskey=""D"" name=""w_descricao"" class=""STI"" ROWS=5 cols=65 title=""Descreva o conte�do do arquivo."">" & w_descricao & "</TEXTAREA></td>" 
    ShowHTML "      <tr><td><font size=""1""><b>A<u>r</u>quivo:</b><br><input " & w_Disabled & " accesskey=""R"" type=""file"" name=""w_caminho"" class=""STI"" SIZE=""80"" MAXLENGTH=""100"" VALUE="""" title=""OBRIGAT�RIO. Clique no bot�o ao lado para localizar o arquivo. Ele ser� transferido automaticamente para o servidor."">" 
    If w_caminho > "" Then 
       ShowHTML "              <b>" & LinkArquivo("SS", w_cliente, w_caminho, "_blank", "Clique para exibir o arquivo atual.", "Exibir", null) & "</b>" 
    End If 
    ShowHTML "      <tr><td align=""center""><hr>" 
    If O = "E" Then 
       ShowHTML "   <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"" onClick=""return confirm('Confirma a exclus�o do registro?');"">" 
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
    ShowHTML " alert('Op��o n�o dispon�vel');" 
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
REM Rotina de visualiza��o
REM -------------------------------------------------------------------------
Sub VisualNovo

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
  ShowHTML "<TITLE>" & conSgSistema & " - Visualiza��o de A��o</TITLE>"
  ShowHTML "</HEAD>"  
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_tipo <> "WORD" Then
     BodyOpenClean "onLoad='document.focus()'; "
  End If
  ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "EMBED") & """><TD ALIGN=""RIGHT""><B><FONT SIZE=4 COLOR=""#000000"">"
  If P1 = 1 Then
     ShowHTML "Iniciativas Priorit�rias do Governo <BR> Relat�rio Geral por A��o"
  ElseIf P1 = 2 Then
     ShowHTML "Plano Plurianual 2004 - 2007 <BR> Relat�rio Geral por A��o"
  Else
     ShowHTML "Visualiza��o de A��o"
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
     ShowHTML "<center><B><font size=1>Clique <a class=""HL"" href=""javascript:history.back(1);"">aqui</a> para voltar � tela anterior</font></b></center>"
  End If
  
  ' Chama a rotina de visualiza��o dos dados da a��o, na op��o "Listagem"
  ShowHTML VisualAcao(w_chave, "L", w_usuario, P1, P4)

  
  If w_tipo > "" and w_tipo <> "WORD" Then
     ShowHTML "<center><B><font size=1>Clique <a class=""HL"" href=""javascript:history.back(1);"">aqui</a> para voltar � tela anterior</font></b></center>"
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
REM Rotina de visualiza��o do novo layout de relat�rios
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
  ShowHTML "<TITLE>" & conSgSistema & " - A��es - Exerc�cio " & w_ano & "</TITLE>"
  ShowHTML "</HEAD>"  
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_tipo <> "WORD" Then
     BodyOpenClean "onLoad='document.focus()'; "
  End If
  ShowHTML "<div align=""center"">"
  ShowHTML "<table width=""95%"" border=""0"" cellspacing=""3"">"
  ShowHTML "<tr><td colspan=""2"">"
  ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><DIV ALIGN=""LEFT""><IMG src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "EMBED") & """></DIV></TD>"
  ShowHTML "<TD><DIV ALIGN=""RIGHT""><FONT SIZE=4 COLOR=""#000000""><B>"
  If P1 = 1 Then
     ShowHTML "Ficha Resumida da A��o <br> Exerc�cio " & w_ano
  ElseIf P1 = 2 Then
     ShowHTML "Ficha Resumida da A��o <br> Exerc�cio " & w_ano
  Else
     ShowHTML "A��es PPA <br> Exerc�cio " & w_ano
  End If
  ShowHTML "</B></FONT></DIV></TD></TR>"
  'ShowHTML "</FONT><TR><TD ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
  'If w_tipo <> "WORD" Then
  '   ShowHTML "&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Imprimir"" SRC=""images/impressora.jpg"" onClick=""window.print();"">"
  '   ShowHTML "&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Gerar word"" SRC=""images/word.gif"" onClick=""window.open('" & w_pagina & "Visual&R=" & w_pagina & par & "&O=L&w_chave=" & w_chave & "&w_tipo=word&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=1&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") &"','VisualAcaoWord','menubar=yes resizable=yes scrollbars=yes');"">"
  'End If
  ShowHTML "</TABLE></TD></TR>"
  'ShowHTML "<HR>"
  If w_tipo > "" and w_tipo <> "WORD" Then
     ShowHTML "<div align=""center""><b><font size=""1"">Clique <a class=""HL"" href=""javascript:history.back(1);"">aqui</a> para voltar � tela anterior</b></font></div>"
  End If
  
  ' Chama a rotina de visualiza��o dos dados da a��o, na op��o "Listagem"
  ShowHTML VisualAcao(w_chave, "L", w_usuario, P1, P4, "sim", "sim", "sim", "sim", "sim", "sim", "sim", "sim", "sim", "sim", "sim", "sim")

  
  If w_tipo > "" and w_tipo <> "WORD" Then
     ShowHTML "<div align=""center""><b><font size=""1"">Clique <a class=""HL"" href=""javascript:history.back(1);"">aqui</a> para voltar � tela anterior</b></font></div>"
  End If

  ShowHTML "</DIV>"
  ShowHTML "</BODY>"
  ShowHTML "</HTML>"
  
  Set w_tipo                = Nothing 
  Set w_erro                = Nothing 
  Set w_logo                = Nothing 
  Set w_chave               = Nothing

End Sub

REM =========================================================================
REM Rotina de exclus�o
REM -------------------------------------------------------------------------
Sub Excluir

  Dim w_chave, w_chave_pai, w_chave_aux, w_observacao
  
  Dim w_troca, i, w_erro
  
  w_Chave           = Request("w_Chave")
  w_chave_aux       = Request("w_chave_aux")
  w_troca           = Request("w_troca")
  
  If w_troca > "" Then ' Se for recarga da p�gina
     w_observacao     = Request("w_observacao")
  End If

  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=../" & MontaURL("MESA") & """>"
  If InStr("E",O) > 0 Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     Validate "w_assinatura", "Assinatura Eletr�nica", "1", "1", "6", "30", "1", "1"
     If P1 <> 1 Then ' Se n�o for encaminhamento
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

  ' Chama a rotina de visualiza��o dos dados da a��o, na op��o "Listagem"
  ShowHTML VisualAcao(w_chave, "V", w_usuario, P1, P4, "sim", "sim", "sim", "sim", "sim", "sim", "sim", "sim", "sim", "sim", "sim", "sim")

  ShowHTML "<HR>"
  AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"ISACGERAL",R,O
  ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & w_menu & """>"
  DB_GetSolicData_IS RS, w_chave, "ISACGERAL"
  ShowHTML "<INPUT type=""hidden"" name=""w_tramite"" value=""" & RS("sq_siw_tramite") & """>"
  DesconectaBD

  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "  <table width=""97%"" border=""0"">"
  ShowHTML "      <tr><td align=""LEFT"" colspan=4><font size=""1""><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY=""A"" class=""STI"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
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
REM Fim da rotina de exclus�o
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de tramita��o
REM -------------------------------------------------------------------------
Sub Encaminhamento
  
  Dim w_chave, w_chave_pai, w_chave_aux, w_destinatario, w_despacho, w_tramite
  Dim w_sg_tramite, w_novo_tramite, w_tipo
  
  Dim w_troca, i, w_erro
  
  w_Chave           = Request("w_Chave")
  w_chave_aux       = Request("w_chave_aux")
  w_troca           = Request("w_troca")
  w_tipo            = Nvl(Request("w_tipo"),"")
  
  If w_troca > "" Then ' Se for recarga da p�gina
     w_tramite      = Request("w_tramite")
     w_destinatario = Request("w_destinatario")
     w_novo_tramite = Request("w_novo_tramite")
     w_despacho     = Request("w_despacho")
  Else
     DB_GetSolicData_IS RS, w_chave, "ISACGERAL"
     w_tramite      = RS("sq_siw_tramite")
     w_novo_tramite = RS("sq_siw_tramite")
     DesconectaBD
  End If
  
  ' Recupera a sigla do tr�mite desejado, para verificar a lista de poss�veis destinat�rios.
  DB_GetTramiteData RS, w_novo_tramite
  w_sg_tramite   = RS("sigla")
  DesconectaBD

  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=../" & MontaURL("MESA") & """>"
  If InStr("V",O) > 0 Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     Validate "w_destinatario", "Destinat�rio", "HIDDEN", "1", "1", "10", "", "1"
     Validate "w_despacho", "Despacho", "", "1", "1", "2000", "1", "1"
     Validate "w_assinatura", "Assinatura Eletr�nica", "1", "1", "6", "30", "1", "1"
     If P1 <> 1 or (P1 = 1 and w_tipo = "Volta") Then ' Se n�o for encaminhamento e nem o sub-menu do cadastramento
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
  ShowHTML "<div align=""center"">"
  ShowHTML "<table width=""95%"" border=""0"" cellspacing=""3"">"

  ' Chama a rotina de visualiza��o dos dados da a��o, na op��o "Listagem"
  ShowHTML VisualAcao(w_chave, "V", w_usuario, P1, P4, "", "", "", "", "", "", "", "", "", "", "", "")

  ShowHTML "<HR>"
  AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"ISACENVIO",R,O
  ShowHTML MontaFiltro("POST")
  ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & w_menu & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_tramite"" value=""" & w_tramite & """>"

  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "  <table width=""97%"" border=""0"">"
  ShowHTML "    <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
  If P1 <> 1 Then ' Se n�o for cadastramento
     SelecaoFase "<u>F</u>ase da a��o:", "F", "Se deseja alterar a fase atual da a��o, selecione a fase para a qual deseja envi�-la.", w_novo_tramite, w_menu, "w_novo_tramite", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='w_destinatario'; document.Form.submit();"""
     ' Se for envio para o cadastramento, exibe apenas as pessoas autorizadas a faz�-lo.
     If w_sg_tramite = "CI" Then
        SelecaoSolicResp "<u>D</u>estinat�rio:", "D", "Selecione, na rela��o, um destinat�rio para a a��o.", w_destinatario, w_chave, w_novo_tramite, w_novo_tramite, "w_destinatario", "CADASTRAMENTO"
     Else
        SelecaoPessoa "<u>D</u>estinat�rio:", "D", "Selecione, na rela��o, um destinat�rio para a a��o.", w_destinatario, null, "w_destinatario", "USUARIOS"
     End If
  Else
     SelecaoFase "<u>F</u>ase da a��o:", "F", "Se deseja alterar a fase atual da a��o, selecione a fase para a qual deseja envi�-la.", w_novo_tramite, w_menu, "w_novo_tramite", null, null
     SelecaoPessoa "<u>D</u>estinat�rio:", "D", "Selecione, na rela��o, um destinat�rio para a a��o.", w_destinatario, null, "w_destinatario", "USUARIOS"
  End If
  ShowHTML "    <tr><td valign=""top"" colspan=2><font size=""1""><b>D<u>e</u>spacho:</b><br><textarea " & w_Disabled & " accesskey=""E"" name=""w_despacho"" class=""STI"" ROWS=5 cols=75 title=""Informe o que o destinat�rio deve fazer quando receber a a��o."">" & w_despacho & "</TEXTAREA></td>"
  ShowHTML "      </table>"
  ShowHTML "      <tr><td align=""LEFT"" colspan=4><font size=""1""><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY=""A"" class=""STI"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
  ShowHTML "    <tr><td align=""center"" colspan=4><hr>"
  ShowHTML "      <input class=""STB"" type=""submit"" name=""Botao"" value=""Enviar"">"
  If P1 <> 1 Then ' Se n�o for cadastramento
     ' Volta para a listagem
     DB_GetMenuData RS, w_menu
     ShowHTML "      <input class=""STB"" type=""button"" onClick=""location.href='" & replace(RS("link"),w_dir,"") & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & rs("sigla") & MontaFiltro("GET") & "';"" name=""Botao"" value=""Abandonar"">"
     DesconectaBD
  ElseIf P1 = 1 and w_tipo = "Volta" Then
     ShowHTML "      <input class=""STB"" type=""button"" onClick=""location.href='" & R & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"" name=""Botao"" value=""Abandonar"">"  
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
  Set w_tipo            = Nothing
  
  Set w_troca           = Nothing 
  Set i                 = Nothing 
  Set w_erro            = Nothing
End Sub
REM =========================================================================
REM Fim da rotina de encaminhamento
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de anota��o
REM -------------------------------------------------------------------------
Sub Anotar

  Dim w_chave, w_chave_pai, w_chave_aux, w_observacao
  
  Dim w_troca, i, w_erro
  
  w_Chave           = Request("w_Chave")
  w_chave_aux       = Request("w_chave_aux")
  w_troca           = Request("w_troca")
  
  If w_troca > "" Then ' Se for recarga da p�gina
     w_observacao     = Request("w_observacao")
  End If

  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=../" & MontaURL("MESA") & """>"
  If InStr("V",O) > 0 Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     Validate "w_observacao", "Anota��o", "", "1", "1", "2000", "1", "1"
     Validate "w_assinatura", "Assinatura Eletr�nica", "1", "1", "6", "30", "1", "1"
     If P1 <> 1 Then ' Se n�o for encaminhamento
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
  ShowHTML "<div align=""center"">"
  ShowHTML "<table width=""95%"" border=""0"" cellspacing=""3"">"

  ' Chama a rotina de visualiza��o dos dados da a��o, na op��o "Listagem"
  ShowHTML VisualAcao(w_chave, "V", w_usuario, P1, P4, "", "", "", "", "", "", "", "", "", "", "", "")

  ShowHTML "<HR>"
  AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"ISACENVIO",R,O
  ShowHTML MontaFiltro("POST")
  ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & w_menu & """>"
  DB_GetSolicData_IS RS, w_chave, "ISACGERAL"
  ShowHTML "<INPUT type=""hidden"" name=""w_tramite"" value=""" & RS("sq_siw_tramite") & """>"
  DesconectaBD

  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "  <table width=""97%"" border=""0"">"
  ShowHTML "    <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
  ShowHTML "    <tr><td valign=""top""><font size=""1""><b>A<u>n</u>ota��o:</b><br><textarea " & w_Disabled & " accesskey=""N"" name=""w_observacao"" class=""STI"" ROWS=5 cols=75 title=""Redija a anota��o desejada."">" & w_observacao & "</TEXTAREA></td>"
  ShowHTML "      </table>"
  ShowHTML "      <tr><td align=""LEFT"" colspan=4><font size=""1""><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY=""A"" class=""STI"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
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
REM Fim da rotina de anota��o
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de conclus�o
REM -------------------------------------------------------------------------
Sub Concluir

  Dim w_chave, w_chave_pai, w_chave_aux, w_destinatario
  Dim w_inicio_real, w_fim_real, w_nota_conclusao, w_custo_real
  Dim w_cont_m, w_cont_t, w_html
  
  Dim w_troca, i, w_erro
  
  w_Chave           = Request("w_Chave")
  w_chave_aux       = Request("w_chave_aux")
  w_troca           = Request("w_troca")
  
  If w_troca > "" Then ' Se for recarga da p�gina
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
     Validate "w_inicio_real", "In�cio da execu��o", "DATA", 1, 10, 10, "", "0123456789/"
     Validate "w_fim_real", "T�rmino da execu��o", "DATA", 1, 10, 10, "", "0123456789/"
     CompData "w_inicio_real", "In�cio da execu��o", "<=", "w_fim_real", "T�rmino da execu��o"
     CompData "w_fim_real", "T�rmino da execu��o", "<=", FormataDataEdicao(FormatDateTime(Date(),2)), "data atual"
     Validate "w_custo_real", "Recurso executado", "VALOR", "1", 4, 18, "", "0123456789.,"
     Validate "w_nota_conclusao", "Nota de conclus�o", "", "1", "1", "2000", "1", "1"
     Validate "w_assinatura", "Assinatura Eletr�nica", "1", "1", "6", "30", "1", "1"
     If P1 <> 1 Then ' Se n�o for encaminhamento
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
  ShowHTML "<div align=""center"">"
  ShowHTML "<table width=""95%"" border=""0"" cellspacing=""3"">"

  ' Chama a rotina de visualiza��o dos dados da a��o, na op��o "Listagem"
  ShowHTML VisualAcao(w_chave, "V", w_usuario, P1, P4, "", "", "", "", "", "", "", "", "", "", "", "")

  ShowHTML "<HR>"
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "  <table width=""97%"" border=""0"">"
  ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
  ShowHTML "          <tr>"
  
  ' Verifica se a a��o tem etapas em aberto e avisa o usu�rio caso isso ocorra.
  DB_GetSolicMeta_IS RS, w_chave, null, "LISTA", null, null, null, null, null, null, null, null, null
  w_cont_m = 0
  While NOT RS.EOF
     If cDbl(RS("perc_conclusao")) <> 100 Then
        w_cont_m = w_cont_m + 1
     End If
     RS.MoveNext
  Wend
  DesconectaBD
  
  DB_GetLinkData RS, RetornaCliente(), "ISTCAD"
  DB_GetSolicList_IS RS, RS("sq_menu"), w_usuario, "ISTCAD", 5, _
     null, null, null, null, null, null, _
     null, null, null, null, _
     null, null, null, null, null, null, null, _
     null, null, null, null, w_chave, null, null, null, null, null, w_ano
  RS.sort = "ordem, fim, prioridade"
  w_cont_t = 0
  While NOT RS.EOF
     If RS("concluida") <> "S" Then
        w_cont_t = w_cont_t + 1
     End If
     RS.MoveNext
  Wend
  If w_cont_m > 0 or w_cont_t > 0 Then
     ScriptOpen "JavaScript"
     w_html = w_html & "alert('"
     If w_cont_m > 0 Then
        w_html = w_html & "ATEN��O: esta a��o possui " & w_cont_m & " meta(s) com percentual de conclus�o abaixo de 100%!\n\n"
     End If
     If w_cont_t > 0 Then
        w_html = w_html & "ATEN��O: esta a��o possui " & w_cont_t & " tarefa(s) n�o conclu�da(s)!\n\n"
     End If
     w_html = w_html & "Ainda assim voc� poder� concluir esta a��o.');"
     ShowHTML w_html
     ScriptClose
  End If
  DesconectaBD
  AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"ISACCONC",R,O
  ShowHTML MontaFiltro("POST")
  ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & w_menu & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_concluida"" value=""S"">"
  DB_GetSolicData_IS RS, w_chave, "ISACGERAL"
  ShowHTML "<INPUT type=""hidden"" name=""w_tramite"" value=""" & RS("sq_siw_tramite") & """>"
  If Nvl(RS("cd_acao"),"") > "" Then
     ShowHTML "              <td valign=""top""><font size=""1""><b>In�<u>c</u>io da execu��o:</b><br><input readonly " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_inicio_real"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & Nvl(w_inicio_real, "01/01/"&w_ano) & """ onKeyDown=""FormataData(this,event);"" title=""Informe a data de in�cio da execu��o da a��o.(Usar formato dd/mm/aaaa)""></td>"
     ShowHTML "              <td valign=""top""><font size=""1""><b><u>T</u>�rmino da execu��o:</b><br><input readonly " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_fim_real"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & Nvl(w_fim_real, "31/12/"&w_ano) & """ onKeyDown=""FormataData(this,event);"" title=""Informe a data de t�rmino da execu��o da a��o.(Usar formato dd/mm/aaaa)""></td>"
  Else
     ShowHTML "              <td valign=""top""><font size=""1""><b>In�<u>c</u>io da execu��o:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_inicio_real"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & Nvl(w_inicio_real, "01/01/"&w_ano) & """ onKeyDown=""FormataData(this,event);"" title=""Informe a data de in�cio da execu��o da a��o.(Usar formato dd/mm/aaaa)""></td>"
     ShowHTML "              <td valign=""top""><font size=""1""><b><u>T</u>�rmino da execu��o:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_fim_real"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & Nvl(w_fim_real, "31/12/"&w_ano) & """ onKeyDown=""FormataData(this,event);"" title=""Informe a data de t�rmino da execu��o da a��o.(Usar formato dd/mm/aaaa)""></td>"  
  End If
  DesconectaBD  
  ShowHTML "              <td valign=""top""><font size=""1""><b><u>R</u>ecurso executado:</b><br><input " & w_Disabled & " accesskey=""O"" type=""text"" name=""w_custo_real"" class=""STI"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_custo_real & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o valor que foi efetivamente gasto com a execu��o da a��o.""></td>"
  ShowHTML "          </table>"
  ShowHTML "    <tr><td valign=""top""><font size=""1""><b>Nota d<u>e</u> conclus�o:</b><br><textarea " & w_Disabled & " accesskey=""E"" name=""w_nota_conclusao"" class=""STI"" ROWS=5 cols=75 title=""Insira informa��es relevantes sobre o encerramento do exerc�cio."">" & w_nota_conclusao & "</TEXTAREA></td>"
  ShowHTML "      <tr><td align=""LEFT"" colspan=4><font size=""1""><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY=""A"" class=""STI"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
  ShowHTML "    <tr><td align=""center"" colspan=4><hr>"
  ShowHTML "      <input class=""STB"" type=""submit"" name=""Botao"" value=""Concluir"">"
  If P1 <> 1 Then ' Se n�o for cadastramento
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
  Set w_cont_m          = Nothing
  Set w_cont_t          = Nothing
  Set w_html            = Nothing
End Sub
REM =========================================================================
REM Fim da rotina de conclus�o
REM -------------------------------------------------------------------------

REM =========================================================================
REM Gera uma linha de apresenta��o da tabela de etapas
REM -------------------------------------------------------------------------
Function Metalinha (p_chave,  p_chave_aux, p_titulo, p_resp,  p_setor, _
                     p_inicio, p_fim,      p_perc,   p_word,  p_destaque, _
                     p_oper,   p_tipo,     p_loa)
  Dim l_html, RsQuery, l_row

  If p_loa > "" Then
     p_loa = "Sim"
  Else
     p_loa = "N�o"
  End If 
  l_row = ""

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
  If cDbl(Nvl(p_word,0)) = 1 Then
     l_html = l_html & VbCrLf & "        <td><font size=""1"">" & p_destaque & p_titulo & "</b>"
  Else
     l_html = l_html & VbCrLf & "<A class=""HL"" HREF=""#"" onClick=""window.open('Acao.asp?par=AtualizaMeta&O=V&w_chave=" & p_chave & "&w_chave_aux=" & p_chave_aux & "&w_tipo=Volta&P1=10&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "','Meta','width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no'); return false;"" title=""Clique para exibir os dados!"">" & p_destaque & p_titulo & "</A>"
  End If
  l_html = l_html & VbCrLf & "        <td align=""center"" " & l_row & "><font size=""1"">" & p_loa & "</td>"
  l_html = l_html & VbCrLf & "        <td align=""center"" " & l_row & "><font size=""1"">" & FormataDataEdicao(p_fim) & "</td>"
  l_html = l_html & VbCrLf & "        <td nowrap align=""right"" " & l_row & "><font size=""1"">" & p_perc & " %</td>"
  If p_oper = "S" Then
     l_html = l_html & VbCrLf & "        <td align=""top"" nowrap " & l_row & "><font size=""1"">"
     ' Se for listagem de metas no cadastramento da a��o, exibe opera��es de altera��o e exclus�o
     If p_tipo = "PROJETO" Then
        l_html = l_html & VbCrLf & "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & p_chave & "&w_chave_aux=" & p_chave_aux & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Alterar"">Alt</A>&nbsp"
        If p_loa = "N�o" Then
           l_html = l_html & VbCrLf & "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "GRAVA&R=" & w_pagina & par & "&O=E&w_chave=" & p_chave & "&w_chave_aux=" & p_chave_aux & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ onClick=""return confirm('Confirma a exclus�o do registro?');"" title=""Excluir"">Excl</A>&nbsp"
        End If
     ' Caso contr�rio, � listagem de atualiza��o de metas. Neste caso, coloca apenas a op��o de altera��o
     Else
        l_html = l_html & VbCrLf & "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & p_chave & "&w_chave_aux=" & p_chave_aux & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Atualiza dados da meta"">Atualizar</A>&nbsp"
     End If
     l_html = l_html & VbCrLf & "        </td>"
  Else
     If p_tipo = "ETAPA" Then
        l_html = l_html & VbCrLf & "        <td align=""top"" nowrap " & l_row & "><font size=""1"">"
        l_html = l_html & VbCrLf & "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=V&w_chave=" & p_chave & "&w_chave_aux=" & p_chave_aux & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Exibe os dados da meta"">Exibir</A>&nbsp"
        l_html = l_html & VbCrLf & "        </td>"
     End If
  End If
  l_html = l_html & VbCrLf &  "      </tr>"
  metalinha = l_html

  Set l_row     = Nothing
  Set l_html    = Nothing
End Function
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de prepara��o para envio de e-mail relativo a projetos
REM Finalidade: preparar os dados necess�rios ao envio autom�tico de e-mail
REM Par�metro: p_solic: n�mero de identifica��o da solicita��o. 
REM            p_tipo:  1 - Inclus�o
REM                     2 - Tramita��o
REM                     3 - Conclus�o
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
     w_html = w_html & "      <tr valign=""top""><td align=""center""><font size=2><b>INCLUS�O DE A��O</b></font><br><br><td></tr>" & VbCrLf
  ElseIf p_tipo = 2 Then
     w_html = w_html & "      <tr valign=""top""><td align=""center""><font size=2><b>TRAMITA��O DE A��O</b></font><br><br><td></tr>" & VbCrLf
  ElseIf p_tipo = 3 Then
     w_html = w_html & "      <tr valign=""top""><td align=""center""><font size=2><b>CONCLUS�O DE A��O</b></font><br><br><td></tr>" & VbCrLf
  End IF
  w_html = w_html & "      <tr valign=""top""><td><font size=2><b><font color=""#BC3131"">ATEN��O</font>: Esta � uma mensagem de envio autom�tico. N�o responda esta mensagem.</b></font><br><br><td></tr>" & VbCrLf


  ' Recupera os dados da a��o
  DB_GetSolicData_IS RSM, p_solic, "ISACGERAL"
  w_nome = "A��o " & RSM("titulo")

  w_html = w_html & VbCrLf & "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  w_html = w_html & VbCrLf & "    <table width=""99%"" border=""0"">"
  w_html = w_html & VbCrLf & "      <tr><td><font size=2>A��o: <b>" & RSM("titulo") & "</b></font></td>"
      
  ' Identifica��o da a��o
  w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>EXTRATO DA A��O</td>"
  ' Se a classifica��o foi informada, exibe.
  If Not IsNull(RSM("sq_cc")) Then
     w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Classifica��o:<br><b>" & RSM("cc_nome") & " </b></td>"
  End If
  w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
  w_html = w_html & VbCrLf & "          <tr valign=""top"">"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Respons�vel pelo monitoramento:<br><b>" & RSM("nm_sol") & "</b></td>"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Unidade respons�vel pelo monitoramento:<br><b>" & RSM("nm_unidade_resp") & "</b></td>"
  w_html = w_html & VbCrLf & "          <tr valign=""top"">"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Data de recebimento:<br><b>" & FormataDataEdicao(RSM("inicio")) & " </b></td>"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Limite para conclus�o:<br><b>" & FormataDataEdicao(RSM("fim")) & " </b></td>"
  w_html = w_html & VbCrLf & "          </table>"

  ' Informa��es adicionais
  If Nvl(RSM("descricao"),"") > "" Then 
     If Nvl(RSM("descricao"),"") > "" Then w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Resultados da a��o:<br><b>" & CRLF2BR(RSM("descricao")) & " </b></td>" End If
  End If

  w_html = w_html & VbCrLf & "    </table>"
  w_html = w_html & VbCrLf & "</tr>"

  ' Dados da conclus�o da a��o, se ela estiver nessa situa��o
  If RSM("concluida") = "S" and Nvl(RSM("data_conclusao"),"") > "" Then
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>DADOS DA CONCLUS�O</td>"
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
     w_html = w_html & VbCrLf & "          <tr valign=""top"">"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">In�cio da execu��o:<br><b>" & FormataDataEdicao(RSM("inicio_real")) & " </b></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">T�rmino da execu��o:<br><b>" & FormataDataEdicao(RSM("fim_real")) & " </b></td>"
     w_html = w_html & VbCrLf & "          </table>"
     w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Nota de conclus�o:<br><b>" & CRLF2BR(RSM("nota_conclusao")) & " </b></td>"
  End If

  If p_tipo = 2 Then ' Se for tramita��o
     ' Encaminhamentos
     DB_GetSolicLog RS, p_solic, null, "LISTA"
     RS.Sort = "data desc"
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>�LTIMO ENCAMINHAMENTO</td>"
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
     w_html = w_html & VbCrLf & "          <tr valign=""top"">"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">De:<br><b>" & RS("responsavel") & "</b></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Para:<br><b>" & RS("destinatario") & "</b></td>"
     w_html = w_html & VbCrLf & "          <tr valign=""top""><td colspan=2><font size=""1"">Despacho:<br><b>" & CRLF2BR(Nvl(RS("despacho"),"---")) & " </b></td>"
     w_html = w_html & VbCrLf & "          </table>"
     
     ' Configura o destinat�rio da tramita��o como destinat�rio da mensagem
     DB_GetPersonData RS, w_cliente, RS("sq_pessoa_destinatario"), null, null
     w_destinatarios = RS("email") & "; "
     
     DesconectaBD
  End If

  w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>OUTRAS INFORMA��ES</td>"
  DB_GetCustomerSite RS, Session("p_cliente")
  w_html = w_html & "      <tr valign=""top""><td><font size=2>" & VbCrLf
  w_html = w_html & "         Para acessar o sistema use o endere�o: <b><a class=""SS"" href=""" & RS("logradouro") & """ target=""_blank"">" & RS("Logradouro") & "</a></b></li>" & VbCrLf
  w_html = w_html & "      </font></td></tr>" & VbCrLf
  DesconectaBD

  w_html = w_html & "      <tr valign=""top""><td><font size=2>" & VbCrLf
  w_html = w_html & "         Dados da ocorr�ncia:<br>" & VbCrLf
  w_html = w_html & "         <ul>" & VbCrLf
  w_html = w_html & "         <li>Respons�vel: <b>" & Session("nome") & "</b></li>" & VbCrLf
  w_html = w_html & "         <li>Data do servidor: <b>" & FormatDateTime(Date(),1) & ", " & Time() & "</b></li>" & VbCrLf
  w_html = w_html & "         <li>IP de origem: <b>" & Request.ServerVariables("REMOTE_HOST") & "</b></li>" & VbCrLf
  w_html = w_html & "         </ul>" & VbCrLf
  w_html = w_html & "      </font></td></tr>" & VbCrLf
  w_html = w_html & "    </table>" & VbCrLf
  w_html = w_html & "</td></tr>" & VbCrLf
  w_html = w_html & "</table>" & VbCrLf
  w_html = w_html & "</BODY>" & VbCrLf
  w_html = w_html & "</HTML>" & VbCrLf

  ' Recupera o e-mail do respons�vel
  DB_GetPersonData RS, w_cliente, RSM("solicitante"), null, null
  If Instr(w_destinatarios,RS("email") & "; ") = 0 Then w_destinatarios = w_destinatarios & RS("email") & "; " End If
  DesconectaBD
  
  ' Recupera o e-mail do titular e do substituto pelo setor respons�vel
  DB_GetUorgResp RS, RSM("sq_unidade")
  If Instr(w_destinatarios,RS("email_titular") & "; ") = 0    and Nvl(RS("email_titular"),"nulo") <> "nulo"    Then w_destinatarios = w_destinatarios & RS("email_titular") & "; "    End If
  If Instr(w_destinatarios,RS("email_substituto") & "; ") = 0 and Nvl(RS("email_substituto"),"nulo") <> "nulo" Then w_destinatarios = w_destinatarios & RS("email_substituto") & "; " End If
  DesconectaBD
  
  ' Prepara os dados necess�rios ao envio
  DB_GetCustomerData RS, Session("p_cliente")
  If p_tipo = 1 or p_tipo = 3 Then ' Inclus�o ou Conclus�o
     If p_tipo = 1 Then w_assunto = "Inclus�o - " & w_nome Else w_assunto = "Conclus�o - " & w_nome End If
  ElseIf p_tipo = 2 Then ' Tramita��o
     w_assunto = "Tramita��o - " & w_nome
  End If
  DesconectaBD

  If w_destinatarios > "" Then
     ' Executa o envio do e-mail
     w_resultado = EnviaMail(w_assunto, w_html, w_destinatarios, null)
  End If
        
  ' Se ocorreu algum erro, avisa da impossibilidade de envio
  If w_resultado > "" Then 
     ScriptOpen "JavaScript"
     ShowHTML "  alert('ATEN��O: n�o foi poss�vel proceder o envio do e-mail.\n" & w_resultado & "');" 
     ScriptClose
  End If

  Set RSM                      = Nothing
  Set w_html                   = Nothing
  Set p_solic                  = Nothing
  Set w_destinatarios          = Nothing
  Set w_assunto                = Nothing
End Sub
REM =========================================================================
REM Fim da rotina da prepara��o para envio de e-mail
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de prepara��o para envio de e-mail relativo restri��es
REM Finalidade: preparar os dados necess�rios ao envio autom�tico de e-mail
REM Par�metro: p_solic: n�mero de identifica��o da solicita��o. 
REM            p_tipo:  I - Inclus�o
REM                     E - Exclus�o
REM -------------------------------------------------------------------------
Sub RestricaoMail(p_solic, p_descricao, p_tp_restricao, p_providencia, p_tipo)

  Dim w_cab, w_html, w_texto, w_sq_rest, RSM, w_resultado, w_destinatarios
  Dim w_assunto, w_assunto1, l_sq_rest, w_nome
  
  w_destinatarios = ""
  w_resultado     = ""
  
  w_html = "<HTML>" & VbCrLf
  w_html = w_html & BodyOpenMail(null) & VbCrLf
  w_html = w_html & "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">" & VbCrLf
  w_html = w_html & "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">" & VbCrLf
  w_html = w_html & "    <table width=""97%"" border=""0"">" & VbCrLf
  If p_tipo = "I" Then
     w_html = w_html & "      <tr valign=""top""><td align=""center""><font size=2><b>INCLUS�O DE RESTRI��O</b></font><br><br><td></tr>" & VbCrLf
  ElseIf p_tipo = "E" Then
     w_html = w_html & "      <tr valign=""top""><td align=""center""><font size=2><b>EXCLUS�O DE RESTRI��O</b></font><br><br><td></tr>" & VbCrLf
  End If
  w_html = w_html & "      <tr valign=""top""><td><font size=2><b><font color=""#BC3131"">ATEN��O</font>: Esta � uma mensagem de envio autom�tico. N�o responda esta mensagem.</b></font><br><br><td></tr>" & VbCrLf


  ' Recupera os dados do programa
  DB_GetSolicData_IS RSM, p_solic, "ISACGERAL"

  w_html = w_html & VbCrLf & "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  w_html = w_html & VbCrLf & "    <table width=""99%"" border=""0"">"
  w_html = w_html & VbCrLf & "      <tr><td><font size=2>A��o: <b>" & RSM("cd_unidade") & "." & RSM("cd_programa") & "." &  RSM("cd_acao") & " - " & RSM("nm_ppa") & "</b></font></td>"
      
  ' Identifica��o da a��o
  w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>EXTRATO DA A��O</td>"
  w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
  w_html = w_html & VbCrLf & "          <tr valign=""top"">"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Respons�vel pelo monitoramento:<br><b>" & RSM("nm_sol") & "</b></td>"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Unidade respons�vel pelo monitoramento:<br><b>" & RSM("nm_unidade_resp") & "</b></td>"
  w_html = w_html & VbCrLf & "          <tr valign=""top"">"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Data de recebimento:<br><b>" & FormataDataEdicao(RSM("inicio")) & " </b></td>"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Limite para conclus�o:<br><b>" & FormataDataEdicao(RSM("fim")) & " </b></td>"
  w_html = w_html & VbCrLf & "          </table>"
  
  ' Recupera o e-mail do respons�vel
  DB_GetPersonData RS, w_cliente, RSM("solicitante"), null, null
  If Instr(w_destinatarios,RS("email") & "; ") = 0 Then w_destinatarios = w_destinatarios & RS("email") & "; " End If
  DesconectaBD


  RSM.Close
  
  ' Identifica��o da restri��o
  w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>EXTRATO DA RESTRI��O</td>"
  w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
  w_html = w_html & VbCrLf & "          <tr valign=""top"">"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Descri��o da restri��o:<br><b>" & p_descricao & "</b></td>"
  DB_GetTPRestricao_IS RSM, p_tp_restricao, null
  w_html = w_html & VbCrLf & "          <tr valign=""top"">"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Tipo da restri��o:<br><b>" & RSM("nome") & "</b></td>"
  RSM.Close
  w_html = w_html & VbCrLf & "          <tr valign=""top"">"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Provid�ncia:<br><b>" & Nvl(p_providencia,"---") & "</b></td>"
  w_html = w_html & VbCrLf & "          </table>"
  
  w_html = w_html & VbCrLf & "    </table>"
  w_html = w_html & VbCrLf & "</tr>"
  
    w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>OUTRAS INFORMA��ES</td>"
  DB_GetCustomerSite RS, Session("p_cliente")
  w_html = w_html & "      <tr valign=""top""><td><font size=2>" & VbCrLf
  w_html = w_html & "         Para acessar o sistema use o endere�o: <b><a class=""SS"" href=""" & RS("logradouro") & """ target=""_blank"">" & RS("Logradouro") & "</a></b></li>" & VbCrLf
  w_html = w_html & "      </font></td></tr>" & VbCrLf
  DesconectaBD

  w_html = w_html & "      <tr valign=""top""><td><font size=2>" & VbCrLf
  w_html = w_html & "         Dados da ocorr�ncia:<br>" & VbCrLf
  w_html = w_html & "         <ul>" & VbCrLf
  w_html = w_html & "         <li>Respons�vel: <b>" & Session("nome") & "</b></li>" & VbCrLf
  w_html = w_html & "         <li>Data do servidor: <b>" & FormatDateTime(Date(),1) & ", " & Time() & "</b></li>" & VbCrLf
  w_html = w_html & "         <li>IP de origem: <b>" & Request.ServerVariables("REMOTE_HOST") & "</b></li>" & VbCrLf
  w_html = w_html & "         </ul>" & VbCrLf
  w_html = w_html & "      </font></td></tr>" & VbCrLf
  w_html = w_html & "    </table>" & VbCrLf
  w_html = w_html & "</td></tr>" & VbCrLf
  w_html = w_html & "</table>" & VbCrLf
  w_html = w_html & "</BODY>" & VbCrLf
  w_html = w_html & "</HTML>" & VbCrLf
    
  ' Recupera o e-mail do usu�rio que est� cadastrando a restri��o
  DB_GetPersonData RS, w_cliente, Session("sq_pessoa"), null, null
  If Instr(w_destinatarios,RS("email") & "; ") = 0 Then w_destinatarios = w_destinatarios & RS("email") & "; " End If
  DesconectaBD
  
  ' Recupera o e-mail dos interessados
  DB_GetSolicInter RSM, p_solic, null, "LISTA"
  If Not RSM.EOF Then
     While Not RSM.EOF 
        DB_GetPersonData RS, w_cliente, RSM("sq_pessoa"), null, null
        If Instr(w_destinatarios,RS("email") & "; ") = 0 Then w_destinatarios = w_destinatarios & RS("email") & "; " End If
        DesconectaBD
        RSM.MoveNext
    Wend
  End If
  RSM.Close
  
  ' Prepara os dados necess�rios ao envio
  DB_GetCustomerData RS, Session("p_cliente")
  If p_tipo = "I" Then ' Inclus�o
     If p_tipo = "I" Then w_assunto = "Inclus�o de restri��o do programa" End If
  ElseIf p_tipo = "E" Then ' Exclus�o
     w_assunto = "Exclus�o de restri��o do programa"
  End If
  DesconectaBD
 
  If w_destinatarios > "" Then
     ' Executa o envio do e-mail
     w_resultado = EnviaMail(w_assunto, w_html, w_destinatarios, null)
  End If
        
  ' Se ocorreu algum erro, avisa da impossibilidade de envio
  If w_resultado > "" Then 
     ScriptOpen "JavaScript"
     ShowHTML "  alert('ATEN��O: n�o foi poss�vel proceder o envio do e-mail.\n" & w_resultado & "');" 
     ScriptClose
  End If

  Set RSM                      = Nothing
  Set w_html                   = Nothing
  Set p_solic                  = Nothing
  Set w_destinatarios          = Nothing
  Set w_assunto                = Nothing
End Sub
REM =========================================================================
REM Fim da rotina da prepara��o para envio de e-mail
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de busca das a��es do PPA
REM -------------------------------------------------------------------------
Sub BuscaAcao
 
  Dim w_nome, w_cliente, w_ano, w_programa, w_acao, w_unidade, w_chave, ChaveAux, restricao, campo
  
  w_nome     = UCase(Request("w_nome"))
  w_cliente  = Request("w_cliente")
  w_ano      = Request("w_ano")
  w_programa = Request("w_programa")
  w_acao     = Request("w_acao")
  w_unidade  = Request("w_unidade")
  w_chave    = Request("w_chave")
  ChaveAux   = Request("ChaveAux")
  restricao  = Request("restricao")
  campo      = Request("campo")
  
  If restricao = "FINANCIAMENTO" Then
     DB_GetAcaoPPA_IS RS, w_cliente, w_ano, w_programa, ChaveAux, null , w_unidade, restricao, w_chave, w_nome
     RS.Sort   = "descricao_acao"
  ElseIf restricao = "IDENTIFICACAO" Then
     DB_GetAcaoPPA_IS RS, w_cliente, w_ano, null, ChaveAux, null, null, restricao, null, w_nome
     RS.Sort   = "descricao_acao"
  Else
     DB_GetAcaoPPA_IS RS, w_cliente, w_ano, w_programa, ChaveAux, null, w_unidade, null, null, w_nome
     RS.Sort   = "descricao_acao"
  End If
    
  Cabecalho
  ShowHTML "<TITLE>Sele��o de a��es do PPA</TITLE>"
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente
  ScriptOpen "JavaScript"
  ShowHTML "  function volta(l_chave) {"
  ShowHTML "     opener.Form." & campo & ".value=l_chave;"
  ShowHTML "     opener.Form." & campo & ".focus();"
  ShowHTML "     window.close();"
  ShowHTML "     opener.focus();"
  ShowHTML "   }"
  ValidateOpen "Validacao"
  Validate "w_nome", "Nome", "1", "", "4", "100", "1", "1"
  Validate "ChaveAux", "C�digo", "1", "", "4", "4", "1", "1"
  ShowHTML "  if (theForm.w_nome.value == '' && theForm.ChaveAux.value == '') {"
  ShowHTML "     alert ('Informe um valor para o nome ou para o c�digo!');"
  ShowHTML "     theForm.w_nome.focus();"
  ShowHTML "     return false;"
  ShowHTML "  }"
  ShowHTML "  theForm.Botao[0].disabled=true;"
  ShowHTML "  theForm.Botao[1].disabled=true;"
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  BodyOpen "onLoad='document.Form.w_nome.focus();'"
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
  ShowHTML "    <table width=""100%"" border=""0"">"
  AbreForm  "Form", w_dir&w_Pagina&"BuscaAcao", "POST", "return(Validacao(this))", null, P1, P2, P3, P4, TP, SG, null, null
  ShowHTML "<INPUT type=""hidden"" name=""w_cliente"" value=""" & w_cliente &""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_ano"" value=""" & w_ano &""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_programa"" value=""" & w_programa &""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_unidade"" value=""" & w_unidade &""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_acao"" value=""" & w_acao &""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave &""">"
  ShowHTML "<INPUT type=""hidden"" name=""restricao"" value=""" & restricao &""">"
  ShowHTML "<INPUT type=""hidden"" name=""campo"" value=""" & campo &""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & w_menu &""">"
  
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2><b><ul>Instru��es</b>:<li>Informe parte do nome da a��o ou o c�digo da a��o.<li>Quando a rela��o for exibida, selecione a a��o desejada clicando sobre o link <i>Selecionar</i>.<li>Ap�s informar o nome da a��o ou o c�digo da a��o, clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Cancelar</i>, a procura � cancelada.</ul></div>"
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
  ShowHTML "    <table width=""100%"" border=""0"">"
  ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Parte do <U>n</U>ome da a��o:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_nome"" size=""50"" maxlength=""100"" value=""" & w_nome & """>"
  ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>C</U>�digo da a��o:<br><INPUT ACCESSKEY=""S"" " & w_Disabled & " class=""sti"" type=""text"" name=""ChaveAux"" size=""5"" maxlength=""4"" value=""" & ChaveAux & """>"
  
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
  If w_nome > "" or ChaveAux > "" Then
     ShowHTML "<tr><td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
     ShowHTML "<tr><td>"
     ShowHTML "    <TABLE WIDTH=""100%"" border=0>"
     If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=5 align=""center""><font size=""1""><b>N�o foram encontrados registros.</b></td></tr>"
     Else
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td>"
        ShowHTML "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        ShowHTML "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        ShowHTML "            <td><font size=""1""><b>C�digo</font></td>"
        ShowHTML "            <td><font size=""1""><b>Nome</font></td>"
        ShowHTML "            <td><font size=""1""><b>Opera��es</font></td>"
        ShowHTML "          </tr>"
        While Not RS.EOF
           If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
           ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
           ShowHTML "            <td align=""center""><font size=""1"">" & RS("cd_unidade") & "." & RS("cd_programa") & "." & RS("cd_acao")& "</td>"
           ShowHTML "            <td><font size=""1"">" & RS("descricao_acao") & "</td>"
           ShowHTML "            <td><font size=""1""><a class=""ss"" href=""#"" onClick=""javascript:volta('" & RS("chave") & "');"">Selecionar</a>"
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
  DesConectaBD	 
  ShowHTML "    </table>"
  ShowHTML "    </TD>"
  ShowHTML "</tr>"
  ShowHTML "</FORM>"  
  ShowHTML "</table>"
  ShowHTML "</center>"
  Estrutura_Texto_Fecha

  Set w_nome                = Nothing
  Set ChaveAux              = Nothing
      
End Sub
REM =========================================================================
REM Fim da rotina de busca de �rea do conhecimento
REM -------------------------------------------------------------------------

REM =========================================================================
REM Procedimento que executa as opera��es de BD
REM -------------------------------------------------------------------------
Public Sub Grava
  Dim p_sq_endereco_unidade
  Dim w_quantitativo_total, w_perc_conclusao, i
  Dim p_modulo
  Dim w_Null
  Dim w_chave_nova
  Dim w_mensagem
  Dim FS, F1, w_file, w_tamanho, w_tipo, w_nome, field, w_maximo
  
  w_file    = ""
  w_tamanho = ""
  w_tipo    = ""
  w_nome    = ""
  
  Cabecalho
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  BodyOpen "onLoad=document.focus();"
  
  AbreSessao    
   
  If SG = "ISACGERAL" or SG = "VLRAGERAL" Then
     ' Verifica se a Assinatura Eletr�nica � v�lida
     If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then
        If O = "I" and  Request("w_sq_acao_ppa") > "" Then
           DB_GetAcao_IS RS, Mid(Request("w_sq_acao_ppa"),1,4), Mid(Request("w_sq_acao_ppa"),5,4), Mid(Request("w_sq_acao_ppa"),13,5), w_ano, w_cliente, null, null
           If cDbl(RS("Existe")) > 0 Then
              ScriptOpen "JavaScript"
              ShowHTML "  alert('A��o j� cadastrada!');"
              ShowHTML "  history.back(1);"
              ScriptClose
              Exit Sub
           End If 
        ElseIf O = "E" Then
           ' Se for opera��o de exclus�o, verifica se � necess�rio excluir os arquivos f�sicos
           DB_GetSolicLog RS, Request("w_chave"), null, "LISTA"
           ' Mais de um registro de log significa que deve ser cancelada, e n�o exclu�da.
           ' Nessa situa��o, n�o � necess�rio excluir os arquivos.
           If RS.RecordCount <= 1 Then
              DB_GetSolicAnexo RS, Request("w_chave"), null, w_cliente
              While Not RS.EOF
                Set FS = CreateObject("Scripting.FileSystemObject")
                If FS.FileExists(conFilePhysical & w_cliente & "\" & RS("caminho")) Then
                   FS.DeleteFile conFilePhysical & w_cliente & "\" & RS("caminho")
                End If
                RS.MoveNext
              Wend 
           End If
        End If
        'Recupera 10% dos dias de prazo da tarefa, para emitir o alerta 
        Dim w_dias
        DB_Get10PercentDays_IS RS,Request("w_inicio"), Request("w_fim")
        w_dias = RS("dias")
        DesconectaBD
        DML_PutAcaoGeral_IS O, _
            Request("w_chave"), Request("w_menu"), Session("lotacao"), Request("w_solicitante"), Request("w_proponente"), _
            Session("sq_pessoa"), null, Request("w_descricao"), Request("w_justificativa"), Request("w_inicio"), Request("w_fim"), Request("w_valor"), _
            Request("w_data_hora"), Request("w_sq_unidade_resp"), Request("w_titulo"), Request("w_prioridade"), Request("w_aviso"), w_dias, _
            Request("w_cidade"), Request("w_palavra_chave"), _
            null, null, null, null, null, null, null, _
            w_ano, w_cliente, Mid(Request("w_sq_acao_ppa"),1,4), Mid(Request("w_sq_acao_ppa"),5,4), Mid(Request("w_sq_acao_ppa"),9,4), Mid(Request("w_sq_acao_ppa"),13,5), Request("w_sq_isprojeto"), Request("w_selecao_mp"), Request("w_selecao_se"), _
            null, null, w_chave_nova, w_copia, Request("w_sq_unidade_adm"), null
          
        If O = "I" Then
           ' Envia e-mail comunicando a inclus�o
           SolicMail Nvl(Request("w_chave"), w_chave_nova) ,1
        End If
        ScriptOpen "JavaScript"
        If O = "I" Then
           ' Exibe mensagem de grava��o com sucesso
           If Nvl(Request("w_sq_acao_ppa"),"") = "" Then
              ShowHTML "  alert('A��o " & w_chave_nova & " cadastrada com sucesso!');"
           Else
              ShowHTML "  alert('A��o " & Mid(Request("w_sq_acao_ppa"),13,5)& "." & Mid(Request("w_sq_acao_ppa"),1,4) & "." & Mid(Request("w_sq_acao_ppa"),5,4) & " cadastrada com sucesso!');"
           End If
           ' Recupera os dados para montagem correta do menu
           DB_GetMenuData RS1, w_menu
           ShowHTML "  parent.menu.location='../Menu.asp?par=ExibeDocs&O=A&w_chave=" & w_chave_nova & "&w_documento=Nr. " & w_chave_nova & "&R=" & R & "&SG=" & RS1("sigla") & "&TP=" & TP & MontaFiltro("GET") & "';"
        ElseIf O = "E" Then
           ShowHTML "  location.href='" & R & "&O=L&R=" & R & "&SG=ISACAD&w_menu=" & w_menu & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & MontaFiltro("GET") & "';"
        Else
           If SG = "VLRAGERAL" Then
              O = "P"
           End If
           ' Aqui deve ser usada a vari�vel de sess�o para evitar erro na recupera��o do link
           DB_GetLinkData RS1, Session("p_cliente"), SG
           ShowHTML "  location.href='" & replace(RS1("link"),w_dir,"") & "&O=" & O & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
        End If
        ScriptClose
     Else
        ScriptOpen "JavaScript"
        ShowHTML "  alert('Assinatura Eletr�nica inv�lida!');"
        ShowHTML "  history.back(1);"
        ScriptClose
     End If
  ElseIf SG = "ISACRESP" Then  
     ' Verifica se a Assinatura Eletr�nica � v�lida
     If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then

        DML_PutRespAcao_IS Request("w_chave_aux"), Request("w_responsavel"), Request("w_telefone"), Request("w_email"), Request("w_tipo")
        
        ScriptOpen "JavaScript"
        ' Recupera a sigla do servi�o pai, para fazer a chamada ao menu
        DB_GetLinkData RS, Session("p_cliente"), SG
        ShowHTML "  location.href='" & replace(RS("link"),w_dir,"") & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
        DesconectaBD
        ScriptClose
     Else
        ScriptOpen "JavaScript"
        ShowHTML "  alert('Assinatura Eletr�nica inv�lida!');"
        ShowHTML "  history.back(1);"
        ScriptClose
     End If    
  ElseIf SG = "ISACPROQUA" Then
     ' Verifica se a Assinatura Eletr�nica � v�lida
     If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then

        DML_PutProgQualitativa_IS _
            Request("w_chave"), null, Request("w_observacao"),  null, Request("w_problema"), _
            Request("w_objetivo"), Request("w_publico_alvo"), Request("w_estrategia"), _
            Request("w_sistematica"), Request("w_metodologia"), SG
          
        ScriptOpen "JavaScript"
        If O = "I" Then
           ' Recupera os dados para montagem correta do menu
           DB_GetMenuData RS1, w_menu
           ShowHTML "  parent.menu.location='../Menu.asp?par=ExibeDocs&O=A&w_chave=" & w_chave_nova & "&w_documento=Nr. " & w_chave_nova & "&R=" & R & "&SG=" & RS1("sigla") & "&TP=" & TP & MontaFiltro("GET") & "';"
        Else
           ' Aqui deve ser usada a vari�vel de sess�o para evitar erro na recupera��o do link
           DB_GetLinkData RS1, Session("p_cliente"), SG
           ShowHTML "  location.href='" & replace(RS1("link"),w_dir,"") & "&O=" & O & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
        End If
        ScriptClose
     Else
        ScriptOpen "JavaScript"
        ShowHTML "  alert('Assinatura Eletr�nica inv�lida!');"
        ShowHTML "  history.back(1);"
        ScriptClose
     End If
  ElseIf SG = "ISMETA" Then
     ' Verifica se a Assinatura Eletr�nica � v�lida
     If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then
        dbms.BeginTrans()
        DML_PutAcaoMeta_IS O, Request("w_chave"), Request("w_chave_aux"), _
           Request("w_titulo"), Request("w_descricao"), Request("w_ordem"), Request("w_inicio"), _
           Request("w_fim"), Request("w_perc_conclusao"), Request("w_orcamento"), _
           Request("w_programada"),Request("w_cumulativa"),Request("w_quantidade"),Request("w_unidade_medida")
        'For i = 1 to 12
        '   DML_PutMetaMensalIni_IS "W", Request("w_chave_aux"), Trim(Request("w_cron_ini_"&i&"")), Request("w_referencia_"&i&""), w_cliente
        'Next 
        If O <> "E" Then
           DML_PutMetaMensalIni_IS "W", Request("w_chave_aux"), w_cliente, _
              Trim(Request("w_cron_ini_1")), Trim(Request("w_cron_ini_2")), Trim(Request("w_cron_ini_3")), Trim(Request("w_cron_ini_4")), _
              Trim(Request("w_cron_ini_5")), Trim(Request("w_cron_ini_6")), Trim(Request("w_cron_ini_7")), Trim(Request("w_cron_ini_8")), _
              Trim(Request("w_cron_ini_9")), Trim(Request("w_cron_ini_10")), Trim(Request("w_cron_ini_11")), Trim(Request("w_cron_ini_12"))
        End If
        dbms.CommitTrans()
        ScriptOpen "JavaScript"
        ' Recupera a sigla do servi�o pai, para fazer a chamada ao menu
        DB_GetLinkData RS, Session("p_cliente"), SG
        ShowHTML "  location.href='" & replace(RS("link"),w_dir,"") & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
        DesconectaBD
        ScriptClose
     Else
        ScriptOpen "JavaScript"
        ShowHTML "  alert('Assinatura Eletr�nica inv�lida!');"
        ShowHTML "  history.back(1);"
        ScriptClose
     End If
  ElseIf SG = "ISACPROFIN" Then
     ' Verifica se a Assinatura Eletr�nica � v�lida
     If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then
        DML_PutFinancAcaoPPA_IS O, Request("w_chave"), Mid(Request("w_chave_aux"),1,4), _ 
                                Mid(Request("w_chave_aux"),5,4), Mid(Request("w_chave_aux"),9,4), _
                                w_cliente, w_ano, Request("w_obs_financ")
        ScriptOpen "JavaScript"
        ' Recupera a sigla do servi�o pai, para fazer a chamada ao menu
        DB_GetLinkData RS, Session("p_cliente"), SG
        ShowHTML "  location.href='" & replace(RS("link"),w_dir,"") & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
        DesconectaBD
        ScriptClose
     Else
        ScriptOpen "JavaScript"
        ShowHTML "  alert('Assinatura Eletr�nica inv�lida!');"
        ShowHTML "  history.back(1);"
        ScriptClose
     End If
  ElseIf SG = "ISACAD" Then
     ' Verifica se a Assinatura Eletr�nica � v�lida
     If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then
        
        ' Verifica se a meta � cumulativa ou n�o para o calculo do percentual de conclus�o
        If Request("w_cumulativa") = "N" Then
           If cDbl(Nvl(Request("w_quantidade"),0)) = 0 Then
              w_perc_conclusao = 100
           Else
              i = 1
              ' Faz a varredura do campos de quantidade e ir� armazenar o percentual de conclus�o do ultimo m�s atualizazado
              for i = 12 to 1 step -1
                 If cDbl(Nvl(Request("w_realizado_"&i&""),0)) > 0 Then
                    w_perc_conclusao = (Request("w_realizado_"&i&"")*100)/Request("w_quantidade")
                    i = 1
                 End If
              next
           End If
        Else
           'Se n�o for cumulativa faz o percentual de conclus�o com todos os valores do formul�rio
           w_quantitativo_total = 0
           for i = 1 to 12
              w_quantitativo_total = w_quantitativo_total + cDbl(Nvl(Request("w_realizado_"&i),0))
           next
           If cDbl(Nvl(Request("w_quantidade"),0)) = 0 Then
              w_perc_conclusao = 100
           Else
              w_perc_conclusao = (w_quantitativo_total*100)/cDbl(Request("w_quantidade"))
           End If
        End If
        DML_PutAtualizaMeta_IS Request("w_chave"), Request("w_chave_aux"), Nvl(w_perc_conclusao,0), Request("w_situacao_atual"), _
                             Request("w_exequivel"), Request("w_justificativa_inex"), Request("w_outras_medidas")
        i = 1
        ' Grava��o da execu��o f�sica e feita m�s por m�s
        DML_PutMetaMensal_IS "E", Request("w_chave_aux"), Request("w_realizado_"&i&""), Request("w_revisado_"&i&""), Request("w_referencia_"&i&""), w_cliente
        While i < 13 
           DML_PutMetaMensal_IS "Z", Request("w_chave_aux"), cDbl(Nvl(Request("w_realizado_"&i&""),0)), cDbl(Nvl(Request("w_revisado_"&i&""),0)), Request("w_referencia_"&i&""), w_cliente
           If cDbl(Nvl(Request("w_realizado_"&i&""),0)) > 0 or cDbl(Nvl(Request("w_revisado_"&i&""),0)) > 0 Then
              DML_PutMetaMensal_IS "I", Request("w_chave_aux"), Request("w_realizado_"&i&""), Request("w_revisado_"&i&""), Request("w_referencia_"&i&""), w_cliente
           End If
           i = i + 1
        wend   
          
        ScriptOpen "JavaScript"
        ' Recupera a sigla do servi�o pai, para fazer a chamada ao menu
        ShowHTML "  location.href='" & R & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
        ScriptClose
     Else
        ScriptOpen "JavaScript"
        ShowHTML "  alert('Assinatura Eletr�nica inv�lida!');"
        ShowHTML "  history.back(1);"
        ScriptClose
     End If
  ElseIf SG = "ISACRESTR" Then
     ' Verifica se a Assinatura Eletr�nica � v�lida
     If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then
          
        DML_PutRestricao_IS O, SG, Request("w_chave"), Request("w_chave_aux"), Request("w_cd_subacao"), Request("w_sq_isprojeto"), _
            Request("w_cd_tipo_restricao"), _
            Request("w_cd_tipo_inclusao"), Request("w_cd_competencia"), Request("w_superacao"), _
            Request("w_relatorio"), Request("w_tempo_habil"), Request("w_descricao"), _
            Request("w_providencia"), Request("w_observacao_controle"), Request("w_observacao_monitor"), w_ano, w_cliente
          
        If O = "I" or O = "E" Then
           RestricaoMail Request("w_chave"), Request("w_descricao"), Request("w_cd_tipo_restricao"), Request("w_providencia"), O              
        End If
        ScriptOpen "JavaScript"
        ' Recupera a sigla do servi�o pai, para fazer a chamada ao menu
        DB_GetLinkData RS, Session("p_cliente"), SG
        ShowHTML "  location.href='" & replace(RS("link"),w_dir,"") & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
        DesconectaBD
        ScriptClose
     Else
        ScriptOpen "JavaScript"
        ShowHTML "  alert('Assinatura Eletr�nica inv�lida!');"
        ShowHTML "  history.back(1);"
        ScriptClose
     End If 
  ElseIf SG = "ISACINTERE" Then
     ' Verifica se a Assinatura Eletr�nica � v�lida
     If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then

        DML_PutProjetoInter O, Request("w_chave"), Request("w_chave_aux"), Request("w_tipo_visao"), Request("w_envia_email")
        
        ScriptOpen "JavaScript"
        ' Recupera a sigla do servi�o pai, para fazer a chamada ao menu
        DB_GetLinkData RS, Session("p_cliente"), SG
        ShowHTML "  location.href='" & replace(RS("link"),w_dir,"") & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
        DesconectaBD
        ScriptClose
     Else
        ScriptOpen "JavaScript"
        ShowHTML "  alert('Assinatura Eletr�nica inv�lida!');"
        ShowHTML "  history.back(1);"
        ScriptClose
     End If
  ElseIf SG = "ISACANEXO" Then
     ' Verifica se a Assinatura Eletr�nica � v�lida
     If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then

        ' Se foi feito o upload de um arquivo  
        Set FS = CreateObject("Scripting.FileSystemObject")
        If ul.State = 0 Then
           w_maximo     = ul.Texts.Item("w_upload_maximo")
           For Each Field in ul.Files.Items
              If Field.Length > 0 Then
                 ' Verifica se o tamanho das fotos est� compat�vel com  o limite de 100KB. 
                 If cDbl(Field.Length) > cDbl(w_maximo) Then 
                    ScriptOpen("JavaScript") 
                    ShowHTML "  alert('Aten��o: o tamanho m�ximo do arquivo n�o pode exceder " & cDbl(w_maximo)/1024 & " KBytes!');" 
                    ShowHTML "  history.back(1);" 
                    ScriptClose 
                    Response.End() 
                    exit sub 
                 End If 
                 ' Se j� h� um nome para o arquivo, mant�m 
                 Set FS = CreateObject("Scripting.FileSystemObject")
                 If ul.Texts.Item("w_atual") > "" Then
                    DB_GetSolicAnexo RS, ul.Texts.Item("w_chave"), ul.Texts.Item("w_atual"), w_cliente 
                    FS.DeleteFile conFilePhysical & w_cliente & "\" & RS("caminho")
                    w_file = Mid(RS("caminho"),1,Instr(RS("caminho"),".")-1) & Mid(Field.FileName,Instr(Field.FileName,"."),30)
                 Else
                    w_file = replace(FS.GetTempName(),".tmp",Mid(Field.FileName,Instr(Field.FileName,"."),30))
                 End If
                 w_tamanho = Field.Length
                 w_tipo    = Field.ContentType
                 w_nome    = Field.FileName
                 Field.SaveAs conFilePhysical & w_cliente & "\" & w_file
              End If
           Next
    
           'Response.Write UploadID & "w_file: " & w_file & "<br> " & "w_tamanho: " & w_tamanho & "<br> " & "w_tipo: " & w_tipo & "<br> " & "w_nome: " & w_nome
           'Response.End()

           ' Se for exclus�o e houver um arquivo f�sico, deve remover o arquivo do disco.  
           If O = "E" and ul.Texts.Item("w_atual") > "" Then  
              DB_GetSolicAnexo RS, ul.Texts.Item("w_chave"), ul.Texts.Item("w_atual"), w_cliente 
              FS.DeleteFile conFilePhysical & w_cliente & "\" & RS("caminho")
              DesconectaBD
           End If  
    
           'Response.Write O& ", " &w_cliente& ", " &ul.Texts.Item("w_chave")& ", " &ul.Texts.Item("w_chave_aux")& ", " &ul.Texts.Item("w_nome")& ", " &ul.Texts.Item("w_descricao")
           'Response.End()
           DML_PutSolicArquivo O, _  
               w_cliente, ul.Texts.Item("w_chave"), ul.Texts.Item("w_chave_aux"), ul.Texts.Item("w_nome"), ul.Texts.Item("w_descricao"), _  
               w_file, w_tamanho, w_tipo, w_nome
        Else
           ScriptOpen "JavaScript" 
           ShowHTML "  alert('ATEN��O: ocorreu um erro na transfer�ncia do arquivo. Tente novamente!');" 
           ScriptClose 
           Response.End()
           Exit Sub
        End If                
        ScriptOpen "JavaScript"
        ' Recupera a sigla do servi�o pai, para fazer a chamada ao menu 
        DB_GetLinkData RS, Session("p_cliente"), SG 
        ShowHTML "  location.href='" & replace(RS("link"),w_dir,"") & "&O=L&w_chave=" & ul.Texts.Item("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';" 
        DesconectaBD
        ScriptClose
     Else
        ScriptOpen "JavaScript"
        ShowHTML "  alert('Assinatura Eletr�nica inv�lida!');"
        ShowHTML "  history.back(1);"
        ScriptClose
     End If
  ElseIf SG = "ISACENVIO" Then
     ' Verifica se a Assinatura Eletr�nica � v�lida
     If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then

        DB_GetSolicData_IS RS, Request("w_chave"), "ISACGERAL"
        If cDbl(RS("sq_siw_tramite")) <> cDbl(Request("w_tramite")) Then
           ScriptOpen "JavaScript"
           ShowHTML "  alert('ATEN��O: Outro usu�rio j� encaminhou esta a��o para outra fase de execu��o!');"
           ScriptClose
        Else
           DML_PutProjetoEnvio Request("w_menu"), Request("w_chave"), w_usuario, Request("w_tramite"), Request("w_novo_tramite"), "N", Request("w_observacao"), Request("w_destinatario"), Request("w_despacho"), null, null, null, null
           
           ' Envia e-mail comunicando a tramita��o
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
        ShowHTML "  alert('Assinatura Eletr�nica inv�lida!');"
        ShowHTML "  history.back(1);"
        ScriptClose
     End If
  ElseIf SG = "ISACCONC" Then
     ' Verifica se a Assinatura Eletr�nica � v�lida
     If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then

        DB_GetSolicData_IS RS, Request("w_chave"), "ISACGERAL"
        If cDbl(RS("sq_siw_tramite")) <> cDbl(Request("w_tramite")) Then
           ScriptOpen "JavaScript"
           ShowHTML "  alert('ATEN��O: Outro usu�rio j� encaminhou esta a��o para outra fase de execu��o!');"
           ScriptClose
        Else
           DML_PutProjetoConc Request("w_menu"), Request("w_chave"), w_usuario, Request("w_tramite"), Request("w_inicio_real"), Request("w_fim_real"), Request("w_nota_conclusao"), Request("w_custo_real")
           
           ' Envia e-mail comunicando a conclus�o
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
        ShowHTML "  alert('Assinatura Eletr�nica inv�lida!');"
        ShowHTML "  history.back(1);"
        ScriptClose
     End If
  Else
     ScriptOpen "JavaScript"
     ShowHTML "  alert('Bloco de dados n�o encontrado: " & SG & "');"
     ShowHTML "  history.back(1);"
     ScriptClose
  End If

  Set FS                    = Nothing
  Set w_Mensagem            = Nothing
  Set w_chave_nova          = Nothing
  Set p_sq_endereco_unidade = Nothing
  Set p_modulo              = Nothing
  Set w_Null                = Nothing
End Sub
REM -------------------------------------------------------------------------
REM Fim do procedimento que executa as opera��es de BD
REM =========================================================================

REM =========================================================================
REM Rotina principal
REM -------------------------------------------------------------------------
Sub Main
  ' Verifica se o usu�rio tem lota��o e localiza��o
  If (len(Session("LOTACAO")&"") = 0 or len(Session("LOCALIZACAO")&"") = 0) and Session("LogOn") = "Sim" Then
    ScriptOpen "JavaScript"
    ShowHTML " alert('Voc� n�o tem lota��o ou localiza��o definida. Entre em contato com o RH!'); "
    ShowHTML " top.location.href='Default.asp'; "
    ScriptClose
   Exit Sub
  End If

  Select Case Par
    Case "INICIAL"      Inicial
    Case "GERAL"        Geral
    Case "RESP"         Responsaveis
    Case "PROGQUAL"     ProgramacaoQualitativa
    Case "META"         Metas
    Case "ATUALIZAMETA" AtualizaMeta
    Case "PROGFINAN"    Financiamento
    Case "RESTRICAO"    Restricoes
    Case "INTERESS"     Interessados
    Case "VISUAL"       Visual
    Case "VISUALNOVO"   VisualNovo
    Case "VISUALE"      VisualE
    Case "EXCLUIR"      Excluir
    Case "ENVIO"        Encaminhamento
    Case "ANEXO"        Anexos
    Case "ANOTACAO"     Anotar
    Case "CONCLUIR"     Concluir
    Case "OUTRAS"       Iniciativas
    Case "FINANC"       Financiamento
    Case "BUSCAACAO"    BuscaAcao
    Case "RECURSOPROGRAMADO" RecursoProgramado
    Case "GRAVA"        Grava
    Case Else
       Cabecalho
       ShowHTML "<BASE HREF=""" & conRootSIW & """>"
       BodyOpen "onLoad=document.focus();"
       ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
       ShowHTML "<HR>"
       ShowHTML "<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src=""images/icone/underc.gif"" align=""center""> <b>Esta op��o est� sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>"
       Rodape
  End Select
End Sub
REM =========================================================================
REM Fim da rotina principal
REM -------------------------------------------------------------------------
%>

