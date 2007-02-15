<?
// =========================================================================
// Rotina de visualização dos dados do projeto
// -------------------------------------------------------------------------
function VisualProjeto($l_chave,$operacao,$l_usuario) {
  extract($GLOBALS);
  include_once($w_dir_volta.'classes/sp/db_getLinkSubMenu.php');
  include_once($w_dir_volta.'classes/sp/db_getSolicIndicador.php');
  include_once($w_dir_volta.'classes/sp/db_getSolicRecursos.php');
  include_once($w_dir_volta.'classes/sp/db_getSolicMeta.php');
  include_once($w_dir_volta.'classes/sp/db_getSolicLog.php');
  
  //Recupera as informações do sub-menu
  $RS = db_getLinkSubMenu::getInstanceOf($dbms, $w_cliente, f($RS_Menu,'sigla'));
  foreach ($RS as $row) {
    if     (strpos(f($row,'sigla'),'ANEXO')!==false)    $l_nome_menu['PJANEXO'] = strtoupper(f($row,'nome'));
    elseif (strpos(f($row,'sigla'),'AREAS')!==false)    $l_nome_menu['PJAREAS'] = strtoupper(f($row,'nome'));
    elseif (strpos(f($row,'sigla'),'GERAL')!==false)    $l_nome_menu['PJGERAL'] = strtoupper(f($row,'nome'));
    elseif (strpos(f($row,'sigla'),'QUALIT')!==false)   $l_nome_menu['PJQUALIT'] = strtoupper(f($row,'nome'));
    elseif (strpos(f($row,'sigla'),'ETAPA')!==false)    $l_nome_menu['PJETAPA'] = strtoupper(f($row,'nome'));
    elseif (strpos(f($row,'sigla'),'INTERES')!==false)  $l_nome_menu['PJINTERES'] = strtoupper(f($row,'nome'));
    elseif (strpos(f($row,'sigla'),'RECURSO')!==false)  $l_nome_menu['PJRECURSO'] = strtoupper(f($row,'nome'));
    elseif (strpos(f($row,'sigla'),'RUBRICA')!==false)  $l_nome_menu['PJRUBRICA'] = strtoupper(f($row,'nome'));
    else $l_nome_menu[f($row,'sigla')] = strtoupper(f($row,'nome'));
  }
  $w_html='';
  // Verifica se o cliente tem o módulo de acordos contratado
  $RS = db_getSiwCliModLis::getInstanceOf($dbms,$w_cliente,null,'AC');
  if (count($RS)>0) $w_acordo='S'; else $w_acordo='N';

  // Verifica se o cliente tem o módulo de viagens contratado
  $RS = db_getSiwCliModLis::getInstanceOf($dbms,$w_cliente,null,'PD');
  if (count($RS)>0) $w_viagem='S'; else $w_viagem='N';

  // Verifica se o cliente tem o módulo planejamento estratégico
  $RS = db_getSiwCliModLis::getInstanceOf($dbms,$w_cliente,null,'IS');
  if (count($RS)>0) $w_acao='S'; else $w_acao='N';

  // Recupera os dados do projeto
  $RS = db_getSolicData::getInstanceOf($dbms,$l_chave,'PJGERAL');
  // Recupera o tipo de visão do usuário
  if ($_SESSION['INTERNO']=='N') {
    // Se for usuário externa, tem visão resumida
    $w_tipo_visao=2;
  } elseif (Nvl(f($RS,'solicitante'),0)==$l_usuario || 
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
    if ($operacao != 'T' && $w_tipo_visao==0) $w_html .= chr(13).'       <td align="right"><b><A class="HL" HREF="projeto.php?par=Visual&O=T&w_chave='.f($RS,'sq_siw_solicitacao').'&w_tipo=volta&P1=&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe as informações do projeto.">Exibir todas as informações</a></td></tr>';
    $w_html.=chr(13).'    <table width="99%" border="0">';
    $w_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    if (nvl(f($RS,'sq_peobjetivo'),'')!='') {
      $w_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>PLANO ESTRATÉGICO: '.f($RS,'nm_plano').'</b></font></div></td></tr>';
      $w_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>OBJETIVO: '.f($RS,'nm_objetivo').'</b></font></div></td></tr>';
    }
    $w_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>PROJETO: '.f($RS,'titulo').' ('.f($RS,'sq_siw_solicitacao').')</b></font></div></td></tr>';
    $w_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
     
    // Identificação do projeto
    $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>'.$l_nome_menu['PJGERAL'].'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';

    // Se a classificação foi informada, exibe.
    if (Nvl(f($RS,'sq_cc'),'')>'') {
      $w_html .= chr(13).'      <tr><td width="30%"><b>Classificação:<b></td>';
      $w_html .= chr(13).'        <td>'.f($RS,'cc_nome').' </td></tr>';
    }
    
    // Se o acordo foi informado, exibe.
    if (Nvl(f($RS,'sq_acordo'),'')>'') {
      if (substr(f($RS,'sg_acordo'),0,3)=='GCC') {
        $w_html.=chr(13).'      <tr><td width="30%"><font size=1><b>Convênio: <b></td>';
        $w_html .= chr(13).'        <td><A class="hl" HREF="mod_ac/convenios.php?par=Visual&O=L&w_chave='.f($RS,'sq_acordo').'&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=GCCCAD" title="Exibe as informações do acordo." target="_blank">'.f($RS,'cd_acordo').' ('.f($RS,'sq_acordo').') '.f($RS,'nm_acordo').'</a></b></font></td></tr>';
      } else {
        $w_html.=chr(13).'      <tr><td width="30%"><font size=1><b>Contrato: <b></td>';
        $w_html .= chr(13).'        <td><A class="hl" HREF="mod_ac/contratos.php?par=Visual&O=L&w_chave='.f($RS,'sq_acordo').'&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=GCCCAD" title="Exibe as informações do acordo." target="_blank">'.f($RS,'cd_acordo').' ('.f($RS,'sq_acordo').') '.f($RS,'nm_acordo').'</a></b></font></td></tr>';
      }
    } elseif (Nvl(f($RS,'sq_programa'),'')>'') {
      $w_html.=chr(13).'      <tr><td width="30%"><font size=1><b>Programa: <b></td>';
      $w_html .= chr(13).'        <td><A class="hl" HREF="mod_pe/programa.php?par=Visual&O=L&w_chave='.f($RS,'sq_programa').'&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=PEPROCAD" title="Exibe as informações do programa." target="_blank">'.f($RS,'cd_programa').' - '.f($RS,'nm_programa').'</a></b></font></td></tr>';
    } else {
      if (Nvl(f($RS,'sq_solic_pai'),'')>'') {
        $RS1 = db_getSolicData_IS::getInstanceOf($dbms,f($RS,'sq_solic_pai'),'ISACGERAL');
        foreach($RS1 as $row1) {$RS1=$row1; break;}
        $w_html .= chr(13).'      <tr><td colspan=3>Ação: <b>'.f($RS1,'cd_unidade').'.'.f($RS1,'cd_programa').'.'.f($RS1,'cd_acao').' - '.f($RS1,'nm_ppa').'</b></td>';
      }
    }
    $w_html .= chr(13).'      <tr><td valign="top" colspan="2">';
    $w_html .= chr(13).'          <tr><td width="30%"><b>Local de execução:</b></td><td>'.f($RS,'nm_cidade').' ('.f($RS,'co_uf').")</b></td>";
    $w_html .= chr(13).'          <tr><td><b>Proponente externo:<b></td>';
    $w_html .= chr(13).'        <td>'.nvl(f($RS,'proponente'),'---').' </b></td>';
    $w_html .= chr(13).'          <tr><td><b>Responsável:<b></td>';
    $w_html .= chr(13).'        <td>'.ExibePessoa(null,$w_cliente,f($RS,'solicitante'),$TP,f($RS,'nm_sol')).'</b></td>';
    $w_html .= chr(13).'          <tr><td><b>Unidade responsável:</b></td>';
    $w_html .= chr(13).'        <td>'.ExibeUnidade(null,$w_cliente,f($RS,'nm_unidade_resp'),f($RS,'sq_unidade'),$TP).'</b></td>';

    // Exibe o orçamento disponível para o projeto se for visão completa
    if ($w_tipo_visao==0) { 
      $w_html .= chr(13).'    <tr><td><b>Orçamento disponível:</b></td>';
      $w_html .= chr(13).'      <td>'.number_format(f($RS,'valor'),2,',','.').' </td></tr>';
    }
    $w_html .= chr(13).'      <tr><td><b>Início previsto:</b></td>';
    $w_html .= chr(13).'        <td>'.FormataDataEdicao(f($RS,'inicio')).' </td></tr>';
    $w_html .= chr(13).'      <tr><td><b>Término previsto:</b></td>';
    $w_html .= chr(13).'        <td>'.FormataDataEdicao(f($RS,'fim')).' </td></tr>';
    $w_html .= chr(13).'      <tr><td><b>Prioridade:</b></td>';
    $w_html .= chr(13).'        <td>'.RetornaPrioridade(f($RS,'prioridade')).' </td></tr>';
    
    
    // Informações adicionais
    if (Nvl(f($RS,'descricao'),'') > '' || Nvl(f($RS,'justificativa'),'') > '' || $w_acordo == 'S' || $w_viagem=='S') {
      if ($w_tipo_visao!=2) {
        if ($w_acordo=='S' || $w_viagem=='S') {
          $w_html.=chr(13).'    <tr><td colspan=3><br><font size="2"><b>INFORMAÇÕES ADICIONAIS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
          if ($w_acordo=='S') {
            if (f($RS,'vincula_contrato')=='S') {
              $w_html .= chr(13).'<tr><td><b>Permite a vinculação de contratos:</b></td>';
              $w_html .= chr(13).'  <td>Sim</td></tr>';
            } else {
              $w_html .= chr(13).'<tr><td><b>Permite a vinculação de contratos:</b></td>';
              $w_html .= chr(13).'  <td>Não</td></tr>';
            }
          }
          if ($w_viagem=='S') {
            if (f($RS,'vincula_viagem')=='S') { 
              $w_html .= chr(13).'<tr><td><b>Permite a vinculação de viagens:</b></td>';
              $w_html .= chr(13).'  <td>Sim</td></tr>';
            } else {
              $w_html .= chr(13).'<tr><td><b>Permite a vinculação de viagens:</b></td>';
              $w_html .= chr(13).'  <td>Não</td></tr>';
            }
          }
        }
      }
    } 

    // Programação qualitativa
    if ($operacao=='T' && $l_nome_menu['PJQUALIT']!='') {
      $w_html.=chr(13).'    <tr><td colspan=3><br><font size="2"><b>'.$l_nome_menu['PJQUALIT'].'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      // Se for visão completa
      $w_html .= chr(13).'<tr valign="top"><td><b>Objetivo Superior:</b></td>';
      $w_html .= chr(13).'  <td>'.Nvl(CRLF2BR(f($RS,'objetivo_superior')),'---').' </td></tr>';
      $w_html .= chr(13).'<tr valign="top"><td><b>Objetivos Específicos:</b></td>';
      $w_html .= chr(13).'  <td>'.Nvl(CRLF2BR(f($RS,'descricao')),'---').' </td></tr>';
      $w_html .= chr(13).'<tr valign="top"><td><b>Exclusões Específicas:</b></td>';
      $w_html .= chr(13).'  <td>'.Nvl(CRLF2BR(f($RS,'exclusoes')),'---').' </td></tr>';
      $w_html .= chr(13).'<tr valign="top"><td><b>Premissas:</b></td>';
      $w_html .= chr(13).'  <td>'.Nvl(CRLF2BR(f($RS,'premissas')),'---').' </td></tr>';
      $w_html .= chr(13).'<tr valign="top"><td><b>Restricões:</b></td>';
      $w_html .= chr(13).'  <td>'.Nvl(CRLF2BR(f($RS,'restricoes')),'---').' </td></tr>';
      $w_html .= chr(13).'<tr valign="top"><td><b>Observações:</b></td>';
      $w_html .= chr(13).'  <td>'.Nvl(CRLF2BR(f($RS,'justificativa')),'---').' </td></tr>';
    } 

    // Indicadores
    if ($operacao=='T' && $l_nome_menu['INDSOLIC']!='') {
      $RS = db_getSolicIndicador::getInstanceOf($dbms,$l_chave,null,null,null);
      $RS = SortArray($RS,'nm_tipo_indicador','asc','nome','asc');
      if (count($RS)>0) {
        $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>'.$l_nome_menu['INDSOLIC'].'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
        $w_html .= chr(13).'      <tr><td align="center" colspan="2">';
        $w_html.=chr(13).'          <table width=100%  border="1" bordercolor="#00000">';
        $w_html .= chr(13).'          <tr><td bgColor="#f0f0f0" width="5%" nowrap><div align="center"><b>Tipo</b></div></td>';
        $w_html .= chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>Indicador</b></div></td>';
        $w_html .= chr(13).'          </tr>';
        $w_cor=$conTrBgColor;
        foreach ($RS as $row) {
          $w_html .= chr(13).'      <tr>';
          $w_html .= chr(13).'        <td nowrap>'.f($row,'nm_tipo_indicador').'</td>';
          $w_html .= chr(13).'        <td><A class="HL" HREF="javascript:this.status.value;" onClick="window.open(\''.$conRootSIW.'mod_pe/indicador.php?par=FramesAfericao&R='.$w_pagina.$par.'&O=L&w_troca=p_base&p_tipo_indicador='.f($row,'sq_tipo_indicador').'&p_indicador='.f($row,'chave').'&p_pesquisa=BASE&p_volta=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\',\'Afericao\',\'width=730,height=500,top=30,left=30,status=no,resizable=yes,scrollbars=yes,toolbar=no\');" title="Exibe informaçoes sobre o indicador.">'.f($row,'nome').'</a></td></td>';
          $w_html .= chr(13).'      </tr>';
        } 
        $w_html .= chr(13).'         </table></td></tr>';
      }

      // Metas
      $RS = db_getSolicMeta::getInstanceOf($dbms,$w_cliente,$l_usuario,$l_chave,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
      $RS = SortArray($RS,'ordem','asc','titulo','asc');
      if (count($RS)>0 && $l_nome_menu['METASOLIC']!='') {
        $w_html .= chr(13).'      <tr><td colspan="2"><br><font size="2"><b>'.$l_nome_menu['METASOLIC'].'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
        $w_html .= chr(13).'      <tr><td align="center" colspan="2">';
        $w_html .= chr(13).'          <table width=100%  border="1" bordercolor="#00000">';     
        $w_html .= chr(13).'          <tr align="center" valign="top" bgColor="#f0f0f0">';
        $w_html .= chr(13).'            <td><b>Meta</b></td>';
        $w_html .= chr(13).'            <td><b>Início</b></td>';
        $w_html .= chr(13).'            <td><b>Fim</b></td>';
        $w_html .= chr(13).'            <td><b>Indicador</b></td>';
        $w_html .= chr(13).'            <td><b>Base</b></td>';
        $w_html .= chr(13).'            <td><b>Valor a ser alcançado</b></td>';
        $w_html .= chr(13).'            <td width="1%" nowrap><b>U.M.</b></td>';
        $w_html .= chr(13).'          </tr>';
        $w_cor=$conTrBgColor;
        foreach ($RS as $row) {
          $w_html .= chr(13).'      <tr>';
          $w_html .= chr(13).'        <td>'.f($row,'titulo').'</td>';
          $w_html .= chr(13).'        <td align="center">'.date(d.'/'.m.'/'.y,f($row,'inicio')).'</td>';
          $w_html .= chr(13).'        <td align="center">'.date(d.'/'.m.'/'.y,f($row,'fim')).'</td>';
          $w_html .= chr(13).'        <td>'.f($row,'nm_indicador').'</td>';
          $w_html .= chr(13).'        <td>'.f($row,'nm_base_geografica').'</td>';
          $w_html .= chr(13).'        <td align="right">'.formatNumber(f($row,'quantidade'),4).'</td>';
          $w_html .= chr(13).'        <td align="center">'.f($row,'sg_unidade_medida').'</td>';        
          $w_html .= chr(13).'      </tr>';
        } 
        $w_html .= chr(13).'         </table></td></tr>';
        $w_html .= chr(13).'<tr><td colspan=3><table border=0>';
        $w_html .= chr(13).'  <tr><td align="right">U.M.<td>Unidade de medida do indicador';
        $w_html .= chr(13).'  </table>';
      }

      // Recursos
      $RS = db_getSolicRecursos::getInstanceOf($dbms,$w_cliente,$w_usuario,$l_chave,null,null,null,null,null,null,null,null,null,null,null);
      $RS = SortArray($RS,'nm_tipo_recurso','asc','nm_recurso','asc'); 
      if (count($RS)>0 && $l_nome_menu['RECSOLIC']!='') {
        $w_html .= chr(13).'      <tr><td colspan="2"><br><font size="2"><b>'.$l_nome_menu['RECSOLIC'].'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
        $w_html .= chr(13).'      <tr><td align="center" colspan="2">';
        $w_html .= chr(13).'          <table width=100%  border="1" bordercolor="#00000">';     
        $w_html .= chr(13).'          <tr align="center" valign="top" bgColor="#f0f0f0">';
        $w_html .= chr(13).'            <td><b>Tipo</b></td>';
        $w_html .= chr(13).'            <td><b>Código</b></td>';
        $w_html .= chr(13).'            <td><b>Recurso</b></td>';
        $w_html .= chr(13).'            <td width="1%" nowrap><b>U.M.</b></td>';
        $w_html .= chr(13).'          </tr>';
        $w_cor=$conTrBgColor;
        foreach ($RS as $row) {
          $w_html .= chr(13).'      <tr>';
          $w_html .= chr(13).'        <td>'.f($row,'nm_tipo_completo').'</td>';
          $w_html .= chr(13).'        <td>'.nvl(f($row,'codigo'),'---').'</td>';
          $w_html .= chr(13).'        <td>'.ExibeRecurso($w_dir_volta,$w_cliente,f($row,'nm_recurso'),f($row,'sq_recurso'),$TP).'</td>';
          $w_html .= chr(13).'        <td align="center" nowrap>'.f($row,'nm_unidade_medida').'</td>';        
          $w_html .= chr(13).'      </tr>';
        } 
        $w_html .= chr(13).'         </table></td></tr>';
        $w_html .= chr(13).'<tr><td colspan=3><table border=0>';
        $w_html .= chr(13).'  <tr><td align="right">U.M.<td>Unidade de medida do indicador';
        $w_html .= chr(13).'  </table>';
      }
    } 

    // Dados da conclusão do projeto, se ela estiver nessa situação
    if (f($RS,'concluida')=='S' && Nvl(f($RS,'data_conclusao'),'') > '') {
      $w_html .= chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS DA CONCLUSÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $w_html .= chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
      $w_html .= chr(13).'      <tr><td width="30%"><b>Início previsto:</b></td>';
      $w_html .= chr(13).'        <td>'.FormataDataEdicao(f($RS,'inicio_real')).' </td></tr>';
      $w_html .= chr(13).'      <tr><td><b>Término previsto:</b></td>';
      $w_html .= chr(13).'        <td>'.FormataDataEdicao(f($RS,'fim_real')).' </td></tr>';
      if ($w_tipo_visao==0) { 
        $w_html .= chr(13).'    <tr><td><b>Custo real:</b></td>';
        $w_html .= chr(13).'      <td>'.number_format(f($RS,'custo_real'),2,',','.').' </td></tr>';
      }
      $w_html .= chr(13).'          </table>';
      if ($w_tipo_visao==0) { 
        $w_html .= chr(13).'    <tr><td valign="top"><b>Nota de conclusão:</b></td>';
        $w_html .= chr(13).'      <td>'.CRLF2BR(f($RS,'nota_conclusao')).' </td></tr>';
      }
    }
  } 

  // Se for listagem, exibe os outros dados dependendo do tipo de visão  do usuário
  if ($w_tipo_visao!=2 && ($operacao=='L' || $operacao=='T')) {
    if (f($RS,'aviso_prox_conc')=='S') {
      // Configuração dos alertas de proximidade da data limite para conclusão da demanda
      $w_html.=chr(13).'        <tr><td colspan="2"><br><font size="2"><b>ALERTA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $w_html .= chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
      if (f($RS,'aviso_prox_conc')=='N') {
        $w_html .= chr(13).'      <tr><td width="30%"><b>Emite aviso:</b></td>';
        $w_html .= chr(13).'        <td>'.retornaSimNao(f($RS,'aviso_prox_conc')).' </td></tr>';
      } else {
        $w_html .= chr(13).'      <tr><td width="30%"><b>Emite aviso:</b></td>';
        $w_html .= chr(13).'        <td>'.retornaSimNao(f($RS,'aviso_prox_conc')).', a partir de '.formataDataEdicao(f($RS,'aviso')).'.</td></tr>';
      }
      $w_html .= chr(13).'          </table>';
    } 
  }

  // Rubricas do projeto
  $RS = db_getSolicRubrica::getInstanceOf($dbms,$l_chave,null,null,null,null,null);
  $RS = SortArray($RS,'codigo','asc');
  if (count($RS)>0 && $l_nome_menu['PJRUBRICA']!='') {
    $w_html.=chr(13).'        <tr><td colspan=2><br><font size="2"><b>'.$l_nome_menu['PJRUBRICA'].'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $w_html .= chr(13).'      <tr><td align="center" colspan="2">';
    $w_html .= chr(13).'          <table width=100%  border="1" bordercolor="#00000">';
    $w_html .= chr(13).'          <tr><td rowspan="2" bgColor="#f0f0f0"><div><b>Código</td>';
    $w_html .= chr(13).'            <td rowspan="2" bgColor="#f0f0f0"><div><b>Nome</td>';
    $w_html .= chr(13).'            <td rowspan="2" bgColor="#f0f0f0"><div><b>Valor Inicial</td>';
    $w_html .= chr(13).'            <td colspan="3" bgcolor="'.$conTrBgColorLightBlue1.'" align="center"><b>Entrada</td>';
    $w_html .= chr(13).'            <td colspan="3" bgcolor="'.$conTrBgColorLightRed1.'" align="center"><b>Saída</td>';
    $w_html .= chr(13).'          </tr>';
    $w_html .= chr(13).'          <tr bgcolor="'.$conTrAlternateBgColor.'" align="center">';
    $w_html .= chr(13).'            <td bgcolor="'.$conTrBgColorLightBlue1.'"><b>Prevista</td>';
    $w_html .= chr(13).'            <td bgcolor="'.$conTrBgColorLightBlue1.'"><b>Real</td>';
    $w_html .= chr(13).'            <td bgcolor="'.$conTrBgColorLightBlue1.'"><b>Pendente</td>';
    $w_html .= chr(13).'            <td bgcolor="'.$conTrBgColorLightRed1.'"><b>Prevista</td>';
    $w_html .= chr(13).'            <td bgcolor="'.$conTrBgColorLightRed1.'"><b>Real</td>';
    $w_html .= chr(13).'            <td bgcolor="'.$conTrBgColorLightRed1.'"><b>Pendente</td>';
    $w_html .= chr(13).'          </tr>';      
    $w_cor=$conTrBgColor;
    $w_valor_inicial    = 0;
    $w_entrada_prevista = 0;
    $w_entrada_real     = 0;
    $w_entrada_pendente = 0;
    $w_saida_prevista   = 0;
    $w_saida_real       = 0;
    $w_saida_pendente   = 0;
    foreach ($RS as $row) {
      if ($w_cor==$conTrBgColor || $w_cor=='')  {
        $w_cor      = $conTrAlternateBgColor;
        $w_cor_blue = $conTrBgColorLightBlue1;
        $w_cor_red  = $conTrBgColorLightRed1;
      } else {
        $w_cor      = $conTrBgColor;
        $w_cor_blue = $conTrBgColorLightBlue2;
        $w_cor_red  = $conTrBgColorLightRed2;
      }
      $w_html .= chr(13).'      <tr>';
      $w_html .= chr(13).'          <td><A class="hl" HREF="javascript:location.href=this.location.href;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.'mod_fn/lancamento.php?par=Ficharubrica&O=L&w_sq_projeto_rubrica='.f($row,'sq_projeto_rubrica').'&w_tipo=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Extrato Rubrica'.'&SG='.$SG.MontaFiltro('GET')).'\',\'Ficha3\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Exibe as informações deste registro.">'.f($row,'codigo').'</A>&nbsp';
      $w_html .= chr(13).'          <td>'.f($row,'nome').' </td>';
      $w_html .= chr(13).'          <td align="right">'.number_format(f($row,'valor_inicial'),2,',','.').' </td>';
      $w_html .= chr(13).'          <td align="right" bgcolor="'.$w_cor_blue.'">'.number_format(f($row,'entrada_prevista'),2,',','.').' </td>';
      $w_html .= chr(13).'          <td align="right" bgcolor="'.$w_cor_blue.'">'.number_format(f($row,'entrada_real'),2,',','.').' </td>';
      $w_html .= chr(13).'          <td align="right" bgcolor="'.$w_cor_blue.'">'.number_format(f($row,'entrada_pendente'),2,',','.').' </td>';
      $w_html .= chr(13).'          <td align="right" bgcolor="'.$w_cor_red.'">'.number_format(f($row,'saida_prevista'),2,',','.').' </td>';
      $w_html .= chr(13).'          <td align="right" bgcolor="'.$w_cor_red.'">'.number_format(f($row,'saida_real'),2,',','.').' </td>';
      $w_html .= chr(13).'          <td align="right" bgcolor="'.$w_cor_red.'">'.number_format(f($row,'saida_pendente'),2,',','.').' </td>';
      $w_html .= chr(13).'      </tr>';
      $w_valor_inicial    += f($row,'valor_inicial');
      $w_entrada_prevista += f($row,'entrada_prevista');
      $w_entrada_real     += f($row,'entrada_real');
      $w_entrada_pendente += f($row,'entrada_pendente');
      $w_saida_prevista   += f($row,'saida_prevista');
      $w_saida_real       += f($row,'saida_real');
      $w_saida_pendente   += f($row,'saida_pendente');
    } 
    $w_html .= chr(13).'      <tr>';
    $w_html .= chr(13).'          <td align="right" colspan="2"><b>Total</td>';
    $w_html .= chr(13).'          <td align="right"><b>'.number_format($w_valor_inicial,2,',','.').' </b></td>';
    $w_html .= chr(13).'          <td align="right" bgcolor="'.$conTrBgColorLightBlue1.'"><b>'.number_format($w_entrada_prevista,2,',','.').' </b></td>';
    $w_html .= chr(13).'          <td align="right" bgcolor="'.$conTrBgColorLightBlue1.'"><b>'.number_format($w_entrada_real,2,',','.').' </b></td>';
    $w_html .= chr(13).'          <td align="right" bgcolor="'.$conTrBgColorLightBlue1.'"><b>'.number_format($w_entrada_pendente,2,',','.').' </b></td>';
    $w_html .= chr(13).'          <td align="right" bgcolor="'.$conTrBgColorLightRed1.'"><b>'.number_format($w_saida_prevista,2,',','.').' </b></td>';
    $w_html .= chr(13).'          <td align="right" bgcolor="'.$conTrBgColorLightRed1.'"><b>'.number_format($w_saida_real,2,',','.').' </b></td>';
    $w_html .= chr(13).'          <td align="right" bgcolor="'.$conTrBgColorLightRed1.'"><b>'.number_format($w_saida_pendente,2,',','.').' </b></td>';
    $w_html .= chr(13).'      </tr>';
    $w_html .= chr(13).'         </table></td></tr>';
  }

  if ($w_tipo_visao!=2 && ($operacao=='L' || $operacao=='T')) {
    // Interessados na execução do projeto
    $RS = db_getSolicInter::getInstanceOf($dbms,$l_chave,null,'LISTA');
    $RS = SortArray($RS,'nome','asc');
    if (count($RS)>0 && $l_nome_menu['PJINTERESS']!='') {
      $w_html.=chr(13).'        <tr><td colspan="2"><br><font size="2"><b>'.$l_nome_menu['PJINTERESS'].'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $w_html .= chr(13).'      <tr><td align="center" colspan="2">';
      $w_html.=chr(13).'          <table width=100%  border="1" bordercolor="#00000">';
      $w_html .= chr(13).'          <tr><td bgColor="#f0f0f0"><div><b>Nome</b></div></td>';
      $w_html .= chr(13).'            <td bgColor="#f0f0f0"><div><b>Tipo de visão</b></div></td>';
      $w_html .= chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>Envia e-mail</b></div></td>';
      $w_html .= chr(13).'          </tr>';
      $w_cor=$conTrBgColor;
      foreach ($RS as $row) {
        $w_html .= chr(13).'      <tr>';
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
    if (count($RS)>0 && $l_nome_menu['PJAREAS']!='') {
      $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>'.$l_nome_menu['PJAREAS'].'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $w_html .= chr(13).'      <tr><td align="center" colspan="2">';
      $w_html.=chr(13).'          <table width=100%  border="1" bordercolor="#00000">';
      $w_html .= chr(13).'          <tr><td bgColor="#f0f0f0"><div align="center"><b>Nome</b></div></td>';
      $w_html .= chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>Papel</b></div></td>';
      $w_html .= chr(13).'          </tr>';
      $w_cor=$conTrBgColor;
      foreach ($RS as $row) {
        $w_html .= chr(13).'      <tr>';
        $w_html .= chr(13).'        <td>'.f($row,'nome').'</td>';
        $w_html .= chr(13).'        <td>'.f($row,'papel').'</td>';
        $w_html .= chr(13).'      </tr>';
      } 
      $w_html .= chr(13).'         </table></td></tr>';
    }
  }
  
  //Lista das atividades que não são ligadas a nenhuma etapa
  if ($operacao=='T') {
    $RS = db_getSolicList::getInstanceOf($dbms,$w_menu,$l_usuario,'GDPCADET',3,
           null,null,null,null,null,null,null,null,null,null,null,null,null,null,
           null,null,null,null,null,null,null,null,$l_chave,null,null,null);
    if (count($RS)>0) {
      $w_html .= chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ATIVIDADES NÃO LIGADAS A '.$l_nome_menu['PJETAPA'].'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $w_html .= chr(13).'      <tr><td align="center" colspan="2">';
      $w_html .= chr(13).'          <table width=100%  border="1" bordercolor="#00000">';
      $w_html .= chr(13).'            <tr><td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Nº</td>';
      $w_html .= chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Detalhamento</td>';
      $w_html .= chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Responsável</td>';
      $w_html .= chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Setor</td>';
      $w_html .= chr(13).'            <td colspan=2 bgColor="#f0f0f0"><div align="center"><b>Execução</td>';
      $w_html .= chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Orçamento</td>';
      $w_html .= chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Conc.</td>';
      $w_html .= chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Ativ.</td>';
      $w_html .= chr(13).'          </tr>';
      $w_html .= chr(13).'          <tr>';
      $w_html .= chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>De</td>';
      $w_html .= chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>Até</td>';
      $w_html .= chr(13).'          </tr>';
      foreach ($RS as $row) {
        $w_html .= chr(13).'      <tr><td>';
        if (f($row,'concluida')=='N'){
          if (f($row,'fim')<addDays(time(),-1))
            $w_html .= chr(13).'   <img src="'.$conImgAtraso.'" border=0 width=15 heigth=15 align="center">';
          elseif (f($row,'aviso_prox_conc')=='S' && (f($row,'aviso')<=addDays(time(),-1)))
            $w_html .= chr(13).'   <img src="'.$conImgAviso.'" border=0 width=15 height=15 align="center">';
          else
            $w_html .= chr(13).'   <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">';
        } else {
          if (f($row,'sg_tramite')=='CA') {
            ShowHTML('           <img src="'.$conImgCancel.'" border=0 width=15 height=15 align="center">');            
          } elseif (f($row,'fim')<Nvl(f($row,'fim_real'),f($row,'fim')))
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
  $w_p3 = '';
  if (count($RS)>0) {
    foreach ($RS as $row) {
      if (Nvl(f($row,'P2'),0) > 0) $w_p2 = f($row,'P2');
      if (Nvl(f($row,'P3'),0) > 0) $w_p3 = f($row,'P3');
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
      $w_html .= chr(13).'    document.Form1.p_projeto.value=projeto;';
      $w_html .= chr(13).'    document.Form1.p_atividade.value=etapa;';
      $RS1 = db_getMenuData::getInstanceOf($dbms,$w_p2);
      $w_html .= chr(13).'    document.Form1.action=\''.f($RS1,'link').'\';';
      $w_html .= chr(13).'    document.Form1.P2.value=\''.$w_p2.'\';';
      $w_html .= chr(13).'    document.Form1.SG.value=\''.f($RS1,'sigla').'\';';        
      $w_html .= chr(13).'    document.Form1.p_agrega.value=\'GRDMETAPA\';';
      $RS1 = db_getTramiteList::getInstanceOf($dbms,$w_p2,null,null);
      $RS1 = SortArray($RS1,'ordem','asc');
      $w_html .= chr(13).'    document.Form1.p_fase.value=\'\';';
      $w_fases='';
      foreach($RS1 as $row1) {
        if (f($row1,'sigla')!='CA') $w_fases=$w_fases.','.f($row1,'sq_siw_tramite');
      } 
      $w_html .= chr(13).'    document.Form1.p_fase.value=\''.substr($w_fases,1,100).'\';';
      $w_html .= chr(13).'    document.Form1.submit();';
      $w_html .= chr(13).'  }';
      $w_html .= chr(13).'</SCRIPT>';
    }
    // Monta função JAVASCRIPT para fazer a chamada para a lista de contratos
    if ($w_p3 > '') {
      $w_html .= chr(13).'<SCRIPT LANGUAGE="JAVASCRIPT">';
      $w_html .= chr(13).'  function listac (projeto, etapa) {';
      $w_html .= chr(13).'    document.Form1.p_projeto.value=projeto;';
      $w_html .= chr(13).'    document.Form1.p_atividade.value=etapa;';
      $RS1 = db_getMenuData::getInstanceOf($dbms,$w_p3);
      $w_html .= chr(13).'    document.Form1.action=\''.f($RS1,'link').'\';';
      $w_html .= chr(13).'    document.Form1.P2.value=\''.$w_p3.'\';';
      $w_html .= chr(13).'    document.Form1.SG.value=\''.f($RS1,'sigla').'\';';
      $w_html .= chr(13).'    document.Form1.p_agrega.value=\''.substr(f($RS1,'sigla'),0,3).'ETAPA\';';
      $RS1 = db_getTramiteList::getInstanceOf($dbms,$w_p3,null,null);
      $RS1 = SortArray($RS1,'ordem','asc');
      $w_html .= chr(13).'    document.Form1.p_fase.value=\'\';';
      $w_fases='';
      foreach($RS1 as $row1) {
        if (f($row1,'sigla')!='CA') $w_fases=$w_fases.','.f($row1,'sq_siw_tramite');
      } 
      $w_html .= chr(13).'    document.Form1.p_fase.value=\''.substr($w_fases,1,100).'\';';
      $w_html .= chr(13).'    document.Form1.submit();';
      $w_html .= chr(13).'  }';
      $w_html .= chr(13).'</SCRIPT>';
    }      
    $RS1 = db_getMenuData::getInstanceOf($dbms,$w_p2);
    AbreForm('Form1',f($RS1,'link'),'POST',null,'Lista',3,$w_p2,1,null,RemoveTP($w_TP),f($RS1,'sigla'),$w_pagina.$par,'L');
    $w_html .= chr(13).'<input type="Hidden" name="p_projeto" value="">';
    $w_html .= chr(13).'<input type="Hidden" name="p_atividade" value="">';
    $w_html .= chr(13).'<input type="Hidden" name="p_agrega" value="">';
    $w_html .= chr(13).'<input type="Hidden" name="p_fase" value="">';
  }
  if(count($RS)>0 && $l_nome_menu['PJETAPA']!='') {
    $RS1 = db_getSolicData::getInstanceOf($dbms,$l_chave,'PJGERAL');
    $w_html .=chr(13).'        <tr><td colspan=2><br><font size="2"><b>'.$l_nome_menu['PJETAPA'].'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $w_html .= chr(13).'      <tr><td align="center" colspan="2">';
    $w_html .=chr(13).'          <table width=100%  border="1" bordercolor="#00000">';
    $w_html .= chr(13).'          <tr><td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Etapa</b></div></td>';
    $w_html .= chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Título</b></div></td>';
    $w_html .= chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Responsável</b></div></td>';
    $w_html .= chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Setor</b></div></td>';
    $w_html .= chr(13).'            <td colspan=2 bgColor="#f0f0f0"><div align="center"><b>Execução</b></div></td>';
    $w_html .= chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Orçamento</b></div></td>';
    $w_html .= chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Conc.</b></div></td>';
    $w_html .= chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Ativ.</b></div></td>';
    if(f($RS1,'vincula_contrato')=='S') $w_html .= chr(13).'          <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Contr.</b></div></td>';
    $w_html .= chr(13).'          </tr>';
    $w_html .= chr(13).'          <tr><td bgColor="#f0f0f0"><div align="center"><b>De</b></div></td>';
    $w_html .= chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>Até</b></div></td>';
    $w_html .= chr(13).'          </tr>';
    //Se for visualização normal, irá visualizar somente as etapas
    if ($operacao=='L' || $operacao=='V') {
      foreach($RS as $row) {
        $w_html .= chr(13).EtapaLinha($l_chave,f($row,'sq_projeto_etapa'),f($row,'titulo'),f($row,'nm_resp'),f($row,'sg_setor'),f($row,'inicio_previsto'),f($row,'fim_previsto'),f($row,'perc_conclusao'),f($row,'qt_ativ'),'<b>',null,'PROJETO',f($row,'sq_pessoa'),f($row,'sq_unidade'),f($row,'pj_vincula_contrato'),f($row,'qt_contr'),f($row,'orcamento'),0);
        // Recupera as etapas vinculadas ao nível acima
        $RS1 = db_getSolicEtapa::getInstanceOf($dbms,$l_chave,f($row,'sq_projeto_etapa'),'LSTNIVEL',null);
        $RS1 = SortArray($RS1,'ordem','asc');
        foreach($RS1 as $row1) {
         $w_html .= chr(13).EtapaLinha($l_chave,f($row1,'sq_projeto_etapa'),f($row1,'titulo'),f($row1,'nm_resp'),f($row1,'sg_setor'),f($row1,'inicio_previsto'),f($row1,'fim_previsto'),f($row1,'perc_conclusao'),f($row1,'qt_ativ'),null,null,'PROJETO',f($row1,'sq_pessoa'),f($row1,'sq_unidade'),f($row1,'pj_vincula_contrato'),f($row1,'qt_contr'),f($row1,'orcamento'),1);
           // Recupera as etapas vinculadas ao nível acima
          $RS2 = db_getSolicEtapa::getInstanceOf($dbms,$l_chave,f($row1,'sq_projeto_etapa'),'LSTNIVEL',null);
          $RS2 = SortArray($RS2,'ordem','asc');
          foreach($RS2 as $row2) {
            $w_html .= chr(13).EtapaLinha($l_chave,f($row2,'sq_projeto_etapa'),f($row2,'titulo'),f($row2,'nm_resp'),f($row2,'sg_setor'),f($row2,'inicio_previsto'),f($row2,'fim_previsto'),f($row2,'perc_conclusao'),f($row2,'qt_ativ'),null,null,'PROJETO',f($row2,'sq_pessoa'),f($row2,'sq_unidade'),f($row2,'pj_vincula_contrato'),f($row2,'qt_contr'),f($row2,'orcamento'),2);
            // Recupera as etapas vinculadas ao nível acima
            $RS3 = db_getSolicEtapa::getInstanceOf($dbms,$l_chave,f($row2,'sq_projeto_etapa'),'LSTNIVEL',null);
            $RS3 = SortArray($RS3,'ordem','asc');
            foreach($RS3 as $row3) {
              $w_html .= chr(13).EtapaLinha($l_chave,f($row3,'sq_projeto_etapa'),f($row3,'titulo'),f($row3,'nm_resp'),f($row3,'sg_setor'),f($row3,'inicio_previsto'),f($row3,'fim_previsto'),f($row3,'perc_conclusao'),f($row3,'qt_ativ'),null,null,'PROJETO',f($row3,'sq_pessoa'),f($row3,'sq_unidade'),f($row3,'pj_vincula_contrato'),f($row3,'qt_contr'),f($row3,'orcamento'),3);
              // Recupera as etapas vinculadas ao nível acima
              $RS4 = db_getSolicEtapa::getInstanceOf($dbms,$l_chave,f($row3,'sq_projeto_etapa'),'LSTNIVEL',null);
              $RS4 = SortArray($RS4,'ordem','asc');
              foreach($RS4 as $row4) {
                $w_html .= chr(13).EtapaLinha($l_chave,f($row4,'sq_projeto_etapa'),f($row4,'titulo'),f($row4,'nm_resp'),f($row4,'sg_setor'),f($row4,'inicio_previsto'),f($row4,'fim_previsto'),f($row4,'perc_conclusao'),f($row4,'qt_ativ'),null,null,'PROJETO',f($row4,'sq_pessoa'),f($row4,'sq_unidade'),f($row4,'pj_vincula_contrato'),f($row4,'qt_contr'),f($row4,'orcamento'),4);
              } 
            } 
          } 
        } 
      } 
    } elseif ($w_tipo_visao!=2 && ($operacao=='T')){
      //Se for visualização total, ira visualizar as etapas e as atividades correspondentes
      foreach($RS as $row) {
        $w_html .= chr(13).EtapaLinhaAtiv($l_chave,f($row,'sq_projeto_etapa'),f($row,'titulo'),f($row,'nm_resp'),f($row,'sg_setor'),f($row,'inicio_previsto'),f($row,'fim_previsto'),f($row,'perc_conclusao'),f($row,'qt_ativ'),'<b>',null,'PROJETO','RESUMIDO',f($row,'sq_pessoa'),f($row,'sq_unidade'),f($row,'pj_vincula_contrato'),f($row,'qt_contr'),f($row,'orcamento'),0);
        // Recupera as etapas vinculadas ao nível acima
        $RS1 = db_getSolicEtapa::getInstanceOf($dbms,$l_chave,f($row,'sq_projeto_etapa'),'LSTNIVEL',null);
        $RS1 = SortArray($RS1,'ordem','asc');
        foreach($RS1 as $row1) {
          $w_html .= chr(13).EtapaLinhaAtiv($l_chave,f($row1,'sq_projeto_etapa'),f($row1,'titulo'),f($row1,'nm_resp'),f($row1,'sg_setor'),f($row1,'inicio_previsto'),f($row1,'fim_previsto'),f($row1,'perc_conclusao'),f($row1,'qt_ativ'),null,null,'PROJETO','RESUMIDO',f($row1,'sq_pessoa'),f($row1,'sq_unidade'),f($row1,'pj_vincula_contrato'),f($row1,'qt_contr'),f($row1,'orcamento'),1);
          // Recupera as etapas vinculadas ao nível acima
          $RS2 = db_getSolicEtapa::getInstanceOf($dbms,$l_chave,f($row1,'sq_projeto_etapa'),'LSTNIVEL',null);
          $RS2 = SortArray($RS2,'ordem','asc');
          foreach($RS2 as $row2) {
            $w_html .= chr(13).EtapaLinhaAtiv($l_chave,f($row2,'sq_projeto_etapa'),f($row2,'titulo'),f($row2,'nm_resp'),f($row2,'sg_setor'),f($row2,'inicio_previsto'),f($row2,'fim_previsto'),f($row2,'perc_conclusao'),f($row2,'qt_ativ'),null,null,'PROJETO','RESUMIDO',f($row2,'sq_pessoa'),f($row2,'sq_unidade'),f($row2,'pj_vincula_contrato'),f($row2,'qt_contr'),f($row2,'orcamento'),2);
            // Recupera as etapas vinculadas ao nível acima
            $RS3 = db_getSolicEtapa::getInstanceOf($dbms,$l_chave,f($row2,'sq_projeto_etapa'),'LSTNIVEL',null);
            $RS3 = SortArray($RS3,'ordem','asc');
            foreach($RS3 as $row3) {
              $w_html .= chr(13).EtapaLinhaAtiv($l_chave,f($row3,'sq_projeto_etapa'),f($row3,'titulo'),f($row3,'nm_resp'),f($row3,'sg_setor'),f($row3,'inicio_previsto'),f($row3,'fim_previsto'),f($row3,'perc_conclusao'),f($row3,'qt_ativ'),null,null,'PROJETO','RESUMIDO',f($row3,'sq_pessoa'),f($row3,'sq_unidade'),f($row3,'pj_vincula_contrato'),f($row3,'qt_contr'),f($row3,'orcamento'),3);
              // Recupera as etapas vinculadas ao nível acima
              $RS4 = db_getSolicEtapa::getInstanceOf($dbms,$l_chave,f($row3,'sq_projeto_etapa'),'LSTNIVEL',null);
              $RS4 = SortArray($RS4,'ordem','asc');
              foreach($RS4 as $row4) {
                $w_html .= chr(13).EtapaLinhaAtiv($l_chave,f($row4,'sq_projeto_etapa'),f($row4,'titulo'),f($row4,'nm_resp'),f($row4,'sg_setor'),f($row4,'inicio_previsto'),f($row4,'fim_previsto'),f($row4,'perc_conclusao'),f($row4,'qt_ativ'),null,null,'PROJETO','RESUMIDO',f($row4,'sq_pessoa'),f($row4,'sq_unidade'),f($row4,'pj_vincula_contrato'),f($row4,'qt_contr'),f($row4,'orcamento'),4);
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

  if ($w_tipo_visao!=2 && ($operacao=='L' || $operacao=='T')) {
    // Recursos envolvidos na execução do projeto
    $RS = db_getSolicRecurso::getInstanceOf($dbms,$l_chave,null,'LISTA');
    $RS = SortArray($RS,'tipo','asc','nome','asc');
    if (count($RS)>0 && $l_nome_menu['PJRECURSO']!='') {
      $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>'.$l_nome_menu['PJRECURSO'].'<hr color=#000000 SIZE=1></b></font></td></tr>';
      $w_html .= chr(13).'      <tr><td align="center" colspan="2">';
      $w_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
      $w_html.=chr(13).'         <tr><td bgColor="#f0f0f0"><div align="center"><b>Tipo</b></div></td>';
      $w_html.=chr(13).'             <td bgColor="#f0f0f0"><div align="center"><b>Nome</b></div></td>';
      $w_html.=chr(13).'             <td bgColor="#f0f0f0"><div align="center"><b>Finalidade</b></div></td>';
      $w_html .= chr(13).'       </tr>';
      $w_cor=$conTrBgColor;
      foreach ($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor; 
        $w_html .= chr(13).'      <tr>';
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
      if (count($RS)>0 && $l_nome_menu['PJANEXO']!='') {
        $w_html .= chr(13).'        <tr><td colspan=2><br><font size="2"><b>'.$l_nome_menu['PJANEXO'].'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
        $w_html .= chr(13).'      <tr><td align="center" colspan="2">';
        $w_html .= chr(13).'          <table width=100%  border="1" bordercolor="#00000">';
        $w_html .= chr(13).'            <tr><td bgColor="#f0f0f0"><div align="center"><b>Título</b></div></td>';
        $w_html .= chr(13).'              <td bgColor="#f0f0f0"><div align="center"><b>Descrição</b></div></td>';
        $w_html .= chr(13).'              <td bgColor="#f0f0f0"><div align="center"><b>Tipo</b></div></td>';
        $w_html .= chr(13).'              <td bgColor="#f0f0f0"><div align="center"><b>KB</b></div></td>';
        $w_html .= chr(13).'            </tr>';
        $w_cor=$conTrBgColor;
        foreach ($RS as $row) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor; 
          $w_html .= chr(13).'      <tr>';
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
    if($w_tipo_visao!=2) {
      $RS = db_getSolicLog::getInstanceOf($dbms,$l_chave,null,'LISTA');
      $RS = SortArray($RS,'phpdt_data','desc','sq_siw_solic_log','desc');
      $w_html.=chr(13).'   <tr><td colspan="2"><br><font size="2"><b>OCORRÊNCIAS E ANOTAÇÕES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $w_html.=chr(13).'   <tr><td colspan="2"><div align="center">';
      $w_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
      $w_html.=chr(13).'       <tr><td bgColor="#f0f0f0"><div align="center"><b>Data</b></div></td>';
      $w_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="center"><b>Ocorrência/Anotação</b></div></td>';
      $w_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="center"><b>Responsável</b></div></td>';
      $w_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="center"><b>Fase/Destinatário</b></div></td>';
      $w_html.=chr(13).'       </tr>';
    
      if (count($RS)==0) {
        $w_html .= chr(13).'      <tr><td colspan=6 align="center"><b>Não foram encontrados encaminhamentos.</b></td></tr>';
      } else {
        $w_html .= chr(13).'      <tr>';
        $w_cor=$conTrBgColor;
        $i = 0;
        foreach ($RS as $row) {
          if ($i==0) {
            $w_html .= chr(13).'        <td colspan=6>Fase atual: <b>'.f($row,'fase').'</b></td>';
            $i = 1;
          }
          if ($operacao=='T' || Nvl(f($row,'sq_projeto_log'),'')=='' || Nvl(f($row,'destinatario'),'')>'') {
            $w_html = $w_html.chr(13).'      <tr valign="top">';
            $w_html .= chr(13).'        <td nowrap>'.FormataDataEdicao(f($row,'phpdt_data'),3).'</td>';
            if (Nvl(f($row,'caminho'),'')>'') $w_html .= chr(13).'        <td>'.CRLF2BR(Nvl(f($row,'despacho'),'---').'<br>'.LinkArquivo('HL',$w_cliente,f($row,'sq_siw_arquivo'),'_blank','Clique para exibir o anexo em outra janela.','Anexo - '.f($row,'tipo').' - '.round(f($row,'tamanho')/1024,1).' KB',null)).'</td>';
            else                              $w_html .= chr(13).'        <td>'.CRLF2BR(Nvl(f($row,'despacho'),'---')).'</td>';
            $w_html .= chr(13).'        <td nowrap>'.ExibePessoa(null,$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'responsavel')).'</td>';
            if ((Nvl(f($row,'sq_projeto_log'),'')>'') && (Nvl(f($row,'destinatario'),'')>''))      $w_html .= chr(13).'        <td nowrap>'.ExibePessoa(null,$w_cliente,f($row,'sq_pessoa_destinatario'),$TP,f($row,'destinatario')).'</td>';
            elseif ((Nvl(f($row,'sq_projeto_log'),'')>'')  && (Nvl(f($row,'destinatario'),'')==''))$w_html .= chr(13).'        <td nowrap>Anotação</td>';
            else                                                                                   $w_html .= chr(13).'        <td nowrap>'.Nvl(f($row,'tramite'),'---').'</td>';
            $w_html .= chr(13).'      </tr>';
          }
        } 
        $w_html .= chr(13).'         </table></td></tr>';
      } 
    }
    $w_html .= chr(13).'</table>';
  } 
  return $w_html;
} 
?>