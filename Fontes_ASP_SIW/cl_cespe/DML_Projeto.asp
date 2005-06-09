<%
REM =========================================================================
REM Grava a tela de outra parte
REM -------------------------------------------------------------------------
Sub DML_PutProjetoOutra (Operacao, p_restricao, p_chave, p_chave_aux, p_sq_pessoa, _
    p_cpf, p_cnpj, p_nome, p_nome_resumido, p_sexo, p_nascimento, p_rg_numero, p_rg_emissao, _
    p_rg_emissor, p_passaporte, p_sq_pais_passaporte, p_inscricao_estadual, p_logradouro, _
    p_complemento, p_bairro, p_sq_cidade, p_cep, p_ddd, p_nr_telefone, _
    p_nr_fax, p_nr_celular, p_email, p_sq_agencia, p_op_conta, p_nr_conta, p_pessoa_atual)
    
  Dim l_Operacao, l_restricao, l_chave, l_chave_aux, l_sq_pessoa
  Dim l_cpf, l_cnpj, l_nome, l_nome_resumido, l_sexo, l_nascimento, l_rg_numero, l_rg_emissao
  Dim l_rg_emissor, l_passaporte, l_sq_pais_passaporte, l_inscricao_estadual, l_logradouro
  Dim l_complemento, l_bairro, l_sq_cidade, l_cep, l_ddd, l_nr_telefone
  Dim l_nr_fax, l_nr_celular, l_email, l_sq_agencia, l_op_conta, l_nr_conta, l_pessoa_atual
  
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
  Set l_pessoa_atual        = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",            adVarchar, adParamInput,   1, Operacao)
     set l_restricao            = .CreateParameter("l_restricao",           adVarchar, adParamInput,  10, p_restricao)
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,    , Tvl(p_chave))
     set l_chave_aux            = .CreateParameter("l_chave_aux",           adInteger, adParamInput,    , Tvl(p_chave_aux))
     set l_sq_pessoa            = .CreateParameter("l_sq_pessoa",           adInteger, adParamInput,    , Tvl(p_sq_pessoa))
     set l_cpf                  = .CreateParameter("l_cpf",                 adVarchar, adParamInput,  14, Tvl(p_cpf))
     set l_cnpj                 = .CreateParameter("l_cnpj",                adVarchar, adParamInput,  18, Tvl(p_cnpj))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,  60, Tvl(p_nome))
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
     .parameters.Append         l_pessoa_atual
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_PutAcordoOutra"
     'On Error Resume Next
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
     .parameters.Delete         "l_pessoa_atual"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Grava a tela de preposto
REM -------------------------------------------------------------------------
Sub DML_PutProjetoPreposto (Operacao, p_restricao, p_chave, p_chave_aux, p_sq_pessoa, _
                            p_cpf, p_nome, p_nome_resumido, p_sexo, p_rg_numero, p_rg_emissao, p_rg_emissor)
    
  Dim l_Operacao, l_restricao, l_chave, l_chave_aux, l_sq_pessoa
  Dim l_cpf, l_nome, l_nome_resumido, l_sexo, l_rg_numero, l_rg_emissao, l_rg_emissor
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_restricao           = Server.CreateObject("ADODB.Parameter")
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_chave_aux           = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_pessoa           = Server.CreateObject("ADODB.Parameter") 
  Set l_cpf                 = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                = Server.CreateObject("ADODB.Parameter") 
  Set l_nome_resumido       = Server.CreateObject("ADODB.Parameter") 
  Set l_sexo                = Server.CreateObject("ADODB.Parameter") 
  Set l_rg_numero           = Server.CreateObject("ADODB.Parameter") 
  Set l_rg_emissao          = Server.CreateObject("ADODB.Parameter") 
  Set l_rg_emissor          = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",      adVarchar, adParamInput,   1, Operacao)
     set l_restricao            = .CreateParameter("l_restricao",     adVarchar, adParamInput,  10, p_restricao)
     set l_chave                = .CreateParameter("l_chave",         adInteger, adParamInput,    , Tvl(p_chave))
     set l_chave_aux            = .CreateParameter("l_chave_aux",     adInteger, adParamInput,    , Tvl(p_chave_aux))
     set l_sq_pessoa            = .CreateParameter("l_sq_pessoa",     adInteger, adParamInput,    , Tvl(p_sq_pessoa))
     set l_cpf                  = .CreateParameter("l_cpf",           adVarchar, adParamInput,  14, Tvl(p_cpf))
     set l_nome                 = .CreateParameter("l_nome",          adVarchar, adParamInput,  60, Tvl(p_nome))
     set l_nome_resumido        = .CreateParameter("l_nome_resumido", adVarchar, adParamInput,  15, Tvl(p_nome_resumido))
     set l_sexo                 = .CreateParameter("l_sexo",          adVarchar, adParamInput,   1, Tvl(p_sexo))
     set l_rg_numero            = .CreateParameter("l_rg_numero",     adVarchar, adParamInput,  30, Tvl(p_rg_numero))
     set l_rg_emissao           = .CreateParameter("l_rg_emissao",    adDate,    adParamInput,    , Tvl(p_rg_emissao))
     set l_rg_emissor           = .CreateParameter("l_rg_emissor",    adVarchar, adParamInput,  30, Tvl(p_rg_emissor))
     .parameters.Append         l_Operacao
     .parameters.Append         l_restricao
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_sq_pessoa
     .parameters.Append         l_cpf
     .parameters.Append         l_nome
     .parameters.Append         l_nome_resumido
     .parameters.Append         l_sexo
     .parameters.Append         l_rg_numero
     .parameters.Append         l_rg_emissao
     .parameters.Append         l_rg_emissor
     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_PutAcordoPreposto"
     'On Error Resume Next
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
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_nome_resumido"
     .parameters.Delete         "l_sexo"
     .parameters.Delete         "l_rg_numero"
     .parameters.Delete         "l_rg_emissao" 
     .parameters.Delete         "l_rg_emissor"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Grava a tela de representantes
REM -------------------------------------------------------------------------
Sub DML_PutProjetoRep (Operacao, p_restricao, p_chave, p_chave_aux, p_sq_pessoa, _
    p_cpf, p_nome, p_nome_resumido, p_sexo, p_rg_numero, p_rg_emissao, _
    p_rg_emissor, p_ddd, p_nr_telefone, p_nr_fax, p_nr_celular, p_email)
    
  Dim l_Operacao, l_restricao, l_chave, l_chave_aux, l_sq_pessoa
  Dim l_cpf, l_nome, l_nome_resumido, l_sexo, l_rg_numero, l_rg_emissao
  Dim l_rg_emissor, l_ddd, l_nr_telefone, l_nr_fax, l_nr_celular, l_email
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_restricao           = Server.CreateObject("ADODB.Parameter")
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_chave_aux           = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_pessoa           = Server.CreateObject("ADODB.Parameter") 
  Set l_cpf                 = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                = Server.CreateObject("ADODB.Parameter") 
  Set l_nome_resumido       = Server.CreateObject("ADODB.Parameter") 
  Set l_sexo                = Server.CreateObject("ADODB.Parameter") 
  Set l_rg_numero           = Server.CreateObject("ADODB.Parameter") 
  Set l_rg_emissao          = Server.CreateObject("ADODB.Parameter") 
  Set l_rg_emissor          = Server.CreateObject("ADODB.Parameter") 
  Set l_ddd                 = Server.CreateObject("ADODB.Parameter")
  Set l_nr_telefone         = Server.CreateObject("ADODB.Parameter")
  Set l_nr_fax              = Server.CreateObject("ADODB.Parameter")
  Set l_nr_celular          = Server.CreateObject("ADODB.Parameter")
  Set l_email               = Server.CreateObject("ADODB.Parameter")
  
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
     set l_rg_numero            = .CreateParameter("l_rg_numero",           adVarchar, adParamInput,  30, Tvl(p_rg_numero))
     set l_rg_emissao           = .CreateParameter("l_rg_emissao",          adDate,    adParamInput,    , Tvl(p_rg_emissao))
     set l_rg_emissor           = .CreateParameter("l_rg_emissor",          adVarchar, adParamInput,  30, Tvl(p_rg_emissor))
     set l_ddd                  = .CreateParameter("l_ddd",                 adVarchar, adParamInput,   4, Tvl(p_ddd))
     set l_nr_telefone          = .CreateParameter("l_nr_telefone",         adVarchar, adParamInput,  25, Tvl(p_nr_telefone))
     set l_nr_fax               = .CreateParameter("l_nr_fax",              adVarchar, adParamInput,  25, Tvl(p_nr_fax))
     set l_nr_celular           = .CreateParameter("l_nr_celular",          adVarchar, adParamInput,  25, Tvl(p_nr_celular))
     set l_email                = .CreateParameter("l_email",               adVarchar, adParamInput,  60, Tvl(p_email))
     
     .parameters.Append         l_Operacao
     .parameters.Append         l_restricao
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_sq_pessoa 
     .parameters.Append         l_cpf
     .parameters.Append         l_nome 
     .parameters.Append         l_nome_resumido 
     .parameters.Append         l_sexo 
     .parameters.Append         l_rg_numero 
     .parameters.Append         l_rg_emissao 
     .parameters.Append         l_rg_emissor 
     .parameters.Append         l_ddd 
     .parameters.Append         l_nr_telefone 
     .parameters.Append         l_nr_fax 
     .parameters.Append         l_nr_celular 
     .parameters.Append         l_email 
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_PutAcordoRep"
     'On Error Resume Next
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
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_nome_resumido"
     .parameters.Delete         "l_sexo"
     .parameters.Delete         "l_rg_numero"
     .parameters.Delete         "l_rg_emissao" 
     .parameters.Delete         "l_rg_emissor"
     .parameters.Delete         "l_ddd"
     .parameters.Delete         "l_nr_telefone"
     .parameters.Delete         "l_nr_fax"
     .parameters.Delete         "l_nr_celular"
     .parameters.Delete         "l_email"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Grava a tela de informações adicionais
REM -------------------------------------------------------------------------
Sub DML_PutInformar (p_chave, p_sq_cidade, p_inicio, p_fim, p_limite_passagem)
    
  Dim l_chave, l_sq_cidade
  Dim l_inicio, l_fim, l_limite_passagem
  
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_cidade           = Server.CreateObject("ADODB.Parameter") 
  Set l_inicio              = Server.CreateObject("ADODB.Parameter") 
  Set l_fim                 = Server.CreateObject("ADODB.Parameter") 
  Set l_limite_passagem     = Server.CreateObject("ADODB.Parameter") 
    
  with sp
     set l_chave           = .CreateParameter("l_chave",           adInteger, adParamInput,    , Tvl(p_chave))
     set l_sq_cidade       = .CreateParameter("l_sq_cidade",       adInteger, adParamInput,    , Tvl(p_sq_cidade))
     set l_inicio          = .CreateParameter("l_inicio",          adDate,    adParamInput,    , Tvl(p_inicio))
     set l_fim             = .CreateParameter("l_fim",             adDate,    adParamInput,    , Tvl(p_fim))
     set l_limite_passagem = .CreateParameter("l_limite_passagem", adinteger, adParamInput,    , Tvl(p_limite_passagem))
     
     .parameters.Append         l_chave
     .parameters.Append         l_sq_cidade
     .parameters.Append         l_inicio
     .parameters.Append         l_fim
     .parameters.Append         l_limite_passagem
      
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_PutInformar"
     'On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_sq_cidade"
     .parameters.Delete         "l_inicio"
     .parameters.Delete         "l_fim"
     .parameters.Delete         "l_limite_passagem"
     
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Grava a tela de passagens
REM -------------------------------------------------------------------------
Sub DML_PutViagemBenef (Operacao, p_restricao, p_chave, p_chave_aux, p_sq_pessoa, _
                        p_cpf, p_nome, p_nome_resumido, p_sexo, p_rg_numero, p_rg_emissao, _
                        p_rg_emissor, p_ddd, p_nr_telefone, p_nr_fax, p_passaporte_numero, p_sq_pais_passaporte, _
                        p_saida, p_retorno, p_valor, p_origem, p_destino, p_reserva, p_bilhete, p_trechos, p_sq_viagem)
    
  Dim l_Operacao, l_restricao, l_chave, l_chave_aux, l_sq_pessoa
  Dim l_cpf, l_nome, l_nome_resumido, l_sexo, l_rg_numero, l_rg_emissao
  Dim l_rg_emissor, l_ddd, l_nr_telefone, l_nr_fax, l_passaporte_numero, l_sq_pais_passaporte
  Dim l_saida, l_retorno, l_valor, l_origem, l_destino, l_reserva, l_bilhete, l_trechos, l_sq_viagem
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_restricao           = Server.CreateObject("ADODB.Parameter")
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_chave_aux           = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_pessoa           = Server.CreateObject("ADODB.Parameter") 
  Set l_cpf                 = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                = Server.CreateObject("ADODB.Parameter") 
  Set l_nome_resumido       = Server.CreateObject("ADODB.Parameter")
  Set l_sexo                = Server.CreateObject("ADODB.Parameter")  
  Set l_rg_numero           = Server.CreateObject("ADODB.Parameter") 
  Set l_rg_emissao          = Server.CreateObject("ADODB.Parameter") 
  Set l_rg_emissor          = Server.CreateObject("ADODB.Parameter") 
  Set l_ddd                 = Server.CreateObject("ADODB.Parameter")
  Set l_nr_telefone         = Server.CreateObject("ADODB.Parameter")
  Set l_nr_fax              = Server.CreateObject("ADODB.Parameter")
  Set l_passaporte_numero   = Server.CreateObject("ADODB.Parameter")
  Set l_sq_pais_passaporte  = Server.CreateObject("ADODB.Parameter")
  Set l_saida               = Server.CreateObject("ADODB.Parameter")
  Set l_retorno             = Server.CreateObject("ADODB.Parameter")
  Set l_valor               = Server.CreateObject("ADODB.Parameter")
  Set l_origem              = Server.CreateObject("ADODB.Parameter")
  Set l_destino             = Server.CreateObject("ADODB.Parameter")
  Set l_reserva             = Server.CreateObject("ADODB.Parameter")
  Set l_bilhete             = Server.CreateObject("ADODB.Parameter")
  Set l_trechos             = Server.CreateObject("ADODB.Parameter")
  Set l_sq_viagem           = Server.CreateObject("ADODB.Parameter")
  
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
     set l_rg_numero            = .CreateParameter("l_rg_numero",           adVarchar, adParamInput,  30, Tvl(p_rg_numero))
     set l_rg_emissao           = .CreateParameter("l_rg_emissao",          adDate,    adParamInput,    , Tvl(p_rg_emissao))
     set l_rg_emissor           = .CreateParameter("l_rg_emissor",          adVarchar, adParamInput,  30, Tvl(p_rg_emissor))
     set l_ddd                  = .CreateParameter("l_ddd",                 adVarchar, adParamInput,   4, Tvl(p_ddd))
     set l_nr_telefone          = .CreateParameter("l_nr_telefone",         adVarchar, adParamInput,  25, Tvl(p_nr_telefone))
     set l_nr_fax               = .CreateParameter("l_nr_fax",              adVarchar, adParamInput,  25, Tvl(p_nr_fax))
     set l_passaporte_numero    = .CreateParameter("l_passaporte_numero",   adVarchar, adParamInput,  20, Tvl(p_passaporte_numero))
     set l_sq_pais_passaporte   = .CreateParameter("l_sq_pais_passaporte",  adInteger, adParamInput,    , Tvl(p_sq_pais_passaporte))
     set l_saida                = .CreateParameter("l_saida",               adDate,    adParamInput,    , Tvl(p_saida))
     set l_retorno              = .CreateParameter("l_retorno",             adDate,    adParamInput,    , Tvl(p_retorno))
     set l_valor                = .CreateParameter("l_valor",               adNumeric ,adParamInput)
     l_valor.Precision    = 18
     l_valor.NumericScale = 2
     l_valor.Value        = Tvl(p_valor)
     set l_origem               = .CreateParameter("l_origem",              adInteger, adParamInput,    , Tvl(p_origem))
     set l_destino              = .CreateParameter("l_destino",             adInteger, adParamInput,    , Tvl(p_destino))
     set l_reserva              = .CreateParameter("l_reserva",             adVarchar, adParamInput,  30, Tvl(p_reserva))
     set l_bilhete              = .CreateParameter("l_bilhete",             adVarchar, adParamInput,  20, Tvl(p_bilhete))
     set l_trechos              = .CreateParameter("l_trechos",             adVarchar, adParamInput, 100, Tvl(p_trechos))
     set l_sq_viagem            = .CreateParameter("l_sq_viagem",           adInteger, adParamInput,    , Tvl(p_sq_viagem))
     
     .parameters.Append         l_Operacao
     .parameters.Append         l_restricao
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_sq_pessoa 
     .parameters.Append         l_cpf
     .parameters.Append         l_nome 
     .parameters.Append         l_nome_resumido
     .parameters.Append         l_sexo
     .parameters.Append         l_rg_numero 
     .parameters.Append         l_rg_emissao 
     .parameters.Append         l_rg_emissor 
     .parameters.Append         l_ddd 
     .parameters.Append         l_nr_telefone 
     .parameters.Append         l_nr_fax 
     .parameters.Append         l_passaporte_numero 
     .parameters.Append         l_sq_pais_passaporte
     .parameters.Append         l_saida
     .parameters.Append         l_retorno
     .parameters.Append         l_valor
     .parameters.Append         l_origem
     .parameters.Append         l_destino
     .parameters.Append         l_reserva
     .parameters.Append         l_bilhete
     .parameters.Append         l_trechos
     .parameters.Append         l_sq_viagem
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_PutViagemBenef"
     'On Error Resume Next
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
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_nome_resumido"
     .parameters.Delete         "l_sexo"
     .parameters.Delete         "l_rg_numero"
     .parameters.Delete         "l_rg_emissao" 
     .parameters.Delete         "l_rg_emissor"
     .parameters.Delete         "l_ddd"
     .parameters.Delete         "l_nr_telefone"
     .parameters.Delete         "l_nr_fax"
     .parameters.Delete         "l_passaporte_numero"
     .parameters.Delete         "l_sq_pais_passaporte"
     .parameters.Delete         "l_saida"
     .parameters.Delete         "l_retorno"
     .parameters.Delete         "l_valor"
     .parameters.Delete         "l_origem"
     .parameters.Delete         "l_destino"
     .parameters.Delete         "l_reserva"
     .parameters.Delete         "l_bilhete"
     .parameters.Delete         "l_trechos"
     .parameters.Delete         "l_sq_viagem"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Grava a tela de Apoio na SIW_SOLIC_APOIO
REM -------------------------------------------------------------------------
Sub DML_PutSolicApoio (p_operacao, p_chave, p_chave_aux, p_sq_tipo_apoio, p_entidade, p_descricao, p_valor, p_usuario)
    
  Dim l_operacao, l_chave, l_chave_aux, l_sq_tipo_apoio
  Dim l_entidade, l_descricao, l_valor, l_usuario  
  
  Set l_operacao            = Server.CreateObject("ADODB.Parameter") 
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_chave_aux           = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_tipo_apoio       = Server.CreateObject("ADODB.Parameter") 
  Set l_entidade            = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao           = Server.CreateObject("ADODB.Parameter")
  Set l_valor               = Server.CreateObject("ADODB.Parameter")  
  Set l_usuario             = Server.CreateObject("ADODB.Parameter")  
    
  with sp
     set l_operacao        = .CreateParameter("l_operacao",        adVarchar, adParamInput,  2, Tvl(p_operacao))
     set l_chave           = .CreateParameter("l_chave",           adInteger, adParamInput,    , Tvl(p_chave))
     set l_chave_aux       = .CreateParameter("l_chave_aux",       adInteger, adParamInput,    , Tvl(p_chave_aux))
     set l_sq_tipo_apoio   = .CreateParameter("l_sq_tipo_apoio",   adInteger, adParamInput,    , Tvl(p_sq_tipo_apoio))
     set l_entidade        = .CreateParameter("l_entidade",        adVarchar, adParamInput,  50, Tvl(p_entidade))
     set l_descricao       = .CreateParameter("l_descricao",       adVarchar, adParamInput, 200, Tvl(p_descricao))
     set l_valor           = .CreateParameter("l_valor",           adNumeric ,adParamInput)
     l_valor.Precision     = 18
     l_valor.NumericScale  = 2
     l_valor.Value         = Tvl(p_valor)
     set l_usuario        = .CreateParameter("l_usuario",          adInteger, adParamInput,    , Tvl(p_usuario))
   
     .parameters.Append         l_operacao
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_sq_tipo_apoio
     .parameters.Append         l_entidade
     .parameters.Append         l_descricao
     .parameters.Append         l_valor
     .parameters.Append         l_usuario
      
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_PutSolicApoio"
     'On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_chave_aux"
     .parameters.Delete         "l_sq_tipo_apoio"
     .parameters.Delete         "l_entidade"
     .parameters.Delete         "l_descricao"
     .parameters.Delete         "l_valor"
     .parameters.Delete         "l_usuario"
     
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>