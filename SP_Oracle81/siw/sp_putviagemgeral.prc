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
    p_justif_dia_util     in varchar2  default null,
    p_inicio              in date      default null,
    p_fim                 in date      default null,
    p_data_hora           in varchar2  default null,
    p_aviso               in varchar2  default null,
    p_dias                in number    default null,
    p_projeto             in number    default null,
    p_tarefa              in number    default null,
    p_cpf                 in varchar2  default null,
    p_nome                in varchar2  default null,
    p_nome_res            in varchar2  default null,
    p_sexo                in varchar2  default null,
    p_vinculo             in number    default null,
    p_inicio_atual        in date      default null,
    p_chave_nova          out number,
    p_copia               in number   default null,
    p_codigo_interno      in out varchar2
   ) is
   w_ano        number(4);
   w_sequencial number(18)     := 0;
   w_existe     number(4);
   w_arq        varchar2(4000) := ', ';
   w_chave      number(18)     := Nvl(p_chave,0);
   w_log_sol    number(18);
   w_log_esp    number(18);
   w_cont       number(4);
   w_reg        PD_parametro%rowtype;
   
   w_pessoa     number(18) := null;
   
begin
   If p_operacao = 'I' Then -- Inclus�o
      -- Recupera a pr�xima chave
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
         from siw_tramite        a,
              sg_autenticacao    b,
              eo_localizacao     c,
              co_pessoa_endereco d
        where a.sq_menu            = p_menu
          and a.sigla              = 'CI'
          and b.sq_pessoa          = p_cadastrador
          and b.sq_localizacao     = c.sq_localizacao
          and c.sq_pessoa_endereco = d.sq_pessoa_endereco
      );
      
      -- Insere registro em GD_DEMANDA
      Insert into gd_demanda
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
      
      -- Se for pessoa que ainda n�o consta da base de dados, inclui
      If p_solicitante is null Then
         -- recupera a pr�xima chave da pessoa
         select sq_pessoa.nextval into w_pessoa from dual;
   
         -- insere os dados da pessoa
         insert into co_pessoa (sq_pessoa, sq_pessoa_pai, sq_tipo_vinculo, sq_tipo_pessoa,   nome,   nome_resumido)
         (select                w_pessoa,  p_cliente,     p_vinculo,       sq_tipo_pessoa,   p_nome, p_nome_res
            from co_tipo_pessoa a
           where a.nome  = 'F�sica'
             and a.ativo = 'S'
         );
         
         insert into co_pessoa_fisica 
                (sq_pessoa, cpf,   sexo,   cliente)
         values (w_pessoa,  p_cpf, p_sexo, p_cliente);
      Else
         -- Verifica se a pessoa tem registro em CO_PESSOA_FISICA. Se n�o tiver, grava
         select count(*) into w_existe from co_pessoa_fisica where sq_pessoa = p_solicitante;
         If w_existe = 0 Then
            insert into co_pessoa_fisica 
                   (sq_pessoa,     cpf,   sexo,   cliente)
            values (p_solicitante, p_cpf, p_sexo, p_cliente);
         End If;
        
         -- Verifica se a pessoa tem indica��o do tipo de v�nculo. Se n�o tiver, grava
         select count(*) into w_existe from co_pessoa where sq_pessoa = p_solicitante and sq_tipo_vinculo is not null;
         If w_existe = 0 Then
            update co_pessoa set sq_tipo_vinculo = p_vinculo where sq_pessoa = p_solicitante;
         End If;
      End If;
      
      -- Insere registro em PD_MISSAO
      insert into pd_missao
        (sq_siw_solicitacao, cliente,   sq_pessoa,                     tipo,   justificativa_dia_util)
      values
        (w_chave,            p_cliente, Nvl(w_pessoa, p_solicitante),  p_tipo, p_justif_dia_util);

      -- Insere log da solicita��o
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
           
      -- Recupera o c�digo interno  do acordo, gerado por trigger
      select codigo_interno into p_codigo_interno from pd_missao where sq_siw_solicitacao = w_chave;

      -- Se a demanda foi copiada de outra, grava os dados complementares
      If p_copia is not null Then
         -- Copia as di�rias da miss�o original
         Insert Into pd_diaria (sq_diaria, sq_siw_solicitacao, sq_cidade, quantidade, valor)
         (Select sq_diaria.nextval, w_chave, sq_cidade, quantidade, valor
           from pd_diaria a
          where a.sq_siw_solicitacao = p_copia
         );

         -- Copia os deslocamentos da miss�o original
         Insert Into pd_deslocamento (sq_deslocamento, sq_siw_solicitacao, origem, destino, sq_cia_transporte, saida, chegada, codigo_cia_transporte, valor_trecho)
         (Select sq_deslocamento.nextval, w_chave, origem, destino, sq_cia_transporte, saida, chegada, codigo_cia_transporte, valor_trecho
           from pd_deslocamento a
          where a.sq_siw_solicitacao = p_copia
         );
      End If;
   Elsif p_operacao = 'A' Then -- Altera��o
      -- Atualiza a tabela de solicita��es
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
          sq_unidade_resp  = p_unid_resp
      where sq_siw_solicitacao = p_chave;
      
      -- Atualiza a tabela de demandas
      Update pd_missao set
          tipo                   = p_tipo,
          justificativa_dia_util = p_justif_dia_util
      where sq_siw_solicitacao = p_chave;
   Elsif p_operacao = 'E' Then -- Exclus�o
      -- Verifica a quantidade de logs da solicita��o
      select count(*) into w_log_sol from siw_solic_log  where sq_siw_solicitacao = p_chave;
      select count(*) into w_log_esp from gd_demanda_log where sq_siw_solicitacao = p_chave;
      
      -- Se n�o foi enviada para outra fase nem para outra pessoa, exclui fisicamente.
      -- Caso contr�rio, coloca a solicita��o como cancelada.
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
         
         -- Atualiza a situa��o da demanda
         update gd_demanda set concluida = 'S' where sq_siw_solicitacao = p_chave;

         -- Recupera a chave que indica que a solicita��o est� cancelada
         select a.sq_siw_tramite into w_chave from siw_tramite a where a.sq_menu = p_menu and a.sigla = 'CA';
         
         -- Atualiza a situa��o da solicita��o
         update siw_solicitacao set sq_siw_tramite = w_chave, conclusao = sysdate where sq_siw_solicitacao = p_chave;
      Else
         -- Remove os registros vinculados � miss�o
         delete pd_missao_solic where sq_solic_missao    = p_chave;
         delete pd_deslocamento where sq_siw_solicitacao = p_chave;
         delete pd_diaria       where sq_siw_solicitacao = p_chave;
         delete pd_missao       where sq_siw_solicitacao = p_chave;
            
         -- Remove o registro na tabela de demandas
         delete gd_demanda where sq_siw_solicitacao = p_chave;
         
         -- Remove o log da solicita��o
         delete siw_solic_log where sq_siw_solicitacao = p_chave;

         -- Remove o registro na tabela de solicita��es
         delete siw_solicitacao where sq_siw_solicitacao = p_chave;
      End If;
   End If;
   
   -- O tratamento a seguir � relativo ao c�digo interno do acordo.
   If p_operacao                in ('I','A')  and 
      (p_inicio_atual           is null       or
       to_char(p_inicio,'yyyy') <> to_char(Nvl(p_inicio_atual, p_inicio),'yyyy')
      ) Then
      
      -- Recupera os par�metros do cliente informado
      select * into w_reg from pd_parametro where cliente = p_cliente;

      If to_char(p_inicio,'yyyy') <  w_reg.ano_corrente Then
    
         -- Configura o ano do acordo para o ano informado na data de in�cio.
         w_ano := to_number(to_char(p_inicio,'yyyy'));
         
         -- Verifica se j� h� alguma miss�o no ano informado na data de in�cio.
         -- Se tiver, verifica o pr�ximo sequencial. Caso contr�rio, usa 1.
         select count(*) into w_existe 
           from siw_solicitacao a, pd_missao b
          where a.sq_siw_solicitacao     = b.sq_siw_solicitacao
            and to_char(a.inicio,'yyyy') = w_ano
            and a.sq_siw_solicitacao     <> w_chave
            and b.cliente                = p_cliente;
            
         If w_existe = 0 Then
            w_sequencial := 1;
         Else
            select Nvl(max(to_number(replace(replace(replace(b.codigo_interno,'/'||w_ano,''),Nvl(w_reg.prefixo,''),''),Nvl(w_reg.sufixo,''),''))),0)+1
              into w_sequencial
              from siw_solicitacao a, pd_missao b
             where a.sq_siw_solicitacao     = b.sq_siw_solicitacao
               and to_char(a.inicio,'yyyy') = to_char(p_inicio,'yyyy')
               and b.cliente                = p_cliente;
         End If;
         
         p_codigo_interno := Nvl(w_reg.prefixo,'')||w_sequencial||'/'||w_ano||Nvl(w_reg.sufixo,'');

         -- Atualiza o c�digo interno do acordo para o sequencial encontrato
         update pd_missao a set
            codigo_interno = p_codigo_interno
         where a.sq_siw_solicitacao = w_chave;
         
      Else
         
         -- Se n�o for ano anterior, recupera o pr�ximo sequencial e atualiza PD_PARAMETRO
         w_sequencial := w_reg.sequencial + 1;
         update pd_parametro set sequencial = w_sequencial where cliente = p_cliente;
         
         p_codigo_interno := Nvl(w_reg.prefixo,'')||w_sequencial||'/'||w_reg.ano_corrente||Nvl(w_reg.sufixo,'');

         -- Atualiza o c�digo interno do acordo para o sequencial encontrato
         update pd_missao a set
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
