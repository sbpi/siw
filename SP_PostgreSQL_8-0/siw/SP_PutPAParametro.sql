create or replace FUNCTION SP_PutPAParametro
   (p_cliente                   numeric,
    p_despacho_arqcentral       numeric,
    p_despacho_emprestimo       numeric,
    p_despacho_devolucao        numeric,
    p_despacho_autuar           numeric,
    p_despacho_arqsetorial      numeric,
    p_despacho_anexar           numeric,
    p_despacho_apensar          numeric,
    p_despacho_eliminar         numeric,
    p_despacho_desmembrar       numeric,
    p_arquivo_central           numeric,
    p_limite_interessados       numeric,
    p_ano_corrente              numeric
   ) RETURNS VOID AS $$
DECLARE
   
   p_operacao varchar(1);
   w_existe   numeric(18);
   
BEGIN
   -- Verifica se operação de inclusão ou alteração
   select count(*) into w_existe from pa_parametro a where a.cliente = p_cliente;
   If w_existe > 0 Then
      p_operacao := 'A';
   Else
      p_operacao := 'I';
   End If;
   
   -- Grava os parametros do módulo de protocolo e arquivos do cliente
   If p_operacao = 'I' Then
      -- Insere registro
      insert into pa_parametro
         (cliente,           despacho_arqcentral,    despacho_emprestimo,    despacho_devolucao,    despacho_autuar,       despacho_arqsetorial, 
          despacho_anexar,   despacho_apensar,       despacho_eliminar,      arquivo_central,       limite_interessados,   ano_corrente,
          despacho_desmembrar)
      values
         (p_cliente,         p_despacho_arqcentral,  p_despacho_emprestimo,  p_despacho_devolucao,  p_despacho_autuar,     p_despacho_arqsetorial,
          p_despacho_anexar, p_despacho_apensar,     p_despacho_eliminar,    p_arquivo_central,     p_limite_interessados, p_ano_corrente,
          p_despacho_desmembrar);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update pa_parametro
         set despacho_arqcentral   = p_despacho_arqcentral,
             despacho_emprestimo   = p_despacho_emprestimo,
             despacho_devolucao    = p_despacho_devolucao,
             despacho_autuar       = p_despacho_autuar,
             despacho_arqsetorial  = p_despacho_arqsetorial,
             despacho_anexar       = p_despacho_anexar,
             despacho_apensar      = p_despacho_apensar,
             despacho_eliminar     = p_despacho_eliminar,
             despacho_desmembrar   = p_despacho_desmembrar,
             arquivo_central       = p_arquivo_central,
             limite_interessados   = p_limite_interessados,
             ano_corrente          = p_ano_corrente
       where cliente = p_cliente;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;