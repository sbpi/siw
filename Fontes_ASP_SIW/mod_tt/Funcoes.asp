<%
REM =========================================================================
REM Montagem da seleção de cidade
REM -------------------------------------------------------------------------
Sub SelecaoCidadeCentral (label, accesskey, hint, chave, campo, atributo)
    DB_GetCentralTel RS, null, null, chave, null, null
    
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cInt(nvl(RS("sq_cidade"),0)) = cInt(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("sq_pessoa_endereco") & """ SELECTED>" & RS("nm_cidade")
       Else
          ShowHTML "          <option value=""" & RS("sq_pessoa_endereco") & """>" & RS("nm_cidade")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de cidade
REM -------------------------------------------------------------------------
Sub SelecaoCentralFone (label, accesskey, hint, chave, campo, atributo)
    DB_GetCentralTel RS, null, null, null, null, null
    
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cInt(nvl(RS("chave"),0)) = cInt(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("chave") & """ SELECTED>" & RS("logradouro")
       Else
          ShowHTML "          <option value=""" & RS("chave") & """>" & RS("logradouro")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de cidade
REM -------------------------------------------------------------------------
Sub SelecaoTTUsuario (label, accesskey, hint, chave, chaveAux, campo, atributo)
    DB_GetTTUsuario RS, null, null, chave, null, null
    
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cInt(nvl(RS("usuario"),0)) = cInt(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("usuario") & """ SELECTED>" & RS("nm_usuario")
       Else
          ShowHTML "          <option value=""" & RS("usuario") & """>" & RS("nm_usuario")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção dos telefones de uma pessoa
REM -------------------------------------------------------------------------
Sub SelecaoTelefone2 (label, accesskey, hint, chave, chaveAux, campo, O, restricao)
    DB_GetFoneList RS, w_cliente, null, restricao
    
    
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & ">"
    End If
    ShowHTML "          <option value="""">---"
    
    If O = "A" then
      While Not RS.EOF
        If cInt(nvl(RS("sq_pessoa_telefone"),0)) = cInt(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("sq_pessoa_telefone") & """ SELECTED>" & RS("numero") & " - "& RS("tipo_telefone")
        End If
        RS.MoveNext
      Wend
      DB_GetFoneList RS, w_cliente, null, "TRONCO"
      While Not RS.EOF
        If cInt(nvl(RS("sq_pessoa_telefone"),0)) <> cInt(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("sq_pessoa_telefone") & """>" & RS("numero") & " - "& RS("tipo_telefone")
        End If
        RS.MoveNext
      Wend
    Else
      While Not RS.EOF
        If cInt(nvl(RS("sq_pessoa_telefone"),0)) = cInt(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("sq_pessoa_telefone") & """ SELECTED>" & RS("numero") & " - "& RS("tipo_telefone")
        Else
          ShowHTML "          <option value=""" & RS("sq_pessoa_telefone") & """>" & RS("numero") & " - "& RS("tipo_telefone")
        End If
        RS.MoveNext
      Wend
    End If
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de pessoas
REM -------------------------------------------------------------------------
Sub SelecaoPessoa2 (label, accesskey, hint, chave, chaveAux, campo, O, restricao)
  DB_GetPersonList RS, w_cliente, ChaveAux, restricao, null, null, null, null
  RS.Sort = "nome_resumido"
  If IsNull(hint) Then
    ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & ">"
  Else
    ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & ">"
  End If
  ShowHTML "          <option value="""">---"
    
  If O = "A" then
    While Not RS.EOF
      If cInt(nvl(RS("sq_pessoa"),0)) = cInt(nvl(chave,0)) Then
        ShowHTML "          <option value=""" & RS("sq_pessoa") & """ SELECTED>" & RS("NOME_RESUMIDO") & " (" & RS("SG_UNIDADE") & ")"
      End If
      RS.MoveNext
    Wend   
  
    DB_GetPersonList RS, w_cliente, ChaveAux, "TTUSUCENTRAL", null, null, null, null
    While Not RS.EOF
      If cInt(nvl(RS("sq_pessoa"),0)) <> cInt(nvl(chave,0)) Then
        ShowHTML "          <option value=""" & RS("sq_pessoa") & """>" & RS("NOME_RESUMIDO") & " (" & RS("SG_UNIDADE") & ")"
      End If
      RS.MoveNext
    Wend
    ShowHTML "          </select>"
  Else
    'DB_GetPersonList RS, w_cliente, ChaveAux, restricao, null, null, null, null
    While Not RS.EOF
       If cInt(nvl(RS("sq_pessoa"),0)) = cInt(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("sq_pessoa") & """ SELECTED>" & RS("NOME_RESUMIDO") & " (" & RS("SG_UNIDADE") & ")"
       Else
          ShowHTML "          <option value=""" & RS("sq_pessoa") & """>" & RS("NOME_RESUMIDO") & " (" & RS("SG_UNIDADE") & ")"
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
  End If
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>