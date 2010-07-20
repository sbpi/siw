create or replace procedure SP_VerificaSenha
   (p_cliente  in number,
    p_username in varchar2,
    p_senha    in varchar2,
    p_result   out sys_refcursor
   ) is
   w_reg number(4);
begin
   open p_result for 
       select ativo 
         from sg_autenticacao a, co_pessoa b 
        where a.sq_pessoa     = b.sq_pessoa 
          and b.sq_pessoa_pai = p_cliente
          and upper(username) = upper(p_username)
          and upper(senha)    = criptografia(upper(p_senha));
          
   -- Verifica se o ajuste da pesquisa de preço foi executado na data atual
   select count(a.cliente) into w_reg
     from cl_parametro a
    where a.cliente                     = p_cliente
      and a.data_ajuste_pesquisa        is not null
      and trunc(a.data_ajuste_pesquisa) = trunc(sysdate);
   
   -- Se o ajuste não foi executado.
   If w_reg = 0 Then
      -- Atualiza as pesquisas de preço de todos os itens do catálogo
      sp_ajustapesquisamaterial(p_cliente, null, 'TODOS');
      -- Atualiza pagamentos de diárias pendentes de prestação de contas
      sp_ajustaFasePagamento(p_cliente, null, 'TODOS');
      -- Registra que o ajuste já foi executado na data atual
      update cl_parametro set data_ajuste_pesquisa = sysdate where cliente = p_cliente;
   End If;
end SP_VerificaSenha;
/
