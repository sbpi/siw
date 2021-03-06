alter procedure Sp_GetGPColaborador
   (@p_cliente                 int,
    @p_chave                   int    = null,
    @p_nome                    varchar(60)  = null,
    @p_ativo                   varchar(1)  = null,
    @p_modalidade_contrato     int    = null,
    @p_unidade_lotacao         int    = null,
    @p_filhos_lotacao          varchar(1)  = null,
    @p_unidade_exercicio       int    = null,
    @p_filhos_exercicio        varchar(1)  = null,
    @p_afastamento             varchar(1000)  = null,
    @p_dt_ini                  datetime      = null,
    @p_dt_fim                  datetime      = null,
    @p_ferias                  varchar(1)  = null,
    @p_viagem                  varchar(1)  = null,
    @p_chave_aux               int    = null,
    @p_restricao               varchar(20)  = null
    ) as
    
    declare @l_item   varchar;
    declare @l_afastamento varchar;
    declare @x_afastamento varchar;
    set @l_afastamento = replace(@p_afastamento,' ','') +',';
    set @x_afastamento = '';
    
begin
   
   If @p_afastamento is not null begin

      while(@l_afastamento is not null) begin
        set @l_item  = ltrim(rtrim(substring(@l_afastamento,1,charindex(',',@l_afastamento))-1));
         If len(@l_item) > 0 begin
            set @x_afastamento = @x_afastamento+','''+ cast(@l_item as numeric) + '''';
         End 
             set @l_afastamento = substring(@l_afastamento,charindex(',',@l_afastamento)+1,200);
         
      End

          set @x_afastamento = substring(@x_afastamento,2,200);
   End
   
   If @p_restricao is null begin
      -- Recupera todos ou um colaborador
       
         select a.sq_pessoa chave, a.ctps_numero, a.ctps_serie, a.ctps_emissor, a.ctps_emissao_data,
                a.pis_pasep, a.pispasep_numero, a.pispasep_cadastr, a.te_numero, a.te_zona, a.te_secao,
                a.tipo_sangue, a.doador_sangue, a.doador_orgaos, a.reservista_csm, a.reservista_numero,
                a.observacoes, b.nome, b.nome_resumido, b.sq_tipo_vinculo, c.nascimento, c.rg_numero, c.rg_emissor, c.rg_emissao,
                c.cpf, c.sq_cidade_nasc, c.passaporte_numero, c.sq_pais_passaporte, c.sq_etnia, c.sq_deficiencia,
                e.sq_modalidade_contrato, e.sq_contrato_colaborador, d.email, e.inicio, e.fim,
                e.sq_posto_trabalho, e.sq_unidade_lotacao, e.sq_unidade_exercicio, e.sq_modalidade_contrato,
                e.matricula, e.sq_localizacao,
                f.nome localizacao, f.fax, f.telefone, f.ramal, f.telefone2,
                g.nome unidade, g.sigla, g.email email_unidade,
                h.logradouro endereco, (i.nome+'-'+i.co_uf) Cidade, i.ddd
           from gp_colaborador                                a
                  inner          join co_pessoa               b on (a.sq_pessoa      = b.sq_pessoa)
                    left outer   join co_pessoa_fisica        c on (a.sq_pessoa      = c.sq_pessoa)
                    left outer   join (select w.sq_pessoa, w.logradouro email
                                         from co_pessoa_endereco            w
                                              inner   join co_tipo_endereco x on (w.sq_tipo_endereco   = x.sq_tipo_endereco)
                                              inner   join co_pessoa        y on (w.sq_pessoa          = y.sq_pessoa)
                                                inner join co_tipo_pessoa   z on (y.sq_tipo_pessoa     = z.sq_tipo_pessoa)
                                        where x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                                          and x.email              = 'S'
                                          and x.ativo              = 'S'
                                          and w.padrao             = 'S'
                                      )                       d on (b.sq_pessoa      = d.sq_pessoa)
                    left outer   join (select w.sq_pessoa, w.sq_pessoa_endereco, w.sq_cidade, w.logradouro
                                         from co_pessoa_endereco          w
                                              inner join co_tipo_endereco x on (w.sq_tipo_endereco   = x.sq_tipo_endereco)
                                              inner join co_pessoa        y on (w.sq_pessoa          = y.sq_pessoa)
                                              inner join co_tipo_pessoa   z on (y.sq_tipo_pessoa     = z.sq_tipo_pessoa)
                                        where x.sq_tipo_pessoa     = z.sq_tipo_pessoa
                                          and x.nome               = 'Comercial'
                                          and x.ativo              = 'S'
                                          and w.padrao             = 'S'
                                      )                       h on (b.sq_pessoa_pai = h.sq_pessoa)
                  left outer     join co_cidade               i on (h.sq_cidade     = i.sq_cidade)
                  inner          join gp_contrato_colaborador e on (a.sq_pessoa      = e.sq_pessoa and
                                                                    e.fim            is null)
                    left outer   join eo_localizacao          f on (e.sq_localizacao = f.sq_localizacao) 
                      left outer join eo_unidade              g on (f.sq_unidade     = g.sq_unidade)                                             
          where a.cliente  = @p_cliente
            and ((@p_chave  is null) or (@p_chave is not null and a.sq_pessoa    = @p_chave))
            and ((@p_nome   is null) or (@p_nome  is not null and (b.nome_indice like '%'+dbo.acentos(@p_nome)+'%' or b.nome_resumido_ind like '%'+dbo.acentos(@p_nome)+'%')))
            and (@p_ativo       is null or (@p_ativo    = 'S' and e.fim is null));
   end else if @p_restricao = 'AFASTAMENTO' begin
      -- Recupera os colaboradores que est�o ligados a um afastamento
       
         select distinct a.sq_pessoa chave, a.ctps_numero, a.ctps_serie, a.ctps_emissor, a.ctps_emissao_data,
                a.pis_pasep, a.pispasep_numero, a.pispasep_cadastr, a.te_numero, a.te_zona, a.te_secao,
                a.tipo_sangue, a.doador_sangue, a.doador_orgaos, a.reservista_csm, a.reservista_numero,
                a.observacoes, b.nome, b.nome_resumido, c.nascimento, c.rg_numero, c.rg_emissor, c.rg_emissao,
                c.cpf, c.sq_cidade_nasc, c.passaporte_numero, c.sq_pais_passaporte, c.sq_etnia, c.sq_deficiencia,
                e.sq_contrato_colaborador,
                h.sigla+' ('+g.nome+' - R.'+g.ramal+')' local
           from gp_colaborador                          a
                inner      join co_pessoa               b on (a.sq_pessoa = b.sq_pessoa and
                                                              a.cliente   = b.sq_pessoa_pai)
                inner      join co_pessoa_fisica        c on (a.sq_pessoa = c.sq_pessoa)
                inner      join gp_contrato_colaborador e on (a.sq_pessoa = e.sq_pessoa and
                                                              e.fim is null)
                    inner   join eo_localizacao         g on (e.sq_localizacao         = g.sq_localizacao)
                      inner join eo_unidade             h on (g.sq_unidade             = h.sq_unidade)
                  inner    join g@p_afastamento          f on (e.sq_contrato_colaborador = f.sq_contrato_colaborador)
          where a.cliente      = @p_cliente 
            and (@p_chave       is null or (@p_chave    is not null and a.sq_pessoa     = @p_chave))
            and (@p_nome        is null or (@p_nome     is not null and (b.nome_indice like '%'+dbo.acentos(@p_nome)+'%' or b.nome_resumido_ind like '%'+dbo.acentos(@p_nome)+'%')));
   end else if @p_restricao = 'SELAFAST' begin
      -- Recupera todas ou um colaborador
       
         select d.sq_pessoa chave, d.nome, d.nome_resumido, d.nome_resumido_ind, c.sq_contrato_colaborador,
                g.sigla+' ('+f.nome+' - R.'+f.ramal+')' local
           from g@p_tipo_afastamento                        a
                inner       join g@p_afastamento_modalidade b on (a.sq_tipo_afastamento    = b.sq_tipo_afastamento)
                  inner     join gp_contrato_colaborador   c on (b.sq_modalidade_contrato = c.sq_modalidade_contrato and
                                                                 c.fim is null)
                    inner   join eo_localizacao            f on (c.sq_localizacao         = f.sq_localizacao)
                      inner join eo_unidade                g on (f.sq_unidade             = g.sq_unidade)
                    inner   join co_pessoa                 d on (c.sq_pessoa              = d.sq_pessoa)
                      inner join co_pessoa_fisica          e on (d.sq_pessoa              = e.sq_pessoa and
                                                                 e.sexo                   = case a.sexo when 'A' then e.sexo else a.sexo end
                                                                )
          where a.cliente             = @p_cliente
            and a.sq_tipo_afastamento = @p_chave_aux
            and (@p_nome               is null or (@p_nome     is not null and (d.nome_indice like '%'+dbo.acentos(@p_nome)+'%' or d.nome_resumido_ind like '%'+dbo.acentos(@p_nome)+'%')));
   end else if @p_restricao = 'COLABORADOR' begin
      -- Recupera os colaboradores ativos
       
         select distinct a.sq_pessoa chave, a.ctps_numero, a.ctps_serie, a.ctps_emissor, a.ctps_emissao_data,
                a.pis_pasep, a.pispasep_numero, a.pispasep_cadastr, a.te_numero, a.te_zona, a.te_secao,
                a.tipo_sangue, a.doador_sangue, a.doador_orgaos, a.reservista_csm, a.reservista_numero,
                a.observacoes, b.nome, b.nome_resumido, b.nome_resumido_ind, c.nascimento, c.rg_numero, c.rg_emissor, c.rg_emissao,
                c.cpf, c.sq_cidade_nasc, c.passaporte_numero, c.sq_pais_passaporte, c.sq_etnia, c.sq_deficiencia,
                e.sq_contrato_colaborador, e.matricula, h.sigla nm_exercicio, e.inicio, e.fim,
                e.sq_posto_trabalho, e.sq_unidade_lotacao, e.sq_unidade_exercicio, e.sq_modalidade_contrato,
                e.matricula, e.sq_localizacao,
                h.sigla+' ('+g.nome+')' local, g.ramal,
                i.nome nm_modalidade_contrato, c.cpf
           from gp_colaborador                                a
                inner          join co_pessoa                 b on (a.sq_pessoa = b.sq_pessoa and
                                                                    a.cliente   = b.sq_pessoa_pai)
                inner          join co_pessoa_fisica          c on (a.sq_pessoa = c.sq_pessoa)
                inner          join gp_contrato_colaborador   e on (a.sq_pessoa = e.sq_pessoa and
                                                                    e.fim is null)
                  inner        join eo_localizacao            g on (e.sq_localizacao         = g.sq_localizacao)
                    inner      join eo_unidade                h on (g.sq_unidade             = h.sq_unidade)
                  inner        join g@p_modalidade_contrato    i on (e.sq_modalidade_contrato  = i.sq_modalidade_contrato)
          where a.cliente              = @p_cliente 
            and @p_afastamento          is null
            and @p_viagem               is null
            and (@p_chave               is null or (@p_chave               is not null and e.sq_contrato_colaborador = @p_chave))
            and (@p_nome                is null or (@p_nome                is not null and (b.nome_indice like '%'+dbo.acentos(@p_nome)+'%' or b.nome_resumido_ind like '%'+dbo.acentos(@p_nome)+'%')))
            and (@p_modalidade_contrato is null or (@p_modalidade_contrato is not null and e.sq_modalidade_contrato  = @p_modalidade_contrato))
            and (@p_unidade_lotacao     is null or (@p_unidade_lotacao     is not null and ((@p_filhos_lotacao   is null and e.sq_unidade_lotacao   = @p_unidade_lotacao)   or (@p_filhos_lotacao   is not null and e.sq_unidade_lotacao in (select chave from dbo.Sp_fGetUnidade(@p_unidade_lotacao,'DOWN'))))))
                                                                                                                                                                                                                                --  (select sq_unidade 
                                                                                                                                                                                                                                --  from eo_unidade
                                                                                                                                                                                                                                --start with sq_unidade = @p_unidade_lotacao
                                                                                                                                                                                                                                --connect by prior sq_unidade = sq_unidade_pai
                                                                                                                                                                                                                                
                                                                                                                                                                                                                                                                        
            and (@p_unidade_exercicio   is null or (@p_unidade_exercicio   is not null and ((@p_filhos_exercicio is null and e.sq_unidade_exercicio = @p_unidade_exercicio) or (@p_filhos_exercicio is not null and e.sq_unidade_exercicio in (select chave from dbo.Sp_fGetUnidade(@p_unidade_lotacao,'DOWN'))))))
                                                                                                                                                                                                                                  --(select sq_unidade 
                                                                                                                                                                                                                                  --from eo_unidade
                                                                                                                                                                                                                                --start with sq_unidade = @p_unidade_exercicio
                                                                                                                                                                                                                                --connect by prior sq_unidade = sq_unidade_pai
                                                                                                                                                                                                                                 
     UNION
         select distinct a.sq_pessoa chave, a.ctps_numero, a.ctps_serie, a.ctps_emissor, a.ctps_emissao_data,
                a.pis_pasep, a.pispasep_numero, a.pispasep_cadastr, a.te_numero, a.te_zona, a.te_secao,
                a.tipo_sangue, a.doador_sangue, a.doador_orgaos, a.reservista_csm, a.reservista_numero,
                a.observacoes, b.nome, b.nome_resumido, b.nome_resumido_ind, c.nascimento, c.rg_numero, c.rg_emissor, c.rg_emissao,
                c.cpf, c.sq_cidade_nasc, c.passaporte_numero, c.sq_pais_passaporte, c.sq_etnia, c.sq_deficiencia,
                e.sq_contrato_colaborador, e.matricula, h.sigla nm_exercicio, e.inicio, e.fim,
                e.sq_posto_trabalho, e.sq_unidade_lotacao, e.sq_unidade_exercicio, e.sq_modalidade_contrato,
                e.matricula, e.sq_localizacao,
                h.sigla+' ('+g.nome+')' local, g.ramal,
                i.nome nm_modalidade_contrato, c.cpf
           from gp_colaborador                                a
                inner          join co_pessoa                 b on (a.sq_pessoa = b.sq_pessoa and
                                                                    a.cliente   = b.sq_pessoa_pai)
                inner          join co_pessoa_fisica          c on (a.sq_pessoa = c.sq_pessoa)
                inner          join gp_contrato_colaborador   e on (a.sq_pessoa = e.sq_pessoa and
                                                                    e.fim is null)
                  inner        join eo_localizacao            g on (e.sq_localizacao         = g.sq_localizacao)
                    inner      join eo_unidade                h on (g.sq_unidade             = h.sq_unidade)
                  inner        join g@p_modalidade_contrato    i on (e.sq_modalidade_contrato  = i.sq_modalidade_contrato)
                  inner        join g@p_afastamento            f on (e.sq_contrato_colaborador = f.sq_contrato_colaborador)
          where a.cliente              = @p_cliente
            and @p_afastamento          is not null 
            and (@p_chave               is null or (@p_chave               is not null and e.sq_contrato_colaborador = @p_chave))
            and (@p_nome                is null or (@p_nome                is not null and (b.nome_indice like '%'+dbo.acentos(@p_nome)+'%' or b.nome_resumido_ind like '%'+dbo.acentos(@p_nome)+'%')))
            and (@p_modalidade_contrato is null or (@p_modalidade_contrato is not null and e.sq_modalidade_contrato  = @p_modalidade_contrato))
            and (@p_unidade_lotacao     is null or (@p_unidade_lotacao     is not null and ((@p_filhos_lotacao   is null and e.sq_unidade_lotacao   = @p_unidade_lotacao)   or (@p_filhos_lotacao   is not null and e.sq_unidade_lotacao in (select chave from dbo.Sp_fGetUnidade(@p_unidade_lotacao,'DOWN')))))) 
                                                                                                                                                                                                                                --(select sq_unidade 
                                                                                                                                                                                                                                 -- from eo_unidade
                                                                                                                                                                                                                                --start with sq_unidade = @p_unidade_lotacao
                                                                                                                                                                                                                                --connect by prior sq_unidade = sq_unidade_pai                                                                                                                                                                                                                                
            and (@p_unidade_exercicio   is null or (@p_unidade_exercicio   is not null and ((@p_filhos_exercicio is null and e.sq_unidade_exercicio = @p_unidade_exercicio) or (@p_filhos_exercicio is not null and e.sq_unidade_exercicio in (select chave from dbo.Sp_fGetUnidade(@p_unidade_lotacao,'DOWN')))))) 
                                                                                                                                                                                                                                --(select sq_unidade     
                                                                                                                                                                                                                                --  from eo_unidade
                                                                                                                                                                                                                                --start with sq_unidade = @p_unidade_exercicio
                                                                                                                                                                                                                                --connect by prior sq_unidade = sq_unidade_pai                                                                                                                                                                                                                                
           and charindex(''''+f.sq_tipo_afastamento+'''',@x_afastamento) > 0
           and (@p_dt_ini               is null or (@p_dt_ini              is not null and (f.inicio_data between @p_dt_ini and @p_dt_fim) or (f.fim_data between @p_dt_ini and @p_dt_fim)))
     UNION
         select distinct a.sq_pessoa chave, a.ctps_numero, a.ctps_serie, a.ctps_emissor, a.ctps_emissao_data,
                a.pis_pasep, a.pispasep_numero, a.pispasep_cadastr, a.te_numero, a.te_zona, a.te_secao,
                a.tipo_sangue, a.doador_sangue, a.doador_orgaos, a.reservista_csm, a.reservista_numero,
                a.observacoes, b.nome, b.nome_resumido, b.nome_resumido_ind, c.nascimento, c.rg_numero, c.rg_emissor, c.rg_emissao,
                c.cpf, c.sq_cidade_nasc, c.passaporte_numero, c.sq_pais_passaporte, c.sq_etnia, c.sq_deficiencia,
                e.sq_contrato_colaborador, e.matricula, h.sigla nm_exercicio, e.inicio, e.fim,
                e.sq_posto_trabalho, e.sq_unidade_lotacao, e.sq_unidade_exercicio, e.sq_modalidade_contrato,
                e.matricula, e.sq_localizacao,
                h.sigla+' ('+g.nome+')' local, g.ramal,
                i.nome nm_modalidade_contrato, c.cpf
             from gp_colaborador                                a
                inner          join co_pessoa                 b on (a.sq_pessoa = b.sq_pessoa and
                                                                    a.cliente   = b.sq_pessoa_pai)
                inner          join co_pessoa_fisica          c on (a.sq_pessoa = c.sq_pessoa)
                inner          join gp_contrato_colaborador   e on (a.sq_pessoa = e.sq_pessoa and
                                                                    e.fim is null)
                  inner        join eo_localizacao            g on (e.sq_localizacao         = g.sq_localizacao)
                    inner      join eo_unidade                h on (g.sq_unidade             = h.sq_unidade)                                                                    
                  inner        join g@p_modalidade_contrato    i on (e.sq_modalidade_contrato  = i.sq_modalidade_contrato)
                  inner        join pd_missao                 j on (a.sq_pessoa               = j.sq_pessoa)
                    inner      join siw_solicitacao           l on (j.sq_siw_solicitacao      = l.sq_siw_solicitacao)
          where a.cliente              = @p_cliente
            and @p_viagem               is not null 
            and (@p_chave               is null or (@p_chave               is not null and e.sq_contrato_colaborador = @p_chave))
            and (@p_nome                is null or (@p_nome                is not null and (b.nome_indice like '%'+dbo.acentos(@p_nome)+'%' or b.nome_resumido_ind like '%'+dbo.acentos(@p_nome)+'%')))
            and (@p_modalidade_contrato is null or (@p_modalidade_contrato is not null and e.sq_modalidade_contrato  = @p_modalidade_contrato))
            and (@p_unidade_lotacao     is null or (@p_unidade_lotacao     is not null and ((@p_filhos_lotacao   is null and e.sq_unidade_lotacao   = @p_unidade_lotacao)   or (@p_filhos_lotacao   is not null and e.sq_unidade_lotacao in (select chave from dbo.Sp_fGetUnidade(@p_unidade_lotacao,'DOWN'))))))
                                                                                                                                                                                                                                --(select sq_unidade 
                                                                                                                                                                                                                                --from eo_unidade
                                                                                                                                                                                                                                --start with sq_unidade = @p_unidade_lotacao
                                                                                                                                                                                                                                --connect by prior sq_unidade = sq_unidade_pai
                                                                                                                                                                                                                                
            and (@p_unidade_exercicio   is null or (@p_unidade_exercicio   is not null and ((@p_filhos_exercicio is null and e.sq_unidade_exercicio = @p_unidade_exercicio) or (@p_filhos_exercicio is not null and e.sq_unidade_exercicio in (select chave from dbo.Sp_fGetUnidade(@p_unidade_exercicio,'DOWN'))))))
                                                                                                                                                                                                                                --(select sq_unidade 
                                                                                                                                                                                                                                --from eo_unidade
                                                                                                                                                                                                                                --start with sq_unidade = @p_unidade_exercicio
                                                                                                                                                                                                                                --connect by prior sq_unidade = sq_unidade_pai

           and ((l.inicio              between @p_dt_ini and @p_dt_fim) or (l.fim      between @p_dt_ini and @p_dt_fim));
   End 
end 

