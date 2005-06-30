create or replace procedure sp_GetProjeto_IS
   (P_CHAVE       NUMBER   default null,
    P_CLIENTE	    NUMBER,
    P_CODIGO	    VARCHAR2 default null,
    P_NOME	      VARCHAR2 default null,
    P_RESPONSAVEL	VARCHAR2 default null,
    P_TELEFONE	  VARCHAR2 default null,
    P_EMAIL	      VARCHAR2 default null,
    P_ORDEM	      NUMBER   default null,
    P_ATIVO	      VARCHAR2 default null,
    P_PADRAO	    VARCHAR2 default null,
    p_selecao_mp  varchar2 default null,
    p_selecao_se  varchar2 default null,
    p_restricao   varchar2 default null,
    p_siw_solic   number   default null,
    p_result      out siw.siw.sys_refcursor) is
begin
   -- Recupera os projetos do modulo Infra-SIG
   If p_restricao is null Then
      open p_result for
         select a.sq_isprojeto chave, a.cliente, a.codigo, a.nome, a.responsavel, a.telefone, 
                a.email, a.ordem, a.ativo, a.padrao, 0.00 aprovado, 0.00 empenhado, 0.00 liquidado,
                b.sq_siw_solicitacao, c.titulo
           from is_projeto          a,
                is_acao             b,
                siw.siw_solicitacao b1,
                siw.siw_tramite     b2,
                siw.pj_projeto      c 
          where a.sq_isprojeto       = b.sq_isprojeto (+) 
            and a.cliente            = b.cliente (+)
            and b.sq_siw_solicitacao = b1.sq_siw_solicitacao (+)
            and b1.sq_siw_tramite    = b2.sq_siw_tramite (+) 
            and b2.sigla (+) <> 'CA'                                    
            and b.sq_siw_solicitacao = c.sq_siw_solicitacao (+)
            and a.cliente      = p_cliente
            and ((p_chave       is null) or (p_chave       is not null and a.sq_isprojeto = p_chave))
            and ((p_codigo      is null) or (p_codigo      is not null and a.codigo       = p_codigo))
            and ((p_nome        is null) or (p_nome        is not null and upper(a.nome) like '%'||upper(p_nome)||'%'))
            and ((p_responsavel is null) or (p_responsavel is not null and upper(a.responsavel)  like '%'||upper(p_responsavel)||'%'))
            and ((p_telefone    is null) or (p_telefone    is not null and a.telefone     = p_telefone))
            and ((p_email       is null) or (p_email       is not null and a.email        = p_email))
            and ((p_ordem       is null) or (p_ordem       is not null and a.ordem        = p_ordem))         
            and ((p_ativo       is null) or (p_ativo       is not null and a.ativo        = p_ativo))
            and ((p_padrao      is null) or (p_padrao      is not null and a.padrao       = p_padrao))
            and ((p_selecao_mp  is null) or (p_selecao_mp  is not null and b.selecao_mp   = p_selecao_mp))
            and ((p_selecao_se  is null) or (p_selecao_se  is not null and b.selecao_se   = p_selecao_se))
            and ((p_siw_solic   is null) or (p_siw_solic   is not null and b.sq_siw_solicitacao = p_siw_solic));
   Elsif p_restricao = 'CADASTRAMENTO' Then   
      open p_result for      
          select a.sq_isprojeto chave, a.cliente, a.codigo, a.nome, a.responsavel, a.telefone, 
                 a.email, a.ordem, a.ativo, a.padrao, 0.00 aprovado, 0.00 empenhado, 0.00 liquidado
            from is_projeto                     a
           where a.cliente      = p_cliente
             and ((p_chave       is null) or (p_chave       is not null and a.sq_isprojeto = p_chave))
             and ((p_codigo      is null) or (p_codigo      is not null and a.codigo       = p_codigo))
             and ((p_nome        is null) or (p_nome        is not null and upper(a.nome) like '%'||upper(p_nome)||'%'))
             and ((p_responsavel is null) or (p_responsavel is not null and a.responsavel  = p_responsavel))
             and ((p_telefone    is null) or (p_telefone    is not null and a.telefone     = p_telefone))
             and ((p_email       is null) or (p_email       is not null and a.email        = p_email))
             and ((p_ordem       is null) or (p_ordem       is not null and a.ordem        = p_ordem))         
             and ((p_ativo       is null) or (p_ativo       is not null and a.ativo        = p_ativo))
             and ((p_padrao      is null) or (p_padrao      is not null and a.padrao       = p_padrao));
   End If;
end sp_GetProjeto_IS;
/
