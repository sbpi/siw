create or replace procedure Sp_GetUniMedida_IS
   (p_chave   in  number   default null,
    p_ativo   in  varchar2 default null,
    p_result  out sys_refcursor) is
begin
   -- Recupera todas as unidades de medidas
   open p_result for 
      select a.cd_unidade_medida chave, a.nome, a.ativo, a.tipo
        from is_sig_unidade_medida a
       where ((p_chave   is null) or (p_chave   is not null and a.cd_unidade_medida  = p_chave))
         and ((p_ativo   is null) or (p_ativo   is not null and a.ativo              = p_ativo));
end Sp_GetUniMedida_IS;
/

