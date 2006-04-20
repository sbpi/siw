<?
include_once($w_dir_volta.'classes/sp/db_getTramiteList.php');
// =========================================================================
// Montagem da seleção da fase de uma solicitação
// -------------------------------------------------------------------------
function selecaoFaseCheck($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  $RS = db_getTramiteList::getInstanceOf($dbms, $chaveAux, null);
  array_key_case_change(&$RS);
  $RS = SortArray($RS,'ordem','asc');
  //$RS->Filter="ativo = 'S' or sigla = 'AT'";
  ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b>');
  foreach($RS as $row)  {
    if (nvl($chave,'')=="') { ShowHTML('          <BR><input type="CHECKBOX" name="'.$campo.'" value="'.f($row,'SQ_SIW_TRAMITE').'" CHECKED>'.f($row,'nome')); }
    else {
      $l_marcado='N';
      $l_chave=$chave.',";
      while((strpos($l_chave,",') ? strpos($l_chave,",')+1 : 0)>0) {
        $l_item=trim(substr($l_chave,0,(strpos($l_chave,",') ? strpos($l_chave,",')+1 : 0)-1));
        $l_chave=substr($l_chave,(strpos($l_chave,",') ? strpos($l_chave,",')+1 : 0)+1-1,100);
        if ($l_item>'') {
          if (f($row,'sq_siw_tramite')]==$l_item]) { $l_marcado="S"; };
        }
      }
      if ($l_marcado=='S') 
         { ShowHTML('          <BR><input type="CHECKBOX" name="'.$campo.'" value="'.f($row,'sq_siw_tramite').'" CHECKED>'.f($row,'nome')); }
      else
         { ShowHTML('          <BR><input type="CHECKBOX" name="'.$campo.'" value="'.f($row,'sq_siw_tramite').'" >'.f($row,'nome')); }
    }
  }
  ShowHTML('          </select>');

  return $function_ret;
}
?>
