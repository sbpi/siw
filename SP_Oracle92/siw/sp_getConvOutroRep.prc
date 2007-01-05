create or replace procedure SP_GetConvOutroRep
   (p_chave       in number,
    p_sq_pessoa   in number default null,    
    p_chave_aux   in number default null,
    p_result      out sys_refcursor) is
begin
  -- Recupera os tipos de contrato do cliente
  open p_result for 
    select  a.sq_acordo_outra_parte, a.sq_pessoa, a.sq_siw_solicitacao, 
            d.nome nm_pessoa, d.nome_resumido, c.cpf,
            b.sq_pessoa_fax, b.nr_fax,
            f.sq_pessoa_telefone, f.ddd, f.nr_telefone,
            l.sq_pessoa_celular, l.nr_celular, i.email            
      from ac_acordo_outra_rep       a
        inner join co_pessoa         d     on (a.sq_pessoa = d.sq_pessoa)
        inner join co_pessoa_fisica  c     on (a.sq_pessoa = c.sq_pessoa)
        left outer join  (select sq_pessoa, w.sq_pessoa_telefone sq_pessoa_fax, w.numero nr_fax
                           from co_pessoa_telefone          w
                                inner join co_tipo_telefone x on (w.sq_tipo_telefone   = x.sq_tipo_telefone)
                                inner join co_pessoa        z on (w.sq_pessoa          = z.sq_pessoa)
                          where x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                             and x.nome               = 'Fax'
                             and x.ativo              = 'S'
                             and w.padrao             = 'S'
                        )                  b on (a.sq_pessoa     = b.sq_pessoa)
       left outer join  (select sq_pessoa, w.sq_pessoa_telefone, w.ddd, w.numero nr_telefone
                           from co_pessoa_telefone          w
                                inner join co_tipo_telefone x on (w.sq_tipo_telefone   = x.sq_tipo_telefone)
                                inner join co_pessoa        z on (w.sq_pessoa          = z.sq_pessoa)
                           where x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                             and x.nome               = 'Comercial'
                             and x.ativo              = 'S'
                             and w.padrao             = 'S'
                         )                  f on (a.sq_pessoa     = f.sq_pessoa)
        left outer join  (select sq_pessoa, w.sq_pessoa_telefone sq_pessoa_celular, w.numero nr_celular
                            from co_pessoa_telefone          w
                                 inner join co_tipo_telefone x on (w.sq_tipo_telefone   = x.sq_tipo_telefone)
                                 inner join co_pessoa        z on (w.sq_pessoa          = z.sq_pessoa)
                           where x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                             and x.nome               = 'Celular'
                             and x.ativo              = 'S'
                             and w.padrao             = 'S'
                         )                  l on (a.sq_pessoa     = l.sq_pessoa)
        left outer join  (select sq_pessoa, logradouro email
                            from co_pessoa_endereco            w
                                inner   join co_tipo_endereco x on (w.sq_tipo_endereco   = x.sq_tipo_endereco)
                                inner   join co_pessoa        y on (w.sq_pessoa          = y.sq_pessoa)
                                inner join co_tipo_pessoa   z on (y.sq_tipo_pessoa     = z.sq_tipo_pessoa)
                          where x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                             and x.email              = 'S'
                             and x.ativo              = 'S'
                             and w.padrao             = 'S'
                         )                  i on (a.sq_pessoa = i.sq_pessoa)        
    where a.sq_siw_solicitacao = p_chave
       and (p_sq_pessoa    is null or (p_sq_pessoa   is not null and a.sq_pessoa             = p_sq_pessoa))
       and (p_chave_aux    is null or (p_chave_aux   is not null and a.sq_acordo_outra_parte = p_chave_aux));
   
end SP_GetConvOutroRep;
/
