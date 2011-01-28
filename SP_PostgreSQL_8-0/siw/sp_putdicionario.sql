create or replace FUNCTION SP_PUTDICIONARIO
  (p_cliente numeric, 
   p_sistema varchar, 
   p_owner   varchar
  ) RETURNS VOID AS $$
DECLARE
  
   c_tabela CURSOR FOR
    select /*+ ordered */ f.sq_tabela, e.sq_tabela_tipo, c.sq_usuario, d.sq_sistema, a.table_name nome,
           case when f.sq_tabela is null 
                then case when b.comments is null
                          then 'A ser inserido' 
                          else b.comments
                     end
                else f.descricao
           end descricao
      from all_tables                       a
           inner      join all_tab_comments b on (a.owner          = b.owner and
                                                  a.table_name     = b.table_name
                                                 )
           inner      join dc_usuario       c on (a.owner          = c.nome)
           inner      join dc_sistema       d on (c.sq_sistema     = d.sq_sistema and
                                                  d.cliente        = p_cliente and
                                                  d.sigla          = upper(p_sistema)
                                                 )
           left outer join dc_tabela        f on (a.table_name     = f.nome and
                                                  f.sq_usuario     = c.sq_usuario and
                                                  f.sq_sistema     = d.sq_sistema
                                                 ),
           dc_tabela_tipo                   e
    where a.owner      = upper(p_owner)
      and e.nome       = 'Tabela'
   order by a.table_name;
BEGIN
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
  commit;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;