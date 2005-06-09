create or replace procedure SP_GetUserData
   (p_cliente  in number,
    p_username in varchar2,
    p_result   out sys_refcursor
   ) is
begin
   open p_result for 
     select a.sq_pessoa, a.username, a.senha, a.assinatura, a.ativo, a.sq_unidade, a.sq_localizacao,
            a.gestor_seguranca, a.gestor_sistema, a.cliente, a.email,
            a.ultima_troca_senha, a.ultima_troca_assin, a.tentativas_senha, a.tentativas_assin,
            b.nome, b.nome_resumido, c.interno, d.sq_pessoa_endereco, e.codigo 
       from sg_autenticacao a
            inner        join co_pessoa       b on (a.sq_pessoa       = b.sq_pessoa)
              left outer join co_tipo_vinculo c on (b.sq_tipo_vinculo = c.sq_tipo_vinculo)
            inner        join eo_localizacao  d on (a.sq_localizacao  = d.sq_localizacao)
            inner        join eo_unidade      e on (a.sq_unidade      = e.sq_unidade)
      where a.cliente         = p_cliente
        and upper(a.username) = upper(p_username);
end SP_GetUserData;
/

