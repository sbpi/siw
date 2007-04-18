<? 
// =========================================================================
// Rotina de emissão da ordem de serviço
// -------------------------------------------------------------------------
function VisualOS($l_chave,$l_sg) {
  extract($GLOBALS);
  $l_html='';
  // Recupera os dados da tarefa
  $RS1 = db_getSolicData::getInstanceof($dbms,$l_chave,$l_sg);
  $l_html.=chr(13).'    <table border=0 width="100%">';
  $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
  $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>'.f($RS1,'nome').' - '.f($RS1,'sq_siw_solicitacao').'</b></font></div></td></tr>';
  $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
  // Identificação da solicitação
  switch (f($RS_Menu,'data_hora')) {
  case 1 :
    $l_html.=chr(13).'   <tr><td width="20%">Data programada:</td>';
    $l_html.=chr(13).'       <td><font size="2"><b>'.Nvl(FormataDataEdicao(f($RS1,'phpdt_fim')),'-').'</b></font></td></tr>';
    break;
  case 2 :
    $l_html.=chr(13).'   <tr><td width="20%">Data programada:</td>';
    $l_html.=chr(13).'       <td><font size="2"><b>'.Nvl(substr(FormataDataEdicao(f($RS1,'phpdt_fim'),3),0,-3),'-').'</b></font></td></tr>';
    break;
  case 3 :
    $l_html.=chr(13).'   <tr><td width="20%">Início:</td>';
    $l_html.=chr(13).'       <td><font size="2"><b><b>'.Nvl(FormataDataEdicao(f($RS1,'phpdt_inicio')),'-').'</b></font></td></tr>';
    $l_html.=chr(13).'   <tr><td width="20%">Término:</font></td>';
    $l_html.=chr(13).'       <td><font size="2"><b><b>'.Nvl(FormataDataEdicao(f($RS1,'phpdt_fim')),'-').'</b></font></td></tr>';
    break;
  case 4 :
    $l_html.=chr(13).'   <tr><td width="20%">Início:</td>';
    $l_html.=chr(13).'       <td><font size="2"><b><b>'.Nvl(substr(FormataDataEdicao(f($RS1,'phpdt_inicio'),0,-3),3),'-').'</b></font></td></tr>';
    $l_html.=chr(13).'   <tr><td width="20%"><b>Término:</b></td>';
    $l_html.=chr(13).'       <td><font size="2"><b><b>'.Nvl(substr(FormataDataEdicao(f($RS1,'phpdt_fim'),3),0,-3),'-').'</b></font></td></tr>';
    break;
  }
  $l_html.=chr(13).'   <tr><td>Unidade solicitante:</font></td>';
  $l_html.=chr(13).'       <td><font size="2"><b>'.f($RS1,'nm_unidade_solic').'</b></font></td></tr>';
  $l_html.=chr(13).'   <tr><td>Usuário solicitante:</font></td>';
  $l_html.=chr(13).'       <td><font size="2"><b>'.f($RS1,'nm_sol').'</b></font></td></tr>';
  $l_html.=chr(13).'   <tr><td>Destino:</font></td>';
  $l_html.=chr(13).'       <td><font size="2"><b>'.f($RS1,'destino').'</b></font></td></tr>';
  $l_html.=chr(13).'   <tr><td>Qtd. pessoas:</font><br>';
  $l_html.=chr(13).'       <td><font size="1"><b>'.f($RS1,'qtd_pessoas').'</b></font></td>';  
  $l_html.=chr(13).'   <tr><td>Carga:</font><br>';
  $l_html.=chr(13).'       <td><font size="1"><b>'.retornaSimNao(f($RS1,'carga')).'</b></font></td></tr>';
  if (Nvl(f($RS1,'descricao'),'')!='') {
    $l_html.=chr(13).'   <tr valign="top">';
    $l_html.=chr(13).'       <td width="20%">Detalhamento:</td>';
    $l_html.=chr(13).'       <td><b>'.crlf2br(Nvl(f($RS1,'descricao'),'---')).'</b></td></tr>';
  }

  // Campos para informar os dados do atendimento
  $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS DO ATENDIMENTO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
  $l_html.=chr(13).'      <table border=0 width="100%" cellpadding=10>';
  $l_html.=chr(13).'        <tr valign="top">';
  $l_html.=chr(13).'          <td colspan="2"><b>Motorista:</b><br><input type="text" size=80 name="data" readonly></td>';
  $l_html.=chr(13).'          <td><b>Placa:</b><br><input type="text" size=20 name="data" readonly></td>';
  $l_html.=chr(13).'        <tr valign="top">';
  $l_html.=chr(13).'          <td><b>Data/hora de saída:</b><br><input type="text" size=30 name="data" readonly></td>';
  $l_html.=chr(13).'          <td><b>Hodômetro na saída:</b><br><input type="text" size=30 name="data" readonly></td>';
  $l_html.=chr(13).'        <tr valign="top">';
  $l_html.=chr(13).'          <td><b>Data/hora de retorno:</b><br><input type="text" size=30 name="data" readonly></td>';
  $l_html.=chr(13).'          <td><b>Hodômetro na chegada:</b><br><input type="text" size=30 name="data" readonly></td>';
  $l_html.=chr(13).'        <tr><td><b>Trecho parcial?</b><br>';
  $l_html.=chr(13).'        <input type="checkbox" name="parcial" value="S"> <b>SIM';
  $l_html.=chr(13).'        <input type="checkbox" name="parcial" value="N"> <b>NÃO';
  $l_html.=chr(13).'        <tr><td colspan="3"><b>Passageiro (nome e assinatura):</b><br><input type="text" size=94 name="data" readonly></td>';
  $l_html.=chr(13).'        <tr><td colspan="3" align="center"><br><font size="2"><b>ATENÇÃO: descreva no verso desta OS quaisquer observações que julgar relevantes.</b></font></td></tr>';
  $l_html.=chr(13).'      </table>';
  $l_html.=chr(13).'    </table>';
  return $l_html;
} 
?>