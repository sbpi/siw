create or replace procedure sp_PutXMLDadoFisico_PPA
   (p_cliente            in number   default null,
    p_ano                in number   default null,
    p_cd_programa        in varchar2 default null,
    p_cd_acao_ppa        in varchar2 default null,
    p_cd_localizador_ppa in varchar2 default null,
    p_qtd_ano_1          in number   default null,
    p_qtd_ano_2          in number   default null,
    p_qtd_ano_3          in number   default null,
    p_qtd_ano_4          in number   default null,
    p_qtd_ano_5          in number   default null,
    p_qtd_ano_6          in number   default null,
    p_observacao         in varchar2 default null,
    p_cumulativa         in varchar2 default null
   ) is
   w_cont     number(4);
   w_operacao varchar2(1);

begin
   select count(*) into w_cont 
     from is_ppa_dado_fisico a 
    where a.cd_localizador_ppa = p_cd_localizador_ppa
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
      insert into is_ppa_dado_fisico (cliente, ano, cd_programa, cd_acao_ppa, cd_localizador_ppa,
                                      qtd_ano_1, qtd_ano_2, qtd_ano_3, qtd_ano_4, qtd_ano_5, qtd_ano_6,
                                      observacao, cumulativa, flag_inclusao, flag_alteracao)
      values (p_cliente, p_ano, p_cd_programa, p_cd_acao_ppa, p_cd_localizador_ppa,
              p_qtd_ano_1, p_qtd_ano_2, p_qtd_ano_3, p_qtd_ano_4, p_qtd_ano_5, p_qtd_ano_6,
              p_observacao, p_cumulativa, sysdate, sysdate);
   Else
      -- Altera registro
      update is_ppa_dado_fisico set
         qtd_ano_1          = p_qtd_ano_1,
         qtd_ano_2          = p_qtd_ano_2,
         qtd_ano_3          = p_qtd_ano_3,
         qtd_ano_4          = p_qtd_ano_4,
         qtd_ano_5          = p_qtd_ano_5,
         qtd_ano_6          = p_qtd_ano_6,
         observacao         = p_observacao,
         cumulativa         = p_cumulativa,
         flag_alteracao     = sysdate
       where cd_localizador_ppa = p_cd_localizador_ppa
         and cd_acao_ppa        = p_cd_acao_ppa
         and cd_programa        = p_cd_programa
         and cliente            = p_cliente
         and ano                = p_ano;
   End If;
end sp_PutXMLDadoFisico_PPA;
/
