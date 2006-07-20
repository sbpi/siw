<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Geral.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Geral.asp" -->
<!-- #INCLUDE FILE="DB_Tipo_Curso.asp" -->
<!-- #INCLUDE FILE="DB_Turno.asp" -->
<!-- #INCLUDE FILE="DB_Serie.asp" -->
<!-- #INCLUDE FILE="DB_Tipo_Disciplina.asp" -->
<!-- #INCLUDE FILE="DB_Origem_Escola.asp" -->
<!-- #INCLUDE FILE="DB_Relatorio.asp" -->

<%
Response.Expires = -1500
REM =========================================================================
REM  /Rel_Boletim.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Relatório de botetins
REM Mail     : alex@sbpi.com.br
REM Criacao  : 03/09/2003, 10:00
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
Dim dbms, sp, RS, RS1, RS2, RS3, w_ano
Dim P1, P2, P3, P4, TP, SG
Dim R, O, w_Cont, w_Pagina, w_Disabled, w_TP, w_troca, w_cor, w_Dir
Dim w_ContOut
Dim w_Titulo
Dim w_Imagem
Dim w_ImagemPadrao
Dim w_Assinatura, w_Cliente, w_Classe, w_filter
Private Par, w_linha, w_pag

AbreSessao

' Carrega variáveis locais com os dados dos parâmetros recebidos
Par          = ucase(Request("Par"))
P1           = Request("P1")
P2           = Request("P2")
P3           = Request("P3")
P4           = Request("P4")
TP           = Request("TP")
SG           = ucase(Request("SG"))
R            = uCase(Request("R"))
O            = uCase(Request("O"))
w_troca      = Request("w_troca")
w_Assinatura = uCase(Request("w_Assinatura"))
w_Pagina     = "Rel_Boletim.asp?par="
w_Dir        = "ecw/"
w_Disabled   = "ENABLED"

If P3 = "" Then P3 = 1           Else P3 = cDbl(P3) End If
If P4 = "" Then P4 = conPageSize Else P4 = cDbl(P4) End If

If O = "" Then O = "P" End If

Select Case O
  Case "I" 
     w_TP = TP & " - Inclusão"
  Case "A" 
     w_TP = TP & " - Alteração"
  Case "E" 
     w_TP = TP & " - Exclusão"
  Case "P" 
     w_TP = TP & " - Filtragem"
  Case "D" 
     w_TP = TP & " - Desativar"
  Case "T" 
     w_TP = TP & " - Ativar"
  Case "H" 
     w_TP = TP & " - Herança"
  Case Else
     w_TP = TP & " - Listagem"
End Select

w_cliente         = RetornaCliente()
  
If Request("Regional") > "" Then Session("Regional") = Request("Regional") End If
If Request("Periodo") > ""  Then Session("Periodo") = Request("Periodo")   End If

VerificaParametros

Main

FechaSessao

Set w_filter        = Nothing
Set w_cor           = Nothing
Set w_classe        = Nothing
Set w_cliente       = Nothing

Set RS              = Nothing
Set RS1             = Nothing
Set RS2             = Nothing
Set RS3             = Nothing
Set Par             = Nothing
Set P1              = Nothing
Set P2              = Nothing
Set P3              = Nothing
Set P4              = Nothing
Set TP              = Nothing
Set SG              = Nothing
Set R               = Nothing
Set O               = Nothing
Set w_ImagemPadrao  = Nothing
Set w_Imagem        = Nothing
Set w_Titulo        = Nothing
Set w_ContOut       = Nothing
Set w_Cont          = Nothing
Set w_Pagina        = Nothing
Set w_Disabled      = Nothing
Set w_TP            = Nothing
Set w_troca         = Nothing
Set w_Assinatura    = Nothing

REM =========================================================================
REM Rotina de consulta de alunos
REM -------------------------------------------------------------------------
Sub Inicial

  Dim w_co_aluno
  Dim p_matricula
  Dim w_ds_Aluno, p_ds_Aluno
  Dim p_unidade, p_tipo
  Dim p_modalidade, p_serie, p_turma
  Dim p_busca_aluno
  Dim p_Ordena

  p_ds_aluno         = uCase(Request("p_ds_Aluno"))
  p_unidade          = uCase(Request("p_unidade"))
  p_modalidade       = uCase(Request("p_modalidade"))
  p_serie            = uCase(Request("p_serie"))
  p_turma            = uCase(Request("p_turma"))
  p_matricula        = uCase(Request("p_matricula"))
  p_busca_aluno      = uCase(Request("p_busca_aluno"))
  p_ordena           = uCase(Request("p_ordena"))
  
  If O = "L" Then
     If p_ds_aluno > ""    Then If p_busca_aluno = "S" Then p_ds_aluno = "%" & p_ds_aluno & "%"       Else p_ds_aluno = p_ds_aluno & "%"       End If End If
     DB_GetStudentRel RS, Session("periodo"), Session("regional"), null, p_ds_aluno, p_matricula, p_unidade, p_serie, p_turma, p_modalidade, null, null, null, null, null, null, null, null, null, null, null
     If p_ordena > "" Then RS.sort = p_ordena & ", ds_aluno" Else RS.sort = "ds_aluno" End If
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     If O="P" Then
        Validate "periodo", "Período", "SELECT", "1", "1", "10", "1", "1"
        Validate "regional", "Regional", "SELECT", "", "1", "10", "1", "1"
        Validate "p_matricula", "Matrícula", "1", "", "10", "12", "", "0123456789-"
        Validate "p_ds_aluno", "Nome", "1", "", "3", "40", "1", "1"
        Validate "P4", "Linhas por página", "1", "1", "1", "4", "", "0123456789"
        ShowHtml "  if (theForm.p_matricula.value == '' && theForm.p_modalidade.selectedIndex == 0 && theForm.p_serie.selectedIndex == 0 && theForm.p_turma.selectedIndex == 0 && theForm.p_ds_aluno.value == '' && theForm.p_unidade.selectedIndex == 0) {"
        ShowHTML "     alert('Indique pelo menos um critério de filtragem!');"
        ShowHTML "     theForm.p_matricula.focus();"
        ShowHTML "     return false;"
        ShowHTML "  }"
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ScriptOpen "JavaScript"
  ShowHTML "function janelaBoletimHTML(p_matricula) {"
  ShowHTML "  window.open('" & w_Pagina & "ExibeBoletim&R=" & w_Pagina & par & "&O=F&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & "BOLETIM" & "&p_matricula=' + p_matricula ,'Boletim','top=10 left=30 width=750 height=500 toolbar=no scrollbars=yes status=no address=no resizable=yes');"
  ShowHTML "}"
  ShowHTML "function janelaBoletimWORD(p_matricula) {"
  ShowHTML "  window.open('" & w_Pagina & "ExibeBoletim&R=" & w_Pagina & par & "&O=R&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & "BOLETIM" & "&p_matricula=' + p_matricula ,'Boletim','top=10 left=30 width=750 height=500 scrollbars=yes menubar=yes resizable=yes');"
  ShowHTML "}"
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If InStr("P",O) > 0 Then
     BodyOpen "onLoad='document.Form.p_matricula.focus()';"
  Else
     BodyOpen "onLoad=document.focus();"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  If O <> "P" Then ExibeParametros w_cliente End If
  
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ShowHTML "<tr><td><font size=""2"">"
    If p_matricula & p_serie & p_unidade & p_ds_aluno & p_turma & p_modalidade & p_Ordena > "" Then
       ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_Dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_ds_aluno=" & p_ds_aluno & "&p_matricula=" & p_matricula & "&p_serie=" & p_serie & "&p_turma=" & p_turma & "&p_modalidade=" & p_modalidade & "&p_unidade=" & p_unidade & "&p_ordena=" & p_ordena & "&p_busca_aluno=" &p_busca_aluno& """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a></font>"
    Else
       ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_Dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_ds_aluno=" & p_ds_aluno & "&p_matricula=" & p_matricula & "&p_serie=" & p_serie & "&p_turma=" & p_turma & "&p_modalidade=" & p_modalidade & "&p_unidade=" & p_unidade & "&p_ordena=" & p_ordena & "&p_busca_aluno=" &p_busca_aluno& """><u>F</u>iltrar (Inativo)</a>"
    End If
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Matrícula</font></td>"
    ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
    ShowHTML "          <td><font size=""1""><b>Série</font></td>"
    ShowHTML "          <td><font size=""1""><b>Turma</font></td>"
    ShowHTML "          <td><font size=""1""><b>Unidade</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF and RS.AbsolutePage = P3
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("co_aluno") & "</td>"
        ShowHTML "        <td><font size=""1"">" & lCase(RS("ds_aluno")) & "</span></td>"
        ShowHTML "        <td><font size=""1"">" & Nvl(RS("sg_serie"),"---") & "</td>"
        ShowHTML "        <td><font size=""1"">" & Nvl(RS("co_letra_turma"),"---") & "</td>"
        ShowHTML "        <td><font size=""1"">" & Nvl(uCase(RS("ds_escola")),"---") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "           <a class=""HL"" HREF=""javascript:janelaBoletimHTML('" & trim(RS("co_aluno")) & "');"">Visualizar"
        ShowHTML "           <a class=""HL"" HREF=""javascript:janelaBoletimWORD('" & trim(RS("co_aluno")) & "');"">Gerar Word"
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    ShowHTML "<tr><td align=""center"" colspan=3>"
    MontaBarra w_dir&w_pagina&par&"&R="&w_Pagina&par&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG, RS.PageCount, P3, P4, RS.RecordCount
    ShowHTML "</tr>"
    DesConectaBD     
  ElseIf Instr("P",O) > 0 Then
    AbreForm "Form", w_Dir&w_Pagina&par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""70%"" border=""0"">"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    SelecaoPeriodoLetivo "Perío<u>d</u>o letivo:", "D", null, Session("periodo"), null, "periodo", null
    SelecaoRegional "<u>R</u>egional:", "R", null, Session("regional"), null, "regional", "informal = 'N'", "onChange=""document.Form.target=''; document.Form.O.value='P'; document.Form.submit();"""
    ShowHTML "          </table>"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    If Session("regional") = "00" or IsNull(Tvl(Session("regional"))) Then
       SelecaoUnidadeEnsino "<u>U</u>nidade de ensino:", "U", null, p_unidade, null, "p_unidade", null, "onChange=""document.Form.target=''; document.Form.O.value='P'; document.Form.submit();"""
    Else
       SelecaoUnidadeEnsino "<u>U</u>nidade de ensino:", "U", null, p_unidade, null, "p_unidade", "co_sigre like '" & Session("regional") & "*'", "onChange=""document.Form.target=''; document.Form.O.value='P'; document.Form.submit();"""
    End IF
    SelecaoModEnsino "<u>M</u>odalidade de ensino:", "M", null, p_modalidade, null, "p_modalidade", null, "onChange=""document.Form.target=''; document.Form.O.value='P'; document.Form.submit();"""
    ShowHTML "          </table>"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    If p_modalidade > "" Then
       SelecaoSerie "<u>S</u>érie:", "S", null, p_serie, null, "p_serie", "co_tipo_curso = " & Nvl(p_modalidade,0), "onChange=""document.Form.target=''; document.Form.O.value='P'; document.Form.submit();"""
    Else
       SelecaoSerie "<u>S</u>érie:", "S", null, p_serie, null, "p_serie", null, "onChange=""document.Form.target=''; document.Form.O.value='P'; document.Form.submit();"""
    End If
    w_filter = ""
    If p_modalidade > "" Then w_filter = w_filter & " and co_tipo_curso = " & p_modalidade End If
    If p_serie > ""      Then w_filter = w_filter & " and sg_serie = '" & p_serie & "'"    End If
    If w_filter > "" Then w_filter = mid(w_filter,6,200) Else w_filter = null End If
    SelecaoTurma "T<u>u</u>rma:", "U", null, p_turma, Nvl(p_unidade,0), "p_turma", w_filter, null
    ShowHTML "          </table>"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>M</U>atrícula:<br><INPUT ACCESSKEY=""M"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_matricula"" size=""12"" maxlength=""12"" value=""" & p_matricula & """></td>"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>N</U>ome aluno:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_ds_aluno"" size=""40"" maxlength=""40"" value=""" & p_ds_aluno & """></td>"
    ShowHTML "          <td align=""left""><font size=""1""><b>Buscar:</b>"
    If p_busca_aluno = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_busca_aluno"" value=""N""> Iniciado em <input " & w_Disabled & " type=""radio"" name=""p_busca_aluno"" value=""S"" checked> Qualquer parte &nbsp;&nbsp;&nbsp;"
    Else
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_busca_aluno"" value=""N"" checked> Iniciado em <input " & w_Disabled & " type=""radio"" name=""p_busca_aluno"" value=""S""> Qualquer parte &nbsp;&nbsp;&nbsp;"
    End If    
    ShowHTML "</table>"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY=""O"" " & w_Disabled & " class=""STS"" name=""p_ordena"" size=""1"">"
    If p_Ordena="CO_ALUNO" Then
       ShowHTML "          <option value=""co_aluno"">Matrícula<option value="""" SELECTED>Nome aluno<option value=""ds_escola"">Unidade de ensino"
    ElseIf p_Ordena="DS_RESPONSAVEL" Then
       ShowHTML "          <option value=""co_aluno"">Matrícula<option value="""">Nome aluno<option value=""ds_escola"">Unidade de ensino"
    ElseIf p_Ordena="DS_ESCOLA" Then
       ShowHTML "          <option value=""co_aluno"">Matrícula<option value="""">Nome aluno<option value=""ds_escola"" SELECTED>Unidade de ensino"
    Else
       ShowHTML "          <option value=""co_aluno"">Matrícula<option value="""" SELECTED>Nome aluno<option value=""ds_escola"">Unidade de ensino"
    End If
    ShowHTML "          </select></td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""STI"" type=""text"" name=""P4"" size=""4"" maxlength=""4"" value=""" & P4 & """></td>"
    ShowHTML "      </table>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"" name=""Botao"" value=""Remover filtro"">"
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

  Set w_co_aluno         = Nothing
  Set w_ds_Aluno         = Nothing
  Set p_ds_Aluno         = Nothing
  Set p_ordena           = Nothing

End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de exibição dos dados de alunos
REM -------------------------------------------------------------------------
Sub ExibeBoletim

  Dim p_matricula
  Dim w_ds_unidade_ant
  Dim w_cont_ds
  p_matricula        = uCase(Request("p_matricula"))
  SG                 = uCase(Request("SG"))
  If O = "R" Then
     HeaderWord null
     w_pag   = 1
     w_linha = 6
     CabecalhoWord w_cliente, "Boletim", w_pag
  Else
     Cabecalho
     ShowHTML "<HEAD>"
     ShowHTML    "<TITLE>Aluno - Boletim</TITLE>"
     ShowHTML "</HEAD>"
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     BodyOpenClean "onLoad=document.focus();"
     CabecalhoRelatorio w_cliente, "Boletim"
     ExibeParametrosRel w_cliente
  End If
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  Dim  w_resultado, w_total_faltas, w_media_curso
  DB_GetAlunoData RS, Session("periodo"), p_matricula, SG
  RS.sort = "ds_ordem_imp"
  ShowHTML "<tr><td><font size=""2"">"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  ShowHTML "<tr><td align=""center"" colspan=3>"
  If Not RS.EOF Then
     ShowHTML "<tr valign=""top""><td bgcolor=""#FAEBD7""><table border=1 cellpadding=2 cellspacing=5 width=""100%"">"
     ShowHTML "  <tr valign=""top"">"
     ShowHTML "    <td colspan=1><font size=1>Nº de matrícula:<br><b>" & Nvl(Trim(RS("co_aluno")),"---")
     ShowHTML "    <td colspan=1><font size=1>Nome:<br><b>" & Nvl(trim(RS("ds_aluno")),"---")
     ShowHTML "    <td colspan=1><font size=1>Unidade:<br><b>" & Nvl(trim(RS("ds_escola")),"---")
     ShowHTML "  <tr valign=""top"">"
     ShowHTML "    <td colspan=1><font size=1 colspan=1>Turno:<br><b>" & Nvl(Trim(RS("co_turno")),"---")
     ShowHTML "    <td colspan=1><font size=1 colspan=1>Série:<br><b>" & Nvl(trim(RS("descr_serie")),"---")    
     ShowHTML "    <td colspan=1><font size=1 colspan=2>Turma:<br><b>" & Nvl(trim(RS("co_letra_turma")),"---")
     ShowHTML "  <tr valign=""top"">"
     ShowHTML "    <td colspan=1><font size=1 colspan=1>Bloco:<br><b>" & Nvl(Trim(RS("co_bloco")),"---")
     ShowHTML "    <td colspan=1><font size=1 colspan=1>Sala:<br><b>"  & Nvl(trim(RS("ds_sala")),"---")
     ShowHTML "    <td colspan=1><font size=1 colspan=2>Modalidade:<br><b>" & Nvl(trim(RS("ds_curso")),"---")
     ShowHTML "  </table>"
     'ShowHTML "  <table border=0 cellpadding=2 cellspacing=0 width=""100%"">"
     'ShowHTML "    <tr valign=""top"">"
     'ShowHTML "      <td colspan=1><font size=1>Matrícula:</td><td colspan=3><font size=1><b>" & Nvl(Trim(RS("co_aluno")),"---")
     'ShowHTML "      <td colspan=1><font size=1>Aluno:</td><td colspan=3><font size=1><b>" & Nvl(trim(RS("ds_aluno")),"---")
     'ShowHTML "      <td colspan=1><font size=1>Unidade:</td><td colspan=3><font size=1><b>" & Nvl(trim(RS("ds_escola")),"---")
     'ShowHTML "    <tr valign=""top"">"
     'ShowHTML "      <td colspan=1><font size=1>Turno:</td><td colspan=3><font size=1><b>" & Nvl(Trim(RS("co_turno")),"---")
     'ShowHTML "      <td colspan=1><font size=1>Série:</td><td colspan=3><font size=1><b>" & Nvl(trim(RS("descr_serie")),"---")
     'ShowHTML "      <td colspan=1><font size=1>Turma:</td><td colspan=3><font size=1><b>" & Nvl(trim(RS("co_letra_turma")),"---")
     'ShowHTML "    <tr valign=""top"">"
     'ShowHTML "      <td colspan=1><font size=1>Bloco:</td><td colspan=3><font size=1><b>" & Nvl(Trim(RS("co_bloco")),"---")
     'ShowHTML "      <td colspan=1><font size=1>Sala:</td><td colspan=3><font size=1><b>" & Nvl(trim(RS("ds_sala")),"---")
     'ShowHTML "      <td colspan=1><font size=1>Modalidade:</td><td colspan=3><font size=1><b>" & Nvl(trim(RS("ds_curso")),"---")
     'ShowHTML "    </table>"
  Else
     ShowHTML "  <tr valign=""top""><td colspan=13><table border=0 cellpadding=2 cellspacing=0 width=""100%"">"
     ShowHTML "    <tr valign=""top"">"
     ShowHTML "      <td colspan=4><font size=1>Matrícula:</td><td colspan=3><font size=1>---"
     ShowHTML "      <td colspan=4><font size=1>Aluno:</td><td colspan=3><font size=1>---"
     ShowHTML "      <td colspan=4><font size=1>Unidade:</td><td colspan=3><font size=1>---"
     ShowHTML "    <tr valign=""top"">"
     ShowHTML "      <td colspan=4><font size=1>Turno:</td><td colspan=3><font size=1><b>---"
     ShowHTML "      <td colspan=4><font size=1>Série:</td><td colspan=3><font size=1><b>---"
     ShowHTML "      <td colspan=4><font size=1>Turma:</td><td colspan=3><font size=1><b>---"
     ShowHTML "    <tr valign=""top"">"
     ShowHTML "      <td colspan=4><font size=1>Bloco:</td><td colspan=3><font size=1><b>---"
     ShowHTML "      <td colspan=4><font size=1>Sala:</td><td colspan=3><font size=1><b>---"
     ShowHTML "      <td colspan=4><font size=1>Modalidade:</td><td colspan=3><font size=1><b>---"
     ShowHTML "    </table>"
  End If
  ShowHTML "</td></tr>"
  ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
  ShowHTML "    <tr valign=""top"">"
  ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
  ShowHTML "          <td rowspan=2 colspan=1 align=""center""><font size=""1""><b>Componente Curricular</font></td>"
  ShowHTML "          <td rowspan=1 colspan=2 align=""center""><font size=""1""><b>1º Bim</font></td>"
  ShowHTML "          <td rowspan=1 colspan=2 align=""center""><font size=""1""><b>2º Bim</font></td>"
  ShowHTML "          <td rowspan=1 colspan=2 align=""center""><font size=""1""><b>3º Bim</font></td>"
  ShowHTML "          <td rowspan=1 colspan=2 align=""center""><font size=""1""><b>4º Bim</font></td>"
  ShowHTML "          <td rowspan=2 colspan=1 align=""center""><font size=""1""><b>Média</font></td>"
  ShowHTML "          <td rowspan=2 colspan=1 align=""center""><font size=""1""><b>Av. Especial</font></td>"
  ShowHTML "          <td rowspan=2 colspan=1 align=""center""><font size=""1""><b>Total de Faltas</font></td>"
  ShowHTML "          <td rowspan=2 colspan=1 align=""center""><font size=""1""><b>Resultado</font></td>"
  ShowHTML "        </tr>"
  ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
  ShowHTML "          <td rowspan=1 colspan=1 align=""center""><font size=""1""><b>Nota</font></td>"
  ShowHTML "          <td rowspan=1 colspan=1 align=""center""><font size=""1""><b>Falta</font></td>"
  ShowHTML "          <td rowspan=1 colspan=1 align=""center""><font size=""1""><b>Nota</font></td>"
  ShowHTML "          <td rowspan=1 colspan=1 align=""center""><font size=""1""><b>Falta</font></td>"
  ShowHTML "          <td rowspan=1 colspan=1 align=""center""><font size=""1""><b>Nota</font></td>"
  ShowHTML "          <td rowspan=1 colspan=1 align=""center""><font size=""1""><b>Falta</font></td>"
  ShowHTML "          <td rowspan=1 colspan=1 align=""center""><font size=""1""><b>Nota</font></td>"
  ShowHTML "          <td rowspan=1 colspan=1 align=""center""><font size=""1""><b>Falta</font></td>"
  ShowHTML "        </tr>"
  If RS.EOF Then
     ShowHTML "      <tr bgcolor=""" & conTrAlternateBgColor & """><td colspan=13 align=""center""><font size=""2""><b>Não foi encontrado nenhum registro.</b></td></tr>"
  Else
     While Not RS.EOF
        w_media_curso = RS("nu_media_nota")
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """>"        
        ShowHTML "      <td align=""left""><font size=1>" & Nvl(trim(lCase(RS("ds_disciplina"))),"---")
        ShowHTML "      <td align=""center""><font size=1>" & Nvl(RS("nu_nota_b1"),"---")
        ShowHTML "      <td align=""center""><font size=1>" & Nvl(trim(RS("nu_faltas_b1")),"---")
        ShowHTML "      <td align=""center""><font size=1>" & Nvl(RS("nu_nota_b2"),"---")
        ShowHTML "      <td align=""center""><font size=1>" & Nvl(trim(RS("nu_faltas_b2")),"---")
        ShowHTML "      <td align=""center""><font size=1>" & Nvl(RS("nu_nota_b3"),"---")
        ShowHTML "      <td align=""center""><font size=1>" & Nvl(trim(RS("nu_faltas_b3")),"---")
        ShowHTML "      <td align=""center""><font size=1>" & Nvl(RS("nu_nota_b4"),"---")
        ShowHTML "      <td align=""center""><font size=1>" & Nvl(trim(RS("nu_faltas_b4")),"---")
        ShowHTML "      <td align=""center""><font size=1>" & Nvl(trim(RS("nu_media_anual")),"---")
        ShowHTML "      <td align=""center""><font size=1>" & Nvl(trim(RS("nu_recup_especial")),"---")
        w_total_faltas = cDbl(Cvl(RS("nu_faltas_b1"))) + cDbl(Cvl(RS("nu_faltas_b2"))) + cDbl(Cvl(RS("nu_faltas_b3"))) + cDbl(Cvl(RS("nu_faltas_b4")))
        ShowHTML "      <td align=""center""><font size=1>" & Nvl(w_total_faltas,"---")
        w_resultado = null
        If RS("nu_recup_especial") > ""  and RS("nu_faltas_b4") > ""  Then
           If RS("nu_recup_especial") > RS("nu_media_nota") Then
              w_resultado = "AP"
           Else
              w_resultado = "RP"
           End If
        Else
           If RS("nu_media_anual") > RS("nu_media_nota")  and RS("nu_faltas_b4") > ""  Then
              w_resultado = "AP"
           ElseIf RS("nu_media_anual") < RS("nu_media_nota")  and RS("nu_faltas_b4") > "" Then
              w_resultado = "RP"
           End If
        End If
        ShowHTML "      <td align=""center""><font size=1>" & Nvl((w_resultado),"---")
        RS.MoveNext
     Wend
  End If
  ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
  ShowHTML "        <td colspan=13 align=""left""><font size=""1"">Média da escola: " & w_media_curso & "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
  ShowHTML "        <font size=""1""><b>AP</b> - Aprovado&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
  ShowHTML "        <font size=""1""><b>RP</b> - Reprovado&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>"
  ShowHTML "</tr>" 
  ShowHTML "</table>"
  If O = "F" Then
     ShowHTML "<tr valign=""top""><td colspan=""13"" >&nbsp;"
     ShowHTML "      <tr><td align=""center"" colspan=""13"" height=""1"" bgcolor=""#000000"">"
     ShowHTML "      <tr><td align=""center"" colspan=""13"">" 
     ShowHTMl "      </td></tr>"                
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  ShowHTML "</body> "
  ShowHTML "</HTML> "
  
  Set p_matricula        = Nothing

End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina principal
REM -------------------------------------------------------------------------
Sub Main
  ' Verifica se o usuário tem lotação e localização
  Select Case Par
    Case "INICIAL"
       Inicial
    Case "EXIBEBOLETIM"
       ExibeBoletim
    Case Else
       Cabecalho
       ShowHTML "<BASE HREF=""" & Request.ServerVariables("server_name") & "/siw/"">"
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

