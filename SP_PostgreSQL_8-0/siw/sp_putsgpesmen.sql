create or replace FUNCTION SP_PutSgPesMen
   (p_operacao             varchar,
    p_Pessoa               numeric,
    p_Menu                 numeric,
    p_Endereco             numeric   
   ) RETURNS VOID AS $$
DECLARE
   w_existe    numeric(18);
   w_tramite   numeric(18);

    c_permissao CURSOR FOR
      select distinct p_pessoa sq_pessoa, a.sq_menu, p_endereco sq_pessoa_endereco, b.level
        from siw_menu a
             inner join (select sq_menu, level 
                           from connectby('siw_menu','sq_menu_pai','sq_menu',to_char(p_menu),0) 
                                as (sq_menu numeric, sq_menu_pai numeric, level int)
                        ) b on (a.sq_menu = b.sq_menu)
      order by level;

    c_filhos CURSOR FOR
      select distinct p_pessoa sq_pessoa, a.sq_menu, p_endereco sq_pessoa_endereco
        from siw_menu a
             inner join (select sq_menu, level 
                           from connectby('siw_menu','sq_menu','sq_menu_pai',to_char(p_menu),0) 
                                as (sq_menu numeric, sq_menu_pai numeric, level int)
                        ) b on (a.sq_menu = b.sq_menu)
       where a.ultimo_nivel = 'S';

    c_tramites CURSOR FOR
      select distinct p_pessoa sq_pessoa, a.sq_menu, p_endereco sq_pessoa_endereco
        from siw_menu a
             inner join (select sq_menu, level 
                           from connectby('siw_menu','sq_menu','sq_menu_pai',to_char(p_menu),0) 
                                as (sq_menu numeric, sq_menu_pai numeric, level int)
                        ) b on (a.sq_menu = b.sq_menu)
       where a.tramite = 'S';
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro em SG_PESSOA_MENU, para cada endereço da organização
      insert into sg_pessoa_menu (sq_pessoa, sq_menu, sq_pessoa_endereco)
        (select distinct p_pessoa sq_pessoa, a.sq_menu, b.sq_pessoa_endereco 
           from siw_menu a
                inner     join siw_menu_endereco b on (a.sq_menu            = b.sq_menu)
                  inner   join eo_localizacao    d on (b.sq_pessoa_endereco = d.sq_pessoa_endereco)
                    inner join sg_autenticacao   c on (c.sq_localizacao     = d.sq_localizacao and
                                                       c.sq_pessoa          = p_pessoa
                                                      )
          where 0         = (select count(*) from sg_pessoa_menu where sq_pessoa = p_pessoa and sq_menu=a.sq_menu and sq_pessoa_endereco=b.sq_pessoa_endereco)
            and a.sq_menu in (select sq_menu
                                from connectby('siw_menu','sq_menu_pai','sq_menu',to_char(p_menu),0) 
                                     as (sq_menu numeric, sq_menu_pai numeric, level int)
                             )
        );
   Elsif p_operacao = 'E' Then
      -- Apaga as permissões de opções de sub-menu, se existirem
      for crec in c_filhos loop
          DELETE FROM sg_pessoa_menu
           where sq_pessoa          = crec.sq_pessoa
             and sq_menu            = crec.sq_menu
             and sq_pessoa_endereco = crec.sq_pessoa_endereco;
      end loop;

      -- Para todas as opções superiores à informada, executa o bloco abaixo
      for crec in c_Permissao loop
         -- Verifica se a opção a ser excluída tem opções subordinadas a ela. Exclui apenas se não tiver, para evitar erro.
         select count(*) into w_existe
           from siw_menu a
          where a.sq_menu <> crec.sq_menu
            and a.sq_menu in (select sq_menu from siw_menu_endereco where sq_pessoa_endereco = crec.sq_pessoa_endereco)
            and a.sq_menu in (select sq_menu from sg_pessoa_menu    where sq_pessoa          = crec.sq_pessoa       and sq_pessoa_endereco = crec.sq_pessoa_endereco)
            and a.sq_menu in (select sq_menu
                                from connectby('siw_menu','sq_menu','sq_menu_pai',to_char(crec.sq_menu),0) 
                                     as (sq_menu numeric, sq_menu_pai numeric, level int)
                             );
                  
         -- Verifica se a opção a ser excluída tem permissões a trâmites de serviços subordinados a ela.
         select count(*) into w_tramite
           from sg_tramite_pessoa      x
                inner join siw_tramite y on (x.sq_siw_tramite = y.sq_siw_tramite)
          where x.sq_pessoa = crec.sq_pessoa
            and y.sq_menu in (select distinct a.sq_menu
                                from siw_menu a
                                     inner join (select sq_menu
                                                   from connectby('siw_menu','sq_menu','sq_menu_pai',to_char(crec.sq_menu),0) 
                                                        as (sq_menu numeric, sq_menu_pai numeric, level int)
                                                ) b on (a.sq_menu = b.sq_menu)
                               where a.tramite = 'S'
                             );
                                  
         If w_existe = 0 and w_tramite = 0 Then
            DELETE FROM sg_pessoa_menu
             where sq_pessoa          = crec.sq_pessoa
               and sq_menu            = crec.sq_menu
               and sq_pessoa_endereco = crec.sq_pessoa_endereco;
         End If;
      end loop;
   End If;
   
   commit;   
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;