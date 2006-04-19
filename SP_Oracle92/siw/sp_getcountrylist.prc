create or replace procedure SP_GetCountryList
   (p_restricao in  varchar2 default null,
    p_nome      in  varchar2 default null,
    p_ativo     in  varchar2 default null,
    p_sigla     in  varchar2 default null,
    p_result    out sys_refcursor) is
begin
   -- Recupera os paises existentes
   open p_result for 
      select sq_pais, nome, Nvl(sigla,'-') sigla, ddi,
             ativo, 
             case ativo when 'S' then 'Sim' else 'Não' end ativodesc, 
             padrao, 
             case padrao when 'S' then 'Sim' else 'Não' end padraodesc
        from co_pais
       where (p_restricao is null or (p_restricao = 'ATIVO'      and ativo = 'S')
                                  or (p_restricao = 'NOMEBRASIL' and nome = 'Brasil')
                                  or (p_restricao = 'NOMEFRANCA' and nome = 'França')
                                  or (p_restricao = 'BRASILFRANCA' and (nome = 'Brasil' or nome = 'França')))
         and (p_nome  is null     or (p_nome  is not null and (acentos(nome) like '%'||acentos(p_nome)||'%')))
         and (p_ativo is null     or (p_ativo is not null and ativo = p_ativo))
         and (p_sigla is null     or (p_sigla is not null and sigla = p_sigla));
end SP_GetCountryList;
/
