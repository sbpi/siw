create or replace FUNCTION SP_PutAlmoxarifado
   (p_operacao                  varchar,
    p_cliente                   numeric,
    p_localizacao               numeric,
    p_chave                     numeric,
    p_nome                      varchar,
    p_ativo                     varchar 
   ) RETURNS VOID AS $$
DECLARE
BEGIN
   If p_operacao = 'I' Then
      -- Insere registro      
         insert into mt_almoxarifado (sq_almoxarifado,         cliente,   sq_localizacao,      nome,         ativo            )
         (select                      sq_almoxarifado.nextval, p_cliente, p_localizacao, trim(p_nome), p_ativo from dual);      
      -- Insere Registro na tabela de locais
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update mt_almoxarifado set
         sq_localizacao         = p_localizacao, 
         nome                   = trim(p_nome),
         ativo                  = p_ativo
      where sq_almoxarifado = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM mt_almoxarifado where sq_almoxarifado = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;