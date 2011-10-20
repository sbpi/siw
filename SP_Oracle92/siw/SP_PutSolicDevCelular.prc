create or replace procedure SP_PutSolicDevCelular
   (p_menu                in number,
    p_chave               in number,
    p_pessoa              in number,
    p_fim                 in date      default null,
    p_pendencia           in varchar2  default null,
    p_acessorios          in varchar2  default null,
    p_bloqueio            in varchar2  default null,
    p_observacao          in varchar2  default null
   ) is
   
   w_chave_cel sr_celular.sq_celular%type;
begin
   -- Recupera a chave do log
   select sq_celular into w_chave_cel from sr_solicitacao_celular where sq_siw_solicitacao = p_chave;
   
   -- Atualiza a tabela de sr_solicitacao_celular
   Update sr_solicitacao_celular 
      set fim_real             = p_fim,
          pendencia            = p_pendencia,
          acessorios_pendentes = p_acessorios
   where sq_siw_solicitacao = p_chave;   
   
   If p_bloqueio = 'S' Then
      -- Registra o bloqueio do celular
      update sr_celular a set a.bloqueado = 'S', a.inicio_bloqueio = p_fim, a.motivo_bloqueio = p_observacao where sq_celular = w_chave_cel;
   Else
      -- Atualiza a tabela de solicitações
      Update siw_solicitacao set descricao = case when p_observacao is not null then p_observacao else null end where sq_siw_solicitacao = p_chave;
   End If;
   
   

end SP_PutSolicDevCelular;
/
