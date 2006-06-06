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
<!-- #INCLUDE VIRTUAL="/siw/mod_pd/DB_Viagem.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/mod_rh/DB_Tabelas.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/cp_upload/_upload.asp" -->
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
Dim p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_projeto, p_atividade, p_codigo
Dim p_chave, p_assunto, p_pais, p_uf, p_cidade, p_regiao, p_usu_resp, p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc
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
   p_atividade      = uCase(ul.Texts.Item("p_atividade"))  
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
   p_codigo         = uCase(ul.Texts.Item("p_codigo"))
   p_chave          = uCase(ul.Texts.Item("p_chave"))  
   p_assunto        = uCase(ul.Texts.Item("p_assunto"))  
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
   p_codigo         = uCase(Request("p_codigo"))    
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

Set UploadId      = Nothing
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
Set p_codigo      = Nothing
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
        If p_projeto > ""  Then 
           DB_GetSolicData_IS RS, p_projeto, "ISACGERAL"
           If Nvl(RS("cd_acao"),"") > "" Then
              w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Ação <td><font size=1>[<b><A class=""HL"" HREF=""" & w_dir & "Acao.asp?par=Visual&O=L&w_chave=" & p_projeto & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Exibe as informações da ação."">" & RS("cd_unidade") & "." & RS("cd_programa") & "." & RS("cd_acao") & " - " & RS("nm_ppa") & " (" & RS("ds_unidade") & ")</a></b>]"
           Else
              w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Ação <td><font size=1>[<b><A class=""HL"" HREF=""" & w_dir & "Acao.asp?par=Visual&O=L&w_chave=" & p_projeto & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Exibe as informações da ação."">" & RS("titulo") & "</a></b>]"
           End If
        End If
        If p_atividade > ""  Then 
           DB_GetSolicData_IS RS, p_atividade, "ISTAGERAL"
              w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Tarefa <td><font size=1>[<b><A class=""HL"" HREF=""" & w_dir & "Tarefa.asp?par=Visual&O=L&w_chave=" & p_atividade & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Exibe as informações da tarefa."">" & RS("assunto") &"("& RS("sq_siw_solicitacao") & ")</a></b>]"
        End If                
        If p_codigo      > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>PCD nº <td><font size=1>[<b>" & p_codigo & "</b>]"       End If
        If p_assunto     > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Descrição <td><font size=1>[<b>" & p_assunto & "</b>]"   End If
        If p_solicitante > ""  Then
           DB_GetPersonData RS, w_cliente, p_solicitante, null, null
           w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Responsável <td><font size=1>[<b>" & RS("nome_resumido") & "</b>]"
        End If
        If p_unidade     > ""  Then 
           DB_GetUorgData RS, p_unidade
           w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Unidade proponente <td><font size=1>[<b>" & RS("nome") & "</b>]"
        End If
        If p_proponente  > ""  Then 
           w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Proposto<td><font size=1>[<b>" & p_proponente & "</b>]"
        End If        
        If p_palavra     > ""  Then 
           w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>CPF proposto <td><font size=1>[<b>" & p_palavra & "</b>]"
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
        If p_usu_resp > ""  Then
           DB_GetCiaTrans RS, w_cliente, p_usu_resp, null, null, null, null, null, null, null, null
           w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Companhia de viagem<td><font size=1>[<b>" & RS("nome") & "</b>]"
        End If        
        If p_ativo > ""  Then
           w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Tipo<td><font size=1>[<b>"
           If p_ativo = "I" Then 
              w_filtro = w_filtro & "Inicial"
           ElseIf p_ativo = "P" Then 
              w_filtro = w_filtro & "Prorrogação"
           ElseIf p_ativo = "C" Then
              w_filtro = w_filtro & "Complementação"
           End If
           w_filtro = w_filtro & "</b>]"
        End If                
        If p_ini_i       > ""  Then 
           w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Mês <td><font size=1>[<b>" & p_ini_i & "</b>]"
        End If
        If p_atraso      = "S" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Situação <td><font size=1>[<b>Apenas atrasadas</b>]"                            End If        
        If w_filtro > "" Then w_filtro = "<table border=0><tr valign=""top""><td><font size=1><b>Filtro:</b><td nowrap><font size=1><ul>" & w_filtro & "</ul></tr></table>"                    End If
     End If
     DB_GetLinkData RS, w_cliente, SG
     If w_copia > "" Then ' Se for cópia, aplica o filtro sobre todas as PCDs visíveis pelo usuário
        DB_GetSolicList rs, RS("sq_menu"), w_usuario, SG, 3, _
           p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
           p_unidade, p_prioridade, p_ativo, p_proponente, _
           p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
           p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, p_projeto, p_atividade, p_codigo, Nvl(Request("p_sq_prop"),"")
     Else
        If Nvl(Request("p_agrega"),"") = "GRPDACAO" Then
           DB_GetSolicList_IS RS, RS("sq_menu"), w_usuario, Nvl(Request("p_agrega"),SG), P1, _
             p_ini_i, p_ini_f, null, null, p_atraso, p_solicitante, _
             p_unidade, null,  p_ativo, p_proponente, p_chave, p_assunto, _
             p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, p_uorg_resp, p_palavra, _
             p_prazo, p_fase, p_projeto, p_atividade, null, p_codigo, null, null, w_ano
        ElseIf Nvl(Request("p_agrega"),"") = "GRPDCIAVIAGEM" or Nvl(Request("p_agrega"),"") = "GRPDCIDADE" or Nvl(Request("p_agrega"),"") = "GRPDDATA" Then
           DB_GetSolicViagem rs, RS("sq_menu"), w_usuario, Nvl(Request("p_agrega"),SG), 3, _
             p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
             p_unidade, p_prioridade, p_ativo, p_proponente, _
             p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
             p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, p_projeto, p_atividade, p_codigo, Nvl(Request("p_sq_prop"),"")
        Else
           DB_GetSolicList rs, RS("sq_menu"), w_usuario, Nvl(Request("p_agrega"),SG), P1, _
              p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
              p_unidade, p_prioridade, p_ativo, p_proponente, _
              p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
              p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, p_projeto, p_atividade, p_codigo, Nvl(Request("p_sq_prop"),"")
       End If
     End If
     If p_ordena > "" Then RS.sort = p_ordena Else RS.sort = "ordem, fim, prioridade" End If
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=../" & MontaURL("MESA") & """>" End If
  ShowHTML "<TITLE>" & conSgSistema & " - Listagem de Viagens</TITLE>"
  ScriptOpen "Javascript"
  Modulo
  FormataCPF
  CheckBranco
  FormataData
  ValidateOpen "Validacao"
  If Instr("CP",O) > 0 Then
     If P1 <> 1 or O = "C" Then ' Se não for cadastramento ou se for cópia        
        Validate "p_codigo", "Número da PCD", "", "", "2", "60", "1", "1"
        Validate "p_assunto", "Assunto", "", "", "2", "90", "1", "1"
        Validate "p_proponente", "Proposto", "", "", "2", "60", "1", ""
        Validate "p_palavra", "CPF", "CPF", "", "14", "14", "", "0123456789-."
        Validate "p_ini_i", "Primeira saída", "DATA", "", "10", "10", "", "0123456789/"
        Validate "p_ini_f", "Último retorno", "DATA", "", "10", "10", "", "0123456789/"
        ShowHTML "  if ((theForm.p_ini_i.value != '' && theForm.p_ini_f.value == '') || (theForm.p_ini_i.value == '' && theForm.p_ini_f.value != '')) {"
        ShowHTML "     alert ('Informe ambas as datas ou nenhuma delas!');"
        ShowHTML "     theForm.p_ini_i.focus();"
        ShowHTML "     return false;"
        ShowHTML "  }"
        CompData "p_ini_i", "Primeira saída", "<=", "p_ini_f", "Último retorno"
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
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Nº","codigo_interno") & "</font></td>"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Proposto","nm_prop") & "</font></td>"
    ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Proponente","sg_unidade_resp") & "</font></td>"
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
           ShowHTML "        <td align=""center""><font size=""1"">&nbsp;" & Nvl(FormataDataEdicao(FormatDateTime(RS("inicio_real"),2)),"-") & "</td>"
           ShowHTML "        <td align=""center""><font size=""1"">&nbsp;" & Nvl(FormataDataEdicao(FormatDateTime(RS("fim_real"),2)),"-") & "</td>"
           If P1 > 1 Then
              ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(RS("custo_real"),2) & "&nbsp;</td>"
              w_parcial = w_parcial + cDbl(RS("custo_real"))
              ShowHTML "        <td nowrap><font size=""1"">" & RS("nm_tramite") & "</td>" 
           End If
        Else
           ShowHTML "        <td align=""center""><font size=""1"">&nbsp;" & Nvl(FormataDataEdicao(FormatDateTime(RS("inicio"),2)),"-") & "</td>"
           ShowHTML "        <td align=""center""><font size=""1"">&nbsp;" & Nvl(FormataDataEdicao(FormatDateTime(RS("fim"),2)),"-") & "</td>"
           If P1 > 1 Then 
              ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(RS("valor"),2) & "&nbsp;</td>"
              w_parcial = w_parcial + cDbl(RS("valor"))
              ShowHTML "        <td nowrap><font size=""1"">" & RS("nm_tramite") & "</td>" 
           End If
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
              ElseIf RS("sg_tramite") = "AE" Then
                 ShowHTML "          <A class=""hl"" HREF=""javascript:location.href=this.location.href;"" onClick=""window.open('" & w_pagina & "Emissao&R=" & w_Pagina & par & "&O=L&w_menu=" & w_menu & "&w_chave=" & RS("sq_siw_solicitacao") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "','Financeiro','toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes');"" title=""Emitir autorização e proposta de concessão."">Emitir</A>&nbsp" 
                 ShowHTML "          <A class=""hl"" HREF=""javascript:location.href=this.location.href;"" onClick=""window.open('" & w_pagina & "InformarPassagens&R=" & w_Pagina & par & "&O=I&w_menu=" & w_menu & "&w_chave=" & RS("sq_siw_solicitacao") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - Informar dados das passagens" & "&SG=INFPASS','Passagens','toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes');"" title=""Informar os dados das passagens."">Informar</A>&nbsp" 
              ElseIf RS("sg_tramite") = "EE" Then
                 ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "Anotacao&R=" & w_pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Registra anotações para a pcd, sem enviá-la."">Anotar</A>&nbsp"
                 ShowHTML "          <A class=""hl"" HREF=""javascript:location.href=this.location.href;"" onClick=""window.open('" & w_pagina & "Prestacaocontas&R=" & w_Pagina & par & "&O=L&w_menu=" & w_menu & "&w_chave=" & RS("sq_siw_solicitacao") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "','Financeiro','toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes');"" title=""Emitir relatório para prestacao de contas."">Relatório</A>&nbsp" 
              End If
              ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "envio&R=" & w_pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Envia a PCD para outro responsável."">Enviar</A>&nbsp"
              If RS("sg_tramite") = "EE" Then
                 ShowHTML "          <A class=""HL"" HREF=""" & w_dir & w_pagina & "Concluir&R=" & w_pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Conclui a execução da pcd."">Concluir</A>&nbsp"
              End If
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

    ShowHTML "      <tr><td valign=""top"" colspan=""2"">"
    ShowHTML "        <table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "          <tr>"     
    SelecaoAcao "Açã<u>o</u>:", "O", "Selecione a ação da tarefa na relação.", w_cliente, w_ano, null, null, null, null, "p_projeto", "ACAO", "onchange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_atividade'; document.Form.target=''; document.Form.submit();""", p_projeto
    ShowHTML "          <tr>"
    SelecaoTarefa "<u>T</u>arefa:", "T", null, w_cliente, w_ano, p_atividade, "p_atividade", Nvl(p_projeto,0), null
    ShowHTML "          </tr>"
    ShowHTML "        </table></td></tr>"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    If P1 <> 1 or O = "C" Then ' Se não for cadastramento ou se for cópia
       ShowHTML "   <tr valign=""top"">"
       ShowHTML "     <td valign=""top""><font size=""1""><b>Número da P<U>C</U>D:<br><INPUT ACCESSKEY=""C"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_codigo"" size=""20"" maxlength=""60"" value=""" & p_codigo & """></td>"
       ShowHTML "     <td valign=""top""><font size=""1""><b><U>D</U>escrição:<br><INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_assunto"" size=""25"" maxlength=""90"" value=""" & p_assunto & """></td>"       
       ShowHTML "   <tr valign=""top"">"
       SelecaoPessoa "Respo<u>n</u>sável:", "N", "Selecione o responsável pela PCD na relação.", p_solicitante, null, "p_solicitante", "USUARIOS"
       SelecaoUnidade1 "<U>U</U>nidade proponente:", "U", "Selecione a unidade proponente da PCD", p_unidade, null, "p_unidade", "VIAGEMANO", null, w_ano
       ShowHTML "   <tr>"       
       ShowHTML "     <td valign=""top""><font size=""1""><b><U>P</U>roposto:<br><INPUT ACCESSKEY=""P"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_proponente"" size=""25"" maxlength=""60"" value=""" & p_proponente & """></td>"
       ShowHTML "     <td valign=""top""><font size=""1""><b>CP<u>F</u> do proposto:<br><INPUT ACCESSKEY=""F"" TYPE=""text"" class=""sti"" NAME=""p_palavra"" VALUE=""" & p_palavra & """ SIZE=""14"" MaxLength=""14"" onKeyDown=""FormataCPF(this, event);"">"       
       ShowHTML "   <tr>"
       SelecaoPais "Pa<u>í</u>s destino:", "I", null, p_pais, null, "p_pais", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_regiao'; document.Form.submit();"""
       SelecaoRegiao "<u>R</u>egião destino:", "R", null, p_regiao, p_pais, "p_regiao", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_uf'; document.Form.submit();"""
       ShowHTML "   <tr>"
       SelecaoEstado "E<u>s</u>tado destino:", "S", null, p_uf, p_pais, "N", "p_uf", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_cidade'; document.Form.submit();"""
       SelecaoCidade "<u>C</u>idade destino:", "C", null, p_cidade, p_pais, p_uf, "p_cidade", null, null
       ShowHTML "   <tr>"
       SelecaoTipoPCD "Ti<u>p</u>o:", "P", null, p_ativo, "p_ativo", null, null
       SelecaoCiaTrans "Cia. Via<u>g</u>em", "R", "Selecione a companhia de transporte desejada.", w_cliente,  p_usu_resp, null, "p_usu_resp", "S", null
       ShowHTML "   <tr>"
       ShowHTML "     <td valign=""top""><font size=""1""><b>Pri<u>m</u>eira saída e Último retorno:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""p_ini_i"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_ini_i & """ onKeyDown=""FormataData(this,event);"" title=""Usar formato dd/mm/aaaa""> e <input " & w_Disabled & " accesskey=""C"" type=""text"" name=""p_ini_f"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_ini_f & """ onKeyDown=""FormataData(this,event);"" title=""Usar formato dd/mm/aaaa""></td>"
       If O <> "C" Then ' Se não for cópia
          ShowHTML "<tr>"
          SelecaoFaseCheck "Recuperar fases:", "S", null, p_fase, P2, "p_fase", null, null
       End If
    End If
    ShowHTML "      <tr>"
    ShowHTML "        <td valign=""top""><font size=""1""><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""STI"" type=""text"" name=""P4"" size=""4"" maxlength=""4"" value=""" & P4 & """></td></tr>"
    ShowHTML "    </table>"
    ShowHTML "    <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "    <tr><td align=""center"" colspan=""3"">"
    ShowHTML "          <input class=""STB"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
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

  ' Se não puder cadastrar para outros, carrega os dados do usuário logado
  If w_cadgeral = "N" Then
     DB_GetBenef RS, w_cliente, null, Session("USERNAME"), null, null, 1, null, null
     If RS.RecordCount > 0 Then 
        w_cpf                 = RS("cpf")
        w_sq_prop             = RS("sq_pessoa")
        w_nm_prop             = RS("nm_pessoa")
        w_nm_prop_res         = RS("nome_resumido")
        w_sexo                = RS("sexo")
        w_vinculo             = RS("sq_tipo_vinculo")
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
     If w_cadgeral = "S" Then
        Validate "w_sq_unidade_resp", "Unidade proponente", "SELECT", 1, 1, 18, "", "0123456789"
     End If
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
     If O = "I" Then
        If w_cadgeral = "S" Then
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
        Else
           If w_sexo = "" Then
              Validate "w_sexo", "Sexo", "SELECT", 1, 1, 1, "MF", ""
           End If
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
    If w_sq_unidade_resp = "" Then
       ' Recupera todos os registros para a listagem
       DB_GetUorgList RS, w_cliente, Session("LOTACAO"), "VIAGEMUNID", null, null, w_ano
       If Not RS.EOF Then
          w_sq_unidade_resp = RS("sq_unidade")
          If w_cadgeral = "N" Then
             ShowHTML "<INPUT type=""hidden"" name=""w_sq_unidade_resp"" value=""" & w_sq_unidade_resp &""">"
          Else
             SelecaoUnidade1 "<U>U</U>nidade proponente:", "U", "Selecione a unidade proponente da PCD", w_sq_unidade_resp, null, "w_sq_unidade_resp", "VIAGEMANO", null, w_ano
          End if
       Else
          If w_cadgeral = "N" Then
             ScriptOpen "JavaScript"
             ShowHTML "  alert('ATENÇÃO: Sua lotação não está ligada a nenhuma unidade proponente. Entre em contato com os gestores do sistema!');"
             ShowHTML "  history.back(1);"
             ScriptClose
          Else
             SelecaoUnidade1 "<U>U</U>nidade proponente:", "U", "Selecione a unidade proponente da PCD", w_sq_unidade_resp, null, "w_sq_unidade_resp", "VIAGEMANO", null, w_ano
          End if
       End If
    Else
       If w_cadgeral = "N" Then
          ShowHTML "<INPUT type=""hidden"" name=""w_sq_unidade_resp"" value=""" & w_sq_unidade_resp &""">"
       Else
          SelecaoUnidade1 "<U>U</U>nidade proponente:", "U", "Selecione a unidade proponente da PCD", w_sq_unidade_resp, null, "w_sq_unidade_resp", "VIAGEMANO", null, w_ano
       End if
    End If
    SelecaoTipoPCD "Ti<u>p</u>o:", "P", null, w_tipo_missao, "w_tipo_missao", null, null
    ShowHTML "              <td valign=""top""><font size=""1""><b>Pri<u>m</u>eira saída:</b><br><input " & w_Disabled & " accesskey=""M"" type=""text"" name=""w_inicio"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_inicio & """ onKeyDown=""FormataData(this,event);"" title=""Usar formato dd/mm/aaaa"">" & ExibeCalendario("Form", "w_inicio") & "</td>"
    ShowHTML "              <td valign=""top""><font size=""1""><b>Último re<u>t</u>orno:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_fim"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_fim & """ onKeyDown=""FormataData(this,event);"" title=""Usar formato dd/mm/aaaa"">" & ExibeCalendario("Form", "w_fim") & "</td>"
    ShowHTML "          </table>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><u>J</u>ustificativa para início e término de viagens em sextas-feiras, sábados, domingos e feriados:</b><br><textarea " & w_Disabled & " accesskey=""J"" name=""w_justif_dia_util"" class=""STI"" ROWS=5 cols=75 title=""É obrigatório justificar, neste campo, início ou término de viagens sextas-feiras, sábados, domingos e feriados. Caso contrário, deixe este campo em branco."">" & w_justif_dia_util & "</TEXTAREA></td>"
    If O = "I" Then
       If w_cadgeral = "S" Then
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
                SelecaoVinculo "Tipo de <u>v</u>ínculo:", "V", null, w_vinculo, null, "w_vinculo", "S", "Física", null
             Else
                ShowHTML "<INPUT type=""hidden"" name=""w_vinculo"" value=""" & w_vinculo &""">"
             End If
          Else
             ShowHTML "            <td><font size=""1""><b><u>N</u>ome completo:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nm_prop"" class=""sti"" SIZE=""45"" MAXLENGTH=""60"" VALUE=""" & w_nm_prop & """></td>"
             ShowHTML "            <td><font size=""1""><b><u>N</u>ome resumido:</b><br><input " & w_Disabled & " accesskey=""N"" type=""text"" name=""w_nm_prop_res"" class=""sti"" SIZE=""15"" MAXLENGTH=""15"" VALUE=""" & w_nm_prop_res & """></td>"
             SelecaoSexo "Se<u>x</u>o:", "X", null, w_sexo, null, "w_sexo", null, null
             SelecaoVinculo "Tipo de <u>v</u>ínculo:", "V", null, w_vinculo, null, "w_vinculo", "S", "Física", null
          End If
          ShowHTML "          </table>"
       Else
          If w_sexo = "N" Then
             ShowHTML "<INPUT type=""hidden"" name=""w_cpf"" value=""" & w_cpf &""">"
             ShowHTML "<INPUT type=""hidden"" name=""w_nm_prop"" value=""" & w_nm_prop &""">"
             ShowHTML "<INPUT type=""hidden"" name=""w_nm_prop_res"" value=""" & w_nm_prop_res &""">"
             ShowHTML "<INPUT type=""hidden"" name=""w_vinculo"" value=""" & w_vinculo &""">"
             ShowHTML "      <tr><td align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
             ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
             ShowHTML "      <tr><td valign=""top"" align=""center"" bgcolor=""#D0D0D0""><font size=""1""><b>Dados do Proposto</td></td></tr>"
             ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
             ShowHTML "      <tr><td><font size=1>Confirme os dados abaixo, informando ou alterando o sexo, se necessário. Após a gravação serão solicitados dados complementares sobre ele.</font></td></tr>"
             ShowHTML "      <tr><td align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
             ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
             ShowHTML "        <tr valign=""top"">"
             ShowHTML "            <td>CPF:<b><br><font size=""2"">" & w_cpf & "</b></td>"
             ShowHTML "            <td>Nome completo:<b><br><font size=""2"">" & w_nm_prop & "</td>"
             ShowHTML "            <td>Nome resumido:<b><br><font size=""2"">" & w_nm_prop_res & "</td>"
             SelecaoSexo "Se<u>x</u>o:", "X", null, w_sexo, null, "w_sexo", null, null
             ShowHTML "          </table>"
          Else
             ShowHTML "<INPUT type=""hidden"" name=""w_sexo"" value=""" & w_sexo &""">"
          End If
       End If
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
     If w_cadgeral = "S" Then
        ShowHTML "  theForm.Botao[0].disabled=true;"
        ShowHTML "  theForm.Botao[1].disabled=true;"
     Else
        ShowHTML "  theForm.Botao.disabled=true;"
     End If
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
          SelecaoVinculo "Tipo de <u>v</u>ínculo:", "V", null, w_sq_tipo_vinculo, null, "w_sq_tipo_vinculo", "S", "Física", null
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
       If w_cadgeral = "S" Then
          ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Alterar proposto"" onClick=""Botao.value=this.value; document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.submit();"">"
       End If
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
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=5 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
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
    ShowHTML "              <input class=""stb"" type=""button"" onClick=""location.href='" & R & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&w_chave=" & w_chave & "&O=L';"" name=""Botao"" value=""Cancelar"">"
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
        null, null, null, null, null, null, null, null, null, null, w_chave, _
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
               ShowHTML "        <td align=""center""><font size=""1"">" & FormataDataEdicao(RS("inicio_real")) & "</td>"
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
       ShowHTML "<INPUT type=""hidden"" name=""w_maximo_diarias"" value=""" & (cInt(DateDiff("d",FormatDateTime(w_vetor_trechos(i,9),2),FormatDateTime(Nvl(w_vetor_trechos(i+1,8),w_vetor_trechos(i,9)),2))) + cInt(1)) &""">"
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
     ShowHTML "<center><B>Clique <a class=""HL"" href=""javascript:history.back(1);"">aqui</a> para voltar à tela anterior</b></center>"
  End If

  ' Chama a rotina de visualização dos dados da PCD, na opção "Listagem"
  ShowHTML VisualViagem(w_chave, "L", w_usuario, P1, P4)

  If w_tipo > "" and w_tipo <> "WORD" Then
     ShowHTML "<center><B>Clique <a class=""HL"" href=""javascript:history.back(1);"">aqui</a> para voltar à tela anterior</b></center>"
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
     w_tramite       = Request("w_tramite")
     w_destinatario  = Request("w_destinatario")
     w_envio         = Request("w_envio")
     w_despacho      = Request("w_despacho")
     w_justificativa = Request("w_justificativa")
  Else
     DB_GetSolicData RS, w_chave, SG
     w_inicio        = RS("inicio")
     w_tramite       = RS("sq_siw_tramite")
     w_justificativa = RS("justificativa")
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
        If Nvl(Mid(w_erro,1,1),"") = "0" or w_sg_tramite = "EE" Then
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
           If Nvl(Mid(w_erro,1,1),"") = "1" or Nvl(Mid(w_erro,1,1),"") = "2" Then
              If (w_inicio - w_prazo) < Date() Then
                Validate "w_justificativa", "Justificativa", "", "", "1", "2000", "1", "1"
                ShowHTML "if (theForm.w_envio[0].checked && theForm.w_justificativa.value == '') {"
                ShowHTML "     alert('Informe uma justificativa para o não cumprimento do prazo regulamentar!');"
                ShowHTML "     theForm.w_justificativa.focus();"
                ShowHTML "     return false;"
                ShowHTML "}"
             End If
           End If           
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
  If Nvl(w_erro,"") = "" or w_sg_tramite = "EE" or (nvl(mid(w_erro,1,1),"0")="2" and w_sg_tramite = "CI") or (Nvl(w_erro,"") > "" and RetornaGestor(w_chave, w_usuario) = "S") Then
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
        If Nvl(Mid(w_erro,1,1),"") = "0" or w_sg_tramite = "EE" Then
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
        If Not (Nvl(Mid(w_erro,1,1),"") = "0" or w_sg_tramite = "EE") Then
           If Nvl(Mid(w_erro,1,1),"") = "1" or Nvl(Mid(w_erro,1,1),"") = "2" Then
              If (w_inicio - w_prazo) < Date() Then
                 ShowHTML "    <tr><td><b><u>J</u>ustificativa para não cumprimento do prazo regulamentar de " & w_prazo & " dias:</b><br><textarea " & w_Disabled & " accesskey=""J"" name=""w_justificativa"" class=""STI"" ROWS=5 cols=75 title=""Se o início da viagem for anterior a " & FormataDataEdicao(FormatDateTime(Date()+w_prazo,2)) & ", justifique o motivo do não cumprimento do prazo regulamentar para o pedido."">" & w_justificativa & "</TEXTAREA></td>"
              End If
           End If
        End If
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
     ProgressBar w_dir_volta, UploadID         
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
     ShowHTML "if (theForm.w_caminho.value != '') {return ProgressBar();}"          
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
  ShowHTML VisualViagem(w_chave, "L", w_usuario, P1, P4)

  ShowHTML "<HR>"
  ShowHTML "<FORM action=""" & w_dir & w_pagina & "Grava&SG=PDENVIO&O="&O&"&w_menu="&w_menu&"&UploadID="&UploadID&""" name=""Form"" onSubmit=""return(Validacao(this));"" enctype=""multipart/form-data"" method=""POST"">"
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
  
  'Recupera a data da primeira saída
  DB_GetPD_Deslocamento RS, w_chave, null, "DADFIN"
  RS.Sort = "saida, chegada"
  If Not RS.EOF Then
     w_inicio_real = RS("saida")
     While Not RS.EOF
        w_custo_real = w_custo_real + (cDbl(FormatNumber(Nvl(RS("quantidade"),0),1)) * cDbl(FormatNumber(Nvl(RS("valor"),0),2)))
        w_fim_real = RS("chegada")
        RS.MoveNext
     wend
  End If
  DesconectaBD  
  
  'Recupera os dados da solicitacao de passagens e diárias
  DB_GetSolicData RS, w_chave, Mid(SG,1,3) & "GERAL"
  w_custo_real = w_custo_real + cDbl(FormatNumber(Nvl(RS("valor_passagem"),0),2)) + cDbl(FormatNumber(Nvl(RS("valor_adicional"),0),2)) + cDbl(FormatNumber(Nvl(RS("valor_alimentacao"),0),2)) + cDbl(FormatNumber(Nvl(RS("valor_transporte"),0),2)) - cDbl(FormatNumber(Nvl(RS("desconto_alimentacao"),0),2)) - cDbl(FormatNumber(Nvl(RS("desconto_transporte"),0),2))
  DesconectaBD
  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=../" & MontaURL("MESA") & """>"
  If InStr("V",O) > 0 Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"

  ' Chama a rotina de visualização dos dados da PCD, na opção "Listagem"
 ShowHTML VisualViagem(w_chave, "L", w_usuario, P1, P4)

  ShowHTML "<HR>"
  ShowHTML "<FORM action=""" & w_dir & w_pagina & "Grava&SG=PDCONC&O="&O&"&w_menu="&w_menu&""" name=""Form"" onSubmit=""return(Validacao(this));"" method=""POST"">"  
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
  ShowHTML "<INPUT type=""hidden"" name=""w_inicio_real"" value=""" & w_inicio_real & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_fim_real"" value=""" & w_fim_real & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_custo_real"" value=""" & w_custo_real & """>"
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "  <table width=""100%"" border=""0"">"
  DB_GetCustomerData RS, w_cliente 
  w_Disabled = "READONLY"
  ShowHTML "      <tr><td valign=""top""><table border=1 width=""100%"" cellspacing=0 bgcolor=""" & conTableBgColor & """>"
  ShowHTML "          <tr bgcolor=""" & conTrAlternateBgColor & """>"
  ShowHTML "              <td align=""center""><font size=""1""><b>Primeira saída</b></td>"
  ShowHTML "              <td align=""center""><font size=""1""><b>Último retorno</b></td>"
  ShowHTML "              <td align=""center""><font size=""1""><b>Custo total</b></td>"
  ShowHTML "          </tr>"
  ShowHTML "          <tr>"
  ShowHTML "              <td align=""center""><font size=""1"">" & FormataDataEdicao(w_inicio_real) & "</td>"
  ShowHTML "              <td align=""center""><font size=""1"">" & FormataDataEdicao(w_fim_real) & "</td>"
  ShowHTML "              <td align=""right""><font size=""1"">" & FormatNumber(w_custo_real,2) & "</td>"
  ShowHTML "          </tr>"
  ShowHTML "          </table>"
  ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Nota d<u>e</u> conclusão:</b><br><textarea " & w_Disabled & " accesskey=""E"" name=""w_nota_conclusao"" class=""STI"" ROWS=5 cols=75>Conferi a documentação necessária para prestação de contas desta PCD.</TEXTAREA></td>"
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
REM Rotina de emissão da autorização e da proposta de concessão de passagens e diárias
REM -------------------------------------------------------------------------
Sub Emissao

  Dim w_chave, w_logo, w_primeira_saida, w_prazo, w_total
  
  w_chave = Request("w_chave")
  
  'Recupera a data da primeira saída
  DB_GetPD_Deslocamento RS, w_chave, null, "PDGERAL"
  RS.Sort = "saida, chegada"
  w_primeira_saida = RS("saida")
  DesconectaBD
  
  ' Recupera os parâmetros do módulo de viagem
  DB_GetPDParametro RS, w_cliente, null, null
  w_prazo        = cDbl(RS("dias_antecedencia"))
  DesconectaBD
  
  'Recupera os dados da solicitacao de passagens e diárias
  DB_GetSolicData RS, w_chave, Mid(SG,1,3) & "GERAL"
  
  'Recupera os dados do proposto
  DB_GetBenef RS1, w_cliente, Nvl(RS("sq_prop"),0), null, null, null, 1, null, null

  Response.AddHeader "Content-Disposition", "attachment; filename=Emissão"&w_chave&".doc"
  Response.ContentType = "application/msword"
   
  ShowHTML "{\rtf1\ansi\ansicpg1252\uc1\deff0\stshfdbch0\stshfloch0\stshfhich0\stshfbi0\deflang1033\deflangfe1033{\fonttbl{\f0\froman\fcharset0\fprq2{\*\panose 02020603050405020304}Times New Roman;}{\f1\fswiss\fcharset0\fprq2{\*\panose 020b0604020202020204}Arial;}"
  ShowHTML "{\f36\fswiss\fcharset0\fprq2{\*\panose 020b0506020202030204}Arial Narrow;}{\f129\froman\fcharset238\fprq2 Times New Roman CE;}{\f130\froman\fcharset204\fprq2 Times New Roman Cyr;}{\f132\froman\fcharset161\fprq2 Times New Roman Greek;}"
  ShowHTML "{\f133\froman\fcharset162\fprq2 Times New Roman Tur;}{\f134\froman\fcharset177\fprq2 Times New Roman (Hebrew);}{\f135\froman\fcharset178\fprq2 Times New Roman (Arabic);}{\f136\froman\fcharset186\fprq2 Times New Roman Baltic;}"
  ShowHTML "{\f137\froman\fcharset163\fprq2 Times New Roman (Vietnamese);}{\f139\fswiss\fcharset238\fprq2 Arial CE;}{\f140\fswiss\fcharset204\fprq2 Arial Cyr;}{\f142\fswiss\fcharset161\fprq2 Arial Greek;}{\f143\fswiss\fcharset162\fprq2 Arial Tur;}"
  ShowHTML "{\f144\fswiss\fcharset177\fprq2 Arial (Hebrew);}{\f145\fswiss\fcharset178\fprq2 Arial (Arabic);}{\f146\fswiss\fcharset186\fprq2 Arial Baltic;}{\f147\fswiss\fcharset163\fprq2 Arial (Vietnamese);}{\f489\fswiss\fcharset238\fprq2 Arial Narrow CE;}"
  ShowHTML "{\f490\fswiss\fcharset204\fprq2 Arial Narrow Cyr;}{\f492\fswiss\fcharset161\fprq2 Arial Narrow Greek;}{\f493\fswiss\fcharset162\fprq2 Arial Narrow Tur;}{\f496\fswiss\fcharset186\fprq2 Arial Narrow Baltic;}}{\colortbl;\red0\green0\blue0;"
  ShowHTML "\red0\green0\blue255;\red0\green255\blue255;\red0\green255\blue0;\red255\green0\blue255;\red255\green0\blue0;\red255\green255\blue0;\red255\green255\blue255;\red0\green0\blue128;\red0\green128\blue128;\red0\green128\blue0;\red128\green0\blue128;"
  ShowHTML "\red128\green0\blue0;\red128\green128\blue0;\red128\green128\blue128;\red192\green192\blue192;\red255\green255\blue255;}{\stylesheet{\ql \li0\ri0\widctlpar\aspalpha\aspnum\faauto\adjustright\rin0\lin0\itap0 "
  ShowHTML "\fs24\lang1046\langfe1046\cgrid\langnp1046\langfenp1046 \snext0 \styrsid9184405 Normal;}{\s1\qc \fi708\li0\ri0\keepn\widctlpar\aspalpha\aspnum\faauto\outlinelevel0\adjustright\rin0\lin0\itap0 \b\f1\fs24\lang1046\langfe1046\cgrid\langnp1046\langfenp1046 "
  ShowHTML "\sbasedon0 \snext0 \styrsid9184405 heading 1;}{\s2\ql \li0\ri0\keepn\widctlpar\aspalpha\aspnum\faauto\outlinelevel1\adjustright\rin0\lin0\itap0 \b\f1\fs22\lang1046\langfe1046\cgrid\langnp1046\langfenp1046 \sbasedon0 \snext0 \styrsid9184405 heading 2;}{\*"
  ShowHTML "\cs10 \additive \ssemihidden Default Paragraph Font;}{\*\ts11\tsrowd\trftsWidthB3\trpaddl108\trpaddr108\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3\tscellwidthfts0\tsvertalt\tsbrdrt\tsbrdrl\tsbrdrb\tsbrdrr\tsbrdrdgl\tsbrdrdgr\tsbrdrh\tsbrdrv "
  ShowHTML "\ql \li0\ri0\widctlpar\aspalpha\aspnum\faauto\adjustright\rin0\lin0\itap0 \fs20\lang1024\langfe1024\cgrid\langnp1024\langfenp1024 \snext11 \ssemihidden Normal Table;}{\s15\ql \li0\ri0\widctlpar"
  ShowHTML "\tqc\tx4419\tqr\tx8838\aspalpha\aspnum\faauto\adjustright\rin0\lin0\itap0 \fs20\lang1046\langfe1046\cgrid\langnp1046\langfenp1046 \sbasedon0 \snext15 \styrsid9184405 header;}{"
  ShowHTML "\s16\qj \fi708\li0\ri0\widctlpar\aspalpha\aspnum\faauto\adjustright\rin0\lin0\itap0 \f1\fs24\lang1046\langfe1046\cgrid\langnp1046\langfenp1046 \sbasedon0 \snext16 \styrsid9184405 Body Text Indent;}}{\*\latentstyles\lsdstimax156\lsdlockeddef0}"
  ShowHTML "{\*\rsidtbl \rsid1071686\rsid3545814\rsid8462233\rsid9184405\rsid10884206\rsid12078955\rsid12326642\rsid14038699}{\*\generator Microsoft Word 11.0.5604;}{\info{\title  }{\author Suporte T\'e9cnico}{\operator Suporte T\'e9cnico}"
  ShowHTML "{\creatim\yr2006\mo5\dy10\hr14\min6}{\revtim\yr2006\mo5\dy10\hr14\min6}{\version2}{\edmins0}{\nofpages2}{\nofwords644}{\nofchars3677}{\*\company SBPI Consultoria}{\nofcharsws4313}{\vern24689}}\margl1701\margr1797\margt454\margb567 "
  ShowHTML "\widowctrl\ftnbj\aenddoc\noxlattoyen\expshrtn\noultrlspc\dntblnsbdb\nospaceforul\formshade\horzdoc\dgmargin\dghspace180\dgvspace180\dghorigin1701\dgvorigin454\dghshow1\dgvshow1"
  ShowHTML "\jexpand\viewkind1\viewscale75\pgbrdrhead\pgbrdrfoot\splytwnine\ftnlytwnine\htmautsp\nolnhtadjtbl\useltbaln\alntblind\lytcalctblwd\lyttblrtgr\lnbrkrule\nobrkwrptbl\snaptogridincell\allowfieldendsel\wrppunct\asianbrkrule\nojkernpunct\rsidroot9184405 \fet0"
  ShowHTML "\sectd \linex0\headery709\footery709\colsx708\endnhere\sectlinegrid360\sectdefaultcl\sectrsid12326642\sftnbj {\*\pnseclvl1\pnucrm\pnstart1\pnindent720\pnhang {\pntxta .}}{\*\pnseclvl2\pnucltr\pnstart1\pnindent720\pnhang {\pntxta .}}{\*\pnseclvl3"
  ShowHTML "\pndec\pnstart1\pnindent720\pnhang {\pntxta .}}{\*\pnseclvl4\pnlcltr\pnstart1\pnindent720\pnhang {\pntxta )}}{\*\pnseclvl5\pndec\pnstart1\pnindent720\pnhang {\pntxtb (}{\pntxta )}}{\*\pnseclvl6\pnlcltr\pnstart1\pnindent720\pnhang {\pntxtb (}{\pntxta )}}"
  ShowHTML "{\*\pnseclvl7\pnlcrm\pnstart1\pnindent720\pnhang {\pntxtb (}{\pntxta )}}{\*\pnseclvl8\pnlcltr\pnstart1\pnindent720\pnhang {\pntxtb (}{\pntxta )}}{\*\pnseclvl9\pnlcrm\pnstart1\pnindent720\pnhang {\pntxtb (}{\pntxta )}}\pard\plain \s15\ql \li0\ri0\widctlpar"
  ShowHTML "\tqc\tx4419\tqr\tx8838\aspalpha\aspnum\faauto\adjustright\rin0\lin0\itap0\pararsid3545814 \fs20\lang1046\langfe1046\cgrid\langnp1046\langfenp1046 {\lang1024\langfe1024\noproof\insrsid9184405 "
  ShowHTML "\par }\pard\plain \ql \li0\ri0\widctlpar\pvpara\phpg\posx5615\posy210\dxfrtext141\dfrmtxtx141\dfrmtxty0\nowrap\aspalpha\aspnum\faauto\adjustright\rin0\lin0\itap0\pararsid9184405 \fs24\lang1046\langfe1046\cgrid\langnp1046\langfenp1046 {"
  ShowHTML "\lang1024\langfe1024\noproof\insrsid9184405 {\*\shppict{\pict{\*\picprop\shplid1025{\sp{\sn shapeType}{\sv 75}}{\sp{\sn fFlipH}{\sv 0}}{\sp{\sn fFlipV}{\sv 0}}{\sp{\sn fillColor}{\sv 268435473}}{\sp{\sn fFilled}{\sv 0}}"
  ShowHTML "{\sp{\sn fLine}{\sv 0}}{\sp{\sn fLayoutInCell}{\sv 1}}}\picscalex100\picscaley100\piccropl0\piccropr0\piccropt0\piccropb0\picw1958\pich2170\picwgoal1110\pichgoal1230\pngblip\bliptag-1973820408{\*\blipuid 8a59e408278a8b7c4ae763e2dfaf7202}"
  ShowHTML "89504e470d0a1a0a0000000d494844520000004a000000520803000000c48bb2b50000000467414d410000b1889598f4a6000002be504c544500000000003300"
  ShowHTML "00660000990000cc0000ff3300003300333300663300993300cc3300ff6600006600336600666600996600cc6600ff9900009900339900669900999900cc9900"
  ShowHTML "ffcc0000cc0033cc0066cc0099cc00cccc00ffff0000ff0033ff0066ff0099ff00ccff00ff0d0d0d1a1a1a2828280033000033330033660033990033cc0033ff"
  ShowHTML "3333003333333333663333993333cc3333ff6633006633336633666633996633cc6633ff9933009933339933669933999933cc9933ffcc3300cc3333cc3366cc"
  ShowHTML "3399cc33cccc33ffff3300ff3333ff3366ff3399ff33ccff33ff3535354343435050505d5d5d0066000066330066660066990066cc0066ff3366003366333366"
  ShowHTML "663366993366cc3366ff6666006666336666666666996666cc6666ff9966009966339966669966999966cc9966ffcc6600cc6633cc6666cc6699cc66cccc66ff"
  ShowHTML "ff6600ff6633ff6666ff6699ff66ccff66ff6b6b6b7878788686869393930099000099330099660099990099cc0099ff3399003399333399663399993399cc33"
  ShowHTML "99ff6699006699336699666699996699cc6699ff9999009999339999669999999999cc9999ffcc9900cc9933cc9966cc9999cc99cccc99ffff9900ff9933ff99"
  ShowHTML "66ff9999ff99ccff99ffa1a1a1aeaeaebbbbbbc9c9c900cc0000cc3300cc6600cc9900cccc00ccff33cc0033cc3333cc6633cc9933cccc33ccff66cc0066cc33"
  ShowHTML "66cc6666cc9966cccc66ccff99cc0099cc3399cc6699cc9999cccc99ccffcccc00cccc33cccc66cccc99ccccccccccffffcc00ffcc33ffcc66ffcc99ffccccff"
  ShowHTML "ccffd6d6d6e4e4e4f1f1f100ff0000ff3300ff6600ff9900ffcc00ffff33ff0033ff3333ff6633ff9933ffcc33ffff66ff0066ff3366ff6666ff9966ffcc66ff"
  ShowHTML "ff99ff0099ff3399ff6699ff9999ffcc99ffffccff00ccff33ccff66ccff99ccffccccffffffff00ffff33ffff66ffff99ffffccffffff43f3a7ba0000000970"
  ShowHTML "48597300000ec300000ec301c76fa86400000c7049444154789ca558cb6edb4816cdca2baff213b20394b460365ce913664ca05865545140c90db037ca074c6f"
  ShowHTML "62b423838f465106d21fd233025fe043bfd26d8be45fcc294a8e9db49deec198b024d3d4e1a973cf7d14df0cdfffe99e3eedfee2d237dfff77df74fbc307bc77"
  ShowHTML "dfbff6fb507dddd57db31bd2a66b7134ff0754d735fb5d1755ba6bfbb6eb1f69957f1faa68eadebce7fb66dff4b9d29532acfa763cdb77f1df87eafabeee3be8"
  ShowHTML "d40f6ddf69fda35fe133cee0847939eaf777a0767b68039d7270293a5ff9bedf40a9aeddb74d3da45df762005e6655e0ce664561dd75b9b6fdb90fb53eeb2ed1"
  ShowHTML "756ff4effabf09b52f463691a0562e3235f74938affc2ebcb20a2bf3fda4817e7f0105e2bd5101f70d8b2e14425295b47a25c2cad749d337bdb8d189d2aaeb6e"
  ShowHTML "f4f7a1ca620f1775bbaec9c53ea78252410a55d9823c04bec69148b25cce3734d9d3a0e971dd57b17c0ed5effb7d8be847821814290415aa9ae3b57ef0e755b3"
  ShowHTML "a96da502b221c2fa99c271b8bc7c050a1142ccfa460860e11044b8a9bf02ea43edfb3aebdbcda62640266422446322f31a2bf8b96f712f2aa961159070b3d7be"
  ShowHTML "2fa8bc7f08567ed6f43ab1c34d4424162f365db7ef5f630537eff6ad5060430958e54d6f0109ec1eaa7ba8a59ba2e9eb9da666fd54e47db37f55abbea9bb8628"
  ShowHTML "b3364a71ef26f9ec2bf3bdfa016ad51a2e405c2971718e126532e01528282e72619dd896e144ad02d9e78fda8055952878ab2d812426464b2155b17f050a3e88"
  ShowHTML "289544d8751346267b43ed0ba39bacaafae161e5d71d34888c8e882fb923b23189f92d54671c181b8d84504a6dc0b0ed949ee3ee4a2799d63a9dfb7e8bf80ed2"
  ShowHTML "2061fd4b41555bb75d7d54ec00d5c36d79d3809185efc20b7503e7b7a9f1915c7fe48caf3d9df82a00aba6be91ee5da8086453500bccca67505be47c41f2f16e"
  ShowHTML "648c5fdee4a1528cadcb92f3983b8b72cb1757816a704f136155c813dc75ac16e5570b84cb039186667960262d658741a0f8fa9aefca6bbe64ebb8c7e7db24b1"
  ShowHTML "125fd3a5b0ac6423c48d801ffac7301ea14ad40162138b9c12cb523a4dab545bfcae64dcf544b8cc85eb7a7c5d2ebc4a5769a5b5651165626932b1dd7e05d569"
  ShowHTML "a8ab1128fdd9f7519f56bee6b7ceedc20bc219e797ce355f44e174cdaf39aa8cbf9a2304fee1fa54ebcdd711c4bdd24a0165fcd14a89355b301a30c773b8f313"
  ShowHTML "e70e0f33c279e4694b58b8cc80d9caaefc2afbc60c5582ff1a46bfeabaaaefb582488e949c5fb7bf5f5cf36b0707dbb0ed8e5f65d4c458da0adc57fe87aaf91a"
  ShowHTML "aa8990b796aae06a38bbbe170ee74c182ef100c12ec1ecd271784e3c7e3923f45085840850a95572ec8f6f8e3eefb5f62d41b20ad9563d68fe71cd97f2d2b9e4"
  ShowHTML "9757eb8f4756502ca7d79c8b319ba9c82a4851b5fdb103bd199dd0b72da9b47f6afc790f668a95fff104779c77f8758ebfe0e85c09677be153735d5501495562"
  ShowHTML "a3f72d7ad408854e851fac3ff8e08f7713a1b8665c72b0c0f1af2323e717c3cd5b17bf2c9300ccab87f90715aa4ec2f25d179706aa01ab06a59c54816f2a27b2"
  ShowHTML "8e82837bee3897ef1ce88477b05a17bbf8a3c3a7d04f57a30a2bbb22792e14f275571e1668d6879820b2fab365b0fce9dae1ec7af4d3e591d3251ffa127f0bfe"
  ShowHTML "9127da20f976657283e4a8bc657c80ea8b3ca21332b1c1673e37c9c3cb8534faf0673ac1ec6b7c102cda4a5ddfc35701e288944dbaba28bf449006f466827a27"
  ShowHTML "6af5415112f2c579f2a813378ca28beb6ba0e15ce2162cd60ffa839f91b196d1245c8a2f6688053851837552e9954597f85672e9c4c89d839f16f0165c603e4b"
  ShowHTML "e422ab52df4e9183f41fe63b94164f167547b5c77e851a6c073c66c162d70ffca0d525e7fca0d73557d36bbed62b159aca36ae50d0f699db193adbc86b222432"
  ShowHTML "51b092054ed4978b51a79f9e347338651197daaa2c3a5e0f56139a7db1e8d0493a411719e4c4dca9b61316b3d0f9587aece0f1e7af212b58a4b3600f362ec5bd"
  ShowHTML "27941e4a96814247a074ac9f63405a1db80e3f67473f19ad0ebfe36782f7402c6169dc17bcf04d5d77ed2354d784238a89212dfa65406e3923dc64a0336a65fc"
  ShowHTML "c50f5a317ecb34e93b31a5e3e59446bb7e9c78df8c0eed323110b8d342daa0f758c1fa0f4f4d9f7290f3a3c7b8c7624f29dd76130f5a1013a76c747b0ca8b88c"
  ShowHTML "9143ed0d21a7fe49dedeb4519128937f7a3aea63fcc56f7198cf37721dc73a09222a37ff1e5509cde8101fdd1e97bd990a0b619dda45484a926b31e57cf13ec0"
  ShowHTML "aa0e5addc67f94c83fbe14a8576065eaba1f01e72aab8173e85e6304cbb83333a665db85e99561fa36a1bc5c734df951275c8d5aea4e7f293933fdd0d8dce82b"
  ShowHTML "83b01fbeee833b54bfdc3a393dcc55dad629595f701608e6982ec1e385c7a65238dec094b5127a4c3e44cf12cbe69b823c34fdbe20cbc35c815e57eb0c6bbbe5"
  ShowHTML "21f598c9bc35b225442d5cf35051ffc7948c53894b458809a4fb1aaa43c533f180e789b67c5452f4971c393893f0631a0453e6c43beefaa0e2db9fc921670955"
  ShowHTML "9805bf86eabb4f63ed318e13d5dc463d0a6a6af86c39baaa0756e7d73c7693000acc3174999c8560d966573f4e598f1da7db18ef8efe257aaec0aa122961336f"
  ShowHTML "17a330a0ff95a61a0681b9c69eabf13a4a024cc15dfd70e8cf072858a3fb442712f6956fb55a992a69d45035c5bc00a53eb248642986ac4a0a7ab20294c454b0"
  ShowHTML "4c10ad0ed36bff046576688da02637897deacf75759fd2319a98aba4c2788af7b1a2df438113ec2fc8c84b180fed0e0e7df4551f77dd76ac57d6c9d2f6c14ad0"
  ShowHTML "c37467239a95ae3103d4638f446f26f3b918731fd7271d72e6b9af4cf66c4b93e70493ccdcbe0fe08a8938812aaa5a16651461b6415f1fbbb710a72b6d907698"
  ShowHTML "0ccba719f969168d4b579ab2b8d1f3a432fc88fdc1f6ab3ae9774314e71bec2f3ff8866f4ddffb9ae441d698ec1b5e801ae242bac43db1f44a67708d3547fd4e"
  ShowHTML "c3ac2bfabed8746d8b75627a49ab8a5a73adcc1ecfb07a11aa6c7675017799fd918db9a2d60459ed85bf97437cb7cb170b370ceaca9eafb4f53651fbb6dbe378"
  ShowHTML "8555e97acb90d8da3eb56de5075674666a152b066825cf51afcea3a54ab5bd9adbb6ce9b369261ff32ab01d354d74498b031f3e904a136956acd4a68753735bd"
  ShowHTML "8ba3332dabda5ea13648d59c71f67c0ff01c8acdf8459e5796d6a8a9a3f399c366b329b49227d305e3d3717e9e60876fe96c878acd8e558147df425dceb8ecb3"
  ShowHTML "1a194626d89c9c8889f4dce90fbb216633b22ce4a18f20b61626c34f18e6d8717d7c6cf40cc25db0f1c4c2b9e0ce55a546271f0ed5cbb3e9ae8ca60cb5fad364"
  ShowHTML "32d0b15f522bbd4225bb700e500e2f06cf7b33ac51d7f8e11447aef19d87fd8b30ace8afa90e24dc512beb4428c4d4189858c8bf7ec74dc71ebfb7859e1805b0"
  ShowHTML "c0129141fd46049d19576256949f1e59d9caaad2f474858917937580d23a31bb37a2f2067b8c73f44640adc7be34769c6161ba1d280ea8e19970df0f4d313658"
  ShowHTML "e432e5174cda7eb25c0939e5e87f638e4ff2dd4eb25900d7954379e88fc7f92a36e330815e78d54213c38a4e2613b445d3fddefac5768ebaec78e93827d03c62"
  ShowHTML "cc0bd000c976282fe0bd773f3c5506ce43546e905d6837104246119d12112782a337d8d626f283902cdcba2d5c57dc6dd717cbe037addc3bf34d74a4387ef2d5"
  ShowHTML "e2c20ba6261c3389bd924fa457de61920bc131ad2c6c432c9dd6285a3037b288b144682b9de20f29cf4c3f7a6ed1129b3e2e5c6e1886228332621ac751841ede"
  ShowHTML "14559585e8bafd26bcdbb2c59407a1f84d982a651693b2c73af368d1d271907f1cff8fcf15baf392e8a9645ed494fdc6ec7e865dbf8de177cc0b241564cae2ed"
  ShowHTML "b054ff44d7bd7bcce82f6e2ff99adc30e9e26e33ef5cd0250d340fe5d4f3ae64849603efbc972c0323116667d8df711e640172f44b6d784a9c325e9c7b9a22d7"
  ShowHTML "ca053c331581f4b2136c6ab508a51662a34420deeba97b7e71bee04c868a85ded52bf52a5ecf4229ccac39c3a476c6261ef5dc8dca6846eb59380523ca6718b0"
  ShowHTML "e069befc98a07ff3d79e7e9471cce419bce85c9c39b8373be7b333956834ee40c2bbf28ce11c06ae7367969d876ae10dc32b5063b7b88d6590510a0fe3dee70c"
  ShowHTML "6722150a52963118e1dcb58b5f787acdbf7d78f8ed53b56d7ccbbca920de04f707d60f7199dfc35bdbb80427fece6112550c7789fff444f3cf0fe8628c50efe5"
  ShowHTML "8d8b8d257fc7e19abe7ac814d88db39613fd2cb0ef2d5f789ef9d2b33ef4c4c5e29da9a98eb3407f1c4a336b6e0d97775833d0b72f3d197de5692ddcb5e0e314"
  ShowHTML "5a0ee883fd5094f16142be8b5f62f41da871a546b705f6cec8c70dd6ed2cf82f77afe17c1feac80e98bb3b33618cc777aefd0ba8035ed9bfcee57f837ae549f6b73fff05532332b865345cd90000000049454e44ae426082}}{\nonshppict"
  ShowHTML "{\pict\picscalex101\picscaley101\piccropl0\piccropr0\piccropt0\piccropb0\picw1958\pich2170\picwgoal1110\pichgoal1230\wmetafile8\bliptag-1973820408\blipupi95{\*\blipuid 8a59e408278a8b7c4ae763e2dfaf7202}"
  ShowHTML "010009000003720e000000004d0e000000000400000003010800050000000b0200000000050000000c0253004b00030000001e0004000000070104004d0e0000"
  ShowHTML "410b2000cc0052004a000000000052004a0000000000280000004a00000052000000010008000000000000000000000000000000000000000000000000000000"
  ShowHTML "0000ffffff00fefefe0098fefe00cbcbfe00cbfefe0065cbfe0065fefe0032cbfe0000cbcb0098cbfe006598980000cbfe000098cb00cbcbcb0098cbcb0065cb"
  ShowHTML "cb0000659800989898003265cb000065cb0032989800003298000032cb000098fe003298cb0000656500006532000032650032cbcb0000323200656598003265"
  ShowHTML "65000065fe003298650032fefe0000989800326598006598cb0000986500326532000032000000fefe00000032009898cb009898650098983200986532006565"
  ShowHTML "320065656500cbcb98009865000098656500cb983200cb9865000000650065323200cb65320032323200cb9800003298fe00cbfecb00656500003265fe0098cb"
  ShowHTML "98009832000098980000cb9898006532000000650000fefecb0065cb9800323265000000980065986500323200009865980032cb980032650000000000000000"
  ShowHTML "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000"
  ShowHTML "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000"
  ShowHTML "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000"
  ShowHTML "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000"
  ShowHTML "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000"
  ShowHTML "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000"
  ShowHTML "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000"
  ShowHTML "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000"
  ShowHTML "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000"
  ShowHTML "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000"
  ShowHTML "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000202"
  ShowHTML "020202020202020202020202020202020202020202020202020202020202020202020e0202020202020202020202020202020202020202020202020202020202"
  ShowHTML "0202020202020202000002020202020202020202020202020202020202020202020202020202020202020202020e0e0512020202020202020202020202020202"
  ShowHTML "020202020202020202020202020202020202020200000202020202020202020202020202020202020202020202020202020202020e120e020212042c05120e12"
  ShowHTML "0e120e120e0202020202020202020202020202020202020202020202020202020000020202020202020202020202020202020202020202020202020212321236"
  ShowHTML "303412020e0e2c0f2602120e33342f432c0e12020202020202020202020202020202020202020202020202020000020202020202020202020202020202020202"
  ShowHTML "020202020e120e342f2f352f352f0e0205040205020f0e123533392f352f2c120e12020202020202020202020202020202020202020202020000020202020202"
  ShowHTML "020202020202020202020202020e123234344130382f3e33333412320e020e05040e1232332f442f412f4430300e123212020202020202020202020202020202"
  ShowHTML "0202020200000202020202020202020202020202020202120e2f30482022281a2f2f2f412f300e12050c231d11120e122f33393335330b271a1e2f2f0e120202"
  ShowHTML "02020202020202020202020202020202000002020202020202020202020202020232123630313a1a1e3129304433442f444c120e102a090c1c32120e4b30442f"
  ShowHTML "413330202930332f442f120e02020202020202020202020202020202000002020202020202020202020202120e12361220241d1b1b2f35332f442f4430120e12"
  ShowHTML "0b11151a1e0e0e122f382f332f4435282f4435332f2f2d2f12020202020202020202020202020202000002020202020202020202020e121230204b203a2f333e"
  ShowHTML "4b33443e4430442f384b11190d1b1a1a24201a2848204b304430443333334433381d441511343102020202020202020202020202000002020202020202020202"
  ShowHTML "0e122d3815201a3a352f2d382f44304b3029311b312826111d1b1d1b0838153a1d24151b2f382f302f332f442f252e191d2f2f2f0e0202020202020202020202"
  ShowHTML "00000202020202020202020e123444310d1b3a34300e342f44333a1a2420311d2b1a1a110d1a15200d1a1a26111a241a480d3a28443e44342f30201511301531"
  ShowHTML "1f0e02020202020202020202000002020202020202020e2f2d1e223020280e120e3831441a1b251b240d2f15203a31311f200b31312d2d302f4820301d24151a"
  ShowHTML "1a151d4b2f2c0e2f2f241d24352f2d1202020202020202020000020202020202020e333325151e2f120e021244110d1a3a251e0d3a2030310b060e05260c090c"
  ShowHTML "110204053212302f3a11110d1e091a1e3a3012320225114e2f0d1b2c120202020202020200000202020202020e2d2e2f2f1a282f02020e2f151a1d1a203a1a3a"
  ShowHTML "12120b0b070605060f0c0c1d1505050a0526152c3230343a2f24151a4d1a0e120e022f2f151509300e1202020202020200000202020202343e31243a330e0202"
  ShowHTML "38201c201c141e20310e2c0f0e02150d1a0d090c1c1717141c0d0908111a0d050e020e02123030191a0d1c093a0e020e20204b39332f12020202020200000202"
  ShowHTML "02020e2f2f15151a2f02022d340d28241a1a200b0b1b3a1e1a12191a2f3a201a161c260b1424151e3428080a0f28281a2c322d3e25244d0d11282c020e2f2f33"
  ShowHTML "352f121202020202000002020212332f2f301e3002022f301e0d1a0d1e4a1e1b291b2929291e1c1a332f332f49140f321731302f3331091b291b291e1b100b30"
  ShowHTML "3031241e111d1e32020e3e33332f414c1202020200000202342f3933392f0f02022f392f1d1a284802060f1b1e1b1e1e1b1e0d1b2f3e302016141925211a203a"
  ShowHTML "2f300d1e1b1e1b1e1b29100404203038243a2f2f0e020e2f3533392f351202020000020e332f4439333302021233382f111a380f0e4a1e1b291e291e291e1611"
  ShowHTML "1c0c090937211414170d0d0c240d091e291b1b1b1e1e1110060e2f2f44332f390e02022d3333412f330e12020000022d2f12342f0e02020202022f2f2e302f04"
  ShowHTML "311e201e1a291c1e1e1b3c090d24202b1c0d08111c2b1a11140d081e1e1c1c1e1c1b201e05042f2f2f2f352f0e020202322f2d302f340e020000301202023e30"
  ShowHTML "020202020234333e2f331f0c111e1e1b291e291e291e291e1105204b3a040e0b3a302b100a4a2929021b291e291b291b1c091f30332f3e330e0202020202332f"
  ShowHTML "0f023131000002020202020202020202023039382f20051d08111e1e1e291c1e1e2b1a292031283a4c04021230381e250f201e1e1b1e1e1e1a1e1e1c080c084a"
  ShowHTML "2f2f30300e0202020202022f0e0202020000020202020202020202020230382f3e10100c1c0d0d1a021e291b1e1b2930333038383a05050f202b3a34302f3a1b"
  ShowHTML "1e1e1e1a29110d0c1c0d1d041e2f3330060202020202022f0b02020200000202020202020202020202202f2f03060b110d1721140d0d191e1e1a352f352f2d30"
  ShowHTML "0e04020b2c3a352f2f332f281a1e250c0816211411242011064b2f30070502020202020202020202000002020202020202020202120e283125151e160d163716"
  ShowHTML "161114241c0b4b33332f33300f020e0b0b20442f332f440b2511090d16211716160d10060a0b2015050e02020202020202020202000002020202020202020202"
  ShowHTML "0e0604080f1a1a1e0c161a1e1617140d080d1e3a2f2f31153d0e02122c04103434281524080c1617210d1c491911070a03070307031202020202020202020202"
  ShowHTML "000002020202020202020202120f20281e282b1c0d210d1d291b291c1714160909151d030f05050f26030a0a0d0c0d141414090c0c1b1c170d110a030a050605"
  ShowHTML "0a0e020202020202020202020000020202020202020202120e28201a1e1b1b260d17190c1b1e1a1e1e163f16140c08190504020b1210260d0c113f14140d230c"
  ShowHTML "081b48140d1e10241d0603070512020202020202020202020000020202020202120e12320e1b291e1b1e1e1c1c17162a241a1e1b291a2b161711110c250f0e0b"
  ShowHTML "0b09090c1721140c0c2a0c231a1a37210d1e1e1b1e0406050a0e120202020202020202020000020202020e1205120303321e1b1e1a1e1c2b2516140d230c1a1b"
  ShowHTML "1b1b1a1e1a1e1614080d0c0d08111416080c2308230c1d1b1a1e17171a1e1e1e1b1e1b1e1a1903040e120e020202020200000202020202050a0305061a1b291a"
  ShowHTML "291b1e163714170d0c2a091b1e1b1e1b1e0d0d0920111a111a0d0d090d0c08230c2a1b1b1e2b17141e1e1e1b291e1e1a08050a030405120e1202020200000202"
  ShowHTML "020202030705034a1b29201e1b1a20490d11210d230c231e1a1b1a11081b25302d2f2d2f2e300b1a1d090823230c201a1b2b21111e1b201e1c1e251905060303"
  ShowHTML "070705050202020200000202020202020603261b1e1b291b1e1b291e1e0d17110c2a0c0c1a091e3038310412332f342f2f0b0e2d332f3024241b291b1e161711"
  ShowHTML "1e1b291e1e1b1e240a07060308230a3202020202000002020202020205034a1a1a1e1a1e1e1b1b1e1a0c1716230c2324081b2f2f352f322f2f2831312d2f2d44"
  ShowHTML "352f122819241b1e1b1714111e1e1a1e1a1b1b1b1d2604080305050202020202000002020202020202031b1a1a1e1e1a1e1e291b1e0d1c140c2a0c1d1e2f1036"
  ShowHTML "332f38200d1d240924243a303312042f28141e1b1b1416181e1e291b291e1e1e1b111d050a030202020202020000020202020202020605070f1e25291a1e1b1e"
  ShowHTML "1a1c1914080d1d2f2f33362f151b31302f2f352f2d2f2d30151a2f332d2f15111b1413091e1e1a1b1a1e281b1a15070502030202020202020000020202020232"
  ShowHTML "04050603101c2b371e1b1e1b1e1a0d160d091e2d3e2f30241a30332f2f39332f123431303a201a33310230111c211a0d1b1b1e1e1e1e1b1b1c1a250606020202"
  ShowHTML "020202020000020202020e120308050a031c16160f1e1b1e20150949191b2f0e2d2f0d1b2f333933352f392f0e050e3e352f283a430e2f30191411241b1b1b20"
  ShowHTML "151e1b1e2024150502050e020202020200000202120e05050a030a0f1a1c37160f151e0e1e100d1a3a2f2f3448242f392f2f333933332f3612460e2e332f2f25"
  ShowHTML "1a312f39311111111a1b1e140b1b1e151f1d0805120e050e02020202000002120e120503030a221e1e37142b100a220a0b0f19112f33352f0d3a352f3533352f"
  ShowHTML "352f352f362f362f3533352f22202d12120d19240b1b2026471b1b1e1b1e1503030705120e120202000002020a050a031d1a021e1c1a1e1c1a1b1a0306051c24"
  ShowHTML "2d4631113a2f3333412f3333332f332f2f2f332f332f33332f3130123111110d0a0329311e1a1e1e1e1a1e111d070605040e12020000020202030306151e1b1e"
  ShowHTML "1e1e1e1e1b1a1b0f0525241e362f301e2f33392f3933393335333933352f392f3533392f352f302f36300d1e05031a3a1b1e1e1b1a1b1b1b0b04050a03060202"
  ShowHTML "0000020202030a051b1b1e1b1e1e1e1b1e1e1e190a0d2428332f443033333339332f333933332f3933332f39332f333933331e39332f241a25031a1a291b1e1b"
  ShowHTML "291e1b1b1e0d08030a02020200000202020203201b29281e1b0220291b1e1a1e08161930123015332e0e313035333533352f35333533352f35333533352f1530"
  ShowHTML "0f300b0d1d24071e1a1b281b1e291a291b1b05050502020200000202020202301e1e291e291b291e291a200c11141a300e3024330b050e34332f33332f3b332f"
  ShowHTML "332f3333332f3333332f1b300b2f200d1c0c1c1b291b291e291e1e1b291b1d070202020200000202020202281a1e1a1e1a1e1b1e1a1e110d1416242f12302434"
  ShowHTML "0405022f352f39333533392f2f2f35333533392f352f113a352f2d0d2111151c1b1e1a1e1b1b1e1e1a1b0a0202020202000002020202121a291e291b291e1e1b"
  ShowHTML "2b0c0d211c1b252f332f193933122f36332f333933332f340e302f352f2f3339333320312f2d300c091416091a1b1e1b451e1e1b2b1b1b0f0202020200000202"
  ShowHTML "0202051b241b22291e1e1b1a08113f111b1e152f3444192f352f35423533352f3533392f0b3e35352f332f2f352f19280f30120d230d3f141d1e201b1b1b201b"
  ShowHTML "1915251a020202020000020202050f100b1b291e29161e1a1117171e1e1a1a2f0e342033332f332f332f3333332f33332f2f332f2d0e2d33332f1a300b32200d"
  ShowHTML "082a171414241e1b1b1e1e1e1903080f0e02020200000202020a050a0a1a1a1e11160c0d181c1a1e1a1e143a2d332f30352f3933352f3933353335333533392f"
  ShowHTML "02050e33352f1a2f2e2f190d230c230d14160c1a1a1e1a1e100a07070e1202020000020206070a0506101b1a1e0c24141e1a1e1a1e1a111b33332f2844333339"
  ShowHTML "41332f3933333339332f332d0f04122e332f3a2f332f0d0c08230c230d21160c1e10101a11070605040e0202000002020706030303031a1a1a243f141a1b1a1b"
  ShowHTML "1a1a151a35302f202f33352f352f35423533353335333533432f353335312d0f43251924230c230c23173f11151103201103050303120e020000020202070a05"
  ShowHTML "0a0a1e0c0d14371b1e1a1a2a0c2a0c11300e40311a2f3333412f3333332f3333332f33332f2f33331f1a33323e141b1b1e1a1a0c0c0c1414110c1a260d070a02"
  ShowHTML "04070202000002020202050603050d0d141c1a1e1d0c2308230c230d2f2e2d2f25203933392f391f2d2e352f353335333533352f101b352f2f1a1b1b1a1b1a1b"
  ShowHTML "1a1d0c14140d0c0a05050307020202020000020202020202060b0d14141c110c0c2a0c230c2a082a1a332f2f301a2f2f332f12020e302f39332f333933332f10"
  ShowHTML "1e312f39381e1b1a1e1b1b1a1e1a1a2417140d1119030602020202020000020202020202020c19141417181414163c0d080c082a0d112f040e3320202f2f350a"
  ShowHTML "3d3e352f353335333530263032042f1f1d1b1a1a1a1e20161117211421143f0c06020202020202020000020202020202020c090c090c1114161414141414140d"
  ShowHTML "11111a2d3034301b24303331332f333b2f2f332f151d1e2f2f0b310d1c1a1c161414171414141611160c140c1d020202020202020000020202020202030d1a11"
  ShowHTML "0d0d0c0c080d18141416141721140d2f36333634312415382f2f3933352f340b153a2d2f352f1d1414142117171414140d0d0c0c080d090d0d26020202020202"
  ShowHTML "00000202020202020e030b1a1e161e1c1e371e1c1c110d09090c090d1c2f2c0e302f28311a1525101a2828312f2d0e34310c0d0c090c0d0d1c1c1e111a1b1e1a"
  ShowHTML "060504030a320202020202020000020202020202070a07241a09151e1e1a1a1e1a1c201c1116152424112f2d352f040e352f25302d12052d352f3619191c1914"
  ShowHTML "0d1b251a1c1c19261a1a221a05060705050502020202020200000202020202320a070a0f1e101e1a1e1b1e1e1e1a1b1e1e10061015111130302d0e0e2f2f122f"
  ShowHTML "33340e322f341f2411060603101a1e1a1b110f202b1b1e1a06030a070a0e0202020202020000020202020205030603100606201e1a1c1b1e1a1a1a1e08060303"
  ShowHTML "050a0916111124202d2e2f302f2d310d19141124050a0506082220282c15111e151a1a110305050605050e02020202020000020202020202020306050605121a"
  ShowHTML "1b1e1e1a1a111111260706050a03110d171b1e1a110d191d0d0d0d091714140a0a050602201a1e1a1e1b1e20251a1b110a080a03080712020202020200000202"
  ShowHTML "02020202020202020506030b1b1b201e1a160806150b030a050a150d141a201b1b1b200c0c08232321140803050a050b1b1b1a1e1a1e201e2c1e200605050305"
  ShowHTML "0202020202020202000002020202020202020202020706051a101a1c2b161c1e1e1b1e1b1c0f10110d141e1a1b1a1e230c2a0c19110c14030a0e1a1b1e1a291a"
  ShowHTML "1e1b1e1a20110f07050202020202020202020202000002020202020202020202020505060803061e111e1b1e1a1b1b1e201b06191814111e1b1b1a0823082314"
  ShowHTML "080d060605151a1a1b1e281e1b1b1b1e1106030505020202020202020202020200000202020202020202020202050a0504070a1a111a1e1a1b1e1e1b1e100605"
  ShowHTML "1c14141a1e1a1e230c2a24140d0d060f060508040d1b1e1b1b1a1b1a10050607120202020202020202020202000002020202020202020202020a0305050a0707"
  ShowHTML "051b201b1b1b1a1e241e1526070d14141a1b202a232421141d06050d191615260b1e201b1a1b1a26030307050e02020202020202020202020000020202020202"
  ShowHTML "0202020202030a0710030a05061a1a1a1e1b1e1b1e1a1e1a280914211b1a1e230814140d1d050a2626081e1a1b1a1e1e291a1a0605030a031202020202020202"
  ShowHTML "0202020200000202020202020202020202050705070a0706050b1a1e1b1b1a1e201a1a1b1a110c171a1b1a082314170c062608161111241e261e1a1c271b0306"
  ShowHTML "070705070e0202020202020202020202000002020202020202020202020e0f0708050603040306101a07101b1a1b1b1a1e0f110c161a1e2324140d140d141920"
  ShowHTML "1e252517261b19071b240605040306031202020202020202020202020000020202020202020202020203030305070202020a030a150f03151a1a201b11190a0d"
  ShowHTML "211a150c140d19060506051a220d19110d110303220d020202020202020202020202020202020202000002020202020202020202020202020202020202070f03"
  ShowHTML "0a030f030b1a1b1c101a1d1117141e15170c1403151f1c0f1c1d1414190606051002020202020202020202020202020202020202000002020202020202020202"
  ShowHTML "02020202020202020202030f0305030a030a0515080303100d161417140d030a180d03190d08050a030a03030502020202020202020202020202020202020202"
  ShowHTML "00000202020202020202020202020202020202020202040706030a05080310030a030f031411141414060a0306030a070a060802090303030202020202020202"
  ShowHTML "0202020202020202020202020000020202020202020202020202020202020202020203050508050305060305020a05030d0d13140c0603050503050305020305"
  ShowHTML "030503070202020202020202020202020202020202020202000002020202020202020202020202020202020202020f0608050202020210070607060510110d0c"
  ShowHTML "11030a030a050a050202020205031202020202020202020202020202020202020202020200000202020202020202020202020202020202020202020202020202"
  ShowHTML "0202020303060704030b0c0d030a050a030503020202020202020e02020202020202020202020202020202020202020200000202020202020202020202020202"
  ShowHTML "02020202020202020202020202020205080308050604020906030a030a0306020202020202020202020202020202020202020202020202020202020200000202"
  ShowHTML "02020202020202020202020202020202020202020202020202020202050603070202020202050307020302020202020202020202020202020202020202020202"
  ShowHTML "0202020202020202000002020202020202020202020202020202020202020202020202020202020202030202020202020202020304020202020202020202020202020202020202020202020202020202020202020000040000002701ffff030000000000}}"
  ShowHTML "\par }\pard\plain \s15\qc \li0\ri0\widctlpar\tqc\tx4419\tqr\tx8838\aspalpha\aspnum\faauto\adjustright\rin0\lin0\itap0\pararsid9184405 \fs20\lang1046\langfe1046\cgrid\langnp1046\langfenp1046 {\b\f36\fs28\insrsid9184405 Presid\'eancia da Rep\'fablica}{"
  ShowHTML "\insrsid9184405 "
  ShowHTML "\par }\pard\plain \qc \li0\ri0\widctlpar\aspalpha\aspnum\faauto\adjustright\rin0\lin0\itap0\pararsid9184405 \fs24\lang1046\langfe1046\cgrid\langnp1046\langfenp1046 {\f1\insrsid9184405 Secretaria Especial de Pol\'edticas de Promo\'e7\'e3o da Igualdade Racial"
  ShowHTML ""
  ShowHTML "\par }{\insrsid9184405 "
  ShowHTML "\par "
  ShowHTML "\par "
  ShowHTML "\par }\pard \ql \li0\ri0\widctlpar\aspalpha\aspnum\faauto\adjustright\rin0\lin0\itap0\pararsid9184405 {\f1\insrsid9184405 Autoriza\'e7\'e3o n\'ba " & mid(RS("codigo_interno"),5)
  ShowHTML "\par "
  ShowHTML "\par "
  ShowHTML "\par "
  ShowHTML "\par \tab \tab \tab \tab \tab              Bras\'edlia-DF, "& mid(FormatDateTime(Date,1),InStr(FormatDateTime(Date(),1)," "))& "."  
  ShowHTML "\par "
  ShowHTML "\par }{\f1\insrsid9184405 "
  ShowHTML "\par Ao Senhor"
  ShowHTML "\par }\pard\plain \s2\ql \li0\ri0\keepn\widctlpar\aspalpha\aspnum\faauto\outlinelevel1\adjustright\rin0\lin0\itap0\pararsid9184405 \b\f1\fs22\lang1046\langfe1046\cgrid\langnp1046\langfenp1046 {\fs24\insrsid9184405 S\'edlvio Andrade Junior"
  ShowHTML "\par }\pard\plain \ql \li0\ri0\widctlpar\aspalpha\aspnum\faauto\adjustright\rin0\lin0\itap0\pararsid9184405 \fs24\lang1046\langfe1046\cgrid\langnp1046\langfenp1046 {\f1\insrsid9184405 Coordenador-Geral de Log\'edstica"
  ShowHTML "\par Minist\'e9rio da Justi\'e7a"
  ShowHTML "\par "
  ShowHTML "\par "
  ShowHTML "\par "
  ShowHTML "\par \tab       Senhor Coordenador,"
  ShowHTML "\par }{\f1\insrsid9184405 "
  ShowHTML "\par "
  If (w_primeira_saida - Date()) < w_prazo Then
     ShowHTML "\par }\pard \qj \fi708\li0\ri0\widctlpar\aspalpha\aspnum\faauto\adjustright\rin0\lin0\itap0\pararsid2980338 {\f1\insrsid2980338         Autorizo o encaminhamento da PCD, em nome do(a) " &RS1("nm_tipo_vinculo")& " }{\b\f1\insrsid2980338 " &RS1("nm_pessoa")& ", }{"
     ShowHTML "\f1\insrsid2980338 que se encontra fora do prazo de (" &w_prazo& ") dez dias, conforme Portaria n\'ba 98 de 16 de julho de 2003 do Minist\'e9rio do Planejamento."
  Else
     ShowHTML "\par }\pard \qj \fi708\li0\ri0\widctlpar\aspalpha\aspnum\faauto\adjustright\rin0\lin0\itap0\pararsid9184405 {\f1\insrsid9184405        Reporto-me, mui respeitosamente a Vossa Senhoria, a fim de solicitar-lhe as provid\'eancias cab\'edveis e necess\'e1"
     ShowHTML "rias, para a concess\'e3o de di\'e1rias e passagens para o(a) "&RS1("nm_tipo_vinculo")&" }{\b\f1\insrsid9184405 "&RS1("nm_pessoa")&"}{\f1\insrsid9184405 .}{\b\f1\insrsid9184405 "
  End If
  ShowHTML "\par }\pard\plain \s16\qj \fi708\li0\ri0\widctlpar\aspalpha\aspnum\faauto\adjustright\rin0\lin0\itap0\pararsid9184405 \f1\fs24\lang1046\langfe1046\cgrid\langnp1046\langfenp1046 {\insrsid9184405       "
  ShowHTML "\par }\pard\plain \qj \fi708\li0\ri0\widctlpar\aspalpha\aspnum\faauto\adjustright\rin0\lin0\itap0\pararsid9184405 \fs24\lang1046\langfe1046\cgrid\langnp1046\langfenp1046 {\f1\insrsid9184405 "
  ShowHTML "\par "
  ShowHTML "\par }\pard \qc \fi708\li0\ri0\widctlpar\aspalpha\aspnum\faauto\adjustright\rin0\lin0\itap0\pararsid9184405 {\f1\insrsid9184405 Atenciosamente,"
  ShowHTML "\par "
  ShowHTML "\par "
  ShowHTML "\par "
  ShowHTML "\par }\pard\plain \s1\qc \li0\ri0\keepn\widctlpar\aspalpha\aspnum\faauto\outlinelevel0\adjustright\rin0\lin0\itap0\pararsid9184405 \b\f1\fs24\lang1046\langfe1046\cgrid\langnp1046\langfenp1046 {\insrsid9184405 " & RS("nm_titular")
  ShowHTML "\par }\pard\plain \qc \li0\ri0\widctlpar\aspalpha\aspnum\faauto\adjustright\rin0\lin0\itap0\pararsid3545814 \fs24\lang1046\langfe1046\cgrid\langnp1046\langfenp1046 {\f1\insrsid9184405 " &RS("nm_unidade_resp")& "}{\f1\insrsid12326642 "
  ShowHTML "\par \page }{\insrsid12326642\charrsid3545814 "
  ShowHTML "\par }\trowd \irow0\irowband0\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth4500 \cellx3060\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth7220 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl"
  ShowHTML "\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard \qc \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid12326642\charrsid5664258 \page \~\cell }{"
  ShowHTML "\b\fs18\insrsid12326642\charrsid5664258 PROPOSTA DE CONCESS\'c3O DE PASSAGENS E DI\'c1RIAS\cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\f1\fs16\insrsid12326642\charrsid5664258 \cell }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid12326642\charrsid5664258 \trowd \irow0\irowband0"
  ShowHTML "\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth4500 \cellx3060\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth7220 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl"
  ShowHTML "\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row }\trowd \irow1\irowband1\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 "
  ShowHTML "\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth4500 \cellx3060\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth7220 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard "
  ShowHTML "\qc \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\b\fs16\insrsid12326642\charrsid5664258 \~}{\b\f1\fs16\insrsid12326642\charrsid5664258 \cell }{\fs14\insrsid12326642\charrsid5664258 "
  If RS("tipo_missao") = "I" Then
     ShowHTML "( x ) INICIAL               (   ) PRORROGA\'c7\'c3O               (   ) COMPLEMENTA\'c7\'c3O\cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\f1\fs16\insrsid12326642\charrsid5664258 \cell }\pard "
  ElseIf RS("tipo_missao") = "P" Then
     ShowHTML "(   ) INICIAL               ( x ) PRORROGA\'c7\'c3O               (   ) COMPLEMENTA\'c7\'c3O\cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\f1\fs16\insrsid12326642\charrsid5664258 \cell }\pard "
  ElseIf RS("tipo_missao") = "C" Then
     ShowHTML "(   ) INICIAL               (   ) PRORROGA\'c7\'c3O               ( x ) COMPLEMENTA\'c7\'c3O\cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\f1\fs16\insrsid12326642\charrsid5664258 \cell }\pard "
  Else
     ShowHTML "(   ) INICIAL               (   ) PRORROGA\'c7\'c3O               (   ) COMPLEMENTA\'c7\'c3O\cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\f1\fs16\insrsid12326642\charrsid5664258 \cell }\pard "
  End If
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid12326642\charrsid5664258 \trowd \irow1\irowband1"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth4500 \cellx3060\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth7220 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone "
  ShowHTML "\clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row }\trowd \irow2\irowband2\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb"
  ShowHTML "\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2182 \cellx742\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2318 \cellx3060\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1232 \cellx4292\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb"
  ShowHTML "\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth928 \cellx5220\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1812 \cellx7032\clvertalb\clbrdrt\brdrnone "
  ShowHTML "\clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth452 \cellx7484\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth596 \cellx8080"
  ShowHTML "\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2200 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard \qc \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\b\fs12\insrsid12326642\charrsid5664258 \~}{\b\f1\fs12\insrsid12326642\charrsid5664258 \cell \cell }{"
  ShowHTML "\fs12\insrsid12326642\charrsid5664258 \~\cell \~}{\f1\fs12\insrsid12326642\charrsid5664258 \cell }{\fs12\insrsid12326642\charrsid5664258 \~}{\f1\fs12\insrsid12326642\charrsid5664258 \cell }{\fs12\insrsid12326642\charrsid5664258 \~}{"
  ShowHTML "\f1\fs12\insrsid12326642\charrsid5664258 \cell }{\fs12\insrsid12326642\charrsid5664258 \~}{\f1\fs12\insrsid12326642\charrsid5664258 \cell }{\fs12\insrsid12326642\charrsid5664258 \~}{\f1\fs12\insrsid12326642\charrsid5664258 \cell }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\f1\fs12\insrsid12326642\charrsid5664258 \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {"
  ShowHTML "\fs12\insrsid12326642\charrsid5664258 \trowd \irow2\irowband2\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone "
  ShowHTML "\clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2182 \cellx742\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2318 \cellx3060\clvertalb\clbrdrt\brdrnone \clbrdrl"
  ShowHTML "\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1232 \cellx4292\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth928 \cellx5220"
  ShowHTML "\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1812 \cellx7032\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth452 \cellx7484\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth596 \cellx8080\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb"
  ShowHTML "\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2200 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row "
  ShowHTML "}\trowd \irow3\irowband3\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone" 
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2182 \cellx742\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2318 \cellx3060\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb"
  ShowHTML "\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1232 \cellx4292\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth928 \cellx5220\clvertalb\clbrdrt\brdrnone \clbrdrl"
  ShowHTML "\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1812 \cellx7032\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1048 \cellx8080\clvertalb\clbrdrt"
  ShowHTML "\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2200 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 "
  ShowHTML "\cellx10354\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs18\insrsid12326642\charrsid5664258 \~}{\f1\fs18\insrsid12326642\charrsid5664258 \cell \cell }{\fs18\insrsid12326642\charrsid5664258 \~}{"
  ShowHTML "\f1\fs18\insrsid12326642\charrsid5664258 \cell \cell }\pard \qc \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\b\fs18\insrsid12326642\charrsid5664258    BENEFICI\'c1RIO}{\b\f1\fs18\insrsid12326642\charrsid5664258 "
  ShowHTML "\cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\f1\fs18\insrsid12326642\charrsid5664258 \cell }{\fs18\insrsid12326642\charrsid5664258 \~}{\f1\fs18\insrsid12326642\charrsid5664258 \cell \cell "
  ShowHTML "}\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs18\insrsid12326642\charrsid5664258 \trowd \irow3\irowband3"
  ShowHTML "\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2182 "
  ShowHTML "\cellx742\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2318 \cellx3060\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth1232 \cellx4292\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth928 \cellx5220\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone "
  ShowHTML "\clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1812 \cellx7032\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1048 \cellx8080\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone "
  ShowHTML "\clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2200 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row "
  ShowHTML "}\trowd \irow4\irowband4\ts11\trrh150\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth4500 \cellx3060\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth7220 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone "
  ShowHTML "\clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard \qc \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\b\fs22\insrsid12326642\charrsid5664258 MINIST\'c9RIO DA JUSTI\'c7A \cell }"
  If uCase(Trim(RS1("nm_tipo_vinculo"))) = "COLABORADOR EVENTUAL" Then
     ShowHTML "\pard \ql \li0\ri-280\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin-280\lin0\pararsid12326642 {\fs16\insrsid12326642\charrsid5664258 (  ) Servidor    ( X ) Colaborador Eventual    (  ) Convidado  (  ) Assessoramento Especial \cell } "
  ElseIf uCase(Trim(RS1("nm_tipo_vinculo"))) = "QUADRO PERMANENTE" Then
     ShowHTML "\pard \ql \li0\ri-280\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin-280\lin0\pararsid12326642 {\fs16\insrsid12326642\charrsid5664258 ( X ) Servidor    (  ) Colaborador Eventual    (  ) Convidado  (  ) Assessoramento Especial \cell } "
  ElseIf uCase(Trim(RS1("nm_tipo_vinculo"))) = "DIRIGENTE" or Instr(RS1("nm_tipo_vinculo"),"Função") > 0 Then
     ShowHTML "\pard \ql \li0\ri-280\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin-280\lin0\pararsid12326642 {\fs16\insrsid12326642\charrsid5664258 (  ) Servidor    (  ) Colaborador Eventual    (  ) Convidado  ( X ) Assessoramento Especial \cell } "  
  ElseIf RS1("interno") = "N" Then
     ShowHTML "\pard \ql \li0\ri-280\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin-280\lin0\pararsid12326642 {\fs16\insrsid12326642\charrsid5664258 (  ) Servidor    (  ) Colaborador Eventual    ( X ) Convidado  (  ) Assessoramento Especial \cell } "
  Else
     ShowHTML "\pard \ql \li0\ri-280\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin-280\lin0\pararsid12326642 {\fs16\insrsid12326642\charrsid5664258 (  ) Servidor    (  ) Colaborador Eventual    (  ) Convidado  (  ) Assessoramento Especial \cell } "
  End If
  ShowHTML "\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\f1\fs16\insrsid12326642\charrsid5664258 \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {"
  ShowHTML "\fs16\insrsid12326642\charrsid5664258 \trowd \irow4\irowband4\ts11\trrh150\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone "
  ShowHTML "\clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth4500 \cellx3060\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth7220 \cellx10280\clvertalb\clbrdrt"
  ShowHTML "\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row }\trowd \irow5\irowband5"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2182 \cellx742\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2318 \cellx3060\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb"
  ShowHTML "\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1232 \cellx4292\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth3788 \cellx8080\clvertalb\clbrdrt\brdrnone \clbrdrl"
  ShowHTML "\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2200 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard "
  ShowHTML "\qc \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid12326642\charrsid5664258 \~}{\f1\fs16\insrsid12326642\charrsid5664258 \cell \cell }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid12326642\charrsid5664258 \~}{\f1\fs16\insrsid12326642\charrsid5664258 \cell \cell }{\fs16\insrsid12326642\charrsid5664258 \~}{"
  ShowHTML "\f1\fs16\insrsid12326642\charrsid5664258 \cell \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid12326642\charrsid5664258 \trowd \irow5\irowband5"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2182 \cellx742\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2318 \cellx3060\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb"
  ShowHTML "\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1232 \cellx4292\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth3788 \cellx8080\clvertalb\clbrdrt\brdrnone \clbrdrl"
  ShowHTML "\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2200 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row "
  ShowHTML "}\trowd \irow6\irowband6\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth4500 \cellx3060\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2399 \cellx5459\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 "
  ShowHTML "\clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth4821 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard "
  ShowHTML "\qc \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid12326642\charrsid5664258 \~\cell }\pard \qr \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {"
  ShowHTML "\b\fs16\insrsid12326642\charrsid5664258 N\'ba:\cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid12326642\charrsid5664258 \~\cell }{\f1\fs16\insrsid12326642\charrsid5664258 \cell }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid12326642\charrsid5664258 \trowd \irow6\irowband6"
  ShowHTML "\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth4500 \cellx3060\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2399 \cellx5459\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 "
  ShowHTML "\clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth4821 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row "
  ShowHTML "}\trowd \irow7\irowband7\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2927 \cellx1487\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth8867 \cellx10354\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\b\fs16\insrsid12326642\charrsid5664258 1 - PROPONENTE\cell }{\fs16\insrsid12326642\charrsid5664258 \cell }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid12326642\charrsid5664258 \trowd \irow7\irowband7"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2927 "
  ShowHTML "\cellx1487\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth8867 \cellx10354\row }\trowd \irow8\irowband8"
  ShowHTML "\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth11720 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs12\insrsid12326642\charrsid5664258 \'d3RG\'c3O/UNIDADE\cell \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {"
  ShowHTML "\fs12\insrsid12326642\charrsid5664258 \trowd \irow8\irowband8\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb"
  ShowHTML "\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth11720 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row "
  ShowHTML "}\trowd \irow9\irowband9\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth11720 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid12326642\charrsid5664258 SECRETARIA ESPECIAL DE POL\'cdTICAS DE PROMO\'c7\'c3O DA IGUALDADE RACIAL\cell \cell }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid12326642\charrsid5664258 \trowd \irow9\irowband9"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth11720 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row }\trowd \irow10\irowband10"
  ShowHTML "\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth11720 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs12\insrsid12326642\charrsid5664258 NOME\cell \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {"
  ShowHTML "\fs12\insrsid12326642\charrsid5664258 \trowd \irow10\irowband10\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb"
  ShowHTML "\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth11720 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row "
  ShowHTML "}\trowd \irow11\irowband11\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr"
  ShowHTML "\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth11720 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid12326642\charrsid5664258 " &RS("nm_titular")& "\cell \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {"
  ShowHTML "\fs16\insrsid12326642\charrsid5664258 \trowd \irow11\irowband11\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb"
  ShowHTML "\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth11720 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row "
  ShowHTML "}\trowd \irow12\irowband12\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth11720 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs12\insrsid12326642\charrsid5664258 CARGO/FUN\'c7\'c3O\cell \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {"
  ShowHTML "\fs12\insrsid12326642\charrsid5664258 \trowd \irow12\irowband12\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb"
  ShowHTML "\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth11720 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row "
  ShowHTML "}\trowd \irow13\irowband13\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr"
  ShowHTML "\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth11720 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid12326642\charrsid5664258 Subsecretário da " & RS("nm_unidade_resp") & "  \cell \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {"
  ShowHTML "\fs16\insrsid12326642\charrsid5664258 \trowd \irow13\irowband13\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb"
  ShowHTML "\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth11720 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row "
  ShowHTML "}\trowd \irow14\irowband14\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2927 \cellx1487\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1110 \cellx2597\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb"
  ShowHTML "\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth643 \cellx3240\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1052 \cellx4292\clvertalb\clbrdrt\brdrnone "
  ShowHTML "\clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1167 \cellx5459\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1573 \cellx7032"
  ShowHTML "\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth452 \cellx7484\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth596 \cellx8080\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2200 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb"
  ShowHTML "\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\b\fs16\insrsid12326642\charrsid5664258 2 - PROPOSTO\cell }{"
  ShowHTML "\fs16\insrsid12326642\charrsid5664258 \cell \cell \cell \cell \cell \cell \cell \cell \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid12326642\charrsid5664258 \trowd \irow14\irowband14"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2927 \cellx1487\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1110 \cellx2597\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb"
  ShowHTML "\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth643 \cellx3240\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1052 \cellx4292\clvertalb\clbrdrt\brdrnone "
  ShowHTML "\clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1167 \cellx5459\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1573 \cellx7032"
  ShowHTML "\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth452 \cellx7484\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth596 \cellx8080\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2200 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb"
  ShowHTML "\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row }\trowd \irow15\irowband15\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt"
  ShowHTML "\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth8472 \cellx7032\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth3248 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs12\insrsid12326642\charrsid5664258 NOME\cell TELEFONE\cell \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {"
  ShowHTML "\fs12\insrsid12326642\charrsid5664258 \trowd \irow15\irowband15\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb"
  ShowHTML "\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth8472 \cellx7032\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth3248 \cellx10280"
  ShowHTML "\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row }\trowd \irow16\irowband16"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth8472 \cellx7032\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth3248 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone "
  ShowHTML "\clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid12326642\charrsid5664258 " & RS1("nm_pessoa")
  ShowHTML "\cell (" & Nvl(RS1("ddd"),"  ")& ") " & Nvl(RS1("nr_telefone"),"       ") & " / " & Nvl(RS1("nr_celular"),"       ")& "\cell \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid12326642\charrsid5664258 \trowd \irow16\irowband16"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth8472 \cellx7032\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth3248 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone "
  ShowHTML "\clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row }\trowd \irow17\irowband17\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb"
  ShowHTML "\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth4680 \cellx3240\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2219 \cellx5459\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2025 \cellx7484\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone "
  ShowHTML "\clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2796 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs12\insrsid12326642\charrsid5664258 CARGO/FUN\'c7\'c3O\cell MATR\'cdCULA SIAPE\cell CI\cell CPF\cell \cell }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs12\insrsid12326642\charrsid5664258 \trowd \irow17\irowband17"
  ShowHTML "\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth4680 \cellx3240\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2219 \cellx5459\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone "
  ShowHTML "\clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2025 \cellx7484\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2796 \cellx10280"
  ShowHTML "\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row }\trowd \irow18\irowband18"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth4680 \cellx3240\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2219 \cellx5459\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone "
  ShowHTML "\clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2025 \cellx7484\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2796 "
  ShowHTML "\cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {"
  ShowHTML "\fs16\insrsid12326642\charrsid5664258 ---\cell \~\cell " &RS1("rg_numero")& " " &RS1("rg_emissor")& "\cell " &RS1("cpf")& "\cell \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid12326642\charrsid5664258 \trowd \irow18\irowband18"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth4680 \cellx3240\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2219 \cellx5459\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone "
  ShowHTML "\clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2025 \cellx7484\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2796 "
  ShowHTML "\cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row }\trowd \irow19\irowband19"
  ShowHTML "\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2927 \cellx1487\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2805 \cellx4292\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl"
  ShowHTML "\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2740 \cellx7032\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth3248 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs12\insrsid12326642\charrsid5664258 BANCO\cell AG\'caNCIA\cell C/C N\'ba\cell \'d3RG\'c3O DE ORIGEM/UNIDADE\cell \cell }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs12\insrsid12326642\charrsid5664258 \trowd \irow19\irowband19"
  ShowHTML "\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2927 \cellx1487\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2805 \cellx4292\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl"
  ShowHTML "\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2740 \cellx7032\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth3248 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row }\trowd \irow20\irowband20"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2927 \cellx1487\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2805 \cellx4292\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone "
  ShowHTML "\clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2740 \cellx7032\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth3248 "
  ShowHTML "\cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {"
  ShowHTML "\fs16\insrsid12326642\charrsid5664258 " &RS1("nm_banco")& "\cell " &RS1("cd_agencia")& "\cell " &RS1("nr_conta")& "\cell \~\cell \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid12326642\charrsid5664258 \trowd \irow20\irowband20"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2927 \cellx1487\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2805 \cellx4292\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone "
  ShowHTML "\clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2740 \cellx7032\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth3248 "
  ShowHTML "\cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row }\trowd \irow21\irowband21"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth5732 "
  ShowHTML "\cellx4292\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth6062 \cellx10354\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {"
  ShowHTML "\b\fs16\insrsid12326642\charrsid5664258 3 - DESCRI\'c7\'c3O SUCINTA DO SERVI\'c7O A SER EXECUTADO\cell }{\fs16\insrsid12326642\charrsid5664258 \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {"
  ShowHTML "\fs16\insrsid12326642\charrsid5664258 \trowd \irow21\irowband21\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone "
  ShowHTML "\clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth5732 \cellx4292\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth6062 \cellx10354\row }\trowd \irow22\irowband22"
  ShowHTML "\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth11720 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid12326642\charrsid5664258 Objetivo/Assunto a ser tratado/Evento\cell \cell }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid12326642\charrsid5664258 \trowd \irow22\irowband22"
  ShowHTML "\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth11720\clshdrawnil \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\row }\trowd \irow23\irowband23"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvmgf\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrtbl \clbrdrr\brdrs\brdrw10 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth11720\clshdrawnil \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\b\fs16\insrsid9440179\charrsid5664258 " & RS("descricao")
  ShowHTML "\par }{\fs16\insrsid9440179\charrsid5664258 \~"
  ShowHTML "\par }{\b\fs16\insrsid9440179\charrsid5664258 \~"
  ShowHTML "\par \~}{\fs16\insrsid9440179\charrsid5664258 \cell \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid9440179\charrsid5664258 \trowd \irow23\irowband23"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvmgf\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrtbl \clbrdrr\brdrs\brdrw10 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth11720\clshdrawnil \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\row }\trowd \irow24\irowband24"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvmrg\clvertalb\clbrdrt\brdrtbl \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrtbl \clbrdrr\brdrs\brdrw10 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth11720\clshdrawnil \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid9440179\charrsid5664258 \cell \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {"
  ShowHTML "\fs16\insrsid9440179\charrsid5664258 \trowd \irow24\irowband24\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvmrg\clvertalb\clbrdrt\brdrtbl \clbrdrl\brdrs\brdrw10 \clbrdrb"
  ShowHTML "\brdrtbl \clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth11720\clshdrawnil \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354"
  ShowHTML "\row }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\b\fs16\insrsid9440179\charrsid5664258 \cell }{\fs16\insrsid9440179\charrsid5664258 \cell }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid9440179\charrsid5664258 \trowd \irow25\irowband25"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvmrg\clvertalb\clbrdrt\brdrtbl \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrtbl \clbrdrr\brdrs\brdrw10 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth11720\clshdrawnil \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\row }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\b\fs16\insrsid9440179\charrsid5664258 \cell }{\fs16\insrsid9440179\charrsid5664258 \cell }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid9440179\charrsid5664258 \trowd \irow26\irowband26"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvmrg\clvertalb\clbrdrt\brdrtbl \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrtbl \clbrdrr\brdrs\brdrw10 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth11720\clshdrawnil \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\row }\trowd \irow27\irowband27"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvmrg\clvertalb\clbrdrt\brdrtbl \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth11720\clshdrawnil \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\b\fs16\insrsid9440179\charrsid5664258 \cell }{\f1\fs16\insrsid9440179\charrsid5664258 \cell }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid9440179\charrsid5664258 \trowd \irow27\irowband27"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvmrg\clvertalb\clbrdrt\brdrtbl \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth11720\clshdrawnil \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\row }\trowd \irow28\irowband28"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth4680\clshdrawnil \cellx3240\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth7040\clshdrawnil \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl"
  ShowHTML "\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone"
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\b\fs16\insrsid12326642\charrsid5664258"
  ShowHTML "4 - BENEF\'cdCIOS RECEBIDOS PELO SERVIDOR\cell }{\fs16\insrsid12326642\charrsid5664258 \cell }{\f1\fs16\insrsid12326642\charrsid5664258 \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {"
  ShowHTML "\fs16\insrsid12326642\charrsid5664258 \trowd \irow28\irowband28\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone "
  ShowHTML "\clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth4680\clshdrawnil \cellx3240\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth7040\clshdrawnil \cellx10280\clvertalb\clbrdrt"
  ShowHTML "\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\row }\trowd \irow29\irowband29"
  ShowHTML "\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\clcfpat8\clcbpat8\clbgdcross\cltxlrtb\clNoWrap\clftsWidth3\clwWidth5732\clcbpatraw8\clcfpatraw8\clbgdcross \cellx4292\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth3192\clshdrawnil \cellx7484\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\clcfpat8\clcbpat8\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2796\clcbpatraw8\clcfpatraw8 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\pard "
  If cDbl(Nvl(RS("valor_alimentacao"),0)) > 0 Then
     ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs14\insrsid12326642\charrsid2128030  AUX\'cdLIO-ALIMENTA\'c7\'c3O       SIM ( x )   N\'c3O (   )     -                  \cell }\pard "
  Else
     ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs14\insrsid12326642\charrsid2128030  AUX\'cdLIO-ALIMENTA\'c7\'c3O       SIM (   )   N\'c3O ( X )     -                  \cell }\pard "
  End If
  ShowHTML "\qc \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid12326642\charrsid5664258 Valor R$\cell }\pard \qr \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid16481867 {"
  ShowHTML "\fs16\insrsid16481867 " & FormatNumber(Nvl(RS("valor_alimentacao"),0),2)& "}{\fs16\insrsid12326642\charrsid5664258 \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\f1\fs16\insrsid12326642\charrsid5664258 \cell }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid12326642\charrsid5664258 \trowd \irow29\irowband29"
  ShowHTML "\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\clcfpat8\clcbpat8\clbgdcross\cltxlrtb\clNoWrap\clftsWidth3\clwWidth5732\clcbpatraw8\clcfpatraw8\clbgdcross \cellx4292\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth3192\clshdrawnil \cellx7484\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\clcfpat8\clcbpat8\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2796\clcbpatraw8\clcfpatraw8 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\row "
  ShowHTML "}\trowd \irow30\irowband30\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone "
  ShowHTML "\clcfpat8\clcbpat8\clbgdcross\cltxlrtb\clNoWrap\clftsWidth3\clwWidth5732\clcbpatraw8\clcfpatraw8\clbgdcross \cellx4292\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth3192\clshdrawnil \cellx7484\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\clcfpat8\clcbpat8\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2796\clcbpatraw8\clcfpatraw8 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\pard "
  If cDbl(Nvl(RS("valor_transporte"),0)) > 0 Then
     ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs14\insrsid12326642\charrsid2128030 AUX\'cdLIO-TRANSPORTE          SIM ( x )   N\'c3O (   )     -                  \cell }\pard "
  Else
     ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs14\insrsid12326642\charrsid2128030 AUX\'cdLIO-TRANSPORTE          SIM (   )   N\'c3O ( X )     -                  \cell }\pard "
  End If
  ShowHTML "\qc \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid12326642\charrsid5664258 Valor R$\cell }\pard \qr \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid16481867 {"
  ShowHTML "\fs16\insrsid16481867 " & FormatNumber(Nvl(RS("valor_transporte"),0),2)& "}{\fs16\insrsid12326642\charrsid5664258 \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\f1\fs16\insrsid12326642\charrsid5664258 \cell }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid12326642\charrsid5664258 \trowd \irow30\irowband30"
  ShowHTML "\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone "
  ShowHTML "\clcfpat8\clcbpat8\clbgdcross\cltxlrtb\clNoWrap\clftsWidth3\clwWidth5732\clcbpatraw8\clcfpatraw8\clbgdcross \cellx4292\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth3192\clshdrawnil \cellx7484\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\clcfpat8\clcbpat8\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2796\clcbpatraw8\clcfpatraw8 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\row "
  ShowHTML "}\trowd \irow31\irowband31\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth5040\clshdrawnil \cellx3600\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1080\clshdrawnil \cellx4680\clvertalb\clbrdrt\brdrnone \clbrdrl"
  ShowHTML "\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1080\clshdrawnil \cellx5760\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth4520\clshdrawnil "
  ShowHTML "\cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 "
  ShowHTML "{\b\fs16\insrsid12326642\charrsid5664258 5 - DADOS DA VIAGEM/ C\'c1LCULO DAS DI\'c1RIAS\cell }{\fs16\insrsid12326642\charrsid5664258 \cell }{\b\fs16\insrsid12326642\charrsid5664258 \cell }{\fs16\insrsid12326642\charrsid5664258 \cell }{"
  ShowHTML "\f1\fs16\insrsid12326642\charrsid5664258 \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid12326642\charrsid5664258 \trowd \irow31\irowband31"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth5040\clshdrawnil \cellx3600\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1080\clshdrawnil \cellx4680\clvertalb\clbrdrt\brdrnone \clbrdrl"
  ShowHTML "\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1080\clshdrawnil \cellx5760\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth4520\clshdrawnil "
  ShowHTML "\cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\row }\trowd \irow32\irowband32"
  ShowHTML "\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth4037\clshdrawnil \cellx2597\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1003\clshdrawnil \cellx3600\clvertalb\clbrdrt"
  ShowHTML "\brdrs\brdrw10 \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1080\clshdrawnil \cellx4680\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth1080\clshdrawnil \cellx5760\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1724\clshdrawnil \cellx7484\clvertalb\clbrdrt"
  ShowHTML "\brdrs\brdrw10 \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2796\clshdrawnil \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\pard \qc \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs12\insrsid12326642\charrsid5664258 \~\cell DATA DE\cell DATA DE\cell QUANTIDADE\cell "
  ShowHTML "VALOR UNIT\'c1RIO\cell TOTAL POR\cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\f1\fs12\insrsid12326642\charrsid5664258 \cell }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs12\insrsid12326642\charrsid5664258 \trowd \irow32\irowband32"
  ShowHTML "\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth4037\clshdrawnil \cellx2597\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1003\clshdrawnil \cellx3600\clvertalb\clbrdrt"
  ShowHTML "\brdrs\brdrw10 \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1080\clshdrawnil \cellx4680\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth1080\clshdrawnil \cellx5760\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1724\clshdrawnil \cellx7484\clvertalb\clbrdrt"
  ShowHTML "\brdrs\brdrw10 \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2796\clshdrawnil \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\row }\trowd \irow33\irowband33\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone "
  ShowHTML "\clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth4037\clshdrawnil \cellx2597\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth1003\clshdrawnil \cellx3600\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1080\clshdrawnil \cellx4680\clvertalb\clbrdrt\brdrnone "
  ShowHTML "\clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1080\clshdrawnil \cellx5760\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth1724\clshdrawnil \cellx7484\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2796\clshdrawnil \cellx10280\clvertalb\clbrdrt"
  ShowHTML "\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\pard \qc \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {"
  ShowHTML "\fs12\insrsid12326642\charrsid5664258 DESTINOS\cell IN\'cdCIO\cell T\'c9RMINO\cell DE DI\'c1RIAS\cell R$\cell LOCALIDADE - R$\cell }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {"
  ShowHTML "\f1\fs12\insrsid12326642\charrsid5664258 \cell }\pard"
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0" 
  ShowHTML "{\fs12\insrsid12326642\charrsid5664258 \trowd \irow33\irowband33"   
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth4037\clshdrawnil \cellx2597\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1003\clshdrawnil \cellx3600\clvertalb\clbrdrt\brdrnone "
  ShowHTML "\clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1080\clshdrawnil \cellx4680\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth1080\clshdrawnil \cellx5760\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1724\clshdrawnil \cellx7484\clvertalb\clbrdrt"
  ShowHTML "\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2796\clshdrawnil \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\row }"
  DB_GetPD_Deslocamento RS2, w_chave, null, "DADFIN"
  RS2.Sort = "saida, chegada"
  If Not RS2.EOF Then  
     w_total = 0
     While Not RS2.EOF
        ShowHTML "\trowd \irow34\irowband34\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrs\brdrw10 "
        ShowHTML "\clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth4037\clshdrawnil \cellx2597\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 "
        ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth1003\clshdrawnil \cellx3600\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1080\clshdrawnil \cellx4680\clvertalb\clbrdrt\brdrnone "
        ShowHTML "\clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \clcfpat8\clcbpat8\clbgdcross\cltxlrtb\clNoWrap\clftsWidth3\clwWidth1080\clcbpatraw8\clcfpatraw8\clbgdcross \cellx5760\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrnone \clbrdrb"
        ShowHTML "\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1724\clshdrawnil \cellx7484\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 "
        ShowHTML "\clcfpat8\clcbpat16\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2796\clcbpatraw16\clcfpatraw8 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\pard "     
        ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid16481867\charrsid5664258 " &RS2("nm_destino")& "\cell }\pard \qc \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {"
        ShowHTML "\fs16\insrsid16481867\charrsid5664258 " & FormataDataEdicao(FormatDateTime(RS2("saida"),2)) & "\cell }\pard \qc \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid6649499 {\fs16\insrsid16481867\charrsid5664258 " & FormataDataEdicao(FormatDateTime(RS2("chegada"),2)) & "\cell }\pard "
        ShowHTML "\qr \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid16481867\charrsid5664258 " & FormatNumber(Nvl(RS2("quantidade"),0),1) & "\cell " & FormatNumber(Nvl(RS2("valor"),0),2) & "\cell " & FormatNumber(cDbl(FormatNumber(Nvl(RS2("quantidade"),0),1)) * cDbl(FormatNumber(Nvl(RS2("valor"),0),2)),2) & "\cell }\pard "
        ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\f1\fs16\insrsid16481867\charrsid5664258 \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 "
        ShowHTML "{ \fs16\insrsid16481867\charrsid5664258 \trowd \irow34\irowband34\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb"
        ShowHTML "\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth4037\clshdrawnil \cellx2597\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1003\clshdrawnil "
        ShowHTML "\cellx3600\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1080\clshdrawnil \cellx4680\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr"
        ShowHTML "\brdrs\brdrw10 \clcfpat8\clcbpat8\clbgdcross\cltxlrtb\clNoWrap\clftsWidth3\clwWidth1080\clcbpatraw8\clcfpatraw8\clbgdcross \cellx5760\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 "
        ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth1724\clshdrawnil \cellx7484\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \clcfpat8\clcbpat16\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2796\clcbpatraw16\clcfpatraw8 "
        ShowHTML "\cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\row }\pard"
        w_total = w_total + (cDbl(FormatNumber(Nvl(RS2("quantidade"),0),1)) * cDbl(FormatNumber(Nvl(RS2("valor"),0),2)))
        RS2.MoveNext
     wend
  End If
  RS2.Close
  'ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid6649499 {\fs16\insrsid16481867\charrsid5664258 GIG/BSB/GIG\cell }\pard \qc \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid6649499 {"
  'ShowHTML "\fs16\insrsid16481867\charrsid5664258 18/10/2004\cell 18/10/2004\cell }\pard \qr \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid6649499 {\fs16\insrsid16481867\charrsid5664258 0,5\cell 130,57\cell 65,29\cell }\pard "
  'ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\f1\fs16\insrsid16481867\charrsid5664258 \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {"
  'ShowHTML "\fs16\insrsid16481867\charrsid5664258 \trowd \irow35\irowband35\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb"
  'ShowHTML "\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth4037\clshdrawnil \cellx2597\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1003\clshdrawnil "
  'ShowHTML "\cellx3600\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1080\clshdrawnil \cellx4680\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr"
  'ShowHTML "\brdrs\brdrw10 \clcfpat8\clcbpat8\clbgdcross\cltxlrtb\clNoWrap\clftsWidth3\clwWidth1080\clcbpatraw8\clcfpatraw8\clbgdcross \cellx5760\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 "
  'ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth1724\clshdrawnil \cellx7484\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \clcfpat8\clcbpat16\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2796\clcbpatraw16\clcfpatraw8 "
  'ShowHTML "\cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\row }\pard "
  'ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid6649499 {\fs16\insrsid16481867\charrsid5664258 GIG/BSB/GIG\cell }\pard \qc \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid6649499 {"
  'ShowHTML "\fs16\insrsid16481867\charrsid5664258 18/10/2004\cell 18/10/2004\cell }\pard \qr \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid6649499 {\fs16\insrsid16481867\charrsid5664258 0,5\cell 130,57\cell 65,29\cell }\pard "
  'ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\f1\fs16\insrsid16481867\charrsid5664258 \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {"
  'ShowHTML "\fs16\insrsid16481867\charrsid5664258 \trowd \irow36\irowband36\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3\tblrsid16481867 \clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl"
  'ShowHTML "\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth4037\clshdrawnil \cellx2597\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 "
  'ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth1003\clshdrawnil \cellx3600\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1080\clshdrawnil \cellx4680\clvertalb\clbrdrt\brdrnone "
  'ShowHTML "\clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \clcfpat8\clcbpat8\clbgdcross\cltxlrtb\clNoWrap\clftsWidth3\clwWidth1080\clcbpatraw8\clcfpatraw8\clbgdcross \cellx5760\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrnone \clbrdrb"
  'ShowHTML "\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1724\clshdrawnil \cellx7484\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 "
  'ShowHTML "\clcfpat8\clcbpat16\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2796\clcbpatraw16\clcfpatraw8 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\row "
  'ShowHTML "}\trowd \irow37\irowband37\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3\tblrsid16481867 \clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr"
  'ShowHTML "\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth4037\clshdrawnil \cellx2597\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1003\clshdrawnil \cellx3600"
  'ShowHTML "\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1080\clshdrawnil \cellx4680\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr"
  'ShowHTML "\brdrs\brdrw10 \clcfpat8\clcbpat8\clbgdcross\cltxlrtb\clNoWrap\clftsWidth3\clwWidth1080\clcbpatraw8\clcfpatraw8\clbgdcross \cellx5760\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 "
  'ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth1724\clshdrawnil \cellx7484\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \clcfpat8\clcbpat16\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2796\clcbpatraw16\clcfpatraw8 "
  'ShowHTML "\cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid6649499 {"
  'ShowHTML "\fs16\insrsid16481867\charrsid5664258 GIG/BSB/GIG\cell }\pard \qc \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid6649499 {\fs16\insrsid16481867\charrsid5664258 18/10/2004\cell 18/10/2004\cell }\pard "
  'ShowHTML "\qr \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid6649499 {\fs16\insrsid16481867\charrsid5664258 0,5\cell 130,57\cell 65,29\cell }\pard "
  
  'ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 "
  'ShowHTML "{\f1\fs16\insrsid16481867\charrsid5664258 \cell }"
  ShowHTML "\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 "
  ShowHTML "{ \fs16\insrsid16481867\charrsid5664258 \trowd \irow37\irowband37"

  ShowHTML "\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3\tblrsid16481867 \clvertalb\clbrdrt\"

  ShowHTML "\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth4037\clshdrawnil \cellx2597\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth1003\clshdrawnil \cellx3600\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1080\clshdrawnil \cellx4680\clvertalb\clbrdrt"
  ShowHTML "\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \clcfpat8\clcbpat8\clbgdcross\cltxlrtb\clNoWrap\clftsWidth3\clwWidth1080\clcbpatraw8\clcfpatraw8\clbgdcross \cellx5760\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrnone \clbrdrb"
  ShowHTML "\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1724\clshdrawnil \cellx7484\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 "
  ShowHTML "\clcfpat8\clcbpat16\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2796\clcbpatraw16\clcfpatraw8 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil "
  ShowHTML "}\trowd \irow38\irowband38\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth6120\clshdrawnil \cellx4680\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\clcfpat8\clcbpat8\clbgdcross\cltxlrtb\clNoWrap\clftsWidth3\clwWidth1080\clcbpatraw8\clcfpatraw8\clbgdcross \cellx5760\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth1272\clshdrawnil \cellx7032\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth452\clshdrawnil \cellx7484\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl"
  ShowHTML "\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 \clcbpat16\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2796\clcbpatraw16 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid16481867\charrsid2128030 (\'c9 obrigat\'f3rio justificar, neste campo, in\'ed"
  ShowHTML "cio e t\'e9rmino de viagens "
  ShowHTML "\par }{\fs16\insrsid16481867\charrsid13388689 sextas-feiras, s\'e1bados, domigos e feriados)"
  ShowHTML "\par }{\fs18\insrsid16481867 \charrsid13388689 " & Nvl(RS("justificativa_dia_util"),"---")
  ShowHTML "\par }{\fs18\insrsid16481867\charrsid13388689 \~}{\fs16\insrsid16481867\charrsid2128030 \cell }{\fs16\insrsid16481867\charrsid5664258 \~\cell }\pard \qc \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {"
  ShowHTML "\fs16\insrsid16481867\charrsid5664258 \~\cell \~\cell }\pard \qr \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid6649499 {\fs16\insrsid16481867\charrsid5664258 \cell }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\f1\fs16\insrsid16481867\charrsid5664258 \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {"
  ShowHTML "\fs18\insrsid16481867\charrsid2128030 \trowd \irow38\irowband38\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvmgf\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb"
  ShowHTML "\brdrtbl \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth6120\clshdrawnil \cellx4680\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\clcfpat8\clcbpat8\clbgdcross\cltxlrtb\clNoWrap\clftsWidth3\clwWidth1080\clcbpatraw8\clcfpatraw8\clbgdcross \cellx5760\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth1272\clshdrawnil \cellx7032\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth452\clshdrawnil \cellx7484\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl"
  ShowHTML "\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 \clcbpat16\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2796\clcbpatraw16 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\row }\trowd \irow39\irowband39\ts11\trrh164\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvmrg\clvertalb\clbrdrt\brdrtbl "
  ShowHTML "\clbrdrl\brdrs\brdrw10 \clbrdrb\brdrtbl \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth6120\clshdrawnil \cellx4680\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2804\clshdrawnil \cellx7484\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10 \clcfpat8\clcbpat16\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2796\clcbpatraw16\clcfpatraw8 "
  ShowHTML "\cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 "
  ShowHTML "{\fs16\insrsid16481867\charrsid13388689 \cell }{\fs16\insrsid16481867\charrsid5664258 (a) subtotal\cell }\pard \qr \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid16481867\charrsid5664258 " & FormatNumber(Nvl(w_total,0),2)& "\cell "
  ShowHTML "}\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\f1\fs16\insrsid16481867\charrsid5664258 \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {"
  ShowHTML "\fs16\insrsid16481867\charrsid5664258 \trowd \irow39\irowband39\ts11\trrh164\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvmrg\clvertalb\clbrdrt\brdrtbl \clbrdrl\brdrs\brdrw10 \clbrdrb"
  ShowHTML "\brdrtbl \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth6120\clshdrawnil \cellx4680\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2804\clshdrawnil \cellx7484"
  ShowHTML "\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10 \clcfpat8\clcbpat16\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2796\clcbpatraw16\clcfpatraw8 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone "
  ShowHTML "\clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\row }\trowd \irow40\irowband40\ts11\trrh119\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 "
  ShowHTML "\clvmrg\clvertalb\clbrdrt\brdrtbl \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrtbl \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth6120\clshdrawnil \cellx4680\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2804\clshdrawnil \cellx7484\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \clcfpat8\clcbpat8\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2796\clcbpatraw8\clcfpatraw8 "
  ShowHTML "\cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 "
  ShowHTML "{\fs18\insrsid16481867 \cell }{\fs16\insrsid16481867\charrsid5664258 (b) adicional\cell }\pard \qr \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid16481867\charrsid5664258 " & FormatNumber(Nvl(RS("valor_adicional"),0),2)& "\cell }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\f1\fs16\insrsid16481867\charrsid5664258 \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {"
  ShowHTML "\fs16\insrsid16481867\charrsid5664258 \trowd \irow40\irowband40\ts11\trrh119\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvmrg\clvertalb\clbrdrt\brdrtbl \clbrdrl\brdrs\brdrw10 \clbrdrb"
  ShowHTML "\brdrtbl \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth6120\clshdrawnil \cellx4680\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2804\clshdrawnil \cellx7484"
  ShowHTML "\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \clcfpat8\clcbpat8\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2796\clcbpatraw8\clcfpatraw8 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb"
  ShowHTML "\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\row }\trowd \irow41\irowband41\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 "
  ShowHTML "\clvmrg\clvertalb\clbrdrt\brdrtbl \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrtbl \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth6120\clshdrawnil \cellx4680\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2804\clshdrawnil \cellx7484\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \clcfpat8\clcbpat8\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2796\clcbpatraw8\clcfpatraw8 "
  ShowHTML "\cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 "
  ShowHTML "{\fs18\insrsid16481867\charrsid13388689 \cell }{\fs16\insrsid16481867\charrsid5664258 (c) desconto aux\'edlio-alimenta\'e7\'e3o\cell }\pard \qr \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid6649499 {"
  ShowHTML "\fs16\insrsid16481867\charrsid5664258 " & FormatNumber(Nvl(RS("desconto_alimentacao"),0),2)& "\cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\f1\fs16\insrsid16481867\charrsid5664258 \cell }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid16481867\charrsid5664258 \trowd \irow41\irowband41"
  ShowHTML "\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvmrg\clvertalb\clbrdrt\brdrtbl \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrtbl \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth6120\clshdrawnil \cellx4680\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2804\clshdrawnil \cellx7484\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl"
  ShowHTML "\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \clcfpat8\clcbpat8\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2796\clcbpatraw8\clcfpatraw8 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\row }\trowd \irow42\irowband42\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvmrg\clvertalb\clbrdrt\brdrtbl "
  ShowHTML "\clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth6120\clshdrawnil \cellx4680\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2804\clshdrawnil \cellx7484\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \clcfpat8\clcbpat8\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2796\clcbpatraw8\clcfpatraw8 "
  ShowHTML "\cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 "
  ShowHTML "{\fs18\insrsid16481867\charrsid13388689 \cell }{\fs16\insrsid16481867\charrsid5664258 (d) desconto aux\'edlio-transporte\cell }\pard \qr \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid6649499 {"
  ShowHTML "\fs16\insrsid16481867\charrsid5664258 " & FormatNumber(Nvl(RS("desconto_transporte"),0),2)& "\cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\f1\fs16\insrsid16481867\charrsid5664258 \cell }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid16481867\charrsid5664258 \trowd \irow42\irowband42"
  ShowHTML "\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvmrg\clvertalb\clbrdrt\brdrtbl \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth6120\clshdrawnil \cellx4680\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2804\clshdrawnil \cellx7484\clvertalb\clbrdrt\brdrnone \clbrdrl"
  ShowHTML "\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \clcfpat8\clcbpat8\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2796\clcbpatraw8\clcfpatraw8 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\row }\trowd \irow43\irowband43\ts11\trrh195\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone "
  ShowHTML "\clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2182\clshdrawnil \cellx742\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth745\clshdrawnil \cellx1487\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1110\clshdrawnil \cellx2597\clvertalb\clbrdrt\brdrnone \clbrdrl"
  ShowHTML "\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth643\clshdrawnil \cellx3240\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth1440\clshdrawnil \cellx4680\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2804\clshdrawnil \cellx7484\clvertalb"
  ShowHTML "\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \clcfpat8\clcbpat16\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2796\clcbpatraw16\clcfpatraw8 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone "
  ShowHTML "\clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid16481867\charrsid5664258 \~}{"
  ShowHTML "\f1\fs16\insrsid16481867\charrsid5664258 \cell }{\fs16\insrsid16481867\charrsid5664258 \~}{\f1\fs16\insrsid16481867\charrsid5664258 \cell }{\fs16\insrsid16481867\charrsid5664258 \~}{\f1\fs16\insrsid16481867\charrsid5664258 \cell }{"
  ShowHTML "\fs16\insrsid16481867\charrsid5664258 \~}{\f1\fs16\insrsid16481867\charrsid5664258 \cell }{\fs16\insrsid16481867\charrsid5664258 \~}{\f1\fs16\insrsid16481867\charrsid5664258 \cell }{\b\fs12\insrsid16481867\charrsid5664258  TOTAL (a + b - c - d)\cell "
  ShowHTML "}\pard \qr \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid16481867\charrsid5664258 " & FormatNumber(cDbl(FormatNumber(Nvl(w_total,0),2)) + cDbl(FormatNumber(Nvl(RS("valor_adicional"),0),2)) - cDbl(FormatNumber(Nvl(RS("desconto_alimentacao"),0),2)) - cDbl(FormatNumber(Nvl(RS("desconto_transporte"),0),2)),2) & "\cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {"
  ShowHTML "\f1\fs16\insrsid16481867\charrsid5664258 \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid16481867\charrsid5664258 \trowd \irow43\irowband43"
  ShowHTML "\ts11\trrh195\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2182\clshdrawnil \cellx742\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth745\clshdrawnil \cellx1487\clvertalb\clbrdrt\brdrnone \clbrdrl"
  ShowHTML "\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1110\clshdrawnil \cellx2597\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth643\clshdrawnil \cellx3240\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1440\clshdrawnil \cellx4680\clvertalb\clbrdrt"
  ShowHTML "\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2804\clshdrawnil \cellx7484\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 "
  ShowHTML "\clcfpat8\clcbpat16\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2796\clcbpatraw16\clcfpatraw8 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\row "
  ShowHTML "}\trowd \irow44\irowband44\ts11\trrh152\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth6120\clshdrawnil \cellx4680\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2804\clshdrawnil \cellx7484\clvertalb\clbrdrt\brdrnone \clbrdrl"
  ShowHTML "\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \clcbpat8\cltxlrtb\clNoWrap\clftsWidth3\clwWidth596\clcbpatraw8 \cellx8080\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10 "
  ShowHTML "\clcbpat8\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2200\clcbpatraw8 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\pard "
  ShowHTML "\qc \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid16481867\charrsid5664258 \~\cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {"
  ShowHTML "\fs16\insrsid16481867\charrsid5664258 \cell }\pard \qc \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid16481867\charrsid5664258 \~\cell \~\cell }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\f1\fs16\insrsid16481867\charrsid5664258 \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {"
  ShowHTML "\fs16\insrsid16481867\charrsid5664258 \trowd \irow44\irowband44\ts11\trrh152\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb"
  ShowHTML "\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth6120\clshdrawnil \cellx4680\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2804\clshdrawnil \cellx7484\clvertalb"
  ShowHTML "\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \clcbpat8\cltxlrtb\clNoWrap\clftsWidth3\clwWidth596\clcbpatraw8 \cellx8080\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10 "
  ShowHTML "\clcbpat8\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2200\clcbpatraw8 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\row }\trowd \irow45\irowband45"
  ShowHTML "\ts11\trrh132\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth6120\clshdrawnil \cellx4680\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth5600\clshdrawnil \cellx10280\clvertalb\clbrdrt"
  ShowHTML "\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {"
  ShowHTML "\fs14\insrsid16481867\charrsid13388689 DATA:" &FormataDataEdicao(FormatDateTime(Date(),2))& "\cell }{\fs16\insrsid16481867\charrsid5664258 __________________________________________\cell }{\f1\fs16\insrsid16481867\charrsid5664258 \cell }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid16481867\charrsid5664258 \trowd \irow45\irowband45"
  ShowHTML "\ts11\trrh132\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth6120\clshdrawnil \cellx4680\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth5600\clshdrawnil \cellx10280\clvertalb\clbrdrt"
  ShowHTML "\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\row }\trowd \irow46\irowband46"
  ShowHTML "\ts11\trrh102\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth6120\clshdrawnil \cellx4680\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth5600\clshdrawnil \cellx10280\clvertalb\clbrdrt"
  ShowHTML "\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\pard \qc \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {"
  ShowHTML "\fs16\insrsid16481867\charrsid5664258 \~\cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs14\insrsid16481867\charrsid13388689        ASSINATURA/CARIMBO DO PROPONENTE\cell }{"
  ShowHTML "\f1\fs16\insrsid16481867\charrsid5664258 \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid16481867\charrsid5664258 \trowd \irow46\irowband46"
  ShowHTML "\ts11\trrh102\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth6120\clshdrawnil \cellx4680\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth5600\clshdrawnil \cellx10280\clvertalb\clbrdrt"
  ShowHTML "\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\row }\trowd \irow47\irowband47"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2182\clshdrawnil \cellx742\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth745\clshdrawnil \cellx1487\clvertalb\clbrdrt\brdrnone \clbrdrl"
  ShowHTML "\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1110\clshdrawnil \cellx2597\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth643\clshdrawnil \cellx3240\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1440\clshdrawnil \cellx4680\clvertalb\clbrdrt\brdrnone \clbrdrl"
  ShowHTML "\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth779\clshdrawnil \cellx5459\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth1573\clshdrawnil \cellx7032\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth452\clshdrawnil \cellx7484\clvertalb\clbrdrt\brdrnone \clbrdrl"
  ShowHTML "\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth596\clshdrawnil \cellx8080\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2200\clshdrawnil \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid16481867\charrsid5664258 \~\cell \~\cell \~\cell \~\cell \~\cell \~\cell \~\cell \~\cell \~\cell \~\cell }{\f1\fs16\insrsid16481867\charrsid5664258 "
  ShowHTML "\cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid16481867\charrsid5664258 \trowd \irow47\irowband47"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2182\clshdrawnil \cellx742\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth745\clshdrawnil \cellx1487\clvertalb\clbrdrt\brdrnone \clbrdrl"
  ShowHTML "\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1110\clshdrawnil \cellx2597\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth643\clshdrawnil \cellx3240\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1440\clshdrawnil \cellx4680\clvertalb\clbrdrt\brdrnone \clbrdrl"
  ShowHTML "\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth779\clshdrawnil \cellx5459\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth1573\clshdrawnil \cellx7032\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth452\clshdrawnil \cellx7484\clvertalb\clbrdrt\brdrnone \clbrdrl"
  ShowHTML "\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth596\clshdrawnil \cellx8080\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2200\clshdrawnil \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\row }\trowd \irow48\irowband48"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth4037\clshdrawnil \cellx2597\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth7683\clshdrawnil \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl"
  ShowHTML "\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74\clshdrawnil \cellx10354\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\b\fs16\insrsid16481867\charrsid5664258 "
  
  ShowHTML "6 - BILHETE DE PASSAGEM:\cell }{"
  ShowHTML "\fs16\insrsid12326642\charrsid5664258 \cell }{\f1\fs16\insrsid12326642\charrsid5664258 \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid12326642\charrsid5664258 \trowd \irow48\irowband48"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth4037 "
  ShowHTML "\cellx2597\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth7683 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row }\trowd \irow49\irowband49\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl"
  ShowHTML "\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth11720 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354"
  ShowHTML "\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs12\insrsid12326642\charrsid5664258 RESERVA EFETUADA COM O MENOR PRE\'c7O\cell }{\f1\fs12\insrsid12326642\charrsid5664258 \cell }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs12\insrsid12326642\charrsid5664258 \trowd \irow49\irowband49"
  ShowHTML "\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth11720 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row }\trowd \irow50\irowband50"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2182 \cellx742\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth7338 \cellx8080\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone "
  ShowHTML "\clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2200 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs12\insrsid12326642\charrsid5664258 \~\cell \cell \~\cell }{\f1\fs12\insrsid12326642\charrsid5664258 \cell }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs12\insrsid12326642\charrsid5664258 \trowd \irow50\irowband50"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2182 \cellx742\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth7338 \cellx8080\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone "
  ShowHTML "\clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2200 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row }\trowd \irow51\irowband51"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth11720 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs12\insrsid12326642\charrsid5664258  (  ) EMISS\'c3O LO"
  ShowHTML "CAL                                       (   ) PTA:  ______________________________________                                                        \cell }{\f1\fs12\insrsid12326642\charrsid5664258 \cell }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs12\insrsid12326642\charrsid5664258 \trowd \irow51\irowband51"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth11720 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs12\insrsid12326642\charrsid5664258 \~\cell }{\f1\fs12\insrsid12326642\charrsid5664258 \cell }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs12\insrsid12326642\charrsid5664258 \trowd \irow52\irowband52"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth11720 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row }\trowd \irow53\irowband53"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth5732 \cellx4292\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1167 \cellx5459\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone "
  ShowHTML "\clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth4821 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid12326642\charrsid5664258 DATA e HOR\'c1RIO:                    IDA:                              \cell \cell VOLTA:                                 "
  ShowHTML "\cell }{\f1\fs16\insrsid12326642\charrsid5664258 \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid12326642\charrsid5664258 \trowd \irow53\irowband53"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth5732 \cellx4292\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1167 \cellx5459\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone "
  ShowHTML "\clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth4821 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row }\trowd \irow54\irowband54"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2182 \cellx742\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth745 \cellx1487\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone "
  ShowHTML "\clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1110 \cellx2597\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth5483 \cellx8080\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone" 
  ShowHTML "\clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2200 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid12326642\charrsid5664258 \~\cell \cell \cell \cell \~\cell }{\f1\fs16\insrsid12326642\charrsid5664258 \cell }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid12326642\charrsid5664258 \trowd \irow54\irowband54"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2182 \cellx742\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth745 \cellx1487\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone "
  ShowHTML "\clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1110 \cellx2597\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth5483 \cellx8080\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone "
  ShowHTML "\clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2200 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row "
  ShowHTML "}\trowd \irow55\irowband55\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth4037 \cellx2597\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth643 \cellx3240\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone "
  ShowHTML "\clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth3600 \cellx6840\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth644 \cellx7484\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone "
  ShowHTML "\clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth596 \cellx8080\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2200 \cellx10280\clvertalb\clbrdrt"
  ShowHTML "\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid12326642\charrsid5664258 V"
  ShowHTML "\'f4o:                                                                  }{\v\fs16\insrsid12326642\charrsid5664258                                                                      }{\fs16\insrsid12326642\charrsid5664258 \cell        \cell \cell        "
  ShowHTML "\cell \cell C\'d3D.:       \cell }{\f1\fs16\insrsid12326642\charrsid5664258 \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid12326642\charrsid5664258 \trowd \irow55\irowband55"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth4037 \cellx2597\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth643 \cellx3240\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone "
  ShowHTML "\clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth3600 \cellx6840\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth644 \cellx7484\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone "
  ShowHTML "\clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth596 \cellx8080\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2200 \cellx10280\clvertalb\clbrdrt"
  ShowHTML "\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row }\trowd \irow56\irowband56"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2182 \cellx742\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth7338 \cellx8080\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone "
  ShowHTML "\clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2200 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid12326642\charrsid5664258 \~\cell \cell \~\cell }{\f1\fs16\insrsid12326642\charrsid5664258 \cell }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid12326642\charrsid5664258 \trowd \irow56\irowband56"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2182 \cellx742\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth7338 \cellx8080\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone "
  ShowHTML "\clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2200 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row }\trowd \irow57\irowband57"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth4680 \cellx3240\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1052 \cellx4292\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone "
  ShowHTML "\clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth3788 \cellx8080\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2200 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl"
  ShowHTML "\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid12326642\charrsid5664258 "
  ShowHTML "Valor da passagem (num\'e9rico e por extenso):\cell \cell \cell \~\cell }{\f1\fs16\insrsid12326642\charrsid5664258 \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid12326642\charrsid5664258 "
  ShowHTML "\trowd \irow57\irowband57\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth4680 \cellx3240\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth1052 \cellx4292\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone "
  ShowHTML "\clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth3788 \cellx8080\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2200 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl"
  ShowHTML "\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row }\trowd \irow58\irowband58\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3"
  ShowHTML "\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2182 \cellx742\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth7338 \cellx8080\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2200 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb"
  ShowHTML "\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid12326642\charrsid5664258 \~\cell \cell \~\cell }{"
  ShowHTML "\f1\fs16\insrsid12326642\charrsid5664258 \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid12326642\charrsid5664258 \trowd \irow58\irowband58"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2182 \cellx742\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth7338 \cellx8080\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone "
  ShowHTML "\clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2200 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row }\trowd \irow59\irowband59"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth4680 \cellx3240\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrtbl \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth7040 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone "
  ShowHTML "\clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs14\insrsid12326642\charrsid13388689 "
  ShowHTML "DATA:  ________/____________/___________\cell }\pard \qc \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid12326642\charrsid5664258 __________________________________________________\cell }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\f1\fs16\insrsid12326642\charrsid5664258 \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {"
  ShowHTML "\fs16\insrsid12326642\charrsid5664258 \trowd \irow59\irowband59\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb"
  ShowHTML "\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth4680 \cellx3240\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrtbl \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth7040 \cellx10280\clvertalb\clbrdrt"
  ShowHTML "\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row }\trowd \irow60\irowband60"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2182 \cellx742\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2498 \cellx3240\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb"
  ShowHTML "\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth7040 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard "
  ShowHTML "\qc \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid1071686\charrsid5664258 \cell \cell }{\fs14\insrsid1071686\charrsid13388689 ASSINATURA e CARIMBO\cell }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\f1\fs16\insrsid1071686\charrsid5664258 \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {"
  ShowHTML "\fs16\insrsid1071686\charrsid14172830 \trowd \irow60\irowband60\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb"
  ShowHTML "\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2182 \cellx742\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2498 \cellx3240\clvertalb\clbrdrt\brdrnone "
  ShowHTML "\clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth7040 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 "
  ShowHTML "\cellx10354\row }\trowd \irow61\irowband61\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrtbl \clbrdrb\brdrtbl \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2182 \cellx742\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2498 \cellx3240\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrnone "
  ShowHTML "\clbrdrb\brdrtbl \clbrdrr\brdrtbl \cltxlrtb\clNoWrap\clftsWidth3\clwWidth7040 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard "
  ShowHTML "\qc \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid12326642\charrsid5664258 \cell \cell }{\fs14\insrsid12326642\charrsid13388689 \cell }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\f1\fs16\insrsid12326642\charrsid5664258 \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {"
  ShowHTML "\fs16\insrsid12326642\charrsid14172830 \trowd \irow61\irowband61\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrtbl \clbrdrb"
  ShowHTML "\brdrtbl \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2182 \cellx742\clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2498 \cellx3240\clvertalb\clbrdrt"
  ShowHTML "\brdrs\brdrw10 \clbrdrl\brdrnone \clbrdrb\brdrtbl \clbrdrr\brdrtbl \cltxlrtb\clNoWrap\clftsWidth3\clwWidth7040 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 "
  ShowHTML "\cellx10354\row }\trowd \irow62\irowband62\ts11\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth11720 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\v\insrsid12326642 \cell }{\v\f1\insrsid12326642 \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\v\insrsid12326642 "
  ShowHTML "\trowd \irow62\irowband62\ts11\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth11720 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row }\trowd \irow63\irowband63"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2927 "
  ShowHTML "\cellx1487\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth8793 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\b\fs16\insrsid12326642\charrsid14172830 7  - CONCESS\'c3O\cell }{"
  ShowHTML "\fs16\insrsid12326642\charrsid14172830 \cell }{\f1\fs16\insrsid12326642\charrsid14172830 \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid12326642\charrsid14172830 \trowd \irow63\irowband63"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2927 "
  ShowHTML "\cellx1487\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth8793 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row }\trowd \irow64\irowband64\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl"
  ShowHTML "\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth11720 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354"
  ShowHTML "\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs12\insrsid12326642\charrsid14172830 NA QUALIDADE DE ORDENADOR DE DESPESA AUTORIZO O PAGAMENTO DA(S) DI\'c1RIA(S) E EMISS\'c3O DA REQUISI\'c7\'c3"
  ShowHTML "O DE TRANSPORTE POR VIA\cell }{\f1\fs12\insrsid12326642\charrsid14172830 \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs12\insrsid12326642\charrsid14172830 \trowd \irow64\irowband64"
  ShowHTML "\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth11720 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row }\trowd \irow65\irowband65"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth11720 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs12\insrsid12326642\charrsid14172830  (    ) TERRESTRE                         (     ) A\'c9REA                                  RT N\'ba"
  ShowHTML "______________                                                             DATA: ____/____/________     \cell }{\f1\fs12\insrsid12326642\charrsid14172830 \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {"
  ShowHTML "\fs12\insrsid12326642\charrsid14172830 \trowd \irow65\irowband65\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb"
  ShowHTML "\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth11720 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row "
  ShowHTML "}\trowd \irow66\irowband66\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2182 \cellx742\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth7338 \cellx8080\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone "
  ShowHTML "\clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2200 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid12326642\charrsid14172830 \~\cell \cell \~\cell }{\f1\fs16\insrsid12326642\charrsid14172830 \cell }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid12326642\charrsid14172830 \trowd \irow66\irowband66"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2182 \cellx742\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth7338 \cellx8080\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone "
  ShowHTML "\clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2200 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row }\trowd \irow67\irowband67"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrtbl \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2182 "
  ShowHTML "\cellx742\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrtbl \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2498 \cellx3240\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrtbl \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth7040 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid12326642\charrsid14172830 \~\cell \cell                                    __________________________________________________\cell }{"
  ShowHTML "\f1\fs16\insrsid12326642\charrsid14172830 \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid12326642\charrsid14172830 \trowd \irow67\irowband67"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrtbl \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2182 "
  ShowHTML "\cellx742\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrtbl \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2498 \cellx3240\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrtbl \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth7040 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row }\trowd \irow68\irowband68"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2182 \cellx742\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2498 \cellx3240\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb"
  ShowHTML "\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth7040 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid12326642\charrsid14172830 \~\cell \cell }{\fs14\insrsid12326642\charrsid15559966 "
  ShowHTML "                                                      ASSINATURA/CARIMBO DO ORDENADOR DE DESPESAS\cell }{\f1\fs16\insrsid12326642\charrsid14172830 \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {"
  ShowHTML "\fs18\insrsid12326642\charrsid15559966 \trowd \irow68\irowband68\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb"
  ShowHTML "\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2182 \cellx742\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2498 \cellx3240\clvertalb\clbrdrt\brdrnone "
  ShowHTML "\clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth7040 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 "
  ShowHTML "\cellx10354\row }\trowd \irow69\irowband69\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth4680 \cellx3240\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth7040 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone "
  ShowHTML "\clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\b\fs16\insrsid12326642\charrsid14172830 8 - SETOR FINANCEIRO/ PUBLICA\'c7\'c3O\cell }"
  ShowHTML "{\fs16\insrsid12326642\charrsid14172830 \cell }{\f1\fs16\insrsid12326642\charrsid14172830 \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid12326642\charrsid14172830 \trowd \irow69\irowband69"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth4680 "
  ShowHTML "\cellx3240\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth7040 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row }\trowd \irow70\irowband70\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl"
  ShowHTML "\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth11720 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354"
  ShowHTML "\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs12\insrsid12326642\charrsid14172830 O PAGAMENTO DO VALOR ACIMA FOI EFETIVADO MEDIANTE ORDEM BANC\'c1RIA N\'ba"
  ShowHTML " _______________ ,       DE ____/____/________\cell }{\f1\fs12\insrsid12326642\charrsid14172830 \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs12\insrsid12326642\charrsid14172830 \trowd \irow70\irowband70"
  ShowHTML "\ts11\trrh90\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth11720 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row }\trowd \irow71\irowband71"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2182 \cellx742\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth7338 \cellx8080\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone "
  ShowHTML "\clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2200 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid12326642\charrsid14172830 \~\cell \cell \~\cell }{\f1\fs16\insrsid12326642\charrsid14172830 \cell }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid12326642\charrsid14172830 \trowd \irow71\irowband71"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2182 \cellx742\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth7338 \cellx8080\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone "
  ShowHTML "\clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2200 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid12326642\charrsid14172830 \~\cell \cell \~\cell }{\f1\fs16\insrsid12326642\charrsid14172830 \cell }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid12326642\charrsid14172830 \trowd \irow72\irowband72"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2182 \cellx742\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth7338 \cellx8080\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone "
  ShowHTML "\clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2200 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row }\trowd \irow73\irowband73"
  ShowHTML "\ts11\trrh30\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth11720 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs12\insrsid12326642\charrsid14172830 O PRESENTE DOCUMENTO EST\'c1 DE ACORDO COM AS NORMAS REGULAMENTARES E SER\'c1 PUBLICADO, NOS   TERMOS   DA   LEGISLA\'c7\'c3"
  ShowHTML "O    EM\cell }{\f1\fs12\insrsid12326642\charrsid14172830 \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs12\insrsid12326642\charrsid14172830 \trowd \irow73\irowband73"
  ShowHTML "\ts11\trrh30\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrs\brdrw10\brdrcf1 "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth11720 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row }\trowd \irow74\irowband74"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth6899 \cellx5459\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2621 \cellx8080\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone "
  ShowHTML "\clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2200 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard" 
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs12\insrsid12326642\charrsid14172830 VIGOR, NO BOLETIM DE SERVI\'c7O N\'ba _________________  DE ____/____/_________\cell \cell \~\cell }{"
  ShowHTML "\f1\fs12\insrsid12326642\charrsid14172830 \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs12\insrsid12326642\charrsid14172830 \trowd \irow74\irowband74"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth6899 \cellx5459\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2621 \cellx8080\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone "
  ShowHTML "\clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2200 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row }\trowd \irow75\irowband75"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2182 \cellx742\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth7338 \cellx8080\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone "
  ShowHTML "\clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2200 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid12326642\charrsid14172830 \~\cell \cell \~\cell }{\f1\fs16\insrsid12326642\charrsid14172830 \cell }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs16\insrsid12326642\charrsid14172830 \trowd \irow75\irowband75"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2182 \cellx742\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth7338 \cellx8080\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone "
  ShowHTML "\clbrdrr\brdrs\brdrw10 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth2200 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row }\trowd \irow76\irowband76"
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrnone \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth3780 \cellx2340\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrtbl \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth7940 \cellx10280\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone "
  ShowHTML "\clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs14\insrsid12326642\charrsid14172830 "
  ShowHTML "DATA: ____/____/________                            }{\v\fs14\insrsid12326642\charrsid14172830                          }{\fs14\insrsid12326642\charrsid14172830 \cell }\pard "
  ShowHTML "\qc \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs16\insrsid12326642\charrsid14172830 ________________________________________________________\cell }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\f1\fs16\insrsid12326642\charrsid14172830 \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {"
  ShowHTML "\fs16\insrsid12326642\charrsid14172830 \trowd \irow76\irowband76\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb"
  ShowHTML "\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth3780 \cellx2340\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrtbl \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth7940 \cellx10280\clvertalb\clbrdrt"
  ShowHTML "\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrnone \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row }\trowd \irow77\irowband77\lastrow "
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2182 \cellx742\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth745 \cellx1487\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb"
  ShowHTML "\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth853 \cellx2340\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth7940 \cellx10280\clvertalb"
  ShowHTML "\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrtbl \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {"
  ShowHTML "\fs18\insrsid12326642\charrsid14172830 \cell \~\cell \~\cell }\pard \qc \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\fs14\insrsid12326642\charrsid15559966 ASSINATURA/CARIMBO DO RESPONS\'c1"
  ShowHTML "VEL PELO SETOR FINANCEIRO\cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0\pararsid12326642 {\insrsid12326642 \~}{\f1\fs18\insrsid12326642\charrsid14172830 \cell }\pard "
  ShowHTML "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\fs18\insrsid12326642\charrsid15559966 \trowd \irow77\irowband77\lastrow "
  ShowHTML "\ts11\trrh100\trleft-1440\trkeep\trftsWidth3\trwWidth11794\trftsWidthB3\trftsWidthA3\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3 \clvertalb\clbrdrt\brdrnone \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone "
  ShowHTML "\cltxlrtb\clNoWrap\clftsWidth3\clwWidth2182 \cellx742\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth745 \cellx1487\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb"
  ShowHTML "\brdrs\brdrw10 \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth853 \cellx2340\clvertalb\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10\brdrcf1 \cltxlrtb\clNoWrap\clftsWidth3\clwWidth7940 \cellx10280\clvertalb"
  ShowHTML "\clbrdrt\brdrnone \clbrdrl\brdrnone \clbrdrb\brdrtbl \clbrdrr\brdrnone \cltxlrtb\clNoWrap\clftsWidth3\clwWidth74 \cellx10354\row }\pard \ql \li0\ri0\widctlpar\aspalpha\aspnum\faauto\adjustright\rin0\lin0\itap0\pararsid12326642 {"
  ShowHTML "\insrsid8462233\charrsid3545814 "
  ShowHTML "\par }}"
 
  DesconectaBD
  
  Set w_chave = Nothing
  Set w_logo  = Nothing
End Sub

REM =========================================================================
REM Rotina para informação dos dados da viagem
REM -------------------------------------------------------------------------
Sub InformarPassagens
  Dim w_chave, w_valor, w_pta, w_sq_cia_transporte, w_emissao_bilhete
  Dim w_codigo_voo, w_valor_passagem, w_sq_deslocamento
  Dim w_vetor_trechos(50,10), i, j
  
  w_chave           = Request("w_chave")
  w_menu            = Request("w_menu")
  
  DB_GetSolicData RS, w_chave, "PDGERAL"
  
  w_valor_passagem   = FormatNumber(RS("valor_passagem"),2)
  w_pta              = RS("pta")
  w_emissao_bilhete  = FormataDataEdicao(RS("emissao_bilhete"))
  
  Cabecalho
   
  ShowHTML "<HEAD>"
  ScriptOpen "JavaScript"
  CheckBranco
  FormataData
  FormataValor
  ValidateOpen "Validacao"
  ShowHTML "  var i,k;"
  ShowHTML "  for (k=0; k < theForm.w_sq_cia_transporte.length; k++) {"
  ShowHTML "    var w_campo = 'theForm.w_sq_cia_transporte['+k+']';"
  ShowHTML "    if(eval(w_campo + '.value')==''){"
  ShowHTML "      alert('Informe a companhia de transporte para cada trecho!'); "
  ShowHTML "      return false;"           
  ShowHTML "    }"
  ShowHTML "  }"
  ShowHTML "  for (k=0; k < theForm.w_codigo_voo.length; k++) {"
  ShowHTML "    if(theForm.w_codigo_voo[k].value==''){"
  ShowHTML "      alert('Informe os códigos de vôos para cada trecho!'); "
  ShowHTML "      return false;"      
  ShowHTML "    }"     
  ShowHTML "    var w_campo = 'theForm.w_codigo_voo['+k+']';"
  ShowHTML "    if (eval(w_campo + '.value.length < 3 && ' + w_campo + '.value != """"')){"
  ShowHTML "      alert('Favor digitar pelo menos 3 posições no campo Código do vôo.');"
  ShowHTML "      eval(w_campo + '.focus()');"
  ShowHTML "      theForm.Botao.disabled=false;"
  ShowHTML "      return (false);"
  ShowHTML "    }"
  ShowHTML "    if (eval(w_campo + '.value.length > 30 && ' + w_campo + '.value != """"')){"
  ShowHTML "      alert('Favor digitar no máximo 30 posições no campo Código do vôo.');"
  ShowHTML "      eval(w_campo + '.focus()');"
  ShowHTML "      theForm.Botao.disabled=false;"
  ShowHTML "      return (false);"
  ShowHTML "    }"
  ShowHTML "  }"
  Validate "w_pta", "Número do PTA/Ticket", "", "1", "1", "100", "1", "1"
  Validate "w_valor_passagem", "Valor das passagens", "VALOR", "1", 4, 18, "", "0123456789.,"
  Validate "w_emissao_bilhete", "Data da emissão", "DATA", "1", "10", "10", "", "0123456789/"
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
  ShowHTML "        <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Bilhete de passagem</td>"
  DB_GetPD_Deslocamento RS, w_chave, null, SG
  RS.Sort = "saida, chegada"
  If Not RS.EOF Then
     RS.MoveFirst
     i = 1
     While Not RS.EOF
        w_vetor_trechos(i,1)  = RS("sq_deslocamento")
        w_vetor_trechos(i,2)  = RS("cidade_dest")
        w_vetor_trechos(i,10) = RS("nm_origem")
        w_vetor_trechos(i,3)  = RS("nm_destino")
        w_vetor_trechos(i,4)  = FormataDataEdicao(FormatDateTime(RS("saida"),2)) & ", " &  Mid(FormatDateTime(RS("saida"),3),1,5)
        w_vetor_trechos(i,5)  = FormataDataEdicao(FormatDateTime(RS("chegada"),2)) & ", " &  Mid(FormatDateTime(RS("chegada"),3),1,5)
        w_vetor_trechos(i,6)  = RS("sq_cia_transporte")
        w_vetor_trechos(i,7)  = RS("codigo_voo")
        w_vetor_trechos(i,8)  = RS("saida")
        w_vetor_trechos(i,9)  = RS("chegada")
        i = i + 1
        RS.MoveNext
     wend
     ShowHTML "     <tr><td align=""center"" colspan=""2"">"
     ShowHTML "       <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     ShowHTML "         <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
     ShowHTML "         <td><font size=""1""><b>Origem</font></td>"
     ShowHTML "         <td><font size=""1""><b>Destino</font></td>"
     ShowHTML "         <td><font size=""1""><b>Saida</font></td>"
     ShowHTML "         <td><font size=""1""><b>Chegada</font></td>"
     ShowHTML "         <td><font size=""1""><b>Cia. transporte</font></td>"
     ShowHTML "         <td><font size=""1""><b>Código vôo</font></td>"
     ShowHTML "         </tr>"
     w_cor = conTrBgColor
     j = i
     i = 1
     While Not i = j
       ShowHTML "<INPUT type=""hidden"" name=""w_sq_deslocamento"" value=""" & w_vetor_trechos(i,1) & """>"
       ShowHTML "<INPUT type=""hidden"" name=""w_sq_cidade"" value=""" & w_vetor_trechos(i,2) &""">"
       If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
       ShowHTML "     <tr valign=""middle"" bgcolor=""" & w_cor & """>"
       ShowHTML "       <td><font size=""1"">" & w_vetor_trechos(i,10) & "</td>"
       ShowHTML "       <td><font size=""1"">" & w_vetor_trechos(i,3) & "</td>"
       ShowHTML "       <td align=""center""><font size=""1"">" & w_vetor_trechos(i,4) & "</td>"
       ShowHTML "       <td align=""center""><font size=""1"">" & w_vetor_trechos(i,5) & "</td>"
       SelecaoCiaTrans "", "", "Selecione a companhia de transporte para este destino.", w_cliente,  w_vetor_trechos(i,6), null, "w_sq_cia_transporte", "S", null
       ShowHTML "       <td align=""left""><font size=""1""><input type=""text"" name=""w_codigo_voo"" class=""sti"" SIZE=""10"" MAXLENGTH=""30"" VALUE=""" & w_vetor_trechos(i,7) & """  title=""Informe o código do vôo para este destino.""></td>"    
       ShowHTML "     </tr>"
       i = i + 1
     wend
     ShowHTML "        </tr>"  
     ShowHTML "        </table></td></tr>"
  End If
  ShowHTML "        <tr><td colspan=""2""><font size=""1""><b>Nº do PTA/Ticket: </b><input type=""text"" name=""w_pta"" class=""sti"" SIZE=""100"" MAXLENGTH=""100"" VALUE=""" & w_pta & """ title=""Informe o número do bilhete(PTA/eTicket).""></td>"
  ShowHTML "        <tr><td><font size=""1""><b>Data da emissão: </b><input type=""text"" name=""w_emissao_bilhete"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_emissao_bilhete & """ onKeyDown=""FormataData(this,event);""></td>"
  ShowHTML "            <td><font size=""1""><b>Valor das passagens R$: </b><input type=""text"" name=""w_valor_passagem"" class=""sti"" SIZE=""10"" MAXLENGTH=""18"" VALUE=""" & w_valor_passagem & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o valor total das passagens.""></td>"
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
  Set w_sq_cia_transporte  = Nothing
  Set w_codigo_voo         = Nothing
  Set w_valor_passagem     = Nothing
  Set w_pta                = Nothing
  Set w_emissao_bilhete    = Nothing
  Set w_sq_deslocamento    = Nothing
  Set w_menu               = Nothing
  
End Sub

REM =========================================================================
REM Rotina de emissão do relatório para prestação de contas.
REM -------------------------------------------------------------------------
Sub PrestacaoContas

  Dim w_chave
  
  w_chave = Request("w_chave")
  
  Response.AddHeader "Content-Disposition", "attachment; filename=Relatorio"&w_chave&".doc"
  Response.ContentType = "application/msword"
  
  ShowHTML RelatorioViagem(w_chave)
  
  Set w_chave = Nothing
End Sub

REM =========================================================================
REM Devolve string com o relatório de viagem no formato RTF
REM -------------------------------------------------------------------------
Function RelatorioViagem(w_chave)

  Dim w_percurso, w_diaria, w_valor, w_html
  
  w_chave = Request("w_chave")
  
  'Recupera os dados da solicitacao de passagens e diárias
  DB_GetSolicData RS, w_chave, Mid(SG,1,3) & "GERAL"


  'Recupera a data da primeira saída
  DB_GetPD_Deslocamento RS1, w_chave, null, "DADFIN"
  RS1.Sort = "saida, chegada"
  If Not RS1.EOF Then
     w_percurso = RS1("nm_origem")
     w_percurso = w_percurso & "/" & RS1("nm_destino")
     w_diaria = w_diaria + cDbl(RS1("quantidade"))
     w_valor  = w_valor  + (cDbl(FormatNumber(Nvl(RS1("quantidade"),0),1)) * cDbl(FormatNumber(Nvl(RS1("valor"),0),2))) + cDbl(FormatNumber(Nvl(RS("valor_adicional"),0),2)) - cDbl(FormatNumber(Nvl(RS("desconto_alimentacao"),0),2)) - cDbl(FormatNumber(Nvl(RS("desconto_transporte"),0),2))
     RS1.MoveNext
     While Not RS1.EOF
        w_diaria = w_diaria + cDbl(RS1("quantidade"))
        w_valor  = w_valor  + (cDbl(FormatNumber(Nvl(RS1("quantidade"),0),1)) * cDbl(FormatNumber(Nvl(RS1("valor"),0),2)))
        w_percurso = w_percurso & "/" & RS1("nm_destino")
        RS1.MoveNext
     wend
  End If
  RS1.Close
    
  'Recupera os dados do proposto
  DB_GetBenef RS1, w_cliente, Nvl(RS("sq_prop"),0), null, null, null, 1, null, null

  w_html = ""
  w_html = w_html & "{\rtf1\ansi\ansicpg1252\uc1\deff0\stshfdbch0\stshfloch0\stshfhich0\stshfbi0\deflang1033\deflangfe1033{\fonttbl{\f0\froman\fcharset0\fprq2{\*\panose 02020603050405020304}Times New Roman;}{\f1\fswiss\fcharset0\fprq2{\*\panose 020b0604020202020204}Arial;}"
  w_html = w_html & "{\f36\froman\fcharset238\fprq2 Times New Roman CE;}{\f37\froman\fcharset204\fprq2 Times New Roman Cyr;}{\f39\froman\fcharset161\fprq2 Times New Roman Greek;}{\f40\froman\fcharset162\fprq2 Times New Roman Tur;}"
  w_html = w_html & "{\f41\froman\fcharset177\fprq2 Times New Roman (Hebrew);}{\f42\froman\fcharset178\fprq2 Times New Roman (Arabic);}{\f43\froman\fcharset186\fprq2 Times New Roman Baltic;}{\f44\froman\fcharset163\fprq2 Times New Roman (Vietnamese);}"
  w_html = w_html & "{\f46\fswiss\fcharset238\fprq2 Arial CE;}{\f47\fswiss\fcharset204\fprq2 Arial Cyr;}{\f49\fswiss\fcharset161\fprq2 Arial Greek;}{\f50\fswiss\fcharset162\fprq2 Arial Tur;}{\f51\fswiss\fcharset177\fprq2 Arial (Hebrew);}"
  w_html = w_html & "{\f52\fswiss\fcharset178\fprq2 Arial (Arabic);}{\f53\fswiss\fcharset186\fprq2 Arial Baltic;}{\f54\fswiss\fcharset163\fprq2 Arial (Vietnamese);}}{\colortbl;\red0\green0\blue0;\red0\green0\blue255;\red0\green255\blue255;\red0\green255\blue0;"
  w_html = w_html & "\red255\green0\blue255;\red255\green0\blue0;\red255\green255\blue0;\red255\green255\blue255;\red0\green0\blue128;\red0\green128\blue128;\red0\green128\blue0;\red128\green0\blue128;\red128\green0\blue0;\red128\green128\blue0;\red128\green128\blue128;"
  w_html = w_html & "\red192\green192\blue192;}{\stylesheet{\ql \li0\ri0\widctlpar\aspalpha\aspnum\faauto\adjustright\rin0\lin0\itap0 \fs24\lang1046\langfe1046\cgrid\langnp1046\langfenp1046 \snext0 Normal;}{"
  w_html = w_html & "\s1\qc \li0\ri0\keepn\widctlpar\aspalpha\aspnum\faauto\outlinelevel0\adjustright\rin0\lin0\itap0 \b\i\f1\fs24\lang1046\langfe1046\cgrid\langnp1046\langfenp1046 \sbasedon0 \snext0 heading 1;}{\*\cs10 \additive \ssemihidden Default Paragraph Font;}{\*"
  w_html = w_html & "\ts11\tsrowd\trftsWidthB3\trpaddl108\trpaddr108\trpaddfl3\trpaddft3\trpaddfb3\trpaddfr3\tscellwidthfts0\tsvertalt\tsbrdrt\tsbrdrl\tsbrdrb\tsbrdrr\tsbrdrdgl\tsbrdrdgr\tsbrdrh\tsbrdrv "
  w_html = w_html & "\ql \li0\ri0\widctlpar\aspalpha\aspnum\faauto\adjustright\rin0\lin0\itap0 \fs20\lang1024\langfe1024\cgrid\langnp1024\langfenp1024 \snext11 \ssemihidden Normal Table;}}{\*\latentstyles\lsdstimax156\lsdlockeddef0}{\*\rsidtbl \rsid1469200\rsid6709522"
  w_html = w_html & "\rsid13377954\rsid13532367}{\*\generator Microsoft Word 11.0.5604;}{\info{\title RELAT\'d3RIO DE VIAGEM}{\author TERESA SOARES}{\operator Suporte T\'e9cnico}{\creatim\yr2006\mo5\dy12\hr10\min30}{\revtim\yr2006\mo5\dy12\hr10\min30}"
  w_html = w_html & "{\printim\yr2006\mo4\dy25\hr18\min28}{\version2}{\edmins0}{\nofpages1}{\nofwords153}{\nofchars873}{\*\company Minist\'e9rio da Jusit\'e7a}{\nofcharsws1024}{\vern24689}}\margl1418\margr1418\margt899\margb851 "
  w_html = w_html & "\deftab708\widowctrl\ftnbj\aenddoc\hyphhotz425\noxlattoyen\expshrtn\noultrlspc\dntblnsbdb\nospaceforul\formshade\horzdoc\dgmargin\dghspace180\dgvspace180\dghorigin1418\dgvorigin899\dghshow1\dgvshow1"
  w_html = w_html & "\jexpand\viewkind1\viewscale100\pgbrdrhead\pgbrdrfoot\nolnhtadjtbl\nojkernpunct\rsidroot6709522 \fet0\sectd \linex0\colsx708\endnhere\sectlinegrid360\sectdefaultcl\sftnbj {\*\pnseclvl1\pnucrm\pnstart1\pnindent720\pnhang {\pntxta .}}{\*\pnseclvl2"
  w_html = w_html & "\pnucltr\pnstart1\pnindent720\pnhang {\pntxta .}}{\*\pnseclvl3\pndec\pnstart1\pnindent720\pnhang {\pntxta .}}{\*\pnseclvl4\pnlcltr\pnstart1\pnindent720\pnhang {\pntxta )}}{\*\pnseclvl5\pndec\pnstart1\pnindent720\pnhang {\pntxtb (}{\pntxta )}}{\*\pnseclvl6"
  w_html = w_html & "\pnlcltr\pnstart1\pnindent720\pnhang {\pntxtb (}{\pntxta )}}{\*\pnseclvl7\pnlcrm\pnstart1\pnindent720\pnhang {\pntxtb (}{\pntxta )}}{\*\pnseclvl8\pnlcltr\pnstart1\pnindent720\pnhang {\pntxtb (}{\pntxta )}}{\*\pnseclvl9\pnlcrm\pnstart1\pnindent720\pnhang "
  w_html = w_html & "{\pntxtb (}{\pntxta )}}\pard\plain \qc \li0\ri0\widctlpar\tx4680\tx5040\tx5580\aspalpha\aspnum\faauto\adjustright\rin0\lin0\itap0 \fs24\lang1046\langfe1046\cgrid\langnp1046\langfenp1046 {\fs20\lang1024\langfe1024\noproof\insrsid1469200 "
  w_html = w_html & "{\shp{\*\shpinst\shpleft4320\shptop-720\shpright4860\shpbottom-180\shpfhdr0\shpbxcolumn\shpbxignore\shpbypara\shpbyignore\shpwr2\shpwrk2\shpfblwtxt1\shpz0\shplid1026{\sp{\sn shapeType}{\sv 75}}{\sp{\sn fFlipH}{\sv 0}}{\sp{\sn fFlipV}{\sv 0}}"
  w_html = w_html & "{\sp{\sn pib}{\sv {\pict\picscalex40\picscaley37\piccropl0\piccropr0\piccropt0\piccropb0\picw2381\pich2593\picwgoal1350\pichgoal1470\pngblip\bliptag93535635{\*\blipuid 05933d93d5af8c665d329e24f38e3896}"
  w_html = w_html & "89504e470d0a1a0a0000000d494844520000005a000000620803000000e75f34f0000000c0504c54453537342826220e663970642f39286446386f374c411074"
  w_html = w_html & "49306c504944574b514f476754736a4e534a696e6b73645d614d4634a27232d379258d714a9e754f05874a288645968d38ab9334a89618d3ac1ad3c914f2d307"
  w_html = w_html & "f7e807ecdc319c935bc9b65eebdb5fb5ad2a6a806e8f8d90b0afb0a4a29af8ec93f8efb3ddd19acecdcef9f3d1fdfae7fffffffdfcf9e7e7e7bfbbb57b778900"
  w_html = w_html & "0000000000000000000000000000000000000000000000000000000000000000000000000000000000c940afe600000001624b47440088051d480000000c636d"
  w_html = w_html & "50504a436d703037313200000003480073bc000010d0494441546843b5990b77a2dab28589f8027968d08022080888803cfeff9f3b5f2d349df4eef4d9778c7b"
  w_html = w_html & "e8b4b115e6aa35eb356bb5f6f89f5ddabf416ed2eecb6dedadfd370f3dfe0d749746d5e305d73e6ed1edff0dba6de2b64b27ec267d5471fbafccfec9eae6f6c9"
  w_html = w_html & "0174c4d523ae6e6dd5de6ee9839fe73a2cf517767e82965d3fc19bf891de1e6915dfa23416e85b1b3fcdee60a7fa899d9fa02bd02677555ddcdeefa0c7d1394a"
  w_html = w_html & "6fb288b0a2d8e11f69f37f857ec06f0542094cda54c9e37e4ba3f7284dab47529677ecbe355dda34f18fc83f46488b995d527f5cbb04829be451dde3d3fbe996"
  w_html = w_html & "36f5a52cca2169582111a67e74e90f84e0ba26692f87d5aa4feee5a54dd842f4fe8ed9ed50ef7779998fc36e5f704ff72de6bf92f30374c7c62f1f20cfd6abd5"
  w_html = w_html & "0ad39be67c02fa04ef176b575e2ebb71bfeff1017efde1fac9ea2629d6abc36c76007c1db4c9bd8d829dbe0ba276b81445b12fbd6114a3d9cf4f8cfc191a1f26"
  w_html = w_html & "e672b95ecd56abf53abf57f7fbedb4d3f5dd2ebc0d83371690f4482470ee77eefdb3d9dfa0c90952a0bb55c454b15c2e37a0afd7cba6ba5709466bfa7117c4fe"
  w_html = w_html & "f5dd1b86cba5bef497b6b9b431915e11876d537dad348fef11d2902737820e66634bd3cca5f6b634979bcb75dd9c4e7b5dd3c3dd3e4c6aa748e0e16e5dadf252"
  w_html = w_html & "6e367523418839d177f3bf5b2d617b4bfbbbe779ba86d9da1baf5062c1f47eabe9e7e33e88ee494df83569feb13aacaeb3d5c7a5ac40be11addf98f98deb2669"
  w_html = w_html & "1ec966d03d7dab2d35ed4da0d7ebebfd16eef5a5a647a1b70fd3fb507bfbfa7e5de3e8c312fcf5eaa3be27bf53fe0dbabb11cdf961a56f7561039bc5f44dde55"
  w_html = w_html & "62b4409f7798ddf4f96e77a92dfcb03ae06b09a4c3051f7d77e777e834edaf87c3eab27dd34a2264b97c7b5b3a97e651857b0b7af4283a79fb73f3188aa2beeb"
  w_html = w_html & "6b3624b1b912e88f6bf25b847f87ee928f03d039a1b1c94bd3dc6eb74357ddbb38f0b66c03e8f3ce0bd247d277ed65d86c049b3812e8c36c95ff2d42d20df790"
  w_html = w_html & "2584f36a3b2449d326d5a34baad0b3081581c66cef5c251d01dd9b58c05ed89b823e1c861fddd8753e9b0359e0d77ade5eaa8742897c8c7e428bd9e7fba3c5df"
  w_html = w_html & "571899695c6ff02d4f5d7f84bea59b0d250370c9c16ddd5797f681d962b409f452ac16b3a306a31f8d871f67b3d91b0e59cf08d2c3a1fe5379229b1e8d24b7b6"
  w_html = w_html & "1693c9415e0400b353df12a32742a2b3ee653752a64b24fc30e50034e1b4e4c175df54ed8b71e5467a534b6fea0a933b36c0ae37e2fdabd0fc486ea1a563f4cb"
  w_html = w_html & "eae86879712ad5a3c391eb355408279200abf51594f4d93915741bc555d7a4692e8b2b78f1fd9aa8a3b65511466f80765d2f3b8567ccb6b2b8e19b47636d3626"
  w_html = w_html & "7690b540cb1fb36e6f713cd93d055f5591e25d63c9e2dc4634acd79b6bc5d355da8496653af950d7fcf4fd9064981d891b49ddd154f64237e9c55bf342b37c36"
  w_html = w_html & "e2099a9adba449953fa1a94c9bcda69fc2230eac7c2846d7b40d2e16a9871cb3e95ca0b7de1650738216d365a74fb29f2913d3989b44579124b799662945a14a"
  w_html = w_html & "db2a1c8bdc05b42c1cd7a91dde39c57089da563d52f517089487a60773787d86c9131a86d251112685e36dab27b72a8dd3388ab2b2748dc119f36128dda22ec6"
  w_html = w_html & "7c2c002fc328e2fb5bd3b64d42617841ebbfeadf0bbae9064d27af75ff28ae22c6c22cf03dcf2a47ccacebdccef3d1c8f3dc28fb814de485e5f981b83566fd73"
  w_html = w_html & "783aee74e126ef5e7cbc5a4117c749f6843c9d8260bfa7647b9665958e638f7959e48663bbb6c31f6af538daee585896dce3edf741700a5fcf66349d6f84b429"
  w_html = w_html & "2b9f4fd2b377bbdd5e80e983c7e34ec8e821c0759dbc18ea01da6dc731eabe341c6a23bbd42dc0f73c25fdfe7466c3dfb96e2af48b5cdc2290420a57983b0656"
  w_html = w_html & "16c01535c137107c7dc1368aa2b48d319744922c95153045219ce2ead913a69449210c6c5d3fb2eeafeb321ad8383a6e89fb6c7bb158cc6d023cb7f96cb48ddc"
  w_html = w_html & "79624b012400b6809f1086cf9620d00d7eae6e62b7b5fc0a1ee4609686ed1685036c3df2b2581844b96bdb7591db547465b65c5bff74149bc1927aa4b2b16b6f"
  w_html = w_html & "189dde1427d67ab3f944bfbac00ca3cdee81244c8a09dc290a8c1f466354660bee915804393ca7770526354aa334356dd756f78f8f287cdf511528200afd981b"
  w_html = w_html & "763e8e46a990c702d2e5cd625cb82c467c1ba6b0cdcde04651f0fe7e0e3f3eee0a2d45c16a4d1c87a724b9e787d947f8c2067deb7b2e661165a50bc7f3b94350"
  w_html = w_html & "0bb2dd3bd8edb8069132ba4f5c1adb4e906907f73409e31849a591f25d5395fe8cb605f66eb29b8a668ee628de2bc7b920cf711dd044475d974e8ed9c3e0b857"
  w_html = w_html & "b157126cb70b409ed1c6aeb7aae94408e2c6a6aada0275477bd363d4c00b9be42b8a71cc6141a0e545e828cb32b7ddd22102b9437111615170ce3e68bdb3c347"
  w_html = w_html & "df023815d50ee22dd1bb605fbf603b863be66e2e462bec097a012f0e81974310b19d4dc87b854c57c0c0003f4af19b6a48777d42aff4f065b70b9bb66d0bd38b"
  w_html = w_html & "e2092d3b18896adb70c0b5f9157c41065731f2b5a8de62e9b62c287d393b078a13a00bf25ad09c1ecec5e87e1825c259d12ddd7ca0b0f8d8eca1a832c115606c"
  w_html = w_html & "7cce37caeaeeae1a395fc8abc236812676813616d2610a3819e5b78a3f81364851c7f5454f05d1e983f141594dcfbe4ff56982ae455cc9174a636551803aa0ad"
  w_html = w_html & "8cb982a69e1226184d099c9246a0a5cadaae7ff43c6c6636a1eb8a025c2f37c517e82a1641a114c85a54c8846d1ae4b39d9b10429c6035bf25fe264272feba86"
  w_html = w_html & "4b610de23b72f830c97c11a0c134f04de5a9274764bc902f0fab8fb241e4f120563b396e74140b404ebf80c68d6cc1311ccbcbe2b640321c34a4004d984e554c"
  w_html = w_html & "a54fa0093e915df47c1931c02f29048105745fbba3ab42ee79293e30da60237c0934b5ff7e118b3591b50a3aa0274fd04478830291cf5109b4dcab9481d8a738"
  w_html = w_html & "911dae637c41eec570904dca1e5f1a6e96765d5bce26e9a75aafb6ac2b35d5680805294ed26e4580885c28988be8b896497731ecd1fdc4768a1ed92046b31903"
  w_html = w_html & "c64c8fde7bbfaf0f339ed6de94fed390399daa7c2da5244e124f5abd92296fdb204bd25b5361b64b6cb80e8e549743eda3e689d1a35d53130df7786bee09ae17"
  w_html = w_html & "4ca4b082f61210c9472a5f2a0ab0977eaf64ca9b9f20501f8f5b46e32eebc1c1f497fb240a0599d64393374c3783d55aaa8f4256bf4c6a13624c2a1f74482f48"
  w_html = w_html & "143657de948702311505849f140cc3319713b89da34740867f6a1f4c5b41746baf40afde3425df59e0834e80b3a60869a50c36f7095bdbde19672e718a8a344d"
  w_html = w_html & "1b94c2755c4d23736ca4826d68262a8a266fdba6e9eda3382327a6ba04b4e8f7a69aa6bca7e6435e269eb0b1f5de0a2961cc2f7b6f678a0aa387c3b7a82a75b9"
  w_html = w_html & "747404046cb99abe3fb70ca5b85121ab5ffe6b107baa2786e0c2d3df2eebd9f6ed42c6afcadb79bf3beb60b373a204cb49090d2e5c0a5f299a4a04f73ebc951b"
  w_html = w_html & "425690856b1e7d26cce720dd55176bbb7dab597fada0d7710874a86f6962a32b3ea397a1a45c9baa648f342fe9b7dbfd29b64c6d49f590ca26a705b3cfa1e3a5"
  w_html = w_html & "f9e244461d4b4d48cc274067fb2322076c9aafb4ef9ab81b8642b402262b64a083d8624691b194dac39432dbfc26cc287e8fab2c3dd5c5d9726d31df2ab9c344"
  w_html = w_html & "0d29e34845cd0d942ac503643850ba897a7a54b336f64c03d0afe1f16975774b319ba2c88ed89566fa1c52281975a29869e23355ab72b2d0b0d17abe825e7a3b"
  w_html = w_html & "8116a1ff3ae3483e8f2e9e0d0ce9de314f49bd85edf572798c7613b42ec33ab18788820697d10005a68791afa0f5ddf9b4650bd41ff0999137728af7a55ecbc0"
  w_html = w_html & "51a50943e34ceec706ed14eea0fa7cf64d648352fdaa0cc8b5dd491b973505fa7416e86900829a7f0c1cd39894cbea529fcc8d763e29e8a352755cda56553544"
  w_html = w_html & "e95184bd34dbadf81168b5af69a258ba77c9c5af56a767146095c8d18a3cbf3475a0f16228362bf145030ccfd9f9245a7ad21ed151e4296a4c173a54fdd1b412"
  w_html = w_html & "d5fb3a3215aebb9aa358045a93ab8acbf2a61ebdef90d8baa8280e1850e7a72873dc3c8d00673f0a5b3077ef2c314d48fca53ea0539b6e7c257aef90f36dd555"
  w_html = w_html & "899a91a07319000dd14aa06dbdddee14dd6a52322f875b741275afc61d4e5f507aa7696ae3d29b4ee9a6c2e1a5561162978a1da4df34586adae9fc7e3c9f94f6"
  w_html = w_html & "53c0a8c327f4a38f214b3e836e4dd311912fe4fc390fd48bfcf1e81dad077358bcce930357b9727b166801d677ef709e5dfa17342bf8443ba309e0476dcbd753"
  w_html = w_html & "a9f7b2d7dcefd8fda31b0bad28f8c47e4a879643eb7ae83778f1fd04d14f608b88ce0745482e939d194467c61674b52ed03ac84ecdc1d51419c3a278f4a3dd69"
  w_html = w_html & "8fdc2eba91759e576dcfc72d89f0eeaf751e178b658066fe52d0a65b933de5257b826f39c2dd6da9b9dad3ba079db9cee7f35aea7539770a04f97349254813bc"
  w_html = w_html & "88c5efe728f30f6ba62d67904e2bd00c4e459e738c152a70ee8ad29a0cd5e6102c177a021d44fd13378e7384ee28dbe9584ea02d395006f8e493a1074ee37229"
  w_html = w_html & "2213b4e1f47242b55af9a14c6ddc96286434a14014b4e7f9424ea1047a988b2e920d89d8f58df9dcc2a073140628d6e5156f0e74449a0073296aade47c652da7"
  w_html = w_html & "926b9fc5b9d19f908584470d92bdb03fe31ad94f53e58b7e3ecffcc0980767e6bfd37d5852181cdaa5c948adef3cdddbe9bc33497a87f3a3cdea03ca4fa74c9b"
  w_html = w_html & "2f7c6b3eb7bb4737693715cc2aaecba95f437769d9999599c728d393412aa0a601ad2abfee5927cbe3a8682bb1ef6c36570e84364d1646c7b96505966fd584dc"
  w_html = w_html & "84f4abed76a2c7334b92124233cbb3a4b7d45459509c378910c390d9303ceaea3dd0a6092deba234b4adcdb3966fc0afd2b1af9098ea3501e3f9b6e548a8fb9e"
  w_html = w_html & "9d65d65c33dc1a52679fd0ba2595f4135a29e96b610819bee5056ef969f333d89e5da6777c3bc46e3ecd6d9f930e29fc8e60af7e111266a110425fc06a39fcba"
  w_html = w_html & "96c65cec08011f48ed696278f5dd27347667568088e1f37201773ede24ccc6af6ed471e5a71b918d65495e7a59280fd68fdaf5958cfdbda3c3c925736df43a31"
  w_html = w_html & "582fbccc0b33df5d3843ed8c720c87f9a688b4abb441d735d199b461cbc6668e7aecb17f1476e88784ddaf03ca97d552aa1659105a721b6ffd2c0b16beedd979"
  w_html = w_html & "dde7e8a3ab3a68611ecd37eb4dd175542bdbcd32dbcf3cd7868c91d00a33ef0bf2d7ff2be89d8ccbb75d1626f3bdc00e321c64db6339f4602968c2a0efd5e948"
  w_html = w_html & "9005449c6d2d900783fc8b65ae5f0f557f59cd3323b666b6e5e562b8e1f996efba219be61dca898b570e5e4cc37233dbf3323f7317c41c261b5918fab644c1af"
  w_html = w_html & "eb2bf4a32bbdcc924871d93242c60efc2cb42d92c8cbf8f102dff4bdd01773cf1090c9a90e7c09e1301dfced689c7262db3ea653290629490b0643dbb7049c93"
  w_html = w_html & "378b45cf56e0e2630b4fb8c6dcb0a92e6c2be470d497c2ffa3d5923c8e6545199b63e82c55e6a120d90bfbf55dfc24219cd9812b5a47248fc1a66c974fbf93f1"
  w_html = w_html & "5943beaed595ae2f46318e92d092d4f2625b4c009409cfb45c25a3a8bc7c2ed3989f59b8e577935fe5e9fb467a075a893dc98039d02260886301640d2777be9c"
  w_html = w_html & "60c0063168e3f77f5edfdcf8fcba1b303108c300d33d0668110379c7e85f4af0d5d3c10b5167593284d8c6e5db39fee71a7f82a6db142e8e2312e086e415acfc"
  w_html = w_html & "15d7727a41790f323b2430996ffe0cfcdb7faf7dd95437e04fc2c2cf88181f30391a572923670c730a6508b44bb2fe818ae9a33f5badbeaa73d7931c2237b09b"
  w_html = w_html & "634f4e54c7e2094dc071ba50ff166f7f0dbedf3cca044a6e88d572546b7232d74d563384fcc9757f8beb7f6eaf1fc05fcc876228b68cd21d136f4d49f9efd75f08f916ed7d8f6b4da7fc6fa6fe5443fe6e49cd61d4cf4efb7771fdc30a5deefc21e77e36e73fbe24c8e7499be1b00000000049454e44ae426082}"
  w_html = w_html & "}}{\sp{\sn pibName}{\sv http://www.tjdf.gov.br/armas1.gif}}{\sp{\sn pibFlags}{\sv 10}}{\sp{\sn pictureGray}{\sv 1}}{\sp{\sn pictureBiLevel}{\sv 0}}{\sp{\sn fLine}{\sv 0}}"
  w_html = w_html & "{\sp{\sn wzDescription}{\sv }}{\sp{\sn fLayoutInCell}{\sv 0}}{\sp{\sn fLayoutInCell}{\sv 0}}}{\shprslt\par\pard\ql \li0\ri0\widctlpar\pvpara\posx4319\posnegy-721\dxfrtext180\dfrmtxtx180\dfrmtxty0\aspalpha\aspnum\faauto\adjustright\rin0\lin0\itap0 "
  w_html = w_html & "{\pict\picscalex40\picscaley37\piccropl0\piccropr0\piccropt0\piccropb0\picw2381\pich2593\picwgoal1350\pichgoal1470\wmetafile8\bliptag93535635{\*\blipuid 05933d93d5af8c665d329e24f38e3896}"
  w_html = w_html & "010009000003e21300000000bd13000000000400000003010800050000000b0200000000050000000c0263005b00030000001e000400000007010400bd130000"
  w_html = w_html & "410b2000cc0062005a000000000062005a0000000000280000005a00000062000000010008000000000000000000000000000000000000000000000000000000"
  w_html = w_html & "0000ffffff00d1f3f9005fdbeb00f9fcfd00e7fafd0093ecf80007e8f700b3eff80031dcec009ad1dd0007d3f2009aa2a4005eb6c9002579d300cecdce004586"
  w_html = w_html & "28006e806a001aacd3005b939c00b0afb000b5bbbf004a87050049741000e7e7e7004f759e003272a200908d8f0089777b0039660e00506c30004e6a73003493"
  w_html = w_html & "ab002f6470004a718d004f514b00414c370014c9d30054674700343735001896a800388d960022262800736b6e0034464d002aadb500615d6400694a53005744"
  w_html = w_html & "49006f38460064283900000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000"
  w_html = w_html & "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000"
  w_html = w_html & "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000"
  w_html = w_html & "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000"
  w_html = w_html & "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000"
  w_html = w_html & "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000"
  w_html = w_html & "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000"
  w_html = w_html & "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000"
  w_html = w_html & "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000"
  w_html = w_html & "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000"
  w_html = w_html & "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000"
  w_html = w_html & "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000"
  w_html = w_html & "00000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000000101"
  w_html = w_html & "01010101010101010101010101010101010101010101010101010101010101010101010101010101041b2b140401010101010101010101010101010101010101"
  w_html = w_html & "01010101010101010101010101010101010101010101010100000101010101010101010101010101010101010101010101010101010101010101010101010101"
  w_html = w_html & "010101010f2e1b150f01010101010101010101010101010101010101010101010101010101010101010101010101010101010101000001010101010101010101"
  w_html = w_html & "0101010101010101010101010101010101010101010101010101010104181804142e232b0c180101010101010101010101010101010101010101010101010101"
  w_html = w_html & "01010101010101010101010101010101000001010101010101010101010101010101010101010101010101010101010101010118150c1c2f3132151415142c2b"
  w_html = w_html & "0c14041b1b0c0f180401010101010101010101010101010101010101010101010101010101010101010101010000010101010101010101010101010101010101"
  w_html = w_html & "0101010101010101010101180c1c2f1f2f2d293232320f15141b232e1c0c041c32323232312f1b15180101010101010101010101010101010101010101010101"
  w_html = w_html & "0101010101010101000001010101010101010101010101010101010101010101010101010f1b2e22292d2d2d22222d3132320f151515141b1c14011c32323232"
  w_html = w_html & "312f2d252f2e1b0f040101010101010101010101010101010101010101010101010101010000010101010101010101010101010101010101010101010104152b"
  w_html = w_html & "1f1f2d2920292d29292d29323232150f0f15141c0c15011c32323232312229252f25252e2f1b0f01010101010101010101010101010101010101010101010101"
  w_html = w_html & "000001010101010101010101010101010101010101010104142e1f2d292d202029251f313131323232321b0f0f15141c0c15012f323232323131202d2f25252f"
  w_html = w_html & "2f2f2f2b0f010101010101010101010101010101010101010101010100000101010101010101010101010101010101010104152e1f2020252520202e2f223132"
  w_html = w_html & "3227272a2a1b040f0f140c1c0c15010f3132323232312f1f311f1f312f2f2f2f30130f0101010101010101010101010101010101010101010000010101010101"
  w_html = w_html & "010101010101010101010101182b222d222d2d20292f3131323030272427272a2e1b1b2e30303030302f2b1b2b32323232323232313131312f2f2f2f2e2d2f1b"
  w_html = w_html & "1801010101010101010101010101010101010101000001010101010101010101010101010101040c2e29251f2d202e2f2f30302730303027273030302f1f2e1f"
  w_html = w_html & "201f291f22231f2e2f2e30303032323127303031312f2f292d1f3022291404010101010101010101010101010101010100000101010101010101010101010101"
  w_html = w_html & "01182b1f1f072d25292f2f2e0c0c30303030302f2e2f222d1f252529292e252d222529291f2d2f202e2e2f30303030302e302f1f2d2f2e2529292b1801010101"
  w_html = w_html & "01010101010101010101010100000101010101010101010101010101152f2f29292d2d23302e15041b3030312f2e292f291f1f2d1f2d29202d1f252d1f0b1f20"
  w_html = w_html & "2e2d22251f2d20312e3032302f151b2f2f2f2d25251f1f29140401010101010101010101010101010000010101010101010101010101040c222d1f2f252d1f2e"
  w_html = w_html & "1404041c30302f20292d252f1f2d1f2d222d2d222d22202923292225292d20201f0b1f2f2d2d3131322f0f181b2f2d2d292f2529201b04010101010101010101"
  w_html = w_html & "0101010100000101010101010101010101181c292d2d2d2f1f221b1801182b3022291f292e2e252f2f2d22291f2e2b2b2b2b2b2e2b2b1c2b2e23301f2e2d2f22"
  w_html = w_html & "222d292d2e31301501181c1f302d2525292f2b18010101010101010101010101000001010101010101010101182b2d2d2d2d292f2e0f010114312f22202f202d"
  w_html = w_html & "291f222f2f1c0c151804010f0f15141c0c15010118180f141c2f2f202d20201f292f1f311b0401152f202d202f292d110f010101010101010101010100000101"
  w_html = w_html & "01010101010101182b312d20251f2f1c1801182b31292d2d2031292f222f1b0f181b26261e1114150f15141c0c0c1b261e26140404010f1c2f2e2d2d2f292d22"
  w_html = w_html & "2e2f1501181c1f2f2d252d222f0f010101010101010101010000010101010101010118132d2e2f222e2e1401010f31222d22252d292e312b150f2b1c23232e2e"
  w_html = w_html & "261e1e231b1b1b2b11232626262e2e232e1b1b150f1b1f2f2f2d2d2922222f1c1801142f2529252f312f0f010101010101010101000001010101010101182f2d"
  w_html = w_html & "1f202f2f2f0f01010c31321f1f29292030302b181c23242b2a272c231f1f1f211a211f211a211f1f1f232c272a2b2a27230c0c302320222d251f202f2f0f010f"
  w_html = w_html & "2e292f3131312f0f01010101010101010000010101010101182b292f252d1f2b1801011b2f292229202f2e30302b0f261e1e172b2a2730303023232122252d25"
  w_html = w_html & "22212323272730272a2b27272426110c30322f2d2d1f2529291f1501182b2f313131312f0f0101010101010100000101010101182f3122292f1f2b1801041c29"
  w_html = w_html & "222f2d2d1f3227302e0f261e1e1d272b2a303030303030211f07070b21213030303030272a2b241e1e242611143027323129291f22202e0c01041c3131313131"
  w_html = w_html & "2f18010101010101000001010101042b3131312d1f2b0401011c2e2e2d1f221f2c30302f0f111e1e2727272b2a3030303030302c282507252821303030303027"
  w_html = w_html & "2a2b23231e1e1e1e140c30272c2f312d1f1f29301501011b31313131312f1801010101010000010101010c31313131312f1801010c1f2f2d1f2d2328072d210d"
  w_html = w_html & "151e1e242427272b2a27303030232321221f121f222123232c2727272a2b2626111e171e26022d250728321f1f201f291f0f01041c31313131312f0401010101"
  w_html = w_html & "0000010101183131323131310f01011832322d29203032200b12070b120a15242423242b2a272c231f1f1f211a2221221a211f1f1f232c272a2b171e1e261710"
  w_html = w_html & "2d070b12122d273231291f32322f0401042f31313131310c0101010100000101011532312f31311401010114323232293032321f0b0e0e0b0725201f26262411"
  w_html = w_html & "262626151b2f302e2b2e2e2b1b23302e0c261e1e1e111d17101e290b07120e0e0b2927272732323232320f01010f2f31312f312f0101010100000101011c321c"
  w_html = w_html & "32311c04010101041b3232323232311f070e0e0e0e0b071229261e1716261b041b30302e1c2e2e1b0c2330302b18261616161717292507120e0e0e0e0b0d3027"
  w_html = w_html & "27273232321c040101011431312b323115010101000001010c322b14312f180101010101011532323232310d070e1a211a0e0e0b072d29161e0c2f302b15302e"
  w_html = w_html & "1c2e2e1b0c23302f2b141b1b16161025070b0e0e0e211a0e07030f30272727321b0101010101011c31311b31310f01010000011b2f14041c2f18010101010101"
  w_html = w_html & "01010f2f321c2c1a0b120e291e210e0e0e0b0b2d13303030301b14151c2e2e1b0c232e0f30302f0c132d070b0e0e0e28252c0e0e071a2c2b2327271401010101"
  w_html = w_html & "010101182f310f1b32320f01000014140401012f14010101010101050601040f151826210b120e12212626211a0e120b0b2d1f3030301b151c2e2e1b0c1b152e"
  w_html = w_html & "30302320070b0e0e0e282507212c0e120b212a2a2a2b0f04060401010101010115311401181c2f0400001801010115310401010101010102202e23242427272c"
  w_html = w_html & "0b0b0e280b26262623211a0e120b0b2d1318010f1c2e2e1b0c150f1c2e290b0b120e1a1a12070728272c0e120b2c2a2a2a2a27222d04010101010101012b2f18"
  w_html = w_html & "01041c140000010101012f0c010101010102080c2a2727272a272727120b0e280b122626262623221a1a12070905010f1c2e2e1b0c150118090b121a1a1a1207"
  w_html = w_html & "0707252727210e0b25272a2a2a2a2a2a2a1b080501010101010f311b0101182b0000010101182b040101050804080d272a27272a2727272420070e1a0b0b2926"
  w_html = w_html & "262623232c221a1a1207060f1c2e2e1b0c150607121a1a1a280b0707070b2c24271a0e0b1227272a2a2a2a2a2a2a2102040805010101142b0101010f00000101"
  w_html = w_html & "01040f080605020909021b2a2a272a2a2724272321070e1a250b0b212626232323232c22191912072d2b2e1b03071219191a280b0707070707212424271a0e07"
  w_html = w_html & "2824272a2a2a2a272a2a2a13090305020602041c0101010100000101010101030b060501080d24272a272a2a27272423210b121a120b0b252326232323232324"
  w_html = w_html & "21221920250b2d0b121919191a2507070707070728242424270e0e0b212424272a2a2a27272a27270c0105030706010401010101000001010101010808030308"
  w_html = w_html & "041b23242427272a27242326270b121a280b0b0b282323232323232323272c272a2a2a27272c2c280b070707070707252c2424242c1a120b21232427272a2a27"
  w_html = w_html & "242727242e0a090306020101010101010000010101010105050108030d261e171d232424242626262712251a280b0b0b0b292326232324272a2a2a2a2a272727"
  w_html = w_html & "272727272c1a250707070b2123242424211a120b2c242324272a2a2423242724241302010204010101010101000001010101010409030204111e1e16241e1e16"
  w_html = w_html & "1d1e1e242428071a1a0b0b0b0b0b2123242727272730302f2e3030302e2f303027272c212507292323242424211a0b122c24232424272a232626242426260803"
  w_html = w_html & "060101010101010100000101010101010406030d1e1717162417171d1d171727232907201a0b0b0b0b0b12242727272e30302e1c1b1b2b1b1b1c2e30302f242c"
  w_html = w_html & "2c29242323242424221a07282724232626262424242624241e1e0d080401010101010101000001010101010101040111171716161d17161d16161d241e1f0720"
  w_html = w_html & "19120b0b0b0b212c2730301b1c1c1c150f1b30140f142b1b1c1c30302423242323242427192007282724241e1e171d1d1e1e1e24171e1b040101010101010101"
  w_html = w_html & "000001010101010105090d1e1716171f1d161616171d1d1d171e092d19280b0b25212c30302f1b15151c300f140c2f151514301b15141c30303023232424242c"
  w_html = w_html & "1920091f24271e1e17161d1617171e24171d2603010101010101010100000101010101010408131f2c2c2a0f1e161d1e1317161d171e0925191a0b25212c301c"
  w_html = w_html & "2b1c2e0f0f1b302b2f2e2b2b2f2e2f0c0f142f1c2b2b30232324242c1912091f23272417171616171d16171d17171d0f01010101010101010000010101010101"
  w_html = w_html & "0208041422212a131e1d2c2a1317161d170c060b191a0b212330301b0c1b2f1c2e2e2b2e23272727232e2b2e2b1c2f0c0c1c3030232424211925060c26242324"
  w_html = w_html & "171616161d16161d17161d14050101010101010100000101010101050609090d22272a2a2a2c222a14101616170a040919221a2330302b1418142b2e2b23272a"
  w_html = w_html & "272727272727272e2b2f1c1518142e30302e2721190b050c26261e161d1716161616161d17161629060101010101010100000101010506080204020a2c2a2a2a"
  w_html = w_html & "2a2a272c0a11161613090307291f23232f2e2b30142e2b2e272730312f2f2f2b2f2f3027232b2e2b14302b2f2f2323221907092d1e1e1d16171d17161616161d"
  w_html = w_html & "1d1616110208060401010101000001040508060309090d2c2a2a2a2a2a2a2a1b020c161401020307202323301b1b1b2b302b23243031313131312f1b2b1c1b2f"
  w_html = w_html & "30232e2b302b1b0c1b302e1f2007060511171616172a271d1616161d2a2a1d100903060205040101000002090308050401182c2a2a2a2a27272a2a2a290d1305"
  w_html = w_html & "010101062d2e30300c0f152b2b2e30313131313131312f1b1b0c1c2f2f30232b2b1c0f0f1b30232e2d0601010f1716161d2a2a2a1d16161d2a2a2a2304050508"
  w_html = w_html & "03030501000005090909090d0d222a2a2a2a2a2727272a2a2c202d0d03060208292e302e1b1b0c2e2b23313131313131312b0c1414140c2f2f2f30231c2f0f1c"
  w_html = w_html & "1c30302e2806020a0d1e1d161d2a2a2a2a1d161d2a2a2a27220d09090903040100000105050405080a27272a272a2a2423272a27241402080a0309071f23302b"
  w_html = w_html & "2e302f1c2e3031313131313131312b141815141c2f2f2f302e1c2e302e2e3023220709030a0a1e161d2a272a272a1d1d2a2a2a27230f05040504010100000101"
  w_html = w_html & "050808021b24272a24272a2423242a2723110205040509292e301b0c0c1b2e2b30313131313131313131311c182f3131312f2f30231b2f0c0c0c1b302e2d0304"
  w_html = w_html & "04050c1d1d272727272a2a272a2a2a27240f080804010101000001010103090923232427262427242624241d1e1e0c0a0309201f2e301c180f2e1c2e32313131"
  w_html = w_html & "31313131313131311c313131312f2f2f302b1c1c180f2e302b1f2d09030a1324241e1e27242a2a24272a2a272420090601010101000001010101051826262424"
  w_html = w_html & "1e171d1e1e1e241617100c020920222e2e301c1b1b2b1b2e31312b2f3131313131312f313131313131312f2f302b1b1c1b0c2b302b1f222d0905141e1e161627"
  w_html = w_html & "23272723272a2a27241405010101010100000101010108091e1e1d1d17171d16171e1d1617172d0720221f2323302e2f2f2f1b2331312b1c1c1b313232312b1c"
  w_html = w_html & "2b1b313131312f2f2f2e1b2f2f2e2e302e1f222220090a1017161624241e1e1e242727242420020101010101000001010101040f1e171d1d17161d16171d1d16"
  w_html = w_html & "162d0720221f232e232f1c1b1b2e1b30312f1b1b0c1c323232311c1b0c1c31313131312f2f2e0c2f1b1b1c2f2e2928222220072d101617241e171716241e1e24"
  w_html = w_html & "2614010101010101000001010101040a1e17171d16161d16171d16162d0b20191f23262e2e1b15151b2e1b30311b150f0f143132321c150f0f142f313131312f"
  w_html = w_html & "2f2e0c300c0f142b2e2907122219200712101d1e171d17161d1616171e0a01010101010100000101010106091e17171d17161616171d162d0b20191f2626262e"
  w_html = w_html & "2e3014141b2e1b2f31312b18141c2f3232323118151c2b3131312f2f2f2e0c2e1b151b302b29070725221920072d1d17161d1d161d1616171709020101010101"
  w_html = w_html & "0000010101010104111617171d16161d1721250b1a191f262626262e2e302b2f2f2f0c2e31313114323232323232321b2f31311c2f2b1c2f302b1b2f302f2f30"
  w_html = w_html & "2b20070707251a19200b2d10161d17161d161d16110401010101010100000101040309091f1617171d16161d11090b1a191f2626262626262b302e1c1b2e1b1c"
  w_html = w_html & "3231323132323232323232313131311b1b1b2b2f301b1b2b1b1b2f301c2d07070707251a19200b2d101616171d17161620090903040101010000010102080805"
  w_html = w_html & "14171d1716161d21090b1a191f262626262626231c301b14141c1c1b2f313132323232313232323131311c0c0c0c1b2f2e0c2b1c14141c301b25070707070725"
  w_html = w_html & "1a1a200b2d10161d171d1610180508080501010100000102040402080c291d171d1d1025251a1a1f26262626262623231c231c0f141c2f0c1c3231323232322b"
  w_html = w_html & "1c2b1b31312f1c140f0f0c2b1b0c2f1c15152b2e11070707070707070b1a1a200b25101d161d16110608020405050101000002090925120d0d0a1b161d102525"
  w_html = w_html & "0e1a1f262626262626242c29222e301c2b2f2e1c0c2e31313232322b1b1b1b313131312f182b312b142b2e302b2b301c2e21282507070707070b1a1a0e0b2510"
  w_html = w_html & "1616110a030d250909090501000008090602050405080a101007250e1a1f26262626242128120b0b281b302f1b1c1c300c0c2f3131311c140c14142f31313131"
  w_html = w_html & "1c312e140c2f1c1b1c30231b2323232421282507070707200e0e0b2510100a02050405080309020100000104050603090903060809120e0e1f1e26262128120b"
  w_html = w_html & "0b0b0b0b0b112e301b15151c2f140c2f3131312b0f0f141c31313131312b14142f1b15151c301c2b232323232424242c28250707280e0e250b030a0309090306"
  w_html = w_html & "05040101000001010102060205080609120e0e1f242128120b0b0b0b0b0b0b0b0b201b2e0c0f0c302f2e14142b3131310f1c3131313131301b15142e2f2f0f14"
  w_html = w_html & "1b2f0c23232323232424242424242c2912280e0e1209060205020605010101010000010101010503090907120e0e212128120b0b0b0b0b0b0b0b0b0b0b0b131c"
  w_html = w_html & "302f2b2b1c2b2e0c0f141c2f2f313131322f1b150f1b2f2b1c2b1c2f301b2b23232323232424242424242427272c1a0e0e12070b070601010101010100000101"
  w_html = w_html & "010101020809120e0e0e0e0e1a1a1a1a1a28282812250b0b0b0b251b2b302f0c14142e2f2b140f0f0f15150f181818142b2f1c14140c2f301c1b232323232324"
  w_html = w_html & "24272c2c2121211a1a0e0e0e0e0e1209080501010101010100000101010101050307070b0b12120e0e0e1a1a1a1919192222211a1a28281a0c2e30151814302b"
  w_html = w_html & "2e2e2b1b14150f15141b2e2f2b2b300f180c302b0c23242c2c21211f221919191a1a1a0e0e0e12120b0b0707030101010101010100000101010101010409090d"
  w_html = w_html & "2d250b0707070b25121220201919222222221f1f1f0c2b2b2f1c1b0c141b302b2f2e2f2b2f2b2f0c140c1c1c2f2e1c141f1f2222222222191920201225250b07"
  w_html = w_html & "07070b25122d0903010101010101010100000101010101010502041527212c2c2c21292812250b0707070b252d202922221f0c1c30302b0f0f1b2f1b1b1c301b"
  w_html = w_html & "1b1b300c18152e30301b141f2229202d12250b090907070b2512281a212c2a2a271b040204010101010101010000010101010101050609031c2a2a272427272a"
  w_html = w_html & "27272a2c210d06080309030907072d0c0c2e30152b1c1b15150c2e140f141c1b1c14302b140c25070709030906020505020d132c2a2a2a272a2a2a2a27210306"
  w_html = w_html & "0401010101010101000001010101010109030201020d21272727272a2a2a2a2723230c0a0d050108030508251c141b2e3030300c142b2e2b0f2b3030302b1415"
  w_html = w_html & "13070505030201020d081813272724272a2a2a272a2a2a2a271b020303010101010101010000010101010105050102030d081b272a2724272a2a272723262626"
  w_html = w_html & "1801080d04010a09201f1b15141c2e232f30303030232b1b0f151b1f2d090601050d020105222c2a2a27232a2a2a2a27272a2a27241302010204010101010101"
  w_html = w_html & "00000101010101080803030804021f272a2724272a2a2423241e1e2611080d0501080302251f1f232b1b150f0f150f0f1818150d2d281f2207050d0201020d02"
  w_html = w_html & "1c23272a2724242a2a2a2a2727272a2723130303020801010101010100000101010105070908040203031b24272724232727242626241e1e1e130201020d0508"
  w_html = w_html & "07291f2c262323232e2e222d12250b070b211f2d0902020d0501021f262623241e2624272a2a272427242724230f050609090401010101010000010101010809"
  w_html = w_html & "0605030906041423272724261e1e241e261e241e1e1101040d08010d06251f21232323232424210707070707281f2209030a010a0a041526261e1e16171d2727"
  w_html = w_html & "272a2724241e171e11090604030305010101010100000101010101010102060501020d232424271e1616171e1e1e24171e1e010a0a01080d0403291f2c232324"
  w_html = w_html & "242721070707070b21222d06050d05040d0a111e1e1e1717162a2a24242727241e16161e18020805040101010101010100000101010101010101010101060326"
  w_html = w_html & "261e17171d161717171e1e2c0f18081305010d08010825221f232323242421070707071222220702010a0a0102221e1e1e1d1d1d2a2a2a241e1d24241716160f"
  w_html = w_html & "010101010101010101010101000001010101010101010101010404291e171d16171d161616171e211b05130801080d04010a0729222c23232427210707070b1a"
  w_html = w_html & "22200b0a01050d0201111e241e1d272727272a1e1716171d16161101010101010101010101010101000001010101010101010101010102032616161616171d1d"
  w_html = w_html & "1717172c150d0d02050d0801040d0825221f24232427210707071222220b080d04010a0a182326242424242724242a1d1616161d161615010101010101010101"
  w_html = w_html & "01010101000001010101010101010101010104040d10161616171b262424261b0c23232c2c2c1504080a040920222c2324272107070b1a222003010d18040513"
  w_html = w_html & "152626241e1e172426241d1d1616161d171b040101010101010101010101010100000101010101010101010101010403031811161617132c2a270c2324242424"
  w_html = w_html & "2a2a242b1302010825191f2424272107071222190b02011514140f0a211e241e1e171d1e1e171d1d161610110d03010101010101010101010101010100000101"
  w_html = w_html & "01010101010101010101040604020d1b1617212c2127232427272a2727242424261b04080720192c242721070b1a19200702181b1a1a130f1f1e1e1e1716241e"
  w_html = w_html & "17161d1d161613050406010101010101010101010101010100000101010101010101010101010101020d02010d26152122212c272427272a2a2724241e1e110a"
  w_html = w_html & "062519212427210725221925030a150f22190f0a111e1e171616241716171e17100f0a0d05010101010101010101010101010101000001010101010101010101"
  w_html = w_html & "010105030308010603180a2222222127242424272a272424171e1e1f050720192c27210b281920070a0d0f0c140c02031b10171617111d1710130c0809080106"
  w_html = w_html & "03060401010101010101010101010101000001010101010101010101010102070601060905080d0202091f27272324272a2423242613111e0212121921272125"
  w_html = w_html & "1a19250d0f2b140d050d050a1f111f0c130c26260f02030202090801030705010101010101010101010101010000010101010101010101010101050805050605"
  w_html = w_html & "040608010303142424242624241e1e1e2402080a080d07201a2721281a200929110e22151513130f131813050213050903010608010206050208040101010101"
  w_html = w_html & "010101010101010100000101010101010101010101010101010101010101010209050d2324241e171d17171e1e1b0a080a0a06121a21211a1a25061515221c14"
  w_html = w_html & "0a0c13010d050a0a010a0a0209050101010101010101010101010101010101010101010100000101010101010101010101010101010101010101010102020d14"
  w_html = w_html & "1e17161d1d16171d1e1e20050d0a0507201a211a2007051513141414190c22151c14020d05050d05050101010101010101010101010101010101010101010101"
  w_html = w_html & "0000010101010101010101010101010101010101010101010403080113101617171d1e17171711040d02020b120e0e0e1209050a131f1a0c13131a1c1919180a"
  w_html = w_html & "0a010603010101010101010101010101010101010101010101010101000001010101010101010101010101010101010101010101020304020d0513161717180d"
  w_html = w_html & "0a0c13050d050803070e0e0e070305150f191a1b0c1b1c151a1914020d0504030401010101010101010101010101010101010101010101010000010101010101"
  w_html = w_html & "010101010101010101010101010101010105010306040d0f1011040d050606020d01080a09120e120603020f130c140c150d020d0c0c0d010306010501010101"
  w_html = w_html & "01010101010101010101010101010101010101010000010101010101010101010101010101010101010101010101020905080301080c0203010302080d010a06"
  w_html = w_html & "03070e07030308010d0c0c0a05090506020403020209020101010101010101010101010101010101010101010101010100000101010101010101010101010101"
  w_html = w_html & "01010101010101010108030601060501010101080409050a0a01030609070b070306060103080203010801010101020601030302010101010101010101010101"
  w_html = w_html & "01010101010101010101010100000101010101010101010101010101010101010101010101060706010401010101010102090103060103080906070309060301"
  w_html = w_html & "06060509050101010101010401060708010101010101010101010101010101010101010101010101000001010101010101010101010101010101010101010101"
  w_html = w_html & "01020604010101010101010102030409080109080605080506060301060301030501010101010101010506050101010101010101010101010101010101010101"
  w_html = w_html & "01010101000001010101010101010101010101010101010101010101010101010101010101010101010502090504030501010101010203010809050501010101"
  w_html = w_html & "01010101010101010101010101010101010101010101010101010101010101010000010101010101010101010101010101010101010101010101010101010101"
  w_html = w_html & "01010101010403070801050101010101010105010607060101010101010101010101010101010101010101010101010101010101010101010101010100000101"
  w_html = w_html & "01010101010101010101010101010101010101010101010101010101010101010101020304010101010101010101010105060501010101010101010101010101"
  w_html = w_html & "01010101010101010101010101010101010101010101010100000101010101010101010101010101010101010101010101010101010101010101010101010101"
  w_html = w_html & "010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010101010000040000002701ffff030000000000}\par}}}{\f1\fs16\insrsid1469200 Minist\'e9rio da Justi\'e7a"
  w_html = w_html & "\par }\pard \qc \li0\ri0\widctlpar\aspalpha\aspnum\faauto\adjustright\rin0\lin0\itap0 {\f1\fs16\insrsid1469200 Secretaria Executiva"
  w_html = w_html & "\par Subsecretaria de Planejamento, Or\'e7amento e Administra\'e7\'e3o"
  w_html = w_html & "\par Coordena\'e7\'e3o-Geral de Log\'edstica"
  w_html = w_html & "\par Divis\'e3o de Execu\'e7\'e3o Or\'e7ament\'e1ria e Financeira"
  w_html = w_html & "\par "
  w_html = w_html & "\par }\trowd \irow0\irowband0\lastrow \ts11\trgaph70\trleft-70\trftsWidth1\trpaddl70\trpaddr70\trpaddfl3\trpaddfr3 \clvertalt\clbrdrt\brdrtbl \clbrdrl\brdrtbl \clbrdrb\brdrtbl \clbrdrr\brdrtbl \cltxlrtb\clftsWidth3\clwWidth9430\clshdrawnil \cellx9360"
  w_html = w_html & "\pard\plain \s1\qc \li0\ri0\keepn\widctlpar\intbl\aspalpha\aspnum\faauto\outlinelevel0\adjustright\rin0\lin0 \b\i\f1\fs24\lang1046\langfe1046\cgrid\langnp1046\langfenp1046 {\insrsid1469200 RELAT\'d3RIO DE VIAGENS NACIONAIS/INTERNACIONAIS"
  w_html = w_html & "\par }\pard\plain \qc \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 \fs24\lang1046\langfe1046\cgrid\langnp1046\langfenp1046 {\f1\fs18\insrsid1469200 Portaria N\'ba 47/MPO 29/04/2003 \endash  DOU 30/04/2003\cell }\pard "
  w_html = w_html & "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\b\insrsid1469200 \trowd \irow0\irowband0\lastrow \ts11\trgaph70\trleft-70\trftsWidth1\trpaddl70\trpaddr70\trpaddfl3\trpaddfr3 \clvertalt\clbrdrt\brdrtbl \clbrdrl\brdrtbl \clbrdrb"
  w_html = w_html & "\brdrtbl \clbrdrr\brdrtbl \cltxlrtb\clftsWidth3\clwWidth9430\clshdrawnil \cellx9360\row }\pard \ql \li0\ri0\widctlpar\aspalpha\aspnum\faauto\adjustright\rin0\lin0\itap0 {\b\fs16\insrsid1469200 "
  w_html = w_html & "\par }\trowd \irow0\irowband0\ts11\trgaph70\trleft-70\trbrdrt\brdrs\brdrw10 \trbrdrl\brdrs\brdrw10 \trbrdrb\brdrs\brdrw10 \trbrdrr\brdrs\brdrw10 \trbrdrh\brdrs\brdrw10 \trbrdrv\brdrs\brdrw10 \trftsWidth1\trpaddl70\trpaddr70\trpaddfl3\trpaddfr3 \clvertalt"
  w_html = w_html & "\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \cltxlrtb\clftsWidth3\clwWidth9430\clshdrawnil \cellx9360\pard\plain "
  w_html = w_html & "\s1\ql \li0\ri0\keepn\widctlpar\intbl\aspalpha\aspnum\faauto\outlinelevel0\adjustright\rin0\lin0 \b\i\f1\fs24\lang1046\langfe1046\cgrid\langnp1046\langfenp1046 {\insrsid1469200 1 \endash  IDENTIFICA\'c7\'c3O DO SERVIDOR\cell }\pard\plain "
  w_html = w_html & "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 \fs24\lang1046\langfe1046\cgrid\langnp1046\langfenp1046 {\insrsid1469200 \trowd \irow0\irowband0\ts11\trgaph70\trleft-70\trbrdrt\brdrs\brdrw10 \trbrdrl\brdrs\brdrw10 \trbrdrb"
  w_html = w_html & "\brdrs\brdrw10 \trbrdrr\brdrs\brdrw10 \trbrdrh\brdrs\brdrw10 \trbrdrv\brdrs\brdrw10 \trftsWidth1\trpaddl70\trpaddr70\trpaddfl3\trpaddfr3 \clvertalt\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 "
  w_html = w_html & "\cltxlrtb\clftsWidth3\clwWidth9430\clshdrawnil \cellx9360\row }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\insrsid1469200 "
  w_html = w_html & "\par }\pard \ql \li0\ri0\widctlpar\intbl\tx5220\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\insrsid1469200 Nome:  }{\insrsid6709522" &RS1("nm_pessoa")& "}{\insrsid1469200                }{\insrsid6709522                 }{\insrsid1469200 Matr\'ed"
  w_html = w_html & "cula SIAPE: "
  w_html = w_html & "\par Fun\'e7\'e3o/Cargo : " &RS1("nm_tipo_vinculo")& "                             C\'f3digo: "
  DB_GetCustomerData RS2, Session("p_cliente")
  w_html = w_html & "\par }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\insrsid1469200 \'d3rg\'e3o de Exerc\'edcio: " & RS2("nome_resumido")
  RS2.Close
  w_html = w_html & "\par \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\insrsid1469200 \trowd \irow1\irowband1\lastrow \ts11\trgaph70\trleft-70\trbrdrt\brdrs\brdrw10 \trbrdrl\brdrs\brdrw10 \trbrdrb\brdrs\brdrw10 \trbrdrr\brdrs\brdrw10 "
  w_html = w_html & "\trbrdrh\brdrs\brdrw10 \trbrdrv\brdrs\brdrw10 \trftsWidth1\trpaddl70\trpaddr70\trpaddfl3\trpaddfr3 \clvertalt\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \cltxlrtb\clftsWidth3\clwWidth9430\clshdrawnil "
  w_html = w_html & "\cellx9360\row }\pard \ql \li0\ri0\widctlpar\aspalpha\aspnum\faauto\adjustright\rin0\lin0\itap0 {\fs16\insrsid1469200 "
  w_html = w_html & "\par }\pard\plain \s1\ql \li0\ri0\keepn\widctlpar\intbl\aspalpha\aspnum\faauto\outlinelevel0\adjustright\rin0\lin0 \b\i\f1\fs24\lang1046\langfe1046\cgrid\langnp1046\langfenp1046 {\insrsid1469200 2 \endash  IDENTIFICA\'c7\'c3O DO AFASTAMENTO\tab \cell "
  w_html = w_html & "}\pard\plain \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 \fs24\lang1046\langfe1046\cgrid\langnp1046\langfenp1046 {\insrsid1469200 \trowd \irow0\irowband0\ts11\trgaph70\trleft-70\trbrdrt\brdrs\brdrw10 \trbrdrl\brdrs\brdrw10 "
  w_html = w_html & "\trbrdrb\brdrs\brdrw10 \trbrdrr\brdrs\brdrw10 \trbrdrh\brdrs\brdrw10 \trbrdrv\brdrs\brdrw10 \trftsWidth1\trpaddl70\trpaddr70\trpaddfl3\trpaddfr3 \clvertalt\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 "
  w_html = w_html & "\cltxlrtb\clftsWidth3\clwWidth9430\clshdrawnil \cellx9360\row }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\insrsid1469200 Autoriza\'e7\'e3o do Afastamento: "
  w_html = w_html & "\par "
  w_html = w_html & "\par Percurso: " &lCase(w_percurso)& "     \par Di\'e1rias recebidas: Qtd: " &FormatNumber(w_diaria,1)& " Valor: " &FormatNumber(w_valor,2)
  w_html = w_html & "\par "
  w_html = w_html & "\par }\pard \ql \li0\ri0\widctlpar\intbl\tx5040\tx5220\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\insrsid1469200 Sa\'edda: " &FormataDataEdicao(RS("inicio"))& "                                         Chegada:  " &FormataDataEdicao(RS("fim"))& ""
  w_html = w_html & "\par }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\insrsid1469200 \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\insrsid1469200 \trowd \irow1\irowband1\lastrow "
  w_html = w_html & "\ts11\trgaph70\trleft-70\trbrdrt\brdrs\brdrw10 \trbrdrl\brdrs\brdrw10 \trbrdrb\brdrs\brdrw10 \trbrdrr\brdrs\brdrw10 \trbrdrh\brdrs\brdrw10 \trbrdrv\brdrs\brdrw10 \trftsWidth1\trpaddl70\trpaddr70\trpaddfl3\trpaddfr3 \clvertalt\clbrdrt\brdrs\brdrw10 "
  w_html = w_html & "\clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \cltxlrtb\clftsWidth3\clwWidth9430\clshdrawnil \cellx9360\row }\pard \ql \li0\ri0\widctlpar\aspalpha\aspnum\faauto\adjustright\rin0\lin0\itap0 {\fs16\insrsid1469200 "
  w_html = w_html & "\par }\pard\plain \s1\ql \li0\ri0\keepn\widctlpar\intbl\aspalpha\aspnum\faauto\outlinelevel0\adjustright\rin0\lin0 \b\i\f1\fs24\lang1046\langfe1046\cgrid\langnp1046\langfenp1046 {\insrsid1469200 3 \endash  DESCRI\'c7\'c3O SUCINTA DA VIAGEM\cell }\pard\plain "
  w_html = w_html & "\ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 \fs24\lang1046\langfe1046\cgrid\langnp1046\langfenp1046 {\insrsid1469200 \trowd \irow0\irowband0\ts11\trgaph70\trleft-70\trbrdrt\brdrs\brdrw10 \trbrdrl\brdrs\brdrw10 \trbrdrb"
  w_html = w_html & "\brdrs\brdrw10 \trbrdrr\brdrs\brdrw10 \trbrdrh\brdrs\brdrw10 \trbrdrv\brdrs\brdrw10 \trftsWidth1\trpaddl70\trpaddr70\trpaddfl3\trpaddfr3 \clvertalt\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 "
  w_html = w_html & "\cltxlrtb\clftsWidth3\clwWidth9430\clshdrawnil \cellx9360\row }\trowd \irow1\irowband1\ts11\trgaph70\trleft-70\trbrdrt\brdrs\brdrw10 \trbrdrl\brdrs\brdrw10 \trbrdrb\brdrs\brdrw10 \trbrdrr\brdrs\brdrw10 \trbrdrh\brdrs\brdrw10 \trbrdrv\brdrs\brdrw10 "
  w_html = w_html & "\trftsWidth1\trpaddl70\trpaddr70\trpaddfl3\trpaddfr3 \clvertalt\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \cltxlrtb\clftsWidth3\clwWidth1150\clshdrawnil \cellx1080\clvertalt\clbrdrt\brdrs\brdrw10 \clbrdrl"
  w_html = w_html & "\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \cltxlrtb\clftsWidth3\clwWidth8280\clshdrawnil \cellx9360\pard\plain \s1\qc \li0\ri0\keepn\widctlpar\intbl\aspalpha\aspnum\faauto\outlinelevel0\adjustright\rin0\lin0 "
  w_html = w_html & "\b\i\f1\fs24\lang1046\langfe1046\cgrid\langnp1046\langfenp1046 {\b0\insrsid1469200 Data"
  w_html = w_html & "\par }\pard\plain \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 \fs24\lang1046\langfe1046\cgrid\langnp1046\langfenp1046 {\insrsid1469200 "
  w_html = w_html & "\par "
  w_html = w_html & "\par "
  w_html = w_html & "\par "
  w_html = w_html & "\par "
  w_html = w_html & "\par "
  w_html = w_html & "\par "
  w_html = w_html & "\par "
  w_html = w_html & "\par "
  w_html = w_html & "\par "
  w_html = w_html & "\par "
  w_html = w_html & "\par "
  w_html = w_html & "\par "
  w_html = w_html & "\par "
  w_html = w_html & "\par "
  w_html = w_html & "\par "
  w_html = w_html & "\par "
  w_html = w_html & "\par "
  w_html = w_html & "\par "
  w_html = w_html & "\par \cell }\pard\plain \s1\qc \li0\ri0\keepn\widctlpar\intbl\aspalpha\aspnum\faauto\outlinelevel0\adjustright\rin0\lin0 \b\i\f1\fs24\lang1046\langfe1046\cgrid\langnp1046\langfenp1046 {\b0\insrsid1469200 Atividades"
  w_html = w_html & "\par }\pard\plain \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 \fs24\lang1046\langfe1046\cgrid\langnp1046\langfenp1046 {\insrsid1469200 "
  w_html = w_html & "\par "
  w_html = w_html & "\par "
  w_html = w_html & "\par \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\insrsid1469200 \trowd \irow1\irowband1\ts11\trgaph70\trleft-70\trbrdrt\brdrs\brdrw10 \trbrdrl\brdrs\brdrw10 \trbrdrb\brdrs\brdrw10 \trbrdrr\brdrs\brdrw10 \trbrdrh"
  w_html = w_html & "\brdrs\brdrw10 \trbrdrv\brdrs\brdrw10 \trftsWidth1\trpaddl70\trpaddr70\trpaddfl3\trpaddfr3 \clvertalt\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \cltxlrtb\clftsWidth3\clwWidth1150\clshdrawnil \cellx1080"
  w_html = w_html & "\clvertalt\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \cltxlrtb\clftsWidth3\clwWidth8280\clshdrawnil \cellx9360\row }\trowd \irow2\irowband2\lastrow \ts11\trgaph70\trleft-70\trbrdrt\brdrs\brdrw10 \trbrdrl"
  w_html = w_html & "\brdrs\brdrw10 \trbrdrb\brdrs\brdrw10 \trbrdrr\brdrs\brdrw10 \trbrdrh\brdrs\brdrw10 \trbrdrv\brdrs\brdrw10 \trftsWidth1\trpaddl70\trpaddr70\trpaddfl3\trpaddfr3 \clvertalt\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr"
  w_html = w_html & "\brdrs\brdrw10 \cltxlrtb\clftsWidth3\clwWidth9430\clshdrawnil \cellx9360\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\insrsid1469200 "
  w_html = w_html & "Data: _____/_____/_____                                __________________________________    "
  w_html = w_html & "\par                                                                                 Assinatura do Servidor/Colaborador"
  w_html = w_html & "\par \cell }\pard \ql \li0\ri0\widctlpar\intbl\aspalpha\aspnum\faauto\adjustright\rin0\lin0 {\insrsid1469200 \trowd \irow2\irowband2\lastrow \ts11\trgaph70\trleft-70\trbrdrt\brdrs\brdrw10 \trbrdrl\brdrs\brdrw10 \trbrdrb\brdrs\brdrw10 \trbrdrr\brdrs\brdrw10 "
  w_html = w_html & "\trbrdrh\brdrs\brdrw10 \trbrdrv\brdrs\brdrw10 \trftsWidth1\trpaddl70\trpaddr70\trpaddfl3\trpaddfr3 \clvertalt\clbrdrt\brdrs\brdrw10 \clbrdrl\brdrs\brdrw10 \clbrdrb\brdrs\brdrw10 \clbrdrr\brdrs\brdrw10 \cltxlrtb\clftsWidth3\clwWidth9430\clshdrawnil "
  w_html = w_html & "\cellx9360\row }\pard \ql \li0\ri0\widctlpar\aspalpha\aspnum\faauto\adjustright\rin0\lin0\itap0 {\insrsid1469200 "
  w_html = w_html & "\par }}"
  RS1.Close
  DesconectaBD
  
  RelatorioViagem = w_html
End Function

REM =========================================================================
REM Rotina de preparação para envio de e-mail relativo a PCDs
REM Finalidade: preparar os dados necessários ao envio automático de e-mail
REM Parâmetro: p_solic: número de identificação da solicitação. 
REM            p_tipo:  1 - Inclusão
REM                     2 - Tramitação
REM                     3 - Conclusão
REM -------------------------------------------------------------------------
Sub SolicMail(p_solic, p_tipo)

  Dim w_cab, w_html, w_texto, w_solic, RSM, w_resultado, w_destinatarios, w_anexos
  Dim w_assunto, w_assunto1, l_solic, w_nome, w_sg_tramite, w_file, FS, F1
  
  l_solic         = p_solic
  w_destinatarios = ""
  w_resultado     = ""
  w_anexos        = null
  
  ' Recupera os dados da PCD
  DB_GetSolicData RSM, p_solic, "PDGERAL"
  w_sg_tramite = RSM("sg_tramite")

  w_nome = RSM("codigo_interno")

  w_html = "<HTML>" & VbCrLf
  w_html = w_html & BodyOpenMail(null) & VbCrLf
  w_html = w_html & "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">" & VbCrLf
  w_html = w_html & "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">" & VbCrLf
  w_html = w_html & "    <table width=""97%"" border=""0"">" & VbCrLf
  If p_tipo = 1 Then
     w_html = w_html & "      <tr valign=""top""><td align=""center""><font size=2><b>INCLUSÃO DE PCD</b></font><br><br><td></tr>" & VbCrLf
  ElseIf w_sg_tramite = "EE" Then
     w_html = w_html & "      <tr valign=""top""><td align=""center""><font size=2><b>PRESTAÇÃO DE CONTAS DE PCD</b></font><br><br><td></tr>" & VbCrLf
  ElseIf p_tipo = 2 Then
     w_html = w_html & "      <tr valign=""top""><td align=""center""><font size=2><b>TRAMITAÇÃO DE PCD</b></font><br><br><td></tr>" & VbCrLf
  ElseIf p_tipo = 3 Then
     w_html = w_html & "      <tr valign=""top""><td align=""center""><font size=2><b>CONCLUSÃO DE PCD</b></font><br><br><td></tr>" & VbCrLf
  End IF
  
  If w_sg_tramite = "EE" Then
     w_html = w_html & "      <tr valign=""top""><td><font size=1><b><font color=""#BC3131"">ATENÇÃO</font>:<br>Conforme Portaria Nº 47/MPO 29/04/2003 – DOU 30/04/2003, é necessário elaborar o relatório de viagem e entregar os bilhetes de embarque.<br><br>Use o arquivo anexo para elaborar seu relatório de viagem e entregue-o assinado ao setor competente, juntamente com os bilhetes.</b></font><br><br><td></tr>" & VbCrLf
  Else
     w_html = w_html & "      <tr valign=""top""><td><font size=2><b><font color=""#BC3131"">ATENÇÃO</font>: Esta é uma mensagem de envio automático. Não responda esta mensagem.</b></font><br><br><td></tr>" & VbCrLf
  End If

  w_html = w_html & VbCrLf & "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  w_html = w_html & VbCrLf & "    <table width=""99%"" border=""0"">"
  'w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Ação: <b>" & RSM("nm_projeto") & "</b></td>"
  'w_html = w_html & VbCrLf & "      <tr><td><font size=1>Detalhamento: <b>" & CRLF2BR(RSM("assunto")) & "</b></font></td></tr>"
      
  ' Identificação da PCD
  w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>EXTRATO DA PCD</td>"
  w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
  w_html = w_html & VbCrLf & "          <tr valign=""top"">"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Proposto:<br><b>" & RSM("nm_prop") & "</b></td>"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Unidade proponente:<br><b>" & RSM("nm_unidade_resp") & "</b></td>"
  w_html = w_html & VbCrLf & "          <tr valign=""top"">"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Primeira saída:<br><b>" & FormataDataEdicao(RSM("inicio")) & " </b></td>"
  w_html = w_html & VbCrLf & "          <td><font size=""1"">Último retorno:<br><b>" & FormataDataEdicao(RSM("fim")) & " </b></td>"
  w_html = w_html & VbCrLf & "          </table>"

  ' Informações adicionais
  If Nvl(RSM("descricao"),"") > "" Then 
     If Nvl(RSM("descricao"),"") > "" Then w_html = w_html & VbCrLf & "      <tr><td valign=""top""><font size=""1"">Descrição da PCD:<br><b>" & CRLF2BR(RSM("descricao")) & " </b></td>" End If
  End If

  w_html = w_html & VbCrLf & "    </table>"
  w_html = w_html & VbCrLf & "</tr>"

  If p_tipo = 2 Then ' Se for tramitação
     ' Encaminhamentos
     DB_GetSolicLog RS, p_solic, null, "LISTA"
     RS.Sort = "data desc"
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>ÚLTIMO ENCAMINHAMENTO</td>"
     w_html = w_html & VbCrLf & "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
     w_html = w_html & VbCrLf & "          <tr valign=""top"">"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">De:<br><b>" & RS("responsavel") & "</b></td>"
     w_html = w_html & VbCrLf & "          <td><font size=""1"">Despacho:<br><b>" & CRLF2BR(Nvl(RS("despacho"),"---")) & " </b></td>"
     w_html = w_html & VbCrLf & "          </table>"
          
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
   
  ' Prepara os dados necessários ao envio
  DB_GetCustomerData RS, Session("p_cliente")
  If p_tipo = 1 or p_tipo = 3 Then ' Inclusão ou Conclusão
     If p_tipo = 1 Then w_assunto = "Inclusão - " & w_nome Else w_assunto = "Encerramento - " & w_nome End If
  ElseIf w_sg_tramite = "EE" Then ' Prestação de contas
     w_assunto = "Prestação de Contas - " & w_nome
  ElseIf p_tipo = 2 Then ' Tramitação
     w_assunto = "Tramitação - " & w_nome
  End If
  DesconectaBD
  
  ' Se for o trâmite de prestação de contas, envia e-mail ao proposto com o relatório de viagem anexado
  If w_sg_tramite = "EE" Then
     
     ' Gera o arquivo de exportação
     Set FS = CreateObject("Scripting.FileSystemObject")

     ' Configura o nome dos arquivo recebido e do arquivo registro
     w_file    = "relatorio_" & replace(w_nome,"/","-") & ".doc"

     Set F1 = FS.CreateTextFile(conFilePhysical & w_cliente & "\" & w_file, true)
     F1.WriteLine RelatorioViagem(p_solic)
     F1.Close
     
     w_anexos = conFilePhysical & w_cliente & "\" & w_file

  End If
  
  ' Configura os destinatário da mensagem
  DB_GetTramiteResp RS, p_solic, null, null
  If Not RS.EOF Then
     While Not RS.EOF
        If Instr(w_destinatarios,RS("email") & "; ") = 0 Then w_destinatarios = w_destinatarios & RS("email") & "; " End If
        RS.MoveNext
     wend
  End If
  
  ' Recupera o e-mail do responsável
  DB_GetPersonData RS, w_cliente, RSM("solicitante"), null, null
  If Instr(w_destinatarios,RS("email") & "; ") = 0 Then w_destinatarios = w_destinatarios & RS("email") & "; " End If
  DesconectaBD

  ' Recupera o e-mail do proposto
  DB_GetPersonData RS, w_cliente, RSM("sq_prop"), null, null
  If Instr(w_destinatarios,RS("email") & "; ") = 0 Then w_destinatarios = w_destinatarios & RS("email") & "; " End If
  DesconectaBD

  If w_destinatarios > "" Then
     ' Executa o envio do e-mail
     w_resultado = EnviaMail(w_assunto, w_html, w_destinatarios, w_anexos)
  End If
        
  If w_sg_tramite = "EE" Then
     ' Remove o arquivo temporário
     FS.DeleteFile conFilePhysical & w_cliente & "\" & w_file, true
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
  Dim FS, F1, w_file, w_tamanho, w_tipo, w_nome, field, w_maximo
  Dim w_chave_nova
  Dim i, j
  
  w_file    = ""
  w_tamanho = ""
  w_tipo    = ""
  w_nome    = ""
  
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
          
          dbms.BeginTrans
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
           
          If O = "I" Then
             ' Recupera os dados para montagem correta do menu
             DB_GetMenuData RS1, w_menu
             ScriptOpen "JavaScript"
             ShowHTML "  alert('" & w_codigo & " cadastrada com sucesso!');"
             ShowHTML "  parent.menu.location='../Menu.asp?par=ExibeDocs&O=A&w_chave=" & w_chave_nova & "&w_documento=" & w_codigo & "&R=" & R & "&SG=" & RS1("sigla") & "&TP=" & RemoveTP(TP) & "';"
             ScriptClose          
          ElseIf O = "E" Then
             ScriptOpen "JavaScript"
             ShowHTML "  location.href='" & R & "&O=L&R=" & R & "&SG="&Mid(SG,1,2)&"INICIAL&w_menu=" & w_menu & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & MontaFiltro("GET") & "';"
             ScriptClose          
          Else
             ' Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link
             DB_GetLinkData RS1, Session("p_cliente"), SG
             ScriptOpen "JavaScript"
             ShowHTML "  location.href='" & replace(RS1("link"),w_dir,"") & "&O=" & O & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
             ScriptClose          
          End If
          dbms.CommitTrans
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
 
          dbms.BeginTrans
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
          dbms.CommitTrans
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

          dbms.BeginTrans
          'ExibeVariaveis
          DML_PutPD_Deslocamento O, _
            Request("w_chave"), Request("w_chave_aux"), _ 
            Request("w_cidade_orig"), Request("w_data_saida"), Request("w_hora_saida"), _
            Request("w_cidade_dest"), Request("w_data_chegada"), Request("w_hora_chegada"), _
            null, null
          
          ScriptOpen "JavaScript"
          ' Aqui deve ser usada a variável de sessão para evitar erro na recuperação do link
          DB_GetLinkData RS1, Session("p_cliente"), SG
          ShowHTML "  location.href='" & replace(RS1("link"),w_dir,"") & "&O=L&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
          ScriptClose
          dbms.CommitTrans
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

          dbms.BeginTrans

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
          dbms.CommitTrans
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
      
          dbms.BeginTrans

          'ExibeVariaveis
          DML_PutPDMissao null, Request("w_chave"), Nvl(Request("w_vlr_alimentacao"),0), Nvl(Request("w_vlr_transporte"),0), Nvl(Request("w_adicional"),0),  _
                          Nvl(Request("w_desc_alimentacao"),0), Nvl(Request("w_desc_trasnporte"),0), null, null, null, null
          
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
          dbms.CommitTrans
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "INFPASS"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
      
          dbms.BeginTrans

          'ExibeVariaveis
          DML_PutPDMissao null, Request("w_chave"), null, null, null,  _
                          null, null, Request("w_pta"), Request("w_emissao_bilhete"), Request("w_valor_passagem"), SG
          
          For i = 1 To Request.Form("w_sq_deslocamento").Count
              DML_PutPD_Deslocamento "P", _
                 Request("w_chave"), Request.Form("w_sq_deslocamento")(i), _ 
                 null, null, null, null, null, null,  _
                 Request.Form("w_sq_cia_transporte")(i), Nvl(Request.Form("w_codigo_voo")(i),0)              
          Next
              
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & w_pagina & "Informarpassagens&O=" & O & "&w_chave=" & Request("w_Chave") & "&w_menu=" & Request("w_menu") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
          ScriptClose
          dbms.CommitTrans
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

          dbms.BeginTrans

          ' Trata o recebimento de upload ou dados 
          If InStr(uCase(Request.ServerVariables("http_content_type")),"MULTIPART/FORM-DATA") > 0 Then 
             ' Verifica se outro usuário já enviou a solicitação
             DB_GetSolicData RS, ul.Texts.Item("w_chave"), "PDINICIAL"
             If cDbl(RS("sq_siw_tramite")) <> cDbl(ul.Texts.Item("w_tramite")) Then
                ScriptOpen "JavaScript"
                ShowHTML "  alert('ATENÇÃO: Outro usuário já encaminhou a solicitação para outra fase!');"
                ShowHTML "  history.back(1);"
                ScriptClose
                Response.End()
                Exit Sub
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
             End If
          Else 
             'SolicMail Request("w_chave"),2
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
                   DB_GetTramiteList RS, Request("w_tramite"), "PROXIMO", null
                Else
                   DB_GetTramiteList RS, Request("w_tramite"), "ANTERIOR", null
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
             
                DML_PutViagemEnvio Request("w_menu"), Request("w_chave"), w_usuario, Request("w_tramite"), _ 
                    Request("w_envio"), Request("w_despacho"), Request("w_justificativa")
             
                ' Envia e-mail comunicando de tramitação
                SolicMail Request("w_chave"),2
             
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
          End If
          dbms.CommitTrans
       Else 
          ScriptOpen "JavaScript" 
          ShowHTML "  alert('Assinatura Eletrônica inválida!');" 
          ShowHTML "  history.back(1);" 
          ScriptClose 
       End If 
    Case "PDCONC"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then

          dbms.BeginTrans

          DB_GetSolicData RS, Request("w_chave"), SG
          If cDbl(RS("sq_siw_tramite")) <> cDbl(Request("w_tramite")) Then
             ScriptOpen "JavaScript"
             ShowHTML "  alert('ATENÇÃO: Outro usuário já encaminhou esta PCD para outra fase de execução!');"
             ScriptClose
          Else
             DML_PutDemandaConc w_menu, Request("w_chave"), w_usuario, Request("w_tramite"), Request("w_inicio_real"), Request("w_fim_real"), Request("w_nota_conclusao"), Request("w_custo_real"), _  
                 w_file, w_tamanho, w_tipo, w_nome
             ' Envia e-mail comunicando a conclusão
             SolicMail Request("w_chave") ,3
             
             ScriptOpen "JavaScript"
             ' Volta para a listagem
             DB_GetMenuData RS, w_menu
             ShowHTML "  location.href='" & replace(RS("link"),w_dir,"") & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & rs("sigla") & MontaFiltro("UPLOAD") & "';" 
             DesconectaBD
             ScriptClose
          End If
          dbms.CommitTrans
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
    Case "INICIAL"           Inicial
    Case "GERAL"             Geral
    Case "OUTRA"             OutraParte
    Case "TRECHOS"           Trechos
    Case "VINCULACAO"        Vinculacao
    Case "DADOSFINANCEIROS"  DadosFinanceiros
    Case "VISUAL"            Visual
    Case "EXCLUIR"           Excluir
    Case "ENVIO"             Encaminhamento
    Case "ANOTACAO"          Anotar
    Case "CONCLUIR"          Concluir
    Case "EMISSAO"           Emissao
    Case "INFORMARPASSAGENS" InformarPassagens
    Case "PRESTACAOCONTAS"   PrestacaoContas
    Case "GRAVA"             Grava
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

