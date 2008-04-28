CREATE OR REPLACE FUNCTION siw.sp_GetEspecieDocumento_PA
   (p_chave     numeric,
    p_cliente   numeric,
    p_nome      varchar,
    p_sigla     varchar,
    p_ativo     varchar,
    p_restricao varchar)

  RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;
begin
   If p_restricao is null Then
      -- Recupera as esp�cies do documento
      open p_result for
         select a.sq_especie_documento as chave, a.cliente, a.nome, a.sigla, a.ativo,
                case a.ativo when 'S' then 'Sim' else 'N�o' end as nm_ativo
           from siw.pa_especie_documento a
          where ((p_chave   is null) or (p_chave   is not null and a.sq_especie_documento = p_chave))
            and ((p_cliente is null) or (p_cliente is not null and a.cliente              = p_cliente))
            and ((p_nome    is null) or (p_nome    is not null and upper(a.nome)          like '%'||upper(p_nome)||'%'))
            and ((p_sigla   is null) or (p_sigla   is not null and upper(a.sigla)         = upper(p_sigla)))
            and ((p_ativo   is null) or (p_ativo   is not null and a.ativo                = p_ativo));
   ElsIf p_restricao = 'EXISTE' Then
      -- Verifica se h� outro registro com a mesma descri��o ou sigla
      open p_result for
         select a.sq_especie_documento as chave, a.cliente, a.nome, a.sigla, a.ativo,
                case a.ativo when 'S' then 'Sim' else 'N�o' end as nm_ativo
           from siw.pa_especie_documento a
          where a.sq_especie_documento <> coalesce(p_chave,0)
            and a.cliente              = p_cliente
            and ((p_nome    is null)   or (p_nome    is not null and upper(a.nome)          = upper(p_nome)))
            and ((p_sigla   is null)   or (p_sigla   is not null and upper(a.sigla)         = upper(p_sigla)))
            and ((p_ativo   is null)   or (p_ativo   is not null and a.ativo                = p_ativo));
   Elsif p_restricao = 'VINCULACAO' Then
      -- Verifica se o registro j� esta vinculado
      open p_result for
         select count(a.sq_especie_documento) as existe
           from siw.pa_especie_documento    a
                inner join siw.pa_documento b on (a.sq_especie_documento = b.sq_especie_documento)
          where a.sq_especie_documento = p_chave;
   End If;
   return p_result;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.sp_GetEspecieDocumento_PA
   (p_chave     numeric,
    p_cliente   numeric,
    p_nome      varchar,
    p_sigla     varchar,
    p_ativo     varchar,
    p_restricao varchar) OWNER TO siw;

