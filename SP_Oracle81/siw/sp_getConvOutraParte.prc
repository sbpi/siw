create or replace procedure SP_GetConvOutraParte
   (p_sq_acordo_outra_parte   in number default null,
    p_chave                   in number,
    p_chave_aux               in number default null,
    p_tipo                    in number default null,
    p_result                  out siw.sys_refcursor) is
begin
  -- Recupera os tipos de contrato do cliente
  open p_result for 
    select a.sq_acordo_outra_parte, a.sq_siw_solicitacao, a.outra_parte,b.cnpj, c.nome nm_pessoa, c.nome_resumido,
           decode(a.tipo,1,'concedente/contratante/parceiro',2,'convenente',3,'executor/contratado') nm_tipo
       from ac_acordo_outra_parte a, 
            co_pessoa_juridica b, 
            co_pessoa c          
     where a.outra_parte = b.sq_pessoa
       and a.outra_parte = c.sq_pessoa
       and (a.sq_siw_solicitacao = p_chave)
       and ((p_sq_acordo_outra_parte is null) or (p_sq_acordo_outra_parte is not null and a.sq_acordo_outra_parte  = p_sq_acordo_outra_parte))
       and ((p_chave_aux is null)             or (p_chave_aux is not null and a.outra_parte          = p_chave_aux))
       and ((p_tipo      is null)             or (p_tipo      is not null and a.tipo                 = p_tipo));
end SP_GetConvOutraParte;
/
