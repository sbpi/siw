create or replace procedure sp_PutProjeto_IS
   (p_operacao in  varchar2             ,
    P_CHAVE       NUMBER   default null,
    P_CLIENTE	    NUMBER   default null,
    P_CODIGO	    VARCHAR2 default null,
    P_NOME	      VARCHAR2 default null,
    P_RESPONSAVEL	VARCHAR2 default null,
    P_TELEFONE	  VARCHAR2 default null,
    P_EMAIL	      VARCHAR2 default null,
    P_ORDEM	      NUMBER   default null,
    P_ATIVO	      VARCHAR2 default null,
    P_PADRAO	    VARCHAR2 default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into is_projeto(sq_isprojeto, cliente, codigo, nome, responsavel, telefone, email, ordem, ativo, padrao)
      (select sq_isprojeto.nextval, p_cliente, p_codigo, p_nome, p_responsavel, p_telefone, p_email, p_ordem,  p_ativo,  p_padrao from dual);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update is_projeto
         set 
             CLIENTE      = P_CLIENTE,
             CODIGO       = P_CODIGO,
             NOME         = P_NOME,
             RESPONSAVEL  = P_RESPONSAVEL,
             TELEFONE     = P_TELEFONE,
             EMAIL        = P_EMAIL,
             ORDEM        = P_ORDEM,
             ATIVO        = P_ATIVO,
             PADRAO       = P_PADRAO
       where sq_isprojeto = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete is_projeto
       where sq_isprojeto = p_chave;
   End If;
end sp_PutProjeto_IS;
/

