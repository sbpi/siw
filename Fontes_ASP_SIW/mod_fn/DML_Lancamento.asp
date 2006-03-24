<%
REM =========================================================================
REM Grava a tela de dados gerais de um acordo
REM -------------------------------------------------------------------------
Sub DML_PutFinanceiroGeral(Operacao, p_cliente, p_chave, p_menu, p_unidade, _
    p_solicitante, p_cadastrador, p_sqcc, p_descricao, p_vencimento, p_valor, _
    p_data_hora, p_aviso, p_dias, p_cidade, p_projeto, p_sq_acordo_parcela, _
    p_observacao, p_sq_tipo_lancamento, p_sq_forma_pagamento, p_sq_tipo_pessoa, _
    p_forma_atual, p_vencimento_atual, p_chave_nova, p_codigo_interno)
    
  Dim l_Operacao, l_cliente, l_chave, l_menu, l_unidade, l_solicitante
  Dim l_cadastrador, l_sqcc, l_descricao, l_vencimento, l_valor, l_data_hora
  Dim l_aviso, l_dias, l_cidade, l_projeto, l_sq_acordo_parcela, l_observacao
  Dim l_sq_tipo_lancamento, l_sq_forma_pagamento, l_sq_tipo_pessoa
  Dim l_forma_atual, l_vencimento_atual, l_chave_nova, l_codigo_interno
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_cliente             = Server.CreateObject("ADODB.Parameter") 
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_menu                = Server.CreateObject("ADODB.Parameter") 
  Set l_unidade             = Server.CreateObject("ADODB.Parameter") 
  Set l_solicitante         = Server.CreateObject("ADODB.Parameter") 
  Set l_cadastrador         = Server.CreateObject("ADODB.Parameter") 
  Set l_sqcc                = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao           = Server.CreateObject("ADODB.Parameter") 
  Set l_vencimento          = Server.CreateObject("ADODB.Parameter") 
  Set l_valor               = Server.CreateObject("ADODB.Parameter") 
  Set l_data_hora           = Server.CreateObject("ADODB.Parameter") 
  Set l_aviso               = Server.CreateObject("ADODB.Parameter") 
  Set l_dias                = Server.CreateObject("ADODB.Parameter") 
  Set l_cidade              = Server.CreateObject("ADODB.Parameter") 
  Set l_projeto             = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_acordo_parcela   = Server.CreateObject("ADODB.Parameter") 
  Set l_observacao          = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_tipo_lancamento  = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_forma_pagamento  = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_tipo_pessoa      = Server.CreateObject("ADODB.Parameter") 
  Set l_forma_atual         = Server.CreateObject("ADODB.Parameter") 
  Set l_vencimento_atual    = Server.CreateObject("ADODB.Parameter") 
  Set l_chave_nova          = Server.CreateObject("ADODB.Parameter") 
  Set l_codigo_interno      = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",            adVarchar, adParamInput,   1, Operacao)
     set l_cliente              = .CreateParameter("l_cliente",             adInteger, adParamInput,    , p_cliente)
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,    , Tvl(p_chave))
     set l_menu                 = .CreateParameter("l_menu",                adInteger, adParamInput,    , p_menu)
     set l_unidade              = .CreateParameter("l_unidade",             adInteger, adParamInput,    , Tvl(p_unidade))
     set l_solicitante          = .CreateParameter("l_solicitante",         adInteger, adParamInput,    , Tvl(p_solicitante))
     set l_cadastrador          = .CreateParameter("l_cadastrador",         adInteger, adParamInput,    , Tvl(p_cadastrador))
     set l_sqcc                 = .CreateParameter("l_sqcc",                adInteger, adParamInput,    , Tvl(p_sqcc))
     set l_descricao            = .CreateParameter("l_descricao",           adVarchar, adParamInput,2000, Tvl(p_descricao))
     set l_vencimento           = .CreateParameter("l_vencimento",          adDate,    adParamInput,    , Tvl(p_vencimento))
     set l_valor                = .CreateParameter("l_valor",               adNumeric ,adParamInput)
     l_valor.Precision    = 18
     l_valor.NumericScale = 2
     l_valor.Value        = Tvl(p_valor)
     set l_data_hora            = .CreateParameter("l_data_hora",           adVarchar, adParamInput,   1, Tvl(p_data_hora))
     set l_aviso                = .CreateParameter("l_aviso",               adVarchar, adParamInput,   1, Tvl(p_aviso))
     set l_dias                 = .CreateParameter("l_dias",                adInteger, adParamInput,    , Nvl(p_dias,0))
     set l_cidade               = .CreateParameter("l_cidade",              adInteger, adParamInput,    , Tvl(p_cidade))
     set l_projeto              = .CreateParameter("l_projeto",             adInteger, adParamInput,    , Tvl(p_projeto))
     set l_sq_acordo_parcela    = .CreateParameter("l_sq_acordo_parcela",   adInteger, adParamInput,    , Tvl(p_sq_acordo_parcela))
     set l_observacao           = .CreateParameter("l_observacao",          adVarchar, adParamInput,2000, Tvl(p_observacao))
     set l_sq_tipo_lancamento   = .CreateParameter("l_sq_tipo_lancamento",  adInteger, adParamInput,    , Tvl(p_sq_tipo_lancamento))
     set l_sq_forma_pagamento   = .CreateParameter("l_sq_forma_pagamento",  adInteger, adParamInput,    , Tvl(p_sq_forma_pagamento))
     set l_sq_tipo_pessoa       = .CreateParameter("l_sq_tipo_pessoa",      adInteger, adParamInput,    , Tvl(p_sq_tipo_pessoa))
     set l_forma_atual          = .CreateParameter("l_forma_atual",         adInteger, adParamInput,    , Tvl(p_forma_atual))
     set l_vencimento_atual     = .CreateParameter("l_vencimento_atual",    adDate,    adParamInput,    , Tvl(p_vencimento_atual))
     set l_chave_nova           = .CreateParameter("l_chave_nova",          adInteger, adParamOutput,   , null)
     set l_codigo_interno       = .CreateParameter("l_codigo_interno",      adVarchar, adParamOutput, 60, null)
     .parameters.Append         l_Operacao
     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     .parameters.Append         l_menu
     .parameters.Append         l_unidade
     .parameters.Append         l_solicitante
     .parameters.Append         l_cadastrador
     .parameters.Append         l_sqcc
     .parameters.Append         l_descricao
     .parameters.Append         l_vencimento
     .parameters.Append         l_valor
     .parameters.Append         l_data_hora
     .parameters.Append         l_aviso
     .parameters.Append         l_dias
     .parameters.Append         l_cidade
     .parameters.Append         l_projeto
     .parameters.Append         l_sq_acordo_parcela
     .parameters.Append         l_observacao
     .parameters.Append         l_sq_tipo_lancamento
     .parameters.Append         l_sq_forma_pagamento
     .parameters.Append         l_sq_tipo_pessoa
     .parameters.Append         l_forma_atual
     .parameters.Append         l_vencimento_atual
     .parameters.Append         l_chave_nova
     .parameters.Append         l_codigo_interno
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_PutFinanceiroGeral"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     p_chave_nova     = l_chave_nova.Value
     p_codigo_interno = l_codigo_interno.Value
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_menu"
     .parameters.Delete         "l_unidade"
     .parameters.Delete         "l_solicitante"
     .parameters.Delete         "l_cadastrador"
     .parameters.Delete         "l_sqcc"
     .parameters.Delete         "l_descricao"
     .parameters.Delete         "l_vencimento"
     .parameters.Delete         "l_valor"
     .parameters.Delete         "l_data_hora"
     .parameters.Delete         "l_aviso"
     .parameters.Delete         "l_dias"
     .parameters.Delete         "l_cidade"
     .parameters.Delete         "l_projeto"
     .parameters.Delete         "l_sq_acordo_parcela"
     .parameters.Delete         "l_observacao"
     .parameters.Delete         "l_sq_tipo_lancamento"
     .parameters.Delete         "l_sq_forma_pagamento"
     .parameters.Delete         "l_sq_tipo_pessoa"
     .parameters.Delete         "l_forma_atual"
     .parameters.Delete         "l_vencimento_atual"
     .parameters.Delete         "l_chave_nova"
     .parameters.Delete         "l_codigo_interno"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Grava a tela de outra parte
REM -------------------------------------------------------------------------
Sub DML_PutLancamentoOutra (Operacao, p_restricao, p_chave, p_chave_aux, p_sq_pessoa, _
    p_cpf, p_cnpj, p_nome, p_nome_resumido, p_sexo, p_nascimento, p_rg_numero, p_rg_emissao, _
    p_rg_emissor, p_passaporte, p_sq_pais_passaporte, p_inscricao_estadual, p_logradouro, _
    p_complemento, p_bairro, p_sq_cidade, p_cep, p_ddd, p_nr_telefone, _
    p_nr_fax, p_nr_celular, p_email, p_sq_agencia, p_op_conta, p_nr_conta, _
    p_sq_pais_estrang, p_aba_code, p_swift_code, p_endereco_estrang, p_banco_estrang, _
    p_agencia_estrang, p_cidade_estrang, p_informacoes, p_codigo_deposito, p_pessoa_atual)
    
  Dim l_Operacao, l_restricao, l_chave, l_chave_aux, l_sq_pessoa
  Dim l_cpf, l_cnpj, l_nome, l_nome_resumido, l_sexo, l_nascimento, l_rg_numero, l_rg_emissao
  Dim l_rg_emissor, l_passaporte, l_sq_pais_passaporte, l_inscricao_estadual, l_logradouro
  Dim l_complemento, l_bairro, l_sq_cidade, l_cep, l_ddd, l_nr_telefone
  Dim l_nr_fax, l_nr_celular, l_email, l_sq_agencia, l_op_conta, l_nr_conta, l_pessoa_atual
  Dim l_sq_pais_estrang, l_aba_code, l_swift_code, l_endereco_estrang, l_banco_estrang
  Dim l_agencia_estrang, l_cidade_estrang, l_informacoes, l_codigo_deposito
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_restricao           = Server.CreateObject("ADODB.Parameter")
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_chave_aux           = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_pessoa           = Server.CreateObject("ADODB.Parameter") 
  Set l_cpf                 = Server.CreateObject("ADODB.Parameter") 
  Set l_cnpj                = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                = Server.CreateObject("ADODB.Parameter") 
  Set l_nome_resumido       = Server.CreateObject("ADODB.Parameter") 
  Set l_sexo                = Server.CreateObject("ADODB.Parameter") 
  Set l_nascimento          = Server.CreateObject("ADODB.Parameter") 
  Set l_rg_numero           = Server.CreateObject("ADODB.Parameter") 
  Set l_rg_emissao          = Server.CreateObject("ADODB.Parameter") 
  Set l_rg_emissor          = Server.CreateObject("ADODB.Parameter") 
  Set l_passaporte          = Server.CreateObject("ADODB.Parameter")
  Set l_sq_pais_passaporte  = Server.CreateObject("ADODB.Parameter")
  Set l_inscricao_estadual  = Server.CreateObject("ADODB.Parameter")
  Set l_logradouro          = Server.CreateObject("ADODB.Parameter")
  Set l_complemento         = Server.CreateObject("ADODB.Parameter")
  Set l_bairro              = Server.CreateObject("ADODB.Parameter")
  Set l_sq_cidade           = Server.CreateObject("ADODB.Parameter")
  Set l_cep                 = Server.CreateObject("ADODB.Parameter")
  Set l_ddd                 = Server.CreateObject("ADODB.Parameter")
  Set l_nr_telefone         = Server.CreateObject("ADODB.Parameter")
  Set l_nr_fax              = Server.CreateObject("ADODB.Parameter")
  Set l_nr_celular          = Server.CreateObject("ADODB.Parameter")
  Set l_email               = Server.CreateObject("ADODB.Parameter")
  Set l_sq_agencia          = Server.CreateObject("ADODB.Parameter")
  Set l_op_conta            = Server.CreateObject("ADODB.Parameter")
  Set l_nr_conta            = Server.CreateObject("ADODB.Parameter")
  Set l_sq_pais_estrang     = Server.CreateObject("ADODB.Parameter")
  Set l_aba_code            = Server.CreateObject("ADODB.Parameter")
  Set l_swift_code          = Server.CreateObject("ADODB.Parameter")
  Set l_endereco_estrang    = Server.CreateObject("ADODB.Parameter")
  Set l_banco_estrang       = Server.CreateObject("ADODB.Parameter")
  Set l_agencia_estrang     = Server.CreateObject("ADODB.Parameter")
  Set l_cidade_estrang      = Server.CreateObject("ADODB.Parameter")
  Set l_informacoes         = Server.CreateObject("ADODB.Parameter")
  Set l_codigo_deposito     = Server.CreateObject("ADODB.Parameter")
  Set l_pessoa_atual        = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",            adVarchar, adParamInput,   1, Operacao)
     set l_restricao            = .CreateParameter("l_restricao",           adVarchar, adParamInput,  10, p_restricao)
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,    , Tvl(p_chave))
     set l_chave_aux            = .CreateParameter("l_chave_aux",           adInteger, adParamInput,    , Tvl(p_chave_aux))
     set l_sq_pessoa            = .CreateParameter("l_sq_pessoa",           adInteger, adParamInput,    , Tvl(p_sq_pessoa))
     set l_cpf                  = .CreateParameter("l_cpf",                 adVarchar, adParamInput,  14, Tvl(p_cpf))
     set l_cnpj                 = .CreateParameter("l_cnpj",                adVarchar, adParamInput,  18, Tvl(p_cnpj))
     set l_nome                  = .CreateParameter("l_nome",               adVarchar, adParamInput,  60, Tvl(p_nome))
     set l_nome_resumido        = .CreateParameter("l_nome_resumido",       adVarchar, adParamInput,  15, Tvl(p_nome_resumido))
     set l_sexo                 = .CreateParameter("l_sexo",                adVarchar, adParamInput,   1, Tvl(p_sexo))
     set l_nascimento           = .CreateParameter("l_nascimento",          adDate,    adParamInput,    , Tvl(p_nascimento))
     set l_rg_numero            = .CreateParameter("l_rg_numero",           adVarchar, adParamInput,  30, Tvl(p_rg_numero))
     set l_rg_emissao           = .CreateParameter("l_rg_emissao",          adDate,    adParamInput,    , Tvl(p_rg_emissao))
     set l_rg_emissor           = .CreateParameter("l_rg_emissor",          adVarchar, adParamInput,  30, Tvl(p_rg_emissor))
     set l_passaporte           = .CreateParameter("l_passaporte",          adVarchar, adParamInput,  20, Tvl(p_passaporte))
     set l_sq_pais_passaporte   = .CreateParameter("l_sq_pais_passaporte",  adInteger, adParamInput,    , Tvl(p_sq_pais_passaporte))
     set l_inscricao_estadual   = .CreateParameter("l_inscricao_estadual",  adVarchar, adParamInput,  20, Tvl(p_inscricao_estadual))
     set l_logradouro           = .CreateParameter("l_logradouro",          adVarchar, adParamInput,  60, Tvl(p_logradouro))
     set l_complemento          = .CreateParameter("l_complemento",         adVarchar, adParamInput,  20, Tvl(p_complemento))
     set l_bairro               = .CreateParameter("l_bairro",              adVarchar, adParamInput,  30, Tvl(p_bairro))
     set l_sq_cidade            = .CreateParameter("l_sq_cidade",           adInteger, adParamInput,    , Tvl(p_sq_cidade))
     set l_cep                  = .CreateParameter("l_cep",                 adVarchar, adParamInput,   9, Tvl(p_cep))
     set l_ddd                  = .CreateParameter("l_ddd",                 adVarchar, adParamInput,   4, Tvl(p_ddd))
     set l_nr_telefone          = .CreateParameter("l_nr_telefone",         adVarchar, adParamInput,  25, Tvl(p_nr_telefone))
     set l_nr_fax               = .CreateParameter("l_nr_fax",              adVarchar, adParamInput,  25, Tvl(p_nr_fax))
     set l_nr_celular           = .CreateParameter("l_nr_celular",          adVarchar, adParamInput,  25, Tvl(p_nr_celular))
     set l_email                = .CreateParameter("l_email",               adVarchar, adParamInput,  60, Tvl(p_email))
     set l_sq_agencia           = .CreateParameter("l_sq_agencia",          adInteger, adParamInput,    , Tvl(p_sq_agencia))
     set l_op_conta             = .CreateParameter("l_op_conta",            adVarchar, adParamInput,   6, Tvl(p_op_conta))
     set l_nr_conta             = .CreateParameter("l_nr_conta",            adVarchar, adParamInput,  30, Tvl(p_nr_conta))
     set l_sq_pais_estrang      = .CreateParameter("l_sq_pais_estrang",     adInteger, adParamInput,    , Tvl(p_sq_pais_estrang))
     set l_aba_code             = .CreateParameter("l_aba_code",            adVarchar, adParamInput,  12, Tvl(p_aba_code))
     set l_swift_code           = .CreateParameter("l_swift_code",          adVarchar, adParamInput,  30, Tvl(p_swift_code))
     set l_endereco_estrang     = .CreateParameter("l_endereco_estrang",    adVarchar, adParamInput, 100, Tvl(p_endereco_estrang))
     set l_banco_estrang        = .CreateParameter("l_banco_estrang",       adVarchar, adParamInput,  20, Tvl(p_banco_estrang))
     set l_agencia_estrang      = .CreateParameter("l_agencia_estrang",     adVarchar, adParamInput,  60, Tvl(p_agencia_estrang))
     set l_cidade_estrang       = .CreateParameter("l_cidade_estrang",      adVarchar, adParamInput,  60, Tvl(p_cidade_estrang))
     set l_informacoes          = .CreateParameter("l_informacoes",         adVarchar, adParamInput, 200, Tvl(p_informacoes))
     set l_codigo_deposito      = .CreateParameter("l_codigo_deposito",     adVarchar, adParamInput,  50, Tvl(p_codigo_deposito))
     set l_pessoa_atual         = .CreateParameter("l_pessoa_atual",        adInteger, adParamInput,    , Tvl(p_pessoa_atual))
     .parameters.Append         l_Operacao
     .parameters.Append         l_restricao
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_sq_pessoa 
     .parameters.Append         l_cpf
     .parameters.Append         l_cnpj 
     .parameters.Append         l_nome 
     .parameters.Append         l_nome_resumido 
     .parameters.Append         l_sexo 
     .parameters.Append         l_nascimento 
     .parameters.Append         l_rg_numero 
     .parameters.Append         l_rg_emissao 
     .parameters.Append         l_rg_emissor 
     .parameters.Append         l_passaporte 
     .parameters.Append         l_sq_pais_passaporte 
     .parameters.Append         l_inscricao_estadual 
     .parameters.Append         l_logradouro 
     .parameters.Append         l_complemento 
     .parameters.Append         l_bairro 
     .parameters.Append         l_sq_cidade 
     .parameters.Append         l_cep 
     .parameters.Append         l_ddd 
     .parameters.Append         l_nr_telefone 
     .parameters.Append         l_nr_fax 
     .parameters.Append         l_nr_celular 
     .parameters.Append         l_email 
     .parameters.Append         l_sq_agencia 
     .parameters.Append         l_op_conta 
     .parameters.Append         l_nr_conta 
     .parameters.Append         l_sq_pais_estrang
     .parameters.Append         l_aba_code
     .parameters.Append         l_swift_code
     .parameters.Append         l_endereco_estrang
     .parameters.Append         l_banco_estrang
     .parameters.Append         l_agencia_estrang
     .parameters.Append         l_cidade_estrang
     .parameters.Append         l_informacoes
     .parameters.Append         l_codigo_deposito
     .parameters.Append         l_pessoa_atual
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_PutAcordoOutra"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_restricao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_chave_aux"
     .parameters.Delete         "l_sq_pessoa"
     .parameters.Delete         "l_cpf"
     .parameters.Delete         "l_cnpj"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_nome_resumido"
     .parameters.Delete         "l_sexo"
     .parameters.Delete         "l_nascimento"
     .parameters.Delete         "l_rg_numero"
     .parameters.Delete         "l_rg_emissao" 
     .parameters.Delete         "l_rg_emissor"
     .parameters.Delete         "l_passaporte"
     .parameters.Delete         "l_sq_pais_passaporte"
     .parameters.Delete         "l_inscricao_estadual"
     .parameters.Delete         "l_logradouro"
     .parameters.Delete         "l_complemento" 
     .parameters.Delete         "l_bairro"
     .parameters.Delete         "l_sq_cidade"
     .parameters.Delete         "l_cep"
     .parameters.Delete         "l_ddd"
     .parameters.Delete         "l_nr_telefone"
     .parameters.Delete         "l_nr_fax"
     .parameters.Delete         "l_nr_celular"
     .parameters.Delete         "l_email"
     .parameters.Delete         "l_sq_agencia"
     .parameters.Delete         "l_op_conta"
     .parameters.Delete         "l_nr_conta"
     .parameters.Delete         "l_sq_pais_estrang"
     .parameters.Delete         "l_aba_code"
     .parameters.Delete         "l_swift_code"
     .parameters.Delete         "l_endereco_estrang"
     .parameters.Delete         "l_banco_estrang"
     .parameters.Delete         "l_agencia_estrang"
     .parameters.Delete         "l_cidade_estrang"
     .parameters.Delete         "l_informacoes"
     .parameters.Delete         "l_codigo_deposito"
     .parameters.Delete         "l_pessoa_atual"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Grava a tela de documentos
REM -------------------------------------------------------------------------
Sub DML_PutLancamentoDoc(Operacao, p_chave, p_chave_aux, _
    p_sq_tipo_documento, p_numero, p_data, p_serie, p_valor, _
    p_patrimonio, p_retencao, p_tributo)
    
  Dim l_Operacao, l_chave, l_chave_aux
  Dim l_sq_tipo_documento, l_numero, l_data, l_valor, l_serie
  Dim l_patrimonio, l_retencao, l_tributo
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_chave_aux           = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_tipo_documento   = Server.CreateObject("ADODB.Parameter") 
  Set l_numero              = Server.CreateObject("ADODB.Parameter") 
  Set l_data                = Server.CreateObject("ADODB.Parameter") 
  Set l_serie               = Server.CreateObject("ADODB.Parameter") 
  Set l_valor               = Server.CreateObject("ADODB.Parameter") 
  Set l_patrimonio          = Server.CreateObject("ADODB.Parameter") 
  Set l_retencao            = Server.CreateObject("ADODB.Parameter") 
  Set l_tributo             = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",            adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,    , Tvl(p_chave))
     set l_chave_aux            = .CreateParameter("l_chave_aux",           adInteger, adParamInput,    , Tvl(p_chave_aux))
     set l_sq_tipo_documento    = .CreateParameter("l_sq_tipo_documento",   adInteger, adParamInput,    , Tvl(p_sq_tipo_documento))
     set l_numero               = .CreateParameter("l_numero",              adVarchar, adParamInput,  30, Tvl(p_numero))
     set l_data                 = .CreateParameter("l_data",                adDate,    adParamInput,    , Tvl(p_data))
     set l_serie                = .CreateParameter("l_serie",               adVarchar, adParamInput,  10, Tvl(p_serie))
     set l_valor                = .CreateParameter("l_valor",               adNumeric ,adParamInput)
     l_valor.Precision    = 18
     l_valor.NumericScale = 2
     l_valor.Value        = Tvl(p_valor)
     set l_patrimonio           = .CreateParameter("l_patrimonio",          adVarchar, adParamInput,   1, Tvl(p_patrimonio))
     set l_retencao             = .CreateParameter("l_retencao",            adVarchar, adParamInput,   1, Tvl(p_retencao))
     set l_tributo              = .CreateParameter("l_tributo",             adVarchar, adParamInput,   1, Tvl(p_tributo))
     .parameters.Append         l_Operacao
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_sq_tipo_documento
     .parameters.Append         l_numero
     .parameters.Append         l_data
     .parameters.Append         l_serie
     .parameters.Append         l_valor
     .parameters.Append         l_patrimonio
     .parameters.Append         l_retencao
     .parameters.Append         l_tributo
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_PutLancamentoDoc"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_chave_aux"
     .parameters.Delete         "l_sq_tipo_documento"
     .parameters.Delete         "l_numero"
     .parameters.Delete         "l_data"
     .parameters.Delete         "l_serie"
     .parameters.Delete         "l_valor"
     .parameters.Delete         "l_patrimonio"
     .parameters.Delete         "l_retencao"
     .parameters.Delete         "l_tributo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Encaminha a solicitacao
REM -------------------------------------------------------------------------
Sub DML_PutLancamentoEnvio(p_menu, p_chave, p_pessoa, p_tramite, p_novo_tramite, p_devolucao, p_observacao, p_destinatario, p_despacho, _
        p_caminho, p_tamanho, P_tipo, p_nome_original)
  Dim l_Operacao, l_menu, l_chave, l_pessoa, l_tramite, l_novo_tramite, l_devolucao, l_observacao, l_destinatario, l_despacho
  Dim l_caminho, l_tamanho, l_tipo, l_nome_original
  
  Set l_menu                = Server.CreateObject("ADODB.Parameter") 
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_pessoa              = Server.CreateObject("ADODB.Parameter") 
  Set l_tramite             = Server.CreateObject("ADODB.Parameter") 
  Set l_novo_tramite        = Server.CreateObject("ADODB.Parameter") 
  Set l_devolucao           = Server.CreateObject("ADODB.Parameter") 
  Set l_observacao          = Server.CreateObject("ADODB.Parameter") 
  Set l_destinatario        = Server.CreateObject("ADODB.Parameter") 
  Set l_despacho            = Server.CreateObject("ADODB.Parameter") 
  Set l_caminho             = Server.CreateObject("ADODB.Parameter") 
  Set l_tamanho             = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo                = Server.CreateObject("ADODB.Parameter")
  Set l_nome_original       = Server.CreateObject("ADODB.Parameter")  
  with sp
     set l_menu                 = .CreateParameter("l_menu",            adInteger, adParamInput,    , p_menu)
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,    , p_chave)
     set l_pessoa               = .CreateParameter("l_pessoa",          adInteger, adParamInput,    , p_pessoa)
     set l_tramite              = .CreateParameter("l_tramite",         adInteger, adParamInput,    , p_tramite)
     set l_novo_tramite         = .CreateParameter("l_novo_tramite",    adInteger, adParamInput,    , Tvl(p_novo_tramite))
     set l_devolucao            = .CreateParameter("l_devolucao",       adVarchar, adParamInput,   1, p_devolucao)
     set l_observacao           = .CreateParameter("l_observacao",      adVarchar, adParamInput,2000, p_observacao)
     set l_destinatario         = .CreateParameter("l_destinatario",    adInteger, adParamInput,    , Tvl(p_destinatario))
     set l_despacho             = .CreateParameter("l_despacho",        adVarchar, adParamInput,2000, p_despacho)
     set l_caminho              = .CreateParameter("l_caminho",         adVarchar, adParamInput, 255, Tvl(p_caminho))
     set l_tamanho              = .CreateParameter("l_tamanho",         adInteger, adParamInput,    , Tvl(p_tamanho))
     set l_tipo                 = .CreateParameter("l_tipo",            adVarchar, adParamInput,  60, Tvl(p_tipo))
     set l_nome_original        = .CreateParameter("l_nome_original",   adVarchar, adParamInput, 255, Tvl(p_nome_original))
     .parameters.Append         l_menu
     .parameters.Append         l_chave
     .parameters.Append         l_pessoa
     .parameters.Append         l_tramite
     .parameters.Append         l_novo_tramite
     .parameters.Append         l_devolucao
     .parameters.Append         l_observacao
     .parameters.Append         l_destinatario
     .parameters.Append         l_despacho
     .parameters.Append         l_caminho
     .parameters.Append         l_tamanho
     .parameters.Append         l_tipo
     .parameters.Append         l_nome_original
     .CommandText               = Session("schema") & "SP_PutLancamentoEnvio"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_menu"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_pessoa"
     .parameters.Delete         "l_tramite"
     .parameters.Delete         "l_novo_tramite"
     .parameters.Delete         "l_devolucao"
     .parameters.Delete         "l_observacao"
     .parameters.Delete         "l_destinatario"
     .parameters.Delete         "l_despacho"
     .parameters.Delete         "l_caminho"
     .parameters.Delete         "l_tamanho"
     .parameters.Delete         "l_tipo"
     .parameters.Delete         "l_nome_original"
  end with
End Sub

REM =========================================================================
REM Conclui o lançamento financeiro
REM -------------------------------------------------------------------------
Sub DML_PutFinanceiroConc(p_menu, p_chave, p_pessoa, p_tramite, p_quitacao, p_valor_real, p_codigo_deposito, p_observacao,  _
        p_caminho, p_tamanho, P_tipo, p_nome_original)
  Dim l_menu, l_chave, l_pessoa, l_tramite
  Dim l_quitacao, l_codigo_deposito, l_observacao, l_valor_real
  Dim l_caminho, l_tamanho, l_tipo, l_nome_original
  
  Set l_menu                = Server.CreateObject("ADODB.Parameter") 
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_pessoa              = Server.CreateObject("ADODB.Parameter") 
  Set l_tramite             = Server.CreateObject("ADODB.Parameter") 
  Set l_quitacao            = Server.CreateObject("ADODB.Parameter") 
  Set l_valor_real          = Server.CreateObject("ADODB.Parameter") 
  Set l_codigo_deposito     = Server.CreateObject("ADODB.Parameter") 
  Set l_observacao          = Server.CreateObject("ADODB.Parameter") 
  Set l_caminho             = Server.CreateObject("ADODB.Parameter") 
  Set l_tamanho             = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo                = Server.CreateObject("ADODB.Parameter")
  Set l_nome_original       = Server.CreateObject("ADODB.Parameter")  
  with sp
     set l_menu                 = .CreateParameter("l_menu",            adInteger, adParamInput,    , p_menu)
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,    , p_chave)
     set l_pessoa               = .CreateParameter("l_pessoa",          adInteger, adParamInput,    , p_pessoa)
     set l_tramite              = .CreateParameter("l_tramite",         adInteger, adParamInput,    , p_tramite)
     set l_quitacao             = .CreateParameter("l_quitacao",        adDate,    adParamInput,    , p_quitacao)
     set l_valor_real           = .CreateParameter("l_valor_real",      adNumeric ,adParamInput)
     l_valor_real.Precision    = 18
     l_valor_real.NumericScale = 2
     l_valor_real.Value        = p_valor_real
     set l_codigo_deposito      = .CreateParameter("l_codigo_deposito", adVarchar, adParamInput,  50, Tvl(p_codigo_deposito))
     set l_observacao           = .CreateParameter("l_observacao",  adVarchar, adParamInput,     500, Tvl(p_observacao))
     set l_caminho              = .CreateParameter("l_caminho",         adVarchar, adParamInput, 255, Tvl(p_caminho))
     set l_tamanho              = .CreateParameter("l_tamanho",         adInteger, adParamInput,    , Tvl(p_tamanho))
     set l_tipo                 = .CreateParameter("l_tipo",            adVarchar, adParamInput,  60, Tvl(p_tipo))
     set l_nome_original        = .CreateParameter("l_nome_original",   adVarchar, adParamInput, 255, Tvl(p_nome_original))
     .parameters.Append         l_menu
     .parameters.Append         l_chave
     .parameters.Append         l_pessoa
     .parameters.Append         l_tramite
     .parameters.Append         l_quitacao
     .parameters.Append         l_valor_real
     .parameters.Append         l_codigo_deposito
     .parameters.Append         l_observacao
     .parameters.Append         l_caminho
     .parameters.Append         l_tamanho
     .parameters.Append         l_tipo
     .parameters.Append         l_nome_original
     .CommandText               = Session("schema") & "SP_PutFinanceiroConc"
     'On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_menu"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_pessoa"
     .parameters.Delete         "l_tramite"
     .parameters.Delete         "l_quitacao"
     .parameters.Delete         "l_valor_real"
     .parameters.Delete         "l_codigo_deposito"
     .parameters.Delete         "l_observacao"
     .parameters.Delete         "l_caminho"
     .parameters.Delete         "l_tamanho"
     .parameters.Delete         "l_tipo"
     .parameters.Delete         "l_nome_original"
  end with
End Sub

%>