<%
REM =========================================================================
REM Mantém a tabela de modalidades de contratação
REM -------------------------------------------------------------------------
Sub DML_PutGPModalidade(Operacao, p_chave,    p_cliente,  p_nome,   p_descricao, p_sigla,_
                        p_ferias, p_username, p_passagem, p_diaria, p_ativo)
  
  Dim l_Operacao, l_chave, l_cliente, l_nome, l_sigla, l_descricao
  Dim l_ferias, l_username, l_passagem, l_diaria, l_ativo
  
  Set l_Operacao       = Server.CreateObject("ADODB.Parameter")
  Set l_chave          = Server.CreateObject("ADODB.Parameter") 
  Set l_cliente        = Server.CreateObject("ADODB.Parameter") 
  Set l_nome           = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao      = Server.CreateObject("ADODB.Parameter") 
  Set l_sigla          = Server.CreateObject("ADODB.Parameter") 
  Set l_ferias         = Server.CreateObject("ADODB.Parameter") 
  Set l_username       = Server.CreateObject("ADODB.Parameter") 
  Set l_passagem       = Server.CreateObject("ADODB.Parameter")
  Set l_diaria         = Server.CreateObject("ADODB.Parameter")  
  Set l_ativo          = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao      = .CreateParameter("l_operacao",      adVarchar, adParamInput,   1, Operacao)
     set l_chave         = .CreateParameter("l_chave",         adInteger, adParamInput,    , Tvl(p_chave))
     set l_cliente       = .CreateParameter("l_cliente",       adInteger, adParamInput,    , Tvl(p_cliente))
     set l_nome          = .CreateParameter("l_nome",          adVarchar, adParamInput,  30, Tvl(p_nome))
     set l_descricao     = .CreateParameter("l_descricao",     adVarchar, adParamInput, 500, Tvl(p_descricao))
     set l_sigla         = .CreateParameter("l_sigla",         adVarchar, adParamInput,  10, Tvl(p_sigla))
     set l_ferias        = .CreateParameter("l_ferias",        adVarchar, adParamInput,   1, Tvl(p_ferias))
     set l_username      = .CreateParameter("l_username",      adVarchar, adParamInput,   1, Tvl(p_username))
     set l_passagem      = .CreateParameter("l_passagem",      adVarchar, adParamInput,   1, Tvl(p_passagem))
     set l_diaria        = .CreateParameter("l_diaria",        adVarchar, adParamInput,   1, Tvl(p_diaria))
     set l_ativo         = .CreateParameter("l_ativo",         adVarchar, adParamInput,   1, Tvl(p_ativo))
     
     .parameters.Append         l_Operacao
     .parameters.Append         l_chave
     .parameters.Append         l_cliente
     .parameters.Append         l_nome
     .parameters.Append         l_descricao
     .parameters.Append         l_sigla
     .parameters.Append         l_ferias
     .parameters.Append         l_username
     .parameters.Append         l_passagem
     .parameters.Append         l_diaria
     .parameters.Append         l_ativo
     
     .CommandText               = Session("schema") & "SP_PutGPModalidade"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_descricao"
     .parameters.Delete         "l_sigla"
     .parameters.Delete         "l_ferias"
     .parameters.Delete         "l_username"
     .parameters.Delete         "l_passagem"
     .parameters.Delete         "l_diaria"
     .parameters.Delete         "l_ativo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantém a tabela de tipos de afastamento
REM -------------------------------------------------------------------------
Sub DML_PutGPTipoAfast(Operacao, p_chave,                p_cliente,       p_nome,    p_sigla,           p_limite_dias, _
                       p_sexo,   p_percentual_pagamento, p_contagem_dias, p_periodo, p_sobrepoe_ferias, p_ativo, p_fase)
  
  Dim l_Operacao, l_chave, l_cliente, l_nome, l_sigla, l_limite_dias
  Dim l_sexo, l_percentual_pagamento, l_contagem_dias, l_periodo, l_sobrepoe_ferias, l_ativo, l_fase
  
  Set l_Operacao              = Server.CreateObject("ADODB.Parameter")
  Set l_chave                 = Server.CreateObject("ADODB.Parameter") 
  Set l_cliente               = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                  = Server.CreateObject("ADODB.Parameter") 
  Set l_sigla                 = Server.CreateObject("ADODB.Parameter") 
  Set l_limite_dias           = Server.CreateObject("ADODB.Parameter")
  Set l_sexo                  = Server.CreateObject("ADODB.Parameter") 
  Set l_percentual_pagamento  = Server.CreateObject("ADODB.Parameter") 
  Set l_contagem_dias         = Server.CreateObject("ADODB.Parameter")
  Set l_periodo               = Server.CreateObject("ADODB.Parameter")
  Set l_sobrepoe_ferias       = Server.CreateObject("ADODB.Parameter")    
  Set l_ativo                 = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao        = .CreateParameter("l_operacao",        adVarchar, adParamInput,   1, Operacao)
     set l_chave           = .CreateParameter("l_chave",           adInteger, adParamInput,    , Tvl(p_chave))
     set l_cliente         = .CreateParameter("l_cliente",         adInteger, adParamInput,    , Tvl(p_cliente))
     set l_nome            = .CreateParameter("l_nome",            adVarchar, adParamInput,  50, Tvl(p_nome))
     set l_sigla           = .CreateParameter("l_sigla",           adVarchar, adParamInput,   2, Tvl(p_sigla))
     set l_limite_dias     = .CreateParameter("l_limite_dias",     adInteger, adParamInput,   , Tvl(p_limite_dias))
     set l_sexo            = .CreateParameter("l_sexo",            adVarchar, adParamInput,   1, Tvl(p_sexo))
     set l_percentual_pagamento = .CreateParameter("l_percentual_pagamento",adNumeric ,adParamInput)
     l_percentual_pagamento.Precision    = 18
     l_percentual_pagamento.NumericScale = 2
     l_percentual_pagamento.Value        = Tvl(p_percentual_pagamento)     
     set l_contagem_dias   = .CreateParameter("l_contagem_dias",   adVarchar, adParamInput,   1, Tvl(p_contagem_dias))
     set l_periodo         = .CreateParameter("l_periodo",         adVarchar, adParamInput,   1, Tvl(p_periodo))
     set l_sobrepoe_ferias = .CreateParameter("l_sobrepoe_ferias", adVarchar, adParamInput,   1, Tvl(p_sobrepoe_ferias))
     set l_ativo           = .CreateParameter("l_ativo",           adVarchar, adParamInput,   1, Tvl(p_ativo))
     set l_fase            = .CreateParameter("l_fase",            adVarchar, adParamInput, 200, Tvl(p_fase))
     
     .parameters.Append         l_Operacao
     .parameters.Append         l_chave
     .parameters.Append         l_cliente
     .parameters.Append         l_nome
     .parameters.Append         l_sigla
     .parameters.Append         l_limite_dias
     .parameters.Append         l_sexo
     .parameters.Append         l_percentual_pagamento
     .parameters.Append         l_contagem_dias
     .parameters.Append         l_periodo
     .parameters.Append         l_sobrepoe_ferias
     .parameters.Append         l_ativo
     .parameters.Append         l_fase
     
     .CommandText               = Session("schema") & "SP_PutGPTipoAfast"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_sigla"
     .parameters.Delete         "l_limite_dias"
     .parameters.Delete         "l_sexo"
     .parameters.Delete         "l_percentual_pagamento"
     .parameters.Delete         "l_contagem_dias"
     .parameters.Delete         "l_periodo"
     .parameters.Delete         "l_sobrepoe_ferias"
     .parameters.Delete         "l_ativo"
     .parameters.Delete         "l_fase"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantém a tabela de datas especiais
REM -------------------------------------------------------------------------
Sub DML_PutDataEspecial(Operacao, p_chave, p_cliente,  p_sq_pais, p_co_uf, p_sq_cidade, p_tipo, _
                        p_data_especial, p_nome, p_abrangencia, p_expediente, p_ativo)
  
  Dim l_Operacao, l_chave, l_cliente, l_sq_pais, l_co_uf, l_sq_cidade, l_tipo
  Dim l_data_especial, l_nome, l_abrangencia, l_expediente, l_ativo
  
  Set l_Operacao       = Server.CreateObject("ADODB.Parameter")
  Set l_chave          = Server.CreateObject("ADODB.Parameter") 
  Set l_cliente        = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_pais        = Server.CreateObject("ADODB.Parameter") 
  Set l_co_uf          = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_cidade      = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo           = Server.CreateObject("ADODB.Parameter") 
  Set l_data_especial  = Server.CreateObject("ADODB.Parameter") 
  Set l_nome           = Server.CreateObject("ADODB.Parameter")
  Set l_abrangencia    = Server.CreateObject("ADODB.Parameter")
  Set l_expediente     = Server.CreateObject("ADODB.Parameter")    
  Set l_ativo          = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao      = .CreateParameter("l_operacao",      adVarchar, adParamInput,   1, Operacao)
     set l_chave         = .CreateParameter("l_chave",         adInteger, adParamInput,    , Tvl(p_chave))
     set l_cliente       = .CreateParameter("l_cliente",       adInteger, adParamInput,    , Tvl(p_cliente))
     set l_sq_pais       = .CreateParameter("l_sq_pais",       adInteger, adParamInput,    , Tvl(p_sq_pais))
     set l_co_uf         = .CreateParameter("l_co_uf",         adVarchar, adParamInput,   3, Tvl(p_co_uf))
     set l_sq_cidade     = .CreateParameter("l_sq_cidade",     adInteger, adParamInput,    , Tvl(p_sq_cidade))
     set l_tipo          = .CreateParameter("l_tipo",          adVarchar, adParamInput,   1, Tvl(p_tipo))
     set l_data_especial = .CreateParameter("l_data_especial", adVarchar, adParamInput,  10, Tvl(p_data_especial))
     set l_nome          = .CreateParameter("l_nome",          adVarchar, adParamInput,  60, Tvl(p_nome))
     set l_abrangencia   = .CreateParameter("l_abrangencia",   adVarchar, adParamInput,   1, Tvl(p_abrangencia))
     set l_expediente    = .CreateParameter("l_expediente",    adVarchar, adParamInput,   1, Tvl(p_expediente))
     set l_ativo         = .CreateParameter("l_ativo",         adVarchar, adParamInput,   1, Tvl(p_ativo))
     
     .parameters.Append         l_Operacao
     .parameters.Append         l_chave
     .parameters.Append         l_cliente
     .parameters.Append         l_sq_pais
     .parameters.Append         l_co_uf
     .parameters.Append         l_sq_cidade
     .parameters.Append         l_tipo
     .parameters.Append         l_data_especial
     .parameters.Append         l_nome
     .parameters.Append         l_abrangencia
     .parameters.Append         l_expediente
     .parameters.Append         l_ativo
     
     .CommandText               = Session("schema") & "SP_PutDataEspecial"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_sq_pais"
     .parameters.Delete         "l_co_uf"
     .parameters.Delete         "l_sq_cidade"
     .parameters.Delete         "l_tipo"
     .parameters.Delete         "l_data_especial"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_abrangencia"
     .parameters.Delete         "l_expediente"
     .parameters.Delete         "l_ativo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantém a tabela de parametros
REM -------------------------------------------------------------------------
Sub DML_PutGPParametro(p_cliente, p_sq_unidade_gestao, p_admissao_texto, p_admissao_destino, _
                       p_rescisao_texto, p_rescisao_destino, p_feriado_legenda, p_feriado_nome, _
                       p_ferias_legenda, p_ferias_nome, p_viagem_legenda, p_viagem_nome)
  
  Dim l_cliente, l_sq_unidade_gestao, l_admissao_texto, l_admissao_destino, l_rescisao_texto, l_rescisao_destino
  Dim l_feriado_legenda, l_feriado_nome, l_ferias_legenda, l_ferias_nome, l_viagem_legenda, l_viagem_nome
  
  Set l_cliente           = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_unidade_gestao = Server.CreateObject("ADODB.Parameter") 
  Set l_admissao_texto    = Server.CreateObject("ADODB.Parameter") 
  Set l_admissao_destino  = Server.CreateObject("ADODB.Parameter") 
  Set l_rescisao_texto    = Server.CreateObject("ADODB.Parameter") 
  Set l_rescisao_destino  = Server.CreateObject("ADODB.Parameter") 
  Set l_feriado_legenda   = Server.CreateObject("ADODB.Parameter")
  Set l_feriado_nome      = Server.CreateObject("ADODB.Parameter")  
  Set l_ferias_legenda    = Server.CreateObject("ADODB.Parameter")
  Set l_ferias_nome       = Server.CreateObject("ADODB.Parameter")  
  Set l_viagem_legenda    = Server.CreateObject("ADODB.Parameter")
  Set l_viagem_nome       = Server.CreateObject("ADODB.Parameter")  
  
  with sp
     set l_cliente            = .CreateParameter("l_cliente",            adInteger, adParamInput,    , Tvl(p_cliente))
     set l_sq_unidade_gestao  = .CreateParameter("l_sq_unidade_gestao",  adInteger, adParamInput,    , Tvl(p_sq_unidade_gestao))
     set l_admissao_texto     = .CreateParameter("l_admissao_texto",     adVarchar, adParamInput,1000, Tvl(p_admissao_texto))
     set l_admissao_destino   = .CreateParameter("l_admissao_destino",   adVarchar, adParamInput, 100, Tvl(p_admissao_destino))
     set l_rescisao_texto     = .CreateParameter("l_rescisao_texto",     adVarchar, adParamInput,1000, Tvl(p_rescisao_texto))
     set l_rescisao_destino   = .CreateParameter("l_rescisao_destino",   adVarchar, adParamInput, 100, Tvl(p_rescisao_destino)) 
     set l_feriado_legenda    = .CreateParameter("l_feriado_legenda",    adVarchar, adParamInput,   2, Tvl(p_feriado_legenda))
     set l_feriado_nome       = .CreateParameter("l_feriado_nome",       adVarchar, adParamInput,  20, Tvl(p_feriado_nome))
     set l_ferias_legenda     = .CreateParameter("l_ferias_legenda",     adVarchar, adParamInput,   2, Tvl(p_ferias_legenda))
     set l_ferias_nome        = .CreateParameter("l_ferias_nome",        adVarchar, adParamInput,  20, Tvl(p_ferias_nome))
     set l_viagem_legenda     = .CreateParameter("l_viagem_legenda",     adVarchar, adParamInput,   2, Tvl(p_viagem_legenda))
     set l_viagem_nome        = .CreateParameter("l_viagem_nome",        adVarchar, adParamInput,  20, Tvl(p_viagem_nome))
     
     .parameters.Append         l_cliente
     .parameters.Append         l_sq_unidade_gestao
     .parameters.Append         l_admissao_texto
     .parameters.Append         l_admissao_destino
     .parameters.Append         l_rescisao_texto
     .parameters.Append         l_rescisao_destino
     .parameters.Append         l_feriado_legenda
     .parameters.Append         l_feriado_nome
     .parameters.Append         l_ferias_legenda
     .parameters.Append         l_ferias_nome
     .parameters.Append         l_viagem_legenda
     .parameters.Append         l_viagem_nome
     
     .CommandText               = Session("schema") & "SP_PutGPParametro"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_sq_unidade_gestao"
     .parameters.Delete         "l_admissao_texto"
     .parameters.Delete         "l_admissao_destino"
     .parameters.Delete         "l_rescisao_texto"
     .parameters.Delete         "l_rescisao_destino"
     .parameters.Delete         "l_feriado_legenda"
     .parameters.Delete         "l_feriado_nome"
     .parameters.Delete         "l_ferias_legenda"
     .parameters.Delete         "l_ferias_nome"
     .parameters.Delete         "l_viagem_legenda"
     .parameters.Delete         "l_viagem_nome"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantém a tabela de afastamentos
REM -------------------------------------------------------------------------
Sub DML_PutAfastamento(Operacao, p_chave, p_cliente,  p_sq_tipo_afastamento, p_sq_contrato_colaborador, _
                       p_inicio_data, p_inicio_periodo, p_fim_data, p_fim_periodo, p_dias, p_observacao)
  
  Dim l_Operacao, l_chave, l_cliente, l_sq_tipo_afastamento, l_sq_contrato_colaborador
  Dim l_inicio_data, l_inicio_periodo, l_fim_data, l_fim_periodo, l_dias, l_observacao
  
  Set l_Operacao                 = Server.CreateObject("ADODB.Parameter")
  Set l_chave                    = Server.CreateObject("ADODB.Parameter") 
  Set l_cliente                  = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_tipo_afastamento      = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_contrato_colaborador  = Server.CreateObject("ADODB.Parameter") 
  Set l_inicio_data              = Server.CreateObject("ADODB.Parameter") 
  Set l_inicio_periodo           = Server.CreateObject("ADODB.Parameter") 
  Set l_fim_data                 = Server.CreateObject("ADODB.Parameter") 
  Set l_fim_periodo              = Server.CreateObject("ADODB.Parameter")
  Set l_dias                     = Server.CreateObject("ADODB.Parameter")
  Set l_observacao               = Server.CreateObject("ADODB.Parameter")    
  
  with sp
     set l_Operacao                 = .CreateParameter("l_operacao",                  adVarchar, adParamInput,   1, Operacao)
     set l_chave                    = .CreateParameter("l_chave",                     adInteger, adParamInput,    , Tvl(p_chave))
     set l_cliente                  = .CreateParameter("l_cliente",                   adInteger, adParamInput,    , Tvl(p_cliente))
     set l_sq_tipo_afastamento      = .CreateParameter("l_sq_tipo_afastamento",       adInteger, adParamInput,    , Tvl(p_sq_tipo_afastamento))
     set l_sq_contrato_colaborador  = .CreateParameter("l_sq_contrato_colaborador",   adInteger, adParamInput,    , Tvl(p_sq_contrato_colaborador))
     set l_inicio_data              = .CreateParameter("l_inicio_data",               adDate,    adParamInput,    , Tvl(p_inicio_data))
     set l_inicio_periodo           = .CreateParameter("l_inicio_periodo",            adVarchar, adParamInput,   1, Tvl(p_inicio_periodo))   
     set l_fim_data                 = .CreateParameter("l_fim_data",                  adDate,    adParamInput,    , Tvl(p_fim_data))
     set l_fim_periodo              = .CreateParameter("l_fim_periodo",               adVarchar, adParamInput,   1, Tvl(p_fim_periodo))   
     set l_dias                     = .CreateParameter("l_dias",                      adInteger, adParamInput,    , Tvl(p_dias))
     set l_observacao               = .CreateParameter("l_observacao",                adVarchar, adParamInput, 300, Tvl(p_observacao))
     
     .parameters.Append         l_Operacao
     .parameters.Append         l_chave
     .parameters.Append         l_cliente
     .parameters.Append         l_sq_tipo_afastamento
     .parameters.Append         l_sq_contrato_colaborador
     .parameters.Append         l_inicio_data
     .parameters.Append         l_inicio_periodo
     .parameters.Append         l_fim_data
     .parameters.Append         l_fim_periodo
     .parameters.Append         l_dias
     .parameters.Append         l_observacao
     
     .CommandText               = Session("schema") & "SP_PutAfastamento"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_sq_tipo_afastamento"
     .parameters.Delete         "l_sq_contrato_colaborador"
     .parameters.Delete         "l_inicio_data"
     .parameters.Delete         "l_inicio_periodo"
     .parameters.Delete         "l_fim_data"
     .parameters.Delete         "l_fim_periodo"
     .parameters.Delete         "l_dias"
     .parameters.Delete         "l_observacao"

  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantém a tabela de cargos
REM -------------------------------------------------------------------------
Sub DML_PutCargo(Operacao, p_chave, p_cliente, p_sq_tipo, p_sq_formacao, p_nome, _
                       p_descricao, p_atividades, p_competencias, p_salario_piso, p_salario_teto, _
                       p_ativo)
  
  Dim l_Operacao, l_chave, l_cliente, l_nome, l_sq_tipo, l_sq_formacao
  Dim l_descricao, l_atividades, l_competencias, l_salario_piso, l_salario_teto, l_ativo
  
  Set l_Operacao     = Server.CreateObject("ADODB.Parameter")
  Set l_chave        = Server.CreateObject("ADODB.Parameter") 
  Set l_cliente      = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_tipo      = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_formacao  = Server.CreateObject("ADODB.Parameter")
  Set l_nome         = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao    = Server.CreateObject("ADODB.Parameter") 
  Set l_atividades   = Server.CreateObject("ADODB.Parameter") 
  Set l_competencias = Server.CreateObject("ADODB.Parameter")
  Set l_salario_piso = Server.CreateObject("ADODB.Parameter")
  Set l_salario_teto = Server.CreateObject("ADODB.Parameter")    
  Set l_ativo        = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao       = .CreateParameter("l_operacao",     adVarchar, adParamInput,    1, Operacao)
     set l_chave          = .CreateParameter("l_chave",        adInteger, adParamInput,     , Tvl(p_chave))
     set l_cliente        = .CreateParameter("l_cliente",      adInteger, adParamInput,     , Tvl(p_cliente))
     set l_sq_tipo        = .CreateParameter("l_sq_tipo",      adInteger, adParamInput,     , Tvl(p_sq_tipo))
     set l_sq_formacao    = .CreateParameter("l_sq_formacao",  adInteger, adParamInput,     , Tvl(p_sq_formacao))
     set l_nome           = .CreateParameter("l_nome",         adVarchar, adParamInput,   30, Tvl(p_nome))
     set l_descricao      = .CreateParameter("l_descricao",    adVarchar, adParamInput, 1000, Tvl(p_descricao))
     set l_atividades     = .CreateParameter("l_atividades",   adVarchar ,adParamInput, 1000, Tvl(p_atividades))
     set l_competencias   = .CreateParameter("l_competencias", adVarchar, adParamInput, 1000, Tvl(p_competencias))
     set l_salario_piso   = .CreateParameter("l_salario_piso", adNumeric, adParamInput)
     l_salario_piso.Precision    = 18
     l_salario_piso.NumericScale = 2
     l_salario_piso.Value        = Tvl(p_salario_piso)
     set l_salario_teto   = .CreateParameter("l_salario_teto", adNumeric, adParamInput)
     l_salario_teto.Precision    = 18
     l_salario_teto.NumericScale = 2
     l_salario_teto.Value        = Tvl(p_salario_teto)
     set l_ativo           = .CreateParameter("l_ativo",           adVarchar, adParamInput,   1, Tvl(p_ativo))
     
     .parameters.Append         l_Operacao
     .parameters.Append         l_chave
     .parameters.Append         l_cliente
     .parameters.Append         l_sq_tipo
     .parameters.Append         l_sq_formacao
     .parameters.Append         l_nome
     .parameters.Append         l_descricao
     .parameters.Append         l_atividades
     .parameters.Append         l_competencias
     .parameters.Append         l_salario_piso
     .parameters.Append         l_salario_teto
     .parameters.Append         l_ativo
     
     .CommandText               = Session("schema") & "SP_PutCargo"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_sq_tipo"
     .parameters.Delete         "l_sq_formacao"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_descricao"
     .parameters.Delete         "l_atividades"
     .parameters.Delete         "l_competencias"
     .parameters.Delete         "l_salario_piso"
     .parameters.Delete         "l_salario_teto"
     .parameters.Delete         "l_ativo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>