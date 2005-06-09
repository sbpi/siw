create or replace procedure SP_PUTDICIONARIO
  (p_cliente in number,
   p_sistema in varchar2,
   p_owner   in varchar2
  ) is

  cursor c_tabela is
    select /*+ ordered */ f.sq_tabela, e.sq_tabela_tipo, c.sq_usuario, d.sq_sistema, a.table_name nome,
           decode(f.sq_tabela,null,decode(b.comments,null,'A ser inserido',b.comments),f.descricao) descricao
      from all_tables       a,
           all_tab_comments b,
           dc_usuario       c,
           dc_sistema       d,
           dc_tabela        f,
           dc_tabela_tipo   e
    where (a.owner          = b.owner and
           a.table_name     = b.table_name
          )
      and (a.owner          = c.nome)
      and (c.sq_sistema     = d.sq_sistema and
           d.cliente        = p_cliente and
           d.sigla          = upper(p_sistema)
          )
      and (a.table_name     = f.nome (+) and
           f.sq_usuario (+) = c.sq_usuario and
           f.sq_sistema (+) = d.sq_sistema
          )
      and a.owner      = upper(p_owner)
      and e.nome       = 'Tabela'
   order by a.table_name;
begin
  -- Atualiza as tabelas do usuário e sistema informado
  for crec in c_tabela loop
     If crec.sq_tabela is null Then
        insert into dc_tabela (sq_tabela, sq_tabela_tipo, sq_usuario, sq_sistema, nome, descricao )
        values (sq_tabela.nextval,
                crec.sq_tabela_tipo,
                crec.sq_usuario,
                crec.sq_sistema,
                crec.nome,
                crec.descricao
               );
     Else
        update dc_tabela set descricao = crec.descricao where sq_tabela = crec.sq_tabela;
     End If;
  end loop;

  -- Efetiva as operações
  commit;
end SP_PUTDICIONARIO;
/

