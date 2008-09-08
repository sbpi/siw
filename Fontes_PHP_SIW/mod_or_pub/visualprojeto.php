<?
// =========================================================================
// Rotina de visualização dos dados da ação
// -------------------------------------------------------------------------
function VisualProjeto($l_chave,$O,$w_usuario,$P1,$P4) {
  extract($GLOBALS);
  global $w_Disabled;
  $l_html='';
  // Recupera os dados da ação
  $RS = db_getSolicData::getInstanceOf($dbms,$l_chave,'PJGERAL');
  // O código abaixo foi comentado em 23/11/2004, devido à mudança na regra definida pelo usuário,
  // que agora permite visão geral para todos os usuários
  // Recupera o tipo de visão do usuário
  //If cDbl(Nvl(RS('solicitante'),0)) = cDbl(w_usuario) or _
  //   cDbl(Nvl(RS('executor'),0))    = cDbl(w_usuario) or _
  //   cDbl(Nvl(RS('cadastrador'),0)) = cDbl(w_usuario) or _
  //   cDbl(Nvl(RS('titular'),0))     = cDbl(w_usuario) or _
  //   cDbl(Nvl(RS('substituto'),0))  = cDbl(w_usuario) or _
  //   cDbl(Nvl(RS('tit_exec'),0))    = cDbl(w_usuario) or _
  //   cDbl(Nvl(RS('subst_exec'),0))  = cDbl(w_usuario) Then
  //   ' Se for solicitante, executor ou cadastrador, tem visão completa
  //   w_tipo_visao = 0
  //Else
  //   $RS = db_getSolicInter Rsquery, w_chave, w_usuario, 'REGISTRO'
  //   If Not RSquery.EOF Then
  //      ' Se for interessado, verifica a visão cadastrada para ele.
  //      w_tipo_visao = cDbl(RSquery('tipo_visao'))
  //   Else
  //      $RS = db_getSolicAreas Rsquery, w_chave, Session('sq_lotacao'), 'REGISTRO'
  //      If Not RSquery.EOF Then
  //         ' Se for de uma das unidades envolvidas, tem visão parcial
  //         w_tipo_visao = 1
  //      Else
  //         ' Caso contrário, tem visão resumida
  //         w_tipo_visao = 2
  //      End If
  //   End If
  //End If
  $w_tipo_visao=0;
  //Se for para exibir só a ficha resumo da ação.
  if ($P1==1 || $P1==2) {
    $l_html .=chr(13).'<div align=center><center>';
    $l_html .=chr(13).'  <table border="0" cellpadding="0" cellspacing="0" width="100%">';
    $l_html .=chr(13).'    <tr><td align="center" colspan="2">';
    $l_html .=chr(13).'      <table width="100%" border="0">';
    if ($P4!=1) $l_html .=chr(13).'      <tr><td align="right" colspan="3"><b><A class="HL" HREF="'.$w_dir.'projeto.php?par=Visual&O=L&w_chave='.f($RS,'sq_siw_solicitacao').'&w_tipo=volta&P1=&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe as informações da ação.">Exibir todas as informações</a></td></tr>';
    // Se a iniciativa prioritária for informada, exibe.
    if ( nvl(f($RS,'sq_orprioridade'),'')>'') {
      $l_html .=chr(13).' <tr><td width="30%"><b>Iniciativa prioritária:</b></td>';
      $l_html .=chr(13).'        <td>'.f($RS,'nm_pri').'</td></tr>';
    }
    // Se a ação no PPA for informada, exibe.
    if (nvl(f($RS,'sq_acao_ppa'),'')>'') {
      $l_html .=chr(13).'      <tr><td valign="top"><b>Programa PPA:</b></td>';
      $l_html .=chr(13).'        <td>'.f($RS,'nm_ppa_pai').'</td></tr>';
      $l_html .=chr(13).'      <tr><td valign="top"><b>Cód:</b></td>';
      $l_html .=chr(13).'        <td>'.f($RS,'cd_ppa_pai').'</td></tr>';
      $l_html .=chr(13).'      <tr bgcolor="#D0D0D0"><td valign="top"><b>Ação PPA:</b></td>';
      $l_html .=chr(13).'        <td>'.f($RS,'nm_ppa').' </td></tr>';
      $l_html .=chr(13).'      <tr><td valign="top"><b>Cód:</b></td>';
      $l_html .=chr(13).'        <td>'.f($RS,'cd_ppa').'</td></tr>';
    } else {
      $l_html .=chr(13).'      <tr><td valign="top"><b>Ação:</b></td>';
      $l_html .=chr(13).'        <td>'.f($RS,'titulo').'</td></tr>';
    } 
    if ($w_tipo_visao==0) {
      // Se for visão completa
      $l_html .=chr(13).'      <tr><td valign="top"><b>Recurso programado:</b></td>';
      $l_html .=chr(13).'        <td>'.number_format(f($RS,'valor'),2,',','.').'</td></tr>';
    } 
    $RS1 = db_getPersonData::getInstanceOf($dbms,$w_cliente,f($RS,'Solicitante'),null,null);
    if ($P4==1) {
      $l_html .=chr(13).'      <tr><td valign="top"><b>Responsável monitoramento:</b></td>';
      $l_html .=chr(13).'        <td>'.f($RS,'nm_sol').'</td></tr>';
      $l_html .=chr(13).'      <tr><td valign="top"><b>E-mail:</b></td>';
      $l_html .=chr(13).'        <td>'.f($RS1,'email').'</td></tr>';
    } else {
      $l_html .=chr(13).'      <tr><td valign="top"><b>Responsável monitoramento:</b></td>';
      $l_html .=chr(13).'        <td>'.ExibePessoa('../',$w_cliente,f($RS,'solicitante'),$TP,f($RS,'nm_sol')).'</td></tr>';
      $l_html .=chr(13).'      <tr><td valign="top"><b>E-mail:</b></td>';
      $l_html .=chr(13).'        <td><A class="HL" HREF="mailto:'.f($RS1,'email').'">'.f($RS1,'email').'</a></td></tr>';
    } 
    $l_html .=chr(13).'        <tr><td valign="top"><b>Telefone:</b></td>';
    $l_html .=chr(13).'          <td>'.Nvl(f($RS1,'telefone'),'---').' </td></tr>';
    $l_html .=chr(13).'         </table></td></tr>';
    if ($w_tipo_visao==0 || $w_tipo_visao==1) {
      // Metas da ação
      // Recupera todos os registros para a listagem     
      $RS1 = db_getSolicEtapa::getInstanceOf($dbms,$l_chave,null,'LSTNULL',null);
      $RS1 = SortArray($RS1,'ordem','asc');
      if (count($RS1)>0) {
        // Se não foram selecionados registros, exibe mensagem
        $l_html .=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>METAS CADASTRADAS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
        $l_html .=chr(13).'      <tr><td align="center" colspan="2">';
        $l_html .=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
        foreach ($RS1 as $row1){ 
          $l_html .=chr(13).'          <tr align="center">';
          $l_html .=chr(13).'           <td rowspan="2" bgColor="#f0f0f0"><div><b>Produto</b></div></td>';
          $l_html .=chr(13).'           <td rowspan="2" bgColor="#f0f0f0"><div><b>Unidade medida</b></div></td>';
          $l_html .=chr(13).'           <td rowspan="2" bgColor="#f0f0f0"><div><b>LOA</b></div></td>';
          $l_html .=chr(13).'           <td rowspan="2" bgColor="#f0f0f0"><div><b>Cumulativa</b></div></td>';
          $l_html .=chr(13).'           <td rowspan="2" bgColor="#f0f0f0"><div><b>Será cumprida</b></div></td>';
          $l_html .=chr(13).'           <td rowspan="1" colspan="3" bgColor="#f0f0f0"><div><b>Quantitativo</b></div></td>';
          $l_html .=chr(13).'         </tr>';
          $l_html .=chr(13).'         <tr align="center">';
          $l_html .=chr(13).'           <td bgColor="#f0f0f0"><div><b>Programado</b></div></td>';
          $l_html .=chr(13).'           <td bgColor="#f0f0f0"><div><b>Realizado</b></div></td>';
          $l_html .=chr(13).'           <td bgColor="#f0f0f0"><div><b>% Realizado</b></div></td>';
          $l_html .=chr(13).'         </tr>';
          $l_html .=chr(13).'         <tr valign="top">';
          $l_html .=chr(13).'           <td nowrap>';
          if ((f($row1,'fim_previsto')<time()) && (Nvl(f($row1,'perc_conclusao'),0)<100))    $l_html .=chr(13).'           <img src="'.$conImgAtraso.'" border=0 width=15 height=15 align="center">';
          elseif (Nvl(f($row1,'perc_conclusao'),0)<100)   $l_html .=chr(13).'           <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">';
          else         $l_html .=chr(13).'           <img src="'.$conImgOkNormal.'" border=0 width=15 height=15 align="center">';
          if ($P4==1)  $l_html .=chr(13).f($row1,'titulo').'</td>';
          else         $l_html .=chr(13).'<A class="HL" HREF="javascript:this.status.value;" onClick="window.open(\'projeto.php?par=AtualizaEtapa&O=V&w_chave='.f($row1,'sq_siw_solicitacao').'&w_chave_aux='.f($row1,'sq_projeto_etapa').'&w_tipo=Volta&P1=10&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\',\'Meta\',\'width=600, height=350, top=50, left=50, toolbar=no, scrollbars=yes, resizable=yes, status=no\'); return false;" title="Clique para exibir os dados!">'.f($row1,'titulo').'</A></td>';
          $l_html .=chr(13).'          <td>'.Nvl(f($row1,'unidade_medida'),'---').'</td>';
          $l_html .=chr(13).'          <td align="center">'.Nvl(f($row1,'nm_programada'),'---').'</td>';
          $l_html .=chr(13).'          <td align="center">'.Nvl(f($row1,'nm_cumulativa'),'---').'</td>';
          $l_html .=chr(13).'          <td align="center">'.Nvl(f($row1,'nm_exequivel'),'---').'</td>';
          $l_html .=chr(13).'          <td align="right">'.Nvl(f($row1,'quantidade'),0).'</td>';
          $l_html .=chr(13).'          <td align="right">'.((Nvl(f($row1,'quantidade'),0)* Nvl(f($row1,'perc_conclusao'),0))/100).'</td>';
          $l_html .=chr(13).'          <td align="right">'.Nvl(f($row1,'perc_conclusao'),0).'</td></tr>';
          $l_html .=chr(13).'      <tr><td colspan="8"><DD>Especifição do produto: <b>'.Nvl(f($row1,'descricao'),'---').'</DD></td></tr>';
          $l_html .=chr(13).'      <tr><td colspan="8"><DD>Situação atual: <b>'.Nvl(f($row1,'situacao_atual'),'---').'</DD></td></tr>';
          if (f($row1,'exequivel')=='N') {
            $l_html .=chr(13).'      <tr><td colspan="8"><DD>Quais os motivos para o não cumprimento da meta? <b>'.Nvl(f($row1,'justificativa_inexequivel'),'---').'</DD></td></tr>';
            $l_html .=chr(13).'      <tr><td colspan="8"><DD>Quais as medidas necessárias para o cumprimento da meta? <b>'.Nvl(f($row1,'outras_medidas'),'---').'</DD></td></tr>';
          } 
        } 
        $l_html .=chr(13).'         </table></td></tr>';
      } 
    } 
    if ($w_tipo_visao==0) {
      //Financiamento
      $RS1 = db_getFinancAcaoPPA::getInstanceOf($dbms,$l_chave,$w_cliente,null);
      if (f($RS,'cd_ppa')>'') {
        $l_html .=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>FINANCIAMENTO</b>';
        $RS2 = db_getOrImport::getInstanceOf($dbms,null,$w_cliente,null,null,null,null,null);
        $RS2 = SortArray($RS2,'phpdt_data_arquivo','desc');
        foreach($RS2 as $row2){$RS2=$row2; break;}
        $l_html .=chr(13).'          <font size="2"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fonte: SIAFI&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Atualização: '.FormataDataEdicao(f($RS2,'phpdt_data_arquivo'),3).'<hr NOSHADE color=#000000 SIZE=1></b>';  
        $l_html .=chr(13).'      <tr><td align="center" colspan="2">';
        $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
        $l_html.=chr(13).'          <tr align="center">';
        $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>Cód. Prog.</b></div></td>';
        $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>Cód. Ação</b></div></td>';
        $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>Aprovado</b></div></td>';
        $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>Empenhado</b></div></td>';
        $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>Saldo</b></div></td>';
        $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>Liquidado</b></div></td>';
        $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>A Liquidar</b></div></td>';
        $l_html .=chr(13).'          </tr>';
        $l_html .=chr(13).'      <tr valign="top">';
        $l_html .=chr(13).'        <td>'.f($RS,'cd_ppa_pai').'</td>';
        $l_html .=chr(13).'        <td>'.f($RS,'cd_ppa').'</td>';
        $l_html .=chr(13).'        <td align="right">'.number_format(f($RS,'aprovado'),2,',','.').'</td>';
        $l_html .=chr(13).'        <td align="right">'.number_format(f($RS,'empenhado'),2,',','.').'</td>';
        $l_html .=chr(13).'        <td align="right">'.number_format(Nvl(f($RS,'aprovado'),0.00)-Nvl(f($RS,'empenhado'),0.00),2,',','.').'</td>';
        $l_html .=chr(13).'        <td align="right">'.number_format(f($RS,'liquidado'),2,',','.').'</td>';
        $l_html .=chr(13).'        <td align="right">'.number_format((Nvl(f($RS,'empenhado'),0.00)-Nvl(f($RS,'liquidado'),0.00)),2,',','.').'</td>';
        $l_html .=chr(13).'      </tr>';
        if (count($RS1)>0) {
          foreach($RS1 as $row1) {
            $l_html .=chr(13).'      <tr valign="top">';
            $l_html .=chr(13).'        <td>'.f($row1,'cd_ppa_pai').'</td>';
            $l_html .=chr(13).'        <td>'.f($row1,'cd_ppa').'</td>';
            $l_html .=chr(13).'        <td align="right">'.number_format(f($row1,'aprovado'),2,',','.').'</td>';
            $l_html .=chr(13).'        <td align="right">'.number_format(f($row1,'empenhado'),2,',','.').'</td>';
            $l_html .=chr(13).'        <td align="right">'.number_format(Nvl(f($row1,'aprovado'),0.00)-Nvl(f($row1,'empenhado'),0.00),2,',','.').'</td>';
            $l_html .=chr(13).'        <td align="right">'.number_format(f($row1,'liquidado'),2,',','.').'</td>';
            $l_html .=chr(13).'        <td align="right">'.number_format((Nvl(f($row1,'empenhado'),0.00)-Nvl(f($row1,'liquidado'),0.00)),2,',','.').'</td>';
            $l_html .=chr(13).'      </tr>';
            $l_html .=chr(13).'      <tr valign="top">';
            $l_html .=chr(13).'        <td colspan=7><DD><b>Observação:</b> '.Nvl(f($row1,'observacao'),'---').'</DD></td>';
            $l_html .=chr(13).'      </tr>';
          } 
        } 
        $l_html .=chr(13).'         </table></td></tr>';
      } elseif (count($RS1)>0) {
        $l_html .=chr(13).'      <tr><td valign="top" colspan="2" align="left" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="2"><b>&nbsp;Financiamento</b>';
        $RS2 = db_getOrImport::getInstanceOf($dbms,null,$w_cliente,null,null,null,null,null);
        $RS2 = SortArray($RS2,'data_arquivo','desc');
        $l_html .=chr(13).'          <font size="2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fonte: SIAFI&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Atualização: '.Nvl(FormataDataEdicao(f($RS2,'data_arquivo')),'-').'</td></tr>';
        $l_html .=chr(13).'      <tr><td align="center" colspan="2">';
        $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
        $l_html.=chr(13).'          <tr align="center">';
        $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>Cód. Prog.</b></div></td>';
        $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>Cód. Ação</b></div></td>';
        $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>Aprovado</b></div></td>';
        $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>Empenhado</b></div></td>';
        $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>Saldo</b></div></td>';
        $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>Liquidado</b></div></td>';
        $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>A Liquidar</b></div></td>';
        $l_html .=chr(13).'          </tr>';
        foreach($RS1 as $row1) {
          $l_html .=chr(13).'      <tr valign="top">';
          $l_html .=chr(13).'        <td>'.f($row1,'cd_ppa_pai').'</td>';
          $l_html .=chr(13).'        <td>'.f($row1,'cd_ppa').'</td>';
          $l_html .=chr(13).'        <td align="right">'.number_format(f($row1,'aprovado'),2,',','.').'</td>';
          $l_html .=chr(13).'        <td align="right">'.number_format(f($row1,'empenhado'),2,',','.').'</td>';
          $l_html .=chr(13).'        <td align="right">'.number_format(Nvl(f($row1,'aprovado'),0.00)-Nvl(f($row1,'empenhado'),0.00),2,',','.').'</td>';
          $l_html .=chr(13).'        <td align="right">'.number_format(f($row1,'liquidado'),2,',','.').'</td>';
          $l_html .=chr(13).'        <td align="right">'.number_format((Nvl(f($row1,'empenhado'),0.00)-Nvl(f($row1,'liquidado'),0.00)),2,',','.').'</td>';
          $l_html .=chr(13).'      </tr>';
          $l_html .=chr(13).'      <tr valign="top">';
          $l_html .=chr(13).'        <td colspan=7><DD><b>Observação:</b> '.Nvl(f($row1,'observacao'),'---').'</DD></td>';
          $l_html .=chr(13).'      </tr>';
        }
        $l_html .=chr(13).'         </table></td></tr>';
      } 
      // Listagem das tarefas na visualização da ação, rotina adquirida apartir da rotina exitente na Projetoativ.php para listagem das tarefas
      $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'ORPCAD');
      $RS = db_getSolicList::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,'ORPCAD',5,
            null,null,null,null,null,null,
            null,null,null,null,
            null,null,null,null,null,null,null,
            null,null,null,null,null,$l_chave,null,null,null);
      $RS = SortArray($RS,'ordem','asc','fim','asc','prioridade','asc');
      if (count($RS)>0) {
        $l_html .=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>TAREFAS CADASTRADAS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
        $l_html .=chr(13).'      <tr><td align="center" colspan="2">';
        $l_html .=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
        $l_html .=chr(13).'          <tr align="center">';
        $l_html .=chr(13).'            <td nowrap bgColor="#f0f0f0"><div><b>Nº</b></div></td>';
        $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>Detalhamento</b></div></td>';
        $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>Responsável</b></div></td>';
        $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>Parcerias</b></div></td>';;
        $l_html .=chr(13).'            <td nowrap bgColor="#f0f0f0"><div><b>Fim<br>previsto</b></div></td>';
        $l_html .=chr(13).'            <td nowrap bgColor="#f0f0f0"><div><b>Programado<br>R$ 1,00</b></div></td>';
        $l_html .=chr(13).'            <td nowrap bgColor="#f0f0f0"><div><b>Executado<br>R$ 1,00</b></div></td>';
        $l_html .=chr(13).'            <td nowrap bgColor="#f0f0f0"><div><b>Fase atual</b></div></td>';
        $l_html .=chr(13).'            <td nowrap bgColor="#f0f0f0"><div><b>Prioridade</b></div></td>';
        $l_html .=chr(13).'          </tr>';
        foreach($RS as $row) {
          $l_html .=chr(13).'       <tr valign="top">';
          $l_html .=chr(13).'         <td nowrap>';
          if (f($row,'concluida')=='N') {
            if (f($row,'fim')<addDays(time(),-1)) {
              $l_html .=chr(13).'          <img src="'.$conImgAtraso.'" border=0 width=15 heigth=15 align="center">';
            } elseif (f($row,'aviso_prox_conc')=='S' && (f($row,'aviso')<=addDays(time(),-1))) {
              $l_html .=chr(13).'          <img src="'.$conImgAviso.'" border=0 width=15 height=15 align="center">';
            } else {
              $l_html .=chr(13).'          <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">';
            } 
          } else {
            if (f($row,'fim')<Nvl(f($row,'fim_real'),f($row,'fim'))) {
              $l_html .=chr(13).'          <img src="'.$conImgOkAtraso.'" border=0 width=15 heigth=15 align="center">';
            } else {
              $l_html .=chr(13).'          <img src="'.$conImgOkNormal.'" border=0 width=15 height=15 align="center">';
            } 
          } 
          if ($P4==1)   $l_html .=chr(13).f($row,'sq_siw_solicitacao').'</td>';
          else          $l_html .=chr(13).'         <A class="HL" HREF="'.$w_dir.'projetoativ.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro." target="_blank">'.f($row,'sq_siw_solicitacao').'&nbsp;</a></td>';
          //If Len(Nvl(RS('assunto'),'-')) > 80 Then w_titulo = Mid(Nvl(RS('assunto'),'-'),1,50) & '...' Else w_titulo = Nvl(RS('assunto'),'-') End If
          if (f($row,'sg_tramite')=='CA') $l_html .=chr(13).'      <td><strike>'.Nvl(f($row,'assunto'),'-').'</strike></td>';
          else                           $l_html .=chr(13).'      <td>'.Nvl(f($row,'assunto'),'-').'</td>';
          $l_html .=chr(13).'         <td>'.Nvl(f($row,'palavra_chave'),'---').'</td>';
          $l_html .=chr(13).'         <td>'.Nvl(f($row,'proponente'),'---').'</td>';
          $l_html .=chr(13).'         <td align="center">&nbsp;'.FormataDataEdicao(f($row,'fim')).'</td>';
          $l_html .=chr(13).'         <td align="right" nowrap>'.number_format(Nvl(f($row,'valor'),0),2,',','.').'</td>';
          $l_html .=chr(13).'         <td align="right" nowrap>'.number_format(Nvl(f($row,'custo_real'),0),2,',','.').'</td>';
          $l_html .=chr(13).'         <td nowrap>'.f($row,'nm_tramite').'</td>';
          $l_html .=chr(13).'         <td nowrap>'.RetornaPrioridade(f($row,'prioridade')).'</td></tr>';
        } 
        $l_html .=chr(13).'         </table></td></tr>';
      }  
    } 
    $l_html .=chr(13).'</table>';
    $l_html .=chr(13).'</center>';
    $l_html .=chr(13).'</div>';
  } else {
    if ($O=='L' || $O=='V') {
      // Se for listagem dos dados
      $l_html .=chr(13).'<div align=center><center>';
      $l_html .=chr(13).'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
      $l_html .=chr(13).'<tr><td align="center">';
      $l_html .=chr(13).'    <table width="99%" border="0">';
      $l_html .=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
      $l_html .=chr(13).'      <tr><td colspan="2" bgcolor="#f0f0f0"><font size=2>Ação: <b>'.f($RS,'titulo').'</b></font></td></tr>';
      $l_html .=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
      // Identificação da ação
      $l_html.=chr(13).'       <tr><td colspan="2"><br><font size="2"><b>IDENTIFICAÇÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  

      // Se a classificação foi informada, exibe.
      if (nvl(f($RS,'sq_cc'),'')>''){ 
        $l_html .=chr(13).'      <tr><td width="30%">Classificação:</b></td>';
        $l_html .=chr(13).'        <td>'.f($RS,'cc_nome').' </td></tr>';
      }
      // Se a iniciativa prioritária for informada, exibe.
      if (nvl(f($RS,'sq_orprioridade'),'')>'') {
        $l_html .=chr(13).'      <tr><td width="30%"><b>Iniciativa prioritária:</b></td>';
        $l_html .=chr(13).'        <td>'.f($RS,'nm_pri').'</td></tr>';
        if (nvl(f($RS,'cd_pri'),'')>'') {
          $l_html .=chr(13).' ('.f($RS,'cd_pri').')';
        } 
        if (nvl(f($RS,'resp_pri'),'')>'') {
//          $l_html .=chr(13).'      <tr><td valign="top" colspan="2">';
          $l_html .=chr(13).'      <tr><td valign="top"><b>Responsável iniciativa prioritária:</b></td>';
          $l_html .=chr(13).'        <td>'.f($RS,'resp_pri').' </td></tr>';
          if (nvl(f($RS,'fone_pri'),'')>''){
            $l_html .=chr(13).'    <tr><td><b>Telefone:</b></td>';
            $l_html .=chr(13).'      <td>'.f($RS,'fone_pri').' </td></tr>';
          }
        }  
        if (nvl(f($RS,'mail_ppa_pai'),'')>'') {
           $l_html .=chr(13).'      <tr><td><b><b>Email:</b></td>';
           $l_html .=chr(13).'        <td>'.f($RS,'mail_pri').' </td></tr>';
        } 
      } 
      $l_html .=chr(13).'          </b></td>';
      // Se a ação no PPA for informada, exibe.
      if (nvl(f($RS,'sq_acao_ppa'),'')>'') {
        $l_html .=chr(13).'      <tr><td valign="top"><b>Programa PPA:</b></td>';
        $l_html .=chr(13).'        <td>'.f($RS,'nm_ppa_pai').' ('.f($RS,'cd_ppa_pai').')'.' </td></tr>';
        if (nvl(f($RS,'resp_ppa_pai'),'')>'') {
          $l_html .=chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
          $l_html .=chr(13).'      <tr><td valign="top"><b>Gerente executivo:</b></td>';
          $l_html .=chr(13).'        <td>'.f($RS,'resp_ppa_pai').' </td></tr>';
          if (nvl(f($RS,'fone_ppa_pai'),'')>'') {
            $l_html .=chr(13).'    <tr><td><b>Telefone:</b></td>';
            $l_html .=chr(13).'        <td>'.f($RS,'fone_ppa_pai').' </td></tr>';
          } 
          if (nvl(f($RS,'mail_ppa_pai'),'')>'') {
            $l_html .=chr(13).'    <tr><td><b>Email:</b></td>';
            $l_html .=chr(13).'        <td>'.f($RS,'mail_ppa_pai').' </td></tr>';
          } 
          $l_html .=chr(13).'          </table>';
        } 
        $l_html .=chr(13).'      <tr><td valign="top"><b>Ação PPA:</b></td>';
        $l_html .=chr(13).'        <td>'.f($RS,'nm_ppa').' ('.f($RS,'cd_ppa').')'.' </b></td>';
        $l_html .=chr(13).'      <tr><td valign="top" colspan="2">';
        if (f($RS,'mpog_ppa')=='S') {
          $l_html .=chr(13).'    <tr><td><b>Selecionada MP:</b></td>';
          $l_html .=chr(13).'        <td>Sim</td></tr>';
        } else {
          $l_html .=chr(13).'    <tr><td><b>Selecionada MP:</b></td>';
          $l_html .=chr(13).'        <td>Não</td></tr>';
        } 
        if (f($RS,'relev_ppa')=='S') {
          $l_html .=chr(13).'    <tr><td><b>Selecionada SE/MS:</b></td>';
          $l_html .=chr(13).'        <td>Sim</td></tr>';
        } else {
          $l_html .=chr(13).'    <tr><td><b>Selecionada SE/MS:</b></td>';
          $l_html .=chr(13).'        <td>Não</td></tr>';
        } 
        if (nvl(f($RS,'resp_ppa'),'')>'') {
          $l_html .=chr(13).'      <tr><td valign="top" colspan="2">';
          $l_html .=chr(13).'      <tr><td valign="top">Coordenador:</b></td>';
          $l_html .=chr(13).'          <td>'.f($RS,'resp_ppa').' </b></td>';
          if (nvl(f($RS,'fone_ppa'),'')>'') {
            $l_html .=chr(13).'    <tr><td><b>Telefone:</b></td>';
            $l_html .=chr(13).'        <td>'.f($RS,'fone_ppa').' </td></tr>';
          } 
          if (nvl(f($RS,'mail_ppa'),'')>'') {
            $l_html .=chr(13).'    <tr><td><b>Email:</b></td>';
            $l_html .=chr(13).'        <td>'.f($RS,'mail_ppa').' </td></tr>';
          } 
        } 
      } 
      $l_html .=chr(13).'      <tr><td valign="top" colspan="2">';//<table border=0 width="100%" cellspacing=0>';
      //w_html = w_html & VbCrLf & '          <tr valign=''top''>'
      //w_html = w_html & VbCrLf & '          <td colspan=3>Abrangência da ação:(Quando Brasília-DF, impacto nacional. Quando a capital de um estado, impacto estadual.)<br><b>' & RS('nm_cidade') & ' (' & RS('co_uf') & ')</b></td>'
      $l_html .=chr(13).'          <tr valign="top">';
      $l_html .=chr(13).'            <td><b>Responsável monitoramento:</b></td>';
      $l_html .=chr(13).'            <td>'.ExibePessoa('../',$w_cliente,f($RS,'solicitante'),$TP,f($RS,'nm_sol')).'</td></tr>';
      $l_html .=chr(13).'          <tr><td><b>Setor responsável monitoramento:</b></td>';
      $l_html .=chr(13).'            <td>'.f($RS,'nm_unidade_resp').' </td></tr>';
      if ($w_tipo_visao==0) {
        // Se for visão completa
        $l_html .=chr(13).'          <td><b>Recurso programado:</b></td>';
        $l_html .=chr(13).'          <td>'.number_format(f($RS,'valor'),2,',','.').' </td></tr>';
      } 
      $l_html .=chr(13).'        <tr><td><b>Início previsto:</b></td>';
      $l_html .=chr(13).'          <td>'.FormataDataEdicao(f($RS,'inicio')).' </td></tr>';
      $l_html .=chr(13).'        <tr><td><b>Fim previsto:</b></td>';
      $l_html .=chr(13).'          <td>'.FormataDataEdicao(f($RS,'fim')).' </td></tr>';
      //w_html = w_html & VbCrLf & '          <td>Prioridade:<br><b>' & RetornaPrioridade(RS('prioridade')) & ' </b></td>'
      $l_html .=chr(13).'        <tr valign="top"><td><b>Parcerias externas:</b></td>';
      $l_html .=chr(13).'          <td>'.CRLF2BR(Nvl(f($RS,'proponente'),'---')).' </td></tr>';
      $l_html .=chr(13).'        <tr valign="top"><td><b>Parcerias internas:</b></td>';
      $l_html .=chr(13).'          <td>'.CRLF2BR(Nvl(f($RS,'palavra_chave'),'---')).' </td></tr>';
//      $l_html .=chr(13).'          </table>';
      if ($w_tipo_visao==0 || $w_tipo_visao==1) {
        // Informações adicionais
        $l_html .=chr(13).'       <tr><td colspan="2"><br><font size="2"><b>INFORMAÇÕES ADICIONAIS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
        $l_html .=chr(13).'      <tr><td valign="top"><b>Situação problema:</b></td>';
        $l_html .=chr(13).'        <td>'.CRLF2BR(Nvl(f($RS,'problema'),'---')).' </td></tr>';
        $l_html .=chr(13).'      <tr><td valign="top"><b>Objetivo da ação:</b></td>';
        $l_html .=chr(13).'        <td>'.CRLF2BR(Nvl(f($RS,'objetivo'),'---')).' </b></td>';
        $l_html .=chr(13).'      <tr><td valign="top"><b>Descrição da ação:</b></td>';
        $l_html .=chr(13).'        <td>'.CRLF2BR(Nvl(f($RS,'ds_acao'),'---')).' </td></tr>';
        $l_html .=chr(13).'      <tr><td valign="top"><b>Público alvo:</b></td>';
        $l_html .=chr(13).'        <td>'.CRLF2BR(Nvl(f($RS,'publico_alvo'),'---')).' </td></tr>';
        if (Nvl(f($RS,'descricao'),'')>'') {
          $l_html .=chr(13).'      <tr><td valign="top"><b>Resultados da ação:</b></td>';
        $l_html .=chr(13).'        <td>'.CRLF2BR(f($RS,'descricao')).' </td></tr>';
        } 
        $l_html .=chr(13).'      <tr><td valign="top"><b>Estratégia de implantação:</b></td>';
        $l_html .=chr(13).'        <td>'.CRLF2BR(Nvl(f($RS,'estrategia'),'---')).' </td></tr>';
        $l_html .=chr(13).'      <tr><td valign="top"><b>Indicadores de desempenho:</b></td>';
        $l_html .=chr(13).'        <td>'.CRLF2BR(Nvl(f($RS,'indicadores'),'---')).' </td></tr>';
        if ($w_tipo_visao==0 && Nvl(f($RS,'justificativa'),'')>'') {   
          // Se for visão completa
          $l_html .=chr(13).'      <tr><td valign="top"><b>Observações:</b></td>';
          $l_html .=chr(13).'        <td>'.CRLF2BR(f($RS,'justificativa')).' </td></tr>';
        } 
      } 
      if ($w_tipo_visao==0 || $w_tipo_visao==1) {
        // Outras iniciativas
        $RS1 = db_getOrPrioridadeList::getInstanceOf($dbms,$l_chave,$w_cliente,null);
        $RS1 = SortArray($RS1,'Existe','desc');
        if (count($RS1)>0) {
          $i=0;
          foreach($RS1 as $row1) {
            if (Nvl(f($row1,'Existe'),0)>0) {
              if ($i==0) {
                $l_html .=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>OUTRAS INICIATIVAS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
                $l_html .=chr(13).'      <tr><td align="center" colspan="2">';
                $l_html.=chr(13).'         <table width=100%  border="1" bordercolor="#00000">'; 
                $l_html .=chr(13).'          <tr align="center">';
                $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>Nome</b></div></td>';
                $l_html .=chr(13).'          </tr>';
              }
              $i=1;                
              $l_html .=chr(13).'      <tr valign="top">';
              $l_html .=chr(13).'        <td><ul><li>'.f($row1,'nome').'</td>';
              $l_html .=chr(13).'      </tr>';
            } 
          } 
        } 
      } 
      // Dados da conclusão da ação, se ela estiver nessa situação
      if (f($RS,'concluida')=='S' && Nvl(f($RS,'data_conclusao'),'')>'') {
        $l_html .=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS DA CONCLUSÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
        $l_html .=chr(13).'      <tr><td><b>Início da execução:</b></td>';
        $l_html .=chr(13).'        <td>'.FormataDataEdicao(f($RS,'inicio_real')).' </td></tr>';
        $l_html .=chr(13).'      <tr><td><b>Término da execução:</b></td>';
        $l_html .=chr(13).'        <td>'.FormataDataEdicao(f($RS,'fim_real')).' </td></tr>';
        if ($w_tipo_visao==0) {
          $l_html .=chr(13).'    <tr><td><b>Recurso executado:</b></td>';
          $l_html .=chr(13).'      <td>'.number_format(f($RS,'custo_real'),2,',','.').' </td></tr>';
        } 
        if ($w_tipo_visao==0) {
          $l_html .=chr(13).'     <tr><td valign="top"><b>Nota de conclusão:</b></td>';
          $l_html .=chr(13).'        <td>'.CRLF2BR(f($RS,'nota_conclusao')).' </td></tr>';
        } 
      } 
    } 
    if ($w_tipo_visao==0) {
      //Financiamento
      $RS1 = db_getFinancAcaoPPA::getInstanceOf($dbms,$l_chave,RetornaCliente(),null);
      if (f($RS,'cd_ppa')>'') {
        $l_html .=chr(13).'       <tr><td colspan="2"><br><font size="2"><b>FINANCIAMENTO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
        $l_html .=chr(13).'      <tr><td align="center" colspan="2">';
        $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';    
        $l_html.=chr(13).'          <tr align="center">';
        $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>Código</b></div></td>';
        $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>Nome</b></div></td>';
        $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>Dotação autorizada</b></div></td>';
        $l_html .=chr(13).'          </tr>';
        $l_html .=chr(13).'      <tr valign="top">';
        $l_html .=chr(13).'        <td>'.f($RS,'cd_ppa_pai').'.'.f($RS,'cd_ppa').'</td>';
        $l_html .=chr(13).'        <td>'.f($RS,'nm_ppa').'</td>';
        $l_html .=chr(13).'        <td align="right">'.number_format(f($RS,'aprovado'),2,',','.').'</td>';
        $l_html .=chr(13).'      </tr>';
        if (count($RS1)>0) {
          foreach($RS1 as $row1) {
            $l_html .=chr(13).'      <tr valign="top">';
            $l_html .=chr(13).'        <td>'.f($row1,'cd_ppa_pai').'.'.f($row1,'cd_ppa').'</td>';
            $l_html .=chr(13).'        <td>'.f($row1,'nome').'</td>';
            $l_html .=chr(13).'        <td align="right">'.number_format(f($row1,'aprovado'),2,',','.').'</td>';
            $l_html .=chr(13).'      </tr>';
            $l_html .=chr(13).'      <tr valign="top">';
            $l_html .=chr(13).'        <td colspan=3><DD><b>Observação:</b> '.Nvl(f($row1,'observacao'),'---').'</DD></td>';
            $l_html .=chr(13).'      </tr>';
          } 
        } 
        $l_html .=chr(13).'         </table></td></tr>';
      } elseif (count($RS1)>0) {
        $l_html .=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>FINANCIAMENTO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
        $l_html .=chr(13).'      <tr><td align="center" colspan="2">';
        $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';    
        $l_html.=chr(13).'          <tr align="center">';        
          $l_html .=chr(13).'         <td bgColor="#f0f0f0"><div><b>Código</b></div></td>';
        $l_html .=chr(13).'           <td bgColor="#f0f0f0"><div><b>Nome</b></div></td>';
       $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>Aprovado</b></div></td>';
        $l_html .=chr(13).'          </tr>';
        foreach ($RS1 as $row1) {
          $l_html .=chr(13).'      <tr valign="top">';
          $l_html .=chr(13).'        <td>'.f($row1,'cd_ppa_pai').'.'.f($row1,'cd_ppa').'</td>';
          $l_html .=chr(13).'        <td>'.f($row1,'nome').'</td>';
          $l_html .=chr(13).'        <td align="right">'.number_format(f($row1,'aprovado'),2,',','.').'</td>';
          $l_html .=chr(13).'      </tr>';
          $l_html .=chr(13).'      <tr valign="top" bgcolor="'.$w_cor.'">';
          $l_html .=chr(13).'        <td colspan=3><DD><b>Observação:</b> '.Nvl(f($row1,'observacao'),'---').'</DD></td>';
          $l_html .=chr(13).'      </tr>';  
        }
        $l_html .=chr(13).'         </table></td></tr>';
      } 
    } 
    // Se for listagem, exibe os outros dados dependendo do tipo de visão  do usuário
    if ($O=='L' && $w_tipo_visao!=2) {
      if (f($RS,'aviso_prox_conc')=='S') {
        // Configuração dos alertas de proximidade da data limite para conclusão da demanda
        $l_html .=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ALERTA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
        $l_html .=chr(13).'      <tr><td colspan="2">Será enviado aviso a partir de <b>'.f($RS,'dias_aviso').'</b> dias antes de <b>'.FormataDataEdicao(f($RS,'fim')).'</b></td></tr>';
        //w_html = w_html & VbCrLf & '      <tr><td valign=''top'' colspan=''2''><table border=0 width=''100''' cellspacing=0>'
        //w_html = w_html & VbCrLf & '          <td valign=''top''>Emite aviso:<br><b>' & Replace(Replace(RS('aviso_prox_conc'),'S','Sim'),'N','Não') & ' </b></td>'
        //w_html = w_html & VbCrLf & '          <td valign=''top''>Dias:<br><b>' & RS('dias_aviso') & ' </b></td>'
        //w_html = w_html & VbCrLf & '          </table>'
      } 
      // Interessados na execução da ação    
      $RS = db_getSolicInter::getInstanceOf($dbms,$l_chave,null,'LISTA');
      $RS = SortArray($RS,'nome_resumido','asc');
      if (count($RS)>0) {
        $TP=RemoveTP($TP).' - Interessados';
        $l_html .=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>INTERESSADOS NA EXECUÇÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
        $l_html .=chr(13).'      <tr><td valign="top" colspan="2" align="center"><b><center><B>Clique <a class="HL" HREF="'.$w_dir.'projeto.php?par=interess&R='.$w_Pagina.$par.'&O=L&w_chave='.$l_chave.'&P1=4&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" target="_blank">aqui</a> para visualizar os Interessados na execução</b></center></td>';
        //w_html = w_html & VbCrLf & '      <tr><td align=''center'' colspan=''2''>'
        //w_html = w_html & VbCrLf & '        <TABLE WIDTH=''100''' bgcolor=''' & conTableBgColor & ''' BORDER=''' & conTableBorder & ''' CELLSPACING=''' & conTableCellSpacing & ''' CELLPADDING=''' & conTableCellPadding & ''' BorderColorDark=''' & conTableBorderColorDark & ''' BorderColorLight=''' & conTableBorderColorLight & '''>'
        //w_html = w_html & VbCrLf & '          <tr bgcolor=''' & conTrBgColor & ''' align=''center''>'
        //w_html = w_html & VbCrLf & '            <td><b>Nome</td>'
        //w_html = w_html & VbCrLf & '            <td><b>Tipo de visão</td>'
        //w_html = w_html & VbCrLf & '            <td><b>Envia e-mail</td>'
        //w_html = w_html & VbCrLf & '          </tr>'    
        //While Not Rs.EOF
        //  If w_cor = conTrBgColor or w_cor = '' Then w_cor = conTrAlternateBgColor Else w_cor = conTrBgColor End If
        //  w_html = w_html & VbCrLf & '      <tr valign=''top'' bgcolor=''' & w_cor & '''>'
        //  w_html = w_html & VbCrLf & '        <td>& RS('nome_resumido') & '</td>'
        //  w_html = w_html & VbCrLf & '        <td><font size=''1''>' & RetornaTipoVisao(RS('tipo_visao')) & '</td>'
        //  w_html = w_html & VbCrLf & '        <td align=''center''><font size=''1''>' & Replace(Replace(RS('envia_email'),'S','Sim'),'N','Não') & '</td>'
        //  w_html = w_html & VbCrLf & '      </tr>'
        //  Rs.MoveNext
        //wend
        //w_html = w_html & VbCrLf & '         </table></td></tr>'
      } 
      // Áreas envolvidas na execução da ação
      $RS = db_getSolicAreas::getInstanceOf($dbms,$l_chave,null,'LISTA');
      $RS = SortArray($RS,'nome','asc');
      if (count($RS)>0) {
        $l_html .=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ÁREAS/INSTITUIÇÕES ENVOLVIDAS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
        $l_html .=chr(13).'      <tr><td align="center" colspan="2">';
        $l_html.=chr(13).'         <table width=100%  border="1" bordercolor="#00000">'; 
        $l_html .=chr(13).'          <tr align="center">';
        $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>Nome</b></div></td>';
        $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>Papel</b></div></td>';
        $l_html .=chr(13).'          </tr>';
        foreach($RS as $row) {
          $l_html .=chr(13).'      <tr valign="top">';
          $l_html .=chr(13).'        <td>'.f($row,'nome').'</td>';
          $l_html .=chr(13).'        <td>'.f($row,'papel').'</td>';
          $l_html .=chr(13).'      </tr>';
        } 
        $l_html .=chr(13).'         </table></td></tr>';
      } 
      // Etapas da ação    
      // Recupera todos os registros para a listagem
      $RS = db_getSolicEtapa::getInstanceOf($dbms,$l_chave,null,'LISTA',null);
      $RS = SortArray($RS,'ordem','asc');
      // Recupera o código da opção de menu  a ser usada para listar as atividades
      $w_p2='';
      foreach($RS as $row) {
        if (Nvl(f($row,'P2'),0)>0) $w_p2=f($row,'P2');
      } 
      $RS = db_getSolicEtapa::getInstanceOf($dbms,$l_chave,null,'LSTNULL',null);
      $RS = SortArray($RS,'ordem','asc');
      if (count($RS)>0) {
        // Se não foram selecionados registros, exibe mensagem
        // Monta função JAVASCRIPT para fazer a chamada para a lista de atividades
        if ($w_p2>'') {
          $l_html .=chr(13).'<SCRIPT LANGUAGE="JAVASCRIPT">';
          $l_html .=chr(13).'  function lista (projeto, etapa) {';
          $l_html .=chr(13).'    document.Form.p_projeto.value=projeto;';
          $l_html .=chr(13).'    document.Form.p_atividade.value=etapa;';
          $l_html .=chr(13).'    document.Form.p_agrega.value=\'GRDMETAPA\';';
          $RS1 = db_getTramiteList::getInstanceOf($dbms,$w_P2,null,null);
          $RS1 = SortArray($RS1,'ordem','asc');
          $l_html .=chr(13).'    document.Form.p_fase.value=\'\';';
          $w_fases='';
          foreach($RS1 as $row1) {
            if (f($row1,'sigla')!='CA') {
              $w_fases=$w_fases.','.f($row1,'sq_siw_tramite');
            } 
          } 
          $l_html .=chr(13).'    document.Form.p_fase.value=\'\'.substr($w_fases,1,100).\'\';';
          $l_html .=chr(13).'    document.Form.submit();';
          $l_html .=chr(13).'  }';
          $l_html .=chr(13).'</SCRIPT>';
          $RS1 = db_getMenuData::getInstanceOf($dbms,$w_p2);
          AbreForm('Form',f($RS1,'link'),'POST','return(Validacao(this));','Atividades',3,$w_P2,1,null,$w_TP,f($RS1,'sigla'),$w_pagina.$par,'L');
          $l_html .=chr(13).MontaFiltro('POST');
          $l_html .=chr(13).'<input type="Hidden" name="p_projeto" value="">';
          $l_html .=chr(13).'<input type="Hidden" name="p_atividade" value="">';
          $l_html .=chr(13).'<input type="Hidden" name="p_agrega" value="">';
          $l_html .=chr(13).'<input type="Hidden" name="p_fase" value="">';
        } 
        $l_html .=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>METAS FÍSICAS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
        $l_html .=chr(13).'      <tr><td align="center" colspan="2">';
        $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';    
        $l_html.=chr(13).'          <tr align="center">';
        $l_html .=chr(13).'          <td bgColor="#f0f0f0"><div><b>Metas</b></div></td>';
        //w_html = w_html & VbCrLf & '          <td><b>Produto</font></td>'
        //w_html = w_html & VbCrLf & '          <td rowspan=2><b>Responsável</td>'
        //w_html = w_html & VbCrLf & '          <td rowspan=2><b>Setor</td>'
        $l_html .=chr(13).'          <td bgColor="#f0f0f0"><div><b>Execução até</b></div></td>';
        $l_html .=chr(13).'          <td bgColor="#f0f0f0"><div><b>Conc.</b></div></td>';
        //w_html = w_html & VbCrLf & '          <td rowspan=2><b>Ativ.</td>'
        $l_html .=chr(13).'          </tr>';
        //w_html = w_html & VbCrLf & '          <tr bgcolor=''' & conTrBgColor & ''' align=''center''>'
        //w_html = w_html & VbCrLf & '          <td><b>De</td>'
        //w_html = w_html & VbCrLf & '          <td><b>Até</td>'
        //w_html = w_html & VbCrLf & '          </tr>'
        foreach($RS as $row) {
          $l_html .=chr(13).EtapaLinha($l_chave,f($row,'sq_projeto_etapa'),f($row,'titulo'),f($row,'nm_resp'),f($row,'sg_setor'),f($row,'inicio_previsto'),f($row,'fim_previsto'),f($row,'perc_conclusao'),f($row,'qt_ativ'),'<b>',null,'PROJETO');
          // Recupera as etapas vinculadas ao nível acima
          $RS1 = db_getSolicEtapa::getInstanceOf($dbms,$l_chave,f($row,'sq_projeto_etapa'),'LSTNIVEL',null);
          $RS1 = SortArray($RS1,'ordem','asc');
          foreach($RS1 as $row1) {
            $l_html .=chr(13).EtapaLinha($l_chave,f($row1,'sq_projeto_etapa'),f($row1,'titulo'),f($row1,'nm_resp'),f($row1,'sg_setor'),f($row1,'inicio_previsto'),f($row1,'fim_previsto'),f($row1,'perc_conclusao'),f($row1,'qt_ativ'),null,null,'PROJETO');
             // Recupera as etapas vinculadas ao nível acima
            $RS2 = db_getSolicEtapa::getInstanceOf($dbms,$l_chave,f($row1,'sq_projeto_etapa'),'LSTNIVEL',null);
            $RS2 = SortArray($RS2,'ordem','asc');
            foreach($RS2 as $row2) {
              $l_html .=chr(13).EtapaLinha($l_chave,f($row2,'sq_projeto_etapa'),f($row2,'titulo'),f($row2,'nm_resp'),f($row2,'sg_setor'),f($row2,'inicio_previsto'),f($row2,'fim_previsto'),f($row2,'perc_conclusao'),f($row2,'qt_ativ'),'<b>',null,'PROJETO');
              // Recupera as etapas vinculadas ao nível acima
              $RS3 = db_getSolicEtapa::getInstanceOf($dbms,$l_chave,f($row2,'sq_projeto_etapa'),'LSTNIVEL',null);
              $RS3 = SortArray($RS3,'ordem','asc');
              foreach($RS3 as $row3) {
                $l_html .=chr(13).EtapaLinha($l_chave,f($row3,'sq_projeto_etapa'),f($row3,'titulo'),f($row3,'nm_resp'),f($row3,'sg_setor'),f($row3,'inicio_previsto'),f($row3,'fim_previsto'),f($row3,'perc_conclusao'),f($row3,'qt_ativ'),'<b>',null,'PROJETO');
                  // Recupera as etapas vinculadas ao nível acima
                $RS4 = db_getSolicEtapa::getInstanceOf($dbms,$l_chave,f($row3,'sq_projeto_etapa'),'LSTNIVEL',null);
                $RS4 = SortArray($RS4,'ordem','asc');
                foreach($RS4 as $row4) {
                  $l_html .=chr(13).EtapaLinha($l_chave,f($row4,'sq_projeto_etapa'),f($row4,'titulo'),f($row4,'nm_resp'),f($row4,'sg_setor'),f($row4,'inicio_previsto'),f($row4,'fim_previsto'),f($row4,'perc_conclusao'),f($row4,'qt_ativ'),'<b>',null,'PROJETO');
                } 
              } 
            } 
          } 
        } 
        $l_html .=chr(13).'      </form>';
        $l_html .=chr(13).'      </center>';
        $l_html .=chr(13).'         </table></td></tr>';
      } 
      // Listagem das tarefas na visualização da ação, rotina adquirida apartir da rotina exitente na projetoativ.php para listagem das tarefas
      $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'ORPCAD');
      $RS = db_getSolicList::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,'ORPCAD',5,
              null,null,null,null,null,null,null,null,null,null,
              null,null,null,null,null,null,null,
              null,null,null,null,null,$l_chave,null,null,null);
      $RS = SortArray($RS,'ordem','asc','phpdt_fim','asc','prioridade','asc');
      if (count($RS)>0) {
        $l_html .=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>TAREFAS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
        $l_html .=chr(13).'      <tr><td align="center" colspan="2">';
     //   $l_html .=chr(13).'        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
       $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">'; 
        $l_html .=chr(13).'          <tr align="center">';
        $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>Nº</b></div></td>';
        $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>Responsável</b></div></td>';
        $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>Detalhamento</b></div></td>';
        $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>Fim previsto</b></div></td>';
        $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>Fase atual</b></div></td>';
        $l_html .=chr(13).'          </tr>';
        foreach($RS as $row) {
//          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;$l_html .=chr(13).'       <tr valign="top" bgcolor="'.$w_cor.'">';
          $l_html .=chr(13).'       <tr valign="top">';
          $l_html .=chr(13).'         <td nowrap>';
          if (f($row,'concluida')=='N') {
            if (f($row,'fim')<time()) {
              $l_html .=chr(13).'          <img src="'.$conImgAtraso.'" border=0 width=15 heigth=15 align="center">';
            } elseif (f($row,'aviso_prox_conc')=='S' && (f($row,'aviso')<=time())) {
              $l_html .=chr(13).'          <img src="'.$conImgAviso.'" border=0 width=15 height=15 align="center">';
            } else {
              $l_html .=chr(13).'          <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">';
            } 
          } else {
            if (f($row,'fim')<Nvl(f($row,'fim_real'),f($row,'fim'))) {
              $l_html .=chr(13).'          <img src="'.$conImgOkAtraso.'" border=0 width=15 heigth=15 align="center">';
            } else {
              $l_html .=chr(13).'          <img src="'.$conImgOkNormal.'" border=0 width=15 height=15 align="center">';
            } 
          } 
          $l_html .=chr(13).'         <A class="HL" HREF="'.$w_dir.'projetoativ.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro." target="_blank">'.f($row,'sq_siw_solicitacao').'&nbsp;</a>';
          $l_html .=chr(13).'         <td>'.ExibePessoa('../',$w_cliente,f($row,'solicitante'),$TP,f($row,'nm_solic')).'</td>';
          if (strlen(Nvl(f($row,'assunto'),'-'))>50) $w_titulo=substr(Nvl(f($row,'assunto'),'-'),0,50).'...'; else $w_titulo=Nvl(f($row,'assunto'),'-');         
          if (f($RS,'sg_tramite')=='CA') {
            $l_html .=chr(13).'      <td><strike>'.$w_titulo.'</strike></td>';
          } else {
            $l_html .=chr(13).'      <td>'.$w_titulo.'</td>';
          } 
          $l_html .=chr(13).'         <td align="center">&nbsp;'.Nvl(FormatDateTime(f($RS,'fim'),2,',','.'),'-').'</td>';
          $l_html .=chr(13).'         <td align="center"  nowrap>'.Nvl(f($RS,'nm_tramite'),'-').'</td>';
        } 
  //      $l_html .=chr(13).'         </table></td></tr>';
      } 
      // Recursos envolvidos na execução da ação
      $RS = db_getSolicRecurso::getInstanceOf($dbms,$l_chave,null,'LISTA');
      $RS = SortArray($RS,'tipo','asc','nome','asc');
      if (count($RS)>0) {
        $l_html .=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>RECURSOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
        $l_html .=chr(13).'      <tr><td align="center" colspan="2">';
        $l_html.=chr(13).'         <table width=100%  border="1" bordercolor="#00000">'; 
        $l_html .=chr(13).'          <tr align="center">';
        $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>Tipo</b></div></td>';
        $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>Nome</b></div></td>';
        $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>Finalidade</b></div></td>';
        $l_html .=chr(13).'          </tr>';
        foreach($RS as $row) {
          $l_html .=chr(13).'      <tr valign="top" ">';
          $l_html .=chr(13).'        <td>'.RetornaTipoRecurso(f($row,'tipo')).'</td>';
          $l_html .=chr(13).'        <td>'.f($row,'nome').'</td>';
          $l_html .=chr(13).'        <td>'.CRLF2BR(Nvl(f($row,'finalidade'),'---')).'</td>';
          $l_html .=chr(13).'      </tr>';
        } 
      } 
      $l_html .=chr(13).'         </table></td></tr>';
    } 
    if ($O=='L' || $O=='V') {
      // Se for listagem dos dados 
      if ($w_tipo_visao!=2) {
        // Arquivos vinculados
        $RS = db_getSolicAnexo::getInstanceOf($dbms,$l_chave,null,$w_cliente);
        $RS = SortArray($RS,'nome','asc');
        if (count($RS)>0) {
          $l_html .=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ARQUIVOS ANEXOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
          $l_html .=chr(13).'      <tr><td align="center" colspan="2">';
          $l_html.=chr(13).'         <table width=100%  border="1" bordercolor="#00000">'; 
          $l_html .=chr(13).'          <tr align="center">';
          $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>Título</b></div></td>';
          $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>Descrição</b></div></td>';
          $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>Tipo</b></div></td>';
          $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>KB</b></div></td>';
          $l_html .=chr(13).'          </tr>';
          $w_cor=$conTrBgColor;
          foreach($RS as $row) {
            $l_html .=chr(13).'      <tr valign="top">';
            $l_html .=chr(13).'        <td>'.LinkArquivo('HL',$w_cliente,f($row,'chave_aux'),'_blank','Clique para exibir o arquivo em outra janela.',f($row,'nome'),null).'</td>';
            $l_html .=chr(13).'        <td>'.Nvl(f($row,'descricao'),'---').'</td>';
            $l_html .=chr(13).'        <td>'.f($row,'tipo').'</td>';
            $l_html .=chr(13).'        <td align="right">'.round((f($row,'tamanho')/1024),1).'&nbsp;</td>';
            $l_html .=chr(13).'      </tr>';
          } 
          $l_html .=chr(13).'         </table></td></tr>';
        } 
      } 
      // Encaminhamentos
      $RS = db_getSolicLog::getInstanceOf($dbms,$l_chave,null,'LISTA');
      $RS = SortArray($RS,'phpdt_data','desc','sq_siw_solic_log','desc');
      $l_html .=chr(13).'     <tr><td colspan="2"><br><font size="2"><b>OCORRÊNCIA E ANOTAÇÕES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
      $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">';
      $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';    
      $l_html.=chr(13).'          <tr align="center">';
      $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>Data</b></div></td>';
      $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>Despacho/Observação</b></div></td>';
      $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>Responsável</b></div></td>';
      $l_html .=chr(13).'            <td bgColor="#f0f0f0"><div><b>Fase / Destinatário</b></div></td>';
      $l_html .=chr(13).'          </tr>';
      if (count($RS)<=0) {
        $l_html .=chr(13).'      <tr><td colspan=6 align="center"><b>Não foram encontrados encaminhamentos.</b></td></tr>';
      } else {
        $l_html .=chr(13).'      <tr valign="top">';
        $i=0;
        foreach($RS as $row) {
          if($i==0) $l_html .=chr(13).'        <td colspan=6>Fase atual: <b>'.f($row,'fase').'</b></td>';
          $i=1;
          $l_html .=chr(13).'      <tr valign="top">';
          //$l_html .=chr(13).'        <td nowrap>>'.$FormatDateTime(f($row,'data'),2,',','.').', '.$FormatDateTime(f($row,'data'),4,',','.').'</td>';
          $l_html .=chr(13).'        <td nowrap>'.FormataDataEdicao(f($row,'phpdt_data'),3).'</td>';
          
        //  FormataDataEdicao(f($row,'phpdt_data'),3)
          $l_html .=chr(13).'        <td>'.CRLF2BR(Nvl(f($row,'despacho'),'---')).'</td>';
          $l_html .=chr(13).'        <td nowrap>'.ExibePessoa('../',$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'responsavel')).'</td>';
          if ((nvl(Tvl(f($row,'sq_projeto_log')),'')>'') && (nvl(Tvl(f($row,'destinatario')),'')>'')) {
            $l_html .=chr(13).'        <td nowrap>'.ExibePessoa('../',$w_cliente,f($row,'sq_pessoa_destinatario'),$TP,f($row,'destinatario')).'</td>';
          } elseif ((nvl(Tvl(f($row,'sq_projeto_log')),'')>'') && nvl(Tvl(f($row,'destinatario')),'')>'') {
            $l_html .=chr(13).'        <td nowrap>Anotação</td>';
          } else {
            $l_html .=chr(13).'        <td nowrap>'.Nvl(f($row,'tramite'),'---').'</td>';
          } 
          $l_html .=chr(13).'      </tr>';
        } 
      $l_html .=chr(13).'         </table></td></tr>';
      } 
      $l_html .=chr(13).'</table>';
    } 
  }
  return $l_html;
} 
?>


