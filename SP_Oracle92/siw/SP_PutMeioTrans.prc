create or replace procedure SP_PutMeioTrans
   (p_operacao                 in  varchar2,
    p_cliente                  in  number   default null,
    p_chave                    in  number   default null,
    p_nome                     in  varchar2 default null,
    p_aereo                    in  varchar2 default null,
    p_rodoviario               in  varchar2 default null,
    p_ferroviario              in  varchar2 default null,
    p_aquaviario               in  varchar2 default null,
    p_ativo                    in  varchar2 default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into pd_meio_transporte (
                  sq_meio_transporte, cliente, nome, aereo, rodoviario, ferroviario,
                  aquaviario, ativo)
      (select sq_meio_transporte.nextval, p_cliente, trim(p_nome), p_aereo, p_rodoviario,
              p_ferroviario, p_aquaviario, p_ativo from dual);
   Elsif p_operacao = 'A' Then
      --Altera registro
      update pd_meio_transporte set
         nome                   = trim(p_nome),
         aereo                  = trim(p_aereo),
         rodoviario             = trim(p_rodoviario),
         ferroviario            = trim(p_ferroviario),
         aquaviario             = trim(p_aquaviario),
         ativo                  = p_ativo
       where sq_meio_transporte = p_chave;
   Elsif p_operacao = 'E' Then
      --Exclui registro
      delete pd_meio_transporte where sq_meio_transporte = p_chave;
   End If;
end SP_PutMeioTrans;
/
