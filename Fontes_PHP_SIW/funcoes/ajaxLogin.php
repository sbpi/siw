<?php

$w_dir_volta = '../';
include_once("../constants.inc");
include_once("../funcoes.php");
include_once('../classes/db/abreSessao.php');
include_once('../classes/sp/db_getCustomerData.php');

if(isset($_POST["ajaxLogin"]) && $_POST["ajaxLogin"]=="ajaxLogin"){
    extract($GLOBALS);

    $p_cliente = $_POST["p_cliente"];
    $p_dbms    = $_POST["p_dbms"];

    if(Nvl($p_dbms,'')!=""){
        session_start();
        $_SESSION['DBMS']=$p_dbms;
        $dbms = abreSessao::getInstanceOf($p_dbms);

        $l_rs = db_getCustomerData::getInstanceOf($dbms,$p_cliente);

        $ad = Nvl(f($l_rs,'ad_domain_controlers'),null);
        $ol = Nvl(f($l_rs,'ol_domain_controlers'),null);

        FechaSessao($dbms);

        unset($_SESSION);
        session_destroy();

        if(Nvl( $ad,'')!="" || Nvl( $ol,'')!=""){
            die("true");
        }
    }
    die("false");
}
?>