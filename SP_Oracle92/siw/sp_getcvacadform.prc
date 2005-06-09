create or replace procedure SP_GetCVAcadForm
   (p_usuario   in number,
    p_chave     in number    default null,
    p_tipo      in varchar2,
    p_result    out sys_refcursor) is
begin
   If p_tipo = 'ACADEMICA' Then
      -- Recupera os dados de formação acadêmica do colaborador
      open p_result for 
         select a.sq_cvpessoa_escol, a.sq_pessoa, a.sq_area_conhecimento, 
                a.sq_pais, a.sq_formacao, a.nome, a.instituicao, a.inicio, a.fim,
                b.nome nm_area, b.codigo_cnpq,
                c.nome nm_formacao, c.ordem,
                d.nome nm_pais
           from cv_pessoa_escol                      a
                left outer join co_area_conhecimento b on (a.sq_area_conhecimento = b.sq_area_conhecimento)
                inner      join co_formacao          c on (a.sq_formacao          = c.sq_formacao)
                inner      join co_pais              d on (a.sq_pais              = d.sq_pais)
          where a.sq_pessoa         = p_usuario
            and ((p_chave           is null) or (p_chave is not null and a.sq_cvpessoa_escol = p_chave));
   Elsif p_tipo = 'CURSO' Then
      -- Recupera os cursos de extensão do colaborador
      open p_result for 
         select a.sq_cvpescurtec, a.sq_pessoa, a.sq_area_conhecimento, 
                a.sq_formacao, a.nome, a.instituicao, a.carga_horaria, a.conclusao,
                b.nome nm_area, b.codigo_cnpq, 
                c.nome nm_formacao, c.ordem
           from cv_pessoa_curso                      a
                inner join co_area_conhecimento b on (a.sq_area_conhecimento = b.sq_area_conhecimento)
                inner join co_formacao          c on (a.sq_formacao          = c.sq_formacao)
          where a.sq_pessoa         = p_usuario
            and ((p_chave           is null) or (p_chave is not null and a.sq_cvpescurtec= p_chave));
   Elsif p_tipo = 'PRODUCAO' Then
      -- Recupera os cursos de extensão do colaborador
      open p_result for 
         select a.sq_cvpessoa_prod, a.sq_pessoa, a.sq_area_conhecimento, 
                a.sq_formacao, a.nome, a.meio, a.data,
                b.nome nm_area, b.codigo_cnpq, 
                c.nome nm_formacao, c.ordem
           from cv_pessoa_prod                  a
                inner join co_area_conhecimento b on (a.sq_area_conhecimento = b.sq_area_conhecimento)
                inner join co_formacao          c on (a.sq_formacao          = c.sq_formacao)
          where a.sq_pessoa         = p_usuario
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
           from cv_pessoa_exp                   a
                inner join co_area_conhecimento b  on (a.sq_area_conhecimento = b.sq_area_conhecimento)
                inner join co_cidade            c  on (a.sq_cidade            = c.sq_cidade)
                  inner join co_uf              c1 on (c.co_uf                = c1.co_uf)
                  inner join co_pais            c2 on (c.sq_pais              = c2.sq_pais)
                left outer join eo_tipo_posto   d  on (a.sq_eo_tipo_posto     = d.sq_eo_tipo_posto)
                left outer join co_tipo_vinculo e  on (a.sq_tipo_vinculo      = e.sq_tipo_vinculo)
          where a.sq_pessoa         = p_usuario
            and ((p_chave           is null) or (p_chave is not null and a.sq_cvpesexp = p_chave));
    Elsif p_tipo = 'CARGO' Then
      -- Recupera os cargos de uma experiencia profissional(p_usuario é usado como a chave da experiencia profissional)
      open p_result for 
         select a.sq_cvpescargo, a.sq_cvpesexp, a.sq_area_conhecimento, 
                a.especialidades, a.inicio, a.fim,
                b.nome nm_area, b.codigo_cnpq
           from cv_pessoa_cargo                 a
                inner join co_area_conhecimento b on (a.sq_area_conhecimento = b.sq_area_conhecimento)
          where a.sq_cvpesexp       = p_usuario
            and ((p_chave           is null) or (p_chave is not null and a.sq_cvpescargo = p_chave));
   End If;
end SP_GetCVAcadForm;
/

