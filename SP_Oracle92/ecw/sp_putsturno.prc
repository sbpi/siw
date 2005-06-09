create or replace procedure SP_PutSTurno
   (p_operacao                 in  varchar2,
    p_chave                    in  char default null,
    p_co_turno                 in  char default null,
    p_ds_turno                 in  varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into s_turno (co_turno, ds_turno)
      values(
                 p_co_turno,
                 trim(upper(p_ds_turno))
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update s_turno set
         co_turno       = p_co_turno,
         ds_turno       = trim(upper(p_ds_turno))
      where co_turno    = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete s_turno where co_turno = p_chave;
   End If;
end SP_PutSTurno;
/

