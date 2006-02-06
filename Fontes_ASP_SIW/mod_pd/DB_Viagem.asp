<%
REM =========================================================================
REM Recupera a lista de viajantes de um projeto
REM -------------------------------------------------------------------------
Sub DB_GetViagemBenef(p_rs, p_chave, p_cliente, p_pessoa, p_restricao, p_cpf, p_nome, p_dt_ini, p_dt_fim, p_chave_aux)
  Dim l_chave, l_cliente, l_pessoa, l_restricao, l_cpf, l_nome, l_dt_ini, l_dt_fim, l_chave_aux
  
  Set l_chave        = Server.CreateObject("ADODB.Parameter")
  Set l_cliente      = Server.CreateObject("ADODB.Parameter")
  Set l_pessoa       = Server.CreateObject("ADODB.Parameter")
  Set l_restricao    = Server.CreateObject("ADODB.Parameter")
  Set l_cpf          = Server.CreateObject("ADODB.Parameter")
  Set l_nome         = Server.CreateObject("ADODB.Parameter")
  Set l_dt_ini       = Server.CreateObject("ADODB.Parameter")
  Set l_dt_fim       = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux    = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave",     adInteger, adParamInput,   , Tvl(p_chave))
     set l_cliente              = .CreateParameter("l_cliente",   adInteger, adParamInput,   , p_cliente)
     set l_pessoa               = .CreateParameter("l_pessoa",    adInteger, adParamInput,   , Tvl(p_pessoa))
     set l_restricao            = .CreateParameter("l_restricao", adVarchar, adParamInput, 50, Tvl(p_restricao))
     set l_cpf                  = .CreateParameter("l_cpf",       adVarchar, adParamInput, 14, Tvl(p_cpf))
     set l_nome                 = .CreateParameter("l_nome",      adVarchar, adParamInput, 20, Tvl(p_nome))
     set l_dt_ini               = .CreateParameter("l_dt_ini",    adDate,    adParamInput,   , Tvl(p_dt_ini))
     set l_dt_fim               = .CreateParameter("l_dt_fim",    adDate,    adParamInput,   , Tvl(p_dt_fim))
     set l_chave_aux            = .CreateParameter("l_chave_aux", adInteger, adParamInput,   , Tvl(p_chave_aux))          
     .parameters.Append         l_chave
     .parameters.Append         l_cliente
     .parameters.Append         l_pessoa
     .parameters.Append         l_restricao
     .parameters.Append         l_cpf
     .parameters.Append         l_nome
     .parameters.Append         l_dt_ini
     .parameters.Append         l_dt_fim
     .parameters.Append         l_chave_aux
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetViagemBenef"
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
     .parameters.Delete         "l_cpf"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_dt_ini"
     .parameters.Delete         "l_dt_fim"
     .parameters.Delete         "l_chave_aux"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

%>

