create or replace procedure sp_calculaDiarias_OTCA(p_chave in number, p_todos in varchar2 default null, p_tipo in varchar2 default null) is
  w_existe    number(18);
  w_internacional       varchar2(1);
  w_fim_semana          varchar2(1);

  -- Cursor que recupera todas as solicitações
  cursor c_solic is
    select a.sq_siw_solicitacao
           from siw_solicitacao      a
                inner join pd_missao b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao);

  cursor c_diarias is
    select t.sq_diaria, floor(trunc(u.saida)-trunc(v.chegada)) quantidade
      from PD_DIARIA t 
           inner   join pd_deslocamento u on (t.sq_deslocamento_chegada = u.sq_deslocamento)
           inner   join pd_deslocamento v on (t.sq_deslocamento_saida   = v.sq_deslocamento)
           inner   join siw_solicitacao w on (t.sq_siw_solicitacao      = w.sq_siw_solicitacao)
             inner join siw_tramite     x on (w.sq_siw_tramite          = x.sq_siw_tramite)
     where t.sq_siw_solicitacao = p_chave
       and t.tipo               = coalesce(p_tipo,case x.sigla when 'CI' then 'S' else 'P' end);
   
begin
  -- Verifica se a solicitacao existe
  select count(a.sq_siw_solicitacao) into w_existe 
    from pd_diaria                      a 
         inner     join pd_missao       b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
           inner   join siw_solicitacao c on (b.sq_siw_solicitacao = c.sq_siw_solicitacao)
             inner join siw_tramite     d on (c.sq_siw_tramite     = d.sq_siw_tramite)
   where b.cumprimento        in ('N','I','P') 
     and a.sq_siw_solicitacao = coalesce(p_chave,0)
     and a.tipo               = coalesce(p_tipo,case d.sigla when 'CI' then 'S' else 'P' end);
     
  If w_existe = 0 and coalesce(p_todos,'nulo') <> 'TODOS' Then
     return;
  Elsif coalesce(p_todos,'nulo') = 'TODOS' Then
     -- Atualiza as diárias de todas as viagens
     for crec in c_solic loop
        sp_calculaDiarias_OTCA(crec.sq_siw_solicitacao);
     end loop;
     return;
  End If;
  -- Recupera informação sobre viagem internacional
  select internacional, diaria_fim_semana into w_internacional, w_fim_semana from pd_missao where sq_siw_solicitacao = p_chave;
  
  -- Zera as quantidades de diárias da solicitação
  update pd_diaria 
     set quantidade = 0 
    where sq_diaria in (select sq_diaria 
                          from pd_diaria a 
                               join   siw_solicitacao b on a.sq_siw_solicitacao = b.sq_siw_solicitacao 
                                 join siw_tramite     c on b.sq_siw_tramite = c.sq_siw_tramite 
                         where a.sq_siw_solicitacao = p_chave 
                           and a.tipo = coalesce(p_tipo,case c.sigla when 'CI' then 'S' else 'P' end) 
                           and a.calculo_diaria_texto is null
                       );
  
  for crec in c_diarias loop
     update pd_diaria a set a.quantidade = crec.quantidade, a.calculo_diaria_qtd = crec.quantidade where sq_diaria = crec.sq_diaria and a.calculo_diaria_texto is null;
  end loop;
end sp_calculaDiarias_OTCA;
/
