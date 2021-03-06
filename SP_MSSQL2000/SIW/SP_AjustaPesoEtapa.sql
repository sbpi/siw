set ANSI_NULLS ON
set QUOTED_IDENTIFIER ON
GO
ALTER procedure [dbo].[sp_ajustaPesoEtapa](
    @p_projeto int       = null, 
    @p_pai     int       = null, 
    @p_todos   varchar(2000) = null
    ) as
    
    Declare @w_existe int;
    Declare @sq_projeto_etapa int;
    Declare @peso_projeto float;
    Declare @peso_pai float;
Begin  
  -- Cursor que recupera todos os projetos
If cursor_status('global', 'c_projetos') <> -3 Begin
    Deallocate c_projetos;
End
  Declare c_projetos cursor for
    select a.sq_siw_solicitacao
           from pj_projeto a
                inner join pj_projeto_etapa b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao);

If cursor_status('global', 'c_raiz') <> -3 Begin
    Deallocate c_raiz;
End
  Declare c_raiz cursor for
    select a.sq_projeto_etapa, a.peso/(case b.peso when 0 then 1 else b.peso end) as peso_projeto
           from pj_projeto_etapa a,
                (select sum(peso) as peso
                   from pj_projeto_etapa
                  where sq_siw_solicitacao = @p_projeto
                    and sq_etapa_pai       is null
                )                b
          where a.sq_siw_solicitacao = @p_projeto
            and a.sq_etapa_pai       is null;
If cursor_status('global', 'c_niv') <> -1 and cursor_status('global', 'c_niv') <> -3 Begin
    Deallocate c_niv;
End 
  Declare c_niv cursor for
    select a.sq_projeto_etapa, 
           a.peso/(case b.peso when 0 then 1 else b.peso end) as peso_pai, 
           (a1.peso_projeto * (a.peso/(case b.peso when 0 then 1 else b.peso end))) as peso_projeto
           from pj_projeto_etapa            a
                inner join pj_projeto_etapa a1 on (a.sq_etapa_pai = a1.sq_projeto_etapa),
                (select sum(peso) as peso
                   from pj_projeto_etapa
                  where sq_siw_solicitacao = @p_projeto
                    and sq_etapa_pai       = @p_pai
                )                b
          where a.sq_siw_solicitacao = @p_projeto
            and a.sq_etapa_pai       = @p_pai;
--begin
  If @p_projeto is null Begin
    If coalesce(@p_todos,'nulo') <> 'TODOS' Begin
       return;
    End Else Begin
        Declare @sq_siw_solicitacao int;
       -- Atualiza os pesos relativos de todos os projetos
        Open c_projetos
            Fetch Next From c_projetos Into @sq_siw_solicitacao
            While @@fetch_status = 0 Begin
                exec  sp_ajustaPesoEtapa        @sq_siw_solicitacao,null;
                Fetch Next From c_projetos Into @sq_siw_solicitacao
            End
        Close      c_projetos
        Deallocate c_projetos
    End
  End Else If @p_projeto is not null Begin
     select @w_existe = count(sq_siw_solicitacao) from pj_projeto where sq_siw_solicitacao = @p_projeto;
     If @w_existe = 0 Begin
        return;
     End
  End
  
  if @p_pai is null Begin
    Open c_raiz
        Fetch Next From c_raiz into @sq_projeto_etapa, @peso_projeto
        While @@Fetch_Status = 0 Begin
            update pj_projeto_etapa set peso_pai = peso, peso_projeto = @peso_projeto where sq_projeto_etapa = @sq_projeto_etapa;
            exec sp_ajustaPesoEtapa @p_projeto, @sq_projeto_etapa;        
            Fetch Next From c_raiz into @sq_projeto_etapa, @peso_projeto
        End
    Close      c_raiz
    Deallocate c_raiz

  End Else Begin
    Open c_niv
        Fetch Next From c_niv Into @sq_projeto_etapa, @peso_pai, @peso_projeto
        While @@Fetch_Status = 0 Begin
            update pj_projeto_etapa set peso_pai = @peso_pai, peso_projeto = @peso_projeto where sq_projeto_etapa = @sq_projeto_etapa;
            exec sp_ajustaPesoEtapa @p_projeto, @sq_projeto_etapa;
            Fetch Next From c_niv Into @sq_projeto_etapa, @peso_pai, @peso_projeto;
        End
    Close      c_niv
    Deallocate c_niv
  end
end


