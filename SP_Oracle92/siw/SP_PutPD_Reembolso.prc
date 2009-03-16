create or replace procedure SP_PutPD_Reembolso
   (p_cliente             in number,
    p_chave               in number,
    p_reembolso           in varchar2  default null,
    p_deposito            in varchar2  default null,
    p_valor               in number    default null,
    p_observacao          in varchar2  default null,
    p_financeiro          in number    default null,
    p_rubrica             in number    default null,
    p_lancamento          in number    default null
   ) is

   w_financeiro number(18) := p_financeiro;
   w_existe     number(18);
   
begin
   -- Verifica se precisa gravar o tipo de vínculo financeiro
   If p_financeiro is null and p_lancamento is not null Then
      -- Verifica se há um vínculo único para as opções enviadas
      select count(*) into w_existe
        from pd_vinculo_financeiro
       where sq_projeto_rubrica = p_rubrica
         and sq_tipo_lancamento = p_lancamento
         and bilhete            = 'S';

      -- Prepara variável para gravação se encontrou um, e apenas um registro.
      If w_existe = 1 Then
         select sq_pdvinculo_financeiro into w_financeiro
           from pd_vinculo_financeiro
          where sq_projeto_rubrica = p_rubrica
            and sq_tipo_lancamento = p_lancamento
            and bilhete            = 'S';
      End If;
   End If;
   
   -- Atualiza os dados da viagem
   update pd_missao 
      set reembolso              = coalesce(p_reembolso,'N'),
          reembolso_valor        = coalesce(p_valor,0),
          reembolso_observacao   = p_observacao,
          sq_pdvinculo_reembolso = coalesce(w_financeiro,sq_pdvinculo_reembolso),
          deposito_identificado  = p_deposito
    where sq_siw_solicitacao = p_chave;
end SP_PutPD_Reembolso;
/
