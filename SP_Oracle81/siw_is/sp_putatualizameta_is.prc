create or replace procedure SP_PutAtualizaMeta_IS
   (p_chave               in number,
    p_chave_aux           in number,
    p_perc_conclusao      in number,
    p_situacao_atual      in varchar2  default null,
    p_exequivel           in varchar2,
    p_justificativa_inex  in varchar2  default null,
    p_outras_medidas      in varchar2  default null
   ) is
   w_cd_subacao number(4);

   cursor c_atualiza_acao is
      select 'update is_sig_acao '||
             '   set situacao_atual = '''||p_situacao_atual||''', '||
             '       percentual_execucao = '||trunc(p_perc_conclusao)||', '||
             '       flag_alteracao = sysdate'||
             ' where ano         = '||a.ano||' '||
             '   and cliente     = '||a.cliente||'  '||
             '   and cd_programa = '''||a.cd_programa||''' '||
             '   and cd_acao     = '''||a.cd_acao||''' '||
             '   and cd_subacao  = '''||a.cd_subacao ||''' ' w_sql
        from is_acao    a
       where a.sq_siw_solicitacao = p_chave;
begin
   -- Atualiza a tabela de metas da ação
   Update is_meta set
       perc_conclusao            = p_perc_conclusao,
       situacao_atual            = p_situacao_atual,
       exequivel                 = p_exequivel,
       justificativa_inexequivel = p_justificativa_inex,
       outras_medidas            = p_outras_medidas,
       ultima_atualizacao    = sysdate
   where sq_siw_solicitacao = p_chave
     and sq_meta            = p_chave_aux;
   
   select cd_subacao into w_cd_subacao from is_meta where sq_meta = p_chave_aux;
   
   If Nvl(w_cd_subacao,0) > 0 Then
      for crec in c_atualiza_acao loop
         EXECUTE IMMEDIATE crec.w_sql;
      end loop;
   End If;
end SP_PutAtualizaMeta_IS;
/
