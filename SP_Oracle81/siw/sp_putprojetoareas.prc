create or replace procedure SP_PutProjetoAreas
   (p_operacao            in  varchar2,
    p_chave               in number,
    p_chave_aux           in number    default null,
    p_papel               in varchar2  default null
   ) is
begin
   If p_operacao = 'I' Then -- Inclus�o
      -- Insere registro na tabela de �reas envolvidas
      Insert Into pj_projeto_envolv 
         ( sq_unidade,  sq_siw_solicitacao, papel )
      Values
         ( p_chave_aux, p_chave,            trim(p_papel) );
   Elsif p_operacao = 'A' Then -- Altera��o
      -- Atualiza a tabela de �reas envolvidas
      Update pj_projeto_envolv set
          papel            = trim(p_papel)
      where sq_siw_solicitacao = p_chave
        and sq_unidade         = p_chave_aux;
   Elsif p_operacao = 'E' Then -- Exclus�o
      -- Remove o registro na tabela de �reas envolvidas
      delete pj_projeto_envolv  
       where sq_siw_solicitacao = p_chave
         and sq_unidade         = p_chave_aux;
   End If;
end SP_PutProjetoAreas;
/

