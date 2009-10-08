create or replace procedure sp_putGpDesempenho
   (p_contrato                 in  number,
    p_ano                      in  number,
    p_percentual               in  number,
    p_operacao                 in  varchar2
   ) is   
begin
  -- Grava os parametros do módulo de recursos humanos do cliente
   If p_operacao = 'I' Then
      -- Insere registro
      insert into gp_desempenho(sq_contrato_colaborador,ano,percentual)
      values
         (p_contrato, p_ano, p_percentual);
   Elsif p_operacao = 'A' Then
      -- Altera registro
      update gp_desempenho
         set percentual              = p_percentual
       where sq_contrato_colaborador = p_contrato and ano = p_ano;
   Elsif p_operacao = 'E' Then
      -- Exclui registro
      delete from gp_desempenho
       where sq_contrato_colaborador = p_contrato 
         and ano = p_ano;       
   End If;
end sp_putGpDesempenho;
/
