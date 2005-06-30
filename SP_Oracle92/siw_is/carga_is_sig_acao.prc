create or replace procedure CARGA_IS_SIG_ACAO(p_cliente in number, p_ano in number) is
  w_cd_programa varchar2(4) := '';
  w_cd_acao     varchar2(4) := '';
  w_cont        number(18) := 0;
  l_cont        varchar2(4);
  cursor c_ppa_acao is
    select a.cliente, a.ano, a.cd_programa, a.cd_acao, null cd_subacao, b.cd_regiao, 
           a.cd_tipo_acao, a.cd_produto,
           a.cd_unidade_medida, b.cd_localizador, a.cd_acao_ppa, a.ano is_ano, c.cd_orgao, c.cd_tipo_orgao, 
           a.cd_unidade, a.cd_tipo_unidade, a.nome nm_acao, d.nome||' - '||c.cd_unidade nm_subacao, 
           a.direta, a.descentralizada, a.linha_credito, a.mes_inicio, a.ano_inicio, a.mes_termino, 
           a.ano_termino, a.valor_ano_anterior, a.cd_sof_referencia
      from is_ppa_acao a
           inner join is_ppa_localizador b on (a.cliente = b.cliente and
                                               a.ano     = b.ano and
                                               a.cd_programa = b.cd_programa and
                                               a.cd_acao_ppa = b.cd_acao_ppa
                                              )
           inner join is_ppa_unidade     c on (a.cd_unidade = c.cd_unidade and
                                               a.cd_tipo_unidade = c.cd_tipo_unidade
                                              )
           inner join is_regiao          d on (b.cd_regiao = d.cd_regiao)
    where a.cliente  = p_cliente
      and ((p_cliente <> 362) or (p_cliente = 362 and c.cd_orgao = 36000))
      and a.ano      = p_ano
    order by a.cliente, a.ano, a.cd_programa, a.cd_acao, b.cd_localizador;
begin
  for crec in c_ppa_acao loop
     if crec.cd_programa = w_cd_programa and crec.cd_acao = w_cd_acao then
        w_cont := w_cont + 1;
     else
        w_cd_programa := crec.cd_programa;
        w_cd_acao     := crec.cd_acao;
        w_cont        := 1;
     end if;
     l_cont := replace(lpad(w_cont,4),' ','0');
     insert into is_sig_acao (cliente, ano, cd_programa, cd_acao, cd_subacao, cd_regiao,
            cd_tipo_acao, cd_localizador, cd_acao_ppa,
            is_ano, cd_orgao, cd_tipo_orgao, cd_unidade, cd_tipo_unidade, descricao_acao,
            descricao_subacao, direta, descentralizada, linha_credito, mes_inicio, ano_inicio,
            mes_termino, ano_termino, valor_ano_anterior, cd_sof_referencia)
     values (crec.cliente, crec.ano, crec.cd_programa, crec.cd_acao, l_cont, crec.cd_regiao,
            crec.cd_tipo_acao, crec.cd_localizador, crec.cd_acao_ppa,
            crec.is_ano, crec.cd_orgao, crec.cd_tipo_orgao, crec.cd_unidade, crec.cd_tipo_unidade, crec.nm_acao,
            crec.nm_subacao, crec.direta, crec.descentralizada, crec.linha_credito, crec.mes_inicio, crec.ano_inicio,
            crec.mes_termino, crec.ano_termino, crec.valor_ano_anterior, crec.cd_sof_referencia);
  end loop;
  commit;
end CARGA_IS_SIG_ACAO;
/
