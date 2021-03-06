create or replace procedure SP_putTipoMovimentacao
   (p_operacao                 in  varchar2,
    p_cliente                  in  number,
    p_chave                    in  number   default null,
    p_nome                     in  varchar2 default null,
    p_entrada                  in  varchar2 default null,    
    p_saida                    in  varchar2 default null,    
    p_orcamentario             in  varchar2 default null,    
    p_consumo                  in  varchar2 default null,    
    p_permanente               in  varchar2 default null,    
    p_inativa_bem              in  varchar2 default null,    
    p_ativo                    in  varchar2 default null
   ) is
begin
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
      delete mt_tipo_movimentacao where sq_tipo_movimentacao = p_chave;
   End If;
end SP_putTipoMovimentacao;
/
