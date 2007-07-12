create or replace function dados_solic(p_chave in numeric) returns varchar as $$
/**********************************************************************************
* Nome      : dados_solic
* Finalidade: Recuperar informa��es de uma solicita��o
* Autor     : Alexandre Vinhadelli Papad�polis
* Data      : 21/06/2007, 12:30
* Par�metros:
*    p_chave : chave prim�ria de SIW_SOLICITACAO
* Retorno: se a solicita��o n�o existir, retorna nulo
*          se a solicita��o existir, retorna string contendo informa��es sobre ela.
*          A string cont�m v�rios peda�os separados por |@|
*          1  - string para exibi��o em listagens, composta da sigla do m�dulo e do c�digo da solicita��o
*          2  - codigo da solicita��o
*          3  - titulo da solicita��o
*          4  - siw_menu.sq_menu - chave do menu ao qual a solicita��o est� ligada ()
*          5  - siw_menu.nome    - nome do menu
*          6  - siw_menu.sigla   - sigla do menu
*          7  - siw_menu.p1      - valor de p1
*          8  - siw_menu.p2      - valor de p2
*          9  - siw_menu.p3      - valor de p3
*          10 - siw_menu.p4      - valor de p4
*          11 - siw_menu.link    - link para a rotina de visualiza��o
*          12 - siw_modulo.sigla - sigla do m�dulo da solicita��o
***********************************************************************************/
declare
  Result        varchar(32767) := null;
  w_reg         numeric(18);
  c_solic       siw_solicitacao.sq_siw_solicitacao%type;
  c_nome        siw_menu.nome%type;
  c_codigo      varchar(255);
  c_titulo      varchar(4000);
  c_sq_menu     siw_menu.sq_menu%type;
  c_sigla       siw_menu.sigla%type;
  c_p1          siw_menu.p1%type;
  c_p2          siw_menu.p2%type;
  c_p3          siw_menu.p3%type;
  c_p4          siw_menu.p4%type;
  c_link        siw_menu.link%type;
  c_sg_modulo   siw_menu.sigla%type;

  c_dados cursor (l_chave numeric) for
     select a.sq_menu, a.nome, a.sigla, a.p1, a.p2, a.p3, a.p4,
            coalesce(a1.link, replace(lower(a.link),'inicial','visual')) as link,
            a2.sigla as sg_modulo,
            b.sq_siw_solicitacao,
            coalesce(p1.codigo_interno, to_char(p2.sq_siw_solicitacao), to_char(p3.sq_siw_solicitacao), 
                     pv.codigo,         pw.codigo,                      to_char(px.sq_siw_solicitacao), 
                     py.codigo,         pz.codigo,                      to_char(b.sq_siw_solicitacao)
                    ) as codigo,
            coalesce(p1.titulo,         p2.titulo,                      p3.destino,
                     pv.titulo,         pw.titulo,                      px.assunto,
                     py.titulo,         pz.titulo,                      coalesce(b.descricao,b.justificativa)
                    ) as titulo
       from siw_menu                             a
            left  join siw_menu                  a1 on (a.sq_menu             = a1.sq_menu_pai and
                                                        a1.sigla              like '%VISUAL%'
                                                       )
            inner join siw_modulo                a2 on (a.sq_modulo           = a2.sq_modulo)
            inner join siw_solicitacao           b  on (a.sq_menu             = b.sq_menu)
            left  join pe_programa               p1  on (b.sq_siw_solicitacao = p1.sq_siw_solicitacao)
            left  join pj_projeto                p2  on (b.sq_siw_solicitacao = p2.sq_siw_solicitacao)
            left  join sr_solicitacao_transporte p3  on (b.sq_siw_solicitacao = p3.sq_siw_solicitacao)
            left  join (select x.sq_siw_solicitacao as chave, 
                               x.prefixo||'.'||substr(1000000+x.numero_documento,2,6)||'/'||x.ano||'-'||substr(100+to_number(x.digito),2,2) as codigo,
                               y.descricao as titulo
                          from pa_documento           x
                               join   siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                       )                         pv  on (b.sq_siw_solicitacao = pv.chave)
            left  join (select x.sq_siw_solicitacao as chave, x.codigo_interno as codigo, y.descricao as titulo
                          from pd_missao              x
                               join   siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                       )                         pw  on (b.sq_siw_solicitacao = pw.chave)
            left  join gd_demanda                px  on (b.sq_siw_solicitacao = px.sq_siw_solicitacao)
            left  join (select x.sq_siw_solicitacao as chave, x.codigo_interno as codigo, y.descricao as titulo
                          from fn_lancamento          x
                               join   siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                       )                         py  on (b.sq_siw_solicitacao = py.chave)
            left  join (select x.sq_siw_solicitacao as chave, x.codigo_interno as codigo,
                               coalesce(x.titulo, w.nome_resumido||' - '||z.nome||' ('||to_char(x.inicio,'dd/mm/yyyy')||'-'||to_char(x.fim,'dd/mm/yyyy')||')') as titulo
                          from ac_acordo                     x
                               left   join   co_pessoa       w on (x.outra_parte        = w.sq_pessoa)
                               inner  join   siw_solicitacao y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao)
                                 left join ct_cc             z on (y.sq_cc              = z.sq_cc)
                       )                         pz  on (b.sq_siw_solicitacao = pz.chave)
      where b.sq_siw_solicitacao = l_chave;
begin
  if p_chave is not null then
     -- Verifica se a solicita��o existe e, se existir, recupera seus dados
     select count(sq_siw_solicitacao) into w_reg from siw_solicitacao where sq_siw_solicitacao = p_chave;
     if w_reg > 0 then
        open c_dados (p_chave);
        loop
          fetch c_dados into c_sq_menu, c_nome, c_sigla, c_p1, c_p2, c_p3, c_p4, c_link, c_sg_modulo, c_solic, c_codigo, c_titulo;
          If Not Found Then Exit; End If;
          Result := c_nome||': '||c_codigo||'|@|'||c_codigo||'|@|'||c_titulo||'|@|'||c_sq_menu||'|@|'||c_nome||'|@|'||c_sigla||'|@|'||c_p1||'|@|'||c_p2||'|@|'||c_p3||'|@|'||c_p4||'|@|'||c_link||'|@|'||c_sg_modulo;
        end loop;
        close c_dados;
     end if;
  end if;
  return(Result);
end; $$ language 'plpgsql' volatile;
