<?
include_once($w_dir_volta.'classes/sp/db_getSolicEtapa.php');
// =========================================================================
// Montagem da seleção de etapas do projeto
// -------------------------------------------------------------------------
function selecaoEtapa($label,$accesskey,$hint,$chave,$chaveAux,$chaveAux2,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  if (!isset($hint)) { 
    ShowHTML('          <td valign="top"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>'); 
  } else { 
    ShowHTML('          <td valign="top" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>'); 
  }
  ShowHTML('          <option value="">---');

  if ($restricao=='Pesquisa') {
    $RST = db_getSolicEtapa::getInstanceOf($dbms, $chaveAux, null, 'LSTNULL', nvl($chaveAux2,0));
  } else {
    $RST = db_getSolicEtapa::getInstanceOf($dbms, $chaveAux, null, 'LSTNULL', null);
  }
  $RST = SortArray($RST,'ordem','asc');
  foreach($RST as $rowT) {
    if ($restricao=='Grupo' && (f($rowT,'vincula_atividade')=='N' || f($rowT,'perc_conclusao')>=100)) { 
      ShowHTML('          <option value="">'.f($rowT,'ordem').'. '.f($rowT,'titulo')); 
    } else { 
      if (nvl(f($rowT,'sq_projeto_etapa'),0)==nvl($chave,0)) { ShowHTML('          <option value="'.f($rowT,'sq_projeto_etapa').'" SELECTED>'.f($rowT,'ordem').'. '.f($rowT,'titulo')); } else { ShowHTML('          <option value="'.f($rowT,'sq_projeto_etapa').'">'.f($rowT,'ordem').'. '.f($rowT,'titulo')); } 
    }
    if ($restricao=='Pesquisa') {
      $RST1 = db_getSolicEtapa::getInstanceOf($dbms, $chaveAux, f($rowT,'sq_projeto_etapa'), 'LSTNIVEL', nvl($chaveAux2,0));
    } else {
      $RST1 = db_getSolicEtapa::getInstanceOf($dbms, $chaveAux, f($rowT,'sq_projeto_etapa'), 'LSTNIVEL', null);
    }
    $RST1 = SortArray($RST1,'ordem','asc');
    foreach($RST1 as $rowT1) {
      if ($restricao=='Grupo' && (f($rowT1,'vincula_atividade')=='N' || f($rowT1,'perc_conclusao')>=100)) { 
        ShowHTML('          <option value="">'.f($rowT,'ordem').'.'.f($rowT1,'ordem').'. '.f($rowT1,'titulo')); 
      } else { 
        if (nvl(f($rowT1,'sq_projeto_etapa'),0)==nvl($chave,0)) { ShowHTML('          <option value="'.f($rowT1,'sq_projeto_etapa').'" SELECTED>'.f($rowT1,'ordem').'. '.f($rowT1,'titulo')); } else { ShowHTML('          <option value="'.f($rowT1,'sq_projeto_etapa').'">'.f($rowT,'ordem').'.'.f($rowT1,'ordem').'. '.f($rowT1,'titulo')); } 
      }
      if ($restricao=='Pesquisa') {
        $RST2 = db_getSolicEtapa::getInstanceOf($dbms, $chaveAux, f($rowT1,'sq_projeto_etapa'), 'LSTNIVEL', nvl($chaveAux2,0));
      } else {
        $RST2 = db_getSolicEtapa::getInstanceOf($dbms, $chaveAux, f($rowT1,'sq_projeto_etapa'), 'LSTNIVEL', null);
      }
      $RST2 = SortArray($RST2,'ordem','asc');
      foreach($RST2 as $rowT2) {
        if ($restricao=='Grupo' && (f($rowT2,'vincula_atividade')=='N' || f($rowT2,'perc_conclusao')>=100)) { 
          ShowHTML('          <option value="">'.f($rowT,'ordem').'.'.f($rowT1,'ordem').'.'.f($rowT2,'ordem').'. '.f($rowT2,'titulo')); 
        } else { 
          if (nvl(f($rowT2,'sq_projeto_etapa'),0)==nvl($chave,0)) { ShowHTML('          <option value="'.f($rowT2,'sq_projeto_etapa').'" SELECTED>'.f($rowT2,'ordem').'. '.f($rowT2,'titulo')); } else { ShowHTML('          <option value="'.f($rowT2,'sq_projeto_etapa').'">'.f($rowT,'ordem').'.'.f($rowT1,'ordem').'.'.f($rowT2,'ordem').'. '.f($rowT2,'titulo')); } 
        }
        if ($restricao=='Pesquisa') {
          $RST3 = db_getSolicEtapa::getInstanceOf($dbms, $chaveAux, f($rowT2,'sq_projeto_etapa'), 'LSTNIVEL', nvl($chaveAux2,0));
        } else {
          $RST3 = db_getSolicEtapa::getInstanceOf($dbms, $chaveAux, f($rowT2,'sq_projeto_etapa'), 'LSTNIVEL', null);
        }
        $RST3 = SortArray($RST3,'ordem','asc');
        foreach($RST3 as $rowT3) {
          if ($restricao=='Grupo' && (f($rowT3,'vincula_atividade')=='N' || f($rowT3,'perc_conclusao')>=100)) { 
            ShowHTML('          <option value="">'.f($rowT,'ordem').'.'.f($rowT1,'ordem').'.'.f($rowT2,'ordem').'.'.f($rowT3,'ordem').'. '.f($rowT3,'titulo')); 
          } else { 
            if (nvl(f($rowT3,'sq_projeto_etapa'),0)==nvl($chave,0)) { ShowHTML('          <option value="'.f($rowT3,'sq_projeto_etapa').'" SELECTED>'.f($rowT3,'ordem').'. '.f($rowT3,'titulo')); } else { ShowHTML('          <option value="'.f($rowT3,'sq_projeto_etapa').'">'.f($rowT,'ordem').'.'.f($rowT1,'ordem').'.'.f($rowT2,'ordem').'.'.f($rowT3,'ordem').'. '.f($rowT3,'titulo')); } 
          }
          if ($restricao=='Pesquisa') {
            $RST4 = db_getSolicEtapa::getInstanceOf($dbms, $chaveAux, f($rowT3,'sq_projeto_etapa'), 'LSTNIVEL', nvl($chaveAux2,0));
          } else {
            $RST4 = db_getSolicEtapa::getInstanceOf($dbms, $chaveAux, f($rowT3,'sq_projeto_etapa'), 'LSTNIVEL', null);
          }
          $RST4 = SortArray($RST4,'ordem','asc');
          foreach($RST4 as $rowT4) {
            if ($restricao=='Grupo' && (f($rowT4,'vincula_atividade')=='N' || f($rowT4,'perc_conclusao')>=100)) { 
              ShowHTML('          <option value="">'.f($rowT,'ordem').'.'.f($rowT1,'ordem').'.'.f($rowT2,'ordem').'.'.f($rowT3,'ordem').'.'.f($rowT4,'ordem').'. '.f($rowT4,'titulo')); 
            } else { 
              if (nvl(f($rowT4,'sq_projeto_etapa'),0)==nvl($chave,0)) { ShowHTML('          <option value="'.f($rowT4,'sq_projeto_etapa').'" SELECTED>'.f($rowT4,'ordem').'. '.f($rowT4,'titulo')); } else { ShowHTML('          <option value="'.f($rowT4,'sq_projeto_etapa').'">'.f($rowT,'ordem').'.'.f($rowT1,'ordem').'.'.f($rowT2,'ordem').'.'.f($rowT3,'ordem').'.'.f($rowT4,'titulo')); } 
            }
          }
        }
      }
    }
  }
  ShowHTML('          </select>');
  return $function_ret;
}
?>