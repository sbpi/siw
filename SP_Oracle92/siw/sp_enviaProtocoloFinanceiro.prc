create or replace procedure sp_enviaProtocoloFinanceiro(p_cliente in number, p_lancamento in number default null, p_todos in varchar2 default null) is
   w_chave_nova     siw_solicitacao.sq_siw_solicitacao%type;
   w_codigo_interno varchar2(60);
   w_nu_guia        pa_documento_log.nu_guia%type;
   w_ano_guia       pa_documento_log.ano_guia%type;
   w_unidade_guia   number(18);
  
   cursor c_protocolo is
     select a.sq_especie_documento, a.sigla, a.sq_assunto, b.sq_menu, b.sq_unid_executora, 
            c.sq_siw_solicitacao, c.protocolo_siw, c.codigo_interno, trunc(c.inclusao) as inclusao, 
            c.descricao, 'N' as processo, 'N' as circular,
            e.pessoa    as sq_pessoa, c.sq_unidade,
            e2.sq_pessoa as sq_usuario,
            f.sq_siw_tramite, 
            g.sq_unidade as unidade_destino,
            h.sq_tipo_despacho as novo_tramite, h.sigla as sg_despacho,
            'contabilização.' as nm_novo_tramite
       from pa_especie_documento            a
            inner   join siw_menu           b on (a.cliente              = b.sq_pessoa and
                                                  b.sigla                = 'PADCAD'
                                                 )
              inner join siw_tramite        f on (b.sq_menu              = f.sq_menu and
                                                  f.ordem                = 1
                                                 )
            inner   join siw_cliente_modulo i on (a.cliente              = i.sq_pessoa)
              inner join siw_modulo         j on (i.sq_modulo            = j.sq_modulo and
                                                  j.sigla                = 'PA' -- Cliente deve ter módulo de protocolo
                                                 ),
            eo_unidade                      g,
            pa_tipo_despacho                h,
            siw_solicitacao                 c
            inner   join siw_tramite       c1 on (c.sq_siw_tramite       = c1.sq_siw_tramite and c1.sigla = 'AT')
            inner   join siw_menu           d on (c.sq_menu              = d.sq_menu and
                                                  d.sigla                = 'FNDREEMB' -- Aplica-se apenas a reembolso de despesas
                                                 )
            inner   join fn_lancamento      e on (c.sq_siw_solicitacao   = e.sq_siw_solicitacao and e.quitacao < trunc(sysdate))
              inner join (select w.sq_siw_solicitacao, max(w.sq_siw_solic_log) chave
                            from siw_solic_log                w
                                 inner   join siw_solicitacao x on (w.sq_siw_solicitacao = x.sq_siw_solicitacao)
                                   inner join siw_menu        y on (x.sq_menu            = y.sq_menu and
                                                                    y.sigla              = 'FNDREEMB' and
                                                                    y.sq_pessoa          = p_cliente
                                                                   )
                                 inner   join siw_tramite     z on (w.sq_siw_tramite     = z.sq_siw_tramite and z.sigla = 'EE')
                          group by w.sq_siw_solicitacao
                         )                 e1 on (e.sq_siw_solicitacao   = e1.sq_siw_solicitacao)
              inner join siw_solic_log     e2 on (e1.chave               = e2.sq_siw_solic_log)
      where a.cliente                = p_cliente
        and ((coalesce(p_todos,'-') <> 'TODOS' and c.sq_siw_solicitacao = p_lancamento) or
             (coalesce(p_todos,'-') = 'TODOS'  and c.protocolo_siw      is null)
            )
        and a.sigla                  = 'REDE' -- Reembolso de despesas
        and upper(g.sigla)           = 'CONTABILIDADE' and g.ativo = 'S' and g.externo  ='N' -- Destino é a unidade de contabilidade
        and h.sigla                  = 'ENCAMINHAR'; -- Despacho é ENCAMINHAR

begin
  for crec in c_protocolo loop
      -- Cria o documento no sistema de protocolo
      sp_putdocumentogeral(p_operacao           => case when crec.protocolo_siw is null then 'I' else 'A' end,
                           p_chave              => crec.protocolo_siw,
                           p_copia              => null,
                           p_menu               => crec.sq_menu,
                           p_unidade            => crec.sq_unidade,
                           p_unid_autua         => case when crec.protocolo_siw is null then crec.sq_unid_executora else null end,
                           p_solicitante        => crec.sq_usuario,
                           p_cadastrador        => crec.sq_usuario,
                           p_solic_pai          => null,
                           p_vinculo            => null,
                           p_processo           => 'N',
                           p_circular           => 'N',
                           p_especie_documento  => crec.sq_especie_documento,
                           p_doc_original       => crec.codigo_interno,
                           p_inicio             => crec.inclusao,
                           p_volumes            => null,
                           p_dt_autuacao        => null,
                           p_copias             => null,
                           p_natureza_documento => null,
                           p_fim                => null,
                           p_data_recebimento   => trunc(sysdate),
                           p_interno            => 'S',
                           p_pessoa_origem      => null,
                           p_pessoa_interes     => crec.sq_pessoa,
                           p_cidade             => null,
                           p_assunto            => crec.sq_assunto,
                           p_descricao          => crec.descricao,
                           p_chave_nova         => w_chave_nova,
                           p_codigo_interno     => w_codigo_interno
                          );
      If crec.protocolo_siw is null Then
         -- Envia para contabilidade somente se for inclusão
         sp_putdocumentoenvio(
                           p_menu               => crec.sq_menu,
                           p_chave              => w_chave_nova,
                           p_pessoa             => crec.sq_usuario,
                           p_tramite            => crec.sq_siw_tramite,
                           p_interno            => 'S',
                           p_unidade_origem     => crec.sq_unidade,
                           p_unidade_destino    => crec.unidade_destino,
                           p_pessoa_destino     => null,
                           p_tipo_despacho      => crec.novo_tramite,
                           p_prefixo            => null,
                           p_numero             => null,
                           p_ano                => null,
                           p_despacho           => 'Envio automatizado para '||crec.nm_novo_tramite||'.',
                           p_emite_aviso        => 'N',
                           p_dias_aviso         => null,
                           p_retorno_limite     => null,
                           p_pessoa_externa     => null,
                           p_unidade_externa    => null,
                           p_nu_guia            => w_nu_guia,
                           p_ano_guia           => w_ano_guia,
                           p_unidade_autuacao   => w_unidade_guia
                          );
      
         -- Vincula o reembolso com o protocolo
         update siw_solicitacao set protocolo_siw = w_chave_nova where sq_siw_solicitacao = crec.sq_siw_solicitacao;
      End If;
  end loop;
end sp_enviaProtocoloFinanceiro;
/
