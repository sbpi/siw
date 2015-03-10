create or replace procedure SP_PutCoMoeda
   (p_operacao                 in  varchar2,
    p_chave                    in  number   default null,
    p_codigo                   in  varchar2 default null,
    p_nome                     in  varchar2 default null,
    p_sigla                    in  varchar2 default null,
    p_simbolo                  in  varchar2 default null,
    p_tipo                     in  varchar2 default null,
    p_serie_compra             in  number   default null,
    p_serie_venda              in  number   default null,
    p_exclusao_ptax            in  date     default null,
    p_ativo                    in  varchar2 default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into co_moeda (sq_moeda, nome, codigo, sigla, simbolo, tipo, exclusao_ptax, bc_serie_compra, bc_serie_venda, ativo)
         (select Nvl(p_Chave, sq_moeda.nextval),
                 trim(upper(p_nome)),
                 trim(p_codigo),                 
                 p_sigla,
                 p_simbolo,
                 p_tipo,
                 p_exclusao_ptax,
                 p_serie_compra,
                 p_serie_venda,
                 p_ativo
            from dual
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update co_moeda
         set nome                = trim(upper(p_nome)),
             codigo              = trim(p_codigo),
             sigla               = upper(p_sigla),
             simbolo             = p_simbolo,
             tipo                = upper(p_tipo),
             exclusao_ptax       = p_exclusao_ptax,
             bc_serie_compra     = p_serie_compra,
             bc_serie_venda      = p_serie_venda,
             ativo               = p_ativo
      where sq_moeda = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui cotações vinculadas
      delete co_moeda_cotacao where sq_moeda = p_chave;
      
      -- Exclui registro
      delete co_moeda where sq_moeda = p_chave;
   End If;
end SP_PutCoMoeda;
/
