create or replace procedure SP_PutImpostoDoc
   (p_operacao            in varchar2,
    p_documento           in number,
    p_imposto             in number,
    p_solic_retencao      in number   default null,
    p_solic_imposto       in number   default null,
    p_aliquota_total      in number   default null,
    p_aliquota_retencao   in number   default null,
    p_aliquota_normal     in number   default null,
    p_valor_total         in number   default null,
    p_valor_retencao      in number   default null,
    p_valor_normal        in number   default null,
    p_quitacao_retencao   in date     default null,
    p_quitacao_imposto    in date     default null
   ) is

   w_menu           siw_menu%rowtype;
   w_chave_nova     siw_solicitacao.sq_siw_solicitacao%type;
   w_codigo_interno varchar2(60);
   w_nu_guia        pa_documento_log.nu_guia%type;
   w_ano_guia       pa_documento_log.ano_guia%type;
   w_unidade_guia   number(18);

   cursor c_protocolo is
     select a.sq_especie_documento, a.sigla, a.sq_assunto, b.sq_menu,  
            c.sq_siw_solicitacao, c.protocolo_siw, a2.numero, a2.data, 
            case substr(d.sigla,3,1) when 'D' then 'Pagamento de ' else 'Recebimento de ' end||' '||a1.nome||' de '||c3.nome||' no valor de R$ '||fValor(a2.valor,'T')||' para '||l.nome||'.' as descricao, 'N' as processo, 'N' as circular,
            c.cadastrador as sq_pessoa, c.sq_unidade,
            f.sq_siw_tramite,
            f1.sq_siw_tramite as novo_tramite, h.sq_tipo_despacho as sq_despacho, h.sigla as sg_despacho,
            f1.nome as nm_novo_tramite
       from pa_especie_documento            a
            inner   join fn_tipo_documento a1 on (a.sq_especie_documento = a1.sq_especie_documento)
              inner join fn_lancamento_doc a2 on (a1.sq_tipo_documento   = a2.sq_tipo_documento)
            inner   join siw_menu           b on (a.cliente              = b.sq_pessoa and
                                                  b.sigla                = 'PADCAD'
                                                 )
              inner join siw_tramite        f on (b.sq_menu              = f.sq_menu and
                                                  f.ordem                = 1
                                                 )
              inner join siw_tramite       f1 on (b.sq_menu              = f1.sq_menu and
                                                  f1.ordem               = 2
                                                 )
            inner   join pa_tipo_despacho   h on (b.sq_pessoa            = h.cliente and
                                                  h.sigla                = 'ENCAMINHAR'
                                                 )
            inner   join siw_solicitacao    c on (a2.sq_siw_solicitacao  = c.sq_siw_solicitacao)
              inner join fn_imposto_doc    c2 on (c.sq_siw_solicitacao   = c2.solic_imposto)
              inner join fn_imposto        c3 on (c2.sq_imposto          = c3.sq_imposto)
              inner join siw_menu           d on (c.sq_menu              = d.sq_menu)
              inner join fn_lancamento      k on (c.sq_siw_solicitacao   = k.sq_siw_solicitacao)
              inner join co_pessoa          l on (k.pessoa               = l.sq_pessoa)
            inner   join siw_cliente_modulo i on (a.cliente              = i.sq_pessoa)
              inner join siw_modulo         j on (i.sq_modulo            = j.sq_modulo and
                                                  j.sigla                = 'PA'
                                                 )
      where c2.sq_lancamento_doc = p_documento
        and c2.sq_imposto        = p_imposto;
begin
   If p_operacao = 'I' Then -- Inclusão
      insert into fn_imposto_doc
        (sq_lancamento_doc, sq_imposto,    solic_retencao,   solic_imposto,   aliquota_total,      aliquota_retencao, 
         aliquota_normal,   valor_total,   valor_retencao,   valor_normal,    quitacao_retencao,   quitacao_imposto)
      values
        (p_documento,       p_imposto,     p_solic_retencao, p_solic_imposto, p_aliquota_total,    p_aliquota_retencao, 
         p_aliquota_normal, p_valor_total, p_valor_retencao, p_valor_normal,  p_quitacao_retencao, p_quitacao_imposto);
   Elsif p_operacao = 'A' Then -- Alteração
      update fn_imposto_doc
         set solic_retencao    = p_solic_retencao,
             solic_imposto     = p_solic_imposto,
             aliquota_total    = p_aliquota_total,
             aliquota_retencao = p_aliquota_retencao,
             aliquota_normal   = p_aliquota_normal,
             valor_total       = p_valor_total,
             valor_retencao    = p_valor_retencao,
             valor_normal      = p_valor_normal,
             quitacao_retencao = p_quitacao_retencao,
             quitacao_imposto  = p_quitacao_imposto
       where sq_lancamento_doc = p_documento
         and sq_imposto        = p_imposto;
   Elsif p_operacao = 'E' Then -- Exclusão
      delete fn_imposto_doc where sq_lancamento_doc = p_documento and sq_imposto = p_imposto;
   End If;
   
   If p_operacao <> 'E' Then

      -- Recupera os dados do serviço
      select a.* into w_menu 
        from siw_menu                       a
             inner   join siw_solicitacao   b on (a.sq_menu            = b.sq_menu)
               inner join fn_lancamento_doc c on (b.sq_siw_solicitacao = c.sq_siw_solicitacao)
       where c.sq_lancamento_doc = p_documento;

      for crec in c_protocolo loop
          -- Cria o documento no sistema de protocolo
          sp_putdocumentogeral(p_operacao           => case when crec.protocolo_siw is null then 'I' else 'A' end,
                               p_chave              => crec.protocolo_siw,
                               p_copia              => null,
                               p_menu               => crec.sq_menu,
                               p_unidade            => crec.sq_unidade,
                               p_unid_autua         => crec.sq_unidade,
                               p_solicitante        => crec.sq_pessoa,
                               p_cadastrador        => crec.sq_pessoa,
                               p_solic_pai          => null,
                               p_vinculo            => null,
                               p_processo           => 'N',
                               p_circular           => 'N',
                               p_especie_documento  => crec.sq_especie_documento,
                               p_doc_original       => crec.numero,
                               p_inicio             => crec.data,
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
             -- Envia para tramitação
             sp_putdocumentoenvio(p_menu               => crec.sq_menu,
                                  p_chave              => w_chave_nova,
                                  p_pessoa             => crec.sq_pessoa,
                                  p_tramite            => crec.sq_siw_tramite,
                                  p_interno            => 'S',
                                  p_unidade_origem     => crec.sq_unidade,
                                  p_unidade_destino    => crec.sq_unidade,
                                  p_pessoa_destino     => null,
                                  p_tipo_despacho      => crec.sq_despacho,
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
 
             -- Recebe automaticamente
             sp_putdocumentoreceb(p_operacao 	 => 'R',
                                  p_pessoa     => crec.sq_pessoa,
                                  p_unid_autua => crec.sq_unidade,
                                  p_nu_guia    => w_nu_guia,
                                  p_ano_guia   => w_ano_guia,
                                  p_observacao => null);
 
          End If;
          
          -- Vincula o pagamento com o protocolo
          update siw_solicitacao set protocolo_siw = w_chave_nova where sq_siw_solicitacao = crec.sq_siw_solicitacao;
          update fn_lancamento   
             set processo = (select prefixo||'.'||substr(1000000+numero_documento,2,6)||'/'||ano||'-'||substr(100+digito,2,2)
                               from pa_documento
                              where sq_siw_solicitacao = w_chave_nova
                            )
          where sq_siw_solicitacao = crec.sq_siw_solicitacao;                   
      end loop;
   End If;
   
end SP_PutImpostoDoc;
/
