<%
REM =========================================================================
REM Recupera a lista de acordos do cliente
REM -------------------------------------------------------------------------
Sub DB_GetAgree(p_rs, p_chave, p_cliente, p_restricao)
  Dim l_chave, l_cliente, l_restricao
  Set l_chave        = Server.CreateObject("ADODB.Parameter")
  Set l_cliente      = Server.CreateObject("ADODB.Parameter")
  Set l_restricao    = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave",       adInteger, adParamInput,   , Tvl(p_chave))
     set l_cliente              = .CreateParameter("l_cliente",     adInteger, adParamInput,   , p_cliente)
     set l_restricao            = .CreateParameter("l_restricao",   adVarchar, adParamInput, 50, p_restricao)
     .parameters.Append         l_chave
     .parameters.Append         l_cliente
     .parameters.Append         l_restricao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetAgree"
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

REM =========================================================================
REM Recupera a lista de representantes de um acordo
REM -------------------------------------------------------------------------
Sub DB_GetAcordoRep(p_rs, p_chave, p_cliente, p_pessoa, p_restricao)
  Dim l_chave, l_cliente, l_pessoa, l_restricao
  Set l_chave        = Server.CreateObject("ADODB.Parameter")
  Set l_cliente      = Server.CreateObject("ADODB.Parameter")
  Set l_pessoa       = Server.CreateObject("ADODB.Parameter")
  Set l_restricao    = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave",       adInteger, adParamInput,   , Tvl(p_chave))
     set l_cliente              = .CreateParameter("l_cliente",     adInteger, adParamInput,   , p_cliente)
     set l_pessoa              = .CreateParameter("l_pessoa",     adInteger, adParamInput,   , p_pessoa)
     set l_restricao            = .CreateParameter("l_restricao",   adVarchar, adParamInput, 50, p_restricao)
     .parameters.Append         l_chave
     .parameters.Append         l_cliente
     .parameters.Append         l_pessoa
     .parameters.Append         l_restricao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetAcordoRep"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_pessoa"
     .parameters.Delete         "l_restricao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera a lista de acordos do cliente
REM -------------------------------------------------------------------------
Sub DB_GetAcordoParcela(p_rs, p_chave, p_chave_aux, p_restricao, p_outra_parte, p_dt_ini, p_dt_fim, p_usuario, p_fase, p_menu)
  Dim l_chave, l_chave_aux, l_restricao, l_outra_parte, l_dt_ini, l_dt_fim, l_usuario, l_fase, l_menu
  Set l_chave        = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux    = Server.CreateObject("ADODB.Parameter")
  Set l_restricao    = Server.CreateObject("ADODB.Parameter")
  Set l_outra_parte  = Server.CreateObject("ADODB.Parameter")
  Set l_dt_ini       = Server.CreateObject("ADODB.Parameter")
  Set l_dt_fim       = Server.CreateObject("ADODB.Parameter")
  Set l_usuario      = Server.CreateObject("ADODB.Parameter")
  Set l_fase         = Server.CreateObject("ADODB.Parameter")
  Set l_menu         = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave",       adInteger, adParamInput,   , Tvl(p_chave))
     set l_chave_aux            = .CreateParameter("l_chave_aux",   adInteger, adParamInput,   , Tvl(p_chave_aux))
     set l_restricao            = .CreateParameter("l_restricao",   adVarchar, adParamInput, 50, Tvl(p_restricao))
     set l_outra_parte          = .CreateParameter("l_outra_parte", adVarchar, adParamInput, 60, Tvl(p_outra_parte))
     set l_dt_ini               = .CreateParameter("l_dt_ini",      adDate,    adParamInput,   , Tvl(p_dt_ini))
     set l_dt_fim               = .CreateParameter("l_dt_fim",      adDate,    adParamInput,   , Tvl(p_dt_fim))
     set l_usuario              = .CreateParameter("l_usuario",     adInteger, adParamInput,   , Tvl(p_usuario))
     set l_fase                 = .CreateParameter("l_fase",        adVarchar, adParamInput, 20, Tvl(p_fase))
     set l_menu                 = .CreateParameter("l_menu",        adInteger, adParamInput,   , Tvl(p_menu))
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_restricao
     .parameters.Append         l_outra_parte
     .parameters.Append         l_dt_ini
     .parameters.Append         l_dt_fim
     .parameters.Append         l_usuario
     .parameters.Append         l_fase
     .parameters.Append         l_menu
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetAcordoParcela"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_chave_aux"
     .parameters.Delete         "l_restricao"
     .parameters.Delete         "l_outra_parte"
     .parameters.Delete         "l_dt_ini"
     .parameters.Delete         "l_dt_fim"
     .parameters.Delete         "l_usuario"
     .parameters.Delete         "l_fase"
     .parameters.Delete         "l_menu"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

%>

