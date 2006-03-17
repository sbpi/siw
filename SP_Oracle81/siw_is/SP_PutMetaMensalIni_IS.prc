create or replace procedure SP_PutMetaMensalIni_IS
   (p_operacao            in varchar2,
    p_chave               in number,
    p_cliente             in number,
    p_cronogramado_1      in number,
    p_cronogramado_2      in number,
    p_cronogramado_3      in number,
    p_cronogramado_4      in number,
    p_cronogramado_5      in number,
    p_cronogramado_6      in number,
    p_cronogramado_7      in number,
    p_cronogramado_8      in number,
    p_cronogramado_9      in number,
    p_cronogramado_10     in number,
    p_cronogramado_11     in number,
    p_cronogramado_12     in number
   ) is

   w_cd_programa varchar2(4);
   w_cd_acao     varchar2(4);
   w_ano         number(4);
   w_cliente     number(4);
   w_cd_subacao  number(4);
begin   
   If p_operacao = 'W' Then
      -- Recupera a chave para atualização do campo meta_nao_cumulativa na tabela is_sig_acao
      select a.cd_programa, a.cd_acao, a.ano, a.cliente, b.cd_subacao 
        into w_cd_programa, w_cd_acao, w_ano, w_cliente, w_cd_subacao
        from is_acao     a,
             is_meta     b
       where a.sq_siw_solicitacao = b.sq_siw_solicitacao
         and b.sq_meta            = p_chave;

      update is_sig_dado_fisico set
             cron_ini_mes_1  = p_cronogramado_1,
             cron_ini_mes_2  = p_cronogramado_2,
             cron_ini_mes_3  = p_cronogramado_3,
             cron_ini_mes_4  = p_cronogramado_4,
             cron_ini_mes_5  = p_cronogramado_5,
             cron_ini_mes_6  = p_cronogramado_6,
             cron_ini_mes_7  = p_cronogramado_7,
             cron_ini_mes_8  = p_cronogramado_8,
             cron_ini_mes_9  = p_cronogramado_9,
             cron_ini_mes_10 = p_cronogramado_10,
             cron_ini_mes_11 = p_cronogramado_11,
             cron_ini_mes_12 = p_cronogramado_12,
             flag_alteracao  = sysdate
       where ano         = w_ano
         and cd_programa = w_cd_programa
         and cd_acao     = w_cd_acao 
         and cd_subacao  = w_cd_subacao
         and cliente     = w_cliente;     
   End If;
end SP_PutMetaMensalIni_IS;
/
