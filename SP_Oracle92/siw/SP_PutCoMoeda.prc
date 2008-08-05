create or replace procedure SP_PutCoMoeda
   (p_operacao                 in  varchar2,
    p_chave                    in  number default null,
    p_codigo                   in  varchar2,
    p_nome                     in  varchar2,
    p_sigla                    in  varchar2,
    p_simbolo                  in  varchar2,
    p_tipo                     in  varchar2,
    p_exclusao_ptax            in      date,
    p_ativo                    in  varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into co_moeda (sq_moeda, nome, codigo, sigla, simbolo, tipo, exclusao_ptax, ativo)
         (select Nvl(p_Chave,sq_moeda.nextval),
                 trim(upper(p_nome)),
                 trim(p_codigo),                 
                 p_sigla,
                 p_simbolo,
                 p_tipo,
                 p_exclusao_ptax,
                 p_ativo
            from dual
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update co_moeda   set 
         nome                 = trim(upper(p_nome)),
         codigo               = trim(p_codigo),
         sigla                = upper(p_sigla),
         simbolo              = p_simbolo,
         tipo                 = upper(p_tipo),
         exclusao_ptax        = p_exclusao_ptax,
         ativo                = p_ativo
      where sq_moeda    = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete co_moeda where sq_moeda = p_chave;
   End If;
end SP_PutCoMoeda;
/
