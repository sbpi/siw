create or replace procedure SP_PutRestricaoEtapa
   (p_operacao                 in varchar2,
    p_chave                    in number,
    p_sq_projeto_etapa         in number
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into siw_restricao_etapa
          (sq_siw_restricao,     sq_projeto_etapa)
        values
          (p_chave,     p_sq_projeto_etapa);
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete siw_restricao_etapa where sq_siw_restricao = p_chave;
   End If;
end SP_PutRestricaoEtapa;
/
