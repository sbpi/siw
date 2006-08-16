create or replace procedure SP_GetGPContrato
   (p_cliente                 in number,
    p_chave                   in number    default null,
    p_sq_pessoa               in number    default null,
    p_modalidade_contrato     in number    default null,
    p_unidade_lotacao         in number    default null,
    p_filhos_lotacao          in varchar2  default null,
    p_unidade_exercicio       in number    default null,
    p_filhos_exercicio        in varchar2  default null,
    p_afastamento             in number    default null,
    p_dt_ini                  in date      default null,
    p_dt_fim                  in date      default null,
    p_chave_aux               in number    default null,
    p_restricao               in varchar2  default null,
    p_result    out siw.sys_refcursor) is
    
    l_item        varchar2(18);
    l_afastamento varchar2(200) := p_afastamento ||',';
    x_afastamento varchar2(200) := '';
    
begin
   
   If p_afastamento is not null Then
      Loop
         l_item  := Trim(substr(l_afastamento,1,Instr(l_afastamento,',')-1));
         If Length(l_item) > 0 Then
            x_afastamento := x_afastamento||','''||to_number(l_item)||'''';
         End If;
         l_afastamento := substr(l_afastamento,Instr(l_afastamento,',')+1,200);
         Exit when l_afastamento is null;
      End Loop;
      x_afastamento := substr(x_afastamento,2,200);
   End If;
   
   If p_restricao is null Then
      -- Recupera todos ou um colaborador
      open p_result for 
         select e.sq_contrato_colaborador chave, a.sq_pessoa, a.ctps_numero, a.ctps_serie, a.ctps_emissor, a.ctps_emissao_data,
                a.pis_pasep, a.pispasep_numero, a.pispasep_cadastr, a.te_numero, a.te_zona, a.te_secao,
                a.tipo_sangue, a.doador_sangue, a.doador_orgaos, a.reservista_csm, a.reservista_numero, a.observacoes, 
                b.sq_tipo_vinculo, b.nome, b.nome_resumido, c.nascimento, c.rg_numero, c.rg_emissor, c.rg_emissao,
                c.cpf, c.sq_cidade_nasc, c.passaporte_numero, c.sq_pais_passaporte, c.sq_etnia, c.sq_deficiencia,
                e.matricula, g.nome nm_exercicio, e.inicio, e.fim,
                e.sq_posto_trabalho, e.sq_unidade_lotacao, e.sq_unidade_exercicio, e.sq_modalidade_contrato,
                e.matricula, e.sq_localizacao,
                h.sigla||' ('||g.nome||')' local, g.ramal, h.nome nm_unidade_exercicio, h.sigla sg_unidade_exercicio,
                i.nome nm_modalidade_contrato, j.nome nm_posto_trabalho, 
                l.nome nm_unidade_lotacao, l.sigla sg_unidade_lotacao
           from gp_colaborador            a,
                co_pessoa                 b,
                co_pessoa_fisica          c,
                gp_contrato_colaborador   e,
                eo_localizacao            g,
                eo_unidade                h,
                gp_modalidade_contrato    i,
                gp_afastamento            f,
                eo_posto_trabalho         j,
                eo_unidade                l
          where (a.sq_pessoa               = b.sq_pessoa and
                 a.cliente                 = b.sq_pessoa_pai)
            and (a.sq_pessoa               = c.sq_pessoa)
            and (a.sq_pessoa               = e.sq_pessoa)
            and (e.sq_localizacao          = g.sq_localizacao          (+))
            and (g.sq_unidade              = h.sq_unidade              (+))
            and (e.sq_modalidade_contrato  = i.sq_modalidade_contrato  (+))
            and (e.sq_contrato_colaborador = f.sq_contrato_colaborador (+))
            and (e.sq_posto_trabalho       = j.sq_posto_trabalho)
            and (e.sq_unidade_lotacao      = l.sq_unidade)
            and a.cliente                  = p_cliente 
            and (p_chave                   is null or (p_chave               is not null and e.sq_contrato_colaborador = p_chave))
            and (p_sq_pessoa               is null or (p_sq_pessoa           is not null and a.sq_pessoa               = p_sq_pessoa))
            and (p_modalidade_contrato     is null or (p_modalidade_contrato is not null and e.sq_modalidade_contrato  = p_modalidade_contrato))
            and (p_unidade_lotacao         is null or (p_unidade_lotacao     is not null and ((p_filhos_lotacao   is null and e.sq_unidade_lotacao   = p_unidade_lotacao)   or (p_filhos_lotacao   is not null and e.sq_unidade_lotacao in (select sq_unidade 
                                                                                                                                                                                                                                  from eo_unidade
                                                                                                                                                                                                                                start with sq_unidade = p_unidade_lotacao
                                                                                                                                                                                                                                connect by prior sq_unidade = sq_unidade_pai)))))
            and (p_unidade_exercicio   is null or (p_unidade_exercicio   is not null and ((p_filhos_exercicio is null and e.sq_unidade_exercicio = p_unidade_exercicio) or (p_filhos_exercicio is not null and e.sq_unidade_exercicio in (select sq_unidade 
                                                                                                                                                                                                                                  from eo_unidade
                                                                                                                                                                                                                                start with sq_unidade = p_unidade_exercicio
                                                                                                                                                                                                                                connect by prior sq_unidade = sq_unidade_pai)))))
           and (p_afastamento          is null or (p_afastamento         is not null and f.sq_tipo_afastamento in (x_afastamento)))
           and (p_dt_ini               is null or (p_dt_ini              is not null and ((f.inicio_data           between p_dt_ini      and p_dt_fim)   or
                                                                                          (Nvl(f.fim_data,sysdate) between p_dt_ini      and p_dt_fim)   or
                                                                                          (p_dt_ini                between f.inicio_data and Nvl(f.fim_data,sysdate)) or
                                                                                          (p_dt_fim                between f.inicio_data and Nvl(f.fim_data,sysdate))
                                                                                         )
                                                   )
                );
   End If;
end SP_GetGPContrato;
/
