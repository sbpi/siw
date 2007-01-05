create or replace procedure SP_GetConvOutroRep
   (p_chave       in number,
    p_sq_pessoa   in number default null,    
    p_chave_aux   in number default null,
    p_result      out siw.sys_refcursor) is
begin
  -- Recupera os tipos de contrato do cliente
  open p_result for 
    select  a.sq_acordo_outra_parte, a.sq_pessoa, a.sq_siw_solicitacao, 
            d.nome nm_pessoa, d.nome_resumido, c.cpf,
            b.sq_pessoa_fax, b.nr_fax,
            f.sq_pessoa_telefone, f.ddd, f.nr_telefone,
            l.sq_pessoa_celular, l.nr_celular, i.email            
      from ac_acordo_outra_rep  a,
           co_pessoa            d,     
           co_pessoa_fisica     c,    
           (select w.sq_pessoa, w.sq_pessoa_telefone sq_pessoa_fax, w.numero nr_fax
                           from co_pessoa_telefone  w,
                                co_tipo_telefone    x,
                                co_pessoa           z
                          where w.sq_tipo_telefone   = x.sq_tipo_telefone
                             and w.sq_pessoa          = z.sq_pessoa
                             and x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                             and x.nome               = 'Fax'
                             and x.ativo              = 'S'
                             and w.padrao             = 'S'
                        )                  b,
           (select w.sq_pessoa, w.sq_pessoa_telefone, w.ddd, w.numero nr_telefone
                           from co_pessoa_telefone  w,
                                co_tipo_telefone    x, 
                                co_pessoa           z 
                           where (w.sq_tipo_telefone  = x.sq_tipo_telefone)
                             and (w.sq_pessoa         = z.sq_pessoa)
                             and x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                             and x.nome               = 'Comercial'
                             and x.ativo              = 'S'
                             and w.padrao             = 'S'
                         )                  f,  
           (select w.sq_pessoa, w.sq_pessoa_telefone sq_pessoa_celular, w.numero nr_celular
                            from co_pessoa_telefone w,
                                 co_tipo_telefone   x, 
                                 co_pessoa          z 
                           where (w.sq_tipo_telefone   = x.sq_tipo_telefone) 
                             and (w.sq_pessoa          = z.sq_pessoa)
                             and x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                             and x.nome               = 'Celular'
                             and x.ativo              = 'S'
                             and w.padrao             = 'S'
                         )                  l, 
           (select w.sq_pessoa, logradouro email
                            from co_pessoa_endereco w,
                                 co_tipo_endereco   x, 
                                 co_pessoa          y, 
                                 co_tipo_pessoa     z  
                          where (w.sq_tipo_endereco   = x.sq_tipo_endereco)
                             and (w.sq_pessoa          = y.sq_pessoa)
                             and (y.sq_tipo_pessoa     = z.sq_tipo_pessoa) 
                             and x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                             and x.email              = 'S'
                             and x.ativo              = 'S'
                             and w.padrao             = 'S'
                         )                  i 
    where  (a.sq_pessoa          = d.sq_pessoa)
       and (a.sq_pessoa          = c.sq_pessoa)
       and (a.sq_pessoa          = b.sq_pessoa (+))
       and (a.sq_pessoa          = f.sq_pessoa (+))
       and (a.sq_pessoa          = l.sq_pessoa (+))
       and (a.sq_pessoa          = i.sq_pessoa (+))        
       and (a.sq_siw_solicitacao = p_chave)
       and (p_sq_pessoa    is null or (p_sq_pessoa   is not null and a.sq_pessoa             = p_sq_pessoa))
       and (p_chave_aux    is null or (p_chave_aux   is not null and a.sq_acordo_outra_parte = p_chave_aux));
   
end SP_GetConvOutroRep;
/
