<?php
// =========================================================================
// Rotina de visualização dos dados do programa
// -------------------------------------------------------------------------
function VisualPrograma($l_chave,$O,$l_usuario,$P1,$P4,$l_identificacao,$l_responsavel,$l_qualitativa,$l_orcamentaria,$l_indicador,$l_restricao,$l_interessado,$l_anexo,$l_acao,$l_ocorrencia,$l_consulta) {
  extract($GLOBALS);
  $l_html='';
  // Recupera os dados do programa
  $sql = new db_getSolicData_IS; $RS = $sql->getInstanceOf($dbms,$l_chave,'ISPRGERAL');
  foreach($RS as $row){$RS=$row; break;}
  //Se for para exibir só a ficha resumo do programa.
  if ($P1==1 || $P1==2 || $P1==3) {
    if (!($P4==1)) $l_html.=chr(13).'      <tr><td align="right" colspan="2"><br><b><A class="HL" HREF="'.$w_dir.'programa.php?par=Visual&O=L&w_chave='.f($RS,'sq_siw_solicitacao').'&w_tipo=volta&P1=&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe as informações da ação.">Exibir todas as informações</a></td></tr>';
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>PROGRAMA: '.f($RS,'cd_programa').' - '.f($RS,'ds_programa').'</b></font></div></td></tr>';
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    // Identificação do programa
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>IDENTIFICAÇÃO DO PROGRAMA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $l_html.=chr(13).'   <tr><td width="30%"><b>Unidade Orçamentária:</b></td>';
    $l_html.=chr(13).'       <td>'.f($RS,'nm_orgao').'</td></tr>';
    if ($P4==1) {
      $l_html.=chr(13).'   <tr><td><b>Unidade Administrativa:</b></td>';
      $l_html.=chr(13).'       <td>'.f($RS,'nm_unidade_adm').'</td></tr>';
    } else {
      $l_html.=chr(13).'   <tr><td><b>Unidade Administrativa:</b></td>';
      $l_html.=chr(13).'       <td>'.ExibeUnidade('../',$w_cliente,f($RS,'nm_unidade_adm'),f($RS,'sq_unidade_adm'),$TP).'</td></tr>';
    } 
    if ($P4==1) {
      $l_html.=chr(13).'   <tr><td><b>Área Planejamento:</b></td>';
      $l_html.=chr(13).'       <td>'.f($RS,'nm_unidade_resp').'</td></tr>';
      $l_html.=chr(13).'   <tr><td><b>Responsável Monitoramento:</b></td>';
      $l_html.=chr(13).'       <td>'.f($RS,'nm_sol').'</td></tr>';
    } else {
      $l_html.=chr(13).'   <tr><td><b>Área Planejamento:</b></td>';
      $l_html.=chr(13).'       <td>'.ExibeUnidade('../',$w_cliente,f($RS,'nm_unidade_resp'),f($RS,'sq_unidade'),$TP).'</td></tr>';
      $l_html.=chr(13).'   <tr><td><b>Responsável Monitoramento:</b></td>';
      $l_html.=chr(13).'       <td>'.ExibePessoa('../',$w_cliente,f($RS,'solicitante'),$TP,f($RS,'nm_sol_comp')).'</td></tr>';
    } 
    $l_html.=chr(13).'   <tr><td><b>Endereço Internet:</b></td>';
    $l_html.=chr(13).'       <td>'.Nvl(f($RS,'ln_programa'),'-').'</td></tr>';
    $l_html.=chr(13).'   <tr><td><b>Recurso Programado '.$w_ano.':</b></td>';
    $l_html.=chr(13).'       <td>R$ '.number_format(f($RS,'valor'),2,',','.').'</td></tr>';
    // Indicadores do programa
    // Recupera todos os registros para a listagem     
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>INDICADORES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $sql = new db_getSolicIndic_IS; $RS1 = $sql->getInstanceOf($dbms,$l_chave,null,'LISTA',null,null);
    $RS1 = SortArray($RS1,'ordem','asc');
    if (count($RS1)>0) {
      $l_cont=1;
      foreach($RS1 as $row1) {
        $sql = new db_getSolicIndic_IS; $RS2 = $sql->getInstanceOf($dbms,$l_chave,f($row1,'sq_indicador'),'REGISTRO',null,null);
        foreach($RS2 as $row2){$RS2=$row2; break;}
        $l_html.=chr(13).'   <tr><td valigin="top" bgcolor="#f0f0f0"><b>'.$l_cont.') Indicador:</b></td>';
        $l_html.=chr(13).'       <td bgcolor="#f0f0f0"><b>'.f($RS2,'titulo').'</b></td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Tipo indicador:</b></td>';
        $l_html.=chr(13).'       <td>'.f($RS2,'nm_tipo').'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Quantitativo:</b></td>';
        $l_html.=chr(13).'       <td>'.number_format((Nvl(f($RS2,'quantidade'),0)),2,',','.').'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Índice de Referência:</b></td>';
        $l_html.=chr(13).'       <td>'.f($RS2,'valor_referencia').'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Data de Referência:</b></td>';
        $l_html.=chr(13).'       <td>'.FormataDataEdicao(f($RS2,'apuracao_referencia')).'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Índice Apurado:</b></td>';
        $l_html.=chr(13).'       <td>'.number_format(Nvl(f($RS2,'valor_apurado'),0),0,',','.').'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Indicador PPA:</b></td>';
        if (Nvl(f($RS2,'cd_indicador'),'')>'') $l_html.=chr(13).'       <td>Sim</td></tr>';
        else                                   $l_html.=chr(13).'       <td>Não</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Unidade de Medida:</b></td>';
        $l_html.=chr(13).'       <td>'.Nvl(f($RS2,'nm_unidade_medida'),'-').'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Cumulativo:</b></td>';
        if (f($RS2,'cumulativa')=='N') $l_html.=chr(13).'       <td>Não</td></tr>';
        else                           $l_html.=chr(13).'       <td>Sim</td></tr>';
        $l_html.=chr(13).'   <tr><td colspan="2"><div align="center">';
        $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
        $l_html.=chr(13).'       <tr><td bgColor="#cccccc" colspan="4"><div align="center"><b>Previsão</b></div></td></tr>';
        $l_html.=chr(13).'       <tr><td bgColor="#f0f0f0"><div align="center"><b>2004</b></div></td>';
        $l_html.=chr(13).'           <td bgColor="#f0f0f0"><div align="center"><b>2005</b></div></td>';
        $l_html.=chr(13).'           <td bgColor="#f0f0f0"><div align="center"><b>2006</b></div></td>';
        $l_html.=chr(13).'           <td bgColor="#f0f0f0"><div align="center"><b>2007</b></div></td>';
        $l_html.=chr(13).'       </tr>';
        $l_html.=chr(13).'       <tr><td align="right">'.number_format(Nvl(f($RS2,'previsao_ano_1'),0),2,',','.').'</td>';
        $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'previsao_ano_2'),0),2,',','.').'</td>';
        $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'previsao_ano_3'),0),2,',','.').'</td>';
        $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'previsao_ano_4'),0),2,',','.').'</td>';
        $l_html.=chr(13).'       </tr>';
        $l_html.=chr(13).'     </table></div></td></tr>';
        $l_cont+=1;
      } 
    } else {
      $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">Nenhum indicador cadastrado para este programa</div></td></tr>';
    } 
    // Listagem das restrições do programa
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>RESTRIÇÕES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $sql = new db_getRestricao_IS; $RS1 = $sql->getInstanceOf($dbms,'ISPRRESTR',$l_chave,null);
    $RS1 = SortArray($RS1,'phpdt_inclusao','desc');
    if (count($RS1)>0) {
      $l_cont=1;
      foreach($RS1 as $row) {
        $l_html.=chr(13).'   <tr><td valigin="top" bgcolor="#f0f0f0"><b>'.$l_cont.') Tipo:</b></td>';
        $l_html.=chr(13).'       <td bgcolor="#f0f0f0">'.f($row,'nm_tp_restricao').'</b></td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Descrição:</b></td>';
        $l_html.=chr(13).'       <td>'.f($row,'descricao').'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Providência:</b></td>';
        $l_html.=chr(13).'       <td>'.Nvl(f($row,'providencia'),'-').'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Data de Inclusão:</b></td>';
        $l_html.=chr(13).'       <td>'.FormataDataEdicao(f($row,'phpdt_inclusao'),3).'</td></tr>';
        $l_cont+=1;
      } 
    } else {
      $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">Nenhuma restrição cadastrada</div></td></tr>';
    } 
    // Ações do programa
    // Recupera todos os registros para a listagem
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>AÇÕES DO PROGRAMA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $sql = new db_getAcaoPPA_IS; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,$w_ano,f($RS,'cd_programa'),null,null,null,null,null,null,null,null);
    $RS1 = SortArray($RS1,'chave','asc');
    if (count($RS1)>0) {
      // Se não foram selecionados registros, exibe mensagem  
      $l_html.=chr(13).'   <tr><td colspan="2"><div align="center">';
      $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
      $l_html.=chr(13).'       <tr><td bgColor="#cccccc" colspan="4"><div align="center"><b>Ações</b></div></td></tr>';
      $l_html.=chr(13).'       <tr><td bgColor="#f0f0f0" width="5%" ><div align="center"><b>Cód.</b></div></td>';
      $l_html.=chr(13).'           <td bgColor="#f0f0f0" width="46%"><div align="center"><b>Descrição</b></div></td>';
      $l_html.=chr(13).'           <td bgColor="#f0f0f0" width="30%"><div align="center"><b>Unidade</b></div></td>';
      $l_html.=chr(13).'           <td bgColor="#f0f0f0" width="14%"><div align="center"><b>Fase</b></div></td>';
      $l_html.=chr(13).'       </tr>';
      foreach($RS1 as $row) {
        if (Nvl(f($row,'sq_siw_solicitacao'),'')>'' && $P4!=1) {
          $l_html.=chr(13).'       <tr valign="top"><td align="center"><A class="HL" HREF="'.$w_dir.'acao.php?par='.'Visual&R='.$l_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'cd_acao').'</a></td>';
        } else {
          $l_html.=chr(13).'       <tr valign="top"><td align="center">'.f($row,'cd_acao').'</td>';
        } 
        $l_html.=chr(13).'           <td>'.f($row,'descricao_acao').'</td>';
        $l_html.=chr(13).'           <td>'.f($row,'cd_unidade').' - '.f($row,'ds_unidade').'</td>';
        $l_html.=chr(13).'           <td>'.Nvl(f($row,'nm_tramite'),'Não Cadastrada').'</td>';
        $l_html.=chr(13).'       </tr>';
      }
      $l_html.=chr(13).'     </table></div></td></tr>';
    } else {
      $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">Não existe nenhuma ação para este programa</div></td></tr>';
    } 
    // Encaminhamentos
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>OCORRÊNCIAS E ANOTAÇÕES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $sql = new db_getSolicLog; $RS = $sql->getInstanceOf($dbms,$l_chave,null,null,'LISTA');
    $RS = SortArray($RS,'phpdt_data','desc');
    if (count($RS)>0) {
      $l_html.=chr(13).'   <tr><td colspan="2"><div align="center">';
      $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
      $l_html.=chr(13).'       <tr><td bgColor="#f0f0f0"><div align="center"><b>Data</b></div></td>';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="center"><b>Ocorrência/Anotação</b></div></td>';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="center"><b>Responsável</b></div></td>';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="center"><b>Fase/Destinatário</b></div></td>';
      $l_html.=chr(13).'       </tr>';
      $i=0;
      foreach($RS as $row) {
        if ($i==0) {
          $l_html.=chr(13).'       <tr><td colspan="4">Fase Atual: <b>'.f($row,'fase').'</b></td></tr>';
          $i+=1;
        }
        $l_html.=chr(13).'    <tr><td nowrap>'.FormataDataEdicao(f($row,'phpdt_data'),3).'</td>';
        $l_html.=chr(13).'        <td>'.CRLF2BR(Nvl(f($row,'despacho'),'---')).'</td>';
        $l_html.=chr(13).'        <td nowrap>'.ExibePessoa('../',$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'responsavel')).'</td>';
        if ((Nvl(f($row,'sq_projeto_log'),'')>'') && (Nvl(f($row,'destinatario'),'')>''))         $l_html.=chr(13).'        <td nowrap>'.ExibePessoa('../',$w_cliente,f($row,'sq_pessoa_destinatario'),$TP,f($row,'destinatario')).'</td>';
        elseif ((Nvl(f($row,'sq_projeto_log'),'')>'')  && (Nvl(f($row,'destinatario'),'')==''))   $l_html.=chr(13).'        <td nowrap>Anotação</td>';
        else $l_html.=chr(13).'        <td nowrap>'.Nvl(f($row,'tramite'),'---').'</td>';
        $l_html.=chr(13).'      </tr>';
      } 
      $l_html.=chr(13).'         </table></div></td></tr>';
    } else {
      $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">Não foi encontrado nenhum encaminhamento</div></td></tr>';
    } 
    $l_html.=chr(13).'</table>';
  } else {
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>PROGRAMA: '.f($RS,'cd_programa').' - '.f($RS,'ds_programa').'</b></font></div></td></tr>';
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    // Identificação do programa
    if (upper($l_identificacao)==upper('sim')) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>IDENTIFICAÇÃO DO PROGRAMA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html.=chr(13).'   <tr><td width="30%"><b>Unidade Orçamentária:</b></td>';
      $l_html.=chr(13).'       <td><div align="justify"><b>'.f($RS,'nm_orgao').'</b></div></td></tr>';
      if ($P4==1) {
        $l_html.=chr(13).'   <tr><td><b>Unidade Administrativa:</b></td>';
        $l_html.=chr(13).'       <td>'.f($RS,'nm_unidade_adm').'</td></tr>';
      } else {
        $l_html.=chr(13).'   <tr><td><b>Unidade Administrativa:</b></td>';
        $l_html.=chr(13).'       <td>'.ExibeUnidade('../',$w_cliente,f($RS,'nm_unidade_adm'),f($RS,'sq_unidade_adm'),$TP).'</td></tr>';
      } 
      if ($P4==1) {
        $l_html.=chr(13).'   <tr><td><b>Área Planejamento:</b></td>';
        $l_html.=chr(13).'       <td>'.f($RS,'nm_unidade_resp').'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Responsável Monitoramento:</b></td>';
        $l_html.=chr(13).'       <td>'.f($RS,'nm_sol').'</td></tr>';
      } else {
        $l_html.=chr(13).'   <tr><td><b>Área Planejamento:</b></td>';
        $l_html.=chr(13).'       <td>'.ExibeUnidade('../',$w_cliente,f($RS,'nm_unidade_resp'),f($RS,'sq_unidade'),$TP).'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Responsável Monitoramento:</b></td>';
        $l_html.=chr(13).'       <td>'.ExibePessoa('../',$w_cliente,f($RS,'solicitante'),$TP,f($RS,'nm_sol_comp')).'</td></tr>';
      } 
      $l_html.=chr(13).'   <tr><td><b>Endereço Internet:</b></td>';
      $l_html.=chr(13).'       <td>'.Nvl(f($RS,'ln_programa'),'-').'</td></tr>';
      $l_html.=chr(13).'   <tr><td><b>Recurso Programado '.$w_ano.':</b></td>';
      $l_html.=chr(13).'       <td>R$ '.number_format(f($RS,'valor'),2,',','.').'</td></tr>';
      if (f($RS,'mpog_ppa')=='S') {
        $l_html.=chr(13).'   <tr><td><b>Selecionado SPI/MP:</b></td>';
        $l_html.=chr(13).'       <td>Sim</td></tr>';
      } else {
        $l_html.=chr(13).'   <tr><td><b>Selecionado SPI/MP:</b></td>';
        $l_html.=chr(13).'       <td>Não</td></tr>';
      } 
      if (f($RS,'relev_ppa')=='S') {
        $l_html.=chr(13).'   <tr><td><b>Selecionado SE/SEPPIR:</b></td>';
        $l_html.=chr(13).'       <td>Sim</td></tr>';
      } else {
        $l_html.=chr(13).'   <tr><td><b>Selecionado SE/SEPPIR:</b></td>';
        $l_html.=chr(13).'       <td>Não</td></tr>';
      } 
      $l_html.=chr(13).'   <tr><td><b>Natureza:</b></td>';
      $l_html.=chr(13).'       <td>'.f($RS,'nm_natureza').'</td></tr>';
      $l_html.=chr(13).'   <tr><td><b>Tipo Programa:</b></td>';
      $l_html.=chr(13).'       <td>'.f($RS,'nm_tipo_programa').'</td></tr>';
      $l_html.=chr(13).'   <tr><td><b>Horizonte:</b></td>';
      $l_html.=chr(13).'       <td>'.f($RS,'nm_horizonte').'</td></tr>';
      $l_html.=chr(13).'   <tr><td><b>Parcerias Externas:</b></td>';
      $l_html.=chr(13).'       <td>'.CRLF2BR(Nvl(f($RS,'proponente'),'-')).'</td></tr>';
      $l_html.=chr(13).'   <tr><td><b>Parcerias Internas:</b></td>';
      $l_html.=chr(13).'       <td>'.CRLF2BR(Nvl(f($RS,'palavra_chave'),'-')).'</td></tr>';
      $l_html.=chr(13).'   <tr><td><b>Fase Atual do Programa:</b></td>';
      $l_html.=chr(13).'       <td>'.Nvl(f($RS,'nm_tramite'),'-').'</td></tr>';
    } 
    // Responsaveis
    if (upper($l_responsavel)==upper('sim')) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>RESPONSÁVEIS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      if (f($RS,'nm_gerente_programa')>'' || f($RS,'nm_gerente_executivo')>'' || f($RS,'nm_gerente_adjunto')>'') {
        if (Nvl(f($RS,'nm_gerente_programa'),'')>'') {
          $l_html.=chr(13).'   <tr><td><b>Gerente do Programa:</b></td>';
          $l_html.=chr(13).'       <td>'.f($RS,'nm_gerente_programa').'</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>Telefone:</b></td>';
          $l_html.=chr(13).'       <td>'.Nvl(f($RS,'fn_gerente_programa'),'-').'</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>E-mail:</b></td>';
          $l_html.=chr(13).'       <td>'.Nvl(f($RS,'em_gerente_programa'),'-').'</td></tr>';
        } 
        if (Nvl(f($RS,'nm_gerente_executivo'),'')>'') {
          $l_html.=chr(13).'   <tr><td><b>Gerente Executivo do Programa:</b></td>';
          $l_html.=chr(13).'       <td>'.f($RS,'nm_gerente_executivo').'</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>Telefone:</b></td>';
          $l_html.=chr(13).'       <td>'.Nvl(f($RS,'fn_gerente_executivo'),'-').'</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>E-mail:</b></td>';
          $l_html.=chr(13).'       <td>'.Nvl(f($RS,'em_gerente_executivo'),'-').'</td></tr>';
        } 
        if (Nvl(f($RS,'nm_gerente_adjunto'),'')>'') {
          $l_html.=chr(13).'   <tr><td><b>Gerente Executivo Adjunto:</b></td>';
          $l_html.=chr(13).'       <td>'.f($RS,'nm_gerente_adjunto').'</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>Telefone:</b></td>';
          $l_html.=chr(13).'       <td>'.Nvl(f($RS,'fn_gerente_adjunto'),'-').'</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>E-mail:</b></td>';
          $l_html.=chr(13).'       <td>'.Nvl(f($RS,'em_gerente_adjunto'),'-').'</td></tr>';
        } 
      } else {
        $l_html.=chr(13).'   <tr><td colspan="2"><div align="center">Nenhum responsável cadastrado</div></td>';
      } 
    } 
    if (upper($l_identificacao)==upper('sim')) {
      // Dados da conclusão do programa, se ela estiver nessa situação
      if (f($RS,'concluida')=='S' && Nvl(f($RS,'data_conclusao'),'')>'') {
        $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS DA CONCLUSÃO DO PROGRAMA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Recurso Executado:</b></td>';
        $l_html.=chr(13).'       <td>'.number_format(f($RS,'custo_real'),2,',','.').'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Nota de Conclusão:</b></td>';
        $l_html.=chr(13).'       <td><div align="justify">'.CRLF2BR(f($RS,'nota_conclusao')).'</div></td></tr>';
      } 
    } 
    // Programação Qualitativa
    if (upper($l_qualitativa)==upper('sim')) {
      $l_html.=chr(13).'   <tr><td colspan="2"><br><font size="2"><b>PROGRAMAÇÃO QUALITATIVA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html.=chr(13).'   <tr><td valign="top"><b>Objetivo:</b></td>';
      $l_html.=chr(13).'       <td><div align="justify">'.Nvl(f($RS,'objetivo'),'-').'</div></td></tr>';
      $l_html.=chr(13).'   <tr><td valign="top"><b>Contribuição do programa para que o objetivo setorial seja alcançado:</b></td>';
      $l_html.=chr(13).'       <td><div align="justify">'.Nvl(f($RS,'contribuicao_objetivo'),'-').'</div></td></tr>';
      $l_html.=chr(13).'   <tr><td valign="top"><b>Justificativa:</b></td>';
      $l_html.=chr(13).'       <td><div align="justify">'.Nvl(f($RS,'justificativa_sigplan'),'-').'</div></td></tr>';
      $l_html.=chr(13).'   <tr><td valign="top"><b>Público Alvo:</b></td>';
      $l_html.=chr(13).'       <td><div align="justify">'.Nvl(f($RS,'publico_alvo'),'-').'</div></td></tr>';
      $l_html.=chr(13).'   <tr><td valign="top"><b>Resultados Esperados:</b></td>';
      $l_html.=chr(13).'       <td><div align="justify">'.Nvl(f($RS,'descricao'),'-').'</div></td></tr>';
      $l_html.=chr(13).'   <tr><td valign="top"><b>Estratégia de Implementação:</b></td>';
      $l_html.=chr(13).'       <td><div align="justify">'.Nvl(f($RS,'estrategia'),'-').'</div></td></tr>';
      $l_html.=chr(13).'   <tr><td valign="top"><b>Potencialidades:</b></td>';
      $l_html.=chr(13).'       <td><div align="justify">'.Nvl(f($RS,'potencialidades'),'-').'</div></td></tr>';
      $l_html.=chr(13).'   <tr><td valign="top"><b>Sistemática e estratégias a serem adotadas para o monitoramento do programa:</b></td>';
      $l_html.=chr(13).'       <td><div align="justify">'.Nvl(f($RS,'estrategia_monit'),'-').'</div></td></tr>';
      $l_html.=chr(13).'   <tr><td valign="top"><b>Sistemática e estratégias a serem adotadas para a avaliação do programa:</b></td>';
      $l_html.=chr(13).'       <td><div align="justify">'.Nvl(f($RS,'metodologia_aval'),'-').'</div></td></tr>';
      $l_html.=chr(13).'   <tr><td valign="top"><b>Observações:</b></td>';
      $l_html.=chr(13).'       <td><div align="justify">'.Nvl(f($RS,'justificativa'),'-').'</div></td></tr>';
    } 
    // Programação orçamentaria
    if (upper($l_orcamentaria)==upper('sim')) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>PROGRAMAÇÃO ORÇAMENTÁRIA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_cont=1;
      $sql = new db_getPPADadoFinanc_IS; $RS1 = $sql->getInstanceOf($dbms,f($RS,'cd_programa'),null,$w_ano,$w_cliente,'VALORFONTE');
      if (count($RS1)<=0) {
        $l_html.=chr(13).'   <tr><td colspan="2">Nao existe nenhuma programação financeira para este programa</td></tr>';
      } else {
        $l_html.=chr(13).'   <tr><td><b>Valor Estimado para o Programa:</b></td>';
        $l_html.=chr(13).'       <td>'.number_format(Nvl(f($RS,'valor_estimado'),0),2,',','.').'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Valor no PPA:</b></td>';
        $l_html.=chr(13).'       <td>'.number_format(Nvl(f($RS,'valor_ppa'),0),2,',','.').'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Tipo de Orçamento:</b></td>';
        $i=0;
        foreach($RS1 as $row) {
          if ($i==0) {
            $l_html.=chr(13).'       <td>'.Nvl(f($row,'nm_orcamento'),'-').'</td></tr>';
            $l_html.=chr(13).'   <tr><td colspan="2" valigin="top" bgcolor="#f0f0f0"><b>Valor por Fonte:</b></td>';
            $i=1;
          }
          $l_html.=chr(13).'   <tr><td valigin="top" bgcolor="#f0f0f0"><b>'.$l_cont.') Fonte:</b></td>';
          $l_html.=chr(13).'       <td bgcolor="#f0f0f0"><b>'.f($row,'nm_fonte').'</b></td></tr>';
          $l_html.=chr(13).'   <tr><td colspan="2"><div align="center">';
          $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
          $l_html.=chr(13).'       <tr><td bgColor="#f0f0f0"><div align="center"><b>2004</b></div></td>';
          $l_html.=chr(13).'           <td bgColor="#f0f0f0"><div align="center"><b>2005</b></div></td>';
          $l_html.=chr(13).'           <td bgColor="#f0f0f0"><div align="center"><b>2006</b></div></td>';
          $l_html.=chr(13).'           <td bgColor="#f0f0f0"><div align="center"><b>2007</b></div></td>';
          $l_html.=chr(13).'           <td bgColor="#f0f0f0"><div align="center"><b>2008</b></div></td>';
          $l_html.=chr(13).'           <td bgColor="#f0f0f0"><div align="center"><b>Total 2004-2008</b></div></td></tr>';
          $l_html.=chr(13).'       <tr><td><div align="right">'.number_format(Nvl(f($row,'valor_ano_1'),0.00),2,',','.').'</div></td>';
          $l_html.=chr(13).'           <td><div align="right">'.number_format(Nvl(f($row,'valor_ano_2'),0.00),2,',','.').'</div></td>';
          $l_html.=chr(13).'           <td><div align="right">'.number_format(Nvl(f($row,'valor_ano_3'),0.00),2,',','.').'</div></td>';
          $l_html.=chr(13).'           <td><div align="right">'.number_format(Nvl(f($row,'valor_ano_4'),0.00),2,',','.').'</div></td>';
          $l_html.=chr(13).'           <td><div align="right">'.number_format(Nvl(f($row,'valor_ano_5'),0.00),2,',','.').'</div></td>';
          $l_html.=chr(13).'           <td><div align="right">'.number_format(Nvl(f($row,'valor_total'),0.00),2,',','.').'</div></td></tr>';
          $l_html.=chr(13).'     </table></div></td></tr>';
          $l_cont=$l_cont+1;
        } 
        $l_html.=chr(13).'   <tr><td colspan="2">Fonte dos Dados: SIGPLAN/MP</td></tr>';
      } 
    } 
    // Indicadores do programa
    if (upper($l_indicador)==upper('sim')) {
      // Recupera todos os registros para a listagem     
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>INDICADORES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $sql = new db_getSolicIndic_IS; $RS1 = $sql->getInstanceOf($dbms,$l_chave,null,'LISTA',null,null);
      $RS1 = SortArray($RS1,'ordem','asc');
      if (count($RS1)>0) {
        $l_cont=1;
        foreach($RS1 as $row) {
          $sql = new db_getSolicIndic_IS; $RS2 = $sql->getInstanceOf($dbms,$l_chave,f($row,'sq_indicador'),'REGISTRO',null,null);
          foreach($RS2 as $row2){$RS=$row2; break;}
          $l_html.=chr(13).'   <tr><td valigin="top" bgcolor="#f0f0f0"><b>'.$l_cont.') Indicador:</b></td>';
          $l_html.=chr(13).'       <td bgcolor="#f0f0f0"><b>'.f($row2,'titulo').'</b></td></tr>';
          $l_html.=chr(13).'   <tr><td><b>Tipo indicador:</b></td>';
          $l_html.=chr(13).'       <td>'.Nvl(f($row2,'nm_tipo'),'-').'</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>Quantitativo:</b></td>';
          $l_html.=chr(13).'       <td>'.number_format((Nvl(f($row2,'quantidade'),0)),2,',','.').'</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>Índice de Referência:</b></td>';
          $l_html.=chr(13).'       <td>'.number_format(Nvl(f($row2,'valor_referencia'),0),0,',','.').'</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>Data de Referência:</b></td>';
          $l_html.=chr(13).'       <td>'.FormataDataEdicao(f($row2,'apuracao_referencia')).'</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>Índice Apurado:</b></td>';
          $l_html.=chr(13).'       <td>'.number_format(Nvl(f($row2,'valor_apurado'),0),0,',','.').'</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>Indicador PPA:</b></td>';
          if (Nvl(f($row2,'cd_indicador'),'')>'') $l_html.=chr(13).'       <td>Sim</td></tr>';
          else                                   $l_html.=chr(13).'       <td>Não</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>Unidade de Medida:</b></td>';
          $l_html.=chr(13).'       <td>'.Nvl(f($row2,'nm_unidade_medida'),'-').'</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>Cumulativo:</b></td>';
          if (f($row2,'cumulativa')=='N') $l_html.=chr(13).'       <td>Não</td></tr>';
          else                           $l_html.=chr(13).'       <td>Sim</td></tr>';
          $l_html.=chr(13).'   <tr><td valign="top"><b>Fórmula de Calculo:</b></td>';
          $l_html.=chr(13).'       <td valign="top">'.Nvl(f($row2,'formula'),'-').'</td></tr>';
          $l_html.=chr(13).'   <tr><td valign="top"><b>Fonte:</b></td>';
          $l_html.=chr(13).'       <td valign="top">'.Nvl(f($row2,'fonte'),'-').'</td></tr>';
          $l_html.=chr(13).'   <tr><td valign="top"><b>Periodicidade:</b></td>';
          $l_html.=chr(13).'       <td valign="top">'.Nvl(f($row2,'nm_periodicidade'),'-').'</td></tr>';
          $l_html.=chr(13).'   <tr><td valign="top"><b>Base Geográfica:</b></td>';
          $l_html.=chr(13).'       <td valign="top">'.Nvl(f($row2,'nm_base_geografica'),'-').'</td></tr>';
          $l_html.=chr(13).'   <tr><td valign="top"><b>Conceituação:</b></td>';
          $l_html.=chr(13).'       <td valign="top">'.Nvl(f($row2,'conceituacao'),'-').'</td></tr>';
          $l_html.=chr(13).'   <tr><td valign="top"><b>Interpretação:</b></td>';
          $l_html.=chr(13).'       <td valign="top">'.Nvl(f($row2,'interpretacao'),'-').'</td></tr>';
          $l_html.=chr(13).'   <tr><td valign="top"><b>Usos:</b></td>';
          $l_html.=chr(13).'       <td valign="top">'.Nvl(f($row2,'usos'),'-').'</td></tr>';
          $l_html.=chr(13).'   <tr><td valign="top"><b>Limitações:</b></td>';
          $l_html.=chr(13).'       <td valign="top">'.Nvl(f($row2,'limitacoes'),'-').'</td></tr>';
          $l_html.=chr(13).'   <tr><td valign="top"><b>Categorias sugeridas para análise:</b></td>';
          $l_html.=chr(13).'       <td valign="top">'.Nvl(f($row2,'categoria_analise'),'-').'</td></tr>';
          $l_html.=chr(13).'   <tr><td valign="top"><b>Dados estatísticos e comentários:</b></td>';
          $l_html.=chr(13).'       <td valign="top">'.Nvl(f($row2,'comentarios'),'-').'</td></tr>';
          $l_html.=chr(13).'   <tr><td valign="top"><b>Observações:</b></td>';
          $l_html.=chr(13).'       <td valign="top">'.Nvl(f($row2,'observacao'),'-').'</td></tr>';
          $l_html.=chr(13).'   <tr><td colspan="2"><div align="center">';
          $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
          $l_html.=chr(13).'       <tr><td bgColor="#cccccc" colspan="4"><div align="center"><b>Previsão</b></div></td></tr>';
          $l_html.=chr(13).'       <tr><td bgColor="#f0f0f0"><div align="center"><b>2004</b></div></td>';
          $l_html.=chr(13).'           <td bgColor="#f0f0f0"><div align="center"><b>2005</b></div></td>';
          $l_html.=chr(13).'           <td bgColor="#f0f0f0"><div align="center"><b>2006</b></div></td>';
          $l_html.=chr(13).'           <td bgColor="#f0f0f0"><div align="center"><b>2007</b></div></td>';
          $l_html.=chr(13).'       </tr>';
          $l_html.=chr(13).'       <tr><td align="right">'.number_format(Nvl(f($row2,'previsao_ano_1'),0),2,',','.').'</td>';
          $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($row2,'previsao_ano_2'),0),2,',','.').'</td>';
          $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($row2,'previsao_ano_3'),0),2,',','.').'</td>';
          $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($row2,'previsao_ano_4'),0),2,',','.').'</td>';
          $l_html.=chr(13).'       </tr>';
          $l_html.=chr(13).'     </table></div></td></tr>';
          $l_cont=$l_cont+1;
        } 
      } else {
        $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">Nenhum indicador cadastrado para este programa</div></td></tr>';
      } 
    } 
    // Listagem das restrições do programa
    if (upper($l_restricao)==upper('sim')) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>RESTRIÇÕES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $sql = new db_getRestricao_IS; $RS1 = $sql->getInstanceOf($dbms,'ISPRRESTR',$l_chave,null);
      $RS1 = SortArray($RS1,'phpdt_inclusao','desc');
      if (count($RS1)>0) {
        $l_cont=1;
        foreach($RS1 as $row) {
          $l_html.=chr(13).'   <tr><td valigin="top" bgcolor="#f0f0f0"><b>'.$l_cont.') Tipo:</b></td>';
          $l_html.=chr(13).'       <td bgcolor="#f0f0f0"><b>'.f($row,'nm_tp_restricao').'</b></td></tr>';
          $l_html.=chr(13).'   <tr><td><b>Descrição:</b></td>';
          $l_html.=chr(13).'       <td>'.f($row,'descricao').'</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>Providência:</b></td>';
          $l_html.=chr(13).'       <td>'.Nvl(f($row,'providencia'),'-').'</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>Data de Inclusão:</b></td>';
          $l_html.=chr(13).'       <td>'.FormataDataEdicao(f($row,'phpdt_inclusao'),3).'</td></tr>';
          $l_cont=$l_cont+1;
        } 
      } else {
        $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">Nenhuma restrição cadastrada</div></td></tr>';
      } 
    } 
    // Interessados na execução do programa
    if (upper($l_interessado)==upper('sim')) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>INTERESSADOS NA EXECUÇÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $sql = new db_getSolicInter; $RS1 = $sql->getInstanceOf($dbms,$l_chave,null,'LISTA');
      $RS1 = SortArray($RS1,'nome_resumido','asc');
      if (count($RS1)>0) {
        $TP=RemoveTP($TP).' - Interessados';
        $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">Clique <a class="HL" HREF="'.$w_dir.'acao.php?par=interess&R='.$l_Pagina.$par.'&O=L&w_chave='.$l_chave.'&P1=4&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" target="_blank">aqui</a> para visualizar os Interessados na execução</div></td></tr>';
      } else {
        $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">Nenhum interessado cadastrado</div></td></tr>';
      } 
    } 
    // Arquivos vinculados ao programa
    if (upper($l_anexo)==upper('sim')) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ANEXOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $sql = new db_getSolicAnexo; $RS1 = $sql->getInstanceOf($dbms,$l_chave,null,$w_cliente);
      $RS1 = SortArray($RS1,'nome','asc');
      if (count($RS1)>0) {
        $l_html.=chr(13).'   <tr><td colspan="2"><div align="center">';
        $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
        $l_html.=chr(13).'       <tr><td bgColor="#f0f0f0"><div align="center"><b>Título</b></div></td>';
        $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="center"><b>Descrição</b></div></td>';
        $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="center"><b>Tipo</b></div></td>';
        $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="center"><b>KB</b></div></td>';
        $l_html.=chr(13).'       </tr>';
        foreach($RS1 as $row) {
          $l_html.=chr(13).'       <tr><td>'.LinkArquivo('HL',$w_cliente,f($row,'chave_aux'),'_blank','Clique para exibir o arquivo em outra janela.',f($row,'nome'),null).'</td>';
          $l_html.=chr(13).'           <td>'.Nvl(f($row,'descricao'),'-').'</td>';
          $l_html.=chr(13).'           <td>'.f($row,'tipo').'</td>';
          $l_html.=chr(13).'         <td><div align="right">'.round(f($row,'tamanho')/1024).'&nbsp;</td>';
          $l_html.=chr(13).'      </tr>';
        }
        $l_html.=chr(13).'         </table></div></td></tr>';
      } else {
        $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">Nenhuma arquivo cadastrado</div></td></tr>';
      } 
    } 
    // Ações do programa
    if (upper($l_acao)==upper('sim')) {
      // Recupera todos os registros para a listagem
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>AÇÕES DO PROGRAMA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $sql = new db_getAcaoPPA_IS; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,$w_ano,f($RS,'cd_programa'),null,null,null,null,null,null,null,null);
      $RS1 = SortArray($RS1,'chave','asc');
      if (count($RS1)>0) {
        // Se não foram selecionados registros, exibe mensagem  
        $l_html.=chr(13).'   <tr><td colspan="2"><div align="center">';
        $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
        $l_html.=chr(13).'       <tr><td bgColor="#cccccc" colspan="4"><div align="center"><b>Ações</b></div></td></tr>';
        $l_html.=chr(13).'       <tr><td bgColor="#f0f0f0" width="5%" ><div align="center"><b>Cód.</b></div></td>';
        $l_html.=chr(13).'           <td bgColor="#f0f0f0" width="46%"><div align="center"><b>Descrição</b></div></td>';
        $l_html.=chr(13).'           <td bgColor="#f0f0f0" width="30%"><div align="center"><b>Unidade</b></div></td>';
        $l_html.=chr(13).'           <td bgColor="#f0f0f0" width="14%"><div align="center"><b>Fase</b></div></td>';
        $l_html.=chr(13).'       </tr>';
        foreach($RS1 as $row) {
          if (Nvl(f($row,'sq_siw_solicitacao'),'')>'' && $P4!=1)
            $l_html.=chr(13).'       <tr valign="top"><td align="center"><A class="HL" HREF="'.$w_dir.'acao.php?par='.'Visual&R='.$l_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'cd_acao').'</a></td>';
          else
          $l_html.=chr(13).'       <tr valign="top"><td align="center">'.f($row,'cd_acao').'</td>';
          $l_html.=chr(13).'           <td>'.f($row,'descricao_acao').'</td>';
          $l_html.=chr(13).'           <td>'.f($row,'cd_unidade').' - '.f($row,'ds_unidade').'</td>';
          $l_html.=chr(13).'           <td>'.Nvl(f($row,'nm_tramite'),'Não Cadastrada').'</td>';
          $l_html.=chr(13).'       </tr>';
        } 
        $l_html.=chr(13).'     </table></div></td></tr>';
      } else {
        $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">Não existe nenhuma ação para este programa</div></td></tr>';
      } 
    } 
    // Encaminhamentos
    if (upper($l_ocorrencia)==upper('sim')) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>OCORRÊNCIAS E ANOTAÇÕES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $sql = new db_getSolicLog; $RS = $sql->getInstanceOf($dbms,$l_chave,null,null,'LISTA');
      $RS = SortArray($RS,'phpdt_data','desc');
      if (count($RS)>0) {
        $l_html.=chr(13).'   <tr><td colspan="2"><div align="center">';
        $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
        $l_html.=chr(13).'       <tr><td bgColor="#f0f0f0"><div align="center"><b>Data</b></div></td>';
        $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="center"><b>Ocorrência/Anotação</b></div></td>';
        $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="center"><b>Responsável</b></div></td>';
        $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="center"><b>Fase/Destinatário</b></div></td>';
        $l_html.=chr(13).'       </tr>';
        $i=0;
        foreach($RS as $row) {
          if ($i==0) {
            $l_html.=chr(13).'       <tr><td colspan="4">Fase Atual: <b>'.f($row,'fase').'</b></td></tr>';
            $i=1;
          }
          $l_html.=chr(13).'    <tr><td nowrap>'.FormataDataEdicao(f($row,'phpdt_data'),3).'</td>';
          $l_html.=chr(13).'        <td>'.CRLF2BR(Nvl(f($row,'despacho'),'---')).'</td>';
          $l_html.=chr(13).'        <td nowrap>'.ExibePessoa('../',$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'responsavel')).'</td>';
          if ((Nvl(f($row,'sq_projeto_log'),'')>'') && (Nvl(f($row,'destinatario'),'')>''))         $l_html.=chr(13).'        <td nowrap>'.ExibePessoa('../',$w_cliente,f($row,'sq_pessoa_destinatario'),$TP,f($row,'destinatario')).'</td>';
          elseif ((Nvl(f($row,'sq_projeto_log'),'')>'')  && (Nvl(f($row,'destinatario'),'')==''))   $l_html.=chr(13).'        <td nowrap>Anotação</td>';
          else $l_html.=chr(13).'        <td nowrap>'.Nvl(f($row,'tramite'),'---').'</td>';
          $l_html.=chr(13).'      </tr>';
        } 
        $l_html.=chr(13).'         </table></div></td></tr>';
      } else {
        $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">Não foi encontrado nenhum encaminhamento</div></td></tr>';
      } 
    } 
    //Dados da Consulta
    if (upper($l_consulta)==upper('sim')) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS DA CONSULTA<hr NOSHADE color=#000000 SIZE=1></b></td></tr>';
      $l_html.=chr(13).'   <tr><td><b>Consulta Realizada por:</b></td>';
      $l_html.=chr(13).'       <td>'.$_SESSION['NOME_RESUMIDO'].'</td></tr>';
      $l_html.=chr(13).'   <tr><td><b>Data da Consulta:</b></td>';
      $l_html.=chr(13).'       <td>'.FormataDataEdicao(time(),3).'</td></tr>';
    }
  } 
  return $l_html;
} 
?>