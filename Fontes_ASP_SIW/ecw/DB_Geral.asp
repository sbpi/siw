<%
REM =========================================================================
REM Recupera os períodos disponíveis
REM -------------------------------------------------------------------------
Sub DB_GetPeriodoList(p_rs)

  with sp
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then 
        .CommandText             = "ecw.ecw.SP_GetPeriodoList"
     Else
        .CommandText             = "ecw.SP_GetPeriodoList"
     End If
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os alunos existentes
REM -------------------------------------------------------------------------
Sub DB_GetAlunoList(p_rs, p_periodo, p_regional, p_aluno, p_responsavel, p_pai, p_mae, p_matricula, p_unidade, p_cpf, p_tipo_resp)
  Dim l_periodo, l_regional, l_aluno, l_responsavel, l_pai, l_mae, l_matricula, l_unidade, l_cpf, l_tipo_resp
  Set l_periodo      = Server.CreateObject("ADODB.Parameter")
  Set l_regional     = Server.CreateObject("ADODB.Parameter")
  Set l_aluno        = Server.CreateObject("ADODB.Parameter")
  Set l_responsavel  = Server.CreateObject("ADODB.Parameter")
  Set l_pai          = Server.CreateObject("ADODB.Parameter")
  Set l_mae          = Server.CreateObject("ADODB.Parameter")
  Set l_matricula    = Server.CreateObject("ADODB.Parameter")
  Set l_unidade      = Server.CreateObject("ADODB.Parameter")
  Set l_cpf          = Server.CreateObject("ADODB.Parameter")
  Set l_tipo_resp    = Server.CreateObject("ADODB.Parameter")

  with sp
     set l_periodo              = .CreateParameter("l_periodo",     adInteger, adParamInput,  , p_periodo)
     set l_regional             = .CreateParameter("l_regional",    adVarchar, adParamInput, 2, tvl(p_regional))
     set l_aluno                = .CreateParameter("l_aluno",       adVarchar, adParamInput,42, tvl(p_aluno))
     set l_responsavel          = .CreateParameter("l_responsavel", adVarchar, adParamInput,42, tvl(p_responsavel))
     set l_pai                  = .CreateParameter("l_pai",         adVarchar, adParamInput,42, tvl(p_pai))
     set l_mae                  = .CreateParameter("l_mae",         adVarchar, adParamInput,42, tvl(p_mae))
     set l_matricula            = .CreateParameter("l_matricula",   adVarchar, adParamInput,12, tvl(p_matricula))
     set l_unidade              = .CreateParameter("l_unidade",     adInteger, adParamInput,  , tvl(p_unidade))
     set l_cpf                  = .CreateParameter("l_cpf",         adChar,    adParamInput,14, tvl(p_cpf))
     set l_tipo_resp            = .CreateParameter("l_tipo_resp",   adInteger, adParamInput,  , tvl(p_tipo_resp))
     .parameters.Append         l_periodo
     .parameters.Append         l_regional
     .parameters.Append         l_aluno
     .parameters.Append         l_responsavel
     .parameters.Append         l_pai
     .parameters.Append         l_mae
     .parameters.Append         l_matricula
     .parameters.Append         l_unidade
     .parameters.Append         l_cpf
     .parameters.Append         l_tipo_resp
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then 
        .CommandText               = "ecw.ecw.SP_GetStudentList"
     Else
        .CommandText               = "ecw.SP_GetStudentList"
     End If
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_periodo"
     .Parameters.Delete         "l_regional"
     .Parameters.Delete         "l_aluno"
     .Parameters.Delete         "l_responsavel"
     .Parameters.Delete         "l_pai"
     .Parameters.Delete         "l_mae"
     .Parameters.Delete         "l_matricula"
     .Parameters.Delete         "l_unidade"
     .Parameters.Delete         "l_cpf"
     .Parameters.Delete         "l_tipo_resp"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os alunos existentes
REM -------------------------------------------------------------------------
Sub DB_GetFuncList(p_rs, p_periodo, p_regional, p_cpf, p_cargo, p_matricula, p_unidade, p_funcionario, p_prof, p_canc)
  Dim l_periodo, l_regional, l_cpf, l_cargo, l_matricula, l_unidade, l_funcionario, l_prof, l_canc
  Set l_periodo      = Server.CreateObject("ADODB.Parameter")
  Set l_regional     = Server.CreateObject("ADODB.Parameter")
  Set l_cpf          = Server.CreateObject("ADODB.Parameter")
  Set l_cargo        = Server.CreateObject("ADODB.Parameter")
  Set l_matricula    = Server.CreateObject("ADODB.Parameter")
  Set l_unidade      = Server.CreateObject("ADODB.Parameter")
  Set l_funcionario  = Server.CreateObject("ADODB.Parameter")
  Set l_prof         = Server.CreateObject("ADODB.Parameter")
  Set l_canc         = Server.CreateObject("ADODB.Parameter")

  with sp
     set l_periodo              = .CreateParameter("l_periodo",     adInteger, adParamInput,  , p_periodo)
     set l_regional             = .CreateParameter("l_regional",    adVarchar, adParamInput, 2, tvl(p_regional))
     set l_cpf                  = .CreateParameter("l_cpf",         adVarchar, adParamInput,14, tvl(p_cpf))
     set l_cargo                = .CreateParameter("l_cargo",       adVarchar, adParamInput,17, tvl(p_cargo))
     set l_matricula            = .CreateParameter("l_matricula",   adVarchar, adParamInput, 8, tvl(p_matricula))
     set l_unidade              = .CreateParameter("l_unidade",     adInteger, adParamInput,  , tvl(p_unidade))
     set l_funcionario          = .CreateParameter("l_funcionario", adVarchar, adParamInput,42, tvl(p_funcionario))
     set l_prof                 = .CreateParameter("l_prof",        adVarchar, adParamInput, 1, tvl(p_prof))
     set l_canc                 = .CreateParameter("l_canc",        adVarchar, adParamInput, 1, tvl(p_canc))
     .parameters.Append         l_periodo
     .parameters.Append         l_regional
     .parameters.Append         l_cpf
     .parameters.Append         l_cargo
     .parameters.Append         l_matricula
     .parameters.Append         l_unidade
     .parameters.Append         l_funcionario
     .parameters.Append         l_prof
     .parameters.Append         l_canc
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then 
        .CommandText               = "ecw.ecw.SP_GetFuncList"
     Else
        .CommandText               = "ecw.SP_GetFuncList"
     End If
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_periodo"
     .Parameters.Delete         "l_regional"
     .Parameters.Delete         "l_cpf"
     .Parameters.Delete         "l_cargo"
     .Parameters.Delete         "l_matricula"
     .Parameters.Delete         "l_unidade"
     .Parameters.Delete         "l_funcionario"
     .Parameters.Delete         "l_prof"
     .Parameters.Delete         "l_canc"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os dados de um aluno
REM -------------------------------------------------------------------------
Sub DB_GetAlunoData(p_rs, p_periodo, p_matricula, p_dados)
  Dim l_periodo, l_matricula, l_dados
  Set l_periodo      = Server.CreateObject("ADODB.Parameter")
  Set l_matricula    = Server.CreateObject("ADODB.Parameter")
  Set l_dados        = Server.CreateObject("ADODB.Parameter")

  with sp
     set l_periodo              = .CreateParameter("l_periodo",   adInteger, adParamInput,   , p_periodo)
     set l_matricula            = .CreateParameter("l_matricula", adchar,    adParamInput, 12, tvl(p_matricula))
     set l_dados                = .CreateParameter("l_dados",     adVarchar, adParamInput, 12, p_dados)
     .parameters.Append         l_periodo
     .parameters.Append         l_matricula
     .parameters.Append         l_dados
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then 
        .CommandText               = "ecw.ecw.SP_GetStudentData"
     Else
        .CommandText               = "ecw.SP_GetStudentData"
     End If
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_periodo"
     .Parameters.Delete         "l_matricula"
     .Parameters.Delete         "l_dados"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os dados de um responsável
REM -------------------------------------------------------------------------
Sub DB_GetResponsData(p_rs, p_periodo, p_responsavel)
  Dim l_periodo, l_responsavel
  Set l_periodo      = Server.CreateObject("ADODB.Parameter")
  Set l_responsavel  = Server.CreateObject("ADODB.Parameter")

  with sp
     set l_periodo              = .CreateParameter("l_periodo",     adInteger, adParamInput,   , p_periodo)
     set l_responsavel          = .CreateParameter("l_responsavel", adVarchar, adParamInput, 20, tvl(p_responsavel))
     .parameters.Append         l_periodo
     .parameters.Append         l_responsavel
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then 
        .CommandText               = "ecw.ecw.SP_GetResponsData"
     Else
        .CommandText               = "ecw.SP_GetResponsData"
     End If
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_periodo"
     .Parameters.Delete         "l_responsavel"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os dados de um servidor
REM -------------------------------------------------------------------------
Sub DB_GetFuncData(p_rs, p_periodo, p_codigo, p_dados)
  Dim l_periodo, l_codigo, l_dados
  Set l_periodo      = Server.CreateObject("ADODB.Parameter")
  Set l_codigo       = Server.CreateObject("ADODB.Parameter")
  Set l_dados        = Server.CreateObject("ADODB.Parameter")

  with sp
     set l_periodo              = .CreateParameter("l_periodo",   adInteger, adParamInput,   , p_periodo)
     set l_codigo               = .CreateParameter("l_codigo",    adchar,    adParamInput, 10, tvl(p_codigo))
     set l_dados                = .CreateParameter("l_dados",     adVarchar, adParamInput, 12, p_dados)
     .parameters.Append         l_periodo
     .parameters.Append         l_codigo
     .parameters.Append         l_dados
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then 
        .CommandText               = "ecw.ecw.SP_GetFuncData"
     Else
        .CommandText               = "ecw.SP_GetFuncData"
     End If
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_periodo"
     .Parameters.Delete         "l_codigo"
     .Parameters.Delete         "l_dados"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera as unidades de ensino existentes
REM -------------------------------------------------------------------------
Sub DB_GetSchoolList(p_rs, p_cliente)
  Dim l_cliente, l_periodo, l_regional

  with sp
     set l_cliente              = .CreateParameter("l_cliente",  adInteger, adParamInput,  , p_cliente)
     .parameters.Append         l_cliente
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then 
        .CommandText               = "ecw.ecw.SP_GetSchoolList"
     Else
        .CommandText               = "ecw.SP_GetSchoolList"
     End If
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os tipos válidos de responsável pelo aluno
REM -------------------------------------------------------------------------
Sub DB_GetResponKindList(p_rs, p_cliente)
  Dim l_cliente, l_periodo, l_regional

  with sp
     set l_cliente              = .CreateParameter("l_cliente",  adInteger, adParamInput,  , p_cliente)
     .parameters.Append         l_cliente
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then 
        .CommandText               = "ecw.ecw.SP_GetRespKindList"
     Else
        .CommandText               = "ecw.SP_GetRespKindList"
     End If
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera as turmas existentes
REM -------------------------------------------------------------------------
Sub DB_GetTurmaList(p_rs, p_periodo, p_unidade)
  Dim l_periodo, l_unidade
  Set l_periodo      = Server.CreateObject("ADODB.Parameter")
  Set l_unidade      = Server.CreateObject("ADODB.Parameter")

  with sp
     set l_periodo              = .CreateParameter("l_periodo",  adInteger, adParamInput,  , p_periodo)
     set l_unidade              = .CreateParameter("l_unidade", adVarchar, adParamInput, 5, tvl(p_unidade))
     .parameters.Append         l_periodo
     .parameters.Append         l_unidade
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then 
        .CommandText               = "ecw.ecw.SP_GetTurmaList"
     Else
        .CommandText               = "ecw.SP_GetTurmaList"
     End If
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_periodo"
     .Parameters.Delete         "l_unidade"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os calendário da unidade selecionada
REM -------------------------------------------------------------------------
Sub DB_GetCalendarList(p_rs, p_periodo, p_unidade)
  Dim l_periodo, l_unidade
  Set l_periodo      = Server.CreateObject("ADODB.Parameter")
  Set l_unidade      = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_periodo              = .CreateParameter("l_periodo",  adVarchar, adParamInput, 4, tvl(p_periodo))
     set l_unidade              = .CreateParameter("l_unidade",  adVarChar, adParamInput, 5, tvl(p_unidade))
     .parameters.Append         l_periodo
     .parameters.Append         l_unidade
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then 
        .CommandText               = "ecw.ecw.SP_GetCalendarList"
     Else
        .CommandText               = "ecw.SP_GetCalendarList"
     End If
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_periodo"
     .Parameters.Delete         "l_unidade"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os períodos disponíveis
REM -------------------------------------------------------------------------
Sub DB_GetVersionList(p_rs)

  with sp
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then 
        .CommandText               = "ecw.ecw.SP_GetVersionList"
     Else
        .CommandText               = "ecw.SP_GetVersionList"
     End If
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

%>

