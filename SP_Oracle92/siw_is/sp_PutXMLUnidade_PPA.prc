create or replace procedure sp_PutXMLUnidade_PPA
   (p_chave     in  number   default null,
    p_tipo_unid in  varchar2 default null,
    p_orgao     in  varchar2 default null,
    p_tipo_org  in varchar2  default null,
    p_nome      in  varchar2
   ) is
   w_cont     number(4);
   w_operacao varchar2(1);
begin
   select count(*) into w_cont from is_ppa_unidade a where a.cd_unidade = p_chave and a.cd_tipo_unidade = p_tipo_unid;
   If w_cont = 0 
      Then w_operacao := 'I';
      Else w_operacao := 'A';
   End If;
  
   If w_operacao = 'I' Then
      -- Insere registro
      insert into is_ppa_unidade (cd_unidade, cd_tipo_unidade, cd_orgao, cd_tipo_orgao, nome, flag_inclusao)
      values (p_chave, p_tipo_unid, p_orgao, p_tipo_org, p_nome, sysdate);
   Else
      -- Altera registro
      update is_ppa_unidade set
         cd_tipo_unidade = p_tipo_unid,
         cd_orgao        = p_orgao,
         cd_tipo_orgao   = p_tipo_org,
         nome            = p_nome,
         flag_inclusao = sysdate
       where cd_unidade      = p_chave
         and cd_tipo_unidade = p_tipo_unid;
   End If;
end sp_PutXMLUnidade_PPA;
/
