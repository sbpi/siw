create or replace procedure SP_GetSchoolList
   (p_cliente     in number,
    p_result      out sys_refcursor
   ) is
begin
   -- Recupera a lista de escolas
   open p_result for
      select a.co_unidade, a.ds_escola, a.co_sigre, a.ds_endereco, a.ds_bairro, a.nu_cep,
             a.ds_cidade, a.ds_uf_cidade, a.ds_gre,
             b.ds_unidade, b.tp_escola, b.ds_nome_relatorio, b.ds_vinheta, b.nu_telefone_1,
             b.nu_telefone_2, b.nu_fax, b.ds_e_mail, b.ds_ato, b.ds_numero, b.dt_data,
             b.ds_orgao, b.ds_grade_curric, b.nu_cgc_escola, b.nu_inscr_escola,
             b.ds_diretor, b.ds_secretario, b.dt_atualizacao, b.ds_rural,
             b.nu_remessa, b.nu_alunosativos, b.nu_matriculados, b.nu_ativos
        from s_escola  a,
             s_unidade b
        where a.co_unidade = b.co_unidade;
end SP_GetSchoolList;
/

