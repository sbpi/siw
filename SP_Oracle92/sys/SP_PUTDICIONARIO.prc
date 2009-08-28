create or replace procedure SP_PUTDICIONARIO
  (p_cliente in number, 
   p_sistema in varchar2, 
   p_owner   in varchar2
  ) is
  
  cursor c_tabela is
    select f.sq_tabela chave, e.sq_tabela_tipo, c.sq_usuario, d.sq_sistema, a.table_name nome,
           case when b.comments is null
                then case when f.sq_tabela is null 
                          then 'A ser inserido' 
                          else f.descricao
                     end
                else b.comments
           end descricao
      from all_tables                       a
           inner      join all_tab_comments b on (a.owner          = b.owner and
                                                  a.table_name     = b.table_name
                                                 )
           inner      join siw.dc_usuario   c on (a.owner          = c.nome)
           inner      join siw.dc_sistema   d on (c.sq_sistema     = d.sq_sistema and
                                                  d.cliente        = p_cliente and
                                                  d.sigla          = upper(p_sistema)
                                                 )
           left outer join siw.dc_tabela    f on (a.table_name     = f.nome and
                                                  f.sq_usuario     = c.sq_usuario and
                                                  f.sq_sistema     = d.sq_sistema
                                                 ),
           siw.dc_tabela_tipo                e
     where a.owner                   = upper(p_owner)
       and (instr(a.table_name, '$') = 0 
       and upper(a.table_name)       not in ('PLAN_TABLE', 'SG_PESSOA_MENU_TMP', 'LIGORI', 'LIGNATE', 'LIGREC', 'SQLEXPERT_PLAN1'))
       and e.nome                    = 'Tabela'
    order by a.table_name;

  cursor c_coluna is
    select e.sq_coluna chave, a.sq_tabela, b.sq_dado_tipo, t2.column_name nome, t2.column_id ordem, 
           t2.data_length tamanho, t2.data_precision precisao, t2.data_scale escala, 
           case t2.nullable when 'Y' then 'N' else 'S' end obrigatorio,
           case when t3.comments is null
                then case when e.descricao is null 
                          then 'A ser inserido.' 
                          else e.descricao 
                          end
                else t3.comments
           end descricao
      from all_tables                         t1
           inner        join all_tab_columns  t2 on (t2.OWNER       = t1.owner and
                                                     t2.TABLE_NAME  = t1.table_name
                                                    )
             inner      join siw.dc_dado_tipo b  on (b.nome         = replace(replace(replace(replace(replace(replace(replace(replace(replace(t2.data_type,'VARCHAR2','VarChar'),'CHAR','Char'), 'NUMBER', 'Numeric'), 'DATE','Date'), 'BLOB','Blob'), 'BFILE', 'Blob'), 'RAW', 'Blob'), 'FLOAT', 'Numeric'), 'LONG', 'VarChar'))
           inner        join all_col_comments t3 on (t3.owner       = t1.owner and
                                                     t3.table_name  = t2.TABLE_NAME and
                                                     t3.column_name = t2.COLUMN_NAME
                                                    )
           inner        join siw.dc_tabela    a  on (t1.table_name  = a.nome)
               inner    join siw.dc_usuario   c  on (a.sq_usuario   = c.sq_usuario and
                                                     t1.owner       = c.nome and
                                                     c.nome         = upper(p_owner)
                                                    )
               inner    join siw.dc_sistema   d  on (c.sq_sistema   = d.sq_sistema and
                                                     d.sigla        = upper(p_sistema) and
                                                     d.cliente      = p_cliente
                                                    )
           left outer   join siw.dc_coluna    e  on (a.sq_tabela    = e.sq_tabela and
                                                     t2.column_name = e.nome
                                                    )
     where (instr(t1.table_name, '$') = 0 and upper(t1.table_name) not in ('PLAN_TABLE', 'SG_PESSOA_MENU_TMP', 'LIGORI', 'LIGNATE', 'LIGREC', 'SQLEXPERT_PLAN1'))
    order by a.sq_tabela, t2.COLUMN_NAME;

  cursor c_relacionamento is
    select f.sq_relacionamento chave, t1.constraint_name nome, 
           b.sq_tabela tabela_pai, a.sq_tabela tabela_filha, a.sq_sistema,
           case when f.descricao is null then 'A ser inserido' else f.descricao end descricao
      from all_constraints                        t1
           inner       join siw.dc_tabela         a  on (t1.table_name        = a.nome)
             inner     join siw.dc_usuario        c  on (a.sq_usuario         = c.sq_usuario and
                                                         t1.owner             = c.nome
                                                        )
               inner   join siw.dc_sistema        d  on (c.sq_sistema         = d.sq_sistema and
                                                         d.cliente            = p_cliente and
                                                         d.sigla              = upper(p_sistema)
                                                        )
           inner       join all_constraints       t3 on (t1.r_constraint_name = t3.constraint_name and
                                                         t1.r_owner           = t3.owner
                                                        )
             inner     join siw.dc_tabela         b  on (t3.table_name        = b.nome)
               inner   join siw.dc_usuario        g  on (b.sq_usuario         = g.sq_usuario and
                                                         t3.owner             = g.nome
                                                        )
                 inner join siw.dc_sistema        h  on (g.sq_sistema         = h.sq_sistema and
                                                         d.sq_sistema         = h.sq_sistema and
                                                         h.cliente            = p_cliente and
                                                         h.sigla              = upper(p_sistema)
                                                        )
           left outer  join siw.dc_relacionamento f  on (t1.constraint_name   = f.nome and
                                                         (f.sq_sistema        = d.sq_sistema or
                                                          f.sq_sistema        = h.sq_sistema
                                                         )
                                                        )
     where t1.owner                   = upper(p_owner)
       and t1.constraint_type         ='R'
       and (instr(t1.table_name, '$') = 0 
       and upper(t1.table_name)       not in ('PLAN_TABLE', 'SG_PESSOA_MENU_TMP', 'LIGORI', 'LIGNATE', 'LIGREC', 'SQLEXPERT_PLAN1'))
    order by t1.constraint_name;

  cursor c_trigger is
    select d.sq_trigger chave, a.sq_tabela, b.sq_usuario, c.sq_sistema, t.trigger_name nome, 
           case when d.sq_trigger is null then 'A ser inserido' else d.descricao end descricao
      from dba_triggers                     t
           inner        join siw.dc_tabela  a on (t.table_name   = a.nome)
             inner      join siw.dc_usuario b on (a.sq_usuario   = b.sq_usuario and
                                                   b.nome        = upper(p_owner)
                                                  )
               inner    join siw.dc_sistema c on (b.sq_sistema   = c.sq_sistema and
                                                  c.cliente      = p_cliente and
                                                  c.sigla        = upper(p_sistema)
                                                 )
             left outer join siw.dc_trigger d on (t.trigger_name = d.nome and
                                                  d.sq_tabela    = a.sq_tabela
                                                 )
     where t.owner = upper(p_owner)
    order by t.trigger_name;

  cursor c_indice is
    select e.sq_indice chave, b.sq_indice_tipo, c.sq_usuario, c.sq_sistema, t1.index_name nome, 
           case when e.descricao is null then 'A ser inserido' else e.descricao end descricao
      from all_indexes                        t1
           inner      join siw.DC_TABELA      a  on (t1.table_name = a.nome)
             inner    join siw.dc_usuario     c  on (a.sq_usuario  = c.sq_usuario and
                                                     t1.owner      = c.nome and
                                                     c.nome        = upper(p_owner)
                                                    )
               inner  join siw.dc_sistema     d  on (c.sq_sistema  = d.sq_sistema and
                                                     d.cliente     = p_cliente and
                                                     d.sigla       = upper(p_sistema)
                                                    )
           inner      join siw.DC_INDICE_TIPO b  on (b.nome        = case t1.uniqueness when 'NONUNIQUE' then 'Normal' else 'Único' end)
           left outer join siw.dc_indice      e  on (t1.index_name = e.nome and
                                                     e.sq_usuario  = c.sq_usuario and
                                                     e.sq_sistema  = d.sq_sistema
                                                    )
     where t1.owner                   = upper(p_owner)
       and (instr(t1.table_name, '$') = 0 
       and upper(t1.table_name)       not in ('PLAN_TABLE', 'SG_PESSOA_MENU_TMP', 'LIGORI', 'LIGNATE', 'LIGREC', 'SQLEXPERT_PLAN1'))
    order by t1.table_name, t1.index_name;

  cursor c_procedure is
    select d.sq_stored_proc chave, a.sq_sp_tipo, b.sq_usuario, c.sq_sistema, object_name nome, 
           case when d.descricao is null then 'A ser inserido' else d.descricao end descricao
      from all_objects                        t
           inner      join siw.dc_sp_tipo     a on (t.object_type = a.nome)
           inner      join siw.dc_sistema     c on (c.cliente      = p_cliente and
                                                    c.sigla        = upper(p_sistema)
                                                   )
             inner    join siw.dc_usuario     b on (b.sq_sistema   = c.sq_sistema and
                                                    b.nome         = t.owner
                                                   )
           left outer join siw.dc_stored_proc d on (t.object_name  = d.nome and
                                                    b.sq_usuario   = d.sq_usuario
                                                   )
     where t.owner = upper(p_owner)
       and t.object_type in ('FUNCTION', 'PROCEDURE')
       and t.object_name <> 'HASH_MD5'
    order by t.object_type, t.object_name;

  cursor c_procedure_param is
    select f.sq_sp_param chave, a.sq_stored_proc, b.sq_dado_tipo, t2.position ordem, 
           case when t2.argument_name is null then 'RESULT' else t2.argument_name end nome, 
           case when f.sq_sp_param is null then 'A ser inserido' else f.descricao end descricao, 
           case t2.in_out when 'IN' then 'E' when 'OUT' then 'S' else 'A' end tipo,
           Nvl(t2.data_length,0) tamanho, t2.data_precision precisao, t2.data_scale escala, 
           'N' obrigatorio,
           t2.default_value padrao
      from sys.all_objects                    t1
           inner      join sys.all_arguments  t2 on (t1.object_id     = t2.object_id)
           inner      join siw.dc_stored_proc a  on (t1.object_name   = a.nome)
             inner    join siw.dc_usuario     d  on (a.sq_usuario     = d.sq_usuario and
                                                     t1.owner         = d.nome
                                                    )
               inner  join siw.dc_sistema     e  on (d.sq_sistema     = e.sq_sistema and
                                                     e.cliente        = p_cliente and
                                                     e.sigla          = upper(p_sistema)
                                                    )
           left outer join siw.dc_sp_param    f  on (a.sq_stored_proc = f.sq_stored_proc and
                                                     f.nome           = case when t2.argument_name is null then 'RESULT' else t2.argument_name end
                                                    ),
           siw.dc_dado_tipo                   b
     where t1.owner       = upper(p_owner)
       and t1.object_type in ('FUNCTION', 'PROCEDURE')
       and b.nome         = replace(replace(replace(replace(replace(t2.data_type,'VARCHAR2','VarChar'),'CHAR','Char'),'NUMBER','Numeric'), 'DATE', 'Date'),'REF CURSOR', 'Cursor')
    order by t1.object_name, t2.position;

begin
  -- Atualiza as tabelas do usuário e sistema informado
  for crec in c_tabela loop
     If crec.chave is null Then
        insert into siw.dc_tabela 
               (sq_tabela, sq_tabela_tipo, sq_usuario, sq_sistema, nome, descricao)
        values (siw.sq_tabela.nextval, 
                crec.sq_tabela_tipo, 
                crec.sq_usuario, 
                crec.sq_sistema, 
                crec.nome, 
                crec.descricao
               );
     Else
        update siw.dc_tabela set descricao = crec.descricao where sq_tabela = crec.chave;
     End If;
  end loop;
  
  -- Atualiza as colunas de tabelas
  for crec in c_coluna loop
     If crec.chave is null Then
        insert into siw.dc_coluna 
               (sq_coluna, sq_tabela, sq_dado_tipo, nome,   descricao, 
                ordem,     tamanho,   precisao,     escala, obrigatorio
               )
        values (siw.sq_coluna.nextval,
                crec.sq_tabela,
                crec.sq_dado_tipo, 
                crec.nome, 
                crec.descricao, 
                crec.ordem, 
                crec.tamanho, 
                crec.precisao,
                crec.escala,
                crec.obrigatorio
               );
     Else
        update siw.dc_coluna set 
           sq_dado_tipo = crec.sq_dado_tipo,
           descricao    = crec.descricao,
           ordem        = crec.ordem,
           tamanho      = crec.tamanho,
           precisao     = crec.precisao,
           escala       = crec.escala,
           obrigatorio  = crec.obrigatorio
        where sq_coluna = crec.chave;
     End If;
  end loop;
  
  -- Atualiza os relacionamentos entre as tabelas
  for crec in c_relacionamento loop
     If crec.chave is null Then
        insert into siw.dc_relacionamento 
               (sq_relacionamento, nome, descricao, tabela_pai, tabela_filha, sq_sistema)
        values (siw.sq_relacionamento.nextval, 
                crec.nome, 
                crec.descricao, 
                crec.tabela_pai, 
                crec.tabela_filha, 
                crec.sq_sistema
               );
     Else
        update siw.dc_relacionamento set
           tabela_pai   = crec.tabela_pai,
           tabela_filha = crec.tabela_filha, 
           descricao    = crec.descricao 
        where sq_relacionamento = crec.chave;
     End If;
  end loop;

  -- Recria as colunas de relacionamentos não localizados no dicionário,
  -- ligadas a tabelas do cliente, sistema e usuário informado
  
  -- Remove colunas de relacionamento onde a tabela filha seja do sistema e usuário indicado
  delete from 
  (select a.*
     from siw.dc_relac_cols                      a
          inner       join siw.dc_relacionamento b on (a.sq_relacionamento = b.sq_relacionamento)
            inner     join siw.dc_tabela         c on (b.tabela_filha      = c.sq_tabela)
              inner   join siw.dc_usuario        d on (c.sq_usuario        = d.sq_usuario and
                                                       d.nome              = upper(p_owner)
                                                      )
                inner join siw.dc_sistema        e on (d.sq_sistema        = e.sq_sistema and
                                                       e.cliente           = p_cliente and
                                                       e.sigla             = upper(p_sistema)
                                                      )
  );
  -- Remove colunas de relacionamento onde a tabela pai seja do sistema e usuário indicado
  delete from 
  (select a.*
     from siw.dc_relac_cols                      a
          inner       join siw.dc_relacionamento b on (a.sq_relacionamento = b.sq_relacionamento)
            inner     join siw.dc_tabela         c on (b.tabela_pai        = c.sq_tabela)
              inner   join siw.dc_usuario        d on (c.sq_usuario        = d.sq_usuario and
                                                       d.nome              = upper(p_owner)
                                                      )
                inner join siw.dc_sistema        e on (d.sq_sistema        = e.sq_sistema and
                                                       e.cliente           = p_cliente and
                                                       e.sigla             = upper(p_sistema)
                                                      )
  );
  -- Cria as colunas de relacionamento
  insert into siw.dc_relac_cols (sq_relacionamento, coluna_pai, coluna_filha)
  (select e.sq_relacionamento, d.sq_coluna coluna_pai, b.sq_coluna coluna_filha
     from all_constraints                          t1
          inner         join all_cons_columns      t2 on (t2.owner             = t1.owner and 
                                                          t2.constraint_name   = t1.constraint_name
                                                         )
            inner       join siw.dc_tabela         a  on (t2.table_name        = a.nome)
              inner     join siw.dc_coluna         b  on (a.sq_tabela          = b.sq_tabela and
                                                          t2.column_name       = b.nome
                                                         )
              inner     join siw.dc_usuario        f  on (a.sq_usuario         = f.sq_usuario)
                inner   join siw.dc_sistema        g  on (f.sq_sistema         = g.sq_sistema and
                                                          g.cliente            = p_cliente
                                                         )
          inner         join all_constraints       t3 on (t1.r_owner           = t3.owner and
                                                          t1.r_constraint_name = t3.constraint_name
                                                         )
            inner       join all_cons_columns      t4 on (t3.owner             = t4.owner and
                                                          t3.constraint_name   = t4.constraint_name and
                                                          t2.position          = t4.position
                                                         )
              inner     join siw.dc_tabela         c on (t4.table_name         = c.nome)
                inner   join siw.dc_coluna         d on (c.sq_tabela           = d.sq_tabela and
                                                         t4.column_name        = d.nome
                                                        )
                inner   join siw.dc_usuario        h  on (c.sq_usuario         = h.sq_usuario)
                  inner join siw.dc_sistema        i  on (h.sq_sistema         = i.sq_sistema and
                                                          g.sq_sistema         = i.sq_sistema and
                                                          i.cliente            = p_cliente
                                                         )
          inner         join siw.dc_relacionamento e on (t1.constraint_name    = e.nome)
            inner       join siw.dc_sistema        j on (e.sq_sistema          = j.sq_sistema and
                                                         j.cliente             = p_cliente
                                                        )
    where t1.owner                   = upper(p_owner)
      and t1.constraint_type         = 'R'
      and (instr(t1.table_name, '$') = 0 
      and upper(t1.table_name)       not in ('PLAN_TABLE', 'SG_PESSOA_MENU_TMP', 'LIGORI', 'LIGNATE', 'LIGREC', 'SQLEXPERT_PLAN1')) 
      and ((f.nome = upper(p_owner) and g.sigla = upper(p_sistema)) or
           (h.nome = upper(p_owner) and i.sigla = upper(p_sistema))
          )
      and 0 = (select count(*) 
                 from siw.dc_relac_cols 
                where sq_relacionamento = e.sq_relacionamento
                  and coluna_pai        = d.sq_coluna
                  and coluna_filha      = b.sq_coluna
              )
  );

  -- Atualiza as triggers
  for crec in c_trigger loop
     If crec.chave is null Then
        insert into siw.dc_trigger 
               (sq_trigger, sq_tabela, sq_usuario, sq_sistema, nome, descricao)
        values (siw.sq_trigger.nextval, 
                crec.sq_tabela,
                crec.sq_usuario,
                crec.sq_sistema,
                crec.nome, 
                crec.descricao
               );
     Else
        update siw.dc_trigger set descricao = crec.descricao where sq_trigger = crec.chave;
     End If;
  end loop;

  -- Recria os eventos de trigger, eliminando os registros existentes
  -- ligados a tabelas do cliente, sistema e usuário informado
  delete siw.dc_trigger_evento a 
   where a.sq_trigger in 
         (select sq_trigger
            from siw.dc_trigger
           where sq_usuario = (select sq_usuario 
                                 from siw.dc_usuario 
                                where nome       = upper(p_owner)
                                  and sq_sistema = (select sq_sistema 
                                                      from siw.dc_sistema 
                                                     where cliente = p_cliente
                                                       and sigla   = upper(p_sistema)
                                                   )
                              )
         );
  -- Cria os eventos de trigger
  insert into siw.dc_trigger_evento (sq_trigger, sq_evento)
  (select a.sq_trigger, b.sq_evento
     from dba_triggers                    t 
          inner      join siw.dc_trigger  a on (t.trigger_name     = a.nome)
            inner    join siw.dc_usuario  c on (a.sq_usuario       = c.sq_usuario and
                                                t.owner            = c.nome and
                                                c.nome             = upper(p_owner)
                                               )
              inner  join siw.dc_sistema  d on (c.sq_sistema       = d.sq_sistema and
                                                d.cliente          = p_cliente and
                                                d.sigla            = upper(p_sistema)
                                                )
          inner      join siw.dc_evento   b on (t.triggering_event = b.nome)
   where t.owner = upper(p_owner)
  );

  -- Atualiza os índices
  for crec in c_indice loop
     If crec.chave is null Then
        insert into siw.dc_indice 
               (sq_indice, sq_indice_tipo, sq_usuario, sq_sistema, nome, descricao)
        values (siw.sq_indice.nextval, 
                crec.sq_indice_tipo,
                crec.sq_usuario,
                crec.sq_sistema,
                crec.nome, 
                crec.descricao
               );
     Else
        update siw.dc_indice set descricao = crec.descricao where sq_indice = crec.chave;
     End If;
  end loop;

  -- Recria as colunas de índices, eliminando os registros que tiverem colunas pais
  -- ou filhas ligadas a tabelas do cliente, sistema e usuário informado
  delete siw.dc_indice_cols a 
   where a.sq_coluna in 
         (select sq_coluna 
            from siw.dc_coluna 
           where sq_tabela in (select sq_tabela 
                                 from siw.dc_tabela 
                                where sq_usuario = (select sq_usuario 
                                                      from siw.dc_usuario 
                                                     where nome       = upper(p_owner)
                                                       and sq_sistema = (select sq_sistema 
                                                                           from siw.dc_sistema 
                                                                          where cliente = p_cliente
                                                                            and sigla   = upper(p_sistema)
                                                                        )
                                                   )
                              )
         );
  -- Cria as colunas de índices
  insert into siw.dc_indice_cols (sq_indice, sq_coluna, ordem, ordenacao)
  (select b.sq_indice, c.sq_coluna, T2.COLUMN_POSITION, case T2.DESCEND when 'ASC' then 'A' else 'D' end ordenacao
     from all_indexes                    t1
          inner     join siw.dc_tabela   a  on (t1.table_name  = a.nome)
            inner   join siw.dc_usuario  d  on (a.sq_usuario   = d.sq_usuario and
                                                t1.owner       = d.nome and
                                                d.nome         = upper(p_owner)
                                                )
              inner join siw.dc_sistema  e  on (d.sq_sistema   = e.sq_sistema and
                                                e.cliente      = p_cliente and
                                                e.sigla        = upper(p_sistema)
                                               )
          inner     join siw.dc_indice   b  on (t1.index_name  = b.nome and
                                                d.sq_usuario   = b.sq_usuario
                                               )
          inner     join all_ind_columns t2 on (t1.owner       = t2.index_owner and
                                                t2.index_name  = t1.index_name
                                               )
            inner   join siw.dc_coluna   c  on (t2.column_name = c.nome and
                                                a.sq_tabela    = c.sq_tabela
                                               )
    where t1.owner                  = upper(p_owner)
     and (instr(t1.table_name, '$') = 0 
     and upper(t1.table_name)       not in ('PLAN_TABLE', 'SG_PESSOA_MENU_TMP', 'LIGORI', 'LIGNATE', 'LIGREC', 'SQLEXPERT_PLAN1'))
  );
          
  -- Atualiza as procedures
  for crec in c_procedure loop
     If crec.chave is null Then
        insert into siw.dc_stored_proc 
               (sq_stored_proc, sq_sp_tipo, sq_usuario, sq_sistema, nome, descricao)
        values (siw.sq_stored_proc.nextval, 
                crec.sq_sp_tipo,
                crec.sq_usuario,
                crec.sq_sistema,
                crec.nome, 
                crec.descricao
               );
     Else
        update siw.dc_stored_proc set descricao = crec.descricao where sq_stored_proc = crec.chave;
     End If;
  end loop;

  -- Atualiza os parâmetros das procedures
  for crec in c_procedure_param loop
     If crec.chave is null Then
        insert into siw.dc_sp_param 
               (sq_sp_param, sq_stored_proc, sq_dado_tipo, nome,   descricao,   tipo, 
                ordem,       tamanho,        precisao,     escala, obrigatorio, valor_padrao
               )
        values (siw.Sq_Sp_Param.nextval, 
                crec.sq_stored_proc,
                crec.sq_dado_tipo,
                crec.nome, 
                crec.descricao,
                crec.tipo,
                crec.ordem,
                crec.tamanho,
                crec.precisao,
                crec.escala,
                crec.obrigatorio,
                crec.padrao
               );
     Else
        update siw.dc_sp_param set
           sq_dado_tipo = crec.sq_dado_tipo,
           descricao    = crec.descricao,
           tipo         = crec.tipo,
           ordem        = crec.ordem
        where sq_sp_param = crec.chave;
     End If;
  end loop;

  -- Recria os vínculos entre stored procedures e tabelas, eliminando os registros que tiverem
  -- tabelas do cliente, sistema e usuário informado
  delete siw.dc_sp_tabs a 
   where a.sq_tabela in 
         (select sq_tabela
            from siw.dc_tabela
           where sq_usuario = (select sq_usuario 
                                 from siw.dc_usuario 
                                where nome       = upper(p_owner)
                                  and sq_sistema = (select sq_sistema 
                                                      from siw.dc_sistema 
                                                     where cliente = p_cliente
                                                       and sigla   = upper(p_sistema)
                                                   )
                              )
         );
  -- Cria os vínculos entre stored procedures e tabelas
  insert into siw.dc_sp_tabs (sq_stored_proc, sq_tabela)
  (select a.sq_stored_proc, b.sq_tabela
     from sys.all_dependencies           t
          inner join siw.dc_stored_proc  a on (t.name             = a.nome)
            inner   join siw.dc_usuario  d on (a.sq_usuario       = d.sq_usuario and
                                               t.owner            = d.nome
                                               )
              inner join siw.dc_sistema  e on (d.sq_sistema       = e.sq_sistema and
                                               e.cliente          = p_cliente and
                                               e.sigla            = upper(p_sistema)
                                              )
          inner join siw.dc_tabela       b on (t.referenced_name  = b.nome)
            inner   join siw.dc_usuario  f on (b.sq_usuario       = f.sq_usuario and
                                               t.referenced_owner = f.nome
                                               )
              inner join siw.dc_sistema  g on (f.sq_sistema       = g.sq_sistema and
                                               g.cliente          = p_cliente and
                                               g.sigla            = upper(p_sistema)
                                              )
    where t.referenced_owner = upper(p_owner)
      and t.referenced_type = 'TABLE'
  );

  -- Recria os vínculos entre stored procedures, eliminando os registros que tiverem
  -- procedures pais ou filhas ligadas ao cliente, sistema e usuário informado
  delete siw.dc_sp_sp a 
   where a.sp_pai in 
         (select sq_stored_proc 
            from siw.dc_stored_proc
           where sq_usuario = (select sq_usuario 
                                 from siw.dc_usuario 
                                where nome       = upper(p_owner)
                                  and sq_sistema = (select sq_sistema 
                                                      from siw.dc_sistema 
                                                     where cliente = p_cliente
                                                       and sigla   = upper(p_sistema)
                                                   )
                              )
         );

  delete siw.dc_sp_sp a 
   where a.sp_filha in 
         (select sq_stored_proc 
            from siw.dc_stored_proc
           where sq_usuario = (select sq_usuario 
                                 from siw.dc_usuario 
                                where nome       = upper(p_owner)
                                  and sq_sistema = (select sq_sistema 
                                                      from siw.dc_sistema 
                                                     where cliente = p_cliente
                                                       and sigla   = upper(p_sistema)
                                                   )
                              )
         );
  -- Cria os vínculos entre stored procedures
  insert into siw.dc_sp_sp (sp_pai, sp_filha)
  (select b.sq_stored_proc, a.sq_stored_proc
     from sys.all_dependencies t
          inner join siw.dc_stored_proc a on (t.name             = a.nome)
            inner   join siw.dc_usuario d on (a.sq_usuario       = d.sq_usuario and
                                              t.owner            = d.nome
                                              )
              inner join siw.dc_sistema e on (d.sq_sistema       = e.sq_sistema and
                                              e.cliente          = p_cliente and
                                              e.sigla            = upper(p_sistema)
                                             )
          inner join siw.dc_stored_proc b on (t.referenced_name  = b.nome)
            inner   join siw.dc_usuario f on (b.sq_usuario       = f.sq_usuario and
                                              t.referenced_owner = f.nome
                                              )
              inner join siw.dc_sistema g on (f.sq_sistema       = g.sq_sistema and
                                              g.cliente          = p_cliente and
                                              g.sigla            = upper(p_sistema)
                                             )
    where t.owner            = upper(p_owner)
       or t.referenced_owner = upper(p_owner)
  );

  -- Remove colunas de relacionamentos não localizados no dicionário de dados
  delete siw.dc_relac_cols
   where sq_relacionamento in 
        (select x.sq_relacionamento
          from siw.dc_relacionamento          x
               inner      join siw.dc_tabela  m on (x.tabela_filha = m.sq_tabela)
               inner      join siw.dc_usuario n on (m.sq_usuario   = n.sq_usuario and
                                                    n.nome         = upper(p_owner)
                                                   )
               inner      join siw.dc_sistema o on (n.sq_sistema   = o.sq_sistema and
                                                    o.sigla        = upper(p_sistema) and
                                                    o.cliente      = p_cliente
                                                   )
               left outer join (select f.sq_relacionamento
                                  from all_constraints                        t1
                                       inner       join siw.dc_tabela         a  on (t1.table_name        = a.nome)
                                         inner     join siw.dc_usuario        c  on (a.sq_usuario         = c.sq_usuario and
                                                                                     t1.owner             = c.nome
                                                                                    )
                                           inner   join siw.dc_sistema        d  on (c.sq_sistema         = d.sq_sistema and
                                                                                     d.cliente            = p_cliente and
                                                                                     d.sigla              = upper(p_sistema)
                                                                                    )
                                       inner       join all_constraints       t3 on (t1.r_constraint_name = t3.constraint_name and
                                                                                     t1.r_owner           = t3.owner
                                                                                    )
                                         inner     join siw.dc_tabela         b  on (t3.table_name        = b.nome)
                                           inner   join siw.dc_usuario        g  on (b.sq_usuario         = g.sq_usuario and
                                                                                     t3.owner             = g.nome
                                                                                    )
                                             inner join siw.dc_sistema        h  on (g.sq_sistema         = h.sq_sistema and
                                                                                     h.cliente            = p_cliente and
                                                                                     h.sigla              = upper(p_sistema)
                                                                                    )
                                       inner       join siw.dc_relacionamento f  on (t1.constraint_name   = f.nome)
                                 where t1.owner                   = upper(p_owner)
                                   and t1.constraint_type         ='R'
                                   and (instr(t1.table_name, '$') = 0 
                                   and upper(t1.table_name)       not in ('PLAN_TABLE', 'SG_PESSOA_MENU_TMP', 'LIGORI', 'LIGNATE', 'LIGREC', 'SQLEXPERT_PLAN1'))
                               ) y on (x.sq_relacionamento = y.sq_relacionamento)
         where y.sq_relacionamento is null
       );

  -- Remove colunas não localizadas no dicionário de dados
  delete siw.dc_coluna 
  where sq_coluna in (select x.sq_coluna
                        from siw.dc_coluna x
                             inner      join siw.dc_tabela  z on (x.sq_tabela = z.sq_tabela)
                             inner      join siw.dc_usuario w on (z.sq_usuario   = w.sq_usuario and
                                                                  w.nome         = upper(p_owner)
                                                                 )
                             inner      join siw.dc_sistema v on (w.sq_sistema   = v.sq_sistema and
                                                                  v.sigla        = upper(p_sistema) and
                                                                  v.cliente      = p_cliente
                                                                 )
                             left outer join (select a.sq_coluna, e.owner, e.TABLE_NAME, e.COLUMN_NAME
                                                from siw.dc_coluna                   a
                                                     inner   join siw.dc_tabela   b  on (a.sq_tabela    = b.sq_tabela)
                                                       inner join siw.dc_usuario  c  on (b.sq_usuario   = c.sq_usuario and
                                                                                         c.nome         = upper(p_owner)
                                                                                        )
                                                       inner join siw.dc_sistema  d  on (c.sq_sistema   = d.sq_sistema and
                                                                                         d.sigla        = upper(p_sistema) and
                                                                                         d.cliente      = p_cliente
                                                                                        )
                                                     inner   join all_tab_columns e on (e.owner        = c.nome and
                                                                                        e.table_name   = b.nome and
                                                                                        e.column_name  = a.nome
                                                                                       )
                                             ) y on (x.sq_coluna = y.sq_coluna)
                       where y.sq_coluna is null
                     );

  -- Remove relacionamentos não localizados no dicionário de dados
  delete siw.dc_relacionamento 
   where sq_relacionamento in 
        (select x.sq_relacionamento
          from siw.dc_relacionamento          x
               inner      join siw.dc_tabela  m on (x.tabela_filha = m.sq_tabela)
               inner      join siw.dc_usuario n on (m.sq_usuario   = n.sq_usuario and
                                                    n.nome         = upper(p_owner)
                                                   )
               inner      join siw.dc_sistema o on (n.sq_sistema   = o.sq_sistema and
                                                    o.sigla        = upper(p_sistema) and
                                                    o.cliente      = p_cliente
                                                   )
               left outer join (select f.sq_relacionamento
                                  from all_constraints                        t1
                                       inner       join siw.dc_tabela         a  on (t1.table_name        = a.nome)
                                         inner     join siw.dc_usuario        c  on (a.sq_usuario         = c.sq_usuario and
                                                                                     t1.owner             = c.nome
                                                                                    )
                                           inner   join siw.dc_sistema        d  on (c.sq_sistema         = d.sq_sistema and
                                                                                     d.cliente            = p_cliente and
                                                                                     d.sigla              = upper(p_sistema)
                                                                                    )
                                       inner       join all_constraints       t3 on (t1.r_constraint_name = t3.constraint_name and
                                                                                     t1.r_owner           = t3.owner
                                                                                    )
                                         inner     join siw.dc_tabela         b  on (t3.table_name        = b.nome)
                                           inner   join siw.dc_usuario        g  on (b.sq_usuario         = g.sq_usuario and
                                                                                     t3.owner             = g.nome
                                                                                    )
                                             inner join siw.dc_sistema        h  on (g.sq_sistema         = h.sq_sistema and
                                                                                     h.cliente            = p_cliente and
                                                                                     h.sigla              = upper(p_sistema)
                                                                                    )
                                       inner       join siw.dc_relacionamento f  on (t1.constraint_name   = f.nome)
                                 where t1.owner                   = upper(p_owner)
                                   and t1.constraint_type         ='R'
                                   and (instr(t1.table_name, '$') = 0 
                                   and upper(t1.table_name)       not in ('PLAN_TABLE', 'SG_PESSOA_MENU_TMP', 'LIGORI', 'LIGNATE', 'LIGREC', 'SQLEXPERT_PLAN1'))
                               ) y on (x.sq_relacionamento = y.sq_relacionamento)
         where y.sq_relacionamento is null
       );

  -- Remove triggers não localizados no dicionário de dados
  delete siw.dc_trigger 
   where sq_trigger in 
        (select x.sq_trigger
          from siw.dc_trigger x
               inner      join siw.dc_tabela  z on (x.sq_tabela  = z.sq_tabela)
               inner      join siw.dc_usuario w on (z.sq_usuario = w.sq_usuario and
                                                    w.nome       = upper(p_owner)
                                                   )
               inner      join siw.dc_sistema v on (w.sq_sistema = v.sq_sistema and
                                                    v.sigla      = upper(p_sistema) and
                                                    v.cliente    = p_cliente
                                                   )
               left outer join (select d.sq_trigger
                                  from dba_triggers                  t
                                       inner     join siw.dc_tabela  a on (t.table_name   = a.nome)
                                         inner   join siw.dc_usuario b on (a.sq_usuario   = b.sq_usuario and
                                                                           b.nome         = upper(p_owner)
                                                                          )
                                           inner join siw.dc_sistema c on (b.sq_sistema   = c.sq_sistema and
                                                                           c.cliente      = p_cliente and
                                                                           c.sigla        = upper(p_sistema)
                                                                          )
                                         inner   join siw.dc_trigger d on (t.trigger_name = d.nome and
                                                                           d.sq_tabela    = a.sq_tabela
                                                                          )
                                 where t.owner = upper(p_owner)
                               ) y on (x.sq_trigger = y.sq_trigger)
         where y.sq_trigger is null
       );

  -- Remove índices não localizados no dicionário de dados
  delete siw.dc_indice
   where sq_indice in 
        (select x.sq_indice
          from siw.dc_indice x
               inner      join siw.dc_usuario w on (x.sq_usuario = w.sq_usuario and
                                                    w.nome       = upper(p_owner)
                                                   )
               inner      join siw.dc_sistema v on (x.sq_sistema = v.sq_sistema and
                                                    v.sigla      = upper(p_sistema) and
                                                    v.cliente    = p_cliente
                                                   )
               left outer join (select e.sq_indice
                                  from all_indexes                       t1
                                       inner     join siw.dc_tabela      a  on (t1.table_name = a.nome)
                                         inner   join siw.dc_usuario     c  on (a.sq_usuario  = c.sq_usuario and
                                                                                t1.owner      = c.nome and
                                                                                c.nome        = upper(p_owner)
                                                                               )
                                           inner join siw.dc_sistema     d  on (c.sq_sistema  = d.sq_sistema and
                                                                                d.cliente     = p_cliente and
                                                                                d.sigla       = upper(p_sistema)
                                                                               )
                                       inner     join siw.dc_indice      e  on (t1.index_name = e.nome and
                                                                                e.sq_usuario  = c.sq_usuario and
                                                                                e.sq_sistema  = d.sq_sistema
                                                                               )
                                 where t1.owner                   = upper(p_owner)
                                   and (instr(t1.table_name, '$') = 0 
                                   and upper(t1.table_name)       not in ('PLAN_TABLE', 'SG_PESSOA_MENU_TMP', 'LIGORI', 'LIGNATE', 'LIGREC', 'SQLEXPERT_PLAN1'))
                               ) y on (x.sq_indice = y.sq_indice)
         where y.sq_indice is null
       );

  -- Remove parâmetros não localizados nas procedures do dicionário de dados
  delete siw.dc_sp_param
   where sq_sp_param in 
      (select x.sq_sp_param
        from siw.dc_sp_param x
             inner      join siw.dc_stored_proc u on (x.sq_stored_proc = u.sq_stored_proc)
             inner      join siw.dc_usuario     w on (u.sq_usuario = w.sq_usuario and
                                                      w.nome       = upper(p_owner)
                                                     )
             inner      join siw.dc_sistema     v on (w.sq_sistema = v.sq_sistema and
                                                      v.sigla      = upper(p_sistema) and
                                                      v.cliente    = p_cliente
                                                     )
             left outer join (select f.sq_sp_param
                                from sys.all_objects                   t1
                                     inner     join sys.all_arguments  t2 on (t1.owner         = t2.owner and
                                                                              t1.object_name   = t2.object_name
                                                                             )
                                     inner     join siw.dc_stored_proc a  on (t1.object_name   = a.nome)
                                       inner   join siw.dc_usuario     d  on (a.sq_usuario     = d.sq_usuario and
                                                                              t1.owner         = d.nome
                                                                             )
                                         inner join siw.dc_sistema     e  on (d.sq_sistema     = e.sq_sistema and
                                                                              e.cliente        = p_cliente and
                                                                              e.sigla          = upper(p_sistema)
                                                                             )
                                     inner     join siw.dc_sp_param    f  on (a.sq_stored_proc = f.sq_stored_proc and
                                                                              f.nome           = case when t2.argument_name is null then 'RESULT' else t2.argument_name end
                                                                             )
                               where t1.owner       = upper(p_owner)
                                 and t1.object_type in ('FUNCTION', 'PROCEDURE')
                             ) y on (x.sq_sp_param = y.sq_sp_param)
       where y.sq_sp_param is null
     );

  -- Remove parâmetros de procedures não localizadas no dicionário de dados
  delete siw.dc_sp_param
   where sq_stored_proc in 
        (select x.sq_stored_proc
          from siw.dc_stored_proc             x
               inner      join siw.dc_usuario w on (x.sq_usuario = w.sq_usuario and
                                                    w.nome       = upper(p_owner)
                                                   )
               inner      join siw.dc_sistema v on (w.sq_sistema = v.sq_sistema and
                                                    v.sigla      = upper(p_sistema) and
                                                    v.cliente    = p_cliente
                                                   )
               left outer join (select d.sq_stored_proc
                                  from all_objects                     t
                                       inner   join siw.dc_sp_tipo     a on (t.object_type = a.nome)
                                       inner   join siw.dc_sistema     c on (c.cliente      = p_cliente and
                                                                             c.sigla        = upper(p_sistema)
                                                                            )
                                         inner join siw.dc_usuario     b on (b.sq_sistema   = c.sq_sistema and
                                                                             b.nome         = t.owner
                                                                            )
                                       inner   join siw.dc_stored_proc d on (t.object_name  = d.nome and
                                                                             b.sq_usuario   = d.sq_usuario
                                                                            )
                                 where t.owner = upper(p_owner)
                                   and t.object_type in ('FUNCTION', 'PROCEDURE')
                                   and t.object_name <> 'HASH_MD5'
                               ) y on (x.sq_stored_proc = y.sq_stored_proc)
         where y.sq_stored_proc is null
       );

  -- Remove vínculo de tabelas ligadas a procedures não localizadas no dicionário de dados
  delete siw.dc_sp_tabs
   where sq_stored_proc in 
        (select x.sq_stored_proc
          from siw.dc_stored_proc             x
               inner      join siw.dc_usuario w on (x.sq_usuario = w.sq_usuario and
                                                    w.nome       = upper(p_owner)
                                                   )
               inner      join siw.dc_sistema v on (w.sq_sistema = v.sq_sistema and
                                                    v.sigla      = upper(p_sistema) and
                                                    v.cliente    = p_cliente
                                                   )
               left outer join (select d.sq_stored_proc
                                  from all_objects                     t
                                       inner   join siw.dc_sp_tipo     a on (t.object_type = a.nome)
                                       inner   join siw.dc_sistema     c on (c.cliente      = p_cliente and
                                                                             c.sigla        = upper(p_sistema)
                                                                            )
                                         inner join siw.dc_usuario     b on (b.sq_sistema   = c.sq_sistema and
                                                                             b.nome         = t.owner
                                                                            )
                                       inner   join siw.dc_stored_proc d on (t.object_name  = d.nome and
                                                                             b.sq_usuario   = d.sq_usuario
                                                                            )
                                 where t.owner = upper(p_owner)
                                   and t.object_type in ('FUNCTION', 'PROCEDURE')
                                   and t.object_name <> 'HASH_MD5'
                               ) y on (x.sq_stored_proc = y.sq_stored_proc)
         where y.sq_stored_proc is null
       );

  -- Remove vínculo entre procedures quando a procedure pai não foi localizada no dicionário de dados
  delete siw.dc_sp_sp
   where sp_pai in 
        (select x.sq_stored_proc
          from siw.dc_stored_proc             x
               inner      join siw.dc_usuario w on (x.sq_usuario = w.sq_usuario and
                                                    w.nome       = upper(p_owner)
                                                   )
               inner      join siw.dc_sistema v on (w.sq_sistema = v.sq_sistema and
                                                    v.sigla      = upper(p_sistema) and
                                                    v.cliente    = p_cliente
                                                   )
               left outer join (select d.sq_stored_proc
                                  from all_objects                     t
                                       inner   join siw.dc_sp_tipo     a on (t.object_type = a.nome)
                                       inner   join siw.dc_sistema     c on (c.cliente      = p_cliente and
                                                                             c.sigla        = upper(p_sistema)
                                                                            )
                                         inner join siw.dc_usuario     b on (b.sq_sistema   = c.sq_sistema and
                                                                             b.nome         = t.owner
                                                                            )
                                       inner   join siw.dc_stored_proc d on (t.object_name  = d.nome and
                                                                             b.sq_usuario   = d.sq_usuario
                                                                            )
                                 where t.owner = upper(p_owner)
                                   and t.object_type in ('FUNCTION', 'PROCEDURE')
                                   and t.object_name <> 'HASH_MD5'
                               ) y on (x.sq_stored_proc = y.sq_stored_proc)
         where y.sq_stored_proc is null
       );

  -- Remove vínculo entre procedures quando a procedure pai não foi localizada no dicionário de dados
  delete siw.dc_sp_sp
   where sp_filha in 
        (select x.sq_stored_proc
          from siw.dc_stored_proc             x
               inner      join siw.dc_usuario w on (x.sq_usuario = w.sq_usuario and
                                                    w.nome       = upper(p_owner)
                                                   )
               inner      join siw.dc_sistema v on (w.sq_sistema = v.sq_sistema and
                                                    v.sigla      = upper(p_sistema) and
                                                    v.cliente    = p_cliente
                                                   )
               left outer join (select d.sq_stored_proc
                                  from all_objects                     t
                                       inner   join siw.dc_sp_tipo     a on (t.object_type = a.nome)
                                       inner   join siw.dc_sistema     c on (c.cliente      = p_cliente and
                                                                             c.sigla        = upper(p_sistema)
                                                                            )
                                         inner join siw.dc_usuario     b on (b.sq_sistema   = c.sq_sistema and
                                                                             b.nome         = t.owner
                                                                            )
                                       inner   join siw.dc_stored_proc d on (t.object_name  = d.nome and
                                                                             b.sq_usuario   = d.sq_usuario
                                                                            )
                                 where t.owner = upper(p_owner)
                                   and t.object_type in ('FUNCTION', 'PROCEDURE')
                                   and t.object_name <> 'HASH_MD5'
                               ) y on (x.sq_stored_proc = y.sq_stored_proc)
         where y.sq_stored_proc is null
       );

  -- Remove procedures não localizadas no dicionário de dados
  delete siw.dc_stored_proc
   where sq_stored_proc in 
        (select x.sq_stored_proc
          from siw.dc_stored_proc             x
               inner      join siw.dc_usuario w on (x.sq_usuario = w.sq_usuario and
                                                    w.nome       = upper(p_owner)
                                                   )
               inner      join siw.dc_sistema v on (w.sq_sistema = v.sq_sistema and
                                                    v.sigla      = upper(p_sistema) and
                                                    v.cliente    = p_cliente
                                                   )
               left outer join (select d.sq_stored_proc
                                  from all_objects                     t
                                       inner   join siw.dc_sp_tipo     a on (t.object_type = a.nome)
                                       inner   join siw.dc_sistema     c on (c.cliente      = p_cliente and
                                                                             c.sigla        = upper(p_sistema)
                                                                            )
                                         inner join siw.dc_usuario     b on (b.sq_sistema   = c.sq_sistema and
                                                                             b.nome         = t.owner
                                                                            )
                                       inner   join siw.dc_stored_proc d on (t.object_name  = d.nome and
                                                                             b.sq_usuario   = d.sq_usuario
                                                                            )
                                 where t.owner = upper(p_owner)
                                   and t.object_type in ('FUNCTION', 'PROCEDURE')
                                   and t.object_name <> 'HASH_MD5'
                               ) y on (x.sq_stored_proc = y.sq_stored_proc)
         where x.sq_stored_proc is null
       );

  -- Remove tabelas não localizadas no dicionário de dados
  delete siw.dc_tabela
   where sq_tabela in 
        (select x.sq_tabela
          from siw.dc_tabela                  x
               inner      join siw.dc_usuario w on (x.sq_usuario = w.sq_usuario and
                                                    w.nome       = upper(p_owner)
                                                   )
               inner      join siw.dc_sistema v on (w.sq_sistema = v.sq_sistema and
                                                    v.sigla      = upper(p_sistema) and
                                                    v.cliente    = p_cliente
                                                   )
               left outer join (select f.sq_tabela
                                  from all_tables                       a
                                       inner      join all_tab_comments b on (a.owner          = b.owner and
                                                                              a.table_name     = b.table_name
                                                                             )
                                       inner      join siw.dc_usuario   c on (a.owner          = c.nome)
                                       inner      join siw.dc_sistema   d on (c.sq_sistema     = d.sq_sistema and
                                                                              d.cliente        = p_cliente and
                                                                              d.sigla          = upper(p_sistema)
                                                                             )
                                       inner join siw.dc_tabela    f on (a.table_name     = f.nome and
                                                                              f.sq_usuario     = c.sq_usuario and
                                                                              f.sq_sistema     = d.sq_sistema
                                                                             ),
                                       siw.dc_tabela_tipo                e
                                 where a.owner      = upper(p_owner)
                                   and e.nome       = 'Tabela'
                               )              y on (x.sq_tabela  = y.sq_tabela)
         where y.sq_tabela is null
       );

  -- Efetiva as operações
  commit;
end SP_PUTDICIONARIO;
/
GRANT EXECUTE ON SP_PUTDICIONARIO TO SIW
/
/
