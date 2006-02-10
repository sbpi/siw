<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Geral.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Gerencial.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Cliente.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Seguranca.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Link.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_EO.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DML_Solic.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DML_Demanda.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/mod_pd/Funcoes.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/mod_pd/DB_Tabelas.asp" -->
<!-- #INCLUDE FILE="DB_Geral.asp" -->
<!-- #INCLUDE FILE="DB_Viagem.asp" -->
<!-- #INCLUDE FILE="DML_Viagem.asp" -->
<!-- #INCLUDE FILE="ValidaViagem.asp" -->
<!-- #INCLUDE FILE="VisualViagem.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Tabelas.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /viagem.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Gerencia o seviço de viagens
REM Mail     : celso@sbpi.com.br
REM Criacao  : 05/10/2005, 11:19
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
Dim w_Assinatura, w_ano, w_cadgeral
Dim p_ativo, p_solicitante, p_prioridade, p_unidade, p_proponente, p_ordena
Dim p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_projeto, p_atividade
Dim p_chave, p_assunto, p_pais, p_uf, p_cidade, p_regiao, p_usu_resp, p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc
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
w_pagina     = "viagem.asp?par="
w_Dir        = "mod_is/"
w_dir_volta  = "../"  
w_Disabled   = "ENABLED"

SG           = ucase(Request("SG"))
O            = ucase(Request("O"))
w_cliente    = RetornaCliente()
w_usuario    = RetornaUsuario()
w_menu       = RetornaMenu(w_cliente, SG)
w_ano        = RetornaAno()
w_cadgeral   = RetornaCadastrador_PD(w_menu, w_usuario)

If InStr(uCase(Request.ServerVariables("http_content_type")),"MULTIPART/FORM-DATA") > 0 Then  
   ' Cria o objeto de upload  
   Set ul       = Nothing  
   Set ul       = Server.CreateObject("Dundas.Upload.2")  
   ul.SaveToMemory  
    
   w_troca          = ul.Form("w_troca")  
   w_copia          = ul.Form("w_copia")  
   p_projeto        = uCase(ul.Form("p_projeto"))  
   p_atividade      = uCase(ul.Form("p_atividade"))  
   p_ativo          = uCase(ul.Form("p_ativo"))  
   p_solicitante    = uCase(ul.Form("p_solicitante"))  
   p_prioridade     = uCase(ul.Form("p_prioridade"))  
   p_unidade        = uCase(ul.Form("p_unidade"))  
   p_proponente     = uCase(ul.Form("p_proponente"))  
   p_ordena         = uCase(ul.Form("p_ordena"))  
   p_ini_i          = uCase(ul.Form("p_ini_i"))  
   p_ini_f          = uCase(ul.Form("p_ini_f"))  
   p_fim_i          = uCase(ul.Form("p_fim_i"))  
   p_fim_f          = uCase(ul.Form("p_fim_f"))  
   p_atraso         = uCase(ul.Form("p_atraso"))  
   p_chave          = uCase(ul.Form("p_chave"))  
   p_assunto        = uCase(ul.Form("p_assunto"))  
   p_pais           = uCase(ul.Form("p_pais"))  
   p_regiao         = uCase(ul.Form("p_regiao"))  
   p_uf             = uCase(ul.Form("p_uf"))  
   p_cidade         = uCase(ul.Form("p_cidade"))  
   p_usu_resp       = uCase(ul.Form("p_usu_resp"))  
   p_uorg_resp      = uCase(ul.Form("p_uorg_resp"))  
   p_palavra        = uCase(ul.Form("p_palavra"))  
   p_prazo          = uCase(ul.Form("p_prazo"))  
   p_fase           = uCase(ul.Form("p_fase"))  
   p_sqcc           = uCase(ul.Form("p_sqcc"))  
    
   P1               = ul.Form("P1")  
   P2               = ul.Form("P2")  
   P3               = ul.Form("P3")  
   P4               = ul.Form("P4")  
   TP               = ul.Form("TP")  
   R                = uCase(ul.Form("R"))  
   w_Assinatura     = uCase(ul.Form("w_Assinatura"))  
Else  
   w_troca          = Request("w_troca")  
   w_copia          = Request("w_copia")  
   p_projeto        = uCase(Request("p_projeto"))
   p_atividade      = uCase(Request("p_atividade"))
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
   p_assunto        = uCase(Request("p_assunto"))  
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
    
   If InStr("PDTRECHO,PDVINC",SG) > 0 Then
      If O <> "I" and Request("w_chave_aux") = "" Then O = "L" End If
   ElseIf SG = "PDENVIO" Then 
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

Set w_cadgeral    = Nothing
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

  Dim w_tarefa, w_total, w_parcial
  
  If O = "L" Then
     If Instr(uCase(R),"GR_") > 0 or Instr(uCase(R),"PROJETO") > 0 Then
        w_filtro = ""
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
        If p_assunto     > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Descrição <td><font size=1>[<b>" & p_assunto & "</b>]"                            End If
        If p_palavra     > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Responsável <td><font size=1>[<b>" & p_palavra & "</b>]"                     End If
        If p_ini_i       > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Data recebimento <td><font size=1>[<b>" & p_ini_i & "-" & p_ini_f & "</b>]"     End If
        If p_fim_i       > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Limite conclusão <td><font size=1>[<b>" & p_fim_i & "-" & p_fim_f & "</b>]"     End If
        If p_atraso      = "S" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Situação <td><font size=1>[<b>Apenas atrasadas</b>]"                            End If
        If w_filtro > "" Then w_filtro = "<table border=0><tr valign=""top""><td><font size=1><b>Filtro:</b><td nowrap><font size=1><ul>" & w_filtro & "</ul></tr></table>"                    End If
     End If

     DB_GetLinkData RS, w_cliente, SG
     If w_copia > "" Then ' Se for cópia, aplica o filtro sobre todas as PCDs visíveis pelo usuário
        DB_GetSolicList rs, RS("sq_menu"), w_usuario, SG, 3, _
           p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
           p_unidade, p_prioridade, p_ativo, p_proponente, _
           p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
           p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, null, null, null, null
     Else
        DB_GetSolicList rs, RS("sq_menu"), w_usuario, SG, P1, _
           p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
           p_unidade, p_prioridade, p_ativo, p_proponente, _
           p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
           p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, null, null, null, null
     End If

     If p_ordena > "" Then RS.sort = p_ordena Else RS.sort = "ordem, fim, prioridade" End If
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=../" & MontaURL("MESA") & """>" End If
  ShowHTML "<TITLE>" & conSgSistema & " - Listagem de Viagens</TITLE>"
  ScriptOpen "Javascript"
  CheckBranco
  FormataData
  ValidateOpen "Validacao"
  If Instr("CP",O) > 0 Then
     If P1 <> 1 or O = "C" Then ' Se não for cadastramento ou se for cópia
        Validate "p_chave", "Número da PCD", "", "", "1", "18", "", "0123456789"
        Validate "p_prazo", "Dias para a data limite", "", "", "1", "2", "", "0123456789"
        Validate "p_proponente", "Parcerias externas", "", "", "2", "90", "1", ""
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
  ElseIf InStr("CP",O) > 0 Then
     BodyOpen "onLoad='document.Form.p_projeto.focus()';"
  Else
     BodyOpen "onLoad=document.focus();"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  If w_filtro > "" Then ShowHTML w_filtro End If
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ShowHTML "<tr><td><font size=""1"">"
    If P1 = 1 and w_copia = "" Then ' Se for cadastramento e não for resultado de busca para cópia
       If w_submenu > "" Then
          DB_GetLinkSubMenu RS1, w_cliente, Request("SG")
          ShowHTML "<tr><td><font size=""1"">"
          ShowHTML "    <a accesskey=""I"" class=""SS"" href=""" & w_dir & w_pagina & "Geral&R=" & w_pagina & par & "&O=I&SG=" & RS1("sigla") & "&w_menu=" & w_menu & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;"
          ShowHTML "    <a accesskey=""C"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_pagina & par & "&O=C&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>C</u>opiar</a>"
       Else
          ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;"
       End If
    End If
    If Instr(uCase(R),"GR_") = 0 and Instr(uCase(R),"PROJETO") = 0 Then
       If w_copia > "" Then ' Se for cópia
          If MontaFiltro("GET") > "" Then
             ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_pagina & par & "&O=C&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a>"
          Else
             ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_pagina & par & "&O=C&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>F</u>iltrar (Inativo)</a>"
          End If
       Else
          If MontaFiltro("GET") > "" Then
             ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a>"
          Else
             ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>F</u>iltrar (Inativo)</a>"
          End If
       End If
    End If
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Nº","sq_siw_solicitacao") & "</font></td>"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Proposto","nm_prop") & "</font></td>"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Unidade","sg_unidade_resp") & "</font></td>"
    If P1 > 2 Then ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Usuário atual", "nm_exec") & "</font></td>" End If
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Início","inicio") & "</font></td>"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Fim","fim") & "</font></td>"
    If P1 > 1 Then 
       ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Valor","valor") & "</font></td>"
       ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Fase atual","nm_tramite") & "</font></td>"
    End If
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
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
        ShowHTML "        <A class=""HL"" HREF=""" & w_dir & w_pagina & "Visual&R=" & w_pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exibe as informações deste registro."">" & RS("codigo_interno") & "&nbsp;</a>"
        ShowHTML "        <td><font size=""1"">" & ExibePessoa("../", w_cliente, RS("sq_prop"), TP, RS("nm_prop")) & "</td>"
        ShowHTML "        <td><font size=""1"">" & ExibeUnidade("../", w_cliente, RS("sg_unidade_resp"), RS("sq_unidade_resp"), TP) & "</td>"
        If P1 > 2 Then ' Se for cadastramento ou mesa de trabalho, não exibe o executor, pois já é o usuário logado
           If Nvl(RS("nm_exec"),"---") > "---" Then
              ShowHTML "        <td><font size=""1"">" & ExibePessoa("../", w_cliente, RS("executor"), TP, RS("nm_exec")) & "</td>"
           Else
              ShowHTML "        <td><font size=""1"">---</td>"
           End If
        End If
        If RS("sg_tramite") = "AT" Then
           ShowHTML "        <td align=""center""><font size=""1"">&nbsp;" & Nvl(FormatDateTime(RS("inicio_real"),2),"-") & "</td>"
           ShowHTML "        <td align=""center""><font size=""1"">&nbsp;" & Nvl(FormatDateTime(RS("fim_real"),2),"-") & "</td>"
           ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(RS("custo_real"),2) & "&nbsp;</td>"
           w_parcial = w_parcial + cDbl(RS("custo_real"))
        Else
           ShowHTML "        <td align=""center""><font size=""1"">&nbsp;" & Nvl(FormatDateTime(RS("inicio"),2),"-") & "</td>"
           ShowHTML "        <td align=""center""><font size=""1"">&nbsp;" & Nvl(FormatDateTime(RS("fim"),2),"-") & "</td>"
        End If
        If P1 > 1 Then 
           ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(RS("valor"),2) & "&nbsp;</td>"
           w_parcial = w_parcial + cDbl(RS("valor"))
           ShowHTML "        <td nowrap><font size=""1"">" & RS("nm_tramite") & "</td>" 
        End If
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        If P1 <> 3 and P1 <> 5 Then ' Se não for acompanhamento
           If w_copia > "" Then ' Se for listagem para cópia
              DB_GetLinkSubMenu RS1, w_cliente, Request("SG")
              ShowHTML "          <a accesskey=""I"" class=""HL"" href=""" & w_dir & w_pagina & "Geral&R=" & w_pagina & par & "&O=I&SG=" & RS1("sigla") & "&w_menu=" & w_menu & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&w_copia=" & RS("sq_siw_solicitacao") & MontaFiltro("GET") & """>Copiar</a>&nbsp;"
           ElseIf P1 = 1 Then ' Se for cadastramento
              If w_submenu > "" Then
                 ShowHTML "          <A class=""HL"" HREF=""Menu.asp?par=ExibeDocs&O=A&w_chave=" & RS("sq_siw_solicitacao") & "&R=" & w_pagina & par & "&SG=" & SG & "&TP=" & TP & "&w_documento=" & RS("codigo_interno") & MontaFiltro("GET") & """ title=""Altera as informações cadastrais da PCD"" TARGET=""menu"">Alterar</a>&nbsp;"
              Else
                 ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_pagina & par & "&O=A&w_chave=" & RS("sq_siw_solicitacao") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Altera as informações cadastrais da PCD"">Alterar</A>&nbsp"
              End If
              ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "Excluir&R=" & w_pagina & par & "&O=E&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exclusão da PCD."">Excluir</A>&nbsp"
              ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "Envio&R=" & w_pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Encaminhamento da PCD."">Enviar</A>&nbsp"
           ElseIf P1 = 2 Then ' Se for execução
              If RS("sg_tramite") = "DF" Then
                 ShowHTML "          <A class=""hl"" HREF=""javascript:location.href=this.location.href;"" onClick=""window.open('" & w_pagina & "DadosFinanceiros&R=" & w_Pagina & par & "&O=I&w_menu=" & w_menu & "&w_chave=" & RS("sq_siw_solicitacao") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - Dados Financeiros" & "&SG=DADFIN','Financeiro','toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes');"" title=""Informar os dados financeiros da viagem."">Diárias</A>&nbsp" 
              End If
              ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "envio&R=" & w_pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Envia a PCD para outro responsável."">Enviar</A>&nbsp"
           End If
        Else
           If cDbl(Nvl(RS("solicitante"),0)) = cDbl(w_usuario) or _
              cDbl(Nvl(RS("titular"),0))     = cDbl(w_usuario) or _
              cDbl(Nvl(RS("substituto"),0))  = cDbl(w_usuario) or _
              cDbl(Nvl(RS("tit_exec"),0))    = cDbl(w_usuario) or _
              cDbl(Nvl(RS("subst_exec"),0))  = cDbl(w_usuario) _
           Then
              ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "envio&R=" & w_pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Envia a PCD para outro responsável."">Enviar</A>&nbsp"
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
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Para selecionar a PCD que deseja copiar, informe nos campos abaixo os critérios de seleção e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    Else
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    End If
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"" valign=""top""><table border=0 width=""90%"" cellspacing=0>"
    AbreForm "Form", w_dir & w_pagina & par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    If O = "C" Then ' Se for cópia, cria parâmetro para facilitar a recuperação dos registros
       ShowHTML "<INPUT type=""hidden"" name=""w_copia"" value=""OK"">"
    End If

    ' Recupera dados da opção Projetos
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "      <tr>"
    DB_GetLinkData RS, w_cliente, "ISACAD"
    SelecaoProjeto "Açã<u>o</u>:", "O", "Selecione a ação da PCD na relação.", p_projeto, w_usuario, RS("sq_menu"), "p_projeto", "PJLIST", "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_atividade'; document.Form.submit();"""
    DesconectaBD
    ShowHTML "      </tr>"
    ShowHTML "      <tr>"
    SelecaoEtapa "Eta<u>p</u>a:", "P", "Se necessário, indique a etapa à qual esta PCD deve ser vinculada.", p_atividade, p_projeto, null, "p_atividade", null, null
    ShowHTML "      </tr>"
    ShowHTML "          </table>"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"

    If P1 <> 1 or O = "C" Then ' Se não for cadastramento ou se for cópia
       ShowHTML "      <tr valign=""top"">"
       ShowHTML "          <td valign=""top""><font size=""1""><b>Número da <U>d</U>emanda:<br><INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_chave"" size=""18"" maxlength=""18"" value=""" & p_chave & """></td>"
       ShowHTML "          <td valign=""top""><font size=""1""><b>Dias para a data limi<U>t</U>e:<br><INPUT ACCESSKEY=""T"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_prazo"" size=""2"" maxlength=""2"" value=""" & p_prazo & """></td>"
       ShowHTML "      <tr valign=""top"">"
       SelecaoPessoa "Respo<u>n</u>sável:", "N", "Selecione o responsável pela PCD na relação.", p_solicitante, null, "p_solicitante", "USUARIOS"
       SelecaoUnidade "<U>S</U>etor responsável:", "S", null, p_unidade, null, "p_unidade", null, null
       ShowHTML "      <tr valign=""top"">"
       SelecaoPessoa "Responsável atua<u>l</u>:", "L", "Selecione o responsável atual pela PCD na relação.", p_usu_resp, null, "p_usu_resp", "USUARIOS"
       SelecaoUnidade "<U>S</U>etor atual:", "S", "Selecione a unidade onde a PCD se encontra na relação.", p_uorg_resp, null, "p_uorg_resp", null, null
       ShowHTML "      <tr>"
       SelecaoPais "<u>P</u>aís:", "P", null, p_pais, null, "p_pais", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_regiao'; document.Form.submit();"""
       SelecaoRegiao "<u>R</u>egião:", "R", null, p_regiao, p_pais, "p_regiao", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_uf'; document.Form.submit();"""
       ShowHTML "      <tr>"
       SelecaoEstado "E<u>s</u>tado:", "S", null, p_uf, p_pais, "N", "p_uf", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_cidade'; document.Form.submit();"""
       SelecaoCidade "<u>C</u>idade:", "C", null, p_cidade, p_pais, p_uf, "p_cidade", null, null
       ShowHTML "      <tr>"
       SelecaoPrioridade "<u>P</u>rioridade:", "P", "Informe a prioridade desta PCD.", p_prioridade, null, "p_prioridade", null, null
       ShowHTML "          <td valign=""top""><font size=""1""><b>Parcerias exter<u>n</u>as:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_proponente"" size=""25"" maxlength=""90"" value=""" & p_proponente & """></td>"
       ShowHTML "      <tr>"
       ShowHTML "          <td valign=""top""><font size=""1""><b>A<U>s</U>sunto:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_assunto"" size=""25"" maxlength=""90"" value=""" & p_assunto & """></td>"
       ShowHTML "          <td valign=""top"" colspan=2><font size=""1""><b>Pala<U>v</U>ras-chave:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_palavra"" size=""25"" maxlength=""90"" value=""" & p_palavra & """></td>"
       ShowHTML "      <tr>"
       ShowHTML "          <td valign=""top""><font size=""1""><b>Iní<u>c:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""p_ini_i"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_ini_i & """ onKeyDown=""FormataData(this,event);"" title=""Usar formato dd/mm/aaaa""> e <input " & w_Disabled & " accesskey=""C"" type=""text"" name=""p_ini_f"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_ini_f & """ onKeyDown=""FormataData(this,event);"" title=""Usar formato dd/mm/aaaa""></td>"
       ShowHTML "          <td valign=""top""><font size=""1""><b>Limi<u>t</u>e para conclusão entre:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""p_fim_i"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_fim_i & """ onKeyDown=""FormataData(this,event);"" title=""Usar formato dd/mm/aaaa""> e <input " & w_Disabled & " accesskey=""T"" type=""text"" name=""p_fim_f"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_fim_f & """ onKeyDown=""FormataData(this,event);"" title=""Usar formato dd/mm/aaaa""></td>"
       If O <> "C" Then ' Se não for cópia
          ShowHTML "      <tr>"
          ShowHTML "          <td valign=""top""><font size=""1""><b>Exibe somente atividades em atraso?</b><br>"
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
       ShowHTML "          <option value=""assunto"" SELECTED>Descrição<option value=""inicio"">Data de recebimento<option value="""">Data limite para conclusão<option value=""nm_tramite"">Fase atual<option value=""prioridade"">Prioridade<option value=""proponente"">Parcerias externas"
    ElseIf p_Ordena="INICIO" Then
       ShowHTML "          <option value=""assunto"">Descrição<option value=""inicio"" SELECTED>Data de recebimento<option value="""">Data limite para conclusão<option value=""nm_tramite"">Fase atual<option value=""prioridade"">Prioridade<option value=""proponente"">Parcerias externas"
    ElseIf p_Ordena="NM_TRAMITE" Then
       ShowHTML "          <option value=""assunto"">Descrição<option value=""inicio"">Data de recebimento<option value="""">Data limite para conclusão<option value=""nm_tramite"" SELECTED>Fase atual<option value=""prioridade"">Prioridade<option value=""proponente"">Parcerias externas"
    ElseIf p_Ordena="PRIORIDADE" Then
       ShowHTML "          <option value=""assunto"">Descrição<option value=""inicio"">Data de recebimento<option value="""">Data limite para conclusão<option value=""nm_tramite"">Fase atual<option value=""prioridade"" SELECTED>Prioridade<option value=""proponente"">Parcerias externas"
    ElseIf p_Ordena="PROPONENTE" Then
       ShowHTML "          <option value=""assunto"">Descrição<option value=""inicio"">Data de recebimento<option value="""">Data limite para conclusão<option value=""nm_tramite"">Fase atual<option value=""prioridade"">Prioridade<option value=""proponente"" SELECTED>Parcerias externas"
    Else
       ShowHTML "          <option value=""assunto"">Descrição<option value=""inicio"">Data de recebimento<option value="""" SELECTED>Data limite para conclusão<option value=""nm_tramite"">Fase atual<option value=""prioridade"">Prioridade<option value=""proponente"">Parcerias externas"
    End If
    ShowHTML "          </select></td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""STI"" type=""text"" name=""P4"" size=""4"" maxlength=""4"" value=""" & P4 & """></td></tr>"
    ShowHTML "          </table>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
    If O = "C" Then ' Se for cópia
       ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"" name=""Botao"" value=""Abandonar cópia"">"
    Else
       ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"" name=""Botao"" value=""Remover filtro"">"
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

  Set w_tarefa  = Nothing
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
  Dim w_sq_unidade_resp, w_tarefa, w_assunto, w_prioridade, w_aviso, w_dias
  Dim w_cpf, w_inicio_real, w_fim_real, w_concluida, w_nm_prop_res
  Dim w_data_conclusao, w_nota_conclusao, w_custo_real, w_tipo_missao
  Dim w_projeto, w_atividade, w_projeto_ant, w_atividade_ant
  
  Dim w_chave, w_chave_pai, w_chave_aux, w_sq_menu, w_sq_unidade
  Dim w_sq_tramite, w_solicitante, w_cadastrador, w_executor, w_descricao
  Dim w_justif_dia_util, w_inicio, w_fim, w_inclusao, w_ultima_alteracao
  Dim w_conclusao, w_vinculo, w_opiniao, w_data_hora, w_sexo, w_uf, w_sq_prop, w_nm_prop
  
  Dim w_troca, i, w_erro, w_como_funciona, w_cor, w_readonly

  w_chave           = Request("w_chave")
  w_readonly        = ""
  w_erro            = ""
  w_troca           = Request("w_troca")

  ' Verifica se há necessidade de recarregar os dados da tela a partir
  ' da própria tela (se for recarga da tela) ou do banco de dados (se não for inclusão)
  If w_troca > "" Then ' Se for recarga da página
     If Nvl(Request("w_cpf"),"") > "" Then
        ' Recupera os dados do proponente
        DB_GetBenef RS, w_cliente, null, Request("w_cpf"), null, null, 1, null, null
        If RS.RecordCount > 0 Then 
           w_cpf                 = RS("cpf")
           w_sq_prop             = RS("sq_pessoa")
           w_nm_prop             = RS("nm_pessoa")
           w_nm_prop_res         = RS("nome_resumido")
           w_sexo                = RS("sexo")
           w_vinculo             = RS("sq_tipo_vinculo")
        Else
           w_cpf                 = Request("w_cpf") 
           w_sq_prop             = ""
           w_nm_prop             = ""
           w_nm_prop_res         = ""
           w_sexo                = ""
           w_vinculo             = ""
        End If
     End If
     w_sq_unidade_resp     = Request("w_sq_unidade_resp") 
     w_assunto             = Request("w_assunto") 
     w_prioridade          = Request("w_prioridade") 
     w_aviso               = Request("w_aviso") 
     w_dias                = Request("w_dias") 
     w_inicio_real         = Request("w_inicio_real") 
     w_fim_real            = Request("w_fim_real") 
     w_concluida           = Request("w_concluida") 
     w_data_conclusao      = Request("w_data_conclusao") 
     w_nota_conclusao      = Request("w_nota_conclusao") 
     w_custo_real          = Request("w_custo_real") 
     w_atividade           = Request("w_atividade")
     'w_cpf                 = Nvl(w_sexo, Request("w_cpf"))
     'w_sq_prop             = Nvl(w_sexo, Request("w_sq_prop"))
     'w_nm_prop             = Nvl(w_sexo, Request("w_nm_prop"))
     'w_nm_prop_res         = Nvl(w_sexo, Request("w_nm_prop_res"))
     'w_sexo                = Nvl(w_sexo, Request("w_sexo"))
  
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
     w_justif_dia_util     = Request("w_justif_dia_util") 
     w_inicio              = Request("w_inicio") 
     w_fim                 = Request("w_fim") 
     w_inclusao            = Request("w_inclusao") 
     w_ultima_alteracao    = Request("w_ultima_alteracao") 
     w_conclusao           = Request("w_conclusao") 
     w_opiniao             = Request("w_opiniao") 
     w_data_hora           = Request("w_data_hora") 
     w_uf                  = Request("w_uf") 
     w_tipo_missao         = Request("w_tipo_missao") 
  Else
     If InStr("AEV",O) > 0 or w_copia > "" Then
        ' Recupera os dados da PCD
        If w_copia > "" Then
           DB_GetSolicData RS, w_copia, SG
        Else
           DB_GetSolicData RS, w_chave, SG
        End If
        If RS.RecordCount > 0 Then 
           w_sq_unidade_resp     = RS("sq_unidade_resp") 
           w_assunto             = RS("assunto")         
           w_prioridade          = RS("prioridade") 
           w_aviso               = RS("aviso_prox_conc") 
           w_dias                = RS("dias_aviso") 
           w_inicio_real         = RS("inicio_real") 
           w_fim_real            = RS("fim_real") 
           w_concluida           = RS("concluida") 
           w_data_conclusao      = RS("data_conclusao") 
           w_nota_conclusao      = RS("nota_conclusao") 
           w_custo_real          = RS("custo_real") 
  
           w_chave_pai           = RS("sq_solic_pai") 
           w_chave_aux           = null
           w_sq_menu             = RS("sq_menu") 
           w_sq_unidade          = RS("sq_unidade") 
           w_sq_tramite          = RS("sq_siw_tramite") 
           w_solicitante         = RS("solicitante") 
           w_cadastrador         = RS("cadastrador") 
           w_executor            = RS("executor") 
           w_descricao           = RS("descricao") 
           w_tipo_missao         = RS("tp_missao") 
           w_justif_dia_util     = RS("justificativa_dia_util") 
           w_inicio              = FormataDataEdicao(RS("inicio"))
           w_fim                 = FormataDataEdicao(RS("fim"))
           w_inclusao            = RS("inclusao") 
           w_ultima_alteracao    = RS("ultima_alteracao") 
           w_conclusao           = RS("conclusao") 
           w_opiniao             = RS("opiniao") 
           w_data_hora           = RS("data_hora") 
           w_cpf                 = RS("cpf")
           w_nm_prop             = RS("nm_prop") 
           w_nm_prop_res         = RS("nm_prop_res") 
           w_sexo                = RS("sexo") 
           w_vinculo             = RS("sq_tipo_vinculo") 
           w_uf                  = RS("co_uf") 
           w_sq_prop             = RS("sq_prop")
           DesconectaBD
        End If

     End If
     
  End If  
  Cabecalho
  ShowHTML "<HEAD>"
  ' Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  ' tratando as particularidades de cada serviço
  ScriptOpen "JavaScript"
  Modulo
  FormataCPF
  CheckBranco
  FormataData
  ShowHTML "function botoes() {"
  If O = "I" Then
     ShowHTML "  document.Form.Botao[0].disabled = true;"
     ShowHTML "  document.Form.Botao[1].disabled = true;"
  Else
     ShowHTML "  document.Form.Botao.disabled = true;"
  end If
  ShowHTML "}"
  ValidateOpen "Validacao"
  If O = "I" or O = "A" Then
     ShowHTML "  if (theForm.Botao.value == ""Troca"") { return true; }"
     'Validate "w_projeto", "Ação", "SELECT", 1, 1, 18, "", "0123456789"
     'Validate "w_tarefa", "Tarefa", "1", "", 3, 100, "1", "1"
     Validate "w_descricao", "Descrição", "1", 1, 5, 2000, "1", "1"
     Validate "w_sq_unidade_resp", "Unidade proponente", "SELECT", 1, 1, 18, "", "0123456789"
     Validate "w_tipo_missao", "Tipo da PCD", "SELECT", 1, 1, 1, "1", ""
     Validate "w_inicio", "Primeira saída", "DATA", 1, 10, 10, "", "0123456789/"
     Validate "w_fim", "Último retorno", "DATA", 1, 10, 10, "", "0123456789/"
     CompData "w_inicio", "Início previsto", "<=", "w_fim", "Fim previsto"
     Validate "w_justif_dia_util", "Justificativa", "1", "", 5, 2000, "1", "1"
     ShowHTML "  var w_data, w_data1, w_data2;"
     ShowHTML "  w_data = theForm.w_inicio.value;"
     ShowHTML "  w_data = w_data.substr(3,2) + '/' + w_data.substr(0,2) + '/' + w_data.substr(6,4);"
     ShowHTML "  w_data1  = new Date(Date.parse(w_data));"
     ShowHTML "  if ((w_data1.getDay() == 0 || w_data1.getDay() == 5 || w_data1.getDay() == 6) && theForm.w_justif_dia_util.value=='') {"
     ShowHTML "     alert('É necessário justificar o início de viagens em sextas-feiras, sábados, domingos e feriados!');"
     ShowHTML "     theForm.w_inicio.focus();"
     ShowHTML "     return false;"
     ShowHTML "  }"
     ShowHTML "  w_data = theForm.w_fim.value;"
     ShowHTML "  w_data = w_data.substr(3,2) + '/' + w_data.substr(0,2) + '/' + w_data.substr(6,4);"
     ShowHTML "  w_data2  = new Date(Date.parse(w_data));"
     ShowHTML "  if ((w_data2.getDay() == 0 || w_data2.getDay() == 5 || w_data2.getDay() == 6) && theForm.w_justif_dia_util.value=='') {"
     ShowHTML "     alert('É necessário justificar o término de viagens em sextas-feiras, sábados, domingos e feriados!');"
     ShowHTML "     theForm.w_fim.focus();"
     ShowHTML "     return false;"
     ShowHTML "  }"
     If O = "I" and w_cadgeral = "S" Then
        Validate "w_cpf", "CPF", "CPF", "1", "14", "14", "", "0123456789-."
        If w_sq_prop > "" Then
           If Nvl(w_sexo,"") = "" Then
              Validate "w_sexo", "Sexo", "SELECT", 1, 1, 1, "MF", ""
           End If
           If Nvl(w_vinculo,"") = "" Then
              Validate "w_vinculo", "Tipo de vínculo", "SELECT", 1, 1, 18, "", "1"
           End If
        Else
           Validate "w_nm_prop", "Nome do proposto", "1", 1, 5, 60, "1", "1"
           Validate "w_nm_prop_res", "Nome resumido do proposto", "1", 1, 2, 15, "1", "1"
           Validate "w_sexo", "Sexo", "SELECT", 1, 1, 1, "MF", ""
           Validate "w_vinculo", "Tipo de vínculo", "SELECT", 1, 1, 18, "", "1"
        End If
     End If
  End If
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_troca > "" Then
     BodyOpen "onLoad='document.focus()';"
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
    ShowHTML "<INPUT type=""hidden"" name=""w_inicio_atual"" value=""" & w_inicio &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_atividade_ant"" value=""" & w_atividade_ant &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_aviso"" value=""N"">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_prop"" value=""" & w_sq_prop &""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td valign=""top"" align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Identificação</td></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td><font size=1>Os dados deste bloco serão utilizados para identificação da PCD, bem como para o controle de sua execução.</font></td></tr>"
    ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    
    ' Recupera dados da opção Ações
    'ShowHTML "      <tr>"
    'SelecaoAcao "<u>A</u>ção:", "A", null, w_cliente, w_ano, null, null, null, null, "w_projeto", "ACAO", "onchange=""botoes(); document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='w_tarefa'; document.Form.target=''; document.Form.submit();""", w_projeto
    'ShowHTML "      </tr>"
    'ShowHTML "      <tr>"
    'SelecaoTarefa "<u>T</u>arefa:", "T", null, w_cliente, w_ano, w_tarefa, "w_tarefa", Nvl(w_projeto,0), null
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Des<u>c</u>rição sucinta do serviço a ser executado (Objetivo/assunto a ser tratado/evento):</b><br><textarea " & w_Disabled & " accesskey=""c"" name=""w_descricao"" class=""STI"" ROWS=5 cols=75 title=""Descreva, de forma detalhada, os objetivos da PCD."">" & w_descricao & "</TEXTAREA></td>"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    SelecaoUnidade1 "<U>U</U>nidade proponente:", "U", "Selecione a unidade proponente da PCD", w_sq_unidade_resp, null, "w_sq_unidade_resp", "VIAGEM", null, w_ano
    SelecaoTipoPCD "Ti<u>p</u>o:", "P", null, w_tipo_missao, "w_tipo_missao", null, null
    ShowHTML "              <td valign=""top""><font size=""1""><b>Pri<u>m</u>eira saída:</b><br><input " & w_Disabled & " accesskey=""M"" type=""text"" name=""w_inicio"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_inicio & """ onKeyDown=""FormataData(this,event);"" title=""Usar formato dd/mm/aaaa"">" & ExibeCalendario("Form", "w_inicio") & "</td>"
    ShowHTML "              <td valign=""top""><font size=""1""><b>Último re<u>t</u>orno:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_fim"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_fim & """ onKeyDown=""FormataData(this,event);"" title=""Usar formato dd/mm/aaaa"">" & ExibeCalendario("Form", "w_fim") & "</td>"
    ShowHTML "          </table>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>J</u>ustificativa para início e término de viagens em sextas-feiras, sábados, domingos e feriados:</b><br><textarea " & w_Disabled & " accesskey=""J"" name=""w_justif_dia_util"" class=""STI"" ROWS=5 cols=75 title=""É obrigatório justificar, neste campo, início ou término de viagens sextas-feiras, sábados, domingos e feriados. Caso contrário, deixe este campo em branco."">" & w_justif_dia_util & "</TEXTAREA></td>"
    If O = "I" and w_cadgeral = "S" Then
       ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td valign=""top"" align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Dados do Proposto</td></td></tr>"
       ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td><font size=1>Insira abaixo os dados do proposto. Após a gravação serão solicitados dados complementares sobre ele.</font></td></tr>"
       ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
       ShowHTML "        <tr valign=""top"">"
       ShowHTML "            <td><font size=""1""><b><u>C</u>PF:<br><INPUT ACCESSKEY=""C"" TYPE=""text"" class=""sti"" NAME=""w_cpf"" VALUE=""" & w_cpf & """ SIZE=""14"" MaxLength=""14"" onKeyDown=""FormataCPF(this, event);"" onBlur=""botoes(); document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_nm_prop'; document.Form.submit();"">"
       If w_sq_prop > "" Then
          ShowHTML "            <td><font size=""1"">Nome completo:<b><br>" & w_nm_prop & "</td>"
          ShowHTML "            <td><font size=""1"">Nome resumido:<b><br>" & w_nm_prop_res & "</td>"
          If Nvl(w_sexo,"") = "" Then
             SelecaoSexo "Se<u>x</u>o:", "X", null, w_sexo, null, "w_sexo", null, null
          Else
             ShowHTML "<INPUT type=""hidden"" name=""w_sexo"" value=""" & w_sexo &""">"
          End If
          If Nvl(w_vinculo,"") = "" Then
             SelecaoVinculo "Tipo de <u>v</u>ínculo:", "V", null, w_vinculo, null, "w_vinculo", "ativo='S' and sq_tipo_pessoa='Física'"
          Else
             ShowHTML "<INPUT type=""hidden"" name=""w_vinculo"" value=""" & w_vinculo &""">"
          End If
       Else
          ShowHTML "            <td><font size=""1""><b><u>N</u>ome completo:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nm_prop"" class=""sti"" SIZE=""45"" MAXLENGTH=""60"" VALUE=""" & w_nm_prop & """></td>"
          ShowHTML "            <td><font size=""1""><b><u>N</u>ome resumido:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nm_prop_res"" class=""sti"" SIZE=""15"" MAXLENGTH=""15"" VALUE=""" & w_nm_prop_res & """></td>"
          SelecaoSexo "Se<u>x</u>o:", "X", null, w_sexo, null, "w_sexo", null, null
          SelecaoVinculo "Tipo de <u>v</u>ínculo:", "V", null, w_vinculo, null, "w_vinculo", "ativo='S' and sq_tipo_pessoa='Física'"
       End If
       If w_sq_prop > "" Then
       Else
       End If
       ShowHTML "          </table>"
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
    ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_projeto_ant         = Nothing 
  Set w_atividade_ant       = Nothing 
  Set w_projeto             = Nothing 
  Set w_atividade           = Nothing 
  Set w_nm_prop_res         = Nothing 
  Set w_sq_unidade_resp     = Nothing 
  Set w_tarefa              = Nothing
  Set w_assunto             = Nothing 
  Set w_prioridade          = Nothing 
  Set w_aviso               = Nothing 
  Set w_dias                = Nothing 
  Set w_cpf                 = Nothing
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
  Set w_justif_dia_util     = Nothing 
  Set w_inicio              = Nothing 
  Set w_fim                 = Nothing 
  Set w_inclusao            = Nothing 
  Set w_ultima_alteracao    = Nothing 
  Set w_conclusao           = Nothing 
  Set w_vinculo             = Nothing 
  Set w_opiniao             = Nothing 
  Set w_data_hora           = Nothing 
  Set w_tipo_missao         = Nothing 
  Set w_sexo                = Nothing 
  Set w_uf                  = Nothing 
  Set w_sq_prop             = Nothing 
  Set w_nm_prop             = Nothing 
  
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
  Dim w_cpf, w_nascimento, w_rg_numero, w_rg_emissor, w_rg_emissao, w_matricula
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
  w_pessoa_atual    = Request("w_pessoa_atual")
  
  DB_GetSolicData RS, w_chave, SG
  If w_sq_pessoa = "" and Instr(Request("botao"),"Selecionar") = 0 Then
     w_sq_pessoa        = RS("sq_prop")
     w_pessoa_atual     = RS("sq_prop")
  ElseIf Instr(Request("botao"),"Selecionar") = 0 Then
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
  End If
  w_forma_pagamento  = "CREDITO"
  w_sq_tipo_pessoa = 1
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
     w_matricula            = Request("w_matricula")
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
           w_matricula            = RS("passaporte_numero")
           w_sq_pais_passaporte   = RS("sq_pais_passaporte")
           w_sexo                 = RS("sexo")
           w_cnpj                 = RS("cnpj")
           w_inscricao_estadual   = RS("inscricao_estadual")
           If Nvl(w_nr_conta,"") = "" Then
              w_sq_banco          = RS("sq_banco")
              w_sq_agencia        = RS("sq_agencia")
              w_operacao          = RS("operacao")
              w_nr_conta          = RS("nr_conta")
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
     Validate "w_cpf", "CPF", "CPF", "1", "14", "14", "", "0123456789-."
     ShowHTML "  theForm.w_sq_pessoa.value = '';"
     ShowHTML "}"
  ElseIf O = "I" or O = "A" Then
     ShowHTML "  if (theForm.Botao.value.indexOf('Alterar') >= 0) { return true; }"
     Validate "w_nome", "Nome", "1", 1, 5, 60, "1", "1"
     Validate "w_nome_resumido", "Nome resumido", "1", 1, 2, 15, "1", "1"
     Validate "w_sexo", "Sexo", "SELECT", 1, 1, 1, "MF", ""
     If w_sq_tipo_vinculo = "" Then
        Validate "w_sq_tipo_vinculo", "Tipo de vínculo", "SELECT", 1, 1, 1, "", "1"
     End If
     Validate "w_matricula", "Matrícula SIAPE", "1", "", 1, 20, "1", "1"
     Validate "w_rg_numero", "Identidade", "1", 1, 2, 30, "1", "1"
     Validate "w_rg_emissao", "Data de emissão", "DATA", "", 10, 10, "", "0123456789/"
     Validate "w_rg_emissor", "Órgão expedidor", "1", 1, 2, 30, "1", "1"
     Validate "w_ddd", "DDD", "1", "1", 3, 4, "", "0123456789"
     Validate "w_nr_telefone", "Telefone", "1", 1, 7, 25, "1", "1"
     Validate "w_nr_fax", "Fax", "1", "", 7, 25, "1", "1"
     Validate "w_nr_celular", "Celular", "1", "", 7, 25, "1", "1"
     If Instr("CREDITO,DEPOSITO", w_forma_pagamento) > 0 Then
        Validate "w_sq_banco", "Banco", "SELECT", 1, 1, 10, "1", "1"
        Validate "w_sq_agencia", "Agencia", "SELECT", 1, 1, 10, "1", "1"
        Validate "w_operacao", "Operação", "1", "", 1, 6, "", "0123456789"
        Validate "w_nr_conta", "Número da conta", "1", "1", 2, 30, "ZXAzxa", "0123456789-"
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
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
  End If
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If (w_cpf = "" and w_cnpj = "") or Instr(Request("botao"),"Alterar") > 0 or Instr(Request("botao"),"Procurar") > 0 Then ' Se o beneficiário ainda não foi selecionado
     If Instr(Request("botao"),"Procurar") > 0 Then ' Se está sendo feita busca por nome
        BodyOpenClean "onLoad='document.focus()';"
     Else
        BodyOpenClean "onLoad='document.Form.w_cpf.focus()';"
     End If
  ElseIf w_troca > "" Then
     BodyOpenClean "onLoad='document.Form." & w_troca & ".focus()';"
  Else
     BodyOpenClean "onLoad='document.Form.w_nome.focus()';"
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
       ShowHTML "        <tr><td colspan=4><font size=1><b><u>C</u>PF:<br><INPUT ACCESSKEY=""C"" TYPE=""text"" class=""sti"" NAME=""w_cpf"" VALUE=""" & w_cpf & """ SIZE=""14"" MaxLength=""14"" onKeyDown=""FormataCPF(this, event);"">"
       ShowHTML "            <INPUT class=""stb"" TYPE=""submit"" NAME=""Botao"" VALUE=""Selecionar"">"
       ShowHTML "        <tr><td colspan=4><p>&nbsp</p>"
       ShowHTML "        <tr><td colspan=4 heigth=1 bgcolor=""#000000"">"
       ShowHTML "        <tr><td colspan=4>"
       ShowHTML "             <font size=1><b><u>P</u>rocurar pelo nome:</b> (Informe qualquer parte do nome SEM ACENTOS)<br><INPUT ACCESSKEY=""P"" TYPE=""text"" class=""sti"" NAME=""w_nome"" VALUE=""" & w_nome & """ SIZE=""20"" MaxLength=""20"">"
       ShowHTML "              <INPUT class=""stb"" TYPE=""submit"" NAME=""Botao"" VALUE=""Procurar"" onClick=""Botao.value=this.value; document.Form.action='" & w_dir & w_Pagina & par &"'"">"
       ShowHTML "      </table>"
       If w_nome > "" Then
          DB_GetBenef RS, w_cliente, null, null, null, w_nome, 1, null, null
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
              ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_pagina & par & "&R=" & R & "&O=A&w_cpf=" & RS("cpf") & "&w_sq_pessoa=" & RS("sq_pessoa") & "&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&Botao=Selecionar"">Selecionar</A>&nbsp"
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
       ShowHTML "             <td><font size=""1""><b><u>N</u>ome completo:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""45"" MAXLENGTH=""60"" VALUE=""" & w_nome & """></td>"
       ShowHTML "             <td><font size=""1""><b><u>N</u>ome resumido:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nome_resumido"" class=""sti"" SIZE=""15"" MAXLENGTH=""15"" VALUE=""" & w_nome_resumido & """></td>"
       SelecaoSexo "Se<u>x</u>o:", "X", null, w_sexo, null, "w_sexo", null, null
       If Nvl(w_sq_tipo_vinculo,"") = "" Then
          SelecaoVinculo "Tipo de <u>v</u>ínculo:", "V", null, w_sq_tipo_vinculo, null, "w_sq_tipo_vinculo", "ativo='S' and sq_tipo_pessoa='Física'"
       Else
          ShowHTML "<INPUT type=""hidden"" name=""w_sq_tipo_vinculo"" value=""" & w_sq_tipo_vinculo &""">"
       End If
       ShowHTML "          <tr valign=""top"">"
       ShowHTML "          <td title=""Informe este campo apenas se o proposto tiver matrícula SIAPE. Caso contrário, deixe-o em branco.""><font size=""1""><b><u>M</u>atrícula SIAPE:</b><br><input " & w_Disabled & " accesskey=""M"" type=""text"" name=""w_matricula"" class=""sti"" SIZE=""20"" MAXLENGTH=""20"" VALUE=""" & w_matricula & """></td>"
       ShowHTML "          <td><font size=""1""><b><u>I</u>dentidade:</b><br><input " & w_Disabled & " accesskey=""I"" type=""text"" name=""w_rg_numero"" class=""sti"" SIZE=""14"" MAXLENGTH=""80"" VALUE=""" & w_rg_numero & """></td>"
       ShowHTML "          <td><font size=""1""><b>Data de <u>e</u>missão:</b><br><input " & w_Disabled & " accesskey=""E"" type=""text"" name=""w_rg_emissao"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_rg_emissao & """ onKeyDown=""FormataData(this,event);""></td>"
       ShowHTML "          <td><font size=""1""><b>Ór<u>g</u>ão emissor:</b><br><input " & w_Disabled & " accesskey=""G"" type=""text"" name=""w_rg_emissor"" class=""sti"" SIZE=""30"" MAXLENGTH=""30"" VALUE=""" & w_rg_emissor & """></td>"
       ShowHTML "          </table>"
       ShowHTML "      <tr><td colspan=""2"" align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Telefones</td></td></tr>"
       ShowHTML "      <tr><td colspan=""2"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><td colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
       ShowHTML "          <tr valign=""top"">"
       ShowHTML "          <td><font size=""1""><b><u>D</u>DD:</b><br><input " & w_Disabled & " accesskey=""D"" type=""text"" name=""w_ddd"" class=""sti"" SIZE=""4"" MAXLENGTH=""4"" VALUE=""" & w_ddd & """></td>"
       ShowHTML "          <td><font size=""1""><b>Te<u>l</u>efone:</b><br><input " & w_Disabled & " accesskey=""L"" type=""text"" name=""w_nr_telefone"" class=""sti"" SIZE=""20"" MAXLENGTH=""40"" VALUE=""" & w_nr_telefone & """></td>"
       ShowHTML "          <td title=""Se a outra parte informar um número de fax, informe-o neste campo.""><font size=""1""><b>Fa<u>x</u>:</b><br><input " & w_Disabled & " accesskey=""X"" type=""text"" name=""w_nr_fax"" class=""sti"" SIZE=""20"" MAXLENGTH=""20"" VALUE=""" & w_nr_fax & """></td>"
       ShowHTML "          <td title=""Se a outra parte informar um celular institucional, informe-o neste campo.""><font size=""1""><b>C<u>e</u>lular:</b><br><input " & w_Disabled & " accesskey=""E"" type=""text"" name=""w_nr_celular"" class=""sti"" SIZE=""20"" MAXLENGTH=""20"" VALUE=""" & w_nr_celular & """></td>"
       ShowHTML "          </table>"
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
          ShowHTML "          <td title=""Alguns bancos trabalham com o campo 'Operação', além do número da conta. A Caixa Econômica Federal é um exemplo. Se for o caso,informe a operação neste campo; caso contrário, deixe-o em branco.""><font size=""1""><b>O<u>p</u>eração:</b><br><input " & w_Disabled & " accesskey=""O"" type=""text"" name=""w_operacao"" class=""sti"" SIZE=""6"" MAXLENGTH=""6"" VALUE=""" & w_operacao & """></td>"
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
  Set w_matricula           = Nothing 
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
REM Rotina de cadastramento da trechos
REM -------------------------------------------------------------------------
Sub Trechos

  Dim w_chave, w_chave_aux, w_pais_orig, w_uf_orig, w_cidade_orig, w_pais_dest, w_uf_dest, w_cidade_dest
  Dim w_data_saida, w_hora_saida, w_data_chegada, w_hora_chegada, w_inicio, w_fim
  Dim w_troca, i, w_erro, w_como_funciona, w_cor, w_disabled

  w_erro            = ""
  w_troca           = Request("w_troca")
  w_chave           = Request("w_chave")
  w_chave_aux       = Request("w_chave_aux")
  w_inicio          = Request("w_inicio")
  w_fim             = Request("w_fim")
  
  If (O = "I" or O = "A") and Nvl(w_inicio,"") = "" Then
     DB_GetSolicData RS, w_chave, SG
     w_inicio = FormataDataEdicao(FormatDateTime(RS("inicio"),2))
     w_fim    = FormataDataEdicao(FormatDateTime(RS("fim"),2))
  End If
  
  If w_troca > "" Then ' Se for recarga da página
     w_pais_orig        = Request("w_pais_orig")
     w_uf_orig          = Request("w_uf_orig")
     w_cidade_orig      = Request("w_cidade_orig")
     w_pais_dest        = Request("w_pais_dest")
     w_uf_dest          = Request("w_uf_dest")
     w_cidade_dest      = Request("w_cidade_dest")
     w_data_saida       = Request("w_data_saida")
     w_hora_saida       = Request("w_hora_saida")
     w_data_chegada     = Request("w_data_chegada")
     w_hora_chegada     = Request("w_hora_chegada")
  ElseIf O = "L" Then
     DB_GetPD_Deslocamento RS, w_chave, null, SG
     RS.Sort = "saida, chegada"
  ElseIf InStr("AE",O) > 0 Then
     DB_GetPD_Deslocamento RS, w_chave, w_chave_aux, SG
     w_pais_orig        = RS("pais_orig")
     w_uf_orig          = RS("uf_orig")
     w_cidade_orig      = RS("cidade_orig")
     w_pais_dest        = RS("pais_dest")
     w_uf_dest          = RS("uf_dest")
     w_cidade_dest      = RS("cidade_dest")
     w_data_saida       = FormataDataEdicao(FormatDateTime(RS("saida"),2))
     w_hora_saida       = Mid(FormatDateTime(RS("saida"),3),1,5)
     w_data_chegada     = FormataDataEdicao(FormatDateTime(RS("chegada"),2))
     w_hora_chegada     = Mid(FormatDateTime(RS("chegada"),3),1,5)
     DesconectaBD
  End If

  If O = "I" Then
     If w_pais_orig = "" Then
        DB_GetPD_Deslocamento RS1, w_chave, null, SG
        RS1.Sort = "saida desc, chegada desc"
        If RS1.RecordCount = 0 Then
           ' Carrega os valores padrão para país, estado e cidade
           DB_GetCustomerData RS1, w_cliente
           w_pais_orig   = RS1("sq_pais")
           w_uf_orig     = RS1("co_uf")
           w_cidade_orig = RS1("sq_cidade_padrao")
           w_pais_dest   = RS1("sq_pais")
           RS1.Close
        Else
           ' Carrega os valores da última saída
           w_pais_orig   = RS1("pais_dest")
           w_uf_orig     = RS1("uf_dest")
           w_cidade_orig = RS1("cidade_dest")
           w_pais_dest   = RS1("pais_dest")
           RS1.Close
        End If
     End If
  End If
  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente  
  ' Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  ' tratando as particularidades de cada serviço
  ScriptOpen "JavaScript"
  CheckBranco
  FormataData
  FormataHora
  ValidateOpen "Validacao"
  If O = "I" or O = "A" Then
     Validate "w_pais_orig", "País de origem", "SELECT", 1, 1, 18, "", "1"
     Validate "w_uf_orig", "UF de origem", "SELECT", 1, 1, 2, "1", ""
     Validate "w_cidade_orig", "Cidade de origem", "SELECT", 1, 1, 18, "", "1"
     Validate "w_data_saida", "Data de saída", "DATA", "1", 10, 10, "", "0123456789/"
     CompData "w_data_saida", "Data de saída", ">=", w_inicio, "início da missão (" & w_inicio & "), informado na tela de dados gerais"
     CompData "w_data_saida", "Data de saída", "<=", w_fim, "término da missão (" & w_fim & "), informado na tela de dados gerais"
     Validate "w_hora_saida", "Hora de saída", "HORA", "1", 5, 5, "", "0123456789:"

     Validate "w_pais_dest", "País de destino", "SELECT", 1, 1, 18, "", "1"
     Validate "w_uf_dest", "UF de destino", "SELECT", 1, 1, 2, "1", ""
     Validate "w_cidade_dest", "Cidade de destino", "SELECT", 1, 1, 18, "", "1"
     Validate "w_data_chegada", "Data de chegada", "DATA", "1", 10, 10, "", "0123456789/"
     CompData "w_data_chegada", "Data de chegada", ">=", w_inicio, "início da missão (" & w_inicio & "), informado na tela de dados gerais"
     CompData "w_data_chegada", "Data de chegada", "<=", w_fim, "término da missão (" & w_fim & "), informado na tela de dados gerais"
     Validate "w_hora_chegada", "Hora de chegada", "HORA", "1", 5, 5, "", "0123456789:"

     ShowHTML "  if (theForm.w_pais_orig.selectedIndex == theForm.w_pais_dest.selectedIndex && theForm.w_uf_orig.selectedIndex == theForm.w_uf_dest.selectedIndex && theForm.w_cidade_orig.selectedIndex == theForm.w_cidade_dest.selectedIndex) {"
     ShowHTML "      alert('Cidades de origem e de destino não podem ser iguais!'); " & VbCrLf
     ShowHTML "      theForm.w_cidade_dest.focus(); " & VbCrLf
     ShowHTML "      return (false); " & VbCrLf
     ShowHTML "  }"

     CompData "w_data_saida", "Data de saída", "<=", "w_data_chegada", "Data de chegada"
     ShowHTML "  if (theForm.w_data_saida.value == theForm.w_data_chegada.value) {"
     CompHora "w_hora_saida", "Hora de saída", "<", "w_hora_chegada", "Hora de chegada"
     ShowHTML "  }"
     
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
  End If
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_troca > "" Then
     BodyOpenClean "onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf O = "I" or O = "A" Then
     BodyOpenClean "onLoad='document.Form.w_pais_orig.focus()';"
  Else
     BodyOpenClean "onLoad='document.focus()';"
  End If
    Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  ShowHTML "<table align=""center"" border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ShowHTML "<tr><td><font size=""1"">"
    ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_pagina & par & "&O=I&w_chave="&w_chave&"&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Origem</font></td>"
    ShowHTML "          <td><font size=""1""><b>Destino</font></td>"
    ShowHTML "          <td><font size=""1""><b>Saída</font></td>"
    ShowHTML "          <td><font size=""1""><b>Chegada</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=4 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      While Not RS.EOF 
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & RS("nm_origem") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("nm_destino") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & FormatDateTime(RS("saida"),2) & ", " &  Mid(FormatDateTime(RS("saida"),3),1,5) & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & FormatDateTime(RS("chegada"),2) & ", " &  Mid(FormatDateTime(RS("chegada"),3),1,5) & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & par & "&R=" & w_pagina & par & "&O=A&w_chave_aux=" & RS("sq_deslocamento") & "&w_chave=" & RS("sq_siw_solicitacao") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Altera os dados do trecho."">Alterar</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "Grava&R=" & w_pagina & par & "&O=E&w_chave_aux=" & RS("sq_deslocamento") & "&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exclusão do trecho."" onClick=""return(confirm('Confirma exclusão do trecho?'));"">Excluir</A>&nbsp"
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
      DesconectaBD
    End If
  ElseIf Instr("IA",O) > 0 Then
    AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_chave_aux &""">"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr><td colspan=""5"" align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td colspan=""5"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td colspan=""5"" align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Origem</td></td></tr>"
    ShowHTML "      <tr><td colspan=""5"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr valign=""top"">"
    SelecaoPais "<u>P</u>aís:", "P", null, w_pais_orig, null, "w_pais_orig", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_uf_orig'; document.Form.submit();"""
    SelecaoEstado "E<u>s</u>tado:", "S", null, w_uf_orig, w_pais_orig, "N", "w_uf_orig", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_cidade_orig'; document.Form.submit();"""
    SelecaoCidade "<u>C</u>idade:", "C", null, w_cidade_orig, w_pais_orig, w_uf_orig, "w_cidade_orig", null, null
    ShowHTML "          <td><font size=""1""><b><u>S</u>aída:</b><br><input " & w_Disabled & " accesskey=""S"" type=""text"" name=""w_data_saida"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_data_saida & """ onKeyDown=""FormataData(this,event);""> " & ExibeCalendario("Form", "w_data_saida") & "</td>"
    ShowHTML "          <td><font size=""1""><b><u>H</u>ora local:</b><br><input " & w_Disabled & " accesskey=""H"" type=""text"" name=""w_hora_saida"" class=""sti"" SIZE=""5"" MAXLENGTH=""5"" VALUE=""" & w_hora_saida & """ onKeyDown=""FormataHora(this,event);""></td>"
    ShowHTML "      <tr><td colspan=""5"" align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td colspan=""5"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><td colspan=""5"" align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Destino</td></td></tr>"
    ShowHTML "      <tr><td colspan=""5"" align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr valign=""top"">"
    SelecaoPais "<u>P</u>aís:", "P", null, w_pais_dest, null, "w_pais_dest", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_uf_dest'; document.Form.submit();"""
    SelecaoEstado "E<u>s</u>tado:", "S", null, w_uf_dest, w_pais_dest, "N", "w_uf_dest", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_cidade_dest'; document.Form.submit();"""
    SelecaoCidade "<u>C</u>idade:", "C", null, w_cidade_dest, w_pais_dest, w_uf_dest, "w_cidade_dest", null, null
    ShowHTML "          <td><font size=""1""><b><u>C</u>hegada:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_data_chegada"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_data_chegada & """ onKeyDown=""FormataData(this,event);"" onFocus=""if (document.Form.w_data_chegada.value=='') { document.Form.w_data_chegada.value = document.Form.w_data_saida.value; }""> " & ExibeCalendario("Form", "w_data_chegada") & "</td>"
    ShowHTML "          <td><font size=""1""><b><u>H</u>ora local:</b><br><input " & w_Disabled & " accesskey=""H"" type=""text"" name=""w_hora_chegada"" class=""sti"" SIZE=""5"" MAXLENGTH=""5"" VALUE=""" & w_hora_chegada & """ onKeyDown=""FormataHora(this,event);""></td>"
    ShowHTML "      <tr><td colspan=""5""><table border=""0"" width=""100%"">"
    ShowHTML "      <tr><td align=""center"" colspan=""5"" height=""1"" bgcolor=""#000000""></TD></TR>"
    ShowHTML "      <tr><td align=""center"" colspan=""5"">"
    ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Gravar"" onClick=""Botao.value=this.value;"">"
    ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Alterar outra parte"" onClick=""Botao.value=this.value; document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.submit();"">"
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
    Estrutura_Fecha
  Estrutura_Fecha
  Estrutura_Fecha
  Rodape

  Set w_chave               = Nothing
  Set w_chave_aux           = Nothing
  Set w_pais_orig           = Nothing
  Set w_uf_orig             = Nothing
  Set w_cidade_orig         = Nothing
  Set w_pais_dest           = Nothing
  Set w_uf_dest             = Nothing
  Set w_cidade_dest         = Nothing
  Set w_data_saida          = Nothing
  Set w_hora_saida          = Nothing
  Set w_data_chegada        = Nothing
  Set w_hora_chegada        = Nothing
  Set w_troca               = Nothing 
  Set i                     = Nothing 
  Set w_erro                = Nothing 
  Set w_como_funciona       = Nothing 
  Set w_cor                 = Nothing 
  Set w_disabled            = Nothing
End Sub

REM =========================================================================
REM Rotina de vinculação a tarefas e ações
REM -------------------------------------------------------------------------
Sub Vinculacao

  Dim w_chave, w_chave_aux, w_titulo, p_assunto
  Dim w_troca, i, w_erro, w_como_funciona, w_cor, w_disabled

  w_erro            = ""
  w_troca           = Request("w_troca")
  w_chave           = Request("w_chave")
  w_chave_aux       = Request("w_chave_aux")
  p_assunto         = Request("p_assunto")
  
  If O = "L" Then
     DB_GetLinkData RS, w_cliente, "ISTCAD"
     
     DB_GetSolicList_IS RS, RS("sq_menu"), w_usuario, SG, P1, _
        null, null, null, null, null, null, null, null, null, null, p_chave, _
        null, null, null, null, null, null, null, null, null, null, null, _
        null, null, null, null, null, w_ano
     RS.Sort = "titulo"
  End If

  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente  
  ' Monta o código JavaScript necessário para validação de campos e preenchimento automático de máscara,
  ' tratando as particularidades de cada serviço
  ScriptOpen "JavaScript"
  CheckBranco
  FormataData
  FormataHora
  ValidateOpen "Validacao"
  If O = "I" Then
     If Nvl(p_assunto,"") = "" Then
        Validate "p_assunto", "Detalhamento", "", "1", "2", "90", "1", "1"
        ShowHTML "  theForm.Botao[0].disabled=true;"
        ShowHTML "  theForm.Botao[1].disabled=true;"
     Else
        ShowHTML "  if (theForm.Botao.value=='Procurar') {"
        Validate "p_assunto", "Detalhamento", "", "1", "2", "90", "1", "1"
        ShowHTML "  } else {"
        ShowHTML "  var i; "
        ShowHTML "  var w_erro=true; "
        ShowHTML "  if (theForm.w_tarefa.value==undefined) {"
        ShowHTML "     for (i=0; i < theForm.w_tarefa.length; i++) {"
        ShowHTML "       if (theForm.w_tarefa[i].checked) w_erro=false;"
        ShowHTML "     }"
        ShowHTML "  }"
        ShowHTML "  else {"
        ShowHTML "     if (theForm.w_tarefa.checked) w_erro=false;"
        ShowHTML "  }"
        ShowHTML "  if (w_erro) {"
        ShowHTML "    alert('Você deve selecionar pelo menos uma tarefa!'); "
        ShowHTML "    return false;"
        ShowHTML "  }"
        ShowHTML "  }"
        ShowHTML "  theForm.Botao[0].disabled=true;"
        ShowHTML "  theForm.Botao[1].disabled=true;"
        ShowHTML "  theForm.Botao[2].disabled=true;"
     End If
  End If
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_troca > "" Then
     BodyOpenClean "onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf O = "I" and Nvl(p_assunto,"") = "" Then
     BodyOpenClean "onLoad='document.Form.p_assunto.focus()';"
  Else
     BodyOpenClean "onLoad='document.focus()';"
  End If
    Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  ShowHTML "<table align=""center"" border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ShowHTML "<tr><td><font size=""1"">"
    ShowHTML "<tr><td><font size=""1""><a accesskey=""I"" class=""SS"" href=""" & w_dir & w_pagina & par & "&R=" & w_pagina & par & "&O=I&w_chave="&w_chave&"&w_chave_aux="&w_chave_aux&"&&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Nº</font></td>"
    ShowHTML "          <td><font size=""1""><b>Tarefa</font></td>"
    ShowHTML "          <td><font size=""1""><b>Início</font></td>"
    ShowHTML "          <td><font size=""1""><b>Fim</font></td>"
    ShowHTML "          <td><font size=""1""><b>Situação</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      While Not RS.EOF 
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
        ShowHTML "        <A class=""HL"" HREF=""" & w_dir & "Tarefas.asp?par=visual&R=" & w_pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=2&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exibe as informações da tarefa."">" & RS("sq_siw_solicitacao") & "</a>"
        If Len(Nvl(RS("titulo"),"-")) > 50 Then w_titulo = Mid(Nvl(RS("titulo"),"-"),1,50) & "..." Else w_titulo = Nvl(RS("titulo"),"-") End If
        If RS("sg_tramite") = "CA" Then
           ShowHTML "        <td title=""" & replace(replace(replace(RS("titulo"), "'", "\'"), """", "\'"),VbCrLf,"\n") & """><font size=""1""><strike>" & w_titulo & "</strike></td>"
        Else
           ShowHTML "        <td title=""" & replace(replace(replace(RS("titulo"), "'", "\'"), """", "\'"),VbCrLf,"\n") & """><font size=""1"">" & w_titulo & "</td>"
        End IF
        If RS("concluida") = "N" Then
           ShowHTML "        <td align=""center""><font size=""1"">" & FormataDataEdicao(RS("inicio")) & "</td>"
           ShowHTML "        <td align=""center""><font size=""1"">" & FormataDataEdicao(RS("fim")) & "</td>"
        Else
           ShowHTML "        <td align=""center""><font size=""1"">" & FormataDataEdicao(RS("inicio_real")) & "</td>"
           ShowHTML "        <td align=""center""><font size=""1"">" & FormataDataEdicao(RS("fim_real")) & "</td>"
        End If
        ShowHTML "        <td><font size=""1"">" & RS("nm_tramite") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "Grava&R=" & w_pagina & par & "&O=E&w_chave_aux=" & RS("sq_solic_missao") & "&w_tarefa=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Desvinculação da tarefa."" onClick=""return(confirm('Desvincula tarefa?'));"">Desvincular</A>&nbsp"
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
      DesconectaBD
    End If
  ElseIf O = "I" Then
    AbreForm "Form", w_dir & w_pagina & par, "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_chave_aux &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_tarefa"" value="""">"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe uma parte do detalhamento da tarefa e clique sobre o botão <i>Procurar</i>. Em seguida, marque todas as tarefas vinculadas a esta PCD e clique sobre o botão <i>Vincular</i>.<br><br>Você pode fazer diversas procuras ou ainda clicar sobre o botão <i>Cancelar</i> para retornar à listagem das tarefas já vinculadas.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
    ShowHTML "    <table border=""0"">"
    ShowHTML "        <tr><td><font size=1><b><u>D</u>etalhamento:</b> (Informe qualquer parte do detalhamento da tarefa)<br><INPUT ACCESSKEY=""P"" TYPE=""text"" class=""sti"" NAME=""p_assunto"" VALUE=""" & p_assunto & """ SIZE=""40"" MaxLength=""40"">"
    ShowHTML "              <INPUT class=""stb"" TYPE=""submit"" NAME=""Botao"" VALUE=""Procurar"" onClick=""Botao.value=this.value; document.Form.action='" & w_dir & w_Pagina & par &"'"">"
    ShowHTML "              <input class=""stb"" type=""button"" onClick=""location.href='" & R & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_chave=" & w_chave & "&O=L';"" name=""Botao"" value=""Cancelar"">"
    ShowHTML "      </table>"
    If Nvl(p_assunto,"") > "" Then
       DB_GetLinkData RS, w_cliente, "ISTCAD"
       DB_GetSolicList_IS RS, RS("sq_menu"), w_usuario, "ISTCAD", 4, _
           null, null, null, null, null, null, null, null, null, null, null, _
           p_assunto, null, null, null, null, null, null, null, null, null, null, _
           null, null, null, null, null, w_ano
       RS1.Sort = "titulo"
       ShowHTML "<tr><td>"
       ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
       ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
       ShowHTML "          <td><font size=""1""><b>&nbsp;</font></td>"
       ShowHTML "          <td><font size=""1""><b>Nº</font></td>"
       ShowHTML "          <td><font size=""1""><b>Tarefa</font></td>"
       ShowHTML "          <td><font size=""1""><b>Início</font></td>"
       ShowHTML "          <td><font size=""1""><b>Fim</font></td>"
       ShowHTML "          <td><font size=""1""><b>Situação</font></td>"
       ShowHTML "        </tr>"
       If RS.EOF Then
          ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
       Else
          While Not RS.EOF 
            If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
            ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
            ShowHTML "          <td align=""center""><input type=""checkbox"" name=""w_tarefa"" value=""" & RS("sq_siw_solicitacao") & """>"
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
            ShowHTML "        <A class=""HL"" HREF=""" & w_dir & "Tarefas.asp?par=visual&R=" & w_pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=2&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exibe as informações da tarefa."">" & RS("sq_siw_solicitacao") & "</a>"
            If Len(Nvl(RS("titulo"),"-")) > 50 Then w_titulo = Mid(Nvl(RS("titulo"),"-"),1,50) & "..." Else w_titulo = Nvl(RS("titulo"),"-") End If
            If RS("sg_tramite") = "CA" Then
               ShowHTML "        <td title=""" & replace(replace(replace(RS("titulo"), "'", "\'"), """", "\'"),VbCrLf,"\n") & """><font size=""1""><strike>" & w_titulo & "</strike></td>"
            Else
               ShowHTML "        <td title=""" & replace(replace(replace(RS("titulo"), "'", "\'"), """", "\'"),VbCrLf,"\n") & """><font size=""1"">" & w_titulo & "</td>"
            End IF
            If RS("concluida") = "N" Then
               ShowHTML "        <td align=""center""><font size=""1"">" & FormataDataEdicao(RS("inicio")) & "</td>"
               ShowHTML "        <td align=""center""><font size=""1"">" & FormataDataEdicao(RS("fim")) & "</td>"
            Else
               ShowHTML "        <td align=""center""><font size=""1""" & FormataDataEdicao(RS("inicio_real")) & "</td>"
               ShowHTML "        <td align=""center""><font size=""1"">" & FormataDataEdicao(RS("fim_real")) & "</td>"
            End If
            ShowHTML "        <td><font size=""1"">" & RS("nm_tramite") & "</td>"
            ShowHTML "      </tr>"
            RS.MoveNext
          wend
          DesconectaBD
       End If
       ShowHTML "    </table>"
       ShowHTML "  </td>"
       ShowHTML "</tr>"
       ShowHTML "  <tr><td align=""center""><input class=""stb"" type=""submit"" name=""Botao"" value=""Vincular"" onClick=""document.Form.action='" & w_dir & w_pagina & "Grava'""></td></tr>"
       DesConectaBD     
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
  Set w_titulo              = Nothing
  Set p_assunto             = Nothing
  Set w_troca               = Nothing 
  Set i                     = Nothing 
  Set w_erro                = Nothing 
  Set w_como_funciona       = Nothing 
  Set w_cor                 = Nothing 
  Set w_disabled            = Nothing
End Sub

REM =========================================================================
REM Rotina para informação dos dados financeiros
REM -------------------------------------------------------------------------
Sub DadosFinanceiros
  Dim w_chave, w_aux_alimentacao, w_aux_transporte, w_vlr_alimentacao, w_vlr_transporte
  Dim w_qtd_diarias, w_vlr_diarias, w_sq_cidade, w_adicional, w_sq_diaria, w_menu
  Dim w_desc_alimentacao, w_desc_transporte
  Dim w_vetor_trechos(50,9), i, j
  
  w_chave           = Request("w_chave")
  w_menu            = Request("w_menu")
  
  DB_GetSolicData RS, w_chave, "PDGERAL"
  
  w_adicional = Nvl(FormatNumber(RS("valor_adicional"),2),0)
  w_desc_alimentacao =  Nvl(FormatNumber(RS("desconto_alimentacao"),2),0)
  w_desc_transporte =  Nvl(FormatNumber(RS("desconto_transporte"),2),0)
  
  Cabecalho
   
  ShowHTML "<HEAD>"
  ScriptOpen "JavaScript"
  FormataValor
  ValidateOpen "Validacao"
  ShowHTML "  if (theForm.w_aux_alimentacao[0].checked) {"
  ShowHTML "    if (theForm.w_vlr_alimentacao.value=='') {"
  ShowHTML "      alert('Se houver auxílio-alimentação, informe o valor!');"
  ShowHTML "      return false;"  
  ShowHTML "    }"
  CompValor "w_vlr_alimentacao", "Valor auxílio-alimentação", ">", "0,00", "zero"
  ShowHTML "  } else { "
  ShowHTML "    if (theForm.w_vlr_alimentacao.value!='0,00' && theForm.w_vlr_alimentacao.value!='') {"
  ShowHTML "      alert('Se não houver auxílio-alimentação, não informe o valor!');"
  ShowHTML "      return false;"
  ShowHTML "    }"
  ShowHTML "  }"  
  ShowHTML "  if (theForm.w_aux_transporte[0].checked) {"
  ShowHTML "    if (theForm.w_vlr_transporte.value=='') {"
  ShowHTML "      alert('Se houver auxílio-transporte, informe o valor!');"
  ShowHTML "      return false;"  
  ShowHTML "    }"
  CompValor "w_vlr_transporte", "Valor auxílio-transporte", ">", "0,00", "zero"
  ShowHTML "  } else { "
  ShowHTML "    if (theForm.w_vlr_transporte.value!='0,00' && theForm.w_vlr_transporte.value!='') {"
  ShowHTML "      alert('Se não houver auxílio-transporte, não informe o valor!');"
  ShowHTML "      return false;"
  ShowHTML "    }"
  ShowHTML "  }" 
  ShowHTML "  var i,k;"
  ShowHTML "  for (k=0; k < theForm.w_qtd_diarias.length; k++) {"
  ShowHTML "    var w_campo = 'theForm.w_qtd_diarias['+k+']';"
  ShowHTML "    if((eval(w_campo + '.value')!='')&&(eval(w_campo + '.value')=='')){"
  ShowHTML "      alert('Para cada quantidade de diárias informada, informe o valor unitário correspondente!'); "
  ShowHTML "      return false;"           
  ShowHTML "    }"
  ShowHTML "    if (eval(w_campo + '.value.length < 3 && ' + w_campo + '.value != """"'))"
  ShowHTML "    {"
  ShowHTML "      alert('Favor digitar pelo menos 3 posições no campo Quantidade de diárias.');"
  ShowHTML "      eval(w_campo + '.focus()');"
  ShowHTML "      theForm.Botao.disabled=false;"
  ShowHTML "      return (false);"
  ShowHTML "    }"
  ShowHTML "    if (eval(w_campo + '.value.length > 5 && ' + w_campo + '.value != """"'))"
  ShowHTML "    {"
  ShowHTML "      alert('Favor digitar no máximo 5 posições no campo Quantidade de diárias.');"
  ShowHTML "      eval(w_campo + '.focus()');"
  ShowHTML "      theForm.Botao.disabled=false;"
  ShowHTML "      return (false);"
  ShowHTML "    }"
  ShowHTML "    var checkOK = '0123456789,';"
  ShowHTML "    var checkStr = eval(w_campo + '.value');"
  ShowHTML "    var allValid = true;"
  ShowHTML "    for (i = 0;  i < checkStr.length;  i++)"
  ShowHTML "    {"
  ShowHTML "      ch = checkStr.charAt(i);"
  ShowHTML "      if ((checkStr.charCodeAt(i) != 13) && (checkStr.charCodeAt(i) != 10) && (checkStr.charAt(i) != '\\')) {"
  ShowHTML "        for (j = 0;  j < checkOK.length;  j++) {"
  ShowHTML "          if (ch == checkOK.charAt(j)){"
  ShowHTML "            break;"
  ShowHTML "          } "
  ShowHTML "          if (j == checkOK.length-1)"
  ShowHTML "          {"
  ShowHTML "            allValid = false;"
  ShowHTML "            break;"
  ShowHTML "          }"
  ShowHTML "        }"
  ShowHTML "      }"
  ShowHTML "      if (!allValid)"
  ShowHTML "      {"
  ShowHTML "        alert('Favor digitar apenas números no campo Quantidade de diárias.');"
  ShowHTML "        eval(w_campo + '.focus()');"
  ShowHTML "        theForm.Botao.disabled=false;"
  ShowHTML "        return (false);"
  ShowHTML "      }"
  ShowHTML "    } "
  ShowHTML "    if((theForm.w_qtd_diarias[k].value.charAt(theForm.w_qtd_diarias[k].value.indexOf(',')+1)!=5) && (theForm.w_qtd_diarias[k].value.charAt(theForm.w_qtd_diarias[k].value.indexOf(',')+1)!=0)) {"
  ShowHTML "      alert('O valor decimal para quantidade de diarias deve ser 0 ou 5.');"
  ShowHTML "      return (false);"
  ShowHTML "    }"
  ShowHTML "    var V1, V2;"
  ShowHTML "    V1 = theForm.w_qtd_diarias[k].value.toString().replace(/\$|\./g,'');"
  ShowHTML "    V2 = theForm.w_maximo_diarias[k].value.toString().replace(/\$|\./g,'');"
  ShowHTML "    V1 = V1.toString().replace(',','.'); "
  ShowHTML "    V2 = V2.toString().replace(',','.'); "
  ShowHTML "    if(parseFloat(V1) > parseFloat(V2)){"
  ShowHTML "      alert('Quantidade informada  da ' + (k + 1) + 'ª cidade foi execedido('+theForm.w_maximo_diarias[k].value + ').');"
  ShowHTML "      return (false);"
  ShowHTML "    }"
  ShowHTML "  }"
  ShowHTML "  for (k=0; k < theForm.w_vlr_diarias.length; k++) {"
  ShowHTML "    if((theForm.w_vlr_diarias[k].value!='')&&(theForm.w_vlr_diarias[k].value=='')){"
  ShowHTML "      alert('Para cada valor unitário da diária informado, informe a quantidade de diárias correspondente!'); "
  ShowHTML "      return false;"      
  ShowHTML "    }"     
  ShowHTML "    var w_campo = 'theForm.w_vlr_diarias['+k+']';"
  ShowHTML "    if (eval(w_campo + '.value.length < 3 && ' + w_campo + '.value != """"'))"
  ShowHTML "    {"
  ShowHTML "      alert('Favor digitar pelo menos 3 posições no campo Valor unitário da diária.');"
  ShowHTML "      eval(w_campo + '.focus()');"
  ShowHTML "      theForm.Botao.disabled=false;"
  ShowHTML "      return (false);"
  ShowHTML "    }"
  ShowHTML "    if (eval(w_campo + '.value.length > 18 && ' + w_campo + '.value != """"'))"
  ShowHTML "    {"
  ShowHTML "      alert('Favor digitar no máximo 18 posições no campo Valor unitário da diária.');"
  ShowHTML "      eval(w_campo + '.focus()');"
  ShowHTML "      theForm.Botao.disabled=false;"
  ShowHTML "      return (false);"
  ShowHTML "    }"
  ShowHTML "    var checkOK = '0123456789,.';"
  ShowHTML "    var checkStr = eval(w_campo + '.value');"
  ShowHTML "    var allValid = true;"
  ShowHTML "    for (i = 0;  i < checkStr.length;  i++)"
  ShowHTML "    {"
  ShowHTML "      ch = checkStr.charAt(i);"
  ShowHTML "      if ((checkStr.charCodeAt(i) != 13) && (checkStr.charCodeAt(i) != 10) && (checkStr.charAt(i) != '\\')) {"
  ShowHTML "        for (j = 0;  j < checkOK.length;  j++) {"
  ShowHTML "          if (ch == checkOK.charAt(j)){"
  ShowHTML "            break;"
  ShowHTML "          } "
  ShowHTML "          if (j == checkOK.length-1)"
  ShowHTML "          {"
  ShowHTML "            allValid = false;"
  ShowHTML "            break;"
  ShowHTML "          }"
  ShowHTML "        }"
  ShowHTML "      }"
  ShowHTML "      if (!allValid)"
  ShowHTML "      {"
  ShowHTML "        alert('Favor digitar apenas números no campo Valor unitário da diária.');"
  ShowHTML "        eval(w_campo + '.focus()');"
  ShowHTML "        theForm.Botao.disabled=false;"
  ShowHTML "        return (false);"
  ShowHTML "      }"
  ShowHTML "    } "
  ShowHTML "  }"  
  ShowHTML "  theForm.Botao[0].disabled=true;"
  ShowHTML "  theForm.Botao[1].disabled=true;"
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  BodyOpen "onLoad='document.focus()';"
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"  
  ShowHTML "<div align=center><center>"
  ShowHTML "  <table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  ShowHTML "    <tr><td align=""center"" bgcolor=""#FAEBD7"" colspan=""2"">"
  ShowHTML "      <table border=1 width=""100%"">"
  ShowHTML "        <tr><td valign=""top"" colspan=""2"">"    
  ShowHTML "          <TABLE border=0 WIDTH=""100%"" CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
  ShowHTML "            <tr><td><font size=""1"">Número:<b><br>" & RS("codigo_interno") & " (" & w_chave & ")</font></td>"
  DB_GetBenef RS1, w_cliente, Nvl(RS("sq_prop"),0), null, null, null, 1, null, null
  ShowHTML "                <td colspan=""2""><font size=""1"">Proposto:<b><br>" & RS1("nm_pessoa") &  "</font></td></tr>"
  RS1.Close
  ShowHTML "            <tr><td><font size=""1"">Tipo:<b><br>" & RS("nm_tipo_missao") & "</font></td>"
  ShowHTML "                <td><font size=""1"">Primeira saída:<br><b>" & FormataDataEdicao(RS("inicio")) & " </b></td>"
  ShowHTML "                <td><font size=""1"">Último retorno:<br><b>" & FormataDataEdicao(RS("fim")) & " </b></td></tr>"
  ShowHTML "          </TABLE></td></tr>"
  ShowHTML "      </table>"
  ShowHTML "  </table>" 
  AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
  ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave &""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & w_menu &""">"
  ShowHTML "  <table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  ShowHTML "    <tr bgcolor=""" & conTrBgColor & """><td>"
  ShowHTML "      <table width=""99%"" border=""0"">"  
  ShowHTML "        <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Benefícios recebidos pelo servidor</td>"
  ShowHTML "        <tr valign=""top"">"    
  If cDbl(Nvl(RS("valor_alimentacao"),0)) > 0 Then
     MontaRadioSN "<b>Auxílio-Alimentação?</b>", w_aux_alimentacao, "w_aux_alimentacao"
  Else
     MontaRadioNS "<b>Auxílio-Alimentação?</b>", w_aux_alimentacao, "w_aux_alimentacao"
  End If
  ShowHTML "            <td><font size=""1""><b>Valor R$: </b><input type=""text"" name=""w_vlr_alimentacao"" class=""sti"" SIZE=""10"" MAXLENGTH=""18"" VALUE=""" & FormatNumber(Nvl(RS("valor_alimentacao"),0),2) & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o valor do auxílio-alimentação.""></td>"  
  ShowHTML "        </tr>"
  ShowHTML "        <tr valign=""top"">"    
  If cDbl(Nvl(RS("valor_transporte"),0)) > 0 Then
     MontaRadioSN "<b>Auxílio-Transporte?</b>", w_aux_transporte, "w_aux_transporte"
  Else
     MontaRadioNS "<b>Auxílio-Transporte?</b>", w_aux_transporte, "w_aux_transporte"
  End If
  ShowHTML "        <td><font size=""1""><b>Valor R$: </b><input type=""text"" name=""w_vlr_transporte"" class=""sti"" SIZE=""10"" MAXLENGTH=""18"" VALUE=""" & FormatNumber(Nvl(RS("valor_transporte"),0),2) & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o valor do auxílio-transporte.""></td>"  
  ShowHTML "        </tr>"
  DesconectaBD
  ShowHTML "        <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Dados da viagem/cálculo das diárias</td>"  
  DB_GetPD_Deslocamento RS, w_chave, null, SG
  RS.Sort = "saida, chegada"
  If Not RS.EOF Then
     RS.MoveFirst
     i = 1
     While Not RS.EOF
        w_vetor_trechos(i,1) = RS("sq_diaria")
        w_vetor_trechos(i,2) = RS("cidade_dest")
        w_vetor_trechos(i,3) = RS("nm_destino")
        w_vetor_trechos(i,4) = FormataDataEdicao(FormatDateTime(RS("saida"),2)) & ", " &  Mid(FormatDateTime(RS("saida"),3),1,5)
        w_vetor_trechos(i,5) = FormataDataEdicao(FormatDateTime(RS("chegada"),2)) & ", " &  Mid(FormatDateTime(RS("chegada"),3),1,5)
        w_vetor_trechos(i,6) = FormatNumber(Nvl(RS("quantidade"),0),1)
        w_vetor_trechos(i,7) = FormatNumber(Nvl(RS("valor"),0),2)
        w_vetor_trechos(i,8) = RS("saida")
        w_vetor_trechos(i,9) = RS("chegada")
        i = i + 1
        RS.MoveNext
     wend
     ShowHTML "     <tr><td align=""center"" colspan=""2"">"
     ShowHTML "       <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     ShowHTML "         <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
     ShowHTML "         <td><font size=""1""><b>Destino</font></td>"
     ShowHTML "         <td><font size=""1""><b>Saida</font></td>"
     ShowHTML "         <td><font size=""1""><b>Chegada</font></td>"
     ShowHTML "         <td><font size=""1""><b>Quantidade de diárias</font></td>"
     ShowHTML "         <td><font size=""1""><b>Valor unitário R$</font></td>"
     ShowHTML "         </tr>"
     w_cor = conTrBgColor
     j = i
     i = 1
     While Not i = j
       ShowHTML "<INPUT type=""hidden"" name=""w_sq_diaria"" value=""" & w_vetor_trechos(i,1) & """>"
       ShowHTML "<INPUT type=""hidden"" name=""w_sq_cidade"" value=""" & w_vetor_trechos(i,2) &""">"
       ShowHTML "<INPUT type=""hidden"" name=""w_maximo_diarias"" value=""" & DateDiff("d",FormatDateTime(w_vetor_trechos(i,9),2),FormatDateTime(Nvl(w_vetor_trechos(i+1,8),w_vetor_trechos(i,9)),2))  &""">"
       If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
       ShowHTML "     <tr valign=""top"" bgcolor=""" & w_cor & """>"
       ShowHTML "       <td><font size=""1"">" & w_vetor_trechos(i,3) & "</td>"
       ShowHTML "       <td align=""center""><font size=""1"">" & w_vetor_trechos(i,4) & "</td>"
       ShowHTML "       <td align=""center""><font size=""1"">" & w_vetor_trechos(i,5) & "</td>"
       ShowHTML "       <td align=""right""><font size=""1""><input type=""text"" name=""w_qtd_diarias"" class=""sti"" SIZE=""10"" MAXLENGTH=""5"" VALUE=""" & w_vetor_trechos(i,6) & """ onKeyDown=""FormataValor(this,5,1,event);"" title=""Informe a quantidade de diárias para este destino.""></td>"
       ShowHTML "       <td align=""right""><font size=""1""><input type=""text"" name=""w_vlr_diarias"" class=""sti"" SIZE=""10"" MAXLENGTH=""18"" VALUE=""" & w_vetor_trechos(i,7) & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o valor unitário das diárias para este destino.""></td>"    
       ShowHTML "     </tr>"
       i = i + 1
     wend
     ShowHTML "        <tr><td valign=""top"" colspan=""5"" align=""center"" bgcolor=""" & conTrBgColor & """><font size=""1""><b>Outros valores</td>"  
     ShowHTML "        <tr bgcolor=""" & conTrAlternateBgColor & """>"
     ShowHTML "          <td align=""right"" colspan=""4""><font size=""1""><b>adicional:</b></td>"
     ShowHTML "          <td align=""right""><font size=""1""><input type=""text"" name=""w_adicional"" class=""sti"" SIZE=""10"" MAXLENGTH=""18"" VALUE=""" & w_adicional & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o valor adicional.""></td>"
     ShowHTML "        </tr>" 
     ShowHTML "        <tr bgcolor=""" & conTrBgColor & """>"
     ShowHTML "          <td align=""right"" colspan=""4""><font size=""1""><b>desconto auxílio-alimentação:</b></td>"
     ShowHTML "          <td align=""right""><font size=""1""><input type=""text"" name=""w_desc_alimentacao"" class=""sti"" SIZE=""10"" MAXLENGTH=""18"" VALUE=""" & w_desc_alimentacao & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o desconto do auxílio-alimentação.""></td>"
     ShowHTML "        </tr>"
     ShowHTML "        <tr bgcolor=""" & conTrAlternateBgColor & """>"
     ShowHTML "          <td align=""right"" colspan=""4""><font size=""1""><b>desconto auxílio-transporte:</b></td>"
     ShowHTML "          <td align=""right""><font size=""1""><input type=""text"" name=""w_desc_transporte"" class=""sti"" SIZE=""10"" MAXLENGTH=""18"" VALUE=""" & w_desc_transporte & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o desconto do auxílio-transporte.""></td>"
     ShowHTML "        </tr>"  
     ShowHTML "        </table></td></tr>"
  End If
'  If Not Rs.EOF Then
'     ShowHTML "     <tr><td align=""center"" colspan=""2"">"
'     ShowHTML "       <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
'     ShowHTML "         <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
'     ShowHTML "         <td><font size=""1""><b>Destino</font></td>"
'     ShowHTML "         <td><font size=""1""><b>Saida</font></td>"
'     ShowHTML "         <td><font size=""1""><b>Chegada</font></td>"
'     ShowHTML "         <td><font size=""1""><b>Quantidade de diárias</font></td>"
'     ShowHTML "         <td><font size=""1""><b>Valor unitário R$</font></td>"
'     ShowHTML "         </tr>"
'     w_cor = conTrBgColor
'     While Not Rs.EOF
'       ShowHTML "<INPUT type=""hidden"" name=""w_sq_diaria"" value=""" & RS("sq_diaria") &""">"
'       ShowHTML "<INPUT type=""hidden"" name=""w_sq_cidade"" value=""" & RS("cidade_dest") &""">"
'       If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
'       ShowHTML "     <tr valign=""top"" bgcolor=""" & w_cor & """>"
'       ShowHTML "       <td><font size=""1"">" & RS("nm_destino") & "</td>"
'       ShowHTML "       <td align=""center""><font size=""1"">" & FormataDataEdicao(FormatDateTime(RS("saida"),2)) & ", " &  Mid(FormatDateTime(RS("saida"),3),1,5) & "</td>"
'       ShowHTML "       <td align=""center""><font size=""1"">" & FormataDataEdicao(FormatDateTime(RS("chegada"),2)) & ", " &  Mid(FormatDateTime(RS("chegada"),3),1,5) & "</td>"
'       ShowHTML "       <td align=""right""><font size=""1""><input type=""text"" name=""w_qtd_diarias"" class=""sti"" SIZE=""10"" MAXLENGTH=""5"" VALUE=""" & FormatNumber(Nvl(RS("quantidade"),0),1) & """ onKeyDown=""FormataValor(this,5,1,event);"" title=""Informe a quantidade de diárias para este destino.""></td>"
'       ShowHTML "       <td align=""right""><font size=""1""><input type=""text"" name=""w_vlr_diarias"" class=""sti"" SIZE=""10"" MAXLENGTH=""18"" VALUE=""" & FormatNumber(Nvl(RS("valor"),0),2) & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o valor unitário das diárias para este destino.""></td>"    
'       ShowHTML "     </tr>"
'       Rs.MoveNext
'     wend
'     ShowHTML "        <tr><td valign=""top"" colspan=""5"" align=""center"" bgcolor=""" & conTrBgColor & """><font size=""1""><b>Outros valores</td>"  
'     ShowHTML "        <tr bgcolor=""" & conTrAlternateBgColor & """>"
'     ShowHTML "          <td align=""right"" colspan=""4""><font size=""1""><b>adicional:</b></td>"
'     ShowHTML "          <td align=""right""><font size=""1""><input type=""text"" name=""w_adicional"" class=""sti"" SIZE=""10"" MAXLENGTH=""18"" VALUE=""" & w_adicional & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o valor adicional.""></td>"
'     ShowHTML "        </tr>" 
'     ShowHTML "        <tr bgcolor=""" & conTrBgColor & """>"
'     ShowHTML "          <td align=""right"" colspan=""4""><font size=""1""><b>desconto auxílio-alimentação:</b></td>"
'     ShowHTML "          <td align=""right""><font size=""1""><input type=""text"" name=""w_desc_alimentacao"" class=""sti"" SIZE=""10"" MAXLENGTH=""18"" VALUE=""" & w_desc_alimentacao & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o desconto do auxílio-alimentação.""></td>"
'     ShowHTML "        </tr>"
'     ShowHTML "        <tr bgcolor=""" & conTrAlternateBgColor & """>"
'     ShowHTML "          <td align=""right"" colspan=""4""><font size=""1""><b>desconto auxílio-transporte:</b></td>"
'     ShowHTML "          <td align=""right""><font size=""1""><input type=""text"" name=""w_desc_transporte"" class=""sti"" SIZE=""10"" MAXLENGTH=""18"" VALUE=""" & w_desc_transporte & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o desconto do auxílio-transporte.""></td>"
'     ShowHTML "        </tr>"  
'     ShowHTML "        </table></td></tr>"
'  End If
  ShowHTML "        <tr><td align=""center"" colspan=""2"">"
  ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Gravar"">"
  ShowHTML "            <input class=""STB"" type=""button"" onClick=""window.close();"" name=""Botao"" value=""Fechar"">"
  DesconectaBD  
  ShowHTML "      </table>"
  ShowHTML "    </td>"
  ShowHTML "</tr>"
  ShowHTML "</FORM>"
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape
  
  Set w_chave              = Nothing
  Set w_aux_alimentacao    = Nothing
  Set w_aux_transporte     = Nothing
  Set w_vlr_alimentacao    = Nothing
  Set w_vlr_transporte     = Nothing
  Set w_qtd_diarias        = Nothing
  Set w_vlr_diarias        = Nothing
  Set w_sq_cidade          = Nothing
  Set w_adicional          = Nothing
  Set w_desc_alimentacao   = Nothing
  Set w_desc_transporte    = Nothing
  Set w_sq_diaria          = Nothing
  Set w_menu               = Nothing
  
End Sub

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
     cabecalho
  End if
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>" & conSgSistema & " - Visualização de Tarefa</TITLE>"
  ShowHTML "</HEAD>"  
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_tipo <> "WORD" Then
     BodyOpenClean "onLoad='document.focus()'; "
  End If
  ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "EMBED") & """><TD ALIGN=""RIGHT""><B><FONT SIZE=4 COLOR=""#000000"">"
  ShowHTML "Visualização de PCD"
  ShowHTML "</FONT><TR><TD ALIGN=""RIGHT""><B><FONT SIZE=2 COLOR=""#000000"">" & DataHora() & "</B>"
  If w_tipo <> "WORD" Then
     ShowHTML "&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Imprimir"" SRC=""images/impressora.jpg"" onClick=""window.print();"">"
     ShowHTML "&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Gerar word"" SRC=""images/word.gif"" onClick=""window.open('" & w_pagina & "Visual&R=" & w_pagina & par & "&O=L&w_chave=" & w_chave & "&w_tipo=word&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=1&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") &"','VisualAcaoWord','menubar=yes resizable=yes scrollbars=yes');"">"
  End If
  ShowHTML "</TD></TR>"
  ShowHTML "</FONT></B></TD></TR></TABLE>"
  ShowHTML "<HR>"
  If w_tipo > "" and w_tipo <> "WORD" Then
     ShowHTML "<center><B><FONT SIZE=2>Clique <a class=""HL"" href=""javascript:history.back(1);"">aqui</a> para voltar à tela anterior</font></b></center>"
  End If

  ' Chama a rotina de visualização dos dados da PCD, na opção "Listagem"
  ShowHTML VisualViagem(w_chave, "L", w_usuario, P1, P4)

  If w_tipo > "" and w_tipo <> "WORD" Then
     ShowHTML "<center><B><FONT SIZE=2>Clique <a class=""HL"" href=""javascript:history.back(1);"">aqui</a> para voltar à tela anterior</font></b></center>"
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

  ' Chama a rotina de visualização dos dados da PCD, na opção "Listagem"
  ShowHTML VisualViagem(w_chave, "V", w_usuario, P1, P4)

  ShowHTML "<HR>"
  AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"PDIDENT",R,O
  ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & w_menu & """>"

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

  Dim w_chave, w_chave_pai, w_chave_aux, w_destinatario, w_despacho, w_justificativa
  Dim w_inicio, w_prazo
  Dim w_tramite, w_sg_tramite, w_envio, w_tipo
  
  Dim w_troca, i, w_erro
  
  w_Chave           = Request("w_Chave")
  w_chave_aux       = Request("w_chave_aux")
  w_troca           = Request("w_troca")
  w_tipo            = Nvl(Request("w_tipo"),"")
  
  If w_troca > "" Then ' Se for recarga da página
     w_tramite      = Request("w_tramite")
     w_destinatario = Request("w_destinatario")
     w_envio        = Request("w_envio")
     w_despacho     = Request("w_despacho")
  Else
     DB_GetSolicData RS, w_chave, SG
     w_inicio       = RS("inicio")
     w_tramite      = RS("sq_siw_tramite")
     DesconectaBD

     ' Recupera os parâmetros do módulo de viagem
     DB_GetPDParametro RS, w_cliente, null, null
     w_prazo        = cDbl(RS("dias_antecedencia"))
     DesconectaBD
  End If
  
  ' Recupera a sigla do trâmite desejado, para verificar a lista de possíveis destinatários.
  DB_GetTramiteData RS, w_tramite
  w_sg_tramite   = RS("sigla")
  DesconectaBD

  ' Se for envio, executa verificações nos dados da solicitação
  If O = "V" Then w_erro = ValidaViagem(w_cliente, w_chave, SG, "PDGERAL", null, null, w_tramite) End If

  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=../" & MontaURL("MESA") & """>"
  If InStr("V",O) > 0 Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     If w_sg_tramite = "CI" Then
        If (w_inicio - w_prazo) < Date() Then
           Validate "w_justificativa", "Justificativa", "", "1", "1", "2000", "1", "1"
        End If
     Else
        If Nvl(Mid(w_erro,1,1),"") = "0" Then
           Validate "w_despacho", "Despacho", "1", "1", "1", "2000", "1", "1"
        Else
           Validate "w_despacho", "Despacho", "", "", "1", "2000", "1", "1"
           ShowHTML "  if (theForm.w_envio[0].checked && theForm.w_despacho.value != '') {"
           ShowHTML "     alert('Informe o despacho apenas se for devolução para a fase anterior!');"
           ShowHTML "     theForm.w_despacho.focus();"
           ShowHTML "     return false;"
           ShowHTML "  }"
           ShowHTML "  if (theForm.w_envio[1].checked && theForm.w_despacho.value == '') {"
           ShowHTML "     alert('Informe um despacho descrevendo o motivo da devolução!');"
           ShowHTML "     theForm.w_despacho.focus();"
           ShowHTML "     return false;"
           ShowHTML "  }"
        End If
     End If
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
     BodyOpen "onLoad='document.focus()';"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"

  ' Chama a rotina de visualização dos dados da PCD, na opção "Listagem"
  ShowHTML VisualViagem(w_chave, "V", w_usuario, P1, P4)

  ShowHTML "<HR>"
  If Nvl(w_erro,"") = "" or (Nvl(w_erro,"") > "" and RetornaGestor(w_chave, w_usuario) = "S") Then
     AbreForm "Form", w_dir & w_pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"PDENVIO",R,O
     ShowHTML MontaFiltro("POST")
     ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
     ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
     ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & w_menu & """>"
     ShowHTML "<INPUT type=""hidden"" name=""w_tramite"" value=""" & w_tramite & """>"
 
     ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
     ShowHTML "  <table width=""97%"" border=""0"">"
     ShowHTML "    <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%""><tr valign=""top"">"
     If w_sg_tramite = "CI" Then ' Se cadastramento inicial
        ShowHTML "<INPUT type=""hidden"" name=""w_envio"" value=""N"">"
        If (w_inicio - w_prazo) < Date() Then
           ShowHTML "    <tr><td><font size=""1""><b><u>J</u>ustificativa para não cumprimento do prazo regulamentar de " & w_prazo & " dias:</b><br><textarea " & w_Disabled & " accesskey=""J"" name=""w_justificativa"" class=""STI"" ROWS=5 cols=75 title=""Se o início da viagem for anterior a " & FormataDataEdicao(FormatDateTime(Date()+w_prazo,2)) & ", justifique o motivo do não cumprimento do prazo regulamentar para o pedido."">" & w_justificativa & "</TEXTAREA></td>"
        End If
     Else
        ShowHTML "    <tr valign=""top"" align=""center""><font size=1><b>Tipo do Encaminhamento</b><br>"
        If Nvl(Mid(w_erro,1,1),"") = "0" Then
           ShowHTML "              <input DISABLED class=""STR"" type=""radio"" name=""w_envio"" value=""N""> Enviar para a próxima fase <br><input DISABLED class=""STR"" class=""STR"" type=""radio"" name=""w_envio"" value=""S"" checked> Devolver para a fase anterior"
           ShowHTML "<INPUT type=""hidden"" name=""w_envio"" value=""S"">"
        Else
           If Nvl(w_envio,"N") = "N" Then
              ShowHTML "              <input " & w_Disabled & " class=""STR"" type=""radio"" name=""w_envio"" value=""N"" checked> Enviar para a próxima fase <br><input " & w_Disabled & " class=""STR"" class=""STR"" type=""radio"" name=""w_envio"" value=""S""> Devolver para a fase anterior"
           Else
              ShowHTML "              <input " & w_Disabled & " class=""STR"" type=""radio"" name=""w_envio"" value=""N""> Enviar para a próxima fase <br><input " & w_Disabled & " class=""STR"" class=""STR"" type=""radio"" name=""w_envio"" value=""S"" checked> Devolver para a fase anterior"
           End If
        End If
        ShowHTML "    <tr><td><font size=""1""><b>D<u>e</u>spacho (informar apenas se for devolução à fase anterior):</b><br><textarea " & w_Disabled & " accesskey=""E"" name=""w_despacho"" class=""STI"" ROWS=5 cols=75 title=""Informe o que o destinatário deve fazer quando receber a PCD."">" & w_despacho & "</TEXTAREA></td>"
     End If
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
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_prazo           = Nothing
  Set w_justificativa   = Nothing
  Set w_envio           = Nothing 
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
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"

  ' Chama a rotina de visualização dos dados da PCD, na opção "Listagem"
  ShowHTML VisualTarefa(w_chave, "V", w_usuario)

  ShowHTML "<HR>"
  ShowHTML "<FORM action=""" & w_dir & w_pagina & "Grava&SG=PDENVIO&O="&O&"&w_menu="&w_menu&""" name=""Form"" onSubmit=""return(Validacao(this));"" enctype=""multipart/form-data"" method=""POST"">"
  ShowHTML "<INPUT type=""hidden"" name=""P1"" value=""" & P1 & """>"
  ShowHTML "<INPUT type=""hidden"" name=""P2"" value=""" & P2 & """>"
  ShowHTML "<INPUT type=""hidden"" name=""P3"" value=""" & P3 & """>"
  ShowHTML "<INPUT type=""hidden"" name=""P4"" value=""" & P4 & """>"
  ShowHTML "<INPUT type=""hidden"" name=""TP"" value=""" & TP & """>"
  ShowHTML "<INPUT type=""hidden"" name=""R"" value=""" & R & """>"

  ShowHTML MontaFiltro("POST")
  ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
  DB_GetSolicData RS, w_chave, SG
  ShowHTML "<INPUT type=""hidden"" name=""w_tramite"" value=""" & RS("sq_siw_tramite") & """>"
  DesconectaBD

  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "  <table width=""97%"" border=""0"">"
  ShowHTML "    <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
  DB_GetCustomerData RS, w_cliente  
  ShowHTML "      <tr><td align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""2""><b><font color=""#BC3131"">ATENÇÃO</font>: o tamanho máximo aceito para o arquivo é de " & cDbl(RS("upload_maximo"))/1024 & " KBytes</b>.</font></td>"  
  ShowHTML "<INPUT type=""hidden"" name=""w_upload_maximo"" value=""" & RS("upload_maximo") & """>"  
  ShowHTML "      <tr><td valign=""top""><font size=""1""><b>A<u>n</u>otação:</b><br><textarea " & w_Disabled & " accesskey=""N"" name=""w_observacao"" class=""STI"" ROWS=5 cols=75 title=""Redija a anotação desejada."">" & w_observacao & "</TEXTAREA></td>"  
  ShowHTML "      <tr><td><font size=""1""><b>A<u>r</u>quivo:</b><br><input " & w_Disabled & " accesskey=""R"" type=""file"" name=""w_caminho"" class=""STI"" SIZE=""80"" MAXLENGTH=""100"" VALUE="""" title=""OPCIONAL. Se desejar anexar um arquivo, clique no botão ao lado para localizá-lo. Ele será transferido automaticamente para o servidor."">"  
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
     Validate "w_custo_real", "Rercurso executado", "VALOR", "1", 4, 18, "", "0123456789.,"
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

  ' Chama a rotina de visualização dos dados da PCD, na opção "Listagem"
  ShowHTML VisualTarefa(w_chave, "V", w_usuario)

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
  DB_GetSolicData RS, w_chave, SG
  ShowHTML "<INPUT type=""hidden"" name=""w_tramite"" value=""" & RS("sq_siw_tramite") & """>"
  DesconectaBD

  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "  <table width=""97%"" border=""0"">"
  DB_GetCustomerData RS, w_cliente 
  ShowHTML "      <tr><td align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""2""><b><font color=""#BC3131"">ATENÇÃO</font>: o tamanho máximo aceito para o arquivo é de " & cDbl(RS("upload_maximo"))/1024 & " KBytes</b>.</font></td>" 
  ShowHTML "<INPUT type=""hidden"" name=""w_upload_maximo"" value=""" & RS("upload_maximo") & """>" 

  ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
  ShowHTML "          <tr>"
  ShowHTML "              <td valign=""top""><font size=""1""><b>Iní<u>c</u>io da execução:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_inicio_real"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_inicio_real & """ onKeyDown=""FormataData(this,event);"" title=""Informe a data de início da execução da PCD.(Usar formato dd/mm/aaaa)""></td>"
  ShowHTML "              <td valign=""top""><font size=""1""><b><u>T</u>érmino da execução:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_fim_real"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_fim_real & """ onKeyDown=""FormataData(this,event);"" title=""Informe a data de término da execução da PCD.(Usar formato dd/mm/aaaa)""></td>"
  ShowHTML "              <td valign=""top""><font size=""1""><b><u>R</u>ecurso executado:</b><br><input " & w_Disabled & " accesskey=""O"" type=""text"" name=""w_custo_real"" class=""STI"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_custo_real & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o valor que foi efetivamente gasto com a execução da PCD.""></td>"
  ShowHTML "          </table>"
  ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Nota d<u>e</u> conclusão:</b><br><textarea " & w_Disabled & " accesskey=""E"" name=""w_nota_conclusao"" class=""STI"" ROWS=5 cols=75 title=""Insira informações relevantes sobre a conclusão da PCD."">" & w_nota_conclusao & "</TEXTAREA></td>"  
  ShowHTML "      <tr><td><font size=""1""><b>A<u>r</u>quivo:</b><br><input " & w_Disabled & " accesskey=""R"" type=""file"" name=""w_caminho"" class=""STI"" SIZE=""80"" MAXLENGTH=""100"" VALUE="""" title=""OPCIONAL. Se desejar anexar um arquivo, clique no botão ao lado para localizá-lo. Ele será transferido automaticamente para o servidor."">"  
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
REM Rotina de preparação para envio de e-mail relativo a PCDs
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
     w_html = w_html & "      <tr valign=""top""><td align=""center""><font size=2><b>INCLUSÃO DE TAREFA</b></font><br><br><td></tr>" & VbCrLf
  ElseIf p_tipo = 2 Then
     w_html = w_html & "      <tr valign=""top""><td align=""center""><font size=2><b>TRAMITAÇÃO DE TAREFA</b></font><br><br><td></tr>" & VbCrLf
  ElseIf p_tipo = 3 Then
     w_html = w_html & "      <tr valign=""top""><td align=""center""><font size=2><b>CONCLUSÃO DE TAREFA</b></font><br><br><td></tr>" & VbCrLf
  End IF
  w_html = w_html & "      <tr valign=""top""><td><font size=2><b><font color=""#BC3131"">ATENÇÃO</font>: Esta é uma mensagem de envio automático. Não responda esta mensagem.</b></font><br><br><td></tr>" & VbCrLf


  ' Recupera os dados da PCD
  DB_GetSolicData RSM, p_solic, SG
  
  w_nome = "Tarefa " & RSM("sq_siw_solicitacao")

  w_html = w_html & VbCrLf & "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  w_html = w_html & VbCrLf & "    <table width=""99%"" border=""0"">"
  'w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Ação: <b>" & RSM("nm_projeto") & "</b></td>"
  'w_html = w_html & VbCrLf & "      <tr><td><font size=1>Detalhamento: <b>" & CRLF2BR(RSM("assunto")) & "</b></font></td></tr>"
      
  ' Identificação da PCD
  w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>EXTRATO DA TAREFA</td>"
  w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
  w_html = w_html & VbCrLf & "          <tr valign=""top"">"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Responsável pelo monitoramento:<br><b>" & RSM("nm_sol") & "</b></td>"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Área planejamento:<br><b>" & RSM("nm_unidade_resp") & "</b></td>"
  w_html = w_html & VbCrLf & "          <tr valign=""top"">"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Data de recebimento:<br><b>" & FormataDataEdicao(RSM("inicio")) & " </b></td>"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Limite para conclusão:<br><b>" & FormataDataEdicao(RSM("fim")) & " </b></td>"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Prioridade:<br><b>" & RetornaPrioridade(RSM("prioridade")) & " </b></td>"
  w_html = w_html & VbCrLf & "          </table>"

  ' Informações adicionais
  If Nvl(RSM("descricao"),"") > "" Then 
     If Nvl(RSM("descricao"),"") > "" Then w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Resultados da PCD:<br><b>" & CRLF2BR(RSM("descricao")) & " </b></td>" End If
  End If

  w_html = w_html & VbCrLf & "    </table>"
  w_html = w_html & VbCrLf & "</tr>"

  ' Dados da conclusão da PCD, se ela estiver nessa situação
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
  Dim p_modulo, w_codigo
  Dim w_Null
  Dim w_mensagem
  Dim FS, F1, w_file
  Dim w_chave_nova
  Dim i, j

  Cabecalho
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  BodyOpen "onLoad=document.focus();"
  
  AbreSessao    
  Select Case SG
    Case "PDIDENT"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          Dim w_dias
          
          If Nvl(Request("w_justif_dia_util"),"") = "" and (RetornaExpediente(Request("w_inicio"), w_cliente, null, null, null) = "N" or RetornaExpediente(Request("w_fim"), w_cliente, null, null, null) = "N") Then
             ScriptOpen "JavaScript"
             ShowHTML "  alert('É necessário justificar o início e término de viagens em feriados!');"
             ShowHTML "  history.back(1);"
             ScriptClose
             Response.End()
             Exit Sub
          End If
             
          'ExibeVariaveis
          DML_PutViagemGeral O, w_cliente, _
            Request("w_chave"), Request("w_menu"), Session("lotacao"), Request("w_sq_unidade_resp"), _
            Request("w_sq_prop"), Session("sq_pessoa"), Request("w_tipo_missao"), Request("w_descricao"), _
            Request("w_justif_dia_util"), Request("w_inicio"), Request("w_fim"), Request("w_data_hora"), _
            Request("w_aviso"), Request("w_dias"), Request("w_projeto"), Request("w_tarefa"),  _
            Request("w_cpf"), Request("w_nm_prop"), Request("w_nm_prop_res"), _
            Request("w_sexo"), Request("w_vinculo"), Request("w_inicio_atual"), w_chave_nova, w_copia, w_codigo
          
          ScriptOpen "JavaScript"
          If O = "I" Then
             ' Envia e-mail comunicando a inclusão
             'SolicMail Nvl(Request("w_chave"), w_chave_nova) ,1
             ' Exibe mensagem de gravação com sucesso
             ShowHTML "  alert('" & w_codigo & " cadastrada com sucesso!');"
             
             ' Recupera os dados para montagem correta do menu
             DB_GetMenuData RS1, w_menu
             ShowHTML "  parent.menu.location='../Menu.asp?par=ExibeDocs&O=A&w_chave=" & w_chave_nova & "&w_documento=" & w_codigo & "&R=" & R & "&SG=" & RS1("sigla") & "&TP=" & RemoveTP(TP) & "';"
          ElseIf O = "E" Then
             ShowHTML "  location.href='" & R & "&O=L&R=" & R & "&SG="&Mid(SG,1,2)&"INICIAL&w_menu=" & w_menu & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & MontaFiltro("GET") & "';"
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
    Case "PDOUTRA"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
 
          'ExibeVariaveis
          DML_PutViagemOutra O, SG, _
                Request("w_chave"),              Request("w_chave_aux"),          Request("w_sq_pessoa"), _
                Request("w_cpf"),                Request("w_nome"),               Request("w_nome_resumido"), _
                Request("w_sexo"),               Request("w_sq_tipo_vinculo"),    Request("w_matricula"), _
                Request("w_rg_numero"),          Request("w_rg_emissao"),         Request("w_rg_emissor"), _
                Request("w_ddd"),                Request("w_nr_telefone"),        Request("w_nr_fax"), _
                Request("w_nr_celular"),         Request("w_sq_agencia"),         Request("w_operacao"), _
                Request("w_nr_conta"),           Request("w_sq_pais_estrang"),    Request("w_aba_code"), _
                Request("w_swift_code"),         Request("w_endereco_estrang"),   Request("w_banco_estrang"), _
                Request("w_agencia_estrang"),    Request("w_cidade_estrang"),     Request("w_informacoes"), _
                Request("w_codigo_deposito")
              
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=" & O & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "PDTRECHO"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then

          'ExibeVariaveis
          DML_PutPD_Deslocamento O, _
            Request("w_chave"), Request("w_chave_aux"), _ 
            Request("w_cidade_orig"), Request("w_data_saida"), Request("w_hora_saida"), _
            Request("w_cidade_dest"), Request("w_data_chegada"), Request("w_hora_chegada")
          
          ScriptOpen "JavaScript"
          ' Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link
          DB_GetLinkData RS1, Session("p_cliente"), SG
          ShowHTML "  location.href='" & replace(RS1("link"),w_dir,"") & "&O=L&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "PDVINC"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          'ExibeVariaveis
          If O = "I" Then
             For w_cont = 1 To Request.Form("w_tarefa").Count
                 If Nvl(Request.Form("w_tarefa")(w_cont),"") > "" Then
                    DML_PutPdTarefa O, Request("w_chave"), Request.Form("w_tarefa")(w_cont)
                 End If
             Next
          ElseIf O = "E" Then
             DML_PutPdTarefa O, Request("w_chave_aux"), Request("w_tarefa")
          End If
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_chave=" & Request("w_chave") & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "DADFIN"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
      
          'ExibeVariaveis
          DML_PutPDMissao null, Request("w_chave"), Nvl(Request("w_vlr_alimentacao"),0), Nvl(Request("w_vlr_transporte"),0), Nvl(Request("w_adicional"),0),  _
                          Nvl(Request("w_desc_alimentacao"),0), Nvl(Request("w_desc_trasnporte"),0), null
          
          For i = 1 To Request.Form("w_sq_diaria").Count
             If Request.Form("w_sq_diaria")(i) > "" Then
                DML_PutPDDiaria "A", Request("w_chave"), Request.Form("w_sq_diaria")(i), Request.Form("w_sq_cidade")(i), _
                                Nvl(Request.Form("w_qtd_diarias")(i),0),  Nvl(Request.Form("w_vlr_diarias")(i),0)
             Else
                DML_PutPDDiaria "I", Request("w_chave"), null, Request.Form("w_sq_cidade")(i), _
                                Nvl(Request.Form("w_qtd_diarias")(i),0),  Nvl(Request.Form("w_vlr_diarias")(i),0)
             End If
          Next
              
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & w_pagina & "DadosFinanceiros&O=" & O & "&w_chave=" & Request("w_Chave") & "&w_menu=" & Request("w_menu") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "PDENVIO" 
       ' Verifica se a Assinatura Eletrônica é válida 
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _ 
          w_assinatura = "" Then 
    
          ' Verifica se outro usuário já enviou a solicitação
          DB_GetSolicData RS, Request("w_chave"), "PDINICIAL"
          If cDbl(RS("sq_siw_tramite")) <> cDbl(Request("w_tramite")) Then
             ScriptOpen "JavaScript"
             ShowHTML "  alert('ATENÇÃO: Outro usuário já encaminhou a solicitação para outra fase!');"
             ShowHTML "  history.back(1);"
             ScriptClose
             Response.End()
             Exit Sub
          Else
             ' Verifica o próximo trâmite
             If Request("w_envio") = "N" Then
                DB_GetTramiteList RS, Request("w_tramite"), "PROXIMO"
             Else
                DB_GetTramiteList RS, Request("w_tramite"), "ANTERIOR"
             End If
             DB_GetTramiteSolic RS1, Request("w_chave"), RS("sq_siw_tramite"), null, null
             
             If RS1.recordcount < 1 Then
                ScriptOpen "JavaScript"
                ShowHTML "  alert('ATENÇÃO: Não há nenhuma pessoa habilitada a cumprir o trâmite """ & RS("nome") & """!');"
                ShowHTML "  history.back(1);"
                ScriptClose
                Response.End()
                Exit Sub
             End If
          End If

          DML_PutViagemEnvio Request("w_menu"), Request("w_chave"), w_usuario, Request("w_tramite"), _ 
              Request("w_envio"), Request("w_despacho"), Request("w_justificativa")
             
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
       Else 
          ScriptOpen "JavaScript" 
          ShowHTML "  alert('Assinatura Eletrônica inválida!');" 
          ShowHTML "  history.back(1);" 
          ScriptClose 
       End If 
    Case "GDCONC"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then

          DB_GetSolicData RS, ul.Form("w_chave"), SG
          If cDbl(RS("sq_siw_tramite")) <> cDbl(ul.Form("w_tramite")) Then
             ScriptOpen "JavaScript"
             ShowHTML "  alert('ATENÇÃO: Outro usuário já encaminhou esta PCD para outra fase de execução!');"
             ScriptClose
          Else
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
    
             DML_PutDemandaConc w_menu, ul.Form("w_chave"), w_usuario, ul.Form("w_tramite"), ul.Form("w_inicio_real"), ul.Form("w_fim_real"), ul.Form("w_nota_conclusao"), ul.Form("w_custo_real"), _  
                 w_file, ul.Files("w_caminho").Size, ul.Files("w_caminho").ContentType               
             ' Envia e-mail comunicando a conclusão
             SolicMail ul.form("w_chave") ,3
             
             ScriptOpen "JavaScript"
             ' Volta para a listagem
             DB_GetMenuData RS, w_menu
             ShowHTML "  location.href='" & replace(RS("link"),w_dir,"") & "&O=L&w_chave=" & ul.Form("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & rs("sigla") & MontaFiltroUpload(ul.Form) & "';" 
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

  Set w_chave_nova          = Nothing
  Set w_file                = Nothing
  Set FS                    = Nothing
  Set w_Mensagem            = Nothing
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
    Case "INICIAL"          Inicial
    Case "GERAL"            Geral
    Case "OUTRA"            OutraParte
    Case "TRECHOS"          Trechos
    Case "VINCULACAO"       Vinculacao
    Case "DADOSFINANCEIROS" DadosFinanceiros
    Case "VISUAL"           Visual
    Case "EXCLUIR"          Excluir
    Case "ENVIO"            Encaminhamento
    Case "ANOTACAO"         Anotar
    Case "CONCLUIR"         Concluir
    Case "GRAVA"            Grava
    Case Else
       Cabecalho
       ShowHTML "<BASE HREF=""" & conRootSIW & """>"
       BodyOpen "onLoad=document.focus();"
       ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
       ShowHTML "<HR>"
       ShowHTML "<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src=""images/icone/underc.gif"" align=""center""> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>"
       ExibeVariaveis
       Rodape
  End Select
End Sub
REM =========================================================================
REM Fim da rotina principal
REM -------------------------------------------------------------------------
%>

