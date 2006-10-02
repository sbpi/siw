create or replace procedure SP_PutSiwUsuario
   (p_operacao            in  varchar2,
    p_chave               in  number   default null,
    p_cliente             in  number,
    p_nome                in  varchar2,
    p_nome_resumido       in  varchar2,
    p_vinculo             in  number,
    p_tipo_pessoa         in  varchar2,
    p_unidade             in  number,
    p_localizacao         in  number,
    p_username            in  varchar2,
    p_email               in  varchar2,
    p_gestor_seguranca    in  varchar2,
    p_gestor_sistema      in  varchar2
   ) is
   w_existe          number(18);
   w_chave           number(18);
   w_sq_tipo_vinculo number(18);
begin
   w_chave := p_chave;
   
   -- Verifica se o usuário já existe em CO_PESSOA_FISICA
   if p_username is not null then
     select count(a.sq_pessoa) into w_existe 
       from co_pessoa_fisica a, co_pessoa   b
      where (a.sq_pessoa = b.sq_pessoa and b.sq_pessoa_pai = p_cliente)
        and cpf = p_username;
     if w_existe > 0 then
        select a.sq_pessoa into w_chave
          from co_pessoa_fisica a, co_pessoa   b
         where (a.sq_pessoa = b.sq_pessoa and b.sq_pessoa_pai = p_cliente)
           and cpf = p_username;
     else
        select count(a.sq_pessoa) into w_existe 
          from sg_autenticacao  a, co_pessoa   b 
         where (a.sq_pessoa = b.sq_pessoa and b.sq_pessoa_pai = p_cliente)
           and username = p_username;
        if w_existe > 0 then
           select a.sq_pessoa into w_chave
             from sg_autenticacao  a, co_pessoa   b
            where (a.sq_pessoa = b.sq_pessoa and b.sq_pessoa_pai = p_cliente)
              and username = p_username;
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
         
      -- Se existir, executa a alteração
      Else
         
         select a.sq_tipo_vinculo into w_sq_tipo_vinculo from co_pessoa a where sq_pessoa = w_chave;
         
         -- Atualiza tabela corporativa de pessoas
         Update co_pessoa set
             sq_tipo_vinculo  = Nvl(p_vinculo,w_sq_tipo_vinculo),
             nome             = trim(p_nome), 
             nome_resumido    = trim(p_nome_resumido)
         where sq_pessoa      = w_chave;
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
              assinatura
            )
         Values
            ( w_chave,              p_unidade,        p_localizacao,
              p_cliente,            p_username,       p_email,
              Nvl(p_gestor_seguranca,'N'),   Nvl(p_gestor_sistema,'N'), criptografia(p_username),
              criptografia(p_username)
            );
      -- Se existir, executa a alteração
      Else
         -- Atualiza registro na tabela de segurança
         Update sg_autenticacao set
             sq_unidade       = p_unidade,
             sq_localizacao   = p_localizacao,
             gestor_seguranca = Nvl(p_gestor_seguranca,gestor_seguranca),
             gestor_sistema   = Nvl(p_gestor_sistema,gestor_sistema),
             email            = p_email
         where sq_pessoa      = w_chave;
       End If;
          
   Elsif p_operacao = 'E' Then
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
   commit;
end SP_PutSiwUsuario;
/
