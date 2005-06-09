<%
REM =========================================================================
REM Recupera o nome do unidade
REM -------------------------------------------------------------------------
Sub DB_GetUorgData(p_rs, p_sq_unidade)
  Dim l_sq_unidade
  Set l_sq_unidade = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_sq_unidade           = .CreateParameter("l_sq_unidade", adInteger, adParamInput, , p_sq_unidade)
     .parameters.Append         l_sq_unidade
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetUorgData"
     On Error Resume Next     
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_sq_unidade"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os responsáveis por pela unidade escolhida
REM -------------------------------------------------------------------------
Sub DB_GetUorgResp(p_rs, p_sq_unidade)
  
  Dim l_sq_unidade
  
  Set l_sq_unidade = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_sq_unidade           = .CreateParameter("l_sq_unidade", adInteger, adParamInput, , p_sq_unidade)
     .parameters.Append         l_sq_unidade
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetUorgResp"
     On Error Resume Next     
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_sq_unidade"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>