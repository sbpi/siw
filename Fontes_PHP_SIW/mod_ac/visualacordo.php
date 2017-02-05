<?php
// =========================================================================
// Rotina de visualização dos dados do acordo
// -------------------------------------------------------------------------
function VisualAcordo($l_chave,$l_O,$l_usuario,$l_P1,$l_tipo) {
  extract($GLOBALS);
  if ($l_tipo!='WORD') $w_TrBgColor=''; else $w_TrBgColor=$conTrBgColor;
  $l_html='';
  
  // Carrega o segmento do cliente
  $sql = new db_getCustomerData; $RS_Cliente = $sql->getInstanceOf($dbms,$w_cliente); 
  $w_segmento = f($RS_Cliente,'segmento');
  
  // Recupera os dados do acordo
  $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$l_chave,substr($SG,0,3).'GERAL');
  $w_SG              = f($RS,'sigla');
  $w_tramite         = f($RS,'sq_siw_tramite');
  $w_tramite_ativo   = f($RS,'ativo');
  $w_valor_inicial   = f($RS,'valor_contrato');
  $w_fim             = f($RS,'fim_real');
  $w_sg_tramite      = f($RS,'sg_tramite');
  $w_sigla           = f($RS,'sigla');
  $w_aditivo         = f($RS,'aditivo');
  $w_texto_pagamento = f($RS,'condicoes_pagamento');
  $w_aditivo         = f($RS,'aditivo');
  $w_idcc            = f($RS,'idcc');
  $w_igcc            = f($RS,'igcc');
  $w_exibe_idec      = f($RS,'exibe_idec');
  $w_sb_moeda        = nvl(f($RS,'sb_moeda'),'');

  // Tipo da pessoa que está sendo contratada (1 ou 3 = Física; 2 ou 4 = Jurídica)
  if (Nvl(f($RS,'sq_tipo_pessoa'),0)==1 || Nvl(f($RS,'sq_tipo_pessoa'),0)==3) $w_tipo_pessoa = 'F';
  else $w_tipo_pessoa = 'J';
  
  // Verifica as opções de submenu
  $sql = new db_getLinkSubMenu; $RS_Submenu = $sql->getInstanceOf($dbms, $w_cliente, $w_SG);
  $w_termo = false;
  foreach($RS_Submenu as $row) {
    if (strpos(f($row,'sigla'),'TERMO')!==false) {
      $w_termo = true;
      break;
    }
  }
  
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
    $l_html.=$crlf . '<style>';
    $l_html.=$crlf . '.remover{background-color:#ff3737}';
    $l_html.=$crlf . '</style>';
    $l_html.=$crlf . '<script language="Javascript">';
    $l_html.=$crlf . '$(document).ready(function(){';
    //Fecha toda a visualização
    $l_html.=$crlf . '  $(\'a[id^="link"]\').each(function() {';
    $l_html.=$crlf . '    id = $(this).attr("value");';
    $l_html.=$crlf . '    $(this).css("text-decoration","none")';
    $l_html.=$crlf . '           .css("color","#000000")';
    $l_html.=$crlf . '           .css("font-weight","bold");';
    $l_html.=$crlf . '    sinal = "[+]";';
    $l_html.=$crlf . '    $(".remover-" + id).hide();';
    $l_html.=$crlf . '    $(this).html(sinal);';
    $l_html.=$crlf . '  });';
    
    
    $l_html.=$crlf . '  $(\'a[id^="link"]\').click(function() {';
    $l_html.=$crlf . '    sinal = $(this).html();';
    $l_html.=$crlf . '    id = $(this).attr("value");';

    //Fechado
    $l_html.=$crlf . '    if(sinal == "[+]"){';
    $l_html.=$crlf . '      sinal = "[-]";';
    $l_html.=$crlf . '      $(".remover-" + id).show();';

    //Aberto
    $l_html.=$crlf . '    } else {';
    $l_html.=$crlf . '      sinal = "[+]";';

    $l_html.=$crlf . '      $(".remover-" + id).hide();';

    $l_html.=$crlf . '    }';

    $l_html.=$crlf . '    $(this).html(sinal);';
    $l_html.=$crlf . '   });';
    $l_html.=$crlf . '});';
    $l_html.=$crlf.'</script>';
    $l_html.=$crlf.'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
    $l_html.=$crlf.'<tr><td>';
    $l_html.=$crlf.'    <table width="99%" border="0">';
    $l_html.=$crlf.'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    if ($w_mod_pa=='S') {
      if ($w_embed!='WORD' && nvl(f($RS,'protocolo_siw'),'')!='') {
        $l_html.=$crlf.'      <tr><td bgcolor="'.$conTrBgColor.'" nowrap><font size="2"><b>PROCESSO: <A class="HL" HREF="mod_pa/documento.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($RS,'protocolo_siw').'&w_tipo=&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=PADGERAL'.MontaFiltro('GET').'" title="Exibe as informações deste registro." target="processo">'.f($RS,'protocolo').'&nbsp;</a><td bgcolor="'.$conTrBgColor.'" align="right"><font size=2><b>CONTRATO: '.f($RS,'codigo_interno').' - '.f($RS,'titulo').' ('.$l_chave.')'.'</b></font></td></tr>';
      } else {
        $l_html.=$crlf.'      <tr><td bgcolor="'.$conTrBgColor.'" nowrap><font size="2"><b>PROCESSO: '.nvl(f($RS,'processo'),'---').'<td bgcolor="'.$conTrBgColor.'" align="right"><font size=2><b>CONTRATO: '.f($RS,'codigo_interno').' - '.f($RS,'titulo').' ('.$l_chave.')'.'</b></font></td></tr>';
      }
    } elseif ($w_segmento=='Público' && (substr($w_sigla,0,3)=='GCA' || substr($w_sigla,0,3)=='GCD' || substr($w_sigla,0,3)=='GCZ')) { 
      if (substr($w_sigla,0,3)=='GCA') $l_html.=$crlf.'      <tr><td colspan="2" bgcolor="'.$conTrBgColor.'" align=justify><font size="2"><b>PROCESSO: '.nvl(f($RS,'processo'),'---').' ACT: '.f($RS,'codigo_interno').' - '.f($RS,'titulo').' ('.$l_chave.')'.'</b></font></td></tr>';
      else                        $l_html.=$crlf.'      <tr><td bgcolor="'.$conTrBgColor.'" nowrap><font size="2"><b>PROCESSO: '.nvl(f($RS,'processo'),'---').'<td bgcolor="'.$conTrBgColor.'" align="right"><font size=2><b>CONTRATO: '.f($RS,'codigo_interno').' - '.f($RS,'titulo').' ('.$l_chave.')'.'</b></font></td></tr>';
    } else {
      if (substr($w_sigla,0,3)=='GCA') $l_html.=$crlf.'      <tr><td colspan="2" bgcolor="'.$conTrBgColor.'" align=justify><font size="2"><b>ACT: '.f($RS,'codigo_interno').' - '.f($RS,'titulo').' ('.$l_chave.')'.'</b></font></td></tr>';
      else                        $l_html.=$crlf.'      <tr><td colspan="2" bgcolor="'.$conTrBgColor.'" align=justify<font size="2"><b>CONTRATO: '.f($RS,'codigo_interno').' - '.f($RS,'titulo').' ('.$l_chave.')'.'</b></font></td></tr>';
    }
    $l_html.=$crlf.'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    $l_html .= $crlf.'    <tr><td colspan=2><table border=0 cellpadding=0 cellspacing=0 width="100%"><tr valign="top" align="center">';
    if ($l_tipo!='WORD') {
      if ($w_exibe_idec=='S') $l_html .= $crlf.'        <td width="25%">'.VisualIndicador($w_dir_volta,$w_cliente,'IDEC',$TP,'IDEC').': '.ExibeSmile('IDEC',$w_idcc).' '.formatNumber($w_idcc,2).'%</b></td>';
      $l_html .= $crlf.'        <td width="25%">'.VisualIndicador($w_dir_volta,$w_cliente,'IDCC',$TP,'IDCC').': '.ExibeSmile('IDCC',$w_idcc).' '.formatNumber($w_idcc,2).'%</b></td>';
      $l_html .= $crlf.'        <td width="25%">'.VisualIndicador($w_dir_volta,$w_cliente,'IGCC',$TP,'IGCC').': '.ExibeSmile('IGCC',$w_igcc).' '.formatNumber($w_igcc,2).'%</b></td>';
    } else {
      if ($w_exibe_idec=='S') $l_html .= $crlf.'        <td width="25%">IDEC: '.ExibeSmile('idec',$w_idcc).' '.formatNumber($w_idcc,2).'%</b></td>';
      $l_html .= $crlf.'        <td width="25%">IDCC: '.ExibeSmile('idcc',$w_idcc).' '.formatNumber($w_idcc,2).'%</b></td>';
      $l_html .= $crlf.'        <td width="25%">IGCC: '.ExibeSmile('igcc',$w_igcc).' '.formatNumber($w_igcc,2).'%</b></td>';
    }
    $l_html .= $crlf.'      </table>';
    // Identificação do acordo
    $l_html.=$crlf.'      <tr><td colspan="2"><br><font size="2"><b>IDENTIFICAÇÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
    if (nvl(f($RS,'sq_solic_vinculo'),'')!='') {
      // Recupera dados da solicitação de compra
      $sql = new db_getSolicData; $RS1 = $sql->getInstanceOf($dbms,f($RS,'sq_solic_vinculo'),null);
      $vinc = explode('|@|',f($RS1,'dados_solic'));
      if ($vinc[5]=='PJCAD') { 
        $texto = 'Projeto'; $exibe = false;
      } else {
        $texto = $vinc[4];
      }
      $l_html.=chr(13).'      <tr><td width="30%"><b>'.$texto.': </b></td>';
      $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS,'sq_solic_vinculo'),f($RS1,'dados_solic'),'N',$l_tipo).'</td>';
    }
    if (nvl(f($RS,'dados_pai'),'')!='') {
      $pai = explode('|@|',f($RS,'dados_pai'));
      if ($pai[5]=='PJCAD' && $exibe) {
        $l_html.=chr(13).'      <tr><td width="30%"><b>Projeto: </b></td>';
        if (Nvl(f($RS,'dados_pai'),'')!='') {
          $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS,'sq_solic_pai'),f($RS,'dados_pai'),'N',$l_tipo).'</td>';
        } else {
          $l_html.=chr(13).'        <td>---</td>';
        }
        $exibe = false;
      } elseif(nvl($pai[4],'')!='' && !$w_exibe_processo) {
        $l_html.=chr(13).'      <tr><td width="30%"><b>'.$pai[4].': </b></td>';
        $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS,'sq_solic_pai'),f($RS,'dados_pai'),'N',$l_tipo).'</td>';
      }
    } 

    if (nvl(f($RS,'nm_etapa'),'')>'' && $w_cliente != '10135') {
      if (substr($w_sigla,0,3)=='GCB') {   
        $l_html.=$crlf.'      <tr valign="top"><td><b>Modalidade: </b></td>';
        $l_html.=$crlf.'          <td>      '.f($RS,'nm_etapa').'</td></tr>';
      } else { 
        $l_html.=$crlf.'      <tr valign="top"><td><b>Etapa: </b></td>';
        $l_html.=$crlf.'          <td>      '.f($RS,'nm_etapa').'</td></tr>';
      }
    } 

    // Se a classificação foi informada, exibe.
    if (Nvl(f($RS,'sq_cc'),'')>'') {
      $l_html .= $crlf.'      <tr><td width="25%"><b>Classificação:<b></td>';
      $l_html .= $crlf.'        <td>'.f($RS,'nm_cc').' </td></tr>';
    }
    
    if (substr($w_sigla,0,3)=='GCB'){ 
      $l_html.=$crlf.'      <tr valign="top">';
      $l_html.=$crlf.'        <td><b>Plano de trabalho: </b></td>';
      $l_html.=$crlf.'        <td>'.CRLF2BR(f($RS,'objeto')).'</td></tr>';
    } else {                        
      $l_html.=$crlf.'      <tr valign="top">';
      $l_html.=$crlf.'        <td><b>Objeto: </b></td>';
      $l_html.=$crlf.'        <td>'.CRLF2BR(f($RS,'objeto')).'</td></tr>';
    }
    $l_html.=$crlf.'      <tr><td valign="top"><b>Tipo:</b></td>';
    $l_html.=$crlf.'          <td>'.f($RS,'nm_tipo_acordo').'</td></tr>';
    $l_html.=$crlf.'      <tr><td><b>Cidade de origem:</b></td>';
    $l_html.=$crlf.'          <td>'.f($RS,'nm_cidade').' ('.f($RS,'co_uf').')</td></tr>';
    if ($l_tipo!='WORD') {
      $l_html.=$crlf.'          <tr><td><b>Gestor do contrato:</b></td>';
      $l_html.=$crlf.'              <td>'.ExibePessoa($w_dir_volta,$w_cliente,f($RS,'solicitante'),$TP,f($RS,'nm_solic')).'</b></td>';
      $l_html.=$crlf.'          <tr><td><b>Unidade responsável monitoramento:</b></td>';
      $l_html.=$crlf.'              <td>'.ExibeUnidade($w_dir_volta,$w_cliente,f($RS,'nm_unidade_resp'),f($RS,'sq_unidade'),$TP).'</b></td>';
    } else {
      $l_html.=$crlf.'          <tr><td><b>Responsável monitoramento:</b></td>';
      $l_html.=$crlf.'              <td>'.f($RS,'nm_solic').'</td></tr>';
      $l_html.=$crlf.'          <tr><td><b>Unidade responsável monitoramento:</b></td>';
      $l_html.=$crlf.'              <td>'.f($RS,'nm_unidade_resp').'</td></tr>';
    } 
    $l_html.=$crlf.'          <tr><td valign="top"><b>Valor:</b></td>';
    $l_html.=$crlf.'              <td>'.(($w_sb_moeda!='') ? $w_sb_moeda.' ' : '').formatNumber(f($RS,'valor_contrato')).' </td></tr>';
    if(substr($w_sigla,0,3)=='GCR' || substr($w_sigla,0,3)=='GCD' || substr($w_sigla,0,3)=='GCZ') {
      $l_html.=$crlf.'          <tr><td><b>Vigência:</b></td>';
      $l_html.=$crlf.'              <td>'.FormataDataEdicao(f($RS,'inicio')).' a '.FormataDataEdicao(f($RS,'fim')).' (contrato e aditivos)</td></tr>';
      $l_html.=$crlf.'          <tr valign="top">';
      $l_html.=$crlf.'              <td><b>Assinatura do contrato:</b></td>';
      $l_html.=$crlf.'              <td>'.Nvl(FormataDataEdicao(f($RS,'assinatura')),'---').'</td></tr>';
      if ($w_segmento=='Público' && substr($w_sigla,0,3)!='GCB') { 
        $l_html.=$crlf.'          <tr valign="top">';
        $l_html.=$crlf.'              <td><b>Publicação D.O.:</b></td>';
        $l_html.=$crlf.'              <td>'.Nvl(FormataDataEdicao(f($RS,'publicacao')),'---').'</td></tr>';
        $l_html.=$crlf.'          <tr><td><b>Página D.O.:</b></td>';
        $l_html.=$crlf.'              <td>'.nvl(CRLF2BR(f($RS,'numero_certame')),'---').'</td></tr>';
      }        
    } else {
      $l_html.=$crlf.'          <tr><td><b>Vigência:</b></td>';
      $l_html.=$crlf.'              <td>'.FormataDataEdicao(f($RS,'inicio')).' a '.FormataDataEdicao(f($RS,'fim')).'</td></tr>';
    }
    if ($w_tipo_visao==0 || $w_tipo_visao==1) {
      // Informações adicionais
      if (Nvl(f($RS,'descricao'),'')>'' || Nvl(f($RS,'justificativa'),'')>'') {
        if (Nvl(f($RS,'descricao'),'')>'' && $w_cliente!='10135'){
           $l_html.=$crlf.'      <tr><td valign="top"><b>Resultados esperados:</b></td>';
           $l_html.=$crlf.'          <td>'.CRLF2BR(f($RS,'descricao')).'</td></tr>';
        }
        if ($w_tipo_visao==0 && Nvl(f($RS,'justificativa'),'')>'') {
          $l_html.=$crlf.'      <tr><td valign="top"><b>Observações:</b></td>';
          $l_html.=$crlf.'          <td>'.CRLF2BR(f($RS,'justificativa')).'</td></tr>';
        } 
      }
     } 

    if ($w_tipo_visao==0 || $w_tipo_visao==1) {
      // Dados Adicionais
      if(($w_segmento=='Público' || $w_segmento=='Agência' || Nvl(f($RS,'nm_lcmodalidade'),'')!='') && (substr($w_sigla,0,3)=='GCD' || substr($w_sigla,0,3)=='GCZ')) {
        $l_html.=$crlf.'      <tr><td colspan="2"><br><font size="2"><b>DADOS ADICIONAIS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
        if ($w_segmento=='Público' && substr($w_sigla,0,3)!='GCZ') {
          $l_html.=$crlf.'      <tr><td><b>Fonte de recurso:</b></td>';
          $l_html.=$crlf.'        <td>'.nvl(CRLF2BR(f($RS,'nm_lcfonte_recurso')),'---').'</td></tr>';
          $l_html.=$crlf.'      <tr><td><b>Especificação de despesa:</b></td>';
          $l_html.=$crlf.'        <td>'.nvl(CRLF2BR(f($RS,'nm_espec_despesa')),'---').'</td></tr>';
        }
        $l_html.=$crlf.'      <tr valign="top">';
        $l_html.=$crlf.'          <td><b>Modalidade:</b></td>';
        $l_html.=$crlf.'          <td>'.Nvl(f($RS,'nm_lcmodalidade'),'---').'</td></tr>';
        $l_html.=$crlf.'      <tr><td ><b>Número do certame:</b></td>';
        $l_html.=$crlf.'        <td>'.nvl(CRLF2BR(f($RS,'numero_certame')),'---').'</td></tr>';
        if (substr($w_sigla,0,3)!='GCZ') {
          if (f($RS_Cliente,'ata_registro_preco')=='S') $l_html.=$crlf.'      <tr><td ><b>Número da ata:</b></td><td>'.nvl(CRLF2BR(f($RS,'numero_ata')),'---').'</td></tr>';
          $l_html.=$crlf.'      <tr><td><b>Tipo de reajuste:</b></td>';
          $l_html.=$crlf.'        <td>'.nvl(CRLF2BR(f($RS,'nm_tipo_reajuste')),'---').'</td></tr>';
          if(nvl(f($RS,'tipo_reajuste'),'')==1) {
            $l_html.=$crlf.'      <tr><td><b>Índice base:</b></td>';
            $l_html.=$crlf.'        <td>'.nvl(f($RS,'nm_eoindicador'),'---').' de '.nvl(f($RS,'indice_base'),'---');
            if (nvl(f($RS,'vl_indice_base'),'')!='') $l_html.=' ('.formatNumber(f($RS,'vl_indice_base'),4).')';
            else $l_html.=' (não informado)';
          }
          $l_html.=$crlf.'      <tr valign="top">';
          $l_html.=$crlf.'        <td><b>Alteração contratual:</b></td>';
          $l_html.=$crlf.'        <td><b>Limite: </b>'.formatNumber(nvl(f($RS,'limite_variacao'),0)).'%';
          $l_html.=$crlf.'            <b>Acréscimo/Supressão: </b>'.formatNumber(nvl(f($RS,'limite_usado'),0),6).'%';
          $l_html.=$crlf.'            <b>Disponível: </b>'.formatNumber(nvl(f($RS,'limite_variacao') - nvl(f($RS,'limite_usado'),0),0),6).'%';
          $l_html.=$crlf.'      <tr><td ><b>Parcela paga em uma única liquidação?</b></td>';
          $l_html.=$crlf.'        <td>'.RetornaSimNao(f($RS,'financeiro_unico')).'</td></tr>';
        }
        if (substr($w_sigla,0,3)=='GCB'){ 
          $l_html.=$crlf.'          <tr valign="top">';
          $l_html.=$crlf.'          <td><b>Número do empenho (modalidade/nível/mensalidade):</b></td>';
          $l_html.=$crlf.'          <td>'.Nvl(f($RS,'processo'),'---').'</td></tr>';
        }
        if (f($RS,'valor_caucao')>0 || $w_segmento=='Público' || $w_segmento=='Agência'){ 
          $l_html.=$crlf.'          <tr valign="top">';
          $l_html.=$crlf.'          <td><b>Valor da caução:</b></td>';
          $l_html.=$crlf.'          <td>'.(($w_sb_moeda!='') ? $w_sb_moeda.' ' : '').formatNumber(f($RS,'valor_caucao')).'</td></tr>';
        }
      }
    } 
    // Dados da conclusão da solicitação, se ela estiver nessa situação
    if (Nvl(f($RS,'conclusao'),'')>'') {
      $l_html.=$crlf.'      <tr><td colspan="2"><br><font size="2"><b>DADOS DO ENCERRAMENTO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';   
      $l_html.=$crlf.'      <tr><td valign="top" colspan="2">';
      $l_html.=$crlf.'      <tr><td><b>Início da vigência:</b></td>';
      $l_html.=$crlf.'        <td>'.FormataDataEdicao(f($RS,'inicio')).'</td></tr>';
      $l_html.=$crlf.'      <tr><td><b>Término da vigência:</b></td>';
      $l_html.=$crlf.'        <td>'.FormataDataEdicao(f($RS,'fim')).'</td></tr>';
      if ($w_tipo_visao==0 && substr($w_sigla,0,3)!='GCA') {
        $l_html.=$crlf.'    <tr><td><b>Valor realizado:</b></td>';
        $l_html.=$crlf.'      <td>'.(($w_sb_moeda!='') ? $w_sb_moeda.' ' : '').formatNumber(f($RS,'saldo_contrato')).'</td></tr>';
      } 
      if ($w_tipo_visao==0) {
        $l_html.=$crlf.'      <tr><td valign="top"><b>Nota de conclusão:</b></td>';
        $l_html.=$crlf.'          <td>'.nvl(CRLF2BR(f($RS,'observacao')),'---').'</td></tr>';
      } 
    } else {
      // Se for listagem, exibe os outros dados dependendo do tipo de visão  do usuário
      if ($w_tipo_visao!=2 && ($l_O=='L' || $l_O=='T') && $l_P1==4) {
        if (f($RS,'aviso_prox_conc')=='S') {
          // Configuração dos alertas de proximidade da data limite para conclusão do acordo
          $l_html.=$crlf.'      <tr><td colspan="2"><br><font size="2"><b>ALERTAS DE PROXIMIDADE DA DATA PREVISTA DE TÉRMINO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
          $l_html.=$crlf.'      <tr><td><b>Emite aviso:</b></td>';
          $l_html.=$crlf.'        <td>'.retornaSimNao(f($RS,'aviso_prox_conc')).', a partir de '.formataDataEdicao(f($RS,'aviso')).'.</td></tr>';
        } 
      } 
    }
    // Notas de empenho
    if($w_segmento=='Público' && substr($w_sigla,0,3)=='GCD') {
      $sql = new db_getAcordoNota; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,null,$l_chave,null,null,null,null,null,null);
      $RS1 = SortArray($RS1,'data','desc', 'cd_aditivo','desc');
      if (count($RS1)>0) {
        $l_html.=$crlf.'      <tr><td colspan="2"><br><font size="2"><b>NOTAS DE EMPENHO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';   
        $l_html.=$crlf.'      <tr><td colspan="2">';
        $l_html.=$crlf.'        <table width=100%  border="1" bordercolor="#00000">';
        $l_html.=$crlf.'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
        $l_html.=$crlf.'          <td rowspan=2><b>Aditivo</td>';
        $l_html.=$crlf.'          <td rowspan=2><b>Número</td>';
        $l_html.=$crlf.'          <td rowspan=2><b>Outra parte</td>';
        $l_html.=$crlf.'          <td rowspan=2><b>Data</td>';
        $l_html.=$crlf.'          <td colspan=5><b>Valores'.(($w_sb_moeda!='') ? ' ('.$w_sb_moeda.')' : '').'</td>';
        $l_html.=$crlf.'          <td colspan=2><b>Saldos'.(($w_sb_moeda!='') ? ' ('.$w_sb_moeda.')' : '').'</td>';
        $l_html.=$crlf.'        </tr>';
        $l_html.=$crlf.'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
        $l_html.=$crlf.'          <td><b>Emissão</td>';
        $l_html.=$crlf.'          <td><b>Canc.</td>';
        $l_html.=$crlf.'          <td><b>Total</td>';
        $l_html.=$crlf.'          <td><b>Liquidado</td>';
        $l_html.=$crlf.'          <td><b>Pago</td>';
        $l_html.=$crlf.'          <td><b>A liquidar</td>';
        $l_html.=$crlf.'          <td><b>A pagar</td>';
        $l_html.=$crlf.'        </tr>';
        if (count($RS1)<=0) {
          // Se não foram selecionados registros, exibe mensagem 
          $l_html.=$crlf.'      <tr><td colspan=11 align="center"><b>Não foram encontrados registros.</b></td></tr>';
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
            $l_html.=$crlf.'      <tr valign="top">';
            $l_html.=$crlf.'        <td nowrap>'.nvl(f($row,'cd_aditivo'),'---').'</td>';
            $l_html.=$crlf.'        <td nowrap>'.f($row,'sg_tipo_documento').' '.f($row,'numero').'&nbsp;';
            if (f($row,'abrange_inicial')=='S')   { $l_html.= '('.f($row,'sg_inicial').')';   $w_legenda_ini = ' ('.f($row,'sg_inicial').') Valor inicial'; }
            if (f($row,'abrange_acrescimo')=='S') { $l_html.= '('.f($row,'sg_acrescimo').')'; $w_legenda_acr = ' ('.f($row,'sg_acrescimo').') Acréscimo/Supressão'; }
            if (f($row,'abrange_reajuste')=='S')  { $l_html.= '('.f($row,'sg_reajuste').')';  $w_legenda_rea = ' ('.f($row,'sg_reajuste').') Reajuste'; }
            $l_html.=$crlf.'        <td>'.nvl(f($row,'nm_outra_parte'),'---').'</td>';
            $l_html.=$crlf.'        <td align="center">'.Nvl(FormataDataEdicao(f($row,'data'),5),'---').'</td>';
            $l_html.=$crlf.'        <td align="right">'.formatNumber(Nvl(f($row,'valor'),0)).'</td>';
            $l_html.=$crlf.'        <td align="right">'.formatNumber(Nvl(f($row,'vl_cancelamento'),0)).'</td>';
            $l_html.=$crlf.'        <td align="right">'.formatNumber(Nvl(f($row,'valor'),0) - Nvl(f($row,'vl_cancelamento'),0)).'</td>';
            $l_html.=$crlf.'        <td align="right">'.formatNumber(Nvl(f($row,'vl_liquidado'),0)).'</td>';
            $l_html.=$crlf.'        <td align="right">'.formatNumber(Nvl(f($row,'vl_pago'),0)).'</td>';
            $l_html.=$crlf.'        <td align="right">'.formatNumber(Nvl(f($row,'valor'),0) - Nvl(f($row,'vl_cancelamento'),0) - Nvl(f($row,'vl_liquidado'),0)).'</td>';
            $l_html.=$crlf.'        <td align="right">'.formatNumber(Nvl(f($row,'valor'),0) - Nvl(f($row,'vl_cancelamento'),0) - Nvl(f($row,'vl_pago'),0)).'</td>';
            $l_html.=$crlf.'      </tr>';
            $w_total  += (nvl(f($row,'valor'),0) - nvl(f($row,'vl_cancelamento'),0));
            $w_nota   += nvl(f($row,'valor'),0);
            $w_cancel += nvl(f($row,'vl_cancelamento'),0);
            $w_liq    += nvl(f($row,'vl_liquidado'),0);
            $w_pago   += nvl(f($row,'vl_pago'),0);
            $w_aliq   += ((nvl(f($row,'valor'),0) - nvl(f($row,'vl_cancelamento'),0)) - nvl(f($row,'vl_liquidado'),0));
            $w_apag   += ((nvl(f($row,'valor'),0) - nvl(f($row,'vl_cancelamento'),0)) - nvl(f($row,'vl_pago'),0));
          } 
          $l_html.=$crlf.'      <trvalign="top">';
          $l_html.=$crlf.'        <td align="right" colspan=4>Totais</td>';
          $l_html.=$crlf.'        <td align="right">'.Nvl(formatNumber($w_nota),0).'</td>';
          $l_html.=$crlf.'        <td align="right">'.Nvl(formatNumber($w_cancel),0).'</td>';
          $l_html.=$crlf.'        <td align="right">'.Nvl(formatNumber($w_total),0).'</td>';
          $l_html.=$crlf.'        <td align="right">'.Nvl(formatNumber($w_liq),0).'</td>';
          $l_html.=$crlf.'        <td align="right">'.Nvl(formatNumber($w_pago),0).'</td>';
          $l_html.=$crlf.'        <td align="right">'.Nvl(formatNumber($w_aliq),0).'</td>';
          $l_html.=$crlf.'        <td align="right">'.Nvl(formatNumber($w_apag),0).'</td>';
        } 
        $l_html.=$crlf.'    </table>';
        $w_legenda = $w_legenda_ini.$w_legenda_acr.$w_legenda_rea;
        if (nvl($w_legenda,'')!='') $l_html.=$crlf.'      <tr><td colspan="2">Legenda: '.$w_legenda.'</td></tr>';
        $l_html.=$crlf.'  </td>';
        $l_html.=$crlf.'</tr>';
      }
    }
    // Aditivos
    if(substr($w_sigla,0,3)=='GCR' || substr($w_sigla,0,3)=='GCD') {
      $sql = new db_getAcordoAditivo; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,null,$l_chave,null,null,null,null,null,null,null,null,null);
      $RS1 = SortArray($RS1,'codigo','desc');
      if (count($RS1)>0) {
        $l_html.=$crlf.'      <tr><td colspan="2"><br><font size="2"><b>ADITIVOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';   
        $l_html.=$crlf.'      <tr><td colspan="2" align="center">';
        $l_html.=$crlf.'        <table class="tudo" width=100%  border="1" bordercolor="#00000">';
        $l_html.=$crlf.'        <tr align="center">';
        $l_html.=$crlf.'          <td rowspan=2 bgcolor="'.$conTrBgColor.'"><b>Código</td>';
        $l_html.=$crlf.'          <td rowspan=2 bgcolor="'.$conTrBgColor.'"><b>Período</td>';
        $l_html.=$crlf.'          <td rowspan=2 bgcolor="'.$conTrBgColor.'"><b>Objeto</td>';
        $l_html.=$crlf.'          <td colspan=4 bgcolor="'.$conTrBgColor.'"><b>Totais do aditivo</td>';
        $l_html.=$crlf.'          <td colspan=4 bgcolor="'.$conTrBgColor.'"><b>Parcelas do aditivo</td>';
        $l_html.=$crlf.'        </tr>';
        $l_html.=$crlf.'        <tr align="center">';
        $l_html.=$crlf.'          <td bgcolor="'.$conTrBgColor.'"><b>Inicial</td>';
        $l_html.=$crlf.'          <td bgcolor="'.$conTrBgColor.'"><b>Reajuste</td>';
        $l_html.=$crlf.'          <td bgcolor="'.$conTrBgColor.'"><b>Acr./Supr.</td>';
        $l_html.=$crlf.'          <td bgcolor="'.$conTrBgColor.'"><b>Total</td>';
        $l_html.=$crlf.'          <td bgcolor="'.$conTrBgColor.'"><b>Inicial</td>';
        $l_html.=$crlf.'          <td bgcolor="'.$conTrBgColor.'"><b>Reajuste</td>';
        $l_html.=$crlf.'          <td bgcolor="'.$conTrBgColor.'"><b>Acr./Supr.</td>';
        $l_html.=$crlf.'          <td bgcolor="'.$conTrBgColor.'"><b>Total</td>';
        $l_html.=$crlf.'        </tr>';
        if (count($RS1)==0) {
          // Se não foram selecionados registros, exibe mensagem 
          $l_html.=$crlf.'      <tr><td colspan=11 align="center"><b>Não foram encontrados registros.</b></td></tr>';
        } else {
          // Lista os registros selecionados para listagem 
          $w_tot_in = 0;
          $w_tot_rj = 0;
          $w_tot_ac = 0;
          $w_tot_ad = 0;
          foreach($RS1 as $row) {
            // Recupera os arquivos do aditivo
            $sql = new db_getAditivoAnexo; $RS2 = $sql->getInstanceOf($dbms, $l_chave, f($row,'sq_acordo_aditivo'), null);
            $RS2 = SortArray($RS2, 'nome', 'asc', 'tamanho', 'asc');

            $l_html.=$crlf.'      <tr valign="top" align="center">';
            $l_html.=$crlf.'        <td align="left" width="1%" nowrap>'.f($row,'codigo').'</td>';
            $l_html.=$crlf.'        <td>'.Nvl(FormataDataEdicao(f($row,'inicio'),5),'---').' a '.Nvl(FormataDataEdicao(f($row,'fim'),5),'---').'</td>';
            $l_html.=$crlf.'        <td align="left">'.crlf2br(f($row,'objeto'));
            if (nvl(f($row,'observacao'),'')!='') $l_html.='<hr NOSHADE color=#000000 size=1><b>Observação:</b>&nbsp;'.crlf2br(f($row,'observacao'));
            if (count($RS2)) {
              $l_html.='<hr NOSHADE color=#000000 size=1><b>Arquivo(s) anexado(s):</b>';
              foreach($RS2 as $row2) {
                $l_html.='<br>'.LinkArquivo('HL', $w_cliente, f($row, 'arquivo'), null, null, f($row2,'nome'), null) . ' (' . round(f($row2, 'tamanho') / 1024, 1) . ' KB) ';
              }
            }
            $l_html.='</td>';
            $l_html.=$crlf.'        <td align="right">'.formatNumber(f($row,'valor_inicial')).'</td>';
            $l_html.=$crlf.'        <td align="right">'.formatNumber(f($row,'valor_reajuste')).'</td>';
            $l_html.=$crlf.'        <td align="right">'.formatNumber(f($row,'valor_acrescimo')).'</td>';
            $l_html.=$crlf.'        <td align="right"><b>'.formatNumber(f($row,'valor_aditivo')).'</b></td>';
            $l_html.=$crlf.'        <td align="right">'.formatNumber(f($row,'parcela_inicial')).'</td>';
            $l_html.=$crlf.'        <td align="right">'.formatNumber(f($row,'parcela_reajustada')).'</td>';
            $l_html.=$crlf.'        <td align="right">'.formatNumber(f($row,'parcela_acrescida')).'</td>';
            $l_html.=$crlf.'        <td align="right"><b>'.formatNumber(f($row,'parcela_aditivo')).'</b></td>';
            $l_html.=$crlf.'      </tr>';
            $w_tot_in += f($row,'valor_inicial');
            $w_tot_rj += f($row,'valor_reajuste');
            $w_tot_ac += f($row,'valor_acrescimo');
            $w_tot_ad += f($row,'valor_aditivo');
          } 
          $l_html.=$crlf.'      <tr valign="top" align="center">';
          $l_html.=$crlf.'        <td colspan=3 align="right"><b>Totais</b></td>';
          $l_html.=$crlf.'        <td align="right">'.formatNumber($w_tot_in).'</td>';
          $l_html.=$crlf.'        <td align="right">'.formatNumber($w_tot_rj).'</td>';
          $l_html.=$crlf.'        <td align="right">'.formatNumber($w_tot_ac).'</td>';
          $l_html.=$crlf.'        <td align="right"><b>'.formatNumber($w_tot_ad).'</b></td>';
          $l_html.=$crlf.'        <td colspan=4>&nbsp;</td>';
        } 
        $l_html.=$crlf.'      </center>';
        $l_html.=$crlf.'    </table>';
        $l_html.=$crlf.'  </td>';
        $l_html.=$crlf.'</tr>';
      }
    } elseif(substr($w_sigla,0,3)=='GCZ') {
      $sql = new db_getAcordoAditivo; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,null,$l_chave,null,null,null,null,null,null,null,null,null);
      $RS1 = SortArray($RS1,'codigo','desc');
      if (count($RS1)>0) {
        $l_html.=$crlf.'      <tr><td colspan="2"><br><font size="2"><b>ADITIVOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';   
        $l_html.=$crlf.'      <tr><td colspan="2" align="center">';
        $l_html.=$crlf.'        <table width=100%  border="1" bordercolor="#00000">';
        $l_html.=$crlf.'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
        $l_html.=$crlf.'          <td><b>Código</td>';
        $l_html.=$crlf.'          <td><b>Período</td>';
        $l_html.=$crlf.'          <td><b>Objeto</td>';
        if ($w_cliente!=10135) { 
        //ABDI
          $l_html.=$crlf.'          <td><b>Documento</td>';
          $l_html.=$crlf.'          <td><b>Data</td>';
        }
        $l_html.=$crlf.'          <td><b>Observação</td>';
        $l_html.=$crlf.'        </tr>';
        if (count($RS1)==0) {
          // Se não foram selecionados registros, exibe mensagem 
          $l_html.=$crlf.'      <tr><td colspan=6 align="center"><b>Não foram encontrados registros.</b></td></tr>';
        } else {
          // Lista os registros selecionados para listagem 
          foreach($RS1 as $row) {
            $l_html.=$crlf.'      <tr valign="top" align="center">';
            $l_html.=$crlf.'        <td align="left" width="1%" nowrap>'.f($row,'codigo').'</td>';
            $l_html.=$crlf.'        <td width="1%" nowrap>'.Nvl(FormataDataEdicao(f($row,'inicio'),5),'---').' a '.Nvl(FormataDataEdicao(f($row,'fim'),5),'---').'</td>';
            $l_html.=$crlf.'        <td align="left">'.f($row,'objeto').'</td>';
            if ($w_cliente!=10135) { 
              //ABDI
              $l_html.=$crlf.'        <td align="left" width="1%" nowrap>'.nvl(f($row,'documento_origem'),'---').'</td>';
              $l_html.=$crlf.'        <td width="1%" nowrap>'.Nvl(FormataDataEdicao(f($row,'documento_data'),5),'---').'</td>';
            }
            $l_html.=$crlf.'        <td align="left">'.nvl(f($row,'observacao'),'---').'</td>';
            $l_html.=$crlf.'      </tr>';
          } 
        } 
        $l_html.=$crlf.'    </table>';
        $l_html.=$crlf.'  </td>';
        $l_html.=$crlf.'</tr>';
      }
    }

    // Exibe ficha completa
    if ($l_P1==4 && substr($w_sigla,0,3)!='GCZ' && $w_termo) {
      // Termo de referência
      $l_html.=$crlf.'      <tr><td colspan="2"><br><font size="2"><b>TERMO DE REFERÊNCIA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html.=$crlf.'      <tr valign="top">';
      $l_html.=$crlf.'        <td ><b>Atividades a serem desenvolvidas:</b></td>';
      $l_html.=$crlf.'        <td>'.nvl(CRLF2BR(f($RS,'atividades')),'---').'</td></tr>';
      $l_html.=$crlf.'      <tr valign="top">';
      $l_html.=$crlf.'        <td><b>Produtos a serem entregues:</b></td>';
      $l_html.=$crlf.'        <td>'.nvl(CRLF2BR(f($RS,'produtos')),'---').'</td></tr>';
      $l_html.=$crlf.'      <tr valign="top">';
      $l_html.=$crlf.'        <td ><b>Documentação vinculada:</b></td>';
      $l_html.=$crlf.'        <td>'.nvl(CRLF2BR(f($RS,'requisitos')),'---').'</td></tr>';
      if (substr($w_sigla,0,3)=='GCB'){
        $l_html.=$crlf.'      <tr valign="top">';
        $l_html.=$crlf.'      <td><b>Código para o bolsista:</b></td>';
        $l_html.=$crlf.'      <td>'.Nvl(f($RS,'codigo_externo'),'---').'</td></tr>';
      } else {
        $l_html.=$crlf.'      <tr valign="top">';
        $l_html.=$crlf.'        <td><b>Código para a outra parte:</b></td>';
        $l_html.=$crlf.'        <td>'.Nvl(f($RS,'codigo_externo'),'---').'</td></tr>';
      }
      if ($w_tipo_visao!=2 && Nvl(f($RS,'cd_modalidade'),'')=='F') {
        $l_html.=$crlf.'      <tr><td><b>Pemite vinculação de projetos?</b></td>';
        $l_html.=$crlf.'        <td>'.f($RS,'nm_vincula_projeto').'</td></tr>';
        $l_html.=$crlf.'      <tr><td><b>Pemite vinculação de demandas?</b></td>';
        $l_html.=$crlf.'        <td>'.f($RS,'nm_vincula_demanda').'</td></tr>';
        $l_html.=$crlf.'       <tr><td><b>Pemite vinculação de viagens?</b></td>';
        $l_html.=$crlf.'        <td>'.f($RS,'nm_vincula_viagem').'</td></tr>';
      }
    } 
    // Outra parte
    if     (substr($w_sigla,0,3)=='GCB')$l_html.=$crlf.'      <tr><td colspan="2"><br><font size="2"><b>BOLSISTA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    elseif (substr($w_sigla,0,3)=='GCZ')$l_html.=$crlf.'      <tr><td colspan="2"><br><font size="2"><b>DETENTOR<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    else                                $l_html.=$crlf.'      <tr><td colspan="2"><br><font size="2"><b>OUTRA(S) PARTE(S)<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $sql = new db_getConvOutraParte; $RSQuery = $sql->getInstanceOf($dbms,null,$l_chave,null,null);
    if (count($RSQuery)==0) {
      if     (substr($w_sigla,0,3)=='GCB') $l_html.=$crlf.'      <tr><td colspan=2 align="center">Bolsita não informado';
      elseif (substr($w_sigla,0,3)=='GCZ') $l_html.=$crlf.'      <tr><td colspan=2 align="center">Detentor não informado';
      else                                 $l_html.=$crlf.'      <tr><td colspan=2 align="center">Outra parte não informada';
    } else {
      foreach($RSQuery as $row) { 
        $l_html.=$crlf.'      <tr><td colspan=2 bgColor="'.$conTrBgColor.'" style="border: 1px solid rgb(0,0,0);" ><b>';
        $l_html.=$crlf.'          '.f($row,'nm_pessoa').' ('.f($row,'nome_resumido').')';
        if ($w_tipo_pessoa=='F') $l_html.=$crlf.'          - '.f($row,'cpf').'</b>';
        else                     $l_html.=$crlf.'          - '.f($row,'cnpj').'</b>';
        if ($l_P1==4) {
          $sql = new db_getBenef; $RSQuery1 = $sql->getInstanceOf($dbms,$w_cliente,Nvl(f($row,'outra_parte'),0),null,null,null,null,Nvl(f($row,'sq_tipo_pessoa'),0),null,null,null,null,null,null,null, null, null, null, null);
          foreach($RSQuery1 as $row1){$RSQuery1=$row1; break;}
          if ($w_tipo_pessoa=='F') {
            $l_html.=$crlf.'      <tr><td colspan="2">';
            $l_html.=$crlf.'          <tr><td><b>Sexo:</b></td>'; 
            $l_html.=$crlf.'              <td>'.f($RSQuery1,'nm_sexo').'</td></tr>';
            $l_html.=$crlf.'          <tr><td><b>Data de nascimento:</b></td>'; 
            $l_html.=$crlf.'              <td>'.FormataDataEdicao(Nvl(f($RSQuery1,'nascimento'),'---')).'</td></tr>';
            $l_html.=$crlf.'          <tr><td><b>Identidade:</b></td>'; 
            $l_html.=$crlf.'              <td>'.f($RSQuery1,'rg_numero').'</td></tr>';
            $l_html.=$crlf.'          <tr><td><b>Data de emissão:</b></td>'; 
            $l_html.=$crlf.'              <td>'.FormataDataEdicao(Nvl(f($RSQuery1,'rg_emissao'),'---')).'</td>';
            $l_html.=$crlf.'          <tr><td><b>Órgão emissor:</b></td>'; 
            $l_html.=$crlf.'              <td>'.f($RSQuery1,'rg_emissor').'</td></tr>';
            $l_html.=$crlf.'          <tr><td><b>Passaporte:</b></td>'; 
            $l_html.=$crlf.'              <td>'.Nvl(f($RSQuery1,'passaporte_numero'),'---').'</td></tr>';
            $l_html.=$crlf.'          <tr><td><b>País emissor:</b></td>'; 
            $l_html.=$crlf.'              <td>'.Nvl(f($RSQuery1,'nm_pais_passaporte'),'---').'</td></tr>';
          } elseif (f($RSQuery1,'sq_tipo_pessoa')==2) {
            $l_html.=$crlf.'      <tr><td><b>Inscrição estadual:</b></td>'; 
            $l_html.=$crlf.'          <td>'.Nvl(f($RSQuery1,'inscricao_estadual'),'---').'</td></tr>';
          } 
          if (f($RSQuery1,'sq_tipo_pessoa')==1 || f($RSQuery1,'sq_tipo_pessoa')==3) {
            $l_html.=$crlf.'      <tr><td colspan="2" align="center" style="border: 1px solid rgb(0,0,0);"><b>Endereço comercial, Telefones e e-Mail</td>';
          } else {
            $l_html.=$crlf.'      <tr><td colspan="2" align="center" style="border: 1px solid rgb(0,0,0);"><b>Endereço principal, Telefones e e-Mail</td>';
          } 
          $l_html.=$crlf.'      <tr><td colspan="2">';
          $l_html.=$crlf.'          <tr valign="top">';
          $l_html.=$crlf.'            <td><b>Telefone:</b></td>'; 
          if (nvl(f($RSQuery1,'ddd'),'nulo')!='nulo') {
            $l_html.=$crlf.'            <td>('.f($RSQuery1,'ddd').') '.f($RSQuery1,'nr_telefone').'</td></tr>';
          } else {
            $l_html.=$crlf.'            <td>---</td></tr>';
          }
          $l_html.=$crlf.'          <tr><td><b>Fax:</b></td>'; 
          $l_html.=$crlf.'            <td>'.Nvl(f($RSQuery1,'nr_fax'),'---').'</td></tr>';
          $l_html.=$crlf.'          <tr><td><b>Celular:</b></td>'; 
          $l_html.=$crlf.'            <td>'.Nvl(f($RSQuery1,'nr_celular'),'---').'</td></tr>';
          $l_html.=$crlf.'          <tr valign="top">';
          $l_html.=$crlf.'             <td><b>Endereço:</b></td>'; 
          $l_html.=$crlf.'            <td>'.f($RSQuery1,'logradouro').'</td></tr>';
          $l_html.=$crlf.'          <tr><td><b>Complemento:</b></td>'; 
          $l_html.=$crlf.'            <td>'.Nvl(f($RSQuery1,'complemento'),'---').'</td></tr>';
          $l_html.=$crlf.'          <tr><td><b>Bairro:</b></td>'; 
          $l_html.=$crlf.'            <td>'.Nvl(f($RSQuery1,'bairro'),'---').'</td></tr>';
          $l_html.=$crlf.'          <tr valign="top">';
          if (f($RSQuery1,'pd_pais')=='S') {
            $l_html.=$crlf.'          <td><b>Cidade:</b></td>'; 
            $l_html.=$crlf.'          <td>'.f($RSQuery1,'nm_cidade').'-'.f($RSQuery1,'co_uf').'</td></tr>';
          } else {
            $l_html.=$crlf.'          <td><b>Cidade:</b></td>'; 
            $l_html.=$crlf.'          <td>'.f($RSQuery1,'nm_cidade').'-'.f($RSQuery1,'nm_pais').'</td></tr>';
          } 
          $l_html.=$crlf.'          <tr><td><b>CEP:</b></td>'; 
          $l_html.=$crlf.'            <td>'.f($RSQuery1,'cep').'</td></tr>';
          if (Nvl(f($RSQuery1,'email'),'nulo')!='nulo') {
            if ($l_tipo!='WORD') {
              $l_html.=$crlf.'              <tr><td><b>e-Mail:</b></td>';
              $l_html.=$crlf.'                <td><a class="hl" href="mailto:'.f($RSQuery1,'email').'">'.f($RSQuery1,'email').'</td></tr>';
            } else {
              $l_html.=$crlf.'              <tr><td><b>e-Mail:</b></td>';
              $l_html.=$crlf.'                <td>'.f($RSQuery1,'email').'</td></tr>';
            } 
          } else {
            $l_html.=$crlf.'              <tr><td><b>e-Mail:</b></td>';
            $l_html.=$crlf.'                <td>---</td></tr>';
          }  
          if (substr($w_sigla,0,3)=='GCR') {
            $l_html.=$crlf.'      <tr><td colspan="2" align="center" style="border: 1px solid rgb(0,0,0);"><b>Dados para recebimento ('.upper(f($RS,'nm_forma_pagamento')).')</td>';
          } elseif (substr($w_sigla,0,3)=='GCD') {
            $l_html.=$crlf.'      <tr><td colspan="2" align="center" style="border: 1px solid rgb(0,0,0);"><b>Dados para pagamento ('.upper(f($RS,'nm_forma_pagamento')).')</td>';
          } elseif (substr($w_sigla,0,3)!='GCZ') {
            $l_html.=$crlf.'      <tr><td colspan="2" align="center" style="border: 1px solid rgb(0,0,0);"><b>Dados para pagamento/recebimento ('.upper(f($RS,'nm_forma_pagamento')).')</td>';
          } 
          if (substr($w_sigla,0,3)!='GCR' && substr($w_sigla,0,3)!='GCZ') {
            if (!(strpos('CREDITO,DEPOSITO',f($RS,'sg_forma_pagamento'))===false)) {
              if (Nvl(f($RS,'cd_banco'),'')>'') {
                $l_html.=$crlf.'          <tr><td><b>Banco:</b></td>';
                $l_html.=$crlf.'                <td>'.f($RS,'cd_banco').' - '.f($RS,'nm_banco').'</td></tr>';
                $l_html.=$crlf.'          <tr><td><b>Agência:</b></td>';
                $l_html.=$crlf.'              <td>'.f($RS,'cd_agencia').' - '.f($RS,'nm_agencia').'</td></tr>';
                if (f($RS,'exige_operacao')=='S') $l_html.=$crlf.'          <tr><td><b>Operação:</b></td><td>'.Nvl(f($RS,'operacao_conta'),'---').'</td>';
                $l_html.=$crlf.'          <tr><td><b>Número da conta:</b></td>';
                $l_html.=$crlf.'              <td>'.Nvl(f($RS,'numero_conta'),'---').'</td></tr>';
              } else {
                $l_html.=$crlf.'          <tr><td><b>Banco:</b></td>';
                $l_html.=$crlf.'              <td>---</td></tr>';
                $l_html.=$crlf.'          <tr><td><b>Agência:</b></td>';
                $l_html.=$crlf.'              <td>---</td></tr>';
                if (f($RS,'exige_operacao')=='S') $l_html.=$crlf.'          <tr><td><b>Operação:</b></td><td>---</td></tr>';
                $l_html.=$crlf.'          <tr><td><b>Número da conta:</b></td>';
                $l_html.=$crlf.'              <td>---</td></tr>';
              } 
            } elseif (f($RS,'sg_forma_pagamento')=='ORDEM') {
              $l_html.=$crlf.'          <tr valign="top">';
              if (Nvl(f($RS,'cd_banco'),'')>'') {
                $l_html.=$crlf.'          <td><b>Banco:<b><br>'.f($RS,'cd_banco').' - '.f($RS,'nm_banco').'</td>';
                $l_html.=$crlf.'          <td><b>Agência:<b><br>'.f($RS,'cd_agencia').' - '.f($RS,'nm_agencia').'</td>';
              } else {
                $l_html.=$crlf.'          <td><b>Banco:<b><br>---</td>';
                $l_html.=$crlf.'          <td><b>Agência:<b><br>---</td>';
              } 
            } elseif (f($RS,'sg_forma_pagamento')=='EXTERIOR') {
              $l_html .= $crlf.'    <tr><td colspan=2><table border=0 cellpadding=0 cellspacing=0 width="100%">';
              $l_html.=$crlf.'          <tr valign="top">';
              $l_html.=$crlf.'            <td>Banco:<b><br>'.nvl(f($RS,'banco_estrang'),'---').'</td>';
              $l_html.=$crlf.'            <td>ABA Code:<b><br>'.Nvl(f($RS,'aba_code'),'---').'</td>';
              $l_html.=$crlf.'            <td>SWIFT Code:<b><br>'.Nvl(f($RS,'swift_code'),'---').'</td>';
              $l_html.=$crlf.'          <tr><td colspan=3>Endereço da agência:<b><br>'.Nvl(f($RS,'endereco_estrang'),'---').'</td>';
              $l_html.=$crlf.'          <tr valign="top">';
              $l_html.=$crlf.'            <td colspan=2>Agência:<b><br>'.Nvl(f($RS,'agencia_estrang'),'---').'</td>';
              $l_html.=$crlf.'            <td>Número da conta:<b><br>'.Nvl(f($RS,'numero_conta'),'---').'</td>';
              $l_html.=$crlf.'          <tr valign="top">';
              $l_html.=$crlf.'            <td colspan=2>Cidade:<b><br>'.nvl(f($RS,'nm_cidade'),'---').'</td>';
              $l_html.=$crlf.'            <td>País:<b><br>'.nvl(f($RS,'nm_pais'),'---').'</td>';
              $l_html.=$crlf.'          <tr><td colspan=3>Informações adicionais:<b><br>'.crlf2br(nvl(f($RS,'informacoes'),'---')).'</td>';
              $l_html .= $crlf.'    </table>';
            } 
          } 
        } 
        // Representantes legais e contatos
        if ($w_tipo_pessoa=='J') {
          if ($w_tipo_visao!=2) {
            // i==1: exibe representantes legais; i==2: contatos
            for ($i=1; $i<=2; $i++) {
              $sql = new db_getConvOutroRep; $RSQuery = $sql->getInstanceOf($dbms,$l_chave,null,f($row,'sq_acordo_outra_parte'),$i);
              $RSQuery = SortArray($RSQuery,'nome_indice','asc');
              $label = (($i==1) ? 'Representantes legais' : 'Contatos');
              $l_html.=$crlf.'      <tr><td colspan="2" align="center" style="border: 1px solid rgb(0,0,0);"><b>'.$label.'</b></td>';
              if (count($RSQuery)==0) {
                $l_html.=$crlf.'      <tr><td colspan=2><b>'.$label.' não informados</b></font></td></tr>';
              } else {
                $l_html.=$crlf.'      <tr><td colspan="2" align="center">';
                $l_html.=$crlf.'        <table width=100%  border="1" bordercolor="#00000">';              
                $l_html.=$crlf.'          <tr><td bgColor="'.$conTrBgColor.'" align="center"><b><b>Nome</b></td>';
                $l_html.=$crlf.'            <td bgColor="'.$conTrBgColor.'" align="center"><b>CPF</b></td>';
                $l_html.=$crlf.'            <td bgColor="'.$conTrBgColor.'" align="center"><b>Sexo</b></td>';
                $l_html.=$crlf.'            <td bgColor="'.$conTrBgColor.'" align="center"><b>Identidade</b></td>';
                $l_html.=$crlf.'            <td bgColor="'.$conTrBgColor.'" align="center"><b>Orgão emissão</b></td>';
                $l_html.=$crlf.'            <td bgColor="'.$conTrBgColor.'" align="center"><b><b>DDD</b></td>';
                $l_html.=$crlf.'            <td bgColor="'.$conTrBgColor.'" align="center"><b><b>Telefone</b></td>';
                $l_html.=$crlf.'            <td bgColor="'.$conTrBgColor.'" align="center"><b><b>Celular</b></td>';
                $l_html.=$crlf.'            <td bgColor="'.$conTrBgColor.'" align="center"><b><b>e-Mail</b></td>';
                $l_html.=$crlf.'            <td bgColor="'.$conTrBgColor.'" align="center"><b><b>Cargo</b></td>';
                $l_html.=$crlf.'          </tr>';
                $w_cor=$w_TrBgColor;
                foreach($RSQuery as $row2) {
                  $l_html.=$crlf.'      <tr><td>'.ExibePessoa($w_dir_volta,$w_cliente,f($row2,'sq_pessoa'),$TP,f($row2,'nm_pessoa')).'</td>';
                  $l_html.=$crlf.'        <td align="center">'.f($row2,'cpf').'</td>';
                  $l_html.=$crlf.'        <td>'.f($row2,'nm_sexo').'</td>';
                  $l_html.=$crlf.'        <td>'.Nvl(f($row2,'rg_numero'),'---').'</td>';
                  $l_html.=$crlf.'        <td>'.Nvl(f($row2,'rg_emissor'),'---').'</td>';
                  $l_html.=$crlf.'        <td align="center" >'.Nvl(f($row2,'ddd'),'---').'</td>';
                  $l_html.=$crlf.'        <td>'.Nvl(f($row2,'nr_telefone'),'---').'</td>';
                  $l_html.=$crlf.'        <td>'.Nvl(f($row2,'nr_celular'),'---').'</td>';
                  if (Nvl(f($row2,'email'),'nulo')!='nulo') {
                    if ($l_tipo!='WORD') {
                      $l_html.=$crlf.'        <td><a class="hl" href="mailto:'.f($row2,'email').'">'.f($row2,'email').'</a></td>';
                    } else {
                      $l_html.=$crlf.'        <td>'.f($row2,'email').'</td>';
                    } 
                  } else {
                    $l_html.=$crlf.'        <td>---</td>';
                  } 
                  $l_html.=$crlf.'        <td>'.Nvl(f($row2,'cargo'),'---').'</td>';
                }
                $l_html.=$crlf.'        </table></td></tr>';      
              }
            }
          }
        }
      }
    }
  } 

  if ($O!='V') {
    // Resumo financeiro do contrato
    $sql = new db_getAcordoParcela; $RS = $sql->getInstanceOf($dbms,$l_chave,null,'RESFIN',null,null,null,null,null,null,null);
    $RS = SortArray($RS,'inicio','asc', 'ini_aditivo', 'asc');
    if (count($RS)>0) {
      $l_html.=$crlf.'      <tr><td colspan="2"><br><font size="2"><b>RESUMO FINANCEIRO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html.=$crlf.'      <tr><td colspan="2" align="center">';
      $l_html.=$crlf.'        <table class="tudo" width=100%  border="1" bordercolor="#00000">';
      $l_html.=$crlf.'          <tr align="center">';
      $l_html.=$crlf.'            <td rowspan=2 bgColor="'.$conTrBgColor.'"><b>Aditivo</b></td>';
      $l_html.=$crlf.'            <td rowspan=2 bgColor="'.$conTrBgColor.'"><b>Período</b></td>';
      $l_html.=$crlf.'            <td colspan=3 bgColor="'.$conTrBgColor.'"><b>Valores'.(($w_sb_moeda!='') ? ' ('.$w_sb_moeda.')' : '').'</b></td>';
      $l_html.=$crlf.'            <td colspan=2 bgColor="'.$conTrBgColor.'"><b>Saldos'.(($w_sb_moeda!='') ? ' ('.$w_sb_moeda.')' : '').'</b></td>';
      $l_html.=$crlf.'            <td colspan=2 bgColor="'.$conTrBgColor.'"><b>%</b></td>';
      $l_html.=$crlf.'          </tr>';
      $l_html.=$crlf.'          <tr align="center">';
      $l_html.=$crlf.'            <td bgColor="'.$conTrBgColor.'"><b>Previsto (1)</b></td>';
      $l_html.=$crlf.'            <td bgColor="'.$conTrBgColor.'"><b>Liquidado (2)</b></td>';
      $l_html.=$crlf.'            <td bgColor="'.$conTrBgColor.'"><b>Pago (3)</b></td>';
      $l_html.=$crlf.'            <td bgColor="'.$conTrBgColor.'"><b>A liquidar (1)-(2)</b></td>';
      $l_html.=$crlf.'            <td bgColor="'.$conTrBgColor.'"><b>A pagar (2)-(3)</b></td>';
      $l_html.=$crlf.'            <td bgColor="'.$conTrBgColor.'"><b>Liquidado</b></td>';
      $l_html.=$crlf.'            <td bgColor="'.$conTrBgColor.'"><b>Pago</b></td>';
      $l_html.=$crlf.'          </tr>';
      $w_cor=$w_TrBgColor;
      $w_total    = 0;
      $w_total_i  = 0;
      $w_total_e  = 0;
      $w_total_r  = 0;
      $w_atual    = 0;
      $w_tot_parc = 0;
      $w_tot_liq  = 0;
      foreach($RS as $row) {
        $l_html.=$crlf.'        <tr valign="top">';
        $w_tot_parc = 0;
        $w_atual = f($row,'sq_acordo_parcela');
        $l_html.=$crlf.'          <td align="center">'.nvl(f($row,'codigo'),'---').'</td>';
        if(nvl(f($row,'sq_acordo_aditivo'),'')=='') $l_html.=$crlf.'        <td align="center">'.FormataDataEdicao(f($row,'inicio'),5).' a '.FormataDataEdicao(f($row,'fim'),5).'</td>';
        else                                        $l_html.=$crlf.'        <td align="center">'.FormataDataEdicao(f($row,'ini_aditivo'),5).' a '.FormataDataEdicao(f($row,'fim_aditivo'),5).'</td>';
        $l_html.=$crlf.'        <td align="right">'.formatNumber(f($row,'valor_previsto')).'</td>';
        $l_html.=$crlf.'        <td align="right">'.formatNumber(nvl(f($row,'valor_liquidado'),0)).'</td>';
        $l_html.=$crlf.'        <td align="right">'.formatNumber(nvl(f($row,'valor_pago'),0)).'</td>';
        $l_html.=$crlf.'        <td align="right">'.formatNumber(f($row,'valor_previsto')-f($row,'valor_liquidado')).'</td>';
        $l_html.=$crlf.'        <td align="right">'.formatNumber(f($row,'valor_liquidado')-f($row,'valor_pago')).'</td>';
        if (f($row,'valor_previsto')!=0) {
          $l_html.=$crlf.'        <td align="center">'.formatNumber(f($row,'valor_liquidado')/f($row,'valor_previsto')*100,2).'</td>';
          $l_html.=$crlf.'        <td align="center">'.formatNumber(f($row,'valor_pago')/f($row,'valor_previsto')*100,2).'</td>';
        } else {
          $l_html.=$crlf.'        <td align="center">0,00</td>';
          $l_html.=$crlf.'        <td align="center">0,00</td>';
        }
        $w_total   += f($row,'valor');
        $w_total_i += f($row,'valor_previsto');
        $w_total_e += f($row,'valor_liquidado');
        $w_total_r += f($row,'valor_pago');
        $l_html.=$crlf.'      </tr>';
      }
      if (count($RS)>1) {
        $l_html.=$crlf.'      <tr valign="top">';
        $l_html.=$crlf.'        <td align="right" colspan=2><b>Totais</b></td>';
        $l_html.=$crlf.'        <td align="right"><b>'.formatNumber($w_total_i).'</b></td>';
        $l_html.=$crlf.'        <td align="right"><b>'.formatNumber($w_total_e).'</b></td>';
        $l_html.=$crlf.'        <td align="right"><b>'.formatNumber($w_total_r).'</b></td>';
        $l_html.=$crlf.'        <td align="right"><b>'.formatNumber($w_total_i-$w_total_e).'</b></td>';
        $l_html.=$crlf.'        <td align="right"><b>'.formatNumber($w_total_e-$w_total_r).'</b></td>';
        if ($w_total_i!=0) {
          $l_html.=$crlf.'        <td align="center"><b>'.formatNumber($w_total_e/$w_total_i*100,2).'</td>';
          $l_html.=$crlf.'        <td align="center"><b>'.formatNumber($w_total_r/$w_total_i*100,2).'</td>';
        } else {
          $l_html.=$crlf.'        <td align="center"><b>0,00</td>';
          $l_html.=$crlf.'        <td align="center"><b>0,00</td>';
        }
        $l_html.=$crlf.'      </tr>';
      }
      $l_html.=$crlf.'         </table></td></tr>';
    } 
  
    // Parcelas
    $sql = new db_getAcordoParcela; $RS = $sql->getInstanceOf($dbms,$l_chave,null,null,null,null,null,null,null,null,null);
    $RS = SortArray($RS,'ordem','asc', 'dt_lancamento', 'asc');
    if (count($RS)>0) {
      $l_colspan = 0;
      $l_html.=$crlf.'      <tr><td colspan="2"><br><font size="2"><b>PARCELAS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      if (nvl($w_texto_pagamento,'')!='') {
        $l_html.=$crlf.'      <tr valign="top"><td><b>Condições para pagamento:</b></td>';
        $l_html.=$crlf.'        <td>'.CRLF2BR($w_texto_pagamento).'</td></tr>';
      }
      $l_html.=$crlf.'      <tr><td colspan="2" align="center">';
      $l_html.=$crlf.'        <table class="tudo" width=100%  border="1" bordercolor="#00000">';
      $l_html.=$crlf.'          <tr align="center">';
      $l_colspan++; $l_html.=$crlf.'            <td rowspan=2 bgColor="'.$conTrBgColor.'"><b>Ordem</b></td>';
      $l_colspan++; $l_html.=$crlf.'            <td rowspan=2 bgColor="'.$conTrBgColor.'"><b>Período</b></td>';
      $l_colspan++; $l_html.=$crlf.'            <td rowspan=2 bgColor="'.$conTrBgColor.'"><b>Vencimento</b></td>';
      if($w_aditivo>0) {
        $l_html.=$crlf.'            <td colspan=4 bgColor="'.$conTrBgColor.'"><b>Valor'.(($w_sb_moeda!='') ? ' ('.$w_sb_moeda.')' : '').'</b></td>';
      } else {
        $l_colspan++; $l_html.=$crlf.'            <td rowspan=2 bgColor="'.$conTrBgColor.'"><b>Valor'.(($w_sb_moeda!='') ? ' ('.$w_sb_moeda.')' : '').'</b></td>';
      }
      $l_colspan++; $l_html.=$crlf.'            <td rowspan=2 bgColor="'.$conTrBgColor.'"><b>Observações</b></td>';
      $l_html.=$crlf.'            <td colspan=5 bgColor="'.$conTrBgColor.'"><b>Financeiro</b></td>';
      $l_html.=$crlf.'          </tr>';
      $l_html.=$crlf.'          <tr align="center">';
      if($w_aditivo>0) {
        $l_colspan++; $l_html.=$crlf.'            <td bgColor="'.$conTrBgColor.'"><b>Inicial</b></td>';
        $l_colspan++; $l_html.=$crlf.'            <td bgColor="'.$conTrBgColor.'"><b>Excedente</b></td>';
        $l_colspan++; $l_html.=$crlf.'            <td bgColor="'.$conTrBgColor.'"><b>Reajuste</b></td>';
        $l_colspan++; $l_html.=$crlf.'            <td bgColor="'.$conTrBgColor.'"><b>Total</b></td>';
      }
      $l_html.=$crlf.'            <td bgColor="'.$conTrBgColor.'"><b>Lançamento</b></td>';
      $l_html.=$crlf.'            <td bgColor="'.$conTrBgColor.'"><b>Período</b></td>';
      $l_html.=$crlf.'            <td bgColor="'.$conTrBgColor.'"><b>Vencimento</b></td>';
      $l_html.=$crlf.'            <td bgColor="'.$conTrBgColor.'"><b>Valor'.(($w_sb_moeda!='') ? ' ('.$w_sb_moeda.')' : '').'</b></td>';
      $l_html.=$crlf.'            <td bgColor="'.$conTrBgColor.'"><b>Quitação</b></td>';
      $l_html.=$crlf.'          </tr>';
      $w_cor=$w_TrBgColor;
      $w_atual    = 0;
      $w_cont     = 0;
      $w_tot_parc = 0;
      $s_total    = 0;
      $s_total_i  = 0;
      $s_total_e  = 0;
      $s_total_r  = 0;
      $s_tot_liq  = 0;
      $s_real     = 0;
      $w_total    = 0;
      $w_total_i  = 0;
      $w_total_e  = 0;
      $w_total_r  = 0;
      $w_tot_liq  = 0;
      $w_bloco    = '';
      $w_id = 0;
      $f_html = '';
      $i = 0;
      foreach($RS as $row) {
        if ($w_atual!=f($row,'sq_acordo_parcela')) {
          // Verifica se a parcela anterior tem mais de um financeiro para colocar a linha-resumo
          if ($w_cont > 1) {
            // chamada para o JavaScript de colapsar/expandir
            $l_html.=$crlf.'          <td colspan=3 align="right">Total dos '.$w_cont.' lançamentos&nbsp;<a href="javascript:this.status.value" value="'.$w_id.'" id="link-'.$w_id.'">[-]</a>&nbsp;</td>';
            $l_html.=$crlf.'          <td align="right"><b>'.formatNumber($w_tot_parc).'</b></td>';
            $l_html.=$crlf.'          <td>&nbsp;</td>';
            $l_html.=$crlf.'        </tr>';
            $l_html.=$crlf.$f_html;
            $f_html = '';
            $w_id++;
            $i = 0;
          } else {
            $l_html.=$crlf.$f_html;
            $f_html = '';
            $i = 0;
          }

          if ($w_bloco!=f($row,'sq_acordo_aditivo') && $w_aditivo>0 && f($row,'prorrogacao')=='S') {
            $l_html.=$crlf.$f_html;
            $f_html = '';
            $l_html.=$crlf.'        <tr valign="top" bgColor="'.$conTrBgColor.'">';
            $l_html.=$crlf.'        <td align="right" colspan="2"><b>Totais do período</b></td>';
            $l_html.=$crlf.'        <td align="right"><b>Previsto</b></td>';
            if($w_aditivo>0) {
              $l_html.=$crlf.'        <td align="right"><b>'.formatNumber($s_total_i).'</b></td>';
              $l_html.=$crlf.'        <td align="right"><b>'.formatNumber($s_total_e).'</b></td>';
              $l_html.=$crlf.'        <td align="right"><b>'.formatNumber($s_total_r).'</b></td>';
            }
            $l_html.=$crlf.'        <td align="right"><b>'.formatNumber($s_total).'</b></td>';
            $l_html.=$crlf.'        <td colspan=3>';
            $l_html.=$crlf.'        &nbsp;</td>';
            $l_html.=$crlf.'        <td align="right"><b>Liquidado<br>Pago</b></td>';
            $l_html.=$crlf.'        <td align="right"><b>'.formatNumber($s_tot_liq).'<br>'.formatNumber($s_real).'</b></td>';
            if ($s_total==0) {
              $l_html.=$crlf.'        <td align="center">0,00%<br>0,00%</td>';
            } else {
              $l_html.=$crlf.'        <td align="center"><b>'.formatNumber($s_tot_liq/$s_total*100,2).'%<br>'.formatNumber($s_real/$s_total*100,2).'%</b></td>';
            }
            $l_html.=$crlf.'      </tr>';

            $w_bloco    = f($row,'sq_acordo_aditivo');
            $s_total    = 0;
            $s_total_i  = 0;
            $s_total_e  = 0;
            $s_total_r  = 0;
            $s_tot_liq  = 0;
            $s_real     = 0;
          } 

          // Verifica se a próxima parcela tem mais de um financeiro associado e cria uma nova classe
          $l_html.=$crlf.'      <tr valign="top">';
          $w_tot_parc = 0;
          $w_atual = f($row,'sq_acordo_parcela');

          $l_html.=$crlf.'          <td align="center">';
          if (Nvl($w_sg_tramite,'-')=='CR' && $w_fim-f($row,'vencimento')<0) {
            $l_html.=$crlf.'           <img src="'.$conImgCancel.'" border=0 width=10 heigth=10 align="center" title="Parcela cancelada!">';
          } elseif (Nvl(f($row,'quitacao'),'nulo')=='nulo') {
            if (f($row,'vencimento')<addDays(time(),-1))  {
              $l_html.=$crlf.'           <img src="'.$conImgAtraso.'" border=0 width=10 heigth=10 align="center">';
            } elseif (f($row,'vencimento')-addDays(time(),-1)<=5) {
              $l_html.=$crlf.'           <img src="'.$conImgAviso.'" border=0 width=10 height=10 align="center">';
            } else {
              $l_html.=$crlf.'           <img src="'.$conImgNormal.'" border=0 width=10 height=10 align="center">';
            } 
          } else {
            if (f($row,'quitacao')>f($row,'vencimento')) {
              $l_html.=$crlf.'           <img src="'.$conImgOkAtraso.'" border=0 width=10 heigth=10 align="center">';
            } else {
              $l_html.=$crlf.'           <img src="'.$conImgOkNormal.'" border=0 width=10 height=10 align="center">';
            } 
          } 

          $l_html.=$crlf.'      '.f($row,'ordem').'</td>';
          if(nvl(f($row,'inicio'),'')!='') $l_html.=$crlf.'        <td align="center">'.FormataDataEdicao(f($row,'inicio'),5).' a '.FormataDataEdicao(f($row,'fim'),5).'</td>';
          else                             $l_html.=$crlf.'        <td align="center">---</td>';
          $l_html.=$crlf.'        <td align="center">'.FormataDataEdicao(f($row,'vencimento'),5).'</td>';
          if($w_aditivo>0) {
            $l_html.=$crlf.'        <td align="right">'.formatNumber(f($row,'valor_inicial')).'</td>';
            $l_html.=$crlf.'        <td align="right">'.formatNumber(f($row,'valor_excedente')).'</td>';
            $l_html.=$crlf.'        <td align="right">'.formatNumber(f($row,'valor_reajuste')).'</td>';
          }
          $l_html.=$crlf.'        <td align="right">'.formatNumber(f($row,'valor')).'</td>';
          $l_html.=$crlf.'        <td>'.crlf2br(Nvl(f($row,'observacao'),'---')).'</td>';
          $w_total   += f($row,'valor');
          $w_total_i += f($row,'valor_inicial');
          $w_total_e += f($row,'valor_excedente');
          $w_total_r += f($row,'valor_reajuste');
          
          $s_total   += f($row,'valor');
          $s_total_i += f($row,'valor_inicial');
          $s_total_e += f($row,'valor_excedente');
          $s_total_r += f($row,'valor_reajuste');
          $w_cont = f($row,'qt_financeiro');
        }

        if (Nvl(f($row,'cd_lancamento'),'')>'') {
          if (f($row,'qt_financeiro')>1) $f_html.=$crlf . '      <tr valign="top" class="remover-'.$w_id.'"><td colspan="'.$l_colspan.'">';
          if ($l_tipo!='WORD') $f_html.=$crlf.'        <td align="center" nowrap><A class="hl" HREF="mod_fn/lancamento.php?par=Visual&O=L&w_chave='.f($row,'sq_lancamento').'&w_tipo=&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$l_tipo.'&TP='.$TP.'&SG=FN'.substr($SG,2,1).'CONT" title="Exibe as informações do lançamento." target="Lancamento">'.f($row,'cd_lancamento').'</a></td>';
          else                 $f_html.=$crlf.'        <td align="center" nowrap>'.f($row,'cd_lancamento').'</td>';
          if(nvl(f($row,'inicio'),'')!='') $f_html.=$crlf.'        <td align="center">'.FormataDataEdicao(f($row,'referencia_inicio'),5).' a '.FormataDataEdicao(f($row,'referencia_fim'),5).'</td>';
          else                             $f_html.=$crlf.'        <td align="center">---</td>';
          $f_html.=$crlf.'        <td align="center">'.FormataDataEdicao(f($row,'dt_lancamento'),5).'</td>';
          $f_html.=$crlf.'        <td align="right">'.formatNumber(f($row,'vl_lancamento')).'</td>';
          $f_html.=$crlf.'        <td align="center">'.Nvl(FormataDataEdicao(f($row,'quitacao'),5),'---').'</td>';
          $f_html.=$crlf.'      </tr>';
          if (Nvl(f($row,'quitacao'),'')!='') { 
            $w_real += f($row,'vl_lancamento');
            $s_real += f($row,'vl_lancamento');
          }
          $w_tot_parc += f($row,'vl_lancamento');
          $w_tot_liq  += f($row,'vl_lancamento');
          $s_tot_liq  += f($row,'vl_lancamento');
          $i++;
        } else {
          $f_html.=$crlf.'        <td align="center">---</td>';
          $f_html.=$crlf.'        <td align="center">---</td>';
          $f_html.=$crlf.'        <td align="center">---</td>';
          $f_html.=$crlf.'        <td align="center">---</td>';
          $f_html.=$crlf.'        <td align="center">---</td>';
          $i = 0;
        } 
      }
      
      if ($w_cont > 1) {
        $l_html.=$crlf . '      <tr valign="top">';
        $l_html.=$crlf . '          <td colspan=3 align="right">Total dos ' . $w_cont . ' lançamentos&nbsp;<a href="javascript:this.status.value" value="'.$w_id.'" id="link-'.$w_id.'">[-]</a>&nbsp;</td>';
        $l_html.=$crlf . '          <td align="right"><b>' . formatNumber($w_tot_parc) . '</b></td>';
        $l_html.=$crlf . '          <td>&nbsp;</td>';
        $l_html.=$crlf . '        </tr>';
        $l_html.=$crlf.$f_html;
      } else {
        $l_html.=$crlf.$f_html;
      }
      if ($w_total>0 || $w_real>0) {
        if ($w_bloco!='') {
          $l_html.=$crlf.'        <tr valign="top" bgColor="'.$conTrBgColor.'">';
          $l_html.=$crlf.'        <td align="right" colspan="2"><b>Totais do período</b></td>';
          $l_html.=$crlf.'        <td align="right"><b>Previsto</b></td>';
          if($w_aditivo>0) {
            $l_html.=$crlf.'        <td align="right"><b>'.formatNumber($s_total_i).'</b></td>';
            $l_html.=$crlf.'        <td align="right"><b>'.formatNumber($s_total_e).'</b></td>';
            $l_html.=$crlf.'        <td align="right"><b>'.formatNumber($s_total_r).'</b></td>';
          }
          $l_html.=$crlf.'        <td align="right"><b>'.formatNumber($s_total).'</b></td>';
          $l_html.=$crlf.'        <td colspan=3>';
          $l_html.=$crlf.'        &nbsp;</td>';
          $l_html.=$crlf.'        <td align="right"><b>Liquidado<br>Pago</b></td>';
          $l_html.=$crlf.'        <td align="right"><b>'.formatNumber($s_tot_liq).'<br>'.formatNumber($s_real).'</b></td>';
          if ($s_total==0) {
            $l_html.=$crlf.'        <td align="center">0,00%<br>0,00%</td>';
          } else {
            $l_html.=$crlf.'        <td align="center"><b>'.formatNumber($s_tot_liq/$s_total*100,2).'%<br>'.formatNumber($s_real/$s_total*100,2).'%</b></td>';
          }
          $l_html.=$crlf.'      </tr>';
        } 
        $l_html.=$crlf.'        <tr valign="top" bgColor="'.$conTrAlternateBgColor.'">';
        $l_html.=$crlf.'        <td align="right" colspan="2"><b>Totais do contrato</b></td>';
        $l_html.=$crlf.'        <td align="right"><b>Previsto</b></td>';
        if($w_aditivo>0) {
          $l_html.=$crlf.'        <td align="right"><b>'.formatNumber($w_total_i).'</b></td>';
          $l_html.=$crlf.'        <td align="right"><b>'.formatNumber($w_total_e).'</b></td>';
          $l_html.=$crlf.'        <td align="right"><b>'.formatNumber($w_total_r).'</b></td>';
        }
        $l_html.=$crlf.'        <td align="right"><b>'.formatNumber($w_total).'</b></td>';
        $l_html.=$crlf.'        <td colspan=3>';
        if (round($w_valor_inicial-$w_total,2)!=0) {
          $l_html.=$crlf.'        <b>O valor das parcelas difere do valor contratado ('.formatNumber($w_valor_inicial-$w_total).')</b></td>';
        } else {
          $l_html.=$crlf.'        &nbsp;</td>';
        } 
        $l_html.=$crlf.'        <td align="right"><b>Liquidado<br>Pago</b></td>';
        $l_html.=$crlf.'        <td align="right"><b>'.formatNumber($w_tot_liq).'<br>'.formatNumber($w_real).'</b></td>';
        if ($w_total==0) {
          $l_html.=$crlf.'        <td align="center">0,00%<br>0,00%</td>';
        } else {
          $l_html.=$crlf.'        <td align="center"><b>'.formatNumber($w_tot_liq/$w_total*100,2).'%<br>'.formatNumber($w_real/$w_total*100,2).'%</b></td>';
        }
        $l_html.=$crlf.'      </tr>';
      } 
      $l_html.=$crlf.'         </table></td></tr>';
    } 
  
    //Listagem dos itens do pedido de compra
    $sql = new db_getCLSolicItem; $RS1 = $sql->getInstanceOf($dbms,null,$l_chave,null,null,null,null,null,null,null,null,null,null,'ITEMARP');
    $RS1 = SortArray($RS1,'ordem','asc','nm_tipo_material','asc','nome','asc'); 
    if (count($RS1)>0) {  
      $w_total_preco = 0;
      $i             = 0;
      $l_html.=$crlf.'      <tr><td colspan="2"><br><font size="2"><b>ITENS ('.count($RS1).')<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
      $l_html.=$crlf.'      <tr><td colspan="2" align="center">';
      $l_html.=$crlf.'        <table width=100%  border="0" bordercolor="#00000">';
      foreach($RS1 as $row){ 
        if (f($row,'cancelado')=='S') $w_cor = ' BGCOLOR="'.$conTrBgColorLightRed2.'" '; else $w_cor = '';
        $l_html.=$crlf.'      <tr valign="top" '.$w_cor.'>';
        if (f($row,'cancelado')=='S') {
          $l_html.=$crlf.'        <td rowspan="4"><font size="2"><b>'.f($row,'ordem').'</b></font></td>';
        } else {
          $l_html.=$crlf.'        <td rowspan="3"><font size="2"><b>'.f($row,'ordem').'</b></font></td>';
        }
        $l_html.=$crlf.'        <td>Código:<br><b>'.f($row,'codigo_interno').'</b></td>';
        if ($l_tipo!='WORD'){
          $l_html.=$crlf.'        <td colspan="3">Nome:<br><b>'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'sq_material'),$TP,null).'</b></td>';
        } else {
          $l_html.=$crlf.'        <td colspan="3">Nome:<br><b>'.f($row,'nome').'</b></td>';
        }
        $l_html.=$crlf.'      </tr>';
        $l_html.=$crlf.'      <tr valign="top">';
        $l_html.=$crlf.'        <td>Fabricante:<br><b>'.f($row,'fabricante').'</b></td>';
        $l_html.=$crlf.'        <td>Marca/Modelo:<br><b>'.f($row,'marca_modelo').'</b></td>';
        $l_html.=$crlf.'        <td>Embalagem:<br><b>'.nvl(f($row,'embalagem'),'---').'</b></td>';
        $l_html.=$crlf.'        <td>Fator de embalagem:<br><b>'.nvl(f($row,'fator_embalagem'),'---').'</b></td>';
        $l_html.=$crlf.'      </tr>';
        $l_html.=$crlf.'      <tr valign="top">';
        if ($w_cliente==9614) {
          $l_html.=$crlf.'        <td>CMM:<br><b>'.formatNumber(f($row,'quantidade'),0).'</b></td>';
        } else {
          $l_html.=$crlf.'        <td>Quantidade:<br><b>'.formatNumber(f($row,'quantidade'),0).'</b></td>';
        }
        $l_html.=$crlf.'        <td>$ Unitário:<br><b>'.formatNumber(f($row,'valor_unidade'),2).'</b></td>';
        if ($w_cliente==9614) {
          $l_html.=$crlf.'        <td>$ Total<br><b>'.formatNumber(f($row,'valor_item'),2).'</b></td>';
        } else {
          $l_html.=$crlf.'        <td>$ Total<br><b>'.formatNumber(f($row,'valor_item'),2).'</b></td>';
        }

        $l_html.=$crlf.'      </tr>';
        if (f($row,'cancelado')=='S') {
          $l_html.=$crlf.'      <tr>';
          $l_html.=$crlf.'        <td valign="center"><font size="2"><b>INDISPONÍVEL</b></font></td>';
          $l_html.=$crlf.'        <td colspan=3>Motivo da indisponibilidade:<br><b>'.f($row,'motivo_cancelamento').'</b></td>';
          $l_html.=$crlf.'      </tr>';
        }
        $l_html.=$crlf.'      <tr><td><td colspan="4"><hr NOSHADE color=#000000 SIZE=1></td></tr>'; 
        $w_total_preco += f($row,'valor_item');
      }
      if ($w_cliente==9634) { // SMSSP trabalha com quantidades mensais
        $l_html.=$crlf.'      <tr>';
        $l_html.=$crlf.'        <td align="right" colspan="3"><b>Total mensal:&nbsp;&nbsp;</b></td>';
        $l_html.=$crlf.'        <td><b>'.formatNumber($w_total_preco,2).'</b></td>';
        $l_html.=$crlf.'      </tr>';
      }
      $l_html.=$crlf.'    </table></td></tr>';
    }
  
    if ($w_tipo_visao!=2 && $l_P1==4 && ($l_O=='L' || $l_O=='V' || $l_O=='T')) {
      // Arquivos vinculados
      $sql = new db_getSolicAnexo; $RS = $sql->getInstanceOf($dbms,$l_chave,null,$w_cliente);
      $RS = SortArray($RS,'nome','asc');
      if (count($RS)>0) {
        $l_html.=$crlf.'      <tr><td colspan="2"><br><font size="2"><b>ARQUIVOS ANEXOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
        $l_html.=$crlf.'      <tr><td colspan="2" align="center">';
        $l_html.=$crlf.'        <table width=100%  border="1" bordercolor="#00000">';
        $l_html.=$crlf.'          <tr align="center">';
        $l_html.=$crlf.'          <td bgColor="'.$conTrBgColor.'"><b>Título</b></td>';
        $l_html.=$crlf.'          <td bgColor="'.$conTrBgColor.'"><b>Descrição</b></td>';
        $l_html.=$crlf.'          <td bgColor="'.$conTrBgColor.'"><b>Tipo</b></td>';
        $l_html.=$crlf.'          <td bgColor="'.$conTrBgColor.'"><b>KB</b></td>';
        $l_html.=$crlf.'          </tr>';
        $w_cor=$w_TrBgColor;
        foreach($RS as $row) {
          $l_html.=$crlf.'      <tr valign="top">';
          if ($l_tipo!='WORD') {
            $l_html.=$crlf.'        <td>'.LinkArquivo('HL',$w_cliente,f($row,'chave_aux'),'_blank','Clique para exibir o arquivo em outra janela.',f($row,'nome'),null).'</td>';
          } else {
            $l_html.=$crlf.'        <td>'.f($row,'nome').'</td>';
          } 
          $l_html.=$crlf.'        <td>'.Nvl(f($row,'descricao'),'---').'</td>';
          $l_html.=$crlf.'        <td>'.f($row,'tipo').'</td>';
          $l_html.=$crlf.'        <td align="right">'.(round(f($row,'tamanho')/1024,1)).'&nbsp;</td>';
          $l_html.=$crlf.'      </tr>';
        } 
        $l_html.=$crlf.'         </table></td></tr>';
      } 
    }
  } 

  // Se for envio, executa verificações nos dados da solicitação
  $w_erro = ValidaAcordo($w_cliente,$l_chave,substr($w_sigla,0,3).'GERAL',null,null,null,Nvl($w_tramite,0));
  if ($w_tipo_visao!=2 && $w_erro>'') {
    $l_html.=$crlf.'<tr><td colspan=2><font size=2>';
    $l_html.=$crlf.'<HR>';
    if (substr($w_erro,0,1)=='0') {
      $l_html.=$crlf.'  <font color="#BC3131"><b>ATENÇÃO:</b></font> Foram identificadas as pendências listadas abaixo, não sendo possível seu encaminhamento para fases posteriores à atual.';
    } elseif (substr($w_erro,0,1)=='1') {
      $l_html.=$crlf.'  <font color="#BC3131"><b>ATENÇÃO:</b></font> Foram identificadas as pendências listadas abaixo. Seu encaminhamento para fases posteriores à atual só pode ser feito por um gestor do sistema ou deste módulo.';
    } else {
      $l_html.=$crlf.'  <font color="#BC3131"><b>ATENÇÃO:</b></font> Foram identificados os alertas listados abaixo. Eles não impedem o encaminhamento para fases posteriores à atual, mas convém sua verificação.';
    } 
    $l_html.=$crlf.'  <ul>'.substr($w_erro,1,1000).'</ul>';
    $l_html.=$crlf.'  </font></td></tr>';
  } 
  if ($w_tipo_visao!=2 && $l_P1==4 && ($l_O=='L' || $l_O=='V' || $l_O=='T')) {
    include_once($w_dir_volta.'funcoes/exibeLog.php');
    $l_html .= exibeLog($l_chave,$l_O,$l_usuario,$w_tramite_ativo,$l_tipo);
  } 
  $l_html.=$crlf.'    </table>';
  $l_html.=$crlf.'</table>';
  return $l_html;
}
?>
