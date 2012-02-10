create or replace procedure SP_PutConfCL
   (p_operacao                 in varchar2,
    p_cliente                  in number,
    p_menu                     in number,
    p_chave                    in number   default null,
    p_siw_solicitacao          in number   default null,
    p_rubrica                  in number   default null,
    p_lancamento               in number   default null,
    p_consumo                  in varchar2 default null,
    p_permanente               in varchar2 default null,
    p_servico                  in varchar2 default null,
    p_outros                   in varchar2 default null
   ) is
begin
   If p_operacao = 'I' Then
      -- Insere registro
      insert into cl_vinculo_financeiro
        (sq_clvinculo_financeiro,         cliente,      sq_siw_solicitacao,   sq_projeto_rubrica,   sq_tipo_lancamento,
         consumo,                         permanente,   servico,              outros,               sq_menu)
      values
        (sq_clvinculo_financeiro.nextval, p_cliente,    p_siw_solicitacao,    p_rubrica,            p_lancamento,
         p_consumo,                       p_permanente, p_servico,            p_outros,             p_menu
        );
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update cl_vinculo_financeiro
         set sq_projeto_rubrica = p_rubrica,
             sq_tipo_lancamento = p_lancamento,
             consumo            = p_consumo,
             permanente         = p_permanente,
             servico            = p_servico,
             outros             = p_outros
       where sq_clvinculo_financeiro = p_chave;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete cl_vinculo_financeiro where sq_clvinculo_financeiro = p_chave;
   End If;
end SP_PutConfCL;
/
