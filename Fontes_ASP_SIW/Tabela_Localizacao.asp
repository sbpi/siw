<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE FILE="Constants.inc" -->
<!-- #INCLUDE FILE="jScript.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Geral.asp" -->
<!-- #INCLUDE FILE="DB_Seguranca.asp" -->
<!-- #INCLUDE FILE="DML_Tabela_Localizacao.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /Tabela_Localizacao.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Gerencia a atualização das tabelas de localização
REM Mail     : alex@sbpi.com.br
REM Criacao  : 18/03/2003, 21:02
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
Dim R, O, w_Cont, w_Pagina, w_Disabled, w_TP, w_troca,  w_cor
Dim w_Assinatura, w_filter, w_menu, w_cliente
Dim w_dir_volta
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
w_Pagina     = "Tabela_Localizacao.asp?par="
w_Disabled   = "ENABLED"

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
  Case Else
     w_TP = TP & " - Listagem"
End Select

w_cliente = RetornaCliente()
w_menu    = RetornaMenu(w_cliente, SG) 

Main

FechaSessao

Set w_cor       = Nothing
Set w_menu      = Nothing
Set w_cliente   = Nothing

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
Set w_troca     = Nothing
Set w_Assinatura= Nothing

REM =========================================================================
REM Rotina da tabela de cidades
REM -------------------------------------------------------------------------
Sub Cidade

  Dim w_sq_cidade
  Dim w_sq_pais, p_sq_pais
  Dim w_co_uf, p_co_uf
  Dim w_nome, p_nome
  Dim w_ddd
  Dim w_codigo_ibge
  Dim w_ativo, p_ativo
  Dim w_capital
  Dim p_Ordena
  Dim w_libera_edicao
  
  DB_GetMenuData RS, w_menu
  w_libera_edicao = RS("libera_edicao")
  
  p_sq_pais     = uCase(Request("p_sq_pais"))
  p_co_uf       = uCase(Request("p_co_uf"))
  p_nome        = uCase(Request("p_nome"))
  p_ativo       = uCase(Request("p_ativo"))
  p_ordena      = uCase(Request("p_ordena"))
  
  If p_sq_pais & p_co_uf & p_nome = "" Then O = "P" End If
  
  If w_troca > "" Then
     w_sq_cidade        = Request("w_sq_cidade")
     w_sq_pais          = Request("w_sq_pais")
     w_co_uf            = Request("w_co_uf")
     w_nome             = Request("w_nome")
     w_ddd              = Request("w_ddd")
     w_codigo_ibge      = Request("w_codigo_ibge")
     w_capital          = Request("w_capital")
  ElseIf O = "L" Then
     DB_GetCityList RS, p_sq_pais, p_co_uf, p_nome, null
     If p_ordena > "" Then RS.sort = p_ordena Else RS.sort = "NOME" End If
  ElseIf O = "A" or O = "E" Then
     w_sq_cidade = Request("w_sq_cidade")
     DB_GetCityData RS, w_sq_cidade               
     w_sq_pais          = RS("sq_pais")
     w_co_uf            = RS("co_uf")
     w_nome             = RS("nome")
     w_ddd              = RS("ddd")
     w_codigo_ibge      = RS("codigo_ibge")
     w_capital          = RS("capital")
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_sq_pais", "País", "SELECT", "1", "1", "10", "", "1"
        Validate "w_co_uf", "Estado", "SELECT", "1", "1", "3", "1", "1"
        Validate "w_nome", "Nome", "1", "1", "3", "60", "1", "1"
        Validate "w_ddd", "DDD", "1", "", "2", "4", "", "1"
        Validate "w_codigo_ibge", "IBGE", "1", "", "1", "20", "1", "1"
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     ElseIf O = "E" Then
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
        ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
        ShowHTML "     { return (true); }; "
        ShowHTML "     { return (false); }; "
     ElseIf O="P" Then
        Validate "p_sq_pais", "Pais", "SELECT", "1", "1", "10", "", "1"
        Validate "p_co_uf", "UF", "SELECT", "1", "2", "2", "1", ""
        Validate "p_nome", "nome", "1", "", "3", "50", "1", "1"
        Validate "P4", "Linhas por página", "1", "1", "1", "4", "", "0123456789"
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  If w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf InStr("IAE",O) > 0 Then
     If O = "E" Then
        BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
     Else
        BodyOpen "onLoad='document.Form.w_sq_pais.focus()';"
     End If
  ElseIf InStr("P",O) > 0 Then
     BodyOpen "onLoad='document.Form.p_sq_pais.focus()';"
  Else
     BodyOpen "onLoad=document.focus();"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ShowHTML "<tr><td><font size=""2"">"
    If w_libera_edicao = "S" Then
       ShowHTML "<a accesskey=""I"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_sq_pais=" & p_sq_pais & "&p_co_uf=" & p_co_uf & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """><u>I</u>ncluir</a>&nbsp;"
    End If
    If p_sq_pais & p_co_uf & p_nome & p_ativo & p_Ordena > "" Then
       ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_sq_pais=" & p_sq_pais & "&p_co_uf=" & p_co_uf & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a></font>"
    Else
       ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_sq_pais=" & p_sq_pais & "&p_co_uf=" & p_co_uf & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """><u>F</u>iltrar (Inativo)</a>"
    End If
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""2""><b>Chave</font></td>"
    ShowHTML "          <td><font size=""2""><b>País</font></td>"
    ShowHTML "          <td><font size=""2""><b>UF</font></td>"
    ShowHTML "          <td><font size=""2""><b>Cidade</font></td>"
    ShowHTML "          <td><font size=""2""><b>DDD</font></td>"
    ShowHTML "          <td><font size=""2""><b>IBGE</font></td>"
    ShowHTML "          <td><font size=""2""><b>Capital</font></td>"
    If w_libera_edicao = "S" Then
       ShowHTML "          <td><font size=""2""><b>Operações</font></td>"
    End If
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=8 align=""center""><font  size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF and RS.AbsolutePage = P3
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("sq_cidade") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("sq_pais") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("co_uf") & "</td>"
        ShowHTML "        <td><font  size=""1"">" & RS("nome") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("ddd") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("codigo_ibge") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("capital") & "</td>"
        If w_libera_edicao = "S" Then
           ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
           ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_cidade=" & RS("sq_cidade") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_sq_pais=" & p_sq_pais & "&p_co_uf=" & p_co_uf & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """>Alterar</A>&nbsp"
           ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_cidade=" & RS("sq_cidade") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_sq_pais=" & p_sq_pais & "&p_co_uf=" & p_co_uf & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """>Excluir</A>&nbsp"
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
    MontaBarra w_pagina&par&"&R="&w_Pagina&par&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG, RS.PageCount, P3, P4, RS.RecordCount
    ShowHTML "</tr>"        
    DesconectaBD	 
  ElseIf Instr("IAE",O) > 0 Then
    If O = "E" Then w_Disabled = "DISABLED" End If
    AbreForm "Form", w_Pagina&"Grava", "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,R,O    
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_sq_pais"" value=""" & p_sq_pais &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_co_uf"" value=""" & p_co_uf &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_nome"" value=""" & p_nome &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_ativo"" value=""" & p_ativo &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_ordena"" value=""" & p_ordena &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_cidade"" value=""" & w_sq_cidade &""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""90%"" border=""0"">"
    ShowHTML "      <tr align=""left""><td valign=""top""><table width=""100%"" cellpadding=0 cellspacing=0><tr><td>"
    ShowHTML "      <tr>"
    SelecaoPais "<u>P</u>aís:", "P", "Selecione o país na relação.", w_sq_pais, null, "w_sq_pais", null, "onChange=""document.Form.action='" & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='w_co_uf'; document.Form.submit();"""
    SelecaoEstado "<u>U</u>F:", "U", "Selecione a UF na relação.", w_co_uf, w_sq_pais, "N", "w_co_uf", null, null
    ShowHTML "      </tr></table></td></tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_nome"" size=""60"" maxlength=""60"" value=""" & w_nome & """></td></tr>"
    ShowHTML "      <tr align=""left""><td valign=""top""><table width=""100%"" cellpadding=0 cellspacing=0><tr><td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>D</U>DD:<br><INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_ddd"" size=""4"" maxlength=""4"" value=""" & w_ddd & """></td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b>I<U>B</U>GE:<br><INPUT ACCESSKEY=""B"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_codigo_ibge"" size=""6"" maxlength=""6"" value=""" & w_codigo_ibge & """></td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b>Capital?</b><br>"
    If w_capital = "S" Then
       ShowHTML "              <input " & w_Disabled & " class=""STR"" type=""radio"" name=""w_capital"" value=""S"" checked> Sim <input " & w_Disabled & " class=""STR"" type=""radio"" name=""w_capital"" value=""N""> Não"
    Else
       ShowHTML "              <input " & w_Disabled & " class=""STR"" type=""radio"" name=""w_capital"" value=""S""> Sim <input " & w_Disabled & " class=""STR"" type=""radio"" name=""w_capital"" value=""N"" checked> Não"
    End If
    ShowHTML "      </tr></table></td></tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""STI"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    If O = "E" Then
       ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Gravar"">"
    End If
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & "&O=L&p_sq_pais=" & p_sq_pais & "&p_co_uf=" & p_co_uf & "';"" name=""Botao"" value=""Cancelar"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  ElseIf Instr("P",O) > 0 Then
    AbreForm "Form", w_Pagina&par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""70%"" border=""0"">"
    ShowHTML "      <tr align=""left""><td valign=""top""><table width=""100%"" cellpadding=0 cellspacing=0><tr><td>"
    ShowHTML "<tr>"
    SelecaoPais "<u>P</u>aís:", "P", null, p_sq_pais, null, "p_sq_pais", null, "onChange=""document.Form.O.value='P'; document.Form.w_troca.value='p_co_uf'; document.Form.submit();"""
    SelecaoEstado "<u>U</u>F:", "U", null, p_co_uf, p_sq_pais, "N", "p_co_uf", null, null
    ShowHTML "      </tr></table></td></tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_nome"" size=""50"" maxlength=""50"" value=""" & p_nome & """></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY=""O"" " & w_Disabled & " class=""STS"" name=""p_ordena"" size=""1"">"
    If p_Ordena="NOME" Then
       ShowHTML "          <option value="""">Código<option value=""nome"" SELECTED>Nome<option value=""ativo"">Ativo"
    ElseIf p_Ordena="codigo_siafi" Then
       ShowHTML "          <option value="""">Código<option value=""nome"">Nome<option value=""ativo"">Ativo"
    ElseIf p_Ordena="ATIVO" Then
       ShowHTML "          <option value="""">Código<option value=""nome"">Nome<option value=""ativo"" SELECTED>Ativo"
    Else
       ShowHTML "          <option value="""" SELECTED>Código<option value=""nome"">Nome<option value=""ativo"">Ativo"
    End If
    ShowHTML "          </select></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""STI"" type=""text"" name=""P4"" size=""4"" maxlength=""4"" value=""" & P4 & """></td></tr>"    
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

  Set w_sq_cidade   = Nothing
  Set w_sq_pais     = Nothing
  Set w_co_uf       = Nothing
  Set w_nome        = Nothing
  Set w_ddd         = Nothing
  Set w_codigo_ibge = Nothing
  Set w_ativo       = Nothing
  Set w_capital     = Nothing
  Set w_libera_edicao = Nothing

  Set p_nome        = Nothing
  Set p_sq_pais     = Nothing
  Set p_co_uf       = Nothing
  Set p_ativo       = Nothing
  Set p_Ordena      = Nothing

End Sub

REM =========================================================================
REM Rotina da tabela de estados
REM -------------------------------------------------------------------------
Sub Estado

  Dim w_co_uf
  Dim w_sq_pais, p_sq_pais
  Dim w_sq_regiao, p_sq_regiao
  Dim w_nome
  Dim w_ativo, p_ativo
  Dim w_padrao
  Dim w_codigo_ibge
  Dim w_ordem
  Dim p_Ordena
  Dim w_libera_edicao

  p_sq_pais          = uCase(Request("p_sq_pais"))
  p_sq_regiao        = uCase(Request("p_sq_regiao"))
  p_ativo            = uCase(Request("p_ativo"))
  p_ordena           = uCase(Request("p_ordena"))
  
  DB_GetMenuData RS, w_menu
  w_libera_edicao = RS("libera_edicao")
  
  If p_sq_pais = "" Then O = "P" End If
  
  If w_troca > "" Then
     w_co_uf        = Request("w_co_uf")
     w_sq_pais      = Request("w_sq_pais")
     w_sq_regiao    = Request("w_sq_regiao")
     w_nome         = Request("w_nome")
     w_ativo        = Request("w_ativo")
     w_padrao       = Request("w_padrao")
     w_codigo_ibge  = Request("w_codigo_ibge")
     w_ordem        = Request("w_ordem")
  ElseIf O = "L" Then
     DB_GetStateList RS, Nvl(p_sq_pais,0), p_sq_regiao, p_ativo, null
     If p_ordena > "" Then RS.sort = p_ordena Else RS.sort = "padrao desc, co_uf" End If
  ElseIf O = "A" or O = "E" Then
     w_sq_pais      = Request("w_sq_pais")
     w_co_uf        = Request("w_co_uf")
     DB_GetStateData RS, w_sq_pais, w_co_uf             
     w_sq_regiao    = RS("sq_regiao")
     w_nome         = RS("nome")
     w_ativo        = RS("ativo")
     w_padrao       = RS("padrao")
     w_codigo_ibge  = RS("codigo_ibge")
     w_ordem        = RS("ordem")
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        If O = "I" Then
           Validate "w_sq_pais", "País", "SELECT", "1", "1", "10", "", "1"
        End If
        Validate "w_sq_regiao", "Região", "SELECT", "1", "1", "10", "", "1"
        If O = "I" Then
           Validate "w_co_uf", "Sigla", "1", "1", "2", "3", "1", "1"
        End If
        Validate "w_nome", "Nome", "1", "1", "3", "50", "1", "1"
        Validate "w_codigo_ibge", "Código IBGE", "1", "", "2", "2", "1", "1"
        Validate "w_ordem", "Ordem na região", "1", "", "1", "5", "", "0123456789"
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     ElseIf O = "E" Then
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
        ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
        ShowHTML "     { return (true); }; "
        ShowHTML "     { return (false); }; "
     ElseIf O="P" Then
        Validate "p_sq_pais", "País", "1", "1", "1", "10", "", "1"
        Validate "P4", "Linhas por página", "1", "1", "1", "4", "", "0123456789"        
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ShowHTML "</HEAD>"
  
  If w_troca > "" Then
     BodyOpen "onLoad='document.Form." & w_troca & ".focus()';"
  ElseIf O = "E" Then
     BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
  ElseIf O = "A" Then
     BodyOpen "onLoad='document.Form.w_nome.focus()';"
  ElseIf O = "I" Then
     BodyOpen "onLoad='document.Form.w_sq_pais.focus()';"
  ElseIf InStr("P",O) > 0 Then
     BodyOpen "onLoad='document.Form.p_sq_pais.focus()';"
  Else
     BodyOpen "onLoad=document.focus();"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ShowHTML "<tr><td>"
    If w_libera_edicao = "S" Then
       ShowHTML "<font size=""2""><a accesskey=""I"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_sq_pais=" & p_sq_pais & "&p_sq_regiao=" & p_sq_regiao & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """><u>I</u>ncluir</a>&nbsp;"
    End If
    If p_sq_pais & p_sq_regiao & p_ativo & p_Ordena > "" Then
       ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_sq_pais=" & p_sq_pais & "&p_sq_regiao=" & p_sq_regiao & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a></font>"
    Else
       ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_sq_pais=" & p_sq_pais & "&p_sq_regiao=" & p_sq_regiao & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """><u>F</u>iltrar (Inativo)</a>"
    End If
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""2""><b>País</font></td>"
    ShowHTML "          <td><font size=""2""><b>Região</font></td>"
    ShowHTML "          <td><font size=""2""><b>Sigla</font></td>"
    ShowHTML "          <td><font size=""2""><b>Nome</font></td>"
    ShowHTML "          <td><font size=""2""><b>IBGE</font></td>"
    ShowHTML "          <td><font size=""2""><b>Ativo</font></td>"
    ShowHTML "          <td><font size=""2""><b>Padrão</font></td>"
    If w_libera_edicao = "S" Then    
       ShowHTML "          <td><font size=""2""><b>Operações</font></td>"
    End If
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=8 align=""center""><font  size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF and RS.AbsolutePage = P3
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """>"
        ShowHTML "        <td><font size=""1"">" & RS("nome_pais") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("nome_regiao") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("co_uf") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("nome") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("codigo_ibge") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("ativodesc") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("padraodesc") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        If w_libera_edicao = "S" Then
           ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_pais=" & RS("sq_pais") & "&w_co_uf=" & RS("co_uf") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_sq_pais=" & p_sq_pais & "&p_sq_regiao=" & p_sq_regiao & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """>Alterar</A>&nbsp"
           ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_pais=" & RS("sq_pais") & "&w_co_uf=" & RS("co_uf") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_sq_pais=" & p_sq_pais & "&p_sq_regiao=" & p_sq_regiao & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """>Excluir</A>&nbsp"
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
    MontaBarra w_pagina&par&"&R="&w_Pagina&par&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG, RS.PageCount, P3, P4, RS.RecordCount
    ShowHTML "</tr>"    
    DesconectaBD	 
  ElseIf Instr("IAE",O) > 0 Then
    If O = "E" Then
       w_Disabled = "DISABLED"
    End If
    AbreForm "Form", w_Pagina&"Grava", "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,R,O    
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_sq_pais"" value=""" & p_sq_pais &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_sq_regiao"" value=""" & p_sq_regiao &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_ativo"" value=""" & p_ativo &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_ordena"" value=""" & p_ordena &""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""70%"" border=""0"">"
    ShowHTML "      <tr align=""left""><td valign=""top""><table width=""100%"" cellpadding=0 cellspacing=0><tr><td>"
    ShowHTML "<tr>"
    If O = "I" Then
       SelecaoPais "<u>P</u>aís:", "P", null, w_sq_pais, null, "w_sq_pais", null, "onChange=""document.Form.action='" & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='w_sq_regiao'; document.Form.submit();"""
    Else
       SelecaoPais "<u>P</u>aís:", "P", null, w_sq_pais, null, "w_sq_pais1", "ATIVO", "disabled"
       ShowHTML "<INPUT type=""hidden"" name=""w_sq_pais"" value=""" & w_sq_pais &""">"
    End If
    SelecaoRegiao "<u>R</u>egião:", "R", null, w_sq_regiao, w_sq_pais, "w_sq_regiao", null, null
    ShowHTML "      </tr></table></td></tr>"
    ShowHTML "      <tr align=""left""><td valign=""top""><table width=""100%"" cellpadding=0 cellspacing=0><tr><td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_nome"" size=""30"" maxlength=""30"" value=""" & w_nome & """></td>"
    If O = "I" Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>Sig<U>l</U>a:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_co_uf"" size=""2"" maxlength=""2"" value=""" & w_co_uf & """></td>"
    Else
       ShowHTML "          <td valign=""top""><font size=""1""><b>Sig<U>l</U>a:<br><INPUT DISABLED ACCESSKEY=""L"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_co_uf1"" size=""2"" maxlength=""2"" value=""" & w_co_uf & """></td>"
       ShowHTML "<INPUT type=""hidden"" name=""w_co_uf"" value=""" & w_co_uf &""">"
    End If
    ShowHTML "      </tr></table></td></tr>"
    ShowHTML "      <tr align=""left""><td valign=""top""><table width=""100%"" cellpadding=0 cellspacing=0><tr><td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>O</U>rdem na região:<br><INPUT ACCESSKEY=""O"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_ordem"" size=""5"" maxlength=""5"" value=""" & w_ordem & """></td>"
    ShowHTML "          <td valign=""top""><font size=""1""><b>Código <U>I</U>BGE:<br><INPUT ACCESSKEY=""I"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_codigo_ibge"" size=""2"" maxlength=""2"" value=""" & w_codigo_ibge & """></td>"
    ShowHTML "      </tr></table></td></tr>"
    ShowHTML "      <tr align=""left"">"
    MontaRadioNS "Padrão?", w_padrao, "w_padrao"
    ShowHTML "      </tr>"
    ShowHTML "      <tr align=""left"">"
    MontaRadioSN "Ativo?", w_ativo, "w_ativo"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""STI"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    If O = "E" Then
       ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Gravar"">"
    End If
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_sq_pais=" & p_sq_pais & "&p_sq_regiao=" & p_sq_regiao & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & "';"" name=""Botao"" value=""Cancelar"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  ElseIf Instr("P",O) > 0 Then
    AbreForm "Form", w_Pagina&par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_troca"" value="""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""70%"" border=""0"">"
    SelecaoPais "<u>P</u>aís:", "P", null, p_sq_pais, null, "p_sq_pais", null, "onChange=""document.Form.action='" & w_pagina & par & "'; document.Form.O.value='" & O & "'; document.Form.w_troca.value='p_sq_regiao'; document.Form.submit();"""
    SelecaoRegiao "<u>R</u>egião:", "R", null, p_sq_regiao, p_sq_pais, "p_sq_regiao", null, null
    ShowHTML "      <tr align=""left"">"
    MontaRadioSN "Ativo?", p_ativo, "p_ativo"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY=""O"" " & w_Disabled & " class=""STS"" name=""p_ordena"" size=""1"">"
    If p_Ordena="C.ORDEM" Then
       ShowHTML "          <option value="""">Código<option value=""c.ordem"" SELECTED>Região<option value=""ativo"">Ativo"
    ElseIf p_Ordena="ATIVO" Then
       ShowHTML "          <option value="""">Código<option value=""c.ordem"">Região<option value=""ativo"" SELECTED>Ativo"
    Else
       ShowHTML "          <option value="""" SELECTED>Código<option value=""c.ordem"">Região<option value=""ativo"">Ativo"
    End If
    ShowHTML "          </select></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""STI"" type=""text"" name=""P4"" size=""4"" maxlength=""4"" value=""" & P4 & """></td></tr>"
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

  Set w_co_uf           = Nothing
  Set w_sq_pais         = Nothing
  Set p_sq_pais         = Nothing
  Set w_sq_regiao       = Nothing
  Set p_sq_regiao       = Nothing
  Set w_nome            = Nothing
  Set w_ativo           = Nothing
  Set p_ativo           = Nothing
  Set w_padrao          = Nothing
  Set w_codigo_ibge     = Nothing
  Set w_ordem           = Nothing
  Set p_Ordena          = Nothing
  Set w_libera_edicao   = Nothing

End Sub

REM =========================================================================
REM Rotina da tabela de regiões
REM -------------------------------------------------------------------------
Sub Regiao

  Dim w_sq_regiao
  Dim w_nome, p_nome
  Dim w_sq_pais, p_sq_pais
  Dim w_sigla, p_sigla
  Dim w_ordem
  Dim p_Ordena
  Dim w_libera_edicao

  p_nome             = uCase(Request("p_nome"))
  p_sq_pais          = uCase(Request("p_sq_pais"))
  p_ordena           = uCase(Request("p_ordena"))
  
  DB_GetMenuData RS, w_menu
  w_libera_edicao = RS("libera_edicao")
  
  If O = "L" Then
     DB_GetRegionList RS, p_sq_pais, "N", p_nome
     If p_ordena > "" Then RS.sort = p_ordena Else RS.sort = "padrao desc,sq_pais,sq_regiao" End If
  ElseIf O = "A" or O = "E" Then
     w_sq_regiao = Request("w_sq_regiao")
     DB_GetRegionData RS, w_sq_regiao              
     w_nome                 = RS("nome")
     w_ordem                = RS("ordem")
     w_sigla                = RS("sigla")
     w_sq_pais              = RS("sq_pais")
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_sq_pais", "País", "1", "1", "1", "10", "", "1"
        Validate "w_nome", "Nome", "1", "1", "3", "20", "1", "1"
        Validate "w_sigla", "Sigla", "1", "", "1", "2", "1", "1"
        Validate "w_ordem", "Ordem", "1", "1", "1", "4", "", "0123456789"
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     ElseIf O = "E" Then
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
        ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
        ShowHTML "     { return (true); }; "
        ShowHTML "     { return (false); }; "
     ElseIf O="P" Then
        Validate "p_nome", "nome", "1", "", "3", "20", "1", "1"
        Validate "P4", "Linhas por página", "1", "1", "1", "4", "", "0123456789"        
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
        BodyOpen "onLoad='document.Form.w_sq_pais.focus()';"
     End If
  ElseIf InStr("P",O) > 0 Then
     BodyOpen "onLoad='document.Form.p_nome.focus()';"
  Else
     BodyOpen "onLoad=document.focus();"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ShowHTML "<tr><td><font size=""2"">"
    If w_libera_edicao = "S" Then
       ShowHTML "<a accesskey=""I"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_sq_pais=" & p_sq_pais & "&p_ordena=" & p_ordena & """><u>I</u>ncluir</a>&nbsp;"
    End If
    If p_nome & p_sq_pais & p_Ordena > "" Then
       ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_sq_pais=" & p_sq_pais & "&p_ordena=" & p_ordena & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a></font>"
    Else
       ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_sq_pais=" & p_sq_pais & "&p_ordena=" & p_ordena & """><u>F</u>iltrar (Inativo)</a>"
    End If
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""2""><b>Chave</font></td>"
    ShowHTML "          <td><font size=""2""><b>País</font></td>"
    ShowHTML "          <td><font size=""2""><b>Nome</font></td>"
    ShowHTML "          <td><font size=""2""><b>Sigla</font></td>"
    ShowHTML "          <td><font size=""2""><b>Ordem</font></td>"
    If w_libera_edicao = "S" Then
       ShowHTML "          <td><font size=""2""><b>Operações</font></td>"
    End If
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font  size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF and RS.AbsolutePage = P3
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("sq_regiao") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("nome_pais") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("nome") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("sigla") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("ordem") & "</td>"
        If w_libera_edicao = "S" Then
           ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
           ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_regiao=" & RS("sq_regiao") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_sq_pais=" & p_sq_pais & "&p_ordena=" & p_ordena & """>Alterar</A>&nbsp"
           ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_regiao=" & RS("sq_regiao") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_sq_pais=" & p_sq_pais & "&p_ordena=" & p_ordena & """>Excluir</A>&nbsp"
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
    MontaBarra w_pagina&par&"&R="&w_Pagina&par&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG, RS.PageCount, P3, P4, RS.RecordCount
    ShowHTML "</tr>"        
    DesconectaBD	 
  ElseIf Instr("IAE",O) > 0 Then
    If O = "E" Then
       w_Disabled = "DISABLED"
    End If
    AbreForm "Form", w_Pagina&"Grava", "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,R,O    
    ShowHTML "<INPUT type=""hidden"" name=""p_nome"" value=""" & p_nome &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_sq_pais"" value=""" & p_sq_pais &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_ordena"" value=""" & p_ordena &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_regiao"" value=""" & w_sq_regiao &""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""70%"" border=""0"">"
    ShowHTML "      <tr>"
    SelecaoPais "<u>P</u>aís:", "P", null, w_sq_pais, null, "w_sq_pais", "ATIVO", null
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_nome"" size=""50"" maxlength=""50"" value=""" & w_nome & """></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>S</U>igla:<br><INPUT ACCESSKEY=""S"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_sigla"" size=""2"" maxlength=""2"" value=""" & w_sigla & """></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>O<U>r</U>dem:<br><INPUT ACCESSKEY=""R"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_ordem"" size=""4"" maxlength=""4"" value=""" & w_ordem & """></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""STI"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    If O = "E" Then
       ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Gravar"">"
    End If
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_sigla=" & p_sigla & "&p_sq_pais=" & p_sq_pais & "&p_ordena=" & p_ordena & "';"" name=""Botao"" value=""Cancelar"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  ElseIf Instr("P",O) > 0 Then
    AbreForm "Form", w_Pagina&par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"
  
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""70%"" border=""0"">"
    ShowHTML "      <tr>"
    SelecaoPais "<u>P</u>aís:", "P", null, p_sq_pais, null, "p_sq_pais", "ATIVO", null
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_nome"" size=""50"" maxlength=""50"" value=""" & p_nome & """></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY=""O"" " & w_Disabled & " class=""STS"" name=""p_ordena"" size=""1"">"
    If p_Ordena="NOME" Then
       ShowHTML "          <option value=""sq_regiao"">Código<option value=""nome"" SELECTED>Nome<option value="""">Ordem"
    ElseIf p_Ordena="CODIGO" Then
       ShowHTML "          <option value=""sq_regiao"">Código<option value=""nome"">Nome<option value="""">Ordem"
    Else
       ShowHTML "          <option value=""sq_regiao"" SELECTED>Código<option value=""nome"">Nome<option value="""" SELECTED>Ordem"
    End If
    ShowHTML "          </select></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""STI"" type=""text"" name=""P4"" size=""4"" maxlength=""4"" value=""" & P4 & """></td></tr>"    
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

  Set w_sq_regiao = Nothing
  Set w_sigla     = Nothing
  Set w_nome      = Nothing
  Set w_ordem     = Nothing
  Set w_sq_pais     = Nothing
  Set p_nome      = Nothing
  Set p_sq_pais     = Nothing
  Set p_ordena    = Nothing
  Set w_libera_edicao = Nothing

End Sub

REM =========================================================================
REM Rotina da tabela de países
REM -------------------------------------------------------------------------
Sub Pais

  Dim w_sq_pais, w_padrao
  Dim w_nome, p_nome
  Dim w_ativo, p_ativo
  Dim w_sigla, p_sigla
  Dim w_ddi
  Dim p_Ordena
  Dim w_libera_edicao

  p_nome            = uCase(Request("p_nome"))
  p_ativo           = uCase(Request("p_ativo"))
  p_ordena          = uCase(Request("p_ordena"))
  p_sigla           = uCase(Request("p_sigla"))
  
  DB_GetMenuData RS, w_menu
  w_libera_edicao = RS("libera_edicao")
  
  If O = "L" Then
     DB_GetCountryList RS, null, p_nome, p_ativo, p_sigla
     If p_ordena > "" Then RS.sort = p_ordena Else RS.sort = "padrao desc, sq_pais" End If
  ElseIf O = "A" or O = "E" Then
     w_sq_pais = Request("w_sq_pais")
     DB_GetCountryData RS, w_sq_pais
     w_nome                 = RS("nome")
     w_ddi                  = RS("ddi")
     w_sigla                = RS("sigla")
     w_ativo                = RS("ativo")
     w_padrao               = RS("padrao")
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_nome", "Nome", "1", "1", "3", "50", "1", "1"
        Validate "w_ddi", "DDI", "1", "1", "2", "10", "1", "1"
        Validate "w_sigla", "Sigla", "1", "1", "3", "3", "1", "1"
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     ElseIf O = "E" Then
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
        ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
        ShowHTML "     { return (true); }; "
        ShowHTML "     { return (false); }; "
     ElseIf O="P" Then
        Validate "p_nome", "nome", "1", "", "3", "50", "1", "1"
        Validate "p_sigla", "Sigla", "1", "", "3", "3", "1", ""
        Validate "P4", "Linhas por página", "1", "1", "1", "4", "", "0123456789"        
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
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ShowHTML "<tr><td>"
    If w_libera_edicao = "S" Then
       ShowHTML "<font size=""2""><a accesskey=""I"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_sigla=" & p_sigla & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """><u>I</u>ncluir</a>&nbsp;"
    End If
    If p_nome & p_sigla & p_ativo & p_Ordena > "" Then
       ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_sigla=" & p_sigla & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """><u><font color=""#BC5100"">F</u>iltrar (Ativo)</font></a></font>"
    Else
       ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_sigla=" & p_sigla & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """><u>F</u>iltrar (Inativo)</a>"
    End If
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "          <td><font size=""2""><b>Chave</font></td>"
    ShowHTML "          <td><font size=""2""><b>Nome</font></td>"
    ShowHTML "          <td><font size=""2""><b>Sigla</font></td>"
    ShowHTML "          <td><font size=""2""><b>DDI</font></td>"
    ShowHTML "          <td><font size=""2""><b>Ativo</font></td>"
    ShowHTML "          <td><font size=""2""><b>Padrao</font></td>"
    If w_libera_edicao = "S" Then
       ShowHTML "          <td><font size=""2""><b>Operações</font></td>"
    End If
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=7 align=""center""><font  size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else      
      rs.PageSize     = P4
      rs.AbsolutePage = P3
      While Not RS.EOF and RS.AbsolutePage = P3
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("sq_pais") & "</td>"
        ShowHTML "        <td align=""left""><font size=""1"">" & RS("nome") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("sigla") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("ddi") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("ativodesc") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("padraodesc") & "</td>"
        If w_libera_edicao = "S" Then
           ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
           ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_pais=" & RS("sq_pais") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_sigla=" & p_sigla & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """>Alterar</A>&nbsp"
           ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_pais=" & RS("sq_pais") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_sigla=" & p_sigla & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & """>Excluir</A>&nbsp"
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
    MontaBarra w_pagina&par&"&R="&w_Pagina&par&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG, RS.PageCount, P3, P4, RS.RecordCount
    ShowHTML "</tr>"    
    DesconectaBD	 
  ElseIf Instr("IAE",O) > 0 Then
    If O = "E" Then
       w_Disabled = "DISABLED"
    End If
    AbreForm "Form", w_Pagina&"Grava", "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,R,O        
    ShowHTML "<INPUT type=""hidden"" name=""p_nome"" value=""" & p_nome &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_sigla"" value=""" & p_sigla &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_ativo"" value=""" & p_ativo &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_ordena"" value=""" & p_ordena &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_pais"" value=""" & w_sq_pais &""">"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""70%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_nome"" size=""50"" maxlength=""50"" value=""" & w_nome & """></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>D</U>DI:<br><INPUT ACCESSKEY=""D"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_ddi"" size=""10"" maxlength=""10"" value=""" & w_ddi & """></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>S</U>igla:<br><INPUT ACCESSKEY=""S"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_sigla"" size=""3"" maxlength=""3"" value=""" & w_sigla & """></td>"
    ShowHTML "      <tr align=""left"">"
    MontaRadioSN "Ativo?", w_ativo, "w_ativo"
    ShowHTML "      </tr>"
    ShowHTML "      <tr align=""left"">"
    MontaRadioNS "Padrão?", w_padrao, "w_padrao"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""STI"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    If O = "E" Then
       ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Gravar"">"
    End If
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_sigla=" & p_sigla & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & "';"" name=""Botao"" value=""Cancelar"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  ElseIf Instr("P",O) > 0 Then
    AbreForm "Form", w_Pagina&par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"
  
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""70%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_nome"" size=""50"" maxlength=""50"" value=""" & p_nome & """></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>S</U>igla:<br><INPUT ACCESSKEY=""S"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_sigla"" size=""3"" maxlength=""3"" value=""" & p_sigla & """></td>"
    ShowHTML "      <tr align=""left"">"
    MontaRadioSN "Ativo?", p_ativo, "p_ativo"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY=""O"" " & w_Disabled & " class=""STS"" name=""p_ordena"" size=""1"">"
    If p_Ordena="NOME" Then
       ShowHTML "          <option value="""">Código<option value=""nome"" SELECTED>Nome<option value=""sigla"">Sigla<option value=""ativo"">Ativo"
    ElseIf p_Ordena="sigla" Then
       ShowHTML "          <option value="""">Código<option value=""nome"">Nome<option value=""sigla"" SELECTED>Sigla<option value=""ativo"">Ativo"
    ElseIf p_Ordena="ATIVO" Then
       ShowHTML "          <option value="""">Código<option value=""nome"">Nome<option value=""sigla"">Sigla<option value=""ativo"" SELECTED>Ativo"
    Else
       ShowHTML "          <option value="""" SELECTED>Código<option value=""nome"">Nome<option value=""sigla"">Sigla<option value=""ativo"">Ativo"
    End If
    ShowHTML "          </select></td>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""STI"" type=""text"" name=""P4"" size=""4"" maxlength=""4"" value=""" & P4 & """></td></tr>"
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

  Set w_sq_pais         = Nothing
  Set w_sigla           = Nothing
  Set w_nome            = Nothing
  Set w_ddi             = Nothing
  Set w_ativo           = Nothing
  Set w_padrao          = Nothing
  Set p_nome            = Nothing
  Set p_sigla           = Nothing
  Set p_ativo           = Nothing
  Set p_ordena          = Nothing
  Set w_libera_edicao   = Nothing

End Sub

REM =========================================================================
REM Procedimento que executa as operações de BD
REM -------------------------------------------------------------------------
Public Sub Grava

  Dim p_codigo
  Dim p_sq_pais
  Dim p_sq_regiao
  Dim p_co_uf
  Dim p_sigla
  Dim p_nome
  Dim p_ativo
  Dim p_ordena
  Dim w_Null

  Cabecalho
  ShowHTML "</HEAD>"
  BodyOpen "onLoad=document.focus();"
  
  AbreSessao	
  Select Case SG
    Case "COCIDADE"
       p_nome            = uCase(Request("p_nome"))
       p_sq_pais         = uCase(Request("p_sq_pais"))
       p_co_uf           = uCase(Request("p_co_uf"))
       p_ordena          = uCase(Request("p_ordena"))
  
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          DML_COCIDADE O, _
                   Request("w_sq_cidade"), Request("w_ddd"), Request("w_codigo_ibge"), Request("w_sq_pais"), _
                   Request("w_sq_regiao"), Request("w_co_uf"), Request("w_nome"), Request("w_capital")
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_sq_pais=" & p_sq_pais & "&p_co_uf=" & p_co_uf & "&p_ordena=" & p_ordena & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "COPAIS"
       p_nome            = uCase(Request("p_nome"))
       p_codigo          = uCase(Request("p_codigo"))
       p_ativo           = uCase(Request("p_ativo"))
       p_ordena          = uCase(Request("p_ordena"))
  
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          DML_COPAIS O, _
                   Request("w_sq_pais"), Request("w_nome"), Request("w_ativo"), _
                   Request("w_padrao"), Request("w_ddi"), Request("w_sigla")
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_sigla=" & p_sigla & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "COREGIAO"
       p_nome            = uCase(Request("p_nome"))
       p_sq_pais         = uCase(Request("p_sq_pais"))
       p_ordena          = uCase(Request("p_ordena"))
  
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          DML_COREGIAO O, _
                   Request("w_sq_regiao"), Request("w_sq_pais"), Request("w_nome"), _
                   Request("w_sigla"), Request("w_ordem")
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" & p_nome & "&p_sq_pais=" & p_sq_pais & "&p_ordena=" & p_ordena & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
    Case "COUF"
       p_sq_pais         = uCase(Request("p_sq_pais"))
       p_sq_regiao       = uCase(Request("p_sq_regiao"))
       p_ativo           = uCase(Request("p_ativo"))
       p_ordena          = uCase(Request("p_ordena"))
  
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          DML_COUF O, _
                   Request("w_co_uf"), Request("w_sq_pais"), Request("w_sq_regiao"), Request("w_nome"), _
                   Request("w_ativo"), Request("w_padrao"), Request("w_codigo_ibge"), Request("w_ordem")
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_sq_pais=" & p_sq_pais & "&p_sq_regiao=" & p_sq_regiao & "&p_ativo=" & p_ativo & "&p_ordena=" & p_ordena & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
  End Select

  Set p_sq_pais         = Nothing
  Set p_sq_regiao       = Nothing
  Set p_co_uf           = Nothing
  Set p_codigo          = Nothing
  Set p_sigla           = Nothing
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
  Select Case Par
    Case "PAIS"
       Pais
    Case "REGIAO"
       Regiao
    Case "ESTADO"
       Estado
    Case "CIDADE"
       Cidade
    Case "GRAVA"
       Grava
    Case Else
       Cabecalho
       BodyOpen "onLoad=document.focus();"
       ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
       ShowHTML "<HR>"
       ShowHTML "<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src=""images/icone/underc.gif"" align=""center""> <b>Esta opção está sendo desenvolvida.</b><br><br><br><br><br><br><br><br><br><br></center></div>"
       Rodape
  End Select
End Sub
%>

