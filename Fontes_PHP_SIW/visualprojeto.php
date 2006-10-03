<?
// =========================================================================
// Rotina de visualização dos dados do projeto
// -------------------------------------------------------------------------
function VisualProjeto($l_chave,$operacao,$l_usuario) {
  extract($GLOBALS);
  include_once($w_dir_volta.'classes/sp/db_getSolicLog.php');
  $w_html='';
  // Verifica se o cliente tem o módulo de acordos contratado
  $RS = db_getSiwCliModLis::getInstanceOf($dbms,$w_cliente,null,'AC');
  if (count($RS)>0) $w_acordo='S'; else $w_acordo='N';

  // Verifica se o cliente tem o módulo de viagens contratado
  $RS = db_getSiwCliModLis::getInstanceOf($dbms,$w_cliente,null,'PD');
  if (count($RS)>0) $w_viagem='S'; else $w_viagem='N';

  // Recupera os dados do projeto
  $RS = db_getSolicData::getInstanceOf($dbms,$l_chave,'PJGERAL');
  // Recupera o tipo de visão do usuário
  if (Nvl(f($RS,'solicitante'),0)==$l_usuario || 
      Nvl(f($RS,'executor'),0)==$l_usuario || 
      Nvl(f($RS,'cadastrador'),0)==$l_usuario || 
      Nvl(f($RS,'titular'),0)==$l_usuario || 
      Nvl(f($RS,'substituto'),0)==$l_usuario || 
      Nvl(f($RS,'tit_exec'),0)==$l_usuario || 
      Nvl(f($RS,'subst_exec'),0)==$l_usuario || 
      SolicAcesso($l_chave,$l_usuario) >= 8) {
    // Se for solicitante, executor ou cadastrador, tem visão completa
    $w_tipo_visao = 0;
  } else {
    $RSQuery = db_getSolicInter::getInstanceOf($dbms,$l_chave,$l_usuario,'REGISTRO');
    if (count($RSquery)>0) {
      // Se for interessado, verifica a visão cadastrada para ele.
      $w_tipo_visao = f($RSquery,'tipo_visao');
    } else {
      $RSQuery = db_getSolicAreas::getInstanceOf($dbms,$l_chave,$_SESSION['LOTACAO'],'REGISTRO');
      if (count($RSquery)>0) {
        // Se for de uma das unidades envolvidas, tem visão parcial
        $w_tipo_visao = 1;
      } else {
        // Caso contrário, tem visão resumida
        $w_tipo_visao = 2;
      } 
      if (SolicAcesso($l_chave,$l_usuario)>2) $w_tipo_visao = 1;
    }  
  }
  // Se for listagem ou envio, exibe os dados de identificação do projeto
  if ($operacao=='L' || $operacao=='V' || $operacao=='T') {
    // Se for listagem dos dados
    $w_html .= chr(13).'<div align=center><center>';
    $w_html .= chr(13).'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
    $w_html .= chr(13).'<tr bgcolor="'.$conTrBgColor.'"><td align="center">';
    $w_html .= chr(13).'    <table width="99%" border="0">';
    $w_html .= chr(13).'      <tr><td>Projeto: <b>'.f($RS,'titulo').' ('.f($RS,'sq_siw_solicitacao').')</b></td>';
    if ($operacao != 'T' && $w_tipo_visao==0) {
      $w_html .= chr(13).'       <td align="right"><b><A class="HL" HREF="projeto.php?par=Visual&O=T&w_chave='.f($RS,'sq_siw_solicitacao').'&w_tipo=volta&P1=&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=GDPCAD" title="Exibe as informações do projeto.">Exibir todas as informações</a></td></tr>';
    } 
    // Identificação do projeto
    $w_html .= chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Identificação</td>';

    // Se a classificação foi informada, exibe.
    if (Nvl(f($RS,'sq_cc'),'')>'') $w_html .= chr(13).'      <tr><td colspan=3>Classificação:<b>'.f($RS,'cc_nome').' </b></td>';

    // Se o acordo foi informado, exibe.
    if (Nvl(f($RS,'cd_acordo'),'')>'') $w_html .= chr(13).'      <tr><td colspan=3>Acordo: <b>'.f($RS,'cd_acordo').' ('.f($RS,'sq_acordo').') '.f($RS,'nm_acordo').' </b></td>';

    $w_html .= chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
    $w_html .= chr(13).'          <tr valign="top">';
    $w_html .= chr(13).'            <td>Cidade de origem:<br><b>'.f($RS,'nm_cidade').' ('.f($RS,'co_uf').")</b></td>";
    $w_html .= chr(13).'          <td colspan=2>Proponente externo:<br><b>'.f($RS,'proponente').' </b></td>';
    $w_html .= chr(13).'          <tr valign="top">';
    $w_html .= chr(13).'          <td>Responsável:<br><b>'.ExibePessoa(null,$w_cliente,f($RS,'solicitante'),$TP,f($RS,'nm_sol')).'</b></td>';
    $w_html .= chr(13).'          <td>Unidade responsável:<br><b>'.ExibeUnidade(null,$w_cliente,f($RS,'nm_unidade_resp'),f($RS,'sq_unidade'),$TP).'</b></td>';

    // Exibe o orçamento disponível para o projeto se for visão completa
    if ($w_tipo_visao==0) $w_html .= chr(13).'          <td>Orçamento disponível:<br><b>'.number_format(f($RS,'valor'),2,',','.').' </b></td>';

    $w_html .= chr(13).'          <tr valign="top">';
    $w_html .= chr(13).'          <td>Data de recebimento:<br><b>'.FormataDataEdicao(f($RS,'inicio')).' </b></td>';
    $w_html .= chr(13).'          <td>Limite para conclusão:<br><b>'.FormataDataEdicao(f($RS,'fim')).' </b></td>';
    $w_html .= chr(13).'          <td>Prioridade:<br><b>'.RetornaPrioridade(f($RS,'prioridade')).' </b></td>';
    if ($w_tipo_visao==0 || $w_tipo_visao==1) {
      // Informações adicionais
      if (Nvl(f($RS,'descricao'),'') > '' || Nvl(f($RS,'justificativa'),'') > '' || $w_acordo == 'S' || $w_viagem=='S') {
        $w_html .= chr(13).'      <tr><td colspan=3 align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);\"><b>Informações adicionais</td>';
        if (Nvl(f($RS,'descricao'),'') > '') {
        $w_html .= chr(13).'      <tr><td colspan=3>Resultados do projeto:<br><b>'.CRLF2BR(f($RS,'descricao')).' </b></td>';
        }
        if ($w_tipo_visao==0 && Nvl(f($RS,'justificativa'),'') > '') {
          // Se for visão completa
          $w_html .= chr(13).'      <tr><td colspan=3>Recomendações superiores:<br><b>'.CRLF2BR(f($RS,'justificativa')).' </b></td>';
        } 
        if ($w_acordo=='S' || $w_viagem=='S') {
          $w_html .= chr(13).'          <tr valign="top">';
          if ($w_acordo=='S') {
            if (f($RS,'vincula_contrato')=='S') $w_html .= chr(13).'<td>Permite a vinculação de contratos:<br><b>Sim</b>';
            else $w_html .= chr(13).'<td>Permite a vinculação de contratos:<br><b>Não</b>';
          }
          if ($w_viagem=='S') {
            if (f($RS,'vincula_viagem')=='S') $w_html .= chr(13).'<td>Permite a vinculação de viagens:<br><b>Sim</b>';
            else $w_html .= chr(13).'<td>Permite a vinculação de viagens:<br><b>Não</b>';
          }
        }
      }
    } 
    $w_html .= chr(13).'          </table>';
    // Dados da conclusão do projeto, se ela estiver nessa situação
    if (f($RS,'concluida')=='S' && Nvl(f($RS,'data_conclusao'),'') > '') {
      $w_html .= chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Dados da conclusão</td>';
      $w_html .= chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
      $w_html .= chr(13).'          <tr valign="top">';
      $w_html .= chr(13).'          <td>Início da execução:<br><b>'.FormataDataEdicao(f($RS,'inicio_real')).' </b></td>';
      $w_html .= chr(13).'          <td>Término da execução:<br><b>'.FormataDataEdicao(f($RS,'fim_real')).' </b></td>';
      if ($w_tipo_visao==0) $w_html .= chr(13).'          <td>Custo real:<br><b>'.number_format(f($RS,'custo_real'),2,',','.').' </b></td>';
      $w_html .= chr(13).'          </table>';
      if ($w_tipo_visao==0) $w_html .= chr(13).'      <tr><td valign="top">Nota de conclusão:<br><b>'.CRLF2BR(f($RS,'nota_conclusao')).' </b></td>';
    }
  }
  // Se for listagem, exibe os outros dados dependendo do tipo de visão  do usuário
  if (($operacao=='L' && $w_tipo_visao!=2) || ($operacao=='T' && $w_tipo_visao!=2)) {
    if (f($RS,'aviso_prox_conc')=='S') {
      // Configuração dos alertas de proximidade da data limite para conclusão da demanda
      $w_html .= chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);\"><b>Alertas</td>';
      $w_html .= chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
      $w_html .= chr(13).'          <td valign="top">Emite aviso:<br><b>'.str_replace('N','Não',str_replace('S','Sim',f($RS,'aviso_prox_conc'))).' </b></td>';
      $w_html .= chr(13).'          <td valign="top">Dias:<br><b>'.f($RS,'dias_aviso').' </b></td>';
      $w_html .= chr(13).'          </table>';
    } 
    // Interessados na execução do projeto
    $RS = db_getSolicInter::getInstanceOf($dbms,$l_chave,null,'LISTA');
    $RS = SortArray($RS,'nome','asc');
    if (count($RS)>0) {
      $w_html .= chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Interessados na execução</td>';
      $w_html .= chr(13).'      <tr><td align="center" colspan="2">';
      $w_html .= chr(13).'        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
      $w_html .= chr(13).'          <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $w_html .= chr(13).'            <td><b>Nome</td>';
      $w_html .= chr(13).'            <td><b>Tipo de visão</td>';
      $w_html .= chr(13).'            <td><b>Envia e-mail</td>';
      $w_html .= chr(13).'          </tr>';
      $w_cor=$conTrBgColor;
      foreach ($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor; 
        $w_html .= chr(13).'      <tr valign="top" bgcolor="'.$w_cor.'">';
        $w_html .= chr(13).'        <td>'.ExibePessoa(null,$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'nome').' ('.f($row,'lotacao').')').'</td>';
        $w_html .= chr(13).'        <td>'.RetornaTipoVisao(f($row,'tipo_visao')).'</td>';
        $w_html .= chr(13).'        <td align="center">'.str_replace('N','Não',str_replace('S','Sim',f($row,'envia_email'))).'</td>';
        $w_html .= chr(13).'      </tr>';
      } 
      $w_html .= chr(13).'         </table></td></tr>';
    } 
    // Áreas envolvidas na execução do projeto
    $RS = db_getSolicAreas::getInstanceOf($dbms,$l_chave,null,'LISTA');
    $RS = SortArray($RS,'nome','asc');
    if (count($RS)>0) {
      $w_html .= chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Áreas/Instituições envolvidas</td>';
      $w_html .= chr(13).'      <tr><td align="center" colspan="2">';
      $w_html .= chr(13).'        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
      $w_html .= chr(13).'          <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $w_html .= chr(13).'            <td><b>Nome</td>';
      $w_html .= chr(13).'            <td><b>Papel</td>';
      $w_html .= chr(13).'          </tr>';
      $w_cor=$conTrBgColor;
      foreach ($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor; 
        $w_html .= chr(13).'      <tr valign="top" bgcolor="'.$w_cor.'">';
        $w_html .= chr(13).'        <td>'.f($row,'nome').'</td>';
        $w_html .= chr(13).'        <td>'.f($row,'papel').'</td>';
        $w_html .= chr(13).'      </tr>';
      } 
      $w_html .= chr(13).'         </table></td></tr>';
    }
    //Lista das atividades que não são ligadas a nenhuma etapa
    if ($operacao=='T') {
      $RS = db_getSolicList::getInstanceOf($dbms,$w_menu,$l_usuario,'GDPCADET',3,
             null,null,null,null,null,null,null,null,null,null,null,null,null,null,
             null,null,null,null,null,null,null,null,$l_chave,null,null,null);
      if (count($RS)>0) {
        $w_html .= chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Atividades não ligadas a etapas</td>';
        $w_html .= chr(13).'      <tr><td align="center" colspan="2">';
        $w_html .= chr(13).'        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
        $w_html .= chr(13).'          <tr bgcolor="'.$conTrBgColor.'" align="center">';
        $w_html .= chr(13).'            <td rowspan=2><b>Nº</td>';
        $w_html .= chr(13).'            <td rowspan=2><b>Detalhamento</td>';
        $w_html .= chr(13).'            <td rowspan=2><b>Responsável</td>';
        $w_html .= chr(13).'            <td rowspan=2><b>Setor</td>';
        $w_html .= chr(13).'            <td colspan=2><b>Execução</td>';
        $w_html .= chr(13).'            <td rowspan=2><b>Conc.</td>';
        $w_html .= chr(13).'            <td rowspan=2><b>Ativ.</td>';
        $w_html .= chr(13).'          </tr>';
        $w_html .= chr(13).'          <tr bgcolor="'.$conTrBgColor.'" align="center">';
        $w_html .= chr(13).'            <td><b>De</td>';
        $w_html .= chr(13).'            <td><b>Até</td>';
        $w_html .= chr(13).'          </tr>';
        foreach ($RS as $row) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor; 
          $w_html .= chr(13).'      <tr valign="top" bgcolor="'.$w_cor.'"><td>';
          if (f($row,'concluida')=='N'){
            if (f($row,'fim')<addDays(time(),-1))
              $w_html .= chr(13).'   <img src="'.$conImgAtraso.'" border=0 width=15 heigth=15 align="center">';
            elseif (f($row,'aviso_prox_conc')=='S' && (f($row,'aviso')<=addDays(time(),-1)))
              $w_html .= chr(13).'   <img src="'.$conImgAviso.'" border=0 width=15 height=15 align="center">';
            else
              $w_html .= chr(13).'   <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">';
          } else {
            if (f($row,'fim')<Nvl(f($row,'fim_real'),f($row,'fim')))
              $w_html .= chr(13).'   <img src="'.$conImgOkAtraso.'" border=0 width=15 heigth=15 align="center">';
            else
              $w_html .= chr(13).'   <img src="'.$conImgOkNormal.'" border=0 width=15 height=15 align="center">';
          } 
          $w_html .= chr(13).'  <A class="HL" HREF="projetoativ.php?par=Visual&R=ProjetoAtiv.php?par=Visual&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro." target="blank">'.f($row,'sq_siw_solicitacao').'</a>';
          $w_html .= chr(13).'     <td>'.Nvl(f($row,'assunto'),'-');
          $w_html .= chr(13).'     <td>'.ExibePessoa(null,$w_cliente,f($row,'solicitante'),$TP,f($row,'nm_resp')).'</td>';
          $w_html .= chr(13).'     <td>'.f($row,'sg_unidade_resp').'</td>';
          $w_html .= chr(13).'     <td align="center">'.Nvl(FormataDataEdicao(f($row,'inicio')),'-').'</td>';
          $w_html .= chr(13).'     <td align="center">'.Nvl(FormataDataEdicao(f($row,'fim')),'-').'</td>';
          $w_html .= chr(13).'     <td colspan=2 nowrap>'.f($row,'nm_tramite').'</td>';
        } 
        $w_html .= chr(13).'      </td></tr></table>';
      } 
    } 
    // Etapas do projeto
    // Recupera todos os registros para a listagem
    $RS = db_getSolicEtapa::getInstanceOf($dbms,$l_chave,null,'LISTA',null);
    $RS = SortArray($RS,'ordem','asc');
    // Recupera o código da opção de menu  a ser usada para listar as atividades
    $w_p2 = '';
    if (count($RS)>0) {
      foreach ($RS as $row) {
        if (Nvl(f($row,'P2'),0) > 0) $w_p2 = f($row,'P2');
        break;
      } 
      reset($RS);
    } 
    $RS = db_getSolicEtapa::getInstanceOf($dbms,$l_chave,null,'LSTNULL',null);
    $RS = SortArray($RS,'ordem','asc');
    if (count($RS)>0) {
      // Se não foram selecionados registros, exibe mensagem
      // Monta função JAVASCRIPT para fazer a chamada para a lista de atividades
      if ($w_p2 > '') {
        $w_html .= chr(13).'<SCRIPT LANGUAGE="JAVASCRIPT">';
        $w_html .= chr(13).'  function lista (projeto, etapa) {';
        $w_html .= chr(13).'    document.Form.p_projeto.value=projeto;';
        $w_html .= chr(13).'    document.Form.p_atividade.value=etapa;';
        $w_html .= chr(13).'    document.Form.p_agrega.value=\'GRDMETAPA\';';
        $RS1 = db_getTramiteList::getInstanceOf($dbms,$w_p2,null,null);
        $RS1 = SortArray($RS1,'ordem','asc');
        $w_html .= chr(13).'    document.Form.p_fase.value=\'\';';
        $w_fases='';
        foreach($RS1 as $row1) {
          if (f($row1,'sigla')!='CA') $w_fases=$w_fases.','.f($row1,'sq_siw_tramite');
        } 
        $w_html .= chr(13).'    document.Form.p_fase.value=\''.substr($w_fases,1,100).'\';';
        $w_html .= chr(13).'    document.Form.submit();';
        $w_html .= chr(13).'  }';
        $w_html .= chr(13).'</SCRIPT>';
      }
      $RS1 = db_getMenuData::getInstanceOf($dbms,$w_p2);
      AbreForm('Form',f($RS1,'link'),'POST','return(Validacao(this));','Atividades',3,$w_p2,1,null,$w_TP,f($RS1,'sigla'),$w_pagina.$par,'L');
      $w_html .= chr(13).'<input type="Hidden" name="p_projeto" value="">';
      $w_html .= chr(13).'<input type="Hidden" name="p_atividade" value="">';
      $w_html .= chr(13).'<input type="Hidden" name="p_agrega" value="">';
      $w_html .= chr(13).'<input type="Hidden" name="p_fase" value="">';
    }
    if(count($RS)>0) {
      $RS1 = db_getSolicData::getInstanceOf($dbms,$l_chave,'PJGERAL');
      $w_html .= chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Etapas</td>';
      $w_html .= chr(13).'      <tr><td align="center" colspan="2">';
      $w_html .= chr(13).'        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
      $w_html .= chr(13).'          <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $w_html .= chr(13).'          <td rowspan=2><b>Etapa</td>';
      $w_html .= chr(13).'          <td rowspan=2><b>Título</td>';
      $w_html .= chr(13).'          <td rowspan=2><b>Responsável</td>';
      $w_html .= chr(13).'          <td rowspan=2><b>Setor</td>';
      $w_html .= chr(13).'          <td colspan=2><b>Execução</td>';
      $w_html .= chr(13).'          <td rowspan=2><b>Conc.</td>';
      $w_html .= chr(13).'          <td rowspan=2><b>Ativ.</td>';
      if(f($RS1,'vincula_contrato')=='S') $w_html .= chr(13).'          <td rowspan=2><b>Contr.</td>';
      $w_html .= chr(13).'          </tr>';
      $w_html .= chr(13).'          <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $w_html .= chr(13).'          <td><b>De</td>';
      $w_html .= chr(13).'          <td><b>Até</td>';
      $w_html .= chr(13).'          </tr>';
      //Se for visualização normal, irá visualizar somente as etapas
      if ($operacao=='L') {
        foreach($RS as $row) {
          $w_html .= chr(13).EtapaLinha($l_chave,f($row,'sq_projeto_etapa'),f($row,'titulo'),f($row,'nm_resp'),f($row,'sg_setor'),f($row,'inicio_previsto'),f($row,'fim_previsto'),f($row,'perc_conclusao'),f($row,'qt_ativ'),'<b>',null,'PROJETO',f($row,'sq_pessoa'),f($row,'sq_unidade'),f($row,'pj_vincula_contrato'),f($row,'qt_contr'));
          // Recupera as etapas vinculadas ao nível acima
          $RS1 = db_getSolicEtapa::getInstanceOf($dbms,$l_chave,f($row,'sq_projeto_etapa'),'LSTNIVEL',null);
          $RS1 = SortArray($RS1,'ordem','asc');
          foreach($RS1 as $row1) {
           $w_html .= chr(13).EtapaLinha($l_chave,f($row1,'sq_projeto_etapa'),f($row1,'titulo'),f($row1,'nm_resp'),f($row1,'sg_setor'),f($row1,'inicio_previsto'),f($row1,'fim_previsto'),f($row1,'perc_conclusao'),f($row1,'qt_ativ'),null,null,'PROJETO',f($row1,'sq_pessoa'),f($row1,'sq_unidade'),f($row1,'pj_vincula_contrato'),f($row1,'qt_contr'));
             // Recupera as etapas vinculadas ao nível acima
            $RS2 = db_getSolicEtapa::getInstanceOf($dbms,$l_chave,f($row1,'sq_projeto_etapa'),'LSTNIVEL',null);
            $RS2 = SortArray($RS2,'ordem','asc');
            foreach($RS2 as $row2) {
              $w_html .= chr(13).EtapaLinha($l_chave,f($row2,'sq_projeto_etapa'),f($row2,'titulo'),f($row2,'nm_resp'),f($row2,'sg_setor'),f($row2,'inicio_previsto'),f($row2,'fim_previsto'),f($row2,'perc_conclusao'),f($row2,'qt_ativ'),null,null,'PROJETO',f($row2,'sq_pessoa'),f($row2,'sq_unidade'),f($row2,'pj_vincula_contrato'),f($row2,'qt_contr'));
              // Recupera as etapas vinculadas ao nível acima
              $RS3 = db_getSolicEtapa::getInstanceOf($dbms,$l_chave,f($row2,'sq_projeto_etapa'),'LSTNIVEL',null);
              $RS3 = SortArray($RS3,'ordem','asc');
              foreach($RS3 as $row3) {
                $w_html .= chr(13).EtapaLinha($l_chave,f($row3,'sq_projeto_etapa'),f($row3,'titulo'),f($row3,'nm_resp'),f($row3,'sg_setor'),f($row3,'inicio_previsto'),f($row3,'fim_previsto'),f($row3,'perc_conclusao'),f($row3,'qt_ativ'),null,null,'PROJETO',f($row3,'sq_pessoa'),f($row3,'sq_unidade'),f($row3,'pj_vincula_contrato'),f($row3,'qt_contr'));
                // Recupera as etapas vinculadas ao nível acima
                $RS4 = db_getSolicEtapa::getInstanceOf($dbms,$l_chave,f($row3,'sq_projeto_etapa'),'LSTNIVEL',null);
                $RS4 = SortArray($RS4,'ordem','asc');
                foreach($RS4 as $row4) {
                  $w_html .= chr(13).EtapaLinha($l_chave,f($row4,'sq_projeto_etapa'),f($row4,'titulo'),f($row4,'nm_resp'),f($row4,'sg_setor'),f($row4,'inicio_previsto'),f($row4,'fim_previsto'),f($row4,'perc_conclusao'),f($row4,'qt_ativ'),null,null,'PROJETO',f($row4,'sq_pessoa'),f($row4,'sq_unidade'),f($row4,'pj_vincula_contrato'),f($row4,'qt_contr'));
                } 
              } 
            } 
          } 
        } 
      }elseif ($operacao=='T'){
        //Se for visualização total, ira visualizar as etapas e as atividades correspondentes
        foreach($RS as $row) {
          $w_html .= chr(13).EtapaLinhaAtiv($l_chave,f($row,'sq_projeto_etapa'),f($row,'titulo'),f($row,'nm_resp'),f($row,'sg_setor'),f($row,'inicio_previsto'),f($row,'fim_previsto'),f($row,'perc_conclusao'),f($row,'qt_ativ'),'<b>',null,'PROJETO','RESUMIDO',f($row,'sq_pessoa'),f($row,'sq_unidade'),f($row,'pj_vincula_contrato'),f($row,'qt_contr'));
          // Recupera as etapas vinculadas ao nível acima
          $RS1 = db_getSolicEtapa::getInstanceOf($dbms,$l_chave,f($row,'sq_projeto_etapa'),'LSTNIVEL',null);
          $RS1 = SortArray($RS1,'ordem','asc');
          foreach($RS1 as $row1) {
            $w_html .= chr(13).EtapaLinhaAtiv($l_chave,f($row1,'sq_projeto_etapa'),f($row1,'titulo'),f($row1,'nm_resp'),f($row1,'sg_setor'),f($row1,'inicio_previsto'),f($row1,'fim_previsto'),f($row1,'perc_conclusao'),f($row1,'qt_ativ'),null,null,'PROJETO','RESUMIDO',f($row1,'sq_pessoa'),f($row1,'sq_unidade'),f($row1,'pj_vincula_contrato'),f($row1,'qt_contr'));
            // Recupera as etapas vinculadas ao nível acima
            $RS2 = db_getSolicEtapa::getInstanceOf($dbms,$l_chave,f($row1,'sq_projeto_etapa'),'LSTNIVEL',null);
            $RS2 = SortArray($RS2,'ordem','asc');
            foreach($RS2 as $row2) {
              $w_html .= chr(13).EtapaLinhaAtiv($l_chave,f($row2,'sq_projeto_etapa'),f($row2,'titulo'),f($row2,'nm_resp'),f($row2,'sg_setor'),f($row2,'inicio_previsto'),f($row2,'fim_previsto'),f($row2,'perc_conclusao'),f($row2,'qt_ativ'),null,null,'PROJETO','RESUMIDO',f($row2,'sq_pessoa'),f($row2,'sq_unidade'),f($row2,'pj_vincula_contrato'),f($row2,'qt_contr'));
              // Recupera as etapas vinculadas ao nível acima
              $RS3 = db_getSolicEtapa::getInstanceOf($dbms,$l_chave,f($row2,'sq_projeto_etapa'),'LSTNIVEL',null);
              $RS3 = SortArray($RS3,'ordem','asc');
              foreach($RS3 as $row3) {
                $w_html .= chr(13).EtapaLinhaAtiv($l_chave,f($row3,'sq_projeto_etapa'),f($row3,'titulo'),f($row3,'nm_resp'),f($row3,'sg_setor'),f($row3,'inicio_previsto'),f($row3,'fim_previsto'),f($row3,'perc_conclusao'),f($row3,'qt_ativ'),null,null,'PROJETO','RESUMIDO',f($row3,'sq_pessoa'),f($row3,'sq_unidade'),f($row3,'pj_vincula_contrato'),f($row3,'qt_contr'));
                // Recupera as etapas vinculadas ao nível acima
                $RS4 = db_getSolicEtapa::getInstanceOf($dbms,$l_chave,f($row3,'sq_projeto_etapa'),'LSTNIVEL',null);
                $RS4 = SortArray($RS4,'ordem','asc');
                foreach($RS4 as $row4) {
                  $w_html .= chr(13).EtapaLinhaAtiv($l_chave,f($row4,'sq_projeto_etapa'),f($row4,'titulo'),f($row4,'nm_resp'),f($row4,'sg_setor'),f($row4,'inicio_previsto'),f($row4,'fim_previsto'),f($row4,'perc_conclusao'),f($row4,'qt_ativ'),null,null,'PROJETO','RESUMIDO',f($row4,'sq_pessoa'),f($row4,'sq_unidade'),f($row4,'pj_vincula_contrato'),f($row4,'qt_contr'));
                } 
              } 
            } 
          } 
        } 
      } 
      $w_html .= chr(13).'      </form>';
      $w_html .= chr(13).'      </center>';
      $w_html .= chr(13).'         </table></td></tr>';
    }
    // Recursos envolvidos na execução do projeto
    $RS = db_getSolicRecurso::getInstanceOf($dbms,$l_chave,null,'LISTA');
    $RS = SortArray($RS,'tipo','asc','nome','asc');
    if (count($RS)>0) {
      $w_html .= chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Recursos</td>';
      $w_html .= chr(13).'      <tr><td align="center" colspan="2">';
      $w_html .= chr(13).'        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
      $w_html .= chr(13).'          <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $w_html .= chr(13).'            <td><b>Tipo</td>';
      $w_html .= chr(13).'            <td><b>Nome</td>';
      $w_html .= chr(13).'            <td><b>Finalidade</td>';
      $w_html .= chr(13).'          </tr>';
      $w_cor=$conTrBgColor;
      foreach ($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor; 
        $w_html .= chr(13).'      <tr valign="top" bgcolor="'.$w_cor.'">';
        $w_html .= chr(13).'        <td>'.RetornaTipoRecurso(f($row,'tipo')).'</td>';
        $w_html .= chr(13).'        <td>'.f($row,'nome').'</td>';
        $w_html .= chr(13).'        <td>'.CRLF2BR(Nvl(f($row,'finalidade'),'---')).'</td>';
        $w_html .= chr(13).'      </tr>';
      } 
      $w_html .= chr(13).'         </table></td></tr>';
    }     
  }
  if ($operacao=='L' || $operacao=='V' || $operacao=='T') {
    // Se for listagem dos dados
    if($w_tipo_visao!=2) {
      // Arquivos vinculados
      $RS = db_getSolicAnexo::getInstanceOf($dbms,$l_chave,null,$w_cliente);
      $RS = SortArray($RS,'nome','asc');
      if (count($RS)>0) {
        $w_html .= chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Arquivos anexos</td>';
        $w_html .= chr(13).'      <tr><td align="center" colspan="2">';
        $w_html .= chr(13).'        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
        $w_html .= chr(13).'          <tr bgcolor="'.$conTrBgColor.'" align="center">';
        $w_html .= chr(13).'          <td><b>Título</td>';
        $w_html .= chr(13).'          <td><b>Descrição</td>';
        $w_html .= chr(13).'          <td><b>Tipo</td>';
        $w_html .= chr(13).'          <td><b>KB</td>';
        $w_html .= chr(13).'          </tr>';
        $w_cor=$conTrBgColor;
        foreach ($RS as $row) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor; 
          $w_html .= chr(13).'      <tr valign="top" bgcolor="'.$w_cor.'">';
          $w_html .= chr(13).'        <td>'.LinkArquivo('HL',$w_cliente,f($row,'chave_aux'),'_blank','Clique para exibir o arquivo em outra janela.',f($row,'nome'),null).'</td>';
          $w_html .= chr(13).'        <td>'.Nvl(f($row,'descricao'),'---').'</td>';
          $w_html .= chr(13).'        <td>'.f($row,'tipo').'</td>';
          $w_html .= chr(13).'        <td align="right">'.round(f($row,'tamanho')/1024,1).'&nbsp;</td>';
          $w_html .= chr(13).'      </tr>';
        } 
        $w_html .= chr(13).'         </table></td></tr>';
      } 
    }
    // Encaminhamentos
    $RS = db_getSolicLog::getInstanceOf($dbms,$l_chave,null,'LISTA');
    $RS = SortArray($RS,'phpdt_data','desc','sq_siw_solic_log','desc');
    $w_html .= chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Ocorrências e Anotações</td>';
    $w_html .= chr(13).'      <tr><td align="center" colspan="2">';
    $w_html .= chr(13).'        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
    $w_html .= chr(13).'          <tr bgcolor="'.$conTrBgColor.'" align="center">';
    $w_html .= chr(13).'            <td><b>Data</td>';
    $w_html .= chr(13).'            <td><b>Despacho/Observação</td>';
    $w_html .= chr(13).'            <td><b>Responsável</td>';
    $w_html .= chr(13).'            <td><b>Fase / Destinatário</td>';
    $w_html .= chr(13).'          </tr>';
    if (count($RS)==0) {
      $w_html .= chr(13).'      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>Não foram encontrados encaminhamentos.</b></td></tr>';
    } else {
      $w_html .= chr(13).'      <tr bgcolor="'.$conTrBgColor.'" valign="top">';
      $w_cor=$conTrBgColor;
      $i = 0;
      foreach ($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        if ($i==0) {
          $w_html .= chr(13).'        <td colspan=6>Fase atual: <b>'.f($row,'fase').'</b></td>';
          $i = 1;
        }
        $w_html = $w_html.chr(13).'      <tr valign="top" bgcolor="'.$w_cor.'">';
        $w_html .= chr(13).'        <td nowrap>'.FormataDataEdicao(f($row,'phpdt_data'),3).'</td>';
        if (Nvl(f($row,'caminho'),'')>'') $w_html .= chr(13).'        <td>'.CRLF2BR(Nvl(f($row,'despacho'),'---').'<br>'.LinkArquivo('HL',$w_cliente,f($row,'sq_siw_arquivo'),'_blank','Clique para exibir o anexo em outra janela.','Anexo - '.f($row,'tipo').' - '.round(f($row,'tamanho')/1024,1).' KB',null)).'</td>';
        else                              $w_html .= chr(13).'        <td>'.CRLF2BR(Nvl(f($row,'despacho'),'---')).'</td>';
        $w_html .= chr(13).'        <td nowrap>'.ExibePessoa(null,$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'responsavel')).'</td>';
        if ((Nvl(f($row,'sq_projeto_log'),'')>'') && (Nvl(f($row,'destinatario'),'')>''))      $w_html .= chr(13).'        <td nowrap>'.ExibePessoa(null,$w_cliente,f($row,'sq_pessoa_destinatario'),$TP,f($row,'destinatario')).'</td>';
        elseif ((Nvl(f($row,'sq_projeto_log'),'')>'')  && (Nvl(f($row,'destinatario'),'')==''))$w_html .= chr(13).'        <td nowrap>Anotação</td>';
        else                                                                                   $w_html .= chr(13).'        <td nowrap>'.Nvl(f($row,'tramite'),'---').'</td>';
        $w_html .= chr(13).'      </tr>';
      } 
      $w_html .= chr(13).'         </table></td></tr>';
    } 
    $w_html .= chr(13).'</table>';
  } 
  return $w_html;
} 
?>