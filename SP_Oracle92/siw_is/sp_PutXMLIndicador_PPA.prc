create or replace procedure sp_PutXMLIndicador_PPA
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
    p_valor_ano_1    in number   default null,
    p_valor_ano_2    in number   default null,
    p_valor_ano_3    in number   default null,
    p_valor_ano_4    in number   default null,
    p_valor_ano_5    in number   default null,
    p_valor_ano_6    in number   default null, 
    p_valor_ref      in number   default null,  
    p_valor_final    in number   default null,                         
    p_apurado_ano_1  in varchar2 default null,
    p_apurado_ano_2  in varchar2 default null,
    p_apurado_ano_3  in varchar2 default null,
    p_apurado_ano_4  in varchar2 default null,
    p_apurado_ano_5  in varchar2 default null,
    p_apurado_ano_6  in varchar2 default null,                    
    p_apurado_ref    in varchar2 default null,
    p_apurado_final  in varchar2 default null,
    p_apuracao       in varchar2 default null,
    p_observacao     in varchar2 default null
   ) is
   w_cont     number(4);
   w_operacao varchar2(1);

begin
   select count(*) into w_cont 
     from is_ppa_indicador a 
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
      insert into is_ppa_indicador (cliente, ano, cd_programa, cd_indicador, cd_unidade_medida, cd_periodicidade,
                                    cd_base_geografica, nome, fonte, formula, valor_ano_1, valor_ano_2, valor_ano_3,
                                    valor_ano_4, valor_ano_5, valor_ano_6, valor_referencia, valor_final, apurado_ano_1,
                                    apurado_ano_2, apurado_ano_3, apurado_ano_4, apurado_ano_5, apurado_ano_6, 
                                    apurado_referencia, apurado_final, apuracao, observacao, flag_inclusao, flag_alteracao)
      values (p_cliente, p_ano, p_cd_programa, p_chave, p_unidade_med, p_periodicidade, 
              p_base_geo, p_nome, p_fonte, p_formula, p_valor_ano_1, p_valor_ano_2, p_valor_ano_3,
              p_valor_ano_4, p_valor_ano_5, p_valor_ano_6, p_valor_ref, p_valor_final, p_apurado_ano_1,
              p_apurado_ano_2, p_apurado_ano_3, p_apurado_ano_4, p_apurado_ano_5, p_apurado_ano_6,
              p_apurado_ref, p_apurado_final, to_date(p_apuracao,'yyyy-mm-dd hh24:mi:ss'), p_observacao, sysdate, sysdate);
   Else
      -- Altera registro
      update is_ppa_indicador set
         cd_unidade_medida  = p_unidade_med,
         cd_periodicidade   = p_periodicidade,
         cd_base_geografica = p_base_geo,
         nome               = p_nome,
         fonte              = p_fonte,
         formula            = p_formula,
         valor_ano_1        = p_valor_ano_1,
         valor_ano_2        = p_valor_ano_2,
         valor_ano_3        = p_valor_ano_3,
         valor_ano_4        = p_valor_ano_4,
         valor_ano_5        = p_valor_ano_5,
         valor_ano_6        = p_valor_ano_6,
         valor_referencia   = p_valor_ref,
         valor_final        = p_valor_final,
         apurado_ano_1      = p_apurado_ano_1,
         apurado_ano_2      = p_apurado_ano_2,
         apurado_ano_3      = p_apurado_ano_3,
         apurado_ano_4      = p_apurado_ano_4,
         apurado_ano_5      = p_apurado_ano_5,
         apurado_ano_6      = p_apurado_ano_6,
         apurado_referencia = p_apurado_ref,
         apurado_final      = p_apurado_final,
         apuracao           =  to_date(p_apuracao,'yyyy-mm-dd hh24:mi:ss'),
         observacao         = p_observacao,
         flag_alteracao     = sysdate
       where cd_indicador     = p_chave
         and cd_programa      = p_cd_programa
         and cliente          = p_cliente
         and ano              = p_ano;
   End If;
end sp_PutXMLIndicador_PPA;
/
