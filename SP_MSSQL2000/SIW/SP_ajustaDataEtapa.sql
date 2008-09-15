alter procedure dbo.SP_ajustaDataEtapa(
    @p_projeto int, 
    @p_todos   varchar(255) = null
    ) as
Begin
  Declare @w_existe    int;
  Declare @w_inicio    datetime
  Set     @w_inicio    = null;
  Declare @sq_siw_solicitacao numeric(18)

  -- Cursor que recupera todos os projetos
  Declare c_proj cursor for
    select a.sq_siw_solicitacao
           from pj_projeto a
                inner join pj_projeto_etapa b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao);

  Declare c_pacotes cursor for
     select a.sq_etapa_pai, a.inicio_real, a.fim_real
       from pj_projeto_etapa a
      where a.sq_siw_solicitacao = @p_projeto
        and a.inicio_real        is not null
        and a.pacote_trabalho    = 'S';

  Declare @w_etapa numeric(18);

  Declare c_pais cursor for
     select a.sq_projeto_etapa
       from pj_projeto_etapa a
       where a.sq_projeto_etapa in (select chave from dbo.sp_fGetProjetoEtapa(@w_etapa , 'UP'))
  -- Verifica se o projeto existe
  select @w_existe  = count(sq_siw_solicitacao) from pj_projeto where sq_siw_solicitacao = coalesce(@p_projeto,0);
  If @w_existe = 0 and coalesce(@p_todos,'nulo') <> 'TODOS' Begin
     return;
  End Else If coalesce(@p_todos,'nulo') = 'TODOS' Begin
     

    -- Atualiza as datas de todos os projetos
    Open c_proj
        Fetch Next from c_proj into @sq_siw_solicitacao
        While @@fetch_status = 0 Begin
            exec SP_ajustadataEtapa @sq_siw_solicitacao;
            Fetch Next from c_proj into @sq_siw_solicitacao
        End
        Close c_proj
        Deallocate c_proj
    End
  
  -- Reinicializa as datas das etapas que não são pacote de trabalho
  update pj_projeto_etapa set inicio_real = null, fim_real = null where pacote_trabalho = 'N' and sq_siw_solicitacao = @p_projeto;
    
    Declare @sq_etapa_pai     numeric(18); 
    Declare @inicio_real      datetime; 
    Declare @fim_real         datetime;
    Declare @sq_projeto_etapa numeric(18);
    
    open c_pacotes
        fetch next from c_pacotes into @sq_etapa_pai, @inicio_real, @fim_real;
        While @@fetch_status = 0 Begin
     -- Ajusta a data de início das etapas
            Open c_pais
                fetch next from c_pais into @sq_projeto_etapa
                While @@fetch_status = 0 Begin
                    update pj_projeto_etapa set inicio_real = @inicio_real 
                    where (inicio_real is null or inicio_real > @inicio_real) 
                        and sq_projeto_etapa = @sq_projeto_etapa;
                    If  @w_inicio is null or @w_inicio > @inicio_real Begin 
                        Set @w_inicio = @inicio_real; 
                    End
                    fetch next from c_pais into @sq_projeto_etapa    
                End
            Close      c_pais
            Deallocate c_pais

     Set @w_etapa = @sq_etapa_pai;
     -- Ajusta a data de término das etapas
            Open c_pais
                fetch next from c_pais into @sq_projeto_etapa
                While @@fetch_status = 0 Begin
                    update pj_projeto_etapa set fim_real = @fim_real 
                    where (fim_real is null or fim_real < @fim_real) 
                    and 0 = (select count(*) from pj_projeto_etapa where pacote_trabalho = 'S'
                    and fim_real is null
                    and 0 < dbo.sp_fCountProjetoEtapa(sq_projeto_etapa , 'DOWN'))
                    and sq_projeto_etapa = @sq_projeto_etapa;
                    fetch next from c_pais into @sq_projeto_etapa                
                End 
            Close      c_pais
            Deallocate c_pais
     -- Ajusta o início real do projeto
     if @w_inicio is not null Begin
        update pj_projeto set inicio_real = @w_inicio where sq_siw_solicitacao = @p_projeto;
     end
    fetch next from c_pacotes into @sq_etapa_pai, @inicio_real, @fim_real;
 End
    close      c_pacotes
    deallocate c_pacotes
end
