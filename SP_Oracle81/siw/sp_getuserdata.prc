create or replace procedure SP_GetUserData
   (p_cliente  in number,
    p_username in varchar2,
    p_result   out siw.sys_refcursor
   ) is
begin
   open p_result for 
     select a.sq_pessoa, a.username, a.senha, a.assinatura, a.ativo, a.sq_unidade, a.sq_localizacao,
            a.gestor_seguranca, a.gestor_sistema, a.cliente, a.email,
            a.ultima_troca_senha, a.ultima_troca_assin, a.tentativas_senha, a.tentativas_assin,
            b.nome, b.nome_resumido, c.interno, d.sq_pessoa_endereco, e.codigo 
       from sg_autenticacao a,
            co_pessoa       b,
            co_tipo_vinculo c,
            eo_localizacao  d,
            eo_unidade      e
      where (a.sq_pessoa       = b.sq_pessoa)
        and (b.sq_tipo_vinculo = c.sq_tipo_vinculo (+) )
        and (a.sq_localizacao  = d.sq_localizacao)
        and (a.sq_unidade      = e.sq_unidade)
        and a.cliente         = p_cliente
        and upper(a.username) = upper(p_username);
end SP_GetUserData;
/

