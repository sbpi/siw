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

  // Verifica se é necessário mostrar o recurso
  $RS_Recursos = db_getRecurso::getInstanceOf($dbms,$w_cliente,$w_usuario,$w_menu,null,null,null,null,null,'SERVICO');
  if (count($RS_Recursos)) $w_exibe_recurso = true; else $w_exibe_recurso = false;
  if ($w_exibe_recurso) {
    $RS_Recurso = db_getSolicRecursos::getInstanceOf($dbms,$w_cliente,$w_usuario,$l_chave,null,null,null,null,null,null,null,null,null,null,null);
    foreach ($RS_Recurso as $row) {$RS_Recurso = $row; break;}
    $l_html.=chr(13).'   <tr valign="top">';
    $l_html.=chr(13).'       <td width="20%">Recurso:</font></td>';
    $l_html.=chr(13).'       <td><font size="2"><b>'.nvl(f($RS_Recurso,'nm_recurso'),'Não informado').'</b><br>'.f($RS_Recurso,'ds_recurso').'</font></td></tr>';
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
  $l_html.=chr(13).'        <tr><td colspan="2"><b>Responsável pelo atendimento (nome e assinatura):</b><br><input type="text" size=80 name="responsável" readonly></td></tr>';
  $l_html.=chr(13).'        <tr><td colspan="2"><b>Recebedor (nome e assinatura):</b><br><input type="text" size=80 name="responsável" readonly></td></tr>';
  $l_html.=chr(13).'        <tr><td colspan="2" align="center"><br><font size="2"><b>ATENÇÃO: descreva abaixo observações e materiais eventualmente consumidos no atendimento.</b></font></td></tr>';
  $l_html.=chr(13).'        <tr><td colspan="2" align="center"><table border=1 cellpadding=0 cellspacing=0 width="100%"><tr><td><br>&nbsp;<tr><td><br>&nbsp;<tr><td><br>&nbsp;<tr><td><br>&nbsp;<tr><td><br>&nbsp;<tr><td><br>&nbsp;<tr><td><br>&nbsp;<tr><td><br>&nbsp;</table></td></tr>';
  $l_html.=chr(13).'      </table>';
  $l_html.=chr(13).'    </table>';
  return $l_html;
} 
?>