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
    $w_html.=chr(13).'<div align=center><center>';
    $w_html.=chr(13).'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
    $w_html.=chr(13).'<tr bgcolor="'.$conTrBgColor.'"><td align="center">';

    $w_html.=chr(13).'    <table width="99%" border="0">';
    if (nvl(f($RS,'nm_projeto'),'')>'') {
      $w_html.=chr(13).'      <tr><td valign="top">Projeto: <b>'.f($RS,'nm_projeto').'  ('.f($RS,'sq_solic_pai').')</b></td>';
    } 

    if (nvl(f($RS,'nm_etapa'),'')>'') {
      $w_html.=chr(13).'      <tr><td valign="top">Etapa: <b>'.MontaOrdemEtapa(f($RS,'sq_projeto_etapa')).'. '.f($RS,'nm_etapa').' </b></td>';
    } 

    if (nvl(f($RS,'sq_demanda_pai'),'')>'') {
      // Recupera os dados da demanda
      $RS1 = db_getSolicData::getInstanceOf($dbms,f($RS,'sq_demanda_pai'),'GDGERAL');
      $w_html.=chr(13).'      <tr><td valign="top">Atividade pai: <b><A class="HL" HREF="'.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($RS1,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro." target="_blank">'.f($RS1,'sq_siw_solicitacao').'</a> - '.f($RS1,'assunto').' </b></td>';
    } 

    $w_html.=chr(13).'      <tr><td>Detalhamento: <b>'.$w_chave.'<br>'.CRLF2BR(f($RS,'assunto')).'</b></td></tr>';

    // Identificação da demanda
    $w_html.=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Identificação</td>';

    // Se a classificação foi informada, exibe.
    if (nvl(f($RS,'sq_cc'),'')>'') {
      $w_html.=chr(13).'      <tr><td valign="top">Classificação:<br><b>'.f($RS,'cc_nome').' </b></td>';
    } 

    $w_html.=chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
    $w_html.=chr(13).'          <tr valign="top">';
    $w_html.=chr(13).'          <td>Local de execução:<br><b>'.f($RS,'nm_cidade').' ('.f($RS,'co_uf').')</b></td>';
    if (Nvl(f($RS,'proponente'),'')>'') {
      $w_html.=chr(13).'          <td colspan=2>Proponente externo:<br><b>'.f($RS,'proponente').' </b></td>';
    } else {
      $w_html.=chr(13).'          <td colspan=2>Proponente externo:<br><b> --- </b></td>';
    } 

    $w_html.=chr(13).'          <tr valign="top">';
    $w_html.=chr(13).'          <td>Responsável:<br><b>'.ExibePessoa(null,$w_cliente,f($RS,'solicitante'),$TP,f($RS,'nm_sol')).'</b></td>';
    $w_html.=chr(13).'          <td>Unidade responsável:<br><b>'.ExibeUnidade(null,$w_cliente,f($RS,'nm_unidade_resp'),f($RS,'sq_unidade_resp'),$TP).'</b></td>';

    if ($w_tipo_visao==0) {
      // Se for visão completa
      $w_html.=chr(13).'          <td valign="top">Orçamento disponível:<br><b>'.number_format(f($RS,'valor'),2,',','.').' </b></td>';
    } 

    $w_html.=chr(13).'          <tr valign="top">';
    $w_html.=chr(13).'          <td>Início previsto:<br><b>'.FormataDataEdicao(f($RS,'inicio')).' </b></td>';
    $w_html.=chr(13).'          <td>Término previsto:<br><b>'.FormataDataEdicao(f($RS,'fim')).' </b></td>';
    $w_html.=chr(13).'          <td>Prioridade:<br><b>'.RetornaPrioridade(f($RS,'prioridade')).' </b></td>';
    $w_html.=chr(13).'          <tr>';
    $w_html.=chr(13).'          <td colspan=3>Palavras-chave:<br><b>'.f($RS,'palavra_chave').' </b></td>';
    $w_html.=chr(13).'          </table>';

    $RSQuery = db_getSolicList::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,f($RS,'sigla'),4,
            null,null,null,null,null,null,null,null,null,null,null, null, null, null, null, null, null,
            null, null, null, null,null, null, null, f($RS,'sq_siw_solicitacao'), null);
    $RSQuery = SortArray($RSQuery,'fim','asc','prioridade','asc');
    if (count($RSQuery)>0) {
      $w_html.=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Atividades subordinadas</td>';
      $w_html.=chr(13).'      <tr><td><table border=0 with="100%" cellpadding=0 cellspacing=0>';
      $w_html.=chr(13).'        <tr><td align="right"><b>Registros: '.count($RSQuery);
      $w_html.=chr(13).'        <tr><td align="center" colspan=3>';
      $w_html.=chr(13).'          <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
      $w_html.=chr(13).'            <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $w_html.=chr(13).'              <td><b>Nº</td>';
      $w_html.=chr(13).'              <td><b>Etapa</td>';
      $w_html.=chr(13).'              <td><b>Responsável</td>';
      $w_html.=chr(13).'              <td><b>Detalhamento</td>';
      $w_html.=chr(13).'              <td><b>Fim previsto</td>';
      $w_html.=chr(13).'              <td><b>Fase atual</td>';
      $w_html.=chr(13).'            </tr>';
      foreach($RSQuery as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        $w_html.=chr(13).'        <tr bgcolor="'.$w_cor.'" valign="top">';
        $w_html.=chr(13).'          <td nowrap>';
        if (f($row,'concluida')=='N') {
          if (f($row,'fim')<addDays(time(),-1)) {
            $w_html.=chr(13).'             <img src="'.$conImgAtraso.'" border=0 width=15 heigth=15 align="center">';
          } elseif (f($row,'aviso_prox_conc')=='S' && (f($row,'aviso')<=addDays(time(),-1))) {
            $w_html.=chr(13).'             <img src="'.$conImgAviso.'" border=0 width=15 height=15 align="center">';
          } else {
            $w_html.=chr(13).'             <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">';
          } 
        } else {
          if (f($row,'sg_tramite')=='CA') {
            $w_html.=chr(13).'             <img src="'.$conImgCancel.'" border=0 width=15 height=15 align="center">';            
          } elseif (f($row,'fim')<Nvl(f($row,'fim_real'),f($row,'fim'))) {
            $w_html.=chr(13).'             <img src="'.$conImgOkAtraso.'" border=0 width=15 heigth=15 align="center">';
          } else {
            $w_html.=chr(13).'             <img src="'.$conImgOkNormal.'" border=0 width=15 height=15 align="center">';
          } 
        } 
        $w_html.=chr(13).'          <A class="HL" HREF="'.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro." target="_blank">'.f($row,'sq_siw_solicitacao').'&nbsp;</a>';
        if (nvl(f($row,'sq_projeto_etapa'),'nulo')!='nulo') {
          $w_html.=chr(13).'            <td>'.ExibeEtapa('V',f($row,'sq_solic_pai'),f($row,'sq_projeto_etapa'),'Volta',10,MontaOrdemEtapa(f($row,'sq_projeto_etapa')).' - '.f($row,'nm_etapa'),$TP,$SG).'</td>';
        } else {
          $w_html.=chr(13).'            <td>---</td>';
        } 
        $w_html.=chr(13).'          <td>'.ExibePessoa(null,$w_cliente,f($row,'solicitante'),$TP,f($row,'nm_solic')).'</td>';
        if (strlen(Nvl(f($row,'assunto'),'-'))>50) $w_titulo = substr(Nvl(f($row,'assunto'),'-'),0,50).'...'; else $w_titulo = Nvl(f($row,'assunto'),'-');
        $w_html.=chr(13).'          <td title="'.htmlspecialchars(f($row,'assunto')).'">'.htmlspecialchars($w_titulo).'</td>';
        $w_html.=chr(13).'          <td align="center">&nbsp;'.Nvl(FormataDataEdicao(f($row,'fim')),'-').'</td>';
        $w_html.=chr(13).'          <td>'.f($row,'nm_tramite').'</td>';
        $w_html.=chr(13).'        </tr>';
        $w_html.=chr(13).'      </table>';
      } 
    }

    if ($w_tipo_visao==0 || $w_tipo_visao==1) {
      // Informações adicionais
      if (Nvl(f($RS,'descricao'),'')>'' || Nvl(f($RS,'justificativa'),'')>'') {
        $w_html.=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Informações adicionais</td>';
        if (Nvl(f($RS,'descricao'),'')>'') $w_html.=chr(13).'      <tr><td valign="top">Resultados da demanda:<br><b>'.CRLF2BR(f($RS,'descricao')).' </b></td>';
        if ($w_tipo_visao==0 && Nvl(f($RS,'justificativa'),'')>'') {
          // Se for visão completa
          $w_html.=chr(13).'      <tr><td valign="top">Observações:<br><b>'.CRLF2BR(f($RS,'justificativa')).' </b></td>';
        } 
      } 
    } 

    // Dados da conclusão da demanda, se ela estiver nessa situação
    if (f($RS,'concluida')=='S' && Nvl(f($RS,'data_conclusao'),'')>'') {
      $w_html.=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Dados da conclusão</td>';
      $w_html.=chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
      $w_html.=chr(13).'          <tr valign="top">';
      $w_html.=chr(13).'          <td>Início previsto:<br><b>'.FormataDataEdicao(f($RS,'inicio_real')).' </b></td>';
      $w_html.=chr(13).'          <td>Término previsto:<br><b>'.FormataDataEdicao(f($RS,'fim_real')).' </b></td>';
      if ($w_tipo_visao==0) {
        $w_html.=chr(13).'          <td>Custo real:<br><b>'.number_format(f($RS,'custo_real'),2,',','.').' </b></td>';
      } 
      $w_html.=chr(13).'          </table>';
      if ($w_tipo_visao==0) {
        $w_html.=chr(13).'      <tr><td valign="top">Nota de conclusão:<br><b>'.CRLF2BR(f($RS,'nota_conclusao')).' </b></td>';
      } 
    } 
  } 

  // Se for listagem, exibe os outros dados dependendo do tipo de visão  do usuário
  if ($operacao=='L' && $w_tipo_visao!=2) {
    if (f($RS,'aviso_prox_conc')=='S') {
      // Configuração dos alertas de proximidade da data limite para conclusão da demanda
      $w_html.=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Alertas</td>';
      $w_html.=chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
      $w_html.=chr(13).'          <td valign="top">Emite aviso:<br><b>'.str_replace('N','Não',str_replace('S','Sim',f($RS,'aviso_prox_conc'))).' </b></td>';
      $w_html.=chr(13).'          <td valign="top">Dias:<br><b>'.f($RS,'dias_aviso').' </b></td>';
      $w_html.=chr(13).'          </table>';
    } 

    // Interessados na execução da demanda
    $RS = db_getSolicInter::getInstanceOf($dbms,$w_chave,null,'LISTA');
    $RS = SortArray($RS,'nome','asc');
    if (count($RS)>0) {
      $w_html.=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Interessados na execução</td>';
      $w_html.=chr(13).'      <tr><td align="center" colspan="2">';
      $w_html.=chr(13).'        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
      $w_html.=chr(13).'          <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $w_html.=chr(13).'            <td><b>Nome</td>';
      $w_html.=chr(13).'            <td><b>Tipo de visão</td>';
      $w_html.=chr(13).'            <td><b>Envia e-mail</td>';
      $w_html.=chr(13).'          </tr>';
      $w_cor=$conTrBgColor;
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        $w_html.=chr(13).'      <tr valign="top" bgcolor="'.$w_cor.'">';
        $w_html.=chr(13).'        <td>'.ExibePessoa(null,$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'nome').' ('.f($row,'lotacao').')').'</td>';
        $w_html.=chr(13).'        <td>'.RetornaTipoVisao(f($row,'tipo_visao')).'</td>';
        $w_html.=chr(13).'        <td align="center">'.str_replace('N','Não',str_replace('S','Sim',f($row,'envia_email'))).'</td>';
        $w_html.=chr(13).'      </tr>';
      } 
      $w_html.=chr(13).'         </table></td></tr>';
    } 

    // Áreas envolvidas na execução da demanda
    $RS = db_getSolicAreas::getInstanceOf($dbms,$w_chave,null,'LISTA');
    $RS = SortArray($RS,'nome','asc');
    if (count($RS)>0) {
      $w_html.=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Áreas/Instituições envolvidas</td>';
      $w_html.=chr(13).'      <tr><td align="center" colspan="2">';
      $w_html.=chr(13).'        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
      $w_html.=chr(13).'          <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $w_html.=chr(13).'            <td><b>Nome</td>';
      $w_html.=chr(13).'            <td><b>Papel</td>';
      $w_html.=chr(13).'          </tr>';
      $w_cor=$conTrBgColor;
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        $w_html.=chr(13).'      <tr valign="top" bgcolor="'.$w_cor.'">';
        $w_html.=chr(13).'        <td>'.f($row,'nome').'</td>';
        $w_html.=chr(13).'        <td>'.f($row,'papel').'</td>';
        $w_html.=chr(13).'      </tr>';
      } 
      $w_html.=chr(13).'         </table></td></tr>';
    } 
  } 

  if ($operacao=='L' || $operacao=='V') {
    // Se for listagem dos dados
    // Arquivos vinculados
    $RS = db_getSolicAnexo::getInstanceOf($dbms,$w_chave,null,$w_cliente);
    $RS = SortArray($RS,'nome','asc');
    if (count($RS)>0) {
      $w_html.=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Arquivos anexos</td>';
      $w_html.=chr(13).'      <tr><td align="center" colspan="2">';
      $w_html.=chr(13).'        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
      $w_html.=chr(13).'          <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $w_html.=chr(13).'          <td><b>Título</td>';
      $w_html.=chr(13).'          <td><b>Descrição</td>';
      $w_html.=chr(13).'          <td><b>Tipo</td>';
      $w_html.=chr(13).'          <td><b>KB</td>';
      $w_html.=chr(13).'          </tr>';
      $w_cor=$conTrBgColor;
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        $w_html.=chr(13).'      <tr valign="top" bgcolor="'.$w_cor.'">';
        $w_html.=chr(13).'        <td>'.LinkArquivo('HL',$w_cliente,f($row,'chave_aux'),'_blank','Clique para exibir o arquivo em outra janela.',f($row,'nome'),null).'</td>';
        $w_html.=chr(13).'        <td>'.Nvl(f($row,'descricao'),'---').'</td>';
        $w_html.=chr(13).'        <td>'.f($row,'tipo').'</td>';
        $w_html.=chr(13).'        <td align="right">'.round(f($row,'tamanho')/1024,1).'&nbsp;</td>';
        $w_html.=chr(13).'      </tr>';
      } 
      $w_html.=chr(13).'         </table></td></tr>';
    } 

    // Encaminhamentos
    $RS = db_getSolicLog::getInstanceOf($dbms,$w_chave,null,'LISTA');
    $RS = SortArray($RS,'phpdt_data','desc','sq_siw_solic_log','desc');
    $w_html.=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Ocorrências e Anotações</td>';
    $w_html.=chr(13).'      <tr><td align="center" colspan="2">';
    $w_html.=chr(13).'        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
    $w_html.=chr(13).'          <tr bgcolor="'.$conTrBgColor.'" align="center">';
    $w_html.=chr(13).'            <td><b>Data</td>';
    $w_html.=chr(13).'            <td><b>Despacho/Observação</td>';
    $w_html.=chr(13).'            <td><b>Responsável</td>';
    $w_html.=chr(13).'            <td><b>Fase / Destinatário</td>';
    $w_html.=chr(13).'          </tr>';
    if (count($RS)<=0) {
      $w_html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>Não foram encontrados encaminhamentos.</b></td></tr>';
    } else {
      $w_html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'" valign="top">';
      $w_cor=$conTrBgColor;
      $i = 0;
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        if ($i==0) {
          $w_html.=chr(13).'        <td colspan=6>Fase atual: <b>'.f($row,'fase').'</b></td>';
          $i = 1;
        }
        $w_html.=chr(13).'      <tr valign="top" bgcolor="'.$w_cor.'">';
        $w_html.=chr(13).'        <td nowrap>'.FormataDataEdicao(f($row,'phpdt_data'),3).'</td>';
        if (Nvl(f($row,'caminho'),'')>'') {
          $w_html.=chr(13).'        <td>'.CRLF2BR(Nvl(f($row,'despacho'),'---').'<br>'.LinkArquivo('HL',$w_cliente,f($row,'sq_siw_arquivo'),'_blank','Clique para exibir o anexo em outra janela.','Anexo - '.f($row,'tipo').' - '.round(f($row,'tamanho')/1024,1).' KB',null)).'</td>';
        } else {
          $w_html.=chr(13).'        <td>'.CRLF2BR(Nvl(f($row,'despacho'),'---')).'</td>';
        } 
        $w_html.=chr(13).'        <td nowrap>'.ExibePessoa(null,$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'responsavel')).'</td>';
        if (nvl(f($row,'sq_demanda_log'),'')>'' && nvl(f($row,'destinatario'),'')>'') {
          $w_html.=chr(13).'        <td nowrap>'.ExibePessoa(null,$w_cliente,f($row,'sq_pessoa_destinatario'),$TP,f($row,'destinatario')).'</td>';
        } elseif (nvl(f($row,'sq_demanda_log'),'')>'' && nvl(f($row,'destinatario'),'')=='') {
          $w_html.=chr(13).'        <td nowrap>Anotação</td>';
       } else {
          $w_html.=chr(13).'        <td nowrap>'.Nvl(f($row,'tramite'),'---').'</td>';
        } 
        $w_html.=chr(13).'      </tr>';
      } 
    } 
    $w_html.=chr(13).'         </table></td></tr>';
    $w_html.=chr(13).'</table>';
  } 
  return $w_html;
}
?>
