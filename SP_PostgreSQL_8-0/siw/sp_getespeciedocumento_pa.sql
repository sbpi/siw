create or replace FUNCTION sp_GetEspecieDocumento_PA
   (p_chave      numeric,
    p_cliente    numeric,
    p_nome       varchar,
    p_sigla      varchar,
    p_ativo      varchar,
    p_restricao  varchar,
    p_result  REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   If p_restricao is null Then
      -- Recupera as espécies do documento
      open p_result for 
         select a.sq_especie_documento as chave, a.cliente, a.nome, a.sigla, a.ativo,
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo,
                b.sq_assunto, b.codigo as cd_assunto, b.descricao as ds_assunto
           from pa_especie_documento a
                left join pa_assunto b on (a.sq_assunto = b.sq_assunto)
          where ((p_chave   is null) or (p_chave   is not null and a.sq_especie_documento = p_chave))
            and ((p_cliente is null) or (p_cliente is not null and a.cliente              = p_cliente))
            and ((p_nome    is null) or (p_nome    is not null and upper(a.nome)          like '%'||upper(p_nome)||'%'))
            and ((p_sigla   is null) or (p_sigla   is not null and upper(a.sigla)         = upper(p_sigla)))         
            and ((p_ativo   is null) or (p_ativo   is not null and a.ativo                = p_ativo));
   ElsIf p_restricao = 'EXISTE' Then
      -- Verifica se há outro registro com a mesma descrição ou sigla
      open p_result for 
         select a.sq_especie_documento as chave, a.cliente, a.nome, a.sigla, a.ativo,
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo
           from pa_especie_documento a
          where a.sq_especie_documento <> coalesce(p_chave,0)
            and a.cliente              = p_cliente
            and ((p_nome    is null)   or (p_nome    is not null and upper(a.nome)          = upper(p_nome)))
            and ((p_sigla   is null)   or (p_sigla   is not null and upper(a.sigla)         = upper(p_sigla)))         
            and ((p_ativo   is null)   or (p_ativo   is not null and a.ativo                = p_ativo));   
   Elsif p_restricao = 'VINCULACAO' Then
      -- Verifica se o registro já esta vinculado
      open p_result for 
         select count(a.sq_especie_documento) as existe
           from pa_especie_documento    a
                inner join pa_documento b on (a.sq_especie_documento = b.sq_especie_documento)
          where a.sq_especie_documento = p_chave;
   End If;
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;