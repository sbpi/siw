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
  $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>SERVIÇO: '.f($RS1,'nome').' - SOLICITAÇÃO: '.f($RS1,'sq_siw_solicitacao').'</b></font></div></td></tr>';
  $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
  // Identificação da solicitação
  $l_html.=chr(13).'   <tr><td colspan="2"><br><font size="2"><b>DADOS DA SOLICITAÇÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
  $l_html.=chr(13).'   <tr><td>Usuário solicitante:</font></td>';
  $l_html.=chr(13).'       <td><font size="2"><b>'.f($RS1,'nm_sol').'</b></font></td></tr>';
  $l_html.=chr(13).'   <tr><td>Unidade:</font></td>';
  $l_html.=chr(13).'       <td><font size="2"><b>'.f($RS1,'nm_unidade_solic').'</b></font></td></tr>';
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
  if (Nvl(f($RS1,'descricao'),'')!='') {
    $l_html.=chr(13).'   <tr valign="top">';
    $l_html.=chr(13).'       <td width="20%">Detalhamento:</td>';
    $l_html.=chr(13).'       <td><b>'.crlf2br(Nvl(f($RS1,'descricao'),'-')).'</b></td></tr>';
  }
  if (Nvl(f($RS1,'justificativa'),'')!='') {
    $l_html.=chr(13).'   <tr valign="top">';
    $l_html.=chr(13).'       <td width="20%">Justificativa:</td>';
    $l_html.=chr(13).'       <td><b>'.crlf2br(Nvl(f($RS1,'justificativa'),'-')).'</b></td></tr>';
  }

  // Campos para informar os dados do atendimento
  $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS DO ATENDIMENTO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
  $l_html.=chr(13).'      <table border=0 width="100%" cellpadding=10>';
  $l_html.=chr(13).'        <tr valign="top">';
  $l_html.=chr(13).'          <td><b>Data e hora de conclusão:</b><br><input type="text" size=30 name="data" readonly></td>';
  $l_html.=chr(13).'          <td><b>Valor (se houver):</b><br><input type="text" size=30 name="valor" readonly></td>';
  $l_html.=chr(13).'        <tr><td colspan="2"><b>Nome do responsável pelo atendimento:</b><br><input type="text" size=50 name="responsável" readonly></td></tr>';
  $l_html.=chr(13).'        <tr valign="top">';
  $l_html.=chr(13).'          <td><b>Assinatura do responsável pelo atendimento:</b><br><input type="text" size=35 name="assinatura_solic" readonly></td>';
  $l_html.=chr(13).'          <td><b>Assinatura do solicitante:</b><br><input type="text" size=35 name="assinatura_resp" readonly></td>';
  $l_html.=chr(13).'        <tr><td colspan="2" align="center"><br><font size="2"><b>ATENÇÃO: descreva no verso desta OS os materiais eventualmente consumidos no atendimento.</b></font></td></tr>';
  $l_html.=chr(13).'      </table>';
  $l_html.=chr(13).'    </table>';
  return $l_html;
} 
?>