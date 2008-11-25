create or replace procedure SP_GetMtSituacao
   (p_cliente           in number,
    p_restricao         in varchar default null,
    p_chave             in number  default null,
    p_ativo             in varchar default null,
    p_nome              in varchar2 default null,
    p_sigla             in varchar2 default null,
    p_result            out sys_refcursor) is
begin
   -- Recupera os grupos de veículos
   if p_restricao is null then
     open p_result for 
        select a.sq_mtsituacao as chave, a.cliente, a.nome, a.sigla, a.entrada, a.saida, a.estorno, a.consumo, a.permanente, a.inativa_bem, a.situacao_fisica, a.ativo 
        from mt_situacao a where 
             a.cliente      = p_cliente
              and (p_chave is null   or (p_chave  is not null and a.sq_mtsituacao      = p_chave))
              and (p_ativo is null   or (p_ativo  is not null and a.ativo              = p_ativo))
              and (p_sigla is null   or (p_sigla  is not null and acentos(a.sigla)     = acentos(p_sigla)))
              and (p_nome is null    or (p_nome  is not null and acentos(a.nome)       = acentos(p_nome)));
   end if;         
end SP_GetMtSituacao;
/
