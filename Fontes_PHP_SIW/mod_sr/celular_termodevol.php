<?php
// =========================================================================
// Rotina de emiss�o da ordem de servi�o
// -------------------------------------------------------------------------
function celular_termoDevol($l_chave,$l_sg) {
  extract($GLOBALS);
  $l_html='';
  // Recupera os dados da tarefa
  $sql = new db_getSolicData; $RS1 = $sql->getInstanceof($dbms,$l_chave,$l_sg);
  $sql = new db_getBenef;     $RS2 = $sql->getInstanceOf($dbms, $w_cliente, 10135, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
  foreach($RS2 as $row) { $RS2 = $row; break; }
  
  $l_html.=chr(13).'    <table border=0 width="100%">';
  $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
  $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>'.f($RS1,'nome').' - '.f($RS1,'sq_siw_solicitacao').'</b></font></div></td></tr>';
  $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
  $l_html.=chr(13).'   <tr><td>Solicitante:</font></td>';
  $l_html.=chr(13).'       <td><font size="2"><b>'.f($RS1,'nm_cad').'</b></font></td></tr>';
  $l_html.=chr(13).'   <tr><td>Benefici�rio:</font></td>';
  $l_html.=chr(13).'       <td><font size="2"><b>'.f($RS1,'nm_sol').'</b></font></td></tr>';
  $l_html.=chr(13).'   <tr><td>Unidade solicitante:</font></td>';
  $l_html.=chr(13).'       <td><font size="2"><b>'.f($RS1,'nm_unidade_solic').'</b></font></td></tr>';
  $l_html.=chr(13).'   <tr><td width="20%">Per�odo inicialmente solicitado:</td>';
  $l_html.=chr(13).'       <td><font size="2"><b><b>'.FormataDataEdicao(f($RS1,'inicio'),5).' a '.FormataDataEdicao(f($RS1,'fim'),5).'</b></font></td></tr>';
  $l_html.=chr(13).'   <tr><td width="20%">Per�odo do empr�stimo:</td>';
  $l_html.=chr(13).'       <td><font size="2"><b><b>'.FormataDataEdicao(f($RS1,'inicio_real'),5).' a '.FormataDataEdicao(f($RS1,'fim_real'),5).'</b></font></td></tr>';
  $l_html.=chr(13).'   <tr><td width="20%">Destino:</td>';
  $l_html.=chr(13).'       <td><font size="2"><b><b>'.f($RS1,'nm_pais_cel').'</b></font></td></tr>';
  $l_html.=chr(13).'   <tr><td width="20%">Justificativa:</td>';
  $l_html.=chr(13).'       <td><font size="2"><b><b>'.f($RS1,'justificativa').'</b></font></td></tr>';

  // Campos para informar os dados do atendimento
  $l_html.=chr(13).'      <tr><td colspan="2"><font size="2"><hr NOSHADE color=#000000 SIZE=1></font></td></tr>';
  $l_html.=chr(13).'      <table border=0 width="100%" cellpadding=10 bgcolor="'.$conTrBgColor.'">';
  $l_html.=chr(13).'        <tr valign="top"><td colspan="2">';
  $l_html.=chr(13).'<p align="justify">Eu, <b>'.f($RS1,'nm_sol').'</b>, declaro que devolvi para <b>'.f($RS2,'nome_resumido').' - '.f($RS2,'nm_pessoa').'</b>, o aparelho celular da marca <b>'.f($RS1,'marca').'</b>, ';
  $l_html.=chr(13).'modelo <b>'.f($RS1,'modelo').'</b>, n�mero <b>'.f($RS1,'numero_linha').'</b>, sim card no. <b>'.f($RS1,'sim_card').'</b>, imei do aparelho no. <b>'.f($RS1,'imei').'</b> ';
  $l_html.=chr(13).'em perfeitas condi��es de uso, contendo os seguintes itens:</p>';
  $l_html.=chr(13).'<p align="justify"><b>'.f($RS1,'acessorios').'</b></p>';
  if (f($RS1,'pendencia')=='S') {
    $l_html.=chr(13).'<p align="justify"><b><font color="#FF0000">Aten��o: Devolu��o com os seguintes itens pendentes:<br><b>'.f($RS1,'acessorios_pendentes').'</b></font></b></p>';
  }
  $l_html.=chr(13).'        <tr valign="top" align="center">';
  $l_html.=chr(13).'          <td width="50%"><input type="text" size=35 name="respons�vel" readonly><br><b>Assinatura do Benefici�rio</b></td>';
  $l_html.=chr(13).'          <td width="50%"><input type="text" size=35 name="respons�vel" readonly><br><b>Local e Data</b></td>';
  $l_html.=chr(13).'      </table>';
  $l_html.=chr(13).'    </table>';
  return $l_html;
} 
?>