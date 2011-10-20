<?php
extract($GLOBALS);
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class db_getValorDiaria  
*
* { Description :- 
*    Recupera os valores de diárias
* }
*/

class db_getValorDiaria {
   function getInstanceOf($dbms, $p_cliente, $p_chave) {
     extract($GLOBALS,EXTR_PREFIX_SAME,'strchema');
     $params=array('p_cliente' =>array($p_cliente,      B_INTEGER,        32),
                   'p_chave'   =>array(tvl($p_chave),   B_INTEGER,        32),
                   'p_result'  =>array(null,            B_CURSOR,         -1)
                  );

     $sql = new db_exec; $par = $sql->normalize($params); extract($par,EXTR_OVERWRITE);

     $SQL = "select a.sq_valor_diaria as chave, a.continente, a.nacional, a.sq_pais, a.sq_cidade,".$crlf.
            "       a.sq_categoria_diaria, a.valor, a.tipo_diaria, a.sq_moeda, ".$crlf.
            "       case a.nacional when 'S' then 'Sim' else 'Não' end as nm_nacional,".$crlf.
            "       case a.continente".$crlf.
            "            when 1 then 'América'".$crlf.
            "            when 2 then 'Europa'".$crlf.
            "            when 3 then 'Ásia'".$crlf.
            "            when 4 then 'África'".$crlf.
            "            when 5 then 'Oceania' ".$crlf.
            "       end as nm_continente,".$crlf.
            "       case tipo_diaria".$crlf.
            "            when 'D' then 'Diária'".$crlf.
            "            when 'H' then 'Hospedagem'".$crlf.
            "            when 'V' then 'Locação'".$crlf.
            "       end as nm_tipo_diaria,".$crlf.
            "       b.nome as nm_pais,".$crlf.
            "       c.nome as nm_cidade,".$crlf.
            "       c.co_uf as nm_uf,".$crlf.
            "       d.nome as nm_moeda, d.sigla as sg_moeda,".$crlf.
            "       e.nome as nm_categoria_diaria".$crlf.
            "  from pd_valor_diaria                 a".$crlf.
            "       left   join co_pais             b  on (a.sq_pais             = b.sq_pais)".$crlf.
            "       left   join co_cidade           c  on (a.sq_cidade           = c.sq_cidade)".$crlf.
            "       inner  join co_moeda            d  on (a.sq_moeda            = d.sq_moeda)".$crlf.
            "       inner  join pd_categoria_diaria e  on (a.sq_categoria_diaria = e.sq_categoria_diaria)".$crlf.
            " where a.cliente = ".$p_cliente.$crlf.
            (($p_chave>'') ? "  and a.sq_valor_diaria = ".$p_chave.$crlf : "");

     $l_rs = $sql->getInstanceOf($dbms,$SQL,$recordcount);
     return $l_rs;
   }
}
?>
