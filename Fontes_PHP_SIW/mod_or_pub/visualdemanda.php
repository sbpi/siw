<?
// =========================================================================
// Rotina de visualização dos dados da tarefa
// -------------------------------------------------------------------------
function VisualDemanda($l_chave,$Operacao,$l_usuario) {
  extract($GLOBALS);
  global $w_Disabled;
  $l_html='';
  // Recupera os dados da tarefa
  $RS = db_getSolicData::getInstanceOf($dbms,$l_chave,'GDGERAL');
  // O código abaixo foi comentado em 23/11/2004, devido à mudança na regra definida pelo usuário,
  // que agora permite visão geral para todos os usuários
  // Recupera o tipo de visão do usuário
  //If cDbl(Nvl(RS('solicitante'),0)) = cDbl(w_usuario) or _
  //   cDbl(Nvl(RS('executor'),0))    = cDbl(w_usuario) or _
  //   cDbl(Nvl(RS('cadastrador'),0)) = cDbl(w_usuario) or _
  //   cDbl(Nvl(RS('titular'),0))     = cDbl(w_usuario) or _
  //   cDbl(Nvl(RS('substituto'),0))  = cDbl(w_usuario) or _
  //   cDbl(Nvl(RS('tit_exec'),0))    = cDbl(w_usuario) or _
  //   cDbl(Nvl(RS('subst_exec'),0))  = cDbl(w_usuario) Then
  //   ' Se for solicitante, executor ou cadastrador, tem visão completa
  //   w_tipo_visao = 0
  //Else
  //   $RS = db_getSolicInter Rsquery, w_chave, w_usuario, 'REGISTRO'
  //   If Not RSquery.EOF Then
  //      ' Se for interessado, verifica a visão cadastrada para ele.
  //      w_tipo_visao = cDbl(RSquery('tipo_visao'))
  //   Else
  //      $RS = db_getSolicAreas Rsquery, w_chave, Session('sq_lotacao'), 'REGISTRO'
  //      If Not RSquery.EOF Then
  //         ' Se for de uma das unidades envolvidas, tem visão parcial
  //         w_tipo_visao = 1
  //      Else
  //         ' Caso contrário, tem visão resumida
  //         w_tipo_visao = 2
  //      End If
  //   End If
  //End If
  $w_tipo_visao=0;
  // Se for listagem ou envio, exibe os dados de identificação da tarefa
  if ($Operacao=='L' || $Operacao=='V') {
  //if ($P1==1 || $P1==2) {
    // Se for listagem dos dados
    $l_html .=chr(13).'<div align=center><center>';
    $l_html .=chr(13).'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
    $l_html .=chr(13).'<tr bgcolor="'.$conTrBgColor.'"><td align="center">';
    $l_html .=chr(13).'    <table width="99%" border="0">';
    if (nvl(f($RS,'nm_projeto'),'')>'') {
      // Recupera os dados da ação
      $RS1 = db_getSolicData::getInstanceOf($dbms,f($RS,'sq_solic_pai'),'PJGERAL');
      // Se a ação no PPA for informada, exibe.
      if (nvl(f($RS1,'sq_acao_ppa'),'')>'') {
        $l_html .=chr(13).'      <tr><td valign="top">Programa PPA:<b>'.f($RS1,'nm_ppa_pai').' ('.f($RS1,'cd_ppa_pai').')'.' </b></td>';
        $l_html .=chr(13).'      <tr><td valign="top">Ação PPA:<b>'.f($RS1,'nm_ppa').' ('.f($RS1,'cd_ppa').')'.' </b></td>';
      } 
      // Se a iniciativa prioritária for informada, exibe.
      if (nvl(f($RS1,'sq_orprioridade'),'')>'') {
        $l_html .=chr(13).'      <tr><td valign="top">Iniciativa prioritária:<b>'.f($RS1,'nm_pri');
        if (nvl(f($RS1,'cd_pri'),'')>'') {
          $l_html .=chr(13).' ('.f($RS1,'cd_pri').')';
        }
        $l_html .=chr(13).'          </b></td>';
        $l_html .=chr(13).'      <tr><td valign="top">Ação: <b>'.f($RS,'nm_projeto').'</b></td>';
      } 
    } 
    //If Not IsNull(RS('nm_etapa')) Then
    //   w_html = w_html & VbCrLf & '      <tr><td valign=''top''><b>' & MontaOrdemEtapa(RS('sq_projeto_etapa')) & '. ' & RS('nm_etapa') & ' </b></td>'
    //End If
    $l_html .=chr(13).'      <tr><td>Detalhamento: <b>'.CRLF2BR(f($RS,'assunto')).' ('.$l_chave.') </b></td></tr>';
    // Identificação da tarefa
    $l_html .=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Identificação</td>';
    // Se a classificação foi informada, exibe.
    if (nvl(f($RS,'sq_cc'),'')>'') {
      $l_html .=chr(13).'      <tr><td valign="top">Classificação:<br><b>'.f($RS,'cc_nome').' </b></td>';
    } 
    $l_html .=chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
    $l_html .=chr(13).'          <tr valign="top">';
    $l_html .=chr(13).'          <td>Responsável:<br><b>'.ExibePessoa('../',$w_cliente,f($RS,'solicitante'),$TP,f($RS,'nm_sol')).'</A></b></td>';
    $l_html .=chr(13).'          <td>Unidade responsável:<br><b>'.f($RS,'nm_unidade_resp').' </b></td>';
    if ($w_tipo_visao==0) {
      // Se for visão completa
      $l_html .=chr(13).'          <td valign="top">Recurso programado:<br><b>'.number_Format(f($RS,'valor'),2,',','.').' </b></td>';
    } 
    $l_html .=chr(13).'          <tr valign="top">';
    $l_html .=chr(13).'          <td>Início previsto:<br><b>'.FormataDataEdicao(f($RS,'inicio')).' </b></td>';
    $l_html .=chr(13).'          <td>Fim previsto:<br><b>'.FormataDataEdicao(f($RS,'fim')).' </b></td>';
    $l_html .=chr(13).'          <td>Prioridade:<br><b>'.RetornaPrioridade(f($RS,'prioridade')).' </b></td>';
    $l_html .=chr(13).'          <tr>';
    $l_html .=chr(13).'          <td colspan=2>Responsável:<br><b>'.Nvl(f($RS,'palavra_chave'),'---').' </b></td>';
    $l_html .=chr(13).'          <td>Ordem:<br><b>'.f($RS,'ordem').' </b></td>';
    $l_html .=chr(13).'          <tr valign="top">';
    $l_html .=chr(13).'          <td colspan=3>Parcerias externas:<br><b>'.Nvl(f($RS,'proponente'),'---').' </b></td>';
    //w_html = w_html & VbCrLf & '          <tr valign=''top''>'
    //w_html = w_html & VbCrLf & '          <td colspan=3>Abrangência da ação:(Quando Brasília-DF, impacto nacional. Quando a capital de um estado, impacto estadual.):<br><b>' & RS('nm_cidade') & ' (' & RS('co_uf') & ')</b></td>'
    $l_html .=chr(13).'          </table>';
    if ($w_tipo_visao==0 || $w_tipo_visao==1) {
      // Informações adicionais
      if (Nvl(f($RS,'descricao'),'')>'' || Nvl(f($RS,'justificativa'),'')>'') {
        $l_html .=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Informações adicionais</td>';
        if (Nvl(f($RS,'descricao'),'')>'') {
          $l_html .=chr(13).'      <tr><td valign="top">Resultados espearados:<br><b>'.CRLF2BR(f($RS,'descricao')).' </b></td>';
        }
        if ($w_tipo_visao==0 && Nvl(f($RS,'justificativa'),'')>'') {
          // Se for visão completa
          $l_html .=chr(13).'      <tr><td valign="top">Observações:<br><b>'.CRLF2BR(f($RS,'justificativa')).' </b></td>';
        } 
      } 
    } 
    // Dados da conclusão da tarefa, se ela estiver nessa situação
    if (f($RS,'concluida')=='S' && Nvl(f($RS,'data_conclusao'),'')>'') {
      $l_html .=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Dados da conclusão</td>';
      $l_html .=chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
      $l_html .=chr(13).'          <tr valign="top">';
      $l_html .=chr(13).'          <td>Início da execução:<br><b>'.FormataDataEdicao(f($RS,'inicio_real')).' </b></td>';
      $l_html .=chr(13).'          <td>Término da execução:<br><b>'.FormataDataEdicao(f($RS,'fim_real')).' </b></td>';
      if ($w_tipo_visao==0) {
        $l_html .=chr(13).'          <td>Rercuso executado:<br><b>'.number_format(f($RS,'custo_real'),2,',','.').' </b></td>';
      } 
      $l_html .=chr(13).'          </table>';
      if ($w_tipo_visao==0) {
        $l_html .=chr(13).'      <tr><td valign="top">Nota de conclusão:<br><b>'.CRLF2BR(f($RS,'nota_conclusao')).' </b></td>';
      } 
    } 
  } 
  // Se for listagem, exibe os outros dados dependendo do tipo de visão  do usuário
  if ($Operacao=='L' && $w_tipo_visao!=2) {
    if (f($RS,'aviso_prox_conc')=='S') {
    // Configuração dos alertas de proximidade da data limite para conclusão da tarefa
      $l_html .=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Alerta</td>';
      $l_html .=chr(13).'      <tr><td align="center" height="1" bgcolor="#000000"></td></tr>';
      $l_html .=chr(13).'      <tr><td>Será enviado aviso a partir de <b>'.f($RS,'dias_aviso').'</b> dias antes de <b>'.FormataDataEdicao(f($RS,'fim')).'</b></td></tr>';
      //w_html = w_html & VbCrLf & '      <tr><td valign=''top'' colspan=''2''><table border=0 width=''100''' cellspacing=0>'
      //w_html = w_html & VbCrLf & '          <td valign=''top''>Emite aviso:<br><b>' & Replace(Replace(RS('aviso_prox_conc'),'S','Sim'),'N','Não') & ' </b></td>'
      //w_html = w_html & VbCrLf & '          <td valign=''top''>Dias:<br><b>' & RS('dias_aviso') & ' </b></td>'
      //w_html = w_html & VbCrLf & '          </table>'
    } 
    // Interessados na execução da tarefa
    $RS = db_getSolicInter::getInstanceOf($dbms,$l_chave,null,'LISTA');
    $RS = SortArray($RS,'nome_resumido','asc');
    if (count($RS)>0) {
      $l_html .=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Interessados na execução</td>';
      $l_html .=chr(13).'      <tr><td align="center" colspan="2">';
      $l_html .=chr(13).'        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
      $l_html .=chr(13).'          <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $l_html .=chr(13).'            <td><b>Nome</td>';
      $l_html .=chr(13).'            <td><b>Tipo de visão</td>';
      $l_html .=chr(13).'            <td><b>Envia e-mail</td>';
      $l_html .=chr(13).'          </tr>';
      $w_cor=$conTrBgColor;
      foreach ($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        $l_html .=chr(13).'      <tr valign="top" bgcolor="'.$w_cor.'">';
        $l_html .=chr(13).'        <td>'.f($row,'nome_resumido').'</td>';
        $l_html .=chr(13).'        <td>'.RetornaTipoVisao(f($row,'tipo_visao')).'</td>';
        $l_html .=chr(13).'        <td align="center">'.str_replace('N','Não',str_replace('S','Sim',f($row,'envia_email'))).'</td>';
        $l_html .=chr(13).'      </tr>';
      } 
      $l_html .=chr(13).'         </table></td></tr>';
    } 
    // Áreas envolvidas na execução da tarefa
    $RS = db_getSolicAreas::getInstanceOf($dbms,$l_chave,null,'LISTA');
    $RS = SortArray($RS,'nome','asc');
    if (count($RS)>0) {
      $l_html .=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Áreas/Instituições envolvidas</td>';
      $l_html .=chr(13).'      <tr><td align="center" colspan="2">';
      $l_html .=chr(13).'        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
      $l_html .=chr(13).'          <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $l_html .=chr(13).'            <td><b>Nome</td>';
      $l_html .=chr(13).'            <td><b>Papel</td>';
      $l_html .=chr(13).'          </tr>';
      $w_cor=$conTrBgColor;
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        $l_html .=chr(13).'      <tr valign="top" bgcolor="'.$w_cor.'">';
        $l_html .=chr(13).'        <td>'.f($row,'nome').'</td>';
        $l_html .=chr(13).'        <td>'.f($row,'papel').'</td>';
        $l_html .=chr(13).'      </tr>';
      } 
      $l_html .=chr(13).'         </table></td></tr>';
    } 
  } 
  if ($Operacao=='L' || $Operacao=='V') {
    // Se for listagem dos dados
    if ($w_tipo_visao!=2) {
      // Arquivos vinculados
      $RS = db_getSolicAnexo::getInstanceOf($dbms,$l_chave,null,$w_cliente);
      $RS = SortArray($RS,'nome','asc');
      if(count($RS)>0) {
        $l_html .=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Arquivos anexos</td>';
        $l_html .=chr(13).'      <tr><td align="center" colspan="2">';
        $l_html .=chr(13).'        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
        $l_html .=chr(13).'          <tr bgcolor="'.$conTrBgColor.'" align="center">';
        $l_html .=chr(13).'          <td><b>Título</td>';
        $l_html .=chr(13).'          <td><b>Descrição</td>';
        $l_html .=chr(13).'          <td><b>Tipo</td>';
        $l_html .=chr(13).'          <td><b>KB</td>';
        $l_html .=chr(13).'          </tr>';
        $w_cor = $conTrBgColor;
        foreach ($RS as $row) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
          $l_html .=chr(13).'      <tr valign="top" bgcolor="'.$w_cor.'">';
          $l_html .=chr(13).'        <td>'.LinkArquivo('HL',$w_cliente,f($row,'chave_aux'),'_blank','Clique para exibir o arquivo em outra janela.',f($row,'nome'),null).'</td>';
          $l_html .=chr(13).'        <td>'.Nvl(f($row,'descricao'),'---').'</td>';
          $l_html .=chr(13).'        <td>'.f($row,'tipo').'</td>';
          $l_html .=chr(13).'        <td align="right">'.(round(f($row,'tamanho')/1024,1)).'&nbsp;</td>';
          $l_html .=chr(13).'      </tr>';
        } 
        $l_html .=chr(13).'         </table></td></tr>';
      } 
    } 
    // Encaminhamentos
    $RS = db_getSolicLog::getInstanceOf($dbms,$l_chave,null,'LISTA');
    $RS = SortArray($RS,'data','desc','sq_siw_solic_log','desc');
    $l_html .=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Ocorrências e Anotações</td>';
    $l_html .=chr(13).'      <tr><td align="center" colspan="2">';
    $l_html .=chr(13).'        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
    $l_html .=chr(13).'          <tr bgcolor="'.$conTrBgColor.'" align="center">';
    $l_html .=chr(13).'            <td><b>Data</td>';
    $l_html .=chr(13).'            <td><b>Despacho/Observação</td>';
    $l_html .=chr(13).'            <td><b>Responsável</td>';
    $l_html .=chr(13).'            <td><b>Fase / Destinatário</td>';
    $l_html .=chr(13).'          </tr>';
    if (count($RS)<=0) {
      $l_html .=chr(13).'      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><b>Não foram encontrados encaminhamentos.</b></td></tr>';
    } else {
      $l_html .=chr(13).'      <tr bgcolor="'.$conTrBgColor.'" valign="top">';
      $i=0;
      $w_cor=$conTrBgColor;
      foreach($RS as $row) {
        if($i==0) $l_html .=chr(13).'        <td colspan=6>Fase atual: <b>'.f($row,'fase').'</b></td>';
        $i=1;
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        $l_html .=chr(13).'      <tr valign="top" bgcolor="'.$w_cor.'">';
        $l_html.=chr(13).'        <td nowrap>'.FormataDataEdicao(f($row,'phpdt_data'),3).'</td>';
        if (Nvl(f($row,'caminho'),'')>'') {
          $l_html.=chr(13).'        <td>'.CRLF2BR(Nvl(f($row,'despacho'),'---').'<br>'.LinkArquivo('HL',$w_cliente,f($row,'sq_siw_arquivo'),'_blank','Clique para exibir o anexo em outra janela.','Anexo - '.f($row,'tipo').' - '.round(f($row,'tamanho')/1024,1).' KB',null)).'</td>';
        } else {
          $l_html.=chr(13).'        <td>'.CRLF2BR(Nvl(f($row,'despacho'),'---')).'</td>';
        }         
        $l_html.=chr(13).'        <td nowrap>'.ExibePessoa('../',$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'responsavel')).'</td>';
        if ((Nvl(f($row,'sq_demanda_log'),'')>'') && (Nvl(f($row,'destinatario'),'')>''))         $l_html.=chr(13).'        <td nowrap>'.ExibePessoa('../',$w_cliente,f($row,'sq_pessoa_destinatario'),$TP,f($row,'destinatario')).'</td>';
        elseif ((Nvl(f($row,'sq_demanda_log'),'')>'')  && (Nvl(f($row,'destinatario'),'')==''))   $l_html.=chr(13).'        <td nowrap>Anotação</td>';
        else $l_html.=chr(13).'        <td nowrap>'.Nvl(f($row,'tramite'),'---').'</td>';
        $l_html.=chr(13).'      </tr>';
      } 
    } 
    $l_html .=chr(13).'         </table></td></tr>';
    $l_html .=chr(13).'</table>';
  } 
  return $l_html;
} 
?>