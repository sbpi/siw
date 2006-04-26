create or replace function SP_UpdatePassword
   (p_cliente   numeric,
    p_sq_pessoa numeric,
    p_valor     varchar,
    p_tipo      varchar
   ) returns void as $$
begin
   If p_tipo = 'PASSWORD' Then
      update sg_autenticacao
         set senha                   = criptografia(upper(p_valor)),
             ultima_troca_senha      = now(),
             tentativas_senha        = 0
       where sq_pessoa               = p_sq_pessoa;
   Elsif p_tipo = 'SIGNATURE' Then
      update sg_autenticacao
         set assinatura              = criptografia(upper(p_valor)),
             ultima_troca_assin      = now(),
             tentativas_assin        = 0
       where sq_pessoa               = p_sq_pessoa;
   End If;
end; $$ language 'plpgsql' volatile;