<%
REM =========================================================================
REM Recupera as Matrizes existentes
REM -------------------------------------------------------------------------
Sub DB_GetMatrixList(p_rs)

  with sp
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_GetMatrixList"
     Else
        .CommandText               = "ecw.SP_GetMatrixList"
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
REM Recupera a lista das série com exceção das existentes na matriz
REM -------------------------------------------------------------------------
Sub DB_GetMatrixSerieList(p_rs, p_co_grade_curric, p_co_tipo_curso)
  Dim l_co_grade_curric
  Dim l_co_tipo_curso
  Set l_co_grade_curric = Server.CreateObject("ADODB.Parameter")
  Set l_co_tipo_curso   = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_co_grade_curric      = .CreateParameter("l_co_grade_curric", adInteger, adParamInput, , Tvl(p_co_grade_curric))
     set l_co_tipo_curso        = .CreateParameter("l_co_tipo_curso",   adInteger, adParamInput, , Tvl(p_co_tipo_curso))
     .parameters.Append         l_co_grade_curric
     .parameters.Append         l_co_tipo_curso
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_GetMatSerList"
     Else
        .CommandText               = "ecw.SP_GetMatSerList"
     End If
     On Error Resume Next     
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_co_grade_curric"
     .Parameters.Delete         "l_co_tipo_curso"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------


REM =========================================================================
REM Recupera os dados da Matriz Curricular
REM -------------------------------------------------------------------------
Sub DB_GetMatrixData(p_rs, p_co_grade_curric)
  Dim l_co_grade_curric
  Set l_co_grade_curric = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_co_grade_curric      = .CreateParameter("l_co_grade_curric", adInteger, adParamInput, , p_co_grade_curric)
     .parameters.Append         l_co_grade_curric
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_GetMatrixData"
     Else
        .CommandText               = "ecw.SP_GetMatrixData"
     End If
     On Error Resume Next     
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_co_grade_curric"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os dados da Matriz Curricular
REM -------------------------------------------------------------------------
Sub DB_GetMatrixSerieData(p_rs, p_co_grade_curric)
  Dim l_co_grade_curric
  Set l_co_grade_curric = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_co_grade_curric      = .CreateParameter("l_co_grade_curric", adInteger, adParamInput, , p_co_grade_curric)
     .parameters.Append         l_co_grade_curric
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_GetMatSerData"
     Else
        .CommandText               = "ecw.SP_GetMatSerData"
     End If
     On Error Resume Next     
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_co_grade_curric"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os dados da Matriz Curricular
REM -------------------------------------------------------------------------
Sub DB_GetMatrixSerieOneData(p_rs, p_co_grade_curric, p_sg_serie)
  Dim l_co_grade_curric, l_sg_serie
  Set l_co_grade_curric = Server.CreateObject("ADODB.Parameter")
  Set l_sg_serie        = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_co_grade_curric      = .CreateParameter("l_co_grade_curric", adInteger, adParamInput,  , Tvl(p_co_grade_curric))
     set l_sg_serie             = .CreateParameter("l_sg_serie",        adVarchar, adParamInput, 5, p_sg_serie)
     .parameters.Append         l_co_grade_curric
     .parameters.Append         l_sg_serie
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_GetMatSerOData"
     Else
        .CommandText               = "ecw.SP_GetMatSerOData"
     End If
     On Error Resume Next     
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_co_grade_curric"
     .Parameters.Delete         "l_sg_serie"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os dados da Matriz Curricular
REM -------------------------------------------------------------------------
Sub DB_GetMatrixDisciplineData(p_rs, p_co_grade_curric, p_sg_serie)
  Dim l_co_grade_curric, l_sg_serie
  Set l_co_grade_curric = Server.CreateObject("ADODB.Parameter")
  Set l_sg_serie        = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_co_grade_curric      = .CreateParameter("l_co_grade_curric", adInteger, adParamInput,  , p_co_grade_curric)
     set l_sg_serie             = .CreateParameter("l_sg_serie",        adVarChar, adParamInput, 5, p_sg_serie)
     .parameters.Append         l_co_grade_curric
     .parameters.Append         l_sg_serie
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_GetMatDiscData"
     Else
        .CommandText               = "ecw.SP_GetMatDiscData"
     End If
     On Error Resume Next     
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_co_grade_curric"
     .Parameters.Delete         "l_sg_serie"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os dados da Matriz Curricular
REM -------------------------------------------------------------------------
Sub DB_GetMatrixDisciplineOneData(p_rs, p_co_grade_curric, p_co_tipo_disciplina, p_sg_serie)
  Dim l_co_grade_curric, l_sg_serie, l_co_tipo_disciplina
  Set l_co_grade_curric       = Server.CreateObject("ADODB.Parameter")
  Set l_sg_serie              = Server.CreateObject("ADODB.Parameter")
  Set l_co_tipo_disciplina    = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_co_grade_curric      = .CreateParameter("l_co_grade_curric",    adInteger, adParamInput,  , Tvl(p_co_grade_curric))
     set l_co_tipo_disciplina   = .CreateParameter("l_co_tipo_disciplina", adInteger, adParamInput,  , Tvl(p_co_tipo_disciplina))
     set l_sg_serie             = .CreateParameter("l_sg_serie",           adVarchar, adParamInput, 5, p_sg_serie)
     .parameters.Append         l_co_grade_curric
     .parameters.Append         l_co_tipo_disciplina
     .parameters.Append         l_sg_serie
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_GetMatDiscOData"
     Else
        .CommandText               = "ecw.SP_GetMatDiscOData"
     End If
     On Error Resume Next     
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_co_grade_curric"
     .Parameters.Delete         "l_co_tipo_disciplina"
     .Parameters.Delete         "l_sg_serie"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------



%>

