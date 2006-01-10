create or replace procedure SP_GetAlberData
   (p_chave     in number,
    p_carteira  in varchar2,
    p_result    out siw.siw.sys_refcursor) is
begin
    -- Recupera a lista de alberguistas
    open p_result for 
       select a.sq_alberguista, a.carteira, a.nome, a.nascimento, a.endereco, 
              a.bairro, a.cep, a.cidade, a.uf, a.ddd, a.fone, a.cpf, a.rg_numero, 
              a.rg_emissor, a.email, a.sexo, a.formacao, a.trabalha, a.email_trabalho, 
              a.conhece_albergue, a.visitas, a.classificacao, a.destino, a.destino_outros, 
              a.motivo_viagem, a.motivo_outros, a.forma_conhece, a.forma_outros, 
              a.sq_cidade, a.carteira_emissao, a.carteira_validade
         from alberguista a
        where (p_chave    is null or (p_chave    is not null and a.sq_alberguista = p_chave))
          and (p_carteira is null or (p_carteira is not null and a.carteira       = p_carteira));
end SP_GetAlberData;
/
