<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/sp/db_exec.php');
/**
 * class sp_getcitylist
 *
 * { Description :- 
 *    Retorna array com as cidades do país e estado indicado.
 * }
 */
class db_getCityList {

  function getInstanceOf($dbms, $p_pais, $p_estado, $p_nome, $p_restricao) {
    extract($GLOBALS, EXTR_PREFIX_SAME, 'strchema');
    $params = array("p_pais" =>      array($p_pais,      B_NUMERIC, 32),
                    "p_estado" =>    array($p_estado,    B_VARCHAR, 2),
                    "p_nome" =>      array($p_nome,      B_VARCHAR, 60),
                    "p_restricao" => array($p_restricao, B_VARCHAR, 30),
                    "p_result" =>    array(null,         B_CURSOR, -1)
    );
    
    $w_restricao = nvl($p_restricao, '\'-\'');
    $sql = new db_exec; $par = $sql->normalize($params); extract($par, EXTR_OVERWRITE);

    $SQL =  " select a.sq_cidade, a.sq_cidade, b.co_uf, c.nome as sq_pais, a.nome, coalesce(a.ddd,'-') as ddd,$crlf" .
            "      case a.capital when 'S' then 'Sim' else 'Não' end as capital, a.aeroportos,$crlf" .
            "      coalesce(a.codigo_ibge,'-') as codigo_ibge,$crlf" .
            "      acentos(a.nome) as ordena$crlf" .
            " from co_cidade            a$crlf" .
            "      inner  join co_uf    b on (a.co_uf     = b.co_uf and$crlf" .
            "                                  a.sq_pais   = b.sq_pais$crlf" .
            "                                 )$crlf" .
            "      inner  join co_pais  c on (a.sq_pais   = c.sq_pais)$crlf" .
            "      left   join (select x.sq_cidade, count(x.sq_cidade) as qtd$crlf" .
            "                     from eo_indicador_afericao   x$crlf" .
            "                          inner join eo_indicador y on (x.sq_eoindicador = y.sq_eoindicador and$crlf" .
            "                                                        y.ativo          = 'S'$crlf" .
            "                                                       )$crlf" .
            "                    where to_char(y.cliente) = coalesce($p_nome,'0') -- $p_restricao como chave de SIW_CLIENTE$crlf" .
            "                      and x.sq_cidade is not null$crlf" .
            "                   group by x.sq_cidade$crlf" .
            "                  )         d on (a.sq_cidade = d.sq_cidade)$crlf" .
            " where ($p_estado is null or ($p_estado is not null and b.co_uf   = $p_estado))$crlf" .
            "   and ($p_pais   is null or ($p_pais   is not null and c.sq_pais = $p_pais))$crlf" .
            "   and (($w_restricao  = 'INDICADOR'  and d.sq_cidade is not null) or$crlf" .
            "        ($w_restricao  <> 'INDICADOR' and ($p_nome     is null      or ($p_nome is not null and acentos(a.nome) like '%'".C."acentos($p_nome)".C."'%')))$crlf" .
            "       )$crlf";

    $l_rs = $sql->getInstanceOf($dbms, $SQL, $params);
    return $l_rs;
  }
}
?>
