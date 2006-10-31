create or replace procedure sp_PutXMLPrograma_SIG
   (p_cliente        in number   default null,
    p_ano            in number   default null,
    p_chave          in varchar2 default null,
    p_tipo_org       in varchar2 default null,    
    p_orgao          in varchar2 default null,
    p_nome           in varchar2 default null,    
    p_tipo_prog      in number   default null,
    p_macro          in varchar2 default null,          
    p_mes_ini        in varchar2 default null,
    p_ano_ini        in varchar2 default null,
    p_mes_fim        in varchar2 default null,
    p_ano_fim        in varchar2 default null,
    p_objetivo       in varchar2 default null,
    p_publico_alvo   in varchar2 default null,
    p_justificativa  in varchar2 default null,
    p_estrategia     in varchar2 default null,
    p_ln_programa    in varchar2 default null,
    p_valor_estimado in number   default null,
    p_valor_ppa      in number   default null,
    p_temporario     in varchar2 default null,
    p_estruturante   in varchar2 default null,
    p_contexto       in varchar2 default null,
    p_atual_contexto in date     default null,
    p_estagio        in varchar2 default null,
    p_andamento      in varchar2 default null,
    p_cronograma     in varchar2 default null,
    p_perc_execucao  in number   default null,
    p_comentario_sit in varchar2 default null,
    p_atual_sit      in date     default null,
    p_situacao_atual in varchar2 default null,
    p_resultados_obt in varchar2 default null,
    p_atual_sit_atual in date    default null,
    p_coment_execucao in varchar2 default null
   ) is
   w_cont     number(4);
   w_operacao varchar2(1);

begin
   select count(*) into w_cont 
     from is_sig_programa a 
    where a.cd_programa = p_chave 
      and a.cliente     = p_cliente
      and a.ano         = p_ano;
   If w_cont = 0 
      Then w_operacao := 'I';
      Else w_operacao := 'A';
   End If;
      
   If w_operacao = 'I' Then
      -- Insere registro
      insert into is_sig_programa (cliente, ano, cd_programa, cd_tipo_orgao, cd_orgao, nome, cd_tipo_programa,
                                   cd_macro, mes_inicio, ano_inicio, mes_termino, ano_termino, objetivo, publico_alvo,
                                   justificativa, estrategia, ln_programa, valor_estimado, valor_ppa, 
                                   temporario, estruturante, contexto, atualizacao_contexto, cd_estagio, cd_andamento, 
                                   cd_cronograma, percentual_execucao, comentario_situacao, atualizacao_situacao,
                                   situacao_atual, resultados_obtidos, atualizacao_situacao_atual, comentario_execucao,
                                   flag_inclusao, flag_alteracao)
      values (p_cliente, p_ano, p_chave, p_tipo_org, p_orgao, p_nome, p_tipo_prog, 
              p_macro, p_mes_ini, p_ano_ini, p_mes_fim, p_ano_fim, p_objetivo, p_publico_alvo,
              p_justificativa, p_estrategia, p_ln_programa, p_valor_estimado, p_valor_ppa, 
              p_temporario, p_estruturante, p_contexto, p_atual_contexto, p_estagio, p_andamento,
              p_cronograma, p_perc_execucao, p_comentario_sit, p_atual_sit, 
              p_situacao_atual, p_resultados_obt, p_atual_sit_atual, p_coment_execucao,
              sysdate, sysdate);
   Else
      -- Altera registro
      update is_sig_programa set
         cd_tipo_orgao              = p_tipo_org,
         cd_orgao                   = p_orgao,
         nome                       = p_nome,
         cd_tipo_programa           = p_tipo_prog,
         cd_macro                   = p_macro,
         mes_inicio                 = p_mes_ini,
         ano_inicio                 = p_ano_ini,
         mes_termino                = p_mes_fim,
         ano_termino                = p_ano_fim,
         objetivo                   = p_objetivo,
         publico_alvo               = p_publico_alvo,
         justificativa              = p_justificativa,
         estrategia                 = p_estrategia,
         ln_programa                = p_ln_programa,
         valor_estimado             = p_valor_estimado,
         valor_ppa                  = p_valor_ppa,
         temporario                 = p_temporario,
         estruturante               = p_estruturante,
         contexto                   = p_contexto,
         atualizacao_contexto       = p_atual_contexto,
         cd_estagio                 = p_estagio,
         cd_andamento               = p_andamento,
         cd_cronograma              = p_cronograma,
         percentual_execucao        = p_perc_execucao,
         comentario_situacao        = p_comentario_sit,
         atualizacao_situacao       = p_atual_sit,
         situacao_atual             = p_situacao_atual,
         resultados_obtidos         = p_resultados_obt,
         atualizacao_situacao_atual = p_atual_sit_atual,
         comentario_execucao        = p_coment_execucao,      
         flag_alteracao             = sysdate
       where cd_programa      = p_chave
         and cliente          = p_cliente
         and ano              = p_ano;
   End If;
end sp_PutXMLPrograma_SIG;
/
