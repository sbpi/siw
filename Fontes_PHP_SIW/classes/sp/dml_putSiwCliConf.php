<?
extract($GLOBALS); include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
/**
* class dml_putSiwCliConf
*
* { Description :- 
*    Manipula registros de siw_cliente
* }
*/

class dml_putSiwCliConf {
   function getInstanceOf($dbms, $chave, $tamanho_minimo_senha, $tamanho_maximo_senha, $maximo_tentativas,
        $dias_vigencia_senha, $dias_aviso_expiracao, $smtp_server, $siw_email_nome, $siw_email_conta,
        $siw_email_senha, $logo, $logo1, $fundo, $tipo, $upload_maximo) {
     $sql=$strschema.'sp_putSiwCliConf';
     $params=array('chave'                  =>array($chave,                 B_NUMERIC,     32),
                   'tamanho_minimo_senha'   =>array($tamanho_minimo_senha,  B_NUMERIC,     32),
                   'tamanho_maximo_senha'   =>array($tamanho_maximo_senha,  B_NUMERIC,     32),
                   'maximo_tentativas'      =>array($maximo_tentativas,     B_NUMERIC,     32),
                   'dias_vigencia_senha'    =>array($dias_vigencia_senha,   B_NUMERIC,     32),
                   'dias_aviso_expiracao'   =>array($dias_aviso_expiracao,  B_NUMERIC,     32),
                   'smtp_server'            =>array($smtp_server,           B_VARCHAR,     60),
                   'siw_email_nome'         =>array($siw_email_nome,        B_VARCHAR,     60),
                   'siw_email_conta'        =>array($siw_email_conta,       B_VARCHAR,     60),
                   'siw_email_senha'        =>array($siw_email_senha,       B_VARCHAR,     60),
                   'logo'                   =>array($logo,                  B_VARCHAR,     60),
                   'logo1'                  =>array($logo1,                 B_VARCHAR,     60),
                   'fundo'                  =>array($fundo,                 B_VARCHAR,     60),
                   'tipo'                   =>array($tipo,                  B_VARCHAR,     15),
                   'upload_maximo'          =>array($upload_maximo,         B_NUMERIC,     32)
                  );
     $l_rs = DatabaseQueriesFactory::getInstanceOf($sql, $dbms, $params, DB_TYPE);
     $l_error_reporting = error_reporting(); 
     error_reporting(0); 
     if(!$l_rs->executeQuery()) { 
       error_reporting($l_error_reporting); 
       TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__); 
     } else {
       error_reporting($l_error_reporting); 
       return true;
     }
   }
}
?>
