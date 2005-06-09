<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Serie.asp" -->
<!-- #INCLUDE FILE="DML_Serie.asp" -->
<!-- #INCLUDE FILE="DB_Tipo_Curso.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /Serie.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Gerencia a atualização das tabelas de ambiente
REM Mail     : alex@sbpi.com.br
REM Criacao  : 15/08/2003, 15:30
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
w_Pagina     = "Serie.asp?par="
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
REM Rotina da tabela de bancos
REM -------------------------------------------------------------------------
Sub Serie

  Dim w_sg_serie
  Dim p_codigo
  Dim w_ds_serie, p_ds_serie
  Dim p_Ordena
  Dim p_co_tipo_curso, w_co_tipo_curso

  p_ds_serie         = uCase(Request("p_ds_serie"))
  p_co_tipo_curso    = Request("p_co_tipo_curso")
  p_codigo           = Request("p_codigo")
  p_ordena           = uCase(Request("p_ordena"))
  
  If O = "L" Then
     DB_GetSerieList RS
     If p_ds_serie & p_codigo > "" Then
        w_filter = ""
        If p_ds_serie > ""   Then w_filter = w_filter & " and descr_serie like '*" & p_ds_serie & "*'" End If
        If p_codigo   > ""   Then w_filter = w_filter & " and co_tipo_curso = " & cDbl(p_codigo)       End If
        RS.Filter = Mid(w_filter,6,255)
     End If
     If p_ordena > "" Then RS.sort = p_ordena Else RS.sort = "co_tipo_curso, descr_serie" End If
  ElseIf O = "A" or O = "E" Then
     w_sg_serie = Request("w_sg_serie")
     DB_GetSerieData RS, w_sg_serie
     w_co_tipo_curso = cDbl(RS("co_tipo_curso"))
     w_ds_serie      = RS("descr_serie")
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_co_tipo_curso", "Modalidade de ensino", "SELECT", "1", "1", "50", "1", "1"
        Validate "w_sg_serie1", "Sigla", "1", "1", "1", "5", "1", "1"
        Validate "w_ds_serie", "Descrição", "1", "1", "3", "50", "1", "1"
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     ElseIf O = "E" Then
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
        ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
        ShowHTML "     { return (true); }; "
        ShowHTML "     { return (false); }; "
     ElseIf O="P" Then
        Validate "p_codigo", "Modalidade de ensino", "SELECT", "", "1", "50", "1", "1"
        Validate "p_ds_serie", "Descrição", "1", "", "3", "30", "1", "1"
        Validate "P4", "Linhas por página", "1", "1", "1", "4", "", "0123456789"
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  ShowHTML "<BASE HREF=""http://" & Request.ServerVariables("server_name") & "/siw/"">"
  If InStr("IAE",O) > 0 Then
     If O = "E" Then
        BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
     Else
        BodyOpen "onLoad='document.Form.w_co_tipo_curso.focus()';"
     End If
  ElseIf InStr("P",O) > 0 Then
     BodyOpen "onLoad='document.Form.p_codigo.focus()';"
  Else
     BodyOpen "onLoad=document.focus();"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    w_filter = ""
    If p_codigo > "" Then 
       DB_GetCourseTypeList RS1
       RS1.Filter = "co_tipo_curso = " & p_codigo
       w_filter = w_filter & " [Mod.: <b>" & RS1("ds_tipo_curso") & "</b>]&nbsp;"
    End If
    If p_ds_serie > "" Then 
       w_filter = w_filter & "[Descrição: <b>" & p_ds_serie & "</b>]&nbsp;"
    End If
    If w_filter > ""  Then ShowHTML "<tr bgcolor=""" & conTrAlternateBgColor & """><td colspan=5><font size=1><b>&nbsp;Filtro:&nbsp;</b>" & w_filter & "</font><BR>"            
    ShowHTML "<tr><td><font size=""2"">"
    If P1 <> 1 Then
       ShowHTML "                            <a accesskey=""I"" class=""SS"" href=""" & w_Dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_codigo=" & p_codigo & "&p_ds_serie=" & p_ds_serie & "&p_ordena=" & p_ordena & """><u>I</u>ncluir</a>&nbsp;"
    End If
    If p_codigo & p_ds_serie & p_Ordena & p_co_tipo_curso > "" Then
       ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_Dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_codigo=" & p_codigo & "&p_ds_serie=" & p_ds_serie & "&p_ordena=" & p_ordena & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a></font>"
    Else
       ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_Dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_codigo=" & p_codigo & "&p_ds_serie=" & p_ds_serie & "&p_ordena=" & p_ordena & """><u>F</u>iltrar (Inativo)</a>"
    End If
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""2""><b>Sigla</font></td>"
    ShowHTML "          <td><font size=""2""><b>Modalidade de Ensino</font></td>"
    ShowHTML "          <td><font size=""2""><b>Descrição</font></td>"
    If P1 <> 1 Then
       ShowHTML "          <td width=""20%""><font size=""2""><b>Operações</font></td>"
    End If
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=4 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF and RS.AbsolutePage = P3
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("sg_serie") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("ds_tipo_curso") & "</td>"        
        ShowHTML "        <td><font size=""1"">" & RS("descr_serie") & "</td>"
        If P1 <> 1 Then
           ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
           ShowHTML "          <A class=""HL"" HREF=""" & w_Dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sg_serie=" & RS("sg_serie") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_codigo=" & p_codigo & "&p_ds_serie=" & p_ds_serie & "&p_ordena=" & p_ordena & """>Alterar</A>&nbsp"
           ShowHTML "          <A class=""HL"" HREF=""" & w_Dir & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sg_serie=" & RS("sg_serie") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_codigo=" & p_codigo & "&p_ds_serie=" & p_ds_serie & "&p_ordena=" & p_ordena & """>Excluir</A>&nbsp"
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
    ShowHTML "<INPUT type=""hidden"" name=""p_ds_serie"" value=""" & p_ds_serie &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_codigo"" value=""" & p_codigo &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_ordena"" value=""" & p_ordena &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sg_serie"" value=""" & w_sg_serie &""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""70%"" border=""0"">"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""90%"" border=""0"">"            
    SelecaoModEnsino "<u>M</u>odalidade de ensino:", "M", null, w_co_tipo_curso, null, "w_co_tipo_curso", null,null    
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>S</U>igla:<br><INPUT ACCESSKEY=""S"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_sg_serie1"" size=""4"" maxlength=""50"" value=""" & w_sg_serie & """></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>D</U>escrição:<br><INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_ds_serie"" size=""50"" maxlength=""50"" value=""" & w_ds_serie & """></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""STI"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    If O = "E" Then
       ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Gravar"">"
    End If
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_ds_serie=" & p_ds_serie & "&p_codigo=" & p_codigo & "&p_ordena=" & p_ordena & "';"" name=""Botao"" value=""Cancelar"">"
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
    SelecaoModEnsino "<u>M</u>odalidade de ensino:", "M", null, p_codigo, null, "p_codigo", null,null    
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>D</U>escrição:<br><INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_ds_serie"" size=""50"" maxlength=""50"" value=""" & p_ds_serie & """></td>"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY=""O"" " & w_Disabled & " class=""STS"" name=""p_ordena"" size=""1"">"
    If p_Ordena="SG_SERIE" Then
       ShowHTML "          <option value="""">Descrição<option value=""SG_SERIE"" SELECTED>Sigla"
    Else
       ShowHTML "          <option value="""" SELECTED>Descrição<option value=""SG_SERIE"">Sigla"
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

  Set w_sg_serie  = Nothing
  Set w_ds_serie  = Nothing
  Set p_codigo    = Nothing
  Set p_ordena    = Nothing

End Sub
REM =========================================================================
REM Fim da tabela de area de atuação
REM -------------------------------------------------------------------------

REM =========================================================================
REM Procedimento que executa as operações de BD
REM -------------------------------------------------------------------------
Public Sub Grava

  Dim p_codigo
  Dim p_sg_serie
  Dim p_ds_serie
  Dim p_ordena
  Dim w_Null

  Cabecalho
  ShowHTML "</HEAD>"
  BodyOpen "onLoad=document.focus();"
  
  AbreSessao    
  Select Case SG
    Case "SERIE"
       p_ds_serie        = uCase(Request("p_ds_serie"))
       p_codigo          = Request("p_codigo")
       p_ordena          = uCase(Request("p_ordena"))
  
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          DML_SSERIE O, _
                   Request("w_sg_serie"), Request("w_sg_serie1"), Request("w_co_tipo_curso"),  Request("w_ds_serie")
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_ds_serie=" & p_ds_serie & "&p_codigo=" & p_codigo & "&p_ordena=" & p_ordena & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
  End Select

  Set p_sg_serie        = Nothing
  Set p_codigo          = Nothing
  Set p_ds_serie        = Nothing
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
    Case "SERIE"
       serie
    Case "GRAVA"
       Grava
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

