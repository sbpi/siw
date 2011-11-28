create or replace procedure SP_PutTTClassificacao
   (p_sq_cc              in varchar2 default null,
    p_sq_central_fone    in number   default null,
    p_cliente            in number   default null
    ) is

    l_item       varchar2(18);
    l_sq_cc      varchar2(200) := p_sq_cc ||',';
    x_sq_cc      varchar2(200) := '';

    cursor c_classificacao is
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
begin
   If p_sq_cc is not null Then
   
      -- Montagem da string para teste da chaves da classificacao
      Loop
         l_item  := Trim(substr(l_sq_cc,1,Instr(l_sq_cc,',')-1));
         If Length(l_item) > 0 Then
            x_sq_cc := x_sq_cc||',''['||to_number(l_item)||']''';
         End If;
         l_sq_cc := substr(l_sq_cc,Instr(l_sq_cc,',')+1,200);
         Exit when length(l_sq_cc)=0 or l_sq_cc is null;
      End Loop;
      x_sq_cc := substr(x_sq_cc,2,200);
      
      -- Teste das chaves da string com o cursor
      For crec in c_classificacao loop
         If InStr(x_sq_cc,'['||crec.sq_cc||']') > 0 Then
            If crec.existe > 0 and crec.marcado = 0 Then
               update tt_cc 
                  set ativacao    = sysdate,
                      desativacao = null
                where sq_cc = crec.sq_cc
                  and sq_central_fone = p_sq_central_fone;
            ElsIf crec.existe = 0 and crec.marcado = 0 Then
               insert into tt_cc
                 (sq_cc, sq_central_fone, cliente, ativacao)
               values
                 (crec.sq_cc, p_sq_central_fone, p_cliente, sysdate);
            End If;
         Else
            If crec.desativacao is null Then
               update tt_cc 
                  set desativacao = sysdate
                where sq_cc           = crec.sq_cc
                  and sq_central_fone = p_sq_central_fone;
            End If;
         End If;
      End Loop;
   End If;
end SP_PutTTClassificacao;
/
