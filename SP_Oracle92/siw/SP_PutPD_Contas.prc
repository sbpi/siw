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
   w_cont    number(10);
begin
   -- Atualiza os dados da viagem
   update pd_missao set cumprimento = p_cumprimento where sq_siw_solicitacao = p_chave;
   
   If p_cumprimento = 'I' or p_cumprimento = 'P' Then
      select count(*) into w_cont from pd_deslocamento a where a.tipo = 'P' and a.sq_siw_solicitacao = p_chave;
      If w_cont = 0 Then
         -- Se cumprimento integral, copia os deslocamentos da solicitação para a prestação de contas
         insert into pd_deslocamento
           (sq_deslocamento,           sq_siw_solicitacao,   origem,         destino,             saida,         chegada, 
            passagem,                  sq_meio_transporte,   valor_trecho,   sq_cia_transporte,   codigo_voo,    compromisso,
            aeroporto_origem,          aeroporto_destino,    tipo)
         (select 
            sq_deslocamento.nextval,   a.sq_siw_solicitacao, a.origem,       a.destino,           a.saida,       a.chegada, 
            a.passagem,                a.sq_meio_transporte, a.valor_trecho, a.sq_cia_transporte, a.codigo_voo,  a.compromisso,
            a.aeroporto_origem,        a.aeroporto_destino,  'P'
            from pd_deslocamento a 
           where a.tipo              = 'S' 
            and a.sq_siw_solicitacao = p_chave
         );
      End If;

      select count(*) into w_cont from pd_diaria a where a.tipo = 'P' and a.sq_siw_solicitacao = p_chave;
      If w_cont = 0 Then
         -- Se cumprimento integral, copia as diárias da solicitação para a prestação de contas
         insert into pd_diaria
           (sq_diaria,                   sq_siw_solicitacao,              sq_cidade,              quantidade,                valor, 
            hospedagem,                  hospedagem_qtd,                  hospedagem_valor,       veiculo,                   veiculo_qtd, 
            veiculo_valor,               sq_valor_diaria,                 diaria,                 sq_deslocamento_chegada,   sq_deslocamento_saida, 
            sq_valor_diaria_hospedagem,  sq_valor_diaria_veiculo,         justificativa_diaria,   justificativa_veiculo,
            sq_pdvinculo_diaria,         sq_pdvinculo_hospedagem,         sq_pdvinculo_veiculo,   hospedagem_checkin,        hospedagem_checkout,
            hospedagem_observacao,       veiculo_retirada,                veiculo_devolucao,      tipo)
         (select sq_diaria.nextval,      sq_siw_solicitacao,              sq_cidade,              quantidade,                valor, 
            hospedagem,                  hospedagem_qtd,                  hospedagem_valor,       veiculo,                   veiculo_qtd, 
            veiculo_valor,               sq_valor_diaria,                 diaria,                 sq_deslocamento_chegada,   sq_deslocamento_saida, 
            sq_valor_diaria_hospedagem,  sq_valor_diaria_veiculo,         justificativa_diaria,   justificativa_veiculo,
            sq_pdvinculo_diaria,         sq_pdvinculo_hospedagem,         sq_pdvinculo_veiculo,   hospedagem_checkin,        hospedagem_checkout,
            hospedagem_observacao,       veiculo_retirada,                veiculo_devolucao,      'P'
            from pd_diaria a 
           where a.tipo              = 'S' 
            and a.sq_siw_solicitacao = p_chave
         );
      End If;
   Else
      -- Se viagem cancelada, remove os deslocamentos e as diárias
      delete pd_diaria a       where a.tipo = 'P' and a.sq_siw_solicitacao = p_chave;
      delete pd_deslocamento a where a.tipo = 'P' and a.sq_siw_solicitacao = p_chave;
   End If;
   
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
