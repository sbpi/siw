create or replace procedure sp_getDocumentoInter
   (p_chave     in number   default null,
    p_chave_aux in number   default null,
    p_principal in varchar2 default null,
    p_restricao in varchar2 default null,
    p_result    out sys_refcursor) is
begin
   If p_restricao is null Then
      open p_result for
         select a.sq_siw_solicitacao as chave, a.sq_pessoa as chave_aux, a.principal,
                case a.principal when 'S' then 'Sim' else 'Não' end as nm_principal,
                b.nome, b.nome_resumido,
                c.rg_numero, c.rg_emissor, c.rg_emissao, c.cpf, c.passaporte_numero, c.sq_pais_passaporte, c.sexo,
                case when c.sexo is null then null else case c.sexo when 'M' then 'Masculino' else 'Feminino' end end as nm_sexo,
                case when c.sq_pessoa is not null 
                     then c.cpf
                     else d.cnpj
                end as identificador_principal,
                case when c.sq_pessoa is not null 
                     then c.rg_numero||' ('||c.rg_emissor||')'
                     else d.inscricao_estadual
                end as identificador_secundario,
                case when c.passaporte_numero is null
                     then null 
                     else case c1.padrao when 'S' then c.passaporte_numero else c.passaporte_numero||' ('||c1.nome||')' end
                end as nr_passaporte,
                c1.nome as nm_pais,
                d.cnpj, d.inscricao_estadual,
                e.sq_tipo_pessoa, e.nome as nm_tipo_pessoa
           from pa_documento_interessado          a
                inner     join co_pessoa          b  on (a.sq_pessoa          = b.sq_pessoa)
                  left    join co_pessoa_fisica   c  on (b.sq_pessoa          = c.sq_pessoa)
                    left  join co_pais            c1 on (c.sq_pais_passaporte = c1.sq_pais)
                  left    join co_pessoa_juridica d  on (b.sq_pessoa          = d.sq_pessoa)
                  inner   join co_tipo_pessoa     e  on (b.sq_tipo_pessoa     = e.sq_tipo_pessoa)
          where (p_chave     is null or (p_chave is not null and a.sq_siw_solicitacao = p_chave))
            and (p_chave_aux is null or (p_chave_aux is not null and b.sq_pessoa  = p_chave_aux))
            and (p_principal is null or (p_principal is not null and a.principal  = p_principal));
   End If;
end sp_getDocumentoInter;
/
