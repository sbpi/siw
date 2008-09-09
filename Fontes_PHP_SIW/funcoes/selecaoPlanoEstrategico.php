<?
include_once($w_dir_volta.'classes/sp/db_getPlanoEstrategico.php');
// =========================================================================
// Montagem da seleção de plano estratégico
// -------------------------------------------------------------------------
function selecaoPlanoEstrategico($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  if (!isset($hint)) ShowHTML('          <td colspan="'.$colspan.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  else               ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  ShowHTML('          <option value="">---');
  if ($restricao=='OBJETIVO' || $restricao=='TODOS' || $restricao=='TULTIMO') $l_ativo = null; else $l_ativo = 'S';
  if ($restricao=='ULTIMO' || $restricao=='TULTIMO') {
    $RST = db_getPlanoEstrategico::getInstanceOf($dbms,$w_cliente,null,null,null,null,null,$l_ativo,'REGISTROS');
    foreach ($RST as $row) {
      if (f($row,'filho')==0) {
        if (nvl(f($row,'chave'),0)==nvl($chave,0)) { 
          ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'titulo')); 
        } else { 
          ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'titulo')); 
        }
      }
    }
  } else {
    $RST = db_getPlanoEstrategico::getInstanceOf($dbms,$w_cliente,null,null,null,null,null,$l_ativo,'IS NULL');
    foreach ($RST as $row) {
      if ($restricao!='OBJETIVO' || ($restricao=='OBJETIVO' && f($row,'qt_objetivo')>0)) {
        if (nvl(f($row,'chave'),0)==nvl($chave,0)) { ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'titulo')); } else { ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'titulo')); }
        $RST1 = db_getPlanoEstrategico::getInstanceOf($dbms,$w_cliente,null,null,null,null,null,$l_ativo,f($row,'chave'));
        foreach($RST1 as $row1) {
          if ($restricao!='OBJETIVO' || ($restricao=='OBJETIVO' && f($row1,'qt_objetivo')>0)) {
            if (nvl(f($row1,'chave'),0)==nvl($chave,0)) { ShowHTML('          <option value="'.f($row1,'chave').'" SELECTED>&nbsp;&nbsp;&nbsp;'.f($row1,'titulo')); } else { ShowHTML ('          <option value="'.f($row1,'chave').'">&nbsp;&nbsp;&nbsp;'.f($row1,'titulo')); }
            $RST2 = db_getPlanoEstrategico::getInstanceOf($dbms,$w_cliente,null,null,null,null,null,$l_ativo,f($row1,'chave'));
            foreach($RST2 as $row2) {
              if ($restricao!='OBJETIVO' || ($restricao=='OBJETIVO' && f($row2,'qt_objetivo')>0)) {
                if (nvl(f($row2,'chave'),0)==nvl($chave,0)) { ShowHTML('          <option value="'.f($row2,'chave').'" SELECTED>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.f($row2,'titulo')); } else { ShowHTML('          <option value="'.f($row2,'chave').'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.f($row2,'titulo')); }
                $RST3 = db_getPlanoEstrategico::getInstanceOf($dbms,$w_cliente,null,null,null,null,null,$l_ativo,f($row2,'chave'));
                foreach($RST3 as $row3) {
                  if ($restricao!='OBJETIVO' || ($restricao=='OBJETIVO' && f($row3,'qt_objetivo')>0)) {
                    if (nvl(f($row3,'chave'),0)==nvl($chave,0)) { ShowHTML('          <option value="'.f($row3,'chave').'" SELECTED>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.f($row3,'titulo')); } else { ShowHTML ('          <option value="'.f($row3,'chave').'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.f($row3,'titulo')); }
                    $RST4 = db_getPlanoEstrategico::getInstanceOf($dbms,$w_cliente,null,null,null,null,null,$l_ativo,f($row3,'chave'));
                    foreach($RST4 as $row4) {
                      if ($restricao!='OBJETIVO' || ($restricao=='OBJETIVO' && f($row4,'qt_objetivo')>0)) {
                        if (nvl(f($row4,'chave'),0)==nvl($chave,0)) { ShowHTML('          <option value="'.f($row4,'chave').'" SELECTED>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.f($row4,'titulo')); } else { ShowHTML('          <option value="'.f($row4,'chave').'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.f($row4,'titulo')); }
                      }
                    }
                  }
                }
              }
            }
          }
        }
      }
    }
  }
  ShowHTML('          </select>');
}
?>
