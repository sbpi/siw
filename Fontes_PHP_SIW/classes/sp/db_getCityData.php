<?php
extract($GLOBALS); include_once($w_dir_volta."classes/sp/db_exec.php");
/**
* class db_getCityData
*
* { Description :- 
*    Recupera os dados da cidade
* }
*/
class db_getCityData {
   function getInstanceOf($dbms, $p_chave) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema');
     $params=array("p_chave"    =>array($p_chave,       B_NUMERIC,   32),
                   "p_result"   =>array(null,           B_CURSOR,    -1)
                  );
    $sql = new db_exec; $par = $sql->normalize($params); extract($par, EXTR_OVERWRITE);

    $SQL =  "select a.sq_cidade, a.sq_pais, a.sq_regiao, a.co_uf, a.nome, a.ddd, a.codigo_ibge, a.capital, a.codigo_externo, a.aeroportos,$crlf" .
            "       a.nome".C."', '".C."b.nome".C."', '".C."c.nome as google$crlf" .
            "  from co_cidade a$crlf" .
            "       inner join co_uf   b on (a.sq_pais = b.sq_pais and a.co_uf = b.co_uf)$crlf" .
            "       inner join co_pais c on (a.sq_pais = c.sq_pais)$crlf".
            " where sq_cidade = $p_chave$crlf";

    $l_rs = $sql->getInstanceOf($dbms, $SQL, $params);
    if (count($l_rs)==1) return $l_rs[0];
    return $l_rs;
  }
}
?>
