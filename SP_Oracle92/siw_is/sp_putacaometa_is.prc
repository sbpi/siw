create or replace procedure SP_PutAcaoMeta_IS
   (p_operacao            in  varchar2,
    p_chave               in number,
    p_chave_aux           in number    default null,
    p_titulo              in varchar2  default null,
    p_descricao           in varchar2  default null,
    p_ordem               in number    default null,
    p_inicio              in date      default null,
    p_fim                 in date      default null,
    p_perc_conclusao      in number    default null,
    p_orcamento           in number    default null,
    p_programada          in varchar2  default null,
    p_cumulativa          in varchar2  default null,
    p_quantidade          in number    default null,
    p_unidade_medida      in varchar2  default null,
    p_pns                 in varchar2  default null
   ) is
   w_chave    number(18);
   w_total    float;
begin
   If p_operacao = 'I' Then -- Inclusão
      -- Recupera a próxima chave
      select sq_meta.nextval into w_chave from dual;
      
      -- Insere registro na tabela de metas da ação
      Insert Into is_meta 
         ( sq_meta,          sq_siw_solicitacao, ordem, 
           titulo,           descricao,          inicio_previsto,    fim_previsto, 
           perc_conclusao,   orcamento,          ultima_atualizacao,
           programada,       cumulativa,         quantidade,         unidade_medida)
      Values
         ( w_chave,           p_chave,            p_ordem,
           p_titulo,          p_descricao,        p_inicio,           p_fim,
           p_perc_conclusao,  p_orcamento,        sysdate,            
           p_programada,      p_cumulativa,       p_quantidade,       p_unidade_medida);
   Elsif p_operacao = 'A' Then -- Alteração
      If p_cumulativa = 'S' Then
         select count(*) into w_total from is_meta_execucao where sq_meta = p_chave_aux;
         If w_total > 0 Then
            select nvl(a.realizado,0) total
              into w_total
              from is_meta_execucao a
             where a.sq_meta = p_chave_aux
               and a.referencia = (select max(referencia) from is_meta_execucao where sq_meta = p_chave_aux);
         Else
            w_total := 0;
         End If;
      Else
         select sum(nvl(a.realizado,0)) total
           into w_total
           from is_meta_execucao a
          where a.sq_meta = p_chave_aux;
      End If;
      
      If Nvl(p_quantidade,0) = 0 Then
         If w_total > 0 Then w_total := 100; Else w_total := 0; End If;
      Else
         If w_total > 0 Then w_total := w_total/p_quantidade*100; Else w_total := 0; End If;
      End If;
      
       
      -- Atualiza a tabela de metas da ação
      Update is_meta set
          ordem                = p_ordem,
          titulo               = p_titulo,
          descricao            = p_descricao,
          inicio_previsto      = p_inicio,
          fim_previsto         = p_fim,
          perc_conclusao       = w_total,
          orcamento            = p_orcamento,
          programada           = p_programada,
          cumulativa           = p_cumulativa,
          quantidade           = p_quantidade,
          unidade_medida       = p_unidade_medida,
          ultima_atualizacao   = sysdate
      where sq_siw_solicitacao = p_chave
        and sq_meta            = p_chave_aux;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Remove os registros de acompanhamento da execução
      delete is_meta_execucao a where a.sq_meta = p_chave_aux;

      -- Remove o registro na tabela de meta da ação
      delete is_meta
       where sq_siw_solicitacao = p_chave
         and sq_meta            = p_chave_aux;
   End If;
end SP_PutAcaoMeta_IS;
/

