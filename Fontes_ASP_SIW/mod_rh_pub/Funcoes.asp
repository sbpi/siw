<%
REM =========================================================================
REM Montagem da seleção de idiomas
REM -------------------------------------------------------------------------
Sub SelecaoIdioma (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetIdiomList RS, null, "S"
    RS.Sort   = "Nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("sq_idioma"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("sq_idioma") & """ SELECTED>" & RS("Nome")
       Else
          ShowHTML "          <option value=""" & RS("sq_idioma") & """>" & RS("Nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de etnia
REM -------------------------------------------------------------------------
Sub SelecaoEtnia (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetEtniaList RS, null, "S"
    RS.Sort   = "Nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("sq_etnia"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("sq_etnia") & """ SELECTED>" & RS("Nome")
       Else
          ShowHTML "          <option value=""" & RS("sq_etnia") & """>" & RS("Nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de deficiência
REM -------------------------------------------------------------------------
Sub SelecaoDeficiencia (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetDeficiencyList RS, null, "S"
    RS.Sort   = "sq_grupo_deficiencia, nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("sq_deficiencia"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("sq_deficiencia") & """ SELECTED>" & RS("sq_grupo_deficiencia") & " - " & RS("Nome")
       Else
          ShowHTML "          <option value=""" & RS("sq_deficiencia") & """>" & RS("sq_grupo_deficiencia") & " - " & RS("Nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de estado civil
REM -------------------------------------------------------------------------
Sub SelecaoEstadoCivil (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetCivStateList RS, restricao
    RS.Sort   = "nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("sq_estado_civil"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("sq_estado_civil") & """ SELECTED>" & RS("Nome")
       Else
          ShowHTML "          <option value=""" & RS("sq_estado_civil") & """>" & RS("Nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de formação acadêmica
REM -------------------------------------------------------------------------
Sub SelecaoFormacao (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetFormationList RS, chaveAux, null, null
    RS.Sort   = "ordem"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("sq_formacao"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("sq_formacao") & """ SELECTED>" & RS("Nome")
       Else
          ShowHTML "          <option value=""" & RS("sq_formacao") & """>" & RS("Nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>

