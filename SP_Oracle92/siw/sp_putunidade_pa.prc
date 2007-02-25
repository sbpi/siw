create or replace procedure sp_putUnidade_PA
   (p_operacao           in  varchar2,
    p_cliente            in  number   default null,
    p_chave              in  number   default null,
    p_unidade_pai        in  number   default null,
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
   w_nr_documento number(10)  := p_nr_documento;
   w_prefixo      varchar2(5) := p_prefixo;
begin
   -- Evita gravar nulo no campo prefixo
   If p_operacao = 'I' or p_operacao = 'A' Then
      if p_unidade_pai is not null then
        select prefixo into w_prefixo from pa_unidade where sq_unidade = p_unidade_pai;
      end if;

      -- Evita gravar nulo no campo numero_documento
      if coalesce(w_nr_documento,0) = 0 then w_nr_documento := 0; end if;
   End If;
   
   If p_operacao = 'I' Then
      -- Insere registro
      insert into pa_unidade 
         (sq_unidade,       cliente,        sq_unidade_pai,       registra_documento,       autua_processo,       prefixo,
          numero_documento, numero_tramite, numero_transferencia, numero_eliminacao,        arquivo_setorial,     ativo) 
      values 
         (p_chave,          p_cliente,      p_unidade_pai,        p_registra_documento,     p_autua_processo,     w_prefixo, 
          w_nr_documento,   p_nr_tramite,   p_nr_transferencia,   p_nr_eliminacao,          p_arquivo_setorial,   p_ativo);
   Elsif p_operacao = 'A' Then
      -- Verifica se há documentos vinculados à unidade. Se houver, recupera o maior sequencial utilizado
      select coalesce(max(a.numero_documento),0) into w_nr_documento from pa_documento a where a.unidade_autuacao = p_chave;
      
      -- Se o número informado pelo usuário for maior que o encontrado, prevalece o do usuário.
      -- Caso contrário, prevalece o maior número já vinculado a documentos autuados pela unidade.
      if coalesce(p_nr_documento,0) > w_nr_documento then w_nr_documento := p_nr_documento; end if;
      
      update pa_unidade set
         sq_unidade_pai       = p_unidade_pai,
         registra_documento   = p_registra_documento,
         autua_processo       = p_autua_processo,
         prefixo              = w_prefixo,
         numero_documento     = w_nr_documento,
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
