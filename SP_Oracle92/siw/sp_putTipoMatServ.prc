create or replace procedure sp_putTipoMatServ
   (p_operacao       in  varchar2            ,
    p_cliente        in  varchar2 default null,
    p_chave          in  number   default null,
    p_chave_pai      in  number   default null,
    p_nome           in  varchar2 default null,
    p_sigla          in  varchar2 default null,
    p_classe         in  number   default null,
    p_gestora        in  number   default null,
    p_descricao      in  varchar2 default null,
    p_codigo_externo in  varchar2 default null,
    p_ativo          in  varchar2 default null
   ) is
begin
   If p_operacao in ('I','C') Then
      -- Insere registro
      insert into cl_tipo_material
        (sq_tipo_material,         cliente,   sq_tipo_pai, nome,   sigla,          classe,   unidade_gestora,  descricao,   codigo_externo,   ativo)
      values
        (sq_tipo_material.nextval, p_cliente, p_chave_pai, p_nome, upper(p_sigla), p_classe, p_gestora,        p_descricao, p_codigo_externo, p_ativo);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update cl_tipo_material
         set sq_tipo_pai     = p_chave_pai,
             nome            = p_nome,
             sigla           = upper(p_sigla),
             classe          = p_classe,
             unidade_gestora = p_gestora,
             descricao       = p_descricao,
             codigo_externo  = p_codigo_externo
       where sq_tipo_material = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete cl_tipo_material where sq_tipo_material = p_chave;
   Elsif p_operacao = 'T' Then
      -- Ativa registro
      update cl_tipo_material set ativo = 'S' where sq_tipo_material = p_chave;
   Elsif p_operacao = 'D' Then
      -- Desativa registro
      update cl_tipo_material set ativo = 'N' where sq_tipo_material = p_chave;
   End If;
end sp_putTipoMatServ;
/
