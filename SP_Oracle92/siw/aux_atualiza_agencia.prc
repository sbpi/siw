create or replace procedure aux_atualiza_agencia is
  cursor c_BC_BANCO is
      select w.data_length,
             a.bc_codigo, a.bc_nome_reduzido, a.bc_nome_extenso, a.bc_compensacao,
             b.sq_banco, b.nome, 
             (select count(*) from otca.bc_agencia t where t.banco = a.bc_nome_extenso) agencias
        from otca.bc_banco              a
             left join otca.co_banco b on (a.bc_codigo = b.codigo),
             all_tab_columns            w
      where w.owner       = 'OTCA'
        and w.table_name  = 'CO_BANCO'
        and w.column_name = 'NOME'
      order by a.bc_codigo;

  cursor c_BC_AGENCIA is
      select distinct w.data_length,
             s.bc_codigo, s.bc_nome_extenso banco,
             t.ag_codigo codigo, case t.ag_nome when t.banco then 'AG. '||t.ag_bairro else t.ag_nome end nome, 
             nvl(v.sq_banco,-1) sq_banco, v.ativo bc_ativo,
             u.sq_agencia, u.nome nm_agencia, u.ativo, t.ag_inicio
        from otca.bc_agencia            t
             inner join otca.bc_banco   s on (t.banco     = s.bc_nome_extenso)
             left  join otca.co_banco   v on (s.bc_codigo = v.codigo)
             left  join otca.co_agencia u on (v.sq_banco  = u.sq_banco and 
                                         t.ag_codigo = substr(u.codigo,1,4)
                                        ),
             all_tab_columns            w
      where w.owner       = 'OTCA'
        and w.table_name = 'CO_AGENCIA'
        and w.column_name = 'NOME'
        AND t.ag_inicio   = (select max(ag_inicio) from otca.bc_agencia where banco = t.banco and ag_codigo = t.ag_codigo)
      order by s.bc_codigo, t.ag_codigo;
   
  err_msg    otca.bc_banco.mensagem%type;
  nm_banco   otca.co_banco.nome%type;
  nm_agencia otca.co_agencia.nome%type;
begin
  -- Atualiza os bancos
  update otca.co_banco set nome = codigo||' '||substr(nome,1,27);
  
  update otca.bc_banco set mensagem = null;
  update otca.co_banco set ativo = 'N';
  
  for c in c_BC_BANCO loop
      begin
        nm_banco := substr(c.bc_nome_reduzido, 1, c.data_length);
        if c.sq_banco is null then
           -- Banco não existe na base
           If c.bc_compensacao = 'SIM' and length(c.bc_codigo) = 3 and c.agencias > 0 Then
              insert into otca.co_banco (sq_banco, codigo, nome, ativo, padrao)
              values (otca.sq_banco.nextval, c.bc_codigo, nm_banco, 'S', 'N');
           End If;
        else
           -- Banco existe, mas o nome difere do que consta no Banco Central
           update otca.co_banco 
              set ativo = case when c.bc_compensacao = 'SIM' and length(c.bc_codigo) = 3 and c.agencias > 0 then 'S' else 'N' end,
                  nome = nm_banco 
           where sq_banco = c.sq_banco;
        end if;
      exception
        when OTHERS then
          err_msg := substr(SQLERRM, 1, 4000);
          update otca.bc_banco set mensagem = err_msg where bc_codigo = c.bc_codigo;
      end;
  end loop;
  commit;

  -- Atualiza as agências
  update otca.bc_agencia set mensagem = null;
  update otca.co_agencia set ativo = 'N';
  
  for c in c_BC_AGENCIA loop
      begin
        nm_agencia := substr(c.nome, 1, c.data_length);
        if c.sq_agencia is null then
           -- Agência não existe na base
           insert into otca.co_agencia (sq_agencia, sq_banco, codigo, ativo, padrao, nome)
           values (otca.sq_agencia.nextval, c.sq_banco, c.codigo, 'S', 'N', nm_agencia);
        else
           -- Agência existe, mas o nome difere do que consta no Banco Central
           update otca.co_agencia set ativo = c.bc_ativo, nome = nm_agencia where sq_agencia = c.sq_agencia;
        end if;
      exception
        when OTHERS then
          err_msg := substr(SQLERRM, 1, 4000);
          update otca.bc_agencia set mensagem = err_msg where banco = c.banco and ag_codigo = c.codigo;
      end;
  end loop;
  commit;

end aux_atualiza_agencia;
/
