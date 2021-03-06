create or replace procedure sp_GetEspecieDocumento_PA
   (p_chave     in  number   default null,
    p_cliente   in  number   default null,
    p_nome      in  varchar2 default null,
    p_sigla     in  varchar2 default null,
    p_ativo     in  varchar2 default null,
    p_restricao in  varchar2 default null,
    p_result  out sys_refcursor) is
begin
   If p_restricao is null Then
      -- Recupera as esp�cies do documento
      open p_result for 
         select a.sq_especie_documento as chave, a.cliente, a.nome, a.sigla, a.ativo,
                case a.ativo when 'S' then 'Sim' else 'N�o' end as nm_ativo,
                acentos(a.nome) as nome_indice,
                b.sq_assunto, b.codigo as cd_assunto, b.descricao as ds_assunto
           from pa_especie_documento a
                left join pa_assunto b on (a.sq_assunto = b.sq_assunto)
          where ((p_chave   is null) or (p_chave   is not null and a.sq_especie_documento = p_chave))
            and ((p_cliente is null) or (p_cliente is not null and a.cliente              = p_cliente))
            and ((p_nome    is null) or (p_nome    is not null and upper(a.nome)          like '%'||upper(p_nome)||'%'))
            and ((p_sigla   is null) or (p_sigla   is not null and upper(a.sigla)         = upper(p_sigla)))         
            and ((p_ativo   is null) or (p_ativo   is not null and a.ativo                = p_ativo));
   ElsIf p_restricao = 'EXISTE' Then
      -- Verifica se h� outro registro com a mesma descri��o ou sigla
      open p_result for 
         select a.sq_especie_documento as chave, a.cliente, a.nome, a.sigla, a.ativo,
                acentos(a.nome) as nome_indice,
                case a.ativo when 'S' then 'Sim' else 'N�o' end as nm_ativo
           from pa_especie_documento a
          where a.sq_especie_documento <> coalesce(p_chave,0)
            and a.cliente              = p_cliente
            and ((p_nome    is null)   or (p_nome    is not null and upper(a.nome)          = upper(p_nome)))
            and ((p_sigla   is null)   or (p_sigla   is not null and upper(a.sigla)         = upper(p_sigla)))         
            and ((p_ativo   is null)   or (p_ativo   is not null and a.ativo                = p_ativo));   
   Elsif p_restricao = 'VINCULACAO' Then
      -- Verifica se o registro j� esta vinculado
      open p_result for 
         select count(a.sq_especie_documento) as existe
           from pa_especie_documento    a
                inner join pa_documento b on (a.sq_especie_documento = b.sq_especie_documento)
          where a.sq_especie_documento = p_chave;
   End If;
end sp_GetEspecieDocumento_PA;
/
