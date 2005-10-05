create or replace procedure SP_GetCiaTrans
   (p_cliente         in  number,
    p_chave           in  number   default null,
    p_nome            in  varchar2 default null,
    p_aereo           in  varchar2 default null,
    p_rodoviario      in  varchar2 default null,
    p_aquaviario      in  varchar2 default null,
    p_padrao          in  varchar2 default null,
    p_ativo           in  varchar2 default null,
    p_chave_aux       in  number   default null,
    p_restricao       in  varchar2 default null,
    p_result          out siw.sys_refcursor) is
begin
   -- Recupera as companhias de viagem
   open p_result for
      select a.sq_cia_transporte chave, a.cliente, a.nome,
             decode(a.aereo,'S','Sim','Não') nm_aereo, a.aereo,
             decode(a.rodoviario,'S','Sim','Não') nm_rodoviario, a.rodoviario,
             decode(a.aquaviario,'S','Sim','Não') nm_aquaviario, a.aquaviario,
             decode(a.ativo,'S','Sim','Não') nm_ativo, a.ativo,
             decode(a.padrao,'S','Sim','Não') nm_padrao, a.padrao
        from pd_cia_transporte a
       where a.cliente = p_cliente
         and (p_chave      is null or (p_chave      is not null and a.sq_cia_transporte = p_chave))
         and (p_nome       is null or (p_nome       is not null and upper(acentos(a.nome)) like upper(acentos(p_nome))))
         and (p_aereo      is null or (p_aereo      is not null and a.aereo = p_aereo))
         and (p_rodoviario is null or (p_rodoviario is not null and a.rodoviario = p_rodoviario))
         and (p_aquaviario is null or (p_aquaviario is not null and a.aquaviario = p_aquaviario))
         and (p_padrao     is null or (p_padrao     is not null and a.padrao = p_padrao))
         and (p_ativo      is null or (p_ativo      is not null and a.ativo  = p_ativo))
         and (p_chave_aux  is null or (p_chave_aux  is not null and a.sq_cia_transporte <> p_chave_aux));
end SP_GetCiaTrans;
/
