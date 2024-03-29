<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Geral.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_EO.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Gerencial.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Cliente.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Seguranca.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Link.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/VisualProjeto.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /GR_Projeto.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papad�polis
REM Descricao: Gerencia o m�dulo de projetos
REM Mail     : alex@sbpi.com.br
REM Criacao  : 15/10/2003 12:25
REM Versao   : 1.0.0.0
REM Local    : Bras�lia - DF
REM -------------------------------------------------------------------------
REM
REM Par�metros recebidos:
REM    R (refer�ncia) = usado na rotina de grava��o, com conte�do igual ao par�metro T
REM    O (opera��o)   = L   : Listagem
REM                   = P   : Filtragem
REM                   = V   : Gera��o de gr�fico
REM                   = W   : Gera��o de documento no formato MS-Word (Office 2003)

' Verifica se o usu�rio est� autenticado
If Session("LogOn") <> "Sim" Then
   EncerraSessao
End If

' Declara��o de vari�veis
Dim dbms, sp, RS, RS1, RS2, RS_menu, w_ano
Dim P1, P2, P3, P4, TP, SG, FS, w_file
Dim R, O, w_Cont, w_Reg, w_Pagina, w_Disabled, w_TP, w_classe, w_submenu
Dim w_Assinatura
Dim p_chave,p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, p_uorg_resp, p_palavra
Dim p_prazo, p_fase, p_sqcc, p_agrega, p_tamanho, p_projeto, p_atividade, p_ini_i
Dim p_ini_f, p_fim_i, p_fim_f, p_atraso, p_unidade, p_prioridade, p_solicitante, p_ativo, p_tipo
Dim p_proponente, p_ordena
Set RS = Server.CreateObject("ADODB.RecordSet")
Set RS1 = Server.CreateObject("ADODB.RecordSet")
Set RS2 = Server.CreateObject("ADODB.RecordSet")
Set RS_menu = Server.CreateObject("ADODB.RecordSet")
 


Dim w_troca,w_cor, w_filter, w_cliente, w_usuario, w_menu
Dim w_sq_pessoa, w_pag, w_linha, w_nm_quebra, w_qt_quebra, w_filtro
Dim ul,File
Dim t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_acima, t_custo
Dim t_totsolic, t_totcad, t_tottram, t_totconc, t_totatraso, t_totaviso, t_totvalor, t_totacima, t_totcusto
Dim w_dir_volta, w_dir

w_troca                 = Request("w_troca")

p_tipo                  = uCase(Request("p_tipo"))
p_ativo                 = uCase(Request("p_ativo"))
p_solicitante           = uCase(Request("p_solicitante"))
p_unidade               = uCase(Request("p_unidade"))
p_proponente            = uCase(Request("p_proponente"))
p_ordena                = uCase(Request("p_ordena"))
p_ini_i                 = uCase(Request("p_ini_i"))
p_ini_f                 = uCase(Request("p_ini_f"))
p_fim_i                 = uCase(Request("p_fim_i"))
p_fim_f                 = uCase(Request("p_fim_f"))
p_atraso                = uCase(Request("p_atraso"))
p_chave                 = uCase(Request("p_chave"))
p_assunto               = uCase(Request("p_assunto"))
p_usu_resp              = uCase(Request("p_usu_resp"))
p_uorg_resp             = uCase(Request("p_uorg_resp"))
p_palavra               = uCase(Request("p_palavra"))
p_prazo                 = uCase(Request("p_prazo"))
p_fase                  = uCase(Request("p_fase"))
p_agrega                = uCase(Request("p_agrega"))
p_tamanho               = uCase(Request("p_tamanho"))
p_sqcc                  = uCase(Request("p_sqcc"))
p_projeto               = uCase(Request("p_projeto"))
p_atividade             = uCase(Request("p_atividade"))
p_pais                  = uCase(Request("p_pais"))
p_regiao                = uCase(Request("p_regiao"))
p_uf                    = uCase(Request("p_uf"))
p_cidade                = uCase(Request("p_cidade"))
p_prioridade            = uCase(Request("p_prioridade"))


Private Par

AbreSessao

' Carrega vari�veis locais com os dados dos par�metros recebidos
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

w_Pagina     = "GR_Projeto.asp?par="
w_Dir        = "cl_cespe/"
w_dir_volta  = "../"  
w_Disabled   = "ENABLED"

If O = "" Then O = "P" End If

Select Case O
  Case "V" 
     w_TP = TP & " - Gr�fico"
  Case "P" 
     w_TP = TP & " - Filtragem"
  Case Else
     w_TP = TP & " - Listagem"
End Select

w_cliente         = RetornaCliente()
w_usuario         = RetornaUsuario()
w_menu            = P2

' Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
DB_GetLinkSubMenu RS, Session("p_cliente"), SG
If RS.RecordCount > 0 Then
   w_submenu = "Existe"
Else
   w_submenu = ""
End If
DesconectaBD

' Recupera a configura��o do servi�o
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
Set p_tamanho     = Nothing
Set p_projeto     = Nothing
Set p_atividade   = Nothing

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
Set w_dir         = Nothing
Set w_dir_volta   = Nothing
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
     If p_sqcc > ""  Then 
        DB_GetCCData RS, p_sqcc
        w_filtro = w_filtro & "<tr valign=""top""><td align=""right"">Classifica��o <td>[<b>" & RS("nome") & "</b>]"
     End If
     If p_chave       > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right"">Projeto n� <td>[<b>" & p_chave & "</b>]" End If
     If p_prazo       > ""  Then w_filtro = w_filtro & " <tr valign=""top""><td align=""right"">Prazo para conclus�o at�<td>[<b>" & FormatDateTime(DateAdd("d",p_prazo,Date()),1) & "</b>]" End If
     If p_solicitante > ""  Then
        DB_GetPersonData RS, w_cliente, p_solicitante, null, null
        w_filtro = w_filtro & "<tr valign=""top""><td align=""right"">Gerente <td>[<b>" & RS("nome_resumido") & "</b>]"
     End If
     If p_unidade     > ""  Then 
        DB_GetUorgData RS, p_unidade
        w_filtro = w_filtro & "<tr valign=""top""><td align=""right"">Unidade respons�vel <td>[<b>" & RS("nome") & "</b>]"
     End If
     If p_usu_resp > ""  Then
        DB_GetPersonData RS, w_cliente, p_usu_resp, null, null
        w_filtro = w_filtro & "<tr valign=""top""><td align=""right"">Executor <td>[<b>" & RS("nome_resumido") & "</b>]"
     End If
     If p_pais > ""  Then 
        DB_GetCountryData RS, p_pais
        w_filtro = w_filtro & "<tr valign=""top""><td align=""right"">Pa�s <td>[<b>" & RS("nome") & "</b>]"
     End If
     If p_regiao > ""  Then 
        DB_GetRegionData RS, p_regiao
        w_filtro = w_filtro & "<tr valign=""top""><td align=""right"">Regi�o <td>[<b>" & RS("nome") & "</b>]"
     End If
     If p_uf > ""  Then 
        DB_GetStateData RS, p_pais, p_uf
        w_filtro = w_filtro & "<tr valign=""top""><td align=""right"">Estado <td>[<b>" & RS("nome") & "</b>]"
     End If
     If p_cidade > ""  Then 
        DB_GetCityData RS, p_cidade
        w_filtro = w_filtro & "<tr valign=""top""><td align=""right"">Cidade <td>[<b>" & RS("nome") & "</b>]"
     End If
     If p_prioridade  > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right"">Prioridade <td>[<b>" & RetornaPrioridade(p_prioridade) & "</b>]"   End If
     If p_proponente  > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right"">Proponente <td>[<b>" & p_proponente & "</b>]"                      End If
     If p_assunto     > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right"">T�tulo <td>[<b>" & p_assunto & "</b>]"                            End If
     If p_palavra     > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right"">N� do Pronac <td>[<b>" & p_palavra & "</b>]"                     End If
     If p_ini_i       > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right"">Data recebimento <td>[<b>" & p_ini_i & "-" & p_ini_f & "</b>]"     End If
     If p_fim_i       > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right"">Limite conclus�o <td>[<b>" & p_fim_i & "-" & p_fim_f & "</b>]"     End If
     If p_atraso      = "S" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right"">Situa��o <td>[<b>Apenas atrasadas</b>]"                            End If
     If w_filtro > "" Then w_filtro = "<table border=0><tr valign=""top""><td><b>Filtro:</b><td nowrap><ul>" & w_filtro & "</ul></tr></table>"                    End If

     Select case p_agrega
        Case "GRPRPROJ"
           DB_GetSolicList RS1, P2, w_usuario, p_agrega, 4, _
                p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
                p_unidade, p_prioridade, p_ativo, p_proponente, _
                p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
                p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, p_projeto, p_atividade, null, null
           w_TP = TP & " - Por projeto"
           RS1.sort = "titulo"
        Case "GRPRPROP"
           DB_GetSolicList RS1, P2, w_usuario, p_agrega, 4, _
                p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
                p_unidade, p_prioridade, p_ativo, p_proponente, _
                p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
                p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, p_projeto, p_atividade, null, null
           w_TP = TP & " - Por proponente"
           RS1.Filter = "nm_prop_res <> null"
           RS1.sort = "nm_prop_res"
        Case "GRPRRESP"
           DB_GetSolicList RS1, P2, w_usuario, p_agrega, 4, _
                p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
                p_unidade, p_prioridade, p_ativo, p_proponente, _
                p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
                p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, null, null, null, null
           w_TP = TP & " - Por Gerente"
           RS1.sort = "nm_solic"
        Case "GRPRRESPATU"
           DB_GetSolicList RS1, P2, w_usuario, p_agrega, 4, _
                p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
                p_unidade, p_prioridade, p_ativo, p_proponente, _
                p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
                p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, null, null, null, null
           w_TP = TP & " - Por executor"
           RS1.Filter = "executor <> null"
           RS1.sort = "nm_exec"
        Case "GRPRCC"
           DB_GetSolicList RS1, P2, w_usuario, p_agrega, 4, _
                p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
                p_unidade, p_prioridade, p_ativo, p_proponente, _
                p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
                p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, null, null, null, null
           w_TP = TP & " - Por classifica��o"
           RS1.sort = "sg_cc"
        Case "GRPRSETOR"
           w_TP = TP & " - Por unidade respons�vel"
           DB_GetSolicList RS1, P2, w_usuario, p_agrega, 4, _
                p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
                p_unidade, p_prioridade, p_ativo, p_proponente, _
                p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
                p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, null, null, null, null
           RS1.sort = "nm_unidade_resp"
        Case "GRPRPRIO" 
           w_TP = TP & " - Por prioridade"
           DB_GetSolicList RS1, P2, w_usuario, p_agrega, 4, _
                p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
                p_unidade, p_prioridade, p_ativo, p_proponente, _
                p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
                p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, null, null, null, null
           RS1.sort = "nm_prioridade"
        Case "GRPRLOCAL" 
           w_TP = TP & " - Por UF"
           DB_GetSolicList RS1, P2, w_usuario, p_agrega, 4, _
                p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
                p_unidade, p_prioridade, p_ativo, p_proponente, _
                p_chave, p_assunto, p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, _
                p_uorg_resp, p_palavra, p_prazo, p_fase, p_sqcc, null, null, null, null
           RS1.sort = "co_uf"
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
     ShowHTML "<TITLE>" & conSgSistema & " - Listagem de projetos</TITLE>"
     If P1 = 2 Then ShowHTML "<meta http-equiv=""Refresh"" content=""30; URL=" & replace(MontaURL("MESA"),w_dir,"") & """>" End If
     Estrutura_CSS w_cliente

     If O = "P" Then
        ScriptOpen "Javascript"
        CheckBranco
        FormataData
        ValidateOpen "Validacao"
        Validate "p_chave", "N�mero do projeto", "", "", "1", "18", "", "0123456789"
        Validate "p_prazo", "Dias para a data limite", "", "", "1", "2", "", "0123456789"
        Validate "p_proponente", "Proponente externo", "", "", "2", "90", "1", ""
        Validate "p_assunto", "T�tulo", "", "", "2", "90", "1", "1"
        Validate "p_palavra", "N� do Pronac", "", "", "2", "20", "1", "1"
        Validate "p_ini_i", "In�cio", "DATA", "", "10", "10", "", "0123456789/"
        Validate "p_ini_f", "Fim", "DATA", "", "10", "10", "", "0123456789/"
        ShowHTML "  if ((theForm.p_ini_i.value != '' && theForm.p_ini_f.value == '') || (theForm.p_ini_i.value == '' && theForm.p_ini_f.value != '')) {"
        ShowHTML "     alert ('Informe ambas as datas de in�cio!');"
        ShowHTML "     theForm.p_ini_i.focus();"
        ShowHTML "     return false;"
        ShowHTML "  }"
        CompData "p_ini_i", "In�cio de", "<=", "p_ini_f", "In�cio at�"
        Validate "p_fim_i", "Fim", "DATA", "", "10", "10", "", "0123456789/"
        Validate "p_fim_f", "Fim de", "DATA", "", "10", "10", "", "0123456789/"
        ShowHTML "  if ((theForm.p_fim_i.value != '' && theForm.p_fim_f.value == '') || (theForm.p_fim_i.value == '' && theForm.p_fim_f.value != '')) {"
        ShowHTML "     alert ('Informe ambas as datas finais!');"
        ShowHTML "     theForm.p_fim_i.focus();"
        ShowHTML "     return false;"
        ShowHTML "  }"
        CompData "p_fim_i", "Fim de", "<=", "p_fim_f", "Fim at�"
        ValidateClose
        ScriptClose
     Else
        ShowHTML "<TITLE>" & w_TP & "</TITLE>"
     End If
     ShowHTML "</HEAD>"
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
      If w_Troca > "" Then ' Se for recarga da p�gina
        BodyOpen "onLoad=""document.Form." & w_Troca & ".focus();"""
     ElseIf InStr("P",O) > 0 Then
        If P1 = 1 Then ' Se for cadastramento
           BodyOpen "onLoad=""document.Form.p_ordena.focus();"""
        Else
           BodyOpen "onLoad=""document.Form.p_agrega.focus();"""
        End if
     Else
        BodyOpenClean "onLoad=""document.focus();"""
     End If
     If O = "L" Then
        ShowHTML "<center>"
        Estrutura_Topo_Limpo
        Estrutura_Menu
        Estrutura_Corpo_Abre
        Estrutura_Texto_Abre
  
        
        If w_filtro > "" Then ShowHTML w_filtro End If
     Else
        ShowHTML "<center>"
        Estrutura_Topo_Limpo
        Estrutura_Menu
        Estrutura_Corpo_Abre
        Estrutura_Texto_Abre
  
        
     End If
  End If

  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" or O = "W" Then
    If O = "L" Then
       ShowHTML "<tr><td>"
       If MontaFiltro("GET") > "" Then
          ShowHTML "                         <a accesskey=""F"" class=""ss"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a>"
        Else
          ShowHTML "                         <a accesskey=""F"" class=""ss"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>F</u>iltrar (Inativo)</a>"
       End If
    End IF
    ImprimeCabecalho
    If RS1.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=10 align=""center""><b>N�o foram encontrados registros.</b></td></tr>"
    Else
      If O = "L" Then
         ShowHTML "<SCRIPT LANGUAGE=""JAVASCRIPT"">"
         ShowHTML "  function lista (filtro, cad, exec, conc, atraso) {"
         ShowHTML "    if (filtro != -1) {"
         Select case p_agrega
            Case "GRPRPROJ"    ShowHTML "      document.Form.p_projeto.value=filtro;"
            Case "GRPRPROP"    ShowHTML "      document.Form.p_proponente.value=filtro;"
            Case "GRPRRESP"    ShowHTML "      document.Form.p_solicitante.value=filtro;"
            Case "GRPRRESPATU" ShowHTML "      document.Form.p_usu_resp.value=filtro;"
            Case "GRPRCC"      ShowHTML "      document.Form.p_sqcc.value=filtro;"
            Case "GRPRSETOR"   ShowHTML "      document.Form.p_unidade.value=filtro;"
            Case "GRPRPRIO"    ShowHTML "      document.Form.p_prioridade.value=filtro;"
            Case "GRPRLOCAL"   ShowHTML "      document.Form.p_uf.value=filtro;"
         End Select
         ShowHTML "    }"
         Select case p_agrega
            Case "GRPRPROJ"    ShowHTML "    else document.Form.p_projeto.value='" & Request("p_projeto")& "';"
            Case "GRPRPROP"    ShowHTML "    else document.Form.p_proponente.value=""" & Request("p_proponente")& """;"
            Case "GRPRRESP"    ShowHTML "    else document.Form.p_solicitante.value='" & Request("p_solicitante")& "';"
            Case "GRPRRESPATU" ShowHTML "    else document.Form.p_usu_resp.value='" & Request("p_usu_resp")& "';"
            Case "GRPRCC"      ShowHTML "    else document.Form.p_sqcc.value='" & Request("p_sqcc")& "';"
            Case "GRPRSETOR"   ShowHTML "    else document.Form.p_unidade.value='" & Request("p_unidade")& "';"
            Case "GRPRPRIO"    ShowHTML "    else document.Form.p_prioridade.value='" & Request("p_prioridade")& "';"
            Case "GRPRLOCAL"   ShowHTML "    else document.Form.p_uf.value='" & Request("p_uf")& "';"
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
            Case "GRPRPROJ"    If Request("p_projeto") = ""     Then ShowHTML "<input type=""Hidden"" name=""p_projeto"" value="""">"       End If
            Case "GRPRPROP"    If Request("p_proponente") = ""  Then ShowHTML "<input type=""Hidden"" name=""p_proponente"" value="""">"    End If
            Case "GRPRRESP"    If Request("p_solicitante") = "" Then ShowHTML "<input type=""Hidden"" name=""p_solicitante"" value="""">"   End If  
            Case "GRPRRESPATU" If Request("p_usu_resp") = ""    Then ShowHTML "<input type=""Hidden"" name=""p_usu_resp"" value="""">"      End If
            Case "GRPRCC"      If Request("p_sqcc") = ""        Then ShowHTML "<input type=""Hidden"" name=""p_sqcc"" value="""">"          End If
            Case "GRPRSETOR"   If Request("p_unidade") = ""     Then ShowHTML "<input type=""Hidden"" name=""p_unidade"" value="""">"       End If
            Case "GRPRPRIO"    If Request("p_prioridade") = ""  Then ShowHTML "<input type=""Hidden"" name=""p_prioridade"" value="""">"    End If
            Case "GRPRLOCAL"   If Request("p_uf") = ""          Then ShowHTML "<input type=""Hidden"" name=""p_uf"" value="""">"            End If
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
           Case "GRPRPROJ"
              If w_nm_quebra <> RS1("titulo") Then
                 If w_qt_quebra > 0 Then
                    ImprimeLinha t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_custo, t_acima, w_chave
                    w_linha = w_linha + 2
                 End If
                 If O <> "W" or (O = "W" and w_linha <= 25) Then
                    ' Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><b>" & RS1("titulo")
                 End If
                 w_nm_quebra       = RS1("titulo")
                 w_chave           = RS1("sq_siw_solicitacao")
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
           Case "GRPRPROP"
              If w_nm_quebra <> RS1("nm_prop_res") Then
                 If w_qt_quebra > 0 Then
                    ImprimeLinha t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_custo, t_acima, w_chave
                    w_linha = w_linha + 2
                 End If
                 If O <> "W" or (O = "W" and w_linha <= 25) Then
                    ' Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><b>" & RS1("nm_prop_res")
                 End If
                 w_nm_quebra       = RS1("nm_prop_res")
                 w_chave           = RS1("nm_prop_res")
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
           Case "GRPRRESP"
              If w_nm_quebra <> RS1("nm_solic") Then
                 If w_qt_quebra > 0 Then
                    ImprimeLinha t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_custo, t_acima, w_chave
                    w_linha = w_linha + 2
                 End If
                 If O <> "W" or (O = "W" and w_linha <= 25) Then
                    ' Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><b>" & RS1("nm_solic")
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
           Case "GRPRRESPATU"
              If w_nm_quebra <> RS1("nm_exec") Then
                 If w_qt_quebra > 0 Then
                    ImprimeLinha t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_custo, t_acima, w_chave
                    w_linha = w_linha + 2
                 End If
                 If O <> "W" or (O = "W" and w_linha <= 25) Then
                    ' Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><b>" & RS1("nm_exec")
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
           Case "GRPRCC"
              If w_nm_quebra <> RS1("sg_cc") Then
                 If w_qt_quebra > 0 Then
                    ImprimeLinha t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_custo, t_acima, w_chave
                    w_linha = w_linha + 2
                 End If
                 If O <> "W" or (O = "W" and w_linha <= 25) Then
                    ' Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><b>" & RS1("sg_cc")
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
           Case "GRPRSETOR"
              If w_nm_quebra <> RS1("nm_unidade_resp") Then
                 If w_qt_quebra > 0 Then
                    ImprimeLinha t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_custo, t_acima, w_chave
                    w_linha = w_linha + 2
                 End If
                 If O <> "W" or (O = "W" and w_linha <= 25) Then
                    ' Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><b>" & RS1("nm_unidade_resp")
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
           Case "GRPRPRIO"
              If w_nm_quebra <> RS1("nm_prioridade") Then
                 If w_qt_quebra > 0 Then 
                    ImprimeLinha t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_custo, t_acima, w_chave
                    w_linha = w_linha + 2
                 End If
                 If O <> "W" or (O = "W" and w_linha <= 25) Then
                    ' Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><b>" & RS1("nm_prioridade")
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
           Case "GRPRLOCAL"
              If w_nm_quebra <> RS1("co_uf") Then
                 If w_qt_quebra > 0 Then
                    ImprimeLinha t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_custo, t_acima, w_chave
                    w_linha = w_linha + 2
                 End If
                 If O <> "W" or (O = "W" and w_linha <= 25) Then
                    ' Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><b>" & RS1("co_uf")
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
        End Select
        If O = "W" and w_linha > 25 Then ' Se for gera��o de MS-Word, quebra a p�gina
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
              Case "GRPRPROJ"    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><b>" & RS1("titulo")
              Case "GRPRPROP"    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><b>" & RS1("nm_prop_res")
              Case "GRPRRESP"    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><b>" & RS1("nm_solic")
              Case "GRPRRESPATU" ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><b>" & RS1("nm_exec")
              Case "GRPRCC"      ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><b>" & RS1("sg_cc")
              Case "GRPRSETOR"   ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><b>" & RS1("nm_unidade_resp")
              Case "GRPRPRIO"    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><b>" & RS1("nm_prioridade")
              Case "GRPRLOCAL"   ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><b>" & RS1("co_uf")
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
      ShowHTML "          <td><b>Totais</font></td>"
      ImprimeLinha t_totsolic, t_totcad, t_tottram, t_totconc, t_totatraso, t_totaviso, t_totvalor, t_totcusto, t_totacima, -1
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    
    If RS1.RecordCount > 0 and p_tipo = "N" Then ' Coloca o gr�fico somente se o usu�rio desejar
       ShowHTML "<tr><td align=""center"" height=20>"
       ShowHTML "<tr><td align=""center""><IMG SRC=""" & conPHP4 & "geragrafico.php?p_genero=M&p_objeto=" & RS_Menu("nome") & "&p_tipo="&SG&"&p_grafico=Barra&p_tot="&t_totsolic&"&p_cad="&t_totcad&"&p_tram="&t_tottram&"&p_conc="&t_totconc&"&p_atraso="&t_totatraso&"&p_aviso="&t_totaviso&"&p_acima="&t_totacima&""">"
       ShowHTML "<tr><td align=""center"" height=20>"
       If (t_totcad + t_tottram) > 0 Then
          ShowHTML "<tr><td align=""center""><IMG SRC=""" & conPHP4 & "geragrafico.php?p_genero=M&p_objeto=" & RS_Menu("nome") & "&p_tipo="&SG&"&p_grafico=Pizza&p_tot="&t_totsolic&"&p_cad="&t_totcad&"&p_tram="&t_tottram&"&p_conc="&t_totconc&"&p_atraso="&t_totatraso&"&p_aviso="&t_totaviso&"&p_acima="&t_totacima&""">"
       End If
    End If
    
  ElseIf Instr("P",O) > 0 Then
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify"">Informe nos campos abaixo os valores que deseja filtrar e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td valign=""top""><table border=0 width=""100%"" cellspacing=0>"
    AbreForm "Form", w_dir & w_Pagina & par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"

    ' Exibe par�metros de apresenta��o
    ShowHTML "         <tr><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><b>Par�metros de Apresenta��o</td>"
    ShowHTML "         <tr valign=""top""><td colspan=2><table border=0 width=""100%"" cellpadding=0 cellspacing=0><tr valign=""top"">"
    ShowHTML "          <td><b><U>A</U>gregar por:<br><SELECT ACCESSKEY=""O"" " & w_Disabled & " class=""sts"" name=""p_agrega"">"
    If RS_menu("solicita_cc") = "S" Then
       Select case p_agrega
          Case "GRPRCC"      ShowHTML "          <option value=""GRPRCC"" selected>Classifica��o<option value=""GRPRRESPATU"">Executor<option value=""GRPRPRIO"">Prioridade<option value=""GRPRPROJ"">Projeto<option value=""GRPRPROP"">Proponente<option value=""GRPRRESP"">Gerente<option value=""GRPRSETOR"">Unidade respons�vel<option value=""GRPRLOCAL"">UF"
          Case "GRPRPRIO"    ShowHTML "          <option value=""GRPRCC"">Classifica��o<option value=""GRPRRESPATU"">Executor<option value=""GRPRPRIO"" selected>Prioridade<option value=""GRPRPROJ"">Projeto<option value=""GRPRPROP"">Proponente<option value=""GRPRRESP"">Gerente<option value=""GRPRSETOR"">Unidade respons�vel<option value=""GRPRLOCAL"">UF"
          Case "GRPRPROJ"    ShowHTML "          <option value=""GRPRCC"">Classifica��o<option value=""GRPRRESPATU"">Executor<option value=""GRPRPRIO"">Prioridade<option value=""GRPRPROJ"" selected>Projeto<option value=""GRPRPROP"">Proponente<option value=""GRPRRESP"">Gerente<option value=""GRPRSETOR"">Unidade respons�vel<option value=""GRPRLOCAL"">UF"
          Case "GRPRPROP"    ShowHTML "          <option value=""GRPRCC"">Classifica��o<option value=""GRPRRESPATU"">Executor<option value=""GRPRPRIO"">Prioridade<option value=""GRPRPROJ"">Projeto<option value=""GRPRPROP"" selected>Proponente<option value=""GRPRRESP"">Gerente<option value=""GRPRSETOR"">Unidade respons�vel<option value=""GRPRLOCAL"">UF"
          Case "GRPRRESPATU" ShowHTML "          <option value=""GRPRCC"">Classifica��o<option value=""GRPRRESPATU"" selected>Executor<option value=""GRPRPRIO"">Prioridade<option value=""GRPRPROJ"">Projeto<option value=""GRPRPROP"">Proponente<option value=""GRPRRESP"">Gerente<option value=""GRPRSETOR"">Unidade respons�vel<option value=""GRPRLOCAL"">UF"
          Case "GRPRSETOR"   ShowHTML "          <option value=""GRPRCC"">Classifica��o<option value=""GRPRRESPATU"">Executor<option value=""GRPRPRIO"">Prioridade<option value=""GRPRPROJ"">Projeto<option value=""GRPRPROP"">Proponente<option value=""GRPRRESP"">Gerente<option value=""GRPRSETOR"" selected>Unidade respons�vel<option value=""GRPRLOCAL"">UF"
          Case "GRPRLOCAL"   ShowHTML "          <option value=""GRPRCC"">Classifica��o<option value=""GRPRRESPATU"">Executor<option value=""GRPRPRIO"">Prioridade<option value=""GRPRPROJ"">Projeto<option value=""GRPRPROP"">Proponente<option value=""GRPRRESP"">Gerente<option value=""GRPRSETOR"">Unidade respons�vel<option value=""GRPRLOCAL"" selected>UF"
          Case Else          ShowHTML "          <option value=""GRPRCC"">Classifica��o<option value=""GRPRRESPATU"">Executor<option value=""GRPRPRIO"">Prioridade<option value=""GRPRPROJ"">Projeto<option value=""GRPRPROP"">Proponente<option value=""GRPRRESP"" selected>Gerente<option value=""GRPRSETOR"">Unidade respons�vel<option value=""GRPRLOCAL"">UF"
       End Select
    Else
       Select case p_agrega
          Case "GRPRCC"      ShowHTML "          <option value=""GRPRRESPATU"">Executor<option value=""GRPRPRIO"">Prioridade<option value=""GRPRPROJ"">Projeto<option value=""GRPRPROP"">Proponente<option value=""GRPRRESP"">Gerente<option value=""GRPRSETOR"">Unidade respons�vel<option value=""GRPRLOCAL"">UF"
          Case "GRPRPRIO"    ShowHTML "          <option value=""GRPRRESPATU"">Executor<option value=""GRPRPRIO"" selected>Prioridade<option value=""GRPRPROJ"">Projeto<option value=""GRPRPROP"">Proponente<option value=""GRPRRESP"">Gerente<option value=""GRPRSETOR"">Unidade respons�vel<option value=""GRPRLOCAL"">UF"
          Case "GRPRPROJ"    ShowHTML "          <option value=""GRPRRESPATU"">Executor<option value=""GRPRPRIO"">Prioridade<option value=""GRPRPROJ"" selected>Projeto<option value=""GRPRPROP"">Proponente<option value=""GRPRRESP"">Gerente<option value=""GRPRSETOR"">Unidade respons�vel<option value=""GRPRLOCAL"">UF"
          Case "GRPRPROP"    ShowHTML "          <option value=""GRPRRESPATU"">Executor<option value=""GRPRPRIO"">Prioridade<option value=""GRPRPROJ"">Projeto<option value=""GRPRPROP"" selected>Proponente<option value=""GRPRRESP"">Gerente<option value=""GRPRSETOR"">Unidade respons�vel<option value=""GRPRLOCAL"">UF"
          Case "GRPRRESPATU" ShowHTML "          <option value=""GRPRRESPATU"" selected>Executor<option value=""GRPRPRIO"">Prioridade<option value=""GRPRPROJ"">Projeto<option value=""GRPRPROP"">Proponente<option value=""GRPRRESP"">Gerente<option value=""GRPRSETOR"">Unidade respons�vel<option value=""GRPRLOCAL"">UF"
          Case "GRPRSETOR"   ShowHTML "          <option value=""GRPRRESPATU"">Executor<option value=""GRPRPRIO"">Prioridade<option value=""GRPRPROJ"">Projeto<option value=""GRPRPROP"">Proponente<option value=""GRPRRESP"">Gerente<option value=""GRPRSETOR"" selected>Unidade respons�vel<option value=""GRPRLOCAL"">UF"
          Case "GRPRLOCAL"   ShowHTML "          <option value=""GRPRRESPATU"">Executor<option value=""GRPRPRIO"">Prioridade<option value=""GRPRPROJ"">Projeto<option value=""GRPRPROP"">Proponente<option value=""GRPRRESP"">Gerente<option value=""GRPRSETOR"">Unidade respons�vel<option value=""GRPRLOCAL"" selected>UF"
          Case Else          ShowHTML "          <option value=""GRPRRESPATU"">Executor<option value=""GRPRPRIO"">Prioridade<option value=""GRPRPROJ"">Projeto<option value=""GRPRPROP"">Proponente<option value=""GRPRRESP"" selected>Gerente<option value=""GRPRSETOR"">Unidade respons�vel<option value=""GRPRLOCAL"">UF"
       End Select
    End If
    ShowHTML "          </select></td>"
    MontaRadioNS "<b>Inibe exibi��o do gr�fico?</b>", p_tipo, "p_tipo"
    MontaRadioSN "<b>Limita tamanho do t�tulo?</b>", p_tamanho, "p_tamanho"
    ShowHTML "           </table>"
    ShowHTML "         </tr>"
    ShowHTML "         <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><b>Crit�rios de Busca</td>"

    If RS_menu("solicita_cc") = "S" Then
       ShowHTML "      <tr><td colspan=2><table border=0 width=""90%"" cellspacing=0><tr valign=""top"">"
       SelecaoCC "C<u>l</u>assifica��o:", "L", "Selecione um dos itens relacionados.", p_sqcc, null, "p_sqcc", "SIWSOLIC"
       ShowHTML "          </table>"
    End If
    ShowHTML "      <tr valign=""top"">"
    ShowHTML "          <td valign=""top""><b>N<u>�</u>mero do projeto:<br><INPUT ACCESSKEY=""U"" " & w_Disabled & " class=""sti"" type=""text"" name=""p_chave"" size=""18"" maxlength=""18"" value=""" & p_chave & """></td>"
    ShowHTML "          <td valign=""top""><b><u>D</u>ias para a data limite:<br><INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""sti"" type=""text"" name=""p_prazo"" size=""2"" maxlength=""2"" value=""" & p_prazo & """></td>"
    ShowHTML "      <tr valign=""top"">"
    SelecaoPessoa "Gere<u>n</u>te:", "N", "Selecione o gerente do projeto na rela��o.", p_solicitante, null, "p_solicitante", "Gerente"
    'SelecaoUnidade "<U>S</U>etor respons�vel:", "S", null, p_unidade, null, "p_unidade", null, null
    'ShowHTML "      <tr valign=""top"">"
    SelecaoPessoa "E<u>x</u>ecutor:", "X", "Selecione o executor do projeto na rela��o.", p_usu_resp, null, "p_usu_resp", "USUARIOS"
    'SelecaoUnidade "<U>S</U>etor executor:", "S", "Selecione a unidade onde o projeto se encontra na rela��o.", p_uorg_resp, null, "p_uorg_resp", null, null
    'ShowHTML "      <tr>"
    'SelecaoPais "<u>P</u>a�s:", "P", null, p_pais, null, "p_pais", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.target=''; document.Form.w_troca.value='p_regiao'; document.Form.submit();"""
    'SelecaoRegiao "<u>R</u>egi�o:", "R", null, p_regiao, p_pais, "p_regiao", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.target=''; document.Form.w_troca.value='p_uf'; document.Form.submit();"""
    'ShowHTML "      <tr>"
    'SelecaoEstado "E<u>s</u>tado:", "S", null, p_uf, p_pais, "N", "p_uf", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.target=''; document.Form.w_troca.value='p_cidade'; document.Form.submit();"""
    'SelecaoCidade "<u>C</u>idade:", "C", null, p_cidade, p_pais, p_uf, "p_cidade", null, null
    ShowHTML "      <tr>"
    SelecaoPrioridade "<u>P</u>rioridade:", "P", "Informe a prioridade deste projeto.", p_prioridade, null, "p_prioridade", null, null
    ShowHTML "          <td valign=""top""><b>P<u>r</u>oponente:<br><INPUT ACCESSKEY=""R"" " & w_Disabled & " class=""sti"" type=""text"" name=""p_proponente"" size=""25"" maxlength=""90"" value=""" & p_proponente & """></td>"
    ShowHTML "      <tr>"
    ShowHTML "          <td valign=""top""><b><U>T</U>�tulo:<br><INPUT ACCESSKEY=""T"" " & w_Disabled & " class=""sti"" type=""text"" name=""p_assunto"" size=""25"" maxlength=""90"" value=""" & p_assunto & """></td>"
    ShowHTML "          <td valign=""top"" colspan=2><b>N� do Pron<U>a</U>c:<br><INPUT ACCESSKEY=""A"" " & w_Disabled & " class=""sti"" type=""text"" name=""p_palavra"" size=""20"" maxlength=""20"" value=""" & p_palavra & """></td>"
    ShowHTML "      <tr>"
    ShowHTML "          <td valign=""top""><b>In�<u>c</u>io do evento:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""p_ini_i"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_ini_i & """ onKeyDown=""FormataData(this,event);""> e <input " & w_Disabled & " accesskey=""C"" type=""text"" name=""p_ini_f"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_ini_f & """ onKeyDown=""FormataData(this,event);""></td>"
    ShowHTML "          <td valign=""top""><b>Fi<u>m</u> do evento:   </b><br><input " & w_Disabled & " accesskey=""M"" type=""text"" name=""p_fim_i"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_fim_i & """ onKeyDown=""FormataData(this,event);""> e <input " & w_Disabled & " accesskey=""M"" type=""text"" name=""p_fim_f"" class=""sti"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_fim_f & """ onKeyDown=""FormataData(this,event);""></td>"
    ShowHTML "      <tr>"
    ShowHTML "          <td valign=""top""><b>Exibe somente projetos em atraso?</b><br>"
    If p_atraso = "S" Then
       ShowHTML "              <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_atraso"" value=""S"" checked> Sim <br><input " & w_Disabled & " class=""str"" class=""str"" type=""radio"" name=""p_atraso"" value=""N""> N�o"
    Else
       ShowHTML "              <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_atraso"" value=""S""> Sim <br><input " & w_Disabled & " class=""str"" class=""str"" type=""radio"" name=""p_atraso"" value=""N"" checked> N�o"
    End If
    SelecaoFaseCheck "Recuperar fases:", "S", null, p_fase, P2, "p_fase", null, null
    ShowHTML "      <tr><td align=""center"" colspan=""2"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""2"">"
    ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Exibir"" onClick=""javascript:document.Form.O.value='L';"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
    ShowHTML "</table>"
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
       Case "GRPRPROJ"    ShowHTML "          <td><b>Projeto</font></td>"
       Case "GRPRPROP"    ShowHTML "          <td><b>Proponente</font></td>"
       Case "GRPRRESP"    ShowHTML "          <td><b>Gerente</font></td>"
       Case "GRPRRESPATU" ShowHTML "          <td><b>Executor</font></td>"
       Case "GRPRCC"      ShowHTML "          <td><b>Classifica��o</font></td>"
       Case "GRPRSETOR"   ShowHTML "          <td><b>Unidade respons�vel</font></td>"
       Case "GRPRPRIO"    ShowHTML "          <td><b>Prioridade</font></td>"
       Case "GRPRLOCAL"   ShowHTML "          <td><b>UF</font></td>"
       Case "GRPRAREA"    ShowHTML "          <td><b>�rea envolvida</font></td>"
       Case "GRPRINTER"   ShowHTML "          <td><b>Interessado</font></td>"
    End Select
    ShowHTML "          <td><b>Total</font></td>"
    ShowHTML "          <td><b>Cad.</font></td>"
    ShowHTML "          <td><b>Exec.</font></td>"
    ShowHTML "          <td><b>Conc.</font></td>"
    ShowHTML "          <td><b>Atraso</font></td>"
    ShowHTML "          <td><b>Aviso</font></td>"
    If Session("interno") = "S" Then
       ShowHTML "          <td><b>$ Prev.</font></td>"
       ShowHTML "          <td><b>$ Real</font></td>"
       ShowHTML "          <td><b>Real > Previsto</font></td>"
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
    If O = "L"                  Then ShowHTML "          <td align=""right""><a class=""hl"" href=""javascript:lista('" & p_chave & "', -1, -1, -1, -1);"" onMouseOver=""window.status='Exibe as demandas.'; return true"" onMouseOut=""window.status=''; return true"">" & FormatNumber(p_solic,0) & "</a>&nbsp;</font></td>"                  Else ShowHTML "          <td align=""right"">" & FormatNumber(p_solic,0) & "&nbsp;</font></td>" End If
    If p_cad > 0    and O = "L" Then ShowHTML "          <td align=""right""><a class=""hl"" href=""javascript:lista('" & p_chave & "', 0, -1, -1, -1);"" onMouseOver=""window.status='Exibe as demandas.'; return true"" onMouseOut=""window.status=''; return true"">" & FormatNumber(p_cad,0) & "</a>&nbsp;</font></td>"                     Else ShowHTML "          <td align=""right"">" & FormatNumber(p_cad,0) & "&nbsp;</font></td>"   End If
    If p_tram > 0   and O = "L" Then ShowHTML "          <td align=""right""><a class=""hl"" href=""javascript:lista('" & p_chave & "', -1, 0, -1, -1);"" onMouseOver=""window.status='Exibe as demandas.'; return true"" onMouseOut=""window.status=''; return true"">" & FormatNumber(p_tram,0) & "</a>&nbsp;</font></td>"                    Else ShowHTML "          <td align=""right"">" & FormatNumber(p_tram,0) & "&nbsp;</font></td>"  End If
    If p_conc > 0   and O = "L" Then ShowHTML "          <td align=""right""><a class=""hl"" href=""javascript:lista('" & p_chave & "', -1, -1, 0, -1);"" onMouseOver=""window.status='Exibe as demandas.'; return true"" onMouseOut=""window.status=''; return true"">" & FormatNumber(p_conc,0) & "</a>&nbsp;</font></td>"                    Else ShowHTML "          <td align=""right"">" & FormatNumber(p_conc,0) & "&nbsp;</font></td>"  End If
    If p_atraso > 0 and O = "L" Then ShowHTML "          <td align=""right""><a class=""hl"" href=""javascript:lista('" & p_chave & "', -1, -1, -1, 0);"" onMouseOver=""window.status='Exibe as demandas.'; return true"" onMouseOut=""window.status=''; return true""><font color=""red""><b>" & FormatNumber(p_atraso,0) & "</a>&nbsp;</font></td>" Else ShowHTML "          <td align=""right""><b>" & p_atraso & "&nbsp;</font></td>"             End If
    If p_aviso > 0  and O = "L" Then ShowHTML "          <td align=""right""><font color=""red""><b>" & FormatNumber(p_aviso,0) & "&nbsp;</font></td>"  Else ShowHTML "          <td align=""right""><b>" & p_aviso & "&nbsp;</font></td>"  End If
    If Session("interno") = "S" Then
       ShowHTML "          <td align=""right"">" & FormatNumber(p_valor,2) & "&nbsp;</font></td>"
       ShowHTML "          <td align=""right"">" & FormatNumber(p_custo,2) & "&nbsp;</font></td>"
       If p_acima > 0  Then ShowHTML "          <td align=""right""><font color=""red""><b>" & FormatNumber(p_acima,0) & "&nbsp;</font></td>"  Else ShowHTML "          <td align=""right""><b>" & p_acima & "&nbsp;</font></td>"  End If
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
  ' Verifica se o usu�rio tem lota��o e localiza��o
  If (len(Session("LOTACAO")&"") = 0 or len(Session("LOCALIZACAO")&"") = 0) and Session("LogOn") = "Sim" Then
    ScriptOpen "JavaScript"
    ShowHTML " alert('Voc� n�o tem lota��o ou localiza��o definida. Entre em contato com o RH!'); "
    ShowHTML " top.location.href='Default.asp'; "
    ScriptClose
   Exit Sub
  End If

  Select Case Par
    Case "GERENCIAL" Gerencial
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
  
       
       ShowHTML "<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src=""images/icone/underc.gif"" align=""center""> <b>Esta op��o est� sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>"
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