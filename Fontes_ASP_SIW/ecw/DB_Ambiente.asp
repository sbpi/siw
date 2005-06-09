<%
REM =========================================================================
REM Recupera os bancos existentes
REM -------------------------------------------------------------------------
Sub DB_GetAmbientList(p_rs)

  with sp
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then 
        .CommandText               = "ecw.ecw.SP_GetAmbientList"
     Else
        .CommandText               = "ecw.SP_GetAmbientList"
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
REM Recupera os dados do banco
REM -------------------------------------------------------------------------
Sub DB_GetAmbientData(p_rs, p_co_seq_ambiente)
  Dim l_co_seq_ambiente
  Set l_co_seq_ambiente = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_co_seq_ambiente       = .CreateParameter("l_co_sq_ambiente", adInteger, adParamInput, , p_co_seq_ambiente)
     .parameters.Append         l_co_seq_ambiente
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     If Session("dbms") = 2 Then 
        .CommandText               = "ecw.ecw.SP_GetAmbientData"
     Else
        .CommandText               = "ecw.SP_GetAmbientData"
     End If
     On Error Resume Next     
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_co_seq_ambiente"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

%>

