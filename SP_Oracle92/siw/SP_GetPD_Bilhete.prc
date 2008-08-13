create or replace procedure SP_GetPD_Bilhete
   (p_chave     in number   default null,
    p_chave_aux in number   default null,
    p_inicio    in date     default null,
    p_fim       in date     default null,
    p_numero    in varchar2 default null,
    p_cia_trans in number   default null,
    p_restricao in varchar2,
    p_result    out sys_refcursor) is
begin
   If p_restricao is null Then
      -- Recupera os bilhetes ligados a viagens
      open p_result for
         select a.sq_bilhete as chave, a.sq_siw_solicitacao, a.sq_cia_transporte, a.data, a.numero, a.trecho, a.valor_bilhete, a.valor_pta, 
                a.valor_taxa_embarque, a.rloc, a.classe,
                f.nome as nm_cia_transporte
           from pd_bilhete                     a
                inner   join pd_cia_transporte f on (a.sq_cia_transporte  = f.sq_cia_transporte)
          where a.sq_siw_solicitacao = p_chave
            and (p_chave_aux         is null or (p_chave_aux is not null and a.sq_bilhete = p_chave_aux));   
   End If;         
End SP_GetPD_Bilhete;
/
