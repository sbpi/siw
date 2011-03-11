create or replace procedure SP_PutDemandaConc
   (p_menu                in number,
    p_chave               in number,
    p_pessoa              in number,
    p_tramite             in number,
    p_inicio_real         in date      default null,
    p_fim_real            in date      default null,
    p_nota_conclusao      in varchar2  default null,
    p_custo_real          in number    default null,
    p_caminho             in varchar2  default null,
    p_tamanho             in number    default null,
    p_tipo                in varchar2  default null,
    p_nome_original       in varchar2  default null
   ) is
   w_chave_dem      number(18) := null;
   w_chave_arq      number(18) := null;
   w_solic          siw_solicitacao%rowtype;
   w_menu           siw_menu%rowtype;
   w_modulo         siw_modulo%rowtype;
   w_data_atual     date := sysdate;
   w_cliente        number(18);
   w_mod_pa         varchar2(1);
   w_chave_nova     number(18);
   w_codigo_interno varchar2(60);
   w_nu_guia        pa_documento_log.nu_guia%type;
   w_ano_guia       pa_documento_log.ano_guia%type;
   w_unidade_guia   number(18);
   
   cursor c_protocolo is
     select a.sq_especie_documento, a.sigla, a.sq_assunto, b.sq_menu,
            c.codigo_interno, trunc(c.inclusao) as inclusao, c.descricao,
            d.sq_unid_executora, 'N' as processo, 'N' as circular,
            e.sq_pessoa,
            f.sq_siw_tramite,
            g.despacho_arqsetorial
       from pa_especie_documento      a
            inner   join siw_menu     b on (a.cliente            = b.sq_pessoa and
                                            b.sigla              = 'PADCAD'
                                           )
              inner join siw_tramite  f on (b.sq_menu            = f.sq_menu and
                                            f.ordem              = 1
                                           )
            inner   join pa_parametro g on (a.cliente            = g.cliente),
            siw_solicitacao           c
            inner   join siw_menu     d on (c.sq_menu            = d.sq_menu)
            inner   join pd_missao    e on (c.sq_siw_solicitacao = e.sq_siw_solicitacao)
      where a.cliente            = w_cliente
        and a.sigla              = 'SOVI'
        and c.sq_siw_solicitacao = p_chave;
begin
  -- Recupera o cliente e os dados da solicitação, do menu e do módulo
  select * into w_solic  from siw_solicitacao where sq_siw_solicitacao = p_chave;
  select * into w_menu   from siw_menu        where sq_menu            = w_solic.sq_menu;
  select * into w_modulo from siw_modulo      where sq_modulo          = w_menu.sq_modulo;
  w_cliente := w_menu.sq_pessoa;
  
  -- Verifica se o cliente tem o módulo de protocolo e arquivo contratado
  select case count(*) when 0 then 'N' else 'S' end
    into w_mod_pa
    from siw_cliente_modulo a
         inner join siw_modulo b on (a.sq_modulo = b.sq_modulo)
   where a.sq_pessoa = w_cliente 
     and b.sigla     = 'PA';
  
   -- Recupera a chave do log
   select sq_siw_solic_log.nextval into w_chave_dem from dual;
   
   -- Insere registro na tabela de log da solicitacao
   Insert Into siw_solic_log 
      (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
       sq_siw_tramite,            data,               devolucao, 
       observacao
      )
   Values
      (w_chave_dem,               p_chave,            p_pessoa,
       p_tramite,                 w_data_atual,       'N',
       'Conclusão');
       
   -- Atualiza o registro da demanda com os dados da conclusão.
   Update gd_demanda set
      inicio_real     = coalesce(p_inicio_real,inicio_real),
      fim_real        = coalesce(p_fim_real,fim_real),
--      nota_conclusao  = coalesce(p_nota_conclusao,nota_conclusao),
      custo_real      = coalesce(p_custo_real,custo_real),
      concluida       = 'S',
      data_conclusao  = w_data_atual
   Where sq_siw_solicitacao = p_chave;

   -- Atualiza a situação da solicitação
   Update siw_solicitacao
      set sq_siw_tramite =
          (select sq_siw_tramite
             from siw_tramite
            where sq_menu = p_menu
              and Nvl(sigla, 'z') = 'AT')
      Where sq_siw_solicitacao = p_chave;

   -- Se foi informado um arquivo, grava.
   If p_caminho is not null Then
      -- Recupera a próxima chave
      select sq_siw_arquivo.nextval into w_chave_arq from dual;
       
      -- Insere registro em SIW_ARQUIVO
      insert into siw_arquivo (sq_siw_arquivo, cliente, nome, descricao, inclusao, tamanho, tipo, caminho, nome_original)
      (select w_chave_arq, sq_pessoa_pai, p_chave||' - Anexo', null, w_data_atual, 
              p_tamanho,   p_tipo,        p_caminho, p_nome_original
         from co_pessoa a
        where a.sq_pessoa = p_pessoa
      );
      
      -- Insere registro em SIW_SOLIC_LOG_ARQ
      insert into siw_solic_log_arq (sq_siw_solic_log, sq_siw_arquivo)
      values (w_chave_dem, w_chave_arq);
   End If;
   
   If w_cliente = 10135 and w_mod_pa = 'S' Then
      for crec in c_protocolo loop
          -- Cria o documento no sistema de protocolo
          sp_putdocumentogeral(p_operacao           => 'I',
                               p_chave              => null,
                               p_copia              => null,
                               p_menu               => crec.sq_menu,
                               p_unidade            => crec.sq_unid_executora,
                               p_unid_autua         => crec.sq_unid_executora,
                               p_solicitante        => p_pessoa,
                               p_cadastrador        => p_pessoa,
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
                               p_data_recebimento   => crec.inclusao,
                               p_interno            => 'S',
                               p_pessoa_origem      => null,
                               p_pessoa_interes     => crec.sq_pessoa,
                               p_cidade             => null,
                               p_assunto            => crec.sq_assunto,
                               p_descricao          => crec.descricao,
                               p_chave_nova         => w_chave_nova,
                               p_codigo_interno     => w_codigo_interno
                              );
          -- Envia para arquivamento setorial
          sp_putdocumentoenvio(p_menu               => crec.sq_menu,
                               p_chave              => w_chave_nova,
                               p_pessoa             => p_pessoa,
                               p_tramite            => crec.sq_siw_tramite,
                               p_interno            => 'S',
                               p_unidade_origem     => crec.sq_unid_executora,
                               p_unidade_destino    => crec.sq_unid_executora,
                               p_pessoa_destino     => null,
                               p_tipo_despacho      => crec.despacho_arqsetorial,
                               p_prefixo            => null,
                               p_numero             => null,
                               p_ano                => null,
                               p_despacho           => 'Envio automatizado para arquivamento setorial.',
                               p_emite_aviso        => 'N',
                               p_dias_aviso         => null,
                               p_retorno_limite     => null,
                               p_pessoa_externa     => null,
                               p_unidade_externa    => null,
                               p_nu_guia            => w_nu_guia,
                               p_ano_guia           => w_ano_guia,
                               p_unidade_autuacao   => w_unidade_guia
                              );
          -- Executa arquivamento setorial
          sp_putdocumentoarqset(p_chave             => w_chave_nova,
                                p_usuario           => p_pessoa,
                                p_observacao        => p_nota_conclusao
                               );
          -- Vincula a viagem com o protocolo
          update siw_solicitacao set protocolo_siw = w_chave_nova where sq_siw_solicitacao = p_chave;
      end loop;
   End If;
end SP_PutDemandaConc;
/
