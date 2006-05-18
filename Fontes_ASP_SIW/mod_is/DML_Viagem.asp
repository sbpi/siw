<%
REM =========================================================================
REM Grava a tela de dados gerais de um acordo
REM -------------------------------------------------------------------------
Sub DML_PutViagemGeral(Operacao, p_cliente, p_chave, p_menu, p_unidade, p_unid_resp, _
    p_solicitante, p_cadastrador, p_tipo, p_descricao, p_justificativa, _
    p_inicio, p_fim, p_data_hora, p_aviso, p_dias, p_projeto, p_tarefa, p_cpf, p_nome, _
    p_nome_resumido, p_sexo, p_vinculo, p_inicio_atual, p_chave_nova, p_copia, p_codigo_interno)
    
  Dim l_Operacao, l_cliente, l_chave, l_menu, l_unidade, l_unid_resp, l_solicitante
  Dim l_cadastrador, l_tipo, l_descricao, l_justificativa
  Dim l_inicio, l_fim, l_data_hora, l_aviso, l_dias
  Dim l_projeto, l_cpf, l_tarefa, l_nome, l_nome_resumido 
  Dim l_sexo, l_inicio_atual, l_vinculo
  Dim l_chave_nova, l_copia, l_codigo_interno
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_cliente             = Server.CreateObject("ADODB.Parameter") 
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_menu                = Server.CreateObject("ADODB.Parameter") 
  Set l_unidade             = Server.CreateObject("ADODB.Parameter") 
  Set l_unid_resp           = Server.CreateObject("ADODB.Parameter") 
  Set l_solicitante         = Server.CreateObject("ADODB.Parameter") 
  Set l_cadastrador         = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo                = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao           = Server.CreateObject("ADODB.Parameter") 
  Set l_justificativa       = Server.CreateObject("ADODB.Parameter") 
  Set l_inicio              = Server.CreateObject("ADODB.Parameter") 
  Set l_fim                 = Server.CreateObject("ADODB.Parameter") 
  Set l_data_hora           = Server.CreateObject("ADODB.Parameter") 
  Set l_aviso               = Server.CreateObject("ADODB.Parameter") 
  Set l_dias                = Server.CreateObject("ADODB.Parameter") 
  Set l_projeto             = Server.CreateObject("ADODB.Parameter") 
  Set l_tarefa              = Server.CreateObject("ADODB.Parameter") 
  Set l_cpf                 = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                = Server.CreateObject("ADODB.Parameter") 
  Set l_nome_resumido       = Server.CreateObject("ADODB.Parameter") 
  Set l_sexo                = Server.CreateObject("ADODB.Parameter") 
  Set l_vinculo             = Server.CreateObject("ADODB.Parameter") 
  Set l_inicio_atual        = Server.CreateObject("ADODB.Parameter") 
  Set l_chave_nova          = Server.CreateObject("ADODB.Parameter") 
  Set l_copia               = Server.CreateObject("ADODB.Parameter") 
  Set l_codigo_interno      = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",        adVarchar, adParamInput,   1, Operacao)
     set l_cliente              = .CreateParameter("l_cliente",         adInteger, adParamInput,    , p_cliente)
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,    , Tvl(p_chave))
     set l_menu                 = .CreateParameter("l_menu",            adInteger, adParamInput,    , p_menu)
     set l_unidade              = .CreateParameter("l_unidade",         adInteger, adParamInput,    , Tvl(p_unidade))
     set l_unid_resp            = .CreateParameter("l_unid_resp",       adInteger, adParamInput,    , Tvl(p_unid_resp))
     set l_solicitante          = .CreateParameter("l_solicitante",     adInteger, adParamInput,    , Tvl(p_solicitante))
     set l_cadastrador          = .CreateParameter("l_cadastrador",     adInteger, adParamInput,    , Tvl(p_cadastrador))
     set l_tipo                 = .CreateParameter("l_tipo",            adVarchar, adParamInput,   1, Tvl(p_tipo))
     set l_descricao            = .CreateParameter("l_descricao",       adVarchar, adParamInput,2000, Tvl(p_descricao))
     set l_justificativa        = .CreateParameter("l_justificativa",   adVarchar, adParamInput,2000, Tvl(p_justificativa))
     set l_inicio               = .CreateParameter("l_inicio",          adDate,    adParamInput,    , Tvl(p_inicio))
     set l_fim                  = .CreateParameter("l_fim",             adDate,    adParamInput,    , Tvl(p_fim))
     set l_data_hora            = .CreateParameter("l_data_hora",       adVarchar, adParamInput,   1, Tvl(p_data_hora))
     set l_aviso                = .CreateParameter("l_aviso",           adVarchar, adParamInput,   1, Tvl(p_aviso))
     set l_dias                 = .CreateParameter("l_dias",            adInteger, adParamInput,    , Nvl(p_dias,0))
     set l_projeto              = .CreateParameter("l_projeto",         adInteger, adParamInput,    , Tvl(p_projeto))
     set l_tarefa               = .CreateParameter("l_tarefa",          adInteger, adParamInput,    , Tvl(p_tarefa))
     set l_cpf                  = .CreateParameter("l_cpf",             adVarchar, adParamInput,  14, Tvl(p_cpf))
     set l_nome                 = .CreateParameter("l_nome",            adVarchar, adParamInput,  60, Tvl(p_nome))
     set l_nome_resumido        = .CreateParameter("l_nome_resumido",   adVarchar, adParamInput,  15, Tvl(p_nome_resumido))
     set l_sexo                 = .CreateParameter("l_sexo",            adVarchar, adParamInput,   1, Tvl(p_sexo))
     set l_vinculo              = .CreateParameter("l_vinculo",         adInteger, adParamInput,    , Tvl(p_vinculo))
     set l_inicio_atual         = .CreateParameter("l_inicio_atual",    adDate,    adParamInput,    , Tvl(p_inicio_atual))
     set l_chave_nova           = .CreateParameter("l_chave_nova",      adInteger, adParamOutput,   , null)
     set l_copia                = .CreateParameter("l_copia",           adInteger, adParamInput,    , Tvl(p_copia))
     set l_codigo_interno       = .CreateParameter("l_codigo_interno",  adVarchar, adParamOutput, 60, null)
     .parameters.Append         l_Operacao
     .parameters.Append         l_cliente
     .parameters.Append         l_chave
     .parameters.Append         l_menu
     .parameters.Append         l_unidade
     .parameters.Append         l_unid_resp
     .parameters.Append         l_solicitante
     .parameters.Append         l_cadastrador
     .parameters.Append         l_tipo
     .parameters.Append         l_descricao
     .parameters.Append         l_justificativa
     .parameters.Append         l_inicio
     .parameters.Append         l_fim
     .parameters.Append         l_data_hora
     .parameters.Append         l_aviso
     .parameters.Append         l_dias
     .parameters.Append         l_projeto
     .parameters.Append         l_tarefa
     .parameters.Append         l_cpf
     .parameters.Append         l_nome
     .parameters.Append         l_nome_resumido
     .parameters.Append         l_sexo
     .parameters.Append         l_vinculo
     .parameters.Append         l_inicio_atual
     .parameters.Append         l_chave_nova
     .parameters.Append         l_copia
     .parameters.Append         l_codigo_interno
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_PutViagemGeral"
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
     .parameters.Delete         "l_unid_resp"
     .parameters.Delete         "l_solicitante"
     .parameters.Delete         "l_cadastrador"
     .parameters.Delete         "l_tipo"
     .parameters.Delete         "l_descricao"
     .parameters.Delete         "l_justificativa"
     .parameters.Delete         "l_inicio"
     .parameters.Delete         "l_fim"
     .parameters.Delete         "l_data_hora"
     .parameters.Delete         "l_aviso"
     .parameters.Delete         "l_dias"
     .parameters.Delete         "l_projeto"
     .parameters.Delete         "l_tarefa"
     .parameters.Delete         "l_cpf"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_nome_resumido"
     .parameters.Delete         "l_sexo"
     .parameters.Delete         "l_vinculo"
     .parameters.Delete         "l_inicio_atual"
     .parameters.Delete         "l_chave_nova"
     .parameters.Delete         "l_copia"
     .parameters.Delete         "l_codigo_interno"
  end with
End Sub

REM =========================================================================
REM Grava a tela de parcelas
REM -------------------------------------------------------------------------
Sub DML_PutPD_Deslocamento(Operacao, p_chave, p_chave_aux, _
    p_origem, p_data_saida, p_hora_saida, p_destino, p_data_chegada, p_hora_chegada, _
    p_sq_cia_transporte, p_codigo_voo)
    
  Dim l_Operacao, l_chave, l_chave_aux
  Dim l_ordem, l_data, l_valor, l_observacao
  Dim l_origem, l_data_saida, l_hora_saida
  Dim l_destino, l_data_chegada, l_hora_chegada
  Dim l_sq_cia_transporte, l_codigo_voo
  
  Set l_Operacao          = Server.CreateObject("ADODB.Parameter")
  Set l_chave             = Server.CreateObject("ADODB.Parameter") 
  Set l_chave_aux         = Server.CreateObject("ADODB.Parameter") 
  Set l_origem            = Server.CreateObject("ADODB.Parameter") 
  Set l_data_saida        = Server.CreateObject("ADODB.Parameter") 
  Set l_hora_saida        = Server.CreateObject("ADODB.Parameter") 
  Set l_destino           = Server.CreateObject("ADODB.Parameter") 
  Set l_data_chegada      = Server.CreateObject("ADODB.Parameter") 
  Set l_hora_chegada      = Server.CreateObject("ADODB.Parameter")
  Set l_sq_cia_transporte = Server.CreateObject("ADODB.Parameter") 
  Set l_codigo_voo        = Server.CreateObject("ADODB.Parameter")  
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",          adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",             adInteger, adParamInput,    , Tvl(p_chave))
     set l_chave_aux            = .CreateParameter("l_chave_aux",         adInteger, adParamInput,    , Tvl(p_chave_aux))
     set l_origem               = .CreateParameter("l_origem",            adInteger, adParamInput,    , Tvl(p_origem))
     set l_data_saida           = .CreateParameter("l_data_saida",        adDate,    adParamInput,    , Tvl(p_data_saida))
     set l_hora_saida           = .CreateParameter("l_hora_saida",        adVarchar, adParamInput,   5, Tvl(p_hora_saida))
     set l_destino              = .CreateParameter("l_destino",           adInteger, adParamInput,    , Tvl(p_destino))
     set l_data_chegada         = .CreateParameter("l_data_chegada",      adDate,    adParamInput,    , Tvl(p_data_chegada))
     set l_hora_chegada         = .CreateParameter("l_hora_chegada",      adVarchar, adParamInput,   5, Tvl(p_hora_chegada))
     set l_sq_cia_transporte    = .CreateParameter("l_sq_cia_transporte", adInteger, adParamInput,    , Tvl(p_sq_cia_transporte))
     set l_codigo_voo           = .CreateParameter("l_codigo_voo",        adVarchar, adParamInput,  30, Tvl(p_codigo_voo))
     .parameters.Append         l_Operacao
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_origem
     .parameters.Append         l_data_saida
     .parameters.Append         l_hora_saida
     .parameters.Append         l_destino
     .parameters.Append         l_data_chegada
     .parameters.Append         l_hora_chegada
     .parameters.Append         l_sq_cia_transporte
     .parameters.Append         l_codigo_voo
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_PutPD_Deslocamento"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_chave_aux"
     .parameters.Delete         "l_origem"
     .parameters.Delete         "l_data_saida"
     .parameters.Delete         "l_hora_saida"
     .parameters.Delete         "l_destino"
     .parameters.Delete         "l_data_chegada"
     .parameters.Delete         "l_hora_chegada"
     .parameters.Delete         "l_sq_cia_transporte"
     .parameters.Delete         "l_codigo_voo"
  end with
End Sub

REM =========================================================================
REM Grava a tela de outra parte
REM -------------------------------------------------------------------------
Sub DML_PutViagemOutra (Operacao, p_restricao, p_chave, p_chave_aux, p_sq_pessoa, _
    p_cpf, p_nome, p_nome_resumido, p_sexo, p_vinculo, p_matricula, p_rg_numero, p_rg_emissao, _
    p_rg_emissor, p_ddd, p_nr_telefone, p_nr_fax, p_nr_celular, p_sq_agencia, p_op_conta, p_nr_conta, _
    p_sq_pais_estrang, p_aba_code, p_swift_code, p_endereco_estrang, p_banco_estrang, _
    p_agencia_estrang, p_cidade_estrang, p_informacoes, p_codigo_deposito)
    
  Dim l_Operacao, l_restricao, l_chave, l_chave_aux, l_sq_pessoa
  Dim l_cpf, l_nome, l_nome_resumido, l_sexo, l_vinculo, l_matricula, l_rg_numero, l_rg_emissao
  Dim l_rg_emissor, l_ddd, l_nr_telefone, l_nr_fax, l_nr_celular
  Dim l_sq_agencia, l_op_conta, l_nr_conta
  Dim l_sq_pais_estrang, l_aba_code, l_swift_code, l_endereco_estrang, l_banco_estrang
  Dim l_agencia_estrang, l_cidade_estrang, l_informacoes, l_codigo_deposito
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_restricao           = Server.CreateObject("ADODB.Parameter")
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_chave_aux           = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_pessoa           = Server.CreateObject("ADODB.Parameter") 
  Set l_cpf                 = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                = Server.CreateObject("ADODB.Parameter") 
  Set l_nome_resumido       = Server.CreateObject("ADODB.Parameter") 
  Set l_sexo                = Server.CreateObject("ADODB.Parameter") 
  Set l_vinculo             = Server.CreateObject("ADODB.Parameter") 
  Set l_matricula           = Server.CreateObject("ADODB.Parameter") 
  Set l_rg_numero           = Server.CreateObject("ADODB.Parameter") 
  Set l_rg_emissao          = Server.CreateObject("ADODB.Parameter") 
  Set l_rg_emissor          = Server.CreateObject("ADODB.Parameter") 
  Set l_ddd                 = Server.CreateObject("ADODB.Parameter")
  Set l_nr_telefone         = Server.CreateObject("ADODB.Parameter")
  Set l_nr_fax              = Server.CreateObject("ADODB.Parameter")
  Set l_nr_celular          = Server.CreateObject("ADODB.Parameter")
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
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",            adVarchar, adParamInput,   1, Operacao)
     set l_restricao            = .CreateParameter("l_restricao",           adVarchar, adParamInput,  10, p_restricao)
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,    , Tvl(p_chave))
     set l_chave_aux            = .CreateParameter("l_chave_aux",           adInteger, adParamInput,    , Tvl(p_chave_aux))
     set l_sq_pessoa            = .CreateParameter("l_sq_pessoa",           adInteger, adParamInput,    , Tvl(p_sq_pessoa))
     set l_cpf                  = .CreateParameter("l_cpf",                 adVarchar, adParamInput,  14, Tvl(p_cpf))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,  60, Tvl(p_nome))
     set l_nome_resumido        = .CreateParameter("l_nome_resumido",       adVarchar, adParamInput,  15, Tvl(p_nome_resumido))
     set l_sexo                 = .CreateParameter("l_sexo",                adVarchar, adParamInput,   1, Tvl(p_sexo))
     set l_vinculo              = .CreateParameter("l_vinculo",             adInteger, adParamInput,    , Tvl(p_vinculo))
     set l_matricula            = .CreateParameter("l_matricula",           adVarchar, adParamInput,  20, Tvl(p_matricula))
     set l_rg_numero            = .CreateParameter("l_rg_numero",           adVarchar, adParamInput,  30, Tvl(p_rg_numero))
     set l_rg_emissao           = .CreateParameter("l_rg_emissao",          adDate,    adParamInput,    , Tvl(p_rg_emissao))
     set l_rg_emissor           = .CreateParameter("l_rg_emissor",          adVarchar, adParamInput,  30, Tvl(p_rg_emissor))
     set l_ddd                  = .CreateParameter("l_ddd",                 adVarchar, adParamInput,   4, Tvl(p_ddd))
     set l_nr_telefone          = .CreateParameter("l_nr_telefone",         adVarchar, adParamInput,  25, Tvl(p_nr_telefone))
     set l_nr_fax               = .CreateParameter("l_nr_fax",              adVarchar, adParamInput,  25, Tvl(p_nr_fax))
     set l_nr_celular           = .CreateParameter("l_nr_celular",          adVarchar, adParamInput,  25, Tvl(p_nr_celular))
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
     .parameters.Append         l_Operacao
     .parameters.Append         l_restricao
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_sq_pessoa 
     .parameters.Append         l_cpf
     .parameters.Append         l_nome 
     .parameters.Append         l_nome_resumido 
     .parameters.Append         l_sexo 
     .parameters.Append         l_vinculo
     .parameters.Append         l_matricula 
     .parameters.Append         l_rg_numero 
     .parameters.Append         l_rg_emissao 
     .parameters.Append         l_rg_emissor 
     .parameters.Append         l_ddd 
     .parameters.Append         l_nr_telefone 
     .parameters.Append         l_nr_fax 
     .parameters.Append         l_nr_celular 
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
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_PutViagemOutra"
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
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_nome_resumido"
     .parameters.Delete         "l_sexo"
     .parameters.Delete         "l_vinculo"
     .parameters.Delete         "l_matricula"
     .parameters.Delete         "l_rg_numero"
     .parameters.Delete         "l_rg_emissao" 
     .parameters.Delete         "l_rg_emissor"
     .parameters.Delete         "l_passaporte"
     .parameters.Delete         "l_ddd"
     .parameters.Delete         "l_nr_telefone"
     .parameters.Delete         "l_nr_fax"
     .parameters.Delete         "l_nr_celular"
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
  end with
End Sub

REM =========================================================================
REM Grava a tela de parcelas
REM -------------------------------------------------------------------------
Sub DML_PutPDTarefa(Operacao, p_chave, p_tarefa)
    
  Dim l_Operacao, l_chave, l_tarefa
  
  Set l_Operacao        = Server.CreateObject("ADODB.Parameter")
  Set l_chave           = Server.CreateObject("ADODB.Parameter") 
  Set l_tarefa          = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",        adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,    , p_chave)
     set l_tarefa               = .CreateParameter("l_tarefa",          adInteger, adParamInput,    , p_tarefa)
     .parameters.Append         l_Operacao
     .parameters.Append         l_chave
     .parameters.Append         l_tarefa
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_PutPDTarefa"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_tarefa"
  end with
End Sub

REM =========================================================================
REM Encaminha a solicitacao
REM -------------------------------------------------------------------------
Sub DML_PutViagemEnvio(p_menu, p_chave, p_pessoa, p_tramite, p_devolucao, p_despacho, p_justificativa)
  Dim l_Operacao, l_menu, l_chave, l_pessoa, l_tramite, l_devolucao, l_despacho, l_justificativa
  
  Set l_menu                = Server.CreateObject("ADODB.Parameter") 
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_pessoa              = Server.CreateObject("ADODB.Parameter") 
  Set l_tramite             = Server.CreateObject("ADODB.Parameter") 
  Set l_devolucao           = Server.CreateObject("ADODB.Parameter") 
  Set l_despacho            = Server.CreateObject("ADODB.Parameter") 
  Set l_justificativa             = Server.CreateObject("ADODB.Parameter") 
  with sp
     set l_menu                 = .CreateParameter("l_menu",            adInteger, adParamInput,    , p_menu)
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,    , p_chave)
     set l_pessoa               = .CreateParameter("l_pessoa",          adInteger, adParamInput,    , p_pessoa)
     set l_tramite              = .CreateParameter("l_tramite",         adInteger, adParamInput,    , p_tramite)
     set l_devolucao            = .CreateParameter("l_devolucao",       adVarchar, adParamInput,   1, p_devolucao)
     set l_despacho             = .CreateParameter("l_despacho",        adVarchar, adParamInput,2000, tvl(p_despacho))
     set l_justificativa        = .CreateParameter("l_justificativa",   adVarchar, adParamInput,2000, tvl(p_justificativa))
     .parameters.Append         l_menu
     .parameters.Append         l_chave
     .parameters.Append         l_pessoa
     .parameters.Append         l_tramite
     .parameters.Append         l_devolucao
     .parameters.Append         l_despacho
     .parameters.Append         l_justificativa
     .CommandText               = Session("schema") & "SP_PutViagemEnvio"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_menu"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_pessoa"
     .parameters.Delete         "l_tramite"
     .parameters.Delete         "l_devolucao"
     .parameters.Delete         "l_despacho"
     .parameters.Delete         "l_justificativa"
  end with
End Sub

REM =========================================================================
REM Conclui o acordo
REM -------------------------------------------------------------------------
Sub DML_PutAcordoConc(p_menu, p_chave, p_pessoa, p_tramite, p_inicio_real, p_fim_real, _
        p_nota_conclusao, p_custo_real, p_tipo)
  Dim l_menu, l_chave, l_pessoa, l_tramite
  Dim l_inicio_real, l_fim_real, l_nota_conclusao, l_custo_real
  Dim l_tipo
  
  Set l_menu                = Server.CreateObject("ADODB.Parameter") 
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_pessoa              = Server.CreateObject("ADODB.Parameter") 
  Set l_tramite             = Server.CreateObject("ADODB.Parameter") 
  Set l_inicio_real         = Server.CreateObject("ADODB.Parameter") 
  Set l_fim_real            = Server.CreateObject("ADODB.Parameter") 
  Set l_nota_conclusao      = Server.CreateObject("ADODB.Parameter") 
  Set l_custo_real          = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo                = Server.CreateObject("ADODB.Parameter") 
  with sp
     set l_menu                 = .CreateParameter("l_menu",            adInteger, adParamInput,    , p_menu)
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,    , p_chave)
     set l_pessoa               = .CreateParameter("l_pessoa",          adInteger, adParamInput,    , p_pessoa)
     set l_tramite              = .CreateParameter("l_tramite",         adInteger, adParamInput,    , p_tramite)
     set l_inicio_real          = .CreateParameter("l_inicio_real",     adDate,    adParamInput,    , Tvl(p_inicio_real))
     set l_fim_real             = .CreateParameter("l_fim_real",        adDate,    adParamInput,    , Tvl(p_fim_real))
     set l_nota_conclusao       = .CreateParameter("l_nota_conclusao",  adVarchar, adParamInput,2000, Tvl(p_nota_conclusao))
     set l_custo_real           = .CreateParameter("l_custo_real",      adNumeric ,adParamInput)
     l_custo_real.Precision     = 18
     l_custo_real.NumericScale  = 2
     l_custo_real.Value         = Tvl(p_custo_real)
     set l_tipo                 = .CreateParameter("l_tipo",            adInteger, adParamInput,    , Tvl(p_tipo))
     .parameters.Append         l_menu
     .parameters.Append         l_chave
     .parameters.Append         l_pessoa
     .parameters.Append         l_tramite
     .parameters.Append         l_inicio_real
     .parameters.Append         l_fim_real
     .parameters.Append         l_nota_conclusao
     .parameters.Append         l_custo_real
     .parameters.Append         l_tipo
     .CommandText               = Session("schema") & "SP_PutAcordoConc"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_menu"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_pessoa"
     .parameters.Delete         "l_tramite"
     .parameters.Delete         "l_inicio_real"
     .parameters.Delete         "l_fim_real"
     .parameters.Delete         "l_nota_conclusao"
     .parameters.Delete         "l_custo_real"
     .parameters.Delete         "l_tipo"
  end with
End Sub

REM =========================================================================
REM Atualiza os dados financeiros em PD_MISSAO
REM -------------------------------------------------------------------------
Sub DML_PutPDMissao(Operacao, p_chave, p_valor_alimentacao, p_valor_transporte, p_valor_adicional, _
                    p_desconto_alimentacao, p_desconto_transporte, p_pta, p_emissao_bilhete, p_valor_passagem, p_restricao)
    
  Dim l_Operacao, l_chave, l_valor_alimentacao, l_valor_transporte, l_valor_adicional
  Dim l_desconto_alimentacao, l_desconto_transporte, l_pta, l_emissao_bilhete, l_valor_passagem, l_restricao
  
  Set l_Operacao              = Server.CreateObject("ADODB.Parameter")
  Set l_chave                 = Server.CreateObject("ADODB.Parameter") 
  Set l_valor_alimentacao     = Server.CreateObject("ADODB.Parameter")
  Set l_valor_transporte      = Server.CreateObject("ADODB.Parameter")  
  Set l_valor_adicional       = Server.CreateObject("ADODB.Parameter") 
  Set l_desconto_alimentacao  = Server.CreateObject("ADODB.Parameter") 
  Set l_desconto_transporte   = Server.CreateObject("ADODB.Parameter") 
  Set l_pta                   = Server.CreateObject("ADODB.Parameter")
  Set l_emissao_bilhete       = Server.CreateObject("ADODB.Parameter")
  Set l_valor_passagem        = Server.CreateObject("ADODB.Parameter")
  Set l_restricao             = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_Operacao                 = .CreateParameter("l_operacao",             adVarchar, adParamInput,   1, Operacao)
     set l_chave                    = .CreateParameter("l_chave",                adInteger, adParamInput,    , p_chave)
     set l_valor_alimentacao        = .CreateParameter("l_valor_alimentacao",    adNumeric ,adParamInput)
     l_valor_alimentacao.Precision       = 18
     l_valor_alimentacao.NumericScale    = 2
     l_valor_alimentacao.Value           = Tvl(p_valor_alimentacao)
     set l_valor_transporte         = .CreateParameter("l_valor_transporte",     adNumeric ,adParamInput)
     l_valor_transporte.Precision        = 18
     l_valor_transporte.NumericScale     = 2
     l_valor_transporte.Value            = Tvl(p_valor_transporte)
     set l_valor_adicional          = .CreateParameter("l_valor_adicional",      adNumeric ,adParamInput)
     l_valor_adicional.Precision         = 18
     l_valor_adicional.NumericScale      = 2
     l_valor_adicional.Value             = Tvl(p_valor_adicional)
     set l_desconto_alimentacao     = .CreateParameter("l_desconto_alimentacao", adNumeric ,adParamInput)
     l_desconto_alimentacao.Precision    = 18
     l_desconto_alimentacao.NumericScale = 2
     l_desconto_alimentacao.Value        = Tvl(p_desconto_alimentacao)
     set l_desconto_transporte      = .CreateParameter("l_desconto_transporte",  adNumeric ,adParamInput)
     l_desconto_transporte.Precision     = 18
     l_desconto_transporte.NumericScale  = 2
     l_desconto_transporte.Value         = Tvl(p_desconto_transporte)
     set l_pta                      = .CreateParameter("l_pta",                  adVarchar, adParamInput,  30, Tvl(p_pta))
     set l_emissao_bilhete          = .CreateParameter("l_emissao_bilhete",      adDate,    adParamInput,    , Tvl(p_emissao_bilhete))
     set l_valor_passagem           = .CreateParameter("l_valor_passagem",       adNumeric ,adParamInput)
     l_valor_passagem.Precision          = 18
     l_valor_passagem.NumericScale       = 2
     l_valor_passagem.Value              = Tvl(p_valor_passagem)
     set l_restricao                = .CreateParameter("l_restricao",            adVarchar, adParamInput,  30, p_restricao)     
     .parameters.Append         l_Operacao
     .parameters.Append         l_chave
     .parameters.Append         l_valor_alimentacao
     .parameters.Append         l_valor_transporte
     .parameters.Append         l_valor_adicional
     .parameters.Append         l_desconto_alimentacao
     .parameters.Append         l_desconto_transporte
     .parameters.Append         l_pta
     .parameters.Append         l_emissao_bilhete          
     .parameters.Append         l_valor_passagem 
     .parameters.Append         l_restricao
     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_PutPDMissao"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_valor_alimentacao"
     .parameters.Delete         "l_valor_transporte"
     .parameters.Delete         "l_valor_adicional"
     .parameters.Delete         "l_desconto_alimentacao"
     .parameters.Delete         "l_desconto_transporte"
     .parameters.Delete         "l_pta"
     .parameters.Delete         "l_emissao_bilhete"
     .parameters.Delete         "l_valor_passagem"
     .parameters.Delete         "l_restricao"
  end with
End Sub

REM =========================================================================
REM Grava os dados das diárias
REM -------------------------------------------------------------------------
Sub DML_PutPDDiaria(Operacao, p_chave, p_sq_diaria, p_sq_cidade, p_quantidade, p_valor)
    
  Dim l_Operacao, l_chave, l_sq_diaria, l_sq_cidade, l_quantidade, l_valor
  
  Set l_Operacao              = Server.CreateObject("ADODB.Parameter")
  Set l_chave                 = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_diaria             = Server.CreateObject("ADODB.Parameter")
  Set l_sq_cidade             = Server.CreateObject("ADODB.Parameter")  
  Set l_quantidade            = Server.CreateObject("ADODB.Parameter") 
  Set l_valor                 = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao           = .CreateParameter("l_operacao",             adVarchar, adParamInput,   1, Operacao)
     set l_chave              = .CreateParameter("l_chave",                adInteger, adParamInput,    , p_chave)
     set l_sq_diaria          = .CreateParameter("l_sq_diaria",            adInteger, adParamInput,    , p_sq_diaria)
     set l_sq_cidade          = .CreateParameter("l_sq_cidade",            adInteger, adParamInput,    , p_sq_cidade)
     set l_quantidade         = .CreateParameter("l_quantidade",           adNumeric ,adParamInput)
     l_quantidade.Precision     = 5
     l_quantidade.NumericScale  = 1
     l_quantidade.Value         = Tvl(p_quantidade)
     set l_valor              = .CreateParameter("l_valor",                adNumeric ,adParamInput)
     l_valor.Precision          = 18
     l_valor.NumericScale       = 2
     l_valor.Value              = Tvl(p_valor)
     .parameters.Append         l_Operacao
     .parameters.Append         l_chave
     .parameters.Append         l_sq_diaria
     .parameters.Append         l_sq_cidade
     .parameters.Append         l_quantidade
     .parameters.Append         l_valor
     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_PutPDDiaria"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_sq_diaria"
     .parameters.Delete         "l_sq_cidade"
     .parameters.Delete         "l_quantidade"
     .parameters.Delete         "l_valor"
  end with
End Sub
%>