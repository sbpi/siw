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
<!-- #INCLUDE FILE="DB_Relatorio.asp" -->
<!-- #INCLUDE FILE="DB_Ambiente.asp" -->
<!-- #INCLUDE FILE="DB_Tipo_Sala.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /Rel_Turma.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Emite lista de salas e turmas
REM Mail     : alex@sbpi.com.br
REM Criacao  : 04/09/2003, 09:00
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
Dim dbms, sp, RS, RS1, RS2, RS3, RS_Temp
Dim P1, P2, P3, P4, TP, SG, w_ano
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
w_Pagina     = "Rel_Turma.asp?par="
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

  Dim p_unidade
  Dim p_modalidade, p_turno, p_serie, p_turma
  Dim p_Ordena, w_regional, w_atual, p_tipo
  Dim w_tot1, w_tot2, w_total_unidade, w_total_sala, w_unidade_atual

  p_modalidade       = trim(uCase(Request("p_modalidade"))) 
  p_turno            = trim(uCase(Request("p_turno"))) 
  p_serie            = trim(uCase(Request("p_serie"))) 
  p_turma            = trim(uCase(Request("p_turma")))
  p_unidade          = trim(uCase(Request("p_unidade")))
  p_ordena           = trim(uCase(Request("p_ordena")))
  p_tipo             = trim(uCase(Request("p_tipo")))
  
  If O = "L" or O = "W" Then
     If Session("regional") = "" or Session("regional") = "00" Then
        DB_GetRoomClassList RS1, Session("periodo"), null, p_unidade, p_modalidade, p_turno, p_serie, p_turma, null, null
     Else
        DB_GetRoomClassList RS1, Session("periodo"), Session("regional"), p_unidade, p_modalidade, p_turno, p_serie, p_turma, null, null
     End If
     'If p_unidade & p_modalidade & p_turno & p_serie & p_turma > "" Then
     '   w_filter = ""
     '   If p_unidade    > ""       Then w_filter = w_filter & " and co_unidade      = '" & p_unidade    & "' "    End If
     '   If p_modalidade > ""       Then w_filter = w_filter & " and co_tipo_curso   = '" & p_modalidade & "' "    End If
     '   If p_turno      > ""       Then w_filter = w_filter & " and co_turno        = '" & p_turno      & "' "    End If
     '   If p_serie      > ""       Then w_filter = w_filter & " and sg_serie        = '" & p_serie      & "' "    End If
     '   If p_turma      > ""       Then w_filter = w_filter & " and co_turma        = '" & p_turma      & "' "    End If
     '   RS1.Filter = Mid(w_filter,6,255)
     'End If
     If p_ordena > "" Then 
        RS1.sort = "ds_gre, co_unidade, " & p_ordena
     Else
        RS1.Sort = "ds_gre, co_unidade, co_turno"
     End If
  End If
  
  If O = "W" Then
     HeaderWord null
     w_pag   = 1
     w_linha = 6
     If p_Tipo = "S" Then
        CabecalhoWord w_cliente, "Quantitativo de turmas", w_pag
     Else
        CabecalhoWord w_cliente, "Lista de turmas", w_pag
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
           Validate "P4", "Linhas por página", "1", "1", "1", "4", "", "0123456789"
        End If
        ValidateClose
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
        If p_Tipo = "S" Then
           CabecalhoRelatorio w_cliente, "Quantitativo de turmas"
        Else
           CabecalhoRelatorio w_cliente, "Lista de turmas"
        End If
        ExibeParametrosRel w_cliente
        ShowHTML "<BR>"
     Else
        ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
        ShowHTML "<HR>"
     End If
  End If
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" or O = "W" Then
    ShowHTML "<tr><td align=""center"" colspan=12>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    If RS1.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=12 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      RS1.PageSize     = P4
      RS1.AbsolutePage = P3
      w_atual    = ""
      w_regional = "a"
      w_tot1     = 0
      w_tot2     = 0
      While Not RS1.EOF and (RS1.AbsolutePage = P3 or O = "W" or p_tipo = "S")
        If w_regional <> RS1("regional") or w_atual <> RS1("co_unidade") Then
           If w_atual > "" Then
              If p_tipo = "S" or O = "W" Then ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=12><font size=""1""><b>Total da unidade: " & FormatNumber(w_tot1,0) & "</b></td></tr>" End If
              w_tot2 = w_tot2 + w_tot1
              w_tot1 = 0
              w_linha = w_linha + 1
              If w_regional <> RS1("regional") Then
                 If p_tipo = "S" or O = "W" Then 
                    ShowHTML "      <tr bgcolor=""" & conTrTotalBgcolor & """><td colspan=12><font size=""2""><b>TOTAL DA REGIONAL: " & FormatNumber(w_tot2,0) & "</b></td></tr>"
                    ShowHTML "      <tr><td colspan=12><font size=""2""><b>&nbsp;</b></td></tr>"
                    w_linha = w_linha + 1
                 End If
                 w_tot2 = 0
              End If
           End If
           If w_regional <> RS1("regional") Then
              ShowHTML "      <tr bgcolor=""" & conTrTotalBgcolor & """><td colspan=12><font size=""2""><b>REGIONAL DE ENSINO: " & ucase(RS1("ds_gre")) & "</b></td></tr>"
           End If
           ShowHTML "      <tr bgcolor=""" & conTrAlternateBgColor & """><td colspan=12><font size=""2""><b>Unidade: " & RS1("ds_escola") & "</b></td></tr>"
           w_regional = RS1("regional")
           w_atual    = RS1("co_unidade")
           If p_tipo = "N" Then
                 ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
                 ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
                 ShowHTML "          <td><font size=""1""><b>Turno</font></td>"
                 ShowHTML "          <td><font size=""1""><b>Modalidade de ensino</font></td>"
                 ShowHTML "          <td><font size=""1""><b>Série</font></td>"             
                 ShowHTML "          <td><font size=""1""><b>Turma</font></td>"             
                 ShowHTML "          <td><font size=""1""><b>Bloco/Sala</font></td>"                                               
                 ShowHTML "          <td><font size=""1""><b>Definitiva</font></td>"
                 ShowHTML "          <td><font size=""1""><b>Descrição</font></td>"              
                 ShowHTML "          <td><font size=""1""><b>Total de alunos</font></td>"              
                 ShowHTML "          <td><font size=""1""><b>Máx. de alunos</font></td>"      
                 ShowHTML "          <td><font size=""1""><b>Abreviatura</font></td>"
                 ShowHTML "        </tr>"
                 ShowHTML "        </tr>"
           End If
       End If
           If w_linha > 30 and O = "W" Then
              ShowHTML "    </table>"
              ShowHTML "  </td>"
              ShowHTML "</tr>"
              ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td colspan=12><font size=""1""><b>Dt.Receb.:</b> Data de Recebimento - <b>Dt.Proces.:</b> Data de Processamento</font></td>"
              ShowHTML "</table>"
              ShowHTML "</center></div>"
              ShowHTML "    <br style=""page-break-after:always"">"
              w_linha = 6
              w_pag   = w_pag + 1
              If p_Tipo = "S" Then
                 CabecalhoWord w_cliente, "Quantitativo de Turmas", w_pag
              Else
                 CabecalhoWord w_cliente, "Lista de Turmas", w_pag
              End If
              ExibeParametrosRel w_cliente
              ShowHTML "<div align=center><center>"
              ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
              ShowHTML "<tr><td align=""center"" colspan=12>"
              If p_unidade > "" Then
                 ShowHTML "<br>Unidade de Ensino: <b>" & RS1("ds_escola") & "</b>"
              End If
              If p_tipo = "N" Then
                 ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
                 ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
                 ShowHTML "          <td><font size=""1""><b>Turno</font></td>"
                 ShowHTML "          <td><font size=""1""><b>Modalidade de ensino</font></td>"
                 ShowHTML "          <td><font size=""1""><b>Série</font></td>"             
                 ShowHTML "          <td><font size=""1""><b>Turma</font></td>"             
                 ShowHTML "          <td><font size=""1""><b>Bloco/Sala</font></td>"                                               
                 ShowHTML "          <td><font size=""1""><b>Definitiva</font></td>"
                 ShowHTML "          <td><font size=""1""><b>Descrição</font></td>"              
                 ShowHTML "          <td><font size=""1""><b>Total de alunos</font></td>"              
                 ShowHTML "          <td><font size=""1""><b>Máx. de alunos</font></td>"      
                 ShowHTML "          <td><font size=""1""><b>Abreviatura</font></td>"
                 ShowHTML "        </tr>"
                ShowHTML "        </tr>"
              End If
           End If
           w_cor = conTrBgColor
           If p_tipo = "N" Then
              ShowHTML "      <tr bgcolor=""" & w_cor & """>"
              ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(RS1("co_turno"),"---") & "</td>"
              ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(RS1("ds_tipo_curso"),"---") & "</td>"
              ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(RS1("sg_serie"),"---") & "</td>"
              ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(RS1("co_letra_turma"),"---") & "</td>"
              ShowHTML "        <td align=""center""><font size=""1"">"& Nvl(RS1("co_bloco"),"---")&"/"& Nvl(RS1("co_sala"),"---") & "</td>"              
              ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(RS1("st_turma_definitiv"),"---") & "</td>"
              ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(RS1("ds_turma"),"---") & "</td>"
              ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(RS1("nu_alunos_sala"),"---") & "</td>"
              ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(RS1("aluno_matric"),"---") & "</td>"
              ShowHTML "        <td align=""center""><font size=""1"">"&  trim(Nvl(RS1("co_letra_turma"),"---"))&"</td>"
              ShowHTML "      </tr>"
              w_linha = w_linha + 1
           End If
           RS1.MoveNext
           w_tot1  = w_tot1 + 1
        wend
    End If
    If (w_atual > "" and p_tipo = "S") or (w_atual > "" and O = "W")  Then
       ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=12><font size=""1""><b>Total da unidade: " & FormatNumber(w_tot1,0) & "</b></td></tr>"
       w_tot2 = w_tot2 + w_tot1
       ShowHTML "      <tr bgcolor=""" & conTrTotalBgcolor & """><td colspan=12><font size=""2""><b>TOTAL DA REGIONAL: " & FormatNumber(w_tot2,0) & "</b></td></tr>"
       If Session("regional") = "" or Session("regional") = "00" Then
          ShowHTML "      <tr bgcolor=""" & conTrBgcolor & """><td colspan=12><font size=""2""><b>TOTAL DE TURMAS: " & FormatNumber(RS1.RecordCount,0) & "</b></td></tr>"
       End If
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    If O = "L" and p_tipo = "N" Then
       ShowHTML "<tr><td align=""center"" colspan=12>"
       MontaBarra w_dir&w_pagina&par&"&R="&w_Pagina&par&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG, RS1.PageCount, P3, P4, RS1.RecordCount
       ShowHTML "</tr>"
    End If
         
  ElseIf Instr("P",O) > 0 Then
    AbreForm "Form", w_Dir&w_Pagina&par, "POST", "return(Validacao(this));", "RelSalaTurma",P1,P2,P3,null,TP,SG,R,"L"
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
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    SelecaoTurno "<u>T</u>urno:", "T", null, p_turno, null, "p_turno", null, null
    ShowHTML "          </table>"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY=""O"" " & w_Disabled & " class=""STS"" name=""p_ordena"" size=""1"">"
    If p_Ordena="DS_TIPO_CURSO" Then
       ShowHTML "          <option value="""">Turno<option value=""DS_TIPO_CURSO"" SELECTED>Modalidade de ensino"
    Else
       ShowHTML "           <option value="""" SELECTED>Turno<option value=""DS_TIPO_CURSO"">Modalidade de ensino"
    End If
    ShowHTML "          </select></td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""STI"" type=""text"" name=""P4"" size=""4"" maxlength=""4"" value=""" & P4 & """></td>"
    ShowHTML "      </table>"
    ShowHTML "      </tr>"    
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Exibir:</b><br>"
    If p_tipo = "N" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_tipo"" value=""S""> Apenas totais <input " & w_Disabled & " type=""radio"" name=""p_tipo"" value=""N"" checked> Totais e detalhes&nbsp;&nbsp;&nbsp;"
    Else
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_tipo"" value=""S"" checked> Apenas totais <input " & w_Disabled & " type=""radio"" name=""p_tipo"" value=""N""> Totais e detalhes&nbsp;&nbsp;&nbsp;"
    End If
    ShowHTML "          </table>"
    ShowHTML "       </tr>"    
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

  Set w_regional         = Nothing 
  Set w_tot1             = Nothing 
  Set w_tot2             = Nothing 
  Set p_tipo             = Nothing
  Set p_modalidade       = Nothing 
  Set p_turno            = Nothing 
  Set p_serie            = Nothing 
  Set p_turma            = Nothing 
  Set p_unidade          = Nothing
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

