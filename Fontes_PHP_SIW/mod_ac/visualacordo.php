<?
// =========================================================================
// Rotina de visualização dos dados do acordo
// -------------------------------------------------------------------------
function VisualAcordo($l_chave,$l_O,$l_usuario,$l_P1,$l_P4) {
  extract($GLOBALS);
  if ($l_P4==1) $w_TrBgColor=''; else $w_TrBgColor=$conTrBgColor;
  $w_html='';
  
  // Carrega o segmento do cliente
  $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente); 
  $w_segmento = f($RS,'segmento');
  
  // Recupera os dados do acordo
  $RS = db_getSolicData::getInstanceOf($dbms,$l_chave,substr($SG,0,3).'GERAL');
  $w_tramite        = f($RS,'sq_siw_tramite');
  $w_valor_inicial  = f($RS,'valor');
  $w_fim            = f($RS,'fim_real');
  $w_sg_tramite     = f($RS,'sg_tramite');
  $w_sigla          = f($RS,'sigla');
  $w_aditivo        = f($RS,'aditivo');

  // Recupera o tipo de visão do usuário
  if ($_SESSION['INTERNO']=='N') {
    // Se for usuário externa, tem visão resumida
    $w_tipo_visao=2;
  } elseif (Nvl(f($RS,'solicitante'),0)==$l_usuario || 
    Nvl(f($RS,'executor'),0)==$l_usuario || 
    Nvl(f($RS,'cadastrador'),0)==$l_usuario || 
    Nvl(f($RS,'titular'),0)==$l_usuario || 
    Nvl(f($RS,'substituto'),0)==$l_usuario || 
    Nvl(f($RS,'tit_exec'),0)==$l_usuario || 
    Nvl(f($RS,'subst_exec'),0)==$l_usuario || 
    SolicAcesso($l_chave,$l_usuario)>=8) {
      // Se for solicitante, executor ou cadastrador, tem visão completa
      $w_tipo_visao=0;
  } else {
    if (SolicAcesso($l_chave,$l_usuario)>2) $w_tipo_visao=1;
  } 
  // Se for listagem ou envio, exibe os dados de identificação do acordo
  // Se for listagem dos dados
  if ($l_O=='L' || $l_O=='V') {
    $w_html.=chr(13).'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
    $w_html.=chr(13).'<tr><td>';
    $w_html.=chr(13).'    <table width="99%" border="0">';
    if (!($l_P1==4 || $l_P4==1)) {
      $w_html.=chr(13).'       <tr><td align="right" colspan="2"><b><A class="hl" HREF="'.$w_dir.$w_pagina.'visual&O=T&w_chave='.f($RS,'sq_siw_solicitacao').'&w_tipo=volta&P1=4&P2='.$P2.'&P3='.$P3.'&P4='.$l_P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe todas as informações.">Exibir todas as informações</a></td>';
    } 
    $w_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    if ($w_segmento=='Público' && (substr($w_sigla,0,3)=='GCA' || substr($w_sigla,0,3)=='GCD' || substr($w_sigla,0,3)=='GCZ')) { 
      if (substr($w_sigla,0,3)=='GCA') $w_html.=chr(13).'      <tr><td colspan="2" bgcolor="#f0f0f0"><div align=justify><font size="2"><b>PROCESSO: '.nvl(f($RS,'processo'),'---').' ACT: '.f($RS,'codigo_interno').' - '.f($RS,'titulo').' ('.$l_chave.')'.'</b></font></div></td></tr>';
      else                        $w_html.=chr(13).'      <tr><td bgcolor="#f0f0f0"><font size="2"><b>PROCESSO: '.nvl(f($RS,'processo'),'---').'<td bgcolor="#f0f0f0" align="right"><font size=2><b>CONTRATO: '.f($RS,'codigo_interno').' - '.f($RS,'titulo').' ('.$l_chave.')'.'</b></font></td></tr>';
    } else {
      if (substr($w_sigla,0,3)=='GCA') $w_html.=chr(13).'      <tr><td colspan="2" bgcolor="#f0f0f0"><div align=justify><font size="2"><b>ACT: '.f($RS,'codigo_interno').' - '.f($RS,'titulo').' ('.$l_chave.')'.'</b></font></div></td></tr>';
      else                        $w_html.=chr(13).'      <tr><td colspan="2" bgcolor="#f0f0f0"><div align=justify><font size="2"><b>CONTRATO: '.f($RS,'codigo_interno').' - '.f($RS,'titulo').' ('.$l_chave.')'.'</b></font></div></td></tr>';
    }
    $w_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    // Identificação do acordo
    $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>IDENTIFICAÇÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
    
    // Exibe a vinculação
    $w_html.=chr(13).'      <tr><td valign="top"><b>Vinculação: </b></td>';
    $w_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS,'sq_solic_pai'),f($RS,'dados_pai'),'S').'</td></tr>';

    if (nvl(f($RS,'nm_etapa'),'')>'') {
      if (substr($w_sigla,0,3)=='GCB') {   
        $w_html.=chr(13).'      <tr valign="top"><td><b>Modalidade: </b></td>';
        $w_html.=chr(13).'          <td>      '.f($RS,'nm_etapa').'</td></tr>';
      } else { 
        $w_html.=chr(13).'      <tr valign="top"><td><b>Etapa: </b></td>';
        $w_html.=chr(13).'          <td>      '.f($RS,'nm_etapa').'</td></tr>';
      }
    } 

    // Se a classificação foi informada, exibe.
    if (Nvl(f($RS,'sq_cc'),'')>'') {
      $w_html .= chr(13).'      <tr><td width="30%"><b>Classificação:<b></td>';
      $w_html .= chr(13).'        <td>'.f($RS,'nm_cc').' </td></tr>';
    }
    
    if (substr($w_sigla,0,3)=='GCB'){ 
      $w_html.=chr(13).'      <tr valign="top">';
      $w_html.=chr(13).'        <td><b><font size=1>Plano de trabalho: </b></td>';
      $w_html.=chr(13).'        <td>'.CRLF2BR(f($RS,'objeto')).'</td></tr>';
    } else {                        
      $w_html.=chr(13).'      <tr valign="top">';
      $w_html.=chr(13).'        <td><b><font size=1>Objeto: </b></td>';
      $w_html.=chr(13).'        <td>'.CRLF2BR(f($RS,'objeto')).'</td></tr>';
    }
    $w_html.=chr(13).'      <tr><td valign="top"><b>Tipo:</b></td>';
    $w_html.=chr(13).'          <td>'.f($RS,'nm_tipo_acordo').'</td></tr>';
    $w_html.=chr(13).'      <tr><td><b>Cidade de origem:</b></td>';
    $w_html.=chr(13).'          <td>'.f($RS,'nm_cidade').' ('.f($RS,'co_uf').')</td></tr>';
    if (!$l_P4==1) {
      $w_html.=chr(13).'          <tr><td><b>Responsável monitoramento:</b></td>';
      $w_html.=chr(13).'              <td>'.ExibePessoa($w_dir_volta,$w_cliente,f($RS,'solicitante'),$TP,f($RS,'nm_solic')).'</b></td>';
      $w_html.=chr(13).'          <tr><td><b>Unidade responsável monitoramento:</b></td>';
      $w_html.=chr(13).'              <td>'.ExibeUnidade($w_dir_volta,$w_cliente,f($RS,'nm_unidade_resp'),f($RS,'sq_unidade'),$TP).'</b></td>';
    } else {
      $w_html.=chr(13).'          <tr><td><b>Responsável monitoramento:</b></td>';
      $w_html.=chr(13).'              <td>'.f($RS,'nm_solic').'</td></tr>';
      $w_html.=chr(13).'          <tr><td><b>Unidade responsável monitoramento:</b></td>';
      $w_html.=chr(13).'              <td>'.f($RS,'nm_unidade_resp').'</td></tr>';
    } 
    // Se for visão completa
    if ($w_tipo_visao==0 && substr($w_sigla,0,3)!='GCA') {
      $w_html.=chr(13).'          <tr><td valign="top"><b>Valor:</b></td>';
      $w_html.=chr(13).'              <td>'.formatNumber(f($RS,'valor')).' </td></tr>';
    } 
    if(substr($w_sigla,0,3)=='GCD' || substr($w_sigla,0,3)=='GCZ') {
      $w_html.=chr(13).'          <tr><td><b>Vigência:</b></td>';
      $w_html.=chr(13).'              <td>'.FormataDataEdicao(f($RS,'inicio')).' a '.FormataDataEdicao(f($RS,'fim')).' (contrato e aditivos)</td></tr>';
      $w_html.=chr(13).'          <tr valign="top">';
      $w_html.=chr(13).'              <td><b>Assinatura do contrato:</b></td>';
      $w_html.=chr(13).'              <td>'.Nvl(FormataDataEdicao(f($RS,'assinatura')),'---').'</td></tr>';
      if (substr($w_sigla,0,3)!='GCB') { 
        $w_html.=chr(13).'          <tr valign="top">';
        $w_html.=chr(13).'              <td><b>Publicação D.O.:</b></td>';
        $w_html.=chr(13).'              <td>'.Nvl(FormataDataEdicao(f($RS,'publicacao')),'---').'</td></tr>';
        $w_html.=chr(13).'          <tr><td><b>Página D.O.:</b></td>';
        $w_html.=chr(13).'              <td>'.nvl(CRLF2BR(f($RS,'numero_certame')),'---').'</td></tr>';
      }        
    } else {
      $w_html.=chr(13).'          <tr><td><b>Vigência:</b></td>';
      $w_html.=chr(13).'              <td>'.FormataDataEdicao(f($RS,'inicio')).' a '.FormataDataEdicao(f($RS,'fim')).'</td></tr>';
    }
    if ($w_tipo_visao==0 || $w_tipo_visao==1) {
      // Informações adicionais
      if (Nvl(f($RS,'descricao'),'')>'' || Nvl(f($RS,'justificativa'),'')>'') {
        if (Nvl(f($RS,'descricao'),'')>''){
           $w_html.=chr(13).'      <tr><td valign="top"><b>Resultados esperados:</b></td>';
           $w_html.=chr(13).'          <td>'.CRLF2BR(f($RS,'descricao')).'</td></tr>';
        }
        if ($w_tipo_visao==0 && Nvl(f($RS,'justificativa'),'')>'') {
          $w_html.=chr(13).'      <tr><td valign="top"><b>Observações:</b></td>';
          $w_html.=chr(13).'          <td>'.CRLF2BR(f($RS,'justificativa')).'</td></tr>';
        } 
      }
     } 

    if ($w_tipo_visao==0 || $w_tipo_visao==1) {
      // Dados Adicionais
      if($w_segmento=='Público' && (substr($w_sigla,0,3)=='GCD' || substr($w_sigla,0,3)=='GCZ')) {
        $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS ADICIONAIS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
        if (substr($w_sigla,0,3)!='GCZ') {
          $w_html.=chr(13).'      <tr><td><b>Fonte de recurso:</b></td>';
          $w_html.=chr(13).'        <td>'.nvl(CRLF2BR(f($RS,'nm_lcfonte_recurso')),'---').'</td></tr>';
          $w_html.=chr(13).'      <tr><td><b>Especificação de despesa:</b></td>';
          $w_html.=chr(13).'        <td>'.nvl(CRLF2BR(f($RS,'nm_espec_despesa')),'---').'</td></tr>';
        }
        $w_html.=chr(13).'      <tr valign="top">';
        $w_html.=chr(13).'          <td><b>Modalidade:</b></td>';
        $w_html.=chr(13).'          <td>'.Nvl(f($RS,'nm_lcmodalidade'),'---').'</td></tr>';
        $w_html.=chr(13).'      <tr><td ><b>Número do certame:</b></td>';
        $w_html.=chr(13).'        <td>'.nvl(CRLF2BR(f($RS,'numero_certame')),'---').'</td></tr>';
        if (substr($w_sigla,0,3)!='GCZ') {
          $w_html.=chr(13).'      <tr><td ><b>Número da ata:</b></td>';
          $w_html.=chr(13).'        <td>'.nvl(CRLF2BR(f($RS,'numero_ata')),'---').'</td></tr>';
          $w_html.=chr(13).'      <tr><td><b>Tipo de reajuste:</b></td>';
          $w_html.=chr(13).'        <td>'.nvl(CRLF2BR(f($RS,'nm_tipo_reajuste')),'---').'</td></tr>';
          if(nvl(f($RS,'tipo_reajuste'),'')==1) {
            $w_html.=chr(13).'      <tr><td><b>Índice base:</b></td>';
            $w_html.=chr(13).'        <td>'.nvl(f($RS,'nm_eoindicador'),'---').' de '.nvl(f($RS,'indice_base'),'---');
            if (nvl(f($RS,'vl_indice_base'),'')!='') $w_html.=' ('.formatNumber(f($RS,'vl_indice_base'),4).')';
            else $w_html.=' (não informado)';
          }
          $w_html.=chr(13).'      <tr valign="top">';
          $w_html.=chr(13).'        <td><b>Alteração contratual:</b></td>';
          $w_html.=chr(13).'        <td><b>Limite: </b>'.formatNumber(nvl(f($RS,'limite_variacao'),0)).'%';
          $w_html.=chr(13).'            <b>Acréscimo/Supressão: </b>'.formatNumber(nvl(f($RS,'limite_usado'),0),6).'%';
          $w_html.=chr(13).'            <b>Disponível: </b>'.formatNumber(nvl(f($RS,'limite_variacao') - nvl(f($RS,'limite_usado'),0),0),6).'%';
          $w_html.=chr(13).'      <tr><td ><b>Parcelas pagas em uma única liquidação?</b></td>';
          $w_html.=chr(13).'        <td>'.RetornaSimNao(f($RS,'financeiro_unico')).'</td></tr>';
        }
        if (substr($w_sigla,0,3)=='GCB'){ 
          $w_html.=chr(13).'          <tr valign="top">';
          $w_html.=chr(13).'          <td><b>Número do empenho (modalidade/nível/mensalidade):</b></td>';
          $w_html.=chr(13).'          <td>'.Nvl(f($RS,'processo'),'---').'</td></tr>';
        }
      }
    } 
    // Dados da conclusão da solicitação, se ela estiver nessa situação
    if (Nvl(f($RS,'conclusao'),'')>'') {
      $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS DO ENCERRAMENTO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';   
      $w_html.=chr(13).'      <tr><td valign="top" colspan="2">';
      $w_html.=chr(13).'      <tr><td><b>Início da vigência:</b></td>';
      $w_html.=chr(13).'        <td>'.FormataDataEdicao(f($RS,'inicio')).'</td></tr>';
      $w_html.=chr(13).'      <tr><td><b>Término da vigência:</b></td>';
      $w_html.=chr(13).'        <td>'.FormataDataEdicao(f($RS,'fim')).'</td></tr>';
      if ($w_tipo_visao==0 && substr($w_sigla,0,3)!='GCA') {
        $w_html.=chr(13).'    <tr><td><b>Valor realizado:</b></td>';
        $w_html.=chr(13).'      <td>'.formatNumber(f($RS,'valor_atual')).'</td></tr>';
      } 
      if ($w_tipo_visao==0) {
        $w_html.=chr(13).'      <tr><td valign="top"><b>Nota de conclusão:</b></td>';
        $w_html.=chr(13).'          <td>'.nvl(CRLF2BR(f($RS,'observacao')),'---').'</td></tr>';
      } 
    } else {
      // Se for listagem, exibe os outros dados dependendo do tipo de visão  do usuário
      if ($w_tipo_visao!=2 && ($l_O=='L' || $l_O=='T') && $l_P1==4) {
        if (f($RS,'aviso_prox_conc')=='S') {
          // Configuração dos alertas de proximidade da data limite para conclusão do acordo
          $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ALERTAS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
          $w_html.=chr(13).'      <tr><td><b>Emite aviso:</b></td>';
          $w_html.=chr(13).'        <td>'.retornaSimNao(f($RS,'aviso_prox_conc')).', a partir de '.formataDataEdicao(f($RS,'aviso')).'.</td></tr>';
        } 
      } 
    }
    // Notas de empenho
    if($w_segmento=='Público' && substr($w_sigla,0,3)=='GCD') {
      $RS1 = db_getAcordoNota::getInstanceOf($dbms,$w_cliente,null,$l_chave,null,null,null,null,null,null);
      $RS1 = SortArray($RS1,'data','desc', 'cd_aditivo','desc');
      $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>NOTAS DE EMPENHO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';   
      $w_html.=chr(13).'      <tr><td colspan="2">';
      $w_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
      $w_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $w_html.=chr(13).'          <td rowspan=2><b>Aditivo</td>';
      $w_html.=chr(13).'          <td rowspan=2><b>Número</td>';
      $w_html.=chr(13).'          <td rowspan=2><b>Outra parte</td>';
      $w_html.=chr(13).'          <td rowspan=2><b>Data</td>';
      $w_html.=chr(13).'          <td colspan=5><b>Valores</td>';
      $w_html.=chr(13).'          <td colspan=2><b>Saldos</td>';
      $w_html.=chr(13).'        </tr>';
      $w_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $w_html.=chr(13).'          <td><b>Emissão</td>';
      $w_html.=chr(13).'          <td><b>Canc.</td>';
      $w_html.=chr(13).'          <td><b>Total</td>';
      $w_html.=chr(13).'          <td><b>Liquidado</td>';
      $w_html.=chr(13).'          <td><b>Pago</td>';
      $w_html.=chr(13).'          <td><b>A liquidar</td>';
      $w_html.=chr(13).'          <td><b>A pagar</td>';
      $w_html.=chr(13).'        </tr>';
      if (count($RS)<=0) {
        // Se não foram selecionados registros, exibe mensagem 
        $w_html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>Não foram encontrados registros.</b></td></tr>';
      } else {
        // Lista os registros selecionados para listagem 
        $w_total  = 0;
        $w_liq    = 0;
        $w_pago   = 0;
        $w_aliq   = 0;
        $w_apag   = 0;
        $w_cancel = 0;
        $w_nota   = 0;
        foreach($RS1 as $row) {
          $w_html.=chr(13).'      <tr valign="top">';
          $w_html.=chr(13).'        <td nowrap>'.nvl(f($row,'cd_aditivo'),'---').'</td>';
          $w_html.=chr(13).'        <td nowrap>'.f($row,'sg_tipo_documento').' '.f($row,'numero').'&nbsp;';
          if (f($row,'abrange_inicial')=='S')   { $w_html.= '('.f($row,'sg_inicial').')';   $w_legenda_ini = ' ('.f($row,'sg_inicial').') Valor inicial'; }
          if (f($row,'abrange_acrescimo')=='S') { $w_html.= '('.f($row,'sg_acrescimo').')'; $w_legenda_acr = ' ('.f($row,'sg_acrescimo').') Acréscimo/Supressão'; }
          if (f($row,'abrange_reajuste')=='S')  { $w_html.= '('.f($row,'sg_reajuste').')';  $w_legenda_rea = ' ('.f($row,'sg_reajuste').') Reajuste'; }
          $w_html.=chr(13).'        <td>'.nvl(f($row,'nm_outra_parte'),'---').'</td>';
          $w_html.=chr(13).'        <td align="center">'.Nvl(FormataDataEdicao(f($row,'data'),5),'---').'</td>';
          $w_html.=chr(13).'        <td align="right">'.formatNumber(Nvl(f($row,'valor'),0)).'</td>';
          $w_html.=chr(13).'        <td align="right">'.formatNumber(Nvl(f($row,'vl_cancelamento'),0)).'</td>';
          $w_html.=chr(13).'        <td align="right">'.formatNumber(Nvl(f($row,'valor'),0) - Nvl(f($row,'vl_cancelamento'),0)).'</td>';
          $w_html.=chr(13).'        <td align="right">'.formatNumber(Nvl(f($row,'vl_liquidado'),0)).'</td>';
          $w_html.=chr(13).'        <td align="right">'.formatNumber(Nvl(f($row,'vl_pago'),0)).'</td>';
          $w_html.=chr(13).'        <td align="right">'.formatNumber(Nvl(f($row,'valor'),0) - Nvl(f($row,'vl_cancelamento'),0) - Nvl(f($row,'vl_liquidado'),0)).'</td>';
          $w_html.=chr(13).'        <td align="right">'.formatNumber(Nvl(f($row,'valor'),0) - Nvl(f($row,'vl_cancelamento'),0) - Nvl(f($row,'vl_pago'),0)).'</td>';
          $w_html.=chr(13).'      </tr>';
          $w_total  += (nvl(f($row,'valor'),0) - nvl(f($row,'vl_cancelamento'),0));
          $w_nota   += nvl(f($row,'valor'),0);
          $w_cancel += nvl(f($row,'vl_cancelamento'),0);
          $w_liq    += nvl(f($row,'vl_liquidado'),0);
          $w_pago   += nvl(f($row,'vl_pago'),0);
          $w_aliq   += ((nvl(f($row,'valor'),0) - nvl(f($row,'vl_cancelamento'),0)) - nvl(f($row,'vl_liquidado'),0));
          $w_apag   += ((nvl(f($row,'valor'),0) - nvl(f($row,'vl_cancelamento'),0)) - nvl(f($row,'vl_pago'),0));
        } 
        $w_html.=chr(13).'      <trvalign="top">';
        $w_html.=chr(13).'        <td align="right" colspan=4>Totais</td>';
        $w_html.=chr(13).'        <td align="right">'.Nvl(formatNumber($w_nota),0).'</td>';
        $w_html.=chr(13).'        <td align="right">'.Nvl(formatNumber($w_cancel),0).'</td>';
        $w_html.=chr(13).'        <td align="right">'.Nvl(formatNumber($w_total),0).'</td>';
        $w_html.=chr(13).'        <td align="right">'.Nvl(formatNumber($w_liq),0).'</td>';
        $w_html.=chr(13).'        <td align="right">'.Nvl(formatNumber($w_pago),0).'</td>';
        $w_html.=chr(13).'        <td align="right">'.Nvl(formatNumber($w_aliq),0).'</td>';
        $w_html.=chr(13).'        <td align="right">'.Nvl(formatNumber($w_apag),0).'</td>';
      } 
      $w_html.=chr(13).'    </table>';
      $w_legenda = $w_legenda_ini.$w_legenda_acr.$w_legenda_rea;
      if (nvl($w_legenda,'')!='') $w_html.=chr(13).'      <tr><td colspan="2">Legenda: '.$w_legenda.'</td></tr>';
      $w_html.=chr(13).'  </td>';
      $w_html.=chr(13).'</tr>';
    }
    // Aditivos
    if(substr($w_sigla,0,3)=='GCD') {
      $RS1 = db_getAcordoAditivo::getInstanceOf($dbms,$w_cliente,null,$l_chave,null,null,null,null,null,null,null,null,null);
      $RS1 = SortArray($RS1,'codigo','desc');
      $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ADITIVOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';   
      $w_html.=chr(13).'      <tr><td colspan="2"><div align="center">';
      $w_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
      $w_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $w_html.=chr(13).'          <td rowspan=2><b>Código</td>';
      $w_html.=chr(13).'          <td rowspan=2><b>Período</td>';
      $w_html.=chr(13).'          <td rowspan=2><b>Objeto</td>';
      $w_html.=chr(13).'          <td colspan=4><b>Totais do aditivo</td>';
      $w_html.=chr(13).'          <td colspan=4><b>Parcelas do aditivo</td>';
      $w_html.=chr(13).'        </tr>';
      $w_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $w_html.=chr(13).'          <td><b>Inicial</td>';
      $w_html.=chr(13).'          <td><b>Reajuste</td>';
      $w_html.=chr(13).'          <td><b>Acr./Supr.</td>';
      $w_html.=chr(13).'          <td><b>Total</td>';
      $w_html.=chr(13).'          <td><b>Inicial</td>';
      $w_html.=chr(13).'          <td><b>Reajuste</td>';
      $w_html.=chr(13).'          <td><b>Acr./Supr.</td>';
      $w_html.=chr(13).'          <td><b>Total</td>';
      $w_html.=chr(13).'        </tr>';
      if (count($RS1)==0) {
        // Se não foram selecionados registros, exibe mensagem 
        $w_html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'"><td colspan=9 align="center"><b>Não foram encontrados registros.</b></td></tr>';
      } else {
        // Lista os registros selecionados para listagem 
        $w_tot_in = 0;
        $w_tot_rj = 0;
        $w_tot_ac = 0;
        $w_tot_ad = 0;
        foreach($RS1 as $row) {
          $w_html.=chr(13).'      <tr valign="top" align="center">';
          $w_html.=chr(13).'        <td align="left" width="1%" nowrap>'.f($row,'codigo').'</td>';
          $w_html.=chr(13).'        <td>'.Nvl(FormataDataEdicao(f($row,'inicio'),5),'---').' a '.Nvl(FormataDataEdicao(f($row,'fim'),5),'---').'</td>';
          $w_html.=chr(13).'        <td align="left">'.f($row,'objeto').'</td>';
          $w_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'valor_inicial')).'</td>';
          $w_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'valor_reajuste')).'</td>';
          $w_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'valor_acrescimo')).'</td>';
          $w_html.=chr(13).'        <td align="right"><b>'.formatNumber(f($row,'valor_aditivo')).'</b></td>';
          $w_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'parcela_inicial')).'</td>';
          $w_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'parcela_reajustada')).'</td>';
          $w_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'parcela_acrescida')).'</td>';
          $w_html.=chr(13).'        <td align="right"><b>'.formatNumber(f($row,'parcela_aditivo')).'</b></td>';
          $w_html.=chr(13).'      </tr>';
          $w_tot_in += f($row,'valor_inicial');
          $w_tot_rj += f($row,'valor_reajuste');
          $w_tot_ac += f($row,'valor_acrescimo');
          $w_tot_ad += f($row,'valor_aditivo');
        } 
        $w_html.=chr(13).'      <tr valign="top" align="center">';
        $w_html.=chr(13).'        <td colspan=3 align="right"><b>Totais</b></td>';
        $w_html.=chr(13).'        <td align="right">'.formatNumber($w_tot_in).'</td>';
        $w_html.=chr(13).'        <td align="right">'.formatNumber($w_tot_rj).'</td>';
        $w_html.=chr(13).'        <td align="right">'.formatNumber($w_tot_ac).'</td>';
        $w_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_tot_ad).'</b></td>';
        $w_html.=chr(13).'        <td colspan=4>&nbsp;</td>';
      } 
      $w_html.=chr(13).'      </center>';
      $w_html.=chr(13).'    </table>';
      $w_html.=chr(13).'  </td>';
      $w_html.=chr(13).'</tr>';
    } elseif(substr($w_sigla,0,3)=='GCZ') {
      $RS1 = db_getAcordoAditivo::getInstanceOf($dbms,$w_cliente,null,$l_chave,null,null,null,null,null,null,null,null,null);
      $RS1 = SortArray($RS1,'codigo','desc');
      $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ADITIVOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';   
      $w_html.=chr(13).'      <tr><td colspan="2"><div align="center">';
      $w_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
      $w_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $w_html.=chr(13).'          <td><b>Código</td>';
      $w_html.=chr(13).'          <td><b>Período</td>';
      $w_html.=chr(13).'          <td><b>Objeto</td>';
      $w_html.=chr(13).'          <td><b>Documento</td>';
      $w_html.=chr(13).'          <td><b>Data</td>';
      $w_html.=chr(13).'          <td><b>Observação</td>';
      $w_html.=chr(13).'        </tr>';
      if (count($RS1)==0) {
        // Se não foram selecionados registros, exibe mensagem 
        $w_html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>Não foram encontrados registros.</b></td></tr>';
      } else {
        // Lista os registros selecionados para listagem 
        foreach($RS1 as $row) {
          $w_html.=chr(13).'      <tr valign="top" align="center">';
          $w_html.=chr(13).'        <td align="left" width="1%" nowrap>'.f($row,'codigo').'</td>';
          $w_html.=chr(13).'        <td width="1%" nowrap>'.Nvl(FormataDataEdicao(f($row,'inicio'),5),'---').' a '.Nvl(FormataDataEdicao(f($row,'fim'),5),'---').'</td>';
          $w_html.=chr(13).'        <td align="left">'.f($row,'objeto').'</td>';
          $w_html.=chr(13).'        <td align="left" width="1%" nowrap>'.nvl(f($row,'documento_origem'),'---').'</td>';
          $w_html.=chr(13).'        <td width="1%" nowrap>'.Nvl(FormataDataEdicao(f($row,'documento_data'),5),'---').'</td>';
          $w_html.=chr(13).'        <td align="left">'.nvl(f($row,'observacao'),'---').'</td>';
          $w_html.=chr(13).'      </tr>';
        } 
      } 
      $w_html.=chr(13).'    </table>';
      $w_html.=chr(13).'  </td>';
      $w_html.=chr(13).'</tr>';
    }

    // Exibe ficha completa
    if ($l_P1==4 && substr($w_sigla,0,3)!='GCZ') {
      // Termo de referência
      $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>TERMO DE REFERÊNCIA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $w_html.=chr(13).'      <tr valign="top">';
      $w_html.=chr(13).'        <td ><b>Atividades a serem desenvolvidas:</b></td>';
      $w_html.=chr(13).'        <td>'.nvl(CRLF2BR(f($RS,'atividades')),'---').'</td></tr>';
      $w_html.=chr(13).'      <tr valign="top">';
      $w_html.=chr(13).'        <td><b>Produtos a serem entregues:</b></td>';
      $w_html.=chr(13).'        <td>'.nvl(CRLF2BR(f($RS,'produtos')),'---').'</td></tr>';
      $w_html.=chr(13).'      <tr valign="top">';
      $w_html.=chr(13).'        <td ><b>Qualificação exigida:</b></td>';
      $w_html.=chr(13).'        <td>'.nvl(CRLF2BR(f($RS,'requisitos')),'---').'</td></tr>';
      if (substr($w_sigla,0,3)=='GCB'){
        $w_html.=chr(13).'      <tr valign="top">';
        $w_html.=chr(13).'      <td><b>Código para o bolsista:</b></td>';
        $w_html.=chr(13).'      <td>'.Nvl(f($RS,'codigo_externo'),'---').'</td></tr>';
      } else {
        $w_html.=chr(13).'      <tr valign="top">';
        $w_html.=chr(13).'        <td><b>Código para a outra parte:</b></td>';
        $w_html.=chr(13).'        <td>'.Nvl(f($RS,'codigo_externo'),'---').'</td></tr>';
      }
      if ($w_tipo_visao!=2 && Nvl(f($RS,'cd_modalidade'),'')=='F') {
        $w_html.=chr(13).'      <tr><td><b>Pemite vinculação de projetos?</b></td>';
        $w_html.=chr(13).'        <td>'.f($RS,'nm_vincula_projeto').'</td></tr>';
        $w_html.=chr(13).'      <tr><td><b>Pemite vinculação de demandas?</b></td>';
        $w_html.=chr(13).'        <td>'.f($RS,'nm_vincula_demanda').'</td></tr>';
        $w_html.=chr(13).'       <tr><td><b>Pemite vinculação de viagens?</b></td>';
        $w_html.=chr(13).'        <td>'.f($RS,'nm_vincula_viagem').'</td></tr>';
      }
    } 
    // Outra parte
    if     (substr($w_sigla,0,3)=='GCB')$w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>BOLSISTA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    elseif (substr($w_sigla,0,3)=='GCZ')$w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DETENTOR<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    else                                $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>OUTRA(S) PARTE(S)<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $RSQuery = db_getConvOutraParte::getInstanceOf($dbms,null,$l_chave,null,null);
    if (count($RSQuery)==0) {
      if     (substr($w_sigla,0,3)=='GCB') $w_html.=chr(13).'      <tr><td colspan=2 align="center"><font size=1>Bolsita não informado';
      elseif (substr($w_sigla,0,3)=='GCZ') $w_html.=chr(13).'      <tr><td colspan=2 align="center"><font size=1>Detentor não informado';
      else                                 $w_html.=chr(13).'      <tr><td colspan=2 align="center"><font size=1>Outra parte não informada';
    } else {
      foreach($RSQuery as $row) { 
        $w_html.=chr(13).'      <tr><td colspan=2 bgColor="#f0f0f0"style="border: 1px solid rgb(0,0,0);" ><b>';
        $w_html.=chr(13).'          '.f($row,'nm_pessoa').' ('.f($row,'nome_resumido').')';
        if (Nvl(f($RS,'sq_tipo_pessoa'),0)==1) $w_html.=chr(13).'          - '.f($row,'cpf').'</b>';
        else                                   $w_html.=chr(13).'          - '.f($row,'cnpj').'</b>';
        if ($l_P1==4) {
          $RSQuery1 = db_getBenef::getInstanceOf($dbms,$w_cliente,Nvl(f($row,'outra_parte'),0),null,null,null,Nvl(f($row,'sq_tipo_pessoa'),0),null,null,null,null,null,null,null);
          foreach($RSQuery1 as $row1){$RSQuery1=$row1; break;}
          if (f($RSQuery1,'sq_tipo_pessoa')==1) {
            $w_html.=chr(13).'      <tr><td colspan="2">';
            $w_html.=chr(13).'          <tr><td><b>Sexo:</b></td>'; 
            $w_html.=chr(13).'              <td>'.f($RSQuery1,'nm_sexo').'</td></tr>';
            $w_html.=chr(13).'          <tr><td><b>Data de nascimento:</b></td>'; 
            $w_html.=chr(13).'              <td>'.FormataDataEdicao(Nvl(f($RSQuery1,'nascimento'),'---')).'</td></tr>';
            $w_html.=chr(13).'          <tr><td><b>Identidade:</b></td>'; 
            $w_html.=chr(13).'              <td>'.f($RSQuery1,'rg_numero').'</td></tr>';
            $w_html.=chr(13).'          <tr><td><b>Data de emissão:</b></td>'; 
            $w_html.=chr(13).'              <td>'.FormataDataEdicao(Nvl(f($RSQuery1,'rg_emissao'),'---')).'</td>';
            $w_html.=chr(13).'          <tr><td><b>Órgão emissor:</b></td>'; 
            $w_html.=chr(13).'              <td>'.f($RSQuery1,'rg_emissor').'</td></tr>';
            $w_html.=chr(13).'          <tr><td><b>Passaporte:</b></td>'; 
            $w_html.=chr(13).'              <td>'.Nvl(f($RSQuery1,'passaporte_numero'),'---').'</td></tr>';
            $w_html.=chr(13).'          <tr><td><b>País emissor:</b></td>'; 
            $w_html.=chr(13).'              <td>'.Nvl(f($RSQuery1,'nm_pais_passaporte'),'---').'</td></tr>';
          } else {
            $w_html.=chr(13).'      <tr><td><b>Inscrição estadual:</b></td>'; 
            $w_html.=chr(13).'          <td>'.Nvl(f($RSQuery1,'inscricao_estadual'),'---').'</td></tr>';
          } 
          if (f($RSQuery1,'sq_tipo_pessoa')==1) {
            $w_html.=chr(13).'      <tr><td colspan="2" align="center" style="border: 1px solid rgb(0,0,0);"><b>Endereço comercial, Telefones e e-Mail</td>';
          } else {
            $w_html.=chr(13).'      <tr><td colspan="2" align="center" style="border: 1px solid rgb(0,0,0);"><b>Endereço principal, Telefones e e-Mail</td>';
          } 
          $w_html.=chr(13).'      <tr><td colspan="2">';
          $w_html.=chr(13).'          <tr valign="top">';
          $w_html.=chr(13).'            <td><b>Telefone:</b></td>'; 
          if (nvl(f($RSQuery1,'ddd'),'nulo')!='nulo') {
            $w_html.=chr(13).'            <td>('.f($RSQuery1,'ddd').') '.f($RSQuery1,'nr_telefone').'</td></tr>';
          } else {
            $w_html.=chr(13).'            <td>---</td></tr>';
          }
          $w_html.=chr(13).'          <tr><td><b>Fax:</b></td>'; 
          $w_html.=chr(13).'            <td>'.Nvl(f($RSQuery1,'nr_fax'),'---').'</td></tr>';
          $w_html.=chr(13).'          <tr><td><b>Celular:</b></td>'; 
          $w_html.=chr(13).'            <td>'.Nvl(f($RSQuery1,'nr_celular'),'---').'</td></tr>';
          $w_html.=chr(13).'          <tr valign="top">';
          $w_html.=chr(13).'             <td><b>Endereço:</b></td>'; 
          $w_html.=chr(13).'            <td>'.f($RSQuery1,'logradouro').'</td></tr>';
          $w_html.=chr(13).'          <tr><td><b>Complemento:</b></td>'; 
          $w_html.=chr(13).'            <td>'.Nvl(f($RSQuery1,'complemento'),'---').'</td></tr>';
          $w_html.=chr(13).'          <tr><td><b>Bairro:</b></td>'; 
          $w_html.=chr(13).'            <td>'.Nvl(f($RSQuery1,'bairro'),'---').'</td></tr>';
          $w_html.=chr(13).'          <tr valign="top">';
          if (f($RSQuery1,'pd_pais')=='S') {
            $w_html.=chr(13).'          <td><b>Cidade:</b></td>'; 
            $w_html.=chr(13).'          <td>'.f($RSQuery1,'nm_cidade').'-'.f($RSQuery1,'co_uf').'</td></tr>';
          } else {
            $w_html.=chr(13).'          <td><b>Cidade:</b></td>'; 
            $w_html.=chr(13).'          <td>'.f($RSQuery1,'nm_cidade').'-'.f($RSQuery1,'nm_pais').'</td></tr>';
          } 
          $w_html.=chr(13).'          <tr><td><b>CEP:</b></td>'; 
          $w_html.=chr(13).'            <td>'.f($RSQuery1,'cep').'</td></tr>';
          if (Nvl(f($RSQuery1,'email'),'nulo')!='nulo') {
            if (!$l_P4==1) {
              $w_html.=chr(13).'              <tr><td><b>e-Mail:</b></td>';
              $w_html.=chr(13).'                <td><a class="hl" href="mailto:'.f($RSQuery1,'email').'">'.f($RSQuery1,'email').'</td></tr>';
            } else {
              $w_html.=chr(13).'              <tr><td><b>e-Mail:</b></td>';
              $w_html.=chr(13).'                <td>'.f($RSQuery1,'email').'</td></tr>';
            } 
          } else {
            $w_html.=chr(13).'              <tr><td><b>e-Mail:</b></td>';
            $w_html.=chr(13).'                <td>---</td></tr>';
          }  
          if (substr($w_sigla,0,3)=='GCR') {
            $w_html.=chr(13).'      <tr><td colspan="2" align="center" style="border: 1px solid rgb(0,0,0);"><b>Dados para recebimento</td>';
            $w_html.=chr(13).'      <tr><td><b>Forma de recebimento:</b></td>';
            $w_html.=chr(13).'      <td>'.f($RS,'nm_forma_pagamento').'</td></tr>';
          } elseif (substr($w_sigla,0,3)=='GCD') {
            $w_html.=chr(13).'      <tr><td colspan="2" align="center" style="border: 1px solid rgb(0,0,0);"><b>Dados para pagamento</td>';
            $w_html.=chr(13).'      <tr><td><b>Forma de pagamento:</b></td>';
            $w_html.=chr(13).'      <td>'.f($RS,'nm_forma_pagamento').'</td></tr>';
          } elseif (substr($w_sigla,0,3)!='GCZ') {
            $w_html.=chr(13).'      <tr><td colspan="2" align="center" style="border: 1px solid rgb(0,0,0);"><b>Dados para pagamento/recebimento</td>';
            $w_html.=chr(13).'      <tr><td><b>Forma de pagamento/recebimento:</b></td>';
            $w_html.=chr(13).'      <td>'.f($RS,'nm_forma_pagamento').'</td></tr>';
          } 
          if (substr($w_sigla,0,3)!='GCR' && substr($w_sigla,0,3)!='GCZ') {
            if (!(strpos('CREDITO,DEPOSITO',f($RS,'sg_forma_pagamento'))===false)) {
              if (Nvl(f($RS,'cd_banco'),'')>'') {
                $w_html.=chr(13).'          <tr><td><b>Banco:</b></td>';
                $w_html.=chr(13).'                <td>'.f($RS,'cd_banco').' - '.f($RS,'nm_banco').'</td></tr>';
                $w_html.=chr(13).'          <tr><td><b>Agência:</b></td>';
                $w_html.=chr(13).'              <td>'.f($RS,'cd_agencia').' - '.f($RS,'nm_agencia').'</td></tr>';
                if (f($RS,'exige_operacao')=='S') $w_html.=chr(13).'          <tr><td><b>Operação:</b></td><td>'.Nvl(f($RS,'operacao_conta'),'---').'</td>';
                $w_html.=chr(13).'          <tr><td><b>Número da conta:</b></td>';
                $w_html.=chr(13).'              <td>'.Nvl(f($RS,'numero_conta'),'---').'</td></tr>';
              } else {
                $w_html.=chr(13).'          <tr><td><b>Banco:</b></td>';
                $w_html.=chr(13).'              <td>---</td></tr>';
                $w_html.=chr(13).'          <tr><td><b>Agência:</b></td>';
                $w_html.=chr(13).'              <td>---</td></tr>';
                if (f($RS,'exige_operacao')=='S') $w_html.=chr(13).'          <tr><td><b>Operação:</b></td><td>---</td></tr>';
                $w_html.=chr(13).'          <tr><td><b>Número da conta:</b></td>';
                $w_html.=chr(13).'              <td>---</td></tr>';
              } 
            } elseif (f($RS,'sg_forma_pagamento')=='ORDEM') {
              $w_html.=chr(13).'          <tr valign="top">';
              if (Nvl(f($RS,'cd_banco'),'')>'') {
                $w_html.=chr(13).'          <td><b>Banco:<b><br>'.f($RS,'cd_banco').' - '.f($RS,'nm_banco').'</td>';
                $w_html.=chr(13).'          <td><b>Agência:<b><br>'.f($RS,'cd_agencia').' - '.f($RS,'nm_agencia').'</td>';
              } else {
                $w_html.=chr(13).'          <td><b>Banco:<b><br>---</td>';
                $w_html.=chr(13).'          <td><b>Agência:<b><br>---</td>';
              } 
            } elseif (f($RS,'sg_forma_pagamento')=='EXTERIOR') {
              $w_html.=chr(13).'          <tr valign="top">';
              $w_html.=chr(13).'          <td>Banco:<b><br>'.f($RS,'banco_estrang').'</td>';
              $w_html.=chr(13).'          <td>ABA Code:<b><br>'.Nvl(f($RS,'aba_code'),'---').'</td>';
              $w_html.=chr(13).'          <td>SWIFT Code:<b><br>'.Nvl(f($RS,'swift_code'),'---').'</td>';
              $w_html.=chr(13).'          <tr><td colspan=3>Endereço da agência:<b><br>'.Nvl(f($RS,'endereco_estrang'),'---').'</td>';
              $w_html.=chr(13).'          <tr valign="top">';
              $w_html.=chr(13).'          <td colspan=2>Agência:<b><br>'.Nvl(f($RS,'agencia_estrang'),'---').'</td>';
              $w_html.=chr(13).'          <td>Número da conta:<b><br>'.Nvl(f($RS,'numero_conta'),'---').'</td>';
              $w_html.=chr(13).'          <tr valign="top">';
              $w_html.=chr(13).'          <td colspan=2>Cidade:<b><br>'.f($RS,'nm_cidade').'</td>';
              $w_html.=chr(13).'          <td>País:<b><br>'.f($RS,'nm_pais').'</td>';
            } 
          } 
        } 
        // Preposto
        if (Nvl(f($RS,'sq_tipo_pessoa'),0)==2 && $l_P1==4) {
          if ($w_tipo_visao!=2) {
            $RSQuery1 = db_getConvPreposto::getInstanceOf($dbms,$l_chave,f($row,'sq_acordo_outra_parte'),null);            
            $w_html.=chr(13).'      <tr><td colspan="2" align="center" style="border: 1px solid rgb(0,0,0);"><b>Prepostos</td>';
            if (count($RSQuery1)==0) {
              $w_html.=chr(13).'      <tr><td colspan=2><font size=1><b>Prepostos não informados</b></font></td></tr>';
            } else {
              $w_html.=chr(13).'      <tr><td colspan="2"><div align="center">';
              $w_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';              
              $w_html.=chr(13).'          <tr><td bgColor="#f0f0f0"><div align="center"><b>Nome</b></div></td>';
              $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>CPF</b></div></td>';
              $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>Sexo</b></div></td>';
              $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>Identidade</b></div></td>';
              $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>Orgão emissão</b></div></td>';
              $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>E-mail</b></div></td>';
              $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>Cargo</b></div></td>';
              foreach($RSQuery1 as $row1) {
                $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
                $w_html.=chr(13).'      <tr>';
                $w_html.=chr(13).'        <td >'.f($row1,'nm_pessoa').'</td>';
                $w_html.=chr(13).'        <td align="center">'.f($row1,'cpf').'</td>';
                $w_html.=chr(13).'        <td>'.f($row1,'nm_sexo').'</td>';
                $w_html.=chr(13).'        <td>'.Nvl(f($row1,'rg_numero'),'---').'</td>';
                $w_html.=chr(13).'        <td>'.Nvl(f($row1,'rg_emissor'),'---').'</td>';
                if (Nvl(f($row1,'email'),'nulo')!='nulo') {
                  if (!$l_P4==1) {
                    $w_html.=chr(13).'        <td><a class="hl" href="mailto:'.f($row1,'email').'">'.f($row1,'email').'</a></td>';
                  } else {
                    $w_html.=chr(13).'        <td>'.f($row1,'email').'</td>';
                  } 
                } else {
                  $w_html.=chr(13).'        <td>---</td>';
                } 
                $w_html.=chr(13).'        <td>'.Nvl(f($row1,'cargo'),'---').'</td>';
              }
              $w_html.=chr(13).'        </table></td></tr>';              
            }
          }
          // Representantes
          //$RSQuery = db_getAcordoRep::getInstanceOf($dbms,f($RS,'sq_siw_solicitacao'),$w_cliente,null,null);
          $RSQuery = db_getConvOutroRep::getInstanceOf($dbms,$l_chave,null,f($row,'sq_acordo_outra_parte'));
          $RSQuery = SortArray($RSQuery,'nm_pessoa','asc');
          $w_html.=chr(13).'      <tr><td colspan="2" align="center" style="border: 1px solid rgb(0,0,0);"><b>Representantes</td>';
          if (count($RSQuery)==0) {
            $w_html.=chr(13).'      <tr><td colspan=2><font size=1><b>Representantes não informados</b></font></td></tr>';
          } else {
            $w_html.=chr(13).'      <tr><td colspan="2"><div align="center">';
            $w_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';              
            $w_html.=chr(13).'          <tr><td bgColor="#f0f0f0"><div align="center"><b><b>Nome</b></div></td>';
            $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>CPF</b></div></td>';
            $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b><b>DDD</b></div></td>';
            $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b><b>Telefone</b></div></td>';
            $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b><b>Celular</b></div></td>';
            $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b><b>e-Mail</b></div></td>';
            $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b><b>Cargo</b></div></td>';
            $w_html.=chr(13).'          </tr>';
            $w_cor=$w_TrBgColor;
            foreach($RSQuery as $row2) {
              $w_html.=chr(13).'      <tr><td>'.f($row2,'nm_pessoa').'</td>';
              $w_html.=chr(13).'        <td align="center">'.f($row2,'cpf').'</td>';
              $w_html.=chr(13).'        <td align="center" >'.Nvl(f($row2,'ddd'),'---').'</td>';
              $w_html.=chr(13).'        <td>'.Nvl(f($row2,'nr_telefone'),'---').'</td>';
              $w_html.=chr(13).'        <td>'.Nvl(f($row2,'nr_celular'),'---').'</td>';
              if (Nvl(f($row2,'email'),'nulo')!='nulo') {
                if (!$l_P4==1) {
                  $w_html.=chr(13).'        <td><a class="hl" href="mailto:'.f($row2,'email').'">'.f($row2,'email').'</a></td>';
                } else {
                  $w_html.=chr(13).'        <td>'.f($row2,'email').'</td>';
                } 
              } else {
                $w_html.=chr(13).'        <td>---</td>';
              } 
              $w_html.=chr(13).'        <td>'.Nvl(f($row2,'cargo'),'---').'</td>';
            }
            $w_html.=chr(13).'        </table></td></tr>';      
          } 
        }
      } 
    }
    } 

  // Parcelas
  $RS = db_getAcordoParcela::getInstanceOf($dbms,$l_chave,null,null,null,null,null,null,null,null,null);
  $RS = SortArray($RS,'ordem','asc', 'dt_lancamento', 'asc');
  if (count($RS)>0) {
    //$w_html.=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Parcelas</td>';
    $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>PARCELAS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $w_html.=chr(13).'      <tr><td colspan="2"><div align="center">';
    $w_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
    $w_html.=chr(13).'          <tr align="center">';
    $w_html.=chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><div><b>Ordem</b></div></td>';
    $w_html.=chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><div><b>Período</b></div></td>';
    $w_html.=chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><div><b>Vencimento</b></div></td>';
    if($w_aditivo>0) {
      $w_html.=chr(13).'            <td colspan=4 bgColor="#f0f0f0"><div><b>Valor</b></div></td>';
    } else {
      $w_html.=chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><div><b>Valor</b></div></td>';
    }
    $w_html.=chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><div><b>Observações</b></div></td>';
    $w_html.=chr(13).'            <td colspan=5 bgColor="#f0f0f0"><div><b>Financeiro</b></div></td>';
    $w_html.=chr(13).'          </tr>';
    $w_html.=chr(13).'          <tr align="center">';
    if($w_aditivo>0) {
      $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Inicial</b></div></td>';
      $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Excedente</b></div></td>';
      $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Reajuste</b></div></td>';
      $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Total</b></div></td>';
    }
    $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Lançamento</b></div></td>';
    $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Período</b></div></td>';
    $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Vencimento</b></div></td>';
    $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Valor</b></div></td>';
    $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Quitação</b></div></td>';
    $w_html.=chr(13).'          </tr>';
    $w_cor=$w_TrBgColor;
    $w_total    = 0;
    $w_total_i  = 0;
    $w_total_e  = 0;
    $w_total_r  = 0;
    $w_atual    = 0;
    $w_tot_parc = 0;
    $w_cont     = 1;
    foreach($RS as $row) {
      $w_html.=chr(13).'        <tr valign="top">';
      if ($w_atual!=f($row,'sq_acordo_parcela')) {
        if ($w_cont > 1) {
          if($w_aditivo>0) {
            $w_html.=chr(13).'          <td>&nbsp;</td>';
            $w_html.=chr(13).'          <td>&nbsp;</td>';
            $w_html.=chr(13).'          <td>&nbsp;</td>';
          }
          $w_html.=chr(13).'          <td>&nbsp;</td>';
          $w_html.=chr(13).'          <td>&nbsp;</td>';
          $w_html.=chr(13).'          <td>&nbsp;</td>';
          $w_html.=chr(13).'          <td>&nbsp;</td>';
          $w_html.=chr(13).'          <td>&nbsp;</td>';
          $w_html.=chr(13).'          <td colspan=3 align="right"><b>Total da parcela: </b></td>';
          $w_html.=chr(13).'          <td align="right"><b>'.formatNumber($w_tot_parc).'</b></td>';
          $w_html.=chr(13).'          <td>&nbsp;</td>';
          $w_html.=chr(13).'        <tr valign="top">';
        }
        $w_cont     = 1;
        $w_tot_parc = 0;
        $w_atual = f($row,'sq_acordo_parcela');
        $w_html.=chr(13).'          <td align="center">';
        if (Nvl($w_sg_tramite,'-')=='CR' && $w_fim-f($row,'vencimento')<0) {
          $w_html.=chr(13).'           <img src="'.$conImgCancel.'" border=0 width=10 heigth=10 align="center" title="Parcela cancelada!">';
        } elseif (Nvl(f($row,'quitacao'),'nulo')=='nulo') {
          if (f($row,'vencimento')<addDays(time(),-1))  {
            $w_html.=chr(13).'           <img src="'.$conImgAtraso.'" border=0 width=10 heigth=10 align="center">';
          } elseif (f($row,'vencimento')-addDays(time(),-1)<=5) {
            $w_html.=chr(13).'           <img src="'.$conImgAviso.'" border=0 width=10 height=10 align="center">';
          } else {
            $w_html.=chr(13).'           <img src="'.$conImgNormal.'" border=0 width=10 height=10 align="center">';
          } 
        } else {
          if (f($row,'quitacao')>f($row,'vencimento')) {
            $w_html.=chr(13).'           <img src="'.$conImgOkAtraso.'" border=0 width=10 heigth=10 align="center">';
          } else {
            $w_html.=chr(13).'           <img src="'.$conImgOkNormal.'" border=0 width=10 height=10 align="center">';
          } 
        } 
        $w_html.=chr(13).'        '.f($row,'ordem').'</td>';
        if(nvl(f($row,'inicio'),'')!='') $w_html.=chr(13).'        <td align="center">'.FormataDataEdicao(f($row,'inicio'),5).' a '.FormataDataEdicao(f($row,'fim'),5).'</td>';
        else                             $w_html.=chr(13).'        <td align="center">---</td>';
        $w_html.=chr(13).'        <td align="center">'.FormataDataEdicao(f($row,'vencimento'),5).'</td>';
        if($w_aditivo>0) {
          $w_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'valor_inicial')).'</td>';
          $w_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'valor_excedente')).'</td>';
          $w_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'valor_reajuste')).'</td>';
        }
        $w_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'valor')).'</td>';
        $w_html.=chr(13).'        <td>'.crlf2br(Nvl(f($row,'observacao'),'---')).'</td>';
        $w_total   += f($row,'valor');
        $w_total_i += f($row,'valor_inicial');
        $w_total_e += f($row,'valor_excedente');
        $w_total_r += f($row,'valor_reajuste');
      } else {
        if($w_aditivo>0) {
          $w_html.=chr(13).'          <td>&nbsp;</td>';
          $w_html.=chr(13).'          <td>&nbsp;</td>';
          $w_html.=chr(13).'          <td>&nbsp;</td>';
        }
        $w_html.=chr(13).'          <td>&nbsp;</td>';
        $w_html.=chr(13).'          <td>&nbsp;</td>';
        $w_html.=chr(13).'          <td>&nbsp;</td>';
        $w_html.=chr(13).'          <td>&nbsp;</td>';
        $w_html.=chr(13).'          <td>&nbsp;</td>';
        $w_cont += 1;
      }
      if (Nvl(f($row,'cd_lancamento'),'')>'') {
        $w_html.=chr(13).'        <td align="center" nowrap><A class="hl" HREF="mod_fn/lancamento.php?par=Visual&O=L&w_chave='.f($row,'sq_lancamento').'&w_tipo=&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$l_P4.'&TP='.$TP.'&SG=FN'.substr($SG,2,1).'CONT" title="Exibe as informações do lançamento." target="Lancamento">'.f($row,'cd_lancamento').'</a></td>';
        if(nvl(f($row,'inicio'),'')!='') $w_html.=chr(13).'        <td align="center">'.FormataDataEdicao(f($row,'referencia_inicio'),5).' a '.FormataDataEdicao(f($row,'referencia_fim'),5).'</td>';
        else                             $w_html.=chr(13).'        <td align="center">---</td>';
        $w_html.=chr(13).'        <td align="center">'.FormataDataEdicao(f($row,'dt_lancamento'),5).'</td>';
        $w_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'vl_lancamento')).'</td>';
        if (Nvl(f($row,'quitacao'),'nulo') <> 'nulo') $w_real     += f($row,'vl_lancamento');
        $w_tot_parc += f($row,'vl_lancamento');
      } else {
        $w_html.=chr(13).'        <td align="center">---</td>';
        $w_html.=chr(13).'        <td align="center">---</td>';
        $w_html.=chr(13).'        <td align="center">---</td>';
        $w_html.=chr(13).'        <td align="center">---</td>';
      } 
      $w_html.=chr(13).'        <td align="center">'.Nvl(FormataDataEdicao(f($row,'quitacao'),5),'---').'</td>';
      $w_html.=chr(13).'      </tr>';
    } 
    if ($w_total>0 || $w_real>0) {     
      $w_html.=chr(13).'      <tr valign="top">';
      $w_html.=chr(13).'        <td align="right" colspan=3><b>Previsto</b></td>';
      if($w_aditivo>0) {
        $w_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_total_i).'</b></td>';
        $w_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_total_e).'</b></td>';
        $w_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_total_r).'</b></td>';
      }
      $w_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_total).'</b></td>';
      $w_html.=chr(13).'        <td colspan=3>';
      if (round($w_valor_inicial-$w_total,2)!=0) {
        $w_html.=chr(13).'        <font size=1><b>O valor das parcelas difere do valor contratado ('.formatNumber($w_valor_inicial-$w_total).')</b></td>';
      } else {
        $w_html.=chr(13).'        &nbsp;</td>';
      } 
      $w_html.=chr(13).'        <td align="right"><b>Liquidado</b></td>';
      $w_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_real).'</b></td>';
      $w_html.=chr(13).'        <td>&nbsp;</td>';
      $w_html.=chr(13).'      </tr>';
    } 
    $w_html.=chr(13).'         </table></td></tr>';
  } 

  //Listagem dos itens do pedido de compra
  $RS1 = db_getCLSolicItem::getInstanceOf($dbms,null,$l_chave,null,null,null,null,null,null,null,null,null,null,'ITEMARP');
  $RS1 = SortArray($RS1,'ordem','asc','nm_tipo_material','asc','nome','asc'); 
  if (count($RS1)>0) {  
    $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ITENS ('.count($RS1).')<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
    $w_html.=chr(13).'      <tr><td colspan="2"><div align="center">';
    $w_html.=chr(13).'        <table width=100%  border="0" bordercolor="#00000">';
    if (count($RS1)==0) {
      // Se não foram selecionados registros, exibe mensagem
      $w_html.=chr(13).'      <tr><td align="center"><b>Não foram encontrados registros.</b></td></tr>';
    } else {
      // Lista os registros selecionados para listagem
      $w_total_preco = 0;
      $i             = 0;
      foreach($RS1 as $row){ 
        if (f($row,'cancelado')=='S') $w_cor = ' BGCOLOR="'.$conTrBgColorLightRed2.'" '; else $w_cor = '';
        $w_html.=chr(13).'      <tr valign="top" '.$w_cor.'>';
        if (f($row,'cancelado')=='S') {
          $w_html.=chr(13).'        <td rowspan="4"><font size="2"><b>'.f($row,'ordem').'</b></font></td>';
        } else {
          $w_html.=chr(13).'        <td rowspan="3"><font size="2"><b>'.f($row,'ordem').'</b></font></td>';
        }
        $w_html.=chr(13).'        <td>Código:<br><b>'.f($row,'codigo_interno').'</b></td>';
        if ($l_P4!=1){
          $w_html.=chr(13).'        <td colspan="2">Nome:<br><b>'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'sq_material'),$TP,null).'</b></td>';
        } else {
          $w_html.=chr(13).'        <td colspan="2">Nome:<br><b>'.f($row,'nome').'</b></td>';
        }
        $w_html.=chr(13).'      </tr>';
        $w_html.=chr(13).'      <tr valign="top">';
        $w_html.=chr(13).'        <td>Fabricante:<br><b>'.f($row,'fabricante').'</b></td>';
        $w_html.=chr(13).'        <td>Marca/Modelo:<br><b>'.f($row,'marca_modelo').'</b></td>';
        $w_html.=chr(13).'        <td>Embalagem:<br><b>'.nvl(f($row,'embalagem'),'---').'</b></td>';
        $w_html.=chr(13).'      </tr>';
        $w_html.=chr(13).'      <tr valign="top">';
        $w_html.=chr(13).'        <td>CMM:<br><b>'.formatNumber(f($row,'quantidade'),2).'</b></td>';
        $w_html.=chr(13).'        <td>$ Unitário:<br><b>'.formatNumber(f($row,'valor_unidade'),4).'</b></td>';
        $w_html.=chr(13).'        <td>$ Mensal<br><b>'.formatNumber(f($row,'valor_item'),4).'</b></td>';
        $w_html.=chr(13).'      </tr>';
        if (f($row,'cancelado')=='S') {
          $w_html.=chr(13).'      <tr>';
          $w_html.=chr(13).'        <td valign="center"><font size="2"><b>INDISPONÍVEL</b></font></td>';
          $w_html.=chr(13).'        <td colspan=2>Motivo da indisponibilidade:<br><b>'.f($row,'motivo_cancelamento').'</b></td>';
          $w_html.=chr(13).'      </tr>';
        }
        $w_html.=chr(13).'      <tr><td><td colspan="3"><hr NOSHADE color=#000000 SIZE=1></td></tr>'; 
        $w_total_preco += f($row,'valor_item');
      }
      $w_html.=chr(13).'      <tr>';
      $w_html.=chr(13).'        <td align="right" colspan="3"><b>Total mensal:&nbsp;&nbsp;</b></td>';
      $w_html.=chr(13).'        <td><b>'.formatNumber($w_total_preco,4).'</b></td>';
      $w_html.=chr(13).'      </tr>';
      $w_html.=chr(13).'    </table></td></tr>';
    } 
  }

  if ($w_tipo_visao!=2 && $l_P1==4 && ($l_O=='L' || $l_O=='V' || $l_O=='T')) {
    // Arquivos vinculados
    $RS = db_getSolicAnexo::getInstanceOf($dbms,$l_chave,null,$w_cliente);
    $RS = SortArray($RS,'nome','asc');
    if (count($RS)>0) {
      $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ARQUIVOS ANEXOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $w_html.=chr(13).'      <tr><td colspan="2"><div align="center">';
      $w_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
      $w_html.=chr(13).'          <tr align="center">';
      $w_html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Título</b></div></td>';
      $w_html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Descrição</b></div></td>';
      $w_html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Tipo</b></div></td>';
      $w_html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>KB</b></div></td>';
      $w_html.=chr(13).'          </tr>';
      $w_cor=$w_TrBgColor;
      foreach($RS as $row) {
        $w_html.=chr(13).'      <tr valign="top">';
        if ($l_P4!=1) {
          $w_html.=chr(13).'        <td>'.LinkArquivo('HL',$w_cliente,f($row,'chave_aux'),'_blank','Clique para exibir o arquivo em outra janela.',f($row,'nome'),null).'</td>';
        } else {
          $w_html.=chr(13).'        <td>'.f($row,'nome').'</td>';
        } 
        $w_html.=chr(13).'        <td>'.Nvl(f($row,'descricao'),'---').'</td>';
        $w_html.=chr(13).'        <td>'.f($row,'tipo').'</td>';
        $w_html.=chr(13).'        <td align="right">'.(round(f($row,'tamanho')/1024,1)).'&nbsp;</td>';
        $w_html.=chr(13).'      </tr>';
      } 
      $w_html.=chr(13).'         </table></td></tr>';
    } 
  } 

  // Se for envio, executa verificações nos dados da solicitação
  $w_erro = ValidaAcordo($w_cliente,$l_chave,substr($w_sigla,0,3).'GERAL',null,null,null,Nvl($w_tramite,0));
  if ($w_tipo_visao!=2 && $w_erro>'') {
    $w_html.=chr(13).'<tr><td colspan=2><font size=2>';
    $w_html.=chr(13).'<HR>';
    if (substr($w_erro,0,1)=='0') {
      $w_html.=chr(13).'  <font color="#BC3131"><b>ATENÇÃO:</b></font> Foram identificados os erros listados abaixo, não sendo possível seu encaminhamento para fases posteriores à atual.';
    } elseif (substr($w_erro,0,1)=='1') {
      $w_html.=chr(13).'  <font color="#BC3131"><b>ATENÇÃO:</b></font> Foram identificados os erros listados abaixo. Seu encaminhamento para fases posteriores à atual só pode ser feito por um gestor do sistema ou do módulo de projetos.';
    } else {
      $w_html.=chr(13).'  <font color="#BC3131"><b>ATENÇÃO:</b></font> Foram identificados os alertas listados abaixo. Eles não impedem o encaminhamento para fases posteriores à atual, mas convém sua verificação.';
    } 
    $w_html.=chr(13).'  <ul>'.substr($w_erro,1,1000).'</ul>';
    $w_html.=chr(13).'  </font></td></tr>';
  } 
  if ($w_tipo_visao!=2 && $l_P1==4 && ($l_O=='L' || $l_O=='V' || $l_O=='T')) {
    // Encaminhamentos
    $RS = db_getSolicLog::getInstanceOf($dbms,$l_chave,null,'LISTA');
    $RS = SortArray($RS,'phpdt_data','desc','sq_siw_solic_log','desc');
    $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>OCORRÊNCIAS E ANOTAÇÕES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $w_html.=chr(13).'      <tr><td colspan="2"><div align="center">';
    $w_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';    
    $w_html.=chr(13).'          <tr align="center">';
    $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Data</b></div></td>';
    $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Despacho/Observação</b></div></td>';
    $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Responsável</b></div></td>';
    $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Fase / Destinatário</b></div></td>';
    $w_html.=chr(13).'          </tr>';
    if (count($RS)<=0) {
      $w_html.=chr(13).'      <tr><td colspan=6 align="center"><b>Não foram encontrados encaminhamentos.</b></td></tr>';
    } else {
      $w_html.=chr(13).'      <tr valign="top">';
      $w_cor=$conTrBgColor;
      $i = 0;
      foreach($RS as $row) {
        if ($i==0) {
          $w_html.=chr(13).'        <td colspan=6>Fase atual: <b>'.f($row,'fase').'</b></td>';
          $i = 1;
        }
        $w_html.=chr(13).'      <tr valign="top">';
        $w_html.=chr(13).'        <td nowrap>'.FormataDataEdicao(f($row,'phpdt_data'),3).'</td>';
        if (Nvl(f($row,'caminho'),'')>'' && $l_P4!=1) {
          $w_html.=chr(13).'        <td>'.CRLF2BR(Nvl(f($row,'despacho'),'---').'<br>'.LinkArquivo('HL',$w_cliente,f($row,'sq_siw_arquivo'),'_blank','Clique para exibir o anexo em outra janela.','Anexo - '.f($row,'tipo').' - '.round(f($row,'tamanho')/1024,1).' KB',null)).'</td>';
        } else {
          $w_html.=chr(13).'        <td>'.CRLF2BR(Nvl(f($row,'despacho'),'---')).'</td>';
        } 
        if ($l_P4!=1) $w_html.=chr(13).'        <td nowrap>'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'responsavel')).'</td>';
        else          $w_html.=chr(13).'        <td nowrap>'.f($row,'responsavel').'</td>';
        if (nvl(f($row,'sq_acordo_log'),'')>'' && nvl(f($row,'destinatario'),'')>'') {
          if ($l_P4!=1) $w_html.=chr(13).'        <td nowrap>'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'sq_pessoa_destinatario'),$TP,f($row,'destinatario')).'</td>';
          else          $w_html.=chr(13).'        <td nowrap>'.f($row,'destinatario').'</td>';
        } elseif (nvl(f($row,'sq_acordo_log'),'')>'' && nvl(f($row,'destinatario'),'')=='') {
          $w_html.=chr(13).'        <td nowrap>Anotação</td>';
       } else {
          if(strpos(f($row,'despacho'),'***')!==false) {
            $w_html.=chr(13).'        <td nowrap>---</td>';
          } else {
            $w_html.=chr(13).'        <td nowrap>'.Nvl(f($row,'tramite'),'---').'</td>';
          }
        } 
        $w_html.=chr(13).'      </tr>';
      } 
    } 
    $w_html.=chr(13).'         </table></td></tr>';
    $w_html.=chr(13).'</table>';
  } 
  $w_html.=chr(13).'    </table>';
  $w_html.=chr(13).'</table>';
  return $w_html;
}
?>
