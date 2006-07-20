<%@ Language=VBScript %>
<%Option Explicit%>
<!-- #INCLUDE VIRTUAL="/siw/Constants.inc" -->
<!-- #INCLUDE VIRTUAL="/siw/jScript.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/Funcoes.asp" -->
<!-- #INCLUDE VIRTUAL="/siw/DB_Geral.asp" -->
<!-- #INCLUDE FILE="Funcoes.asp" -->
<!-- #INCLUDE FILE="DB_Geral.asp" -->
<!-- #INCLUDE FILE="DB_Relatorio.asp" -->
<%
Response.Expires = -1500
REM =========================================================================
REM  /Rel_Comunicacao.asp
REM ------------------------------------------------------------------------
REM Nome     : Alexandre Vinhadelli Papadópolis
REM Descricao: Relatório de transferência de arquivos
REM Mail     : alex@sbpi.com.br
REM Criacao  : 12/09/2003, 09:00
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
Dim dbms, sp, RS, RS1, RS2, RS3, RS_temp, w_ano
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
w_Pagina     = "Rel_Comunicacao.asp?par="
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
REM Rotina de consulta de comunicação
REM -------------------------------------------------------------------------
Sub Inicial

  Dim p_unidade, p_tipo
  Dim p_status, p_arq_regional
  Dim p_processamento_ini, p_processamento_fim, p_recebimento_ini, p_recebimento_fim
  Dim p_Ordena, w_regional, w_atual
  Dim w_tot1, w_tot2

  p_status              = uCase(Request("p_status")) 
  p_arq_regional        = uCase(Request("p_arq_regional")) 
  p_processamento_ini   = uCase(Request("p_processamento_ini")) 
  p_processamento_fim   = uCase(Request("p_processamento_fim")) 
  p_recebimento_ini     = uCase(Request("p_recebimento_ini")) 
  p_recebimento_fim     = uCase(Request("p_recebimento_fim")) 
  p_unidade             = uCase(Request("p_unidade"))
  p_tipo                = uCase(Request("p_tipo"))
  p_ordena              = uCase(Request("p_ordena"))
  
  If O = "L" or O = "W" Then
     If Session("regional") = "" or Session("regional") = "00" Then
        DB_GetComunicRel RS1, null, p_unidade, p_processamento_ini, p_processamento_fim, p_recebimento_ini, p_recebimento_fim
     Else
        DB_GetComunicRel RS1, Session("regional"), p_unidade, p_processamento_ini, p_processamento_fim, p_recebimento_ini, p_recebimento_fim
     End If
     If p_status & p_arq_regional > "" Then
        w_filter = ""
        If p_status            = "PROCESSADOS" Then
           w_filter = w_filter & " and dt_process > 01/01/0001 "
        ElseIf p_status = "PROCESSAR" Then
           w_filter = w_filter & " and dt_process = null "
       End If
        If p_arq_regional = "S" Then
           w_filter = w_filter & " and nu_regional > 00 "
        End If
        RS1.Filter = Mid(w_filter,6,255)
     End If
     If p_ordena > "" Then
        RS1.Sort = "ds_gre," & p_ordena
     Else
        RS1.Sort = "ds_gre, dt_recebimento desc"
     End If
  End If
  
  If O = "W" Then
     HeaderWord null
     w_pag   = 1
     w_linha = 6
     CabecalhoWord w_cliente, "Transferência de Arquivos", w_pag
     DB_GetUorgList RS_temp, w_cliente, Session("regional"), "CODIGO", null, null, null
     ShowHTML "<TABLE WIDTH=""100%"" BORDER=0>"
     ShowHTML "  <TR><TD><FONT SIZE=2 COLOR=""#000000"">Regional de Ensino: <b>" & RS_temp("nome") & "</b></TD></TR>"
     ShowHTML "</TABLE>"
  Else
     Cabecalho
     ShowHTML "<HEAD>"
     If InStr("P",O) > 0 Then
        ScriptOpen "JavaScript"
        CheckBranco
        FormataData
        ValidateOpen "Validacao"
        If O="P" Then
           Validate "regional", "Regional", "SELECT", "", "1", "10", "1", "1"
           Validate "p_processamento_ini", "Processamento - data inicial", "DATA", "", "10", "10", "", "0123456789/"
           Validate "p_processamento_fim", "Processamento - data final", "DATA", "", "10", "10", "", "0123456789/"
           ShowHTML "  if ((theForm.p_processamento_ini.value == '' && theForm.p_processamento_fim.value != '') || (theForm.p_processamento_ini.value != '' && theForm.p_processamento_fim.value == '')) { "
           ShowHTML "     alert('Informe as datas de processamento inicial e final ou nenhuma delas!');"
           ShowHTML "     theForm.p_processamento_ini.focus();"
           ShowHTML "     return false;"
           ShowHTML "  }"
           CompData "p_processamento_ini", "Processamento - data inicial", "<=", "p_processamento_fim", "Processamento - data final"           
           Validate "p_recebimento_ini", "Recebimento - data inicial", "DATA", "", "10", "10", "", "0123456789/"
           Validate "p_recebimento_fim", "Recebimento - data final", "DATA", "", "10", "10", "", "0123456789/"
           ShowHTML "  if ((theForm.p_recebimento_ini.value == '' && theForm.p_recebimento_fim.value != '') || (theForm.p_recebimento_ini.value != '' && theForm.p_recebimento_fim.value == '')) { "
           ShowHTML "     alert('Informe as datas de recebimento inicial e final ou nenhuma delas!');"
           ShowHTML "     theForm.p_recebimento_ini.focus();"
           ShowHTML "     return false;"
           ShowHTML "  }"
           CompData "p_recebimento_ini", "Recebimento - data inicial", "<=", "p_recebimento_fim", "Recebimento - data final"
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
        CabecalhoRelatorio w_cliente, "Transferência de Arquivos"
        DB_GetUorgList RS_temp, w_cliente, Session("regional"), "CODIGO", null, null, null
        ShowHTML "<TABLE WIDTH=""100%"" BORDER=0>"
        ShowHTML "  <TR><TD><FONT SIZE=2 COLOR=""#000000"">Regional de Ensino: <b>" & RS_temp("nome") & "</b></TD></TR>"
        ShowHTML "</TABLE>"
        ShowHTML "<BR>"
     Else
        ShowHTML "<B><FONT COLOR=""#000000"">" & w_TP & "</FONT></B>"
        ShowHTML "<HR>"
     End If
  End If

  ShowHTML "<div align=center><center>"
  ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
  If O = "L" or O = "W" Then
    ShowHTML "<tr><td align=""center"" colspan=5>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    If RS1.EOF Then
        ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=5 align=""center""><font size=""2""><b>Não foram encontrados registros.</b></td></tr>"
    Else
      RS1.PageSize     = P4
      RS1.AbsolutePage = P3
      w_atual    = ""
      w_regional = "a"
      w_tot1     = 0
      w_tot2     = 0
      While Not RS1.EOF and (RS1.AbsolutePage = P3 or O = "W" or p_tipo = "S")
        If w_regional <> RS1("regional") or w_atual <> RS1("co_escola") Then
           If w_atual > "" Then
              If p_tipo = "S" or O = "W" Then ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=5><font size=""1""><b>Total de arquivos da unidade: " & FormatNumber(w_tot1,0) & "</b></td></tr>" End If
              w_tot2 = w_tot2 + w_tot1
              w_tot1 = 0
              If w_regional <> RS1("regional") Then
                 If p_tipo = "S" or O = "W" Then 
                    ShowHTML "      <tr bgcolor=""" & conTrTotalBgcolor & """><td colspan=5><font size=""2""><b>TOTAL DE ARQUIVOS DA REGIONAL: " & FormatNumber(w_tot2,0) & "</b></td></tr>"
                    ShowHTML "      <tr><td colspan=5><font size=""2""><b>&nbsp;</b></td></tr>"
                 End If
                 w_tot2 = 0
              End If
           End If
           If w_regional <> RS1("regional") Then
              w_linha = w_linha + 3
              ShowHTML "      <tr bgcolor=""" & conTrTotalBgcolor & """><td colspan=5><font size=""2""><b>REGIONAL DE ENSINO: " & ucase(RS1("ds_gre")) & "</b></td></tr>"
           End If
           ShowHTML "      <tr bgcolor=""" & conTrAlternateBgColor & """><td colspan=5><font size=""2""><b>Unidade: " & RS1("ds_escola") & "</b></td></tr>"
           w_linha = w_linha + 1
           w_regional = RS1("regional")
           w_atual    = RS1("co_escola")
           If p_tipo = "N" Then
              ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
              ShowHTML "          <td><font size=""1""><b>Nome do arquivo</font></td>"
              ShowHTML "          <td><font size=""1""><b>Dt.Receb.</font></td>"
              ShowHTML "          <td><font size=""1""><b>Dt.Proces.</font></td>"
              ShowHTML "          <td><font size=""1""><b>Regional</font></td>"
              ShowHTML "          <td><font size=""1""><b>Usuário</font></td>"
              ShowHTML "        </tr>"
           End If
        End If
        If w_linha > 30 and O = "W" Then
           ShowHTML "    </table>"
           ShowHTML "  </td>"
           ShowHTML "</tr>"
           ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td colspan=5><font size=""1""><b>Dt.Receb.:</b> Data de Recebimento - <b>Dt.Proces.:</b> Data de Processamento</font></td>"
           ShowHTML "</table>"
           ShowHTML "</center></div>"
           ShowHTML "    <br style=""page-break-after:always"">"
           w_linha = 6
           w_pag   = w_pag + 1
           CabecalhoWord w_cliente, "Transferência de Arquivos", w_pag
           DB_GetUorgList RS_temp, w_cliente, null, null, null, null, null
           RS_temp.Filter = "codigo = '" & Session("regional") & "'"
           ShowHTML "<div align=center><center>"
           ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
           ShowHTML "  <tr><td colspan=5><FONT SIZE=2 COLOR=""#000000"">Regional de Ensino: <b>" & RS_temp("nome") & "</b></TD></TR>"
           ShowHTML "  <tr bgcolor=""" & conTrAlternateBgColor & """><td colspan=5><font size=""2""><b>Unidade: " & RS1("ds_escola") & "</b></td></tr>"
           If p_tipo = "N" Then
              ShowHTML "   <tr bgcolor=""" & conTrBgColor & """ align=""center""><td colspan=5>"
              ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
              ShowHTML "        <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
              ShowHTML "          <td><font size=""1""><b>Nome do arquivo</font></td>"
              ShowHTML "          <td><font size=""1""><b>Dt.Receb.</font></td>"
              ShowHTML "          <td><font size=""1""><b>Dt.Proces.</font></td>"
              ShowHTML "          <td><font size=""1""><b>Regional</font></td>"
              ShowHTML "          <td><font size=""1""><b>Usuário</font></td>"
              ShowHTML "        </tr>"
              ShowHTML "        </tr>"
           End If
        End If
        w_cor = conTrBgColor
        If p_tipo = "N" Then
           ShowHTML "      <tr bgcolor=""" & w_cor & """>"
           ShowHTML "        <td><font size=""1"">" & Nvl(RS1("ds_arquivo"),"---") & "</td>"
           ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(FormataDataEdicao(RS1("dt_recebimento")),"---") & "</td>"
           ShowHTML "        <td align=""center""><font size=""1"">" & Nvl(FormataDataEdicao(RS1("dt_process")),"---") & "</td>"
           ShowHTML "        <td><font size=""1"">" & Nvl(RS1("ds_gre"),"---") & "</td>"
           ShowHTML "        <td><font size=""1"">" & Nvl(RS1("ds_usuario"),"---") & "</td>"
           ShowHTML "      </tr>"
        End If
        RS1.MoveNext
        w_linha = w_linha + 1
        w_tot1  = w_tot1 + 1
      wend
    End If
    If (w_atual > "" and p_tipo = "S") or O = "W" Then
       ShowHTML "      <tr bgcolor=""" & conTrBgColor & """><td colspan=5><font size=""1""><b>Total de arquivos da unidade: " & FormatNumber(w_tot1,0) & "</b></td></tr>"
       w_tot2 = w_tot2 + w_tot1
       ShowHTML "      <tr bgcolor=""" & conTrTotalBgcolor & """><td colspan=5><font size=""2""><b>TOTAL DE ARQUIVOS DA REGIONAL: " & FormatNumber(w_tot2,0) & "</b></td></tr>"
       If Session("regional") = "" or Session("regional") = "00" Then
          ShowHTML "      <tr bgcolor=""" & conTrBgcolor & """><td colspan=5><font size=""2""><b>TOTAL DE ARQUIVOS: " & FormatNumber(RS1.RecordCount,0) & "</b></td></tr>"
       End If
    End If
    ShowHTML "      </center>"
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    If p_tipo = "N" or O = "W" Then ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td colspan=5><font size=""1""><b>Dt.Receb.:</b> Data de Recebimento - <b>Dt.Proces.:</b> Data de Processamento</font></td>" End If
    If O = "L" and p_tipo = "N" Then
       ShowHTML "<tr><td align=""center"" colspan=5>"
       MontaBarra w_dir&w_pagina&par&"&R="&w_Pagina&par&"&O="&O&"&P1="&P1&"&P2="&P2&"&TP="&TP&"&SG="&SG, RS1.PageCount, P3, P4, RS1.RecordCount
       ShowHTML "</tr>"
    End If
    DesConectaBD     
  ElseIf Instr("P",O) > 0 Then
    AbreForm "Form", w_Dir&w_Pagina&par, "POST", "return(Validacao(this));", "RelDuplic",P1,P2,P3,null,TP,SG,R,"L"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td><div align=""justify""><font size=2>Informe nos campos abaixo os valores que deseja filtrar e clique sobre o botão <i>Visualizar</i> para exibir a relação na tela ou sobre <i>Gerar Word</i> para gerar um arquivo no formato Word. Clicando sobre o botão <i>Remover filtro</i>, o filtro existente será apagado.</div><hr>"
    ShowHTML "<tr bgcolor=""" & conTrBgColor & """><td align=""center"">"
    ShowHTML "    <table width=""70%"" border=""0"">"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    SelecaoRegional "<u>R</u>egional:", "R", null, Session("regional"), null, "regional", "informal = 'N'", "onChange=""document.Form.target=''; document.Form.O.value='P'; document.Form.submit();"""
    If Session("regional") = "00" or IsNull(Tvl(Session("regional"))) Then
       SelecaoUnidadeEnsino "<u>U</u>nidade de ensino:", "U", null, p_unidade, null, "p_unidade", null, "onChange=""document.Form.target=''; document.Form.O.value='P'; document.Form.submit();"""
    Else
       SelecaoUnidadeEnsino "<u>U</u>nidade de ensino:", "U", null, p_unidade, null, "p_unidade", "co_sigre like '" & Session("regional") & "*'", "onChange=""document.Form.target=''; document.Form.O.value='P'; document.Form.submit();"""
    End IF
    ShowHTML "          </table>"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Status do arquivo:</b><br>"
    If p_status = "PROCESSAR" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_status"" value=""""> Todos<input " & w_Disabled & " type=""radio"" name=""p_status"" value=""PROCESSAR"" checked> A Processar<input " & w_Disabled & " type=""radio"" name=""p_status"" value=""PROCESSADOS""> Processados"
    ElseIf p_status = "PROCESSADOS" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_status"" value=""""> Todos<input " & w_Disabled & " type=""radio"" name=""p_status"" value=""PROCESSAR""> A Processar<input " & w_Disabled & " type=""radio"" name=""p_status"" value=""PROCESSADOS"" checked> Processados"
    Else
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_status"" value="""" checked> Todos<input " & w_Disabled & " type=""radio"" name=""p_status"" value=""PROCESSAR""> A Processar<input " & w_Disabled & " type=""radio"" name=""p_status"" value=""PROCESSADOS""> Processados"
    End If
    ShowHTML "          </table>"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    MontaRadioNS "Somente arquivos enviados pela Regional?", p_arq_regional, "p_arq_regional"
    ShowHTML "      </table>" 
    ShowHTML "      </tr>" 
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Data de processamento:&nbsp;&nbsp;<input type=""text"" class=""sti"" size=10 maxlength=10 name=""p_processamento_ini"" value=""" & p_processamento_ini & """ onKeyDown=""FormataData(this,event);""> a <input type=""text"" class=""sti"" size=10 maxlength=10 name=""p_processamento_fim"" value=""" & p_processamento_fim & """ onKeyDown=""FormataData(this,event);"">"
    ShowHTML "      <tr><td valign=""top""><font size=""1""><b>Data de recebimento:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type=""text"" class=""sti"" size=10 maxlength=10 name=""p_recebimento_ini"" value=""" & p_recebimento_ini & """ onKeyDown=""FormataData(this,event);""> a <input type=""text"" class=""sti"" size=10 maxlength=10 name=""p_recebimento_fim"" value=""" & p_recebimento_fim & """ onKeyDown=""FormataData(this,event);"">"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>O</U>rdenação por:<br><SELECT ACCESSKEY=""O"" " & w_Disabled & " class=""STS"" name=""p_ordena"" size=""1"">"
    If p_Ordena="DT_PROCESS desc" Then
       ShowHTML "          <option value="""">Recebimento<option value=""DT_PROCESS"" SELECTED>Processamento<option value=""CO_ESCOLA"">Código unidade<option value=""DS_USUARIO"">Usuário"
    ElseIf p_Ordena="CO_ESCOLA" Then
       ShowHTML "          <option value="""">Recebimento<option value=""DT_PROCESS"">Processamento<option value=""CO_ESCOLA"" SELECTED>Código unidade<option value=""DS_USUARIO"">Usuário"
    ElseIf p_Ordena="DS_UNIDADE" Then
       ShowHTML "          <option value="""">Recebimento<option value=""DT_PROCESS"">Processamento<option value=""CO_ESCOLA"">Código unidade<option value=""DS_USUARIO"" SELECTED>Usuário"
    Else
       ShowHTML "          <option value="""" SELECTED>Recebimento<option value=""DT_PROCESS"">Processamento<option value=""CO_ESCOLA"">Código unidade<option value=""DS_USUARIO"">Usuário"
    End If
    ShowHTML "          </select></td>"
    ShowHTML "      </table>"
    ShowHTML "      </tr>"
    ShowHTML "      <tr><td><table border=0 cellpadding=0 cellspacing=0 width=""100%""><tr valign=""top"">"
    ShowHTML "          <td><font size=""1""><b>Exibir:</b><br>"
    If p_tipo = "N" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_tipo"" value=""S""> Apenas totais <input " & w_Disabled & " type=""radio"" name=""p_tipo"" value=""N"" checked> Totais e detalhes "
    Else
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""p_tipo"" value=""S"" checked> Apenas totais <input " & w_Disabled & " type=""radio"" name=""p_tipo"" value=""N""> Totais e detalhes "
    End If
    ShowHTML "          <td valign=""top""><font size=""1""><b><U>L</U>inhas por página:<br><INPUT ACCESSKEY=""L"" " & w_Disabled & " class=""STI"" type=""text"" name=""P4"" size=""4"" maxlength=""4"" value=""" & P4 & """></td>"
    ShowHTML "          </table>"
    ShowHTML "      </tr>"
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

  Set w_regional           = Nothing 
  Set w_tot1               = Nothing 
  Set w_tot2               = Nothing 
  Set p_status             = Nothing 
  Set p_arq_regional       = Nothing 
  Set p_processamento_ini  = Nothing 
  Set p_processamento_fim  = Nothing 
  Set p_recebimento_ini    = Nothing 
  Set p_recebimento_fim    = Nothing 
  Set p_unidade            = Nothing
  Set p_tipo               = Nothing
  Set p_ordena             = Nothing

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

