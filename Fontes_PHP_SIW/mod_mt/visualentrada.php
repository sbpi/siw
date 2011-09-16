<?php
// =========================================================================
// Rotina de visualização dos dados da solicitacao
// -------------------------------------------------------------------------
function VisualEntrada($v_chave,$l_O,$l_usuario,$l_P1,$l_tipo) {
  extract($GLOBALS);
  if ($l_tipo=='WORD') $w_TrBgColor=''; else $w_TrBgColor=$conTrBgColor;
  $l_html='';
  // Recupera os dados da solicitacao
  $sql = new db_getMtMovim; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$SG,3,
    null,null,null,null,null,null,null,null,null,null,$v_chave,null,null,null,null,null,null,
    null,null,null,null,null,null,null,null,null,null,null);
         
  foreach($RS as $row){$RS=$row; break;}

  // Se for listagem ou envio, exibe os dados de identificação do lançamento
  if ($l_O=='L' || $l_O=='V') {
    // Se for listagem dos dados
    $l_html.=chr(13).'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
    $l_html.=chr(13).'<tr><td>';
    $l_html.=chr(13).'    <table width="99%" border="0">';
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    $l_html.=chr(13).'      <tr valign="top">';
    $l_html.=chr(13).'        <td bgcolor="#f0f0f0"><font size="2"><b>'.f($RS,'nm_fornecedor').' - '.f($RS,'nm_tp_doc').' '.' '.f($RS,'nr_doc').' de '.formataDataEdicao(f($RS,'dt_doc'),5).' ('.$v_chave.')</b></td>';
    $l_html.=chr(13).'        <td bgcolor="#f0f0f0" width="1%" align="right" nowrap><font size="2"><b>'.f($RS,'nm_sit').((nvl(f($RS,'armazenamento'),'')=='') ? '' : ' EM '.formataDataEdicao(f($RS,'armazenamento'),5)).'</b></td>';
    $l_html.=chr(13).'      </tr>';
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
     
    // Dados da entrada
    $l_html.=chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
    if(nvl(f($RS,'processo'),'')!='' || nvl(f($RS,'protocolo_siw'),'')!='') {
      $l_html.=chr(13).'      <tr><td><b>Número do protocolo: </b></td>';
      if ($w_embed!='WORD' && nvl(f($RS,'protocolo_siw'),'')!='') {
        $l_html.=chr(13).'        <td><A class="HL" HREF="mod_pa/documento.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($RS,'protocolo_siw').'&w_tipo=&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro." target="processo">'.f($RS,'processo').'&nbsp;</a>';
      } else {
        $l_html.=chr(13).'        <td>'.f($RS,'processo');
      }
    }
    // Exibe a vinculação
    $l_html.=chr(13).'      <tr><td width="30%"><b>Lançamento: </b></td>';
    if (!($l_P1==4 || $l_tipo=='WORD')) $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS,'sq_siw_solicitacao'),f($RS,'codigo_interno'),'S','N').'</td></tr>';
    else                         $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS,'sq_siw_solicitacao'),f($RS,'codigo_interno'),'S','S').'</td></tr>';
    $l_html.=chr(13).'      <tr><td width="30%"><b>Vinculação: </b></td>';
    if (!($l_P1==4 || $l_tipo=='WORD')) $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS,'sq_solic_pai'),f($RS,'dados_pai'),'N','N').'</td></tr>';
    else                         $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS,'sq_solic_pai'),f($RS,'dados_pai'),'N','S').'</td></tr>';
    $l_html.=chr(13).'      <tr><td><b>Tipo da movimentação: </b></td><td>'.f($RS,'nm_tp_mov').' </td></tr>';
    $l_html.=chr(13).'      <tr><td><b>Data prevista para entrega:</b></td><td>'.FormataDataEdicao(f($RS,'recebimento_previsto')).' </td></tr>';
    $l_html.=chr(13).'      <tr><td><b>Data efetiva de entrega:</b></td><td>'.FormataDataEdicao(f($RS,'recebimento_efetivo')).' </td></tr>';
    $l_html.=chr(13).'      <tr><td><b>Valor: </b></td><td>'.formatNumber(f($RS,'vl_doc')).'</td></tr>';

    $l_html.=chr(13).'          </table></td></tr>';    
    
    //Listagem dos itens da entrada de material
    $sql = new db_getMtEntItem; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,$v_chave,null,null,null,null,null,null,null,null,null,null,null,null,null);
    $RS1 = SortArray($RS1,'ordem','asc','nome','asc'); 
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ITENS ('.count($RS1).')<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
    if (count($RS1)>0) {
      unset($w_classes);
      foreach($RS1 as $row) $w_classes[f($row,'classe')] = 1;
      reset($RS1);
      $colspan = 0;
      $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">';
      $l_html.=chr(13).'        <table class="tudo" width=100% border="1" bordercolor="#00000">';
      $l_html.=chr(13).'        <tr align="center">';
      $l_html.=chr(13).'          <td rowspan=2><b>Item</b></td>';
      $l_html.=chr(13).'          <td rowspan=2><b>Nome</b></td>';
      if (!$w_classes[4]) {
        $l_html.=chr(13).'          <td rowspan=2><b>Marca</b></td>';
      } elseif ($w_classes[1] || $w_classes[2] || $w_classes[3] && $w_classes[4]) {
        $l_html.=chr(13).'          <td rowspan=2><b>Fabricante / Marca</b></td>';
      } elseif ($w_classes[4]) {
        $l_html.=chr(13).'          <td rowspan=2><b>Fabricante</b></td>';
      }
      if ($w_classes[4]) {
        $l_html.=chr(13).'          <td rowspan=2><b>Modelo</b></td>';
        $colspan++;
        $l_html.=chr(13).'          <td rowspan=2><b>Vida útil</b></td>';
        $colspan++;
      }
      if ($w_classes[1]) {
        $l_html.=chr(13).'          <td rowspan=2><b>Lote</b></td>';
        $l_html.=chr(13).'          <td rowspan=2><b>Fabricação</b></td>';
        $colspan += 2;
      }
      if ($w_classes[1] || $w_classes[2] || $w_classes[3]) {
        $l_html.=chr(13).'          <td rowspan=2><b>Validade</b></td>';
        $l_html.=chr(13).'          <td rowspan=2><b>Local de armazenamento</b></td>';
        $l_html.=chr(13).'          <td rowspan=2><b>Fator<br>Embal.</b></td>';
        $l_html.=chr(13).'          <td rowspan=2><b>U.M.</b></td>';
        $colspan += 4;
      }
      $l_html.=chr(13).'          <td rowspan=2><b>Qtd</b></td>';
      $l_html.=chr(13).'          <td colspan=2><b>Valores</b></td>';
      if (f($RS,'sg_sit')=='AR' && ($w_classes[1] || $w_classes[2] || $w_classes[3])) $l_html.=chr(13).'          <td rowspan=2><b>Saldo atual</b></td>';
      $l_html.=chr(13).'        </tr>';
      $l_html.=chr(13).'        <tr align="center">';
      $l_html.=chr(13).'          <td><b>Unit.</b></td>';
      $l_html.=chr(13).'          <td><b>Total</b></td>';
      $l_html.=chr(13).'        </tr>';
      // Lista os registros selecionados para listagem
      $w_total = 0;
      foreach($RS1 as $row){ 
        $l_html.=chr(13).'      <tr valign="top">';
        $l_html.=chr(13).'        <td align="center">'.f($row,'ordem').'</td>';
        $l_html.=chr(13).'        <td>'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'sq_material'),$TP,null).'</td>';
        $l_html.=chr(13).'        <td>'.nvl(f($row,'marca'),'&nbsp;').'</td>';
        if ($w_classes[4]) {
          $l_html.=chr(13).'        <td>'.nvl(f($row,'modelo'),'&nbsp;').'</td>';
          $l_html.=chr(13).'        <td align="center">'.nvl(f($row,'vida_util'),'&nbsp').'</td>';
        }
        if ($w_classes[1]) {
          $l_html.=chr(13).'        <td align="center">'.nvl(formataDataEdicao(f($row,'lote_numero'),5),'&nbsp;').'</td>';
          $l_html.=chr(13).'        <td align="center">'.nvl(formataDataEdicao(f($row,'fabricacao'),5),'&nbsp;').'</td>';
        }
        if ($w_classes[1] || $w_classes[2] || $w_classes[3]) {
          $l_html.=chr(13).'        <td align="center">'.nvl(formataDataEdicao(f($row,'validade'),5),'&nbsp;').'</td>';
          $l_html.=chr(13).'        <td>'.nvl(f($row,'local_armazenamento'),'&nbsp;').'</td>';
          $l_html.=chr(13).'        <td align="center">'.((f($row,'classe')==1||f($row,'classe')==3) ? f($row,'fator_embalagem') : '&nbsp;').'</td>';
          $l_html.=chr(13).'        <td align="center" title="'.f($row,'nm_unidade_medida').'">'.f($row,'sg_unidade_medida').'</td>';
        }
        $l_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'quantidade'),0).'</td>';
        $l_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'valor_unitario'),10).'</td>';
        $l_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'valor_total')).'</td>';
        if (f($RS,'sg_sit')=='AR' && ($w_classes[1] || $w_classes[2] || $w_classes[3])) $l_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'saldo_atual'),0).'</td>';
        $l_html.=chr(13).'        </tr>';
        $w_total += f($row,'valor_total');
      }
      if (count($RS1)>1) $l_html.=chr(13).'      <tr bgcolor="'.$w_cor.'" valign="top"><td colspan='.(5+$colspan).' align="right"><b>Total dos itens</b><td align="right">'.formatNumber($w_total).((f($RS,'sg_sit')=='AR' && ($w_classes[1] || $w_classes[2] || $w_classes[3])) ? '<td>&nbsp;</td>' : '').'</tr>';
      $l_html.=chr(13).'    </table>';
    }
  }
  if ($l_O=='L' || $l_O=='V') {
    // Se for listagem dos dados
    // Arquivos vinculados
    $sql = new db_getDocumentoArquivo; $RS1 = $sql->getInstanceOf($dbms,$v_chave,null,null,null,$w_cliente);
    $RS1 = SortArray($RS1,'ordem','asc','nome','asc');
    if (count($RS1)>0) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ANEXOS ('.count($RS1).')<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
      $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">';
      $l_html.=chr(13).'        <table class="tudo" width=100% border="1" bordercolor="#00000">';
      $l_html.=chr(13).'          <tr align="center">';
      $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Ordem</b></td>';
      $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Título</b></td>';
      $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Descrição</b></td>';
      $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Tipo</b></td>';
      $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>KB</b></td>';
      $l_html.=chr(13).'          </tr>';
      foreach($RS1 as $row) {
        $l_html.=chr(13).'      <tr valign="top">';
        $l_html.=chr(13).'        <td align="center">'.f($row,'ordem').'</td>';
        if (!($l_P1==4 || $l_tipo=='WORD')) $l_html.=chr(13).'        <td>'.LinkArquivo('HL',$w_cliente,f($row,'chave_aux'),'_blank','Clique para exibir o arquivo em outra janela.',f($row,'nome'),null).'</td>';
        else                         $l_html.=chr(13).'        <td>'.f($row,'nome').'</td>';
        $l_html.=chr(13).'        <td>'.Nvl(f($row,'descricao'),'---').'</td>';
        $l_html.=chr(13).'        <td>'.f($row,'tipo').'</td>';
        $l_html.=chr(13).'        <td align="right">'.round(f($row,'tamanho')/1024,1).'&nbsp;</td>';
        $l_html.=chr(13).'      </tr>';
      } 
      $l_html.=chr(13).'         </table></td></tr>';
    }
    
    // Se for envio, executa verificações nos dados da solicitação
    $w_erro = ValidaEntrada($w_cliente,$v_chave,$SG,null,null,null,null);
    if ($w_erro>'') {
      $l_html.=chr(13).'<tr><td colspan=2><font size=2>';
      $l_html.=chr(13).'<HR>';
      if (substr($w_erro,0,1)=='0') {
        $l_html.=chr(13).'  <font color="#BC3131"><b>ATENÇÃO:</b></font> Foram identificadas as pendências listadas abaixo, não sendo possível armazenar/incorporar seus itens.';
      } elseif (substr($w_erro,0,1)=='1') {
        $l_html.=chr(13).'  <font color="#BC3131"><b>ATENÇÃO:</b></font> Foram identificadas as pendências listadas abaixo. O armazenamento/incorporação dos itens só pode ser feito por um gestor do sistema ou deste módulo.';
      } else {
        $l_html.=chr(13).'  <font color="#BC3131"><b>ATENÇÃO:</b></font> Foram identificados os alertas listados abaixo. Eles não impedem o armazenamento/incorporação dos itens, mas convém sua verificação.';
      } 
      $l_html.=chr(13).'  <ul>'.substr($w_erro,1,1000).'</ul>';
      $l_html.=chr(13).'  </font></td></tr>';
    }
  }
  $l_html.=chr(13).'</table>';
  return $l_html;
}
?>