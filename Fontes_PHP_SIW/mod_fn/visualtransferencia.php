<?php
// =========================================================================
// Rotina de visualização dos dados do lançamento
// -------------------------------------------------------------------------
function VisualTransferencia($v_chave,$l_O,$w_usuario,$l_P1,$l_tipo) {
  extract($GLOBALS);
  if ($l_tipo=='WORD') $w_TrBgColor=''; else $w_TrBgColor=$conTrBgColor;
  $l_html='';
  // Recupera os dados do lançamento
  $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$v_chave,$SG);
  $w_tramite       = f($RS,'sq_siw_tramite');
  $w_tramite_ativo = f($RS,'ativo');
  $w_SG            = f($RS,'sigla');
  $w_tipo_rubrica  = f($RS,'tipo_rubrica');
  $w_qtd_rubrica   = nvl(f($RS,'qtd_rubrica'),0);
  $w_sq_projeto    = nvl(f($RS,'sq_projeto'),0);
  $w_sb_moeda           = nvl(f($RS,'sb_moeda'),'');

  // Recupera o tipo de visão do usuário
  if (Nvl(f($RS,'solicitante'),0)   == $w_usuario || 
      Nvl(f($RS,'executor'),0)      == $w_usuario || 
      Nvl(f($RS,'cadastrador'),0)   == $w_usuario || 
      Nvl(f($RS,'titular'),0)       == $w_usuario || 
      Nvl(f($RS,'substituto'),0)    == $w_usuario || 
      Nvl(f($RS,'tit_exec'),0)      == $w_usuario || 
      Nvl(f($RS,'subst_exec'),0)    == $w_usuario || 
      SolicAcesso($v_chave,$w_usuario)>=8) {
    // Se for solicitante, executor ou cadastrador, tem visão completa
    $w_tipo_visao=0;
  } else {
    if (SolicAcesso($v_chave,$w_usuario)>2) $w_tipo_visao = 1;
  } 
  // Se for listagem ou envio, exibe os dados de identificação do lançamento
  if ($l_O=='L' || $l_O=='V') {
    // Se for listagem dos dados
    $l_html.=chr(13).'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
    $l_html.=chr(13).'<tr><td>';
    $l_html.=chr(13).'    <table width="99%" border="0">';
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    $l_html.=chr(13).'      <tr><td colspan="2" bgcolor="#f0f0f0"><font size="2"><b>'.upper(f($RS,'nome')).' '.f($RS,'codigo_interno').' ('.$v_chave.')</b></td>';
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
     
    // Identificação do lançamento
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>IDENTIFICAÇÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';      // Identificação do lançamento
    $l_html .= chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
    
    // Verifica o segmento do cliente    
    $sql = new db_getCustomerData; $RS1 = $sql->getInstanceOf($dbms,$w_cliente); 
    $w_segmento = f($RS1,'segmento');
    if ($w_mod_pa=='S' && nvl(f($RS,'processo'),'')!='') {
      if ((!($l_P1==4 || $l_tipo=='WORD')) && nvl(f($RS,'protocolo_siw'),'')!='') {
        $l_html.=chr(13).'      <tr><td><b>Número do processo: </b></td><td><A class="HL" HREF="mod_pa/documento.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($RS,'protocolo_siw').'&w_tipo=&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=PADGERAL'.MontaFiltro('GET').'" title="Exibe as informações deste registro." target="processo">'.f($RS,'processo').'&nbsp;</a>';
      } else {
        $l_html.=chr(13).'      <tr><td><b>Número do processo: </b></td><td>'.f($RS,'processo');
      }
    } elseif ($w_segmento=='Público') { 
      $l_html.=chr(13).'      <tr><td><b>Número do processo: </b></td>';
      $l_html.=chr(13).'        <td>'.nvl(f($RS,'processo'),'---').' </td></tr>';
    }   
    
    if (Nvl(f($RS,'cd_acordo'),'')>'') {
      if (!($l_P1==4 || $l_tipo=='WORD')) {
        $l_html.=chr(13).'      <tr><td width="30%"><b>Contrato: </b></td>';
        $l_html.=chr(13).'        <td><A class="hl" HREF="mod_ac/contratos.php?par=Visual&O=L&w_chave='.f($RS,'sq_solic_pai').'&w_tipo=&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$l_tipo.'&TP='.$TP.'&SG=GC'.substr($SG,2,1).'CAD" title="Exibe as informações do contrato." target="Contrato">'.f($RS,'cd_acordo').' ('.f($RS,'sq_solic_pai').')</a> </td></tr>';
      } else {
        $l_html.=chr(13).'      <tr><td><b>Contrato: </b></td><td>'.f($RS,'cd_acordo').' ('.f($RS,'sq_solic_pai').') </td></tr>';
      }
    } elseif (nvl(f($RS,'sq_solic_pai'),'')!='') {
      $l_html.=chr(13).'      <tr><td width="30%"><b>Vinculação: </b></td>';
      if (Nvl(f($RS,'dados_pai'),'')!='') {
        $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS,'sq_solic_pai'),f($RS,'dados_pai'),'N',$l_tipo).'</td>';
      } else {
        $l_html.=chr(13).'        <td>---</td>';
      }
    } 
    if (f($RS_Menu,'sigla')=='FNDVIA' || f($RS_Menu,'sigla')=='FNREVENT') {
      $l_html.=chr(13).'      <tr><td width="30%"><b>Projeto: </b></td>';
      if (Nvl(f($RS,'dados_avo'),'')!='') {
        $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS,'sq_solic_avo'),f($RS,'dados_avo'),'N',$l_tipo).'</td>';
      } else {
        $l_html.=chr(13).'        <td>---</td>';
      }
    }
    $l_html.=chr(13).'      <tr><td width="30%"><b>Tipo de lançamento: </b></td><td>'.f($RS,'nm_tipo_lancamento').' </td></tr>';
    $l_html.=chr(13).'      <tr><td><b>Unidade responsável: </b></td>';
    if (!($l_P1==4 || $l_tipo=='WORD')){
      $l_html.=chr(13).'        <td>'.ExibeUnidade($w_dir_volta,$w_cliente,f($RS,'nm_unidade_resp'),f($RS,'sq_unidade'),$TP).'</td></tr>';
    } else {
      $l_html.=chr(13).'        <td>'.f($RS,'nm_unidade_resp').'</td></tr>';
    }
    $l_html.=chr(13).'      <tr><td width="30%"><b>Conta origem: </b></td><td>'.f($RS,'nm_ban_org').' AG. '.f($RS,'cd_age_org').' C/C '.f($RS,'nr_conta_org').((nvl(f($RS,'sb_moeda'),'')=='') ? '' : ' ('.f($RS,'sg_moeda').')').'</td></tr>';
    $l_html.=chr(13).'      <tr><td width="30%"><b>Conta destino: </b></td><td>'.f($RS,'nm_banco').' AG. '.f($RS,'cd_agencia').' C/C '.f($RS,'numero_conta').((nvl(f($RS,'sb_moeda_benef'),'')=='') ? '' : ' ('.f($RS,'sg_moeda_benef').')').'</td></tr>';
    $l_html.=chr(13).'      <tr><td><b>Data da operação:</b></td><td>'.FormataDataEdicao(f($RS,'vencimento')).' </td></tr>';
    $l_html.=chr(13).'      <tr><td><b>Valor:</b></td><td>'.(($w_sb_moeda!='') ? $w_sb_moeda.' ' : '').formatNumber(Nvl(f($RS,'valor'),0)).' </td></tr>';
    $sql = new db_getSolicCotacao; $RS_Moeda_Cot = $sql->getInstanceOf($dbms,$w_cliente, $v_chave,null,null,null,null);
    $RS_Moeda_Cot = SortArray($RS_Moeda_Cot,'sb_moeda','asc');
    foreach($RS_Moeda_Cot as $row) {
      if ($w_sb_moeda!=f($row,'sb_moeda_cot')) {
        $l_html.=chr(13).'          <tr><td></td><td>'.f($row,'sb_moeda_cot').' '.formatNumber(f($row,'vl_cotacao')).' </td></tr>';
      }
    }
    $l_html.=chr(13).'      <tr valign="top"><td><b>Observação:</b></td><td>'.CRLF2BR(Nvl(f($RS,'descricao'),'---')).' </td></tr>';
    
    // Exibida apenas para gestores
    if (RetornaGestor($v_chave,$w_usuario)=='S') {
      
      $l_html.=chr(13).'      <tr bgColor="'.$conTrBgColor.'"><td colspan=2 style="border: 1px solid rgb(0,0,0);"><b>Informações Contábeis</td>';
      $l_html.=chr(13).'          <tr valign="top"><td><b>Conta Contábil de Débito:</b></td><td>'.nvl(f($RS,'cc_debito'),'---').'</td></tr>';
      $l_html.=chr(13).'          <tr valign="top"><td><b>Conta Contábil de Crédito:</b></td><td>'.nvl(f($RS,'cc_credito'),'---').'</td></tr>';
      $l_html.=chr(13).'          <tr valign="top"><td><b>Última atualização:</b></td><td>'.nvl(FormataDataEdicao(f($RS,'phpdt_cc_data'),3),'---').'</td></tr>';
      $l_html.=chr(13).'          <tr valign="top"><td><b>Responsável pela atualização:</b></td>';
      if (Nvl(f($RS,'cc_pessoa'),'nulo')!='nulo') {
        if ($l_tipo!='WORD') $l_html.=chr(13).'        <td>'.ExibePessoa($w_dir_volta,$w_cliente,f($RS,'cc_pessoa'),$TP,f($RS,'cc_pessoa_nome_res')).'</td>';
        else                 $l_html.=chr(13).'        <td>'.f($RS,'cc_pessoa_nome_res').'</td>';
      } else {
        $l_html.=chr(13).'        <td>---<td>';
      }
    }
  }  
  // Encaminhamentos
  include_once($w_dir_volta.'funcoes/exibeLog.php');
  $l_html .= exibeLog($v_chave,$l_O,$l_usuario,$w_tramite_ativo,(($l_tipo=='WORD') ? 'WORD' : 'HTML'));
  $l_html.=chr(13).'    </table>';
  $l_html.=chr(13).'</table>';
  return $l_html;
} 
?>