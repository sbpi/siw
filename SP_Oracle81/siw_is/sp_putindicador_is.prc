create or replace procedure sp_PutIndicador_IS
   (p_operacao           in varchar2,
    p_chave              in number,
    p_chave_aux          in number    default null,
    p_ano                in number,
    p_cliente            in number,
    p_cd_programa        in varchar2,
    p_cd_unidade_medida  in number    default null,
    p_cd_periodicidade   in number    default null,
    p_cd_base_geografica in number    default null,
    p_categoria_analise  in varchar2  default null,
    p_ordem              in number,
    p_titulo             in varchar2,
    p_conceituacao       in varchar2,
    p_interpretacao      in varchar2  default null,
    p_usos               in varchar2  default null,
    p_limitacoes         in varchar2  default null,
    p_comentarios        in varchar2  default null,
    p_fonte              in varchar2  default null,
    p_tipo               in varchar2  default null,
    p_formula            in varchar2  default null,
    p_indice_ref         in number    default null,
    p_indice_apurado     in number    default null,
    p_apuracao_ref       in date      default null,
    p_apuracao_ind       in date      default null,
    p_observacoes        in varchar2  default null,
    p_cumulativa         in varchar2  default null,
    p_quantidade         in number    default null,
    p_exequivel          in varchar2  default null,
    p_situacao_atual     in varchar2  default null,
    p_justificativa_inex in varchar2  default null,
    p_outras_medidas     in varchar2  default null,
    p_prev_ano_1         in number    default null,
    p_prev_ano_2         in number    default null,
    p_prev_ano_3         in number    default null,
    p_prev_ano_4         in number    default null,
    p_restricao          in number    default null                       
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into is_indicador (sq_indicador,                sq_siw_solicitacao,   ano, 
                                cd_programa,                 is_cliente,           is_ano, 
                                is_cd_programa,              cliente,              cd_unidade_medida, 
                                cd_periodicidade,            cd_base_geografica,   categoria_analise, 
                                ordem,                       titulo,               conceituacao, 
                                interpretacao,               usos,                 limitacoes, 
                                comentarios,                 fonte,                formula, 
                                tipo,                        valor_referencia,     apuracao_referencia,
                                valor_apurado,               apuracao_indice,
                                observacao,                  cumulativa,           quantidade,
                                exequivel,                   situacao_atual,       justificativa_inexequivel,
                                outras_medidas,              previsao_ano_1,       previsao_ano_2,
                                previsao_ano_3,              previsao_ano_4)
                               (select sq_indicador.nextval, p_chave,              p_ano,  
                                p_cd_programa,               p_cliente,            p_ano, 
                                p_cd_programa,               p_cliente,            p_cd_unidade_medida, 
                                p_cd_periodicidade,          p_cd_base_geografica, p_categoria_analise,
                                p_ordem,                     p_titulo,             p_conceituacao, 
                                p_interpretacao,             p_usos,               p_limitacoes, 
                                p_comentarios,               p_fonte,              p_formula, 
                                p_tipo,                      p_indice_ref,         p_apuracao_ref, 
                                p_indice_apurado,            p_apuracao_ind,
                                p_observacoes,               p_cumulativa,         p_quantidade,
                                p_exequivel,                 p_situacao_atual,     p_justificativa_inex,
                                p_outras_medidas,            p_prev_ano_1,         p_prev_ano_2,
                                p_prev_ano_3,                p_prev_ano_4          from dual);
   Elsif p_operacao = 'A' Then
      If p_restricao <> 2 and p_restricao <> 3 and p_restricao <> 5 Then
         -- Altera registro
         update is_indicador set 
                cd_unidade_medida         = p_cd_unidade_medida,
                cd_periodicidade          = p_cd_periodicidade,
                cd_base_geografica        = p_cd_base_geografica,
                categoria_analise         = p_categoria_analise,
                ordem                     = p_ordem,
                titulo                    = p_titulo,
                conceituacao              = p_conceituacao,
                interpretacao             = p_interpretacao,
                usos                      = p_usos,
                limitacoes                = p_limitacoes,
                comentarios               = p_comentarios,
                fonte                     = p_fonte,
                formula                   = p_formula,
                tipo                      = p_tipo,
                valor_referencia          = p_indice_ref,
                apuracao_referencia       = p_apuracao_ref,
                observacao                = p_observacoes,
                cumulativa                = p_cumulativa,
                quantidade                = p_quantidade,
                exequivel                 = p_exequivel,
                situacao_atual            = p_situacao_atual,
                justificativa_inexequivel = p_justificativa_inex,
                outras_medidas            = p_outras_medidas,
                previsao_ano_1            = p_prev_ano_1,
                previsao_ano_2            = p_prev_ano_2,
                previsao_ano_3            = p_prev_ano_3,
                previsao_ano_4            = p_prev_ano_4
          where sq_indicador = p_chave_aux;
   Else
      update is_indicador set 
                valor_apurado             = p_indice_apurado,
                apuracao_indice           = p_apuracao_ind,
                exequivel                 = p_exequivel,
                situacao_atual            = p_situacao_atual,
                justificativa_inexequivel = p_justificativa_inex,
                outras_medidas            = p_outras_medidas
          where sq_indicador = p_chave_aux;
   End If;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete is_indicador
       where sq_indicador = p_chave_aux and cd_indicador is null;
   End If;
end sp_PutIndicador_IS;
/

