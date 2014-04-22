<?php
include_once($w_dir_volta.'classes/sp/db_getSolicRubrica.php');
// =========================================================================
// Montagem da seleção das rubricas de um projeto
// -------------------------------------------------------------------------
function selecaoRubrica($label,$accesskey,$hint,$chave,$chaveAux,$sq_rubrica_destino,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $sql = new db_getSolicRubrica;
  if ($restricao=='RUBRICAS')                  { $RS = $sql->getInstanceOf($dbms,$chaveAux,null,'S',$sq_rubrica_destino,null,'N',null,null,'FOLHA'); }
  elseif ($restricao=='ARVORE')                { $RS = $sql->getInstanceOf($dbms,$chaveAux,null,'S',$sq_rubrica_destino,null,'N',null,null,'SUBORDINACAO'); }
  elseif (strpos($restricao,'FINANC')!==false) { $RS = $sql->getInstanceOf($dbms,$chaveAux,$w_menu,'S',null,$sq_rubrica_destino,null,null,null,$restricao); }
  else                                         { $RS = $sql->getInstanceOf($dbms,$chaveAux,null,'S',$sq_rubrica_destino,null,null,null,null,$restricao); }
  $RS = SortArray($RS,'ordena','asc','codigo','asc','nome','asc');
  if (nvl($label,'')=='') $l_label = ''; else $l_label = '<b>'.$label.'</b><br>';
  ShowHTML('          <td colspan="'.$colspan.'"'.((Nvl($hint,'')!='') ? ' title="'.$hint.'"': '').'>'.$l_label.'<SELECT ACCESSKEY="'.$accesskey.'" CLASS="STS" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  foreach ($RS as $row) {
    ShowHTML('          <option value="'.f($row,'sq_projeto_rubrica').'"'.((strpos('RUBRICAS,SELECAO',$restricao)!==false && f($row,'ultimo_nivel')=='N') ? ' DISABLED': '').((nvl(f($row,'sq_projeto_rubrica'),0)==nvl($chave,0)) ? ' SELECTED' : '').'>'.f($row,'codigo').' - '.f($row,'nome'));
  } 
  ShowHTML('          </select>');
} 
?>