create or replace procedure SP_PutPD_RespContas
   (p_operacao            in  varchar2,
    p_cliente             in number,
    p_chave               in number,
    p_usuario             in number,
    p_pessoa              in number,
    p_justificativa       in varchar2
   ) is
begin
   -- Atualiza o cadastrador da solicitação, também responsável pela prestação de contas, juntamente com o solicitante e o beneficiário
   update siw_solicitacao
     set cadastrador        = p_pessoa,
         justificativa      = p_justificativa
   where sq_siw_solicitacao = p_chave;

   -- Registra a alteração.
   Insert into gd_demanda_log 
          (sq_demanda_log,            sq_siw_solicitacao, cadastrador, data_inclusao, observacao)
   (select sq_demanda_log.nextval,    p_chave,            p_usuario,   sysdate,
           'Alteração do responsável pela prestação de contas para: '||a.nome||chr(13)||chr(10)||'Justificativa: '||p_justificativa
      from co_pessoa a
     where a.sq_pessoa = p_pessoa
   );
end SP_PutPD_RespContas;
/
