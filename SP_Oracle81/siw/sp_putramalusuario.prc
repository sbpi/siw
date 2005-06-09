create or replace procedure SP_PutRamalUsuario
   (p_operacao            in varchar2,
    p_cliente             in number,
    p_chave               in number,
    p_nome                in varchar2,
    p_nome_resumido       in varchar2,
    p_nascimento          in date,
    p_sexo                in varchar2,
    p_sq_estado_civil     in number,
    p_sq_formacao         in number,
    p_sq_etnia            in number,
    p_sq_deficiencia      in number     default null,
    p_cidade              in number,
    p_rg_numero           in varchar2,
    p_rg_emissor          in varchar2,
    p_rg_emissao          in date,
    p_cpf                 in varchar2,
    p_passaporte_numero   in varchar2   default null,
    p_sq_pais_passaporte  in number     default null
   ) is
   w_existe number(18);
   w_chave  number(18);
begin

   -- Altera o nome e o nome resumido na tabela de pessoas
   update co_pessoa
      set nome          = p_nome,
          nome_resumido = p_nome_resumido
    where sq_pessoa = p_chave;
    
   -- Verifica se existe dado para a pessoa na tabela de pessoas físicas
   select count(*) into w_existe from co_pessoa_fisica where sq_pessoa = p_chave;
   
   -- Insere ou altera, dependendo do resultado
   If w_existe = 0 Then
      insert into co_pessoa_fisica
        (sq_pessoa,            nascimento,          rg_numero,            rg_emissor, 
         rg_emissao,           cpf,                 sq_cidade_nasc,       passaporte_numero,   
         sq_pais_passaporte,   sq_etnia,            sq_deficiencia,       sexo, 
         cliente,              sq_formacao)
      values
        (p_chave,              p_nascimento,        p_rg_numero,          p_rg_emissor, 
         p_rg_emissao,         p_cpf,               p_cidade,             p_passaporte_numero, 
         p_sq_pais_passaporte, p_sq_etnia,          p_sq_deficiencia,     p_sexo, 
         p_cliente,            p_sq_formacao);
   Else
      update co_pessoa_fisica
         set nascimento         = p_nascimento,
             rg_numero          = p_rg_numero,
             rg_emissor         = p_rg_emissor,
             rg_emissao         = p_rg_emissao,
             cpf                = p_cpf,
             sq_cidade_nasc      = p_cidade,
             passaporte_numero  = p_passaporte_numero,
             sq_pais_passaporte = p_sq_pais_passaporte,
             sq_etnia           = p_sq_etnia,
             sq_deficiencia     = p_sq_deficiencia,
             sexo               = p_sexo,
             sq_formacao        = p_sq_formacao
       where sq_pessoa = p_chave;
   End If;

   -- Verifica se existe dado para a pessoa na tabela principal de currículo
   select count(*) into w_existe from cv_pessoa where sq_pessoa = p_chave;
   
   -- Insere ou altera, dependendo do resultado
   If w_existe = 0 Then
      insert into cv_pessoa
        (sq_pessoa, cliente, sq_estado_civil, inclusao, alteracao)
      values
        (p_chave, p_cliente, p_sq_estado_civil, sysdate, sysdate);
   Else
      update cv_pessoa
         set sq_estado_civil = p_sq_estado_civil,
             alteracao       = sysdate
       where sq_pessoa = p_chave;
   End If;
   commit;
end SP_PutRamalUsuario;
/

