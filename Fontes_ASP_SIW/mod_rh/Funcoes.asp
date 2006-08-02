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
    RS.Sort   = "codigo_siape"
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
    RS.Sort   = "sq_grupo_defic, nome"
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
          ShowHTML "          <option value=""" & RS("sq_deficiencia") & """>" & RS("sq_grupo_defic") & " - " & RS("Nome")
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

REM =========================================================================
REM Montagem da seleção dos tipos de postos
REM -------------------------------------------------------------------------
Sub SelecaoTipoPosto2 (label, accesskey, hint, chave, chaveAux, campo, restricao)
    DB_GetTipoPostoList RS, w_cliente, null, null
    RS.Sort = "descricao"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("sq_eo_tipo_posto"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("sq_eo_tipo_posto") & """ SELECTED>" & RS("descricao")
       Else
          ShowHTML "          <option value=""" & RS("sq_eo_tipo_posto") & """>" & RS("descricao")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    RS.Close
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção do tipo da data
REM -------------------------------------------------------------------------
Sub SelecaoTipoData (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    
    Dim w_tipos
    
    w_tipos = ""
    DB_GetDataEspecial RS1, w_cliente, null, null, null, null, null, "VERIFICATIPO"
    If Not RS1.EOF Then
       While Not RS1.EOF
          w_tipos = w_tipos & RS1("tipo") 
          RS1.MoveNext
       Wend
    End If
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    If Nvl(chave,"") = "I" Then
       ShowHTML "          <option value=""I"" SELECTED>Invariável"
    Else
       ShowHTML "          <option value=""I"">Invariável"
    End If
    If Nvl(chave,"") = "E" Then
       ShowHTML "          <option value=""E"" SELECTED>Específica"
    Else
       ShowHTML "          <option value=""E"">Específica"
    End If
    If InStr(w_tipos,"S") = 0 or Nvl(chave,"") = "S" Then
       If Nvl(chave,"") = "S" Then
          ShowHTML "          <option value=""S"" SELECTED>Segunda Carnaval"
       Else
          ShowHTML "          <option value=""S"">Segunda Carnaval"
       End If
    End If
    If InStr(w_tipos,"C") = 0 or Nvl(chave,"") = "C" Then
       If Nvl(chave,"") = "C" Then
          ShowHTML "          <option value=""C"" SELECTED>Terça Carnaval"
       Else
          ShowHTML "          <option value=""C"">Terça Carnaval"
       End If
    End If
    If InStr(w_tipos,"Q") = 0 or Nvl(chave,"") = "Q" Then
       If Nvl(chave,"") = "Q" Then
          ShowHTML "          <option value=""Q"" SELECTED>Quarta Cinzas"
       Else
          ShowHTML "          <option value=""Q"">Quarta Cinzas"
       End If
    End If
    If InStr(w_tipos,"P") = 0 or Nvl(chave,"") = "P" Then
       If Nvl(chave,"") = "P" Then
          ShowHTML "          <option value=""P"" SELECTED>Sexta Santa"
       Else
          ShowHTML "          <option value=""P"">Sexta Santa"
       End If
    End If
    If InStr(w_tipos,"D") = 0 or Nvl(chave,"") = "D" Then
       If Nvl(chave,"") = "D" Then
          ShowHTML "          <option value=""D"" SELECTED>Domingo Páscoa"
       Else
          ShowHTML "          <option value=""D"">Domingo Páscoa"
       End If
    End If
    If InStr(w_tipos,"H") = 0 or Nvl(chave,"") = "H" Then
       If Nvl(chave,"") = "H" Then
          ShowHTML "          <option value=""H"" SELECTED>Corpus Christi"
       Else
          ShowHTML "          <option value=""H"">Corpus Christi"
       End If
    End If
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de abragência da data
REM -------------------------------------------------------------------------
Sub SelecaoAbrangData (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""sts"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    If Nvl(chave,"") = "I" Then
       ShowHTML "          <option value=""I"" SELECTED>Internacional"
    Else
       ShowHTML "          <option value=""I"">Internacional"
    End If
    If Nvl(chave,"") = "N" Then
       ShowHTML "          <option value=""N"" SELECTED>Nacional"
    Else
       ShowHTML "          <option value=""N"">Nacional"
    End If
    If Nvl(chave,"") = "E" Then
       ShowHTML "          <option value=""E"" SELECTED>Estadual"
    Else
       ShowHTML "          <option value=""E"">Estadual"
    End If
    If Nvl(chave,"") = "M" Then
       ShowHTML "          <option value=""M"" SELECTED>Municipal"
    Else
       ShowHTML "          <option value=""M"">Municipal"
    End If
    If Nvl(chave,"") = "O" Then
       ShowHTML "          <option value=""O"" SELECTED>Organização"
    Else
       ShowHTML "          <option value=""O"">Organização"
    End If
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção dos tipos de afastamentos
REM -------------------------------------------------------------------------
Sub SelecaoTipoAfastamento (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    If restricao = "AFASTAMENTO" Then
       DB_GetGPTipoAfast RS, w_cliente, null, null, "S", null, null, restricao
       RS.Sort = "nome"
    Else
       DB_GetGPTipoAfast RS, w_cliente, null, null, null, null, null, null
       RS.Sort = "nome"
    End if
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
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção dos colaboradores
REM -------------------------------------------------------------------------
Sub SelecaoColaborador (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetGPColaborador RS, w_cliente, null, null, null, null, null, null, null, null, null, null, null, null, null, chaveAux, restricao
    RS.Sort = "nome_resumido"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" title=""" & hint & """><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("sq_contrato_colaborador"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("sq_contrato_colaborador") & """ SELECTED>" & RS("nome_resumido")
       Else
          ShowHTML "          <option value=""" & RS("sq_contrato_colaborador") & """>" & RS("nome_resumido")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    
    ShowHTML "              <a class=""ss"" href=""#"" onClick=""window.open('Afastamento.asp?par=BuscaColaborador&TP=" & RemoveTP(TP) & "&w_cliente=" &w_cliente& "&chaveAux=" &chaveAux& "&w_menu=" &w_menu& "&restricao=" &restricao& "&campo=" &campo& "','Colaborador','top=10,left=10,width=780,height=550,toolbar=yes,status=yes,resizable=yes,scrollbars=yes'); return false;"" title=""Clique aqui para selecionar o colaborador.""><img src=images/Folder/Explorer.gif border=0 align=top height=15 width=15></a>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da URL com os dados de um colaborador
REM -------------------------------------------------------------------------
Function ExibeColaborador (p_dir, p_cliente, p_pessoa, p_tp, p_nome)
  Dim l_string
  If Nvl(p_nome,"") = "" Then
     l_string="---"
  Else
     l_string = l_string & "<A class=""hl"" HREF=""#"" onClick=""window.open('" & p_dir & "Afastamento.asp?par=TELACOLABORADOR&w_cliente=" & p_cliente & "&w_sq_pessoa=" & p_pessoa & "&P1=" & P1 & "&P2=" & P2 & "&P3=" & P3 & "&P4=" & P4 & "&TP=" & p_TP & "&SG="& SG & "','Colaborador','width=780,height=300,top=10,left=10,toolbar=no,scrollbars=yes,resizable=yes,status=no'); return false;"" title=""Clique para exibir os dados deste colaborador!"">" & p_nome & "</A>"
  End If
  ExibeColaborador = l_string
  
  Set l_string = Nothing
End Function
REM =========================================================================
REM Final da função
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de modalidades de contrato
REM -------------------------------------------------------------------------
Sub SelecaoModalidade (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetGPModalidade RS, w_cliente, null, null, null, "S", null, restricao
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
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção dos cargos
REM -------------------------------------------------------------------------
Sub SelecaoCargo (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetCargo RS, w_cliente, null, null, null, null, "S", restricao
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
REM Final da rotina
REM -------------------------------------------------------------------------
%>

