<? 
// =========================================================================
// Montagem da seleção dos meses do ano
// -------------------------------------------------------------------------
function selecaoMes($label,$accesskey,$hint,$cliente,$chave,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  if (!isset($hint))
    ShowHTML('          <td colspan="'.$colspan.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else
    ShowHTML('          <td colspan="'.$colspan.'" TITLE="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  if (Nvl($chave,'')=='01')   ShowHTML('          <option value="01" SELECTED>Janeiro');
  else                        ShowHTML('          <option value="01">Janeiro');
  if (Nvl($chave,'')=='02')   ShowHTML('          <option value="02" SELECTED>Fevereiro');
  else                        ShowHTML('          <option value="02">Fevereiro');
  if (Nvl($chave,'')=='03')   ShowHTML('          <option value="03" SELECTED>Março');
  else                        ShowHTML('          <option value="03">Março');
  if (Nvl($chave,'')=='04')   ShowHTML('          <option value="04" SELECTED>Abril');
  else                        ShowHTML('          <option value="04">Abril');
  if (Nvl($chave,'')=='05')   ShowHTML('          <option value="05" SELECTED>Maio');
  else                        ShowHTML('          <option value="05">Maio');
  if (Nvl($chave,'')=='06')   ShowHTML('          <option value="06" SELECTED>Junho');
  else                        ShowHTML('          <option value="06">Junho');
  if (Nvl($chave,'')=='07')   ShowHTML('          <option value="07" SELECTED>Julho');
  else                        ShowHTML('          <option value="07">Julho');
  if (Nvl($chave,'')=='08')   ShowHTML('          <option value="08" SELECTED>Agosto');
  else                        ShowHTML('          <option value="08">Agosto');
  if (Nvl($chave,'')=='09')   ShowHTML('          <option value="09" SELECTED>Setembro');
  else                        ShowHTML('          <option value="09">Setembro');
  if (Nvl($chave,'')=='10')   ShowHTML('          <option value="10" SELECTED>Outubro');
  else                        ShowHTML('          <option value="10">Outubro');
  if (Nvl($chave,'')=='11')   ShowHTML('          <option value="11" SELECTED>Novembro');
  else                        ShowHTML('          <option value="11">Novembro');
  if (Nvl($chave,'')=='12')   ShowHTML('          <option value="12" SELECTED>Dezembro');
  else                        ShowHTML('          <option value="12">Dezembro');
  ShowHTML('          </select>');
} 
?>