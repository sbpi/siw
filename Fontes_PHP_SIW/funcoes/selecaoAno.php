<?
include_once($w_dir_volta.'classes/sp/db_getCTEspecificacao.php');
// =========================================================================
// Montagem da seleção de anos
// -------------------------------------------------------------------------
function selecaoAno($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$anos=2) {
  extract($GLOBALS);
  $l_RS = db_getCTEspecificacao::getInstanceOf($dbms,$w_cliente,null,null,null,null,null,null,'ANOS');
  $l_cont=strftime('%Y',(time()))-$anos;
  if (!isset($hint)) {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" class="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td valign="top" TITLE="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" class="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } 
  ShowHTML('          <option value="">---');
  if($restricao=='ESPEC') {
    $l_teste = 'sim';
    while($l_cont<strftime('%Y',(time()))+($anos+1)) {
      foreach($l_RS as $l_row) {
         if(f($l_row,'ano')==$l_cont) $l_teste = 'nao'; 
      }
      if($l_teste=='sim') {
        if (nvl($l_cont,0)==nvl($chave,0)) {
          ShowHTML('          <option value="'.$l_cont.'" SELECTED>'.$l_cont);
        } else {
          ShowHTML('          <option value="'.$l_cont.'">'.$l_cont);
        } 
        $l_cont += 1;
      }
    } 
  } else {
    while($l_cont<strftime('%Y',(time()))+($anos+1)) {
      if (nvl($l_cont,0)==nvl($chave,0)) {
        ShowHTML('          <option value="'.$l_cont.'" SELECTED>'.$l_cont);
      } else {
        ShowHTML('          <option value="'.$l_cont.'">'.$l_cont);
      } 
      $l_cont += 1;
    } 
  }
  ShowHTML('          </select>');
} 
?>