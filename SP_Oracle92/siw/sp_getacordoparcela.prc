create or replace procedure SP_GetAcordoParcela
   (p_chave       in number   default null,
    p_chave_aux   in number   default null,
    p_restricao   in varchar2 default null,
    p_outra_parte in varchar2 default null,
    p_dt_ini      in date     default null,
    p_dt_fim      in date     default null,
    p_usuario     in number   default null,
    p_fase        in varchar2 default null,
    p_menu        in number   default null,
    p_result      out sys_refcursor) is
begin
   -- Recupera os dados de uma parcela ou as parcelas de um acordo
   -- dependendo dos parâmetros informados
   If p_restricao is null Then
      open p_result for 
         select a.sq_acordo_parcela, a.sq_siw_solicitacao, a.ordem, a.emissao, a.vencimento, a.quitacao,
                a.documento_interno, a.documento_externo, a.observacao, a.valor,
                b.sq_siw_solicitacao sq_lancamento, b.codigo_interno cd_lancamento, 
                b.vencimento dt_lancamento, b.valor vl_lancamento
           from ac_acordo_parcela             a
                left outer join (select x.sq_acordo_parcela, x.sq_siw_solicitacao, y.valor,
                                        x.codigo_interno, x.vencimento, 
                                        z.sigla sg_tramite
                                  from fn_lancamento                x
                                       inner   join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                         inner join siw_tramite     z on (y.sq_siw_tramite     = z.sq_siw_tramite and
                                                                          nvl(z.sigla,'-')     <> 'CA'
                                                                         )
                                )             b  on (a.sq_acordo_parcela  = b.sq_acordo_parcela)
          where (p_chave     is null or (p_chave     is not null and a.sq_siw_solicitacao = p_chave))
            and (p_chave_aux is null or (p_chave_aux is not null and a.sq_acordo_parcela  = p_chave_aux));
   Elsif p_restricao = 'CADASTRO' Then
      open p_result for 
        select a.sq_siw_solicitacao, a.sq_solic_pai, a.solicitante, a.sq_unidade, a.sq_cc, 
               b.codigo_interno cd_acordo, b.sq_forma_pagamento, b.sq_tipo_pessoa, b.outra_parte,
               b.objeto,
               c.sq_acordo_parcela, c.ordem, c.vencimento, c.valor, c.quitacao,
               g.nome_resumido,
               case when h.sq_pessoa is null then i.cnpj else h.cpf end cnpjcpf
          from siw_solicitacao                          a
               inner            join ac_acordo          b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
                 inner          join co_pessoa          g on (b.outra_parte        = g.sq_pessoa)
                   left outer   join co_pessoa_fisica   h on (g.sq_pessoa          = h.sq_pessoa)
                   left outer   join co_pessoa_juridica i on (g.sq_pessoa          = i.sq_pessoa)
                 inner          join siw_tramite        j on (a.sq_siw_tramite     = j.sq_siw_tramite and
                                                              0                    <> InStr(p_fase,Nvl(j.sigla,'-'))
                                                             )
                 inner          join (select sq_siw_solicitacao, acesso(sq_siw_solicitacao, p_usuario) acesso
                                        from siw_solicitacao
                                     )                    k on (a.sq_siw_solicitacao = k.sq_siw_solicitacao and
                                                                0                    < k.acesso
                                                               )
                 inner          join ac_acordo_parcela    c on (b.sq_siw_solicitacao = c.sq_siw_solicitacao)
                   left outer   join (select x.sq_acordo_parcela, z.sigla sg_tramite
                                        from fn_lancamento                x
                                             inner   join siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                               inner join siw_tramite     z on (y.sq_siw_tramite     = z.sq_siw_tramite and
                                                                                'CA'                 <> Nvl(z.sigla,'CA')
                                                                               )
                                     )               d  on (c.sq_acordo_parcela  = d.sq_acordo_parcela)
         where d.sq_acordo_parcela is null
           and a.sq_menu         = p_menu
           and (p_chave       is null or (p_chave       is not null and a.sq_siw_solicitacao = p_chave))
           and (p_chave_aux   is null or (p_chave_aux   is not null and c.sq_acordo_parcela  = p_chave_aux))
           and (p_outra_parte is null or (p_outra_parte is not null and (acentos(g.nome,null) like '%'||acentos(p_outra_parte,null)||'%' or acentos(g.nome_resumido,null) like '%'||acentos(p_outra_parte,null)||'%')))
           and (p_dt_ini      is null or (p_dt_ini      is not null and c.vencimento         between p_dt_ini and p_dt_fim));
   End If;
End SP_GetAcordoParcela;
/

