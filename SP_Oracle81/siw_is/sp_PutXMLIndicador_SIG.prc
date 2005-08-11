create or replace procedure sp_PutXMLIndicador_SIG
   (p_cliente        in number   default null,
    p_ano            in number   default null,
    p_cd_programa    in varchar2 default null,
    p_chave          in number   default null,
    p_unidade_med    in number   default null,
    p_periodicidade  in number   default null,
    p_base_geo       in number   default null,
    p_nome           in varchar2 default null,
    p_fonte          in varchar2 default null,
    p_formula        in varchar2 default null,
    p_valor_apurado  in number   default null,  
    p_valor_ppa      in number   default null,                         
    p_valor_programa in number   default null,                             
    p_valor_mes_1    in number   default null,
    p_valor_mes_2    in number   default null,
    p_valor_mes_3    in number   default null,
    p_valor_mes_4    in number   default null,
    p_valor_mes_5    in number   default null,
    p_valor_mes_6    in number   default null,
    p_valor_mes_7    in number   default null,
    p_valor_mes_8    in number   default null,
    p_valor_mes_9    in number   default null,
    p_valor_mes_10   in number   default null,
    p_valor_mes_11   in number   default null,
    p_valor_mes_12   in number   default null,                                           
    p_apuracao       in date     default null
   ) is
   w_cont     number(4);
   w_operacao varchar2(1);
   w_data     date;

begin
   select count(*) into w_cont 
     from is_sig_indicador a 
    where a.cd_indicador = p_chave 
      and a.cd_programa  = p_cd_programa
      and a.cliente      = p_cliente
      and a.ano          = p_ano;
   If w_cont = 0 
      Then w_operacao := 'I';
      Else w_operacao := 'A';
   End If;
      
   If w_operacao = 'I' Then
      -- Insere registro
      insert into is_sig_indicador (cliente, ano, cd_programa, cd_indicador, cd_unidade_medida, cd_periodicidade,
                                    cd_base_geografica, nome, fonte, formula, valor_apurado, valor_ppa, valor_programa,
                                    valor_mes_1, valor_mes_2, valor_mes_3, valor_mes_4, valor_mes_5, valor_mes_6,  
                                    valor_mes_7, valor_mes_8, valor_mes_9, valor_mes_10, valor_mes_11, valor_mes_12, 
                                    apuracao, flag_inclusao, flag_alteracao)
      values (p_cliente, p_ano, p_cd_programa, p_chave, p_unidade_med, p_periodicidade, 
              p_base_geo, p_nome, p_fonte, p_formula, p_valor_apurado, p_valor_ppa, p_valor_programa,
              p_valor_mes_1, p_valor_mes_2, p_valor_mes_3, p_valor_mes_4, p_valor_mes_5, p_valor_mes_6,
              p_valor_mes_7, p_valor_mes_8, p_valor_mes_9, p_valor_mes_10, p_valor_mes_11, p_valor_mes_12,
              p_apuracao, sysdate, sysdate);
   Else
      -- Altera registro
      update is_sig_indicador set
         cd_unidade_medida  = p_unidade_med,
         cd_periodicidade   = p_periodicidade,
         cd_base_geografica = p_base_geo,
         nome               = p_nome,
         fonte              = p_fonte,
         formula            = p_formula,
         valor_ppa          = p_valor_ppa,
         valor_programa     = p_valor_programa,
         valor_mes_1        = p_valor_mes_1,
         valor_mes_2        = p_valor_mes_2,
         valor_mes_3        = p_valor_mes_3,
         valor_mes_4        = p_valor_mes_4,
         valor_mes_5        = p_valor_mes_5,
         valor_mes_6        = p_valor_mes_6,
         valor_mes_7        = p_valor_mes_7,
         valor_mes_8        = p_valor_mes_8,
         valor_mes_9        = p_valor_mes_9,
         valor_mes_10       = p_valor_mes_10,
         valor_mes_11       = p_valor_mes_11,
         valor_mes_12       = p_valor_mes_12,
         flag_alteracao     = sysdate
       where cd_indicador     = p_chave
         and cd_programa      = p_cd_programa
         and cliente          = p_cliente
         and ano              = p_ano;
     --- Verifica se a data de apuracao e o valor da apuração deve ser atualizados
     select apuracao into w_data 
       from is_sig_indicador
      where cd_indicador     = p_chave
        and cd_programa      = p_cd_programa
        and cliente          = p_cliente
        and ano              = p_ano;
     If p_apuracao > w_data Then
         update is_sig_indicador set
            valor_apurado      = p_valor_apurado,
            apuracao           = p_apuracao
          where cd_indicador     = p_chave
            and cd_programa      = p_cd_programa
            and cliente          = p_cliente
            and ano              = p_ano;
     End If;
   End If;
end sp_PutXMLIndicador_SIG;
/
