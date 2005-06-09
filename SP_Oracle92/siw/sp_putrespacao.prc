create or replace procedure SP_PutRespAcao
   (p_chave               in number,
    p_responsavel         in varchar2  default null,
    p_telefone            in varchar2  default null,
    p_email               in varchar2  default null,
    p_tipo                in number
   ) is
begin
   If p_tipo = 1 or p_tipo = 2 Then
      -- Atualiza a tabela de açoes do PPA
      Update or_acao_ppa set
          responsavel      = p_responsavel,
          telefone         = p_telefone,
          email            = p_email
      where sq_acao_ppa    = p_chave;
   Elsif p_tipo = 3 Then
      Update or_prioridade set
           responsavel       = p_responsavel,
           telefone          = p_telefone,
           email             = p_email
       where sq_orprioridade = p_chave;
   End If;
end SP_PutRespAcao;
/

