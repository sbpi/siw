<%
REM =========================================================================
REM Recupera a lista de clientes do SIW
REM -------------------------------------------------------------------------
Sub DB_GetCall(p_rs, p_chave, p_pessoa, p_tipo, p_restricao, p_sq_cc, p_contato, p_numero, p_inicio, p_fim, p_ativo)

  Dim l_chave, l_pessoa, l_tipo, l_restricao, l_sq_cc, l_contato, l_numero, l_inicio, l_fim, l_ativo
  
  Set l_chave       = Server.CreateObject("ADODB.Parameter")
  Set l_pessoa      = Server.CreateObject("ADODB.Parameter")
  Set l_tipo        = Server.CreateObject("ADODB.Parameter")
  Set l_restricao   = Server.CreateObject("ADODB.Parameter")
  Set l_sq_cc       = Server.CreateObject("ADODB.Parameter")
  Set l_contato     = Server.CreateObject("ADODB.Parameter")
  Set l_numero      = Server.CreateObject("ADODB.Parameter")
  Set l_inicio      = Server.CreateObject("ADODB.Parameter")
  Set l_fim         = Server.CreateObject("ADODB.Parameter")
  Set l_ativo       = Server.CreateObject("ADODB.Parameter")

  with sp
     set l_chave                = .CreateParameter("l_chave",       adInteger,  adParamInput,   , Tvl(p_chave))
     set l_pessoa               = .CreateParameter("l_pessoa",      adInteger,  adParamInput,   , p_pessoa)
     set l_tipo                 = .CreateParameter("l_tipo",        adInteger,  adParamInput,   , p_tipo)
     set l_restricao            = .CreateParameter("l_restricao",   adVarchar,  adParamInput, 20, Tvl(p_restricao))
     set l_sq_cc                = .CreateParameter("l_sq_cc",       adInteger,  adParamInput,   , Tvl(p_sq_cc))
     set l_contato              = .CreateParameter("l_contato",     adVarchar,  adParamInput, 60, Tvl(p_contato))
     set l_numero               = .CreateParameter("l_numero",      adVarchar,  adParamInput, 20, Tvl(p_numero))
     set l_inicio               = .CreateParameter("l_inicio",      adDate,     adParamInput,   , Tvl(p_inicio))
     set l_fim                  = .CreateParameter("l_fim",         adDate,     adParamInput,   , Tvl(p_fim))
     set l_ativo                = .CreateParameter("l_ativo",       adVarchar,  adParamInput,  1, Tvl(p_ativo))
     .parameters.Append         l_chave
     .parameters.Append         l_pessoa
     .parameters.Append         l_tipo
     .parameters.Append         l_restricao
     .parameters.Append         l_sq_cc
     .parameters.Append         l_contato
     .parameters.Append         l_numero
     .parameters.Append         l_inicio
     .parameters.Append         l_fim
     .parameters.Append         l_ativo

     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetCall"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If

     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_pessoa"
     .Parameters.Delete         "l_tipo"
     .Parameters.Delete         "l_restricao"
     .Parameters.Delete         "l_sq_cc"
     .Parameters.Delete         "l_contato"
     .Parameters.Delete         "l_numero"
     .Parameters.Delete         "l_inicio"
     .Parameters.Delete         "l_fim"
     .Parameters.Delete         "l_ativo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Grava dados da ligação
REM -------------------------------------------------------------------------
Sub DB_PutCall(p_Operacao, p_chave, p_destino, p_sq_cc, p_contato, p_assunto, p_pessoa, p_fax, p_trabalho)

  Dim l_operacao, l_chave, l_destino, l_sq_cc, l_contato, l_assunto, l_pessoa, l_fax, l_trabalho
  
  Set l_operacao    = Server.CreateObject("ADODB.Parameter")
  Set l_chave       = Server.CreateObject("ADODB.Parameter")
  Set l_destino     = Server.CreateObject("ADODB.Parameter")
  Set l_sq_cc       = Server.CreateObject("ADODB.Parameter")
  Set l_contato     = Server.CreateObject("ADODB.Parameter")
  Set l_assunto     = Server.CreateObject("ADODB.Parameter")
  Set l_pessoa      = Server.CreateObject("ADODB.Parameter")
  Set l_fax         = Server.CreateObject("ADODB.Parameter")
  Set l_trabalho    = Server.CreateObject("ADODB.Parameter")

  with sp
     set l_operacao             = .CreateParameter("l_operacao",    adVarchar,  adParamInput,   1, p_operacao)
     set l_chave                = .CreateParameter("l_chave",       adInteger,  adParamInput,    , p_chave)
     set l_destino              = .CreateParameter("l_destino",     adInteger,  adParamInput,    , Tvl(p_destino))
     set l_sq_cc                = .CreateParameter("l_sq_cc",       adInteger,  adParamInput,    , Tvl(p_sq_cc))
     set l_contato              = .CreateParameter("l_contato",     adVarchar,  adParamInput,  60, Tvl(p_contato))
     set l_assunto              = .CreateParameter("l_assunto",     adVarchar,  adParamInput,1000, Tvl(p_assunto))
     set l_pessoa               = .CreateParameter("l_pessoa",      adInteger,  adParamInput,    , Tvl(p_pessoa))
     set l_fax                  = .CreateParameter("l_fax",         adVarchar,  adParamInput,   1, Tvl(p_fax))
     set l_trabalho             = .CreateParameter("l_trabalho",    adVarchar,  adParamInput,   1, Tvl(p_trabalho))
     .parameters.Append         l_operacao
     .parameters.Append         l_chave
     .parameters.Append         l_destino
     .parameters.Append         l_sq_cc
     .parameters.Append         l_contato
     .parameters.Append         l_assunto
     .parameters.Append         l_pessoa
     .parameters.Append         l_fax
     .parameters.Append         l_trabalho

     .CommandText               = Session("schema") & "SP_PutCall"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     

     .Parameters.Delete         "l_operacao"
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_destino"
     .Parameters.Delete         "l_sq_cc"
     .Parameters.Delete         "l_contato"
     .Parameters.Delete         "l_assunto"
     .Parameters.Delete         "l_pessoa"
     .Parameters.Delete         "l_fax"
     .Parameters.Delete         "l_trabalho"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>

