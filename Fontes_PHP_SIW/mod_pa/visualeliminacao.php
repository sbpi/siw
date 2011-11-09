<?php
// =========================================================================
// Rotina de visualização dos dados da eliminação
// -------------------------------------------------------------------------
function VisualEliminacao($v_chave,$l_O,$l_usuario,$l_P1,$l_tipo) {
  extract($GLOBALS);
  if ($l_tipo=='WORD') $w_TrBgColor=''; else $w_TrBgColor=$conTrBgColor;
  $l_html='';
  // Recupera os dados da solicitacao
  $sql = new db_getSolicPA; $RS = $sql->getInstanceOf($dbms,null,$l_usuario,$SG,5,
          null,null,null,null,null,null,null,null,null,null,
          $v_chave,null,null,null,null,null,null,
          null,null,null,null,null,null,null,null,null,null,null);
  foreach($RS as $row){$RS=$row; break;}
  $w_tramite        = f($RS,'sq_siw_tramite');
  $w_sigla          = f($RS,'sigla');
  $w_sg_tramite     = f($RS,'sg_tramite');
  $w_tramite_ativo  = f($RS,'ativo');

  // Recupera o tipo de visão do usuário
  if (Nvl(f($RS,'solicitante'),0)   == $l_usuario || 
      Nvl(f($RS,'executor'),0)      == $l_usuario || 
      Nvl(f($RS,'cadastrador'),0)   == $l_usuario || 
      Nvl(f($RS,'titular'),0)       == $l_usuario || 
      Nvl(f($RS,'substituto'),0)    == $l_usuario || 
      Nvl(f($RS,'tit_exec'),0)      == $l_usuario || 
      Nvl(f($RS,'subst_exec'),0)    == $l_usuario || 
      SolicAcesso($v_chave,$l_usuario)>=8) {
    // Se for solicitante, executor ou cadastrador, tem visão completa
    $w_tipo_visao=0;
  } else {
    if (SolicAcesso($v_chave,$l_usuario)>2) $w_tipo_visao = 1;
  } 
  // Se for listagem ou envio, exibe os dados de identificação do lançamento
  $l_html.=chr(13).'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
  $l_html.=chr(13).'<tr><td>';
  $l_html.=chr(13).'    <table width="99%" border="0">';
  $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
  $l_html.=chr(13).'      <tr><td colspan="2" bgcolor="#f0f0f0"><font size="2"><b>'.upper(f($RS,'nome')).' '.f($RS,'codigo_interno').' ('.$v_chave.')</b></td>';
  $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
   
  // Identificação do lançamento
  $l_html .= chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
  $l_html .= chr(13).'    <tr><td width="30%"><b>Solicitante:<b></td>';
  if (!($l_P1==4 || $l_tipo=='WORD')){
    $l_html .= chr(13).'        <td>'.ExibePessoa(null,$w_cliente,f($RS,'solicitante'),$TP,f($RS,'nm_solic')).'</b></td>';
  } else {
    $l_html .= chr(13).'        <td>'.f($RS,'nm_solic').'</b></td>';
  }
    $l_html.=chr(13).'      <tr><td><b>Unidade solicitante: </b></td>';
  if (!($l_P1==4 || $l_tipo=='WORD')){
    $l_html.=chr(13).'        <td>'.ExibeUnidade($w_dir_volta,$w_cliente,f($RS,'nm_unidade_resp'),f($RS,'sq_unidade'),$TP).'</td></tr>';
  } else {
    $l_html.=chr(13).'        <td>'.f($RS,'nm_unidade_resp').'</td></tr>';
  }
  if ($w_sg_tramite=='AT') $l_html.=chr(13).'      <tr><td><b>Data da eliminação:</b></td><td>'.formataDataEdicao(f($RS,'dt_eliminacao')).' </td></tr>';
  $l_html.=chr(13).'      <tr><td><b>Observação:</b></td><td>'.crlf2br(f($RS,'observacao')).' </td></tr>';
  $l_html.=chr(13).'          </table></td></tr>';    
  
  $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>EMISSÃO DE LISTAGEM<hr NOSHADE color=#000000 SIZE=1></b></font><ul>';
  $l_html.=chr(13).'         <li><A class="HL" href="'.$w_dir.$w_pagina.'EmiteListElim&R='.$w_pagina.$par.'&O=L&w_chave='.$v_chave.'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=PAELIM" title="Clique neste link para emitir a listagem.">Listagem de Eliminação de Documentos</A>&nbsp';
  $l_html.=chr(13).'      </ul></td></tr>';

  if ($l_O!='X') {
    //Listagem dos itens do pedido de compra. Não exibido quando operação igual a X (conclusão do pedido).
    $sql = new db_getPAElimItem; $RS1 = $sql->getInstanceOf($dbms,null,$v_chave,null,null,null,null);
    $RS1 = SortArray($RS1,'cd_assunto','asc', 'ano','asc','numero_documento','asc'); 
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ITENS ('.count($RS1).')<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
    $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">';
    $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
    $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
    $colspan=0;
    $colspan++; $l_html.=chr(13).'          <td rowspan=2><b>Protocolo</td>';
    $colspan++; $l_html.=chr(13).'          <td rowspan=2><b>Tipo</td>';
    $l_html.=chr(13).'          <td colspan=4><b>Documento original</td>';
    $l_html.=chr(13).'          <td colspan='./*(($l_tipo!='WORD') ? */3/* : 2)*/.'><b>Assunto</td>';
    /*if ($l_tipo!='WORD')*/ $l_html.=chr(13).'          <td colspan=3><b>Localização</td>';
    $colspan++; $l_html.=chr(13).'          <td rowspan=2><b>Guarda</td>';
    $l_html.=chr(13).'        </tr>';
    $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
    $colspan++; $l_html.=chr(13).'          <td><b>Espécie</td>';
    $colspan++; $l_html.=chr(13).'          <td><b>Nº</td>';
    $colspan++; $l_html.=chr(13).'          <td><b>Data</td>';
    $colspan++; $l_html.=chr(13).'          <td><b>Procedência</td>';
    /*if ($l_tipo!='WORD')*/ $colspan++; $l_html.=chr(13).'          <td><b>Código</td>';
    $colspan++; $l_html.=chr(13).'          <td><b>Descrição</td>';
    $colspan++; $l_html.=chr(13).'          <td><b>Detalhamento</td>';
    //if ($l_tipo!='WORD') {
      $colspan++; $l_html.=chr(13).'          <td><b>Caixa</td>';
      $colspan++; $l_html.=chr(13).'          <td><b>Pasta</td>';
      $colspan++; $l_html.=chr(13).'          <td><b>Local</td>';
    //}
    $l_html.=chr(13).'        </tr>';
    if (count($RS1)==0) {
      // Se não foram selecionados registros, exibe mensagem
      $l_html.=chr(13).'      <tr><td colspan='.$colspan.' align="center"><b>Não foram encontrados registros.</b></td></tr>';
    } else {
      // Lista os registros selecionados para listagem
      $w_atual = '';
      foreach($RS1 as $row){ 
        if (f($row,'cd_assunto')!=$w_atual) {
          if ($w_atual!='') {
            $l_html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'" valign="top">';
            $l_html.=chr(13).'        <td colspan='.($colspan-1).' align="right"><b>Documentos na classificação '.$w_atual.'</b>';
            $l_html.=chr(13).'        <td align="center"><b>'.$w_cont.'</b></td>';
            $l_html.=chr(13).'      </tr>';
          }
          $w_atual = f($row,'cd_assunto');
          $w_cont  = 0;
        }
        $l_html.=chr(13).'        <tr valign="top">';
        if (!($l_P1==4 || $l_tipo=='WORD')){
          $l_html.=chr(13).'        <td align="center"><A class="HL" HREF="'.$w_dir.'documento.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'chave').'&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" target="visualdoc" title="Exibe as informações deste registro.">'.f($row,'numero_documento').'/'.substr(f($row,'ano'),2,2).'&nbsp;</a>';
        } else {
          $l_html.=chr(13).'        <td align="center">'.f($row,'numero_documento').'/'.substr(f($row,'ano'),2,2);
        }
        $l_html.=chr(13).'        <td>'.f($row,'nm_tipo').'</td>';
        $l_html.=chr(13).'        <td>'.f($row,'nm_especie').'</td>';
        $l_html.=chr(13).'        <td>'.f($row,'numero_original').'</td>';
        $l_html.=chr(13).'        <td align="center">'.formataDataEdicao(f($row,'ini_item'),5).'</td>';
        $l_html.=chr(13).'        <td>&nbsp;'.f($row,'nm_origem_doc').'</td>';
        /*if ($l_tipo!='WORD') */$l_html.=chr(13).'        <td>'.f($row,'cd_assunto').'</td>';
        $l_html.=chr(13).'        <td>'.f($row,'ds_assunto').'</td>';
        $l_html.=chr(13).'        <td>'.wordwrap(f($row,'descricao'),35,'<br />',true).'</td>';
        //if ($l_tipo!='WORD') {
          $l_html.=chr(13).'        <td>'.f($row,'nr_caixa').((nvl(f($row,'nr_caixa'),'')!='') ? '/' : '').f($row,'sg_unid_caixa').'</td>';
          $l_html.=chr(13).'        <td align="center">'.f($row,'pasta').'</td>';
          $l_html.=chr(13).'        <td>'.f($row,'nm_arquivo_local').'</td>';
        //}
        $l_html.=chr(13).'        <td align="center">'.nvl(str_replace('/20','/',f($row,'prazo_guarda')),'&nbsp;').'</td>';
        $w_cont++;
      }
      if ($w_atual!='') {
        $l_html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'" valign="top">';
        $l_html.=chr(13).'        <td colspan='.($colspan-1).' align="right"><b>Documentos na classificação '.$w_atual.'</b>';
        $l_html.=chr(13).'        <td align="center"><b>'.$w_cont.'</b></td>';
        $l_html.=chr(13).'      </tr>';
        $w_cont = 0;
      }
    } 
    $l_html.=chr(13).'         </table></td></tr>';
  }

  if ($l_O=='L' || $l_O=='V') {
    // Se for envio, executa verificações nos dados da solicitação
    $w_erro = ValidaEliminacao($w_cliente,$v_chave,substr($w_sigla,0,4).'GERAL',null,null,null,Nvl($w_tramite,0));
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


    // Encaminhamentos
    include_once($w_dir_volta.'funcoes/exibeLog.php');
    $l_html .= exibeLog($v_chave,$l_O,$l_usuario,$w_tramite_ativo,(($l_tipo=='WORD') ? 'WORD' : 'HTML'));
  }
  $l_html .= chr(13).'</table>';
  return $l_html;
}
?>