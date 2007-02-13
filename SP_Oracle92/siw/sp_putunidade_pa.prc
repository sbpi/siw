create or replace procedure sp_putUnidade_PA
   (p_operacao           in  varchar2,
    p_cliente            in  number   default null,
    p_chave              in  number   default null,
    p_registra_documento in  varchar2 default null,
    p_autua_processo     in  varchar2 default null,
    p_prefixo            in  varchar2 default null,
    p_nr_documento       in  number   default null,
    p_nr_tramite         in  number   default null,
    p_nr_transferencia   in  number   default null,
    p_nr_eliminacao      in  number   default null,
    p_arquivo_setorial   in  varchar2 default null,
    p_ativo              in  varchar2 default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into pa_unidade (sq_unidade, cliente, registra_documento, autua_processo, prefixo,
                              numero_documento, numero_tramite, numero_transferencia, numero_eliminacao,
                              arquivo_setorial, ativo) 
      values (p_chave, p_cliente, p_registra_documento, p_autua_processo, p_prefixo, 
              p_nr_documento, p_nr_tramite, p_nr_transferencia, p_nr_eliminacao,
              p_arquivo_setorial, p_ativo);
   Elsif p_operacao = 'A' Then
      update pa_unidade
         set registra_documento   = p_registra_documento,
             autua_processo       = p_autua_processo,
             prefixo              = p_prefixo,
             numero_documento     = p_nr_documento,
             numero_tramite       = p_nr_tramite,
             numero_transferencia = p_nr_transferencia,
             numero_eliminacao    = p_nr_eliminacao,
             arquivo_setorial     = p_arquivo_setorial,
             ativo                = p_ativo
       where sq_unidade = p_chave;
             
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete pa_unidade where sq_unidade = p_chave;
   End If;
end sp_putUnidade_PA;
/
