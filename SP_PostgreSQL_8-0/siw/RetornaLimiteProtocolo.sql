create or replace function RetornaLimiteProtocolo(p_chave numeric)  RETURNS varchar AS $$
DECLARE
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
  Result varchar(32767) := null;
  w_reg  numeric(18);

   c_dados CURSOR FOR
      select case a2.sigla
                  when 'AS' then case d.sigla when 'ANOS' then to_char(to_date(to_char(case a.processo when 'S' then a.data_autuacao else a1.inicio end,'dd/mm/')||to_char(case a.processo when 'S' then a.data_autuacao else a1.inicio end,'yyyy'),'dd/mm/yyyy')+cast(c.fase_corrente_anos as integer),'dd/mm/yyyy') else d.descricao end
                  when 'AT' then case e.sigla when 'ANOS' then to_char(to_date(to_char(case a.processo when 'S' then a.data_autuacao else a1.inicio end,'dd/mm/')||to_char(case a.processo when 'S' then a.data_autuacao else a1.inicio end,'yyyy'),'dd/mm/yyyy')+cast(c.fase_intermed_anos as integer),'dd/mm/yyyy') else e.descricao end
                  when 'EL' then case e.sigla when 'ANOS' then to_char(to_date(to_char(case a.processo when 'S' then a.data_autuacao else a1.inicio end,'dd/mm/')||to_char(case a.processo when 'S' then a.data_autuacao else a1.inicio end,'yyyy'),'dd/mm/yyyy')+cast(c.fase_intermed_anos as integer),'dd/mm/yyyy') else e.descricao end
             end as intermediario
        from pa_documento                        a
             inner     join siw_solicitacao      a1 on (a.sq_siw_solicitacao   = a1.sq_siw_solicitacao)
               inner   join siw_tramite          a2 on (a1.sq_siw_tramite      = a2.sq_siw_tramite)
             inner     join pa_documento_assunto b on (a.sq_siw_solicitacao   = b.sq_siw_solicitacao and b.principal = 'S')
               inner   join pa_assunto           c on (b.sq_assunto           = c.sq_assunto)
                 left  join pa_tipo_guarda       d on (c.fase_corrente_guarda = d.sq_tipo_guarda)
                 left  join pa_tipo_guarda       e on (c.fase_intermed_guarda = e.sq_tipo_guarda)
                 left  join pa_tipo_guarda       f on (c.fase_final_guarda    = f.sq_tipo_guarda)
       where a.sq_siw_solicitacao = p_chave;
BEGIN
  if p_chave is not null then
     -- Verifica se a solicitação existe e, se existir, recupera seus dados
     select count(sq_siw_solicitacao) into w_reg from pa_documento where sq_siw_solicitacao = p_chave;
     if w_reg > 0 then
        for crec in c_dados loop
            Result := crec.intermediario;
        end loop;
     end if;
  end if;
  return(Result);
END; $$ LANGUAGE 'PLPGSQL' VOLATILE;