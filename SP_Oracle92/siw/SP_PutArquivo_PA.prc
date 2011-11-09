create or replace procedure SP_PutArquivo_PA
   (p_operacao                 in  varchar2,
    p_cliente                  in  number,
    p_chave                    in  number   default null,
    p_nome                     in  varchar2 default null,
    p_ativo                    in  varchar2 default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
         insert into pa_arquivo (sq_localizacao, cliente,   nome,   ativo)
         (select                 p_chave,        p_cliente, p_nome, p_ativo from dual);
      -- Insere Registro na tabela de locais
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update pa_arquivo set
         nome                   = p_nome,
         ativo                  = p_ativo
      where sq_localizacao      = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete pa_arquivo_local 
      where sq_localizacao    = p_chave
        and sq_arquivo_local in (select sq_arquivo_local 
                                   from (select sq_arquivo_local
                                           from pa_arquivo_local
                                          where sq_localizacao = p_chave
                                         connect by prior sq_arquivo_local = sq_local_pai
                                         start with sq_local_pai is null
                                         order by level desc
                                        )
                                );
      delete pa_arquivo       where sq_localizacao = p_chave;
   End If;
end SP_PutArquivo_PA;
/
