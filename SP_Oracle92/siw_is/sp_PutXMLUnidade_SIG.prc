create or replace procedure sp_PutXMLUnidade_SIG
   (p_ano       in  number   default null,
    p_chave     in  number   default null,
    p_tipo_unid in  varchar2 default null,
    p_orgao     in  varchar2 default null,
    p_tipo_org  in varchar2  default null,
    p_nome      in  varchar2
   ) is
   w_cont     number(4);
   w_operacao varchar2(1);
begin
   select count(*) into w_cont 
     from is_sig_unidade a 
    where a.ano             = p_ano
      and a.cd_unidade      = p_chave 
      and a.cd_tipo_unidade = p_tipo_unid
      and a.cd_orgao        = p_orgao
      and a.cd_tipo_orgao   = p_tipo_org;
   If w_cont = 0 
      Then w_operacao := 'I';
      Else w_operacao := 'A';
   End If;
  
   If w_operacao = 'I' Then
      -- Insere registro
      insert into is_sig_unidade (ano, cd_unidade, cd_tipo_unidade, cd_orgao, cd_tipo_orgao, nome, flag_inclusao)
      values (p_ano, p_chave, p_tipo_unid, p_orgao, p_tipo_org, p_nome, sysdate);
   Else
      -- Altera registro
      update is_sig_unidade set
         nome            = p_nome
       where ano             = p_ano
         and cd_unidade      = p_chave
         and cd_tipo_unidade = p_tipo_unid
         and cd_orgao        = p_orgao
         and cd_tipo_orgao   = p_tipo_org;
   End If;
end sp_PutXMLUnidade_SIG;
/
