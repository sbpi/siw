create or replace function SP_PutSIWModulo
   (p_operacao         varchar,
    p_sq_modulo        numeric,
    p_nome             varchar,
    p_sigla            varchar,
    p_objetivo_geral   varchar
   ) returns void as $$
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into siw_modulo (sq_modulo, nome, sigla,objetivo_geral)
      (select nextval('sq_modulo'),
              trim(p_nome),
              trim(upper(p_sigla)),
              trim(p_objetivo_geral)
      );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update siw_modulo set
         nome           = trim(p_nome),
         sigla          = trim(upper(p_sigla)),
         objetivo_geral = trim(p_objetivo_geral)
      where sq_modulo   = p_sq_modulo;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete from siw_modulo where sq_modulo = p_sq_modulo;
   End If;
end; $$ language 'plpgsql' volatile;

