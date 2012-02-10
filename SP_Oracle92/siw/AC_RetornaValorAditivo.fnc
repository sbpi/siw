create or replace function AC_RetornaValorAditivo
  (p_contrato    in number,
   p_aditivo     in number
  ) return float is
/**********************************************************************************
* Nome      : AC_RetornaValorAditivo
* Finalidade: Calcula o valor das parcelas abrangidas pelo contrato ou aditivo
* Autor     : Alexandre Vinhadelli Papadópolis
* Data      : 02/05/2005, 12:52
* Parâmetros:
*    p_contrato: chave primária de AC_ACORDO
*    p_aditivo : chave primária de AC_ACORDO_ADITIVO
***********************************************************************************/
  Result    float := '0';
  w_existe  number(18);

  cursor c_dados is
    select sum(vl_parcela) as vl_parcela
     from (select y.sq_acordo_aditivo, x.sq_acordo_parcela,
                  case y.prorrogacao
                  when 'N' then x.valor_excedente
                           else case when (y.acrescimo = 'S' or y.supressao = 'S') and y.revisao = 'S'
                                     then (x.valor_inicial + x.valor_excedente + x.valor_reajuste)
                                     else case when (y.acrescimo = 'S' or y.supressao = 'S')
                                               then (x.valor_inicial + x.valor_excedente)
                                               else case y.revisao when 'S' then (x.valor_inicial + x.valor_reajuste)
                                                                            else x.valor_inicial
                                                    end
                                          end
                                end
                  end vl_parcela
             from ac_acordo_parcela            x
                  inner join ac_acordo_aditivo y on (x.sq_siw_solicitacao = y.sq_siw_solicitacao and
                                                     y.sq_acordo_aditivo  = p_aditivo and
                                                     x.inicio             between y.inicio and y.fim
                                                    )
            where x.sq_siw_solicitacao = p_contrato
           )
   group by sq_acordo_aditivo;

begin

 -- Verifica se o serviço existe
 select count(*) into w_existe
   from ac_acordo a
        inner join ac_acordo_aditivo b on (a.sq_siw_solicitacao = b.sq_siw_solicitacao)
  where a.sq_siw_solicitacao = coalesce(p_contrato,0)
    and b.sq_acordo_aditivo  = coalesce(p_aditivo,0);

 If w_existe = 0 Then Return (Result); End If;

 for crec in c_dados loop Result := crec.vl_parcela; end loop;

 return(Result);
end AC_RetornaValorAditivo;
/
