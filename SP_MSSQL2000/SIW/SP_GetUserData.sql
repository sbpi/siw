alter procedure dbo.SP_GetUserData
   (@p_cliente  int,
    @p_username varchar(30)
   ) as
begin
     select a.sq_pessoa, a.username, a.senha, a.assinatura, a.ativo, a.sq_unidade, a.sq_localizacao,
            a.gestor_seguranca, a.gestor_sistema, a.cliente, a.email,
            a.ultima_troca_senha, a.ultima_troca_assin, a.tentativas_senha, a.tentativas_assin,
            a.tipo_autenticacao, 
            case a.tipo_autenticacao when 'B' then 'BD' when 'A' then 'MS-AD' else 'O-LDAP' end as nm_tipo_autenticacao,
            b.nome, b.nome_resumido, c.interno, d.sq_pessoa_endereco, e.codigo,
            f.cpf, f.sexo,
            case f.sexo when 'F' then 'Feminino' when 'M' then 'Masculino' else null end as nm_sexo,
            dbo.to_char(a.ultima_troca_senha, 'DD/MM/YYYY, HH24:MI:SS') as dt_ultima_troca_senha, 
            dbo.to_char(a.ultima_troca_assin, 'DD/MM/YYYY, HH24:MI:SS') as dt_ultima_troca_assin 
       from sg_autenticacao a
            inner   join co_pessoa        b on (a.sq_pessoa       = b.sq_pessoa)
              left  join co_tipo_vinculo  c on (b.sq_tipo_vinculo = c.sq_tipo_vinculo)
              left  join co_pessoa_fisica f on (b.sq_pessoa       = f.sq_pessoa)
            inner   join eo_localizacao   d on (a.sq_localizacao  = d.sq_localizacao)
            inner   join eo_unidade       e on (a.sq_unidade      = e.sq_unidade)
      where a.cliente         = @p_cliente
        and upper(a.username) = upper(@p_username);
end
