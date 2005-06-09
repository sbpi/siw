<%
REM =========================================================================
REM Recupera as Áreas de atuações existentes
REM -------------------------------------------------------------------------
Sub DB_GetSchoolOriginList(p_rs)

  with sp
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_GetSchOrList"
     Else
        .CommandText               = "ecw.SP_GetSchOrList"
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
REM Recupera os dados da área de atuação
REM -------------------------------------------------------------------------
Sub DB_GetSchoolOriginData(p_rs, p_co_origem_escola)
  Dim l_co_origem_escola
  Set l_co_origem_escola = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_co_origem_escola     = .CreateParameter("l_co_origem_escola", adInteger, adParamInput, , p_co_origem_escola)
     .parameters.Append         l_co_origem_escola
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_GetSchOrData"
     Else
        .CommandText               = "ecw.SP_GetSchOrData"
     End If
     On Error Resume Next     
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_co_origem_escola"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

%>

