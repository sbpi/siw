create or replace procedure SP_PutCVHist
   (p_operacao                     in varchar2,
    p_chave                        in number,
    p_residencia_outro_pais        in varchar2,
    p_mudanca_nacionalidade        in varchar2,
    p_mudanca_nacionalidade_medida in varchar2 default null,
    p_emprego_seis_meses           in varchar2,
    p_impedimento_viagem_aerea     in varchar2,
    p_objecao_informacoes          in varchar2,
    p_prisao_envolv_justica        in varchar2,
    p_motivo_prisao                in varchar2 default null,
    p_fato_relevante_vida          in varchar2 default null,
    p_servidor_publico             in varchar2,
    p_servico_publico_inicio       in date     default null,
    p_servico_publico_fim          in date     default null,
    p_atividades_civicas           in varchar2 default null,
    p_familiar                     in varchar2
   ) is
   w_existe number(18);
   w_chave  number(18);
begin

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
   update cv_pessoa set alteracao = sysdate where sq_pessoa = p_chave;
   
   commit;
end SP_PutCVHist;
/

