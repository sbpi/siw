<?php
extract($GLOBALS); 
include_once($w_dir_volta.'classes/db/DatabaseQueriesFactory.php');
include_once($w_dir_volta.'classes/sp/db_getUserData.php');
include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
include_once($w_dir_volta.'classes/ldap/ldap.php');
/**
* class sp_verificaSenha
*
* { Description :- 
*    Verifica se o usuário e senha informados estão corretos, e se o usuário está ativo.
* }
*/

class db_verificaSenha {
   function getInstanceOf($dbms, $p_cliente, $p_username, $p_senha) {

     // Verifica se a autenticação do usuário é pela aplicação ou por MS-AD/LDAP
      $sql = new DB_GetUserData; $rs = $sql->getInstanceOf($dbms, $p_cliente, $p_username);
      $w_tipo = f($rs,'tipo_autenticacao');

      if ($w_tipo == 'B' || $par=='Senha') { // O segundo teste evita autenticação da senha no LDAP
        // Verificação pelo banco de dados
        extract($GLOBALS,EXTR_PREFIX_SAME,'strchema'); $sql=$strschema.'sp_verificaSenha';
        $params=array('p_cliente'  =>array($p_cliente,     B_NUMERIC,     32),
                      'p_username' =>array($p_username,    B_VARCHAR,     60),
                      'p_senha'    =>array($p_senha,       B_VARCHAR,    255),
                      'p_result'   =>array(null,           B_CURSOR,      -1)
                      );
        $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
        $l_error_reporting = error_reporting(); 
        error_reporting(E_ERROR);
        if(!$l_rs->executeQuery()) {
          error_reporting($l_error_reporting);
          TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__);  
        } else {
          error_reporting($l_error_reporting);
          $l_data = $l_rs->getResultArray();
          if     ($l_rs->getNumRows()==0) { return 2; }
          elseif (f($l_data,'ativo') == 'N') { return 3; }
          else   { return 0; }
        }
      } else {
        $sql = new db_getCustomerData; $rs1 = $sql->getInstanceOf($dbms, $p_cliente);

        if ($w_tipo=='A') {
          $array = array(            
              'domain_controllers'    => f($rs1,'ad_domain_controlers'),
              'base_dn'               => f($rs1,'ad_base_dn')          ,
              'account_suffix'        => f($rs1,'ad_account_sufix')    ,               
          );
        } else {
          $array = array(            
              'domain_controllers'    => f($rs1,'ol_domain_controlers'),
              'base_dn'               => f($rs1,'ol_base_dn')          ,
              'account_suffix'        => f($rs1,'ol_account_sufix')    ,               
          );
        }

        $adldap = new adLDAP($array);
                                                                                                                                                         
        if(!$adldap->authenticate($p_username,$p_senha)){
          return 5;
        } else {
          // Testa se o usuário de rede existe e se não está bloqueado.
          $user = $adldap->user_info($p_username,array("userAccountControl"));
          $user_attrib = $adldap->account_attrib($user[0]['useraccountcontrol'][0]);
          if (in_array('ACCOUNTDISABLE',$user_attrib)) {
            return 4;
          }
        }
      }
   }
}    
?>
