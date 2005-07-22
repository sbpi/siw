<%
REM =========================================================================
REM Montagem da seleção de tipos de lançamento
REM -------------------------------------------------------------------------
Sub SelecaoTipoLancamento (label, accesskey, hint, chave, cliente, campo, restricao, atributo)
    
    Dim l_RS, l_label
    
    DB_GetTipoLancamento l_RS, null, cliente, restricao
    l_RS.Sort = "nome"
    If Nvl(label,"") > "" then l_label = label & "<br>" else l_label="" end if
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & l_label & "</b><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" TITLE=""" & hint & """><font size=""1""><b>" & l_label & "</b><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not l_RS.EOF
       If cDbl(nvl(l_RS("chave"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & l_RS("chave") & """ SELECTED>" & l_RS("nome")
       Else
          ShowHTML "          <option value=""" & l_RS("chave") & """>" & l_RS("nome")
       End If
       l_RS.MoveNext
    Wend
    ShowHTML "          </select>"
    
    Set l_RS    = Nothing
    Set l_label = Nothing
    
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de prioridade
REM -------------------------------------------------------------------------
Sub SelecaoEsfera (label, accesskey, hint, chave, chaveAux, cliente, campo, restricao, atributo)
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" normal" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    If chaveAux = "Federal"   Then ShowHTML " <option value=""F"" SELECTED>Federal"   Else ShowHTML " <option value=""F"">Federal"   End If
    If chaveAux = "Estadual"  Then ShowHTML " <option value=""E"" SELECTED>Estadual"  Else ShowHTML " <option value=""E"">Estadual"  End If
    If chaveAux = "Municipal" Then ShowHTML " <option value=""M"" SELECTED>Municipal" Else ShowHTML " <option value=""M"">Municipal" End If
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de prioridade
REM -------------------------------------------------------------------------
Sub SelecaoCalculo (label, accesskey, hint, chave, chaveAux, cliente, campo, restricao, atributo)
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" normal" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    If chaveAux = "Nominal"  Then ShowHTML " <option value=0 SELECTED>Nominal"  Else ShowHTML " <option value=0>Nominal"  End If
    If chaveAux = "Retenção" Then ShowHTML " <option value=1 SELECTED>Retencao" Else ShowHTML " <option value=1>Retencao" End If
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de tipos de documento
REM -------------------------------------------------------------------------
Sub SelecaoTipoDocumento (label, accesskey, hint, chave, cliente, campo, restricao, atributo)
    DB_GetTipoDocumento RS, null, cliente
    RS.Sort = "nome"
    If IsNull(hint) Then
       ShowHTML " <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML " <td valign=""top"" TITLE=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("chave"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML " <option value=""" & RS("chave") & """ SELECTED>" & RS("nome")
       Else
          ShowHTML " <option value=""" & RS("chave") & """>" & RS("nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção dos acordos
REM -------------------------------------------------------------------------
Sub SelecaoAcordo (label, accesskey, hint,  cliente, chave, chaveAux, campo, restricao, atributo)
    
    Dim l_menu
    DB_GetLinkData RS1, w_cliente, "GC"&mid(SG,3,1)&"CAD"
    l_menu = RS1("sq_menu")
    RS1.Close
    
    'Response.Write "["&"GC"&mid(SG,3,1)&"CAD"&"]"
    'Response.Write "["&l_menu&"]"
    'Response.Write "["&w_usuario&"]"
    'Response.End()
    
    DB_GetSolicList RS, l_menu, w_usuario, "GC"&mid(SG,3,1)&"CAD", 3, _
    null, null, null, null, null, null, _
    null, null, null, null, _
    null, null, null, null, null, null, null, _
    null, null, null, null, null, null, null, null, null
    RS.Sort = "nm_outra_parte_resumido, fim desc"
    If restricao > "" Then RS.Filter = restricao End If
    
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" TITLE=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("sq_siw_solicitacao"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("sq_siw_solicitacao") & """ SELECTED>" & RS("codigo_interno") & " - " & RS("nm_outra_parte_resumido") & " ("  & Mid(RS("objeto"),1,45) & ")"
       Else
          ShowHTML "          <option value=""" & RS("sq_siw_solicitacao") & """>" & RS("codigo_interno") & " - " & RS("nm_outra_parte_resumido") & " (" & Mid(RS("objeto"),1,45) & ")"
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    
    Set l_menu = Nothing
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de parcelas de um acordo
REM -------------------------------------------------------------------------
Sub SelecaoAcordoParcela (label, accesskey, hint, cliente, chave, chaveAux, campo, restricao, atributo)
    
    Dim l_menu
    DB_GetLinkData RS1, w_cliente, "GC"&mid(SG,3,1)&"CAD"
    l_menu = RS1("sq_menu")
    RS1.Close

    DB_GetAcordoParcela RS, chaveAux, null, restricao, null, null, null, w_usuario, "'EE', 'ER'", l_menu
    RS.Sort = "ordem"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" TITLE=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("sq_acordo_parcela"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("sq_acordo_parcela") & """ SELECTED>" & mid(1000 + cDbl(RS("ordem")),2,3) & " - " & FormataDataEdicao(RS("vencimento")) & " - " & FormatNumber(RS("valor"),2)
       Else
          ShowHTML "          <option value=""" & RS("sq_acordo_parcela") & """>" & mid(1000 + cDbl(RS("ordem")),2,3) & " - " & FormataDataEdicao(RS("vencimento")) & " - " & FormatNumber(RS("valor"),2)
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    
    Set l_menu = Nothing
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção das opções de ordenação dos relatórios de contas
REM -------------------------------------------------------------------------
Sub SelecaoOrdenaRel (label, accesskey, hint, cliente, chave, campo, restricao, atributo)
    
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" TITLE=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    If Nvl(chave,"") = "VENCIMENTO" or Nvl(chave,"") = "" Then
       ShowHTML "          <option value=""VENCIMENTO"" SELECTED>Vencimento"
       If Mid(restricao,3,1) = "R" Then
          ShowHTML "          <option value=""NM_PESSOA_RESUMIDO"">Cliente"
       ElseIf Mid(restricao,3,1) = "D" Then
          ShowHTML "          <option value=""NM_PESSOA_RESUMIDO"">Fornecedor"
       End If
       ShowHTML "          <option value=""NM_TRAMITE"">Situação"
    ElseIf Nvl(chave,"") = "SQ_PESSOA" Then
       ShowHTML "          <option value=""VENCIMENTO"">Vencimento"
       If Mid(restricao,3,1) = "R" Then
          ShowHTML "          <option value=""NM_PESSOA_RESUMIDO"" SELECTED>Cliente"
       ElseIf Mid(restricao,3,1) = "D" Then
          ShowHTML "          <option value=""NM_PESSOA_RESUMIDO"" SELECTED>Fornecedor"
       End If
       ShowHTML "          <option value=""NM_TRAMITE"">Situação"
    ElseIf Nvl(chave,"") = "NM_TRAMITE" Then
       ShowHTML "          <option value=""VENCIMENTO"">Vencimento"
       If Mid(restricao,3,1) = "R" Then
          ShowHTML "          <option value=""NM_PESSOA_RESUMIDO"">Cliente"
       ElseIf Mid(restricao,3,1) = "D" Then
          ShowHTML "          <option value=""NM_PESSOA_RESUMIDO"">Fornecedor"
       End If
       ShowHTML "          <option value=""NM_TRAMITE"" SELECTED>Situação"
    End If
    ShowHTML "          </select>"
    
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem de campo do tipo radio com todos os meses do ano
REM -------------------------------------------------------------------------
Sub MontaRadioMes (Label, Chave, Campo)
    Dim l_mes(12), l_texto, l_i
    l_mes(1) = "Janeiro"    : l_mes(2)  = "Fevereiro" : l_mes(3)  = "Março"    : l_mes(4)  = "Abril"
    l_mes(5) = "Maio"       : l_mes(6)  = "Junho"     : l_mes(7)  = "Julho"    : l_mes(8)  = "Agosto"
    l_mes(9) = "Setembro"   : l_mes(10) = "Outubro"   : l_mes(11) = "Novembro" : l_mes(12) = "Dezembro"
    
    ShowHTML "          <td><font size=""1"">"
    If Nvl(Label,"") > "" Then
       ShowHTML Label & "</b><br>"
    End If
    ShowHTML "    <table border=""0"">"
    For l_i = 1 to 6
       If cInt(chave) = l_i Then l_texto = "checked" Else l_texto = "" End If
       ShowHTML "              <tr><td valing=""top""><font size=""1""><input " & w_Disabled & " type=""radio"" name=""" & campo & """ value=""" & Mid(100+l_i,2,2) & """ " & l_texto & "> " & l_mes(l_i)
       If cInt(chave) = l_i+6 Then l_texto = "checked" Else l_texto = "" End If
       ShowHTML "                  <td valing=""top""><font size=""1""><input " & w_Disabled & " type=""radio"" name=""" & campo & """ value=""" & Mid(100+l_i+6,2,2) & """ " & l_texto & "> " & l_mes(l_i+6)
    Next
    ShowHTML "</table>"
 
 Set l_texto = Nothing
 Set l_i     = Nothing
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>