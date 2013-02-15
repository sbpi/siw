create or replace procedure SP_PutPAParametro
   (p_cliente                  in  number,
    p_despacho_arqcentral      in  number,
    p_despacho_desarqcentral   in  number,
    p_despacho_emprestimo      in  number,
    p_despacho_devolucao       in  number,
    p_despacho_autuar          in  number,
    p_despacho_arqsetorial     in  number,
    p_despacho_anexar          in  number,
    p_despacho_apensar         in  number,
    p_despacho_eliminar        in  number,
    p_despacho_desmembrar      in  number,
    p_arquivo_central          in  number,
    p_limite_interessados      in  number,
    p_ano_corrente             in  number,
    p_envio_externo            in varchar2,
    p_emite_guia_remessa       in varchar2
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
         (cliente,           despacho_arqcentral,    despacho_desarqcentral,   despacho_emprestimo,    despacho_devolucao,    despacho_autuar,   despacho_arqsetorial,
          despacho_anexar,   despacho_apensar,       despacho_eliminar,        arquivo_central,        limite_interessados,   ano_corrente,      despacho_desmembrar,
         	envio_externo,     emite_guia_remessa
         )
      values
         (p_cliente,         p_despacho_arqcentral,  p_despacho_desarqcentral, p_despacho_emprestimo,  p_despacho_devolucao,  p_despacho_autuar, p_despacho_arqsetorial,
          p_despacho_anexar, p_despacho_apensar,     p_despacho_eliminar,      p_arquivo_central,      p_limite_interessados, p_ano_corrente,    p_despacho_desmembrar,
          p_envio_externo,   p_emite_guia_remessa
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update pa_parametro
         set despacho_arqcentral    = p_despacho_arqcentral,
             despacho_desarqcentral = p_despacho_desarqcentral,
             despacho_emprestimo    = p_despacho_emprestimo,
             despacho_devolucao     = p_despacho_devolucao,
             despacho_autuar        = p_despacho_autuar,
             despacho_arqsetorial   = p_despacho_arqsetorial,
             despacho_anexar        = p_despacho_anexar,
             despacho_apensar       = p_despacho_apensar,
             despacho_eliminar      = p_despacho_eliminar,
             despacho_desmembrar    = p_despacho_desmembrar,
             arquivo_central        = p_arquivo_central,
             limite_interessados    = p_limite_interessados,
             ano_corrente           = p_ano_corrente,
             envio_externo          = p_envio_externo,
             emite_guia_remessa     = p_emite_guia_remessa
       where cliente = p_cliente;
   End If;
end  SP_PutPAParametro;
/
