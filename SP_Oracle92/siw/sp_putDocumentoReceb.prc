create or replace procedure sp_putDocumentoReceb
   (p_pessoa       in number,
    p_unid_autua   in number   default null,
    p_nu_guia      in number   default null,
    p_ano_guia     in number   default null
   ) is
   cursor c_protocolo is
     select a.sq_documento_log, a.sq_siw_solicitacao, a.unidade_destino, a.unidade_origem, a.interno
       from pa_documento_log a
            inner join pa_documento b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
      where a.nu_guia          = p_nu_guia
        and a.ano_guia         = p_ano_guia
        and b.unidade_autuacao = p_unid_autua
        and a.recebimento      is null;
begin
  for crec in c_protocolo loop
     -- Se tramitação interna, garante que a unidade de posse é a unidade recebedora
     update pa_documento a 
        set a.unidade_int_posse = case interno when 'S' then crec.unidade_destino else crec.unidade_origem end
      where a.sq_siw_solicitacao = crec.sq_siw_solicitacao;
     
     -- Atualiza o log com os dados do recebimento
     update pa_documento_log a set a.recebedor = p_pessoa, a.recebimento = sysdate where a.sq_documento_log = crec.sq_documento_log;
  end loop;
end sp_putDocumentoReceb;
/
