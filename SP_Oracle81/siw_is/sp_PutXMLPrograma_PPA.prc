create or replace procedure sp_PutXMLPrograma_PPA
   (p_cliente        in number   default null,
    p_ano            in number   default null,
    p_chave          in varchar2 default null,
    p_orgao          in varchar2 default null,
    p_tipo_org       in varchar2 default null,
    p_orgao_siorg    in number   default null,
    p_tipo_prog      in number   default null,
    p_nome           in varchar2 default null,
    p_mes_ini        in varchar2 default null,
    p_ano_ini        in varchar2 default null,
    p_mes_fim        in varchar2 default null,
    p_ano_fim        in varchar2 default null,
    p_objetivo       in varchar2 default null,
    p_publico_alvo   in varchar2 default null,
    p_justificativa  in varchar2 default null,
    p_estrategia     in varchar2 default null,
    p_valor_estimado in number   default null,
    p_temporario     in varchar2 default null,
    p_padronizado    in varchar2 default null,
    p_observacao     in varchar2 default null
   ) is
   w_cont     number(4);
   w_operacao varchar2(1);

begin
   select count(*) into w_cont 
     from is_ppa_programa a 
    where a.cd_programa = p_chave 
      and a.cliente     = p_cliente
      and a.ano         = p_ano;
   If w_cont = 0 
      Then w_operacao := 'I';
      Else w_operacao := 'A';
   End If;
      
   If w_operacao = 'I' Then
      -- Insere registro
      insert into is_ppa_programa (cliente, ano, cd_programa, cd_orgao, cd_tipo_orgao, cd_orgao_siorg, cd_tipo_programa,
                                   nome, mes_inicio, ano_inicio, mes_termino, ano_termino, objetivo, publico_alvo,
                                   justificativa, estrategia, valor_estimado, temporario, padronizado, observacao,
                                   flag_inclusao, flag_alteracao)
      values (p_cliente, p_ano, p_chave, p_orgao, p_tipo_org, p_orgao_siorg, p_tipo_prog, 
              p_nome, p_mes_ini, p_ano_ini, p_mes_fim, p_ano_fim, p_objetivo, p_publico_alvo,
              p_justificativa, p_estrategia, p_valor_estimado, p_temporario, p_padronizado, p_observacao,
              sysdate, sysdate);
   Else
      -- Altera registro
      update is_ppa_programa set
         cd_orgao           = p_orgao,
         cd_tipo_orgao      = p_tipo_org,
         cd_orgao_siorg     = p_orgao_siorg,
         cd_tipo_programa   = p_tipo_prog,
         nome               = p_nome,
         mes_inicio         = p_mes_ini,
         ano_inicio         = p_ano_ini,
         mes_termino        = p_mes_fim,
         ano_termino        = p_ano_fim,
         objetivo           = p_objetivo,
         publico_alvo       = p_publico_alvo,
         justificativa      = p_justificativa,
         estrategia         = p_estrategia,
         valor_estimado     = p_valor_estimado,
         temporario         = p_temporario,
         padronizado        = p_padronizado,
         observacao         = p_observacao,
         flag_alteracao     = sysdate
       where cd_programa      = p_chave
         and cliente          = p_cliente
         and ano              = p_ano;
   End If;
end sp_PutXMLPrograma_PPA;
/
