create or replace FUNCTION sp_putMatServ
   (p_operacao         varchar,
    p_cliente          numeric,
    p_usuario          numeric,
    p_chave            numeric,
    p_copia            numeric,
    p_tipo_material    numeric,
    p_unidade_medida   numeric,
    p_nome             varchar,
    p_descricao        varchar,
    p_detalhamento     varchar,
    p_apresentacao     varchar,
    p_codigo_interno   varchar,
    p_codigo_externo   varchar,
    p_exibe_catalogo   varchar,
    p_vida_util        numeric,
    p_ativo            varchar,
    p_chave_nova      numeric
   ) RETURNS VOID AS $$
DECLARE
   w_raiz      varchar(80);
   w_existe    numeric(18);
   w_chave     numeric(18)                             := p_chave;
   w_codigo    cl_material.codigo_interno%type        := p_codigo_interno;
   w_tipo      cl_tipo_material.sq_tipo_material%type := null;
   w_parametro cl_parametro%rowtype;
   w_material  cl_material%rowtype;
BEGIN
    -- Trata a numeração automática de códigos de material
   If p_operacao <> 'E' Then
      -- Recupera os parâmetros do módulo
      select * into w_parametro from cl_parametro where cliente = p_cliente;
      
      If w_parametro.codificacao_automatica = 'S' Then
         -- Se alteração, recupera os dados do registro existente
         If p_chave is not null Then
            select * into w_material from cl_material where sq_material = p_chave;
            w_codigo := w_material.codigo_interno;
            w_tipo   := w_material.sq_tipo_material;
         End If;
         
         -- Recupera prefixo do código
         select montanometipomaterial(p_tipo_material) into w_raiz;
         
         -- Se o tipo mudar, gera novo código
         If w_tipo is null or w_tipo <> p_tipo_material or (p_chave is not null and instr(w_codigo, w_raiz)=0) Then
            
            -- Remove nome de classe, grupo e subgrupo
            If instr(w_raiz,' ') > 0 Then
               w_raiz := substr(w_raiz,1,instr(w_raiz,' ')-1);
            End If;
            
            -- Recupera o próximo sequencial para o tipo de material informado
            select lpad(coalesce(to_number(max(replace(trim(codigo_interno),w_raiz,''))),0)+1,4,'0')
              into w_codigo
              from cl_material
             where cliente          = p_cliente
               and sq_tipo_material = p_tipo_material
               and codigo_interno   like w_raiz||'%';

            -- Define o código interno do material a partir da raiz e do sequencial
            w_codigo := w_raiz||w_codigo;
         End If;
      End If;
   End If;
   
   If p_operacao = 'I' or p_operacao = 'C' Then
      -- Gera a nova chave do registro, a partir da sequence
      select sq_material.nextval into w_chave;

      -- Insere registro
      insert into cl_material
        (sq_material,          cliente,         sq_tipo_material,           sq_unidade_medida,    nome, 
         descricao,            detalhamento,    apresentacao,               codigo_interno,       codigo_externo,   exibe_catalogo,
         vida_util,            ativo)
      values
        (w_chave,              p_cliente,       p_tipo_material,            p_unidade_medida,     p_nome, 
         p_descricao,          p_detalhamento,  p_apresentacao,             w_codigo,             p_codigo_externo, p_exibe_catalogo,
         p_vida_util,          p_ativo
        );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update cl_material
         set sq_tipo_material  = p_tipo_material,
             sq_unidade_medida = p_unidade_medida,
             nome              = p_nome,
             descricao         = p_descricao,
             detalhamento      = p_detalhamento,
             apresentacao      = p_apresentacao,
             codigo_interno    = w_codigo,
             codigo_externo    = p_codigo_externo,
             exibe_catalogo    = p_exibe_catalogo,
             vida_util         = p_vida_util,
             ativo             = p_ativo
       where sq_material = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui o material
      DELETE FROM cl_material              where sq_material = p_chave;
   End If;
   
   -- Devolve a chave
   If p_chave is not null
      Then p_chave_nova := p_chave;
      Else p_chave_nova := w_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;