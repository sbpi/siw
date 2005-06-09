create or replace procedure SP_PutSSerie
   (p_operacao               in  varchar2,
    p_chave                  in  varchar2 default null,
    p_sg_serie               in  varchar2 default null,
    p_co_tipo_curso          in  number,
    p_ds_serie               in  varchar2
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into s_serie (sg_serie, co_tipo_curso, descr_serie)
      values(
                 trim(upper(p_sg_serie)),
                 p_co_tipo_curso,
                 trim(upper(p_ds_serie))
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update s_serie set
         sg_serie       = trim(upper(p_sg_serie)),
         co_tipo_curso  = p_co_tipo_curso ,
         descr_serie    = trim(upper(p_ds_serie))
      where sg_serie    = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete s_serie where sg_serie = p_chave;
   End If;
end SP_PutSSerie;
/

