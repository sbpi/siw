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
<!-- #INCLUDE FILE="DML_Programa.asp" -->
<!-- #INCLUDE FILE="VisualPrograma.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Tabelas.asp" -->
<!-- #INCLUDE FILE="DML_Tabelas.asp" -->
<!-- #INCLUDE FILE="DB_SIAFI.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /Programa.asp
REM ------------------------------------------------------------------------
REM Nome     : Celso Miguel Lago Filho 
REM Descricao: Gerencia o módulo de programas
REM Mail     : celso@sbpi.com.br
REM Criacao  : 29/12/2004 16:30
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
Dim p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_programa, p_atividade
Dim p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, p_uorg_resp, p_palavra, p_prazo, p_fase
Dim p_cd_programa, p_qtd_restricao
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

' Carrega variáveis locais com os dados dos parâmetros recebidos
Par          = ucase(Request("Par"))
w_pagina     = "Programa.asp?par="
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
   p_programa          = uCase(ul.Texts.Item("p_programa"))
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
   p_cd_programa      = uCase(ul.Texts.Item("p_cd_programa"))
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
   p_programa         = uCase(Request("p_programa"))
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
   p_cd_programa      = uCase(Request("p_cd_programa"))
   p_qtd_restricao    = uCase(Request("p_qtd_restricao"))
   
   P1           = Nvl(Request("P1"),0)
   P2           = Nvl(Request("P2"),0)
   P3           = cDbl(Nvl(Request("P3"),1))
   P4           = cDbl(Nvl(Request("P4"),conPagesize))
   TP           = Request("TP")
   R            = uCase(Request("R"))
   w_Assinatura = uCase(Request("w_Assinatura"))
   w_SG         = uCase(Request("w_SG"))
  
   If SG = "ISPRINTERE" or SG = "ISPRRESP" or SG = "ISPRANEXO" or SG = "ISPRINDIC" or _
      SG = "ISPRRESTR" Then
      If O <> "I" and O <> "E" and Request("w_chave_aux") = "" Then O = "L" End If
   ElseIf SG = "ISPRENVIO" Then 
      O = "V"
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
     If par="BUSCAPROGRAMA" Then
        w_TP = TP & " - Busca programa"
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
Set p_programa     = Nothing
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
Set p_cd_programa = Nothing
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

REM =========================================================================
REM Rotina de visualização resumida dos registros
REM -------------------------------------------------------------------------
Sub Inicial

  Dim w_titulo, w_total, w_parcial

  If O = "L" Then
     If Instr(uCase(R),"GR_") > 0 Then
        w_filtro = ""
        If p_programa > ""  Then 
           DB_GetSolicData_IS RS, p_programa, "ISPRGERAL"
           w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Ação <td><font size=1>[<b>" & RS("titulo") & "</b>]"
        End If
        If p_cd_programa > ""  Then 
           DB_GetProgramaPPA_IS RS, p_cd_programa, w_cliente, w_ano, null, null
           w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Programa PPA <td><font size=1>[<b>" & RS("ds_programa") & " (" & RS("cd_programa") & ")" & "</b>]"
        End If
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
        If p_proponente  > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Parcerias externas<td><font size=1>[<b>" & p_proponente & "</b>]"                      End If
        If p_assunto     > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Assunto <td><font size=1>[<b>" & p_assunto & "</b>]"                            End If
        If p_palavra     > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Parcerias internas <td><font size=1>[<b>" & p_palavra & "</b>]"                     End If
        If p_ini_i       > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Data recebimento <td><font size=1>[<b>" & p_ini_i & "-" & p_ini_f & "</b>]"     End If
        If p_fim_i       > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Limite conclusão <td><font size=1>[<b>" & p_fim_i & "-" & p_fim_f & "</b>]"     End If
        If p_atraso      = "S" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Situação <td><font size=1>[<b>Apenas atrasadas</b>]"                            End If
        If p_qtd_restricao = "S" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Situação <td><font size=1>[<b>Apenas programas com restrição</b>]"                            End If
        If w_filtro > "" Then w_filtro = "<table border=0><tr valign=""top""><td><font size=1><b>Filtro:</b><td nowrap><font size=1><ul>" & w_filtro & "</ul></tr></table>"                    End If
     End If
     
     DB_GetLinkData RS, w_cliente, "ISPCAD"
     
     If w_copia > "" Then ' Se for cópia, aplica o filtro sobre todas as demandas visíveis pelo usuário
        DB_GetSolicList_IS RS, RS("sq_menu"), w_usuario, SG, 3, _
           p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
           p_unidade, p_prioridade, p_qtd_restricao, p_proponente, _
           p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
           p_uorg_resp, p_palavra, p_prazo, p_fase, p_programa, p_atividade, null, p_cd_programa, null, null, w_ano
     Else
        DB_GetSolicList_IS RS, RS("sq_menu"), w_usuario, SG, P1, _
           p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
           p_unidade, p_prioridade, p_qtd_restricao, p_proponente, _
           p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
           p_uorg_resp, p_palavra, p_prazo, p_fase, p_programa, p_atividade, null, p_cd_programa, null, null, w_ano
        Select case Request("p_agrega")
           Case "GRISPRESPATU"
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
          'ShowHTML "    <a accesskey=""C"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=C&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>C</u>opiar</a>"
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
    ShowHTML "          <td rowspan=2><font size=""1""><b>" & LinkOrdena("Programa", "cd_programa") & "</font></td>"
    ShowHTML "          <td rowspan=2><font size=""1""><b>" & LinkOrdena("Responsável", "nm_solic") & "</font></td>"
    If P1 <> 2 Then ShowHTML "          <td rowspan=2><font size=""1""><b>" & LinkOrdena("Usuário atual", "cd_exec") & "</font></td>" End If
    If P1 = 1 or P1 = 2 Then ' Se for cadastramento ou mesa de trabalho
       ShowHTML "          <td rowspan=2><font size=""1""><b>" & LinkOrdena("Título", "titulo") & "</font></td>"
       ShowHTML "          <td colspan=2><font size=""1""><b>Execução</font></td>"
    Else
       ShowHTML "          <td rowspan=2><font size=""1""><b>" & LinkOrdena("Parcerias", "proponente") & "</font></td>"
       ShowHTML "          <td rowspan=2><font size=""1""><b>" & LinkOrdena("Título", "titulo") & "</font></td>"
       ShowHTML "          <td colspan=2><font size=""1""><b>Execução</font></td>"
       ShowHTML "          <td rowspan=2><font size=""1""><b>Valor</font></td>"
       ShowHTML "          <td rowspan=2><font size=""1""><b>" & LinkOrdena("Fase atual", "nm_tramite") & "</font></td>"
    End If
    ShowHTML "          <td rowspan=2><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("De", "inicio") & "</font></td>"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Até", "fim") & "</font></td>"
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
        ShowHTML "        <A class=""HL"" HREF=""" & w_dir & w_pagina & "Visual&R=" & w_pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=2&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exibe as informações deste registro."">" & RS("cd_programa") & "&nbsp;</a>"
        
        ShowHTML "        <td><font size=""1"">" & ExibePessoa("../", w_cliente, RS("solicitante"), TP, RS("nm_solic")) & "</A></td>"
        If P1 <> 2 Then ' Se for mesa de trabalho, não exibe o executor, pois já é o usuário logado
           If Nvl(RS("nm_exec"),"---") > "---" Then
              ShowHTML "        <td><font size=""1"">" & ExibePessoa("../", w_cliente, RS("executor"), TP, RS("nm_exec")) & "</td>"
           Else
              ShowHTML "        <td><font size=""1"">---</td>"
           End If
        End If
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
              ShowHTML "        <td title=""" & replace(replace(replace(RS("titulo"), "'", "\'"), """", "\'"),VbCrLf,"\n") & """><font size=""1""><strike>" & w_titulo & "</strike></td>"
           Else
              ShowHTML "        <td title=""" & replace(replace(replace(RS("titulo"), "'", "\'"), """", "\'"),VbCrLf,"\n") & """><font size=""1"">" & w_titulo & "</td>"
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
        If P1 <> 3 and P1 <> 5 Then ' Se não for acompanhamento
           If w_copia > "" Then ' Se for listagem para cópia
              DB_GetLinkSubMenu RS1, w_cliente, Request("SG")
              'ShowHTML "          <a accesskey=""I"" class=""HL"" href=""" & w_dir & w_pagina & "Geral&R=" & w_pagina & par & "&O=I&SG=" & RS1("sigla") & "&w_menu=" & w_menu & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&w_copia=" & RS("sq_siw_solicitacao") & MontaFiltro("GET") & """>Copiar</a>&nbsp;"
           ElseIf P1 = 1 Then ' Se for cadastramento
              If w_submenu > "" Then
                 ShowHTML "          <A class=""HL"" HREF=""Menu.asp?par=ExibeDocs&O=A&w_chave=" & RS("sq_siw_solicitacao") & "&R=" & w_Pagina & par & "&SG=" & SG & "&TP=" & TP & "&w_documento=Nr. " & RS("sq_siw_solicitacao") & MontaFiltro("GET") & """ title=""Altera as informações cadastrais do programa"" TARGET=""menu"">Alterar</a>&nbsp;"
              Else
                 ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & RS("sq_siw_solicitacao") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Altera as informações cadastrais do programa"">Alterar</A>&nbsp"
              End If
              ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "Excluir&R=" & w_pagina & par & "&O=E&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exclusão de programa."">Excluir</A>&nbsp"
              ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "Envio&R=" & w_pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Encaminhamento do programa."">Enviar</A>&nbsp"
           ElseIf P1 = 2 Then ' Se for execução
              If cDbl(w_usuario) = cDbl(RS("executor")) Then
                 If cDbl(Nvl(RS("solicitante"),0)) = cDbl(w_usuario) or _
                    cDbl(Nvl(RS("titular"),0))     = cDbl(w_usuario) or _
                    cDbl(Nvl(RS("substituto"),0))  = cDbl(w_usuario) or _
                    cDbl(Nvl(RS("tit_exec"),0))    = cDbl(w_usuario) or _
                    cDbl(Nvl(RS("executor"),0))    = cDbl(w_usuario) or _
                    cDbl(Nvl(RS("subst_exec"),0))  = cDbl(w_usuario) _
                 Then
                    ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "Indicador&R=" & w_pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & "ISPRINDIC" & MontaFiltro("GET") & """ title=""Atualiza os indicadores do programa"" target=""Indicadores"">Ind</A>&nbsp"
                    ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "Restricao&R=" & w_pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & "ISPRRESTR" & MontaFiltro("GET") & """ title=""Atualiza as restricoes do programa"" target=""Restricoes"">Rest</A>&nbsp"
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
                 ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "Indicador&R=" & w_pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & "ISPRINDIC" & MontaFiltro("GET") & """ title=""Indicadores do programa."" target=""Indicadores"">Ind</A>&nbsp"
                 ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "Restricao&R=" & w_pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & "ISPRRESTR" & MontaFiltro("GET") & """ title=""Restricoes do programa."" target=""Restricoes"">Rest</A>&nbsp"
                 ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "Envio&R=" & w_pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Envia a ação para outro responsável."">Enviar</A>&nbsp"
              End If
           End If
        Else
           ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "Indicador&R=" & w_pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & "ISPRINDIC" & MontaFiltro("GET") & """ title=""Indicadores do programa"" target=""Indicadores"">Ind</A>&nbsp"
           ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "Restricao&R=" & w_pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & "ISPRRESTR" & MontaFiltro("GET") & """ title=""Restricoes do programa"" target=""Restricoes"">Rest</A>&nbsp"
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
       DB_GetLinkData RS, w_cliente, "ISPCAD"
       SelecaoProgramaPPA "Programa <u>P</u>PA:", "P", null, w_cliente, w_ano, p_cd_programa, "p_cd_programa", null, null, w_menu
       DesconectaBD
       ShowHTML "      </tr>"
       ShowHTML "          </table>"
       
       ShowHTML "      <tr valign=""top"">"
       ShowHTML "          <td valign=""top""><font size=""1""><b>Dias para a data limi<U>t</U>e:<br><INPUT ACCESSKEY=""T"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_prazo"" size=""2"" maxlength=""2"" value=""" & p_prazo & """></td>"
       ShowHTML "      <tr valign=""top"">"
       SelecaoPessoa "Respo<u>n</u>sável:", "N", "Selecione o responsável pelo programa na relação.", p_solicitante, null, "p_solicitante", "USUARIOS"
       SelecaoUnidade "<U>S</U>etor responsável:", "S", null, p_unidade, null, "p_unidade", null, null
       ShowHTML "      <tr valign=""top"">"
       SelecaoPessoa "Responsável atua<u>l</u>:", "L", "Selecione o responsável atual pela ação na relação.", p_usu_resp, null, "p_usu_resp", "USUARIOS"
       SelecaoUnidade "<U>S</U>etor atual:", "S", "Selecione a unidade onde a ação se encontra na relação.", p_uorg_resp, null, "p_uorg_resp", null, null
       ShowHTML "          <td valign=""top""><font size=""1""><b>Propo<U>n</U>ente externo:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_proponente"" size=""25"" maxlength=""90"" value=""" & p_proponente & """></td>"
       ShowHTML "      <tr>"
       ShowHTML "          <td valign=""top""><font size=""1""><b>A<U>s</U>sunto:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_assunto"" size=""25"" maxlength=""90"" value=""" & p_assunto & """></td>"
       ShowHTML "          <td valign=""top"" colspan=2><font size=""1""><b>Pala<U>v</U>ras-chave:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_palavra"" size=""25"" maxlength=""90"" value=""" & p_palavra & """></td>"
       ShowHTML "      <tr>"
       ShowHTML "          <td valign=""top""><font size=""1""><b>Data de re<u>c</u>ebimento entre:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""p_ini_i"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_ini_i & """ onKeyDown=""FormataData(this,event);"" title=""Usar formato dd/mm/aaaa""> e <input " & w_Disabled & " accesskey=""C"" type=""text"" name=""p_ini_f"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_ini_f & """ onKeyDown=""FormataData(this,event);"" title=""Usar formato dd/mm/aaaa""></td>"
       ShowHTML "          <td valign=""top""><font size=""1""><b>Limi<u>t</u>e para conclusão entre:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""p_fim_i"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_fim_i & """ onKeyDown=""FormataData(this,event);"" title=""Usar formato dd/mm/aaaa""> e <input " & w_Disabled & " accesskey=""T"" type=""text"" name=""p_fim_f"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_fim_f & """ onKeyDown=""FormataData(this,event);"" title=""Usar formato dd/mm/aaaa""></td>"
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
       ShowHTML "          <option value=""assunto"" SELECTED>Assunto<option value=""inicio"">Data de recebimento<option value="""">Data limite para conclusão<option value=""nm_tramite"">Fase atual<option value=""proponente"">Proponente externo"
    ElseIf p_Ordena="INICIO" Then
       ShowHTML "          <option value=""assunto"">Assunto<option value=""inicio"" SELECTED>Data de recebimento<option value="""">Data limite para conclusão<option value=""nm_tramite"">Fase atual<option value=""proponente"">Proponente externo"
    ElseIf p_Ordena="NM_TRAMITE" Then
       ShowHTML "          <option value=""assunto"">Assunto<option value=""inicio"">Data de recebimento<option value="""">Data limite para conclusão<option value=""nm_tramite"" SELECTED>Fase atual<option value=""proponente"">Proponente externo"
    ElseIf p_Ordena="PRIORIDADE" Then
       ShowHTML "          <option value=""assunto"">Assunto<option value=""inicio"">Data de recebimento<option value="""">Data limite para conclusão<option value=""nm_tramite"">Fase atual<option value=""proponente"">Proponente externo"
    ElseIf p_Ordena="PROPONENTE" Then
       ShowHTML "          <option value=""assunto"">Assunto<option value=""inicio"">Data de recebimento<option value="""">Data limite para conclusão<option value=""nm_tramite"">Fase atual<option value=""proponente"" SELECTED>Proponente externo"
    Else
       ShowHTML "          <option value=""assunto"">Assunto<option value=""inicio"">Data de recebimento<option value="""" SELECTED>Data limite para conclusão<option value=""nm_tramite"">Fase atual<option value=""prioridade"">Proponente externo"
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
  Dim w_data_conclusao, w_nota_conclusao, w_custo_real
  Dim w_cd_programa, w_selecao_mp, w_selecao_se, w_sq_natureza, w_sq_horizonte
  Dim w_sq_unidade_adm, w_ln_programa
  
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
     w_cd_programa             = Request("w_cd_programa") 
     w_descricao               = Request("w_descricao")
     w_justificativa           = Request("w_justificativa")
     w_selecao_mp              = Request("w_selecao_mp")
     w_selecao_se              = Request("w_selecao_se")
     w_sq_natureza             = Request("w_sq_natureza")
     w_sq_horizonte            = Request("w_sq_horizonte")
     w_sq_unidade_adm          = Request("w_sq_unidade_adm")
     w_ln_programa             = Request("w_ln_programa")
     If w_cd_programa > "" Then
        DB_GetProgramaPPA_IS RS, w_cd_programa, w_cliente, w_ano, null, null
        w_titulo                  = RS("cd_programa") & " - " & Mid(RS("ds_programa"),1,60)
     End If
  Else
     If InStr("AEV",O) > 0 or w_copia > "" Then
        ' Recupera os dados da ação
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
           w_valor                  = FormatNumber(Nvl(RS("valor"),0),2)
           w_opiniao                = RS("opiniao") 
           w_data_hora              = RS("data_hora") 
           w_cd_programa            = RS("cd_programa")
           w_selecao_mp             = RS("mpog_ppa")
           w_selecao_se             = RS("relev_ppa")
           w_sq_natureza            = RS("sq_natureza")
           w_sq_horizonte           = RS("sq_horizonte")
           w_palavra_chave          = RS("palavra_chave") 
           w_descricao              = RS("descricao")
           w_justificativa          = RS("justificativa")  
           w_sq_unidade_adm         = RS("sq_unidade_adm")
           w_ln_programa            = RS("ln_programa")
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
     Validate "w_titulo", "Programa", "1", 1, 5, 100, "1", "1"
     If O = "I" and w_cd_programa = "" Then
        Validate "w_cd_programa", "Programa PPA", "SELECT", "1", 1, 90, "1", "1"
     End If
     Validate "w_sq_unidade_adm", "Unidade administrativa", "HIDDEN", 1, 1, 18, "", "0123456789"
     Validate "w_solicitante", "Responsável monitoramento", "HIDDEN", 1, 1, 18, "", "0123456789"
     Validate "w_sq_unidade_resp", "Área planejamento", "SELECT", 1, 1, 18, "", "0123456789"
     Validate "w_sq_natureza", "Natureza", "SELECT", 1, 1, 18, "", "0123456789"
     Validate "w_sq_horizonte", "Horizonte", "SELECT", 1, 1, 18, "", "0123456789"
     Validate "w_inicio", "Início previsto", "DATA", 1, 10, 10, "", "0123456789/"
     Validate "w_fim", "Fim previsto", "DATA", 1, 10, 10, "", "0123456789/"
     CompData "w_inicio", "Data de recebimento", "<=", "w_fim", "Limite para conclusão"
     'Validate "w_valor", "Recurso programado", "VALOR", "1", 4, 18, "", "0123456789.,"
     'CompValor "w_valor", "Recurso programado", ">", "0,00", "zero"
     Validate "w_proponente", "Parcerias externas", "", "", 2, 90, "1", "1"
     Validate "w_palavra_chave", "Parcerias internas", "", "", 2, 90, "1", "1"
     Validate "w_ln_programa", "Endereço na internet", "", "", 11, 120, "1", "1"
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
    ShowHTML "<INPUT type=""hidden"" name=""w_valor"" value=""0,00"">"

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
    ShowHTML "      <tr><td><font size=1>Os dados deste bloco serão utilizados para identificação do programa, bem como para o controle de sua execução.</font></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    If w_cd_programa > "" Then
       ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Programa:</b><br><INPUT READONLY " & w_Disabled & " class=""STI"" type=""text"" name=""w_titulo"" size=""90"" maxlength=""100"" value=""" & w_titulo & """ ></td>"
    Else
       ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Programa:</b><br><INPUT ACCESSKEY=""A"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_titulo"" size=""90"" maxlength=""100"" value=""" & w_titulo & """ ></td>"
    End If
    ShowHTML "          <tr>"
    If O = "I" or w_cd_programa = "" Then
       SelecaoProgramaPPA "Programa <u>P</u>PA:", "P", null, w_cliente, w_ano, w_cd_programa, "w_cd_programa", "IDENTIFICACAO", "onchange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_cd_programa'; document.Form.submit();""", w_menu
    Else
       SelecaoProgramaPPA "Programa <u>P</u>PA:", "P", null, w_cliente, w_ano, w_cd_programa, "w_cd_programa", null, "disabled", w_menu
       ShowHTML "<INPUT type=""hidden"" name=""w_cd_programa"" value=""" & w_cd_programa &""">"
    End If
    ShowHTML "          </tr>"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
    SelecaoUnidade_IS "<U>U</U>nidade administrativa:", "U", "Selecione a unidade administratriva responsável pelo programa.", w_sq_unidade_adm, null, "w_sq_unidade_adm", null, "ADMINISTRATIVA"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
    MontaRadioNS "<b>Selecionado pelo SPI/MP?</b>", w_selecao_mp, "w_selecao_mp"
    MontaRadioNS "<b>Selecionado pelo SE/SEPPIR?</b>", w_selecao_se, "w_selecao_se"
    ShowHTML "      </table></td></tr>"
    ShowHTML "      <tr valign=""top"">"
    SelecaoPessoa "Respo<u>n</u>sável monitoramento:", "N", "Selecione o nome da pessoa responsável pelas informações no SISPLAM.", w_solicitante, null, "w_solicitante", "USUARIOS"
    ShowHTML "      <tr valign=""top"">"
    SelecaoUnidade_IS "<U>Á</U>rea planejamento:", "S", "Selecione a área da secretaria ou orgão responsável pelo programa.", w_sq_unidade_resp, null, "w_sq_unidade_resp", null, "PLANEJAMENTO"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
    SelecaoNatureza_IS "Na<u>t</u>ureza:", "T", "Indique qual a natureza do programa com relação às suas ações.", w_cliente, w_sq_natureza, "w_sq_natureza", null, null
    SelecaoHorizonte_IS "<U>H</U>orizonte temporal:", "H", "Indique se o programa é contínuo ao longo do PPA ou se é apenas temporário.", w_cliente, w_sq_horizonte, "w_sq_horizonte", null, null
    ShowHTML "      </table></td></tr>"
    ShowHTML "          </table>"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "          <tr>"
    If w_cd_programa > "" Then
       ShowHTML "              <td valign=""top""><font size=""1""><b>Iní<u>c</u>io previsto:</b><br><input readonly " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_inicio"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & Nvl(w_inicio, "01/01/"&w_ano) & """ onKeyDown=""FormataData(this,event);"" title=""Usar formato dd/mm/aaaa""></td>"
       ShowHTML "              <td valign=""top""><font size=""1""><b><u>F</u>im previsto:</b><br><input readonly " & w_Disabled & " accesskey=""F"" type=""text"" name=""w_fim"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & Nvl(w_fim, "31/12/"&w_ano) & """ onKeyDown=""FormataData(this,event);"" title=""Usar formato dd/mm/aaaa""></td>"
    Else
       ShowHTML "              <td valign=""top""><font size=""1""><b>Iní<u>c</u>io previsto:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_inicio"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & Nvl(w_inicio, "01/01/"&w_ano) & """ onKeyDown=""FormataData(this,event);"" title=""Usar formato dd/mm/aaaa""></td>"
       ShowHTML "              <td valign=""top""><font size=""1""><b><u>F</u>im previsto:</b><br><input " & w_Disabled & " accesskey=""F"" type=""text"" name=""w_fim"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & Nvl(w_fim, "31/12/"&w_ano) & """ onKeyDown=""FormataData(this,event);"" title=""Usar formato dd/mm/aaaa""></td>"    
    End If
    'If O = "I" and w_cd_programa > "" Then
    '   DB_GetProgramaPPA_IS RS, w_cd_programa, w_cliente, w_ano, null, null
    '   w_valor = FormatNumber(cDbl(Nvl(RS("valor_estimado"),0.00)))
    '   DesconectaBD
    'End If
    'ShowHTML "              <td valign=""top""><font size=""1""><b><u>R</u>ecurso programado:</b><br><input " & w_Disabled & " accesskey=""O"" type=""text"" name=""w_valor"" class=""STI"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_valor & """ onKeyDown=""FormataValor(this,18,2,event);""></td>"
    ShowHTML "          </table>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Parc<u>e</u>rias externas:<br><INPUT ACCESSKEY=""E"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_proponente"" size=""90"" maxlength=""90"" value=""" & w_proponente & """ title=""Informar quais são os parceiros externos na execução do programa (campo opcional).""></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>P</u>arcerias internas:<br><INPUT ACCESSKEY=""P"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_palavra_chave"" size=""90"" maxlength=""90"" value=""" & w_palavra_chave & """ title=""Informar quais são os parceiros internos na execução do programa (campo opcional).""></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>En<u>d</u>ereço internet:<br><INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_ln_programa"" size=""90"" maxlength=""120"" value=""" & w_ln_programa & """ title=""Se desejar, informe o link do programa na internet.""></td>"

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
    ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_selecao_mp              = Nothing 
  Set w_selecao_se              = Nothing 
  Set w_sq_horizonte            = Nothing 
  Set w_sq_natureza             = Nothing 
  Set w_ln_programa             = Nothing
  Set w_cd_programa             = Nothing 
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
  Dim w_cd_programa, w_selecao_mp, w_selecao_se, w_sq_natureza, w_sq_horizonte
  Dim w_sq_unidade_adm, w_ln_programa
  
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
  
  If InStr("A",O) > 0 or w_copia > "" Then
     ' Recupera os dados da ação
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
        w_valor                  = FormatNumber(Nvl(RS("valor"),0),2)
        w_opiniao                = RS("opiniao") 
        w_data_hora              = RS("data_hora") 
        w_cd_programa            = RS("cd_programa")
        w_selecao_mp             = RS("mpog_ppa")
        w_selecao_se             = RS("relev_ppa")
        w_sq_natureza            = RS("sq_natureza")
        w_sq_horizonte           = RS("sq_horizonte")
        w_palavra_chave          = RS("palavra_chave") 
        w_descricao              = RS("descricao")
        w_justificativa          = RS("justificativa")  
        w_sq_unidade_adm         = RS("sq_unidade_adm")
        w_ln_programa            = RS("ln_programa")
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
  If O = "A" Then
     Validate "w_valor", "Recurso programado", "VALOR", "1", 4, 18, "", "0123456789.,"
     CompValor "w_valor", "Recurso programado", ">", "0,00", "zero"
     Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
  ElseIf O = "P" Then
     Validate "w_chave", "Programa PPA", "SELECT", "1", 1, 18, "", "0123456789"
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
    ShowHTML "<INPUT type=""hidden"" name=""w_cd_programa"" value=""" & w_cd_programa & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_selecao_mp"" value=""" & w_selecao_mp & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_selecao_se"" value=""" & w_selecao_se & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_natureza"" value=""" & w_sq_natureza & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_horizonte"" value=""" & w_sq_horizonte & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_palavra_chave"" value=""" & w_palavra_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_unidade_adm"" value=""" & w_sq_unidade_adm & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_ln_programa"" value=""" & w_ln_programa & """>"
    
    'Passagem da cidade padrão como brasília, pelo retidara do impacto geográfico da tela
    DB_GetCustomerData RS1, w_cliente
    ShowHTML "<INPUT type=""hidden"" name=""w_cidade"" value=""" & RS1("sq_cidade_padrao") &""">"
    RS1.Close

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"

    ShowHTML "    <table width=""99%"" border=""0"">"
    ShowHTML "      <tr><td><font size=2>Programa: <b>" & RS("titulo") & "</b></font></td></tr>"
     
    ' Identificação da ação
    ShowHTML "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Identificação</td>"  
      
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><font size=""1"">Programa PPA:<br><b>" & RS("ds_programa") & " (" & RS("cd_programa") & ")" & " </b></td>"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "      <tr><td><font size=""1"">Unidade Administrativa:<br><b>" & ExibeUnidade("../", w_cliente, RS("nm_unidade_adm"), RS("sq_unidade_adm"), TP) & "</b></td>"
    ShowHTML "          <td><font size=""1"">Unidade Orçamentária:<br><b>" & RS("nm_orgao") & " </b></td>"
    ShowHTML "          <tr valign=""top"">"
    If RS("mpog_ppa") = "S" Then
       ShowHTML "          <td><font size=""1"">Selecionada SPI/MP:<br><b>Sim</b></td>"
    Else
       ShowHTML "          <td><font size=""1"">Selecionada SPI/MP:<br><b>Não</b></td>"
    End If
    If RS("relev_ppa") = "S" Then
       ShowHTML "          <td><font size=""1"">Selecionada SE/SEPPIR:<br><b>Sim</b></td>"
    Else
       ShowHTML "          <td><font size=""1"">Selecionada SE/SEPPIR:<br><b>Não</b></td>"
    End If
    ShowHTML "        <tr valign=""top"">"
    ShowHTML "          <td><font size=""1"">Responsável monitoramento:<br><b>" & ExibePessoa("../", w_cliente, RS("solicitante"), TP, RS("nm_sol")) & "</b></td>"
    ShowHTML "             <td><font size=""1"">Área planejamento:<br><b>" & ExibeUnidade("../", w_cliente, RS("nm_unidade_resp"), RS("sq_unidade"), TP) & "</b></td>"
    ShowHTML "        <tr valign=""top"">"
    ShowHTML "          <td><font size=""1"">Natureza:<br><b>" & RS("nm_natureza") & " </b></td>"
    ShowHTML "          <td><font size=""1"">Horizonte:<br><b>" & RS("nm_horizonte") & " </b></td>"
    ShowHTML "          <tr valign=""top"">"
    ShowHTML "          <td><font size=""1"">Tipo do programa:<br><b>" & RS("nm_tipo_programa") & " </b></td>"
    If Nvl(RS("ln_programa"),"---") = "---" Then
       ShowHTML "          <td><font size=""1"">Endereço na internet:<br><b>" & Nvl(RS("ln_programa"),"---") & "</b></td>"
    Else
       ShowHTML "          <td><font size=""1"">Endereço na internet:<br><a href=""" & Nvl(RS("ln_programa"),"---") & """ target=""blank""><b>" & Nvl(RS("ln_programa"),"---") & "</b></a></td>"
    End If
    ShowHTML "          </table>"
    ShowHTML "        <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "        <tr valign=""top"">"
    ShowHTML "          <td><font size=""1"">Recurso programado:<br><b>" & FormatNumber(RS("valor"),2) & " </b></td>"
    ShowHTML "          <td><font size=""1"">Início previsto:<br><b>" & FormataDataEdicao(RS("inicio")) & " </b></td>"
    ShowHTML "          <td><font size=""1"">Fim previsto:<br><b>" & FormataDataEdicao(RS("fim")) & " </b></td>"
    ShowHTML "          </table>"
    ShowHTML "          <tr valign=""top""><td colspan=""2""><font size=""1"">Parcerias externas:<br><b>" & CRLF2BR(Nvl(RS("proponente"),"---")) & " </b></td>"
    ShowHTML "          <tr valign=""top""><td colspan=""2""><font size=""1"">Parcerias internas:<br><b>" & CRLF2BR(Nvl(RS("palavra_chave"),"---")) & " </b></td>"  
        
    ' Responsaveis
    If RS("nm_gerente_programa") > "" or RS("nm_gerente_executivo") > "" or RS("nm_gerente_adjunto") > "" Then
       ShowHTML "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Responsáveis</td>"  
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
    ShowHTML"      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Programação Financeira</td>"
    DB_GetPPADadoFinanc_IS RS1, w_cd_programa, null, w_ano, w_cliente, "VALORFONTE"
    If RS1.EOF Then
       ShowHTML"      <tr><td valign=""top""><font size=""1""><DD><b>Nao existe nenhum valor para este programa</b></DD></td>"
    Else
       w_cor = ""
       ShowHTML"                      <tr><td valign=""top"" colspan=""2""><font size=""1"">Fonte: SIGPLAN/MP - PPA 2004-2007</td>"
       ShowHTML"      <tr><td valign=""top""><font size=""1"">Tipo de orçamento:<br><b>" & RS1("nm_orcamento") & "</b></td>"
       ShowHTML"      <tr><td valign=""top""><font size=""1"">Valor por fonte: </td>"
       ShowHTML"      <tr><td align=""center"" colspan=""2"">"
       ShowHTML"        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
       ShowHTML"          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
       ShowHTML"            <td><font size=""1""><b>Fonte</font></td>"
       ShowHTML"            <td><font size=""1""><b>2004*</font></td>"
       ShowHTML"            <td><font size=""1""><b>2005**</font></td>"
       ShowHTML"            <td><font size=""1""><b>2006</font></td>"
       ShowHTML"            <td><font size=""1""><b>2007</font></td>"
       ShowHTML"            <td><font size=""1""><b>2008</font></td>"
       ShowHTML"            <td><font size=""1""><b>Total</font></td>"
       ShowHTML"          </tr>"
       While Not RS1.EOF 
          If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
          ShowHTML"       <tr bgcolor=""" & w_cor & """ valign=""top"">"
          ShowHTML"         <td><font size=""1"">" & RS1("nm_fonte")& "</td>"
          ShowHTML"         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_1"),0.00)))& "</td>"
          ShowHTML"         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_2"),0.00)))& "</td>"
          ShowHTML"         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_3"),0.00)))& "</td>"
          ShowHTML"         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_4"),0.00)))& "</td>"
          ShowHTML"         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_ano_5"),0.00)))& "</td>"
          ShowHTML"         <td align=""center""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS1("valor_total"),0.00)))& "</td>"
          ShowHTML"       </tr>"
          RS1.MoveNext
       wend
       RS1.Close
       DB_GetPPADadoFinanc_IS RS1, w_cd_programa, null, w_ano, w_cliente, "VALORTOTAL"
       ShowHTML"      <tr><td valign=""top"" align=""right""><font size=""1""><b>Totais </td>"
       If RS1.EOF Then
          ShowHTML"         <td valign=""top"" colspan=6><font size=""1""><DD><b>Nao existe nenhum valor para este programa</b></DD></td>"
       Else
           If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
           ShowHTML"         <td align=""center""><font size=""1""><b>" & FormatNumber(cDbl(Nvl(RS1("valor_ano_1"),0.00)))& "</td>"
           ShowHTML"         <td align=""center""><font size=""1""><b>" & FormatNumber(cDbl(Nvl(RS1("valor_ano_2"),0.00)))& "</td>"
           ShowHTML"         <td align=""center""><font size=""1""><b>" & FormatNumber(cDbl(Nvl(RS1("valor_ano_3"),0.00)))& "</td>"
           ShowHTML"         <td align=""center""><font size=""1""><b>" & FormatNumber(cDbl(Nvl(RS1("valor_ano_4"),0.00)))& "</td>"
           ShowHTML"         <td align=""center""><font size=""1""><b>" & FormatNumber(cDbl(Nvl(RS1("valor_ano_5"),0.00)))& "</td>"
           ShowHTML"         <td align=""center""><font size=""1""><b>" & FormatNumber(cDbl(Nvl(RS1("valor_total"),0.00)))& "</td>"
           ShowHTML"       </tr>"
           ShowHTML"       </table>"  
       End If
    End If
    RS1.Close
    ShowHTML"<tr><td valign=""top"" colspan=""2""><font size=""1"">* Valor Lei Orçamentária Anual - LOA 2004 + Créditos</td>"
    ShowHTML"<tr><td valign=""top"" colspan=""2""><font size=""1"">** Valor do Projeto de Lei Orçamentária Anual - PLOA 2005</td>"    
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>R</u>ecurso programado:</b><br><input " & w_Disabled & " accesskey=""O"" type=""text"" name=""w_valor"" class=""STI"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_valor & """ onKeyDown=""FormataValor(this,18,2,event);""></td>"
    ShowHTML "      <tr><td><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
    ShowHTML "      <tr><td align=""center""><hr>"
    ShowHTML "      <tr><td align=""center"">"
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
    SelecaoProgramaIS "Programa <u>P</u>PA:", "P", null, w_cliente, w_ano, w_chave, "w_chave", "CADASTRADOS", null
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
    ShowHTML " alert('Opção não disponível');"
    ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_selecao_mp              = Nothing 
  Set w_selecao_se              = Nothing 
  Set w_sq_horizonte            = Nothing 
  Set w_sq_natureza             = Nothing 
  Set w_ln_programa             = Nothing
  Set w_cd_programa             = Nothing 
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
  Set w_pais                    = Nothing 
  Set w_uf                      = Nothing 
  Set w_cidade                  = Nothing 
  Set w_palavra_chave           = Nothing 
  Set p_cd_programa             = Nothing 
  
  Set w_troca                   = Nothing 
  Set i                         = Nothing 
  Set w_erro                    = Nothing 
  Set w_como_funciona           = Nothing 
  Set w_cor                     = Nothing 

End Sub
REM =========================================================================
REM Fim da rotina de recurso programado
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina dos responsaveis
REM -------------------------------------------------------------------------
Sub Responsaveis

  Dim w_chave, w_chave_aux, w_nome, w_tipo
  Dim w_nm_gerente_programa, w_fn_gerente_programa, w_em_gerente_programa
  Dim w_nm_gerente_executivo, w_fn_gerente_executivo, w_em_gerente_executivo
  Dim w_nm_gerente_adjunto, w_fn_gerente_adjunto, w_em_gerente_adjunto
  Dim w_cd_programa, w_ds_programa
  
  Dim w_troca, i, w_erro
  
  w_Chave                = Request("w_Chave")
  w_Chave_aux            = Request("w_Chave_aux")
  w_cd_programa          = Request("w_cd_programa")
  w_ds_programa          = Request("w_ds_programa")
  w_nm_gerente_programa  = Request("w_nm_gerente_programa")
  w_fn_gerente_programa  = Request("w_fn_gerente_programa")
  w_em_gerente_programa  = Request("w_em_gerente_programa")
  w_nm_gerente_executivo = Request("w_nm_gerente_executivo")
  w_fn_gerente_executivo = Request("w_fn_gerente_executivo")
  w_em_gerente_executivo = Request("w_em_gerente_executivo")
  w_nm_gerente_adjunto   = Request("w_nm_gerente_adjunto")
  w_fn_gerente_adjunto   = Request("w_fn_gerente_adjunto")
  w_em_gerente_adjunto   = Request("w_em_gerente_adjunto")

  
  If O = "L" Then
     ' Recupera todos os registros para a listagem
     DB_GetSolicData_IS RS, w_chave, SG
  ElseIf InStr("A",O) > 0 Then
     DB_GetProgramaPPA_IS RS, w_cd_programa, w_cliente, w_ano, null, null
     If Not RS.EOF Then
        w_nm_gerente_programa  = RS("nm_gerente_programa")
        w_fn_gerente_programa  = RS("fn_gerente_programa")
        w_em_gerente_programa  = RS("em_gerente_programa")
        w_nm_gerente_executivo = RS("nm_gerente_executivo")
        w_fn_gerente_executivo = RS("fn_gerente_executivo")
        w_em_gerente_executivo = RS("em_gerente_executivo")
        w_nm_gerente_adjunto   = RS("nm_gerente_adjunto")
        w_fn_gerente_adjunto   = RS("fn_gerente_adjunto")
        w_em_gerente_adjunto   = RS("em_gerente_adjunto")
        w_ds_programa          = RS("ds_programa")
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
        Validate "w_nm_gerente_programa", "Gerente do programa", "", "1", "3", "60", "1", "1"
        Validate "w_fn_gerente_programa", "Telefone do gerente programa", "1", "", "7", "20", "1", "1"
        Validate "w_em_gerente_programa", "Email do gerente programa", "", "", "3", "60", "1", "1"
        Validate "w_nm_gerente_executivo", "Gerente do executivo", "", "1", "3", "60", "1", "1"
        Validate "w_fn_gerente_executivo", "Telefone do gerente executivo", "1", "", "7", "20", "1", "1"
        Validate "w_em_gerente_executivo", "Email do gerente executivo", "", "", "3", "60", "1", "1"
        Validate "w_nm_gerente_adjunto", "Gerente adjunto", "", "", "3", "60", "1", "1"
        Validate "w_fn_gerente_adjunto", "Telefone do gerente adjunto", "1", "", "7", "20", "1", "1"
        Validate "w_em_gerente_adjunto", "Email do gerente adjunto", "", "", "3", "60", "1", "1"
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If O = "A" Then
     BodyOpen "onLoad='document.Form.w_nm_gerente_programa.focus()';"
  Else
     BodyOpen "onLoad='document.focus()';"
  End If
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
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">Programa PPA</td>"
        ShowHTML "        <td><font size=""1"">" & RS("cd_programa") & " - " & RS("ds_programa") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" &w_chave & "&w_cd_programa=" & RS("cd_programa") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_chave_aux=" &RS("sq_siw_solicitacao")& """>Responsáveis</A>&nbsp"
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
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
    ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_chave_aux & """>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top""><font size=""2""><b>Programa PPA: </b>" &w_cd_programa& " - " & w_ds_programa & " </b>" 
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Gerente do programa: </b>" 
    ShowHTML "      <tr><td><font size=""1""><b><u>N</u>ome:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nm_gerente_programa"" class=""STI"" SIZE=""50"" MAXLENGTH=""60"" VALUE=""" & w_nm_gerente_programa & """ title=""Informe o nome do gerente do programa.""></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>T</u>elefone:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_fn_gerente_programa"" class=""STI"" SIZE=""15"" MAXLENGTH=""14"" VALUE=""" & w_fn_gerente_programa & """ title=""Informe o telefone do gerente do programa.""></td>"
    ShowHTML "      <tr><td><font size=""1""><b><u>E</u>mail:</b><br><input " & w_Disabled & " accesskey=""E"" type=""text"" name=""w_em_gerente_programa"" class=""STI"" SIZE=""50"" MAXLENGTH=""60"" VALUE=""" & w_em_gerente_programa & """ title=""Informe o e-mail do gerente do programa.""></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Gerente Executivo do programa: </b>" 
    ShowHTML "      <tr><td><font size=""1""><b>N<u>o</u>me:</b><br><input " & w_Disabled & " accesskey=""O"" type=""text"" name=""w_nm_gerente_executivo"" class=""STI"" SIZE=""50"" MAXLENGTH=""60"" VALUE=""" & w_nm_gerente_executivo & """ title=""Informe o nome do gerente executivo do programa.""></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Te<u>l</u>efone:</b><br><input " & w_Disabled & " accesskey=""L"" type=""text"" name=""w_fn_gerente_executivo"" class=""STI"" SIZE=""15"" MAXLENGTH=""14"" VALUE=""" & w_fn_gerente_executivo & """ title=""Informe o telefone do gerente executivo do programa.""></td>"
    ShowHTML "      <tr><td><font size=""1""><b>Em<u>a</u>il:</b><br><input " & w_Disabled & " accesskey=""A"" type=""text"" name=""w_em_gerente_executivo"" class=""STI"" SIZE=""50"" MAXLENGTH=""60"" VALUE=""" & w_em_gerente_executivo & """ title=""Informe o e-mail do gerente executivo do programa.""></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Gerente Adjunto do programa: </b>" 
    ShowHTML "      <tr><td><font size=""1""><b>No<u>m</u>e:</b><br><input " & w_Disabled & " accesskey=""M"" type=""text"" name=""w_nm_gerente_adjunto"" class=""STI"" SIZE=""50"" MAXLENGTH=""60"" VALUE=""" & w_nm_gerente_adjunto & """ title=""No caso de programa multisetorial cuja gerência não pertença a SEPPIR, informe o nome do gerente executivo adjunto do programa.""></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Tele<u>f</u>one:</b><br><input " & w_Disabled & " accesskey=""F"" type=""text"" name=""w_fn_gerente_adjunto"" class=""STI"" SIZE=""15"" MAXLENGTH=""14"" VALUE=""" & w_fn_gerente_adjunto & """ title=""No caso de programa multisetorial cuja gerência não pertença a SEPPIR, informe o telefone do gerente executivo adjunto do programa.""></td>"
    ShowHTML "      <tr><td><font size=""1""><b>Ema<u>i</u>l:</b><br><input " & w_Disabled & " accesskey=""I"" type=""text"" name=""w_em_gerente_adjunto"" class=""STI"" SIZE=""50"" MAXLENGTH=""60"" VALUE=""" & w_em_gerente_adjunto & """ title=""No caso de programa multisetorial cuja gerência não pertença a SEPPIR, informe o e-mail do gerente executivo adjunto do programa.""></td>"

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
    ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_chave                 = Nothing 
  Set w_chave_aux             = Nothing 
  Set w_cd_programa           = Nothing
  Set w_ds_programa           = Nothing 
  Set w_nm_gerente_programa   = Nothing
  Set w_fn_gerente_programa   = Nothing
  Set w_em_gerente_programa   = Nothing
  Set w_nm_gerente_executivo  = Nothing
  Set w_fn_gerente_executivo  = Nothing
  Set w_em_gerente_executivo  = Nothing
  Set w_nm_gerente_adjunto    = Nothing
  Set w_fn_gerente_adjunto    = Nothing
  Set w_em_gerente_adjunto    = Nothing
    
  Set w_troca                 = Nothing 
  Set i                       = Nothing 
  Set w_erro                  = Nothing
End Sub
REM =========================================================================
REM Fim da tela de responsáveis
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de cadastramento da programação qualitativa
REM -------------------------------------------------------------------------
Sub ProgramacaoQualitativa
  
  Dim w_chave, w_sq_menu
  
  Dim w_resultados, w_potencialidades, w_observacoes, w_contexto, w_justificativa_sigplan
  Dim w_objetivo, w_publico_alvo, w_estrategia, w_contribuicao_objetivo, w_diretriz
  Dim w_estrategia_monit, w_metodologia_aval, w_cd_programa, w_ds_programa
  
  Dim w_troca, w_erro, w_cor, w_readonly

  w_chave           = Request("w_chave")
  w_readonly        = ""
  w_erro            = ""
  w_troca           = Request("w_troca")

  ' Verifica se há necessidade de recarregar os dados da tela a partir
  ' da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  If w_troca > "" Then ' Se for recarga da página

     w_chave                   = Request("w_chave") 
     w_sq_menu                 = Request("w_sq_menu") 
     w_resultados              = Request("w_resultados") 
     w_potencialidades         = Request("w_potencialidades") 
     w_contribuicao_objetivo   = Request("w_contribuicao_objetivo")
     w_diretriz                = Request("w_diretriz") 
     w_estrategia_monit        = Request("w_estrategia_monit") 
     w_metodologia_aval        = Request("w_metodologia_aval")  
  Else  
     DB_GetSolicData_IS RS, w_chave, SG
     If RS.RecordCount > 0 Then 
        w_sq_menu                = RS("sq_menu")  
        w_contexto               = RS("contexto")
        w_justificativa_sigplan  = RS("justificativa_sigplan")
        w_objetivo               = RS("objetivo")
        w_publico_alvo           = RS("publico_alvo")
        w_resultados             = RS("descricao")
        w_estrategia             = RS("estrategia")
        w_potencialidades        = RS("potencialidades")
        If RS("justificativa") <> "" Then
           w_observacoes            = RS("justificativa")
        Else
           w_observacoes            = RS("observacoes_ppa")
        End If
        w_contribuicao_objetivo   = RS("contribuicao_objetivo")
        w_diretriz                = RS("diretriz") 
        w_estrategia_monit        = RS("estrategia_monit") 
        w_metodologia_aval        = RS("metodologia_aval")  
        w_cd_programa             = RS("cd_programa")  
        w_ds_programa             = RS("ds_programa")  
        DesconectaBD
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
     Validate "w_contribuicao_objetivo", "Explique como o programa contribui para que o objetivo setorial seja alcançado", "1", "", 5, 2000, "1", "1"
     Validate "w_diretriz", "Diretrizes do Plano Nacional de Políticas de Integração Racial", "1", "", 5, 2000, "1", "1"
     Validate "w_resultados", "Resultados esperados", "1", "", 5, 2000, "1", "1"
     Validate "w_potencialidades", "Potencialidades", "1", "", 5, 2000, "1", "1"
     Validate "w_estrategia_monit", "Sistemática e estratégias a serem adotadas para o monitoramento do programa", "1", "", 5, 2000, "1", "1"
     Validate "w_metodologia_aval", "Sistemática e metodologias a serem adotadas para avaliação do programa", "1", "", 5, 2000, "1", "1"
     Validate "w_observacoes", "Observações", "1", "", 5, 4000, "1", "1"
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
     BodyOpen "onLoad='document.Form.w_resultados.focus()';"
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
    ShowHTML "      <tr><td valign=""top"" align=""center"" bgcolor=""#D0D0D0""><font size=""2""><b>Programação qualitativa</td></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td><font size=1>Os dados deste bloco visam orientar os executores do programa.</font></td></tr>"
    ShowHTML "      <tr><td><font size=""1"">Programa Codº " & w_cd_programa & " - " & w_ds_programa & "</td>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    'ShowHTML "      <tr><td valign=""top""><div align=""justify""><font size=""1"" color=""red"">Diretrizes e desafios do governo associados ao programa:<br><b>Falta definir qual o campo deve ser visualizado</b></td>"
    'ShowHTML "      <tr><td valign=""top""><div align=""justify""><font size=""1"" color=""red"">Objetivo setorial:<br><b>Falta definir qual o campo deve ser visualizado</b></div></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>E</u>xplique como o programa contribui para que o objetivo setorial seja alcançado:</b><br><textarea " & w_Disabled & " accesskey=""E"" name=""w_contribuicao_objetivo"" class=""STI"" ROWS=5 cols=75 title=""Descreva de que forma a execução do programa vai contribuir para o alcance do objetivo setorial do governo ao qual o programa está relacionado."">" & w_contribuicao_objetivo & "</TEXTAREA></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>D</u>iretrizes do Plano Nacional da Promoção da Igualdade Racial:</b><br><textarea " & w_Disabled & " accesskey=""D"" name=""w_diretriz"" class=""STI"" ROWS=5 cols=75 title=""Informe a qual(is) diretrize(s) do Programa Naciona de Políticas de Integração Racial - o programa está relacionado."">" & w_diretriz & "</TEXTAREA></td>"
    'ShowHTML "      <tr><td valign=""top""><font size=""1"">Problema:<br><b>" & Nvl(w_contexto,"---")& "</b></td>"
    ShowHTML "      <tr><td valign=""top""><div align=""justify""><font size=""1"">Objetivo:<br><b>" & Nvl(w_objetivo,"---")& "</b></div></td>"
    ShowHTML "      <tr><td valign=""top""><div align=""justify""><font size=""1"">Justificativa:<br><b>" & Nvl(w_justificativa_sigplan,"---")& "</b></div></td>"
    ShowHTML "      <tr><td valign=""top""><div align=""justify""><font size=""1"">Público-alvo:<br><b>" & Nvl(w_publico_alvo,"---")& "</b></div></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>R</u>esultados esperados:</b><br><textarea " & w_Disabled & " accesskey=""R"" name=""w_resultados"" class=""STI"" ROWS=5 cols=75 title=""Descreva os principais resultados que se espera alcançar com a execução do programa."">" & w_resultados & "</TEXTAREA></td>"
    ShowHTML "      <tr><td valign=""top""><div align=""justify""><font size=""1"">Estratégia implementação:<br><b>" & Nvl(w_estrategia,"---")& "</b></div></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>P</u>otencialidades:</b><br><textarea " & w_Disabled & " accesskey=""P"" name=""w_potencialidades"" class=""STI"" ROWS=5 cols=75 title=""Descreva quais são os principais pontos fortes (internos) e as principais oportunidades (externas) do programa."">" & w_potencialidades & "</TEXTAREA></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>S</u>istemática e estratégias a serem adotadas para o monitoramento do programa:</b><br><textarea " & w_Disabled & " accesskey=""S"" name=""w_estrategia_monit"" class=""STI"" ROWS=5 cols=75 title=""Descreva a sistemática e as estratégias que serão adotadas para o monitoramento do programa, informando, inclusive as ferramentas que serão utilizadas."">" & w_estrategia_monit & "</TEXTAREA></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Sistemática e <u>m</u>etodologias a serem adotadas para avalição do programa:</b><br><textarea " & w_Disabled & " accesskey=""M"" name=""w_metodologia_aval"" class=""STI"" ROWS=5 cols=75 title=""Descreva a sistemática e as metodologias que serão adotadas para a avaliação do programa, informando, inclusive as ferramentas que serão utilizadas."">" & w_metodologia_aval & "</TEXTAREA></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>O</u>bservações:</b><br><textarea " & w_Disabled & " accesskey=""O"" name=""w_observacoes"" class=""STI"" ROWS=5 cols=75 title=""Informe as observações pertinentes (campo não obrigatório)."">" & w_observacoes & "</TEXTAREA></td>"
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
    ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_chave                   = Nothing 
  Set w_sq_menu                 = Nothing 
  Set w_resultados              = Nothing 
  Set w_potencialidades         = Nothing 
  Set w_observacoes             = Nothing
  Set w_contribuicao_objetivo   = Nothing
  Set w_diretriz                = Nothing
  Set w_estrategia_monit        = Nothing
  Set w_metodologia_aval        = Nothing
  Set w_cd_programa             = Nothing 
  Set w_ds_programa             = Nothing 
  
  Set w_troca                   = Nothing 
  Set w_erro                    = Nothing 
  Set w_cor                     = Nothing
  Set w_readonly                = Nothing  

End Sub
REM =========================================================================
REM Fim da rotina de programação qualitativa
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de indicadores do programa
REM -------------------------------------------------------------------------
Sub Indicadores
  Dim w_chave, w_chave_aux, w_cd_unidade_medida, w_cd_programa, w_cd_periodicidade
  Dim w_cd_base_geografica, w_categoria_analise, w_titulo, w_ordem, w_conceituacao
  Dim w_interpretacao, w_usos, w_limitacoes, w_comentarios, w_fonte, w_formula
  Dim w_tipo_in, w_indice_ref, w_apuracao_ref, w_ppa, w_cd_indicador, w_indice_apurado, w_apuracao_ind
  Dim w_prev_ano_1, w_prev_ano_2, w_prev_ano_3, w_prev_ano_4, w_observacoes 
  Dim w_quantidade, w_cumulativa, w_exequivel, w_situacao_atual, w_outras_medidas, w_justificativa_inex
  Dim w_acesso, w_nm_programada, w_nm_unidade_medida, w_nm_cumulativa
  Dim w_nm_periodicidade, w_nm_base_geografica, w_ds_programa
  
  Dim w_troca, i, w_texto
  
  w_Chave           = Request("w_Chave")
  w_chave_aux       = Request("w_chave_aux")
  w_troca           = Request("w_troca")
  w_cd_programa     = Request("w_cd_programa")
  
  If w_troca > "" Then ' Se for recarga da página
     w_cd_unidade_medida    = Request("w_cd_unidade_medida")
     w_cd_programa          = Request("w_cd_programa")
     w_cd_periodicidade     = Request("w_cd_periodicidade")
     w_cd_base_geografica   = Request("w_cd_base_geografica")
     w_categoria_analise    = Request("w_categoria_analise")
     w_ordem                = Request("w_ordem")
     w_titulo               = Request("w_titulo")
     w_conceituacao         = Request("w_conceituacao")    
     w_interpretacao        = Request("w_interpretacao")    
     w_usos                 = Request("w_usos")    
     w_limitacoes           = Request("w_limitacoes")    
     w_comentarios          = Request("w_comentarios")    
     w_fonte                = Request("w_fonte")    
     w_formula              = Request("w_formula")    
     w_tipo_in              = Request("w_tipo_in")
     w_indice_ref           = Request("w_indice_ref")        
     w_apuracao_ref         = Request("w_apuracao_ref")       
     w_prev_ano_1           = Request("w_prev_ano_1")
     w_prev_ano_2           = Request("w_prev_ano_2")
     w_prev_ano_3           = Request("w_prev_ano_3")
     w_prev_ano_4           = Request("w_prev_ano_4")  
     w_observacoes          = Request("w_observacoes")
     w_cd_indicador         = Request("w_cd_indicador")
     w_cumulativa           = Request("w_cumulativa")
     w_quantidade           = Request("w_quantidade")
     w_exequivel            = Request("w_exequivel")
     w_situacao_atual       = Request("w_situacao_atual")
     w_justificativa_inex   = Request("w_justificativa_inex")
     w_outras_medidas       = Request("w_outras_medidas")
     w_indice_apurado       = Request("w_indice_apurado")
     w_apuracao_ind         = Request("w_apuracao_ind")
  ElseIf O = "L" Then
     DB_GetSolicData_IS RS, w_chave, SG
     w_cd_programa = RS("cd_programa")
     w_ds_programa = RS("ds_programa")  
     If cDbl(Nvl(RS("tit_exec"),0))    = cDbl(w_usuario) or _
        cDbl(Nvl(RS("subst_exec"),0))  = cDbl(w_usuario) or _
        cDbl(Nvl(RS("titular"),0))     = cDbl(w_usuario) or _
        cDbl(Nvl(RS("substituto"),0))  = cDbl(w_usuario) or _
        cDbl(Nvl(RS("executor"),0))    = cDbl(w_usuario) or _
       (cDbl(Nvl(RS("cadastrador"),0)) = cDbl(w_usuario) and P1 < 2) or _
        cDbl(Nvl(RS("solicitante"),0)) = cDbl(w_usuario)  _
     Then
        If Nvl(RS("inicio_real"),"") > "" or (Nvl(RS("sg_tramite"),"--") <> "EE" and P1 > 1) Then
           w_acesso = 0
        Else
           w_acesso = 1
        End If
     Else
        w_acesso = 0 
     End If
     DesconectaBD
     ' Recupera todos os registros para a listagem
     DB_GetSolicIndic_IS RS, w_chave, null, "LISTA"
     RS.Sort = "ordem"
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
     ' Recupera os dados do endereço informado
     DB_GetSolicIndic_IS RS, w_chave, w_chave_aux, "REGISTRO"
     w_cd_unidade_medida    = RS("cd_unidade_medida")
     w_cd_periodicidade     = RS("cd_periodicidade")
     w_cd_base_geografica   = RS("cd_base_geografica")
     w_categoria_analise    = RS("categoria_analise")
     w_titulo               = RS("titulo")
     w_ordem                = RS("ordem")
     w_conceituacao         = RS("conceituacao")    
     w_interpretacao        = RS("interpretacao")
     w_usos                 = RS("usos")
     w_limitacoes           = RS("limitacoes")
     w_comentarios          = RS("comentarios")
     w_fonte                = RS("fonte")
     w_formula              = RS("formula")
     w_tipo_in              = RS("tipo")
     w_indice_ref           = FormatNumber(cDbl(Nvl(RS("valor_referencia"),0)),2)
     w_apuracao_ref         = FormataDataEdicao(RS("apuracao_referencia"))    
     w_observacoes          = RS("observacao")
     w_cd_indicador         = RS("cd_indicador")
     w_cumulativa           = RS("cumulativa")
     w_quantidade           = FormatNumber(cDbl(Nvl(RS("quantidade"),0)),2)
     w_situacao_atual       = RS("situacao_atual")
     w_exequivel            = RS("exequivel")
     w_justificativa_inex   = RS("justificativa_inexequivel")
     w_outras_medidas       = RS("outras_medidas")
     w_prev_ano_1           = FormatNumber(cDbl(Nvl(RS("previsao_ano_1"),0)),2)
     w_prev_ano_2           = FormatNumber(cDbl(Nvl(RS("previsao_ano_2"),0)),2)
     w_prev_ano_3           = FormatNumber(cDbl(Nvl(RS("previsao_ano_3"),0)),2)
     w_prev_ano_4           = FormatNumber(cDbl(Nvl(RS("previsao_ano_4"),0)),2) 
     w_indice_apurado       = FormatNumber(cDbl(Nvl(RS("valor_apurado"),0)),2)
     w_apuracao_ind         = FormataDataEdicao(RS("apuracao_indice"))  
     w_nm_unidade_medida    = RS("nm_unidade_medida")
     w_nm_cumulativa        = RS("nm_cumulativa")
     w_nm_periodicidade     = RS("nm_periodicidade")
     w_nm_base_geografica   = RS("nm_base_geografica")
     If RS("cd_indicador") > "" Then
        w_nm_programada       = "Sim"
     Else   
        w_nm_programada       = "Não"
     End If   
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
        If (P1 <> 2 and P1 <> 3 and P1 <> 5) or O = "I" Then
           If w_cd_indicador = "" Then
              Validate "w_titulo", "Título", "", "1", "2", "200", "1", "1"
              Validate "w_cd_unidade_medida", "Unidade de medida", "SELECT", "1", "1", "18", "1", "1"
              Validate "w_cd_periodicidade", "Periodicidade", "SELECT", "1", "1", "18", "1", "1"
              Validate "w_cd_base_geografica", "Base geográfica", "SELECT", "1", "1", "18", "1", "1"
           End If
           Validate "w_quantidade", "Índice programado", "VALOR", "1", 4, 18, "", "0123456789.,"
           CompValor "w_quantidade", "Índice programado", ">", "0", "zero"
           If w_cd_indicador = "" Then
              Validate "w_indice_ref", "Índice referência", "VALOR", "", 4, 18, "", "0123456789.,"
              Validate "w_apuracao_ref", "Data de referência", "DATA", "", 10, 10, "", "0123456789/"
           End If
           Validate "w_ordem", "Ordem", "1", "1", "1", "3", "", "0123456789"
           If w_cd_indicador = "" Then
              Validate "w_fonte", "Fonte", "", "", "3", "200", "1", "1"
              Validate "w_prev_ano_1", "Previsão ano 1", "VALOR", "", 4, 18, "", "0123456789.,"
              Validate "w_prev_ano_2", "Previsão ano 2", "VALOR", "", 4, 18, "", "0123456789.,"
              Validate "w_prev_ano_3", "Previsão ano 3", "VALOR", "", 4, 18, "", "0123456789.,"
              Validate "w_prev_ano_4", "Previsão ano 4", "VALOR", "", 4, 18, "", "0123456789.,"   
           End If
           Validate "w_conceituacao", "Conceituação", "", "1", "3", "2000", "1", "1"
           Validate "w_interpretacao", "Interpretacao", "", "", "3", "2000", "1", "1"
           Validate "w_usos", "Usos", "", "", "3", "2000", "1", "1"
           Validate "w_limitacoes", "Limitações", "", "", "3", "2000", "1", "1"
           Validate "w_categoria_analise", "Categoria sugeridas para análise", "", "", "3", "2000", "1", "1"
           Validate "w_comentarios", "Dados estatísticos e comentários", "", "", "3", "2000", "1", "1"
           If w_cd_indicador = "" Then
              Validate "w_formula", "Fórmula de cáculo", "", "", "3", "4000", "1", "1"
           End If
           Validate "w_observacoes", "Observações", "", "", "3", "4000", "1", "1"
        Else 
           Validate "w_indice_apurado", "Índice apurado", "VALOR", "", 4, 18, "", "0123456789.,"
           Validate "w_apuracao_ind", "Data de apuração", "DATA", "", 10, 10, "", "0123456789/"
           Validate "w_situacao_atual", "Situação atual", "", "", "2", "4000", "1", "1"
           ShowHTML "  if (theForm.w_exequivel[1].checked && theForm.w_justificativa_inex.value == '') {"
           ShowHTML "     alert ('Justifique porque o indicador não será cumprido!');"
           ShowHTML "     theForm.w_justificativa_inex.focus();"
           ShowHTML "     return false;"
           ShowHTML "  } else { if (theForm.w_exequivel[0].checked) "
           ShowHTML "     theForm.w_justificativa_inex.value = '';"
           ShowHTML "   }"
           ShowHTML "  if (theForm.w_exequivel[1].checked && theForm.w_outras_medidas.value == '') {"
           ShowHTML "     alert ('Indique quais são as medidas necessárias para o cumprimento do indicador!');"
           ShowHTML "     theForm.w_outras_medidas.focus();"
           ShowHTML "     return false;"
           ShowHTML "  } else { if (theForm.w_exequivel[0].checked) "
           ShowHTML "     theForm.w_outras_medidas.value = '';"
           ShowHTML "   }"
           Validate "w_justificativa_inex", "Justificativa", "", "", "2", "1000", "1", "1"
           Validate "w_outras_medidas", "Medidas", "", "", "2", "1000", "1", "1"     
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
  ShowHTML "     <tr><td><font size=""1"">Programa Codº " & w_cd_programa & " - " & w_ds_programa & "</td>"
  If O = "L" Then
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    If cDbl(w_acesso) = 1 and (P1 <> 2 and P1 <> 3 and P1 <> 5) Then
       ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&w_cd_programa=" & w_cd_programa &  "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    Else
       ShowHTML "<tr><td><font size=""2"">&nbsp;"
    End If
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Indicador</font></td>"
    ShowHTML "          <td><font size=""1""><b>PPA</font></td>"
    ShowHTML "          <td><font size=""1""><b>Cumulativa</font></td>"
    ShowHTML "          <td><font size=""1""><b>Tipo</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=9 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
       While Not RS.EOF
          If RS("cd_indicador") > "" Then
             w_ppa = "sim"
          Else
             w_ppa = "não"
          End If
          If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
          ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
          ShowHTML "        <td><font size=""1""><A class=""HL"" HREF=""#"" onClick=""window.open('Programa.asp?par=AtualizaIndicador&O=V&w_chave=" & w_chave & "&w_chave_aux=" & RS("sq_indicador") & "&w_tipo=Volta&P1=10&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "','Indicador','width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no'); return false;"" title=""Clique para exibir os dados!"">" &RS("titulo") & "</A></td>"
          ShowHTML "        <td align=""center""><font size=""1"">" & w_ppa & "</td>"
          ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(RS("cumulativa"),"---")& "</td>"
          ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(RS("nm_tipo"),"---")& "</td>"
          ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
          If cDbl(w_acesso) = 1 Then
             If P1 = 2 or P1 = 3 or P1 = 5 Then
                ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & w_chave & "&w_chave_aux=" & RS("sq_indicador") & "&w_cd_programa=" & w_cd_programa & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Atualizar</A>&nbsp"
             Else
                ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & w_chave & "&w_chave_aux=" & RS("sq_indicador") & "&w_cd_programa=" & w_cd_programa & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
             End If
             If (Nvl(RS("cd_indicador"),"") = "") and (P1 <> 2 and P1 <> 3 and P1 <> 5) Then
                ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "GRAVA&R=" & w_pagina & par & "&O=E&w_chave=" & w_chave & "&w_chave_aux=" & RS("sq_indicador") & "&w_cd_programa=" & w_cd_programa & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ onClick=""return confirm('Confirma a exclusão do registro?');"">Excluir</A>&nbsp"
             End If
          Else
             ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "AtualizaIndicador" & "&R=" & w_Pagina & par & "&O=V&w_chave=" & w_chave & "&w_chave_aux=" & RS("sq_indicador") & "&w_cd_programa=" & w_cd_programa & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Exibir</A>&nbsp"
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
  ElseIf Instr("IAEV",O) > 0 Then
    If InStr("EV",O) Then
       w_Disabled = " DISABLED "
    End If
    AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_chave_aux & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_cd_programa"" value=""" & w_cd_programa & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_data_hora"" value=""" & RS_menu("data_hora") &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & RS_menu("sq_menu") &""">"
    If (P1 <> 2 and P1 <> 3 and P1 <> 5) or O = "I" Then
       If w_cd_indicador > "" and O <> "E" and O <> "V" Then
          ShowHTML "<INPUT type=""hidden"" name=""w_titulo"" value=""" & w_titulo &""">"
          ShowHTML "<INPUT type=""hidden"" name=""w_cd_unidade_medida"" value=""" & w_cd_unidade_medida &""">"
          ShowHTML "<INPUT type=""hidden"" name=""w_cd_periodicidade"" value=""" & w_cd_periodicidade &""">"
          ShowHTML "<INPUT type=""hidden"" name=""w_cd_base_geografica"" value=""" & w_cd_base_geografica &""">"
          ShowHTML "<INPUT type=""hidden"" name=""w_indice_ref"" value=""" & w_indice_ref &""">"
          ShowHTML "<INPUT type=""hidden"" name=""w_apuracao_ref"" value=""" & w_apuracao_ref &""">"
          ShowHTML "<INPUT type=""hidden"" name=""w_fonte"" value=""" & w_fonte &""">"
          ShowHTML "<INPUT type=""hidden"" name=""w_formula"" value=""" & w_formula &""">"
          ShowHTML "<INPUT type=""hidden"" name=""w_prev_ano_1"" value=""" & w_prev_ano_1 &""">"
          ShowHTML "<INPUT type=""hidden"" name=""w_prev_ano_2"" value=""" & w_prev_ano_2 &""">"
          ShowHTML "<INPUT type=""hidden"" name=""w_prev_ano_3"" value=""" & w_prev_ano_3 &""">"
          ShowHTML "<INPUT type=""hidden"" name=""w_prev_ano_4"" value=""" & w_prev_ano_4 &""">"
       End If
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
       ShowHTML "  <table width=""97%"" border=""0"">"
       If w_cd_indicador > "" and O <> "E" and O <> "V" Then
          w_Disabled = " DISABLED "
       End If
       ShowHTML "    <tr><td><font size=""1""><b><u>I</u>ndicador:</b><br><input " & w_Disabled & " accesskey=""I"" type=""text"" name=""w_titulo"" class=""STI"" SIZE=""90"" MAXLENGTH=""100"" VALUE=""" & w_titulo & """ title=""Informe a denominação do indicador.""></td>"
       ShowHTML "    <tr><td valign=""top""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
       SelecaoUniMedida_IS "Unidade de <U>m</U>edida:", "M", "Selecione a unidade de medida do indicador", w_cd_unidade_medida, "w_cd_unidade_medida", null, null
       If w_cd_indicador > "" and O <> "E" and O <> "V" Then
          w_Disabled = " "
       End If
       MontaTipoIndicador "<b>Tipo de indicador?</b>", w_tipo_in, "w_tipo_in"
       ShowHTML "    <tr><td align=""left""><font size=""1""><b>Índice <u>p</u>rogramado:<br><input accesskey=""P"" type=""text"" name=""w_quantidade"" class=""STI"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_quantidade & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o índice que se deseja alcançar ao final do exercício.""></td>"
       MontaRadioNS "<b>Indicador cumulativo?</b>", w_cumulativa, "w_cumulativa"
       ShowHTML "         </table></td></tr>"
        If w_cd_indicador > "" and O <> "E" and O <> "V" Then
          w_Disabled = " DISABLED "
       End If
       ShowHTML "    <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
       ShowHTML "    <tr><td valign=""top""><font size=""1""><b><u>Í</u>ndice referência:</b><br><input " & w_Disabled & " accesskey=""I"" type=""text"" name=""w_indice_ref"" class=""STI"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_indice_ref & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o índice que será utilizado como linha de base.""></td>"
       ShowHTML "              <td><font size=""1""><b><u>D</u>ata de referência:</b><br><input " & w_Disabled & " accesskey=""A"" type=""text"" name=""w_apuracao_ref"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_apuracao_ref & """ onKeyDown=""FormataData(this,event);"" title=""Informe a data em que foi apurado o índice de referência.(Usar formato dd/mm/aaaa)""></td>"
       If w_cd_indicador > "" and O <> "E" and O <> "V" Then
          w_Disabled = " "
       End If
       ShowHTML "              <td align=""left""><font size=""1""><b>O<u>r</u>dem:<br><INPUT ACCESSKEY=""R"" TYPE=""TEXT"" CLASS=""STI"" NAME=""w_ordem"" SIZE=3 MAXLENGTH=3 VALUE=""" & w_ordem & """ " & w_Disabled & "></td>"
       ShowHTML "         </table></td></tr>"
       If w_cd_indicador > "" and O <> "E" and O <> "V" Then
          w_Disabled = " DISABLED "
       End If
       ShowHTML "    <tr><td><font size=""1""><b>F<u>o</u>nte:</b><br><input " & w_Disabled & " accesskey=""O"" type=""text"" name=""w_fonte"" class=""STI"" SIZE=""90"" MAXLENGTH=""200"" VALUE=""" & w_fonte & """ title=""Fonte do indicador.""></td>"
       ShowHTML "    <tr><td valign=""top""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
       SelecaoPeriodicidade_IS "<U>P</U>eriodicidade:", "P", "Selecione a periodicidade do indicador", w_cd_periodicidade, "w_cd_periodicidade", null, null
       SelecaoBaseGeografica_IS "<U>B</U>ase geográfica:", "B", "Selecione a base geográfica do indicador", w_cd_base_geografica, "w_cd_base_geografica", null, null
       ShowHTML "         </table></td></tr>"
       ShowHTML "    <tr><td valign=""top""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
       ShowHTML "    <tr valign=""top""><td><font size=""1""><b>Previsão 2004:</b><br><input " & w_Disabled & " type=""text"" name=""w_prev_ano_1"" class=""STI"" SIZE=""12"" MAXLENGTH=""18"" VALUE=""" & w_prev_ano_1 & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o índice previsto para o 1º ano  (campo não obrigatório).""></td>"
       ShowHTML "        <td><font size=""1""><b>Previsão 2005:</b><br><input " & w_Disabled & " type=""text"" name=""w_prev_ano_2"" class=""STI"" SIZE=""12"" MAXLENGTH=""18"" VALUE=""" & w_prev_ano_2 & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o índice previsto para o 2º ano  (campo não obrigatório).""></td>"
       ShowHTML "        <td><font size=""1""><b>Previsão 2006:</b><br><input " & w_Disabled & " type=""text"" name=""w_prev_ano_3"" class=""STI"" SIZE=""12"" MAXLENGTH=""18"" VALUE=""" & w_prev_ano_3 & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o índice previsto para o 3º ano  (campo não obrigatório).""></td>"
       ShowHTML "        <td><font size=""1""><b>Previsão 2007:</b><br><input " & w_Disabled & " type=""text"" name=""w_prev_ano_4"" class=""STI"" SIZE=""12"" MAXLENGTH=""18"" VALUE=""" & w_prev_ano_4 & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o índice previsto para o 4º ano  (campo não obrigatório).""></td>"
       ShowHTML "    </table></td></tr>"
       If w_cd_indicador > "" and O <> "E" and O <> "V" Then
          w_Disabled = " "
       End If
       ShowHTML "    <tr><td><font size=""1""><b><u>C</u>onceituação:</b><br><textarea " & w_Disabled & " accesskey=""C"" name=""w_conceituacao"" class=""STI"" ROWS=5 cols=75 title=""Descreva as características que definem o indicador e a forma como ele se expressa, se necessário agregando informações para a compreensão de seu conteúdo."">" & w_conceituacao & "</TEXTAREA></td>"
       ShowHTML "    <tr><td><font size=""1""><b>I<u>n</u>terpretação:</b><br><textarea " & w_Disabled & " accesskey=""N"" name=""w_interpretacao"" class=""STI"" ROWS=5 cols=75 title=""Explique, de maneira sucinta, o tipo de informação obtida com o indicador e o seu significado."">" & w_interpretacao & "</TEXTAREA></td>"
       ShowHTML "    <tr><td><font size=""1""><b><u>U</u>sos:</b><br><textarea " & w_Disabled & " accesskey=""U"" name=""w_usos"" class=""STI"" ROWS=5 cols=75 title=""Descreva as principais formas de utilização dos dados que devem ser consideradas para fins de análise."">" & w_usos & "</TEXTAREA></td>"
       ShowHTML "    <tr><td><font size=""1""><b><u>L</u>imitações:</b><br><textarea " & w_Disabled & " accesskey=""L"" name=""w_limitacoes"" class=""STI"" ROWS=5 cols=75 title=""Informe os fatores que restringem a interpretação do indicador, referentes tanto ao próprio conceito quanto à fonte utilizada."">" & w_limitacoes & "</TEXTAREA></td>"
       ShowHTML "    <tr><td><font size=""1""><b>C<u>a</u>tegorias sugeridas para análise:</b><br><textarea " & w_Disabled & " accesskey=""A"" name=""w_categoria_analise"" class=""STI"" ROWS=5 cols=75 title=""Informe os níveis de desagregação dos dados que podem contribuir para a interpretação da informação do indicador e que sejam efetivamente disponíveis, como, por exemplo, sexo e idade."">" & w_categoria_analise & "</TEXTAREA></td>"
       ShowHTML "    <tr><td><font size=""1""><b><u>D</u>ados estatísticos e comentários:</b><br><textarea " & w_Disabled & " accesskey=""D"" name=""w_comentarios"" class=""STI"" ROWS=5 cols=75 title=""Campo destinado à inserção de informações, resumidas e comentadas, que ilustram a aplicação do indicador com base na situação real observada. Sempre que possível os dados devem ser desagregados por grandes regiões e para anos selecionados da década seguinte."">" & w_comentarios & "</TEXTAREA></td>"
       If w_cd_indicador > "" and O <> "E" and O <> "V" Then
          w_Disabled = " DISABLED "
       End If
       ShowHTML "    <tr><td><font size=""1""><b><u>F</u>órmula de cálculo:</b><br><textarea " & w_Disabled & " accesskey=""F"" name=""w_formula"" class=""STI"" ROWS=5 cols=75 title=""Demonstrar, de forma sucinta e por meio de expressões matemáticas, o algoritmo que permite calcular o valor do indicador."">" & w_formula & "</TEXTAREA></td>"
       If w_cd_indicador > "" and O <> "E" and O <> "V" Then
          w_Disabled = " "
       End If
       ShowHTML "    <tr><td><font size=""1""><b>Ob<u>s</u>ervações:</b><br><textarea " & w_Disabled & " accesskey=""S"" name=""w_observacoes"" class=""STI"" ROWS=5 cols=75 title=""Informe as observações pertinentes (campo não obrigatório)."">" & w_observacoes & "</TEXTAREA></td>"
    Else
       ShowHTML "    <tr><td align=""center"" bgcolor=""#FAEBD7"" colspan=""2"">"
       ShowHTML "      <table border=1 width=""100%"">"
       ShowHTML "        <tr><td valign=""top"" colspan=""2"">"    
       ShowHTML "          <TABLE border=0 WIDTH=""100%"" CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
       ShowHTML "            <tr><td colspan=""2""><font size=""1"">Indicador:<b><br><font size=2>" & w_titulo & "</font></td></tr>"
       ShowHTML "            <tr><td><font size=""1"">Indicador PPA?<b><br>" & w_nm_programada& "</td>"
       ShowHTML "                <td><font size=""1"">Unidade de medida:<b><br>" & w_nm_unidade_medida & "</td></tr>"
       If w_tipo_in = "R" Then
          ShowHTML "            <tr><td><font size=""1"">Tipo do indicador:<b><br>Resultado</td>"
       Else
          ShowHTML "            <tr><td><font size=""1"">Tipo do indicador:<b><br>Processo</td>"
       End If
       ShowHTML "                <td><font size=""1"">Índice programado:<b><br>" & w_quantidade & "</td></tr>"
       ShowHTML "            <tr><td><font size=""1"">Cumulativa?<b><br>" & w_nm_cumulativa & "</td>"
       ShowHTML "                <td><font size=""1"">Índice referência:<b><br>" & w_indice_ref & "</td></tr>"
       ShowHTML "            <tr><td><font size=""1"">Data apuração:<b><br>" & FormataDataEdicao(w_apuracao_ref) & "</td>"
       ShowHTML "                <td><font size=""1"">Fonte:<b><br>" & w_fonte & "</td></tr>"
       ShowHTML "            <tr><td><font size=""1"">Periodicidade:<b><br>" & w_nm_periodicidade & "</td>"
       ShowHTML "                <td><font size=""1"">Base geográfica:<b><br>" & w_nm_base_geografica & "</td></tr>"
    
       ShowHTML "          </TABLE>"
       ShowHTML "      </table>"
       ShowHTML "    <tr bgcolor=""" & conTrBgColor & """><td align=""center"" colspan=""2"">"
       ShowHTML "      <table width=""100%"" border=""0"">"
       ShowHTML "         <tr valign=""top""><td><font size=""1""><b>Previsão:</b><br></td>"
       ShowHTML "           <table border=1 width=""100%"" cellspacing=0><tr valign=""top"">"
       ShowHTML "             <tr><td align=""center""><font size=""1""><b>2004</b></td>"
       ShowHTML "                 <td align=""center""><font size=""1""><b>2005</b></td>"
       ShowHTML "                 <td align=""center""><font size=""1""><b>2006</b></td>"
       ShowHTML "                 <td align=""center""><font size=""1""><b>2007</b></td>"
       ShowHTML "             </tr>"
       ShowHTML "             <tr><td align=""right""><font size=""1"">" & FormatNumber(Nvl(w_prev_ano_1,0),2) & "</td>"
       ShowHTML "                 <td align=""right""><font size=""1"">" & FormatNumber(Nvl(w_prev_ano_2,0),2) & "</td>"
       ShowHTML "                 <td align=""right""><font size=""1"">" & FormatNumber(Nvl(w_prev_ano_3,0),2) & "</td>"
       ShowHTML "                 <td align=""right""><font size=""1"">" & FormatNumber(Nvl(w_prev_ano_4,0),2) & "</td>"
       ShowHTML "             </tr>"
       ShowHTML "           </table></td></tr>"
       ShowHTML "    <tr bgcolor=""" & conTrBgColor & """><td align=""center"" colspan=""2"">"
       ShowHTML "      <table width=""100%"" border=""0"">"
       If w_conceituacao <> "" Then
          ShowHTML "     <tr><td valign=""top""><font size=""1""><b>Conceituação:</b><br>" & w_conceituacao & "</td>"
          ShowHTML "     <tr><td valign=""top""><font size=""1"">&nbsp;</td>"
       End If
       If w_interpretacao <> "" Then
          ShowHTML "     <tr><td valign=""top""><font size=""1""><b>Interpretação:</b><br>" & w_interpretacao & "</td>"
          ShowHTML "     <tr><td valign=""top""><font size=""1"">&nbsp;</td>"
       End If
       If w_usos <> "" Then
          ShowHTML "     <tr><td valign=""top""><font size=""1""><b>Usos:</b><br>" & w_usos & "</td>"
          ShowHTML "     <tr><td valign=""top""><font size=""1"">&nbsp;</td>"
       End If
       If w_limitacoes <> "" Then
          ShowHTML "     <tr><td valign=""top""><font size=""1""><b>Limitações:</b><br>" & w_limitacoes & "</td>"
          ShowHTML "     <tr><td valign=""top""><font size=""1"">&nbsp;</td>"
       End If
       If w_categoria_analise <> "" Then
          ShowHTML "     <tr><td valign=""top""><font size=""1""><b>Categorias sugeridas para análise:</b><br>" & w_categoria_analise & "</td>"
          ShowHTML "     <tr><td valign=""top""><font size=""1"">&nbsp;</td>"
       End If
       If w_comentarios <> "" Then
          ShowHTML "     <tr><td valign=""top""><font size=""1""><b>Dados estatísticos e comentários:</b><br>" & w_comentarios & "</td>"
          ShowHTML "     <tr><td valign=""top""><font size=""1"">&nbsp;</td>"
       End If
       If w_formula <> "" Then
          ShowHTML "     <tr><td valign=""top""><font size=""1""><b>Fórmula de cálculo:</b><br>" & w_formula & "</td>"
          ShowHTML "     <tr><td valign=""top""><font size=""1"">&nbsp;</td>"
       End If
       If w_observacoes <> "" Then
          ShowHTML "     <tr><td valign=""top""><font size=""1""><b>Observações:</b><br>" & w_observacoes & "</td>"
          ShowHTML "     <tr><td valign=""top""><font size=""1"">&nbsp;</td>"
       End If
       ShowHTML "    <tr valign=""top""><td colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
       ShowHTML "    <tr valign=""top""><td><font size=""1""><b><u>Í</u>ndice apurado:</b><br><input " & w_Disabled & " accesskey=""I"" type=""text"" name=""w_indice_apurado"" class=""STI"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_indice_apurado & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o índice que foi apurado.""></td>"
       ShowHTML "              <td><font size=""1""><b>Da<u>t</u>a de apuração:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_apuracao_ind"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_apuracao_ind & """ onKeyDown=""FormataData(this,event);"" title=""Informe a data de apuração do índice.(Usar formato dd/mm/aaaa)""></td>"
       ShowHTML "         </table></td></tr>"
       ShowHTML "    <tr><td valign=""top""><font size=""1""><b><u>S</u>ituação atual do indicador:</b><br><textarea " & w_Disabled & " accesskey=""S"" name=""w_situacao_atual"" class=""STI"" ROWS=5 cols=75 title=""Descreva, de maneria sucinta, qual é a situação atual do indicador."">" & w_situacao_atual & "</TEXTAREA></td>"
       ShowHTML "    <tr valign=""top"">"
       MontaRadioSN "<b>O índice programado será alcançado?</b>", w_exequivel, "w_exequivel"
       ShowHTML "    </tr>"
       ShowHTML "    <tr><td valign=""top""><font size=""1""><b><u>I</u>nformar os motivos que impedem o alcance do índice programado:</b><br><textarea " & w_Disabled & " accesskey=""J"" name=""w_justificativa_inex"" class=""STI"" ROWS=5 cols=75 title=""Informe os motivos que inviabilizam que o índice seja alcançado."">" & w_justificativa_inex & "</TEXTAREA></td>"
       ShowHTML "    <tr><td valign=""top""><font size=""1""><b><u>Q</u>uais as medidas necessárias para que o índice programado seja alcançado?</b><br><textarea " & w_Disabled & " accesskey=""Q"" name=""w_outras_medidas"" class=""STI"" ROWS=5 cols=75 title=""Descreva quais são as medidas que devem ser adotadas para  que a tendencia de não alcance do índice programado possa ser revertida."">" & w_outras_medidas & "</TEXTAREA></td>"
    End If
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
    ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape
  
  Set w_cd_unidade_medida   = Nothing 
  Set w_cd_programa         = Nothing
  Set w_ds_programa         = Nothing  
  Set w_cd_periodicidade    = Nothing 
  Set w_cd_base_geografica  = Nothing 
  Set w_categoria_analise   = Nothing  
  Set w_conceituacao        = Nothing 
  Set w_interpretacao       = Nothing 
  Set w_usos                = Nothing 
  Set w_limitacoes          = Nothing 
  Set w_comentarios         = Nothing 
  Set w_fonte               = Nothing 
  Set w_formula             = Nothing 
  Set w_tipo_in             = Nothing 
  Set w_indice_ref          = Nothing 
  Set w_apuracao_ref        = Nothing
  Set w_indice_apurado      = Nothing
  Set w_apuracao_ind        = Nothing 
  Set w_ppa                 = Nothing 
  Set w_prev_ano_1          = Nothing 
  Set w_prev_ano_2          = Nothing 
  Set w_prev_ano_3          = Nothing 
  Set w_prev_ano_4          = Nothing 
  Set w_observacoes         = Nothing 
  Set w_cumulativa          = Nothing
  Set w_quantidade          = Nothing
  Set w_exequivel           = Nothing
  Set w_situacao_atual      = Nothing
  Set w_justificativa_inex  = Nothing
  Set w_outras_medidas      = Nothing
  
  Set w_chave               = Nothing 
  Set w_chave_aux           = Nothing 
  Set w_titulo              = Nothing 
  Set w_ordem               = Nothing 
  
  Set w_troca               = Nothing 
  Set i                     = Nothing 
  Set w_texto               = Nothing
End Sub
REM =========================================================================
REM Fim da tela de indicadores do programa
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de atualização do indicador da programa
REM -------------------------------------------------------------------------
Sub AtualizaIndicador
  Dim w_chave, w_chave_aux, w_titulo, w_ordem
  Dim w_situacao_atual, w_sq_pessoa, w_sq_unidade
  Dim w_programada, w_cumulativa, w_quantidade, w_nm_programada, w_nm_cumulativa
  Dim w_exequivel, w_justificativa_inex, w_outras_medidas
  Dim w_execucao_fisica(), w_execucao_financeira(), w_referencia()
  Dim w_quantitativo_1, w_quantitativo_2, w_quantitativo_3, w_quantitativo_4, w_quantitativo_5, w_quantitativo_6
  Dim w_quantitativo_7, w_quantitativo_8, w_quantitativo_9, w_quantitativo_10, w_quantitativo_11, w_quantitativo_12
  Dim w_cabecalho, w_fase, w_p2, w_fases
  Dim w_tipo, w_conceituacao, w_interpretacao, w_usos, w_limitacoes, w_comentarios, w_fonte, w_formula, w_indice_ref, w_apuracao_ref
  Dim w_observacao, w_prev_ano_1, w_prev_ano_2, w_prev_ano_3, w_prev_ano_4, w_nm_unidade_medida, w_nm_periodicidade, w_nm_base_geografica
  Dim w_categoria_analise, w_indice_apurado, w_apuracao_ind
  
  Dim w_troca, i, w_texto
  
  w_Chave           = Request("w_Chave")
  w_chave_aux       = Request("w_chave_aux")
  w_troca           = Request("w_troca")
  w_tipo            = uCase(trim(Request("w_tipo")))
  
  DB_GetSolicData_IS RS, w_chave, "ISPRGERAL"
  w_cabecalho = "      <tr><td valign=""top"" colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font  size=""2""><b>Programa: " & RS("titulo") & "</td></tr>"
  
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
     w_sq_pessoa            = Request("w_sq_pessoa")
     w_sq_unidade           = Request("w_sq_unidade")
     w_quantidade           = Request("w_quantidade")
     w_cumulativa           = Request("w_cumulativa")
     w_programada           = Request("w_programada")
     w_conceituacao         = Request("w_conceituacao")
     for i = 0 to i = 12 
        w_execucao_fisica[i]     = Request("w_execucao_fisica[i]")
        w_execucao_financeira[i] = Request("w_execucao_financeira[i]")
     next
  ElseIf O = "L" Then
     ' Recupera todos os registros para a listagem
     DB_GetSolicIndic_IS RS, w_chave, null, "LISTA"
     RS.Sort = "ordem"

  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
     ' Recupera os dados do endereço informado
     DB_GetSolicIndic_IS RS, w_chave, w_chave_aux, "REGISTRO"
     w_titulo               = RS("titulo")
     w_ordem                = RS("ordem")
     w_sq_pessoa            = RS("sq_pessoa")
     w_sq_unidade           = RS("sq_unidade")
     w_situacao_atual       = RS("situacao_atual")
     w_quantidade           = FormatNumber(cDbl(Nvl(RS("quantidade"),0)),2)
     w_cumulativa           = RS("cumulativa")
     w_exequivel            = RS("exequivel")
     w_justificativa_inex   = RS("justificativa_inexequivel")
     w_outras_medidas       = RS("outras_medidas")
     If RS("cd_indicador") > "" Then
        w_nm_programada        = "Sim"
     Else
        w_nm_programada        = "Não"
     End If
     w_nm_cumulativa        = Nvl(RS("nm_cumulativa"),"---")
     w_nm_unidade_medida    = Nvl(RS("nm_unidade_medida"),"---")
     w_nm_periodicidade     = Nvl(RS("nm_periodicidade"),"---")
     w_nm_base_geografica   = Nvl(RS("nm_base_geografica"),"---")
     w_categoria_analise    = Nvl(RS("categoria_analise"),"---")
     w_conceituacao         = Nvl(RS("conceituacao"),"---")
     w_interpretacao        = Nvl(RS("interpretacao"),"---")
     w_usos                 = Nvl(RS("usos"),"---")
     w_limitacoes           = Nvl(RS("limitacoes"),"---")
     w_comentarios          = Nvl(RS("comentarios"),"---")
     w_fonte                = Nvl(RS("fonte"),"---")
     w_formula              = Nvl(RS("formula"),"---")
     w_tipo                 = Nvl(RS("tipo"),"---")
     w_indice_ref           = FormatNumber(cDbl(Nvl(RS("valor_referencia"),0)),2)
     w_indice_apurado       = FormatNumber(cDbl(Nvl(RS("valor_apurado"),0)),2)
     w_apuracao_ref         = Nvl(FormataDataEdicao(RS("apuracao_referencia")),"---")
     w_apuracao_ind         = Nvl(FormataDataEdicao(RS("apuracao_indice")),"---")
     w_prev_ano_1           = cDbl(Nvl(RS("previsao_ano_1"),0))
     w_prev_ano_2           = cDbl(Nvl(RS("previsao_ano_2"),0))
     w_prev_ano_3           = cDbl(Nvl(RS("previsao_ano_3"),0))
     w_prev_ano_4           = cDbl(Nvl(RS("previsao_ano_4"),0))
     w_quantitativo_1       = RS("valor_mes_1")
     w_quantitativo_2       = RS("valor_mes_2")
     w_quantitativo_3       = RS("valor_mes_3")
     w_quantitativo_4       = RS("valor_mes_4")
     w_quantitativo_5       = RS("valor_mes_5")
     w_quantitativo_6       = RS("valor_mes_6")
     w_quantitativo_7       = RS("valor_mes_7")
     w_quantitativo_8       = RS("valor_mes_8")
     w_quantitativo_9       = RS("valor_mes_9")
     w_quantitativo_10      = RS("valor_mes_10")
     w_quantitativo_11      = RS("valor_mes_11")
     w_quantitativo_12      = RS("valor_mes_12")
     w_observacao           = Nvl(RS("observacao"),"---")
     DesconectaBD
  ElseIf Nvl(w_sq_pessoa,"") = "" Then
     ' Se o indicador não tiver responsável atribuído, recupera o responsável pela ação
     DB_GetSolicData_IS RS, w_chave, "ISPRGERAL"
     w_sq_pessoa            = RS("solicitante")
     w_sq_unidade           = RS("sq_unidade_resp")
  End If
  If w_tipo = "WORD" Then
      Response.ContentType = "application/msword"
  Else
     Cabecalho
  End If
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>" & conSgSistema & " - Indicador do programa</TITLE>"
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
        ShowHTML "     alert ('Justifique porque o indicador não será cumprido!');"
        ShowHTML "     theForm.w_justificativa_inex.focus();"
        ShowHTML "     return false;"
        ShowHTML "  } else { if (theForm.w_exequivel[0].checked) "
        ShowHTML "     theForm.w_justificativa_inex.value = '';"
        ShowHTML "   }"
        ShowHTML "  if (theForm.w_exequivel[1].checked && theForm.w_outras_medidas.value == '') {"
        ShowHTML "     alert ('Indique quais são as medidas necessárias para o cumprimento do indicador!');"
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
     ShowHTML "&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Gerar word"" SRC=""images/word.gif"" onClick=""window.open('Programa.asp?par=AtualizaIndicador&O=V&w_chave=" & w_chave & "&w_chave_aux=" & w_chave_aux & "&w_tipo=WORD&P1=10&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "','IndicadorWord','width=600, height=350, top=65, left=65, menubar=yes, scrollbars=yes, resizable=yes, status=no');"">"
     ShowHTML "</td></tr>"
  End If
  If O = "L" Then
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "  <tr><td colspan=""2""><font size=""3""></td>"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount & "</td></tr>"
    ShowHTML "  <tr><td align=""center"" colspan=""3"">"
    ShowHTML "      <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Indicador</font></td>"
    ShowHTML "          <td><font size=""1""><b>PPA</font></td>"
    ShowHTML "          <td><font size=""1""><b>Data apuracao</font></td>"
    ShowHTML "          <td><font size=""1""><b>Indice referência</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    ShowHTML "        </tr>"
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
           ShowHtml Indicadorlinha(w_chave, Rs("sq_indicador"), Rs("titulo"), Rs("apuracao_referencia"), Rs("indice_referencia"), null, "<b>", w_fase, "ETAPA")
        Else
           ShowHtml Indicadorlinha(w_chave, Rs("sq_indicador"), Rs("titulo"), Rs("apuracao_referencia"), Rs("indice_referencia"), null, "<b>", "N", "ETAPA")
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
       ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
       ShowHTML "<INPUT type=""hidden"" name=""w_cumulativa"" value=""" & w_cumulativa & """>"
       ShowHTML "<INPUT type=""hidden"" name=""w_quantidade"" value=""" & w_quantidade & """>"
    End If
    ShowHTML "    <tr><td align=""center"" bgcolor=""#FAEBD7"" colspan=""2"">"
    ShowHTML "      <table border=1 width=""100%"">"
    ShowHTML "        <tr><td valign=""top"" colspan=""2"">"    
    ShowHTML "          <TABLE border=0 WIDTH=""100%"" CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "            <tr><td colspan=""2""><font size=""1"">Indicador:<b><br><font size=2>" & w_titulo & "</font></td></tr>"
    ShowHTML "            <tr><td><font size=""1"">Indicador PPA?<b><br>" & w_nm_programada& "</td>"
    ShowHTML "                <td><font size=""1"">Unidade de medida:<b><br>" & w_nm_unidade_medida & "</td></tr>"
    If w_tipo = "R" Then
       ShowHTML "            <tr><td><font size=""1"">Tipo do indicador:<b><br>Resultado</td>"
    Else
       ShowHTML "            <tr><td><font size=""1"">Tipo do indicador:<b><br>Processo</td>"
    End If
    ShowHTML "                <td><font size=""1"">Índice programado:<b><br>" & w_quantidade & "</td></tr>"
    ShowHTML "            <tr><td><font size=""1"">Cumulativa?<b><br>" & w_nm_cumulativa & "</td>"
    ShowHTML "                <td><font size=""1"">Índice referência:<b><br>" & w_indice_ref & "</td></tr>"
    ShowHTML "            <tr><td><font size=""1"">Data apuração:<b><br>" & FormataDataEdicao(w_apuracao_ref) & "</td>"
    ShowHTML "                <td><font size=""1"">Fonte:<b><br>" & w_fonte & "</td></tr>"
    ShowHTML "            <tr><td><font size=""1"">Periodicidade:<b><br>" & w_nm_periodicidade & "</td>"
    ShowHTML "                <td><font size=""1"">Base geográfica:<b><br>" & w_nm_base_geografica & "</td></tr>"
    
    ShowHTML "          </TABLE>"
    ShowHTML "      </table>"
    ShowHTML "    <tr bgcolor=""" & conTrBgColor & """><td align=""center"" colspan=""2"">"
    ShowHTML "      <table width=""100%"" border=""0"">"
    ShowHTML "         <tr valign=""top""><td><font size=""1""><b>Previsão:</b><br></td>"
    ShowHTML "           <table border=1 width=""100%"" cellspacing=0><tr valign=""top"">"
    ShowHTML "             <tr><td align=""center""><font size=""1""><b>2004</b></td>"
    ShowHTML "                 <td align=""center""><font size=""1""><b>2005</b></td>"
    ShowHTML "                 <td align=""center""><font size=""1""><b>2006</b></td>"
    ShowHTML "                 <td align=""center""><font size=""1""><b>2007</b></td>"
    ShowHTML "             </tr>"
    ShowHTML "             <tr><td align=""right""><font size=""1"">" & FormatNumber(w_prev_ano_1,2) & "</td>"
    ShowHTML "                 <td align=""right""><font size=""1"">" & FormatNumber(w_prev_ano_2,2) & "</td>"
    ShowHTML "                 <td align=""right""><font size=""1"">" & FormatNumber(w_prev_ano_3,2) & "</td>"
    ShowHTML "                 <td align=""right""><font size=""1"">" & FormatNumber(w_prev_ano_4,2) & "</td>"
    ShowHTML "             </tr>"
    ShowHTML "           </table></td></tr>"
    ShowHTML "    <tr bgcolor=""" & conTrBgColor & """><td align=""center"" colspan=""2"">"
    ShowHTML "      <table width=""100%"" border=""0"">"
    If w_conceituacao <> "---" Then
       ShowHTML "     <tr><td valign=""top""><font size=""1""><b>Conceituação:</b><br>" & w_conceituacao & "</td>"
       ShowHTML "     <tr><td valign=""top""><font size=""1"">&nbsp;</td>"
    End If
    If w_interpretacao <> "---" Then
       ShowHTML "     <tr><td valign=""top""><font size=""1""><b>Interpretação:</b><br>" & w_interpretacao & "</td>"
       ShowHTML "     <tr><td valign=""top""><font size=""1"">&nbsp;</td>"
    End If
    If w_usos <> "---" Then
       ShowHTML "     <tr><td valign=""top""><font size=""1""><b>Usos:</b><br>" & w_usos & "</td>"
       ShowHTML "     <tr><td valign=""top""><font size=""1"">&nbsp;</td>"
    End If
    If w_limitacoes <> "---" Then
       ShowHTML "     <tr><td valign=""top""><font size=""1""><b>Limitações:</b><br>" & w_limitacoes & "</td>"
       ShowHTML "     <tr><td valign=""top""><font size=""1"">&nbsp;</td>"
    End If
    If w_categoria_analise <> "---" Then
       ShowHTML "     <tr><td valign=""top""><font size=""1""><b>Categorias sugeridas para análise:</b><br>" & w_categoria_analise & "</td>"
       ShowHTML "     <tr><td valign=""top""><font size=""1"">&nbsp;</td>"
    End If
    If w_comentarios <> "---" Then
       ShowHTML "     <tr><td valign=""top""><font size=""1""><b>Dados estatísticos e comentários:</b><br>" & w_comentarios & "</td>"
       ShowHTML "     <tr><td valign=""top""><font size=""1"">&nbsp;</td>"
    End If
    If w_formula <> "---" Then
       ShowHTML "     <tr><td valign=""top""><font size=""1""><b>Fórmula de cálculo:</b><br>" & w_formula & "</td>"
       ShowHTML "     <tr><td valign=""top""><font size=""1"">&nbsp;</td>"
    End If
    If w_observacao <> "---" Then
       ShowHTML "     <tr><td valign=""top""><font size=""1""><b>Observações:</b><br>" & w_observacao & "</td>"
       ShowHTML "     <tr><td valign=""top""><font size=""1"">&nbsp;</td>"
    End If
    ShowHTML "     <tr><td valign=""top""><table width=""100%"" border=""0"">"
    ShowHTML "     <tr><td valign=""top""><font size=""1""><b>Índice apurado:</b><br>" & w_indice_apurado & "</td>"
    If w_apuracao_ind <> "---" Then
       ShowHTML "      <td valign=""top""><font size=""1""><b>Data apuração:</b><br>" & w_apuracao_ind & "</td>"
    End If
    ShowHTML "     </table>"  
    ShowHTML "     <tr><td valign=""top""><font size=""1"">&nbsp;</td>"
    If w_situacao_atual <> "---" Then
       ShowHTML "     <tr><td valign=""top""><font size=""1""><b>Situação atual do indicador:</b><br>" & Nvl(w_situacao_atual,"---") & "</td>"    
       ShowHTML "     <tr><td valign=""top""><font size=""1"">&nbsp;</td>"
    End If
    If w_exequivel <> "---" Then
       ShowHTML "     <tr><td valign=""top""><font size=""1""><b>O índice programado será alcançado?</b><br>" & RetornaSimNao(w_exequivel) & "</td>"    
       ShowHTML "     <tr><td valign=""top""><font size=""1"">&nbsp;</td>"
    End If
    If w_exequivel = "N" Then
       ShowHTML "     <tr><td valign=""top""><font size=""1""><b>Infomar os motivos quem impedem o alcance do índice programado:</b><br>" & Nvl(w_justificativa_inex,"---") & "</td>"
       ShowHTML "     <tr><td valign=""top""><font size=""1"">&nbsp;</td>"
       ShowHTML "     <tr><td valign=""top""><font size=""1""><b>Quais medidas necessárias para que o índice programado seja alcançado?:</b><br>" & Nvl(w_outras_medidas,"---") & "</td>"
    End If
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
    If w_tipo <> "WORD" Then
       ShowHTML "</FORM>"
    End If
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  If w_tipo <> "WORD" Then
     Rodape
  End If
  Set RS1                       = Nothing 
  Set w_sq_pessoa               = Nothing
  Set w_sq_unidade              = Nothing
  Set w_situacao_atual          = Nothing
  Set w_fase                    = Nothing
  Set w_p2                      = Nothing

  Set w_chave                   = Nothing 
  Set w_chave_aux               = Nothing 
  Set w_titulo                  = Nothing 
  Set w_ordem                   = Nothing
  
  Set w_troca                   = Nothing 
  Set i                         = Nothing 
  Set w_texto                   = Nothing
End Sub
REM =========================================================================
REM Fim da tela de atualização dos indicadores do programa
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de restrições do programa
REM -------------------------------------------------------------------------
Sub Restricoes
  Dim w_chave, w_chave_aux, w_cd_tipo_restricao
  Dim w_cd_tipo_inclusao, w_cd_competencia, w_inclusao
  Dim w_descricao, w_providencia, w_superacao, w_relatorio, w_tempo_habil
  Dim w_observacao_monitor, w_observacao_controle, w_nm_tipo_restricao
  Dim w_acesso, w_cabecalho, w_tipo, w_cd_programa, w_ds_programa
  
  Dim w_troca, i, w_texto
  
  w_Chave           = Request("w_Chave")
  w_chave_aux       = Request("w_chave_aux")
  w_troca           = Request("w_troca")
  w_tipo            = Request("w_tipo")
  
  DB_GetSolicData_IS RS, w_chave, SG
  w_cd_programa = RS("cd_programa")
  w_ds_programa = RS("ds_programa")
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
  w_cabecalho = "      <tr><td valign=""top"" colspan=""3"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font  size=""2""><b>Programa: " & RS("titulo") & "</td></tr>"
  DesconectaBD
  
  If w_troca > "" Then ' Se for recarga da página
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
  ElseIf O = "L" Then
     ' Recupera todos os registros para a listagem
     DB_GetRestricao_IS RS, SG, w_chave, null
     RS.Sort = "inclusao desc"
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
     DB_GetRestricao_IS RS, SG, w_chave, w_chave_aux
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
        Validate "w_cd_tipo_restricao", "Tipo da restrição", "SELECT", "1", "1", "18", "", "1"
        'Validate "w_cd_tipo_inclusao", "Tipo de inclusão", "", "", "1", "2", "1", "1"
        'Validate "w_cd_competencia", "Competência", "", "", "1", "2", "1", "1"
        Validate "w_descricao", "Descrição", "", "1", "3", "4000", "1", "1"
        Validate "w_providencia", "Providência", "", "", "3", "4000", "1", "1"
        'Validate "w_superacao", "Superação", "DATA", "", 10, 10, "", "0123456789/"
        'Validate "w_observacao_controle", "Observação controle", "", "", "3", "4000", "1", "1"
        'Validate "w_observacao_monitor", "Observação monitor", "", "", "3", "4000", "1", "1"
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
    ShowHTML "     <tr><td><font size=""1"">Programa Codº " & w_cd_programa & " - " & w_ds_programa & "</td>"
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    If cDbl(w_acesso) = 1 Then
       ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    Else
       ShowHTML "<tr><td><font size=""2"">&nbsp;"
    End If
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Descrição</font></td>"
    ShowHTML "          <td><font size=""1""><b>Tipo restricao</font></td>"
    'ShowHTML "          <td><font size=""1""><b>Tipo inclusão</font></td>"
    'ShowHTML "          <td><font size=""1""><b>Inclusão</font></td>"
    'ShowHTML "          <td><font size=""1""><b>Superação</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=9 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
       While Not RS.EOF
          If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
          ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
          ShowHTML "        <td><font size=""1""><A class=""HL"" HREF=""#"" onClick=""window.open('" & w_pagina & par & "&O=V&w_chave=" & w_chave & "&w_chave_aux=" & RS("sq_restricao") &  "&w_tipo=Volta&P1=10&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "','Restricao','width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no'); return false;"" title=""Clique para exibir os dados!"">" &RS("descricao") & "</A></td>"
          ShowHTML "        <td><font size=""1"">" & RS("nm_tp_restricao") & "</td>"
          'ShowHTML "        <td align=""center""><font size=""1"">" & NVL(RS("cd_tipo_inclusao"),"---") & "</td>"
          'ShowHTML "        <td align=""center""><font size=""1"">" & FormataDataEdicao(RS("inclusao"))& "</td>"
          'ShowHTML "        <td align=""center""><font size=""1"">" & NVL(FormataDataEdicao(RS("superacao")),"---")& "</td>"
          ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
          If cDbl(w_acesso) = 1 Then
             ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & w_chave & "&w_chave_aux=" & RS("sq_restricao") &  "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
             ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "GRAVA&R=" & w_pagina & par & "&O=E&w_chave=" & w_chave & "&w_chave_aux=" & RS("sq_restricao") &  "&w_descricao=" & RS("descricao")& "&w_providencia=" & RS("providencia") & "&w_cd_tipo_restricao=" & RS("cd_tipo_restricao") &  "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ onClick=""return confirm('Confirma a exclusão do registro?');"">Excluir</A>&nbsp"
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

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "  <table width=""97%"" border=""0"">"
    ShowHTML "    <tr valign=""top"" >"
    SelecaoTPRestricao_IS "<U>T</U>ipo de restrição:", "T", "Selecione o tipo de restrição", w_cd_tipo_restricao, "w_cd_tipo_restricao", null, null
    'ShowHTML "                        <td><font size=""1""><b>Tipo de <u>I</u>nclusão:</b><br><input " & w_Disabled & " accesskey=""I"" type=""text"" name=""w_cd_tipo_inclusao"" class=""STI"" SIZE=""3"" MAXLENGTH=""2"" VALUE=""" & w_cd_tipo_inclusao & """ title=""Informe o tipo de inclusão da restrição.""></td>"
    'ShowHTML "    <tr valign=""top"" ><td><font size=""1""><b><u>C</u>ompetência:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_cd_competencia"" class=""STI"" SIZE=""3"" MAXLENGTH=""2"" VALUE=""" & w_cd_competencia & """ title=""Informe a competência da restrição.""></td>"
    'ShowHTML "                        <td><font size=""1""><b><u>S</u>uperação:</b><br><input " & w_Disabled & " accesskey=""S"" type=""text"" name=""w_superacao"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_superacao & """ onKeyDown=""FormataData(this,event);"" title=""Data de superação da restrição.""></td>"
    'ShowHTML "    <tr valign=""top"">"
    'MontaRadioSN "<b>Relatório?</b>", w_relatorio, "w_relatorio"
    'MontaRadioSN "<b>Tempo hábil?</b>", w_tempo_habil, "w_tempo_habil"
    ShowHTML "    <tr><td colspan=2><font size=""1""><b><u>D</u>escrição:</b><br><textarea " & w_Disabled & " accesskey=""D"" name=""w_descricao"" class=""STI"" ROWS=5 cols=75 title=""Descreva os fatores que podem prejudicar o andamento do programa. As restrições podem ser administrativas, ambientais, de auditoria, de licitações, financeiras, institucuionais, políticas, tecnológicas, judiciais, etc. Cada tipo de restrição deve ser inserido separadamente."">" & w_descricao & "</TEXTAREA></td>"
    ShowHTML "    <tr><td colspan=2><font size=""1""><b><u>P</u>rovidência:</b><br><textarea " & w_Disabled & " accesskey=""P"" name=""w_providencia"" class=""STI"" ROWS=5 cols=75 title=""Informe as providências que devem ser tomadas para a superação da restrição."">" & w_providencia & "</TEXTAREA></td>"
    'ShowHTML "    <tr><td colspan=2><font size=""1""><b>O<u>b</u>servação de controle:</b><br><textarea " & w_Disabled & " accesskey=""b"" name=""w_observacao_controle"" class=""STI"" ROWS=5 cols=75 title=""Observações de controle."">" & w_observacao_controle & "</TEXTAREA></td>"
    'ShowHTML "    <tr><td colspan=2><font size=""1""><b><u>O</u>bservação do monitor:</b><br><textarea " & w_Disabled & " accesskey=""O"" name=""w_observacao_monitor"" class=""STI"" ROWS=5 cols=75 title=""Observações do monitor."">" & w_observacao_monitor & "</TEXTAREA></td>"
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
        ShowHTML "&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Gerar word"" SRC=""images/word.gif"" onClick=""window.open('" & w_pagina & par & "&O=V&w_chave=" & w_chave & "&w_chave_aux=" & w_chave_aux &  "&w_tipo=WORD&P1=10&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "','RestricaoWord','width=600, height=350, top=65, left=65, menubar=yes, scrollbars=yes, resizable=yes, status=no');"">"
        ShowHTML "</td></tr>"
     End If
     ShowHTML "    <tr><td align=""center"" bgcolor=""#FAEBD7"" colspan=""2"">"
     ShowHTML "      <table border=1 width=""100%"">"
     ShowHTML "        <tr><td valign=""top"" colspan=""2"">"    
     ShowHTML "          <TABLE border=0 WIDTH=""100%"" CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     ShowHTML "            <tr><td colspan=""2""><font size=""1"">Descrição da restrição:<b><br><font size=2>" & Nvl(w_descricao,"---") & "</font></td></tr>"
     ShowHTML "            <tr><td><font size=""1"">Tipo de restrição<b><br>" & Nvl(w_nm_tipo_restricao,"---")& "</td>"
     'ShowHTML "                <td><font size=""1"">Tipo de inclusão:<b><br>" & w_cd_tipo_inclusao & "</td></tr>"
     'ShowHTML "            <tr><td><font size=""1"">Competência:<b><br>" & Nvl(w_cd_competencia,"---") & "</td>"
     'ShowHTML "                <td><font size=""1"">Data superação:<b><br>" & Nvl(w_superacao,"---") & "</td></tr>"
     'ShowHTML "            <tr><td><font size=""1"">Relatório?<b><br>" & RetornaSimNao(w_relatorio) & "</td>"
     'ShowHTML "                <td><font size=""1"">Tempo hábil?<b><br>" & RetornaSimNao(w_tempo_habil) & "</td></tr>"
     ShowHTML "            <tr><td><font size=""1"">Data inclusão:<b><br>" & Nvl(FormataDataEdicao(w_inclusao),"---") & "</td>"    
     ShowHTML "          </TABLE>"
     ShowHTML "      </table>"
     ShowHTML "    <tr bgcolor=""" & conTrBgColor & """><td align=""center"" colspan=""2"">"
     ShowHTML "      <table width=""100%"" border=""0"">"
     ShowHTML "     <tr><td valign=""top""><font size=""1""><b>Providência:</b><br>" & Nvl(w_providencia,"---") & "</td>"
     'ShowHTML "     <tr><td valign=""top""><font size=""1"">&nbsp;</td>"
     'ShowHTML "     <tr><td valign=""top""><font size=""1""><b>Observação de controle:</b><br>" & Nvl(w_observacao_controle,"---") & "</td>"
     'ShowHTML "     <tr><td valign=""top""><font size=""1"">&nbsp;</td>"
     'ShowHTML "     <tr><td valign=""top""><font size=""1""><b>Observação do monitor:</b><br>" & Nvl(w_observacao_monitor,"---") & "</td>"
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
    ShowHTML " alert('Opção não disponível');"
    ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape
  
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
REM Fim da tela de indicadores do programa
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
    ShowHTML "      <tr><td colspan=3><font size=1>Usuários que devem receber emails dos encaminhamentos deste programa.</font></td></tr>"
    ShowHTML "      <tr><td colspan=3 align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    If P1 <> 4 Then 
       ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    Else
       DB_GetSolicData_IS RS1, w_chave, "ISPRGERAL"
       ShowHTML "<tr><td colspan=3 align=""center"" bgcolor=""#FAEBD7""><table border=1 width=""100%""><tr><td>"
       ShowHTML "    <TABLE WIDTH=""100%"" CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
       ShowHTML "        <tr valign=""top"">"
       If RS1("cd_programa") > "" Then
          ShowHTML "          <td><font size=""1""><b>Programa Codº " & RS1("cd_programa") & " - " & RS1("ds_programa") &" </b>"      
       End If
       RS1.Close       
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
        'ShowHTML "        <td><font size=""1"">" & RetornaTipoVisao(RS("tipo_visao")) & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & Replace(Replace(RS("envia_email"),"S","Sim"),"N","Não") & "</td>"
        If P1 <> 4 Then
           ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
           'ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & w_chave & "&w_chave_aux=" & Rs("sq_pessoa") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
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
    ShowHTML "<INPUT type=""hidden"" name=""w_tipo_visao"" value=""0"">"
    ShowHTML "<INPUT type=""hidden"" name=""w_envia_email"" value=""S"">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
    If O = "I" Then
       SelecaoPessoa "<u>P</u>essoa:", "N", "Selecione a pessoa que deve receber e-mails com informações sobre o programa.", w_chave_aux, null, "w_chave_aux", "USUARIOS"
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
     ProgressBar w_dir_volta, UploadID         
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
    AbreSessao 
    DB_GetSolicData_IS RS1, w_chave, SG
    ShowHTML "     <tr><td><font size=""1"">Programa Codº " & RS1("cd_programa") & " - " & RS1("ds_programa") & "</td>"
    RS1.Close
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
       ShowHTML "      <tr><td align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""2""><b><font color=""#BC3131"">ATENÇÃO</font>: o tamanho máximo aceito para o arquivo é de " & cDbl(RS("upload_maximo"))/1024 & " KBytes</b>.</font></td>" 
       ShowHTML "<INPUT type=""hidden"" name=""w_upload_maximo"" value=""" & RS("upload_maximo") & """>" 
    End If 
    
    ShowHTML "      <tr><td><font size=""1""><b><u>T</u>ítulo:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_nome"" class=""STI"" SIZE=""75"" MAXLENGTH=""255"" VALUE=""" & w_nome & """ title=""Informe o tíulo do arquivo.""></td>" 
    ShowHTML "      <tr><td><font size=""1""><b><u>D</u>escrição:</b><br><textarea " & w_Disabled & " accesskey=""D"" name=""w_descricao"" class=""STI"" ROWS=5 cols=65 title=""Descreva o conteúdo do arquivo."">" & w_descricao & "</TEXTAREA></td>" 
    ShowHTML "      <tr><td><font size=""1""><b>A<u>r</u>quivo:</b><br><input " & w_Disabled & " accesskey=""R"" type=""file"" name=""w_caminho"" class=""STI"" SIZE=""80"" MAXLENGTH=""100"" VALUE="""" title=""OBRIGATÓRIO. Clique no botão ao lado para localizar o arquivo. Ele será transferido automaticamente para o servidor."">" 
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
REM Rotina das outras iniciativas
REM -------------------------------------------------------------------------
Sub Iniciativas
  
  Dim w_chave, w_chave_pai, w_chave_aux, w_sq_menu, w_sq_unidade
  
  Dim w_outras_iniciativas, w_nm_ppa_pai, w_cd_ppa_pai, w_nm_ppa, w_cd_ppa, w_nm_pri, w_cd_pri
  Dim w_sq_isprojeto
  
  Dim w_troca, i, w_erro, w_como_funciona, w_cor, w_readonly

  w_chave           = Request("w_chave")
  w_readonly        = ""
  w_erro            = ""
  w_troca           = Request("w_troca")

  
  DB_GetSolicData_IS RS, w_chave, SG
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
     w_sq_isprojeto        = RS("sq_isprojeto")
     DesconectaBD
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    ShowHTML " history.back(1);"
    ScriptClose
  End If
  If cDbl(Nvl(w_sq_isprojeto,0)) = 0 Then
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
  If w_sq_isprojeto > "" Then
     ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Iniciativa prioritária: </b><br>" & w_nm_pri & " </b>"      
  End If
  
  DB_GetOrPrioridadeList RS, w_chave, w_cliente, w_sq_isprojeto
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
    DB_GetSolicData_IS RS, w_chave, SG
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    If RS("sq_acao_ppa") > "" Then
       ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Programa PPA: </b><br>" &RS("cd_ppa_pai")& " - " & RS("nm_ppa_pai") & " </b>"
       ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Ação PPA: </b><br>" &RS("cd_ppa")& " - " & RS("nm_ppa") & " </b>"      
    End If
    If RS("sq_isprojeto") > "" Then
       ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Iniciativa prioritária: </b><br>" & RS("nm_pri") & " </b>"      
    End If
    DesconectaBD
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
    If O = "I" Then
       SelecaoAcaoPPA "Ação <u>P</u>PA:", "P", null, w_sq_acao_ppa, w_chave, w_ano, "w_sq_acao_ppa", "FINANCIAMENTO", null, w_menu
    Else
       ShowHTML "<INPUT type=""hidden"" name=""w_sq_acao_ppa"" value=""" & w_sq_acao_ppa &""">"
       SelecaoAcaoPPA "Ação <u>P</u>PA:", "P", null, w_sq_acao_ppa, w_chave, w_ano, "w_sq_acao_ppa", null, "disabled", w_menu
    End If
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Obse<u>r</u>vações:</b><br><textarea " & w_Disabled & " accesskey=""R"" name=""w_obs_financ"" class=""STI"" ROWS=5 cols=75 title=""Informar fatos ou situações que sejam relevantes para uma melhor compreensão do financiamento da ação."">" & w_obs_financ & "</TEXTAREA></td>"
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
REM Rotina de visualização
REM -------------------------------------------------------------------------
Sub Visual1

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
  ShowHTML "<TITLE>" & conSgSistema & " - Visualização de Ação</TITLE>"
  ShowHTML "</HEAD>"  
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_tipo <> "WORD" Then
     BodyOpenClean "onLoad='document.focus()'; "
  End If
  ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "EMBED") & """><TD ALIGN=""RIGHT""><B><FONT SIZE=4 COLOR=""#000000"">"
  If P1 = 1 Then
     ShowHTML "Relatório Geral por Programa"
  ElseIf P1 = 2 Then
     ShowHTML "Plano Plurianual 2004 - 2007 <BR> Relatório Geral por Programa"
  Else
     ShowHTML "Visualização do Programa"
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
  ShowHTML VisualPrograma(w_chave, "L", w_usuario, P1, P4)

  
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
REM Rotina de visualização do novo layout de relatórios
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
  ShowHTML "<TITLE>" & conSgSistema & " - Visualização do Programa</TITLE>"
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
  If P1 = 1 or P1 = 2 Then
     ShowHTML "Ficha Resumida do Programa <br> Exercício " & w_ano
  Else
     ShowHTML "Programas <br> Exercício " & w_ano
  End If 
  ShowHTML "</B></FONT></DIV></TD></TR>"
  ShowHTML "</TABLE></TD></TR>"
  If w_tipo > "" and w_tipo <> "WORD" Then
     ShowHTML "<tr><td colspan=""2""><div align=""center""><b><font size=""1"">Clique <a class=""HL"" href=""javascript:history.back(1);"">aqui</a> para voltar à tela anterior</b></font></div></td></tr>"
  End If
  
  ' Chama a rotina de visualização dos dados da ação, na opção "Listagem"
  ShowHTML VisualPrograma(w_chave, "L", w_usuario, P1, P4, "sim", "sim", "sim", "sim", "sim", "sim", "sim", "sim", "sim", "sim", "sim")

  
  If w_tipo > "" and w_tipo <> "WORD" Then
     ShowHTML "<tr><td colspan=""2""><div align=""center""><b><font size=""1"">Clique <a class=""HL"" href=""javascript:history.back(1);"">aqui</a> para voltar à tela anterior</b></font></div></td></tr>"
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
  ShowHTML VisualPrograma(w_chave, "V", w_usuario, P1, P4, "", "", "", "", "", "", "", "", "", "", "")

  ShowHTML "<HR>"
  AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"ISPRGERAL",R,O
  ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & w_menu & """>"
  DB_GetSolicData_IS RS, w_chave, "ISPRGERAL"
  ShowHTML "<INPUT type=""hidden"" name=""w_tramite"" value=""" & RS("sq_siw_tramite") & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_cd_programa"" value=""" & RS("cd_programa") & """>"
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
  Dim w_sg_tramite, w_novo_tramite, w_tipo
  
  Dim w_troca, i, w_erro
  
  w_Chave           = Request("w_Chave")
  w_chave_aux       = Request("w_chave_aux")
  w_troca           = Request("w_troca")
  w_tipo            = Nvl(Request("w_tipo"),"")
  
  If w_troca > "" Then ' Se for recarga da página
     w_tramite      = Request("w_tramite")
     w_destinatario = Request("w_destinatario")
     w_novo_tramite = Request("w_novo_tramite")
     w_despacho     = Request("w_despacho")
  Else
     DB_GetSolicData_IS RS, w_chave, "ISPRGERAL"
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
     If P1 <> 1 or (P1 = 1 and w_tipo = "Volta") Then ' Se não for encaminhamento e nem o sub-menu do cadastramento
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

  ' Chama a rotina de visualização dos dados da ação, na opção "Listagem"
  ShowHTML VisualPrograma(w_chave, "V", w_usuario, P1, P4, "", "", "", "", "", "", "", "", "", "", "")

  ShowHTML "<HR>"
  AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"ISPRENVIO",R,O
  ShowHTML MontaFiltro("POST")
  ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & w_menu & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_tramite"" value=""" & w_tramite & """>"

  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "  <table width=""97%"" border=""0"">"
  ShowHTML "    <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
  If P1 <> 1 Then ' Se não for cadastramento
     SelecaoFase "<u>F</u>ase do programa:", "F", "Se deseja alterar a fase atual do programa, selecione a fase para a qual deseja enviá-la.", w_novo_tramite, w_menu, "w_novo_tramite", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='w_destinatario'; document.Form.submit();"""
     ' Se for envio para o cadastramento, exibe apenas as pessoas autorizadas a fazê-lo.
     If w_sg_tramite = "CI" Then
        SelecaoSolicResp "<u>D</u>estinatário:", "D", "Selecione, na relação, um destinatário para o programa.", w_destinatario, w_chave, w_novo_tramite, w_novo_tramite, "w_destinatario", "CADASTRAMENTO"
     Else
        SelecaoPessoa "<u>D</u>estinatário:", "D", "Selecione, na relação, um destinatário para o programa.", w_destinatario, null, "w_destinatario", "USUARIOS"
     End If
  Else
     SelecaoFase "<u>F</u>ase do programa:", "F", "Se deseja alterar a fase atual do programa, selecione a fase para a qual deseja enviá-la.", w_novo_tramite, w_menu, "w_novo_tramite", null, null
     SelecaoPessoa "<u>D</u>estinatário:", "D", "Selecione, na relação, um destinatário para o programa.", w_destinatario, null, "w_destinatario", "USUARIOS"
  End If
  ShowHTML "    <tr><td valign=""top"" colspan=2><font size=""1""><b>D<u>e</u>spacho:</b><br><textarea " & w_Disabled & " accesskey=""E"" name=""w_despacho"" class=""STI"" ROWS=5 cols=75 title=""Informe o que o destinatário deve fazer quando receber o programa."">" & w_despacho & "</TEXTAREA></td>"
  ShowHTML "      </table>"
  ShowHTML "      <tr><td align=""LEFT"" colspan=4><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""STI"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
  ShowHTML "    <tr><td align=""center"" colspan=4><hr>"
  ShowHTML "      <input class=""STB"" type=""submit"" name=""Botao"" value=""Enviar"">"
  If P1 <> 1 Then ' Se não for cadastramento
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
  ShowHTML "<div align=""center"">"
  ShowHTML "<table width=""95%"" border=""0"" cellspacing=""3"">"

  ' Chama a rotina de visualização dos dados da ação, na opção "Listagem"
  ShowHTML VisualPrograma(w_chave, "V", w_usuario, P1, P4, "", "", "", "", "", "", "", "", "", "", "")

  ShowHTML "<HR>"
  AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"ISPRENVIO",R,O
  ShowHTML MontaFiltro("POST")
  ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & w_menu & """>"
  DB_GetSolicData_IS RS, w_chave, "ISPRGERAL"
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
  ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=../" & MontaURL("MESA") & """>"
  If InStr("V",O) > 0 Then
     ScriptOpen "JavaScript"
     CheckBranco
     FormataData
     FormataDataHora
     FormataValor
     ValidateOpen "Validacao"
     Validate "w_inicio_real", "Início da execução", "DATA", 1, 10, 10, "", "0123456789/"
     Validate "w_fim_real", "Término da execução", "DATA", 1, 10, 10, "", "0123456789/"
     CompData "w_inicio_real", "Início da execução", "<=", "w_fim_real", "Término da execução"
     CompData "w_fim_real", "Término da execução", "<=", FormataDataEdicao(FormatDateTime(Date(),2)), "data atual"
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
  ShowHTML "<div align=""center"">"
  ShowHTML "<table width=""95%"" border=""0"" cellspacing=""3"">"

  ' Chama a rotina de visualização dos dados da ação, na opção "Listagem"
  ShowHTML VisualPrograma(w_chave, "V", w_usuario, P1, P4, "", "", "", "", "", "", "", "", "", "", "")

  ShowHTML "<HR>"
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "  <table width=""97%"" border=""0"">"
  ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
  ShowHTML "          <tr>"
  
  ' Verifica se o programa tem indicadores em aberto e avisa o usuário caso isso ocorra.
  'DB_GetSolicIndic_IS RS, w_chave, null, "LISTA"
  'w_cont = 0
  'While NOT RS.EOF
  '   If cDbl(RS("perc_conclusao")) <> 100 Then
  '      w_cont = w_cont + 1
  '   End If
  '   RS.MoveNext
  'Wend
  'If w_cont > 0 Then
  '   ScriptOpen "JavaScript"
  '   ShowHTML "  alert('ATENÇÃO: das " & RS.RecordCount & " etapas desta ação, " & w_cont & " não têm 100% de conclusão!\n\nAinda assim você poderá concluir esta ação.');"
  '   ScriptClose
  'End If
  'DesconectaBD

  AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"ISPRCONC",R,O
  ShowHTML MontaFiltro("POST")
  ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & w_menu & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_concluida"" value=""S"">"
  DB_GetSolicData_IS RS, w_chave, "ISPRGERAL"
  ShowHTML "<INPUT type=""hidden"" name=""w_tramite"" value=""" & RS("sq_siw_tramite") & """>"
  If Nvl(RS("cd_programa"),"") > "" Then
     ShowHTML "              <td valign=""top""><font size=""1""><b>Iní<u>c</u>io da execução:</b><br><input readonly " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_inicio_real"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" &  Nvl(w_inicio_real, "01/01/"&w_ano) & """ onKeyDown=""FormataData(this,event);"" title=""Informe a data de início da execução do programa.(Usar formato dd/mm/aaaa)""></td>"
     ShowHTML "              <td valign=""top""><font size=""1""><b><u>T</u>érmino da execução:</b><br><input readonly " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_fim_real"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & Nvl(w_fim_real, "31/12/"&w_ano) & """ onKeyDown=""FormataData(this,event);"" title=""Informe a data de término da execução do programa.(Usar formato dd/mm/aaaa)""></td>"
  Else
     ShowHTML "              <td valign=""top""><font size=""1""><b>Iní<u>c</u>io da execução:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""" & Nvl(w_inicio_real, "01/01/"&w_ano)& """ class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_inicio_real & """ onKeyDown=""FormataData(this,event);"" title=""Informe a data de início da execução do programa.(Usar formato dd/mm/aaaa)""></td>"
     ShowHTML "              <td valign=""top""><font size=""1""><b><u>T</u>érmino da execução:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""" & Nvl(w_fim_real, "31/12/"&w_ano) & """ class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_fim_real & """ onKeyDown=""FormataData(this,event);"" title=""Informe a data de término da execução do programa.(Usar formato dd/mm/aaaa)""></td>"  
  End If
  DesconectaBD
  ShowHTML "              <td valign=""top""><font size=""1""><b><u>R</u>ecurso executado:</b><br><input " & w_Disabled & " accesskey=""O"" type=""text"" name=""w_custo_real"" class=""STI"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_custo_real & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o valor que foi efetivamente gasto com a execução do programa.""></td>"
  ShowHTML "          </table>"
  ShowHTML "    <tr><td valign=""top""><font size=""1""><b>Nota d<u>e</u> conclusão:</b><br><textarea " & w_Disabled & " accesskey=""E"" name=""w_nota_conclusao"" class=""STI"" ROWS=5 cols=75 title=""Insira informações relevantes sobre o encerramento do exercício."">" & w_nota_conclusao & "</TEXTAREA></td>"
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
Function Indicadorlinha (p_chave,  p_chave_aux, p_titulo, _
                         p_apuracao,  p_indice, p_word,  p_destaque, _
                         p_oper,   p_tipo,     p_loa)
  Dim l_html, RsQuery, l_row

  If p_loa = "S" Then
     p_loa = "Sim"
  Else
     p_loa = "Não"
  End If 
  l_row = ""

  If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
  l_html = l_html & VbCrLf & "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
  l_html = l_html & VbCrLf & "        <td nowrap " & l_row & "><font size=""1"">"
  If cDbl(Nvl(p_word,0)) = 1 Then
     l_html = l_html & VbCrLf & "        <td><font size=""1"">" & p_destaque & p_titulo & "</b>"
  Else
     l_html = l_html & VbCrLf & "<A class=""HL"" HREF=""#"" onClick=""window.open('Programa.asp?par=AtualizaIndicador&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_chave_aux=" & p_chave_aux & "&w_tipo=Volta&P1=10&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "','Indicador','width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no'); return false;"" title=""Clique para exibir os dados!"">" & p_destaque & p_titulo & "</A>"
  End If
  l_html = l_html & VbCrLf & "        <td align=""center"" " & l_row & "><font size=""1"">" & p_loa & "</td>"
  l_html = l_html & VbCrLf & "        <td align=""center"" " & l_row & "><font size=""1"">" & Nvl(FormataDataEdicao(p_apuracao),"---") & "</td>"
  l_html = l_html & VbCrLf & "        <td nowrap align=""right"" " & l_row & "><font size=""1"">" & Nvl(p_indice,"---") & " %</td>"
  If p_oper = "S" Then
     l_html = l_html & VbCrLf & "        <td align=""top"" nowrap " & l_row & "><font size=""1"">"
     ' Se for listagem de indicadores no cadastramento do programa, exibe operações de alteração e exclusão
     If p_tipo = "PROJETO" Then
        l_html = l_html & VbCrLf & "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & p_chave & "&w_chave_aux=" & p_chave_aux & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Alterar"">Alt</A>&nbsp"
        If (uCase(Mid(p_titulo,1,13)) = uCase("NAO INFORMADO")) or  (uCase(Mid(p_titulo,1,13)) <> uCase("NAO INFORMADO") and p_loa = "Não") Then
           l_html = l_html & VbCrLf & "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "GRAVA&R=" & w_pagina & par & "&O=E&w_chave=" & p_chave & "&w_chave_aux=" & p_chave_aux & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ onClick=""return confirm('Confirma a exclusão do registro?');"" title=""Excluir"">Excl</A>&nbsp"
        End If
     ' Caso contrário, é listagem de atualização do indicador. Neste caso, coloca apenas a opção de alteração
     Else
        l_html = l_html & VbCrLf & "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & p_chave & "&w_chave_aux=" & p_chave_aux & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Atualiza dados do indicador"">Atualizar</A>&nbsp"
     End If
     l_html = l_html & VbCrLf & "        </td>"
  Else
     If p_tipo = "ETAPA" Then
        l_html = l_html & VbCrLf & "        <td align=""top"" nowrap " & l_row & "><font size=""1"">"
        l_html = l_html & VbCrLf & "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_Pagina & par & "&O=V&w_chave=" & p_chave & "&w_chave_aux=" & p_chave_aux & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Atualiza dados do indicador"">Exibir</A>&nbsp"
        l_html = l_html & VbCrLf & "        </td>"
     End If
  End If
  l_html = l_html & VbCrLf &  "      </tr>"
  Indicadorlinha = l_html

  Set l_row     = Nothing
  Set l_html    = Nothing
End Function
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de preparação para envio de e-mail relativo a programas
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
     w_html = w_html & "      <tr valign=""top""><td align=""center""><font size=2><b>INCLUSÃO DE PROGRAMA</b></font><br><br><td></tr>" & VbCrLf
  ElseIf p_tipo = 2 Then
     w_html = w_html & "      <tr valign=""top""><td align=""center""><font size=2><b>TRAMITAÇÃO DE PROGRAMA</b></font><br><br><td></tr>" & VbCrLf
  ElseIf p_tipo = 3 Then
     w_html = w_html & "      <tr valign=""top""><td align=""center""><font size=2><b>CONCLUSÃO DE PROGRAMA</b></font><br><br><td></tr>" & VbCrLf
  End IF
  w_html = w_html & "      <tr valign=""top""><td><font size=2><b><font color=""#BC3131"">ATENÇÃO</font>: Esta é uma mensagem de envio automático. Não responda esta mensagem.</b></font><br><br><td></tr>" & VbCrLf


  ' Recupera os dados da ação
  DB_GetSolicData_IS RSM, p_solic, "ISPRGERAL"
  
  w_nome = "Programa " & RSM("titulo")

  w_html = w_html & VbCrLf & "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  w_html = w_html & VbCrLf & "    <table width=""99%"" border=""0"">"
  w_html = w_html & VbCrLf & "      <tr><td><font size=2>Programa: <b>" & RSM("titulo") & "</b></font></td>"
      
  ' Identificação da ação
  w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>EXTRATO DO PROGRAMA</td>"
  w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
  w_html = w_html & VbCrLf & "          <tr valign=""top"">"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Responsável pelo monitoramento:<br><b>" & RSM("nm_sol") & "</b></td>"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Área de planejamento:<br><b>" & RSM("nm_unidade_resp") & "</b></td>"
  w_html = w_html & VbCrLf & "          <tr valign=""top"">"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Data de recebimento:<br><b>" & FormataDataEdicao(RSM("inicio")) & " </b></td>"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Limite para conclusão:<br><b>" & FormataDataEdicao(RSM("fim")) & " </b></td>"
  w_html = w_html & VbCrLf & "          </table>"

  ' Informações adicionais
  If Nvl(RSM("descricao"),"") > "" Then 
     If Nvl(RSM("descricao"),"") > "" Then w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Resultados esperados:<br><b>" & CRLF2BR(RSM("descricao")) & " </b></td>" End If
  End If

  w_html = w_html & VbCrLf & "    </table>"
  w_html = w_html & VbCrLf & "</tr>"

  ' Dados da conclusão do programa, se ele estiver nessa situação
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
REM Rotina de preparação para envio de e-mail relativo restrições
REM Finalidade: preparar os dados necessários ao envio automático de e-mail
REM Parâmetro: p_solic: número de identificação da solicitação. 
REM            p_tipo:  I - Inclusão
REM                     E - Exclusão
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
     w_html = w_html & "      <tr valign=""top""><td align=""center""><font size=2><b>INCLUSÃO DE RESTRIÇÃO</b></font><br><br><td></tr>" & VbCrLf
  ElseIf p_tipo = "E" Then
     w_html = w_html & "      <tr valign=""top""><td align=""center""><font size=2><b>EXCLUSÃO DE RESTRIÇÃO</b></font><br><br><td></tr>" & VbCrLf
  End If
  w_html = w_html & "      <tr valign=""top""><td><font size=2><b><font color=""#BC3131"">ATENÇÃO</font>: Esta é uma mensagem de envio automático. Não responda esta mensagem.</b></font><br><br><td></tr>" & VbCrLf


  ' Recupera os dados do programa
  DB_GetSolicData_IS RSM, p_solic, "ISPRGERAL"

  w_html = w_html & VbCrLf & "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  w_html = w_html & VbCrLf & "    <table width=""99%"" border=""0"">"
  w_html = w_html & VbCrLf & "      <tr><td><font size=2>Programa: <b>" & RSM("titulo") & "</b></font></td>"
      
  ' Identificação do programa
  w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>EXTRATO DO PROGRAMA</td>"
  w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
  w_html = w_html & VbCrLf & "          <tr valign=""top"">"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Responsável pelo monitoramento:<br><b>" & RSM("nm_sol") & "</b></td>"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Área de planejamento:<br><b>" & RSM("nm_unidade_resp") & "</b></td>"
  w_html = w_html & VbCrLf & "          <tr valign=""top"">"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Data de recebimento:<br><b>" & FormataDataEdicao(RSM("inicio")) & " </b></td>"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Limite para conclusão:<br><b>" & FormataDataEdicao(RSM("fim")) & " </b></td>"
  w_html = w_html & VbCrLf & "          </table>"
  
  ' Recupera o e-mail do responsável
  DB_GetPersonData RS, w_cliente, RSM("solicitante"), null, null
  If Instr(w_destinatarios,RS("email") & "; ") = 0 Then w_destinatarios = w_destinatarios & RS("email") & "; " End If
  DesconectaBD


  RSM.Close
  
  ' Identificação da restrição
  w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>EXTRATO DA RESTRIÇÃO</td>"
  w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
  w_html = w_html & VbCrLf & "          <tr valign=""top"">"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Descrição da restrição:<br><b>" & p_descricao & "</b></td>"
  DB_GetTPRestricao_IS RSM, p_tp_restricao, null
  w_html = w_html & VbCrLf & "          <tr valign=""top"">"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Tipo da restrição:<br><b>" & RSM("nome") & "</b></td>"
  RSM.Close
  w_html = w_html & VbCrLf & "          <tr valign=""top"">"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Providência:<br><b>" & Nvl(p_providencia,"---") & "</b></td>"
  w_html = w_html & VbCrLf & "          </table>"
  
  w_html = w_html & VbCrLf & "    </table>"
  w_html = w_html & VbCrLf & "</tr>"
  
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
    
  ' Recupera o e-mail do usuário que está cadastrando a restrição
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
  
  ' Prepara os dados necessários ao envio
  DB_GetCustomerData RS, Session("p_cliente")
  If p_tipo = "I" Then ' Inclusão
     If p_tipo = "I" Then w_assunto = "Inclusão de restrição do programa" End If
  ElseIf p_tipo = "E" Then ' Exclusão
     w_assunto = "Exclusão de restrição do programa"
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
REM Rotina de busca dos programas do PPA
REM -------------------------------------------------------------------------
Sub BuscaPrograma
 
  Dim w_nome, w_cliente, ChaveAux, restricao, campo
  
  w_nome     = UCase(Request("w_nome"))
  w_cliente  = Request("w_cliente")
  w_ano      = Request("w_ano")
  ChaveAux   = Request("ChaveAux")
  restricao  = Request("restricao")
  campo      = Request("campo")
  
  DB_GetProgramaPPA_IS RS, ChaveAux, w_cliente, w_ano, restricao, w_nome
  RS.Sort   = "ds_programa"
    
  Cabecalho
  ShowHTML "<TITLE>Seleção de programas do PPA</TITLE>"
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
  Validate "ChaveAux", "Código", "1", "", "4", "4", "1", "1"
  ShowHTML "  if (theForm.w_nome.value == '' && theForm.ChaveAux.value == '') {"
  ShowHTML "     alert ('Informe um valor para o nome ou para o código!');"
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
  AbreForm  "Form", w_dir&w_Pagina&"BuscaPrograma", "POST", "return(Validacao(this))", null, P1, P2, P3, P4, TP, SG, null, null
  ShowHTML "<INPUT type=""hidden"" name=""w_cliente"" value=""" & w_cliente &""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_ano"" value=""" & w_ano &""">"
  ShowHTML "<INPUT type=""hidden"" name=""restricao"" value=""" & restricao &""">"
  ShowHTML "<INPUT type=""hidden"" name=""campo"" value=""" & campo &""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & w_menu &""">"
  
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2><b><ul>Instruções</b>:<li>Informe parte do nome do programa ou o código do programa.<li>Quando a relação for exibida, selecione o programa desejado clicando sobre o link <i>Selecionar</i>.<li>Após informar o nome do programa ou o código do programa, clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Cancelar</i>, a procura é cancelada.</ul></div>"
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
  ShowHTML "    <table width=""100%"" border=""0"">"
  ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Parte do <U>n</U>ome do programa:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_nome"" size=""50"" maxlength=""100"" value=""" & w_nome & """>"
  ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>C</U>ódigo do programa:<br><INPUT ACCESSKEY=""S"" " & w_Disabled & " class=""sti"" type=""text"" name=""ChaveAux"" size=""5"" maxlength=""4"" value=""" & ChaveAux & """>"
  
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
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=5 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
     Else
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td>"
        ShowHTML "        <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        ShowHTML "          <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        ShowHTML "            <td><font size=""1""><b>Código</font></td>"
        ShowHTML "            <td><font size=""1""><b>Nome</font></td>"
        ShowHTML "            <td><font size=""1""><b>Operações</font></td>"
        ShowHTML "          </tr>"
        While Not RS.EOF
           If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
           ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
           ShowHTML "            <td align=""center""><font size=""1"">" & RS("cd_programa") & "</td>"
           ShowHTML "            <td><font size=""1"">" & RS("ds_programa") & "</td>"
           ShowHTML "            <td><font size=""1""><a class=""ss"" href=""#"" onClick=""javascript:volta('" & RS("cd_programa") & "');"">Selecionar</a>"
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
REM Fim da rotina de busca de área do conhecimento
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

  If SG = "ISPRGERAL" or SG = "VLRPGERAL" Then
    ' Verifica se a Assinatura Eletrônica é válida
    If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
       w_assinatura = "" Then
       
       If O = "E" Then
          DB_GetSolicLog RS, Request("w_chave"), null, "LISTA"
          ' Mais de um registro de log significa que deve ser cancelada, e não excluída.
          ' Nessa situação, não é necessário excluir os arquivos.
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
       Else
          If O = "I" Then
             DB_GetPrograma_IS RS, Request("w_cd_programa"), w_ano, w_cliente, null
             If cDbl(RS("Existe")) > 0 Then
                DesconectaBD
                ScriptOpen "JavaScript"
                ShowHTML "  alert('Programa já cadastrado!');"
                ShowHTML "  history.back(1);"
                ScriptClose
                Exit Sub
             End If 
          End If
          'Recupera 10% dos dias de prazo da tarefa, para emitir o alerta  
          Dim w_dias
          DB_Get10PercentDays_IS RS,Request("w_inicio"), Request("w_fim")
          w_dias = RS("dias")
          DesconectaBD
       End If
       
       DML_PutAcaoGeral_IS O, _
           Request("w_chave"), Request("w_menu"), Session("lotacao"), Request("w_solicitante"), Request("w_proponente"), _
           Session("sq_pessoa"), Request("w_executor"), Request("w_descricao"), Request("w_justificativa"), Request("w_inicio"), Request("w_fim"), Request("w_valor"), _
           Request("w_data_hora"), Request("w_sq_unidade_resp"), Request("w_titulo"), Request("w_prioridade"), Request("w_aviso"), w_dias, _
           Request("w_cidade"), Request("w_palavra_chave"), _
           null, null, null, null, null, null, null, _
           w_ano, w_cliente, Request("w_cd_programa"), null, null, null, null, Request("w_selecao_mp"), Request("w_selecao_se"), _
           Request("w_sq_natureza"), Request("w_sq_horizonte"), w_chave_nova, w_copia, Request("w_sq_unidade_adm"), Request("w_ln_programa")

       If O = "I" Then
          ' Envia e-mail comunicando a inclusão
          SolicMail Nvl(Request("w_chave"), w_chave_nova) ,1
       End If
       ScriptOpen "JavaScript"
       If O = "I" Then
          ' Exibe mensagem de gravação com sucesso
          ShowHTML "  alert('Programa " & Request("w_cd_programa") & " cadastrado com sucesso!');"
          ' Recupera os dados para montagem correta do menu
          DB_GetMenuData RS1, w_menu
          ShowHTML "  parent.menu.location='../Menu.asp?par=ExibeDocs&O=A&w_chave=" & w_chave_nova & "&w_documento=Nr. " & w_chave_nova & "&R=" & R & "&SG=" & RS1("sigla") & "&TP=" & TP & MontaFiltro("GET") & "';"
       ElseIf O = "E" Then
          ShowHTML "  location.href='" & R & "&O=L&R=" & R & "&SG=ISPCAD&w_menu=" & w_menu & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & MontaFiltro("GET") & "';"
       Else
          ' Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link
          If SG = "VLRPGERAL" Then
             O = "P"
          End If
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
  ElseIf SG = "ISPRRESP" Then
    ' Verifica se a Assinatura Eletrônica é válida
    If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
       w_assinatura = "" Then

       DML_PutRespPrograma_IS Request("w_chave"), _
                              Request("w_nm_gerente_programa"), Request("w_fn_gerente_programa"), Request("w_em_gerente_programa"), _
                              Request("w_nm_gerente_executivo"), Request("w_fn_gerente_executivo"), Request("w_em_gerente_executivo"), _
                              Request("w_nm_gerente_adjunto"), Request("w_fn_gerente_adjunto"), Request("w_em_gerente_adjunto")
       
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
  ElseIf SG = "ISPRPROQUA" Then
    ' Verifica se a Assinatura Eletrônica é válida
    If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then
       DML_PutProgQualitativa_IS _
           Request("w_chave"), Request("w_resultados"), Request("w_observacoes"),  Request("w_potencialidades"), null, _
           Request("w_contribuicao_objetivo"), null, Request("w_estrategia_monit"), Request("w_diretriz"), _
           Request("w_metodologia_aval"), SG
          
       ScriptOpen "JavaScript"
       If O = "I" Then
          ' Recupera os dados para montagem correta do menu
          DB_GetMenuData RS1, w_menu
          ShowHTML "  parent.menu.location='../Menu.asp?par=ExibeDocs&O=A&w_chave=" & w_chave_nova & "&w_documento=Nr. " & w_chave_nova & "&R=" & R & "&SG=" & RS1("sigla") & "&TP=" & TP & MontaFiltro("GET") & "';"
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
  ElseIf SG = "ISPRINDIC" Then
    ' Verifica se a Assinatura Eletrônica é válida
    If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then
       DML_PutIndicador_IS O, _
           Request("w_chave"), Request("w_chave_aux"), w_ano, w_cliente, Request("w_cd_programa"), Request("w_cd_unidade_medida"), _
           Request("w_cd_periodicidade"), Request("w_cd_base_geografica"), Request("w_categoria_analise"), Request("w_ordem"), Request("w_titulo"),  _
           Request("w_conceituacao"), Request("w_interpretacao"), Request("w_usos"), Request("w_limitacoes"), Request("w_comentarios"), _
           Request("w_fonte"), Request("w_formula"), Request("w_tipo_in"), Request("w_indice_ref"), Request("w_indice_apurado"), Request("w_apuracao_ref"), Request("w_apuracao_ind"), _
           Request("w_observacoes"), Request("w_cumulativa"), Request("w_quantidade"), Nvl(Request("w_exequivel"),"S"), Request("w_situacao_atual"), _
           Request("w_justificativa_inex"), Request("w_outras_medidas"), Request("w_prev_ano_1"), Request("w_prev_ano_2"), Request("w_prev_ano_3"), Request("w_prev_ano_4"), P1     
       
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
  ElseIf SG = "ISPRRESTR" Then
    ' Verifica se a Assinatura Eletrônica é válida
    If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then
          
       DML_PutRestricao_IS O, SG, Request("w_chave"), Request("w_chave_aux"), null, null, _
           Request("w_cd_tipo_restricao"), _
           Request("w_cd_tipo_inclusao"), Request("w_cd_competencia"), Request("w_superacao"), _
           Request("w_relatorio"), Request("w_tempo_habil"), Request("w_descricao"), _
           Request("w_providencia"), Request("w_observacao_controle"), Request("w_observacao_monitor"), w_ano, w_cliente
          
       If O = "I" or O = "E" Then
          RestricaoMail Request("w_chave"), Request("w_descricao"), Request("w_cd_tipo_restricao"), Request("w_providencia"), O              
       End If
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
  ElseIf SG = "ISPRINTERE" Then
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
  ElseIf SG = "ISPRANEXO" Then
    ' Verifica se a Assinatura Eletrônica é válida
    If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then
       ' Se foi feito o upload de um arquivo  
       Set FS = CreateObject("Scripting.FileSystemObject")
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
                ' Se já há um nome para o arquivo, mantém 
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
          
          ' Se for exclusão e houver um arquivo físico, deve remover o arquivo do disco.  
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
          ShowHTML "  alert('ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!');" 
          ScriptClose 
          Response.End()
          Exit Sub
       End If
       
       ScriptOpen "JavaScript"
       ' Recupera a sigla do serviço pai, para fazer a chamada ao menu 
       DB_GetLinkData RS, Session("p_cliente"), SG 
       ShowHTML "  location.href='" & replace(RS("link"),w_dir,"") & "&O=L&w_chave=" & ul.Texts.Item("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';" 
       DesconectaBD
       ScriptClose
    Else
       ScriptOpen "JavaScript"
       ShowHTML "  alert('Assinatura Eletrônica inválida!');"
       ShowHTML "  history.back(1);"
       ScriptClose
    End If
  ElseIf SG = "ISPRENVIO" Then
    ' Verifica se a Assinatura Eletrônica é válida
    If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then

       DB_GetSolicData_IS RS, Request("w_chave"), "ISPRGERAL"
       If cDbl(RS("sq_siw_tramite")) <> cDbl(Request("w_tramite")) Then
          ScriptOpen "JavaScript"
          ShowHTML "  alert('ATENÇÃO: Outro usuário já encaminhou esta ação para outra fase de execução!');"
          ScriptClose
       Else
          DML_PutProjetoEnvio Request("w_menu"), Request("w_chave"), w_usuario, Request("w_tramite"), Request("w_novo_tramite"), "N", Request("w_observacao"), Request("w_destinatario"), Request("w_despacho"), null, null, null, null
          
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
  ElseIf SG = "ISPRCONC" Then
    ' Verifica se a Assinatura Eletrônica é válida
    If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then

       DB_GetSolicData_IS RS, Request("w_chave"), "ISPRGERAL"
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
  Else
    ScriptOpen "JavaScript"
    ShowHTML "  alert('Bloco de dados não encontrado: " & SG & "');"
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

  'Response.Write PAR
  'Response.End()
  Select Case Par
    Case "INICIAL"              Inicial
    Case "GERAL"                Geral
    Case "RESP"                 Responsaveis
    Case "PROGQUAL"             ProgramacaoQualitativa
    Case "INDICADOR"            Indicadores
    Case "ATUALIZAINDICADOR"    AtualizaIndicador
    Case "RESTRICAO"            Restricoes
    Case "INTERESS"             Interessados
    Case "VISUAL"               Visual
    Case "VISUALE"              VisualE
    Case "EXCLUIR"              Excluir
    Case "ENVIO"                Encaminhamento
    Case "ANEXO"                Anexos
    Case "ANOTACAO"             Anotar
    Case "CONCLUIR"             Concluir
    Case "BUSCAPROGRAMA"        BuscaPrograma
    Case "RECURSOPROGRAMADO"    RecursoProgramado
    Case "GRAVA"                Grava
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


