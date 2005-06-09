<%
REM =========================================================================
REM Recupera os tipos de disciplinas existentes
REM -------------------------------------------------------------------------
Sub DB_GetDisciplineTypeList(p_rs)

  with sp
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_GetDiscTPList"
     Else
        .CommandText               = "ecw.SP_GetDiscTPList"
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
REM Recupera os dados do tipo da disciplina
REM -------------------------------------------------------------------------
Sub DB_GetDisciplineTypeData(p_rs, p_co_tipo_disciplina)
  Dim l_co_tipo_disciplina
  Set l_co_tipo_disciplina = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_co_tipo_disciplina   = .CreateParameter("l_co_tipo_disciplina", adInteger, adParamInput, , p_co_tipo_disciplina)
     .parameters.Append         l_co_tipo_disciplina
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_GetDiscTPData"
     Else
        .CommandText               = "ecw.SP_GetDiscTPData"
     End If
     On Error Resume Next     
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_co_tipo_disciplina"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

%>

