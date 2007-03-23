create or replace procedure SP_PutCTEspecificacao
   (p_operacao                 in  varchar2,
    p_cliente                  in  number   default null,
    p_chave                    in  number   default null,
    p_chave_pai                in  number   default null,
    p_sq_cc                    in  number   default null,
    p_ano                      in  varchar2 default null,
    p_codigo                   in  varchar2 default null,
    p_nome                     in  varchar2 default null,
    p_valor                    in  number   default null,
    p_ultimo_nivel             in  varchar2 default null,
    p_ativo                    in  varchar2 default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into ct_especificacao_despesa
        (sq_especificacao_despesa, cliente, sq_cc, especificacao_pai, 
         ano, codigo, nome, valor, ultimo_nivel, ativo)
        (select sq_especificacao_despesa.nextval,  p_cliente,  p_sq_cc,  p_chave_pai, 
                 p_ano,  p_codigo,  p_nome,  p_valor,  p_ultimo_nivel,  p_ativo 
            from dual
         );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update ct_especificacao_despesa
         set sq_cc             = p_sq_cc,
             especificacao_pai = p_chave_pai,
             ano               = p_ano,
             codigo            = p_codigo,
             nome              = p_nome,
             valor             = p_valor,
             ultimo_nivel      = p_ultimo_nivel,
             ativo             = p_ativo
       where sq_especificacao_despesa = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete ct_especificacao_despesa where sq_especificacao_despesa = p_chave;
   Elsif p_operacao = 'T' Then
      -- Ativa o registro
      update ct_especificacao_despesa set ativo = 'S' where sq_especificacao_despesa = p_chave;
   Elsif p_operacao = 'D' Then
      -- Desativa o registro
      update ct_especificacao_despesa set ativo = 'N' where sq_especificacao_despesa = p_chave;
   End If;
end SP_PutCTEspecificacao;
/
