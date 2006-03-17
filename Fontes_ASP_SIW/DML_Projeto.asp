<%
REM =========================================================================
REM Mantщm a tabela principal de Projetos
REM -------------------------------------------------------------------------
Sub DML_PutProjetoGeral(Operacao, p_chave, p_menu, p_unidade, p_solicitante, p_proponente, _
    p_cadastrador, p_executor, p_sqcc, p_descricao, p_justificativa, p_inicio, p_fim, p_valor, _
    p_data_hora, p_unid_resp, p_assunto, p_prioridade, p_aviso, p_dias, p_cidade, p_palavra_chave, _
    p_vincula_contrato, p_vincula_viagem, p_sq_acao_ppa, p_sq_orprioridade, p_selecionada_mpog, _
    p_selecionada_relev, p_sq_tipo_pessoa, p_chave_nova, p_copia)
    
  Dim l_Operacao, l_Chave, l_menu, l_unidade, l_solicitante, l_proponente
  Dim l_cadastrador, l_executor, l_sqcc, l_descricao, l_justificativa, l_inicio, l_fim, l_valor
  Dim l_data_hora, l_unid_resp, l_assunto, l_prioridade, l_aviso, l_dias, l_cidade, l_palavra_chave
  Dim l_vincula_contrato, l_vincula_viagem, l_chave_nova, l_copia
  Dim l_sq_acao_ppa, l_sq_orprioridade, l_selecionada_mpog, l_selecionada_relev, l_sq_tipo_pessoa
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_copia               = Server.CreateObject("ADODB.Parameter") 
  Set l_menu                = Server.CreateObject("ADODB.Parameter") 
  Set l_unidade             = Server.CreateObject("ADODB.Parameter") 
  Set l_solicitante         = Server.CreateObject("ADODB.Parameter") 
  Set l_proponente          = Server.CreateObject("ADODB.Parameter") 
  Set l_cadastrador         = Server.CreateObject("ADODB.Parameter") 
  Set l_executor            = Server.CreateObject("ADODB.Parameter") 
  Set l_sqcc                = Server.CreateObject("ADODB.Parameter")
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
  Set l_vincula_contrato    = Server.CreateObject("ADODB.Parameter") 
  Set l_vincula_viagem      = Server.CreateObject("ADODB.Parameter") 
  Set l_chave_nova          = Server.CreateObject("ADODB.Parameter") 
  Set l_sq_acao_ppa         = Server.CreateObject("ADODB.Parameter")
  Set l_sq_orprioridade     = Server.CreateObject("ADODB.Parameter")
  Set l_selecionada_mpog    = Server.CreateObject("ADODB.Parameter")
  Set l_selecionada_relev   = Server.CreateObject("ADODB.Parameter")
  Set l_sq_tipo_pessoa      = Server.CreateObject("ADODB.Parameter")

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
     set l_sqcc                 = .CreateParameter("l_sqcc",            adInteger, adParamInput,    , Tvl(p_sqcc))
     set l_descricao            = .CreateParameter("l_descricao",       adVarchar, adParamInput,2000, Tvl(p_descricao))
     set l_justificativa        = .CreateParameter("l_justificativa",   adVarchar, adParamInput,2000, Tvl(p_justificativa))
     set l_inicio               = .CreateParameter("l_inicio",          adDate,    adParamInput,    , Tvl(p_inicio))
     set l_fim                  = .CreateParameter("l_fim",             adDate,    adParamInput,    , Tvl(p_fim))
     set l_valor                = .CreateParameter("l_valor",           adNumeric ,adParamInput)',    , Tvl(p_valor))
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
     set l_vincula_contrato     = .CreateParameter("l_vincula_contrato",adVarchar, adParamInput,   1, Tvl(p_vincula_contrato))
     set l_vincula_viagem       = .CreateParameter("l_vincula_viagem",  adVarchar, adParamInput,   1, Tvl(p_vincula_viagem))
     set l_sq_acao_ppa          = .CreateParameter("l_sq_acao_ppa",     adInteger, adParamInput,    , Tvl(p_sq_acao_ppa))
     set l_sq_orprioridade      = .CreateParameter("l_sq_orprioridade", adInteger, adParamInput,    , Tvl(p_sq_orprioridade))
     set l_selecionada_mpog     = .CreateParameter("l_selecionada_mpog",adVarchar, adParamInput,   1, Tvl(p_selecionada_mpog))
     set l_selecionada_relev    = .CreateParameter("l_selecionada_relev",adVarchar,adParamInput,   1, Tvl(p_selecionada_relev))
     set l_sq_tipo_pessoa       = .CreateParameter("l_sq_tipo_pessoa",  adInteger, adParamInput,    , Tvl(p_sq_tipo_pessoa))
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
     .parameters.Append         l_sqcc
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
     .parameters.Append         l_vincula_contrato
     .parameters.Append         l_vincula_viagem
     .parameters.Append         l_sq_acao_ppa
     .parameters.Append         l_sq_orprioridade
     .parameters.Append         l_selecionada_mpog
     .parameters.Append         l_selecionada_relev
     .parameters.Append         l_sq_tipo_pessoa
     .parameters.Append         l_chave_nova

     .CommandText               = Session("schema") & "SP_PutProjetoGeral"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     p_chave_nova = l_chave_nova.Value

     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_copia"
     .parameters.Delete         "l_menu"
     .parameters.Delete         "l_unidade"
     .parameters.Delete         "l_solicitante"
     .parameters.Delete         "l_proponente"
     .parameters.Delete         "l_cadastrador"
     .parameters.Delete         "l_executor"
     .parameters.Delete         "l_sqcc"
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
     .parameters.Delete         "l_vincula_contrato"
     .parameters.Delete         "l_vincula_viagem"
     .parameters.Delete         "l_sq_acao_ppa"
     .parameters.Delete         "l_sq_orprioridade"
     .parameters.Delete         "l_selecionada_mpog"
     .parameters.Delete         "l_selecionada_relev"
     .parameters.Delete         "l_sq_tipo_pessoa"
     .parameters.Delete         "l_chave_nova"
  end with
End Sub

REM =========================================================================
REM Mantщm a tabela OR_ACAO do projeto
REM -------------------------------------------------------------------------
Sub DML_PutProjetoInfo(p_chave, p_descricao, p_justificativa, p_problema, _
                       p_ds_acao, p_publico_alvo, p_estrategia, p_indicadores, p_objetivo)
  Dim l_Chave, l_descricao, l_justificativa
  Dim l_problema, l_ds_acao, l_publico_alvo, l_estrategia, l_indicadores, l_objetivo
  
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao           = Server.CreateObject("ADODB.Parameter") 
  Set l_justificativa       = Server.CreateObject("ADODB.Parameter")  
  Set l_problema            = Server.CreateObject("ADODB.Parameter") 
  Set l_ds_acao             = Server.CreateObject("ADODB.Parameter") 
  Set l_publico_alvo        = Server.CreateObject("ADODB.Parameter") 
  Set l_estrategia          = Server.CreateObject("ADODB.Parameter") 
  Set l_indicadores         = Server.CreateObject("ADODB.Parameter")
  Set l_objetivo            = Server.CreateObject("ADODB.Parameter")  
   
  with sp
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,    , Tvl(p_chave))
     set l_descricao            = .CreateParameter("l_descricao",       adVarchar, adParamInput,2000, Tvl(p_descricao))
     set l_justificativa        = .CreateParameter("l_justificativa",   adVarchar, adParamInput,2000, Tvl(p_justificativa))
     set l_problema             = .CreateParameter("l_problema",        adVarchar, adParamInput,2000, Tvl(p_problema))
     set l_ds_acao              = .CreateParameter("l_ds_acao",         adVarchar, adParamInput,2000, Tvl(p_ds_acao))
     set l_publico_alvo         = .CreateParameter("l_publico_alvo",    adVarchar, adParamInput,2000, Tvl(p_publico_alvo))
     set l_estrategia           = .CreateParameter("l_estrategia",      adVarchar, adParamInput,2000, Tvl(p_estrategia))
     set l_indicadores          = .CreateParameter("l_indicadores",     adVarchar, adParamInput,2000, Tvl(p_indicadores))
     set l_objetivo             = .CreateParameter("l_objetivo",        adVarchar, adParamInput,2000, Tvl(p_objetivo))
     
     .parameters.Append         l_chave
     .parameters.Append         l_descricao
     .parameters.Append         l_justificativa
     .parameters.Append         l_problema
     .parameters.Append         l_ds_acao
     .parameters.Append         l_publico_alvo
     .parameters.Append         l_estrategia
     .parameters.Append         l_indicadores
     .parameters.Append         l_objetivo

     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_PutProjetoInfo"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_descricao"
     .parameters.Delete         "l_justificativa"
     .parameters.Delete         "l_problema"
     .parameters.Delete         "l_ds_acao"
     .parameters.Delete         "l_publico_alvo"
     .parameters.Delete         "l_estrategia"
     .parameters.Delete         "l_indicadores"
     .parameters.Delete         "l_objetivo"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------


REM =========================================================================
REM Mantщm a tabela de etapas de um projeto
REM -------------------------------------------------------------------------
Sub DML_PutProjetoEtapa(Operacao, p_chave, p_chave_aux, p_chave_pai, p_titulo, _
       p_descricao, p_ordem, p_inicio, p_fim, p_perc_conclusao, p_orcamento, _
       p_sq_pessoa, p_sq_unidade, p_vincula_atividade, p_usuario, p_programada, p_cumulativa, p_quantidade, p_unidade_medida)
  Dim l_Operacao, l_chave, l_chave_aux, l_chave_pai, l_titulo, l_descricao, l_ordem, l_usuario
  Dim l_inicio, l_fim, l_perc_conclusao, l_orcamento, l_sq_pessoa, l_sq_unidade, l_vincula_atividade
  Dim l_programada, l_cumulativa, l_quantidade, l_unidade_medida
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_chave_aux           = Server.CreateObject("ADODB.Parameter") 
  Set l_chave_pai           = Server.CreateObject("ADODB.Parameter") 
  Set l_titulo              = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao           = Server.CreateObject("ADODB.Parameter") 
  Set l_ordem               = Server.CreateObject("ADODB.Parameter") 
  Set l_inicio              = Server.CreateObject("ADODB.Parameter") 
  Set l_fim                 = Server.CreateObject("ADODB.Parameter")
  Set l_perc_conclusao      = Server.CreateObject("ADODB.Parameter")
  Set l_orcamento           = Server.CreateObject("ADODB.Parameter")
  Set l_sq_pessoa           = Server.CreateObject("ADODB.Parameter")
  Set l_sq_unidade          = Server.CreateObject("ADODB.Parameter")
  Set l_vincula_atividade   = Server.CreateObject("ADODB.Parameter")
  Set l_usuario             = Server.CreateObject("ADODB.Parameter")
  Set l_programada          = Server.CreateObject("ADODB.Parameter")
  Set l_cumulativa          = Server.CreateObject("ADODB.Parameter")
  Set l_quantidade          = Server.CreateObject("ADODB.Parameter")
  Set l_unidade_medida      = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",            adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,    , Tvl(p_chave))
     set l_chave_aux            = .CreateParameter("l_chave_aux",           adInteger, adParamInput,    , Tvl(p_chave_aux))
     set l_chave_pai            = .CreateParameter("l_chave_pai",           adInteger, adParamInput,    , Tvl(p_chave_pai))
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
     set l_sq_pessoa            = .CreateParameter("l_sq_pessoa",           adInteger, adParamInput,    , p_sq_pessoa)
     set l_sq_unidade           = .CreateParameter("l_sq_unidade",          adInteger, adParamInput,    , p_sq_unidade)
     set l_vincula_atividade    = .CreateParameter("l_vincula_atividade",   adVarchar, adParamInput,   1, p_vincula_atividade)
     set l_usuario              = .CreateParameter("l_usuario",             adInteger, adParamInput,    , p_usuario)
     set l_programada           = .CreateParameter("l_programada",          adVarchar, adParamInput,   1, p_programada)
     set l_cumulativa           = .CreateParameter("l_cumulativa",          adVarchar, adParamInput,   1, p_cumulativa)
     set l_quantidade           = .CreateParameter("l_quantidade",          adInteger, adParamInput,    , p_quantidade)
     set l_unidade_medida       = .CreateParameter("l_unidade_medida",      adVarchar, adParamInput,  30, p_unidade_medida)
     .parameters.Append         l_Operacao
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_chave_pai
     .parameters.Append         l_titulo
     .parameters.Append         l_descricao
     .parameters.Append         l_ordem
     .parameters.Append         l_inicio
     .parameters.Append         l_fim
     .parameters.Append         l_perc_conclusao
     .parameters.Append         l_orcamento
     .parameters.Append         l_sq_pessoa
     .parameters.Append         l_sq_unidade
     .parameters.Append         l_vincula_atividade
     .parameters.Append         l_usuario
     .parameters.Append         l_programada
     .parameters.Append         l_cumulativa
     .parameters.Append         l_quantidade
     .parameters.Append         l_unidade_medida
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_PutProjetoEtapa"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_chave_aux"
     .parameters.Delete         "l_chave_pai"
     .parameters.Delete         "l_titulo"
     .parameters.Delete         "l_descricao"
     .parameters.Delete         "l_ordem"
     .parameters.Delete         "l_inicio"
     .parameters.Delete         "l_fim"
     .parameters.Delete         "l_perc_conclusao"
     .parameters.Delete         "l_orcamento"
     .parameters.Delete         "l_sq_pessoa"
     .parameters.Delete         "l_sq_unidade"
     .parameters.Delete         "l_vincula_atividade"
     .parameters.Delete         "l_usuario"
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
REM Atualiza uma etapa de projeto
REM -------------------------------------------------------------------------
Sub DML_PutAtualizaEtapa(p_chave, p_chave_aux, p_usuario, p_perc_conclusao, p_situacao_atual, _
                         p_exequivel, p_justificativa_inex, p_outras_medidas)
  Dim l_chave, l_chave_aux, l_usuario, l_perc_conclusao, l_situacao_atual, l_exequivel
  Dim l_justificativa_inex, l_outras_medidas
  
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_chave_aux           = Server.CreateObject("ADODB.Parameter") 
  Set l_usuario             = Server.CreateObject("ADODB.Parameter") 
  Set l_perc_conclusao      = Server.CreateObject("ADODB.Parameter")
  Set l_situacao_atual      = Server.CreateObject("ADODB.Parameter")
  Set l_exequivel           = Server.CreateObject("ADODB.Parameter")
  Set l_justificativa_inex  = Server.CreateObject("ADODB.Parameter")
  Set l_outras_medidas      = Server.CreateObject("ADODB.Parameter")
  with sp
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,    , p_chave)
     set l_chave_aux            = .CreateParameter("l_chave_aux",           adInteger, adParamInput,    , p_chave_aux)
     set l_usuario              = .CreateParameter("l_usuario",             adInteger, adParamInput,    , p_usuario)
     set l_perc_conclusao       = .CreateParameter("l_perc_conclusao",      adDouble, adParamInput,     , p_perc_conclusao)
     set l_situacao_atual       = .CreateParameter("l_situacao_atual",      adVarchar, adParamInput,4000, Tvl(p_situacao_atual))
     set l_exequivel            = .CreateParameter("l_exequivel",           adVarchar, adParamInput,   1, Tvl(p_exequivel))
     set l_justificativa_inex   = .CreateParameter("l_justificativa_inex",  adVarchar, adParamInput,4000, Tvl(p_justificativa_inex))
     set l_outras_medidas       = .CreateParameter("l_outras_medidas",      adVarchar, adParamInput,4000, Tvl(p_outras_medidas))
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_usuario
     .parameters.Append         l_perc_conclusao
     .parameters.Append         l_situacao_atual
     .parameters.Append         l_exequivel
     .parameters.Append         l_justificativa_inex
     .parameters.Append         l_outras_medidas
     .CommandText               = Session("schema") & "SP_PutAtualizaEtapa"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_chave_aux"
     .parameters.Delete         "l_usuario"
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
REM Mantщm a tabela de etapas de um projeto
REM -------------------------------------------------------------------------
Sub DML_PutEtapaMensal(Operacao, p_chave, p_quantitativo, p_referencia)
  Dim l_Operacao, l_chave, l_quantitativo, l_referencia
    
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter") 
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_quantitativo        = Server.CreateObject("ADODB.Parameter")
  Set l_referencia          = Server.CreateObject("ADODB.Parameter")
  
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",            adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",               adInteger, adParamInput,    , Tvl(p_chave))
     set l_quantitativo         = .CreateParameter("l_quantitativo",        adInteger, adParamInput,    , Tvl(p_quantitativo))
     set l_referencia           = .CreateParameter("l_referencia",          adDate,    adParamInput,    , Tvl(p_referencia))
     .parameters.Append         l_Operacao
     .parameters.Append         l_chave
     .parameters.Append         l_quantitativo
     .parameters.Append         l_referencia
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = TRUE End If
     .CommandText               = Session("schema") & "SP_PutEtapaMensal"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     If Session("dbms") = 1 or Session("dbms") = 3 Then .Properties("PLSQLRSet") = FALSE End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_quantitativo"
     .parameters.Delete         "l_referencia"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantщm a tabela de interessados em um Projeto
REM -------------------------------------------------------------------------
Sub DML_PutProjetoRec(Operacao, p_chave, p_chave_aux, p_nome, p_tipo, p_descricao, p_finalidade)
  Dim l_Operacao, l_chave, l_chave_aux, l_nome, l_tipo, l_descricao, l_finalidade
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_chave_aux           = Server.CreateObject("ADODB.Parameter") 
  Set l_nome                = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo                = Server.CreateObject("ADODB.Parameter") 
  Set l_descricao           = Server.CreateObject("ADODB.Parameter") 
  Set l_finalidade          = Server.CreateObject("ADODB.Parameter") 
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",        adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,    , p_chave)
     set l_chave_aux            = .CreateParameter("l_chave_aux",       adInteger, adParamInput,    , Tvl(p_chave_aux))
     set l_nome                 = .CreateParameter("l_nome",            adVarchar, adParamInput, 100, Tvl(p_nome))
     set l_tipo                 = .CreateParameter("l_tipo",            adInteger, adParamInput,    , Tvl(p_tipo))
     set l_descricao            = .CreateParameter("l_descricao",       adVarchar, adParamInput,2000, Tvl(p_descricao))
     set l_finalidade           = .CreateParameter("l_finalidade",      adVarchar, adParamInput,2000, Tvl(p_finalidade))
     .parameters.Append         l_Operacao
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_nome
     .parameters.Append         l_tipo
     .parameters.Append         l_descricao
     .parameters.Append         l_finalidade
     .CommandText               = Session("schema") & "SP_PutProjetoRec"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_chave_aux"
     .parameters.Delete         "l_nome"
     .parameters.Delete         "l_tipo"
     .parameters.Delete         "l_descricao"
     .parameters.Delete         "l_finalidade"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantщm a tabela de recursos de uma etapa
REM -------------------------------------------------------------------------
Sub DML_PutSolicEtpRec(Operacao, p_chave, p_chave_aux)
  Dim l_Operacao, l_chave, l_chave_aux
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_chave_aux           = Server.CreateObject("ADODB.Parameter") 
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",        adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,    , Tvl(p_chave))
     set l_chave_aux            = .CreateParameter("l_chave_aux",       adInteger, adParamInput,    , p_chave_aux)
     .parameters.Append         l_Operacao
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .CommandText               = Session("schema") & "SP_PutSolicEtpRec"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_chave_aux"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantщm a tabela de interessados em um Projeto
REM -------------------------------------------------------------------------
Sub DML_PutProjetoInter(Operacao, p_chave, p_chave_aux, p_tipo_visao, p_envia_email)
  Dim l_Operacao, l_chave, l_chave_aux, l_tipo_visao, l_envia_email
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_chave_aux           = Server.CreateObject("ADODB.Parameter") 
  Set l_tipo_visao          = Server.CreateObject("ADODB.Parameter") 
  Set l_envia_email         = Server.CreateObject("ADODB.Parameter") 
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",        adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,    , Tvl(p_chave))
     set l_chave_aux            = .CreateParameter("l_chave_aux",       adInteger, adParamInput,    , p_chave_aux)
     set l_tipo_visao           = .CreateParameter("l_tipo_visao",      adInteger, adParamInput,    , p_tipo_visao)
     set l_envia_email          = .CreateParameter("l_envia_email",     adVarchar, adParamInput,   1, p_envia_email)
     .parameters.Append         l_Operacao
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_tipo_visao
     .parameters.Append         l_envia_email
     .CommandText               = Session("schema") & "SP_PutProjetoInter"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_chave_aux"
     .parameters.Delete         "l_tipo_visao"
     .parameters.Delete         "l_envia_email"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantщm a tabela de сreas envolvidas na execuчуo de um Projeto
REM -------------------------------------------------------------------------
Sub DML_PutProjetoAreas(Operacao, p_chave, p_chave_aux, p_papel)
  Dim l_Operacao, l_chave, l_chave_aux, l_papel
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_chave_aux           = Server.CreateObject("ADODB.Parameter") 
  Set l_papel               = Server.CreateObject("ADODB.Parameter") 
  with sp
     set l_Operacao             = .CreateParameter("l_operacao",        adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,    , Tvl(p_chave))
     set l_chave_aux            = .CreateParameter("l_chave_aux",       adInteger, adParamInput,    , p_chave_aux)
     set l_papel                = .CreateParameter("l_papel",           adVarchar, adParamInput,2000, p_papel)
     .parameters.Append         l_Operacao
     .parameters.Append         l_chave
     .parameters.Append         l_chave_aux
     .parameters.Append         l_papel
     .CommandText               = Session("schema") & "SP_PutProjetoAreas"
     On Error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_chave_aux"
     .parameters.Delete         "l_papel"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Tramita o Projeto
REM -------------------------------------------------------------------------
Sub DML_PutProjetoEnvio(p_menu, p_chave, p_pessoa, p_tramite, p_novo_tramite, p_devolucao, p_observacao, p_destinatario, p_despacho, _
                        p_caminho, p_tamanho, p_tipo, p_nome)
  Dim l_Operacao, l_menu, l_chave, l_pessoa, l_tramite, l_novo_tramite, l_devolucao, l_observacao, l_destinatario, l_despacho
  Dim l_caminho, l_tamanho, l_tipo, l_nome
  
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
  Set l_nome                = Server.CreateObject("ADODB.Parameter") 
  with sp
     set l_menu                 = .CreateParameter("l_menu",            adInteger, adParamInput,    , p_menu)
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,    , p_chave)
     set l_pessoa               = .CreateParameter("l_pessoa",          adInteger, adParamInput,    , p_pessoa)
     set l_tramite              = .CreateParameter("l_tramite",         adInteger, adParamInput,    , p_tramite)
     set l_novo_tramite         = .CreateParameter("l_novo_tramite",    adInteger, adParamInput,    , p_novo_tramite)
     set l_devolucao            = .CreateParameter("l_devolucao",       adVarchar, adParamInput,   1, p_devolucao)
     set l_observacao           = .CreateParameter("l_observacao",      adVarchar, adParamInput,2000, p_observacao)
     set l_destinatario         = .CreateParameter("l_destinatario",    adInteger, adParamInput,    , p_destinatario)
     set l_despacho             = .CreateParameter("l_despacho",        adVarchar, adParamInput,2000, p_despacho)
     set l_caminho              = .CreateParameter("l_caminho",         adVarchar, adParamInput, 255, Tvl(p_caminho))
     set l_tamanho              = .CreateParameter("l_tamanho",         adInteger, adParamInput,    , Tvl(p_tamanho))
     set l_tipo                 = .CreateParameter("l_tipo",            adVarchar, adParamInput,  60, Tvl(p_tipo))
     set l_nome                 = .CreateParameter("l_nome",            adVarchar, adParamInput, 255, Tvl(p_nome))
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
     .parameters.Append         l_nome
     .CommandText               = Session("schema") & "SP_PutProjetoEnvio"
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
     .parameters.Delete         "l_nome"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Conclui a Projeto
REM -------------------------------------------------------------------------
Sub DML_PutProjetoConc(p_menu, p_chave, p_pessoa, p_tramite, p_inicio_real, p_fim_real, p_nota_conclusao, p_custo_real)
  Dim l_menu, l_chave, l_pessoa, l_tramite
  Dim l_inicio_real, l_fim_real, l_nota_conclusao, l_custo_real
  
  Set l_menu                = Server.CreateObject("ADODB.Parameter") 
  Set l_chave               = Server.CreateObject("ADODB.Parameter") 
  Set l_pessoa              = Server.CreateObject("ADODB.Parameter") 
  Set l_tramite             = Server.CreateObject("ADODB.Parameter") 
  Set l_inicio_real         = Server.CreateObject("ADODB.Parameter") 
  Set l_fim_real            = Server.CreateObject("ADODB.Parameter") 
  Set l_nota_conclusao      = Server.CreateObject("ADODB.Parameter") 
  Set l_custo_real          = Server.CreateObject("ADODB.Parameter") 
  with sp
     set l_menu                 = .CreateParameter("l_menu",            adInteger, adParamInput,    , p_menu)
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,    , p_chave)
     set l_pessoa               = .CreateParameter("l_pessoa",          adInteger, adParamInput,    , p_pessoa)
     set l_tramite              = .CreateParameter("l_tramite",         adInteger, adParamInput,    , p_tramite)
     set l_inicio_real          = .CreateParameter("l_inicio_real",     adDate,    adParamInput,    , Tvl(p_inicio_real))
     set l_fim_real             = .CreateParameter("l_fim_real",        adDate,    adParamInput,    , Tvl(p_fim_real))
     set l_nota_conclusao       = .CreateParameter("l_nota_conclusao",  adVarchar, adParamInput,2000, Tvl(p_nota_conclusao))
     set l_custo_real           = .CreateParameter("l_custo_real",      adNumeric ,adParamInput)
     l_custo_real.Precision    = 18
     l_custo_real.NumericScale = 2
     l_custo_real.Value        = Tvl(p_custo_real)
     .parameters.Append         l_menu
     .parameters.Append         l_chave
     .parameters.Append         l_pessoa
     .parameters.Append         l_tramite
     .parameters.Append         l_inicio_real
     .parameters.Append         l_fim_real
     .parameters.Append         l_nota_conclusao
     .parameters.Append         l_custo_real
     .CommandText               = Session("schema") & "SP_PutProjetoConc"
     On Error Resume Next
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
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantщm a tabela de сreas envolvidas na execuчуo de um Projeto
REM -------------------------------------------------------------------------
Sub DML_PutProjetoOutras(Operacao, p_chave, p_sq_orprioridade)
  Dim l_Operacao, l_chave, l_sq_orprioridade
  
  Set l_Operacao            = Server.CreateObject("ADODB.Parameter")
  Set l_chave               = Server.CreateObject("ADODB.Parameter")
  Set l_sq_orprioridade     = Server.CreateObject("ADODB.Parameter") 
  with sp
     set l_Operacao             = .CreateParameter("l_Operacao",        adVarchar, adParamInput, 1, Operacao)
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,  ,tvl(p_chave))
     set l_sq_orprioridade      = .CreateParameter("l_sq_orprioridade", adInteger, adParamInput,  ,tvl(p_sq_orprioridade))
     .parameters.Append         l_Operacao
     .parameters.Append         l_chave
     .parameters.Append         l_sq_orprioridade
     .CommandText               = Session("schema") & "SP_PutProjetoOutras"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_sq_orprioridade"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------

REM =========================================================================
REM Mantщm a tabela de financiamento da aчуo
REM -------------------------------------------------------------------------
Sub DML_PutProjetoFinancAcao(Operacao, p_chave, p_sq_acao_ppa, p_obs_financ)
  Dim l_Operacao, l_chave, l_sq_acao_ppa, l_obs_financ
  
  Set l_Operacao     = Server.CreateObject("ADODB.Parameter")
  Set l_chave        = Server.CreateObject("ADODB.Parameter")
  Set l_sq_acao_ppa  = Server.CreateObject("ADODB.Parameter")
  Set l_obs_financ   = Server.CreateObject("ADODB.Parameter")  
  with sp
     set l_Operacao             = .CreateParameter("l_Operacao",        adVarchar, adParamInput,   1, Operacao)
     set l_chave                = .CreateParameter("l_chave",           adInteger, adParamInput,    ,tvl(p_chave))
     set l_sq_acao_ppa          = .CreateParameter("l_sq_acao_ppa",     adInteger, adParamInput,    ,tvl(p_sq_acao_ppa))
     set l_obs_financ           = .CreateParameter("l_obs_financ",      adVarchar, adParamInput,2000,tvl(p_obs_financ))
     .parameters.Append         l_Operacao
     .parameters.Append         l_chave
     .parameters.Append         l_sq_acao_ppa
     .parameters.Append         l_obs_financ
     .CommandText               = Session("schema") & "SP_PutProjetoFinancAcao"
     On error Resume Next
     .Execute
     If Err.Description > "" Then 
        TrataErro
     End If
     .parameters.Delete         "l_Operacao"
     .parameters.Delete         "l_chave"
     .parameters.Delete         "l_sq_acao_ppa"
     .parameters.Delete         "l_obs_financ"
  end with
End Sub
REM =========================================================================
REM Final da rotina
REM -------------------------------------------------------------------------
%>