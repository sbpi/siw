create or replace FUNCTION SP_PutEsquemaInsert
   (p_operacao           varchar,
    p_chave              numeric,
    p_sq_esquema_tabela  numeric,
    p_sq_coluna          numeric,
    p_ordem              numeric,
    p_valor              varchar,
    p_registro           numeric   
   ) RETURNS VOID AS $$
DECLARE
   w_registro numeric(4);
   w_existe   numeric(4);
BEGIN
   If p_operacao = 'I' Then
      If p_ordem = 1 Then
         select nvl(max(registro),0)+1 into w_registro
           from dc_esquema_insert 
          where sq_esquema_tabela = p_sq_esquema_tabela 
            and ordem = 1;
      Else 
         select distinct max(registro) into w_registro 
           from dc_esquema_insert 
          where sq_esquema_tabela = p_sq_esquema_tabela;
      End If;
      -- Insere registro
      insert into dc_esquema_insert (sq_esquema_insert, sq_esquema_tabela, registro, sq_coluna, 
                                     ordem, valor)
         (select nextVal('sq_esquema_insert'),
                 p_sq_esquema_tabela,
                 w_registro,
                 p_sq_coluna,
                 p_ordem,
                 p_valor
           
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update dc_esquema_insert set
         ordem  = p_ordem,
         valor  = p_valor
      where sq_esquema_insert = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM dc_esquema_insert where sq_esquema_tabela = p_sq_esquema_tabela and registro = p_registro;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;