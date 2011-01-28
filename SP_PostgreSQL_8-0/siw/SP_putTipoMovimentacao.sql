create or replace FUNCTION SP_putTipoMovimentacao
   (p_operacao                  varchar,
    p_cliente                   numeric,
    p_chave                     numeric,
    p_nome                      varchar,
    p_entrada                   varchar,    
    p_saida                     varchar,    
    p_orcamentario              varchar,    
    p_consumo                   varchar,    
    p_permanente                varchar,    
    p_inativa_bem               varchar,    
    p_ativo                     varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro
      insert into mt_tipo_movimentacao (sq_tipo_movimentacao,          cliente,         nome,       entrada, 
                                        saida,     orcamentario,   consumo,   permanente,   inativa_bem, ativo)
      (select                           sq_tipo_movimentacao.nextval, p_cliente,      p_nome,     p_entrada, 
                                        p_saida, p_orcamentario, p_consumo, p_permanente, p_inativa_bem, p_ativo from dual);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update mt_tipo_movimentacao set
         nome                    = p_nome,
         entrada                 = p_entrada,
         saida                   = p_saida,
         orcamentario            = p_orcamentario,
         consumo                 = p_consumo,
         permanente              = p_permanente,
         inativa_bem             = p_inativa_bem,
         ativo                   = p_ativo
      where sq_tipo_movimentacao = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM mt_tipo_movimentacao where sq_tipo_movimentacao = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;