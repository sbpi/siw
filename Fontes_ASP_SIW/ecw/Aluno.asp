<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Geral.asp" -->

<%
Response.Expires = -1500
REM =========================================================================
REM  /Aluno.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Gerencia a consulta de alunos
REM Mail     : alex@sbpi.com.br
REM Criacao  : 18/08/2003, 14:55
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
Private Par

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
w_Pagina     = "Aluno.asp?par="
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

Set w_ImagemPadrao  = Nothing
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
  Dim p_codigo
  Dim w_ds_Aluno, p_nome
  Dim p_mat, p_resp, p_uni, p_situacao
  Dim p_pai, p_mae
  Dim p_1, p_2, p_3, p_4
  Dim p_Ordena

  p_nome         = uCase(Request("p_nome"))
  p_1            = uCase(Request("p_1")) 'Tipo de busca para o nome do aluno
  p_uni          = uCase(Request("p_uni"))
  p_resp         = uCase(Request("p_resp"))
  p_2            = uCase(Request("p_2")) 'Tipo de busca para o nome do responsável
  p_pai          = uCase(Request("p_pai"))
  p_3            = uCase(Request("p_3")) 'Tipo de busca para o nome do pai
  p_mae          = uCase(Request("p_mae"))
  p_4            = uCase(Request("p_4")) 'Tipo de busca para o nome da mãe
  p_mat          = uCase(Request("p_mat"))
  p_codigo       = uCase(Request("p_codigo"))
  p_ordena       = uCase(Request("p_ordena"))
  
  If O = "L" Then
     If p_nome > "" Then If p_1 = "S" Then p_nome = "%" & p_nome & "%" Else p_nome = p_nome & "%" End If End If
     If p_resp > "" Then If p_2 = "S" Then p_resp = "%" & p_resp & "%" Else p_resp = p_resp & "%" End If End If
     If p_pai > ""  Then If p_3 = "S" Then p_pai = "%" & p_pai & "%"   Else p_pai = p_pai & "%"   End If End If
     If p_mae > ""  Then If p_4 = "S" Then p_mae = "%" & p_mae & "%"   Else p_mae = p_mae & "%"   End If End If
     DB_GetAlunoList RS, Session("periodo"), Session("regional"), p_nome, p_resp, p_pai, p_mae, p_mat, p_uni, null, null
     If p_ordena > "" Then RS.sort = p_ordena & ", ds_aluno, ds_escola" Else RS.sort = "ds_aluno, ds_escola" End If
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     FormataMat
     ValidateOpen "Validacao"
     If O="P" Then
        Validate "periodo", "Período", "SELECT", "1", "1", "10", "1", "1"
        Validate "regional", "Regional", "SELECT", "", "1", "10", "1", "1"
        Validate "p_mat", "Matrícula", "1", "", "10", "12", "", "0123456789-"
        Validate "p_nome", "Nome", "1", "", "3", "40", "1", "1"
        Validate "p_resp", "Responsável", "1", "", "3", "40", "1", "1"
        Validate "p_pai", "Pai", "1", "", "3", "40", "1", "1"
        Validate "p_mae", "Mãe", "1", "", "3", "40", "1", "1"
        Validate "P4", "Linhas por página", "1", "1", "1", "4", "", "0123456789"
        ShowHTML "  if (theForm.p_mat.value == '' && theForm.p_resp.value == '' && theForm.p_pai.value == '' && theForm.p_mae.value == '' && theForm.p_nome.value == '' && theForm.p_uni.selectedIndex == 0) {"
        ShowHTML "     alert('Indique pelo menos um critério de filtragem!');"
        ShowHTML "     theForm.p_mat.focus();"
        ShowHTML "     return false;"
        ShowHTML "  }"
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ScriptOpen "JavaScript"
  ShowHTML "function janelaAluno(p_mat) {"
  ShowHTML "  window.open('" & w_Pagina & "ExibeAluno&R=" & w_Pagina & par & "&O=F&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_mat=' + p_mat ,'Aluno','top=10 left=30 width=780 height=500 toolbar=no scrollbars=yes status=no address=no resizable=yes');"
  ShowHTML "}"
  ShowHTML "function janelaResponsavel(p_resp) {"
  ShowHTML "  window.open('" & "Responsavel.asp?par=ExibeResponsavel&R=" & w_Pagina & par & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_responsavel=' + p_resp ,'Responsavel','top=20 left=20 width=750 height=500 toolbar=no scrollbars=yes status=no address=no resizable=yes');"
  ShowHTML "}"
  ShowHTML "function selecionar(campo1,campo2,campo3,campo4){"
  ShowHTML "  if (campo2[1].checked || campo3[1].checked || campo4[1].checked){"
  ShowHTML "     alert('A busca em ""Qualquer parte"" só pode ser escolhida em uma das buscas!');"
  ShowHTML "     return campo1[0].checked = true;"
  ShowHTML "   }"
  ShowHTML "  }"
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
  If InStr("IAE",O) > 0 Then
     If O = "E" Then
        BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
     Else
        BodyOpen "onLoad='document.Form.w_ds_aluno.focus()';"
     End If
  ElseIf InStr("P",O) > 0 Then
     BodyOpen "onLoad='document.Form.p_mat.focus()';"
  Else
     BodyOpen "onLoad=document.focus();"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  If O <> "P" Then ExibeParametros w_cliente End If
  
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    w_filter = ""
    If p_mat > "" Then 
       w_filter = w_filter & "[Matr.: <b>" & p_mat & "</b>]&nbsp;"
    End If
    If p_nome > "" Then 
       w_filter = w_filter & "[Aluno: <b>" & p_nome & "</b>]&nbsp;"
    End If
    If p_resp > "" Then 
       w_filter = w_filter & "[Resp.: <b>" & p_resp & "</b>]&nbsp;"
    End If
    If p_pai > "" Then 
       w_filter = w_filter & "[Pai: <b>" & p_pai & "</b>]&nbsp;"
    End If
    If p_mae > "" Then 
       w_filter = w_filter & "[Mãe: <b>" & p_mae & "</b>]&nbsp;"
    End If
    If p_uni > "" Then 
       DB_GetSchoolList RS1, w_cliente
       RS1.Filter = "co_unidade = " & p_uni
       w_filter = w_filter & " [Unid.: <b>" & RS1("ds_unidade") & "</b>]&nbsp;"
    End If
    If w_filter > ""  Then ShowHTML "<tr bgcolor=""" & conTrAlternateBgColor & """><td colspan=5><font size=1><b>&nbsp;Filtro:&nbsp;</b>" & w_filter & "</font><BR>"
    ShowHTML "<tr><td><font size=""2"">"
    If p_mat & p_resp & p_uni & p_nome & p_pai & p_mae & p_Ordena > "" Then
       ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_Dir & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_mat=" & p_mat & "&p_resp=" & p_resp & "&p_pai=" & p_pai & "&p_mae=" & p_mae & "&p_uni=" & p_uni & "&p_ordena=" & p_ordena & "&p_1=" &p_1& "&p_2=" &p_2& "&p_3=" &p_3& "&p_4=" &p_4& """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a></font>"
    Else
       ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_Dir & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_mat=" & p_mat & "&p_resp=" & p_resp & "&p_pai=" & p_pai & "&p_mae=" & p_mae & "&p_uni=" & p_uni & "&p_ordena=" & p_ordena & "&p_1=" &p_1& "&p_2=" &p_2& "&p_3=" &p_3& "&p_4=" &p_4& """><u>F</u>iltrar (Inativo)</a>"
    End If
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Matrícula</font></td>"
    ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
    ShowHTML "          <td><font size=""1""><b>Responsável</font></td>"
    ShowHTML "          <td><font size=""1""><b>Unidade</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=5 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF and RS.AbsolutePage = P3
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """>"
        ShowHTML "        <td align=""center"" nowrap><font size=""1"">" & RS("co_aluno") & "</td>"
        ShowHTML "        <td title=""Clique para ver a ficha cadastral deste aluno.""><font size=""1""><a class=""HL"" HREF=""javascript:janelaAluno('" & trim(RS("co_aluno")) & "');"">" & lCase(RS("ds_aluno")) & "</span></td>"
        If IsNull(Tvl(RS("ds_responsavel"))) Then
           ShowHTML "        <td><font size=""1"">---</td>"
        Else
           ShowHTML "        <td title=""Clique para ver a ficha cadastral deste responsável.""><font size=""1""><a class=""HL"" HREF=""javascript:janelaResponsavel('" & trim(RS("co_responsavel")) & "');"">" & lCase(RS("ds_responsavel")) & "</a></td>"
        End If
        ShowHTML "        <td><font size=""1"">" & lCase(RS("ds_escola")) & "</td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    ShowHTML "<tr><td align=""center"" colspan=3>"
    MontaBarra w_dir&w_pagina&par&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG, RS.PageCount, P3, P4, RS.RecordCount
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
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>M</U>atrícula:<br><INPUT ACCESSKEY=""M"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_mat"" size=""12"" maxlength=""10"" value=""" & p_mat & """ onKeyDown=""FormataMat(this,event);""></td>"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%"">"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>N</U>ome aluno:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_nome"" size=""40"" maxlength=""40"" value=""" & p_nome & """></td>"
    ShowHTML "          <td align=""left""><font size=""1""><b>Buscar:</b>"
    If p_1 = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_1"" value=""N""> Iniciado em <input " & w_Disabled & " type=""radio"" name=""p_1"" value=""S"" checked onClick=""selecionar(p_1,p_2,p_3,p_4);"" > Qualquer parte &nbsp;&nbsp;&nbsp;"
    Else
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_1"" value=""N"" checked> Iniciado em <input " & w_Disabled & " type=""radio"" name=""p_1"" value=""S"" onClick=""selecionar(p_1,p_2,p_3,p_4);"" > Qualquer parte &nbsp;&nbsp;&nbsp;"
    End If    
    ShowHTML "</table>"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%"">"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Nome <U>r</U>esponsável:<br><INPUT ACCESSKEY=""R"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_resp"" size=""40"" maxlength=""40"" value=""" & p_resp & """></td>"
    ShowHTML "          <td align=""left""><font size=""1""><b>Buscar:</b>"
    If p_2 = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_2"" value=""N""> Iniciado em <input " & w_Disabled & " type=""radio"" name=""p_2"" value=""S"" checked onClick=""selecionar(p_2,p_1,p_3,p_4);"" > Qualquer parte &nbsp;&nbsp;&nbsp;"
    Else
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_2"" value=""N"" checked> Iniciado em <input " & w_Disabled & " type=""radio"" name=""p_2"" value=""S"" onClick=""selecionar(p_2,p_1,p_3,p_4);"" > Qualquer parte &nbsp;&nbsp;&nbsp;"
    End If    
    ShowHTML "</table>"    
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%"">"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Nome <U>p</U>ai:<br><INPUT ACCESSKEY=""P"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_pai"" size=""40"" maxlength=""40"" value=""" & p_pai & """></td>"
    ShowHTML "          <td align=""left""><font size=""1""><b>Buscar:</b>"
    If p_3 = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_3"" value=""N""> Iniciado em <input " & w_Disabled & " type=""radio"" name=""p_3"" value=""S"" checked onClick=""selecionar(p_3,p_1,p_2,p_4);"" > Qualquer parte &nbsp;&nbsp;&nbsp;"
    Else
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_3"" value=""N"" checked> Iniciado em <input " & w_Disabled & " type=""radio"" name=""p_3"" value=""S"" onClick=""selecionar(p_3,p_1,p_2,p_4);"" > Qualquer parte &nbsp;&nbsp;&nbsp;"
    End If    
    ShowHTML "</table>"    
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%"">"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Nome m<U>ã</U>e:<br><INPUT ACCESSKEY=""A"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_mae"" size=""40"" maxlength=""40"" value=""" & p_mae & """></td>"
        ShowHTML "          <td align=""left""><font size=""1""><b>Buscar:</b>"
    If p_4 = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_4"" value=""N""> Iniciado em <input " & w_Disabled & " type=""radio"" name=""p_4"" value=""S"" checked onClick=""selecionar(p_4,p_1,p_2,p_3);"" > Qualquer parte &nbsp;&nbsp;&nbsp;"
    Else
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_4"" value=""N"" checked> Iniciado em <input " & w_Disabled & " type=""radio"" name=""p_4"" value=""S"" onClick=""selecionar(p_4,p_1,p_2,p_3);"" > Qualquer parte &nbsp;&nbsp;&nbsp;"
    End If    
    ShowHTML "</table>"    
    ShowHTML "      <tr>"
    If Session("regional") = "00" or IsNull(Tvl(Session("regional"))) Then
       SelecaoUnidadeEnsino "<u>U</u>nidade de ensino", "U", null, p_uni, null, "p_uni", null, null
    Else
       SelecaoUnidadeEnsino "<u>U</u>nidade de ensino", "U", null, p_uni, null, "p_uni", "co_sigre like '" & Session("regional") & "*'", null
    End IF
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY=""O"" " & w_Disabled & " class=""STS"" name=""p_ordena"" size=""1"">"
    If p_Ordena="CO_ALUNO" Then
       ShowHTML "          <option value=""co_aluno"">Matrícula<option value="""" SELECTED>Nome aluno<option value=""ds_responsavel"">Nome responsável<option value=""ds_escola"">Unidade de ensino"
    ElseIf p_Ordena="DS_RESPONSAVEL" Then
       ShowHTML "          <option value=""co_aluno"">Matrícula<option value="""">Nome aluno<option value=""ds_responsavel"" SELECTED>Nome responsável<option value=""ds_escola"">Unidade de ensino"
    ElseIf p_Ordena="DS_ESCOLA" Then
       ShowHTML "          <option value=""co_aluno"">Matrícula<option value="""">Nome aluno<option value=""ds_responsavel"">Nome responsável<option value=""ds_escola"" SELECTED>Unidade de ensino"
    Else
       ShowHTML "          <option value=""co_aluno"">Matrícula<option value="""" SELECTED>Nome aluno<option value=""ds_responsavel"">Nome responsável<option value=""ds_escola"">Unidade de ensino"
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
  Set p_nome             = Nothing
  Set p_codigo           = Nothing
  Set p_ordena           = Nothing
  Set p_1                = Nothing
  Set p_2                = Nothing
  Set p_3                = Nothing
  Set p_4                = Nothing

End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de exibição dos dados de alunos
REM -------------------------------------------------------------------------
Sub ExibeAluno

  Dim p_mat
  Dim w_ds_unidade_ant
  Dim w_cont_ds
  p_mat        = uCase(Request("p_mat"))

  DB_GetAlunoData RS, Session("periodo"), p_mat, "CABECALHO"
  Cabecalho
  ShowHTML "<HEAD>"
  ScriptOpen "JavaScript"
  ShowHTML "function janelaResponsavel(p_resp) {"
  ShowHTML "  window.open('" & "Responsavel.asp?par=ExibeResponsavel&R=" & w_Pagina & par & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_responsavel=' + p_resp ,'Responsavel','top=20 left=20 width=750 height=500 toolbar=no scrollbars=yes status=no address=no resizable=yes');"
  ShowHTML "}"
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
  BodyOpenClean "onLoad=document.focus();"
  ShowHTML "<div align=center><center>"
  ShowHTML "<TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"

  ShowHTML "<tr valign=""top""><td bgcolor=""#FAEBD7""><table border=1 cellpadding=2 cellspacing=5 width=""100%"">"
  ShowHTML "  <tr valign=""top"">"
  ShowHTML "    <td ><font size=1>Nº de matrícula:<br><b>" & RS("co_aluno")
  ShowHTML "    <td colspan=3><font size=1>Nome:<br><b>" & RS("ds_aluno")
  ShowHTML "    <td colspan=2><font size=1>Unidade:<br><b>" & Nvl(RS("ds_unidade"),"---")
  ShowHTML "  <tr valign=""top"">"
  ShowHTML "    <td><font size=1>Data da matrícula:<br><b>" & FormataDataEdicao(RS("dt_matricula"))
  ShowHTML "    <td><font size=1>ANEE:<br><b>" & Nvl(trim(RS("tp_anee")),"NÃO")       
  ShowHTML "    <td><font size=1>Renda Minha:<br><b>" & Nvl(trim(RS("tp_bolsa_escola")),"NÃO")
  ShowHTML "    <td><font size=1>Nº Renda Minha:<br><b>" & Nvl(trim(RS("nu_bolsa_escola")),"---")
  ShowHTML "    <td><font size=1>Data de ingresso:<br><b>" & FormataDataEdicao(RS("dt_ingresso"))
  If trim(RS("tp_ano_letivo")) = "A" Then
     ShowHTML "    <td><font size=1>Ano:<br><b>" & Mid(Session("periodo"),1,4)
  Else
     ShowHTML "    <td><font size=1>Ano/Período:<br><b>" & Replace(Session("periodo"),Mid(Session("periodo"),5),"/"&Mid(Session("periodo"),5))
  End If
  ShowHTML "  </table>"
  ShowHTML "<tr valign=""top""><td><table border=1 cellpadding=1 cellspacing=0 width=""100%"">"
  ShowHTML "  <tr valign=""top"" align=""center"">"
  If SG = "CADASTRO" or SG = "" Then
     ShowHTML "    <td><font class=""SS"" color=""#FF0000""><b>Cadastro "
  Else
     ShowHTML "    <td><font size=1><A class=""SS"" HREF=""" & w_dir & w_pagina & par & "&p_mat=" & p_mat & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&SG=CADASTRO"">Cadastro</a> "
  End If
  If SG = "TURMA" Then
     ShowHTML "    <td><font class=""SS"" color=""#FF0000""><b>Turmas "
  Else
     ShowHTML "    <td><font size=1><A class=""SS"" HREF=""" & w_dir & w_pagina & par & "&p_mat=" & p_mat & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&SG=TURMA"">Turmas</a> "
  End If
  If SG = "DOCUMENTO" Then
     ShowHTML "    <td><font class=""SS"" color=""#FF0000""><b>Documentos "
  Else
     ShowHTML "    <td><font size=1><A class=""SS"" HREF=""" & w_dir & w_pagina & par & "&p_mat=" & p_mat & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&SG=DOCUMENTO"">Documentos</a> "
  End If
  If SG = "ADAPT" Then
     ShowHTML "    <td><font class=""SS"" color=""#FF0000""><b>Adaptação "
  Else
     ShowHTML "    <td><font size=1><A class=""SS"" HREF=""" & w_dir & w_pagina & par & "&p_mat=" & p_mat & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&SG=ADAPT"">Adaptação</a> "
  End If
  If SG = "APROVEIT" Then
     ShowHTML "    <td><font class=""SS"" color=""#FF0000""><b>Aproveitamento de Estudo "
  Else
     ShowHTML "    <td><font size=1><A class=""SS"" HREF=""" & w_dir & w_pagina & par & "&p_mat=" & p_mat & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&SG=APROVEIT"">Aproveitamento de Estudo</a> "
  End If
  If SG = "DEPEND" Then
     ShowHTML "    <td><font class=""SS"" color=""#FF0000""><b>Dependência "
  Else
     ShowHTML "    <td><font size=1><A class=""SS"" HREF=""" & w_dir & w_pagina & par & "&p_mat=" & p_mat & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&SG=DEPEND"">Dependência</a> "
  End If
  If SG = "MEDICA" Then
     ShowHTML "    <td><font class=""SS"" color=""#FF0000""><b>Ficha Médica "
  Else
     ShowHTML "    <td><font size=1><A class=""SS"" HREF=""" & w_dir & w_pagina & par & "&p_mat=" & p_mat & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&SG=MEDICA"">Ficha Médica</a> "
  End If
  If SG = "BOLETIM" Then
     ShowHTML "    <td><font class=""SS"" color=""#FF0000""><b>Boletim "
  Else
     ShowHTML "    <td><font size=1><A class=""SS"" HREF=""" & w_dir & w_pagina & par & "&p_mat=" & p_mat & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&SG=BOLETIM"">Boletim</a> "
  End If
  ShowHTML "  </table>"
     
  If SG = "" or SG = "CADASTRO" Then
    ShowHTML "<tr valign=""top""><td><table border=0 cellpadding=0 cellspacing=0 width=""100%"">"
    ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
    ShowHTML "    <tr valign=""top"">"
    ShowHTML "      <td><font size=1>Data de nascimento:<br><b>" &FormataDataEdicao(RS("dt_nascimento"))
    'ShowHTML "      <td><font size=1>Data de nascimento:<br><b>---"
    ShowHTML "      <td><font size=1>Sexo:<br><b>" & Nvl(trim(RS("tp_sexo_aluno")),"---")
    ShowHTML "      <td><font size=1>Naturalidade:<br><b>" & Nvl(trim(RS("ds_naturalidade")),"---")
    ShowHTML "      <td><font size=1>UF:<br><b>" & Nvl(trim(RS("ds_uf_nascimento")),"---")
    ShowHTML "      <td><font size=1>Nacionalidade:<br><b>" & Nvl(trim(RS("ds_nacionalidade")),"---")
    ShowHTML "    </table>"
    ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
    ShowHTML "    <tr valign=""top"">"
    ShowHTML "      <td><font size=1>Endereço:<br><b>" & Nvl(Trim(RS("ds_endereco")),"---")
    ShowHTML "      <td><font size=1>Bairro:<br><b>" & Nvl(trim(RS("ds_bairro")),"---")
    ShowHTML "    </table>"
    ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
    ShowHTML "    <tr valign=""top"">"
    ShowHTML "      <td><font size=1>Cidade:<br><b>" & Nvl(Trim(RS("ds_cidade")),"---")
    ShowHTML "      <td><font size=1>UF:<br><b>" & Nvl(trim(RS("ds_uf_cidade")),"---")
    ShowHTML "      <td><font size=1>CEP:<br><b>" & Nvl(trim(RS("nu_cep")),"---")
    ShowHTML "      <td><font size=1>E-Mail:<br><b>" & Nvl(trim(RS("ds_e_mail")),"---")
    ShowHTML "    </table>"
    ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
    ShowHTML "    <tr valign=""top"">"
    ShowHTML "      <td><font size=1>Peso:<br><b>" & Nvl(Trim(RS("nu_peso")),"---")
    ShowHTML "      <td><font size=1>Altura:<br><b>" & Nvl(trim(RS("nu_altura")),"---")
    ShowHTML "      <td><font size=1>Apto para Ed.Física:<br><b>" & Nvl(trim(RS("tp_apto_ed_fisica")),"---")
    ShowHTML "      <td><font size=1>Ensino Religioso:<br><b>" & Nvl(trim(RS("st_ens_religioso")),"---")
    ShowHTML "      <td><font size=1>Estado Civil:<br><b>" & Nvl(trim(RS("tp_estado_civil")),"---")
    ShowHTML "      <td><font size=1>Situação Acadêmica:<br><b>" & Nvl(trim(RS("ds_situacao_aluno")),"---")
    ShowHTML "    </table>"
    ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
    ShowHTML "    <tr valign=""top"">"
    ShowHTML "      <td><font size=1>Nome do cônjuge:<br><b>" & Nvl(Trim(RS("ds_conjuge")),"---")
    ShowHTML "      <td><font size=1>Tempo de Escolaridade:<br><b>" & Nvl(trim(RS("nu_tempo_escolar")),"---")
    ShowHTML "    </table>"
    ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
    ShowHTML "    <tr valign=""top"">"
    ShowHTML "      <td><font size=1>Escola de Origem:<br><b>" & Nvl(Trim(RS("tp_escola_origem")),"---")
    ShowHTML "      <td><font size=1>Descrição da Escola de Origem:<br><b>" & Nvl(trim(RS("ds_origem_escola")),"---")
    ShowHTML "      <td><font size=1>Tamanho Calçado:<br><b>" & Nvl(Trim(RS("nu_pe")),"---")
    ShowHTML "      <td><font size=1>Tamanho Uniforme:<br><b>" & Nvl(Trim(RS("nu_uniforme")),"---")
    ShowHTML "    </table>"
    ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
    ShowHTML "    <tr valign=""top"">"
    ShowHTML "      <td><font size=1>Pai:<br><b>" & Nvl(Trim(RS("ds_pai")),"---")
    ShowHTML "      <td><font size=1>Mãe:<br><b>" & Nvl(trim(RS("ds_mae")),"---")
    ShowHTML "    <tr valign=""top"">"
    ShowHTML "      <td><font size=1>Telefone Pai:<br><b>" & Nvl(Trim(RS("ds_telefone_pai")),"---")
    ShowHTML "      <td><font size=1>Telefone Mãe:<br><b>" & Nvl(trim(RS("ds_telefone_mae")),"---")
    ShowHTML "    </table>"
    ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
    ShowHTML "    <tr valign=""top"">"
    If IsNull(Tvl(RS("ds_responsavel"))) Then
       ShowHTML "      <td><font size=""1"">Responsável:<br><b>---</td>"
    Else
       ShowHTML "      <td title=""Clique para ver a ficha cadastral deste responsável.""><font size=""1"">Responsável:<br><b><a class=""HL"" HREF=""javascript:janelaResponsavel('" & trim(RS("co_responsavel")) & "');"">" & RS("ds_responsavel") & "</a></td>"
    End If
    ShowHTML "    </table>"
    ShowHTML "  </table>"
       
  Elseif SG =  "TURMA" Then
    DB_GetAlunoData RS, Session("periodo"), p_mat, "TURMA"
    While Not RS.EOF
       If RS.RecordCount > 1 Then
          ShowHTML "<tr valign=""top"">"
          ShowHTML "<td bgcolor="""&conTrAlternateBgColor&"""><font size=2>Unidade:<b>" & Nvl(RS("ds_escola"),"---")
       End If
       ShowHTML "<tr valign=""top"">"
       ShowHTML "<td><table border=0 cellpadding=0 cellspacing=0 width=""100%"">"
       ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
       ShowHTML "    <tr valign=""top"">"
       ShowHTML "      <td><font size=1>Turno:<br><b>" & Nvl(trim(RS("co_turno")),"---")
       ShowHTML "      <td><font size=1>Curso:<br><b>" & Nvl(trim(RS("ds_curso")),"---")
       ShowHTML "      <td><font size=1>Série:<br><b>" & Nvl(trim(RS("sg_serie")),"---")
       ShowHTML "      <td><font size=1>Nº de chamada:<br><b>" & Nvl(trim(RS("nu_chamada")),"---")
       ShowHTML "    </table>"
       ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
       ShowHTML "    <tr valign=""top"">"
       ShowHTML "      <td><font size=1>Turma:<br><b>" & Nvl(Trim(RS("ds_turma")),"---")
       ShowHTML "      <td><font size=1>Abreviatura:<br><b>" & Nvl(trim(RS("co_letra_turma")),"---")
       ShowHTML "      <td><font size=1>Bloco:<br><b>" & Nvl(trim(RS("co_bloco")),"---")
       ShowHTML "      <td><font size=1>Sala:<br><b>" & Nvl(trim(RS("ds_sala")),"---")
       ShowHTML "    </table>"
       ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
       ShowHTML "    <tr valign=""top"">"
       If RS("dt_movimentacao") > "" Then
          ShowHTML "      <td><font size=1>Data de Movimentação:<br><b>" & FormataDataEdicao(RS("dt_movimentacao"))
       Else
          ShowHTML "      <td><font size=1>Data de Movimentação:<br><b>---"
       End If       
       ShowHTML "      <td><font size=1>Movimentação:<br><b>" & Nvl(trim(RS("st_movimentacao")),"---")
       RS.MoveNext
       ShowHTML "    </table>"
       ShowHTML "  </table>"
    Wend
        
  Elseif SG = "DOCUMENTO" Then
    ShowHTML "<tr valign=""top""><td><table border=0 cellpadding=0 cellspacing=0 width=""100%"">"
    ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
    ShowHTML "    <tr valign=""top"">"
    ShowHTML "      <td><font size=1>Nº do RG:<br><b>" & Nvl(trim(RS("nu_rg")),"---")
    ShowHTML "      <td><font size=1>Órgão Emissor:<br><b>" & Nvl(trim(RS("ds_orgao_emissor")),"---")
    If RS("dt_emissao") > "" Then
       ShowHTML "      <td><font size=1>Data de Emissão:<br><b>" & FormataDataEdicao(RS("dt_emissao"))
    Else
        ShowHTML "      <td><font size=1>Data de Emissão:<br><b>---"
    End If
    ShowHTML "      <td><font size=1>Nº de Reservista:<br><b>" & Nvl(trim(RS("nu_reservista")),"---")
    ShowHTML "    </table>"
    ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
    ShowHTML "    <tr valign=""top"">"
    ShowHTML "      <td><font size=1>Nº do CPF:<br><b>" & Nvl(Trim(RS("nu_cpf")),"---")
    ShowHTML "      <td><font size=1>Nº do Título de Eleitor:<br><b>" & Nvl(trim(RS("nu_titulo_eleitor")),"---")
    ShowHTML "      <td><font size=1>Zona:<br><b>" & Nvl(trim(RS("ds_zona")),"---")
    ShowHTML "      <td><font size=1>Seção:<br><b>" & Nvl(trim(RS("ds_secao")),"---")
    ShowHTML "    </table>"
    ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
    ShowHTML "    <tr valign=""top"">"
    ShowHTML "      <td><font size=1>Certidão de:<br><b>" & Nvl(Trim(RS("ds_certidao")),"---")
    ShowHTML "      <td><font size=1>Nº da Certidão:<br><b>" & Nvl(trim(RS("nu_certidao")),"---")
    ShowHTML "      <td><font size=1>Nº do Livro:<br><b>" & Nvl(trim(RS("nu_livro")),"---")
    ShowHTML "      <td><font size=1>Nº da Folha:<br><b>" & Nvl(trim(RS("nu_folha")),"---")
    ShowHTML "    </table>"
    ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
    ShowHTML "    <tr valign=""top"">"
    ShowHTML "      <td><font size=1>Cartório:<br><b>" & Nvl(Trim(RS("ds_cartorio")),"---")
    ShowHTML "      <td><font size=1>Cidade do Cartório:<br><b>" & Nvl(trim(RS("ds_cidade_certidao")),"---")
    ShowHTML "      <td><font size=1>UF:<br><b>" & Nvl(trim(RS("ds_uf_certidao")),"---")
    ShowHTML "    </table>"
    ShowHTML "  </table>"
       
  Elseif SG = "APROVEIT" Then
    DB_GetAlunoData RS, Session("periodo"), p_mat, SG
    If RS.RecordCount < 2 Then
       ShowHTML "<tr><td align=""center"" colspan=6>"
       ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
       ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
       ShowHTML "          <td><font size=""1""><b>Série</font></td>"
       ShowHTML "          <td><font size=""1""><b>Componente Curricular</font></td>"
       ShowHTML "          <td><font size=""1""><b>Tipo de exame</font></td>"
       ShowHTML "          <td><font size=""1""><b>Resultado</font></td>"
       ShowHTML "          <td><font size=""1""><b>Aulas dadas</font></td>"
       ShowHTML "          <td><font size=""1""><b>Faltas</font></td>"          
    End If
    While Not RS.EOF
       If RS.RecordCount > 1 Then
          ShowHTML "<tr valign=""top"" colspan=6>"
          ShowHTML "<td bgcolor="""&conTrAlternateBgColor&"""><font size=2>Unidade:<b>" & Nvl(RS("ds_escola"),"---")
          ShowHTML "<tr><td align=""center"" colspan=5>"
          ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
          ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
          ShowHTML "          <td><font size=""1""><b>Série</font></td>"
          ShowHTML "          <td><font size=""1""><b>Componente Curricular</font></td>"
          ShowHTML "          <td><font size=""1""><b>Tipo de exame</font></td>"
          ShowHTML "          <td><font size=""1""><b>Resultado</font></td>"
          ShowHTML "          <td><font size=""1""><b>Aulas dadas</font></td>"
          ShowHTML "          <td><font size=""1""><b>Faltas</font></td>"
       End If
       If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
       ShowHTML "      <tr bgcolor=""" & w_cor & """>"          
       ShowHTML "      <td><font size=1>" & Nvl(trim(RS("sg_serie")),"---")
       ShowHTML "      <td><font size=1>" & Nvl(RS("ds_disciplina"),"---")
       ShowHTML "      <td><font size=1>" & Nvl(trim(RS("id_exame")),"---")
       ShowHTML "      <td><font size=1>" & Nvl(trim(RS("nu_nota")),"---")
       ShowHTML "      <td><font size=1>" & Nvl(Trim(RS("nu_aulas_dadas")),"---")
       ShowHTML "      <td><font size=1>" & Nvl(trim(RS("nu_faltas")),"---")
       RS.MoveNext
       If RS.RecordCount > 1 Then
          ShowHTML "</table>"
       End If
    Wend              
    ShowHTML "  </table>"
    ShowHTML "</tr>"  
  Elseif SG = "DEPEND" Then
    DB_GetAlunoData RS, Session("periodo"), p_mat, SG
    w_cont_ds = 0
    w_ds_unidade_ant = RS("ds_escola")
    While Not RS.EOF
       If w_ds_unidade_ant <> RS("ds_escola") Then
          w_cont_ds = w_cont_ds + 1
       End If
       w_ds_unidade_ant = RS("ds_escola")
       RS.MoveNext
    Wend
    If w_cont_ds < 2 Then
       ShowHTML "<tr><td align=""center"" colspan=5>"
       ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
       ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
       ShowHTML "          <td><font size=""1""><b>Série</font></td>"
       ShowHTML "          <td><font size=""1""><b>Componente Curricular</font></td>"
       ShowHTML "          <td><font size=""1""><b>Resultado</font></td>"
       ShowHTML "          <td><font size=""1""><b>Aulas dadas</font></td>"
       ShowHTML "          <td><font size=""1""><b>Faltas</font></td>"
    End If
    RS.MoveFirst
    While Not RS.EOF
       If w_ds_unidade_ant <> RS("ds_escola") and w_cont_ds > 1  Then
          ShowHTML "<tr valign=""top"" colspan = 5>"
          ShowHTML "<td bgcolor="""&conTrAlternateBgColor&"""><font size=2>Unidade:<b>" & Nvl(RS("ds_escola"),"---")
          ShowHTML "<tr><td align=""center"" colspan=5>"
          ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
          ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
          ShowHTML "          <td colspan=1><font size=""1""><b>Série</font></td>"
          ShowHTML "          <td colspan=1><font size=""1""><b>Componente Curricular</font></td>"
          ShowHTML "          <td colspan=1><font size=""1""><b>Resultado</font></td>"
          ShowHTML "          <td colspan=1><font size=""1""><b>Aulas dadas</font></td>"
          ShowHTML "          <td colspan=1><font size=""1""><b>Faltas</font></td>"
          ShowHTML "        </tr>"          
       End If
       w_ds_unidade_ant = Nvl(RS("ds_escola"),"---")
       If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
       ShowHTML "      <tr bgcolor=""" & w_cor & """>"          
       ShowHTML "      <td colspan=1 align=""center""><font size=1>" & Nvl(trim(RS("dp_serie")),"---")
       ShowHTML "      <td colspan=1><font size=1>" & Nvl(RS("ds_disciplina"),"---")
       ShowHTML "      <td colspan=1 align=""center""><font size=1>" & Nvl(trim(RS("nu_nota")),"---")
       ShowHTML "      <td colspan=1 align=""center""><font size=1>" & Nvl(Trim(RS("nu_aulas_dadas")),"---")
       ShowHTML "      <td colspan=1 align=""center""><font size=1>" & Nvl(trim(RS("nu_faltas")),"---")
       RS.MoveNext
       If w_cont_ds > 1 Then
          ShowHTML "</table>"
       End If
    Wend    
    ShowHTML "  </table>"
    ShowHTML "</tr>"
    ShowHTML "</td>"      

  Elseif SG = "ADAPT" Then
    DB_GetAlunoData RS, Session("periodo"), p_mat, SG
    If RS.RecordCount < 2 Then
       ShowHTML "<tr><td align=""center"" colspan=5>"
       ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
       ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
       ShowHTML "          <td><font size=""1""><b>Série</font></td>"
       ShowHTML "          <td><font size=""1""><b>Componente Curricular</font></td>"
       ShowHTML "          <td><font size=""1""><b>Resultado</font></td>"
       ShowHTML "          <td><font size=""1""><b>Aulas dadas</font></td>"
       ShowHTML "          <td><font size=""1""><b>Faltas</font></td>"
    End If
    While Not RS.EOF
       If RS.RecordCount > 1 Then
          ShowHTML "<tr valign=""top"" colspan=5>"
          ShowHTML "<td bgcolor="""&conTrAlternateBgColor&"""><font size=2>Unidade:<b>" & Nvl(RS("ds_escola"),"---")
          ShowHTML "<tr><td align=""center"" colspan=5>"
          ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
          ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
          ShowHTML "          <td><font size=""1""><b>Série</font></td>"
          ShowHTML "          <td><font size=""1""><b>Componente Curricular</font></td>"
          ShowHTML "          <td><font size=""1""><b>Resultado</font></td>"
          ShowHTML "          <td><font size=""1""><b>Aulas dadas</font></td>"
          ShowHTML "          <td><font size=""1""><b>Faltas</font></td>"
       End If
       If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
       ShowHTML "      <tr bgcolor=""" & w_cor & """>"        
       ShowHTML "      <td><font size=1>" & Nvl(trim(RS("sg_serie")),"---")
       ShowHTML "      <td><font size=1>" & Nvl(RS("ds_disciplina"),"---")
       ShowHTML "      <td><font size=1>" & Nvl(trim(RS("nu_nota")),"---")
       ShowHTML "      <td><font size=1>" & Nvl(Trim(RS("nu_aulas_dadas")),"---")
       ShowHTML "      <td><font size=1>" & Nvl(trim(RS("nu_faltas")),"---")
       RS.MoveNext
       If RS.RecordCount > 1 Then
          ShowHTML "</table>"
       End If
    Wend
    ShowHTML "  </table>"
    ShowHTML "</tr>"               
            
  Elseif SG = "MEDICA" Then
    ShowHTML "<tr valign=""top""><td><table border=0 cellpadding=0 cellspacing=0 width=""100%"">"
    ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
    ShowHTML "    <tr valign=""top""><td><font size=1>Problemas de Saúde:<br>"
    If Nvl(RS("tp_visao"),0)  = 0     Then ShowHTML "          <input type=""checkbox"" disabled name=check> Visão"   Else ShowHTML "          <input type=""checkbox"" disabled name=check checked> Visão"   End If
    If Nvl(RS("tp_audicao"),0)= 0     Then ShowHTML "          <input type=""checkbox"" disabled name=check> Audição" Else ShowHTML "          <input type=""checkbox"" disabled name=check checked> Audicao" End If
    If IsNull(RS("ds_probsaude"))     Then ShowHTML "          <input type=""checkbox"" disabled name=check> Outros"  Else ShowHTML "          <input type=""checkbox"" disabled name=check checked> Outros - <b>" & RS("ds_probsaude") & "</b>" End If

    ShowHTML "    <tr valign=""top""><td><font size=1>Acompanhamento:<br>"
    If Nvl(RS("tp_neuro"),0)  = 0     Then ShowHTML "          <input type=""checkbox"" disabled name=check> Neurológico"  Else ShowHTML "          <input type=""checkbox"" disabled name=check checked> Neurológico"   End If
    If Nvl(RS("tp_cardio"),0) = 0     Then ShowHTML "          <input type=""checkbox"" disabled name=check> Cardiológico" Else ShowHTML "          <input type=""checkbox"" disabled name=check checked> Cardiológico" End If
    If Nvl(RS("tp_psico"),0)  = 0     Then ShowHTML "          <input type=""checkbox"" disabled name=check> Psicológico"  Else ShowHTML "          <input type=""checkbox"" disabled name=check checked> Psicológico" End If
    If IsNull(RS("ds_acompanhamento"))Then ShowHTML "          <input type=""checkbox"" disabled name=check> Outros"  Else ShowHTML "          <input type=""checkbox"" disabled name=check checked> Outros - <b>" & RS("ds_probsaude") & "</b>" End If

    ShowHTML "    <tr><td><font size=1>Alergia a alimentos:<br><b>" & Nvl(trim(RS("ds_alergia_aliment")),"Nada consta")
    ShowHTML "    <tr><td><font size=1>Alergia a medicamentos:<br><b>" & Nvl(trim(RS("ds_alergia_medicam")),"Nada consta")
    ShowHTML "    <tr><td><font size=1>Remédios controlados:<br><b>" & Nvl(trim(RS("ds_remedios")),"Nada consta")
    ShowHTML "    <tr><td><font size=1>Informações complementares:<br><b>"
    ShowHTML "    </table>"
    ShowHTML "  </table>"
     
  Elseif SG = "BOLETIM" Then
    DB_GetAlunoData RS, Session("periodo"), p_mat, SG
    Dim  w_resultado, w_total_faltas, w_media_curso
    RS.sort = "ds_ordem_imp"
    If Not RS.EOF Then
       ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
       ShowHTML "    <tr valign=""top"">"
       ShowHTML "      <td><font size=1>Turno:<br><b>" & Nvl(Trim(RS("co_turno")),"---")
       ShowHTML "      <td><font size=1>Série:<br><b>" & Nvl(trim(RS("descr_serie")),"---")
       ShowHTML "      <td><font size=1>Turma:<br><b>" & Nvl(trim(RS("co_letra_turma")),"---")
       ShowHTML "    <tr valign=""top"">"
       ShowHTML "      <td><font size=1>Bloco:<br><b>" & Nvl(Trim(RS("co_bloco")),"---")
       ShowHTML "      <td><font size=1>Sala:<br><b>" & Nvl(trim(RS("ds_sala")),"---")
       ShowHTML "      <td><font size=1>Modalidade:<br><b>" & Nvl(trim(RS("ds_curso")),"---")
       ShowHTML "    </table>"
    Else
       ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
       ShowHTML "    <tr valign=""top"">"
       ShowHTML "      <td colspan=5><font size=1>Turno:<br><b>---"
       ShowHTML "      <td colspan=4><font size=1>Série:<br><b>---"
       ShowHTML "      <td colspan=4><font size=1>Turma:<br><b>---"
       ShowHTML "    <tr valign=""top"">"
       ShowHTML "      <td colspan=5><font size=1>Bloco:<br><b>---"
       ShowHTML "      <td colspan=4><font size=1>Sala:<br><b>---"
       ShowHTML "      <td colspan=4><font size=1>Modalidade:<br><b>---"
       ShowHTML "    </table>"
    End If
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
          ShowHTML "      <td nowrap align=""left""><font size=1>" & Nvl(trim(lcase(RS("ds_disciplina"))),"---")
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
    ShowHTML "        <font size=""1"">AP - Aprovado&nbsp;&nbsp;&nbsp;&nbsp;"
    ShowHTML "        <font size=""1"">RP - Reprovado&nbsp;&nbsp;&nbsp;&nbsp;"
    ShowHTML "        <font size=""1"">CS - Cursando&nbsp;&nbsp;&nbsp;&nbsp;"
    ShowHTML "        <font size=""1"">NC - Não Consta&nbsp;&nbsp;&nbsp;&nbsp;</td>"
    'ShowHTML "  <tr  align=""right""><td colspan=13 align=""right""><a class=""SS"" HREF=""javascript:window.top.print();""><IMG SRC=""images/impressora.jpg"" title=""Clique aqui para imprimir esta página"" BORDER=0 aling=""right""></a></td></tr>"
    ShowHTML "    </table>"
    ShowHTML "  </table>"
    ShowHTML "</tr>"
                      
  Else
     ScriptOpen "JavaScript"
     ShowHTML " alert('Opção não disponível');"
     ShowHTML " history.back(1);"
     ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  ShowHTML "</body> "
  ShowHTML "</HTML> "

  Set p_mat        = Nothing

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
    Case "EXIBEALUNO"
       ExibeAluno
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

