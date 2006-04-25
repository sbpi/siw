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
<!-- #INCLUDE VIRTUAL="/siw/mod_ac/DB_Tabelas.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DML_Solic.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/mod_ac/DML_Contrato.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/mod_ac/ValidaAcordo.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/mod_ac/VisualAcordo.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/mod_ac/Funcoes.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/cp_upload/_upload.asp" -->

<%
Response.Expires = -1500
REM =========================================================================
REM  /Contratos.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Gerencia as rotinas relativas a controle de contratos e convênios
REM Mail     : alex@sbpi.com.br
REM Criacao  : 23/01/2005 15:01
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
Dim w_Assinatura
Dim p_ativo, p_solicitante, p_prioridade, p_unidade, p_proponente, p_ordena
Dim p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_projeto
Dim p_chave, p_objeto, p_pais, p_uf, p_cidade, p_regiao, p_usu_resp, p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc
Dim w_troca,w_cor, w_filter, w_cliente, w_usuario, w_menu, w_dir, w_dir_volta, UploadID
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
w_pagina     = "Contratos.asp?par="
w_Dir        = "cl_cespe/"
w_dir_volta  = "../"  
w_Disabled   = "ENABLED"

SG           = ucase(Request("SG"))
O            = ucase(Request("O"))
w_cliente    = RetornaCliente()
w_usuario    = RetornaUsuario()
w_menu       = RetornaMenu(w_cliente, SG)

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
          
   w_troca          = ul.Texts.Item("w_troca")  
   w_copia          = ul.Texts.Item("w_copia")  
   p_projeto        = uCase(ul.Texts.Item("p_projeto"))  
   p_ativo          = uCase(ul.Texts.Item("p_ativo"))  
   p_solicitante    = uCase(ul.Texts.Item("p_solicitante"))  
   p_prioridade     = uCase(ul.Texts.Item("p_prioridade"))  
   p_unidade        = uCase(ul.Texts.Item("p_unidade"))  
   p_proponente     = uCase(ul.Texts.Item("p_proponente"))  
   p_ordena         = uCase(ul.Texts.Item("p_ordena"))  
   p_ini_i          = uCase(ul.Texts.Item("p_ini_i"))  
   p_ini_f          = uCase(ul.Texts.Item("p_ini_f"))  
   p_fim_i          = uCase(ul.Texts.Item("p_fim_i"))  
   p_fim_f          = uCase(ul.Texts.Item("p_fim_f"))  
   p_atraso         = uCase(ul.Texts.Item("p_atraso"))  
   p_chave          = uCase(ul.Texts.Item("p_chave"))  
   p_objeto        = uCase(ul.Texts.Item("p_objeto"))  
   p_pais           = uCase(ul.Texts.Item("p_pais"))  
   p_regiao         = uCase(ul.Texts.Item("p_regiao"))  
   p_uf             = uCase(ul.Texts.Item("p_uf"))  
   p_cidade         = uCase(ul.Texts.Item("p_cidade"))  
   p_usu_resp       = uCase(ul.Texts.Item("p_usu_resp"))  
   p_uorg_resp      = uCase(ul.Texts.Item("p_uorg_resp"))  
   p_palavra        = uCase(ul.Texts.Item("p_palavra"))  
   p_prazo          = uCase(ul.Texts.Item("p_prazo"))  
   p_fase           = uCase(ul.Texts.Item("p_fase"))  
   p_sqcc           = uCase(ul.Texts.Item("p_sqcc"))  
    
   P1               = ul.Texts.Item("P1")  
   P2               = ul.Texts.Item("P2")  
   P3               = ul.Texts.Item("P3")  
   P4               = ul.Texts.Item("P4")  
   TP               = ul.Texts.Item("TP")  
   R                = uCase(ul.Texts.Item("R"))  
   w_Assinatura     = uCase(ul.Texts.Item("w_Assinatura"))  
Else  
   w_troca          = Request("w_troca")  
   w_copia          = Request("w_copia")  
   p_projeto        = uCase(Request("p_projeto"))
   p_ativo          = uCase(Request("p_ativo"))  
   p_solicitante    = uCase(Request("p_solicitante"))  
   p_prioridade     = uCase(Request("p_prioridade"))  
   p_unidade        = uCase(Request("p_unidade"))  
   p_proponente     = uCase(Request("p_proponente"))  
   p_ordena         = uCase(Request("p_ordena"))  
   p_ini_i          = uCase(Request("p_ini_i"))  
   p_ini_f          = uCase(Request("p_ini_f"))  
   p_fim_i          = uCase(Request("p_fim_i"))  
   p_fim_f          = uCase(Request("p_fim_f"))  
   p_atraso         = uCase(Request("p_atraso"))  
   p_chave          = uCase(Request("p_chave"))  
   p_objeto        = uCase(Request("p_objeto"))  
   p_pais           = uCase(Request("p_pais"))  
   p_regiao         = uCase(Request("p_regiao"))  
   p_uf             = uCase(Request("p_uf"))  
   p_cidade         = uCase(Request("p_cidade"))  
   p_usu_resp       = uCase(Request("p_usu_resp"))  
   p_uorg_resp      = uCase(Request("p_uorg_resp"))  
   p_palavra        = uCase(Request("p_palavra"))  
   p_prazo          = uCase(Request("p_prazo"))  
   p_fase           = uCase(Request("p_fase"))  
   p_sqcc           = uCase(Request("p_sqcc"))  
    
   P1               = Nvl(Request("P1"),0)  
   P2               = Nvl(Request("P2"),0)  
   P3               = cDbl(Nvl(Request("P3"),1))  
   P4               = cDbl(Nvl(Request("P4"),conPagesize))  
   TP               = Request("TP")  
   R                = uCase(Request("R"))  
   w_Assinatura     = uCase(Request("w_Assinatura"))  
    
   If InStr(SG, "ANEXO") > 0 or InStr(SG, "PARC") > 0 or InStr(SG, "REPR") > 0 Then
      If InStr("IG",O) = 0 and Request("w_chave_aux") = "" Then O = "L" End If
   ElseIf InStr(SG, "ENVIO") > 0 Then
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
  Case "G" 
     w_TP = TP & " - Gerar"
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
     If Instr(uCase(R),"GR_") > 0 or Instr(uCase(R),"PROJETO") > 0 Then
        w_filtro = ""
        If p_projeto > ""  Then 
           DB_GetSolicData RS, p_projeto, "PJGERAL"
           w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Ação <td><font size=1>[<b><A class=""hl"" HREF=""" & "Projeto.asp?par=Visual&O=L&w_chave=" & p_projeto & "&w_tipo=Volta&P1=2&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Exibe as informações do projeto."" target=""_blank"">" & RS("titulo") & "</a></b>]"
        End If
        If p_sqcc > ""  Then 
           DB_GetCCData RS, p_sqcc
           w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Classificação <td><font size=1>[<b>" & RS("nome") & "</b>]"
        End If
        If p_chave       > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Atividade nº <td><font size=1>[<b>" & p_chave & "</b>]" End If
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
        If p_proponente  > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Parceria externa <td><font size=1>[<b>" & p_proponente & "</b>]"                      End If
        If p_objeto     > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Detalhamento <td><font size=1>[<b>" & p_objeto & "</b>]"                            End If
        If p_palavra     > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Responsável <td><font size=1>[<b>" & p_palavra & "</b>]"                     End If
        If p_ini_i       > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Data recebimento <td><font size=1>[<b>" & p_ini_i & "-" & p_ini_f & "</b>]"     End If
        If p_fim_i       > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Limite conclusão <td><font size=1>[<b>" & p_fim_i & "-" & p_fim_f & "</b>]"     End If
        If p_atraso      = "S" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Situação <td><font size=1>[<b>Apenas atrasadas</b>]"                            End If
        If w_filtro > "" Then w_filtro = "<table border=0><tr valign=""top""><td><font size=1><b>Filtro:</b><td nowrap><font size=1><ul>" & w_filtro & "</ul></tr></table>"                    End If
     End If

     DB_GetLinkData RS, w_cliente, SG
     If w_copia > "" Then ' Se for cópia, aplica o filtro sobre todas as demandas visíveis pelo usuário
        DB_GetSolicList rs, RS("sq_menu"), w_usuario, SG, 3, _
           p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
           p_unidade, p_prioridade, p_ativo, p_proponente, _
           p_chave, p_objeto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
           p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, p_projeto, null, null, null
     Else
        DB_GetSolicList rs, RS("sq_menu"), w_usuario, SG, P1, _
           p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
           p_unidade, p_prioridade, p_ativo, p_proponente, _
           p_chave, p_objeto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
           p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, p_projeto, null, null, null
           Select case Request("p_agrega")
              Case "GRACRESPATU"
                 RS.Filter = "executor <> null"
           End Select
     End If

     If p_ordena > "" Then RS.sort = p_ordena & ", inicio" Else RS.sort = "nm_outra_parte, inicio desc" End If
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente  
  If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=../" & MontaURL("MESA") & """>" End If
  ShowHTML "<TITLE>" & conSgSistema & " - Listagem de atividades</TITLE>"
  ScriptOpen "Javascript"
  CheckBranco
  FormataData
  ValidateOpen "Validacao"
  If Instr("CP",O) > 0 Then
     If P1 <> 1 or O = "C" Then ' Se não for cadastramento ou se for cópia
        Validate "p_chave", "Número do contrato", "", "", "1", "18", "", "0123456789"
        Validate "p_prazo", "Dias para a data limite", "", "", "1", "2", "", "0123456789"
        Validate "p_proponente", "Parcerias externas", "", "", "2", "90", "1", ""
        Validate "p_objeto", "Assunto", "", "", "2", "90", "1", "1"
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
     BodyOpen "onLoad='document.Form.p_projeto.focus()';"
  Else
     BodyOpen "onLoad=document.focus();"
  End If
    Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  If w_filtro > "" Then ShowHTML w_filtro End If
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ShowHTML "<tr><td><font size=""1"">"
    If P1 = 1 and w_copia = "" Then ' Se for cadastramento e não for resultado de busca para cópia
       If w_submenu > "" Then
          DB_GetLinkSubMenu RS1, w_cliente, Request("SG")
          ShowHTML "<tr><td><font size=""1"">"
          ShowHTML "    <a accesskey=""I"" class=""ss"" href=""" & w_dir & w_pagina & "Geral&R=" & w_pagina & par & "&O=I&SG=" & RS1("sigla") & "&w_menu=" & w_menu & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;"
          ShowHTML "    <a accesskey=""C"" class=""ss"" href=""" & w_dir & w_pagina & par & "&R=" & w_pagina & par & "&O=C&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>C</u>opiar</a>"
       Else
          ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""ss"" href=""" & w_dir & w_pagina & par & "&R=" & w_pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;"
       End If
    End If
    If Instr(uCase(R),"GR_") = 0 and Instr(uCase(R),"ACORDO") = 0 Then
       If w_copia > "" Then ' Se for cópia
          If MontaFiltro("GET") > "" Then
             ShowHTML "                         <a accesskey=""F"" class=""ss"" href=""" & w_dir & w_pagina & par & "&R=" & w_pagina & par & "&O=C&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a>"
          Else
             ShowHTML "                         <a accesskey=""F"" class=""ss"" href=""" & w_dir & w_pagina & par & "&R=" & w_pagina & par & "&O=C&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>F</u>iltrar (Inativo)</a>"
          End If
       Else
          If MontaFiltro("GET") > "" Then
             ShowHTML "                         <a accesskey=""F"" class=""ss"" href=""" & w_dir & w_pagina & par & "&R=" & w_pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a>"
          Else
             ShowHTML "                         <a accesskey=""F"" class=""ss"" href=""" & w_dir & w_pagina & par & "&R=" & w_pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>F</u>iltrar (Inativo)</a>"
          End If
       End If
    End If
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td rowspan=2><font size=""1""><b>Código</font></td>"
    ShowHTML "          <td rowspan=2><font size=""1""><b>Outra parte</font></td>"
    If Mid(SG,1,3) = "GCR" Then
       ShowHTML "          <td rowspan=2><font size=""1""><b>Classificação</font></td>"
    Else
       ShowHTML "          <td rowspan=2><font size=""1""><b>Classif./Projeto</font></td>"
    End IF
    ShowHTML "          <td colspan=2><font size=""1""><b>Vigência</font></td>"
    ShowHTML "          <td rowspan=2><font size=""1""><b>Valor</font></td>"
    If P1 <> 1 and P1 <> 2 Then ' Se for cadastramento ou mesa de trabalho
       ShowHTML "          <td rowspan=2><font size=""1""><b>Fase atual</font></td>"
    End If
    ShowHTML "          <td rowspan=2><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Início</font></td>"
    ShowHTML "          <td><font size=""1""><b>Fim</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=10 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      w_parcial       = 0
      While Not RS.EOF and RS.AbsolutePage = P3
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td nowrap><font size=""1"">"
        If Nvl(RS("conclusao"),"nulo") = "nulo" Then
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
        ShowHTML "        <A class=""hl"" HREF=""" & w_dir & w_pagina & "Visual&R=" & w_pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exibe as informações deste registro."">" & RS("codigo_interno") & "&nbsp;</a>"
        If Nvl(RS("outra_parte"),"nulo") <> "nulo" Then
           ShowHTML "        <td><font size=""1"">" & ExibePessoa(w_dir_volta, w_cliente, RS("outra_parte"), TP, RS("nm_outra_parte_resumido")) & "</td>"
        Else
           ShowHTML "        <td align=""center""><font size=""1"">---</td>"
        End If
        If cDbl(Nvl(RS("sq_solic_pai"),0)) = 0 Then
           ShowHTML "        <td><font size=""1"">" & RS("nm_cc") & "</td>"
        Else
           ShowHTML "        <td><font size=""1""><A class=""hl"" HREF=""" & "Projeto.asp?par=Visual&O=L&w_chave=" & RS("sq_solic_pai") & "&w_tipo=Volta&P1=2&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Exibe as informações do projeto."" target=""_blank"">" & RS("nm_projeto") & "</a></td>"
        End If
        ShowHTML "        <td align=""center""><font size=""1"">&nbsp;" & Nvl(FormatDateTime(RS("inicio"),2),"-") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">&nbsp;" & Nvl(FormatDateTime(RS("fim"),2),"-") & "</td>"
        If RS("sg_tramite") = "AT" Then
           ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(RS("custo_real"),2) & "&nbsp;</td>"
           w_parcial = w_parcial + cDbl(RS("custo_real"))
        Else
           ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(RS("valor"),2) & "&nbsp;</td>"
           w_parcial = w_parcial + cDbl(RS("valor"))
        End If
        If P1 <> 1 and P1 <> 2 Then ' Se for cadastramento ou mesa de trabalho
           ShowHTML "        <td nowrap><font size=""1"">" & RS("nm_tramite") & "</td>"
        End If
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        If P1 <> 3 Then ' Se não for acompanhamento
           If w_copia > "" Then ' Se for listagem para cópia
              DB_GetLinkSubMenu RS1, w_cliente, Request("SG")
              ShowHTML "          <a accesskey=""I"" class=""hl"" href=""" & w_dir & w_pagina & "Geral&R=" & w_pagina & par & "&O=I&SG=" & RS1("sigla") & "&w_menu=" & w_menu & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&w_copia=" & RS("sq_siw_solicitacao") & MontaFiltro("GET") & """>Copiar</a>&nbsp;"
           ElseIf P1 = 1 Then ' Se for cadastramento
              If w_submenu > "" Then
                 ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_pagina & "Geral&O=A&w_chave=" & RS("sq_siw_solicitacao") & "&R=" & w_pagina & par & "&SG=" & SG & "&TP=" & TP & "&w_documento=" & RS("codigo_interno") & MontaFiltro("GET") & """ title=""Altera as informações cadastrais do contrato"">Alterar</a>&nbsp;"
              Else
                 ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_pagina & par & "&O=A&w_chave=" & RS("sq_siw_solicitacao") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Altera as informações cadastrais do contrato"">Alterar</A>&nbsp"
              End If
              ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_pagina & "Excluir&R=" & w_pagina & par & "&O=E&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exclusão do contrato."">Excluir</A>&nbsp"
           ElseIf P1 = 2 Then ' Se for execução
              If cDbl(w_usuario) = cDbl(RS("executor")) Then
                 ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_pagina & "Anotacao&R=" & w_pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Registra anotações para o contrato, sem enviá-la."">Anotar</A>&nbsp"
                 ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_pagina & "envio&R=" & w_pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Envia o contrato para outro responsável."">Enviar</A>&nbsp"
                 ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_pagina & "Concluir&R=" & w_pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Conclui a execução do contrato."">Concluir</A>&nbsp"
              Else
                 ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_pagina & "envio&R=" & w_pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Envia o contrato para outro responsável."">Enviar</A>&nbsp"
              End If
           End If
        Else
           If cDbl(Nvl(RS("solicitante"),0)) = cDbl(w_usuario) or _
              cDbl(Nvl(RS("titular"),0))     = cDbl(w_usuario) or _
              cDbl(Nvl(RS("substituto"),0))  = cDbl(w_usuario) or _
              cDbl(Nvl(RS("tit_exec"),0))    = cDbl(w_usuario) or _
              cDbl(Nvl(RS("subst_exec"),0))  = cDbl(w_usuario) _
           Then
              ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_pagina & "envio&R=" & w_pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Envia o contrato para outro responsável."">Enviar</A>&nbsp"
           Else
              ShowHTML "          ---&nbsp"
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
    If O = "C" Then ' Se for cópia
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Para selecionar o contrato que deseja copiar, informe nos campos abaixo os critérios de seleção e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    Else
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    End If
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td valign=""top""><table border=0 width=""90%"" cellspacing=0>"
    AbreForm "Form", w_dir & w_pagina & par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    If O = "C" Then ' Se for cópia, cria parâmetro para facilitar a recuperação dos registros
       ShowHTML "<INPUT type=""hidden"" name=""w_copia"" value=""OK"">"
    End If

    ' Recupera dados da opção Projetos
    ShowHTML "      <tr><td colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "      <tr>"
    DB_GetLinkData RS, w_cliente, "PJCAD"
    SelecaoProjeto "Pr<u>o</u>jeto:", "O", "Selecione o projeto do contrato na relação.", p_projeto, w_usuario, RS("sq_menu"), "p_projeto", w_menu, null
    DesconectaBD
    ShowHTML "      </tr>"
    ShowHTML "          </table>"
    ShowHTML "      <tr><td colspan=""2""><table border=0 width=""100%"" cellspacing=0>"

    If P1 <> 1 or O = "C" Then ' Se não for cadastramento ou se for cópia
       If RS_menu("solicita_cc") = "S" Then
          ShowHTML "      <tr>"
          SelecaoCC "C<u>l</u>assificação:", "L", "Selecione a classificação desejada.", p_sqcc, null, "p_sqcc", "SIWSOLIC"
          ShowHTML "      </tr>"
       End If
       ShowHTML "      <tr valign=""top"">"
       ShowHTML "          <td><font size=""1""><b>Número da <U>d</U>emanda:<br><INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""sti"" type=""text"" name=""p_chave"" size=""18"" maxlength=""18"" value=""" & p_chave & """></td>"
       ShowHTML "          <td><font size=""1""><b>Dias para a data limi<U>t</U>e:<br><INPUT ACCESSKEY=""T"" " & w_Disabled & " class=""sti"" type=""text"" name=""p_prazo"" size=""2"" maxlength=""2"" value=""" & p_prazo & """></td>"
       ShowHTML "      <tr valign=""top"">"
       SelecaoPessoa "Respo<u>n</u>sável:", "N", "Selecione o responsável pelo monitoramento do contrato na relação.", p_solicitante, null, "p_solicitante", "USUARIOS"
       SelecaoUnidade "<U>S</U>etor responsável:", "S", null, p_unidade, null, "p_unidade", null, null
       ShowHTML "      <tr valign=""top"">"
       SelecaoPessoa "Responsável atua<u>l</u>:", "L", "Selecione o responsável atual pelo contrato na relação.", p_usu_resp, null, "p_usu_resp", "USUARIOS"
       SelecaoUnidade "<U>S</U>etor atual:", "S", "Selecione a unidade onde o contrato se encontra na relação.", p_uorg_resp, null, "p_uorg_resp", null, null
       ShowHTML "      <tr>"
       SelecaoPais "<u>P</u>aís:", "P", null, p_pais, null, "p_pais", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_regiao'; document.Form.submit();"""
       SelecaoRegiao "<u>R</u>egião:", "R", null, p_regiao, p_pais, "p_regiao", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_uf'; document.Form.submit();"""
       ShowHTML "      <tr>"
       SelecaoEstado "E<u>s</u>tado:", "S", null, p_uf, p_pais, "N", "p_uf", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_cidade'; document.Form.submit();"""
       SelecaoCidade "<u>C</u>idade:", "C", null, p_cidade, p_pais, p_uf, "p_cidade", null, null
       ShowHTML "      <tr>"
       ShowHTML "          <td><font size=""1""><b>O<U>b</U>jeto:<br><INPUT ACCESSKEY=""B"" " & w_Disabled & " class=""sti"" type=""text"" name=""p_objeto"" size=""25"" maxlength=""90"" value=""" & p_objeto & """></td>"
       ShowHTML "      <tr>"
       ShowHTML "          <td><font size=""1""><b>Iní<u>c</u>io vigência entre:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""p_ini_i"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_ini_i & """ onKeyDown=""FormataData(this,event);""> e <input " & w_Disabled & " accesskey=""C"" type=""text"" name=""p_ini_f"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_ini_f & """ onKeyDown=""FormataData(this,event);""></td>"
       ShowHTML "          <td><font size=""1""><b>Fi<u>m</u> vigência entre:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""p_fim_i"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_fim_i & """ onKeyDown=""FormataData(this,event);""> e <input " & w_Disabled & " accesskey=""T"" type=""text"" name=""p_fim_f"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_fim_f & """ onKeyDown=""FormataData(this,event);""></td>"
       If O <> "C" Then ' Se não for cópia
          ShowHTML "      <tr>"
          ShowHTML "          <td><font size=""1""><b>Exibe contratos vencidos?</b><br>"
          If p_atraso = "S" Then
             ShowHTML "              <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_atraso"" value=""S"" checked> Sim <br><input " & w_Disabled & " class=""str"" class=""str"" type=""radio"" name=""p_atraso"" value=""N""> Não"
          Else
             ShowHTML "              <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_atraso"" value=""S""> Sim <br><input " & w_Disabled & " class=""str"" class=""str"" type=""radio"" name=""p_atraso"" value=""N"" checked> Não"
          End If
          SelecaoFaseCheck "Recuperar fases:", "S", null, p_fase, P2, "p_fase", null, null
       End If
    End If
    ShowHTML "      <tr>"
    ShowHTML "          <td><font size=""1""><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY=""O"" " & w_Disabled & " class=""sts"" name=""p_ordena"" size=""1"">"
    If p_Ordena="ASSUNTO" Then
       ShowHTML "          <option value=""assunto"" SELECTED>Objeto<option value=""inicio"">Início vigência<option value="""">Término vigência<option value=""nm_tramite"">Fase atual<option value=""prioridade"">Outra parte<option value=""proponente"">Projeto"
    ElseIf p_Ordena="INICIO" Then
       ShowHTML "          <option value=""assunto"">Objeto<option value=""inicio"" SELECTED>Início vigência<option value="""">Término vigência<option value=""nm_tramite"">Fase atual<option value=""prioridade"">Outra parte<option value=""proponente"">Projeto"
    ElseIf p_Ordena="FIM" Then
       ShowHTML "          <option value=""assunto"">Objeto<option value=""inicio"">Início vigência<option value="""">Término vigência<option value=""nm_tramite"" SELECTED>Fase atual<option value=""prioridade"">Outra parte<option value=""proponente"">Projeto"
    ElseIf p_Ordena="NM_OUTRA_PARTE" Then
       ShowHTML "          <option value=""assunto"">Objeto<option value=""inicio"">Início vigência<option value="""">Término vigência<option value=""nm_tramite"">Fase atual<option value=""prioridade"" SELECTED>Outra parte<option value=""proponente"">Projeto"
    ElseIf p_Ordena="NM_PROJETO" Then
       ShowHTML "          <option value=""assunto"">Objeto<option value=""inicio"">Início vigência<option value="""">Término vigência<option value=""nm_tramite"">Fase atual<option value=""prioridade"">Outra parte<option value=""proponente"" SELECTED>Projeto"
    Else
       ShowHTML "          <option value=""assunto"">Objeto<option value=""inicio"">Início vigência<option value="""" SELECTED>Término vigência<option value=""nm_tramite"">Fase atual<option value=""prioridade"">Outra parte<option value=""proponente"">Projeto"
    End If
    ShowHTML "          </select></td>"
    ShowHTML "          <td><font size=""1""><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""sti"" type=""text"" name=""P4"" size=""4"" maxlength=""4"" value=""" & P4 & """></td></tr>"
    ShowHTML "          </table>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
    If O = "C" Then ' Se for cópia
       ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"" name=""Botao"" value=""Abandonar cópia"">"
    Else
       ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"" name=""Botao"" value=""Remover filtro"">"
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

  Set w_titulo  = Nothing
  Set w_total   = Nothing
  Set w_parcial = Nothing

End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina dos dados gerais
REM -------------------------------------------------------------------------
Sub Geral
  Dim w_sq_unidade_resp, w_objeto, w_aviso, w_dias, w_sq_tipo_acordo
  Dim w_inicio_real, w_fim_real, w_concluida, w_sq_forma_pagamento
  Dim w_data_conclusao, w_nota_conclusao, w_custo_real, w_sqcc
  Dim w_projeto, w_cd_modalidade, w_sq_tipo_pessoa, w_prazo_indeterm
  Dim w_pessoa_fisica, w_pessoa_juridica, w_forma_atual, w_inicio_atual
  
  Dim w_chave, w_chave_pai, w_chave_aux, w_sq_menu, w_sq_unidade
  Dim w_sq_tramite, w_solicitante, w_cadastrador, w_executor, w_descricao
  Dim w_justificativa, w_inicio, w_fim, w_inclusao, w_ultima_alteracao
  Dim w_conclusao, w_valor, w_opiniao, w_data_hora, w_pais, w_uf, w_cidade, w_palavra_chave
  
  Dim w_troca, i, w_erro, w_como_funciona, w_cor, w_readonly

  w_chave           = Request("w_chave")
  w_sq_tipo_acordo  = Request("w_sq_tipo_acordo")
  w_readonly        = ""
  w_erro            = ""
  w_troca           = Request("w_troca")

  ' Verifica se há necessidade de recarregar os dados da tela a partir
  ' da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  If w_troca > "" Then ' Se for recarga da página
     w_sq_unidade_resp     = Request("w_sq_unidade_resp") 
     w_objeto              = Request("w_objeto") 
     w_aviso               = Request("w_aviso") 
     w_dias                = Request("w_dias") 
     w_inicio_atual        = Request("w_inicio_real") 
     w_inicio_real         = Request("w_inicio_real") 
     w_fim_real            = Request("w_fim_real") 
     w_concluida           = Request("w_concluida") 
     w_data_conclusao      = Request("w_data_conclusao") 
     w_nota_conclusao      = Request("w_nota_conclusao") 
     w_custo_real          = Request("w_custo_real") 
     w_projeto             = Request("w_projeto") 
     w_sq_tipo_acordo      = Request("w_sq_tipo_acordo")
     w_sq_tipo_pessoa      = Request("w_sq_tipo_pessoa")
     w_sq_forma_pagamento  = Request("w_sq_forma_pagamento") 
  
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
        ' Recupera os dados do contrato
        If w_copia > "" Then
           DB_GetSolicData RS, w_copia, Mid(SG,1,3)&"GERAL"
        Else
           DB_GetSolicData RS, w_chave, Mid(SG,1,3)&"GERAL"
        End If
        If RS.RecordCount > 0 Then 
           w_sq_unidade_resp     = RS("sq_unidade")
           w_objeto              = RS("objeto") 
           w_aviso               = RS("aviso_prox_conc") 
           w_dias                = RS("dias_aviso") 
           w_inicio_real         = RS("inicio") 
           w_fim_real            = RS("fim") 
           w_custo_real          = RS("valor") 
           w_projeto             = RS("sq_solic_pai") 
           w_sq_tipo_acordo      = RS("sq_tipo_acordo") 
           w_sq_tipo_pessoa      = RS("sq_tipo_pessoa")
           w_sq_forma_pagamento  = RS("sq_forma_pagamento")
           w_forma_atual         = RS("sq_forma_pagamento")
  
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
           w_inicio_atual        = FormataDataEdicao(RS("inicio"))
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

  If cDbl(Nvl(w_sq_tipo_acordo,0)) > 0 Then
     DB_GetAgreeType RS, w_sq_tipo_acordo, null, w_cliente, Mid(SG,1,3)&"GERAL"
     w_cd_modalidade       = RS("modalidade") 
     w_prazo_indeterm      = RS("prazo_indeterm")
     w_pessoa_fisica       = RS("pessoa_fisica")
     w_pessoa_juridica     = RS("pessoa_juridica")
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente  
  ' Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  ' tratando as particularidades de cada serviço
  ScriptOpen "JavaScript"
  CheckBranco
  FormataData
  FormataDataHora
  FormataValor
  ValidateOpen "Validacao"
  If O = "I" or O = "A" Then
     Validate "w_sq_tipo_acordo", "Modalidade de contratação", "SELECT", 1, 1, 18, "", "0123456789"
     Validate "w_objeto", "Objeto", "1", 1, 5, 2000, "1", "1"
     If w_pessoa_fisica = "S" and w_pessoa_juridica = "S" Then
        Validate "w_sq_tipo_pessoa", "Pessoa a ser contratada", "SELECT", 1, 1, 18, "", "0123456789"
     End If
     If Mid(SG,1,3) = "GCR" Then
        Validate "w_sq_forma_pagamento", "Forma de recebimento", "SELECT", 1, 1, 18, "", "0123456789"
     ElseIf Mid(SG,1,3) = "GCD" Then
        Validate "w_sq_forma_pagamento", "Forma de pagamento", "SELECT", 1, 1, 18, "", "0123456789"
     Else
        Validate "w_sq_forma_pagamento", "Forma de pagamento/recebimento", "SELECT", 1, 1, 18, "", "0123456789"
     End If
     Validate "w_inicio", "Início vigência", "DATA", 1, 10, 10, "", "0123456789/"
     If w_prazo_indeterm = "N" Then
        Validate "w_fim", "Término vigência", "DATA", 1, 10, 10, "", "0123456789/"
        CompData "w_inicio", "Início vigência", "<=", "w_fim", "Término vigência"
     End If
     Validate "w_valor", "Valor", "VALOR", "1", 4, 18, "", "0123456789.,"
     Validate "w_solicitante", "Responsável", "", 1, 1, 18, "", "0123456789"
     Validate "w_sq_unidade_resp", "Setor responsável", "HIDDEN", 1, 1, 18, "", "0123456789"
     If w_cd_modalidade <> "F" Then
        Validate "w_projeto", "Projeto", "SELECT", "", 1, 18, "", "0123456789"
     End If
     If RS_menu("solicita_cc") = "S" Then
        If w_cd_modalidade <> "F" Then
           Validate "w_sqcc", "Classificação", "SELECT", "", 1, 18, "", "0123456789"
        Else
           Validate "w_sqcc", "Classificação", "SELECT", "1", 1, 18, "", "0123456789"
        End If
     End If
     If w_cd_modalidade <> "F" and RS_menu("solicita_cc") = "S" Then
        ShowHTML "  if (theForm.w_projeto.selectedIndex > 0 && theForm.w_sqcc.selectedIndex > 0) {"
        ShowHTML "     alert('Informe um projeto ou uma classificação. Você não pode escolher ambos!');"
        ShowHTML "     theForm.w_projeto.focus();"
        ShowHTML "     return false;"
        ShowHTML "  }"
        ShowHTML "  if (theForm.w_projeto.selectedIndex == 0 && theForm.w_sqcc.selectedIndex == 0) {"
        ShowHTML "     alert('Informe um projeto ou uma classificação!');"
        ShowHTML "     theForm.w_projeto.focus();"
        ShowHTML "     return false;"
        ShowHTML "  }"
     End If
     Validate "w_pais", "País", "SELECT", 1, 1, 18, "", "0123456789"
     Validate "w_uf", "Estado", "SELECT", 1, 1, 3, "1", "1"
     Validate "w_cidade", "Cidade", "SELECT", 1, 1, 18, "", "0123456789"
     If RS_menu("descricao") = "S" Then
        Validate "w_descricao", "Resultados esperados", "1", 1, 5, 2000, "1", "1"
     End If
     If RS_menu("justificativa") = "S" Then
        Validate "w_justificativa", "Observações", "1", "", 5, 2000, "1", "1"
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
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf Instr("EV",O) > 0 Then
     BodyOpen "onLoad='document.focus()';"
  Else
     BodyOpen "onLoad='document.Form.w_sq_tipo_acordo.focus()';"
  End If
    Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
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
    ShowHTML "<INPUT type=""hidden"" name=""w_forma_atual"" value=""" & w_forma_atual &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_inicio_atual"" value=""" & w_inicio_atual &""">"
    
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
    ShowHTML "    <table width=""100%"" border=""0"">"
    ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Identificação</td></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td><font size=1>Os dados deste bloco serão utilizados para identificação do contrato, bem como para o controle de sua execução.</font></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    
    ShowHTML "      <tr>"
    SelecaoTipoAcordo "<u>T</u>ipo de acordo:", "T", "Selecione na lista o tipo de acordo adequado.", w_sq_tipo_acordo, null, w_cliente, "w_sq_tipo_acordo", Mid(SG,1,3)&"GERAL", "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_objeto'; document.Form.submit();"""
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td colspan=2><font size=""1""><b>O<u>b</u>jeto do contrato:</b><br><textarea " & w_Disabled & " accesskey=""O"" name=""w_objeto"" class=""sti"" ROWS=5 cols=75 title=""Descreva o objeto da contratação."">" & w_objeto & "</TEXTAREA></td>"
    ShowHTML "      <tr><td colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "        <tr valign=""top"">"
    If w_pessoa_fisica = "S" and w_pessoa_juridica = "S" Then
       SelecaoTipoPessoa "O<u>u</u>tra parte é pessoa:", "T", "Selecione na lista o tipo de pessoa que será indicada como a outra parte.", w_sq_tipo_pessoa, w_cliente, "w_sq_tipo_pessoa", null, null
    ElseIf w_pessoa_fisica = "S" Then
       DB_GetKindPersonList RS, "Física"
       w_sq_tipo_pessoa = RS("sq_tipo_pessoa")
       ShowHTML "<INPUT type=""hidden"" name=""w_sq_tipo_pessoa"" value=""" & RS("sq_tipo_pessoa") &""">"
    Else
       DB_GetKindPersonList RS, "Jurídica"
       w_sq_tipo_pessoa = RS("sq_tipo_pessoa")
       ShowHTML "<INPUT type=""hidden"" name=""w_sq_tipo_pessoa"" value=""" & RS("sq_tipo_pessoa") &""">"
    End If
    If Mid(SG,1,3) = "GCR" Then
       SelecaoFormaPagamento "<u>F</u>orma de recebimento:", "F", "Selecione na lista a forma de recebimento para este acordo.", w_sq_forma_pagamento, Mid(SG,1,3)&"CAD", "w_sq_forma_pagamento", null
    ElseIf Mid(SG,1,3) = "GCD" Then
       SelecaoFormaPagamento "<u>F</u>orma de pagamento:", "F", "Selecione na lista a forma de pagamento para este acordo.", w_sq_forma_pagamento, Mid(SG,1,3)&"CAD", "w_sq_forma_pagamento", null
    Else
       SelecaoFormaPagamento "<u>F</u>orma de pagamento/recebimento:", "F", "Selecione na lista a forma usual para pagamento/recebimento neste acordo.", w_sq_forma_pagamento, Mid(SG,1,3)&"CAD", "w_sq_forma_pagamento", null
    End If
    ShowHTML "        <tr valign=""top"">"
    ShowHTML "              <td><font size=""1""><b>Iní<u>c</u>io vigência:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_inicio"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & Nvl(w_inicio,FormataDataEdicao(Date())) & """ onKeyDown=""FormataData(this,event);""></td>"
    If w_prazo_indeterm = "N" Then
       ShowHTML "              <td><font size=""1""><b><u>F</u>im vigência:</b><br><input " & w_Disabled & " accesskey=""F"" type=""text"" name=""w_fim"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_fim & """ onKeyDown=""FormataData(this,event);""></td>"
    End If
    ShowHTML "              <td><font size=""1""><b>Valo<u>r</u>:</b><br><input " & w_Disabled & " accesskey=""O"" type=""text"" name=""w_valor"" class=""sti"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_valor & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o valor total real ou estimado para o contrato.""></td>"
    ShowHTML "          </table>"

    ShowHTML "      <tr><td colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    SelecaoPessoa "Respo<u>n</u>sável monitoramento:", "N", "Selecione o responsável pelo monitoramento do contrato.", w_solicitante, null, "w_solicitante", "USUARIOS"
    SelecaoUnidade "<U>S</U>etor responsável monitoramento:", "S", "Selecione o setor responsável pelo monitoramento do contrato", w_sq_unidade_resp, null, "w_sq_unidade_resp", null, null
    ShowHTML "          </table>"
    ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Vinculação</td></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    If Nvl(w_cd_modalidade,"F") <> "F" Then
       ShowHTML "      <tr><td><font size=1>Selecione um projeto ou uma classificação para o contrato. Você deve escolher uma, e apenas uma das duas.</font></td></tr>"
       ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       
       ' Recupera dados da opção Projetos
       ShowHTML "      <tr>"
       DB_GetLinkData RS, w_cliente, "PJCAD"
       DB_GetLinkData RS1, w_cliente, Mid(SG,1,3)&"CAD"
       SelecaoProjeto "Pr<u>o</u>jeto:", "P", "Selecione o projeto ao qual o contrato está vinculado.", w_projeto, w_usuario, RS("sq_menu"), "w_projeto", RS1("sq_menu"), null
       DesconectaBD
       RS1.Close
       ShowHTML "      </tr>"
    Else
       ShowHTML "      <tr><td><font size=1>Classifique o acordo perante uma das opções exibidas na lista.</font></td></tr>"
       ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    End If
    
    If RS_menu("solicita_cc") = "S" Then
       ShowHTML "          <tr>"
       SelecaoCC "C<u>l</u>assificação:", "L", "Selecione um dos itens relacionados.", w_sqcc, null, "w_sqcc", "SIWSOLIC"
       ShowHTML "          </tr>"
    End If

    ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Local do Fornecimento ou Prestação do Serviço</td></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td><font size=1>Selecione país, estado e cidade onde os serviços serão prestados ou onde deverá ocorrer a entrega de produtos. Se mais de uma cidade, selecione a cidade que controlará os serviços ou fornecimentos.</font></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "      <tr>"
    SelecaoPais "<u>P</u>aís:", "P", null, w_pais, null, "w_pais", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_uf'; document.Form.submit();"""
    SelecaoEstado "E<u>s</u>tado:", "S", null, w_uf, w_pais, "N", "w_uf", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_cidade'; document.Form.submit();"""
    SelecaoCidade "<u>C</u>idade:", "C", null, w_cidade, w_pais, w_uf, "w_cidade", null, null
    ShowHTML "          </table>"
    If RS_menu("descricao") = "S" or RS_menu("justificativa") = "S" Then
       ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Informações adicionais</td></td></tr>"
       ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td><font size=1>Os dados deste bloco visam orientar os responsáveis pelo monitoramento do contrato.</font></td></tr>"
       ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       If RS_menu("descricao") = "S" Then
          ShowHTML "      <tr><td><font size=""1""><b>Res<u>u</u>ltados esperados:</b><br><textarea " & w_Disabled & " accesskey=""U"" name=""w_descricao"" class=""sti"" ROWS=5 cols=75 title=""Descreva os resultados esperados com a contratação."">" & w_descricao & "</TEXTAREA></td>"
       End If
       If RS_menu("justificativa") = "S" Then
          ShowHTML "      <tr><td><font size=""1""><b>Obse<u>r</u>vações:</b><br><textarea " & w_Disabled & " accesskey=""R"" name=""w_justificativa"" class=""sti"" ROWS=5 cols=75 >" & w_justificativa & "</TEXTAREA></td>"
       End If
    End If
    ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Alerta de atraso</td></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td><font size=1>Os dados abaixo indicam como deve ser tratada a proximidade do final da vigência.</font></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td><table border=""0"" width=""100%"">"
    ShowHTML "          <tr>"
    MontaRadioNS "<b>Emite alerta?</b>", w_aviso, "w_aviso"
    ShowHTML "              <td><font size=""1""><b>Quantos <U>d</U>ias antes do fim da vigência?<br><INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_dias"" size=""2"" maxlength=""2"" value=""" & w_dias & """ title=""Número de dias para emissão do alerta de proximidade do final da vigência.""></td>"
    ShowHTML "          </table>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000""></TD></TR>"

    ' Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
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
  ShowHTML "</center>"
    Estrutura_Texto_Fecha
    Estrutura_Fecha
  Estrutura_Fecha
  Estrutura_Fecha
  Rodape

  Set w_inicio_atual        = Nothing
  Set w_forma_atual         = Nothing
  Set w_sq_forma_pagamento  = Nothing
  Set w_sq_tipo_pessoa      = Nothing
  Set w_prazo_indeterm      = Nothing
  Set w_pessoa_fisica       = Nothing
  Set w_pessoa_juridica     = Nothing
  Set w_cd_modalidade       = Nothing
  Set w_sq_tipo_acordo      = Nothing
  Set w_projeto             = Nothing 
  Set w_sq_unidade_resp     = Nothing 
  Set w_objeto              = Nothing 
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
REM Rotina de termo de referência
REM -------------------------------------------------------------------------
Sub Termo
  Dim w_codigo_externo, w_atividades, w_produtos, w_requisitos, w_sq_tipo_acordo
  Dim w_vincula_projeto, w_vincula_demanda, w_vincula_viagem, w_cd_modalidade
  
  Dim w_chave, w_sq_menu
  
  Dim w_troca, i, w_erro, w_como_funciona, w_cor, w_readonly

  w_chave           = Request("w_chave")
  w_sq_tipo_acordo  = Request("w_sq_tipo_acordo")
  w_readonly        = ""
  w_erro            = ""
  w_troca           = Request("w_troca")

  ' Verifica se há necessidade de recarregar os dados da tela a partir
  ' da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  If w_troca > "" Then ' Se for recarga da página
     w_codigo_externo      = Request("w_codigo_externo") 
     w_atividades          = Request("w_atividades") 
     w_produtos            = Request("w_produtos") 
     w_requisitos          = Request("w_requisitos") 
     w_vincula_projeto     = Request("w_vincula_projeto") 
     w_vincula_demanda     = Request("w_vincula_demanda") 
     w_vincula_viagem      = Request("w_vincula_viagem") 
  
     w_chave               = Request("w_chave") 
     w_sq_menu             = Request("w_sq_menu") 
  Else
     If InStr("AEV",O) > 0 Then
        DB_GetSolicData RS, w_chave, SG
        If RS.RecordCount > 0 Then 
           w_codigo_externo      = RS("codigo_externo") 
           w_atividades          = RS("atividades") 
           w_produtos            = RS("produtos") 
           w_requisitos          = RS("requisitos") 
           w_vincula_projeto     = RS("vincula_projeto") 
           w_vincula_demanda     = RS("vincula_demanda") 
           w_vincula_viagem      = RS("vincula_viagem") 
           w_sq_tipo_acordo      = RS("sq_tipo_acordo") 
           w_sq_menu             = RS("sq_menu") 
           DesconectaBD
        End If

     End If
     
  End If  

  If cDbl(Nvl(w_sq_tipo_acordo,0)) > 0 Then
    DB_GetAgreeType RS, w_sq_tipo_acordo, null, w_cliente, Mid(SG,1,3)&"GERAL"
    w_cd_modalidade       = RS("modalidade") 
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente
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
     Validate "w_atividades", "Atividades", "1", 1, 5, 2000, "1", "1"
     Validate "w_produtos", "Produtos", "1", 1, 5, 2000, "1", "1"
     Validate "w_requisitos", "Requisitos", "1", 1, 5, 2000, "1", "1"
     Validate "w_codigo_externo", "Código externo", "1", "", 2, 60, "1", "1"
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
     BodyOpen "onLoad='document.Form.w_atividades.focus()';"
  End If
    Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  ShowHTML "<table align=""center"" border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If Instr("IAEV",O) > 0 Then
    If InStr("EV",O) Then
       w_Disabled = " DISABLED "
       If O = "V" Then
          w_Erro = Validacao(w_sq_solicitacao, sg)
       End If
    End If

    AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,w_pagina & par,O
    ShowHTML MontaFiltro("POST")
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_copia"" value=""" & w_copia &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & RS_menu("sq_menu") &""">"
    
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Especificação dos produtos ou serviços</td></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td><font size=1>Os dados deste bloco serão utilizados para especificação dos produtos ou serviços acordados com a outra parte.</font></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    
    ShowHTML "      <tr><td><font size=""1""><b><u>A</u>tividades a serem desenvolvidas:</b><br><textarea " & w_Disabled & " accesskey=""A"" name=""w_atividades"" class=""sti"" ROWS=5 cols=75 title=""Descreva as atividades a serem desenvolvidas para cumprimento dos termos do acordo."">" & w_atividades & "</TEXTAREA></td>"
    ShowHTML "      <tr><td><font size=""1""><b><u>P</u>rodutos a serem entregues:</b><br><textarea " & w_Disabled & " accesskey=""P"" name=""w_produtos"" class=""sti"" ROWS=5 cols=75 title=""Relacione os produtos a serem entregues para cumprimentos dos termos do acordo."">" & w_produtos & "</TEXTAREA></td>"
    ShowHTML "      <tr><td><font size=""1""><b><u>R</u>equisitos para contratação:</b><br><textarea " & w_Disabled & " accesskey=""R"" name=""w_requisitos"" class=""sti"" ROWS=5 cols=75 title=""Relacione os requisitos a serem cumpridos para contratação."">" & w_requisitos & "</TEXTAREA></td>"

    ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Informações adicionais</td></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td><font size=1>Os dados deste bloco permitem a identificação do acordo pela outra parte e configuram as possibilidades de vinculação com outros tipos de documento.</font></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td><font size=""1""><b><u>C</u>ódigo do acordo para a outra parte:</b><br><INPUT ACCESSKEY=""C"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_codigo_externo"" size=""60"" maxlength=""60"" value=""" & w_codigo_externo & """ title=""Informe, se desejar, o código pelo qual este acordo é reconhecido pela outra parte.""></td>"
    If Nvl(w_cd_modalidade,"") = "F" Then
       ShowHTML "          <tr><td><table border=0 width=""100%"" cellspacing=0 cellpadding=0><tr valign=""top"">"
       MontaRadioNS "<b>Pemite vinculação de projetos?</b>", w_vincula_projeto, "w_vincula_projeto"
       MontaRadioNS "<b>Pemite vinculação de demandas?</b>", w_vincula_demanda, "w_vincula_demanda"
       MontaRadioNS "<b>Pemite vinculação de viagem?</b>", w_vincula_viagem, "w_vincula_viagem"
       ShowHTML "      </tr></table>"
    End If
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000""></TD></TR>"

    ' Verifica se poderá ser feito o envio da solicitação, a partir do resultado da validação
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
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
  ShowHTML "</center>"
    Estrutura_Texto_Fecha
    Estrutura_Fecha
  Estrutura_Fecha
  Estrutura_Fecha
  Rodape

  Set w_codigo_externo      = Nothing 
  Set w_atividades          = Nothing 
  Set w_produtos            = Nothing 
  Set w_requisitos          = Nothing 
  Set w_vincula_projeto     = Nothing
  Set w_vincula_demanda     = Nothing
  Set w_vincula_viagem      = Nothing
  Set w_cd_modalidade       = Nothing
  Set w_sq_tipo_acordo      = Nothing 
  
  Set w_chave               = Nothing 
  Set w_sq_menu             = Nothing 
  
  Set w_troca               = Nothing 
  Set i                     = Nothing 
  Set w_erro                = Nothing 
  Set w_como_funciona       = Nothing 
  Set w_cor                 = Nothing 

End Sub
REM =========================================================================
REM Fim da rotina de termo de referência
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de cadastramento da outra parte
REM -------------------------------------------------------------------------
Sub OutraParte

  Dim w_chave, w_chave_aux, w_sq_pessoa, w_nome, w_nome_resumido, w_sq_pessoa_pai
  Dim w_sq_tipo_pessoa, w_nm_tipo_pessoa, w_sq_tipo_vinculo, w_nm_tipo_vinculo, w_interno, w_vinculo_ativo
  Dim w_sq_banco, w_sq_agencia, w_operacao, w_nr_conta
  Dim w_sq_pessoa_telefone, w_ddd, w_nr_telefone, w_email
  Dim w_sq_pessoa_fax, w_nr_fax, w_sq_pessoa_celular, w_nr_celular
  Dim w_sq_pessoa_endereco, w_logradouro, w_complemento, w_bairro, w_cep
  Dim w_sq_cidade, w_co_uf, w_sq_pais, w_pd_pais
  Dim w_cpf, w_nascimento, w_rg_numero, w_rg_emissor, w_rg_emissao, w_passaporte_numero
  Dim w_sq_pais_passaporte, w_sexo
  Dim w_cnpj, w_inscricao_estadual, w_pessoa_atual
  Dim w_sq_pais_estrang, w_aba_code, w_swift_code, w_endereco_estrang, w_banco_estrang
  Dim w_agencia_estrang, w_cidade_estrang, w_informacoes, w_codigo_deposito
  Dim w_forma_pagamento
  
  Dim w_troca, i, w_erro, w_como_funciona, w_cor, w_disabled

  If O = "" Then O = "P" End If

  w_erro            = ""
  w_troca           = Request("w_troca")
  w_chave           = Request("w_chave")
  w_chave_aux       = Request("w_chave_aux")
  w_cpf             = Request("w_cpf")
  w_cnpj            = Request("w_cnpj")
  w_sq_pessoa       = Request("w_sq_pessoa")
  w_pessoa_atual       = Request("w_pessoa_atual")
  
  DB_GetSolicData RS, w_chave, SG
  If w_sq_pessoa = "" and Instr(Request("botao"),"Selecionar") = 0 Then
     w_sq_pessoa        = RS("outra_parte")
     w_pessoa_atual     = RS("outra_parte")
  End If
  w_sq_banco         = RS("sq_banco")
  w_sq_agencia       = RS("sq_agencia")
  w_operacao         = RS("operacao_conta")
  w_nr_conta         = RS("numero_conta")
  w_sq_pais_estrang  = RS("sq_pais_estrang")
  w_aba_code         = RS("aba_code")
  w_swift_code       = RS("swift_code")
  w_endereco_estrang = RS("endereco_estrang")
  w_banco_estrang    = RS("banco_estrang")
  w_agencia_estrang  = RS("agencia_estrang")
  w_cidade_estrang   = RS("cidade_estrang")
  w_informacoes      = RS("informacoes")
  w_codigo_deposito  = RS("codigo_deposito")
  w_forma_pagamento  = RS("sg_forma_pagamento")
  w_sq_tipo_pessoa = RS("sq_tipo_pessoa")
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
     w_sq_pais_estrang      = Request("w_sq_pais_estrang")
     w_aba_code             = Request("w_aba_code")
     w_swift_code           = Request("w_swift_code")
     w_endereco_estrang     = Request("w_endereco_estrang")
     w_banco_estrang        = Request("w_banco_estrang")
     w_agencia_estrang      = Request("w_agencia_estrang")
     w_cidade_estrang       = Request("w_cidade_estrang")
     w_informacoes          = Request("w_informacoes")
     w_codigo_deposito      = Request("w_codigo_deposito")
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
        DB_GetBenef RS, w_cliente, w_sq_pessoa, w_cpf, w_cnpj, null, null, null, null
        If Not RS.EOF Then
           w_sq_pessoa            = RS("sq_pessoa")
           w_nome                 = RS("nm_pessoa")
           w_nome_resumido        = RS("nome_resumido")
           w_sq_pessoa_pai        = RS("sq_pessoa_pai")
           w_nm_tipo_pessoa       = RS("nm_tipo_pessoa")
           w_sq_tipo_vinculo      = RS("sq_tipo_vinculo")
           w_nm_tipo_vinculo      = RS("nm_tipo_vinculo")
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
           If InStr("CREDITO,DEPOSITO",w_forma_pagamento) > 0 Then
              If Nvl(w_nr_conta,"") = "" Then
                 w_sq_banco          = RS("sq_banco")
                 w_sq_agencia        = RS("sq_agencia")
                 w_operacao          = RS("operacao")
                 w_nr_conta          = RS("nr_conta")
              End If
           End If
        End If
        DesconectaBD
     End If
     
  End If  
  Cabecalho
  ShowHTML "<HEAD>"
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
        Validate "w_rg_emissao", "Data de emissão", "1", "", 10, 10, "", "0123456789/"
        Validate "w_rg_emissor", "Órgão expedidor", "1", 1, 2, 30, "1", "1"
        Validate "w_passaporte_numero", "Passaporte", "1", "", 1, 20, "1", "1"
        Validate "w_sq_pais_passaporte", "País emissor", "SELECT", "", 1, 10, "1", "1"
     Else
        Validate "w_inscricao_estadual", "Inscrição estadual", "1", "", 2, 20, "1", "1"
     End If
     Validate "w_ddd", "DDD", "1", "1", 3, 4, "", "0123456789"
     Validate "w_nr_telefone", "Telefone", "1", 1, 7, 25, "1", "1"
     Validate "w_nr_fax", "Fax", "1", "", 7, 25, "1", "1"
     Validate "w_nr_celular", "Celular", "1", "", 7, 25, "1", "1"
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
     If cDbl(w_sq_tipo_pessoa) = 1 Then
        Validate "w_email", "E-Mail", "1", "1", 4, 60, "1", "1"
     Else
        Validate "w_email", "E-Mail", "1", "", 4, 60, "1", "1"
     End If
     If Mid(SG,1,3) <> "GCR" Then
        If Instr("CREDITO,DEPOSITO", w_forma_pagamento) > 0 Then
           If Mid(SG,1,3) = "GCD" Then
              Validate "w_sq_banco", "Banco", "SELECT", 1, 1, 10, "1", "1"
              Validate "w_sq_agencia", "Agencia", "SELECT", 1, 1, 10, "1", "1"
              Validate "w_operacao", "Operação", "1", "", 1, 6, "", "0123456789"
              Validate "w_nr_conta", "Número da conta", "1", "1", 2, 30, "ZXAzxa", "0123456789-"
           ElseIf Mid(SG,1,3) = "GCP" Then
              Validate "w_sq_banco", "Banco", "SELECT", 1, "", 10, "1", "1"
              Validate "w_sq_agencia", "Agencia", "SELECT", 1, "", 10, "1", "1"
              Validate "w_operacao", "Operação", "1", "", 1, 6, "", "0123456789"
              Validate "w_nr_conta", "Número da conta", "1", "", 2, 30, "ZXAzxa", "0123456789-"
              ShowHTML "  if !(theForm.w_sq_banco.selectedIndex == 0 && theForm.w_sq_agencia.selectedIndex == 0 && theForm.w_nr_conta == '') {"
              ShowHTML "     if (theForm.w_sq_banco.selectedIndex == 0 || theForm.w_sq_agencia.selectedIndex == 0 || theForm.w_nr_conta == '') {"
              ShowHTML "        alert('Informe todos os dados bancários ou nenhum deles!');"
              ShowHTML "        document.Form.w_sq_banco.focus();"
              ShowHTML "        return false;"
              ShowHTML "     }"
              ShowHTML "  }"
           End If
        ElseIf w_forma_pagamento = "ORDEM" Then
           Validate "w_sq_banco", "Banco", "SELECT", 1, 1, 10, "1", "1"
           Validate "w_sq_agencia", "Agencia", "SELECT", 1, 1, 10, "1", "1"
        ElseIf w_forma_pagamento = "EXTERIOR" Then
           Validate "w_banco_estrang", "Banco de destino", "1", "1", 1, 60, 1, 1
           Validate "w_aba_code", "Código ABA", "1", "", 1, 12, 1, 1
           Validate "w_swift_code", "Código SWIFT", "1", "", 1, 30, "", 1
           Validate "w_endereco_estrang", "Endereço da agência destino", "1", "", 3, 100, 1, 1
           ShowHTML "  if (theForm.w_aba_code.value == '' && theForm.w_swift_code.value == '' && theForm.w_endereco_estrang.value == '') {"
           ShowHTML "     alert('Informe código ABA, código SWIFT ou endereço da agência!');"
           ShowHTML "     document.Form.w_aba_code.focus();"
           ShowHTML "     return false;"
           ShowHTML "  }"
           Validate "w_agencia_estrang", "Nome da agência destino", "1", "1", 1, 60, 1, 1
           Validate "w_nr_conta", "Número da conta", "1", 1, 1, 10, 1, 1
           Validate "w_cidade_estrang", "Cidade da agência", "1", "1", 1, 60, 1, 1
           Validate "w_sq_pais_estrang", "País da agência", "SELECT", "1", 1, 18, 1, 1
           Validate "w_informacoes", "Informações adicionais", "1", "", 5, 200, 1, 1
        End If
     End If
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
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
       ShowHTML "    <table border=""0"">"
       ShowHTML "        <tr><td colspan=4><font size=2>Informe os dados abaixo e clique no botão ""Selecionar"" para continuar.</TD>"
       If cDbl(w_sq_tipo_pessoa) = 1 Then
          ShowHTML "        <tr><td colspan=4><font size=1><b><u>C</u>PF:<br><INPUT ACCESSKEY=""C"" TYPE=""text"" class=""sti"" NAME=""w_cpf"" VALUE=""" & w_cpf & """ SIZE=""14"" MaxLength=""14"" onKeyDown=""FormataCPF(this, event);"">"
       Else
          ShowHTML "        <tr><td colspan=4><font size=1><b><u>C</u>NPJ:<br><INPUT ACCESSKEY=""C"" TYPE=""text"" class=""sti"" NAME=""w_cnpj"" VALUE=""" & w_cnpj & """ SIZE=""18"" MaxLength=""18"" onKeyDown=""FormataCNPJ(this, event);"">"
       End IF
       ShowHTML "            <INPUT class=""stb"" TYPE=""submit"" NAME=""Botao"" VALUE=""Selecionar"">"
       ShowHTML "        <tr><td colspan=4><p>&nbsp</p>"
       ShowHTML "        <tr><td colspan=4 heigth=1 bgcolor=""#000000"">"
       ShowHTML "        <tr><td colspan=4>"
       ShowHTML "             <font size=1><b><u>P</u>rocurar pelo nome:</b> (Informe qualquer parte do nome SEM ACENTOS)<br><INPUT ACCESSKEY=""P"" TYPE=""text"" class=""sti"" NAME=""w_nome"" VALUE=""" & w_nome & """ SIZE=""20"" MaxLength=""20"">"
       ShowHTML "              <INPUT class=""stb"" TYPE=""submit"" NAME=""Botao"" VALUE=""Procurar"" onClick=""Botao.value=this.value; document.Form.action='" & w_dir & w_Pagina & par &"'"">"
       ShowHTML "      </table>"
       If w_nome > "" Then
          DB_GetBenef RS, w_cliente, null, null, null, w_nome, w_sq_tipo_pessoa, null, null
          ShowHTML "<tr><td colspan=3>"
          ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
          ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
          ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
          ShowHTML "          <td><font size=""1""><b>Nome resumido</font></td>"
          If cDbl(w_sq_tipo_pessoa) = 1 Then
             ShowHTML "          <td><font size=""1""><b>CPF</font></td>"
          Else
             ShowHTML "          <td><font size=""1""><b>CNPJ</font></td>"
          End If
          ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
          ShowHTML "        </tr>"
          If RS.EOF Then
              ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=4 align=""center""><font  size=""1""><b>Não há pessoas que contenham o texto informado.</b></td></tr>"
          Else
            While Not RS.EOF
              ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
              ShowHTML "        <td><font  size=""1"">" & RS("nm_pessoa") & "</td>"
              ShowHTML "        <td><font  size=""1"">" & RS("nome_resumido") & "</td>"
              If cDbl(w_sq_tipo_pessoa) = 1 Then
                 ShowHTML "        <td align=""center""><font  size=""1"">" & Nvl(RS("cpf"),"---") & "</td>"
              Else
                 ShowHTML "        <td align=""center""><font  size=""1"">" & Nvl(RS("cnpj"),"---") & "</td>"
              End If
              ShowHTML "        <td nowrap><font size=""1"">"
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
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
       ShowHTML "    <table width=""97%"" border=""0"">"
       ShowHTML "      <tr><td colspan=""2"" align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Identificação</td></td></tr>"
       ShowHTML "      <tr><td colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td colspan=""2""><table border=""0"" width=""100%"">"
       ShowHTML "          <tr valign=""top"">"
       If cDbl(w_sq_tipo_pessoa) = 1 Then
          ShowHTML "          <td><font size=1>CPF:</font><br><b><font size=2>" & w_cpf
          ShowHTML "              <INPUT type=""hidden"" name=""w_cpf"" value=""" & w_cpf & """>"
       Else
          ShowHTML "          <td><font size=1>CNPJ:</font><br><b><font size=2>" & w_cnpj
          ShowHTML "              <INPUT type=""hidden"" name=""w_cnpj"" value=""" & w_cnpj & """>"
       End IF
       ShowHTML "          <tr valign=""top"">"
       ShowHTML "             <td><font size=""1""><b><u>N</u>ome completo:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""45"" MAXLENGTH=""60"" VALUE=""" & w_nome & """></td>"
       ShowHTML "             <td><font size=""1""><b><u>N</u>ome resumido:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome_resumido"" class=""sti"" SIZE=""15"" MAXLENGTH=""15"" VALUE=""" & w_nome_resumido & """></td>"
       ShowHTML "          </table>"
       If cDbl(w_sq_tipo_pessoa) = 1 Then
          ShowHTML "      <tr><td colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
          ShowHTML "          <tr valign=""top"">"
          SelecaoSexo "Se<u>x</u>o:", "X", null, w_sexo, null, "w_sexo", null, null
          ShowHTML "          <td><font size=""1""><b>Da<u>t</u>a de nascimento:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_nascimento"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_nascimento & """ onKeyDown=""FormataData(this,event);""></td>"
          ShowHTML "          <tr valign=""top"">"
          ShowHTML "          <td><font size=""1""><b><u>I</u>dentidade:</b><br><input " & w_Disabled & " accesskey=""I"" type=""text"" name=""w_rg_numero"" class=""sti"" SIZE=""14"" MAXLENGTH=""80"" VALUE=""" & w_rg_numero & """></td>"
          ShowHTML "          <td><font size=""1""><b>Data de <u>e</u>missão:</b><br><input " & w_Disabled & " accesskey=""E"" type=""text"" name=""w_rg_emissao"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_rg_emissao & """ onKeyDown=""FormataData(this,event);""></td>"
          ShowHTML "          <td><font size=""1""><b>Ór<u>g</u>ão emissor:</b><br><input " & w_Disabled & " accesskey=""G"" type=""text"" name=""w_rg_emissor"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & w_rg_emissor & """></td>"
          ShowHTML "          <tr valign=""top"">"
          ShowHTML "          <td><font size=""1""><b>Passapo<u>r</u>te:</b><br><input " & w_Disabled & " accesskey=""R"" type=""text"" name=""w_passaporte_numero"" class=""sti"" SIZE=""15"" MAXLENGTH=""15"" VALUE=""" & w_passaporte_numero & """></td>"
          SelecaoPais "<u>P</u>aís emissor do passaporte:", "P", null, w_sq_pais_passaporte, null, "w_sq_pais_passaporte", null, null
          ShowHTML "          </table>"
       Else
          ShowHTML "      <tr><td><font size=""1""><b><u>I</u>nscrição estadual:</b><br><input " & w_Disabled & " accesskey=""I"" type=""text"" name=""w_inscricao_estadual"" class=""sti"" SIZE=""20"" MAXLENGTH=""20"" VALUE=""" & w_inscricao_estadual & """></td>"
       End If
       ShowHTML "      <tr><td colspan=""2"" align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       If cDbl(w_sq_tipo_pessoa) = 1 Then
          ShowHTML "      <tr><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Endereço comercial, Telefones e e-Mail</td></td></tr>"
       Else
          ShowHTML "      <tr><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Endereço principal, Telefones e e-Mail</td></td></tr>"
       End If
       ShowHTML "      <tr><td colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
       ShowHTML "          <tr valign=""top"">"
       ShowHTML "          <td><font size=""1""><b><u>D</u>DD:</b><br><input " & w_Disabled & " accesskey=""D"" type=""text"" name=""w_ddd"" class=""sti"" SIZE=""4"" MAXLENGTH=""4"" VALUE=""" & w_ddd & """></td>"
       ShowHTML "          <td><font size=""1""><b>Te<u>l</u>efone:</b><br><input " & w_Disabled & " accesskey=""L"" type=""text"" name=""w_nr_telefone"" class=""sti"" SIZE=""20"" MAXLENGTH=""40"" VALUE=""" & w_nr_telefone & """></td>"
       ShowHTML "          <td title=""Se a outra parte informar um número de fax, informe-o neste campo.""><font size=""1""><b>Fa<u>x</u>:</b><br><input " & w_Disabled & " accesskey=""X"" type=""text"" name=""w_nr_fax"" class=""sti"" SIZE=""20"" MAXLENGTH=""20"" VALUE=""" & w_nr_fax & """></td>"
       ShowHTML "          <td title=""Se a outra parte informar um celular institucional, informe-o neste campo.""><font size=""1""><b>C<u>e</u>lular:</b><br><input " & w_Disabled & " accesskey=""E"" type=""text"" name=""w_nr_celular"" class=""sti"" SIZE=""20"" MAXLENGTH=""20"" VALUE=""" & w_nr_celular & """></td>"
       ShowHTML "          <tr valign=""top"">"
       ShowHTML "          <td colspan=2><font size=""1""><b>En<u>d</u>ereço:</b><br><input " & w_Disabled & " accesskey=""D"" type=""text"" name=""w_logradouro"" class=""sti"" SIZE=""50"" MAXLENGTH=""50"" VALUE=""" & w_logradouro & """></td>"
       ShowHTML "          <td><font size=""1""><b>C<u>o</u>mplemento:</b><br><input " & w_Disabled & " accesskey=""O"" type=""text"" name=""w_complemento"" class=""sti"" SIZE=""20"" MAXLENGTH=""20"" VALUE=""" & w_complemento & """></td>"
       ShowHTML "          <td><font size=""1""><b><u>B</u>airro:</b><br><input " & w_Disabled & " accesskey=""B"" type=""text"" name=""w_bairro"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & w_bairro & """></td>"
       ShowHTML "          <tr valign=""top"">"
       SelecaoPais "<u>P</u>aís:", "P", null, w_sq_pais, null, "w_sq_pais", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_co_uf'; document.Form.submit();"""
       ShowHTML "          <td>"
       SelecaoEstado "E<u>s</u>tado:", "S", null, w_co_uf, w_sq_pais, "N", "w_co_uf", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_sq_cidade'; document.Form.submit();"""
       SelecaoCidade "<u>C</u>idade:", "C", null, w_sq_cidade, w_sq_pais, w_co_uf, "w_sq_cidade", null, null
       ShowHTML "          <tr valign=""top"">"
       If Nvl(w_pd_pais,"S") = "S" then
          ShowHTML "              <td><font size=""1""><b>C<u>E</u>P:</b><br><input " & w_Disabled & " accesskey=""E"" type=""text"" name=""w_cep"" class=""sti"" SIZE=""9"" MAXLENGTH=""9"" VALUE=""" & w_cep & """ onKeyDown=""FormataCEP(this,event);""></td>"
       Else
          ShowHTML "              <td><font size=""1""><b>C<u>E</u>P:</b><br><input " & w_Disabled & " accesskey=""E"" type=""text"" name=""w_cep"" class=""sti"" SIZE=""9"" MAXLENGTH=""9"" VALUE=""" & w_cep & """></td>"
       End IF
       ShowHTML "              <td colspan=3 title=""Se a outra parte informar um e-mail institucional, informe-o neste campo.""><font size=""1""><b>e-<u>M</u>ail:</b><br><input " & w_Disabled & " accesskey=""M"" type=""text"" name=""w_email"" class=""sti"" SIZE=""50"" MAXLENGTH=""60"" VALUE=""" & w_email & """></td>"
       ShowHTML "          </table>"
       If Mid(SG,1,3) <> "GCR" Then ' Se não for acordo de receita
          If Instr("CREDITO,DEPOSITO", w_forma_pagamento) > 0 Then
             ShowHTML "      <tr><td colspan=""2"" align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
             ShowHTML "      <tr><td colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
             ShowHTML "      <tr><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Dados bancários</td></td></tr>"
             ShowHTML "      <tr><td colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
             ShowHTML "      <tr><td colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
             ShowHTML "      <tr valign=""top"">"
             SelecaoBanco "<u>B</u>anco:", "B", "Selecione o banco onde deverão ser feitos os pagamentos referentes ao acordo.", w_sq_banco, null, "w_sq_banco", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_sq_agencia'; document.Form.submit();"""
             SelecaoAgencia "A<u>g</u>ência:", "A", "Selecione a agência onde deverão ser feitos os pagamentos referentes ao acordo.", w_sq_agencia, Nvl(w_sq_banco,-1), "w_sq_agencia", null, null
             ShowHTML "      <tr valign=""top"">"
             ShowHTML "          <td title=""Alguns bancos trabalham com o campo \'Operação\', além do número da conta. A Caixa Econômica Federal é um exemplo. Se for o caso,informe a operação neste campo; caso contrário, deixe-o em branco.""><font size=""1""><b>O<u>p</u>eração:</b><br><input " & w_Disabled & " accesskey=""O"" type=""text"" name=""w_operacao"" class=""sti"" SIZE=""6"" MAXLENGTH=""6"" VALUE=""" & w_operacao & """></td>"
             ShowHTML "          <td title=""Informe o número da conta bancária, colocando o dígito verificador, se existir, separado por um hífen. Exemplo: 11214-3. Se o banco não trabalhar com dígito verificador, informe apenas números. Exemplo: 10845550.""><font size=""1""><b>Número da con<u>t</u>a:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_nr_conta"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & w_nr_conta & """></td>"
             ShowHTML "          </table>"
          ElseIf w_forma_pagamento = "ORDEM" Then
             ShowHTML "      <tr><td colspan=""2"" align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
             ShowHTML "      <tr><td colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
             ShowHTML "      <tr><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Dados para Ordem Bancária</td></td></tr>"
             ShowHTML "      <tr><td colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
             ShowHTML "      <tr><td colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
             ShowHTML "      <tr valign=""top"">"
             SelecaoBanco "<u>B</u>anco:", "B", "Selecione o banco onde deverão ser feitos os pagamentos referentes ao acordo.", w_sq_banco, null, "w_sq_banco", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_sq_agencia'; document.Form.submit();"""
             SelecaoAgencia "A<u>g</u>ência:", "A", "Selecione a agência onde deverão ser feitos os pagamentos referentes ao acordo.", w_sq_agencia, Nvl(w_sq_banco,-1), "w_sq_agencia", null, null
          ElseIf w_forma_pagamento = "EXTERIOR" Then
             ShowHTML "      <tr><td colspan=""2"" align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
             ShowHTML "      <tr><td colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
             ShowHTML "      <tr><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Dados da conta no exterior</td></td></tr>"
             ShowHTML "      <tr><td colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
             ShowHTML "      <tr><td colspan=""2""><font size=1><b><font color=""#BC3131"">ATENÇÃO:</font></b> É obrigatório o preenchimento de um destes campos: Swift Code, ABA Code ou Endereço da Agência.</td></tr>"
             ShowHTML "      <tr><td colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
             ShowHTML "      <tr><td colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
             ShowHTML "      <tr valign=""top"">"
             ShowHTML "          <td title=""Banco onde o crédito deve ser efetuado.""><font size=""1""><b><u>B</u>anco de crédito:</b><br><input " & w_Disabled & " accesskey=""B"" type=""text"" name=""w_banco_estrang"" class=""sti"" SIZE=""40"" MAXLENGTH=""60"" VALUE=""" & w_banco_estrang & """></td>"
             ShowHTML "          <td title=""Código ABA da agência destino.""><font size=""1""><b>A<u>B</u>A code:</b><br><input " & w_Disabled & " accesskey=""B"" type=""text"" name=""w_aba_code"" class=""sti"" SIZE=""12"" MAXLENGTH=""12"" VALUE=""" & w_aba_code & """></td>"
             ShowHTML "          <td title=""Código SWIFT da agência destino.""><font size=""1""><b>S<u>W</u>IFT code:</b><br><input " & w_Disabled & " accesskey=""W"" type=""text"" name=""w_swift_code"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & w_swift_code & """></td>"
             ShowHTML "      <tr><td colspan=3 title=""Endereço da agência.""><font size=""1""><b>E<u>n</u>dereço da agência:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_endereco_estrang"" class=""sti"" SIZE=""80"" MAXLENGTH=""100"" VALUE=""" & w_endereco_estrang & """></td>"
             ShowHTML "      <tr valign=""top"">"
             ShowHTML "          <td colspan=2 title=""Nome da agência destino.""><font size=""1""><b>Nome da a<u>g</u>ência:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_agencia_estrang"" class=""sti"" SIZE=""40"" MAXLENGTH=""60"" VALUE=""" & w_agencia_estrang & """></td>"
             ShowHTML "          <td title=""Número da conta destino.""><font size=""1""><b>Número da con<u>t</u>a:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_nr_conta"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & w_nr_conta & """></td>"
             ShowHTML "      <tr valign=""top"">"
             ShowHTML "          <td colspan=2 title=""Cidade da agência destino.""><font size=""1""><b><u>C</u>idade:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_cidade_estrang"" class=""sti"" SIZE=""40"" MAXLENGTH=""60"" VALUE=""" & w_cidade_estrang & """></td>"
             SelecaoPais "<u>P</u>aís:", "P", "Selecione o país de destino", w_sq_pais_estrang, null, "w_sq_pais_estrang", null, null
             ShowHTML "          </table>"
             ShowHTML "      <tr><td colspan=2 title=""Se necessário, escreva informações adicionais relevantes para o pagamento.""><font size=""1""><b>Info<u>r</u>mações adicionais:</b><br><textarea " & w_Disabled & " accesskey=""R"" name=""w_informacoes"" class=""sti"" ROWS=3 cols=75 >" & w_informacoes & "</TEXTAREA></td>"
          End If
       End If
    
       ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000""></TD></TR>"
       ShowHTML "      <tr><td align=""center"" colspan=""3"">"
       ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Gravar"" onClick=""Botao.value=this.value;"">"
       ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Alterar outra parte"" onClick=""Botao.value=this.value; document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.submit();"">"
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
  Set w_forma_pagamento     = Nothing 
  Set w_sq_banco            = Nothing 
  Set w_sq_agencia          = Nothing 
  Set w_operacao            = Nothing 
  Set w_nr_conta            = Nothing 
  Set w_sq_pais_estrang     = Nothing
  Set w_aba_code            = Nothing
  Set w_swift_code          = Nothing
  Set w_endereco_estrang    = Nothing
  Set w_banco_estrang       = Nothing
  Set w_agencia_estrang     = Nothing
  Set w_cidade_estrang      = Nothing
  Set w_informacoes         = Nothing
  Set w_codigo_deposito     = Nothing
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
REM Fim da tela de outra parte
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
  
  DB_GetSolicData RS, w_chave, SG
  If w_sq_pessoa = "" and Instr(Request("botao"),"Selecionar") = 0 Then
     w_sq_pessoa = RS("preposto")
  End If
  w_sq_tipo_pessoa = RS("sq_tipo_pessoa")
  DesconectaBD
  If cDbl(Nvl(w_sq_pessoa,0)) = 0 Then O = "I" Else O = "A" End If
  
  ' Se acordo com pessoa física, não permite a inclusão dos dados do preposto
  If cDbl(w_sq_tipo_pessoa) = 1 Then
     Cabecalho
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     BodyOpen "onLoad='document.focus()';"
     ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><font size=2 color=""red"">"
     ShowHTML "   Acordos com pessoa física não permitem a indicação do preposto."
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
    Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
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
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
       ShowHTML "    <table border=""0"">"
       ShowHTML "        <tr><td colspan=4><font size=2>Informe os dados abaixo e clique no botão ""Selecionar"" para continuar.</TD>"
       ShowHTML "        <tr><td colspan=4><font size=1><b><u>C</u>PF:<br><INPUT ACCESSKEY=""C"" TYPE=""text"" class=""sti"" NAME=""w_cpf"" VALUE=""" & w_cpf & """ SIZE=""14"" MaxLength=""14"" onKeyDown=""FormataCPF(this, event);"">"
       ShowHTML "            <INPUT class=""stb"" TYPE=""submit"" NAME=""Botao"" VALUE=""Selecionar"">"
       ShowHTML "        <tr><td colspan=4><p>&nbsp</p>"
       ShowHTML "        <tr><td colspan=4 heigth=1 bgcolor=""#000000"">"
       ShowHTML "        <tr><td colspan=4>"
       ShowHTML "             <font size=1><b><u>P</u>rocurar pelo nome:</b> (Informe qualquer parte do nome SEM ACENTOS)<br><INPUT ACCESSKEY=""P"" TYPE=""text"" class=""sti"" NAME=""w_nome"" VALUE=""" & w_nome & """ SIZE=""20"" MaxLength=""20"">"
       ShowHTML "              <INPUT class=""stb"" TYPE=""submit"" NAME=""Botao"" VALUE=""Procurar"" onClick=""Botao.value=this.value; document.Form.action='" & w_dir & w_Pagina & par &"'"">"
       ShowHTML "      </table>"
       If w_nome > "" Then
          DB_GetBenef RS, w_cliente, null, null, null, w_nome, 1, null, null ' Recupera apenas pessoas físicas
          ShowHTML "<tr><td align=""center"" colspan=3>"
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
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
       ShowHTML "    <table width=""97%"" border=""0"">"
       ShowHTML "      <tr><td colspan=""2"" align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Identificação</td></td></tr>"
       ShowHTML "      <tr><td colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td colspan=""2""><table border=""0"" width=""100%"">"
       ShowHTML "          <tr valign=""top"">"
       ShowHTML "          <td><font size=1>CPF:</font><br><b><font size=2>" & w_cpf
       ShowHTML "              <INPUT type=""hidden"" name=""w_cpf"" value=""" & w_cpf & """>"
       ShowHTML "          <tr valign=""top"">"
       ShowHTML "             <td><font size=""1""><b><u>N</u>ome completo:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""40"" MAXLENGTH=""60"" VALUE=""" & w_nome & """></td>"
       ShowHTML "             <td><font size=""1""><b><u>N</u>ome resumido:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome_resumido"" class=""sti"" SIZE=""15"" MAXLENGTH=""15"" VALUE=""" & w_nome_resumido & """></td>"
       SelecaoSexo "Se<u>x</u>o:", "X", null, w_sexo, null, "w_sexo", null, null
       ShowHTML "          <tr valign=""top"">"
       ShowHTML "          <td><font size=""1""><b><u>I</u>dentidade:</b><br><input " & w_Disabled & " accesskey=""I"" type=""text"" name=""w_rg_numero"" class=""sti"" SIZE=""14"" MAXLENGTH=""80"" VALUE=""" & w_rg_numero & """></td>"
       ShowHTML "          <td><font size=""1""><b>Data de <u>e</u>missão:</b><br><input " & w_Disabled & " accesskey=""E"" type=""text"" name=""w_rg_emissao"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_rg_emissao & """ onKeyDown=""FormataData(this,event);""></td>"
       ShowHTML "          <td><font size=""1""><b>Ór<u>g</u>ão emissor:</b><br><input " & w_Disabled & " accesskey=""G"" type=""text"" name=""w_rg_emissor"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & w_rg_emissor & """></td>"
       ShowHTML "          </table>"
    
       ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000""></TD></TR>"
       ShowHTML "      <tr><td align=""center"" colspan=""3"">"
       ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Gravar"" onClick=""Botao.value=this.value;"">"
       ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Alterar outra parte"" onClick=""Botao.value=this.value; document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.submit();"">"
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
  DB_GetSolicData RS, w_chave, SG
  w_sq_tipo_pessoa = RS("sq_tipo_pessoa")
  DesconectaBD

  ' Se acordo com pessoa física, não permite a inclusão dos dados do preposto
  If cDbl(w_sq_tipo_pessoa) = 1 Then
     Cabecalho
     ShowHTML "<HEAD>"
     Estrutura_CSS w_cliente  
     ShowHTML "</HEAD>"
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     BodyOpen "onLoad='document.focus()';"
       Estrutura_Topo_Limpo
     Estrutura_Menu
     Estrutura_Corpo_Abre
     Estrutura_Texto_Abre
     ShowHTML "<table align=""center"" border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
     ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center""><font size=2 color=""red"">"
     ShowHTML "   Acordos com pessoa física não permitem a indicação de representantes."
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
        ' Recupera os representantes do acordo pela outra parte
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
  End If
  ShowHTML "</HEAD>"
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
    Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""ss"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>CPF</font></td>"
    ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
    ShowHTML "          <td><font size=""1""><b>DDD</font></td>"
    ShowHTML "          <td><font size=""1""><b>Telefone</font></td>"
    ShowHTML "          <td><font size=""1""><b>Fax</font></td>"
    ShowHTML "          <td><font size=""1""><b>Celular</font></td>"
    ShowHTML "          <td><font size=""1""><b>e-Mail</font></td>"
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
        ShowHTML "        <td><font size=""1"">" & Nvl(RS("nr_fax"),"---") & "</td>"
        ShowHTML "        <td><font size=""1"">" & Nvl(RS("nr_celular"),"---") & "</td>"
        ShowHTML "        <td><font size=""1"">" & Nvl(RS("email"),"---") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
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
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
       ShowHTML "    <table border=""0"">"
       ShowHTML "        <tr><td colspan=4><font size=2>Informe os dados abaixo e clique no botão ""Selecionar"" para continuar.</TD>"
       ShowHTML "        <tr><td colspan=4><font size=1><b><u>C</u>PF:<br><INPUT ACCESSKEY=""C"" TYPE=""text"" class=""sti"" NAME=""w_cpf"" VALUE=""" & w_cpf & """ SIZE=""14"" MaxLength=""14"" onKeyDown=""FormataCPF(this, event);"">"
       ShowHTML "            <INPUT class=""stb"" TYPE=""submit"" NAME=""Botao"" VALUE=""Selecionar"">"
       ShowHTML "        <tr><td colspan=4><p>&nbsp</p>"
       ShowHTML "        <tr><td colspan=4 heigth=1 bgcolor=""#000000"">"
       ShowHTML "        <tr><td colspan=4>"
       ShowHTML "             <font size=1><b><u>P</u>rocurar pelo nome:</b> (Informe qualquer parte do nome SEM ACENTOS)<br><INPUT ACCESSKEY=""P"" TYPE=""text"" class=""sti"" NAME=""w_nome"" VALUE=""" & w_nome & """ SIZE=""20"" MaxLength=""20"">"
       ShowHTML "              <INPUT class=""stb"" TYPE=""submit"" NAME=""Botao"" VALUE=""Procurar"" onClick=""Botao.value=this.value; document.Form.action='" & w_dir & w_Pagina & par &"'"">"
       ShowHTML "      </table>"
       If w_nome > "" Then
          DB_GetBenef RS, w_cliente, null, null, null, w_nome, 1, null, null ' Recupera apenas pessoas físicas
          RS.Sort = "nm_pessoa"
          ShowHTML "<tr><td align=""center"" colspan=3>"
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
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
       ShowHTML "    <table width=""97%"" border=""0"">"
       ShowHTML "      <tr><td colspan=""2"" align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Identificação</td></td></tr>"
       ShowHTML "      <tr><td colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td colspan=""2""><table border=""0"" width=""100%"">"
       ShowHTML "          <tr valign=""top"">"
       ShowHTML "          <td><font size=1>CPF:</font><br><b><font size=2>" & w_cpf
       ShowHTML "              <INPUT type=""hidden"" name=""w_cpf"" value=""" & w_cpf & """>"
       ShowHTML "          <tr valign=""top"">"
       ShowHTML "             <td><font size=""1""><b><u>N</u>ome completo:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""40"" MAXLENGTH=""60"" VALUE=""" & w_nome & """></td>"
       ShowHTML "             <td><font size=""1""><b><u>N</u>ome resumido:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome_resumido"" class=""sti"" SIZE=""15"" MAXLENGTH=""15"" VALUE=""" & w_nome_resumido & """></td>"
       SelecaoSexo "Se<u>x</u>o:", "X", null, w_sexo, null, "w_sexo", null, null
       ShowHTML "          <tr valign=""top"">"
       ShowHTML "          <td><font size=""1""><b><u>I</u>dentidade:</b><br><input " & w_Disabled & " accesskey=""I"" type=""text"" name=""w_rg_numero"" class=""sti"" SIZE=""14"" MAXLENGTH=""80"" VALUE=""" & w_rg_numero & """></td>"
       ShowHTML "          <td><font size=""1""><b>Data de <u>e</u>missão:</b><br><input " & w_Disabled & " accesskey=""E"" type=""text"" name=""w_rg_emissao"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_rg_emissao & """ onKeyDown=""FormataData(this,event);""></td>"
       ShowHTML "          <td><font size=""1""><b>Ór<u>g</u>ão emissor:</b><br><input " & w_Disabled & " accesskey=""G"" type=""text"" name=""w_rg_emissor"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & w_rg_emissor & """></td>"
       ShowHTML "          </table>"

       ShowHTML "      <tr><td colspan=""2"" align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Telefones e e-Mail</td></td></tr>"
       ShowHTML "      <tr><td colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
       ShowHTML "          <td><font size=""1""><b><u>D</u>DD:</b><br><input " & w_Disabled & " accesskey=""D"" type=""text"" name=""w_ddd"" class=""sti"" SIZE=""4"" MAXLENGTH=""4"" VALUE=""" & w_ddd & """></td>"
       ShowHTML "          <td><font size=""1""><b>Te<u>l</u>efone:</b><br><input " & w_Disabled & " accesskey=""L"" type=""text"" name=""w_nr_telefone"" class=""sti"" SIZE=""20"" MAXLENGTH=""40"" VALUE=""" & w_nr_telefone & """></td>"
       ShowHTML "          <td title=""Se o representante informar um número de fax, informe-o neste campo.""><font size=""1""><b>Fa<u>x</u>:</b><br><input " & w_Disabled & " accesskey=""X"" type=""text"" name=""w_nr_fax"" class=""sti"" SIZE=""20"" MAXLENGTH=""20"" VALUE=""" & w_nr_fax & """></td>"
       ShowHTML "          <td title=""Se o representante informar um celular institucional, informe-o neste campo.""><font size=""1""><b>C<u>e</u>lular:</b><br><input " & w_Disabled & " accesskey=""E"" type=""text"" name=""w_nr_celular"" class=""sti"" SIZE=""20"" MAXLENGTH=""20"" VALUE=""" & w_nr_celular & """></td>"
       ShowHTML "          <tr><td colspan=4><font size=""1""><b>e-<u>M</u>ail:</b><br><input " & w_Disabled & " accesskey=""M"" type=""text"" name=""w_email"" class=""sti"" SIZE=""40"" MAXLENGTH=""50"" VALUE=""" & w_email & """></td>"
       ShowHTML "          </table>"
    
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
REM Rotina de parcelas
REM -------------------------------------------------------------------------
Sub parcelas
  Dim w_chave, w_chave_aux, w_ordem, w_data, w_observacao, w_valor
  Dim w_inicio, w_fim, w_prazo_indeterm, w_valor_inicial, w_total
  
  Dim w_troca, i, w_erro
  
  w_Chave           = Request("w_Chave")
  w_chave_aux       = Request("w_chave_aux")
  w_troca           = Request("w_troca")
  
  If InStr("LIAG",O) > 0 and w_Troca = "" Then
     ' Recupera dados do acordo para as críticas
     DB_GetSolicData RS, w_chave, SG
     w_inicio         = RS("inicio")
     w_fim            = RS("fim")
     w_prazo_indeterm = RS("prazo_indeterm")
     w_valor_inicial  = RS("valor_inicial")
  End If
  
  If w_troca > "" Then ' Se for recarga da página
     w_ordem        = Request("w_ordem")
     w_data         = Request("w_data")
     w_observacao   = Request("w_observacao")    
     w_valor        = Request("w_valor")
  ElseIf O = "L" Then
     ' Recupera todos os registros para a listagem
     DB_GetAcordoParcela RS, w_chave, null, null, null, null, null, null, null, null
     RS.Sort = "ordem"
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
     ' Recupera dados do acordo para as críticas
     DB_GetSolicData RS, w_chave, SG
     w_inicio         = RS("inicio")
     w_fim            = RS("fim")
     w_prazo_indeterm = RS("prazo_indeterm")
     DesconectaBD

     ' Recupera os dados do endereço informado
     DB_GetAcordoParcela RS, w_chave, w_chave_aux, null, null, null, null, null, null, null
     w_ordem          = RS("ordem")
     w_data           = FormataDataEdicao(RS("vencimento"))
     w_observacao     = RS("observacao")    
     w_valor          = FormatNumber(RS("valor"),2)
     DesconectaBD
  End If

  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente  
  If InStr("IAEGCP",O) > 0 Then
     ScriptOpen "JavaScript"
     CheckBranco
     FormataData
     FormataValor
     ShowHTML "function trataUnica() {"
     ShowHTML "  if (document.Form.w_tipo_geracao[0].checked || document.Form.w_tipo_geracao[1].checked) {"
     ShowHTML "     document.Form.w_vencimento[0].checked = false;"
     ShowHTML "     document.Form.w_vencimento[1].checked = false;"
     ShowHTML "     document.Form.w_vencimento[2].checked = false;"
     ShowHTML "     document.Form.w_dia_vencimento.value = '';"
     ShowHTML "     document.Form.w_valor_parcela[0].checked = false;"
     ShowHTML "     document.Form.w_valor_parcela[1].checked = false;"
     ShowHTML "     document.Form.w_valor_parcela[2].checked = false;"
     ShowHTML "     document.Form.w_valor_diferente.value = '';"
     ShowHTML "   }"
     ShowHTML "}"
     ShowHTML "function trataVencimento() {"
     ShowHTML "  if (document.Form.w_tipo_geracao[0].checked || document.Form.w_tipo_geracao[1].checked) {"
     ShowHTML "     document.Form.w_tipo_geracao[0].checked = false;"
     ShowHTML "     document.Form.w_tipo_geracao[1].checked = false;"
     ShowHTML "   }"
     ShowHTML "  if (document.Form.w_vencimento[0].checked || document.Form.w_vencimento[1].checked) {"
     ShowHTML "     document.Form.w_dia_vencimento.value = '';"
     ShowHTML "   }"
     ShowHTML "}"
     ShowHTML "function trataValor() {"
     ShowHTML "  if (document.Form.w_tipo_geracao[0].checked || document.Form.w_tipo_geracao[1].checked) {"
     ShowHTML "     document.Form.w_tipo_geracao[0].checked = false;"
     ShowHTML "     document.Form.w_tipo_geracao[1].checked = false;"
     ShowHTML "   }"
     ShowHTML "  if (document.Form.w_valor_parcela[0].checked) {"
     ShowHTML "     document.Form.w_valor_diferente.value = '';"
     ShowHTML "   }"
     ShowHTML "}"
     ShowHTML "function trataDiaVencimento() {"
     ShowHTML "  if (document.Form.w_tipo_geracao[0].checked || document.Form.w_tipo_geracao[1].checked) {"
     ShowHTML "     document.Form.w_tipo_geracao[0].checked = false;"
     ShowHTML "     document.Form.w_tipo_geracao[1].checked = false;"
     ShowHTML "   }"
     ShowHTML "   document.Form.w_vencimento[2].checked = true;"
     ShowHTML "}"
     ShowHTML "function trataValorDiferente() {"
     ShowHTML "  if (document.Form.w_tipo_geracao[0].checked || document.Form.w_tipo_geracao[1].checked) {"
     ShowHTML "     document.Form.w_tipo_geracao[0].checked = false;"
     ShowHTML "     document.Form.w_tipo_geracao[1].checked = false;"
     ShowHTML "   }"
     ShowHTML "  if (document.Form.w_valor_parcela[0].checked) {"
     ShowHTML "     document.Form.w_valor_parcela[0].checked = false;"
     ShowHTML "   }"
     ShowHTML "}"
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_ordem", "Número de ordem da parcela", "1", "1", "1", "4", "", "0123456789"
        Validate "w_data", "Data de vencimento da parcela", "DATA", "1", "10", "10", "", "0123456789/"
        CompData "w_data", "Data de vencimento", ">=", "w_inicio", "Data de início de vigência do acordo"
        CompData "w_data", "Data de vencimento", "<=", "w_fim", "Data de término de vigência do acordo"
        Validate "w_valor", "Valor da parcela", "VALOR", "1", 4, 18, "", "0123456789.,"
        Validate "w_observacao", "Observação", "1", "", "3", "200", "1", "1"
     ElseIf O = "G" Then
        Validate "w_dia_vencimento", "Dia de vencimento", "1", "", 1,  2, "", "0123456789"
        Validate "w_valor_diferente", "Valor da parcela", "VALOR", "", 4, 18, "", "0123456789.,"
        ShowHTML "  for (i = 0; i < theForm.w_tipo_geracao.length; i++) {"
        ShowHTML "      if (theForm.w_tipo_geracao[i].checked) break;"
        ShowHTML "      if (i == theForm.w_tipo_geracao.length-1) {"
        ShowHTML "         alert('Você deve selecionar uma das opções apresentadas!');"
        ShowHTML "         return false;"
        ShowHTML "      }"
        ShowHTML "  }"
        ShowHTML "  if (theForm.w_tipo_geracao[2].checked || theForm.w_tipo_geracao[3].checked ) {"
        ShowHTML "     for (i = 0; i < theForm.w_vencimento.length; i++) {"
        ShowHTML "         if (theForm.w_vencimento[i].checked) break;"
        ShowHTML "         if (i == theForm.w_vencimento.length-1) {"
        ShowHTML "            alert('Você deve selecionar um dia para vencimento das parcelas!');"
        ShowHTML "            return false;"
        ShowHTML "         }"
        ShowHTML "     }"
        ShowHTML "     for (i = 0; i < theForm.w_valor_parcela.length; i++) {"
        ShowHTML "         if (theForm.w_valor_parcela[i].checked) break;"
        ShowHTML "         if (i == theForm.w_valor_parcela.length-1) {"
        ShowHTML "            alert('Você deve selecionar uma das opções para cálculo do valor das parcelas!');"
        ShowHTML "            return false;"
        ShowHTML "         }"
        ShowHTML "     }"
        ShowHTML "  }"
        ShowHTML "  if (theForm.w_vencimento[2].checked) {"
        ShowHTML "     if (theForm.w_dia_vencimento.value == '') {"
        ShowHTML "        alert('Você deve informar o dia de vencimento das parcelas!');"
        ShowHTML "        theForm.w_dia_vencimento.focus();"
        ShowHTML "        return false;"
        ShowHTML "     }"
        ShowHTML "     if (theForm.w_dia_vencimento.value > 28) {"
        ShowHTML "        alert('Para vencimento após o dia 28, utilize a opção de vencimento no último dia do mês!');"
        ShowHTML "        theForm.w_dia_vencimento.focus();"
        ShowHTML "        return false;"
        ShowHTML "     }"
        ShowHTML "  }"
        ShowHTML "  if (theForm.w_valor_parcela[1].checked || theForm.w_valor_parcela[2].checked) {"
        ShowHTML "     if (theForm.w_valor_diferente.value == '') {"
        ShowHTML "        alert('Você deve informar o valor para a parcela diferente das demais!');"
        ShowHTML "        theForm.w_valor_diferente.focus();"
        ShowHTML "        return false;"
        ShowHTML "     }"
        ShowHTML "  }"
        Validate "w_observacao", "Observação", "1", "", "3", "200", "1", "1"
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
     BodyOpen "onLoad='document.Form.w_ordem.focus()';"
  Else
     BodyOpen "onLoad='document.focus()';"
  End If
    Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""ss"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "                         <a accesskey=""G"" class=""ss"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=G&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>G</u>erar</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><td colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Ordem</font></td>"
    ShowHTML "          <td><font size=""1""><b>Vencimento</font></td>"
    ShowHTML "          <td><font size=""1""><b>Valor</font></td>"
    ShowHTML "          <td><font size=""1""><b>Observação</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      w_total = 0
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">"
        If Nvl(RS("quitacao"),"nulo") = "nulo" Then
           If RS("vencimento") < Date() Then
              ShowHTML "           <img src=""" & conImgAtraso & """ border=0 width=15 heigth=15 align=""center"">"
           ElseIf cDbl(RS("vencimento")-Date()) <= 5 Then
              ShowHTML "           <img src=""" & conImgAviso & """ border=0 width=15 height=15 align=""center"">"
           Else
              ShowHTML "           <img src=""" & conImgNormal & """ border=0 width=15 height=15 align=""center"">"
           End IF
        Else
           If RS("quitacao") < RS("vencimento") Then
              ShowHTML "           <img src=""" & conImgOkAtraso & """ border=0 width=15 heigth=15 align=""center"">"
           Else
              ShowHTML "           <img src=""" & conImgOkNormal & """ border=0 width=15 height=15 align=""center"">"
           End IF
        End If
        ShowHTML "        " & RS("ordem") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & FormataDataEdicao(RS("vencimento")) & "</td>"
        ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(RS("valor"),2) & "&nbsp;&nbsp;</td>"
        ShowHTML "        <td><font size=""1"">" & Nvl(RS("observacao"),"---") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & w_chave & "&w_chave_aux=" & Rs("sq_acordo_parcela") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & "GRAVA&R=" & w_Pagina & par & "&O=E&w_chave=" & w_chave & "&w_chave_aux=" & Rs("sq_acordo_parcela") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ onClick=""return confirm('Confirma a exclusão do registro?');"">Excluir</A>&nbsp"
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        w_total = w_total + cDbl(RS("valor"))
        RS.MoveNext
      wend
    End If
    If w_total > 0 Then
       If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
       ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
       ShowHTML "        <td align=""center"" colspan=2><font size=""1""><b>Total</b></td>"
       ShowHTML "        <td align=""right""><font size=""1""><b>" & FormatNumber(w_total,2) & "</b>&nbsp;&nbsp;</td>"
       If cDbl(w_valor_inicial) <> cDbl(w_total) Then
          ShowHTML "        <td colspan=2><font size=1><b>O valor das parcelas difere do valor contratado (" & FormatNumber(w_valor_inicial,2) & ")</b></td>"
       Else
          ShowHTML "        <td colspan=2>&nbsp;</td>"
       End If
       ShowHTML "      </tr>"
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
    AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_chave_aux & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_inicio"" value=""" & FormataDataEdicao(w_inicio) & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_fim"" value=""" & FormataDataEdicao(w_fim) & """>"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr><td><font size=""1""><b>ATENÇÃO</b>: a data de vencimento deve estar contida dentro da vigência do acordo, de <b>" & FormataDataEdicao(w_inicio) & "</b> e <b>" & FormataDataEdicao(w_fim) & "</b>.<br>&nbsp;</td>"
    ShowHTML "      <tr><td colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Número de <u>o</u>rdem da parcela:</b><br><input " & w_Disabled & " accesskey=""O"" type=""text"" name=""w_ordem"" class=""sti"" SIZE=""4"" MAXLENGTH=""4"" VALUE=""" & w_ordem & """ title=""Informe o número de ordem da parcela, que indica a seqüência de pagamento.""></td>"
    ShowHTML "          <td><font size=""1""><b><u>D</u>ata de vencimento:</b><br><input " & w_Disabled & " accesskey=""D"" type=""text"" name=""w_data"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_data & """ onKeyDown=""FormataData(this,event);"" title=""Informe a data de vencimento da parcela.""></td>"
    ShowHTML "          <td><font size=""1""><b><u>V</u>alor:</b><br><input " & w_Disabled & " accesskey=""V"" type=""text"" name=""w_valor"" class=""sti"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_valor & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o valor da parcela.""></td>"
    ShowHTML "          </table>"
    ShowHTML "      <tr><td colspan=4><font size=""1""><b>Obse<u>r</u>vações:</b><br><textarea " & w_Disabled & " accesskey=""R"" name=""w_observacao"" class=""sti"" ROWS=5 cols=75 >" & w_observacao & "</TEXTAREA></td>"
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
    ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=L';"" name=""Botao"" value=""Cancelar"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  ElseIf Instr("G",O) > 0 Then
    AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_chave_aux & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_inicio"" value=""" & FormataDataEdicao(w_inicio) & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_fim"" value=""" & FormataDataEdicao(w_fim) & """>"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr><td><font size=""2""><b>ATENÇÃO</b>: as parcelas existentes, se existirem, serão excluídas.<br>&nbsp;</td>"
    ShowHTML "      <tr><td><font size=""1""><b>Dados do acordo</b>:<ul>"
    ShowHTML "              <li>Vigência: <b>" & FormataDataEdicao(w_inicio) & "</b> a <b>" & FormataDataEdicao(w_fim) & "</b>"
    ShowHTML "              <li>Valor: <b>" & FormatNumber(w_valor_inicial,2) & "</b>"
    ShowHTML "              </ul>"
    ShowHTML "      <tr><td colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "          <tr valign=""top""><td colspan=2><font size=""1""><b>Dados necessários à geração de parcelas únicas:</b>"
    ShowHTML "          <tr valign=""top""><td><input " & w_Disabled & " type=""radio"" name=""w_tipo_geracao"" value=11 onClick=""trataUnica();""><td><font size=1>Gerar uma única parcela, paga no início do acordo</td>"
    ShowHTML "          <tr valign=""top""><td><input " & w_Disabled & " type=""radio"" name=""w_tipo_geracao"" value=12 onClick=""trataUnica();""><td><font size=1>Gerar uma única parcela, paga no fim do acordo</td>"
    ShowHTML "          <tr valign=""top""><td colspan=2><font size=""1""><b>Dados necessários à geração de parcelas mensais:</b>"
    ShowHTML "          <tr valign=""top""><td><input " & w_Disabled & " type=""radio"" name=""w_tipo_geracao"" value=21 onClick=""trataUnica();""><td><font size=1>Gerar parcelas mensais, a cada trinta dias após o início da vigência</td>"
    ShowHTML "          <tr valign=""top""><td><input " & w_Disabled & " type=""radio"" name=""w_tipo_geracao"" value=22 onClick=""trataUnica();""><td><font size=1>Gerar parcelas mensais, a cada trinta dias a partir do início da vigência</td>"
    ShowHTML "          <tr><td><td><table border=0 cellspacing=0 cellpadding=0>"
    ShowHTML "              <tr valign=""top""><td colspan=3><font size=1><b>Dia de vencimento das parcelas:</td>"
    ShowHTML "              <tr valign=""top""><td><td><input " & w_Disabled & " type=""radio"" name=""w_vencimento"" value=""P"" onClick=""trataVencimento();""><td><font size=1>Sempre no primeiro dia do mês</td>"
    ShowHTML "              <tr valign=""top""><td><td><input " & w_Disabled & " type=""radio"" name=""w_vencimento"" value=""U"" onClick=""trataVencimento();""><td><font size=1>Sempre no último dia do mês</td>"
    ShowHTML "              <tr valign=""top""><td><td><input " & w_Disabled & " type=""radio"" name=""w_vencimento"" value=""D"" onClick=""trataVencimento();""><td><font size=1>Sempre no dia <input " & w_Disabled & " type=""text"" name=""w_dia_vencimento"" class=""sti"" SIZE=""2"" MAXLENGTH=""2"" VALUE="""" onKeyDown=""trataDiaVencimento();"" title=""Informe o dia de vencimento da parcela.""> de cada mês.</td>"
    ShowHTML "              </table>"
    ShowHTML "          <tr><td><td><table border=0 cellspacing=0 cellpadding=0>"
    ShowHTML "              <tr valign=""top""><td colspan=3><font size=1><b>Valores das parcelas:</td>"
    ShowHTML "              <tr valign=""top""><td><td><input " & w_Disabled & " type=""radio"" name=""w_valor_parcela"" value=""I"" onClick=""trataValor();""><td><font size=1>As parcelas têm valores iguais</td>"
    ShowHTML "              <tr valign=""top""><td><td><input " & w_Disabled & " type=""radio"" name=""w_valor_parcela"" value=""P"" onClick=""trataValor();""><td><font size=1>A primeira parcela tem valor diferente das demais</td>"
    ShowHTML "              <tr valign=""top""><td><td><input " & w_Disabled & " type=""radio"" name=""w_valor_parcela"" value=""U"" onClick=""trataValor();""><td><font size=1>A última parcela tem valor diferente das demais</td>"
    ShowHTML "              <tr valign=""top""><td colspan=2><td><font size=1><b>Valor da parcela diferente das demais:</b> <input " & w_Disabled & " type=""text"" name=""w_valor_diferente"" class=""sti"" SIZE=""18"" MAXLENGTH=""18"" onKeyDown=""FormataValor(this, 18, 2, event); trataValorDiferente();"" VALUE="""" title=""Informe o valor da primeira parcela. As demais terão valores iguais.""></td>"
    ShowHTML "              </table>"
    ShowHTML "          </table>"
    ShowHTML "      <tr><td colspan=4><font size=""1""><b>Obse<u>r</u>vações gerais a serem gravadas em todas as parcelas:</b><br><textarea " & w_Disabled & " accesskey=""R"" name=""w_observacao"" class=""sti"" ROWS=5 cols=75 >" & w_observacao & "</TEXTAREA></td>"
    ShowHTML "      <tr><td align=""center""><hr>"
    ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Gerar"">"
    ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=L';"" name=""Botao"" value=""Cancelar"">"
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

  Set w_total           = Nothing 
  Set w_inicio          = Nothing 
  Set w_fim             = Nothing 
  Set w_prazo_indeterm  = Nothing 
  Set w_chave           = Nothing 
  Set w_chave_aux       = Nothing 
  Set w_ordem           = Nothing 
  Set w_observacao      = Nothing 
  
  Set w_troca           = Nothing 
  Set i                 = Nothing 
  Set w_erro            = Nothing
End Sub
REM =========================================================================
REM Fim da tela de parcelas
REM -------------------------------------------------------------------------

REM ------------------------------------------------------------------------- 
REM Rotina de anexos 
REM ------------------------------------------------------------------------- 
Sub Anexo
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
  Estrutura_CSS w_cliente
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
    Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then 
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem 
    ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""ss"" href=""" & w_dir & w_pagina & par & "&R=" & w_pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;" 
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
        ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_pagina & par & "&O=A&w_chave=" & w_chave & "&w_chave_aux=" & Rs("chave_aux") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp" 
        ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_pagina & par & "&O=E&w_chave=" & w_chave & "&w_chave_aux=" & Rs("chave_aux") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp" 
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
    
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>" 
    ShowHTML "    <table width=""100%"" border=""0"">" 
    
    If O = "I" or O = "A" Then 
       DB_GetCustomerData RS, w_cliente 
       ShowHTML "      <tr><td align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""2""><b><font color=""#BC3131"">ATENÇÃO</font>: o tamanho máximo aceito para o arquivo é de " & cDbl(RS("upload_maximo"))/1024 & " KBytes</b>.</font></td>" 
       ShowHTML "<INPUT type=""hidden"" name=""w_upload_maximo"" value=""" & RS("upload_maximo") & """>" 
    End If 
    
    ShowHTML "      <tr><td><font size=""1""><b><u>T</u>ítulo:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""75"" MAXLENGTH=""255"" VALUE=""" & w_nome & """ title=""OBRIGATÓRIO. Informe um título para o arquivo.""></td>" 
    ShowHTML "      <tr><td><font size=""1""><b><u>D</u>escrição:</b><br><textarea " & w_Disabled & " accesskey=""D"" name=""w_descricao"" class=""sti"" ROWS=5 cols=65 title=""OBRIGATÓRIO. Descreva a finalidade do arquivo."">" & w_descricao & "</TEXTAREA></td>" 
    ShowHTML "      <tr><td><font size=""1""><b>A<u>r</u>quivo:</b><br><input " & w_Disabled & " accesskey=""R"" type=""file"" name=""w_caminho"" class=""sti"" SIZE=""80"" MAXLENGTH=""100"" VALUE="""" title=""OBRIGATÓRIO. Clique no botão ao lado para localizar o arquivo. Ele será transferido automaticamente para o servidor."">" 
    If w_caminho > "" Then 
       ShowHTML "              <b>" & LinkArquivo("SS", w_cliente, w_arquivo, "_blank", "Clique para exibir o arquivo atual.", "Exibir", null) & "</b>" 
    End If 
    ShowHTML "      <tr><td align=""center""><hr>" 
    If O = "E" Then 
       ShowHTML "   <input class=""stb"" type=""submit"" name=""Botao"" value=""Excluir"" onClick=""return confirm('Confirma a exclusão do registro?');"">" 
    Else 
       If O = "I" Then 
          ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Incluir"">" 
       Else 
          ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Atualizar"">" 
       End If 
    End If 
    ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_pagina & par & "&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&O=L';"" name=""Botao"" value=""Cancelar"">" 
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
  End if
  
  ShowHTML "<HEAD>"
  If Mid(SG,1,3) = "GCR" Then
     ShowHTML "<TITLE>" & conSgSistema & " - Visualização de Contrato de Receita</TITLE>"
  ElseIf Mid(SG,1,3) = "GCD" Then
     ShowHTML "<TITLE>" & conSgSistema & " - Visualização de Contrato de Despesa</TITLE>"
  Else
     ShowHTML "<TITLE>" & conSgSistema & " - Visualização de Contrato de Parceria</TITLE>"
  End IF
  ShowHTML "</HEAD>"  
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_tipo <> "WORD" Then
     BodyOpenClean "onLoad='document.focus()'; "
  End If
  ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "EMBED") & """><TD ALIGN=""RIGHT""><B><FONT SIZE=4 COLOR=""#000000"">"
  If Mid(SG,1,3) = "GCR" Then
     ShowHTML "Visualização de Contrato de Receita"
  ElseIf Mid(SG,1,3) = "GCD" Then
     ShowHTML "Visualização de Contrato de Despesa"
  Else
     ShowHTML "Visualização de Contrato de Parceria"
  End IF
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

  ' Chama a rotina de visualização dos dados do contrato, na opção "Listagem"
  ShowHTML VisualAcordo(w_chave, "L", w_usuario, P1, P4)

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
  Estrutura_CSS w_cliente  
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
    Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"

  ' Chama a rotina de visualização dos dados do contrato, na opção "Listagem"
  ShowHTML VisualAcordo(w_chave, "V", w_usuario, P1, P4)

  ShowHTML "<HR>"
  AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,Mid(SG,1,3)&"GERAL",R,O
  ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & w_menu & """>"
  DB_GetSolicData RS, w_chave, Mid(SG,1,3)&"GERAL"
  ShowHTML "<INPUT type=""hidden"" name=""w_tramite"" value=""" & RS("sq_siw_tramite") & """>"
  DesconectaBD

  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "  <table width=""97%"" border=""0"">"
  ShowHTML "      <tr><td align=""LEFT"" colspan=4><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
  ShowHTML "    <tr><td align=""center"" colspan=4><hr>"
  ShowHTML "      <input class=""stb"" type=""submit"" name=""Botao"" value=""Excluir"">"
  ShowHTML "      <input class=""stb"" type=""button"" onClick=""history.back(1);"" name=""Botao"" value=""Abandonar"">"
  ShowHTML "      </td>"
  ShowHTML "    </tr>"
  ShowHTML "  </table>"
  ShowHTML "  </TD>"
  ShowHTML "</tr>"
  ShowHTML "</FORM>"
  ShowHTML "</table>"
  ShowHTML "</center>"
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

  Dim w_chave, w_chave_pai, w_chave_aux, w_destinatario, w_despacho
  Dim w_tramite, w_sg_tramite, w_novo_tramite
  
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
     DB_GetSolicData RS, w_chave, Mid(SG,1,3)&"GERAL"
     w_tramite      = RS("sq_siw_tramite")
     w_novo_tramite = RS("sq_siw_tramite")
     DesconectaBD
  End If

  ' Recupera a sigla do trâmite desejado, para verificar a lista de possíveis destinatários.
  DB_GetTramiteData RS, w_novo_tramite
  w_sg_tramite   = RS("sigla")
  DesconectaBD

  ' Se for envio, executa verificações nos dados da solicitação
  If O = "V" Then w_erro = ValidaAcordo(w_cliente, w_chave, Mid(SG,1,3)&"GERAL", null, null, null, w_tramite) End If

  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente  
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
    Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"" colspan=2>"

  ' Chama a rotina de visualização dos dados do projeto, na opção "Listagem"
  ShowHTML VisualAcordo(w_chave, "V", w_usuario, P1, P4)

  ShowHTML "<HR>"
  AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"ORPENVIO",R,O
  ShowHTML MontaFiltro("POST")
  ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & w_menu & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_tramite"" value=""" & w_tramite & """>"

  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"" colspan=2>"
  ShowHTML "  <table width=""97%"" border=""0"">"
  ShowHTML "    <tr><td colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
  If P1 <> 1 Then ' Se não for cadastramento
     If Mid(Nvl(w_erro,"-"),1,1) <> "0" and RetornaGestor(w_chave, w_usuario) = "S" Then
        SelecaoFase "<u>F</u>ase do contrato:", "F", "Se deseja alterar a fase atual do contrato, selecione a fase para a qual deseja enviá-la.", w_novo_tramite, w_menu, "w_novo_tramite", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='w_destinatario'; document.Form.submit();"""
     Else
        SelecaoFase "<u>F</u>ase do contrato:", "F", "Se deseja alterar a fase atual do contrato, selecione a fase para a qual deseja enviá-la.", w_novo_tramite, w_tramite, "w_novo_tramite", "ERRO", "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='w_destinatario'; document.Form.submit();"""
     End If
     ' Se for envio para o cadastramento, exibe apenas as pessoas autorizadas a fazê-lo.
     If w_sg_tramite = "CI" Then
        SelecaoSolicResp "<u>D</u>estinatário:", "D", "Selecione, na relação, um destinatário para o contrato.", w_destinatario, w_chave, w_novo_tramite, w_novo_tramite, "w_destinatario", "CADASTRAMENTO"
     Else
        SelecaoPessoa "<u>D</u>estinatário:", "D", "Selecione, na relação, um destinatário para o contrato.", w_destinatario, null, "w_destinatario", "USUARIOS"
     End If
  Else
     If Mid(Nvl(w_erro,"-"),1,1) <> "0" and RetornaGestor(w_chave, w_usuario) = "S" Then
        SelecaoFase "<u>F</u>ase do contrato:", "F", "Se deseja alterar a fase atual do contrato, selecione a fase para a qual deseja enviá-la.", w_novo_tramite, w_menu, "w_novo_tramite", null, null
     Else
        SelecaoFase "<u>F</u>ase do contrato:", "F", "Se deseja alterar a fase atual do contrato, selecione a fase para a qual deseja enviá-la.", w_novo_tramite, w_tramite, "w_novo_tramite", "ERRO", null
     End If
     SelecaoPessoa "<u>D</u>estinatário:", "D", "Selecione, na relação, um destinatário para o contrato.", w_destinatario, null, "w_destinatario", "USUARIOS"
  End If
  ShowHTML "    <tr><td colspan=2><font size=""1""><b>D<u>e</u>spacho:</b><br><textarea " & w_Disabled & " accesskey=""E"" name=""w_despacho"" class=""sti"" ROWS=5 cols=75 title=""Descreva a ação esperada pelo destinatário na execução do contrato."">" & w_despacho & "</TEXTAREA></td>"
  ShowHTML "      </table>"
  ShowHTML "      <tr><td align=""LEFT"" colspan=4><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
  ShowHTML "    <tr><td align=""center"" colspan=4><hr>"
  ShowHTML "      <input class=""stb"" type=""submit"" name=""Botao"" value=""Enviar"">"
  If P1 <> 1 Then ' Se não for cadastramento
     ' Volta para a listagem
     DB_GetMenuData RS, w_menu
     ShowHTML "      <input class=""stb"" type=""button"" onClick=""location.href='" & replace(RS("link"),w_dir,"") & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & rs("sigla") & MontaFiltro("GET") & "';"" name=""Botao"" value=""Abandonar"">"
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
    Estrutura_Texto_Fecha
    Estrutura_Fecha
  Estrutura_Fecha
  Estrutura_Fecha
  Rodape

  Set w_novo_tramite    = Nothing 
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
REM Fim da rotina de tramitacao
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
  Estrutura_CSS w_cliente  
  ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=../" & MontaURL("MESA") & """>"
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
    Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"

  ' Chama a rotina de visualização dos dados do contrato, na opção "Listagem"
  ShowHTML VisualDemanda(w_chave, "V", w_usuario)

  ShowHTML "<HR>"
  ShowHTML "<FORM action=""" & w_dir & w_pagina & "Grava&SG=ORPENVIO&O="&O&"&w_menu="&w_menu&""" name=""Form"" onSubmit=""return(Validacao(this));"" enctype=""multipart/form-data"" method=""POST"">"
  ShowHTML "<INPUT type=""hidden"" name=""P1"" value=""" & P1 & """>"
  ShowHTML "<INPUT type=""hidden"" name=""P2"" value=""" & P2 & """>"
  ShowHTML "<INPUT type=""hidden"" name=""P3"" value=""" & P3 & """>"
  ShowHTML "<INPUT type=""hidden"" name=""P4"" value=""" & P4 & """>"
  ShowHTML "<INPUT type=""hidden"" name=""TP"" value=""" & TP & """>"
  ShowHTML "<INPUT type=""hidden"" name=""R"" value=""" & R & """>"

  ShowHTML MontaFiltro("POST")
  ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
  DB_GetSolicData RS, w_chave, Mid(SG,1,3)&"GERAL"
  ShowHTML "<INPUT type=""hidden"" name=""w_tramite"" value=""" & RS("sq_siw_tramite") & """>"
  DesconectaBD

  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "  <table width=""97%"" border=""0"">"
  ShowHTML "    <tr><td colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
  DB_GetCustomerData RS, w_cliente  
  ShowHTML "      <tr><td align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""2""><b><font color=""#BC3131"">ATENÇÃO</font>: o tamanho máximo aceito para o arquivo é de " & cDbl(RS("upload_maximo"))/1024 & " KBytes</b>.</font></td>"  
  ShowHTML "<INPUT type=""hidden"" name=""w_upload_maximo"" value=""" & RS("upload_maximo") & """>"  
  ShowHTML "      <tr><td><font size=""1""><b>A<u>n</u>otação:</b><br><textarea " & w_Disabled & " accesskey=""N"" name=""w_observacao"" class=""sti"" ROWS=5 cols=75 title=""Redija a anotação desejada."">" & w_observacao & "</TEXTAREA></td>"  
  ShowHTML "      <tr><td><font size=""1""><b>A<u>r</u>quivo:</b><br><input " & w_Disabled & " accesskey=""R"" type=""file"" name=""w_caminho"" class=""sti"" SIZE=""80"" MAXLENGTH=""100"" VALUE="""" title=""OPCIONAL. Se desejar anexar um arquivo, clique no botão ao lado para localizá-lo. Ele será transferido automaticamente para o servidor."">"  
  ShowHTML "      </table>"
  ShowHTML "      <tr><td align=""LEFT"" colspan=4><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
  ShowHTML "    <tr><td align=""center"" colspan=4><hr>"
  ShowHTML "      <input class=""stb"" type=""submit"" name=""Botao"" value=""Gravar"">"
  ShowHTML "      <input class=""stb"" type=""button"" onClick=""history.back(1);"" name=""Botao"" value=""Abandonar"">"
  ShowHTML "      </td>"
  ShowHTML "    </tr>"
  ShowHTML "  </table>"
  ShowHTML "  </TD>"
  ShowHTML "</tr>"
  ShowHTML "</FORM>"
  ShowHTML "</table>"
  ShowHTML "</center>"
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
  
  Dim w_troca, i, w_erro, w_tramite
  
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
  Response.write Nvl(w_chave,"nulo")
  Response.End()
  If O = "V" Then w_erro = ValidaProjeto(w_cliente, w_chave, "PJGERAL", null, null, null, w_tramite) End If

  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente  
  ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=../" & MontaURL("MESA") & """>"
  If InStr("V",O) > 0 Then
     ScriptOpen "JavaScript"
     CheckBranco
     FormataData
     FormataDataHora
     FormataValor
     ProgressBar w_dir_volta, UploadID         
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
     Validate "w_custo_real", "Rercurso executado", "VALOR", "1", 4, 18, "", "0123456789.,"
     Validate "w_nota_conclusao", "Nota de conclusão", "", "1", "1", "2000", "1", "1"
     Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     If P1 <> 1 Then ' Se não for encaminhamento
        ShowHTML "  theForm.Botao[0].disabled=true;"
        ShowHTML "  theForm.Botao[1].disabled=true;"
     Else
        ShowHTML "  theForm.Botao.disabled=true;"
     End If
     ShowHTML "if (theForm.w_caminho.value != '') {return ProgressBar();}"          
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
    Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"

  ' Chama a rotina de visualização dos dados do contrato, na opção "Listagem"
  ShowHTML VisualDemanda(w_chave, "V", w_usuario)

  ShowHTML "<HR>"
  ShowHTML "<FORM action=""" & w_dir & w_pagina & "Grava&SG=GDCONC&O="&O&"&w_menu="&w_menu&""" name=""Form"" onSubmit=""return(Validacao(this));"" enctype=""multipart/form-data"" method=""POST"">"  
  ShowHTML "<INPUT type=""hidden"" name=""P1"" value=""" & P1 & """>"  
  ShowHTML "<INPUT type=""hidden"" name=""P2"" value=""" & P2 & """>"  
  ShowHTML "<INPUT type=""hidden"" name=""P3"" value=""" & P3 & """>"  
  ShowHTML "<INPUT type=""hidden"" name=""P4"" value=""" & P4 & """>"  
  ShowHTML "<INPUT type=""hidden"" name=""TP"" value=""" & TP & """>"  
  ShowHTML "<INPUT type=""hidden"" name=""R"" value=""" & R & """>"  
    
  ShowHTML MontaFiltro("POST")
  ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_concluida"" value=""S"">"
  DB_GetSolicData RS, w_chave, "ORPGERAL"
  ShowHTML "<INPUT type=""hidden"" name=""w_tramite"" value=""" & RS("sq_siw_tramite") & """>"
  DesconectaBD

  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "  <table width=""97%"" border=""0"">"
  DB_GetCustomerData RS, w_cliente 
  ShowHTML "      <tr><td align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""2""><b><font color=""#BC3131"">ATENÇÃO</font>: o tamanho máximo aceito para o arquivo é de " & cDbl(RS("upload_maximo"))/1024 & " KBytes</b>.</font></td>" 
  ShowHTML "<INPUT type=""hidden"" name=""w_upload_maximo"" value=""" & RS("upload_maximo") & """>" 

  ShowHTML "      <tr><td colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
  ShowHTML "          <tr>"
  ShowHTML "              <td><font size=""1""><b>Iní<u>c</u>io vigência:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_inicio_real"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_inicio_real & """ onKeyDown=""FormataData(this,event);"" title=""Informe a data real de início da vigência.""></td>"
  ShowHTML "              <td><font size=""1""><b><u>T</u>érmino vigência:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_fim_real"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_fim_real & """ onKeyDown=""FormataData(this,event);"" title=""Informe a data real de término da vigência.""></td>"
  ShowHTML "              <td><font size=""1""><b>Valo<u>r</u> real:</b><br><input " & w_Disabled & " accesskey=""O"" type=""text"" name=""w_custo_real"" class=""sti"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_custo_real & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o valor real do contrato.""></td>"
  ShowHTML "          </table>"
  ShowHTML "      <tr><td><font size=""1""><b>Nota d<u>e</u> conclusão:</b><br><textarea " & w_Disabled & " accesskey=""E"" name=""w_nota_conclusao"" class=""sti"" ROWS=5 cols=75 title=""Descreva o quanto a demanda atendeu aos resultados esperados."">" & w_nota_conclusao & "</TEXTAREA></td>"  
  ShowHTML "      <tr><td><font size=""1""><b>A<u>r</u>quivo:</b><br><input " & w_Disabled & " accesskey=""R"" type=""file"" name=""w_caminho"" class=""sti"" SIZE=""80"" MAXLENGTH=""100"" VALUE="""" title=""OPCIONAL. Se desejar anexar um arquivo, clique no botão ao lado para localizá-lo. Ele será transferido automaticamente para o servidor."">"  
  ShowHTML "      <tr><td align=""LEFT"" colspan=4><font size=""1""><b><U>A</U>ssinatura Eletrônica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
  ShowHTML "    <tr><td align=""center"" colspan=4><hr>"
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
  ShowHTML "</center>"
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
REM Rotina de preparação para envio de e-mail relativo a contratos
REM Finalidade: preparar os dados necessários ao envio automático de e-mail
REM Parâmetro: p_solic: número de identificação da solicitação. 
REM            p_tipo:  1 - Inclusão
REM                     2 - Tramitação
REM                     3 - Conclusão
REM -------------------------------------------------------------------------
Sub SolicMail(p_solic, p_tipo)

  Dim w_cab, w_html, w_texto, w_solic, RSM, w_resultado, w_destinatarios
  Dim w_objeto, w_objeto1, l_solic, w_nome
  
  l_solic         = p_solic
  w_destinatarios = ""
  w_resultado     = ""
  
  w_html = "<HTML>" & VbCrLf
  w_html = w_html & BodyOpenMail(null) & VbCrLf
  w_html = w_html & "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">" & VbCrLf
  w_html = w_html & "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">" & VbCrLf
  w_html = w_html & "    <table width=""97%"" border=""0"">" & VbCrLf
  If p_tipo = 1 Then
     w_html = w_html & "      <tr valign=""top""><td align=""center""><font size=2><b>INCLUSÃO DE CONTRATO</b></font><br><br><td></tr>" & VbCrLf
  ElseIf p_tipo = 2 Then
     w_html = w_html & "      <tr valign=""top""><td align=""center""><font size=2><b>TRAMITAÇÃO DE CONTRATO</b></font><br><br><td></tr>" & VbCrLf
  ElseIf p_tipo = 3 Then
     w_html = w_html & "      <tr valign=""top""><td align=""center""><font size=2><b>CONCLUSÃO DE CONTRATO</b></font><br><br><td></tr>" & VbCrLf
  End IF
  w_html = w_html & "      <tr valign=""top""><td><font size=2><b><font color=""#BC3131"">ATENÇÃO</font>: Esta é uma mensagem de envio automático. Não responda esta mensagem.</b></font><br><br><td></tr>" & VbCrLf


  ' Recupera os dados da tarefa
  DB_GetSolicData RSM, p_solic, "GDGERAL"
  
  w_nome = "Contrato " & RSM("sq_siw_solicitacao")

  w_html = w_html & VbCrLf & "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  w_html = w_html & VbCrLf & "    <table width=""99%"" border=""0"">"
  w_html = w_html & VbCrLf & "      <tr><td><font size=""1"">Projeto: <b>" & RSM("nm_projeto") & "</b></td>"
  w_html = w_html & VbCrLf & "      <tr><td><font size=1>Objeto: <b>" & CRLF2BR(RSM("objeto")) & "</b></font></td></tr>"
      
  ' Identificação do contrato
  w_html = w_html & VbCrLf & "      <tr><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>EXTRATO DO CONTRATO</td>"
  ' Se a classificação foi informada, exibe.
  If Not IsNull(RSM("sq_cc")) Then
     w_html = w_html & VbCrLf & "      <tr><td><font size=""1"">Classificação:<br><b>" & RSM("cc_nome") & " </b></td>"
  End If
  w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
  w_html = w_html & VbCrLf & "          <tr valign=""top"">"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Responsável pelo monitoramento:<br><b>" & RSM("nm_sol") & "</b></td>"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Unidade responsável pelo monitoramento:<br><b>" & RSM("nm_unidade_resp") & "</b></td>"
  w_html = w_html & VbCrLf & "          <tr valign=""top"">"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Início vigência:<br><b>" & FormataDataEdicao(RSM("inicio")) & " </b></td>"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Término vigência:<br><b>" & FormataDataEdicao(RSM("fim")) & " </b></td>"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Prioridade:<br><b>" & RetornaPrioridade(RSM("prioridade")) & " </b></td>"
  w_html = w_html & VbCrLf & "          </table>"

  ' Informações adicionais
  If Nvl(RSM("descricao"),"") > "" Then 
     If Nvl(RSM("descricao"),"") > "" Then w_html = w_html & VbCrLf & "      <tr><td><font size=""1"">Resultados do contrato:<br><b>" & CRLF2BR(RSM("descricao")) & " </b></td>" End If
  End If

  w_html = w_html & VbCrLf & "    </table>"
  w_html = w_html & VbCrLf & "</tr>"

  ' Dados da conclusão do contrato, se ela estiver nessa situação
  If Nvl(RSM("conclusao"),"nulo") <> "nulo" and Nvl(RSM("data_conclusao"),"") > "" Then
     w_html = w_html & VbCrLf & "      <tr><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>DADOS DA CONCLUSÃO</td>"
     w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
     w_html = w_html & VbCrLf & "          <tr valign=""top"">"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Início da execução:<br><b>" & FormataDataEdicao(RSM("inicio_real")) & " </b></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Término da execução:<br><b>" & FormataDataEdicao(RSM("fim_real")) & " </b></td>"
     w_html = w_html & VbCrLf & "          </table>"
     w_html = w_html & VbCrLf & "      <tr><td><font size=""1"">Nota de conclusão:<br><b>" & CRLF2BR(RSM("nota_conclusao")) & " </b></td>"
  End If

  If p_tipo = 2 Then ' Se for tramitação
     ' Encaminhamentos
     DB_GetSolicLog RS, p_solic, null, "LISTA"
     RS.Sort = "data desc"
     w_html = w_html & VbCrLf & "      <tr><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>ÚLTIMO ENCAMINHAMENTO</td>"
     w_html = w_html & VbCrLf & "      <tr><td colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
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

  w_html = w_html & VbCrLf & "      <tr><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>OUTRAS INFORMAÇÕES</td>"
  DB_GetCustomerSite RS, Session("p_cliente")
  w_html = w_html & "      <tr valign=""top""><td><font size=2>" & VbCrLf
  w_html = w_html & "         Para acessar o sistema use o endereço: <b><a class=""ss"" href=""" & RS("logradouro") & """ target=""_blank"">" & RS("Logradouro") & "</a></b></li>" & VbCrLf
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
     If p_tipo = 1 Then w_objeto = "Inclusão - " & w_nome Else w_objeto = "Conclusão - " & w_nome End If
  ElseIf p_tipo = 2 Then ' Tramitação
     w_objeto = "Tramitação - " & w_nome
  End If
  DesconectaBD

  If w_destinatarios > "" Then
     ' Executa o envio do e-mail
     w_resultado = EnviaMail(w_objeto, w_html, w_destinatarios)
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
  Set w_objeto                = Nothing
End Sub
REM =========================================================================
REM Fim da rotina da preparação para envio de e-mail
REM -------------------------------------------------------------------------

REM =========================================================================
REM Procedimento que executa as operações de BD
REM -------------------------------------------------------------------------
Public Sub Grava
  Dim p_sq_endereco_unidade
  Dim p_modulo, w_codigo
  Dim w_Null
  Dim w_mensagem
  Dim FS, F1, w_file, w_tamanho, w_tipo, w_nome, field, w_maximo
  Dim w_chave_nova, w_item
  
  w_file    = ""
  w_tamanho = ""
  w_tipo    = ""
  w_nome    = ""
  
  Cabecalho
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  BodyOpen "onLoad=document.focus();"
  
  If Instr(SG, "CAD") > 0 Then
     ' Verifica se a Assinatura Eletrônica é válida
     If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then
          
        DML_PutAcordoGeral O, w_cliente, _
            Request("w_chave"), Request("w_menu"), Request("w_sq_unidade_resp"), Request("w_solicitante"), _
            Session("sq_pessoa"), Request("w_sqcc"), Request("w_descricao"), Request("w_justificativa"), _
            Request("w_inicio"), Request("w_fim"), Request("w_valor"), Request("w_data_hora"), _
            Request("w_aviso"), Request("w_dias"), Request("w_cidade"),  Request("w_projeto"), _
            Request("w_sq_tipo_acordo"), Request("w_objeto"), Request("w_sq_tipo_pessoa"), _
            Request("w_sq_forma_pagamento"), Request("w_forma_atual"), Request("w_inicio_atual"), _
            w_chave_nova, w_copia, w_codigo
          
        ScriptOpen "JavaScript"
        If O = "I" Then
           'Envia e-mail comunicando a inclusão
           'SolicMail Nvl(Request("w_chave"), w_chave_nova) ,1
              
           ' Recupera os dados para montagem correta do menu
           DB_GetMenuData RS1, w_menu
           ShowHTML "  parent.menu.location='../Menu.asp?par=ExibeDocs&O=A&w_chave=" & w_chave_nova & "&w_documento=" & w_codigo & "&R=" & R & "&SG=" & RS1("sigla") & "&TP=" & RemoveTP(TP) & "';"
        End If
        ' Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link
        DB_GetLinkData RS1, Session("p_cliente"), SG
        ShowHTML "  location.href='" & replace(RS1("link"),w_dir,"") & "&P1=" & RS1("P1") & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
        ScriptClose
     Else
        ScriptOpen "JavaScript"
        ShowHTML "  alert('Assinatura Eletrônica inválida!');"
        ShowHTML "  history.back(1);"
        ScriptClose
     End If
  ElseIf Instr(SG, "TERMO") > 0 Then
     ' Verifica se a Assinatura Eletrônica é válida
     If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then
 
        DML_PutAcordoTermo O, Request("w_chave"), _
            Request("w_atividades"), Request("w_produtos"), Request("w_requisitos"), _
            Request("w_codigo_externo"), Request("w_vincula_projeto"), _
            Request("w_vincula_demanda"), Request("w_vincula_viagem")
              
        ScriptOpen "JavaScript"
        ShowHTML "  location.href='" & R & "&O=" & O & "&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
        ScriptClose
     Else
        ScriptOpen "JavaScript"
        ShowHTML "  alert('Assinatura Eletrônica inválida!');"
        ShowHTML "  history.back(1);"
        ScriptClose
     End If
  ElseIf Instr(SG, "PARC") > 0 Then
     ' Verifica se a Assinatura Eletrônica é válida
     If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then
 
        DML_PutAcordoParc O, Request("w_chave"), Request("w_chave_aux"), _
            Request("w_ordem"), Request("w_data"), Request("w_valor"), Request("w_observacao"), _
            Request("w_tipo_geracao"), Request("w_vencimento"), Request("w_dia_vencimento"), _
            Request("w_valor_parcela"), Request("w_valor_diferente")
              
        ScriptOpen "JavaScript"
        ShowHTML "  location.href='" & R & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
        ScriptClose
     Else
        ScriptOpen "JavaScript"
        ShowHTML "  alert('Assinatura Eletrônica inválida!');"
        ShowHTML "  history.back(1);"
        ScriptClose
     End If
  ElseIf Instr(SG, "OUTRA") > 0 Then
     ' Verifica se a Assinatura Eletrônica é válida
     If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then
 
        'ExibeVariaveis
        DML_PutAcordoOutra Request("O"),                    SG,                              Request("w_chave"), _
                           Request("w_chave_aux"),          Request("w_sq_pessoa"),          Request("w_cpf"), _
                           Request("w_cnpj"),               Request("w_nome"),               Request("w_nome_resumido"), _
                           Request("w_sexo"),               Request("w_nascimento"),         Request("w_rg_numero"), _
                           Request("w_rg_emissao"),         Request("w_rg_emissor"),         Request("w_passaporte_numero"), _
                           Request("w_sq_pais_passaporte"), Request("w_inscricao_estadual"), Request("w_logradouro"), _
                           Request("w_complemento"),        Request("w_bairro"),             Request("w_sq_cidade"), _
                           Request("w_cep"),                Request("w_ddd"),                Request("w_nr_telefone"), _
                           Request("w_nr_fax"),             Request("w_nr_celular"),         Request("w_email"), _
                           Request("w_sq_agencia"),         Request("w_operacao"),           Request("w_nr_conta"), _
                           Request("w_sq_pais_estrang"),    Request("w_aba_code"),           Request("w_swift_code"), _
                           Request("w_endereco_estrang"),   Request("w_banco_estrang"),      Request("w_agencia_estrang"), _
                           Request("w_cidade_estrang"),     Request("w_informacoes"),        Request("w_codigo_deposito"), _
                           Request("w_pessoa_atual")
              
        ScriptOpen "JavaScript"
        ShowHTML "  location.href='" & R & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
        ScriptClose
     Else
        ScriptOpen "JavaScript"
        ShowHTML "  alert('Assinatura Eletrônica inválida!');"
        ShowHTML "  history.back(1);"
        ScriptClose
     End If
  ElseIf Instr(SG, "PREP") > 0 Then
     ' Verifica se a Assinatura Eletrônica é válida
     If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then
 
        'ExibeVariaveis
        DML_PutAcordoPreposto Request("O"), SG, Request("w_chave"), Request("w_chave_aux"), _
            Request("w_sq_pessoa"), Request("w_cpf"), Request("w_nome"), _
            Request("w_nome_resumido"), Request("w_sexo"), _
            Request("w_rg_numero"), Request("w_rg_emissao"), Request("w_rg_emissor")
              
        ScriptOpen "JavaScript"
        ShowHTML "  location.href='" & R & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
        ScriptClose
     Else
        ScriptOpen "JavaScript"
        ShowHTML "  alert('Assinatura Eletrônica inválida!');"
        ShowHTML "  history.back(1);"
        ScriptClose
     End If
  ElseIf Instr(SG, "REPRES") > 0 Then
     ' Verifica se a Assinatura Eletrônica é válida
     If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then
 
        'ExibeVariaveis
        DML_PutAcordoRep Request("O"), SG, Request("w_chave"), Request("w_chave_aux"), _
            Request("w_sq_pessoa"), Request("w_cpf"), Request("w_nome"), _
            Request("w_nome_resumido"), Request("w_sexo"), _
            Request("w_rg_numero"), Request("w_rg_emissao"), Request("w_rg_emissor"), _
            Request("w_ddd"), Request("w_nr_telefone"), _
            Request("w_nr_fax"), Request("w_nr_celular"), Request("w_email")
              
        ScriptOpen "JavaScript"
        ShowHTML "  location.href='" & R & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
        ScriptClose
     Else
        ScriptOpen "JavaScript"
        ShowHTML "  alert('Assinatura Eletrônica inválida!');"
        ShowHTML "  history.back(1);"
        ScriptClose
     End If
  ElseIf Instr(SG, "ANEXO") > 0 Then
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
        ShowHTML "  location.href='" & R & "&O=L&w_chave=" & ul.Texts.Item("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';" 
        ScriptClose
     Else
        ScriptOpen "JavaScript"
        ShowHTML "  alert('Assinatura Eletrônica inválida!');"
        ShowHTML "  history.back(1);"
        ScriptClose
     End If
  ElseIf Instr(SG, "ENVIO") > 0 Then
     ' Verifica se a Assinatura Eletrônica é válida 
     If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _ 
        w_assinatura = "" Then 
    
        ' Trata o recebimento de upload ou dados 
        If InStr(uCase(Request.ServerVariables("http_content_type")),"MULTIPART/FORM-DATA") > 0 Then 
           ' Se foi feito o upload de um arquivo 
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
                    w_file    = nvl(ul.Texts.Item("w_atual"),replace(FS.GetTempName(),".tmp",Mid(Field.FileName,Instr(Field.FileName,"."),30)))
                    w_tamanho = Field.Length
                    w_tipo    = Field.ContentType
                    w_nome    = Field.FileName
                    Field.SaveAs conFilePhysical & w_cliente & "\" & w_file
                 End If
              Next                 
              DML_PutDemandaEnvio w_menu, ul.Texts.Item("w_chave"), w_usuario, ul.Texts.Item("w_tramite"), _ 
                 ul.Texts.Item("w_novo_tramite"), "N", ul.Texts.Item("w_observacao"), ul.Texts.Item("w_destinatario"), ul.Texts.Item("w_despacho"), _ 
                 w_file, w_tamanho, w_tipo, w_nome
           Else
              ScriptOpen "JavaScript" 
              ShowHTML "  alert('ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!');" 
              ScriptClose 
           End If
           ScriptOpen "JavaScript" 
           ' Volta para a listagem 
           DB_GetMenuData RS, w_menu 
           ShowHTML "  location.href='" & replace(RS("link"),w_dir,"") & "&O=L&w_chave=" & ul.Texts.Item("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & RemoveTP(TP) & "&SG=" & rs("sigla") & MontaFiltro("UPLOAD") & "';" 
           DesconectaBD 
           ScriptClose 
        Else 
           DML_PutDemandaEnvio Request("w_menu"), Request("w_chave"), w_usuario, Request("w_tramite"), _ 
               Request("w_novo_tramite"), "N", Request("w_observacao"), Request("w_destinatario"), Request("w_despacho"), _ 
               null, null, null , null
              
           ' Envia e-mail comunicando de tramitação
           'SolicMail Request("w_chave"),2
              
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
              DB_GetMenuData RS, Request("w_menu") 
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
  ElseIf Instr(SG, "CONC") > 0 Then
     ' Verifica se a Assinatura Eletrônica é válida
     If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
        w_assinatura = "" Then
 
        DB_GetSolicData RS, ul.Texts.Item("w_chave"), "GDGERAL" 
        If cDbl(RS("sq_siw_tramite")) <> cDbl(ul.Texts.Item("w_tramite")) Then
           ScriptOpen "JavaScript"
           ShowHTML "  alert('ATENÇÃO: Outro usuário já encaminhou este contrato para outra fase!');"
           ScriptClose
        Else
           ' Se foi feito o upload de um arquivo  
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
                      w_file    = nvl(ul.Texts.Item("w_atual"),replace(FS.GetTempName(),".tmp",Mid(Field.FileName,Instr(Field.FileName,"."),30)))
                      w_tamanho = Field.Length
                      w_tipo    = Field.ContentType
                      w_nome    = Field.FileName
                      Field.SaveAs conFilePhysical & w_cliente & "\" & w_file
                   End If
                Next                 
             Else
                ScriptOpen "JavaScript" 
                ShowHTML "  alert('ATENÇÃO: ocorreu um erro na transferência do arquivo. Tente novamente!');" 
                ScriptClose 
             End If     
           
           DML_PutDemandaConc w_menu, ul.Texts.Item("w_chave"), w_usuario, ul.Texts.Item("w_tramite"), ul.Texts.Item("w_inicio_real"), ul.Texts.Item("w_fim_real"), ul.Texts.Item("w_nota_conclusao"), ul.Texts.Item("w_custo_real"), _  
               w_file, w_tamanho, w_tipo, w_nome
              
           ' Envia e-mail comunicando a conclusão
           'SolicMail ul.Texts.Item("w_chave") ,3
             
           ScriptOpen "JavaScript"
           ' Volta para a listagem
           DB_GetMenuData RS, w_menu
           ShowHTML "  location.href='" & replace(RS("link"),w_dir,"") & "&O=L&w_chave=" & ul.Texts.Item("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & rs("sigla") & MontaFiltro("UPLOAD") & "';" 
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

  Set w_chave_nova          = Nothing
  Set w_file                = Nothing
  Set FS                    = Nothing
  Set w_Mensagem            = Nothing
  Set p_sq_endereco_unidade = Nothing
  Set p_modulo              = Nothing
  Set w_Null                = Nothing
  Set w_codigo              = Nothing
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
    Case "INICIAL"       Inicial
    Case "GERAL"         Geral
    Case "TERMO"         Termo
    Case "OUTRAPARTE"    OutraParte
    Case "PREPOSTO"      Preposto
    Case "REPRESENTANTE" Representante
    Case "ANEXO"         Anexo
    Case "PARCELAS"      Parcelas
    Case "AREAS"         Areas
    Case "VISUAL"        Visual
    Case "EXCLUIR"       Excluir
    Case "ENVIO"         Encaminhamento
    Case "TRAMITE"       Tramitacao
    Case "ANOTACAO"      Anotar
    Case "CONCLUIR"      Concluir
    Case "GRAVA"         Grava
    Case Else
       Cabecalho
       ShowHTML "<BASE HREF=""" & conRootSIW & """>"
       BodyOpen "onLoad=document.focus();"
         Estrutura_Topo_Limpo
       Estrutura_Menu
       Estrutura_Corpo_Abre
       Estrutura_Texto_Abre
       ShowHTML "<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src=""images/icone/underc.gif"" align=""center""> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>"
       Rodape
  End Select
End Sub
REM =========================================================================
REM Fim da rotina principal
REM -------------------------------------------------------------------------
%>