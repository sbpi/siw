<?php
$w_dir_volta = '../';
include_once($w_dir_volta.'constants.inc');
include_once($w_dir_volta.'funcoes.php');

echo montaRelatorioXLS($_POST[ 'conteudo' ]);

function montaRelatorioXLS($conteudo = null){
  extract($GLOBALS);
  $conteudo = str_replace ("\\\"", '\'', $conteudo);
  $conteudo = str_replace ("\\&quot;", '', $conteudo);
  $conteudo = str_replace ("\\\"", '"', $conteudo);
  //$conteudo = str_ireplace ('WIDTH="100%"', '', $conteudo);
  $conteudo = str_ireplace ('<a', '<x', str_replace ('</a', '</x', $conteudo));
  $conteudo = str_ireplace ('<img', '<x', str_replace ('</img', '</x', $conteudo));
  
  $conteudo = preg_replace("(<td(.class=(\"?)remover(\"?).*?)>(.*?)</td>)Ssi","",$conteudo);
  $conteudo = preg_replace("(<span(.class=(\"?)remover(\"?).*?)>(.*?)</span>)Ssi","",$conteudo);
  $body .= headerWord('PORTRAIT');
  $body.=$conteudo."\r\n";
  
  
  return preg_replace("/(<\/?)(\w+)( |>|\/)/e", "'\\1'.strtolower('\\2').'\\3'",$body);

}
?>