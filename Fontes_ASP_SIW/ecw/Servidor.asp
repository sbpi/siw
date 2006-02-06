<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Geral.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Geral.asp" -->
<!-- #INCLUDE FILE="DB_Cargo.asp" -->
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
w_Pagina     = "Servidor.asp?par="
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

  Dim p_cargo
  Dim p_funcionario
  Dim p_cpf, p_matricula, p_unidade, p_situacao, p_prof, p_canc
  Dim p_busca_serv
  Dim p_Ordena

  p_funcionario      = uCase(Request("p_funcionario"))
  p_unidade          = uCase(Request("p_unidade"))
  p_matricula        = uCase(Request("p_matricula"))
  p_cpf              = uCase(Request("p_cpf"))
  p_prof             = uCase(Request("p_prof"))
  p_canc             = uCase(Request("p_canc"))
  p_cargo            = uCase(Request("p_cargo"))
  p_busca_serv       = uCase(Request("p_busca_serv"))
  p_ordena           = uCase(Request("p_ordena"))
  
  If O = "L" Then
     If p_funcionario > "" Then If p_busca_serv = "S" Then p_funcionario = "%" & p_funcionario & "%" Else p_funcionario = p_funcionario & "%" End If End If
     DB_GetFuncList RS, Session("periodo"), Session("regional"), p_cpf, p_cargo, p_matricula, p_unidade, p_funcionario, p_prof, p_canc
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
        ShowHtml "  if (theForm.p_cpf.value == '' && theForm.p_matricula.value == '' && theForm.p_funcionario.value == '' && theForm.p_unidade.selectedIndex == 0 && theForm.p_prof[2].checked && theForm.p_canc[2].checked && theForm.p_cargo.selectedIndex == 0) {"
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
  ShowHTML "function janelaServidor(p_codigo) {"
  ShowHTML "  window.open('" & w_pagina & "ExibeServidor&R=" & w_Pagina & par & "&O=F&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_codigo=' + p_codigo ,'Servidor','top=30 left=20 width=750 height=500 toolbar=no scrollbars=yes status=no address=no resizable=yes');"
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
    w_filter = ""
    If p_matricula > "" Then 
       w_filter = w_filter & "[Matr.: <b>" & p_matricula & "</b>]&nbsp;"
    End If
    If p_cpf > "" Then 
       w_filter = w_filter & "[CPF: <b>" & p_cpf & "</b>]&nbsp;"
    End If
    If p_funcionario > "" Then 
       w_filter = w_filter & "[Serv.: <b>" & p_funcionario & "</b>]&nbsp;"
    End If
    If p_unidade > "" Then 
       DB_GetSchoolList RS1, w_cliente
       RS1.Filter = "co_unidade = " & p_unidade
       w_filter = w_filter & " [Unid.: <b>" & RS1("ds_unidade") & "</b>]&nbsp;"
    End If    
    If p_cargo > "" Then 
       DB_GetPositionList RS1
       RS1.Filter = "co_cargo = '" & p_cargo & "'"
       w_filter = w_filter & " [Cargo: <b>" & RS1("ds_cargo") & "</b>]&nbsp;"
    End If        
    If p_prof = "S" Then 
       w_filter = w_filter & "[Exibir: <b>Apenas professores</b>]&nbsp;"
    End If
     If p_prof = "N" Then 
       w_filter = w_filter & "[Exibir: <b>Apenas não professores</b>]&nbsp;"
    End If
    If p_canc = "S" Then 
       w_filter = w_filter & "[Canc.: <b>Sim</b>]&nbsp;"
    End If
    If p_canc = "N" Then 
       w_filter = w_filter & "[Canc.: <b>Não</b>]&nbsp;"
    End If
    If w_filter > ""  Then ShowHTML "<tr bgcolor=""" & conTrAlternateBgColor & """><td colspan=7><font size=1><b>&nbsp;Filtro:&nbsp;</b>" & w_filter & "</font><BR>"
    ShowHTML "<tr><td><font size=""2"">"
    If p_cpf & p_matricula & p_unidade & p_funcionario & p_cargo & p_prof & p_canc & p_Ordena > "" Then
       ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_Dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_funcionario=" & p_funcionario & "&p_cpf=" & p_cpf & "&p_matricula=" & p_matricula & "&p_unidade=" & p_unidade & "&p_cargo=" & p_cargo & "&p_prof=" & p_prof & "&p_canc=" & p_canc & "&p_ordena=" & p_ordena & "&p_busca_serv=" &p_busca_serv& """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a></font>"
    Else
       ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_Dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_funcionario=" & p_funcionario & "&p_cpf=" & p_cpf & "&p_matricula=" & p_matricula & "&p_unidade=" & p_unidade & "&p_cargo=" & p_cargo & "&p_prof=" & p_prof & "&p_canc=" & p_canc & "&p_ordena=" & p_ordena & "&p_busca_serv=" &p_busca_serv& """><u>F</u>iltrar (Inativo)</a>"
    End If
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Matrícula SIGRH</font></td>"
    ShowHTML "          <td><font size=""1""><b>Nome do Servidor</font></td>"
    ShowHTML "          <td><font size=""1""><b>Unidade de Ensino</font></td>"
    ShowHTML "          <td><font size=""1""><b>Cargo</font></td>"
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
        ShowHTML "        <td title=""Clique para ver a ficha cadastral deste servidor.""><font size=""1""><a class=""HL"" HREF=""javascript:janelaServidor('" & trim(RS("co_funcionario")) & "');"">" & lCase(RS("ds_funcionario")) & "</a></td>"
        ShowHTML "        <td><font size=""1"">" & Nvl(lCase(RS("ds_escola")),"---") & "</td>"
        ShowHTML "        <td><font size=""1"">" & Nvl(lCase(RS("ds_cargo")),"---") & "</td>"
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
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>M</U>atrícula (SIGRH):<br><INPUT ACCESSKEY=""M"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_matricula"" size=""8"" maxlength=""8"" value=""" & p_matricula & """></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>C</U>PF:<br><INPUT ACCESSKEY=""C"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_cpf"" size=""14"" maxlength=""14"" value=""" & p_cpf & """ onKeyPress=""FormataCPF(this, event);""></td>"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%"">" 
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
       SelecaoUnidadeEnsino "<u>U</u>nidade de ensino", "U", null, p_unidade, null, "p_unidade", null, null
    Else
       SelecaoUnidadeEnsino "<u>U</u>nidade de ensino", "U", null, p_unidade, null, "p_unidade", "co_sigre like '" & Session("regional") & "*'", null
    End IF
    ShowHTML "      </tr>"
    ShowHTML "      <tr>"
    SelecaoCargo "Car<u>g</u>o", "T", null, p_cargo, null, "p_cargo", null
    ShowHTML "      </tr>"

    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    ShowHTML "          <td valign=""top""><font size=""1""><b>Exibir:</b>"
    If p_prof = "S" Then
       ShowHTML "              <br><input " & w_Disabled & " type=""radio"" name=""p_prof"" value=""S"" checked> Apenas professores <br><input " & w_Disabled & " type=""radio"" name=""p_prof"" value=""N""> Apenas não professores <br><input " & w_Disabled & " type=""radio"" name=""p_prof"" value=""""> Ambos"
    Elseif p_prof = "N" Then
       ShowHTML "              <br><input " & w_Disabled & " type=""radio"" name=""p_prof"" value=""S""> Apenas professores <br><input " & w_Disabled & " type=""radio"" name=""p_prof"" value=""N"" checked> Apenas não professores <br><input " & w_Disabled & " type=""radio"" name=""p_prof"" value=""""> Ambos"
    Else
       ShowHTML "              <br><input " & w_Disabled & " type=""radio"" name=""p_prof"" value=""S""> Apenas professores <br><input " & w_Disabled & " type=""radio"" name=""p_prof"" value=""N""> Apenas não professores <br><input " & w_Disabled & " type=""radio"" name=""p_prof"" value="""" checked> Ambos"
    End If
    ShowHTML "          </td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b>Exibe servidores cancelados?</b>"
    If p_canc = "S" Then
       ShowHTML "              <br><input " & w_Disabled & " type=""radio"" name=""p_canc"" value=""S"" checked> Sim <br><input " & w_Disabled & " type=""radio"" name=""p_canc"" value=""N""> Não <br><input " & w_Disabled & " type=""radio"" name=""p_canc"" value=""""> Tanto faz"
    Elseif p_canc = "N" Then
       ShowHTML "              <br><input " & w_Disabled & " type=""radio"" name=""p_canc"" value=""S""> Sim <br><input " & w_Disabled & " type=""radio"" name=""p_canc"" value=""N"" checked> Não <br><input " & w_Disabled & " type=""radio"" name=""p_canc"" value=""""> Tanto faz"
    Else
       ShowHTML "              <br><input " & w_Disabled & " type=""radio"" name=""p_canc"" value=""S""> Sim <br><input " & w_Disabled & " type=""radio"" name=""p_canc"" value=""N""> Não <br><input " & w_Disabled & " type=""radio"" name=""p_canc"" value="""" checked> Tanto faz"
    End If
    ShowHTML "          </td>"
    ShowHTML "      </table>"

    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY=""O"" " & w_Disabled & " class=""STS"" name=""p_ordena"" size=""1"">"
    If p_Ordena="DS_CARGO" Then
       ShowHTML "          <option value=""ds_cargo"" SELECTED>Cargo<option value=""nu_matricula_mec"">Matrícula (SIGRH)<option value=""ds_funcionario"">Nome servidor<option value=""ds_escola"">Unidade de ensino"
    ElseIf p_Ordena="NU_MATRICULA_MEC" Then
       ShowHTML "          <option value=""ds_cargo"">Cargo<option value=""nu_matricula_mec"" SELECTED>Matrícula (SIGRH)<option value=""ds_funcionario"">Nome servidor<option value=""ds_escola"">Unidade de ensino"
    ElseIf p_Ordena="DS_ESCOLA" Then
       ShowHTML "          <option value=""ds_cargo"">Cargo<option value=""nu_matricula_mec"">Matrícula (SIGRH)<option value=""ds_funcionario"">Nome servidor<option value=""ds_escola"" SELECTED>Unidade de ensino"
    Else
       ShowHTML "          <option value=""ds_cargo"">Cargo<option value=""nu_matricula_mec"">Matrícula (SIGRH)<option value="""" SELECTED>Nome servidor<option value=""ds_escola"">Unidade de ensino"
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

  Set p_cargo       = Nothing 
  Set p_funcionario = Nothing 
  Set p_cpf         = Nothing 
  Set p_matricula   = Nothing 
  Set p_unidade     = Nothing 
  Set p_situacao    = Nothing 
  Set p_prof        = Nothing 
  Set p_canc        = Nothing
  Set p_busca_serv  = Nothing

End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de exibição dos dados de alunos
REM -------------------------------------------------------------------------
Sub ExibeServidor

  Dim p_codigo
  Dim w_ds_unidade_ant
  Dim w_cont_ds
  p_codigo        = uCase(Request("p_codigo"))

  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  BodyOpenClean "onLoad=document.focus();"
  ShowHTML "<div align=center><center>"
  ShowHTML "<TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"

  DB_GetFuncData RS, Session("periodo"), p_codigo, "CABECALHO"
  ShowHTML "<tr valign=""top""><td bgcolor=""#FAEBD7""><table border=1 cellpadding=2 cellspacing=5 width=""100%"">"
  ShowHTML "  <tr valign=""top"">"
  ShowHTML "    <td><font size=1>Código:<br><b>" & RS("co_funcionario")
  ShowHTML "    <td colspan=5><font size=1>Nome:<br><b>" & RS("ds_funcionario")
  ShowHTML "  <tr valign=""top"">"
  ShowHTML "    <td><font size=1>Sexo:<br><b>" & Nvl(RS("tp_sexo"),"---")
  ShowHTML "    <td><font size=1>Apelido:<br><b>" & Nvl(trim(RS("ds_apelido")),"---")
  ShowHTML "    <td><font size=1>Matrícula (SIGRH):<br><b>" & Nvl(trim(RS("nu_matricula_mec")),"---")
  ShowHTML "    <td><font size=1>Lotação:<br><b>" & Nvl(trim(RS("lotacao_princ")),"---")
  ShowHTML "    <td><font size=1>Data da nascimento:<br><b>" & FormataDataEdicao(RS("dt_nascimento"))
  If trim(RS("tp_ano_letivo")) = "A" Then
     ShowHTML "    <td><font size=1>Ano:<br><b>" & Mid(Session("periodo"),1,4)
  Else
     ShowHTML "    <td><font size=1>Ano/Período:<br><b>" & Replace(Session("periodo"),Mid(Session("periodo"),5),"/"&Mid(Session("periodo"),5))
  End If
  ShowHTML "  </table>"
  ShowHTML "<tr valign=""top""><td><table border=1 cellpadding=1 cellspacing=0 width=""100%"">"
  ShowHTML "  <tr valign=""top"" align=""center"" bgcolor=""" & conTrBgColor & """>"
  ' Exibe os links abaixo apenas se for professor
  If trim(RS("id_professor")) = "S" Then
     If SG = "CADASTRO" or SG = "" Then
        ShowHTML "    <td width=""25%""><font class=""SS"" color=""#FF0000""><b>Cadastro "
     Else
        ShowHTML "    <td width=""25%""><font size=1><A class=""SS"" HREF=""" & w_dir & w_pagina & par & "&p_codigo=" & p_codigo & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&SG=CADASTRO"">Cadastro</a> "
     End If
     If SG = "DIVERSO" Then
        ShowHTML "    <td width=""25%""><font class=""SS"" color=""#FF0000""><b>Diversos "
     Else
        ShowHTML "    <td width=""25%""><font size=1><A class=""SS"" HREF=""" & w_dir & w_pagina & par & "&p_codigo=" & p_codigo & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&SG=DIVERSO"">Diversos</a> "
     End If
     If SG = "DISCIPLINA" Then
        ShowHTML "    <td width=""25%""><font class=""SS"" color=""#FF0000""><b>Componente Curricular "
     Else
        ShowHTML "    <td width=""25%""><font size=1><A class=""SS"" HREF=""" & w_dir & w_pagina & par & "&p_codigo=" & p_codigo & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&SG=DISCIPLINA"">Componente Curricular</a> "
     End If
     If SG = "GRADE" Then
        ShowHTML "    <td width=""25%""><font class=""SS"" color=""#FF0000""><b>Matriz Horária "
     Else
        ShowHTML "    <td width=""25%""><font size=1><A class=""SS"" HREF=""" & w_dir & w_pagina & par & "&p_codigo=" & p_codigo & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&SG=GRADE"">Matriz Horária</a> "
     End If
  Else
     If SG = "CADASTRO" or SG = "" Then
        ShowHTML "    <td width=""50%""><font class=""SS"" color=""#FF0000""><b>Cadastro "
     Else
        ShowHTML "    <td width=""50%""><font size=1><A class=""SS"" HREF=""" & w_dir & w_pagina & par & "&p_codigo=" & p_codigo & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&SG=CADASTRO"">Cadastro</a> "
     End If
     If SG = "DIVERSO" Then
        ShowHTML "    <td width=""50%""><font class=""SS"" color=""#FF0000""><b>Diversos "
     Else
        ShowHTML "    <td width=""50%""><font size=1><A class=""SS"" HREF=""" & w_dir & w_pagina & par & "&p_codigo=" & p_codigo & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&SG=DIVERSO"">Diversos</a> "
     End If
  End If
  
  ShowHTML "  </table>"
    
  If SG = "" or SG = "CADASTRO" Then
     ShowHTML "<tr valign=""top""><td><table border=0 cellpadding=0 cellspacing=0 width=""100%"">"
     ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
     ShowHTML "    <tr valign=""top"">"
     ShowHTML "      <td><font size=1>Escolaridade:<br><b>" & Nvl(trim(RS("ds_instrucao")),"---")
     ShowHTML "      <td><font size=1>Naturalidade:<br><b>" & Nvl(trim(RS("ds_naturalidade")),"---")
     ShowHTML "      <td><font size=1>UF:<br><b>" & Nvl(trim(RS("ds_uf_nascimento")),"---")
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
     ShowHTML "      <td><font size=1>Telefone:<br><b>" & Nvl(trim(RS("nu_telefone")),"---")
     ShowHTML "      <td><font size=1>Celular:<br><b>" & Nvl(trim(RS("nu_celular")),"---")
     ShowHTML "    </table>"
     ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
     ShowHTML "    <tr valign=""top"">"
     ShowHTML "      <td><font size=1>E-Mail:<br><b>" & Nvl(Trim(RS("ds_e_mail")),"---")
     ShowHTML "      <td><font size=1>Estado Civil:<br><b>" & Nvl(trim(RS("tp_estado_civil")),"---")
     ShowHTML "      <td><font size=1>Nome do cônjuge:<br><b>" & Nvl(Trim(RS("ds_conjuge")),"---")
     ShowHTML "    </table>"
     ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
     ShowHTML "    <tr valign=""top"">"
     ShowHTML "      <td><font size=1>Pai:<br><b>" & Nvl(Trim(RS("ds_pai")),"---")
     ShowHTML "      <td><font size=1>Mãe:<br><b>" & Nvl(trim(RS("ds_mae")),"---")
     ShowHTML "    </table>"
     ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
     ShowHTML "    <tr valign=""top"">"
     ShowHTML "      <td><font size=1>Nº do RG:<br><b>" & Nvl(trim(RS("nu_rg")),"---")
     ShowHTML "      <td><font size=1>Órgão Emissor:<br><b>" & Nvl(trim(RS("ds_orgao_emissor")),"---")
     If RS("dt_emissao") > "" Then
        ShowHTML "      <td><font size=1>Data de Emissão:<br><b>" & FormataDataEdicao(RS("dt_emissao"))
     Else
         ShowHTML "      <td><font size=1>Data de Emissão:<br><b>---"
     End If
     ShowHTML "    </table>"
     ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
     ShowHTML "    <tr valign=""top"">"
     ShowHTML "      <td><font size=1>CPF:<br><b>" & Nvl(Trim(RS("nu_cpf")),"---")
     ShowHTML "      <td><font size=1>Nº do Registro:<br><b>" & Nvl(trim(RS("nu_registro")),"---")
     ShowHTML "    </table>"
     ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
     ShowHTML "    <tr valign=""top""><td><font size=1>"
     If Trim(RS("st_cancelado")) = "S" Then
        ShowHTML "    <input disabled type=""checkbox"" name=""campo"" class=""sti"" checked> Cancelado"
     Else
        ShowHTML "    <input disabled type=""checkbox"" name=""campo"" class=""sti""> Cancelado"
     End If
     If Trim(RS("id_professor")) = "S" Then
        ShowHTML "    <br><input disabled type=""checkbox"" name=""campo"" class=""sti"" checked> Professor"
     Else
        ShowHTML "    <br><input disabled type=""checkbox"" name=""campo"" class=""sti""> Professor"
     End If
     ShowHTML "    </table>"
     ShowHTML "  </table>"
       
  Elseif SG = "DIVERSO" Then
     DB_GetFuncData RS, Session("periodo"), p_codigo, SG
     RS.Sort = "ds_escola"
     While Not RS.EOF
        ShowHTML "<tr valign=""top"">"
        ShowHTML "<td bgcolor="""&conTrAlternateBgColor&"""><font size=2>Unidade:<b>" & Nvl(RS("ds_escola"),"---")
        ShowHTML "<tr valign=""top"">"
        ShowHTML "<td><table border=0 cellpadding=0 cellspacing=0 width=""100%"">"
        ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
        ShowHTML "    <tr valign=""center"" align=""center"">"
        ShowHTML "      <td rowspan=2><font size=1><b>Cargo"
        ShowHTML "      <td rowspan=2><font size=1><b>Área Atuação"
        ShowHTML "      <td rowspan=2><font size=1><b>Admissão"
        ShowHTML "      <td rowspan=2><font size=1><b>Carga Horária"
        ShowHTML "      <td colspan=2><font size=1><b>Expediente"
        ShowHTML "      <td colspan=2><font size=1><b>Almoço"
        ShowHTML "    <tr valign=""top"" align=""center"">"
        ShowHTML "      <td><font size=1><b>Entrada"
        ShowHTML "      <td><font size=1><b>Saída"
        ShowHTML "      <td><font size=1><b>Início"
        ShowHTML "      <td><font size=1><b>Fim"
        ShowHTML "    <tr valign=""top"" align=""center"">"
        ShowHTML "      <td><font size=1>" & Nvl(Trim(RS("ds_cargo")),"---")
        ShowHTML "      <td><font size=1>" & Nvl(Trim(RS("ds_area_atuacao")),"---")
        ShowHTML "      <td><font size=1>" & Nvl(FormataDataEdicao(Trim(RS("dt_admissao"))),"---")
        ShowHTML "      <td><font size=1>" & Nvl(Trim(RS("nu_carga_contrato")),"---")
        ShowHTML "      <td><font size=1>" & Nvl(Trim(RS("nu_hora_entrada")),"---")
        ShowHTML "      <td><font size=1>" & Nvl(Trim(RS("nu_hora_saida")),"---")
        ShowHTML "      <td><font size=1>" & Nvl(Trim(RS("nu_hora_ini_almoc")),"---")
        ShowHTML "      <td><font size=1>" & Nvl(Trim(RS("nu_hora_fim_almoc")),"---")
        ShowHTML "    </table>"
        ShowHTML "  </table>"
        RS.MoveNext
     Wend
        
  Elseif SG = "DISCIPLINA" Then
     DB_GetFuncData RS, Session("periodo"), p_codigo, SG
     RS.Sort = "ds_escola"
     w_ds_unidade_ant = "-1"
     RS.MoveFirst
     While Not RS.EOF
        If w_ds_unidade_ant <> Nvl(RS("ds_escola"),"0") Then
           ShowHTML "<tr valign=""top"">"
           ShowHTML "<td bgcolor="""&conTrAlternateBgColor&"""><font size=2>Unidade:<b>" & Nvl(RS("ds_escola"),"---")
           ShowHTML "<tr valign=""top"">"
           ShowHTML "<td><table border=0 cellpadding=0 cellspacing=0 width=""100%"">"
           ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
           ShowHTML "    <tr valign=""top"" align=""center"">"
           ShowHTML "      <td><font size=1><b>Componente Curricular"
           ShowHTML "      <td><font size=1><b>Habilitado"
        End If
        w_ds_unidade_ant = Nvl(RS("ds_escola"),"---")
        ShowHTML "    <tr valign=""top"">"
        ShowHTML "      <td><font size=1>" & Nvl(RS("ds_disciplina"),"---")
        If IsNull(RS("st_habilitado")) Then
           ShowHTML "      <td align=""center""><font size=1>---"
        Else
           ShowHTML "      <td align=""center""><font size=1>" & Nvl(trim(RS("st_habilitado")),"NÃO")
        End If
        RS.MoveNext
     Wend       
     ShowHTML "    </table>"
     ShowHTML "  </table>"
   
  Elseif SG = "GRADE" Then
     Dim i, w_dia_semana, w_nu_tempo, j
     DB_GetFuncData RS, Session("periodo"), p_codigo, SG
     RS.Sort = "nu_dia_semana, nu_tempo"
     ShowHTML "  <tr valign=""top""><td><table border=1 cellpadding=2 cellspacing=0 width=""100%"">"
     If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrAlternateBgColor & """><td colspan=8 align=""center""><font size=""2""><b>Este servidor não possui nenhuma matriz horária cadastrada.</b></td></tr>"
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
           ShowHTML "    <tr valign=""top"">"
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
    Case "EXIBESERVIDOR"
       ExibeServidor
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

