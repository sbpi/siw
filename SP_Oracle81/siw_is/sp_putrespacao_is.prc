create or replace procedure SP_PutRespAcao_IS
   (p_chave               in number,
    p_responsavel         in varchar2  default null,
    p_telefone            in varchar2  default null,
    p_email               in varchar2  default null,
    p_tipo                in number
   ) is
begin
   If p_tipo = 1 Then
      -- Atualiza a tabela de açoes do PPA
      Update is_acao set
          nm_coordenador       = p_responsavel,
          fn_coordenador       = p_telefone,
          em_coordenador       = p_email
      where sq_siw_solicitacao = p_chave;
   Elsif p_tipo = 2 Then
      Update is_projeto set
           responsavel       = p_responsavel,
           telefone          = p_telefone,
           email             = p_email
       where sq_isprojeto    = p_chave;
   End If;
end SP_PutRespAcao_IS;
/

