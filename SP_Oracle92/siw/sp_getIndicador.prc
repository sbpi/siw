create or replace procedure sp_getIndicador
   (p_cliente        in  number,
    p_chave          in  number default null,
    p_ativo          in varchar2 default null,
    p_pais           in  number default null,
    p_regiao         in  number default null,
    p_uf             in varchar2 default null,
    p_cidade         in  number default null,
    p_afe_i          in date    default null,
    p_afe_f          in date    default null,
    p_ref_i          in date    default null,
    p_ref_f          in date    default null,
    p_restricao      in varchar2 default null,
    p_result         out sys_refcursor) is
begin
   If p_restricao is null Then
      -- Recupera as indicadors de planejamento
      open p_result for 
         select a.sq_eoindicador as chave, a.cliente, a.nome, a.sigla, a.descricao, a.forma_afericao, 
                a.fonte_comprovacao, a.ciclo_afericao, a.ativo,
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo,
                to_char(b.data,'dd/mm/yyyy, hh24:mi:ss') as phpdt_afericao,
                c.sq_unidade_medida, c.nome as nm_unidade_medida,
                d.sq_tipo_indicador, d.nome as nm_tipo_indicador
           from eo_indicador a
                inner join (select sq_eoindicador, max(data_afericao) as data
                              from eo_indicador_afericao
                            group by sq_eoindicador
                           )                 b on (a.sq_eoindicador    = b.sq_eoindicador)
                inner join co_unidade_medida c on (a.sq_unidade_medida = c.sq_unidade_medida)
                inner join eo_tipo_indicador d on (a.sq_tipo_indicador = d.sq_tipo_indicador)
          where a.cliente = p_cliente 
            and ((p_chave is null) or (p_chave is not null and a.sq_eoindicador = p_chave))
            and ((p_ativo is null) or (p_ativo is not null and a.ativo          = p_ativo));         
   Elsif p_restricao = 'AFERICAO' Then
      -- Recupera as indicadors de planejamento
      open p_result for 
         select a.sq_eoindicador as chave, a.cliente, a.nome, a.sigla, a.descricao, a.forma_afericao, 
                a.fonte_comprovacao, a.ciclo_afericao, a.ativo,
                case a.ativo when 'S' then 'Sim' else 'Não' end as nm_ativo,
                b.data_afericao, b.referencia_inicio, b.referencia_fim, b.base_geografica,
                b.fonte, b.valor,
                to_char(b.data_afericao,'dd/mm/yyyy, hh24:mi:ss') as phpdt_afericao,
                to_char(b.referencia_inicio,'dd/mm/yyyy, hh24:mi:ss') as phpdt_inicio,
                to_char(b.referencia_fim,'dd/mm/yyyy, hh24:mi:ss') as phpdt_fim,
                case base_geografica
                     when 1 then 'Nacional'
                     when 2 then 'Regional'
                     when 3 then 'Estadual'
                     when 4 then 'Municipal'
                     when 5 then 'Organizacional'
                end as nm_base_geografica,
                c.sq_pais, c.nome as nm_pais,
                d.sq_regiao, d.nome as nm_regiao,
                e.co_uf,
                f.sq_cidade, f.nome as nm_cidade
           from eo_indicador                      a
                left   join eo_indicador_afericao b on (a.sq_eoindicador = b.sq_eoindicador)
                  left join co_pais               c on (b.sq_pais        = c.sq_pais)
                  left join co_regiao             d on (b.sq_regiao      = d.sq_regiao)
                  left join co_uf                 e on (b.sq_pais        = e.sq_pais and
                                                        b.co_uf          = e.co_uf
                                                       )
                  left join co_cidade             f on (b.sq_cidade      = f.sq_cidade)
          where a.cliente = p_cliente 
            and ((p_chave is null)  or (p_chave  is not null and a.sq_eoindicador = p_chave))
            and ((p_ativo is null)  or (p_ativo  is not null and a.ativo = p_ativo))
            and ((p_pais is null)   or (p_pais   is not null and b.sq_pais = p_pais))
            and ((p_regiao is null) or (p_regiao is not null and b.sq_regiao = p_regiao))
            and ((p_uf is null)     or (p_uf     is not null and b.sq_pais = p_pais and b.co_uf = p_uf))
            and ((p_cidade is null) or (p_cidade is not null and b.sq_cidade = p_cidade))
            and ((p_afe_i is null)  or (p_afe_i  is not null and b.data_afericao between p_afe_i and p_afe_f))
            and ((p_ref_i is null)  or (p_ref_i  is not null and (b.referencia_inicio between p_afe_i and p_afe_f) or
                                                                 (b.referencia_fim    between p_afe_i and p_afe_f) or
                                                                 (p_ref_i             between referencia_inicio and referencia_fim) or
                                                                 (p_ref_f             between referencia_inicio and referencia_fim)
                                       )
                );
   End If;
end sp_getIndicador;
/
