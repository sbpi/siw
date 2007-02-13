create or replace procedure SP_PutPAParametro
   (p_cliente                  in  number,
    p_despacho_arqcentral      in  number,
    p_despacho_emprestimo      in  number,
    p_despacho_devolucao       in  number,
    p_arquivo_central          in  number,
    p_limite_interessados      in  number,
    p_ano_corrente             in  number
   ) is
   
   p_operacao varchar2(1);
   w_existe   number(18);
   
begin
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
         (cliente, despacho_arqcentral, despacho_emprestimo, despacho_devolucao, arquivo_central,
          limite_interessados, ano_corrente)
      values
         (p_cliente, p_despacho_arqcentral,  p_despacho_emprestimo, p_despacho_devolucao, p_arquivo_central, 
          p_limite_interessados, p_ano_corrente);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update pa_parametro
         set despacho_arqcentral   = p_despacho_arqcentral,
             despacho_emprestimo   = p_despacho_emprestimo,
             despacho_devolucao    = p_despacho_devolucao,
             arquivo_central       = p_arquivo_central,
             limite_interessados   = p_limite_interessados,
             ano_corrente          = p_ano_corrente
       where cliente = p_cliente;
   End If;
end  SP_PutPAParametro;
/
