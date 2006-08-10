create or replace procedure SP_PutPdDiaria
   (p_operacao             in  varchar2,
    p_chave                in  number,
    p_sq_diaria            in  number,
    p_sq_cidade            in  number,
    p_quantidade           in  number,
    p_valor                in  number
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere os registros em PD_DIARIA
      insert into pd_diaria
             (sq_diaria,         sq_siw_solicitacao, sq_cidade,   quantidade,   valor)
      (select sq_diaria.nextval, p_chave,            p_sq_cidade, p_quantidade, p_valor 
         from dual);
   Elsif p_operacao = 'A' Then
      -- Atualiza os dados PD_DIARIA
      Update pd_diaria
         set quantidade           = p_quantidade,
             valor                = p_valor
       where sq_siw_solicitacao = p_chave
         and sq_diaria          = p_sq_diaria;
   End If;
end SP_PutPdDiaria;
/
