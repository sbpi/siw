<%
REM =========================================================================
REM Recupera ações do ppa(tabela do SIGPLAN)
REM -------------------------------------------------------------------------
Sub DB_GetAcaoPPA_IS(p_rs, p_cliente, p_ano, p_programa, p_acao, p_subacao, p_unidade, p_restricao, p_chave, p_nome)
  
  Dim l_programa, l_acao, l_subacao, l_unidade, l_cliente, l_ano, l_restricao, l_chave, l_nome
  
  Set l_programa         = Server.CreateObject("ADODB.Parameter")
  Set l_acao             = Server.CreateObject("ADODB.Parameter")
  Set l_subacao          = Server.CreateObject("ADODB.Parameter")
  Set l_unidade          = Server.CreateObject("ADODB.Parameter")
  Set l_cliente          = Server.CreateObject("ADODB.Parameter")
  Set l_ano              = Server.CreateObject("ADODB.Parameter")
  Set l_restricao        = Server.CreateObject("ADODB.Parameter")
  Set l_chave            = Server.CreateObject("ADODB.Parameter")
  Set l_nome             = Server.CreateObject("ADODB.Parameter")

  with sp
     set l_cliente            = .CreateParameter("l_cliente",       adInteger, adParamInput,    , p_cliente)
     set l_ano                = .CreateParameter("l_ano",           adInteger, adParamInput,    , p_ano)
     set l_programa           = .CreateParameter("l_programa",      adVarchar, adParamInput,   4, tvl(p_programa))
     set l_acao               = .CreateParameter("l_acao",          adVarchar, adParamInput,   4, tvl(p_acao))
     set l_subacao            = .CreateParameter("l_subacao",       adVarchar, adParamInput,   4, tvl(p_subacao))
     set l_unidade            = .CreateParameter("l_unidade",       adVarchar, adParamInput,   5, tvl(p_unidade))
     set l_restricao          = .CreateParameter("l_restricao",     adVarchar, adParamInput,  30, tvl(p_restricao))
     set l_chave              = .CreateParameter("l_chave",         adInteger, adParamInput,    , tvl(p_chave))
     set l_nome               = .CreateParameter("l_nome",          adVarchar, adParamInput, 100, tvl(p_nome))
     .parameters.Append         l_cliente
     .parameters.Append         l_ano
     .parameters.Append         l_programa
     .parameters.Append         l_acao
     .parameters.Append         l_subacao
     .parameters.Append         l_unidade
     .parameters.Append         l_restricao
     .parameters.Append         l_chave
     .parameters.Append         l_nome
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema_is") & "SP_GetAcaoPPA_IS"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_ano"
     .Parameters.Delete         "l_programa"
     .Parameters.Delete         "l_acao"
     .Parameters.Delete         "l_subacao"
     .Parameters.Delete         "l_unidade"
     .Parameters.Delete         "l_restricao"
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_nome"
  end with
End Sub

REM =========================================================================
REM Recupera os programas do ppa(tabela do SIGPLAN)
REM -------------------------------------------------------------------------
Sub DB_GetProgramaPPA_IS(p_rs, p_chave, p_cliente, p_ano, p_restricao, p_nome)
  Dim l_cliente, l_ano, l_chave, l_restricao, l_nome
  
  Set l_cliente            = Server.CreateObject("ADODB.Parameter")
  Set l_ano                = Server.CreateObject("ADODB.Parameter")
  Set l_chave              = Server.CreateObject("ADODB.Parameter")
  Set l_restricao          = Server.CreateObject("ADODB.Parameter")
  Set l_nome               = Server.CreateObject("ADODB.Parameter")

  with sp
     set l_cliente            = .CreateParameter("l_cliente",            adInteger, adParamInput,    , p_cliente)
     set l_ano                = .CreateParameter("l_ano",                adInteger, adParamInput,    , p_ano)
     set l_chave              = .CreateParameter("l_chave",              adVarchar, adParamInput,   4, tvl(p_chave))
     set l_restricao          = .CreateParameter("l_restricao",          adVarchar, adParamInput,  30, tvl(p_restricao))
     set l_nome               = .CreateParameter("l_nome",               adVarchar, adParamInput, 100, tvl(p_nome))

     .parameters.Append         l_cliente
     .parameters.Append         l_ano
     .parameters.Append         l_chave
     .parameters.Append         l_restricao
     .parameters.Append         l_nome
          
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema_is") & "SP_GetProgramaPPA_IS"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_ano"
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_restricao"
     .Parameters.Delete         "l_nome"
     
  end with
End Sub

REM =========================================================================
REM Recupera as funções das ações ppa do PPA
REM -------------------------------------------------------------------------
Sub DB_GetFuncao_IS(p_rs, p_chave, p_ativo)
  
  Dim l_chave, l_ativo
  
  Set l_chave       = Server.CreateObject("ADODB.Parameter")
  Set l_ativo       = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_chave           = .CreateParameter("l_chave",        adVarchar, adParamInput,   2, tvl(p_chave))
     set l_ativo           = .CreateParameter("l_funcao",       adVarchar, adParamInput,   1, tvl(p_ativo))
     .parameters.Append         l_chave
     .parameters.Append         l_ativo
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema_is") & "SP_GetFuncao_IS"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Recupera as subfunções das ações ppa do PPA
REM -------------------------------------------------------------------------
Sub DB_GetSubFuncao_IS(p_rs, p_chave, p_funcao)
  
  Dim l_chave, l_funcao
  
  Set l_chave       = Server.CreateObject("ADODB.Parameter")
  Set l_funcao      = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_chave           = .CreateParameter("l_chave",       adVarchar, adParamInput,   3, tvl(p_chave))
     set l_funcao          = .CreateParameter("l_funcao",      adVarchar, adParamInput,   2, tvl(p_funcao))
     .parameters.Append         l_chave
     .parameters.Append         l_funcao
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema_is") & "SP_GetSubFuncao_IS"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_funcao"
  end with
End Sub

REM =========================================================================
REM Recupera as esferas das ações ppa do PPA
REM -------------------------------------------------------------------------
Sub DB_GetEsfera_IS(p_rs, p_chave, p_ativo)
  
  Dim l_chave, l_ativo
  
  Set l_chave       = Server.CreateObject("ADODB.Parameter")
  Set l_ativo       = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_chave           = .CreateParameter("l_chave",        adVarchar, adParamInput,   2, tvl(p_chave))
     set l_ativo           = .CreateParameter("l_funcao",       adVarchar, adParamInput,   1, tvl(p_ativo))
     .parameters.Append         l_chave
     .parameters.Append         l_ativo
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema_is") & "SP_GetEsfera_IS"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Recupera as os tipos de ações ppa do SIGPLAN
REM -------------------------------------------------------------------------
Sub DB_GetTipoAcao_IS(p_rs, p_chave, p_ativo)
  
  Dim l_chave, l_ativo
  
  Set l_chave       = Server.CreateObject("ADODB.Parameter")
  Set l_ativo       = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_chave           = .CreateParameter("l_chave",        adVarchar, adParamInput,   2, tvl(p_chave))
     set l_ativo           = .CreateParameter("l_funcao",       adVarchar, adParamInput,   1, tvl(p_ativo))
     .parameters.Append         l_chave
     .parameters.Append         l_ativo
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema_is") & "SP_GetTipoAcao_IS"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Recupera ações do ppa
REM -------------------------------------------------------------------------
Sub DB_GetNatureza_IS(p_rs, p_chave, p_cliente, p_nome, p_ativo)

  Dim l_chave, l_cliente, l_nome, l_ativo
  
  Set l_chave   = Server.CreateObject("ADODB.Parameter")
  Set l_cliente = Server.CreateObject("ADODB.Parameter")
  Set l_nome    = Server.CreateObject("ADODB.Parameter")
  Set l_ativo   = Server.CreateObject("ADODB.Parameter")
  
  with sp
       
     set l_chave   = .CreateParameter("l_chave"  , adInteger, adParamInput,    , Tvl(p_chave))
     set l_cliente = .CreateParameter("l_cliente", adInteger, adParamInput,    , Tvl(p_cliente))
     set l_nome    = .CreateParameter("l_nome"   , adVarchar, adParamInput,  30, Tvl(p_nome))
     set l_ativo   = .CreateParameter("l_ativo"  , adVarchar, adParamInput,   1, Tvl(p_ativo))
     
     .parameters.Append l_chave
     .parameters.Append l_cliente
     .parameters.Append l_nome
     .parameters.Append l_ativo
     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema_is") & "SP_GetNatureza_IS"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_nome"
     .Parameters.Delete         "l_ativo"
     
  end with
End Sub

REM =========================================================================
REM Recupera ações do ppa
REM -------------------------------------------------------------------------
Sub DB_GetHorizonte_IS(p_rs, p_chave, p_cliente, p_nome, p_ativo)
  
  Dim l_chave, l_cliente, l_nome, l_ativo
  
  Set l_chave   = Server.CreateObject("ADODB.Parameter")
  Set l_cliente = Server.CreateObject("ADODB.Parameter")
  Set l_nome    = Server.CreateObject("ADODB.Parameter")
  Set l_ativo   = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_chave   = .CreateParameter("l_chave"  , adInteger, adParamInput,    , tvl(p_chave))
     set l_cliente = .CreateParameter("l_cliente", adInteger, adParamInput,    , tvl(p_cliente))
     set l_nome    = .CreateParameter("l_nome"   , adVarchar, adParamInput,  30, Tvl(p_nome))
     set l_ativo   = .CreateParameter("l_ativo"  , adVarchar, adParamInput,   1, Tvl(p_ativo))
     
     .parameters.Append         l_chave
     .parameters.Append         l_cliente
     .parameters.Append         l_nome
     .parameters.Append         l_ativo
     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema_is") & "SP_GetHorizonte_IS"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_nome"
     .Parameters.Delete         "l_ativo"
  end with
End Sub

REM =========================================================================
REM Recupera o projetos/planos
REM -------------------------------------------------------------------------
Sub DB_GetProjeto_IS(p_rs, p_chave, p_cliente, p_codigo, p_nome, p_responsavel, p_telefone, p_email, p_ordem, p_ativo, p_padrao, p_selecao_mp, p_selecao_se, p_restricao, p_siw_solic)

  Dim l_chave, l_cliente, l_codigo, l_nome, l_responsavel, l_telefone, l_email, l_ordem, l_ativo, l_padrao, l_selecao_mp, l_selecao_se, l_restricao, l_siw_solic
  
  Set l_chave       = Server.CreateObject("ADODB.Parameter")
  Set l_cliente     = Server.CreateObject("ADODB.Parameter")
  Set l_codigo      = Server.CreateObject("ADODB.Parameter")
  Set l_nome        = Server.CreateObject("ADODB.Parameter")
  Set l_responsavel = Server.CreateObject("ADODB.Parameter")
  Set l_telefone    = Server.CreateObject("ADODB.Parameter")
  Set l_email       = Server.CreateObject("ADODB.Parameter")  
  Set l_ordem       = Server.CreateObject("ADODB.Parameter")
  Set l_ativo       = Server.CreateObject("ADODB.Parameter")
  Set l_padrao      = Server.CreateObject("ADODB.Parameter")
  Set l_selecao_mp  = Server.CreateObject("ADODB.Parameter")  
  Set l_selecao_se  = Server.CreateObject("ADODB.Parameter")
  Set l_restricao   = Server.CreateObject("ADODB.Parameter")
  Set l_siw_solic   = Server.CreateObject("ADODB.Parameter")
  
  with sp
       
     set l_chave        = .CreateParameter("l_chave"       , adInteger, adParamInput,    , Tvl(p_chave))
     set l_cliente      = .CreateParameter("l_cliente"     , adInteger, adParamInput,    , Tvl(p_cliente))
     set l_codigo       = .CreateParameter("l_codigo"      , adVarchar, adParamInput,  50, Tvl(p_codigo))
     set l_nome         = .CreateParameter("l_nome"        , adVarchar, adParamInput, 100, Tvl(p_nome))
     set l_responsavel  = .CreateParameter("l_responsavel" , adVarchar, adParamInput,  60, Tvl(p_responsavel))
     set l_telefone     = .CreateParameter("l_telefone"    , adVarchar, adParamInput,  20, Tvl(p_telefone))
     set l_email        = .CreateParameter("l_email"       , adVarchar, adParamInput,  60, Tvl(p_email))
     set l_ordem        = .CreateParameter("l_ordem"       , adInteger, adParamInput,    , Tvl(p_ordem))
     set l_ativo        = .CreateParameter("l_ativo"       , adVarchar, adParamInput,   1, Tvl(p_ativo))
     set l_padrao       = .CreateParameter("l_padrao"      , adVarchar, adParamInput,   1, Tvl(p_padrao))
     set l_selecao_mp   = .CreateParameter("l_selecao_mp"  , adVarchar, adParamInput,   1, Tvl(p_selecao_mp))
     set l_selecao_se   = .CreateParameter("l_selecao_se"  , adVarchar, adParamInput,   1, Tvl(p_selecao_se))
     set l_restricao    = .CreateParameter("l_restricao"   , adVarchar, adParamInput,  30, Tvl(p_restricao))
     set l_siw_solic    = .CreateParameter("l_siw_solic"   , adInteger, adParamInput,    , Tvl(p_siw_solic))
          
     .parameters.Append l_chave
     .parameters.Append l_cliente
     .parameters.Append l_codigo
     .parameters.Append l_nome
     .parameters.Append l_responsavel
     .parameters.Append l_telefone
     .parameters.Append l_email
     .parameters.Append l_ordem
     .parameters.Append l_ativo
     .parameters.Append l_padrao
     .parameters.Append l_selecao_mp
     .parameters.Append l_selecao_se
     .parameters.Append l_restricao
     .parameters.Append l_siw_solic
     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema_is") & "SP_GetProjeto_IS"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     
     .parameters.Delete "l_chave"
     .parameters.Delete "l_cliente"
     .parameters.Delete "l_codigo"
     .parameters.Delete "l_nome"
     .parameters.Delete "l_responsavel"
     .parameters.Delete "l_telefone"
     .parameters.Delete "l_email"
     .parameters.Delete "l_ordem"
     .parameters.Delete "l_ativo"
     .parameters.Delete "l_padrao"
     .parameters.Delete "l_selecao_mp"
     .parameters.Delete "l_selecao_se"          
     .parameters.Delete "l_restricao"
     .parameters.Delete "l_siw_solic"
  end with
End Sub

REM =========================================================================
REM Recupera as unidade do modulo infra-sig
REM -------------------------------------------------------------------------
Sub DB_GetIsUnidade_IS(p_rs, p_chave, p_cliente)
  Dim l_chave, l_cliente
  Set l_chave       = Server.CreateObject("ADODB.Parameter")
  Set l_cliente     = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave           = .CreateParameter("l_chave",        adInteger, adParamInput,   , tvl(p_chave))
     set l_cliente         = .CreateParameter("l_cliente",      adInteger, adParamInput,   , p_cliente)
     .parameters.Append         l_chave
     .parameters.Append         l_cliente
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema_is") & "SP_GetIsUnidade_IS"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_cliente"
  end with
End Sub

REM =========================================================================
REM Recupera as unidade do modulo infra-sig
REM -------------------------------------------------------------------------
Sub DB_GetIsUnidadeLimite_IS(p_rs, p_chave, p_ano, p_cliente)
  Dim l_chave, l_ano, l_cliente
  Set l_chave       = Server.CreateObject("ADODB.Parameter")
  Set l_ano         = Server.CreateObject("ADODB.Parameter")
  Set l_cliente     = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave           = .CreateParameter("l_chave",        adInteger, adParamInput,   , tvl(p_chave))
     set l_ano             = .CreateParameter("l_ano",          adInteger, adParamInput,   , tvl(p_ano))
     set l_cliente         = .CreateParameter("l_cliente",      adInteger, adParamInput,   , p_cliente)
     .parameters.Append         l_chave
     .parameters.Append         l_ano
     .parameters.Append         l_cliente
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema_is") & "SP_GetIsUnidadeLimite_IS"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_ano"
     .Parameters.Delete         "l_cliente"
  end with
End Sub

REM =========================================================================
REM Recupera ações de financiamento de uma ação específica
REM -------------------------------------------------------------------------
Sub DB_GetFinancAcaoPPA_IS(p_rs, p_chave, p_cliente, p_ano, p_programa, p_acao, p_subacao)
  
  Dim l_chave, l_cliente, l_ano, l_cd_programa, l_cd_acao, l_cd_subacao
  
  Set l_chave       = Server.CreateObject("ADODB.Parameter")
  Set l_cliente     = Server.CreateObject("ADODB.Parameter")
  Set l_ano         = Server.CreateObject("ADODB.Parameter")
  Set l_cd_programa = Server.CreateObject("ADODB.Parameter")
  Set l_cd_acao     = Server.CreateObject("ADODB.Parameter")
  Set l_cd_subacao  = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_chave           = .CreateParameter("l_chave",        adInteger, adParamInput,    , p_chave)
     set l_cliente         = .CreateParameter("l_cliente",      adInteger, adParamInput,    , p_cliente)
     set l_ano             = .CreateParameter("l_ano",          adInteger, adParamInput,    , p_ano)
     set l_cd_programa     = .CreateParameter("l_cd_programa",  adVarchar, adParamInput,   4, tvl(p_programa))
     set l_cd_acao         = .CreateParameter("l_cd_acao",      adVarchar, adParamInput,   4, tvl(p_acao))
     set l_cd_subacao      = .CreateParameter("l_cd_subacao",   adVarchar, adParamInput,   4, tvl(p_subacao))
     
     .parameters.Append         l_chave
     .parameters.Append         l_cliente
     .parameters.Append         l_ano
     .parameters.Append         l_cd_programa
     .parameters.Append         l_cd_acao
     .parameters.Append         l_cd_subacao
     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema_is") & "SP_GetFinacAcaoPPA_IS"
     Set p_rs = Server.CreateObject("ADODB.RecordSet")
     p_rs.cursortype            = adOpenStatic
     p_rs.cursorlocation        = adUseClient
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .Parameters.Delete         "l_chave"
     .Parameters.Delete         "l_cliente"
     .Parameters.Delete         "l_ano"
     .Parameters.Delete         "l_cd_programa"
     .Parameters.Delete         "l_cd_acao"
     .Parameters.Delete         "l_cd_subacao"
  end with
End Sub
%>

