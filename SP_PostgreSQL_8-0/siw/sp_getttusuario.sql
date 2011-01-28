create or replace FUNCTION SP_GetTTUsuario
   (p_chave            numeric,
    p_cliente          numeric,
    p_usuario          numeric,
    p_sq_central_fone  numeric,    
    p_codigo           varchar,    
    p_result          REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
   -- Recupera os tipos de Tabela
     open   p_result for 
     select a.sq_usuario_central chave, a.usuario,
            c.nome nm_usuario
     from tt_usuario               a
       inner join co_pessoa        c on (a.usuario            = c.sq_pessoa)
         
     where ((p_chave           is null) or (p_chave           is not null and a.sq_usuario_central = p_chave))
       and ((p_cliente         is null) or (p_cliente         is not null and a.cliente            = p_cliente))
       and ((p_usuario         is null) or (p_usuario         is not null and a.usuario            = p_usuario))
       and ((p_sq_central_fone is null) or (p_sq_central_fone is not null and a.sq_central_fone    = p_sq_central_fone))
       and ((p_codigo          is null) or (p_codigo          is not null and a.codigo             = p_codigo));   
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;