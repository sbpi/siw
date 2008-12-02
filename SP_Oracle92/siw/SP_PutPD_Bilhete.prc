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
    p_valor_bilhete       in number    default null,
    p_valor_taxa          in number    default null,
    p_valor_pta           in number    default null,
    p_deslocamento        in varchar2  default null,
    p_tipo                in varchar2  default null,
    p_utilizado           in varchar2  default null,
    p_faturado            in varchar2  default null
   ) is
   w_chave_aux number(18)    := p_chave_aux;
   l_item      varchar2(18);
   l_desloc    varchar2(200) := p_deslocamento ||',';
   x_desloc    varchar2(200) := '';
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

   If p_operacao = 'I' Then -- Inclus�o
      -- Recupera a pr�xima chave
      select sq_bilhete.nextval into w_chave_aux from dual;
      
      -- Insere registro na tabela de bilhetes
      insert into pd_bilhete
        (sq_bilhete,         sq_siw_solicitacao,         sq_cia_transporte,       data,         numero,          trecho, 
         valor_bilhete,      valor_pta,                  valor_taxa_embarque,     rloc,         classe,          tipo)
      values
        (w_chave_aux,        p_chave,                    p_sq_cia_transporte,     p_data,       p_numero,        upper(p_trecho), 
         p_valor_bilhete,    p_valor_pta,                p_valor_taxa,            p_rloc,       upper(p_classe), p_tipo);

      -- Vincula os deslocamentos indicados
      update pd_deslocamento set sq_bilhete = w_chave_aux where sq_siw_solicitacao = p_chave and InStr(x_desloc,sq_deslocamento) > 0;
   Elsif p_operacao = 'A' Then -- Altera��o
      -- Atualiza a tabela de bilhetes
      update pd_bilhete set 
           sq_siw_solicitacao  = p_chave,
           sq_cia_transporte   = p_sq_cia_transporte,
           data                = p_data,
           numero              = p_numero,
           trecho              = upper(p_trecho),
           valor_bilhete       = p_valor_bilhete,
           valor_pta           = p_valor_pta,
           valor_taxa_embarque = p_valor_taxa,
           rloc                = p_rloc,
           classe              = p_classe
       where sq_bilhete = w_chave_aux;

      -- Desvincula os deslocamentos
      update pd_deslocamento set sq_bilhete = null where sq_bilhete = w_chave_aux;
  
      -- Vincula os deslocamentos indicados
      update pd_deslocamento set sq_bilhete = w_chave_aux where sq_siw_solicitacao = p_chave and InStr(x_desloc,sq_deslocamento) > 0;
   Elsif p_operacao = 'C' Then -- Confirma��o de uso do bilhete
      -- Desvincula os deslocamentos
      update pd_bilhete a set a.utilizado = p_utilizado where sq_bilhete = w_chave_aux;

   Elsif p_operacao = 'E' Then -- Exclus�o
      -- Desvincula os deslocamentos
      update pd_deslocamento set sq_bilhete = null where sq_bilhete = w_chave_aux;
      
      -- Remove o registro na tabela de deslocamentos
      delete pd_bilhete where sq_bilhete = w_chave_aux;
   End If;

end SP_PutPD_Bilhete;
/
