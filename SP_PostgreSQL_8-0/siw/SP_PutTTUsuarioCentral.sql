create or replace function siw.SP_PutTTUsuarioCentral
   (p_operacao        varchar ,
    p_chave           numeric,
    p_cliente         numeric,
    p_usuario         numeric,
    p_sq_central_fone numeric,
    p_codigo          varchar
    ) 
  RETURNS character varying AS
$BODY$declare


begin
   If p_operacao = 'I' Then

   insert into siw.tt_usuario (sq_usuario_central, cliente, usuario, sq_central_fone, codigo)
   values (sq_usuario_central.nextVal, p_cliente, p_usuario, p_sq_central_fone, p_codigo);

   Elsif p_operacao = 'A' Then
      -- Altera registro
     update siw.tt_usuario set codigo = p_codigo where sq_usuario_central = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
       delete from siw.tt_usuario where sq_usuario_central = p_chave;
   End If;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.SP_PutTTUsuarioCentral
   (p_operacao        varchar ,
    p_chave           numeric,
    p_cliente         numeric,
    p_usuario         numeric,
    p_sq_central_fone numeric,
    p_codigo          varchar
    )  OWNER TO siw;
