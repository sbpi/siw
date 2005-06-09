<%
REM =========================================================================
REM Recupera as Áreas de atuações existentes
REM -------------------------------------------------------------------------
Sub DB_GetPositionList(p_rs)

  with sp
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_GetPositionList"
     Else
        .CommandText               = "ecw.SP_GetPositionList"
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
Sub DB_GetPositionData(p_rs, p_co_cargo)
  Dim l_co_cargo
  Set l_co_cargo = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_co_cargo             = .CreateParameter("l_co_cargo", adVarchar, adParamInput, 17, p_co_cargo)
     .parameters.Append         l_co_cargo
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_GetPositionData"
     Else
        .CommandText               = "ecw.SP_GetPositionData"
     End If
     On Error Resume Next     
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_co_cargo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

%>

