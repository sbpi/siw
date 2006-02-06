<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Matriz.asp" -->
<!-- #INCLUDE FILE="DML_Matriz.asp" -->
<!-- #INCLUDE FILE="DB_Tipo_Curso.asp" -->
<!-- #INCLUDE FILE="DB_Serie.asp" -->
<!-- #INCLUDE FILE="DB_Tipo_Disciplina.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /Matriz_Curricular.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Gerencia a atualização das tabelas de matriz curricular
REM Mail     : alex@sbpi.com.br
REM Criacao  : 25/08/2003, 10:00
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
w_Pagina     = "Matriz_Curricular.asp?par="
w_Dir        = "ecw/"
w_Disabled   = "ENABLED"

If P1 = "" Then P1 = 0           Else P1 = cDbl(P1) End if
If P3 = "" Then P3 = 1           Else P3 = cDbl(P3) End If
If P4 = "" Then P4 = conPageSize Else P4 = cDbl(P4) End If

If O = "" Then O = "L" End If

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
  Case "V" 
     w_TP = TP & " - Vincular Séries"
  Case "M" 
     w_TP ="<div align=""right""><a class=""SS"" HREF=""javascript:window.print();""><IMG SRC=""images/impressora.jpg"" title=""Clique aqui para imprimir esta página"" BORDER=0></a></div>" & TP & " - Listagem"
  Case Else
     w_TP = TP & " - Listagem"
End Select

w_cliente         = RetornaCliente()
  
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
REM Rotina da tabela de matriz curricular
REM -------------------------------------------------------------------------
Sub Matriz

  Dim w_co_grade_curric
  Dim p_co_tipo_curso, w_co_tipo_curso  
  Dim w_ano
  Dim w_dt_grade
  Dim w_turno
  Dim w_nu_grade, p_nu_grade
  Dim w_ds_grade, p_ds_grade
  Dim w_nu_semanas
  Dim p_Ordena
  Dim p_codigo
  Dim w_cont_serie
  Dim w_ds_tipo_curso
  Dim w_teste

  p_ds_grade         = uCase(Request("p_ds_grade"))
  p_co_tipo_curso    = Request("p_co_tipo_curso")
  p_codigo           = Request("p_codigo")
  p_nu_grade         = Request("p_nu_grade")
  p_ordena           = uCase(Request("p_ordena"))
  
  If O = "L" Then
     DB_GetMatrixList RS
     If p_ds_grade & p_codigo & p_co_tipo_curso & p_nu_grade > "" Then
        w_filter = ""
        If p_ds_grade      > ""   Then w_filter = w_filter & " and ds_grade like '*" & p_ds_grade & "*'"      End If
        If p_codigo        > ""   Then w_filter = w_filter & " and co_grade_curric = " & cDbl(p_codigo)       End If
        If p_co_tipo_curso > ""   Then w_filter = w_filter & " and co_tipo_curso   = " & cDbl(p_co_tipo_curso)End If
        If p_nu_grade      > ""   Then w_filter = w_filter & " and nu_grade like '*" & p_nu_grade & "*'"      End If
        RS.Filter = Mid(w_filter,6,255)
     End If
     If p_ordena > "" Then RS.sort = p_ordena Else RS.sort = "ds_grade" End If
  ElseIf InStr("AEVM",O) > 0  Then
     w_co_grade_curric = Request("w_co_grade_curric")
     DB_GetMatrixData RS, w_co_grade_curric
     w_co_tipo_curso = cDbl(RS("co_tipo_curso"))
     w_ano           = RS("ano")
     w_dt_grade      = FormataDataEdicao(RS("dt_grade"))
     w_turno         = RS("turno")
     w_nu_grade      = RS("nu_grade")
     w_ds_grade      = RS("ds_grade")
     w_nu_semanas    = RS("nu_semanas")
     w_ds_tipo_curso = RS("ds_tipo_curso")
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     CheckBranco
     FormataData
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_co_tipo_curso", "Modalidade de ensino", "SELECT", "1", "1", "50", "1", "1"
        Validate "w_ano", "Ano", "1", "1", "1", "4", "1", ""
        Validate "w_turno", "Turno", "1", "1", "1", "2", "1", ""
        Validate "w_dt_grade", "Data Matriz", "DATA", "", "10", "10", "", "1"
        Validate "w_nu_semanas", "Nº de semanas", "1", "", "1", "2", "", "1"
        Validate "w_nu_grade", "Nº da matriz", "1", "", "1", "15", "1", "1"
        Validate "w_ds_grade", "Nome", "1", "", "3", "40", "1", "1"
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     ElseIf O = "E" Then
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
        ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
        ShowHTML "     { return (true); }; "
        ShowHTML "     { return (false); }; "
     ElseIf O="P" Then
        'Validate "p_codigo", "Código", "1", "", "1", "50", "", "1"
        Validate "p_co_tipo_curso", "Modalidade de ensino", "SELECT", "", "1", "50", "1", "1"
        Validate "p_nu_grade", "números de semanas", "1", "", "1", "15", "1", "1"
        Validate "p_ds_grade", "nome", "1", "", "3", "40", "1", "1"
        Validate "P4", "Linhas por página", "1", "1", "1", "4", "", "0123456789"
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ScriptOpen "JavaScript"
  ShowHTML "function janelaSeries(p_co_grade_curric, p_co_tipo_curso) {"
  ShowHTML "  window.open('" & w_Pagina & "ExibeSeries&R=" & w_Pagina & par & "&O=F&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_co_grade_curric=' + p_co_grade_curric + ' " & "&p_co_tipo_curso=' + p_co_tipo_curso ,'Series','top=10 left=30 width=750 height=500 toolbar=no scrollbars=auto status=no address=no resizable=yes');"
  ShowHTML "}"
  ShowHTML "function janelaDisciplinas(p_co_grade_curric,p_sg_serie) {"
  ShowHTML "  window.open('" & w_Pagina & "ExibeDisciplinas&R=" & w_Pagina & par & "&O=F&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_co_grade_curric=' + p_co_grade_curric + ' " & "&p_sg_serie=' + p_sg_serie ,'Disciplinas','top=10 left=30 width=750 height=500 toolbar=no scrollbars=no status=no address=no resizable=yes');"
  ShowHTML "}"
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If InStr("IAE",O) > 0 Then
     If O = "E" Then
        BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
     Else
        BodyOpen "onLoad='document.Form.w_co_tipo_curso.focus()';"
     End If
  ElseIf InStr("P",O) > 0 Then
     BodyOpen "onLoad='document.Form.p_co_tipo_curso.focus()';"
  Else
     BodyOpen "onLoad=document.focus();"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    w_filter = ""
    If p_co_tipo_curso > "" Then 
       DB_GetCourseTypeList RS1
       RS1.Filter = "co_tipo_curso = " & p_co_tipo_curso
       w_filter = w_filter & " [Mod.: <b>" & RS1("ds_tipo_curso") & "</b>]&nbsp;"
    End If
    If p_ds_grade > "" Then 
       w_filter = w_filter & "[Descrição: <b>" & p_ds_grade & "</b>]&nbsp;"
    End If
    If p_nu_grade > "" Then 
       w_filter = w_filter & "[Nº Matriz: <b>" & p_nu_grade & "</b>]&nbsp;"
    End If
    If w_filter > ""  Then ShowHTML "<tr bgcolor=""" & conTrAlternateBgColor & """><td colspan=5><font size=1><b>&nbsp;Filtro:&nbsp;</b>" & w_filter & "</font><BR>"      
    ShowHTML "<tr><td><font size=""2"">"
    If P1 <> 1 Then
       ShowHTML "                            <a accesskey=""I"" class=""SS"" href=""" & w_Dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_codigo=" & p_codigo & "&p_co_tipo_curso=" & p_co_tipo_curso & "&p_nu_grade=" & p_nu_grade & "&p_ds_grade=" & p_ds_grade & "&p_ordena=" & p_ordena & """><u>I</u>ncluir</a>&nbsp;"
    End If
    If p_codigo & p_ds_grade & p_co_tipo_curso & p_nu_grade & p_Ordena > "" Then
       ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_Dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_codigo=" & p_codigo & "&p_ordena=" & p_ordena & "&p_co_tipo_curso=" & p_co_tipo_curso & "&p_nu_grade=" & p_nu_grade & "&p_ds_grade=" & p_ds_grade & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a></font>"
    Else
       ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_Dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_codigo=" & p_codigo & "&p_ordena=" & p_ordena & "&p_co_tipo_curso=" & p_co_tipo_curso & "&p_nu_grade=" & p_nu_grade & "&p_ds_grade=" & p_ds_grade & """><u>F</u>iltrar (Inativo)</a>"
    End If
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    'ShowHTML "          <td><font size=""2""><b>Chave</font></td>"
    ShowHTML "          <td><font size=""2""><b>Modalidade de Ensino</font></td>"
    ShowHTML "          <td><font size=""2""><b>Descrição</font></td>"
    ShowHTML "          <td><font size=""2""><b>Ano</font></td>"
    ShowHTML "          <td><font size=""2""><b>Turno</font></td>"
    ShowHTML "          <td><font size=""2""><b>Data</font></td>"
    ShowHTML "          <td><font size=""2""><b>Nº Semanas</font></td>"
    ShowHTML "          <td><font size=""2""><b>Nº Matriz</font></td>"
    ShowHTML "          <td width=""20%""><font size=""2""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=10 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF and RS.AbsolutePage = P3
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """>"
        'ShowHTML "        <td align=""center""><font size=""1"">" & RS("co_grade_curric") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("ds_tipo_curso") & "</td>"        
        ShowHTML "        <td><font size=""1"">" & RS("ds_grade") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("ano") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("turno") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & FormataDataEdicao(RS("dt_grade")) & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("nu_semanas") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("nu_grade") & "</td>"
        If P1 <> 1 Then
           ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
           ShowHTML "          <A class=""HL"" HREF=""" & w_Dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_co_grade_curric=" & RS("co_grade_curric") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_codigo=" & p_codigo & "&p_ds_grade=" & p_ds_grade & "&p_co_tipo_curso=" & p_co_tipo_curso & "&p_nu_grade=" & p_nu_grade & "&p_ordena=" & p_ordena & """>Alterar</A>&nbsp"
           ShowHTML "          <A class=""HL"" HREF=""" & w_Dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_co_grade_curric=" & RS("co_grade_curric") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_codigo=" & p_codigo & "&p_ds_grade=" & p_ds_grade & "&p_co_tipo_curso=" & p_co_tipo_curso & "&p_nu_grade=" & p_nu_grade & "&p_ordena=" & p_ordena & """>Excluir</A>&nbsp"
           ShowHTML "          <A class=""HL"" HREF=""" & w_Dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=V&w_co_grade_curric=" & RS("co_grade_curric") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_codigo=" & p_codigo & "&p_ds_grade=" & p_ds_grade & "&p_co_tipo_curso=" & p_co_tipo_curso & "&p_nu_grade=" & p_nu_grade & "&p_ordena=" & p_ordena & """>Séries</A>&nbsp"
           ShowHTML "        </td>"
        Else
           ShowHTML "        <td align=""center"" nowrap><font size=""1"">"
           ShowHTML "          <A class=""HL"" HREF=""" & w_Dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=M&w_co_grade_curric=" & RS("co_grade_curric") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_codigo=" & p_codigo & "&p_ds_grade=" & p_ds_grade & "&p_co_tipo_curso=" & p_co_tipo_curso & "&p_nu_grade=" & p_nu_grade & "&p_ordena=" & p_ordena & """>Exibir</A>&nbsp"
           ShowHTML "        </td>"
        End If
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
  ElseIf Instr("IAE",O) > 0 Then
    If O = "E" Then
       w_Disabled = "DISABLED"
    End If
    AbreForm "Form", w_Dir&w_Pagina&"Grava", "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""p_ds_grade"" value=""" & p_ds_grade &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_co_tipo_curso"" value=""" & p_co_tipo_curso &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_nu_grade"" value=""" & p_nu_grade &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_codigo"" value=""" & p_codigo &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_ordena"" value=""" & p_ordena &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_co_grade_curric"" value=""" & w_co_grade_curric &""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""70%"" border=""0"">"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""90%"" border=""0"">"            
    SelecaoModEnsino "<u>M</u>odalidade de ensino:", "M", null, w_co_tipo_curso, null, "w_co_tipo_curso", null,null
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>A</U>no:<br><INPUT ACCESSKEY=""A"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_ano"" size=""4"" maxlength=""4"" value=""" & w_ano & """></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>T</U>urno:<br><INPUT ACCESSKEY=""T"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_turno"" size=""2"" maxlength=""2"" value=""" & w_turno & """></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>D</U>ata Matriz:<br><INPUT ACCESSKEY=""T"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_dt_grade"" size=""10"" maxlength=""10"" value=""" & w_dt_grade & """ onkeydown=""FormataData(this,event)""></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Nº <U>S</U>emanas:<br><INPUT ACCESSKEY=""S"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_nu_semanas"" size=""5"" maxlength=""15"" value=""" & w_nu_semanas & """></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Nº da <U>M</U>atriz:<br><INPUT ACCESSKEY=""M"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_nu_grade"" size=""5"" maxlength=""15"" value=""" & w_nu_grade & """></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>D</U>escrição:<br><INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_ds_grade"" size=""40"" maxlength=""40"" value=""" & w_ds_grade & """></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""STI"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    If O = "E" Then
       ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Gravar"">"
    End If
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_co_tipo_curso=" & p_co_tipo_curso & "&p_nu_grade=" & p_nu_grade & "&p_ds_grade=" & p_ds_grade & "&p_codigo=" & p_codigo & "&p_ordena=" & p_ordena & "';"" name=""Botao"" value=""Cancelar"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  ElseIf Instr("P",O) > 0 Then
    AbreForm "Form", w_Dir&w_Pagina&par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""70%"" border=""0"">"
    SelecaoModEnsino "<u>M</u>odalidade de ensino:", "M", null, p_co_tipo_curso, null, "p_co_tipo_curso", null,null    
    ShowHTML "      </tr>"
    'ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>C</U>have:<br><INPUT ACCESSKEY=""C"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_codigo"" size=""4"" maxlength=""4"" value=""" & p_codigo & """></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>D</U>escrição:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_ds_grade"" size=""40"" maxlength=""40"" value=""" & p_ds_grade & """></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Nº <U>M</U>atriz:<br><INPUT ACCESSKEY=""M"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_nu_grade"" size=""4"" maxlength=""4"" value=""" & p_nu_grade & """></td>"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY=""O"" " & w_Disabled & " class=""STS"" name=""p_ordena"" size=""1"">"
    If p_Ordena = "CO_TIPO_CURSO" Then
       ShowHTML "          <option value="""">Descrição"
       ShowHTML "          <option value=""CO_TIPO_CURSO"" SELECTED>Modalidade de ensino"
    Else
       ShowHTML"           <option value="""" SELECTED>Descrição"
       ShowHTML "          <option value=""CO_TIPO_CURSO"">Modalidade de ensino"
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
  ElseIf Instr("V",O) > 0 Then
    w_disabled = "DISABLED"
    AbreForm "Form", w_Dir&w_Pagina&"Grava", "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""p_ds_grade"" value=""" & p_ds_grade &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_co_tipo_curso"" value=""" & p_co_tipo_curso &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_nu_grade"" value=""" & p_nu_grade &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_codigo"" value=""" & p_codigo &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_ordena"" value=""" & p_ordena &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_co_grade_curric"" value=""" & w_co_grade_curric &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_co_tipo_curso"" value=""" & w_co_tipo_curso &""">"
    
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""90%"" border=""0"">"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""90%"" border=""0"">"            
    ShowHTML "      <tr><td colspan=5 valign=""top""><font size=""1""><b><U>M</U>odalidade de Ensino:<br><SELECT ACCESSKEY=""M""  DISABLED  class=""STS"" name=""w_co_tipo_curso"" size=""1"">"
    ShowHTML "          <OPTION VALUE="""">---"
    DB_GetCourseTypeList RS
    While Not RS.EOF
       If w_co_tipo_curso = cDbl(RS("co_tipo_curso")) Then
          ShowHTML "          <OPTION VALUE=""" & RS("co_tipo_curso") & """ SELECTED>" & RS("ds_tipo_curso")
       Else
          ShowHTML "          <OPTION VALUE=""" & RS("co_tipo_curso") & """>" & RS("ds_tipo_curso")
       End If
       RS.MoveNext
    Wend
    DesconectaBD
    ShowHTML "          </SELECT></td>"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td colspan=1 valign=""top""><font size=""1""><b><U>A</U>no:<br><INPUT ACCESSKEY=""A"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_ano"" size=""4"" maxlength=""4"" value=""" & w_ano & """></td>"
    ShowHTML "          <td colspan=1 valign=""top""><font size=""1""><b><U>T</U>urno:<br><INPUT ACCESSKEY=""T"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_turno"" size=""2"" maxlength=""2"" value=""" & w_turno & """></td>"
    ShowHTML "          <td colspan=1 valign=""top""><font size=""1""><b><U>D</U>ata Matriz:<br><INPUT ACCESSKEY=""T"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_dt_grade"" size=""10"" maxlength=""10"" value=""" & w_dt_grade & """ onkeydown=""FormataData(this,event)""></td>"
    ShowHTML "          <td colspan=1 valign=""top""><font size=""1""><b>Nº <U>S</U>emanas:<br><INPUT ACCESSKEY=""S"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_nu_semanas"" size=""5"" maxlength=""15"" value=""" & w_nu_semanas & """></td>"
    ShowHTML "          <td colspan=1 valign=""top""><font size=""1""><b>Nº da <U>M</U>atriz:<br><INPUT ACCESSKEY=""G"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_nu_grade"" size=""5"" maxlength=""15"" value=""" & w_nu_grade & """></td>"
    ShowHTML "      <tr><td colspan=5 valign=""top""><font size=""1""><b><U>D</U>escrição:<br><INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_ds_grade"" size=""40"" maxlength=""40"" value=""" & w_ds_grade & """></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""5"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""5"">"
    ShowHTML "      <tr><td align=""center"" colspan=""5""><b><font size=""2"">Séries vinculadas à Matriz Curricular"
    ShowHTML "      <tr><td align=""left"" colspan=""5""><a title=""Clique aqui para incluir séries à esta matriz curricular"" accesskey=""I"" class=""SS"" HREF=""javascript:janelaSeries('" & trim(w_co_grade_curric) & "','" & trim(w_co_tipo_curso) & "');""><u>I</u>ncluir</a>&nbsp;"    
    DB_GetMatrixSerieData RS, w_co_grade_curric
    RS.sort = "co_tipo_curso, sg_serie"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrAlternateBgColor & """><td colspan=5 align=""center""><font size=""2""><b>Não foi encontrado nenhum registro.</b></td></tr>"
    Else
       While Not RS.EOF
          If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
          ShowHTML "      <tr bgcolor=""" & w_cor & """>"
          ShowHTML "        <td colspan=4><font size=""1"">" &RS("sg_serie")& " - " & RS("descr_serie") & "</td>"
          If P1 <> 1 Then
             ShowHTML "        <td width=""35%"" align=""center"" nowrap><font size=""1"">"
             ShowHTML "          <A class=""HL"" HREF=""" & w_Dir & w_Pagina & "Grava" & "&R=" & w_Pagina & par & "&O=E&w_sg_serie=" & RS("sg_serie") & "&p_co_grade_curric=" & trim(RS("co_grade_curric")) & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & "SERIES" & "&p_codigo=" & p_codigo & "&p_ds_grade=" & p_ds_grade & "&p_co_tipo_curso=" & p_co_tipo_curso & "&p_nu_grade=" & p_nu_grade & "&p_ordena=" & p_ordena & """ onClick=""return confirm('Confirma a exclusão desta série?')"">Excluir</A>&nbsp"
             ShowHTML "          <A class=""HL"" HREF=""javascript:janelaDisciplinas('" & trim(w_co_grade_curric) & "', '"& trim(RS("sg_serie")) &"');"" title=""Clique aqui para vincular os componetes curriculares à série"">Componentes Curriculares</A>&nbsp"
             ShowHTML "        </td>"
          End If
          ShowHTML "      </tr>"
          RS.MoveNext
       wend
    End If
    ShowHTML "      <tr><td align=""right"" colspan=5><font size=""1""><b>Registros: " & RS.RecordCount
    DesconectaBD
    ShowHTML "    </table>"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"    
    ShowHTML "      <tr><td align=""center"" colspan=""5"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""5"">" 
    ShowHTML "      <tr><td align=""center"" colspan=""5"">"    
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_co_tipo_curso=" & p_co_tipo_curso & "&p_nu_grade=" & p_nu_grade & "&p_ds_grade=" & p_ds_grade & "&p_codigo=" & p_codigo & "&p_ordena=" & p_ordena & "';"" name=""Botao"" value=""Voltar"">"       
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  ElseIf Instr("M",O) > 0 Then
    ShowHTML "<tr><td><font size=""1"">"
    ShowHTML "          <A class=""SS"" HREF=""javascript:history.back(1);"">Voltar</A>&nbsp"
    ShowHTML "<table border=1  bgcolor=""#FAEBD7"" width=""100%"">"
    ShowHTML "        <tr valign=""top"">"
    ShowHTML "          <td><font size=""1"">Matriz curricular:<br><b>" &w_ds_grade & "</font></td>"
    ShowHTML "          <td><font size=""1"">Turno:<br><b>" & w_turno & "</font></td>"
    ShowHTML "          <td><font size=""1"">Ano:<br><b>" & w_ano & "</font></td>"
    ShowHTML "          <td><font size=""1"">Data da matriz:<br><b>" & w_dt_grade & "</font></td>"
    ShowHTML "        <tr valign=""top"">"
    ShowHTML "          <td colspan=2><font size=""1"">Modalidade de ensino:<br><b>" & w_ds_tipo_curso& "</font></td>"
    ShowHTML "          <td><font size=""1"">Nº Semanas:<br><b>" & w_nu_semanas & "</font></td>"
    ShowHTML "          <td><font size=""1"">Nº da Matriz:<br><b>" & w_nu_grade & "</font></td>"
    ShowHTML "    </table>"
    DB_GetMatrixSerieData RS, w_co_grade_curric
    RS.sort = "sg_serie"
    If RS.EOF Then
        ShowHTML "      <tr><td colspan=10>&nbsp;</td></tr>"
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=10 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      While Not RS.EOF
        ShowHTML "      <tr><td colspan=10>&nbsp;</td></tr>"
        ShowHTML "      <tr bgcolor=""" & conTrAlternateBgColor & """>"
        ShowHTML "        <td colspan=10><font size=""2""><b>Série: " & RS("sg_serie") & " - " & RS("descr_serie") &  "</td>"        
        DB_GetMatrixDisciplineData RS1, w_co_grade_curric, RS("sg_serie")
        ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
        ShowHTML "        <td align=""center"" colspan=10><font size=""2""><b>Componentes Curriculares</b></td>"        
        ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
        ShowHTML "          <td><font size=""1""><b>Sigla</font></td>"
        ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
        ShowHTML "          <td><font size=""1""><b>Tipo</font></td>"
        ShowHTML "          <td><font size=""1""><b>CH</font></td>"
        ShowHTML "          <td><font size=""1""><b>Ordem</font></td>"
        ShowHTML "          <td><font size=""1""><b>Avaliação</font></td>"
        ShowHTML "          <td><font size=""1""><b>Digitação</font></td>"
        ShowHTML "          <td><font size=""1""><b>Impressao</font></td>"
        ShowHTML "          <td><font size=""1""><b>Reprova</font></td>"
        ShowHTML "        </tr>"
        If RS1.EOF Then
           ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=10 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
        Else
           While Not RS1.EOF 
              'If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
              ShowHTML "      <tr bgcolor=""" & conTrBgColor & """>"
              ShowHTML "        <td><font size=""1"">" & RS1("sg_disciplina") & "</td>"        
              ShowHTML "        <td><font size=""1"">" & RS1("ds_tipo_disciplina") & "</td>"
              ShowHTML "        <td align=""center""><font size=""1"">" & RS1("tp_disciplina") & "</td>"
              ShowHTML "        <td align=""center""><font size=""1"">" & RS1("carga_horaria_sem") & "</td>"
              ShowHTML "        <td align=""center""><font size=""1"">" & RS1("nu_ordem_imp") & "</td>"
              ShowHTML "        <td align=""center""><font size=""1"">" & RS1("tp_avaliacao") & "</td>"
              ShowHTML "        <td align=""center""><font size=""1"">" & RS1("tp_digitacao") & "</td>"
              ShowHTML "        <td align=""center""><font size=""1"">" & RS1("tp_impressao") & "</td>"
              ShowHTML "        <td align=""center""><font size=""1"">" & RS1("st_reprova") & "</td>"
              ShowHTML "      </tr>"
              RS1.MoveNext
           wend
        End If  
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "</tr>"
    DesConectaBD         
  Else
    ScriptOpen "JavaScript"
    ShowHTML " alert('Opção não disponível');"
    ShowHTML " history.back(1);"
    ScriptClose
  End If
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape

  Set w_co_grade_curric = Nothing
  Set w_ds_grade        = Nothing
  Set p_ds_grade        = Nothing
  Set w_co_tipo_curso   = Nothing
  Set p_co_tipo_curso   = Nothing
  Set w_ano             = Nothing
  Set w_nu_semanas      = Nothing
  Set w_nu_grade        = Nothing
  Set p_nu_grade        = Nothing
  Set p_codigo          = Nothing
  Set p_ordena          = Nothing

End Sub
REM =========================================================================
REM Fim da tabela de Matriz Curricular
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de exibição das series
REM -------------------------------------------------------------------------
Sub ExibeSeries

  Dim p_co_grade_curric
  Dim p_ano, p_turno, p_co_tipo_curso
  Dim w_ds_unidade_ant
  Dim w_cont_ds
  p_co_grade_curric        = cDbl(Request("p_co_grade_curric"))
  p_co_tipo_curso          = cDbl(Request("p_co_tip_curso"))
  
  DB_GetMatrixData RS, p_co_grade_curric
  p_ano           = RS("ano")
  p_turno         = RS("turno")
  p_co_tipo_curso = RS("co_tipo_curso")
  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML    "<TITLE>Séries - Selecionar</TITLE>"
  ScriptOpen "JavaScript"     
  ShowHTML "function checkform(){"
  ShowHTML "var message="""";"
  ShowHTML "var a=0, b, c="""";"
  ShowHTML "b=document.Form.w_serie_box.length;"
  ShowHTML "b=b-1;"
  ShowHTML "while (a<=b){if (document.Form.w_serie_box[a].checked==true){"
  ShowHTML "c="""";"
  ShowHTML "break;"
  ShowHTML "}else{"
  ShowHTML "c=c+""a"";}"
  ShowHTML "a++;}"
  ShowHTML "if (c.length>0){"
  ShowHTML "message=message+""Selecione pelo menos um registro\n""};"
  ShowHTML "if (message.length>0){"
  ShowHTML "alert(message);"
  ShowHTML "return (false);}"
  ShowHTML "else{return (true);}"
  ShowHTML "}"
  ShowHTML "var checkflag = ""false"";"
  ShowHTML "function check(field) {"
  ShowHTML "if (checkflag == ""false"") {"
  ShowHTML "for (i = 0; i < field.length; i++) {"
  ShowHTML "field[i].checked = true;}"
  ShowHTML "checkflag = ""true"";"
  ShowHTML " }"
  ShowHTML "else {"
  ShowHTML "for (i = 0; i < field.length; i++) {"
  ShowHTML "field[i].checked = false; }"
  ShowHTML "checkflag = ""false"";"
  ShowHTML "}"
  ShowHTML "}"
  ShowHTML "function fechar(){"
  ShowHTML "window.top.opener.location.reload();"
  ShowHTML "window.top.close();"
  ShowHTML "}"
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  BodyOpen "onLoad=document.focus();"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=1  bgcolor=""#FAEBD7"" width=""100%"">"
  ShowHTML "        <tr valign=""top"">"
  ShowHTML "          <td><font size=""1"">Matriz curricular:<br><b>" & RS("ds_grade") & "</font></td>"
  ShowHTML "          <td><font size=""1"">Turno:<br><b>" & RS("turno") & "</font></td>"
  ShowHTML "          <td><font size=""1"">Ano:<br><b>" & RS("ano") & "</font></td>"
  ShowHTML "        <tr valign=""top"">"
  ShowHTML "          <td colspan=3><font size=""1"">Modalidade de ensino:<br><b>" & RS("ds_tipo_curso")& "</font></td>"
  ShowHTML "    </table>"
  DesconectaBD
  DB_GetMatrixSerieList RS,p_co_grade_curric, p_co_tipo_curso
  RS.sort = "co_tipo_curso, sg_serie"
  AbreForm "Form", w_Dir&w_Pagina&"Grava", "POST", "return(checkform(this));", null, P1,P2,P3,P4,TP,"SERIES",R,"L"
  ShowHTML "<INPUT type=""hidden"" name=""p_ano"" value=""" & p_ano &""">"
  ShowHTML "<INPUT type=""hidden"" name=""p_turno"" value=""" & p_turno &""">"
  ShowHTML "<INPUT type=""hidden"" name=""p_co_tipo_curso"" value=""" & p_co_tipo_curso &""">"
  ShowHTML "<INPUT type=""hidden"" name=""p_co_grade_curric"" value=""" & p_co_grade_curric &""">"
  
  ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"     
  If RS.EOF Then
     ShowHTML "      <tr bgcolor=""" & conTrAlternateBgColor & """><td colspan=5 align=""center""><font size=""2""><b>Não foi encontrado nenhum registro</b></td></tr>"
  Else
     ShowHTML "<tr><td align=""center"" colspan=5>"
     ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
     ShowHTML "          <td align=""center""><font size=""1""><input type=""checkbox"" name=""w_serie_box_all"" onClick=""this.value=check(this.form.w_serie_box)"">"
     ShowHTML "          <td><font size=""1""><b>Sigla</font></td>"
     ShowHTML "          <td><font size=""1""><b>Modalidade de ensino</font></td>"
     ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
     While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """>"      
        ShowHTML "      <td align=""center""><font size=1><input type=""checkbox"" name=""w_serie_box"" value="""&RS("sg_serie")&""">"    
        ShowHTML "      <td><font size=1>" & Nvl(trim(RS("sg_serie")),"---")
        ShowHTML "      <td><font size=1>" & Nvl(RS("ds_tipo_curso"),"---")
        ShowHTML "      <td><font size=1>" & Nvl(trim(RS("descr_serie")),"---")
        RS.MoveNext
     Wend
  End If              
  ShowHTML "      </table>"
  ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
  ShowHTML "      <tr><td align=""center"" colspan=""3"">"
  If RS.RecordCount > 1 Then
     ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Gravar"">"
  Else
     ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Gravar"" Disabled>"
  End If
  ShowHTML "            <input class=""STB"" type=""button"" onClick=""fechar();"" name=""Botao"" value=""Cancelar"">"
  ShowHTML "          </td>"
  ShowHTML "      </tr>"
  ShowHTML "    </table>"
  ShowHTML "    </TD>"
  ShowHTML "</tr>"
  ShowHTML "</FORM>"
  ShowHTML "</table>"
  ShowHTML "</center>"
  ShowHTML "</body> "
  ShowHTML "</HTML> "
  
  Set p_co_grade_curric        = Nothing

End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de exibição das componentes curriculares(disciplina)
REM -------------------------------------------------------------------------
Sub ExibeDisciplinas

  Dim p_co_grade_curric, p_sg_serie, p_co_tipo_disciplina
  Dim p_ano, p_turno, p_co_tipo_curso
  Dim w_ds_unidade_ant
  Dim w_cont_ds
  Dim w_cont_disciplina
  p_co_grade_curric        = cDbl(trim(Request("p_co_grade_curric")))
  p_sg_serie               = uCase(Request("p_sg_serie"))
  
  DB_GetMatrixSerieOneData RS, p_co_grade_curric, p_sg_serie
  p_ano           = RS("ano")
  p_turno         = RS("turno")
  p_co_tipo_curso = RS("co_tipo_curso")
  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML    "<TITLE>Componentes curriculares - Listagem</TITLE>"
  ScriptOpen "JavaScript"
  ShowHTML "function janelaSelecDiscA(p_co_grade_curric,p_co_tipo_disciplina) {"
  ShowHTML "  window.open('" & w_Pagina & "SelecDisciplina&R=" & w_Pagina & par & "&O=A&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_sg_serie=" &p_sg_serie& "&p_co_grade_curric=' + p_co_grade_curric + ' " & "&p_co_tipo_disciplina=' + p_co_tipo_disciplina ,'SelecDisciplinas','top=70 left=200 width=620 height=300 toolbar=no scrollbars=no status=no address=no resizable=yes');"
  ShowHTML "}"
  ShowHTML "function janelaSelecDiscI(p_co_grade_curric) {"
  ShowHTML "  window.open('" & w_Pagina & "SelecDisciplina&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_sg_serie=" &p_sg_serie& "&p_co_grade_curric=' + p_co_grade_curric,'SelecDisciplinas','top=70 left=200 width=620 height=300 toolbar=no scrollbars=no status=no address=no resizable=yes');"
  ShowHTML "}"
  ShowHTML "function janelaSelecDiscE(p_co_grade_curric,p_co_tipo_disciplina) {"
  ShowHTML "  window.open('" & w_Pagina & "SelecDisciplina&R=" & w_Pagina & par & "&O=E&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_sg_serie=" &p_sg_serie& "&p_co_grade_curric=' + p_co_grade_curric + ' " & "&p_co_tipo_disciplina=' + p_co_tipo_disciplina ,'SelecDisciplinas','top=70 left=200 width=620 height=300 toolbar=no scrollbars=no status=no address=no resizable=yes');"
  ShowHTML "}"  
  ShowHTML "function fechar(){"
  ShowHTML "window.top.opener.location.reload();"
  ShowHTML "window.top.close();"
  ShowHTML "}"        
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  BodyOpen "onLoad=document.focus();"
  ShowHTML "<div align=center><center>"
  ShowHTML "<TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>" 
  ShowHTML "<table border=1  bgcolor=""#FAEBD7"" width=""100%"">"
  ShowHTML "        <tr valign=""top"">"
  ShowHTML "          <td colspan=3><font size=""1"">Matriz curricular:<br><b>" & RS("ds_grade") & "</font></td>"
  ShowHTML "          <td><font size=""1"">Turno:<br><b>" & RS("turno") & "</font></td>"
  ShowHTML "          <td><font size=""1"">Ano:<br><b>" & RS("ano") & "</font></td>"
  ShowHTML "        <tr valign=""top"">"
  ShowHTML "          <td colspan=3><font size=""1"">Modalidade de ensino:<br><b>" & RS("ds_tipo_curso")& "</font></td>"
  ShowHTML "          <td colspan=2><font size=1>Série:<br><b>"&RS("sg_serie")& "-" & RS("descr_serie")
  ShowHTML "</table>"
  AbreForm "Form", w_Dir&w_Pagina&"Grava", "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,R,O
  ShowHTML "<INPUT type=""hidden"" name=""p_co_tipo_curso"" value=""" & p_co_tipo_curso &""">"
  ShowHTML "<INPUT type=""hidden"" name=""p_co_grade_curric"" value=""" & p_co_grade_curric &""">" 
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "    <table width=""90%"" border=""0"">"
  ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
  ShowHTML "    <table width=""90%"" border=""0"">"            
  ShowHTML "      <tr><td align=""center"" colspan=""5""><b><font size=""2"">Componentes curriculares vinculados à Matriz Curricular"
  ShowHTML "      <tr><td align=""left"" colspan=""5""><a title=""Clique aqui para incluir os componentes curriculares à esta matriz curricular"" accesskey=""I"" class=""SS"" HREF=""javascript:janelaSelecDiscI('" & trim(p_co_grade_curric) & "');""><u>I</u>ncluir</a>&nbsp;"    
  DB_GetMatrixDisciplineData RS, p_co_grade_curric, p_sg_serie
  RS.sort = "co_tipo_curso, sg_serie"
  If RS.EOF Then
     ShowHTML "      <tr bgcolor=""" & conTrAlternateBgColor & """><td colspan=5 align=""center""><font size=""2""><b>Não há nenhuma série vinculada.</b></td></tr>"
  Else
     While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """>"
        ShowHTML "        <td colspan=4><font size=""1"">" & RS("sg_disciplina") & " - " & RS("ds_tipo_disciplina") & "</td>"
        ShowHTML "        <td width=""20%"" align=""center"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""javascript:janelaSelecDiscA('" & trim(p_co_grade_curric) & "','" & RS("co_tipo_disciplina") & "');"">Alterar</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""javascript:janelaSelecDiscE('" & trim(p_co_grade_curric) & "','" & RS("co_tipo_disciplina") & "');"">Excluir</A>&nbsp"
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        RS.MoveNext
     wend
  End If
  ShowHTML "      <tr><td align=""right"" colspan=5><font size=""1""><b>Registros: " & RS.RecordCount
  DesconectaBD
  ShowHTML "    </table>"
  ShowHTML "          </td>"
  ShowHTML "      </tr>"    
  ShowHTML "      <tr><td align=""center"" colspan=""5"" height=""1"" bgcolor=""#000000"">"
  ShowHTML "      <tr><td align=""center"" colspan=""5"">" 
  ShowHTML "      <tr><td align=""center"" colspan=""5"">"    
  ShowHTML "            <input class=""STB"" type=""button"" onClick=""fechar();"" name=""Botao"" value=""Fechar"">"       
  ShowHTML "    </table>"
  ShowHTML "    </TD>"
  ShowHTML "</tr>"
  ShowHTML "</FORM>"
  ShowHTML "</table>"
  ShowHTML "</center>"
  Rodape
  ShowHTML "</table>"
  ShowHTML "</center>"
  ShowHTML "</body> "
  ShowHTML "</HTML> "
  
  Set p_co_grade_curric        = Nothing

End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina de exibição das series
REM -------------------------------------------------------------------------
Sub SelecDisciplina

  Dim p_co_grade_curric, p_co_tipo_disciplina, p_sg_serie
  Dim p_ano, p_turno, p_co_tipo_curso
  Dim w_carga_horaria_sem, w_nu_ordem_imp
  Dim w_tp_disciplina, w_tp_avaliacao, w_tp_digitacao, w_tp_impressao, w_st_reprova
  Dim w_selected1, w_selected2, w_selected3, w_selected4
  
  p_co_grade_curric        = cDbl(Request("p_co_grade_curric"))
  p_co_tipo_disciplina     = Tvl(Request("p_co_tipo_disciplina"))
  p_sg_serie               = trim(Request("p_sg_serie"))
  
  If O = "A" or O = "E" Then
     DB_GetMatrixDisciplineOneData RS, p_co_grade_curric, p_co_tipo_disciplina, p_sg_serie
     w_tp_disciplina       = trim(RS("tp_disciplina"))
     w_carga_horaria_sem   = RS("carga_horaria_sem")
     w_nu_ordem_imp        = RS("nu_ordem_imp")
     w_tp_avaliacao        = trim(RS("tp_avaliacao"))
     w_tp_digitacao        = trim(RS("tp_digitacao"))
     w_tp_impressao        = trim(RS("tp_impressao"))
     w_st_reprova          = trim(RS("st_reprova"))
     DesconectaBD
  End If
  
  DB_GetMatrixSerieOneData RS, p_co_grade_curric, p_sg_serie
  
  
  Cabecalho
  ShowHTML "<HEAD>"
  ShowHTML    "<TITLE>Componente curricular - Selecionar</TITLE>"  
  ScriptOpen "JavaScript"
  ShowHTML "function fechar(){"
  ShowHTML "window.top.opener.location.reload();"
  ShowHTML "window.top.close();"
  ShowHTML "}"
  ValidateOpen "Validacao"
  If O = "I" or O = "A" Then
     If O = "I" Then
       Validate "w_co_tipo_disciplina", "Componente curricular", "SELECT", "1", "1", "50", "1", "1"
     End If
     Validate "w_tp_disciplina", "Tipo de componente curricular", "SELECT", "1", "1", "50", "1", "1"
     Validate "w_carga_horaria_sem", "Carga horária semanal", "1", "1", "1", "10", "1", ""
     Validate "w_nu_ordem_imp", "Ordem de impressão", "1", "1", "1", "10", "1", ""
     Validate "w_tp_avaliacao", "Tipo de avaliação", "SELECT", "1", "1", "50", "1", "1"
     Validate "w_tp_digitacao", "Tipo de digitação", "SELECT", "1", "1", "50", "1", "1"
     Validate "w_tp_impressao", "Tipo de impressão", "SELECT", "1", "1", "50", "1", "1"  
     Validate "w_st_reprova", "Reprova", "SELECT", "1", "1", "50", "1", "1"  
     Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
  ElseIf O = "E" Then
     Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
     ShowHTML "     { return (true); }; "
     ShowHTML "     { return (false); }; "
  End If
  ShowHTML "  theForm.Botao[0].disabled=true;"
  ShowHTML "  theForm.Botao[1].disabled=true;"
  ValidateClose
  ScriptClose
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""" & conRootSIW & """>"
  If O = "I" Then
     BodyOpen "onLoad=document.Form.w_co_tipo_disciplina.focus();"
  ElseIf O = "E" Then
     BodyOpen "onLoad=document.Form.w_assinatura.focus();"
  ElseIf O = "A" Then
     BodyOpen "onLoad=document.Form.w_tp_disciplina.focus();"
  Else 
     BodyOpen "onLoad=document.focus();"
  End If
  ShowHTML "<div align=center><center>"
  ShowHTML "<TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""0"" CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
  ShowHTML "<tr><td align=""center"" colspan=4>"
  ShowHTML "<table border=1  bgcolor=""#FAEBD7"" width=""100%"">"
  ShowHTML "        <tr valign=""top"">"
  ShowHTML "          <td colspan=3><font size=""1"">Matriz curricular:<br><b>" & RS("ds_grade") & "</font></td>"
  ShowHTML "          <td colspan=1><font size=""1"">Turno:<br><b>" & RS("turno") & "</font></td>"
  ShowHTML "          <td colspan=1><font size=""1"">Ano:<br><b>" & RS("ano") & "</font></td></tr>"
  ShowHTML "        <tr valign=""top"">"
  ShowHTML "          <td colspan=3><font size=""1"">Modalidade de ensino:<br><b>" & RS("ds_tipo_curso")& "</font></td>"
  ShowHTML "          <td colspan=2><font size=1>Série:<br><b>"&RS("sg_serie")& "-" & RS("descr_serie")& "</font></td></tr>"
  ShowHTML "</table>"
  ShowHTML "<tr><td align=""center"" colspan=4>"
  AbreForm "Form", w_Dir&w_Pagina&"Grava", "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,"DISCIPLINAS",R,O
  ShowHTML "<INPUT type=""hidden"" name=""p_co_grade_curric"" value=""" & p_co_grade_curric &""">"
  ShowHTML "<INPUT type=""hidden"" name=""p_sg_serie"" value=""" & p_sg_serie &""">"
  ShowHTML "<INPUT type=""hidden"" name=""p_turno"" value=""" & RS("turno") &""">"
  ShowHTML "<INPUT type=""hidden"" name=""p_ano"" value=""" & RS("ano") &""">"
  ShowHTML "<INPUT type=""hidden"" name=""p_co_tipo_curso"" value=""" & RS("co_tipo_curso") &""">"
  If O = "E" or O = "A" Then
       ShowHTML "<INPUT type=""hidden"" name=""w_co_tipo_disciplina"" value=""" & p_co_tipo_disciplina &""">"
       If O = "E" Then
          w_Disabled = "DISABLED"
       End If
  End If
  DesconectaBD
  If O = "I" Then
     ShowHTML "      <tr><td colspan=1><font size=""1""><b><U>C</U>omponente curricular:<td colspan=3><SELECT ACCESSKEY=""C"" " & w_Disabled & " class=""STS"" name=""w_co_tipo_disciplina"" size=""1"">"
  ElseIf O = "A" or O = "E" Then
     ShowHTML "      <tr><td colspan=1><font size=""1""><b><U>C</U>omponente curricular:<td colspan=3><SELECT ACCESSKEY=""C"" " & w_Disabled & " class=""STS"" name=""co_tipo_disciplina"" size=""1"" Disabled>"
  End If
  ShowHTML "          <OPTION VALUE="""">---"
  DB_GetDisciplineTypeList RS
  RS.sort = "ds_tipo_disciplina"
  If p_co_tipo_disciplina > "" Then
     p_co_tipo_disciplina = cDbl(p_co_tipo_disciplina)
  End If
  While Not RS.EOF
     If p_co_tipo_disciplina = cDbl(RS("co_tipo_disciplina")) Then
        ShowHTML "          <OPTION VALUE=""" & RS("co_tipo_disciplina") & """ SELECTED>" & RS("ds_tipo_disciplina")
     Else
        ShowHTML "          <OPTION VALUE=""" & RS("co_tipo_disciplina") & """>" & RS("ds_tipo_disciplina")
    End If
    RS.MoveNext
  Wend
  DesconectaBD
  ShowHTML "          </SELECT></td>"
  ShowHTML "      </tr>"
  ShowHTML "      <tr><td colspan=1><font size=""1""><b><U>T</U>ipo de componente curricular:<td colspan=3><SELECT ACCESSKEY=""T"" " & w_Disabled & " class=""STS"" name=""w_tp_disciplina"" size=""1"">"
  ShowHTML "          <OPTION VALUE="""">---"
  Select Case w_tp_disciplina
     Case "BASE NACIONAL COMUM"
        w_selected1 = "SELECTED"
     Case "PARTE DIVERSIFICADA"
        w_selected2 = "SELECTED"
  End Select
  ShowHTML "          <OPTION VALUE=""BASE NACIONAL COMUM"" "&w_selected1&">BASE NACIONAL COMUM"
  ShowHTML "          <OPTION VALUE=""PARTE DIVERSIFICADA"" "&w_selected2&">PARTE DIVERSIFICADA"
  ShowHTML "          </SELECT></td>"
  w_selected1 = ""
  w_selected2 = ""
  w_selected3 = ""
  w_selected4 = ""
  ShowHTML "      <tr><td valign=""top"" colspan=1><font size=""1""><b>Carga <U>H</U>orária Semanal:<td colspan=3><INPUT ACCESSKEY=""H"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_carga_horaria_sem"" size=""4"" maxlength=""10"" value=""" & w_carga_horaria_sem & """></td>"
  ShowHTML "      <tr><td valign=""top"" colspan=1><font size=""1""><b><U>O</U>rdem de impressão:<td colspan=3><INPUT ACCESSKEY=""O"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_nu_ordem_imp"" size=""4"" maxlength=""10"" value=""" & w_nu_ordem_imp & """></td>"
  ShowHTML "      <tr><td colspan=1><font size=""1""><b>Tipo de A<U>v</U>aliação:<td colspan=3><SELECT ACCESSKEY=""V"" " & w_Disabled & " class=""STS"" name=""w_tp_avaliacao"" size=""1"">"
  ShowHTML "          <OPTION VALUE="""">---"
  Select Case w_tp_avaliacao
     Case "NOTA"
        w_selected1 = "SELECTED"
     Case "NÃO TEM"
        w_selected2 = "SELECTED"
  End Select
  ShowHTML "          <OPTION VALUE=""NOTA"" "&w_selected1&">NOTA"
  ShowHTML "          <OPTION VALUE=""NÃO TEM"" "&w_selected2&">NÃO TEM"
  ShowHTML "          </SELECT></td>"
  w_selected1 = ""
  w_selected2 = ""
  w_selected3 = ""
  w_selected4 = ""
  ShowHTML "          <tr><td colspan=1><font size=""1""><b>Tipo de <U>D</U>igitação:<td colspan=3><SELECT ACCESSKEY=""D"" " & w_Disabled & " class=""STS"" name=""w_tp_digitacao"" size=""1"">"
  ShowHTML "          <OPTION VALUE="""">---"
  Select Case w_tp_digitacao
     Case "NOTA"
        w_selected1 = "SELECTED"
     Case "NÃO TEM"
        w_selected2 = "SELECTED"
  End Select
  ShowHTML "          <OPTION VALUE=""NOTA"" "&w_selected1&">NOTA"
  ShowHTML "          <OPTION VALUE=""NÃO TEM"" "&w_selected2&">NÃO TEM"
  ShowHTML "          </SELECT></td>"
  w_selected1 = ""
  w_selected2 = ""
  w_selected3 = ""
  w_selected4 = ""  
  ShowHTML "          <tr><td colspan=1><font size=""1""><b>Tipo de <U>I</U>mpressão:<td colspan=3><SELECT ACCESSKEY=""I"" " & w_Disabled & " class=""STS"" name=""w_tp_impressao"" size=""1"">"
  ShowHTML "          <OPTION VALUE="""">---"
  Select Case w_tp_impressao
     Case "NOTA"
        w_selected1 = "SELECTED"
     Case "NÃO TEM"
        w_selected2 = "SELECTED"
  End Select
  ShowHTML "          <OPTION VALUE=""NOTA"" "&w_selected1&">NOTA"
  ShowHTML "          <OPTION VALUE=""NÃO TEM"" "&w_selected2&">NÃO TEM"
  ShowHTML "          </SELECT></td>" 
  w_selected1 = ""
  w_selected2 = ""
  w_selected3 = ""
  w_selected4 = ""    
  ShowHTML "          <tr><td colspan=1><font size=""1""><b><U>R</U>eprova:<td colspan=3><SELECT ACCESSKEY=""R"" " & w_Disabled & " class=""STS"" name=""w_st_reprova"" size=""1"">"
  ShowHTML "          <OPTION VALUE="""">---"
  Select Case w_st_reprova
     Case "SIM"
        w_selected1 = "SELECTED"
     Case "NÃO"
        w_selected2 = "SELECTED"
  End Select
  ShowHTML "          <OPTION VALUE=""SIM"" "&w_selected1&">SIM"
  ShowHTML "          <OPTION VALUE=""NÃO"" "&w_selected2&">NÃO"
  ShowHTML "          </SELECT></td>"
  w_selected1 = ""
  w_selected2 = ""
  w_selected3 = ""
  w_selected4 = ""  
  ShowHTML "      </tr>"      
  ShowHTML "      <tr><td valign=""top"" colspan=1><font size=""1""><b><U>A</U>ssinatura Eletrônica:<td colspan=3><INPUT ACCESSKEY=""A"" class=""STI"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
  'ShowHTML "      </table>"
  ShowHTML "      <tr><td align=""center"" colspan=""4"" height=""1"" bgcolor=""#000000"">"
  ShowHTML "      <tr><td align=""center"" colspan=""4"">"
  If O = "E" Then
     ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
  Else
     ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Gravar"">"
  End If  
  ShowHTML "            <input class=""STB"" type=""button"" onClick=""fechar();"" name=""Botao"" value=""Cancelar"">"
  ShowHTML "          </td>"
  ShowHTML "      </tr>"
 'ShowHTML "    </table>"
  ShowHTML "    </TD>"
  ShowHTML "</tr>"
  ShowHTML "</FORM>"
  ShowHTML "</table>"
  ShowHTML "</center>"
  ShowHTML "</body> "
  ShowHTML "</HTML> "

  Set p_co_grade_curric        = Nothing
  Set p_co_tipo_curso          = Nothing
  Set p_sg_serie               = Nothing
  Set p_ano                    = Nothing
  Set p_turno                  = Nothing
  Set p_co_tipo_disciplina     = Nothing
  Set w_carga_horaria_sem      = Nothing
  Set w_nu_ordem_imp           = Nothing
  Set w_tp_disciplina          = Nothing
  Set w_tp_avaliacao           = Nothing  
  Set w_tp_digitacao           = Nothing
  Set w_tp_impressao           = Nothing
  Set w_st_reprova             = Nothing
End Sub
REM =========================================================================
REM Fim da rotina
REM -------------------------------------------------------------------------




REM =========================================================================
REM Procedimento que executa as operações de BD
REM -------------------------------------------------------------------------
Public Sub Grava

  Dim p_codigo
  Dim p_co_grade_curric
  Dim p_ds_grade
  Dim p_co_tipo_curso
  Dim p_nu_grade
  Dim p_ordena
  Dim w_Null
  Dim w_serie_box, w_cont_box
  Dim w_ds_disciplina

  Cabecalho
  ShowHTML "</HEAD>"
  BodyOpen "onLoad=document.focus();"
  
  AbreSessao    
  Select Case SG
  
    Case "MATRIZ"
       p_ds_grade        = uCase(Request("p_ds_grade"))
       p_codigo          = Request("p_codigo")
       p_co_tipo_curso   = Request("p_co_tipo_curso")
       p_nu_grade        = uCase(Request("p_nu_grade"))
       p_ordena          = uCase(Request("p_ordena"))
  
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          DML_SMATRIZ O, _
                   Request("w_co_grade_curric"), Request("w_co_tipo_curso"), Request("w_ano"), _
                   Request("w_turno"), Request("w_dt_grade"), Request("w_nu_semanas"), Request("w_nu_grade"), _
                   Request("w_ds_grade")
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_ds_grade=" & p_ds_grade & "&p_co_tipo_curso=" &p_co_tipo_curso& "&p_nu_grade=" &p_nu_grade& "&p_codigo=" & p_codigo & "&p_ordena=" & p_ordena & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    
    Case "SERIES"
       If Request("O") = "L" Then
          w_serie_box = split(Request("w_serie_box"),",")
          For w_cont_box = LBound(w_serie_box) to UBound(w_serie_box)
             DML_SPERIODO Request("O"), _
                   Request("p_turno"),Request("p_co_grade_curric"),  Request("p_ano"), _
                   Request("p_co_tipo_curso"), w_serie_box(w_cont_box)
          Next
          ScriptOpen "JavaScript"
          ShowHTML "window.top.opener.location.replace('" & R & "&O=V&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_ds_grade=" & p_ds_grade & "&p_co_tipo_curso=" &p_co_tipo_curso& "&p_nu_grade=" &p_nu_grade& "&p_codigo=" & p_codigo & "&p_ordena=" & p_ordena & "&w_co_grade_curric=" & Request("p_co_grade_curric") & "');"
          ShowHTML "window.top.close();"
          ScriptClose
       ElseIf Request("O") = "E" Then
           DML_SPERIODO Request("O"), _
                   Request("p_turno"),Request("p_co_grade_curric"),  Request("p_ano"), _
                   Request("p_co_tipo_curso"), Request("w_sg_serie")
           ScriptOpen "JavaScript"
           ShowHTML "  location.href='" & R & "&O=V&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_ds_grade=" & p_ds_grade & "&p_co_tipo_curso=" &p_co_tipo_curso& "&p_nu_grade=" &p_nu_grade& "&p_codigo=" & p_codigo & "&p_ordena=" & p_ordena & "&w_co_grade_curric=" & Request("p_co_grade_curric") & "';"
           ScriptClose
       End If
    
    Case "DISCIPLINAS"
        ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          DB_GetDisciplineTypeData RS, Request("w_co_tipo_disciplina")
          w_ds_disciplina = RS("ds_tipo_disciplina")
          DesconectaBD  
          DML_SDISCIPLINAPER O,_
                 Request("p_sg_serie"), Request("w_co_tipo_disciplina"),Request("p_co_grade_curric"), _
                 Request("p_co_tipo_curso"), Request("p_ano"), Request("p_turno"), Request("w_carga_horaria_sem"), _
                 Request("w_tp_disciplina"), Request("p_sg_serie"), w_ds_disciplina, _
                 Request("w_nu_ordem_imp"), Request("w_tp_avaliacao"), Request("w_tp_digitacao"), _
                 Request("w_tp_impressao"), Request("w_st_reprova")
          ScriptOpen "JavaScript"
          ShowHTML "window.top.opener.location.replace('" & R & "&O=" & O & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_ds_grade=" & p_ds_grade & "&p_co_tipo_curso=" &p_co_tipo_curso& "&p_nu_grade=" &p_nu_grade& "&p_codigo=" & p_codigo & "&p_ordena=" & p_ordena & "&p_co_grade_curric=" & Request("p_co_grade_curric") & "&p_sg_serie=" & Request("p_sg_serie")& "');"
          ShowHTML "window.top.close();"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
  End Select

  Set p_co_grade_curric = Nothing
  Set p_co_tipo_curso   = Nothing
  Set p_nu_grade        = Nothing
  Set p_codigo          = Nothing
  Set p_ds_grade        = Nothing
  Set p_ordena          = Nothing
  Set w_Null            = Nothing
End Sub
REM -------------------------------------------------------------------------
REM Fim do procedimento que executa as operações de BD
REM =========================================================================

REM =========================================================================
REM Rotina principal
REM -------------------------------------------------------------------------
Sub Main
  ' Verifica se o usuário tem lotação e localização
  Select Case Par
    Case "MATRIZ"
       matriz
    Case "EXIBESERIES"
       ExibeSeries
    Case "GRAVA"
       Grava
    Case "EXIBEDISCIPLINAS"
       ExibeDisciplinas
    Case "SELECDISCIPLINA"
       SelecDisciplina
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

