create or replace procedure sp_PutXMLOrgao_PPA
   (p_chave     in varchar2 default null,
    p_tipo_org  in varchar2 default null,
    p_nome      in varchar2 default null,
    p_sigla     in varchar2 default null,
    p_ativo     in varchar2 default null
   ) is
   w_cont     number(4);
   w_operacao varchar2(1);
begin
   If p_ativo = 'N' Then
      update is_ppa_orgao set ativo = 'N' 
        where cd_orgao      = p_chave
          and cd_tipo_orgao = p_tipo_org;
   Else
      select count(*) into w_cont from is_ppa_orgao a where a.cd_orgao = p_chave and a.cd_tipo_orgao = p_tipo_org;
      If w_cont = 0 
         Then w_operacao := 'I';
         Else w_operacao := 'A';
      End If;
    
      If w_operacao = 'I' Then
         -- Insere registro
         insert into is_ppa_orgao (cd_orgao, cd_tipo_orgao, nome, sigla, ativo, flag_inclusao)
         values (p_chave, p_tipo_org, p_nome, p_sigla, 'S', sysdate);
      Else
         -- Altera registro
         update is_ppa_orgao set
            cd_tipo_orgao = p_tipo_org,
            nome          = p_nome,
            sigla         = p_sigla,
            ativo         = p_ativo,
            flag_inclusao = sysdate
          where cd_orgao      = p_chave
            and cd_tipo_orgao = p_tipo_org;
      End If;
   End If;
end sp_PutXMLOrgao_PPA;
/
