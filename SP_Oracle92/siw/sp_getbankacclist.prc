create or replace procedure SP_GetBankAccList
   (p_cliente   in number,
    p_chave     in number   default null,
    p_restricao in varchar2 default null,
    p_result    out sys_refcursor) is
begin
   If p_restricao is null Then
      -- Recupera as contas banc�rias do cliente
      open p_result for 
         select a.sq_pessoa,             b.sq_pessoa_conta,                  b.saldo_inicial,   
                d.sq_banco,              d.codigo||' - '||d.nome as banco,   d.codigo cd_banco,
                e.sq_agencia,            e.codigo||' - '||e.nome as agencia, e.codigo  cd_agencia,
                b.operacao,              b.numero,                           b.ativo,                  b.padrao, 
                b.devolucao_valor,       b.sq_pais_estrang,                  b.aba_code,               b.swift_code,
                b.endereco_estrang,      b.banco_estrang,                    b.agencia_estrang,        b.cidade_estrang,
                b.informacoes,           case b.tipo_conta when '1' then 'Corrente' else 'Poupan�a' end as tipo_conta,
                b.codigo_externo,
                c.sq_moeda,              c.codigo cd_moeda,                  c.sigla sg_moeda,         c.simbolo sb_moeda,
                c.ativo at_moeda
           from co_pessoa                      a
                inner     join co_pessoa_conta b on (a.sq_pessoa  = b.sq_pessoa)
                  inner   join co_agencia      e on (b.sq_agencia = e.sq_agencia)
                    inner join co_banco        d on (e.sq_banco   = d.sq_banco)
                  left    join co_moeda        c on (b.sq_moeda   = c.sq_moeda)
          where a.sq_pessoa        = p_cliente
         order by d.nome, e.codigo;
   ElsIf p_restricao = 'CONTASBANCARIAS' Then
      -- Recupera as contas banc�rias do cliente
      open p_result for 
         select a.sq_pessoa,             b.sq_pessoa_conta,                  b.saldo_inicial,
                d.sq_banco,              d.codigo||' - '||d.nome as banco, 
                e.sq_agencia,            e.codigo||' - '||e.nome as agencia,  
                b.operacao,              b.numero,                           b.ativo,                  b.padrao,
                b.devolucao_valor,       b.sq_pais_estrang,                  b.aba_code,               b.swift_code,
                b.endereco_estrang,      b.banco_estrang,                    b.agencia_estrang,        b.cidade_estrang,
                b.informacoes,           case b.tipo_conta when '1' then 'Corrente' else 'Poupan�a' end as tipo_conta,
                b.codigo_externo,
                c.sq_moeda,              c.codigo cd_moeda,                  c.sigla sg_moeda,         c.simbolo sb_moeda,
                c.ativo at_moeda
           from co_pessoa                      a
                inner     join co_pessoa_conta b on (a.sq_pessoa  = b.sq_pessoa)
                  inner   join co_agencia      e on (b.sq_agencia = e.sq_agencia)
                    inner join co_banco        d on (e.sq_banco   = d.sq_banco)
                  left    join co_moeda        c on (b.sq_moeda   = c.sq_moeda)
          where a.sq_pessoa        = p_cliente
            and b.padrao           = 'S'
            and (p_chave is null or (p_chave is not null and b.sq_pessoa_conta <> p_chave))
         order by d.nome, e.codigo;   
   ElsIf p_restricao = 'FINANCEIRO' Then
      -- Recupera as contas banc�rias do cliente
      open p_result for 
         select a.sq_pessoa,             b.sq_pessoa_conta,                  b.saldo_inicial,
                d.sq_banco,              d.nome as nm_banco,                 d.codigo||' - '||d.nome as banco,
                d.codigo as cd_banco,
                e.sq_agencia,            e.codigo||' - '||e.nome as agencia, e.codigo as cd_agencia,
                b.operacao,              b.numero,                           b.ativo, b.padrao,        b.devolucao_valor,
                b.sq_pais_estrang,       b.aba_code,                         b.swift_code,             b.endereco_estrang,
                b.banco_estrang,         b.agencia_estrang,                  b.cidade_estrang,         b.informacoes,
                case b.tipo_conta when '1' then 'Corrente' else 'Poupan�a' end as tipo_conta,
                b.codigo_externo,
                c.sq_moeda,              c.codigo cd_moeda,                  c.sigla sg_moeda,         c.simbolo sb_moeda,
                c.ativo at_moeda
           from co_pessoa                      a
                inner     join co_pessoa_conta b on (a.sq_pessoa  = b.sq_pessoa)
                  inner   join co_agencia      e on (b.sq_agencia = e.sq_agencia)
                    inner join co_banco        d on (e.sq_banco   = d.sq_banco)
                  left    join co_moeda        c on (b.sq_moeda   = c.sq_moeda)
          where a.sq_pessoa        = p_cliente
            and b.ativo            = 'S'
            and (p_chave is null or (p_chave is not null and b.sq_pessoa_conta = p_chave))
         order by d.nome, e.codigo;   
   ElsIf p_restricao = 'CONTADEV' Then
      -- Recupera as contas banc�rias do cliente que aceitam devolu��o de valores
      open p_result for 
         select a.sq_pessoa,             b.sq_pessoa_conta,                  b.saldo_inicial,
                d.sq_banco,              d.codigo||' - '||d.nome as banco,   d.codigo as cd_banco,
                e.sq_agencia,            e.codigo||' - '||e.nome as agencia, e.codigo as cd_agencia,
                b.operacao,              b.numero,                           b.ativo,                  b.padrao,
                b.devolucao_valor,       b.sq_pais_estrang,                  b.aba_code,               b.swift_code,
                b.endereco_estrang,      b.banco_estrang,                    b.agencia_estrang,        b.cidade_estrang,
                b.informacoes,           case b.tipo_conta when '1' then 'Corrente' else 'Poupan�a' end as tipo_conta,
                b.codigo_externo,
                c.sq_moeda,              c.codigo cd_moeda,                  c.sigla sg_moeda,         c.simbolo sb_moeda,
                c.ativo at_moeda
           from co_pessoa                      a
                inner     join co_pessoa_conta b on (a.sq_pessoa  = b.sq_pessoa)
                  inner   join co_agencia      e on (b.sq_agencia = e.sq_agencia)
                    inner join co_banco        d on (e.sq_banco   = d.sq_banco)
                  left    join co_moeda        c on (b.sq_moeda   = c.sq_moeda)
          where a.sq_pessoa        = p_cliente
            and b.devolucao_valor  = 'S'
            and b.ativo            = 'S'
         order by d.nome, e.codigo;   
   End If;
end SP_GetBankAccList;
/
