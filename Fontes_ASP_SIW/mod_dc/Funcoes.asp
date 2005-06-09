<%
REM =========================================================================
REM Montagem da seleção de tipos de tabela
REM -------------------------------------------------------------------------
Sub SelecaoTipoTabela (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetTipoTabela RS, null
    RS.Sort   = "Nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
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
REM Montagem da seleção de tipos de tabela
REM -------------------------------------------------------------------------
Sub SelecaoTrigger (label, accesskey, hint, cliente, chave, chaveAux, chaveAux2, chaveAux3, campo, restricao, atributo)
    DB_GetTrigger RS, cliente, chave, chaveAux3, chaveAux2, ChaveAux
    RS.Sort   = "nm_trigger, nm_sistema, nm_usuario"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("chave"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("chave") & """ SELECTED>" & RS("nm_trigger")
       Else
          ShowHTML "          <option value=""" & RS("chave") & """>" & RS("nm_trigger")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de eventos de trigger
REM -------------------------------------------------------------------------
Sub SelecaoEvento (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetEventoTrigger RS, null
    RS.Sort   = "Nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
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
REM Montagem da seleção de usuários
REM -------------------------------------------------------------------------
Sub SelecaoUsuario (label, accesskey, hint, cliente, chave, chaveAux, campo, restricao, atributo)
    DB_GetUsuario RS, cliente, null, ChaveAux
    RS.Sort   = "Nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
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
REM Montagem da seleção de tipos de dado
REM -------------------------------------------------------------------------
Sub SelecaoTipoDado (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetTipoDado RS, null
    RS.Sort   = "Nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
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
REM Montagem da seleção de tipos de dado
REM -------------------------------------------------------------------------
Sub SelecaoDadoTipo (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetTipoDado RS, null
    RS.Sort   = "Nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
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
REM Montagem da seleção de tipos de índice
REM -------------------------------------------------------------------------
Sub SelecaoTipoIndice (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetTipoIndice RS, null
    RS.Sort   = "Nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
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
REM Montagem da seleção de tipos de stored procedure
REM -------------------------------------------------------------------------
Sub SelecaoTipoSP (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetTipoSP RS, null
    RS.Sort   = "Nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
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
REM Montagem da seleção de sistema
REM -------------------------------------------------------------------------
Sub SelecaoSistema (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetSistema RS, null, chaveAux
    RS.Sort   = "Nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
      ' Esse trecho de cógigo implementado por Alexandre Papadópolis, aparentemente não altera a exibição de tela.(Egisberto Vicente da Silva)
      If cDbl(nvl(RS("chave"),0)) = cDbl(nvl(chave,0)) Then
         ShowHTML "          <option value=""" & RS("chave") & """ SELECTED>" & RS("Sigla") & " - " & RS("Nome")
       Else
          ShowHTML "          <option value=""" & RS("chave") & """>" & RS("Sigla") & " - " & RS("Nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de Tipos de Arquivo
REM -------------------------------------------------------------------------
Sub SelecaoTipoArquivo (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    If chave = "C" Then ShowHTML "          <option value=""C"" SELECTED>Configuração"      Else ShowHTML "          <option value=""C"">Configuração"      End If
    If chave = "I" Then ShowHTML "          <option value=""I"" SELECTED>Inclusão"          Else ShowHTML "          <option value=""I"">Inclusão"          End If
    If chave = "R" Then ShowHTML "          <option value=""R"" SELECTED>Requisitos"        Else ShowHTML "          <option value=""R"">Requisitos"        End If
    If chave = "G" Then ShowHTML "          <option value=""G"" SELECTED>Rotinas"           Else ShowHTML "          <option value=""G"">Rotinas"           End If
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de Obrigatoriedade
REM -------------------------------------------------------------------------
Sub SelecaoObrigatorio (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    If chave = "S" Then ShowHTML "          <option value=""S"" SELECTED>Sim"      Else ShowHTML "          <option value=""S"">SIM"      End If
    If chave = "N" Then ShowHTML "          <option value=""N"" SELECTED>Não"      Else ShowHTML "          <option value=""N"">Não"      End If
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------


REM =========================================================================
REM Exibe Tipos de Arquivo
REM -------------------------------------------------------------------------
Function ExibeTipoArquivo (chave)
    
    Select case chave
       Case "C"  ExibeTipoArquivo = "Configuração"
       Case "I"  ExibeTipoArquivo = "Inclusão"
       Case "R"  ExibeTipoArquivo = "Requisitos"
       Case "G"  ExibeTipoArquivo = "Rotinas"
       Case Else ExibeTipoArquivo = "---"
    End Select
End Function
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de Tabela
REM -------------------------------------------------------------------------
Sub SelecaoTabela (label, accesskey, hint, cliente, chave, chaveAux, chaveAux2, campo, restricao, atributo)
    DB_GetTabela RS, cliente, null, null, ChaveAux2,chaveAux,null,null, restricao
    RS.Sort   = "nm_usuario, nome"
    If IsNull(hint) Then
      ShowHTML " <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else ShowHTML " <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("chave"),0)) = cDbl(nvl(chave,0)) Then
          If Nvl(chaveAux,"nulo") = "nulo" Then
             ShowHTML "          <option value=""" & RS("chave") & """ SELECTED>" & RS("nm_usuario") & "." & RS("nome")
          Else
             ShowHTML "          <option value=""" & RS("chave") & """ SELECTED>" & RS("nome")
          End If
       Else
          If Nvl(chaveAux,"nulo") = "nulo" Then
             ShowHTML "          <option value=""" & RS("chave") & """>" & RS("nm_usuario") & "." & RS("nome")
          Else
             ShowHTML "          <option value=""" & RS("chave") & """>" & RS("nome")
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
REM Montagem da seleção de Tabela
REM -------------------------------------------------------------------------
Sub SelecaoSP (label, accesskey, hint, cliente, chave, chaveAux, chaveAux2, campo, restricao, atributo)
    DB_GetStoredProcedure RS, cliente, null, chave, null, null, chaveAux2, null, restricao
    RS.Sort   = "nm_sp"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("chave"),0)) = cDbl(nvl(ChaveAux,0)) Then
          ShowHTML "          <option value=""" & RS("chave") & """ SELECTED>" & RS("nm_usuario")& "." & RS("nm_sp")
       Else
          ShowHTML "          <option value=""" & RS("chave") & """>" & RS("nm_usuario")& "." & RS("nm_sp")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------



REM =========================================================================
REM Montagem da seleção de Tipos de Arquivo
REM -------------------------------------------------------------------------
Sub SelecaoArquivo (label, accesskey, hint, cliente, chave, chaveAux, campo, restricao, atributo)
    DB_GetArquivo RS, cliente, null,chaveAux,null,null
    RS.Sort   = "nm_arquivo"

    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("chave"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("chave") & """ SELECTED>" & RS("nm_arquivo")
       Else
          ShowHTML "          <option value=""" & RS("chave") & """>" & RS("nm_arquivo")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub

REM =========================================================================
REM Montagem da seleção de Tipos de Arquivo
REM -------------------------------------------------------------------------
Sub SelecaoTipoParam (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    If chave = "E" Then ShowHTML "          <option value=""E"" SELECTED>Entrada" Else ShowHTML "          <option value=""E"">Entrada" End If
    If chave = "S" Then ShowHTML "          <option value=""S"" SELECTED>Saída"   Else ShowHTML "          <option value=""S"">Saída"   End If
    If chave = "A" Then ShowHTML "          <option value=""A"" SELECTED>Ambos"   Else ShowHTML "          <option value=""A"">Ambos"   End If
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>