create or replace procedure SP_PutSAreaAtuacao
   (p_operacao                 in  varchar2,
    p_chave                    in  number default null,
    p_co_area_atuacao          in  number default null,
    p_ds_area_atuacao          in  varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into s_area_atuacao (co_area_atuacao, ds_area_atuacao)
      values(
                 p_co_area_atuacao,
                 trim(upper(p_ds_area_atuacao))
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update s_area_atuacao set
         co_area_atuacao      = p_co_area_atuacao,
         ds_area_atuacao      = trim(upper(p_ds_area_atuacao))
      where co_area_atuacao   = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete s_area_atuacao where co_area_atuacao = p_chave;
   End If;
end SP_PutSAreaAtuacao;
/

