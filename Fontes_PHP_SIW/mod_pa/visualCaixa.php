<?php
// =========================================================================
// Rotina de visualiza��o dos dados do documento
// -------------------------------------------------------------------------
function VisualCaixa($l_chave, $l_formato='WORD') {
  extract($GLOBALS);

  $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);

  // Recupera os dados da guia
  $RS_Dados = db_getCaixa::getInstanceOf($dbms,$l_chave,$w_cliente,null,null,null,null,null,null,null,null,'PASTA');
  if (Nvl($p_ordena,'')>'') {
    $lista = explode(',',str_replace(' ',',',$p_ordena));
    $RS_Dados = SortArray($RS_Dados,$lista[0],$lista[1],'sg_unidade','asc', 'numero','asc','pasta','asc','cd_assunto','asc','protocolo','asc');
  } else {
    $RS_Dados = SortArray($RS_Dados,'sg_unidade','asc', 'numero','asc','pasta','asc','cd_assunto','asc','protocolo','asc');
  }

  if ($l_formato=='WORD') $l_html = BodyOpenWord(null); else $l_html = '';
  $w_linha = 99;
  $w_pag   = 1;
  $w_pasta = '';

  foreach ($RS_Dados as $row) {
    if (($w_linha > 30 && $l_formato=='WORD') || ($w_pag==1 && $l_formato!='WORD')) {
      if ($w_pag>1 && $l_formato=='WORD') {
        $l_html.=chr(13).'    </table>';
        $l_html.=chr(13).'    <tr><td colspan=2><p>&nbsp;</p>';
        $l_html.=chr(13).'  </table>';
        $l_html.=chr(13).'</table>';
        $l_html.=chr(13).'<br style="page-break-after:always">';
      }
      $l_html.=chr(13).'<table width="95%" border="0" cellspacing="3">';
      if ($l_formato=='WORD' || $w_pag==1) {
        $l_html.=chr(13).'<tr><td colspan="2">';
        $l_html.=chr(13).'  <table width="100%" border=0>';
        $l_html.=chr(13).'    <tr valign="top">';
        $l_html.=chr(13).'      <td><font size=1><b>'.strtoupper(f($RS,'nome').' - '.f($RS,'nome_resumido')).'</b></font></td>';
        $l_html.=chr(13).'      <td align="right"><font size=1><b>P�g. '.$w_pag.'</b></td></font></tr>';
        $l_html.=chr(13).'    <tr valign="top">';
        $l_html.=chr(13).'      <td><font size=1><b>'.strtoupper($conSgSistema.' - M�dulo de '.f($RS_Menu,'nm_modulo')).'</b></font></td>';
        $l_html.=chr(13).'      <td align="right"><font size=1><b>'.datahora().'</b></td></font></tr>';
        $l_html.=chr(13).'  </table>';
      }
      $l_html.=chr(13).'<tr><td colspan="2" align="center">';
      $l_html.=chr(13).'    <table width="100%" border="0" cellspacing="3">';
      $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
      $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0" align=center><font size="2"><b>CAIXA N� '.f($row,'numero').'/'.f($row,'sg_unidade').'</b></font></td></tr>';
      $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
      $l_html.=chr(13).'   <tr><td width="30%"><b>Assunto:</b></td>';
      $l_html.=chr(13).'       <td>'.f($row,'assunto').'</td></tr>';
      $l_html.=chr(13).'   <tr><td width="30%"><b>Data Limite:</b></td>';
      $l_html.=chr(13).'       <td>'.formataDataEdicao(f($row,'data_limite')).'</td></tr>';
      $l_html.=chr(13).'   <tr><td width="30%"><b>Intermedi�rio:</b></td>';
      $l_html.=chr(13).'       <td>'.f($row,'intermediario').'</td></tr>';
      $l_html.=chr(13).'   <tr><td width="30%"><b>Destina��o Final:</b></td>';
      $l_html.=chr(13).'       <td>'.f($row,'destinacao_final').'</td></tr>';
      
      $l_html.=chr(13).'   <tr><td colspan=2><br><b>DOCUMENTOS/PROCESSOS ARQUIVADOS NESTA CAIXA</b></td></tr>';
      $l_html.=chr(13).'   <tr><td colspan=2><table border=1 width="100%">';
      $l_html.=chr(13).'     <tr align="center">';
      $l_html.=chr(13).'       <td rowspan=2><font size=1><b>Tipo</b></font></td>';
      $l_html.=chr(13).'       <td rowspan=2><font size=1><b>Protocolo</b></font></td>';
      $l_html.=chr(13).'       <td rowspan=2><font size=1><b>Assunto</b></font></td>';
      $l_html.=chr(13).'       <td colspan=4><font size=1><b>Documento original</b></font></td>';
      $l_html.=chr(13).'       <td rowspan=2><font size=1><b>Data Limite</b></font></td>';
      $l_html.=chr(13).'     <tr valign="top" align="center">';
      $l_html.=chr(13).'       <td><font size=1><b>Esp�cie</b></font></td>';
      $l_html.=chr(13).'       <td><font size=1><b>N�</b></font></td>';
      $l_html.=chr(13).'       <td><font size=1><b>Data</b></font></td>';
      $l_html.=chr(13).'       <td><font size=1><b>Proced�ncia</b></font></td>';
      $l_html.=chr(13).'     </tr>';
      $w_pag   += 1;
      $w_linha = 6;
    } 
    if (nvl($w_pasta,'.')!=f($row,'pasta')) {
      $l_html.=chr(13).'      <tr><td colspan=8 bgColor="#f0f0f0"style="border: 1px solid rgb(0,0,0);" ><b>PASTA '.f($row,'pasta').'</b></td></tr>';
      $w_pasta = f($row,'pasta');
    }
    $l_html.=chr(13).'     <tr valign="top">';
    $l_html.=chr(13).'       <td><font size=1>'.f($row,'nm_tipo').'</font></td>';
    $l_html.=chr(13).'       <td align="center"><font size=1>'.f($row,'protocolo').'</font></td>';
    $l_html.=chr(13).'       <td align="center"><font size=1>'.f($row,'cd_assunto').'</font></td>';
    $l_html.=chr(13).'       <td><font size=1>'.f($row,'nm_especie').'</font></td>';
    $l_html.=chr(13).'       <td><font size=1>'.f($row,'numero_original').'</font></td>';
    $l_html.=chr(13).'       <td align="center"><font size=1>'.date(d.'/'.m.'/'.y,f($row,'inicio')).'</font></td>';
    $l_html.=chr(13).'       <td><font size=1>'.f($row,'nm_origem_resumido').'</font></td>';
    $l_html.=chr(13).'       <td><font size=1>'.formataDataEdicao(f($row,'data_limite_doc')).'</font></td>';
    $l_html.=chr(13).'     </tr>';
    $w_linha += 1;
  }
  $l_html.=chr(13).'     <tr valign="top">';
  $l_html.=chr(13).'       <td colspan=7 align="right"><font size=1><b>Total da caixa</font></b></td>';
  $l_html.=chr(13).'       <td align="center"><b><font size=1>'.count($RS_Dados).'</font></b></td>';
  $l_html.=chr(13).'     </tr>';
  $l_html.=chr(13).'    </table>';
  if ($l_formato=='WORD') {
    $l_html.=chr(13).'    <tr><td colspan=2><p>&nbsp;</p>';
  }
  $l_html.=chr(13).'  </table>';
  $l_html.=chr(13).'</table>';
  return $l_html;
}
?>