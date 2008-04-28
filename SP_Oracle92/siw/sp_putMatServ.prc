create or replace procedure sp_putMatServ
   (p_operacao        in  varchar2,
    p_cliente         in  number,
    p_usuario         in  number,
    p_chave           in  number   default null,
    p_copia           in  number   default null,
    p_tipo_material   in  number   default null,
    p_unidade_medida  in  number   default null,
    p_nome            in  varchar2 default null,
    p_descricao       in  varchar2 default null,
    p_detalhamento    in  varchar2 default null,
    p_apresentacao    in  varchar2 default null,
    p_codigo_interno  in  varchar2 default null,
    p_codigo_externo  in  varchar2 default null,
    p_exibe_catalogo  in  varchar2 default null,
    p_vida_util       in  number   default null,
    p_ativo           in  varchar2 default null,
    p_chave_nova      out number
   ) is
   w_chave number(18) := p_chave;
begin
   If p_operacao = 'I' or p_operacao = 'C' Then
      -- Gera a nova chave do registro, a partir da sequence
      select sq_material.nextval into w_chave from dual;

      -- Insere registro
      insert into cl_material
        (sq_material,          cliente,         sq_tipo_material,           sq_unidade_medida,    nome, 
         descricao,            detalhamento,    apresentacao,               codigo_interno,       codigo_externo,   exibe_catalogo,
         vida_util,            ativo)
      values
        (w_chave,              p_cliente,       p_tipo_material,            p_unidade_medida,     p_nome, 
         p_descricao,          p_detalhamento,  p_apresentacao,             p_codigo_interno,     p_codigo_externo, p_exibe_catalogo,
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
             codigo_interno    = p_codigo_interno,
             codigo_externo    = p_codigo_externo,
             exibe_catalogo    = p_exibe_catalogo,
             vida_util         = p_vida_util,
             ativo             = p_ativo
       where sq_material = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui o material
      delete cl_material              where sq_material = p_chave;
   End If;
   
   -- Devolve a chave
   If p_chave is not null
      Then p_chave_nova := p_chave;
      Else p_chave_nova := w_chave;
   End If;
end sp_putMatServ;
/
