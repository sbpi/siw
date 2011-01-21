﻿create or replace function dados_solic(p_chave in numeric) returns varchar as $$
/**********************************************************************************
* Nome      : dados_solic
* Finalidade: Recuperar informações de uma solicitação
* Autor     : Alexandre Vinhadelli Papadópolis
* Data      : 21/06/2007, 12:30
* Parâmetros:
*    p_chave : chave primária de SIW_SOLICITACAO
* Retorno: se a solicitação não existir, retorna nulo
*          se a solicitação existir, retorna string contendo informações sobre ela.
*          A string contém vários pedaços separados por |@|
*          1  - string para exibição em listagens, composta da sigla do módulo e do código da solicitação
*          2  - codigo da solicitação
*          3  - titulo da solicitação
*          4  - siw_menu.sq_menu - chave do menu ao qual a solicitação está ligada ()
*          5  - siw_menu.nome    - nome do menu
*          6  - siw_menu.sigla   - sigla do menu
*          7  - siw_menu.p1      - valor de p1
*          8  - siw_menu.p2      - valor de p2
*          9  - siw_menu.p3      - valor de p3
*          10 - siw_menu.p4      - valor de p4
*          11 - siw_menu.link    - link para a rotina de visualização
*          12 - siw_modulo.sigla - sigla do módulo da solicitação
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
            coalesce(b.codigo_interno, to_char(b.sq_siw_solicitacao)) as codigo,
            coalesce(b.titulo, c.destino, d.assunto, b.descricao, b.justificativa) as titulo
       from siw_menu                             a
            left  join siw_menu                  a1 on (a.sq_menu             = a1.sq_menu_pai and
                                                        a1.sigla              like '%VISUAL%'
                                                       )
            inner join siw_modulo                a2 on (a.sq_modulo           = a2.sq_modulo)
            inner join siw_solicitacao           b  on (a.sq_menu             = b.sq_menu)
            left  join sr_solicitacao_transporte c  on (b.sq_siw_solicitacao = c.sq_siw_solicitacao)
            left  join gd_demanda                d  on (b.sq_siw_solicitacao = d.sq_siw_solicitacao)
      where b.sq_siw_solicitacao = p_chave;
begin
  if p_chave is not null then
     -- Verifica se a solicitação existe e, se existir, recupera seus dados
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
