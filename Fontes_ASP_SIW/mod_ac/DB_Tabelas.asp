<%

REM =========================================================================
REM Recupera os tipos de acordo do cliente
REM -------------------------------------------------------------------------
Sub DB_GetAgreeType(p_rs, p_chave, p_chave_aux, p_cliente, p_restricao)
  Dim l_chave, l_chave_aux, l_cliente, l_restricao
  Set l_chave        = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux    = Server.CreateObject("ADODB.Parameter")
  Set l_cliente      = Server.CreateObject("ADODB.Parameter")
  Set l_restricao    = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave",       adInteger, adParamInput,   , Tvl(p_chave))
     set l_chave_aux            = .CreateParameter("l_chave_aux",   adInteger, adParamInput,   , Tvl(p_chave_aux))
     set l_cliente              = .CreateParameter("l_cliente",     adInteger, adParamInput,   , p_cliente)
     set l_restricao            = .CreateParameter("l_restricao",   adVarchar, adParamInput, 50, Tvl(p_restricao))
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_cliente
     .parameters.Append         l_restricao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetAgreeType"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_chave_aux"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_restricao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

%>

