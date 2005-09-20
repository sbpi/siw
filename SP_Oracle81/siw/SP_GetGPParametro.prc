create or replace procedure SP_GetGPParametro
   (p_cliente   in number,
    p_chave_aux in number    default null,
    p_restricao in varchar2  default null,
    p_result    out siw.sys_refcursor) is
begin
   If p_restricao is null Then
      -- Recupera os parametros do modulo de recursos humanos de um cliente
      open p_result for 
         select a.sq_unidade_gestao, a.admissao_texto, a.admissao_destino, a.rescisao_texto,
                a.rescisao_destino, a.feriado_legenda, a.feriado_nome, a.ferias_legenda, a.ferias_nome,
                a.viagem_legenda, a.viagem_nome
           from gp_parametro  a
          where a.cliente = p_cliente;
   End If;
end SP_GetGPParametro;
/
