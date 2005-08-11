create or replace procedure SP_GetGPModalidade
   (p_cliente   in number,
    p_chave     in number    default null,
    p_sigla     in varchar2  default null,
    p_nome      in varchar   default null,
    p_ativo     in varchar2  default null,
    p_chave_aux in number    default null,
    p_restricao in varchar2  default null,
    p_result    out sys_refcursor) is
begin
   If p_restricao is null Then
      -- Recupera todas ou muma das modalidades de contratação
      open p_result for 
         select a.sq_modalidade_contrato chave, a.cliente, a.nome, a.sigla, a.descricao, 
                a.ferias, a.username, a.passagem, a.diaria, a.ativo
           from gp_modalidade_contrato  a
          where a.cliente = p_cliente
            and ((p_chave is null) or (p_chave is not null and a.sq_modalidade_contrato = p_chave))
            and ((p_sigla is null) or (p_sigla is not null and a.sigla                  = p_sigla))
            and ((p_nome  is null) or (p_nome  is not null and a.nome                   = p_nome))
            and ((p_ativo is null) or (p_ativo is not null and a.ativo                  = p_ativo));
   ElsIf p_restricao = 'VERIFICASIGLANOME' Then
      open p_result for                    
         select count(*) existe
           from gp_modalidade_contrato  a
          where a.cliente = p_cliente
            and ((p_chave is null) or (p_chave is not null and a.sq_modalidade_contrato    <> p_chave))
            and (((p_sigla is null) or (p_sigla is not null and a.sigla                     = upper(trim(p_sigla))))
            or  ((p_nome  is null) or (p_nome  is not null and acentos(upper(a.nome),null) = acentos(upper(p_nome),null))));
   
   ElsIf p_restricao = 'TPAFASTAMENTO' Then
      -- Recupera todas ou muma das modalidades de contratação para a tela de cadastramento de tipos de afastamento
         open p_result for 
            select a.sq_modalidade_contrato chave, a.cliente, a.nome, a.sigla, a.descricao, 
                   a.ferias, a.username, a.passagem, a.diaria, a.ativo, b.sq_tipo_afastamento
              from gp_modalidade_contrato    a,
                   (select x.sq_tipo_afastamento, x.sq_modalidade_contrato
                      from gp_afastamento_modalidade x
                     where x.sq_tipo_afastamento = p_chave_aux) b
             where a.cliente = p_cliente
               and a.sq_modalidade_contrato = b.sq_modalidade_contrato (+)
               and ((p_chave is null) or (p_chave is not null and a.sq_modalidade_contrato = p_chave))
               and ((p_sigla is null) or (p_sigla is not null and a.sigla                  = p_sigla))
               and ((p_nome  is null) or (p_nome  is not null and a.nome                   = p_nome))
               and ((p_ativo is null) or (p_ativo is not null and a.ativo                  = p_ativo));
   
   ElsIf p_restricao = 'VERIFICAMODALIDADES' Then
      open p_result for                  
         select count(*) existe 
           from gp_contrato_colaborador a 
          where a.sq_modalidade_contrato = p_chave;
   End If;
end SP_GetGPModalidade;
/
