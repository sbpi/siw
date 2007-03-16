create or replace procedure SP_PutProjetoEtapa
   (p_operacao            in  varchar2,
    p_chave               in number,
    p_chave_aux           in number    default null,
    p_chave_pai           in number    default null,
    p_titulo              in varchar2  default null,
    p_descricao           in varchar2  default null,
    p_ordem               in number    default null,
    p_inicio              in date      default null,
    p_fim                 in date      default null,
    p_perc_conclusao      in number    default null,
    p_orcamento           in number    default null,
    p_sq_pessoa           in number,
    p_sq_unidade          in number,
    p_vincula_atividade   in varchar2  default null,
    p_vincula_contrato    in varchar2  default null,
    p_usuario             in number,
    p_programada          in varchar2  default null,
    p_cumulativa          in varchar2  default null,
    p_quantidade          in number    default null,
    p_unidade_medida      in varchar2  default null,
    p_pacote              in varchar2  default null,
    p_base                in number    default null,
    p_pais                in number    default null,
    p_regiao              in number    default null,
    p_uf                  in varchar2  default null,
    p_cidade              in number    default null,
    p_peso                in number    default null
   ) is
   w_chave    number(18);
   w_pai      number(18);
begin
   If p_operacao = 'I' Then -- Inclusão
      -- Recupera a próxima chave
      select sq_projeto_etapa.nextval into w_chave from dual;
      
      -- Insere registro na tabela de etapas do projeto
      Insert Into pj_projeto_etapa 
         ( sq_projeto_etapa,    sq_siw_solicitacao, sq_etapa_pai,            ordem, 
           titulo,              descricao,          inicio_previsto,         fim_previsto, 
           perc_conclusao,      orcamento,          sq_pessoa,               sq_unidade,
           vincula_atividade,   vincula_contrato,   sq_pessoa_atualizacao,   ultima_atualizacao,
           programada,          cumulativa,         quantidade,              unidade_medida,
           pacote_trabalho,     base_geografica,    sq_pais,                 sq_regiao,
           co_uf,               sq_cidade,          peso)
      Values
         ( w_chave,             p_chave,            p_chave_pai,             p_ordem,
           p_titulo,            p_descricao,        p_inicio,                p_fim,
           p_perc_conclusao,    p_orcamento,        p_sq_pessoa,             p_sq_unidade,
           p_vincula_atividade, p_vincula_contrato, p_usuario,               sysdate,            
           p_programada,        p_cumulativa,       p_quantidade,            p_unidade_medida,
           p_pacote,            p_base,             p_pais,                  p_regiao,
           p_uf,                p_cidade,           p_peso);

      -- Recalcula os percentuais de execução dos pais da etapa
      sp_calculaPercEtapa(w_chave, null);
   
   Elsif p_operacao = 'A' Then -- Alteração
      -- Recupera a etapa pai
      select sq_etapa_pai into w_pai from pj_projeto_etapa where sq_projeto_etapa = p_chave_aux;

      -- Atualiza a tabela de etapas do projeto
      Update pj_projeto_etapa set
          sq_etapa_pai          = p_chave_pai,
          ordem                 = p_ordem,
          titulo                = p_titulo,
          descricao             = p_descricao,
          inicio_previsto       = p_inicio,
          fim_previsto          = p_fim,
          perc_conclusao        = coalesce(p_perc_conclusao,perc_conclusao),
          orcamento             = p_orcamento,
          sq_pessoa             = p_sq_pessoa,
          sq_unidade            = p_sq_unidade,
          vincula_atividade     = p_vincula_atividade,
          vincula_contrato      = p_vincula_contrato,
          programada            = p_programada,
          cumulativa            = p_cumulativa,
          quantidade            = p_quantidade,
          unidade_medida        = p_unidade_medida,
          sq_pessoa_atualizacao = p_usuario,
          ultima_atualizacao    = sysdate,
          pacote_trabalho       = p_pacote,
          base_geografica       = p_base,
          sq_pais               = p_pais,
          sq_regiao             = p_regiao,
          co_uf                 = p_uf,
          sq_cidade             = p_cidade,
          peso                  = p_peso
      where sq_siw_solicitacao = p_chave
        and sq_projeto_etapa   = p_chave_aux;

      -- Se houve alteração da subordinação, recalcula para o pai anterior
      If w_pai <> p_chave_pai Then
         -- Recalcula os percentuais de execução dos pais anteriores da etapa
         sp_calculaPercEtapa(null, w_pai);
      End If;
      
      -- Recalcula os percentuais de execução dos pais da etapa
      sp_calculaPercEtapa(p_chave_aux, null);
   
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Remove os registros de acompanhamento da execução
      delete pj_etapa_mensal a where a.sq_projeto_etapa = p_chave_aux;

      -- Recupera a etapa pai
      select sq_etapa_pai into w_pai from pj_projeto_etapa where sq_projeto_etapa = p_chave_aux;

      -- Remove o registro na tabela de etapas do projeto
      delete pj_projeto_etapa
       where sq_siw_solicitacao = p_chave
        and sq_projeto_etapa   = p_chave_aux;

      -- Recalcula os percentuais de execução dos pais da etapa
      If w_pai is not null Then sp_calculaPercEtapa(null, w_pai); End If;
   
   End If;
   
end SP_PutProjetoEtapa;
/
