create or replace function RetornaLimiteCaixa(p_chave in number) return varchar2 is
/**********************************************************************************
* Nome      : RetornaLimiteProtocolo
* Finalidade: Recuperar informações de um protocolo
* Autor     : Alexandre Vinhadelli Papadópolis
* Data      : 10/11/2009, 18:00
* Parâmetros:
*    p_chave : chave primária de SIW_SOLICITACAO
* Retorno: se a solicitação não existir, retorna nulo
*          se a solicitação existir, retorna string contendo informações sobre ela.
*          A string contém vários pedaços separados por |@|
*          1  - data limite do protocolo
*          2  - data limite na fase intermediária
*          3  - destinação final do protocolo
*          4  - assuntos dos protocolos contidos na caixa
*          5  - espécies documentais dos protocolos contidos na caixa
***********************************************************************************/
  Result          varchar2(32767) := null;
  w_reg           number(18);
  w_limite        varchar2(255);
  w_intermediario varchar2(4000)  := '';
  w_final         varchar2(4000)  := '';
  w_assunto       varchar2(4000)  := '';
  w_especie       varchar2(4000)  := '';

  cursor c_especies is
      select /*+ RULE*/ distinct b.nome
        from pa_documento                        a
             inner     join siw_solicitacao     a1 on (a.sq_siw_solicitacao   = a1.sq_siw_solicitacao)
               inner   join siw_tramite         a2 on (a1.sq_siw_tramite      = a2.sq_siw_tramite and a2.sigla <> 'CA')
             inner     join pa_especie_documento b on (a.sq_especie_documento = b.sq_especie_documento)
       where a.sq_caixa      = p_chave
         and a1.sq_solic_pai is null
      order by acentos(nome);

  cursor c_assuntos is
      select /*+ RULE*/ distinct c.codigo||' - '||c.descricao as nome
        from pa_documento                        a
             inner     join siw_solicitacao     a1 on (a.sq_siw_solicitacao   = a1.sq_siw_solicitacao)
               inner   join siw_tramite         a2 on (a1.sq_siw_tramite      = a2.sq_siw_tramite and a2.sigla <> 'CA')
             inner     join pa_documento_assunto b on (a.sq_siw_solicitacao   = b.sq_siw_solicitacao and b.principal = 'S')
               inner   join pa_assunto           c on (b.sq_assunto           = c.sq_assunto)
       where a.sq_caixa      = p_chave
         and a1.sq_solic_pai is null
      order by 1;

  cursor c_dados is
      select max(case a.processo when 'S' then a.data_autuacao else a1.inicio end) as data_limite,
             case e.sigla 
                  when 'ANOS' then (to_char(max(case a.processo when 'S' then a.data_autuacao else a1.inicio end),'yyyy')+c.fase_intermed_anos)||
                                   to_char(max(case a.processo when 'S' then a.data_autuacao else a1.inicio end),'mmdd')
                  else null 
             end as intermediario,
             case f.sigla when 'ANOS' then to_char(a.data_central,'dd/mm/')||(to_char(a.data_central,'yyyy')+c.fase_final_anos) else f.descricao end as final
        from pa_caixa                            a3
             inner     join pa_documento         a  on (a3.sq_caixa            =  a.sq_caixa)
             inner     join siw_solicitacao      a1 on (a.sq_siw_solicitacao   = a1.sq_siw_solicitacao)
               inner   join siw_tramite          a2 on (a1.sq_siw_tramite      = a2.sq_siw_tramite)
             inner     join pa_documento_assunto b on (a.sq_siw_solicitacao   = b.sq_siw_solicitacao and b.principal = 'S')
               inner   join pa_assunto           c on (b.sq_assunto           = c.sq_assunto)
                 inner join pa_tipo_guarda       d on (c.fase_corrente_guarda = d.sq_tipo_guarda)
                 inner join pa_tipo_guarda       e on (c.fase_intermed_guarda = e.sq_tipo_guarda)
                 inner join pa_tipo_guarda       f on (c.fase_final_guarda    = f.sq_tipo_guarda)
       where a3.sq_caixa     = p_chave
         and a1.sq_solic_pai is null
      group by a.data_central, c.fase_intermed_anos, c.fase_final_anos, e.sigla, e.descricao, f.sigla, f.descricao;
begin
  if p_chave is not null then
     -- Verifica se a caixa existe e, se existir, recupera seus dados
     select count(sq_caixa) into w_reg from pa_caixa where sq_caixa = p_chave;
     if w_reg > 0 then
        -- Recupera data limite, data intermediário e destinação final
        for crec in c_dados loop
            If crec.data_limite   is not null Then w_limite := to_char(crec.data_limite,'dd/mm/yyyy'); End If;
            If crec.intermediario is not null and (w_intermediario is null or crec.intermediario > w_intermediario) Then w_intermediario := crec.intermediario; End If;
            If crec.final         is not null and (instr(w_final, crec.final) = 0 or w_reg = 1)                     Then w_final         := w_final || ' / '|| crec.final; w_reg := w_reg + 1; End If;
        end loop;
        If w_intermediario is not null Then
           w_intermediario := substr(w_intermediario,7,2)||'/'||substr(w_intermediario,5,2)||'/'||substr(w_intermediario,1,4);
        End If;
     
        -- Retorna assuntos
        for crec in c_assuntos loop w_assunto := w_assunto || ' / ' || crec.nome; end loop;

        -- Retorna especies documentais
        for crec in c_especies loop w_especie := w_especie || ' / ' || crec.nome; end loop;

        -- Monta string com os dados
        Result := w_limite||'|@|'||w_intermediario||'|@|'||substr(w_final,4)||'|@|'||substr(w_assunto,4)||'|@|'||substr(w_especie,4);
     end if;
  end if;
  return(Result);
end RetornaLimiteCaixa;
/
