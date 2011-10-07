create or replace procedure SP_PutSolicEntCelular
   (p_menu                in number,
    p_chave               in number,
    p_pessoa              in number,
    p_inicio              in date      default null,
    p_observacao          in varchar2  default null
   ) is
   
   w_chave_dem siw_solic_log.sq_siw_solic_log%type;
begin
   -- Recupera a chave do log
   select sq_siw_solic_log.nextval into w_chave_dem from dual;
   
   -- Atualiza a tabela de sr_solicitacao_celular
   Update sr_solicitacao_celular set inicio_real = p_inicio where sq_siw_solicitacao = p_chave;   
   
   -- Atualiza a tabela de solicitações
   Update siw_solicitacao set observacao = case when p_observacao is not null then p_observacao else null end where sq_siw_solicitacao = p_chave;

end SP_PutSolicEntCelular;
/
