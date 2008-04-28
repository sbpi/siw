CREATE OR REPLACE FUNCTION siw.SP_GetCVAcadForm
   (p_usuario   numeric,
    p_chave     numeric,
    p_tipo      varchar)
  RETURNS refcursor AS
$BODY$declare
    p_result    refcursor;
begin
   If p_tipo = 'ACADEMICA' Then
      -- Recupera os dados de formação acadêmica do colaborador
      open p_result for
         select a.sq_cvpessoa_escol, a.sq_pessoa, a.sq_area_conhecimento,
                a.sq_pais, a.sq_formacao, a.nome, a.instituicao, a.inicio, a.fim,
                b.nome as nm_area, b.codigo_cnpq,
                c.nome as nm_formacao, c.ordem,
                d.nome as nm_pais
           from siw.cv_pessoa_escol                      a
                left outer join siw.co_area_conhecimento b on (a.sq_area_conhecimento = b.sq_area_conhecimento)
                inner      join siw.co_formacao          c on (a.sq_formacao          = c.sq_formacao)
                inner      join siw.co_pais              d on (a.sq_pais              = d.sq_pais)
          where a.sq_pessoa         = p_usuario
            and ((p_chave           is null) or (p_chave is not null and a.sq_cvpessoa_escol = p_chave));
   Elsif p_tipo = 'CURSO' Then
      -- Recupera os cursos de extensão do colaborador
      open p_result for
         select a.sq_cvpescurtec, a.sq_pessoa, a.sq_area_conhecimento,
                a.sq_formacao, a.nome, a.instituicao, a.carga_horaria, a.conclusao,
                b.nome as nm_area, b.codigo_cnpq,
                c.nome as nm_formacao, c.ordem
           from siw.cv_pessoa_curso                      a
                inner join siw.co_area_conhecimento b on (a.sq_area_conhecimento = b.sq_area_conhecimento)
                inner join siw.co_formacao          c on (a.sq_formacao          = c.sq_formacao)
          where a.sq_pessoa         = p_usuario
            and ((p_chave           is null) or (p_chave is not null and a.sq_cvpescurtec= p_chave));
   Elsif p_tipo = 'PRODUCAO' Then
      -- Recupera os cursos de extensão do colaborador
      open p_result for
         select a.sq_cvpessoa_prod, a.sq_pessoa, a.sq_area_conhecimento,
                a.sq_formacao, a.nome, a.meio, a.data,
                b.nome as nm_area, b.codigo_cnpq,
                c.nome as nm_formacao, c.ordem
           from siw.cv_pessoa_prod                  a
                inner join siw.co_area_conhecimento b on (a.sq_area_conhecimento = b.sq_area_conhecimento)
                inner join siw.co_formacao          c on (a.sq_formacao          = c.sq_formacao)
          where a.sq_pessoa         = p_usuario
            and ((p_chave           is null) or (p_chave is not null and a.sq_cvpessoa_prod= p_chave));
   Elsif p_tipo = 'EXPERIENCIA' Then
      -- Recupera as experiências profissionais do colaborador
      open p_result for
         select a.sq_cvpesexp, a.sq_pessoa, a.sq_area_conhecimento,
                a.sq_cidade, a.sq_eo_tipo_posto, a.sq_tipo_vinculo,
                a.empregador, a.entrada, a.saida, a.duracao_mes,
                a.duracao_ano, a.motivo_saida, a.ultimo_salario, a.atividades,
                b.nome as nm_area, b.codigo_cnpq, c.sq_pais, c.co_uf,
                c.nome as nm_cidade, c1.nome as nm_estado, c2.nome as nm_pais,
                d.nome as nm_tipo_posto, d.descricao as ds_tipo_posto
           from siw.cv_pessoa_exp                   a
                inner join siw.co_area_conhecimento b  on (a.sq_area_conhecimento = b.sq_area_conhecimento)
                inner join siw.co_cidade            c  on (a.sq_cidade            = c.sq_cidade)
                  inner join siw.co_uf              c1 on (c.co_uf                = c1.co_uf)
                  inner join siw.co_pais            c2 on (c.sq_pais              = c2.sq_pais)
                left outer join siw.eo_tipo_posto   d  on (a.sq_eo_tipo_posto     = d.sq_eo_tipo_posto)
                left outer join siw.co_tipo_vinculo e  on (a.sq_tipo_vinculo      = e.sq_tipo_vinculo)
          where a.sq_pessoa         = p_usuario
            and ((p_chave           is null) or (p_chave is not null and a.sq_cvpesexp = p_chave));
    Elsif p_tipo = 'CARGO' Then
      -- Recupera os cargos de uma experiencia profissional(p_usuario é usado como a chave da experiencia profissional)
      open p_result for
         select a.sq_cvpescargo, a.sq_cvpesexp, a.sq_area_conhecimento,
                a.especialidades, a.inicio, a.fim,
                b.nome as nm_area, b.codigo_cnpq
           from siw.cv_pessoa_cargo                 a
                inner join siw.co_area_conhecimento b on (a.sq_area_conhecimento = b.sq_area_conhecimento)
          where a.sq_cvpesexp       = p_usuario
            and ((p_chave           is null) or (p_chave is not null and a.sq_cvpescargo = p_chave));
   End If;
   return p_result;
end 
$BODY$
  LANGUAGE 'plpgsql' VOLATILE
  COST 100;
ALTER FUNCTION siw.Sp_GetColuna
   (p_cliente      numeric,
    p_chave        numeric, 
    p_sq_tabela    numeric,
    p_sq_dado_tipo varchar,
    p_sq_sistema   numeric, 
    p_sq_usuario   numeric, 
    p_nome         varchar,
    p_esq_tab      numeric) OWNER TO siw;
