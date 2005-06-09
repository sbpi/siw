create or replace procedure SP_PutRespTarefa_IS
   (p_chave            in number,
    p_nm_responsavel   in varchar2  default null,
    p_fn_responsavel   in varchar2  default null,
    p_em_responsavel   in varchar2  default null
   ) is
begin
   -- Atualiza a tabela de responsável pela tarefa
   Update is_tarefa set
      nm_responsavel        = p_nm_responsavel,
      fn_responsavel        = p_fn_responsavel,
      em_responsavel        = p_em_responsavel
   where sq_siw_solicitacao = p_chave;
end SP_PutRespTarefa_IS;
/

