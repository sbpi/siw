create or replace procedure SP_PutPD_Bilhete
   (p_operacao            in  varchar2,
    p_chave               in number,
    p_chave_aux           in number    default null,
    p_sq_cia_transporte   in number    default null,
    p_data                in date      default null,
    p_numero              in varchar2  default null,
    p_trecho              in varchar2  default null,
    p_rloc                in varchar2  default null,
    p_classe              in varchar2  default null,
    p_valor_cheio         in number    default null,
    p_valor_bilhete       in number    default null,
    p_valor_taxa          in number    default null,
    p_valor_pta           in number    default null,
    p_deslocamento        in varchar2  default null,
    p_tipo                in varchar2  default null,
    p_utilizado           in varchar2  default null,
    p_faturado            in varchar2  default null,
    p_observacao          in varchar2  default null
   ) is
   
   w_chave_aux      number(18)    := p_chave_aux;
   w_valor_passagem number(18,2);
   l_item           varchar2(18);
   l_desloc         varchar2(200) := p_deslocamento ||',';
   x_desloc         varchar2(200) := '';
begin
   If p_deslocamento is not null Then
      Loop
         l_item  := Trim(substr(l_desloc,1,Instr(l_desloc,',')-1));
         If Length(l_item) > 0 Then
            x_desloc := x_desloc||','''||to_number(l_item)||'''';
         End If;
         l_desloc := substr(l_desloc,Instr(l_desloc,',')+1,200);
         Exit when l_desloc is null;
      End Loop;
      x_desloc := substr(x_desloc,2,200);
   End If;

   If p_operacao = 'I' Then -- Inclusão
      -- Recupera a próxima chave
      select sq_bilhete.nextval into w_chave_aux from dual;
      
      -- Insere registro na tabela de bilhetes
      insert into pd_bilhete
        (sq_bilhete,          sq_siw_solicitacao, sq_cia_transporte,       data,                    numero,          trecho, 
         valor_bilhete_cheio, valor_bilhete,      valor_pta,               valor_taxa_embarque,     rloc,            classe,
         tipo,                observacao,         utilizado
        )
      values
        (w_chave_aux,         p_chave,            p_sq_cia_transporte,     p_data,                  p_numero,        upper(p_trecho), 
         p_valor_cheio,       p_valor_bilhete,    p_valor_pta,             p_valor_taxa,            p_rloc,          upper(p_classe), 
         p_tipo,              p_observacao,       p_utilizado
        );

      -- Vincula os deslocamentos indicados
      update pd_deslocamento set sq_bilhete = w_chave_aux where sq_siw_solicitacao = p_chave and InStr(x_desloc,sq_deslocamento) > 0;
   Elsif p_operacao = 'A' Then -- Alteração
      -- Atualiza a tabela de bilhetes
      update pd_bilhete set 
           sq_siw_solicitacao  = p_chave,
           sq_cia_transporte   = p_sq_cia_transporte,
           data                = p_data,
           numero              = p_numero,
           trecho              = upper(p_trecho),
           valor_bilhete_cheio = p_valor_cheio,
           valor_bilhete       = p_valor_bilhete,
           valor_pta           = p_valor_pta,
           valor_taxa_embarque = p_valor_taxa,
           rloc                = p_rloc,
           classe              = p_classe,
           observacao          = p_observacao,
           utilizado           = p_utilizado
       where sq_bilhete = w_chave_aux;

      -- Desvincula os deslocamentos
      update pd_deslocamento set sq_bilhete = null where sq_bilhete = w_chave_aux;
  
      -- Vincula os deslocamentos indicados
      update pd_deslocamento set sq_bilhete = w_chave_aux where sq_siw_solicitacao = p_chave and InStr(x_desloc,sq_deslocamento) > 0;
   Elsif p_operacao = 'C' Then -- Confirmação de uso do bilhete
      -- Desvincula os deslocamentos
      update pd_bilhete a set a.utilizado = p_utilizado where sq_bilhete = w_chave_aux;

   Elsif p_operacao = 'E' Then -- Exclusão
      -- Desvincula os deslocamentos
      update pd_deslocamento set sq_bilhete = null where sq_bilhete = w_chave_aux;
      
      -- Remove o registro na tabela de deslocamentos
      delete pd_bilhete where sq_bilhete = w_chave_aux;
   End If;

  -- Recupera o valor total dos bilhetes
  select sum(nvl(x.valor_bilhete,0))+sum(nvl(x.valor_pta,0))+sum(nvl(x.valor_taxa_embarque,0))
     into w_valor_passagem
     from pd_bilhete x
    where x.sq_siw_solicitacao = p_chave
      and x.tipo = p_tipo;

  -- Atualiza o valor consolidado dos bilhetes
  If p_tipo = 'P' Then
     -- Se informação da agência de viagens, atualiza apenas o valor real
     update pd_missao set valor_passagem = w_valor_passagem where sq_siw_solicitacao = p_chave;
  Else
     -- Caso contrário, atualiza valor previsto e valor real
     update pd_missao set valor_passagem = w_valor_passagem, valor_previsto_bilhetes = w_valor_passagem where sq_siw_solicitacao = p_chave;
  End If;

end SP_PutPD_Bilhete;
/
