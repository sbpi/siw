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
      $RSQuery = db_getSolicAreas::getInstanceOf($dbms,$w_chave,$sq_lotacao_session,'REGISTRO');
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
    $w_html.=chr(13).'<tr><td align="center">';

    $w_html.=chr(13).'    <table width="99%" border="0">';
    $w_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    if (substr($SG,0,3)=='GDP') $w_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>ATIVIDADE: '.f($RS,'palavra_chave').' ('.$w_chave.')'.'</b></font></div></td></tr>';
    else                        $w_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>DEMANDA: '.f($RS,'palavra_chave').' ('.$w_chave.')'.'</b></font></div></td></tr>';
    $w_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    // Identificação da demanda
    $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>IDENTIFICAÇÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
    if (nvl(f($RS,'nm_projeto'),'')>'') {
      $w_html.=chr(13).'      <tr><td width="30%"><b>Projeto: </b></td>';
      $w_html.=chr(13).'        <td>'.f($RS,'nm_projeto').'  ('.f($RS,'sq_solic_pai').')</td></tr>';
    } 

    if (nvl(f($RS,'nm_etapa'),'')>'') {
      $w_html.=chr(13).'      <tr><td valign="top"><b>Etapa: </b></td>';
      $w_html.=chr(13).'        <td>'.MontaOrdemEtapa(f($RS,'sq_projeto_etapa')).'. '.f($RS,'nm_etapa').'</td></tr>';
    } 

    if (nvl(f($RS,'sq_demanda_pai'),'')>'') {
      // Recupera os dados da demanda
      $RS1 = db_getSolicData::getInstanceOf($dbms,f($RS,'sq_demanda_pai'),'GDGERAL');
      $w_html.=chr(13).'      <tr><td valign="top"><b>Atividade pai: </b></td>';
      $w_html.=chr(13).'        <td><A class="HL" HREF="'.$w_pagina.'Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($RS1,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro." target="_blank">'.f($RS1,'sq_siw_solicitacao').'</a> - '.f($RS1,'assunto').' </td></tr>';
    } 

    $w_html.=chr(13).'      <tr><td width="30%"><b>Detalhamento: </b></td>';
    $w_html.=chr(13).'        <td>'.CRLF2BR(f($RS,'assunto')).'</td></tr>';

    // Se a classificação foi informada, exibe.
    if (nvl(f($RS,'sq_cc'),'')>'') {
      $w_html.=chr(13).'      <tr valign="top"><td><b>Classificação:</b></td>';
      $w_html.=chr(13).'        <td>'.f($RS,'cc_nome').' </td></tr>';
    } 
    $w_html.=chr(13).'        <tr valign="top"><td><b>Local de execução:</b></td>';
      $w_html.=chr(13).'        <td>'.f($RS,'nm_cidade').' ('.f($RS,'co_uf').')</td></tr>';
    if (Nvl(f($RS,'proponente'),'')>'') {
      $w_html.=chr(13).'      <tr valign="top"><td><b>Proponente externo:</b></td>';
      $w_html.=chr(13).'        <td>'.f($RS,'proponente').' </td></tr>';
    } else {
      $w_html.=chr(13).'      <tr valign="top"><td><b>Proponente externo:</b></td>';
      $w_html.=chr(13).'        <td>--- </td></tr>';
    } 
    $w_html.=chr(13).'        <tr><td><b>Responsável:</b></td>';
    $w_html.=chr(13).'          <td>'.ExibePessoa(null,$w_cliente,f($RS,'solicitante'),$TP,f($RS,'nm_sol')).'</td></tr>';
    $w_html.=chr(13).'        <tr><td><b>Unidade responsável:</b></td>';
    $w_html.=chr(13).'          <td>'.ExibeUnidade(null,$w_cliente,f($RS,'nm_unidade_resp'),f($RS,'sq_unidade_resp'),$TP).'</td></tr>';

    if ($w_tipo_visao==0) {
      // Se for visão completa
      $w_html.=chr(13).'      <tr valign="top"><td><B>Orçamento disponível: </b></td>';
      $w_html.=chr(13).'        <td>'.number_format(f($RS,'valor'),2,',','.').' </td></tr>';
    } 
    $w_html.=chr(13).'        <tr><td><b>Início previsto:</b></td>';
    $w_html.=chr(13).'          <td>'.FormataDataEdicao(f($RS,'inicio')).' </td></tr>';
    $w_html.=chr(13).'        <tr><td><b>Término previsto:</b></td>';
    $w_html.=chr(13).'          <td>'.FormataDataEdicao(f($RS,'fim')).' </td></tr>';
    $w_html.=chr(13).'        <tr><td><b>Prioridade:</b></td>';
    $w_html.=chr(13).'          <td>'.RetornaPrioridade(f($RS,'prioridade')).' </td></tr>';
    $w_html.=chr(13).'        <tr valign="top"><td><b>Palavras-chave:</b></td>';
    $w_html.=chr(13).'          <td>'.nvl(f($RS,'palavra_chave'),'---').' </td></tr>';
    $RSQuery = db_getSolicList::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,f($RS,'sigla'),4,
            null,null,null,null,null,null,null,null,null,null,null, null, null, null, null, null, null,
            null, null, null, null,null, null, null, f($RS,'sq_siw_solicitacao'), null);
    $RSQuery = SortArray($RSQuery,'fim','asc','prioridade','asc');
    if (count($RSQuery)>0) {
      $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ATIVIDADES SUBORDINADAS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $w_html.=chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
      $w_html.=chr(13).'        <tr><td align="right"><b>Registros: '.count($RSQuery);
      $w_html.=chr(13).'        <tr><td align="center" colspan=3>';
      $w_html.=chr(13).'          <table width=100%  border="1" bordercolor="#00000">';
      $w_html.=chr(13).'            <tr align="center">';
      $w_html.=chr(13).'              <td bgColor="#f0f0f0"><div><b>Nº</b></div></td>';
      $w_html.=chr(13).'              <td bgColor="#f0f0f0"><div><b>Etapa</b></div></td>';
      $w_html.=chr(13).'              <td bgColor="#f0f0f0"><div><b>Responsável</b></div></td>';
      $w_html.=chr(13).'              <td bgColor="#f0f0f0"><div><b>Detalhamento</b></div></td>';
      $w_html.=chr(13).'              <td bgColor="#f0f0f0"><div><b>Fim previsto</b></div></td>';
      $w_html.=chr(13).'              <td bgColor="#f0f0f0"><div><b>Fase atual</b></div></td>';
      $w_html.=chr(13).'            </tr>';
      foreach($RSQuery as $row) {
        $w_html.=chr(13).'        <tr valign="top">';
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
      $w_html.=chr(13).'          </table>';
        $w_html.=chr(13).'      </table>';
      } 
    }

    if ($w_tipo_visao==0 || $w_tipo_visao==1) {
      // Informações adicionais
      if (Nvl(f($RS,'descricao'),'')>'' || Nvl(f($RS,'justificativa'),'')>'') {
        $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>INFORMAÇÕES ADICIONAIS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
        if (Nvl(f($RS,'descricao'),'')>''){ 
          $w_html.=chr(13).'      <tr valign="top"><td><b>Resultados da demanda:</b></td>';
          $w_html.=chr(13).'        <td>'.CRLF2BR(f($RS,'descricao')).' </td></tr>';
        }
        if ($w_tipo_visao==0 && Nvl(f($RS,'justificativa'),'')>'') {
          // Se for visão completa
          $w_html.=chr(13).'      <tr valign="top"><td><b>Observações:</b></td>';
          $w_html.=chr(13).'            <td>'.CRLF2BR(f($RS,'justificativa')).' </td></tr>';
        } 
      } 
    } 

    // Dados da conclusão da demanda, se ela estiver nessa situação
    if (f($RS,'concluida')=='S' && Nvl(f($RS,'data_conclusao'),'')>'') {
      $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS DA CONCLUSÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
      $w_html.=chr(13).'      <tr><td valign="top" colspan="2">';
      $w_html.=chr(13).'      <tr><td><b>Início previsto:</b></td>';
      $w_html.=chr(13).'        <td>'.FormataDataEdicao(f($RS,'inicio_real')).' </td></tr>';
      $w_html.=chr(13).'      <tr><td><b>Término previsto:</b></td>';
      $w_html.=chr(13).'        <td>'.FormataDataEdicao(f($RS,'fim_real')).' </td></tr>';
      if ($w_tipo_visao==0) {
        $w_html.=chr(13).'    <tr><td><b>Custo real:</b></td>';
        $w_html.=chr(13).'      <td>'.number_format(f($RS,'custo_real'),2,',','.').' </td></tr>';
      } 
      if ($w_tipo_visao==0) {
        $w_html.=chr(13).'      <tr valign="top"><td valign="top"><b>Nota de conclusão:</b></td>';
        $w_html.=chr(13).'        <td>'.CRLF2BR(f($RS,'nota_conclusao')).' </td></tr>';
      } 
    } 
  } 

  // Se for listagem, exibe os outros dados dependendo do tipo de visão  do usuário
  if ($operacao=='L' && $w_tipo_visao!=2) {
    if (f($RS,'aviso_prox_conc')=='S') {
      // Configuração dos alertas de proximidade da data limite para conclusão da demanda
      $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ALERTAS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
      $w_html.=chr(13).'      <tr><td valign="top" colspan="2">';
      $w_html.=chr(13).'      <tr><td valign="top"><b>Emite aviso:</b></td>';
      $w_html.=chr(13).'        <td>'.str_replace('N','Não',str_replace('S','Sim',f($RS,'aviso_prox_conc'))).' </td></tr>';
      $w_html.=chr(13).'      <tr><td valign="top"><b>Dias:</b></td>';
      $w_html.=chr(13).'        <td>'.f($RS,'dias_aviso').' </td></tr>';

    } 

    // Interessados na execução da demanda
    $RS = db_getSolicInter::getInstanceOf($dbms,$w_chave,null,'LISTA');
    $RS = SortArray($RS,'nome','asc');
    if (count($RS)>0) {
      $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>INTERESSADOS NA EXECUÇÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
      $w_html.=chr(13).'      <tr><td colspan="2"><div align="center">';
      $w_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
      $w_html.=chr(13).'          <tr align="center">';
      $w_html.=chr(13).'            <td bgColor="#f0f0f0" width="40%"><div><b>Nome</b></div></td>';
      $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Tipo de visão</b></div></td>';
      $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Envia e-mail</b></div></td>';
      $w_html.=chr(13).'          </tr>';
      $w_cor=$conTrBgColor;
      foreach($RS as $row) {
        $w_html.=chr(13).'      <tr valign="top">';
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
      $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ÁREAS/INSTITUIÇÕES ENVOLVIDAS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
      $w_html.=chr(13).'      <tr><td colspan="2"><div align="center">';
      $w_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
      $w_html.=chr(13).'          <tr align="center">';
      $w_html.=chr(13).'            <td bgColor="#f0f0f0" width="40%"><div><b>Nome</b></div></td>';
      $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Papel</b></div></td>';
      $w_html.=chr(13).'          </tr>';
      $w_cor=$conTrBgColor;
      foreach($RS as $row) {
        $w_html.=chr(13).'      <tr valign="top">';
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
      $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ARQUIVOS ANEXOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
      $w_html.=chr(13).'      <tr><td colspan="2"><div align="center">';
      $w_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
      $w_html.=chr(13).'          <tr align="center">';
     $w_html.=chr(13).'             <td bgColor="#f0f0f0" width="40%"><div><b>Título</b></div></td>';
      $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Descrição</b></div></td>';
      $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Tipo</b></div></td>';
      $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>KB</b></div></td>';
      $w_html.=chr(13).'          </tr>';
      $w_cor=$conTrBgColor;
      foreach($RS as $row) {
        $w_html.=chr(13).'      <tr valign="top">';
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
    $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>OCORRÊNCIAS E ANOTAÇÕES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $w_html.=chr(13).'      <tr><td colspan="2"><div align="center">';
    $w_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';    
    $w_html.=chr(13).'          <tr align="center">';
    $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Data</b></div></td>';
    $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Despacho/Observação</b></div></td>';
    $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Responsável</b></div></td>';
    $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Fase / Destinatário</b></div></td>';
    $w_html.=chr(13).'          </tr>';
    if (count($RS)<=0) {
      $w_html.=chr(13).'      <tr><td colspan=6 align="center"><font size="1"><b>Não foram encontrados encaminhamentos.</b></td></tr>';
    } else {
      $w_html.=chr(13).'      <tr valign="top">';
      $w_cor=$conTrBgColor;
      $i = 0;
      foreach($RS as $row) {
        if ($i==0) {
          $w_html.=chr(13).'        <td colspan=6><font size="1">Fase atual: <b>'.f($row,'fase').'</b></td>';
          $i = 1;
        }
        $w_html.=chr(13).'      <tr valign="top">';
        $w_html.=chr(13).'        <td nowrap><font size="1">'.FormataDataEdicao(f($row,'phpdt_data'),3).'</td>';
        if (Nvl(f($row,'caminho'),'')>'') {
          $w_html.=chr(13).'      <td><font size="1">'.CRLF2BR(Nvl(f($row,'despacho'),'---').'<br>'.LinkArquivo('HL',$w_cliente,f($row,'sq_siw_arquivo'),'_blank','Clique para exibir o anexo em outra janela.','Anexo - '.f($row,'tipo').' - '.round(f($row,'tamanho')/1024,1).' KB',null)).'</td>';
        } else {
          $w_html.=chr(13).'      <td><font size="1">'.CRLF2BR(Nvl(f($row,'despacho'),'---')).'</td>';
        } 
        $w_html.=chr(13).'        <td nowrap><font size="1">'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'responsavel')).'</td>';
        if (nvl(f($row,'sq_acordo_log'),'')>'' && nvl(f($row,'destinatario'),'')>'') {
          $w_html.=chr(13).'      <td nowrap><font size="1">'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'sq_pessoa_destinatario'),$TP,f($row,'destinatario')).'</td>';
        } elseif (nvl(f($row,'sq_acordo_log'),'')>'' && nvl(f($row,'destinatario'),'')=='') {
          $w_html.=chr(13).'      <td nowrap><font size="1">Anotação</td>';
       } else {
          $w_html.=chr(13).'      <td nowrap><font size="1">'.Nvl(f($row,'tramite'),'---').'</td>';
        } 
        $w_html.=chr(13).'      </tr>';
      } 
    } 
    $w_html.=chr(13).'         </table></td></tr>';
    $w_html.=chr(13).'</table>';
  } 
  $w_html.=chr(13).'    </table>';
  $w_html.=chr(13).'</table>';
  return $w_html;
}
?>
