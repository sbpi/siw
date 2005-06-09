<%
REM =========================================================================
REM Recupera a lista de clientes do SIW
REM -------------------------------------------------------------------------
Sub DB_GetSiwCliList(p_rs)
  with sp
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetSiwCliList"
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
REM Recupera os dados de um cliente do SIW a partir de seu CNPJ
REM -------------------------------------------------------------------------
Sub DB_GetSiwCliData(p_rs, p_cnpj)
  Dim l_cnpj
  Set l_cnpj        = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cnpj                 = .CreateParameter("l_cnpj",  adVarchar, adParamInput, 18, p_cnpj)
     .parameters.Append         l_cnpj
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetSiwCliData"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_cnpj"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>

