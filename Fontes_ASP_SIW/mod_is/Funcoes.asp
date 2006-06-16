<%
REM =========================================================================
REM Montagem da seleção de ações do PPA(tabela SIGPLAN)
REM -------------------------------------------------------------------------
Sub SelecaoAcaoPPA (label, accesskey, hint, p_cliente, p_ano, p_programa, p_acao, p_subacao, p_unidade, campo, restricao, atributo, p_chave, menu)
   Dim l_chave   
   l_chave = p_programa & p_acao & p_subacao & p_unidade
   If restricao = "FINANCIAMENTO" Then
      DB_GetAcaoPPA_IS RS, p_cliente, p_ano, p_programa, p_acao, null , p_unidade, restricao, p_chave, null
      RS.Sort   = "descricao_acao"
   ElseIf restricao = "IDENTIFICACAO" or restricao = "CONSULTA" Then
      DB_GetAcaoPPA_IS RS, p_cliente, p_ano, null, null, null, null, restricao, null, null
      RS.Sort   = "descricao_acao"
   Else
      DB_GetAcaoPPA_IS RS, p_cliente, p_ano, p_programa, p_acao, null, p_unidade, null, null, null
      RS.Sort   = "descricao_acao"
   End If
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If nvl(RS("chave"),"-") = nvl(l_chave,"-") Then
          ShowHTML "          <option value=""" & RS("chave") & """ SELECTED>" & RS("cd_unidade") & "." & RS("cd_programa") & "." & RS("cd_acao") & " - " &  Mid(RS("descricao_acao"),1,40) & " (" & Mid(RS("ds_unidade"),1,30) & ")"
       Else
          ShowHTML "          <option value=""" & RS("chave") & """>" & RS("cd_unidade") & "." & RS("cd_programa") & "." & RS("cd_acao") & " - " &  Mid(RS("descricao_acao"),1,40) & " (" & Mid(RS("ds_unidade"),1,30) & ")"
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    
    ShowHTML "              <a class=""ss"" href=""#"" onClick=""window.open('Acao.asp?par=BuscaAcao&TP=" & RemoveTP(TP) & "&w_cliente=" &p_cliente& "&w_ano=" &p_ano&  "&w_programa=" &p_programa& "&w_unidade=" &p_unidade& "&w_acao=" &p_acao& "&w_chave=" &p_chave& "&w_menu=" &menu& "&restricao=" &restricao& "&campo=" &campo& "','Acao','top=10,left=10,width=780,height=550,toolbar=yes,status=yes,resizable=yes,scrollbars=yes'); return false;"" title=""Clique aqui para selecionar a ação.""><img src=images/Folder/Explorer.gif border=0 align=top height=15 width=15></a>"
    'ShowHTML "              <a class=""ss"" href=""#"" onClick=""document.Form." & campo & ".selectedIndex=''; return false;"" title=""Clique aqui para apagar o valor deste campo.""><img src=images/Folder/Recyfull.gif border=0 align=top height=15 width=15></a>"

    Set l_chave = Nothing
End Sub

REM =========================================================================
REM Montagem da seleção de ações do PPA(tabela SIGPLAN)
REM -------------------------------------------------------------------------
Sub SelecaoProgramaPPA (label, accesskey, hint, cliente, ano, chave, campo, restricao, atributo, menu)
   
    If restricao = "IDENTIFICACAO" Then
      DB_GetProgramaPPA_IS RS, null, w_cliente, w_ano, restricao, null
       RS.Sort   = "ds_programa"
    ElseIf restricao = "RELATORIO" Then
      DB_GetProgramaPPA_IS RS, null, w_cliente, w_ano, null, null
       RS.Sort   = "ds_programa"    
    Else
       DB_GetProgramaPPA_IS RS, chave, w_cliente, w_ano, restricao, null
       RS.Sort   = "ds_programa"
    End If
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If nvl(RS("cd_programa"),"-") = nvl(chave,"-") Then
          ShowHTML "          <option value=""" & RS("cd_programa") & """ SELECTED>" & RS("cd_programa") & " - " & RS("ds_programa")
       Else
          ShowHTML "          <option value=""" & RS("cd_programa") & """>" & RS("cd_programa") & " - " & RS("ds_programa")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    ShowHTML "              <a class=""ss"" href=""#"" onClick=""window.open('Programa.asp?par=BuscaPrograma&TP=" & RemoveTP(TP) & "&w_cliente=" &cliente& "&w_ano=" &ano& "&w_menu=" &menu& "&restricao=" &restricao& "&campo=" &campo& "','Programa','top=10,left=10,width=780,height=550,toolbar=yes,status=yes,resizable=yes,scrollbars=yes'); return false;"" title=""Clique aqui para selecionar o programa.""><img src=images/Folder/Explorer.gif border=0 align=top height=15 width=15></a>"
    'ShowHTML "              <a class=""ss"" href=""#"" onClick=""document.Form." & campo & ".selectedIndex=''; return false;"" title=""Clique aqui para apagar o valor deste campo.""><img src=images/Folder/Recyfull.gif border=0 align=top height=15 width=15></a>"
End Sub

REM =========================================================================
REM Montagem da seleção de programas cadastrados no INFRASIG
REM -------------------------------------------------------------------------
Sub SelecaoProgramaIS (label, accesskey, hint, cliente, ano, chave, campo, restricao, atributo)
    If restricao = "CADASTRADOS" Then
      DB_GetPrograma_IS RS, Request("w_cd_programa"), w_ano, w_cliente, restricao
      RS.Sort   = "titulo"
    End If
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("chave"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("chave") & """ SELECTED>" & RS("titulo")
       Else
          ShowHTML "          <option value=""" & RS("chave") & """>" & RS("titulo")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub

REM =========================================================================
REM Montagem da seleção de ações do PPA(tabela SIGPLAN)
REM -------------------------------------------------------------------------
Sub SelecaoFuncao (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
   
   DB_GetProgramaPPA_IS RS, chave, chaveaux, w_cliente, w_ano, null
   RS.Sort   = "nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("chave"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("chave") & """ SELECTED>" & RS("Nome") & " (" & RS("chave") & ")"
       Else
          ShowHTML "          <option value=""" & RS("chave") & """>" & RS("Nome") & " (" & RS("chave") & ")"
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub

REM =========================================================================
REM Montagem da seleção de iniciativas prioritarias
REM -------------------------------------------------------------------------
Sub SelecaoIsProjeto (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetProjeto_IS RS, null, w_cliente, null, null, null, null, null, null, "S", null, null, null, restricao, null
    RS.Sort   = "nome"
    Dim w_chave_test
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
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
REM Montagem da seleção da natureza dos programas do PPA
REM -------------------------------------------------------------------------
Sub SelecaoNatureza_IS (label, accesskey, hint, cliente, chave, campo, restricao, atributo)
   
   DB_GetNatureza_IS RS, null, cliente, null, null
   RS.Sort = "nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
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
REM Montagem da seleção do horizonte temporal dos programas do PPA
REM -------------------------------------------------------------------------
Sub SelecaoHorizonte_IS (label, accesskey, hint, cliente, chave, campo, restricao, atributo)
   
   DB_GetHorizonte_IS RS, null, cliente, null, null
   RS.Sort = "nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
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
REM Rotina de selecao das unidades de planejamento e administrativas do modulo infra-sig
REM -------------------------------------------------------------------------
Sub SelecaoUnidade_IS (label, accesskey, hint, chave, chaveAux, campo, atributo, tipo)
    If tipo = "ADMINISTRATIVA" Then
       DB_GetIsUnidade_IS RS, null, w_cliente, "S", null
    ElseIf tipo = "PLANEJAMENTO" Then
       DB_GetIsUnidade_IS RS, null, w_cliente, null, "S"
    End If
    RS.Sort = "nome"
    If RS.RecordCount > 100 Then
       Dim w_nm_unidade, w_sigla
       ShowHTML "<INPUT type=""hidden"" name=""" & campo & """ value=""" & chave &""">"
       If chave > "" Then
          DB_GetIsUnidade_IS RS, chave, w_cliente, null, null
          w_nm_unidade = RS("nome")
          w_sigla      = RS("sigla")
       End If
       If IsNull(hint) Then
          ShowHTML "      <td valign=""top""><font size=""1""><b>" & Label & "</b><br>"
          ShowHTML "          <input READONLY ACCESSKEY=""" & accesskey & """ CLASS=""STS"" type=""text"" name=""" & campo & "_nm" & """ SIZE=""60"" VALUE=""" & w_nm_unidade & """ " & atributo & ">"
       Else
          ShowHTML "      <td valign=""top""title=""" & hint & """><font size=""1""><b>" & Label & "</b><br>"
          ShowHTML "          <input READONLY ACCESSKEY=""" & accesskey & """ CLASS=""STS"" type=""text"" name=""" & campo & "_nm" & """ SIZE=""60"" VALUE=""" & w_nm_unidade & """ " & atributo & ">"
       End If
       ShowHTML "              <a class=""SS"" href=""#"" onClick=""window.open('" & w_dir_volta & "EO.asp?par=BuscaUnidade&TP=" & TP & "&w_cliente=" &w_cliente& "&ChaveAux=" &ChaveAux& "&restricao=" &restricao& "&campo=" &campo& "','Unidade','top=70 left=100 width=600 height=400 toolbar=yes status=yes resizable=yes scrollbars=yes'); return false;"" title=""Clique aqui para selecionar a unidade.""><img src=images/Folder/Explorer.gif border=0></a>"
       ShowHTML "              <a class=""SS"" href=""#"" onClick=""document.Form." & campo & "_nm" & ".value=''; document.Form." & campo & ".value=''; return false;"" title=""Clique aqui para apagar o valor deste campo.""><img src=images/Folder/Recyfull.gif border=0></a>"
    Else
       If IsNull(hint) Then
          ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
       Else
          ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
       End If
       ShowHTML "          <option value="""">---"
       While Not RS.EOF
          If cDbl(nvl(RS("chave"),0)) = cDbl(nvl(chave,0)) Then
             ShowHTML "          <OPTION VALUE=""" & RS("chave") & """ SELECTED>" & RS("Nome") & " (" & RS("Sigla") & ")"
          Else
            ShowHTML "          <OPTION VALUE=""" & RS("chave") & """>" & RS("Nome") & " (" & RS("Sigla") & ")"
          End If
          RS.MoveNext
       Wend
       ShowHTML "          </select>"
    End If
End Sub

REM =========================================================================
REM Montagem da seleção da periodicidades (esquema SIGPLAN)
REM -------------------------------------------------------------------------
Sub SelecaoPeriodicidade_IS (label, accesskey, hint, p_chave, campo, restricao, atributo)

   DB_GetPeriodicidade_IS RS, null, "S"
   RS.Sort   = "nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("chave"),0)) = cDbl(nvl(p_chave,0)) Then
          ShowHTML "          <option value=""" & RS("chave") & """ SELECTED>" & RS("nome")
       Else
          ShowHTML "          <option value=""" & RS("chave") & """>" & RS("nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub

REM =========================================================================
REM Montagem da seleção das bases geográficas (esquema SIGPLAN)
REM -------------------------------------------------------------------------
Sub SelecaoBaseGeografica_IS (label, accesskey, hint, p_chave, campo, restricao, atributo)

   DB_GetBaseGeografica_IS RS, null, "S"
   RS.Sort   = "nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("chave"),0)) = cDbl(nvl(p_chave,0)) Then
          ShowHTML "          <option value=""" & RS("chave") & """ SELECTED>" & RS("nome")
       Else
          ShowHTML "          <option value=""" & RS("chave") & """>" & RS("nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub

REM =========================================================================
REM Montagem da seleção das unidades de medidas (esquema SIGPLAN)
REM -------------------------------------------------------------------------
Sub SelecaoUniMedida_IS (label, accesskey, hint, p_chave, campo, restricao, atributo)

   DB_GetUniMedida_IS RS, null, "S"
   RS.Sort   = "nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("chave"),0)) = cDbl(nvl(p_chave,0)) Then
          ShowHTML "          <option value=""" & RS("chave") & """ SELECTED>" & RS("nome")
       Else
          ShowHTML "          <option value=""" & RS("chave") & """>" & RS("nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub

REM =========================================================================
REM Montagem de campo do tipo de indicador
REM -------------------------------------------------------------------------
Sub MontaTipoIndicador (Label, Chave, Campo)
    ShowHTML "          <td><font size=""1"">"
    If Nvl(Label,"") > "" Then
       ShowHTML Label & "</b><br>"
    End If
    If uCase(Chave) = "P" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""" & campo & """ value=""P"" checked> Processo <input " & w_Disabled & " type=""radio"" name=""" & campo & """ value=""R""> Resultado <input " & w_Disabled & " type=""radio"" name=""" & campo & """ value=""""> ND "
    ElseIf uCase(Chave) = "R" Then
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""" & campo & """ value=""P""> Processo <input " & w_Disabled & " type=""radio"" name=""" & campo & """ value=""R"" checked> Resultado <input " & w_Disabled & " type=""radio"" name=""" & campo & """ value=""""> ND "
    Else
       ShowHTML "              <input " & w_Disabled & " type=""radio"" name=""" & campo & """ value=""P""> Processo <input " & w_Disabled & " type=""radio"" name=""" & campo & """ value=""R"" > Resultado <input " & w_Disabled & " type=""radio"" name=""" & campo & """ value="""" checked> ND "
    End If
End Sub

REM =========================================================================
REM Montagem da seleção dos tipos de restrições (esquema SIGPLAN)
REM -------------------------------------------------------------------------
Sub SelecaoTPRestricao_IS (label, accesskey, hint, p_chave, campo, restricao, atributo)

   DB_GetTPRestricao_IS RS, null, "S"
   RS.Sort   = "nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("chave"),0)) = cDbl(nvl(p_chave,0)) Then
          ShowHTML "          <option value=""" & RS("chave") & """ SELECTED>" & RS("nome")
       Else
          ShowHTML "          <option value=""" & RS("chave") & """>" & RS("nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub

REM =========================================================================
REM Montagem da seleção de ações do PPA(tabela SIGPLAN)
REM -------------------------------------------------------------------------
Sub SelecaoLocalizador_IS (label, accesskey, hint, chave, w_cd_programa, w_cd_acao, w_cd_unidade, campo, restricao, atributo)
   
   DB_GetPPALocalizador_IS RS, w_cliente, w_ano, w_cd_programa, w_cd_acao, w_cd_unidade, null
   RS.Sort   = "nome"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("cd_subacao"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("cd_subacao") & """ SELECTED>" & RS("Nome")
       Else
          ShowHTML "          <option value=""" & RS("cd_subacao") & """>" & RS("Nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub

REM =========================================================================
REM Montagem da seleção das tarefas
REM -------------------------------------------------------------------------
Sub SelecaoTarefa (label, accesskey, hint, cliente, ano, p_chave, campo, restricao, atributo)
    
    DB_GetLinkData RS, w_cliente, "ISTCAD"
    DB_GetSolicList_IS RS, RS("sq_menu"), w_usuario, "ISTCAD", 3, _
       null, null, null, null, null, null, _
       null, null, null, null, _
       null, null, null, null, null, null, null, _
       null, null, null, null, restricao, null, null, null, null, null, w_ano

    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("sq_siw_solicitacao"),0)) = cDbl(nvl(p_chave,0)) Then
          ShowHTML "          <option value=""" & RS("sq_siw_solicitacao") & """ SELECTED>" & RS("titulo") & " (" & RS("sq_siw_solicitacao") & ")"
       Else
          ShowHTML "          <option value=""" & RS("sq_siw_solicitacao") & """>" & RS("titulo") & " (" & RS("sq_siw_solicitacao") & ")"
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub

REM =========================================================================
REM Montagem da seleção de ações cadastradas
REM -------------------------------------------------------------------------
Sub SelecaoAcao (label, accesskey, hint, p_cliente, p_ano, p_programa, p_acao, p_subacao, p_unidade, campo, restricao, atributo, chave)
    DB_GetAcao_IS RS, null, null, null, w_ano, w_cliente, restricao, null
    RS.Sort = "titulo"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("chave"),0)) = cDbl(nvl(chave,0)) Then
          If Nvl(RS("sq_isprojeto"),"") > "" Then
             ShowHTML "          <option value=""" & RS("chave") & """ SELECTED>" & RS("chave") &  " - "  &  RS("titulo")
          Else
             ShowHTML "          <option value=""" & RS("chave") & """ SELECTED>" & RS("codigo") &  " - "  &  RS("titulo")
          End If
       Else
          If Nvl(RS("sq_isprojeto"),"") > "" Then
             ShowHTML "          <option value=""" & RS("chave") & """>" & RS("chave") &  " - "  &  RS("titulo")
          Else
             ShowHTML "          <option value=""" & RS("chave") & """>" & RS("codigo") &  " - "  &  RS("titulo")
          End If
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub

REM =========================================================================
REM Função que formata dias, horas, minutos e segundos a partir dos segundos
REM -------------------------------------------------------------------------
Function FormataDataXML(w_dt_grade)
  Dim l_dt_grade, l_dt_final
  l_dt_grade = Nvl(cDate(w_dt_grade),"")
  If l_dt_grade > "" Then
     l_dt_final = Year(l_dt_grade)&"-"
     If Len(Month(l_dt_grade)) = 2 Then
        l_dt_final = l_dt_final & Month(l_dt_grade)&"-"
     Else
        l_dt_final = l_dt_final & "0" & Month(l_dt_grade)&"-"
     End If
     If Len(Day(l_dt_grade)) = 2 Then 
        l_dt_final = l_dt_final & Day(l_dt_grade)&"-"
     Else
        l_dt_final = l_dt_final & "0" & Day(l_dt_grade)&"-"
     End If
     If Len(Hour(l_dt_grade)) = 2 Then 
        l_dt_final = l_dt_final & "T"&Hour(l_dt_grade)&":"
     Else
        l_dt_final = l_dt_final & "T0"&Hour(l_dt_grade)&":"
     End If
     If Len(Minute(l_dt_grade)) = 2 Then 
        l_dt_final = l_dt_final & Minute(l_dt_grade)&":"
     Else
        l_dt_final = l_dt_final & "0" & Minute(l_dt_grade)&":"
     End If
     If Len(Second(l_dt_grade)) = 2 Then 
        l_dt_final = l_dt_final & Second(l_dt_grade)
     Else
        l_dt_final = l_dt_final & "0" & Second(l_dt_grade)
     End If
  Else
     l_dt_final = ""
  End If

  FormataDataXML = l_dt_final

  Set l_dt_grade       = Nothing
  Set l_dt_final       = Nothing
End Function
%>