create or replace procedure SP_PutPD_Dados
   (p_chave               in number,
    p_fim_semana          in varchar2  default null,
    p_complemento_qtd     in  number   default null,
    p_complemento_base    in  number   default null,
    p_complemento_valor   in  number   default null
   ) is
begin
  -- Atualiza a indicação se deve ser paga diária no fim de semana para viagens nacionais
  update pd_missao
     set diaria_fim_semana = p_fim_semana,
         complemento_qtd   = p_complemento_qtd,
         complemento_base  = p_complemento_base,
         complemento_valor = p_complemento_valor
   where sq_siw_solicitacao = p_chave;

  -- Recalcula as diárias da solicitação
  sp_calculadiarias(p_chave, null, 'S'); 
end SP_PutPD_Dados;
/
