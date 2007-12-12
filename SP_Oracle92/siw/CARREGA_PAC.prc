create or replace procedure CARREGA_PAC is
  w_cliente           number(18) := 10938;
  w_menu_prj          number(18);
  w_usuario           number(18);
  w_unid_usu          number(18);
  w_usu_cad           number(18);
  w_unid_usc          number(18);
  w_chave             number(18);
  w_chave_etapa       number(18);
  w_chave_risco       number(18);
  w_codigo            varchar2(60);
  w_data_hora         siw_menu.data_hora%type;
  w_texto             varchar2(500);
  w_existe            number(10);
  
  cursor c_proj is
      select distinct a.id_pacito, a.nome, 
             to_date('01/'||a.data_inicio,'dd/mm/yyyy') as inicio, 
             last_day(to_date('01/'||a.data_fim,'dd/mm/yyyy')) as fim, 
             b.sq_cidade, b.co_uf, b.sq_pais,
             c.sq_unidade, 
             d.sq_plano,
             e.sq_peobjetivo,
             replace(fValor(a.orc1 + a.orc2 + a.orc3 + a.orc4 + a.orc5, 'N'),'.',',') as orcamento
        from siw_is.temp_pacito       a
             inner   join co_cidade   b on (a.cod_municipio = b.codigo_ibge)
             inner   join eo_unidade  c on (a.cod_orgao     = to_number(c.codigo) and
                                            c.sq_pessoa     = w_cliente
                                           )
             inner   join pe_plano    d on (a.id_eixo       = d.codigo_externo and
                                            d.cliente       = w_cliente
                                           )
               inner join pe_objetivo e on (d.sq_plano      = e.sq_plano and
                                            a.id_sala       = e.codigo_externo and
                                            e.cliente       = w_cliente
                                           )
      order by a.id_pacito;

  cursor c_comp (chave in number) is
      select id_sispac from siw_is.temp_pacito where id_pacito = chave order by id_sispac;

  cursor c_restricao (chave in number) is
      select distinct b.*, c.sq_tipo_restricao
        from siw_is.temp_pacito                 a
             inner   join siw_is.temp_restricao b on (a.id_pacito = b.id_pacito)
             inner   join siw_tipo_restricao    c on (b.tipo_restricao = c.codigo_externo and c.cliente = w_cliente)
      where b.id_pacito = chave
      order by b.id_pacito;
begin
  -- Recupera a chave do menu de projetos
  select sq_menu, data_hora into w_menu_prj, w_data_hora
    from siw_menu a
   where a.sq_pessoa = w_cliente
     and a.sigla = 'PJCAD';

  -- Recupera o usuário e unidade de cadastramento
  select a.sq_pessoa, a.sq_unidade into w_usu_cad, w_unid_usc
    from sg_autenticacao a join co_pessoa b on (a.sq_pessoa = b.sq_pessoa and b.sq_pessoa_pai = w_cliente)
   where a.username = '000.000.001-91';

  -- Recupera o usuário e unidade de monitoramento
  select a.sq_pessoa, a.sq_unidade into w_usuario, w_unid_usu
    from sg_autenticacao a join co_pessoa b on (a.sq_pessoa = b.sq_pessoa and b.sq_pessoa_pai = w_cliente)
   where b.nome_resumido_ind = 'TORQUATO';

  -- Cria os projetos
  for crec in c_proj loop
    -- Verifica se o projeto já existe
    select count(sq_siw_solicitacao) into w_existe from siw_solicitacao where codigo_interno = 'PACITO-'||trim(to_char(crec.id_pacito));
    
    If w_existe = 0 Then
      -- recupera chaves do sispac para gravar no campo de palavras-chave
      w_texto := '';
      for drec in c_comp(crec.id_pacito) loop
         w_texto := w_texto || ', ' ||trim(to_char(drec.id_sispac));
      end loop;
      w_texto := 'Códigos SISPAC: '||substr(w_texto,3);
      
      -- Grava dados gerais do projeto
      sp_putprojetogeral(p_operacao          => 'I',
                         p_chave             => null,
                         p_copia             => null,
                         p_menu              => w_menu_prj,
                         p_unidade           => w_unid_usc, -- unidade do usuário suporte
                         p_solicitante       => w_usuario, -- Torquato
                         p_proponente        => null,
                         p_cadastrador       => w_usu_cad, -- usuário suporte
                         p_executor          => null,
                         p_plano             => crec.sq_plano,
                         p_objetivo          => crec.sq_peobjetivo,
                         p_sqcc              => null,
                         p_solic_pai         => null,
                         p_descricao         => null,
                         p_justificativa     => null,
                         p_inicio            => crec.inicio,
                         p_fim               => crec.fim,
                         p_valor             => crec.orcamento,
                         p_data_hora         => w_data_hora,
                         p_unid_resp         => crec.sq_unidade, -- Órgão externo responsável pela execução.
                         p_codigo            => 'PACITO-'||trim(to_char(crec.id_pacito)),
                         p_titulo            => substr(crec.nome,1,100),
                         p_prioridade        => 2, -- Normal
                         p_aviso             => 'S',
                         p_dias              => 180,
                         p_aviso_pacote      => 'N',
                         p_dias_pacote       => 0,
                         p_cidade            => crec.sq_cidade,
                         p_palavra_chave     => w_texto,
                         p_vincula_contrato  => 'N',
                         p_vincula_viagem    => 'N',
                         p_sq_acao_ppa       => null,
                         p_sq_orprioridade   => null,
                         p_selecionada_mpog  => null,
                         p_selecionada_relev => null,
                         p_sq_tipo_pessoa    => null,
                         p_chave_nova        => w_chave);
                         
      -- Grava uma etapa para o projeto
      sp_putprojetoetapa(p_operacao          => 'I',
                         p_chave             => w_chave,
                         p_chave_aux         => null,
                         p_chave_pai         => null,
                         p_titulo            => substr(crec.nome,1,100),
                         p_descricao         => substr(crec.nome,1,2000),
                         p_ordem             => 1,
                         p_inicio            => crec.inicio,
                         p_fim               => crec.fim,
                         p_perc_conclusao    => 0,
                         p_orcamento         => crec.orcamento,
                         p_sq_pessoa         => w_usuario, -- Torquato
                         p_sq_unidade        => crec.sq_unidade, -- Órgão externo responsável pela execução.
                         p_vincula_atividade => 'S',
                         p_vincula_contrato  => 'N',
                         p_usuario           => w_usu_cad, -- usuário suporte
                         p_programada        => 'N',
                         p_cumulativa        => 'N',
                         p_quantidade        => 0,
                         p_unidade_medida    => null,
                         p_pacote            => 'S',
                         p_base              => 4, -- Município
                         p_pais              => crec.sq_pais,
                         p_regiao            => null,
                         p_uf                => crec.co_uf,
                         p_cidade            => crec.sq_cidade,
                         p_peso              => 1);

      -- Recupera a chave da etapa gravada
      select sq_projeto_etapa into w_chave_etapa from pj_projeto_etapa where sq_siw_solicitacao = w_chave;
      
      -- Grava restrições do projeto
      for drec in c_restricao(crec.id_pacito) loop
        sp_putsolicrestricao(p_operacao           => 'I',
                             p_chave              => w_chave,
                             p_chave_aux          => null,
                             p_pessoa             => w_usuario, -- Torquato
                             p_pessoa_atualizacao => w_usu_cad, -- usuário suporte
                             p_tipo_restricao     => drec.sq_tipo_restricao,
                             p_risco              => 'S',
                             p_problema           => 'N',
                             p_descricao          => drec.descricao,
                             p_probabilidade      => 3,-- Média
                             p_impacto            => 4, -- alto
                             p_criticidade        => null, -- calculado pelo sistema
                             p_estrategia         => 'A', -- Aceitar
                             p_acao_resposta      => 'A ser inserido.',
                             p_fase_atual         => 'D', -- Apenas definido
                             p_data_situacao      => null,
                             p_situacao_atual     => null);
      
        -- Recupera a chave do risco gravado
        select max(sq_siw_restricao) into w_chave_risco from siw_restricao where sq_siw_solicitacao = w_chave;

        -- Vincula a restrição à etapa
        sp_putrestricaoetapa(p_operacao         => 'I',
                             p_chave            => w_chave_risco,
                             p_sq_projeto_etapa => w_chave_etapa);
      end loop;
    End If;
  end loop;
end CARREGA_PAC;
/
