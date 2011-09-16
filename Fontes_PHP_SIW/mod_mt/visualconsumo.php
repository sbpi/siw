<?php
// =========================================================================
// Rotina de visualização dos dados da solicitacao
// -------------------------------------------------------------------------
function VisualConsumo($v_chave,$l_O,$l_usuario,$l_P1,$l_tipo) {
  extract($GLOBALS);
  if ($l_tipo=='WORD') $w_TrBgColor=''; else $w_TrBgColor=$conTrBgColor;
  $l_html='';
  // Recupera os dados da solicitacao
  $sql = new db_getSolicMT; $RS = $sql->getInstanceOf($dbms,$w_menu,$l_usuario,$SG,5,
          null,null,null,null,null,null,null,null,null,null,
          $v_chave,null,null,null,null,null,null,
          null,null,null,null,null,null,null,null,null,null,null);
         
  foreach($RS as $row){$RS=$row; break;}
  $w_tramite        = f($RS,'sq_siw_tramite');
  $w_sigla          = f($RS,'sigla');
  $w_sg_tramite     = f($RS,'sg_tramite');
  $w_fundo_fixo     = f($RS,'fundo_fixo');
  $w_tramite_ativo  = f($RS,'ativo');

  // Visão completa para todos os usuários
  $w_tipo_visao=0;
  
  // Se for listagem ou envio, exibe os dados de identificação do lançamento
  if ($l_O=='L' || $l_O=='V') {
    // Se for listagem dos dados
    $l_html.=chr(13).'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
    $l_html.=chr(13).'<tr><td>';
    $l_html.=chr(13).'    <table width="99%" border="0">';
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    $l_html.=chr(13).'      <tr>';
    $l_html.=chr(13).'        <td bgcolor="#f0f0f0"><font size="2"><b>'.upper(f($RS,'nome')).' '.f($RS,'codigo_interno').' ('.$v_chave.')</b></font></td>';
    $l_html.=chr(13).'        <td align="right" bgcolor="#f0f0f0"><font size="2"><b>'.upper(f($RS,'nm_tramite')).'</b></font></td>';
    $l_html.=chr(13).'      </tr>';
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
     
    // Identificação do pedido
    $l_html.=chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
    if(nvl(f($RS,'protocolo_siw'),'')!='') {
      $l_html.=chr(13).'      <tr><td width="30%"><b>Número do protocolo: </b></td>';
      if ($w_embed!='WORD' && nvl(f($RS,'protocolo_siw'),'')!='') {
        $l_html.=chr(13).'        <td><A class="HL" HREF="mod_pa/documento.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($RS,'protocolo_siw').'&w_tipo=&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro." target="processo">'.f($RS,'processo').'&nbsp;</a>';
      } else {
        $l_html.=chr(13).'        <td>'.f($RS,'processo');
      }
    }
    $l_html.=chr(13).'      <tr><td width="30%"><b>Agendamento desejado:</b></td><td>'.FormataDataEdicao(f($RS,'fim')).' </td></tr>';
    $l_html.=chr(13).'      <tr><td width="30%"><b>Solicitante:<b></td>';
    if (!($l_P1==4 || $l_tipo=='WORD')){
      $l_html.=chr(13).'        <td>'.ExibePessoa(null,$w_cliente,f($RS,'solicitante'),$TP,f($RS,'nm_solic')).'</b></td>';
    } else {
      $l_html.=chr(13).'        <td>'.f($RS,'nm_solic').'</b></td>';
    }
    $l_html.=chr(13).'      <tr><td><b>Unidade solicitante: </b></td>';
    if (!($l_P1==4 || $l_tipo=='WORD')){
      $l_html.=chr(13).'        <td>'.ExibeUnidade($w_dir_volta,$w_cliente,f($RS,'nm_unidade_solic'),f($RS,'sq_unidade'),$TP).'</td></tr>';
    } else {
      $l_html.=chr(13).'        <td>'.f($RS,'nm_unidade_solic').'</td></tr>';
    }
    $l_html.=chr(13).'      <tr valign="top"><td><b>Justificativa:</b></td><td>'.crlf2br(f($RS,'justificativa')).' </td></tr>';
    $l_html.=chr(13).'      <tr valign="top"><td><b>Observações:</b></td><td>'.CRLF2BR(Nvl(f($RS,'observacao'),'---')).' </td></tr>';
    $l_html.=chr(13).'          </table></td></tr>';    
    
    //Listagem dos itens do pedido de material
    $sql = new db_getCLSolicItem; $RS1 = $sql->getInstanceOf($dbms,null,$v_chave,null,null,null,null,null,null,null,null,null,null,'PEDMAT');
    $RS1 = SortArray($RS1,'nm_tipo_material','asc','nm_tipo_material','asc','nome','asc');
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ITENS ('.count($RS1).')<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
    $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">';
    $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
    $l_html.=chr(13).'        <tr align="center">';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0" rowspan="2"><b>Nome</td>';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0" colspan="2"><b>Quantidade</td>';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0" rowspan="2"><b>U.M.</td>';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0" rowspan="2"><b>Fator de Embalagem</td>';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0" rowspan="2"><b>Data de Entrega</td>';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0" rowspan="2"><b>Valor</td>';
    $l_html.=chr(13).'        </tr>';
    $l_html.=chr(13).'        <tr align="center">';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Solicitada</td>';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Autorizada</td>';
    $l_html.=chr(13).'        </tr>';
    if (count($RS1)==0) {
      // Se não foram selecionados registros, exibe mensagem
      $l_html.=chr(13).'      <tr><td colspan=7 align="center"><b>Não foram encontrados registros.</b></td></tr>';
    } else {
      // Lista os registros selecionados para listagem
      $w_total = 0;
      foreach($RS1 as $row){ 
        $l_html.=chr(13).'      <tr align="center">';
        if (!($l_P1==4 || $l_tipo=='WORD')){
          $l_html.=chr(13).'        <td align="left">'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'sq_material'),$TP,null).'</td>';
        } else {
          $l_html.=chr(13).'        <td align="left">'.f($row,'nome').'</td>';
        }
        $l_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'quantidade_pedida'),0).'</td>';
        if(nvl(f($row,'quantidade_entregue'),'')!='' && ($w_sg_tramite=='EA' || $w_sg_tramite=='EE' || $w_sg_tramite=='AT')) {
          $l_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'quantidade_entregue'),0).'</td>';
        } else {
          $l_html.=chr(13).'        <td align="center">---</td>';
        }
        $l_html.=chr(13).'        <td align="center" title="'.f($row,'nm_unidade_medida').'">'.f($row,'sg_unidade_medida').'</td>';
        $l_html.=chr(13).'        <td align="center ">'.f($row,'fator_embalagem').'</td>';
        if ($w_sg_tramite=='AT') {
          $l_html.=chr(13).'        <td align="center">'.nvl(formataDataEdicao(f($row,'data_efetivacao'),5),'---').'</td>';
          $l_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'valor_unitario')).'</td>';
          $w_total += f($row,'valor_unitario');
        } else {
          $l_html.=chr(13).'        <td align="center">---</td>';
          $l_html.=chr(13).'        <td align="right">---</td>';
        }
        $l_html.=chr(13).'        </tr>';
      }
      if (count($RS1>1) && $w_total>0) {
        $l_html.=chr(13).'      <tr valign="top"><td colspan="6" align="right"><b>Total<b>&nbsp;<td align="right">'.formatNumber($w_total).'</td></tr>';
      }
    } 
    $l_html.=chr(13).'         </table></td></tr>';
  }
  if ($l_O=='L' || $l_O=='V') {
    // Se for envio, executa verificações nos dados da solicitação
    $w_erro = ValidaConsumo($w_cliente,$v_chave,$SG,null,null,null,Nvl($w_tramite,0));
    if ($w_erro>'') {
      $l_html.=chr(13).'<tr><td colspan=2><font size=2>';
      $l_html.=chr(13).'<HR>';
      if (substr($w_erro,0,1)=='0') {
        $l_html.=chr(13).'  <font color="#BC3131"><b>ATENÇÃO:</b></font> Foram identificadas as pendências listadas abaixo, não sendo possível seu encaminhamento para fases posteriores à atual.';
      } elseif (substr($w_erro,0,1)=='1') {
        $l_html.=chr(13).'  <font color="#BC3131"><b>ATENÇÃO:</b></font> Foram identificadas as pendências listadas abaixo. Seu encaminhamento para fases posteriores à atual só pode ser feito por um gestor do sistema ou deste módulo.';
      } else {
        $l_html.=chr(13).'  <font color="#BC3131"><b>ATENÇÃO:</b></font> Foram identificados os alertas listados abaixo. Eles não impedem o encaminhamento para fases posteriores à atual, mas convém sua verificação.';
      } 
      $l_html.=chr(13).'  <ul>'.substr($w_erro,1,1000).'</ul>';
      $l_html.=chr(13).'  </font></td></tr>';
    }

    if ($O!='V') {
      // Encaminhamntos
      include_once($w_dir_volta.'funcoes/exibeLog.php');
      $l_html .= exibeLog($v_chave,$l_O,$l_usuario,$w_tramite_ativo,(($l_tipo=='WORD') ? 'WORD' : 'HTML'));
    }
  }
  $l_html.=chr(13).'</table>';
  return $l_html;
}
?>