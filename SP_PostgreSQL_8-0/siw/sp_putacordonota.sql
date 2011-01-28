create or replace FUNCTION SP_PutAcordoNota
   (p_operacao                  varchar,
    p_chave                     numeric,
    p_chave_aux                 numeric,
    p_sq_tipo_documento         numeric,
    p_sq_acordo_outra_parte     numeric,
    p_sq_acordo_aditivo         numeric,
    p_numero                    varchar,
    p_data                      date,
    p_valor                     numeric,
    p_sq_lcfonte_recurso        numeric,
    p_espec_despesa             numeric,
    p_observacao                varchar,
    p_abrange_inicial           varchar,
    p_abrange_acrescimo         varchar,
    p_abrange_reajuste          varchar,
    p_sq_acordo_parcela         numeric,
    p_chave_nova               numeric
   ) RETURNS VOID AS $$
DECLARE
   w_chave   numeric(18);
BEGIN
   If p_operacao = 'I' Then
      select sq_acordo_nota.nextval into w_chave;
      -- Insere registro
      insert into ac_acordo_nota
        (sq_acordo_nota, sq_siw_solicitacao, sq_tipo_documento, sq_acordo_outra_parte, 
         sq_acordo_aditivo, numero, data, valor, sq_lcfonte_recurso, sq_especificacao_despesa, observacao,
         abrange_inicial, abrange_acrescimo, abrange_reajuste
        )
        (select w_chave, p_chave_aux, p_sq_tipo_documento, p_sq_acordo_outra_parte, 
          p_sq_acordo_aditivo, p_numero, p_data, p_valor, p_sq_lcfonte_recurso, p_espec_despesa, p_observacao,
          p_abrange_inicial, p_abrange_acrescimo, p_abrange_reajuste
          
        );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update ac_acordo_nota
         set sq_tipo_documento      = p_sq_tipo_documento,
             sq_acordo_outra_parte   = p_sq_acordo_outra_parte,
             sq_acordo_aditivo       = p_sq_acordo_aditivo,
             numero                  = p_numero,
             data                    = p_data,
             valor                   = p_valor,
             sq_lcfonte_recurso      = p_sq_lcfonte_recurso,
             sq_especificacao_despesa = p_espec_despesa,
             observacao               = p_observacao,
             abrange_inicial          = p_abrange_inicial,
             abrange_acrescimo        = p_abrange_acrescimo,
             abrange_reajuste         = p_abrange_reajuste
       where sq_acordo_nota = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM ac_acordo_nota where sq_acordo_nota = p_chave;
   Elsif p_operacao = 'PARCELA' Then
      insert into ac_parcela_nota
         (sq_acordo_parcela, sq_acordo_nota)
      values
         (p_sq_acordo_parcela, p_chave);
   Elsif p_operacao = 'EXCLUIPARCELA' Then
      DELETE FROM ac_parcela_nota where sq_acordo_nota = p_chave;
   End If;

   -- Devolve a chave
   If p_chave is not null
      Then p_chave_nova := p_chave;
      Else p_chave_nova := w_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;