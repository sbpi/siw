create or replace procedure SP_GetBankAccList
   (p_cliente   in number,
    p_chave     in number   default null,
    p_restricao in varchar2 default null,
    p_result    out sys_refcursor) is
begin
   If p_restricao is null Then
      -- Recupera as contas bancárias do cliente
      open p_result for 
         select a.sq_pessoa, b.sq_pessoa_conta, 
                d.sq_banco, d.codigo||' - '||d.nome banco, 
                e.sq_agencia, e.codigo||' - '||e.nome agencia,  
                b.operacao, b.numero, b.ativo, b.padrao, 
                case b.tipo_conta when '1' then 'Corrente' else 'Poupança' end tipo_conta
           from co_pessoa a,  
                co_pessoa_conta b 
                  left outer join co_agencia e on (b.sq_agencia = e.sq_agencia)
                  left outer join co_banco   d on (e.sq_banco = d.sq_banco)
          where a.sq_pessoa        = b.sq_pessoa  
            and a.sq_pessoa        = p_cliente
         order by d.nome, e.codigo;
   ElsIf p_restricao = 'CONTASBANCARIAS' Then
      -- Recupera as contas bancárias do cliente
      open p_result for 
         select a.sq_pessoa, b.sq_pessoa_conta, 
                d.sq_banco, d.codigo||' - '||d.nome banco, 
                e.sq_agencia, e.codigo||' - '||e.nome agencia,  
                b.operacao, b.numero, b.ativo, b.padrao, 
                case b.tipo_conta when '1' then 'Corrente' else 'Poupança' end tipo_conta
           from co_pessoa a,  
                co_pessoa_conta b 
                  left outer join co_agencia e on (b.sq_agencia = e.sq_agencia)
                  left outer join co_banco   d on (e.sq_banco = d.sq_banco)
          where a.sq_pessoa        = b.sq_pessoa  
            and a.sq_pessoa        = p_cliente
            and b.padrao           = 'S'
            and (p_chave is null or (p_chave is not null and b.sq_pessoa_conta <> p_chave))
         order by d.nome, e.codigo;   
   End If;
end SP_GetBankAccList;
/
