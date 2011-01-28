create or replace FUNCTION sp_putIndicador
   (p_operacao           varchar,
    p_cliente            numeric,
    p_chave              numeric,
    p_nome               varchar,
    p_sigla              varchar,
    p_tipo_indicador     numeric,
    p_unidade_medida     numeric,
    p_descricao          varchar,
    p_forma_afericao     varchar,
    p_fonte_comprovacao  varchar,
    p_ciclo_afericao     varchar,
    p_vincula_meta       varchar,
    p_exibe_mesa         varchar,
    p_ativo              varchar 
   ) RETURNS VOID AS $$
DECLARE
   w_chave numeric(18);
BEGIN
   If p_operacao = 'I' Then
      -- Recupera a próxima chave do registro
      select sq_eoindicador.nextval into w_chave;
      
      -- Insere registro
      insert into eo_indicador
        (sq_eoindicador,   cliente,   sq_tipo_indicador, sq_unidade_medida, nome,   sigla,   descricao,   forma_afericao,   fonte_comprovacao,   
         ciclo_afericao,   ativo,     vincula_meta,      exibe_mesa)
      values
        (w_chave,          p_cliente, p_tipo_indicador,  p_unidade_medida,  p_nome, p_sigla, p_descricao, p_forma_afericao, p_fonte_comprovacao, 
         p_ciclo_afericao, p_ativo,   p_vincula_meta,    p_exibe_mesa);
   Elsif p_operacao = 'A' Then
      update eo_indicador
         set sq_tipo_indicador = p_tipo_indicador,
             sq_unidade_medida = p_unidade_medida,
             nome              = p_nome,
             sigla             = p_sigla,
             descricao         = p_descricao,
             forma_afericao    = p_forma_afericao,
             fonte_comprovacao = p_fonte_comprovacao,
             ciclo_afericao    = p_ciclo_afericao,
             vincula_meta      = p_vincula_meta,
             exibe_mesa        = p_exibe_mesa,
             ativo             = p_ativo
       where sq_eoindicador = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM eo_indicador where sq_eoindicador = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;