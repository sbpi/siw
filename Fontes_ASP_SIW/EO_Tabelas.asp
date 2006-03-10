<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE FILE="Constants.inc" -->
<!-- #INCLUDE FILE="jScript.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Geral.asp" -->
<!-- #INCLUDE FILE="DB_EO_Tabelas.asp" -->
<!-- #INCLUDE FILE="DB_Seguranca.asp" -->
<!-- #INCLUDE FILE="DML_EO_Tabelas.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /EO_Tabelas.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Gerencia a atualização das tabelas do sistema
REM Mail     : alex@sbpi.com.br
REM Criacao  : 24/03/2003 16:55
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
Dim dbms, sp, RS
Dim P1, P2, P3, P4, TP, SG
Dim R, O, w_Cont, w_Pagina, w_Disabled, w_TP
Dim w_Assinatura,  w_cor, w_cliente, w_filter
Dim w_dir, w_dir_volta, w_submenu, w_menu
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
w_Assinatura = uCase(Request("w_Assinatura"))
w_Pagina     = "EO_Tabelas.asp?par="
w_Disabled   = "ENABLED"

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
  Case Else
     w_TP = TP & " - Listagem"
End Select

' Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
' caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
w_cliente = RetornaCliente()
w_menu    = RetornaMenu(w_cliente, SG) 

Main

FechaSessao

Set w_cor       = Nothing

Set w_menu      = Nothing
Set w_cliente   = Nothing
Set w_dir       = Nothing
Set w_dir_volta = Nothing
Set RS          = Nothing
Set Par         = Nothing
Set P1          = Nothing
Set P2          = Nothing
Set P3          = Nothing
Set P4          = Nothing
Set TP          = Nothing
Set SG          = Nothing
Set R           = Nothing
Set O           = Nothing
Set w_Cont      = Nothing
Set w_Pagina    = Nothing
Set w_Disabled  = Nothing
Set w_TP        = Nothing
Set w_Assinatura= Nothing

REM =========================================================================
REM Rotina de feriados
REM -------------------------------------------------------------------------
Sub Feriado

  Dim w_sq_feriado
  Dim w_nome, p_nome
  Dim w_tipo, p_tipo
  Dim w_endereco, p_endereco
  Dim p_Ordena

  p_nome            = uCase(Request("p_nome"))
  p_tipo            = uCase(Request("p_tipo"))
  p_endereco        = uCase(Request("p_endereco"))
  p_ordena          = uCase(Request("p_ordena"))
  
  If O = "L" Then
     DB_GetFeriado RS, w_cliente, Session("localizacao"), null, null
     If p_nome & p_tipo > "" Then
        w_filter = ""
        If p_nome > "" Then w_filter = w_filter & " and nome like '*" & p_nome & "*' " End If
        If p_tipo > "" Then w_filter = w_filter & " and tipo = '" & p_tipo & "' "      End If
        RS.Filter = Mid(w_filter,6,255)
     End If
     If p_Ordena = "" Then 
        RS.Sort = "Nome"
     Else
        RS.Sort = p_ordena 
     End If
  ElseIf O = "A" or O = "E" Then
     w_sq_feriado = Request("w_sq_feriado")
     DB_GetFeriado RS, w_cliente, Session("localizacao"), w_sq_feriado, null
     w_nome     = RS("nome")
     w_tipo     = RS("tipo")
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_nome", "Nome", "1", "1", "3", "25", "1", "1"
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     ElseIf O = "E" Then
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
        ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
        ShowHTML "     { return (true); }; "
        ShowHTML "     { return (false); }; "
     ElseIf O="P" Then
        Validate "p_nome", "Nome", "1", "", "3", "25", "1", "1"
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  If InStr("IAE",O) > 0 Then
     If O = "E" Then
        BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
     Else
        BodyOpen "onLoad='document.Form.w_nome.focus()';"
     End If
  ElseIf InStr("P",O) > 0 Then
     BodyOpen "onLoad='document.Form.p_nome.focus()';"
  Else
     BodyOpen "onLoad=document.focus();"
  End If
  Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ShowHTML "<tr><td><font size=""2"">"
    ShowHTML "  <a accesskey=""I"" class=""ss"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "  <a accesskey=""G"" class=""ss"" href=""" & w_Pagina & "Grava&R=" & w_Pagina & par & "&O=G&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>G</u>erar arquivo</a>&nbsp;"
    If p_nome & p_tipo & p_Ordena > "" Then
       ShowHTML "                         <a accesskey=""F"" class=""ss"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u><font color=""#BC5100"">F</u>iltrar (tipo)</font></a></font>"
    Else
       ShowHTML "                         <a accesskey=""F"" class=""ss"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """><u>F</u>iltrar (Intipo)</a>"
    End If
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Chave</font></td>"
    ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
    ShowHTML "          <td><font size=""1""><b>Tipo</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=5 align=""center""><font  size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("sq_feriado") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("nome") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("nm_tipo") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""hl"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_feriado=" & RS("sq_feriado") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""hl"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_feriado=" & RS("sq_feriado") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & """>Excluir</A>&nbsp"
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    for w_cont = 1990 to 2010
        ShowHTML "      <tr bgcolor=""" & w_cor & """>"
        ShowHTML "        <td align=""center""><font size=""1"">" & w_cont & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">"
        ShowHTML "           " & DomingoPascoa(w_cont) & " - "
        ShowHTML "           " & SextaSanta(w_cont) & " - "
        ShowHTML "           " & TercaCarnaval(w_cont) & " - "
        ShowHTML "           " & CorpusChristi(w_cont)
    next
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    DesConectaBD	 
  ElseIf Instr("IAE",O) > 0 Then
    If O = "E" Then
       w_Disabled = "READONLY"
    End If
    AbreForm "Form", w_Pagina&"Grava", "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_feriado"" value=""" & w_sq_feriado &""">"
    ShowHTML MontaFiltro("POST")

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
    ShowHTML "    <table width=""100%"" border=""0"">"
    ShowHTML "      <tr valign=""top"">"
    ShowHTML "        <td><font size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_nome"" size=""25"" maxlength=""25"" value=""" & w_nome & """></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Tipo:"
    If Nvl(w_tipo,"O") = "O" Then
       ShowHTML "          <br><input type=""radio"" class=""str"" name=""w_tipo"" value=""O"" selected> Oficial  <br><input type=""radio"" class=""str"" name=""w_tipo"" value=""S""> Facultativo sem expediente <br><input type=""radio"" class=""str"" name=""w_tipo"" value=""I""> Facultativo com expediente <br><input type=""radio"" class=""str"" name=""w_tipo"" value=""P""> Horário reduzido"
    ElseIf w_tipo = "S" Then
       ShowHTML "          <br><input type=""radio"" class=""str"" name=""w_tipo"" value=""O""> Oficial  <br><input type=""radio"" class=""str"" name=""w_tipo"" value=""S"" selected> Facultativo sem expediente <br><input type=""radio"" class=""str"" name=""w_tipo"" value=""I""> Facultativo com expediente <br><input type=""radio"" class=""str"" name=""w_tipo"" value=""P""> Horário reduzido"
    ElseIf w_tipo = "I" Then
       ShowHTML "          <br><input type=""radio"" class=""str"" name=""w_tipo"" value=""O""> Oficial  <br><input type=""radio"" class=""str"" name=""w_tipo"" value=""S""> Facultativo sem expediente <br><input type=""radio"" class=""str"" name=""w_tipo"" value=""I"" selected> Facultativo com expediente <br><input type=""radio"" class=""str"" name=""w_tipo"" value=""P""> Horário reduzido"
    Else
       ShowHTML "          <br><input type=""radio"" class=""str"" name=""w_tipo"" value=""O""> Oficial  <br><input type=""radio"" class=""str"" name=""w_tipo"" value=""S""> Facultativo sem expediente <br><input type=""radio"" class=""str"" name=""w_tipo"" value=""I""> Facultativo com expediente <br><input type=""radio"" class=""str"" name=""w_tipo"" value=""P"" selected> Horário reduzido"
    End If
    ShowHTML "          </select></td>"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    If O = "E" Then
       ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Gravar"">"
    End If
    ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"" name=""Botao"" value=""Cancelar"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  ElseIf Instr("P",O) > 0 Then
    AbreForm "Form", w_Pagina&par, "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,R,"L"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
    ShowHTML "    <table width=""100%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""sti"" type=""text"" name=""p_nome"" size=""25"" maxlength=""25"" value=""" & p_nome & """></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>tipo:</b><br>"
    If p_tipo  =  "" Then
       ShowHTML "              <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_tipo"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_tipo"" value=""N""> Não <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_tipo"" value="""" checked> Todos"
    ElseIf p_tipo = "S" Then
       ShowHTML "              <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_tipo"" value=""S"" checked> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_tipo"" value=""N""> Não <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_tipo"" value=""""> Todos"
    Else
       ShowHTML "              <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_tipo"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_tipo"" value=""N"" checked> Não <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_tipo"" value=""""> Todos"
    End If
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY=""O"" " & w_Disabled & " class=""sts"" name=""p_ordena"" size=""1"">"
    If p_Ordena="NOME" Then
       ShowHTML "          <option value="""">Nome<option value=""tipo"">tipo"
    ElseIf p_Ordena="tipo" Then
       ShowHTML "          <option value="""">Nome<option value=""tipo"" SELECTED>tipo"
    Else
       ShowHTML "          <option value="""" SELECTED>Nome<option value=""tipo"">tipo"
    End If
    ShowHTML "          </select></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
    ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"" name=""Botao"" value=""Remover filtro"">"
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
    Estrutura_Texto_Fecha
    Estrutura_Fecha
  Estrutura_Fecha
  Estrutura_Fecha
  Rodape

  Set w_sq_feriado = Nothing
  Set w_nome       = Nothing
  Set w_tipo       = Nothing
  Set p_nome       = Nothing
  Set p_tipo       = Nothing
  Set p_ordena     = Nothing

End Sub

REM =========================================================================
REM Rotina da tabela de áreas de atuação
REM -------------------------------------------------------------------------
Sub AreaAtuacao

  Dim w_sq_area_atuacao
  Dim w_nome, p_nome
  Dim w_ativo, p_ativo
  Dim p_Ordena
  Dim w_libera_edicao

  p_nome            = uCase(Request("p_nome"))
  p_ativo           = uCase(Request("p_ativo"))
  p_ordena          = uCase(Request("p_ordena"))
  
  DB_GetMenuData RS, w_menu
  w_libera_edicao = RS("libera_edicao")
  
  If O = "L" Then
     DB_GetEOAAtuac RS, w_cliente
     If p_nome & p_ativo > "" Then
        w_filter = ""
        If p_nome > ""            Then w_filter = w_filter & " and nome like '*" & p_nome & "*' " End If
        If p_ativo > ""           Then w_filter = w_filter & " and ativo = '" & p_ativo & "' "    End If
        RS.Filter = Mid(w_filter,6,255)
     End If
     If p_Ordena = "" Then 
        RS.Sort = "Nome"
     Else
        RS.Sort = p_ordena 
     End If
  ElseIf O = "A" or O = "E" Then
     w_sq_area_atuacao = Request("w_sq_area_atuacao")
     DB_GetEOAAtuacData RS, w_sq_area_atuacao
     w_nome   = RS("nome")
     w_ativo  = RS("ativo")
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_nome", "Nome", "1", "1", "3", "25", "1", "1"
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     ElseIf O = "E" Then
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
        ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
        ShowHTML "     { return (true); }; "
        ShowHTML "     { return (false); }; "
     ElseIf O="P" Then
        Validate "p_nome", "Nome", "1", "", "3", "25", "1", "1"
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  If InStr("IAE",O) > 0 Then
     If O = "E" Then
        BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
     Else
        BodyOpen "onLoad='document.Form.w_nome.focus()';"
     End If
  ElseIf InStr("P",O) > 0 Then
     BodyOpen "onLoad='document.Form.p_nome.focus()';"
  Else
     BodyOpen "onLoad=document.focus();"
  End If
  Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ShowHTML "<tr><td>"
    If w_libera_edicao = "S" Then
       ShowHTML "<font size=""2""><a accesskey=""I"" class=""ss"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """><u>I</u>ncluir</a>&nbsp;"
    End If
    If p_nome & p_ativo & p_Ordena > "" Then
       ShowHTML "                         <a accesskey=""F"" class=""ss"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a></font>"
    Else
       ShowHTML "                         <a accesskey=""F"" class=""ss"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """><u>F</u>iltrar (Inativo)</a>"
    End If
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Chave</font></td>"
    ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
    ShowHTML "          <td><font size=""1""><b>Ativo</font></td>"
    If w_libera_edicao = "S" Then    
       ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    End If
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=5 align=""center""><font  size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("sq_area_atuacao") & "</td>"
        ShowHTML "        <td align=""left""><font size=""1"">" & RS("nome") & "</td>"
        If RS("ativo") = "S" Then
           ShowHTML "        <td align=""center""><font size=""1"">Sim</td>"
        Else
           ShowHTML "        <td align=""center""><font size=""1"">Não</td>"
        End If
        If w_libera_edicao = "S" Then
           ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
           ShowHTML "          <A class=""hl"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_area_atuacao=" & RS("sq_area_atuacao") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """>Alterar</A>&nbsp"
           ShowHTML "          <A class=""hl"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_area_atuacao=" & RS("sq_area_atuacao") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """>Excluir</A>&nbsp"
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
    DesConectaBD	 
  ElseIf Instr("IAE",O) > 0 Then
    If O = "E" Then
       w_Disabled = "READONLY"
    End If
    AbreForm "Form", w_Pagina&"Grava", "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""p_nome"" value=""" & p_nome &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_ativo"" value=""" & p_ativo &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_ordena"" value=""" & p_ordena &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_area_atuacao"" value=""" & w_sq_area_atuacao &""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
    ShowHTML "    <table width=""100%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_nome"" size=""25"" maxlength=""25"" value=""" & w_nome & """></td>"
    ShowHTML "      <tr>"
    MontaRadioSN "<b>Ativo:</b>", w_ativo, "w_ativo"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    If O = "E" Then
       ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Gravar"">"
    End If
    ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & "';"" name=""Botao"" value=""Cancelar"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  ElseIf Instr("P",O) > 0 Then
    AbreForm "Form", w_Pagina&par, "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,R,"L"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
    ShowHTML "    <table width=""100%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""sti"" type=""text"" name=""p_nome"" size=""25"" maxlength=""25"" value=""" & p_nome & """></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Ativo:</b><br>"
    If p_Ativo  =  "" Then
       ShowHTML "              <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_ativo"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_ativo"" value=""N""> Não <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_ativo"" value="""" checked> Todos"
    ElseIf p_Ativo = "S" Then
       ShowHTML "              <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_ativo"" value=""S"" checked> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_ativo"" value=""N""> Não <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_ativo"" value=""""> Todos"
    Else
       ShowHTML "              <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_ativo"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_ativo"" value=""N"" checked> Não <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_ativo"" value=""""> Todos"
    End If
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY=""O"" " & w_Disabled & " class=""sts"" name=""p_ordena"" size=""1"">"
    If p_Ordena="NOME" Then
       ShowHTML "          <option value="""">Nome<option value=""ativo"">Ativo"
    ElseIf p_Ordena="ATIVO" Then
       ShowHTML "          <option value="""">Nome<option value=""ativo"" SELECTED>Ativo"
    Else
       ShowHTML "          <option value="""" SELECTED>Nome<option value=""ativo"">Ativo"
    End If
    ShowHTML "          </select></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
    ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"" name=""Botao"" value=""Remover filtro"">"
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
    Estrutura_Texto_Fecha
    Estrutura_Fecha
  Estrutura_Fecha
  Estrutura_Fecha
  Rodape

  Set w_sq_area_atuacao = Nothing
  Set w_nome            = Nothing
  Set w_ativo           = Nothing
  Set p_nome            = Nothing
  Set p_ativo           = Nothing
  Set p_ordena          = Nothing
  Set w_libera_edicao   = Nothing

End Sub

REM =========================================================================
REM Rotina da tabela de tipos de unidade organizacional
REM -------------------------------------------------------------------------
Sub TipoUnidade

  Dim w_sq_tipo_unidade
  Dim w_nome, p_nome
  Dim w_ativo, p_ativo
  Dim p_Ordena
  Dim w_libera_edicao
  
  p_nome            = uCase(Request("p_nome"))
  p_ativo           = uCase(Request("p_ativo"))
  p_ordena          = uCase(Request("p_ordena"))
  
  DB_GetMenuData RS, w_menu
  w_libera_edicao = RS("libera_edicao")
  
  If O = "L" Then
     DB_GetUnitTypeList RS,w_cliente
     If p_nome & p_ativo > "" Then
        w_filter = ""
        If p_nome > ""            Then w_filter = w_filter & " and nome like '*" & p_nome & "*' " End If
        If p_ativo > ""           Then w_filter = w_filter & " and ativo = '" & p_ativo & "' "    End If
        RS.Filter = Mid(w_filter,6,255)
     End If
     If p_Ordena = "" Then 
        RS.Sort = "Nome"
     Else
        RS.Sort = p_ordena 
     End If
  ElseIf O = "A" or O = "E" Then
     w_sq_tipo_unidade = Request("w_sq_tipo_unidade")
     DB_GetUnitTypeData RS, w_sq_tipo_unidade
     w_nome   = RS("nome")
     w_ativo  = RS("ativo")
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_nome", "Nome", "1", "1", "3", "25", "1", "1"
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     ElseIf O = "E" Then
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
        ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
        ShowHTML "     { return (true); }; "
        ShowHTML "     { return (false); }; "
     ElseIf O="P" Then
        Validate "p_nome", "Nome", "1", "", "3", "25", "1", "1"
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  If InStr("IAE",O) > 0 Then
     If O = "E" Then
        BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
     Else
        BodyOpen "onLoad='document.Form.w_nome.focus()';"
     End If
  ElseIf InStr("P",O) > 0 Then
     BodyOpen "onLoad='document.Form.p_nome.focus()';"
  Else
     BodyOpen "onLoad=document.focus();"
  End If
  Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ShowHTML "<tr><td>"
    If w_libera_edicao = "S" Then
       ShowHTML "<font size=""2""><a accesskey=""I"" class=""ss"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """><u>I</u>ncluir</a>&nbsp;"
    End If
    If p_nome & p_ativo & p_Ordena > "" Then
       ShowHTML "                         <a accesskey=""F"" class=""ss"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a></font>"
    Else
       ShowHTML "                         <a accesskey=""F"" class=""ss"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """><u>F</u>iltrar (Inativo)</a>"
    End If
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Chave</font></td>"
    ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
    ShowHTML "          <td><font size=""1""><b>Ativo</font></td>"
    If w_libera_edicao = "S" Then    
       ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    End If
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=5 align=""center""><font  size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("sq_tipo_unidade") & "</td>"
        ShowHTML "        <td align=""left""><font size=""1"">" & RS("nome") & "</td>"
        If RS("ativo") = "S" Then
           ShowHTML "        <td align=""center""><font size=""1"">Sim</td>"
        Else
           ShowHTML "        <td align=""center""><font size=""1"">Não</td>"
        End If
        If w_libera_edicao = "S" Then
           ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
           ShowHTML "          <A class=""hl"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_tipo_unidade=" & RS("sq_tipo_unidade") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """>Alterar</A>&nbsp"
           ShowHTML "          <A class=""hl"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_tipo_unidade=" & RS("sq_tipo_unidade") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """>Excluir</A>&nbsp"
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
    DesConectaBD	 
  ElseIf Instr("IAE",O) > 0 Then
    If O = "E" Then
       w_Disabled = "READONLY"
    End If
    AbreForm "Form", w_Pagina&"Grava", "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""p_nome"" value=""" & p_nome &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_ativo"" value=""" & p_ativo &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_ordena"" value=""" & p_ordena &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_tipo_unidade"" value=""" & w_sq_tipo_unidade &""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
    ShowHTML "    <table width=""100%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_nome"" size=""25"" maxlength=""25"" value=""" & w_nome & """></td>"
    ShowHTML "      <tr>"
    MontaRadioSN "<b>Ativo:</b>", w_ativo, "w_ativo"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    If O = "E" Then
       ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Gravar"">"
    End If
    ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & "';"" name=""Botao"" value=""Cancelar"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  ElseIf Instr("P",O) > 0 Then
    AbreForm "Form", w_Pagina&par, "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,R,"L"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
    ShowHTML "    <table width=""100%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""sti"" type=""text"" name=""p_nome"" size=""25"" maxlength=""25"" value=""" & p_nome & """></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Ativo:</b><br>"
    If p_Ativo  =  "" Then
       ShowHTML "              <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_ativo"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_ativo"" value=""N""> Não <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_ativo"" value="""" checked> Todos"
    ElseIf p_Ativo = "S" Then
       ShowHTML "              <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_ativo"" value=""S"" checked> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_ativo"" value=""N""> Não <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_ativo"" value=""""> Todos"
    Else
       ShowHTML "              <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_ativo"" value=""S""> Sim <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_ativo"" value=""N"" checked> Não <input " & w_Disabled & " class=""str"" type=""radio"" name=""p_ativo"" value=""""> Todos"
    End If
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY=""O"" " & w_Disabled & " class=""sts"" name=""p_ordena"" size=""1"">"
    If p_Ordena="NOME" Then
       ShowHTML "          <option value="""">Código<option value=""nome"" SELECTED>Nome<option value=""ativo"">Ativo"
    ElseIf p_Ordena="ATIVO" Then
       ShowHTML "          <option value="""">Código<option value=""nome"">Nome<option value=""ativo"" SELECTED>Ativo"
    Else
       ShowHTML "          <option value="""" SELECTED>Código<option value=""nome"">Nome<option value=""ativo"">Ativo"
    End If
    ShowHTML "          </select></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
    ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"" name=""Botao"" value=""Remover filtro"">"
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
    Estrutura_Texto_Fecha
    Estrutura_Fecha
  Estrutura_Fecha
  Estrutura_Fecha
  Rodape

  Set w_sq_tipo_unidade   = Nothing
  Set w_nome            = Nothing
  Set w_ativo           = Nothing
  Set p_nome            = Nothing
  Set p_ativo           = Nothing
  Set p_ordena          = Nothing
  Set w_libera_edicao   = Nothing

End Sub

REM =========================================================================
REM Rotina da tabela de tipos de posto
REM -------------------------------------------------------------------------
Sub TipoPosto

  Dim w_chave
  Dim w_nome, w_descricao, w_padrao, w_ativo, w_sigla
  
  w_chave = Request("w_chave")
  
  If O = "L" Then

     DB_GetTipoPostoList RS, w_cliente, null
     
  ElseIf O = "A" or O = "E" Then
     
     DB_GetTipoPostoList RS, w_cliente, w_chave
     w_nome      = RS("nome")
     w_sigla     = RS("sigla")
     w_descricao = RS("descricao")
     w_padrao    = RS("padrao")
     w_ativo     = RS("ativo")
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  Estrutura_CSS w_cliente
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_nome", "Nome", "1", "1", "3", "30", "1", "1"
        Validate "w_sigla", "Sigla", "1", "1", "2", "5", "1", "1"
        Validate "w_descricao", "Descricao", "1", "1", "3", "200", "1", "1"
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
  End If
  ShowHTML "</HEAD>"
  If InStr("IAE",O) > 0 Then
     If O = "E" Then
        BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
     Else
        BodyOpen "onLoad='document.Form.w_nome.focus()';"
     End If
  Else
     BodyOpen "onLoad=document.focus();"
  End If
  Estrutura_Topo_Limpo
  Estrutura_Menu
  Estrutura_Corpo_Abre
  Estrutura_Texto_Abre
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""ss"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
    ShowHTML "          <td><font size=""1""><b>Sigla</font></td>"
    ShowHTML "          <td><font size=""1""><b>Ativo</font></td>"
    ShowHTML "          <td><font size=""1""><b>Padrao</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=5 align=""center""><font  size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """>"
        ShowHTML "        <td align=""left""><font size=""1"">" & RS("nome") & "</td>"
        ShowHTML "        <td align=""left""><font size=""1"">" & RS("sigla") & "</td>"
        If RS("ativo") = "S" Then
           ShowHTML "        <td align=""center""><font size=""1"">Sim</td>"
        Else
           ShowHTML "        <td align=""center""><font size=""1"">Não</td>"
        End If
        If RS("padrao") = "S" Then
           ShowHTML "        <td align=""center""><font size=""1"">Sim</td>"
        Else
           ShowHTML "        <td align=""center""><font size=""1"">Não</td>"
        End If
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""hl"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_chave=" & RS("sq_eo_tipo_posto") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""hl"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_chave=" & RS("sq_eo_tipo_posto") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
        ShowHTML "        </td>"
        ShowHTML "      </tr>"
        RS.MoveNext
      wend
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    DesConectaBD	 
  ElseIf Instr("IAE",O) > 0 Then
    If O = "E" Then
       w_Disabled = "READONLY"
    End If
    AbreForm "Form", w_Pagina&"Grava", "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""w_chave"" value=""" & w_chave &""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td>"
    ShowHTML "    <table width=""100%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_nome"" size=""30"" maxlength=""30"" value=""" & w_nome & """></td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>S</U>igla:<br><INPUT ACCESSKEY=""S"" " & w_Disabled & " class=""sti"" type=""text"" name=""w_sigla"" size=""5"" maxlength=""5"" value=""" & w_sigla & """></td>"
    ShowHTML "      <tr><td valign=""top"" colspan=""2""><font size=""1""><b><U>D</U>escrição:<br>"
    ShowHTML "             <textarea ACCESSKEY=""D"" " & w_Disabled & " name=""w_descricao"" class=""sti"" rows=3 cols=55>" & w_descricao & "</textarea>"
    ShowHTML "      <tr>"
    MontaRadioSN "<b>Ativo:</b>", w_ativo, "w_ativo"
    ShowHTML "      </tr>"
    ShowHTML "      <tr>"
    MontaRadioNS "<b>Padrao:</b>", w_padrao, "w_padrao"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""sti"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    If O = "E" Then
       ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       ShowHTML "            <input class=""stb"" type=""submit"" name=""Botao"" value=""Gravar"">"
    End If
    ShowHTML "            <input class=""stb"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"" name=""Botao"" value=""Cancelar"">"
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
    Estrutura_Texto_Fecha
    Estrutura_Fecha
  Estrutura_Fecha
  Estrutura_Fecha
  Rodape

  Set w_chave           = Nothing
  Set w_nome            = Nothing
  Set w_descricao       = Nothing
  Set w_sigla           = Nothing
  Set w_ativo           = Nothing
  Set w_padrao          = Nothing
  
End Sub

REM =========================================================================
REM Procedimento que executa as operações de BD
REM -------------------------------------------------------------------------
Public Sub Grava

  Dim FS, F1, F2, w_caminho, w_arq_evento, w_arq_texto, w_mes, w_dia, w_ano
  Dim w_segcarnaval, w_carnaval, w_cinzas, w_paixao, w_pascoa, w_christi
  Dim w_data, w_linha
  Dim p_co_escolaridade
  Dim p_co_cidadania
  Dim p_cor
  Dim p_codigo_siape
  Dim p_nome
  Dim p_ativo
  Dim p_ordena
  Dim w_Null

  Cabecalho
  ShowHTML "</HEAD>"
  BodyOpen "onLoad=document.focus();"
  
  AbreSessao	
  Select Case SG
    Case "EOFERIADO"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then

          If O = "G" Then
             For w_cont = 1990 to 2010
                ' Configura o ano atual
                w_ano = w_cont
                w_segcarnaval = Mid(FormataDataEdicao(TercaCarnaval(w_ano)-1),1,5)
                w_carnaval = Mid(FormataDataEdicao(TercaCarnaval(w_ano)),1,5)
                w_cinzas = Mid(FormataDataEdicao(TercaCarnaval(w_ano)+1),1,5)
                w_paixao = Mid(FormataDataEdicao(SextaSanta(w_ano)),1,5)
                w_pascoa = Mid(FormataDataEdicao(DomingoPascoa(w_ano)),1,5)
                w_christi = Mid(FormataDataEdicao(CorpusChristi(w_ano)),1,5)

                 ' Configura o caminho para gravação física de arquivos
                 w_caminho = conFilePhysical & w_cliente & "\"
                 w_arq_evento = w_ano & ".evt"
                 w_arq_texto  = w_ano & ".txt"

                ' Gera o arquivo registro da importação
                Set FS = CreateObject("Scripting.FileSystemObject")
                Set F1 = FS.CreateTextFile(w_caminho & w_arq_texto)
            
                ' Atualiza o arquivo de dias livres
                For w_mes = 1 to 12
                  w_linha = ""
                  For w_dia = 1 to 31
                     w_data = Mid(100+w_dia,2,2) & "/" & Mid(100+w_mes,2,2) & "/" & w_ano
                     If IsDate(w_data) Then
                        If WeekDay(cDate(w_data)) = 1 or WeekDay(cDate(w_data)) = 7 or _
                           InStr("01/01,21/04,01/05,07/09,12/10,02/11,15/11,25/12," & _
                                 w_segcarnaval &","& w_carnaval &","& w_cinzas &","& w_paixao &","& w_pascoa &","& w_christi, _
                                 Mid(w_data,1,5)) > 0 _
                        Then
                           w_linha = w_linha &  "1"
                        Else
                           w_linha = w_linha &  "0"
                        End If
                     Else
                        w_dia = 32
                     End If
                  Next
                  F1.WriteLine w_linha
                Next
            
                ' Atualiza o arquivo de eventos
                Set F2 = FS.CreateTextFile(w_caminho & w_arq_evento)
                F2.WriteLine "01 1 ""Confraterização universal"""
                F2.WriteLine "04 21 ""Tiradentes"""
                F2.WriteLine "05 1 ""Dia do Trabalho"""
                F2.WriteLine "09 7 ""Independência do Brasil"""
                F2.WriteLine "10 12 ""Nossa Senhora Aparecida"""
                F2.WriteLine "11 2 ""Finados"""
                F2.WriteLine "11 15 ""Proclamação da República"""
                F2.WriteLine "12 25 ""Natal"""
                F2.WriteLine Mid(w_segcarnaval,4,2) & " " & Mid(w_segcarnaval,1,2) & " ""Ponto facultativo"""
                F2.WriteLine Mid(w_carnaval,4,2) & " " & Mid(w_carnaval,1,2) & " ""Carnaval"""
                F2.WriteLine Mid(w_cinzas,4,2) & " " & Mid(w_cinzas,1,2) & " ""Cinzas - Meio expediente"""
                F2.WriteLine Mid(w_paixao,4,2) & " " & Mid(w_paixao,1,2) & " ""Paixão de Cristo"""
                F2.WriteLine Mid(w_pascoa,4,2) & " " & Mid(w_pascoa,1,2) & " ""Páscoa"""
                F2.WriteLine Mid(w_christi,4,2) & " " & Mid(w_christi,1,2) & " ""Corpus Christi"""

                F1.Close
                f2.Close
          
             Next
          End If
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "EOTPUNID"
       p_nome            = uCase(Request("p_nome"))
       p_ativo           = uCase(Request("p_ativo"))
       p_ordena          = uCase(Request("p_ordena"))
  
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          DML_PutEOTipoUni O, _
                   Request("w_sq_tipo_unidade"), w_cliente, Request("w_nome"), _
                   Request("w_ativo")             
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & MontaFiltro("GET") & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "EOAREAATU"
       p_nome            = uCase(Request("p_nome"))
       p_ativo           = uCase(Request("p_ativo"))
       p_ordena          = uCase(Request("p_ordena"))
  
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          DML_PutEOAAtuac O, _
                   Request("w_sq_area_atuacao"), w_cliente, Request("w_nome"), _
                   Request("w_ativo")
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "EOTPPOSTO"

       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          DML_PutEOTipoPosto O, _
                   Request("w_chave"), w_cliente, Request("w_nome"), Request("w_sigla"), _
                   Request("w_descricao"), Request("w_ativo"), Request("w_padrao")            
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case Else
       ScriptOpen "JavaScript"
       ShowHTML "  alert('Bloco de dados não encontrado: " & SG & "');"
       ShowHTML "  history.back(1);"
       ScriptClose
  End Select

  Set p_co_escolaridade = Nothing
  Set p_co_cidadania    = Nothing
  Set p_cor             = Nothing
  Set p_codigo_siape    = Nothing
  Set p_nome            = Nothing
  Set p_ativo           = Nothing
  Set p_ordena          = Nothing
  Set w_Null            = Nothing
End Sub

REM =========================================================================
REM Rotina principal
REM -------------------------------------------------------------------------
Sub Main
  ' Verifica se o usuário tem lotação e localização
  If (len(Session("LOTACAO")&"") = 0 or len(Session("LOCALIZACAO")&"") = 0) and Session("LogOn") = "Sim" Then
    ScriptOpen "JavaScript"
    ShowHTML " alert('Você não tem lotação ou localização definida. Entre em contato com o RH!'); "
    ShowHTML " top.location.href='Default.asp'; "
    ScriptClose
   Exit Sub
  End If

  Select Case Par
    Case "FERIADO"   Feriado
    Case "TPUNIDADE" TipoUnidade
    Case "AREA"      AreaAtuacao
    Case "TPPOSTO"   TipoPosto
    Case "GRAVA"     Grava
    Case Else
       Cabecalho
       BodyOpen "onLoad=document.focus();"
       Estrutura_Topo_Limpo
       Estrutura_Menu
       Estrutura_Corpo_Abre
       Estrutura_Texto_Abre
       ShowHTML "<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src=""images/icone/underc.gif"" align=""center""> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>"
         Estrutura_Texto_Fecha
         Estrutura_Fecha
       Estrutura_Fecha
       Estrutura_Fecha
       Rodape
  End Select
End Sub
%>

