<%
REM =========================================================================
REM Mantem a tabela de esquemas para importacao
REM -------------------------------------------------------------------------
Sub DML_PutEsquema(Operacao, p_cliente, p_sq_esquema, p_sq_modulo, p_nome, p_descricao, p_tipo, _
                  p_ativo, p_formato, p_ws_servidor, p_ws_url, p_ws_acao, p_ws_mensagem, p_no_raiz)
  
  Dim l_Operacao, l_cliente, l_sq_esquema, l_sq_modulo, l_nome, l_descricao
  Dim l_tipo, l_ativo, l_formato, l_ws_servidor, l_ws_url, l_ws_acao, l_ws_mensagem, l_no_raiz
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_cliente            = Server.CreateObject("ADODB.Parameter")
  Set l_sq_esquema         = Server.CreateObject("ADODB.Parameter")
  Set l_sq_modulo          = Server.CreateObject("ADODB.Parameter")
  Set l_nome               = Server.CreateObject("ADODB.Parameter")
  Set l_descricao          = Server.CreateObject("ADODB.Parameter")
  Set l_tipo               = Server.CreateObject("ADODB.Parameter")
  Set l_ativo              = Server.CreateObject("ADODB.Parameter")
  Set l_formato            = Server.CreateObject("ADODB.Parameter")
  Set l_ws_servidor        = Server.CreateObject("ADODB.Parameter")
  Set l_ws_url             = Server.CreateObject("ADODB.Parameter")
  Set l_ws_acao            = Server.CreateObject("ADODB.Parameter")
  Set l_ws_mensagem        = Server.CreateObject("ADODB.Parameter")
  Set l_no_raiz            = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao     = .CreateParameter("l_Operacao",         adVarchar, adParamInput,  10, Tvl(Operacao))
     set l_cliente      = .CreateParameter("l_cliente",          adInteger, adParamInput,    , p_cliente)
     set l_sq_esquema   = .CreateParameter("l_sq_esquema",       adInteger, adParamInput,    , tvl(p_sq_esquema))
     set l_sq_modulo    = .CreateParameter("l_sq_modulo",        adInteger, adParamInput,    , tvl(p_sq_modulo))
     set l_nome         = .CreateParameter("l_nome",             adVarchar, adParamInput,  60, Tvl(p_nome))
     set l_descricao    = .CreateParameter("l_descricao",        adVarchar, adParamInput, 500, Tvl(p_descricao))
     set l_tipo         = .CreateParameter("l_tipo",             adVarchar, adParamInput,   1, Tvl(p_tipo))
     set l_ativo        = .CreateParameter("l_ativo",            adVarchar, adParamInput,   1, Tvl(p_ativo))
     set l_formato      = .CreateParameter("l_formato",          adVarchar, adParamInput,   1, Tvl(p_formato))
     set l_ws_servidor  = .CreateParameter("l_ws_servidor",      adVarchar, adParamInput, 100, Tvl(p_ws_servidor))
     set l_ws_url       = .CreateParameter("l_ws_url",           adVarchar, adParamInput, 100, Tvl(p_ws_url))
     set l_ws_acao      = .CreateParameter("l_ws_acao",          adVarchar, adParamInput, 100, Tvl(p_ws_acao))
     set l_ws_mensagem  = .CreateParameter("l_ws_mensagem",      adVarchar, adParamInput,4000, Tvl(p_ws_mensagem))
     set l_no_raiz      = .CreateParameter("l_no_raiz",          adVarchar, adParamInput,  50, Tvl(p_no_raiz))
     
     .parameters.Append         l_Operacao
     .parameters.Append         l_cliente
     .parameters.Append         l_sq_esquema
     .parameters.Append         l_sq_modulo
     .parameters.Append         l_nome
     .parameters.Append         l_descricao
     .parameters.Append         l_tipo
     .parameters.Append         l_ativo
     .parameters.Append         l_formato
     .parameters.Append         l_ws_servidor
     .parameters.Append         l_ws_url
     .parameters.Append         l_ws_acao
     .parameters.Append         l_ws_mensagem
     .parameters.Append         l_no_raiz
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_PutEsquema"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_Operacao"
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_sq_esquema"
     .Parameters.Delete         "l_sq_modulo"
     .Parameters.Delete         "l_nome"
     .Parameters.Delete         "l_descricao"
     .Parameters.Delete         "l_tipo"
     .Parameters.Delete         "l_ativo"
     .Parameters.Delete         "l_formato"
     .Parameters.Delete         "l_ws_servidor"
     .Parameters.Delete         "l_ws_url"
     .Parameters.Delete         "l_ws_acao"
     .Parameters.Delete         "l_ws_mensagem"
     .Parameters.Delete         "l_no_raiz"
  end with

End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantem as tabelas de um esquema para importação
REM -------------------------------------------------------------------------

Sub DML_PutEsquemaTabela (Operacao, p_chave, p_sq_esquema, p_sq_tabela, p_ordem, p_elemento)

  Dim l_Operacao, l_Chave, l_sq_esquema, l_sq_tabela, l_ordem, l_elemento
  
  Set l_Operacao                = Server.CreateObject("ADODB.Parameter") 
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_esquema              = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_tabela               = Server.CreateObject("ADODB.Parameter") 
  Set l_ordem                   = Server.CreateObject("ADODB.Parameter") 
  Set l_elemento                = Server.CreateObject("ADODB.Parameter") 
  with sp
     set l_Operacao             = .CreateParameter("l_Operacao",             adVarchar, adParamInput,  10, Tvl(Operacao))
     set l_chave                = .CreateParameter("l_chave",                adInteger, adParamInput,    , Tvl(p_chave))
     set l_sq_esquema           = .CreateParameter("l_sq_esquema",           adInteger, adParamInput,    , Tvl(p_sq_esquema))
     set l_sq_tabela            = .CreateParameter("l_sq_tabela",            adInteger, adParamInput,    , Tvl(p_sq_tabela))
     set l_ordem                = .CreateParameter("l_ordem",                adInteger, adParamInput,    , Tvl(p_ordem))
     set l_elemento             = .CreateParameter("l_elemento",             adVarchar, adParamInput,  50, Tvl(p_elemento))
  
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_sq_esquema
     .parameters.Append         l_sq_tabela
     .parameters.Append         l_ordem
     .parameters.Append         l_elemento

     .CommandText               = Session("schema") & "SP_PutEsquemaTabela"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_sq_esquema"
     .parameters.Delete         "l_sq_tabela"
     .parameters.Delete         "l_ordem"
     .parameters.Delete         "l_elemento"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantem as colunas de uma tabela de um esquema para importação
REM -------------------------------------------------------------------------

Sub DML_PutEsquemaAtributo (Operacao, p_chave, p_sq_esquema_tabela, p_sq_coluna, p_ordem, p_campo_externo)

  Dim l_Operacao, l_Chave, l_sq_esquema_tabela, l_sq_coluna, l_ordem, l_campo_externo
  
  Set l_Operacao                = Server.CreateObject("ADODB.Parameter") 
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_esquema_tabela       = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_coluna               = Server.CreateObject("ADODB.Parameter") 
  Set l_ordem                   = Server.CreateObject("ADODB.Parameter") 
  Set l_campo_externo           = Server.CreateObject("ADODB.Parameter") 
  with sp
     set l_Operacao             = .CreateParameter("l_Operacao",             adVarchar, adParamInput,  10, Tvl(Operacao))
     set l_chave                = .CreateParameter("l_chave",                adInteger, adParamInput,    , Tvl(p_chave))
     set l_sq_esquema_tabela    = .CreateParameter("l_sq_esquema_tabela",    adInteger, adParamInput,    , Tvl(p_sq_esquema_tabela))
     set l_sq_coluna            = .CreateParameter("l_sq_coluna",            adInteger, adParamInput,    , Tvl(p_sq_coluna))
     set l_ordem                = .CreateParameter("l_ordem",                adInteger, adParamInput,    , Tvl(p_ordem))
     set l_campo_externo        = .CreateParameter("l_campo_externo",        adVarchar, adParamInput,  30, Tvl(p_campo_externo))
  
     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_sq_esquema_tabela
     .parameters.Append         l_sq_coluna
     .parameters.Append         l_ordem
     .parameters.Append         l_campo_externo

     .CommandText               = Session("schema") & "SP_PutEsquemaAtributo"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_sq_esquema_tabela"
     .parameters.Delete         "l_sq_coluna"
     .parameters.Delete         "l_ordem"
     .parameters.Delete         "l_campo_externo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>

