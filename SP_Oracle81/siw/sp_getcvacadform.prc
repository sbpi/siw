create or replace procedure SP_GetCVAcadForm
   (p_usuario   in number,
    p_chave     in number    default null,
    p_tipo      in varchar2,
    p_result    out siw.sys_refcursor) is
begin
   If p_tipo = 'ACADEMICA' Then
      -- Recupera os dados de formação acadêmica do colaborador
      open p_result for
         select a.sq_cvpessoa_escol, a.sq_pessoa, a.sq_area_conhecimento,
                a.sq_pais, a.sq_formacao, a.nome, a.instituicao, a.inicio, a.fim,
                b.nome nm_area, b.codigo_cnpq,
                c.nome nm_formacao, c.ordem,
                d.nome nm_pais
           from cv_pessoa_escol                      a,
                co_area_conhecimento b,
                co_formacao          c,
                co_pais              d
          where (a.sq_area_conhecimento = b.sq_area_conhecimento (+))
            and (a.sq_formacao          = c.sq_formacao)
            and (a.sq_pais              = d.sq_pais)
            and a.sq_pessoa         = p_usuario
            and ((p_chave           is null) or (p_chave is not null and a.sq_cvpessoa_escol = p_chave));
   Elsif p_tipo = 'CURSO' Then
      -- Recupera os cursos de extensão do colaborador
      open p_result for
         select a.sq_cvpescurtec, a.sq_pessoa, a.sq_area_conhecimento,
                a.sq_formacao, a.nome, a.instituicao, a.carga_horaria, a.conclusao,
                b.nome nm_area, b.codigo_cnpq,
                c.nome nm_formacao, c.ordem
           from cv_pessoa_curso                 a,
                co_area_conhecimento b,
                co_formacao          c
          where (a.sq_area_conhecimento = b.sq_area_conhecimento)
            and (a.sq_formacao          = c.sq_formacao)
            and a.sq_pessoa         = p_usuario
            and ((p_chave           is null) or (p_chave is not null and a.sq_cvpescurtec= p_chave));
   Elsif p_tipo = 'PRODUCAO' Then
      -- Recupera os cursos de extensão do colaborador
      open p_result for
         select a.sq_cvpessoa_prod, a.sq_pessoa, a.sq_area_conhecimento,
                a.sq_formacao, a.nome, a.meio, a.data,
                b.nome nm_area, b.codigo_cnpq,
                c.nome nm_formacao, c.ordem
           from cv_pessoa_prod                  a,
                co_area_conhecimento b,
                co_formacao          c
          where (a.sq_area_conhecimento = b.sq_area_conhecimento)
            and (a.sq_formacao          = c.sq_formacao)
            and a.sq_pessoa         = p_usuario
            and ((p_chave           is null) or (p_chave is not null and a.sq_cvpessoa_prod= p_chave));
   Elsif p_tipo = 'EXPERIENCIA' Then
      -- Recupera as experiências profissionais do colaborador
      open p_result for
         select a.sq_cvpesexp, a.sq_pessoa, a.sq_area_conhecimento,
                a.sq_cidade, a.sq_eo_tipo_posto, a.sq_tipo_vinculo,
                a.empregador, a.entrada, a.saida, a.duracao_mes,
                a.duracao_ano, a.motivo_saida, a.ultimo_salario, a.atividades,
                b.nome nm_area, b.codigo_cnpq, c.sq_pais, c.co_uf,
                c.nome nm_cidade, c1.nome nm_estado, c2.nome nm_pais,
                d.nome nm_tipo_posto, d.descricao ds_tipo_posto
           from cv_pessoa_exp        a,
                co_area_conhecimento b,
                co_cidade            c,
                  co_uf              c1,
                  co_pais            c2,
                eo_tipo_posto   d,
                co_tipo_vinculo e
          where (a.sq_area_conhecimento = b.sq_area_conhecimento)
            and (a.sq_cidade            = c.sq_cidade)
            and (c.co_uf                = c1.co_uf)
            and (c.sq_pais              = c2.sq_pais)
            and (a.sq_eo_tipo_posto     = d.sq_eo_tipo_posto (+))
            and (a.sq_tipo_vinculo      = e.sq_tipo_vinculo (+))
            and a.sq_pessoa         = p_usuario
            and ((p_chave           is null) or (p_chave is not null and a.sq_cvpesexp = p_chave));
    Elsif p_tipo = 'CARGO' Then
      -- Recupera os cargos de uma experiencia profissional(p_usuario é usado como a chave da experiencia profissional)
      open p_result for
         select a.sq_cvpescargo, a.sq_cvpesexp, a.sq_area_conhecimento,
                a.especialidades, a.inicio, a.fim,
                b.nome nm_area, b.codigo_cnpq
           from cv_pessoa_cargo                 a,
                co_area_conhecimento b
          where (a.sq_area_conhecimento = b.sq_area_conhecimento)
            and a.sq_cvpesexp       = p_usuario
            and ((p_chave           is null) or (p_chave is not null and a.sq_cvpescargo = p_chave));
   End If;
end SP_GetCVAcadForm;
/

