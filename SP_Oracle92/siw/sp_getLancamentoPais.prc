create or replace procedure SP_GetLancamentoPais
   (p_cliente            in number,
    p_menu               in number   default null,
    p_chave              in number   default null,
    p_sq_pais            in number   default null,
    p_restricao          in varchar2 default null,
    p_result             out sys_refcursor) is
begin
   If p_restricao is null Then
      open p_result for 
         select a.sq_pais, a.nome, a.sigla,
                b.sq_siw_solicitacao, b.valor
           from co_pais                           a
                inner   join fn_lancamento_pais   b on (a.sq_pais = b.sq_pais)
          where (p_chave       is null or (p_chave    is not null and b.sq_siw_solicitacao = p_chave))
            and (p_sq_pais     is null or (p_sq_pais  is not null and a.sq_pais            = p_sq_pais));
   Elsif p_restricao = 'EDICAO' Then
      open p_result for 
         select a.sq_pais, a.nome, a.sigla,
                b.sq_siw_solicitacao, b.valor
           from co_pais                           a
                left    join fn_lancamento_pais   b on (a.sq_pais   = b.sq_pais and
                                                        (p_chave    is null or 
                                                         (p_chave   is not null and b.sq_siw_solicitacao = p_chave)
                                                        )
                                                       )
          where a.codigo_externo = 'OTCA';
   End If;
End SP_GetLancamentoPais;
/
