<%

REM =========================================================================
REM Recupera os impostos
REM -------------------------------------------------------------------------
Sub DB_GetImposto(p_rs, p_chave, p_cliente)
  Dim l_chave, l_cliente
  Set l_chave        = Server.CreateObject("ADODB.Parameter")
  Set l_cliente      = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave   = .CreateParameter("l_chave",   adInteger, adParamInput,   , Tvl(p_chave))
     set l_cliente = .CreateParameter("l_cliente", adInteger, adParamInput,   , p_cliente)

     .parameters.Append         l_chave
     .parameters.Append         l_cliente

     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetImposto"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_cliente"

  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os tipos de acordo do cliente
REM -------------------------------------------------------------------------
Sub DB_GetTipoDocumento(p_rs, p_chave, p_cliente)
  Dim l_chave, l_cliente, l_restricao
  Set l_chave        = Server.CreateObject("ADODB.Parameter")
  Set l_cliente      = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave   = .CreateParameter("l_chave",   adInteger, adParamInput,   , Tvl(p_chave))
     set l_cliente = .CreateParameter("l_cliente", adInteger, adParamInput,   , p_cliente)

     .parameters.Append         l_chave
     .parameters.Append         l_cliente

     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetTipoDocumento"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_cliente"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os tipos de lançamento
REM -------------------------------------------------------------------------
Sub DB_GetTipoLancamento(p_rs, p_chave, p_cliente, p_restricao)
  Dim l_chave, l_cliente, l_restricao
  Set l_chave        = Server.CreateObject("ADODB.Parameter")
  Set l_cliente      = Server.CreateObject("ADODB.Parameter")
  Set l_restricao    = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave     = .CreateParameter("l_chave",     adInteger, adParamInput,   , Tvl(p_chave))
     set l_cliente   = .CreateParameter("l_cliente",   adInteger, adParamInput,   , p_cliente)
     set l_restricao = .CreateParameter("l_restricao", adVarchar, adParamInput, 15, p_restricao)

     .parameters.Append         l_chave
     .parameters.Append         l_cliente
     .parameters.Append         l_restricao
     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetTipoLancamento"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_restricao"

  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

%>

