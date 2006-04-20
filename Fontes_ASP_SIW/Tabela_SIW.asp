<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE FILE="Constants.inc" -->
<!-- #INCLUDE FILE="jScript.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Geral.asp" -->
<!-- #INCLUDE FILE="DB_Tabela_SIW.asp" -->
<!-- #INCLUDE FILE="DML_Tabela_SIW.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /Tabela_SIW.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Gerencia a atualização das tabelas de localização
REM Mail     : alex@sbpi.com.br
REM Criacao  : 19/03/2003, 16:35
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
Dim R, O, w_Cont, w_Pagina, w_Disabled, w_TP, w_troca, w_cor
Dim w_Assinatura
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
w_Pagina     = "Tabela_SIW.asp?par="
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
Main

FechaSessao

Set w_cor       = Nothing

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
REM Rotina da tabela de vínculos padrão do SIW para um segmento de mercado
REM -------------------------------------------------------------------------
Sub SegmentoVinc

  Dim w_sq_segmento_vinculo, w_sq_segmento, w_sq_tipo_pessoa,w_padrao,w_ativo
  Dim w_nome_tipo_pessoa, w_nome_segmento
  Dim w_interno, w_contratado, w_ordem
  Dim w_nome
  Dim p_nome,p_ativo
  
  p_nome                        = Trim(uCase(Request("p_nome")))
  p_ativo                       = Trim(Request("p_ativo"))
  w_sq_segmento                 = Request("w_sq_segmento")
  w_interno                     = Request("w_interno")
  w_contratado                  = Request("w_contratado")
  w_ordem                       = Request("w_ordem")
  w_sq_tipo_pessoa              = Request("w_sq_tipo_pessoa")
  w_sq_segmento_vinculo         = Request("w_sq_segmento_vinculo")

  If O = "" Then O="L" end if
  
  DB_GetSegName RS, w_sq_segmento
  w_nome_segmento = RS("nome")
  DesconectaBD
  
  If InStr("LP",O) Then 
     DB_GetSegVincData RS, par, w_sq_segmento, null, null
     RS.sort = "nm_tipo_pessoa, ordem"
  ElseIf (O = "A" or O = "E") Then
     DB_GetSegVincData RS, par, w_sq_segmento, null, w_sq_segmento_vinculo
     w_nome                 = RS("nome_pessoa")
     w_padrao               = RS("padrao")
     w_ativo                = RS("ativo")
     w_interno              = RS("interno")
     w_contratado           = RS("contratado")
     w_ordem                = RS("ordem")
     w_sq_tipo_pessoa       = RS("sq_tipo_pessoa")
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_sq_tipo_pessoa", "Pessoa", "SELECT", "1", "1", "10", "", "1"
        Validate "w_nome", "Nome", "1", "1", "1", "20", "1", "1"
        Validate "w_ordem", "Ordem", "1", "1", "1", "6", "", "0123456789"
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     ElseIf O = "E" Then
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
        ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
        ShowHTML "     { return (true); }; "
        ShowHTML "     { return (false); }; "
     ElseIf O="P" Then
        Validate "p_nome", "Nome", "1", "", "1", "10", "1", "1"
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ShowHTML "<TITLE>" & conSgSistema & " - Módulos por segmento</TITLE>"
  ShowHTML "</HEAD>"
  If O = "I" or O = "A" Then
     BodyOpen "onLoad='document.Form.w_sq_tipo_pessoa.focus()';"
  ElseIf O = "E" Then
     BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
  ElseIf InStr("P",O) > 0 Then
     BodyOpen "onLoad='document.Form.p_nome.focus()';"
  Else
     BodyOpen "onLoad=document.focus();"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<FONT COLOR=""#000000"">Segmento: <B>" & w_nome_segmento & "</B></FONT></B><BR><BR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&w_sq_segmento=" & w_sq_segmento & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" &  p_nome & "&p_ativo=" & p_ativo & """><u>I</u>ncluir</a>&nbsp;"
    'If p_nome  & p_ativo > "" Then
    '   ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&w_sq_segmento=" & w_sq_segmento & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" &  p_nome & "&p_ativo=" & p_ativo & """><u><font color=""#BC5100"">F</u>iltrar (sigla)</font></a></font>"
    'Else
    '   ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&w_sq_segmento=" & w_sq_segmento & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" &  p_nome & "&p_ativo=" & p_ativo & """><u>F</u>iltrar (Ativo)</a>"
    'End If
    ShowHTML "                         <a class=""SS"" href=""#"" onclick=""opener.focus(); window.close();"">Fechar</a>"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"    
    ShowHTML "          <td><font size=""1""><b>Pessoa</font></td>"
    ShowHTML "          <td><font size=""1""><b>Nome</font></td>"
    ShowHTML "          <td><font size=""1""><b>Ativo</font></td>"
    ShowHTML "          <td><font size=""1""><b>Padrão</font></td>"
    ShowHTML "          <td><font size=""1""><b>Interno</font></td>"
    ShowHTML "          <td><font size=""1""><b>Contratado</font></td>"
    ShowHTML "          <td><font size=""1""><b>Ordem</font></td>"
    ShowHTML "          <td><font size=""1""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=8 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & RS("nm_tipo_pessoa") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("nome_pessoa") & "</td>"
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
        If RS("interno") = "S" Then
           ShowHTML "        <td align=""center""><font size=""1"">Sim</td>"
        Else
           ShowHTML "        <td align=""center""><font size=""1"">Não</td>"
        End If
        If RS("contratado") = "S" Then
           ShowHTML "        <td align=""center""><font size=""1"">Sim</td>"
        Else
           ShowHTML "        <td align=""center""><font size=""1"">Não</td>"
        End If
        ShowHTML "        <td align=""center""><font size=""1"">" & nvl(RS("ordem"),"-") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_segmento=" & w_sq_segmento & "&w_sq_segmento_vinculo=" & RS("SQ_SEG_VINCULO") & "&p_nome=" &  p_nome & "&p_ativo=" & p_ativo & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_segmento=" & w_sq_segmento & "&w_sq_segmento_vinculo=" & RS("SQ_SEG_VINCULO") & "&p_nome=" &  p_nome & "&p_ativo=" & p_ativo & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
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
    If O = "E" Then w_Disabled = "DISABLED" End If    
    AbreForm "Form", w_Pagina&"Grava", "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""p_nome"" value=""" & p_nome &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_ativo"" value=""" & p_ativo &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_segmento"" value=""" & w_sq_segmento &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_segmento_vinculo"" value=""" & w_sq_segmento_vinculo &""">"
    
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""90%"" border=""0"">"    
    ShowHTML "      <tr>"
    SelecaoTipoPessoa "<u>P</u>essoa:", "P", null, w_sq_tipo_pessoa, null, "w_sq_tipo_pessoa", null, null
    ShowHTML "      </tr>"        
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_nome"" size=20 maxlength=20 value=""" & w_nome & """></td></tr>"
    ShowHTML "      <tr align=""left"">"
    MontaRadioNS "<b>Padrão?", w_padrao, "w_padrao"
    ShowHTML "      </tr>"
    ShowHTML "      <tr align=""left"">"
    MontaRadioSN "<b>Ativo?", w_ativo, "w_ativo"
    MontaRadioNS "<b>Interno?", w_interno, "w_interno"
    MontaRadioNS "<b>Contratado?", w_contratado, "w_contratado"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>O</U>rdem:<br><INPUT ACCESSKEY=""O"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_ordem"" size=6 maxlength=6 value=""" & w_ordem & """></td></tr>"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""STI"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    If O = "E" Then
       ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Gravar"">"
    End If
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_sq_segmento=" & w_sq_segmento & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" &  p_nome & "&p_ativo=" & p_ativo & "&O=L';"" name=""Botao"" value=""Cancelar"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  ElseIf Instr("P",O) > 0 Then
    AbreForm "Form", w_Pagina&par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_segmento"" value=""" & w_sq_segmento & """>"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""70%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_nome"" size=""10"" maxlength=""10"" value=""" & p_nome & """></td></tr>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_sq_segmento=" & w_sq_segmento & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&O=P&SG=" & SG & "';"" name=""Botao"" value=""Limpar campos"">"
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

  Set w_sq_segmento_vinculo     = Nothing
  Set w_sq_segmento             = Nothing
  Set w_nome_segmento           = Nothing
  Set w_nome_tipo_pessoa        = Nothing
  Set w_sq_tipo_pessoa          = Nothing
  Set w_padrao                  = Nothing
  Set w_ativo                   = Nothing 
  Set p_nome                    = Nothing
  Set p_ativo                   = Nothing
  Set w_nome                    = Nothing
End Sub
REM =========================================================================
REM Fim da tabela de vínculos padrão do SIW para um segmento de mercado
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina da tabela de menu padrão do SIW para um segmento de mercado
REM -------------------------------------------------------------------------
Sub SegmentoMenu

  Dim w_sq_segmento_vinculo, w_sq_segmento, w_sq_modulo,w_padrao,w_ativo
  Dim w_nome_modulo, w_nome_segmento
  Dim w_nome
  Dim p_nome,p_ativo
  
  p_nome                        = Trim(uCase(Request("p_nome")))
  p_ativo                       = Trim(Request("p_ativo"))
  w_sq_segmento                 = Request("w_sq_segmento")
  w_sq_modulo                   = Request("w_sq_modulo")

  If O = "" Then O="L" end if
  
  DB_GetSegName RS, w_sq_segmento
  w_nome_segmento = RS("nome")
  DesconectaBD
  
  If InStr("LP",O) Then 
     DB_GetSegVincData RS, par, w_sq_segmento, null, null
  ElseIf (O = "A" or O = "E") Then
     ' SQL = "select a.*, b.nome, b.objetivo_geral " & VbCrLf & _
     '      "  from dm_segmento_vinculo a, co_tipo_vinculo b " & VbCrLf & _
     '      " where a.sq_modulo   = b.sq_modulo " & VbCrLf & _
     '      "   and a.sq_modulo   = " & w_sq_modulo & VbCrLf & _
     '      "   and a.sq_segmento = " & w_sq_segmento
    ' ConectaBD SQL
    ' w_nome_modulo          = RS("nome")
    ' w_padrao               = RS("padrao")
    ' w_ativo                = RS("ativo")
     'w_objetivo_geral       = RS("objetivo_geral")
     'w_nome  = RS("nome")
    ' DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     modulo
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        If O = "I" Then
           Validate "w_sq_modulo", "Módulo", "SELECT", "1", "1", "10", "", "1"
        End If
        Validate "w_nome", "Objetivo específico", "1", "1", "1", "20", "1", "1"
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     ElseIf O = "E" Then
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
        ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
        ShowHTML "     { return (true); }; "
        ShowHTML "     { return (false); }; "
     ElseIf O="P" Then
        Validate "p_nome", "Nome", "1", "", "1", "10", "1", "1"
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ShowHTML "<TITLE>" & conSgSistema & " - Módulos por segmento</TITLE>"
  ShowHTML "</HEAD>"
  If O = "I" Then
     BodyOpen "onLoad='document.Form.w_sq_modulo.focus()';"
  ElseIf O = "A" Then
     BodyOpen "onLoad='document.Form.w_nome.focus()';"
  ElseIf O = "E" Then
     BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
  ElseIf InStr("P",O) > 0 Then
     BodyOpen "onLoad='document.Form.p_nome.focus()';"
  Else
     BodyOpen "onLoad=document.focus();"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<FONT COLOR=""#000000"">Segmento: <B>" & w_nome_segmento & "</B></FONT></B><BR><BR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&w_sq_segmento=" & w_sq_segmento & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" &  p_nome & "&p_ativo=" & p_ativo & """><u>I</u>ncluir</a>&nbsp;"
    'If p_nome  & p_ativo > "" Then
    '   ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&w_sq_segmento=" & w_sq_segmento & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" &  p_nome & "&p_ativo=" & p_ativo & """><u><font color=""#BC5100"">F</u>iltrar (sigla)</font></a></font>"
    'Else
    '   ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&w_sq_segmento=" & w_sq_segmento & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" &  p_nome & "&p_ativo=" & p_ativo & """><u>F</u>iltrar (Ativo)</a>"
    'End If
    ShowHTML "                         <a class=""SS"" href=""#"" onclick=""opener.focus(); window.close();"">Fechar</a>"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"  
    ShowHTML "          <td><font size=""2""><b>Nome</font></td>"  
    ShowHTML "          <td><font size=""2""><b>Módulo</font></td>"
    ShowHTML "          <td><font size=""2""><b>Objetivo específico</font></td>"
    ShowHTML "          <td title=""padrao""><font size=""2""><b>Com.</font></td>"
    ShowHTML "          <td><font size=""2""><b>Ativo</font></td>"
    ShowHTML "          <td><font size=""2""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=6 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & ExibeTexto(RS("nome")) & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("nm_modulo") & "</td>"
        ShowHTML "        <td><font size=""1"">" & ExibeTexto(RS("objetivo")) & "</td>"
        ShowHTML "        <td align=""center"" title=""padrao""><font size=""1"">" & RS("comercializar") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("ativo") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_segmento=" & w_sq_segmento & "&w_sq_modulo=" & RS("sq_modulo") & "&p_nome=" &  p_nome & "&p_ativo=" & p_ativo & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_segmento=" & w_sq_segmento & "&w_sq_modulo=" & RS("sq_modulo") & "&p_nome=" &  p_nome & "&p_ativo=" & p_ativo & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
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
    If O = "E" Then w_Disabled = "DISABLED" End If 
    AbreForm "Form", w_Pagina&"Grava", "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,R,O   
    ShowHTML "<INPUT type=""hidden"" name=""p_nome"" value=""" & p_nome &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_ativo"" value=""" & p_ativo &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_segmento"" value=""" & w_sq_segmento &""">"
    
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""90%"" border=""0"">"            
    If O = "I" Then
       'ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>M</U>ódulo:<br><SELECT ACCESSKEY=""M"" " & w_Disabled & " class=""STS"" name=""w_sq_modulo"" size=""1"">"
       'ShowHTML "          <OPTION VALUE="""">---"
       'SQL = "select sq_modulo, nome from co_tipo_vinculo " & VbCrLf & _
       '      "MINUS " & VbCrLf & _
       '      "select a.sq_modulo, a.nome " & VbCrLf & _
       '      "  from co_tipo_vinculo a, dm_segmento_vinculo b " & VbCrLf & _
       '      " where a.sq_modulo = b.sq_modulo " & VbCrLf & _
       '      "   and sq_segmento = " & w_sq_segmento & VbCrLf & _
       '      "order by nome " & VbCrLf
       'ConectaBD SQL
       While Not RS.EOF
         If w_sq_modulo = RS("sq_modulo") Then
            ShowHTML "          <OPTION VALUE=""" & RS("sq_modulo") & """ SELECTED>" & RS("Nome")
         Else
            ShowHTML "          <OPTION VALUE=""" & RS("sq_modulo") & """>" & RS("Nome")
         End If
         RS.MoveNext
       Wend
       DesconectaBD
       ShowHTML "          </SELECT></td>"
       ShowHTML "      </tr>"
    Else
       ShowHTML "<INPUT type=""hidden"" name=""w_sq_modulo"" value=""" & w_sq_modulo &""">"
       ShowHTML "      <tr><td valign=""top""><font size=""2"">Módulo: <b>" & w_nome_modulo & "</td></tr>"
       ShowHTML "      <tr><td valign=""top""><font size=""2"">Objetivo geral:<br><b>" & ExibeTexto(w_objetivo_geral) & "</td></tr>"
       ShowHTML "      <tr><td valign=""top""><hr></td></tr>"
    End If
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>O</U>bjetivos específicos: (um em cada linha, sem marcadores ou numeradores)<br><TEXTAREA ACCESSKEY=""O"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_nome"" rows=5 cols=75>" & w_nome & "</TEXTAREA></td></tr>"
    ShowHTML "      <tr>"
    MontaRadioNS "<b>Padrão?", w_padrao, "w_padrao"
    ShowHTML "      </tr>"
    ShowHTML "      <tr>"
    MontaRadioSN "<b>Ativo?", w_ativo, "w_ativo"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""STI"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    If O = "E" Then
       ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Gravar"">"
    End If
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_sq_segmento=" & w_sq_segmento & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" &  p_nome & "&p_ativo=" & p_ativo & "&O=L';"" name=""Botao"" value=""Cancelar"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  ElseIf Instr("P",O) > 0 Then
    AbreForm "Form", w_Pagina&par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_segmento"" value=""" & w_sq_segmento & """>"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""70%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_nome"" size=""10"" maxlength=""10"" value=""" & p_nome & """></td></tr>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_sq_segmento=" & RS("sq_segmento") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&O=P&SG=" & SG & "';"" name=""Botao"" value=""Limpar campos"">"
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

  Set w_sq_segmento_vinculo     = Nothing
  Set w_sq_segmento             = Nothing
  Set w_nome_segmento           = Nothing
  Set w_nome_modulo             = Nothing
  Set w_sq_modulo               = Nothing
  Set w_padrao                  = Nothing
  Set w_ativo                   = Nothing 
  Set p_nome                    = Nothing
  Set p_ativo                   = Nothing
  Set w_nome                    = Nothing
End Sub
REM =========================================================================
REM Fim da tabela de menu padrão do SIW para um segmento de mercado
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina da tabela de módulos do SIW por segmento de mercado
REM -------------------------------------------------------------------------
Sub SegmentoModulo

  Dim w_sq_segmento, w_sq_modulo,w_comercializar,w_ativo
  Dim w_nome_modulo, w_nome_segmento, w_objetivo_geral
  Dim w_objetivo_especifico
  Dim p_objetivo_especifico,p_ativo
  
  p_objetivo_especifico         = Trim(uCase(Request("p_objetivo_especifico")))
  p_ativo                       = Trim(Request("p_ativo"))
  w_sq_segmento                 = Request("w_sq_segmento")
  w_sq_modulo                   = Request("w_sq_modulo")

  If O = "" Then O="L" end if
  
  DB_GetSegName RS, w_sq_segmento
  w_nome_segmento = RS("nome")
  DesconectaBD
  
  If InStr("LP",O) Then 
     DB_GetSegVincData RS, par, w_sq_segmento, null, null
     RS.sort = "nm_modulo"
  ElseIf (O = "A" or O = "E") Then
     DB_GetSegModData RS, w_sq_segmento, w_sq_modulo
     w_nome_modulo          = RS("nome")
     w_comercializar        = RS("comercializar")
     w_ativo                = RS("ativo")
     w_objetivo_geral       = RS("objetivo_geral")
     w_objetivo_especifico  = RS("objetivo_especif")
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     modulo
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        If O = "I" Then
           Validate "w_sq_modulo", "Módulo", "SELECT", "1", "1", "10", "", "1"
        End If
        Validate "w_objetivo_especifico", "Objetivo específico", "1", "1", "1", "4000", "1", "1"
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     ElseIf O = "E" Then
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
        ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
        ShowHTML "     { return (true); }; "
        ShowHTML "     { return (false); }; "
     ElseIf O="P" Then
        Validate "p_objetivo_especifico", "Nome", "1", "", "1", "10", "1", "1"
     End If
     ShowHTML "  theForm.Botao[0].disabled=true;"
     ShowHTML "  theForm.Botao[1].disabled=true;"
     ValidateClose
     ScriptClose
  End If
  ShowHTML "<TITLE>" & conSgSistema & " - Módulos por segmento</TITLE>"
  ShowHTML "</HEAD>"
  If O = "I" Then
     BodyOpen "onLoad='document.Form.w_sq_modulo.focus()';"
  ElseIf O = "A" Then
     BodyOpen "onLoad='document.Form.w_objetivo_especifico.focus()';"
  ElseIf O = "E" Then
     BodyOpen "onLoad='document.Form.w_assinatura.focus()';"
  ElseIf InStr("P",O) > 0 Then
     BodyOpen "onLoad='document.Form.p_objetivo_especifico.focus()';"
  Else
     BodyOpen "onLoad=document.focus();"
  End If
  ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
  ShowHTML "<HR>"
  ShowHTML "<FONT COLOR=""#000000"">Segmento: <B>" & w_nome_segmento & "</B></FONT></B><BR><BR>"
  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" Then
    ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&w_sq_segmento=" & w_sq_segmento & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_objetivo_especifico=" &  p_objetivo_especifico & "&p_ativo=" & p_ativo & """><u>I</u>ncluir</a>&nbsp;"
    'If p_objetivo_especifico  & p_ativo > "" Then
    '   ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&w_sq_segmento=" & w_sq_segmento & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_objetivo_especifico=" &  p_objetivo_especifico & "&p_ativo=" & p_ativo & """><u><font color=""#BC5100"">F</u>iltrar (sigla)</font></a></font>"
    'Else
    '   ShowHTML "                         <a accesskey=""F"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&w_sq_segmento=" & w_sq_segmento & "&O=P&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_objetivo_especifico=" &  p_objetivo_especifico & "&p_ativo=" & p_ativo & """><u>F</u>iltrar (Ativo)</a>"
    'End If
    ShowHTML "                         <a class=""SS"" href=""#"" onclick=""opener.focus(); window.close();"">Fechar</a>"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"    
    ShowHTML "          <td><font size=""2""><b>Módulo</font></td>"
    ShowHTML "          <td><font size=""2""><b>Objetivo específico</font></td>"
    ShowHTML "          <td title=""Comercializar""><font size=""2""><b>Com.</font></td>"
    ShowHTML "          <td><font size=""2""><b>Ativo</font></td>"
    ShowHTML "          <td><font size=""2""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=5 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td><font size=""1"">" & RS("nm_modulo") & "</td>"
        ShowHTML "        <td><font size=""1"">" & ExibeTexto(RS("objetivo_especif")) & "</td>"
        If RS("comercializar") = "S" Then
           ShowHTML "        <td align=""center"" title=""Comercializar""><font size=""1"">Sim</td>"
        Else
           ShowHTML "        <td align=""center"" title=""Comercializar""><font size=""1"">Não</td>"
        End If
        If RS("ativo") = "S" Then
           ShowHTML "        <td align=""center""><font size=""1"">Sim</td>"
        Else
           ShowHTML "        <td align=""center""><font size=""1"">Não</td>"
        End If
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_segmento=" & w_sq_segmento & "&w_sq_modulo=" & RS("sq_modulo") & "&p_objetivo_especifico=" &  p_objetivo_especifico & "&p_ativo=" & p_ativo & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_segmento=" & w_sq_segmento & "&w_sq_modulo=" & RS("sq_modulo") & "&p_objetivo_especifico=" &  p_objetivo_especifico & "&p_ativo=" & p_ativo & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
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
    If O = "E" Then w_Disabled = "DISABLED" End If    
    AbreForm "Form", w_Pagina&"Grava", "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""p_objetivo_especifico"" value=""" & p_objetivo_especifico &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_ativo"" value=""" & p_ativo &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_segmento"" value=""" & w_sq_segmento &""">"
    
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""90%"" border=""0"">"            
    If O = "I" Then
       ShowHTML "<tr>"
       SelecaoSegModulo "<u>M</u>ódulo:", "M", null, w_sq_modulo, w_sq_segmento, "w_sq_modulo", null
       ShowHTML "</tr>"
    Else
       ShowHTML "<INPUT type=""hidden"" name=""w_sq_modulo"" value=""" & w_sq_modulo &""">"
       ShowHTML "      <tr><td valign=""top""><font size=""2"">Módulo: <b>" & w_nome_modulo & "</td></tr>"
       ShowHTML "      <tr><td valign=""top""><font size=""2"">Objetivo geral:<br><b>" & ExibeTexto(w_objetivo_geral) & "</td></tr>"
       ShowHTML "      <tr><td valign=""top""><hr></td></tr>"
    End If
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>O</U>bjetivos específicos: (um em cada linha, sem marcadores ou numeradores)<br><TEXTAREA ACCESSKEY=""O"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_objetivo_especifico"" rows=5 cols=75>" & w_objetivo_especifico & "</TEXTAREA></td></tr>"
    ShowHTML "      <tr align=""left"">"
    MontaRadioSN "Comercializar?", w_comercializar, "w_comercializar"
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
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_sq_segmento=" & w_sq_segmento & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_objetivo_especifico=" &  p_objetivo_especifico & "&p_ativo=" & p_ativo & "&O=L';"" name=""Botao"" value=""Cancelar"">"
    ShowHTML "          </td>"
    ShowHTML "      </tr>"
    ShowHTML "    </table>"
    ShowHTML "    </TD>"
    ShowHTML "</tr>"
    ShowHTML "</FORM>"
  ElseIf Instr("P",O) > 0 Then
    AbreForm "Form", w_Pagina&par, "POST", "return(Validacao(this));", null,P1,P2,P3,null,TP,SG,R,"L"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_segmento"" value=""" & w_sq_segmento & """>"

    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Aplicar filtro</i>. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""70%"" border=""0"">"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_objetivo_especifico"" size=""10"" maxlength=""10"" value=""" & p_objetivo_especifico & """></td></tr>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&w_sq_segmento=" & RS("sq_segmento") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&O=P&SG=" & SG & "';"" name=""Botao"" value=""Limpar campos"">"
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

  Set w_sq_segmento             = Nothing
  Set w_nome_segmento           = Nothing
  Set w_nome_modulo             = Nothing
  Set w_sq_modulo               = Nothing
  Set w_comercializar           = Nothing
  Set w_ativo                   = Nothing 
  Set p_objetivo_especifico     = Nothing
  Set p_ativo                   = Nothing
  Set w_objetivo_geral          = Nothing
  Set w_objetivo_especifico     = Nothing
End Sub
REM =========================================================================
REM Fim da tabela de módulos do SIW por segmento de mercado
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina da tabela de módulos do SIW
REM -------------------------------------------------------------------------
Sub Modulos

  Dim w_sq_modulo,w_nome,w_sigla
  Dim w_objetivo_geral
  Dim p_nome,p_sigla
  
  p_nome                        = Trim(uCase(Request("p_nome")))
  p_sigla                       = Trim(Request("p_sigla"))
  w_sq_modulo                   = Request("w_sq_modulo")

  If O = "" Then O="L" end if
  
  If InStr("LP",O) Then 
     DB_GetModList RS
     RS.sort = "nome"
  ElseIf (O = "A" or O = "E") Then               
     DB_GetModData RS, w_sq_modulo
     w_nome             = RS("nome")
     w_sigla            = RS("sigla")
     w_objetivo_geral   = RS("objetivo_geral")
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     modulo
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_nome", "Nome", "1", "1", "1", "60", "1", "1"
        Validate "w_sigla", "Sigla", "1", "1", "1", "3", "1", "1"
        Validate "w_objetivo_geral", "Objetivo geral", "1", "1", "1", "4000", "1", "1"
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     ElseIf O = "E" Then
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
        ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
        ShowHTML "     { return (true); }; "
        ShowHTML "     { return (false); }; "
     ElseIf O="P" Then
        Validate "p_nome", "Nome", "1", "", "1", "10", "1", "1"
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
    ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" &  p_nome & "&p_sigla=" & p_sigla & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"    
    ShowHTML "          <td><font size=""2""><b>Chave</font></td>"
    ShowHTML "          <td><font size=""2""><b>Nome</font></td>"
    ShowHTML "          <td><font size=""2""><b>Sigla</font></td>"
    ShowHTML "          <td><font size=""2""><b>Objetivo geral</font></td>"
    ShowHTML "          <td><font size=""2""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=5 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("sq_modulo") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("nome") & "</td>"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("sigla") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("objetivo_geral") & "</td>"
        ShowHTML "        <td align=""top"" nowrap><font size=""1"">"
        ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_modulo=" & RS("sq_modulo") & "&p_nome=" &  p_nome & "&p_sigla=" & p_sigla & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_modulo=" & RS("sq_modulo") & "&p_nome=" &  p_nome & "&p_sigla=" & p_sigla & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
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
    If O = "E" Then w_Disabled = "DISABLED" End If    
    AbreForm "Form", w_Pagina&"Grava", "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""p_nome"" value=""" & p_nome &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_sigla"" value=""" & p_sigla &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_modulo"" value=""" & w_sq_modulo &""">"
    
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""90%"" border=""0"">"            
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_nome"" size=""60"" maxlength=""60"" value=""" & w_nome & """></td></tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>S</U>igla:<br><INPUT ACCESSKEY=""S"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_sigla"" size=""3"" maxlength=""3"" value=""" & w_sigla & """></td></tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>O</U>bjetivo geral:<br><TEXTAREA ACCESSKEY=""O"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_objetivo_geral"" rows=5 cols=75>" & w_objetivo_geral & "</TEXTAREA></td></tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>A</U>ssinatura Eletrônica:<br><INPUT ACCESSKEY=""A"" class=""STI"" type=""PASSWORD"" name=""w_assinatura"" size=""30"" maxlength=""30"" value=""""></td>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    If O = "E" Then
       ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Excluir"">"
    Else
       ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Gravar"">"
    End If
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" &  p_nome & "&p_sigla=" & p_sigla & "&O=L';"" name=""Botao"" value=""Cancelar"">"
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
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_nome"" size=""10"" maxlength=""10"" value=""" & p_nome & """></td></tr>"
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&O=P&SG=" & SG & "';"" name=""Botao"" value=""Limpar campos"">"
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

  Set w_sq_modulo               = Nothing
  Set w_nome                    = Nothing
  Set w_sigla                   = Nothing 
  Set p_nome                    = Nothing
  Set p_sigla                   = Nothing
  Set w_objetivo_geral          = Nothing
End Sub
REM =========================================================================
REM Fim da tabela de módulos do SIW
REM -------------------------------------------------------------------------

REM =========================================================================
REM Rotina da tabela de segmentos de mercado
REM -------------------------------------------------------------------------
Sub Segmento

  Dim w_sq_segmento,w_nome,w_ativo
  Dim w_padrao
  Dim p_nome,p_ativo
  
  p_nome                        = Trim(uCase(Request("p_nome")))
  p_ativo                       = Trim(Request("p_ativo"))
  w_sq_segmento                 = Request("w_sq_segmento")
      
  If O = "" Then O="L" end if
  
  If InStr("LP",O) Then 
     DB_GetSegList RS, null
     RS.sort = "padrao desc, nome"
  ElseIf (O = "A" or O = "E") Then               
      DB_GetSegData RS, w_sq_segmento
     w_nome             = RS("nome")
     w_ativo            = RS("ativo")
     w_padrao           = RS("padrao")
     DesconectaBD
  End If
  
  Cabecalho
  ShowHTML "<HEAD>"
  If InStr("IAEP",O) > 0 Then
     ScriptOpen "JavaScript"
     modulo
     ValidateOpen "Validacao"
     If InStr("IA",O) > 0 Then
        Validate "w_nome", "Nome", "1", "1", "1", "40", "1", "1"
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
     ElseIf O = "E" Then
        Validate "w_assinatura", "Assinatura Eletrônica", "1", "1", "6", "30", "1", "1"
        ShowHTML "  if (confirm('Confirma a exclusão deste registro?')) "
        ShowHTML "     { return (true); }; "
        ShowHTML "     { return (false); }; "
     ElseIf O="P" Then
        Validate "p_nome", "Nome", "1", "", "1", "10", "1", "1"
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
    ShowHTML "<tr><td><font size=""2""><a accesskey=""I"" class=""SS"" href=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=I&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" &  p_nome & "&p_ativo=" & p_ativo & """><u>I</u>ncluir</a>&nbsp;"
    ShowHTML "    <td align=""right""><font size=""1""><b>Registros: " & RS.RecordCount
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"    
    ShowHTML "          <td><font size=""2""><b>Chave</font></td>"
    ShowHTML "          <td><font size=""2""><b>Nome</font></td>"
    ShowHTML "          <td><font size=""2""><b>Ativo</font></td>"
    ShowHTML "          <td><font size=""2""><b>Padrão</font></td>"
    ShowHTML "          <td><font size=""2""><b>Operações</font></td>"
    ShowHTML "        </tr>"
    If RS.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=5 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      While Not RS.EOF
        If w_cor = conTrBgColor or w_cor = "" Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        ShowHTML "      <tr bgcolor=""" & w_cor & """ valign=""top"">"
        ShowHTML "        <td align=""center""><font size=""1"">" & RS("sq_segmento") & "</td>"
        ShowHTML "        <td><font size=""1"">" & RS("nome") & "</td>"
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
        ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=A&w_sq_segmento=" & RS("sq_segmento") & "&p_nome=" &  p_nome & "&p_ativo=" & p_ativo & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Alterar</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""" & w_Pagina & par & "&R=" & w_Pagina & par & "&O=E&w_sq_segmento=" & RS("sq_segmento") & "&p_nome=" &  p_nome & "&p_ativo=" & p_ativo & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & """>Excluir</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""#" & RS("sq_segmento") & """ onClick=""window.open('" & w_Pagina & "SegmentoMod&R=" & w_Pagina & par & "&O=L&w_sq_segmento=" & RS("sq_segmento") & "&p_nome=" &  p_nome & "&p_ativo=" & p_ativo & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - Módulos&SG=SEGMOD','endereco','top=10, left=50, width=700, height=500, toolbar=no, status=no, scrollbars=yes, resizable=yes');"">Módulos</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""#" & RS("sq_segmento") & """ onClick=""window.open('" & w_Pagina & "SegmentoMenu&R=" & w_Pagina & par & "&O=L&w_sq_segmento=" & RS("sq_segmento") & "&p_nome=" &  p_nome & "&p_ativo=" & p_ativo & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - Menu&SG=SEGMENU','endereco','top=10, left=50, width=700, height=500, toolbar=no, status=no, scrollbars=yes, resizable=yes');"">Menu</A>&nbsp"
        ShowHTML "          <A class=""HL"" HREF=""#" & RS("sq_segmento") & """ onClick=""window.open('" & w_Pagina & "SegmentoVinc&R=" & w_Pagina & par & "&O=L&w_sq_segmento=" & RS("sq_segmento") & "&p_nome=" &  p_nome & "&p_ativo=" & p_ativo & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & " - Vínculos&SG=SEGVINC','endereco','top=10, left=50, width=700, height=500, toolbar=no, status=no, scrollbars=yes, resizable=yes');"">Vínculos</A>&nbsp"
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
    If O = "E" Then w_Disabled = "DISABLED" End If    
    AbreForm "Form", w_Pagina&"Grava", "POST", "return(Validacao(this));", null, P1,P2,P3,P4,TP,SG,R,O
    ShowHTML "<INPUT type=""hidden"" name=""p_nome"" value=""" & p_nome &""">"
    ShowHTML "<INPUT type=""hidden"" name=""p_ativo"" value=""" & p_ativo &""">"
    ShowHTML "<INPUT type=""hidden"" name=""w_sq_segmento"" value=""" & w_sq_segmento &""">"
    
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""90%"" border=""0"">"            
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""w_nome"" size=""40"" maxlength=""40"" value=""" & w_nome & """></td></tr>"
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
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "&p_nome=" &  p_nome & "&p_ativo=" & p_ativo & "&O=L';"" name=""Botao"" value=""Cancelar"">"
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
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b><U>N</U>ome:<br><INPUT ACCESSKEY=""N"" " & w_Disabled & " class=""STI"" type=""text"" name=""p_nome"" size=""10"" maxlength=""10"" value=""" & p_nome & """></td></tr>"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Ativo:</b><br>"
    If p_Ativo  =  "" Then
       ShowHTML "              <input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_ativo"" value=""S""> Sim <input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_ativo"" value=""N""> Não <input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_ativo"" value="""" checked> Todos"
    ElseIf p_Ativo = "S" Then
       ShowHTML "              <input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_ativo"" value=""S"" checked> Sim <input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_ativo"" value=""N""> Não <input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_ativo"" value=""""> Todos"
    Else
       ShowHTML "              <input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_ativo"" value=""S""> Sim <input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_ativo"" value=""N"" checked> Não <input " & w_Disabled & " class=""STR"" type=""radio"" name=""p_ativo"" value=""""> Todos"
    End If
    ShowHTML "      <tr><td align=""center"" colspan=""3"" height=""1"" bgcolor=""#000000"">"
    ShowHTML "      <tr><td align=""center"" colspan=""3"">"
    ShowHTML "            <input class=""STB"" type=""submit"" name=""Botao"" value=""Aplicar filtro"">"
    ShowHTML "            <input class=""STB"" type=""button"" onClick=""location.href='" & w_Pagina & par & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&O=P&SG=" & SG & "';"" name=""Botao"" value=""Limpar campos"">"
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

  Set w_sq_segmento             = Nothing
  Set w_nome                    = Nothing
  Set w_ativo                   = Nothing 
  Set p_nome                    = Nothing
  Set p_ativo                   = Nothing
  Set w_padrao                  = Nothing
End Sub
REM =========================================================================
REM Fim da tabela de segmentos de mercado
REM -------------------------------------------------------------------------

REM =========================================================================
REM Procedimento que executa as operações de BD
REM -------------------------------------------------------------------------
Public Sub Grava

  Dim p_codigo
  Dim p_sq_banco
  Dim p_padrao
  Dim p_nome
  Dim p_ativo
  Dim p_ordena
  Dim w_Null

  Cabecalho
  ShowHTML "</HEAD>"
  BodyOpen "onLoad=document.focus();"
  
  AbreSessao	
  Select Case SG
   Case "SEGVINC"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
           DML_DMSegVinc O, _
                   Request("w_sq_segmento_vinculo"), Request("w_sq_segmento"), Request("w_sq_tipo_pessoa"), _
                   Request("w_nome"), Request("w_padrao"), Request("w_ativo"),Request("w_interno"), Request("w_contratado"), Request("w_ordem") 
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=L&w_sq_segmento=" & Request("w_sq_segmento") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
   Case "SEGMOD"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          DML_SIWModSeg O, _
                   Request("w_objetivo_especifico"), Request("w_sq_modulo"), Request("w_sq_segmento"), Request("w_comercializar"), _
                   Request("w_ativo")
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=L&w_sq_segmento=" & Request("w_sq_segmento") & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
   Case "COTPMODULO"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          DML_SIWModulo O, _
                   Request("w_sq_modulo"), Request("w_nome"), Request("w_sigla"), Request("w_objetivo_geral")
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
   Case "COTPSEG"
       ' Verifica se a Assinatura Eletrônica é válida
       If (VerificaAssinaturaEletronica(Session("Username"),w_assinatura) and w_assinatura > "") or _
          w_assinatura = "" Then
          DML_COSegmento O, _
                   Request("w_sq_segmento"), Request("w_nome"), Request("w_padrao"), Request("w_ativo")
          ScriptOpen "JavaScript"
          ShowHTML "  location.href='" & R & "&O=L&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & TP & "&SG=" & SG & "';"
          ScriptClose
       Else
          ScriptOpen "JavaScript"
          ShowHTML "  alert('Assinatura Eletrônica inválida!');"
          ShowHTML "  history.back(1);"
          ScriptClose
       End If
  End Select

  Set p_sq_banco        = Nothing
  Set p_codigo          = Nothing
  Set p_padrao          = Nothing
  Set p_nome            = Nothing
  Set p_ativo           = Nothing
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
    Case "SEGMENTOVINC"
       SegmentoVinc
    Case "SEGMENTOMENU"
       SegmentoMenu
    Case "SEGMENTOMOD"
       SegmentoModulo
    Case "MODULOS"
       Modulos
    Case "SEGMENTO"
       Segmento
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
REM =========================================================================
REM Fim da rotina principal
REM -------------------------------------------------------------------------
%>

