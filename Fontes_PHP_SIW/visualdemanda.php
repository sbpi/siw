<?
// =========================================================================
// Rotina de visualiza��o dos dados da demanda
// -------------------------------------------------------------------------
function VisualDemanda($l_chave,$operacao,$w_usuario) {
  extract($GLOBALS);
  include_once($w_dir_volta.'classes/sp/db_getSolicRecursos.php');
  include_once($w_dir_volta.'classes/sp/db_getSolicLog.php');

  //Recupera as informa��es do sub-menu
  $RS = db_getLinkSubMenu::getInstanceOf($dbms, $w_cliente, f($RS_Menu,'sigla'));
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

  $l_html = '';
  // Recupera os dados da demanda
  $RS = db_getSolicData::getInstanceOf($dbms,$l_chave,'GDGERAL');

  // Recupera o tipo de vis�o do usu�rio
  if (Nvl(f($RS,'solicitante'),0)==$w_usuario || 
     Nvl(f($RS,'executor'),0)==$w_usuario || 
     Nvl(f($RS,'cadastrador'),0)==$w_usuario || 
     Nvl(f($RS,'titular'),0)==$w_usuario || 
     Nvl(f($RS,'substituto'),0)==$w_usuario || 
     Nvl(f($RS,'tit_exec'),0)==$w_usuario || 
     Nvl(f($RS,'subst_exec'),0)==$w_usuario || 
     SolicAcesso($l_chave,$w_usuario)>=8)
  {
    // Se for solicitante, executor ou cadastrador, tem vis�o completa
    $w_tipo_visao=0;
  } else {
    $RSQuery = db_getSolicInter::getInstanceOf($dbms,$l_chave,$w_usuario,'REGISTRO');
    if (count($RSQuery)>0) {
      // Se for interessado, verifica a vis�o cadastrada para ele.
      $w_tipo_visao = f($RSQuery,'tipo_visao');
    } else {
      $RSQuery = db_getSolicAreas::getInstanceOf($dbms,$l_chave,$sq_lotacao_session,'REGISTRO');
      if (!($RSQuery==0)) {
        // Se for de uma das unidades envolvidas, tem vis�o parcial
        $w_tipo_visao=1;
      } else {
        // Caso contr�rio, tem vis�o resumida
        $w_tipo_visao=2;
      } 
      if (SolicAcesso($l_chave,$w_usuario)>2) $w_tipo_visao=1;
    } 
  } 

  // Se for listagem ou envio, exibe os dados de identifica��o da demanda
  if ($operacao=='L' || $operacao=='V') {
    // Se for listagem dos dados
    $l_html.=chr(13).'<div align=center><center>';
    $l_html.=chr(13).'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
    $l_html.=chr(13).'<tr><td align="center">';

    $l_html.=chr(13).'    <table width="99%" border="0">';
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><b>['.$l_chave.'] '.crlf2br(f($RS,'assunto')).'</font></div></td></tr>';
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    // Identifica��o da demanda
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>'.$l_nome_menu['GERAL'].'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
    if (nvl(f($RS,'nm_projeto'),'')>'') {
      $l_html.=chr(13).'      <tr><td width="30%"><b>Projeto: </b></td>';
      $l_html.=chr(13).'        <td>'.f($RS,'nm_projeto').'  ('.f($RS,'sq_solic_pai').')</td></tr>';
    } 

    if (nvl(f($RS,'nm_etapa'),'')>'') {
      $l_html.=chr(13).'      <tr><td valign="top"><b>Etapa: </b></td>';
      $l_html.=chr(13).'        <td>'.MontaOrdemEtapa(f($RS,'sq_projeto_etapa')).'. '.f($RS,'nm_etapa').'</td></tr>';
    } 

    if (nvl(f($RS,'ds_restricao'),'')>'') {
      $l_html.=chr(13).'      <tr><td valign="top"><b>'.f($RS,'nm_tipo_restricao').': </b></td>';
      $l_html.=chr(13).'        <td>'.f($RS,'ds_restricao').'</td></tr>';
    } 

    if (nvl(f($RS,'sq_demanda_pai'),'')>'') {
      // Recupera os dados da demanda
      $RS1 = db_getSolicData::getInstanceOf($dbms,f($RS,'sq_demanda_pai'),'GDGERAL');
      $l_html.=chr(13).'      <tr><td valign="top"><b>Tarefa pai: </b></td>';
      $l_html.=chr(13).'        <td><A class="HL" HREF="'.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($RS1,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informa��es deste registro." target="_blank">'.f($RS1,'sq_siw_solicitacao').'</a> - '.f($RS1,'assunto').' </td></tr>';
    } 

    // Se a classifica��o foi informada, exibe.
    if (nvl(f($RS,'sq_cc'),'')>'') {
      $l_html.=chr(13).'      <tr valign="top"><td><b>Classifica��o:</b></td>';
      $l_html.=chr(13).'        <td>'.f($RS,'cc_nome').' </td></tr>';
    } 
    $l_html.=chr(13).'        <tr valign="top"><td><b>Local de execu��o:</b></td>';
      $l_html.=chr(13).'        <td>'.f($RS,'nm_cidade').' ('.f($RS,'co_uf').')</td></tr>';
    if (Nvl(f($RS,'proponente'),'')>'') {
      $l_html.=chr(13).'      <tr valign="top"><td><b>Proponente externo:</b></td>';
      $l_html.=chr(13).'        <td>'.f($RS,'proponente').' </td></tr>';
    } else {
      $l_html.=chr(13).'      <tr valign="top"><td><b>Proponente externo:</b></td>';
      $l_html.=chr(13).'        <td>--- </td></tr>';
    } 
    $l_html.=chr(13).'        <tr><td><b>Respons�vel:</b></td>';
    $l_html.=chr(13).'          <td>'.ExibePessoa(null,$w_cliente,f($RS,'solicitante'),$TP,f($RS,'nm_sol')).'</td></tr>';
    $l_html.=chr(13).'        <tr><td><b>Unidade respons�vel:</b></td>';
    $l_html.=chr(13).'          <td>'.ExibeUnidade(null,$w_cliente,f($RS,'nm_unidade_resp'),f($RS,'sq_unidade_resp'),$TP).'</td></tr>';

    if ($w_tipo_visao==0) {
      // Se for vis�o completa
      $l_html.=chr(13).'      <tr valign="top"><td><B>Or�amento dispon�vel: </b></td>';
      $l_html.=chr(13).'        <td>'.number_format(f($RS,'valor'),2,',','.').' </td></tr>';
    } 
    $l_html.=chr(13).'        <tr><td><b>In�cio previsto:</b></td>';
    $l_html.=chr(13).'          <td>'.FormataDataEdicao(f($RS,'inicio')).' </td></tr>';
    $l_html.=chr(13).'        <tr><td><b>T�rmino previsto:</b></td>';
    $l_html.=chr(13).'          <td>'.FormataDataEdicao(f($RS,'fim')).' </td></tr>';
    $l_html.=chr(13).'        <tr><td><b>Prioridade:</b></td>';
    $l_html.=chr(13).'          <td>'.RetornaPrioridade(f($RS,'prioridade')).' </td></tr>';
    $l_html.=chr(13).'        <tr valign="top"><td><b>Palavras-chave:</b></td>';
    $l_html.=chr(13).'          <td>'.nvl(f($RS,'palavra_chave'),'---').' </td></tr>';
    $RSQuery = db_getSolicList::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,f($RS,'sigla'),4,
            null,null,null,null,null,null,null,null,null,null,null, null, null, null, null, null, null,
            null, null, null, null,null, null, null, f($RS,'sq_siw_solicitacao'), null);
    $RSQuery = SortArray($RSQuery,'fim','asc','prioridade','asc');
    if (count($RSQuery)>0) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>TAREFAS SUBORDINADAS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html.=chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
      $l_html.=chr(13).'        <tr><td align="right"><b>Registros: '.count($RSQuery);
      $l_html.=chr(13).'        <tr><td align="center" colspan=3>';
      $l_html.=chr(13).'          <table width=100%  border="1" bordercolor="#00000">';
      $l_html.=chr(13).'            <tr align="center">';
      $l_html.=chr(13).'              <td bgColor="#f0f0f0"><div><b>N�</b></div></td>';
      $l_html.=chr(13).'              <td bgColor="#f0f0f0"><div><b>Etapa</b></div></td>';
      $l_html.=chr(13).'              <td bgColor="#f0f0f0"><div><b>Respons�vel</b></div></td>';
      $l_html.=chr(13).'              <td bgColor="#f0f0f0"><div><b>Detalhamento</b></div></td>';
      $l_html.=chr(13).'              <td bgColor="#f0f0f0"><div><b>Fim previsto</b></div></td>';
      $l_html.=chr(13).'              <td bgColor="#f0f0f0"><div><b>Fase atual</b></div></td>';
      $l_html.=chr(13).'            </tr>';
      foreach($RSQuery as $row) {
        $l_html.=chr(13).'        <tr valign="top">';
        $l_html.=chr(13).'          <td nowrap>';
        $l_html.=chr(13).ExibeImagemSolic(f($row,'sigla'),f($row,'inicio'),f($row,'fim'),f($row,'inicio_real'),f($row,'fim_real'),f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null);
        $l_html.=chr(13).'          <A class="HL" HREF="'.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informa��es deste registro." target="_blank">'.f($row,'sq_siw_solicitacao').'&nbsp;</a>';
        if (nvl(f($row,'sq_projeto_etapa'),'nulo')!='nulo') {
          $l_html.=chr(13).'            <td>'.ExibeEtapa('V',f($row,'sq_solic_pai'),f($row,'sq_projeto_etapa'),'Volta',10,MontaOrdemEtapa(f($row,'sq_projeto_etapa')).' - '.f($row,'nm_etapa'),$TP,$SG).'</td>';
        } else {
          $l_html.=chr(13).'            <td>---</td>';
        } 
        $l_html.=chr(13).'          <td>'.ExibePessoa(null,$w_cliente,f($row,'solicitante'),$TP,f($row,'nm_solic')).'</td>';
        if (strlen(Nvl(f($row,'assunto'),'-'))>50) $w_titulo = substr(Nvl(f($row,'assunto'),'-'),0,50).'...'; else $w_titulo = Nvl(f($row,'assunto'),'-');
        $l_html.=chr(13).'          <td title="'.htmlspecialchars(f($row,'assunto')).'">'.htmlspecialchars($w_titulo).'</td>';
        $l_html.=chr(13).'          <td align="center">&nbsp;'.Nvl(FormataDataEdicao(f($row,'fim')),'-').'</td>';
        $l_html.=chr(13).'          <td>'.f($row,'nm_tramite').'</td>';
        $l_html.=chr(13).'        </tr>';
        $l_html.=chr(13).'          </table>';
        $l_html.=chr(13).'      </table>';
      } 
    }

    if ($w_tipo_visao==0 || $w_tipo_visao==1) {
      // Informa��es adicionais
      if (Nvl(f($RS,'descricao'),'')>'' || Nvl(f($RS,'justificativa'),'')>'') {
        $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>INFORMA��ES ADICIONAIS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
        if (Nvl(f($RS,'descricao'),'')>''){ 
          $l_html.=chr(13).'      <tr valign="top"><td><b>Resultados da demanda:</b></td>';
          $l_html.=chr(13).'        <td>'.CRLF2BR(f($RS,'descricao')).' </td></tr>';
        }
        if ($w_tipo_visao==0 && Nvl(f($RS,'justificativa'),'')>'') {
          // Se for vis�o completa
          $l_html.=chr(13).'      <tr valign="top"><td><b>Observa��es:</b></td>';
          $l_html.=chr(13).'            <td>'.CRLF2BR(f($RS,'justificativa')).' </td></tr>';
        } 
      } 
    } 

    // Recursos
    $RS1 = db_getSolicRecursos::getInstanceOf($dbms,$w_cliente,$w_usuario,$l_chave,null,null,null,null,null,null,null,null,null,null,null);
    $RS1 = SortArray($RS1,'nm_tipo_recurso','asc','nm_recurso','asc'); 
    if (count($RS1)>0 && $l_nome_menu['RECSOLIC']!='') {
      $l_html .= chr(13).'      <tr><td colspan="2"><br><font size="2"><b>'.$l_nome_menu['RECSOLIC'].'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html .= chr(13).'      <tr><td align="center" colspan="2">';
      $l_html .= chr(13).'          <table width=100%  border="1" bordercolor="#00000">';     
      $l_html .= chr(13).'          <tr align="center" valign="top" bgColor="#f0f0f0">';
      $l_html .= chr(13).'            <td><b>Tipo</b></td>';
      $l_html .= chr(13).'            <td><b>C�digo</b></td>';
      $l_html .= chr(13).'            <td><b>Recurso</b></td>';
      $l_html .= chr(13).'            <td width="1%" nowrap><b>U.M.</b></td>';
      $l_html .= chr(13).'          </tr>';
      $w_cor=$conTrBgColor;
      foreach ($RS1 as $row) {
        $l_html .= chr(13).'      <tr>';
        $l_html .= chr(13).'        <td>'.f($row,'nm_tipo_completo').'</td>';
        $l_html .= chr(13).'        <td>'.nvl(f($row,'codigo'),'---').'</td>';
        $l_html .= chr(13).'        <td>'.ExibeRecurso($w_dir_volta,$w_cliente,f($row,'nm_recurso'),f($row,'sq_recurso'),$TP,$l_chave).'</td>';
        $l_html .= chr(13).'        <td align="center" nowrap>'.f($row,'nm_unidade_medida').'</td>';        
        $l_html .= chr(13).'      </tr>';
      } 
      $l_html .= chr(13).'         </table></td></tr>';
      $l_html .= chr(13).'<tr><td colspan=3><table border=0>';
      $l_html .= chr(13).'  <tr><td align="right">U.M.<td>Unidade de aloca��o do recurso';
      $l_html .= chr(13).'  </table>';
    }

    // Dados da conclus�o da demanda, se ela estiver nessa situa��o
    if (f($RS,'concluida')=='S' && Nvl(f($RS,'data_conclusao'),'')>'') {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS DA CONCLUS�O<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
      $l_html.=chr(13).'      <tr><td valign="top" colspan="2">';
      $l_html.=chr(13).'      <tr><td><b>In�cio previsto:</b></td>';
      $l_html.=chr(13).'        <td>'.FormataDataEdicao(f($RS,'inicio_real')).' </td></tr>';
      $l_html.=chr(13).'      <tr><td><b>T�rmino previsto:</b></td>';
      $l_html.=chr(13).'        <td>'.FormataDataEdicao(f($RS,'fim_real')).' </td></tr>';
      if ($w_tipo_visao==0) {
        $l_html.=chr(13).'    <tr><td><b>Custo real:</b></td>';
        $l_html.=chr(13).'      <td>'.number_format(f($RS,'custo_real'),2,',','.').' </td></tr>';
      } 
      if ($w_tipo_visao==0) {
        $l_html.=chr(13).'      <tr valign="top"><td valign="top"><b>Nota de conclus�o:</b></td>';
        $l_html.=chr(13).'        <td>'.CRLF2BR(f($RS,'nota_conclusao')).' </td></tr>';
      } 
    } 
  } 

  // Se for listagem, exibe os outros dados dependendo do tipo de vis�o  do usu�rio
  if ($operacao=='L' && $w_tipo_visao!=2) {
    if (f($RS,'aviso_prox_conc')=='S') {
      // Configura��o dos alertas de proximidade da data limite para conclus�o da demanda
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ALERTAS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
      $l_html.=chr(13).'      <tr><td valign="top" colspan="2">';
      $l_html.=chr(13).'      <tr><td valign="top"><b>Emite aviso:</b></td>';
      $l_html.=chr(13).'        <td>'.str_replace('N','N�o',str_replace('S','Sim',f($RS,'aviso_prox_conc'))).' </td></tr>';
      $l_html.=chr(13).'      <tr><td valign="top"><b>Dias:</b></td>';
      $l_html.=chr(13).'        <td>'.f($RS,'dias_aviso').' </td></tr>';

    } 

    // Interessados na execu��o do projeto (formato novo)
    $RS1 = db_getSolicInter::getInstanceOf($dbms,$l_chave,null,'LISTA');
    $RS1 = SortArray($RS1,'ordena','asc','or_tipo_interessado','asc','nome','asc');
    if (count($RS1)>0 && $l_nome_menu['RESP']!='') {
      $l_cont = 0;
      $l_novo = 'N';
      // Tratamento para interessados no formato antigo e no novo.
      // A stored procedure d� prefer�ncia para o formato novo.
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
            $l_html .= chr(13).'          <tr><td bgColor="#f0f0f0"><div><b>Nome</b></div></td>';
            $l_html .= chr(13).'            <td bgColor="#f0f0f0"><div><b>Tipo de vis�o</b></div></td>';
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
            $l_html .= chr(13).'        <td align="center">'.str_replace('N','N�o',str_replace('S','Sim',f($row,'envia_email'))).'</td>';
          }
          $l_html .= chr(13).'      </tr>';
        } 
      }
      $l_html.=chr(13).'         </table></div></td></tr>';
    } 

    // Interessados na execu��o da demanda (formato antigo)
    $RS1 = db_getSolicInter::getInstanceOf($dbms,$l_chave,null,'LISTA');
    $RS1 = SortArray($RS1,'nome','asc');
    if (count($RS1)>0 && $l_nome_menu['INTERES']!='') {
      foreach ($RS1 as $row) {
        if ($l_cont==0) {
          $l_html.=chr(13).'        <tr><td colspan="2"><br><font size="2"><b>'.$l_nome_menu['INTERES'].'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
          $l_html .= chr(13).'      <tr><td align="center" colspan="2">';
          $l_html.=chr(13).'          <table width=100%  border="1" bordercolor="#00000">';
          $l_html .= chr(13).'          <tr><td bgColor="#f0f0f0"><div><b>Nome</b></div></td>';
          $l_html .= chr(13).'            <td bgColor="#f0f0f0"><div><b>Tipo de vis�o</b></div></td>';
          $l_html .= chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>Envia e-mail</b></div></td>';
          $l_html .= chr(13).'          </tr>';
          $w_cor=$conTrBgColor;
          $l_cont = 1;
        }
        $l_html .= chr(13).'      <tr>';
        $l_html .= chr(13).'        <td>'.ExibePessoa(null,$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'nome').' ('.f($row,'lotacao').')').'</td>';
        $l_html .= chr(13).'        <td>'.RetornaTipoVisao(f($row,'tipo_visao')).'</td>';
        $l_html .= chr(13).'        <td align="center">'.str_replace('N','N�o',str_replace('S','Sim',f($row,'envia_email'))).'</td>';
        $l_html .= chr(13).'      </tr>';
      } 
      $l_html .= chr(13).'         </table></td></tr>';
    } 

    // �reas envolvidas na execu��o da demanda
    $RS1 = db_getSolicAreas::getInstanceOf($dbms,$l_chave,null,'LISTA');
    $RS1 = SortArray($RS1,'nome','asc');
    if (count($RS1)>0 && $l_nome_menu['AREAS']!='') {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>'.$l_nome_menu['AREAS'].'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
      $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">';
      $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
      $l_html.=chr(13).'          <tr align="center">';
      $l_html.=chr(13).'            <td bgColor="#f0f0f0" width="40%"><div><b>Nome</b></div></td>';
      $l_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Papel</b></div></td>';
      $l_html.=chr(13).'          </tr>';
      $w_cor=$conTrBgColor;
      foreach($RS1 as $row) {
        $l_html.=chr(13).'      <tr valign="top">';
        $l_html.=chr(13).'        <td>'.f($row,'nome').'</td>';
        $l_html.=chr(13).'        <td>'.f($row,'papel').'</td>';
        $l_html.=chr(13).'      </tr>';
      } 
      $l_html.=chr(13).'         </table></td></tr>';
    } 
  } 

  if ($operacao=='L' || $operacao=='V') {
    // Se for listagem dos dados
    // Arquivos vinculados
    $RS1 = db_getSolicAnexo::getInstanceOf($dbms,$l_chave,null,$w_cliente);
    $RS1 = SortArray($RS1,'nome','asc');
    if (count($RS1)>0 && $l_nome_menu['ANEXO']!='') {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>'.$l_nome_menu['ANEXO'].'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
      $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">';
      $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
      $l_html.=chr(13).'          <tr align="center">';
      $l_html.=chr(13).'             <td bgColor="#f0f0f0" width="40%"><div><b>T�tulo</b></div></td>';
      $l_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Descri��o</b></div></td>';
      $l_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Tipo</b></div></td>';
      $l_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>KB</b></div></td>';
      $l_html.=chr(13).'          </tr>';
      $w_cor=$conTrBgColor;
      foreach($RS1 as $row) {
        $l_html.=chr(13).'      <tr valign="top">';
        $l_html.=chr(13).'        <td>'.LinkArquivo('HL',$w_cliente,f($row,'chave_aux'),'_blank','Clique para exibir o arquivo em outra janela.',f($row,'nome'),null).'</td>';
        $l_html.=chr(13).'        <td>'.Nvl(f($row,'descricao'),'---').'</td>';
        $l_html.=chr(13).'        <td>'.f($row,'tipo').'</td>';
        $l_html.=chr(13).'        <td align="right">'.round(f($row,'tamanho')/1024,1).'&nbsp;</td>';
        $l_html.=chr(13).'      </tr>';
      } 
      $l_html.=chr(13).'         </table></td></tr>';
    } 

    // Encaminhamentos
    $RS = db_getSolicLog::getInstanceOf($dbms,$l_chave,null,'LISTA');
    $RS = SortArray($RS,'phpdt_data','desc','sq_siw_solic_log','desc');
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>OCORR�NCIAS E ANOTA��ES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">';
    $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';    
    $l_html.=chr(13).'          <tr align="center">';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Data</b></div></td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Despacho/Observa��o</b></div></td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Respons�vel</b></div></td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Fase / Destinat�rio</b></div></td>';
    $l_html.=chr(13).'          </tr>';
    if (count($RS)<=0) {
      $l_html.=chr(13).'      <tr><td colspan=6 align="center"><font size="1"><b>N�o foram encontrados encaminhamentos.</b></td></tr>';
    } else {
      $l_html.=chr(13).'      <tr valign="top">';
      $w_cor=$conTrBgColor;
      $i = 0;
      foreach($RS as $row) {
        if ($i==0) {
          $l_html.=chr(13).'        <td colspan=6><font size="1">Fase atual: <b>'.f($row,'fase').'</b></td>';
          $i = 1;
        }
        $l_html.=chr(13).'      <tr valign="top">';
        $l_html.=chr(13).'        <td nowrap><font size="1">'.FormataDataEdicao(f($row,'phpdt_data'),3).'</td>';
        if (Nvl(f($row,'caminho'),'')>'') {
          $l_html.=chr(13).'      <td><font size="1">'.CRLF2BR(Nvl(f($row,'despacho'),'---').'<br>'.LinkArquivo('HL',$w_cliente,f($row,'sq_siw_arquivo'),'_blank','Clique para exibir o anexo em outra janela.','Anexo - '.f($row,'tipo').' - '.round(f($row,'tamanho')/1024,1).' KB',null)).'</td>';
        } else {
          $l_html.=chr(13).'      <td><font size="1">'.CRLF2BR(Nvl(f($row,'despacho'),'---')).'</td>';
        } 
        $l_html.=chr(13).'        <td nowrap><font size="1">'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'responsavel')).'</td>';
        if (nvl(f($row,'sq_demanda_log'),'')>'' && nvl(f($row,'destinatario'),'')>'') {
          $l_html.=chr(13).'      <td nowrap><font size="1">'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'sq_pessoa_destinatario'),$TP,f($row,'destinatario')).'</td>';
        } elseif (nvl(f($row,'sq_demanda_log'),'')>'' && nvl(f($row,'destinatario'),'')=='') {
          $l_html.=chr(13).'      <td nowrap><font size="1">Anota��o</td>';
       } else {
          $l_html.=chr(13).'      <td nowrap><font size="1">'.Nvl(f($row,'tramite'),'---').'</td>';
        } 
        $l_html.=chr(13).'      </tr>';
      } 
    } 
    $l_html.=chr(13).'         </table></td></tr>';
    $l_html.=chr(13).'</table>';
  } 
  $l_html.=chr(13).'    </table>';
  $l_html.=chr(13).'</table>';
  return $l_html;
}
?>
