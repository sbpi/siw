<?
// =========================================================================
// Rotina de visualização dos dados do projeto
// -------------------------------------------------------------------------
function VisualProjeto($l_chave,$operacao,$l_usuario) {
  extract($GLOBALS);
  include_once($w_dir_volta.'classes/sp/db_getLinkSubMenu.php');
  include_once($w_dir_volta.'classes/sp/db_getSolicIndicador.php');
  include_once($w_dir_volta.'classes/sp/db_getSolicRecursos.php');
  include_once($w_dir_volta.'classes/sp/db_getSolicRestricao.php');
  include_once($w_dir_volta.'classes/sp/db_getSolicMeta.php');
  include_once($w_dir_volta.'classes/sp/db_getSolicLog.php');
  
  //Recupera as informações do sub-menu
  $RS = db_getLinkSubMenu::getInstanceOf($dbms, $w_cliente, 'PJCAD');
  foreach ($RS as $row) {
    if     (strpos(f($row,'sigla'),'ANEXO')!==false)    $l_nome_menu['ANEXO'] = strtoupper(f($row,'nome'));
    elseif (strpos(f($row,'sigla'),'AREAS')!==false)    $l_nome_menu['AREAS'] = strtoupper(f($row,'nome'));
    elseif (strpos(f($row,'sigla'),'GERAL')!==false)    $l_nome_menu['GERAL'] = strtoupper(f($row,'nome'));
    elseif (strpos(f($row,'sigla'),'QUALIT')!==false)   $l_nome_menu['QUALIT'] = strtoupper(f($row,'nome'));
    elseif (strpos(f($row,'sigla'),'ETAPA')!==false)    $l_nome_menu['ETAPA'] = strtoupper(f($row,'nome'));
    elseif (strpos(f($row,'sigla'),'INTERES')!==false)  $l_nome_menu['INTERES'] = strtoupper(f($row,'nome'));
    elseif (strpos(f($row,'sigla'),'RESP')!==false)     $l_nome_menu['RESP'] = strtoupper(f($row,'nome'));
    elseif (strpos(f($row,'sigla'),'RECURSO')!==false)  $l_nome_menu['RECURSO'] = strtoupper(f($row,'nome'));
    elseif (strpos(f($row,'sigla'),'RUBRICA')!==false)  $l_nome_menu['RUBRICA'] = strtoupper(f($row,'nome'));
    else $l_nome_menu[f($row,'sigla')] = strtoupper(f($row,'nome'));
  }
  $l_html='';
  // Verifica se o cliente tem o módulo de acordos contratado
  $RS = db_getSiwCliModLis::getInstanceOf($dbms,$w_cliente,null,'AC');
  if (count($RS)>0) $w_acordo='S'; else $w_acordo='N';

  // Verifica se o cliente tem o módulo de viagens contratado
  $RS = db_getSiwCliModLis::getInstanceOf($dbms,$w_cliente,null,'PD');
  if (count($RS)>0) $w_viagem='S'; else $w_viagem='N';

  // Verifica se o cliente tem o módulo planejamento estratégico
  $RS = db_getSiwCliModLis::getInstanceOf($dbms,$w_cliente,null,'IS');
  if (count($RS)>0) $w_acao='S'; else $w_acao='N';
  
  // Verifica se o cliente tem o módulo financeiro
  $RS = db_getSiwCliModLis::getInstanceOf($dbms,$w_cliente,null,'FN');
  if (count($RS)>0) $w_financeiro='S'; else $w_financeiro='N';

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
    $l_html .= chr(13).'<div align=center><center>';
    $l_html .= chr(13).'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
    if ($operacao != 'T') $l_html .= chr(13).'       <td align="right"><b><A class="HL" HREF="projeto.php?par=Visual&O=T&w_chave='.f($RS,'sq_siw_solicitacao').'&w_tipo=volta&P1=&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe as informações do projeto.">Exibir todas as informações</a></td></tr>';
    $l_html.=chr(13).'    <table width="99%" border="0">';
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    if (nvl(f($RS,'sq_peobjetivo'),'')!='') {
      $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>PLANO ESTRATÉGICO: '.f($RS,'nm_plano').'</b></font></div></td></tr>';
      $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>OBJETIVO: '.f($RS,'nm_objetivo').'</b></font></div></td></tr>';
    }
    $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>PROJETO: '.f($RS,'titulo').' ('.f($RS,'sq_siw_solicitacao').')</b></font></div></td></tr>';
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
     
    // Identificação do projeto
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>'.$l_nome_menu['GERAL'].'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';

    // Se a classificação foi informada, exibe.
    if (Nvl(f($RS,'sq_cc'),'')>'') {
      $l_html .= chr(13).'      <tr><td width="30%"><b>Classificação:<b></td>';
      $l_html .= chr(13).'        <td>'.f($RS,'cc_nome').' </td></tr>';
    }
    
    // Se o acordo foi informado, exibe.
    if (Nvl(f($RS,'sq_acordo'),'')>'') {
      if (substr(f($RS,'sg_acordo'),0,3)=='GCC') {
        $l_html.=chr(13).'      <tr><td width="30%"><font size=1><b>Convênio: <b></td>';
        $l_html .= chr(13).'        <td><A class="hl" HREF="mod_ac/convenios.php?par=Visual&O=L&w_chave='.f($RS,'sq_acordo').'&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=GCCCAD" title="Exibe as informações do acordo." target="_blank">'.f($RS,'cd_acordo').' ('.f($RS,'sq_acordo').') '.f($RS,'nm_acordo').'</a></b></font></td></tr>';
      } else {
        $l_html.=chr(13).'      <tr><td width="30%"><font size=1><b>Contrato: <b></td>';
        $l_html .= chr(13).'        <td><A class="hl" HREF="mod_ac/contratos.php?par=Visual&O=L&w_chave='.f($RS,'sq_acordo').'&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=GCCCAD" title="Exibe as informações do acordo." target="_blank">'.f($RS,'cd_acordo').' ('.f($RS,'sq_acordo').') '.f($RS,'nm_acordo').'</a></b></font></td></tr>';
      }
    } elseif (Nvl(f($RS,'sq_programa'),'')>'') {
      $l_html.=chr(13).'      <tr><td width="30%"><font size=1><b>Programa: <b></td>';
      $l_html .= chr(13).'        <td><A class="hl" HREF="mod_pe/programa.php?par=Visual&O=L&w_chave='.f($RS,'sq_programa').'&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=PEPROCAD" title="Exibe as informações do programa." target="_blank">'.f($RS,'cd_programa').' - '.f($RS,'nm_programa').'</a></b></font></td></tr>';
    } else {
      if (Nvl(f($RS,'sq_solic_pai'),'')>'') {
        $RS1 = db_getSolicData_IS::getInstanceOf($dbms,f($RS,'sq_solic_pai'),'ISACGERAL');
        foreach($RS1 as $row1) {$RS1=$row1; break;}
        $l_html .= chr(13).'      <tr><td colspan=3>Ação: <b>'.f($RS1,'cd_unidade').'.'.f($RS1,'cd_programa').'.'.f($RS1,'cd_acao').' - '.f($RS1,'nm_ppa').'</b></td>';
      }
    }
    $l_html .= chr(13).'      <tr><td valign="top" colspan="2">';
    $l_html .= chr(13).'          <tr><td width="30%"><b>Local de execução:</b></td><td>'.f($RS,'nm_cidade').' ('.f($RS,'co_uf').")</b></td>";
    $l_html .= chr(13).'          <tr><td><b>Proponente externo:<b></td>';
    $l_html .= chr(13).'        <td>'.nvl(f($RS,'proponente'),'---').' </b></td>';
    $l_html .= chr(13).'          <tr><td><b>Responsável:<b></td>';
    $l_html .= chr(13).'        <td>'.ExibePessoa(null,$w_cliente,f($RS,'solicitante'),$TP,f($RS,'nm_sol')).'</b></td>';
    $l_html .= chr(13).'          <tr><td><b>Unidade responsável:</b></td>';
    $l_html .= chr(13).'        <td>'.ExibeUnidade(null,$w_cliente,f($RS,'nm_unidade_resp'),f($RS,'sq_unidade'),$TP).'</b></td>';

    // Exibe o orçamento disponível para o projeto se for visão completa
    if ($w_tipo_visao==0) { 
      $l_html .= chr(13).'    <tr><td><b>Orçamento disponível:</b></td>';
      $l_html .= chr(13).'      <td>'.number_format(f($RS,'valor'),2,',','.').' </td></tr>';
      $l_html .= chr(13).'    <tr><td><b>Palavra chave:</b></td>';
      $l_html .= chr(13).'      <td>'.nvl(f($RS,'palavra_chave'),'---').' </td></tr>';
    }
    $l_html .= chr(13).'      <tr><td><b>Início previsto:</b></td>';
    $l_html .= chr(13).'        <td>'.FormataDataEdicao(f($RS,'inicio')).' </td></tr>';
    $l_html .= chr(13).'      <tr><td><b>Término previsto:</b></td>';
    $l_html .= chr(13).'        <td>'.FormataDataEdicao(f($RS,'fim')).' </td></tr>';
    $l_html .= chr(13).'      <tr><td><b>Prioridade:</b></td>';
    $l_html .= chr(13).'        <td>'.RetornaPrioridade(f($RS,'prioridade')).' </td></tr>';
    
    // Informações adicionais
      if (Nvl(f($RS,'descricao'),'') > '' || Nvl(f($RS,'justificativa'),'') > '' || $w_acordo == 'S' || $w_viagem=='S') {
        if ($w_tipo_visao!=2) {
          if ($w_acordo=='S' || $w_viagem=='S') {
            $l_html.=chr(13).'    <tr><td colspan=3><br><font size="2"><b>INFORMAÇÕES ADICIONAIS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
            if ($w_acordo=='S') {
              if (f($RS,'vincula_contrato')=='S') {
                $l_html .= chr(13).'<tr><td><b>Permite a vinculação de contratos:</b></td>';
                $l_html .= chr(13).'  <td>Sim</td></tr>';
              } else {
                $l_html .= chr(13).'<tr><td><b>Permite a vinculação de contratos:</b></td>';
                $l_html .= chr(13).'  <td>Não</td></tr>';
              }
            }
            if ($w_viagem=='S') {
              if (f($RS,'vincula_viagem')=='S') { 
                $l_html .= chr(13).'<tr><td><b>Permite a vinculação de viagens:</b></td>';
                $l_html .= chr(13).'  <td>Sim</td></tr>';
              } else {
                $l_html .= chr(13).'<tr><td><b>Permite a vinculação de viagens:</b></td>';
                $l_html .= chr(13).'  <td>Não</td></tr>';
              }
            }
          }
        }
      } 
   
    // Programação qualitativa
    if ($operacao=='T' && $l_nome_menu['QUALIT']!='') {
      $l_html.=chr(13).'    <tr><td colspan=3><br><font size="2"><b>'.$l_nome_menu['QUALIT'].'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      // Se for visão completa
      $l_html .= chr(13).'<tr valign="top"><td><b>Objetivo Superior:</b></td>';
      $l_html .= chr(13).'  <td>'.Nvl(CRLF2BR(f($RS,'objetivo_superior')),'---').' </td></tr>';
      $l_html .= chr(13).'<tr valign="top"><td><b>Objetivos Específicos:</b></td>';
      $l_html .= chr(13).'  <td>'.Nvl(CRLF2BR(f($RS,'descricao')),'---').' </td></tr>';
      $l_html .= chr(13).'<tr valign="top"><td><b>Exclusões Específicas:</b></td>';
      $l_html .= chr(13).'  <td>'.Nvl(CRLF2BR(f($RS,'exclusoes')),'---').' </td></tr>';
      $l_html .= chr(13).'<tr valign="top"><td><b>Premissas:</b></td>';
      $l_html .= chr(13).'  <td>'.Nvl(CRLF2BR(f($RS,'premissas')),'---').' </td></tr>';
      $l_html .= chr(13).'<tr valign="top"><td><b>Restricões:</b></td>';
      $l_html .= chr(13).'  <td>'.Nvl(CRLF2BR(f($RS,'restricoes')),'---').' </td></tr>';
      $l_html .= chr(13).'<tr valign="top"><td><b>Observações:</b></td>';
      $l_html .= chr(13).'  <td>'.Nvl(CRLF2BR(f($RS,'justificativa')),'---').' </td></tr>';
    } 


    // Dados da conclusão do projeto, se ela estiver nessa situação
    if (f($RS,'concluida')=='S' && Nvl(f($RS,'data_conclusao'),'') > '') {
      $l_html .= chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS DA CONCLUSÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html .= chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
      $l_html .= chr(13).'      <tr><td width="30%"><b>Início previsto:</b></td>';
      $l_html .= chr(13).'        <td>'.FormataDataEdicao(f($RS,'inicio_real')).' </td></tr>';
      $l_html .= chr(13).'      <tr><td><b>Término previsto:</b></td>';
      $l_html .= chr(13).'        <td>'.FormataDataEdicao(f($RS,'fim_real')).' </td></tr>';
      if ($w_tipo_visao==0) { 
        $l_html .= chr(13).'    <tr><td><b>Custo real:</b></td>';
        $l_html .= chr(13).'      <td>'.number_format(f($RS,'custo_real'),2,',','.').' </td></tr>';
      }
      $l_html .= chr(13).'          </table>';
      if ($w_tipo_visao==0) { 
        $l_html .= chr(13).'    <tr><td valign="top"><b>Nota de conclusão:</b></td>';
        $l_html .= chr(13).'      <td>'.CRLF2BR(f($RS,'nota_conclusao')).' </td></tr>';
      }
    }
  } 
  // Se for listagem, exibe os outros dados dependendo do tipo de visão  do usuário
  if ($w_tipo_visao!=2 && ($operacao=='L' || $operacao=='T')) {
    if (f($RS,'aviso_prox_conc')=='S' || f($RS,'aviso_prox_conc_pacote')=='S') {
      // Configuração dos alertas de proximidade da data limite para conclusão da demanda
      $l_html.=chr(13).'        <tr><td colspan="2"><br><font size="2"><b>ALERTAS DE PROXIMIDADE DA DATA PREVISTA DE TÉRMINO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html .= chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
      if (f($RS,'aviso_prox_conc')=='S') {
        $l_html .= chr(13).'      <tr><td width="30%"><b>Projeto:</b></td>';
        $l_html .= chr(13).'        <td>A partir de '.formataDataEdicao(f($RS,'aviso')).'.</td></tr>';
      }
      if (f($RS,'aviso_prox_conc_pacote')=='S') {
        $l_html .= chr(13).'      <tr><td width="30%"><b>Pacotes de trabalho:</b></td>';
        $l_html .= chr(13).'        <td>Faltando '.f($RS,'perc_dias_aviso_pacote').'% do período previsto para cada pacote de trabalho.</td></tr>';
      } 
    }
    $l_html .= chr(13).'          </table>';
  }
  // Rubricas do projeto
  if ($l_nome_menu['RUBRICA']!='' && $w_tipo_visao!=2 && ($operacao=='T')) {
    $RS = db_getSolicRubrica::getInstanceOf($dbms,$l_chave,null,'S',null,null,null,null);
    $RS = SortArray($RS,'codigo','asc');
    if (count($RS)>0 && $w_financeiro=='S' ) {
      $l_html.=chr(13).'        <tr><td colspan=2><br><font size="2"><b>'.$l_nome_menu['RUBRICA'].'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html .= chr(13).'      <tr><td align="center" colspan="2">';
      $l_html .= chr(13).'          <table width=100%  border="1" bordercolor="#00000">';
      $l_html .= chr(13).'          <tr align="center">';
      $l_html .= chr(13).'            <td rowspan="2" bgColor="#f0f0f0"><b>Código</td>';
      $l_html .= chr(13).'            <td rowspan="2" bgColor="#f0f0f0"><b>Nome</td>';
      $l_html .= chr(13).'            <td rowspan="2" bgColor="#f0f0f0"><b>Valor Inicial</td>';
      $l_html .= chr(13).'            <td colspan="3" bgcolor="'.$conTrBgColorLightBlue1.'" align="center"><b>Entrada</td>';
      $l_html .= chr(13).'            <td colspan="3" bgcolor="'.$conTrBgColorLightRed1.'" align="center"><b>Saída</td>';
      $l_html .= chr(13).'          </tr>';
      $l_html .= chr(13).'          <tr bgcolor="'.$conTrAlternateBgColor.'" align="center">';
      $l_html .= chr(13).'            <td bgcolor="'.$conTrBgColorLightBlue1.'"><b>Prevista</td>';
      $l_html .= chr(13).'            <td bgcolor="'.$conTrBgColorLightBlue1.'"><b>Real</td>';
      $l_html .= chr(13).'            <td bgcolor="'.$conTrBgColorLightBlue1.'"><b>Pendente</td>';
      $l_html .= chr(13).'            <td bgcolor="'.$conTrBgColorLightRed1.'"><b>Prevista</td>';
      $l_html .= chr(13).'            <td bgcolor="'.$conTrBgColorLightRed1.'"><b>Real</td>';
      $l_html .= chr(13).'            <td bgcolor="'.$conTrBgColorLightRed1.'"><b>Pendente</td>';
      $l_html .= chr(13).'          </tr>';      
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
        $l_html .= chr(13).'      <tr>';
        $l_html .= chr(13).'          <td><A class="hl" HREF="javascript:location.href=this.location.href;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.'mod_fn/lancamento.php?par=Ficharubrica&O=L&w_sq_projeto_rubrica='.f($row,'sq_projeto_rubrica').'&w_tipo=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Extrato Rubrica'.'&SG='.$SG.MontaFiltro('GET')).'\',\'Ficha3\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Exibe as informações deste registro.">'.f($row,'codigo').'</A>&nbsp';
        $l_html .= chr(13).'          <td>'.f($row,'nome').' </td>';
        $l_html .= chr(13).'          <td align="right">'.number_format(f($row,'valor_inicial'),2,',','.').' </td>';
        $l_html .= chr(13).'          <td align="right" bgcolor="'.$w_cor_blue.'">'.number_format(f($row,'entrada_prevista'),2,',','.').' </td>';
        $l_html .= chr(13).'          <td align="right" bgcolor="'.$w_cor_blue.'">'.number_format(f($row,'entrada_real'),2,',','.').' </td>';
        $l_html .= chr(13).'          <td align="right" bgcolor="'.$w_cor_blue.'">'.number_format(f($row,'entrada_pendente'),2,',','.').' </td>';
        $l_html .= chr(13).'          <td align="right" bgcolor="'.$w_cor_red.'">'.number_format(f($row,'saida_prevista'),2,',','.').' </td>';
        $l_html .= chr(13).'          <td align="right" bgcolor="'.$w_cor_red.'">'.number_format(f($row,'saida_real'),2,',','.').' </td>';
        $l_html .= chr(13).'          <td align="right" bgcolor="'.$w_cor_red.'">'.number_format(f($row,'saida_pendente'),2,',','.').' </td>';
        $l_html .= chr(13).'      </tr>';
        $w_valor_inicial    += f($row,'valor_inicial');
        $w_entrada_prevista += f($row,'entrada_prevista');
        $w_entrada_real     += f($row,'entrada_real');
        $w_entrada_pendente += f($row,'entrada_pendente');
        $w_saida_prevista   += f($row,'saida_prevista');
        $w_saida_real       += f($row,'saida_real');
        $w_saida_pendente   += f($row,'saida_pendente');
      }
      $l_html .= chr(13).'      <tr>';
      $l_html .= chr(13).'          <td align="right" colspan="2"><b>Totais:&nbsp;</td>';
      $l_html .= chr(13).'          <td align="right"><b>'.number_format($w_valor_inicial,2,',','.').' </b></td>';
      $l_html .= chr(13).'          <td align="right" bgcolor="'.$conTrBgColorLightBlue1.'"><b>'.number_format($w_entrada_prevista,2,',','.').' </b></td>';
      $l_html .= chr(13).'          <td align="right" bgcolor="'.$conTrBgColorLightBlue1.'"><b>'.number_format($w_entrada_real,2,',','.').' </b></td>';
      $l_html .= chr(13).'          <td align="right" bgcolor="'.$conTrBgColorLightBlue1.'"><b>'.number_format($w_entrada_pendente,2,',','.').' </b></td>';
      $l_html .= chr(13).'          <td align="right" bgcolor="'.$conTrBgColorLightRed1.'"><b>'.number_format($w_saida_prevista,2,',','.').' </b></td>';
      $l_html .= chr(13).'          <td align="right" bgcolor="'.$conTrBgColorLightRed1.'"><b>'.number_format($w_saida_real,2,',','.').' </b></td>';
      $l_html .= chr(13).'          <td align="right" bgcolor="'.$conTrBgColorLightRed1.'"><b>'.number_format($w_saida_pendente,2,',','.').' </b></td>';
      $l_html .= chr(13).'      </tr>';
      $l_html .= chr(13).'         </table></td></tr>';
    } else {
      $l_html.=chr(13).'        <tr><td colspan=2><br><font size="2"><b>'.$l_nome_menu['RUBRICA'].'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html .= chr(13).'      <tr><td align="center" colspan="2">';
      $l_html .= chr(13).'          <table width=100%  border="1" bordercolor="#00000">';
      $l_html .= chr(13).'          <tr align="center">';
      $l_html .= chr(13).'            <td rowspan="2" bgColor="#f0f0f0" width="1%" nowrap><b>Código</td>';
      $l_html .= chr(13).'            <td rowspan="2" bgColor="#f0f0f0"><b>Nome</td>';
      $l_html .= chr(13).'            <td rowspan="2" bgColor="#f0f0f0"><b>Descrição</td>';
      $l_html .= chr(13).'            <td colspan="2" bgColor="#f0f0f0"  align="center"><b>Orçamento</td>';
      $l_html .= chr(13).'          </tr>';
      $l_html .= chr(13).'          <tr align="center" >';
      $l_html .= chr(13).'            <td bgColor="#f0f0f0"><b>Previsto</td>';
      $l_html .= chr(13).'            <td bgColor="#f0f0f0"><b>Realizado</td>';
      $l_html .= chr(13).'          </tr>';      
      $w_cor=$conTrBgColor;
      $w_total_previsto  = 0;
      $w_total_executado = 0;
      foreach ($RS as $row) {
        $RS_Cronograma = db_getCronograma::getInstanceOf($dbms,f($row,'sq_projeto_rubrica'),null,null,null);
        $RS_Cronograma = SortArray($RS_Cronograma,'inicio', 'asc', 'fim', 'asc');
        if (count($RS_Cronograma)>0) $w_rowspan = 'rowspan="2"'; else $w_rowspan = '';
        $l_html .= chr(13).'      <tr valign="top">';
        $l_html .= chr(13).'          <td '.$w_rowspan.'><A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.'projeto.php?par=Cronograma&w_edita=N&O=L&w_chave='.f($row,'sq_projeto_rubrica').'&w_chave_pai='.$l_chave.'&w_tipo=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Extrato Rubrica'.'&SG=PJCRONOGRAMA'.MontaFiltro('GET')).'\',\'Ficha3\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Exibe as informações desta rubrica.">'.f($row,'codigo').'</A>&nbsp';
        $l_html .= chr(13).'          <td>'.f($row,'nome').' </td>';
        $l_html .= chr(13).'          <td>'.f($row,'descricao').' </td>';
        $l_html .= chr(13).'          <td align="right">'.number_format(f($row,'total_previsto'),2,',','.').' </td>';
        $l_html .= chr(13).'          <td align="right">'.number_format(f($row,'total_real'),2,',','.').' </td>';
        $l_html .= chr(13).'      </tr>';
        if ($w_rowspan!='') {
          $l_html .= chr(13).'      <tr><td align="center" colspan="4">';
          $l_html .= chr(13).'        <table width=100%  bordercolor="#00000" cellpadding=1 cellspacing=1>';
          $l_html .= chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
          $l_html .= chr(13).'          <td colspan=4><b>Cronograma Desembolso</td>'; 
          $l_html .= chr(13).'        </tr>';
          $l_html .= chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
          $l_html .= chr(13).'          <td><b>Período</td>';
          $l_html .= chr(13).'          <td><b>Orçamento Previsto</td>';
          $l_html .= chr(13).'          <td><b>Orçamento Realizado</td>';
          $l_html .= chr(13).'          <td><b>% Realização</td>';
          $l_html .= chr(13).'        </tr>';
          foreach ($RS_Cronograma as $row1) {
            $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
            $l_html .= chr(13).'      <tr bgcolor="'.$w_cor.'" valign="top">';
            $l_html .= chr(13).'        <td align="center">'.FormataDataEdicao(f($row1,'inicio'),5).' a ';
            $l_html .= chr(13).'                           '.FormataDataEdicao(f($row1,'fim'),5).'</td>';
            $l_html .= chr(13).'        <td align="right">'.formatNumber(f($row1,'valor_previsto')).'</td>';
            $l_html .= chr(13).'        <td align="right">'.formatNumber(f($row1,'valor_real')).'</td>';
            $w_perc = 0;
            if (f($row1,'valor_previsto') > 0) {
              $w_perc = (f($row1,'valor_real')/f($row1,'valor_previsto')*100);
            }
            $l_html .= chr(13).'        <td align="right">'.formatNumber($w_perc).' %</td>';
            $l_html .= chr(13).'      </tr>';
          } 
          $l_html .= chr(13).'        </table>';
        }
        $w_total_previsto += f($row,'total_previsto');
        $w_total_real     += f($row,'total_real');
      } 
      $l_html .= chr(13).'      <tr>';
      $l_html .= chr(13).'          <td align="right" colspan="3"><b>Totais:&nbsp;</td>';
      $l_html .= chr(13).'          <td align="right"><b>'.number_format($w_total_previsto,2,',','.').' </b></td>';
      $l_html .= chr(13).'          <td align="right"><b>'.number_format($w_total_real,2,',','.').' </b></td>';
      $l_html .= chr(13).'      </tr>';
      $l_html .= chr(13).'         </table></td></tr>';
    }  
  } 

  //Lista das tarefas que não são ligadas a nenhuma etapa
  if ($operacao=='T') {
    $RS = db_getLinkData::getInstanceOf($dbms,$_SESSION['P_CLIENTE'],'GDPCAD');
    $RS = db_getSolicList::getInstanceOf($dbms,f($RS,'sq_menu'),$l_usuario,'GDPCADET',3,
           null,null,null,null,null,null,null,null,null,null,null,null,null,null,
           null,null,null,null,null,null,null,null,$l_chave,null,null,null);
    if (count($RS)>0) {
      $l_html .= chr(13).'      <tr><td colspan="2"><br><font size="2"><b>TAREFAS SEM VINCULAÇÃO COM '.$l_nome_menu['ETAPA'].'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html .= chr(13).'      <tr><td align="center" colspan="2">';
      $l_html .= chr(13).'          <table width=100%  border="1" bordercolor="#00000">';
      $l_html .= chr(13).'            <tr><td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Nº</td>';
      $l_html .= chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Detalhamento</td>';
      $l_html .= chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Responsável</td>';
      $l_html .= chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Setor</td>';
      $l_html .= chr(13).'            <td colspan=2 bgColor="#f0f0f0"><div align="center"><b>Execução</td>';
      $l_html .= chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Fase atual</td>';
      $l_html .= chr(13).'          </tr>';
      $l_html .= chr(13).'          <tr>';
      $l_html .= chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>De</td>';
      $l_html .= chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>Até</td>';
      $l_html .= chr(13).'          </tr>';
      foreach ($RS as $row) {
        $l_html .= chr(13).'      <tr><td>';
        $l_html.=chr(13).ExibeImagemSolic(f($row,'sigla'),f($row,'inicio'),f($row,'fim'),f($row,'inicio_real'),f($row,'fim_real'),f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null);
        $l_html .= chr(13).'  <A class="HL" HREF="projetoativ.php?par=Visual&R=ProjetoAtiv.php?par=Visual&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro." target="blank">'.f($row,'sq_siw_solicitacao').'</a>';
        $l_html .= chr(13).'     <td>'.Nvl(f($row,'assunto'),'-');
        $l_html .= chr(13).'     <td>'.ExibePessoa(null,$w_cliente,f($row,'solicitante'),$TP,f($row,'nm_resp')).'</td>';
        $l_html .= chr(13).'     <td>'.f($row,'sg_unidade_resp').'</td>';
        $l_html .= chr(13).'     <td align="center">'.Nvl(FormataDataEdicao(f($row,'inicio')),'-').'</td>';
        $l_html .= chr(13).'     <td align="center">'.Nvl(FormataDataEdicao(f($row,'fim')),'-').'</td>';
        $l_html .= chr(13).'     <td colspan=2 nowrap>'.f($row,'nm_tramite').'</td>';
      } 
      $l_html .= chr(13).'      </td></tr></table>';
    } 
  } 

  // Etapas do projeto
  // Recupera todos os registros para a listagem
  $RS = db_getSolicEtapa::getInstanceOf($dbms,$l_chave,null,'LISTA',null);
  $RS = SortArray($RS,'ordem','asc');
  // Recupera o código da opção de menu  a ser usada para listar as tarefas
  $w_p2 = '';
  $w_p3 = '';
  if (count($RS)>0) {
    foreach ($RS as $row) {
      if (Nvl(f($row,'P2'),0) > 0) $w_p2 = f($row,'P2');
      if (Nvl(f($row,'P3'),0) > 0) $w_p3 = f($row,'P3');
    } 
    reset($RS);
  } 
  $RS = db_getSolicEtapa::getInstanceOf($dbms,$l_chave,null,'ARVORE',null);
  if (count($RS)>0) {
    // Se não foram selecionados registros, exibe mensagem
    // Monta função JAVASCRIPT para fazer a chamada para a lista de tarefas
    if ($w_p2 > '') {
      $l_html .= chr(13).'<SCRIPT LANGUAGE="JAVASCRIPT">';
      $l_html .= chr(13).'  function lista (projeto, etapa) {';
      $l_html .= chr(13).'    document.Form1.p_projeto.value=projeto;';
      $l_html .= chr(13).'    document.Form1.p_atividade.value=etapa;';
      $RS1 = db_getMenuData::getInstanceOf($dbms,$w_p2);
      $l_html .= chr(13).'    document.Form1.action=\''.f($RS1,'link').'\';';
      $l_html .= chr(13).'    document.Form1.P2.value=\''.$w_p2.'\';';
      $l_html .= chr(13).'    document.Form1.SG.value=\''.f($RS1,'sigla').'\';';        
      $l_html .= chr(13).'    document.Form1.p_agrega.value=\'GRDMETAPA\';';
      $RS1 = db_getTramiteList::getInstanceOf($dbms,$w_p2,null,null);
      $RS1 = SortArray($RS1,'ordem','asc');
      $l_html .= chr(13).'    document.Form1.p_fase.value=\'\';';
      $w_fases='';
      foreach($RS1 as $row1) {
        if (f($row1,'sigla')!='CA') $w_fases=$w_fases.','.f($row1,'sq_siw_tramite');
      } 
      $l_html .= chr(13).'    document.Form1.p_fase.value=\''.substr($w_fases,1,100).'\';';
      $l_html .= chr(13).'    document.Form1.submit();';
      $l_html .= chr(13).'  }';
      $l_html .= chr(13).'</SCRIPT>';
    }
    // Monta função JAVASCRIPT para fazer a chamada para a lista de contratos
    if ($w_p3 > '') {
      $l_html .= chr(13).'<SCRIPT LANGUAGE="JAVASCRIPT">';
      $l_html .= chr(13).'  function listac (projeto, etapa) {';
      $l_html .= chr(13).'    document.Form1.p_projeto.value=projeto;';
      $l_html .= chr(13).'    document.Form1.p_atividade.value=etapa;';
      $RS1 = db_getMenuData::getInstanceOf($dbms,$w_p3);
      $l_html .= chr(13).'    document.Form1.action=\''.f($RS1,'link').'\';';
      $l_html .= chr(13).'    document.Form1.P2.value=\''.$w_p3.'\';';
      $l_html .= chr(13).'    document.Form1.SG.value=\''.f($RS1,'sigla').'\';';
      $l_html .= chr(13).'    document.Form1.p_agrega.value=\''.substr(f($RS1,'sigla'),0,3).'ETAPA\';';
      $RS1 = db_getTramiteList::getInstanceOf($dbms,$w_p3,null,null);
      $RS1 = SortArray($RS1,'ordem','asc');
      $l_html .= chr(13).'    document.Form1.p_fase.value=\'\';';
      $w_fases='';
      foreach($RS1 as $row1) {
        if (f($row1,'sigla')!='CA') $w_fases=$w_fases.','.f($row1,'sq_siw_tramite');
      } 
      $l_html .= chr(13).'    document.Form1.p_fase.value=\''.substr($w_fases,1,100).'\';';
      $l_html .= chr(13).'    document.Form1.submit();';
      $l_html .= chr(13).'  }';
      $l_html .= chr(13).'</SCRIPT>';
    }      
    $RS1 = db_getMenuData::getInstanceOf($dbms,$w_p2);
    AbreForm('Form1',f($RS1,'link'),'POST',null,'Lista',3,$w_p2,1,null,RemoveTP($w_TP),f($RS1,'sigla'),$w_pagina.$par,'L');
    $l_html .= chr(13).'<input type="Hidden" name="p_projeto" value="">';
    $l_html .= chr(13).'<input type="Hidden" name="p_atividade" value="">';
    $l_html .= chr(13).'<input type="Hidden" name="p_agrega" value="">';
    $l_html .= chr(13).'<input type="Hidden" name="p_fase" value="">';
  }
  if(count($RS)>0 && $l_nome_menu['ETAPA']!='') {
    $RS1 = db_getSolicData::getInstanceOf($dbms,$l_chave,'PJGERAL');
    $l_html .= chr(13).'      <tr><td colspan=2><br><font size="2"><b>'.$l_nome_menu['ETAPA'].'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $l_html .= chr(13).'      <tr><td colspan="2">';
    $l_html .= chr(13).'        [<A class="HL" HREF="'.$conRootSIW.'mod_pr/graficos.php?par=hier&w_chave='.$l_chave.'" TARGET="EAP" TITLE="Exibe diagrama hierárquico da estrutura analítica do projeto.">DIAGRAMA HIERÁRQUICO</A>]';
    $l_html .= chr(13).'        [<A CLASS="HL" HREF="'.$conRootSIW.'mod_pr/graficos.php?par=gantt&w_chave='.$l_chave.'" TARGET="GANTT" TITLE="Exibe gráfico de Gantt do projeto.">GRÁFICO DE GANTT</A>]';
    $l_html .= chr(13).'        [<A CLASS="HL" HREF="'.$conRootSIW.'mod_pr/relatorios.php?par=Rel_Progresso&p_projeto='.$l_chave.'&p_inicio='.formataDataEdicao(first_Day(time())).'&p_fim='.formataDataEdicao(last_Day(time())).'&p_indicador=S&p_indicador=S&p_prevista=S&p_realizada=S&p_pendente=S&p_proximo=S&p_questoes=S&O=L&SG=RELPJPROG&TP=Relatório de progresso " TARGET="GANTT" TITLE="Exibe relatório de progresso do mês corrente.">PROGRESSO NO MÊS</A>]';

    $l_html .= chr(13).'      <tr><td align="center" colspan="2">';
    $l_html .= chr(13).'         <table width=100%  border="1" bordercolor="#00000">';
    $l_html .= chr(13).'          <tr><td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Etapa</b></div></td>';
    $l_html .= chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Título</b></div></td>';
    $l_html .= chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Responsável</b></div></td>';
    $l_html .= chr(13).'            <td colspan=2 bgColor="#f0f0f0"><div align="center"><b>Execução prevista</b></div></td>';
    $l_html .= chr(13).'            <td colspan=2 bgColor="#f0f0f0"><div align="center"><b>Execução real</b></div></td>';
    $l_html .= chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Orc.</b></div></td>';
    $l_html .= chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Conc.</b></div></td>';
    $l_html .= chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Peso</b></div></td>';
    $l_html .= chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><div align="center"><b>Tar.</b></div></td>';
    $l_html .= chr(13).'          </tr>';
    $l_html .= chr(13).'          <tr>';
    $l_html .= chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>De</b></div></td>';
    $l_html .= chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>Até</b></div></td>';
    $l_html .= chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>De</b></div></td>';
    $l_html .= chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>Até</b></div></td>';
    $l_html .= chr(13).'          </tr>';
    //Se for visualização normal, irá visualizar somente as etapas
    if ($operacao=='L' || $operacao=='V') {
      foreach($RS as $row) {
        $l_html .= chr(13).EtapaLinha($l_chave,f($row,'sq_projeto_etapa'),f($row,'titulo'),f($row,'nm_resp'),f($row,'sg_setor'),f($row,'inicio_previsto'),f($row,'fim_previsto'),f($row,'inicio_real'),f($row,'fim_real'),f($row,'perc_conclusao'),f($row,'qt_ativ'),((f($row,'pacote_trabalho')=='S') ? '<b>' : ''),null,'PROJETO',f($row,'sq_pessoa'),f($row,'sq_unidade'),f($row,'pj_vincula_contrato'),f($row,'qt_contr'),f($row,'orcamento'),(f($row,'level')-1),f($row,'restricao'),f($row,'peso'));
      } 
    } elseif ($w_tipo_visao!=2 && ($operacao=='T')){
      //Se for visualização total, ira visualizar as etapas e as tarefas correspondentes
      foreach($RS as $row) {
        $l_html .= chr(13).EtapaLinhaAtiv($l_chave,f($row,'sq_projeto_etapa'),f($row,'titulo'),f($row,'nm_resp'),f($row,'sg_setor'),f($row,'inicio_previsto'),f($row,'fim_previsto'),f($row,'inicio_real'),f($row,'fim_real'),f($row,'perc_conclusao'),f($row,'qt_ativ'),((f($row,'pacote_trabalho')=='S') ? '<b>' : ''),null,'PROJETO','RESUMIDO',f($row,'sq_pessoa'),f($row,'sq_unidade'),f($row,'pj_vincula_contrato'),f($row,'qt_contr'),f($row,'orcamento'),(f($row,'level')-1),f($row,'restricao'),f($row,'peso'));
      } 
    } 
    $l_html .= chr(13).'      </form>';
    $l_html .= chr(13).'      </center>';
    $l_html .= chr(13).'         </table></td></tr>';
    $l_html .= chr(13).'<tr><td colspan=13><b>Observação: Pacotes de trabalho destacados em negrito.';
    if ($w_tipo=='WORD') {
      $l_html .= chr(13).'<tr><td colspan=13><table border=0>';
      $l_html .= chr(13).'  <tr valign="top"><td colspan=3><b>Legenda dos sinalizadores da EAP:</b>'.ExibeImagemSolic('ETAPA',null,null,null,null,null,null,null, null,true);
      if ($w_tipo_visao!=2 && ($operacao=='T')){
        $l_html .= chr(13).'  <tr valign="top"><td colspan=3><b>Legenda dos sinalizadores das tarefas:</b>'.ExibeImagemSolic('GD',null,null,null,null,null,null,null, null,true);
      }
      $l_html .= chr(13).'  </table>';
    }
  }

  if ($operacao=='T') {
    // Indicadores
    $RS = db_getSolicIndicador::getInstanceOf($dbms,$l_chave,null,null,'VISUAL');
    $RS = SortArray($RS,'nm_tipo_indicador','asc','nome','asc');
    if (count($RS)>0 && $l_nome_menu['INDSOLIC']!='') { 
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>'.$l_nome_menu['INDSOLIC'].'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html .= chr(13).'      <tr><td align="center" colspan="2">';
      $l_html.=chr(13).'          <table width=100%  border="1" bordercolor="#00000">';
      $l_html .= chr(13).'          <tr><td bgColor="#f0f0f0" width="5%" nowrap><div align="center"><b>Tipo</b></div></td>';
      $l_html .= chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>Indicador</b></div></td>';
      $l_html .= chr(13).'          </tr>';
      $w_cor=$conTrBgColor;
      foreach ($RS as $row) {
        $l_html .= chr(13).'      <tr>';
        $l_html .= chr(13).'        <td nowrap>'.f($row,'nm_tipo_indicador').'</td>';
        $l_html .= chr(13).'        <td><A class="HL" HREF="javascript:this.status.value;" onClick="window.open(\''.$conRootSIW.'mod_pe/indicador.php?par=FramesAfericao&R='.$w_pagina.$par.'&O=L&w_troca=p_base&p_tipo_indicador='.f($row,'sq_tipo_indicador').'&p_indicador='.f($row,'chave').'&p_pesquisa=BASE&p_volta=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\',\'Afericao\',\'width=730,height=500,top=30,left=30,status=no,resizable=yes,scrollbars=yes,toolbar=no\');" title="Exibe informaçoes sobre o indicador.">'.f($row,'nome').'</a></td></td>';
        $l_html .= chr(13).'      </tr>';
      } 
      $l_html .= chr(13).'         </table></td></tr>';
    }
    // Metas
    $RS = db_getSolicMeta::getInstanceOf($dbms,$w_cliente,$l_usuario,$l_chave,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
    $RS = SortArray($RS,'ordem','asc','titulo','asc');
    if (count($RS)>0 && $l_nome_menu['METASOLIC']!='') {
      $l_html .= chr(13).'      <tr><td colspan="2"><br><font size="2"><b>'.$l_nome_menu['METASOLIC'].'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html .= chr(13).'      <tr><td align="center" colspan="2">';
      $l_html .= chr(13).'          <table width=100%  border="1" bordercolor="#00000">';     
      $l_html .= chr(13).'          <tr align="center" valign="top" bgColor="#f0f0f0">';
      $l_html .= chr(13).'            <td><b>Meta</b></td>';
      $l_html .= chr(13).'            <td><b>Início</b></td>';
      $l_html .= chr(13).'            <td><b>Fim</b></td>';
      $l_html .= chr(13).'            <td><b>Indicador</b></td>';
      $l_html .= chr(13).'            <td><b>Base</b></td>';
      $l_html .= chr(13).'            <td><b>Valor a ser alcançado</b></td>';
      $l_html .= chr(13).'            <td width="1%" nowrap><b>U.M.</b></td>';
      $l_html .= chr(13).'          </tr>';
      $w_cor=$conTrBgColor;
      foreach ($RS as $row) {
        $l_html .= chr(13).'      <tr>';
        $l_html .= chr(13).'        <td>'.f($row,'titulo').'</td>';
        $l_html .= chr(13).'        <td align="center">'.date(d.'/'.m.'/'.y,f($row,'inicio')).'</td>';
        $l_html .= chr(13).'        <td align="center">'.date(d.'/'.m.'/'.y,f($row,'fim')).'</td>';
        $l_html .= chr(13).'        <td><A class="HL" HREF="javascript:this.status.value;" onClick="window.open(\''.$conRootSIW.'mod_pe/indicador.php?par=FramesAfericao&R='.$w_pagina.$par.'&O=L&w_troca=p_base&p_tipo_indicador='.f($row,'sq_tipo_indicador').'&p_indicador='.f($row,'sq_eoindicador').'&p_pesquisa=BASE&p_volta=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\',\'Afericao\',\'width=730,height=500,top=30,left=30,status=no,resizable=yes,scrollbars=yes,toolbar=no\');" title="Exibe informaçoes sobre o indicador.">'.f($row,'nm_indicador').'</a></td></td>';
        $l_html .= chr(13).'        <td>'.f($row,'nm_base_geografica').'</td>';
        $l_html .= chr(13).'        <td align="right">'.formatNumber(f($row,'quantidade'),4).'</td>';
        $l_html .= chr(13).'        <td align="center">'.f($row,'sg_unidade_medida').'</td>';        
        $l_html .= chr(13).'      </tr>';
      } 
      $l_html .= chr(13).'         </table></td></tr>';
      $l_html .= chr(13).'<tr><td colspan=3><table border=0>';
      $l_html .= chr(13).'  <tr><td align="right">U.M.<td>Unidade de medida do indicador';
      $l_html .= chr(13).'  </table>';
    }   
    // Recursos
    $RS = db_getSolicRecursos::getInstanceOf($dbms,$w_cliente,$w_usuario,$l_chave,null,null,null,null,null,null,null,null,null,null,null);
    $RS = SortArray($RS,'nm_tipo_recurso','asc','nm_recurso','asc'); 
    if (count($RS)>0 && $l_nome_menu['RECSOLIC']!='') {
      $l_html .= chr(13).'      <tr><td colspan="2"><br><font size="2"><b>'.$l_nome_menu['RECSOLIC'].'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html .= chr(13).'      <tr><td align="center" colspan="2">';
      $l_html .= chr(13).'          <table width=100%  border="1" bordercolor="#00000">';     
      $l_html .= chr(13).'          <tr align="center" valign="top" bgColor="#f0f0f0">';
      $l_html .= chr(13).'            <td><b>Tipo</b></td>';
      $l_html .= chr(13).'            <td><b>Código</b></td>';
      $l_html .= chr(13).'            <td><b>Recurso</b></td>';
      $l_html .= chr(13).'            <td width="1%" nowrap><b>U.M.</b></td>';
      $l_html .= chr(13).'          </tr>';
      $w_cor=$conTrBgColor;
      foreach ($RS as $row) {
        $l_html .= chr(13).'      <tr>';
        $l_html .= chr(13).'        <td>'.f($row,'nm_tipo_completo').'</td>';
        $l_html .= chr(13).'        <td>'.nvl(f($row,'codigo'),'---').'</td>';
        $l_html .= chr(13).'        <td>'.ExibeRecurso($w_dir_volta,$w_cliente,f($row,'nm_recurso'),f($row,'sq_recurso'),$TP,$l_chave).'</td>';
        $l_html .= chr(13).'        <td align="center" nowrap>'.f($row,'nm_unidade_medida').'</td>';        
        $l_html .= chr(13).'      </tr>';
      } 
      $l_html .= chr(13).'         </table></td></tr>';
      $l_html .= chr(13).'<tr><td colspan=3><table border=0>';
      $l_html .= chr(13).'  <tr><td align="right">U.M.<td>Unidade de alocação do recurso';
      $l_html .= chr(13).'  </table>';
    }
    // Riscos
    $RS = db_getSolicRestricao::getInstanceOf($dbms,$l_chave,$w_chave_aux,null,null,null,null,null);
    $RS = SortArray($RS,'problema','desc','criticidade','desc','nm_tipo_restricao','asc','nm_risco','asc'); 
    if (count($RS)>0 && $l_nome_menu['RESTSOLIC']!='') {
      $l_html .= chr(13).'      <tr><td colspan="2"><br><font size="2"><b>QUESTÕES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html .= chr(13).'      <tr><td align="center" colspan="2">';
      $l_html .= chr(13).'          <table width=100%  border="1" bordercolor="#00000">';     
      $l_html .= chr(13).'          <tr align="center" valign="top" bgColor="#f0f0f0">';
      $l_html .= chr(13).'            <td><b>Tipo</b></td>';
      $l_html .= chr(13).'            <td><b>Classificação</b></td>';
      $l_html .= chr(13).'            <td><b>Descrição</b></td>';
      $l_html .= chr(13).'            <td><b>Responsável</b></td>';                   
      $l_html .= chr(13).'            <td><b>Estratégia</b></td>';
      $l_html .= chr(13).'            <td><b>Ação de Resposta</b></td>';
      $l_html .= chr(13).'            <td><b>Fase atual</b></td>';
      $l_html .= chr(13).'          </tr>';
      $w_cor=$conTrBgColor;
      foreach ($RS as $row) {
        $l_html .= chr(13).'      <tr valign="top">';
        $l_html .= chr(13).'        <td nowrap>';
        if (f($row,'risco')=='S') {
          if (f($row,'fase_atual')<>'C') {
            if (f($row,'criticidade')==1)     $l_html .= chr(13).'          <img title="Risco de baixa criticidade" src="'.$conRootSIW.$conImgRiskLow.'" border=0 align="middle">&nbsp;';
            elseif (f($row,'criticidade')==2) $l_html .= chr(13).'          <img title="Risco de média criticidade" src="'.$conRootSIW.$conImgRiskMed.'" border=0 align="middle">&nbsp;';
            else                              $l_html .= chr(13).'          <img title="Risco de alta criticidade" src="'.$conRootSIW.$conImgRiskHig.'" border=0 align="middle">&nbsp;';
          }
        } else {
          if (f($row,'fase_atual')<>'C') {
            if (f($row,'criticidade')==1)     $l_html .= chr(13).'          <img title="Problema de baixa criticidade" src="'.$conRootSIW.$conImgProblem.'" border=0 align="middle">&nbsp;';
            elseif (f($row,'criticidade')==2) $l_html .= chr(13).'          <img title="Problema de média criticidade" src="'.$conRootSIW.$conImgProblem.'" border=0 align="middle">&nbsp;';
            else                              $l_html .= chr(13).'          <img title="Problema de alta criticidade" src="'.$conRootSIW.$conImgProblem.'" border=0 align="middle">&nbsp;';
          }
        }
        $l_html .= chr(13).'          '.f($row,'nm_tipo_restricao').'</td>';
        $l_html .= chr(13).'        <td>'.f($row,'nm_tipo').'</td>';
        $l_html .= chr(13).'        <td>'.ExibeRestricao('V',$w_dir_volta,$w_cliente,f($row,'descricao'),f($row,'chave'),f($row,'chave_aux'),$TP,null).'</td>';
        $l_html .= chr(13).'        <td>'.f($row,'nm_resp').'</td>';
        $l_html .= chr(13).'        <td>'.f($row,'nm_estrategia').'</td>';
        $l_html .= chr(13).'        <td>'.CRLF2BR(f($row,'acao_resposta')).'</td>';
        $l_html .= chr(13).'        <td>'.CRLF2BR(f($row,'nm_fase_atual')).'</td>';
        $l_html .= chr(13).'      </tr>';
      } 
      $l_html .= chr(13).'         </table></td></tr>';
    }
  }  
  if ($w_tipo_visao!=2 && ($operacao=='T')) {
  // Interessados na execução do projeto (formato novo)
    $RS1 = db_getSolicInter::getInstanceOf($dbms,$l_chave,null,'LISTA');
    $RS1 = SortArray($RS1,'ordena','asc','or_tipo_interessado','asc','nome','asc');
    if (count($RS1)>0 && $l_nome_menu['RESP']!='') {
      $l_cont = 0;
      $l_novo = 'N';
      // Tratamento para interessados no formato antigo e no novo.
      // A stored procedure dá preferência para o formato novo.
      foreach($RS1 as $row) {
        if (nvl(f($row,'sq_solicitacao_interessado'),'nulo')!='nulo') {
          if ($l_cont==0) {
            $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>'.$l_nome_menu['RESP'].'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
            $l_html.=chr(13).'   <tr><td colspan="2"><div align="center">';
            $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
            $l_html.=chr(13).'       <tr><td bgColor="#f0f0f0" width="10%" nowrap><div align="center"><b>Tipo de envolvimento</b></div></td>';
            $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="center"><b>Pessoa</b></div></td>';
            $l_html.=chr(13).'       </tr>';
            $l_cont = 1;
            $l_novo = 'S';
          }
          $l_html.=chr(13).'       <tr><td nowrap>'.f($row,'nm_tipo_interessado').'</td>';
          $l_html.=chr(13).'           <td>'.ExibePessoa('../',$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'nome').' ('.f($row,'lotacao').')').'</td>';
          $l_html.=chr(13).'      </tr>';
        } else {
          if ($l_cont==0) {
            $l_html.=chr(13).'        <tr><td colspan="2"><br><font size="2"><b>'.$l_nome_menu['RESP'].'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
            $l_html .= chr(13).'      <tr><td align="center" colspan="2">';
            $l_html.=chr(13).'          <table width=100%  border="1" bordercolor="#00000">';
            $l_html .= chr(13).'          <tr><td bgColor="#f0f0f0"><b>Nome</b></div></td>';
            $l_html .= chr(13).'            <td bgColor="#f0f0f0"><b>Tipo de visão</b></div></td>';
            $l_html .= chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>Envia e-mail</b></div></td>';
            $l_html .= chr(13).'          </tr>';
            $w_cor=$conTrBgColor;
            $l_cont = 1;
          }
          $l_html .= chr(13).'      <tr>';
          if ($l_novo=='S') {
            $l_html .= chr(13).'        <td align="center">*** ALTERAR ***</td>';
            $l_html .= chr(13).'        <td>'.ExibePessoa(null,$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'nome').' ('.f($row,'lotacao').')').'</td>';
          } else {
            $l_html .= chr(13).'        <td>'.ExibePessoa(null,$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'nome').' ('.f($row,'lotacao').')').'</td>';
            $l_html .= chr(13).'        <td>'.RetornaTipoVisao(f($row,'tipo_visao')).'</td>';
            $l_html .= chr(13).'        <td align="center">'.str_replace('N','Não',str_replace('S','Sim',f($row,'envia_email'))).'</td>';
          }
          $l_html .= chr(13).'      </tr>';
        } 
      }
      $l_html.=chr(13).'         </table></div></td></tr>';
    } 
    // Interessados na execução do projeto (formato antigo)
    $RS = db_getSolicInter::getInstanceOf($dbms,$l_chave,null,'LISTA');
    $RS = SortArray($RS,'nome','asc');
    if (count($RS)>0 && $l_nome_menu['INTERES']!='') {
      foreach ($RS as $row) {
        if ($l_cont==0) {
          $l_html.=chr(13).'        <tr><td colspan="2"><br><font size="2"><b>'.$l_nome_menu['INTERES'].'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
          $l_html .= chr(13).'      <tr><td align="center" colspan="2">';
          $l_html.=chr(13).'          <table width=100%  border="1" bordercolor="#00000">';
          $l_html .= chr(13).'          <tr><td bgColor="#f0f0f0"><b>Nome</b></div></td>';
          $l_html .= chr(13).'            <td bgColor="#f0f0f0"><b>Tipo de visão</b></div></td>';
          $l_html .= chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>Envia e-mail</b></div></td>';
          $l_html .= chr(13).'          </tr>';
          $w_cor=$conTrBgColor;
          $l_cont = 1;
        }
        $l_html .= chr(13).'      <tr>';
        $l_html .= chr(13).'        <td>'.ExibePessoa(null,$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'nome').' ('.f($row,'lotacao').')').'</td>';
        $l_html .= chr(13).'        <td>'.RetornaTipoVisao(f($row,'tipo_visao')).'</td>';
        $l_html .= chr(13).'        <td align="center">'.str_replace('N','Não',str_replace('S','Sim',f($row,'envia_email'))).'</td>';
        $l_html .= chr(13).'      </tr>';
      } 
      $l_html .= chr(13).'         </table></td></tr>';
    } 
    // Áreas envolvidas na execução do projeto
    $RS = db_getSolicAreas::getInstanceOf($dbms,$l_chave,null,'LISTA');
    $RS = SortArray($RS,'nome','asc');
    if (count($RS)>0 && $l_nome_menu['AREAS']!='') {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>'.$l_nome_menu['AREAS'].'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html .= chr(13).'      <tr><td align="center" colspan="2">';
      $l_html .=chr(13).'          <table width=100%  border="1" bordercolor="#00000">';
      $l_html .= chr(13).'          <tr><td bgColor="#f0f0f0"><div align="center"><b>Parte interessada</b></div></td>';
      $l_html .= chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>Interesse</b></div></td>';
      $l_html .= chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>Influência</b></div></td>';
      $l_html .= chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>Papel</b></div></td>';
      $l_html .= chr(13).'          </tr>';
      $w_cor=$conTrBgColor;
      foreach ($RS as $row) {
        $l_html .= chr(13).'      <tr valign="top">';
        $l_html.=chr(13).'           <td>'.ExibeUnidadePacote('L',$w_cliente, $l_chave,f($row,'sq_solicitacao_interessado'), f($row,'sq_unidade'),$TP,f($row,'nome')).'</td>';
        $l_html .= chr(13).'        <td align="center">'.Nvl(f($row,'nm_interesse'),'---').'</td>';
        $l_html .= chr(13).'        <td align="center">'.Nvl(f($row,'nm_influencia'),'---').'</td>';          
        $l_html .= chr(13).'        <td>'.crlf2br(f($row,'papel')).'</td>';
        $l_html .= chr(13).'      </tr>';
      } 
      $l_html .= chr(13).'         </table></td></tr>';
    }
  }

  if ($w_tipo_visao!=2 && ($operacao=='L' || $operacao=='T')) {
    // Recursos envolvidos na execução do projeto
    $RS = db_getSolicRecurso::getInstanceOf($dbms,$l_chave,null,'LISTA');
    $RS = SortArray($RS,'tipo','asc','nome','asc');
    if (count($RS)>0 && $l_nome_menu['RECURSO']!='') {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>'.$l_nome_menu['RECURSO'].'<hr color=#000000 SIZE=1></b></font></td></tr>';
      $l_html .= chr(13).'      <tr><td align="center" colspan="2">';
      $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
      $l_html.=chr(13).'         <tr><td bgColor="#f0f0f0"><div align="center"><b>Tipo</b></div></td>';
      $l_html.=chr(13).'             <td bgColor="#f0f0f0"><div align="center"><b>Nome</b></div></td>';
      $l_html.=chr(13).'             <td bgColor="#f0f0f0"><div align="center"><b>Finalidade</b></div></td>';
      $l_html .= chr(13).'       </tr>';
      $w_cor=$conTrBgColor;
      foreach ($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor; 
        $l_html .= chr(13).'      <tr>';
        $l_html .= chr(13).'        <td>'.RetornaTipoRecurso(f($row,'tipo')).'</td>';
        $l_html .= chr(13).'        <td>'.f($row,'nome').'</td>';
        $l_html .= chr(13).'        <td>'.CRLF2BR(Nvl(f($row,'finalidade'),'---')).'</td>';
        $l_html .= chr(13).'      </tr>';
      } 
      $l_html .= chr(13).'         </table></td></tr>';
    }     
  }
  if ($operacao=='V' || $operacao=='T') {
    // Se for listagem dos dados
    if($w_tipo_visao!=2) {
      // Arquivos vinculados
      $RS = db_getSolicAnexo::getInstanceOf($dbms,$l_chave,null,$w_cliente);
      $RS = SortArray($RS,'nome','asc');
      if (count($RS)>0 && $l_nome_menu['ANEXO']!='') {
        $l_html .= chr(13).'        <tr><td colspan=2><br><font size="2"><b>'.$l_nome_menu['ANEXO'].'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
        $l_html .= chr(13).'      <tr><td align="center" colspan="2">';
        $l_html .= chr(13).'          <table width=100%  border="1" bordercolor="#00000">';
        $l_html .= chr(13).'            <tr><td bgColor="#f0f0f0"><div align="center"><b>Título</b></div></td>';
        $l_html .= chr(13).'              <td bgColor="#f0f0f0"><div align="center"><b>Descrição</b></div></td>';
        $l_html .= chr(13).'              <td bgColor="#f0f0f0"><div align="center"><b>Tipo</b></div></td>';
        $l_html .= chr(13).'              <td bgColor="#f0f0f0"><div align="center"><b>KB</b></div></td>';
        $l_html .= chr(13).'            </tr>';
        $w_cor=$conTrBgColor;
        foreach ($RS as $row) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor; 
          $l_html .= chr(13).'      <tr>';
          $l_html .= chr(13).'        <td>'.LinkArquivo('HL',$w_cliente,f($row,'chave_aux'),'_blank','Clique para exibir o arquivo em outra janela.',f($row,'nome'),null).'</td>';
          $l_html .= chr(13).'        <td>'.Nvl(f($row,'descricao'),'---').'</td>';
          $l_html .= chr(13).'        <td>'.f($row,'tipo').'</td>';
          $l_html .= chr(13).'        <td align="right">'.round(f($row,'tamanho')/1024,1).'&nbsp;</td>';
          $l_html .= chr(13).'      </tr>';
        } 
        $l_html .= chr(13).'         </table></td></tr>';
      } 
    }
    // Encaminhamentos
    if($w_tipo_visao!=2) {
      $RS = db_getSolicLog::getInstanceOf($dbms,$l_chave,null,'LISTA');
      $RS = SortArray($RS,'phpdt_data','desc','sq_siw_solic_log','desc');
      $l_html.=chr(13).'   <tr><td colspan="2"><br><font size="2"><b>OCORRÊNCIAS E ANOTAÇÕES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html.=chr(13).'   <tr><td colspan="2"><div align="center">';
      $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
      $l_html.=chr(13).'       <tr><td bgColor="#f0f0f0"><div align="center"><b>Data</b></div></td>';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="center"><b>Ocorrência/Anotação</b></div></td>';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="center"><b>Responsável</b></div></td>';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="center"><b>Fase/Destinatário</b></div></td>';
      $l_html.=chr(13).'       </tr>';
    
      if (count($RS)==0) {
        $l_html .= chr(13).'      <tr><td colspan=6 align="center"><b>Não foram encontrados encaminhamentos.</b></td></tr>';
      } else {
        $l_html .= chr(13).'      <tr>';
        $w_cor=$conTrBgColor;
        $i = 0;
        foreach ($RS as $row) {
          if ($i==0) {
            $l_html .= chr(13).'        <td colspan=6>Fase atual: <b>'.f($row,'fase').'</b></td>';
            $i = 1;
          }
          if ($operacao=='T' || Nvl(f($row,'sq_projeto_log'),'')=='' || Nvl(f($row,'destinatario'),'')>'') {
            $l_html = $l_html.chr(13).'      <tr valign="top">';
            $l_html .= chr(13).'        <td nowrap>'.FormataDataEdicao(f($row,'phpdt_data'),3).'</td>';
            if (Nvl(f($row,'caminho'),'')>'') $l_html .= chr(13).'        <td>'.CRLF2BR(Nvl(f($row,'despacho'),'---').'<br>'.LinkArquivo('HL',$w_cliente,f($row,'sq_siw_arquivo'),'_blank','Clique para exibir o anexo em outra janela.','Anexo - '.f($row,'tipo').' - '.round(f($row,'tamanho')/1024,1).' KB',null)).'</td>';
            else                              $l_html .= chr(13).'        <td>'.CRLF2BR(Nvl(f($row,'despacho'),'---')).'</td>';
            $l_html .= chr(13).'        <td nowrap>'.ExibePessoa(null,$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'responsavel')).'</td>';
            if ((Nvl(f($row,'sq_projeto_log'),'')>'') && (Nvl(f($row,'destinatario'),'')>''))      $l_html .= chr(13).'        <td nowrap>'.ExibePessoa(null,$w_cliente,f($row,'sq_pessoa_destinatario'),$TP,f($row,'destinatario')).'</td>';
            elseif ((Nvl(f($row,'sq_projeto_log'),'')>'')  && (Nvl(f($row,'destinatario'),'')==''))$l_html .= chr(13).'        <td nowrap>Anotação</td>';
            else                                                                                   $l_html .= chr(13).'        <td nowrap>'.Nvl(f($row,'tramite'),'---').'</td>';
            $l_html .= chr(13).'      </tr>';
          }
        } 
        $l_html .= chr(13).'         </table></td></tr>';
      } 
    }
    $l_html .= chr(13).'</table>';
  } 
  $l_html .= chr(13).'</table>';
  return $l_html;
} 
?>