<?php
// =========================================================================
// Rotina de emissão da ordem de serviço
// -------------------------------------------------------------------------
function VisualMapaVeiculo($l_chave,$l_sg,$l_placa,$l_alugado,$l_ativo,$l_solic,$l_inicio,$l_fim,$l_restricao) {
  extract($GLOBALS);
  $l_html='';
  // Recupera os dados da tarefa
  include_once($w_dir_volta.'classes/sp/db_getVeiculo.php');
  $sql = new db_getVeiculo; $RS_Mapa = $sql->getInstanceof($dbms,null,null,$w_cliente,$l_placa,$l_alugado,$l_ativo,$l_solic,$l_inicio,$l_fim,$l_restricao);
  $RS_Mapa = SortArray($RS_Mapa,'st_veiculo','desc','marca','asc','modelo','asc','nm_veiculo','asc','phpdt_fim','asc','phpdt_inicio','asc'); 
  $sql = new db_getSolicData; $RS1 = $sql->getInstanceof($dbms,$l_chave,$l_sg);
  $l_html.=chr(13).'    <table border=0 width="100%" cellpadding=0 cellspacing=0>';
  $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
  $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0" align="center"><font size="2"><b>MAPA DE ALOCAÇÃO DE VEÍCULOS</b></font></td></tr>';
  $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=1></td></tr>';
  if (formataDataEdicao(nvl($l_inicio,time()))==formataDataEdicao(nvl($l_fim,time()))) {
    $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>Data: '.formataDataEdicao(nvl($l_inicio,time())).'</b></font></div></td></tr>';
  } else {
    $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>Período: '.formataDataEdicao(nvl($l_inicio,time())).' a '.formataDataEdicao(nvl($w_fim,time())).'</b></font></div></td></tr>';
  }
  $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=2></td></tr>';
  $l_html.=chr(13).'      <tr><td colspan=2><b>Veículos sem alocação exibidos primeiro. Ordenação por marca, modelo e placa.</b></td>';
  $l_html.=chr(13).'    </table>';
  $l_html.=chr(13).'    <table border=1 width="100%" bordercolor="#00000">';
  $l_html.=chr(13).'      <tr align="center">';
  $l_html.=chr(13).'        <td rowspan=2><b>Veículo</b></td>';
  $l_html.=chr(13).'        <td colspan=5><b>Alocações</b></td>';
  $l_html.=chr(13).'      <tr valign="top" align="center">';
  $l_html.=chr(13).'        <td><b>Saída</b></td>';
  $l_html.=chr(13).'        <td><b>Retorno</b></td>';
  $l_html.=chr(13).'        <td><b>Procedimento</b></td>';
  $l_html.=chr(13).'        <td><b>Solicitante</b></td>';
  $l_html.=chr(13).'        <td><b>Destino</b></td>';
  $w_atual = '';
  foreach($RS_Mapa as $row) {
    $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
    $l_html.=chr(13).'      <tr bgcolor="'.$w_cor.'" valign="top">';
    if ($w_atual!=f($row,'nm_veiculo')) {
      $w_cor1 = $w_cor;
      $l_html.=chr(13).'        <td>'.f($row,'nm_veiculo').'</td>';
      $w_atual = f($row,'nm_veiculo');
    } else {
      $l_html.=chr(13).'        <td bgcolor="'.$w_cor1.'"></td>';
    }
    if (formataDataEdicao(nvl($l_inicio,time()))==formataDataEdicao(nvl($l_fim,time()))) {
      $w_ini = formataDataEdicao(nvl(nvl(f($row,'phpdt_inicio'),f($row,'phpdt_fim')),'&nbsp;'),3);
      if ($w_ini!='&nbsp;') $w_ini = substr($w_ini,12,5);
      $w_fim = substr(formataDataEdicao(f($row,'phpdt_fim'),3),12,5);
    } else {
      $w_ini = formataDataEdicao(nvl(nvl(f($row,'phpdt_inicio'),f($row,'phpdt_fim')),'&nbsp;'),3);
      if ($w_ini!='&nbsp;') substr($w_ini,0,-3);
      $w_fim = substr(formataDataEdicao(f($row,'phpdt_fim'),3),0,-3);
      if (formataDataEdicao(f($row,'phpdt_inicio'))==formataDataEdicao(f($row,'phpdt_fim'))) $w_fim = substr($w_fim,12,5);
    }
    $l_html.=chr(13).'        <td align="center">'.$w_ini.'</td>';
    if (f($row,'procedimento')==2) {
      $l_html.=chr(13).'        <td align="center">'.$w_fim.'</td>';
    } else {
      $l_html.=chr(13).'        <td>&nbsp;</td>';
    }
    $l_html.=chr(13).'        <td>'.nvl(f($row,'nm_procedimento'),'&nbsp').'</td>';
    if (nvl(f($row,'sq_solic'),'')!='') {
      $l_html.=chr(13).'        <td>'.ExibePessoa(null,$w_cliente,f($row,'sq_solic'),$TP,f($row,'nm_solic')).'</b></td>';
    } else {
      $l_html.=chr(13).'        <td>&nbsp;</td>';
    }
    $l_html.=chr(13).'        <td>'.nvl(f($row,'destino'),'&nbsp').'</td>';
  }
  $l_html.=chr(13).'    </table>';
  return $l_html;
} 
?>