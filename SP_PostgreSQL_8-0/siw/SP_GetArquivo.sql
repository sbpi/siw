create or replace function siw.SP_GetArquivo
   (p_cliente      numeric,
    p_restricao    varchar,
    p_chave        numeric,
    p_sq_sistema   numeric,
    p_nome         varchar,
    p_diretorio    varchar,
    p_tipo_arquivo varchar)

  RETURNS character varying AS
$BODY$declare
    
    p_result      refcursor;
begin
   -- Recupera os tipos de arquivos
   If p_restricao is null Then
     open p_result for
        select a.sq_arquivo as chave, a.sq_sistema, a.nome as nm_arquivo, a.descricao, a.tipo, a.diretorio,
               b.sigla as sg_sistema, b.nome as nm_sistema
          from siw.dc_arquivo               a
               inner join    siw.dc_sistema b on (a.sq_sistema = b.sq_sistema)
         where b.cliente = p_cliente
           and ((p_chave        is null) or (p_chave        is not null and a.sq_arquivo = p_chave))
           and ((p_sq_sistema   is null) or (p_sq_sistema   is not null and a.sq_sistema = p_sq_sistema))
           and ((p_tipo_arquivo is null) or (p_tipo_arquivo is not null and a.tipo       = p_tipo_arquivo))
           and ((p_diretorio    is null) or (p_diretorio    is not null and ((p_diretorio = '/' and a.diretorio is null) or (p_diretorio <> '/' and upper(a.diretorio) like '%'||upper(p_diretorio)||'%'))))
           and ((p_nome         is null) or (p_nome         is not null and upper(a.nome) like '%'||upper(p_nome)||'%'));
   Else
     open p_result for
        select a.sq_arquivo as chave, a.sq_sistema, a.nome as  nm_arquivo, a.descricao, a.tipo, a.diretorio,
               b.sigla as sg_sistema, b.nome as nm_sistema
          from siw.dc_arquivo               a
               inner join    siw.dc_sistema b on (a.sq_sistema = b.sq_sistema)
         where b.cliente = p_cliente
           and ((p_chave        is null) or (p_chave        is not null and a.sq_arquivo = p_chave))
           and ((p_sq_sistema   is null) or (p_sq_sistema   is not null and a.sq_sistema = p_sq_sistema))
           and ((p_tipo_arquivo is null) or (p_tipo_arquivo is not null and a.tipo       = p_tipo_arquivo))
           and ((p_diretorio    is null  and a.diretorio is null) or
                (p_diretorio is not null and ((p_diretorio = '/' and a.diretorio is null) or (p_diretorio <> '/' and upper(a.diretorio) = upper(p_diretorio))))
               )
           and ((p_nome         is null) or (p_nome         is not null and upper(a.nome) = upper(p_nome)));
   End If;
end 

$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetArquivo
   (p_cliente      numeric,
    p_restricao    varchar,
    p_chave        numeric,
    p_sq_sistema   numeric,
    p_nome         varchar,
    p_diretorio    varchar,
    p_tipo_arquivo varchar) OWNER TO siw;

