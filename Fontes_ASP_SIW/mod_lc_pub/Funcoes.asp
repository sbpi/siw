<%
REM =========================================================================
REM Montagem da seleção de modalidades de licitação
REM -------------------------------------------------------------------------
Sub SelecaoLcModalidade (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetLcModalidade RS, null, w_cliente
    RS.Sort   = "Nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("chave"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("chave") & """ SELECTED>" & RS("Nome")
       Else
          ShowHTML "          <option value=""" & RS("chave") & """>" & RS("Nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção das unidades de fornecimento
REM -------------------------------------------------------------------------
Sub SelecaoUnidadeFornec (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetLcUnidadeFornec RS, null, w_cliente
    RS.Sort   = "Nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("chave"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("chave") & """ SELECTED>" & RS("Nome") & " (" & RS("Sigla") & ")"
       Else
          ShowHTML "          <option value=""" & RS("chave") & """>" & RS("Nome")& " (" & RS("Sigla") & ")"
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de critérios de julgamento
REM -------------------------------------------------------------------------
Sub SelecaoLcCriterio (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetLcCriterio RS, null, w_cliente
    RS.Sort   = "Nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("chave"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("chave") & """ SELECTED>" & RS("Nome")
       Else
          ShowHTML "          <option value=""" & RS("chave") & """>" & RS("Nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de situações de licitação
REM -------------------------------------------------------------------------
Sub SelecaoLcSituacao (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetLcSituacao RS, null, w_cliente
    RS.Sort   = "Nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("chave"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("chave") & """ SELECTED>" & RS("Nome")
       Else
          ShowHTML "          <option value=""" & RS("chave") & """>" & RS("Nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de finalidade
REM -------------------------------------------------------------------------
Sub SelecaoLcFinalidade (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetLcFinalidade RS, null, w_cliente
    RS.Sort   = "Nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("chave"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("chave") & """ SELECTED>" & RS("Nome")
       Else
          ShowHTML "          <option value=""" & RS("chave") & """>" & RS("Nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de fontes de recurso
REM -------------------------------------------------------------------------
Sub SelecaoLcFonte (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetLcFonte RS, null, w_cliente
    RS.Sort   = "Nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("chave"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("chave") & """ SELECTED>" & RS("Nome")
       Else
          ShowHTML "          <option value=""" & RS("chave") & """>" & RS("Nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>