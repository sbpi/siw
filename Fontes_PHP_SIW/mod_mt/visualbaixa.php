<?php
// =========================================================================
// Rotina de visualização dos dados da baixa de bem patrimonial
// -------------------------------------------------------------------------
function VisualBaixa($v_chave,$l_O,$l_usuario,$l_P1,$l_tipo) {
  extract($GLOBALS);
  if ($l_tipo=='WORD') $w_TrBgColor=''; else $w_TrBgColor=$conTrBgColor;
  $l_html='';
  // Recupera os dados da solicitacao
  $sql = new db_getMtBaixaBem; $RS = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,$SG,3,
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
    $l_html.=chr(13).'        <td bgcolor="#f0f0f0"><font size="2"><b>'.f($RS,'codigo_interno').' ('.$v_chave.')</b></td>';
    $l_html.=chr(13).'        <td bgcolor="#f0f0f0" width="1%" align="right" nowrap><font size="2"><b>'.f($RS,'nm_tramite').'</b></td>';
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
    $l_html.=chr(13).'      <tr><td width="30%"><b>Almoxarifado: </b></td><td>'.f($RS,'nm_almoxarifado').' </td></tr>';
    $l_html.=chr(13).'      <tr><td><b>Tipo da movimentação: </b></td><td>'.f($RS,'nm_tp_mov').' </td></tr>';
    $l_html.=chr(13).'      <tr><td><b>Beneficiário:</b></td><td>'.ExibePessoa($w_dir_volta,$w_cliente,f($RS,'sq_fornecedor'),$TP,f($RS,'nm_fornecedor')).' </td></tr>';
    $l_html.=chr(13).'      <tr><td><b>Motivação para a baixa:</b></td><td>'.crlf2br(f($RS,'descricao')).' </td></tr>';
    
    // Dados da conclusão da solicitação, se ela estiver nessa situação
    if (nvl(f($RS,'sg_tramite'),'')=='AT') {
      $l_html.=chr(13).'   <tr><td colspan="2"><br><font size="2"><b>DADOS DA CONCLUSÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html.=chr(13).'   <tr valign="top"><td><b>Data de efetivação:</b></font></td><td>'.nvl(formataDataEdicao(f($RS,'conclusao')),'---').'</font></td></tr>';
      $l_html.=chr(13).'   <tr valign="top"><td><b>Nota de conclusão:</b></font></td><td>'.nvl(crlf2br(f($RS,'observacao')),'---').'</font></td></tr>';
    }

    $l_html.=chr(13).'          </table></td></tr>';
    
    //Listagem dos itens da entrada de material
    $sql = new db_getMtBaixaBem; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,$w_usuario,'ITENS',3,
        null,null,null,null,null,null,null,null,null,null,$v_chave,null,null,null,null,null,null,
        null,null,null,null,null,null,null,null,null,null,null);
    $RS1 = SortArray($RS1,'numero_rgp','asc','nome','asc');
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ITENS ('.count($RS1).')<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
    if (count($RS1)>0) {
      unset($w_classes);
      foreach($RS1 as $row) $w_classes[f($row,'classe')] = 1;
      reset($RS1);
      $colspan = 0;
      $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">';
      $l_html.=chr(13).'        <table class="tudo" width=100% border="1" bordercolor="#00000">';
      $l_html.=chr(13).'        <tr align="center">';
      $l_html.=chr(13).'          <td><b>RGP</b></td>';
      $l_html.=chr(13).'          <td><b>Descrição</b></td>';
      $l_html.=chr(13).'        </tr>';
      foreach($RS1 as $row){ 
        $l_html.=chr(13).'      <tr valign="top">';
        $l_html.=chr(13).'        <td align="center">'.f($row,'numero_rgp').'</td>';
        $l_html.=chr(13).'        <td>'.ExibePermanente($w_dir_volta,$w_cliente,f($row,'nome_completo'),f($row,'sq_permanente'),$TP,null,null).'</td>';
      }
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
    $w_erro = ValidaBaixa($w_cliente,$v_chave,$SG,null,null,null,null);
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
    if ($O!='V') {
      // Encaminhamentos
      include_once($w_dir_volta.'funcoes/exibeLog.php');
      $l_html .= exibeLog($v_chave,$l_O,$l_usuario,$w_tramite_ativo,(($l_tipo=='WORD') ? 'WORD' : 'HTML'));
    }
  }
  $l_html.=chr(13).'</table>';
  return $l_html;
}
?>