create or replace procedure SP_PutArquivoLocal_PA
   (p_operacao                 in  varchar2,
    p_chave                    in  number   default null,
    p_nome                     in  varchar2 default null,
    p_local                    in  number   default null,
    p_local_pai                in  number default null,
    p_ativo                    in  varchar2 default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
         insert into pa_arquivo_local(sq_arquivo_local,         sq_localizacao, sq_local_pai, nome,   ativo)
         (select                      sq_arquivo_local.nextval, p_chave,        p_local_pai,  p_nome, p_ativo from dual);
        -- Insere Registro na tabela de locais
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update pa_arquivo_local
         set sq_local_pai = p_local_pai,
             nome         = p_nome,
             ativo        = p_ativo
       where sq_arquivo_local = p_local;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete pa_arquivo_local
       where sq_arquivo_local = p_local;
   End If;
end SP_PutArquivoLocal_PA;
/
