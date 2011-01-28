create or replace FUNCTION SP_GetUsuarioTemp(
  p_cliente   numeric, 
  p_cpf       varchar, 
  p_efetivado varchar,
  p_result    REFCURSOR
 ) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
  open p_result for
    select a.cliente, a.cpf, a.nome, a.nome_resumido, a.sexo, a.email, a.vinculo, a.unidade, 
           a.sala, a.ramal, a.efetivar, a.efetivado, a.efetivacao 
      from sg_autenticacao_temp a
     where a.cliente    = p_cliente
       and (p_cpf       is null or (p_cpf       is not null and a.cpf = p_cpf))
       and (p_efetivado is null or (p_efetivado is not null and a.efetivado = p_efetivado));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;