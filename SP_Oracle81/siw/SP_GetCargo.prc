create or replace procedure SP_GetCargo
   (p_cliente      in number,
    p_chave        in number   default null,
    p_sq_tipo      in number   default null,
    p_nome         in varchar2 default null,
    p_sq_formacao  in number   default null,
    p_ativo        in varchar2 default null,
    p_restricao    in varchar2 default null,
    p_result       out siw.sys_refcursor) is

begin
   If p_restricao = 'VERIFICACONTRATO' Then
      open p_result for                  
         select count(*) existe
           from gp_contrato_colaborador a 
          where a.sq_posto_trabalho = p_chave;
   ElsIf p_restricao = 'VERIFICANOME' Then
         If p_chave is not null then
            open p_result for
            select count(*) existe
              from eo_posto_trabalho a
             where a.cliente = p_cliente
               and (a.sq_posto_trabalho <> p_chave)
               and (a.nome = p_nome);
         else
            open p_result for
            select count(*) existe
               from eo_posto_trabalho a
              where a.cliente = p_cliente
                and (a.nome = p_nome);
         End If;
   ElsIf p_restricao is null Then
      -- Recupera todos os cargos
      open p_result for
         select a.sq_posto_trabalho chave, a.cliente, a.nome, a.descricao, a.atividades, a.competencias,
                a.salario_piso, a.salario_teto, a.ativo,
                b.nome nm_tipo_posto, b.descricao ds_tipo_posto, b.sq_eo_tipo_posto sq_tipo_posto,
                c.nome nm_formacao, c.sq_formacao
           from eo_posto_trabalho  a,
                eo_tipo_posto      b,
                co_formacao        c
          where a.cliente = p_cliente
            and (a.sq_eo_tipo_posto = b.sq_eo_tipo_posto)
            and (a.sq_formacao      = c.sq_formacao)
            and ((p_chave           is null) or (p_chave       is not null and a.sq_posto_trabalho = p_chave))
            and ((p_sq_tipo         is null) or (p_sq_tipo     is not null and a.sq_eo_tipo_posto  = p_sq_tipo))
            and ((p_nome            is null) or (p_nome        is not null and a.nome              = p_nome))
            and ((p_sq_formacao     is null) or (p_sq_formacao is not null and a.sq_formacao       = p_sq_formacao))
            and ((p_ativo           is null) or (p_ativo       is not null and a.ativo             = p_ativo));
   End If;
end SP_GetCargo;
/
