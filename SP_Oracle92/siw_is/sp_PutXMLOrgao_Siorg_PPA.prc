create or replace procedure sp_PutXMLOrgao_Siorg_PPA
   (p_chave     in number   default null,
    p_pai       in number   default null,
    p_nome      in varchar2 default null,
    p_orgao     in varchar2 default null,
    p_tipo_org  in varchar2 default null,
    p_ativo     in varchar2 default null
   ) is
   w_cont     number(4);
   w_operacao varchar2(1);
   w_orgao    is_ppa_orgao.cd_orgao%type;
   w_tipo_org is_ppa_orgao.cd_tipo_orgao%type;
begin
   If p_ativo = 'N' Then
      update is_ppa_orgao_siorg set ativo = 'N' 
        where cd_orgao_siorg      = p_chave;
   Else
      select count(*) into w_cont from is_ppa_orgao_siorg a where a.cd_orgao_siorg = p_chave;
      If w_cont = 0 
         Then w_operacao := 'I';
         Else w_operacao := 'A';
      End If;
    
      -- Verifica se o órgão existe na tabela IS_PPA_ORGAO. Se não existir, grava nulo.
      select count(*) into w_cont from is_ppa_orgao where cd_orgao = p_orgao and cd_tipo_orgao = p_tipo_org;
      If w_cont = 0 Then
         w_orgao    := null;
         w_tipo_org := null;
      End If;
      
      If w_operacao = 'I' Then
         -- Insere registro
         insert into is_ppa_orgao_siorg (cd_orgao_siorg, cd_orgao_siorg_pai, cd_orgao, cd_tipo_orgao, nome, ativo, flag_inclusao)
         values (p_chave, p_pai, w_orgao, w_tipo_org, p_nome, 'S', sysdate);
      Else
         -- Altera registro
         update is_ppa_orgao_siorg set
            cd_orgao_siorg_pai = p_pai,
            cd_orgao           = w_orgao,
            cd_tipo_orgao      = w_tipo_org,
            nome               = p_nome,
            ativo              = p_ativo
          where cd_orgao_siorg      = p_chave;
      End If;
   End If;
end sp_PutXMLOrgao_Siorg_PPA;
/
