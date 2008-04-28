CREATE OR REPLACE FUNCTION siw.SP_GetCodigo
  (p_cliente       numeric,
   p_restricao     varchar,
   p_chave_interna varchar,
   p_chave_aux     varchar )


    RETURNS refcursor AS
$BODY$
DECLARE
  
   p_result       refcursor;
   
  -- Recupera a chave primária de uma tabela a partir de seu código externo

  -- p_restricao     : indica a tabela
  -- p_chave_interna : chave do registro num sistema externo
begin
  If p_restricao = 'TIPO_PESSOA' Then
     open p_result for select sq_tipo_pessoa as codigo_interno from siw.CO_TIPO_PESSOA where nome = p_chave_interna;
  Elsif p_restricao = 'BANCO' Then
     open p_result for select sq_banco as codigo_interno from siw.CO_BANCO where sq_banco = p_chave_interna;
  Elsif p_restricao = 'AGENCIA' Then
     open p_result for select sq_agencia as codigo_interno from siw.CO_AGENCIA where sq_agencia = p_chave_interna;
  Elsif p_restricao = 'REGIAO' Then
     open p_result for select sq_regiao as codigo_interno from siw.CO_REGIAO where sq_regiao = p_chave_interna;
  Elsif p_restricao = 'UF' Then
     open p_result for select co_uf as  codigo_interno from siw.CO_UF where co_uf = p_chave_interna;
  Elsif p_restricao = 'UNIDADE' Then
     open p_result for select sq_unidade as codigo_interno, codigo as codigo_externo from siw.EO_UNIDADE where sq_pessoa = p_cliente and codigo = p_chave_interna;
  ElsIf p_restricao = 'PAIS' Then
     open p_result for select sq_pais as codigo_interno, codigo_externo from siw.CO_PAIS where sq_pais = p_chave_interna;
  Elsif p_restricao = 'CIDADE' Then
     open p_result for select sq_cidade as codigo_interno, codigo_externo from siw.CO_CIDADE where sq_cidade = p_chave_interna;
  Elsif p_restricao = 'TIPO_UNIDADE' Then
     open p_result for select sq_tipo_unidade as codigo_interno, codigo_externo from siw.EO_TIPO_UNIDADE where sq_pessoa = p_cliente and sq_tipo_unidade = p_chave_interna;
  Elsif p_restricao = 'AREA_ATUACAO' Then
     open p_result for select sq_area_atuacao as  codigo_interno, codigo_externo from siw.EO_AREA_ATUACAO where sq_pessoa = p_cliente and sq_area_atuacao = p_chave_interna;
  Elsif p_restricao = 'LOCALIZACAO' Then
     open p_result for select sq_localizacao as codigo_interno, codigo_externo from siw.EO_LOCALIZACAO where sq_localizacao = p_chave_interna and sq_unidade in (select sq_unidade from eo_unidade where sq_pessoa = p_cliente);
  Elsif p_restricao = 'PESSOA' Then
     open p_result for select sq_pessoa as codigo_interno, codigo_externo from siw.CO_PESSOA where sq_pessoa_pai = p_cliente and sq_pessoa = p_chave_interna;
  Elsif p_restricao = 'TIPO_VINCULO' Then
     open p_result for select sq_tipo_vinculo as codigo_interno, codigo_externo from siw.CO_TIPO_VINCULO where cliente = p_cliente and sq_tipo_vinculo = p_chave_interna;
  Elsif p_restricao = 'TIPO_ENDERECO' Then
     open p_result for select sq_tipo_endereco as  codigo_interno, codigo_externo from siw.CO_TIPO_ENDERECO where sq_tipo_endereco = p_chave_interna;
  Elsif p_restricao = 'ENDERECO' Then
     open p_result for select sq_pessoa_endereco as codigo_interno, codigo_externo from siw.CO_PESSOA_ENDERECO where sq_pessoa = p_cliente and sq_pessoa_endereco = p_chave_interna;
  End If;
end 
 $BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_GetCodigo
  (p_cliente       numeric,
   p_restricao     varchar,
   p_chave_interna varchar,
   p_chave_aux     varchar ) OWNER TO siw;
