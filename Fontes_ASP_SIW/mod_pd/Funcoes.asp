<%
REM =========================================================================
REM Montagem da seleção de tipos de acordo
REM -------------------------------------------------------------------------
Sub SelecaoTipoAcordo (label, accesskey, hint, chave, chaveAux, chaveAux2, campo, restricao, atributo)
    DB_GetAgreeType RS, null, chaveAux, chaveAux2, restricao
    RS.Sort = "nm_tipo"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ class=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" TITLE=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ class=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("sq_tipo_acordo"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("sq_tipo_acordo") & """ SELECTED>" & RS("nm_tipo")
       Else
          ShowHTML "          <option value=""" & RS("sq_tipo_acordo") & """>" & RS("nm_tipo")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub

REM =========================================================================
REM Montagem da seleção de tipo de PCD
REM -------------------------------------------------------------------------
Sub SelecaoTipoPCD (label, accesskey, hint, chave, campo, restricao, atributo)
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    If nvl(chave,"") = "I" Then ShowHTML "          <option value=""I"" SELECTED>Inicial"        Else ShowHTML "          <option value=""I"">Inicial"          End If
    If nvl(chave,"") = "P" Then ShowHTML "          <option value=""P"" SELECTED>Prorrogação"    Else ShowHTML "          <option value=""P"">Prorrogação"      End If
    If nvl(chave,"") = "C" Then ShowHTML "          <option value=""C"" SELECTED>Complementação" Else ShowHTML "          <option value=""C"">Complementação"   End If
    ShowHTML "          </select>"
End Sub

REM =========================================================================
REM Montagem da seleção de companhias de viagem
REM -------------------------------------------------------------------------
Sub SelecaoCiaTrans (label, accesskey, hint, cliente, chave, chaveAux, campo, restricao, atributo)
    DB_GetCiaTrans RS, cliente, null, null, null, null, null, null, null, null, null
    RS.Sort = "padrao desc, nome"
    If restricao = "S" Then
       RS.Filter = "ativo = 'S'"
    End If
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ class=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" TITLE=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ class=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("chave"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("chave") & """ SELECTED>" & RS("nome")
       Else
          ShowHTML "          <option value=""" & RS("chave") & """>" & RS("nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub

REM =========================================================================
REM Função que retorna S/N indicando se o usuário informado pode cadastrar
REM viagens para qualquer pessoa ou somente para ele mesmo
REM -------------------------------------------------------------------------
Function RetornaCadastrador_PD(p_menu, p_usuario)
  Dim l_acesso

  l_acesso = ""
  
  DB_GetCadastrador_PD p_menu, p_usuario, l_acesso
  RetornaCadastrador_PD = l_acesso
  
  Set l_acesso = Nothing
End Function
%>