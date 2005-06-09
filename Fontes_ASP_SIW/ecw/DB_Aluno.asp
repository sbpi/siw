<%
REM =========================================================================
REM Recupera as unidades de ensino existentes
REM -------------------------------------------------------------------------
Sub DB_GetSchoolList(p_rs, p_cliente)
  Dim l_cliente, l_periodo, l_regional
  Set l_periodo      = Server.CreateObject("ADODB.Parameter")
  Set l_regional     = Server.CreateObject("ADODB.Parameter")

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
REM Recupera os dados do banco
REM -------------------------------------------------------------------------
Sub DB_GetStudentData(p_rs, p_co_seq_ambiente)
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

