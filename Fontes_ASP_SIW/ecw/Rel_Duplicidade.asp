<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Geral.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Geral.asp" -->
<!-- #INCLUDE FILE="DB_Relatorio.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /Rel_Duplicidade.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Emite relatório  de duplicidade de alunos
REM Mail     : alex@sbpi.com.br
REM Criacao  : 27/08/2003, 14:20
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
w_Pagina     = "Rel_Duplicidade.asp?par="
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

  Dim p_unidade, p_tipo
  Dim p_Ordena, w_atual

  p_unidade          = uCase(Request("p_unidade"))
  p_tipo             = uCase(Request("p_tipo"))
  p_ordena           = uCase(Request("p_ordena"))
  
  If O = "L" or O = "W" Then
     DB_GetDoubStudList RS1, Session("periodo"), Session("regional"), p_tipo, p_unidade
     If p_tipo = "MATRICULA" Then
        RS1.Sort = "co_aluno, ds_aluno, ds_escola"
     Else
        RS1.Sort = "ds_aluno, ds_escola"
     End If
  End If
  
  If O = "W" Then
     HeaderWord null
     w_pag   = 1
     w_linha = 6
     If p_tipo = "MATRICULA" Then 
        CabecalhoWord w_cliente, "Duplicidade de Alunos - Matrícula", w_pag
     Else
        CabecalhoWord w_cliente, "Duplicidade de Alunos - Nome", w_pag
     End If
     ExibeParametrosRel w_cliente
     If p_unidade > "" Then
        DB_GetSchoolList RS, w_cliente
        RS.Filter = "co_unidade = '" & p_unidade & "'"
        ShowHTML "<br>Unidade de Ensino: <b>" & RS("ds_escola") & "</b>"
     End If
  Else
     Cabecalho
     ShowHTML "<HEAD>"
     If InStr("P",O) > 0 Then
        ScriptOpen "JavaScript"
        ValidateOpen "Validacao"
        If O="P" Then
           Validate "periodo", "Período", "SELECT", "1", "1", "10", "1", "1"
           Validate "regional", "Regional", "SELECT", "", "1", "10", "1", "1"
           Validate "P4", "Linhas por página", "1", "1", "1", "4", "", "0123456789"
        End If
        ValidateClose
        ScriptClose
     End If
     ScriptOpen "JavaScript"
     ShowHTML "function janelaAluno(p_matricula) {"
     ShowHTML "  window.open('" & w_Pagina & "ExibeAluno&R=" & w_Pagina & par & "&O=F&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_matricula=' + p_matricula ,'Aluno','top=10 left=30 width=750 height=500 toolbar=no scrollbars=yes status=no address=no resizable=yes');"
     ShowHTML "}"
     ShowHTML "function janelaResponsavel(p_responsavel) {"
     ShowHTML "  window.open('" & "Responsavel.asp?par=ExibeResponsavel&R=" & w_Pagina & par & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_responsavel=' + p_responsavel ,'Responsavel','top=20 left=20 width=750 height=500 toolbar=no scrollbars=yes status=no address=no resizable=yes');"
     ShowHTML "}"
     ScriptClose
     ShowHTML "</HEAD>"
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     If O = "L" or O = "W" Then
        BodyOpenClean "onLoad=document.focus();"
     Else
        BodyOpen "onLoad=document.focus();"
     End If
     If O = "L" Then
        If p_tipo = "MATRICULA" Then 
           CabecalhoRelatorio w_cliente, "Duplicidade de Alunos - Matrícula"
        Else
           CabecalhoRelatorio w_cliente, "Duplicidade de Alunos - Nome"
        End If
        ExibeParametrosRel w_cliente
        If p_unidade > "" Then
           DB_GetSchoolList RS, w_cliente
           RS.Filter = "co_unidade = '" & p_unidade & "'"
           ShowHTML "<br>Unidade de Ensino: <b>" & RS("ds_escola") & "</b>"
        End If
     Else
        ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
        ShowHTML "<HR>"
     End If
  End If

  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" or O = "W" Then
    ShowHTML "<tr><td align=""center"" colspan=3>"
    If p_tipo = "MATRICULA" Then
       ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
       ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
       ShowHTML "          <td><font size=""1""><b>Nome do aluno</font></td>"
       ShowHTML "          <td><font size=""1""><b>Nome da mãe</font></td>"
       ShowHTML "          <td><font size=""1""><b>Dt.Nasc.</font></td>"
       ShowHTML "          <td><font size=""1""><b>Sexo</font></td>"
       ShowHTML "          <td><font size=""1""><b>Dt.Matr.</font></td>"
       ShowHTML "          <td><font size=""1""><b>Unidade de Ensino</font></td>"
       ShowHTML "        </tr>"
       If RS1.EOF Then
           ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
       Else
         RS1.PageSize     = P4
         RS1.AbsolutePage = P3
         w_atual = ""
         While Not RS1.EOF and (RS1.AbsolutePage = P3 or O = "W")
           If w_atual <> RS1("co_aluno") Then
              w_atual = RS1("co_aluno")
              ShowHTML "      <tr bgcolor=""" & conTrAlternateBgColor & """><td colspan=6><font size=""2""><b>Matrícula: " & RS1("co_aluno") & "</b></td></tr>"
              DB_GetAlunoData RS2, Session("periodo"), RS1("co_aluno"), "TURMA"
              While Not RS2.EOF
                 If w_linha > 30 and O = "W" Then
                    ShowHTML "    </table>"
                    ShowHTML "  </td>"
                    ShowHTML "</tr>"
                    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td colspan=3><font size=""1""><b>Dt.Nasc.:</b> Data de Nascimento - <b>Dt.Matr.:</b> Data de Matrícula</font></td>"
                    ShowHTML "</table>"
                    ShowHTML "</center></div>"
                    ShowHTML "    <br style=""page-break-after:always"">"
                    w_linha = 6
                    w_pag   = w_pag + 1
                    CabecalhoWord w_cliente, "Duplicidade de Alunos - Matrícula", w_pag
                    ExibeParametrosRel w_cliente
                    ShowHTML "<div align=center><center>"
                    ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
                    ShowHTML "<tr><td align=""center"" colspan=3>"
                    If p_unidade > "" Then
                       DB_GetSchoolList RS, w_cliente
                       RS.Filter = "co_unidade = '" & p_unidade & "'"
                       ShowHTML "<br>Unidade de Ensino: <b>" & RS("ds_escola") & "</b>"
                    End If
                    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
                    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
                    ShowHTML "          <td><font size=""1""><b>Nome do aluno</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Nome da mãe</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Dt.Nasc.</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Sexo</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Dt.Matr.</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Unidade de Ensino</font></td>"
                    ShowHTML "        </tr>"
                 End If
                 w_cor = conTrBgColor
                 ShowHTML "      <tr bgcolor=""" & w_cor & """>"
                 ShowHTML "        <td><font size=""1"">" & lCase(RS2("ds_aluno")) & "</td>"
                 ShowHTML "        <td><font size=""1"">" & lcase(Nvl(RS2("ds_mae"),"---")) & "</td>"
                 ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(FormataDataEdicao(RS2("dt_nascimento")),"---") & "</td>"
                 ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(RS2("tp_sexo_aluno"),"---") & "</td>"
                 If IsNull(Tvl(RS2("dt_matricula"))) Then
                    ShowHTML "        <td align=""center""><font size=""1"">---</td>"
                 Else
                    ShowHTML "        <td align=""center""><font size=""1"">" & FormataDataEdicao(RS2("dt_matricula")) & "</td>"
                 End If
                 ShowHTML "        <td><font size=""1"">" & lCase(RS2("ds_escola")) & "</td>"
                 ShowHTML "      </tr>"
                 RS2.MoveNext
                 w_linha = w_linha + 1
              Wend
           End If
           RS1.MoveNext
           w_linha = w_linha + 1
         wend
       End If
    Else
       ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
       ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
       ShowHTML "          <td><font size=""1""><b>Unidade de Ensino</font></td>"
       ShowHTML "          <td><font size=""1""><b>Matrícula</font></td>"
       ShowHTML "          <td title=""Data de Matrícula""><font size=""1""><b>Dt.Matr.</font></td>"
       ShowHTML "          <td><font size=""1""><b>Turno</font></td>"
       ShowHTML "          <td><font size=""1""><b>Série</font></td>"
       ShowHTML "          <td><font size=""1""><b>Turma</font></td>"
       ShowHTML "          <td title=""Nº de Chamada""><font size=""1""><b>Ch</font></td>"
       ShowHTML "        </tr>"
       If RS1.EOF Then
           ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
       Else
         RS1.PageSize     = P4
         RS1.AbsolutePage = P3
         w_atual = ""
         While Not RS1.EOF and (RS1.AbsolutePage = P3 or O = "W")
           If w_atual <> (RS1("ds_aluno") & RS1("ds_mae") & RS1("dt_nascimento")) Then
              w_atual = RS1("ds_aluno") & RS1("ds_mae") & FormataDataEdicao(RS1("dt_nascimento"))
              ShowHTML "      <tr bgcolor=""" & conTrAlternateBgColor & """><td colspan=7><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
              ShowHTML "        <td width=""38%""><font size=""1"">Nome do aluno: <b>" & RS1("ds_aluno") & "</b></td>"
              ShowHTML "        <td width=""38%""><font size=""1"">Nome da mãe: <b>" & RS1("ds_mae") & "</b></td>"
              ShowHTML "        <td width=""17%""><font size=""1"">Dt.Nasc.: <b>" & FormataDataEdicao(RS1("dt_nascimento")) & "</b></td>"
              ShowHTML "        <td width=""7%""><font size=""1"">Sexo: <b>" & RS1("tp_sexo_aluno") & "</b></td>"
              ShowHTML "        </table>"
              DB_GetDoubleStudData RS2, Session("periodo"), RS1("ds_aluno"), RS1("ds_mae"), RS1("dt_nascimento")
              While Not RS2.EOF
                 If w_linha > 33 and O = "W" Then
                    ShowHTML "    </table>"
                    ShowHTML "  </td>"
                    ShowHTML "</tr>"
                    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td colspan=3><font size=""1""><b>Ch:</b> Nº de Chamada - <b>Dt.Nasc.:</b> Data de Nascimento - <b>Dt.Matr.:</b> Data de Matrícula</font></td>"
                    ShowHTML "</table>"
                    ShowHTML "</center></div>"
                    ShowHTML "    <br style=""page-break-after:always"">"
                    w_linha = 6
                    w_pag   = w_pag + 1
                    CabecalhoWord w_cliente, "Duplicidade de Alunos - Nome", w_pag
                    ExibeParametrosRel w_cliente
                    ShowHTML "<div align=center><center>"
                    ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
                    ShowHTML "<tr><td align=""center"" colspan=3>"
                    If p_unidade > "" Then
                       DB_GetSchoolList RS, w_cliente
                       RS.Filter = "co_unidade = '" & p_unidade & "'"
                       ShowHTML "<br>Unidade de Ensino: <b>" & RS("ds_escola") & "</b>"
                    End If
                    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
                    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
                    ShowHTML "          <td><font size=""1""><b>Unidade de Ensino</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Matrícula</font></td>"
                    ShowHTML "          <td title=""Data de Matrícula""><font size=""1""><b>Dt.Matr.</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Turno</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Série</font></td>"
                    ShowHTML "          <td><font size=""1""><b>Turma</font></td>"
                    ShowHTML "          <td title=""Nº de Chamada""><font size=""1""><b>Ch</font></td>"
                    ShowHTML "        </tr>"
                 End If
                 w_cor = conTrBgColor
                 ShowHTML "      <tr bgcolor=""" & w_cor & """>"
                 ShowHTML "        <td><font size=""1"">" & lcase(Nvl(RS2("ds_escola"),"---")) & "</td>"
                 ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(RS2("co_aluno"),"---") & "</td>"
                 If IsNull(Tvl(RS2("dt_matricula"))) Then
                    ShowHTML "        <td align=""center"" title=""Data de Matrícula""><font size=""1"">---</td>"
                 Else
                    ShowHTML "        <td align=""center"" title=""Data de Matrícula""><font size=""1"">" & FormataDataEdicao(RS2("dt_matricula")) & "</td>"
                 End If
                 ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(RS2("co_turno"),"---") & "</td>"
                 ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(RS2("sg_serie"),"---") & "</td>"
                 ShowHTML "        <td align=""center""><font size=""1"">" & RS2("co_letra_turma") & "</td>"
                 ShowHTML "        <td align=""center"" title=""Nº de Chamada""><font size=""1"">" & RS2("nu_chamada") & "</td>"
                 ShowHTML "      </tr>"
                 RS2.MoveNext
                 w_linha = w_linha + 1
              Wend
           End If
           RS1.MoveNext
           w_linha = w_linha + 1
         wend
       End If
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    If p_tipo = "MATRICULA" Then
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td colspan=3><font size=""1""><b>Dt.Nasc.:</b> Data de Nascimento - <b>Dt.Matr.:</b> Data de Matrícula</font></td>"
    Else
       ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td colspan=3><font size=""1""><b>Ch:</b> Nº de Chamada - <b>Dt.Nasc.:</b> Data de Nascimento - <b>Dt.Matr.:</b> Data de Matrícula</font></td>"
    End If
    If O = "L" Then
       ShowHTML "<tr><td align=""center"" colspan=3>"
       MontaBarra w_dir&w_pagina&par&"&R="&w_Pagina&par&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG, RS1.PageCount, P3, P4, RS1.RecordCount
       ShowHTML "</tr>"
    End If
         
  ElseIf Instr("P",O) > 0 Then
    AbreForm "Form", w_Dir&w_Pagina&par, "POST", "return(Validacao(this));", "RelDuplic",P1,P2,P3,null,TP,SG,R,"L"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Visualizar</i> para exibir a relação na tela ou sobre <i>Gerar Word</i> para gerar um arquivo no formato Word. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""70%"" border=""0"">"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    SelecaoPeriodoLetivo "Perío<u>d</u>o letivo:", "D", null, Session("periodo"), null, "periodo", null
    SelecaoRegional "<u>R</u>egional:", "R", null, Session("regional"), null, "regional", "informal = 'N'", "onChange=""document.Form.target=''; document.Form.O.value='P'; document.Form.submit();"""
    ShowHTML "          </table>"
    ShowHTML "      <tr>"
    If Session("regional") = "00" or IsNull(Tvl(Session("regional"))) Then
       SelecaoUnidadeEnsino "<u>U</u>nidade de ensino:", "U", null, p_unidade, null, "p_unidade", null, null
    Else
       SelecaoUnidadeEnsino "<u>U</u>nidade de ensino:", "U", null, p_unidade, null, "p_unidade", "co_sigre like '" & Session("regional") & "*'", null
    End IF
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Tipo de duplicidade:<br>"
    If p_Tipo="MATRICULA" Then
       ShowHTML "          <input type=""radio"" class=""sti"" name=""p_tipo"" value=""MATRICULA"" CHECKED> Matrícula<input type=""radio"" class=""sti"" name=""p_tipo"" value=""NOME""> Nome do aluno"
    Else
       ShowHTML "          <input type=""radio"" class=""sti"" name=""p_tipo"" value=""MATRICULA""> Matrícula<input type=""radio"" class=""sti"" name=""p_tipo"" value=""NOME"" CHECKED> Nome do aluno"
    End If
    ShowHTML "          </td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""STI"" type=""text"" name=""P4"" size=""4"" maxlength=""4"" value=""" & P4 & """></td>"
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

  Set p_unidade          = Nothing
  Set p_tipo             = Nothing
  Set p_ordena           = Nothing

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

