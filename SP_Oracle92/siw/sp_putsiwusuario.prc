create or replace procedure SP_PutSiwUsuario
   (p_operacao            in  varchar2,
    p_chave               in  number   default null,
    p_cliente             in  number   default null,
    p_nome                in  varchar2 default null,
    p_nome_resumido       in  varchar2 default null,
    p_cpf                 in  varchar2 default null,
    p_sexo                in  varchar2 default null,
    p_vinculo             in  number   default null,
    p_tipo_pessoa         in  varchar2 default null,
    p_unidade             in  number   default null,
    p_localizacao         in  number   default null,
    p_username            in  varchar2 default null,
    p_email               in  varchar2 default null,
    p_gestor_seguranca    in  varchar2 default null,
    p_gestor_sistema      in  varchar2 default null,
    p_tipo_autenticacao   in  varchar2 default null
   ) is
   w_existe number(18);
   w_chave  number(18);
begin
   w_chave := p_chave;
   
   -- Verifica se o usuário já existe em CO_PESSOA_FISICA
   if p_cpf is not null then
     select count(a.sq_pessoa) into w_existe 
       from co_pessoa_fisica a
            join co_pessoa   b on (a.sq_pessoa = b.sq_pessoa and b.sq_pessoa_pai = p_cliente)
      where cpf = p_cpf;
     if w_existe > 0 then
        select a.sq_pessoa into w_chave
          from co_pessoa_fisica a
               join co_pessoa   b on (a.sq_pessoa = b.sq_pessoa and b.sq_pessoa_pai = p_cliente)
         where cpf = p_cpf;
     else
        select count(a.sq_pessoa) into w_existe 
          from sg_autenticacao  a
               join co_pessoa   b on (a.sq_pessoa = b.sq_pessoa and b.sq_pessoa_pai = p_cliente)
         where username = p_cpf;
        if w_existe > 0 then
           select a.sq_pessoa into w_chave
             from sg_autenticacao  a
                  join co_pessoa   b on (a.sq_pessoa = b.sq_pessoa and b.sq_pessoa_pai = p_cliente)
            where username = p_cpf;
        end if;
     end if;
   end if;
   
   If InStr('IA',p_operacao) > 0 Then
      -- Verifica se a pessoa já existe e decide se é inclusão ou alteração
      select count(*) into w_existe from co_pessoa where sq_pessoa = nvl(w_chave,0);
      -- Se não existir, executa a inclusão
      If w_existe = 0 Then
         -- Recupera a próxima chave
         select sq_pessoa.nextval into w_Chave from dual;
          
         -- Insere registro em CO_PESSOA
         insert into co_pessoa (
            sq_pessoa,      sq_pessoa_pai, sq_tipo_vinculo,
            sq_tipo_pessoa, nome,          nome_resumido)
         (select
            w_Chave,        p_cliente,     p_vinculo,
            sq_tipo_pessoa, p_nome,        p_nome_resumido
            from co_tipo_pessoa
           where ativo         = 'S'
             and nome          = p_tipo_pessoa
         );
         
         -- Insere registro em CO_PESSOA_FISICA
         insert into co_pessoa_fisica (sq_pessoa, cpf, sexo, cliente) values (w_chave, p_cpf, p_sexo, p_cliente);
         
      -- Se existir, executa a alteração
      Else
         -- Atualiza tabela corporativa de pessoas
         Update co_pessoa set
             sq_tipo_vinculo  = p_vinculo,
             nome             = trim(p_nome), 
             nome_resumido    = trim(p_nome_resumido)
         where sq_pessoa      = w_chave;

         -- Verifica se o registro existe em CO_PESSOA_FISICA, para garantir que o registro existe
         -- Necessário devido à alteração na lógica de gravação de username
         select count(a.sq_pessoa) into w_existe 
           from co_pessoa_fisica a
                join co_pessoa   b on (a.sq_pessoa = b.sq_pessoa and b.sq_pessoa_pai = p_cliente)
          where cpf = p_cpf;
         if w_existe = 0 then
            insert into co_pessoa_fisica (sq_pessoa, cpf, sexo, cliente) values (w_chave, p_cpf, p_sexo, p_cliente);
         else
            Update co_pessoa_fisica set sexo  = p_sexo where sq_pessoa = w_chave;
         end if;
       End If;

      -- Verifica se o usuário já existe e decide se é inclusão ou alteração
      select count(*) into w_existe from sg_autenticacao where sq_pessoa = nvl(w_chave,0);
      -- Se não existir, executa a inclusão
      If w_existe = 0 Then
         -- Insere registro em SG_AUTENTICACAO
         Insert into sg_autenticacao
            ( sq_pessoa,            sq_unidade,       sq_localizacao,
              cliente,              username,         email,
              gestor_seguranca,     gestor_sistema,   senha,          
              assinatura,           tipo_autenticacao
            )
         Values
            ( w_chave,              p_unidade,        p_localizacao,
              p_cliente,            p_username,       p_email,
              coalesce(p_gestor_seguranca,'N'),       coalesce(p_gestor_sistema,'N'), 
              case p_tipo_autenticacao  when 'B' then criptografia(p_username) else 'Externa' end,
              criptografia(p_username) , 
              p_tipo_autenticacao
            );
         
         -- Insere registros de configuração de e-mail
         insert into sg_pessoa_mail(sq_pessoa_mail, sq_pessoa, sq_menu, alerta_diario, tramitacao, conclusao, responsabilidade)
         (select sq_pessoa_mail.nextval, a.sq_pessoa, c.sq_menu, 'S', 'S', 'S', 
                 case when substr(c.sigla, 1,2) = 'PJ' then 'S' else 'N' end
            from sg_autenticacao        a 
                 inner   join co_pessoa b on (a.sq_pessoa     = b.sq_pessoa)
                   inner join siw_menu  c on (b.sq_pessoa_pai = c.sq_pessoa and c.tramite = 'S')
           where 0   = (select count(*) from sg_pessoa_mail where sq_pessoa = a.sq_pessoa and sq_menu = c.sq_menu)
             and a.sq_pessoa = w_chave
         );
         
      -- Se existir, executa a alteração
      Else
         -- Atualiza registro na tabela de segurança
         Update sg_autenticacao set
             username              = p_username,
             senha                 = case p_tipo_autenticacao  when 'B' then senha else 'Externa' end,
             sq_unidade            = p_unidade,
             sq_localizacao        = p_localizacao,
             gestor_seguranca      = coalesce(p_gestor_seguranca,gestor_seguranca),
             gestor_sistema        = coalesce(p_gestor_sistema,gestor_sistema),
             email                 = p_email,
             tipo_autenticacao     = p_tipo_autenticacao                             
         where sq_pessoa      = w_chave;
       End If;
          
   Elsif p_operacao = 'E' Then
      -- Remove o registro na tabela de configuração de e-mail
      delete sg_pessoa_mail where sq_pessoa = w_chave;
        
      -- Remove o registro na tabela de segurança
      delete sg_autenticacao where sq_pessoa = w_chave;
        
      -- Remove da tabela de pessoas físicas
      delete co_pessoa_fisica where sq_pessoa = w_chave;

      -- Remove da tabela corporativa de pessoas
      delete co_pessoa where sq_pessoa = w_chave;
   Else
      If p_operacao = 'T' Then
         -- Ativa registro
         update sg_autenticacao set ativo = 'S' where sq_pessoa = w_chave;
      Elsif p_operacao = 'D' Then
         -- Desativa registro
         update sg_autenticacao set ativo = 'N' where sq_pessoa = w_chave;
      End If;
   End If;

end SP_PutSiwUsuario;
/
