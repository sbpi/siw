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
<!-- #INCLUDE FILE="DB_Geral.asp" -->
<!-- #INCLUDE FILE="DB_Tabelas.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="VisualPrograma.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /GR_Programa.asp
REM ------------------------------------------------------------------------
REM Nome     : Celso Miguel Lago Filho
REM Descricao: Gerencia o m�dulo de programas
REM Mail     : celso@sbpi.com.br
REM Criacao  : 03/02/2005 14:00
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
Dim dbms, sp, RS, RS1, RS2, RS_menu
Dim P1, P2, P3, P4, TP, SG, FS, w_file
Dim R, O, w_Cont, w_Reg, w_Pagina, w_Disabled, w_TP, w_classe, w_submenu
Dim w_Assinatura
Dim p_ativo, p_solicitante, p_unidade, p_proponente, p_ordena, p_agrega, p_tamanho
Dim p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_tipo
Dim p_chave, p_assunto, p_usu_resp, p_uorg_resp, p_palavra, p_prazo, p_fase
Dim p_cd_programa, p_selecao_mp, p_selecao_se, p_qtd_restricao
Dim w_troca,w_cor, w_filter, w_cliente, w_usuario, w_menu
Dim w_sq_pessoa, w_pag, w_linha, w_nm_quebra, w_qt_quebra, w_filtro
Dim ul,File
Dim t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_acima, t_custo
Dim t_totsolic, t_totcad, t_tottram, t_totconc, t_totatraso, t_totaviso, t_totvalor, t_totacima, t_totcusto
Dim w_dir_volta, w_dir, w_ano
Set rs = Server.CreateObject("ADODB.RecordSet")
Set rs1 = Server.CreateObject("ADODB.RecordSet")
Set rs2 = Server.CreateObject("ADODB.RecordSet")
Set rs_menu = Server.CreateObject("ADODB.RecordSet")

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
p_cd_programa           = uCase(Request("p_cd_programa"))
p_selecao_mp            = uCase(Request("p_selecao_mp"))
p_selecao_se            = uCase(Request("p_selecao_se"))
p_qtd_restricao         = uCase(Request("p_qtd_restricao"))

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

w_Pagina     = "GR_Programa.asp?par="
w_Dir        = "mod_is/"  
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
'w_menu            = RetornaMenu(w_cliente, SG)
w_ano             = RetornaAno()

' Verifica se o documento tem sub-menu. Se tiver, agrega no HREF uma chamada para montagem do mesmo.
DB_GetLinkSubMenu RS, Session("p_cliente"), SG
If RS.RecordCount > 0 Then
   w_submenu = "Existe"
Else
   w_submenu = ""
End If
DesconectaBD

DB_GetMenuData RS_menu, w_menu

Main

FechaSessao

Set t_valor             = Nothing
Set t_acima             = Nothing
Set t_totvalor          = Nothing
Set t_totacima          = Nothing
Set t_aviso             = Nothing
Set t_solic             = Nothing
Set t_cad               = Nothing
Set t_tram              = Nothing
Set t_conc              = Nothing
Set t_atraso            = Nothing
Set w_filtro            = Nothing
Set w_qt_quebra         = Nothing
Set w_nm_quebra         = Nothing
Set w_linha             = Nothing
Set w_pag               = Nothing
Set w_menu              = Nothing
Set w_usuario           = Nothing
Set w_cliente           = Nothing
Set w_filter            = Nothing
Set w_cor               = Nothing
Set ul                  = Nothing
Set File                = Nothing
Set w_sq_pessoa         = Nothing
Set w_troca             = Nothing
Set w_submenu           = Nothing
Set w_reg               = Nothing
Set p_ini_i             = Nothing
Set p_ini_f             = Nothing
Set p_fim_i             = Nothing
Set p_fim_f             = Nothing
Set p_atraso            = Nothing
Set p_unidade           = Nothing
Set p_solicitante       = Nothing
Set p_ativo             = Nothing
Set p_proponente        = Nothing
Set p_tipo              = Nothing
Set p_ordena            = Nothing
Set p_chave             = Nothing 
Set p_assunto           = Nothing 
Set p_usu_resp          = Nothing 
Set p_uorg_resp         = Nothing 
Set p_palavra           = Nothing 
Set p_prazo             = Nothing 
Set p_fase              = Nothing
Set p_agrega            = Nothing
Set p_tamanho           = Nothing
Set p_cd_programa       = Nothing
Set p_selecao_mp        = Nothing
Set p_selecao_se        = Nothing
Set p_qtd_restricao     = Nothing

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
     If p_cd_programa > ""  Then 
        DB_GetProgramaPPA_IS RS, p_cd_programa, w_cliente, w_ano, null, null
        w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Programa PPA <td><font size=1>[<b>" & RS("ds_programa") & " (" & RS("cd_programa") & ")" & "</b>]"
     End If
     If p_chave       > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Programa n� <td><font size=1>[<b>" & p_chave & "</b>]" End If
     If p_prazo       > ""  Then w_filtro = w_filtro & " <tr valign=""top""><td align=""right""><font size=1>Prazo para conclus�o at�<td><font size=1>[<b>" & FormatDateTime(DateAdd("d",p_prazo,Date()),1) & "</b>]" End If
     If p_solicitante > ""  Then
        DB_GetPersonData RS, w_cliente, p_solicitante, null, null
        w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Respons�vel <td><font size=1>[<b>" & RS("nome_resumido") & "</b>]"
     End If
     If p_unidade     > ""  Then 
        DB_GetUorgData RS, p_unidade
        w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Unidade respons�vel <td><font size=1>[<b>" & RS("nome") & "</b>]"
     End If
     If p_usu_resp > ""  Then
        DB_GetPersonData RS, w_cliente, p_usu_resp, null, null
        w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Executor <td><font size=1>[<b>" & RS("nome_resumido") & "</b>]"
     End If
     If p_uorg_resp > ""  Then 
        DB_GetUorgData RS, p_uorg_resp
        w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Unidade atual <td><font size=1>[<b>" & RS("nome") & "</b>]"
     End If
     If p_selecao_mp  > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Selecionada SPI/MP <td><font size=1>[<b>" & p_selecao_se & "</b>]"              End If
     If p_selecao_se  > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Selecionada SE/SEPPIR <td><font size=1>[<b>" & p_selecao_mp & "</b>]"               End If
     If p_proponente  > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Parcerias externas<td><font size=1>[<b>" & p_proponente  & "</b>]"              End If
     If p_assunto     > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Programa <td><font size=1>[<b>" & p_assunto & "</b>]"                           End If
     If p_palavra     > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Parcerias internas<td><font size=1>[<b>" & p_palavra & "</b>]"                  End If
     If p_ini_i       > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Data recebimento <td><font size=1>[<b>" & p_ini_i & "-" & p_ini_f & "</b>]"     End If
     If p_fim_i       > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Limite conclus�o <td><font size=1>[<b>" & p_fim_i & "-" & p_fim_f & "</b>]"     End If
     If p_atraso      = "S" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Situa��o <td><font size=1>[<b>Apenas atrasadas</b>]"                            End If
     If p_qtd_restricao = "S" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Situa��o <td><font size=1>[<b>Apenas programas com restri��o</b>]"                            End If
     If w_filtro > "" Then w_filtro = "<table border=0><tr valign=""top""><td><font size=1><b>Filtro:</b><td nowrap><font size=1><ul>" & w_filtro & "</ul></tr></table>"                    End If

     DB_GetSolicList_IS RS1, P2, w_usuario, p_agrega, 3, _
        p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_solicitante, _
        p_unidade, null,  p_qtd_restricao, p_proponente, p_chave, p_assunto, _
        null, null, null, null, p_usu_resp, p_uorg_resp, p_palavra, _
        p_prazo, p_fase, null, null, null, p_cd_programa, null, null, w_ano

     Select case p_agrega
        Case "GRISPPROGRAMA"
           w_TP = TP & " - Por programa"
           RS1.sort = "titulo"
        Case "GRISPPROP"
           w_TP = TP & " - Por parcerias externas"
           RS1.sort = "proponente"
        Case "GRISPRESP"
           w_TP = TP & " - Por respons�vel"
           RS1.sort = "nm_solic"
        Case "GRISPRESPATU"
           RS1.sort = "nm_exec"
        Case "GRISPSETOR"
           w_TP = TP & " - Por setor �rea de planejamento"
           RS1.sort = "nm_unidade_resp"
     End Select
  End If
  
  If O = "W" Then
     HeaderWord null
     w_pag   = 1
     w_linha = 0
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
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
        'Validate "p_chave", "N�mero do projeto", "", "", "1", "18", "", "0123456789"
        Validate "p_prazo", "Dias para a data limite", "", "", "1", "2", "", "0123456789"
        Validate "p_proponente", "Parcerias externas", "", "", "2", "90", "1", ""
        'Validate "p_assunto", "Assunto", "", "", "2", "90", "1", "1"
        Validate "p_palavra", "Parcerias internas", "", "", "2", "90", "1", "1"
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
        ValidateClose
        ScriptClose
     Else
        ShowHTML "<TITLE>" & w_TP & "</TITLE>"
     End If
     ShowHTML "</HEAD>"
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
      If w_Troca > "" Then ' Se for recarga da p�gina
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
          ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a>"
        Else
          ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=1&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>F</u>iltrar (Inativo)</a>"
       End If
    End IF
    ImprimeCabecalho
    If RS1.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=10 align=""center""><font size=""1""><b>N�o foram encontrados registros.</b></td></tr>"
    Else
      If O = "L" Then
         ShowHTML "<SCRIPT LANGUAGE=""JAVASCRIPT"">"
         ShowHTML "  function lista (filtro, cad, exec, conc, atraso) {"
         ShowHTML "    if (filtro != -1) {"
         Select case p_agrega
            Case "GRISPPROGRAMA" ShowHTML "      document.Form.p_programa.value=filtro;"
            Case "GRISPPROP"     ShowHTML "      document.Form.p_proponente.value=filtro;"
            Case "GRISPRESP"     ShowHTML "      document.Form.p_solicitante.value=filtro;"
            Case "GRISPRESPATU"  ShowHTML "      document.Form.p_usu_resp.value=filtro;"
            Case "GRISPSETOR"    ShowHTML "      document.Form.p_unidade.value=filtro;"
         End Select
         ShowHTML "    }"
         Select case p_agrega
            Case "GRISPPROGRAMA" ShowHTML "    else document.Form.p_programa.value='" & Request("p_programa")& "';"
            Case "GRISPPROP"     ShowHTML "    else document.Form.p_proponente.value=""" & Request("p_proponente")& """;"
            Case "GRISPRESP"     ShowHTML "    else document.Form.p_solicitante.value='" & Request("p_solicitante")& "';"
            Case "GRISPRESPATU"  ShowHTML "    else document.Form.p_usu_resp.value='" & Request("p_usu_resp")& "';"
            Case "GRISPSETOR"    ShowHTML "    else document.Form.p_unidade.value='" & Request("p_unidade")& "';"
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
         ShowHTML "<BASE HREF=""" & conRootSIW & """>"
         DB_GetMenuData RS2, P2
         AbreForm "Form", RS2("link"), "POST", "return(Validacao(this));", "Lista",3,P2,RS2("P3"),null,w_TP,RS2("sigla"), w_dir&w_pagina & par,"L"
         ShowHTML MontaFiltro("POST")
         Select case p_agrega
            Case "GRISPPROGRAMA" If Request("p_programa") = ""    Then ShowHTML "<input type=""Hidden"" name=""p_programa"" value="""">"      End If
            Case "GRISPPROP"     If Request("p_proponente") = ""  Then ShowHTML "<input type=""Hidden"" name=""p_proponente"" value="""">"    End If
            Case "GRISPRESP"     If Request("p_solicitante") = "" Then ShowHTML "<input type=""Hidden"" name=""p_solicitante"" value="""">"   End If  
            Case "GRISPRESPATU"  If Request("p_usu_resp") = ""    Then ShowHTML "<input type=""Hidden"" name=""p_usu_resp"" value="""">"      End If
            Case "GRISPSETOR"    If Request("p_unidade") = ""     Then ShowHTML "<input type=""Hidden"" name=""p_unidade"" value="""">"       End If
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
           Case "GRISPPROGRAMA"
              If w_nm_quebra <> RS1("titulo") Then
                 If w_qt_quebra > 0 Then
                    ImprimeLinha t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_custo, t_acima, w_chave
                    w_linha = w_linha + 2
                 End If
                 If O <> "W" or (O = "W" and w_linha <= 25) Then
                    ' Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("titulo")
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
           Case "GRISPPROP"
              If w_nm_quebra <> RS1("proponente") Then
                 If w_qt_quebra > 0 Then
                    ImprimeLinha t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_custo, t_acima, w_chave
                    w_linha = w_linha + 2
                 End If
                 If O <> "W" or (O = "W" and w_linha <= 25) Then
                    ' Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
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
           Case "GRISPRESP"
              If w_nm_quebra <> RS1("nm_solic") Then
                 If w_qt_quebra > 0 Then
                    ImprimeLinha t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_custo, t_acima, w_chave
                    w_linha = w_linha + 2
                 End If
                 If O <> "W" or (O = "W" and w_linha <= 25) Then
                    ' Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
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
           Case "GRISPRESPATU"
              If w_nm_quebra <> RS1("nm_exec") Then
                 If w_qt_quebra > 0 Then
                    ImprimeLinha t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_custo, t_acima, w_chave
                    w_linha = w_linha + 2
                 End If
                 If O <> "W" or (O = "W" and w_linha <= 25) Then
                    ' Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
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
           Case "GRISPSETOR"
              If w_nm_quebra <> RS1("nm_unidade_resp") Then
                 If w_qt_quebra > 0 Then
                    ImprimeLinha t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_custo, t_acima, w_chave
                    w_linha = w_linha + 2
                 End If
                 If O <> "W" or (O = "W" and w_linha <= 25) Then
                    ' Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
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
              Case "GRISPPROGRAMA" ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("titulo")
              Case "GRISPPROP"     ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("proponente")
              Case "GRISPRESP"     ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("nm_solic")
              Case "GRISPRESPATU"  ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("nm_exec")
              Case "GRISPSETOR"    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("nm_unidade_resp")
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
           If cDbl(Nvl(RS1("valor"),0)) < cDbl(Nvl(RS1("custo_real"),0)) Then
              t_acima    = t_acima + 1
              t_totacima = t_totacima + 1
           End If
        End If
        t_solic    = t_solic + 1
        t_valor    = t_valor + cDbl(Nvl(RS1("valor"),0))
        t_custo    = t_custo + cDbl(Nvl(RS1("custo_real"),0))
        
        t_totvalor = t_totvalor + cDbl(Nvl(RS1("valor"),0))
        t_totcusto = t_totcusto + cDbl(Nvl(RS1("custo_real"),0))
        t_totsolic = t_totsolic + 1
        w_qt_quebra = w_qt_quebra + 1
        RS1.MoveNext
      wend
      ImprimeLinha t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_custo, t_acima, w_chave

      ShowHTML "      <tr bgcolor=""#DCDCDC"" valign=""top"" align=""right"">"
      ShowHTML "          <td><font size=""1""><b>Totais</font></td>"
      ImprimeLinha t_totsolic, t_totcad, t_tottram, t_totconc, t_totatraso, t_totaviso, t_totvalor, t_totcusto, t_totacima, -1
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    
    If RS1.RecordCount > 0 and p_tipo = "N" Then ' Coloca o gr�fico somente se o usu�rio desejar
       ShowHTML "<tr><td align=""center"" height=20>"
       ShowHTML "<tr><td align=""center""><IMG SRC=""" & conPHP4 & w_dir & "geragrafico.php?p_genero=M&p_objeto=" & RS_Menu("nome") & "&p_tipo="&SG&"&p_grafico=Barra&p_tot="&t_totsolic&"&p_cad="&t_totcad&"&p_tram="&t_tottram&"&p_conc="&t_totconc&"&p_atraso="&t_totatraso&"&p_aviso="&t_totaviso&"&p_acima="&t_totacima&""">"       
       ShowHTML "<tr><td align=""center"" height=20>"
       If (t_totcad + t_tottram) > 0 Then
          ShowHTML "<tr><td align=""center""><IMG SRC=""" & conPHP4 & w_dir & "geragrafico.php?p_genero=M&p_objeto=" & RS_Menu("nome") & "&p_tipo="&SG&"&p_grafico=Pizza&p_tot="&t_totsolic&"&p_cad="&t_totcad&"&p_tram="&t_tottram&"&p_conc="&t_totconc&"&p_atraso="&t_totatraso&"&p_aviso="&t_totaviso&"&p_acima="&t_totacima&""">"       
       End If
    End If
    
  ElseIf Instr("P",O) > 0 Then
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td align=""center"" valign=""top""><table border=0 width=""90%"" cellspacing=0>"
    AbreForm "Form", w_dir & w_Pagina & par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"

    ' Exibe par�metros de apresenta��o
    ShowHTML "         <tr><td colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Par�metros de Apresenta��o</td>"
    ShowHTML "         <tr valign=""top""><td colspan=2><table border=0 width=""100%"" cellpadding=0 cellspacing=0><tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b><U>A</U>gregar por:<br><SELECT ACCESSKEY=""A"" " & w_Disabled & " class=""STS"" name=""p_agrega"" size=""1"">"
    Select case p_agrega
       Case "GRISPPROGRAMA" ShowHTML "          <option value=""GRISPRESPATU"">Usu�rio atual<option value=""GRISPPROGRAMA"" selected>Programa<option value=""GRISPPROP"">Parcerias externas<option value=""GRISPRESP"">Respons�vel monitoramento<option value=""GRISPSETOR"">�rea planejamento"
       Case "GRISPPROP"     ShowHTML "          <option value=""GRISPRESPATU"">Usu�rio atual<option value=""GRISPPROGRAMA"">Programa<option value=""GRISPPROP"" selected>Parcerias externas<option value=""GRISPRESP"">Respons�vel monitoramento<option value=""GRISPSETOR"">�rea planejamento"
       Case "GRISPRESPATU"  ShowHTML "          <option value=""GRISPRESPATU"" selected>Usu�rio atual<option value=""GRISPPROGRAMA"">Programa<option value=""GRISPPROP"">Parcerias externas<option value=""GRISPRESP"">Respons�vel monitoramento<option value=""GRISPSETOR"">�rea planejamento"
       Case "GRISPSETOR"    ShowHTML "          <option value=""GRISPRESPATU"">Usu�rio atual<option value=""GRISPROGRAMA"">Programa<option value=""GRISPPROP"">Parcerias externas<option value=""GRISPRESP"">Respons�vel monitoramento<option value=""GRISPSETOR"" selected>�rea planejamento"
       Case Else            ShowHTML "          <option value=""GRISPRESPATU"">Usu�rio atual<option value=""GRISPPROGRAMA"">Programa<option value=""GRISPPROP"">Parcerias externas<option value=""GRISPRESP"" selected>Respons�vel monitoramento<option value=""GRISPSETOR"">�rea planejamento"
    End Select
    ShowHTML "          </select></td>"
    MontaRadioNS "<b>Inibe exibi��o do gr�fico?</b>", p_tipo, "p_tipo"
    MontaRadioSN "<b>Limita tamanho do assunto?</b>", p_tamanho, "p_tamanho"
    ShowHTML "           </table>"
    ShowHTML "         </tr>"
    ShowHTML "         <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Crit�rios de Busca</td>"

    ShowHTML "      <tr><td colspan=2><table border=0 width=""90%"" cellspacing=0><tr valign=""top"">"
    p_cd_programa = ""
    SelecaoProgramaPPA "Programa <u>P</u>PA:", "P", null, w_cliente, w_ano, p_cd_programa, "p_cd_programa", null, null, w_menu
    ShowHTML "          </table>"   
    ShowHTML "      <tr valign=""top"">"
    'ShowHTML "          <td valign=""top""><font size=""1""><b>N�mero da <U>a</U>��o:<br><INPUT ACCESSKEY=""A"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_chave"" size=""18"" maxlength=""18"" value=""" & p_chave & """></td>"
    'ShowHTML "          <td valign=""top""><font size=""1"">"
    ShowHTML "          <td valign=""top""><font size=""1""><b>Dias para a data limi<U>t</U>e:<br><INPUT ACCESSKEY=""T"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_prazo"" size=""2"" maxlength=""2"" value=""" & p_prazo & """></td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b>Exibe somente programas com restri��o?</b><br>"
    If p_qtd_restricao = "S" Then
       ShowHTML "              <input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_qtd_restricao"" value=""S"" checked> Sim <input " & w_Disabled & " class=""STR"" class=""STR"" type=""radio"" name=""p_qtd_restricao"" value=""N""> N�o"
    Else
       ShowHTML "              <input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_qtd_restricao"" value=""S""> Sim <input " & w_Disabled & " class=""STR"" class=""STR"" type=""radio"" name=""p_qtd_restricao"" value=""N"" checked> N�o"
    End If
    ShowHTML "      <tr valign=""top"">"
    SelecaoPessoa "Respo<u>n</u>s�vel monitoramento:", "N", "Selecione o respons�vel pelo monitoramento do programa na rela��o.", p_solicitante, null, "p_solicitante", "USUARIOS"
    SelecaoUnidade_IS "�rea planejamento:", null, "Selecione a unidade respons�vel pelo programa.", p_unidade, null, "p_unidade", null, "PLANEJAMENTO"
    ShowHTML "      <tr valign=""top"">"
    SelecaoPessoa "E<u>x</u>ecutor:", "X", "Selecione o executor do programa na rela��o.", p_usu_resp, null, "p_usu_resp", "USUARIOS"
    SelecaoUnidade "Setor atual:", "Y", "Selecione a unidade onde o programa se encontra na rela��o.", p_uorg_resp, null, "p_uorg_resp", null, null
    'ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    'MontaRadioNS "<b>Selecionada pelo MP?</b>", p_selecao_mp, "w_selecionada_mpog"
    'MontaRadioNS "<b>SE/SEPPIR?</b>", p_selecao_se, "w_selecionada_relevante"
    'ShowHTML "</table>"
    ShowHTML "      <tr>"
    ShowHTML "          <td valign=""top""><font size=""1""><b>Parc<U>e</U>rias externas:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_proponente"" size=""25"" maxlength=""90"" value=""" & p_proponente & """></td>"
    ShowHTML "          <td valign=""top"" colspan=2><font size=""1""><b>Par<U>c</U>erias internas:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_palavra"" size=""25"" maxlength=""90"" value=""" & p_palavra & """></td>"
    'ShowHTML "      <tr>"
    'ShowHTML "          <td valign=""top""><font size=""1""><b>A��<U>o</U>:<br><INPUT ACCESSKEY=""O"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_assunto"" size=""25"" maxlength=""90"" value=""" & p_assunto & """></td>"
    ShowHTML "      <tr>"
    ShowHTML "          <td valign=""top""><font size=""1""><b>Data de re<u>c</u>ebimento entre:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""p_ini_i"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_ini_i & """ onKeyDown=""FormataData(this,event);"" title=""Usar formato dd/mm/aaaa""> e <input " & w_Disabled & " accesskey=""C"" type=""text"" name=""p_ini_f"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_ini_f & """ onKeyDown=""FormataData(this,event);"" title=""Usar formato dd/mm/aaaa""></td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b>Limi<u>t</u>e para conclus�o entre:</b><br><input " & w_Disabled & " accesskey=""T"" type=""text"" name=""p_fim_i"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_fim_i & """ onKeyDown=""FormataData(this,event);"" title=""Usar formato dd/mm/aaaa""> e <input " & w_Disabled & " accesskey=""T"" type=""text"" name=""p_fim_f"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_fim_f & """ onKeyDown=""FormataData(this,event);"" title=""Usar formato dd/mm/aaaa""></td>"
    ShowHTML "      <tr>"
    ShowHTML "          <td valign=""top""><font size=""1""><b>Exibe somente programas em atraso?</b><br>"
    If p_atraso = "S" Then
       ShowHTML "              <input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_atraso"" value=""S"" checked> Sim <br><input " & w_Disabled & " class=""STR"" class=""STR"" type=""radio"" name=""p_atraso"" value=""N""> N�o"
    Else
       ShowHTML "              <input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_atraso"" value=""S""> Sim <br><input " & w_Disabled & " class=""STR"" class=""STR"" type=""radio"" name=""p_atraso"" value=""N"" checked> N�o"
    End If
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
    ShowHTML " alert('Op��o n�o dispon�vel');"
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
       Case "GRISPPROGRAMA" ShowHTML "          <td><font size=""1""><b>Programa</font></td>"
       Case "GRISPPROP"     ShowHTML "          <td><font size=""1""><b>Parceria externa</font></td>"
       Case "GRISPRESP"     ShowHTML "          <td><font size=""1""><b>Respons�vel</font></td>"
       Case "GRISPRESPATU"  ShowHTML "          <td><font size=""1""><b>Executor</font></td>"
       Case "GRISPSETOR"    ShowHTML "          <td><font size=""1""><b>�rea planejamento</font></td>"
    End Select
    ShowHTML "          <td><font size=""1""><b>Total</font></td>"
    ShowHTML "          <td><font size=""1""><b>Prog.</font></td>"
    ShowHTML "          <td><font size=""1""><b>Mon.</font></td>"
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
    If O = "L"                  Then ShowHTML "          <td align=""right""><font size=""1""><a class=""hl"" href=""javascript:lista('" & p_chave & "', -1, -1, -1, -1);"" onMouseOver=""window.status='Exibe os programas.'; return true"" onMouseOut=""window.status=''; return true"">" & FormatNumber(p_solic,0) & "</a>&nbsp;</font></td>"                  Else ShowHTML "          <td align=""right""><font size=""1"">" & FormatNumber(p_solic,0) & "&nbsp;</font></td>" End If
    If p_cad > 0    and O = "L" Then ShowHTML "          <td align=""right""><a class=""hl"" href=""javascript:lista('" & p_chave & "', 0, -1, -1, -1);"" onMouseOver=""window.status='Exibe os programas.'; return true"" onMouseOut=""window.status=''; return true""><font size=""1"">" & FormatNumber(p_cad,0) & "</a>&nbsp;</font></td>"                     Else ShowHTML "          <td align=""right""><font size=""1"">" & FormatNumber(p_cad,0) & "&nbsp;</font></td>"   End If
    If p_tram > 0   and O = "L" Then ShowHTML "          <td align=""right""><a class=""hl"" href=""javascript:lista('" & p_chave & "', -1, 0, -1, -1);"" onMouseOver=""window.status='Exibe os programas.'; return true"" onMouseOut=""window.status=''; return true""><font size=""1"">" & FormatNumber(p_tram,0) & "</a>&nbsp;</font></td>"                    Else ShowHTML "          <td align=""right""><font size=""1"">" & FormatNumber(p_tram,0) & "&nbsp;</font></td>"  End If
    If p_conc > 0   and O = "L" Then ShowHTML "          <td align=""right""><a class=""hl"" href=""javascript:lista('" & p_chave & "', -1, -1, 0, -1);"" onMouseOver=""window.status='Exibe os programas.'; return true"" onMouseOut=""window.status=''; return true""><font size=""1"">" & FormatNumber(p_conc,0) & "</a>&nbsp;</font></td>"                    Else ShowHTML "          <td align=""right""><font size=""1"">" & FormatNumber(p_conc,0) & "&nbsp;</font></td>"  End If
    If p_atraso > 0 and O = "L" Then ShowHTML "          <td align=""right""><a class=""hl"" href=""javascript:lista('" & p_chave & "', -1, -1, -1, 0);"" onMouseOver=""window.status='Exibe os programas.'; return true"" onMouseOut=""window.status=''; return true""><font size=""1"" color=""red""><b>" & FormatNumber(p_atraso,0) & "</a>&nbsp;</font></td>" Else ShowHTML "          <td align=""right""><font size=""1""><b>" & p_atraso & "&nbsp;</font></td>"             End If
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
  ' Verifica se o usu�rio tem lota��o e localiza��o
  If (len(Session("LOTACAO")&"") = 0 or len(Session("LOCALIZACAO")&"") = 0) and Session("LogOn") = "Sim" Then
    ScriptOpen "JavaScript"
    ShowHTML " alert('Voc� n�o tem lota��o ou localiza��o definida. Entre em contato com o RH!'); "
    ShowHTML " top.location.href='Default.asp'; "
    ScriptClose
   Exit Sub
  End If

  Select Case Par
    Case "GERENCIAL"
       Gerencial
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

