create or replace procedure sp_putDocumentoReceb
   (p_pessoa       in number,
    p_unid_autua   in number   default null,
    p_nu_guia      in number   default null,
    p_ano_guia     in number   default null
   ) is
   cursor c_protocolo is
     select a.sq_documento_log, a.sq_siw_solicitacao, a.unidade_destino, a.unidade_origem, a.interno,
            d.sq_caixa
       from pa_documento_log          a
            inner join   pa_unidade   a1 on (a.unidade_origem     = a1.sq_unidade)
            inner   join pa_documento b  on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
              inner join pa_unidade   c  on (b.unidade_autuacao   = c.sq_unidade)
              left  join pa_caixa     d on  (b.sq_caixa           = d.sq_caixa)
      where p_nu_guia     = a.nu_guia
        and p_ano_guia    = a.ano_guia
        and coalesce(a1.sq_unidade_pai,a1.sq_unidade) = coalesce(c.sq_unidade_pai,c.sq_unidade)
        and a.recebimento is null;
begin
  for crec in c_protocolo loop
     -- Se tramitação interna, garante que a unidade de posse é a unidade recebedora
     update pa_documento a 
        set a.unidade_int_posse = case crec.interno when 'S' then crec.unidade_destino else crec.unidade_origem end
      where a.sq_siw_solicitacao = crec.sq_siw_solicitacao;
     
     -- Atualiza o log com os dados do recebimento
     update pa_documento_log a set a.recebedor = p_pessoa, a.recebimento = sysdate where a.sq_documento_log = crec.sq_documento_log;
     
     -- Atualiza a caixa, quando protocolo estiver arquivado
     update pa_caixa c
        set c.arquivo_data = sysdate
     where c.sq_caixa = coalesce(crec.sq_caixa,0);
  end loop;
end sp_putDocumentoReceb;
/
