create or replace procedure SP_PutPD_Deslocamento
   (p_operacao            in  varchar2,
    p_chave               in number,
    p_chave_aux           in number    default null,
    p_origem              in number    default null,
    p_data_saida          in date      default null,
    p_hora_saida          in varchar2  default null,
    p_destino             in number    default null,
    p_data_chegada        in date      default null,
    p_hora_chegada        in varchar2  default null,
    p_sq_cia_transporte   in number    default null,
    p_codigo_voo          in varchar2  default null
   ) is
begin
   -- Remove todos os valores já inseridos relativos as diárias e deslocamento
   If p_operacao = 'I' or p_operacao = 'A' or p_operacao = 'E' Then
      delete pd_diaria where sq_siw_solicitacao = p_chave;
      update pd_missao set
             valor_alimentacao    = 0,
             valor_transporte     = 0,
             desconto_alimentacao = 0,
             desconto_transporte  = 0,
             valor_adicional      = 0,
             valor_passagem       = 0,
             pta                  = null,
             emissao_bilhete      = null
       where sq_siw_solicitacao = p_chave;
      update pd_deslocamento set
             sq_cia_transporte = null,
             codigo_voo        = null
       where sq_siw_solicitacao = p_chave;
   End If;
   If p_operacao = 'I' Then -- Inclusão
      -- Insere registro na tabela de deslocamentos
      insert into pd_deslocamento
        (sq_deslocamento,         sq_siw_solicitacao, origem,   destino,   saida, chegada)
      values
        (sq_deslocamento.nextval, p_chave,            p_origem, p_destino, 
         to_date(to_char(p_data_saida,'dd/mm/yyyy')||', '||p_hora_saida,'dd/mm/yyyy, hh24:mi'), 
         to_date(to_char(p_data_chegada,'dd/mm/yyyy')||', '||p_hora_chegada,'dd/mm/yyyy, hh24:mi')
        );
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de deslocamentos
      update pd_deslocamento
         set origem  = p_origem,
             destino = p_destino,
             saida   = to_date(to_char(p_data_saida,'dd/mm/yyyy')||', '||p_hora_saida,'dd/mm/yyyy, hh24:mi'),
             chegada = to_date(to_char(p_data_chegada,'dd/mm/yyyy')||', '||p_hora_chegada,'dd/mm/yyyy, hh24:mi')
       where sq_deslocamento = p_chave_aux;
   Elsif p_operacao = 'P' Then
   update pd_deslocamento
         set sq_cia_transporte = p_sq_cia_transporte,
             codigo_voo        = p_codigo_voo
       where sq_deslocamento = p_chave_aux;
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Remove o registro na tabela de deslocamentos
      delete pd_deslocamento where sq_deslocamento = p_chave_aux;
   End If;
end SP_PutPD_Deslocamento;
/
