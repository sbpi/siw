create or replace function RetornaLimiteProtocolo(p_chave in number) return varchar2 is
/**********************************************************************************
* Nome      : RetornaLimiteProtocolo
* Finalidade: Recuperar informa��es de um protocolo
* Autor     : Alexandre Vinhadelli Papad�polis
* Data      : 10/11/2009, 18:00
* Par�metros:
*    p_chave : chave prim�ria de SIW_SOLICITACAO
* Retorno: se a solicita��o n�o existir, retorna nulo
*          se a solicita��o existir, retorna string contendo informa��es sobre ela.
*          A string cont�m v�rios peda�os separados por |@|
*          1  - data limite do protocolo
*          2  - data limite na fase intermedi�ria
*          3  - destina��o final do protocolo
***********************************************************************************/
  Result varchar2(32767) := null;
  w_reg  number(18);

  cursor c_dados is
      select max(case a.processo when 'S' then to_char(a.data_autuacao,'dd/mm/yyyy') else to_char(a1.inicio,'dd/mm/yyyy') end) as data_limite,
             case a2.sigla
                  when 'AS' then case d.sigla when 'ANOS' then to_char(a.data_setorial,'dd/mm/')||(to_char(a.data_setorial,'yyyy')+c.fase_corrente_anos) else d.descricao end
                  when 'AT' then case e.sigla when 'ANOS' then to_char(a.data_central,'dd/mm/')||(to_char(a.data_central,'yyyy')+c.fase_intermed_anos) else e.descricao end
             end as intermediario,
             case f.sigla when 'ANOS' then to_char(a.data_central,'dd/mm/')||(to_char(a.data_central,'yyyy')+c.fase_final_anos) else f.descricao end as final
        from pa_documento                        a
             inner     join siw_solicitacao      a1 on (a.sq_siw_solicitacao   = a1.sq_siw_solicitacao)
               inner   join siw_tramite          a2 on (a1.sq_siw_tramite      = a2.sq_siw_tramite)
             inner     join pa_documento_assunto b on (a.sq_siw_solicitacao   = b.sq_siw_solicitacao and b.principal = 'S')
               inner   join pa_assunto           c on (b.sq_assunto           = c.sq_assunto)
                 left  join pa_tipo_guarda       d on (c.fase_corrente_guarda = d.sq_tipo_guarda)
                 left  join pa_tipo_guarda       e on (c.fase_intermed_guarda = e.sq_tipo_guarda)
                 left  join pa_tipo_guarda       f on (c.fase_final_guarda    = f.sq_tipo_guarda)
       where a.sq_siw_solicitacao = p_chave
      group by a2.sigla, a.data_setorial, a.data_central, c.fase_corrente_anos, c.fase_intermed_anos, c.fase_final_anos, d.sigla, d.descricao, e.sigla, e.descricao, f.sigla, f.descricao;
begin
  if p_chave is not null then
     -- Verifica se a solicita��o existe e, se existir, recupera seus dados
     select count(sq_siw_solicitacao) into w_reg from pa_documento where sq_siw_solicitacao = p_chave;
     if w_reg > 0 then
        for crec in c_dados loop
            Result := crec.data_limite||'|@|'||crec.intermediario||'|@|'||crec.final;
        end loop;
     end if;
  end if;
  return(Result);
end RetornaLimiteProtocolo;
/
