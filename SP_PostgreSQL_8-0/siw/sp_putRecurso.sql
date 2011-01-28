create or replace FUNCTION sp_putRecurso
   (p_operacao         varchar,
    p_cliente          numeric,
    p_usuario          numeric,
    p_chave            numeric,
    p_copia            numeric,
    p_tipo_recurso     numeric,
    p_unidade_medida   numeric,
    p_gestora          numeric,
    p_nome             varchar,
    p_codigo           varchar,
    p_descricao        varchar,
    p_finalidade       varchar,
    p_disponibilidade  numeric,
    p_tp_vinculo       varchar,
    p_ch_vinculo       numeric,
    p_ativo            varchar,
    p_chave_nova      numeric
   ) RETURNS VOID AS $$
DECLARE
   w_chave numeric(18) := p_chave;
BEGIN
   If p_operacao = 'I' or p_operacao = 'C' Then
      -- Gera a nova chave do registro, a partir da sequence
      select sq_recurso.nextval into w_chave;

      -- Insere registro
      insert into eo_recurso
        (sq_recurso,           cliente,         sq_tipo_recurso,           sq_unidade_medida,          unidade_gestora,          nome, 
         codigo,               descricao,       finalidade,                disponibilidade_tipo,       ativo)
      values
        (w_chave,              p_cliente,       p_tipo_recurso,            p_unidade_medida,           p_gestora,                p_nome, 
         p_codigo,             p_descricao,     p_finalidade,              p_disponibilidade,          p_ativo
        );
        
      -- Se for cópia, herda os cronogramas de disponibilidade e de indisponibilidade,
      -- bem como a vinculação com opções do menu
      If p_operacao = 'C' Then
         -- Copia períodos de disponibilidade e indisponibilidade apenas se tiver controle por período
         If p_disponibilidade <> 1 Then
            insert into eo_recurso_disponivel
              (sq_recurso_disponivel, sq_recurso, inicio, fim, valor, unidades, limite_diario, dia_util)
            (select sq_recurso_disponivel.nextval, w_chave, inicio, fim, valor, unidades, limite_diario, dia_util
               from eo_recurso_disponivel
              where sq_recurso = p_copia
            );
         
            insert into eo_recurso_indisponivel
              (sq_recurso_indisponivel, sq_recurso, inicio, fim, justificativa)
            (select sq_recurso_indisponivel.nextval, w_chave, inicio, fim, justificativa
               from eo_recurso_indisponivel
              where sq_recurso = p_copia
            );
         End If;
         
         insert into eo_recurso_menu (sq_recurso, sq_menu) (select w_chave, sq_menu from eo_recurso_menu where sq_recurso = p_copia);
      End If;
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update eo_recurso
         set sq_tipo_recurso    = p_tipo_recurso,
             sq_unidade_medida  = p_unidade_medida,
             unidade_gestora      = p_gestora,
             nome                 = p_nome,
             codigo               = p_codigo,
             descricao            = p_descricao,
             finalidade           = p_finalidade,
             disponibilidade_tipo = p_disponibilidade,
             ativo                = p_ativo
       where sq_recurso = p_chave;
   Elsif p_operacao = 'E' Then
      -- Remove o vínculo entre o recurso e o objeto vinculado
      If p_tp_vinculo is not null Then
         If    p_tp_vinculo = 'PESSOA'  Then update co_pessoa  set sq_recurso = null where sq_pessoa = p_ch_vinculo;
         Elsif p_tp_vinculo = 'VEÍCULO' Then update sr_veiculo set sq_recurso = null where sq_veiculo = p_ch_vinculo;
         End If;
      End If;

      -- Exclui o recurso e seu cronograma de disponibilidade
      DELETE FROM eo_recurso_menu         where sq_recurso = p_chave;
      DELETE FROM eo_recurso_disponivel   where sq_recurso = p_chave;
      DELETE FROM eo_recurso_indisponivel where sq_recurso = p_chave;
      DELETE FROM eo_recurso              where sq_recurso = p_chave;
   End If;
   
   -- Na inclusão e alteração, registra o vínculo entre o recurso e o objeto vinculado
   If p_tp_vinculo is not null and p_operacao in ('I','A','C') Then
      If    p_tp_vinculo = 'PESSOA'  Then update co_pessoa  set sq_recurso = w_chave where sq_pessoa = p_ch_vinculo;
      Elsif p_tp_vinculo = 'VEÍCULO' Then update sr_veiculo set sq_recurso = w_chave where sq_veiculo = p_ch_vinculo;
      End If;
   End If;

   -- Devolve a chave
   If p_chave is not null
      Then p_chave_nova := p_chave;
      Else p_chave_nova := w_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;