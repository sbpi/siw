<%
REM =========================================================================
REM Atualiza os responsaveis e seus dados no Programa PPA
REM -------------------------------------------------------------------------
Sub DML_PutRespPrograma_IS (p_chave, _
                            p_nm_gerente_programa, p_fn_gerente_programa, p_em_gerente_programa, _
                            p_nm_gerente_executivo, p_fn_gerente_executivo, p_em_gerente_executivo, _
                            p_nm_gerente_adjunto, p_fn_gerente_adjunto, p_em_gerente_adjunto)

  Dim l_chave
  Dim l_nm_gerente_programa, l_fn_gerente_programa, l_em_gerente_programa
  Dim l_nm_gerente_executivo, l_fn_gerente_executivo, l_em_gerente_executivo
  Dim l_nm_gerente_adjunto, l_fn_gerente_adjunto, l_em_gerente_adjunto
  
  Set l_chave                   = Server.CreateObject("ADODB.Parameter") 
  Set l_nm_gerente_programa     = Server.CreateObject("ADODB.Parameter") 
  Set l_fn_gerente_programa     = Server.CreateObject("ADODB.Parameter") 
  Set l_em_gerente_programa     = Server.CreateObject("ADODB.Parameter") 
  Set l_nm_gerente_executivo    = Server.CreateObject("ADODB.Parameter") 
  Set l_fn_gerente_executivo    = Server.CreateObject("ADODB.Parameter") 
  Set l_em_gerente_executivo    = Server.CreateObject("ADODB.Parameter") 
  Set l_nm_gerente_adjunto      = Server.CreateObject("ADODB.Parameter") 
  Set l_fn_gerente_adjunto      = Server.CreateObject("ADODB.Parameter") 
  Set l_em_gerente_adjunto      = Server.CreateObject("ADODB.Parameter") 
  
  with sp
     set l_chave                 = .CreateParameter("l_chave",                   adInteger, adParamInput,    , Tvl(p_chave))
     set l_nm_gerente_programa   = .CreateParameter("l_nm_gerente_programa",     adVarchar, adParamInput,  60, Tvl(p_nm_gerente_programa))
     set l_fn_gerente_programa   = .CreateParameter("l_fn_gerente_programa",     adVarchar, adParamInput,  20, Tvl(p_fn_gerente_programa))
     set l_em_gerente_programa   = .CreateParameter("l_em_gerente_programa",     adVarchar, adParamInput,  60, Tvl(p_em_gerente_programa))
     set l_nm_gerente_executivo  = .CreateParameter("l_nm_gerente_executivo",    adVarchar, adParamInput,  60, Tvl(p_nm_gerente_executivo))
     set l_fn_gerente_executivo  = .CreateParameter("l_fn_gerente_executivo",    adVarchar, adParamInput,  20, Tvl(p_fn_gerente_executivo))
     set l_em_gerente_executivo  = .CreateParameter("l_em_gerente_executivo",    adVarchar, adParamInput,  60, Tvl(p_em_gerente_executivo))
     set l_nm_gerente_adjunto    = .CreateParameter("l_nm_gerente_adjunto",      adVarchar, adParamInput,  60, Tvl(p_nm_gerente_adjunto))
     set l_fn_gerente_adjunto    = .CreateParameter("l_fn_gerente_adjunto",      adVarchar, adParamInput,  20, Tvl(p_fn_gerente_adjunto))
     set l_em_gerente_adjunto    = .CreateParameter("l_em_gerente_adjunto",      adVarchar, adParamInput,  60, Tvl(p_em_gerente_adjunto))

     .parameters.Append         l_chave
     .parameters.Append         l_nm_gerente_programa
     .parameters.Append         l_fn_gerente_programa
     .parameters.Append         l_em_gerente_programa
     .parameters.Append         l_nm_gerente_executivo
     .parameters.Append         l_fn_gerente_executivo
     .parameters.Append         l_em_gerente_executivo
     .parameters.Append         l_nm_gerente_adjunto
     .parameters.Append         l_fn_gerente_adjunto
     .parameters.Append         l_em_gerente_adjunto
     
     .CommandText               = Session("schema_is") & "SP_PutRespPrograma_IS"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_nm_gerente_programa"
     .parameters.Delete         "l_fn_gerente_programa"
     .parameters.Delete         "l_em_gerente_programa"
     .parameters.Delete         "l_nm_gerente_executivo"
     .parameters.Delete         "l_fn_gerente_executivo"
     .parameters.Delete         "l_em_gerente_executivo"
     .parameters.Delete         "l_nm_gerente_adjunto"
     .parameters.Delete         "l_fn_gerente_adjunto"
     .parameters.Delete         "l_em_gerente_adjunto"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantém a tabela de indicadores do programa
REM -------------------------------------------------------------------------
Sub DML_PutIndicador_IS (Operacao, p_chave, p_chave_aux, ano, cliente, p_cd_programa, p_cd_unidade_medida, _
                            p_cd_periodicidade, p_cd_base_geografica, p_categoria_analise, p_ordem, _
                            p_titulo, p_conceituacao, p_interpretacao, p_usos, p_limitacoes, p_comentarios, _
                            p_fonte, p_formula, p_tipo, p_indice_ref, p_indice_apurado, p_apuracao_ref, p_apuracao_ind, p_observacoes, _
                            p_cumulativa, p_quantidade, p_exequivel, p_situacao_atual, p_justificativa_inex, p_outras_medidas, _
                            p_prev_ano_1, p_prev_ano_2, p_prev_ano_3, p_prev_ano_4, p_p1)

  Dim l_Operacao, l_chave_aux, l_chave, l_ano, l_cliente, l_cd_programa, l_cd_unidade_medida
  Dim l_cd_periodicidade, l_cd_base_geografica, l_categoria_analise, l_ordem
  Dim l_titulo, l_conceituacao, l_interpretacao, l_usos, l_limitacoes, l_comentarios
  Dim l_fonte, l_formula, l_tipo, l_indice_ref, l_indice_apurado, l_apuracao_ref, l_apuracao_ind, l_observacoes
  Dim l_cumulativa, l_quantidade, l_exequivel, l_situacao_atual, l_justificativa_inex, l_outras_medidas
  Dim l_prev_ano_1, l_prev_ano_2, l_prev_ano_3, l_prev_ano_4, l_p1
  
  Set l_Operacao                = Server.CreateObject("ADODB.Parameter") 
  Set l_chave                   = Server.CreateObject("ADODB.Parameter")
  Set l_chave_aux               = Server.CreateObject("ADODB.Parameter")  
  Set l_ano                     = Server.CreateObject("ADODB.Parameter") 
  Set l_cliente                 = Server.CreateObject("ADODB.Parameter") 
  Set l_cd_programa             = Server.CreateObject("ADODB.Parameter") 
  Set l_cd_unidade_medida       = Server.CreateObject("ADODB.Parameter") 
  Set l_cd_periodicidade        = Server.CreateObject("ADODB.Parameter") 
  Set l_cd_base_geografica      = Server.CreateObject("ADODB.Parameter") 
  Set l_categoria_analise       = Server.CreateObject("ADODB.Parameter") 
  Set l_ordem                   = Server.CreateObject("ADODB.Parameter") 
  Set l_titulo                  = Server.CreateObject("ADODB.Parameter") 
  Set l_conceituacao            = Server.CreateObject("ADODB.Parameter") 
  Set l_interpretacao           = Server.CreateObject("ADODB.Parameter") 
  Set l_usos                    = Server.CreateObject("ADODB.Parameter") 
  Set l_limitacoes              = Server.CreateObject("ADODB.Parameter")
  Set l_comentarios             = Server.CreateObject("ADODB.Parameter") 
  Set l_fonte                   = Server.CreateObject("ADODB.Parameter") 
  Set l_formula                 = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo                    = Server.CreateObject("ADODB.Parameter")  
  Set l_indice_ref              = Server.CreateObject("ADODB.Parameter") 
  Set l_indice_apurado          = Server.CreateObject("ADODB.Parameter")
  Set l_apuracao_ref            = Server.CreateObject("ADODB.Parameter")
  Set l_apuracao_ind            = Server.CreateObject("ADODB.Parameter") 
  Set l_cumulativa              = Server.CreateObject("ADODB.Parameter")
  Set l_quantidade              = Server.CreateObject("ADODB.Parameter")
  Set l_exequivel               = Server.CreateObject("ADODB.Parameter") 
  Set l_situacao_atual          = Server.CreateObject("ADODB.Parameter") 
  Set l_justificativa_inex      = Server.CreateObject("ADODB.Parameter") 
  Set l_prev_ano_1              = Server.CreateObject("ADODB.Parameter") 
  Set l_prev_ano_2              = Server.CreateObject("ADODB.Parameter")
  Set l_prev_ano_3              = Server.CreateObject("ADODB.Parameter")
  Set l_prev_ano_4              = Server.CreateObject("ADODB.Parameter")
  Set l_p1                      = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_Operacao              = .CreateParameter("l_Operacao",              adVarchar, adParamInput,   1, Operacao)
     set l_chave                 = .CreateParameter("l_chave",                 adInteger, adParamInput,    , p_chave)
     set l_chave_aux             = .CreateParameter("l_chave_aux",             adInteger, adParamInput,    , Tvl(p_chave_aux))
     set l_ano                   = .CreateParameter("l_ano",                   adInteger, adParamInput,    , ano)
     set l_cliente               = .CreateParameter("l_cliente",               adInteger, adParamInput,    , cliente)
     set l_cd_programa           = .CreateParameter("l_cd_programa",           adVarchar, adParamInput,   4, p_cd_programa)
     set l_cd_unidade_medida     = .CreateParameter("l_cd_unidade_medida",     adInteger, adParamInput,    , Tvl(p_cd_unidade_medida))
     set l_cd_periodicidade      = .CreateParameter("l_cd_periodicidade",      adInteger, adParamInput,    , Tvl(p_cd_periodicidade))
     set l_cd_base_geografica    = .CreateParameter("l_cd_base_geografica",    adInteger, adParamInput,    , Tvl(p_cd_base_geografica))
     set l_categoria_analise     = .CreateParameter("l_categoria_analise",     adVarchar, adParamInput,2000, Tvl(p_categoria_analise))
     set l_ordem                 = .CreateParameter("l_ordem",                 adInteger, adParamInput,    , p_ordem)
     set l_titulo                = .CreateParameter("l_titulo",                adVarchar, adParamInput, 200, p_titulo)
     set l_conceituacao          = .CreateParameter("l_conceituacao",          adVarchar, adParamInput,2000, p_conceituacao)
     set l_interpretacao         = .CreateParameter("l_interpretacao",         adVarchar, adParamInput,2000, Tvl(p_interpretacao))
     set l_usos                  = .CreateParameter("l_usos",                  adVarchar, adParamInput,2000, Tvl(p_usos))
     set l_limitacoes            = .CreateParameter("l_limitacoes",            adVarchar, adParamInput,2000, Tvl(p_limitacoes))
     set l_comentarios           = .CreateParameter("l_comentarios",           adVarchar, adParamInput,2000, Tvl(p_comentarios))
     set l_fonte                 = .CreateParameter("l_fonte",                 adVarchar, adParamInput, 200, Tvl(p_fonte))
     set l_tipo                  = .CreateParameter("l_tipo",                  adVarchar, adParamInput,   1, Tvl(p_tipo))
     set l_formula               = .CreateParameter("l_formula",               adVarchar, adParamInput,4000, Tvl(p_formula))
     set l_indice_ref            = .CreateParameter("l_indice_ref",            adNumeric ,adParamInput)
     l_indice_ref.Precision    = 18
     l_indice_ref.NumericScale = 2
     l_indice_ref.Value        = Tvl(p_indice_ref)
     set l_indice_apurado      = .CreateParameter("l_indice_apurado",          adNumeric ,adParamInput)
     l_indice_apurado.Precision    = 18
     l_indice_apurado.NumericScale = 2
     l_indice_apurado.Value        = Tvl(p_indice_apurado)
     set l_apuracao_ref          = .CreateParameter("l_apuracao_ref",          adDate   , adParamInput,    , Tvl(p_apuracao_ref))
     set l_apuracao_ind          = .CreateParameter("l_apuracao_ind",          adDate   , adParamInput,    , Tvl(p_apuracao_ind))
     set l_observacoes           = .CreateParameter("l_observacoes",           adVarchar, adParamInput,4000, Tvl(p_observacoes))
     set l_cumulativa            = .CreateParameter("l_cumulativa",            adVarchar, adParamInput,   1, Tvl(p_cumulativa))
     set l_quantidade            = .CreateParameter("l_quantidade",            adNumeric ,adParamInput)
     l_quantidade.Precision    = 18
     l_quantidade.NumericScale = 2
     l_quantidade.Value        = Tvl(p_quantidade)
     set l_exequivel             = .CreateParameter("l_exequivel",             adVarchar, adParamInput,   1, Tvl(p_exequivel))
     set l_situacao_atual        = .CreateParameter("l_situacao_atual",        adVarchar, adParamInput,4000, Tvl(p_situacao_atual))
     set l_justificativa_inex    = .CreateParameter("l_justificativa_inex",    adVarchar, adParamInput,1000, Tvl(p_justificativa_inex))     
     set l_outras_medidas        = .CreateParameter("l_outras_medidas",        adVarchar, adParamInput,1000, Tvl(p_outras_medidas))
     set l_prev_ano_1            = .CreateParameter("l_prev_ano_1",            adNumeric ,adParamInput)
     l_prev_ano_1.Precision    = 18
     l_prev_ano_1.NumericScale = 2
     l_prev_ano_1.Value        = Tvl(p_prev_ano_1)
     set l_prev_ano_2            = .CreateParameter("l_prev_ano_2",            adNumeric ,adParamInput)
     l_prev_ano_2.Precision    = 18
     l_prev_ano_2.NumericScale = 2
     l_prev_ano_2.Value        = Tvl(p_prev_ano_2)
     set l_prev_ano_3            = .CreateParameter("l_prev_ano_3",            adNumeric ,adParamInput)
     l_prev_ano_3.Precision    = 18
     l_prev_ano_3.NumericScale = 2
     l_prev_ano_3.Value        = Tvl(p_prev_ano_3)
     set l_prev_ano_4            = .CreateParameter("l_prev_ano_4",            adNumeric ,adParamInput)
     l_prev_ano_4.Precision    = 18
     l_prev_ano_4.NumericScale = 2
     l_prev_ano_4.Value        = Tvl(p_prev_ano_4)
     set l_p1                    = .CreateParameter("l_p1",                    adInteger, adParamInput,    , Tvl(p_p1))
     
     .parameters.Append         l_Operacao
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_ano
     .parameters.Append         l_cliente
     .parameters.Append         l_cd_programa
     .parameters.Append         l_cd_unidade_medida
     .parameters.Append         l_cd_periodicidade
     .parameters.Append         l_cd_base_geografica
     .parameters.Append         l_categoria_analise
     .parameters.Append         l_ordem
     .parameters.Append         l_titulo
     .parameters.Append         l_conceituacao
     .parameters.Append         l_interpretacao
     .parameters.Append         l_usos
     .parameters.Append         l_limitacoes
     .parameters.Append         l_comentarios
     .parameters.Append         l_fonte
     .parameters.Append         l_tipo
     .parameters.Append         l_formula
     .parameters.Append         l_indice_ref
     .parameters.Append         l_indice_apurado
     .parameters.Append         l_apuracao_ref
     .parameters.Append         l_apuracao_ind
     .parameters.Append         l_observacoes
     .parameters.Append         l_cumulativa
     .parameters.Append         l_quantidade
     .parameters.Append         l_exequivel
     .parameters.Append         l_situacao_atual
     .parameters.Append         l_justificativa_inex
     .parameters.Append         l_outras_medidas
     .parameters.Append         l_prev_ano_1
     .parameters.Append         l_prev_ano_2
     .parameters.Append         l_prev_ano_3
     .parameters.Append         l_prev_ano_4
     .parameters.Append         l_p1

     .CommandText               = Session("schema_is") & "SP_PutIndicador_IS"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_chave_aux"
     .parameters.Delete         "l_ano"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_cd_programa"
     .parameters.Delete         "l_cd_unidade_medida"
     .parameters.Delete         "l_cd_periodicidade"
     .parameters.Delete         "l_cd_base_geografica"
     .parameters.Delete         "l_categoria_analise"
     .parameters.Delete         "l_ordem"
     .parameters.Delete         "l_titulo"
     .parameters.Delete         "l_conceituacao"
     .parameters.Delete         "l_interpretacao"
     .parameters.Delete         "l_usos"
     .parameters.Delete         "l_limitacoes"
     .parameters.Delete         "l_comentarios"
     .parameters.Delete         "l_fonte"
     .parameters.Delete         "l_tipo"
     .parameters.Delete         "l_formula"
     .parameters.Delete         "l_indice_ref"
     .parameters.Delete         "l_indice_apurado"
     .parameters.Delete         "l_apuracao_ref"
     .parameters.Delete         "l_apuracao_ind"
     .parameters.Delete         "l_observacoes"
     .parameters.Delete         "l_cumulativa"
     .parameters.Delete         "l_quantidade"
     .parameters.Delete         "l_exequivel"
     .parameters.Delete         "l_situacao_atual"
     .parameters.Delete         "l_justificativa_inex"
     .parameters.Delete         "l_outras_medidas"
     .parameters.Delete         "l_prev_ano_1"
     .parameters.Delete         "l_prev_ano_2"
     .parameters.Delete         "l_prev_ano_3"
     .parameters.Delete         "l_prev_ano_4"
     .parameters.Delete         "l_p1"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantém a tabela de restrições do programa
REM -------------------------------------------------------------------------
Sub DML_PutRestricaoPrograma_IS (Operacao, cliente, ano, p_cd_programa, p_chave_aux, _
                                 p_cd_tipo_restricao, p_cd_tipo_inclusao, p_cd_competencia, _
                                 p_superacao, p_relatorio, p_tempo_habil, p_descricao, _
                                 p_providencia, p_observacao_controle, p_observacao_monitor)

  Dim l_Operacao, l_cliente, l_ano, l_cd_programa, l_chave_aux
  Dim l_cd_tipo_restricao, l_cd_tipo_inclusao, l_cd_competencia
  Dim l_superacao, l_relatorio, l_tempo_habil, l_descricao
  Dim l_providencia, l_observacao_controle, l_observacao_monitor
  
  Set l_Operacao                = Server.CreateObject("ADODB.Parameter")  
  Set l_cliente                 = Server.CreateObject("ADODB.Parameter") 
  Set l_ano                     = Server.CreateObject("ADODB.Parameter")
  Set l_cd_programa             = Server.CreateObject("ADODB.Parameter") 
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
     set l_cd_programa           = .CreateParameter("l_cd_programa",           adVarchar, adParamInput,   4, p_cd_programa)
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
     .parameters.Append         l_cd_programa
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

     .CommandText               = Session("schema_is") & "SP_PutRestricaoPrograma_IS"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_cliente"
     .parameters.Delete         "l_ano"
     .parameters.Delete         "l_cd_programa"
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
%>