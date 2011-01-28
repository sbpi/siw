create or replace FUNCTION SP_PutSgPerMen
   (p_operacao             varchar,
    p_Perfil               numeric,
    p_Menu                 numeric,
    p_Endereco             numeric
   ) RETURNS VOID AS $$
DECLARE
   w_existe    numeric(18);
    c_permissao CURSOR FOR
      select distinct p_Perfil sq_tipo_vinculo, a.sq_menu, p_Endereco sq_pessoa_endereco, level
        from siw_menu a
             inner join (select sq_menu, level 
                           from connectby('siw_menu','sq_menu_pai','sq_menu',to_char(p_menu),0) 
                                as (sq_menu numeric, sq_menu_pai numeric, level int)
                        ) b on (a.sq_menu = b.sq_menu)
      order by level;

    c_filhos CURSOR FOR
      select distinct p_Perfil sq_tipo_vinculo, a.sq_menu, p_Endereco sq_pessoa_endereco
        from siw_menu a
             inner join (select sq_menu, level 
                           from connectby('siw_menu','sq_menu','sq_menu_pai',to_char(p_menu),0) 
                                as (sq_menu numeric, sq_menu_pai numeric, level int)
                        ) b on (a.sq_menu = b.sq_menu)
       where a.ultimo_nivel = 'S';

BEGIN
   If p_operacao = 'I' Then
      -- Insere registro em SG_PERFIL_MENU, para cada endereço da organização
      insert into sg_perfil_menu (sq_tipo_vinculo, sq_menu, sq_pessoa_endereco)
        (select distinct p_Perfil, a.sq_menu, p_Endereco
           from siw_menu a
         where 0 = (select count(*) from sg_perfil_menu    x where x.sq_tipo_vinculo = p_perfil and x.sq_menu = a.sq_menu and x.sq_pessoa_endereco = p_endereco)
           and 0 < (select count(*) from siw_menu_endereco x where x.sq_menu = a.sq_menu and x.sq_pessoa_endereco = p_endereco)
           and a.sq_menu in (select sq_menu
                               from connectby('siw_menu','sq_menu_pai','sq_menu',to_char(p_menu),0) 
                                    as (sq_menu numeric, sq_menu_pai numeric, level int)
                            )
        );
   Elsif p_operacao = 'E' Then
      -- Apaga as permissões de opções de sub-menu, se existirem
      for crec in c_filhos loop
          DELETE FROM sg_Perfil_menu
           where sq_tipo_vinculo    = crec.sq_tipo_vinculo
             and sq_menu            = crec.sq_menu
             and sq_pessoa_endereco = crec.sq_pessoa_endereco;
      end loop;

      -- Para todas as opções superiores à informada, executa o bloco abaixo
      for crec in c_Permissao loop
         -- Verifica se a opção a ser excluída tem opções subordinadas a ela.
         -- Exclui apenas se não tiver, para evitar erro.
         select count(*) into w_existe
           from siw_menu a
          where sq_menu <> crec.sq_menu
            and sq_menu in (select sq_menu from siw_menu_endereco where sq_pessoa_endereco = crec.sq_pessoa_endereco)
            and sq_menu in (select sq_menu from sg_perfil_menu    where sq_tipo_vinculo    = crec.sq_tipo_vinculo and sq_pessoa_endereco = crec.sq_pessoa_endereco)
            and a.sq_menu in (select sq_menu
                                from connectby('siw_menu','sq_menu','sq_menu_pai',to_char(crec.sq_menu),0) 
                                     as (sq_menu numeric, sq_menu_pai numeric, level int)
                             );

         If w_existe = 0 Then
            DELETE FROM sg_Perfil_menu
             where sq_tipo_vinculo    = crec.sq_tipo_vinculo
               and sq_menu            = crec.sq_menu
               and sq_pessoa_endereco = crec.sq_pessoa_endereco;
         End If;
      end loop;
   End If;
   
   commit;   
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;