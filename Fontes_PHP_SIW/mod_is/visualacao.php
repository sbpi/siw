<?php
// =========================================================================
// Rotina de visualização dos dados da ação
// -------------------------------------------------------------------------
function VisualAcao($l_chave,$O,$l_usuario,$P1,$P4,$l_identificacao,$l_responsavel,$l_qualitativa,$l_orcamentaria,$l_meta,$l_restricao,$l_tarefa,$l_interessado,$l_anexo,$l_ocorrencia,$l_dados_consulta,$l_conclusao) {
  extract($GLOBALS);
  $l_html='';
  // Recupera os dados da ação
  $RS1 = db_getSolicData_IS::getInstanceOf($dbms,$l_chave,'ISACGERAL');
  foreach($RS1 as $row1) {$RS1=$row1; break;}
  //Se for para exibir só a ficha resumo da ação.
  if ($P1==1 || $P1==2 || $P1==3) {
    if ($P4!=1) $l_html.=chr(13).'      <tr><td align="right" colspan="2"><br><b><A class="HL" HREF="'.$w_dir.'acao.php?par=Visual&O=L&w_chave='.f($RS1,'sq_siw_solicitacao').'&w_tipo=volta&P1=&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe as informações da ação.">Exibir todas as informações</a></td></tr>';
    $l_html.=chr(13).'      <tr><td  colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    if (Nvl(f($RS1,'cd_acao'),'')>'') $l_html.=chr(13).'   <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>AÇÃO: '.f($RS1,'cd_acao').' - '.f($RS1,'nm_ppa').'</b></div></td></tr>';
    else                              $l_html.=chr(13).'   <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>AÇÃO: '.f($RS1,'titulo').'</b></div></td></tr>';
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    // Identificação da ação
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>IDENTIFICAÇÃO DA AÇÃO<hr NOSHADE color=#000000 SIZE=1></b></td></tr>';
    // Se a ação no PPA for informada, exibe.
    if (Nvl(f($RS1,'cd_acao'),'')>'') {
      $l_html.=chr(13).'   <tr><td width="30%"><b>Programa:</b></td>';
      $l_html.=chr(13).'       <td><div align="justify"><b>'.f($RS1,'cd_ppa_pai').' - '.f($RS1,'nm_ppa_pai').'</b></div></td></tr>';
      $l_html.=chr(13).'   <tr><td><b>Ação:</b></td>';
      $l_html.=chr(13).'       <td><div align="justify">'.f($RS1,'cd_acao').' - '.f($RS1,'nm_ppa').'</div></td></tr>';
    } 
    // Se o programa interno for informado, exibe.
    if (Nvl(f($RS1,'sq_isprojeto'),'')>'') {
      $l_html.=chr(13).'   <tr><td width="30%"><b>Programa Interno:</b></td>';
      $l_html.=chr(13).'       <td><div align="justify"><b>'.f($RS1,'nm_pri').'</b></div></td></tr>';
      if (Nvl(f($RS1,'cd_acao'),'')>'') {
        $l_html.=chr(13).'   <tr><td><b>Recurso Programado '.$w_ano.':</b></td>';
        $l_html.=chr(13).'       <td>R$ '.number_format(f($RS1,'valor'),2,',','.').'</td></tr>';
      } 

    } 
    if (Nvl(f($RS1,'cd_acao'),'')>'') {
      $l_html.=chr(13).'   <tr><td><b>Orgão:</b></td>';
      $l_html.=chr(13).'       <td>'.f($RS1,'nm_orgao').'</td></tr>';
    } 
    if ($P4==1) {
      $l_html.=chr(13).'   <tr><td><b>Unidade Administrativa:</b></td>';
      $l_html.=chr(13).'       <td>'.f($RS1,'nm_unidade_adm').'</td></tr>';
    } else {
      $l_html.=chr(13).'   <tr><td><b>Unidade Administrativa:</b></td>';
      $l_html.=chr(13).'       <td>'.ExibeUnidade('../',$w_cliente,f($RS1,'nm_unidade_adm'),f($RS1,'sq_unidade_adm'),$TP).'</td></tr>';
    }
    if (Nvl(f($RS1,'cd_acao'),'')>'') {
      $l_html.=chr(13).'   <tr><td><b>Unidade Orçamentária:</b></td>';
      $l_html.=chr(13).'       <td>'.f($RS1,'cd_unidade').' - '.f($RS1,'ds_unidade').'</td></tr>';
      $l_html.=chr(13).'   <tr><td><b>Recurso Programado '.$w_ano.':</b></td>';
      $l_html.=chr(13).'       <td>R$ '.number_format(f($RS1,'valor'),2,',','.').'</td></tr>';
    } 
    if ($P4==1) {
      $l_html.=chr(13).'   <tr><td><b>Área Planejamento:</b></td>';
      $l_html.=chr(13).'       <td>'.f($RS1,'nm_unidade_resp').'</td></tr>';
      $l_html.=chr(13).'   <tr><td><b>Responsável Monitoramento:</b></td>';
      $l_html.=chr(13).'       <td>'.f($RS1,'nm_sol').'</td></tr>';
    } else {
      $l_html.=chr(13).'   <tr><td><b>Área Planejamento:</b></td>';
      $l_html.=chr(13).'       <td>'.ExibeUnidade('../',$w_cliente,f($RS1,'nm_unidade_resp'),f($RS1,'sq_unidade'),$TP).'</td></tr>';
      $l_html.=chr(13).'   <tr><td><b>Responsável Monitoramento:</b></td>';
      $l_html.=chr(13).'       <td>'.ExibePessoa('../',$w_cliente,f($RS1,'solicitante'),$TP,f($RS1,'nm_sol_comp')).'</td></tr>';
    } 
    $l_html.=chr(13).'   <tr><td><b>Fase Atual da Ação:</b></td>';
    $l_html.=chr(13).'       <td>'.Nvl(f($RS1,'nm_tramite'),'-').'</td></tr>';
    // Listagem das metas da ação
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>METAS FÍSICAS<hr NOSHADE color=#000000 SIZE=1></b></td></tr>';
    $RS2 = db_getSolicMeta_IS::getInstanceOf($dbms,$w_cliente,$l_chave,null,'LSTNULL',null,null,null,null,null,null,null);
    $RS2 = SortArray($RS2,'ordem','asc');
    if (count($RS2)>0) {
      $l_cont=1;
      foreach ($RS2 as $row2) {
        $RS3 = db_getSolicMeta_IS::getInstanceOf($dbms,$w_cliente,$l_chave,f($row2,'sq_meta'),'REGISTRO',null,null,null,null,null,null,null);
        foreach ($RS3 as $row3){$RS3=$row3; break;}
        $l_html.=chr(13).'   <tr><td valigin="top" bgcolor="#f0f0f0"><b>'.$l_cont.') Meta:</b></td>';
        if (Nvl(f($RS3,'descricao_subacao'),'')>'') $l_html.=chr(13).'       <td bgcolor="#f0f0f0"><b>'.f($row2,'titulo').'('.f($RS3,'descricao_subacao').')</b></td></tr>';
        else                                        $l_html.=chr(13).'       <td bgcolor="#f0f0f0"><b>'.f($row2,'titulo').'</b></td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Descrição da Meta:</b></td>';
        $l_html.=chr(13).'       <td>'.f($row2,'descricao').'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Quantitativo Programado:</b></td>';
        $l_html.=chr(13).'       <td>'.(Nvl(f($row2,'quantidade'),0)).'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Unidade Medida:</b></td>';
        $l_html.=chr(13).'       <td>'.f($row2,'unidade_medida').'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Meta Cumulativa:</b></td>';
        if (f($row2,'cumulativa')=='N') $l_html.=chr(13).'       <td>Não</td></tr>';
        else                           $l_html.=chr(13).'       <td>Sim</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Meta PPA:</b></td>';
        if (Nvl(f($row2,'cd_subacao'),'')>'')    $l_html.=chr(13).'       <td>Sim</td></tr>';
        else                                    $l_html.=chr(13).'       <td>Não</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Setor Responsável pela Meta:</b></td>';
        $l_html.=chr(13).'       <td>'.FormataDataEdicao(f($row2,'sg_setor')).'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Previsão Inicio:</b></td>';
        $l_html.=chr(13).'       <td>'.FormataDataEdicao(f($row2,'inicio_previsto')).'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Previsão Término:</b></td>';
        $l_html.=chr(13).'       <td>'.FormataDataEdicao(f($row2,'fim_previsto')).'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Percentual de Conclusão:</b></td>';
        $l_html.=chr(13).'       <td>'.Nvl(f($row2,'perc_conclusao'),0).'%</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Situação atual da meta:</b></td>';
        $l_html.=chr(13).'       <td>'.Nvl(f($row2,'situacao_atual'),'-').'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>A meta será cumprida:</b></td>';
        if (f($row2,'exequivel')=='N') {
          $l_html.=chr(13).'       <td>Não</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>Justificativa para o não cumprimento da meta:</b></td>';
          $l_html.=chr(13).'       <td>'.Nvl(f($row2,'justificativa_inexequivel'),'-').'</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>Medidas necessárias para realização da meta:</b></td>';
          $l_html.=chr(13).'       <td>'.Nvl(f($row2,'outras_medidas'),'-').'</td></tr>';
        } else {
          $l_html.=chr(13).'       <td>Sim</td></tr>';
        } 
        $l_html.=chr(13).'   <tr><td><b>Criação/Última Atualização:</b></td>';
        $l_html.=chr(13).'       <td>'.FormataDataEdicao(f($row2,'phpdt_ultima_atualizacao'),3).'</td></tr>';
        $l_cont=$l_cont+1;
      } 
    } else {
      $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">Nenhuma meta cadastrada para esta ação</div></td></tr>';
    } 
    // Listagem das restrições da ação
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>RESTRIÇÕES<hr NOSHADE color=#000000 SIZE=1></b></td></tr>';
    $RS2 = db_getRestricao_IS::getInstanceOf($dbms,'ISACRESTR',$l_chave,null);
    $RS2 = SortArray($RS2,'inclusao','desc');
    if (count($RS2)>0) {
      $l_cont=1;
      foreach ($RS2 as $row2) {
        $l_html.=chr(13).'   <tr><td valigin="top" bgcolor="#f0f0f0"><b>'.$l_cont.') Tipo:</b></td>';
        $l_html.=chr(13).'       <td bgcolor="#f0f0f0"><b>'.f($row2,'nm_tp_restricao').'</b></td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Descrição:</b></td>';
        $l_html.=chr(13).'       <td>'.f($row2,'descricao').'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Providência:</b></td>';
        $l_html.=chr(13).'       <td>'.Nvl(f($row2,'providencia'),'-').'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Data de Inclusão:</b></td>';
        $l_html.=chr(13).'       <td>'.FormataDataEdicao(f($row2,'phpdt_inclusao'),3).'</td></tr>';
        $l_cont+=1;
      } 
    } else {
      $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">Nenhuma restrição cadastrada</div></td></tr>';
    } 
    // Listagem das tarefa na visualização da ação, rotina adquirida apartir da rotina exitente na tarefa.php para listagem das tarefa     
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>TAREFAS<hr NOSHADE color=#000000 SIZE=1></b></td></tr>';
    $RS2 = db_getLinkData::getInstanceOf($dbms,RetornaCliente(),'ISTCAD');
    $RS2 = db_getSolicList_IS::getInstanceOf($dbms,f($RS2,'sq_menu'),RetornaUsuario(),'ISTCAD',4,
             null,null,null,null,null,null,
             null,null,null,null,
             null,null,null,null,null,null,null,
             null,null,null,null,$l_chave,null,null,null,null,null,$w_ano);
    $RS2 = SortArray($RS2,'ordem','asc','fim','asc','prioridade','asc');
    if (count($RS2)>0) {
      $l_html.=chr(13).'   <tr><td colspan="2"><div align="center">';
      $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
      $l_html.=chr(13).'   <tr><td width="8%" bgColor="#f0f0f0"><div align="center"><b>Código</b></div></td>';
      $l_html.=chr(13).'       <td bgColor="#f0f0f0"><div align="center"><b>Tarefa</b></div></td>';
      $l_html.=chr(13).'       <td width="12%" bgColor="#f0f0f0"><div align="center"><b>Responsável</b></div></td>';
      $l_html.=chr(13).'       <td width="10%" bgColor="#f0f0f0"><div align="center"><b>Início</b></div></td>';
      $l_html.=chr(13).'       <td width="10%" bgColor="#f0f0f0"><div align="center"><b>Fim</b></div></td>';
      $l_html.=chr(13).'       <td width="12%" bgColor="#f0f0f0"><div align="center"><b>Valor (R$)</b></div></td>';
      $l_html.=chr(13).'       <td width="13%" bgColor="#f0f0f0"><div align="center"><b>Fase Atual</b></div></td></tr>';
      foreach ($RS2 as $row2) {
        $l_html.=chr(13).'   <tr><td nowrap><b>';
        if (f($row2,'concluida')=='N') {
          if (f($row2,'fim')<addDays(time(),-1)) {
            $l_html.=chr(13).'          <img src="'.$conImgAtraso.'" border=0 width=14 heigth=14 align="center">';
          } elseif (f($row2,'aviso_prox_conc')=='S' && (f($row2,'aviso')<=addDays(time(),-1))) {
            $l_html.=chr(13).'          <img src="'.$conImgAviso.'" border=0 width=14 height=14 align="center">';
          } elseif (f($row2,'sg_tramite')=='CA') {
            $l_html.=chr(13).'          <img src="'.$conImgCancel.'" border=0 width=14 height=14 align="center">';
          } else {
            $l_html.=chr(13).'          <img src="'.$conImgNormal.'" border=0 width=14 height=14 align="center">';
          } 
        } else {
          if (f($row2,'fim')<Nvl(f($row2,'fim_real'),f($row2,'fim'))) {
            $l_html.=chr(13).'          <img src="'.$conImgOkAtraso.'" border=0 width=14 heigth=14 align="center">';
          } else {
            $l_html.=chr(13).'          <img src="'.$conImgOkNormal.'" border=0 width=14 height=14 align="center">';
          }
        } 
        $l_html.=chr(13).'    <A class="HL" HREF="'.$w_dir.'tarefas.php?par=Visual&R='.$l_pagina.$par.'&O=L&w_chave='.f($row2,'sq_siw_solicitacao').'&w_tipo=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro." target="_blank">'.f($row2,'sq_siw_solicitacao').'&nbsp;</a>';
        $l_html.=chr(13).'    <td>'.Nvl(f($row2,'titulo'),'-').'</td>';
        $l_html.=chr(13).'    <td>'.ExibePessoa('../',$w_cliente,f($row2,'solicitante'),$TP,f($row2,'nm_solic')).'</td>';
        $l_html.=chr(13).'    <td><div align="center">'.FormataDataEdicao(f($row2,'inicio')).'</div></td>';
        $l_html.=chr(13).'    <td><div align="center">'.FormataDataEdicao(f($row2,'fim')).'</div></td>';
        $l_html.=chr(13).'    <td><div align="right">'.number_format($cDbl[Nvl(f($row2,'valor'),0)],2,',','.').'</div></td>';
        $l_html.=chr(13).'    <td>'.f($row2,'nm_tramite').'</td>';
      } 
      $l_html.=chr(13).'         </table></div></td></tr>';
    } else {
      $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">Nenhuma tarefa cadastrada</div></td></tr>';
    } 
    //Encaminhamentos
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
      $w_html .= chr(13).'      <tr bgcolor="'.$conTrBgColor.'"><td colspan=4 align="center"><b>Não foram encontrados encaminhamentos.</b></td></tr>';
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
        $l_html.=chr(13).'        <td>'.CRLF2BR(Nvl(f($row1,'despacho'),'---')).'</td>';
        $l_html.=chr(13).'        <td nowrap>'.ExibePessoa('../',$w_cliente,f($row1,'sq_pessoa'),$TP,f($row1,'responsavel')).'</td>';
        if ((Nvl(f($row1,'sq_projeto_log'),'')>'') && (Nvl(f($row1,'destinatario'),'')>''))         $l_html.=chr(13).'        <td nowrap>'.ExibePessoa('../',$w_cliente,f($row1,'sq_pessoa_destinatario'),$TP,f($row1,'destinatario')).'</td>';
        elseif ((Nvl(f($row1,'sq_projeto_log'),'')>'')  && (Nvl(f($row1,'destinatario'),'')==''))   $l_html.=chr(13).'        <td nowrap>Anotação</td>';
        else $l_html.=chr(13).'        <td nowrap>'.Nvl(f($row1,'tramite'),'---').'</td>';
        $l_html.=chr(13).'      </tr>';
      } 
      $l_html.=chr(13).'         </table></div></td></tr>';
    } 
    $l_html.=chr(13).'</table>';
  } else {
    $l_html.=chr(13).'      <tr><td  colspan="2"><br><hr NOSHADE color=#000000 size=4></td></tr>';
    if (Nvl(f($RS1,'cd_acao'),'')>'')   $l_html.=chr(13).'   <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>AÇÃO: '.f($RS1,'cd_acao').' - '.f($RS1,'nm_ppa').'</b></div></td></tr>';
    else                                $l_html.=chr(13).'   <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>AÇÃO: '.f($RS1,'titulo').'</b></div></td></tr>';
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    // Identificação da ação
    if (upper($l_identificacao)==upper('sim')) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>IDENTIFICAÇÃO DA AÇÃO<hr NOSHADE color=#000000 SIZE=1></b></td></tr>';
      // Se a ação no PPA for informada, exibe.
      if (Nvl(f($RS1,'cd_acao'),'')>'') {
        $l_html.=chr(13).'   <tr><td width="30%"><b>Programa:</b></td>';
        $l_html.=chr(13).'       <td><div align="justify"><b>'.f($RS1,'cd_ppa_pai').' - '.f($RS1,'nm_ppa_pai').'</b></div></td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Ação:</b></td>';
        $l_html.=chr(13).'       <td><div align="justify">'.f($RS1,'cd_acao').' - '.f($RS1,'nm_ppa').'</div></td></tr>';
      }
      // Se o programa interno for informado, exibe.
      if (Nvl(f($RS1,'sq_isprojeto'),'')>'') {
        $l_html.=chr(13).'   <tr><td width="30%"><b>Programa Interno:</b></td>';
        $l_html.=chr(13).'       <td><div align="justify"><b>'.f($RS1,'nm_pri').'</b></div></td></tr>';
        if (Nvl(f($RS1,'cd_acao'),'')>'') {
          $l_html.=chr(13).'   <tr><td><b>Recurso Programado '.$w_ano.':</b></td>';
          $l_html.=chr(13).'       <td>R$ '.number_format(f($RS1,'valor'),2,',','.').'</td></tr>';
        }
      } 
      if (Nvl(f($RS1,'cd_acao'),'')>'') {
        $l_html.=chr(13).'   <tr><td><b>Orgão:</b></td>';
        $l_html.=chr(13).'       <td>'.f($RS1,'nm_orgao').'</td></tr>';
      } 
      if ($P4==1) {
        $l_html.=chr(13).'   <tr><td><b>Unidade Administrativa:</b></td>';
        $l_html.=chr(13).'       <td>'.f($RS1,'nm_unidade_adm').'</td></tr>';
      } else {
        $l_html.=chr(13).'   <tr><td><b>Unidade Administrativa:</b></td>';
        $l_html.=chr(13).'       <td>'.ExibeUnidade('../',$w_cliente,f($RS1,'nm_unidade_adm'),f($RS1,'sq_unidade_adm'),$TP).'</td></tr>';
      }
      if (Nvl(f($RS1,'cd_acao'),'')>'') {
        $l_html.=chr(13).'   <tr><td><b>Unidade Orçamentária:</b></td>';
        $l_html.=chr(13).'       <td>'.f($RS1,'cd_unidade').' - '.f($RS1,'ds_unidade').'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Recurso Programado '.$w_ano.':</b></td>';
        $l_html.=chr(13).'       <td>R$ '.number_format(f($RS1,'valor'),2,',','.').'</td></tr>';
      } 
      if (f($RS1,'mpog_ppa')=='S') {
        $l_html.=chr(13).'   <tr><td><b>Selecionada SPI/MP:</b></td>';
        $l_html.=chr(13).'       <td>Sim</td></tr>';
      } else {
        $l_html.=chr(13).'   <tr><td><b>Selecionada SPI/MP:</b></td>';
        $l_html.=chr(13).'       <td>Não</td></tr>';
      } 
      if (f($RS1,'relev_ppa')=='S') {
        $l_html.=chr(13).'   <tr><td><b>Selecionada SE/SEPPIR:</b></td>';
        $l_html.=chr(13).'       <td>Sim</td></tr>';
      } else {
        $l_html.=chr(13).'   <tr><td><b>Selecionada SE/SEPPIR:</b></td>';
        $l_html.=chr(13).'       <td>Não</td></tr>';
      } 
      if ($P4==1) {
        $l_html.=chr(13).'   <tr><td><b>Área Planejamento:</b></td>';
        $l_html.=chr(13).'       <td>'.f($RS1,'nm_unidade_resp').'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Responsável Monitoramento:</b></td>';
        $l_html.=chr(13).'       <td>'.f($RS1,'nm_sol').'</td></tr>';
      } else {
        $l_html.=chr(13).'   <tr><td><b>Área Planejamento:</b></td>';
        $l_html.=chr(13).'       <td>'.ExibeUnidade('../',$w_cliente,f($RS1,'nm_unidade_resp'),f($RS1,'sq_unidade'),$TP).'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Responsável Monitoramento:</b></td>';
        $l_html.=chr(13).'       <td>'.ExibePessoa('../',$w_cliente,f($RS1,'solicitante'),$TP,f($RS1,'nm_sol_comp')).'</td></tr>';
      } 
      if (Nvl(f($RS1,'cd_acao'),'')>'') {
        $RS2 = db_getAcaoPPA_IS::getInstanceOf($dbms,$w_cliente,$w_ano,f($RS1,'cd_ppa_pai'),f($RS1,'cd_acao'),null,f($RS1,'cd_unidade'),null,null,null,null,null);
        foreach ($RS2 as $row2) {$RS2=$row2; break;}
        $l_html.=chr(13).'   <tr><td><b>Função:</b></td>';
        $l_html.=chr(13).'       <td>'.f($RS2,'ds_funcao').'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Sub-função:</b></td>';
        $l_html.=chr(13).'       <td>'.f($RS2,'ds_subfuncao').'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Esfera:</b></td>';
        $l_html.=chr(13).'       <td>'.f($RS2,'ds_esfera').'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Tipo de Ação:</b></td>';
        $l_html.=chr(13).'       <td>'.f($RS2,'nm_tipo_acao').'</td></tr>';
      }
      $l_html.=chr(13).'   <tr><td><b>Parcerias Externas:</b></td>';
      $l_html.=chr(13).'       <td>'.CRLF2BR(Nvl(f($RS1,'proponente'),'-')).'</td></tr>';
      $l_html.=chr(13).'   <tr><td><b>Parcerias Internas:</b></td>';
      $l_html.=chr(13).'       <td>'.CRLF2BR(Nvl(f($RS1,'palavra_chave'),'-')).'</td></tr>';
      $l_html.=chr(13).'   <tr><td><b>Fase Atual da Ação:</b></td>';
      $l_html.=chr(13).'       <td>'.Nvl(f($RS1,'nm_tramite'),'-').'</td></tr>';
    } 
    // Responsaveis
    if (upper($l_responsavel)==upper('sim')) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>RESPONSÁVEIS<hr NOSHADE color=#000000 SIZE=1></b></td></tr>';
      if (f($RS1,'nm_gerente_programa')>'' || f($RS1,'nm_gerente_executivo')>'' || f($RS1,'nm_gerente_adjunto')>'' || f($RS1,'resp_ppa')>'' || f($RS1,'resp_pri')>'') {
        if (Nvl(f($RS1,'nm_gerente_programa'),'')>'') {
          $l_html.=chr(13).'   <tr><td><b>Gerente do Programa:</b></td>';
          $l_html.=chr(13).'       <td>'.f($RS1,'nm_gerente_programa').'</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>Telefone:</b></td>';
          $l_html.=chr(13).'       <td>'.Nvl(f($RS1,'fn_gerente_programa'),'-').'</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>E-mail:</b></td>';
          $l_html.=chr(13).'       <td>'.Nvl(f($RS1,'em_gerente_programa'),'-').'</td></tr>';
        }
        if (Nvl(f($RS1,'nm_gerente_executivo'),'')>'') {
          $l_html.=chr(13).'   <tr><td><b>Gerente Executivo do Programa:</b></td>';
          $l_html.=chr(13).'       <td>'.f($RS1,'nm_gerente_executivo').'</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>Telefone:</b></td>';
          $l_html.=chr(13).'       <td>'.Nvl(f($RS1,'fn_gerente_executivo'),'-').'</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>E-mail:</b></td>';
          $l_html.=chr(13).'       <td>'.Nvl(f($RS1,'em_gerente_executivo'),'-').'</td></tr>';
        } 
        if (Nvl(f($RS1,'nm_gerente_adjunto'),'')>'') {
          $l_html.=chr(13).'   <tr><td><b>Gerente Executivo Adjunto:</b></td>';
          $l_html.=chr(13).'       <td>'.f($RS1,'nm_gerente_adjunto').'</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>Telefone:</b></td>';
          $l_html.=chr(13).'       <td>'.Nvl(f($RS1,'fn_gerente_adjunto'),'-').'</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>E-mail:</b></td>';
          $l_html.=chr(13).'       <td>'.Nvl(f($RS1,'em_gerente_adjunto'),'-').'</td></tr>';
        } 
        if (Nvl(f($RS1,'resp_ppa'),'')>'') {
          $l_html.=chr(13).'   <tr><td><b>Coordenador:</b></td>';
          $l_html.=chr(13).'       <td>'.f($RS1,'resp_ppa').'</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>Telefone:</b></td>';
          $l_html.=chr(13).'       <td>'.Nvl(f($RS1,'fone_ppa'),'-').'</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>E-mail:</b></td>';
          $l_html.=chr(13).'       <td>'.Nvl(f($RS1,'mail_ppa'),'-').'</td></tr>';
        } 
        if (Nvl(f($RS1,'resp_pri'),'')>'') {
          $l_html.=chr(13).'   <tr><td><b>Responsável pela Ação:</b></td>';
          $l_html.=chr(13).'       <td>'.f($RS1,'resp_pri').'</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>Telefone:</b></td>';
          $l_html.=chr(13).'       <td>'.Nvl(f($RS1,'fone_pri'),'-').'</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>E-mail:</b></td>';
          $l_html.=chr(13).'       <td>'.Nvl(f($RS1,'mail_pri'),'-').'</td></tr>';
        } 
      } else {
        $l_html.=chr(13).'   <tr><td colspan="2"><div align="center">Nenhum responsável cadastrado</div></td>';
      } 
    } 
    // Dados da conclusão da ação, se ela estiver nessa situação
    if (upper($l_conclusao)==upper('sim')) {
      if (f($RS1,'concluida')=='S' && Nvl(f($RS1,'data_conclusao'),'')>'') {
        $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS DA CONCLUSÃO DA AÇÃO<hr NOSHADE color=#000000 SIZE=1></b></td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Recurso Executado:</b></td>';
        $l_html.=chr(13).'       <td>'.number_format(f($RS1,'custo_real'),2,',','.').'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Nota de Conclusão:</b></td>';
        $l_html.=chr(13).'       <td><div align="justify">'.CRLF2BR(f($RS1,'nota_conclusao')).'</div></td></tr>';
      } 
    } 
    // Programação Qualitativa
    if (upper($l_qualitativa)==upper('sim')) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>PROGRAMAÇÃO QUALITATIVA<hr NOSHADE color=#000000 SIZE=1></b></td></tr>';
      if (Nvl(f($RS1,'cd_acao'),'')>'') {
        $l_html.=chr(13).'   <tr><td valign="top"><b>Descrição da Ação:</b></td>';
        $l_html.=chr(13).'       <td><div align="justify">'.Nvl(f($RS1,'descricao_ppa'),'-').'</div></td></tr>';
      }
      $l_html.=chr(13).'   <tr><td valign="top"><b>Justificativa:</b></td>';
      $l_html.=chr(13).'       <td><div align="justify">'.Nvl(f($RS1,'problema'),'-').'</div></td></tr>';
      $l_html.=chr(13).'   <tr><td valign="top"><b>Objetivo Específico:</b></td>';
      $l_html.=chr(13).'       <td><div align="justify">'.Nvl(f($RS1,'objetivo'),'-').'</div></td></tr>';
      $l_html.=chr(13).'   <tr><td valign="top"><b>Público Alvo:</b></td>';
      $l_html.=chr(13).'       <td><div align="justify">'.Nvl(f($RS1,'publico_alvo'),'-').'</div></td></tr>';
      if (Nvl(f($RS1,'cd_acao'),'')>'') {
        $l_html.=chr(13).'   <tr><td valign="top"><b>Base Legal:</b></td>';
        $l_html.=chr(13).'       <td><div align="justify">'.Nvl(f($RS1,'base_legal'),'-').'</div></td></tr>';
        $l_html.=chr(13).'   <tr><td valign="top"><b>Forma de Implementação:</b></td>';
        $l_html.=chr(13).'       <td><div align="justify">';
        if (f($RS1,'cd_tipo_acao')==1 || f($RS1,'cd_tipo_acao')==2) {
          if (f($RS1,'direta')=='S')                $l_html.=chr(13).' direta';
          elseif (f($RS1,'descentralizada')=='S')   $l_html.=chr(13).' descentralizada';
          elseif (f($RS1,'linha_credito')=='S')     $l_html.=chr(13).' linha de crédito';
        } elseif (f($RS1,'cd_tipo_acao')==4) {
          if (f($RS1,'transf_obrigatoria')=='S')    $l_html.=chr(13).' transferência obrigatória';
          elseif (f($RS1,'transf_voluntaria')=='S') $l_html.=chr(13).' transferência voluntária';
          elseif (f($RS1,'transf_outras')=='S')     $l_html.=chr(13).' outras';
        }
        $l_html.=chr(13).'</div></td></tr>';
        $l_html.=chr(13).'   <tr><td valign="top"><b>Detalhamento da Implementação:</b></td>';
        $l_html.=chr(13).'       <td><div align="justify">'.Nvl(f($RS1,'detalhamento'),'-').'</div></td></tr>';
      } 
      $l_html.=chr(13).'   <tr><td valign="top"><b>Sistemática e Estratégias a serem Adotadas para o Monitoramento da Ação:</b></td>';
      $l_html.=chr(13).'       <td><div align="justify">'.Nvl(f($RS1,'estrategia'),'-').'</div></td></tr>';
      $l_html.=chr(13).'   <tr><td valign="top"><b>Sistemática e Metodologias a serem Adotadas para Avaliação da Ação:</b></td>';
      $l_html.=chr(13).'       <td><div align="justify">'.Nvl(f($RS1,'sistematica'),'-').'</div></td></tr>';
      $l_html.=chr(13).'   <tr><td valign="top"><b>Observações:</b></td>';
      $l_html.=chr(13).'       <td><div align="justify">'.Nvl(f($RS1,'justificativa'),'-').'</div></td></tr>';
    } 
    // Programação orçamentaria
    if (upper($l_orcamentaria)==upper('sim')){
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>PROGRAMAÇÃO ORÇAMENTÁRIA<hr NOSHADE color=#000000 SIZE=1></b></td></tr>';
      if (Nvl(f($RS1,'cd_acao'),'')>'') {
        if (f($RS1,'cd_tipo_acao')!=3) {
          $RS2 = db_getPPADadoFinanc_IS::getInstanceOf($dbms,f($RS1,'cd_acao'),f($RS1,'cd_unidade'),$w_ano,$w_cliente,'VALORTOTALMENSAL');
          foreach ($RS2 as $row2){$RS2=$row2; break;}
          if (count($RS2)>0) {
            $l_html.=chr(13).'   <tr><td colspan="2"><div align="center">';
            $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
            $l_html.=chr(13).'       <tr><td bgColor="#f0f0f0"><div align="center"><b>Ano</b></div></td>';
            $l_html.=chr(13).'           <td bgColor="#f0f0f0"><div align="center"><b>Valor Aprovado</b></div></td>';
            $l_html.=chr(13).'           <td bgColor="#f0f0f0"><div align="center"><b>Valor Autorizado</b></div></td>';
            $l_html.=chr(13).'           <td bgColor="#f0f0f0"><div align="center"><b>Valor Realizado</b></div></td>';
            $l_html.=chr(13).'       <tr><td><div align="right"><b>'.$w_ano.'</b></div></td>';
            $l_html.=chr(13).'           <td><div align="right">'.number_format(Nvl(f($RS2,'previsao_ano'),0),2,',','.').'</div></td>';
            $l_html.=chr(13).'           <td><div align="right">'.number_format(Nvl(f($RS2,'atual_ano'),0),2,',','.').'</div></td>';
            $l_html.=chr(13).'           <td><div align="right">'.number_format(Nvl(f($RS2,'real_ano'),0),2,',','.').'</div></td>';
            $l_html.=chr(13).'       <tr><td width="10%" bgColor="#f0f0f0"><div align="center"><b>Mês</b></div></td>';
            $l_html.=chr(13).'           <td width="30%" bgColor="#f0f0f0"><div align="center"><b>Inicial</b></div></td>';
            $l_html.=chr(13).'           <td width="30%" bgColor="#f0f0f0"><div align="center"><b>Revisado</b></div></td>';
            $l_html.=chr(13).'           <td width="30%" bgColor="#f0f0f0"><div align="center"><b>Realizado</b></div></td></tr>';
            $l_html.=chr(13).'       <tr><td width="10%" align="right"><b>Janeiro:';
            $l_html.=chr(13).'           <td align="right" width="30%">'.number_format(Nvl(f($RS2,'cron_ini_mes_1'),0),2,',','.').'</td>';
            $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'cron_mes_1'),0),2,',','.').'</td>';
            $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'real_mes_1'),0),2,',','.').'</td>';
            $l_html.=chr(13).'       <tr><td align="right"><b>Fevereiro:';
            $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'cron_ini_mes_2'),0),2,',','.').'</td>';
            $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'cron_mes_2'),0),2,',','.').'</td>';
            $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'real_mes_2'),0),2,',','.').'</td>';
            $l_html.=chr(13).'       <tr><td align="right"><b>Março:';
            $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'cron_ini_mes_3'),0),2,',','.').'</td>';
            $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'cron_mes_3'),0),2,',','.').'</td>';
            $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'real_mes_3'),0),2,',','.').'</td>';
            $l_html.=chr(13).'       <tr><td align="right"><b>Abril:';
            $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'cron_ini_mes_4'),0),2,',','.').'</td>';
            $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'cron_mes_4'),0),2,',','.').'</td>';
            $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'real_mes_4'),0),2,',','.').'</td>';
            $l_html.=chr(13).'       <tr><td align="right"><b>Maio:';
            $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'cron_ini_mes_5'),0),2,',','.').'</td>';
            $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'cron_mes_5'),0),2,',','.').'</td>';
            $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'real_mes_5'),0),2,',','.').'</td>';
            $l_html.=chr(13).'       <tr><td align="right"><b>Junho:';
            $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'cron_ini_mes_6'),0),2,',','.').'</td>';
            $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'cron_mes_6'),0),2,',','.').'</td>';
            $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'real_mes_6'),0),2,',','.').'</td>';
            $l_html.=chr(13).'       <tr><td align="right"><b>Julho:';
            $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'cron_ini_mes_7'),0),2,',','.').'</td>';
            $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'cron_mes_7'),0),2,',','.').'</td>';
            $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'real_mes_7'),0),2,',','.').'</td>';
            $l_html.=chr(13).'       <tr><td align="right"><b>Agosto:';
            $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'cron_ini_mes_8'),0),2,',','.').'</td>';
            $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'cron_mes_8'),0),2,',','.').'</td>';
            $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'real_mes_8'),0),2,',','.').'</td>';
            $l_html.=chr(13).'       <tr><td align="right"><b>Setembro:';
            $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'cron_ini_mes_9'),0),2,',','.').'</td>';
            $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'cron_mes_9'),0),2,',','.').'</td>';
            $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'real_mes_9'),0),2,',','.').'</td>';
            $l_html.=chr(13).'       <tr><td align="right"><b>Outubro:';
            $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'cron_ini_mes_10'),0),2,',','.').'</td>';
            $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'cron_mes_10'),0),2,',','.').'</td>';
            $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'real_mes_10'),0),2,',','.').'</td>';
            $l_html.=chr(13).'       <tr><td align="right"><b>Novembro:';
            $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'cron_ini_mes_11'),0),2,',','.').'</td>';
            $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'cron_mes_11'),0),2,',','.').'</td>';
            $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'real_mes_11'),0),2,',','.').'</td>';
            $l_html.=chr(13).'       <tr><td align="right"><b>Dezembro:';
            $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'cron_ini_mes_12'),0),2,',','.').'</td>';
            $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'cron_mes_12'),0),2,',','.').'</td>';
            $l_html.=chr(13).'           <td align="right">'.number_format(Nvl(f($RS2,'real_mes_12'),0),2,',','.').'</td>';
            $l_html.=chr(13).'       <tr><td align="right"><b>Total:';
            $l_html.=chr(13).'           <td align="right"><b>'.number_format(Nvl(f($RS2,'cron_ini_total'),0),2,',','.').'</b></td>';
            $l_html.=chr(13).'           <td align="right"><b>'.number_format(Nvl(f($RS2,'cron_mes_total'),0),2,',','.').'</b></td>';
            $l_html.=chr(13).'           <td align="right"><b>'.number_format(Nvl(f($RS2,'real_mes_total'),0),2,',','.').'</b></td>';
            $l_html.=chr(13).'     </table></div></td></tr>';
          } 
          $l_cont=1;
          $RS2 = db_getPPADadoFinanc_IS::getInstanceOf($dbms,f($RS1,'cd_acao'),f($RS1,'cd_unidade'),$w_ano,$w_cliente,'VALORFONTEACAO');
          if (count($RS2)<=0) {
            $l_html.=chr(13).'   <tr><td colspan="2">Nao existe nenhum valor para esta ação</td></tr>';
          } else {
            if (f($RS1,'cd_tipo_acao')==1) {
              $l_html.=chr(13).'   <tr><td><b>Realizado até '.($w_ano-1).':</b></td>';
              $l_html.=chr(13).'       <td>'.number_format(Nvl(f($RS1,'valor_ano_anterior'),0),2,',','.').'</td></tr>';
              $l_html.=chr(13).'   <tr><td><b>Justificativa da Repercusão Financeira sobre o Custeio da União:</b></td>';
              $l_html.=chr(13).'       <td>'.Nvl(f($RS1,'reperc_financeira'),'-').'</td></tr>';
              $l_html.=chr(13).'   <tr><td><b>Valor Estimado da Repercussão Financeira por Ano (R$ 1,00):</b></td>';
              $l_html.=chr(13).'       <td>'.number_format(Nvl(f($RS1,'valor_reperc_financeira'),0),2,',','.').'</td></tr>';
            } 
            $l_html.=chr(13).'   <tr><td colspan="2" valigin="top" bgcolor="#f0f0f0"><b>Valor por Fonte:</b></td>';
            foreach ($RS2 as $row2) {
              $l_html.=chr(13).'   <tr><td valigin="top" bgcolor="#f0f0f0"><b>'.$l_cont.') Fonte:</b></td>';
              $l_html.=chr(13).'       <td bgcolor="#f0f0f0"><b>'.f($row2,'nm_fonte').'</b></td></tr>';
              $l_html.=chr(13).'   <tr><td colspan="2"><div align="center">';
              $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
              $l_html.=chr(13).'       <tr><td bgColor="#f0f0f0"><div align="center"><b>2004</b></div></td>';
              $l_html.=chr(13).'           <td bgColor="#f0f0f0"><div align="center"><b>2005</b></div></td>';
              $l_html.=chr(13).'           <td bgColor="#f0f0f0"><div align="center"><b>2006</b></div></td>';
              $l_html.=chr(13).'           <td bgColor="#f0f0f0"><div align="center"><b>2007</b></div></td>';
              $l_html.=chr(13).'           <td bgColor="#f0f0f0"><div align="center"><b>2008</b></div></td>';
              $l_html.=chr(13).'           <td bgColor="#f0f0f0"><div align="center"><b>Total 2004-2008</b></div></td></tr>';
              $l_html.=chr(13).'       <tr><td><div align="right">'.number_format(Nvl(f($row2,'valor_ano_1'),0),2,',','.').'</div></td>';
              $l_html.=chr(13).'           <td><div align="right">'.number_format(Nvl(f($row2,'valor_ano_2'),0),2,',','.').'</div></td>';
              $l_html.=chr(13).'           <td><div align="right">'.number_format(Nvl(f($row2,'valor_ano_3'),0),2,',','.').'</div></td>';
              $l_html.=chr(13).'           <td><div align="right">'.number_format(Nvl(f($row2,'valor_ano_4'),0),2,',','.').'</div></td>';
              $l_html.=chr(13).'           <td><div align="right">'.number_format(Nvl(f($row2,'valor_ano_5'),0),2,',','.').'</div></td>';
              $l_html.=chr(13).'           <td><div align="right">'.number_format(Nvl(f($row2,'valor_total'),0),2,',','.').'</div></td></tr>';
              $l_html.=chr(13).'     </table></div></td></tr>';
              $l_cont=$l_cont+1;
            } 
            $l_html.=chr(13).'   <tr><td colspan="2">Fonte dos Dados: SIGPLAN/MP</td></tr>';
          } 
        } else {
          $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">Não existe programação financeira para esta ação, pois esta é uma ação não orçamentária</div></td></tr>';
        } 
      } else {
        $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">Não existe programação financeira para esta ação, pois esta é uma ação não orçamentária</div></td></tr>';
      } 
    } 
    // Listagem das metas da ação
    if (upper($l_meta)==upper('sim')) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>METAS FÍSICAS<hr NOSHADE color=#000000 SIZE=1></b></td></tr>';
      $RS2 = db_getSolicMeta_IS::getInstanceOf($dbms,$w_cliente,$l_chave,null,'LSTNULL',null,null,null,null,null,null,null);
      $RS2 = SortArray($RS2,'ordem','asc');
      if (count($RS2)>0) {
        $l_cont=1;
        foreach ($RS2 as $row2) {
          $RS3 = db_getSolicMeta_IS::getInstanceOf($dbms,$w_cliente,$l_chave,f($row2,'sq_meta'),'REGISTRO',null,null,null,null,null,null,null);
          foreach ($RS3 as $row3){$RS3=$row3; break;}
          $l_html.=chr(13).'   <tr><td valigin="top" bgcolor="#f0f0f0"><b>'.$l_cont.') Meta:</b></td>';
          if (Nvl(f($RS3,'descricao_subacao'),'')>'')    $l_html.=chr(13).'       <td bgcolor="#f0f0f0"><b>'.f($row2,'titulo').'('.f($RS3,'descricao_subacao').')</b></td></tr>';
          else                                           $l_html.=chr(13).'       <td bgcolor="#f0f0f0"><b>'.f($row2,'titulo').'</b></td></tr>';
          $l_html.=chr(13).'   <tr><td><b>Descrição da Meta:</b></td>';
          $l_html.=chr(13).'       <td>'.f($row2,'descricao').'</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>Quantitativo Programado:</b></td>';
          $l_html.=chr(13).'       <td>'.(Nvl(f($row2,'quantidade'),0)).'</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>Unidade Medida:</b></td>';
          $l_html.=chr(13).'       <td>'.f($row2,'unidade_medida').'</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>Meta Cumulativa:</b></td>';
          if (f($row2,'cumulativa')=='N')$l_html.=chr(13).'       <td>Não</td></tr>';
          else                          $l_html.=chr(13).'       <td>Sim</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>Meta PPA:</b></td>';
          if (Nvl(f($row2,'cd_subacao'),'')>'')  $l_html.=chr(13).'       <td>Sim</td></tr>';
          else                                   $l_html.=chr(13).'       <td>Não</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>Setor Responsável pela Meta:</b></td>';
          $l_html.=chr(13).'       <td>'.FormataDataEdicao(f($row2,'sg_setor')).'</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>Previsão Inicio:</b></td>';
          $l_html.=chr(13).'       <td>'.FormataDataEdicao(f($row2,'inicio_previsto')).'</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>Previsão Término:</b></td>';
          $l_html.=chr(13).'       <td>'.FormataDataEdicao(f($row2,'fim_previsto')).'</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>Percentual de Conclusão:</b></td>';
          $l_html.=chr(13).'       <td>'.Nvl(f($row2,'perc_conclusao'),0).'%</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>Situação atual da meta:</b></td>';
          $l_html.=chr(13).'       <td>'.Nvl(f($row2,'situacao_atual'),'-').'</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>A meta será cumprida:</b></td>';
          if (f($row2,'exequivel')=='N') {
            $l_html.=chr(13).'       <td>Não</td></tr>';
            $l_html.=chr(13).'   <tr><td><b>Justificativa para o não cumprimento da meta:</b></td>';
            $l_html.=chr(13).'       <td>'.Nvl(f($row2,'justificativa_inexequivel'),'-').'</td></tr>';
            $l_html.=chr(13).'   <tr><td><b>Medidas necessárias para realização da meta:</b></td>';
            $l_html.=chr(13).'       <td>'.Nvl(f($row2,'outras_medidas'),'-').'</td></tr>';
          } else {
            $l_html.=chr(13).'       <td>Sim</td></tr>';
          } 
          $l_html.=chr(13).'   <tr><td><b>Criação/Última Atualização:</b></td>';
          $l_html.=chr(13).'       <td>'.FormataDataEdicao(f($row2,'phpdt_ultima_atualizacao'),3).'</td></tr>';
          $l_html.=chr(13).'   <tr><td colspan="2"><div align="center">';
          $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
          $l_html.=chr(13).'   <tr><td width="10%" bgColor="#f0f0f0"><div align="center"><b>Mês</b></div></td>';
          $l_html.=chr(13).'       <td width="20%" bgColor="#f0f0f0"><div align="center"><b>Inicial</b></div></td>';
          $l_html.=chr(13).'       <td width="20%" bgColor="#f0f0f0"><div align="center"><b>Revisado</b></div></td>';
          $l_html.=chr(13).'       <td width="20%" bgColor="#f0f0f0"><div align="center"><b>Realizado</b></div></td>';
          $l_html.=chr(13).'       <td width="30%" bgColor="#f0f0f0"><div align="center"><b>Financeiro Realizado</b></div></td>';
          if (count($RS3)>0) {
            $RS4 = db_getMetaMensal_IS::getInstanceOf($dbms,f($row2,'sq_meta'));
            $l_realizado_1  ='';
            $l_revisado_1   ='';
            $l_realizado_2  ='';
            $l_revisado_2   ='';
            $l_realizado_3  ='';
            $l_revisado_3   ='';
            $l_realizado_4  ='';
            $l_revisado_4   ='';
            $l_realizado_5  ='';
            $l_revisado_5   ='';
            $l_realizado_6  ='';
            $l_revisado_6   ='';
            $l_realizado_7  ='';
            $l_revisado_7   ='';
            $l_realizado_8  ='';
            $l_revisado_8   ='';
            $l_realizado_9  ='';
            $l_revisado_9   ='';
            $l_realizado_10 ='';
            $l_revisado_10  ='';
            $l_realizado_11 ='';
            $l_revisado_11  ='';
            $l_realizado_12 ='';
            $l_revisado_12  ='';
            if (count($RS4)>0) {
              foreach($RS4 as $row4) {
                switch ((substr(f($row4,'referencia'),3,2))) {
                  case 1:   $l_realizado_1  = f($row4,'realizado');
                            $l_revisado_1   = f($row4,'revisado');
                  break;
                  case 2:   $l_realizado_2  = f($row4,'realizado');
                            $l_revisado_2   = f($row4,'revisado');
                  break;
                  case 3:   $l_realizado_3  = f($row4,'realizado');
                            $l_revisado_3   = f($row4,'revisado');
                  break;
                  case 4:   $l_realizado_4  = f($row4,'realizado');
                            $l_revisado_4   = f($row4,'revisado');
                  break;
                  case 5:   $l_realizado_5  = f($row4,'realizado');
                            $l_revisado_5   = f($row4,'revisado');
                  break;
                  case 6:   $l_realizado_6  = f($row4,'realizado');
                            $l_revisado_6   = f($row4,'revisado');
                  break;
                  case 7:   $l_realizado_7  = f($row4,'realizado');
                            $l_revisado_7   = f($row4,'revisado');
                  break;
                  case 8:   $l_realizado_8  = f($row4,'realizado');
                            $l_revisado_8   = f($row4,'revisado');
                  break;
                  case 9:   $l_realizado_9  = f($row4,'realizado');
                            $l_revisado_9   = f($row4,'revisado');
                  break;
                  case 10:  $l_realizado_10 = f($row4,'realizado');
                            $l_revisado_10  = f($row4,'revisado');
                  break;
                  case 11:  $l_realizado_11 = f($row4,'realizado');
                            $l_revisado_11  = f($row4,'revisado');
                  break;
                  case 12:  $l_realizado_12 =f($row4,'realizado');
                            $l_revisado_12  =f($row4,'revisado');
                  break;
                }
              } 
            } 
            $l_html.=chr(13).'<tr><td width="10%" align="right"><b>Janeiro:';
            $l_html.=chr(13).'    <td align="right">'.Nvl(f($RS3,'cron_ini_mes_1'),'-').'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right">'.Nvl($l_revisado_1,'-').'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right">'.Nvl($l_realizado_1,'-').'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right">'.number_format(Nvl(f($RS3,'real_mes_1'),0),2,',','.').'</td>';
            $l_html.=chr(13).'<tr><td align="right"><b>Fevereiro:';
            $l_html.=chr(13).'    <td align="right">'.Nvl(f($RS3,'cron_ini_mes_2'),'-').'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right">'.Nvl($l_revisado_2,'-').'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right">'.Nvl($l_realizado_2,'-').'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right">'.number_format(Nvl(f($RS3,'real_mes_2'),0),2,',','.').'</td>';
            $l_html.=chr(13).'<tr><td align="right"><b>Março:';
            $l_html.=chr(13).'    <td align="right">'.Nvl(f($RS3,'cron_ini_mes_3'),'-').'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right">'.Nvl($l_revisado_3,'-').'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right">'.Nvl($l_realizado_3,'-').'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right">'.number_format(Nvl(f($RS3,'real_mes_3'),0),2,',','.').'</td>';
            $l_html.=chr(13).'<tr><td align="right"><b>Abril:';
            $l_html.=chr(13).'    <td align="right">'.Nvl(f($RS3,'cron_ini_mes_4'),'-').'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right">'.Nvl($l_revisado_4,'-').'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right">'.Nvl($l_realizado_4,'-').'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right">'.number_format(Nvl(f($RS3,'real_mes_4'),0),2,',','.').'</td>';
            $l_html.=chr(13).'<tr><td align="right"><b>Maio:';
            $l_html.=chr(13).'    <td align="right">'.Nvl(f($RS3,'cron_ini_mes_5'),'-').'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right">'.Nvl($l_revisado_5,'-').'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right">'.Nvl($l_realizado_5,'-').'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right">'.number_format(Nvl(f($RS3,'real_mes_5'),0),2,',','.').'</td>';
            $l_html.=chr(13).'<tr><td align="right"><b>Junho:';
            $l_html.=chr(13).'    <td align="right">'.Nvl(f($RS3,'cron_ini_mes_6'),'-').'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right">'.Nvl($l_revisado_6,'-').'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right">'.Nvl($l_realizado_6,'-').'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right">'.number_format(Nvl(f($RS3,'real_mes_6'),0),2,',','.').'</td>';
            $l_html.=chr(13).'<tr><td align="right"><b>Julho:';
            $l_html.=chr(13).'    <td align="right">'.Nvl(f($RS3,'cron_ini_mes_7'),'-').'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right">'.Nvl($l_revisado_7,'-').'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right">'.Nvl($l_realizado_7,'-').'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right">'.number_format(Nvl(f($RS3,'real_mes_7'),0),2,',','.').'</td>';
            $l_html.=chr(13).'<tr><td align="right"><b>Agosto:';
            $l_html.=chr(13).'    <td align="right">'.Nvl(f($RS3,'cron_ini_mes_8'),'-').'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right">'.Nvl($l_revisado_8,'-').'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right">'.Nvl($l_realizado_8,'-').'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right">'.number_format(Nvl(f($RS3,'real_mes_8'),0),2,',','.').'</td>';
            $l_html.=chr(13).'<tr><td align="right"><b>Setembro:';
            $l_html.=chr(13).'    <td align="right">'.Nvl(f($RS3,'cron_ini_mes_9'),'-').'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right">'.Nvl($l_revisado_9,'-').'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right">'.Nvl($l_realizado_9,'-').'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right">'.number_format(Nvl(f($RS3,'real_mes_9'),0),2,',','.').'</td>';
            $l_html.=chr(13).'<tr><td align="right"><b>Outubro:';
            $l_html.=chr(13).'    <td align="right">'.Nvl(f($RS3,'cron_ini_mes_10'),'-').'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right">'.Nvl($l_revisado_10,'-').'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right">'.Nvl($l_realizado_10,'-').'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right">'.number_format(Nvl(f($RS3,'real_mes_10'),0),2,',','.').'</td>';
            $l_html.=chr(13).'<tr><td align="right"><b>Novembro:';
            $l_html.=chr(13).'    <td align="right">'.Nvl(f($RS3,'cron_ini_mes_11'),'-').'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right">'.Nvl($l_revisado_11,'-').'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right">'.Nvl($l_realizado_11,'-').'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right">'.number_format(Nvl(f($RS3,'real_mes_11'),0),2,',','.').'</td>';
            $l_html.=chr(13).'<tr><td align="right"><b>Dezembro:';
            $l_html.=chr(13).'    <td align="right">'.Nvl(f($RS3,'cron_ini_mes_12'),'-').'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right">'.Nvl($l_revisado_12,'-').'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right">'.Nvl($l_realizado_12,'-').'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right">'.number_format(Nvl(f($RS3,'real_mes_12'),0),2,',','.').'</td>';
            $l_html.=chr(13).'<tr><td align="right"><b>Total:';
            $l_html.=chr(13).'    <td align="right"><b>'.(Nvl(f($RS3,'cron_ini_mes_1'),0)+Nvl(f($RS3,'cron_ini_mes_2'),0)+Nvl(f($RS3,'cron_ini_mes_3'),0)+Nvl(f($RS3,'cron_ini_mes_4'),0)+Nvl(f($RS3,'cron_ini_mes_5'),0)+Nvl(f($RS3,'cron_ini_mes_6'),0)+Nvl(f($RS3,'cron_ini_mes_7'),0)+Nvl(f($RS3,'cron_ini_mes_8'),0)+Nvl(f($RS3,'cron_ini_mes_9'),0)+Nvl(f($RS3,'cron_ini_mes_10'),0)+Nvl(f($RS3,'cron_ini_mes_11'),0)+Nvl(f($RS3,'cron_ini_mes_12'),0)).'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right"><b>'.(Nvl($l_revisado_1,0)+Nvl($l_revisado_2,0)+Nvl($l_revisado_3,0)+Nvl($l_revisado_4,0)+Nvl($l_revisado_5,0)+Nvl($l_revisado_6,0)+Nvl($l_revisado_7,0)+Nvl($l_revisado_8,0)+Nvl($l_revisado_9,0)+Nvl($l_revisado_10,0)+Nvl($l_revisado_11,0)+Nvl($l_revisado_12,0)).'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right"><b>'.(Nvl($l_realizado_1,0)+Nvl($l_realizado_2,0)+Nvl($l_realizado_3,0)+Nvl($l_realizado_4,0)+Nvl($l_realizado_5,0)+Nvl($l_realizado_6,0)+Nvl($l_realizado_7,0)+Nvl($l_realizado_8,0)+Nvl($l_realizado_9,0)+Nvl($l_realizado_10,0)+Nvl($l_realizado_11,0)+Nvl($l_realizado_12,0)).'&nbsp;</td>';
            $l_html.=chr(13).'    <td align="right"><b>'.number_format(Nvl(f($RS3,'real_mes_1'),0)+Nvl(f($RS3,'real_mes_2'),0)+Nvl(f($RS3,'real_mes_3'),0)+Nvl(f($RS3,'real_mes_4'),0)+Nvl(f($RS3,'real_mes_5'),0)+Nvl(f($RS3,'real_mes_6'),0)+Nvl(f($RS3,'real_mes_7'),0)+Nvl(f($RS3,'real_mes_8'),0)+Nvl(f($RS3,'real_mes_9'),0)+Nvl(f($RS3,'real_mes_10'),0)+Nvl(f($RS3,'real_mes_11'),0)+Nvl(f($RS3,'real_mes_12'),0),2,',','.').'</td>';
          } else {
            $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">Nenhuma dado mensal foi informado para esta meta</div></td></tr>';
          } 
          $l_html.=chr(13).'      </table></div></td></tr>';
          $l_cont=$l_cont+1;
        } 
      } else {
        $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">Nenhuma meta cadastrada para esta ação</div></td></tr>';
      } 
    } 
    // Listagem das restrições da ação
    if (upper($l_restricao)==upper('sim')) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>RESTRIÇÕES<hr NOSHADE color=#000000 SIZE=1></b></td></tr>';
      $RS2 = db_getRestricao_IS::getInstanceOf($dbms,'ISACRESTR',$l_chave,null);
      $RS2 = SortArray($RS2,'inclusao','desc');
      if (count($RS2)>0) {
        $l_cont=1;
        foreach ($RS2 as $row2) {
          $l_html.=chr(13).'   <tr><td valigin="top" bgcolor="#f0f0f0"><b>'.$l_cont.') Tipo:</b></td>';
          $l_html.=chr(13).'       <td bgcolor="#f0f0f0"><b>'.f($row2,'nm_tp_restricao').'</b></td></tr>';
          $l_html.=chr(13).'   <tr><td><b>Descrição:</b></td>';
          $l_html.=chr(13).'       <td>'.f($row2,'descricao').'</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>Providência:</b></td>';
          $l_html.=chr(13).'       <td>'.Nvl(f($row2,'providencia'),'-').'</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>Data de Inclusão:</b></td>';
          $l_html.=chr(13).'       <td>'.FormataDataEdicao(f($row2,'phpdt_inclusao'),3).'</td></tr>';
          $l_cont=$l_cont+1;
        } 
      } else {
        $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">Nenhuma restrição cadastrada</div></td></tr>';
      }
    }
    // Listagem das tarefas na visualização da ação, rotina adquirida apartir da rotina exitente na Tarefas.php para listagem das tarefas
    if ($l_tarefa==upper('sim')) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>TAREFAS<hr NOSHADE color=#000000 SIZE=1></b></td></tr>';
      $RS2 = db_getLinkData::getInstanceOf($dbms,RetornaCliente(),'ISTCAD');
    $RS2 = db_getSolicList_IS::getInstanceOf($dbms,f($RS2,'sq_menu'),RetornaUsuario(),'ISTCAD',3,
             null,null,null,null,null,null,
             null,null,null,null,
             null,null,null,null,null,null,null,
             null,null,null,null,$l_chave,null,null,null,null,null,$w_ano);
      $RS2 = SortArray($RS2,'ordem','asc','fim','asc','prioridade','asc');
      if (count($RS2)>0) {
        $l_aviso            = 0;
        $l_atraso           = 0;
        $l_noprazo          = 0;
        $l_cancelado        = 0;
        $l_concluido        = 0;
        $l_conc_atraso      = 0;
        $l_vr_aviso         = 0;
        $l_vr_atraso        = 0;
        $l_vr_noprazo       = 0;
        $l_vr_cancelado     = 0;
        $l_vr_concluido     = 0;
        $l_vr_conc_atraso   = 0;
        $l_html.=chr(13).'   <tr><td colspan="2"><div align="center">';
        $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
        $l_html.=chr(13).'   <tr><td width="8%" bgColor="#f0f0f0"><div align="center"><b>Código</b></div></td>';
        $l_html.=chr(13).'       <td bgColor="#f0f0f0"><div align="center"><b>Tarefa</b></div></td>';
        $l_html.=chr(13).'       <td width="12%" bgColor="#f0f0f0"><div align="center"><b>Responsável</b></div></td>';
        $l_html.=chr(13).'       <td width="10%" bgColor="#f0f0f0"><div align="center"><b>Início</b></div></td>';
        $l_html.=chr(13).'       <td width="10%" bgColor="#f0f0f0"><div align="center"><b>Fim</b></div></td>';
        $l_html.=chr(13).'       <td width="12%" bgColor="#f0f0f0"><div align="center"><b>Valor (R$)</b></div></td>';
        $l_html.=chr(13).'       <td width="13%" bgColor="#f0f0f0"><div align="center"><b>Fase Atual</b></div></td></tr>';
        foreach ($RS2 as $row2) {
          $l_html.=chr(13).'   <tr><td><b>';
          if (f($row2,'concluida')=='N') {
            if (f($row2,'fim')<addDays(time(),-1)) {
              $l_html.=chr(13).'          <img src="'.$conImgAtraso.'" border=0 width=14 heigth=14 align="center">';
              $l_atraso     = $l_atraso + 1;
              $l_vr_atraso  = $l_vr_atraso + Nvl(f($row2,'valor'),0);
            } elseif (f($row2,'aviso_prox_conc')=='S' && (f($row2,'aviso')<=addDays(time(),-1))){
              $l_html.=chr(13).'          <img src="'.$conImgAviso.'" border=0 width=14 height=14 align="center">';
              $l_aviso      = $l_aviso + 1;
              $l_vr_aviso   = $l_vr_aviso + Nvl(f($row2,'valor'),0);
            } elseif (f($row2,'sg_tramite')=='CA') {
              $l_html.=chr(13).'          <img src="'.$conImgCancel.'" border=0 width=14 height=14 align="center">';
              $l_cancelado      =   $l_cancelado + 1;
              $l_vr_cancelado   =   $l_vr_cancelado + Nvl(f($row2,'valor'),0);
            } else {
              $l_html.=chr(13).'          <img src="'.$conImgNormal.'" border=0 width=14 height=14 align="center">';
              $l_noprazo    =   $l_noprazo + 1;
              $l_vr_noprazo =   $l_vr_noprazo + Nvl(f($row2,'valor'),0);
            }
          } else {
            if (f($row2,'fim')<Nvl(f($row2,'fim_real'),f($row2,'fim'))) {
              $l_html.=chr(13).'          <img src="'.$conImgOkAtraso.'" border=0 width=14 heigth=14 align="center">';
              $l_conc_atraso    =   $l_conc_atraso + 1;
              $l_vr_conc_atraso =   $l_vr_conc_atraso + Nvl(f($row2,'valor'),0);
            } else {
              $l_html.=chr(13).'          <img src="'.$conImgOkNormal.'" border=0 width=14 height=14 align="center">';
              $l_concluido      = $l_concluido + 1;
              $l_vr_concluido   = $l_vr_concluido + Nvl(f($row2,'valor'),0);
            }
          } 
          $l_html.=chr(13).'    <A class="HL" HREF="'.$w_dir.'tarefas.php?par=Visual&R='.$l_pagina.$par.'&O=L&w_chave='.f($row2,'sq_siw_solicitacao').'&w_tipo=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro." target="_blank">'.f($row2,'sq_siw_solicitacao').'&nbsp;</a>';
          $l_html.=chr(13).'    <td>'.Nvl(f($row2,'titulo'),'-').'</td>';
          $l_html.=chr(13).'    <td>'.ExibePessoa('../',$w_cliente,f($row2,'solicitante'),$TP,f($row2,'nm_solic')).'</td>';
          $l_html.=chr(13).'    <td><div align="center">'.FormataDataEdicao(f($row2,'inicio')).'</div></td>';
          $l_html.=chr(13).'    <td><div align="center">'.FormataDataEdicao(f($row2,'fim')).'</div></td>';
          $l_html.=chr(13).'    <td><div align="right">'.number_format(Nvl(f($row2,'valor'),0),2,',','.').'</div></td>';
          $l_html.=chr(13).'    <td>'.f($row2,'nm_tramite').'</td>';
        }
        $l_html.=chr(13).'         </table></div></td></tr>';
        $l_html.=chr(13).'   <tr><td colspan="2"><br>';
        $l_total    = $l_atraso + $l_aviso + $l_noprazo + $l_concluido + $l_conc_atraso;
        $l_vr_total = $l_vr_atraso + $l_vr_aviso + $l_vr_noprazo + $l_vr_concluido + $l_vr_conc_atraso;
        $l_html.=chr(13).'   <tr><td colspan="2"><div align="center">';
        $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
        $l_html.=chr(13).'       <tr><td width="8%" bgColor="#f0f0f0"><div align="center"><b>Simbolo</b></div></td>';
        $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="center"><b>Situação da Tarefa</b></div></td>';
        $l_html.=chr(13).'         <td width="22%" bgColor="#f0f0f0"><div align="center"><b>Valor Total</b></div></td>';
        $l_html.=chr(13).'         <td width="10%" bgColor="#f0f0f0"><div align="center"><b>% Valor</b></div></td>';
        $l_html.=chr(13).'         <td width="12%" bgColor="#f0f0f0"><div align="center"><b>Nº de Tarefas</b></div></td>';
        $l_html.=chr(13).'         <td width="13%" bgColor="#f0f0f0"><div align="center"><b>% Tarefas</b></div></td>';
        $l_html.=chr(13).'       <tr><td><div align="center"><img src="'.$conImgNormal.'" border=0 width=14 height=14 align="center"></div></td>';
        $l_html.=chr(13).'         <td>No Prazo</td>';
        $l_html.=chr(13).'         <td><div align="right">'.number_format($l_vr_noprazo,2,',','.').'</td>';
        if ($l_vr_total>0)  $l_html.=chr(13).'         <td><div align="right">'.round((($l_vr_noprazo/$l_vr_total)*100),2).'</td>';
        else                $l_html.=chr(13).'         <td><div align="right">0</td>';
        $l_html.=chr(13).'         <td><div align="right">'.$l_noprazo.'</td>';
        $l_html.=chr(13).'         <td><div align="right">'.round((($l_noprazo/$l_total)*100),2).'</td></tr>';
        $l_html.=chr(13).'       <tr><td><div align="center"><img src="'.$conImgAtraso.'" border=0 width=14 height=14 align="center"></div></td>';
        $l_html.=chr(13).'         <td>Em Atraso</td>';
        $l_html.=chr(13).'         <td><div align="right">'.number_format($l_vr_atraso,2,',','.').'</td>';
        if ($l_vr_total>0)  $l_html.=chr(13).'         <td><div align="right">'.round((($l_vr_atraso/$l_vr_total)*100),2).'</td>';
        else                $l_html.=chr(13).'         <td><div align="right">0</td>';
        $l_html.=chr(13).'         <td><div align="right">'.$l_atraso.'</td>';
        $l_html.=chr(13).'         <td><div align="right">'.round((($l_atraso/$l_total)*100),2).'</td></tr>';
        $l_html.=chr(13).'       <tr><td><div align="center"><img src="'.$conImgAviso.'" border=0 width=14 height=14 align="center"></div></td>';
        $l_html.=chr(13).'         <td>Em Aviso</td>';
        $l_html.=chr(13).'         <td><div align="right">'.number_format($l_vr_aviso,2,',','.').'</td>';
        if ($l_vr_total>0)  $l_html.=chr(13).'         <td><div align="right">'.round((($l_vr_aviso/$l_vr_total)*100),2).'</td>';
        else                $l_html.=chr(13).'         <td><div align="right">0</td>';
        $l_html.=chr(13).'         <td><div align="right">'.$l_aviso.'</td>';
        $l_html.=chr(13).'         <td><div align="right">'.round((($l_aviso/$l_total)*100),2).'</td></tr>';
        $l_html.=chr(13).'       <tr><td><div align="center"><img src="'.$conImgOkNormal.'" border=0 width=14 height=14 align="center"></div></td>';
        $l_html.=chr(13).'         <td>Concluída no Prazo</td>';
        $l_html.=chr(13).'         <td><div align="right">'.number_format($l_vr_concluido,2,',','.').'</td>';
        if ($l_vr_total>0)  $l_html.=chr(13).'         <td><div align="right">'.round((($l_vr_concluido/$l_vr_total)*100),2).'</td>';
        else                $l_html.=chr(13).'         <td><div align="right">0</td>';
        $l_html.=chr(13).'         <td><div align="right">'.$l_concluido.'</td>';
        $l_html.=chr(13).'         <td><div align="right">'.round((($l_concluido/$l_total)*100),2).'</td></tr>';
        $l_html.=chr(13).'       <tr><td><div align="center"><img src="'.$conImgOkAtraso.'" border=0 width=14 height=14 align="center"></div></td>';
        $l_html.=chr(13).'         <td>Concluída Após o Prazo</td>';
        $l_html.=chr(13).'         <td><div align="right">'.number_format($l_vr_conc_atraso,2,',','.').'</td>';
        if ($l_vr_total>0)  $l_html.=chr(13).'         <td><div align="right">'.round((($l_vr_conc_atraso/$l_vr_total)*100),2).'</td>';
        else                $l_html.=chr(13).'         <td><div align="right">0</td>';
        $l_html.=chr(13).'         <td><div align="right">'.$l_conc_atraso.'</td>';
        $l_html.=chr(13).'         <td><div align="right">'.round((($l_conc_atraso/$l_total)*100),2).'</td></tr>';
        $l_html.=chr(13).'       <tr><td><div align="center"><img src="'.$conImgCancel.'" border=0 width=14 height=14 align="center"></div></td>';
        $l_html.=chr(13).'         <td>Cancelada(*)</td>';
        $l_html.=chr(13).'         <td><div align="right">'.number_format($l_vr_cancelado,2,',','.').'</td>';
        $l_html.=chr(13).'         <td><div align="right">-</td>';
        $l_html.=chr(13).'         <td><div align="right">'.$l_cancelado.'</td>';
        $l_html.=chr(13).'         <td><div align="right">-</td></tr>';
        $l_html.=chr(13).'       <tr><td colspan="2" bgColor="#f0f0f0"><b>Total</b></td>';
        $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="right">'.number_format($l_vr_total,2,',','.').'</td>';
        if ($l_vr_total>0)  $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="right">'.round((($l_vr_total/$l_vr_total)*100),2).'%</td>';
        else                $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="right">0%</td>';
        $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="right">'.$l_total.'</td>';
        $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="right">'.round((($l_total/$l_total)*100),2).'%</td></tr>';
        $l_html.=chr(13).'     </table></div></td></tr>';
        $l_html.=chr(13).'   <tr><td colspan="2">(*) Valores não considerados no cálculo dos totais</td></tr>';
      } else {
        $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">Nenhuma tarefa cadastrada</div></td></tr>';
      }
    } 
    // Interessados na execução da ação
    if (upper($l_interessado)==upper('sim')) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>INTERESSADOS NA EXECUÇÃO<hr NOSHADE color=#000000 SIZE=1></b></td></tr>';
      $RS1 = db_getSolicInter::getInstanceOf($dbms,$l_chave,null,'LISTA');
      $RS1 = SortArray($RS1,'nome_resumido','asc');
      if (count($RS1)>0) {
        $TP = RemoveTP($TP).' - Interessados';
        $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">Clique <a class="HL" HREF="'.$w_dir.'acao.php?par=interess&R='.$l_Pagina.$par.'&O=L&w_chave='.$l_chave.'&P1=4&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" target="_blank">aqui</a> para visualizar os Interessados na execução</div></td></tr>';
      } else {
        $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">Nenhum interessado cadastrado</div></td></tr>';
      } 
    }
    // Arquivos vinculados
    if (upper($l_anexo)==upper('sim')) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ANEXOS<hr NOSHADE color=#000000 SIZE=1></b></td></tr>';
      $RS1 = db_getSolicAnexo::getInstanceOf($dbms,$l_chave,null,$w_cliente);
      $RS1 = SortArray($RS1,'nome','asc');
      if (count($RS1)>0) {
        $l_html.=chr(13).'   <tr><td colspan="2"><div align="center">';
        $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
        $l_html.=chr(13).'       <tr><td bgColor="#f0f0f0"><div align="center"><b>Título</b></div></td>';
        $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="center"><b>Descrição</b></div></td>';
        $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="center"><b>Tipo</b></div></td>';
        $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="center"><b>KB</b></div></td>';
        $l_html.=chr(13).'       </tr>';
        foreach ($RS1 as $row1) {
          $l_html.=chr(13).'       <tr><td>'.LinkArquivo('HL',$w_cliente,f($row1,'chave_aux'),'_blank','Clique para exibir o arquivo em outra janela.',f($row1,'nome'),null).'</td>';
          $l_html.=chr(13).'           <td>'.Nvl(f($row1,'descricao'),'-').'</td>';
          $l_html.=chr(13).'           <td>'.f($row1,'tipo').'</td>';
          $l_html.=chr(13).'         <td><div align="right">'.round($cDbl[f($row1,'tamanho')]/1024,1).'&nbsp;</td>';
          $l_html.=chr(13).'      </tr>';
        } 
        $l_html.=chr(13).'         </table></div></td></tr>';
      } else {
        $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">Nenhuma arquivo cadastrado</div></td></tr>';
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
        $w_html .= chr(13).'      <tr bgcolor="'.$conTrBgColor.'"><td colspan=4 align="center"><b>Não foram encontrados encaminhamentos.</b></td></tr>';
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
          $l_html.=chr(13).'        <td>'.CRLF2BR(Nvl(f($row1,'despacho'),'---')).'</td>';
          $l_html.=chr(13).'        <td nowrap>'.ExibePessoa('../',$w_cliente,f($row1,'sq_pessoa'),$TP,f($row1,'responsavel')).'</td>';
          if ((Nvl(f($row1,'sq_projeto_log'),'')>'') && (Nvl(f($row1,'destinatario'),'')>''))         $l_html.=chr(13).'        <td nowrap>'.ExibePessoa('../',$w_cliente,f($row1,'sq_pessoa_destinatario'),$TP,f($row1,'destinatario')).'</td>';
          elseif ((Nvl(f($row1,'sq_projeto_log'),'')>'')  && (Nvl(f($row1,'destinatario'),'')==''))   $l_html.=chr(13).'        <td nowrap>Anotação</td>';
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
  } 
  return $l_html;
} 
?>