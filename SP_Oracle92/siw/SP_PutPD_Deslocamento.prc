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
    p_codigo_voo          in varchar2  default null,
    p_passagem            in varchar2  default null,
    p_meio_transp         in number    default null,
    p_valor_trecho        in number    default null,
    p_compromisso         in varchar2  default null,
    p_aero_orig           in varchar2  default null,
    p_aero_dest           in varchar2  default null
   ) is
   w_existe     varchar2(1);
begin
   If p_operacao = 'I' Then -- Inclusão
      -- Insere registro na tabela de deslocamentos
      insert into pd_deslocamento
        (sq_deslocamento,         sq_siw_solicitacao, origem,         destino,
         saida,                   chegada, 
         passagem,                sq_meio_transporte, valor_trecho,   sq_cia_transporte,
         codigo_voo,              compromisso,
         aeroporto_origem,        aeroporto_destino)
      values
        (sq_deslocamento.nextval, p_chave,            p_origem,       p_destino, 
         to_date(to_char(p_data_saida,'dd/mm/yyyy')||', '||p_hora_saida,'dd/mm/yyyy, hh24:mi'), 
         to_date(to_char(p_data_chegada,'dd/mm/yyyy')||', '||p_hora_chegada,'dd/mm/yyyy, hh24:mi'),
         p_passagem,              p_meio_transp,      p_valor_trecho, p_sq_cia_transporte,
         p_codigo_voo,            p_compromisso,
         p_aero_orig,             p_aero_dest
        );
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de deslocamentos
      update pd_deslocamento set 
          origem                  = p_origem,
          destino                 = p_destino,
          saida                   = to_date(to_char(p_data_saida,'dd/mm/yyyy')||', '||p_hora_saida,'dd/mm/yyyy, hh24:mi'),
          chegada                 = to_date(to_char(p_data_chegada,'dd/mm/yyyy')||', '||p_hora_chegada,'dd/mm/yyyy, hh24:mi'),
          passagem                = p_passagem,
          sq_meio_transporte      = p_meio_transp,
          valor_trecho            = p_valor_trecho,
          sq_cia_transporte       = p_sq_cia_transporte,
          codigo_voo              = p_codigo_voo,
          compromisso             = p_compromisso,
          aeroporto_origem        = p_aero_orig,
          aeroporto_destino       = p_aero_dest
       where sq_deslocamento = p_chave_aux;
   Elsif p_operacao = 'P' Then
       update pd_deslocamento
         set sq_cia_transporte  = p_sq_cia_transporte,
             codigo_voo         = p_codigo_voo,
             sq_meio_transporte = p_meio_transp
       where sq_deslocamento = p_chave_aux;       
   Elsif p_operacao = 'C' Then
       update pd_deslocamento
         set sq_cia_transporte  = p_sq_cia_transporte,
             codigo_voo         = p_codigo_voo,
             valor_trecho       = p_valor_trecho
       where sq_deslocamento = p_chave_aux;       
   Elsif p_operacao = 'E' Then -- Exclusão
      -- Remove o registro na tabela de deslocamentos
      delete pd_deslocamento where sq_deslocamento = p_chave_aux;
   End If;
   
   -- Verifica se a missão envolve trechos nacionais
   select case count(a.sq_deslocamento) when 0 then 'N' else 'S' end
     into w_existe
     from pd_deslocamento        a
          inner   join co_cidade b on (a.destino = b.sq_cidade)
            inner join co_pais   c on (b.sq_pais = c.sq_pais)
    where c.padrao = 'S'
      and a.sq_siw_solicitacao = p_chave;

   update pd_missao set nacional = w_existe where sq_siw_solicitacao = p_chave;
   
   -- Verifica se a missão envolve trechos inernacionais
   select case count(a.sq_deslocamento) when 0 then 'N' else 'S' end
     into w_existe
     from pd_deslocamento        a
          inner   join co_cidade b on (a.destino = b.sq_cidade)
            inner join co_pais   c on (b.sq_pais = c.sq_pais)
    where c.padrao = 'N'
      and a.sq_siw_solicitacao = p_chave;

   update pd_missao set internacional = w_existe where sq_siw_solicitacao = p_chave;
   
   -- Atualiza a data de início e término da missão
   update siw_solicitacao 
      set (inicio, fim) = (select min(saida), max(chegada) from pd_deslocamento where sq_siw_solicitacao = p_chave)
   where sq_siw_solicitacao = p_chave;
end SP_PutPD_Deslocamento;
/
