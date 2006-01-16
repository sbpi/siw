create or replace procedure SP_GetAlberList
   (p_carteira         in varchar2 default null,
    p_nome             in varchar2 default null,
    p_sexo             in varchar2 default null,
    p_uf               in varchar2 default null,
    p_conhece_albergue in varchar2 default null,
    p_visitas          in number   default null,
    p_classificacao    in varchar2 default null,
    p_destino          in varchar2 default null,
    p_motivo_viagem    in varchar2 default null,
    p_forma_conhece    in varchar2 default null,
    p_result    out sys_refcursor) is
begin
    -- Recupera a lista de alberguistas
    open p_result for 
       select a.sq_alberguista, a.carteira, a.nome, a.nascimento, a.endereco, 
              a.bairro, a.cep, a.cidade, a.uf, a.ddd, a.fone, a.cpf, a.rg_numero, a.rg_emissor, 
              a.email, a.sexo, a.formacao, a.trabalha, a.email_trabalho, a.conhece_albergue, 
              a.visitas, a.classificacao, a.destino, a.destino_outros, a.motivo_viagem, 
              a.motivo_outros, a.forma_conhece, a.forma_outros, a.sq_cidade, a.carteira_emissao, 
              a.carteira_validade
         from alberguista a
        where (p_carteira         is null or (p_carteira         is not null and a.carteira         = p_carteira))
          and (p_nome             is null or (p_nome             is not null and upper(a.nome)      like upper(p_nome) || '%'))
          and (p_sexo             is null or (p_sexo             is not null and a.sexo             = p_sexo))
          and (p_uf               is null or (p_uf               is not null and a.uf               = p_uf))
          and (p_conhece_albergue is null or (p_conhece_albergue is not null and a.conhece_albergue = p_conhece_albergue))
          and (p_visitas          is null or (p_visitas          is not null and a.visitas          = p_visitas))
          and (p_classificacao    is null or (p_classificacao    is not null and a.classificacao    = p_classificacao))
          and (p_destino          is null or (p_destino          is not null and a.destino          = p_destino))
          and (p_motivo_viagem    is null or (p_motivo_viagem    is not null and a.motivo_viagem    = p_motivo_viagem))
          and (p_forma_conhece    is null or (p_forma_conhece    is not null and a.forma_conhece    = p_forma_conhece))
       order by siw.acentos(a.nome);
end SP_GetAlberList;
/
