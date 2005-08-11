create or replace procedure sp_PutXMLDadoFisico_SIG
   (p_cliente            in number   default null,
    p_ano                in number   default null,
    p_cd_programa        in varchar2 default null,
    p_cd_acao            in varchar2 default null,
    p_cd_subacao         in varchar2 default null,
    p_cd_regiao          in varchar2 default null,
    p_cron_ini_mes_1     in number   default null,
    p_cron_ini_mes_2     in number   default null,
    p_cron_ini_mes_3     in number   default null,
    p_cron_ini_mes_4     in number   default null,
    p_cron_ini_mes_5     in number   default null,
    p_cron_ini_mes_6     in number   default null,
    p_cron_ini_mes_7     in number   default null,
    p_cron_ini_mes_8     in number   default null,
    p_cron_ini_mes_9     in number   default null,
    p_cron_ini_mes_10    in number   default null,
    p_cron_ini_mes_11    in number   default null,
    p_cron_ini_mes_12    in number   default null,
    p_cron_mes_1         in number   default null,
    p_cron_mes_2         in number   default null,
    p_cron_mes_3         in number   default null,
    p_cron_mes_4         in number   default null,
    p_cron_mes_5         in number   default null,
    p_cron_mes_6         in number   default null,
    p_cron_mes_7         in number   default null,
    p_cron_mes_8         in number   default null,
    p_cron_mes_9         in number   default null,
    p_cron_mes_10        in number   default null,
    p_cron_mes_11        in number   default null,
    p_cron_mes_12        in number   default null,                                            
    p_real_mes_1         in number   default null,    
    p_real_mes_2         in number   default null,    
    p_real_mes_3         in number   default null,    
    p_real_mes_4         in number   default null,    
    p_real_mes_5         in number   default null,    
    p_real_mes_6         in number   default null,    
    p_real_mes_7         in number   default null,    
    p_real_mes_8         in number   default null,    
    p_real_mes_9         in number   default null,    
    p_real_mes_10        in number   default null,    
    p_real_mes_11        in number   default null,    
    p_real_mes_12        in number   default null,
    p_previsao_ano       in number   default null,    
    p_cron_ini_ano       in number   default null,    
    p_atual_ano          in number   default null,        
    p_cron_ano           in number   default null,            
    p_real_ano           in number   default null,            
    p_comentario_execucao in varchar2 default null
   ) is
   w_cont     number(4);
   w_cont1    number(4);
   w_operacao varchar2(1);

begin
   select count(*) into w_cont 
     from is_sig_dado_fisico a 
    where a.cd_regiao          = p_cd_regiao
      and a.cd_subacao         = p_cd_subacao
      and a.cd_acao            = p_cd_acao
      and a.cd_programa        = p_cd_programa 
      and a.cliente            = p_cliente
      and a.ano                = p_ano;
   If w_cont = 0 Then 
      select count(*) into w_cont1 
        from is_sig_acao a 
       where a.cd_subacao  = p_cd_subacao
         and a.cd_acao     = p_cd_acao
         and a.cd_programa = p_cd_programa 
         and a.cliente     = p_cliente
         and a.ano         = p_ano;
      If w_cont1 > 0 Then
         w_operacao := 'I';
      End If;
   Else w_operacao := 'A';
   End If;
      
   If w_operacao = 'I' Then
      -- Insere registro
      insert into is_sig_dado_fisico (cliente, ano, cd_programa, cd_acao, cd_subacao, cd_regiao,
                                      cron_ini_mes_1, cron_ini_mes_2, cron_ini_mes_3, cron_ini_mes_4, cron_ini_mes_5, cron_ini_mes_6,
                                      cron_ini_mes_7, cron_ini_mes_8, cron_ini_mes_9, cron_ini_mes_10, cron_ini_mes_11, cron_ini_mes_12,
                                      cron_mes_1, cron_mes_2, cron_mes_3, cron_mes_4, cron_mes_5, cron_mes_6,
                                      cron_mes_7, cron_mes_8, cron_mes_9, cron_mes_10, cron_mes_11, cron_mes_12,
                                      real_mes_1, real_mes_2, real_mes_3, real_mes_4, real_mes_5, real_mes_6, 
                                      real_mes_7, real_mes_8, real_mes_9, real_mes_10, real_mes_11, real_mes_12,
                                      previsao_ano, cron_ini_ano, atual_ano, cron_ano, real_ano, comentario_execucao, 
                                      flag_inclusao, flag_alteracao)
      values (p_cliente, p_ano, p_cd_programa, p_cd_acao, p_cd_subacao, p_cd_regiao,
              p_cron_ini_mes_1, p_cron_ini_mes_2, p_cron_ini_mes_3, p_cron_ini_mes_4, p_cron_ini_mes_5, p_cron_ini_mes_6,
              p_cron_ini_mes_7, p_cron_ini_mes_8, p_cron_ini_mes_9, p_cron_ini_mes_10, p_cron_ini_mes_11, p_cron_ini_mes_12,
              p_cron_mes_1, p_cron_mes_2, p_cron_mes_3, p_cron_mes_4, p_cron_mes_5, p_cron_mes_6,
              p_cron_mes_7, p_cron_mes_8, p_cron_mes_9, p_cron_mes_10, p_cron_mes_11, p_cron_mes_12,
              p_real_mes_1, p_real_mes_2, p_real_mes_3, p_real_mes_4, p_real_mes_5, p_real_mes_6, 
              p_real_mes_7, p_real_mes_8, p_real_mes_9, p_real_mes_10, p_real_mes_11, p_real_mes_12,
              p_previsao_ano, p_cron_ini_ano, p_atual_ano, p_cron_ano, p_real_ano, p_comentario_execucao, 
              sysdate, sysdate);
   ElsIf w_operacao = 'A' Then
      -- Altera registro
      update is_sig_dado_fisico set
         cron_ini_mes_1     = p_cron_ini_mes_1,
         cron_ini_mes_2     = p_cron_ini_mes_2,
         cron_ini_mes_3     = p_cron_ini_mes_3,
         cron_ini_mes_4     = p_cron_ini_mes_4,
         cron_ini_mes_5     = p_cron_ini_mes_5,
         cron_ini_mes_6     = p_cron_ini_mes_6,
         cron_ini_mes_7     = p_cron_ini_mes_7,
         cron_ini_mes_8     = p_cron_ini_mes_8,
         cron_ini_mes_9     = p_cron_ini_mes_9,
         cron_ini_mes_10    = p_cron_ini_mes_10,
         cron_ini_mes_11    = p_cron_ini_mes_11,
         cron_ini_mes_12    = p_cron_ini_mes_12,
         cron_mes_1         = p_cron_mes_1,
         cron_mes_2         = p_cron_mes_2,
         cron_mes_3         = p_cron_mes_3,
         cron_mes_4         = p_cron_mes_4,
         cron_mes_5         = p_cron_mes_5,
         cron_mes_6         = p_cron_mes_6,
         cron_mes_7         = p_cron_mes_7,
         cron_mes_8         = p_cron_mes_8,
         cron_mes_9         = p_cron_mes_9,
         cron_mes_10        = p_cron_mes_10,
         cron_mes_11        = p_cron_mes_11,
         cron_mes_12        = p_cron_mes_12,                                                                                                   
         real_mes_1         = p_real_mes_1,
         real_mes_2         = p_real_mes_2,
         real_mes_3         = p_real_mes_3,
         real_mes_4         = p_real_mes_4,
         real_mes_5         = p_real_mes_5,
         real_mes_6         = p_real_mes_6,
         real_mes_7         = p_real_mes_7,
         real_mes_8         = p_real_mes_8,
         real_mes_9         = p_real_mes_9,
         real_mes_10        = p_real_mes_10,
         real_mes_11        = p_real_mes_11,
         real_mes_12        = p_real_mes_12,
         previsao_ano       = p_previsao_ano,
         cron_ini_ano       = p_cron_ini_ano,
         atual_ano          = p_atual_ano,
         cron_ano           = p_cron_ano,
         real_ano           = p_real_ano,
         comentario_execucao = p_comentario_execucao,
         flag_alteracao     = sysdate
       where cd_regiao          = p_cd_regiao
         and cd_subacao         = p_cd_subacao
         and cd_acao            = p_cd_acao
         and cd_programa        = p_cd_programa
         and cliente            = p_cliente
         and ano                = p_ano;
   End If;
end sp_PutXMLDadoFisico_SIG;
/
