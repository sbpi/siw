<%
REM =========================================================================
REM Recupera as modalidades de contratação
REM -------------------------------------------------------------------------
Sub DB_GetGPModalidade(p_rs, p_cliente, p_chave, p_sigla, p_nome, p_ativo, p_chave_aux, p_restricao)
  
  Dim l_cliente, l_chave, l_sigla, l_nome, l_ativo, l_chave_aux, l_restricao
  
  Set l_cliente      = Server.CreateObject("ADODB.Parameter")
  Set l_chave        = Server.CreateObject("ADODB.Parameter")
  Set l_sigla        = Server.CreateObject("ADODB.Parameter")
  Set l_nome         = Server.CreateObject("ADODB.Parameter")
  Set l_ativo        = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux    = Server.CreateObject("ADODB.Parameter")
  Set l_restricao    = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_cliente   = .CreateParameter("l_cliente",   adInteger, adParamInput,   , p_cliente)
     set l_chave     = .CreateParameter("l_chave",     adInteger, adParamInput,   , Tvl(p_chave))
     set l_sigla     = .CreateParameter("l_sigla",     adVarchar, adParamInput, 10, Tvl(p_sigla))
     set l_nome      = .CreateParameter("l_nome",      adVarchar, adParamInput, 30, Tvl(p_nome))
     set l_ativo     = .CreateParameter("l_ativo",     adVarchar, adParamInput,  1, Tvl(p_ativo))
     set l_chave_aux = .CreateParameter("l_chave_aux", adInteger, adParamInput,   , Tvl(p_chave_aux))
     set l_restricao = .CreateParameter("l_restricao", adVarchar, adParamInput, 20, Tvl(p_restricao))
     
     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     .parameters.Append         l_sigla
     .parameters.Append         l_nome
     .parameters.Append         l_ativo
     .parameters.Append         l_chave_aux
     .parameters.Append         l_restricao

     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetGPModalidade"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_sigla"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ativo"
     .parameters.Delete         "l_chave_aux"
     .parameters.Delete         "l_restricao"

  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os tipos de afastamento
REM -------------------------------------------------------------------------
Sub DB_GetGPTipoAfast(p_rs, p_cliente, p_chave, p_sigla, p_nome, p_ativo, p_chave_aux, p_restricao)
  
  Dim l_cliente, l_chave, l_sigla, l_nome, l_ativo, l_chave_aux, l_restricao
  
  Set l_cliente      = Server.CreateObject("ADODB.Parameter")
  Set l_chave        = Server.CreateObject("ADODB.Parameter")
  Set l_sigla        = Server.CreateObject("ADODB.Parameter")
  Set l_nome         = Server.CreateObject("ADODB.Parameter")
  Set l_ativo        = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux    = Server.CreateObject("ADODB.Parameter")
  Set l_restricao    = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_cliente   = .CreateParameter("l_cliente",   adInteger, adParamInput,   , p_cliente)
     set l_chave     = .CreateParameter("l_chave",     adInteger, adParamInput,   , Tvl(p_chave))
     set l_sigla     = .CreateParameter("l_sigla",     adVarchar, adParamInput,  2, Tvl(p_sigla))
     set l_nome      = .CreateParameter("l_nome",      adVarchar, adParamInput, 50, Tvl(p_nome))
     set l_ativo     = .CreateParameter("l_ativo",     adVarchar, adParamInput,  1, Tvl(p_ativo))
     set l_chave_aux = .CreateParameter("l_chave_aux", adInteger, adParamInput,   , Tvl(p_chave_aux))
     set l_restricao = .CreateParameter("l_restricao", adVarchar, adParamInput, 20, Tvl(p_restricao))
     
     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     .parameters.Append         l_sigla
     .parameters.Append         l_nome
     .parameters.Append         l_ativo
     .parameters.Append         l_chave_aux
     .parameters.Append         l_restricao

     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetGPTipoAfast"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_sigla"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ativo"
     .parameters.Delete         "l_chave_aux"
     .parameters.Delete         "l_restricao"

  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera as datas especiais
REM -------------------------------------------------------------------------
Sub DB_GetDataEspecial(p_rs, p_cliente, p_chave, p_ano, p_ativo, p_tipo, p_chave_aux, p_restricao)
  
  Dim l_cliente, l_chave, l_ano, l_ativo, l_tipo, l_chave_aux, l_restricao
  
  Set l_cliente      = Server.CreateObject("ADODB.Parameter")
  Set l_chave        = Server.CreateObject("ADODB.Parameter")
  Set l_ano          = Server.CreateObject("ADODB.Parameter")
  Set l_ativo        = Server.CreateObject("ADODB.Parameter")
  Set l_tipo         = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux    = Server.CreateObject("ADODB.Parameter")
  Set l_restricao    = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_cliente   = .CreateParameter("l_cliente",   adInteger, adParamInput,   , p_cliente)
     set l_chave     = .CreateParameter("l_chave",     adInteger, adParamInput,   , Tvl(p_chave))
     set l_ano       = .CreateParameter("l_ano",       adInteger, adParamInput,   , Tvl(p_ano))
     set l_ativo     = .CreateParameter("l_ativo",     adVarchar, adParamInput,  1, Tvl(p_ativo))
     set l_tipo      = .CreateParameter("l_tipo",      adVarchar, adParamInput,  1, Tvl(p_tipo))
     set l_chave_aux = .CreateParameter("l_chave_aux", adInteger, adParamInput,   , Tvl(p_chave_aux))
     set l_restricao = .CreateParameter("l_restricao", adVarchar, adParamInput, 20, Tvl(p_restricao))
     
     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     .parameters.Append         l_ano
     .parameters.Append         l_ativo
     .parameters.Append         l_tipo
     .parameters.Append         l_chave_aux
     .parameters.Append         l_restricao

     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetDataEspecial"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_ano"
     .parameters.Delete         "l_ativo"
     .parameters.Delete         "l_tipo"
     .parameters.Delete         "l_chave_aux"
     .parameters.Delete         "l_restricao"

  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os parametros
REM -------------------------------------------------------------------------
Sub DB_GetGPParametro(p_rs, p_cliente, p_chave_aux, p_restricao)
  
  Dim l_cliente, l_chave_aux, l_restricao
  
  Set l_cliente      = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux    = Server.CreateObject("ADODB.Parameter")
  Set l_restricao    = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_cliente   = .CreateParameter("l_cliente",   adInteger, adParamInput,   , p_cliente)
     set l_chave_aux = .CreateParameter("l_chave_aux", adInteger, adParamInput,   , Tvl(p_chave_aux))
     set l_restricao = .CreateParameter("l_restricao", adVarchar, adParamInput, 20, Tvl(p_restricao))
     
     .parameters.Append         l_cliente
     .parameters.Append         l_chave_aux
     .parameters.Append         l_restricao

     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetGPParametro"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_chave_aux"
     .parameters.Delete         "l_restricao"

  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os afastamentos
REM -------------------------------------------------------------------------
Sub DB_GetAfastamento(p_rs, p_cliente, p_chave, p_sq_tipo_afastamento, p_sq_contrato_colaborador, _
                      p_inicio_data, p_fim_data, p_periodo_inicio, p_periodo_fim, p_chave_aux, p_restricao)
  
  Dim l_cliente, l_chave, l_sq_tipo_afastamento, l_sq_contrato_colaborador
  Dim l_inicio_data, l_fim_data, l_periodo_inicio, l_periodo_fim, l_chave_aux, l_restricao
  
  Set l_cliente                  = Server.CreateObject("ADODB.Parameter")
  Set l_chave                    = Server.CreateObject("ADODB.Parameter")
  Set l_sq_tipo_afastamento      = Server.CreateObject("ADODB.Parameter")
  Set l_sq_contrato_colaborador  = Server.CreateObject("ADODB.Parameter")
  Set l_inicio_data              = Server.CreateObject("ADODB.Parameter")
  Set l_fim_data                 = Server.CreateObject("ADODB.Parameter")
  Set l_periodo_inicio           = Server.CreateObject("ADODB.Parameter")
  Set l_periodo_fim              = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux                = Server.CreateObject("ADODB.Parameter")
  Set l_restricao                = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_cliente                  = .CreateParameter("l_cliente",                 adInteger, adParamInput,   , p_cliente)
     set l_chave                    = .CreateParameter("l_chave",                   adInteger, adParamInput,   , Tvl(p_chave))
     set l_sq_tipo_afastamento      = .CreateParameter("l_sq_tipo_afastamento",     adInteger, adParamInput,   , Tvl(p_sq_tipo_afastamento))
     set l_sq_contrato_colaborador  = .CreateParameter("l_sq_contrato_colaborador", adInteger, adParamInput,   , Tvl(p_sq_contrato_colaborador))
     set l_inicio_data              = .CreateParameter("l_inicio_data",             adDate,    adParamInput,   , Tvl(p_inicio_data))
     set l_fim_data                 = .CreateParameter("l_fim_data",                adDate,    adParamInput,   , Tvl(p_fim_data))
     set l_periodo_inicio           = .CreateParameter("l_periodo_inicio",          adVarchar, adParamInput,  1, Tvl(p_periodo_inicio))
     set l_periodo_fim              = .CreateParameter("l_periodo_fim",             adVarchar, adParamInput,  1, Tvl(p_periodo_fim))
     set l_chave_aux                = .CreateParameter("l_chave_aux",               adInteger, adParamInput,   , Tvl(p_chave_aux))
     set l_restricao                = .CreateParameter("l_restricao",               adVarchar, adParamInput, 20, Tvl(p_restricao))
     
     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     .parameters.Append         l_sq_tipo_afastamento
     .parameters.Append         l_sq_contrato_colaborador
     .parameters.Append         l_inicio_data
     .parameters.Append         l_fim_data
     .parameters.Append         l_periodo_inicio
     .parameters.Append         l_periodo_fim
     .parameters.Append         l_chave_aux
     .parameters.Append         l_restricao

     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetAfastamento"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_sq_tipo_afastamento"
     .parameters.Delete         "l_sq_contrato_colaborador"
     .parameters.Delete         "l_inicio_data"
     .parameters.Delete         "l_fim_data"
     .parameters.Delete         "l_periodo_inicio"
     .parameters.Delete         "l_periodo_fim"
     .parameters.Delete         "l_chave_aux"
     .parameters.Delete         "l_restricao"

  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os colaboradores
REM -------------------------------------------------------------------------
Sub DB_GetGPColaborador(p_rs, p_cliente, p_chave, p_nome, p_ativo, p_modalidade_contrato, p_unidade_lotacao, _
                        p_filhos_lotacao, p_unidade_exercicio, p_filhos_exercicio, p_afastamento, p_dt_ini, _
                        p_dt_fim, p_ferias, p_viagem, p_chave_aux, p_restricao)
  
  Dim l_cliente, l_chave, l_nome, l_ativo, l_modalidade_contrato, l_unidade_lotacao, l_filhos_lotacao
  Dim l_unidade_exercicio, l_filhos_exercicio, l_afastamento, l_dt_ini, l_dt_fim, l_ferias, l_viagem, l_chave_aux, l_restricao
  
  Set l_cliente             = Server.CreateObject("ADODB.Parameter")
  Set l_chave               = Server.CreateObject("ADODB.Parameter")
  Set l_nome                = Server.CreateObject("ADODB.Parameter")
  Set l_ativo               = Server.CreateObject("ADODB.Parameter")
  Set l_modalidade_contrato = Server.CreateObject("ADODB.Parameter")
  Set l_unidade_lotacao     = Server.CreateObject("ADODB.Parameter")
  Set l_filhos_lotacao      = Server.CreateObject("ADODB.Parameter")
  Set l_unidade_exercicio   = Server.CreateObject("ADODB.Parameter")
  Set l_filhos_exercicio    = Server.CreateObject("ADODB.Parameter")
  Set l_afastamento         = Server.CreateObject("ADODB.Parameter")
  Set l_dt_ini              = Server.CreateObject("ADODB.Parameter")
  Set l_dt_fim              = Server.CreateObject("ADODB.Parameter")
  Set l_ferias              = Server.CreateObject("ADODB.Parameter")
  Set l_viagem              = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux           = Server.CreateObject("ADODB.Parameter")
  Set l_restricao           = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_cliente               = .CreateParameter("l_cliente",              adInteger, adParamInput,   , p_cliente)
     set l_chave                 = .CreateParameter("l_chave",                adInteger, adParamInput,   , Tvl(p_chave))
     set l_nome                  = .CreateParameter("l_nome",                 adVarchar, adParamInput, 60, Tvl(p_nome))
     set l_ativo                 = .CreateParameter("l_ativo",                adVarchar, adParamInput,  1, Tvl(p_ativo))
     set l_modalidade_contrato   = .CreateParameter("l_modalidade_contrato",  adInteger, adParamInput,   , Tvl(p_modalidade_contrato))
     set l_unidade_lotacao       = .CreateParameter("l_unidade_lotacao",      adInteger, adParamInput,   , Tvl(p_unidade_lotacao))
     set l_filhos_lotacao        = .CreateParameter("l_filhos_lotacao",       adVarchar, adParamInput,  1, Tvl(p_filhos_lotacao))
     set l_unidade_exercicio     = .CreateParameter("l_unidade_exercicio",    adInteger, adParamInput,   , Tvl(p_unidade_exercicio))
     set l_filhos_exercicio      = .CreateParameter("l_filhos_exercicio",     adVarchar, adParamInput,  1, Tvl(p_filhos_exercicio))     
     set l_afastamento           = .CreateParameter("l_afastamento",          adVarchar, adParamInput,1000,Tvl(p_afastamento))
     set l_dt_ini                = .CreateParameter("l_dt_ini",               adDate,    adParamInput,   , Tvl(p_dt_ini))
     set l_dt_fim                = .CreateParameter("l_dt_fim",               adDate,    adParamInput,   , Tvl(p_dt_fim))          
     set l_ferias                = .CreateParameter("l_ferias",               adVarchar, adParamInput,  1, Tvl(p_ferias))
     set l_viagem                = .CreateParameter("l_viagem",               adVarchar, adParamInput,  1, Tvl(p_viagem))          
     set l_chave_aux             = .CreateParameter("l_chave_aux",            adInteger, adParamInput,   , Tvl(p_chave_aux))
     set l_restricao             = .CreateParameter("l_restricao",            adVarchar, adParamInput, 20, Tvl(p_restricao))
     
     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     .parameters.Append         l_nome
     .parameters.Append         l_ativo
     .parameters.Append         l_modalidade_contrato
     .parameters.Append         l_unidade_lotacao
     .parameters.Append         l_filhos_lotacao
     .parameters.Append         l_unidade_exercicio
     .parameters.Append         l_filhos_exercicio     
     .parameters.Append         l_afastamento
     .parameters.Append         l_dt_ini
     .parameters.Append         l_dt_fim               
     .parameters.Append         l_ferias   
     .parameters.Append         l_viagem   
     .parameters.Append         l_chave_aux
     .parameters.Append         l_restricao

     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetGPColaborador"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_ativo"
     .parameters.Delete         "l_modalidade_contrato"
     .parameters.Delete         "l_unidade_lotacao"
     .parameters.Delete         "l_filhos_lotacao"
     .parameters.Delete         "l_unidade_exercicio"
     .parameters.Delete         "l_filhos_exercicio"
     .parameters.Delete         "l_afastamento"
     .parameters.Delete         "l_dt_ini"
     .parameters.Delete         "l_dt_fim"
     .parameters.Delete         "l_ferias"
     .parameters.Delete         "l_viagem"
     .parameters.Delete         "l_chave_aux"
     .parameters.Delete         "l_restricao"

  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os Cargos
REM -------------------------------------------------------------------------
Sub DB_GetCargo(p_rs, p_cliente, p_chave, p_tipo, p_nome, p_formacao, p_ativo, p_restricao)
  
  Dim l_cliente, l_chave, l_tipo, l_nome, l_formacao,l_ativo,  l_restricao
  
  Set l_cliente      = Server.CreateObject("ADODB.Parameter")
  Set l_chave        = Server.CreateObject("ADODB.Parameter")
  Set l_tipo        = Server.CreateObject("ADODB.Parameter")
  Set l_nome         = Server.CreateObject("ADODB.Parameter")
  Set l_formacao    = Server.CreateObject("ADODB.Parameter")
  Set l_ativo        = Server.CreateObject("ADODB.Parameter")
  Set l_restricao    = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_cliente   = .CreateParameter("l_cliente",   adInteger, adParamInput,   , p_cliente)
     set l_chave     = .CreateParameter("l_chave",     adInteger, adParamInput,   , Tvl(p_chave))
     set l_tipo      = .CreateParameter("l_tipo",      adInteger, adParamInput,   , Tvl(p_tipo))
     set l_nome      = .CreateParameter("l_nome",      adVarchar, adParamInput, 30, Tvl(p_nome))
     set l_formacao  = .CreateParameter("l_formacao",  adInteger, adParamInput,   , Tvl(p_formacao))
     set l_ativo     = .CreateParameter("l_ativo",     adVarchar, adParamInput,  1, Tvl(p_ativo))
     set l_restricao = .CreateParameter("l_restricao", adVarchar, adParamInput, 20, Tvl(p_restricao))
     
     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     .parameters.Append         l_tipo
     .parameters.Append         l_nome
     .parameters.Append         l_formacao
     .parameters.Append         l_ativo
     .parameters.Append         l_restricao

     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetCargo"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_tipo"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_formacao"
     .parameters.Delete         "l_ativo"
     .parameters.Delete         "l_restricao"

  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Recupera os contratos dos colaboradores
REM -------------------------------------------------------------------------
Sub DB_GetGPContrato(p_rs, p_cliente, p_chave, p_sq_pessoa, p_modalidade_contrato, p_unidade_lotacao, _
                     p_filhos_lotacao, p_unidade_exercicio, p_filhos_exercicio, p_afastamento, p_dt_ini, _
                     p_dt_fim, p_chave_aux, p_restricao)
  
  Dim l_cliente, l_chave, l_sq_pessoa, l_modalidade_contrato, l_unidade_lotacao, l_filhos_lotacao
  Dim l_unidade_exercicio, l_filhos_exercicio, l_afastamento, l_dt_ini, l_dt_fim, l_chave_aux, l_restricao
  
  Set l_cliente             = Server.CreateObject("ADODB.Parameter")
  Set l_chave               = Server.CreateObject("ADODB.Parameter")
  Set l_sq_pessoa           = Server.CreateObject("ADODB.Parameter")
  Set l_modalidade_contrato = Server.CreateObject("ADODB.Parameter")
  Set l_unidade_lotacao     = Server.CreateObject("ADODB.Parameter")
  Set l_filhos_lotacao      = Server.CreateObject("ADODB.Parameter")
  Set l_unidade_exercicio   = Server.CreateObject("ADODB.Parameter")
  Set l_filhos_exercicio    = Server.CreateObject("ADODB.Parameter")
  Set l_afastamento         = Server.CreateObject("ADODB.Parameter")
  Set l_dt_ini              = Server.CreateObject("ADODB.Parameter")
  Set l_dt_fim              = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux           = Server.CreateObject("ADODB.Parameter")
  Set l_restricao           = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_cliente               = .CreateParameter("l_cliente",              adInteger, adParamInput,   , p_cliente)
     set l_chave                 = .CreateParameter("l_chave",                adInteger, adParamInput,   , Tvl(p_chave))
     set l_sq_pessoa             = .CreateParameter("l_sq_pessoa",            adInteger, adParamInput,   , Tvl(p_sq_pessoa))
     set l_modalidade_contrato   = .CreateParameter("l_modalidade_contrato",  adInteger, adParamInput,   , Tvl(p_modalidade_contrato))
     set l_unidade_lotacao       = .CreateParameter("l_unidade_lotacao",      adInteger, adParamInput,   , Tvl(p_unidade_lotacao))
     set l_filhos_lotacao        = .CreateParameter("l_filhos_lotacao",       adVarchar, adParamInput,  1, Tvl(p_filhos_lotacao))
     set l_unidade_exercicio     = .CreateParameter("l_unidade_exercicio",    adInteger, adParamInput,   , Tvl(p_unidade_exercicio))
     set l_filhos_exercicio      = .CreateParameter("l_filhos_exercicio",     adVarchar, adParamInput,  1, Tvl(p_filhos_exercicio))     
     set l_afastamento           = .CreateParameter("l_afastamento",          adVarchar, adParamInput,1000,Tvl(p_afastamento))
     set l_dt_ini                = .CreateParameter("l_dt_ini",               adDate,    adParamInput,   , Tvl(p_dt_ini))
     set l_dt_fim                = .CreateParameter("l_dt_fim",               adDate,    adParamInput,   , Tvl(p_dt_fim))          
     set l_chave_aux             = .CreateParameter("l_chave_aux",            adInteger, adParamInput,   , Tvl(p_chave_aux))
     set l_restricao             = .CreateParameter("l_restricao",            adVarchar, adParamInput, 20, Tvl(p_restricao))
     
     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     .parameters.Append         l_sq_pessoa
     .parameters.Append         l_modalidade_contrato
     .parameters.Append         l_unidade_lotacao
     .parameters.Append         l_filhos_lotacao
     .parameters.Append         l_unidade_exercicio
     .parameters.Append         l_filhos_exercicio     
     .parameters.Append         l_afastamento
     .parameters.Append         l_dt_ini
     .parameters.Append         l_dt_fim               
     .parameters.Append         l_chave_aux
     .parameters.Append         l_restricao

     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_GetGPContrato"
     On Error Resume Next
     Set p_rs                   = .Execute
     If Err.Description > "" Then 
        TrataErro 
     End If     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_sq_pessoa"
     .parameters.Delete         "l_modalidade_contrato"
     .parameters.Delete         "l_unidade_lotacao"
     .parameters.Delete         "l_filhos_lotacao"
     .parameters.Delete         "l_unidade_exercicio"
     .parameters.Delete         "l_filhos_exercicio"
     .parameters.Delete         "l_afastamento"
     .parameters.Delete         "l_dt_ini"
     .parameters.Delete         "l_dt_fim"
     .parameters.Delete         "l_chave_aux"
     .parameters.Delete         "l_restricao"

  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>

