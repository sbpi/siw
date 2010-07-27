create or replace procedure SP_GetPD_Fatura
   (p_cliente             in  number,
    p_agencia             in  number   default null,
    p_fatura              in  number   default null,
    p_bilhete             in  number   default null,
    p_numero_fat          in  varchar2 default null,
    p_arquivo             in  number   default null,
    p_cia_trans           in  number   default null,
    p_solic_viagem        in  number   default null,
    p_solic_pai           in  number   default null,
    p_numero_bil          in  varchar2 default null,
    p_ini_dec             in  date     default null,
    p_fim_dec             in  date     default null,
    p_ini_emifat          in  date     default null,
    p_fim_emifat          in  date     default null,
    p_ini_ven             in  date     default null,
    p_fim_ven             in  date     default null,
    p_ini_emibil          in  date     default null,
    p_fim_emibil          in  date     default null,
    p_restricao           in  varchar2 default null,
    p_result              out sys_refcursor) is
begin
   If p_restricao is null Then
      -- Recupera faturas de bilhetes aéreos
      open p_result for
         select a.sq_fatura_agencia, a.sq_arquivo_eletronico, a.agencia_viagem, a.numero as nr_fatura, a.fim_decendio, 
                a.emissao as emissao_fat, a.vencimento, a.valor, a.registros as reg_fatura, a.importados as imp_fatura, 
                a.rejeitados as rej_fatura, 
                case a.tipo when 0 then 'Bilhetes aéreos' when 1 then 'Hospedagem/Locação/Seguro' else null end as tp_fatura,
                b.data_importacao, b.data_arquivo, b.registros as reg_arquivo, b.importados as imp_arquivo, 
                b.rejeitados as rej_arquivo, b.sq_pessoa as sq_resp_imp, b.arquivo_recebido, b.arquivo_registro,
                to_char(b.data_importacao, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data_importacao,
                to_char(b.data_arquivo, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data_arquivo,
                case b.rejeitados when 0 then 'Completa' else 'Parcial' end as nm_situacao_imp,
                b1.nome as nm_recebido, b1.tamanho as tm_recebido, b1.tipo as tp_recebido, b1.caminho as cm_recebido, b1.sq_siw_arquivo as chave_recebido,
                b2.nome as nm_result,   b2.tamanho as tm_result,   b2.tipo as tp_result,   b2.caminho as cm_result,   b2.sq_siw_arquivo as chave_result,
                b3.nome as nm_resp_imp, b3.nome_resumido as nm_resumido_resp_imp,
                c.sq_solic_pai, c.cd_solic_pai,
                d.nome as nm_agencia, d.nome_resumido as nm_agencia_res
           from pd_fatura_agencia                         a
                inner         join pd_arquivo_eletronico  b  on (a.sq_arquivo_eletronico = b.sq_arquivo_eletronico)
                  inner       join siw_arquivo            b1 on (b.arquivo_recebido      = b1.sq_siw_arquivo)
                  inner       join siw_arquivo            b2 on (b.arquivo_registro      = b2.sq_siw_arquivo)
                  inner       join co_pessoa              b3 on (b.sq_pessoa             = b3.sq_pessoa)
                inner         join (select distinct w.sq_fatura_agencia, y.sq_siw_solicitacao as sq_solic_pai, y.codigo_interno as cd_solic_pai
                                      from pd_bilhete                   w 
                                           left    join siw_solicitacao x on (w.sq_siw_solicitacao   = x.sq_siw_solicitacao)
                                             left  join siw_solicitacao y on (x.sq_solic_pai         = y.sq_siw_solicitacao)
                                     where w.tipo = 'P'
                                   )                      c  on (a.sq_fatura_agencia     = c.sq_fatura_agencia)
                inner         join co_pessoa              d  on (a.agencia_viagem        = d.sq_pessoa)
          where b.cliente            = p_cliente
            and (p_arquivo           is null or (p_arquivo      is not null and p_arquivo            = b.sq_arquivo_eletronico))
            and (p_solic_pai         is null or (p_solic_pai    is not null and p_solic_pai           = coalesce(c.sq_solic_pai,0)))
            and (p_fatura            is null or (p_fatura       is not null and a.sq_fatura_agencia   = p_fatura))
            and (p_agencia           is null or (p_agencia      is not null and a.agencia_viagem      = p_agencia))
            and (p_numero_fat        is null or (p_numero_fat   is not null and a.numero              = p_numero_fat))
            and (p_ini_dec           is null or (p_ini_dec      is not null and (a.inicio_decendio    between p_ini_dec         and p_fim_dec or
                                                                                 a.fim_decendio       between p_ini_dec         and p_fim_dec or
                                                                                 p_ini_dec            between a.inicio_decendio and a.fim_decendio or
                                                                                 p_fim_dec            between a.inicio_decendio and a.fim_decendio
                                                                                )
                                                )
                )
            and (p_ini_emifat        is null or (p_ini_emifat   is not null and (a.emissao            between p_ini_emifat      and p_fim_emifat)))
            and (p_ini_ven           is null or (p_ini_ven      is not null and (a.vencimento         between p_ini_ven         and p_fim_ven)));
   Elsif p_restricao = 'BILHETE' Then
      -- Recupera os bilhetes ligados a faturas
      open p_result for
         select a.sq_fatura_agencia, a.sq_arquivo_eletronico, a.agencia_viagem, a.numero as nr_fatura, a.fim_decendio, a.emissao as emissao_fat, a.vencimento, a.valor, 
                a.registros as reg_fatura, a.importados as imp_fatura, a.rejeitados as rej_fatura, 
                case a.tipo when 0 then 'Bilhetes aéreos' when 1 then 'Hospedagem/Locação/Seguro' else null end as tp_fatura,
                b.data_importacao, b.data_arquivo, b.registros as reg_arquivo, b.importados as imp_arquivo, b.rejeitados as rej_arquivo, 
                b.sq_pessoa as sq_resp_imp, b.arquivo_recebido, b.arquivo_registro,
                to_char(b.data_importacao, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data_importacao,
                to_char(b.data_arquivo, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data_arquivo,
                case b.rejeitados when 0 then 'Completa' else 'Parcial' end nm_situacao_imp,
                b1.nome nm_recebido, b1.tamanho tm_recebido, b1.tipo tp_recebido, b1.caminho cm_recebido, b1.sq_siw_arquivo chave_recebido,
                b2.nome nm_result,   b2.tamanho tm_result,   b2.tipo tp_result,   b2.caminho cm_result,   b2.sq_siw_arquivo chave_result,
                b3.nome nm_resp_imp, b3.nome_resumido nm_resumido_resp_imp,
                c.sq_bilhete, c.sq_cia_transporte, c.data as emissao_bil, c.numero as nr_bilhete, c.trecho, c.valor_bilhete, c.valor_bilhete_cheio, c.valor_pta, 
                c.valor_taxa_embarque, c.rloc, c.classe, c.utilizado, c.faturado, c.observacao as observacao_bil,
                case c.utilizado when 'I' then 'Integral' when 'P' then 'Parcial' when 'C' then 'Não utilizado' else 'Não informado' end as nm_utilizado,
                case c.faturado  when 'S' then 'Sim' else 'Não' end as nm_faturado,
                c1.faixa_inicio, c1.faixa_fim, c1.desconto,
                c2.sq_siw_solicitacao as sq_solic_viagem, c2.codigo_interno as cd_solic_viagem,
                c3.sq_siw_solicitacao as sq_solic_pai,    c3.codigo_interno as cd_solic_pai,
                c5.nome as nm_beneficiario,
                c6.nome as nm_cia_transporte,
                d.nome as nm_agencia, d.nome_resumido as nm_agencia_res
           from pd_fatura_agencia                         a
                inner         join pd_arquivo_eletronico  b  on (a.sq_arquivo_eletronico = b.sq_arquivo_eletronico)
                  inner       join siw_arquivo            b1 on (b.arquivo_recebido      = b1.sq_siw_arquivo)
                  inner       join siw_arquivo            b2 on (b.arquivo_registro      = b2.sq_siw_arquivo)
                  inner       join co_pessoa              b3 on (b.sq_pessoa             = b3.sq_pessoa)
                inner         join pd_bilhete             c  on (a.sq_fatura_agencia     = c.sq_fatura_agencia and c.tipo = 'P')
                  left        join pd_desconto_agencia    c1 on (c.sq_desconto_agencia   = c1.sq_desconto_agencia)
                  left        join siw_solicitacao        c2 on (c.sq_siw_solicitacao    = c2.sq_siw_solicitacao)
                    left      join siw_solicitacao        c3 on (c2.sq_solic_pai         = c3.sq_siw_solicitacao)
                  left        join pd_missao              c4 on (c.sq_siw_solicitacao    = c4.sq_siw_solicitacao)
                    left      join co_pessoa              c5 on (c4.sq_pessoa            = c5.sq_pessoa)
                  inner       join pd_cia_transporte      c6 on (c.sq_cia_transporte     = c6.sq_cia_transporte)
                inner         join co_pessoa              d  on (a.agencia_viagem        = d.sq_pessoa)
          where b.cliente            = p_cliente
            and (p_arquivo           is null or (p_arquivo      is not null and p_arquivo            = b.sq_arquivo_eletronico))
            and (p_solic_viagem      is null or (p_solic_viagem is not null and p_solic_viagem        = coalesce(c2.sq_siw_solicitacao,0)))
            and (p_solic_pai         is null or (p_solic_pai    is not null and p_solic_pai           = coalesce(c3.sq_siw_solicitacao,0)))
            and (p_fatura            is null or (p_fatura       is not null and a.sq_fatura_agencia   = p_fatura))
            and (p_agencia           is null or (p_agencia      is not null and a.agencia_viagem      = p_agencia))
            and (p_numero_fat        is null or (p_numero_fat   is not null and a.numero              = p_numero_fat))
            and (p_ini_dec           is null or (p_ini_dec      is not null and (a.inicio_decendio    between p_ini_dec         and p_fim_dec or
                                                                                 a.fim_decendio       between p_ini_dec         and p_fim_dec or
                                                                                 p_ini_dec            between a.inicio_decendio and a.fim_decendio or
                                                                                 p_fim_dec            between a.inicio_decendio and a.fim_decendio
                                                                                )
                                                )
                )
            and (p_ini_emifat        is null or (p_ini_emifat   is not null and (a.emissao            between p_ini_emifat      and p_fim_emifat)))
            and (p_ini_ven           is null or (p_ini_ven      is not null and (a.vencimento         between p_ini_ven         and p_fim_ven)))
            and (p_bilhete           is null or (p_bilhete      is not null and c.sq_bilhete          = p_bilhete))
            and (p_numero_bil        is null or (p_numero_bil   is not null and c.numero              = p_numero_bil))
            and (p_cia_trans         is null or (p_cia_trans    is not null and c.sq_cia_transporte   = p_cia_trans))
            and (p_ini_emibil        is null or (p_ini_emibil   is not null and c.data                between p_ini_emibil      and p_fim_emibil));
   Elsif p_restricao = 'OUTROS' Then
      -- Recupera locações, hospedagens e seguros ligados a faturas
      open p_result for
         select a.sq_fatura_agencia, a.sq_arquivo_eletronico, a.agencia_viagem, a.numero as nr_fatura, a.fim_decendio, a.emissao as emissao_fat, a.vencimento, a.valor, 
                a.registros as reg_fatura, a.importados as imp_fatura, a.rejeitados as rej_fatura, 
                case a.tipo when 0 then 'Bilhetes aéreos' when 1 then 'Hospedagem/Locação/Seguro' else null end as tp_fatura,
                b.data_importacao, b.data_arquivo, b.registros as reg_arquivo, b.importados as imp_arquivo, b.rejeitados as rej_arquivo, 
                b.sq_pessoa as sq_resp_imp, b.arquivo_recebido, b.arquivo_registro,
                to_char(b.data_importacao, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data_importacao,
                to_char(b.data_arquivo, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data_arquivo,
                case b.rejeitados when 0 then 'Completa' else 'Parcial' end nm_situacao_imp,
                b1.nome nm_recebido, b1.tamanho tm_recebido, b1.tipo tp_recebido, b1.caminho cm_recebido, b1.sq_siw_arquivo chave_recebido,
                b2.nome nm_result,   b2.tamanho tm_result,   b2.tipo tp_result,   b2.caminho cm_result,   b2.sq_siw_arquivo chave_result,
                b3.nome nm_resp_imp, b3.nome_resumido nm_resumido_resp_imp,
                c.sq_fatura_outros, c.tipo as tipo_reg, c.inicio as inicio_reg, c.fim as fim_reg, c.valor as valor_reg, c.sq_pessoa as sq_pessoa_hotel,
                case c.tipo when 1 then 'Hospedagem' when 2 then 'Locação de veículo' when 3 then 'Seguro de viagem' end as nm_tipo_reg,
                c2.sq_siw_solicitacao as sq_solic_viagem, c2.codigo_interno as cd_solic_viagem,
                c3.sq_siw_solicitacao as sq_solic_pai,    c3.codigo_interno as cd_solic_pai,
                c5.nome as nm_beneficiario,
                c6.nome as nm_hotel,
                d.nome as nm_agencia, d.nome_resumido as nm_agencia_res
           from pd_fatura_agencia                         a
                inner         join pd_arquivo_eletronico  b  on (a.sq_arquivo_eletronico = b.sq_arquivo_eletronico)
                  inner       join siw_arquivo            b1 on (b.arquivo_recebido      = b1.sq_siw_arquivo)
                  inner       join siw_arquivo            b2 on (b.arquivo_registro      = b2.sq_siw_arquivo)
                  inner       join co_pessoa              b3 on (b.sq_pessoa             = b3.sq_pessoa)
                inner         join pd_fatura_outros       c  on (a.sq_fatura_agencia     = c.sq_fatura_agencia)
                  inner       join siw_solicitacao        c2 on (c.sq_siw_solicitacao    = c2.sq_siw_solicitacao)
                    left      join siw_solicitacao        c3 on (c2.sq_solic_pai         = c3.sq_siw_solicitacao)
                  inner       join pd_missao              c4 on (c.sq_siw_solicitacao    = c4.sq_siw_solicitacao)
                    left      join co_pessoa              c5 on (c4.sq_pessoa            = c5.sq_pessoa)
                  inner       join co_pessoa              c6 on (c.sq_pessoa             = c6.sq_pessoa)
                inner         join co_pessoa              d  on (a.agencia_viagem        = d.sq_pessoa)
          where b.cliente            = p_cliente
            and (p_arquivo           is null or (p_arquivo      is not null and p_arquivo            = b.sq_arquivo_eletronico))
            and (p_solic_viagem      is null or (p_solic_viagem is not null and p_solic_viagem        = coalesce(c2.sq_siw_solicitacao,0)))
            and (p_solic_pai         is null or (p_solic_pai    is not null and p_solic_pai           = coalesce(c3.sq_siw_solicitacao,0)))
            and (p_fatura            is null or (p_fatura       is not null and a.sq_fatura_agencia   = p_fatura))
            and (p_agencia           is null or (p_agencia      is not null and a.agencia_viagem      = p_agencia))
            and (p_numero_fat        is null or (p_numero_fat   is not null and a.numero              = p_numero_fat))
            and (p_ini_dec           is null or (p_ini_dec      is not null and (a.inicio_decendio    between p_ini_dec         and p_fim_dec or
                                                                                 a.fim_decendio       between p_ini_dec         and p_fim_dec or
                                                                                 p_ini_dec            between a.inicio_decendio and a.fim_decendio or
                                                                                 p_fim_dec            between a.inicio_decendio and a.fim_decendio
                                                                                )
                                                )
                )
            and (p_ini_emifat        is null or (p_ini_emifat   is not null and (a.emissao            between p_ini_emifat      and p_fim_emifat)))
            and (p_ini_ven           is null or (p_ini_ven      is not null and (a.vencimento         between p_ini_ven         and p_fim_ven)));
      Elsif p_restricao = 'TODOS' Then
      -- Recupera os bilhetes ligados a faturas
      open p_result for
         select distinct a.sq_fatura_agencia, a.sq_arquivo_eletronico, a.agencia_viagem, a.numero as nr_fatura, a.fim_decendio, a.emissao as emissao_fat, a.vencimento, a.valor, 
                a.registros as reg_fatura, a.importados as imp_fatura, a.rejeitados as rej_fatura, 
                case a.tipo when 0 then 'Aéreos' when 1 then 'Outros' else null end as tp_fatura,
                b.data_importacao, b.data_arquivo, b.registros as reg_arquivo, b.importados as imp_arquivo, b.rejeitados as rej_arquivo, 
                b.sq_pessoa as sq_resp_imp, b.arquivo_recebido, b.arquivo_registro,
                to_char(b.data_importacao, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data_importacao,
                to_char(b.data_arquivo, 'DD/MM/YYYY, HH24:MI:SS') phpdt_data_arquivo,
                case b.rejeitados when 0 then 'Completa' else 'Parcial' end nm_situacao_imp,
                b1.nome nm_recebido, b1.tamanho tm_recebido, b1.tipo tp_recebido, b1.caminho cm_recebido, b1.sq_siw_arquivo chave_recebido,
                b2.nome nm_result,   b2.tamanho tm_result,   b2.tipo tp_result,   b2.caminho cm_result,   b2.sq_siw_arquivo chave_result,
                b3.nome nm_resp_imp, b3.nome_resumido nm_resumido_resp_imp,
                d.nome as nm_agencia, d.nome_resumido as nm_agencia_res,
                coalesce(c3.sq_siw_solicitacao, e3.sq_siw_solicitacao) as sq_projeto,
                coalesce(c3.codigo_interno,     e3.codigo_interno) as cd_projeto
                
           from pd_fatura_agencia                         a
                inner         join pd_arquivo_eletronico  b  on (a.sq_arquivo_eletronico = b.sq_arquivo_eletronico)
                  inner       join siw_arquivo            b1 on (b.arquivo_recebido      = b1.sq_siw_arquivo)
                  inner       join siw_arquivo            b2 on (b.arquivo_registro      = b2.sq_siw_arquivo)
                  inner       join co_pessoa              b3 on (b.sq_pessoa             = b3.sq_pessoa)
                left          join pd_bilhete             c  on (a.sq_fatura_agencia     = c.sq_fatura_agencia)
                  left        join pd_desconto_agencia    c1 on (c.sq_desconto_agencia   = c1.sq_desconto_agencia)
                  left        join siw_solicitacao        c2 on (c.sq_siw_solicitacao    = c2.sq_siw_solicitacao)
                    left      join siw_solicitacao        c3 on (c2.sq_solic_pai         = c3.sq_siw_solicitacao)
                  left        join pd_missao              c4 on (c.sq_siw_solicitacao    = c4.sq_siw_solicitacao)
                    left      join co_pessoa              c5 on (c4.sq_pessoa            = c5.sq_pessoa)
                  left       join pd_cia_transporte       c6 on (c.sq_cia_transporte     = c6.sq_cia_transporte)
                left         join pd_fatura_outros        e  on (a.sq_fatura_agencia     = e.sq_fatura_agencia)
                  left        join siw_solicitacao        e2 on (e.sq_siw_solicitacao    = e2.sq_siw_solicitacao)
                    left      join siw_solicitacao        e3 on (e2.sq_solic_pai         = e3.sq_siw_solicitacao)
                inner         join co_pessoa              d  on (a.agencia_viagem        = d.sq_pessoa)
          where b.cliente            = p_cliente
            and (p_arquivo           is null or (p_arquivo      is not null and p_arquivo            = b.sq_arquivo_eletronico))
            and (p_solic_viagem      is null or (p_solic_viagem is not null and p_solic_viagem        = coalesce(c2.sq_siw_solicitacao,0)))
            and (p_solic_pai         is null or (p_solic_pai    is not null and p_solic_pai           = coalesce(c3.sq_siw_solicitacao,0)))
            and (p_fatura            is null or (p_fatura       is not null and a.sq_fatura_agencia   = p_fatura))
            and (p_agencia           is null or (p_agencia      is not null and a.agencia_viagem      = p_agencia))
            and (p_numero_fat        is null or (p_numero_fat   is not null and a.numero              = p_numero_fat))
            and (p_ini_dec           is null or (p_ini_dec      is not null and (a.inicio_decendio    between p_ini_dec         and p_fim_dec or
                                                                                 a.fim_decendio       between p_ini_dec         and p_fim_dec or
                                                                                 p_ini_dec            between a.inicio_decendio and a.fim_decendio or
                                                                                 p_fim_dec            between a.inicio_decendio and a.fim_decendio
                                                                                )
                                                )
                )
            and (p_ini_emifat        is null or (p_ini_emifat   is not null and (a.emissao            between p_ini_emifat      and p_fim_emifat)))
            and (p_ini_ven           is null or (p_ini_ven      is not null and (a.vencimento         between p_ini_ven         and p_fim_ven)))
            and (p_bilhete           is null or (p_bilhete      is not null and c.sq_bilhete          = p_bilhete))
            and (p_numero_bil        is null or (p_numero_bil   is not null and c.numero              = p_numero_bil))
            and (p_cia_trans         is null or (p_cia_trans    is not null and c.sq_cia_transporte   = p_cia_trans))
            and (p_ini_emibil        is null or (p_ini_emibil   is not null and c.data                between p_ini_emibil      and p_fim_emibil));   
   End If;
End SP_GetPD_Fatura;
/
