create or replace procedure SP_GetPD_Bilhete
   (p_chave     in number   default null,
    p_chave_aux in number   default null,
    p_inicio    in date     default null,
    p_fim       in date     default null,
    p_numero    in varchar2 default null,
    p_cia_trans in number   default null,
    p_tipo      in varchar2 default null,
    p_restricao in varchar2,
    p_result    out sys_refcursor) is
begin
   If p_restricao is null Then
      -- Recupera os bilhetes ligados a viagens
      open p_result for
         select a.sq_bilhete as chave, a.sq_siw_solicitacao, a.sq_cia_transporte, a.data, a.numero, a.trecho, a.valor_bilhete, a.valor_bilhete_cheio, a.valor_pta, 
                a.valor_taxa_embarque, a.rloc, a.classe, a.utilizado, a.faturado, a.observacao,
                case a.utilizado when 'I' then 'Integral' when 'P' then 'Parcial' when 'C' then 'Não utilizado' else 'Não informado' end as nm_utilizado,
                case a.faturado  when 'S' then 'Sim' else 'Não' end as nm_faturado,
                b.codigo_interno,
                c.codigo_interno as cd_pai,
                e.nome as nm_beneficiario,
                f.nome as nm_cia_transporte
           from pd_bilhete                     a
                left    join siw_solicitacao   b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
                  left  join siw_solicitacao   c on (b.sq_solic_pai       = c.sq_siw_solicitacao)
                left    join pd_missao         d on (a.sq_siw_solicitacao = d.sq_siw_solicitacao)
                  left  join co_pessoa         e on (d.sq_pessoa          = e.sq_pessoa)
                inner   join pd_cia_transporte f on (a.sq_cia_transporte  = f.sq_cia_transporte)
          where a.tipo               = coalesce(p_tipo,'S')
            and (p_chave             is null or (p_chave     is not null and a.sq_siw_solicitacao = p_chave))
            and (p_chave_aux         is null or (p_chave_aux is not null and a.sq_bilhete         = p_chave_aux))
            and (p_numero            is null or (p_numero    is not null and a.numero             = p_numero))
            and (p_cia_trans         is null or (p_cia_trans is not null and a.sq_cia_transporte  = p_cia_trans));
   End If;         
End SP_GetPD_Bilhete;
/
