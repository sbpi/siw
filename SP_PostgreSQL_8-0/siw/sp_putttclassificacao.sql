create or replace FUNCTION SP_PutTTClassificacao
   (p_sq_cc              varchar,
    p_sq_central_fone    numeric,
    p_cliente            numeric   
    ) RETURNS VOID AS $$
DECLARE

    l_item       varchar(18);
    l_sq_cc      varchar(200) := p_sq_cc ||',';
    x_sq_cc      varchar(200) := '';

     c_classificacao CURSOR FOR
       select a.sq_cc, e.nome||' - '||a.nome as nm_cc, a.sigla, 
              coalesce(b.sq_central_fone,0) as existe, 
              coalesce(d.sq_central_fone,0) as marcado,
              b.ativacao, b.desativacao
         from ct_cc            a
              inner join ct_cc e on (a.sq_cc_pai = e.sq_cc)
              left  join tt_cc b on (a.sq_cc     = b.sq_cc)
              left  join tt_cc d on (a.sq_cc     = d.sq_cc and
                                     d.desativacao is null
                                    )
              left  join (select x.sq_cc, count(y.sq_cc) as filhos
                           from ct_cc           x
                                left join ct_cc y on (x.sq_cc = y.sq_cc_pai)
                          group by x.sq_cc
                         )     c on (a.sq_cc     = c.sq_cc)
        where a.cliente         = p_cliente
          and a.ativo           = 'S'
          and c.filhos          = 0;
BEGIN
   If p_sq_cc is not null Then
   
      -- Montagem da string para teste da chaves da classificacao
      Loop
         l_item  := Trim(substr(l_sq_cc,1,Instr(l_sq_cc,',')-1));
         If Length(l_item) > 0 Then
            x_sq_cc := x_sq_cc||',''['||to_number(l_item)||']''';
         End If;
         l_sq_cc := substr(l_sq_cc,Instr(l_sq_cc,',')+1,200);
         Exit when l_sq_cc is null;
      End Loop;
      x_sq_cc := substr(x_sq_cc,2,200);
      
      -- Teste das chaves da string com o 
      For crec in c_classificacao loop
         If InStr(x_sq_cc,'['||crec.sq_cc||']') > 0 Then
            If crec.existe > 0 and crec.marcado = 0 Then
               update tt_cc 
                  set ativacao    = now(),
                      desativacao = null
                where sq_cc = crec.sq_cc
                  and sq_central_fone = p_sq_central_fone;
            ElsIf crec.existe = 0 and crec.marcado = 0 Then
               insert into tt_cc
                 (sq_cc, sq_central_fone, cliente, ativacao)
               values
                 (crec.sq_cc, p_sq_central_fone, p_cliente, now());
            End If;
         Else
            If crec.desativacao is null Then
               update tt_cc 
                  set desativacao = now()
                where sq_cc           = crec.sq_cc
                  and sq_central_fone = p_sq_central_fone;
            End If;
         End If;
      End Loop;
   End If;END; $$ LANGUAGE 'PLPGSQL' VOLATILE;