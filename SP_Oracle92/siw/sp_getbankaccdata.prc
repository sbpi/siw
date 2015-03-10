create or replace procedure SP_GetBankAccData
   (p_chave      in  number,
    p_result     out sys_refcursor
   ) is
begin
   -- Recupera os dados da conta bancária
   open p_result for 
      Select b.sq_banco,              b.codigo agencia,            a.numero,           a.operacao, 
             a.tipo_conta,            a.ativo,                     a.padrao,           a.devolucao_valor,
             a.saldo_inicial,         a.sq_pais_estrang,           a.aba_code,         a.swift_code,
             a.endereco_estrang,      a.banco_estrang,             a.agencia_estrang,  a.cidade_estrang,
             a.informacoes,
             c.sq_moeda,              c.codigo cd_moeda,           c.sigla sg_moeda,   c.simbolo sb_moeda,
             d.nome nm_banco,
             c.ativo at_moeda
      from co_pessoa_conta a
           inner   join co_agencia b on (a.sq_agencia        = b.sq_agencia)
             inner join co_banco   d on (b.sq_banco          = d.sq_banco)
           left  join co_moeda     c on (a.sq_moeda          = c.sq_moeda)
      where a.sq_pessoa_conta   = p_chave;
end SP_GetBankAccData;
/
