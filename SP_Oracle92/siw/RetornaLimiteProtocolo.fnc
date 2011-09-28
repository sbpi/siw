create or replace function RetornaLimiteProtocolo(p_chave in number) return varchar2 is
/**********************************************************************************
* Nome      : RetornaLimiteProtocolo
* Finalidade: Recuperar informações de um protocolo
* Autor     : Alexandre Vinhadelli Papadópolis
* Data      : 10/11/2009, 18:00
* Parâmetros:
*    p_chave : chave primária de SIW_SOLICITACAO
* Retorno: String com data limite na fase corrente ou na fase intermediária.
*          Pode ser uma data ou um texto
***********************************************************************************/
  Result varchar2(32767) := null;
  w_reg  number(18);
  w_mes  number(2);
  w_dia  number(2);
  w_ano  number(4);
  w_bis  boolean;

  cursor c_dados is
      select case when h.data_anexo is null or (h.data_anexo is not null and h.data_anexo <= (case a.processo when 'S' then a.data_autuacao else a1.inicio end))
                  then case a2.sigla
                            when 'AS' then case d.sigla when 'ANOS' then to_char(case a.processo when 'S' then a.data_autuacao else a1.inicio end,'dd/mm/')||to_number(to_char(case a.processo when 'S' then a.data_autuacao else a1.inicio end,'yyyy')+cast(c.fase_corrente_anos as integer)) else d.descricao end
                            when 'AT' then case e.sigla when 'ANOS' then to_char(case a.processo when 'S' then a.data_autuacao else a1.inicio end,'dd/mm/')||to_number(to_char(case a.processo when 'S' then a.data_autuacao else a1.inicio end,'yyyy')+cast(c.fase_intermed_anos as integer)) else e.descricao end
                            when 'EL' then case e.sigla when 'ANOS' then to_char(case a.processo when 'S' then a.data_autuacao else a1.inicio end,'dd/mm/')||to_number(to_char(case a.processo when 'S' then a.data_autuacao else a1.inicio end,'yyyy')+cast(c.fase_intermed_anos as integer)) else e.descricao end
                       end 
                  else case a2.sigla
                            when 'AS' then case d.sigla when 'ANOS' then to_char(h.data_anexo,'dd/mm/')||to_number(to_char(h.data_anexo,'yyyy')+cast(c.fase_corrente_anos as integer)) else d.descricao end
                            when 'AT' then case e.sigla when 'ANOS' then to_char(h.data_anexo,'dd/mm/')||to_number(to_char(h.data_anexo,'yyyy')+cast(c.fase_intermed_anos as integer)) else e.descricao end
                            when 'EL' then case e.sigla when 'ANOS' then to_char(h.data_anexo,'dd/mm/')||to_number(to_char(h.data_anexo,'yyyy')+cast(c.fase_intermed_anos as integer)) else e.descricao end
                       end 
             end as intermediario
        from pa_documento                        a
             inner     join siw_solicitacao      a1 on (a.sq_siw_solicitacao   = a1.sq_siw_solicitacao)
               inner   join siw_tramite          a2 on (a1.sq_siw_tramite      = a2.sq_siw_tramite)
             inner     join pa_documento_assunto b on (a.sq_siw_solicitacao   = b.sq_siw_solicitacao and b.principal = 'S')
               inner   join pa_assunto           c on (b.sq_assunto           = c.sq_assunto)
                 inner join pa_tipo_guarda       d on (c.fase_corrente_guarda = d.sq_tipo_guarda)
                 inner join pa_tipo_guarda       e on (c.fase_intermed_guarda = e.sq_tipo_guarda)
                 inner join pa_tipo_guarda       f on (c.fase_final_guarda    = f.sq_tipo_guarda)
             left      join (select k.sq_siw_solicitacao, max(case l.processo when 'S' then l.data_autuacao else l1.inicio end) data_anexo
                               from pa_documento                  k
                                      inner join pa_documento     l on (k.sq_siw_solicitacao   = l.sq_documento_pai)
                                    inner   join siw_solicitacao k1 on (k.sq_siw_solicitacao  = k1.sq_siw_solicitacao)
                                      inner join siw_solicitacao l1 on (k1.sq_siw_solicitacao = l1.sq_solic_pai)
                              where k.sq_siw_solicitacao = p_chave
                             group by k.sq_siw_solicitacao
                            )                    g on (a.sq_siw_solicitacao   = g.sq_siw_solicitacao)
             left      join (select k.sq_siw_solicitacao, case l.processo when 'S' then l.data_autuacao else l1.inicio end data_anexo
                               from pa_documento                  k
                                      inner join pa_documento     l on (k.sq_siw_solicitacao   = l.sq_documento_pai)
                                    inner   join siw_solicitacao k1 on (k.sq_siw_solicitacao  = k1.sq_siw_solicitacao)
                                      inner join siw_solicitacao l1 on (k1.sq_siw_solicitacao = l1.sq_solic_pai)
                              where k.sq_siw_solicitacao = p_chave
                            )                    h on (a.sq_siw_solicitacao   = h.sq_siw_solicitacao and
                                                       h.data_anexo           = g.data_anexo
                                                      )
       where a.sq_siw_solicitacao = p_chave;
begin
  if p_chave is not null then
     -- Verifica se a solicitação existe e, se existir, recupera seus dados
     select count(sq_siw_solicitacao) into w_reg from pa_documento where sq_siw_solicitacao = p_chave;
     if w_reg > 0 then
        for crec in c_dados loop
            If instr(crec.intermediario,'/') > 0 and length(crec.intermediario) = 10 Then
               w_dia := to_number(substr(crec.intermediario,1,2));
               w_mes := to_number(substr(crec.intermediario,4,2));
               w_ano := to_number(substr(crec.intermediario,7,4));
               If (w_ano mod 4) = 0 
                  Then w_bis := true;
                  Else w_bis := false;                  
               End If;
            
               If w_mes in (4,6,9,11) and w_dia > 30 Then w_dia := 30; End If;
               If w_mes = 2 Then
                  If w_dia >= 29 Then 
                     If w_bis 
                        Then w_dia := 29; 
                        Else w_dia := 28;
                     End If;
                  End If;
               End If;
               Result := to_char(to_date(substr(to_char(100+w_dia),2,2)||substr(crec.intermediario,3),'dd/mm/yyyy'),'dd/mm/yyyy');
            Else
               Result := crec.intermediario;
            End If;
        end loop;
     end if;
  end if;
  return(Result);
end RetornaLimiteProtocolo;
/
