<%
REM =========================================================================
REM Montagem da sele��o de tipos de acordo
REM -------------------------------------------------------------------------
Sub SelecaoTipoAcordo (label, accesskey, hint, chave, chaveAux, chaveAux2, campo, restricao, atributo)
    DB_GetAgreeType RS, chave, chaveAux, chaveAux2, restricao
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
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da sele��o de tipo de conclus�o
REM -------------------------------------------------------------------------
Sub SelecaoTipoConclusao (label, accesskey, hint, chave, campo, restricao, atributo)
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    If cDbl(nvl(chave,-1)) = 0 Then ShowHTML "          <option value=""0"" SELECTED>Conclus�o com renova��o"   Else ShowHTML "          <option value=""0"">Conclus�o com renova��o"   End If
    If cDbl(nvl(chave,-1)) = 1 Then ShowHTML "          <option value=""1"" SELECTED>Conclus�o sem renova��o"  Else ShowHTML "          <option value=""1"">Conclus�o sem renova��o"  End If
    If cDbl(nvl(chave,-1)) = 2 Then ShowHTML "          <option value=""2"" SELECTED>Rescis�o" Else ShowHTML "          <option value=""2"">Rescis�o" End If
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------


%>