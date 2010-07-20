<?php
// =========================================================================
// Rotina de visualização dos dados da tarefa
// -------------------------------------------------------------------------
function VisualTarefa($l_chave,$O,$l_usuario,$P4,$l_identificacao,$l_conclusao,$l_responsavel,$l_anexo,$l_ocorrencia,$l_dados_consulta) {
  extract($GLOBALS);
  $l_html='';
  // Recupera os dados da tarefa
  $RS1 = db_getSolicData_IS::getInstanceof($dbms,$l_chave,'ISTAGERAL');
  foreach($RS1 as $row){$RS1=$row; break;}
  $l_html.=chr(13).'      <tr><td colspan=\'2\'><hr NOSHADE color=#000000 size=4></td></tr>';
  $l_html.=chr(13).'      <tr><td colspan=\'2\'  bgcolor=\'#f0f0f0\'><div align=justify><font size=\'2\'><b>TAREFA: '.f($RS1,'sq_siw_solicitacao').' - '.f($RS1,'titulo').'</b></font></div></td></tr>';
  $l_html.=chr(13).'      <tr><td colspan=\'2\'><hr NOSHADE color=#000000 size=4></td></tr>';
  // Identificação da tarefa
  if (upper($l_identificacao)==upper('sim')) {
    $l_html.=chr(13).'      <tr><td colspan=\'2\'><br><font size=\'2\'><b>IDENTIFICAÇÃO DA TAREFA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    if (Nvl(f($RS1,'nm_projeto'),'')>'') {
      // Recupera os dados da ação
      $RS2 = db_getSolicData_IS::getInstanceOf($dbms,f($RS1,'sq_solic_pai'),'ISACGERAL');
      foreach($RS2 as $row){$RS2=$row; break;}
      // Se a ação no PPA for informada, exibe.
      if (Nvl(f($RS2,'cd_acao'),'')>'') {
        $l_html.=chr(13).'   <tr><td><font size=\'1\'><b>Programa:</b></font></td>';
        $l_html.=chr(13).'       <td><font size=\'1\'>'.f($RS2,'cd_ppa_pai').' - '.f($RS2,'nm_ppa_pai').'</font></div></td></tr>';
        $l_html.=chr(13).'   <tr><td><font size=\'1\'><b>Ação:</b></font></td>';
        $l_html.=chr(13).'       <td><font size=\'1\'>'.f($RS2,'cd_acao').' - '.f($RS2,'nm_ppa').'</font></div></td></tr>';
        $l_html.=chr(13).'   <tr><td><font size=\'1\'><b>Unidade:</b></font></td>';
        $l_html.=chr(13).'       <td><font size=\'1\'>'.f($RS2,'cd_unidade').' - '.f($RS2,'ds_unidade').'</font></div></td></tr>';
      } 
      // Se o programa interno for informado, exibe.
      if (Nvl(f($RS2,'sq_isprojeto'),'')>'') {
        $l_html.=chr(13).'   <tr><td><font size=\'1\'><b>Programa Interno:</b></font></td>';
        if (Nvl(f($RS2,'cd_pri'),'')>'') $l_html.=chr(13).'       <td><div align=\'justify\'><font size=\'1\'><b>'.f($RS2,'cd_pri').' - '.f($RS2,'nm_pri').'</b></font></div></td></tr>';
        else                             $l_html.=chr(13).'       <td><div align=\'justify\'><font size=\'1\'><b>'.f($RS2,'nm_pri').'</b></font></div></td></tr>';
      } 
    } 
    $l_html.=chr(13).'   <tr><td width=\'30%\'><font size=\'1\'><b>Descrição:</b></font></td>';
    $l_html.=chr(13).'       <td><div align=\'justify\'><font size=\'1\'>'.Nvl(f($RS1,'assunto'),'-').'</font></div></td></tr>';
    $l_html.=chr(13).'   <tr><td><font size=\'1\'><b>Recurso Programado '.$w_ano.':</b></font></td>';
    $l_html.=chr(13).'       <td><font size=\'1\'>R$ '.number_format(f($RS1,'valor'),2,',','.').'</font></td></tr>';
    if (Nvl(f($RS2,'cd_acao'),'')>'') {
      $l_html.=chr(13).'   <tr><td><font size=\'1\'><b>Limite Orçamentário '.$w_ano.':</b></font></td>';
      $l_html.=chr(13).'       <td><font size=\'1\'>R$ '.number_format(f($RS1,'custo_real'),2,',','.').'</font></td></tr>';
    } 
    if ($P4==1) {
      $l_html.=chr(13).'   <tr><td><font size=\'1\'><b>Área Planejamento:</b></font></td>';
      $l_html.=chr(13).'       <td><font size=\'1\'>'.f($RS1,'nm_unidade_resp').'</font></td></tr>';
      $l_html.=chr(13).'   <tr><td><font size=\'1\'><b>Responsável SISPLAM:</b></font></td>';
      $l_html.=chr(13).'       <td><font size=\'1\'>'.f($RS1,'nm_sol').'</font></td></tr>';
    } else {
      $l_html.=chr(13).'   <tr><td><font size=\'1\'><b>Área Planejamento:</b></font></td>';
      $l_html.=chr(13).'       <td><font size=\'1\'>'.ExibeUnidade('../',$w_cliente,f($RS1,'nm_unidade_resp'),f($RS1,'sq_unidade'),$TP).'</font></td></tr>';
      $l_html.=chr(13).'   <tr><td><font size=\'1\'><b>Responsável SISPLAM:</b></font></td>';
      $l_html.=chr(13).'       <td><font size=\'1\'>'.ExibePessoa('../',$w_cliente,f($RS1,'solicitante'),$TP,f($RS1,'nm_sol_comp')).'</font></td></tr>';
    } 
    $l_html.=chr(13).'   <tr><td><font size=\'1\'><b>Início Previsto:</b></font></td>';
    $l_html.=chr(13).'       <td><font size=\'1\'>'.FormataDataEdicao(f($RS1,'inicio')).'</font></td></tr>';
    $l_html.=chr(13).'   <tr><td><font size=\'1\'><b>Fim Previsto:</b></font></td>';
    $l_html.=chr(13).'       <td><font size=\'1\'>'.FormataDataEdicao(f($RS1,'fim')).'</font></td></tr>';
    $l_html.=chr(13).'   <tr><td><font size=\'1\'><b>Prioridade:</b></font></td>';
    $l_html.=chr(13).'       <td><font size=\'1\'>'.RetornaPrioridade(f($RS1,'prioridade')).'</font></td></tr>';
    $l_html.=chr(13).'   <tr><td><font size=\'1\'><b>Parecerias Externas:</b></font></td>';
    $l_html.=chr(13).'       <td><font size=\'1\'>'.Nvl(f($RS1,'proponente'),'-').'</font></td></tr>';
    $l_html.=chr(13).'   <tr><td><font size=\'1\'><b>Fase Atual:</b></font></td>';
    $l_html.=chr(13).'       <td><font size=\'1\'>'.Nvl(f($RS1,'nm_tramite'),'-').'</font></td></tr>';
  }
  // Dados da conclusão do programa, se ela estiver nessa situação
  if (upper($l_conclusao)==upper('sim')) {
    if (f($RS1,'concluida')=='S' && Nvl(f($RS1,'data_conclusao'),'')>'') {
      $l_html.=chr(13).'      <tr><td colspan=\'2\'><br><font size=\'2\'><b>DADOS DA CONCLUSÃO DA TAREFA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      if (Nvl(f($RS2,'cd_acao'),'')=='') {
        $l_html.=chr(13).'   <tr><td><font size=\'1\'><b>Recurso Executado:</b></font></td>';
        $l_html.=chr(13).'       <td><font size=\'1\'>'.number_format(f($RS1,'custo_real'),2,',','.').'</font></td></tr>';
      } 
      $l_html.=chr(13).'   <tr><td><font size=\'1\'><b>Início Real:</b></font></td>';
      $l_html.=chr(13).'       <td><font size=\'1\'>'.FormataDataEdicao(f($RS1,'inicio_real')).'</font></td></tr>';
      $l_html.=chr(13).'   <tr><td><font size=\'1\'><b>Fim Real:</b></font></td>';
      $l_html.=chr(13).'       <td><font size=\'1\'>'.FormataDataEdicao(f($RS1,'fim_real')).'</font></td></tr>';
      $l_html.=chr(13).'   <tr><td><font size=\'1\'><b>Nota de Conclusão:</b></font></td>';
      $l_html.=chr(13).'       <td><div align=\'justify\'><font size=\'1\'>'.CRLF2BR(f($RS1,'nota_conclusao')).'</font></div></td></tr>';
    } 
  } 
  //Responsável
  if (upper($l_responsavel)==upper('sim')) {
    $l_html.=chr(13).'      <tr><td colspan=\'2\'><br><font size=\'2\'><b>RESPONSÁVEIS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    if (f($RS1,'nm_responsavel')>'') {
      $l_html.=chr(13).'   <tr><td><font size=\'1\'><b>Responsável pela Tarefa:</b></font></td>';
      $l_html.=chr(13).'       <td><font size=\'1\'>'.f($RS1,'nm_responsavel').'</font></td></tr>';
      $l_html.=chr(13).'   <tr><td><font size=\'1\'><b>Telefone:</b></font></td>';
      $l_html.=chr(13).'       <td><font size=\'1\'>'.Nvl(f($RS1,'fn_responsavel'),'-').'</font></td></tr>';
      $l_html.=chr(13).'   <tr><td><font size=\'1\'><b>E-mail:</b></font></td>';
      $l_html.=chr(13).'       <td><font size=\'1\'>'.Nvl(f($RS1,'em_responsavel'),'-').'</font></td></tr>';
    } else {
      $l_html.=chr(13).'   <tr><td colspan=\'2\'><font size=\'1\'><div align=\'center\'>Nenhum responsável cadastrado</div></font></td>';
    } 
  } 
  // Arquivos vinculados ao programa
  if (upper($l_anexo)==upper('sim')) {
    $l_html.=chr(13).'      <tr><td colspan=\'2\'><br><font size=\'2\'><b>ANEXOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $RS2 = db_getSolicAnexo::getInstanceOf($dbms,$l_chave,null,$w_cliente);
    $RS2 = SortArray($RS2,'nome','asc');
    if (count($RS2)>0) {
      $l_html.=chr(13).'   <tr><td colspan=\'2\'><div align=\'center\'>';
      $l_html.=chr(13).'     <table width=100%  border=\'1\' bordercolor=\'#00000\'>';
      $l_html.=chr(13).'       <tr><td bgColor=\'#f0f0f0\'><div align=\'center\'><font size=\'1\'><b>Título</b></font></div></td>';
      $l_html.=chr(13).'         <td bgColor=\'#f0f0f0\'><div align=\'center\'><font size=\'1\'><b>Descrição</b></font></div></td>';
      $l_html.=chr(13).'         <td bgColor=\'#f0f0f0\'><div align=\'center\'><font size=\'1\'><b>Tipo</b></font></div></td>';
      $l_html.=chr(13).'         <td bgColor=\'#f0f0f0\'><div align=\'center\'><font size=\'1\'><b>KB</b></font></div></td>';
      $l_html.=chr(13).'       </tr>';
      foreach($RS2 as $row2) {
        $l_html.=chr(13).'       <tr><td><font size=\'1\'>'.LinkArquivo('HL',$w_cliente,f($row2,'chave_aux'),'_blank','Clique para exibir o arquivo em outra janela.',f($row2,'nome'),null).'</font></td>';
        $l_html.=chr(13).'           <td><font size=\'1\'>'.Nvl(f($row2,'descricao'),'-').'</font></td>';
        $l_html.=chr(13).'           <td><font size=\'1\'>'.f($row2,'tipo').'</font></td>';
        $l_html.=chr(13).'         <td><div align=\'right\'><font size=\'1\'>'.(round(f($row2,'tamanho')/1024,1)).'&nbsp;</font></td>';
        $l_html.=chr(13).'      </tr>';
      } 
      $l_html.=chr(13).'         </table></div></td></tr>';
    } else {
      $l_html.=chr(13).'      <tr><td colspan=\'2\'><div align=\'center\'><font size=\'1\'>Nenhuma arquivo cadastrado</font></div></td></tr>';
    } 
  } 
  // Encaminhamentos
  if (upper($l_ocorrencia)==upper('sim')) {
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>OCORRÊNCIAS E ANOTAÇÕES<hr NOSHADE color=#000000 SIZE=1></b></td></tr>';
    $RS1 = db_getSolicLog::getInstanceOf($dbms,$l_chave,null,null,'LISTA');
    $RS1 = SortArray($RS1,'phpdt_data','desc','sq_siw_solic_log','desc');
    $l_html.=chr(13).'   <tr><td colspan="2"><div align="center">';
    $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
    $l_html.=chr(13).'       <tr><td bgColor="#f0f0f0"><div align="center"><b>Data</b></div></td>';
    $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="center"><b>Ocorrência/Anotação</b></div></td>';
    $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="center"><b>Responsável</b></div></td>';
    $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="center"><b>Fase/Destinatário</b></div></td>';
    $l_html.=chr(13).'       </tr>';
    $i=0;
    if (count($RS1)==0) {
      $w_html .= chr(13).'      <tr bgcolor="'.$conTrBgColor.'"><td colspan=2 align="center"><b>Não foram encontrados encaminhamentos.</b></td></tr>';
    } else {
      $w_html .= chr(13).'      <tr bgcolor="'.$conTrBgColor.'" valign="top">';
      $w_cor=$conTrBgColor;
      $i = 0;
      foreach ($RS1 as $row1) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        if ($i==0) {
          $l_html.=chr(13).'     <td colspan=4>Fase atual: <b>'.f($row1,'fase').'</b></td></tr>';
          $i=1;
        }
        $w_html = $w_html.chr(13).'      <tr valign="top" bgcolor="'.$w_cor.'">';
        $l_html.=chr(13).'        <td nowrap>'.FormataDataEdicao(f($row1,'phpdt_data'),3).'</td>';
        if (Nvl(f($row1,'caminho'),'')>'') {
          $l_html.=chr(13).'        <td>'.CRLF2BR(Nvl(f($row1,'despacho'),'---').'<br>'.LinkArquivo('HL',$w_cliente,f($row1,'sq_siw_arquivo'),'_blank','Clique para exibir o anexo em outra janela.','Anexo - '.f($row1,'tipo').' - '.round(f($row1,'tamanho')/1024,1).' KB',null)).'</td>';
        } else {
          $l_html.=chr(13).'        <td>'.CRLF2BR(Nvl(f($row1,'despacho'),'---')).'</td>';
        }         
        $l_html.=chr(13).'        <td nowrap>'.ExibePessoa('../',$w_cliente,f($row1,'sq_pessoa'),$TP,f($row1,'responsavel')).'</td>';
        if ((Nvl(f($row1,'sq_demanda_log'),'')>'') && (Nvl(f($row1,'destinatario'),'')>''))         $l_html.=chr(13).'        <td nowrap>'.ExibePessoa('../',$w_cliente,f($row1,'sq_pessoa_destinatario'),$TP,f($row1,'destinatario')).'</td>';
        elseif ((Nvl(f($row1,'sq_demanda_log'),'')>'')  && (Nvl(f($row1,'destinatario'),'')==''))   $l_html.=chr(13).'        <td nowrap>Anotação</td>';
        else $l_html.=chr(13).'        <td nowrap>'.Nvl(f($row1,'tramite'),'---').'</td>';
        $l_html.=chr(13).'      </tr>';
      } 
      $l_html.=chr(13).'         </table></div></td></tr>';
    } 
  } 
  if (upper($l_dados_consulta)==upper('sim')) {
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS DA CONSULTA<hr NOSHADE color=#000000 SIZE=1></b></td></tr>';
    $l_html.=chr(13).'   <tr><td><b>Consulta Realizada por:</b></td>';
    $l_html.=chr(13).'       <td>'.$_SESSION['NOME_RESUMIDO'].'</td></tr>';
    $l_html.=chr(13).'   <tr><td><b>Data da Consulta:</b></td>';
    $l_html.=chr(13).'       <td>'.FormataDataEdicao(time(),3).'</td></tr>';
  }
  $l_html.=chr(13).'         </table>';
  return $l_html;
} 
?>