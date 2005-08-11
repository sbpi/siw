create or replace procedure SP_GetGPColaborador
   (p_cliente   in number,
    p_chave     in number    default null,
    p_nome      in varchar2  default null,
    p_ativo     in varchar2  default null,
    p_chave_aux in number    default null,
    p_restricao in varchar2  default null,
    p_result    out sys_refcursor) is
begin
   If p_restricao is null Then
      -- Recupera todas ou um colaborador
      open p_result for 
         select a.sq_pessoa chave, a.ctps_numero, a.ctps_serie, a.ctps_emissor, a.ctps_emissao_data,
                a.pis_pasep, a.pispasep_numero, a.pispasep_cadastr, a.te_numero, a.te_zona, a.te_secao,
                a.tipo_sangue, a.doador_sangue, a.doador_orgaos, a.reservista_csm, a.reservista_numero,
                a.observacoes, b.nome, b.nome_resumido, c.nascimento, c.rg_numero, c.rg_emissor, c.rg_emissao,
                c.cpf, c.sq_cidade_nasc, c.passaporte_numero, c.sq_pais_passaporte, c.sq_etnia, c.sq_deficiencia,
                e.sq_modalidade_contrato, e.sq_contrato_colaborador
           from gp_colaborador                            a
                  inner      join co_pessoa               b on (a.sq_pessoa = b.sq_pessoa)
                  left outer join co_pessoa_fisica        c on (a.sq_pessoa = c.sq_pessoa)
                  left outer join gp_contrato_colaborador e on (a.sq_pessoa = e.sq_pessoa and
                                                                e.fim is null)
          where a.cliente  = p_cliente
            and ((p_chave  is null) or (p_chave is not null and a.sq_pessoa                    = p_chave))
            and (p_nome    is null or (p_nome        is not null and (b.nome_indice like '%'||acentos(p_nome)||'%' or b.nome_resumido_ind like '%'||acentos(p_nome)||'%')));
   ElsIf p_restricao = 'AFASTAMENTO' Then
      -- Recupera os colaboradores que estão ligados a um afastamento
      open p_result for 
         select distinct a.sq_pessoa chave, a.ctps_numero, a.ctps_serie, a.ctps_emissor, a.ctps_emissao_data,
                a.pis_pasep, a.pispasep_numero, a.pispasep_cadastr, a.te_numero, a.te_zona, a.te_secao,
                a.tipo_sangue, a.doador_sangue, a.doador_orgaos, a.reservista_csm, a.reservista_numero,
                a.observacoes, b.nome, b.nome_resumido, c.nascimento, c.rg_numero, c.rg_emissor, c.rg_emissao,
                c.cpf, c.sq_cidade_nasc, c.passaporte_numero, c.sq_pais_passaporte, c.sq_etnia, c.sq_deficiencia,
                e.sq_contrato_colaborador
           from gp_colaborador                          a
                inner      join co_pessoa               b on (a.sq_pessoa = b.sq_pessoa and
                                                              a.cliente   = b.sq_pessoa_pai)
                inner      join co_pessoa_fisica        c on (a.sq_pessoa = c.sq_pessoa)
                inner      join gp_contrato_colaborador e on (a.sq_pessoa = e.sq_pessoa and
                                                              e.fim is null)
                  inner    join gp_afastamento          f on (e.sq_contrato_colaborador = f.sq_contrato_colaborador)
          where a.cliente      = p_cliente 
            and (p_chave       is null or (p_chave    is not null and a.sq_pessoa     = p_chave))
            and (p_nome        is null or (p_nome     is not null and acentos(b.nome) like '%'||acentos(p_nome)||'%'));

   Elsif p_restricao = 'SELAFAST' Then
      -- Recupera todas ou um colaborador
      open p_result for 
         select d.sq_pessoa chave, d.nome_resumido, d.nome_resumido_ind, c.sq_contrato_colaborador
           from gp_tipo_afastamento                        a
                inner       join gp_afastamento_modalidade b on (a.sq_tipo_afastamento    = b.sq_tipo_afastamento)
                  inner     join gp_contrato_colaborador   c on (b.sq_modalidade_contrato = c.sq_modalidade_contrato and
                                                                 c.fim is null)
                    inner   join co_pessoa                 d on (c.sq_pessoa              = d.sq_pessoa)
                      inner join co_pessoa_fisica          e on (d.sq_pessoa              = e.sq_pessoa and
                                                                 e.sexo                   = case a.sexo when 'A' then e.sexo else a.sexo end
                                                                )
          where a.cliente             = p_cliente
            and a.sq_tipo_afastamento = p_chave_aux;
   End If;
end SP_GetGPColaborador;
/
