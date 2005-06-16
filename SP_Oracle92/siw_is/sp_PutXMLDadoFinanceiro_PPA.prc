create or replace procedure sp_PutXMLDadoFinanceiro_PPA
   (p_cliente            in number   default null,
    p_ano                in number   default null,
    p_cd_programa        in varchar2 default null,
    p_cd_acao_ppa        in varchar2 default null,
    p_cd_localizador_ppa in varchar2 default null,
    p_cd_fonte           in varchar2 default null,
    p_cd_natureza        in varchar2 default null,
    p_cd_tipo_despesa    in number   default null,
    p_valor_ano_1        in number   default null,
    p_valor_ano_2        in number   default null,
    p_valor_ano_3        in number   default null,
    p_valor_ano_4        in number   default null,
    p_valor_ano_5        in number   default null,
    p_valor_ano_6        in number   default null,
    p_observacao         in varchar2 default null
   ) is
   w_cont     number(4);
   w_operacao varchar2(1);

begin
   select count(*) into w_cont 
     from is_ppa_dado_financeiro a 
    where a.cd_tipo_despesa    = p_cd_tipo_despesa
      and a.cd_natureza        = p_cd_natureza
      and a.cd_fonte           = p_cd_fonte
      and a.cd_localizador_ppa = p_cd_localizador_ppa
      and a.cd_acao_ppa        = p_cd_acao_ppa
      and a.cd_programa        = p_cd_programa 
      and a.cliente            = p_cliente
      and a.ano                = p_ano;
   If w_cont = 0 
      Then w_operacao := 'I';
      Else w_operacao := 'A';
   End If;
      
   If w_operacao = 'I' Then
      -- Insere registro
      insert into is_ppa_dado_financeiro (cliente, ano, cd_programa, cd_acao_ppa, cd_localizador_ppa,
                                          cd_fonte, cd_natureza, cd_tipo_despesa, valor_ano_1, valor_ano_2, 
                                          valor_ano_3, valor_ano_4, valor_ano_5, valor_ano_6, observacao,
                                          flag_inclusao, flag_alteracao)
      values (p_cliente, p_ano, p_cd_programa, p_cd_acao_ppa, p_cd_localizador_ppa,
              p_cd_fonte, p_cd_natureza, p_cd_tipo_despesa, p_valor_ano_1, p_valor_ano_2, 
              p_valor_ano_3, p_valor_ano_4, p_valor_ano_5, p_valor_ano_6, p_observacao, 
              sysdate, sysdate);
   Else
      -- Altera registro
      update is_ppa_dado_financeiro set
         valor_ano_1        = p_valor_ano_1,
         valor_ano_2        = p_valor_ano_2,
         valor_ano_3        = p_valor_ano_3,
         valor_ano_4        = p_valor_ano_4,
         valor_ano_5        = p_valor_ano_5,
         valor_ano_6        = p_valor_ano_6,
         observacao         = p_observacao,
         flag_alteracao     = sysdate
       where cd_tipo_despesa    = p_cd_tipo_despesa
         and cd_natureza        = p_cd_natureza
         and cd_fonte           = p_cd_fonte
         and cd_localizador_ppa = p_cd_localizador_ppa
         and cd_acao_ppa        = p_cd_acao_ppa
         and cd_programa        = p_cd_programa
         and cliente            = p_cliente
         and ano                = p_ano;
   End If;
end sp_PutXMLDadoFinanceiro_PPA;
/
