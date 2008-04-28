create or replace function siw.SP_PutViagemGeral
   (p_operacao            varchar,
    p_cliente             numeric,
    p_chave               numeric,
    p_menu                numeric,
    p_unidade             numeric,
    p_unid_resp           numeric,
    p_solicitante         numeric,
    p_cadastrador         numeric,
    p_tipo                varchar  ,
    p_descricao           varchar  ,
    p_justif_dia_util     varchar  ,
    p_inicio              date      ,
    p_fim                 date      ,
    p_data_hora           varchar  ,
    p_aviso               varchar  ,
    p_dias                numeric,
    p_projeto             numeric,
    p_tarefa              numeric,
    p_cpf                 varchar  ,
    p_nome                varchar  ,
    p_nome_res            varchar  ,
    p_sexo                varchar  ,
    p_vinculo             numeric,
    p_inicio_atual        date      ,
    p_copia               numeric
    
   ) RETURNS character varying AS
$BODY$declare
   w_ano        numeric(4);
   w_sequencial numeric(18)     := 0;
   w_existe     numeric(4);
   w_arq        varchar(4000) := ', ';
   w_chave      numeric(18)     := Nvl(p_chave,0);
   w_log_sol    numeric(18);
   w_log_esp    numeric(18);
   w_cont       numeric(4);
   w_reg        siw.PD_parametro%rowtype;
    p_chave_nova          numeric;
   
   w_pessoa     numeric(18) := null;

   p_codigo_interno       varchar;
   
begin
   If p_operacao = 'I' Then -- Inclusão
      -- Recupera a próxima chave
      select nextval('siw.sq_siw_solicitacao') into w_Chave from dual;
       
      -- Insere registro em SIW_SOLICITACAO
      insert into siw.siw_solicitacao (
         sq_siw_solicitacao, sq_menu,       sq_siw_tramite,      solicitante, 
         cadastrador,        descricao,     justificativa,       inicio,
         fim,                inclusao,      ultima_alteracao,    valor,
         data_hora,          sq_unidade,    sq_cc,               sq_cidade_origem,
         sq_solic_pai)
      (select 
         w_Chave,            p_menu,        a.sq_siw_tramite,    p_cadastrador,
         p_cadastrador,      p_descricao,   null,                p_inicio,
         p_fim,              sysdate,       sysdate,             0,
         p_data_hora,        p_unidade,     null,                d.sq_cidade,
         Nvl(p_tarefa, p_projeto)
         from siw.siw_tramite                     a,
              siw.sg_autenticacao                 b
                inner   join eo_localizacao     c on (b.sq_localizacao     = c.sq_localizacao)
                  inner join co_pessoa_endereco d on (c.sq_pessoa_endereco = d.sq_pessoa_endereco)
        where a.sq_menu            = p_menu
          and a.sigla              = 'CI'
          and b.sq_pessoa          = p_cadastrador
      );
      
      -- Insere registro em GD_DEMANDA
      Insert into siw.gd_demanda
         ( sq_siw_solicitacao,  sq_unidade_resp, assunto,           prioridade,
           aviso_prox_conc,     dias_aviso,      inicio_real,       fim_real,
           concluida,           data_conclusao,  nota_conclusao,    custo_real,
           proponente,          ordem
         )
      (select
           w_chave,             p_unid_resp,     null,              null,
           p_aviso,             0,               null,              null,
           'N',                 null,            null,              0,
           null,                0
        from dual
      );
      
      -- Se for pessoa que ainda não consta da base de dados, inclui
      If p_solicitante is null Then
         -- recupera a próxima chave da pessoa
         select nextval('siw.sq_pessoa') into w_pessoa from dual;
   
         -- insere os dados da pessoa
         insert into siw.co_pessoa (sq_pessoa, sq_pessoa_pai, sq_tipo_vinculo, sq_tipo_pessoa,   nome,   nome_resumido)
         (select                w_pessoa,  p_cliente,     p_vinculo,       sq_tipo_pessoa,   p_nome, p_nome_res
            from siw.co_tipo_pessoa a
           where a.nome  = 'Física'
             and a.ativo = 'S'
         );
         
         insert into siw.co_pessoa_fisica 
                (sq_pessoa, cpf,   sexo,   cliente)
         values (w_pessoa,  p_cpf, p_sexo, p_cliente);
      Else
         -- Verifica se a pessoa tem registro em CO_PESSOA_FISICA. Se não tiver, grava
         select count(*) into w_existe from co_pessoa_fisica where sq_pessoa = p_solicitante;
         If w_existe = 0 Then
            insert into siw.co_pessoa_fisica 
                   (sq_pessoa,     cpf,   sexo,   cliente)
            values (p_solicitante, p_cpf, p_sexo, p_cliente);
         End If;
        
         -- Verifica se a pessoa tem indicação do tipo de vínculo. Se não tiver, grava
         select count(*) into w_existe from siw.co_pessoa where sq_pessoa = p_solicitante and sq_tipo_vinculo is not null;
         If w_existe = 0 Then
            update siw.co_pessoa set sq_tipo_vinculo = p_vinculo where sq_pessoa = p_solicitante;
         End If;
      End If;
      
      -- Insere registro em PD_MISSAO
      insert into siw.pd_missao
        (sq_siw_solicitacao, cliente,   sq_pessoa,                     tipo,   justificativa_dia_util)
      values
        (w_chave,            p_cliente, Nvl(w_pessoa, p_solicitante),  p_tipo, p_justif_dia_util);

      -- Insere log da solicitação
      Insert Into siw.siw_solic_log 
         (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
          sq_siw_tramite,            data,               devolucao, 
          observacao
         )
      (select 
          sq_siw_solic_log.nextval,  w_chave,            p_cadastrador,
          a.sq_siw_tramite,          sysdate,            'N',
          'Cadastramento inicial'
         from siw.siw_tramite a
        where a.sq_menu = p_menu
          and a.sigla   = 'CI'
      );
           
      -- Recupera o código interno  do acordo, gerado por trigger
      select codigo_interno into p_codigo_interno from siw.siw_solicitacao where sq_siw_solicitacao = w_chave;

      -- Se a demanda foi copiada de outra, grava os dados complementares
      If p_copia is not null Then
         -- Copia as diárias da missão original
         Insert Into siw.pd_diaria (sq_diaria, sq_siw_solicitacao, sq_cidade, quantidade, valor)
         (Select sq_diaria.nextval, w_chave, sq_cidade, quantidade, valor
           from siw.pd_diaria a
          where a.sq_siw_solicitacao = p_copia
         );

         -- Copia os deslocamentos da missão original
         Insert Into siw.pd_deslocamento (sq_deslocamento, sq_siw_solicitacao, origem, destino, sq_cia_transporte, saida, chegada, codigo_cia_transporte, valor_trecho)
         (Select sq_deslocamento.nextval, w_chave, origem, destino, sq_cia_transporte, saida, chegada, codigo_cia_transporte, valor_trecho
           from siw.pd_deslocamento a
          where a.sq_siw_solicitacao = p_copia
         );
      End If;
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de solicitações
      Update siw.siw_solicitacao set
         solicitante      = p_cadastrador,
         cadastrador      = p_cadastrador,
         descricao        = trim(p_descricao), 
         inicio           = p_inicio,
         fim              = p_fim,
         ultima_alteracao = sysdate,
         sq_unidade       = p_unidade,
         sq_solic_pai     = Nvl(p_tarefa, p_projeto)
      where sq_siw_solicitacao = p_chave;
      
      -- Atualiza a tabela de demandas
      Update siw.gd_demanda set
          sq_unidade_resp  = p_unid_resp
      where sq_siw_solicitacao = p_chave;
      
      -- Atualiza a tabela de demandas
      Update siw.pd_missao set
          tipo                   = p_tipo,
          justificativa_dia_util = p_justif_dia_util
      where sq_siw_solicitacao = p_chave;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Verifica a quantidade de logs da solicitação
      select count(*) into w_log_sol from siw.siw_solic_log  where sq_siw_solicitacao = p_chave;
      select count(*) into w_log_esp from siw.gd_demanda_log where sq_siw_solicitacao = p_chave;
      
      -- Se não foi enviada para outra fase nem para outra pessoa, exclui fisicamente.
      -- Caso contrário, coloca a solicitação como cancelada.
      If (w_log_sol + w_log_esp) > 1 Then
         -- Insere log de cancelamento
         Insert Into siw.siw_solic_log 
            (sq_siw_solic_log,          sq_siw_solicitacao,   sq_pessoa, 
             sq_siw_tramite,            data,                 devolucao, 
             observacao
            )
         (select 
             sq_siw_solic_log.nextval,  a.sq_siw_solicitacao, p_cadastrador,
             a.sq_siw_tramite,          now(),              'N',
             'Cancelamento'
            from siw.siw_solicitacao a
           where a.sq_siw_solicitacao = p_chave
         );
         
         -- Atualiza a situação da demanda
         update siw.gd_demanda set concluida = 'S' where sq_siw_solicitacao = p_chave;

         -- Recupera a chave que indica que a solicitação está cancelada
         select a.sq_siw_tramite into w_chave from siw.siw_tramite a where a.sq_menu = p_menu and a.sigla = 'CA';
         
         -- Atualiza a situação da solicitação
         update siw.siw_solicitacao set sq_siw_tramite = w_chave, conclusao = sysdate where sq_siw_solicitacao = p_chave;
      Else
         -- Remove os registros vinculados à missão
         delete from siw.pd_missao_solic where sq_solic_missao    = p_chave;
         delete from siw.opd_deslocamento where sq_siw_solicitacao = p_chave;
         delete from siw.pd_diaria       where sq_siw_solicitacao = p_chave;
         delete from siw.pd_missao       where sq_siw_solicitacao = p_chave;
            
         -- Remove o registro na tabela de demandas
         delete from siw.gd_demanda where sq_siw_solicitacao = p_chave;
         
         -- Remove o log da solicitação
         delete from siw.siw_solic_log where sq_siw_solicitacao = p_chave;

         -- Remove o registro na tabela de solicitações
         delete from siw.siw_solicitacao where sq_siw_solicitacao = p_chave;
      End If;
   End If;
   
   -- O tratamento a seguir é relativo ao código interno do acordo.
   If p_operacao                in ('I','A')  and 
      (p_inicio_atual           is null       or
       to_char(p_inicio,'yyyy') <> to_char(Nvl(p_inicio_atual, p_inicio),'yyyy')
      ) Then
      
      -- Recupera os parâmetros do cliente informado
      select * into w_reg from siw.pd_parametro where cliente = p_cliente;

      If to_char(p_inicio,'yyyy') <  w_reg.ano_corrente Then
    
         -- Configura o ano do acordo para o ano informado na data de início.
         w_ano := to_numeric(to_char(p_inicio,'yyyy'));
         
         -- Verifica se já há alguma missão no ano informado na data de início.
         -- Se tiver, verifica o próximo sequencial. Caso contrário, usa 1.
         select count(*) into w_existe 
           from siw.siw_solicitacao      a
                  inner join pd_missao b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
          where to_char(a.inicio,'yyyy') = w_ano
            and a.sq_siw_solicitacao     <> w_chave
            and b.cliente                = p_cliente;
            
         If w_existe = 0 Then
            w_sequencial := 1;
         Else
            select Nvl(max(to_numeric(replace(replace(replace(a.codigo_interno,'/'||w_ano,''),Nvl(w_reg.prefixo,''),''),Nvl(w_reg.sufixo,''),''))),0)+1
              into w_sequencial
              from siw.siw_solicitacao        a
                     inner join pd_missao b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
             where to_char(a.inicio,'yyyy') = to_char(p_inicio,'yyyy')
               and b.cliente                = p_cliente;
         End If;
         
         p_codigo_interno := Nvl(w_reg.prefixo,'')||w_sequencial||'/'||w_ano||Nvl(w_reg.sufixo,'');

         -- Atualiza o código interno do acordo para o sequencial encontrato
         update siw.siw_solicitacao a set
            codigo_interno = p_codigo_interno
         where a.sq_siw_solicitacao = w_chave;
         
      End If;
   End If;

   -- Devolve a chave
   If p_chave is not null
      Then p_chave_nova := p_chave;
      Else p_chave_nova := w_chave;
   End If;
end
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
