create or replace procedure sp_PutXMLFonte_PPA
   (p_chave    in  number   default null,
    p_nome     in  varchar2,
    p_desc     in  varchar2 default null,
    p_total    in  varchar2 default null
   ) is
   w_cont     number(4);
   w_operacao varchar2(1);
begin
   select count(*) into w_cont from is_ppa_fonte a where a.cd_fonte = p_chave;
   If w_cont = 0 
      Then w_operacao := 'I';
      Else w_operacao := 'A';
   End If;
  
   If w_operacao = 'I' Then
      -- Insere registro
      insert into is_ppa_fonte (cd_fonte, nome, descricao, totalizacao, flag_inclusao)
      values (p_chave, p_nome, Nvl(p_desc,'Não informado.'), p_total, sysdate);
   Else
      -- Altera registro
      update is_ppa_fonte set
         nome          = p_nome,
         descricao     = Nvl(p_desc,descricao),
         totalizacao   = p_total
       where cd_fonte = p_chave;
   End If;
end sp_PutXMLFonte_PPA;
/
