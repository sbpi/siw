<?
// =========================================================================
// Rotina de visualização dos dados da demanda
// -------------------------------------------------------------------------
function VisualDemanda($w_chave,$operacao,$w_usuario) {
  extract($GLOBALS);
  include_once($w_dir_volta.'classes/sp/db_getSolicLog.php');

  $w_html = '';
  // Recupera os dados da demanda
  $RS = db_getSolicData::getInstanceOf($dbms,$w_chave,'GDGERAL');

  // Recupera o tipo de visão do usuário
  if (Nvl(f($RS,'solicitante'),0)==$w_usuario || 
     Nvl(f($RS,'executor'),0)==$w_usuario || 
     Nvl(f($RS,'cadastrador'),0)==$w_usuario || 
     Nvl(f($RS,'titular'),0)==$w_usuario || 
     Nvl(f($RS,'substituto'),0)==$w_usuario || 
     Nvl(f($RS,'tit_exec'),0)==$w_usuario || 
     Nvl(f($RS,'subst_exec'),0)==$w_usuario || 
     SolicAcesso($w_chave,$w_usuario)>=8)
  {
    // Se for solicitante, executor ou cadastrador, tem visão completa
    $w_tipo_visao=0;
  } else {
    $RSQuery = db_getSolicInter::getInstanceOf($dbms,$w_chave,$w_usuario,'REGISTRO');
    if (count($RSQuery)>0) {
      // Se for interessado, verifica a visão cadastrada para ele.
      $w_tipo_visao = f($RSQuery,'tipo_visao');
    } else {
      $RS = db_getSolicAreas::getInstanceOf($dbms,$w_chave,$sq_lotacao_session,'REGISTRO');
      if (!($RSQuery==0)) {
        // Se for de uma das unidades envolvidas, tem visão parcial
        $w_tipo_visao=1;
      } else {
        // Caso contrário, tem visão resumida
        $w_tipo_visao=2;
      } 
      if (SolicAcesso($w_chave,$w_usuario)>2) $w_tipo_visao=1;
    } 
  } 

  // Se for listagem ou envio, exibe os dados de identificação da demanda

  if ($operacao=='L' || $operacao=='V') {
    // Se for listagem dos dados
    $w_html = $w_html.chr(13).'<div align=center><center>';
    $w_html = $w_html.chr(13).'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
    $w_html = $w_html.chr(13).'<tr bgcolor="'.$conTrBgColor.'"><td align="center">';

    $w_html = $w_html.chr(13).'    <table width="99%" border="0">';
    if (nvl(f($RS,'nm_projeto'),'')>'') {
      $w_html = $w_html.chr(13).'      <tr><td valign="top"><font size="1">Projeto: <b>'.f($RS,'nm_projeto').'  ('.f($RS,'sq_solic_pai').')</b></td>';
    } 

    if (nvl(f($RS,'nm_etapa'),'')>'') {
      $w_html = $w_html.chr(13).'      <tr><td valign="top"><font size="1">Etapa: <b>'.MontaOrdemEtapa(f($RS,'sq_projeto_etapa')).'. '.f($RS,'nm_etapa').' </b></td>';
    } 

    $w_html = $w_html.chr(13).'      <tr><td><font size=1>Detalhamento: <b>'.$w_chave.'<br>'.CRLF2BR(f($RS,'assunto')).'</b></font></td></tr>';

    // Identificação da demanda
    $w_html = $w_html.chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Identificação</td>';

    // Se a classificação foi informada, exibe.
    if (nvl(f($RS,'sq_cc'),'')>'') {
      $w_html = $w_html.chr(13).'      <tr><td valign="top"><font size="1">Classificação:<br><b>'.f($RS,'cc_nome').' </b></td>';
    } 

    $w_html = $w_html.chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
    $w_html = $w_html.chr(13).'          <tr valign="top">';
    $w_html = $w_html.chr(13).'          <td><font size="1">Cidade de origem:<br><b>'.f($RS,'nm_cidade').' ('.f($RS,'co_uf').')</b></td>';
    if (Nvl(f($RS,'proponente'),'')>'') {
      $w_html = $w_html.chr(13).'          <td colspan=2><font size="1">Proponente externo:<br><b>'.f($RS,'proponente').' </b></td>';
    } else {
      $w_html = $w_html.chr(13).'          <td colspan=2><font size="1">Proponente externo:<br><b> --- </b></td>';
    } 

    $w_html = $w_html.chr(13).'          <tr valign="top">';
    $w_html = $w_html.chr(13).'          <td><font size="1">Responsável:<br><b>'.ExibePessoa(null,$w_cliente,f($RS,'solicitante'),$TP,f($RS,'nm_sol')).'</b></td>';
    $w_html = $w_html.chr(13).'          <td><font size="1">Unidade responsável:<br><b>'.ExibeUnidade(null,$w_cliente,f($RS,'nm_unidade_resp'),f($RS,'sq_unidade_resp'),$TP).'</b></td>';

    if ($w_tipo_visao==0) {
      // Se for visão completa
      $w_html = $w_html.chr(13).'          <td valign="top"><font size="1">Orçamento disponível:<br><b>'.number_format(f($RS,'valor'),2,',','.').' </b></td>';
    } 

    $w_html = $w_html.chr(13).'          <tr valign="top">';
    $w_html = $w_html.chr(13).'          <td><font size="1">Data de recebimento:<br><b>'.FormataDataEdicao(f($RS,'inicio')).' </b></td>';
    $w_html = $w_html.chr(13).'          <td><font size="1">Limite para conclusão:<br><b>'.FormataDataEdicao(f($RS,'fim')).' </b></td>';
    $w_html = $w_html.chr(13).'          <td><font size="1">Prioridade:<br><b>'.RetornaPrioridade(f($RS,'prioridade')).' </b></td>';
    $w_html = $w_html.chr(13).'          <tr>';
    $w_html = $w_html.chr(13).'          <td colspan=3><font size="1">Palavras-chave:<br><b>'.f($RS,'palavra_chave').' </b></td>';
    $w_html = $w_html.chr(13).'          </table>';

    if ($w_tipo_visao==0 || $w_tipo_visao==1) {
      // Informações adicionais
      if (Nvl(f($RS,'descricao'),'')>'' || Nvl(f($RS,'justificativa'),'')>'') {
        $w_html = $w_html.chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Informações adicionais</td>';
        if (Nvl(f($RS,'descricao'),'')>'') $w_html = $w_html.chr(13).'      <tr><td valign="top"><font size="1">Resultados da demanda:<br><b>'.CRLF2BR(f($RS,'descricao')).' </b></td>';
        if ($w_tipo_visao==0 && Nvl(f($RS,'justificativa'),'')>'') {
          // Se for visão completa
          $w_html = $w_html.chr(13).'      <tr><td valign="top"><font size="1">Recomendações superiores:<br><b>'.CRLF2BR(f($RS,'justificativa')).' </b></td>';
        } 
      } 
    } 

    // Dados da conclusão da demanda, se ela estiver nessa situação
    if (f($RS,'concluida')=='S' && Nvl(f($RS,'data_conclusao'),'')>'') {
      $w_html==$w_html.chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Dados da conclusão</td>';
      $w_html = $w_html.chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
      $w_html = $w_html.chr(13).'          <tr valign="top">';
      $w_html = $w_html.chr(13).'          <td><font size="1">Início da execução:<br><b>'.FormataDataEdicao(f($RS,'inicio_real')).' </b></td>';
      $w_html = $w_html.chr(13).'          <td><font size="1">Término da execução:<br><b>'.FormataDataEdicao(f($RS,'fim_real')).' </b></td>';
      if ($w_tipo_visao==0) {
        $w_html = $w_html.chr(13).'          <td><font size="1">Custo real:<br><b>'.number_format(f($RS,'custo_real'),2,',','.').' </b></td>';
      } 
      $w_html = $w_html.chr(13).'          </table>';
      if ($w_tipo_visao==0) {
        $w_html = $w_html.chr(13).'      <tr><td valign="top"><font size="1">Nota de conclusão:<br><b>'.CRLF2BR(f($RS,'nota_conclusao')).' </b></td>';
      } 
    } 
  } 

  // Se for listagem, exibe os outros dados dependendo do tipo de visão  do usuário
  if ($operacao=='L' && $w_tipo_visao!=2) {
    if (f($RS,'aviso_prox_conc')=='S') {
      // Configuração dos alertas de proximidade da data limite para conclusão da demanda
      $w_html = $w_html.chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Alertas</td>';
      $w_html = $w_html.chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
      $w_html = $w_html.chr(13).'          <td valign="top"><font size="1">Emite aviso:<br><b>'.str_replace('N','Não',str_replace('S','Sim',f($RS,'aviso_prox_conc'))).' </b></td>';
      $w_html = $w_html.chr(13).'          <td valign="top"><font size="1">Dias:<br><b>'.f($RS,'dias_aviso').' </b></td>';
      $w_html = $w_html.chr(13).'          </table>';
    } 

    // Interessados na execução da demanda
    $RS = db_getSolicInter::getInstanceOf($dbms,$w_chave,null,'LISTA');
    $RS = SortArray($RS,'nome','asc');
    if (count($RS)>0) {
      $w_html = $w_html.chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Interessados na execução</td>';
      $w_html = $w_html.chr(13).'      <tr><td align="center" colspan="2">';
      $w_html = $w_html.chr(13).'        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
      $w_html = $w_html.chr(13).'          <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $w_html = $w_html.chr(13).'            <td><font size="1"><b>Nome</font></td>';
      $w_html = $w_html.chr(13).'            <td><font size="1"><b>Tipo de visão</font></td>';
      $w_html = $w_html.chr(13).'            <td><font size="1"><b>Envia e-mail</font></td>';
      $w_html = $w_html.chr(13).'          </tr>';
      $w_cor=$conTrBgColor;
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        $w_html = $w_html.chr(13).'      <tr valign="top" bgcolor="'.$w_cor.'">';
        $w_html = $w_html.chr(13).'        <td><font size="1">'.ExibePessoa(null,$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'nome').' ('.f($row,'lotacao').')').'</td>';
        $w_html = $w_html.chr(13).'        <td><font size="1">'.RetornaTipoVisao(f($row,'tipo_visao')).'</td>';
        $w_html = $w_html.chr(13).'        <td align="center"><font size="1">'.str_replace('N','Não',str_replace('S','Sim',f($row,'envia_email'))).'</td>';
        $w_html = $w_html.chr(13).'      </tr>';
      } 
      $w_html = $w_html.chr(13).'         </table></td></tr>';
    } 

    // Áreas envolvidas na execução da demanda
    $RS = db_getSolicAreas::getInstanceOf($dbms,$w_chave,null,'LISTA');
    $RS = SortArray($RS,'nome','asc');
    if (count($RS)>0) {
      $w_html = $w_html.chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Áreas/Instituições envolvidas</td>';
      $w_html = $w_html.chr(13).'      <tr><td align="center" colspan="2">';
      $w_html = $w_html.chr(13).'        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
      $w_html = $w_html.chr(13).'          <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $w_html = $w_html.chr(13).'            <td><font size="1"><b>Nome</font></td>';
      $w_html = $w_html.chr(13).'            <td><font size="1"><b>Papel</font></td>';
      $w_html = $w_html.chr(13).'          </tr>';
      $w_cor=$conTrBgColor;
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        $w_html = $w_html.chr(13).'      <tr valign="top" bgcolor="'.$w_cor.'">';
        $w_html = $w_html.chr(13).'        <td><font size="1">'.f($row,'nome').'</td>';
        $w_html = $w_html.chr(13).'        <td><font size="1">'.f($row,'papel').'</td>';
        $w_html = $w_html.chr(13).'      </tr>';
      } 
      $w_html = $w_html.chr(13).'         </table></td></tr>';
    } 
  } 

  if ($operacao=='L' || $operacao=='V') {
    // Se for listagem dos dados
    // Arquivos vinculados
    $RS = db_getSolicAnexo::getInstanceOf($dbms,$w_chave,null,$w_cliente);
    $RS = SortArray($RS,'nome','asc');
    if (count($RS)>0) {
      $w_html = $w_html.chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Arquivos anexos</td>';
      $w_html = $w_html.chr(13).'      <tr><td align="center" colspan="2">';
      $w_html = $w_html.chr(13).'        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
      $w_html = $w_html.chr(13).'          <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $w_html = $w_html.chr(13).'          <td><font size="1"><b>Título</font></td>';
      $w_html = $w_html.chr(13).'          <td><font size="1"><b>Descrição</font></td>';
      $w_html = $w_html.chr(13).'          <td><font size="1"><b>Tipo</font></td>';
      $w_html = $w_html.chr(13).'          <td><font size="1"><b>KB</font></td>';
      $w_html = $w_html.chr(13).'          </tr>';
      $w_cor=$conTrBgColor;
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        $w_html = $w_html.chr(13).'      <tr valign="top" bgcolor="'.$w_cor.'">';
        $w_html = $w_html.chr(13).'        <td><font size="1">'.LinkArquivo('HL',$w_cliente,f($row,'chave_aux'),'_blank','Clique para exibir o arquivo em outra janela.',f($row,'nome'),null).'</td>';
        $w_html = $w_html.chr(13).'        <td><font size="1">'.Nvl(f($row,'descricao'),'---').'</td>';
        $w_html = $w_html.chr(13).'        <td><font size="1">'.f($row,'tipo').'</td>';
        $w_html = $w_html.chr(13).'        <td align="right"><font size="1">'.round(f($row,'tamanho')/1024,1).'&nbsp;</td>';
        $w_html = $w_html.chr(13).'      </tr>';
      } 
      $w_html = $w_html.chr(13).'         </table></td></tr>';
    } 

    // Encaminhamentos
    $RS = db_getSolicLog::getInstanceOf($dbms,$w_chave,null,'LISTA');
    $RS = SortArray($RS,'data','desc','sq_siw_solic_log','desc');
    $w_html = $w_html.chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Ocorrências e Anotações</td>';
    $w_html = $w_html.chr(13).'      <tr><td align="center" colspan="2">';
    $w_html = $w_html.chr(13).'        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
    $w_html = $w_html.chr(13).'          <tr bgcolor="'.$conTrBgColor.'" align="center">';
    $w_html = $w_html.chr(13).'            <td><font size="1"><b>Data</font></td>';
    $w_html = $w_html.chr(13).'            <td><font size="1"><b>Despacho/Observação</font></td>';
    $w_html = $w_html.chr(13).'            <td><font size="1"><b>Responsável</font></td>';
    $w_html = $w_html.chr(13).'            <td><font size="1"><b>Fase / Destinatário</font></td>';
    $w_html = $w_html.chr(13).'          </tr>';
    if (count($RS)<=0) {
      $w_html = $w_html.chr(13).'      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><font size="1"><b>Não foram encontrados encaminhamentos.</b></td></tr>';
    } else {
      $w_html = $w_html.chr(13).'      <tr bgcolor="'.$conTrBgColor.'" valign="top">';
      $w_html = $w_html.chr(13).'        <td colspan=6><font size="1">Fase atual: <b>'.f($RS,'fase').'</b></td>';
      $w_cor=$conTrBgColor;
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        $w_html = $w_html.chr(13).'      <tr valign="top" bgcolor="'.$w_cor.'">';
        $w_html = $w_html.chr(13).'        <td nowrap><font size="1">'.FormataDataEdicao(f($row,'data')).', '.$FormatDateTime[f($row,'data')][4].'</td>';
        if (Nvl(f($row,'caminho'),'')>'') {
          $w_html = $w_html.chr(13).'        <td><font size="1">'.CRLF2BR(Nvl(f($row,'despacho'),'---').'<br>'.LinkArquivo('HL',$w_cliente,f($row,'sq_siw_arquivo'),'_blank','Clique para exibir o anexo em outra janela.','Anexo - '.f($row,'tipo').' - '.round(f($row,'tamanho')/1024,1).' KB',null)).'</td>';
        } else {
          $w_html = $w_html.chr(13).'        <td><font size="1">'.CRLF2BR(Nvl(f($row,'despacho'),'---')).'</td>';
        } 
        $w_html = $w_html.chr(13).'        <td nowrap><font size="1">'.ExibePessoa(null,$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'responsavel')).'</td>';
        if (nvl(f($row,'sq_demanda_log'),'')>'' && nvl(f($row,'destinatario'),'')>'') {
          $w_html = $w_html.chr(13).'        <td nowrap><font size="1">'.ExibePessoa(null,$w_cliente,f($row,'sq_pessoa_destinatario'),$TP,f($row,'destinatario')).'</td>';
        } elseif (nvl(f($row,'sq_demanda_log'),'')>'' && nvl(f($row,'destinatario'),'')=='') {
          $w_html = $w_html.chr(13).'        <td nowrap><font size="1">Anotação</td>';
       } else {
          $w_html = $w_html.chr(13).'        <td nowrap><font size="1">'.Nvl(f($row,'tramite'),'---').'</td>';
        } 
        $w_html = $w_html.chr(13).'      </tr>';
      } 
    } 
    $w_html = $w_html.chr(13).'         </table></td></tr>';
    $w_html = $w_html.chr(13).'</table>';
  } 
  return $w_html;
}
?>


