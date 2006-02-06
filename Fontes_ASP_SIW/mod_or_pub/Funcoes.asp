<%
REM =========================================================================
REM Montagem da seleção de ações do PPA
REM -------------------------------------------------------------------------
Sub SelecaoAcaoPPA (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    If restricao = "CADASTRO" Then
       DB_GetAcaoPPA RS, null, w_cliente, null, null, null, null, null, null, null, null
       RS.Sort   = "nome"
       RS.Filter = "sq_acao_ppa_pai = null and chave <> " &  cDbl(Nvl(chaveAux,0))
    ElseIf restricao = "IDENTIFICACAO" Then
       DB_GetAcaoPPA RS, null, w_cliente, null, null, null, null, null, chaveAux, null, null
       RS.Sort   = "nome"
       RS.Filter = "sq_acao_ppa_pai <> null and acao = 0"
    ElseIf restricao = "FINANCIAMENTO" Then
       DB_GetAcaoPPA RS, null, w_cliente, null, null, null, null, null, chaveAux, null, null
       RS.Sort   = "nome"
       RS.Filter = "sq_acao_ppa_pai <> null and outras_acao = 0 and acao = 0"
    Else
       DB_GetAcaoPPA RS, chave, w_cliente, null, null, null, null, null, null, null, null
       RS.Sort   = "nome"
    End If
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If restricao = "CADASTRO" Then
          If cDbl(nvl(RS("chave"),0)) = cDbl(nvl(chave,0)) Then
             ShowHTML "          <option value=""" & RS("chave") & """ SELECTED>" & RS("Nome") & " (" & RS("codigo") & ")"
          Else
             ShowHTML "          <option value=""" & RS("chave") & """>" & RS("Nome") & " (" & RS("codigo") & ")"
          End If
       Else
          If cDbl(nvl(RS("chave"),0)) = cDbl(nvl(chave,0)) Then
             ShowHTML "          <option value=""" & RS("chave") & """ SELECTED>" & RS("Nome") & " (" & RS("cd_pai") & "." & RS("codigo") & ")"
          Else
             ShowHTML "          <option value=""" & RS("chave") & """>" & RS("Nome") & " (" & RS("cd_pai") & "." & RS("codigo") & ")"
          End If
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de iniciativas prioritarias
REM -------------------------------------------------------------------------
Sub SelecaoOrPrioridade (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetOrPrioridade RS, null, w_cliente, null, null, null, null
    RS.Sort   = "nome"
    Dim w_chave_test
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("chave"),0)) <> cDbl(w_chave_test) Then
          If cDbl(nvl(RS("chave"),0)) = cDbl(nvl(chave,0)) Then
             ShowHTML "          <option value=""" & RS("chave") & """ SELECTED>" & RS("Nome")
          Else
             ShowHTML "          <option value=""" & RS("chave") & """>" & RS("Nome")
          End If
       End If
       w_chave_test = nvl(RS("chave"),0)
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem do cabeçalho de documentos Word
REM -------------------------------------------------------------------------
Sub CabecalhoWordOR (p_titulo, p_pagina, w_logo)
    ShowHTML "<BASE HREF=""" & conRootSIW & """>"
    ShowHTML "<TABLE WIDTH=""100%"" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN=""LEFT"" SRC=""" & w_logo & """><TD ALIGN=""RIGHT""><B><FONT SIZE=4 COLOR=""#000000"">"
    ShowHTML p_titulo
    ShowHTML "</FONT><TR><TD WIDTH=""50%"" ALIGN=""RIGHT""><B><font size=1 COLOR=""#000000"">" & DataHora() & "</B>"
    ShowHTML "<TR><TD COLSPAN=""2"" ALIGN=""RIGHT""><B><FONT SIZE=2 COLOR=""#000000"">Página: " & p_pagina & "</B></TD></TR>"
    ShowHTML "</TD></TR>"
    ShowHTML "</FONT></B></TD></TR></TABLE>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Remontagem do cabeçalho de documentos Word
REM -------------------------------------------------------------------------
Sub CabecalhoWordRel (w_logo, w_pag, w_linha, p_responsavel, p_prioridade, _
                      p_selecionada_mpog, p_selecionada_relevante, p_tarefas_atraso, w_filtro, _
                      p_campos)
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    ShowHTML "</table>"
    ShowHTML "</center></div>"
    ShowHTML "    <br style=""page-break-after:always"">"
    w_linha = 5
    w_pag   = w_pag + 1
    CabecalhoWordOR "Iniciativa Prioritária", w_pag, w_logo
    ShowHTML "<div align=center><center>"
    ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
    w_filtro = ""
    If p_responsavel           > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Responsável<td><font size=1>[<b>" & p_responsavel & "</b>]"                     End If
    If p_prioridade            > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Prioridade<td><font size=1>[<b>" & RetornaPrioridade(p_prioridade) & "</b>]"    End If
    If p_selecionada_mpog      > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Selecionada MP<td><font size=1>[<b>" & p_selecionada_mpog & "</b>]"             End If
    If p_selecionada_relevante > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Selecionada Relevante<td><font size=1>[<b>" & p_selecionada_relevante & "</b>]" End If
    If p_tarefas_atraso        > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Ações com tarefas em atraso&nbsp;<font size=1>[<b>" & p_tarefas_atraso & "</b>]&nbsp;"  End If
    ShowHTML "<tr><td align=""left"" colspan=3>"
    If w_filtro                > "" Then ShowHTML "<table border=0><tr valign=""top""><td><font size=1><b>Filtro:</b><td nowrap><font size=1><ul>" & w_filtro & "</ul></td></tr></table></td></tr>"         End If    
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "        <td><font size=""1""><b>Nome</font></td>"
    If Instr(p_campos,"responsavel") Then
       ShowHTML "     <td><font size=""1""><b>Responsável</font></td>"
    End If
    If Instr(p_campos,"email") Then
       ShowHTML "     <td><font size=""1""><b>e-Mail</font></td>"
    End If
    If Instr(p_campos,"telefone") Then
       ShowHTML "     <td><font size=""1""><b>Telefone</font></td>"
    End If
    If Instr(p_campos,"aprovado")   Then ShowHTML "          <td><font size=""1""><b>Aprovado</font></td>" End If
    If Instr(p_campos,"empenhado")  Then ShowHTML "          <td><font size=""1""><b>Empenhado</font></td>" End If
    If Instr(p_campos,"saldo")      Then ShowHTML "          <td><font size=""1""><b>Saldo</font></td>" End If
    If Instr(p_campos,"liquidado")  Then ShowHTML "          <td><font size=""1""><b>Liquidado</font></td>" End If
    If Instr(p_campos,"liquidar")   Then ShowHTML "          <td><font size=""1""><b>A liquidar</font></td>" End If
    ShowHTML "      </tr>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Remontagem do cabeçalho de documentos Word
REM -------------------------------------------------------------------------
Sub CabecalhoWordSint (w_logo, w_pag, w_linha, p_responsavel, p_prioridade, _
                       p_selecionada_mpog, p_selecionada_relevante, p_tarefas_atraso, w_filtro, _
                       p_campos)
    ShowHTML "    </table>"
    ShowHTML "  </td>"
    ShowHTML "</tr>"
    ShowHTML "</table>"
    ShowHTML "</center></div>"
    ShowHTML "    <br style=""page-break-after:always"">"
    w_linha = 5
    w_pag   = w_pag + 1
    CabecalhoWordOR "Iniciativa Prioritária", w_pag, w_logo
    ShowHTML "<div align=center><center>"
    ShowHTML "<table border=""0"" cellpadding=""0"" cellspacing=""0"" width=""100%"">"
    w_filtro = ""
    If p_responsavel           > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Responsável<td><font size=1>[<b>" & p_responsavel & "</b>]"                     End If
    If p_prioridade            > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Prioridade<td><font size=1>[<b>" & RetornaPrioridade(p_prioridade) & "</b>]"    End If
    If p_selecionada_mpog      > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Selecionada MP<td><font size=1>[<b>" & p_selecionada_mpog & "</b>]"             End If
    If p_selecionada_relevante > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Selecionada Relevante<td><font size=1>[<b>" & p_selecionada_relevante & "</b>]" End If
    If p_tarefas_atraso        > "" Then w_filtro = w_filtro & "<tr valign=""top""><td align=""right""><font size=1>Tarefas em atraso&nbsp;<font size=1>[<b>" & p_tarefas_atraso & "</b>]&nbsp;"  End If
    ShowHTML "<tr><td align=""left"" colspan=3>"
    If w_filtro                > "" Then ShowHTML "<table border=0><tr valign=""top""><td><font size=1><b>Filtro:</b><td nowrap><font size=1><ul>" & w_filtro & "</ul></td></tr></table></td></tr>"         End If    
    ShowHTML "<tr><td align=""center"" colspan=3>"
    ShowHTML "    <TABLE WIDTH=""100%"" bgcolor=""" & conTableBgColor & """ BORDER=""" & conTableBorder & """ CELLSPACING=""" & conTableCellSpacing & """ CELLPADDING=""" & conTableCellPadding & """ BorderColorDark=""" & conTableBorderColorDark & """ BorderColorLight=""" & conTableBorderColorLight & """>"
    ShowHTML "      <tr bgcolor=""" & conTrBgColor & """ align=""center"">"
    ShowHTML "        <td><font size=""1""><b>Nome</font></td>"
    If Instr(p_campos,"responsavel") Then
       ShowHTML "     <td><font size=""1""><b>Responsável</font></td>"
    End If
    If Instr(p_campos,"email") Then
       ShowHTML "     <td><font size=""1""><b>e-Mail</font></td>"
    End If
    If Instr(p_campos,"telefone") Then
       ShowHTML "     <td><font size=""1""><b>Telefone</font></td>"
    End If
    If Instr(p_campos,"aprovado")   Then ShowHTML "          <td><font size=""1""><b>Aprovado</font></td>" End If
    If Instr(p_campos,"empenhado")  Then ShowHTML "          <td><font size=""1""><b>Empenhado</font></td>" End If
    If Instr(p_campos,"saldo")      Then ShowHTML "          <td><font size=""1""><b>Saldo</font></td>" End If
    If Instr(p_campos,"liquidado")  Then ShowHTML "          <td><font size=""1""><b>Liquidado</font></td>" End If
    If Instr(p_campos,"liquidar")   Then ShowHTML "          <td><font size=""1""><b>A liquidar</font></td>" End If
    ShowHTML "      </tr>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Funçao para retornar sim ou nao
REM -------------------------------------------------------------------------
Function RetornaSimNao (p_chave)
    Select Case p_Chave
       Case "S" RetornaSimNao = "Sim"
       Case "N" RetornaSimNao = "Não"
       Case Else RetornaSimNao = "Não"
    End Select
End Function
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>