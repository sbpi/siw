create or replace procedure SP_GetGpPensionista
   (p_chave        in number,
    p_cliente      in number,
    p_colaborador  in number,
    p_result     out sys_refcursor) is
begin
      -- Recupera os dados do desempenho do colaborador
      open p_result for 
      select a.sq_pessoa as chave, a.cliente, a.colaborador, a.tipo,
      -- Indica o tipo de pensão a que o valor se refere: 
      -- 1 - valor fixo; 
      -- 2 - percentual do salário bruto; 
      -- 3 - percentual do salário líquido; 
      -- 4 - número de salários mínimos.
      case when a.tipo = 1 then 'Valor fixo'
           when a.tipo = 2 then 'Percentual do salário bruto'
           when a.tipo = 3 then 'Percentual do salário líquido'
           when a.tipo = 4 then 'Número de salários mínimos'             
             end as tipo_pensao,
             a.valor,     a.inicio,  a.fim,         a.observacao,
             b.nome,      b.nome_resumido, c.cpf, c.rg_numero, c.sexo,
             c.nascimento
        from gp_pensao a 
       inner join co_pessoa b on (a.sq_pessoa = b.sq_pessoa)
       inner join co_pessoa_fisica c on (b.sq_pessoa = c.sq_pessoa)
       where a.cliente = p_cliente
         and colaborador = p_colaborador
         and (p_chave    is null or (p_chave is not null and a.sq_pessoa = p_chave));

end SP_GetGpPensionista; 
/
