create or replace procedure sp_PutXMLMunicipio
   (p_chave     in varchar2 default null,
    p_regiao    in varchar2 default null,
    p_nome      in varchar2 default null
   ) is
   w_cont      number(4);
   w_operacao  varchar2(1);
   w_sq_cidade siw.co_cidade.sq_cidade%type;
begin
   select count(*) into w_cont from is_municipio a where a.cd_municipio = p_chave;
   If w_cont = 0 
      Then w_operacao := 'I';
      Else w_operacao := 'A';
   End If;
  
   -- Tenta localizar a cidade em SIW.CO_CIDADE
   select count(*) into w_cont
     from siw.co_cidade a
    where siw.acentos(a.nome) = siw.acentos(p_nome)
      and a.co_uf             = p_regiao;
     
   If w_cont > 0 Then
      select sq_cidade into w_sq_cidade
        from siw.co_cidade a
       where siw.acentos(a.nome) = siw.acentos(p_nome)
         and a.co_uf             = p_regiao;
   Else
      w_sq_cidade := null;
   End If;
     
   If w_operacao = 'I' Then
      -- Insere registro
      insert into is_municipio (cd_municipio, cd_regiao, sq_cidade, nome, flag_inclusao)
      values (p_chave, p_regiao, w_sq_cidade, p_nome, sysdate);
   Else
      -- Altera registro
      update is_municipio set
         cd_regiao     = p_regiao,
         sq_cidade     = w_sq_cidade,
         nome          = p_nome,
         flag_inclusao = sysdate
       where cd_municipio      = p_chave;
   End If;
end sp_PutXMLMunicipio;
/
