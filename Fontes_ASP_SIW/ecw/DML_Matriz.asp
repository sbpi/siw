<%
REM =========================================================================
REM Manipula registros de S_GRADE_CURRIC
REM -------------------------------------------------------------------------
Sub DML_SMATRIZ(Operacao, Chave, co_tipo_curso, ano, turno, dt_grade, nu_semanas, nu_grade, ds_grade)
  Dim l_Operacao, l_Chave, l_co_tipo_curso, l_ano, l_turno, l_dt_grade, l_nu_semanas, l_nu_grade, l_ds_grade
  Set l_Operacao        = Server.CreateObject("ADODB.Parameter")
  Set l_Chave           = Server.CreateObject("ADODB.Parameter")
  Set l_co_tipo_curso   = Server.CreateObject("ADODB.Parameter")
  Set l_ano             = Server.CreateObject("ADODB.Parameter")
  Set l_turno           = Server.CreateObject("ADODB.Parameter")
  Set l_dt_grade        = Server.CreateObject("ADODB.Parameter")
  Set l_nu_semanas      = Server.CreateObject("ADODB.Parameter")
  Set l_nu_grade        = Server.CreateObject("ADODB.Parameter")
  Set l_ds_grade        = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",        adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,    , Tvl(chave))
     set l_co_tipo_curso        = .CreateParameter("l_co_tipo_curso",   adInteger, adParamInput,    , co_tipo_curso)
     set l_ano                  = .CreateParameter("l_ano",             adInteger, adParamInput,    , ano)
     set l_turno                = .CreateParameter("l_turno",           adChar,    adParamInput,   2, turno)
     set l_dt_grade             = .CreateParameter("l_dt_grade",        adDate,    adParamInput,    , Tvl(dt_grade))
     set l_nu_semanas           = .CreateParameter("l_nu_semanas",      adInteger, adParamInput,    , nu_semanas)
     set l_nu_grade             = .CreateParameter("l_nu_grade",        adChar,    adParamInput,  15, nu_grade)
     set l_ds_grade             = .CreateParameter("l_ds_grade",        adChar,    adParamInput,  40, ds_grade)
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_co_tipo_curso
     .parameters.Append         l_ano
     .parameters.Append         l_turno
     .parameters.Append         l_dt_grade
     .parameters.Append         l_nu_semanas
     .parameters.Append         l_nu_grade
     .parameters.Append         l_ds_grade
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_PutSGrade_Curr"
     Else
        .CommandText               = "ecw.SP_PutSGrade_Curr"
     End If
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If          
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_co_tipo_curso"
     .parameters.Delete         "l_ano"
     .parameters.Delete         "l_turno"
     .parameters.Delete         "l_dt_grade"
     .parameters.Delete         "l_nu_semanas"
     .parameters.Delete         "l_nu_grade"
     .parameters.Delete         "l_ds_grade"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Manipula registros de S_PERIODO
REM -------------------------------------------------------------------------
Sub DML_SPERIODO(Operacao, turno, co_grade_curric, ano, co_tipo_curso, sg_serie )
  Dim l_Operacao, l_turno, l_co_grade_curric, l_ano, l_co_tipo_curso, l_sg_serie
  Set l_Operacao        = Server.CreateObject("ADODB.Parameter")
  Set l_turno           = Server.CreateObject("ADODB.Parameter")
  Set l_co_grade_curric = Server.CreateObject("ADODB.Parameter")
  Set l_ano             = Server.CreateObject("ADODB.Parameter")
  Set l_co_tipo_curso   = Server.CreateObject("ADODB.Parameter")
  Set l_sg_serie        = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",        adVarchar, adParamInput,   1, Operacao)
     set l_turno                = .CreateParameter("l_turno",           adChar,    adParamInput,   2, Tvl(turno))
     set l_co_grade_curric      = .CreateParameter("l_co_grade_curric", adInteger, adParamInput,    , Tvl(co_grade_curric))
     set l_ano                  = .CreateParameter("l_ano",             adInteger, adParamInput,    , Tvl(ano))
     set l_co_tipo_curso        = .CreateParameter("l_co_tipo_curso",   adInteger, adParamInput,    , Tvl(co_tipo_curso))
     set l_sg_serie             = .CreateParameter("l_sg_serie",        adVarchar, adParamInput,   5, Tvl(sg_serie))
     .parameters.Append         l_Operacao
     .parameters.Append         l_turno
     .parameters.Append         l_co_grade_curric
     .parameters.Append         l_ano
     .parameters.Append         l_co_tipo_curso
     .parameters.Append         l_sg_serie
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_PutSPeriodo"
     Else
        .CommandText               = "ecw.SP_PutSPeriodo"
     End If
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If          
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_turno"
     .parameters.Delete         "l_co_grade_curric"
     .parameters.Delete         "l_ano"
     .parameters.Delete         "l_co_tipo_curso"
     .parameters.Delete         "l_sg_serie"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Manipula registros de S_DISCIPLINA_PER
REM -------------------------------------------------------------------------
Sub DML_SDISCIPLINAPER(Operacao, sg_serie, co_tipo_disciplina, co_grade_curric, co_tipo_curso, ano, _
                        turno, carga_horaria_sem, tp_disciplina, co_disciplina, ds_disciplina, nu_ordem_imp, _
                        tp_avaliacao, tp_digitacao, tp_impressao, st_reprova)
  Dim l_Operacao, l_sg_serie, l_co_tipo_disciplina, l_co_grade_curric, l_co_tipo_curso, l_ano
  Dim l_turno, l_carga_horaria_sem, l_tp_disciplina, l_co_disciplina, l_ds_disciplina, l_nu_ordem_imp, l_tp_avaliacao
  Dim l_tp_digitacao, l_tp_impressao, l_st_reprova
  Set l_Operacao              = Server.CreateObject("ADODB.Parameter")
  Set l_sg_serie              = Server.CreateObject("ADODB.Parameter")
  Set l_co_tipo_disciplina    = Server.CreateObject("ADODB.Parameter")
  Set l_co_grade_curric       = Server.CreateObject("ADODB.Parameter")
  Set l_co_tipo_curso         = Server.CreateObject("ADODB.Parameter")
  Set l_ano                   = Server.CreateObject("ADODB.Parameter")
  Set l_turno                 = Server.CreateObject("ADODB.Parameter")
  Set l_carga_horaria_sem     = Server.CreateObject("ADODB.Parameter")
  Set l_tp_disciplina         = Server.CreateObject("ADODB.Parameter")
  Set l_co_disciplina         = Server.CreateObject("ADODB.Parameter")
  Set l_ds_disciplina         = Server.CreateObject("ADODB.Parameter")
  Set l_nu_ordem_imp          = Server.CreateObject("ADODB.Parameter")
  Set l_tp_avaliacao          = Server.CreateObject("ADODB.Parameter")
  Set l_tp_digitacao          = Server.CreateObject("ADODB.Parameter")
  Set l_tp_impressao          = Server.CreateObject("ADODB.Parameter")
  Set l_st_reprova            = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",           adVarChar, adParamInput,   1, Operacao)
     set l_sg_serie             = .CreateParameter("l_sg_serie",           adVarChar, adParamInput,   5, sg_serie)
     set l_co_tipo_disciplina   = .CreateParameter("l_co_tipo_disciplina", adInteger, adParamInput,    , Tvl(co_tipo_disciplina))
     set l_co_grade_curric      = .CreateParameter("l_co_grade_curric",    adInteger, adParamInput,    , Tvl(co_grade_curric))
     set l_co_tipo_curso        = .CreateParameter("l_co_tipo_curso",      adInteger, adParamInput,    , Tvl(co_tipo_curso))     
     set l_ano                  = .CreateParameter("l_ano",                adInteger, adParamInput,    , Tvl(ano))
     set l_turno                = .CreateParameter("l_turno",              adChar,    adParamInput,   2, Tvl(turno))
     set l_carga_horaria_sem    = .CreateParameter("l_carga_horaria_sem",  adInteger, adParamInput,    , Tvl(carga_horaria_sem))
     set l_tp_disciplina        = .CreateParameter("l_tp_disciplina",      adVarChar, adParamInput,  30, Tvl(tp_disciplina))
     set l_co_disciplina        = .CreateParameter("l_co_disciplina",      adChar,    adParamInput,   4, Tvl(co_disciplina))
     set l_ds_disciplina        = .CreateParameter("l_ds_disciplina",      adChar,    adParamInput,  60, Tvl(ds_disciplina))
     set l_nu_ordem_imp         = .CreateParameter("l_nu_ordem_imp",       adInteger, adParamInput,    , Tvl(nu_ordem_imp))
     set l_tp_avaliacao         = .CreateParameter("l_tp_avaliacao",       adVarChar, adParamInput,   8, Tvl(tp_avaliacao))
     set l_tp_digitacao         = .CreateParameter("l_tp_digitacao",       adVarChar, adParamInput,   8, Tvl(tp_digitacao))
     set l_tp_impressao         = .CreateParameter("l_tp_impressao",       adVarChar, adParamInput,   8, Tvl(tp_impressao))            
     set l_st_reprova           = .CreateParameter("l_st_reprova",         adVarChar, adParamInput,   3, Tvl(st_reprova))     
     .parameters.Append         l_Operacao
     .parameters.Append         l_sg_serie
     .parameters.Append         l_co_tipo_disciplina
     .parameters.Append         l_co_grade_curric
     .parameters.Append         l_co_tipo_curso
     .parameters.Append         l_ano
     .parameters.Append         l_turno
     .parameters.Append         l_carga_horaria_sem
     .parameters.Append         l_tp_disciplina
     .parameters.Append         l_co_disciplina
     .parameters.Append         l_ds_disciplina
     .parameters.Append         l_nu_ordem_imp
     .parameters.Append         l_tp_avaliacao
     .parameters.Append         l_tp_digitacao
     .parameters.Append         l_tp_impressao
     .parameters.Append         l_st_reprova
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_PutSDiscPer"
     Else
        .CommandText               = "ecw.SP_PutSDiscPer"
     End If
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If          
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_sg_serie"
     .parameters.Delete         "l_co_tipo_disciplina"
     .parameters.Delete         "l_co_grade_curric"
     .parameters.Delete         "l_co_tipo_curso"
     .parameters.Delete         "l_ano"
     .parameters.Delete         "l_turno"
     .parameters.Delete         "l_carga_horaria_sem"
     .parameters.Delete         "l_tp_disciplina"
     .parameters.Delete         "l_co_disciplina"
     .parameters.Delete         "l_ds_disciplina"
     .parameters.Delete         "l_nu_ordem_imp"
     .parameters.Delete         "l_tp_avaliacao"
     .parameters.Delete         "l_tp_digitacao"
     .parameters.Delete         "l_tp_impressao"
     .parameters.Delete         "l_st_reprova"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>

