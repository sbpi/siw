create or replace procedure SP_PutCaixa
   (p_operacao                 in varchar2,
    p_cliente                  in number               ,
    p_chave                    in number   default null,  
    p_sq_unidade               in number   default null,
    p_sq_arquivo_local         in number   default null,
    p_assunto                  in varchar2 default null,
    p_descricao                in varchar2 default null,
    p_data_limite              in date     default null, 
    p_numero                   in number   default null,
    p_intermediario            in varchar2 default null,
    p_destinacao_final         in varchar2 default null, 
    p_arquivo_data             in date     default null, 
    p_arquivo_guia_numero      in number   default null, 
    p_arquivo_guia_ano         in number   default null,
    p_elimin_data              in date     default null,
    p_elimin_guia_numero       in number   default null,
    p_elimin_guia_ano          in number   default null
   ) is
   
   w_numero number(10);
begin
   If p_operacao = 'I' Then
      -- Recupera o próximo número da caixa para unidade informada
      select coalesce(numero_caixa,0)+1 into w_numero from pa_unidade where sq_unidade = p_sq_unidade;
      
      -- Atualiza o valor do número da caixa
      update pa_unidade set numero_caixa = w_numero where sq_unidade = p_sq_unidade;
      
      -- Insere registro
      insert into pa_caixa
        (sq_caixa,         cliente,   sq_unidade,    assunto,   descricao,   data_limite,   numero, 
         intermediario,    destinacao_final)
      values
        (sq_caixa.nextval, p_cliente, p_sq_unidade,  p_assunto, p_descricao, p_data_limite, w_numero, 
         p_intermediario,  p_destinacao_final);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update pa_caixa
         set cliente          = p_cliente,
             assunto          = p_assunto,
             descricao        = p_descricao,
             data_limite      = p_data_limite, 
             intermediario    = p_intermediario,
             destinacao_final = p_destinacao_final
       where sq_caixa = p_chave;

   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete pa_caixa where sq_caixa = p_chave;
   End If;
end SP_PutCaixa;
/
