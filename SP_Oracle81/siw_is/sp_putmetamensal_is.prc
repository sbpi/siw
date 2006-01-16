create or replace procedure SP_PutMetaMensal_IS
   (p_operacao            in varchar2,
    p_chave               in number,
    p_realizado           in number   default null,
    p_revisado            in number   default null,
    p_referencia          in date     default null,
    p_cliente             in number   default null
   ) is
   w_cd_subacao number(4);
   w_sql        varchar2(2000);
   w_referencia number(2) := to_char(p_referencia,'mm');
   
   cursor c_atualiza_dado_fisico is
      select 'update is_sig_dado_fisico '||
             '   set real_mes_'|| w_referencia ||' = '||p_realizado||', '||
             '       cron_mes_'|| w_referencia ||' = '||p_revisado||', '||
             '       flag_alteracao = sysdate'||
             ' where ano         = '||b.ano||' '||
             '   and cliente     = '||b.cliente||'  '||
             '   and cd_programa = '''||b.cd_programa||''' '||
             '   and cd_acao     = '''||b.cd_acao||''' '||
             '   and cd_subacao  = '''||b.cd_subacao ||''' ' w_sql
        from is_meta    a,
             is_acao    b
       where a.sq_siw_solicitacao = b.sq_siw_solicitacao
         and a.sq_meta = p_chave;

begin   
   if p_operacao = 'E' Then
      -- Apaga todos os registros para que seja feita a atualização
      delete is_meta_execucao where sq_meta = p_chave;
   Elsif p_operacao = 'Z' Then
      select cd_subacao into w_cd_subacao from is_meta where sq_meta = p_chave;
      If Nvl(w_cd_subacao,0) > 0 Then
         for crec in c_atualiza_dado_fisico loop
            EXECUTE IMMEDIATE crec.w_sql;
         End loop;
      End If;
   Else
      -- Insere registro na tabela de meses da meta
      Insert Into is_meta_execucao
         ( sq_meta, referencia,   realizado, revisado, cliente)
      Values
         ( p_chave, last_day(p_referencia), p_realizado,  p_revisado, p_cliente);
   End If;
end SP_PutMetaMensal_IS;
/
