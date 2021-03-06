<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Geral.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Cliente.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Seguranca.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Link.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_EO.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DML_Solic.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DML_Demanda.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE FILE="VisualDemanda.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/cp_upload/_upload.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /projetoativ.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papad�polis
REM Descricao: Gerencia o m�dulo de demandas
REM Mail     : alex@sbpi.com.br
REM Criacao  : 15/10/2003 12:25
REM Versao   : 1.0.0.0
REM Local    : Bras�lia - DF
REM -------------------------------------------------------------------------
REM
REM Par�metros recebidos:
REM    R (refer�ncia) = usado na rotina de grava��o, com conte�do igual ao par�metro T
REM    O (opera��o)   = I   : Inclus�o
REM                   = A   : Altera��o
REM                   = C   : Cancelamento
REM                   = E   : Exclus�o
REM                   = L   : Listagem
REM                   = P   : Pesquisa
REM                   = D   : Detalhes
REM                   = N   : Nova solicita��o de envio

' Verifica se o usu�rio est� autenticado
If Session("LogOn") <> "Sim" Then
   EncerraSessao
End If

' Declara��o de vari�veis
Dim dbms, sp, RS, RS1, RS2, RS3, RS4, RS_menu, w_ano
Dim P1, P2, P3, P4, TP, SG
Dim R, O, w_Cont, w_Reg, w_Pagina, w_Disabled, w_TP, w_classe, w_submenu, w_filtro, w_copia
Dim w_Assinatura
Dim p_ativo, p_solicitante, p_prioridade, p_unidade, p_proponente, p_ordena
Dim p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_projeto, p_atividade
Dim p_chave, p_assunto, p_pais, p_uf, p_cidade, p_regiao, p_usu_resp, p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc
Dim w_troca,w_cor, w_filter, w_cliente, w_usuario, w_menu
Dim w_sq_pessoa
Dim ul,File
Dim w_dir, w_dir_volta, UploadID
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
w_Pagina     = "projetoativ.asp?par="
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
    
   If SG = "GDPANEXO" or SG = "GDPINTERES" or SG = "GDPAREAS" Then
      If O <> "I" and Request("w_chave_aux") = "" Then O = "L" End If  
   ElseIf SG = "GDPENVIO" Then  
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
Set w_dir_volta   = Nothing
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
Set w_Pagina      = Nothing
Set w_Disabled    = Nothing
Set w_TP          = Nothing
Set w_Assinatura  = Nothing

REM =========================================================================
REM Rotina de visualiza��o resumida dos registros
REM -------------------------------------------------------------------------
Sub Inicial

  Dim w_titulo, w_total, w_parcial
  
  If O = "L" Then
     If Instr(uCase(R),"GR_") > 0 or Instr(uCase(R),"PROJETO") > 0 Then
        w_filtro = ""
        If p_projeto > ""  Then 
           DB_GetSolicData RS, p_projeto, "PJGERAL"
           w_filtro = w_filtro & "<tr valign=""top""><TD align=""right""><font size=1>Projeto <TD><font size=1>[<b><A class=""hl"" HREF=""" & w_dir & "Projeto.asp?par=Visual&O=L&w_chave=" & p_projeto & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Exibe as informa��es do projeto."">" & RS("titulo") & "</a></b>]"
        End If
        If p_atividade > ""  Then 
           DB_GetSolicEtapa RS, p_projeto, p_atividade, "REGISTRO", null
           w_filtro = w_filtro & "<tr valign=""top""><TD align=""right""><font size=1>Etapa <TD><font size=1>[<b>" & MontaOrdemEtapa(RS("sq_projeto_etapa")) & " - " & RS("titulo") & "</b>]"
        End If
        If p_sqcc > ""  Then 
           DB_GetCCData RS, p_sqcc
           w_filtro = w_filtro & "<tr valign=""top""><TD align=""right""><font size=1>Classifica��o <TD><font size=1>[<b>" & RS("nome") & "</b>]"
        End If
        If p_chave       > ""  Then w_filtro = w_filtro & "<tr valign=""top""><TD align=""right""><font size=1>Atividade n� <TD><font size=1>[<b>" & p_chave & "</b>]" End If
        If p_prazo       > ""  Then w_filtro = w_filtro & " <tr valign=""top""><TD align=""right""><font size=1>Prazo para conclus�o at�<TD><font size=1>[<b>" & FormatDateTime(DateAdd("d",p_prazo,Date()),1) & "</b>]" End If
        If p_solicitante > ""  Then
           DB_GetPersonData RS, w_cliente, p_solicitante, null, null
           w_filtro = w_filtro & "<tr valign=""top""><TD align=""right""><font size=1>Respons�vel <TD><font size=1>[<b>" & RS("nome_resumido") & "</b>]"
        End If
        If p_unidade     > ""  Then 
           DB_GetUorgData RS, p_unidade
           w_filtro = w_filtro & "<tr valign=""top""><TD align=""right""><font size=1>Unidade respons�vel <TD><font size=1>[<b>" & RS("nome") & "</b>]"
        End If
        If p_usu_resp > ""  Then
           DB_GetPersonData RS, w_cliente, p_usu_resp, null, null
           w_filtro = w_filtro & "<tr valign=""top""><TD align=""right""><font size=1>Executor <TD><font size=1>[<b>" & RS("nome_resumido") & "</b>]"
        End If
        If p_uorg_resp > ""  Then 
           DB_GetUorgData RS, p_uorg_resp
           w_filtro = w_filtro & "<tr valign=""top""><TD align=""right""><font size=1>Unidade atual <TD><font size=1>[<b>" & RS("nome") & "</b>]"
        End If
        If p_pais > ""  Then 
           DB_GetCountryData RS, p_pais
           w_filtro = w_filtro & "<tr valign=""top""><TD align=""right""><font size=1>Pa�s <TD><font size=1>[<b>" & RS("nome") & "</b>]"
        End If
        If p_regiao > ""  Then 
           DB_GetRegionData RS, p_regiao
           w_filtro = w_filtro & "<tr valign=""top""><TD align=""right""><font size=1>Regi�o <TD><font size=1>[<b>" & RS("nome") & "</b>]"
        End If
        If p_uf > ""  Then 
           DB_GetStateData RS, p_pais, p_uf
           w_filtro = w_filtro & "<tr valign=""top""><TD align=""right""><font size=1>Estado <TD><font size=1>[<b>" & RS("nome") & "</b>]"
        End If
        If p_cidade > ""  Then 
           DB_GetCityData RS, p_cidade
           w_filtro = w_filtro & "<tr valign=""top""><TD align=""right""><font size=1>Cidade <TD><font size=1>[<b>" & RS("nome") & "</b>]"
        End If
        If p_prioridade  > ""  Then w_filtro = w_filtro & "<tr valign=""top""><TD align=""right""><font size=1>Prioridade <TD><font size=1>[<b>" & RetornaPrioridade(p_prioridade) & "</b>]"   End If
        If p_proponente  > ""  Then w_filtro = w_filtro & "<tr valign=""top""><TD align=""right""><font size=1>Proponente <TD><font size=1>[<b>" & p_proponente & "</b>]"                      End If
        If p_assunto     > ""  Then w_filtro = w_filtro & "<tr valign=""top""><TD align=""right""><font size=1>Detalhamento <TD><font size=1>[<b>" & p_assunto & "</b>]"                            End If
        If p_palavra     > ""  Then w_filtro = w_filtro & "<tr valign=""top""><TD align=""right""><font size=1>Palavras-chave <TD><font size=1>[<b>" & p_palavra & "</b>]"                     End If
        If p_ini_i       > ""  Then w_filtro = w_filtro & "<tr valign=""top""><TD align=""right""><font size=1>Data recebimento <TD><font size=1>[<b>" & p_ini_i & "-" & p_ini_f & "</b>]"     End If
        If p_fim_i       > ""  Then w_filtro = w_filtro & "<tr valign=""top""><TD align=""right""><font size=1>Limite conclus�o <TD><font size=1>[<b>" & p_fim_i & "-" & p_fim_f & "</b>]"     End If
        If p_atraso      = "S" Then w_filtro = w_filtro & "<tr valign=""top""><TD align=""right""><font size=1>Situa��o <TD><font size=1>[<b>Apenas atrasadas</b>]"                            End If
        If w_filtro > "" Then w_filtro = "<table border=0><tr valign=""top""><TD><font size=1><b>Filtro:</b><TD nowrap><font size=1><ul>" & w_filtro & "</ul></tr></table>"                    End If
     End If

     DB_GetLinkData RS, w_cliente, "GDPCAD"
     If w_copia > "" Then ' Se for c�pia, aplica o filtro sobre todas as demandas vis�veis pelo usu�rio
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

     If p_ordena > "" Then RS.sort = p_ordena & ", fim, prioridade" Else RS.sort = "fim, prioridade" End If
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>" & conSgSistema & " - Listagem de projetos</TITLE>"
  If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""30; URL=" & replace(MontaURL("MESA"),w_dir,"") & """>" End If
  Estrutura_CSS w_cliente

  ScriptOpen "Javascript"
  CheckBranco
  FormataData
  ValidateOpen "Validacao"
  If Instr("CP",O) > 0 Then
     If P1 <> 1 or O = "C" Then ' Se n�o for cadastramento ou se for c�pia
        Validate "p_chave", "N�mero da atividade", "", "", "1", "18", "", "0123456789"
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
     BodyOpen "onLoad='document.Form.p_projeto.focus()';"
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
    If P1 = 1 and w_copia = "" Then ' Se for cadastramento e n�o for resultado de busca para c�pia
       If w_submenu > "" Then
          DB_GetLinkSubMenu RS1, w_cliente, Request("SG")
          ShowHTML "<tr><TD>"
          ShowHTML "    <a accesskey=""I"" class=""ss"" href=""" & w_dir & w_Pagina & "Geral&R=" & w_Pagina & par & "&O=I&SG=" & RS1("sigla") & "&w_menu=" & w_menu & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;"
          ShowHTML "    <a accesskey=""C"" class=""ss"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=C&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>C</u>opiar</a>"
       Else
          ShowHTML "<tr><TD><a accesskey=""I"" class=""ss"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;"
       End If
    End If
    If Instr(uCase(R),"GR_") = 0 and Instr(uCase(R),"PROJETO") = 0 Then
       If w_copia > "" Then ' Se for c�pia
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
    ShowHTML "          <TD><b>N�</font></td>"
    If p_Projeto > "" Then
       ShowHTML "          <TD><b>Etapa</font></td>"
    Else
       ShowHTML "          <TD><b>Projeto</font></td>"
    End If
    ShowHTML "          <TD><b>Respons�vel</font></td>"
    ShowHTML "          <TD><b>Executor</font></td>"
    If P1 = 1 or P1 = 2 Then ' Se for cadastramento ou mesa de trabalho
       ShowHTML "          <TD><b>Detalhamento</font></td>"
       ShowHTML "          <TD><b>Fim previsto</font></td>"
    Else
       ShowHTML "          <TD><b>Proponente</font></td>"
       ShowHTML "          <TD><b>Detalhamento</font></td>"
       ShowHTML "          <TD><b>Fim previsto</font></td>"
       If Session("interno") = "S" Then ShowHTML "          <TD><b>Valor</font></td>" End If
       ShowHTML "          <TD><b>Fase atual</font></td>"
    End If
    ShowHTML "          <TD><b>Opera��es</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><TD colspan=10 align=""center""><b>N�o foram encontrados registros.</b></td></tr>"
    Else
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      w_parcial       = 0
      While Not RS.EOF and RS.AbsolutePage = P3
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <TD nowrap>"
        If RS("concluida") = "N" Then
           If RS("fim") < Date() Then
              ShowHTML "           <img src=""" & conImgAtraso & """ border=0 width=10 height=10 align=""center"">"
           ElseIf RS("aviso_prox_conc") = "S" and (RS("aviso") <= Date()) Then
              ShowHTML "           <img src=""" & conImgAviso & """ border=0 width=10 height=10 align=""center"">"
           Else
              ShowHTML "           <img src=""" & conImgNormal & """ border=0 width=10 height=10 align=""center"">"
           End If
        Else
           If RS("fim") < Nvl(RS("fim_real"),RS("fim")) Then
              ShowHTML "           <img src=""" & conImgOkAtraso & """ border=0 width=10 height=10 align=""center"">"
           Else
              ShowHTML "           <img src=""" & conImgOkNormal & """ border=0 width=10 height=10 align=""center"">"
           End If
        End If
        ShowHTML "        <A class=""hl"" HREF=""" & w_dir & w_Pagina & "Visual&R=" & w_Pagina & par & "&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exibe as informa��es deste registro."">" & RS("sq_siw_solicitacao") & "&nbsp;</a>"
        If p_projeto > "" Then
           If nvl(RS("sq_projeto_etapa"),"nulo") <> "nulo" Then
              ShowHTML "        <TD>" & ExibeEtapa("V", RS("sq_solic_pai"), RS("sq_projeto_etapa"), "Volta", 10, MontaOrdemEtapa(RS("sq_projeto_etapa")) & " - " & RS("nm_etapa"), TP, SG) & "</td>"
           Else
              ShowHTML "        <TD>---</td>"
           End If
        Else
           ShowHTML "        <TD><A class=""hl"" HREF=""" & w_dir & "Projeto.asp?par=Visual&O=L&w_chave=" & RS("sq_solic_pai") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Exibe as informa��es do projeto."">" & RS("nm_projeto") & "</a></td>"
        End If
        ShowHTML "        <TD>" & ExibePessoa(w_dir_volta, w_cliente, RS("solicitante"), TP, RS("nm_solic")) & "</td>"
        If Nvl(RS("nm_exec"),"---") <> "---" Then
           ShowHTML "        <TD>" & ExibePessoa(w_dir_volta, w_cliente, RS("executor"), TP, RS("nm_exec")) & "</td>"
        Else   
           ShowHTML "        <TD>" & Nvl(RS("nm_exec"),"---") & "</td>"
        End If
        If P1 <> 1 and P1 <> 2 Then ' Se n�o for cadastramento nem mesa de trabalho
           ShowHTML "        <TD>" & Nvl(RS("proponente"),"---") & "</td>"
        End If
        ' Verifica se foi enviado o par�metro p_tamanho = N. Se chegou, o assunto deve ser exibido sem corte.
        ' Este par�metro � enviado pela tela de filtragem das p�ginas gerenciais
        If Request("p_tamanho") = "N" Then
           ShowHTML "        <TD>" & Nvl(RS("assunto"),"-") & "</td>"
        Else
           If Len(Nvl(RS("assunto"),"-")) > 50 Then w_titulo = Mid(Nvl(RS("assunto"),"-"),1,50) & "..." Else w_titulo = Nvl(RS("assunto"),"-") End If
           If RS("sg_tramite") = "CA" Then
              ShowHTML "        <TD title=""" & replace(replace(replace(RS("assunto"), "'", "\'"), """", "\'"),VbCrLf,"\n") & """><strike>" & w_titulo & "</strike></td>"
           Else
              ShowHTML "        <TD title=""" & replace(replace(replace(RS("assunto"), "'", "\'"), """", "\'"),VbCrLf,"\n") & """>" & w_titulo & "</td>"
           End IF
        End If
        ShowHTML "        <TD>" & Nvl(FormatDateTime(RS("fim"),2),"-") & "</td>"
        ' Mostra os valor se o usu�rio for interno e n�o for cadastramento nem mesa de trabalho
        If P1 <> 1 and P1 <> 2 Then 
           If Session("interno") = "S" Then
              If RS("sg_tramite") = "AT" Then
                 ShowHTML "        <TD align=""right"">" & FormatNumber(RS("custo_real"),2) & "&nbsp;</td>"
                 w_parcial = w_parcial + cDbl(RS("custo_real"))
              Else
                 ShowHTML "        <TD align=""right"">" & FormatNumber(RS("valor"),2) & "&nbsp;</td>"
                 w_parcial = w_parcial + cDbl(RS("valor"))
              End If
           End If
           ShowHTML "        <TD nowrap>" & RS("nm_tramite") & "</td>"
        End If
        ShowHTML "        <TD align=""top"" nowrap>"
        If P1 <> 3 Then ' Se n�o for acompanhamento
           If w_copia > "" Then ' Se for listagem para c�pia
              DB_GetLinkSubMenu RS1, w_cliente, Request("SG")
              ShowHTML "          <a accesskey=""I"" class=""hl"" href=""" & w_dir & w_Pagina & "Geral&R=" & w_Pagina & par & "&O=I&SG=" & RS1("sigla") & "&w_menu=" & w_menu & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&w_copia=" & RS("sq_siw_solicitacao") & MontaFiltro("GET") & """>Copiar</a>&nbsp;"
           ElseIf P1 = 1 Then ' Se for cadastramento
              If w_submenu > "" Then
                 ShowHTML "          <A class=""hl"" HREF=""Menu.asp?par=ExibeDocs&O=A&w_chave=" & RS("sq_siw_solicitacao") & "&R=" & w_Pagina & par & "&SG=" & SG & "&TP=" & TP & "&w_documento=Nr. " & RS("sq_siw_solicitacao") & MontaFiltro("GET") & """ title=""Altera as informa��es cadastrais da atividade"" TARGET=""menu"">Alterar</a>&nbsp;"
              Else
                 ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & RS("sq_siw_solicitacao") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Altera as informa��es cadastrais da atividade"">Alterar</A>&nbsp"
              End If
              ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & "Excluir&R=" & w_Pagina & par & "&O=E&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Exclus�o da atividade."">Excluir</A>&nbsp"
           ElseIf P1 = 2 Then ' Se for execu��o
              If cDbl(w_usuario) = cDbl(RS("executor")) Then
                 ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & "Anotacao&R=" & w_Pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Registra anota��es para a atividade, sem envi�-la."">Anotar</A>&nbsp"
                 ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & "envio&R=" & w_Pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Envia a atividade para outro respons�vel."">Enviar</A>&nbsp"
                 ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & "Concluir&R=" & w_Pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Conclui a execu��o da atividade."">Concluir</A>&nbsp"
              Else
                 ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & "envio&R=" & w_Pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Envia a atividade para outro respons�vel."">Enviar</A>&nbsp"
              End If
           End If
        Else
           If cDbl(Nvl(RS("solicitante"),0)) = cDbl(w_usuario) or _
              cDbl(Nvl(RS("titular"),0))     = cDbl(w_usuario) or _
              cDbl(Nvl(RS("substituto"),0))  = cDbl(w_usuario) _
           Then
              ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & "envio&R=" & w_Pagina & par & "&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ title=""Envia a atividade para outro respons�vel."">Enviar</A>&nbsp"
           Else
              ShowHTML "          ---&nbsp"
           End If
        End If
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend

      ' Mostra os valor se o usu�rio for interno e n�o for cadastramento nem mesa de trabalho
      If P1 <> 1 and P1 <> 2 and Session("interno") = "S" Then 
         ' Coloca o valor parcial apenas se a listagem ocupar mais de uma p�gina
         If RS.PageCount > 1 Then
            ShowHTML "        <tr bgcolor=""" & conTrBgColor & """>"
            ShowHTML "          <TD colspan=7 align=""right""><b>Total desta p�gina&nbsp;</font></td>"
            ShowHTML "          <TD align=""right""><b>" & FormatNumber(w_parcial,2) & "&nbsp;</font></td>"
            ShowHTML "          <TD colspan=2>&nbsp;</font></td>"
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
    If O = "C" Then ' Se for c�pia
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><TD><div align=""justify"">Para selecionar a atividade que deseja copiar, informe nos campos abaixo os crit�rios de sele��o e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>"
    Else
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><TD><div align=""justify"">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>"
    End If
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><TD>"
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><TD valign=""top""><table border=0 width=""90%"" cellspacing=0>"
    AbreForm "Form", w_dir & w_Pagina & par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    If O = "C" Then ' Se for c�pia, cria par�metro para facilitar a recupera��o dos registros
       ShowHTML "<INPUT type=""hidden"" name=""w_copia"" value=""OK"">"
    End If

    ' Recupera dados da op��o Projetos
    ShowHTML "      <tr><TD valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "      <tr>"
    DB_GetLinkData RS, w_cliente, "PJCAD"
    SelecaoProjeto "Pro<u>j</u>eto:", "J", "Selecione o projeto da atividade na rela��o.", p_projeto, w_usuario, RS("sq_menu"), "p_projeto", "PJLIST", "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_atividade'; document.Form.submit();"""
    DesconectaBD
    ShowHTML "      </tr>"
    ShowHTML "      <tr>"
    SelecaoEtapa "Eta<u>p</u>a:", "P", "Se necess�rio, indique a etapa � qual esta atividade deve ser vinculada.", p_atividade, p_projeto, null, "p_atividade", null, null
    ShowHTML "      </tr>"
    ShowHTML "          </table>"
    ShowHTML "      <tr><TD valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"

    If P1 <> 1 or O = "C" Then ' Se n�o for cadastramento ou se for c�pia
       If RS_menu("solicita_cc") = "S" Then
          ShowHTML "      <tr>"
          SelecaoCC "C<u>l</u>assifica��o:", "L", "Selecione a classifica��o desejada.", p_sqcc, null, "p_sqcc", "SIWSOLIC"
          ShowHTML "      </tr>"
       End If
       ShowHTML "      <tr valign=""top"">"
       ShowHTML "          <TD valign=""top""><b>N�mero da <U>d</U>emanda:<br><INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""sti"" type=""text"" name=""p_chave"" size=""18"" maxlength=""18"" value=""" & p_chave & """></td>"
       ShowHTML "          <TD valign=""top""><b>Dias para a data limi<U>t</U>e:<br><INPUT ACCESSKEY=""T"" " & w_Disabled & " class=""sti"" type=""text"" name=""p_prazo"" size=""2"" maxlength=""2"" value=""" & p_prazo & """></td>"
       ShowHTML "      <tr valign=""top"">"
       SelecaoPessoa "Respo<u>n</u>s�vel:", "N", "Selecione o respons�vel pela atividade na rela��o.", p_solicitante, null, "p_solicitante", "USUARIOS"
       SelecaoUnidade "<U>S</U>etor respons�vel:", "S", null, p_unidade, null, "p_unidade", null, null
       ShowHTML "      <tr valign=""top"">"
       SelecaoPessoa "Respons�vel atua<u>l</u>:", "L", "Selecione o respons�vel atual pela atividade na rela��o.", p_usu_resp, null, "p_usu_resp", "USUARIOS"
       SelecaoUnidade "<U>S</U>etor atual:", "S", "Selecione a unidade onde a atividade se encontra na rela��o.", p_uorg_resp, null, "p_uorg_resp", null, null
       ShowHTML "      <tr>"
       SelecaoPais "<u>P</u>a�s:", "P", null, p_pais, null, "p_pais", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_regiao'; document.Form.submit();"""
       SelecaoRegiao "<u>R</u>egi�o:", "R", null, p_regiao, p_pais, "p_regiao", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_uf'; document.Form.submit();"""
       ShowHTML "      <tr>"
       SelecaoEstado "E<u>s</u>tado:", "S", null, p_uf, p_pais, "N", "p_uf", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_cidade'; document.Form.submit();"""
       SelecaoCidade "<u>C</u>idade:", "C", null, p_cidade, p_pais, p_uf, "p_cidade", null, null
       ShowHTML "      <tr>"
       SelecaoPrioridade "<u>P</u>rioridade:", "P", "Informe a prioridade desta atividade.", p_prioridade, null, "p_prioridade", null, null
       ShowHTML "          <TD valign=""top""><b>Propo<U>n</U>ente externo:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""sti"" type=""text"" name=""p_proponente"" size=""25"" maxlength=""90"" value=""" & p_proponente & """></td>"
       ShowHTML "      <tr>"
       ShowHTML "          <TD valign=""top""><b>A<U>s</U>sunto:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""sti"" type=""text"" name=""p_assunto"" size=""25"" maxlength=""90"" value=""" & p_assunto & """></td>"
       ShowHTML "          <TD valign=""top"" colspan=2><b>Pala<U>v</U>ras-chave:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""sti"" type=""text"" name=""p_palavra"" size=""25"" maxlength=""90"" value=""" & p_palavra & """></td>"
       ShowHTML "      <tr>"
       ShowHTML "          <TD valign=""top""><b>Data de re<u>c</u>ebimento entre:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""p_ini_i"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_ini_i & """ onKeyDown=""FormataData(this,event);""> e <input " & w_Disabled & " accesskey=""C"" type=""text"" name=""p_ini_f"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_ini_f & """ onKeyDown=""FormataData(this,event);""></td>"
       ShowHTML "          <TD valign=""top""><b>Limi<u>t</u>e para conclus�o entre:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""p_fim_i"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_fim_i & """ onKeyDown=""FormataData(this,event);""> e <input " & w_Disabled & " accesskey=""T"" type=""text"" name=""p_fim_f"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_fim_f & """ onKeyDown=""FormataData(this,event);""></td>"
       If O <> "C" Then ' Se n�o for c�pia
          ShowHTML "      <tr>"
          ShowHTML "          <TD valign=""top""><b>Exibe somente atividades em atraso?</b><br>"
          If p_atraso = "S" Then
             ShowHTML "              <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_atraso"" value=""S"" checked> Sim <br><input " & w_Disabled & " class=""str"" class=""str"" type=""radio"" name=""p_atraso"" value=""N""> N�o"
          Else
             ShowHTML "              <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_atraso"" value=""S""> Sim <br><input " & w_Disabled & " class=""str"" class=""str"" type=""radio"" name=""p_atraso"" value=""N"" checked> N�o"
          End If
          SelecaoFaseCheck "Recuperar fases:", "S", null, p_fase, P2, "p_fase", null, null
       End If
    End If
    ShowHTML "      <tr>"
    ShowHTML "          <TD valign=""top""><b><U>O</U>rdena��o por:<br><SELECT ACCESSKEY=""O"" " & w_Disabled & " class=""sts"" name=""p_ordena"" size=""1"">"
    If p_Ordena="ASSUNTO" Then
       ShowHTML "          <option value=""assunto"" SELECTED>Detalhamento<option value=""inicio"">Data de recebimento<option value="""">Data limite para conclus�o<option value=""nm_tramite"">Fase atual<option value=""prioridade"">Prioridade<option value=""proponente"">Proponente externo"
    ElseIf p_Ordena="INICIO" Then
       ShowHTML "          <option value=""assunto"">Detalhamento<option value=""inicio"" SELECTED>Data de recebimento<option value="""">Data limite para conclus�o<option value=""nm_tramite"">Fase atual<option value=""prioridade"">Prioridade<option value=""proponente"">Proponente externo"
    ElseIf p_Ordena="NM_TRAMITE" Then
       ShowHTML "          <option value=""assunto"">Detalhamento<option value=""inicio"">Data de recebimento<option value="""">Data limite para conclus�o<option value=""nm_tramite"" SELECTED>Fase atual<option value=""prioridade"">Prioridade<option value=""proponente"">Proponente externo"
    ElseIf p_Ordena="PRIORIDADE" Then
       ShowHTML "          <option value=""assunto"">Detalhamento<option value=""inicio"">Data de recebimento<option value="""">Data limite para conclus�o<option value=""nm_tramite"">Fase atual<option value=""prioridade"" SELECTED>Prioridade<option value=""proponente"">Proponente externo"
    ElseIf p_Ordena="PROPONENTE" Then
       ShowHTML "          <option value=""assunto"">Detalhamento<option value=""inicio"">Data de recebimento<option value="""">Data limite para conclus�o<option value=""nm_tramite"">Fase atual<option value=""prioridade"">Prioridade<option value=""proponente"" SELECTED>Proponente externo"
    Else
       ShowHTML "          <option value=""assunto"">Detalhamento<option value=""inicio"">Data de recebimento<option value="""" SELECTED>Data limite para conclus�o<option value=""nm_tramite"">Fase atual<option value=""prioridade"">Prioridade<option value=""proponente"">Proponente externo"
    End If
    ShowHTML "          </select></td>"
    ShowHTML "          <TD valign=""top""><b><U>L</U>inhas por p�gina:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""sti"" type=""text"" name=""P4"" size=""4"" maxlength=""4"" value=""" & P4 & """></td></tr>"
    ShowHTML "          </table>"
    ShowHTML "      <tr><TD align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><TD align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
    If O = "C" Then ' Se for c�pia
       ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"" name=""Botao"" value=""Abandonar c�pia"">"
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
    ShowHTML " alert('Op��o n�o dispon�vel');"
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
  Dim w_sq_unidade_resp, w_assunto, w_prioridade, w_aviso, w_dias
  Dim w_inicio_real, w_fim_real, w_concluida, w_proponente
  Dim w_data_conclusao, w_nota_conclusao, w_custo_real, w_sqcc
  Dim w_projeto, w_atividade, w_projeto_ant, w_atividade_ant
  
  Dim w_chave, w_chave_pai, w_chave_aux, w_sq_menu, w_sq_unidade
  Dim w_sq_tramite, w_solicitante, w_cadastrador, w_executor, w_descricao
  Dim w_justificativa, w_inicio, w_fim, w_inclusao, w_ultima_alteracao
  Dim w_conclusao, w_valor, w_opiniao, w_data_hora, w_pais, w_uf, w_cidade, w_palavra_chave
  
  Dim w_troca, i, w_erro, w_como_funciona, w_cor, w_readonly

  w_chave           = Request("w_chave")
  w_readonly        = ""
  w_erro            = ""
  w_troca           = Request("w_troca")

  ' Verifica se h� necessidade de recarregar os dados da tela a partir
  ' da pr�pria tela (se for recarga da tela) ou do banco de dados (se n�o for inclus�o)
  If w_troca > "" Then ' Se for recarga da p�gina
     w_proponente          = Request("w_proponente") 
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
     w_projeto             = Request("w_projeto") 
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
        ' Recupera os dados da atividade
        If w_copia > "" Then
           DB_GetSolicData RS, w_copia, SG
        Else
           DB_GetSolicData RS, w_chave, SG
        End If
        If RS.RecordCount > 0 Then 
           w_proponente          = RS("proponente") 
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
           w_projeto             = RS("sq_solic_pai") 
           w_atividade           = RS("sq_projeto_etapa") 
           w_projeto_ant         = RS("sq_solic_pai")
           w_atividade_ant       = RS("sq_projeto_etapa")
  
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
  ShowHTML "<TITLE>" & conSgSistema & " - Listagem de projetos</TITLE>"
  If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & replace(MontaURL("MESA"),w_dir,"") & """>" End If
  Estrutura_CSS w_cliente
  
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
     Validate "w_projeto", "Projeto", "SELECT", 1, 1, 18, "", "0123456789"
     Validate "w_atividade", "Atividade", "SELECT", "", 1, 18, "", "0123456789"
     ShowHTML "  if (theForm.w_atividade[theForm.w_atividade.selectedIndex].value=='' && theForm.w_atividade.selectedIndex != 0) {"
     ShowHTML "     alert('A etapa selecionada n�o permite atividades vinculadas.\n Ela pode estar com  100% de conclus�o ou ser usada apenas para agrupamento de outras etapas.');"
     ShowHTML "     theForm.w_atividade.focus();"
     ShowHTML "     return false;"
     ShowHTML "  }"
     Validate "w_assunto", "Detalhamento", "1", 1, 5, 2000, "1", "1"
     If RS_menu("solicita_cc") = "S" Then
        Validate "w_sqcc", "Classifica��o", "SELECT", 1, 1, 18, "", "0123456789"
     End If
     Validate "w_solicitante", "Solicitante", "HIDDEN", 1, 1, 18, "", "0123456789"
     Validate "w_sq_unidade_resp", "Unidade respons�vel", "HIDDEN", 1, 1, 18, "", "0123456789"
     Validate "w_prioridade", "Prioridade", "SELECT", 1, 1, 1, "", "0123456789"
     Select Case RS_menu("data_hora")
        Case 1
              Validate "w_fim", "Limite para conclus�o", "DATA", 1, 10, 10, "", "0123456789/"
        Case 2
           Validate "w_fim", "Limite para conclus�o", "DATAHORA", 1, 17, 17, "", "0123456789/"
        Case 3
           Validate "w_inicio", "Data de recebimento", "DATA", 1, 10, 10, "", "0123456789/"
           Validate "w_fim", "Limite para conclus�o", "DATA", 1, 10, 10, "", "0123456789/"
           CompData "w_inicio", "Data de recebimento", "<=", "w_fim", "Limite para conclus�o"
        Case 4
           Validate "w_inicio", "Data de recebimento", "DATAHORA", 1, 17, 17, "", "0123456789/,: "
           Validate "w_fim", "Limite para conclus�o", "DATAHORA", 1, 17, 17, "", "0123456789/,: "
           CompData "w_inicio", "Data de recebimento", "<=", "w_fim", "Limite para conclus�o"
     End Select
     Validate "w_valor", "Or�amento dispon�vel", "VALOR", "1", 4, 18, "", "0123456789.,"
     Validate "w_palavra_chave", "Palavras-chave", "", "", 2, 90, "1", "1"
     Validate "w_proponente", "Proponente externo", "", "", 2, 90, "1", "1"
     Validate "w_pais", "Pa�s", "SELECT", 1, 1, 18, "", "0123456789"
     Validate "w_uf", "Estado", "SELECT", 1, 1, 3, "1", "1"
     Validate "w_cidade", "Cidade", "SELECT", 1, 1, 18, "", "0123456789"
     If RS_menu("descricao") = "S" Then
        Validate "w_descricao", "Resultados da atividade", "1", 1, 5, 2000, "1", "1"
     End If
     If RS_menu("justificativa") = "S" Then
        Validate "w_justificativa", "Recomenda��es superiores", "1", 1, 5, 2000, "1", "1"
     End If
     Validate "w_dias", "Dias de alerta", "1", "", 1, 2, "", "0123456789"
     ShowHTML "  if (theForm.w_aviso[0].checked) {"
     ShowHTML "     if (theForm.w_dias.value == '') {"
     ShowHTML "        alert('Informe a partir de quantos dias antes da data limite voc� deseja ser avisado de sua proximidade!');"
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
     BodyOpen "onLoad='document.Form.w_projeto.focus()';"
  End If
  ShowHtml "<center>"
  Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre

  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
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

    AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
    ShowHTML MontaFiltro("POST")
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_copia"" value=""" & w_copia &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_data_hora"" value=""" & RS_menu("data_hora") &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & RS_menu("sq_menu") &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_projeto_ant"" value=""" & w_projeto_ant &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_atividade_ant"" value=""" & w_atividade_ant &""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><TD>"
    ShowHTML "    <table width=""100%"" border=""0"">"
    ShowHTML "      <tr><TD align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><TD align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><TD valign=""top"" align=""center"" bgcolor=""#D0D0D0""><b>Identifica��o</td></td></tr>"
    ShowHTML "      <tr><TD align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><TD><font size=1>Os dados deste bloco ser�o utilizados para identifica��o da atividade, bem como para o controle de sua execu��o.</font></td></tr>"
    ShowHTML "      <tr><TD align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    
    ' Recupera dados da op��o Projetos
    ShowHTML "      <tr>"
    DB_GetLinkData RS, w_cliente, "PJCAD"
    SelecaoProjeto "Pro<u>j</u>eto:", "J", "Selecione o projeto da atividade na rela��o.", w_projeto, w_usuario, RS("sq_menu"), "w_projeto", "PJLISTCAD", "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_atividade'; document.Form.submit();"""
    DesconectaBD
    ShowHTML "      </tr>"
    
    ShowHTML "      <tr>"
    SelecaoEtapa "Eta<u>p</u>a:", "P", "Se necess�rio, indique a etapa � qual esta atividade deve ser vinculada.", w_atividade, w_projeto, null, "w_atividade", "Grupo", null
    ShowHTML "      </tr>"

    ShowHTML "      <tr><TD valign=""top""><b>Detalh<u>a</u>mento:</b><br><textarea " & w_Disabled & " accesskey=""A"" name=""w_assunto"" class=""sti"" ROWS=5 cols=75 title=""Escreva um texto de detalhamento para esta atividade."">" & w_assunto & "</TEXTAREA></td>"
    If RS_menu("solicita_cc") = "S" Then
       ShowHTML "          <tr>"
       SelecaoCC "C<u>l</u>assifica��o:", "L", "Selecione um dos itens relacionados.", w_sqcc, null, "w_sqcc", "SIWSOLIC"
       ShowHTML "          </tr>"
    End If
    ShowHTML "      <tr><TD valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    SelecaoPessoa "Respo<u>n</u>s�vel:", "N", "Selecione o respons�vel pela atividade na rela��o.", w_solicitante, null, "w_solicitante", "USUARIOS"
    SelecaoUnidade "<U>U</U>nidade respons�vel:", "U", "Selecione a unidade respons�vel pela execu��o da atividade", w_sq_unidade_resp, null, "w_sq_unidade_resp", null, null
    SelecaoPrioridade "<u>P</u>rioridade:", "P", "Informe a prioridade desta atividade.", w_prioridade, null, "w_prioridade", null, null
    ShowHTML "          <tr>"
    Select Case RS_menu("data_hora")
       Case 1
          ShowHTML "              <TD valign=""top""><b>Limi<u>t</u>e para conclus�o:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_fim"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_fim & """ onKeyDown=""FormataData(this,event);"" title=""Data limite para que a execu��o da atividade esteja conclu�da.""></td>"
       Case 2
          ShowHTML "              <TD valign=""top""><b>Limi<u>t</u>e para conclus�o:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_fim"" class=""sti"" SIZE=""17"" MAXLENGTH=""17"" VALUE=""" & w_fim & """ onKeyDown=""FormataDataHora(this,event);"" title=""Data/hora limite para que a execu��o da atividade esteja conclu�da.""></td>"
       Case 3
          ShowHTML "              <TD valign=""top""><b>Data de re<u>c</u>ebimento:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_inicio"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & Nvl(w_inicio,FormataDataEdicao(Date())) & """ onKeyDown=""FormataData(this,event);"" title=""Data de recebimento da solicita��o.""></td>"
          ShowHTML "              <TD valign=""top""><b>Limi<u>t</u>e para conclus�o:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_fim"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_fim & """ onKeyDown=""FormataData(this,event);"" title=""Data limite para que a execu��o da atividade esteja conclu�da.""></td>"
       Case 4
          ShowHTML "              <TD valign=""top""><b>Data de re<u>c</u>ebimento:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_inicio"" class=""sti"" SIZE=""17"" MAXLENGTH=""17"" VALUE=""" & w_inicio & """ onKeyDown=""FormataDataHora(this,event);"" title=""Data/hora de recebimento da solicita��o.""></td>"
          ShowHTML "              <TD valign=""top""><b>Limi<u>t</u>e para conclus�o:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_fim"" class=""sti"" SIZE=""17"" MAXLENGTH=""17"" VALUE=""" & w_fim & """ onKeyDown=""FormataDataHora(this,event);"" title=""Data/hora limite para que a execu��o da atividade esteja conclu�da.""></td>"
    End Select
    ShowHTML "              <TD valign=""top""><b>O<u>r</u>�amento dispon�vel:</b><br><input " & w_Disabled & " accesskey=""O"" type=""text"" name=""w_valor"" class=""sti"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_valor & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o or�amento dispon�vel para execu��o da atividade, ou zero se n�o for o caso.""></td>"
    ShowHTML "          </table>"
    ShowHTML "      <tr><TD valign=""top""><b>Pa<u>l</u>avras-chave:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_palavra_chave"" size=""90"" maxlength=""90"" value=""" & w_palavra_chave & """ title=""Se desejar, informe palavras-chave adicionais aos campos informados e que permitam a identifica��o desta atividade.""></td>"
    ShowHTML "      <tr><TD align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><TD align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><TD valign=""top"" align=""center"" bgcolor=""#D0D0D0""><b>Identifica��o do proponente</td></td></tr>"
    ShowHTML "      <tr><TD align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><TD><font size=1>Os dados deste bloco identificam o proponente externo e sua localiza��o, sendo utilizados para consultas gerenciais por distribui��o geogr�fica.</font></td></tr>"
    ShowHTML "      <tr><TD align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><TD valign=""top""><b>Nome do proponent<u>e</u> externo:<br><INPUT ACCESSKEY=""E"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_proponente"" size=""90"" maxlength=""90"" value=""" & w_proponente & """ title=""Proponente externo da atividade. Preencha apenas se houver.""></td>"
    ShowHTML "      <tr><TD valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "      <tr>"
    SelecaoPais "<u>P</u>a�s:", "P", null, w_pais, null, "w_pais", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_uf'; document.Form.submit();"""
    SelecaoEstado "E<u>s</u>tado:", "S", null, w_uf, w_pais, "N", "w_uf", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='w_cidade'; document.Form.submit();"""
    SelecaoCidade "<u>C</u>idade:", "C", null, w_cidade, w_pais, w_uf, "w_cidade", null, null
    ShowHTML "          </table>"
    If RS_menu("descricao") = "S" or RS_menu("justificativa") = "S" Then
       ShowHTML "      <tr><TD align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><TD align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><TD valign=""top"" align=""center"" bgcolor=""#D0D0D0""><b>Informa��es adicionais</td></td></tr>"
       ShowHTML "      <tr><TD align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       ShowHTML "      <tr><TD><font size=1>Os dados deste bloco visam orientar os executores da atividade.</font></td></tr>"
       ShowHTML "      <tr><TD align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
       If RS_menu("descricao") = "S" Then
          ShowHTML "      <tr><TD valign=""top""><b>Res<u>u</u>ltados da atividade:</b><br><textarea " & w_Disabled & " accesskey=""U"" name=""w_descricao"" class=""sti"" ROWS=5 cols=75 title=""Descreva os resultados esperados ap�s a execu��o da atividade."">" & w_descricao & "</TEXTAREA></td>"
       End If
       If RS_menu("justificativa") = "S" Then
          ShowHTML "      <tr><TD valign=""top""><b><u>R</u>ecomenda��es superiores:</b><br><textarea " & w_Disabled & " accesskey=""R"" name=""w_justificativa"" class=""sti"" ROWS=5 cols=75 title=""Relacione as recomenda��es a serem seguidas na execu��o da atividade."">" & w_justificativa & "</TEXTAREA></td>"
       End If
    End If
    ShowHTML "      <tr><TD align=""center"" height=""2"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><TD align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><TD valign=""top"" align=""center"" bgcolor=""#D0D0D0""><b>Alerta de atraso</td></td></tr>"
    ShowHTML "      <tr><TD align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><TD><font size=1>Os dados abaixo indicam como deve ser tratada a proximidade da data limite para conclus�o da atividade.</font></td></tr>"
    ShowHTML "      <tr><TD align=""center"" height=""1"" bgcolor=""#000000""></td></tr>"
    ShowHTML "      <tr><TD><table border=""0"" width=""100%"">"
    ShowHTML "          <tr>"
    MontaRadioNS "<b>Emite alerta?</b>", w_aviso, "w_aviso"
    ShowHTML "              <TD valign=""top""><b>Quantos <U>d</U>ias antes da data limite?<br><INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_dias"" size=""2"" maxlength=""2"" value=""" & w_dias & """ title=""N�mero de dias para emiss�o do alerta de proximidade da data limite para conclus�o da atividade.""></td>"
    ShowHTML "          </table>"
    ShowHTML "      <tr><TD align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000""></TD></TR>"

    ' Verifica se poder� ser feito o envio da solicita��o, a partir do resultado da valida��o
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
    ShowHTML " alert('Op��o n�o dispon�vel');"
    'ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
    Estrutura_Texto_Fecha
    Estrutura_Fecha
  Estrutura_Fecha
  Estrutura_Fecha
  Rodape

  Set w_projeto_ant         = Nothing 
  Set w_atividade_ant       = Nothing 
  Set w_projeto             = Nothing 
  Set w_atividade           = Nothing 
  Set w_proponente          = Nothing 
  Set w_sq_unidade_resp     = Nothing 
  Set w_assunto             = Nothing 
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
  ShowHTML "<TITLE>" & conSgSistema & " - Listagem de projetos</TITLE>"
  If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & replace(MontaURL("MESA"),w_dir,"") & """>" End If
  Estrutura_CSS w_cliente
  
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
  ShowHtml "<center>"
  Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then 
    AbreSessao 
    ' Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem 
    ShowHTML "<tr><TD><a accesskey=""I"" class=""ss"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;" 
    ShowHTML "    <TD align=""right""><b>Registros existentes: " & RS.RecordCount 
    ShowHTML "<tr><TD align=""center"" colspan=3>" 
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>" 
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">" 
    ShowHTML "          <TD><b>T�tulo</font></td>" 
    ShowHTML "          <TD><b>Descri��o</font></td>" 
    ShowHTML "          <TD><b>Tipo</font></td>" 
    ShowHTML "          <TD><b>KB</font></td>" 
    ShowHTML "          <TD><b>Opera��es</font></td>" 
    ShowHTML "        </tr>" 
    If RS.EOF Then ' Se n�o foram selecionados registros, exibe mensagem 
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><TD colspan=7 align=""center""><b>N�o foram encontrados registros.</b></td></tr>" 
    Else 
      ' Lista os registros selecionados para listagem 
      While Not RS.EOF 
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If 
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">" 
        ShowHTML "        <TD>" & LinkArquivo("HL", w_cliente, RS("chave_aux"), "_blank", "Clique para exibir o arquivo em outra janela.", RS("nome"), null) & "</td>" 
        ShowHTML "        <TD>" & Nvl(RS("descricao"),"---") & "</td>" 
        ShowHTML "        <TD>" & RS("tipo") & "</td>" 
        ShowHTML "        <TD align=""right"">" & Round(cDbl(RS("tamanho"))/1024,1) & "&nbsp;</td>" 
        ShowHTML "        <TD align=""top"" nowrap>" 
        ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & w_chave & "&w_chave_aux=" & Rs("chave_aux") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp" 
        ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_chave=" & w_chave & "&w_chave_aux=" & Rs("chave_aux") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp" 
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
    
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """>" 
    ShowHTML "    <table width=""97%"" border=""0"">" 
    
    If O = "I" or O = "A" Then 
       DB_GetCustomerData RS, w_cliente 
       ShowHTML "      <tr><TD align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><b><font color=""#BC3131"">ATEN��O</font>: o tamanho m�ximo aceito para o arquivo � de " & cDbl(RS("upload_maximo"))/1024 & " KBytes</b>.</font></td>" 
       ShowHTML "<INPUT type=""hidden"" name=""w_upload_maximo"" value=""" & RS("upload_maximo") & """>" 
    End If 
    
    ShowHTML "      <tr><TD><b><u>T</u>�tulo:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_nome"" class=""sti"" SIZE=""75"" MAXLENGTH=""255"" VALUE=""" & w_nome & """ title=""OBRIGAT�RIO. Informe um t�tulo para o arquivo.""></td>" 
    ShowHTML "      <tr><TD><b><u>D</u>escri��o:</b><br><textarea " & w_Disabled & " accesskey=""D"" name=""w_descricao"" class=""sti"" ROWS=5 cols=65 title=""OBRIGAT�RIO. Descreva a finalidade do arquivo."">" & w_descricao & "</TEXTAREA></td>" 
    ShowHTML "      <tr><TD><b>A<u>r</u>quivo:</b><br><input " & w_Disabled & " accesskey=""R"" type=""file"" name=""w_caminho"" class=""sti"" SIZE=""80"" MAXLENGTH=""100"" VALUE="""" title=""OBRIGAT�RIO. Clique no bot�o ao lado para localizar o arquivo. Ele ser� transferido automaticamente para o servidor."">" 
    If w_caminho > "" Then 
       ShowHTML "              <b>" & LinkArquivo("SS", w_cliente, w_caminho, "_blank", "Clique para exibir o arquivo atual.", "Exibir", null) & "</b>" 
    End If 
    ShowHTML "      <tr><hr>" 
    If O = "E" Then 
       ShowHTML "   <input class=""stb"" type=""submit"" name=""Botao"" value=""Excluir"" onClick=""return confirm('Confirma a exclus�o do registro?');"">" 
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
  Else 
    ScriptOpen "JavaScript" 
    ShowHTML " alert('Op��o n�o dispon�vel');" 
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
  ShowHTML "<TITLE>" & conSgSistema & " - Listagem de projetos</TITLE>"
  If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & replace(MontaURL("MESA"),w_dir,"") & """>" End If
  Estrutura_CSS w_cliente
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
  ShowHtml "<center>"
  Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  
  
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    AbreSessao
    ' Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
    ShowHTML "<tr><TD><a accesskey=""I"" class=""ss"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <TD align=""right""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><TD align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <TD><b>Pessoa</font></td>"
    ShowHTML "          <TD><b>Visao</font></td>"
    ShowHTML "          <TD><b>Envia e-mail</font></td>"
    ShowHTML "          <TD><b>Opera��es</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se n�o foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><TD colspan=7 align=""center""><b>N�o foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <TD>" & ExibePessoa(w_dir_volta, w_cliente, RS("sq_pessoa"), TP, RS("nome") & " (" & RS("lotacao") & ")") & "</td>"
        ShowHTML "        <TD>" & RetornaTipoVisao(RS("tipo_visao")) & "</td>"
        ShowHTML "        " & Replace(Replace(RS("envia_email"),"S","Sim"),"N","N�o") & "</td>"
        ShowHTML "        <TD align=""top"" nowrap>"
        ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & w_chave & "&w_chave_aux=" & Rs("sq_pessoa") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & "GRAVA&R=" & w_Pagina & par & "&O=E&w_chave=" & w_chave & "&w_chave_aux=" & Rs("sq_pessoa") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ onClick=""return confirm('Confirma a exclus�o do registro?');"">Excluir</A>&nbsp"
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
    AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """>"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr><TD valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
    If O = "I" Then
       SelecaoPessoa "<u>P</u>essoa:", "N", "Selecione o interessado na rela��o.", w_chave_aux, null, "w_chave_aux", "USUARIOS"
    Else
       ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_chave_aux &""">"
       ShowHTML "      <tr><TD valign=""top""><b>Pessoa:</b><br>" & w_nome & "</td>"
    End If
    SelecaoTipoVisao "<u>T</u>ipo de vis�o:", "T", "Selecione o tipo de vis�o que o interessado ter� desta atividade.", w_tipo_visao, null, "w_tipo_visao", null, null
    ShowHTML "          </table>"
    ShowHTML "      <tr>"
    MontaRadioNS "<b>Envia e-mail ao interessado quando houver encaminhamento?</b>", w_envia_email, "w_envia_email"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><TD align=""center"" colspan=4><hr>"
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
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Op��o n�o dispon�vel');"
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
REM Rotina de �reas envolvidas
REM -------------------------------------------------------------------------
Sub Areas
  Dim w_chave, w_chave_pai, w_chave_aux, w_nome, w_papel, w_envia_email
  
  Dim w_troca, i, w_erro
  
  w_Chave           = Request("w_Chave")
  w_chave_aux       = Request("w_chave_aux")
  w_troca           = Request("w_troca")
  
  If w_troca > "" Then ' Se for recarga da p�gina
     w_papel                = Request("w_papel")
  ElseIf O = "L" Then
     ' Recupera todos os registros para a listagem
     DB_GetSolicAreas RS, w_chave, null, "LISTA"
     RS.Sort = "nome"
  ElseIf InStr("AEV",O) > 0 and w_Troca = "" Then
     ' Recupera os dados do endere�o informado
     DB_GetSolicAreas RS, w_chave, w_chave_aux, "REGISTRO"
     w_nome                 = RS("nome")
     w_papel                = RS("papel")
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>" & conSgSistema & " - Listagem de projetos</TITLE>"
  If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & replace(MontaURL("MESA"),w_dir,"") & """>" End If
  Estrutura_CSS w_cliente

  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     modulo
     checkbranco
     formatadata
     FormataCEP
     FormataValor
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_chave_aux", "�rea/Institui��o", "HIDDEN", "1", "1", "10", "", "1"
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
  ShowHtml "<center>"
  Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  
  
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  
  If O = "L" Then
    AbreSessao
    ' Exibe a quantidade de registros apresentados na listagem e o cabe�alho da tabela de listagem
    ShowHTML "<tr><TD><a accesskey=""I"" class=""ss"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&w_chave=" & w_chave & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <TD align=""right""><b>Registros existentes: " & RS.RecordCount
    ShowHTML "<tr><TD align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <TD><b>�rea/Institui��o</font></td>"
    ShowHTML "          <TD><b>Papel</font></td>"
    ShowHTML "          <TD><b>Opera��es</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se n�o foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><TD colspan=7 align=""center""><b>N�o foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <TD>" & RS("nome") & "</td>"
        ShowHTML "        <TD>" & RS("papel") & "</td>"
        ShowHTML "        <TD align=""top"" nowrap>"
        ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & w_chave & "&w_chave_aux=" & Rs("sq_unidade") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""hl"" HREF=""" & w_dir & w_Pagina & "GRAVA&R=" & w_Pagina & par & "&O=E&w_chave=" & w_chave & "&w_chave_aux=" & Rs("sq_unidade") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ onClick=""return confirm('Confirma a exclus�o do registro?');"">Excluir</A>&nbsp"
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
    AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """>"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr><TD valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
    If O = "I" Then
       SelecaoUnidade "<U>�</U>rea/Institui��o:", "A", null, w_chave_aux, null, "w_chave_aux", null, null
    Else
       ShowHTML "<INPUT type=""hidden"" name=""w_chave_aux"" value=""" & w_chave_aux &""">"
       ShowHTML "      <tr><TD valign=""top""><b>�rea/Institui��o:</b><br>" & w_nome & "</td>"
    End If
    ShowHTML "      <tr><TD valign=""top""><b><u>P</u>apel desempenhado:</b><br><textarea " & w_Disabled & " accesskey=""P"" name=""w_papel"" class=""sti"" ROWS=5 cols=75 title=""Descreva o papel desempenhado pela �rea ou institui��o na execu��o da atividade."">" & w_papel & "</TEXTAREA></td>"
    ShowHTML "          </table>"
    ShowHTML "      <tr><TD align=""center"" colspan=4><hr>"
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
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Op��o n�o dispon�vel');"
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
  Set w_papel           = Nothing 
  
  Set w_troca           = Nothing 
  Set i                 = Nothing 
  Set w_erro            = Nothing
End Sub
REM =========================================================================
REM Fim da tela de �reas envolvidas
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de visualiza��o
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
  ShowHTML "<TITLE>" & conSgSistema & " - Visualiza��o de atividade</TITLE>"
  ShowHTML "</HEAD>"  
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If w_tipo <> "WORD" Then
     BodyOpenClean "onLoad='document.focus()'; "
  End If
  ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "EMBED") & """><TD ALIGN=""RIGHT""><B><FONT SIZE=4 COLOR=""#000000"">"
  ShowHTML "Visualiza��o de Atividade"
  ShowHTML "</FONT><TR><TD ALIGN=""RIGHT""><B><FONT SIZE=2 COLOR=""#000000"">" & DataHora() & "</B></TD></TR>"
  If w_tipo <> "WORD" Then
     ShowHTML "&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Imprimir"" SRC=""images/impressora.jpg"" onClick=""window.print();"">"
     ShowHTML "&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Gerar word"" SRC=""images/word.gif"" onClick=""window.open('" & w_pagina & "Visual&R=" & w_pagina & par & "&O=L&w_chave=" & w_chave & "&w_tipo=word&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=1&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") &"','VisualAcaoWord','menubar=yes resizable=yes scrollbars=yes');"">"
  End If
  ShowHTML "</FONT></B></TD></TR></TABLE>"
  ShowHTML "<HR>"
  If w_tipo > "" and w_tipo <> "WORD" Then
     ShowHTML "<center><B>Clique <a class=""hl"" href=""javascript:history.back(1);"">aqui</a> para voltar � tela anterior</font></b></center>"
  End If

  ' Chama a rotina de visualiza��o dos dados da atividade, na op��o "Listagem"
  ShowHTML VisualDemanda(w_chave, "L", w_usuario)

  If w_tipo > "" and w_tipo <> "WORD" Then
     ShowHTML "<center><B>Clique <a class=""hl"" href=""javascript:history.back(1);"">aqui</a> para voltar � tela anterior</font></b></center>"
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
REM Fim da rotina de visualiza��o
REM -------------------------------------------------------------------------

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
  ShowHTML "<TITLE>" & conSgSistema & " - Listagem de projetos</TITLE>"
  If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & replace(MontaURL("MESA"),w_dir,"") & """>" End If
  Estrutura_CSS w_cliente
  
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
  ShowHtml "<center>"
  Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  
  
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"

  ' Chama a rotina de visualiza��o dos dados da atividade, na op��o "Listagem"
  ShowHTML VisualDemanda(w_chave, "V", w_usuario)

  ShowHTML "<HR>"
  AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"GDPGERAL",R,O
  ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & w_menu & """>"
  DB_GetSolicData RS, w_chave, "GDPGERAL"
  ShowHTML "<INPUT type=""hidden"" name=""w_tramite"" value=""" & RS("sq_siw_tramite") & """>"
  DesconectaBD

  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><TD align=""center"">"
  ShowHTML "  <table width=""97%"" border=""0"">"
  ShowHTML "      <tr><TD align=""LEFT"" colspan=4><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
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
REM Fim da rotina de exclus�o
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de tramita��o
REM -------------------------------------------------------------------------
Sub Encaminhamento

  Dim w_chave, w_chave_pai, w_chave_aux, w_destinatario, w_despacho
  Dim w_tramite, w_sg_tramite, w_novo_tramite
  
  Dim w_troca, i, w_erro
  
  w_Chave           = Request("w_Chave")
  w_chave_aux       = Request("w_chave_aux")
  w_troca           = Request("w_troca")
  w_erro            = ""
    
  If w_troca > "" Then ' Se for recarga da p�gina
     w_tramite      = Request("w_tramite")
     w_destinatario = Request("w_destinatario")
     w_novo_tramite = Request("w_novo_tramite")
     w_despacho     = Request("w_despacho")
  Else
     DB_GetSolicData RS, w_chave, "GDPGERAL"
     w_tramite      = RS("sq_siw_tramite")
     w_novo_tramite = RS("sq_siw_tramite")
     DesconectaBD
  End If
  
  ' Recupera a sigla do tr�mite desejado, para verificar a lista de poss�veis destinat�rios.
  DB_GetTramiteData RS, w_novo_tramite
  w_sg_tramite = RS("sigla")
  DesconectaBD

  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "<TITLE>" & conSgSistema & " - Listagem de projetos</TITLE>"
  If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & replace(MontaURL("MESA"),w_dir,"") & """>" End If
  Estrutura_CSS w_cliente
  
  If InStr("V",O) > 0 Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     Validate "w_destinatario", "Destinat�rio", "HIDDEN", "1", "1", "10", "", "1"
     Validate "w_despacho", "Despacho", "", "1", "1", "2000", "1", "1"
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
     BodyOpen "onLoad='document.Form.w_destinatario.focus()';"
  End If
  ShowHtml "<center>"
  Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  
  
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"

  ' Chama a rotina de visualiza��o dos dados da atividade, na op��o "Listagem"
  ShowHTML VisualDemanda(w_chave, "V", w_usuario)

  ShowHTML "<tr><TD>"
  ShowHTML "  <table width=""100%"" border=""0"">"
  ShowHTML "    <tr><TD colspan=""2""><HR>"
  
  AbreForm "Form", w_dir & w_Pagina & "Grava", "POST", "return(Validacao(this));", null,P1,P2,P3,P4,TP,"GDPENVIO",R,O
  ShowHTML MontaFiltro("POST")
  ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
  ShowHTML "<INPUT type=""hidden"" name=""w_menu"" value=""" & w_menu & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_tramite"" value=""" & w_tramite & """>"

  ShowHTML "    <tr><TD valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
  If P1 <> 1 Then ' Se n�o for cadastramento
     SelecaoFase "<u>F</u>ase da atividade:", "F", "Se deseja alterar a fase atual da atividade, selecione a fase para a qual deseja envi�-la.", w_novo_tramite, w_menu, "w_novo_tramite", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='w_destinatario'; document.Form.submit();"""
     ' Se for envio para o cadastramento, exibe apenas as pessoas autorizadas a faz�-lo.
     If w_sg_tramite = "CI" Then
        SelecaoSolicResp "<u>D</u>estinat�rio:", "D", "Selecione um destinat�rio para a atividade na rela��o.", w_destinatario, w_chave, w_novo_tramite, w_novo_tramite, "w_destinatario", "CADASTRAMENTO"
     Else
        SelecaoPessoa "<u>D</u>estinat�rio:", "D", "Selecione um destinat�rio para a atividade na rela��o.", w_destinatario, null, "w_destinatario", "USUARIOS"
     End If
  Else
     SelecaoFase "<u>F</u>ase da atividade:", "F", "Se deseja alterar a fase atual da atividade, selecione a fase para a qual deseja envi�-la.", w_novo_tramite, w_menu, "w_novo_tramite", null, null
     SelecaoPessoa "<u>D</u>estinat�rio:", "D", "Selecione um destinat�rio para a atividade na rela��o.", w_destinatario, null, "w_destinatario", "USUARIOS"
  End If
  ShowHTML "    <tr><TD valign=""top"" colspan=2><b>D<u>e</u>spacho:</b><br><textarea " & w_Disabled & " accesskey=""E"" name=""w_despacho"" class=""sti"" ROWS=5 cols=75 title=""Descreva o papel desempenhado pela �rea ou institui��o na execu��o da atividade."">" & w_despacho & "</TEXTAREA></td>"
  ShowHTML "      </table>"
  ShowHTML "      <tr><TD align=""LEFT"" colspan=4><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
  ShowHTML "    <tr><TD align=""center"" colspan=4><hr>"
  ShowHTML "      <input class=""stb"" type=""submit"" name=""Botao"" value=""Enviar"">"
  If P1 <> 1 Then ' Se n�o for cadastramento
     ' Volta para a listagem
     DB_GetMenuData RS, w_menu
     ShowHTML "      <input class=""stb"" type=""button"" onClick=""location.href='" & RS("link") & "&O=L&w_chave=" & Request("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & rs("sigla") & MontaFiltro("GET") & "';"" name=""Botao"" value=""Abandonar"">"
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
  ShowHTML "<TITLE>" & conSgSistema & " - Listagem de projetos</TITLE>"
  If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & replace(MontaURL("MESA"),w_dir,"") & """>" End If
  Estrutura_CSS w_cliente
  If InStr("V",O) > 0 Then
     ScriptOpen "JavaScript"
     ProgressBar w_dir_volta, UploadID         
     ValidateOpen "Validacao"
     Validate "w_observacao", "Anota��o", "", "1", "1", "2000", "1", "1"
     Validate "w_caminho", "Arquivo", "", "", "5", "255", "1", "1"
     Validate "w_assinatura", "Assinatura Eletr�nica", "1", "1", "6", "30", "1", "1"
     If P1 <> 1 Then ' Se n�o for encaminhamento
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
  ShowHtml "<center>"
  Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  
  
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"

  ' Chama a rotina de visualiza��o dos dados da atividade, na op��o "Listagem"
  ShowHTML VisualDemanda(w_chave, "V", w_usuario)

  ShowHTML "<HR>"
  ShowHTML "<FORM action=""" & w_dir & w_pagina & "Grava&SG=GDPENVIO&O="&O&"&w_menu="&w_menu&"&UploadID="&UploadID&""" name=""Form"" onSubmit=""return(Validacao(this));"" enctype=""multipart/form-data"" method=""POST"">"
  ShowHTML "<INPUT type=""hidden"" name=""P1"" value=""" & P1 & """>"
  ShowHTML "<INPUT type=""hidden"" name=""P2"" value=""" & P2 & """>"
  ShowHTML "<INPUT type=""hidden"" name=""P3"" value=""" & P3 & """>"
  ShowHTML "<INPUT type=""hidden"" name=""P4"" value=""" & P4 & """>"
  ShowHTML "<INPUT type=""hidden"" name=""TP"" value=""" & TP & """>"
  ShowHTML "<INPUT type=""hidden"" name=""R"" value=""" & R & """>"

  ShowHTML MontaFiltro("POST")
  ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
  ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
  DB_GetSolicData RS, w_chave, "GDPGERAL"
  ShowHTML "<INPUT type=""hidden"" name=""w_tramite"" value=""" & RS("sq_siw_tramite") & """>"
  DesconectaBD

  ShowHTML "<tr bgcolor=""" & conTrBgColor & """>"
  ShowHTML "  <table width=""97%"" border=""0"">"
  ShowHTML "    <tr><TD valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0><tr valign=""top"">"
  DB_GetCustomerData RS, w_cliente  
  ShowHTML "      <tr><TD align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><b><font color=""#BC3131"">ATEN��O</font>: o tamanho m�ximo aceito para o arquivo � de " & cDbl(RS("upload_maximo"))/1024 & " KBytes</b>.</font></td>"  
  ShowHTML "<INPUT type=""hidden"" name=""w_upload_maximo"" value=""" & RS("upload_maximo") & """>"  
  ShowHTML "      <tr><TD valign=""top""><b>A<u>n</u>ota��o:</b><br><textarea " & w_Disabled & " accesskey=""N"" name=""w_observacao"" class=""sti"" ROWS=5 cols=75 title=""Redija a anota��o desejada."">" & w_observacao & "</TEXTAREA></td>"
  ShowHTML "      <tr><TD><b>A<u>r</u>quivo:</b><br><input " & w_Disabled & " accesskey=""R"" type=""file"" name=""w_caminho"" class=""sti"" SIZE=""80"" MAXLENGTH=""100"" VALUE="""" title=""OPCIONAL. Se desejar anexar um arquivo, clique no bot�o ao lado para localiz�-lo. Ele ser� transferido automaticamente para o servidor."">"
  ShowHTML "      </table>"
  ShowHTML "      <tr><TD align=""LEFT"" colspan=4><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
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
REM Fim da rotina de anota��o
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de conclus�o
REM -------------------------------------------------------------------------
Sub Concluir

  Dim w_chave, w_chave_pai, w_chave_aux, w_destinatario
  Dim w_inicio_real, w_fim_real, w_nota_conclusao, w_custo_real
  
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
  ShowHTML "<TITLE>" & conSgSistema & " - Listagem de projetos</TITLE>"
  If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""300; URL=" & replace(MontaURL("MESA"),w_dir,"") & """>" End If
  Estrutura_CSS w_cliente
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
           Validate "w_fim_real", "T�rmino da execu��o", "DATA", 1, 10, 10, "", "0123456789/"
        Case 2
           Validate "w_fim_real", "T�rmino da execu��o", "DATAHORA", 1, 17, 17, "", "0123456789/"
        Case 3
           Validate "w_inicio_real", "In�cio da execu��o", "DATA", 1, 10, 10, "", "0123456789/"
           Validate "w_fim_real", "T�rmino da execu��o", "DATA", 1, 10, 10, "", "0123456789/"
           CompData "w_inicio_real", "In�cio da execu��o", "<=", "w_fim_real", "T�rmino da execu��o"
           CompData "w_fim_real", "T�rmino da execu��o", "<=", FormataDataEdicao(FormatDateTime(Date(),2)), "data atual"
        Case 4
           Validate "w_inicio_real", "In�cio da execu��o", "DATAHORA", 1, 17, 17, "", "0123456789/,: "
           Validate "w_fim_real", "T�rmino da execu��o", "DATAHORA", 1, 17, 17, "", "0123456789/,: "
           CompData "w_inicio_real", "In�cio da execu��o", "<=", "w_fim_real", "T�rmino da execu��o"
     End Select
     Validate "w_custo_real", "Custo real", "VALOR", "1", 4, 18, "", "0123456789.,"
     Validate "w_nota_conclusao", "Nota de conclus�o", "", "1", "1", "2000", "1", "1"
     Validate "w_assinatura", "Assinatura Eletr�nica", "1", "1", "6", "30", "1", "1"
     If P1 <> 1 Then ' Se n�o for encaminhamento
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
  ShowHtml "<center>"
  Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  
  
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  ' Chama a rotina de visualiza��o dos dados da atividade, na op��o "Listagem"
  ShowHTML VisualDemanda(w_chave, "V", w_usuario)

  ShowHTML "<HR>"
  ShowHTML "<FORM action=""" & w_dir & w_pagina & "Grava&SG=GDCONC&O="&O&"&w_menu="&w_menu&"&UploadID="&UploadID&""" name=""Form"" onSubmit=""return(Validacao(this));"" enctype=""multipart/form-data"" method=""POST"">"  
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
  DB_GetSolicData RS, w_chave, "GDPGERAL"
  ShowHTML "<INPUT type=""hidden"" name=""w_tramite"" value=""" & RS("sq_siw_tramite") & """>"
  DesconectaBD

  ShowHTML "<tr bgcolor=""" & conTrBgColor & """>"
  ShowHTML "  <table width=""97%"" border=""0"">"
  DB_GetCustomerData RS, w_cliente 
  ShowHTML "      <tr><TD align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><b><font color=""#BC3131"">ATEN��O</font>: o tamanho m�ximo aceito para o arquivo � de " & cDbl(RS("upload_maximo"))/1024 & " KBytes</b>.</font></td>" 
  ShowHTML "<INPUT type=""hidden"" name=""w_upload_maximo"" value=""" & RS("upload_maximo") & """>" 

  ShowHTML "      <tr><TD valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
  ShowHTML "          <tr>"
  Select Case RS_menu("data_hora")
     Case 1
        ShowHTML "              <TD valign=""top""><b><u>T</u>�rmino da execu��o:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_fim_real"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_fim_real & """ onKeyDown=""FormataData(this,event);"" title=""Informe a data de t�rmino da execu��o da atividade.""></td>"
     Case 2
        ShowHTML "              <TD valign=""top""><b><u>T</u>�rmino da execu��o:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_fim_real"" class=""sti"" SIZE=""17"" MAXLENGTH=""17"" VALUE=""" & w_fim_real & """ onKeyDown=""FormataDataHora(this,event);"" title=""Informe a data/hora de t�rmino da execu��o da atividade.""></td>"
     Case 3
        ShowHTML "              <TD valign=""top""><b>In�<u>c</u>io da execu��o:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_inicio_real"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_inicio_real & """ onKeyDown=""FormataData(this,event);"" title=""Informe a data/hora de in�cio da execu��o da atividade.""></td>"
        ShowHTML "              <TD valign=""top""><b><u>T</u>�rmino da execu��o:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_fim_real"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & w_fim_real & """ onKeyDown=""FormataData(this,event);"" title=""Informe a data de t�rmino da execu��o da atividade.""></td>"
     Case 4
        ShowHTML "              <TD valign=""top""><b>In�<u>c</u>io da execu��o:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""w_inicio_real"" class=""sti"" SIZE=""17"" MAXLENGTH=""17"" VALUE=""" & w_inicio_real & """ onKeyDown=""FormataDataHora(this,event);"" title=""Informe a data/hora de in�cio da execu��o da atividade.""></td>"
        ShowHTML "              <TD valign=""top""><b><u>T</u>�rmino da execu��o:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""w_fim_real"" class=""sti"" SIZE=""17"" MAXLENGTH=""17"" VALUE=""" & w_fim_real & """ onKeyDown=""FormataDataHora(this,event);"" title=""Informe a data de t�rmino da execu��o da atividade.""></td>"
  End Select
  ShowHTML "              <TD valign=""top""><b>Custo <u>r</u>eal:</b><br><input " & w_Disabled & " accesskey=""O"" type=""text"" name=""w_custo_real"" class=""sti"" SIZE=""18"" MAXLENGTH=""18"" VALUE=""" & w_custo_real & """ onKeyDown=""FormataValor(this,18,2,event);"" title=""Informe o or�amento dispon�vel para execu��o da atividade, ou zero se n�o for o caso.""></td>"
  ShowHTML "          </table>"
  ShowHTML "      <tr><TD valign=""top""><b>Nota d<u>e</u> conclus�o:</b><br><textarea " & w_Disabled & " accesskey=""E"" name=""w_nota_conclusao"" class=""sti"" ROWS=5 cols=75 title=""Descreva o quanto a demanda atendeu aos resultados esperados."">" & w_nota_conclusao & "</TEXTAREA></td>"  
  ShowHTML "      <tr><TD><b>A<u>r</u>quivo:</b><br><input " & w_Disabled & " accesskey=""R"" type=""file"" name=""w_caminho"" class=""sti"" SIZE=""80"" MAXLENGTH=""100"" VALUE="""" title=""OPCIONAL. Se desejar anexar um arquivo, clique no bot�o ao lado para localiz�-lo. Ele ser� transferido automaticamente para o servidor."">"  
  ShowHTML "      <tr><TD align=""LEFT"" colspan=4><b><U>A</U>ssinatura Eletr�nica:<BR> <INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td></tr>"
  ShowHTML "    <tr><TD align=""center"" colspan=4><hr>"
  ShowHTML "      <input class=""stb"" type=""submit"" name=""Botao"" value=""Concluir"">"
  If P1 <> 1 Then ' Se n�o for cadastramento
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
REM Fim da rotina de conclus�o
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de prepara��o para envio de e-mail relativo a demandas eventuais
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
  w_html = w_html & "<tr bgcolor=""" & conTrBgColor & """>" & VbCrLf
  w_html = w_html & "    <table width=""97%"" border=""0"">" & VbCrLf
  If p_tipo = 1 Then
     w_html = w_html & "      <tr valign=""top""><b>INCLUS�O DE ATIVIDADE</b></font><br><br><TD></tr>" & VbCrLf
  ElseIf p_tipo = 2 Then
     w_html = w_html & "      <tr valign=""top""><b>TRAMITA��O DE ATIVIDADE</b></font><br><br><TD></tr>" & VbCrLf
  ElseIf p_tipo = 3 Then
     w_html = w_html & "      <tr valign=""top""><b>CONCLUS�O DE ATIVIDADE</b></font><br><br><TD></tr>" & VbCrLf
  End IF
  w_html = w_html & "      <tr valign=""top""><TD><b><font color=""#BC3131"">ATEN��O</font>: Esta � uma mensagem de envio autom�tico. N�o responda esta mensagem.</b></font><br><br><TD></tr>" & VbCrLf


  ' Recupera os dados da atividade
  DB_GetSolicData RSM, p_solic, "GDGERAL"
  
  w_nome = "Atividade " & RSM("sq_siw_solicitacao")

  w_html = w_html & VbCrLf & "<tr bgcolor=""" & conTrBgColor & """>"
  w_html = w_html & VbCrLf & "    <table width=""99%"" border=""0"">"
  w_html = w_html & VbCrLf & "      <tr><TD valign=""top"">Projeto: <b>" & RSM("nm_projeto") & "  (" & RSM("sq_solic_pai") & ")</b></td>"
  If Not IsNull(RSM("nm_etapa")) Then
     w_html = w_html & VbCrLf & "      <tr><TD valign=""top"">Etapa: <b>" & MontaOrdemEtapa(RSM("sq_projeto_etapa")) & ". " & RSM("nm_etapa") & " </b></td>"
  End If
  w_html = w_html & VbCrLf & "      <tr><TD><font size=1>Detalhamento: <b>" & p_solic & "<br>" & CRLF2BR(RSM("assunto")) & "</b></font></td></tr>"
      
  ' Identifica��o da atividade
  w_html = w_html & VbCrLf & "      <tr><TD valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><b>EXTRATO DA ATIVIDADE</td>"
  ' Se a classifica��o foi informada, exibe.
  If Not IsNull(RSM("sq_cc")) Then
     w_html = w_html & VbCrLf & "      <tr><TD valign=""top"">Classifica��o:<br><b>" & RSM("cc_nome") & " </b></td>"
  End If
  w_html = w_html & VbCrLf & "      <tr><TD valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
  w_html = w_html & VbCrLf & "          <tr valign=""top"">"
  w_html = w_html & VbCrLf & "          <TD>Respons�vel:<br><b>" & RSM("nm_sol") & "</b></td>"
  w_html = w_html & VbCrLf & "          <TD>Unidade respons�vel:<br><b>" & RSM("nm_unidade_resp") & "</b></td>"
  w_html = w_html & VbCrLf & "          <tr valign=""top"">"
  w_html = w_html & VbCrLf & "          <TD>Data de recebimento:<br><b>" & FormataDataEdicao(RSM("inicio")) & " </b></td>"
  w_html = w_html & VbCrLf & "          <TD>Limite para conclus�o:<br><b>" & FormataDataEdicao(RSM("fim")) & " </b></td>"
  w_html = w_html & VbCrLf & "          <TD>Prioridade:<br><b>" & RetornaPrioridade(RSM("prioridade")) & " </b></td>"
  w_html = w_html & VbCrLf & "          </table>"

  ' Informa��es adicionais
  If Nvl(RSM("descricao"),"") > "" Then 
     If Nvl(RSM("descricao"),"") > "" Then w_html = w_html & VbCrLf & "      <tr><TD valign=""top"">Resultados da atividade:<br><b>" & CRLF2BR(RSM("descricao")) & " </b></td>" End If
  End If

  w_html = w_html & VbCrLf & "    </table>"
  w_html = w_html & VbCrLf & "</tr>"

  ' Dados da conclus�o da atividade, se ela estiver nessa situa��o
  If RSM("concluida") = "S" and Nvl(RSM("data_conclusao"),"") > "" Then
     w_html = w_html & VbCrLf & "      <tr><TD valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><b>DADOS DA CONCLUS�O</td>"
     w_html = w_html & VbCrLf & "      <tr><TD valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
     w_html = w_html & VbCrLf & "          <tr valign=""top"">"
     w_html = w_html & VbCrLf & "          <TD>In�cio da execu��o:<br><b>" & FormataDataEdicao(RSM("inicio_real")) & " </b></td>"
     w_html = w_html & VbCrLf & "          <TD>T�rmino da execu��o:<br><b>" & FormataDataEdicao(RSM("fim_real")) & " </b></td>"
     w_html = w_html & VbCrLf & "          </table>"
     w_html = w_html & VbCrLf & "      <tr><TD valign=""top"">Nota de conclus�o:<br><b>" & CRLF2BR(RSM("nota_conclusao")) & " </b></td>"
  End If

  If p_tipo = 2 Then ' Se for tramita��o
     ' Encaminhamentos
     DB_GetSolicLog RS, p_solic, null, "LISTA"
     RS.Sort = "data desc"
     w_html = w_html & VbCrLf & "      <tr><TD valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><b>�LTIMO ENCAMINHAMENTO</td>"
     w_html = w_html & VbCrLf & "      <tr><TD valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
     w_html = w_html & VbCrLf & "          <tr valign=""top"">"
     w_html = w_html & VbCrLf & "          <TD>De:<br><b>" & RS("responsavel") & "</b></td>"
     w_html = w_html & VbCrLf & "          <TD>Para:<br><b>" & RS("destinatario") & "</b></td>"
     w_html = w_html & VbCrLf & "          <tr valign=""top""><TD colspan=2>Despacho:<br><b>" & CRLF2BR(Nvl(RS("despacho"),"---")) & " </b></td>"
     w_html = w_html & VbCrLf & "          </table>"
     
     ' Configura o destinat�rio da tramita��o como destinat�rio da mensagem
     DB_GetPersonData RS, w_cliente, RS("sq_pessoa_destinatario"), null, null
     w_destinatarios = RS("email") & "; "
     
     DesconectaBD
  End If

  w_html = w_html & VbCrLf & "      <tr><TD valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><b>OUTRAS INFORMA��ES</td>"
  DB_GetCustomerSite RS, Session("p_cliente")
  w_html = w_html & "      <tr valign=""top""><TD>" & VbCrLf
  w_html = w_html & "         Para acessar o sistema use o endere�o: <b><a class=""ss"" href=""" & RS("logradouro") & """ target=""_blank"">" & RS("Logradouro") & "</a></b></li>" & VbCrLf
  w_html = w_html & "      </font></td></tr>" & VbCrLf
  DesconectaBD

  w_html = w_html & "      <tr valign=""top""><TD>" & VbCrLf
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
  
  ' Recupera o e-mail do titular e do substituto pela unidade respons�vel
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
REM Procedimento que executa as opera��es de BD
REM -------------------------------------------------------------------------
Public Sub Grava
  Dim p_sq_endereco_unidade
  Dim p_modulo
  Dim w_Null
  Dim w_mensagem
  Dim FS, F1, w_file, w_tamanho, w_tipo, w_nome, field, w_maximo
  Dim w_chave_nova
  
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
    Case "GDPGERAL"
       ' Verifica se a Assinatura Eletr�nica � v�lida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          
          DML_PutDemandaGeral O, _
              Request("w_chave"), Request("w_menu"), Session("lotacao"), Request("w_solicitante"), Request("w_proponente"), _
              Session("sq_pessoa"), null, Request("w_sqcc"), Request("w_descricao"), Request("w_justificativa"), "0", Request("w_inicio"), Request("w_fim"), Request("w_valor"), _
              Request("w_data_hora"), Request("w_sq_unidade_resp"), Request("w_assunto"), Request("w_prioridade"), Request("w_aviso"), Request("w_dias"), _
              Request("w_cidade"), Request("w_palavra_chave"),null, null, null, null, null, null, null, _
              Request("w_projeto"), Request("w_atividade"), Request("w_projeto_ant"), Request("w_atividade_ant"), w_chave_nova, w_copia
          
          If O = "I" Then
             ' Envia e-mail comunicando a inclus�o
             SolicMail Nvl(Request("w_chave"), w_chave_nova) ,1

             ' Recupera os dados para montagem correta do menu
             DB_GetMenuData RS1, w_menu
             ScriptOpen "JavaScript"
             ShowHTML "  parent.menu.location='../Menu.asp?par=ExibeDocs&O=A&w_chave=" & w_chave_nova & "&w_documento=Nr. " & w_chave_nova & "&R=" & R & "&SG=" & RS1("sigla") & "&TP=" & TP & MontaFiltro("GET") & "';"
          ElseIf O = "E" Then
             ScriptOpen "JavaScript"
             ShowHTML "  location.href='" & R & "&O=L&R=" & R & "&SG=GDPCAD&w_menu=" & w_menu & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&p_proponente=" & Request("p_proponente") & MontaFiltro("GET") & "';"
          Else
             ' Aqui deve ser usada a vari�vel de sess�o para evitar erro na recupera��o do link
             DB_GetLinkData RS1, Session("p_cliente"), SG
             ScriptOpen "JavaScript"
             ShowHTML "  location.href='" & replace(RS1("link"),w_dir,"") & "&O=" & O & "&w_chave=" & Request("w_Chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
          End If
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletr�nica inv�lida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "GDPINTERES"
       ' Verifica se a Assinatura Eletr�nica � v�lida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then

          DML_PutDemandaInter O, Request("w_chave"), Request("w_chave_aux"), Request("w_tipo_visao"), Request("w_envia_email")
          
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
    Case "GDPAREAS"
       ' Verifica se a Assinatura Eletr�nica � v�lida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then

          DML_PutDemandaAreas O, Request("w_chave"), Request("w_chave_aux"), Request("w_papel")
          
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
    Case "GDPANEXO"
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
    Case "GDPENVIO" 
       ' Verifica se a Assinatura Eletr�nica � v�lida 
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _ 
          w_assinatura = "" Then 
    
          ' Trata o recebimento de upload ou dados 
          If InStr(uCase(Request.ServerVariables("http_content_type")),"MULTIPART/FORM-DATA") > 0 Then 
             ' Se foi feito o upload de um arquivo 
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
                ShowHTML "  alert('ATEN��O: ocorreu um erro na transfer�ncia do arquivo. Tente novamente!');" 
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
    
             ' Envia e-mail comunicando a inclus�o
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
       Else 
          ScriptOpen "JavaScript" 
          ShowHTML "  alert('Assinatura Eletr�nica inv�lida!');" 
          ShowHTML "  history.back(1);" 
          ScriptClose 
       End If 
    Case "GDCONC"
       ' Verifica se a Assinatura Eletr�nica � v�lida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then

          DB_GetSolicData RS, ul.Texts.Item("w_chave"), "GDGERAL" 
          If cDbl(RS("sq_siw_tramite")) <> cDbl(ul.Texts.Item("w_tramite")) Then
             ScriptOpen "JavaScript"
             ShowHTML "  alert('ATEN��O: Outro usu�rio j� encaminhou esta atividade para outra fase de execu��o!');"
             ScriptClose
          Else
             ' Se foi feito o upload de um arquivo  
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
                      w_file    = nvl(ul.Texts.Item("w_atual"),replace(FS.GetTempName(),".tmp",Mid(Field.FileName,Instr(Field.FileName,"."),30)))
                      w_tamanho = Field.Length
                      w_tipo    = Field.ContentType
                      w_nome    = Field.FileName
                      Field.SaveAs conFilePhysical & w_cliente & "\" & w_file
                   End If
                Next
                DML_PutDemandaConc w_menu, ul.Texts.Item("w_chave"), w_usuario, ul.Texts.Item("w_tramite"), ul.Texts.Item("w_inicio_real"), ul.Texts.Item("w_fim_real"), ul.Texts.Item("w_nota_conclusao"), ul.Texts.Item("w_custo_real"), _  
                    w_file, w_tamanho, w_tipo, w_nome

                ' Envia e-mail comunicando a conclus�o
                SolicMail ul.Texts.Item"w_chave") ,3
                                 
             Else
                ScriptOpen "JavaScript" 
                ShowHTML "  alert('ATEN��O: ocorreu um erro na transfer�ncia do arquivo. Tente novamente!');" 
                ScriptClose 
             End If
                 
             ScriptOpen "JavaScript"
             ' Volta para a listagem
             DB_GetMenuData RS, w_menu
             ShowHTML "  location.href='" & replace(RS("link"),w_dir,"") & "&O=L&w_chave=" & ul.Texts.Item("w_chave") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & rs("sigla") & MontaFiltro("UPLOAD") & "';" 
             DesconectaBD
             ScriptClose
          End If
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletr�nica inv�lida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case Else
       ScriptOpen "JavaScript"
       ShowHTML "  alert('Bloco de dados n�o encontrado: " & SG & "');"
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
    ShowHTML " top.location.href='../Default.asp'; "
    ScriptClose
   Exit Sub
  End If

  Select Case Par
    Case "INICIAL"  Inicial
    Case "GERAL"    Geral
    Case "ANEXO"    Anexos
    Case "INTERESS" Interessados
    Case "AREAS"    Areas
    Case "VISUAL"   Visual
    Case "EXCLUIR"  Excluir
    Case "ENVIO"    Encaminhamento
    Case "TRAMITE"  Tramitacao
    Case "ANOTACAO" Anotar
    Case "CONCLUIR" Concluir
    Case "GRAVA"    Grava
    Case Else
       Cabecalho
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