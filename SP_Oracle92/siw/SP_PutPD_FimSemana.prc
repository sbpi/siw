create or replace procedure SP_PutPD_FimSemana
   (p_chave               in number,
    p_fim_semana          in varchar2  default null
   ) is
begin
  -- Atualiza a indica��o se deve ser paga di�ria no fim de semana para viagens nacionais
  update pd_missao
     set diaria_fim_semana = p_fim_semana
   where sq_siw_solicitacao = p_chave;

  -- Recalcula as di�rias da solicita��o
  sp_calculadiarias(p_chave, null, 'S'); 
end SP_PutPD_FimSemana;
/
