create or replace procedure SP_PutSCargo
   (p_operacao                 in  varchar2,
    p_chave                    in  varchar2,
    p_co_cargo                 in  varchar2,
    p_ds_cargo                 in  varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into s_cargo (co_cargo, ds_cargo)
      values(
                 p_co_cargo,
                 trim(upper(p_ds_cargo))
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update s_cargo set
         co_cargo      = p_co_cargo,
         ds_cargo      = trim(upper(p_ds_cargo))
      where co_cargo   = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete s_cargo where co_cargo = p_chave;
   End If;
end SP_PutSCargo;
/

