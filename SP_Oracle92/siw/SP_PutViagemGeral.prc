create or replace procedure SP_PutViagemGeral
   (p_operacao            in varchar2,
    p_cliente             in number   default null,
    p_chave               in number   default null,
    p_menu                in number,
    p_unidade             in number    default null,
    p_unid_resp           in number    default null,
    p_solicitante         in number    default null,
    p_cadastrador         in number    default null,
    p_tipo                in varchar2  default null,
    p_descricao           in varchar2  default null,
    p_agenda              in varchar2  default null,
    p_justif_dia_util     in varchar2  default null,
    p_inicio              in date      default null,
    p_fim                 in date      default null,
    p_data_hora           in varchar2  default null,
    p_aviso               in varchar2  default null,
    p_dias                in number    default null,
    p_projeto             in number    default null,
    p_tarefa              in number    default null,
    p_inicio_atual        in date      default null,
    p_passagem            in varchar2  default null,
    p_diaria              in number     default null,
    p_hospedagem          in varchar2  default null,
    p_veiculo             in varchar2  default null,
    p_proponente          in varchar2  default null,
    p_financeiro          in number    default null,
    p_rubrica             in number    default null,
    p_lancamento          in number    default null,
    p_chave_nova          out number,
    p_copia               in number   default null,
    p_codigo_interno      in out varchar2
   ) is
   w_arq        varchar2(4000) := ', ';
   w_ano        number(4);
   w_sequencial number(18)     := 0;
   w_existe     number(4);
   w_chave      number(18)     := Nvl(p_chave,0);
   w_log_sol    number(18);
   w_log_esp    number(18);
   w_reg        PD_parametro%rowtype;
   w_financeiro number(18) := p_financeiro;

   cursor c_arquivos is
      select sq_siw_arquivo from siw_solic_arquivo where sq_siw_solicitacao = p_chave;
   
begin
   -- Verifica se precisa gravar o tipo de vínculo financeiro
   If instr('IA','I')>0 and p_financeiro is null and p_lancamento is not null Then
      -- Verifica se há um vínculo único para as opções enviadas
      select count(*) into w_existe
        from pd_vinculo_financeiro
       where sq_projeto_rubrica = p_rubrica
         and sq_tipo_lancamento = p_lancamento
         and bilhete            = 'S';
      -- Prepara variável para gravação se encontrou um, e apenas um registro.
      If w_existe = 1 Then
         select sq_pdvinculo_financeiro into w_financeiro
           from pd_vinculo_financeiro
          where sq_projeto_rubrica = p_rubrica
            and sq_tipo_lancamento = p_lancamento
            and bilhete            = 'S';
      End If;
   End If;
   
   If p_operacao = 'I' Then -- Inclusão
      -- Recupera a próxima chave
      select sq_siw_solicitacao.nextval into w_Chave from dual;
       
      -- Insere registro em SIW_SOLICITACAO
      insert into siw_solicitacao (
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
         from siw_tramite                     a,
              sg_autenticacao                 b
                inner   join eo_localizacao     c on (b.sq_localizacao     = c.sq_localizacao)
                  inner join co_pessoa_endereco d on (c.sq_pessoa_endereco = d.sq_pessoa_endereco)
        where a.sq_menu            = p_menu
          and a.sigla              = 'CI'
          and b.sq_pessoa          = p_cadastrador
      );
      
      -- Insere registro em GD_DEMANDA
      Insert into gd_demanda
         ( sq_siw_solicitacao,  sq_unidade_resp, assunto,           prioridade,
           aviso_prox_conc,     dias_aviso,      inicio_real,       fim_real,
           concluida,           data_conclusao,  nota_conclusao,    custo_real,
           proponente,          ordem
         )
      (select
           w_chave,             p_unid_resp,     p_agenda,          null,
           p_aviso,             0,               null,              null,
           'N',                 null,            null,              0,
           p_proponente,        0
        from dual
      );
      
      -- Insere registro em PD_MISSAO
      insert into pd_missao
        (sq_siw_solicitacao, cliente,   sq_pessoa,                     tipo,      justificativa_dia_util,
         passagem,           diaria,    hospedagem,                    veiculo,   sq_pdvinculo_bilhete
        )
      values
        (w_chave,            p_cliente, p_solicitante,                 p_tipo,    p_justif_dia_util,
         p_passagem,         p_diaria,  p_hospedagem,                  p_veiculo, w_financeiro
        );

      -- Insere log da solicitação
      Insert Into siw_solic_log 
         (sq_siw_solic_log,          sq_siw_solicitacao, sq_pessoa, 
          sq_siw_tramite,            data,               devolucao, 
          observacao
         )
      (select 
          sq_siw_solic_log.nextval,  w_chave,            p_cadastrador,
          a.sq_siw_tramite,          sysdate,            'N',
          'Cadastramento inicial'
         from siw_tramite a
        where a.sq_menu = p_menu
          and a.sigla   = 'CI'
      );
           
      -- Recupera o código interno  do acordo, gerado por trigger
      select codigo_interno into p_codigo_interno from siw_solicitacao where sq_siw_solicitacao = w_chave;

      -- Se a demanda foi copiada de outra, grava os dados complementares
      If p_copia is not null Then
         -- Copia as diárias da missão original
         Insert Into pd_diaria (sq_diaria, sq_siw_solicitacao, sq_cidade, quantidade, valor, hospedagem, hospedagem_qtd, hospedagem_valor, veiculo, veiculo_qtd, veiculo_valor, sq_valor_diaria, diaria, sq_deslocamento_chegada, sq_deslocamento_saida, sq_valor_diaria_hospedagem, sq_valor_diaria_veiculo, justificativa_diaria, justificativa_veiculo, sq_pdvinculo_diaria, sq_pdvinculo_hospedagem, sq_pdvinculo_veiculo, hospedagem_checkin, hospedagem_checkout, hospedagem_observacao, veiculo_retirada, veiculo_devolucao, tipo, calculo_diaria_qtd, calculo_diaria_texto, calculo_hospedagem_qtd, calculo_hospedagem_texto, calculo_veiculo_qtd, calculo_veiculo_texto)
         (Select sq_diaria.nextval,        w_chave,            sq_cidade, quantidade, valor, hospedagem, hospedagem_qtd, hospedagem_valor, veiculo, veiculo_qtd, veiculo_valor, sq_valor_diaria, diaria, sq_deslocamento_chegada, sq_deslocamento_saida, sq_valor_diaria_hospedagem, sq_valor_diaria_veiculo, justificativa_diaria, justificativa_veiculo, sq_pdvinculo_diaria, sq_pdvinculo_hospedagem, sq_pdvinculo_veiculo, hospedagem_checkin, hospedagem_checkout, hospedagem_observacao, veiculo_retirada, veiculo_devolucao, tipo, calculo_diaria_qtd, calculo_diaria_texto, calculo_hospedagem_qtd, calculo_hospedagem_texto, calculo_veiculo_qtd, calculo_veiculo_texto
           from pd_diaria a
          where a.sq_siw_solicitacao = p_copia
         );

         -- Copia os deslocamentos da missão original
         insert into pd_deslocamento (sq_deslocamento, sq_siw_solicitacao, origem, destino, sq_cia_transporte, saida, chegada, codigo_cia_transporte, valor_trecho, codigo_voo, sq_meio_transporte, passagem, compromisso, sq_bilhete, aeroporto_origem, aeroporto_destino, tipo)
         (Select sq_deslocamento.nextval,              w_chave,            origem, destino, null,              saida, chegada, null,                  0,            null,       sq_meio_transporte, passagem, compromisso, null,       aeroporto_origem, aeroporto_destino, tipo
           from pd_deslocamento a
          where a.tipo               = 'S'
            and a.sq_siw_solicitacao = p_copia
         );
      End If;
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de solicitações
      Update siw_solicitacao set
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
      Update gd_demanda set
          assunto          = p_agenda,
          proponente       = p_proponente,
          sq_unidade_resp  = p_unid_resp
      where sq_siw_solicitacao = p_chave;
      
      -- Atualiza a tabela de demandas
      Update pd_missao set
          tipo                   = p_tipo,
          justificativa_dia_util = nvl(p_justif_dia_util,justificativa_dia_util),
          passagem               = p_passagem,
          diaria                 = p_diaria,
          hospedagem             = p_hospedagem,
          veiculo                = p_veiculo,
          sq_pdvinculo_bilhete   = w_financeiro
      where sq_siw_solicitacao = p_chave;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Verifica a quantidade de logs da solicitação
      select count(*) into w_log_sol from siw_solic_log  where sq_siw_solicitacao = p_chave;
      select count(*) into w_log_esp from gd_demanda_log where sq_siw_solicitacao = p_chave;
      
      -- Se não foi enviada para outra fase nem para outra pessoa, exclui fisicamente.
      -- Caso contrário, coloca a solicitação como cancelada.
      If (w_log_sol + w_log_esp) > 1 Then
         -- Insere log de cancelamento
         Insert Into siw_solic_log 
            (sq_siw_solic_log,          sq_siw_solicitacao,   sq_pessoa, 
             sq_siw_tramite,            data,                 devolucao, 
             observacao
            )
         (select 
             sq_siw_solic_log.nextval,  a.sq_siw_solicitacao, p_cadastrador,
             a.sq_siw_tramite,          sysdate,              'N',
             'Cancelamento'
            from siw_solicitacao a
           where a.sq_siw_solicitacao = p_chave
         );
         
         -- Atualiza a situação da demanda
         update gd_demanda set concluida = 'S' where sq_siw_solicitacao = p_chave;

         -- Recupera a chave que indica que a solicitação está cancelada
         select a.sq_siw_tramite into w_chave from siw_tramite a where a.sq_menu = p_menu and a.sigla = 'CA';
         
         -- Atualiza a situação da solicitação
         update siw_solicitacao set sq_siw_tramite = w_chave, conclusao = sysdate where sq_siw_solicitacao = p_chave;
      Else
        
         -- Monta string com a chave dos arquivos ligados à solicitação informada
         for crec in c_arquivos loop
            w_arq := w_arq || crec.sq_siw_arquivo;
         end loop;
         w_arq := substr(w_arq, 3, length(w_arq));
      
         -- Remove os registros vinculados à missão
         delete pd_missao_solic where sq_solic_missao    = p_chave;
         delete pd_diaria       where sq_siw_solicitacao = p_chave;
         delete pd_deslocamento where sq_siw_solicitacao = p_chave;
         delete pd_missao       where sq_siw_solicitacao = p_chave;
            
         -- Remove o registro na tabela de demandas
         delete gd_demanda where sq_siw_solicitacao = p_chave;
         
         -- Remove os arquivos
         delete siw_solic_arquivo           where sq_siw_solicitacao = p_chave;
         delete siw_arquivo                 where sq_siw_arquivo     in (w_arq);
         
         -- Remove o log da solicitação
         delete siw_solic_log where sq_siw_solicitacao = p_chave;


         -- Remove o registro na tabela de solicitações
         delete siw_solicitacao where sq_siw_solicitacao = p_chave;
      End If;
   End If;
   
   -- O tratamento a seguir é relativo ao código interno do acordo.
   If p_operacao                in ('I','A')  and 
      (p_inicio_atual           is null       or
       to_char(p_inicio,'yyyy') <> to_char(Nvl(p_inicio_atual, p_inicio),'yyyy')
      ) Then
      
      -- Recupera os parâmetros do cliente informado
      select * into w_reg from pd_parametro where cliente = p_cliente;

      If to_char(p_inicio,'yyyy') <  w_reg.ano_corrente Then
    
         -- Configura o ano do acordo para o ano informado na data de início.
         w_ano := to_number(to_char(p_inicio,'yyyy'));
         
         -- Verifica se já há alguma missão no ano informado na data de início.
         -- Se tiver, verifica o próximo sequencial. Caso contrário, usa 1.
         select count(*) into w_existe 
           from siw_solicitacao      a
                  inner join pd_missao b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
          where to_char(a.inicio,'yyyy') = w_ano
            and a.sq_siw_solicitacao     <> w_chave
            and b.cliente                = p_cliente;
            
         If w_existe = 0 Then
            w_sequencial := 1;
         Else
            select Nvl(max(to_number(replace(replace(replace(a.codigo_interno,'/'||w_ano,''),Nvl(w_reg.prefixo,''),''),Nvl(w_reg.sufixo,''),''))),0)+1
              into w_sequencial
              from siw_solicitacao        a
                   inner join pd_missao   b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
             where to_char(a.inicio,'yyyy') = to_char(p_inicio,'yyyy')
               and b.cliente                = p_cliente
               and a.codigo_interno         like w_reg.prefixo||'%';
         End If;
         
         p_codigo_interno := Nvl(w_reg.prefixo,'')||w_sequencial||'/'||w_ano||Nvl(w_reg.sufixo,'');

         -- Atualiza o código interno do acordo para o sequencial encontrato
         update siw_solicitacao a set
            codigo_interno = p_codigo_interno
         where a.sq_siw_solicitacao = w_chave;
         
      End If;
   End If;

   -- Devolve a chave
   If p_chave is not null
      Then p_chave_nova := p_chave;
      Else p_chave_nova := w_chave;
   End If;
end SP_PutViagemGeral;
/
