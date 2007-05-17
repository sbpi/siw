<?
// =========================================================================
// Rotina de exibição dos alertas de atraso e proximidade da data de conclusão
// -------------------------------------------------------------------------
function VisualAlerta($l_cliente,$l_usuario,$l_tipo) {
  extract($GLOBALS);

  // Recupera solicitações a serem listadas
  $RS = db_getAlerta::getInstanceOf($dbms, $l_cliente, $l_usuario, 'SOLICGERAL', 'N');
  $RS = SortArray($RS,'nm_modulo','asc', 'nm_servico', 'asc', 'titulo', 'asc');

  $l_html = '<tr>'.chr(13).chr(10);
  $l_html .= '    <td><b>MÓDULOS E SERVIÇOS EM ATRASO OU ALERTA: '.chr(13).chr(10);
  $l_html .= '    <td align="right"><b>Registros: '.count($RS).chr(13).chr(10);
  $l_html .= '<tr><td align="center" colspan=2>'.chr(13).chr(10);
  $l_html .= '    <TABLE WIDTH="100%" bgcolor='.$conTableBgColor.' BORDER='.$conTableBorder.' CELLSPACING='.$conTableCellSpacing.' CELLPADDING='.$conTableCellPadding.' BorderColorDark='.$conTableBorderColorDark.' BorderColorLight='.$conTableBorderColorLight.'>'.chr(13).chr(10);
  $l_html .= '        <tr bgcolor='.$conTrBgColor.' align="center">'.chr(13).chr(10);
  $l_html .= '          <td><b>Módulo</td>'.chr(13).chr(10);
  $l_html .= '          <td><b>Serviço</td>'.chr(13).chr(10);
  $l_html .= '          <td><b>Código</td>'.chr(13).chr(10);
  $l_html .= '          <td><b>Título/Descrição</td>'.chr(13).chr(10);
  $l_html .= '          <td><b>Responsável</td>'.chr(13).chr(10);
  $l_html .= '          <td><b>Executor</td>'.chr(13).chr(10);
  $l_html .= '          <td><b>Término</td>'.chr(13).chr(10);
  $l_html .= '          <td><b>Fase atual</td>'.chr(13).chr(10);
  $l_html .= '        </tr>'.chr(13).chr(10);

  $w_sq_modulo='-';
  $w_sq_servico='-';
  foreach ($RS as $row) {
    // Alterna a cor de fundo para facilitar a leitura
    $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;

    // Se o usuário for responsável ou executor, destaca em vermelho
    if ($l_usuario==f($row,'solicitante')) $w_cor = $conTrBgColorLightBlue2; 
    if ($l_usuario==f($row,'sq_exec'))     $w_cor = $conTrBgColorLightBlue1; 

    if ($w_sq_modulo!=f($row,'sq_modulo') && $w_sq_modulo!='')  {
      $l_html .= '    <tr valign="top" bgcolor='.$conTrBgColor.'><td colspan=12><hr NOSHADE color=#000000 size=1></td></tr>'.chr(13).chr(10);
    } elseif ($w_sq_servico!=f($row,'sq_menu')  && $w_sq_servico!='') {
      $l_html .= '    <tr valign="top" bgcolor='.$conTrBgColor.'><td><td colspan=11><hr NOSHADE color=#000000 size=1></td></tr>'.chr(13).chr(10);
    }

    $l_html .= '    <tr valign="top" bgcolor='.$w_cor.'>'.chr(13).chr(10);

    // Evita que o nome do módulo seja repetido
    if ($w_sq_modulo!=f($row,'sq_modulo')) {
      $l_html .= '      <td bgcolor="'.$conTrBgColor.'">'.f($row,'nm_modulo').'</td>'.chr(13).chr(10);
      $w_sq_modulo=f($row,'sq_modulo');
    } else {
      $l_html .= '      <td bgcolor="'.$conTrBgColor.'">&nbsp;</td>'.chr(13).chr(10);
    } 
    
    // Evita que o nome do serviço seja repetido
    if ($w_sq_servico!=f($row,'sq_menu')) {
      $l_html .= '      <td bgcolor="'.$conTrBgColor.'">'.f($row,'nm_servico').'</td>'.chr(13).chr(10);
      $w_sq_servico=f($row,'sq_menu');
    } else {
      $l_html .= '      <td bgcolor="'.$conTrBgColor.'">&nbsp;</td>'.chr(13).chr(10);
    }

    $l_html .= '      <td nowrap>'.ExibeImagemSolic(f($row,'sg_servico'),f($row,'inicio'),f($row,'fim'),f($row,'inicio_real'),f($row,'fim_real'),f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null).' '.f($row,'codigo').'</td>'.chr(13).chr(10);
    $l_html .= '      <td>'.crlf2br(f($row,'titulo')).'</td>'.chr(13).chr(10);
    $l_html .= '      <td nowrap>'.f($row,'nm_resp').'</td>'.chr(13).chr(10);
    $l_html .= '      <td nowrap>'.nvl(f($row,'nm_exec'),'---').'</td>'.chr(13).chr(10);
    $l_html .= '      <td>'.formataDataEdicao(f($row,'fim'),5).'</td>'.chr(13).chr(10);
    $l_html .= '      <td>'.f($row,'nm_tramite').'</td>'.chr(13).chr(10);
    $l_html .= '      </td>'.chr(13).chr(10);
    $l_html .= '    </tr>'.chr(13).chr(10);
  }

  $l_html .= '    </table>'.chr(13).chr(10);
  $l_html .= '<tr><td><b>Legenda para as cores das linhas:</b><table border=0>'.chr(13).chr(10);
  $l_html .= '  <tr><td width=50 bgcolor="'.$conTrBgColorLightBlue1.'">&nbsp;<td>Você é o executor ou o responsável pelo trâmite.'.chr(13).chr(10);
  $l_html .= '  <tr><td width=50 bgcolor="'.$conTrBgColorLightBlue2.'">&nbsp;<td>Você é o solicitante ou o responsável pela solicitação.'.chr(13).chr(10);
  $l_html .= '  <tr><td width=50 bgcolor="'.$conTrBgColor.'">&nbsp;<td>Você tem permissão para acompanhar o andamento da solicitação.'.chr(13).chr(10);
  $l_html .= '  <tr><td width=50 bgcolor="'.$conTrAlternateBgColor.'">&nbsp;<td>Você tem permissão para acompanhar o andamento da solicitação.'.chr(13).chr(10);
  $l_html .= '  </table>'.chr(13).chr(10);
  $l_html .= '</tr>'.chr(13).chr(10);


  // Linha separadora entre os blocos
  $l_html .= '<tr><td colspan=2><p><hr noshade color=#000000 size=4></p></td></tr>'.chr(13).chr(10);

  // Recupera pacotes de trabalho a serem listados
  $RS = db_getAlerta::getInstanceOf($dbms, $l_cliente, $l_usuario, 'PACOTE', 'N');
  $RS = SortArray($RS,'nm_projeto','asc', 'cd_ordem', 'asc');

  $l_html .= '<tr>'.chr(13).chr(10);
  $l_html .= '    <td><b>PACOTES DE TRABALHO EM ATRASO OU ALERTA: '.chr(13).chr(10);
  $l_html .= '    <td align="right"><b>Registros: '.count($RS).chr(13).chr(10);
  $l_html .= '<tr><td align="center" colspan=2>'.chr(13).chr(10);
  $l_html .= '    <TABLE WIDTH="100%" bgcolor='.$conTableBgColor.' BORDER='.$conTableBorder.' CELLSPACING='.$conTableCellSpacing.' CELLPADDING='.$conTableCellPadding.' BorderColorDark='.$conTableBorderColorDark.' BorderColorLight='.$conTableBorderColorLight.'>'.chr(13).chr(10);
  $l_html .= '        <tr bgcolor='.$conTrBgColor.' align="center">'.chr(13).chr(10);
  $l_html .= '          <tr align="center">'.chr(13).chr(10);
  $l_html .= '            <td rowspan=2 bgColor="#f0f0f0" width="20">&nbsp;</td>'.chr(13).chr(10);
  $l_html .= '            <td rowspan=2 bgColor="#f0f0f0"><b>Etapa</b></td>'.chr(13).chr(10);
  $l_html .= '            <td rowspan=2 bgColor="#f0f0f0"><b>Título</b></td>'.chr(13).chr(10);
  $l_html .= '            <td rowspan=2 bgColor="#f0f0f0"><b>Responsável</b></td>'.chr(13).chr(10);
  $l_html .= '            <td rowspan=2 bgColor="#f0f0f0"><b>Setor</b></td>'.chr(13).chr(10);
  $l_html .= '            <td colspan=2 bgColor="#f0f0f0" nowrap><b>Execução prevista</b></td>'.chr(13).chr(10);
  $l_html .= '            <td rowspan=2 bgColor="#f0f0f0" nowrap><b>Início real</b></td>'.chr(13).chr(10);
  $l_html .= '            <td rowspan=2 bgColor="#f0f0f0"><b>Conc.</b></td>'.chr(13).chr(10);
  $l_html .= '          </tr>'.chr(13).chr(10);
  $l_html .= '          <tr align="center">'.chr(13).chr(10);
  $l_html .= '            <td bgColor="#f0f0f0"><b>De</b></td>'.chr(13).chr(10);
  $l_html .= '            <td bgColor="#f0f0f0"><b>Até</b></td>'.chr(13).chr(10);
  $l_html .= '          </tr>'.chr(13).chr(10);
  $l_html .= '        </tr>'.chr(13).chr(10);;

  $w_projeto=0;
  foreach ($RS as $row) {
    // Alterna a cor de fundo para facilitar a leitura
    $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;

    // Se o usuário for responsável ou executor, destaca em vermelho
    if ($l_usuario==f($row,'sq_resp_etapa')||$l_usuario==f($row,'tit_unid_resp_etapa')||$l_usuario==f($row,'sub_unid_resp_etapa')) $w_cor = $conTrBgColorLightBlue1; 

    if ($w_projeto!=f($row,'sq_siw_solicitacao') && $w_projeto!=0)  {
      $l_html .= '    <tr valign="top" bgcolor='.$conTrBgColor.'><td colspan=10><hr NOSHADE color=#000000 size=1></td></tr>'.chr(13).chr(10);
    }


    // Evita que o nome do módulo seja repetido
    if ($w_projeto!=f($row,'sq_siw_solicitacao')) {
      $l_html .= '    <tr valign="top" bgcolor='.$w_cor.'>'.chr(13).chr(10);
      $l_html .= '      <td bgcolor="'.$conTrAlternateBgColor.'" colspan=10><b>'.f($row,'nm_projeto').'</b></td>'.chr(13).chr(10);
      $w_projeto = f($row,'sq_siw_solicitacao');
    }

    $l_html .= '    <tr valign="top" bgcolor='.$w_cor.'>'.chr(13).chr(10);
    $l_html .= '        <td bgcolor="'.$conTrBgColor.'">&nbsp;</td>'.chr(13).chr(10);
    $l_html .= '        <td nowrap>'.ExibeImagemSolic('ETAPA',f($row,'inicio_previsto'),f($row,'fim_previsto'),f($row,'inicio_real'),f($row,'fim_real'),null,null,null,f($row,'perc_conclusao')).chr(13).chr(10);
    $l_html .= ' '.MontaOrdemEtapa(f($row,'sq_projeto_etapa')).exibeImagemRestricao(f($row,'restricao')).'</td>'.chr(13).chr(10);
    $l_html .= '        <td>'.f($row,'titulo').'</b>'.chr(13).chr(10);
    $l_html .= '        <td nowrap>'.f($row,'nm_resp_etapa').'</b>'.chr(13).chr(10);
    $l_html .= '        <td>'.f($row,'sg_unid_resp_etapa').'</b>'.chr(13).chr(10);
    $l_html .= '        <td align="center">'.formataDataEdicao(f($row,'inicio_previsto'),5).'</td>'.chr(13).chr(10);
    $l_html .= '        <td align="center">'.formataDataEdicao(f($row,'fim_previsto'),5).'</td>'.chr(13).chr(10);
    $l_html .= '        <td align="center">'.nvl(formataDataEdicao(f($row,'inicio_real'),5),'---').'</td>'.chr(13).chr(10);
    $l_html .= '        <td align="right" width="1%" nowrap>'.formatNumber(f($row,'perc_conclusao')).' %</td>'.chr(13).chr(10);
    $l_html .= '      </tr>'.chr(13).chr(10);
  }

  $l_html .= '    </table>'.chr(13).chr(10);
  $l_html .= '<tr><td><b>Legenda para as cores das linhas:</b><table border=0>'.chr(13).chr(10);
  $l_html .= '  <tr><td width=50 bgcolor="'.$conTrBgColorLightBlue1.'">&nbsp;<td>Você é o responsável ou o titular/substituto do setor responsável pelo pacote de trabalho.'.chr(13).chr(10);
  $l_html .= '  <tr><td width=50 bgcolor="'.$conTrBgColor.'">&nbsp;<td>Você tem permissão para acompanhar o andamento do pacote de trabalho.'.chr(13).chr(10);
  $l_html .= '  <tr><td width=50 bgcolor="'.$conTrAlternateBgColor.'">&nbsp;<td>Você tem permissão para acompanhar o andamento do pacote de trabalho.'.chr(13).chr(10);
  $l_html .= '  </table>'.chr(13).chr(10);
  $l_html .= '</tr>'.chr(13).chr(10);
  return $l_html;
} 

?>