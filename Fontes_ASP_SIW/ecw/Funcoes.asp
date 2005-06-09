<%

REM =========================================================================
REM Verifica sele��o do per�odo letivo e da regional de ensino
REM -------------------------------------------------------------------------
Sub VerificaParametros
    w_ImagemPadrao = "images/folder/SheetLittle.gif"

    If Session("Periodo") = "" or Session("Regional") = "" Then
       ScriptOpen "Javascript"
       ShowHTML "  alert('Voc� deve selecionar o periodo letivo e a regional de ensino desejada!');"
       DB_GetLinkData RS, Session("p_cliente"), "MESA"
       If Not RS.EOF Then
          If RS("IMAGEM") > "" Then
             ShowHTML "location.href='http://" & Request.ServerVariables("server_name") & "/siw/" & RS("LINK") & "&P1="&RS("P1")&"&P2="&RS("P2")&"&P3="&RS("P3")&"&P4="&RS("P4")&"&TP=<img src="&RS("IMAGEM")&" BORDER=0>"&RS("nome")&"&SG="&RS("SIGLA")&"';"
          Else
             ShowHTML "location.href='http://" & Request.ServerVariables("server_name") & "/siw/" & RS("LINK") & "&P1="&RS("P1")&"&P2="&RS("P2")&"&P3="&RS("P3")&"&P4="&RS("P4")&"&TP=<img src="&w_ImagemPadrao&" BORDER=0>"&RS("nome")&"&SG="&RS("SIGLA")&"';"
          End If
       Else
       End If
       ScriptClose
       DesconectaBD
       Response.End()
    End If
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Visualiza��o dos par�metros selecionados
REM -------------------------------------------------------------------------
Sub ExibeParametros(p_cliente)
  Dim RS_temp

  DB_GetUorgList RS_temp, p_cliente, null, null

  If Session("regional") > "" Then
     RS_temp.Filter = "codigo = '" & Session("regional") & "'"
  Else
     RS_temp.Filter = "informal = 'N' and codigo = null"
  End If
  
  ShowHTML "<center><b>Per�odo letivo: [" & Mid(Session("periodo"),1,4) & "] "
  ShowHTML "Regional: [" & RS_temp("nome") & "]</b></center>"

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Visualiza��o dos par�metros selecionados em relat�rios
REM -------------------------------------------------------------------------
Sub ExibeParametrosRel(p_cliente)
  Dim RS_temp

  DB_GetUorgList RS_temp, p_cliente, null, null

  If Session("regional") > "" Then
     RS_temp.Filter = "codigo = '" & Session("regional") & "'"
  Else
     RS_temp.Filter = "informal = 'N' and codigo = null"
  End If
  
  ShowHTML "<TABLE WIDTH=""100%"" BORDER=0>"
  ShowHTML "  <TR><TD><FONT SIZE=2 COLOR=""#000000"">Per�odo letivo: <b>" & Mid(Session("periodo"),1,4) & "</b></TD></TR>"
  ShowHTML "  <TR><TD><FONT SIZE=2 COLOR=""#000000"">Regional de Ensino: <b>" & RS_temp("nome") & "</b></TD></TR>"
  ShowHTML "</TABLE>"

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da sele��o de unidades de ensino
REM -------------------------------------------------------------------------
Sub SelecaoUnidadeEnsino (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetSchoolList RS, w_cliente
    If restricao > "" Then
       RS.Filter = restricao
    End If
    RS.Sort = "ds_escola"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("co_unidade"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("co_unidade") & """ SELECTED>" & RS("ds_escola")
       Else
          ShowHTML "          <option value=""" & RS("co_unidade") & """>" & RS("ds_escola")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da sele��o de tipos de respons�vel
REM -------------------------------------------------------------------------
Sub SelecaoTipoResponsavel (label, accesskey, hint, chave, chaveAux, campo, restricao)
    DB_GetResponKindList RS, w_cliente
    If restricao > "" Then
       RS.Filter = restricao
    End If
    RS.Sort = "ds_tip_responsavel"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(nvl(RS("co_tip_responsavel"),0)) = cDbl(nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("co_tip_responsavel") & """ SELECTED>" & RS("ds_tip_responsavel")
       Else
          ShowHTML "          <option value=""" & RS("co_tip_responsavel") & """>" & RS("ds_tip_responsavel")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da sele��o de tipos de respons�vel
REM -------------------------------------------------------------------------
Sub SelecaoCargo (label, accesskey, hint, chave, chaveAux, campo, restricao)
    DB_GetPositionList RS
    If restricao > "" Then
       RS.Filter = restricao
    End If
    RS.Sort = "ds_cargo"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If RS("co_cargo") = chave Then
          ShowHTML "          <option value=""" & RS("co_cargo") & """ SELECTED>" & RS("ds_cargo")
       Else
          ShowHTML "          <option value=""" & RS("co_cargo") & """>" & RS("ds_cargo")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da sele��o de regional de ensino
REM -------------------------------------------------------------------------
Sub SelecaoRegional (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    DB_GetUorgList RS, w_cliente, null, null
    If Nvl(Session("codigo"),"00") = "00" Then
       If restricao > "" Then
          RS.Filter = restricao & " and codigo <> '00'"
       Else
          RS.Filter = "codigo <> '00'"
       End If
       ShowHTML "          <option value=""00"">Todas"
    Else
       If restricao > "" Then
          RS.Filter = restricao & " and codigo = '" & Session("codigo") & "'"
       Else
          RS.Filter = "codigo = '" & Session("codigo") & "'"
       End If
    End If
    RS.Sort = "nome"
    While Not RS.EOF
       If Nvl(RS("codigo"),"") = Nvl(chave,"") Then
          ShowHTML "          <option value=""" & RS("codigo") & """ SELECTED>" & RS("nome")
       Else
          ShowHTML "          <option value=""" & RS("codigo") & """>" & RS("nome")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da sele��o de per�odo letivo
REM -------------------------------------------------------------------------
Sub SelecaoPeriodoLetivo (label, accesskey, hint, chave, chaveAux, campo, restricao)
    DB_GetPeriodoList RS
    If restricao > "" Then
       RS.Filter = restricao
    End If
    RS.Sort = "periodo desc"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(Nvl(RS("ano_sem"),0)) = cDbl(Nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("ano_sem") & """ SELECTED>" & RS("periodo")
       Else
          ShowHTML "          <option value=""" & RS("ano_sem") & """>" & RS("periodo")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da sele��o de modalidade de ensino
REM -------------------------------------------------------------------------
Sub SelecaoModEnsino (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetCourseTypeList RS
    If restricao > "" Then
       RS.Filter = restricao
    End If
    RS.Sort = "ds_tipo_curso"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(Nvl(RS("co_tipo_curso"),0)) = cDbl(Nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("co_tipo_curso") & """ SELECTED>" & RS("ds_tipo_curso")
       Else
          ShowHTML "          <option value=""" & RS("co_tipo_curso") & """>" & RS("ds_tipo_curso")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da sele��o de turnos
REM -------------------------------------------------------------------------
Sub SelecaoTurno (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetTurnList RS
    If restricao > "" Then
       RS.Filter = restricao
    End If
    RS.Sort = "ds_turno"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If Nvl(RS("co_turno"),"") = Nvl(chave,"") Then
          ShowHTML "          <option value=""" & RS("co_turno") & """ SELECTED>" & RS("ds_turno")
       Else
          ShowHTML "          <option value=""" & RS("co_turno") & """>" & RS("ds_turno")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da sele��o de s�ries
REM -------------------------------------------------------------------------
Sub SelecaoSerie (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetSerieList RS
    If restricao > "" Then
       RS.Filter = restricao
    End If
    RS.Sort = "descr_serie"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If Nvl(RS("sg_serie"),"") = Nvl(chave,"") Then
          ShowHTML "          <option value=""" & RS("sg_serie") & """ SELECTED>" & RS("descr_serie")
       Else
          ShowHTML "          <option value=""" & RS("sg_serie") & """>" & RS("descr_serie")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da sele��o de turmas
REM -------------------------------------------------------------------------
Sub SelecaoTurma (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetTurmaList RS, Session("periodo"), chaveAux
    If restricao > "" Then
       RS.Filter = restricao
    End If
    RS.Sort = "co_letra_turma"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(Nvl(RS("co_turma"),"0")) = cDbl(Nvl(chave,"0")) Then
          ShowHTML "          <option value=""" & RS("co_turma") & """ SELECTED>" & RS("co_letra_turma") & " (" & RS("sg_tipo_curso") & ")"
       Else
          ShowHTML "          <option value=""" & RS("co_turma") & """>" & RS("co_letra_turma") & " (" & RS("sg_tipo_curso") & ")"
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da sele��o de disciplinas
REM -------------------------------------------------------------------------
Sub SelecaoDisciplina (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetDisciplineTypeList RS
    If restricao > "" Then
       RS.Filter = restricao
    End If
    RS.Sort = "ds_tipo_disciplina"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(Nvl(RS("co_tipo_disciplina"),"0")) = cDbl(Nvl(chave,"0")) Then
          ShowHTML "          <option value=""" & RS("co_tipo_disciplina") & """ SELECTED>" & RS("ds_tipo_disciplina")
       Else
          ShowHTML "          <option value=""" & RS("co_tipo_disciplina") & """>" & RS("ds_tipo_disciplina")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da sele��o de origens do aluno
REM -------------------------------------------------------------------------
Sub SelecaoEscolaOrigem (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetSchoolOriginList RS
    If restricao > "" Then
       RS.Filter = restricao
    End If
    RS.Sort = "ds_origem_escola"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(Nvl(RS("co_origem_escola"),"0")) = cDbl(Nvl(chave,"0")) Then
          ShowHTML "          <option value=""" & RS("co_origem_escola") & """ SELECTED>" & RS("ds_origem_escola")
       Else
          ShowHTML "          <option value=""" & RS("co_origem_escola") & """>" & RS("ds_origem_escola")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da sele��o de sexo
REM -------------------------------------------------------------------------
Sub SelecaoSexo (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    If chave = "M" Then
       ShowHTML "          <option value=""F"">Feminino"
       ShowHTML "          <option value=""M"" SELECTED>Masculino"
    ElseIf chave = "F" Then
       ShowHTML "          <option value=""F"" SELECTED>Feminino"
       ShowHTML "          <option value=""M"">Masculino"
    Else
       ShowHTML "          <option value=""F"">Feminino"
       ShowHTML "          <option value=""M"">Masculino"
    End If
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da sele��o de sexo
REM -------------------------------------------------------------------------
Sub SelecaoSitAcademica (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    If chave = "ALUNO NOVO" Then
       ShowHTML "          <option value=""ALUNO NOVO"" SELECTED>Aluno Novo"
       ShowHTML "          <option value=""BIRREPETENTE"">Birrepetente"
       ShowHTML "          <option value=""REPETENTE"">Repetente"
    ElseIf chave = "BIRREPETENTE" Then
       ShowHTML "          <option value=""ALUNO NOVO"">Aluno Novo"
       ShowHTML "          <option value=""BIRREPETENTE"" SELECTED>Birrepetente"
       ShowHTML "          <option value=""REPETENTE"">Repetente"
    ElseIf chave = "REPETENTE" Then
       ShowHTML "          <option value=""ALUNO NOVO"">Aluno Novo"
       ShowHTML "          <option value=""BIRREPETENTE"">Birrepetente"
       ShowHTML "          <option value=""REPETENTE"" SELECTED>Repetente"
    Else
       ShowHTML "          <option value=""ALUNO NOVO"">Aluno Novo"
       ShowHTML "          <option value=""BIRREPETENTE"">Birrepetente"
       ShowHTML "          <option value=""REPETENTE"">Repetente"
    End If
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da sele��o de sexo
REM -------------------------------------------------------------------------
Sub SelecaoMovAluno (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    If chave = "ALUNO ATIVO" Then
       ShowHTML "          <option value=""ALUNO ATIVO"" selected>Aluno ativo"
       ShowHTML "          <option value=""CANCELAMENTO DE MATRICULA"">Cancelamento de matr�cula"
       ShowHTML "          <option value=""DESIST�NCIA DO ALUNO"">Desist�ncia do aluno"
       ShowHTML "          <option value=""TRANSF. UNIDADE ENSINO"">Transf. unidade ensino"
       ShowHTML "          <option value=""TRANSFER�NCIA DE TURMA"">Transfer�ncia de turma"
    ElseIf chave = "CANCELAMENTO DE MATRICULA" Then
       ShowHTML "          <option value=""ALUNO ATIVO"">Aluno ativo"
       ShowHTML "          <option value=""CANCELAMENTO DE MATRICULA"" selected>Cancelamento de matr�cula"
       ShowHTML "          <option value=""DESIST�NCIA DO ALUNO"">Desist�ncia do aluno"
       ShowHTML "          <option value=""TRANSF. UNIDADE ENSINO"">Transf. unidade ensino"
       ShowHTML "          <option value=""TRANSFER�NCIA DE TURMA"">Transfer�ncia de turma"
    ElseIf chave = "DESIST�NCIA DO ALUNO" Then
       ShowHTML "          <option value=""ALUNO ATIVO"">Aluno ativo"
       ShowHTML "          <option value=""CANCELAMENTO DE MATRICULA"">Cancelamento de matr�cula"
       ShowHTML "          <option value=""DESIST�NCIA DO ALUNO"" selected>Desist�ncia do aluno"
       ShowHTML "          <option value=""TRANSF. UNIDADE ENSINO"">Transf. unidade ensino"
       ShowHTML "          <option value=""TRANSFER�NCIA DE TURMA"">Transfer�ncia de turma"
    ElseIf chave = "TRANSF. UNIDADE ENSINO" Then
       ShowHTML "          <option value=""ALUNO ATIVO"">Aluno ativo"
       ShowHTML "          <option value=""CANCELAMENTO DE MATRICULA"">Cancelamento de matr�cula"
       ShowHTML "          <option value=""DESIST�NCIA DO ALUNO"">Desist�ncia do aluno"
       ShowHTML "          <option value=""TRANSF. UNIDADE ENSINO"" selected>Transf. unidade ensino"
       ShowHTML "          <option value=""TRANSFER�NCIA DE TURMA"">Transfer�ncia de turma"
    ElseIf chave = "TRANSFER�NCIA DE TURMA" Then
       ShowHTML "          <option value=""ALUNO ATIVO"">Aluno ativo"
       ShowHTML "          <option value=""CANCELAMENTO DE MATRICULA"">Cancelamento de matr�cula"
       ShowHTML "          <option value=""DESIST�NCIA DO ALUNO"">Desist�ncia do aluno"
       ShowHTML "          <option value=""TRANSF. UNIDADE ENSINO"">Transf. unidade ensino"
       ShowHTML "          <option value=""TRANSFER�NCIA DE TURMA"" selected>Transfer�ncia de turma"
    Else
       ShowHTML "          <option value=""ALUNO ATIVO"">Aluno ativo"
       ShowHTML "          <option value=""CANCELAMENTO DE MATRICULA"">Cancelamento de matr�cula"
       ShowHTML "          <option value=""DESIST�NCIA DO ALUNO"">Desist�ncia do aluno"
       ShowHTML "          <option value=""TRANSF. UNIDADE ENSINO"">Transf. unidade ensino"
       ShowHTML "          <option value=""TRANSFER�NCIA DE TURMA"">Transfer�ncia de turma"
    End If
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da sele��o dos ambientes
REM -------------------------------------------------------------------------
Sub SelecaoAmbiente (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetAmbientList RS
    If restricao > "" Then
       RS.Filter = restricao
    End If
    RS.Sort = "ds_ambiente"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If Nvl(cDbl(RS("co_seq_ambiente")),"") = Nvl(chave,"") Then
          ShowHTML "          <option value=""" & RS("co_seq_ambiente") & """ SELECTED>" & RS("ds_ambiente")
       Else
          ShowHTML "          <option value=""" & RS("co_seq_ambiente") & """>" & RS("ds_ambiente")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da sele��o dos ambientes
REM -------------------------------------------------------------------------
Sub SelecaoTipoSala (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)
    DB_GetRoomTypeList RS
    If restricao > "" Then
       RS.Filter = restricao
    End If
    RS.Sort = "ds_tipo_sala"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If Nvl(cDbl(RS("co_tipo_sala")),"") = Nvl(chave,"") Then
          ShowHTML "          <option value=""" & RS("co_tipo_sala") & """ SELECTED>" & RS("ds_tipo_sala")
       Else
          ShowHTML "          <option value=""" & RS("co_tipo_sala") & """>" & RS("ds_tipo_sala")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da sele��o do calend�rio da unidade
REM -------------------------------------------------------------------------
Sub SelecaoCalendario (label, accesskey, hint, chave, chaveAux, campo, restricao, atributo)   
    DB_GetCalendarList RS, Left(Session("periodo"),4), chaveAux
    If restricao > "" Then
       RS.Filter = restricao
    End If
    RS.Sort = "ds_calendario"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & " " & atributo & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If cDbl(Nvl(RS("co_calendario"),0)) = cDbl(Nvl(chave,0)) Then
          ShowHTML "          <option value=""" & RS("co_calendario") & """ SELECTED>" & RS("ds_calendario")
       Else
          ShowHTML "          <option value=""" & RS("co_calendario") & """>" & RS("ds_calendario")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da sele��o de tipos de �rea de atua��o
REM -------------------------------------------------------------------------
Sub SelecaoAtuacao (label, accesskey, hint, chave, chaveAux, campo, restricao)
    DB_GetAtuationAreaList RS
    If restricao > "" Then
       RS.Filter = restricao
    End If
    RS.Sort = "ds_area_atuacao"
    If IsNull(hint) Then
       ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & ">"
    Else
       ShowHTML "          <td valign=""top"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If chave > "" Then chave = cDbl(chave)
       If chave = cDbl(RS("co_area_atuacao"))Then
          ShowHTML "          <option value=""" & RS("co_area_atuacao") & """ SELECTED>" & RS("ds_area_atuacao")
       Else
          ShowHTML "          <option value=""" & RS("co_area_atuacao") & """>" & RS("ds_area_atuacao")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    DesconectaBD
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da sele��o de tipos de �rea de atua��o
REM -------------------------------------------------------------------------
Sub SelecaoEscolaridade (label, accesskey, hint, chave, chaveAux, campo, restricao)
    ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & ">"
    ShowHTML "          <option value="""">---"
       If "Sem Escolaridade" = chave Then
          ShowHTML "          <option value=""Sem Escolaridade"" SELECTED>Sem Escolaridade"
          ShowHTML "          <option value=""Ensino Fundamental Incompleto"">Ensino Fundamental Incompleto"
          ShowHTML "          <option value=""Ensino Fundamental Completo"">Ensino Fundamental Completo"
          ShowHTML "          <option value=""Ensino M�dio Incompleto"">Ensino M�dio Incompleto"
          ShowHTML "          <option value=""Ensino M�dio Completo"">Ensino M�dio Completo"
          ShowHTML "          <option value=""Ensino Superior Incompleto"">Ensino Superior Incompleto"
          ShowHTML "          <option value=""Ensino Superior Completo"">Ensino Superior Completo"
          ShowHTML "          <option value=""P�s-Gradua��o"">P�s-Gradua��o"
          ShowHTML "          <option value=""Mestrado"">Mestrado"
          ShowHTML "          <option value=""Doutorado"">Doutorado"
       ElseIf chave = "Ensino Fundamental Incompleto" Then
          ShowHTML "          <option value=""Sem Escolaridade"">Sem Escolaridade"
          ShowHTML "          <option value=""Ensino Fundamental Incompleto"" SELECTED>Ensino Fundamental Incompleto"
          ShowHTML "          <option value=""Ensino Fundamental Completo"">Ensino Fundamental Completo"
          ShowHTML "          <option value=""Ensino M�dio Incompleto"">Ensino M�dio Incompleto"
          ShowHTML "          <option value=""Ensino M�dio Completo"">Ensino M�dio Completo"
          ShowHTML "          <option value=""Ensino Superior Incompleto"">Ensino Superior Incompleto"
          ShowHTML "          <option value=""Ensino Superior Completo"">Ensino Superior Completo"
          ShowHTML "          <option value=""P�s-Gradua��o"">P�s-Gradua��o"
          ShowHTML "          <option value=""Mestrado"">Mestrado"
          ShowHTML "          <option value=""Doutorado"">Doutorado"
       ElseIf chave = "Ensino Fundamental Completo" Then
          ShowHTML "          <option value=""Sem Escolaridade"">Sem Escolaridade"
          ShowHTML "          <option value=""Ensino Fundamental Incompleto"">Ensino Fundamental Incompleto"
          ShowHTML "          <option value=""Ensino Fundamental Completo"" SELECTED>Ensino Fundamental Completo"
          ShowHTML "          <option value=""Ensino M�dio Incompleto"">Ensino M�dio Incompleto"
          ShowHTML "          <option value=""Ensino M�dio Completo"">Ensino M�dio Completo"
          ShowHTML "          <option value=""Ensino Superior Incompleto"">Ensino Superior Incompleto"
          ShowHTML "          <option value=""Ensino Superior Completo"">Ensino Superior Completo"
          ShowHTML "          <option value=""P�s-Gradua��o"">P�s-Gradua��o"
          ShowHTML "          <option value=""Mestrado"">Mestrado"
          ShowHTML "          <option value=""Doutorado"">Doutorado"
       ElseIf chave = "Ensino M�dio Incompleto" Then
          ShowHTML "          <option value=""Sem Escolaridade"">Sem Escolaridade"
          ShowHTML "          <option value=""Ensino Fundamental Incompleto"">Ensino Fundamental Incompleto"
          ShowHTML "          <option value=""Ensino Fundamental Completo"">Ensino Fundamental Completo"
          ShowHTML "          <option value=""Ensino M�dio Incompleto"" SELECTED>Ensino M�dio Incompleto"
          ShowHTML "          <option value=""Ensino M�dio Completo"">Ensino M�dio Completo"
          ShowHTML "          <option value=""Ensino Superior Incompleto"">Ensino Superior Incompleto"
          ShowHTML "          <option value=""Ensino Superior Completo"">Ensino Superior Completo"
          ShowHTML "          <option value=""P�s-Gradua��o"">P�s-Gradua��o"
          ShowHTML "          <option value=""Mestrado"">Mestrado"
          ShowHTML "          <option value=""Doutorado"">Doutorado"
       ElseIf chave = "Ensino M�dio Completo" Then
          ShowHTML "          <option value=""Sem Escolaridade"">Sem Escolaridade"
          ShowHTML "          <option value=""Ensino Fundamental Incompleto"">Ensino Fundamental Incompleto"
          ShowHTML "          <option value=""Ensino Fundamental Completo"">Ensino Fundamental Completo"
          ShowHTML "          <option value=""Ensino M�dio Incompleto"">Ensino M�dio Incompleto"
          ShowHTML "          <option value=""Ensino M�dio Completo"" SELECTED>Ensino M�dio Completo"
          ShowHTML "          <option value=""Ensino Superior Incompleto"">Ensino Superior Incompleto"
          ShowHTML "          <option value=""Ensino Superior Completo"">Ensino Superior Completo"
          ShowHTML "          <option value=""P�s-Gradua��o"">P�s-Gradua��o"
          ShowHTML "          <option value=""Mestrado"">Mestrado"
          ShowHTML "          <option value=""Doutorado"">Doutorado"
       ElseIf chave = "Ensino Superior Incompleto" Then
          ShowHTML "          <option value=""Sem Escolaridade"">Sem Escolaridade"
          ShowHTML "          <option value=""Ensino Fundamental Incompleto"">Ensino Fundamental Incompleto"
          ShowHTML "          <option value=""Ensino Fundamental Completo"">Ensino Fundamental Completo"
          ShowHTML "          <option value=""Ensino M�dio Incompleto"">Ensino M�dio Incompleto"
          ShowHTML "          <option value=""Ensino M�dio Completo"">Ensino M�dio Completo"
          ShowHTML "          <option value=""Ensino Superior Incompleto"" SELECTED>Ensino Superior Incompleto"
          ShowHTML "          <option value=""Ensino Superior Completo"">Ensino Superior Completo"
          ShowHTML "          <option value=""P�s-Gradua��o"">P�s-Gradua��o"
          ShowHTML "          <option value=""Mestrado"">Mestrado"
          ShowHTML "          <option value=""Doutorado"">Doutorado"          
       ElseIf chave = "Ensino Superior Completo" Then
          ShowHTML "          <option value=""Sem Escolaridade"">Sem Escolaridade"
          ShowHTML "          <option value=""Ensino Fundamental Incompleto"">Ensino Fundamental Incompleto"
          ShowHTML "          <option value=""Ensino Fundamental Completo"">Ensino Fundamental Completo"
          ShowHTML "          <option value=""Ensino M�dio Incompleto"">Ensino M�dio Incompleto"
          ShowHTML "          <option value=""Ensino M�dio Completo"">Ensino M�dio Completo"
          ShowHTML "          <option value=""Ensino Superior Incompleto"">Ensino Superior Incompleto"
          ShowHTML "          <option value=""Ensino Superior Completo"" SELECTED>Ensino Superior Completo"
          ShowHTML "          <option value=""P�s-Gradua��o"">P�s-Gradua��o"
          ShowHTML "          <option value=""Mestrado"">Mestrado"
          ShowHTML "          <option value=""Doutorado"">Doutorado" 
       ElseIf chave = "P�s-Gradua��o" Then
          ShowHTML "          <option value=""Sem Escolaridade"">Sem Escolaridade"
          ShowHTML "          <option value=""Ensino Fundamental Incompleto"">Ensino Fundamental Incompleto"
          ShowHTML "          <option value=""Ensino Fundamental Completo"">Ensino Fundamental Completo"
          ShowHTML "          <option value=""Ensino M�dio Incompleto"">Ensino M�dio Incompleto"
          ShowHTML "          <option value=""Ensino M�dio Completo"">Ensino M�dio Completo"
          ShowHTML "          <option value=""Ensino Superior Incompleto"">Ensino Superior Incompleto"
          ShowHTML "          <option value=""Ensino Superior Completo"">Ensino Superior Completo"
          ShowHTML "          <option value=""P�s-Gradua��o"" SELECTED>P�s-Gradua��o"
          ShowHTML "          <option value=""Mestrado"">Mestrado"
          ShowHTML "          <option value=""Doutorado"">Doutorado" 
       ElseIf chave = "Mestrado" Then
          ShowHTML "          <option value=""Sem Escolaridade"">Sem Escolaridade"
          ShowHTML "          <option value=""Ensino Fundamental Incompleto"">Ensino Fundamental Incompleto"
          ShowHTML "          <option value=""Ensino Fundamental Completo"">Ensino Fundamental Completo"
          ShowHTML "          <option value=""Ensino M�dio Incompleto"">Ensino M�dio Incompleto"
          ShowHTML "          <option value=""Ensino M�dio Completo"">Ensino M�dio Completo"
          ShowHTML "          <option value=""Ensino Superior Incompleto"">Ensino Superior Incompleto"
          ShowHTML "          <option value=""Ensino Superior Completo"">Ensino Superior Completo"
          ShowHTML "          <option value=""P�s-Gradua��o"">P�s-Gradua��o"
          ShowHTML "          <option value=""Mestrado"" SELECTED>Mestrado"
          ShowHTML "          <option value=""Doutorado"">Doutorado" 
       ElseIf chave = "Doutorado" Then
          ShowHTML "          <option value=""Sem Escolaridade"">Sem Escolaridade"
          ShowHTML "          <option value=""Ensino Fundamental Incompleto"">Ensino Fundamental Incompleto"
          ShowHTML "          <option value=""Ensino Fundamental Completo"">Ensino Fundamental Completo"
          ShowHTML "          <option value=""Ensino M�dio Incompleto"">Ensino M�dio Incompleto"
          ShowHTML "          <option value=""Ensino M�dio Completo"">Ensino M�dio Completo"
          ShowHTML "          <option value=""Ensino Superior Incompleto"">Ensino Superior Incompleto"
          ShowHTML "          <option value=""Ensino Superior Completo"">Ensino Superior Completo"
          ShowHTML "          <option value=""P�s-Gradua��o"">P�s-Gradua��o"
          ShowHTML "          <option value=""Mestrado"">Mestrado"
          ShowHTML "          <option value=""Doutorado"" SELECTED>Doutorado" 
       Else
          ShowHTML "          <option value=""Sem Escolaridade"">Sem Escolaridade"
          ShowHTML "          <option value=""Ensino Fundamental Incompleto"">Ensino Fundamental Incompleto"
          ShowHTML "          <option value=""Ensino Fundamental Completo"">Ensino Fundamental Completo"
          ShowHTML "          <option value=""Ensino M�dio Incompleto"">Ensino M�dio Incompleto"
          ShowHTML "          <option value=""Ensino M�dio Completo"">Ensino M�dio Completo"
          ShowHTML "          <option value=""Ensino Superior Incompleto"">Ensino Superior Incompleto"
          ShowHTML "          <option value=""Ensino Superior Completo"">Ensino Superior Completo"
          ShowHTML "          <option value=""P�s-Gradua��o"">P�s-Gradua��o"
          ShowHTML "          <option value=""Mestrado"">Mestrado"
          ShowHTML "          <option value=""Doutorado"">Doutorado" 
       End If
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da sele��o das vers�es da unidade
REM -------------------------------------------------------------------------
Sub SelecaoVersao (label, accesskey, hint, chave, chaveAux, campo, restricao)
    DB_GetVersionList RS
    If restricao > "" Then
       RS.Filter = restricao
    End If
    RS.Sort = "ds_versao"
    If IsNull(hint) Then
       ShowHTML "          <td align=""left""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & ">"
    Else
       ShowHTML "          <td align=""left"" ONMOUSEOVER=""popup('" & hint & "','white')""; ONMOUSEOUT=""kill()""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & ">"
    End If
    ShowHTML "          <option value="""">---"
    While Not RS.EOF
       If trim(chave) = trim(RS("ds_versao"))Then
          ShowHTML "          <option value=""" & RS("ds_versao") & """ SELECTED>" & RS("ds_versao")
       Else
          ShowHTML "          <option value=""" & RS("ds_versao") & """>" & RS("ds_versao")
       End If
       RS.MoveNext
    Wend
    ShowHTML "          </select>"
    DesconectaBD
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------


%>

