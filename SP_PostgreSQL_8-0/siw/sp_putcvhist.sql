create or replace FUNCTION SP_PutCVHist
   (p_operacao                     varchar,
    p_chave                        numeric,
    p_residencia_outro_pais        varchar,
    p_mudanca_nacionalidade        varchar,
    p_mudanca_nacionalidade_medida varchar,
    p_emprego_seis_meses           varchar,
    p_impedimento_viagem_aerea     varchar,
    p_objecao_informacoes          varchar,
    p_prisao_envolv_justica        varchar,
    p_motivo_prisao                varchar,
    p_fato_relevante_vida          varchar,
    p_servidor_publico             varchar,
    p_servico_publico_inicio       date,
    p_servico_publico_fim          date,
    p_atividades_civicas           varchar,
    p_familiar                     varchar
   ) RETURNS VOID AS $$
DECLARE
   w_existe numeric(18);
   w_chave  numeric(18);
BEGIN

   -- Verifica se existe registro de histórico para a pessoa
   select count(*) into w_existe from cv_pessoa_hist where sq_pessoa = p_chave;
   
   -- Insere ou altera, dependendo do resultado
   If w_existe = 0 Then
      insert into cv_pessoa_hist
        (sq_pessoa,                       residencia_outro_pais,   mudanca_nacionalidade, 
         mudanca_nacionalidade_medida,    emprego_seis_meses,      impedimento_viagem_aerea, 
         objecao_informacoes,             prisao_envolv_justica,   motivo_prisao, 
         fato_relevante_vida,             servidor_publico,        servico_publico_inicio, 
         servico_publico_fim,             atividades_civicas,      familiar)
      values
        (p_chave,                         p_residencia_outro_pais, p_mudanca_nacionalidade, 
         p_mudanca_nacionalidade_medida,  p_emprego_seis_meses,    p_impedimento_viagem_aerea, 
         p_objecao_informacoes,           p_prisao_envolv_justica, p_motivo_prisao, 
         p_fato_relevante_vida,           p_servidor_publico,      p_servico_publico_inicio, 
         p_servico_publico_fim,           p_atividades_civicas,    p_familiar);
   Else
      update cv_pessoa_hist
         set residencia_outro_pais        = p_residencia_outro_pais,
             mudanca_nacionalidade        = p_mudanca_nacionalidade,
             mudanca_nacionalidade_medida = p_mudanca_nacionalidade_medida,
             emprego_seis_meses           = p_emprego_seis_meses,
             impedimento_viagem_aerea     = p_impedimento_viagem_aerea,
             objecao_informacoes          = p_objecao_informacoes,
             prisao_envolv_justica        = p_prisao_envolv_justica,
             motivo_prisao                = p_motivo_prisao,
             fato_relevante_vida          = p_fato_relevante_vida,
             servidor_publico             = p_servidor_publico,
             servico_publico_inicio       = p_servico_publico_inicio,
             servico_publico_fim          = p_servico_publico_fim,
             atividades_civicas           = p_atividades_civicas,
             familiar                     = p_familiar
       where sq_pessoa = p_chave;
   End If;

   -- Atualiza a data de última alteração nas informações curriculares
   update cv_pessoa set alteracao = now() where sq_pessoa = p_chave;
   
   commit;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;