alter procedure sp_calculaPercEtapa(
    @p_chave int, 
    @p_pai   int = null
    ) as
  Declare @w_chave_pai int; 
  Set     @w_chave_pai = @p_pai;
begin
  If @p_chave is not null Begin
     select @w_chave_pai  = sq_etapa_pai from pj_projeto_etapa where sq_projeto_etapa = @p_chave;
  End
  
  if @w_chave_pai is not null Begin
     update pj_projeto_etapa set
       perc_conclusao = (select coalesce(sum(b.perc_conclusao*b.peso)/(case sum(b.peso) when 0 then count(b.sq_projeto_etapa) else sum(b.peso) end),0)
                             from pj_projeto_etapa            a
                                  inner join pj_projeto_etapa b on (a.sq_projeto_etapa = b.sq_etapa_pai)
                            where a.sq_projeto_etapa = @w_chave_pai
                          )
     where sq_projeto_etapa = @w_chave_pai;
  
     exec dbo.sp_calculaPercEtapa @w_chave_pai, null;
  end
end