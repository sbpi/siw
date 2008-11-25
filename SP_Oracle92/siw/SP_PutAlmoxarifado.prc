create or replace procedure SP_PutAlmoxarifado
   (p_operacao                 in  varchar2,
    p_cliente                  in  number,
    p_localizacao              in  number   default null,
    p_chave                    in  number   default null,
    p_nome                     in  varchar2 default null,
    p_ativo                    in  varchar2 default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro      
         insert into mt_almoxarifado (sq_almoxarifado,         cliente,   sq_localizacao,      nome,         ativo            )
         (select                      sq_almoxarifado.nextval, p_cliente, p_localizacao, trim(p_nome), p_ativo from dual);      
      -- Insere Registro na tabela de locais
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update mt_almoxarifado set
         sq_localizacao         = p_localizacao, 
         nome                   = trim(p_nome),
         ativo                  = p_ativo
      where sq_almoxarifado = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete mt_almoxarifado where sq_almoxarifado = p_chave;
   End If;
end SP_PutAlmoxarifado;
/
