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
REM  /Rel_Aluno.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Emite lista de alunos
REM Mail     : alex@sbpi.com.br
REM Criacao  : 29/08/2003, 08:48
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
'If Session("LogOn") <> "Sim" Then
'   EncerraSessao
'End If

' Declaração de variáveis
Dim dbms, sp, RS, RS1, RS2, RS3
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
w_Pagina     = "Rel_Aluno.asp?par="
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

Set w_pag           = Nothing
Set w_linha         = Nothing
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

  Dim p_15, p_16, p_17
  Dim p_1, p_2, p_3, p_4, p_5, p_6
  Dim p_7, p_8, p_9, p_10, p_11, p_12, p_13, p_14
  Dim p_Ordena, w_regional, w_atual
  Dim w_tot1, w_tot2

  ' Esta forma de identificação dos parâmetros é necessária pois o HTML tem limite
  ' de 256 bytes no método GET, usado na barra de navegação
  p_1       = uCase(Request("p_1")) ' Modalidade
  p_2       = uCase(Request("p_2")) ' Turno
  p_3       = uCase(Request("p_3")) ' Série
  p_4       = uCase(Request("p_4")) ' Turma
  p_5       = uCase(Request("p_5")) ' Situação acadêmica do aluno
  p_6       = uCase(Request("p_6")) ' Movimentação do aluno
  p_7       = uCase(Request("p_7")) ' Sexo
  p_8       = uCase(Request("p_8")) ' Idade inicial
  p_9       = uCase(Request("p_9")) ' Idade final
  p_10      = uCase(Request("p_10"))' Origem da escola
  p_11      = uCase(Request("p_11"))' Data de matrícula inicial
  p_12      = uCase(Request("p_12"))' Data de matrícula final 
  p_13      = uCase(Request("p_13"))' Data de nascimento inicial
  p_14      = uCase(Request("p_14"))' Data de nascimento final
  p_15      = uCase(Request("p_15"))' Unidade de ensino
  p_16      = uCase(Request("p_16"))' Tipo do relatório (apenas totais ou alunos/totais)
  p_17      = uCase(Request("p_17"))' Componente curricular
  p_ordena  = uCase(Request("p_ordena"))
  
  If O = "L" or O = "W" Then
     DB_GetStudentRel RS1, Session("periodo"), Session("regional"), p_17, null, null, _
        p_15, p_3, p_4, p_1, p_2, p_10, p_5, p_6, p_7, p_8, p_9, p_11, p_12, p_13, p_14
        'If p_8 > ""     Then w_filter = w_filter & " and idade >= " & p_8 & " and idade <= " & p_9                   End If
     RS1.Sort = "regional, co_unidade, ds_aluno, tp_sexo_aluno, dt_nascimento"
  End If
  
  If O = "W" Then
     HeaderWord null
     w_pag   = 1
     w_linha = 8
     If p_16 = "S" Then
        CabecalhoWord w_cliente, "Quantitativo de Alunos", w_pag
     Else
        CabecalhoWord w_cliente, "Lista de Alunos", w_pag
     End If
     ExibeParametrosRel w_cliente
  Else
     Cabecalho
     ShowHTML "<HEAD>"
     If InStr("P",O) > 0 Then
        ScriptOpen "JavaScript"
        CheckBranco
        FormataData
        ValidateOpen "Validacao"
        If O="P" Then
           Validate "periodo", "Período", "SELECT", "1", "1", "10", "1", "1"
           Validate "regional", "Regional", "SELECT", "", "1", "10", "1", "1"
           ShowHTML "  if (theForm.regional[theForm.regional.selectedIndex].value == '00' && theForm.p_15.selectedIndex == 0 && theForm.p_16[1].checked) { "
           ShowHTML "     alert('Para toda a rede de ensino não é possível listar os alunos. Escolha uma regional ou marque \""Apenas totais\"" no campo \""Exibir\""');"
           ShowHTML "     theForm.regional.focus();"
           ShowHTML "     return false;"
           ShowHTML "  }"
           Validate "p_8", "Idade inicial", "", "", "1", "2", "", "0123456789"
           Validate "p_9", "Idade final", "", "", "1", "2", "", "0123456789"
           ShowHTML "  if ((theForm.p_8.value == '' && theForm.p_9.value != '') || (theForm.p_8.value != '' && theForm.p_9.value == '')) { "
           ShowHTML "     alert('Informe as idades inicial e final ou nenhuma delas!');"
           ShowHTML "     theForm.p_8.focus();"
           ShowHTML "     return false;"
           ShowHTML "  }"
           ShowHTML "  if (theForm.p_8.value > theForm.p_9.value) { "
           ShowHTML "     alert('Idade final deve ser menor que idade inicial!');"
           ShowHTML "     theForm.p_8.focus();"
           ShowHTML "     return false;"
           ShowHTML "  }"
           Validate "p_11", "Matrícula - data inicial", "DATA", "", "10", "10", "", "0123456789/"
           Validate "p_12", "Matrícula - data final", "DATA", "", "10", "10", "", "0123456789/"
           ShowHTML "  if ((theForm.p_11.value == '' && theForm.p_12.value != '') || (theForm.p_11.value != '' && theForm.p_12.value == '')) { "
           ShowHTML "     alert('Informe as datas de matrícula inicial e final ou nenhuma delas!');"
           ShowHTML "     theForm.p_11.focus();"
           ShowHTML "     return false;"
           ShowHTML "  }"
           CompData "p_11", "Matrícula - data inicial", "<=", "p_12", "Matrícula - data final"
           Validate "p_13", "Nascimento - data inicial", "DATA", "", "10", "10", "", "0123456789/"
           Validate "p_14", "Nascimento - data final", "DATA", "", "10", "10", "", "0123456789/"
           ShowHTML "  if ((theForm.p_13.value == '' && theForm.p_14.value != '') || (theForm.p_13.value != '' && theForm.p_14.value == '')) { "
           ShowHTML "     alert('Informe as datas de nascimento inicial e final ou nenhuma delas!');"
           ShowHTML "     theForm.p_13.focus();"
           ShowHTML "     return false;"
           ShowHTML "  }"
           CompData "p_13", "Nascimento - data inicial", "<=", "p_14", "Nascimento - data final"
           CompData "p_11", "Data Matrícula", ">", "p_13", "Data Nascimento"
           Validate "P4", "Linhas por página", "1", "1", "1", "4", "", "0123456789"
        End If
        ValidateClose
        ScriptClose
     End If
     If O = "L" Then
        ScriptOpen "JavaScript"
        ShowHTML "function janelaAluno(p_matricula) {"
        ShowHTML "  window.open('Aluno.asp?par=ExibeAluno&R=" & w_Pagina & par & "&O=F&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_matricula=' + p_matricula ,'Aluno','top=10 left=30 width=750 height=500 toolbar=no scrollbars=yes status=no address=no resizable=yes');"
        ShowHTML "}"
        ShowHTML "function janelaResponsavel(p_responsavel) {"
        ShowHTML "  window.open('" & "Responsavel.asp?par=ExibeResponsavel&R=" & w_Pagina & par & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_responsavel=' + p_responsavel ,'Responsavel','top=20 left=20 width=750 height=500 toolbar=no scrollbars=yes status=no address=no resizable=yes');"
        ShowHTML "}"
        ScriptClose
     End If
     ShowHTML "</HEAD>"
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     If O = "L" or O = "W" Then
        BodyOpenClean "onLoad=document.focus();"
     Else
        BodyOpen "onLoad=document.focus();"
     End If
     If O = "L" Then
        If p_16 = "S" Then
           CabecalhoRelatorio w_cliente, "Quantitativo de Alunos"
        Else
           CabecalhoRelatorio w_cliente, "Lista de Alunos"
        End If
        ExibeParametrosRel w_cliente
     Else
        ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
        ShowHTML "<HR>"
     End If
  End If

  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" or O = "W" Then
    w_filter = ""
    If p_17 > "" Then 
       DB_GetDisciplineTypeData RS, p_17
       w_filter = w_filter & " [Comp.Cur.: <b>" & RS("ds_tipo_disciplina") & "</b>]&nbsp;"
    End If
    If p_1 > "" Then 
       DB_GetCourseTypeData RS, p_1
       w_filter = "[Mod.: <b>" & RS("sg_tipo_curso") & "</b>]&nbsp;"
    End If
    If p_3 > ""       Then w_filter = w_filter & " [Série: <b>" & p_3  & "</b>]&nbsp;"                        End If
    If p_4 > "" Then 
       DB_GetTurmaList RS, Session("periodo"), p_15
       RS.Filter = "co_turma = " & p_4
       w_filter = w_filter & " [Turma: <b>" & RS("co_letra_turma") & "</b>]&nbsp;"
    End If
    If p_2 > ""       Then w_filter = w_filter & " [Turno: <b>" & p_2 & "</b>]&nbsp;"                         End If
    If p_10 > "" Then 
       DB_GetSchoolOriginData RS, p_10
       w_filter = w_filter & " [Origem: <b>" & RS("ds_origem_escola") & "</b>]&nbsp;"
    End If
    If p_5 > ""       Then w_filter = w_filter & " [Situação: <b>" & p_5 & "</b>]&nbsp;"                      End If
    If p_6 > ""       Then w_filter = w_filter & " [Mov: <b>" & p_6 & "</b>]&nbsp;"                           End If
    If p_7 > ""       Then w_filter = w_filter & " [Sexo: <b>" & p_7 & "</b>]&nbsp;"                          End If
    If p_8 > ""       Then w_filter = w_filter & " [Idade: <b>" & p_8 & "</b>-<b>" & p_9 & "</b>]&nbsp;"      End If
    If p_11 > ""      Then w_filter = w_filter & " [Dt.Matr: <b>" & p_11 & "</b>-<b>" & p_12 & "</b>]&nbsp;"  End If
    If p_13 > ""      Then w_filter = w_filter & " [Dt.Nasc: <b>" & p_13 & "</b>-<b>" & p_14 & "</b>]&nbsp;"  End If
    If w_filter > ""  Then ShowHTML "<tr><td colspan=14>&nbsp;Filtro:&nbsp;<font size=1>" & w_filter & "</font><BR>" End If
    ShowHTML "<tr><td align=""center"" colspan=14>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    If RS1.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=14 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      RS1.PageSize     = P4
      RS1.AbsolutePage = P3
      w_atual    = ""
      w_regional = "a"
      w_tot1     = 0
      w_tot2     = 0
      While Not RS1.EOF and (RS1.AbsolutePage = P3 or O = "W" or p_16 = "S")
        If w_regional <> RS1("regional") or w_atual <> RS1("co_unidade") Then
           If w_atual > "" Then
              If p_16 = "S" or O = "W" Then ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=14><font size=""1""><b>Total da unidade: " & FormatNumber(w_tot1,0) & "</b></td></tr>" End If
              w_tot2 = w_tot2 + w_tot1
              w_tot1 = 0
              If w_regional <> RS1("regional") Then
                 If p_16 = "S" or O = "W" Then 
                    ShowHTML "      <tr bgcolor=""" & conTrTotalBgcolor & """><td colspan=14><font size=""2""><b>TOTAL DA REGIONAL: " & FormatNumber(w_tot2,0) & "</b></td></tr>"
                    ShowHTML "      <tr><td colspan=14><font size=""2""><b>&nbsp;</b></td></tr>"
                 End If
                 w_tot2 = 0
                 w_linha = w_linha + 2
              End If
           End If
           If w_regional <> RS1("regional") Then
              ShowHTML "      <tr bgcolor=""" & conTrTotalBgcolor & """><td colspan=14><font size=""2""><b>REGIONAL DE ENSINO: " & ucase(RS1("ds_gre")) & "</b></td></tr>"
           End If
           ShowHTML "      <tr bgcolor=""" & conTrAlternateBgColor & """><td colspan=14><font size=""2""><b>Unidade: " & RS1("ds_escola") & "</b></td></tr>"
           w_regional = RS1("regional")
           w_atual    = RS1("co_unidade")
           w_linha = w_linha + 2
           ' Imprime o cabeçalho das linhas-detalhe apenas se for listagem analítica
           If p_16 = "N" Then
              ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
              ShowHTML "          <td><font size=""1""><b>Ch</font></td>"
              ShowHTML "          <td><font size=""1""><b>Matrícula</font></td>"
              ShowHTML "          <td><font size=""1""><b>Nome do aluno</font></td>"
              If p_7 = ""         Then ShowHTML "          <td><font size=""1""><b>Sexo</font></td>"              End If
              ShowHTML "          <td><font size=""1""><b>Dt.Nasc.</font></td>"
              ShowHTML "          <td><font size=""1""><b>Idade</font></td>"
              If p_1 = ""         Then ShowHTML "          <td><font size=""1""><b>Mod.</font></td>"              End If
              If p_3 = ""         Then ShowHTML "          <td><font size=""1""><b>Série</font></td>"             End If
              If p_4 = ""         Then ShowHTML "          <td><font size=""1""><b>Turma</font></td>"             End If
              If p_2 = ""         Then ShowHTML "          <td><font size=""1""><b>Turno</font></td>"             End If
              If p_6 = ""         Then ShowHTML "          <td><font size=""1""><b>Mov.</font></td>"              End If
              If p_5 = ""         Then ShowHTML "          <td><font size=""1""><b>Sit.Acadêmica</font></td>"     End If
              If p_10 = ""        Then ShowHTML "          <td><font size=""1""><b>Origem do Aluno</font></td>"   End If
              ShowHTML "          <td><font size=""1""><b>Dt.Matr.</font></td>"
              ShowHTML "        </tr>"
           End If
        End If
        If w_linha > 30 and O = "W" Then
           ShowHTML "    </table>"
           ShowHTML "  </td>"
           ShowHTML "</tr>"
           If p_16 = "N" Then ShowHTML "<tr bgcolor=""" & conTrAlternateBgColor & """><td colspan=14><font size=""1""><b>Ch:</b> Nº de Chamada - <b>Dt.Nasc.:</b> Data de Nascimento - <b>Mod.:</b> Modalidade de Ensino - <b>Mov.:</b> Movimentação do aluno - <b>Dt.Matr.:</b> Data de Matrícula</font></td>" End If
           ShowHTML "</table>"
           ShowHTML "</center></div>"
           ShowHTML "    <br style=""page-break-after:always"">"
           w_linha = 6
           w_pag   = w_pag + 1
           If p_16 = "S" Then
              CabecalhoWord w_cliente, "Quantitativo de Alunos", w_pag
           Else
              CabecalhoWord w_cliente, "Lista de Alunos", w_pag
           End If
           ExibeParametrosRel w_cliente
           ShowHTML "<div align=center><center>"
           ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
           If p_15 > "" Then
              ShowHTML "      <tr bgcolor=""" & conTrTotalBgcolor & """><td colspan=14><font size=""2""><b>REGIONAL DE ENSINO: " & ucase(RS1("ds_gre")) & "</b></td></tr>"
              ShowHTML "      <tr bgcolor=""" & conTrAlternateBgColor & """><td colspan=14><font size=""2""><b>Unidade: " & RS1("ds_escola") & "</b></td></tr>"
           End If
           If w_filter > ""  Then ShowHTML "<tr><td colspan=14>&nbsp;Filtro:&nbsp;<font size=1>" & w_filter & "</font><BR>" End If
           ShowHTML "<tr><td align=""center"" colspan=14>"
           ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
           ' Imprime o cabeçalho das linhas-detalhe apenas se for listagem analítica
           If p_16 = "N" Then
              ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
              ShowHTML "          <td><font size=""1""><b>Ch</font></td>"
              ShowHTML "          <td><font size=""1""><b>Matrícula</font></td>"
              ShowHTML "          <td><font size=""1""><b>Nome do aluno</font></td>"
              If p_7 = ""         Then ShowHTML "          <td><font size=""1""><b>Sexo</font></td>"              End If
              ShowHTML "          <td><font size=""1""><b>Dt.Nasc.</font></td>"
              ShowHTML "          <td><font size=""1""><b>Idade</font></td>"
              If p_1 = ""         Then ShowHTML "          <td><font size=""1""><b>Mod.</font></td>"              End If
              If p_3 = ""         Then ShowHTML "          <td><font size=""1""><b>Série</font></td>"             End If
              If p_4 = ""         Then ShowHTML "          <td><font size=""1""><b>Turma</font></td>"             End If
              If p_2 = ""         Then ShowHTML "          <td><font size=""1""><b>Turno</font></td>"             End If
              If p_6 = ""         Then ShowHTML "          <td><font size=""1""><b>Mov.</font></td>"              End If
              If p_5 = ""         Then ShowHTML "          <td><font size=""1""><b>Sit.Acadêmica</font></td>"     End If
              If p_10 = ""        Then ShowHTML "          <td><font size=""1""><b>Origem do Aluno</font></td>"   End If
              ShowHTML "          <td><font size=""1""><b>Dt.Matr.</font></td>"
              ShowHTML "        </tr>"
           End If
        End If
        w_cor = conTrBgColor
        If p_16 = "N" Then
            ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
            ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(RS1("nu_chamada"),"---") & "</td>"
            ShowHTML "        <td nowrap align=""center""><font size=""1"">" & Nvl(RS1("co_aluno"),"---") & "</td>"
            ShowHTML "        <td><font size=""1"">" & lCase(RS1("ds_aluno")) & "</td>"
            If p_7 = ""    Then ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(RS1("tp_sexo_aluno"),"---") & "</td>"  End If
            ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(FormataDataEdicao(RS1("dt_nascimento")),"---") & "</td>"
            ShowHTML "        <td align=""center""><font size=""1"">" & lcase(Nvl(RS1("idade"),"---")) & "</td>"
            If p_1 = ""    Then ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(RS1("sg_tipo_curso"),"---") & "</td>"  End If
            If p_3 = ""    Then ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(RS1("sg_serie"),"---") & "</td>"       End If
            If p_4 = ""    Then ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(RS1("co_letra_turma"),"---") & "</td>" End If
            If p_2 = ""    Then ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(RS1("co_turno"),"---") & "</td>"       End If
            If p_6 = ""    Then ShowHTML "        <td><font size=""1"">" & lcase(Nvl(RS1("st_movimentacao"),"---")) & "</td>"          End If
            If p_5 = ""    Then ShowHTML "        <td><font size=""1"">" & lcase(Nvl(RS1("ds_situacao_aluno"),"---")) & "</td>"        End If
            If p_10 = ""   Then ShowHTML "        <td><font size=""1"">" & lcase(Nvl(RS1("ds_origem_escola"),"---")) & "</td>"         End If
            If IsNull(Tvl(RS1("dt_matricula"))) Then
               ShowHTML "        <td align=""center""><font size=""1"">---</td>"
            Else
               ShowHTML "        <td align=""center""><font size=""1"">" & FormataDataEdicao(RS1("dt_matricula")) & "</td>"
            End If
            ShowHTML "      </tr>"
            w_linha = w_linha + 1
        End If
        RS1.MoveNext
        w_tot1  = w_tot1 + 1
      wend
    End If
    If w_atual > "" and (p_16 = "S" or O = "W") Then
       ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=14><font size=""1""><b>Total da unidade: " & FormatNumber(w_tot1,0) & "</b></td></tr>"
       w_tot2 = w_tot2 + w_tot1
       ShowHTML "      <tr bgcolor=""" & conTrTotalBgcolor & """><td colspan=14><font size=""2""><b>TOTAL DA REGIONAL: " & FormatNumber(w_tot2,0) & "</b></td></tr>"
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    If p_16 = "N" Then ShowHTML "<tr bgcolor=""" & conTrAlternateBgColor & """><td colspan=14><font size=""1""><b>Ch:</b> Nº de Chamada - <b>Dt.Nasc.:</b> Data de Nascimento - <b>Mod.:</b> Modalidade de Ensino - <b>Mov.:</b> Movimentação do aluno - <b>Dt.Matr.:</b> Data de Matrícula - <br><b>Sit.Acadêmica:</b> Situação Acadêmica</font></td>" End If
    If O = "L" and p_16 = "N" Then
       ShowHTML "<tr><td align=""center"" colspan=14>"
       MontaBarra w_dir&w_pagina&par&"&O="&O&"&P1="&P1&"&P2="&P2&"&SG="&SG, RS1.PageCount, P3, P4, RS1.RecordCount
       ShowHTML "</tr>"
    End If
  ElseIf Instr("P",O) > 0 Then
    AbreForm "Form", w_Dir&w_Pagina&par, "POST", "return(Validacao(this));", "RelAluno",P1,P2,P3,null,TP,SG,R,"L"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Visualizar</i> para exibir a relação na tela ou sobre <i>Gerar Word</i> para gerar um arquivo no formato Word. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""70%"" border=""0"">"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    SelecaoPeriodoLetivo "Perío<u>d</u>o letivo:", "D", null, Session("periodo"), null, "periodo", null
    SelecaoRegional "<u>R</u>egional:", "R", null, Session("regional"), null, "regional", "informal = 'N'", "onChange=""document.Form.target=''; document.Form.O.value='P'; document.Form.submit();"""
    ShowHTML "          </table>"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    If Session("regional") = "00" or IsNull(Tvl(Session("regional"))) Then
       SelecaoUnidadeEnsino "<u>U</u>nidade de ensino:", "U", null, p_15, null, "p_15", null, "onChange=""document.Form.target=''; document.Form.O.value='P'; document.Form.submit();"""
    Else
       SelecaoUnidadeEnsino "<u>U</u>nidade de ensino:", "U", null, p_15, null, "p_15", "co_sigre like '" & Session("regional") & "*'", "onChange=""document.Form.target=''; document.Form.O.value='P'; document.Form.submit();"""
    End IF
    SelecaoModEnsino "<u>M</u>odalidade de ensino:", "M", null, p_1, null, "p_1", null, "onChange=""document.Form.target=''; document.Form.O.value='P'; document.Form.submit();"""
    ShowHTML "          </table>"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    If p_1 > "" Then
       SelecaoSerie "<u>S</u>érie:", "S", null, p_3, null, "p_3", "co_tipo_curso = " & Nvl(p_1,0), "onChange=""document.Form.target=''; document.Form.O.value='P'; document.Form.submit();"""
    Else
       SelecaoSerie "<u>S</u>érie:", "S", null, p_3, null, "p_3", null, "onChange=""document.Form.target=''; document.Form.O.value='P'; document.Form.submit();"""
    End If
    w_filter = ""
    If p_1 > "" Then w_filter = w_filter & " and co_tipo_curso = " & p_1 End If
    If p_3 > ""      Then w_filter = w_filter & " and sg_serie = '" & p_3 & "'"    End If
    If w_filter > "" Then w_filter = mid(w_filter,6,200) Else w_filter = null End If
    SelecaoTurma "T<u>u</u>rma:", "U", null, p_4, Nvl(p_15,0), "p_4", w_filter, null
    ShowHTML "          </table>"
    ShowHTML "      </tr>"
    ShowHTML "      <tr>"
    SelecaoDisciplina "<u>C</u>omponente curricular:", "C", null, p_17, null, "p_17", null, null
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    SelecaoTurno "<u>T</u>urno:", "T", null, p_2, null, "p_2", null, null
    SelecaoEscolaOrigem "<u>O</u>rigem do aluno:", "O", null, p_10, null, "p_10", null, null
    ShowHTML "          </table>"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    SelecaoSitAcademica "S<u>i</u>tuação acadêmica:", "I", null, p_5, null, "p_5", null, null
    SelecaoMovAluno "Mo<u>v</u>imentação do aluno:", "V", null, p_6, null, "p_6", null, null
    SelecaoSexo "Se<u>x</u>o:", "X", null, p_7, null, "p_7", null, null
    ShowHTML "          </table>"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Faixa etária: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=""text"" class=""sti"" size=2 maxlength=2 name=""p_8"" value=""" & p_8 & """> a <input type=""text"" class=""sti"" size=2 maxlength=2 name=""p_9"" value=""" & p_9 & """>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Data de matrícula: &nbsp;&nbsp;&nbsp;&nbsp;<input type=""text"" class=""sti"" size=10 maxlength=10 name=""p_11"" value=""" & p_11 & """ onKeyDown=""FormataData(this,event);""> a <input type=""text"" class=""sti"" size=10 maxlength=10 name=""p_12"" value=""" & p_12 & """ onKeyDown=""FormataData(this,event);"">"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Data de nascimento: <input type=""text"" class=""sti"" size=10 maxlength=10 name=""p_13"" value=""" & p_13 & """ onKeyDown=""FormataData(this,event);""> a <input type=""text"" class=""sti"" size=10 maxlength=10 name=""p_14"" value=""" & p_14 & """ onKeyDown=""FormataData(this,event);"">"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Exibir:</b><br>"
    If p_16 = "N" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_16"" value=""S""> Apenas totais <input " & w_Disabled & " type=""radio"" name=""p_16"" value=""N"" checked> Totais e detalhes "
    Else
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_16"" value=""S"" checked> Apenas totais <input " & w_Disabled & " type=""radio"" name=""p_16"" value=""N""> Totais e detalhes "
    End If
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""STI"" type=""text"" name=""P4"" size=""4"" maxlength=""4"" value=""" & P4 & """></td>"
    ShowHTML "          </table>"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Visualizar"" onClick=""document.Form.O.value='L'"">"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Gerar Word"" onClick=""document.Form.O.value='W'"">"
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

  Set w_regional        = Nothing 
  Set w_tot1            = Nothing 
  Set w_tot2            = Nothing 
  Set p_1               = Nothing 
  Set p_2               = Nothing 
  Set p_3               = Nothing 
  Set p_4               = Nothing 
  Set p_5               = Nothing 
  Set p_6               = Nothing
  Set p_7               = Nothing 
  Set p_8               = Nothing 
  Set p_9               = Nothing 
  Set p_10              = Nothing 
  Set p_11              = Nothing 
  Set p_12              = Nothing 
  Set p_13              = Nothing 
  Set p_14              = Nothing
  Set p_15              = Nothing
  Set p_16              = Nothing
  Set p_17              = Nothing
  Set p_ordena          = Nothing

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

