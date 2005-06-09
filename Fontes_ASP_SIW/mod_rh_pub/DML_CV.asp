<%
REM =========================================================================
REM Mantщm os dados de identificacao do colaborador
REM -------------------------------------------------------------------------
Sub DML_PutCVIdent(Operacao, p_cliente, p_chave, p_nome, p_nome_resumido, p_nascimento, p_sexo, _
         p_sq_estado_civil, p_sq_formacao, p_cidade, _
         p_rg_numero, p_rg_emissor, p_rg_emissao, p_cpf, p_passaporte_numero, p_sq_pais_passaporte, _
         p_foto, p_tamanho, p_tipo)

  Dim l_Operacao, l_cliente, l_Chave, l_nome, l_nome_resumido, l_nascimento, l_sexo
  Dim l_sq_estado_civil, l_sq_formacao, l_cidade
  Dim l_rg_numero, l_rg_emissor, l_rg_emissao, l_cpf, l_passaporte_numero, l_sq_pais_passaporte
  Dim l_foto, l_tamanho, l_tipo
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_cliente             = Server.CreateObject("ADODB.Parameter") 
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                = Server.CreateObject("ADODB.Parameter") 
  Set l_nome_resumido       = Server.CreateObject("ADODB.Parameter") 
  Set l_foto                = Server.CreateObject("ADODB.Parameter")
  Set l_tamanho             = Server.CreateObject("ADODB.Parameter")
  Set l_tipo                = Server.CreateObject("ADODB.Parameter")
  Set l_nascimento          = Server.CreateObject("ADODB.Parameter") 
  Set l_sexo                = Server.CreateObject("ADODB.Parameter")
  Set l_sq_estado_civil     = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_formacao         = Server.CreateObject("ADODB.Parameter") 
  Set l_cidade              = Server.CreateObject("ADODB.Parameter")
  Set l_rg_numero           = Server.CreateObject("ADODB.Parameter") 
  Set l_rg_emissor          = Server.CreateObject("ADODB.Parameter") 
  Set l_rg_emissao          = Server.CreateObject("ADODB.Parameter") 
  Set l_cpf                 = Server.CreateObject("ADODB.Parameter") 
  Set l_passaporte_numero   = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_pais_passaporte  = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",            adVarchar, adParamInput,   1, Operacao)
     set l_cliente              = .CreateParameter("l_cliente",             adInteger, adParamInput,    , p_cliente)
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,    , Tvl(p_chave))
     set l_nome                 = .CreateParameter("l_nome",                adVarchar, adParamInput,  60, p_nome)
     set l_nome_resumido        = .CreateParameter("l_nome_resumido",       adVarchar, adParamInput,  15, p_nome_resumido)
     set l_foto                 = .CreateParameter("l_foto",                adVarchar, adParamInput, 255, Tvl(p_foto))
     set l_tamanho              = .CreateParameter("l_tamanho",             adInteger, adParamInput,    , Tvl(p_tamanho))
     set l_tipo                 = .CreateParameter("l_tipo",                adVarchar, adParamInput,  60, Tvl(p_tipo))
     set l_nascimento           = .CreateParameter("l_nascimento",          adDate,    adParamInput,    , p_nascimento)
     set l_sexo                 = .CreateParameter("l_sexo",                adVarchar, adParamInput,   1, p_sexo)
     set l_sq_estado_civil      = .CreateParameter("l_sq_estado_civil",     adInteger, adParamInput,    , p_sq_estado_civil)
     set l_sq_formacao          = .CreateParameter("l_sq_formacao",         adInteger, adParamInput,    , p_sq_formacao)
     set l_cidade               = .CreateParameter("l_cidade",              adInteger, adParamInput,    , p_cidade)
     set l_rg_numero            = .CreateParameter("l_rg_numero",           adVarchar, adParamInput,  30, p_rg_numero)
     set l_rg_emissor           = .CreateParameter("l_rg_emissor",          adVarchar, adParamInput,  30, p_rg_emissor)
     set l_rg_emissao           = .CreateParameter("l_rg_emissao",          adDate,    adParamInput,    , p_rg_emissao)
     set l_cpf                  = .CreateParameter("l_cpf",                 adVarchar, adParamInput,  14, p_cpf)
     set l_passaporte_numero    = .CreateParameter("l_passaporte_numero",   adVarchar, adParamInput,  20, tvl(p_passaporte_numero))
     set l_sq_pais_passaporte   = .CreateParameter("l_sq_pais_passaporte",  adInteger, adParamInput,    , tvl(p_sq_pais_passaporte))

     .parameters.Append         l_Operacao
     .parameters.Append         l_Cliente
     .parameters.Append         l_Chave
     .parameters.Append         l_nome
     .parameters.Append         l_nome_resumido
     .parameters.Append         l_foto
     .parameters.Append         l_tamanho
     .parameters.Append         l_tipo
     .parameters.Append         l_nascimento
     .parameters.Append         l_sexo
     .parameters.Append         l_sq_estado_civil
     .parameters.Append         l_sq_formacao
     .parameters.Append         l_cidade
     .parameters.Append         l_rg_numero
     .parameters.Append         l_rg_emissor
     .parameters.Append         l_rg_emissao
     .parameters.Append         l_cpf
     .parameters.Append         l_passaporte_numero
     .parameters.Append         l_sq_pais_passaporte
     
     'Response.Write "{"&l_Operacao&"}{"&l_Cliente&"}{"&l_Chave&"}{"&l_nome&"}{"&l_nome_resumido&"}{"&l_nascimento&"}{"&l_sexo&"}{"&l_sq_estado_civil&"}{"&l_sq_formacao&"}{"&l_sq_etnia&"}{"&l_sq_deficiencia&"}{"&l_cidade&"}{"&l_rg_numero&"}{"&l_rg_emissor&"}{"&l_rg_emissao&"}{"&l_cpf&"}{"&l_passaporte_numero&"}{"&l_sq_pais_passaporte&"}"
	 'Response.End()
	 
     .CommandText               = Session("schema") & "SP_PutCVIdent"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Cliente"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_nome_resumido"
     .parameters.Delete         "l_foto"
     .parameters.Delete         "l_tamanho"
     .parameters.Delete         "l_tipo"
     .parameters.Delete         "l_nascimento"
     .parameters.Delete         "l_sexo"
     .parameters.Delete         "l_sq_estado_civil"
     .parameters.Delete         "l_sq_formacao"
     .parameters.Delete         "l_cidade"
     .parameters.Delete         "l_rg_numero"
     .parameters.Delete         "l_rg_emissor"
     .parameters.Delete         "l_rg_emissao"
     .parameters.Delete         "l_cpf"
     .parameters.Delete         "l_passaporte_numero"
     .parameters.Delete         "l_sq_pais_passaporte"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantщm os dados de identificacao do colaborador
REM -------------------------------------------------------------------------
Sub DML_PutCVHist(Operacao, p_chave, p_residencia_outro_pais, p_mudanca_nacionalidade, _
         p_mudanca_nacionalidade_medida, p_emprego_seis_meses, p_impedimento_viagem_aerea, _
         p_objecao_informacoes, p_prisao_envolv_justica, p_motivo_prisao, p_fato_relevante_vida, _
         p_servidor_publico, p_servico_publico_inicio, p_servico_publico_fim, p_atividades_civicas, _
         p_familiar)

  Dim l_Operacao, l_Chave, l_residencia_outro_pais, l_mudanca_nacionalidade
  Dim l_mudanca_nacionalidade_medida, l_emprego_seis_meses, l_impedimento_viagem_aerea
  Dim l_objecao_informacoes, l_prisao_envolv_justica, l_motivo_prisao, l_fato_relevante_vida
  Dim l_servidor_publico, l_servico_publico_inicio, l_servico_publico_fim, l_atividades_civicas
  Dim l_familiar
  
  Set l_Operacao                        = Server.CreateObject("ADODB.Parameter")
  Set l_chave                           = Server.CreateObject("ADODB.Parameter") 
  Set l_residencia_outro_pais           = Server.CreateObject("ADODB.Parameter") 
  Set l_mudanca_nacionalidade           = Server.CreateObject("ADODB.Parameter") 
  Set l_mudanca_nacionalidade_medida    = Server.CreateObject("ADODB.Parameter") 
  Set l_emprego_seis_meses              = Server.CreateObject("ADODB.Parameter") 
  Set l_impedimento_viagem_aerea        = Server.CreateObject("ADODB.Parameter")
  Set l_objecao_informacoes             = Server.CreateObject("ADODB.Parameter") 
  Set l_prisao_envolv_justica           = Server.CreateObject("ADODB.Parameter") 
  Set l_motivo_prisao                   = Server.CreateObject("ADODB.Parameter") 
  Set l_fato_relevante_vida             = Server.CreateObject("ADODB.Parameter") 
  Set l_servidor_publico                = Server.CreateObject("ADODB.Parameter")
  Set l_servico_publico_inicio          = Server.CreateObject("ADODB.Parameter") 
  Set l_servico_publico_fim             = Server.CreateObject("ADODB.Parameter") 
  Set l_atividades_civicas              = Server.CreateObject("ADODB.Parameter") 
  Set l_familiar                        = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao                     = .CreateParameter("l_operacao",                        adVarchar, adParamInput,   1, Operacao)
     set l_chave                        = .CreateParameter("l_chave",                           adInteger, adParamInput,    , p_chave)
     set l_residencia_outro_pais        = .CreateParameter("l_residencia_outro_pais",           adVarchar, adParamInput,   1, p_residencia_outro_pais)
     set l_mudanca_nacionalidade        = .CreateParameter("l_mudanca_nacionalidade",           adVarchar, adParamInput,   1, p_mudanca_nacionalidade)
     set l_mudanca_nacionalidade_medida = .CreateParameter("l_mudanca_nacionalidade_medida",    adVarchar, adParamInput, 255, tvl(p_mudanca_nacionalidade_medida))
     set l_emprego_seis_meses           = .CreateParameter("l_emprego_seis_meses",              adVarchar, adParamInput,   1, p_emprego_seis_meses)
     set l_impedimento_viagem_aerea     = .CreateParameter("l_impedimento_viagem_aerea",        adVarchar, adParamInput,   1, p_impedimento_viagem_aerea)
     set l_objecao_informacoes          = .CreateParameter("l_objecao_informacoes",             adVarchar, adParamInput,   1, p_objecao_informacoes)
     set l_prisao_envolv_justica        = .CreateParameter("l_prisao_envolv_justica",           adVarchar, adParamInput,   1, p_prisao_envolv_justica)
     set l_motivo_prisao                = .CreateParameter("l_motivo_prisao",                   adVarchar, adParamInput, 255, tvl(p_motivo_prisao))
     set l_fato_relevante_vida          = .CreateParameter("l_fato_relevante_vida",             adVarchar, adParamInput, 255, tvl(p_fato_relevante_vida))
     set l_servidor_publico             = .CreateParameter("l_servidor_publico",                adVarchar, adParamInput,   1, p_servidor_publico)
     set l_servico_publico_inicio       = .CreateParameter("l_servico_publico_inicio",          adDate,    adParamInput,    , tvl(p_servico_publico_inicio))
     set l_servico_publico_fim          = .CreateParameter("l_servico_publico_fim",             adDate,    adParamInput,    , tvl(p_servico_publico_fim))
     set l_atividades_civicas           = .CreateParameter("l_atividades_civicas",              adVarchar, adParamInput, 255, tvl(p_atividades_civicas))
     set l_familiar                     = .CreateParameter("l_familiar",                        adVarchar, adParamInput,   1, p_familiar)

     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_residencia_outro_pais
     .parameters.Append         l_mudanca_nacionalidade
     .parameters.Append         l_mudanca_nacionalidade_medida
     .parameters.Append         l_emprego_seis_meses
     .parameters.Append         l_impedimento_viagem_aerea
     .parameters.Append         l_objecao_informacoes
     .parameters.Append         l_prisao_envolv_justica
     .parameters.Append         l_motivo_prisao
     .parameters.Append         l_fato_relevante_vida
     .parameters.Append         l_servidor_publico
     .parameters.Append         l_servico_publico_inicio
     .parameters.Append         l_servico_publico_fim
     .parameters.Append         l_atividades_civicas
     .parameters.Append         l_familiar

     .CommandText               = Session("schema") & "SP_PutCVHist"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_residencia_outro_pais"
     .parameters.Delete         "l_mudanca_nacionalidade"
     .parameters.Delete         "l_mudanca_nacionalidade_medida"
     .parameters.Delete         "l_emprego_seis_meses"
     .parameters.Delete         "l_impedimento_viagem_aerea"
     .parameters.Delete         "l_objecao_informacoes"
     .parameters.Delete         "l_prisao_envolv_justica"
     .parameters.Delete         "l_motivo_prisao"
     .parameters.Delete         "l_fato_relevante_vida"
     .parameters.Delete         "l_servidor_publico"
     .parameters.Delete         "l_servico_publico_inicio"
     .parameters.Delete         "l_servico_publico_fim"
     .parameters.Delete         "l_atividades_civicas"
     .parameters.Delete         "l_familiar"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantщm os idiomas do colaborador
REM -------------------------------------------------------------------------
Sub DML_PutCVIdioma(Operacao, p_pessoa, p_chave, p_leitura, p_escrita, p_compreensao, p_conversacao)

  Dim l_Operacao, l_pessoa, l_Chave, l_leitura
  Dim l_escrita, l_compreensao, l_conversacao
  
  Set l_Operacao                = Server.CreateObject("ADODB.Parameter")
  Set l_pessoa                  = Server.CreateObject("ADODB.Parameter") 
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_leitura                 = Server.CreateObject("ADODB.Parameter") 
  Set l_escrita                 = Server.CreateObject("ADODB.Parameter") 
  Set l_compreensao             = Server.CreateObject("ADODB.Parameter")
  Set l_conversacao             = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",            adVarchar, adParamInput,   1, Operacao)
     set l_pessoa               = .CreateParameter("l_pessoa",              adInteger, adParamInput,    , p_pessoa)
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,    , tvl(p_chave))
     set l_leitura              = .CreateParameter("l_leitura",             adVarchar, adParamInput,   1, p_leitura)
     set l_escrita              = .CreateParameter("l_escrita",             adVarchar, adParamInput,   1, p_escrita)
     set l_compreensao          = .CreateParameter("l_compreensao",         adVarchar, adParamInput,   1, p_compreensao)
     set l_conversacao          = .CreateParameter("l_conversacao",         adVarchar, adParamInput,   1, p_conversacao)
     .parameters.Append         l_Operacao
     .parameters.Append         l_Pessoa
     .parameters.Append         l_Chave
     .parameters.Append         l_leitura
     .parameters.Append         l_escrita
     .parameters.Append         l_compreensao
     .parameters.Append         l_conversacao

     .CommandText               = Session("schema") & "SP_PutCVIdioma"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Pessoa"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_leitura"
     .parameters.Delete         "l_escrita"
     .parameters.Delete         "l_compreensao"
     .parameters.Delete         "l_conversacao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantщm os dados de formaчуo acadъmica do colaborador
REM -------------------------------------------------------------------------
Sub DML_PutCVEscola(Operacao, p_pessoa, p_chave, p_sq_area_conhecimento, p_sq_pais, _
         p_sq_formacao, p_nome, p_instituicao, p_inicio, p_fim)

  Dim l_Operacao, l_pessoa, l_Chave, l_sq_area_conhecimento, l_sq_pais
  Dim l_sq_formacao, l_nome, l_instituicao, l_inicio, l_fim
  
  Set l_Operacao                = Server.CreateObject("ADODB.Parameter")
  Set l_pessoa                  = Server.CreateObject("ADODB.Parameter") 
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_area_conhecimento    = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_pais                 = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_formacao             = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter")
  Set l_instituicao             = Server.CreateObject("ADODB.Parameter") 
  Set l_inicio                  = Server.CreateObject("ADODB.Parameter") 
  Set l_fim                     = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",             adVarchar, adParamInput,   1, Operacao)
     set l_pessoa               = .CreateParameter("l_pessoa",               adInteger, adParamInput,    , p_pessoa)
     set l_chave                = .CreateParameter("l_chave",                adInteger, adParamInput,    , tvl(p_chave))
     set l_sq_area_conhecimento = .CreateParameter("l_sq_area_conhecimento", adInteger, adParamInput,    , tvl(p_sq_area_conhecimento))
     set l_sq_pais              = .CreateParameter("l_sq_pais",              adInteger, adParamInput,    , p_sq_pais)
     set l_sq_formacao          = .CreateParameter("l_sq_formacao",          adInteger, adParamInput,    , p_sq_formacao)
     set l_nome                 = .CreateParameter("l_nome",                 adVarchar, adParamInput,  80, tvl(p_nome))
     set l_instituicao          = .CreateParameter("l_instituicao",          adVarchar, adParamInput, 100, p_instituicao)
     set l_inicio               = .CreateParameter("l_inicio",               adVarchar, adParamInput,   7, p_inicio)
     set l_fim                  = .CreateParameter("l_fim",                  adVarchar, adParamInput,   7, tvl(p_fim))

     .parameters.Append         l_Operacao
     .parameters.Append         l_Pessoa
     .parameters.Append         l_Chave
     .parameters.Append         l_sq_area_conhecimento
     .parameters.Append         l_sq_pais
     .parameters.Append         l_sq_formacao
     .parameters.Append         l_nome
     .parameters.Append         l_instituicao
     .parameters.Append         l_inicio
     .parameters.Append         l_fim

     .CommandText               = Session("schema") & "SP_PutCVEscola"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Pessoa"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_sq_area_conhecimento"
     .parameters.Delete         "l_sq_pais"
     .parameters.Delete         "l_sq_formacao"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_instituicao"
     .parameters.Delete         "l_inicio"
     .parameters.Delete         "l_fim"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantщm os dados de extensуo acadъmica do colaborador
REM -------------------------------------------------------------------------
Sub DML_PutCVCurso(Operacao, p_pessoa, p_chave, p_sq_area_conhecimento, _
         p_sq_formacao, p_nome, p_instituicao, p_carga_horaria, p_conclusao)

  Dim l_Operacao, l_pessoa, l_Chave, l_sq_area_conhecimento
  Dim l_sq_formacao, l_nome, l_instituicao, l_carga_horaria, l_conclusao
  
  Set l_Operacao                = Server.CreateObject("ADODB.Parameter")
  Set l_pessoa                  = Server.CreateObject("ADODB.Parameter") 
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_area_conhecimento    = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_formacao             = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter")
  Set l_instituicao             = Server.CreateObject("ADODB.Parameter") 
  Set l_carga_horaria           = Server.CreateObject("ADODB.Parameter") 
  Set l_conclusao               = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",             adVarchar, adParamInput,   1, Operacao)
     set l_pessoa               = .CreateParameter("l_pessoa",               adInteger, adParamInput,    , p_pessoa)
     set l_chave                = .CreateParameter("l_chave",                adInteger, adParamInput,    , tvl(p_chave))
     set l_sq_area_conhecimento = .CreateParameter("l_sq_area_conhecimento", adInteger, adParamInput,    , p_sq_area_conhecimento)
     set l_sq_formacao          = .CreateParameter("l_sq_formacao",          adInteger, adParamInput,    , p_sq_formacao)
     set l_nome                 = .CreateParameter("l_nome",                 adVarchar, adParamInput,  80, p_nome)
     set l_instituicao          = .CreateParameter("l_instituicao",          adVarchar, adParamInput, 100, p_instituicao)
     set l_carga_horaria        = .CreateParameter("l_carga_horaria",        adInteger, adParamInput,    , p_carga_horaria)
     set l_conclusao            = .CreateParameter("l_conclusao",            adDate,    adParamInput,    , tvl(p_conclusao))

     .parameters.Append         l_Operacao
     .parameters.Append         l_Pessoa
     .parameters.Append         l_Chave
     .parameters.Append         l_sq_area_conhecimento
     .parameters.Append         l_sq_formacao
     .parameters.Append         l_nome
     .parameters.Append         l_instituicao
     .parameters.Append         l_carga_horaria
     .parameters.Append         l_conclusao

     .CommandText               = Session("schema") & "SP_PutCVCurso"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Pessoa"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_sq_area_conhecimento"
     .parameters.Delete         "l_sq_formacao"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_instituicao"
     .parameters.Delete         "l_carga_horaria"
     .parameters.Delete         "l_conclusao"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantщm os dados de produчуo tщcnica do colaborador
REM -------------------------------------------------------------------------
Sub DML_PutCVProducao(Operacao, p_pessoa, p_chave, p_sq_area_conhecimento, _
         p_sq_formacao, p_nome, p_meio, p_data)

  Dim l_Operacao, l_pessoa, l_Chave, l_sq_area_conhecimento
  Dim l_sq_formacao, l_nome, l_meio, l_data
  
  Set l_Operacao                = Server.CreateObject("ADODB.Parameter")
  Set l_pessoa                  = Server.CreateObject("ADODB.Parameter") 
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_area_conhecimento    = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_formacao             = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                    = Server.CreateObject("ADODB.Parameter")
  Set l_meio                    = Server.CreateObject("ADODB.Parameter") 
  Set l_data                    = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",             adVarchar, adParamInput,   1, Operacao)
     set l_pessoa               = .CreateParameter("l_pessoa",               adInteger, adParamInput,    , p_pessoa)
     set l_chave                = .CreateParameter("l_chave",                adInteger, adParamInput,    , tvl(p_chave))
     set l_sq_area_conhecimento = .CreateParameter("l_sq_area_conhecimento", adInteger, adParamInput,    , p_sq_area_conhecimento)
     set l_sq_formacao          = .CreateParameter("l_sq_formacao",          adInteger, adParamInput,    , p_sq_formacao)
     set l_nome                 = .CreateParameter("l_nome",                 adVarchar, adParamInput,  80, p_nome)
     set l_meio                 = .CreateParameter("l_meio",                 adVarchar, adParamInput, 100, p_meio)
     set l_data                 = .CreateParameter("l_data",                 adDate,    adParamInput,    , p_data)

     .parameters.Append         l_Operacao
     .parameters.Append         l_Pessoa
     .parameters.Append         l_Chave
     .parameters.Append         l_sq_area_conhecimento
     .parameters.Append         l_sq_formacao
     .parameters.Append         l_nome
     .parameters.Append         l_meio
     .parameters.Append         l_data

     .CommandText               = Session("schema") & "SP_PutCVProducao"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Pessoa"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_sq_area_conhecimento"
     .parameters.Delete         "l_sq_formacao"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_meio"
     .parameters.Delete         "l_data"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantщm os dados da experiъncia profissional do colaborador
REM -------------------------------------------------------------------------
Sub DML_PutCVExperiencia(Operacao, p_pessoa, p_chave, p_sq_area_conhecimento, p_sq_cidade, _
         p_sq_eo_tipo_posto, p_sq_tipo_vinculo, p_empregador, p_entrada, p_saida, _
         p_duracao_mes, p_duracao_ano, p_motivo_saida, p_atividades)

  Dim l_Operacao, l_pessoa, l_Chave, l_sq_area_conhecimento, l_sq_cidade, l_sq_eo_tipo_posto
  Dim l_sq_tipo_vinculo, l_empregador, l_entrada, l_saida, l_duracao_mes, l_duracao_ano
  Dim l_motivo_saida, l_atividades
  
  Set l_Operacao                = Server.CreateObject("ADODB.Parameter")
  Set l_pessoa                  = Server.CreateObject("ADODB.Parameter") 
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_area_conhecimento    = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_cidade               = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_eo_tipo_posto        = Server.CreateObject("ADODB.Parameter")
  Set l_sq_tipo_vinculo         = Server.CreateObject("ADODB.Parameter") 
  Set l_empregador              = Server.CreateObject("ADODB.Parameter")
  Set l_entrada                 = Server.CreateObject("ADODB.Parameter") 
  Set l_saida                   = Server.CreateObject("ADODB.Parameter") 
  Set l_duracao_mes             = Server.CreateObject("ADODB.Parameter") 
  Set l_duracao_ano             = Server.CreateObject("ADODB.Parameter") 
  Set l_motivo_saida            = Server.CreateObject("ADODB.Parameter") 
  Set l_atividades              = Server.CreateObject("ADODB.Parameter")  
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",             adVarchar, adParamInput,   1, Operacao)
     set l_pessoa               = .CreateParameter("l_pessoa",               adInteger, adParamInput,    , p_pessoa)
     set l_chave                = .CreateParameter("l_chave",                adInteger, adParamInput,    , tvl(p_chave))
     set l_sq_area_conhecimento = .CreateParameter("l_sq_area_conhecimento", adInteger, adParamInput,    , p_sq_area_conhecimento)
     set l_sq_cidade            = .CreateParameter("l_sq_cidade",            adInteger, adParamInput,    , p_sq_cidade)
     set l_sq_eo_tipo_posto     = .CreateParameter("l_sq_eo_tipo_posto",     adInteger, adParamInput,    , tvl(p_sq_eo_tipo_posto))
     set l_sq_tipo_vinculo      = .CreateParameter("l_sq_tipo_vinculo",      adInteger, adParamInput,    , tvl(p_sq_tipo_vinculo))
     set l_empregador           = .CreateParameter("l_empregador",           adVarchar, adParamInput,  60, p_empregador)
     set l_entrada              = .CreateParameter("l_entrada",              adDate,    adParamInput,    , p_entrada)
     set l_saida                = .CreateParameter("l_saida",                adDate,    adParamInput,    , tvl(p_saida))
     set l_duracao_mes          = .CreateParameter("l_duracao_mes",          adInteger, adParamInput,    , tvl(p_duracao_mes))
     set l_duracao_ano          = .CreateParameter("l_duracao_ano",          adInteger, adParamInput,    , tvl(p_duracao_ano))
     set l_motivo_saida         = .CreateParameter("l_motivo_saida",         adVarchar, adParamInput, 255, tvl(p_motivo_saida))
     set l_atividades           = .CreateParameter("l_atividades",           adVarchar, adParamInput,4000, tvl(p_atividades))

     .parameters.Append         l_Operacao
     .parameters.Append         l_Pessoa
     .parameters.Append         l_Chave
     .parameters.Append         l_sq_area_conhecimento
     .parameters.Append         l_sq_cidade
     .parameters.Append         l_sq_eo_tipo_posto
     .parameters.Append         l_sq_tipo_vinculo
     .parameters.Append         l_empregador
     .parameters.Append         l_entrada
     .parameters.Append         l_saida
     .parameters.Append         l_duracao_mes
     .parameters.Append         l_duracao_ano
     .parameters.Append         l_motivo_saida
     .parameters.Append         l_atividades

     .CommandText               = Session("schema") & "SP_PutCVExp"
     
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Pessoa"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_sq_area_conhecimento"
     .parameters.Delete         "l_sq_cidade"
     .parameters.Delete         "l_sq_eo_tipo_posto"
     .parameters.Delete         "l_sq_tipo_vinculo"
     .parameters.Delete         "l_empregador"
     .parameters.Delete         "l_entrada"
     .parameters.Delete         "l_saida"
     .parameters.Delete         "l_duracao_mes"
     .parameters.Delete         "l_duracao_ano"
     .parameters.Delete         "l_motivo_saida"
     .parameters.Delete         "l_atividades"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantщm os dados de produчуo tщcnica do colaborador
REM -------------------------------------------------------------------------
Sub DML_PutCVCargo(Operacao, p_chave, p_sq_cvpesexp, p_sq_area_conhecimento, _
                   p_especialidades, p_inicio, p_fim)

  Dim l_Operacao, l_Chave, l_sq_cvpesexp, l_sq_area_conhecimento
  Dim l_especialidades, l_inicio, l_fim
  
  Set l_Operacao                = Server.CreateObject("ADODB.Parameter")
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_cvpesexp             = Server.CreateObject("ADODB.Parameter")
  Set l_sq_area_conhecimento    = Server.CreateObject("ADODB.Parameter") 
  Set l_especialidades          = Server.CreateObject("ADODB.Parameter") 
  Set l_inicio                  = Server.CreateObject("ADODB.Parameter")
  Set l_fim                     = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",             adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",                adInteger, adParamInput,    , tvl(p_chave))
     set l_sq_cvpesexp          = .CreateParameter("l_sq_cvpesexp",          adInteger, adParamInput,    , p_sq_cvpesexp)
     set l_sq_area_conhecimento = .CreateParameter("l_sq_area_conhecimento", adInteger, adParamInput,    , p_sq_area_conhecimento)
     set l_especialidades       = .CreateParameter("l_especialidades",       adVarchar, adParamInput, 255, p_especialidades)
     set l_inicio               = .CreateParameter("l_inicio",               adDate,    adParamInput,    , p_inicio)
     set l_fim                  = .CreateParameter("l_fim",                  adDate,    adParamInput,    , tvl(p_fim))

     .parameters.Append         l_Operacao
     .parameters.Append         l_Chave
     .parameters.Append         l_sq_cvpesexp
     .parameters.Append         l_sq_area_conhecimento
     .parameters.Append         l_especialidades
     .parameters.Append         l_inicio
     .parameters.Append         l_fim

     .CommandText               = Session("schema") & "SP_PutCVCargo"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_sq_cvpesexp"
     .parameters.Delete         "l_sq_area_conhecimento"
     .parameters.Delete         "l_especialidades"
     .parameters.Delete         "l_inicio"
     .parameters.Delete         "l_fim"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>