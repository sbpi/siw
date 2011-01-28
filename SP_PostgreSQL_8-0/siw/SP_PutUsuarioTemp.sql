create or replace FUNCTION SP_PutUsuarioTemp (
  p_operacao      varchar,
  p_cliente       numeric, 
  p_cpf           varchar, 
  p_nome          varchar, 
  p_nome_resumido varchar, 
  p_sexo          varchar, 
  p_email         varchar, 
  p_vinculo       numeric, 
  p_unidade       varchar, 
  p_sala          varchar, 
  p_ramal         varchar, 
  p_efetivar      varchar, 
  p_efetivado     varchar 
 ) RETURNS VOID AS $$
DECLARE
BEGIN
  if p_operacao = 'I' then
     insert into sg_autenticacao_temp
       (cliente,   cpf,   nome,   nome_resumido,   sexo,   email,   vinculo,   unidade,   sala,   ramal)
     values
       (p_cliente, p_cpf, p_nome, p_nome_resumido, p_sexo, p_email, p_vinculo, p_unidade, p_sala, p_ramal);
  elsif p_operacao = 'A' then
    update sg_autenticacao_temp
       set cliente       = p_cliente,
           cpf           = p_cpf,
           nome          = p_nome,
           nome_resumido = p_nome_resumido,
           sexo          = p_sexo,
           email         = p_email,
           vinculo       = p_vinculo,
           unidade       = p_unidade,
           sala          = p_sala,
           ramal         = p_ramal
     where cliente = p_cliente
       and cpf     = p_cpf;
  elsif p_operacao = 'E' then
    DELETE FROM sg_autenticacao_temp where cliente = p_cliente and cpf = p_cpf;
  elsif p_operacao = 'T' then 
    -- Guarda informação que o usuário deverá ser criado na base definitiva
    update sg_autenticacao_temp set efetivar = p_efetivar where cliente = p_cliente and cpf = p_cpf;
  end if;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;