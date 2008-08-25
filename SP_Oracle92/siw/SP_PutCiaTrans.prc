create or replace procedure SP_PutCiaTrans
   (p_operacao                 in  varchar2,
    p_cliente                  in  number   default null,
    p_chave                    in  number   default null,
    p_nome                     in  varchar2 default null,
    p_sigla                    in  varchar2 default null,    
    p_aereo                    in  varchar2 default null,
    p_rodoviario               in  varchar2 default null,
    p_aquaviario               in  varchar2 default null,
    p_padrao                   in  varchar2 default null,
    p_ativo                    in  varchar2 default null
   ) is 
   w_sigla varchar2(20) := coalesce(acentos(p_sigla),'NI');   
begin

   If p_operacao = 'I' Then
      -- Insere registro
      insert into pd_cia_transporte (
              sq_cia_transporte,         cliente,   nome,        sigla,         aereo,   
              rodoviario,     aquaviario,                padrao,    ativo)
      (select sq_cia_transporte.nextval, p_cliente, trim(p_nome),w_sigla, p_aereo, 
              p_rodoviario,   p_aquaviario,              p_padrao,  p_ativo from dual);
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
      delete pd_cia_transporte where sq_cia_transporte = p_chave;
   End If;
end SP_PutCiaTrans;
/
