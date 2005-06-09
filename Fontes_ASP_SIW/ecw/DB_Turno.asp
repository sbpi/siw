<%
REM =========================================================================
REM Recupera as Áreas de atuações existentes
REM -------------------------------------------------------------------------
Sub DB_GetTurnList(p_rs)

  with sp
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_GetTurnList"
     Else
        .CommandText               = "ecw.SP_GetTurnList"
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
Sub DB_GetTurnData(p_rs, p_co_turno)
  Dim l_co_turno
  Set l_co_turno = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_co_turno             = .CreateParameter("l_co_turno", adChar, adParamInput, 2, p_co_turno)
     .parameters.Append         l_co_turno
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then
        .CommandText               = "ecw.ecw.SP_GetTurnData"
     Else
        .CommandText               = "ecw.SP_GetTurnData"
     End If
     'Response.Write "["&l_co_turno&"]"
     'Response.Write "<br>"
     'Response.Write sp.CommandText
     'Response.End()
     On Error Resume Next     
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_co_turno"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

%>

