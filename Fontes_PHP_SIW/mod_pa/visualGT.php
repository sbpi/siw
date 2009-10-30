<?php
// =========================================================================
// Rotina de visualização dos dados do documento
// -------------------------------------------------------------------------
function VisualGT($l_unidade, $l_nu_guia, $l_ano_guia, $l_menu=null, $l_formato='WORD') {
  extract($GLOBALS);

  $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);

  // Recupera os dados da guia
  $RS_Dados = db_getCaixa::getInstanceOf($dbms,$p_chave,$w_cliente,null,null,null,$l_unidade,$l_nu_guia,$l_ano_guia,null,null,'PASTA');
  $RS_Dados = SortArray($RS_Dados,'sg_unidade','asc', 'numero','asc','pasta','asc','cd_assunto','asc','protocolo','asc');
  
  if ($l_formato=='WORD') $l_html = BodyOpenWord(null); else $l_html = '';
  $w_linha = 99;
  $w_pag   = 1;

  foreach ($RS_Dados as $row) {
    if (($w_linha > 30 && $l_formato=='WORD') || ($w_pag==1 && $l_formato!='WORD')) {
      if ($w_pag>1 && $l_formato=='WORD') {
        $l_html.=chr(13).'    </table>';
        $l_html.=chr(13).'    <tr><td colspan=2><p>&nbsp;</p>';
        $l_html.=chr(13).'    <tr><td colspan=2><table border=0 width="100%"><tr valign="top">';
        $l_html.=chr(13).'      <td align="center" width=30%>________/________/20______<br><b>Data e hora</b>';
        $l_html.=chr(13).'      <td align="center" width=70%>__________________________________________________<br><b>Nome e assinatura</b>';
        $l_html.=chr(13).'    </table>';
        $l_html.=chr(13).'  </table>';
        $l_html.=chr(13).'</table>';
        $l_html.=chr(13).'<br style="page-break-after:always">';
      }
      $l_html.=chr(13).'<table width="95%" border="0" cellspacing="3">';
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
      $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0" align=center><font size="2"><b>GUIA DE TRANSFERÊNCIA Nº '.f($row,'guia_transferencia').'</b></font></td></tr>';
      $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
      $l_html.=chr(13).'   <tr><td width="30%"><b>Unidade de origem:</b></td>';
      $l_html.=chr(13).'       <td>'.f($row,'nm_unidade').'</td></tr>';
      if (f($row,'interno')=='S') {
        $l_html.=chr(13).'   <tr><td width="30%"><b>Unidade de destino:</b></td>';
        $l_html.=chr(13).'       <td>'.f($row,'nm_unid_dest').'</td></tr>';
      } else {
        $l_html.=chr(13).'   <tr><td width="30%"><b>Pessoa de destino:</b></td>';
        $l_html.=chr(13).'       <td>'.f($row,'nm_pessoa_dest').'</td></tr>';
        $l_html.=chr(13).'   <tr><td width="30%"><b>Unidade externa de destino:</b></td>';
        $l_html.=chr(13).'       <td>'.f($row,'unidade_externa').'</td></tr>';
      }

      $l_html.=chr(13).'   <tr><td colspan=2><br><b>PROTOCOLOS ENCAMINHADOS PARA O ARQUIVO</b></td></tr>';
      $l_html.=chr(13).'   <tr><td colspan=2><table border=1 width="100%">';
      $l_html.=chr(13).'     <tr align="center">';
      $l_html.=chr(13).'       <td rowspan=2><font size=1><b>Caixa</b></font></td>';
      $l_html.=chr(13).'       <td rowspan=2><font size=1><b>Pasta</b></font></td>';
      $l_html.=chr(13).'       <td rowspan=2><font size=1><b>Tipo</b></font></td>';
      $l_html.=chr(13).'       <td rowspan=2><font size=1><b>Protocolo</b></font></td>';
      $l_html.=chr(13).'       <td colspan=4><font size=1><b>Documento original</b></font></td>';
      $l_html.=chr(13).'     <tr valign="top" align="center">';
      $l_html.=chr(13).'       <td><font size=1><b>Espécie</b></font></td>';
      $l_html.=chr(13).'       <td><font size=1><b>Nº</b></font></td>';
      $l_html.=chr(13).'       <td><font size=1><b>Data</b></font></td>';
      $l_html.=chr(13).'       <td><font size=1><b>Procedência</b></font></td>';
      $l_html.=chr(13).'     </tr>';
      $w_pag   += 1;
      $w_linha = 6;
    } 
    $l_html.=chr(13).'     <tr valign="top">';
    $l_html.=chr(13).'       <td align="center"><font size=1>'.f($row,'numero').'</font></td>';
    $l_html.=chr(13).'       <td align="center"><font size=1>'.f($row,'pasta').'</font></td>';
    $l_html.=chr(13).'       <td><font size=1>'.f($row,'nm_tipo').'</font></td>';
    $l_html.=chr(13).'       <td align="center"><font size=1>'.f($row,'protocolo').'</font></td>';
    $l_html.=chr(13).'       <td><font size=1>'.f($row,'nm_especie').'</font></td>';
    $l_html.=chr(13).'       <td><font size=1>'.f($row,'numero_original').'</font></td>';
    $l_html.=chr(13).'       <td align="center"><font size=1>'.date(d.'/'.m.'/'.y,f($row,'inicio')).'</font></td>';
    $l_html.=chr(13).'       <td><font size=1>'.f($row,'nm_origem_resumido').'</font></td>';
    $l_html.=chr(13).'     </tr>';
    $w_linha += 1;
  }
  $l_html.=chr(13).'    </table>';
  if ($l_formato=='WORD') {
    $l_html.=chr(13).'    <tr><td colspan=2><p>&nbsp;</p>';
    $l_html.=chr(13).'    <tr><td colspan=2><table border=0 width="100%"><tr valign="top">';
    $l_html.=chr(13).'      <td align="center" width=30%>________/________/20______<br><b>Data e hora</b>';
    $l_html.=chr(13).'      <td align="center" width=70%>__________________________________________________<br><b>Nome e assinatura</b>';
    $l_html.=chr(13).'    </table>';
  }
  $l_html.=chr(13).'  </table>';
  $l_html.=chr(13).'</table>';
  return $l_html;
} ?>
