create or replace function RetornaLimiteElim(p_chave in number) return varchar2 is
/**********************************************************************************
* Nome      : RetornaLimiteElim
* Finalidade: Recuperar informações de um processo de eliminação
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
  w_especie       varchar2(4000)  := '';

  cursor c_especies is
      select /*+ RULE*/ distinct b.nome
        from pa_eliminacao                       z
             inner     join pa_documento         a on (z.protocolo            = a.sq_siw_solicitacao)
             inner     join siw_solicitacao     a1 on (a.sq_siw_solicitacao   = a1.sq_siw_solicitacao)
               inner   join siw_tramite         a2 on (a1.sq_siw_tramite      = a2.sq_siw_tramite and a2.sigla <> 'CA')
             inner     join pa_especie_documento b on (a.sq_especie_documento = b.sq_especie_documento)
       where z.sq_siw_solicitacao = p_chave
         and a1.sq_solic_pai      is null
      order by acentos(b.nome);

begin
  if p_chave is not null then
     -- Verifica se a caixa existe e, se existir, recupera seus dados
     select count(sq_siw_solicitacao) into w_reg from pa_eliminacao where sq_siw_solicitacao = p_chave;
     if w_reg > 0 then
        -- Recupera as especies documentais
        for crec in c_especies loop w_especie := w_especie || ', ' || crec.nome; end loop;

        -- Monta string com os dados
        Result := substr(w_especie,3);
     end if;
  end if;
  return(Result);
end RetornaLimiteElim;
/
