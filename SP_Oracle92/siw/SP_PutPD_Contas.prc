create or replace procedure SP_PutPD_Contas
   (p_cliente             in number,
    p_chave               in number,
    p_cumprimento         in varchar2  default null,
    p_nota_conclusao      in varchar2  default null,
    p_sq_relatorio        in number    default null,
    p_exclui_arquivo      in varchar2  default null,
    p_nome                in varchar2  default null,
    p_descricao           in varchar2  default null,
    p_caminho             in varchar2  default null,
    p_tamanho             in number    default null,
    p_tipo                in varchar2  default null,
    p_nome_original       in varchar2   default null
   ) is
   
   w_arquivo siw_arquivo.sq_siw_arquivo%type;
begin
   -- Atualiza os dados da viagem
   update pd_missao set cumprimento = p_cumprimento where sq_siw_solicitacao = p_chave;
   
   -- Grava motivo de cancelamento/cumprimento parcial ou anula o conteúdo se cumprimento integral
   update gd_demanda set nota_conclusao = p_nota_conclusao where sq_siw_solicitacao = p_chave;
   
   If p_exclui_arquivo is not null or p_cumprimento = 'C' Then -- Remove arquivo
      -- Atualiza os dados da viagem
      update pd_missao set sq_relatorio_viagem = null where sq_siw_solicitacao = p_chave;

      -- Remove da tabela de arquivos
      delete siw_arquivo where sq_siw_arquivo = p_sq_relatorio;
   Elsif p_caminho is not null Then
      If p_sq_relatorio is null Then -- Inclusão
         -- Recupera a próxima chave
         select sq_siw_arquivo.nextval into w_arquivo from dual;
         
         -- Insere registro em SIW_ARQUIVO
         insert into siw_arquivo
          (sq_siw_arquivo, cliente,   nome,   descricao,   inclusao, tamanho,   tipo,   caminho,   nome_original)
         values
          (w_arquivo,      p_cliente, p_nome, p_descricao, sysdate,  p_tamanho, p_tipo, p_caminho, p_nome_original);
          
         -- Atualiza os dados da viagem
         update pd_missao set sq_relatorio_viagem = w_arquivo where sq_siw_solicitacao = p_chave;
      Else -- Alteração
         -- Atualiza a tabela de arquivos
         update siw_arquivo
            set nome      = p_nome,
                descricao = p_descricao
         where sq_siw_arquivo = p_sq_relatorio;
          
         -- Se foi informado um novo arquivo, atualiza os dados
         If p_caminho is not null Then
            update siw_arquivo
               set inclusao      = sysdate,
                   tamanho       = p_tamanho,
                   tipo          = p_tipo,
                   caminho       = p_caminho,
                   nome_original = p_nome_original
             where sq_siw_arquivo = p_sq_relatorio;
         End If;
      End If;
   End If;
end SP_PutPD_Contas;
/
