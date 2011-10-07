create or replace procedure SP_PutSolicInfCelular
   (p_menu                in number,
    p_chave               in number,
    p_pessoa              in number,
    p_sq_celular          in number    default null,
    p_acessorios          in varchar2  default null
   ) is
   
   w_chave_dem     number(18) := null;
begin
   -- Recupera a chave do log
   select sq_siw_solic_log.nextval into w_chave_dem from dual;
   
   -- Atualiza a tabela de sr_solicitacao_celular
   Update sr_solicitacao_celular set sq_celular = p_sq_celular, acessorios_entregues = p_acessorios where sq_siw_solicitacao = p_chave;   

end SP_PutSolicInfCelular;
/
