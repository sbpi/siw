create or replace procedure SP_PutAlmoxarifadoLocal
   (p_operacao                 in  varchar2,
    p_chave                    in  number   default null,
    p_nome                     in  varchar2 default null,
    p_local                    in  number   default null,
    p_local_pai                in  number default null,
    p_ativo                    in  varchar2 default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro      
         insert into mt_almoxarifado_local(sq_almoxarifado_local,         sq_almoxarifado, sq_local_pai, nome,         ativo)
         (select                           sq_almoxarifado_local.nextval, p_chave,          p_local_pai, trim(p_nome), trim(p_ativo) from dual);
      -- Insere Registro na tabela de locais
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update mt_almoxarifado_local set
         nome                     = trim(p_nome),
         ativo                    = p_ativo
      where sq_almoxarifado_local = p_local;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete mt_almoxarifado_local where sq_almoxarifado_local = p_local;
   End If;
end SP_PutAlmoxarifadoLocal;
/
