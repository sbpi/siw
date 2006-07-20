<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Geral.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Geral.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /Responsavel.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Gerencia a consulta de responsáveis
REM Mail     : alex@sbpi.com.br
REM Criacao  : 21/08/2003, 15:20
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
w_Pagina     = "Responsavel.asp?par="
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

  Dim w_cpf
  Dim p_tipo_responsavel
  Dim w_ds_Aluno, p_ds_Aluno
  Dim p_cpf, p_responsavel, p_unidade, p_situacao
  Dim p_busca_aluno, p_busca_resp
  Dim p_Ordena

  p_ds_aluno         = uCase(Request("p_ds_Aluno"))
  p_unidade          = uCase(Request("p_unidade"))
  p_responsavel      = uCase(Request("p_responsavel"))
  p_cpf              = uCase(Request("p_cpf"))
  p_tipo_responsavel = uCase(Request("p_tipo_responsavel"))
  p_busca_aluno      = uCase(Request("p_busca_aluno"))
  p_busca_resp       = uCase(Request("p_busca_resp"))
  p_ordena           = uCase(Request("p_ordena"))
  
  If O = "L" Then
     If p_ds_aluno > ""    Then If p_busca_aluno = "S" Then p_ds_aluno = "%" & p_ds_aluno & "%"       Else p_ds_aluno = p_ds_aluno & "%"       End If End If
     If p_responsavel > "" Then If p_busca_resp = "S"  Then p_responsavel = "%" & p_responsavel & "%" Else p_responsavel = p_responsavel & "%" End If End If
     DB_GetAlunoList RS, Session("periodo"), Session("regional"), p_ds_aluno, p_responsavel, null, null, null, p_unidade, p_cpf, p_tipo_responsavel
     If p_ordena > "" Then RS.sort = p_ordena & ", ds_aluno" Else RS.sort = "ds_aluno" End If
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     FormataCPF
     Modulo
     ValidateOpen "Validacao"
     If O="P" Then
        Validate "periodo", "Período", "SELECT", "1", "1", "10", "1", "1"
        Validate "regional", "Regional", "SELECT", "", "1", "10", "1", "1"
        Validate "p_cpf", "CPF", "CPF", "", "14", "14", "", "0123456789-."
        Validate "p_ds_aluno", "Nome", "1", "", "3", "40", "1", "1"
        Validate "p_responsavel", "Responsável", "1", "", "3", "40", "1", "1"
        Validate "P4", "Linhas por página", "1", "1", "1", "4", "", "0123456789"
        ShowHtml "  if (theForm.p_cpf.value == '' && theForm.p_responsavel.value == '' && theForm.p_ds_aluno.value == '' && theForm.p_unidade.selectedIndex == 0) {"
        ShowHTML "     alert('Indique pelo menos um critério de filtragem!');"
        ShowHTML "     theForm.p_cpf.focus();"
        ShowHTML "     return false;"
        ShowHTML "  }"
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ScriptOpen "JavaScript"
  ShowHTML "function janelaAluno(p_matricula) {"
  ShowHTML "  window.open('" & "Aluno.asp?par=ExibeAluno&R=" & w_Pagina & par & "&O=F&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_mat=' + p_matricula ,'Aluno','top=10 left=30 width=750 height=500 toolbar=no scrollbars=yes status=no address=no resizable=yes');"
  ShowHTML "}"
  ShowHTML "function janelaResponsavel(p_responsavel) {"
  ShowHTML "  window.open('" & w_pagina & "ExibeResponsavel&R=" & w_Pagina & par & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_responsavel=' + p_responsavel ,'Responsavel','top=20 left=20 width=750 height=500 toolbar=no scrollbars=yes status=no address=no resizable=yes');"
  ShowHTML "}"
  ShowHTML "function selecionar(campo1, campo2){"
  ShowHTML "  if (campo2[1].checked){"
  ShowHTML "     alert('A busca em ""Qualquer parte"" só pode ser escolhida em uma das buscas!');"
  ShowHTML "     return campo1[0].checked = true;"
  ShowHTML "   }"
  ShowHTML "  }"
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If InStr("IAE",O) > 0 Then
     If O = "E" Then
        BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
     Else
        BodyOpen "onLoad='document.Form.w_ds_aluno.focus()';"
     End If
  ElseIf InStr("P",O) > 0 Then
     BodyOpen "onLoad='document.Form.p_cpf.focus()';"
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
    If p_cpf > "" Then 
       w_filter = w_filter & "[CPF: <b>" & p_cpf & "</b>]&nbsp;"
    End If
    If p_responsavel > "" Then 
       w_filter = w_filter & "[Resp.: <b>" & p_responsavel & "</b>]&nbsp;"
    End If
    If p_ds_aluno > "" Then 
       w_filter = w_filter & "[Aluno: <b>" & p_ds_aluno & "</b>]&nbsp;"
    End If
    If p_unidade > "" Then 
       DB_GetSchoolList RS1, w_cliente
       RS1.Filter = "co_unidade = " & p_unidade
       w_filter = w_filter & " [Unid.: <b>" & RS1("ds_unidade") & "</b>]&nbsp;"
    End If   
    If p_tipo_responsavel > "" Then 
       DB_GetResponKindList RS1, w_cliente
       RS1.Filter = "co_tip_responsavel = " & p_tipo_responsavel
       w_filter = w_filter & " [Tipo Resp.: <b>" & RS1("ds_tip_responsavel") & "</b>]&nbsp;"
    End If        
    If w_filter > ""  Then ShowHTML "<tr bgcolor=""" & conTrAlternateBgColor & """><td colspan=7><font size=1><b>&nbsp;Filtro:&nbsp;</b>" & w_filter & "</font><BR>"
    ShowHTML "<tr><td><font size=""2"">"
    If p_cpf & p_responsavel & p_unidade & p_ds_aluno & p_tipo_responsavel & p_Ordena > "" Then
       ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_Dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_ds_aluno=" & p_ds_aluno & "&p_cpf=" & p_cpf & "&p_responsavel=" & p_responsavel & "&p_unidade=" & p_unidade & "&p_ordena=" & p_ordena & "&p_busca_aluno=" &p_busca_aluno& "&p_busca_resp=" &p_busca_resp& "&p_tipo_responsavel=" &p_tipo_responsavel& """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a></font>"
    Else
       ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_Dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_ds_aluno=" & p_ds_aluno & "&p_cpf=" & p_cpf & "&p_responsavel=" & p_responsavel & "&p_unidade=" & p_unidade & "&p_ordena=" & p_ordena & "&p_busca_aluno=" &p_busca_aluno& "&p_busca_resp=" &p_busca_resp& "&p_tipo_responsavel=" &p_tipo_responsavel& """><u>F</u>iltrar (Inativo)</a>"
    End If
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>CPF</font></td>"
    ShowHTML "          <td><font size=""1""><b>Nome do Responsável</font></td>"
    ShowHTML "          <td><font size=""1""><b>Tipo do Responsável</font></td>"
    ShowHTML "          <td><font size=""1""><b>Unidade de Ensino</font></td>"
    ShowHTML "          <td><font size=""1""><b>Nome do Aluno</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=5 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF and RS.AbsolutePage = P3
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """>"
        ShowHTML "        <td align=""center"" nowrap><font size=""1"">" & Nvl(RS("nu_cpf"),"---") & "</td>"
        If IsNull(Tvl(RS("ds_responsavel"))) Then
           ShowHTML "        <td><font size=""1"">---</td>"
        Else
           ShowHTML "        <td title=""Clique para ver a ficha cadastral deste responsável.""><font size=""1""><a class=""HL"" HREF=""javascript:janelaResponsavel('" & trim(RS("co_responsavel")) & "');"">" & Nvl(lCase(RS("ds_responsavel")),"---") & "</a></td>"
        End If
        ShowHTML "        <td><font size=""1"">" & Nvl(lCase(RS("ds_tip_responsavel")),"---") & "</td>"
        ShowHTML "        <td><font size=""1"">" & Nvl(lCase(RS("ds_escola")),"---") & "</td>"
        ShowHTML "        <td title=""Clique para ver a ficha cadastral deste aluno.""><font size=""1""><a class=""HL"" HREF=""javascript:janelaAluno('" & trim(RS("co_aluno")) & "');"">" & Nvl(lCase(RS("ds_aluno")),"---") & "</a></td>"
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
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>C</U>PF:<br><INPUT ACCESSKEY=""C"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_cpf"" size=""14"" maxlength=""14"" value=""" & p_cpf & """ onKeyPress=""FormataCPF(this, event);""></td>"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%"">" 
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Nome <U>r</U>esponsável:<br><INPUT ACCESSKEY=""R"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_responsavel"" size=""40"" maxlength=""40"" value=""" & p_responsavel & """></td>"
    ShowHTML "          <td align=""left""><font size=""1""><b>Buscar:</b>"
    If p_busca_resp = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_busca_resp"" value=""N""> Iniciado em <input " & w_Disabled & " type=""radio"" name=""p_busca_resp"" value=""S"" checked onClick=""selecionar(p_busca_resp,p_busca_aluno)""> Qualquer parte &nbsp;&nbsp;&nbsp;"
    Else
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_busca_resp"" value=""N"" checked> Iniciado em <input " & w_Disabled & " type=""radio"" name=""p_busca_resp"" value=""S"" onClick=""selecionar(p_busca_resp,p_busca_aluno)""> Qualquer parte &nbsp;&nbsp;&nbsp;"
    End If    
    ShowHTML "</table>"    
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%"">"    
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>N</U>ome aluno:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_ds_aluno"" size=""40"" maxlength=""40"" value=""" & p_ds_aluno & """></td>"
    ShowHTML "          <td align=""left""><font size=""1""><b>Buscar:</b>"
    If p_busca_aluno = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_busca_aluno"" value=""N""> Iniciado em <input " & w_Disabled & " type=""radio"" name=""p_busca_aluno"" value=""S"" checked onClick=""selecionar(p_busca_aluno,p_busca_resp)""> Qualquer parte &nbsp;&nbsp;&nbsp;"
    Else
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_busca_aluno"" value=""N"" checked> Iniciado em <input " & w_Disabled & " type=""radio"" name=""p_busca_aluno"" value=""S"" onClick=""selecionar(p_busca_aluno,p_busca_resp)""> Qualquer parte &nbsp;&nbsp;&nbsp;"
    End If    
    ShowHTML "</table>"     
    ShowHTML "      <tr>"
    If Session("regional") = "00" or IsNull(Tvl(Session("regional"))) Then
       SelecaoUnidadeEnsino "<u>U</u>nidade de ensino", "U", null, p_unidade, null, "p_unidade", null, null
    Else
       SelecaoUnidadeEnsino "<u>U</u>nidade de ensino", "U", null, p_unidade, null, "p_unidade", "co_sigre like '" & Session("regional") & "*'", null
    End IF
    ShowHTML "      </tr>"
    ShowHTML "      <tr>"
    SelecaoTipoResponsavel "<u>T</u>ipo de responsável", "T", null, p_tipo_responsavel, null, "p_tipo_responsavel", null
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY=""O"" " & w_Disabled & " class=""STS"" name=""p_ordena"" size=""1"">"
    'If p_Ordena="cpf" Then
    '   ShowHTML "          <option value=""DS_ALUNO"">Nome aluno<option value=""ds_responsavel"">Nome responsável<option value=""ds_escola"">Unidade de ensino"
    If p_Ordena="DS_ALUNO" Then
       ShowHTML "          <option value=""DS_ALUNO"" SELECTED>Nome aluno<option value=""ds_responsavel"">Nome responsável<option value=""ds_escola"">Unidade de ensino"
    ElseIf p_Ordena="DS_ESCOLA" Then
       ShowHTML "          <option value=""DS_ALUNO"">Nome aluno<option value=""ds_responsavel"">Nome responsável<option value=""ds_escola"" SELECTED>Unidade de ensino"
    Else
       ShowHTML "          <option value=""DS_ALUNO"">Nome aluno<option value=""ds_responsavel"" SELECTED>Nome responsável<option value=""ds_escola"">Unidade de ensino"
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

  Set w_cpf              = Nothing
  Set w_ds_Aluno         = Nothing
  Set p_ds_Aluno         = Nothing
  Set p_tipo_responsavel = Nothing
  Set p_busca_aluno      = Nothing
  Set p_busca_resp       = Nothing
  Set p_ordena           = Nothing

End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de exibição dos dados do responsavel
REM -------------------------------------------------------------------------
Sub ExibeResponsavel

  Dim p_responsavel
  p_responsavel      = uCase(Request("p_responsavel"))

  If O = "L" Then
     DB_GetResponsData RS, Session("periodo"), p_responsavel
     RS.Sort = "ds_aluno"
     Cabecalho
     ShowHTML "<HEAD>"
     ShowHTML "<TITLE>Responsável - Cadastro</TITLE>"
     ScriptOpen "JavaScript"
     ShowHTML "function janelaAluno(p_matricula) {"
     ShowHTML "  window.open('" & "Aluno.asp?par=ExibeAluno&R=" & w_Pagina & par & "&O=F&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_mat=' + p_matricula ,'Aluno','top=10 left=30 width=750 height=500 toolbar=no scrollbars=yes status=no address=no resizable=yes');"
     ShowHTML "}"
     ScriptClose
     ShowHTML "</HEAD>"
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     BodyOpen "onLoad=document.focus();"
     ShowHTML "<div align=center><center>"
     ShowHTML "<TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
     ShowHTML "<tr valign=""top""><td><table border=0 cellpadding=0 cellspacing=0 width=""100%"">"
     ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
     ShowHTML "    <tr valign=""top"">"
     ShowHTML "      <td><font size=1>Nome:<br><b>" & RS("ds_responsavel")
     ShowHTML "      <td><font size=1>CPF:<br><b>" & Nvl(trim(RS("nu_cpf")),"---")
     ShowHTML "  </table>"
     ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
     ShowHTML "    <tr valign=""top"">"
     If IsNull(Tvl(RS("dt_nascimento"))) Then
        ShowHTML "      <td><font size=1>Data de nascimento:<br><b>---"
     Else
        ShowHTML "      <td><font size=1>Data de nascimento:<br><b>" & FormataDataEdicao(RS("dt_nascimento"))
     End If
     ShowHTML "      <td><font size=1>Sexo:<br><b>" & Nvl(trim(RS("tp_sexo")),"---")
     ShowHTML "      <td><font size=1>Naturalidade:<br><b>" & Nvl(trim(RS("ds_naturalidade")),"---")
     ShowHTML "      <td><font size=1>UF:<br><b>" & Nvl(trim(RS("ds_uf_nascimento")),"---")
     ShowHTML "      <td><font size=1>Tipo de Responsável:<br><b>" & Nvl(trim(RS("ds_tip_responsavel")),"---")
     ShowHTML "  </table>"
     ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
     ShowHTML "    <tr valign=""top"">"
     ShowHTML "      <td><font size=1>Telefone:<br><b>" & Nvl(trim(RS("nu_telefone")),"---")
     ShowHTML "      <td><font size=1>Celular:<br><b>" & Nvl(trim(RS("nu_celular")),"---")
     ShowHTML "      <td><font size=1>E-Mail:<br><b>" & Nvl(trim(RS("ds_e_mail")),"---")
     ShowHTML "      <td><font size=1>Escolaridade:<br><b>" & Nvl(trim(RS("ds_instrucao")),"---")
     ShowHTML "  </table>"
     ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
     ShowHTML "    <tr valign=""top"">"
     ShowHTML "      <td><font size=1>Nº do RG:<br><b>" & Nvl(trim(RS("nu_rg")),"---")
     ShowHTML "      <td><font size=1>Órgão Emissor:<br><b>" & Nvl(trim(RS("ds_orgao_emissor")),"---")
     If RS("dt_emissao") > "" Then
        ShowHTML "      <td><font size=1>Data de Emissão:<br><b>" & FormataDataEdicao(RS("dt_emissao"))
     Else
        ShowHTML "      <td><font size=1>Data de Emissão:<br><b>---"
     End If
     ShowHTML "      <td><font size=1>Nº de Dependentes:<br><b>" & Nvl(trim(RS("nu_dependentes")),"---")
     ShowHTML "      <td><font size=1>Renda Familiar:<br><b>" & FormatNumber(Nvl(trim(RS("vl_renda_familiar")),"0"),2)
     ShowHTML "  </table>"
     ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
     ShowHTML "    <tr valign=""top"">"
     ShowHTML "      <td><font size=1>Profissão:<br><b>" & Nvl(Trim(RS("ds_profissao")),"---")
     ShowHTML "      <td><font size=1>Empresa/Local de Trabalho:<br><b>" & Nvl(trim(RS("ds_local_trab")),"---")
     ShowHTML "  </table>"
     ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
     ShowHTML "    <tr valign=""top"">"
     ShowHTML "      <td><font size=1>Endereço do trabalho:<br><b>" & Nvl(Trim(RS("ds_endereco_trab")),"---")
     ShowHTML "      <td><font size=1>Bairro:<br><b>" & Nvl(trim(RS("ds_bairro_trab")),"---")
     ShowHTML "  </table>"
     ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
     ShowHTML "    <tr valign=""top"">"
     ShowHTML "      <td><font size=1>Cidade:<br><b>" & Nvl(Trim(RS("ds_cidade_trab")),"---")
     ShowHTML "      <td><font size=1>UF:<br><b>" & Nvl(trim(RS("ds_uf_cidade_trab")),"---")
     ShowHTML "      <td><font size=1>CEP:<br><b>" & Nvl(trim(RS("nu_cep_trab")),"---")
     ShowHTML "      <td><font size=1>Telefone Com.:<br><b>" & Nvl(trim(RS("nu_telefone_trab")),"---")
     ShowHTML "      <td><font size=1>Ramal:<br><b>" & Nvl(trim(RS("nu_ramal_trab")),"---")
     ShowHTML "  </table>"
     ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
     ShowHTML "    <tr valign=""top"">"
     ShowHTML "      <td><font size=1>Endereço para correspondência:<br><b>" & Nvl(Trim(RS("ds_endereco")),"---")
     ShowHTML "      <td><font size=1>Bairro:<br><b>" & Nvl(trim(RS("ds_bairro")),"---")
     ShowHTML "  </table>"
     ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
     ShowHTML "    <tr valign=""top"">"
     ShowHTML "      <td><font size=1>Cidade:<br><b>" & Nvl(Trim(RS("ds_cidade")),"---")
     ShowHTML "      <td><font size=1>UF:<br><b>" & Nvl(trim(RS("ds_uf_cidade")),"---")
     ShowHTML "      <td><font size=1>CEP:<br><b>" & Nvl(trim(RS("nu_cep")),"---")
     ShowHTML "  </table>"
     ShowHTML "  <tr valign=""top""><td align=""center"" bgcolor=""" & conTrBgColor & """><font size=1><b>ALUNOS VINCULADOS AO RESPONSÁVEL"
     ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
     ShowHTML "    <tr valign=""top"" align=""center"">"
     ShowHTML "      <td><font size=1><b>Nome do aluno</b></font></td>"
     ShowHTML "      <td><font size=1><b>Data de Nascimento</b></font></td>"
     ShowHTML "      <td><font size=1><b>Unidade de Ensino</b></font></td>"
     While Not RS.EOF
        ShowHTML "    <tr valign=""top"">"
        ShowHTML "      <td title=""Clique para ver a ficha cadastral deste aluno.""><font size=""1""><a class=""HL"" HREF=""javascript:janelaAluno('" & trim(RS("co_aluno")) & "');"">" & RS("ds_aluno") & "</a></td>"
        ShowHTML "      <td align=""center""><font size=1>" & Nvl(FormataDataEdicao(trim(RS("nasc_aluno"))),"---")
        ShowHTML "      <td><font size=1>" & Nvl(trim(RS("ds_escola")),"---")
        RS.MoveNext
     Wend
     ShowHTML "  </table>"
     ShowHTML "</table>"
     ShowHTML "</center>"
     ShowHTML "</body> "
     ShowHTML "</HTML> "
  End If

  Set p_responsavel      = Nothing

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
    Case "EXIBERESPONSAVEL"
       ExibeResponsavel
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

