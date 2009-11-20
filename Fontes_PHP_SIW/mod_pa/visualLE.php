<?php
// =========================================================================
// Rotina de visualização dos dados do documento
// -------------------------------------------------------------------------
function VisualLE($l_chave, $l_menu=null, $l_formato='WORD') {
  extract($GLOBALS);

  $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);

  // Recupera os dados da guia
  // Recupera os dados da solicitacao
  $RS1 = db_getSolicPA::getInstanceOf($dbms,null,$l_usuario,'PAELI',5,
          null,null,null,null,null,null,null,null,null,null,
          $l_chave,null,null,null,null,null,null,
          null,null,null,null,null,null,null,null,null,null,null);
  foreach($RS1 as $row) { $RS1 = $row; break; }
  
  $RS_Dados = db_getPAElimItem::getInstanceOf($dbms,null,$l_chave,null,null,null,null);
  $RS_Dados = SortArray($RS_Dados,'nm_arquivo_local','asc','sg_unid_caixa','asc','nr_caixa','asc','pasta','asc','ano','asc','protocolo','asc'); 
  
  if ($l_formato=='WORD') $l_html = BodyOpenWord(null); else $l_html = '';
  $w_linha = 99;
  $w_pag   = 1;
  $w_devolvidos = false;

  foreach ($RS_Dados as $row) {
    if (nvl(f($row,'devolucao'),'')!='') $w_devolvidos = true;
    if (($w_linha > 30 && $l_formato=='WORD') || ($w_pag==1 && $l_formato!='WORD')) {
      if ($w_pag>1 && $l_formato=='WORD') {
        $l_html.=chr(13).'    </table>';
        $l_html.=chr(13).'    <tr><td colspan=2><p>&nbsp;</p>';
        $l_html.=chr(13).'    <tr><td colspan=2><table border=0 width="100%"><tr valign="top">';
        $l_html.=chr(13).'      <td align="center" width=50%>__________________________________________________<br><b>PRESIDENTE DA COMISSÃO PERMANENTE DE AVALIAÇÃO</b>';
        $l_html.=chr(13).'      <td align="center" width=50%>__________________________________________________<br><b>AUTORIDADE A QUEM COMPETE AUTORIZAR</b>';
        $l_html.=chr(13).'    </table>';
        $l_html.=chr(13).'  </table>';
        $l_html.=chr(13).'</table>';
        $l_html.=chr(13).'<br style="page-break-after:always">';
      }
      $l_html.=chr(13).'<table width="98%" border="0" cellspacing="3">';
      if ($l_formato=='WORD') {
        $l_html.=chr(13).'<tr><td colspan="2">';
        $l_html.=chr(13).'  <table width="100%" border=0>';
        $l_html.=chr(13).'    <tr valign="top">';
        $l_html.=chr(13).'      <td><font size=1><b>'.strtoupper(f($RS,'nome').' - '.f($RS,'nome_resumido')).'</b></font></td>';
        $l_html.=chr(13).'      <td align="right"><font size=1><b>Pág. '.$w_pag.'</b></td></font></tr>';
        $l_html.=chr(13).'    <tr valign="top">';
        $l_html.=chr(13).'      <td><font size=1><b>'.strtoupper($conSgSistema.' - Módulo de '.f($RS_Menu,'nm_modulo')).'</b></font></td>';
        $l_html.=chr(13).'      <td align="right"><font size=1><b>'.datahora().'</b></td></font></tr>';
        $l_html.=chr(13).'  </table>';
      }
      $l_html.=chr(13).'<tr><td colspan="2" align="center">';
      $l_html.=chr(13).'    <table width="100%" border="0" cellspacing="3">';
      $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
      $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0" align=center><font size="2"><b>FORMULÁRIO DE ELIMINAÇÃO '.f($RS1,'codigo_interno').'</b></font></td></tr>';
      $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
      $l_html.=chr(13).'   <tr><td width="30%"><b>Responsável pela seleção:</b></td>';
      $l_html.=chr(13).'       <td>'.f($RS1,'nm_solic').'</td></tr>';
      $l_html.=chr(13).'   <tr><td width="30%"><b>Unidade solicitante:</b></td>';
      $l_html.=chr(13).'       <td>'.f($RS1,'nm_unidade_resp').'</td></tr>';
      
      $l_html.=chr(13).'   <tr><td colspan=2><br><b>PROTOCOLOS RELACIONADOS</b></td></tr>';
      $l_html.=chr(13).'   <tr><td colspan=2><table border=1 width="100%">';
      $l_html.=chr(13).'     <tr align="center">';
      $l_html.=chr(13).'       <td colspan=3><b>Localização</td>';
      $l_html.=chr(13).'       <td rowspan=2 width="1%" nowrap><b>Guarda</td>';
      $l_html.=chr(13).'       <td rowspan=2 width="1%" nowrap><b>Protocolo</td>';
      $l_html.=chr(13).'       <td rowspan=2 width="1%" nowrap><b>Tipo</td>';
      $l_html.=chr(13).'       <td colspan=4><b>Documento original</td>';
      $l_html.=chr(13).'     </tr>';
      $l_html.=chr(13).'     <tr valign="top" align="center">';
      $l_html.=chr(13).'       <td><b>Local</td>';
      $l_html.=chr(13).'       <td><b>Caixa</td>';
      $l_html.=chr(13).'       <td><b>Pasta</td>';
      $l_html.=chr(13).'       <td><font size=1><b>Espécie</b></font></td>';
      $l_html.=chr(13).'       <td><font size=1><b>Nº</b></font></td>';
      $l_html.=chr(13).'       <td><font size=1><b>Data</b></font></td>';
      $l_html.=chr(13).'       <td><font size=1><b>Procedência</b></font></td>';
      $l_html.=chr(13).'     </tr>';
      $w_pag   += 1;
      $w_linha = 6;
    } 
    $l_html.=chr(13).'     <tr valign="top">';
    $l_html.=chr(13).'       <td><font size=1>'.f($row,'nm_arquivo_local').'</td>';
    $l_html.=chr(13).'       <td><font size=1>'.f($row,'nr_caixa').'/'.f($row,'sg_unid_caixa').'</td>';
    $l_html.=chr(13).'       <td align="center"><font size=1>'.f($row,'pasta').'</td>';
    $l_html.=chr(13).'       <td><font size=1>'.f($row,'prazo_guarda').'</td>';
    $l_html.=chr(13).'       <td align="center" nowrap><font size=1>'.f($row,'protocolo');
    $l_html.=chr(13).'       <td><font size=1>'.f($row,'nm_tipo').'</td>';
    $l_html.=chr(13).'       <td><font size=1>'.f($row,'nm_especie').'</td>';
    $l_html.=chr(13).'       <td><font size=1>'.f($row,'numero_original').'</td>';
    $l_html.=chr(13).'       <td align="center"><font size=1>'.date(d.'/'.m.'/'.y,f($row,'inicio')).'</font></td>';
    $l_html.=chr(13).'       <td>&nbsp;'.f($row,'nm_origem_doc').'</td>';
    $l_html.=chr(13).'     </tr>';
    $w_linha += 1;
  }
  $l_html.=chr(13).'    </table>';
  if ($l_formato=='WORD') {
    $l_html.=chr(13).'    <tr><td colspan=2><p>&nbsp;</p>';
    $l_html.=chr(13).'    <tr><td colspan=2><table border=0 width="100%"><tr valign="top">';
    $l_html.=chr(13).'      <td align="center" width=50%>__________________________________________________<br><b>PRESIDENTE DA COMISSÃO PERMANENTE DE AVALIAÇÃO</b>';
    $l_html.=chr(13).'      <td align="center" width=50%>__________________________________________________<br><b>AUTORIDADE A QUEM COMPETE AUTORIZAR</b>';
    $l_html.=chr(13).'    </table>';
  }
  $l_html.=chr(13).'  </table>';
  $l_html.=chr(13).'</table>';
  return $l_html;
} ?>
