<? 
// =========================================================================
// Rotina de visualização da solicitação
// -------------------------------------------------------------------------
function VisualGeral($l_chave,$O,$l_usuario,$l_sg,$P4) {
  extract($GLOBALS);
  $l_html='';
  // Recupera os dados da tarefa
  $RS1 = db_getSolicData::getInstanceof($dbms,$l_chave,$l_sg);
  $l_html.=chr(13).'    <table border=0 width="100%">';
  $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
  $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>SERVIÇO: '.f($RS1,'nome').' - SOLICITAÇÃO: '.f($RS1,'sq_siw_solicitacao').'</b></font></td></tr>';
  $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
  // Identificação da solicitação
  $l_html.=chr(13).'   <tr><td colspan="2"><br><font size="2"><b>DADOS DA SOLICITAÇÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
  switch (f($RS_Menu,'data_hora')) {
  case 1 :
    $l_html.=chr(13).'   <tr><td width="20%"><b>Data programada:</b></font></td>';
    $l_html.=chr(13).'       <td>'.Nvl(FormataDataEdicao(f($RS1,'phpdt_fim')),'-').'</font></td></tr>';
    break;
  case 2 :
    $l_html.=chr(13).'   <tr><td width="20%"><b>Data programada:</b></font></td>';
    $l_html.=chr(13).'       <td>'.Nvl(substr(FormataDataEdicao(f($RS1,'phpdt_fim'),3),0,-3),'-').'</font></td></tr>';
    break;
  case 3 :
    $l_html.=chr(13).'   <tr><td width="20%"><b>Início:</b></font></td>';
    $l_html.=chr(13).'       <td>'.Nvl(FormataDataEdicao(f($RS1,'phpdt_inicio')),'-').'</font></td></tr>';
    $l_html.=chr(13).'   <tr><td width="20%"><b>Término:</b></font></td>';
    $l_html.=chr(13).'       <td>'.Nvl(FormataDataEdicao(f($RS1,'phpdt_fim')),'-').'</font></td></tr>';
    break;
  case 4 :
    $l_html.=chr(13).'   <tr><td width="20%"><b>Início:</b></font></td>';
    $l_html.=chr(13).'       <td>'.Nvl(substr(FormataDataEdicao(f($RS1,'phpdt_inicio'),0,-3),3),'-').'</font></td></tr>';
    $l_html.=chr(13).'   <tr><td width="20%"><b>Término:</b></font></td>';
    $l_html.=chr(13).'       <td>'.Nvl(substr(FormataDataEdicao(f($RS1,'phpdt_fim'),3),0,-3),'-').'</font></td></tr>';
    break;
  }
  $l_html.=chr(13).'   <tr><td width="20%"><b>Detalhamento:</b></font></td>';
  $l_html.=chr(13).'       <td>'.Nvl(f($RS1,'descricao'),'-').'</font></td></tr>';
  if ($P4==1) {
    $l_html.=chr(13).'   <tr><td colspan="2"><br><font size="2"><b>SOLICITANTE<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $l_html.=chr(13).'   <tr><td><b>Unidade solicitante:</b></font></td>';
    $l_html.=chr(13).'       <td>'.f($RS1,'nm_unidade_solic').'</font></td></tr>';
    $l_html.=chr(13).'   <tr><td><b>Usuário solicitante:</b></font></td>';
    $l_html.=chr(13).'       <td>'.f($RS1,'nm_sol').'</font></td></tr>';
    $l_html.=chr(13).'   <tr><td colspan="2"><br><font size="2"><b>EXECUTOR<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $l_html.=chr(13).'   <tr><td><b>Unidade executora:</b></font></td>';
    $l_html.=chr(13).'       <td>'.f($RS1,'nm_unidade_exec').'</font></td></tr>';
  } else {
    $l_html.=chr(13).'   <tr><td colspan="2"><br><font size="2"><b>SOLICITANTE<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $l_html.=chr(13).'   <tr><td><b>Unidade solicitante:</b></font></td>';
    $l_html.=chr(13).'       <td>'.ExibeUnidade('../',$w_cliente,f($RS1,'nm_unidade_solic'),f($RS1,'sq_unidade'),$TP).'</font></td></tr>';
    $l_html.=chr(13).'   <tr><td><b>Usuário solicitante:</b></font></td>';
    $l_html.=chr(13).'       <td>'.ExibePessoa('../',$w_cliente,f($RS1,'solicitante'),$TP,f($RS1,'nm_sol')).'</font></td></tr>';
    $l_html.=chr(13).'   <tr><td colspan="2"><br><font size="2"><b>EXECUTOR<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $l_html.=chr(13).'   <tr><td><b>Unidade executora:</b></font></td>';
    $l_html.=chr(13).'       <td>'.ExibeUnidade('../',$w_cliente,f($RS1,'nm_unidade_exec'),f($RS1,'sq_unid_executora'),$TP).'</font></td></tr>';
  } 
  // Dados da conclusão da solicitação, se ela estiver nessa situação
  if (nvl(f($RS1,'conclusao'),'')!='') {
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS DA CONCLUSÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $l_html.=chr(13).'   <tr valign="top"><td><b>Data de conclusão:</b></font></td><td>'.FormataDataEdicao(substr(f($RS1,'phpdt_conclusao'),0,-3),3).'</font></td></tr>';
    $l_html.=chr(13).'   <tr valign="top"><td><b>Opinião emitida pelo solicitante:</b></font></td><td>'.nvl(f($RS1,'nm_opiniao'),'---').'</font></td></tr>';
    $l_html.=chr(13).'   <tr valign="top"><td><b>Valor do atendimento:</b></font></td><td>'.FormatNumber(f($RS1,'valor'),2).'</font></td></tr>';
    $l_html.=chr(13).'   <tr><td><b>Responsável pelo atendimento:</b></font></td>';
    $l_html.=chr(13).'       <td>'.ExibePessoa('../',$w_cliente,f($RS1,'executor'),$TP,f($RS1,'nm_exec')).'</font></td></tr>';
    $l_html.=chr(13).'   <tr valign="top"><td><b>Observações:</b></font></td><td>'.nvl(crlf2br(f($RS1,'observacao')),'---').'</font></td></tr>';
  } 
  // Encaminhamentos
  $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>OCORRÊNCIAS E ANOTAÇÕES<hr NOSHADE color=#000000 SIZE=1></b></td></tr>';
  $RS1 = db_getSolicLog::getInstanceOf($dbms,$l_chave,null,'LISTA');
  $RS1 = SortArray($RS1,'phpdt_data','desc','sq_siw_solic_log','desc');
  $l_html.=chr(13).'   <tr><td colspan="2"><div align="center">';
  $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
  $l_html.=chr(13).'       <tr valign="top">';
  $l_html.=chr(13).'         <td align="center"><b>Data</b></td>';
  $l_html.=chr(13).'         <td align="center"><b>Ocorrência/Anotação</b></td>';
  $l_html.=chr(13).'         <td align="center"><b>Responsável</b></td>';
  $l_html.=chr(13).'         <td align="center"><b>Fase</b></td>';
  $l_html.=chr(13).'       </tr>';
  $i=0;
  if (count($RS1)==0) {
    $l_html.=chr(13).'      <tr><td colspan=4 align="center"><b>Não foram encontrados encaminhamentos.</b></td></tr>';
  } else {
    $i = 0;
    foreach ($RS1 as $row1) {
      $l_html.=chr(13).'      <tr valign="top">';
      if ($i==0) {
        $l_html.=chr(13).'     <td colspan=4>Fase atual: <b>'.f($row1,'fase').'</b></td></tr>';
        $l_html.=chr(13).'      <tr valign="top">';
        $i=1;
      }
      $l_html.=chr(13).'        <td nowrap align="center">'.FormataDataEdicao(f($row1,'phpdt_data'),3).'</td>';
      if (Nvl(f($row1,'caminho'),'')>'') {
        $l_html.=chr(13).'        <td>'.CRLF2BR(Nvl(f($row1,'observacao'),'---').'<br>'.LinkArquivo('HL',$w_cliente,f($row1,'sq_siw_arquivo'),'_blank','Clique para exibir o anexo em outra janela.','Anexo - '.f($row1,'tipo').' - '.round(f($row1,'tamanho')/1024,1).' KB',null)).'</td>';
      } else {
        $l_html.=chr(13).'        <td>'.CRLF2BR(Nvl(f($row1,'observacao'),'---')).'</td>';
      }         
      $l_html.=chr(13).'        <td nowrap>'.ExibePessoa('../',$w_cliente,f($row1,'sq_pessoa'),$TP,f($row1,'responsavel')).'</td>';
      if ((Nvl(f($row1,'sq_demanda_log'),'')>'')  && (Nvl(f($row1,'destinatario'),'')==''))   $l_html.=chr(13).'        <td nowrap>Anotação</td>';
      else $l_html.=chr(13).'        <td nowrap>'.Nvl(f($row1,'tramite'),'---').'</td>';
      $l_html.=chr(13).'      </tr>';
    } 
    $l_html.=chr(13).'         </table></td></tr>';
  } 
  $l_html.=chr(13).'         </table>';
  return $l_html;
} 
?>