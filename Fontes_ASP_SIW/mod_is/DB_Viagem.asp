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
REM Verifica se o usuário informado é gestor do sistema ou do módulo ao qual
REM a solicitação pertence
REM -------------------------------------------------------------------------
Sub DB_GetCadastrador_PD(p_menu, p_usuario, p_acesso)
  ' Esta procedure faz chamada a uma função do banco de dados
    
  Dim l_menu, l_usuario, l_acesso
  
  Set l_acesso         = Server.CreateObject("ADODB.Parameter") 
  Set l_menu           = Server.CreateObject("ADODB.Parameter") 
  Set l_usuario        = Server.CreateObject("ADODB.Parameter") 

  with sp
     set l_acesso        = .CreateParameter("l_acesso",     adVarchar, adParamReturnValue, 1, null)
     set l_menu          = .CreateParameter("l_menu",       adInteger, adParamInput,    , p_menu)
     set l_usuario       = .CreateParameter("l_usuario",    adInteger, adParamInput,    , p_usuario)
     .parameters.Append  l_acesso
     .parameters.Append  l_menu
     .parameters.Append  l_usuario
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText        = Session("schema") & "PD_Cadastrador_Geral"
     On error Resume Next
     .Execute
     p_acesso = l_acesso.Value
     
     If Err.Description > "" Then 
        TrataErro
     End If
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_acesso"
     .parameters.Delete         "l_menu"
     .parameters.Delete         "l_usuario"
  end with
End Sub

REM =========================================================================
REM Recupera os deslocamentos de uma missão
REM -------------------------------------------------------------------------
Sub DB_GetPD_Deslocamento(p_rs, p_chave, p_chave_aux, p_restricao)
  Dim l_chave, l_chave_aux, l_restricao
  Set l_chave     = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux = Server.CreateObject("ADODB.Parameter")
  Set l_restricao = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave",         adInteger, adParamInput,   , p_chave)
     set l_chave_aux            = .CreateParameter("l_chave_aux",     adInteger, adParamInput,   , Tvl(p_chave_aux))
     set l_restricao            = .CreateParameter("l_restricao",     adVarchar, adParamInput, 20, p_restricao)
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_restricao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetPD_Deslocamento"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_chave_aux"
     .Parameters.Delete         "l_restricao"
  end with

End Sub
%>

