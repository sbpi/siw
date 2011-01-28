create or replace FUNCTION SP_PutCiaTrans
   (p_operacao                  varchar,
    p_cliente                   numeric,
    p_chave                     numeric,
    p_nome                      varchar,
    p_sigla                     varchar,    
    p_aereo                     varchar,
    p_rodoviario                varchar,
    p_aquaviario                varchar,
    p_padrao                    varchar,
    p_ativo                     varchar 
   ) RETURNS VOID AS $$ 
DECLARE
   w_sigla varchar(20) := coalesce(acentos(p_sigla),'NI');   
BEGIN

   If p_operacao = 'I' Then
      -- Insere registro
      insert into pd_cia_transporte (
              sq_cia_transporte,         cliente,   nome,        sigla,         aereo,   
              rodoviario,     aquaviario,                padrao,    ativo)
      (select sq_cia_transporte.nextval, p_cliente, trim(p_nome),w_sigla, p_aereo, 
              p_rodoviario,   p_aquaviario,              p_padrao,  p_ativo);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update pd_cia_transporte set
         nome                 = trim(p_nome),
         sigla                = w_sigla,
         aereo                = trim(p_aereo),
         rodoviario           = p_rodoviario,
         aquaviario           = p_aquaviario,
         padrao               = p_padrao,
         ativo                = p_ativo
      where sq_cia_transporte = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      DELETE FROM pd_cia_transporte where sq_cia_transporte = p_chave;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;