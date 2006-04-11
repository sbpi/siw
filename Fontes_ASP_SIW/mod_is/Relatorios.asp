<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Geral.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_EO.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Cliente.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Seguranca.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Tabelas.asp" -->
<!-- #INCLUDE FILE="DB_Geral.asp" -->
<!-- #INCLUDE FILE="DML_Tabelas.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="VisualProgramaGer.asp" -->
<!-- #INCLUDE FILE="VisualAcaoGer.asp" -->
<!-- #INCLUDE FILE="VisualTarefaGer.asp" -->
<!-- #INCLUDE FILE="VisualTarefa.asp" -->
<!-- #INCLUDE FILE="VisualAcao.asp" -->
<!-- #INCLUDE FILE="VisualPrograma.asp" -->
<!-- #INCLUDE FILE="DB_SIAFI.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /Relatorios.asp
REM ------------------------------------------------------------------------
REM Nome     : Celso Miguel Lago Filho
REM Descricao: Diversos tipos de relatórios para fazer o acompanhamento gerencial 
REM Mail     : celso@sbpi.com.br
REM Criacao  : 21/04/2004 11:00
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
Dim R, O, w_Cont, w_Reg, w_Pagina, w_Disabled, w_TP, w_classe, w_submenu, w_filtro, w_copia
Dim w_Assinatura
Dim w_troca,w_cor, w_filter, w_cliente, w_usuario, w_menu, w_dir, w_chave, w_dir_volta
Dim w_sq_pessoa, w_ano
Dim ul,File
Dim w_pag, w_linha
Set RS  = Server.CreateObject("ADODB.RecordSet")
Set RS1 = Server.CreateObject("ADODB.RecordSet")
Set RS2 = Server.CreateObject("ADODB.RecordSet")
Set RS3 = Server.CreateObject("ADODB.RecordSet")
Set RS4 = Server.CreateObject("ADODB.RecordSet")
Set RS_Menu = Server.CreateObject("ADODB.RecordSet")

w_troca            = Request("w_troca")
w_copia            = Request("w_copia")
  
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

w_Pagina     = "Relatorios.asp?par="
w_Dir        = "mod_is/"
w_dir_volta  = "../"  
w_Disabled   = "ENABLED"

If O = "" Then 
   If par= "REL_PPA" _
      or par = "REL_PROJETO"          _ 
      or par = "REL_SINTETICO_PR"     _
      or par = "REL_SINTETICO_PPA"    _
      or par = "REL_SINTETICO_PROG"   _
      or par = "REL_PROGRAMA"         _
      or par = "REL_GERENCIAL_PROG"   _
      or par = "REL_GERENCIAL_ACAO"   _
      or par = "REL_GERENCIAL_TAREFA" _
      or par = "REL_DET_TAREFA"       _
      or par = "REL_DET_ACAO"         _
      or par = "REL_DET_PROG"         _
      or par = "REL_METAS"            Then
      O = "P"
   Else 
      O = "L"
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

w_cliente         = RetornaCliente()
w_usuario         = RetornaUsuario()
w_menu            = RetornaMenu(w_cliente, SG)
w_ano             = RetornaAno()

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

Set w_chave       = Nothing
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
Set w_dir         = Nothing
Set w_dir_volta   = Nothing
Set w_ano         = Nothing

REM =========================================================================
REM Relatório da tabela do PPA
REM -------------------------------------------------------------------------
Sub Rel_PPA
  Dim p_cd_acao, p_cd_programa, p_selecionada_mp, p_selecionada_se, p_codigo
  Dim w_acao_aprovado, w_acao_saldo, w_acao_empenhado, w_acao_liquidado, w_acao_liquidar
  Dim w_tot_aprovado, w_tot_saldo, w_tot_empenhado, w_tot_liquidado, w_tot_liquidar
  Dim p_responsavel, p_sq_unidade_resp, p_prioridade, p_tarefas_atraso
  Dim w_atual, w_col, w_col_word, p_campos, p_metas, p_tarefas, w_logo, w_titulo 
  Dim w_tipo_rel, w_linha, w_pag, p_ordena
  
  w_chave           = Request("w_chave")
  w_troca           = Request("w_troca")
  w_tipo_rel        = uCase(trim(Request("w_tipo_rel")))
  
  p_codigo                   = ucase(Trim(Request("p_codigo")))
  If  ucase(Trim(Request("p_cd_programa"))) > "" and p_codigo = "" Then
     p_cd_programa              = ucase(Trim(Request("p_cd_programa")))
  Else
     p_cd_programa              = ucase(Trim(Mid(p_codigo,1,4)))
  End If
  p_cd_acao                  = ucase(Trim(Mid(p_codigo,5,4)))
  p_responsavel              = ucase(Trim(Request("p_responsavel")))
  p_sq_unidade_resp          = ucase(Trim(Request("p_sq_unidade_resp")))
  p_prioridade               = ucase(Trim(Request("p_prioridade")))
  p_selecionada_mp           = ucase(Trim(Request("p_selecionada_mp")))
  p_selecionada_se           = ucase(Trim(Request("p_selecionada_se")))
  p_tarefas_atraso           = ucase(Trim(Request("p_tarefas_atraso")))
  p_campos                   = Request("p_campos")
  p_tarefas                  = Request("p_tarefas")
  p_metas                    = Request("p_metas")
  p_ordena                   = Request("p_ordena")
  
  If O = "L" Then
     ' Recupera o logo do cliente a ser usado nas listagens
     DB_GetCustomerData RS, w_cliente
     If RS("logo") > "" Then
         w_logo = "\img\logo" & Mid(RS("logo"),Instr(RS("logo"),"."),30)
     End If
     DesconectaBD
     ' Recupera todos os registros para a listagem
     If p_cd_programa > "" and p_codigo = "" Then
        DB_GetAcaoPPA_IS RS, w_cliente, w_ano, p_cd_programa , null, null, null, null, null, null
     Else
        DB_GetAcaoPPA_IS RS, w_cliente, w_ano, Mid(p_codigo,1,4), Mid(p_codigo,5,4), null, Mid(p_codigo,13,17), null, null, null
     End If
     If p_responsavel > "" Then
        RS.Filter = "nm_coordenador like '%" & p_responsavel & "%'"
     End If
     If p_ordena > "" Then 
        RS.sort = p_ordena 
     Else 
        RS.Sort = "descricao_acao" 
     End If
  End If
  
  If w_tipo_rel = "WORD" Then
     HeaderWord null
     w_pag   = 1
     w_linha = 5
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "WORD") & """><TD ALIGN=""RIGHT"" NOWRAP><B><FONT SIZE=4 COLOR=""#000000"">"
     ShowHTML "Relatório Analítico - Ações PPA 2004 - 2007 Exercício " & w_ano
     ShowHTML "</FONT><TR><TD WIDTH=""50%"" ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
     ShowHTML "<TR><TD COLSPAN=""2"" ALIGN=""RIGHT""><B><FONT SIZE=2 COLOR=""#000000"">Página: " & w_pag & "</B></TD></TR>"
     ShowHTML "</TD></TR>"
     ShowHTML "</FONT></B></TD></TR></TABLE>"
  Else
     Cabecalho
     ShowHTML "<HEAD>"
     ShowHTML "<TITLE>Relatório Analítico - Ações PPA 2004 - 2007 Exercício " & w_ano & "</TITLE>"
     If InStr("P",O) > 0 Then
        ScriptOpen "JavaScript"
        ValidateOpen "Validacao"
        Validate "p_cd_programa", "Programa", "SELECT", "", "1", "18", "", "1"
        Validate "p_codigo", "Ação", "SELECT", "", "1", "18", "1", "1"
        Validate "p_responsavel", "Responsável", "1", "", "2", "60", "1", "1"
        ShowHTML "  if (theForm.p_tarefas.checked == false) {"
        ShowHTML "     theForm.p_prioridade.value = ''"
        ShowHTML "  }"
        ShowHTML "  if (theForm.p_tarefas.checked == false && theForm.p_tarefas_atraso[0].checked == true) {"
        ShowHTML "      alert('Para exibir somente as tarefas em atraso,\n\n é preciso escolher a exibição da tarefa ');"
        ShowHTML "      return (false);"
        ShowHTML "  }"
        ValidateClose
        ShowHTML "  function MarcaTodosCampos() {"
        ShowHTML "    if (document.Form.w_marca_campos.checked==true) "
        ShowHTML "       for (i=0; i < 7; i++) {"
        ShowHTML "         document.Form.p_campos[i].checked=true;"
        ShowHTML "    } else { "
        ShowHTML "       for (i=0; i < 7; i++) {"
        ShowHTML "         document.Form.p_campos[i].checked=false;"
        ShowHTML "       } "
        ShowHTML "    } "
        ShowHTML "  }"
        ShowHTML "  function MarcaTodosBloco() {"
        ShowHTML "    if (document.Form.w_marca_bloco.checked==true) {"
        ShowHTML "         document.Form.p_tarefas.checked=true;"
        ShowHTML "         document.Form.p_metas.checked=true;"
        ShowHTML "         document.Form.p_sq_unidade_resp.checked=true;"
        ShowHTML "    } else { "
        ShowHTML "         document.Form.p_tarefas.checked=false;"
        ShowHTML "         document.Form.p_metas.checked=false;"
        ShowHTML "         document.Form.p_sq_unidade_resp.checked=false;"
        ShowHTML "    } "
        ShowHTML "  }"
        ScriptClose
     End If
     ShowHTML "</HEAD>"
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     If O = "L" Then
        BodyOpenClean "onLoad='document.focus()';"
        ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "EMBED") & """><TD ALIGN=""RIGHT"" NOWRAP><B><FONT SIZE=4 COLOR=""#000000"">"
        ShowHTML "Relatório Analítico - Ações PPA 2004 - 2007 Exercício " & w_ano
        ShowHTML "</FONT><TR><TD WIDTH=""50%"" ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
        ShowHTML "&nbsp;&nbsp;<IMG BORDER=0 ALIGN=""CENTER"" TITLE=""Gerar word"" SRC=""images/word.gif"" onClick=""window.open('" & w_pagina & par & "&R=" & w_pagina & par & "&O=L&w_chave=" & w_chave & "&w_tipo_rel=word&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") &"','VisualRelPPAWord','menubar=yes resizable=yes scrollbars=yes');"">"
        ShowHTML "&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Imprimir"" SRC=""images/impressora.jpg"" onClick=""window.print();"">"
        ShowHTML "</TD></TR>"
        ShowHTML "</FONT></B></TD></TR></TABLE>"
     Else
        BodyOpen "onLoad='document.Form.p_cd_programa.focus()';"
        ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
     End If
     ShowHTML "<HR>"
  End If
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    w_col      = 2
    w_col_word = 2
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    w_filtro = ""
    If p_responsavel           > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Responsável<td><font size=1>[<b>" & p_responsavel & "</b>]"                     End If
    If p_prioridade            > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Prioridade<td><font size=1>[<b>" & RetornaPrioridade(p_prioridade) & "</b>]"    End If
    If p_selecionada_mp        > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Selecionada SPI/MP<td><font size=1>[<b>" & p_selecionada_mp & "</b>]"             End If
    If p_selecionada_se        > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Selecionada SE/SEPPIR<td><font size=1>[<b>" & p_selecionada_se & "</b>]" End If
    If p_tarefas_atraso        > "" Then w_filtro = w_filtro & "<td><font size=1>Ações com tarefas em atraso&nbsp;<font size=1>[<b>" & p_tarefas_atraso & "</b>]&nbsp;"  End If
    ShowHTML "<tr><td align=""left"" colspan=2>"
    If w_filtro                > "" Then ShowHTML "<table border=0><tr valign=""top""><td><font size=1><b>Filtro:</b><td nowrap><font size=1><ul>" & w_filtro & "</ul></tr></table>"                End If
    ShowHTML "    <td align=""right"" valign=""botton""><font size=""1""><b>Registros listados: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    If w_tipo_rel = "WORD" Then
       ShowHTML "          <td><font size=""1""><b>Código</font></td>"
    Else
       ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Código","codigo") & "</font></td>"
    End If
    If w_tipo_rel = "WORD" Then    
       ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
    Else
       ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Nome","descricao_acao") & "</font></td>"
    End If
    If Instr(p_campos,"responsavel") Then
       If w_tipo_rel = "WORD" Then
          ShowHTML "          <td><font size=""1""><b>Responsável</font></td>"
       Else
          ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Responsável","responsavel") & "</font></td>"
       End If
       w_col      = w_col      + 1
       w_col_word = w_col_word + 1 
    End If
    If Instr(p_campos,"email") Then
       If w_tipo_rel = "WORD" Then
          ShowHTML "          <td nowrap><font size=""1""><b>e-Mail</font></td>"
       Else
          ShowHTML "          <td nowrap><font size=""1""><b>" & LinkOrdena("e-Mail","email") & "</font></td>"
       End If
       w_col      = w_col      + 1
       w_col_word = w_col_word + 1 
    End If
    If Instr(p_campos,"telefone") Then
       If w_tipo_rel = "WORD" Then
          ShowHTML "          <td><font size=""1""><b>Telefone</font></td>"
       Else
          ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Telefone","telefone") & "</font></td>"
       End If
       w_col      = w_col      + 1
       w_col_word = w_col_word + 1 
    End If
    If Instr(p_campos,"orgao") Then
       If w_tipo_rel = "WORD" Then
          ShowHTML "          <td><font size=""1""><b>Órgão</font></td>"
       Else
          ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Órgão","ds_orgao") & "</font></td>"
       End If
       w_col      = w_col      + 1
       w_col_word = w_col_word + 1 
    End If    
    If Instr(p_campos,"aprovado")   Then 
       If w_tipo_rel = "WORD" Then
          ShowHTML "          <td><font size=""1""><b>Aprovado</font></td>" 
       Else
          ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Aprovado","previsao_ano") & "</font></td>" 
       End If
       w_col_word = w_col_word + 1 
    End If
    If Instr(p_campos,"autorizado")  Then 
       If w_tipo_rel = "WORD" Then
          ShowHTML "          <td><font size=""1""><b>Autorizado</font></td>"           
       Else
          ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Autorizado","atual_ano") & "</font></td>" 
       End If
       w_col_word = w_col_word + 1 
    End If
    If Instr(p_campos,"saldo")      Then 
       ShowHTML "          <td><font size=""1""><b>Saldo</font></td>" 
       w_col_word = w_col_word + 1 
    End If
    If Instr(p_campos,"realizado")  Then 
       If w_tipo_rel = "WORD" Then
          ShowHTML "          <td><font size=""1""><b>Realizado</font></td>" 
       Else
          ShowHTML "          <td><font size=""1""><b>" & LinkOrdena("Realizado","real_ano") & "</font></td>" 
       End If
       w_col_word = w_col_word + 1 
    End If
    If Instr(p_campos,"liquidar")   Then 
       ShowHTML "          <td><font size=""1""><b>A liquidar</font></td>" 
       w_col_word = w_col_word + 1 
    End If
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=" & w_col & " align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      w_acao_aprovado = 0.00
      w_acao_saldo    = 0.00
      w_acao_empenhado= 0.00
      w_acao_liquidado= 0.00
      w_acao_liquidar = 0.00
        
      w_tot_aprovado  = 0.00
      w_tot_saldo     = 0.00
      w_tot_empenhado = 0.00
      w_tot_liquidado = 0.00
      w_tot_liquidar  = 0.00
      w_atual         = ""
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        If w_linha > 22 and w_tipo_rel = "WORD" Then
           ShowHTML "    </table>"
           ShowHTML "  </td>"
           ShowHTML "</tr>"
           ShowHTML "</table>"
           ShowHTML "</center></div>"
           ShowHTML "    <br style=""page-break-after:always"">"
           w_linha = 5
           w_pag   = w_pag + 1
           ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "WORD") & """><TD ALIGN=""RIGHT""><B><FONT SIZE=4 COLOR=""#000000"">"
           ShowHTML "Ações PPA"
           ShowHTML "</FONT><TR><TD WIDTH=""50%"" ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
           ShowHTML "<TR><TD COLSPAN=""2"" ALIGN=""RIGHT""><B><FONT SIZE=2 COLOR=""#000000"">Página: " & w_pag & "</B></TD></TR>"
           ShowHTML "</TD></TR>"
           ShowHTML "</FONT></B></TD></TR></TABLE>"
           ShowHTML "<div align=center><center>"
           ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
           ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
           w_filtro = ""
           If p_responsavel           > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Responsável<td><font size=1>[<b>" & p_responsavel & "</b>]"                     End If
           If p_prioridade            > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Prioridade<td><font size=1>[<b>" & RetornaPrioridade(p_prioridade) & "</b>]"    End If
           If p_selecionada_mp        > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Selecionada SPI/MP<td><font size=1>[<b>" & p_selecionada_mp & "</b>]"             End If
           If p_selecionada_se        > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Selecionada SE/SEPPIR<td><font size=1>[<b>" & p_selecionada_se & "</b>]" End If
           If p_tarefas_atraso        > "" Then w_filtro = w_filtro & "<td><font size=1>Ações com tarefas em atraso&nbsp;<font size=1>[<b>" & p_tarefas_atraso & "</b>]&nbsp;"  End If
           ShowHTML "<tr><td align=""left"" colspan=2>"
           If w_filtro                > "" Then ShowHTML "<table border=0><tr valign=""top""><td><font size=1><b>Filtro:</b><td nowrap><font size=1><ul>" & w_filtro & "</ul></tr></table>"                End If
           ShowHTML "    <td align=""right"" valign=""botton""><font size=""1""><b>Registros listados: " & RS.RecordCount
           ShowHTML "<tr><td align=""center"" colspan=3>"
           ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
           ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
           ShowHTML "          <td><font size=""1""><b>Código</font></td>"
           ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
           If Instr(p_campos,"responsavel") Then ShowHTML "          <td><font size=""1""><b>Responsável</font></td>" End If
           If Instr(p_campos,"email")       Then ShowHTML "          <td><font size=""1""><b>e-Mail</font></td>"      End If
           If Instr(p_campos,"telefone")    Then ShowHTML "          <td><font size=""1""><b>Telefone</font></td>"    End If
           If Instr(p_campos,"orgao")       Then ShowHTML "          <td><font size=""1""><b>Órgão</font></td>"       End If
           If Instr(p_campos,"aprovado")    Then ShowHTML "          <td><font size=""1""><b>Aprovado</font></td>"    End If
           If Instr(p_campos,"autorizado")  Then ShowHTML "          <td><font size=""1""><b>Autorizado</font></td>"  End If
           If Instr(p_campos,"saldo")       Then ShowHTML "          <td><font size=""1""><b>Saldo</font></td>"       End If
           If Instr(p_campos,"realizado")   Then ShowHTML "          <td><font size=""1""><b>Realizado</font></td>"   End If
           If Instr(p_campos,"liquidar")    Then ShowHTML "          <td><font size=""1""><b>A liquidar</font></td>"  End If
           ShowHTML "        </tr>"
        End If
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        If Nvl(RS("cd_programa"),"") = "" Then
           If w_atual > "" Then
              If Instr(p_campos,"aprovado") or Instr(p_campos,"saldo") or Instr(p_campos,"empenhado") or Instr(p_campos,"liquidado") or Instr(p_campos,"liquidar") Then
                 ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
                 ShowHTML "        <td colspan=" & w_col & " align=""right""><font size=""1""><b>Totais do programa <b>" & w_atual & "</b></td>"
                 If Instr(p_campos,"aprovado")  Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_aprovado,2) & "</td>" End If
                 If Instr(p_campos,"autorizado")Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_empenhado,2) & "</td>" End If
                 If Instr(p_campos,"saldo")     Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_saldo,2) & "</td>" End If
                 If Instr(p_campos,"realizado") Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_liquidado,2) & "</td>" End If
                 If Instr(p_campos,"liquidar")  Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_liquidar,2) & "</td>" End If
                 ShowHTML "      </tr>"
                 'ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ height=5><td colspan=10></td></tr>"
              End If
              w_acao_aprovado = 0.00
              w_acao_saldo    = 0.00
              w_acao_empenhado= 0.00
              w_acao_liquidado= 0.00
              w_acao_liquidar = 0.00
           End If
           ShowHTML "      <tr bgcolor=""" & conTrAlternateBgColor & """ valign=""top"">"
           ShowHTML "        <td nowrap><font size=""1""><b>" & RS("cd_unidade") & " . " & RS("cd_programa") & " . " & RS("cd_acao") & "</td>"
           ShowHTML "        <td><font size=""1""><b>" & RS("descricao_acao") & "</td>"
           If Instr(p_campos,"responsavel")       Then ShowHTML "        <td><font size=""1"">" & Nvl(RS("responsavel"),"---") & "</td>" End If
           If Instr(p_campos,"email")             Then ShowHTML "        <td><font size=""1"">" & Nvl(RS("email"),"---") & "</td>"  End If
           If Instr(p_campos,"telefone")          Then ShowHTML "        <td><font size=""1"">" & Nvl(RS("telefone"),"---") & "</td>" End If
           If Instr(p_campos,"orgao")             Then ShowHTML "        <td><font size=""1"">" & Nvl(RS("ds_orgao"),"---") & "</td>" End If
           If Instr(p_campos,"aprovado") or Instr(p_campos,"saldo") or Instr(p_campos,"autorizado") or Instr(p_campos,"realizado") or Instr(p_campos,"liquidar") Then
              ShowHTML "        <td colspan=" & w_col_word - w_col & "><font size=""1"">&nbsp;</td>"
           End If
           w_atual = RS("chave")
           w_linha = w_linha + 1
        Else
           ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
           ShowHTML "        <td nowrap><font size=""1"">&nbsp;&nbsp;" & RS("cd_unidade") & "." & RS("cd_programa") & "." & RS("cd_acao") & "</td>"
           ShowHTML "        <td><font size=""1"">" & RS("descricao_acao") & "</td>"
           If Instr(p_campos,"responsavel") Then ShowHTML "        <td><font size=""1"">" & Nvl(RS("responsavel"),"---") & "</td>" End If
           If Instr(p_campos,"email")       Then ShowHTML "        <td><font size=""1"">" & Nvl(RS("email"),"---") & "</td>" End If
           If Instr(p_campos,"telefone")    Then ShowHTML "        <td><font size=""1"">" & Nvl(RS("telefone"),"---") & "</td>" End If
           If Instr(p_campos,"orgao")       Then ShowHTML "        <td><font size=""1"">" & Nvl(RS("ds_orgao"),"---") & "</td>" End If
           If Instr(p_campos,"aprovado")    Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS("previsao_ano"),0)),2) & "</td>" End If
           If Instr(p_campos,"autorizado")  Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS("atual_ano"),0)),2) & "</td>" End If
           If Instr(p_campos,"saldo")       Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(cDbl(RS("aprovado"))-cDbl(RS("empenhado")),2) & "</td>" End If
           If Instr(p_campos,"realizado")   Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS("real_ano"),0)),2) & "</td>" End If
           If Instr(p_campos,"liquidar")    Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(cDbl(RS("empenhado"))-cDbl(RS("liquidado")),2) & "</td>" End If
           w_linha = w_linha + 1
           ShowHTML "</tr>"
           If p_metas > "" Then
              ShowHTML "      <tr><td><td colspan=" & w_col_word & "><table border=1 width=""100%"">"
              DB_GetLinkData RS1, w_cliente, "ISACAD"
              DB_GetSolicList_IS RS2, RS1("sq_menu"), w_usuario, RS1("sigla"), 4, _
                 null, null, null, null, null, null, null, null, null, null, null, null, _
                 null, null, null, null, null, null, null, null, null, null, null, Mid(RS("chave"),1,4), _
                 RS("cd_acao"), null, Mid(RS("chave"),9,4), w_ano
              RS2.sort = "fim, prioridade" 
              If RS2.EOF Then
                 ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=" & w_col_word & " align=""center""><font size=""1""><b>Não foram encontrados registros(metas).</b></td></tr>"
                 w_linha = w_linha + 1
              Else
                 DB_GetSolicMeta_IS RS3, RS2("sq_siw_solicitacao"), null, "LISTA", null, null, null, null, null, null, null
                 RS3.Sort = "ordem"
                 If RS3.EOF Then ' Se não foram selecionados registros, exibe mensagem
                    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=" & w_col_word & " align=""center""><font size=""1""><b>Não foram encontrados registros(metas).</b></td></tr>"
                    w_linha = w_linha + 1
                 Else
                    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
                    ShowHTML "          <td><font size=""1""><b>Produto</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Meta PPA</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Fim previsto</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Unidade<br>medida</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Quantitativo<br>programado</font></td>"
                    ShowHTML "          <td><font size=""1""><b>% Realizado</font></td>"
                    ShowHTML "        </tr>"
                    w_linha = w_linha + 1
                    w_cor = ""
                    While Not RS3.EOF
                       ShowHtml MetaLinha(RS2("sq_siw_solicitacao"), Rs3("sq_meta"), Rs3("titulo"), w_tipo_rel, Rs3("programada"), RS3("unidade_medida"), Rs3("quantidade"), Rs3("fim_previsto"), Rs3("perc_conclusao"), "S", "PROJETO", Nvl(RS3("cd_subacao"),""))
                       RS3.MoveNext
                       w_linha = w_linha + 1
                    Wend
                 End If
              End If
              ShowHTML "        </table>"
           End If
           
           If p_tarefas > "" Then
              ShowHTML "      <tr><td><td colspan=" & w_col_word & "><table border=1 width=""100%"">"
              DB_GetLinkData RS1, w_cliente, "ISACAD"
              DB_GetSolicList_IS RS2, RS1("sq_menu"), w_usuario, RS1("sigla"), 4, _
                 null, null, null, null, null, null, null, null, null, null, null, null, _
                 null, null, null, null, null, null, null, null, null, null, null, Mid(RS("chave"),1,4), _
                 RS("cd_acao"), null, Mid(RS("chave"),9,4), w_ano
              If RS2.EOF Then
                 ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=" & w_col_word & " align=""center""><font size=""1""><b>Não foram encontrados registros(tarefas).</b></td></tr>"
                 w_linha = w_linha + 1
              Else
                 DB_GetLinkData RS1, w_cliente, "ISTCAD"
                  RS2.sort = "fim, prioridade" 
                 DB_GetSolicList_IS RS3, RS1("sq_menu"), w_usuario, RS1("sigla"), 4, _
                    null, null, null, null, null, null, null, p_prioridade, null, null, null, null, _
                    null, null, null, null, null, null, null, null, null, RS2("sq_siw_solicitacao"), null, _
                    null, null, null, null, w_ano
                 If p_tarefas_atraso > "" Then
                    RS3.Filter = "fim < " & Date()
                 End If
                 RS3.sort = "fim, prioridade" 
                 If RS3.EOF Then ' Se não foram selecionados registros, exibe mensagem
                    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=" & w_col_word & " align=""center""><font size=""1""><b>Não foram encontrados registros(tarefas).</b></td></tr>"
                    w_linha = w_linha + 1
                 Else
                    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
                    ShowHTML "          <td><font size=""1""><b>Tarefas</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Detalhamento</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Responsável</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Parcerias</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Fim previsto</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Programado</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Executado</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Fase atual</font></td>"
                    If p_prioridade = "" Then ShowHTML "<td><font size=""1""><b>Prioridade</font></td>" End If
                    ShowHTML "        </tr>"
                    w_linha = w_linha + 1
                    While Not RS3.EOF
                      'If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
                      w_cor = conTrBgColor
                      ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
                      ShowHTML "        <td nowrap><font size=""1"">"
                      If RS3("concluida") = "N" Then
                         If RS3("fim") < Date() Then
                            ShowHTML "           <img src=""" & conImgAtraso & """ border=0 width=15 heigth=15 align=""center"">"
                         ElseIf RS3("aviso_prox_conc") = "S" and (RS3("aviso") <= Date()) Then
                            ShowHTML "           <img src=""" & conImgAviso & """ border=0 width=15 height=15 align=""center"">"
                         Else
                            ShowHTML "           <img src=""" & conImgNormal & """ border=0 width=15 height=15 align=""center"">"
                         End IF
                      Else
                         If RS3("fim") < Nvl(RS3("fim_real"),RS3("fim")) Then
                            ShowHTML "           <img src=""" & conImgOkAtraso & """ border=0 width=15 heigth=15 align=""center"">"
                         Else
                            ShowHTML "           <img src=""" & conImgOkNormal & """ border=0 width=15 height=15 align=""center"">"
                         End IF
                      End If
                      ShowHTML "        <A class=""HL"" HREF=""" & w_dir & "Tarefas.asp?par=Visual&R=" & w_pagina & par & "&O=L&w_chave=" & RS3("sq_siw_solicitacao") & "&w_tipo=&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ TARGET=""VisualTarefa"" title=""Exibe as informações desta tarefa."">" & RS3("sq_siw_solicitacao") & "&nbsp;</a>"
                      If Len(Nvl(RS3("assunto"),"-")) > 50 Then w_titulo = Mid(Nvl(RS3("assunto"),"-"),1,50) & "..." Else w_titulo = Nvl(RS3("assunto"),"-") End If
                      ShowHTML "        <td><font size=""1"">" & w_titulo & "</td>"
                      ShowHTML "        <td><font size=""1"">" & RS3("nm_solic") & "</td>"
                      ShowHTML "        <td><font size=""1"">" & Nvl(RS3("proponente"),"---") & "</td>"
                      ShowHTML "        <td align=""center""><font size=""1"">&nbsp;" & Nvl(FormatDateTime(RS3("fim"),2),"-") & "</td>"
                      ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(RS3("valor"),2) & "&nbsp;</td>"
                      ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(RS3("custo_real"),2) & "&nbsp;</td>"
                      ShowHTML "        <td nowrap><font size=""1"">" & RS3("nm_tramite") & "</td>"
                      If p_prioridade = "" Then ShowHTML "<td nowrap><font size=""1"">" & RetornaPrioridade(RS3("prioridade")) & "</td>" End If
                      ShowHTML "        </td>"
                      ShowHTML "      </tr>"
                      RS3.MoveNext
                      w_linha = w_linha + 1
                    Wend
                 End If
              End If
              ShowHTML "        </table>"
           End If
        If p_sq_unidade_resp > "" Then
           ShowHTML "      <tr><td><td colspan=" & w_col_word & "><table border=1 width=""100%"">"
          DB_GetLinkData RS1, w_cliente, "ISACAD"
          DB_GetSolicList_IS RS2, RS1("sq_menu"), w_usuario, RS1("sigla"), 4, _
             null, null, null, null, null, null, null, null, null, null, null, null, _
             null, null, null, null, null, null, null, null, null, null, null, Mid(RS("chave"),1,4), _
             RS("cd_acao"), null, Mid(RS("chave"),9,4), w_ano
             RS2.sort = "fim, prioridade" 
             If RS2.EOF Then
                 w_linha = w_linha + 1
                 ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td align=""left""><font size=""1""><b>Não foi informado a área de planejamento.</b></td></tr>"
              Else
                 ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
                 ShowHTML "          <td align=""left""><font size=""1""><b>Área planejamento</font></td>"
                 ShowHTML "        </tr>"
                 w_linha = w_linha + 1
                 While Not RS2.EOF
                    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
                    ShowHTML "          <td align=""left""><font size=""1""><b>"& RS2("nm_unidade_resp") & "</font></td>"
                    ShowHTML "        </tr>"
                    RS2.MoveNext
                    w_linha = w_linha + 1
                  Wend
              End If
              ShowHTML "        </table>"
           End If
        End If
        ShowHTML "      </tr>"
        w_acao_aprovado = w_acao_aprovado   + cDbl(Nvl(RS("previsao_ano"),0))
        w_acao_saldo    = w_acao_saldo      + cDbl(RS("aprovado"))-cDbl(RS("empenhado"))
        w_acao_empenhado= w_acao_empenhado  + cDbl(Nvl(RS("atual_ano"),0))
        w_acao_liquidado= w_acao_liquidado  + cDbl(Nvl(RS("real_ano"),0))
        w_acao_liquidar = w_acao_liquidar   + cDbl(RS("empenhado"))-cDbl(RS("liquidado"))
        
        w_tot_aprovado  = w_tot_aprovado   + cDbl(Nvl(RS("previsao_ano"),0))
        w_tot_saldo     = w_tot_saldo      + cDbl(RS("aprovado"))-cDbl(RS("empenhado"))
        w_tot_empenhado = w_tot_empenhado  + cDbl(Nvl(RS("atual_ano"),0))
        w_tot_liquidado = w_tot_liquidado  + cDbl(Nvl(RS("real_ano"),0))
        w_tot_liquidar  = w_tot_liquidar   + cDbl(RS("empenhado"))-cDbl(RS("liquidado"))
        RS.MoveNext
      wend
      If Instr(p_campos,"aprovado") or Instr(p_campos,"saldo") or Instr(p_campos,"autorizado") or Instr(p_campos,"realizado") or Instr(p_campos,"liquidar") Then
         'If Not p_cd_acao > " " Then
         '   ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
         '   ShowHTML "        <td colspan=" & w_col & " align=""right""><font size=""1""><b>Totais do programa <b>" & w_atual & "</b></td>"
         '   If Instr(p_campos,"aprovado")  Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_aprovado,2) & "</td>" End If
         '   If Instr(p_campos,"autorizado")Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_empenhado,2) & "</td>" End If
         '   If Instr(p_campos,"saldo")     Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_saldo,2) & "</td>" End If
         '   If Instr(p_campos,"realizado") Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_liquidado,2) & "</td>" End If
         '   If Instr(p_campos,"liquidar")  Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_liquidar,2) & "</td>" End If
         '   ShowHTML "      </tr>"
         '   w_linha = w_linha + 1
         'End If
         ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ height=5><td colspan=" & w_col &  "></td></tr>"
         ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""center"" height=30>"
         ShowHTML "        <td colspan=" & w_col & " align=""right""><font size=""2""><b>Totais do relatório</td>"
         If Instr(p_campos,"aprovado")  Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_tot_aprovado,2) & "</td>" End If
         If Instr(p_campos,"autorizado") Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_tot_empenhado,2) & "</td>" End If
         If Instr(p_campos,"saldo")     Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_tot_saldo,2) & "</td>" End If
         If Instr(p_campos,"realizado") Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_tot_liquidado,2) & "</td>" End If
         If Instr(p_campos,"liquidar")  Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_tot_liquidar,2) & "</td>" End If
         ShowHTML "      </tr>"
         w_linha = w_linha + 1
      End If
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    DesconectaBD
  ElseIf O = "P" Then
    AbreForm "Form", w_dir & w_pagina & par, "POST", "return(Validacao(this));", "Acao",P1,P2,P3,P4,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr>"
    SelecaoProgramaPPA "<u>P</u>rograma PPA:", "P", null, w_cliente, w_ano, p_cd_programa, "p_cd_programa", null, "onchange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='p_cd_programa'; document.Form.target=''; document.Form.O.value='P'; document.Form.submit();""", w_menu
    ShowHTML "      <tr>"
    SelecaoAcaoPPA "<u>A</u>ção PPA:", "A", null, w_cliente, w_ano, p_cd_programa, null, null, null, "p_codigo", null, null, null, w_menu
    ShowHTML "      <tr><td><font size=""1""><b><u>R</u>esponsável:</b><br><input " & w_disabled & " accesskey=""R"" type=""text"" name=""p_responsavel"" class=""sti"" SIZE=""40"" MAXLENGTH=""60"" VALUE=""" & p_responsavel & """></td>"
    ShowHTML "      <tr><td colspan=3><table border=0 width=""100%"" cellspacing=0 cellpadding=0><tr valign=""top"">"
    SelecaoPrioridade "<u>P</u>rioridade das tarefas:", "P", "Informe a prioridade da tarefa.", p_prioridade, null, "p_prioridade", null, null
    ShowHTML "          <td><font size=""1""><b>Exibir somente tarefas em atraso?</b><br><input " & w_Disabled & " type=""radio"" name=""p_tarefas_atraso"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""p_tarefas_atraso"" value="""" checked> Não"
    ShowHTML "          </table>"    
    ShowHTML "      <tr><td colspan=3><table border=0 width=""100%"" cellspacing=0 cellpadding=0><tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Selecionada MP?</b><br>"
    If p_selecionada_mp = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecionada_mp"" value=""S"" checked> Sim <input " & w_Disabled & " type=""radio"" name=""" & p_selecionada_mp & """ value=""N""> Não <input " & w_Disabled & " type=""radio"" name=""p_selecionada_mp"" value=""""> Independe"
    ElseIf p_selecionada_mp = "N" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecionada_mp"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""p_selecionada_mp"" value=""N"" checked> Não <input " & w_Disabled & " type=""radio"" name=""p_selecionada_mp"" value=""""> Independe"
    Else
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecionada_mp"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""p_selecionada_mp"" value=""N""> Não <input " & w_Disabled & " type=""radio"" name=""p_selecionada_mp"" value="""" checked> Independe"
    End If
    ShowHTML "          <td><font size=""1""><b>Selecionada SE/SEPPIR?</b><br>"
    If p_selecionada_se = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecionada_se"" value=""S"" checked> Sim <input " & w_Disabled & " type=""radio"" name=""" & p_selecionada_se & """ value=""N""> Não <input " & w_Disabled & " type=""radio"" name=""p_selecionada_se"" value=""""> Independe"
    ElseIf p_selecionada_se = "N" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecionada_se"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""p_selecionada_se"" value=""N"" checked> Não <input " & w_Disabled & " type=""radio"" name=""p_selecionada_se"" value=""""> Independe"
    Else
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecionada_se"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""p_selecionada_se"" value=""N""> Não <input " & w_Disabled & " type=""radio"" name=""p_selecionada_se"" value="""" checked> Independe"
    End If
    ShowHTML "          </table>"
    ShowHTML "      <tr><td colspan=3><table border=0 width=""100%"" cellspacing=0 cellpadding=0><tr valign=""top"">"
    ShowHTML "      <tr><td colspan=2><font size=1><b>Campos a serem exibidos"
    ShowHTML "      <tr>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""p_campos"" value=""responsavel""> Responsável</td>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""p_campos"" value=""aprovado""> Aprovado</td>"
    ShowHTML "      <tr>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""p_campos"" value=""email""> e-Mail</td>"
    'ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""p_campos"" value=""saldo""> Saldo</td>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""p_campos"" value=""autorizado""> Autorizado</td>"
    ShowHTML "      <tr>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""p_campos"" value=""telefone""> Telefone</td>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""p_campos"" value=""realizado""> Realizado</td>"
    ShowHTML "      <tr>"
    'ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""p_campos"" value=""liquidar""> A liquidar</td>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""p_campos"" value=""orgao""> Órgão</td>"    
    ShowHTML "      <tr>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""w_marca_campos"" value="""" onClick=""javascript:MarcaTodosCampos();"" TITLE=""Marca todos os itens da relação""> Todos</td>"
    ShowHTML "      <tr><td colspan=2><font size=1><b>Blocos adicionais"
    ShowHTML "      <tr>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""p_metas"" value=""metas""> Metas físicas</td>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""p_tarefas"" value=""tarefas""> Tarefas</td>"
    ShowHTML "      <tr>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""p_sq_unidade_resp"" value=""unidade""> Área planejamento</td>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""w_marca_bloco"" value="""" onClick=""javascript:MarcaTodosBloco();"" TITLE=""Marca todos os itens da relação""> Todos</td>"
    ShowHTML "     </table>"    
    ShowHTML "    <table width=""90%"" border=""0"">"            
    ShowHTML "      <tr><td align=""center""><hr>"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Exibir"">"
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
  
  If w_tipo_rel <> "WORD" Then
     Rodape
  End If
  
  Set w_titulo                  = Nothing 
  Set p_campos                  = Nothing 
  Set p_metas                   = Nothing 
  Set p_tarefas                 = Nothing 
  Set w_logo                    = Nothing 
  Set w_atual                   = Nothing 
  Set w_acao_aprovado           = Nothing 
  Set w_acao_saldo              = Nothing 
  Set w_acao_empenhado          = Nothing 
  Set w_acao_liquidado          = Nothing 
  Set w_acao_liquidar           = Nothing
  Set w_tot_aprovado            = Nothing 
  Set w_tot_saldo               = Nothing 
  Set w_tot_empenhado           = Nothing 
  Set w_tot_liquidado           = Nothing 
  Set w_tot_liquidar            = Nothing
  Set p_codigo                  = Nothing 
  Set p_cd_acao                 = Nothing 
  Set p_cd_programa             = Nothing 
  Set p_selecionada_mp          = Nothing 
  Set p_selecionada_se          = Nothing 
  Set p_responsavel             = Nothing 
  Set p_sq_unidade_resp         = Nothing
  Set p_ordena                  = Nothing 
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Relatório da tabela de planos/projetos especificos
REM -------------------------------------------------------------------------
Sub Rel_Projeto
  Dim p_sq_isprojeto, p_selecao_mp, p_selecao_se, p_siw_solic
  Dim w_acao_aprovado, w_acao_saldo, w_acao_empenhado, w_acao_liquidado, w_acao_liquidar
  Dim w_tot_aprovado, w_tot_saldo, w_tot_empenhado, w_tot_liquidado, w_tot_liquidar
  Dim p_responsavel, p_sq_unidade_resp, p_prioridade, p_tarefas_atraso
  Dim w_atual, w_col, w_col_word, p_campos, p_metas, p_tarefas, w_logo, w_titulo, w_sq_siw_solicitacao
  Dim w_tipo_rel, w_pag, w_linha, p_ordena
  
  w_chave           = Request("w_chave")
  w_troca           = Request("w_troca")
  w_tipo_rel        = uCase(trim(Request("w_tipo_rel")))
  
  p_sq_isprojeto             = ucase(Trim(Request("p_sq_isprojeto")))
  p_responsavel              = ucase(Trim(Request("p_responsavel")))
  p_prioridade               = ucase(Trim(Request("p_prioridade")))
  p_sq_unidade_resp          = ucase(Trim(Request("p_sq_unidade_resp")))
  p_selecao_mp               = ucase(Trim(Request("p_selecao_mp")))
  p_selecao_se               = ucase(Trim(Request("p_selecao_se")))
  p_tarefas_atraso           = ucase(Trim(Request("p_tarefas_atraso")))
  p_campos                   = Request("p_campos")
  p_tarefas                  = Request("p_tarefas")
  p_metas                    = Request("p_metas")
  p_siw_solic                = Request("p_siw_solic")
  p_ordena                   = Request("p_ordena")
  
  If O = "L" Then
     ' Recupera o logo do cliente a ser usado nas listagens
     DB_GetCustomerData RS, w_cliente
     If RS("logo") > "" Then
         w_logo = "\img\logo" & Mid(RS("logo"),Instr(RS("logo"),"."),30)
     End If
     DesconectaBD
     ' Recupera todos os registros para a listagem     
     DB_GetProjeto_IS RS, p_sq_isprojeto, w_cliente, null, null, p_responsavel, null, null, null, null, null, p_selecao_mp, p_selecao_se, null, p_siw_solic
     If p_ordena > "" Then RS.sort = p_ordena Else RS.Sort = "ordem" End If
  End If
  
  If w_tipo_rel = "WORD" Then
     HeaderWord null
     w_pag   = 1
     w_linha = 5
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "WORD") & """><TD ALIGN=""RIGHT"" NOWRAP><B><FONT SIZE=4 COLOR=""#000000"">"
     ShowHTML "Relatório Analítico - Planos interno " & w_ano
     ShowHTML "</FONT><TR><TD WIDTH=""50%"" ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
     ShowHTML "<TR><TD COLSPAN=""2"" ALIGN=""RIGHT""><B><FONT SIZE=2 COLOR=""#000000"">Página: " & w_pag & "</B></TD></TR>"
     ShowHTML "</TD></TR>"
     ShowHTML "</FONT></B></TD></TR></TABLE>"
  Else
     Cabecalho
     ShowHTML "<HEAD>"
     ShowHTML "<TITLE>Relatório Analítico Planos internos " & w_ano & "</TITLE>"
     If InStr("P",O) > 0 Then
        ScriptOpen "JavaScript"
        ValidateOpen "Validacao"
        Validate "p_sq_isprojeto", "Programa interno", "SELECT", "", "1", "18", "", "1"
        Validate "p_siw_solic", "Ações específicas", "SELECT", "", "1", "18", "", "1"
        Validate "p_responsavel", "Responsável", "1", "", "2", "60", "1", "1"
        ShowHTML "  if (theForm.p_tarefas.checked == false) {"
        ShowHTML "     theForm.p_prioridade.value = '';"
        ShowHTML "  }"
        ShowHTML "  if (theForm.p_tarefas.checked == false && theForm.p_tarefas_atraso[0].checked == true) {"
        ShowHTML "      alert('Para exibir somente as tarefas em atraso,\n\n é preciso escolher a exibição da tarefa ');"
        ShowHTML "      return (false);"
        ShowHTML "  }"
        ValidateClose
        ShowHTML "  function MarcaTodosCampos() {"
        ShowHTML "    if (document.Form.w_marca_campos.checked==true) "
        ShowHTML "       for (i=0; i < 3; i++) {"
        ShowHTML "         document.Form.p_campos[i].checked=true;"
        ShowHTML "    } else { "
        ShowHTML "       for (i=0; i < 3; i++) {"
        ShowHTML "         document.Form.p_campos[i].checked=false;"
        ShowHTML "       } "
        ShowHTML "    } "
        ShowHTML "  }"
        ShowHTML "  function MarcaTodosBloco() {"
        ShowHTML "    if (document.Form.w_marca_bloco.checked==true) {"
        ShowHTML "         document.Form.p_tarefas.checked=true;"
        ShowHTML "         document.Form.p_metas.checked=true;"
        ShowHTML "         document.Form.p_sq_unidade_resp.checked=true;"
        ShowHTML "    } else { "
        ShowHTML "         document.Form.p_tarefas.checked=false;"
        ShowHTML "         document.Form.p_metas.checked=false;"
        ShowHTML "         document.Form.p_sq_unidade_resp.checked=false;"
        ShowHTML "    } "
        ShowHTML "  }"        
        ScriptClose
     End If
     ShowHTML "</HEAD>"
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     If O = "L" Then
        BodyOpenClean "onLoad='document.focus()';"
        ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "EMBED") & """><TD ALIGN=""RIGHT"" NOWRAP><B><FONT SIZE=4 COLOR=""#000000"">"
        ShowHTML "Relatório Analítico - Planos internos " & w_ano
        ShowHTML "</FONT><TR><TD WIDTH=""50%"" ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
        ShowHTML "&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Imprimir"" SRC=""images/impressora.jpg"" onClick=""window.print();"">"
        ShowHTML "&nbsp;&nbsp;<IMG BORDER=0 ALIGN=""CENTER"" TITLE=""Gerar word"" SRC=""images/word.gif"" onClick=""window.open('" & w_pagina & par & "&R=" & w_pagina & par & "&O=L&w_chave=" & w_chave & "&w_tipo_rel=word&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") &"','VisualRelPPAWord','menubar=yes resizable=yes scrollbars=yes');"">"
        ShowHTML "</TD></TR>"
        ShowHTML "</FONT></B></TD></TR></TABLE>"
     Else
        BodyOpen "onLoad='document.Form.p_sq_isprojeto.focus()';"
        ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
     End If
     ShowHTML "<HR>"
  End If
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    w_col      = 1
    w_col_word = 1
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    'ShowHTML "<tr><td colspan=2><font size=""1""><font size=""1""><b>Filtro:"
    w_filtro = ""
    If p_responsavel           > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Responsável<td><font size=1>[<b>" & RetornaSimNao(p_responsavel) & "</b>]"                   End If
    If p_prioridade            > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Prioridade<td><font size=1>[<b>" & RetornaPrioridade(p_prioridade) & "</b>]"                 End If
    If p_selecao_mp            > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Selecionada MP<td><font size=1>[<b>" & RetornaSimNao(p_selecao_mp) & "</b>]"                 End If
    If p_selecao_se            > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Selecionada Relevante<td><font size=1>[<b>" & RetornaSimNao(p_selecao_se) & "</b>]"          End If
    If p_tarefas_atraso        > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Tarefas em atraso&nbsp;<font size=1>[<b>" & RetornaSimNao(p_tarefas_atraso) & "</b>]&nbsp;"  End If
    ShowHTML "<tr><td align=""left"" colspan=3>"
    If w_filtro                > "" Then ShowHTML "<table border=0><tr valign=""top""><td><font size=1><b>Filtro:</b><td nowrap><font size=1><ul>" & w_filtro & "</ul></td></tr></table></td></tr>"              End If    
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    If w_tipo_rel = "WORD" Then
       ShowHTML "        <td><font size=""1""><b>Nome</font></td>"
    Else
       ShowHTML "        <td><font size=""1""><b>" & LinkOrdena("Nome","nome") & "</font></td>"
    End If
    If Instr(p_campos,"responsavel") Then
       If w_tipo_rel = "WORD" Then
          ShowHTML "     <td><font size=""1""><b>Responsável</font></td>"
       Else
          ShowHTML "     <td><font size=""1""><b>" & LinkOrdena("Responsável","responsavel") & "</font></td>"
       End If
       w_col      = w_col      + 1
       w_col_word = w_col_word + 1 
    End If
    If Instr(p_campos,"email") Then
       If w_tipo_rel = "WORD" Then
          ShowHTML "     <td><font size=""1""><b>e-Mail</font></td>"
       Else
          ShowHTML "     <td><font size=""1""><b>" & LinkOrdena("e-Mail","email") & "</font></td>"
       End If
       w_col      = w_col      + 1
       w_col_word = w_col_word + 1 
    End If
    If Instr(p_campos,"telefone") Then
       If w_tipo_rel = "WORD" Then
          ShowHTML "     <td><font size=""1""><b>Telefone</font></td>"
       Else
          ShowHTML "     <td><font size=""1""><b>" & LinkOrdena("Telefone","telefone") & "</font></td>"
       End If
       w_col      = w_col      + 1
       w_col_word = w_col_word + 1 
    End If
    If Instr(p_campos,"aprovado")   Then 
       ShowHTML "          <td><font size=""1""><b>Aprovado</font></td>" 
       w_col_word = w_col_word + 1 
    End If
    If Instr(p_campos,"empenhado")  Then 
       ShowHTML "          <td><font size=""1""><b>Empenhado</font></td>" 
       w_col_word = w_col_word + 1 
    End If
    If Instr(p_campos,"saldo")      Then 
       ShowHTML "          <td><font size=""1""><b>Saldo</font></td>" 
       w_col_word = w_col_word + 1 
    End If
    If Instr(p_campos,"liquidado")  Then 
       ShowHTML "          <td><font size=""1""><b>Liquidado</font></td>" 
       w_col_word = w_col_word + 1 
    End If
    If Instr(p_campos,"liquidar")   Then 
       ShowHTML "          <td><font size=""1""><b>A liquidar</font></td>" 
       w_col_word = w_col_word + 1 
    End If
    ShowHTML "      </tr>"
    w_linha = w_linha + 1
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "  <tr bgcolor=""" & conTrBgColor & """><td colspan=" & w_col_word & " align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
        w_linha = w_linha + 1
    Else
      w_acao_aprovado = 0.00
      w_acao_saldo    = 0.00
      w_acao_empenhado= 0.00
      w_acao_liquidado= 0.00
      w_acao_liquidar = 0.00
        
      w_tot_aprovado  = 0.00
      w_tot_saldo     = 0.00
      w_tot_empenhado = 0.00
      w_tot_liquidado = 0.00
      w_tot_liquidar  = 0.00
      w_atual         = 0
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        If w_linha > 20 and w_tipo_rel = "WORD" Then
           CabecalhoWordRel w_logo, w_pag, w_linha, p_responsavel, p_prioridade, _
                            p_selecao_mp, p_selecao_se, p_tarefas_atraso, w_filtro, _
                            p_campos
           w_linha = w_linha + 1
        End If
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        If cDbl(w_atual) <> cDbl(RS("chave")) Then
           If (Instr(p_campos,"aprovado") or Instr(p_campos,"saldo") or Instr(p_campos,"empenhado") or Instr(p_campos,"liquidado") or Instr(p_campos,"liquidar")) and p_sq_isprojeto = "" and w_sq_siw_solicitacao > "" Then
              ShowHTML "   <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
              ShowHTML "     <td colspan=" & w_col & " align=""right""><font size=""1""><b>Totais do programa interno<d>"
              If Instr(p_campos,"aprovado")  Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_aprovado,2) & "</td>" End If
              If Instr(p_campos,"empenhado") Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_empenhado,2) & "</td>" End If
              If Instr(p_campos,"saldo")     Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_saldo,2) & "</td>" End If
              If Instr(p_campos,"liquidado") Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_liquidado,2) & "</td>" End If
              If Instr(p_campos,"liquidar")  Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_liquidar,2) & "</td>" End If
              ShowHTML "   </tr>"
              w_linha = w_linha + 1
           End If
           w_acao_aprovado = 0.00
           w_acao_saldo    = 0.00
           w_acao_empenhado= 0.00
           w_acao_liquidado= 0.00
           w_acao_liquidar = 0.00
           ShowHTML "      <tr bgcolor=""" & conTrAlternateBgColor & """ valign=""top"">"
           ShowHTML "        <td><font size=""1""><b>Programa interno: " & RS("nome") & "</td>"
           If Instr(p_campos,"responsavel")       Then ShowHTML "        <td><font size=""1"">" & Nvl(RS("responsavel"),"---") & "</td>" End If
           If Instr(p_campos,"email")             Then ShowHTML "        <td><font size=""1"">" & Nvl(RS("email"),"---") & "</td>"  End If
           If Instr(p_campos,"telefone")          Then ShowHTML "        <td><font size=""1"">" & Nvl(RS("telefone"),"---") & "</td>" End If
           If Instr(p_campos,"aprovado") or Instr(p_campos,"saldo") or Instr(p_campos,"empenhado") or Instr(p_campos,"liquidado") or Instr(p_campos,"liquidar") Then
              ShowHTML "        <td colspan=" & w_col_word - w_col & "><font size=""1"">&nbsp;</td>"
           End If
           ShowHTML "         </tr>"  
           w_linha = w_linha + 1 
        End If
        w_sq_siw_solicitacao = Nvl(RS("sq_siw_solicitacao"),"")
        w_atual = RS("chave")
        If w_sq_siw_solicitacao > "" Then
           ShowHTML "         <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
           If w_tipo_rel = "WORD" Then
              ShowHTML "        <td colspan=" &  w_col & "><font size=""1""><b>Ação:</b>" & RS("titulo") & "</td>"
           Else
              ShowHTML "        <td colspan=" &  w_col & "><font size=""1""><b>Ação:</b> <A class=""HL"" HREF=""" & w_dir & "Acao.asp?par=Visual&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ TARGET=""VisualAcao"" title=""Exibe as informações da ação."">" & RS("titulo") & "</a></td>"
           End If
           If Instr(p_campos,"aprovado")    Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(Nvl(RS("aprovado"),0),2) & "</td>" End If
           If Instr(p_campos,"empenhado")   Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(Nvl(RS("empenhado"),0),2) & "</td>" End If
           If Instr(p_campos,"saldo")       Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS("aprovado"),0))-cDbl(Nvl(RS("empenhado"),0)),2) & "</td>" End If
           If Instr(p_campos,"liquidado")   Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(Nvl(RS("liquidado"),0),2) & "</td>" End If
           If Instr(p_campos,"liquidar")    Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS("empenhado"),0))-cDbl(Nvl(RS("liquidado"),0)),2) & "</td>" End If
           w_linha = w_linha + 1
           ShowHTML "         </tr>"
        End If
        If w_sq_siw_solicitacao > "" Then
           If p_metas > "" Then
              ShowHTML "   <tr><td colspan=" & w_col_word & ">"
              ShowHTML "     <table border=1 width=""100%"">"
              DB_GetLinkData RS1, w_cliente, "ISACAD"
              DB_GetSolicList_IS RS2, RS1("sq_menu"), w_usuario, RS1("sigla"), 4, _
                 null, null, null, null, null, null, null, null, null, null, w_sq_siw_solicitacao, null, _
                 null, null, null, null, null, null, null, null, null, null, null, null,_
                 null, RS("chave"), null, w_ano
              RS2.sort = "fim, prioridade" 
              If RS2.EOF Then
                 ShowHTML "    <tr bgcolor=""" & conTrBgColor & """><td colspan=" & w_col_word & " align=""center""><font size=""1""><b>Não foram encontrados registros(metas).</b></td></tr>"
                 w_linha = w_linha + 1
              Else
                 DB_GetSolicMeta_IS RS3, RS2("sq_siw_solicitacao"), null, "LSTNULL", null, null, null, null, null, null, null
                 RS3.Sort = "ordem"
                 If RS3.EOF Then ' Se não foram selecionados registros, exibe mensagem
                    ShowHTML " <tr bgcolor=""" & conTrBgColor & """><td colspan=" & w_col_word & " align=""center""><font size=""1""><b>Não foram encontrados registros(metas).</b></td></tr>"
                    w_linha = w_linha + 1
                 Else
                    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
                    ShowHTML "          <td><font size=""1""><b>Produto</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Meta PPA</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Fim previsto</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Unidade<br>medida</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Quantitativo<br>programado</font></td>"
                    ShowHTML "          <td><font size=""1""><b>% Realizado</font></td>"
                    ShowHTML "        </tr>"
                    w_linha = w_linha + 1
                    While Not RS3.EOF
                       ShowHtml MetaLinha(RS2("sq_siw_solicitacao"), Rs3("sq_meta"), Rs3("titulo"), w_tipo_rel, Rs3("programada"), RS3("unidade_medida"), Rs3("quantidade"), Rs3("fim_previsto"), Rs3("perc_conclusao"), "S", "PROJETO", Nvl(RS2("cd_subacao"),""))
                       RS3.MoveNext
                       w_linha = w_linha + 1
                    Wend
                 End If
              End If
              ShowHTML "     </table>"
           End If
           If p_tarefas > "" Then
              ShowHTML "     <tr><td colspan=" & w_col_word & ">"
              ShowHTML "       <table border=1 width=""100%"">"
              DB_GetLinkData RS1, w_cliente, "ISACAD"
              DB_GetSolicList_IS RS2, RS1("sq_menu"), w_usuario, RS1("sigla"), 4, _
                 null, null, null, null, null, null, null, null, null, null, w_sq_siw_solicitacao, null, _
                 null, null, null, null, null, null, null, null, null, null, null, null, _
                 null, RS("chave"), null, w_ano
              RS2.sort = "fim, prioridade"    
              If RS2.EOF Then
                 ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=" & w_col_word & " align=""center""><font size=""1""><b>Não foram encontrados registros(tarefas).</b></td></tr>"
                 w_linha = w_linha + 1
              Else
                 DB_GetLinkData RS1, w_cliente, "ISTCAD"
                 DB_GetSolicList_IS RS3, RS1("sq_menu"), w_usuario, RS1("sigla"), 4, _
                    null, null, null, null, null, null, null, p_prioridade, null, null, null, null, _
                    null, null, null, null, null, null, null, null, null, RS2("sq_siw_solicitacao"), null, _
                    null, null, null, null, w_ano
                 If p_tarefas_atraso > "" Then
                    RS3.Filter = "fim < " & Date()
                 End If
                 RS3.sort = "fim, prioridade" 
                 If RS3.EOF Then ' Se não foram selecionados registros, exibe mensagem
                    ShowHTML "   <tr bgcolor=""" & conTrBgColor & """><td colspan=" & w_col_word & " align=""center""><font size=""1""><b>Não foram encontrados registros(tarefas).</b></td></tr>"
                    w_linha = w_linha + 1
                 Else
                    ShowHTML "   <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
                    ShowHTML "     <td><font size=""1""><b>Tarefas</font></td>"
                    ShowHTML "     <td><font size=""1""><b>Detalhamento</font></td>"
                    ShowHTML "     <td><font size=""1""><b>Responsável</font></td>"
                    ShowHTML "     <td><font size=""1""><b>Parcerias</font></td>"
                    ShowHTML "     <td><font size=""1""><b>Fim previsto</font></td>"
                    ShowHTML "     <td><font size=""1""><b>Programado</font></td>"
                    ShowHTML "     <td><font size=""1""><b>Executado</font></td>"
                    ShowHTML "     <td><font size=""1""><b>Fase atual</font></td>"
                    If p_prioridade = "" Then ShowHTML "<td><font size=""1""><b>Prioridade</font></td>" End If
                    ShowHTML "   </tr>"
                    w_linha = w_linha + 1
                    While Not RS3.EOF
                      'If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
                      w_cor = conTrBgColor
                      ShowHTML " <tr bgcolor=""" & w_cor & """ valign=""top"">"
                      ShowHTML "   <td nowrap><font size=""1"">"
                      If RS3("concluida") = "N" Then
                         If RS3("fim") < Date() Then
                            ShowHTML "           <img src=""" & conImgAtraso & """ border=0 width=15 heigth=15 align=""center"">"
                         ElseIf RS3("aviso_prox_conc") = "S" and (RS3("aviso") <= Date()) Then
                            ShowHTML "           <img src=""" & conImgAviso & """ border=0 width=15 height=15 align=""center"">"
                         Else
                            ShowHTML "           <img src=""" & conImgNormal & """ border=0 width=15 height=15 align=""center"">"
                         End IF
                      Else
                         If RS3("fim") < Nvl(RS3("fim_real"),RS3("fim")) Then
                            ShowHTML "           <img src=""" & conImgOkAtraso & """ border=0 width=15 heigth=15 align=""center"">"
                         Else
                            ShowHTML "           <img src=""" & conImgOkNormal & """ border=0 width=15 height=15 align=""center"">"
                         End IF
                      End If
                      If w_tipo_rel = "WORD" Then
                         ShowHTML "" & RS3("sq_siw_solicitacao") & "&nbsp;"
                      Else
                         ShowHTML "        <A class=""HL"" HREF=""" & w_dir & "Tarefas.asp?par=Visual&R=" & w_pagina & par & "&O=L&w_chave=" & RS3("sq_siw_solicitacao") & "&w_tipo=&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """ TARGET=""VisualTarefa"" title=""Exibe as informações desta tarefa."">" & RS3("sq_siw_solicitacao") & "&nbsp;</a>"
                      End If
                      ShowHTML "   </font></td>"  
                      ShowHTML "   <td><font size=""1"">" & Nvl(RS3("assunto"),"-") & "</td>"
                      ShowHTML "   <td><font size=""1"">" & RS3("nm_solic") & "</td>"
                      ShowHTML "   <td><font size=""1"">" & Nvl(RS3("proponente"),"---") & "</td>"
                      ShowHTML "   <td align=""center""><font size=""1"">&nbsp;" & Nvl(FormatDateTime(RS3("fim"),2),"-") & "</td>"
                      ShowHTML "   <td align=""right""><font size=""1"">" & FormatNumber(RS3("valor"),2) & "&nbsp;</td>"
                      ShowHTML "   <td align=""right""><font size=""1"">" & FormatNumber(RS3("custo_real"),2) & "&nbsp;</td>"
                      ShowHTML "   <td nowrap><font size=""1"">" & RS3("nm_tramite") & "</td>"
                      If p_prioridade = "" Then ShowHTML "<td nowrap><font size=""1"">" & RetornaPrioridade(RS3("prioridade")) & "</td>" End If
                      ShowHTML " </tr>"
                      w_linha = w_linha + 1
                      RS3.MoveNext
                    Wend
                 End If
              End If
              ShowHTML "       </table>"
           End If
           If p_sq_unidade_resp > "" Then
              ShowHTML "     <tr><td colspan=" & w_col_word & ">"
              ShowHTML "       <table border=1 width=""100%"">"
              DB_GetLinkData RS1, w_cliente, "ISACAD"
              DB_GetSolicList_IS RS2, RS1("sq_menu"), w_usuario, RS1("sigla"), 4, _
                 null, null, null, null, null, null, null, null, null, null, RS("sq_siw_solicitacao"), null, _
                 null, null, null, null, null, null, null, null, null, null, null, null, _
                 null, RS("chave"), null, w_ano
              RS2.sort = "fim, prioridade" 
              If RS2.EOF Then
                 ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td align=""left""><font size=""1""><b>Não foi informado a área planejamento.</b></td></tr>"
                 w_linha = w_linha + 1
              Else
                 ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
                 ShowHTML "        <td align=""left""><font size=""1""><b>Área planejamento</font></td>"
                 ShowHTML "      </tr>"
                 w_linha = w_linha + 1
                 While Not RS2.EOF
                    ShowHTML "   <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
                    ShowHTML "     <td align=""left""><font size=""1""><b>"& RS2("nm_unidade_resp") &"</font></td>"
                    ShowHTML "   </tr>"
                    w_linha = w_linha + 1
                    RS2.MoveNext
                  Wend
              End If
              ShowHTML "       </table>"
           End If
        Else
           ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
           ShowHTML "          <td colspan=" & w_col_word & " align=""center""><font size=""1""><b>Não foram encontrados registros.</td>"
           w_linha = w_linha + 1
        End If
        ShowHTML "           </tr>"
        w_acao_aprovado = w_acao_aprovado   + cDbl(Nvl(RS("aprovado"),0.00))
        w_acao_saldo    = w_acao_saldo      + cDbl(Nvl(RS("aprovado"),0.00))-cDbl(Nvl(RS("empenhado"),0.00))
        w_acao_empenhado= w_acao_empenhado  + cDbl(Nvl(RS("empenhado"),0.00))
        w_acao_liquidado= w_acao_liquidado  + cDbl(Nvl(RS("liquidado"),0.00))
        w_acao_liquidar = w_acao_liquidar   + cDbl(Nvl(RS("empenhado"),0.00))-cDbl(Nvl(RS("liquidado"),0.00))
        
        w_tot_aprovado  = w_tot_aprovado   + cDbl(Nvl(RS("aprovado"),0.00))
        w_tot_saldo     = w_tot_saldo      + cDbl(Nvl(RS("aprovado"),0.00))-cDbl(Nvl(RS("empenhado"),0.00))
        w_tot_empenhado = w_tot_empenhado  + cDbl(Nvl(RS("empenhado"),0.00))
        w_tot_liquidado = w_tot_liquidado  + cDbl(Nvl(RS("liquidado"),0.00))
        w_tot_liquidar  = w_tot_liquidar   + cDbl(Nvl(RS("empenhado"),0.00))-cDbl(Nvl(RS("liquidado"),0.00))
        RS.MoveNext
      wend
      If Instr(p_campos,"aprovado") or Instr(p_campos,"saldo") or Instr(p_campos,"empenhado") or Instr(p_campos,"liquidado") or Instr(p_campos,"liquidar") Then
         If p_sq_isprojeto > " " Then
            ShowHTML "       <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
            ShowHTML "         <td colspan=" & w_col & " align=""right""><font size=""1""><b>Totais do programa interno </b></td>"
            If Instr(p_campos,"aprovado")  Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_aprovado,2) & "</td>" End If
            If Instr(p_campos,"empenhado") Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_empenhado,2) & "</td>" End If
            If Instr(p_campos,"saldo")     Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_saldo,2) & "</td>" End If
            If Instr(p_campos,"liquidado") Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_liquidado,2) & "</td>" End If
            If Instr(p_campos,"liquidar")  Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_acao_liquidar,2) & "</td>" End If
            ShowHTML "       </tr>"
            w_linha = w_linha + 1
         End If
         ShowHTML "          <tr bgcolor=""" & conTrBgColor & """ height=5><td colspan=" &  w_col & "></td></tr>"
         ShowHTML "          <tr bgcolor=""" & conTrBgColor & """ valign=""center"" height=30>"
         ShowHTML "            <td colspan=" & w_col & " align=""right""><font size=""2""><b>Totais do relatório</td>"
         If Instr(p_campos,"aprovado")  Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_tot_aprovado,2) & "</td>" End If
         If Instr(p_campos,"empenhado") Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_tot_empenhado,2) & "</td>" End If
         If Instr(p_campos,"saldo")     Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_tot_saldo,2) & "</td>" End If
         If Instr(p_campos,"liquidado") Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_tot_liquidado,2) & "</td>" End If
         If Instr(p_campos,"liquidar")  Then ShowHTML "        <td align=""right""><font size=""1"">" & FormatNumber(w_tot_liquidar,2) & "</td>" End If
         ShowHTML "          </tr>"
         w_linha = w_linha + 1
      End If
    End If
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    ShowHTML "    </table>"
    ShowHTML "</center></div>"
    DesconectaBD
  ElseIf O = "P" Then
    AbreForm "Form", w_dir & w_pagina & par, "POST", "return(Validacao(this));", "Tabela Projetos",P1,P2,P3,P4,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "          <tr>"
    SelecaoIsProjeto "<u>P</u>rograma interno:", "P", null, p_sq_isprojeto, null, "p_sq_isprojeto", null, "onchange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='p_sq_isprojeto'; document.Form.target=''; document.Form.O.value='P'; document.Form.submit();"""
    ShowHTML "          </tr>"
    ShowHTML "          <tr>"
    SelecaoAcao "<u>A</u>ção:", "A", null, w_cliente, w_ano, null, null, null, null, "p_siw_solic", "PROJETO", null, p_sq_isprojeto
    ShowHTML "          </tr>"
    ShowHTML "      <tr><td><font size=""1""><b><u>R</u>esponsável:</b><br><input " & w_disabled & " accesskey=""R"" type=""text"" name=""p_responsavel"" class=""sti"" SIZE=""40"" MAXLENGTH=""60"" VALUE=""" & p_responsavel & """></td>"
    ShowHTML "      <tr><td colspan=3><table border=0 width=""100%"" cellspacing=0 cellpadding=0><tr valign=""top"">"
    SelecaoPrioridade "<u>P</u>rioridade das tarefas:", "P", "Informe a prioridade da tarefa.", p_prioridade, null, "p_prioridade", null, null
    ShowHTML "          <td><font size=""1""><b>Exibir somente tarefas em atraso?</b><br><input " & w_Disabled & " type=""radio"" name=""p_tarefas_atraso"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""p_tarefas_atraso"" value="""" checked> Não"
    ShowHTML "          </table>"
    ShowHTML "      <tr><td colspan=3><table border=0 width=""100%"" cellspacing=0 cellpadding=0><tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Selecionada SPI/MP?</b><br>"
    If p_selecao_mp = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecao_mp"" value=""S"" checked> Sim <input " & w_Disabled & " type=""radio"" name=""" & p_selecao_mp & """ value=""N""> Não <input " & w_Disabled & " type=""radio"" name=""p_selecao_mp"" value=""""> Independe"
    ElseIf p_selecao_mp = "N" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecao_mp"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""p_selecao_mp"" value=""N"" checked> Não <input " & w_Disabled & " type=""radio"" name=""p_selecao_mp"" value=""""> Independe"
    Else
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecao_mp"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""p_selecao_mp"" value=""N""> Não <input " & w_Disabled & " type=""radio"" name=""p_selecao_mp"" value="""" checked> Independe"
    End If
    ShowHTML "          <td><font size=""1""><b>Selecionada SE/SEPPIR?</b><br>"
    If p_selecao_se = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecao_se"" value=""S"" checked> Sim <input " & w_Disabled & " type=""radio"" name=""" & p_selecao_se & """ value=""N""> Não <input " & w_Disabled & " type=""radio"" name=""p_selecao_se"" value=""""> Independe"
    ElseIf p_selecao_se = "N" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecao_se"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""p_selecao_se"" value=""N"" checked> Não <input " & w_Disabled & " type=""radio"" name=""p_selecao_se"" value=""""> Independe"
    Else
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecao_se"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""p_selecao_se"" value=""N""> Não <input " & w_Disabled & " type=""radio"" name=""p_selecao_se"" value="""" checked> Independe"
    End If
    ShowHTML "          </table>"
    ShowHTML "      <tr><td colspan=3><table border=0 width=""100%"" cellspacing=0 cellpadding=0><tr valign=""top"">"
    ShowHTML "      <tr><td colspan=2><font size=1><b>Campos a serem exibidos"
    ShowHTML "      <tr>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""p_campos"" value=""responsavel""> Responsável</td>"
    'ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""p_campos"" value=""aprovado""> Aprovado</td>"
    'ShowHTML "      <tr>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""p_campos"" value=""email""> e-Mail</td>"
    'ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""p_campos"" value=""saldo""> Saldo</td>"
    ShowHTML "      <tr>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""p_campos"" value=""telefone""> Telefone</td>"
    'ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""p_campos"" value=""liquidado""> Liquidado</td>"
    'ShowHTML "      <tr>"
    'ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""p_campos"" value=""liquidar""> A liquidar</td>"
    'ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""p_campos"" value=""empenhado""> Empenhado</td>"    
    'ShowHTML "      <tr>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""w_marca_campos"" value="""" onClick=""javascript:MarcaTodosCampos();"" TITLE=""Marca todos os itens da relação""> Todos</td>"    
    ShowHTML "      <tr><td colspan=2><font size=1><b>Blocos adicionais"
    ShowHTML "      <tr>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""p_metas"" value=""metas""> Metas físicas</td>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""p_tarefas"" value=""tarefas""> Tarefas</td>"
    ShowHTML "      <tr>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""p_sq_unidade_resp"" value=""unidade""> Área planejamento</td>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""w_marca_bloco"" value="""" onClick=""javascript:MarcaTodosBloco();"" TITLE=""Marca todos os itens da relação""> Todos</td>"    
    ShowHTML "     </table>"    
    ShowHTML "    <table width=""90%"" border=""0"">"            
    ShowHTML "      <tr><td align=""center""><hr>"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Exibir"">"
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
  If w_tipo_rel <> "WORD" Then
     Rodape
  End If

  Set w_titulo                  = Nothing 
  Set p_campos                  = Nothing 
  Set p_metas                   = Nothing 
  Set p_tarefas                 = Nothing 
  Set w_logo                    = Nothing 
  Set w_atual                   = Nothing 
  Set w_acao_aprovado           = Nothing 
  Set w_acao_saldo              = Nothing 
  Set w_acao_empenhado          = Nothing 
  Set w_acao_liquidado          = Nothing 
  Set w_acao_liquidar           = Nothing
  Set w_tot_aprovado            = Nothing 
  Set w_tot_saldo               = Nothing 
  Set w_tot_empenhado           = Nothing 
  Set w_tot_liquidado           = Nothing 
  Set w_tot_liquidar            = Nothing
  Set p_sq_isprojeto            = Nothing 
  Set p_selecao_mp              = Nothing 
  Set p_selecao_se              = Nothing 
  Set p_responsavel             = Nothing 
  Set p_sq_unidade_resp         = Nothing
  Set p_ordena                  = Nothing
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Relatório dos programas PPA
REM -------------------------------------------------------------------------
Sub Rel_Programa
  Dim p_cd_programa, p_selecao_mp, p_selecao_se
  Dim p_responsavel, p_sq_unidade_resp
  Dim w_col, w_col_word, p_campos, p_indicador, w_logo, w_titulo 
  Dim w_tipo_rel, w_linha, w_pag, p_ordena
  
  w_chave           = Request("w_chave")
  w_troca           = Request("w_troca")
  w_tipo_rel        = uCase(trim(Request("w_tipo_rel")))
  
  p_cd_programa              = ucase(Trim(Request("p_cd_programa")))
  p_responsavel              = ucase(Trim(Request("p_responsavel")))
  p_sq_unidade_resp          = ucase(Trim(Request("p_sq_unidade_resp")))
  p_selecao_mp               = ucase(Trim(Request("p_selecao_mp")))
  p_selecao_se               = ucase(Trim(Request("p_selecao_se")))
  p_campos                   = Request("p_campos")
  p_indicador                = Request("p_indicador")
  p_ordena                   = Request("p_ordena")
  
  If O = "L" Then
     ' Recupera o logo do cliente a ser usado nas listagens
     DB_GetCustomerData RS, w_cliente
     If RS("logo") > "" Then
         w_logo = "\img\logo" & Mid(RS("logo"),Instr(RS("logo"),"."),30)
     End If
     DesconectaBD
     ' Recupera todos os registros para a listagem
     DB_GetProgramaPPA_IS RS, p_cd_programa, w_cliente, w_ano, null, null
     If p_responsavel > "" Then
        RS.Filter = "nm_gerente_programa like '%" &p_responsavel& "%'"
     End If
     If Nvl(p_ordena,"") > "" Then RS.Sort = p_ordena Else RS.Sort = "ds_programa"
  End If
  
  If w_tipo_rel = "WORD" Then
     HeaderWord null
     w_pag   = 1
     w_linha = 5
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "WORD") & """><TD ALIGN=""RIGHT"" NOWRAP><B><FONT SIZE=4 COLOR=""#000000"">"
     ShowHTML "Relatório Analítico - Programas PPA 2004 - 2007 Exercício " & w_ano
     ShowHTML "</FONT><TR><TD WIDTH=""50%"" ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
     ShowHTML "<TR><TD COLSPAN=""2"" ALIGN=""RIGHT""><B><FONT SIZE=2 COLOR=""#000000"">Página: " & w_pag & "</B></TD></TR>"
     ShowHTML "</TD></TR>"
     ShowHTML "</FONT></B></TD></TR></TABLE>"
  Else
     Cabecalho
     ShowHTML "<HEAD>"
     ShowHTML "<TITLE>Relatório Analítico - Programas PPA 2004 - 2007 Exercício " & w_ano & "</TITLE>"
     If InStr("P",O) > 0 Then
        ScriptOpen "JavaScript"
        ValidateOpen "Validacao"
        Validate "p_cd_programa", "Programa", "SELECT", "", "1", "18", "", "1"
        Validate "p_responsavel", "Responsável", "1", "", "2", "60", "1", "1"
        ValidateClose
        ShowHTML "  function MarcaTodosCampos() {"
        ShowHTML "    if (document.Form.w_marca_campos.checked==true) "
        ShowHTML "       for (i=0; i < 4; i++) {"
        ShowHTML "         document.Form.p_campos[i].checked=true;"
        ShowHTML "    } else { "
        ShowHTML "       for (i=0; i < 4; i++) {"
        ShowHTML "         document.Form.p_campos[i].checked=false;"
        ShowHTML "       } "
        ShowHTML "    } "
        ShowHTML "  }"
        ShowHTML "  function MarcaTodosBloco() {"
        ShowHTML "    if (document.Form.w_marca_bloco.checked==true) {"
        ShowHTML "         document.Form.p_indicador.checked=true;"
        ShowHTML "         document.Form.p_sq_unidade_resp.checked=true;"
        ShowHTML "    } else { "
        ShowHTML "         document.Form.p_indicador.checked=false;"
        ShowHTML "         document.Form.p_sq_unidade_resp.checked=false;"
        ShowHTML "    } "
        ShowHTML "  }"        
        ScriptClose
     End If
     ShowHTML "</HEAD>"
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     If O = "L" Then
        BodyOpenClean "onLoad='document.focus()';"
        ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "EMBED") & """><TD ALIGN=""RIGHT"" NOWRAP><B><FONT SIZE=4 COLOR=""#000000"">"
        ShowHTML "Relatório Analítico - Programas PPA 2004 - 2007 Exercício " & w_ano
        ShowHTML "</FONT><TR><TD WIDTH=""50%"" ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
        ShowHTML "&nbsp;&nbsp;<IMG BORDER=0 ALIGN=""CENTER"" TITLE=""Gerar word"" SRC=""images/word.gif"" onClick=""window.open('" & w_pagina & par & "&R=" & w_pagina & par & "&O=L&w_chave=" & w_chave & "&w_tipo_rel=word&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") &"','VisualRelPPAWord','menubar=yes resizable=yes scrollbars=yes');"">"
        ShowHTML "&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Imprimir"" SRC=""images/impressora.jpg"" onClick=""window.print();"">"
        ShowHTML "</TD></TR>"
        ShowHTML "</FONT></B></TD></TR></TABLE>"
     Else
        BodyOpen "onLoad='document.Form.p_cd_programa.focus();'"
        ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
     End If
     ShowHTML "<HR>"
  End If
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    w_col      = 2
    w_col_word = 2
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    w_filtro = ""
    If p_responsavel           > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Responsável<td><font size=1>[<b>" & p_responsavel & "</b>]"                   End If
    If p_selecao_mp            > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Selecionada SPI/MP<td><font size=1>[<b>" & p_selecao_mp & "</b>]"             End If
    If p_selecao_se            > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Selecionada SE/SEPPIR<td><font size=1>[<b>" & p_selecao_se & "</b>]"              End If
    ShowHTML "<tr><td align=""left"" colspan=2>"
    If w_filtro                > "" Then ShowHTML "<table border=0><tr valign=""top""><td><font size=1><b>Filtro:</b><td nowrap><font size=1><ul>" & w_filtro & "</ul></tr></table>"              End If
    ShowHTML "    <td align=""right"" valign=""botton""><font size=""1""><b>Registros listados: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    If w_tipo_rel = "WORD" Then
       ShowHTML "          <td><font size=""1""><b>Código</font></td>"
    Else
       ShowHTML "     <td><font size=""1""><b>" & LinkOrdena("Código","cd_programa") & "</font></td>"
    End If
    If w_tipo_rel = "WORD" Then
       ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
    Else
       ShowHTML "     <td><font size=""1""><b>" & LinkOrdena("Nome","ds_programa") & "</font></td>"
    End If
    If Instr(p_campos,"responsavel") Then
       If w_tipo_rel = "WORD" Then
          ShowHTML "          <td><font size=""1""><b>Gerente programa</font></td>"
       Else
          ShowHTML "     <td><font size=""1""><b>" & LinkOrdena("Gerente programa","nm_gerente_programa") & "</font></td>"
       End If
       w_col      = w_col      + 1
       w_col_word = w_col_word + 1 
    End If
    If Instr(p_campos,"email") Then
       If w_tipo_rel = "WORD" Then
          ShowHTML "          <td><font size=""1""><b>e-Mail</font></td>"
       Else
          ShowHTML "     <td><font size=""1""><b>" & LinkOrdena("e-Mail","em_gerente_programa") & "</font></td>"
       End If
       w_col      = w_col      + 1
       w_col_word = w_col_word + 1 
    End If
    If Instr(p_campos,"telefone") Then
       If w_tipo_rel = "WORD" Then
          ShowHTML "          <td><font size=""1""><b>Telefone</font></td>"
       Else
          ShowHTML "     <td><font size=""1""><b>" & LinkOrdena("Telefone","fn_gerente_programa") & "</font></td>"
       End If
       w_col      = w_col      + 1
       w_col_word = w_col_word + 1 
    End If
    If Instr(p_campos,"orgao") Then
       If w_tipo_rel = "WORD" Then
          ShowHTML "          <td><font size=""1""><b>Órgão</font></td>"
       Else
          ShowHTML "     <td><font size=""1""><b>" & LinkOrdena("Órgao","ds_orgao") & "</font></td>"
       End If
       w_col      = w_col      + 1
       w_col_word = w_col_word + 1 
    End If    
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=" & w_col & " align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
        If w_linha > 22 and w_tipo_rel = "WORD" Then
           ShowHTML "    </table>"
           ShowHTML "  </td>"
           ShowHTML "</tr>"
           ShowHTML "</table>"
           ShowHTML "</center></div>"
           ShowHTML "    <br style=""page-break-after:always"">"
           w_linha = 5
           w_pag   = w_pag + 1
           ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "WORD") & """><TD ALIGN=""RIGHT""><B><FONT SIZE=4 COLOR=""#000000"">"
           ShowHTML "Ações PPA"
           ShowHTML "</FONT><TR><TD WIDTH=""50%"" ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
           ShowHTML "<TR><TD COLSPAN=""2"" ALIGN=""RIGHT""><B><FONT SIZE=2 COLOR=""#000000"">Página: " & w_pag & "</B></TD></TR>"
           ShowHTML "</TD></TR>"
           ShowHTML "</FONT></B></TD></TR></TABLE>"
           ShowHTML "<div align=center><center>"
           ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
           ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
           w_filtro = ""
           If p_responsavel           > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Responsável<td><font size=1>[<b>" & p_responsavel & "</b>]"                   End If
           If p_selecao_mp            > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Selecionada SPI/MP<td><font size=1>[<b>" & p_selecao_mp & "</b>]"             End If
           If p_selecao_se            > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Selecionada SE/SEPPIR<td><font size=1>[<b>" & p_selecao_se & "</b>]"              End If
           ShowHTML "<tr><td align=""left"" colspan=2>"
           If w_filtro                > "" Then ShowHTML "<table border=0><tr valign=""top""><td><font size=1><b>Filtro:</b><td nowrap><font size=1><ul>" & w_filtro & "</ul></tr></table>"              End If
           ShowHTML "    <td align=""right"" valign=""botton""><font size=""1""><b>Registros listados: " & RS.RecordCount
           ShowHTML "<tr><td align=""center"" colspan=3>"
           ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
           ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
           ShowHTML "          <td><font size=""1""><b>Código</font></td>"
           ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
           If Instr(p_campos,"responsavel") Then ShowHTML "          <td><font size=""1""><b>Gerente programa</font></td>" End If
           If Instr(p_campos,"email")       Then ShowHTML "          <td><font size=""1""><b>e-Mail</font></td>"      End If
           If Instr(p_campos,"telefone")    Then ShowHTML "          <td><font size=""1""><b>Telefone</font></td>"    End If
           If Instr(p_campos,"orgao")       Then ShowHTML "          <td><font size=""1""><b>Órgão</font></td>"       End If
           ShowHTML "        </tr>"
        End If
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ valign=""top"">"
        ShowHTML "        <td nowrap><font size=""1"">" & RS("cd_programa") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("ds_programa") & "</td>"
        If Instr(p_campos,"responsavel") Then ShowHTML "        <td><font size=""1"">" & Nvl(RS("nm_gerente_programa"),"---") & "</td>" End If
        If Instr(p_campos,"email")       Then ShowHTML "        <td><font size=""1"">" & Nvl(RS("em_gerente_programa"),"---") & "</td>" End If
        If Instr(p_campos,"telefone")    Then ShowHTML "        <td><font size=""1"">" & Nvl(RS("fn_gerente_programa"),"---") & "</td>" End If
        If Instr(p_campos,"orgao")       Then ShowHTML "        <td><font size=""1"">" & Nvl(RS("ds_orgao"),"---") & "</td>"            End If
        w_linha = w_linha + 1
        ShowHTML "</tr>"
        If p_indicador > "" Then
           ShowHTML "      <tr><td><td colspan=" & w_col_word & "><table border=1 width=""100%"">"
           DB_GetLinkData RS1, w_cliente, "ISPCAD"
           DB_GetSolicList_IS RS2, RS1("sq_menu"), w_usuario, RS1("sigla"), 4, _
              null, null, null, null, null, null, null, null, null, null, null, null, _
              null, null, null, null, null, null, null, null, null, null, null, null, _
              RS("cd_programa"), null, null, w_ano
           If p_responsavel > "" Then
              RS2.Filter = "nm_gerente_programa like '%" &p_responsavel& "%'"
           End If
           RS2.sort = "fim" 
           If RS2.EOF Then
              ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=" & w_col_word & " align=""center""><font size=""1""><b>Não foram encontrados registros(indicadores).</b></td></tr>"
              w_linha = w_linha + 1
           Else
              DB_GetSolicIndic_IS RS3, RS2("sq_siw_solicitacao"), null, "LISTA"
              RS3.Sort = "ordem"
              If RS3.EOF Then ' Se não foram selecionados registros, exibe mensagem
                 ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=" & w_col_word & " align=""center""><font size=""1""><b>Não foram encontrados registros(indicadores).</b></td></tr>"
                 w_linha = w_linha + 1
              Else
                 ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
                 ShowHTML "          <td><font size=""1""><b>Indicador</font></td>"
                 ShowHTML "          <td><font size=""1""><b>PPA</font></td>"
                 ShowHTML "          <td><font size=""1""><b>Indice<br>referência</font></td>"                 
                 ShowHTML "          <td><font size=""1""><b>Indice<br>programado</font></td>"
                 ShowHTML "          <td><font size=""1""><b>Indice<br>apurado</font></td>"
                 ShowHTML "          <td><font size=""1""><b>Data<br>apuracao</font></td>"
                 ShowHTML "          <td><font size=""1""><b>Unidade<br>medida</font></td>"
                 ShowHTML "        </tr>"
                 w_linha = w_linha + 1
                 While Not RS3.EOF
                    ShowHtml Indicadorlinha(RS2("sq_siw_solicitacao"), Rs3("sq_indicador"), Rs3("titulo"), Rs3("valor_referencia"), Rs3("quantidade"), Rs3("valor_apurado"), Rs3("apuracao_indice"), RS3("nm_unidade_medida"), null, "<b>", "N", "PROJETO", RS3("cd_indicador"))
                    RS3.MoveNext
                    w_linha = w_linha + 1
                 Wend
              End If
           End If
           ShowHTML "        </table>"
        End If
        If p_sq_unidade_resp > "" Then
           ShowHTML "      <tr><td><td colspan=" & w_col_word & "><table border=1 width=""100%"">"
           DB_GetLinkData RS1, w_cliente, "ISPCAD"
           DB_GetSolicList_IS RS2, RS1("sq_menu"), w_usuario, RS1("sigla"), 4, _
              null, null, null, null, null, null, null, null, null, null, null, null, _
              null, null, null, null, null, null, null, null, null, null, null, null, _
              RS("cd_programa"), null, null, w_ano
           If p_responsavel > "" Then
              RS2.Filter = "nm_gerente_programa like '%" &p_responsavel& "%'"
           End If
           RS2.sort = "fim" 
           If RS2.EOF Then
              w_linha = w_linha + 1
              ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td align=""left""><font size=""1""><b>Não foi informado a Área de planejamento.</b></td></tr>"
           Else
              ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
              ShowHTML "          <td align=""left""><font size=""1""><b>Área planejamento<font></td>"
              ShowHTML "        </tr>"
              w_linha = w_linha + 1
              While Not RS2.EOF
                 ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
                 ShowHTML "          <td align=""left""><font size=""1""><b>"& RS2("nm_unidade_resp") & "</font></td>"
                 ShowHTML "        </tr>"
                 RS2.MoveNext
                 w_linha = w_linha + 1
               Wend
           End If
           ShowHTML "        </table>"
        End If
    ' End If
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    DesconectaBD
  ElseIf O = "P" Then
    AbreForm "Form", w_dir & w_pagina & par, "POST", "return(Validacao(this));", "Programa",P1,P2,P3,P4,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr>"
    SelecaoProgramaPPA "<u>P</u>rograma PPA:", "P", null, w_cliente, w_ano, p_cd_programa, "p_cd_programa", null, null, w_menu
    ShowHTML "      <tr><td><font size=""1""><b><u>R</u>esponsável:</b><br><input " & w_disabled & " accesskey=""R"" type=""text"" name=""p_responsavel"" class=""sti"" SIZE=""40"" MAXLENGTH=""60"" VALUE=""" & p_responsavel & """></td>"
    ShowHTML "      <tr><td colspan=3><table border=0 width=""100%"" cellspacing=0 cellpadding=0><tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Selecionada pela SPI/MP?</b><br>"
    If p_selecao_mp = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecao_mp"" value=""S"" checked> Sim <input " & w_Disabled & " type=""radio"" name=""" & p_selecao_mp & """ value=""N""> Não <input " & w_Disabled & " type=""radio"" name=""p_selecao_mp"" value=""""> Independe"
    ElseIf p_selecao_mp = "N" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecao_mp"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""p_selecao_mp"" value=""N"" checked> Não <input " & w_Disabled & " type=""radio"" name=""p_selecao_mp"" value=""""> Independe"
    Else
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecao_mp"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""p_selecao_mp"" value=""N""> Não <input " & w_Disabled & " type=""radio"" name=""p_selecao_mp"" value="""" checked> Independe"
    End If
    ShowHTML "          <td><font size=""1""><b>Selecionada pela SE/SEPPIR?</b><br>"
    If p_selecao_se = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecao_se"" value=""S"" checked> Sim <input " & w_Disabled & " type=""radio"" name=""" & p_selecao_se & """ value=""N""> Não <input " & w_Disabled & " type=""radio"" name=""p_selecao_se"" value=""""> Independe"
    ElseIf p_selecao_se = "N" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecao_se"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""p_selecao_se"" value=""N"" checked> Não <input " & w_Disabled & " type=""radio"" name=""p_selecao_se"" value=""""> Independe"
    Else
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecao_se"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""p_selecao_se"" value=""N""> Não <input " & w_Disabled & " type=""radio"" name=""p_selecao_se"" value="""" checked> Independe"
    End If
    ShowHTML "          </table>"
    ShowHTML "      <tr><td colspan=3><table border=0 width=""100%"" cellspacing=0 cellpadding=0><tr valign=""top"">"
    ShowHTML "      <tr><td colspan=2><font size=1><b>Campos a serem exibidos"
    ShowHTML "      <tr>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""p_campos"" value=""responsavel""> Gerente programa</td>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""p_campos"" value=""email""> e-Mail</td>"
    ShowHTML "      <tr>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""p_campos"" value=""telefone""> Telefone</td>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""p_campos"" value=""orgao""> Órgão</td>"
    ShowHTML "      <tr>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""w_marca_campos"" value="""" onClick=""javascript:MarcaTodosCampos();"" TITLE=""Marca todos os itens da relação""> Todos</td>"
    ShowHTML "      <tr><td colspan=2><font size=1><b>Blocos adicionais"
    ShowHTML "      <tr>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""p_indicador"" value=""Indicador""> Indicadores</td>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""p_sq_unidade_resp"" value=""unidade""> Área planejamento</td>"
    ShowHTML "      <tr>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""w_marca_bloco"" value="""" onClick=""javascript:MarcaTodosBloco();"" TITLE=""Marca todos os itens da relação""> Todos</td>"    
    ShowHTML "     </table>"    
    ShowHTML "    <table width=""90%"" border=""0"">"            
    ShowHTML "      <tr><td align=""center""><hr>"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Exibir"">"
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
  
  If w_tipo_rel <> "WORD" Then
     Rodape
  End If
  
  Set w_titulo                  = Nothing 
  Set p_campos                  = Nothing 
  Set p_indicador               = Nothing 
  Set w_logo                    = Nothing 
  Set p_cd_programa             = Nothing 
  Set p_selecao_mp              = Nothing 
  Set p_selecao_se              = Nothing 
  Set p_responsavel             = Nothing 
  Set p_sq_unidade_resp         = Nothing
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Relatório Sintético de Programas Internos
REM -------------------------------------------------------------------------
Sub Rel_Sintetico_PR
  Dim p_sq_isprojeto, p_selecao_mp, p_selecao_se, p_siw_solic
  Dim p_responsavel, p_sq_unidade_resp, p_prioridade
  Dim w_atual, w_logo, w_titulo, w_sq_siw_solicitacao
  Dim w_tipo_rel, w_quantitativo_total
  Dim p_programada, p_exequivel, p_fim_previsto, p_atraso, p_tarefas_atraso
  Dim w_teste_metas, w_teste_acoes, w_visao, RSquery, w_cont
  
  w_chave           = Request("w_chave")
  w_troca           = Request("w_troca")
  w_tipo_rel        = uCase(trim(Request("w_tipo_rel")))
  
  p_sq_isprojeto             = ucase(Trim(Request("p_sq_isprojeto")))
  p_siw_solic                = ucase(Trim(Request("p_siw_solic")))
  p_responsavel              = ucase(Trim(Request("p_responsavel")))
  p_prioridade               = ucase(Trim(Request("p_prioridade")))
  p_sq_unidade_resp          = ucase(Trim(Request("p_sq_unidade_resp")))
  p_selecao_mp               = ucase(Trim(Request("p_selecao_mp")))
  p_selecao_se               = ucase(Trim(Request("p_selecao_se")))
  p_programada               = ucase(Trim(Request("p_programada")))
  p_exequivel                = ucase(Trim(Request("p_exequivel")))
  p_fim_previsto             = ucase(Trim(Request("p_fim_previsto")))
  p_atraso                   = ucase(Trim(Request("p_atraso")))
  p_tarefas_atraso           = ucase(Trim(Request("p_tarefas_atraso")))
  
  w_cont = 0 
  
  If O = "L" Then
     ' Recupera o logo do cliente a ser usado nas listagens
     DB_GetCustomerData RS, w_cliente
     If RS("logo") > "" Then
         w_logo = "\img\logo" & Mid(RS("logo"),Instr(RS("logo"),"."),30)
     End If
     DesconectaBD
     ' Recupera todos os registros para a listagem
     DB_GetProjeto_IS RS, p_sq_isprojeto, w_cliente, null, null, p_responsavel, null, null, null, null, null, p_selecao_mp, p_selecao_se, null, p_siw_solic
     RS.Sort = "ordem"
  End If
  
  If w_tipo_rel = "WORD" Then
     HeaderWord null
     w_pag   = 1
     w_linha = 8
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "WORD") & """><TD ALIGN=""RIGHT"" NOWRAP><B><FONT SIZE=4 COLOR=""#000000"">"
     ShowHTML "Relatório Sintético - Planos " & w_ano
     ShowHTML "</FONT><TR><TD WIDTH=""50%"" ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
     ShowHTML "<TR><TD COLSPAN=""2"" ALIGN=""RIGHT""><B><FONT SIZE=2 COLOR=""#000000"">Página: " & w_pag & "</B></TD></TR>"
     ShowHTML "</TD></TR>"
     ShowHTML "</FONT></B></TD></TR></TABLE>"
  Else
     Cabecalho
     ShowHTML "<HEAD>"
     ShowHTML "<TITLE>Relatório Sintético - Planos " & w_ano & "</TITLE>"
     If InStr("P",O) > 0 Then
        ScriptOpen "JavaScript"
        ValidateOpen "Validacao"
        Validate "p_sq_isprojeto", "Programa interno", "SELECT", "", "1", "18", "", "1"
        Validate "p_siw_solic", "Ação", "SELECT", "", "1", "18", "", "1"
        Validate "p_responsavel", "Responsável", "1", "", "2", "60", "1", "1"
        ValidateClose
        ScriptClose
     End If
     ShowHTML "</HEAD>"
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     If O = "L" Then
        BodyOpenClean "onLoad='document.focus()';"
        ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "EMBED") & """><TD ALIGN=""RIGHT"" NOWRAP><B><FONT SIZE=4 COLOR=""#000000"">"
        ShowHTML "Relatório Sintético - Planos " & w_ano
        ShowHTML "</FONT><TR><TD WIDTH=""50%"" ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
        ShowHTML "&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Imprimir"" SRC=""images/impressora.jpg"" onClick=""window.print();"">"
        ShowHTML "&nbsp;&nbsp;<IMG BORDER=0 ALIGN=""CENTER"" TITLE=""Gerar word"" SRC=""images/word.gif"" onClick=""window.open('" & w_pagina & par & "&R=" & w_pagina & par & "&O=L&w_chave=" & w_chave & "&w_tipo_rel=word&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") &"','VisualRelPPAWord','menubar=yes resizable=yes scrollbars=yes');"">"
        ShowHTML "</TD></TR>"
        ShowHTML "</FONT></B></TD></TR></TABLE>"
     Else
        BodyOpen "onLoad='document.Form.p_sq_isprojeto.focus()';"
        ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
     End If
     ShowHTML "<HR>"
  End If
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
     ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
     w_filtro = "<tr valign=""top"">"
    If p_responsavel           > "" Then w_filtro = w_filtro & "<td><font size=1>Responsável&nbsp;<font size=1>[<b>" & p_responsavel & "</b>]&nbsp;"                     End If
    If p_prioridade            > "" Then w_filtro = w_filtro & "<td><font size=1>Prioridade&nbsp;<font size=1>[<b>" & RetornaPrioridade(p_prioridade) & "</b>]&nbsp;"    End If
    If p_selecao_mp            > "" Then w_filtro = w_filtro & "<td><font size=1>Selecionada MP&nbsp;<font size=1>[<b>" & p_selecao_mp & "</b>]&nbsp;"             End If
    If p_selecao_se            > "" Then w_filtro = w_filtro & "<td><font size=1>Selecionada Relevante&nbsp;<font size=1>[<b>" & p_selecao_se & "</b>]&nbsp;" End If
    If p_programada            > "" Then w_filtro = w_filtro & "<td><font size=1>Meta PPA&nbsp;<font size=1>[<b>" & p_programada & "</b>]&nbsp;"                         End If
    If p_exequivel             > "" Then w_filtro = w_filtro & "<td><font size=1>Meta será cumprida&nbsp;<font size=1>[<b>" & p_exequivel & "</b>]&nbsp;"                End If
    If p_fim_previsto          > "" Then w_filtro = w_filtro & "<td><font size=1>Metas em atraso&nbsp;<font size=1>[<b>" & p_fim_previsto & "</b>]&nbsp;"                End If
    If p_atraso                > "" Then w_filtro = w_filtro & "<td><font size=1>Ações em atraso&nbsp;<font size=1>[<b>" & p_atraso & "</b>]&nbsp;"                      End If
    If p_tarefas_atraso        > "" Then w_filtro = w_filtro & "<td><font size=1>Ações com tarefas em atraso&nbsp;<font size=1>[<b>" & p_tarefas_atraso & "</b>]&nbsp;"  End If
    ShowHTML "<tr><td align=""center"">"
    If w_filtro                > "" Then ShowHTML "<table border=0 width=""100%""><tr><td width=""25%""><font size=1><b>Filtro:</b><td><font size=1><ul>" & w_filtro & "</ul></tr></table>"                End If
    ShowHTML "<tr><td align=""center"" colspan=""2"">"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td rowspan=""2""><font size=""1""><b>Programa interno</font></td>"
    ShowHTML "          <td rowspan=""2""><font size=""1""><b>Ações Cadastradas</font></td>"
    ShowHTML "          <td rowspan=""1"" colspan=""5""><font size=""1""><b>Metas</font></td>"
    ShowHTML "        </tr>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Produto</font></td>"
    ShowHTML "          <td><font size=""1""><b>Unidade<br>medida</font></td>"
    ShowHTML "          <td><font size=""1""><b>Quantitativo<br>programado</font></td>"
    ShowHTML "          <td><font size=""1""><b>Quantitativo<br>realizado</font></td>"
    ShowHTML "          <td><font size=""1""><b>% Realizado</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
       w_cont = w_cont + 1
       w_linha = w_linha + 1
       ShowHTML "      <tr><td colspan=""7"" align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
       w_atual = 0
       ' Lista os registros selecionados para listagem
       While Not RS.EOF 
          If w_linha > 30 and w_tipo_rel = "WORD" Then
             ShowHTML "    </table>"
             ShowHTML "  </td>"
             ShowHTML "</tr>"
             ShowHTML "</table>"
             ShowHTML "</center></div>"
             ShowHTML "    <br style=""page-break-after:always"">"
             w_linha = 6
             w_pag   = w_pag + 1
             ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "WORD") & """><TD ALIGN=""RIGHT""><B><FONT SIZE=4 COLOR=""#000000"">"
             ShowHTML "Programa interno"
             ShowHTML "</FONT><TR><TD WIDTH=""50%"" ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
             ShowHTML "<TR><TD COLSPAN=""2"" ALIGN=""RIGHT""><B><FONT SIZE=2 COLOR=""#000000"">Página: " & w_pag & "</B></TD></TR>"
             ShowHTML "</TD></TR>"
             ShowHTML "</FONT></B></TD></TR></TABLE>"
             ShowHTML "<div align=center><center>"
             ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
             w_filtro = "<tr valign=""top"">"
             If p_responsavel           > "" Then w_filtro = w_filtro & "<td><font size=1>Responsável&nbsp;<font size=1>[<b>" & p_responsavel & "</b>]&nbsp;"                     End If
             If p_prioridade            > "" Then w_filtro = w_filtro & "<td><font size=1>Prioridade&nbsp;<font size=1>[<b>" & RetornaPrioridade(p_prioridade) & "</b>]&nbsp;"    End If
             If p_selecao_mp            > "" Then w_filtro = w_filtro & "<td><font size=1>Selecionada MP&nbsp;<font size=1>[<b>" & p_selecao_mp & "</b>]&nbsp;"             End If
             If p_selecao_se            > "" Then w_filtro = w_filtro & "<td><font size=1>Selecionada Relevante&nbsp;<font size=1>[<b>" & p_selecao_se & "</b>]&nbsp;" End If
             If p_programada            > "" Then w_filtro = w_filtro & "<td><font size=1>Meta PPA&nbsp;<font size=1>[<b>" & p_programada & "</b>]&nbsp;"                         End If
             If p_exequivel             > "" Then w_filtro = w_filtro & "<td><font size=1>Meta será cumprida&nbsp;<font size=1>[<b>" & p_exequivel & "</b>]&nbsp;"                End If
             If p_fim_previsto          > "" Then w_filtro = w_filtro & "<td><font size=1>Metas em atraso&nbsp;<font size=1>[<b>" & p_fim_previsto & "</b>]&nbsp;"                End If
             If p_atraso                > "" Then w_filtro = w_filtro & "<td><font size=1>Ações em atraso&nbsp;<font size=1>[<b>" & p_atraso & "</b>]&nbsp;"                      End If
             If p_tarefas_atraso        > "" Then w_filtro = w_filtro & "<td><font size=1>Ações com tarefas em atraso&nbsp;<font size=1>[<b>" & p_tarefas_atraso & "</b>]&nbsp;"  End If                    
             ShowHTML "<tr><td align=""center"">"
             If w_filtro                > "" Then ShowHTML "<table border=0 width=""100%""><tr><td width=""25%""><font size=1><b>Filtro:</b><td><font size=1><ul>" & w_filtro & "</ul></tr></table>"                End If
             ShowHTML "    <td align=""right"" valign=""botton""><font size=""1""><b>Registros listados: " & RS.RecordCount
             ShowHTML "<tr><td align=""center"" colspan=""2"">"
             ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
             ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
             ShowHTML "          <td rowspan=""2""><font size=""1""><b>Programa interno</font></td>"
             ShowHTML "          <td rowspan=""2""><font size=""1""><b>Ações Cadastradas</font></td>"
             ShowHTML "          <td rowspan=""1"" colspan=""5""><font size=""1""><b>Metas</font></td>"
             ShowHTML "        </tr>"
             ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
             ShowHTML "          <td><font size=""1""><b>Produto</font></td>"
             ShowHTML "          <td><font size=""1""><b>Unidade<br>medida</font></td>"
             ShowHTML "          <td><font size=""1""><b>Quantitativo<br>programado</font></td>"
             ShowHTML "          <td><font size=""1""><b>Quantitativo<br>realizado</font></td>"
             ShowHTML "          <td><font size=""1""><b>% Realizado</font></td>"
             ShowHTML "        </tr>"
          End If
          'Montagem da lista das ações
           DB_GetLinkData RS1, w_cliente, "ISACAD"
           DB_GetSolicList_IS RS2, RS1("sq_menu"), w_usuario, RS1("sigla"), 4, _
              null, null, null, null, null, null, null, null, null, null, RS("sq_siw_solicitacao"), null, _
              null, null, null, null, null, null, null, null, null, null, null, null, _
              null, RS("chave"), null, w_ano
           RS2.sort = "fim, prioridade" 
          
          'Variarel para o teste de existencia de metas e açoes para visualização no relatorio
          w_teste_metas = 0
          w_teste_acoes = 0 
  
          'Recuperação e verificação das metas das ações de acordo com a visão do usuário
          If Not RS2.EOF Then
             w_teste_acoes = 1
             w_visao = 0 
             If w_visao < 2 Then               
                DB_GetSolicMeta_IS RS3, RS2("sq_siw_solicitacao"), null, "LSTNULL", null, null, null, null, null, null, null
                If p_programada       > "" and p_exequivel    > "" and p_fim_previsto > "" Then
                   RS3.Filter = "programada = '" & p_programada & "' and exequivel = '" & p_exequivel & "' and fim_previsto < '" & Date() & "' and perc_conclusao < 100"
                ElseIf p_programada   > "" and p_exequivel    > "" Then   
                   RS3.Filter = "programada = '" & p_programada & "' and exequivel = '" & p_exequivel & "'"
                ElseIf p_programada   > "" and p_fim_previsto > "" Then
                   RS3.Filter = "programada = '" & p_programada & "' and fim_previsto < '" & Date() & "' and perc_conclusao < 100"
                ElseIf p_fim_previsto > "" and p_exequivel    > "" Then
                   RS3.Filter = "exequivel = '" & p_exequivel & "' and fim_previsto < '" & Date() & "' and perc_conclusao < 100"
                ElseIf p_programada   > "" Then
                   RS3.Filter = "programada = '" & p_programada & "'"
                ElseIf p_exequivel    > "" Then
                   RS3.Filter = "exequivel = '" & p_exequivel & "'"
                ElseIf p_fim_previsto > "" Then
                   RS3.Filter = "fim_previsto < '" & Date() & "' and perc_conclusao < 100"
                End If
                RS3.Sort = "ordem"
                If Not RS3.EOF Then
                   w_teste_metas = 1
                ElseIf p_programada = "" and p_exequivel = "" and p_fim_previsto = "" Then
                   w_teste_metas = 3
                End If
             Else
                w_teste_metas = 0
             End If
          Else
             If RS("sq_siw_solicitacao") > "" Then
                w_teste_acoes = 1
                w_teste_metas = 1
             Else
                w_teste_acoes = 0
             End If
          End If
          
          If w_teste_metas = 1 or w_teste_metas = 3 Then
             'Inicio da montagem da lista das ações e metas de acordo com o filtro
             w_cont = w_cont + 1
             If cDbl(w_atual) <> cDbl(RS("chave")) or p_programada > "" or p_exequivel > "" or p_fim_previsto > "" or p_atraso > ""Then
                ShowHTML "      <tr valign=""top"">"
                ShowHTML "        <td><font size=""1""><b>" & RS("nome") & "</td>"
             Else
                ShowHTML "      <tr valign=""top"">"
                ShowHTML "        <td><font size=""1""><b>&nbsp;</td>"
             End If
             w_linha = w_linha + 1
             w_sq_siw_solicitacao = Nvl(RS("sq_siw_solicitacao"),"")
             If w_sq_siw_solicitacao > "" Then
                If w_tipo_rel = "WORD" Then
                   ShowHTML "        <td><font size=""1""><b>" & RS("titulo") & "</td>"
                Else
                   ShowHTML "        <td><font size=""1""><b><A class=""HL"" HREF=""" & w_dir & "Acao.asp?par=Visual&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=&P1=1&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ TARGET=""VisualAcao"" title=""Exibe as informações da ação."">" & RS("titulo") & "</a></td>"
                End If
                If RS2.EOF Then
                   ShowHTML "      <td colspan=""5"" align=""center""><font size=""1""><b>Não foram encontrados registros.<b></td>"
                Else
                   If RS3.EOF Then ' Se não foram selecionados registros, exibe mensagem
                      ShowHTML "      <td colspan=""5"" align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
                   Else
                      If w_tipo_rel = "WORD" Then
                         ShowHTML "      <td><font size=""1"">" & RS3("titulo") & "</td>"
                      Else
                         ShowHTML "      <td><font size=""1""><A class=""HL"" HREF=""#"" onClick=""window.open('Acao.asp?par=AtualizaMeta&O=V&w_chave=" & RS2("sq_siw_solicitacao") & "&w_chave_aux=" &  Rs3("sq_meta")  & "&w_tipo=Volta&P1=10&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "','Meta','width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no'); return false;"" title=""Clique para exibir os dados!"">" & RS3("titulo") & "</A></td>"
                      End If
                      ShowHTML "      <td nowrap align=""center""><font size=""1"">" & Nvl(RS3("unidade_medida"),"---") & "</td>"
                      ShowHTML "      <td nowrap align=""right"" ><font size=""1"">" & FormatNumber(cDbl(Nvl(Rs3("quantidade"),0)),2) & "</td>"
                      DB_GetMetaMensal_IS RS4, RS3("sq_meta")
                      RS4.Sort = "referencia desc"
                      If Not RS4.EOF Then
                         If RS3("cumulativa") = "S" Then
                            ShowHTML "      <td nowrap align=""right"" ><font size=""1"">" & FormatNumber(cDbl(Nvl(RS4("execucao_fisica"),0)),2) & "</td>"
                         Else
                            w_quantitativo_total = 0
                            While Not RS4.EOF
                               w_quantitativo_total = w_quantitativo_total + cDbl(Nvl(RS4("execucao_fisica"),0))
                               RS4.MoveNext
                            Wend
                            ShowHTML "      <td nowrap align=""right"" ><font size=""1"">" & FormatNumber(cDbl(Nvl(w_quantitativo_total,0)),2) & "</td>"
                         End If
                      Else
                         ShowHTML "      <td nowrap align=""right"" ><font size=""1"">---</td>"
                      End If
                      ShowHTML "      <td nowrap align=""right"" ><font size=""1"">" & Rs3("perc_conclusao") & "</td>"
                      RS3.MoveNext
                      If Not RS3.EOF Then
                         While Not RS3.EOF
                            ShowHTML "      <tr><td colspan=""2"">&nbsp;"
                            If w_tipo_rel = "WORD" Then
                               ShowHTML "      <td><font size=""1"">" & Rs3("titulo") & "</td>"
                            Else
                               ShowHTML "      <td><font size=""1""><A class=""HL"" HREF=""#"" onClick=""window.open('Acao.asp?par=AtualizaMeta&O=V&w_chave=" & RS2("sq_siw_solicitacao") & "&w_chave_aux=" &  Rs3("sq_meta")  & "&w_tipo=Volta&P1=10&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "','Meta','width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no'); return false;"" title=""Clique para exibir os dados!"">" & Rs3("titulo") & "</A></td>"
                            End If
                            ShowHTML "      <td nowrap align=""center""><font size=""1"">" & Nvl(Rs3("unidade_medida"),"---") & "</td>"
                            ShowHTML "      <td nowrap align=""right"" ><font size=""1"">" & FormatNumber(cDbl(Nvl(Rs3("quantidade"),0)),2) & "</td>"
                            DB_GetMetaMensal_IS RS4, RS3("sq_meta")
                            RS4.Sort = "referencia desc"
                            If Not RS4.EOF Then
                               If RS3("cumulativa") = "S" Then
                                  ShowHTML "      <td nowrap align=""right"" ><font size=""1"">" & FormatNumber(cDbl(Nvl(RS4("execucao_fisica"),0)),2) & "</td>"
                               Else
                                  w_quantitativo_total = 0
                                  While Not RS4.EOF
                                     w_quantitativo_total = w_quantitativo_total + cDbl(Nvl(RS4("execucao_fisica"),0))
                                     RS4.MoveNext
                                  Wend
                                  ShowHTML "      <td nowrap align=""right"" ><font size=""1"">" & FormatNumber(cDbl(Nvl(w_quantitativo_total,0)),2) & "</td>"
                               End If
                            Else
                               ShowHTML "      <td nowrap align=""right"" ><font size=""1"">---</td>"
                            End If
                            ShowHTML "           <td nowrap align=""right"" ><font size=""1"">" & Rs3("perc_conclusao") & "</td>"
                            ShowHTML "        </tr>"
                            w_linha = w_linha + 1
                            RS3.MoveNext
                         Wend
                      End If
                   End If
                End If
             Else
                ShowHTML "        <td colspan=""6"" align=""middle""><font size=""1""><b>Não foram encontrados registros.</b></td>"
             End If            
          Else
             If p_programada = "" and p_exequivel = "" and p_fim_previsto = "" and p_atraso = "" Then
                w_cont = w_cont + 1
                If cDbl(w_atual) <> cDbl(RS("chave")) Then
                   ShowHTML "      <tr valign=""top"">"
                   ShowHTML "        <td><font size=""1""><b>" & RS("nome") & "</td>"
                Else
                   ShowHTML "      <tr valign=""top"">"
                   ShowHTML "        <td><font size=""1""><b>&nbsp;</td>"
                End If
                w_linha = w_linha + 1
                If w_teste_acoes = 1 Then
                   ShowHTML "        <td colspan=""1""><font size=""1""><b>" & RS("titulo") & "</b></td>"
                   ShowHTML "        <td colspan=""5"" align=""center""><font size=""1""><b>Não há permissão para visualização da ação<b></td>"
                Else
                   ShowHTML "        <td colspan=""6"" align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td>"
                End If
             End If
          End If
          w_atual = RS("chave")
          RS.MoveNext
       wend
    End If
    If w_cont = 0 Then
       ShowHTML "        <td colspan=""7"" align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td>"
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    DesconectaBD
  ElseIf O = "P" Then
    AbreForm "Form", w_dir & w_pagina & par, "POST", "return(Validacao(this));", "Projeto",P1,P2,P3,P4,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "          <tr>"
    SelecaoIsProjeto "<u>P</u>rograma interno:", "P", null, p_sq_isprojeto, null, "p_sq_isprojeto", null, "onchange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='p_sq_isprojeto'; document.Form.target=''; document.Form.O.value='P'; document.Form.submit();"""
    ShowHTML "          </tr>"
    ShowHTML "          <tr>"
    SelecaoAcao "<u>A</u>ção:", "A", null, w_cliente, w_ano, null, null, null, null, "p_siw_solic", "PROJETO", null, p_sq_isprojeto
    ShowHTML "          </tr>"
    ShowHTML "      <tr><td><font size=""1""><b><u>R</u>esponsável:</b><br><input " & w_disabled & " accesskey=""R"" type=""text"" name=""p_responsavel"" class=""sti"" SIZE=""40"" MAXLENGTH=""60"" VALUE=""" & p_responsavel & """></td>"
    ShowHTML "      <tr>"
    SelecaoPrioridade "<u>P</u>rioridade das tarefas:", "P", "Informe a prioridade da tarefa.", p_prioridade, null, "p_prioridade", null, null
    ShowHTML "      <tr><td colspan=3><table border=0 width=""100%"" cellspacing=0 cellpadding=0><tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Selecionada SPI/MP?</b><br>"
    ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecao_mp"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""p_selecao_mp"" value=""N""> Não <input " & w_Disabled & " type=""radio"" name=""p_selecao_mp"" value="""" checked> Independe"
    ShowHTML "          <td><font size=""1""><b>Selecionada SE/SEPPIR?</b><br>"
    ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecao_se"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""p_selecao_se"" value=""N""> Não <input " & w_Disabled & " type=""radio"" name=""p_selecao_se"" value="""" checked> Independe"
    ShowHTML "      <tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Exibir somente metas do PPA?</b><br>"
    ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_programada"" value=""S""> Sim <br><input " & w_Disabled & " type=""radio"" name=""p_programada"" value="""" checked> Não"
    ShowHTML "          <td><font size=""1""><b>Exibir somente metas que não serão cumpridas?</b><br>"
    ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_exequivel"" value=""N""> Sim <br><input " & w_Disabled & " type=""radio"" name=""p_exequivel"" value="""" checked> Não"
    ShowHTML "      <tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Exibir somente metas em atraso?</b><br>"
    ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_fim_previsto"" value=""S""> Sim <br><input " & w_Disabled & " type=""radio"" name=""p_fim_previsto"" value="""" checked> Não"
    ShowHTML "          <td><font size=""1""><b>Exibir somente ações em atraso?</b><br>"
    ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_atraso"" value=""S""> Sim <br><input " & w_Disabled & " type=""radio"" name=""p_atraso"" value="""" checked> Não"
    'ShowHTML "      <tr valign=""top"">"
    'ShowHTML "          <td><font size=""1""><b>Exibir ações com tarefas em atraso?</b><br>"
    'ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_tarefas_atraso"" value=""S""> Sim <br><input " & w_Disabled & " type=""radio"" name=""p_tarefas_atraso"" value="""" checked> Não"
    ShowHTML "          </table>"
    ShowHTML "    <table width=""90%"" border=""0"">"            
    ShowHTML "      <tr><td align=""center""><hr>"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Exibir"">"
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
  If w_tipo_rel <> "WORD" Then
     Rodape
  End If

  Set w_titulo                  = Nothing 
  Set w_logo                    = Nothing 
  Set w_atual                   = Nothing 
  Set p_sq_isprojeto            = Nothing 
  Set p_siw_solic               = Nothing
  Set p_selecao_mp              = Nothing 
  Set p_selecao_se              = Nothing 
  Set p_responsavel             = Nothing 
  Set p_sq_unidade_resp         = Nothing
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Relatório Sintético das Ações do PPA
REM -------------------------------------------------------------------------
Sub Rel_Sintetico_PPA
  Dim p_codigo, p_cd_acao, p_cd_programa, p_selecao_mp, p_selecao_se
  Dim p_responsavel, p_sq_unidade_resp, p_prioridade
  Dim w_atual, w_logo, w_titulo 
  Dim w_tipo_rel, w_quantitativo_total
  Dim p_programada, p_exequivel, p_fim_previsto, p_atraso, p_tarefas_atraso
  Dim w_teste_metas, w_teste_acoes, w_visao, RSquery, w_cont, w_teste_pai
  
  w_chave           = Request("w_chave")
  w_troca           = Request("w_troca")
  w_tipo_rel        = uCase(trim(Request("w_tipo_rel")))
  
  p_codigo                   = ucase(Trim(Request("p_codigo")))
  If  ucase(Trim(Request("p_cd_programa"))) > "" and p_codigo = "" Then
     p_cd_programa              = ucase(Trim(Request("p_cd_programa")))
  Else
     p_cd_programa              = ucase(Trim(Mid(p_codigo,1,4)))
  End If
  p_cd_acao                  = ucase(Trim(Mid(p_codigo,5,4)))
  p_responsavel              = ucase(Trim(Request("p_responsavel")))
  p_sq_unidade_resp          = ucase(Trim(Request("p_sq_unidade_resp")))
  p_prioridade               = ucase(Trim(Request("p_prioridade")))
  p_selecao_mp               = ucase(Trim(Request("p_selecao_mp")))
  p_selecao_se               = ucase(Trim(Request("p_selecao_se")))
  p_programada               = ucase(Trim(Request("p_programada")))
  p_exequivel                = ucase(Trim(Request("p_exequivel")))
  p_fim_previsto             = ucase(Trim(Request("p_fim_previsto")))
  p_atraso                   = ucase(Trim(Request("p_atraso")))
  p_tarefas_atraso           = ucase(Trim(Request("p_tarefas_atraso")))
  
  w_cont = 0 
  
  If O = "L" Then
     ' Recupera o logo do cliente a ser usado nas listagens
     DB_GetCustomerData RS, w_cliente
     If RS("logo") > "" Then
         w_logo = "\img\logo" & Mid(RS("logo"),Instr(RS("logo"),"."),30)
     End If
     DesconectaBD
     If p_cd_programa > "" and p_codigo = "" Then
        DB_GetAcaoPPA_IS RS, w_cliente, w_ano, p_cd_programa , null, null, null, null, null, null
     Else
        DB_GetAcaoPPA_IS RS, w_cliente, w_ano, Mid(p_codigo,1,4), Mid(p_codigo,5,4), null, Mid(p_codigo,13,17), null, null, null
     End If
     If p_responsavel > "" Then
        RS.Filter = "nm_coordenador like '%" & p_responsavel &"%'"
     End If
     RS.Sort = "cd_programa, cd_acao, cd_unidade"
  End If
  
  If w_tipo_rel = "WORD" Then
     HeaderWord null
     w_pag   = 1
     w_linha = 8
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "WORD") & """><TD ALIGN=""RIGHT"" NOWRAP><B><FONT SIZE=4 COLOR=""#000000"">"
     ShowHTML "Relatório Sintético - Ações PPA 2004 - 2007 Exercício " & w_ano
     ShowHTML "</FONT><TR><TD WIDTH=""50%"" ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
     ShowHTML "<TR><TD COLSPAN=""2"" ALIGN=""RIGHT""><B><FONT SIZE=2 COLOR=""#000000"">Página: " & w_pag & "</B></TD></TR>"
     ShowHTML "</TD></TR>"
     ShowHTML "</FONT></B></TD></TR></TABLE>"
  Else
     Cabecalho
     ShowHTML "<HEAD>"
     ShowHTML "<TITLE>Relatório Sintético - Ações PPA 2004 - 2007 Exercício " & w_ano & "</TITLE>"
     If InStr("P",O) > 0 Then
        ScriptOpen "JavaScript"
        ValidateOpen "Validacao"
        Validate "p_cd_programa", "Programa", "SELECT", "", "1", "18", "1", "1"
        Validate "p_codigo", "Ação", "SELECT", "", "1", "18", "1", "1"
        Validate "p_responsavel", "Responsável", "1", "", "2", "60", "1", "1"
        ValidateClose
        ScriptClose
     End If
     ShowHTML "</HEAD>"
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     If O = "L" Then
        BodyOpenClean "onLoad='document.focus()';"
        ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "EMBED") & """><TD ALIGN=""RIGHT"" NOWRAP><B><FONT SIZE=4 COLOR=""#000000"">"
        ShowHTML "Relatório Sintético - Ações PPA 2004 - 2007 Exercício " & w_ano
        ShowHTML "</FONT><TR><TD WIDTH=""50%"" ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
        ShowHTML "&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Imprimir"" SRC=""images/impressora.jpg"" onClick=""window.print();"">"
        ShowHTML "&nbsp;&nbsp;<IMG BORDER=0 ALIGN=""CENTER"" TITLE=""Gerar word"" SRC=""images/word.gif"" onClick=""window.open('" & w_pagina & par & "&R=" & w_pagina & par & "&O=L&w_chave=" & w_chave & "&w_tipo_rel=word&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") &"','VisualRelPPAWord','menubar=yes resizable=yes scrollbars=yes');"">"
        ShowHTML "</TD></TR>"
        ShowHTML "</FONT></B></TD></TR></TABLE>"
     Else
        BodyOpen "onLoad='document.Form.p_cd_programa.focus()';"
        ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
     End If
     ShowHTML "<HR>"
  End If
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
     ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
     w_filtro = "<tr valign=""top"">"
    If p_responsavel           > "" Then w_filtro = w_filtro & "<td><font size=1>Responsável&nbsp;<font size=1>[<b>" & p_responsavel & "</b>]&nbsp;"                     End If
    If p_prioridade            > "" Then w_filtro = w_filtro & "<td><font size=1>Prioridade&nbsp;<font size=1>[<b>" & RetornaPrioridade(p_prioridade) & "</b>]&nbsp;"    End If
    If p_selecao_mp            > "" Then w_filtro = w_filtro & "<td><font size=1>Selecionada SPI/MP&nbsp;<font size=1>[<b>" & p_selecao_mp & "</b>]&nbsp;"             End If
    If p_selecao_se            > "" Then w_filtro = w_filtro & "<td><font size=1>Selecionada SE/SEPPIR&nbsp;<font size=1>[<b>" & p_selecao_se & "</b>]&nbsp;" End If
    If p_programada            > "" Then w_filtro = w_filtro & "<td><font size=1>Meta PPA&nbsp;<font size=1>[<b>" & p_programada & "</b>]&nbsp;"                         End If
    If p_exequivel             > "" Then w_filtro = w_filtro & "<td><font size=1>Meta será cumprida&nbsp;<font size=1>[<b>" & p_exequivel & "</b>]&nbsp;"                End If
    If p_fim_previsto          > "" Then w_filtro = w_filtro & "<td><font size=1>Metas em atraso&nbsp;<font size=1>[<b>" & p_fim_previsto & "</b>]&nbsp;"                End If
    If p_atraso                > "" Then w_filtro = w_filtro & "<td><font size=1>Ações em atraso&nbsp;<font size=1>[<b>" & p_atraso & "</b>]&nbsp;"                      End If
    If p_tarefas_atraso        > "" Then w_filtro = w_filtro & "<td><font size=1>Ações com tarefas em atraso&nbsp;<font size=1>[<b>" & p_tarefas_atraso & "</b>]&nbsp;"  End If
    ShowHTML "<tr><td align=""left"">"
    If w_filtro                > "" Then ShowHTML "<table border=0><tr valign=""top""><td><font size=1><b>Filtro:</b><td nowrap><font size=1><ul>" & w_filtro & "</ul></tr></table>"                End If
    ShowHTML "<tr><td align=""center"" colspan=""2"">"
    ShowHTML "      <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td rowspan=""1"" colspan=""2""><font size=""1""><b>Programas</font></td>"
    ShowHTML "          <td rowspan=""1"" colspan=""2""><font size=""1""><b>Ações</font></td>"
    DB_GetOrImport RS1, null, w_cliente, null, null, null, null, null
    RS1.Sort ="data_arquivo desc"
    If Not RS1.EOF Then
       ShowHTML "          <td rowspan=""1"" colspan=""4""><font size=""1""><b>Dados SIAFI&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Atualização: " & Nvl(FormataDataEdicao(RS1("data_arquivo")),"-") & "</font></td>"
    Else
       ShowHTML "          <td rowspan=""1"" colspan=""4""><font size=""1""><b>Dados SIAFI</font></td>"
    End If
    RS1.Close
    ShowHTML "          <td rowspan=""1"" colspan=""7""><font size=""1""><b>Metas</font></td>"
    ShowHTML "        </tr>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Cód</font></td>"
    ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
    ShowHTML "          <td><font size=""1""><b>Cód</font></td>"
    ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
    ShowHTML "          <td><font size=""1""><b>Aprovado</font></td>"
    ShowHTML "          <td><font size=""1""><b>Autorizado</font></td>"
    ShowHTML "          <td><font size=""1""><b>Realizado</font></td>"
    ShowHTML "          <td><font size=""1""><b>Importação</font></td>"
    ShowHTML "          <td><font size=""1""><b>Produto</font></td>"
    ShowHTML "          <td><font size=""1""><b>Unidade<br>medida</font></td>"
    ShowHTML "          <td><font size=""1""><b>Quantitativo<br>programado</font></td>"
    ShowHTML "          <td><font size=""1""><b>Quatintativo<br>realizado</font></td>"
    ShowHTML "          <td><font size=""1""><b>% Realizado</font></td>"
    ShowHTML "          <td><font size=""1""><b>Meta<br>PPA</font></td>"
    ShowHTML "          <td><font size=""1""><b>Meta<br>PNPIR</font></td>"
    ShowHTML "        </tr>"    
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
       w_cont = w_cont + 1
       w_linha = w_linha + 1
       ShowHTML "    <tr bgcolor=""" & conTrBgColor & """><td colspan=16 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      w_atual = 0
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
         If w_linha > 19 and w_tipo_rel = "WORD" Then
            ShowHTML "    </table>"
            ShowHTML "  </td>"
            ShowHTML "</tr>"
            ShowHTML "</table>"
            ShowHTML "</center></div>"
            ShowHTML "    <br style=""page-break-after:always"">"
            w_linha = 6
            w_pag   = w_pag + 1
            ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "WORD") & """><TD ALIGN=""RIGHT""><B><FONT SIZE=4 COLOR=""#000000"">"
            ShowHTML "Ações do PPA"
            ShowHTML "</FONT><TR><TD WIDTH=""50%"" ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
            ShowHTML "<TR><TD COLSPAN=""2"" ALIGN=""RIGHT""><B><FONT SIZE=2 COLOR=""#000000"">Página: " & w_pag & "</B></TD></TR>"
            ShowHTML "</TD></TR>"
            ShowHTML "</FONT></B></TD></TR></TABLE>"
            ShowHTML "<div align=center><center>"
            ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
            w_filtro = "<tr valign=""top"">"
            If p_responsavel           > "" Then w_filtro = w_filtro & "<td><font size=1>Responsável&nbsp;<font size=1>[<b>" & p_responsavel & "</b>]&nbsp;"                     End If
            If p_prioridade            > "" Then w_filtro = w_filtro & "<td><font size=1>Prioridade&nbsp;<font size=1>[<b>" & RetornaPrioridade(p_prioridade) & "</b>]&nbsp;"    End If
            If p_selecao_mp            > "" Then w_filtro = w_filtro & "<td><font size=1>Selecionada SPI/MP&nbsp;<font size=1>[<b>" & p_selecao_mp & "</b>]&nbsp;"             End If
            If p_selecao_se            > "" Then w_filtro = w_filtro & "<td><font size=1>Selecionada SE/SEPPIR&nbsp;<font size=1>[<b>" & p_selecao_se & "</b>]&nbsp;" End If
            If p_programada            > "" Then w_filtro = w_filtro & "<td><font size=1>Meta PPA&nbsp;<font size=1>[<b>" & p_programada & "</b>]&nbsp;"                         End If
            If p_exequivel             > "" Then w_filtro = w_filtro & "<td><font size=1>Meta será cumprida&nbsp;<font size=1>[<b>" & p_exequivel & "</b>]&nbsp;"                End If
            If p_fim_previsto          > "" Then w_filtro = w_filtro & "<td><font size=1>Metas em atraso&nbsp;<font size=1>[<b>" & p_fim_previsto & "</b>]&nbsp;"                End If
            If p_atraso                > "" Then w_filtro = w_filtro & "<td><font size=1>Ações em atraso&nbsp;<font size=1>[<b>" & p_atraso & "</b>]&nbsp;"                      End If
            If p_tarefas_atraso        > "" Then w_filtro = w_filtro & "<td><font size=1>Ações com tarefas em atraso&nbsp;<font size=1>[<b>" & p_tarefas_atraso & "</b>]&nbsp;"  End If                    
            ShowHTML "<tr><td align=""left"">"
            If w_filtro                > "" Then ShowHTML "<table border=0><tr valign=""top""><td><font size=1><b>Filtro:</b><td nowrap><font size=1><ul>" & w_filtro & "</ul></tr></table>"                End If
            ShowHTML "<tr><td align=""center"" colspan=""2"">"
            ShowHTML "      <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
            ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
            ShowHTML "          <td rowspan=""1"" colspan=""2""><font size=""1""><b>Programas</font></td>"
            ShowHTML "          <td rowspan=""1"" colspan=""2""><font size=""1""><b>Ações</font></td>"
            DB_GetOrImport RS1, null, w_cliente, null, null, null, null, null
            RS1.Sort ="data_arquivo desc"
            If Not RS1.EOF Then
               ShowHTML "          <td rowspan=""1"" colspan=""4""><font size=""1""><b>Dados SIAFI&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Atualização: " & Nvl(FormataDataEdicao(RS1("data_arquivo")),"-") & "</font></td>"
            Else
               ShowHTML "          <td rowspan=""1"" colspan=""4""><font size=""1""><b>Dados SIAFI</font></td>"
            End If
            RS1.Close
            ShowHTML "          <td rowspan=""1"" colspan=""7""><font size=""1""><b>Metas</font></td>"
            ShowHTML "        </tr>"
            ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
            ShowHTML "          <td><font size=""1""><b>Cód</font></td>"
            ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
            ShowHTML "          <td><font size=""1""><b>Cód</font></td>"
            ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
            ShowHTML "          <td><font size=""1""><b>Aprovado</font></td>"
            ShowHTML "          <td><font size=""1""><b>Autorizado</font></td>"
            ShowHTML "          <td><font size=""1""><b>Realizado</font></td>"
            ShowHTML "          <td><font size=""1""><b>Importação</font></td>"
            ShowHTML "          <td><font size=""1""><b>Produto</font></td>"
            ShowHTML "          <td><font size=""1""><b>Unidade<br>medida</font></td>"
            ShowHTML "          <td><font size=""1""><b>Quantitativo<br>programado</font></td>"
            ShowHTML "          <td><font size=""1""><b>Quatintativo<br>realizado</font></td>"
            ShowHTML "          <td><font size=""1""><b>% Realizado</font></td>"
            ShowHTML "          <td><font size=""1""><b>Meta<br>PPA</font></td>"
            ShowHTML "          <td><font size=""1""><b>Meta<br>PNPIR</font></td>"
            ShowHTML "        </tr>"    
          End If
          'If Nvl(RS("cd_programa"),"") = "" Then
          '   RS.MoveNext
          '   w_teste_pai = 1
          'End If
          'Montagem da lista das ações
          DB_GetLinkData RS1, w_cliente, "ISACAD"
          DB_GetSolicList_IS RS2, RS1("sq_menu"), w_usuario, RS1("sigla"), 4, _
             null, null, null, null, p_atraso, null, null, null, null, null, null, null, _
             null, null, null, null, null, null, null, null, null, null, null, Mid(RS("chave"),1,4), _
             RS("cd_acao"), null, Mid(RS("chave"),9,4), w_ano
           If p_responsavel > "" Then
             RS2.Filter = "nm_coordenador like '%" &p_responsavel& "%'"
          End If
          'Variarel para o teste de existencia de metas e açoes para visualização no relatorio
          w_teste_metas = 0
          w_teste_acoes = 0 
             
          'Recuperação e verificação das metas das ações de acordo com a visão do usuário
          If Not RS2.EOF Then
             w_teste_acoes = 1
             w_visao = 0
             If w_visao < 2 Then               
                DB_GetSolicMeta_IS RS3, RS2("sq_siw_solicitacao"), null, "LSTNULL", null, null, null, null, null, null, null
                If p_programada       > "" and p_exequivel    > "" and p_fim_previsto > "" Then
                   RS3.Filter = "cd_subacao <> null and exequivel = '" & p_exequivel & "' and fim_previsto < '" & Date() & "'"
                ElseIf p_programada   > "" and p_exequivel    > "" Then   
                   RS3.Filter = "cd_subacao <> null and exequivel = '" & p_exequivel & "'"
                ElseIf p_programada   > "" and p_fim_previsto > "" Then
                   RS3.Filter = "cd_subacao <> null and fim_previsto < '" & Date() & "'"
                ElseIf p_fim_previsto > "" and p_exequivel    > "" Then
                   RS3.Filter = "exequivel = '" & p_exequivel & "' and fim_previsto < '" & Date() & "'"
                ElseIf p_programada   > "" Then
                   RS3.Filter = "cd_subacao <> null"
                ElseIf p_exequivel    > "" Then
                   RS3.Filter = "exequivel = '" & p_exequivel & "'"
                ElseIf p_fim_previsto > "" Then
                   RS3.Filter = "fim_previsto < '" & Date() & "'"
                End If
                RS3.Sort = "ordem"
                If Not RS3.EOF Then
                   w_teste_metas = 1
                ElseIf p_programada = "" and p_exequivel = "" and p_fim_previsto = "" Then
                   w_teste_metas = 3
                End If
             Else
                w_teste_metas = 0
             End If
          Else
             If RS("sq_siw_solicitacao") > "" Then
                w_teste_acoes = 1
                w_teste_metas = 0
             Else
                w_teste_acoes = 0
             End If
          End If
          'If w_teste_pai = 1 Then
          '   RS.MovePrevious
          '   w_teste_pai = 0
          'End If
            
          If w_teste_metas = 1 or w_teste_metas = 3 Then
             'Inicio da montagem da lista das ações e metas de acordo com o filtro
             w_cont = w_cont + 1
             If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
             If RS("cd_programa") <> w_teste_pai or p_programada > "" or p_exequivel > "" or p_fim_previsto > "" or p_atraso > "" Then
                ShowHTML " <tr bgcolor=""" & conTrAlternateBgColor & """ valign=""top"">"
                ShowHTML "   <td><font size=""1""><b>" & RS("cd_programa") & "</td>"
                ShowHTML "   <td><font size=""1""><b>" & RS("ds_programa") & "</td>"
                'RS.MoveNext
                w_atual = 1
             Else
                ShowHTML " <tr valign=""top"">"
                ShowHTML "   <td><font size=""1""><b>&nbsp;</td>"
                ShowHTML "   <td><font size=""1""><b>&nbsp;</td>"
             End If
             w_linha = w_linha + 1
             ShowHTML "      <td nowrap><font size=""1""><b>" & RS("cd_unidade") & "." & RS("cd_programa") & "." & RS("cd_acao") & "</td>"
             If w_tipo_rel = "WORD" or RS("sq_siw_solicitacao") = "" Then
                ShowHTML "   <td><font size=""1""><b>" & RS("descricao_acao") & "</td>"
             Else
                ShowHTML "   <td><font size=""1""><b><A class=""HL"" HREF=""" & w_dir & "Acao.asp?par=Visual&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=&P1=2&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ TARGET=""VisualAcao"" title=""Exibe as informações da ação."">" & RS("descricao_acao") & "</a></td>"
             End If
             ShowHTML "      <td align=""right""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS("previsao_ano"),0)),2) & "</td>"
             ShowHTML "      <td align=""right""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS("atual_ano"),0)),2) & "</td>"
             ShowHTML "      <td align=""right""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS("real_ano"),0)),2) & "</td>"
             If Nvl(RS("dt_carga_financ"),"") > "" Then
                ShowHTML "      <td align=""center""><font size=""1"">" & FormataDataEdicao(FormatDateTime(RS("dt_carga_financ"),2)) & "</td>"
             Else
                ShowHTML "      <td align=""center""><font size=""1"">---</td>"
             End If 
             'ShowHTML "      <td align=""right""><font size=""1"">" & FormatNumber(cDbl(Nvl(RS("real_ano"),0)),2) & "</td>"
             'ShowHTML "      <td align=""right""><font size=""1"">" & FormatNumber((cDbl(Nvl(RS("empenhado"),0.00))-cDbl(Nvl(RS("liquidado"),0.00))),2) & "</td>" 
             If RS2.EOF Then
                ShowHTML "   <td colspan=""7"" align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td>"
             Else
                If RS3.EOF Then ' Se não foram selecionados registros, exibe mensagem
                   ShowHTML "<td colspan=""7"" align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
                Else
                   If w_tipo_rel = "WORD" Then
                      ShowHTML "<td><font size=""1"">" & Rs3("titulo") & "</td>"
                   Else
                      ShowHTML "<td><font size=""1""><A class=""HL"" HREF=""#"" onClick=""window.open('Acao.asp?par=AtualizaMeta&O=V&w_chave=" & RS2("sq_siw_solicitacao") & "&w_chave_aux=" &  Rs3("sq_meta")  & "&w_tipo=Volta&P1=10&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "','Meta','width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no'); return false;"" title=""Clique para exibir os dados!"">" & Rs3("titulo") & "</A></td>"
                   End If
                   ShowHTML "      <td nowrap align=""center""><font size=""1"">" & Nvl(Rs3("unidade_medida"),"---") & "</td>"
                   ShowHTML "      <td nowrap align=""right"" ><font size=""1"">" & Rs3("quantidade") & "</td>"
                   DB_GetMetaMensal_IS RS4, RS3("sq_meta")
                   RS4.Sort = "referencia desc"
                   If Not RS4.EOF Then
                      If RS3("cumulativa") = "S" Then
                         ShowHTML "      <td nowrap align=""right"" ><font size=""1"">" & Nvl(RS4("realizado"),0) & "</td>"
                      Else
                         w_quantitativo_total = 0
                         While Not RS4.EOF
                            w_quantitativo_total = w_quantitativo_total + cDbl(Nvl(RS4("realizado"),0))
                            RS4.MoveNext
                         Wend
                         ShowHTML "      <td nowrap align=""right"" ><font size=""1"">" & w_quantitativo_total & "</td>"
                      End If
                   Else
                      ShowHTML "      <td nowrap align=""center"" ><font size=""1"">---</td>"
                   End If
                   ShowHTML "<td nowrap align=""right"" ><font size=""1"">" & Rs3("perc_conclusao") & "</td>"
                   If Nvl(RS3("cd_subacao"),"") > "" Then
                      ShowHTML "<td nowrap align=""center"" ><font size=""1"">Sim</td>"
                   Else
                      ShowHTML "<td nowrap align=""center"" ><font size=""1"">Não</td>"
                   End If
                   ShowHTML "<td nowrap align=""center"" ><font size=""1"">" & Rs3("nm_programada") & "</td>"
                   RS3.MoveNext
                   If Not RS3.EOF Then
                      While Not RS3.EOF
                         ShowHTML "<tr><td colspan=""8"">&nbsp;"
                         If w_tipo_rel = "WORD" Then
                            ShowHTML "<td><font size=""1"">" & Rs3("titulo") & "</td>"
                         Else
                            ShowHTML "<td><font size=""1""><A class=""HL"" HREF=""#"" onClick=""window.open('Acao.asp?par=AtualizaMeta&O=V&w_chave=" & RS2("sq_siw_solicitacao") & "&w_chave_aux=" &  Rs3("sq_meta")  & "&w_tipo=Volta&P1=10&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "','Meta','width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no'); return false;"" title=""Clique para exibir os dados!"">" & Rs3("titulo") & "</A></td>"
                         End If
                         ShowHTML "      <td nowrap align=""center""><font size=""1"">" & Nvl(Rs3("unidade_medida"),"---") & "</td>"
                         ShowHTML "      <td nowrap align=""right"" ><font size=""1"">" & Rs3("quantidade") & "</td>"
                         DB_GetMetaMensal_IS RS4, RS3("sq_meta")
                         RS4.Sort = "referencia desc"
                         If Not RS4.EOF Then
                            If RS3("cumulativa") = "S" Then
                               ShowHTML "      <td nowrap align=""right"" ><font size=""1"">" & Nvl(RS4("realizado"),0) & "</td>"
                            Else
                               w_quantitativo_total = 0
                               While Not RS4.EOF
                                  w_quantitativo_total = w_quantitativo_total + cDbl(Nvl(RS4("realizado"),0))
                                  RS4.MoveNext
                               Wend
                               ShowHTML "      <td nowrap align=""right"" ><font size=""1"">" & w_quantitativo_total & "</td>"
                            End If
                         Else
                            ShowHTML "      <td nowrap align=""center"" ><font size=""1"">---</td>"
                         End If
                         ShowHTML "<td nowrap align=""right"" ><font size=""1"">" & Rs3("perc_conclusao") & "</td>"
                         If Nvl(RS3("cd_subacao"),"") > "" Then
                            ShowHTML "<td nowrap align=""center"" ><font size=""1"">Sim</td>"
                         Else
                            ShowHTML "<td nowrap align=""center"" ><font size=""1"">Não</td>"
                         End If
                         ShowHTML "<td nowrap align=""center"" ><font size=""1"">" & Rs3("nm_programada") & "</td>"
                         w_linha = w_linha + 1
                         RS3.MoveNext
                      Wend
                   End If
                End If
             End If
          Else
             If p_programada = "" and p_exequivel = "" and p_fim_previsto = "" and p_atraso = "" Then
                w_cont = w_cont + 1
                If RS("cd_programa") <> w_teste_pai Then
                   ShowHTML " <tr bgcolor=""" & conTrAlternateBgColor & """ valign=""top"">"
                   ShowHTML "   <td><font size=""1""><b>" & RS("cd_programa") & "</td>"
                   ShowHTML "   <td><font size=""1""><b>" & RS("ds_programa") & "</td>"
                   w_atual = 1
                Else
                   ShowHTML " <tr valign=""top"">"
                   ShowHTML "   <td><font size=""1""><b>&nbsp;</td>"
                   ShowHTML "   <td><font size=""1""><b>&nbsp;</td>"
                End If
                w_linha = w_linha + 1
                If w_teste_acoes = 1 Then
                   ShowHTML "        <td colspan=""1"" nowrap><font size=""1""><b>" & RS("cd_unidade") & "." & RS("cd_programa") & "." & RS("cd_acao") & "</b></td>"
                   ShowHTML "        <td colspan=""1""><font size=""1""><b>" & RS("descricao_acao") & "</b></td>"
                   If w_teste_metas = 3 Then
                      ShowHTML "        <td colspan=""12"" align=""center""><font size=""1""><b>Não foram encontrados registros.<b></td>"
                   Else
                      ShowHTML "        <td colspan=""12"" align=""center""><font size=""1""><b>Não há permissão para visualização da ação<b></td>"
                   End If
                Else
                   ShowHTML "        <td colspan=""1"" nowrap><font size=""1""><b>" & RS("cd_unidade") & "." & RS("cd_programa") & "." & RS("cd_acao") & "</b></td>"
                   ShowHTML "        <td colspan=""1""><font size=""1""><b>" & RS("descricao_acao") & "</b></td>"
                   ShowHTML "        <td colspan=""12"" align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td>"
                End If
             End If
          End If
          w_teste_pai = RS("cd_programa")
          RS.MoveNext
       wend
    End If
    If w_cont = 0 Then
       ShowHTML "        <td colspan=""18"" align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td>"
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    DesconectaBD
  ElseIf O = "P" Then
    AbreForm "Form", w_dir & w_pagina & par, "POST", "return(Validacao(this));", "Acao",P1,P2,P3,P4,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr>"
    SelecaoProgramaPPA "<u>P</u>rograma PPA:", "P", null, w_cliente, w_ano, p_cd_programa, "p_cd_programa", null, "onchange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='p_cd_programa'; document.Form.target=''; document.Form.O.value='P'; document.Form.submit();""", w_menu
    ShowHTML "      <tr>"
    SelecaoAcaoPPA "<u>A</u>ção PPA:", "A", null, w_cliente, w_ano, p_cd_programa, null, null, null, "p_codigo", null, null, null, w_menu
    ShowHTML "      <tr><td><font size=""1""><b><u>R</u>esponsável:</b><br><input " & w_disabled & " accesskey=""R"" type=""text"" name=""p_responsavel"" class=""sti"" SIZE=""40"" MAXLENGTH=""60"" VALUE=""" & p_responsavel & """></td>"
    ShowHTML "      <tr>"
    SelecaoPrioridade "<u>P</u>rioridade das tarefas:", "P", "Informe a prioridade da tarefa.", p_prioridade, null, "p_prioridade", null, null
    ShowHTML "      <tr><td colspan=3><table border=0 width=""100%"" cellspacing=0 cellpadding=0><tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Selecionada SPI/MP?</b><br>"
    ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecao_mp"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""p_selecao_mp"" value=""N""> Não <input " & w_Disabled & " type=""radio"" name=""p_selecao_mp"" value="""" checked> Independe"
    ShowHTML "          <td><font size=""1""><b>Selecionada SE/SEPPIR?</b><br>"
    ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecao_se"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""p_selecao_se"" value=""N""> Não <input " & w_Disabled & " type=""radio"" name=""p_selecao_se"" value="""" checked> Independe"
    ShowHTML "      <tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Exibir somente metas do PPA?</b><br>"
    ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_programada"" value=""S""> Sim <br><input " & w_Disabled & " type=""radio"" name=""p_programada"" value="""" checked> Não"
    ShowHTML "          <td><font size=""1""><b>Exibir somente metas que não serão cumpridas?</b><br>"
    ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_exequivel"" value=""N""> Sim <br><input " & w_Disabled & " type=""radio"" name=""p_exequivel"" value="""" checked> Não"
    ShowHTML "      <tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Exibir somente metas em atraso?</b><br>"
    ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_fim_previsto"" value=""S""> Sim <br><input " & w_Disabled & " type=""radio"" name=""p_fim_previsto"" value="""" checked> Não"
    ShowHTML "          <td><font size=""1""><b>Exibir somente ações em atraso?</b><br>"
    ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_atraso"" value=""S""> Sim <br><input " & w_Disabled & " type=""radio"" name=""p_atraso"" value="""" checked> Não"
    'ShowHTML "      <tr valign=""top"">"
    'ShowHTML "          <td><font size=""1""><b>Exibir ações com tarefas em atraso?</b><br>"
    'ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_tarefas_atraso"" value=""S""> Sim <br><input " & w_Disabled & " type=""radio"" name=""p_tarefas_atraso"" value="""" checked> Não"
    ShowHTML "          </table>"
    ShowHTML "    <table width=""90%"" border=""0"">"            
    ShowHTML "      <tr><td align=""center""><hr>"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Exibir"">"
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
  
  If w_tipo_rel <> "WORD" Then
     Rodape
  End If
  
  Set w_titulo                  = Nothing 
  Set w_logo                    = Nothing 
  Set w_atual                   = Nothing 
  Set p_codigo                  = Nothing 
  Set p_cd_acao                 = Nothing 
  Set p_cd_programa             = Nothing 
  Set p_selecao_mp              = Nothing 
  Set p_selecao_se              = Nothing 
  Set p_responsavel             = Nothing 
  Set p_sq_unidade_resp         = Nothing
End Sub

REM =========================================================================
REM Relatório Sintético dos programas PPA
REM -------------------------------------------------------------------------
Sub Rel_Sintetico_Prog
  Dim p_cd_programa, p_selecao_mp, p_selecao_se
  Dim p_responsavel, p_sq_unidade_resp, p_prioridade
  Dim w_atual, w_logo, w_titulo 
  Dim w_tipo_rel, w_quantitativo_total
  Dim p_loa, p_exequivel, p_atraso
  Dim w_teste_indicador, w_teste_programas, w_visao, RSquery, w_cont, w_teste_pai
  
  w_chave           = Request("w_chave")
  w_troca           = Request("w_troca")
  w_tipo_rel        = uCase(trim(Request("w_tipo_rel")))
  
  p_cd_programa              = ucase(Trim(Request("p_cd_programa")))
  p_responsavel              = ucase(Trim(Request("p_responsavel")))
  p_sq_unidade_resp          = ucase(Trim(Request("p_sq_unidade_resp")))
  p_prioridade               = ucase(Trim(Request("p_prioridade")))
  p_selecao_mp               = ucase(Trim(Request("p_selecao_mp")))
  p_selecao_se               = ucase(Trim(Request("p_selecao_se")))
  p_loa                      = ucase(Trim(Request("p_loa")))
  p_exequivel                = ucase(Trim(Request("p_exequivel")))
  p_atraso                   = ucase(Trim(Request("p_atraso")))
  
  w_cont = 0 
  
  If O = "L" Then
     ' Recupera o logo do cliente a ser usado nas listagens
     DB_GetCustomerData RS, w_cliente
     If RS("logo") > "" Then
         w_logo = "\img\logo" & Mid(RS("logo"),Instr(RS("logo"),"."),30)
     End If
     DesconectaBD
     DB_GetProgramaPPA_IS RS, p_cd_programa, w_cliente, w_ano, null, null
     If p_responsavel > "" Then
        RS.Filter = "nm_gerente_programa like '%" &p_responsavel& "%'"
     End If
     RS.Sort = "ds_programa"
  End If
  
  If w_tipo_rel = "WORD" Then
     HeaderWord null
     w_pag   = 1
     w_linha = 8
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "WORD") & """><TD ALIGN=""RIGHT"" NOWRAP><B><FONT SIZE=4 COLOR=""#000000"">"
     ShowHTML "Relatório Sintético - Programas PPA 2004 - 2007 Exercício " & w_ano
     ShowHTML "</FONT><TR><TD WIDTH=""50%"" ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
     ShowHTML "<TR><TD COLSPAN=""2"" ALIGN=""RIGHT""><B><FONT SIZE=2 COLOR=""#000000"">Página: " & w_pag & "</B></TD></TR>"
     ShowHTML "</TD></TR>"
     ShowHTML "</FONT></B></TD></TR></TABLE>"
  Else
     Cabecalho
     ShowHTML "<HEAD>"
     ShowHTML "<TITLE>Relatório Sintético - Programas PPA 2004 - 2007 Exercício " & w_ano & "</TITLE>"
     If InStr("P",O) > 0 Then
        ScriptOpen "JavaScript"
        ValidateOpen "Validacao"
        Validate "p_cd_programa", "Programa", "SELECT", "", "1", "18", "1", "1"
        Validate "p_responsavel", "Responsável", "1", "", "2", "60", "1", "1"
        ValidateClose
        ScriptClose
     End If
     ShowHTML "</HEAD>"
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     If O = "L" Then
        BodyOpenClean "onLoad='document.focus()';"
        ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "EMBED") & """><TD ALIGN=""RIGHT"" NOWRAP><B><FONT SIZE=4 COLOR=""#000000"">"
        ShowHTML "Relatório Sintético - Programas PPA 2004 - 2007 Exercício " & w_ano
        ShowHTML "</FONT><TR><TD WIDTH=""50%"" ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
        ShowHTML "&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Imprimir"" SRC=""images/impressora.jpg"" onClick=""window.print();"">"
        ShowHTML "&nbsp;&nbsp;<IMG BORDER=0 ALIGN=""CENTER"" TITLE=""Gerar word"" SRC=""images/word.gif"" onClick=""window.open('" & w_pagina & par & "&R=" & w_pagina & par & "&O=L&w_chave=" & w_chave & "&w_tipo_rel=word&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") &"','VisualRelProgWord','menubar=yes resizable=yes scrollbars=yes');"">"
        ShowHTML "</TD></TR>"
        ShowHTML "</FONT></B></TD></TR></TABLE>"
     Else
        BodyOpen "onLoad='document.Form.p_cd_programa.focus()';"
        ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
     End If
     ShowHTML "<HR>"
  End If
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
     ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
     w_filtro = "<tr valign=""top"">"
    If p_responsavel           > "" Then w_filtro = w_filtro & "<td><font size=1>Responsável&nbsp;<font size=1>[<b>" & p_responsavel & "</b>]&nbsp;"                     End If
    If p_selecao_mp            > "" Then w_filtro = w_filtro & "<td><font size=1>Selecionada SPI/MP&nbsp;<font size=1>[<b>" & p_selecao_mp & "</b>]&nbsp;"               End If
    If p_selecao_se            > "" Then w_filtro = w_filtro & "<td><font size=1>Selecionada SE/SEPPIR&nbsp;<font size=1>[<b>" & p_selecao_se & "</b>]&nbsp;"                End If
    If p_loa                   > "" Then w_filtro = w_filtro & "<td><font size=1>Indicador PPA&nbsp;<font size=1>[<b>" & p_loa & "</b>]&nbsp;"                           End If
    If p_exequivel             > "" Then w_filtro = w_filtro & "<td><font size=1>Indicador será cumprido&nbsp;<font size=1>[<b>" & p_exequivel & "</b>]&nbsp;"           End If
    If p_atraso                > "" Then w_filtro = w_filtro & "<td><font size=1>Programas em atraso&nbsp;<font size=1>[<b>" & p_atraso & "</b>]&nbsp;"                  End If
    ShowHTML "<tr><td align=""left"">"
    If w_filtro                > "" Then ShowHTML "<table border=0><tr valign=""top""><td><font size=1><b>Filtro:</b><td nowrap><font size=1><ul>" & w_filtro & "</ul></tr></table>"                End If
    ShowHTML "<tr><td align=""center"" colspan=""2"">"
    ShowHTML "      <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td rowspan=""1"" colspan=""2""><font size=""1""><b>Programas</font></td>"
    ShowHTML "          <td rowspan=""1"" colspan=""7""><font size=""1""><b>Indicadores</font></td>"
    ShowHTML "        </tr>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Cód</font></td>"
    ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
    ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
    ShowHTML "          <td><font size=""1""><b>Índice de<br>referência</font></td>"
    ShowHTML "          <td><font size=""1""><b>Unidade de<br>medida</font></td>"
    ShowHTML "          <td><font size=""1""><b>Índice<br>programado</font></td>"
    ShowHTML "          <td><font size=""1""><b>Indicador<br>PPA</font></td>"
    ShowHTML "          <td><font size=""1""><b>Índice<br>apurado</font></td>"
    ShowHTML "          <td><font size=""1""><b>Data de<br>apuração</font></td>"
    ShowHTML "        </tr>"    
    If RS.EOF Then ' Se não foram selecionados registros, exibe mensagem
       w_cont = w_cont + 1
       w_linha = w_linha + 1
       ShowHTML "    <tr bgcolor=""" & conTrBgColor & """><td colspan=16 align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      w_atual = 0
      ' Lista os registros selecionados para listagem
      While Not RS.EOF
         If w_linha > 19 and w_tipo_rel = "WORD" Then
            ShowHTML "    </table>"
            ShowHTML "  </td>"
            ShowHTML "</tr>"
            ShowHTML "</table>"
            ShowHTML "</center></div>"
            ShowHTML "    <br style=""page-break-after:always"">"
            w_linha = 6
            w_pag   = w_pag + 1
            ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "WORD") & """><TD ALIGN=""RIGHT""><B><FONT SIZE=4 COLOR=""#000000"">"
            ShowHTML "Programas do PPA"
            ShowHTML "</FONT><TR><TD WIDTH=""50%"" ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
            ShowHTML "<TR><TD COLSPAN=""2"" ALIGN=""RIGHT""><B><FONT SIZE=2 COLOR=""#000000"">Página: " & w_pag & "</B></TD></TR>"
            ShowHTML "</TD></TR>"
            ShowHTML "</FONT></B></TD></TR></TABLE>"
            ShowHTML "<div align=center><center>"
            ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
            w_filtro = "<tr valign=""top"">"
            If p_responsavel           > "" Then w_filtro = w_filtro & "<td><font size=1>Responsável&nbsp;<font size=1>[<b>" & p_responsavel & "</b>]&nbsp;"                     End If
            If p_selecao_mp            > "" Then w_filtro = w_filtro & "<td><font size=1>Selecionada SPI/MP&nbsp;<font size=1>[<b>" & p_selecao_mp & "</b>]&nbsp;"             End If
            If p_selecao_se            > "" Then w_filtro = w_filtro & "<td><font size=1>Selecionada SE/SEPPIR&nbsp;<font size=1>[<b>" & p_selecao_se & "</b>]&nbsp;" End If
            If p_loa                   > "" Then w_filtro = w_filtro & "<td><font size=1>Indicador PPA&nbsp;<font size=1>[<b>" & p_loa & "</b>]&nbsp;"                         End If
            If p_exequivel             > "" Then w_filtro = w_filtro & "<td><font size=1>Indicador será cumprido&nbsp;<font size=1>[<b>" & p_exequivel & "</b>]&nbsp;"                End If
            If p_atraso                > "" Then w_filtro = w_filtro & "<td><font size=1>Programas em atraso&nbsp;<font size=1>[<b>" & p_atraso & "</b>]&nbsp;"                      End If
            ShowHTML "<tr><td align=""left"">"
            If w_filtro                > "" Then ShowHTML "<table border=0><tr valign=""top""><td><font size=1><b>Filtro:</b><td nowrap><font size=1><ul>" & w_filtro & "</ul></tr></table>"                End If
            ShowHTML "<tr><td align=""center"" colspan=""2"">"
            ShowHTML "      <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
            ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
            ShowHTML "          <td rowspan=""1"" colspan=""2""><font size=""1""><b>Programas</font></td>"
            ShowHTML "          <td rowspan=""1"" colspan=""7""><font size=""1""><b>Indicadores</font></td>"
            ShowHTML "        </tr>"
            ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
            ShowHTML "          <td><font size=""1""><b>Cód</font></td>"
            ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
            ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
            ShowHTML "          <td><font size=""1""><b>Índice de<br>referência</font></td>"
            ShowHTML "          <td><font size=""1""><b>Unidade de<br>medida</font></td>"
            ShowHTML "          <td><font size=""1""><b>Índice<br>programado</font></td>"
            ShowHTML "          <td><font size=""1""><b>Indicador<br>PPA</font></td>"
            ShowHTML "          <td><font size=""1""><b>Índice<br>apurado</font></td>"
            ShowHTML "          <td><font size=""1""><b>Data de<br>apuração</font></td>"
            ShowHTML "        </tr>"    
          End If

          'Montagem da lista de programa
          DB_GetLinkData RS1, w_cliente, "ISPCAD"
          DB_GetSolicList_IS RS2, RS1("sq_menu"), w_usuario, RS1("sigla"), 4, _
             null, null, null, null, p_atraso, null, null, null, null, null, null, null, _
             null, null, null, null, null, null, null, null, null, null, null, null, _
             RS("cd_programa"), null, null, w_ano
          If p_responsavel > "" Then
             RS2.Filter = "nm_gerente_programa like '%" &p_responsavel& "%'"
          End If
          'Variarel para o teste de existencia de metas e açoes para visualização no relatorio
          w_teste_indicador = 0
          w_teste_programas = 0 
             
          'Recuperação e verificação das metas das ações de acordo com a visão do usuário
          If Not RS2.EOF Then
             w_teste_programas = 1
             w_visao = 0
             If w_visao < 2 Then    
                DB_GetSolicIndic_IS RS3, RS2("sq_siw_solicitacao"), null, "LISTA"           
                If p_loa > "" and p_exequivel    > "" Then
                   RS3.Filter = "cd_indicador <> null and exequivel = '" & p_exequivel & "'"
                ElseIf p_loa > "" Then
                   RS3.Filter = "cd_indicador <> null"
                ElseIf p_exequivel    > "" Then
                   RS3.Filter = "exequivel = '" & p_exequivel & "'"
                End If
                RS3.Sort = "ordem"
                If Not RS3.EOF Then
                   w_teste_indicador = 1
                ElseIf p_loa = "" and p_exequivel = "" Then
                   w_teste_indicador = 3
                End If
             Else
                w_teste_indicador = 0
             End If
          Else
             If RS("sq_siw_solicitacao") > "" Then
                w_teste_programas = 1
                w_teste_indicador = 0
             Else
                w_teste_programas = 0
             End If
          End If
            
          If w_teste_indicador = 1 or w_teste_indicador = 3 Then
             'Inicio da montagem da lista dos programas e indicadores de acordo com o filtro
             w_cont = w_cont + 1
             If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
             'If RS("cd_programa") <> w_teste_pai or p_loa > "" or p_exequivel > "" or p_atraso > "" Then
             '   ShowHTML " <tr bgcolor=""" & conTrAlternateBgColor & """ valign=""top"">"
             '   ShowHTML "   <td><font size=""1""><b>" & RS("cd_programa") & "</td>"
             '   ShowHTML "   <td><font size=""1""><b>" & RS("ds_programa") & "</td>"
             '   RS.MoveNext
             '   w_atual = 1
             'Else
             '   ShowHTML " <tr valign=""top"">"
             '   ShowHTML "   <td><font size=""1""><b>&nbsp;</td>"
             '   ShowHTML "   <td><font size=""1""><b>&nbsp;</td>"
             'End If
             'w_linha = w_linha + 1
             ShowHTML " <tr bgcolor=""" & conTrAlternateBgColor & """ valign=""top"">"
             ShowHTML "      <td nowrap><font size=""1""><b>" & RS("cd_programa") & "</td>"
             If w_tipo_rel = "WORD" or RS("sq_siw_solicitacao") = "" Then
                ShowHTML "   <td><font size=""1""><b>" & RS("ds_programa") & "</td>"
             Else
                ShowHTML "   <td><font size=""1""><b><A class=""HL"" HREF=""" & w_dir & "Programa.asp?par=Visual&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=&P1=2&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ TARGET=""VisualAcao"" title=""Exibe as informações do programa."">" & RS("ds_programa") & "</a></td>"
             End If
             If RS2.EOF Then
                ShowHTML "   <td colspan=""6"" align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td>"
             Else
                If RS3.EOF Then ' Se não foram selecionados registros, exibe mensagem
                   ShowHTML "<td colspan=""7"" align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td></tr>"
                Else
                   If w_tipo_rel = "WORD" Then
                      ShowHTML "<td><font size=""1"">" & Rs3("titulo") & "</td>"
                   Else
                      ShowHTML "<td><font size=""1""><A class=""HL"" HREF=""#"" onClick=""window.open('Programa.asp?par=AtualizaIndicador&O=V&w_chave=" & RS2("sq_siw_solicitacao") & "&w_chave_aux=" &  Rs3("sq_indicador")  & "&w_tipo=Volta&P1=10&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "','Meta','width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no'); return false;"" title=""Clique para exibir os dados!"">" & Rs3("titulo") & "</A></td>"
                   End If
                   ShowHTML "      <td nowrap align=""right""><font size=""1"">" & FormatNumber(cDbl(Nvl(Rs3("valor_referencia"),0)),2) & "</td>"
                   ShowHTML "      <td nowrap align=""left"" ><font size=""1"">" & Nvl(Rs3("nm_unidade_medida"),"---") & "</td>"
                   ShowHTML "      <td nowrap align=""right"" ><font size=""1"">" & FormatNumber(cDbl(Nvl(Rs3("quantidade"),0)),2) & "</td>"
                   If RS3("cd_indicador") > "" Then
                      ShowHTML "<td nowrap align=""center"" ><font size=""1"">Sim</td>"
                   Else
                      ShowHTML "<td nowrap align=""center"" ><font size=""1"">Não</td>"
                   End If
                   ShowHTML "<td nowrap align=""right"" ><font size=""1"">" & FormatNumber(cDbl(Nvl(Rs3("valor_apurado"),0)),2) & "</td>"
                   ShowHTML "<td nowrap align=""center"" ><font size=""1"">" & Nvl(FormataDataEdicao(Rs3("apuracao_indice")),"---") & "</td>"
                   RS3.MoveNext
                   If Not RS3.EOF Then
                      While Not RS3.EOF
                         ShowHTML "<tr><td colspan=""2"">&nbsp;"
                         If w_tipo_rel = "WORD" Then
                            ShowHTML "<td><font size=""1"">" & Rs3("titulo") & "</td>"
                         Else
                            ShowHTML "<td><font size=""1""><A class=""HL"" HREF=""#"" onClick=""window.open('Programa.asp?par=AtualizaIndicador&O=V&w_chave=" & RS2("sq_siw_solicitacao") & "&w_chave_aux=" &  Rs3("sq_indicador")  & "&w_tipo=Volta&P1=10&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "','Meta','width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no'); return false;"" title=""Clique para exibir os dados!"">" & Rs3("titulo") & "</A></td>"
                         End If
                         ShowHTML "      <td nowrap align=""right""><font size=""1"">" & FormatNumber(cDbl(Nvl(Rs3("valor_referencia"),0)),2) & "</td>"
                         ShowHTML "      <td nowrap align=""left"" ><font size=""1"">" & Nvl(Rs3("nm_unidade_medida"),"---") & "</td>"
                         ShowHTML "      <td nowrap align=""right"" ><font size=""1"">" & FormatNumber(cDbl(Nvl(Rs3("quantidade"),0)),2) & "</td>"
                         If RS3("cd_indicador") > "" Then
                            ShowHTML "<td nowrap align=""center"" ><font size=""1"">Sim</td>"
                         Else
                            ShowHTML "<td nowrap align=""center"" ><font size=""1"">Não</td>"
                         End If
                         ShowHTML "<td nowrap align=""right"" ><font size=""1"">" & FormatNumber(cDbl(Nvl(Rs3("valor_apurado"),0)),2) & "</td>"
                         ShowHTML "<td nowrap align=""center"" ><font size=""1"">" & Nvl(FormataDataEdicao(Rs3("apuracao_indice")),"---") & "</td>"
                         RS3.MoveNext
                      Wend
                   End If
                End If
             End If
          Else
             If p_loa = "" and p_exequivel = "" and p_atraso = "" Then
                w_cont = w_cont + 1
                'If RS("cd_programa") <> w_teste_pai Then
                '   ShowHTML " <tr bgcolor=""" & conTrAlternateBgColor & """ valign=""top"">"
                '   ShowHTML "   <td><font size=""1""><b>" & RS("cd_programa") & "</td>"
                '   ShowHTML "   <td><font size=""1""><b>" & RS("ds_programa") & "</td>"
                '   w_atual = 1
                'Else
                '   ShowHTML " <tr valign=""top"">"
                '   ShowHTML "   <td><font size=""1""><b>&nbsp;</td>"
                '   ShowHTML "   <td><font size=""1""><b>&nbsp;</td>"
                'End If
                w_linha = w_linha + 1
                ShowHTML " <tr bgcolor=""" & conTrAlternateBgColor & """ valign=""top"">"
                If w_teste_programas = 1 Then
                   ShowHTML "        <td colspan=""1"" nowrap><font size=""1""><b>" & RS("cd_programa") & "</b></td>"
                   ShowHTML "        <td colspan=""1""><font size=""1""><b>" & RS("ds_programa") & "</b></td>"
                   If w_teste_indicador = 3 Then
                      ShowHTML "        <td colspan=""7"" align=""center""><font size=""1""><b>Não foram encontrados registros.<b></td>"
                   Else
                      ShowHTML "        <td colspan=""7"" align=""center""><font size=""1""><b>Não há permissão para visualização do programa<b></td>"
                   End If
                Else
                   ShowHTML "        <td colspan=""1"" nowrap><font size=""1""><b>" & RS("cd_programa") & "</b></td>"
                   ShowHTML "        <td colspan=""1""><font size=""1""><b>" & RS("ds_programa") & "</b></td>"
                   ShowHTML "        <td colspan=""7"" align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td>"
                End If
             End If
          End If
          'w_teste_pai = RS("cd_programa")
          RS.MoveNext
       wend
    End If
    If w_cont = 0 Then
       ShowHTML "        <td colspan=""17"" align=""center""><font size=""1""><b>Não foram encontrados registros.</b></td>"
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    DesconectaBD
  ElseIf O = "P" Then
    AbreForm "Form", w_dir & w_pagina & par, "POST", "return(Validacao(this));", "Programa",P1,P2,P3,P4,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr>"
    SelecaoProgramaPPA "<u>P</u>rograma PPA:", "P", null, w_cliente, w_ano, p_cd_programa, "p_cd_programa", null, null, w_menu
    ShowHTML "      <tr><td><font size=""1""><b><u>R</u>esponsável:</b><br><input " & w_disabled & " accesskey=""R"" type=""text"" name=""p_responsavel"" class=""sti"" SIZE=""40"" MAXLENGTH=""60"" VALUE=""" & p_responsavel & """></td>"
    ShowHTML "      <tr><td colspan=3><table border=0 width=""100%"" cellspacing=0 cellpadding=0><tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Selecionada SPI/MP?</b><br>"
    ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecao_mp"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""p_selecao_mp"" value=""N""> Não <input " & w_Disabled & " type=""radio"" name=""p_selecao_mp"" value="""" checked> Independe"
    ShowHTML "          <td><font size=""1""><b>Selecionada SE/SEPPIR?</b><br>"
    ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_selecao_se"" value=""S""> Sim <input " & w_Disabled & " type=""radio"" name=""p_selecao_se"" value=""N""> Não <input " & w_Disabled & " type=""radio"" name=""p_selecao_se"" value="""" checked> Independe"
    ShowHTML "      <tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Exibir somente indicadores do PPA?</b><br>"
    ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_loa"" value=""S""> Sim <br><input " & w_Disabled & " type=""radio"" name=""p_loa"" value="""" checked> Não"
    ShowHTML "          <td><font size=""1""><b>Exibir somente indicadores que não serão cumpridos?</b><br>"
    ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_exequivel"" value=""N""> Sim <br><input " & w_Disabled & " type=""radio"" name=""p_exequivel"" value="""" checked> Não"
    ShowHTML "      <tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Exibir somente programas em atraso?</b><br>"
    ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_atraso"" value=""S""> Sim <br><input " & w_Disabled & " type=""radio"" name=""p_atraso"" value="""" checked> Não"
    ShowHTML "          </table>"
    ShowHTML "    <table width=""90%"" border=""0"">"            
    ShowHTML "      <tr><td align=""center""><hr>"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Exibir"">"
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
  
  If w_tipo_rel <> "WORD" Then
     Rodape
  End If
  
  Set w_titulo                  = Nothing 
  Set w_logo                    = Nothing 
  Set w_atual                   = Nothing 
  Set p_cd_programa             = Nothing 
  Set p_selecao_mp              = Nothing 
  Set p_selecao_se              = Nothing 
  Set p_responsavel             = Nothing 
  Set p_sq_unidade_resp         = Nothing
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Relatório do Plano Gerencial de Ações
REM -------------------------------------------------------------------------
Sub Rel_Gerencial_Acao

  Dim p_codigo, w_Erro, w_logo, w_tipo, w_chave

  p_codigo          = Request("p_codigo")
  w_tipo            = uCase(Trim(Request("w_tipo")))            
  
  If O = "L" Then
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
     ShowHTML "<TITLE>Plano Gerencial - Ações PPA 2004 - 2007 Exercício " & w_ano & "</TITLE>"
     
     ShowHTML "</HEAD>"  
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     If w_tipo <> "WORD" Then
        BodyOpenClean "onLoad='document.focus()'; "
     End If
     ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "WORD") & """><TD ALIGN=""RIGHT""><B><FONT SIZE=4 COLOR=""#000000"">"
     If P1 = 1 Then
        ShowHTML "Relatório Geral por Ação"
     ElseIf P1 = 2 Then
        ShowHTML "Plano Plurianual 2004 - 2007 <BR> Relatório Geral por Ação"
     Else
        ShowHTML "Plano Gerencial - Ações PPA 2004 - 2007 Exercício " & w_ano
     End If
     ShowHTML "</FONT><TR><TD ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
     If w_tipo <> "WORD" Then
        ShowHTML "&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Imprimir"" SRC=""images/impressora.jpg"" onClick=""window.print();"">"
        ShowHTML "&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Gerar word"" SRC=""images/word.gif"" onClick=""window.open('" & w_pagina & "Rel_Gerencial_Acao&R=" & w_pagina & par & "&O=L&w_tipo=word&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=1&TP=" & TP & "&SG=" & SG & "&w_chave=" & w_chave &  MontaFiltro("GET") &"','VisualAcaoWord','menubar=yes resizable=yes scrollbars=yes');"">"
     End If
     ShowHTML "</TD></TR>"
     ShowHTML "</FONT></B></TD></TR></TABLE>"
     'ShowHTML "<HR>"
     DB_GetAcaoPPA_IS RS, w_cliente, w_ano, Mid(p_codigo,1,4), Mid(p_codigo,5,4), null, Mid(p_codigo,13,17), null, null, null

     If Nvl(RS("sq_siw_solicitacao"),"") = "" Then
        ScriptOpen "JavaScript"
        ShowHTML "alert('Ação não cadastrada!');"
        ShowHTML "window.close();"
        ScriptClose
        Exit Sub
     Else
        w_chave = RS("sq_siw_solicitacao")
     End If
     DesconectaBD

     ' Chama a rotina de visualização dos dados da programa de acordo com o Plano Gerencial

     ShowHTML VisualAcaoGer(w_chave, P4)

     If w_tipo <> "WORD" Then
        Rodape
     End If
  ElseIf O = "P" Then
     Cabecalho
     ShowHTML "<HEAD>"
     ShowHTML "<TITLE>Ações - Relatório Gerencial " & w_ano & "</TITLE>"
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     Validate "p_codigo", "Ação PPA", "SELECT", "1", "1", "18", "1", "1"
     ValidateClose
     ScriptClose
     ShowHTML "</HEAD>"
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     BodyOpen "onLoad='document.Form.p_codigo.focus()';"
     ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
     ShowHTML "<HR>"
     
     ShowHTML "<div align=center><center>"
     ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"

     AbreForm "Form", w_dir & w_pagina & par, "POST", "return(Validacao(this));", "Acao",P1,P2,P3,P4,TP,SG,R,"L"

     ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
     ShowHTML "    <table width=""97%"" border=""0"">"
     ShowHTML "      <tr>"
     SelecaoAcaoPPA "<u>A</u>ção PPA:", "A", null, w_cliente, w_ano, null, null, null, null, "p_codigo", null, null, null, w_menu
     ShowHTML "          </table>"
     ShowHTML "    <table width=""90%"" border=""0"">"            
     ShowHTML "      <tr><td align=""center""><hr>"
     ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Exibir"">"
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
REM Relatório do Plano Gerencial de Programas
REM -------------------------------------------------------------------------
Sub Rel_Gerencial_Prog

  Dim p_cd_programa, w_Erro, w_logo, w_tipo, w_chave

  p_cd_programa     = Request("p_cd_programa")
  w_tipo            = uCase(Trim(Request("w_tipo")))            
  
  If O = "L" Then
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
     ShowHTML "<TITLE>Plano Gerencial - Programas PPA 2004 - 2007 Exercício " & w_ano & "</TITLE>"
     
     ShowHTML "</HEAD>"  
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     If w_tipo <> "WORD" Then
        BodyOpenClean "onLoad='document.focus()'; "
     End If
     ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "WORD") & """><TD ALIGN=""RIGHT"" NOWRAP><B><FONT SIZE=4 COLOR=""#000000"">"
     If P1 = 1 Then
        ShowHTML "Relatório Geral por Programa"
     ElseIf P1 = 2 Then
        ShowHTML "Plano Plurianual 2004 - 2007 <BR> Relatório Geral por Programa"
     Else
        ShowHTML "Plano Gerencial - Programas PPA 2004 - 2007 Exercício " & w_ano
     End If
     ShowHTML "</FONT><TR><TD ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
     If w_tipo <> "WORD" Then
        ShowHTML "&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Imprimir"" SRC=""images/impressora.jpg"" onClick=""window.print();"">"
        ShowHTML "&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Gerar word"" SRC=""images/word.gif"" onClick=""window.open('" & w_pagina & "Rel_Gerencial_Prog&R=" & w_pagina & par & "&O=L&w_tipo=word&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=1&TP=" & TP & "&SG=" & SG & "&w_chave=" & w_chave &  MontaFiltro("GET") &"','VisualProgramaWord','menubar=yes resizable=yes scrollbars=yes');"">"
     End If
     ShowHTML "</TD></TR>"
     ShowHTML "</FONT></B></TD></TR></TABLE>"
     'ShowHTML "<HR>"
     DB_GetProgramaPPA_IS RS, p_cd_programa, w_cliente, w_ano, null, null

     If Nvl(RS("sq_siw_solicitacao"),"") = "" Then
        ScriptOpen "JavaScript"
        ShowHTML "alert('Programa nao cadastrado!');"
        ShowHTML "window.close();"
        ScriptClose
        Exit Sub
     Else
        w_chave = RS("sq_siw_solicitacao")
     End If
     DesconectaBD

     ' Chama a rotina de visualização dos dados da programa de acordo com o Plano Gerencial

     ShowHTML VisualProgramaGer(w_chave, P4)

     If w_tipo <> "WORD" Then
        Rodape
     End If
  ElseIf O = "P" Then
     Cabecalho
     ShowHTML "<HEAD>"
     ShowHTML "<TITLE>Programas - Relatório Gerencial " & w_ano & "</TITLE>"
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     Validate "p_cd_programa", "Programa", "SELECT", "1", "1", "18", "1", "1"
     ValidateClose
     ScriptClose
     ShowHTML "</HEAD>"
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     BodyOpen "onLoad='document.Form.p_cd_programa.focus()';"
     ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
     ShowHTML "<HR>"
     
     ShowHTML "<div align=center><center>"
     ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"

     AbreForm "Form", w_dir & w_pagina & par, "POST", "return(Validacao(this));", "Programa",P1,P2,P3,P4,TP,SG,R,"L"

     ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
     ShowHTML "    <table width=""97%"" border=""0"">"
     ShowHTML "      <tr>"
     SelecaoProgramaPPA "<u>P</u>rograma PPA:", "P", null, w_cliente, w_ano, p_cd_programa, "p_cd_programa", null, null, w_menu
     ShowHTML "          </table>"
     ShowHTML "    <table width=""90%"" border=""0"">"            
     ShowHTML "      <tr><td align=""center""><hr>"
     ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Exibir"">"
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
  
  If w_tipo <> "WORD" Then
     Rodape
  End If
  
  Set w_tipo                = Nothing 
  Set w_erro                = Nothing 
  Set w_logo                = Nothing 
  Set w_chave               = Nothing

End Sub

REM =========================================================================
REM Relatório do Plano Gerencial de Tarefas
REM -------------------------------------------------------------------------
Sub Rel_Gerencial_Tarefa

  Dim w_Erro, w_logo, w_tipo, w_chave, p_acao, w_troca

  p_acao            = Request("p_acao")
  w_chave           = Request("w_chave")
  w_troca           = Request("w_troca")
  w_tipo            = uCase(Trim(Request("w_tipo")))            
  If O = "L" Then
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
     ShowHTML "<TITLE>" & conSgSistema & " - Visualização de Tarefa</TITLE>"
     
     ShowHTML "</HEAD>"  
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     If w_tipo <> "WORD" Then
        BodyOpenClean "onLoad='document.focus()'; "
     End If
     ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "WORD") & """><TD ALIGN=""RIGHT""><B><FONT SIZE=4 COLOR=""#000000"">"
     ShowHTML "Visualização de Tarefa"
     ShowHTML "</FONT><TR><TD ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
     If w_tipo <> "WORD" Then
        ShowHTML "&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Imprimir"" SRC=""images/impressora.jpg"" onClick=""window.print();"">"
        ShowHTML "&nbsp;&nbsp;<IMG ALIGN=""CENTER"" TITLE=""Gerar word"" SRC=""images/word.gif"" onClick=""window.open('" & w_pagina & "Rel_Gerencial_Tarefa&R=" & w_pagina & par & "&O=L&w_tipo=word&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=1&TP=" & TP & "&SG=" & SG & "&w_chave=" & w_chave &  MontaFiltro("GET") &"','VisualProgramaWord','menubar=yes resizable=yes scrollbars=yes');"">"
     End If
     ShowHTML "</TD></TR>"
     ShowHTML "</FONT></B></TD></TR></TABLE>"
     ShowHTML "<HR>"

     ' Chama a rotina de visualização dos dados da programa de acordo com o Plano Gerencial

     ShowHTML VisualTarefaGer(w_chave, P4)

     If w_tipo <> "WORD" Then
        'Rodape
     End If
  ElseIf O = "P" Then
     Cabecalho
     ShowHTML "<HEAD>"
     ShowHTML "<TITLE>Tarefas - Relatório Gerencial " & w_ano & "</TITLE>"
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     Validate "p_acao", "Acao", "SELECT", "1", "1", "18", "1", "1"
     Validate "w_chave", "Tarefa", "SELECT", "1", "1", "18", "1", "1"
     ValidateClose
     ScriptClose
     ShowHTML "</HEAD>"
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     If w_troca > "" Then
        BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
     Else
        BodyOpen "onLoad='document.Form.p_acao.focus()';"
     End If
     ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
     ShowHTML "<HR>"
     
     ShowHTML "<div align=center><center>"
     ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"

     AbreForm "Form", w_dir & w_pagina & par, "POST", "return(Validacao(this));", "Tarefa",P1,P2,P3,P4,TP,SG,R,"L"
     ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value=""" & w_troca & """>"

     ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
     ShowHTML "    <table width=""97%"" border=""0"">"
     ShowHTML "      <tr>"     
     'DB_GetLinkData RS, w_cliente, "ISACAD"
     'SelecaoProjeto "Açã<u>o</u>:", "O", "Selecione a ação da tarefa na relação.", p_acao, w_usuario, RS("sq_menu"), "p_acao", "PJLIST", "onchange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='P'; document.Form.w_troca.value='w_chave'; document.Form.target=''; document.Form.submit();"""
     SelecaoAcao "Açã<u>o</u>:", "O", "Selecione a ação da tarefa na relação.", w_cliente, w_ano, null, null, null, null, "p_acao", "ACAO", "onchange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.O.value='P'; document.Form.w_troca.value='w_chave'; document.Form.target=''; document.Form.submit();""", p_acao
     'DesconectaBD
     ShowHTML "      <tr>"
     SelecaoTarefa "<u>T</u>arefa:", "T", null, w_cliente, w_ano, w_chave, "w_chave", Nvl(p_acao,0), null
     ShowHTML "          </table>"
     ShowHTML "    <table width=""90%"" border=""0"">"            
     ShowHTML "      <tr><td align=""center""><hr>"
     ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Exibir"">"
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
  
  If w_tipo <> "WORD" Then
     Rodape
  End If
  
  Set w_tipo                = Nothing 
  Set w_erro                = Nothing 
  Set w_logo                = Nothing 
  Set w_chave               = Nothing
  Set p_acao                = Nothing
  Set w_troca               = Nothing

End Sub

REM =========================================================================
REM Relatório para acompanhamento das metas das Ações do PPA
REM -------------------------------------------------------------------------
Sub Rel_Metas
  Dim p_sq_unidade, p_cd_acao, p_cd_programa, w_tipo_rel, p_preenchida
  Dim p_meta_ppa, p_exequivel
  Dim w_programa_atual, w_acao_atual, w_unidade_atual
  Dim w_logo
  Dim w_total_ppa, w_total_sisplam, w_preenchida_ppa, w_preenchida_sisplam, w_exequivel_ppa, w_exequivel_sisplam
  Dim w_conc_abaixo_ppa, w_conc_superior_ppa, w_conc_igual_ppa
  Dim w_conc_abaixo_sisplam, w_conc_superior_sisplam, w_conc_igual_sisplam
  
  
  p_sq_unidade               = ucase(Trim(Request("p_sq_unidade")))
  p_cd_programa              = ucase(Trim(Request("p_cd_programa")))
  p_cd_acao                  = ucase(Trim(Request("p_acao")))
  w_tipo_rel                 = uCase(trim(Request("w_tipo_rel")))
  p_preenchida               = ucase(Trim(Request("p_preenchida")))
  p_meta_ppa                 = ucase(Trim(Request("p_meta_ppa")))
  p_exequivel                = ucase(Trim(Request("p_exequivel")))
  
  w_cont                  = 0 
  w_total_ppa             = 0
  w_total_sisplam         = 0
  w_preenchida_ppa        = 0
  w_preenchida_sisplam    = 0
  w_exequivel_ppa         = 0
  w_exequivel_sisplam     = 0
  w_conc_abaixo_ppa       = 0
  w_conc_superior_ppa     = 0
  w_conc_igual_ppa        = 0
  w_conc_abaixo_sisplam   = 0
  w_conc_superior_sisplam = 0
  w_conc_igual_sisplam    = 0
  
  If O = "L" Then
     ' Recupera o logo do cliente a ser usado nas listagens
     DB_GetCustomerData RS, w_cliente
     If RS("logo") > "" Then
         w_logo = "\img\logo" & Mid(RS("logo"),Instr(RS("logo"),"."),30)
     End If
     DesconectaBD
  End If
  DB_GetSolicMeta_IS RS, null, null, "LSTNULL", w_ano, p_sq_unidade, p_cd_programa, p_cd_acao, p_preenchida, p_meta_ppa, p_exequivel
  RS.Sort = "cd_programa, cd_acao, cd_unidade, cd_subacao"
  If w_tipo_rel = "WORD" Then
     HeaderWord null
     w_pag   = 1
     w_linha = 8
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     ShowHTML "<div align=""center"">"
     ShowHTML "<table width=""95%"" border=""0"" cellspacing=""3"">"
     ShowHTML "<tr><td colspan=""2"">"     
     ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "WORD") & """></TD><TD ALIGN=""RIGHT"" NOWRAP><B><FONT SIZE=4 COLOR=""#000000"">"
     ShowHTML "RELATÓRIO DE CONFERÊNCIA DAS METAS NO SISPLAM <br> Exercício " & w_ano
     ShowHTML "</FONT></TD></TR></TABLE>"
  Else
     Cabecalho
     ShowHTML "<HEAD>"
     ShowHTML "<TITLE>Relatório Metas - Exercício " & w_ano & "</TITLE>"
     If InStr("P",O) > 0 Then
        ScriptOpen "JavaScript"
        ValidateOpen "Validacao"
        Validate "p_sq_unidade", "Responsável", "HIDDEN", "", "2", "60", "1", "1"
        Validate "p_cd_programa", "Programa", "HIDDEN", "", "1", "18", "1", "1"
        Validate "p_cd_acao", "Ação", "HIDDEN", "", "1", "18", "1", "1"
        Validate "w_tipo_rel", "Tipo de Relatório", "SELECT", "", "1", "30", "1", "1"
        Validate "p_preenchida", "Situação Atual da Meta", "SELECT", "", "1", "1", "1", "1"
        Validate "p_meta_ppa", "Tipo da Meta", "SELECT", "", "1", "1", "1", "1"
        Validate "p_exequivel", "Execução da Meta", "SELECT", "", "1", "1", "1", "1"
        ValidateClose
        ScriptClose
     End If
     ShowHTML "</HEAD>"
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     If O = "L" Then
        BodyOpenClean "onLoad='document.focus()';"
        ShowHTML "<BASE HREF=""" & conRootSIW & """>"
        ShowHTML "<div align=""center"">"
        ShowHTML "<table width=""95%"" border=""0"" cellspacing=""3"">"
        ShowHTML "<tr><td colspan=""2"">"             
        ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "EMBED") & """></TD><TD ALIGN=""RIGHT"" NOWRAP><B><FONT SIZE=4 COLOR=""#000000"">"
        ShowHTML "RELATÓRIO DE CONFERÊNCIA DAS METAS NO SISPLAM <br> Exercício " & w_ano
        ShowHTML "</FONT></B></TD></TR></TABLE>"
     Else
        BodyOpen "onLoad='document.Form.p_cd_programa.focus()';"
        ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
        ShowHTML "<div align=center><center>"
        ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
        ShowHTML "<HR>"
     End If
  End If
  If O = "L" Then
    ' Exibe a quantidade de registros apresentados na listagem e o cabeçalho da tabela de listagem
    ShowHTML "<tr><td colspan=""2""><div align=""center""><hr NOSHADE color=#000000 size=2></div></td></tr>"
    ShowHTML "<tr><td colspan=""2""><div align=""center"">"
    ShowHTML "<table border=""0"" width=""100%"">"
    If p_sq_unidade > "" Then
       ShowHTML "<tr><td width=""20%""><font size=""1""><b>Área de planejamento:</b></font></td><td><font size=""1"">" & RS("sg_segor") & "</font></td>"
    Else
       ShowHTML "<tr><td width=""20%""><font size=""1""><b>Área de planejamento:</b></font></td><td><font size=""1"">Todas</font></td>"
    End If
    If p_cd_programa > "" Then
       ShowHTML "    <td width=""20%""><font size=""1""><b>Programa:</b></font></td><td><font size=""1"">" & RS("descricao_programa") & "</font></td></tr>"
    Else
       ShowHTML "    <td width=""20%""><font size=""1""><b>Programa:</b></font></td><td><font size=""1"">Todos</font></td></tr>"
    End If
    If p_cd_acao > "" Then 
       ShowHTML "<tr><td><font size=""1""><b>Ação:</b></font></td><td><font size=""1"">" & RS("descricao_acao") & "</font></td>"
    Else
       ShowHTML "<tr><td><font size=""1""><b>Ação:</b></font></td><td><font size=""1"">Todas</font></td>"
    End If
    If p_preenchida > "" Then 
       If p_preenchida = "S" Then
          ShowHTML "    <td><font size=""1""><b>Situação atual da meta:</b></font></td><td><font size=""1"">Sim</font></td></tr>"
       Else
          ShowHTML "    <td><font size=""1""><b>Situação atual da meta:</b></font></td><td><font size=""1"">Não</font></td></tr>"
       End If
    Else
       ShowHTML "    <td><font size=""1""><b>Situação atual da meta:</b></font></td><td><font size=""1"">Todas</font></td></tr>"
    End If
    If p_meta_ppa > "" Then 
       If p_meta_ppa = "S" Then
          ShowHTML "    <td><font size=""1""><b>Meta PPA:</b></font></td><td><font size=""1"">Sim</font></td>"
       Else
          ShowHTML "    <td><font size=""1""><b>Meta PPA:</b></font></td><td><font size=""1"">Não</font></td>"
       End If
    Else
       ShowHTML "    <td><font size=""1""><b>Meta PPA:</b></font></td><td><font size=""1"">Todas</font></td>"
    End If
    If p_exequivel > "" Then 
       If p_exequivel = "S" Then
          ShowHTML "    <td><font size=""1""><b>Meta será cumprida:</b></font></td><td><font size=""1"">Sim</font></td></tr>"
       Else
          ShowHTML "    <td><font size=""1""><b>Meta será cumprida:</b></font></td><td><font size=""1"">Não</font></td></tr>"
       End If
    Else
       ShowHTML "    <td><font size=""1""><b>Meta será cumprida:</b></font></td><td><font size=""1"">Todas</font></td></tr>"
    End If     
    ShowHTML "</ul></td></tr></table>"
    ShowHTML "</div></td></tr>"
    ShowHTML "<tr><td colspan=""2""><div align=""center""><hr NOSHADE color=#000000 size=2></div></td></tr>"    
    ShowHTML "<tr><td colspan=""2""><div align=""center""><font size=""3""><b>RELATÓRIO DE CONFERÊNCIA</b></font></div></td></tr>"
    ShowHTML "<tr><td colspan=""2"">"
    ShowHTML "      <table border=""1"" bordercolor=""#00000"" width=""100%"">"
    ShowHTML "        <tr>"
    ShowHTML "          <td bgColor=""#f0f0f0"" rowspan=""1"" colspan=""2""><font size=""1""><b>Programas</b></font></td>"
    ShowHTML "          <td bgColor=""#f0f0f0"" rowspan=""1"" colspan=""2""><font size=""1""><b>Ações</b></font></td>"
    ShowHTML "          <td bgColor=""#f0f0f0"" rowspan=""1"" colspan=""2""><font size=""1""><b>Unidade</b></font></td>"
    ShowHTML "          <td bgColor=""#f0f0f0"" rowspan=""1"" colspan=""7""><font size=""1""><b>Metas</font></td>"
    ShowHTML "        </tr>"
    ShowHTML "        <tr>"
    ShowHTML "          <td bgColor=""#f0f0f0""><font size=""1""><b>Cód</b></font></td>"
    ShowHTML "          <td bgColor=""#f0f0f0""><font size=""1""><b>Nome</b></font></td>"
    ShowHTML "          <td bgColor=""#f0f0f0""><font size=""1""><b>Cód</b></font></td>"
    ShowHTML "          <td bgColor=""#f0f0f0""><font size=""1""><b>Nome</b></font></td>"
    ShowHTML "          <td bgColor=""#f0f0f0""><font size=""1""><b>Cód</b></font></td>"
    ShowHTML "          <td bgColor=""#f0f0f0""><font size=""1""><b>Nome</b></font></td>"
    ShowHTML "          <td bgColor=""#f0f0f0""><font size=""1""><b>Meta</b></font></td>"
    ShowHTML "          <td bgColor=""#f0f0f0""><font size=""1""><b>PPA</font></td>"
    ShowHTML "          <td bgColor=""#f0f0f0""><font size=""1""><b>Será<br>cumprida</font></td>"
    ShowHTML "          <td bgColor=""#f0f0f0""><font size=""1""><b>% Executado</font></td>"
    ShowHTML "          <td bgColor=""#f0f0f0""><font size=""1""><b>Situação<br>Atual</font></td>"
    ShowHTML "          <td bgColor=""#f0f0f0""><font size=""1""><b>Realizar<br>Até</font></td>"
    ShowHTML "          <td bgColor=""#f0f0f0""><font size=""1""><b>Fase</font></td>"
    ShowHTML "        </tr>"    
    If RS.EOF Then  
       w_linha = w_linha + 1
       ShowHTML "    <tr><td colspan=""13""><div align=""center""><font size=""1""><b>Não foram encontrados registros</b></div></td></tr>"
    Else
       ' Listagem das metas de acordo com o filtro selecionado na tela de filtragem
       While Not RS.EOF
          If w_linha > 19 and w_tipo_rel = "WORD" Then
             ShowHTML "    </table>"
             ShowHTML "  </td>"
             ShowHTML "</tr>"
             ShowHTML "</table>"
             ShowHTML "</center></div>"
             ShowHTML "    <br style=""page-break-after:always"">"
             w_linha = 6
             w_pag   = w_pag + 1
             ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "WORD") & """><TD ALIGN=""RIGHT""><B><FONT SIZE=4 COLOR=""#000000"">"
             ShowHTML "Ações do PPA"
                ShowHTML "</FONT><TR><TD WIDTH=""50%"" ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
             ShowHTML "<TR><TD COLSPAN=""2"" ALIGN=""RIGHT""><B><FONT SIZE=2 COLOR=""#000000"">Página: " & w_pag & "</B></TD></TR>"
             ShowHTML "</TD></TR>"
             ShowHTML "</FONT></B></TD></TR></TABLE>"
             ShowHTML "<div align=center><center>"
             ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
             ShowHTML "<tr><td colspan=""2""><div align=""center"">"
             ShowHTML "<table border=""0"" width=""100%"">"
             If p_sq_unidade > "" Then
                ShowHTML "<tr><td width=""20%""><font size=""1""><b>Área de planejamento:</b></font></td><td><font size=""1"">" & RS("sg_segor") & "</font></td>"
             Else
                ShowHTML "<tr><td width=""20%""><font size=""1""><b>Área de planejamento:</b></font></td><td><font size=""1"">Todas</font></td>"
             End If
             If p_cd_programa > "" Then
                ShowHTML "    <td width=""20%""><font size=""1""><b>Programa:</b></font></td><td><font size=""1"">" & RS("descricao_programa") & "</font></td></tr>"
             Else
                ShowHTML "    <td width=""20%""><font size=""1""><b>Programa:</b></font></td><td><font size=""1"">Todos</font></td></tr>"
             End If
             If p_cd_acao > "" Then 
                ShowHTML "<tr><td><font size=""1""><b>Ação:</b></font></td><td><font size=""1"">" & RS("descricao_acao") & "</font></td>"
             Else
                ShowHTML "<tr><td><font size=""1""><b>Ação:</b></font></td><td><font size=""1"">Todas</font></td>"
             End If
             If p_preenchida > "" Then 
                If p_preenchida = "S" Then
                   ShowHTML "    <td><font size=""1""><b>Situação atual da meta:</b></font></td><td><font size=""1"">Sim</font></td></tr>"
                Else
                   ShowHTML "    <td><font size=""1""><b>Situação atual da meta:</b></font></td><td><font size=""1"">Não</font></td></tr>"
                End If
             Else
                ShowHTML "    <td><font size=""1""><b>Situação atual da meta:</b></font></td><td><font size=""1"">Todas</font></td></tr>"
             End If
             If p_meta_ppa > "" Then 
                If p_meta_ppa = "S" Then
                   ShowHTML "    <td><font size=""1""><b>Meta PPA:</b></font></td><td><font size=""1"">Sim</font></td>"
                Else
                   ShowHTML "    <td><font size=""1""><b>Meta PPA:</b></font></td><td><font size=""1"">Não</font></td>"
                End If
              Else
                ShowHTML "    <td><font size=""1""><b>Meta PPA:</b></font></td><td><font size=""1"">Todas</font></td>"
             End If
             If p_exequivel > "" Then 
                If p_exequivel = "S" Then
                   ShowHTML "    <td><font size=""1""><b>Meta será cumprida:</b></font></td><td><font size=""1"">Sim</font></td></tr>"
                Else
                   ShowHTML "    <td><font size=""1""><b>Meta será cumprida:</b></font></td><td><font size=""1"">Não</font></td></tr>"
                End If
             Else
                ShowHTML "    <td><font size=""1""><b>Meta será cumprida:</b></font></td><td><font size=""1"">Todas</font></td></tr>"
             End If     
             ShowHTML "</ul></td></tr></table>"
             ShowHTML "</div></td></tr>"
             ShowHTML "<tr><td colspan=""2""><div align=""center""><hr NOSHADE color=#000000 size=2></div></td></tr>"    
             ShowHTML "<tr><td align=""center"" colspan=""2"">"
             ShowHTML "      <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
             ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
             ShowHTML "          <td rowspan=""1"" colspan=""2""><font size=""1""><b>Programas</font></td>"
             ShowHTML "          <td rowspan=""1"" colspan=""2""><font size=""1""><b>Ações</font></td>"
             ShowHTML "          <td rowspan=""1"" colspan=""2""><font size=""1""><b>Unidade</font></td>"
             ShowHTML "          <td rowspan=""1"" colspan=""7""><font size=""1""><b>Metas</font></td>"
             ShowHTML "        </tr>"
             ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
             ShowHTML "          <td><font size=""1""><b>Cód</font></td>"
             ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
             ShowHTML "          <td><font size=""1""><b>Cód</font></td>"
             ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
             ShowHTML "          <td><font size=""1""><b>Cód</font></td>"
             ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
             ShowHTML "          <td bgColor=""#f0f0f0""><font size=""1""><b>Meta</b></font></td>"
             ShowHTML "          <td bgColor=""#f0f0f0""><font size=""1""><b>PPA</font></td>"
             ShowHTML "          <td bgColor=""#f0f0f0""><font size=""1""><b>Será<br>cumprida</font></td>"
             ShowHTML "          <td bgColor=""#f0f0f0""><font size=""1""><b>% Executado</font></td>"
             ShowHTML "          <td bgColor=""#f0f0f0""><font size=""1""><b>Situação<br>Atual</font></td>"
             ShowHTML "          <td bgColor=""#f0f0f0""><font size=""1""><b>Realizar<br>Até</font></td>"
             ShowHTML "          <td bgColor=""#f0f0f0""><font size=""1""><b>Fase</font></td>"
             ShowHTML "        </tr>"    
          End If
          'Inicio da montagem da lista das ações e metas de acordo com o filtro
          If RS("cd_programa") <> w_programa_atual Then
             ShowHTML " <tr valign=""top"">"
             ShowHTML "   <td><font size=""1""><b>" & RS("cd_programa") & "</td>"
             ShowHTML "   <td><font size=""1""><b>" & RS("descricao_programa") & "</td>"
          Else
             ShowHTML " <tr>"
             ShowHTML "   <td><font size=""1""><b>&nbsp;</td>"
             ShowHTML "   <td><font size=""1""><b>&nbsp;</td>"
          End If
          w_linha = w_linha + 1
          If RS("cd_acao") <> w_acao_atual Then
             ShowHTML "      <td><font size=""1""><b>" & RS("cd_acao") & "</td>"
             If w_tipo_rel = "WORD" or RS("sq_siw_solicitacao") = "" Then
                ShowHTML "   <td><font size=""1""><b>" & RS("descricao_acao") & "</td>"
             Else
                ShowHTML "   <td><font size=""1""><b><A class=""HL"" HREF=""" & w_dir & "Acao.asp?par=Visual&O=L&w_chave=" & RS("sq_siw_solicitacao") & "&w_tipo=&P1=2&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """ TARGET=""VisualAcao"" title=""Exibe as informações da ação."">" & RS("descricao_acao") & "</a></td>"
             End If
          Else
             ShowHTML "      <td><font size=""1""><b>&nbsp;</td>"
             ShowHTML "      <td><font size=""1""><b>&nbsp;</td>"
          End If
          If RS("cd_unidade") <> w_unidade_atual Then
             ShowHTML "      <td><font size=""1""><b>" & RS("cd_unidade") & "</td>"
             ShowHTML "      <td><font size=""1""><b>" & RS("descricao_unidade") & "</td>"
          Else
             ShowHTML "      <td><font size=""1""><b>&nbsp;</td>"
             ShowHTML "      <td><font size=""1""><b>&nbsp;</td>"
          End If
          If w_tipo_rel = "WORD" Then
             ShowHTML "<td><font size=""1"">" & RS("titulo") & "</td>"
          Else
             ShowHTML "<td><font size=""1""><A class=""HL"" HREF=""#"" onClick=""window.open('Acao.asp?par=AtualizaMeta&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_chave_aux=" &  RS("sq_meta")  & "&w_tipo=Volta&P1=10&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "','Meta','width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no'); return false;"" title=""Clique para exibir os dados!"">" & RS("titulo") & "</A></td>"
          End If
          If Nvl(RS("cd_subacao"),"") > "" Then
             w_total_ppa = w_total_ppa + 1
             ShowHTML "   <td><div align=""center""><font size=""1"">Sim</font></div></td>"
          Else
             w_total_sisplam = w_total_sisplam + 1
             ShowHTML "   <td><div align=""center""><font size=""1"">Não</font></div></td>"
          End If
          If Nvl(RS("exequivel"),"") = "S" Then
             If Nvl(RS("cd_subacao"),"") > "" Then
                w_exequivel_ppa = w_exequivel_ppa + 1
             Else
                w_exequivel_sisplam = w_exequivel_sisplam + 1
             End If
             ShowHTML "   <td><div align=""center""><font size=""1"">Sim</font></div></td>"
          Else
             ShowHTML "   <td><div align=""center""><font size=""1"">Não</font></div></td>"
          End If
          ShowHTML "   <td><div align=""right""><font size=""1"">"&Nvl(RS("perc_conclusao"),0)&"%</font></div></td>"
          If cDbl(Nvl(RS("perc_conclusao"),0)) = 100 Then
             If Nvl(RS("cd_subacao"),"") > "" Then
                w_conc_igual_ppa = w_conc_igual_ppa + 1
             Else
                w_conc_igual_sisplam = w_conc_igual_sisplam + 1
             End If
          ElseIf cDbl(Nvl(RS("perc_conclusao"),0)) > 100 Then
             If Nvl(RS("cd_subacao"),"") > "" Then
                w_conc_superior_ppa = w_conc_superior_ppa + 1
             Else
                w_conc_superior_sisplam = w_conc_superior_sisplam + 1
             End If
          Else
             If Nvl(RS("cd_subacao"),"") > "" Then
                w_conc_abaixo_ppa = w_conc_abaixo_ppa + 1
             Else
                w_conc_abaixo_sisplam = w_conc_abaixo_sisplam + 1
             End If
          End If
          ShowHTML "   <td><div align=""justify""><font size=""1"">"&Nvl(RS("situacao_atual"),"---")&"</font></div></td>"
          ShowHTML "   <td><div align=""center""><font size=""1"">"&FormatDateTime(RS("fim_previsto"),2)&"</font></div></td>"
          ShowHTML "   <td><font size=""1"">"&RS("descricao_tramite")&"</font></td>"
          ShowHTML " </tr>"
          If Nvl(Trim(RS("descricao")),"") > "" Then
             If Nvl(RS("cd_subacao"),"") > "" Then
                w_preenchida_ppa = w_preenchida_ppa + 1
             Else
                w_preenchida_sisplam = w_preenchida_sisplam + 1
             End If
          End If
          w_programa_atual = RS("cd_programa")
          w_acao_atual = RS("cd_acao")
          w_unidade_atual = RS("cd_unidade")
          w_cont = w_cont + 1
          RS.MoveNext
       wend
    End If
    ShowHTML "    </table>"
    ShowHTML "  </div></td>"
    ShowHTML "</tr>"
    ShowHTML "   <tr><td colspan=""2""><br>"
    ShowHTML "  <tr><td colspan=""2""><div align=""center""><font size=""3""><b>QUADRO DE RESUMO</b></font></div></td></tr>"
    ShowHTML "   <tr><td colspan=""2""><div align=""center"">"
    ShowHTML "     <table width=100%  border=""1"" bordercolor=""#00000"">"
    ShowHTML "       <tr><td bgColor=""#f0f0f0"" colspan=""2""><div align=""center""><font size=""1""><b>Situação</b></font></div></td>"
    ShowHTML "           <td width=""12%"" bgColor=""#f0f0f0"" colspan=""2""><div align=""center""><font size=""1""><b>PPA</b></font></div></td>"        
    ShowHTML "           <td width=""13%"" bgColor=""#f0f0f0"" colspan=""2""><div align=""center""><font size=""1""><b>SISPLAM</b></font></div></td>"
    ShowHTML "           <td width=""13%"" bgColor=""#f0f0f0"" colspan=""2""><div align=""center""><font size=""1""><b>TOTAL</b></font></div></td>"
    ShowHTML "       </tr>"
    ShowHTML "       <tr><td bgColor=""#f0f0f0"" colspan=""2""><div align=""center""><font size=""1"">&nbsp;</font></div></td>"
    ShowHTML "           <td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>QTD</b></font></div></td>"        
    ShowHTML "           <td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>%</b></font></div></td>"
    ShowHTML "           <td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>QTD</b></font></div></td>"        
    ShowHTML "           <td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>%</b></font></div></td>"
    ShowHTML "           <td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>QTD</b></font></div></td>"        
    ShowHTML "           <td bgColor=""#f0f0f0""><div align=""center""><font size=""1""><b>%</b></font></div></td>"    
    ShowHTML "       </tr>"  
    'Quantitativo executado
    ShowHTML "       <tr><td bgColor=""#f0f0f0"" rowspan=""3"" valign=""top""><font size=""1""><b>Quantitativo Executado</b></font></td>"
    ShowHTML "           <td align=""right""><font size=""1"">Abaixo de 100%</font></td>"    
    ShowHTML "         <td><div align=""right""><font size=""1"">" & w_conc_abaixo_ppa & "&nbsp;</font></div></td>"
    If cDbl(w_total_ppa) > 0 Then
       ShowHTML "         <td><div align=""right""><font size=""1"">" & round(((w_conc_abaixo_ppa/w_total_ppa)*100),0) & "%&nbsp;</font></td>"   
    Else
       ShowHTML "         <td><div align=""right""><font size=""1"">0%&nbsp;</font></td>"
    End If
    ShowHTML "         <td><div align=""right""><font size=""1"">" & w_conc_abaixo_sisplam & "&nbsp;</font></div></td>"
    If cDbl(w_total_sisplam) > 0 Then
       ShowHTML "         <td><div align=""right""><font size=""1"">" & round(((w_conc_abaixo_sisplam/w_total_sisplam)*100),0) & "%&nbsp;</font></td>"   
    Else
       ShowHTML "         <td><div align=""right""><font size=""1"">0%&nbsp;</font></td>"
    End If    
    ShowHTML"         <td><div align=""right""><font size=""1"">" & (w_conc_abaixo_sisplam + w_conc_abaixo_ppa) & "&nbsp;</font></div></td>"                   
    If cDbl(w_cont) > 0 Then
       ShowHTML "         <td><div align=""right""><font size=""1"">" & round((((w_conc_abaixo_sisplam + w_conc_abaixo_ppa) /w_cont)*100),0) & "%&nbsp;</font></td>"   
    Else
       ShowHTML "         <td><div align=""right""><font size=""1"">0%&nbsp;</font></td>"
    End If
    ShowHTML "       <tr><td align=""right""><font size=""1"">Acima de 100%</font></td>"    
    ShowHTML "         <td><div align=""right""><font size=""1"">" & w_conc_superior_ppa & "&nbsp;</font></div></td>"
    If cDbl(w_total_ppa) > 0 Then
       ShowHTML "         <td><div align=""right""><font size=""1"">" & round(((w_conc_superior_ppa/w_total_ppa)*100),0) & "%&nbsp;</font></td>"   
    Else
       ShowHTML "         <td><div align=""right""><font size=""1"">0%&nbsp;</font></td>"
    End If
    ShowHTML "         <td><div align=""right""><font size=""1"">" & w_conc_superior_sisplam & "&nbsp;</font></div></td>"
    If cDbl(w_total_sisplam) > 0 Then
       ShowHTML "         <td><div align=""right""><font size=""1"">" & round(((w_conc_superior_sisplam/w_total_sisplam)*100),0) & "%&nbsp;</font></td>"   
    Else
       ShowHTML "         <td><div align=""right""><font size=""1"">0%&nbsp;</font></td>"
    End If
    ShowHTML"         <td><div align=""right""><font size=""1"">" & (w_conc_superior_sisplam + w_conc_superior_ppa) & "&nbsp;</font></div></td>"                   
    If cDbl(w_cont) > 0 Then
       ShowHTML "         <td><div align=""right""><font size=""1"">" & round((((w_conc_superior_sisplam + w_conc_superior_ppa)/w_cont)*100),0) & "%&nbsp;</font></td>"   
    Else
       ShowHTML "         <td><div align=""right""><font size=""1"">0%&nbsp;</font></td>"
    End If    
    ShowHTML "       <tr><td align=""right""><font size=""1"">Em 100%</font></td>"    
    ShowHTML "         <td><div align=""right""><font size=""1"">" & w_conc_igual_ppa & "&nbsp;</font></div></td>"
    If cDbl(w_total_ppa) > 0 Then
       ShowHTML "         <td><div align=""right""><font size=""1"">" & round(((w_conc_igual_ppa/w_total_ppa)*100),0) & "%&nbsp;</font></td>"   
    Else
       ShowHTML "         <td><div align=""right""><font size=""1"">0%&nbsp;</font></td>"
    End If
    ShowHTML "         <td><div align=""right""><font size=""1"">" & w_conc_igual_sisplam & "&nbsp;</font></div></td>"
    If cDbl(w_total_sisplam) > 0 Then
       ShowHTML "         <td><div align=""right""><font size=""1"">" & round(((w_conc_igual_sisplam/w_total_sisplam)*100),0) & "%&nbsp;</font></td>"   
    Else
       ShowHTML "         <td><div align=""right""><font size=""1"">0%&nbsp;</font></td>"
    End If   
    ShowHTML"         <td><div align=""right""><font size=""1"">" & (w_conc_igual_sisplam + w_conc_igual_ppa) & "&nbsp;</font></div></td>"               
    If cDbl(w_cont) > 0 Then
       ShowHTML "         <td><div align=""right""><font size=""1"">" & round((((w_conc_igual_sisplam + w_conc_igual_ppa)/w_cont)*100),0) & "%&nbsp;</font></td>"   
    Else
       ShowHTML "         <td><div align=""right""><font size=""1"">0%&nbsp;</font></td>"
    End If    
    'Em Andamento  
    ShowHTML "       <tr><td bgColor=""#f0f0f0"" rowspan=""2"" valign=""top""><font size=""1""><b>Em Andamento</b></font></td>"
    ShowHTML "           <td align=""right""><font size=""1"">Não será cumprida</font></td>"    
    ShowHTML "         <td><div align=""right""><font size=""1"">" & (w_total_ppa - w_exequivel_ppa) & "&nbsp;</font></div></td>"
    If cDbl(w_total_ppa) > 0 Then
       ShowHTML "         <td><div align=""right""><font size=""1"">" & round((((w_total_ppa - w_exequivel_ppa)/w_total_ppa)*100),0) & "%&nbsp;</font></td>"   
    Else
       ShowHTML "         <td><div align=""right""><font size=""1"">0%&nbsp;</font></td>"
    End If
    ShowHTML "         <td><div align=""right""><font size=""1"">" & (w_total_sisplam - w_exequivel_sisplam) & "&nbsp;</font></div></td>"
    If cDbl(w_total_sisplam) > 0 Then
       ShowHTML "         <td><div align=""right""><font size=""1"">" & round((((w_total_sisplam - w_exequivel_sisplam)/w_total_sisplam)*100),0) & "%&nbsp;</font></td>"   
    Else
       ShowHTML "         <td><div align=""right""><font size=""1"">0%&nbsp;</font></td>"
    End If    
    ShowHTML"         <td><div align=""right""><font size=""1"">" & (w_total_sisplam - w_exequivel_sisplam) + (w_total_ppa - w_exequivel_ppa) & "&nbsp;</font></div></td>"          
    If cDbl(w_cont) > 0 Then
       ShowHTML "         <td><div align=""right""><font size=""1"">" & round(((((w_total_sisplam - w_exequivel_sisplam) + (w_total_ppa - w_exequivel_ppa))/w_cont)*100),0) & "%&nbsp;</font></td>"   
    Else
       ShowHTML "         <td><div align=""right""><font size=""1"">0%&nbsp;</font></td>"
    End If
    ShowHTML "       <tr><td align=""right""><font size=""1"">Será cumprida</font></td>"    
    ShowHTML "         <td><div align=""right""><font size=""1"">" & w_exequivel_ppa & "&nbsp;</font></div></td>"
    If cDbl(w_total_ppa) > 0 Then
       ShowHTML "         <td><div align=""right""><font size=""1"">" & round(((w_exequivel_ppa/w_total_ppa)*100),0) & "%&nbsp;</font></td>"   
    Else
       ShowHTML "         <td><div align=""right""><font size=""1"">0%&nbsp;</font></td>"
    End If
    ShowHTML "         <td><div align=""right""><font size=""1"">" & w_exequivel_sisplam & "&nbsp;</font></div></td>"
    If cDbl(w_total_sisplam) > 0 Then
       ShowHTML "         <td><div align=""right""><font size=""1"">" & round(((w_exequivel_sisplam/w_total_sisplam)*100),0) & "%&nbsp;</font></td>"   
    Else
       ShowHTML "         <td><div align=""right""><font size=""1"">0%&nbsp;</font></td>"
    End If  
    ShowHTML"         <td><div align=""right""><font size=""1"">" & (w_exequivel_ppa + w_exequivel_sisplam) & "&nbsp;</font></div></td>"          
    If cDbl(w_cont) > 0 Then
       ShowHTML "         <td><div align=""right""><font size=""1"">" & round((((w_exequivel_sisplam + w_exequivel_ppa)/w_cont)*100),0) & "%&nbsp;</font></td>"   
    Else
       ShowHTML "         <td><div align=""right""><font size=""1"">0%&nbsp;</font></td>"
    End If    
    'Preenchimento
    ShowHTML "       <tr><td bgColor=""#f0f0f0"" rowspan=""2"" valign=""top""><font size=""1""><b>Preenchimento</b></font></td>"
    ShowHTML "           <td align=""right""><font size=""1"">Preenchida</font></td>"    
    ShowHTML "         <td><div align=""right""><font size=""1"">" & w_preenchida_ppa & "&nbsp;</font></div></td>"
    If cDbl(w_total_ppa) > 0 Then
       ShowHTML "         <td><div align=""right""><font size=""1"">" & round(((w_preenchida_ppa/w_total_ppa)*100),0) & "%&nbsp;</font></td>"   
    Else
       ShowHTML "         <td><div align=""right""><font size=""1"">0%&nbsp;</font></td>"
    End If
    ShowHTML"         <td><div align=""right""><font size=""1"">" & w_preenchida_sisplam & "&nbsp;</font></div></td>"
    If cDbl(w_total_sisplam) > 0 Then
       ShowHTML "         <td><div align=""right""><font size=""1"">" & round(((w_preenchida_sisplam/w_total_sisplam)*100),0) & "%&nbsp;</font></td>"   
    Else
       ShowHTML "         <td><div align=""right""><font size=""1"">0%&nbsp;</font></td>"
    End If   
    ShowHTML"         <td><div align=""right""><font size=""1"">" & (w_preenchida_ppa + w_preenchida_sisplam) & "&nbsp;</font></div></td>"         
    If cDbl(w_cont) > 0 Then
       ShowHTML "         <td><div align=""right""><font size=""1"">" & round((((w_preenchida_sisplam + w_preenchida_ppa)/w_cont)*100),0) & "%&nbsp;</font></td>"   
    Else
       ShowHTML "         <td><div align=""right""><font size=""1"">0%&nbsp;</font></td>"
    End If
    ShowHTML "       <tr><td align=""right""><font size=""1"">Não preenchida</font></td>"    
    ShowHTML "         <td><div align=""right""><font size=""1"">" & (w_total_ppa - w_preenchida_ppa) & "&nbsp;</font></div></td>"
    If cDbl(w_total_ppa) > 0 Then
       ShowHTML "         <td><div align=""right""><font size=""1"">" & round((((w_total_ppa - w_preenchida_ppa)/w_total_ppa)*100),0) & "%&nbsp;</font></td>"   
    Else
       ShowHTML "         <td><div align=""right""><font size=""1"">0%&nbsp;</font></td>"
    End If
    ShowHTML"         <td><div align=""right""><font size=""1"">" & (w_total_sisplam - w_preenchida_sisplam) & "&nbsp;</font></div></td>"
    If cDbl(w_total_sisplam) > 0 Then
       ShowHTML "         <td><div align=""right""><font size=""1"">" & round((((w_total_sisplam - w_preenchida_sisplam)/w_total_sisplam)*100),0) & "%&nbsp;</font></td>"   
    Else
       ShowHTML "         <td><div align=""right""><font size=""1"">0%&nbsp;</font></td>"
    End If    
    ShowHTML"         <td><div align=""right""><font size=""1"">" & ((w_total_ppa - w_preenchida_ppa) + (w_total_sisplam - w_preenchida_sisplam)) & "&nbsp;</font></div></td>"    
    If cDbl(w_cont) > 0 Then
       ShowHTML "         <td><div align=""right""><font size=""1"">" & round(((((w_total_sisplam - w_preenchida_sisplam) + (w_total_ppa - w_preenchida_ppa)) /w_cont)*100),0) & "%&nbsp;</font></td>"   
    Else
       ShowHTML "         <td><div align=""right""><font size=""1"">0%&nbsp;</font></td>"
    End If                
    'Total
    ShowHTML "       <tr><td bgColor=""#f0f0f0"" colspan=""2""><div align=""right""><font size=""1""><b>Total</b></font></div></td>"
    ShowHTML "           <td bgColor=""#f0f0f0""><div align=""right""><font size=""1""><b>" & w_total_ppa & "&nbsp;</b></font></div></td>"
    If cDbl(Nvl(w_cont,0)) > 0 Then
       ShowHTML "           <td bgColor=""#f0f0f0""><div align=""right""><font size=""1""><b>" & round((w_total_ppa/w_cont)*100,0) & "%&nbsp;</b></font></div></td>"
    Else
       ShowHTML "           <td bgColor=""#f0f0f0""><div align=""right""><font size=""1""><b>0%&nbsp;</b></font></div></td>"
    End If
    ShowHTML "           <td bgColor=""#f0f0f0""><div align=""right""><font size=""1""><b>" & w_total_sisplam & "&nbsp;</b></font></div></td>"
    If cDbl(Nvl(w_cont,0)) > 0 Then
       ShowHTML "           <td bgColor=""#f0f0f0""><div align=""right""><font size=""1""><b>" & round((w_total_sisplam/w_cont)*100,0) & "%&nbsp;</b></font></div></td>"
    Else
       ShowHTML "           <td bgColor=""#f0f0f0""><div align=""right""><font size=""1""><b>0%&nbsp;</b></font></div></td>"
    End If    
    ShowHTML "           <td bgColor=""#f0f0f0""><div align=""right""><font size=""1""><b>" & w_cont & "&nbsp;</b></font></div></td>"        
    If cDbl(Nvl(w_cont,0)) > 0 Then
       ShowHTML "           <td bgColor=""#f0f0f0""><div align=""right""><font size=""1""><b>" & round(((w_total_ppa+w_total_sisplam)/w_cont)*100,2) & "%&nbsp;</b></font></div></td>"
    Else
       ShowHTML "           <td bgColor=""#f0f0f0""><div align=""right""><font size=""1""><b>0%&nbsp;</b></font></div></td>"
    End If    

    ShowHTML "       </tr>"
    ShowHTML "    </table></div></td></tr>"
    DesconectaBD
  ElseIf O = "P" Then
    AbreForm "Form", w_dir & w_pagina & par, "POST", "return(Validacao(this));", "Acao",P1,P2,P3,P4,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"

    ShowHTML "<tr><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
    SelecaoUnidade "Á<U>r</U>ea planejamento:", "R", null, p_sq_unidade, null, "p_sq_unidade", null, "onchange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='p_sq_unidade'; document.Form.target=''; document.Form.O.value='P'; document.Form.submit();"""
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
    SelecaoProgramaPPA "<u>P</u>rograma PPA:", "P", null, w_cliente, w_ano, p_cd_programa, "p_cd_programa", "RELATORIO", "onchange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='p_cd_programa'; document.Form.target=''; document.Form.O.value='P'; document.Form.submit();""", w_menu
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
    SelecaoAcaoPPA "<u>A</u>ção PPA:", "A", null, w_cliente, w_ano, p_cd_programa, null, null, null, "p_cd_acao", null, null, null, w_menu
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
    ShowHTML "        <td><font size=""1""><b><u>T</u>ipo de relatório:</b><br><SELECT ACCESSKEY=""T"" CLASS=""STS"" NAME=""w_tipo_rel"" " & w_Disabled & ">"
    If nvl(w_tipo_rel,"-") = "Word"  Then
       ShowHTML "          <option value="""">Consulta na Tela"
       ShowHTML "          <option value=""Word"" SELECTED>Documento Word"
    Else
       ShowHTML "          <option value="""" SELECTED>Consulta na Tela"
       ShowHTML "          <option value=""Word"">Documento Word"
    End If
    ShowHTML "          </select></td><tr>"
    ShowHTML "    </table></td></tr>"
    ShowHTML "<tr><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"    
    ShowHTML "      <tr bgcolor=""" & conTrAlternateBgColor & """><td colspan=""2""><font size=""1""><b>OPÇÕES DE CONSULTA</b></font></td></tr>"    
    ShowHTML "      <tr><td width=""25%"" bgcolor=""" & conTrBgColor & """><font size=""1""><b><u>S</u>ituação Atual da Meta:</b>"
    ShowHTML "          <td bgcolor=""" & conTrBgColor & """><font size=""1""><SELECT ACCESSKEY=""S"" CLASS=""STS"" NAME=""p_preenchida"">"
    If nvl(p_preenchida,"-") <> "S" or nvl(p_preenchida,"-") <> "N" Then 
       ShowHTML "          <option value="""" SELECTED>Todas"
       ShowHTML "          <option value=""S"">Preenchida"
       ShowHTML "          <option value=""N"">Não preenchida"
    ElseIf nvl(p_preenchida,"-") = "S"  Then
       ShowHTML "          <option value="""">Todas"
       ShowHTML "          <option value=""S"" SELECTED>Preenchida"
       ShowHTML "          <option value=""N"">Não preenchida"
    ElseIf nvl(p_preenchida,"-") = "N" Then
       ShowHTML "          <option value="""">Todas"
       ShowHTML "          <option value=""S"">Preenchida"
       ShowHTML "          <option value=""N"" SELECTED>Não preenchida"
    End If
    ShowHTML "          </select></td></tr>"    
    ShowHTML "      <tr><td bgcolor=""" & conTrBgColor & """><font size=""1""><b>T<u>i</u>po de Meta:</b>" 
    ShowHTML "          <td bgcolor=""" & conTrBgColor & """><font size=""1""><SELECT ACCESSKEY=""I"" CLASS=""STS"" NAME=""p_meta_ppa"">"
    If nvl(p_meta_ppa,"-") <> "S" or nvl(p_meta_ppa,"-") <> "N" Then 
       ShowHTML "          <option value="""" SELECTED>Todas"
       ShowHTML "          <option value=""S"">Meta PPA"
       ShowHTML "          <option value=""N"">Meta não PPA"
    ElseIf nvl(p_meta_ppa,"-") = "S"  Then
       ShowHTML "          <option value="""">Todas"
       ShowHTML "          <option value=""S"" SELECTED>Meta PPA"
       ShowHTML "          <option value=""N"">Meta não PPA"
    ElseIf nvl(p_meta_ppa,"-") = "N" Then
       ShowHTML "          <option value="""">Todas"
       ShowHTML "          <option value=""S"">Meta PPA"
       ShowHTML "          <option value=""N"" SELECTED>Meta não PPA"
    End If
    ShowHTML "          </select></td></tr>"
    ShowHTML "      <tr><td bgcolor=""" & conTrBgColor & """><font size=""1""><b><u>E</u>xecução da Meta:</b>"
    ShowHTML "          <td bgcolor=""" & conTrBgColor & """><font size=""1""><SELECT ACCESSKEY=""E"" CLASS=""STS"" NAME=""p_exequivel"">"
    If nvl(p_exequivel,"-") <> "S" or nvl(p_exequivel,"-") <> "N" Then 
       ShowHTML "          <option value="""" SELECTED>Todas"
       ShowHTML "          <option value=""S"">Metas que serão cumpridas"
       ShowHTML "          <option value=""N"">Metas que não serão cumpridas"
    ElseIf nvl(p_exequivel,"-") = "S"  Then
       ShowHTML "          <option value="""">Todas"
       ShowHTML "          <option value=""S"" SELECTED>Metas que serão cumpridas"
       ShowHTML "          <option value=""N"">Metas que não serão cumpridas"
    ElseIf nvl(p_exequivel,"-") = "N" Then
       ShowHTML "          <option value="""">Todas"
       ShowHTML "          <option value=""S"">Metas que serão cumpridas"
       ShowHTML "          <option value=""N"" SELECTED>Metas que não serão cumpridas"
    End If
    ShowHTML "          </select></td></tr>"
    ShowHTML "    </table></td></tr>"
    ShowHTML "    <table width=""100%"" border=""0"">"            
    ShowHTML "      <tr><td align=""center""><hr>"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Exibir"">"
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
  ShowHTML "</DIV>"
  ShowHTML "</BODY>"
  ShowHTML "</HTML>"  
End Sub

REM =========================================================================
REM Relatório detalhado das tarefas
REM -------------------------------------------------------------------------
Sub Rel_Det_Tarefa
  Dim p_sq_unidade, p_cd_acao, p_cd_programa, w_tipo_rel
  Dim w_logo
  Dim w_identificacao, w_responsavel, w_anexo, w_ocorrencia

  
  
  p_sq_unidade               = ucase(Trim(Request("p_sq_unidade")))
  p_cd_programa              = ucase(Trim(Request("p_cd_programa")))
  p_cd_acao                  = ucase(Trim(Request("p_cd_acao")))
  w_tipo_rel                 = uCase(trim(Request("w_tipo_rel")))
  w_identificacao            = uCase(trim(Request("w_identificacao")))
  w_responsavel              = uCase(trim(Request("w_responsavel")))
  w_anexo                    = uCase(trim(Request("w_anexo")))
  w_ocorrencia               = uCase(trim(Request("w_ocorrencia")))
  
  w_cont                  = 0 
  
  If O = "L" Then
     ' Recupera o logo do cliente a ser usado nas listagens
     DB_GetCustomerData RS, w_cliente
     If RS("logo") > "" Then
         w_logo = "\img\logo" & Mid(RS("logo"),Instr(RS("logo"),"."),30)
     End If
     DesconectaBD
  End If

  DB_GetLinkData RS1, RetornaCliente(), "ISTCAD"
  DB_GetSolicList_IS RS, RS1("sq_menu"), w_usuario, "ISTCAD", 4, _
     null, null, null, null, null, null, _
     p_sq_unidade, null, null, null, _
     null, null, null, null, null, null, null, _
     null, null, null, null, null, null, p_cd_programa, Mid(p_cd_acao,5,4), null, null, w_ano
  RS.sort = "ordem, fim, prioridade"
  If w_tipo_rel = "WORD" Then
     HeaderWord null
     w_pag   = 1
     w_linha = 8
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     ShowHTML "<div align=""center"">"
     ShowHTML "<table width=""95%"" border=""0"" cellspacing=""3"">"
     ShowHTML "<tr><td colspan=""2"">"     
     ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "WORD") & """></TD><TD ALIGN=""RIGHT"" NOWRAP><B><FONT SIZE=4 COLOR=""#000000"">"
     ShowHTML "RELATÓRIO DETALHADO DE TAREFAS <br> Exercício " & w_ano
     ShowHTML "</FONT></TD></TR></TABLE>"
  Else
     Cabecalho
     ShowHTML "<HEAD>"
     ShowHTML "<TITLE>Relatório de Tarefas - Exercício " & w_ano & "</TITLE>"
     If InStr("P",O) > 0 Then
        ScriptOpen "JavaScript"
        ValidateOpen "Validacao"
        Validate "p_sq_unidade", "Responsável", "HIDDEN", "", "2", "60", "1", "1"
        Validate "p_cd_programa", "Programa", "HIDDEN", "", "1", "18", "1", "1"
        Validate "p_cd_acao", "Ação", "HIDDEN", "", "1", "18", "1", "1"
        ValidateClose
        ShowHTML "  function MarcaTodosBloco() {"
        ShowHTML "    if (document.Form.w_marca_bloco.checked==true) {"
        ShowHTML "         document.Form.w_identificacao.checked=true;"
        ShowHTML "         document.Form.w_responsavel.checked=true;"
        ShowHTML "         document.Form.w_anexo.checked=true;"
        ShowHTML "         document.Form.w_ocorrencia.checked=true;"
        ShowHTML "    } else { "
        ShowHTML "         document.Form.w_identificacao.checked=false;"
        ShowHTML "         document.Form.w_responsavel.checked=false;"
        ShowHTML "         document.Form.w_anexo.checked=false;"
        ShowHTML "         document.Form.w_ocorrencia.checked=false;"
        ShowHTML "    } "
        ShowHTML "  }"        
        ScriptClose
     End If
     ShowHTML "</HEAD>"
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     If O = "L" Then
        BodyOpenClean "onLoad='document.focus()';"
        ShowHTML "<BASE HREF=""" & conRootSIW & """>"
        ShowHTML "<div align=""center"">"
        ShowHTML "<table width=""95%"" border=""0"" cellspacing=""3"">"
        ShowHTML "<tr><td colspan=""2"">"             
        ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "EMBED") & """></TD><TD ALIGN=""RIGHT"" NOWRAP><B><FONT SIZE=4 COLOR=""#000000"">"
        ShowHTML "RELATÓRIO DETALHADO DE TAREFAS <br> Exercício " & w_ano
        ShowHTML "</FONT></B></TD></TR></TABLE>"
     Else
        BodyOpen "onLoad='document.Form.p_cd_programa.focus()';"
        ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
        ShowHTML "<div align=center><center>"
        ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
        ShowHTML "<HR>"
     End If
  End If
  If O = "L" Then
    ShowHTML "<tr><td colspan=""2""><div align=""center""><hr NOSHADE color=#000000 size=2></div></td></tr>"
    ShowHTML "<tr><td colspan=""2""><div align=""center"">"
    ShowHTML "<table border=""0"" width=""100%"">"
    If p_sq_unidade > "" Then
       DB_GetUorgData RS1, p_sq_unidade
       ShowHTML "<tr><td width=""15%""><font size=""1""><b>Área de planejamento:</b></font></td><td><font size=""1"">" & RS1("nome") & " - " & RS1("sigla")& "</font></td>"
       RS1.Close
    Else
       ShowHTML "<tr><td width=""15%""><font size=""1""><b>Área de planejamento:</b></font></td><td><font size=""1"">Todas</font></td>"
    End If
    If p_cd_programa > "" Then
       DB_GetProgramaPPA_IS RS1, p_cd_programa, w_cliente, w_ano, null, null
       ShowHTML "    <td width=""7%""><font size=""1""><b>Programa:</b></font></td><td nowrap><font size=""1"">" & p_cd_programa & " - " & RS1("ds_programa") & "</font></td></tr>"
       RS1.Close
    Else
       ShowHTML "    <td width=""7%""><font size=""1""><b>Programa:</b></font></td><td><font size=""1"">Todos</font></td></tr>"
    End If
    If p_cd_acao > "" Then
       DB_GetAcaoPPA_IS RS1, w_cliente, w_ano, p_cd_programa, Mid(p_cd_acao,5,4), null, null, null, null, null
       ShowHTML "<tr valign=""top""><td><font size=""1""><b>Ação:</b></font></td><td><font size=""1"">" & Mid(p_cd_acao,5,4) & " - " & RS1("descricao_acao") & "</font></td>"
       RS1.Close
    Else
       ShowHTML "<tr valign=""top""><td><font size=""1""><b>Ação:</b></font></td><td><font size=""1"">Todas</font></td>"
    End If
    
    ShowHTML "</ul></td></tr></table>"
    ShowHTML "</div></td></tr>"
    ShowHTML "<tr><td colspan=""2""><div align=""center""><hr NOSHADE color=#000000 size=2></div></td></tr>"    
    ShowHTML "<tr><td colspan=""2""><div align=""center""><font size=""3""><b>RELATÓRIO DE TAREFAS</b></font></div></td></tr>"
    If RS.EOF Then  
       w_linha = w_linha + 1
       ShowHTML "    <tr><td colspan=""13""><div align=""center""><font size=""3"" color=""red""><b><br>Nenhuma tarefa encontrada</b></div></td></tr>"
    Else
       While Not RS.EOF
          If w_linha > 19 and w_tipo_rel = "WORD" Then
             ShowHTML "    </table>"
             ShowHTML "  </td>"
             ShowHTML "</tr>"
             ShowHTML "</table>"
             ShowHTML "</center></div>"
             ShowHTML "    <br style=""page-break-after:always"">"
             w_linha = 6
             w_pag   = w_pag + 1
             ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "WORD") & """><TD ALIGN=""RIGHT""><B><FONT SIZE=4 COLOR=""#000000"">"
             ShowHTML "Ações do PPA"
             ShowHTML "</FONT><TR><TD WIDTH=""50%"" ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
             ShowHTML "<TR><TD COLSPAN=""2"" ALIGN=""RIGHT""><B><FONT SIZE=2 COLOR=""#000000"">Página: " & w_pag & "</B></TD></TR>"
             ShowHTML "</TD></TR>"
             ShowHTML "</FONT></B></TD></TR></TABLE>"
             ShowHTML "<div align=center><center>"
             ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
             ShowHTML "<tr><td colspan=""2""><div align=""center"">"
             ShowHTML "<table border=""0"" width=""100%"">"
             If p_sq_unidade > "" Then
                ShowHTML "<tr><td width=""20%""><font size=""1""><b>Área de planejamento:</b></font></td><td><font size=""1"">" & RS("sg_segor") & "</font></td>"
             Else
                ShowHTML "<tr><td width=""20%""><font size=""1""><b>Área de planejamento:</b></font></td><td><font size=""1"">Todas</font></td>"
             End If
             If p_cd_programa > "" Then
                ShowHTML "    <td width=""20%""><font size=""1""><b>Programa:</b></font></td><td><font size=""1"">" & RS("descricao_programa") & "</font></td></tr>"
             Else
                ShowHTML "    <td width=""20%""><font size=""1""><b>Programa:</b></font></td><td><font size=""1"">Todos</font></td></tr>"
             End If
             If p_cd_acao > "" Then 
                ShowHTML "<tr><td><font size=""1""><b>Ação:</b></font></td><td><font size=""1"">" & RS("descricao_acao") & "</font></td>"
             Else
                ShowHTML "<tr><td><font size=""1""><b>Ação:</b></font></td><td><font size=""1"">Todas</font></td>"
             End If
             ShowHTML "</ul></td></tr></table>"
             ShowHTML "</div></td></tr>"
          End If
          If w_tipo_rel = "WORD" Then 
             ShowHTML VisualTarefa(RS("sq_siw_solicitacao"), "", w_usuario, 1, w_identificacao, w_identificacao, w_responsavel, w_anexo, w_ocorrencia, "nao")
             ShowHTML "<tr><td colspan=""2""><div align=""center""><BR></div></td></tr>"
          Else
             ShowHTML VisualTarefa(RS("sq_siw_solicitacao"), "", w_usuario, 0, w_identificacao, w_identificacao, w_responsavel, w_anexo, w_ocorrencia, "nao")
             ShowHTML "<tr><td colspan=""2""><div align=""center""><BR></div></td></tr>"
          End If
          RS.MoveNext
       wend
    End If
    
    ShowHTML "   <tr><td colspan=""2""><font size=""2""><b>DADOS DA CONSULTA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>"
    ShowHTML "   <tr><td><font size=""1""><b>Consulta Realizada por:</b></font></td>"
    ShowHTML "       <td><font size=""1"">" &  Session("NOME_RESUMIDO") & "</font></td></tr>"
    ShowHTML "   <tr><td><font size=""1""><b>Data da Consulta:</b></font></td>"
    ShowHTML "       <td><font size=""1"">" &  FormataDataEdicao(FormatDateTime(now(),2)) & ", " & FormatDateTime(now(),4) & "</font></td></tr>"
    
    ShowHTML "    </table>"
    ShowHTML "  </div></td>"
    ShowHTML "</tr>"
    DesconectaBD
  ElseIf O = "P" Then
    AbreForm "Form", w_dir & w_pagina & par, "POST", "return(Validacao(this));", "Acao",P1,P2,P3,P4,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"

    ShowHTML "<tr><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
    SelecaoUnidade "Á<U>r</U>ea planejamento:", "R", null, p_sq_unidade, null, "p_sq_unidade", null, "onchange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='p_sq_unidade'; document.Form.target=''; document.Form.O.value='P'; document.Form.submit();"""
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
    SelecaoProgramaPPA "<u>P</u>rograma PPA:", "P", null, w_cliente, w_ano, p_cd_programa, "p_cd_programa", "RELATORIO", "onchange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='p_cd_programa'; document.Form.target=''; document.Form.O.value='P'; document.Form.submit();""", w_menu
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
    SelecaoAcaoPPA "<u>A</u>ção PPA:", "A", null, w_cliente, w_ano, p_cd_programa, null, null, null, "p_cd_acao", null, null, null, w_menu
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
    ShowHTML "        <td><font size=""1""><b><u>T</u>ipo de relatório:</b><br><SELECT ACCESSKEY=""T"" CLASS=""STS"" NAME=""w_tipo_rel"" " & w_Disabled & ">"
    If nvl(w_tipo_rel,"-") = "Word"  Then
       ShowHTML "          <option value="""">Consulta na Tela"
       ShowHTML "          <option value=""Word"" SELECTED>Documento Word"
    Else
       ShowHTML "          <option value="""" SELECTED>Consulta na Tela"
       ShowHTML "          <option value=""Word"">Documento Word"
    End If
    ShowHTML "          </select></td><tr>"
    ShowHTML "    </table></td></tr>"
    ShowHTML "<tr><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"    
    ShowHTML "      <tr bgcolor=""" & conTrAlternateBgColor & """><td colspan=""2""><font size=""1""><b>ESCOLHA OS BLOCOS A SEREM VISUALIZADOS NO RELATÓRIO</b></font></td></tr>"    
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""w_identificacao"" value=""sim""> Identficação</td>" 
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""w_anexo"" value=""sim""> Anexos</td>"
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""w_responsavel"" value=""sim""> Responsáveis</td>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""w_ocorrencia"" value=""sim""> Ocorrências/Anotações</td>"
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
    ShowHTML "          <td colspan=""2""><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""w_marca_bloco"" value="""" onClick=""javascript:MarcaTodosBloco();"" TITLE=""Marca todos os itens da relação""> Todos</td>"    
    ShowHTML "    </table></td></tr>"    
    ShowHTML "    <table width=""100%"" border=""0"">"            
    ShowHTML "      <tr><td align=""center""><hr>"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Exibir"">"
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
  ShowHTML "</DIV>"
  ShowHTML "</BODY>"
  ShowHTML "</HTML>"  
End Sub

REM =========================================================================
REM Relatório detalhado das ações cadastradas
REM -------------------------------------------------------------------------
Sub Rel_Det_Acao
  Dim p_sq_unidade, p_cd_acao, p_cd_programa, w_tipo_rel
  Dim w_logo
  Dim w_identificacao, w_responsavel, w_qualitativa, w_orcamentaria, w_meta, w_restricao
  Dim w_tarefa, w_interessado, w_anexo, w_ocorrencia
  
  p_sq_unidade               = ucase(Trim(Request("p_sq_unidade")))
  p_cd_programa              = ucase(Trim(Request("p_cd_programa")))
  p_cd_acao                  = ucase(Trim(Request("p_cd_acao")))
  w_tipo_rel                 = uCase(trim(Request("w_tipo_rel")))
  w_identificacao            = uCase(trim(Request("w_identificacao")))
  w_responsavel              = uCase(trim(Request("w_responsavel")))
  w_qualitativa              = uCase(trim(Request("w_qualitativa")))
  w_orcamentaria             = uCase(trim(Request("w_orcamentaria")))
  w_meta                     = uCase(trim(Request("w_meta")))
  w_restricao                = uCase(trim(Request("w_restricao")))
  w_tarefa                   = uCase(trim(Request("w_tarefa")))
  w_interessado              = uCase(trim(Request("w_interessado")))
  w_anexo                    = uCase(trim(Request("w_anexo")))
  w_ocorrencia               = uCase(trim(Request("w_ocorrencia")))
  
  w_cont                  = 0 
  
  If O = "L" Then
     ' Recupera o logo do cliente a ser usado nas listagens
     DB_GetCustomerData RS, w_cliente
     If RS("logo") > "" Then
         w_logo = "\img\logo" & Mid(RS("logo"),Instr(RS("logo"),"."),30)
     End If
     DesconectaBD
  End If

  DB_GetLinkData RS1, RetornaCliente(), "ISACAD"
  DB_GetSolicList_IS RS, RS1("sq_menu"), w_usuario, "ISACAD", 4, _
     null, null, null, null, null, null, _
     p_sq_unidade, null, null, null, _
     null, null, null, null, null, null, null, _
     null, null, null, null, null, null, p_cd_programa, Mid(p_cd_acao,5,4), null, null, w_ano
  RS.sort = "fim, prioridade"
  If w_tipo_rel = "WORD" Then
     HeaderWord null
     w_pag   = 1
     w_linha = 8
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     ShowHTML "<div align=""center"">"
     ShowHTML "<table width=""95%"" border=""0"" cellspacing=""3"">"
     ShowHTML "<tr><td colspan=""2"">"     
     ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "WORD") & """></TD><TD ALIGN=""RIGHT"" NOWRAP><B><FONT SIZE=4 COLOR=""#000000"">"
     ShowHTML "RELATÓRIO DETALHADO DE AÇÕES <br> Exercício " & w_ano
     ShowHTML "</FONT></TD></TR></TABLE>"
  Else
     Cabecalho
     ShowHTML "<HEAD>"
     ShowHTML "<TITLE>Relatório de Ações - Exercício " & w_ano & "</TITLE>"
     If InStr("P",O) > 0 Then
        ScriptOpen "JavaScript"
        ValidateOpen "Validacao"
        Validate "p_sq_unidade", "Responsável", "HIDDEN", "", "2", "60", "1", "1"
        Validate "p_cd_programa", "Programa", "HIDDEN", "", "1", "18", "1", "1"
        Validate "p_cd_acao", "Ação", "HIDDEN", "", "1", "18", "1", "1"
        ValidateClose
        ShowHTML "  function MarcaTodosBloco() {"
        ShowHTML "    if (document.Form.w_marca_bloco.checked==true) {"
        ShowHTML "         document.Form.w_identificacao.checked=true;"
        ShowHTML "         document.Form.w_responsavel.checked=true;"
        ShowHTML "         document.Form.w_qualitativa.checked=true;"
        ShowHTML "         document.Form.w_orcamentaria.checked=true;"
        ShowHTML "         document.Form.w_meta.checked=true;"
        ShowHTML "         document.Form.w_restricao.checked=true;"
        ShowHTML "         document.Form.w_tarefa.checked=true;"
        ShowHTML "         document.Form.w_interessado.checked=true;"
        ShowHTML "         document.Form.w_anexo.checked=true;"
        ShowHTML "         document.Form.w_ocorrencia.checked=true;"
        ShowHTML "    } else { "
        ShowHTML "         document.Form.w_identificacao.checked=false;"
        ShowHTML "         document.Form.w_responsavel.checked=false;"
        ShowHTML "         document.Form.w_qualitativa.checked=false;"
        ShowHTML "         document.Form.w_orcamentaria.checked=false;"
        ShowHTML "         document.Form.w_meta.checked=false;"
        ShowHTML "         document.Form.w_restricao.checked=false;"
        ShowHTML "         document.Form.w_tarefa.checked=false;"
        ShowHTML "         document.Form.w_interessado.checked=false;"
        ShowHTML "         document.Form.w_anexo.checked=false;"
        ShowHTML "         document.Form.w_ocorrencia.checked=false;"
        ShowHTML "    } "
        ShowHTML "  }"
        ScriptClose        
     End If
     ShowHTML "</HEAD>"
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     If O = "L" Then
        BodyOpenClean "onLoad='document.focus()';"
        ShowHTML "<BASE HREF=""" & conRootSIW & """>"
        ShowHTML "<div align=""center"">"
        ShowHTML "<table width=""95%"" border=""0"" cellspacing=""3"">"
        ShowHTML "<tr><td colspan=""2"">"             
        ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "EMBED") & """></TD><TD ALIGN=""RIGHT"" NOWRAP><B><FONT SIZE=4 COLOR=""#000000"">"
        ShowHTML "RELATÓRIO DETALHADO DE AÇÕES <br> Exercício " & w_ano
        ShowHTML "</FONT></B></TD></TR></TABLE>"
     Else
        BodyOpen "onLoad='document.Form.p_cd_programa.focus()';"
        ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
        ShowHTML "<div align=center><center>"
        ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
        ShowHTML "<HR>"
     End If
  End If
  If O = "L" Then
    ShowHTML "<tr><td colspan=""2""><div align=""center""><hr NOSHADE color=#000000 size=2></div></td></tr>"
    ShowHTML "<tr><td colspan=""2""><div align=""center"">"
    ShowHTML "<table border=""0"" width=""100%"">"
    If p_sq_unidade > "" Then
       DB_GetUorgData RS1, p_sq_unidade
       ShowHTML "<tr><td width=""15%""><font size=""1""><b>Área de planejamento:</b></font></td><td><font size=""1"">" & RS1("nome") & " - " & RS1("sigla")& "</font></td>"
       RS1.Close
    Else
       ShowHTML "<tr><td width=""15%""><font size=""1""><b>Área de planejamento:</b></font></td><td><font size=""1"">Todas</font></td>"
    End If
    If p_cd_programa > "" Then
       DB_GetProgramaPPA_IS RS1, p_cd_programa, w_cliente, w_ano, null, null
       ShowHTML "    <td width=""7%""><font size=""1""><b>Programa:</b></font></td><td nowrap><font size=""1"">" & p_cd_programa & " - " & RS1("ds_programa") & "</font></td></tr>"
       RS1.Close
    Else
       ShowHTML "    <td width=""7%""><font size=""1""><b>Programa:</b></font></td><td><font size=""1"">Todos</font></td></tr>"
    End If
    If p_cd_acao > "" Then
       DB_GetAcaoPPA_IS RS1, w_cliente, w_ano, p_cd_programa, Mid(p_cd_acao,5,4), null, null, null, null, null
       ShowHTML "<tr valign=""top""><td><font size=""1""><b>Ação:</b></font></td><td><font size=""1"">" & Mid(p_cd_acao,5,4) & " - " & RS1("descricao_acao") & "</font></td>"
       RS1.Close
    Else
       ShowHTML "<tr valign=""top""><td><font size=""1""><b>Ação:</b></font></td><td><font size=""1"">Todas</font></td>"
    End If
    
    ShowHTML "</ul></td></tr></table>"
    ShowHTML "</div></td></tr>"
    ShowHTML "<tr><td colspan=""2""><div align=""center""><hr NOSHADE color=#000000 size=2></div></td></tr>"    
    ShowHTML "<tr><td colspan=""2""><div align=""center""><font size=""3""><b>RELATÓRIO DE AÇÕES</b></font></div></td></tr>"
    w_linha = 8
    If RS.EOF Then  
       w_linha = w_linha + 1
       ShowHTML "    <tr><td colspan=""13""><div align=""center""><font size=""3"" color=""red""><b><br>Nenhuma ação encontrada</b></div></td></tr>"
    Else
       While Not RS.EOF
          If w_linha > 19 and w_tipo_rel = "WORD" Then
             ShowHTML "    </table>"
             ShowHTML "  </td>"
             ShowHTML "</tr>"
             ShowHTML "</table>"
             ShowHTML "</div>"
             ShowHTML "    <br style=""page-break-after:always"">"
             w_linha = 6
             w_pag   = w_pag + 1
             ShowHTML "<div align=""center"">"
             ShowHTML "<table width=""95%"" border=""0"" cellspacing=""3"">"
             ShowHTML "<tr><td colspan=""2"">"             
             ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "WORD") & """></TD><TD ALIGN=""RIGHT"" NOWRAP><B><FONT SIZE=4 COLOR=""#000000"">"
             ShowHTML "RELATÓRIO DETALHADO DE AÇÕES <br> Exercício " & w_ano
             ShowHTML "</FONT></B></TD></TR></TABLE>"
             ShowHTML "<tr><td colspan=""2""><div align=""center""><hr NOSHADE color=#000000 size=2></div></td></tr>"
             ShowHTML "<tr><td colspan=""2""><div align=""center"">"
             ShowHTML "<table border=""0"" width=""100%"">"
             If p_sq_unidade > "" Then
                ShowHTML "<tr><td width=""20%""><font size=""1""><b>Área de planejamento:</b></font></td><td><font size=""1"">" & RS("sg_segor") & "</font></td>"
             Else
                ShowHTML "<tr><td width=""20%""><font size=""1""><b>Área de planejamento:</b></font></td><td><font size=""1"">Todas</font></td>"
             End If
             If p_cd_programa > "" Then
                ShowHTML "    <td width=""20%""><font size=""1""><b>Programa:</b></font></td><td><font size=""1"">" & RS("descricao_programa") & "</font></td></tr>"
             Else
                ShowHTML "    <td width=""20%""><font size=""1""><b>Programa:</b></font></td><td><font size=""1"">Todos</font></td></tr>"
             End If
             If p_cd_acao > "" Then 
                ShowHTML "<tr><td><font size=""1""><b>Ação:</b></font></td><td><font size=""1"">" & RS("descricao_acao") & "</font></td>"
             Else
                ShowHTML "<tr><td><font size=""1""><b>Ação:</b></font></td><td><font size=""1"">Todas</font></td>"
             End If
             ShowHTML "</ul></td></tr></table>"
             ShowHTML "</div></td></tr>"
             ShowHTML "<tr><td colspan=""2""><div align=""center""><hr NOSHADE color=#000000 size=2></div></td></tr>"    
             ShowHTML "<tr><td colspan=""2""><div align=""center""><font size=""3""><b>RELATÓRIO DE AÇÕES</b></font></div></td></tr>"
          End If
          If w_tipo_rel = "WORD" Then 
             ShowHTML VisualAcao(RS("sq_siw_solicitacao"), "", w_usuario, 0, 1, w_identificacao, w_responsavel, w_qualitativa, w_orcamentaria, w_meta, w_restricao, w_tarefa, w_interessado, w_anexo, w_ocorrencia, "nao", w_identificacao)
          Else
             ShowHTML VisualAcao(RS("sq_siw_solicitacao"), "", w_usuario, 0, 1, w_identificacao, w_responsavel, w_qualitativa, w_orcamentaria, w_meta, w_restricao, w_tarefa, w_interessado, w_anexo, w_ocorrencia, "nao", w_identificacao)
          End If
          w_linha = w_linha + 30
          RS.MoveNext
       wend
    End If
    
    ShowHTML "   <tr><td colspan=""2""><br><font size=""2""><b>DADOS DA CONSULTA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>"
    ShowHTML "   <tr><td><font size=""1""><b>Consulta Realizada por:</b></font></td>"
    ShowHTML "       <td><font size=""1"">" &  Session("NOME_RESUMIDO") & "</font></td></tr>"
    ShowHTML "   <tr><td><font size=""1""><b>Data da Consulta:</b></font></td>"
    ShowHTML "       <td><font size=""1"">" &  FormataDataEdicao(FormatDateTime(now(),2)) & ", " & FormatDateTime(now(),4) & "</font></td></tr>"
    
    ShowHTML "    </table>"
    ShowHTML "  </div></td>"
    ShowHTML "</tr>"
    DesconectaBD
  ElseIf O = "P" Then
    AbreForm "Form", w_dir & w_pagina & par, "POST", "return(Validacao(this));", "Acao",P1,P2,P3,P4,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"

    ShowHTML "<tr><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
    SelecaoUnidade "Á<U>r</U>ea planejamento:", "R", null, p_sq_unidade, null, "p_sq_unidade", null, "onchange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='p_sq_unidade'; document.Form.target=''; document.Form.O.value='P'; document.Form.submit();"""
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
    SelecaoProgramaPPA "<u>P</u>rograma PPA:", "P", null, w_cliente, w_ano, p_cd_programa, "p_cd_programa", "RELATORIO", "onchange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='p_cd_programa'; document.Form.target=''; document.Form.O.value='P'; document.Form.submit();""", w_menu
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
    SelecaoAcaoPPA "<u>A</u>ção PPA:", "A", null, w_cliente, w_ano, p_cd_programa, null, null, null, "p_cd_acao", null, null, null, w_menu
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
    ShowHTML "        <td><font size=""1""><b><u>T</u>ipo de relatório:</b><br><SELECT ACCESSKEY=""T"" CLASS=""STS"" NAME=""w_tipo_rel"" " & w_Disabled & ">"
    If nvl(w_tipo_rel,"-") = "Word"  Then
       ShowHTML "          <option value="""">Consulta na Tela"
       ShowHTML "          <option value=""Word"" SELECTED>Documento Word"
    Else
       ShowHTML "          <option value="""" SELECTED>Consulta na Tela"
       ShowHTML "          <option value=""Word"">Documento Word"
    End If
    ShowHTML "          </select></td><tr>"
    ShowHTML "    </table></td></tr>"
    ShowHTML "<tr><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"    
    ShowHTML "      <tr bgcolor=""" & conTrAlternateBgColor & """><td colspan=""2""><font size=""1""><b>ESCOLHA OS BLOCOS A SEREM VISUALIZADOS NO RELATÓRIO</b></font></td></tr>"    
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""w_identificacao"" value=""sim""> Identficação</td>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""w_restricao"" value=""sim""> Restrições</td>"
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""w_responsavel"" value=""sim""> Responsáveis</td>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""w_tarefa"" value=""sim""> Tarefas</td>"
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""w_qualitativa"" value=""sim""> Programação Qualitativa</td>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""w_interessado"" value=""sim""> Interessados</td>"
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""w_orcamentaria"" value=""sim""> Programação Orçamentária</td>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""w_anexo"" value=""sim""> Anexos</td>"
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""w_meta"" value=""sim""> Metas Físicas</td>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""w_ocorrencia"" value=""sim""> Ocorrência/Anotações</td>"
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
    ShowHTML "          <td colspan=""2""><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""w_marca_bloco"" value="""" onClick=""javascript:MarcaTodosBloco();"" TITLE=""Marca todos os itens da relação""> Todos</td>"    
    ShowHTML "    </table></td></tr>"
    ShowHTML "    <table width=""100%"" border=""0"">"            
    ShowHTML "      <tr><td align=""center""><hr>"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Exibir"">"
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
  ShowHTML "</DIV>"
  ShowHTML "</BODY>"
  ShowHTML "</HTML>"  
End Sub

REM =========================================================================
REM Relatório detalhado dos programas cadastradas
REM -------------------------------------------------------------------------
Sub Rel_Det_Prog
  Dim p_sq_unidade, p_cd_programa, w_tipo_rel
  Dim w_logo
  Dim w_identificacao, w_responsavel, w_qualitativa, w_orcamentaria, w_indicador, w_restricao
  Dim w_acao, w_interessado, w_anexo, w_ocorrencia
  
  p_sq_unidade               = ucase(Trim(Request("p_sq_unidade")))
  p_cd_programa              = ucase(Trim(Request("p_cd_programa")))
  w_tipo_rel                 = uCase(trim(Request("w_tipo_rel")))
  w_identificacao            = uCase(trim(Request("w_identificacao")))
  w_responsavel              = uCase(trim(Request("w_responsavel")))
  w_qualitativa              = uCase(trim(Request("w_qualitativa")))
  w_orcamentaria             = uCase(trim(Request("w_orcamentaria")))
  w_indicador                = uCase(trim(Request("w_meta")))
  w_restricao                = uCase(trim(Request("w_restricao")))
  w_acao                     = uCase(trim(Request("w_tarefa")))
  w_interessado              = uCase(trim(Request("w_interessado")))
  w_anexo                    = uCase(trim(Request("w_anexo")))
  w_ocorrencia               = uCase(trim(Request("w_ocorrencia")))
  
  w_cont                  = 0 
  
  If O = "L" Then
     ' Recupera o logo do cliente a ser usado nas listagens
     DB_GetCustomerData RS, w_cliente
     If RS("logo") > "" Then
         w_logo = "\img\logo" & Mid(RS("logo"),Instr(RS("logo"),"."),30)
     End If
     DesconectaBD
  End If

  DB_GetLinkData RS1, RetornaCliente(), "ISPCAD"
  DB_GetSolicList_IS RS4, RS1("sq_menu"), w_usuario, "ISPCAD", 4, _
     null, null, null, null, null, null, _
     p_sq_unidade, null, null, null, _
     null, null, null, null, null, null, null, _
     null, null, null, null, null, null, null, p_cd_programa, null, null, w_ano
  RS.sort = "fim, prioridade"
  If w_tipo_rel = "WORD" Then
     HeaderWord null
     w_pag   = 1
     w_linha = 8
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     ShowHTML "<div align=""center"">"
     ShowHTML "<table width=""95%"" border=""0"" cellspacing=""3"">"
     ShowHTML "<tr><td colspan=""2"">"     
     ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "WORD") & """></TD><TD ALIGN=""RIGHT"" NOWRAP><B><FONT SIZE=4 COLOR=""#000000"">"
     ShowHTML "RELATÓRIO DETALHADO DE PROGRAMAS <br> Exercício " & w_ano
     ShowHTML "</FONT></TD></TR></TABLE>"
  Else
     Cabecalho
     ShowHTML "<HEAD>"
     ShowHTML "<TITLE>Relatório de Programas - Exercício " & w_ano & "</TITLE>"
     If InStr("P",O) > 0 Then
        ScriptOpen "JavaScript"
        ValidateOpen "Validacao"
        Validate "p_sq_unidade", "Responsável", "HIDDEN", "", "2", "60", "1", "1"
        Validate "p_cd_programa", "Programa", "HIDDEN", "", "1", "18", "1", "1"
        ValidateClose
        ShowHTML "  function MarcaTodosBloco() {"
        ShowHTML "    if (document.Form.w_marca_bloco.checked==true) {"
        ShowHTML "         document.Form.w_identificacao.checked=true;"
        ShowHTML "         document.Form.w_responsavel.checked=true;"
        ShowHTML "         document.Form.w_qualitativa.checked=true;"
        ShowHTML "         document.Form.w_orcamentaria.checked=true;"
        ShowHTML "         document.Form.w_indicador.checked=true;"
        ShowHTML "         document.Form.w_restricao.checked=true;"
        ShowHTML "         document.Form.w_acao.checked=true;"
        ShowHTML "         document.Form.w_interessado.checked=true;"
        ShowHTML "         document.Form.w_anexo.checked=true;"
        ShowHTML "         document.Form.w_ocorrencia.checked=true;"
        ShowHTML "    } else { "
        ShowHTML "         document.Form.w_identificacao.checked=false;"
        ShowHTML "         document.Form.w_responsavel.checked=false;"
        ShowHTML "         document.Form.w_qualitativa.checked=false;"
        ShowHTML "         document.Form.w_orcamentaria.checked=false;"
        ShowHTML "         document.Form.w_indicador.checked=false;"
        ShowHTML "         document.Form.w_restricao.checked=false;"
        ShowHTML "         document.Form.w_acao.checked=false;"
        ShowHTML "         document.Form.w_interessado.checked=false;"
        ShowHTML "         document.Form.w_anexo.checked=false;"
        ShowHTML "         document.Form.w_ocorrencia.checked=false;"
        ShowHTML "    } "
        ShowHTML "  }"
        ScriptClose        
     End If
     ShowHTML "</HEAD>"
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     If O = "L" Then
        BodyOpenClean "onLoad='document.focus()';"
        ShowHTML "<BASE HREF=""" & conRootSIW & """>"
        ShowHTML "<div align=""center"">"
        ShowHTML "<table width=""95%"" border=""0"" cellspacing=""3"">"
        ShowHTML "<tr><td colspan=""2"">"             
        ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "EMBED") & """></TD><TD ALIGN=""RIGHT"" NOWRAP><B><FONT SIZE=4 COLOR=""#000000"">"
        ShowHTML "RELATÓRIO DETALHADO DE PROGRAMAS <br> Exercício " & w_ano
        ShowHTML "</FONT></B></TD></TR></TABLE>"
     Else
        BodyOpen "onLoad='document.Form.p_cd_programa.focus()';"
        ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
        ShowHTML "<div align=center><center>"
        ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
        ShowHTML "<HR>"
     End If
  End If
  If O = "L" Then
    ShowHTML "<tr><td colspan=""2""><div align=""center""><hr NOSHADE color=#000000 size=2></div></td></tr>"
    ShowHTML "<tr><td colspan=""2""><div align=""center"">"
    ShowHTML "<table border=""0"" width=""100%"">"
    If p_sq_unidade > "" Then
       DB_GetUorgData RS1, p_sq_unidade
       ShowHTML "<tr><td width=""15%""><font size=""1""><b>Área de planejamento:</b></font></td><td><font size=""1"">" & RS1("nome") & " - " & RS1("sigla")& "</font></td>"
       RS1.Close
    Else
       ShowHTML "<tr><td width=""15%""><font size=""1""><b>Área de planejamento:</b></font></td><td><font size=""1"">Todas</font></td>"
    End If
    If p_cd_programa > "" Then
       DB_GetProgramaPPA_IS RS1, p_cd_programa, w_cliente, w_ano, null, null
       ShowHTML "    <td width=""7%""><font size=""1""><b>Programa:</b></font></td><td nowrap><font size=""1"">" & p_cd_programa & " - " & RS1("ds_programa") & "</font></td></tr>"
       RS1.Close
    Else
       ShowHTML "    <td width=""7%""><font size=""1""><b>Programa:</b></font></td><td><font size=""1"">Todos</font></td></tr>"
    End If    
    ShowHTML "</ul></td></tr></table>"
    ShowHTML "</div></td></tr>"
    ShowHTML "<tr><td colspan=""2""><div align=""center""><hr NOSHADE color=#000000 size=2></div></td></tr>"
    ShowHTML "<tr><td colspan=""2""><div align=""center""><font size=""3""><b>RELATÓRIO DE PROGRAMAS</b></font></div></td></tr>"
    w_linha = 8
    If RS4.EOF Then  
       w_linha = w_linha + 1
       ShowHTML "    <tr><td colspan=""13""><div align=""center""><font size=""3"" color=""red""><b><br>Nenhum programa encontrado</b></div></td></tr>"
    Else
       While Not RS4.EOF
          If w_linha > 19 and w_tipo_rel = "WORD" Then
             ShowHTML "    </table>"
             ShowHTML "  </td>"
             ShowHTML "</tr>"
             ShowHTML "</table>"
             ShowHTML "</div>"
             ShowHTML "    <br style=""page-break-after:always"">"
             w_linha = 6
             w_pag   = w_pag + 1
             ShowHTML "<div align=""center"">"
             ShowHTML "<table width=""95%"" border=""0"" cellspacing=""3"">"
             ShowHTML "<tr><td colspan=""2"">"             
             ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" src=""" & LinkArquivo(null, w_cliente, w_logo, null, null, null, "WORD") & """></TD><TD ALIGN=""RIGHT"" NOWRAP><B><FONT SIZE=4 COLOR=""#000000"">"
             ShowHTML "RELATÓRIO DETALHADO DE PROGRAMAS <br> Exercício " & w_ano
             ShowHTML "</FONT></B></TD></TR></TABLE>"
             ShowHTML "<tr><td colspan=""2""><div align=""center""><hr NOSHADE color=#000000 size=2></div></td></tr>"
             ShowHTML "<tr><td colspan=""2""><div align=""center"">"
             ShowHTML "<table border=""0"" width=""100%"">"
             If p_sq_unidade > "" Then
                DB_GetUorgData RS1, p_sq_unidade
                ShowHTML "<tr><td width=""20%""><font size=""1""><b>Área de planejamento:</b></font></td><td><font size=""1""" & RS1("nome") & " - " & RS1("sigla")& "</font></td>"
                RS1.Close
             Else
                ShowHTML "<tr><td width=""20%""><font size=""1""><b>Área de planejamento:</b></font></td><td><font size=""1"">Todas</font></td>"
             End If
             If p_cd_programa > "" Then
                DB_GetProgramaPPA_IS RS1, p_cd_programa, w_cliente, w_ano, null, null
                ShowHTML "    <td width=""20%""><font size=""1""><b>Programa:</b></font></td><td><font size=""1"">" & p_cd_programa & " - " & RS1("ds_programa") & "</font></td></tr>"
                RS1.Close
             Else
                ShowHTML "    <td width=""20%""><font size=""1""><b>Programa:</b></font></td><td><font size=""1"">Todos</font></td></tr>"
             End If
             ShowHTML "</ul></td></tr></table>"
             ShowHTML "</div></td></tr>"
             ShowHTML "<tr><td colspan=""2""><div align=""center""><hr NOSHADE color=#000000 size=2></div></td></tr>"
             ShowHTML "<tr><td colspan=""2""><div align=""center""><font size=""3""><b>RELATÓRIO DE PROGRAMAS</b></font></div></td></tr>"
          End If
          If w_tipo_rel = "WORD" Then 
             ShowHTML VisualPrograma(RS4("sq_siw_solicitacao"), "", w_usuario, 0, 1, w_identificacao, w_responsavel, w_qualitativa, w_orcamentaria, w_indicador, w_restricao, w_interessado, w_anexo, w_acao, w_ocorrencia, "nao")
             ShowHTML "<tr><td colspan=""2""><div align=""center""><BR></div></td></tr>"
          Else
             ShowHTML VisualPrograma(RS4("sq_siw_solicitacao"), "", w_usuario, 0, 1, w_identificacao, w_responsavel, w_qualitativa, w_orcamentaria, w_indicador, w_restricao, w_interessado, w_anexo, w_acao, w_ocorrencia, "nao")
             ShowHTML "<tr><td colspan=""2""><div align=""center""><BR></div></td></tr>"
          End If
          w_linha = w_linha + 30
          RS4.MoveNext
       wend
    End If
    
    ShowHTML "   <tr><td colspan=""2""><br><font size=""2""><b>DADOS DA CONSULTA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>"
    ShowHTML "   <tr><td><font size=""1""><b>Consulta Realizada por:</b></font></td>"
    ShowHTML "       <td><font size=""1"">" &  Session("NOME_RESUMIDO") & "</font></td></tr>"
    ShowHTML "   <tr><td><font size=""1""><b>Data da Consulta:</b></font></td>"
    ShowHTML "       <td><font size=""1"">" &  FormataDataEdicao(FormatDateTime(now(),2)) & ", " & FormatDateTime(now(),4) & "</font></td></tr>"
    
    ShowHTML "    </table>"
    ShowHTML "  </div></td>"
    ShowHTML "</tr>"
    DesconectaBD
  ElseIf O = "P" Then
    AbreForm "Form", w_dir & w_pagina & par, "POST", "return(Validacao(this));", "Programa",P1,P2,P3,P4,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave & """>"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"

    ShowHTML "<tr><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
    SelecaoUnidade "Á<U>r</U>ea planejamento:", "R", null, p_sq_unidade, null, "p_sq_unidade", null, "onchange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='p_sq_unidade'; document.Form.target=''; document.Form.O.value='P'; document.Form.submit();"""
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
    SelecaoProgramaPPA "<u>P</u>rograma PPA:", "P", null, w_cliente, w_ano, p_cd_programa, "p_cd_programa", "RELATORIO", "onchange=""document.Form.action='" & w_dir & w_pagina & par & "'; document.Form.w_troca.value='p_cd_programa'; document.Form.target=''; document.Form.O.value='P'; document.Form.submit();""", w_menu
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
    ShowHTML "        <td><font size=""1""><b><u>T</u>ipo de relatório:</b><br><SELECT ACCESSKEY=""T"" CLASS=""STS"" NAME=""w_tipo_rel"" " & w_Disabled & ">"
    If nvl(w_tipo_rel,"-") = "Word"  Then
       ShowHTML "          <option value="""">Consulta na Tela"
       ShowHTML "          <option value=""Word"" SELECTED>Documento Word"
    Else
       ShowHTML "          <option value="""" SELECTED>Consulta na Tela"
       ShowHTML "          <option value=""Word"">Documento Word"
    End If
    ShowHTML "          </select></td><tr>"
    ShowHTML "    </table></td></tr>"
    ShowHTML "<tr><td align=""center"">"
    ShowHTML "    <table width=""97%"" border=""0"">"    
    ShowHTML "      <tr bgcolor=""" & conTrAlternateBgColor & """><td colspan=""2""><font size=""1""><b>ESCOLHA OS BLOCOS A SEREM VISUALIZADOS NO RELATÓRIO</b></font></td></tr>"
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""w_identificacao"" value=""sim""> Identficação</td>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""w_restricao"" value=""sim""> Restrições</td>"
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""w_responsavel"" value=""sim""> Responsáveis</td>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""w_interessado"" value=""sim""> Interessados</td>"
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""w_qualitativa"" value=""sim""> Programação Qualitativa</td>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""w_anexo"" value=""sim""> Anexos</td>"
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""w_orcamentaria"" value=""sim""> Programação Orçamentária</td>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""w_acao"" value=""sim""> Ações</td>"
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""w_indicador"" value=""sim""> Indicadores</td>"
    ShowHTML "          <td><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""w_ocorrencia"" value=""sim""> Ocorrência/Anotações</td>"
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
    ShowHTML "          <td colspan=""2""><font size=""1""><INPUT " & w_Disabled & " class=""STC"" type=""CHECKBOX"" name=""w_marca_bloco"" value="""" onClick=""javascript:MarcaTodosBloco();"" TITLE=""Marca todos os itens da relação""> Todos</td>"    
    ShowHTML "    </table></td></tr>"
    ShowHTML "    <table width=""100%"" border=""0"">"            
    ShowHTML "      <tr><td align=""center""><hr>"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Exibir"">"
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
  ShowHTML "</DIV>"
  ShowHTML "</BODY>"
  ShowHTML "</HTML>"  
End Sub

REM =========================================================================
REM Gera uma linha de apresentação da tabela de metas
REM -------------------------------------------------------------------------
Function MetaLinha (p_chave, p_chave_aux, p_titulo, p_word, p_programada, _
                     p_unidade_medida, p_quantidade, p_fim, p_perc, p_oper, p_tipo, p_loa)
  Dim l_html, l_row

  If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
  l_html = l_html & VbCrLf & "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
  l_html = l_html & VbCrLf & "        <td nowrap " & l_row & "><font size=""1"">"
  If p_fim < Date() and cDbl(p_perc) < 100 Then
     l_html = l_html & VbCrLf & "           <img src=""" & conImgAtraso & """ border=0 width=15 height=15 align=""center"">"
  ElseIf cDbl(p_perc) < 100 Then
     l_html = l_html & VbCrLf & "           <img src=""" & conImgNormal & """ border=0 width=15 height=15 align=""center"">"
  Else
     l_html = l_html & VbCrLf & "           <img src=""" & conImgOkNormal & """ border=0 width=15 height=15 align=""center"">"
  End IF
  If p_word <> "WORD" Then
     l_html = l_html & VbCrLf & "<A class=""HL"" HREF=""#"" onClick=""window.open('Acao.asp?par=AtualizaMeta&O=V&w_chave=" & p_chave & "&w_chave_aux=" & p_chave_aux & "&w_tipo=Volta&P1=10&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "','Meta','width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no'); return false;"" title=""Clique para exibir os dados!"">" & p_titulo & "</A>"
  Else
     l_html = l_html & VbCrLf & "        " & p_titulo & "</td>"
  End if
  If p_loa > "" Then
     l_html = l_html & VbCrLf & "        <td align=""center""><font size=""1"">Sim</b>"
  Else
     l_html = l_html & VbCrLf & "        <td align=""center""><font size=""1"">Não</b>"
  End If
  l_html = l_html & VbCrLf & "        <td align=""center"" " & l_row & "><font size=""1"">" & FormataDataEdicao(p_fim) & "</td>"
  l_html = l_html & VbCrLf & "        <td nowrap " & l_row & "><font size=""1"">" & Nvl(p_unidade_medida, "---") & " </td>"
  l_html = l_html & VbCrLf & "        <td nowrap align=""right"" " & l_row & "><font size=""1"">" & FormatNumber(cDbl(Nvl(p_quantidade,0)),2) & "</td>"
  l_html = l_html & VbCrLf & "        <td nowrap align=""right"" " & l_row & "><font size=""1"">" & p_perc & " %</td>"
  l_html = l_html & VbCrLf &  "      </tr>"

  MetaLinha = l_html

  Set l_row     = Nothing
  Set l_html    = Nothing
End Function

REM =========================================================================
REM Gera uma linha de apresentação da tabela de indicadores
REM -------------------------------------------------------------------------
Function Indicadorlinha (p_chave,  p_chave_aux, p_titulo, _
                         p_valor_ref, p_valor_prog, p_valor_apurado, p_apuracao_ind, _
                         p_unidade_medida, p_word,  p_destaque, _
                         p_oper,   p_tipo,     p_loa)
  Dim l_html, RsQuery, l_row

  If p_loa > "" Then
     p_loa = "Sim"
  Else
     p_loa = "Não"
  End If 
  l_row = ""

  If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
  l_html = l_html & VbCrLf & "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
  l_html = l_html & VbCrLf & "        <td " & l_row & "><font size=""1"">"
  If cDbl(Nvl(p_word,0)) = 1 Then
     l_html = l_html & VbCrLf & "        <td><font size=""1"">" & p_destaque & p_titulo & "</b>"
  Else
     l_html = l_html & VbCrLf & "<A class=""HL"" HREF=""#"" onClick=""window.open('Programa.asp?par=AtualizaIndicador&O=V&w_chave=" & RS("sq_siw_solicitacao") & "&w_chave_aux=" & p_chave_aux & "&w_tipo=Volta&P1=10&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "','Indicador','width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no'); return false;"" title=""Clique para exibir os dados!"">" & p_destaque & p_titulo & "</A>"
  End If
  l_html = l_html & VbCrLf & "        <td align=""center"" " & l_row & "><font size=""1"">" & p_loa & "</td>"
  l_html = l_html & VbCrLf & "        <td nowrap align=""right"" " & l_row & "><font size=""1"">" & FormatNumber(cDbl(Nvl(p_valor_ref,0)),2) & " </td>"
  l_html = l_html & VbCrLf & "        <td nowrap align=""right"" " & l_row & "><font size=""1"">" & FormatNumber(cDbl(Nvl(p_valor_prog,0)),2) & " </td>"
  l_html = l_html & VbCrLf & "        <td nowrap align=""right"" " & l_row & "><font size=""1"">" & FormatNumber(cDbl(Nvl(p_valor_apurado,0)),2) & " </td>"
  l_html = l_html & VbCrLf & "        <td align=""center"" " & l_row & "><font size=""1"">" & Nvl(FormataDataEdicao(p_apuracao_Ind),"---") & "</td>"
  l_html = l_html & VbCrLf & "        <td align=""left"" " & l_row & "><font size=""1"">" & p_unidade_medida & "</td>"
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
REM Rotina principal
REM -------------------------------------------------------------------------
Sub Main
  Select Case Par
    Case "REL_PPA"              Rel_PPA
    Case "REL_PROJETO"          Rel_projeto
    Case "REL_PROGRAMA"         Rel_Programa
    Case "REL_SINTETICO_PR"     Rel_Sintetico_PR
    Case "REL_SINTETICO_PPA"    Rel_Sintetico_PPA
    Case "REL_SINTETICO_PROG"   Rel_Sintetico_Prog
    Case "REL_GERENCIAL_PROG"   Rel_Gerencial_Prog
    Case "REL_GERENCIAL_ACAO"   Rel_Gerencial_Acao
    Case "REL_GERENCIAL_TAREFA" Rel_Gerencial_Tarefa
    Case "REL_METAS"            Rel_Metas
    Case "REL_DET_TAREFA"       Rel_Det_Tarefa
    Case "REL_DET_ACAO"         Rel_Det_Acao
    Case "REL_DET_PROG"         Rel_Det_Prog
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
%>