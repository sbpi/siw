<%

REM =========================================================================
REM Verifica seleção do período letivo e da regional de ensino
REM -------------------------------------------------------------------------
Sub VerificaParametros
    w_ImagemPadrao = "images/folder/SheetLittle.gif"

    If Session("Periodo") = "" or Session("Regional") = "" Then
       ScriptOpen "Javascript"
       ShowHTML "  alert('Você deve selecionar o periodo letivo e a regional de ensino desejada!');"
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
REM Visualização dos parâmetros selecionados
REM -------------------------------------------------------------------------
Sub ExibeParametros(p_cliente)
  Dim RS_temp

  DB_GetUorgList RS_temp, p_cliente, null, null

  If Session("regional") > "" Then
     RS_temp.Filter = "codigo = '" & Session("regional") & "'"
  Else
     RS_temp.Filter = "informal = 'N' and codigo = null"
  End If
  
  ShowHTML "<center><b>Período letivo: [" & Mid(Session("periodo"),1,4) & "] "
  ShowHTML "Regional: [" & RS_temp("nome") & "]</b></center>"

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Visualização dos parâmetros selecionados em relatórios
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
  ShowHTML "  <TR><TD><FONT SIZE=2 COLOR=""#000000"">Período letivo: <b>" & Mid(Session("periodo"),1,4) & "</b></TD></TR>"
  ShowHTML "  <TR><TD><FONT SIZE=2 COLOR=""#000000"">Regional de Ensino: <b>" & RS_temp("nome") & "</b></TD></TR>"
  ShowHTML "</TABLE>"

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção de unidades de ensino
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
REM Montagem da seleção de tipos de responsável
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
REM Montagem da seleção de tipos de responsável
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
REM Montagem da seleção de regional de ensino
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
REM Montagem da seleção de período letivo
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
REM Montagem da seleção de modalidade de ensino
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
REM Montagem da seleção de turnos
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
REM Montagem da seleção de séries
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
REM Montagem da seleção de turmas
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
REM Montagem da seleção de disciplinas
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
REM Montagem da seleção de origens do aluno
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
REM Montagem da seleção de sexo
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
REM Montagem da seleção de sexo
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
REM Montagem da seleção de sexo
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
       ShowHTML "          <option value=""CANCELAMENTO DE MATRICULA"">Cancelamento de matrícula"
       ShowHTML "          <option value=""DESISTÊNCIA DO ALUNO"">Desistência do aluno"
       ShowHTML "          <option value=""TRANSF. UNIDADE ENSINO"">Transf. unidade ensino"
       ShowHTML "          <option value=""TRANSFERÊNCIA DE TURMA"">Transferência de turma"
    ElseIf chave = "CANCELAMENTO DE MATRICULA" Then
       ShowHTML "          <option value=""ALUNO ATIVO"">Aluno ativo"
       ShowHTML "          <option value=""CANCELAMENTO DE MATRICULA"" selected>Cancelamento de matrícula"
       ShowHTML "          <option value=""DESISTÊNCIA DO ALUNO"">Desistência do aluno"
       ShowHTML "          <option value=""TRANSF. UNIDADE ENSINO"">Transf. unidade ensino"
       ShowHTML "          <option value=""TRANSFERÊNCIA DE TURMA"">Transferência de turma"
    ElseIf chave = "DESISTÊNCIA DO ALUNO" Then
       ShowHTML "          <option value=""ALUNO ATIVO"">Aluno ativo"
       ShowHTML "          <option value=""CANCELAMENTO DE MATRICULA"">Cancelamento de matrícula"
       ShowHTML "          <option value=""DESISTÊNCIA DO ALUNO"" selected>Desistência do aluno"
       ShowHTML "          <option value=""TRANSF. UNIDADE ENSINO"">Transf. unidade ensino"
       ShowHTML "          <option value=""TRANSFERÊNCIA DE TURMA"">Transferência de turma"
    ElseIf chave = "TRANSF. UNIDADE ENSINO" Then
       ShowHTML "          <option value=""ALUNO ATIVO"">Aluno ativo"
       ShowHTML "          <option value=""CANCELAMENTO DE MATRICULA"">Cancelamento de matrícula"
       ShowHTML "          <option value=""DESISTÊNCIA DO ALUNO"">Desistência do aluno"
       ShowHTML "          <option value=""TRANSF. UNIDADE ENSINO"" selected>Transf. unidade ensino"
       ShowHTML "          <option value=""TRANSFERÊNCIA DE TURMA"">Transferência de turma"
    ElseIf chave = "TRANSFERÊNCIA DE TURMA" Then
       ShowHTML "          <option value=""ALUNO ATIVO"">Aluno ativo"
       ShowHTML "          <option value=""CANCELAMENTO DE MATRICULA"">Cancelamento de matrícula"
       ShowHTML "          <option value=""DESISTÊNCIA DO ALUNO"">Desistência do aluno"
       ShowHTML "          <option value=""TRANSF. UNIDADE ENSINO"">Transf. unidade ensino"
       ShowHTML "          <option value=""TRANSFERÊNCIA DE TURMA"" selected>Transferência de turma"
    Else
       ShowHTML "          <option value=""ALUNO ATIVO"">Aluno ativo"
       ShowHTML "          <option value=""CANCELAMENTO DE MATRICULA"">Cancelamento de matrícula"
       ShowHTML "          <option value=""DESISTÊNCIA DO ALUNO"">Desistência do aluno"
       ShowHTML "          <option value=""TRANSF. UNIDADE ENSINO"">Transf. unidade ensino"
       ShowHTML "          <option value=""TRANSFERÊNCIA DE TURMA"">Transferência de turma"
    End If
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção dos ambientes
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
REM Montagem da seleção dos ambientes
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
REM Montagem da seleção do calendário da unidade
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
REM Montagem da seleção de tipos de área de atuação
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
REM Montagem da seleção de tipos de área de atuação
REM -------------------------------------------------------------------------
Sub SelecaoEscolaridade (label, accesskey, hint, chave, chaveAux, campo, restricao)
    ShowHTML "          <td valign=""top""><font size=""1""><b>" & Label & "</b><br><SELECT ACCESSKEY=""" & accesskey & """ CLASS=""STS"" NAME=""" & campo & """ " & w_Disabled & ">"
    ShowHTML "          <option value="""">---"
       If "Sem Escolaridade" = chave Then
          ShowHTML "          <option value=""Sem Escolaridade"" SELECTED>Sem Escolaridade"
          ShowHTML "          <option value=""Ensino Fundamental Incompleto"">Ensino Fundamental Incompleto"
          ShowHTML "          <option value=""Ensino Fundamental Completo"">Ensino Fundamental Completo"
          ShowHTML "          <option value=""Ensino Médio Incompleto"">Ensino Médio Incompleto"
          ShowHTML "          <option value=""Ensino Médio Completo"">Ensino Médio Completo"
          ShowHTML "          <option value=""Ensino Superior Incompleto"">Ensino Superior Incompleto"
          ShowHTML "          <option value=""Ensino Superior Completo"">Ensino Superior Completo"
          ShowHTML "          <option value=""Pós-Graduação"">Pós-Graduação"
          ShowHTML "          <option value=""Mestrado"">Mestrado"
          ShowHTML "          <option value=""Doutorado"">Doutorado"
       ElseIf chave = "Ensino Fundamental Incompleto" Then
          ShowHTML "          <option value=""Sem Escolaridade"">Sem Escolaridade"
          ShowHTML "          <option value=""Ensino Fundamental Incompleto"" SELECTED>Ensino Fundamental Incompleto"
          ShowHTML "          <option value=""Ensino Fundamental Completo"">Ensino Fundamental Completo"
          ShowHTML "          <option value=""Ensino Médio Incompleto"">Ensino Médio Incompleto"
          ShowHTML "          <option value=""Ensino Médio Completo"">Ensino Médio Completo"
          ShowHTML "          <option value=""Ensino Superior Incompleto"">Ensino Superior Incompleto"
          ShowHTML "          <option value=""Ensino Superior Completo"">Ensino Superior Completo"
          ShowHTML "          <option value=""Pós-Graduação"">Pós-Graduação"
          ShowHTML "          <option value=""Mestrado"">Mestrado"
          ShowHTML "          <option value=""Doutorado"">Doutorado"
       ElseIf chave = "Ensino Fundamental Completo" Then
          ShowHTML "          <option value=""Sem Escolaridade"">Sem Escolaridade"
          ShowHTML "          <option value=""Ensino Fundamental Incompleto"">Ensino Fundamental Incompleto"
          ShowHTML "          <option value=""Ensino Fundamental Completo"" SELECTED>Ensino Fundamental Completo"
          ShowHTML "          <option value=""Ensino Médio Incompleto"">Ensino Médio Incompleto"
          ShowHTML "          <option value=""Ensino Médio Completo"">Ensino Médio Completo"
          ShowHTML "          <option value=""Ensino Superior Incompleto"">Ensino Superior Incompleto"
          ShowHTML "          <option value=""Ensino Superior Completo"">Ensino Superior Completo"
          ShowHTML "          <option value=""Pós-Graduação"">Pós-Graduação"
          ShowHTML "          <option value=""Mestrado"">Mestrado"
          ShowHTML "          <option value=""Doutorado"">Doutorado"
       ElseIf chave = "Ensino Médio Incompleto" Then
          ShowHTML "          <option value=""Sem Escolaridade"">Sem Escolaridade"
          ShowHTML "          <option value=""Ensino Fundamental Incompleto"">Ensino Fundamental Incompleto"
          ShowHTML "          <option value=""Ensino Fundamental Completo"">Ensino Fundamental Completo"
          ShowHTML "          <option value=""Ensino Médio Incompleto"" SELECTED>Ensino Médio Incompleto"
          ShowHTML "          <option value=""Ensino Médio Completo"">Ensino Médio Completo"
          ShowHTML "          <option value=""Ensino Superior Incompleto"">Ensino Superior Incompleto"
          ShowHTML "          <option value=""Ensino Superior Completo"">Ensino Superior Completo"
          ShowHTML "          <option value=""Pós-Graduação"">Pós-Graduação"
          ShowHTML "          <option value=""Mestrado"">Mestrado"
          ShowHTML "          <option value=""Doutorado"">Doutorado"
       ElseIf chave = "Ensino Médio Completo" Then
          ShowHTML "          <option value=""Sem Escolaridade"">Sem Escolaridade"
          ShowHTML "          <option value=""Ensino Fundamental Incompleto"">Ensino Fundamental Incompleto"
          ShowHTML "          <option value=""Ensino Fundamental Completo"">Ensino Fundamental Completo"
          ShowHTML "          <option value=""Ensino Médio Incompleto"">Ensino Médio Incompleto"
          ShowHTML "          <option value=""Ensino Médio Completo"" SELECTED>Ensino Médio Completo"
          ShowHTML "          <option value=""Ensino Superior Incompleto"">Ensino Superior Incompleto"
          ShowHTML "          <option value=""Ensino Superior Completo"">Ensino Superior Completo"
          ShowHTML "          <option value=""Pós-Graduação"">Pós-Graduação"
          ShowHTML "          <option value=""Mestrado"">Mestrado"
          ShowHTML "          <option value=""Doutorado"">Doutorado"
       ElseIf chave = "Ensino Superior Incompleto" Then
          ShowHTML "          <option value=""Sem Escolaridade"">Sem Escolaridade"
          ShowHTML "          <option value=""Ensino Fundamental Incompleto"">Ensino Fundamental Incompleto"
          ShowHTML "          <option value=""Ensino Fundamental Completo"">Ensino Fundamental Completo"
          ShowHTML "          <option value=""Ensino Médio Incompleto"">Ensino Médio Incompleto"
          ShowHTML "          <option value=""Ensino Médio Completo"">Ensino Médio Completo"
          ShowHTML "          <option value=""Ensino Superior Incompleto"" SELECTED>Ensino Superior Incompleto"
          ShowHTML "          <option value=""Ensino Superior Completo"">Ensino Superior Completo"
          ShowHTML "          <option value=""Pós-Graduação"">Pós-Graduação"
          ShowHTML "          <option value=""Mestrado"">Mestrado"
          ShowHTML "          <option value=""Doutorado"">Doutorado"          
       ElseIf chave = "Ensino Superior Completo" Then
          ShowHTML "          <option value=""Sem Escolaridade"">Sem Escolaridade"
          ShowHTML "          <option value=""Ensino Fundamental Incompleto"">Ensino Fundamental Incompleto"
          ShowHTML "          <option value=""Ensino Fundamental Completo"">Ensino Fundamental Completo"
          ShowHTML "          <option value=""Ensino Médio Incompleto"">Ensino Médio Incompleto"
          ShowHTML "          <option value=""Ensino Médio Completo"">Ensino Médio Completo"
          ShowHTML "          <option value=""Ensino Superior Incompleto"">Ensino Superior Incompleto"
          ShowHTML "          <option value=""Ensino Superior Completo"" SELECTED>Ensino Superior Completo"
          ShowHTML "          <option value=""Pós-Graduação"">Pós-Graduação"
          ShowHTML "          <option value=""Mestrado"">Mestrado"
          ShowHTML "          <option value=""Doutorado"">Doutorado" 
       ElseIf chave = "Pós-Graduação" Then
          ShowHTML "          <option value=""Sem Escolaridade"">Sem Escolaridade"
          ShowHTML "          <option value=""Ensino Fundamental Incompleto"">Ensino Fundamental Incompleto"
          ShowHTML "          <option value=""Ensino Fundamental Completo"">Ensino Fundamental Completo"
          ShowHTML "          <option value=""Ensino Médio Incompleto"">Ensino Médio Incompleto"
          ShowHTML "          <option value=""Ensino Médio Completo"">Ensino Médio Completo"
          ShowHTML "          <option value=""Ensino Superior Incompleto"">Ensino Superior Incompleto"
          ShowHTML "          <option value=""Ensino Superior Completo"">Ensino Superior Completo"
          ShowHTML "          <option value=""Pós-Graduação"" SELECTED>Pós-Graduação"
          ShowHTML "          <option value=""Mestrado"">Mestrado"
          ShowHTML "          <option value=""Doutorado"">Doutorado" 
       ElseIf chave = "Mestrado" Then
          ShowHTML "          <option value=""Sem Escolaridade"">Sem Escolaridade"
          ShowHTML "          <option value=""Ensino Fundamental Incompleto"">Ensino Fundamental Incompleto"
          ShowHTML "          <option value=""Ensino Fundamental Completo"">Ensino Fundamental Completo"
          ShowHTML "          <option value=""Ensino Médio Incompleto"">Ensino Médio Incompleto"
          ShowHTML "          <option value=""Ensino Médio Completo"">Ensino Médio Completo"
          ShowHTML "          <option value=""Ensino Superior Incompleto"">Ensino Superior Incompleto"
          ShowHTML "          <option value=""Ensino Superior Completo"">Ensino Superior Completo"
          ShowHTML "          <option value=""Pós-Graduação"">Pós-Graduação"
          ShowHTML "          <option value=""Mestrado"" SELECTED>Mestrado"
          ShowHTML "          <option value=""Doutorado"">Doutorado" 
       ElseIf chave = "Doutorado" Then
          ShowHTML "          <option value=""Sem Escolaridade"">Sem Escolaridade"
          ShowHTML "          <option value=""Ensino Fundamental Incompleto"">Ensino Fundamental Incompleto"
          ShowHTML "          <option value=""Ensino Fundamental Completo"">Ensino Fundamental Completo"
          ShowHTML "          <option value=""Ensino Médio Incompleto"">Ensino Médio Incompleto"
          ShowHTML "          <option value=""Ensino Médio Completo"">Ensino Médio Completo"
          ShowHTML "          <option value=""Ensino Superior Incompleto"">Ensino Superior Incompleto"
          ShowHTML "          <option value=""Ensino Superior Completo"">Ensino Superior Completo"
          ShowHTML "          <option value=""Pós-Graduação"">Pós-Graduação"
          ShowHTML "          <option value=""Mestrado"">Mestrado"
          ShowHTML "          <option value=""Doutorado"" SELECTED>Doutorado" 
       Else
          ShowHTML "          <option value=""Sem Escolaridade"">Sem Escolaridade"
          ShowHTML "          <option value=""Ensino Fundamental Incompleto"">Ensino Fundamental Incompleto"
          ShowHTML "          <option value=""Ensino Fundamental Completo"">Ensino Fundamental Completo"
          ShowHTML "          <option value=""Ensino Médio Incompleto"">Ensino Médio Incompleto"
          ShowHTML "          <option value=""Ensino Médio Completo"">Ensino Médio Completo"
          ShowHTML "          <option value=""Ensino Superior Incompleto"">Ensino Superior Incompleto"
          ShowHTML "          <option value=""Ensino Superior Completo"">Ensino Superior Completo"
          ShowHTML "          <option value=""Pós-Graduação"">Pós-Graduação"
          ShowHTML "          <option value=""Mestrado"">Mestrado"
          ShowHTML "          <option value=""Doutorado"">Doutorado" 
       End If
    ShowHTML "          </select>"
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Montagem da seleção das versões da unidade
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

