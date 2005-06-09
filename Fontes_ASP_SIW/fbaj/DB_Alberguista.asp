<%
REM =========================================================================
REM Recupera as pessoas vinculadas a um cliente
REM -------------------------------------------------------------------------
Sub DB_GetAlberList(p_rs, p_carteira, p_nome)
  Dim l_carteira, l_nome
  Set l_carteira     = Server.CreateObject("ADODB.Parameter")
  Set l_nome = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_carteira             = .CreateParameter("l_carteira",adVarchar, adParamInput, 20, Tvl(p_carteira))
     set l_nome                 = .CreateParameter("l_nome",    adVarChar, adParamInput, 20, Tvl(p_nome))
     .parameters.Append         l_carteira
     .parameters.Append         l_nome
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = "FBAJ.SP_GetAlberList"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_carteira"
     .Parameters.Delete         "l_nome"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os dados de uma pessoa cadastrada no sistema
REM -------------------------------------------------------------------------
Sub DB_GetAlberData(p_rs, p_sq_alberguista, p_carteira)
  Dim l_sq_alberguista, l_carteira
  Set l_sq_alberguista  = Server.CreateObject("ADODB.Parameter")
  Set l_carteira        = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_sq_alberguista       = .CreateParameter("l_sq_alberguista",  adInteger, adParamInput,   , p_sq_alberguista)
     set l_carteira             = .CreateParameter("l_carteira",        adVarchar, adParamInput, 20, Tvl(p_carteira))
     .parameters.Append         l_sq_alberguista
     .parameters.Append         l_carteira
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = "FBAJ.SP_GetAlberData"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_sq_alberguista"
     .Parameters.Delete         "l_carteira"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------


%>

