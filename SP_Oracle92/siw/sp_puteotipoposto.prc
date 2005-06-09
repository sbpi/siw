create or replace procedure SP_PutEOTipoPosto
   (p_operacao             in varchar2,
    p_chave                in number    default null,
    p_cliente              in number,
    p_nome                 in varchar2,
    p_sigla                in varchar2,
    p_descricao            in varchar2,
    p_ativo                in varchar2,
    p_padrao               in varchar2
   ) is
begin
   If p_operacao = 'I' Then -- Inclusão
      -- Insere registro na tabela de tipos de posto
      insert into eo_tipo_posto
        (sq_eo_tipo_posto,      cliente,    nome,       sigla,
         descricao,             ativo,      padrao)
      (select 
         sq_tipo_posto.nextval, p_cliente,  p_nome,     p_sigla,
         p_descricao,           p_ativo,    p_padrao
       from dual);
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de tipos de posto
      update eo_tipo_posto
         set nome      = p_nome,
             sigla     = p_sigla,
             descricao = p_descricao,
             ativo     = p_ativo,
             padrao    = p_padrao
       where sq_eo_tipo_posto = p_chave;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Remove o registro na tabela de tipos de posto
      delete eo_tipo_posto
       where sq_eo_tipo_posto = p_chave;
   End If;
end SP_PutEOTipoPosto;
/

