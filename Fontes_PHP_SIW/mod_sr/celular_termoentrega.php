<?php
// =========================================================================
// Rotina de emissão da ordem de serviço
// -------------------------------------------------------------------------
function celular_termoEntrega($l_chave,$l_sg,$l_formato=0) {
  extract($GLOBALS);
  $l_html='';
  // Recupera os dados da tarefa
  $sql = new db_getSolicData; $RS1 = $sql->getInstanceof($dbms,$l_chave,$l_sg);
  $sql = new db_getBenef;     $RS2 = $sql->getInstanceOf($dbms, $w_cliente, $w_cliente, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null, null);
  foreach($RS2 as $row) { $RS2 = $row; break; }
  
  $l_html.=chr(13).'    <table border=0 width="100%">';
  if ($l_formato!=1) {
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>'.f($RS1,'nome').' - '.f($RS1,'sq_siw_solicitacao').'</b></font></div></td></tr>';
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    $l_html.=chr(13).'   <tr><td>Solicitante:</font></td>';
    $l_html.=chr(13).'       <td><font size="2"><b>'.f($RS1,'nm_cad').'</b></font></td></tr>';
    $l_html.=chr(13).'   <tr><td>Beneficiário:</font></td>';
    $l_html.=chr(13).'       <td><font size="2"><b>'.f($RS1,'nm_sol').'</b></font></td></tr>';
    $l_html.=chr(13).'   <tr><td>Unidade solicitante:</font></td>';
    $l_html.=chr(13).'       <td><font size="2"><b>'.f($RS1,'nm_unidade_solic').'</b></font></td></tr>';
    $l_html.=chr(13).'   <tr><td width="20%">Período solicitado:</td>';
    $l_html.=chr(13).'       <td><font size="2"><b><b>'.FormataDataEdicao(nvl(f($RS1,'inicio_real'),f($RS1,'inicio')),5).' a '.FormataDataEdicao(f($RS1,'fim'),5).'</b></font></td></tr>';
    $l_html.=chr(13).'   <tr><td width="20%">Destino:</td>';
    $l_html.=chr(13).'       <td><font size="2"><b><b>'.f($RS1,'nm_pais_cel').'</b></font></td></tr>';
    $l_html.=chr(13).'   <tr><td width="20%">Justificativa:</td>';
    $l_html.=chr(13).'       <td><font size="2"><b><b>'.f($RS1,'justificativa').'</b></font></td></tr>';
    $l_html.=chr(13).'      <tr><td colspan="2"><font size="2"><hr NOSHADE color=#000000 SIZE=1></font></td></tr>';
  }

  // Campos para informar os dados do atendimento
  $l_html.=chr(13).'      <table border=0 width="100%" cellpadding=10 bgcolor="'.$conTrBgColor.'">';
  $l_html.=chr(13).'        <tr valign="top"><td colspan="2">';
  $l_html.=chr(13).'<p align="justify">Eu, <b>'.f($RS1,'nm_solicitante').'</b>, declaro que recebi da <b>'.f($RS2,'nome_resumido').' - '.f($RS2,'nm_pessoa').'</b>, o aparelho celular da marca <b>'.f($RS1,'marca').'</b>, ';
  $l_html.=chr(13).'modelo <b>'.f($RS1,'modelo').'</b>, número <b>'.f($RS1,'numero_linha').'</b>, sim card no. <b>'.f($RS1,'sim_card').'</b>, imei do aparelho no. <b>'.f($RS1,'imei').'</b> ';
  $l_html.=chr(13).'em perfeitas condições de uso'.((nvl(f($RS1,'acessorios_entregues'),'')=='') ? '.' : ', contendo os seguintes itens:').'</p>';
  $l_html.=chr(13).'<p align="justify"><b>'.f($RS1,'acessorios_entregues').'</b></p>';
  $l_html.=chr(13).'<p align="justify">Referido aparelho destina-se a uso exclusivo de atividades relativas à Agência, de acordo com o procedimento operacional ';
  $l_html.=chr(13).'vigente, do qual declaro ter ciência e anuência a todos os seus termos.</p>';
  $l_html.=chr(13).'<p align="justify"><b><font color="#FF0000">Atenção: este termo de responsabilidade invalida qualquer outro assinado nesta mesma solicitação.</font></b></p>';
  if ($l_formato!=1) {
    $l_html.=chr(13).'        <tr valign="top" align="center">';
    $l_html.=chr(13).'          <td width="50%"><input type="text" size=35 name="responsável" readonly><br><b>Assinatura do Beneficiário</b></td>';
    $l_html.=chr(13).'          <td width="50%"><input type="text" size=35 name="responsável" readonly><br><b>Local e Data</b></td>';
  }
  $l_html.=chr(13).'      </table>';
  return $l_html;
} 
?>