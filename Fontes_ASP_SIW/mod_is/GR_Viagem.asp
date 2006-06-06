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
<!-- #INCLUDE VIRTUAL="/siw/mod_pd/Funcoes.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/mod_pd/DB_Tabelas.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/mod_pd/DB_Viagem.asp" -->
<!-- #INCLUDE FILE="DB_Geral.asp" -->
<!-- #INCLUDE FILE="DB_Tabelas.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /GR_Viagem.asp
REM ------------------------------------------------------------------------
REM Nome     : Celso Miguel Lago Filho
REM Descricao: Gerencia o m�dulo de passagens e di�rias
REM Mail     : celso@sbpi.com.br
REM Criacao  : 26/05/2006 10:00
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
Dim R, O, w_Cont, w_Reg, w_Pagina, w_Disabled, w_TP, w_classe
Dim w_Assinatura
Dim p_ativo, p_solicitante, p_prioridade, p_unidade, p_proponente, p_ordena, p_agrega, p_tamanho
Dim p_ini_i, p_ini_f, p_fim_i, p_fim_f, p_atraso, p_tipo, p_projeto, p_atividade, p_sq_prop, p_codigo
Dim p_chave, p_assunto, p_usu_resp, p_uorg_resp, p_palavra, p_prazo, p_fase, p_pais, p_uf, p_cidade, p_regiao
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

w_troca            = Request("w_troca")
p_projeto          = uCase(Request("p_projeto"))
p_atividade        = uCase(Request("p_atividade"))
p_tipo             = uCase(Request("p_tipo"))
p_ativo            = uCase(Request("p_ativo"))
p_solicitante      = uCase(Request("p_solicitante"))
p_prioridade       = uCase(Request("p_prioridade"))
p_unidade          = uCase(Request("p_unidade"))
p_proponente       = uCase(Request("p_proponente"))
p_sq_prop          = uCase(Request("p_sq_prop"))
p_ordena           = uCase(Request("p_ordena"))
p_ini_i            = uCase(Request("p_ini_i"))
p_ini_f            = uCase(Request("p_ini_f"))
p_fim_i            = uCase(Request("p_fim_i"))
p_fim_f            = uCase(Request("p_fim_f"))
p_atraso           = uCase(Request("p_atraso"))
p_codigo           = uCase(Request("p_codigo"))
p_chave            = uCase(Request("p_chave"))
p_assunto          = uCase(Request("p_assunto"))
p_usu_resp         = uCase(Request("p_usu_resp"))
p_uorg_resp        = uCase(Request("p_uorg_resp"))
p_palavra          = uCase(Request("p_palavra"))
p_prazo            = uCase(Request("p_prazo"))
p_fase             = uCase(Request("p_fase"))
p_agrega           = uCase(Request("p_agrega"))
p_tamanho          = uCase(Request("p_tamanho"))
  
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

w_Pagina     = "GR_Viagem.asp?par="
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
w_ano             = RetornaAno()

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
Set p_sq_prop     = Nothing
Set p_proponente  = Nothing
Set p_projeto     = Nothing
Set p_tipo        = Nothing
Set p_ordena      = Nothing
Set p_chave       = Nothing 
Set p_assunto     = Nothing 
Set p_usu_resp    = Nothing 
Set p_uorg_resp   = Nothing 
Set p_palavra     = Nothing 
Set p_prazo       = Nothing 
Set p_fase        = Nothing
Set p_agrega      = Nothing
Set p_tamanho     = Nothing
Set p_pais        = Nothing
Set p_regiao      = Nothing
Set p_cidade      = Nothing
Set p_uf          = Nothing

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
        DB_GetSolicData_IS RS, p_projeto, "ISACGERAL"
        If Nvl(RS("cd_acao"),"") > "" Then
           w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>A��o <td><font size=1>[<b><A class=""HL"" HREF=""" & w_dir & "Acao.asp?par=Visual&O=L&w_chave=" & p_projeto & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Exibe as informa��es da a��o."">" & RS("cd_unidade") & "." & RS("cd_programa") & "." & RS("cd_acao") & " - " & RS("nm_ppa") & " (" & RS("ds_unidade") & ")</a></b>]"
        Else
           w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>A��o <td><font size=1>[<b><A class=""HL"" HREF=""" & w_dir & "Acao.asp?par=Visual&O=L&w_chave=" & p_projeto & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Exibe as informa��es da a��o."">" & RS("titulo") & "</a></b>]"
        End If
     End If
     If p_atividade > ""  Then 
        DB_GetSolicData_IS RS, p_atividade, "ISTAGERAL"
           w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Tarefa <td><font size=1>[<b><A class=""HL"" HREF=""" & w_dir & "Tarefa.asp?par=Visual&O=L&w_chave=" & p_atividade & "&w_tipo=Volta&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ title=""Exibe as informa��es da tarefa."">" & RS("assunto") &"("& RS("sq_siw_solicitacao") & ")</a></b>]"
     End If                
     If p_codigo      > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>PCD n� <td><font size=1>[<b>" & p_codigo & "</b>]"       End If
     If p_assunto     > ""  Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Descri��o <td><font size=1>[<b>" & p_assunto & "</b>]"   End If
     If p_solicitante > ""  Then
        DB_GetPersonData RS, w_cliente, p_solicitante, null, null
        w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Respons�vel <td><font size=1>[<b>" & RS("nome_resumido") & "</b>]"
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
     If p_sq_prop  > ""  Then 
        DB_GetPersonData RS, w_cliente, p_sq_prop, null, null
        w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Proposto<td><font size=1>[<b>" & RS("nome_resumido") & "</b>]"
     End If        
     If p_pais > ""  Then 
        DB_GetCountryData RS, p_pais
        w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Pa�s <td><font size=1>[<b>" & RS("nome") & "</b>]"
     End If
     If p_regiao > ""  Then 
        DB_GetRegionData RS, p_regiao
        w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Regi�o <td><font size=1>[<b>" & RS("nome") & "</b>]"
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
           w_filtro = w_filtro & "Prorroga��o"
        ElseIf p_ativo = "C" Then
           w_filtro = w_filtro & "Complementa��o"
        End If
        w_filtro = w_filtro & "</b>]"
     End If                
     If p_fim_i       > ""  Then 
        w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>M�s <td><font size=1>[<b>" & p_fim_i & "</b>]"
     End If     
     If w_filtro > "" Then 
        w_filtro = "<table border=0><tr valign=""top""><td><font size=1><b>Filtro:</b><td nowrap><font size=1><ul>" & w_filtro & "</ul></tr></table>"
     End If
       
     Select case p_agrega
        Case "GRPDCIAVIAGEM"
           DB_GetSolicViagem RS1, P2, w_usuario, p_agrega, 3, _
             p_ini_i, p_ini_f, null, null, p_atraso, p_solicitante, _
             p_unidade, null,  p_ativo, p_proponente, p_chave, p_assunto, _
             p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, p_uorg_resp, p_palavra, _
             p_prazo, p_fase, null, p_projeto, p_atividade, p_codigo, p_sq_prop        
           w_TP = TP & " - Por cia de viagem"
           RS1.sort = "nm_cia_viagem"
        Case "GRPDCIDADE"
           DB_GetSolicViagem RS1, P2, w_usuario, p_agrega, 3, _
             p_ini_i, p_ini_f, null, null, p_atraso, p_solicitante, _
             p_unidade, null,  p_ativo, p_proponente, p_chave, p_assunto, _
             p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, p_uorg_resp, p_palavra, _
             p_prazo, p_fase, null, p_projeto, p_atividade, p_codigo, p_sq_prop                
           w_TP = TP & " - Por cidade de destino"
           RS1.sort = "nm_destino"
        Case "GRPDUNIDADE"
           DB_GetSolicList RS1, P2, w_usuario, p_agrega, 3, _
             p_ini_i, p_ini_f, null, null, p_atraso, p_solicitante, _
             p_unidade, null,  p_ativo, p_proponente, p_chave, p_assunto, _
             p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, p_uorg_resp, p_palavra, _
             p_prazo, p_fase, null, p_projeto, p_atividade, p_codigo, p_sq_prop
           w_TP = TP & " - Por unidade proponente"
           RS1.sort = "nm_unidade_resp"
        Case "GRPDACAO"
           DB_GetSolicList_IS RS1, P2, w_usuario, p_agrega, 3, _
             p_ini_i, p_ini_f, null, null, p_atraso, p_solicitante, _
             p_unidade, null,  p_ativo, p_proponente, p_chave, p_assunto, _
             p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, p_uorg_resp, p_palavra, _
             p_prazo, p_fase, p_projeto, p_atividade, null, p_codigo, null, null, w_ano  
           w_TP = TP & " - Por a��o"
           RS1.sort = "cd_programa, cd_acao"
        Case "GRPDDATA"
           'ExibeVariaveis
           DB_GetSolicViagem RS1, P2, w_usuario, p_agrega, 3, _
             p_ini_i, p_ini_f, p_fim_i, null, p_atraso, p_solicitante, _
             p_unidade, null,  p_ativo, p_proponente, p_chave, p_assunto, _
             p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, p_uorg_resp, p_palavra, _
             p_prazo, p_fase, null, p_projeto, p_atividade, p_codigo, p_sq_prop        
           w_TP = TP & " - Por m�s"
           RS1.sort = "nm_mes desc"
        Case "GRPDPROPOSTO"
           DB_GetSolicList RS1, P2, w_usuario, p_agrega, 3, _
             p_ini_i, p_ini_f, null, null, p_atraso, p_solicitante, _
             p_unidade, null,  p_ativo, p_proponente, p_chave, p_assunto, _
             p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, p_uorg_resp, p_palavra, _
             p_prazo, p_fase, null, p_projeto, p_atividade, p_codigo, p_sq_prop
           w_TP = TP & " - Por proposto"
           RS1.sort = "nm_prop"
        Case "GRPDTIPO"
           DB_GetSolicList RS1, P2, w_usuario, p_agrega, 3, _
             p_ini_i, p_ini_f, null, null, p_atraso, p_solicitante, _
             p_unidade, null,  p_ativo, p_proponente, p_chave, p_assunto, _
             p_pais, p_regiao, p_uf, p_cidade, p_usu_resp, p_uorg_resp, p_palavra, _
             p_prazo, p_fase, null, p_projeto, p_atividade, p_codigo, p_sq_prop
           w_TP = TP & " - Por tipo"
           RS1.sort = "tp_missao"
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
        Modulo
        FormataCPF
        CheckBranco
        FormataData
        ValidateOpen "Validacao"
        Validate "p_codigo", "N�mero da PCD", "", "", "2", "60", "1", "1"
        Validate "p_assunto", "Assunto", "", "", "2", "90", "1", "1"
        Validate "p_proponente", "Proposto", "", "", "2", "60", "1", ""
        Validate "p_palavra", "CPF", "CPF", "", "14", "14", "", "0123456789-."
        Validate "p_ini_i", "Primeira sa�da", "DATA", "", "10", "10", "", "0123456789/"
        Validate "p_ini_f", "�ltimo retorno", "DATA", "", "10", "10", "", "0123456789/"
        ShowHTML "  if ((theForm.p_ini_i.value != '' && theForm.p_ini_f.value == '') || (theForm.p_ini_i.value == '' && theForm.p_ini_f.value != '')) {"
        ShowHTML "     alert ('Informe ambas as datas ou nenhuma delas!');"
        ShowHTML "     theForm.p_ini_i.focus();"
        ShowHTML "     return false;"
        ShowHTML "  }"
        CompData "p_ini_i", "Primeira sa�da", "<=", "p_ini_f", "�ltimo retorno"    
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
            Case "GRPDCIAVIAGEM" ShowHTML "     document.Form.p_usu_resp.value=filtro;"
            Case "GRPDCIDADE"    ShowHTML "     document.Form.p_cidade.value=filtro;"
            Case "GRPDUNIDADE"   ShowHTML "     document.Form.p_unidade.value=filtro;"
            Case "GRPDACAO"      ShowHTML "     document.Form.p_projeto.value=filtro;"
            Case "GRPDDATA"      ShowHTML "     document.Form.p_fim_i.value=filtro;"
            Case "GRPDPROPOSTO"  ShowHTML "     document.Form.p_sq_prop.value=filtro;"
            Case "GRPDTIPO"      ShowHTML "     document.Form.p_ativo.value=filtro;"
         End Select
         ShowHTML "    }"
         Select case p_agrega
            Case "GRPDCIAVIAGEM" ShowHTML "    else document.Form.p_usu_resp.value='" & Request("p_usu_resp")& "';"
            Case "GRPDCIDADE"    ShowHTML "    else document.Form.p_cidade.value=""" & Request("p_cidade")& """;"
            Case "GRPDUNIDADE"   ShowHTML "    else document.Form.p_unidade.value='" & Request("p_unidade")& "';"
            Case "GRPDACAO"      ShowHTML "    else document.Form.p_projeto.value='" & Request("p_projeto")& "';"
            Case "GRPDDATA"      ShowHTML "    else document.Form.p_fim_i.value='" & Request("p_fim_i")& "';"
            Case "GRPDPROPOSTO"  ShowHTML "    else document.Form.p_sq_prop.value='" & Request("p_sq_prop")& "';"
            Case "GRPDTIPO"      ShowHTML "    else document.Form.p_ativo.value='" & Request("p_ativo")& "';"
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
         ShowHTML "    if (cad==-1 && exec==-1 && conc==-1) document.Form.p_fase.value='" & Request("p_fase") & "';"
         ShowHTML "    if (atraso >= 0) document.Form.p_atraso.value='S'; else document.Form.p_atraso.value='" & Request("p_atraso") & "'; "
         ShowHTML "    document.Form.submit();"
         ShowHTML "  }"
         ShowHTML "</SCRIPT>"
         ShowHTML "<BASE HREF=""" & conRootSIW & """>"
         DB_GetMenuData RS2, P2
         AbreForm "Form", RS2("link"), "POST", "return(Validacao(this));", "Lista",3,P2,RS2("P3"),null,w_TP,RS2("sigla"),w_dir & w_pagina & par,"L"
         ShowHTML MontaFiltro("POST")
         ShowHTML "<input type=""Hidden"" name=""p_atraso"" value=""N"">"
         Select case p_agrega
            Case "GRPDCIAVIAGEM" If Request("p_usu_resp")   = ""  Then ShowHTML "<input type=""Hidden"" name=""p_usu_resp"" value="""">"   End If
            Case "GRPDCIDADE"    If Request("p_cidade")     = ""  Then ShowHTML "<input type=""Hidden"" name=""p_cidade"" value="""">"     End If
            Case "GRPDUNIDADE"   If Request("p_unidade")    = ""  Then ShowHTML "<input type=""Hidden"" name=""p_unidade"" value="""">"    End If  
            Case "GRPDACAO"      If Request("p_projeto")    = ""  Then ShowHTML "<input type=""Hidden"" name=""p_projeto"" value="""">"    End If
            Case "GRPDDATA"      If Request("p_fim_i")      = ""  Then ShowHTML "<input type=""Hidden"" name=""p_fim_i"" value="""">"      End If
            Case "GRPDPROPOSTO"  If Request("p_sq_prop")    = ""  Then ShowHTML "<input type=""Hidden"" name=""p_sq_prop"" value="""">"    End If
            Case "GRPDTIPO"      If Request("p_ativo")      = ""  Then ShowHTML "<input type=""Hidden"" name=""p_ativo"" value="""">"      End If
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
           Case "GRPDCIAVIAGEM"
              If w_nm_quebra <> RS1("nm_cia_viagem") Then
                 If w_qt_quebra > 0 Then
                    ImprimeLinha t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_custo, t_acima, w_chave, p_agrega
                    w_linha = w_linha + 2
                 End If
                 If O <> "W" or (O = "W" and w_linha <= 25) Then
                    ' Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("nm_cia_viagem")
                 End If
                 w_nm_quebra       = RS1("nm_cia_viagem")
                 w_chave           = RS1("sq_cia_transporte")
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
           Case "GRPDDATA"
              If w_nm_quebra <> RS1("nm_mes") Then
                 If w_qt_quebra > 0 Then
                    ImprimeLinha t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_custo, t_acima, w_chave, p_agrega
                    w_linha = w_linha + 2
                 End If
                 If O <> "W" or (O = "W" and w_linha <= 25) Then
                    ' Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("nm_mes")
                 End If
                 w_nm_quebra       = RS1("nm_mes")
                 w_chave           = RS1("cd_mes")
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
           Case "GRPDCIDADE"
              If w_nm_quebra <> RS1("nm_destino") Then
                 If w_qt_quebra > 0 Then
                    ImprimeLinha t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_custo, t_acima, w_chave, p_agrega
                    w_linha = w_linha + 2
                 End If
                 If O <> "W" or (O = "W" and w_linha <= 25) Then
                    ' Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("nm_destino")
                 End If
                 w_nm_quebra       = RS1("nm_destino")
                 w_chave           = RS1("destino")
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
           Case "GRPDUNIDADE"
              If w_nm_quebra <> RS1("nm_unidade_resp") Then
                 If w_qt_quebra > 0 Then
                    ImprimeLinha t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_custo, t_acima, w_chave, p_agrega
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
           Case "GRPDACAO"
              If w_nm_quebra <> RS1("descricao_acao") Then
                 If w_qt_quebra > 0 Then
                    ImprimeLinha t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_custo, t_acima, w_chave, p_agrega
                    w_linha = w_linha + 2
                 End If
                 If O <> "W" or (O = "W" and w_linha <= 25) Then
                    ' Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("cd_programa") & "." & RS1("cd_acao") & " - " & RS1("descricao_acao")
                 End If
                 w_nm_quebra       = RS1("descricao_acao")
                 w_chave           = RS1("sq_solic_acao")
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
           Case "GRPDPROPOSTO"
              If Nvl(w_nm_quebra,"") <> RS1("nm_prop") Then
                 If w_qt_quebra > 0 Then
                    ImprimeLinha t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_custo, t_acima, w_chave, p_agrega
                    w_linha = w_linha + 2
                 End If
                 If O <> "W" or (O = "W" and w_linha <= 25) Then
                    ' Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("nm_prop")
                 End If
                 w_nm_quebra       = RS1("nm_prop")
                 w_chave           = RS1("sq_prop")
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
           Case "GRPDTIPO"
              If w_nm_quebra <> RS1("nm_tp_missao") Then
                 If w_qt_quebra > 0 Then 
                    ImprimeLinha t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_custo, t_acima, w_chave, p_agrega
                    w_linha = w_linha + 2
                 End If
                 If O <> "W" or (O = "W" and w_linha <= 25) Then
                    ' Se for gera��o de MS-Word, coloca a nova quebra somente se n�o estourou o limite
                    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("nm_tp_missao")
                 End If
                 w_nm_quebra       = RS1("nm_tp_missao")
                 w_chave           = RS1("tp_missao")
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
              Case "GRPDCIAVIAGEM" ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("nm_cia_transporte")
              Case "GRPDCIDADE"    ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("nm_destino")
              Case "GRPDUNIDADE"   ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("nm_unidade_resp")
              Case "GRPDACAO"      ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("nm_projeto")
              Case "GRPDDATA"      ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("nm_mes")
              Case "GRPDPROPOSTO"  ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("nm_prop")
              Case "GRPDTIPO"      ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top""><td><font size=1><b>" & RS1("tp_missao")
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
      ImprimeLinha t_solic, t_cad, t_tram, t_conc, t_atraso, t_aviso, t_valor, t_custo, t_acima, w_chave, p_agrega
      If p_agrega <> "GRPDCIAVIAGEM" and p_agrega <> "GRPDCIDADE"  and p_agrega <> "GRPDDATA" Then
         ShowHTML "      <tr bgcolor=""#DCDCDC"" valign=""top"" align=""right"">"
         ShowHTML "          <td><font size=""1""><b>Totais</font></td>"
         ImprimeLinha t_totsolic, t_totcad, t_tottram, t_totconc, t_totatraso, t_totaviso, t_totvalor, t_totcusto, t_totacima, -1, p_agrega
      End If
    End If
    ShowHTML "      </FORM>"
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    
    If RS1.RecordCount > 0 and p_tipo = "N" Then ' Coloca o gr�fico somente se o usu�rio desejar
       ShowHTML "<tr><td align=""center"" height=20>"
       ShowHTML "<tr><td align=""center""><IMG SRC=""" & conPHP4 & "mod_pd/" & "geragrafico.php?p_genero=F&p_objeto=" & RS_Menu("nome") & "&p_tipo="&SG&"&p_grafico=Barra&p_tot="&t_totsolic&"&p_cad="&t_totcad&"&p_tram="&t_tottram&"&p_conc="&t_totconc&"&p_atraso="&t_totatraso&"&p_aviso="&t_totaviso&"&p_acima="&t_totacima&""">"
       ShowHTML "<tr><td align=""center"" height=20>"
       If (t_totcad + t_tottram) > 0 Then
          ShowHTML "<tr><td align=""center""><IMG SRC=""" & conPHP4 & "mod_pd/" & "geragrafico.php?p_genero=F&p_objeto=" & RS_Menu("nome") & "&p_tipo="&SG&"&p_grafico=Pizza&p_tot="&t_totsolic&"&p_cad="&t_totcad&"&p_tram="&t_tottram&"&p_conc="&t_totconc&"&p_atraso="&t_totatraso&"&p_aviso="&t_totaviso&"&p_acima="&t_totacima&""">"
       End If
    End If
    
  ElseIf Instr("P",O) > 0 Then
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o bot�o <i>Aplicar filtro</i>. Clicando sobre o bot�o <i>Remover filtro</i>, o filtro existente ser� apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    AbreForm "Form", w_dir & w_Pagina & par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"

    ' Exibe par�metros de apresenta��o
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td align=""center"" valign=""top""><table border=0 width=""90%"" cellspacing=0>"
    ShowHTML "         <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Par�metros de Apresenta��o</td>"
    ShowHTML "         <tr valign=""top""><td colspan=2><table border=0 width=""100%"" cellpadding=0 cellspacing=0><tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b><U>A</U>gregar por:<br><SELECT ACCESSKEY=""A"" " & w_Disabled & " class=""STS"" name=""p_agrega"" size=""1"">"
    If p_agrega = "GRPDCIAVIAGEM" Then                   ShowHTML " <option value=""GRPDCIAVIAGEM"" selected>Cia. Viagem"      Else ShowHTML " <option value=""GRPDCIAVIAGEM"">Cia. viagem"      End If
    If p_agrega = "GRPDCIDADE"    Then                   ShowHTML " <option value=""GRPDCIDADE"" selected>Cidade destino"      Else ShowHTML " <option value=""GRPDCIDADE"">Cidade destino"      End If
    If p_agrega = "GRPDUNIDADE"   Then                   ShowHTML " <option value=""GRPDUNIDADE"" selected>Unidade proponente" Else ShowHTML " <option value=""GRPDUNIDADE"">Unidade proponente" End If
    If p_agrega = "GRPDACAO"      Then                   ShowHTML " <option value=""GRPDACAO"" selected>A��o"                  Else ShowHTML " <option value=""GRPDACAO"">A��o"                  End If
    If p_agrega = "GRPDDATA"      Then                   ShowHTML " <option value=""GRPDDATA"" selected>M�s"                   Else ShowHTML " <option value=""GRPDDATA"">M�s"                   End If
    If p_agrega = "GRPDPROPOSTO"  Then                   ShowHTML " <option value=""GRPDPROPOSTO"" selected>Proposto"          Else ShowHTML " <option value=""GRPDPROPOSTO"">Proposto"          End If
    If p_agrega = "GRPDTIPO"      Then                   ShowHTML " <option value=""GRPDTIPO"" selected>Tipo"                  Else ShowHTML " <option value=""GRPDTIPO"">Tipo"                  End If
    ShowHTML "          </select></td>"
    MontaRadioNS "<b>Inibe exibi��o do gr�fico?</b>", p_tipo, "p_tipo"
    MontaRadioSN "<b>Limita tamanho do detalhamento?</b>", p_tamanho, "p_tamanho"
    ShowHTML "           </table>"
    ShowHTML "         </tr>"
    ShowHTML "         <tr><td valign=""top"" colspan=""2"" align=""center"" bgcolor=""#D0D0D0"" style=""border: 2px solid rgb(0,0,0);""><font size=""1""><b>Crit�rios de Busca</td>"

    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "      <tr><td valign=""top"" colspan=""2"">"
    ShowHTML "        <table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "          <tr>"     
    SelecaoAcao "A��<u>o</u>:", "O", "Selecione a a��o da tarefa na rela��o.", w_cliente, w_ano, null, null, null, null, "p_projeto", "ACAO", "onchange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_atividade'; document.Form.target=''; document.Form.submit();""", p_projeto
    ShowHTML "          <tr>"
    SelecaoTarefa "<u>T</u>arefa:", "T", null, w_cliente, w_ano, p_atividade, "p_atividade", Nvl(p_projeto,0), null
    ShowHTML "          </tr>"
    ShowHTML "        </table></td></tr>"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><table border=0 width=""100%"" cellspacing=0>"
    ShowHTML "   <tr valign=""top"">"
    ShowHTML "     <td valign=""top""><font size=""1""><b>N�mero da P<U>C</U>D:<br><INPUT ACCESSKEY=""C"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_codigo"" size=""20"" maxlength=""60"" value=""" & p_codigo & """></td>"
    ShowHTML "     <td valign=""top""><font size=""1""><b><U>D</U>escri��o:<br><INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_assunto"" size=""25"" maxlength=""90"" value=""" & p_assunto & """></td>"       
    ShowHTML "   <tr valign=""top"">"
    SelecaoPessoa "Respo<u>n</u>s�vel:", "N", "Selecione o respons�vel pela PCD na rela��o.", p_solicitante, null, "p_solicitante", "USUARIOS"
    SelecaoUnidade1 "<U>U</U>nidade proponente:", "U", "Selecione a unidade proponente da PCD", p_unidade, null, "p_unidade", "VIAGEMANO", null, w_ano
    ShowHTML "   <tr>"       
    ShowHTML "     <td valign=""top""><font size=""1""><b><U>P</U>roposto:<br><INPUT ACCESSKEY=""P"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_proponente"" size=""25"" maxlength=""60"" value=""" & p_proponente & """></td>"
    ShowHTML "     <td valign=""top""><font size=""1""><b>CP<u>F</u> do proposto:<br><INPUT ACCESSKEY=""F"" TYPE=""text"" class=""sti"" NAME=""p_palavra"" VALUE=""" & p_palavra & """ SIZE=""14"" MaxLength=""14"" onKeyDown=""FormataCPF(this, event);"">"       
    ShowHTML "   <tr>"
    SelecaoPais "Pa<u>�</u>s destino:", "I", null, p_pais, null, "p_pais", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_regiao'; document.Form.submit();"""
    SelecaoRegiao "<u>R</u>egi�o destino:", "R", null, p_regiao, p_pais, "p_regiao", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_uf'; document.Form.submit();"""
    ShowHTML "   <tr>"
    SelecaoEstado "E<u>s</u>tado destino:", "S", null, p_uf, p_pais, "N", "p_uf", null, "onChange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_cidade'; document.Form.submit();"""
    SelecaoCidade "<u>C</u>idade destino:", "C", null, p_cidade, p_pais, p_uf, "p_cidade", null, null
    ShowHTML "   <tr>"
    SelecaoTipoPCD "Ti<u>p</u>o:", "P", null, p_ativo, "p_ativo", null, null
    SelecaoCiaTrans "Cia. Via<u>g</u>em", "R", "Selecione a companhia de transporte desejada.", w_cliente,  p_usu_resp, null, "p_usu_resp", "S", null
    ShowHTML "   <tr>"
    ShowHTML "     <td valign=""top""><font size=""1""><b>Pri<u>m</u>eira sa�da e �ltimo retorno:</b><br><input " & w_Disabled & " accesskey=""C"" type=""text"" name=""p_ini_i"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_ini_i & """ onKeyDown=""FormataData(this,event);"" title=""Usar formato dd/mm/aaaa""> e <input " & w_Disabled & " accesskey=""C"" type=""text"" name=""p_ini_f"" class=""STI"" SIZE=""10"" MAXLENGTH=""10"" VALUE=""" & p_ini_f & """ onKeyDown=""FormataData(this,event);"" title=""Usar formato dd/mm/aaaa""></td>"
    SelecaoFaseCheck "Recuperar fases:", "S", null, p_fase, P2, "p_fase", null, null
    ShowHTML "    </table>"
    ShowHTML "    <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "    <tr><td align=""center"" colspan=""3"">"
    ShowHTML "          <input class=""STB"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"" name=""Botao"" value=""Remover filtro"">"
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
       Case "GRPDCIAVIAGEM" ShowHTML "          <td><font size=""1""><b>Cia. Viagem</font></td>"
       Case "GRPDCIDADE"    ShowHTML "          <td><font size=""1""><b>Cidade destino</font></td>"
       Case "GRPDUNIDADE"   ShowHTML "          <td><font size=""1""><b>Unidade proponente</font></td>"
       Case "GRPDACAO"      ShowHTML "          <td><font size=""1""><b>A��o</font></td>"
       Case "GRPDDATA"      ShowHTML "          <td><font size=""1""><b>M�s</font></td>"
       Case "GRPDPROPOSTO"  ShowHTML "          <td><font size=""1""><b>Proposto</font></td>"
       Case "GRPDTIPO"      ShowHTML "          <td><font size=""1""><b>Tipo</font></td>"
    End Select
    ShowHTML "          <td><font size=""1""><b>Total</font></td>"
    ShowHTML "          <td><font size=""1""><b>Cad.</font></td>"
    ShowHTML "          <td><font size=""1""><b>Tram.</font></td>"
    ShowHTML "          <td><font size=""1""><b>Enc.</font></td>"
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
Sub ImprimeLinha (p_solic, p_cad, p_tram, p_conc, p_atraso, p_aviso, p_valor, p_custo, p_acima, p_chave, p_agrega)
    If O = "L"                  Then ShowHTML "          <td align=""right""><font size=""1""><a class=""hl"" href=""javascript:lista('" & p_chave & "', -1, -1, -1, -1);"" onMouseOver=""window.status='Exibe as pcds.'; return true"" onMouseOut=""window.status=''; return true"">" & FormatNumber(p_solic,0) & "</a>&nbsp;</font></td>"                  Else ShowHTML "          <td align=""right""><font size=""1"">" & FormatNumber(p_solic,0) & "&nbsp;</font></td>" End If
    If p_cad    > 0 and O = "L" Then ShowHTML "          <td align=""right""><a class=""hl"" href=""javascript:lista('" & p_chave & "', 0, -1, -1, -1);"" onMouseOver=""window.status='Exibe as pcds.'; return true"" onMouseOut=""window.status=''; return true""><font size=""1"">" & FormatNumber(p_cad,0) & "</a>&nbsp;</font></td>"                     Else ShowHTML "          <td align=""right""><font size=""1"">" & FormatNumber(p_cad,0) & "&nbsp;</font></td>"   End If
    If p_tram   > 0 and O = "L" Then ShowHTML "          <td align=""right""><a class=""hl"" href=""javascript:lista('" & p_chave & "', -1, 0, -1, -1);"" onMouseOver=""window.status='Exibe as pcds.'; return true"" onMouseOut=""window.status=''; return true""><font size=""1"">" & FormatNumber(p_tram,0) & "</a>&nbsp;</font></td>"                    Else ShowHTML "          <td align=""right""><font size=""1"">" & FormatNumber(p_tram,0) & "&nbsp;</font></td>"  End If
    If p_conc   > 0 and O = "L" Then ShowHTML "          <td align=""right""><a class=""hl"" href=""javascript:lista('" & p_chave & "', -1, -1, 0, -1);"" onMouseOver=""window.status='Exibe as pcds.'; return true"" onMouseOut=""window.status=''; return true""><font size=""1"">" & FormatNumber(p_conc,0) & "</a>&nbsp;</font></td>"                    Else ShowHTML "          <td align=""right""><font size=""1"">" & FormatNumber(p_conc,0) & "&nbsp;</font></td>"  End If
    If p_atraso > 0 and O = "L" Then ShowHTML "          <td align=""right""><a class=""hl"" href=""javascript:lista('" & p_chave & "', -1, -1, -1, 0);"" onMouseOver=""window.status='Exibe as pcds.'; return true"" onMouseOut=""window.status=''; return true""><font size=""1"" color=""red""><b>" & FormatNumber(p_atraso,0) & "</a>&nbsp;</font></td>" Else ShowHTML "          <td align=""right""><font size=""1""><b>" & p_atraso & "&nbsp;</font></td>"             End If
    If p_agrega = "GRPDCIAVIAGEM" or p_agrega = "GRPDCIDADE" or p_agrega = "GRPDDATA" Then
       ShowHTML "          <td align=""right""><font size=""1"">---&nbsp;</font></td>"
       ShowHTML "          <td align=""right""><font size=""1"">---&nbsp;</font></td>"
       ShowHTML "          <td align=""right""><font size=""1"">---&nbsp;</font></td>"
       ShowHTML "          <td align=""right""><font size=""1"">---&nbsp;</font></td>"    
    Else
       ShowHTML "          <td align=""right""><font size=""1"">" & FormatNumber(p_valor,2) & "&nbsp;</font></td>"
       ShowHTML "          <td align=""right""><font size=""1"">" & FormatNumber(p_custo,2) & "&nbsp;</font></td>"
       If p_aviso  > 0 and O = "L" Then 
          ShowHTML "          <td align=""right""><font size=""1"" color=""red""><b>" & FormatNumber(p_aviso,0) & "&nbsp;</font></td>"
       Else
          ShowHTML "          <td align=""right""><font size=""1""><b>" & p_aviso & "&nbsp;</font></td>"
       End If
       If p_acima  > 0  Then 
          ShowHTML "          <td align=""right""><font size=""1"" color=""red""><b>" & FormatNumber(p_acima,0) & "&nbsp;</font></td>"  
       Else 
          ShowHTML "          <td align=""right""><font size=""1""><b>" & p_acima & "&nbsp;</font></td>"  
       End If    
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

