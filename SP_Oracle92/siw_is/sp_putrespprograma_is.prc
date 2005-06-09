create or replace procedure SP_PutRespPrograma_IS
   (p_chave                 in number,
    p_nm_gerente_programa   in varchar2  default null,
    p_fn_gerente_programa   in varchar2  default null,
    p_em_gerente_programa   in varchar2  default null,
    p_nm_gerente_executivo  in varchar2  default null,
    p_fn_gerente_executivo  in varchar2  default null,
    p_em_gerente_executivo  in varchar2  default null,
    p_nm_gerente_adjunto    in varchar2  default null,
    p_fn_gerente_adjunto    in varchar2  default null,
    p_em_gerente_adjunto    in varchar2  default null
   ) is
begin
   -- Atualiza a tabela de programas
   Update is_programa set
      nm_gerente_programa       = p_nm_gerente_programa,
      fn_gerente_programa       = p_fn_gerente_programa,
      em_gerente_programa       = p_em_gerente_programa,
      nm_gerente_executivo      = p_nm_gerente_executivo,
      fn_gerente_executivo      = p_fn_gerente_executivo,
      em_gerente_executivo      = p_em_gerente_executivo,
      nm_gerente_adjunto        = p_nm_gerente_adjunto,
      fn_gerente_adjunto        = p_fn_gerente_adjunto,
      em_gerente_adjunto        = p_em_gerente_adjunto
   where sq_siw_solicitacao = p_chave;
end SP_PutRespPrograma_IS;
/

