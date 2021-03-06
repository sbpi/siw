alter procedure dbo.SP_GetBankAccList
   (@p_cliente   int,
    @p_chave     int         = null,
    @p_restricao varchar(20) = null
	) as
begin
   If @p_restricao is null Begin
      -- Recupera as contas banc�rias do cliente
         select a.sq_pessoa, b.sq_pessoa_conta, 
                d.sq_banco, d.codigo + ' - ' + d.nome banco, 
                e.sq_agencia, e.codigo + ' - ' + e.nome agencia,  
                b.operacao, b.numero, b.ativo, b.padrao, 
                case b.tipo_conta when '1' then 'Corrente' else 'Poupan�a' end tipo_conta
           from co_pessoa a,  
                co_pessoa_conta b 
                  left outer join co_agencia e on (b.sq_agencia = e.sq_agencia)
                  left outer join co_banco   d on (e.sq_banco = d.sq_banco)
          where a.sq_pessoa        = b.sq_pessoa  
            and a.sq_pessoa        = @p_cliente
         order by d.nome, e.codigo;
   End Else If @p_restricao = 'CONTASBANCARIAS' Begin
      -- Recupera as contas banc�rias do cliente
         select a.sq_pessoa, b.sq_pessoa_conta, 
                d.sq_banco, d.codigo + ' - ' + d.nome banco, 
                e.sq_agencia, e.codigo + ' - ' + e.nome agencia,  
                b.operacao, b.numero, b.ativo, b.padrao, 
                case b.tipo_conta when '1' then 'Corrente' else 'Poupan�a' end tipo_conta
           from co_pessoa a,  
                co_pessoa_conta b 
                  left outer join co_agencia e on (b.sq_agencia = e.sq_agencia)
                  left outer join co_banco   d on (e.sq_banco = d.sq_banco)
          where a.sq_pessoa        = b.sq_pessoa  
            and a.sq_pessoa        = @p_cliente
            and b.padrao           = 'S'
            and (@p_chave is null or (@p_chave is not null and b.sq_pessoa_conta <> @p_chave))
         order by d.nome, e.codigo;   
   End
end
