<%
REM =========================================================================
REM Mantщm a tabela principal de Acao
REM -------------------------------------------------------------------------
Sub DML_PutAcaoGeral_IS(Operacao, p_chave, p_menu, p_unidade, p_solicitante, p_proponente, _
    p_cadastrador, p_executor, p_descricao, p_justificativa, p_inicio, p_fim, p_valor, _
    p_data_hora, p_unid_resp, p_assunto, p_prioridade, p_aviso, p_dias, p_cidade, p_palavra_chave, p_inicio_real, _
    p_fim_real, p_concluida, p_data_conclusao, p_nota_conclusao, p_custo_real, p_opiniao, _
    ano, cliente, p_programa, p_acao, p_subacao, p_cd_unidade, p_sq_isprojeto, p_selecao_mp, p_selecao_se, p_sq_natureza, p_sq_horizonte, p_chave_nova, p_copia, p_unidade_adm, p_ln_programa)
    
  Dim l_Operacao, l_Chave, l_menu, l_unidade, l_solicitante, l_proponente
  Dim l_cadastrador, l_executor, l_descricao, l_justificativa, l_inicio, l_fim, l_valor
  Dim l_data_hora, l_unid_resp, l_assunto, l_prioridade, l_aviso, l_dias, l_cidade, l_palavra_chave, l_inicio_real
  Dim l_fim_real, l_concluida, l_data_conclusao, l_nota_conclusao, l_custo_real, l_opiniao, l_chave_nova, l_copia
  Dim l_ano, l_programa, l_cliente, l_acao, l_subacao, l_cd_unidade, l_sq_isprojeto, l_selecao_mp, l_selecao_se, l_sq_natureza, l_sq_horizonte, l_unidade_adm, l_ln_programa
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_copia               = Server.CreateObject("ADODB.Parameter") 
  Set l_menu                = Server.CreateObject("ADODB.Parameter") 
  Set l_unidade             = Server.CreateObject("ADODB.Parameter") 
  Set l_solicitante         = Server.CreateObject("ADODB.Parameter") 
  Set l_proponente          = Server.CreateObject("ADODB.Parameter") 
  Set l_cadastrador         = Server.CreateObject("ADODB.Parameter") 
  Set l_executor            = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao           = Server.CreateObject("ADODB.Parameter") 
  Set l_justificativa       = Server.CreateObject("ADODB.Parameter")  
  Set l_inicio              = Server.CreateObject("ADODB.Parameter") 
  Set l_fim                 = Server.CreateObject("ADODB.Parameter") 
  Set l_valor               = Server.CreateObject("ADODB.Parameter") 
  Set l_data_hora           = Server.CreateObject("ADODB.Parameter") 
  Set l_unid_resp           = Server.CreateObject("ADODB.Parameter") 
  Set l_assunto             = Server.CreateObject("ADODB.Parameter") 
  Set l_prioridade          = Server.CreateObject("ADODB.Parameter") 
  Set l_aviso               = Server.CreateObject("ADODB.Parameter") 
  Set l_dias                = Server.CreateObject("ADODB.Parameter") 
  Set l_cidade              = Server.CreateObject("ADODB.Parameter") 
  Set l_palavra_chave       = Server.CreateObject("ADODB.Parameter") 
  Set l_inicio_real         = Server.CreateObject("ADODB.Parameter") 
  Set l_fim_real            = Server.CreateObject("ADODB.Parameter") 
  Set l_concluida           = Server.CreateObject("ADODB.Parameter") 
  Set l_data_conclusao      = Server.CreateObject("ADODB.Parameter") 
  Set l_nota_conclusao      = Server.CreateObject("ADODB.Parameter") 
  Set l_custo_real          = Server.CreateObject("ADODB.Parameter") 
  Set l_opiniao             = Server.CreateObject("ADODB.Parameter") 
  Set l_chave_nova          = Server.CreateObject("ADODB.Parameter") 
  Set l_ano                 = Server.CreateObject("ADODB.Parameter")
  Set l_programa            = Server.CreateObject("ADODB.Parameter")
  Set l_cliente             = Server.CreateObject("ADODB.Parameter")
  Set l_acao                = Server.CreateObject("ADODB.Parameter")
  Set l_subacao             = Server.CreateObject("ADODB.Parameter")
  Set l_cd_unidade          = Server.CreateObject("ADODB.Parameter")
  Set l_sq_isprojeto        = Server.CreateObject("ADODB.Parameter")
  Set l_selecao_mp          = Server.CreateObject("ADODB.Parameter")
  Set l_selecao_se          = Server.CreateObject("ADODB.Parameter")
  Set l_sq_natureza         = Server.CreateObject("ADODB.Parameter")
  Set l_sq_horizonte        = Server.CreateObject("ADODB.Parameter")
  Set l_unidade_adm         = Server.CreateObject("ADODB.Parameter")
  Set l_ln_programa         = Server.CreateObject("ADODB.Parameter")

  with sp
     set l_Operacao             = .CreateParameter("l_operacao",        adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,    , Tvl(p_chave))
     set l_copia                = .CreateParameter("l_copia",           adInteger, adParamInput,    , Tvl(p_copia))
     set l_menu                 = .CreateParameter("l_menu",            adInteger, adParamInput,    , p_menu)
     set l_unidade              = .CreateParameter("l_unidade",         adInteger, adParamInput,    , Tvl(p_unidade))
     set l_solicitante          = .CreateParameter("l_solicitante",     adInteger, adParamInput,    , Tvl(p_solicitante))
     set l_proponente           = .CreateParameter("l_proponente",      adVarchar, adParamInput,  90, Tvl(p_proponente))
     set l_cadastrador          = .CreateParameter("l_cadastrador",     adInteger, adParamInput,    , Tvl(p_cadastrador))
     set l_executor             = .CreateParameter("l_executor",        adInteger, adParamInput,    , Tvl(p_executor))
     set l_descricao            = .CreateParameter("l_descricao",       adVarchar, adParamInput,2000, Tvl(p_descricao))
     set l_justificativa        = .CreateParameter("l_justificativa",   adVarchar, adParamInput,2000, Tvl(p_justificativa))
     set l_inicio               = .CreateParameter("l_inicio",          adDate,    adParamInput,    , Tvl(p_inicio))
     set l_fim                  = .CreateParameter("l_fim",             adDate,    adParamInput,    , Tvl(p_fim))
     set l_valor                = .CreateParameter("l_valor",           adNumeric ,adParamInput)
     l_valor.Precision    = 18
     l_valor.NumericScale = 2
     l_valor.Value        = Tvl(p_valor)
     set l_data_hora            = .CreateParameter("l_data_hora",       adVarchar, adParamInput,   1, Tvl(p_data_hora))
     set l_unid_resp            = .CreateParameter("l_unid_resp",       adInteger, adParamInput,    , Tvl(p_unid_resp))
     set l_assunto              = .CreateParameter("l_assunto",         adVarchar, adParamInput,2000, Tvl(p_assunto))
     set l_prioridade           = .CreateParameter("l_prioridade",      adInteger, adParamInput,    , Tvl(p_prioridade))
     set l_aviso                = .CreateParameter("l_aviso",           adVarchar, adParamInput,   1, Tvl(p_aviso))
     set l_dias                 = .CreateParameter("l_dias",            adInteger, adParamInput,    , Nvl(p_dias,0))
     set l_cidade               = .CreateParameter("l_cidade",          adInteger, adParamInput,    , Tvl(p_cidade))
     set l_palavra_chave        = .CreateParameter("l_palavra_chave",   adVarchar, adParamInput,  90, Tvl(p_palavra_chave))
     set l_inicio_real          = .CreateParameter("l_inicio_real",     adDate,    adParamInput,    , Tvl(p_inicio_real))
     set l_fim_real             = .CreateParameter("l_fim_real",        adDate,    adParamInput,    , Tvl(p_fim_real))
     set l_concluida            = .CreateParameter("l_concluida",       adVarchar, adParamInput,   1, Tvl(p_concluida))
     set l_data_conclusao       = .CreateParameter("l_data_conclusao",  adDate,    adParamInput,    , Tvl(p_data_conclusao))
     set l_nota_conclusao       = .CreateParameter("l_nota_conclusao",  adVarchar, adParamInput,2000, Tvl(p_nota_conclusao))
     set l_custo_real           = .CreateParameter("l_custo_real",      adNumeric ,adParamInput)
     l_custo_real.Precision    = 18
     l_custo_real.NumericScale = 2
     l_custo_real.Value        = Tvl(p_custo_real)
     set l_opiniao              = .CreateParameter("l_opiniao",         adInteger, adParamInput,    , Tvl(p_opiniao))
     set l_ano                  = .CreateParameter("l_ano",             adInteger, adParamInput,    , Tvl(ano))
     set l_programa             = .CreateParameter("l_programa",        adVarchar, adParamInput,   4, Tvl(p_programa))
     set l_cliente              = .CreateParameter("l_cliente",         adInteger, adParamInput,    , Tvl(cliente))
     set l_acao                 = .CreateParameter("l_acao",            adVarchar, adParamInput,   4, Tvl(p_acao))
     set l_subacao              = .CreateParameter("l_subacao",         adVarchar, adParamInput,   4, Tvl(p_subacao))
     set l_cd_unidade           = .CreateParameter("l_cd_unidade",      adVarchar, adParamInput,   5, Tvl(p_cd_unidade))
     set l_sq_isprojeto         = .CreateParameter("l_sq_isprojeto",    adInteger, adParamInput,    , Tvl(p_sq_isprojeto))
     set l_selecao_mp           = .CreateParameter("l_selecao_mp",      adVarchar, adParamInput,   1, Tvl(p_selecao_mp))
     set l_selecao_se           = .CreateParameter("l_selecao_se",      adVarchar, adParamInput,   1, Tvl(p_selecao_se))
     set l_sq_natureza          = .CreateParameter("l_sq_natureza",     adInteger, adParamInput,    , Tvl(p_sq_natureza))
     set l_sq_horizonte         = .CreateParameter("l_sq_horizonte",    adInteger, adParamInput,    , Tvl(p_sq_horizonte))
     set l_unidade_adm          = .CreateParameter("l_unidade_adm",     adInteger, adParamInput,    , Tvl(p_unidade_adm))
     set l_ln_programa          = .CreateParameter("l_ln_programa",     adVarchar, adParamInput, 120, Tvl(p_ln_programa))
     set l_chave_nova           = .CreateParameter("l_chave_nova",      adInteger, adParamOutput,   , null)
     .parameters.Append         l_Operacao
     .parameters.Append         l_chave
     .parameters.Append         l_copia
     .parameters.Append         l_menu
     .parameters.Append         l_unidade
     .parameters.Append         l_solicitante
     .parameters.Append         l_proponente
     .parameters.Append         l_cadastrador
     .parameters.Append         l_executor
     .parameters.Append         l_descricao
     .parameters.Append         l_justificativa
     .parameters.Append         l_inicio
     .parameters.Append         l_fim
     .parameters.Append         l_valor
     .parameters.Append         l_data_hora
     .parameters.Append         l_unid_resp
     .parameters.Append         l_assunto
     .parameters.Append         l_prioridade
     .parameters.Append         l_aviso
     .parameters.Append         l_dias
     .parameters.Append         l_cidade
     .parameters.Append         l_palavra_chave
     .parameters.Append         l_inicio_real
     .parameters.Append         l_fim_real
     .parameters.Append         l_concluida
     .parameters.Append         l_data_conclusao
     .parameters.Append         l_nota_conclusao
     .parameters.Append         l_custo_real
     .parameters.Append         l_opiniao
     .parameters.Append         l_ano
     .parameters.Append         l_programa
     .parameters.Append         l_cliente
     .parameters.Append         l_acao
     .parameters.Append         l_subacao
     .parameters.Append         l_cd_unidade
     .parameters.Append         l_sq_isprojeto
     .parameters.Append         l_selecao_mp
     .parameters.Append         l_selecao_se
     .parameters.Append         l_sq_natureza
     .parameters.Append         l_sq_horizonte
     .parameters.Append         l_unidade_adm
     .parameters.Append         l_ln_programa
     .parameters.Append         l_chave_nova
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema_is") & "SP_PutAcaoGeral_IS"
     'On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     p_chave_nova = l_chave_nova.Value
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_copia"
     .parameters.Delete         "l_menu"
     .parameters.Delete         "l_unidade"
     .parameters.Delete         "l_solicitante"
     .parameters.Delete         "l_proponente"
     .parameters.Delete         "l_cadastrador"
     .parameters.Delete         "l_executor"
     .parameters.Delete         "l_descricao"
     .parameters.Delete         "l_justificativa"
     .parameters.Delete         "l_inicio"
     .parameters.Delete         "l_fim"
     .parameters.Delete         "l_valor"
     .parameters.Delete         "l_data_hora"
     .parameters.Delete         "l_unid_resp"
     .parameters.Delete         "l_assunto"
     .parameters.Delete         "l_prioridade"
     .parameters.Delete         "l_aviso"
     .parameters.Delete         "l_dias"
     .parameters.Delete         "l_cidade"
     .parameters.Delete         "l_palavra_chave"
     .parameters.Delete         "l_inicio_real"
     .parameters.Delete         "l_fim_real"
     .parameters.Delete         "l_concluida"
     .parameters.Delete         "l_data_conclusao"
     .parameters.Delete         "l_nota_conclusao"
     .parameters.Delete         "l_custo_real"
     .parameters.Delete         "l_opiniao"
     .parameters.Delete         "l_ano"
     .parameters.Delete         "l_programa"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_acao"
     .parameters.Delete         "l_subacao"
     .parameters.Delete         "l_cd_unidade"
     .parameters.Delete         "l_sq_isprojeto"
     .parameters.Delete         "l_selecao_mp"
     .parameters.Delete         "l_selecao_se"
     .parameters.Delete         "l_sq_natureza"
     .parameters.Delete         "l_sq_horizonte"
     .parameters.Delete         "l_unidade_adm"
     .parameters.Delete         "l_ln_programa"
     .parameters.Delete         "l_chave_nova"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Atualiza os responsaveis e seus dados na Aчуo do PPA, Aчao e Iniciativa
REM -------------------------------------------------------------------------

Sub DML_PutRespAcao_IS (p_chave, p_responsavel, p_telefone, p_email, p_tipo)

  Dim l_Chave, l_Responsavel, l_Telefone, l_Email, l_Tipo
  
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_responsavel             = Server.CreateObject("ADODB.Parameter") 
  Set l_telefone                = Server.CreateObject("ADODB.Parameter") 
  Set l_email                   = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo                    = Server.CreateObject("ADODB.Parameter") 
  with sp
     set l_chave                = .CreateParameter("l_chave",                   adInteger, adParamInput,    , Tvl(p_chave))
     set l_responsavel          = .CreateParameter("l_responsavel",             adVarchar, adParamInput,  60, Tvl(p_responsavel))
     set l_telefone             = .CreateParameter("l_telefone",                adVarchar, adParamInput,  20, Tvl(p_telefone))
     set l_email                = .CreateParameter("l_email",                   adVarchar, adParamInput,  60, Tvl(p_email))
     set l_tipo                 = .CreateParameter("l_tipo",                    adInteger, adParamInput,    , Tvl(p_tipo))
  
     .parameters.Append         l_Chave
     .parameters.Append         l_responsavel
     .parameters.Append         l_telefone
     .parameters.Append         l_email
     .parameters.Append         l_tipo

     .CommandText               = Session("schema_is") & "SP_PutRespAcao_IS"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Chave"
     .parameters.Delete         "l_responsavel"
     .parameters.Delete         "l_telefone"
     .parameters.Delete         "l_email"
     .parameters.Delete         "l_tipo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantщm a tabela IS_PROGRAMA do programa ou a tabela IS_ACAO da aчуo
REM -------------------------------------------------------------------------
Sub DML_PutProgQualitativa_IS(p_chave, p_resultados, p_observacoes, p_potencialidades, p_problema, _
                              p_objetivo, p_publico_alvo, p_estrategia, p_sistematica, _
                              p_metodologia, p_restricao)
  Dim l_Chave, l_resultados, l_potencialidades, l_observacoes, l_problema, l_objetivo
  Dim l_publico_alvo, l_estrategia, l_sistematica, l_metodologia, l_restricao
  
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_resultados          = Server.CreateObject("ADODB.Parameter") 
  Set l_observacoes         = Server.CreateObject("ADODB.Parameter") 
  Set l_potencialidades     = Server.CreateObject("ADODB.Parameter")
  Set l_problema            = Server.CreateObject("ADODB.Parameter")
  Set l_objetivo            = Server.CreateObject("ADODB.Parameter")
  Set l_publico_alvo        = Server.CreateObject("ADODB.Parameter")
  Set l_estrategia          = Server.CreateObject("ADODB.Parameter")
  Set l_sistematica         = Server.CreateObject("ADODB.Parameter")
  Set l_metodologia         = Server.CreateObject("ADODB.Parameter")
  Set l_restricao           = Server.CreateObject("ADODB.Parameter")

   
  with sp
     set l_chave                = .CreateParameter("l_chave",             adInteger, adParamInput,    , Tvl(p_chave))
     set l_resultados           = .CreateParameter("l_resultados",        adVarchar, adParamInput,2000, Tvl(p_resultados))
     set l_observacoes          = .CreateParameter("l_observacoes",       adVarchar, adParamInput,2000, Tvl(p_observacoes))
     set l_potencialidades      = .CreateParameter("l_potencialidades",   adVarchar, adParamInput,2000, Tvl(p_potencialidades))
     set l_problema             = .CreateParameter("l_problema",          adVarchar, adParamInput,2000, Tvl(p_problema))
     set l_objetivo             = .CreateParameter("l_objetivo",          adVarchar, adParamInput,2000, Tvl(p_objetivo))
     set l_publico_alvo         = .CreateParameter("l_publico_alvo",      adVarchar, adParamInput,2000, Tvl(p_publico_alvo))
     set l_estrategia           = .CreateParameter("l_estrategia",        adVarchar, adParamInput,2000, Tvl(p_estrategia))
     set l_sistematica          = .CreateParameter("l_sistematica",       adVarchar, adParamInput,2000, Tvl(p_sistematica))
     set l_metodologia          = .CreateParameter("l_metodologia",       adVarchar, adParamInput,2000, Tvl(p_metodologia))
     set l_restricao            = .CreateParameter("l_restricao",         adVarchar, adParamInput,  30, Tvl(p_restricao))

     .parameters.Append         l_chave
     .parameters.Append         l_resultados
     .parameters.Append         l_observacoes
     .parameters.Append         l_potencialidades
     .parameters.Append         l_problema
     .parameters.Append         l_objetivo
     .parameters.Append         l_publico_alvo
     .parameters.Append         l_estrategia
     .parameters.Append         l_sistematica
     .parameters.Append         l_metodologia
     .parameters.Append         l_restricao

     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema_is") & "SP_PutProgQualitativa_IS"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_resultados"
     .parameters.Delete         "l_observacoes"
     .parameters.Delete         "l_potencialidades"
     .parameters.Delete         "l_problema"
     .parameters.Delete         "l_objetivo"
     .parameters.Delete         "l_publico_alvo"
     .parameters.Delete         "l_estrategia"
     .parameters.Delete         "l_sistematica"
     .parameters.Delete         "l_metodologia"
     .parameters.Delete         "l_restricao"

  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------


REM =========================================================================
REM Mantщm a tabela de metas de uma aчуo
REM -------------------------------------------------------------------------
Sub DML_PutAcaoMeta_IS(Operacao, p_chave, p_chave_aux, p_titulo, _
       p_descricao, p_ordem, p_inicio, p_fim, p_perc_conclusao, p_orcamento, _
       p_programada, p_cumulativa, p_quantidade, p_unidade_medida)
  Dim l_Operacao, l_chave, l_chave_aux, l_titulo, l_descricao, l_ordem
  Dim l_inicio, l_fim, l_perc_conclusao, l_orcamento
  Dim l_programada, l_cumulativa, l_quantidade, l_unidade_medida
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_chave_aux           = Server.CreateObject("ADODB.Parameter") 
  Set l_titulo              = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao           = Server.CreateObject("ADODB.Parameter") 
  Set l_ordem               = Server.CreateObject("ADODB.Parameter") 
  Set l_inicio              = Server.CreateObject("ADODB.Parameter") 
  Set l_fim                 = Server.CreateObject("ADODB.Parameter")
  Set l_perc_conclusao      = Server.CreateObject("ADODB.Parameter")
  Set l_orcamento           = Server.CreateObject("ADODB.Parameter")
  Set l_programada          = Server.CreateObject("ADODB.Parameter")
  Set l_cumulativa          = Server.CreateObject("ADODB.Parameter")
  Set l_quantidade          = Server.CreateObject("ADODB.Parameter")
  Set l_unidade_medida      = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",            adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,    , Tvl(p_chave))
     set l_chave_aux            = .CreateParameter("l_chave_aux",           adInteger, adParamInput,    , Tvl(p_chave_aux))
     set l_titulo               = .CreateParameter("l_titulo",              adVarchar, adParamInput, 100, Tvl(p_titulo))
     set l_descricao            = .CreateParameter("l_descricao",           adVarchar, adParamInput,2000, Tvl(p_descricao))
     set l_ordem                = .CreateParameter("l_ordem",               adVarchar, adParamInput,   3, Tvl(p_ordem))
     set l_inicio               = .CreateParameter("l_inicio",              adDate,    adParamInput,    , Tvl(p_inicio))
     set l_fim                  = .CreateParameter("l_fim",                 adDate,    adParamInput,    , Tvl(p_fim))
     set l_perc_conclusao       = .CreateParameter("l_perc_conclusao",      adInteger, adParamInput,    , Tvl(p_perc_conclusao))
     set l_orcamento            = .CreateParameter("l_orcamento",           adNumeric ,adParamInput)
     l_orcamento.Precision    = 18
     l_orcamento.NumericScale = 2
     l_orcamento.Value        = Tvl(p_orcamento)
     set l_programada           = .CreateParameter("l_programada",          adVarchar, adParamInput,   1, p_programada)
     set l_cumulativa           = .CreateParameter("l_cumulativa",          adVarchar, adParamInput,   1, p_cumulativa)
     set l_quantidade            = .CreateParameter("l_quantidade",           adNumeric ,adParamInput)
     l_quantidade.Precision    = 18
     l_quantidade.NumericScale = 2
     l_quantidade.Value        = Tvl(p_quantidade)
     set l_unidade_medida       = .CreateParameter("l_unidade_medida",      adVarchar, adParamInput,  30, p_unidade_medida)
     .parameters.Append         l_Operacao
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_titulo
     .parameters.Append         l_descricao
     .parameters.Append         l_ordem
     .parameters.Append         l_inicio
     .parameters.Append         l_fim
     .parameters.Append         l_perc_conclusao
     .parameters.Append         l_orcamento
     .parameters.Append         l_programada
     .parameters.Append         l_cumulativa
     .parameters.Append         l_quantidade
     .parameters.Append         l_unidade_medida
     
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema_is") & "SP_PutAcaoMeta_IS"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_chave_aux"
     .parameters.Delete         "l_titulo"
     .parameters.Delete         "l_descricao"
     .parameters.Delete         "l_ordem"
     .parameters.Delete         "l_inicio"
     .parameters.Delete         "l_fim"
     .parameters.Delete         "l_perc_conclusao"
     .parameters.Delete         "l_orcamento"
     .parameters.Delete         "l_programada"
     .parameters.Delete         "l_cumulativa"
     .parameters.Delete         "l_quantidade"
     .parameters.Delete         "l_unidade_medida"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Atualiza uma meta da aчуo
REM -------------------------------------------------------------------------
Sub DML_PutAtualizaMeta_IS(p_chave, p_chave_aux, p_perc_conclusao, p_situacao_atual, _
                         p_exequivel, p_justificativa_inex, p_outras_medidas)
  Dim l_chave, l_chave_aux, l_usuario, l_perc_conclusao, l_situacao_atual, l_exequivel
  Dim l_justificativa_inex, l_outras_medidas
  
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_chave_aux           = Server.CreateObject("ADODB.Parameter") 
  Set l_perc_conclusao      = Server.CreateObject("ADODB.Parameter")
  Set l_situacao_atual      = Server.CreateObject("ADODB.Parameter")
  Set l_exequivel           = Server.CreateObject("ADODB.Parameter")
  Set l_justificativa_inex  = Server.CreateObject("ADODB.Parameter")
  Set l_outras_medidas      = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,    , p_chave)
     set l_chave_aux            = .CreateParameter("l_chave_aux",           adInteger, adParamInput,    , p_chave_aux)
     set l_perc_conclusao       = .CreateParameter("l_perc_conclusao",      adDouble, adParamInput,     , p_perc_conclusao)
     set l_situacao_atual       = .CreateParameter("l_situacao_atual",      adVarchar, adParamInput,4000, Tvl(p_situacao_atual))
     set l_exequivel            = .CreateParameter("l_exequivel",           adVarchar, adParamInput,   1, Tvl(p_exequivel))
     set l_justificativa_inex   = .CreateParameter("l_justificativa_inex",  adVarchar, adParamInput,4000, Tvl(p_justificativa_inex))
     set l_outras_medidas       = .CreateParameter("l_outras_medidas",      adVarchar, adParamInput,4000, Tvl(p_outras_medidas))
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_perc_conclusao
     .parameters.Append         l_situacao_atual
     .parameters.Append         l_exequivel
     .parameters.Append         l_justificativa_inex
     .parameters.Append         l_outras_medidas
     .CommandText               = Session("schema_is") & "SP_PutAtualizaMeta_IS"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_chave_aux"
     .parameters.Delete         "l_perc_conclusao"
     .parameters.Delete         "l_situacao_atual"
     .parameters.Delete         "l_exequivel"
     .parameters.Delete         "l_justificativa_inex"
     .parameters.Delete         "l_outras_medidas"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantщm a tabela de atualzaчуo mensal das metas de uma aчуo
REM -------------------------------------------------------------------------
Sub DML_PutMetaMensal_IS(Operacao, p_chave, p_realizado, p_revisado, p_referencia, p_cliente)
  Dim l_Operacao, l_chave, l_realizado, l_revisado, l_referencia, l_cliente
    
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter") 
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_realizado           = Server.CreateObject("ADODB.Parameter")
  Set l_revisado            = Server.CreateObject("ADODB.Parameter")
  Set l_referencia          = Server.CreateObject("ADODB.Parameter")
  Set l_cliente             = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",            adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,    , Tvl(p_chave))
     set l_realizado            = .CreateParameter("l_realizado",           adInteger, adParamInput,    , Nvl(p_realizado,0))
     set l_revisado             = .CreateParameter("l_revisado",            adInteger, adParamInput,    , Nvl(p_revisado,0))
     set l_referencia           = .CreateParameter("l_referencia",          adDate,    adParamInput,    , Tvl(p_referencia))
     set l_cliente              = .CreateParameter("l_cliente",             adInteger, adParamInput,    , Tvl(p_cliente))
     
     .parameters.Append         l_Operacao
     .parameters.Append         l_chave
     .parameters.Append         l_realizado
     .parameters.Append         l_revisado
     .parameters.Append         l_referencia
     .parameters.Append         l_cliente
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema_is") & "SP_PutMetaMensal_IS"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_realizado"
     .parameters.Delete         "l_revisado"
     .parameters.Delete         "l_referencia"
     .parameters.Delete         "l_cliente"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantщm a tabela de financiamento da aчуo
REM -------------------------------------------------------------------------
Sub DML_PutFinancAcaoPPA_IS(Operacao, p_chave, p_programa, p_acao, p_subacao, cliente, ano, p_obs_financ)
  Dim l_Operacao, l_chave, l_cd_programa, l_cd_acao, l_cd_subacao
  Dim l_ano, l_cliente, l_obs_financ
  
  Set l_Operacao     = Server.CreateObject("ADODB.Parameter")
  Set l_chave        = Server.CreateObject("ADODB.Parameter")
  Set l_cd_programa  = Server.CreateObject("ADODB.Parameter")
  Set l_cd_acao      = Server.CreateObject("ADODB.Parameter")
  Set l_cd_subacao   = Server.CreateObject("ADODB.Parameter")
  Set l_cliente      = Server.CreateObject("ADODB.Parameter")
  Set l_ano          = Server.CreateObject("ADODB.Parameter")
  Set l_obs_financ   = Server.CreateObject("ADODB.Parameter")  
  with sp
     set l_Operacao             = .CreateParameter("l_Operacao",        adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,    ,tvl(p_chave))
     set l_cd_programa          = .CreateParameter("l_cd_programa",     adVarchar, adParamInput,   4,tvl(p_programa))
     set l_cd_acao              = .CreateParameter("l_cd_acao",         adVarchar, adParamInput,   4,tvl(p_acao))
     set l_cd_subacao           = .CreateParameter("l_cd_subacao",      adVarchar, adParamInput,   4,tvl(p_subacao))
     set l_cliente              = .CreateParameter("l_cliente",         adInteger, adParamInput,    ,tvl(cliente))
     set l_ano                  = .CreateParameter("l_ano",             adInteger, adParamInput,    ,tvl(ano))
     set l_obs_financ           = .CreateParameter("l_obs_financ",      adVarchar, adParamInput,2000,tvl(p_obs_financ))
     .parameters.Append         l_Operacao
     .parameters.Append         l_chave
     .parameters.Append         l_cd_programa
     .parameters.Append         l_cd_acao
     .parameters.Append         l_cd_subacao
     .parameters.Append         l_cliente
     .parameters.Append         l_ano
     .parameters.Append         l_obs_financ
     .CommandText               = Session("schema_is") & "SP_PutFinancAcaoPPA_IS"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_cd_programa"
     .parameters.Delete         "l_cd_acao"
     .parameters.Delete         "l_cd_subacao"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_ano"
     .parameters.Delete         "l_obs_financ"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantщm a tabela de restriчѕes da aчуo
REM -------------------------------------------------------------------------
Sub DML_PutRestricaoAcao_IS (Operacao, cliente, ano, p_programa, p_acao, p_subacao, p_chave_aux, _
                             p_cd_tipo_restricao, p_cd_tipo_inclusao, p_cd_competencia, _
                             p_superacao, p_relatorio, p_tempo_habil, p_descricao, _
                             p_providencia, p_observacao_controle, p_observacao_monitor)

  Dim l_Operacao, l_cliente, l_ano, l_programa, l_acao, l_subacao, l_chave_aux
  Dim l_cd_tipo_restricao, l_cd_tipo_inclusao, l_cd_competencia
  Dim l_superacao, l_relatorio, l_tempo_habil, l_descricao
  Dim l_providencia, l_observacao_controle, l_observacao_monitor
  
  Set l_Operacao                = Server.CreateObject("ADODB.Parameter")  
  Set l_cliente                 = Server.CreateObject("ADODB.Parameter") 
  Set l_ano                     = Server.CreateObject("ADODB.Parameter")
  Set l_programa                = Server.CreateObject("ADODB.Parameter")
  Set l_acao                    = Server.CreateObject("ADODB.Parameter") 
  Set l_subacao                 = Server.CreateObject("ADODB.Parameter")  
  Set l_chave_aux               = Server.CreateObject("ADODB.Parameter")  
  Set l_cd_tipo_restricao       = Server.CreateObject("ADODB.Parameter") 
  Set l_cd_tipo_inclusao        = Server.CreateObject("ADODB.Parameter") 
  Set l_cd_competencia          = Server.CreateObject("ADODB.Parameter") 
  Set l_superacao               = Server.CreateObject("ADODB.Parameter") 
  Set l_relatorio               = Server.CreateObject("ADODB.Parameter") 
  Set l_tempo_habil             = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao               = Server.CreateObject("ADODB.Parameter") 
  Set l_providencia             = Server.CreateObject("ADODB.Parameter") 
  Set l_observacao_controle     = Server.CreateObject("ADODB.Parameter") 
  Set l_observacao_monitor      = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_Operacao              = .CreateParameter("l_Operacao",              adVarchar, adParamInput,   1, Operacao)
     set l_cliente               = .CreateParameter("l_cliente",               adInteger, adParamInput,    , cliente)
     set l_ano                   = .CreateParameter("l_ano",                   adInteger, adParamInput,    , ano)
     set l_programa              = .CreateParameter("l_programa",              adVarchar, adParamInput,   4, p_programa)
     set l_acao                  = .CreateParameter("l_acao",                  adVarchar, adParamInput,   4, p_acao)
     set l_subacao               = .CreateParameter("l_subacao",               adVarchar, adParamInput,   4, p_subacao)
     set l_chave_aux             = .CreateParameter("l_chave_aux",             adInteger, adParamInput,    , Tvl(p_chave_aux))     
     set l_cd_tipo_restricao     = .CreateParameter("l_cd_tipo_restricao",     adInteger, adParamInput,    , Tvl(p_cd_tipo_restricao))
     set l_cd_tipo_inclusao      = .CreateParameter("l_cd_tipo_inclusao",      adVarchar, adParamInput,   2, Tvl(p_cd_tipo_inclusao))
     set l_cd_competencia        = .CreateParameter("l_cd_competencia",        adVarchar, adParamInput,   2, p_cd_competencia)
     set l_superacao             = .CreateParameter("l_superacao",             adDate   , adParamInput,    , Tvl(p_superacao))
     set l_relatorio             = .CreateParameter("l_relatorio",             adVarchar, adParamInput,   1, Tvl(p_relatorio))
     set l_tempo_habil           = .CreateParameter("l_tempo_habil",           adVarchar, adParamInput,   1, Tvl(p_tempo_habil))
     set l_descricao             = .CreateParameter("l_descricao",             adVarchar, adParamInput,4000, Tvl(p_descricao))
     set l_providencia           = .CreateParameter("l_providencia",           adVarchar, adParamInput,4000, Tvl(p_providencia))
     set l_observacao_controle   = .CreateParameter("l_observacao_controle",   adVarchar, adParamInput,4000, Tvl(p_observacao_controle))
     set l_observacao_monitor    = .CreateParameter("l_observacao_monitor",    adVarchar, adParamInput,4000, Tvl(p_observacao_monitor))
     
     .parameters.Append         l_Operacao
     .parameters.Append         l_cliente
     .parameters.Append         l_ano
     .parameters.Append         l_programa
     .parameters.Append         l_acao
     .parameters.Append         l_subacao
     .parameters.Append         l_chave_aux
     .parameters.Append         l_cd_tipo_restricao
     .parameters.Append         l_cd_tipo_inclusao
     .parameters.Append         l_cd_competencia
     .parameters.Append         l_superacao
     .parameters.Append         l_relatorio
     .parameters.Append         l_tempo_habil
     .parameters.Append         l_descricao
     .parameters.Append         l_providencia
     .parameters.Append         l_observacao_controle
     .parameters.Append         l_observacao_monitor

     .CommandText               = Session("schema_is") & "SP_PutRestricaoAcao_IS"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_ano"
     .parameters.Delete         "l_programa"
     .parameters.Delete         "l_acao"
     .parameters.Delete         "l_subacao"
     .parameters.Delete         "l_chave_aux"
     .parameters.Delete         "l_cd_tipo_restricao"
     .parameters.Delete         "l_cd_tipo_inclusao"
     .parameters.Delete         "l_cd_competencia"
     .parameters.Delete         "l_superacao"
     .parameters.Delete         "l_relatorio"
     .parameters.Delete         "l_tempo_habil"
     .parameters.Delete         "l_descricao"
     .parameters.Delete         "l_providencia"
     .parameters.Delete         "l_observacao_controle"
     .parameters.Delete         "l_observacao_monitor"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantщm a tabela IS_RESTRICAO
REM -------------------------------------------------------------------------
Sub DML_PutRestricao_IS (Operacao, p_restricao, p_chave, p_chave_aux, p_cd_subacao, p_sq_isprojeto, _
                             p_cd_tipo_restricao, p_cd_tipo_inclusao, p_cd_competencia, _
                             p_superacao, p_relatorio, p_tempo_habil, p_descricao, _
                             p_providencia, p_observacao_controle, p_observacao_monitor, p_ano, p_cliente)

  Dim l_Operacao, l_restricao, l_chave, l_chave_aux, l_cd_subacao, l_sq_isprojeto
  Dim l_cd_tipo_restricao, l_cd_tipo_inclusao, l_cd_competencia
  Dim l_superacao, l_relatorio, l_tempo_habil, l_descricao
  Dim l_providencia, l_observacao_controle, l_observacao_monitor
  Dim l_ano, l_cliente
  
  Set l_Operacao                = Server.CreateObject("ADODB.Parameter")  
  Set l_restricao               = Server.CreateObject("ADODB.Parameter") 
  Set l_chave                   = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux               = Server.CreateObject("ADODB.Parameter")  
  Set l_cd_subacao              = Server.CreateObject("ADODB.Parameter")  
  Set l_sq_isprojeto            = Server.CreateObject("ADODB.Parameter")  
  Set l_cd_tipo_restricao       = Server.CreateObject("ADODB.Parameter") 
  Set l_cd_tipo_inclusao        = Server.CreateObject("ADODB.Parameter") 
  Set l_cd_competencia          = Server.CreateObject("ADODB.Parameter") 
  Set l_superacao               = Server.CreateObject("ADODB.Parameter") 
  Set l_relatorio               = Server.CreateObject("ADODB.Parameter") 
  Set l_tempo_habil             = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao               = Server.CreateObject("ADODB.Parameter") 
  Set l_providencia             = Server.CreateObject("ADODB.Parameter") 
  Set l_observacao_controle     = Server.CreateObject("ADODB.Parameter") 
  Set l_observacao_monitor      = Server.CreateObject("ADODB.Parameter")
  Set l_ano                     = Server.CreateObject("ADODB.Parameter")
  Set l_cliente                 = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_Operacao              = .CreateParameter("l_Operacao",              adVarchar, adParamInput,   1, Operacao)
     set l_restricao             = .CreateParameter("l_restricao",             adVarchar, adParamInput,  11, p_restricao)
     set l_chave                 = .CreateParameter("l_chave",                 adInteger, adParamInput,    , p_chave)
     set l_chave_aux             = .CreateParameter("l_chave_aux",             adInteger, adParamInput,    , Tvl(p_chave_aux))     
     set l_cd_subacao            = .CreateParameter("l_cd_subacao",            adVarchar, adParamInput,   4, tvl(p_cd_subacao))
     set l_sq_isprojeto          = .CreateParameter("l_sq_isprojeto",          adInteger, adParamInput,    , tvl(p_sq_isprojeto))     
     set l_cd_tipo_restricao     = .CreateParameter("l_cd_tipo_restricao",     adInteger, adParamInput,    , Tvl(p_cd_tipo_restricao))
     set l_cd_tipo_inclusao      = .CreateParameter("l_cd_tipo_inclusao",      adVarchar, adParamInput,   2, Tvl(p_cd_tipo_inclusao))
     set l_cd_competencia        = .CreateParameter("l_cd_competencia",        adVarchar, adParamInput,   2, p_cd_competencia)
     set l_superacao             = .CreateParameter("l_superacao",             adDate   , adParamInput,    , Tvl(p_superacao))
     set l_relatorio             = .CreateParameter("l_relatorio",             adVarchar, adParamInput,   1, Tvl(p_relatorio))
     set l_tempo_habil           = .CreateParameter("l_tempo_habil",           adVarchar, adParamInput,   1, Tvl(p_tempo_habil))
     set l_descricao             = .CreateParameter("l_descricao",             adVarchar, adParamInput,4000, Tvl(p_descricao))
     set l_providencia           = .CreateParameter("l_providencia",           adVarchar, adParamInput,4000, Tvl(p_providencia))
     set l_observacao_controle   = .CreateParameter("l_observacao_controle",   adVarchar, adParamInput,4000, Tvl(p_observacao_controle))
     set l_observacao_monitor    = .CreateParameter("l_observacao_monitor",    adVarchar, adParamInput,4000, Tvl(p_observacao_monitor))
     set l_ano                   = .CreateParameter("l_ano",                   adInteger, adParamInput,    , Tvl(p_ano))
     set l_cliente               = .CreateParameter("l_cliente",               adInteger, adParamInput,    , Tvl(p_cliente))
     
     .parameters.Append         l_Operacao
     .parameters.Append         l_restricao
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_cd_subacao
     .parameters.Append         l_sq_isprojeto     
     .parameters.Append         l_cd_tipo_restricao
     .parameters.Append         l_cd_tipo_inclusao
     .parameters.Append         l_cd_competencia
     .parameters.Append         l_superacao
     .parameters.Append         l_relatorio
     .parameters.Append         l_tempo_habil
     .parameters.Append         l_descricao
     .parameters.Append         l_providencia
     .parameters.Append         l_observacao_controle
     .parameters.Append         l_observacao_monitor
     .parameters.Append         l_ano
     .parameters.Append         l_cliente

     .CommandText               = Session("schema_is") & "SP_PutRestricao_IS"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_restricao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_chave_aux"
     .parameters.Delete         "l_cd_subacao"
     .parameters.Delete         "l_sq_isprojeto"     
     .parameters.Delete         "l_cd_tipo_restricao"
     .parameters.Delete         "l_cd_tipo_inclusao"
     .parameters.Delete         "l_cd_competencia"
     .parameters.Delete         "l_superacao"
     .parameters.Delete         "l_relatorio"
     .parameters.Delete         "l_tempo_habil"
     .parameters.Delete         "l_descricao"
     .parameters.Delete         "l_providencia"
     .parameters.Delete         "l_observacao_controle"
     .parameters.Delete         "l_observacao_monitor"
     .parameters.Delete         "l_ano"
     .parameters.Delete         "l_cliente"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>