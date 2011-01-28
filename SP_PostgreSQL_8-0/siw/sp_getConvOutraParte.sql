create or replace FUNCTION SP_GetConvOutraParte
   (p_sq_acordo_outra_parte   numeric,
    p_chave                   numeric,
    p_chave_aux               numeric,
    p_tipo                    numeric,
    p_result                  REFCURSOR) RETURNS REFCURSOR AS $$
DECLARE
BEGIN
  -- Recupera os tipos de contrato do cliente
  open p_result for 
    select a.sq_acordo_outra_parte, a.sq_siw_solicitacao, a.outra_parte, nvl(c.sq_tipo_pessoa,e.sq_tipo_pessoa) sq_tipo_pessoa,
           b.cnpj, d.cpf, nvl(c.nome,e.nome) nm_pessoa, nvl(c.nome_resumido,e.nome_resumido) nome_resumido,
           case a.tipo when 1 then 'concedente/contratante/parceiro' when 2 then 'convenente' when 3 then 'executor/contratado'end nm_tipo
       from ac_acordo_outra_parte a 
        left  join co_pessoa_juridica b on (a.outra_parte = b.sq_pessoa)
        left  join co_pessoa          c on (a.outra_parte = c.sq_pessoa)
        left  join co_pessoa_fisica   d on (a.outra_parte = d.sq_pessoa)
        left  join co_pessoa          e on (a.outra_parte = e.sq_pessoa)
     where a.sq_siw_solicitacao = p_chave
       and ((p_sq_acordo_outra_parte is null) or (p_sq_acordo_outra_parte is not null and a.sq_acordo_outra_parte  = p_sq_acordo_outra_parte))
       and ((p_chave_aux is null)             or (p_chave_aux is not null and a.outra_parte          = p_chave_aux))
       and ((p_tipo      is null)             or (p_tipo      is not null and a.tipo                 = p_tipo));
  return p_result;
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;