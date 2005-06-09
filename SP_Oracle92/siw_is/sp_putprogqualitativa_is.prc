create or replace procedure SP_PutProgQualitativa_IS
   (p_chave                    in  number,
    p_descricao                in  varchar2 default null,
    p_justificativa            in  varchar2 default null,    
    p_potencialidades          in  varchar2 default null,
    p_problema                 in  varchar2 default null,
    p_objetivo                 in  varchar2 default null,
    p_publico_alvo             in  varchar2 default null,
    p_estrategia               in  varchar2 default null,
    p_sistematica              in  varchar2 default null,
    p_metodologia              in  varchar2 default null,
    p_restricao                in  varchar2 default null
   ) is
begin
   -- Altera os registro
   update siw.siw_solicitacao set
          descricao     = trim(p_descricao),
          justificativa = trim(p_justificativa)
    where sq_siw_solicitacao = p_chave;
    If p_restricao = 'ISPRPROQUA'  Then
       update is_programa set
              potencialidades       = trim(p_potencialidades),
              contribuicao_objetivo = trim(p_objetivo),
              diretriz              = trim(p_sistematica),
              estrategia_monit      = trim(p_estrategia),
              metodologia_aval      = trim(p_metodologia)
        where sq_siw_solicitacao = p_chave;
    ElsIf p_restricao = 'ISACPROQUA' Then
       update is_acao set
              problema     = trim(p_problema),
              objetivo     = trim(p_objetivo),
              publico_alvo = trim(p_publico_alvo),
              estrategia   = trim(p_estrategia),
              sistematica  = trim(p_sistematica),
              metodologia  = trim(p_metodologia)
        where sq_siw_solicitacao = p_chave;
    End If;
   
end SP_PutProgQualitativa_IS;
/

