<?
// =========================================================================
// Montagem da seleção de unidade monetária
// -------------------------------------------------------------------------
function selecaoMoeda($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  include_once($w_dir_volta.'classes/sp/db_getMoeda.php');
  $RS = db_getMoeda::getInstanceOf($dbms, $restricao, $chaveAux, 'S', null);
  $RS = SortArray($RS,'padrao','desc','nome','asc');
  if (!isset($hint)) {
     ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
     ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }

  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if (nvl(f($row,'sq_moeda'),0)==nvl($chave,0)) {
       ShowHTML('          <option value="'.f($row,'sq_moeda').'" SELECTED>'.f($row,'nome'));
    } else {
       ShowHTML('          <option value="'.f($row,'sq_moeda').'">'.f($row,'nome'));
    }
  }
  ShowHTML('          </select>');
}
?>
