create or replace procedure sp_calculaDiarias(p_chave in number, p_todos in varchar2 default null, p_tipo in varchar2 default null) is
  i           number(18);
  w_cliente   number(18);
  w_cont      number(18) := 0;
  w_desloc    number(18) := 0;
  w_existe    number(18);
  w_atual     date;
  w_sq_diaria number(18);
  w_diaria    varchar2(1);
  w_inicio    date;
  w_ini_hora  number(4);
  w_fim       date;
  w_ultimo    number(18) := 0;
  w_tot_dia   number(5,2);
  w_compromisso_saida   varchar2(1);
  w_compromisso_retorno varchar2(1);
  w_internacional       varchar2(1);
  w_fim_semana          varchar2(1);

  type diaria is table of number(10,1) index by binary_integer;
  
  diarias diaria;
  
  -- Cursor que recupera todas as solicitações
  cursor c_solic is
    select a.sq_siw_solicitacao
           from siw_solicitacao      a
                inner join pd_missao b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao);

  cursor c_deslocamentos (w_chave in number, w_data in date) is
    select a.sq_deslocamento, a.sq_siw_solicitacao, a.saida, a.chegada,  a.compromisso, 
           b.sq_cidade as cidade_orig, b.co_uf as uf_orig, b.sq_pais as pais_orig,
           c.padrao as origem_nacional,
           d.sq_cidade as cidade_dest, d.co_uf as uf_dest, d.sq_pais as pais_dest,
           e.padrao as destino_nacional,
           f.sq_diaria as sq_diaria_inicio, f.diaria as diaria_inicio, 
           h.sq_diaria as sq_diaria_fim,    h.diaria as diaria_fim 
      from pd_deslocamento                       a
           inner      join pd_missao             a1 on (a.sq_siw_solicitacao         = a1.sq_siw_solicitacao)
           inner      join siw_solicitacao       a2 on (a.sq_siw_solicitacao         = a2.sq_siw_solicitacao)
             inner    join siw_tramite           a3 on (a2.sq_siw_tramite            = a3.sq_siw_tramite)
           inner      join co_cidade             b  on (a.origem                     = b.sq_cidade)
             inner    join co_pais               c  on (b.sq_pais                    = c.sq_pais)
           inner      join co_cidade             d  on (a.destino                    = d.sq_cidade)
             inner    join co_pais               e  on (d.sq_pais                    = e.sq_pais)
           left       join pd_diaria             f  on (a.sq_siw_solicitacao         = f.sq_siw_solicitacao and
                                                        a.destino                    = f.sq_cidade and
                                                        a.sq_deslocamento            = f.sq_deslocamento_saida and
                                                        a.tipo                       = f.tipo
                                                       )
           left       join pd_diaria             h  on (a.sq_siw_solicitacao         = h.sq_siw_solicitacao and
                                                        a.origem                     = h.sq_cidade and
                                                        a.sq_deslocamento            = h.sq_deslocamento_chegada and
                                                        a.tipo                       = h.tipo
                                                       )
     where a.sq_siw_solicitacao = w_chave
       and (w_data              = trunc(a.saida) or w_data = trunc(a.chegada))
       and a.tipo               = coalesce(p_tipo,case a3.sigla when 'CI' then 'S' else 'P' end)
   order by a.saida, a.chegada;

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
     
  -- Verifica se deve executar cálculo de diárias de um cliente específico
  If w_existe > 0 Then
     select cliente into w_cliente from pd_missao where sq_siw_solicitacao = p_chave;
     If w_cliente = 17305 Then -- OTCA
        sp_calculadiarias_OTCA(p_chave, p_todos, p_tipo);
        return;
     End If;
  End If;

  If w_existe = 0 and coalesce(p_todos,'nulo') <> 'TODOS' Then
     return;
  Elsif coalesce(p_todos,'nulo') = 'TODOS' Then
     -- Atualiza as diárias de todas as viagens
     for crec in c_solic loop
        sp_calculaDiarias(crec.sq_siw_solicitacao, null, p_tipo);
     end loop;
     return;
  End If;

  -- Recupera informação sobre viagem internacional
  select internacional, diaria_fim_semana into w_internacional, w_fim_semana from pd_missao where sq_siw_solicitacao = p_chave;
  
  -- Decide se será paga diária em final de semana. (Internacional sempre paga. Nacional depende de indicação na tela da solicitação)
  If w_internacional = 'S' or w_fim_semana = 'S' Then
     w_fim_semana := 'S';
  End If;
   
  -- Recupera o início e o fim da viagem
  select min(trunc(a.saida)), max(trunc(a.chegada)), to_number(to_char(min(a.saida),'hh24mi')), count(a.sq_deslocamento)
    into w_inicio,            w_fim,                 w_ini_hora,                                w_desloc
    from pd_deslocamento        a
         join   siw_solicitacao b on a.sq_siw_solicitacao = b.sq_siw_solicitacao 
           join siw_tramite     c on b.sq_siw_tramite     = c.sq_siw_tramite 
   where a.sq_siw_solicitacao = p_chave 
     and a.tipo = coalesce(p_tipo,case c.sigla when 'CI' then 'S' else 'P' end);
  
  -- Recupera o último delocamento
  for crec in c_deslocamentos (p_chave, w_fim) loop 
      w_ultimo := crec.sq_deslocamento; 
      w_compromisso_retorno := crec.compromisso;
  end loop;
  
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
  
  -- Inicializa o array de diárias
  for crec in (select sq_diaria from pd_diaria a join siw_solicitacao b on a.sq_siw_solicitacao = b.sq_siw_solicitacao join siw_tramite c on b.sq_siw_tramite = c.sq_siw_tramite where a.sq_siw_solicitacao = p_chave and a.tipo = coalesce(p_tipo,case c.sigla when 'CI' then 'S' else 'P' end)) loop diarias(crec.sq_diaria) := 0; end loop;
   
  w_atual   := w_inicio;
  w_tot_dia := 0;
  w_cont    := 1;
  for w_cont in 1..(w_fim-w_inicio+1) loop
     i := 0;
     -- Calcula as diárias
     for crec in c_deslocamentos (p_chave, w_atual) loop
       If w_tot_dia < 1 Then
         -- Verifica diária em fim de semana
         If (w_fim_semana = 'S' or (w_fim_semana = 'N' and to_char(crec.saida,'d') not in (1,7) and to_char(w_atual,'d') not in (1,7))) Then 
            If w_cont = 1 Then
               -- No primeiro dia:
               --    NACIONAL
               --    Toda e qualquer saída após as 18h será computada com o 1/2 diária nacional, tendo compromisso ou não
               --    A saída antes das 18h com compromisso implica em uma diária nacional
               --                          sem compromisso implica em 1/2 diária nacional
               --    INTERNACIONAL
               --    Toda e qualquer saída será computada com o 1 diária internacional
               
               If i = 0 Then
                 -- Grava o compromisso da primeira saída
                 w_compromisso_saida := crec.compromisso;
               End If;
               
               select count(distinct a.sq_deslocamento) into w_existe
                 from pd_deslocamento      a 
                      inner join siw_solicitacao b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
                      inner join siw_tramite     c on (b.sq_siw_tramite     = c.sq_siw_tramite)
                      left  join pd_diaria       d on (a.sq_deslocamento    = d.sq_deslocamento_chegada)
                where a.sq_siw_solicitacao = p_chave 
                  and (d.sq_diaria is null or (d.sq_diaria is not null and d.diaria = 'S'))
                  and w_inicio = trunc(a.saida) 
                  and a.tipo = coalesce(p_tipo,case c.sigla when 'CI' then 'S' else 'P' end);
               If w_existe <= 1 or w_existe = (i+1) or w_inicio = w_fim or w_desloc = 2 Then
                  If crec.diaria_inicio = 'S' Then
                     If crec.destino_nacional = 'N' Then 
                        diarias(crec.sq_diaria_inicio) := diarias(crec.sq_diaria_inicio) + 1; w_tot_dia := w_tot_dia + 1;
                     Elsif w_compromisso_saida = 'N' or w_ini_hora > 1800
                        Then diarias(crec.sq_diaria_inicio) := diarias(crec.sq_diaria_inicio) + 0.5; w_tot_dia := w_tot_dia + 0.5;
                        Else diarias(crec.sq_diaria_inicio) := diarias(crec.sq_diaria_inicio) + 1;   w_tot_dia := w_tot_dia + 1;
                     End If;
                  End If;
               End If;
            Elsif w_cont = (w_fim-w_inicio+1) Then
               -- No último dia:
               --    NACIONAL
               --    Chegada até  as 12h será computada com o 1/2 diária nacional, tendo compromisso ou não
               --    Chegada após as 12h com compromisso implica em uma diária nacional
               --                        sem compromisso implica em 1/2 diária nacional
               --    INTERNACIONAL
               --    Toda e qualquer chegada será computada com o 1/2 diária internacional
               If crec.origem_nacional = 'N' Then
                  If crec.diaria_fim = 'S' Then diarias(crec.sq_diaria_fim) := diarias(crec.sq_diaria_fim) + 0.5;  w_tot_dia := w_tot_dia + 0.5; End If;
                  i := i + 1;
                  exit;
               Elsif crec.diaria_fim = 'S' Then
                  If (w_compromisso_retorno = 'S' and w_tot_dia < 1) or
                     (w_compromisso_retorno = 'N' and w_tot_dia = 0)
                  Then
                     If  crec.compromisso = 'N' or to_char(crec.chegada,'hh24mi') <= 1200 or crec.origem_nacional = 'N' or w_tot_dia = 0.5
                        Then diarias(crec.sq_diaria_fim) := diarias(crec.sq_diaria_fim) + 0.5;  w_tot_dia := w_tot_dia + 0.5;
                        Else diarias(crec.sq_diaria_fim) := diarias(crec.sq_diaria_fim) + 1;    w_tot_dia := w_tot_dia + 1;
                     End If;
                  End If;
               End If;
            Else
               If (trunc(crec.saida) = trunc(crec.chegada) or 
                   (trunc(crec.saida) <> trunc(crec.chegada) and ((crec.origem_nacional = crec.destino_nacional or (crec.destino_nacional = 'N' and w_atual = trunc(crec.saida))) or
                                                                  (crec.origem_nacional  = 'N' and w_atual = trunc(crec.chegada))
                                                                 )
                   )
                  ) 
               Then
                 -- Nos demais dias:
                 --    Cada dia de viagem corresponde a uma diária.
                 --    Se não há alteração de cidade na data, soma uma diária na cidade em que se encontra
                 --    Se há alteração de cidade na data:
                 --       Para destino internacional, conta 1 diária na cidade de destino, não importando horários;
                 --       Para destino nacional, conta 1/2 diária na cidade de origem e 1/2 diária na cidade de destino, não importando horários
                 select count(distinct a.sq_deslocamento) into w_existe
                   from pd_deslocamento      a 
                        inner join siw_solicitacao b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
                        inner join siw_tramite     c on (b.sq_siw_tramite     = c.sq_siw_tramite)
                        left  join pd_diaria       d on (a.sq_deslocamento    = d.sq_deslocamento_chegada)
                  where a.sq_siw_solicitacao = p_chave 
                    and (d.sq_diaria is null or (d.sq_diaria is not null and d.diaria = 'S'))
                    and (w_atual = trunc(a.saida) or w_atual = trunc(a.chegada))
                    and a.tipo = coalesce(p_tipo,case c.sigla when 'CI' then 'S' else 'P' end);
                 If w_existe <= 1 or w_existe = (i+1) Then
                    If crec.origem_nacional = 'N' and crec.destino_nacional = 'S' Then
                       -- vindo do exterior e não sendo o último dia de viagem, 0,5 diária para a origem e para o destino
                       If crec.sq_diaria_inicio is not null and crec.diaria_inicio = 'S' Then diarias(crec.sq_diaria_inicio) := diarias(crec.sq_diaria_inicio) + 0.5;  w_tot_dia := w_tot_dia + 0.5; End If;
                       If crec.sq_diaria_fim    is not null and crec.diaria_fim    = 'S' Then diarias(crec.sq_diaria_fim)    := diarias(crec.sq_diaria_fim) + 0.5;     w_tot_dia := w_tot_dia + 0.5; End If;
                       w_diaria    := crec.diaria_inicio;
                       w_sq_diaria := crec.sq_diaria_inicio;
                       i := i + 1;
                   Else
                       If crec.destino_nacional = 'N' Then
                          If crec.diaria_inicio = 'S' Then diarias(crec.sq_diaria_inicio) := diarias(crec.sq_diaria_inicio) + 1;  w_tot_dia := w_tot_dia + 1; End If;
                       Else
                          If crec.diaria_inicio = 'S' Then 
                             diarias(crec.sq_diaria_inicio) := diarias(crec.sq_diaria_inicio) + 0.5; 
                             If crec.diaria_fim is null Then diarias(crec.sq_diaria_inicio) := diarias(crec.sq_diaria_inicio) + 0.5;    w_tot_dia := w_tot_dia + 0.5; End If;
                          End If;
                          If crec.diaria_fim = 'S' and w_sq_diaria is not null Then 
                             If trunc(crec.saida) <> trunc(crec.chegada) 
                                Then diarias(w_sq_diaria) := diarias(w_sq_diaria) + 1; w_tot_dia := w_tot_dia + 1;
                                Else diarias(w_sq_diaria) := diarias(w_sq_diaria) + 0.5; w_tot_dia := w_tot_dia + 0.5;
                             End If;
                          End If;
                       End If;
                    End If;
                 End If;
               Elsif w_tot_dia = 0 Then
                 If w_diaria = 'S' Then diarias(w_sq_diaria) := diarias(w_sq_diaria) + 1; w_tot_dia := w_tot_dia + 1; End If;
               End If;
            End If;
         End If;
         w_diaria    := crec.diaria_inicio;
         w_sq_diaria := crec.sq_diaria_inicio;
         i           := i + 1;
       End If;
     end loop;
     If i = 0 Then
        If w_diaria = 'S' and (w_fim_semana = 'S' or (w_fim_semana = 'N' and to_char(w_atual,'d') not in (1,7))) Then 
           diarias(w_sq_diaria) := diarias(w_sq_diaria) + 1; 
        End If;
     End If;
     w_atual := w_atual + 1;
     w_tot_dia := 0;
  end loop;
  
  begin
  for j in diarias.FIRST..diarias.LAST loop
     -- Verifica se o índice do array tem um registro de diária correspondente
     select count(*) into w_existe from pd_diaria where sq_diaria = j and sq_siw_solicitacao = p_chave;
     If w_existe > 0 Then
        update pd_diaria a set a.quantidade = coalesce(diarias(j),0), a.calculo_diaria_qtd = coalesce(diarias(j),0) where sq_diaria = j and a.calculo_diaria_texto is null;
     End If;
  end loop;
  /*
  exception
  when others then null;
  */
  end;
end sp_calculaDiarias;
/
