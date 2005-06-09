<%
REM =========================================================================
REM Recupera a lista de acordos do cliente
REM -------------------------------------------------------------------------
Sub DB_GetLancamentoDoc(p_rs, p_chave, p_chave_aux, p_restricao)
  Dim l_chave, l_chave_aux, l_restricao
  Set l_chave        = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux    = Server.CreateObject("ADODB.Parameter")
  Set l_restricao    = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave",       adInteger, adParamInput,   , Tvl(p_chave))
     set l_chave_aux            = .CreateParameter("l_chave_aux",   adInteger, adParamInput,   , Tvl(p_chave_aux))
     set l_restricao            = .CreateParameter("l_restricao",   adVarchar, adParamInput, 50, p_restricao)
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_restricao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetLancamentoDoc"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_chave_aux"
     .parameters.Delete         "l_restricao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera a lista de acordos do cliente
REM -------------------------------------------------------------------------
Sub DB_GetLancamentoLog(p_rs, p_chave)
  Dim l_chave
  
  Set l_chave        = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_chave                = .CreateParameter("l_chave",       adInteger, adParamInput,   , Tvl(p_chave))
     
     .parameters.Append         l_chave
     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetLancamentoLog"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_chave"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera a lista de acordos do cliente
REM -------------------------------------------------------------------------
Sub DB_GetImpostoDoc(p_rs, p_cliente, p_chave, p_chave_aux, p_restricao)
  Dim l_cliente, l_chave, l_chave_aux, l_restricao
  Set l_cliente      = Server.CreateObject("ADODB.Parameter")
  Set l_chave        = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux    = Server.CreateObject("ADODB.Parameter")
  Set l_restricao    = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente              = .CreateParameter("l_cliente",     adInteger, adParamInput,   , p_cliente)
     set l_chave                = .CreateParameter("l_chave",       adInteger, adParamInput,   , Tvl(p_chave))
     set l_chave_aux            = .CreateParameter("l_chave_aux",   adInteger, adParamInput,   , Tvl(p_chave_aux))
     set l_restricao            = .CreateParameter("l_restricao",   adVarchar, adParamInput, 50, p_restricao)
     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_restricao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetImpostoDoc"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_chave_aux"
     .parameters.Delete         "l_restricao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os dados para os relatórios de contas a pagar, a receber e fluxo de caixa
REM -------------------------------------------------------------------------
Sub DB_GetLancamento(p_rs, p_cliente, p_restricao, p_dt_ini, p_dt_fim, p_sq_pessoa, p_fase)
  Dim l_cliente, l_restricao, l_dt_ini, l_dt_fim, l_sq_pessoa, l_fase
  Set l_cliente      = Server.CreateObject("ADODB.Parameter")
  Set l_restricao    = Server.CreateObject("ADODB.Parameter")
  Set l_dt_ini       = Server.CreateObject("ADODB.Parameter")
  Set l_dt_fim       = Server.CreateObject("ADODB.Parameter")
  Set l_sq_pessoa    = Server.CreateObject("ADODB.Parameter")
  Set l_fase         = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente              = .CreateParameter("l_cliente",     adInteger, adParamInput,   , p_cliente)
     set l_restricao            = .CreateParameter("l_restricao",   adVarchar, adParamInput, 50, Tvl(p_restricao))
     set l_dt_ini               = .CreateParameter("l_dt_ini",      adDate,    adParamInput,   , Tvl(p_dt_ini))
     set l_dt_fim               = .CreateParameter("l_dt_fim",      adDate,    adParamInput,   , Tvl(p_dt_fim))
     set l_sq_pessoa            = .CreateParameter("l_sq_pessoa",   adInteger, adParamInput,   , Tvl(p_sq_pessoa))
     set l_fase                 = .CreateParameter("l_fase",        adVarchar, adParamInput, 50, Tvl(p_fase))
     .parameters.Append         l_cliente
     .parameters.Append         l_restricao
     .parameters.Append         l_dt_ini
     .parameters.Append         l_dt_fim
     .parameters.Append         l_sq_pessoa
     .parameters.Append         l_fase
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetLancamento"
     
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_restricao"
     .parameters.Delete         "l_dt_ini"
     .parameters.Delete         "l_dt_fim"
     .parameters.Delete         "l_sq_pessoa"
     .parameters.Delete         "l_fase"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera incidências de impostos
REM -------------------------------------------------------------------------
Sub DB_GetImpostoIncid(p_rs, p_cliente, p_chave, p_documento, p_lancamento, p_restricao)
  Dim l_cliente, l_chave, l_documento, l_lancamento, l_restricao
  Set l_cliente      = Server.CreateObject("ADODB.Parameter")
  Set l_chave        = Server.CreateObject("ADODB.Parameter")
  Set l_documento    = Server.CreateObject("ADODB.Parameter")
  Set l_lancamento   = Server.CreateObject("ADODB.Parameter")
  Set l_restricao    = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_cliente              = .CreateParameter("l_cliente",     adInteger, adParamInput,   , p_cliente)
     set l_chave                = .CreateParameter("l_chave",       adInteger, adParamInput,   , Tvl(p_chave))
     set l_documento            = .CreateParameter("l_documento",   adInteger, adParamInput,   , Tvl(p_documento))
     set l_lancamento           = .CreateParameter("l_lancamento",  adInteger, adParamInput,   , Tvl(p_lancamento))
     set l_restricao            = .CreateParameter("l_restricao",   adVarchar, adParamInput, 50, p_restricao)
     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     .parameters.Append         l_documento
     .parameters.Append         l_lancamento
     .parameters.Append         l_restricao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetImpostoIncid"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_documento"
     .parameters.Delete         "l_lancamento"
     .parameters.Delete         "l_restricao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

%>

