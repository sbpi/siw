create or replace procedure SP_PutMtSituacao
   (p_operacao                 in  varchar2,    
    p_cliente                  in  number   default null,    
    p_chave                    in  number   default null,    
    p_nome                     in  varchar2 default null,
    p_sigla                    in  varchar2 default null,
    p_entrada                  in  varchar2 default null,
    p_saida                    in  varchar2 default null,
    p_estorno                  in  varchar2 default null,
    p_consumo                  in  varchar2 default null,
    p_permanente               in  varchar2 default null,
    p_inativa_bem              in  varchar2 default null,
    p_situacao_fisica          in  varchar2 default null,
    p_ativo                    in  varchar2 default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into mt_situacao (
                  sq_mtsituacao, cliente, nome, sigla, entrada, saida, 
                  estorno, consumo, permanente, inativa_bem,situacao_fisica, ativo)
      (select sq_mtsituacao.nextval, p_cliente, trim(p_nome), trim(p_sigla), trim(p_entrada),
              trim(p_saida), trim(p_estorno), trim(p_consumo), trim(p_permanente), 
              trim(p_inativa_bem), trim(p_situacao_fisica), trim(p_ativo) from dual);
   Elsif p_operacao = 'A' Then
      --Altera registro
      update mt_situacao set
         nome                   = trim(p_nome),
         sigla                  = trim(p_sigla),
         entrada                = trim(p_entrada),
         saida                  = trim(p_saida),
         estorno                = trim(p_estorno),
         consumo                = trim(p_consumo),
         permanente             = trim(p_permanente),
         inativa_bem            = trim(p_inativa_bem),
         situacao_fisica        = trim(p_situacao_fisica),         
         ativo                  = trim(p_ativo)
       where sq_mtsituacao = p_chave;
   Elsif p_operacao = 'E' Then
      --Exclui registro
      delete mt_situacao where sq_mtsituacao = p_chave;
   End If;
end SP_PutMtSituacao;
/
