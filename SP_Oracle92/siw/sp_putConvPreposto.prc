create or replace procedure SP_PutConvPreposto
   ( l_operacao               in varchar2,
     p_restricao              in varchar2,
     p_chave                  in number    default null,
     p_sq_acordo_outra_parte  in number    default null,
     p_sq_pessoa              in number    default null,
     p_cliente                in number    default null,
     p_cpf                    in varchar2  default null,
     p_nome                   in varchar2  default null,
     p_nome_resumido          in varchar2  default null,
     p_sexo                   in varchar2  default null,
     p_rg_numero              in varchar2  default null,
     p_rg_emissao             in date      default null,
     p_rg_emissor             in varchar2  default null
   ) is
   
   w_sg_modulo       varchar2(10);
   w_existe          number(18);
   w_chave_pessoa    number(18) := Nvl(p_sq_pessoa,0);
   w_sq_tipo_pessoa  number(18);
   w_sq_tipo_vinculo number(18);
   w_outra_parte1    number(18);
   w_outra_parte2    number(18);
   w_chave           number(18);
   w_preposto        number(18);
begin
   If l_operacao = 'I' Then
      -- Carrega a chave da tabela CO_TIPO_PESSOA
      select sq_tipo_pessoa into w_sq_tipo_pessoa from co_tipo_pessoa where nome = 'Física';

      select count(*) into w_existe from co_pessoa_fisica where cliente = p_cliente and cpf = p_cpf;
      If w_existe > 0 Then
         select sq_pessoa into w_chave_pessoa from co_pessoa_fisica where cliente = p_cliente and cpf = p_cpf;
      End If;

      If w_chave_pessoa = 0 Then -- Se a chave da pessoa não foi informada, insere
         -- Verifica se o tipo do acordo e carrega a chave da tabela CO_TIPO_VINCULO
         select sq_tipo_vinculo into w_sq_tipo_vinculo from co_tipo_vinculo where nome = 'Cliente' and sq_tipo_pessoa = w_sq_tipo_pessoa and cliente = p_cliente;

         -- recupera a próxima chave da pessoa
         select sq_pessoa.nextval into w_chave_pessoa from dual;

         -- insere os dados da pessoa
         insert into co_pessoa
           (sq_pessoa,      sq_pessoa_pai, sq_tipo_vinculo,   sq_tipo_pessoa,   nome,   nome_resumido)
         values
           (w_chave_pessoa, p_cliente,   w_sq_tipo_vinculo, w_sq_tipo_pessoa, p_nome, p_nome_resumido);

         -- Grava dados complementares, dependendo do tipo de acordo
         update co_pessoa set cliente = 'S'    where sq_pessoa = w_chave_pessoa;
          
         -- Grava dados complementares, dependendo do tipo de acordo
         update co_pessoa set cliente = 'S'    where sq_pessoa = w_chave_pessoa;
      End If;
      -- Verifica se os dados de pessoa física já existem
      select count(*) into w_existe from co_pessoa_fisica where sq_pessoa = w_chave_pessoa;
      
      If w_existe = 0 Then -- Se não existir insere
         insert into co_pessoa_fisica
           (sq_pessoa,      cpf,   sexo,   rg_numero,   rg_emissor, rg_emissao,     cliente)
         values
           (w_chave_pessoa, p_cpf, p_sexo, p_rg_numero, p_rg_emissor, p_rg_emissao, p_cliente);
      End If;
      
      -- Insere registro
      insert into ac_acordo_preposto
         (sq_siw_solicitacao, sq_acordo_outra_parte  , sq_pessoa)
      values
         (p_chave           , p_sq_acordo_outra_parte, w_chave_pessoa);
      
      select nvl(preposto,0) into w_existe from ac_acordo where sq_siw_solicitacao = p_chave;
      If w_existe = 0 Then
         select outra_parte into w_outra_parte1 from ac_acordo where sq_siw_solicitacao = p_chave;
         select outra_parte into w_outra_parte2 from ac_acordo_outra_parte where sq_acordo_outra_parte = p_sq_acordo_outra_parte;
         If w_outra_parte1 = w_outra_parte2 Then
           update ac_acordo set preposto = w_chave_pessoa where sq_siw_solicitacao = p_chave;
         End If;
      End If;      
   Elsif  l_operacao = 'A' Then 
   -- Caso contrário, altera
         update co_pessoa
            set nome          = p_nome,
                nome_resumido = p_nome_resumido
          where sq_pessoa = w_chave_pessoa;
   -- Caso contrário, altera
         update co_pessoa_fisica
            set cpf                = p_cpf,
                sexo               = p_sexo,
                rg_numero          = p_rg_numero,
                rg_emissor         = p_rg_emissor,
                rg_emissao         = p_rg_emissao
          where sq_pessoa = w_chave_pessoa;
      
   Elsif l_operacao = 'E' Then
      -- Exclui registro
      select sq_siw_solicitacao into w_chave 
        from ac_acordo_outra_parte 
       where sq_acordo_outra_parte = p_sq_acordo_outra_parte;
       
      select count(*) into w_existe from ac_acordo_preposto where sq_acordo_outra_parte = p_sq_acordo_outra_parte;
      
      delete ac_acordo_preposto  
       where sq_acordo_outra_parte = p_sq_acordo_outra_parte
         and sq_pessoa             = w_chave_pessoa;             
      
      If w_existe > 1 Then
        select sq_pessoa into w_preposto 
          from ac_acordo_preposto 
         where sq_acordo_outra_parte = p_sq_acordo_outra_parte
           and rownum = 1;
         
        update ac_acordo set preposto = w_preposto
         where sq_siw_solicitacao = w_chave
           and preposto           = w_chave_pessoa;
      Else
        update ac_acordo set preposto = null         
         where sq_siw_solicitacao = w_chave
           and preposto           = w_chave_pessoa;
      End If;      
   End If;
end SP_PutConvPreposto;
/
