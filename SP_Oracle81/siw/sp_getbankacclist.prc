create or replace procedure SP_GetBankAccList
   (p_cliente   in number,
    p_chave     in number   default null,
    p_restricao in varchar2 default null,
    p_result    out siw.sys_refcursor) is
begin
   If p_restricao is null Then
      -- Recupera as contas bancárias do cliente
      open p_result for
         select a.sq_pessoa, b.sq_pessoa_conta,
                d.sq_banco, d.codigo||' - '||d.nome banco,
                e.sq_agencia, e.codigo||' - '||e.nome agencia,
                b.operacao, b.numero, b.ativo, b.padrao,
                decode(b.tipo_conta,'1','Corrente','Poupança') tipo_conta
           from co_pessoa a,
                co_pessoa_conta b,
                   co_agencia e,
                   co_banco   d
          where (b.sq_agencia = e.sq_agencia (+))
            and (e.sq_banco = d.sq_banco (+))
            and a.sq_pessoa        = b.sq_pessoa
            and a.sq_pessoa        = p_cliente
         order by d.nome, e.codigo;
   End If;
end SP_GetBankAccList;
/

