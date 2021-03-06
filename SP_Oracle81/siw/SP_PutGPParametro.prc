create or replace procedure SP_PutGPParametro
   (p_cliente                  in  number,
    p_sq_unidade_gestao        in  number,
    p_admissao_texto           in  varchar2,
    p_admissao_destino         in  varchar2,
    p_rescisao_texto           in  varchar2,
    p_rescisao_destino         in  varchar2,
    p_feriado_legenda          in  varchar2,
    p_feriado_nome             in  varchar2,
    p_ferias_legenda           in  varchar2,
    p_ferias_nome              in  varchar2,
    p_viagem_legenda           in  varchar2,
    p_viagem_nome              in  varchar2
   ) is
   
   p_operacao varchar2(1);
   w_existe   number(18);
   
begin
   -- Verifica se opera��o de inclus�o ou altera��o
   select count(*) into w_existe from gp_parametro a where a.cliente = p_cliente;
   If w_existe > 0 Then
      p_operacao := 'A';
   Else
      p_operacao := 'I';
   End If;
   
   -- Grava os parametros do m�dulo de recursos humanos do cliente
   If p_operacao = 'I' Then
      -- Insere registro
      insert into gp_parametro
         (cliente, sq_unidade_gestao, admissao_texto, admissao_destino, rescisao_texto, rescisao_destino, feriado_legenda, feriado_nome, ferias_legenda, ferias_nome, viagem_legenda, viagem_nome)
      values
         (p_cliente, p_sq_unidade_gestao, p_admissao_texto, p_admissao_destino, p_rescisao_texto, p_rescisao_destino, p_feriado_legenda, p_feriado_nome, p_ferias_legenda, p_ferias_nome, p_viagem_legenda, p_viagem_nome);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update gp_parametro
         set sq_unidade_gestao    = p_sq_unidade_gestao,
             admissao_texto       = p_admissao_texto,
             admissao_destino     = p_admissao_destino,
             rescisao_texto       = p_rescisao_texto,
             rescisao_destino     = p_rescisao_destino,
             feriado_legenda      = p_feriado_legenda,
             feriado_nome         = p_feriado_nome,
             ferias_legenda       = p_ferias_legenda,
             ferias_nome          = p_ferias_nome,
             viagem_legenda       = p_viagem_legenda,
             viagem_nome          = p_viagem_nome
       where cliente = p_cliente;
   End If;
end SP_PutGPParametro;
/
