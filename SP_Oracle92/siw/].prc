create or replace procedure SP_PutLcModalidade
   (p_operacao                 in  varchar2,
    p_chave                    in  number    default null,
    p_cliente                  in  number    default null,
    p_nome                     in  varchar2  default null,
    p_sigla                    in  varchar2  default null,    
    p_descricao                in  varchar2  default null,
    p_fundamentacao            in  varchar2  default null,
    p_minimo_pesquisas         in  number,
    p_minimo_participantes     in  number,
    p_minimo_propostas_validas in  number,
    p_certame                  in  varchar2,
    p_enquadramento_inicial    in number,
    p_enquadramento_final      in number,
    p_ativo                    in  varchar2  default null,
    p_padrao                   in  varchar2  default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into lc_modalidade
             (sq_lcmodalidade,cliente,   nome,   sigla,   descricao,   fundamentacao,minimo_pesquisas,minimo_participantes,minimo_propostas_validas,certame ,enquadramento_inicial,enquadramento_final, ativo,padrao
             )
      (select sq_lcmodalidade.nextval, p_cliente, p_nome, p_sigla, p_descricao, p_fundamentacao,p_minimo_pesquisas,p_minimo_participantes,p_minimo_propostas_validas,p_certame ,p_enquadramento_inicial,p_enquadramento_final, p_ativo, p_padrao
         from dual
      );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update lc_modalidade set
         nome                          = p_nome,
         sigla                         = p_sigla,
         descricao                     = p_descricao,
         fundamentacao                 = p_fundamentacao,
         ativo                         = p_ativo,
         padrao                        = p_padrao,
         minimo_pesquisas              = p_minimo_pesquisas,
         minimo_participantes          = p_minimo_participantes,
         minimo_propostas_validas      = p_minimo_propostas_validas,
         certame                       = p_certame,
         enquadramento_inicial         = p_enquadramento_inicial,
         enquadramento_final           = p_enquadramento_final
       where sq_lcmodalidade           = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete lc_modalidade where sq_lcmodalidade = p_chave;
   End If;
end SP_PutLcModalidade;
/
