<?php
// =========================================================================
// Verifica se o usuário está logado no FABS-WEB
// -------------------------------------------------------------------------
function login_fabs() {
  extract($GLOBALS);
  // Verifica se o usuario está logado no FABS-WEB.
  // A chamada deve ser feita por procedure pois é feita uma atualização de dados
  // e, se usado SELECT em função, a atualização não é permitida.
  $p_logado = 'N';
  $sql=$strschema.'un_verifica_sessao';
  $params=array('p_usuario' =>array($p_usuario, B_INTEGER,  32),
                'p_inicio'  =>array(tvl(null),  B_VARCHAR,  40),
                'p_logado'  =>array(&$p_logado, B_VARCHAR,   1)
               );
  $lql = new DatabaseQueriesFactory; $l_rs = $lql->getInstanceOf($sql, $dbms, $params, DB_TYPE);
  $l_error_reporting = error_reporting(); error_reporting(E_ERROR); 
  if(!$l_rs->executeQuery()) { 
    error_reporting($l_error_reporting);
    TrataErro($sql, $l_rs->getError(), $params, __FILE__, __LINE__, __CLASS__); 
  } else {
    error_reporting($l_error_reporting); 
  }
  if ($p_logado=='N') {
    $SQL = "select endereco from corporativo.un_parametros_ws_enderecos where codigo = 301";
    $sql = new db_exec; $RS = $sql->getInstanceOf($dbms,$SQL,$recordcount);
    $RS = $RS[0];
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Seu acesso foi desativado por exceder o limite de inatividade.\nSe desejar retornar ao FABS-WEB, identifique-se.");');
    ShowHTML(' top.location.href="' . f($RS,'endereco'). 'Frm_Login.LogOut";');
    ScriptClose();
    exit();
  }
}

// =========================================================================
// Montagem padrão de documento HTML
// -------------------------------------------------------------------------
function HtmlOpen($cProperties) {
   ShowHTML('<!DOCTYPE html>');
   ShowHTML('<HTML>');
   ShowHTML('<HEAD>');
   ShowHTML('<meta http-equiv="x-ua-compatible" content="IE=9">');
   ShowHTML('<link rel="stylesheet" type="text/css" href="//app.brasilia.unesco.org/fabsweb-scripts/content/legacy.css">');
   ShowHTML('<link rel="stylesheet" type="text/css" href="//app.brasilia.unesco.org/fabsweb-scripts/content/bootstrap/bootstrap.css">');
}

// =========================================================================
// Devolve array do elemento informado a partir da string
// -------------------------------------------------------------------------
function retornaElementoXML($stringXML,$elemento) {
  
  $result = array();
  $chave  = ' '.$elemento.'="';
  $tam    = strlen($chave);
  $existe = (strpos($stringXML,$chave));
  $temp   = substr($stringXML,strpos($stringXML,$chave)+$tam);
  
  while($existe) {
    $pos   = strpos($temp,'" ');
    $value = substr($temp,0,$pos);
    array_push($result,$value);
    $existe = (strpos($temp,$chave));
    $temp   = substr($temp,strpos($temp,$chave)+$tam);
  }
  
  return $result;
  
}
// =========================================================================
// Chama web service SOAP para processamento de faturas eletrôncias
// -------------------------------------------------------------------------
function ws_fatura($xml) {
  extract($GLOBALS);
  
  // Recupera o endereço do WS e configura os parâmetros para chamada.
  $SQL = "select endereco from corporativo.un_parametros_ws_enderecos where codigo = 403";
  $sql = new db_exec; $RS = $sql->getInstanceOf($dbms,$SQL,$recordcount);
  $RS = $RS[0];
  $url      = f($RS,'endereco');
  $wsdl     = $url.'?WSDL';
  $function = $url.'/FaturarBilhetes';
  
  // Procedimento para obter resposta do WS
  $query_data = http_build_query(array('xml' => $xml));
  $context = stream_context_create(
      array(
          'http' => array(
              'method' => 'POST',
              'header'  => "Content-type: application/x-www-form-urlencoded\r\n" . "Content-Length: " . strlen($query_data) . "\r\n",
              'content' => $query_data
          )
      )
  );

  $xmlString = file_get_contents( $function, false, $context );
  
  // Retorna o resultado no formato HTML ENTITIES (< = &lt; > = &gt; etc.)
  return($xmlString);
}
