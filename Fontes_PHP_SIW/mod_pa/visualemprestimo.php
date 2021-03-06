<?php
// =========================================================================
// Rotina de visualiza��o dos dados da solicitacao
// -------------------------------------------------------------------------
function VisualEmprestimo($v_chave,$l_O,$l_usuario,$l_P1,$l_tipo) {
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

  // Recupera o tipo de vis�o do usu�rio
  if (Nvl(f($RS,'solicitante'),0)   == $l_usuario || 
      Nvl(f($RS,'executor'),0)      == $l_usuario || 
      Nvl(f($RS,'cadastrador'),0)   == $l_usuario || 
      Nvl(f($RS,'titular'),0)       == $l_usuario || 
      Nvl(f($RS,'substituto'),0)    == $l_usuario || 
      Nvl(f($RS,'tit_exec'),0)      == $l_usuario || 
      Nvl(f($RS,'subst_exec'),0)    == $l_usuario || 
      SolicAcesso($v_chave,$l_usuario)>=8) {
    // Se for solicitante, executor ou cadastrador, tem vis�o completa
    $w_tipo_visao=0;
  } else {
    if (SolicAcesso($v_chave,$l_usuario)>2) $w_tipo_visao = 1;
  } 
  // Se for listagem ou envio, exibe os dados de identifica��o do lan�amento
  $l_html.=chr(13).'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
  $l_html.=chr(13).'<tr><td colspan="5">&nbsp;<br>';
  $l_html.=chr(13).'    <table width="99%" border="0">';
  $l_html.=chr(13).'      <tr><td colspan="5" height="2" bgcolor="#000000"></td></tr>';
  $l_html.=chr(13).'      <tr><td colspan="5" bgcolor="#f0f0f0"><font size="2"><b>'.upper(f($RS,'nome')).' '.f($RS,'codigo_interno').' ('.$v_chave.')</b></td>';
  $l_html.=chr(13).'      <tr><td colspan="5" height="2" bgcolor="#000000"></td></tr>';
   
  // Identifica��o do lan�amento
  $l_html .= chr(13).'      <tr><td valign="top" colspan="5"><table border=0 width="100%" cellspacing=0>';
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
  $l_html.=chr(13).'      <tr><td><b>Justificativa:</b></td><td>'.crlf2br(f($RS,'justificativa')).' </td></tr>';
  $l_html.=chr(13).'      <tr><td><b>Data prevista para devolu��o:</b></td><td align="left">'.FormataDataEdicao(f($RS,'fim')).' </td></tr>';
  $l_html.=chr(13).'          </table></td></tr>';    
  
  if ($l_O!='X') {
    //Listagem dos itens do pedido de compra. N�o exibido quando opera��o igual a X (conclus�o do pedido).
    $sql = new db_getPAEmpItem; $RS1 = $sql->getInstanceOf($dbms,null,$v_chave,null,null,null,null);
    $RS1 = SortArray($RS1,'ano','asc','protocolo','asc'); 
    $l_html.=chr(13).'      <tr><td colspan="5"><br><font size="2"><b>ITENS ('.count($RS1).')<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $l_html.=chr(13).'      <tr><td colspan="5"><div align="center"><table width=100%  border="1" bordercolor="#00000">';
    $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
    $l_html.=chr(13).'          <td rowspan=2 width="1%" nowrap><b>Protocolo</td>';
    $l_html.=chr(13).'          <td rowspan=2 width="1%" nowrap><b>Tipo</td>';
    $l_html.=chr(13).'          <td colspan=4><b>Documento original</td>';
    if ($w_sg_tramite=='EE' || $w_sg_tramite=='AT') $l_html.=chr(13).'          <td rowspan=2><b>Devolu��o</td>';
    $l_html.=chr(13).'        </tr>';
    $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
    $l_html.=chr(13).'          <td><b>Esp�cie</td>';
    $l_html.=chr(13).'          <td><b>N�</td>';
    $l_html.=chr(13).'          <td><b>Data</td>';
    $l_html.=chr(13).'          <td><b>Proced�ncia</td>';
    $l_html.=chr(13).'        </tr>';
    if (count($RS1)<=0) {
      // Se n�o foram selecionados registros, exibe mensagem
      $l_html.=chr(13).'      <tr><td colspan=6 align="center"><b>N�o foram encontrados registros.</b></td></tr>';
    } else {
      // Lista os registros selecionados para listagem
      foreach($RS1 as $row){ 
        $l_html.=chr(13).'        <tr valign="top">';
        if (!($l_P1==4 || $l_tipo=='WORD')){
          $l_html.=chr(13).'        <td align="center" width="1%" nowrap><A class="HL" HREF="'.$w_dir.'documento.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'chave').'&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" target="visualdoc" title="Exibe as informa��es deste registro.">'.f($row,'protocolo').'&nbsp;</a>';
        } else {
          $l_html.=chr(13).'        <td align="center" width="1%" nowrap>'.f($row,'protocolo');
        }
        $l_html.=chr(13).'        <td width="10">&nbsp;'.f($row,'nm_tipo').'</td>';
        $l_html.=chr(13).'        <td>&nbsp;'.f($row,'nm_especie').'</td>';
        $l_html.=chr(13).'        <td>&nbsp;'.f($row,'numero_original').'</td>';
        $l_html.=chr(13).'        <td align="center">&nbsp;'.formataDataEdicao(f($row,'inicio'),5).'&nbsp;</td>';
        $l_html.=chr(13).'        <td>&nbsp;'.f($row,'nm_origem_doc').'</td>';
        if ($w_sg_tramite=='AT' && nvl(f($row,'devolucao'),'')=='') {
          $l_html.=chr(13).'        <td align="center">Atendido por c�pia</td>';
        } elseif ($w_sg_tramite=='EE' || $w_sg_tramite=='AT') {
          $l_html.=chr(13).'        <td align="center">&nbsp;'.formataDataEdicao(f($row,'devolucao'),5).'&nbsp;</td>';
        }
      }
    } 
    $l_html.=chr(13).'         </table></td></tr>';
  }

  if ($l_O=='L' || $l_O=='V') {
    // Se for envio, executa verifica��es nos dados da solicita��o
    $w_erro = ValidaEmprestimo($w_cliente,$v_chave,substr($w_sigla,0,4).'GERAL',null,null,null,Nvl($w_tramite,0));
    if ($w_erro>'') {
      $l_html.=chr(13).'<tr><td colspan="5"><font size=2>';
      $l_html.=chr(13).'<HR>';
      if (substr($w_erro,0,1)=='0') {
        $l_html.=chr(13).'  <font color="#BC3131"><b>ATEN��O:</b></font> Foram identificadas as pend�ncias listadas abaixo, n�o sendo poss�vel seu encaminhamento para fases posteriores � atual.';
      } elseif (substr($w_erro,0,1)=='1') {
        $l_html.=chr(13).'  <font color="#BC3131"><b>ATEN��O:</b></font> Foram identificadas as pend�ncias listadas abaixo. Seu encaminhamento para fases posteriores � atual s� pode ser feito por um gestor do sistema ou deste m�dulo.';
      } else {
        $l_html.=chr(13).'  <font color="#BC3131"><b>ATEN��O:</b></font> Foram identificados os alertas listados abaixo. Eles n�o impedem o encaminhamento para fases posteriores � atual, mas conv�m sua verifica��o.';
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