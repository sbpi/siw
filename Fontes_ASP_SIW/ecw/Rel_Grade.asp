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
REM  /Servidor.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Gerencia a consulta de servidores
REM Mail     : alex@sbpi.com.br
REM Criacao  : 22/08/2003, 12:43
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
w_Pagina     = "Rel_Grade.asp?par="
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

  Dim p_funcionario
  Dim p_cpf, p_matricula, p_unidade, p_situacao
  Dim p_busca_serv
  Dim p_Ordena

  p_funcionario      = uCase(Request("p_funcionario"))
  p_unidade          = uCase(Request("p_unidade"))
  p_matricula        = uCase(Request("p_matricula"))
  p_cpf              = uCase(Request("p_cpf"))
  p_ordena           = uCase(Request("p_ordena"))
  p_busca_serv      = uCase(Request("p_busca_serv"))
  
  If O = "L" Then
     If p_funcionario > "" Then If p_busca_serv = "S" Then p_funcionario = "%" & p_funcionario & "%" Else p_funcionario = p_funcionario & "%" End If End If
     DB_GetFuncList RS, Session("periodo"), Session("regional"), p_cpf, null, p_matricula, p_unidade, p_funcionario, "S", null
     If p_ordena > "" Then RS.sort = p_ordena & ", ds_funcionario" Else RS.sort = "ds_funcionario" End If
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
        Validate "p_matricula", "Matrícula (SIGRH)", "1", "", "1", "8", "1", "1"
        Validate "p_cpf", "CPF", "CPF", "", "14", "14", "", "0123456789-."
        Validate "p_funcionario", "Nome", "1", "", "3", "40", "1", "1"
        Validate "P4", "Linhas por página", "1", "1", "1", "4", "", "0123456789"
        ShowHtml "  if (theForm.p_cpf.value == '' && theForm.p_matricula.value == '' && theForm.p_funcionario.value == '' && theForm.p_unidade.selectedIndex == 0) {"
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
  ShowHTML "function janelaGradeHTML(p_codigo) {"
  ShowHTML "  window.open('" & w_pagina & "ExibeGrade&R=" & w_Pagina & par & "&O=F&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_codigo=' + p_codigo ,'Servidor','top=30 left=20 width=750 height=500 scrollbars=yes resizable=yes');"
  ShowHTML "}"
  ShowHTML "function janelaGradeWORD(p_codigo) {"
  ShowHTML "  window.open('" & w_pagina & "ExibeGrade&R=" & w_Pagina & par & "&O=R&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_codigo=' + p_codigo ,'Servidor','top=30 left=20 width=750 height=500 menubar=yes resizable=yes');"
  ShowHTML "}"
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If InStr("IAE",O) > 0 Then
     If O = "E" Then
        BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
     Else
        BodyOpen "onLoad='document.Form.w_nu_matricula_mec.focus()';"
     End If
  ElseIf InStr("P",O) > 0 Then
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
    If p_cpf & p_matricula & p_unidade & p_funcionario & p_Ordena > "" Then
       ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_Dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_funcionario=" & p_funcionario & "&p_cpf=" & p_cpf & "&p_matricula=" & p_matricula & "&p_unidade=" & p_unidade & "&p_ordena=" & p_ordena & "&p_busca_serv=" &p_busca_serv& """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a></font>"
    Else
       ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_Dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_funcionario=" & p_funcionario & "&p_cpf=" & p_cpf & "&p_matricula=" & p_matricula & "&p_unidade=" & p_unidade & "&p_ordena=" & p_ordena & "&p_busca_serv=" &p_busca_serv& """><u>F</u>iltrar (Inativo)</a>"
    End If
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Matrícula SIGRH</font></td>"
    ShowHTML "          <td><font size=""1""><b>Nome do Servidor</font></td>"
    ShowHTML "          <td><font size=""1""><b>Unidade de Ensino</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=5 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF and RS.AbsolutePage = P3
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """>"
        ShowHTML "        <td align=""center"" nowrap><font size=""1"">" & Nvl(RS("nu_matricula_mec"),"---") & "</td>"
        ShowHTML "        <td><font size=""1"">" & lCase(RS("ds_funcionario")) & "</td>"
        ShowHTML "        <td><font size=""1"">" & Nvl(lCase(RS("ds_escola")),"---") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "           <a class=""HL"" HREF=""javascript:janelaGradeHTML('" & trim(RS("co_funcionario")) & "');"">Visualizar"
        ShowHTML "           <a class=""HL"" HREF=""javascript:janelaGradeWORD('" & trim(RS("co_funcionario")) & "');"">Gerar Word"
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
         
  ElseIf Instr("P",O) > 0 Then
    AbreForm "Form", w_Dir&w_Pagina&par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""70%"" border=""0"">"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    SelecaoPeriodoLetivo "Perío<u>d</u>o letivo:", "D", null, Session("periodo"), null, "periodo", null
    SelecaoRegional "<u>R</u>egional:", "R", null, Session("regional"), null, "regional", "informal = 'N'", "onChange=""document.Form.target=''; document.Form.O.value='P'; document.Form.submit();"""
    ShowHTML "          </table>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>M</U>atrícula (SIGRH):<br><INPUT ACCESSKEY=""M"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_matricula"" size=""8"" maxlength=""8"" value=""" & p_matricula & """></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>C</U>PF:<br><INPUT ACCESSKEY=""C"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_cpf"" size=""14"" maxlength=""14"" value=""" & p_cpf & """ onKeyPress=""FormataCPF(this, event);""></td>"
ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"    
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>N</U>ome servidor:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_funcionario"" size=""40"" maxlength=""40"" value=""" & p_funcionario & """></td>"
    ShowHTML "          <td align=""left""><font size=""1""><b>Buscar:</b>"
    If p_busca_serv = "S" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_busca_serv"" value=""N""> Iniciado em <input " & w_Disabled & " type=""radio"" name=""p_busca_serv"" value=""S"" checked> Qualquer parte &nbsp;&nbsp;&nbsp;"
    Else
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_busca_serv"" value=""N"" checked> Iniciado em <input " & w_Disabled & " type=""radio"" name=""p_busca_serv"" value=""S""> Qualquer parte &nbsp;&nbsp;&nbsp;"
    End If    
    ShowHTML "</table>"    
    ShowHTML "      <tr>"
    If Session("regional") = "00" or IsNull(Tvl(Session("regional"))) Then
       SelecaoUnidadeEnsino "<u>U</u>nidade de ensino:", "U", null, p_unidade, null, "p_unidade", null, null
    Else
       SelecaoUnidadeEnsino "<u>U</u>nidade de ensino:", "U", null, p_unidade, null, "p_unidade", "co_sigre like '" & Session("regional") & "*'", null
    End IF
    ShowHTML "      </tr>"
    ShowHTML "      <tr>"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY=""O"" " & w_Disabled & " class=""STS"" name=""p_ordena"" size=""1"">"
    If p_Ordena="NU_MATRICULA_MEC" Then
       ShowHTML "          <option value=""nu_matricula_mec"" SELECTED>Matrícula (SIGRH)<option value=""ds_funcionario"">Nome servidor<option value=""ds_escola"">Unidade de ensino"
    ElseIf p_Ordena="DS_ESCOLA" Then
       ShowHTML "          <option value=""nu_matricula_mec"">Matrícula (SIGRH)<option value=""ds_funcionario"">Nome servidor<option value=""ds_escola"" SELECTED>Unidade de ensino"
    Else
       ShowHTML "          <option value=""nu_matricula_mec"">Matrícula (SIGRH)<option value="""" SELECTED>Nome servidor<option value=""ds_escola"">Unidade de ensino"
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

  Set p_funcionario      = Nothing
  Set p_ordena           = Nothing

End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de exibição dos dados de alunos
REM -------------------------------------------------------------------------
Sub ExibeGrade

  Dim p_codigo
  Dim w_ds_unidade_ant
  Dim w_cont_ds
  p_codigo        = uCase(Request("p_codigo"))
  SG              = uCase(Request("SG"))
  If O = "R" Then
     HeaderWord null
     w_pag   = 1
     w_linha = 6
     CabecalhoWord w_cliente, "Matriz Horária", w_pag
  Else
     Cabecalho
     ShowHTML "<HEAD>"
     ShowHTML    "<TITLE>Professor - Matriz Horária</TITLE>"
     ShowHTML "</HEAD>"
     ShowHTML "<BASE HREF=""" & conRootSIW & """>"
     BodyOpenClean "onLoad=document.focus();"
     CabecalhoRelatorio w_cliente, "Matriz Horária"
     ExibeParametrosRel w_cliente
  End If
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  Dim i, w_dia_semana, w_nu_tempo, j
  DB_GetFuncData RS, Session("periodo"), p_codigo, SG
  RS.Sort = "nu_dia_semana, nu_tempo"
  If Not RS.EOF Then
     ShowHTML "<tr><td align=""center"" colspan=1>"
     ShowHTML "<tr valign=""top""><td bgcolor=""#FAEBD7""><table border=1 cellpadding=2 cellspacing=5 width=""100%"">"
     ShowHTML "  <tr valign=""top"">"
     ShowHTML "      <td><font size=1>Matrícula SIGRH: <font size=1><b>" & Nvl(Trim(RS("nu_matricula_mec")),"---")
     ShowHTML "      <td><font size=1>Nome do servidor: <font size=1><b>" & Nvl(trim(RS("ds_funcionario")),"---")
     ShowHTML "  <tr valign=""top"">"
     ShowHTML "      <td colspan=2><font size=1>Cargo: <font size=1><b>" & Nvl(trim(RS("ds_cargo")),"---")
     ShowHTML "    </table>"
     ShowHTML "</td></tr>"
  Else
     ShowHTML "<tr><td align=""center"" colspan=1>"
     ShowHTML "<tr valign=""top""><td bgcolor=""#FAEBD7""><table border=1 cellpadding=2 cellspacing=5 width=""100%"">"
     ShowHTML "  <tr valign=""top"">"
     ShowHTML "      <td><font size=1>Matrícula SIGRH:<font size=1>---"
     ShowHTML "      <td><font size=1>Nome do servidor:<font size=1>---"
     ShowHTML "  <tr valign=""top"">"
     ShowHTML "      <td colspan=2><font size=1>Cargo: <font size=1>---"
     ShowHTML "    <tr valign=""top"">"
     ShowHTML "    </table>"
     ShowHTML "</td></tr>"
  End If
  ShowHTML "<TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
  If RS.EOF Then
     ShowHTML "      <tr bgcolor=""" & conTrAlternateBgColor & """><td colspan=8 align=""center""><font size=""2""><b>Este servidor não possiu nenhuma Matriz horária cadastrada.</b></td></tr>"
  ElseIf not IsNull(RS("nu_dia_semana")) Then
     For i = 2 to 7 
        Select Case i
           Case 2
              w_dia_semana = "SEGUNDA"
           Case 3
              w_dia_semana = "TERÇA"
           Case 4
              w_dia_semana = "QUARTA"
           Case 5
              w_dia_semana = "QUINTA"
           Case 6
              w_dia_semana = "SEXTA"
           Case 7
              w_dia_semana = "SÁBADO"             
        End Select
        ShowHTML "<tr valign=""top"">"
        ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        ShowHTML "          <td rowspan=2 colspan=1 align=""center""><font size=""1""><b>Horário</font></td>"
        ShowHTML "          <td rowspan=1 colspan=7 align=""center""><font size=""1""><b>" & w_dia_semana & "</font></td>"
        ShowHTML "        </tr>"
        ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        ShowHTML "          <td rowspan=1 colspan=1 align=""center""><font size=""1""><b>T</font></td>"
        ShowHTML "          <td rowspan=1 colspan=1 align=""center""><font size=""1""><b>Série</font></td>"
        ShowHTML "          <td rowspan=1 colspan=1 align=""center""><font size=""1""><b>Turma</font></td>"
        ShowHTML "          <td rowspan=1 colspan=1 align=""center""><font size=""1""><b>BL</font></td>"
        ShowHTML "          <td rowspan=1 colspan=1 align=""center""><font size=""1""><b>SL</font></td>"
        ShowHTML "          <td rowspan=1 colspan=1 align=""center""><font size=""1""><b>Unidade</font></td>"
        ShowHTML "          <td rowspan=1 colspan=1 align=""center""><font size=""1""><b>Comp.Curricular</font></td>"
        ShowHTML "        </tr>"
        For w_nu_tempo = 1 to 10 
            If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If 
            ShowHTML "      <tr bgcolor=""" & w_cor & """>"
            If Not RS.EOF Then
               If cDbl(RS("nu_dia_semana")) = i and cDbl(RS("nu_tempo")) = w_nu_tempo Then
                  ShowHTML "      <td align=""center""><font size=1>" & RS("nu_tempo")
                  ShowHTML "      <td align=""center""><font size=1>" & Nvl(trim(RS("co_turno")),"---")
                  ShowHTML "      <td align=""center""><font size=1>" & Nvl(RS("sg_serie"),"---")
                  ShowHTML "      <td align=""center""><font size=1>" & Nvl(trim(RS("co_letra_turma")),"---")
                  ShowHTML "      <td align=""center""><font size=1>" & Nvl(RS("co_bloco"),"---")
                  ShowHTML "      <td align=""center""><font size=1>" & Nvl(trim(RS("co_sala")),"---")
                  ShowHTML "      <td align=""center""><font size=1>" & Nvl(RS("ds_escola"),"---")
                  ShowHTML "      <td align=""center""><font size=1>" & Nvl(trim(RS("co_disciplina")),"---")
                  RS.MoveNext
               Else
                  ShowHTML "      <td align=""center""><font size=1>" & w_nu_tempo
                  ShowHTML "      <td align=""center""><font size=1>---"
                  ShowHTML "      <td align=""center""><font size=1>---"
                  ShowHTML "      <td align=""center""><font size=1>---"
                  ShowHTML "      <td align=""center""><font size=1>---"
                  ShowHTML "      <td align=""center""><font size=1>---"
                  ShowHTML "      <td align=""center""><font size=1>---"
                  ShowHTML "      <td align=""center""><font size=1>---"
               End If
            Else
               ShowHTML "      <td align=""center""><font size=1>" & w_nu_tempo
               ShowHTML "      <td align=""center""><font size=1>---"
               ShowHTML "      <td align=""center""><font size=1>---"
               ShowHTML "      <td align=""center""><font size=1>---"
               ShowHTML "      <td align=""center""><font size=1>---"
               ShowHTML "      <td align=""center""><font size=1>---"
               ShowHTML "      <td align=""center""><font size=1>---"
               ShowHTML "      <td align=""center""><font size=1>---"
            End If
        Next
        w_cor = conTrBgColor
     Next
  Else
     ShowHTML "      <tr bgcolor=""" & conTrAlternateBgColor & """><td colspan=8 align=""center""><font size=""2""><b>Matriz horária não cadastrada.</b></td></tr>"
  End If
  ShowHTML "  </table>"
  ShowHTML "</tr>"
  If O = "F" Then
     ShowHTML "<tr valign=""top""><td colspan=""8"" >&nbsp;"
     ShowHTML "      <tr><td align=""center"" colspan=""8"" height=""1"" bgcolor=""#000000"">"
     ShowHTML "      <tr><td align=""center"" colspan=""8"">" 
     ShowHTMl "      </td></tr>"                
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  ShowHTML "</body> "
  ShowHTML "</HTML> "
  
  Set p_codigo        = Nothing

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
    Case "EXIBEGRADE"
       ExibeGrade
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

