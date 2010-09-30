<?php
// =========================================================================
// Rotina de visualização dos dados do documento
// -------------------------------------------------------------------------
function VisualFE($l_chave, $l_menu=null, $l_formato='WORD') {
  extract($GLOBALS);

  $RS = new db_getCustomerData; $RS = $RS->getInstanceOf($dbms,$w_cliente);

  // Recupera os dados da guia
  // Recupera os dados da solicitacao
  $sql = new db_getSolicPA; $RS1 = $sql->getInstanceOf($dbms,null,$l_usuario,'PAEMP',5,
          null,null,null,null,null,null,null,null,null,null,
          $l_chave,null,null,null,null,null,null,
          null,null,null,null,null,null,null,null,null,null,null);
  foreach($RS1 as $row) { $RS1 = $row; break; }
  
  $sql = new db_getPAEmpItem; $RS_Dados = $sql->getInstanceOf($dbms,null,$l_chave,null,null,null,null);
  $RS_Dados = SortArray($RS_Dados,'ano','asc','protocolo','asc'); 
  
  if ($l_formato=='WORD') $l_html = BodyOpenWord(null); else $l_html = '';
  $w_linha = 99;
  $w_pag   = 1;
  $w_devolvidos = false;

  foreach ($RS_Dados as $row) {
    if (nvl(f($row,'devolucao'),'')!='') $w_devolvidos = true;
    if (($w_linha > 30 && $l_formato=='WORD') || ($w_pag==1 && $l_formato!='WORD')) {
      if ($w_pag>1 && $l_formato=='WORD') {
        $l_html.=chr(13).'    </table>';
        $l_html.=chr(13).'    <tr><td colspan=2><p>&nbsp;</p>';
        $l_html.=chr(13).'    <tr><td colspan=2><table border=0 width="100%"><tr valign="top">';
        $l_html.=chr(13).'      <td align="center" width=50%>__________________________________________________<br><b>Nome do colaborador do Arquivo</b>';
        $l_html.=chr(13).'      <td align="center" width=50%>__________________________________________________<br><b>Nome do requerente</b>';
        $l_html.=chr(13).'    </table>';
        $l_html.=chr(13).'  </table>';
        $l_html.=chr(13).'</table>';
        $l_html.=chr(13).'<br style="page-break-after:always">';
      }
      $l_html.=chr(13).'<table width="98%" border="0" cellspacing="3">';
      if ($l_formato=='WORD') {
        $l_html.=chr(13).'<tr><td colspan="2">';
        $l_html.=chr(13).'  <table width="100%" border=0>';
        $l_html.=chr(13).'    <tr valign="top">';
        $l_html.=chr(13).'      <td><font size=1><b>'.upper(f($RS,'nome').' - '.f($RS,'nome_resumido')).'</b></font></td>';
        $l_html.=chr(13).'      <td align="right"><font size=1><b>Pág. '.$w_pag.'</b></td></font></tr>';
        $l_html.=chr(13).'    <tr valign="top">';
        $l_html.=chr(13).'      <td><font size=1><b>'.upper($conSgSistema.' - Módulo de '.f($RS_Menu,'nm_modulo')).'</b></font></td>';
        $l_html.=chr(13).'      <td align="right"><font size=1><b>'.datahora().'</b></td></font></tr>';
        $l_html.=chr(13).'  </table>';
      }
      $l_html.=chr(13).'<tr><td colspan="2" align="center">';
      $l_html.=chr(13).'    <table width="100%" border="0" cellspacing="3">';
      $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
      $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0" align=center><font size="2"><b>FORMULÁRIO DE EMPRÉSTIMO '.f($RS1,'codigo_interno').'</b></font></td></tr>';
      $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
      $l_html.=chr(13).'   <tr><td width="30%"><b>Solicitante:</b></td>';
      $l_html.=chr(13).'       <td>'.f($RS1,'nm_solic').'</td></tr>';
      $l_html.=chr(13).'   <tr><td width="30%"><b>Unidade solicitante:</b></td>';
      $l_html.=chr(13).'       <td>'.f($RS1,'nm_unidade_resp').'</td></tr>';
      $l_html.=chr(13).'   <tr><td width="30%"><b>Data da devolução:</b></td>';
      $l_html.=chr(13).'       <td>'.formataDataEdicao(f($RS1,'fim')).'</td></tr>';
      
      $l_html.=chr(13).'   <tr><td colspan=2><br><b>PROTOCOLOS EMPRESTADOS</b></td></tr>';
      $l_html.=chr(13).'   <tr><td colspan=2><table border=1 width="100%">';
      $l_html.=chr(13).'     <tr align="center">';
      $l_html.=chr(13).'       <td rowspan=2 width="1%" nowrap><b>Protocolo</td>';
      $l_html.=chr(13).'       <td rowspan=2 width="1%" nowrap><b>Tipo</td>';
      $l_html.=chr(13).'       <td colspan=4><b>Documento original</td>';
      $l_html.=chr(13).'       <td colspan=3><b>Localização</td>';
      $l_html.=chr(13).'     </tr>';
      $l_html.=chr(13).'     <tr valign="top" align="center">';
      $l_html.=chr(13).'       <td><font size=1><b>Espécie</b></font></td>';
      $l_html.=chr(13).'       <td><font size=1><b>Nº</b></font></td>';
      $l_html.=chr(13).'       <td><font size=1><b>Data</b></font></td>';
      $l_html.=chr(13).'       <td><font size=1><b>Procedência</b></font></td>';
      $l_html.=chr(13).'       <td><b>Caixa</td>';
      $l_html.=chr(13).'       <td><b>Pasta</td>';
      $l_html.=chr(13).'       <td><b>Local</td>';
      $l_html.=chr(13).'     </tr>';
      $w_pag   += 1;
      $w_linha = 6;
    } 
    $l_html.=chr(13).'     <tr valign="top">';
    $l_html.=chr(13).'       <td align="center" nowrap><font size=1>'.f($row,'protocolo');
    $l_html.=chr(13).'       <td><font size=1>'.f($row,'nm_tipo').'</td>';
    $l_html.=chr(13).'       <td><font size=1>'.f($row,'nm_especie').'</td>';
    $l_html.=chr(13).'       <td><font size=1>'.f($row,'numero_original').'</td>';
    $l_html.=chr(13).'       <td align="center"><font size=1>'.date(d.'/'.m.'/'.y,f($row,'inicio')).'</font></td>';
    $l_html.=chr(13).'       <td>&nbsp;'.f($row,'nm_origem_doc').'</td>';
    $l_html.=chr(13).'       <td><font size=1>'.f($row,'nr_caixa').'/'.f($row,'sg_unid_caixa').'</td>';
    $l_html.=chr(13).'       <td align="center"><font size=1>'.f($row,'pasta').'</td>';
    $l_html.=chr(13).'       <td><font size=1>'.f($row,'nm_arquivo_local').'</td>';
    $l_html.=chr(13).'     </tr>';
    $w_linha += 1;
  }
  $l_html.=chr(13).'    </table>';
  if ($l_formato=='WORD') {
    $l_html.=chr(13).'    <tr><td colspan=2><p>&nbsp;</p>';
    $l_html.=chr(13).'    <tr><td colspan=2><table border=0 width="100%"><tr valign="top">';
    $l_html.=chr(13).'      <td align="center" width=50%>__________________________________________________<br><b>Nome do colaborador do Arquivo</b>';
    $l_html.=chr(13).'      <td align="center" width=50%>__________________________________________________<br><b>Nome do requerente</b>';
    $l_html.=chr(13).'    </table>';
  }
  
  // Exibe os itens já devolvidos
  if ($w_devolvidos) {
    $w_cabecalho = true;
    foreach ($RS_Dados as $row) {
      if (nvl(f($row,'devolucao'),'')!='') {
        if (($w_linha > 30 && $l_formato=='WORD') || ($w_pag==1 && $l_formato!='WORD') || $w_cabecalho) {
          if ($w_pag>1 && $l_formato=='WORD' && !$w_cabecalho) {
            $l_html.=chr(13).'    </table>';
            $l_html.=chr(13).'  </table>';
            $l_html.=chr(13).'</table>';
            $l_html.=chr(13).'<br style="page-break-after:always">';
          }
          $l_html.=chr(13).'<table width="100%" border="0" cellspacing="3">';
          if ($l_formato=='WORD' && !$w_cabecalho) {
            $l_html.=chr(13).'<tr><td colspan="2">';
            $l_html.=chr(13).'  <table width="100%" border=0>';
            $l_html.=chr(13).'    <tr valign="top">';
            $l_html.=chr(13).'      <td><font size=1><b>'.upper(f($RS,'nome').' - '.f($RS,'nome_resumido')).'</b></font></td>';
            $l_html.=chr(13).'      <td align="right"><font size=1><b>Pág. '.$w_pag.'</b></td></font></tr>';
            $l_html.=chr(13).'    <tr valign="top">';
            $l_html.=chr(13).'      <td><font size=1><b>'.upper($conSgSistema.' - Módulo de '.f($RS_Menu,'nm_modulo')).'</b></font></td>';
            $l_html.=chr(13).'      <td align="right"><font size=1><b>'.datahora().'</b></td></font></tr>';
            $l_html.=chr(13).'  </table>';
          }
          $l_html.=chr(13).'   <tr><td colspan=2><br><b>PROTOCOLOS DEVOLVIDOS</b></td></tr>';
          $l_html.=chr(13).'   <tr><td colspan=2><table border=1 width="100%">';
          $l_html.=chr(13).'     <tr align="center">';
          $l_html.=chr(13).'       <td rowspan=2 width="1%" nowrap><b>Protocolo</td>';
          $l_html.=chr(13).'       <td rowspan=2 width="1%" nowrap><b>Tipo</td>';
          $l_html.=chr(13).'       <td colspan=2><b>Devolução</td>';
          $l_html.=chr(13).'       <td rowspan=2><b>Assinatura do colaborador do Arquivo</td>';
          $l_html.=chr(13).'     </tr>';
          $l_html.=chr(13).'     <tr valign="top" align="center">';
          $l_html.=chr(13).'       <td><b>Prevista</td>';
          $l_html.=chr(13).'       <td><b>Realizada</td>';
          $l_html.=chr(13).'     </tr>';
          if (!$w_cabecalho) {
            $w_pag   += 1;
            $w_linha = 6;
          } else {
            $w_pag   += 1;
            $w_linha += 6;
            $w_cabecalho = false;
          }
        } 
        $l_html.=chr(13).'     <tr>';
        $l_html.=chr(13).'       <td align="center" nowrap><font size=1>'.f($row,'protocolo');
        $l_html.=chr(13).'       <td><font size=1>'.f($row,'nm_tipo').'</td>';
        $l_html.=chr(13).'       <td align="center"><font size=1>'.formataDataEdicao(f($RS1,'fim')).'</td>';
        $l_html.=chr(13).'       <td align="center"><font size=1>'.formataDataEdicao(f($row,'devolucao')).'</td>';
        $l_html.=chr(13).'       <td width="30%" height="40">&nbsp</td>';
        $l_html.=chr(13).'     </tr>';
        $w_linha += 1;
      }
    }
    $l_html.=chr(13).'    </table>';
  }
  $l_html.=chr(13).'  </table>';
  $l_html.=chr(13).'</table>';
  return $l_html;
} ?>
