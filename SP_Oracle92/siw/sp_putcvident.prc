create or replace procedure SP_PutCVIdent
   (p_operacao            in varchar2,
    p_cliente             in number,
    p_chave               in number     default null,
    p_nome                in varchar2,
    p_nome_resumido       in varchar2,
    p_foto                in varchar2,
    p_tamanho             in number    default null,
    p_tipo                in varchar2  default null,
    p_nome_original       in varchar2   default null,    
    p_nascimento          in date,
    p_sexo                in varchar2,
    p_sq_estado_civil     in number,
    p_sq_formacao         in number,
    p_sq_etnia            in number    default null,
    p_sq_deficiencia      in number    default null,
    p_cidade              in number,
    p_rg_numero           in varchar2,
    p_rg_emissor          in varchar2,
    p_rg_emissao          in date,
    p_cpf                 in varchar2,
    p_passaporte_numero   in varchar2   default null,
    p_sq_pais_passaporte  in number     default null,
    p_chave_nova          out number
   ) is
   w_existe number(18);
   w_chave  number(18);
   w_foto   number(18);
begin
   w_chave := p_chave;
   w_foto  := null;
   
   If nvl(p_chave,0) = 0 Then
      -- Recupera a próxima chave
      select sq_pessoa.nextval into w_Chave from dual;
      
     -- Insere registro em CO_PESSOA
     insert into co_pessoa (
        sq_pessoa,      sq_pessoa_pai, sq_tipo_vinculo,
        sq_tipo_pessoa, nome,          nome_resumido)
     (select
        w_Chave,        p_cliente,     null,
        sq_tipo_pessoa, p_nome,        p_nome_resumido
        from co_tipo_pessoa
       where ativo         = 'S'
         and nome          = 'Física'
     );
   Else
     -- Altera o nome e o nome resumido na tabela de pessoas
     update co_pessoa
        set nome          = p_nome,
            nome_resumido = p_nome_resumido
      where sq_pessoa = w_chave;
   End If;
   
   
   -- Verifica se existe dado para a pessoa na tabela de pessoas físicas
   select count(*) into w_existe from co_pessoa_fisica where sq_pessoa = w_chave;
   
   -- Insere ou altera, dependendo do resultado
   If w_existe = 0 Then
      insert into co_pessoa_fisica
        (sq_pessoa,            nascimento,          rg_numero,            rg_emissor, 
         rg_emissao,           cpf,                 sq_cidade_nasc,       passaporte_numero,   
         sq_pais_passaporte,   sexo,                cliente,              sq_formacao,
         sq_etnia,             sq_deficiencia)
      values
        (w_chave,              p_nascimento,        p_rg_numero,          p_rg_emissor, 
         p_rg_emissao,         p_cpf,               p_cidade,             p_passaporte_numero, 
         p_sq_pais_passaporte, p_sexo,              p_cliente,            p_sq_formacao,
         p_sq_etnia,           p_sq_deficiencia);
   Else
      update co_pessoa_fisica
         set nascimento         = p_nascimento,
             rg_numero          = p_rg_numero,
             rg_emissor         = p_rg_emissor,
             rg_emissao         = p_rg_emissao,
             cpf                = p_cpf,
             sq_cidade_nasc     = p_cidade,
             passaporte_numero  = p_passaporte_numero,
             sq_pais_passaporte = p_sq_pais_passaporte,
             sexo               = p_sexo,
             sq_formacao        = p_sq_formacao,
             sq_etnia           = p_sq_etnia,
             sq_deficiencia     = p_sq_deficiencia
       where sq_pessoa = w_chave;
   End If;

   -- Verifica se existe dado para a pessoa na tabela principal de currículo
   select count(*) into w_existe from cv_pessoa where sq_pessoa = w_chave;
   
   -- Insere ou altera, dependendo do resultado
   If w_existe = 0 Then
      insert into cv_pessoa
        (sq_pessoa, cliente, sq_estado_civil, inclusao, alteracao)
      values
        (w_chave, p_cliente, p_sq_estado_civil, sysdate, sysdate);
   Else
      -- Recupera a foto atual
      select sq_siw_arquivo into w_foto from cv_pessoa where sq_pessoa = w_chave;
      
      update cv_pessoa
         set sq_estado_civil = p_sq_estado_civil,
             alteracao       = sysdate
       where sq_pessoa = w_chave;
   End If;
   
   -- Verifica se é necessário gravar a foto
   If p_foto is not null Then
      If w_foto is null Then
         -- Recupera a próxima chave
         select sq_siw_arquivo.nextval into w_foto from dual;
         
         -- Insere registro em SIW_ARQUIVO
         insert into siw_arquivo
           (sq_siw_arquivo, cliente, nome, descricao, inclusao, tamanho, tipo, caminho, nome_original)
         values
           (w_foto, p_cliente, 'Fotografia', 'Fotografia associada ao CV.', sysdate, p_tamanho, p_tipo, p_foto, p_nome_original);
           
         -- Associa a fotografia ao CV
         update cv_pessoa set sq_siw_arquivo = w_foto where sq_pessoa = w_chave;
      Else
         update siw_arquivo set
            inclusao = sysdate,
            caminho  = p_foto,
            tamanho  = p_tamanho,
            tipo     = p_tipo,
            nome_original = p_nome_original
         where sq_siw_arquivo = w_foto;
      End If;
   End If;
   
   commit;
   
   -- Devolve a chave
   p_chave_nova := w_chave;
   
end SP_PutCVIdent;
/
