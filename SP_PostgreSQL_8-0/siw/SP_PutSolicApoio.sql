CREATE OR REPLACE FUNCTION siw.SP_PutSolicApoio(character varying, character varying,numeric,numeric,
character varying,character varying,numeric,numeric)
  RETURNS character varying AS
$BODY$declare
   Result    varchar(2);
begin

   If $1 = 'I' Then
      -- Insere registro
      insert into siw.siw_solic_apoio
        (sq_solic_apoio, sq_siw_solicitacao, sq_tipo_apoio, entidade, descricao, valor,
         sq_pessoa_atualizacao, ultima_atualizacao)
      values
        (nextval('siw.sq_solic_apoio'), $2, $4, $5, $6, $7,
         $8, now());
   Elsif $1 = 'A' Then
      -- Altera registro
      update siw_solic_apoio
         set sq_tipo_apoio         = $4,
             entidade              = $5,
             descricao             = $6,
             valor                 = $7,
             sq_pessoa_atualizacao = $8,
             ultima_atualizacao    = now()
       where sq_siw_solicitacao = $2
         and sq_solic_apoio     = $3;
   Elsif $1 = 'E' Then
      delete from siw.siw_solic_apoio where sq_solic_apoio = $3;
   End If;
  return Result;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_PutSolicApoio(character varying, character varying,numeric,numeric,
character varying,character varying,numeric,numeric) OWNER TO siw;
