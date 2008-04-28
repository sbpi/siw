create or replace function siw.SP_PutUsuario
   (p_operacao                 varchar,
    p_chave                    numeric,
    p_sq_sistema               numeric,
    p_nome                     varchar,
    p_descricao                varchar
   ) 
  RETURNS character varying AS
$BODY$declare


begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into siw.dc_usuario
        (sq_usuario, sq_sistema, nome, descricao)
      (select sq_usuario.nextval, p_sq_sistema, p_nome, p_descricao from dual);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update siw.dc_Usuario set
         nome      = p_nome,
         descricao = p_descricao,
         sq_sistema= p_sq_sistema
       where sq_Usuario = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete from siw.dc_Usuario where sq_usuario = p_chave;
   End If;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_PutUsuario
   (p_operacao                 varchar,
    p_chave                    numeric,
    p_sq_sistema               numeric,
    p_nome                     varchar,
    p_descricao                varchar
   )  OWNER TO siw;
