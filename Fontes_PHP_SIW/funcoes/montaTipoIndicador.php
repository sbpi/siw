<?
// =========================================================================
// Montagem de campo do tipo de indicador
// -------------------------------------------------------------------------
function montaTipoIndicador($label,$Chave,$Campo) {
  extract($GLOBALS);
  ShowHTML('          <td><font size="1">');
  if (Nvl($label,'')>'') ShowHTML($label.'</b><br>');
  if (strtoupper($Chave)=='P')
    ShowHTML('              <input '.$w_Disabled.' type="radio" name="'.$campo.'" value="P" checked> Processo <input '.$w_Disabled.' type="radio" name="'.$campo.'" value="R"> Resultado <input '.$w_Disabled.' type="radio" name="'.$campo.'" value=""> ND ');
  elseif (strtoupper($Chave)=='R')
    ShowHTML('              <input '.$w_Disabled.' type="radio" name="'.$campo.'" value="P"> Processo <input '.$w_Disabled.' type="radio" name="'.$campo.'" value="R" checked> Resultado <input '.$w_Disabled.' type="radio" name="'.$campo.'" value=""> ND ');
  else
    ShowHTML('              <input '.$w_Disabled.' type="radio" name="'.$campo.'" value="P"> Processo <input '.$w_Disabled.' type="radio" name="'.$campo.'" value="R" > Resultado <input '.$w_Disabled.' type="radio" name="'.$campo.'" value="" checked> ND ');
} 
?>