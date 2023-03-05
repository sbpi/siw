create or replace procedure TEMP_AJUSTA_CRONOGRAMA_PNUMA(p_solicitacao in number) is
  cursor c_orcamento_total is
      select b.codigo, b.nome, c.sq_projeto_rubrica, c.inicio, c.fim, c.valor_previsto
        from siw_solicitacao a
             inner join pj_rubrica            b on a.sq_siw_solicitacao = b.sq_siw_solicitacao
             inner join pj_rubrica_cronograma c on b.sq_projeto_rubrica = c.sq_projeto_rubrica
       where a.sq_siw_solicitacao = p_solicitacao
         and 12                   < months_between(c.fim+1, c.inicio);
         
  w_inicio_projeto  date;
  w_fim_projeto     date;
  w_ano_inicio      number(4);
  w_ano_fim         number(4);
  w_anos            number(2);
  w_valor_anual     number(18,2);
begin
  
  -- Remove cronograma anual
  delete pj_rubrica_cronograma
   where sq_projeto_rubrica in (select sq_projeto_rubrica from pj_rubrica where sq_siw_solicitacao = p_solicitacao)
     and 12                 >= months_between(fim+1, inicio);
     
  -- Recupera dados do projeto
  select a.inicio, a.fim into w_inicio_projeto, w_fim_projeto from siw_solicitacao a where a.sq_siw_solicitacao = p_solicitacao;
  
  -- Configura variáveis para cálculo do valor anual e laço de criação de cronograma
  w_ano_inicio := to_char(w_inicio_projeto,'yyyy');
  w_ano_fim    := to_char(w_fim_projeto,'yyyy');
  w_anos       := w_ano_fim - w_ano_inicio + 1;
  
  -- Insere cronograma anual
  for crec in c_orcamento_total loop
      w_valor_anual := trunc(crec.valor_previsto / w_anos,2);
      for i in w_ano_inicio .. w_ano_fim loop
          sp_putcronograma(p_operacao => 'I',
                           p_chave => crec.sq_projeto_rubrica,
                           p_chave_aux => null,
                           p_inicio => case i when to_char(w_inicio_projeto,'yyyy') then w_inicio_projeto else to_date('01/01/'||i,'dd/mm/yyyy') end,
                           p_fim => case i when to_char(w_fim_projeto,'yyyy') then w_fim_projeto else to_date('31/12/'||i,'dd/mm/yyyy') end,
                           p_valor_previsto => w_valor_anual,
                           p_valor_real => 0,
                           p_quantidade => 0);
      end loop;
  end loop;
  
  -- Efetiva a inclusão do cronograma trimestral
  commit;
  
  -- Remove o cronograma total
  delete pj_rubrica_cronograma
   where sq_projeto_rubrica in (select sq_projeto_rubrica from pj_rubrica where sq_siw_solicitacao = p_solicitacao)
     and 12                 < months_between(fim+1, inicio);
     
  -- Efetiva a remoção do cronograma trimestral
  commit;
  
end TEMP_AJUSTA_CRONOGRAMA_PNUMA;

/* 
-- Somente atualiza os valores
update pj_rubrica_cronograma set valor_previsto = 889700 where sq_rubrica_cronograma = 5044;
update pj_rubrica_cronograma set valor_previsto = 637800 where sq_rubrica_cronograma = 5045;
update pj_rubrica_cronograma set valor_previsto = 957000 where sq_rubrica_cronograma = 5046;
update pj_rubrica_cronograma set valor_previsto = 328000 where sq_rubrica_cronograma = 5047;
update pj_rubrica_cronograma set valor_previsto = 355100 where sq_rubrica_cronograma = 5048;
update pj_rubrica_cronograma set valor_previsto = 235450 where sq_rubrica_cronograma = 5049;
update pj_rubrica_cronograma set valor_previsto = 451000 where sq_rubrica_cronograma = 5050;
update pj_rubrica_cronograma set valor_previsto = 266000 where sq_rubrica_cronograma = 5051;
update pj_rubrica_cronograma set valor_previsto = 6000 where sq_rubrica_cronograma = 5052;
update pj_rubrica_cronograma set valor_previsto = 0 where sq_rubrica_cronograma = 5053;
update pj_rubrica_cronograma set valor_previsto = 0 where sq_rubrica_cronograma = 5054;
update pj_rubrica_cronograma set valor_previsto = 500000 where sq_rubrica_cronograma = 5055;
update pj_rubrica_cronograma set valor_previsto = 0 where sq_rubrica_cronograma = 5056;
update pj_rubrica_cronograma set valor_previsto = 817600 where sq_rubrica_cronograma = 5057;
update pj_rubrica_cronograma set valor_previsto = 2260000 where sq_rubrica_cronograma = 5058;
update pj_rubrica_cronograma set valor_previsto = 533119 where sq_rubrica_cronograma = 5059;
update pj_rubrica_cronograma set valor_previsto = 0 where sq_rubrica_cronograma = 5060;
update pj_rubrica_cronograma set valor_previsto = 243700 where sq_rubrica_cronograma = 5061;
update pj_rubrica_cronograma set valor_previsto = 71950 where sq_rubrica_cronograma = 5062;
update pj_rubrica_cronograma set valor_previsto = 80000 where sq_rubrica_cronograma = 5063;
update pj_rubrica_cronograma set valor_previsto = 234000 where sq_rubrica_cronograma = 5064;
update pj_rubrica_cronograma set valor_previsto = 0 where sq_rubrica_cronograma = 5065;
update pj_rubrica_cronograma set valor_previsto = 60000 where sq_rubrica_cronograma = 5066;
update pj_rubrica_cronograma set valor_previsto = 0 where sq_rubrica_cronograma = 5067;
update pj_rubrica_cronograma set valor_previsto = 0 where sq_rubrica_cronograma = 5068;
update pj_rubrica_cronograma set valor_previsto = 216400 where sq_rubrica_cronograma = 5069;
update pj_rubrica_cronograma set valor_previsto = 78000 where sq_rubrica_cronograma = 5070;
update pj_rubrica_cronograma set valor_previsto = 183000 where sq_rubrica_cronograma = 5071;
update pj_rubrica_cronograma set valor_previsto = 196000 where sq_rubrica_cronograma = 5072;
update pj_rubrica_cronograma set valor_previsto = 0 where sq_rubrica_cronograma = 5073;
update pj_rubrica_cronograma set valor_previsto = 171000 where sq_rubrica_cronograma = 5074;
update pj_rubrica_cronograma set valor_previsto = 394091 where sq_rubrica_cronograma = 5075;
update pj_rubrica_cronograma set valor_previsto = 0 where sq_rubrica_cronograma = 5076;
update pj_rubrica_cronograma set valor_previsto = 160000 where sq_rubrica_cronograma = 5077;
update pj_rubrica_cronograma set valor_previsto = 20000 where sq_rubrica_cronograma = 5078;
update pj_rubrica_cronograma set valor_previsto = 0 where sq_rubrica_cronograma = 5079;
update pj_rubrica_cronograma set valor_previsto = 0 where sq_rubrica_cronograma = 5080;
update pj_rubrica_cronograma set valor_previsto = 0 where sq_rubrica_cronograma = 5081;
update pj_rubrica_cronograma set valor_previsto = 11750 where sq_rubrica_cronograma = 5082;
update pj_rubrica_cronograma set valor_previsto = 32000 where sq_rubrica_cronograma = 5083;
update pj_rubrica_cronograma set valor_previsto = 0 where sq_rubrica_cronograma = 5084;
update pj_rubrica_cronograma set valor_previsto = 249000 where sq_rubrica_cronograma = 5085;
update pj_rubrica_cronograma set valor_previsto = 0 where sq_rubrica_cronograma = 5086;
update pj_rubrica_cronograma set valor_previsto = 8000 where sq_rubrica_cronograma = 5087;
update pj_rubrica_cronograma set valor_previsto = 0 where sq_rubrica_cronograma = 5088;
update pj_rubrica_cronograma set valor_previsto = 0 where sq_rubrica_cronograma = 5089;
update pj_rubrica_cronograma set valor_previsto = 0 where sq_rubrica_cronograma = 5090;
update pj_rubrica_cronograma set valor_previsto = 0 where sq_rubrica_cronograma = 5091;
update pj_rubrica_cronograma set valor_previsto = 0 where sq_rubrica_cronograma = 5092;
update pj_rubrica_cronograma set valor_previsto = 0 where sq_rubrica_cronograma = 5093;
update pj_rubrica_cronograma set valor_previsto = 0 where sq_rubrica_cronograma = 5094;
update pj_rubrica_cronograma set valor_previsto = 0 where sq_rubrica_cronograma = 5095;
update pj_rubrica_cronograma set valor_previsto = 117500 where sq_rubrica_cronograma = 5096;
update pj_rubrica_cronograma set valor_previsto = 182320 where sq_rubrica_cronograma = 5097;
update pj_rubrica_cronograma set valor_previsto = 77000 where sq_rubrica_cronograma = 5098;
update pj_rubrica_cronograma set valor_previsto = 112000 where sq_rubrica_cronograma = 5099;
update pj_rubrica_cronograma set valor_previsto = 15800 where sq_rubrica_cronograma = 5100;
update pj_rubrica_cronograma set valor_previsto = 0 where sq_rubrica_cronograma = 5101;
update pj_rubrica_cronograma set valor_previsto = 0 where sq_rubrica_cronograma = 5102;
update pj_rubrica_cronograma set valor_previsto = 21500 where sq_rubrica_cronograma = 5103;
update pj_rubrica_cronograma set valor_previsto = 0 where sq_rubrica_cronograma = 5104;
update pj_rubrica_cronograma set valor_previsto = 0 where sq_rubrica_cronograma = 5105;
update pj_rubrica_cronograma set valor_previsto = 0 where sq_rubrica_cronograma = 5106;
update pj_rubrica_cronograma set valor_previsto = 0 where sq_rubrica_cronograma = 5107;
update pj_rubrica_cronograma set valor_previsto = 44000 where sq_rubrica_cronograma = 5108;
update pj_rubrica_cronograma set valor_previsto = 84000 where sq_rubrica_cronograma = 5109;
update pj_rubrica_cronograma set valor_previsto = 84000 where sq_rubrica_cronograma = 5110;
update pj_rubrica_cronograma set valor_previsto = 0 where sq_rubrica_cronograma = 5111;
update pj_rubrica_cronograma set valor_previsto = 252000 where sq_rubrica_cronograma = 5112;
update pj_rubrica_cronograma set valor_previsto = 40000 where sq_rubrica_cronograma = 5113;
update pj_rubrica_cronograma set valor_previsto = 60000 where sq_rubrica_cronograma = 5114;
update pj_rubrica_cronograma set valor_previsto = 11735780 where sq_rubrica_cronograma = 5477;


-- Insere os valores totais
prompt PL/SQL Developer Export Tables for user OTCAP@DOCKER
prompt Created by Alexandre on quinta-feira, 2 de março de 2023
set feedback off
set define off

prompt Loading PJ_RUBRICA_CRONOGRAMA...
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5044, 6363, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 889700, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5045, 6364, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 637800, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5046, 6365, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 957000, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5047, 6366, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 328000, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5048, 6370, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 355100, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5049, 6371, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 235450, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5050, 6372, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 451000, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5051, 6373, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 266000, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5052, 6374, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 6000, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5053, 6376, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 0, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5054, 6377, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 0, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5055, 6378, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 500000, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5056, 6379, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 0, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5057, 6381, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 817600, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5058, 6382, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 2260000, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5059, 6383, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 533119, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5060, 6384, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 0, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5061, 6386, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 243700, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5062, 6387, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 71950, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5063, 6388, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 80000, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5064, 6389, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 234000, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5065, 6391, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 0, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5066, 6392, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 60000, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5067, 6393, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 0, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5068, 6394, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 0, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5069, 6396, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 216400, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5070, 6397, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 78000, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5071, 6398, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 183000, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5072, 6399, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 196000, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5073, 6401, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 0, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5074, 6402, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 171000, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5075, 6403, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 394091, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5076, 6404, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 0, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5077, 6405, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 160000, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5078, 6407, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 20000, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5079, 6408, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 0, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5080, 6409, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 0, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5081, 6410, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 0, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5082, 6411, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 11750, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5083, 6413, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 32000, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5084, 6414, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 0, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5085, 6415, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 249000, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5086, 6416, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 0, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5087, 6417, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 8000, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5088, 6419, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 0, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5089, 6420, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 0, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5090, 6421, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 0, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5091, 6422, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 0, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5092, 6424, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 0, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5093, 6425, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 0, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5094, 6426, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 0, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5095, 6427, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 0, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5096, 6429, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 117500, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5097, 6430, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 182320, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5098, 6431, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 77000, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5099, 6432, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 112000, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5100, 6434, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 15800, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5101, 6435, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 0, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5102, 6436, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 0, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5103, 6437, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 21500, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5104, 6439, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 0, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5105, 6440, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 0, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5106, 6441, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 0, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5107, 6442, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 0, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5108, 6444, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 44000, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5109, 6445, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 84000, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5110, 6446, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 84000, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5111, 6447, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 0, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5112, 6448, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 252000, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5113, 6449, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 40000, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5114, 6450, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 60000, 0, 0);
insert into PJ_RUBRICA_CRONOGRAMA (sq_rubrica_cronograma, sq_projeto_rubrica, inicio, fim, valor_previsto, valor_real, quantidade)
values (5477, 6921, to_date('15-04-2020', 'dd-mm-yyyy'), to_date('31-12-2024', 'dd-mm-yyyy'), 11735780, 0, 0);
commit;
prompt 72 records loaded

set feedback on
set define on
prompt Done
*/
/
