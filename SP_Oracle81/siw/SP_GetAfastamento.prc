create or replace procedure SP_GetAfastamento
   (p_cliente                  in number,
    p_chave                    in number    default null,
    p_sq_tipo_afastamento      in number    default null,
    p_sq_contrato_colaborador  in number    default null,
    p_inicio_data              in date      default null,
    p_fim_data                 in date      default null,
    p_periodo_inicio           in varchar2  default null,
    p_periodo_fim              in varchar2  default null,
    p_chave_aux                in number    default null,
    p_restricao                in varchar2  default null,
    p_result    out siw.sys_refcursor) is
    
    w_inicio number(9);
    w_fim    number(9);
begin
   If p_inicio_data is not null Then
      If Nvl(p_periodo_inicio,'M') = 'M' Then
         w_inicio := to_char(p_inicio_data,'yyyymmdd')||0;
      Else
         w_inicio := to_char(p_inicio_data,'yyyymmdd')||1;
      End If;
      If Nvl(p_periodo_fim,'T') = 'M' Then
         w_fim    := to_char(p_fim_data,'yyyymmdd')||0;
      Else
         w_fim    := to_char(p_fim_data,'yyyymmdd')||1;
      End If;
   End If;
   
   If p_restricao is null Then
      -- Recupera todos os afastamentos
      open p_result for 
         select a.sq_afastamento chave, a.sq_tipo_afastamento, a.sq_contrato_colaborador, a.inicio_data,
                a.inicio_periodo, a.fim_data, a.fim_periodo, a.dias, a.observacao, 
                b.nome nm_tipo_afastamento, e.sq_unidade,
                e.sigla||' ('||d.nome||' - R.'||d.ramal||')' local, f.nome_resumido, f.sq_pessoa
           from gp_afastamento          a,
                gp_tipo_afastamento     b,
                gp_contrato_colaborador c,
                eo_localizacao          d,
                eo_unidade              e,
                co_pessoa               f
          where (a.sq_tipo_afastamento      = b.sq_tipo_afastamento)
            and (a.sq_contrato_colaborador  = c.sq_contrato_colaborador)
            and (c.sq_localizacao           = d.sq_localizacao)
            and (d.sq_unidade               = e.sq_unidade)
            and (c.sq_pessoa                = f.sq_pessoa and
                 c.cliente                 = f.sq_pessoa_pai)
            and a.cliente                   = p_cliente
            and ((p_chave                   is null) or (p_chave                   is not null and a.sq_afastamento          = p_chave))
            and ((p_chave_aux               is null) or (p_chave_aux               is not null and a.sq_afastamento          <> p_chave_aux))
            and ((p_sq_tipo_afastamento     is null) or (p_sq_tipo_afastamento     is not null and a.sq_tipo_afastamento     = p_sq_tipo_afastamento))
            and ((p_sq_contrato_colaborador is null) or (p_sq_contrato_colaborador is not null and a.sq_contrato_colaborador = p_sq_contrato_colaborador))
            and ((p_inicio_data             is null) or (p_inicio_data             is not null and (to_char(a.inicio_data,'yyyymmdd')||decode(Nvl(a.inicio_periodo,'M'),'M',0,1) between w_inicio and w_fim or
                                                                                                    to_char(a.fim_data,'yyyymmdd')||decode(Nvl(a.fim_periodo,'M'),'M',0,1) between w_inicio and w_fim or
                                                                                                    w_inicio  between to_char(a.inicio_data,'yyyymmdd')||decode(Nvl(a.inicio_periodo,'M'),'M',0,1) and to_char(a.fim_data,'yyyymmdd')||decode(Nvl(a.fim_periodo,'M'),'M',0,1) or
                                                                                                    w_fim     between to_char(a.inicio_data,'yyyymmdd')||decode(Nvl(a.inicio_periodo,'M'),'M',0,1) and to_char(a.fim_data,'yyyymmdd')||decode(Nvl(a.fim_periodo,'M'),'M',0,1)
                                                                                                   )
                                                        )
                );
   ElsIf p_restricao = 'VERIFICAENVIO' Then
      -- Verifica se houve algum envio para o afastamento
      open p_result for 
         select count(*) existe 
           from gp_afastamento_envio a
          where a.sq_afastamento = p_chave;
   End If;
end SP_GetAfastamento;
/
