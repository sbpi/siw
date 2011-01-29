create or replace function tt_carregaligacao() RETURNS VOID AS $$
declare
begin

insert into tt_ligacao
       (sq_ligacao, cliente, sq_central_fone, sq_tronco,
        sq_ramal, data, operadora, valor,
        duracao, recebida, entrante, fax, numero, sq_usuario_central
       )
(select nextval('sq_ligacao'), 1, 1, b.sq_tronco,
        c.sq_ramal, to_date(to_char(a.data,'dd/mm/yyyy')||', '||a.hora,'dd/mm/yyyy, hh24:mi:ss'),
        nvl(a.operadora,14), a.valor, (substr(a.dura,1,2)*3600)+(substr(a.dura,4,2)*60)+substr(a.dura,7,2),
        'N',  'N', 'N', a.ndisc, d.sq_usuario_central
   from ligori                a
        inner join tt_tronco  b on (b.codigo     = a.tronco)
        inner join tt_ramal   c on (c.codigo     = a.ramal)
        left  join tt_usuario d on (d.codigo     = a.codigo)
  where (0 = (select count(*) from tt_ligacao t1 where t1.cliente = 1 and t1.sq_central_fone = 1 and t1.data = to_date(to_char(a.data,'dd/mm/yyyy')||', '||a.hora,'dd/mm/yyyy, hh24:mi:ss') and t1.sq_ramal = c.sq_ramal))
);

insert into tt_ligacao
       (sq_ligacao, cliente, sq_central_fone, sq_tronco,
        sq_ramal, data, operadora, valor,
        duracao, recebida, entrante, fax, numero, sq_usuario_central
       )
(select nextval('sq_ligacao'), 1, 1, b.sq_tronco,
        c.sq_ramal, to_date(to_char(a.data,'dd/mm/yyyy')||', '||a.hora,'dd/mm/yyyy, hh24:mi:ss'),
        nvl(a.operadora,14), a.valor, (substr(a.dura,1,2)*3600)+(substr(a.dura,4,2)*60)+substr(a.dura,7,2),
        'S',  'S', 'N', a.ndisc, d.sq_usuario_central
   from ligrec                a
        inner join tt_tronco  b on (b.codigo     = a.tronco)
        inner join tt_ramal   c on (c.codigo     = a.ramal)
        left  join tt_usuario d on (d.codigo     = a.codigo)
  where (0 = (select count(*) from tt_ligacao t1 where t1.cliente = 1 and t1.sq_central_fone = 1 and t1.data = to_date(to_char(a.data,'dd/mm/yyyy')||', '||a.hora,'dd/mm/yyyy, hh24:mi:ss') and t1.sq_ramal = c.sq_ramal))
);

insert into tt_ligacao
       (sq_ligacao, cliente, sq_central_fone, sq_tronco,
        sq_ramal, data, operadora, valor,
        duracao, recebida, entrante, fax, numero, sq_usuario_central
       )
(select nextVal('sq_ligacao'), 1, 1, b.sq_tronco,
        c.sq_ramal, to_date(to_char(a.data,'dd/mm/yyyy')||', '||a.hora,'dd/mm/yyyy, hh24:mi:ss'),
        nvl(a.operadora,14), a.valor, (substr(a.dura,1,2)*3600)+(substr(a.dura,4,2)*60)+substr(a.dura,7,2),
        'N',  'S', 'N', a.ndisc, d.sq_usuario_central
   from lignate               a
        inner join tt_tronco  b on (b.codigo     = a.tronco)
        inner join tt_ramal   c on (c.codigo     = a.ramal)
        left  join tt_usuario d on (d.codigo     = a.codigo)
  where (0 = (select count(*) from tt_ligacao t1 where t1.cliente = 1 and t1.sq_central_fone = 1 and t1.data = to_date(to_char(a.data,'dd/mm/yyyy')||', '||a.hora,'dd/mm/yyyy, hh24:mi:ss') and t1.sq_ramal = c.sq_ramal))
);

update tt_ligacao set sq_prefixo = tt_localidade(numero) where sq_prefixo is null;

END $$ LANGUAGE 'plpgsql' VOLATILE;
