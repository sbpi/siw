<%
REM =========================================================================
REM Recupera os alunos duplicados
REM -------------------------------------------------------------------------
Sub DB_GetDoubStudList(p_rs, p_periodo, p_regional, p_tipo, p_unidade)
  Dim l_periodo, l_regional, l_tipo, l_unidade
  Set l_periodo      = Server.CreateObject("ADODB.Parameter")
  Set l_regional     = Server.CreateObject("ADODB.Parameter")
  Set l_tipo         = Server.CreateObject("ADODB.Parameter")
  Set l_unidade      = Server.CreateObject("ADODB.Parameter")

  with sp
     set l_periodo              = .CreateParameter("l_periodo",  adInteger, adParamInput,  , p_periodo)
     set l_regional             = .CreateParameter("l_regional", adVarchar, adParamInput, 2, tvl(p_regional))
     set l_tipo                 = .CreateParameter("l_tipo",     adVarchar, adParamInput, 9, tvl(p_tipo))
     set l_unidade              = .CreateParameter("l_unidade",  adInteger, adParamInput,  , tvl(p_unidade))
     .parameters.Append         l_periodo
     .parameters.Append         l_regional
     .parameters.Append         l_tipo
     .parameters.Append         l_unidade
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_GetDoubStudList"
     Else
        .CommandText               = "ecw.SP_GetDoubStudList"
     End If
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_periodo"
     .Parameters.Delete         "l_regional"
     .Parameters.Delete         "l_tipo"
     .Parameters.Delete         "l_unidade"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os alunos duplicados a partir do nome do aluno, nome da mãe e data de nascimento
REM -------------------------------------------------------------------------
Sub DB_GetDoubleStudData(p_rs, p_periodo, p_aluno, p_mae, p_nascimento)
  Dim l_periodo, l_mae, l_aluno, l_nascimento
  Set l_periodo      = Server.CreateObject("ADODB.Parameter")
  Set l_aluno        = Server.CreateObject("ADODB.Parameter")
  Set l_mae          = Server.CreateObject("ADODB.Parameter")
  Set l_nascimento   = Server.CreateObject("ADODB.Parameter")

  with sp
     set l_periodo              = .CreateParameter("l_periodo",    adInteger, adParamInput,  , p_periodo)
     set l_aluno                = .CreateParameter("l_aluno",      adVarchar, adParamInput, 40, tvl(p_aluno))
     set l_mae                  = .CreateParameter("l_mae",        adVarchar, adParamInput, 40, tvl(p_mae))
     set l_nascimento           = .CreateParameter("l_nascimento", adDate,    adParamInput,   , tvl(p_nascimento))
     .parameters.Append         l_periodo
     .parameters.Append         l_aluno
     .parameters.Append         l_mae
     .parameters.Append         l_nascimento
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_GetDoubStudData"
     Else
        .CommandText               = "ecw.SP_GetDoubStudData"
     End If
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_periodo"
     .Parameters.Delete         "l_aluno"
     .Parameters.Delete         "l_mae"
     .Parameters.Delete         "l_nascimento"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os alunos existentes conforme layout do relatório de alunos
REM -------------------------------------------------------------------------
Sub DB_GetStudentRel(p_rs, p_periodo, p_regional, p_materia, _
   p_aluno, p_matricula, p_unidade, p_serie, p_turma, p_modalidade, _
   p_turno, p_origem, p_situacao, p_movimentacao, p_sexo, _
   p_faixa_i, p_faixa_f, p_mat_i, p_mat_f, p_nasc_i, p_nasc_f)
  Dim l_periodo, l_regional, l_materia, l_aluno, l_matricula, l_unidade, l_serie, l_turma, l_modalidade
  Dim l_turno, l_origem, l_situacao, l_movimentacao, l_sexo, l_faixa_i, l_faixa_f, l_mat_i, l_mat_f, l_nasc_i, l_nasc_f
  Set l_periodo      = Server.CreateObject("ADODB.Parameter")
  Set l_regional     = Server.CreateObject("ADODB.Parameter")
  Set l_materia      = Server.CreateObject("ADODB.Parameter")
  Set l_aluno        = Server.CreateObject("ADODB.Parameter")
  Set l_matricula    = Server.CreateObject("ADODB.Parameter")
  Set l_unidade      = Server.CreateObject("ADODB.Parameter")
  Set l_serie        = Server.CreateObject("ADODB.Parameter")
  Set l_turma        = Server.CreateObject("ADODB.Parameter")
  Set l_modalidade   = Server.CreateObject("ADODB.Parameter")
  Set l_turno        = Server.CreateObject("ADODB.Parameter")
  Set l_origem       = Server.CreateObject("ADODB.Parameter")
  Set l_situacao     = Server.CreateObject("ADODB.Parameter")
  Set l_movimentacao = Server.CreateObject("ADODB.Parameter")
  Set l_sexo         = Server.CreateObject("ADODB.Parameter")
  Set l_faixa_i      = Server.CreateObject("ADODB.Parameter")
  Set l_faixa_f      = Server.CreateObject("ADODB.Parameter")
  Set l_mat_i        = Server.CreateObject("ADODB.Parameter")
  Set l_mat_f        = Server.CreateObject("ADODB.Parameter")
  Set l_nasc_i       = Server.CreateObject("ADODB.Parameter")
  Set l_nasc_f       = Server.CreateObject("ADODB.Parameter")

  with sp
     set l_periodo              = .CreateParameter("l_periodo",     adInteger, adParamInput,   , p_periodo)
     set l_regional             = .CreateParameter("l_regional",    adVarchar, adParamInput,  2, tvl(p_regional))
     set l_materia              = .CreateParameter("l_materia",     adInteger, adParamInput,   , tvl(p_materia))
     set l_aluno                = .CreateParameter("l_aluno",       adVarchar, adParamInput, 42, tvl(p_aluno))
     set l_matricula            = .CreateParameter("l_matricula",   adVarchar, adParamInput, 12, tvl(p_matricula))
     set l_unidade              = .CreateParameter("l_unidade",     adInteger, adParamInput,   , tvl(p_unidade))
     set l_turma                = .CreateParameter("l_turma",       adInteger, adParamInput,   , tvl(p_turma))
     set l_serie                = .CreateParameter("l_serie",       adChar,    adParamInput,  5, tvl(p_serie))
     set l_modalidade           = .CreateParameter("l_modalidade",  adInteger, adParamInput,   , tvl(p_modalidade))
     set l_turno                = .CreateParameter("l_turno",       adChar,    adParamInput,  2, tvl(p_turno))
     set l_origem               = .CreateParameter("l_origem",      adInteger, adParamInput,   , tvl(p_origem))
     set l_situacao             = .CreateParameter("l_situacao",    adChar,    adParamInput, 25, tvl(p_situacao))
     set l_movimentacao         = .CreateParameter("l_movimentacao",adChar,    adParamInput,245, tvl(p_movimentacao))
     set l_sexo                 = .CreateParameter("l_sexo",        adChar,    adParamInput,  1, tvl(p_sexo))
     set l_faixa_i              = .CreateParameter("l_faixa_i",     adInteger, adParamInput,   , tvl(p_faixa_i))
     set l_faixa_f              = .CreateParameter("l_faixa_f",     adInteger, adParamInput,   , tvl(p_faixa_f))
     set l_mat_i                = .CreateParameter("l_mat_i",       adDate,    adParamInput,   , tvl(p_mat_i))
     set l_mat_f                = .CreateParameter("l_mat_f",       adDate,    adParamInput,   , tvl(p_mat_f))
     set l_nasc_i               = .CreateParameter("l_nasc_i",      adDate,    adParamInput,   , tvl(p_nasc_i))
     set l_nasc_f               = .CreateParameter("l_nasc_f",      adDate,    adParamInput,   , tvl(p_nasc_f))
     .parameters.Append         l_periodo
     .parameters.Append         l_regional
     .parameters.Append         l_materia
     .parameters.Append         l_aluno
     .parameters.Append         l_matricula
     .parameters.Append         l_unidade
     .parameters.Append         l_turma
     .parameters.Append         l_serie
     .parameters.Append         l_modalidade
     .parameters.Append         l_turno
     .parameters.Append         l_origem
     .parameters.Append         l_situacao
     .parameters.Append         l_movimentacao
     .parameters.Append         l_sexo
     .parameters.Append         l_faixa_i
     .parameters.Append         l_faixa_f
     .parameters.Append         l_mat_i
     .parameters.Append         l_mat_f
     .parameters.Append         l_nasc_i
     .parameters.Append         l_nasc_f
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_GetStudentRel"
     Else
        .CommandText               = "ecw.SP_GetStudentRel"
     End If
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_periodo"
     .Parameters.Delete         "l_regional"
     .Parameters.Delete         "l_materia"
     .Parameters.Delete         "l_aluno"
     .Parameters.Delete         "l_matricula"
     .Parameters.Delete         "l_unidade"
     .Parameters.Delete         "l_turma"
     .Parameters.Delete         "l_serie"
     .Parameters.Delete         "l_modalidade"
     .Parameters.Delete         "l_turno"
     .Parameters.Delete         "l_origem"
     .Parameters.Delete         "l_situacao"
     .Parameters.Delete         "l_movimentacao"
     .Parameters.Delete         "l_sexo"
     .Parameters.Delete         "l_faixa_i"
     .Parameters.Delete         "l_faixa_f"
     .Parameters.Delete         "l_mat_i"
     .Parameters.Delete         "l_mat_f"
     .Parameters.Delete         "l_nasc_i"
     .Parameters.Delete         "l_nasc_f"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera a lista de turmas
REM -------------------------------------------------------------------------
Sub DB_GetRoomClassList(p_rs, p_periodo, p_regional, p_unidade, p_modalidade, p_turno, p_serie, p_turma, p_ambiente, p_tipo_sala)
  Dim l_periodo, l_regional, l_unidade, l_modalidade, l_turno, l_serie, l_turma, l_ambiente, l_tipo_sala
  Set l_periodo      = Server.CreateObject("ADODB.Parameter")
  Set l_regional     = Server.CreateObject("ADODB.Parameter")
  Set l_unidade      = Server.CreateObject("ADODB.Parameter")
  Set l_modalidade   = Server.CreateObject("ADODB.Parameter")
  Set l_turno        = Server.CreateObject("ADODB.Parameter")
  Set l_serie        = Server.CreateObject("ADODB.Parameter")
  Set l_turma        = Server.CreateObject("ADODB.Parameter")
  Set l_ambiente     = Server.CreateObject("ADODB.Parameter")
  Set l_tipo_sala    = Server.CreateObject("ADODB.Parameter")

  with sp
     set l_periodo              = .CreateParameter("l_periodo",    adInteger, adParamInput,  , p_periodo)
     set l_regional             = .CreateParameter("l_regional",   adVarchar, adParamInput, 2, tvl(p_regional))
     set l_unidade              = .CreateParameter("l_unidade",    adInteger, adParamInput,  , tvl(p_unidade))
     set l_modalidade           = .CreateParameter("l_modalidade", adInteger, adParamInput,  , tvl(p_modalidade))
     set l_turno                = .CreateParameter("l_turno",      adVarchar, adParamInput, 2, tvl(p_turno))
     set l_serie                = .CreateParameter("l_serie",      adVarchar, adParamInput, 5, tvl(p_serie))
     set l_turma                = .CreateParameter("l_turma",      adInteger, adParamInput,  , tvl(p_turma))
     set l_ambiente             = .CreateParameter("l_ambiente",   adInteger, adParamInput,  , tvl(p_ambiente))
     set l_tipo_sala            = .CreateParameter("l_tipo_sala",  adInteger, adParamInput,  , tvl(p_tipo_sala))
     .parameters.Append         l_periodo
     .parameters.Append         l_regional
     .parameters.Append         l_unidade
     .parameters.Append         l_modalidade
     .parameters.Append         l_turno
     .parameters.Append         l_serie
     .parameters.Append         l_turma
     .parameters.Append         l_ambiente
     .parameters.Append         l_tipo_sala
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_GetRoomClList"
     Else
        .CommandText               = "ecw.SP_GetRoomClList"
     End If
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_periodo"
     .Parameters.Delete         "l_regional"
     .Parameters.Delete         "l_unidade"
     .Parameters.Delete         "l_modalidade"
     .Parameters.Delete         "l_turno"
     .Parameters.Delete         "l_serie"
     .Parameters.Delete         "l_turma"
     .Parameters.Delete         "l_ambiente"
     .Parameters.Delete         "l_tipo_sala"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera a lista de salas e turmas
REM -------------------------------------------------------------------------
Sub DB_GetRoomList(p_rs, p_periodo, p_regional, p_unidade, p_modalidade, p_turno, p_serie, p_turma, p_ambiente, p_tipo_sala)
  Dim l_periodo, l_regional, l_unidade, l_modalidade, l_turno, l_serie, l_turma, l_ambiente, l_tipo_sala
  Set l_periodo      = Server.CreateObject("ADODB.Parameter")
  Set l_regional     = Server.CreateObject("ADODB.Parameter")
  Set l_unidade      = Server.CreateObject("ADODB.Parameter")
  Set l_modalidade   = Server.CreateObject("ADODB.Parameter")
  Set l_turno        = Server.CreateObject("ADODB.Parameter")
  Set l_serie        = Server.CreateObject("ADODB.Parameter")
  Set l_turma        = Server.CreateObject("ADODB.Parameter")
  Set l_ambiente     = Server.CreateObject("ADODB.Parameter")
  Set l_tipo_sala    = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_periodo              = .CreateParameter("l_periodo",    adInteger, adParamInput,  , p_periodo)
     set l_regional             = .CreateParameter("l_regional",   adVarchar, adParamInput, 2, tvl(p_regional))
     set l_unidade              = .CreateParameter("l_unidade",    adInteger, adParamInput,  , tvl(p_unidade))
     set l_modalidade           = .CreateParameter("l_modalidade", adInteger, adParamInput,  , tvl(p_modalidade))
     set l_turno                = .CreateParameter("l_turno",      adVarchar, adParamInput, 2, tvl(p_turno))
     set l_serie                = .CreateParameter("l_serie",      adVarchar, adParamInput, 5, tvl(p_serie))
     set l_turma                = .CreateParameter("l_turma",      adInteger, adParamInput,  , tvl(p_turma))
     set l_ambiente             = .CreateParameter("l_ambiente",   adInteger, adParamInput,  , tvl(p_ambiente))
     set l_tipo_sala            = .CreateParameter("l_tipo_sala",  adInteger, adParamInput,  , tvl(p_tipo_sala))
     .parameters.Append         l_periodo
     .parameters.Append         l_regional
     .parameters.Append         l_unidade
     .parameters.Append         l_modalidade
     .parameters.Append         l_turno
     .parameters.Append         l_serie
     .parameters.Append         l_turma
     .parameters.Append         l_ambiente
     .parameters.Append         l_tipo_sala
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_GetRoomList"
     Else
        .CommandText               = "ecw.SP_GetRoomList"
     End If
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_periodo"
     .Parameters.Delete         "l_regional"
     .Parameters.Delete         "l_unidade"
     .Parameters.Delete         "l_modalidade"
     .Parameters.Delete         "l_turno"
     .Parameters.Delete         "l_serie"
     .Parameters.Delete         "l_turma"
     .Parameters.Delete         "l_ambiente"
     .Parameters.Delete         "l_tipo_sala"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os lista de unidades
REM -------------------------------------------------------------------------
Sub DB_GetUnidadeRel(p_rs, p_periodo, p_regional, p_modalidade, p_dif)
  Dim l_periodo, l_regional, l_modalidade, l_dif
  Set l_periodo      = Server.CreateObject("ADODB.Parameter")
  Set l_regional     = Server.CreateObject("ADODB.Parameter")
  Set l_modalidade   = Server.CreateObject("ADODB.Parameter")
  Set l_dif          = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_periodo              = .CreateParameter("l_periodo",     adInteger, adParamInput,  , p_periodo)
     set l_regional             = .CreateParameter("l_regional",    adVarchar, adParamInput, 2, tvl(p_regional))
     set l_modalidade           = .CreateParameter("l_modalidade",  adInteger, adParamInput,  , Nvl(p_modalidade,0))
     set l_dif                  = .CreateParameter("l_dif",         adVarchar, adParamInput, 1, tvl(p_dif))
     .parameters.Append         l_periodo
     .parameters.Append         l_regional
     .parameters.Append         l_modalidade
     .parameters.Append         l_dif
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_GetUnitRel"
     Else
        .CommandText               = "ecw.SP_GetUnitRel"
     End If
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_periodo"
     .Parameters.Delete         "l_regional"
     .Parameters.Delete         "l_modalidade"
     .Parameters.Delete         "l_dif"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera as faltas por série
REM -------------------------------------------------------------------------
Sub DB_GetFaltasRel(p_rs, p_periodo, p_unidade, p_modalidade, p_serie)
  Dim l_periodo, l_unidade, l_modalidade, l_serie
  Set l_periodo      = Server.CreateObject("ADODB.Parameter")
  Set l_unidade      = Server.CreateObject("ADODB.Parameter")
  Set l_modalidade   = Server.CreateObject("ADODB.Parameter")
  Set l_serie        = Server.CreateObject("ADODB.Parameter")

  with sp
     set l_periodo              = .CreateParameter("l_periodo",     adInteger, adParamInput,  , p_periodo)
     set l_unidade              = .CreateParameter("l_unidade",     adInteger, adParamInput,  , p_unidade)
     set l_modalidade           = .CreateParameter("l_modalidade",  adInteger, adParamInput,  , tvl(p_modalidade))
     set l_serie                = .CreateParameter("l_serie",       adVarchar, adParamInput, 5, tvl(p_serie))
     .parameters.Append         l_periodo
     .parameters.Append         l_unidade
     .parameters.Append         l_modalidade
     .parameters.Append         l_serie
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_GetFaltasRel"
     Else
        .CommandText               = "ecw.SP_GetFaltasRel"
     End If
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_periodo"
     .Parameters.Delete         "l_unidade"
     .Parameters.Delete         "l_modalidade"
     .Parameters.Delete         "l_serie"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera o calendário
REM -------------------------------------------------------------------------
Sub DB_GetCalendarioRel(p_rs, p_calendario)
  Dim l_calendario
  Set l_calendario      = Server.CreateObject("ADODB.Parameter")

  with sp
     set l_calendario           = .CreateParameter("l_calendario",  adInteger, adParamInput,  , tvl(p_calendario))
     .parameters.Append         l_calendario
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_GetCalendarRel"
     Else
        .CommandText               = "ecw.SP_GetCalendarRel"
     End If
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_calendario"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera o relatório de controle de comunicação
REM -------------------------------------------------------------------------
Sub DB_GetComunicRel(p_rs,p_regional, p_unidade, p_processamento_ini, p_processamento_fim, p_recebimento_ini, p_recebimento_fim)
  Dim l_regional, l_unidade, l_processamento_ini, l_processamento_fim, l_recebimento_ini, l_recebimento_fim
  Set l_regional           = Server.CreateObject("ADODB.Parameter")
  Set l_unidade            = Server.CreateObject("ADODB.Parameter")
  Set l_processamento_ini  = Server.CreateObject("ADODB.Parameter")
  Set l_processamento_fim  = Server.CreateObject("ADODB.Parameter")
  Set l_recebimento_ini    = Server.CreateObject("ADODB.Parameter")
  Set l_recebimento_fim    = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_regional             = .CreateParameter("l_regional",           adVarchar, adParamInput, 2, tvl(p_regional))
     set l_unidade              = .CreateParameter("l_unidade",            adInteger, adParamInput,  , tvl(p_unidade))
     set l_processamento_ini    = .CreateParameter("l_processamento_ini",  adDate,    adParamInput, 2, tvl(p_processamento_ini))
     set l_processamento_fim    = .CreateParameter("l_processamento_fim",  adDate,    adParamInput, 2, tvl(p_processamento_fim))
     set l_recebimento_ini      = .CreateParameter("l_recebimento_ini",    adDate,    adParamInput, 2, tvl(p_recebimento_ini))
     set l_recebimento_fim      = .CreateParameter("l_recebimento_fim",    adDate,    adParamInput, 2, tvl(p_recebimento_fim))
     .parameters.Append         l_regional
     .parameters.Append         l_unidade
     .parameters.Append         l_processamento_ini
     .parameters.Append         l_processamento_fim
     .parameters.Append         l_recebimento_ini
     .parameters.Append         l_recebimento_fim
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_GetComunicRel"
     Else
        .CommandText               = "ecw.SP_GetComunicRel"
     End If
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_regional"
     .Parameters.Delete         "l_unidade"
     .Parameters.Delete         "l_processamento_ini"
     .Parameters.Delete         "l_processamento_fim"
     .Parameters.Delete         "l_recebimento_ini"
     .Parameters.Delete         "l_recebimento_fim"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os alunos existentes conforme layout do relatório de alunos
REM -------------------------------------------------------------------------
Sub DB_GetFuncRel(p_rs, p_periodo, p_regional, p_bairro, p_unidade, p_area_atuacao, p_escolaridade, p_cargo, p_sexo, p_mat_ini, p_mat_fim)
  Dim l_periodo, l_regional, l_bairro, l_unidade, l_area_atuacao, l_escolaridade, l_cargo
  Dim l_sexo, l_mat_ini, l_mat_fim
  Set l_periodo      = Server.CreateObject("ADODB.Parameter")
  Set l_regional     = Server.CreateObject("ADODB.Parameter")
  Set l_bairro       = Server.CreateObject("ADODB.Parameter")
  Set l_unidade      = Server.CreateObject("ADODB.Parameter")
  Set l_area_atuacao = Server.CreateObject("ADODB.Parameter")
  Set l_escolaridade = Server.CreateObject("ADODB.Parameter")
  Set l_cargo        = Server.CreateObject("ADODB.Parameter")
  Set l_sexo         = Server.CreateObject("ADODB.Parameter")
  Set l_mat_ini      = Server.CreateObject("ADODB.Parameter")
  Set l_mat_fim      = Server.CreateObject("ADODB.Parameter")

  with sp
     set l_periodo              = .CreateParameter("l_periodo",        adInteger, adParamInput,  , p_periodo)
     set l_regional             = .CreateParameter("l_regional",       adVarchar, adParamInput, 2, tvl(p_regional))
     set l_bairro               = .CreateParameter("l_bairro",         adVarchar, adParamInput, 1, tvl(p_bairro))
     set l_unidade              = .CreateParameter("l_unidade",        adInteger, adParamInput,  , tvl(p_unidade))
     set l_area_atuacao         = .CreateParameter("l_area_atuacao",   adInteger, adParamInput,  , tvl(p_area_atuacao))
     set l_escolaridade         = .CreateParameter("l_escolaridade",   adVarchar, adParamInput,40, tvl(p_escolaridade))
     set l_cargo                = .CreateParameter("l_cargo",          adVarchar, adParamInput,17, tvl(p_cargo))
     set l_sexo                 = .CreateParameter("l_sexo",           adChar   , adParamInput, 1, tvl(p_sexo))
     set l_mat_ini              = .CreateParameter("l_mat_ini",        adDate,    adParamInput,  , tvl(p_mat_fim))
     set l_mat_fim              = .CreateParameter("l_mat_fim",        adDate,    adParamInput,  , tvl(p_mat_fim))
     .parameters.Append         l_periodo
     .parameters.Append         l_regional
     .parameters.Append         l_bairro
     .parameters.Append         l_unidade
     .parameters.Append         l_area_atuacao
     .parameters.Append         l_escolaridade
     .parameters.Append         l_cargo
     .parameters.Append         l_sexo
     .parameters.Append         l_mat_ini
     .parameters.Append         l_mat_fim
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_GetFuncRel"
     Else
        .CommandText               = "ecw.SP_GetFuncRel"
     End If
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_periodo"
     .Parameters.Delete         "l_regional"
     .Parameters.Delete         "l_bairro"
     .Parameters.Delete         "l_unidade"
     .Parameters.Delete         "l_area_atuacao"
     .Parameters.Delete         "l_escolaridade"
     .Parameters.Delete         "l_cargo"
     .Parameters.Delete         "l_sexo"
     .Parameters.Delete         "l_mat_ini"
     .Parameters.Delete         "l_mat_fim"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os alunos existentes conforme layout do relatório de alunos
REM -------------------------------------------------------------------------
Sub DB_GetProfRel(p_rs, p_periodo, p_regional, p_modalidade, p_serie, p_turma, p_disciplina, p_turno, p_bairro, p_tipo, p_unidade, p_escolaridade, p_cargo, p_sexo, p_mat_ini, p_mat_fim )
  Dim l_periodo, l_regional, l_modalidade, l_serie, l_turma, l_disciplina, l_turno, l_bairro, l_tipo
  Dim l_unidade, l_escolaridade, l_cargo, l_sexo, l_mat_ini, l_mat_fim
  Set l_periodo      = Server.CreateObject("ADODB.Parameter")
  Set l_regional     = Server.CreateObject("ADODB.Parameter")
  Set l_modalidade   = Server.CreateObject("ADODB.Parameter")
  Set l_serie        = Server.CreateObject("ADODB.Parameter")
  Set l_turma        = Server.CreateObject("ADODB.Parameter")
  Set l_disciplina   = Server.CreateObject("ADODB.Parameter")
  Set l_turno        = Server.CreateObject("ADODB.Parameter")
  Set l_bairro       = Server.CreateObject("ADODB.Parameter")
  Set l_tipo         = Server.CreateObject("ADODB.Parameter")
  Set l_unidade      = Server.CreateObject("ADODB.Parameter")
  Set l_escolaridade = Server.CreateObject("ADODB.Parameter")
  Set l_cargo        = Server.CreateObject("ADODB.Parameter")
  Set l_sexo         = Server.CreateObject("ADODB.Parameter")
  Set l_mat_ini      = Server.CreateObject("ADODB.Parameter")
  Set l_mat_fim      = Server.CreateObject("ADODB.Parameter")

  with sp
     set l_periodo              = .CreateParameter("l_periodo",      adInteger, adParamInput,  , p_periodo)
     set l_regional             = .CreateParameter("l_regional",     adVarchar, adParamInput, 2, tvl(p_regional))
     set l_modalidade           = .CreateParameter("l_modalidade",   adInteger, adParamInput,  , tvl(p_modalidade))
     set l_serie                = .CreateParameter("l_serie",        adVarchar, adParamInput, 5, tvl(p_serie))
     set l_turma                = .CreateParameter("l_turma",        adInteger, adParamInput,  , tvl(p_turma))
     set l_disciplina           = .CreateParameter("l_disciplina",   adInteger, adParamInput,  , tvl(p_disciplina))
     set l_turno                = .CreateParameter("l_turno",        adVarchar, adParamInput, 2, tvl(p_turno))
     set l_bairro               = .CreateParameter("l_bairro",       adVarchar, adParamInput, 1, tvl(p_bairro))
     set l_tipo                 = .CreateParameter("l_tipo",         adVarchar, adParamInput, 1, tvl(p_tipo))
     set l_unidade              = .CreateParameter("l_unidade",      adInteger, adParamInput,  , tvl(p_unidade))
     set l_escolaridade         = .CreateParameter("l_escolaridade", adchar,    adParamInput,40, tvl(p_escolaridade))
     set l_cargo                = .CreateParameter("l_cargo",        adVarchar, adParamInput,17, tvl(p_cargo))
     set l_sexo                 = .CreateParameter("l_sexo",         adVarchar, adParamInput, 1, tvl(p_sexo))
     set l_mat_ini              = .CreateParameter("l_mat_ini",      adDate,    adParamInput,  , tvl(p_mat_ini))
     set l_mat_fim              = .CreateParameter("l_mat_fim",      adDate,    adParamInput,  , tvl(p_mat_fim))
     .parameters.Append         l_periodo
     .parameters.Append         l_regional
     .parameters.Append         l_modalidade
     .parameters.Append         l_serie
     .parameters.Append         l_turma
     .parameters.Append         l_disciplina
     .parameters.Append         l_turno
     .parameters.Append         l_bairro
     .parameters.Append         l_tipo
     .parameters.Append         l_unidade
     .parameters.Append         l_escolaridade
     .parameters.Append         l_cargo
     .parameters.Append         l_sexo
     .parameters.Append         l_mat_ini
     .parameters.Append         l_mat_fim
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_GetProfRel"
     Else
        .CommandText               = "ecw.SP_GetProfRel"
     End If
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_periodo"
     .Parameters.Delete         "l_regional"
     .Parameters.Delete         "l_modalidade"
     .Parameters.Delete         "l_serie"
     .Parameters.Delete         "l_turma"
     .Parameters.Delete         "l_disciplina"
     .Parameters.Delete         "l_turno"
     .Parameters.Delete         "l_bairro"
     .Parameters.Delete         "l_tipo"
     .Parameters.Delete         "l_unidade"
     .Parameters.Delete         "l_escolaridade"
     .Parameters.Delete         "l_cargo"
     .Parameters.Delete         "l_sexo"
     .Parameters.Delete         "l_mat_ini"
     .Parameters.Delete         "l_mat_fim"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os alunos com necessidades educacionais especiais
REM -------------------------------------------------------------------------
Sub DB_GetANEERel(p_rs, p_periodo, p_regional, p_unidade)
  Dim l_periodo, l_regional, l_tipo, l_unidade
  Set l_periodo      = Server.CreateObject("ADODB.Parameter")
  Set l_regional     = Server.CreateObject("ADODB.Parameter")
  Set l_unidade      = Server.CreateObject("ADODB.Parameter")

  with sp
     set l_periodo              = .CreateParameter("l_periodo",  adInteger, adParamInput,  , p_periodo)
     set l_regional             = .CreateParameter("l_regional", adVarchar, adParamInput, 2, tvl(p_regional))
     set l_unidade              = .CreateParameter("l_unidade",  adInteger, adParamInput,  , tvl(p_unidade))
     .parameters.Append         l_periodo
     .parameters.Append         l_regional
     .parameters.Append         l_unidade
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_GetANEERel"
     Else
        .CommandText               = "ecw.SP_GetANEERel"
     End If
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_periodo"
     .Parameters.Delete         "l_regional"
     .Parameters.Delete         "l_unidade"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera a lista de rendimento escolar de uma unidade
REM -------------------------------------------------------------------------
Sub DB_GetRendRel(p_rs, p_periodo, p_unidade, p_modalidade, p_serie, p_turma, p_turno, p_bimestre)
  Dim l_periodo, l_modalidade, l_serie, l_turma, l_turno, l_bimestre, l_unidade
  Set l_periodo      = Server.CreateObject("ADODB.Parameter")
  Set l_unidade      = Server.CreateObject("ADODB.Parameter")
  Set l_modalidade   = Server.CreateObject("ADODB.Parameter")
  Set l_serie        = Server.CreateObject("ADODB.Parameter")
  Set l_turma        = Server.CreateObject("ADODB.Parameter")
  Set l_turno        = Server.CreateObject("ADODB.Parameter")
  Set l_bimestre     = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_periodo              = .CreateParameter("l_periodo",    adInteger, adParamInput,  , p_periodo)
     set l_unidade              = .CreateParameter("l_unidade",    adInteger, adParamInput,  , p_unidade)
     set l_modalidade           = .CreateParameter("l_modalidade", adInteger, adParamInput,  , tvl(p_modalidade))
     set l_serie                = .CreateParameter("l_serie",      adVarchar, adParamInput, 5, tvl(p_serie))
     set l_turma                = .CreateParameter("l_turma",      adInteger, adParamInput,  , tvl(p_turma))
     set l_turno                = .CreateParameter("l_turno",      adVarchar, adParamInput, 2, tvl(p_turno))
     set l_bimestre             = .CreateParameter("l_bimestre",   adVarchar, adParamInput, 5, p_bimestre)
     .parameters.Append         l_periodo
     .parameters.Append         l_unidade
     .parameters.Append         l_modalidade
     .parameters.Append         l_serie
     .parameters.Append         l_turma
     .parameters.Append         l_turno
     .parameters.Append         l_bimestre
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_GetRendRel"
     Else
        .CommandText               = "ecw.SP_GetRendRel"
     End If
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_periodo"
     .Parameters.Delete         "l_unidade"
     .Parameters.Delete         "l_modalidade"
     .Parameters.Delete         "l_serie"
     .Parameters.Delete         "l_turma"
     .Parameters.Delete         "l_turno"
     .Parameters.Delete         "l_bimestre"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

%>

