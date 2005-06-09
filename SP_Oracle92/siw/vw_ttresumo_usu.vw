create or replace view vw_ttresumo_usu as
select x.sq_usuario_central, y.nome_resumido, 'Trabalho' trabalho, 
       Nvl(tt_ori.qtd,0) ori_qtd, Nvl(tt_ori.dura,0) ori_dura,
       Nvl(tt_rec.qtd,0) rec_qtd, Nvl(tt_rec.dura,0) rec_dura,
       Nvl(tt_nat.qtd,0) nat_qtd, Nvl(tt_nat.dura,0) nat_dura,
      (Nvl(tt_ori.qtd,0)+Nvl(tt_rec.qtd,0)+Nvl(tt_nat.qtd,0)) qtd_tot,
      (Nvl(tt_ori.dura,0)+Nvl(tt_rec.dura,0)+Nvl(tt_nat.dura,0)) dura_tot      
  from tt_usuario x, co_pessoa y,
       (select sq_usuario_central, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a where 2=2 and trabalho='S' and entrante='N' group by sq_usuario_central) tt_ori,
       (select sq_usuario_central, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a where 2=2 and trabalho='S' and entrante='S' and recebida='S' group by sq_usuario_central) tt_rec,
       (select sq_usuario_central, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a where 2=2 and trabalho='S' and entrante='S' and recebida='N' group by sq_usuario_central) tt_nat
 where 1=1
   and x.usuario                = y.sq_pessoa
   and x.sq_usuario_central     = tt_ori.sq_usuario_central (+)
   and x.sq_usuario_central     = tt_rec.sq_usuario_central (+)
   and x.sq_usuario_central     = tt_nat.sq_usuario_central (+)
   and 0           < (Nvl(tt_ori.qtd,0) + Nvl(tt_rec.qtd,0) + Nvl(tt_nat.qtd,0))
UNION
select x.sq_usuario_central, y.nome_resumido, 'Particular' trabalho, 
       Nvl(tt_ori.qtd,0) ori_qtd, Nvl(tt_ori.dura,0) ori_dura,
       Nvl(tt_rec.qtd,0) rec_qtd, Nvl(tt_rec.dura,0) rec_dura,
       Nvl(tt_nat.qtd,0) nat_qtd, Nvl(tt_nat.dura,0) nat_dura,
      (Nvl(tt_ori.qtd,0)+Nvl(tt_rec.qtd,0)+Nvl(tt_nat.qtd,0)) qtd_tot,
      (Nvl(tt_ori.dura,0)+Nvl(tt_rec.dura,0)+Nvl(tt_nat.dura,0)) dura_tot      
  from tt_usuario x, co_pessoa y,
       (select sq_usuario_central, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a where 2=2 and trabalho='N' and entrante='N' group by sq_usuario_central) tt_ori,
       (select sq_usuario_central, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a where 2=2 and trabalho='N' and entrante='S' and recebida='S' group by sq_usuario_central) tt_rec,
       (select sq_usuario_central, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a where 2=2 and trabalho='N' and entrante='S' and recebida='N' group by sq_usuario_central) tt_nat
 where 1=1
   and x.usuario                = y.sq_pessoa
   and x.sq_usuario_central     = tt_ori.sq_usuario_central (+)
   and x.sq_usuario_central     = tt_rec.sq_usuario_central (+)
   and x.sq_usuario_central     = tt_nat.sq_usuario_central (+)
   and 0           < (Nvl(tt_ori.qtd,0) + Nvl(tt_rec.qtd,0) + Nvl(tt_nat.qtd,0))
UNION
select x.sq_usuario_central, y.nome_resumido, '.Total' trabalho,
       Nvl(tt_ori.qtd,0) ori_qtd, Nvl(tt_ori.dura,0) ori_dura,
       Nvl(tt_rec.qtd,0) rec_qtd, Nvl(tt_rec.dura,0) rec_dura,
       Nvl(tt_nat.qtd,0) nat_qtd, Nvl(tt_nat.dura,0) nat_dura,
      (Nvl(tt_ori.qtd,0)+Nvl(tt_rec.qtd,0)+Nvl(tt_nat.qtd,0)) qtd_tot,
      (Nvl(tt_ori.dura,0)+Nvl(tt_rec.dura,0)+Nvl(tt_nat.dura,0)) dura_tot      
  from tt_usuario x, co_pessoa y,
       (select sq_usuario_central, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a where 2=2 and trabalho is not null and entrante='N' group by sq_usuario_central) tt_ori,
       (select sq_usuario_central, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a where 2=2 and trabalho is not null and entrante='S' and recebida='S' group by sq_usuario_central) tt_rec,
       (select sq_usuario_central, count(*) qtd,Nvl(sum(duracao),0) dura from tt_ligacao a where 2=2 and trabalho is not null and entrante='S' and recebida='N' group by sq_usuario_central) tt_nat
 where 1=1
   and x.usuario                = y.sq_pessoa
   and x.sq_usuario_central     = tt_ori.sq_usuario_central (+)
   and x.sq_usuario_central     = tt_rec.sq_usuario_central (+)
   and x.sq_usuario_central     = tt_nat.sq_usuario_central (+)
   and 0           < (Nvl(tt_ori.qtd,0) + Nvl(tt_rec.qtd,0) + Nvl(tt_nat.qtd,0))
/

