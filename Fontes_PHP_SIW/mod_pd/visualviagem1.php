<?
// =========================================================================
// Rotina de visualização dos dados da missão
// -------------------------------------------------------------------------
function VisualViagem($l_chave,$l_O,$l_usuario,$l_P1,$l_tipo) {
  extract($GLOBALS);

  if ($l_tipo=='WORD') $w_TrBgColor=''; else $w_TrBgColor=$conTrBgColor;
  $l_html='';

// Recupera os dados da viagem
  $RS = db_getSolicData::getInstanceOf($dbms,$l_chave,substr($SG,0,3).'GERAL');
  $w_tramite    = f($RS,'sq_siw_tramite');
  $w_valor      = f($RS,'valor');
  $w_fim        = f($RS,'fim');
  $w_sg_tramite = f($RS,'sg_tramite');
  $w_or_tramite = f($RS,'or_tramite');
  $w_tramite_ativo      = f($RS,'ativo');

  // Recupera o tipo de visão do usuário
  if (Nvl(f($RS,'solicitante'),0)==$l_usuario || 
      Nvl(f($RS,'executor'),0)==$l_usuario || 
      Nvl(f($RS,'cadastrador'),0)==$l_usuario || 
      Nvl(f($RS,'titular'),0)==$l_usuario || 
      Nvl(f($RS,'substituto'),0)==$l_usuario || 
      Nvl(f($RS,'tit_exec'),0)==$l_usuario || 
      Nvl(f($RS,'subst_exec'),0)==$l_usuario || 
      SolicAcesso($l_chave,$l_usuario)>=8) {
    // Se for solicitante, executor ou cadastrador, tem visão completa
    $w_tipo_visao=0;
  } else {
    if (SolicAcesso($l_chave,$l_usuario)>2) $w_tipo_visao=1;
  } 

  // Se for listagem ou envio, exibe os dados de identificação do acordo
  if ($l_O=='L' || $l_O=='V') {
    // Se for listagem dos dados
    $l_html.=chr(13).'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
    $l_html.=chr(13).'<tr bgcolor="'.$w_TrBgColor.'"><td>';
    $l_html.=chr(13).'    <table width="99%" border="0">';
    $l_html.=chr(13).'      <tr><td colspan=2>Número: <b>'.f($RS,'codigo_interno').' ('.$l_chave.')<br>'.'</b></td></tr>';
    // Identificação da PCD
    $l_html.=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Identificação</td>';
    $l_html.=chr(13).'      <tr><td>Objetivo/assunto a ser tratado/evento:<br><b>'.f($RS,'descricao').'</b></td>';
    $l_html.=chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
    $l_html.=chr(13).'          <tr valign="top">';
    if (!$l_tipo=='WORD') {
      $l_html.=chr(13).'          <td>Unidade proponente:<br><b>'.ExibeUnidade($w_dir_volta,$w_cliente,f($RS,'nm_unidade_resp'),f($RS,'sq_unidade_resp'),$TP).'</b></td>';
    } else {
      $l_html.=chr(13).'          <td>Unidade proponente:<br><b>'.f($RS,'nm_unidade_resp').'</b></td>';
    } 
    $l_html.=chr(13).'          <td valign="top" colspan="2">Tipo:<br><b>'.f($RS,'nm_tipo_missao').' </b></td>';
    $l_html.=chr(13).'          <td>Primeira saída:<br><b>'.FormataDataEdicao(f($RS,'inicio')).' </b></td>';
    $l_html.=chr(13).'          <td>Último retorno:<br><b>'.FormataDataEdicao(f($RS,'fim')).' </b></td>';
    $l_html.=chr(13).'          </table>';
    if (Nvl(f($RS,'justificativa_dia_util'),'')>'') {
      // Se o campo de justificativa de dias úteis para estiver preenchido, exibe
      $l_html.=chr(13).'      <tr><td valign="top" colspan="2">Justificativa para início e término de viagens em sextas-feiras, sábados, domingos e feriados:<br><b>'.CRLF2BR(f($RS,'justificativa_dia_util')).' </b></td>';
    } 
    if (Nvl(f($RS,'justificativa'),'')>'') {
      // Se o campo de justificativa estiver preenchido, exibe
      $l_html.=chr(13).'      <tr><td valign="top" colspan="2">Justificativa do não cumprimento do prazo de solicitação:<br><b>'.CRLF2BR(f($RS,'justificativa')).' </b></td>';
    } 
    // Dados da conclusão da demanda, se ela estiver nessa situação
    if (Nvl(f($RS,'conclusao'),'')>'') {
      $l_html.=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Dados do encerramento</td>';
      $l_html.=chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
      $l_html.=chr(13).'          <tr valign="top">';
      $l_html.=chr(13).'          <td>Início da vigência:<br><b>'.FormataDataEdicao(f($RS,'inicio_real')).' </b></td>';
      $l_html.=chr(13).'          <td>Término da vigência:<br><b>'.FormataDataEdicao(f($RS,'fim_real')).' </b></td>';
      if ($w_tipo_visao==0) {
        $l_html.=chr(13).'          <td>Valor realizado:<br><b>'.formatNumber(f($RS,'custo_real')).' </b></td>';
      } 
      $l_html.=chr(13).'          </table>';
      if ($w_tipo_visao==0) {
        $l_html.=chr(13).'      <tr><td valign="top">Nota de conclusão:<br><b>'.CRLF2BR(f($RS,'observacao')).' </b></td>';
      } 
    } 
    // Vinculações a atividades
    $RS1 = db_getPD_Vinculacao::getInstanceOf($dbms,$l_chave,null,null);
    $RS1 = SortArray($RS1,'inicio','asc');
    if (count($RS1)>0) {
      $l_html.=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Vinculações</td>';
      $l_html.=chr(13).'      <tr><td align="center" colspan="2">';
      $l_html.=chr(13).'        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
      $l_html.=chr(13).'          <tr bgcolor="'.$w_TrBgColor.'" align="center">';
      $l_html.=chr(13).'          <td><b>Nº</td>';
      $l_html.=chr(13).'          <td><b>Projeto</td>';
      $l_html.=chr(13).'          <td><b>Detalhamento</td>';
      $l_html.=chr(13).'          <td><b>Início</td>';
      $l_html.=chr(13).'          <td><b>Fim</td>';
      $l_html.=chr(13).'          <td><b>Situação</td>';
      $l_html.=chr(13).'          </tr>';
      $w_cor=$w_TrBgColor;
      $w_total=0;
      foreach($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        $l_html.=chr(13).'      <tr valign="top" bgcolor="'.$w_cor.'">';
        $l_html.=chr(13).'        <td nowrap>';
        if (f($row,'concluida')=='N') {
          if (f($row,'fim')<addDays(time(),-1)) {
            $l_html.=chr(13).'           <img src="'.$conImgAtraso.'" border=0 width=15 heigth=15 align="center">';
          } elseif (f($row,'aviso_prox_conc')=='S' && (f($row,'aviso')<=addDays(time(),-1))) {
            $l_html.=chr(13).'           <img src="'.$conImgAviso.'" border=0 width=15 height=15 align="center">';
          } else {
            $l_html.=chr(13).'           <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">';
          } 
        } else {
          if (f($row,'fim')<Nvl(f($row,'fim_real'),f($row,'fim'))) {
            $l_html.=chr(13).'           <img src="'.$conImgOkAtraso.'" border=0 width=15 heigth=15 align="center">';
          } else {
            $l_html.=chr(13).'           <img src="'.$conImgOkNormal.'" border=0 width=15 height=15 align="center">';
          } 
        } 
        if (nvl(f($row,'sq_projeto'),'')=='') {
          $l_html.=chr(13).'        <A class="HL" TARGET="VISUAL" HREF="demanda.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'sq_siw_solicitacao').'&nbsp;</a>';
        } else {
          $l_html.=chr(13).'        <A class="HL" TARGET="VISUAL" HREF="projetoativ.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'sq_siw_solicitacao').'&nbsp;</a>';
        }
        $l_html.=chr(13).'        <td>'.nvl(f($row,'nm_projeto'),'---').'</td>';
        if (strlen(Nvl(f($row,'assunto'),'-'))>50) $w_assunto=substr(Nvl(f($row,'assunto'),'-'),0,50).'...'; else $w_assunto=Nvl(f($row,'assunto'),'-');
        if (f($row,'sg_tramite')=='CA') {
          $l_html.=chr(13).'        <td title="'.htmlspecialchars(f($row,'assunto')).'"><strike>'.$w_assunto.'</strike></td>';
        } else {
          $l_html.=chr(13).'        <td title="'.htmlspecialchars(f($row,'assunto')).'">'.$w_assunto.'</td>';
        } 
        if (f($row,'concluida')=='N') {
          $l_html.=chr(13).'        <td align="center">'.FormataDataEdicao(f($row,'inicio')).'</td>';
          $l_html.=chr(13).'        <td align="center">'.FormataDataEdicao(f($row,'fim')).'</td>';
        } else {
          $l_html.=chr(13).'        <td align="center">'.FormataDataEdicao(f($row,'inicio_real')).'</td>';
          $l_html.=chr(13).'        <td align="center">'.FormataDataEdicao(f($row,'fim_real')).'</td>';
        } 
        $l_html.=chr(13).'        <td>'.f($row,'nm_tramite').'</td>';
        $l_html.=chr(13).'      </tr>';
      } 
      $l_html.=chr(13).'         </table></td></tr>';
    } 

    // Outra parte
    $RSQuery = db_getBenef::getInstanceOf($dbms,$w_cliente,Nvl(f($RS,'sq_prop'),0),null,null,null,null,1,null,null,null,null,null,null,null);
    $l_html.=chr(13).'      <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Proposto</td>';
    if (count($RSQuery)==0) {
      $l_html.=chr(13).'      <tr><td colspan=2><b>Proposto não informado.';
    } else {
      foreach($RSQuery as $row) { $RSQuery = $row; break; }
      $l_html.=chr(13).'      <tr><td colspan=2><table border=0 width="100%">';
      $l_html.=chr(13).'      <tr valign="top">';
      $l_html.=chr(13).'          <td>CPF:<b><br>'.f($RSQuery,'cpf').'</td>';
      $l_html.=chr(13).'          <td>Nome:<b><br>'.f($RSQuery,'nm_pessoa').'</td>';
      $l_html.=chr(13).'          <td>Nome resumido:<b><br>'.f($RSQuery,'nome_resumido').'</td>';
      $l_html.=chr(13).'          <td>Sexo:<b><br>'.f($RSQuery,'nm_sexo').'</td>';
      $l_html.=chr(13).'      <tr valign="top">';
      $l_html.=chr(13).'          <td>Matrícula:<b><br>'.Nvl(f($RS,'matricula'),'---').'</td>';
      $l_html.=chr(13).'          <td>Identidade:<b><br>'.Nvl(f($RSQuery,'rg_numero'),'---').'</td>';
      $l_html.=chr(13).'          <td>Data de emissão:<b><br>'.FormataDataEdicao(Nvl(f($RSQuery,'rg_emissao'),'---')).'</td>';
      $l_html.=chr(13).'          <td>Órgão emissor:<b><br>'.Nvl(f($RSQuery,'rg_emissor'),'---').'</td>';
      $l_html.=chr(13).'      <tr><td colspan="4" align="center" style="border: 1px solid rgb(0,0,0);"><b>Telefones</td>';
      $l_html.=chr(13).'      <tr valign="top">';
      $l_html.=chr(13).'          <td>Telefone:<b><br>('.Nvl(f($RSQuery,'ddd'),'---').') '.Nvl(f($RSQuery,'nr_telefone'),'---').'</td>';
      $l_html.=chr(13).'          <td>Fax:<b><br>'.Nvl(f($RSQuery,'nr_fax'),'---').'</td>';
      $l_html.=chr(13).'          <td>Celular:<b><br>'.Nvl(f($RSQuery,'nr_celular'),'---').'</td>';
      $l_html.=chr(13).'      <tr><td colspan="4" align="center" style="border: 1px solid rgb(0,0,0);"><b>Dados bancários</td>';
      if (true) { //(!(strpos('CREDITO,DEPOSITO',$w_forma_pagamento)===false)) {
        $l_html.=chr(13).'          <tr valign="top">';
        if (Nvl(f($RS,'cd_banco'),'')>'') {
          $l_html.=chr(13).'          <td>Banco:<b><br>'.f($RS,'cd_banco').' - '.f($RS,'nm_banco').'</td>';
          $l_html.=chr(13).'          <td>Agência:<b><br>'.f($RS,'cd_agencia').' - '.f($RS,'nm_agencia').'</td>';
          if (f($RS,'exige_operacao')=='S') $l_html.=chr(13).'          <td>Operação:<b><br>'.Nvl(f($RS,'operacao_conta'),'---').'</td>';
          $l_html.=chr(13).'          <td>Número da conta:<b><br>'.Nvl(f($RS,'numero_conta'),'---').'</td>';
        } else {
          $l_html.=chr(13).'          <td>Banco:<b><br>---</td>';
          $l_html.=chr(13).'          <td>Agência:<b><br>---</td>';
          if (f($RS,'exige_operacao')=='S') $l_html.=chr(13).'          <td>Operação:<b><br>---</td>';
          $l_html.=chr(13).'          <td>Número da conta:<b><br>---</td>';
        } 
      } elseif (f($RS,'sg_forma_pagamento')=='ORDEM') {
        $l_html.=chr(13).'          <tr valign="top">';
        if (Nvl(f($RS,'cd_banco'),'')>'') {
          $l_html.=chr(13).'          <td>Banco:<b><br>'.f($RS,'cd_banco').' - '.f($RS,'nm_banco').'</td>';
          $l_html.=chr(13).'          <td>Agência:<b><br>'.f($RS,'cd_agencia').' - '.f($RS,'nm_agencia').'</td>';
        } else {
          $l_html.=chr(13).'          <td>Banco:<b><br>---</td>';
          $l_html.=chr(13).'          <td>Agência:<b><br>---</td>';
        } 
      } elseif (f($RS,'sg_forma_pagamento')=='EXTERIOR') {
        $l_html.=chr(13).'          <tr valign="top">';
        $l_html.=chr(13).'          <td>Banco:<b><br>'.f($RS,'banco_estrang').'</td>';
        $l_html.=chr(13).'          <td>ABA Code:<b><br>'.Nvl(f($RS,'aba_code'),'---').'</td>';
        $l_html.=chr(13).'          <td>SWIFT Code:<b><br>'.Nvl(f($RS,'swift_code'),'---').'</td>';
        $l_html.=chr(13).'          <tr><td colspan=3>Endereço da agência:<b><br>'.Nvl(f($RS,'endereco_estrang'),'---').'</td>';
        $l_html.=chr(13).'          <tr valign="top">';
        $l_html.=chr(13).'          <td colspan=2>Agência:<b><br>'.Nvl(f($RS,'agencia_estrang'),'---').'</td>';
        $l_html.=chr(13).'          <td>Número da conta:<b><br>'.Nvl(f($RS,'numero_conta'),'---').'</td>';
        $l_html.=chr(13).'          <tr valign="top">';
        $l_html.=chr(13).'          <td colspan=2>Cidade:<b><br>'.f($RS,'nm_cidade').'</td>';
        $l_html.=chr(13).'          <td>País:<b><br>'.f($RS,'nm_pais').'</td>';
      } 
      $l_html.=chr(13).'        </table>';
    } 
  } 
  // Se for listagem, exibe os outros dados dependendo do tipo de visão  do usuário
  if ($w_tipo_visao!=2 && ($l_O=='L' || $l_O=='T')) {
    if (f($RS,'aviso_prox_conc')=='S') {
      // Configuração dos alertas de proximidade da data limite para conclusão do acordo
      $l_html.=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Alertas</td>';
      $l_html.=chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
      $l_html.=chr(13).'          <td valign="top">Emite aviso:<br><b>'.str_replace('N','Não',str_replace('S','Sim',f($RS,'aviso_prox_conc'))).' </b></td>';
      $l_html.=chr(13).'          <td valign="top">Dias:<br><b>'.f($RS,'dias_aviso').' </b></td>';
      $l_html.=chr(13).'          </table>';
    } 
  } 

  // Deslocamentos
  $RS = db_getPD_Deslocamento::getInstanceOf($dbms,$l_chave,null,'PDGERAL');
  $RS = SortArray($RS,'phpdt_saida','asc', 'phpdt_chegada', 'asc');
  if (count($RS)>0) {
    $l_html.=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Deslocamentos</td>';
    $l_html.=chr(13).'      <tr><td align="center" colspan="2">';
    $l_html.=chr(13).'        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
    $l_html.=chr(13).'          <tr bgcolor="'.$w_TrBgColor.'" align="center">';
    $l_html.=chr(13).'          <td><b>Origem</td>';
    $l_html.=chr(13).'          <td><b>Destino</td>';
    $l_html.=chr(13).'          <td><b>Saida</td>';
    $l_html.=chr(13).'          <td><b>Chegada</td>';
    $l_html.=chr(13).'          <td><b>Compromisso<br>dia viagem</td>';
    $l_html.=chr(13).'          <td><b>Transporte</td>';
    $l_html.=chr(13).'          <td><b>Bilhete</td>';
    $l_html.=chr(13).'          <td><b>Valor</td>';
    $l_html.=chr(13).'          <td><b>Companhia</td>';
    $l_html.=chr(13).'          <td><b>Vôo</td>';
    $l_html.=chr(13).'          </tr>';
    $w_cor=$w_TrBgColor;
    $w_total=0;
    foreach($RS as $row) {
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
      $l_html.=chr(13).'      <tr valign="top" bgcolor="'.$w_cor.'">';
      $l_html.=chr(13).'        <td>'.f($row,'nm_origem').'</td>';
      $l_html.=chr(13).'        <td>'.f($row,'nm_destino').'</td>';
      $l_html.=chr(13).'        <td align="center">'.substr(FormataDataEdicao(f($row,'phpdt_saida'),6),0,-3).'</td>';
      $l_html.=chr(13).'        <td align="center">'.substr(FormataDataEdicao(f($row,'phpdt_chegada'),6),0,-3).'</td>';
      $l_html.=chr(13).'        <td align="center">'.f($row,'nm_compromisso').'</td>';
      $l_html.=chr(13).'        <td align="center">'.nvl(f($row,'nm_meio_transporte'),'---').'</td>';
      $l_html.=chr(13).'        <td align="center">'.f($row,'nm_passagem').'</td>';
      $l_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'valor_trecho')).'</td>';
      $l_html.=chr(13).'        <td>'.f($row,'nm_cia_transporte').'</td>';
      $l_html.=chr(13).'        <td align="center">'.f($row,'codigo_voo').'</td>';
      $l_html.=chr(13).'      </tr>';
    } 
    $l_html.=chr(13).'         </table></td></tr>';
  } 

  // Benefícios servidor
  $RS = db_getSolicData::getInstanceOf($dbms,$l_chave,'PDGERAL');
/*
  if (count($RS)>0) {
    $l_html.=chr(13).'        <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Benefícios recebidos pelo proposto</td>';
    $l_html.=chr(13).'        <tr><td align="center" colspan="2">';
    $l_html.=chr(13).'          <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
    $l_html.=chr(13).'            <tr bgcolor="'.$w_TrBgColor.'">';
    if (Nvl(f($RS,'valor_alimentacao'),0)>0) $l_html.=chr(13).'           <td>Auxílio-alimentação: <b>Sim</b></td>'; else $l_html.=chr(13).'           <td>Auxílio-alimentação: <b>Não</b></td>';
    $l_html.=chr(13).'              <td>Valor R$: <b>'.formatNumber(Nvl(f($RS,'valor_alimentacao'),0)).'</b></td>';
    $l_html.=chr(13).'            </tr>';
    $l_html.=chr(13).'            <tr bgcolor="'.$w_TrBgColor.'">';
    if (Nvl(f($RS,'valor_transporte'),0)>0) $l_html.=chr(13).'           <td>Auxílio-transporte: <b>Sim</b></td>'; else $l_html.=chr(13).'           <td>Auxílio-transporte: <b>Não</b></td>';
    $l_html.=chr(13).'              <td>Valor R$: <b>'.formatNumber(Nvl(f($RS,'valor_transporte'),0)).'</b></td>';
    $l_html.=chr(13).'            </tr>';
    $l_html.=chr(13).'          </table></td></tr>';
  } 
*/
  
  //Diárias
  $l_html.=chr(13).'        <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Dados da viagem/cálculo das diárias</td>';

  $RSQuery = db_getPD_Deslocamento::getInstanceOf($dbms,$l_chave,null,'PDDIARIA');
  $RSQuery = SortArray($RSQuery,'phpdt_saida','asc', 'phpdt_chegada', 'asc');
  
  if (count($RSQuery)>0) {
    $i = 1;
    foreach($RSQuery as $row) {
      $w_trechos[$i][1]  = f($row,'sq_diaria');
      $w_trechos[$i][2]  = f($row,'sq_deslocamento');
      $w_trechos[$i][3]  = f($row,'sq_deslocamento');
      $w_trechos[$i][4]  = f($row,'cidade_dest');
      $w_trechos[$i][5]  = f($row,'nm_destino');
      $w_trechos[$i][6]  = f($row,'phpdt_chegada');
      $w_trechos[$i][7]  = f($row,'phpdt_saida');
      $w_trechos[$i][8]  = Nvl(f($row,'quantidade'),0);
      $w_trechos[$i][9]  = Nvl(f($row,'valor'),0);
      $w_trechos[$i][10] = f($row,'saida');
      $w_trechos[$i][11] = f($row,'chegada');
      $w_trechos[$i][12] = f($row,'diaria');
      $w_trechos[$i][13] = f($row,'sg_moeda_diaria');
      $w_trechos[$i][14] = f($row,'vl_diaria');
      $w_trechos[$i][15] = f($row,'hospedagem');
      $w_trechos[$i][16] = Nvl(f($row,'hospedagem_qtd'),0);
      $w_trechos[$i][17] = Nvl(f($row,'hospedagem_valor'),0);
      $w_trechos[$i][18] = f($row,'sg_moeda_hospedagem');
      $w_trechos[$i][19] = f($row,'vl_diaria_hospedagem');
      $w_trechos[$i][20] = f($row,'veiculo');
      $w_trechos[$i][21] = Nvl(f($row,'veiculo_qtd'),0);
      $w_trechos[$i][22] = Nvl(f($row,'veiculo_valor'),0);
      $w_trechos[$i][23] = f($row,'sg_moeda_veiculo');
      $w_trechos[$i][24] = f($row,'vl_diaria_veiculo');
      $w_trechos[$i][25] = f($row,'sq_valor_diaria');
      $w_trechos[$i][26] = f($row,'sq_diaria_hospedagem');
      $w_trechos[$i][27] = f($row,'sq_diaria_veiculo');
      $w_trechos[$i][28] = f($row,'justificativa_diaria');
      $w_trechos[$i][29] = f($row,'justificativa_veiculo');
      $w_trechos[$i][30] = f($row,'compromisso');
      $w_trechos[$i][31] = f($row,'compromisso');
      $w_trechos[$i][32] = 'N';

      // Cria array para guardar o valor total por moeda
      if ($w_trechos[$i][13]>'') $w_tot_diaria[$w_trechos[$i][13]] = 0;
      if ($w_trechos[$i][18]>'') $w_tot_diaria[$w_trechos[$i][18]] = 0;
      if ($w_trechos[$i][12]>'') $w_tot_diaria[$w_trechos[$i][23]] = 0;
      if ($i==1) {
        // Se a primeira saída for após as 18:00, deduz meia diária
        if (intVal(str_replace(':','',formataDataEdicao(f($row,'phpdt_saida'),2)))>180000) {
          $w_trechos[$i][32] = 'S';
        }
      } else {
        // Se a última chegada for até 12:00, deduz meia diária
        if ($i==count($RS) && intVal(str_replace(':','',formataDataEdicao(f($row,'phpdt_chegada'),2)))<=120000) {
          $w_trechos[$i-1][32] = 'S';
        }
        $w_trechos[$i-1][3]   = f($row,'sq_deslocamento');
        $w_trechos[$i-1][7] = f($row,'phpdt_saida');
        $w_trechos[$i-1][31] = f($row,'compromisso');
      }
      $i += 1;
    } 
    $l_html.=chr(13).'     <tr><td align="center" colspan="2">';
    $l_html.=chr(13).'       <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
    $l_html.=chr(13).'         <tr bgcolor="'.$conTrBgColor.'" align="center">';
    $l_html.=chr(13).'           <td><b>Destino</td>';
    $l_html.=chr(13).'           <td><b>Chegada</td>';
    $l_html.=chr(13).'           <td><b>Saída</td>';
    $l_html.=chr(13).'         </tr>';
    $w_cor          = $conTrBgColor;
    $j              = $i;
    $i              = 1;
    $w_diarias      = 0;
    $w_locacoes     = 0;
    $w_hospedagens  = 0;
    $w_tot_local    = 0;
    while($i!=($j-1)) {
      $w_max_hosp     = (toDate(formataDataEdicao($w_trechos[$i][7]))-toDate(formataDataEdicao($w_trechos[$i][6])))/86400;
      if ($w_max_hosp >=0) {
        $w_diarias      = nvl($w_trechos[$i][8],0)*nvl($w_trechos[$i][9],0);
        $w_locacoes     = -1*nvl($w_trechos[$i][9],0)*nvl($w_trechos[$i][22],0)/100*nvl($w_trechos[$i][21],0);
        $w_hospedagens  = nvl($w_trechos[$i][16],0)*nvl($w_trechos[$i][17],0);
        
        if ($w_diarias>0)     $w_tot_diaria[$w_trechos[$i][13]] += $w_diarias;
        if ($w_locacoes<>0)   $w_tot_diaria[$w_trechos[$i][23]] += $w_locacoes;
        if ($w_hospedagens>0) $w_tot_diaria[$w_trechos[$i][18]] += $w_hospedagens;
        
        $w_tot_local = $w_diarias + $w_hospedagens + $w_locacoes;
                       
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        $l_html.=chr(13).'     <tr valign="top" bgcolor="'.$w_cor.'">';
        $l_html.=chr(13).'       <td rowspan="2"><b>'.$w_trechos[$i][5].': '.$w_max_hosp;
        if ($w_max_hosp==1) $l_html.=chr(13).' dia'; else $l_html.=chr(13).' dias';
        if ($w_trechos[$i][32]=='S') {
          if ($i==1) $l_html.=chr(13).'<br>Embarque após as 18:00'; else $l_html.=chr(13).'<br>Desembarque até as 12:00';
        }
        if ($i==1 && $w_trechos[$i][30]=='N') $l_html.=chr(13).'<br>Sem compromisso na data';
        if ($i>1  && $w_trechos[$i][31]=='N') $l_html.=chr(13).'<br>Sem compromisso na data';
        $l_html.=chr(13).'<br>'.$w_trechos[$i][13].' '.formatNumber($w_tot_local);
        $l_html.=chr(13).'       <td align="center"><b>'.substr(FormataDataEdicao($w_trechos[$i][6],4),0,-3).'</b></td>';
        $l_html.=chr(13).'       <td align="center"><b>'.substr(FormataDataEdicao($w_trechos[$i][7],4),0,-3).'</b></td>';
        $l_html.=chr(13).'     <tr bgcolor="'.$w_cor.'"><td colspan="2"><table width="100%" border=1>';
        $l_html.=chr(13).'       <tr valign="top" align="center">';
        $l_html.=chr(13).'         <td>Item';
        $l_html.=chr(13).'         <td width="20%">Quantidade';
        $l_html.=chr(13).'         <td width="20%">$ Unitário';
        $l_html.=chr(13).'         <td width="20%">$ Total';
        $l_html.=chr(13).'       </tr>';
        if ($w_trechos[$i][25]>'' && nvl(f($RS,'diaria'),'')!='') {
          $l_html.=chr(13).'       <tr valign="top">';
          $l_html.=chr(13).'         <td>Diária ('.$w_trechos[$i][13].')</td>';
          $l_html.=chr(13).'         <td align="right">'.formatNumber($w_trechos[$i][8],1).'</td>';
          $l_html.=chr(13).'         <td align="right">'.formatNumber($w_trechos[$i][9]).'</td>';
          $l_html.=chr(13).'         <td align="right">'.formatNumber($w_diarias,2).'</td>';
          $l_html.=chr(13).'       </tr>';
        }
        if ($w_trechos[$i][27]>'' && f($RS,'veiculo')=='S' && $w_trechos[$i][21]>0) {
          $l_html.=chr(13).'       <tr valign="top">';
          $l_html.=chr(13).'         <td>Veículo ('.$w_trechos[$i][23].') -'.formatNumber($w_trechos[$i][24],0).'%</td>';
          $l_html.=chr(13).'         <td align="right">'.formatNumber($w_trechos[$i][21],1).'</td>';
          $l_html.=chr(13).'         <td align="right">'.formatNumber(-1*$w_trechos[$i][9]*$w_trechos[$i][22]/100).'</td>';
          $l_html.=chr(13).'         <td align="right">'.formatNumber($w_locacoes,2).'</td>';
          $l_html.=chr(13).'       </tr>';
        }
        if ($w_trechos[$i][26]>'' && f($RS,'hospedagem')=='S' && $w_trechos[$i][16]>0) {
          $l_html.=chr(13).'       <tr valign="top">';
          $l_html.=chr(13).'         <td>Hospedagem ('.$w_trechos[$i][18].')</td>';
          $l_html.=chr(13).'         <td align="right">'.formatNumber($w_trechos[$i][16],1).'</td>';
          $l_html.=chr(13).'         <td align="right">'.formatNumber($w_trechos[$i][17]).'</td>';
          $l_html.=chr(13).'         <td align="right">'.formatNumber($w_hospedagens,2).'</td>';
          $l_html.=chr(13).'       </tr>';
        }
        if ($w_trechos[$i][28]>'') {
          $l_html.=chr(13).'       <tr valign="top">';
          $l_html.=chr(13).'         <td colspan=4><b>Justificativa para diária acima do permitido:</b><br>'.crLf2Br($w_trechos[$i][28]).'</td>';
          $l_html.=chr(13).'       </tr>';
        }
        if ($w_trechos[$i][29]>'') {
          $l_html.=chr(13).'       <tr valign="top">';
          $l_html.=chr(13).'         <td colspan=4><b>Justificativa para locação de veículo:</b><br>'.crLf2Br($w_trechos[$i][29]).'</td>';
          $l_html.=chr(13).'       </tr>';
        }
        $l_html.=chr(13).'     </tr></table>';
      }
      $i += 1;
    }
  
/*
    $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'">';
    $l_html.=chr(13).'          <td rowspan="5" align="right">&nbsp;</td>';
    $l_html.=chr(13).'          <td><b>(a) subtotal:</b></td>';
    $l_html.=chr(13).'          <td align="right" bgcolor="'.$conTrAlternateBgColor.'">'.formatNumber(Nvl($w_total,0)).'</td>';
    $l_html.=chr(13).'        </tr>';
    $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'">';
    $l_html.=chr(13).'          <td><b>(b) adicional:</b></td>';
    $l_html.=chr(13).'          <td align="right" bgcolor="'.$conTrBgColor.'">'.formatNumber(Nvl(f($RS,'valor_adicional'),0)).'</td>';
    $l_html.=chr(13).'        </tr>';
    $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'">';
    $l_html.=chr(13).'          <td><b>(c) desconto auxílio-alimentação:</b></td>';
    $l_html.=chr(13).'          <td align="right" bgcolor="'.$conTrBgColor.'">'.formatNumber(Nvl(f($RS,'desconto_alimentacao'),0)).'</td>';
    $l_html.=chr(13).'        </tr>';
    $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'">';
    $l_html.=chr(13).'          <td><b>(d) desconto auxílio-transporte:</b></td>';
    $l_html.=chr(13).'          <td align="right" bgcolor="'.$conTrBgColor.'">'.formatNumber(Nvl(f($RS,'desconto_transporte'),0)).'</td>';
    $l_html.=chr(13).'        </tr>';
    $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'">';
    $l_html.=chr(13).'          <td><b>Total(a + b - c - d):</b></td>';
    $l_html.=chr(13).'          <td align="right" bgcolor="'.$conTrAlternateBgColor.'">'.formatNumber(Nvl($w_total,0)+Nvl(f($RS,'valor_adicional'),0)-Nvl(f($RS,'desconto_alimentacao'),0)-Nvl(f($RS,'desconto_transporte'),0)).'</td>';
    $l_html.=chr(13).'        </tr>';
*/
    $l_html.=chr(13).'        </table></td></tr>';
    $l_html.=chr(13).'     <tr><td align="center"><b>TOTAL SOLICITADO:';
    foreach($w_tot_diaria as $k => $v) {
      $l_html.=chr(13).'       &nbsp;&nbsp;&nbsp;&nbsp;'.$k.' '.formatNumber($v);
    }
    $l_html.=chr(13).'     </tr>';
  } 

  // Bilhete de passagem
  $RS = db_getPD_Deslocamento::getInstanceOf($dbms,$l_chave,null,$SG);
  $RS = SortArray($RS,'phpdt_saida','asc', 'phpdt_chegada', 'asc');
  if (count($RS)>0) {
    $i=0;
    $j=0;
    foreach($RS as $row) {
      if (nvl(f($row,'sq_cia_transporte'),'')>'') {
        if ($i==0) {
          $l_html.=chr(13).'        <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Bilhete de passagem</td>';
          $l_html.=chr(13).'        <tr><td align="center" colspan="2"><TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
          $l_html.=chr(13).'         <tr bgcolor="'.$conTrBgColor.'" align="center">';
          $l_html.=chr(13).'         <td><b>Origem</td>';
          $l_html.=chr(13).'         <td><b>Destino</td>';
          $l_html.=chr(13).'         <td><b>Saida</td>';
          $l_html.=chr(13).'         <td><b>Chegada</td>';
          $l_html.=chr(13).'         <td><b>Cia. transporte</td>';
          $l_html.=chr(13).'         <td><b>Código vôo</td>';
          $l_html.=chr(13).'         </tr>';
          $w_cor=$conTrBgColor;
          $i=1;
        }
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        $l_html.=chr(13).'     <tr valign="middle" bgcolor="'.$w_cor.'">';
        $l_html.=chr(13).'       <td>'.Nvl(f($row,'nm_origem'),'---').'</td>';
        $l_html.=chr(13).'       <td>'.Nvl(f($row,'nm_destino'),'---').'</td>';
        $l_html.=chr(13).'       <td align="center">'.substr(FormataDataEdicao(f($row,'phpdt_saida'),3),0,-3).'</td>';
        $l_html.=chr(13).'       <td align="center">'.substr(FormataDataEdicao(f($row,'phpdt_chegada'),3),0,-3).'</td>';
        $l_html.=chr(13).'       <td>'.Nvl(f($row,'nm_cia_transporte'),'---').'</td>';
        $l_html.=chr(13).'       <td>'.Nvl(f($row,'codigo_voo'),'---').'</td>';
        $l_html.=chr(13).'     </tr>';
        $j=1;
      }
    } 
    if ($j==1) {
      $l_html.=chr(13).'        </tr>';
      $l_html.=chr(13).'        </table></td></tr>';
      $RS = db_getSolicData::getInstanceOf($dbms,$l_chave,'PDGERAL');
      $l_html.=chr(13).'        <tr><td align="center" colspan="2"><TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
      $l_html.=chr(13).'        <tr><td colspan="2"><b>Nº do PTA/Ticket: </b>'.f($RS,'PTA').'</td>';
      $l_html.=chr(13).'        <tr><td><b>Data da emissão: </b>'.FormataDataEdicao(f($RS,'emissao_bilhete')).'</td>';
      $l_html.=chr(13).'            <td><b>Valor das passagens R$: </b>'.formatNumber(Nvl(f($RS,'valor_passagem'),0)).'</td>';
      $l_html.=chr(13).'      </table>';
      $l_html.=chr(13).'    </td>';
    }
  } 

  // Arquivos gerados para a PCD
  if ($w_or_tramite > 4) {
    $l_html.=chr(13).'        <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Arquivos</td>';
    $l_html.=chr(13).'        <tr><td align="center" colspan="2">';
    $l_html.=chr(13).'          <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
    $l_html.=chr(13).'            <tr bgcolor="'.$w_TrBgColor.'"><td><a target="Emissao" class="hl" title="Emitir autorização e proposta de concessão." href="'.$w_dir.$w_pagina.'Emissao&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.$l_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&SG='.$SG.'">Autorização para emissão de bilhetes</A>';
    $l_html.=chr(13).'            <tr bgcolor="'.$w_TrBgColor.'"><td><a target="Relatorio" class="hl" title="Emitir relatório para prestacao de contas." href="'.$w_dir.$w_pagina.'Prestacaocontas&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.$l_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Relatório de viagem</A>';
    $l_html.=chr(13).'            </tr>';
    $l_html.=chr(13).'          </table></td></tr>';
  }

  // Se for envio, executa verificações nos dados da solicitação
  $w_erro = ValidaViagem($w_cliente,$l_chave,'PDGERAL',null,null,null,Nvl($w_tramite,0));
  if ($w_erro>'') {
    $l_html.=chr(13).'<tr bgcolor="'.$w_TrBgColor.'"><td colspan=2>';
    $l_html.=chr(13).'<HR>';
    if (substr($w_erro,0,1)=='0') {
      $l_html.=chr(13).'  <font color="#BC3131"><b>ATENÇÃO:</b> Foram identificados os erros listados abaixo, não sendo possível seu encaminhamento para fases posteriores à atual.</font>';
    } elseif (substr($w_erro,0,1)=='1') {
      $l_html.=chr(13).'  <font color="#BC3131"><b>ATENÇÃO:</b> Foram identificados os erros listados abaixo. Seu encaminhamento para fases posteriores à atual só pode ser feito por um gestor do sistema ou do módulo de projetos.</font>';
    } else {
      $l_html.=chr(13).'  <font color="#BC3131"><b>ATENÇÃO:</b> Foram identificados os alertas listados abaixo. Eles não impedem o encaminhamento para fases posteriores à atual, mas convém sua verificação.</font>';
    } 
    $l_html.=chr(13).'  <ul>'.substr($w_erro,1,1000).'</ul>';
    $l_html.=chr(13).'  </td></tr>';
  } 

  if ($l_O=='L' || $l_O=='V' || $l_O=='T') {
    // Se for listagem dos dados
    // Encaminhamentos
    $RS = db_getSolicLog::getInstanceOf($dbms,$l_chave,null,'LISTA');
    $RS = SortArray($RS,'phpdt_data','desc', 'sq_siw_solic_log', 'desc');

    $l_html.=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Ocorrências e Anotações</td>';
    $l_html.=chr(13).'      <tr><td align="center" colspan="2">';
    $l_html.=chr(13).'        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
    $l_html.=chr(13).'          <tr bgcolor="'.$w_TrBgColor.'" align="center">';
    $l_html.=chr(13).'            <td><b>Data</td>';
    $l_html.=chr(13).'            <td><b>Despacho/Observação</td>';
    $l_html.=chr(13).'            <td><b>Responsável</td>';
    $l_html.=chr(13).'            <td><b>Fase</td>';
    $l_html.=chr(13).'          </tr>';
    if (count($RS)<=0) {
      $l_html.=chr(13).'      <tr bgcolor="'.$w_TrBgColor.'"><td colspan=6 align="center"><b>Não foram encontrados encaminhamentos.</b></td></tr>';
    } else {
      $l_html.=chr(13).'      <tr bgcolor="'.$w_TrBgColor.'" valign="top">';
      $i = 0;
      foreach($RS as $row) {
        if ($i==0) {
          $l_html.=chr(13).'        <td colspan=6>Fase atual: <b>'.f($row,'fase').'</b></td>';
          $w_cor = $w_TrBgColor;
          if ($w_tramite_ativo=='S') {
            // Recupera os responsáveis pelo tramite
            $RS1 = db_getTramiteResp::getInstanceOf($dbms,$l_chave,null,null);
            $l_html.=chr(13).'      <tr bgcolor="'.$w_TrBgColor.'" valign="top">';
            $l_html.=chr(13).'        <td colspan=6>Responsáveis pelo trâmite: <b>';
            if (count($RS1)>0) {
              $j = 0;
              foreach($RS1 as $row1) {
                if ($j==0) {
                  $w_tramite_resp = f($row1,'nome_resumido');
                  if($l_tipo!='WORD') $l_html.=chr(13).ExibePessoa($w_dir_volta,$w_cliente,f($row1,'sq_pessoa'),$TP,f($row1,'nome_resumido'));
                  else                $l_html.=chr(13).f($row1,'nome_resumido');
                  $j = 1;
                } else {
                  if (strpos($w_tramite_resp,f($row,'nome_resumido'))===false) {
                    if($l_tipo!='WORD') $l_html.=chr(13).', '.ExibePessoa($w_dir_volta,$w_cliente,f($row1,'sq_pessoa'),$TP,f($row1,'nome_resumido'));
                    else                $l_html.=chr(13).', '.f($row1,'nome_resumido');
                  }
                  $w_tramite_resp.=f($row1,'nome_resumido');
                }
              } 
            } 
            $l_html.=chr(13).'</b></td>';
          } 
          $i = 1;
        }
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        $l_html.=chr(13).'      <tr valign="top" bgcolor="'.$w_cor.'">';
        $l_html.=chr(13).'        <td nowrap>'.FormataDataEdicao(f($row,'phpdt_data'),3).'</td>';
        if (Nvl(f($row,'caminho'),'')>'' && $l_tipo!='WORD') {
          $l_html.=chr(13).'        <td>'.CRLF2BR(Nvl(f($row,'despacho'),'---').'<br>'.LinkArquivo('HL',$w_cliente,f($row,'sq_siw_arquivo'),'_blank','Clique para exibir o anexo em outra janela.','Anexo - '.f($row,'tipo').' - '.round(f($row,'tamanho')/1024,1).' KB',null)).'</td>';
        } else {
          $l_html.=chr(13).'        <td>'.CRLF2BR(Nvl(f($row,'despacho'),'---')).'</td>';
        } 
        if ($l_tipo!='WORD') {
          $l_html.=chr(13).'        <td nowrap>'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'responsavel')).'</td>';
        } else {
          $l_html.=chr(13).'        <td nowrap>'.f($row,'responsavel').'</td>';
        } 
        if (nvl(f($row,'chave_log'),'')>'' && nvl(f($row,'destinatario'),'')>'') {
          if ($l_tipo!='WORD') {
            $l_html.=chr(13).'        <td nowrap>'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'sq_pessoa_destinatario'),$TP,f($row,'destinatario')).'</td>';
          } else {
            $l_html.=chr(13).'        <td nowrap>'.f($row,'destinatario').'</td>';
          } 
        } elseif (f($row,'origem')=='ANOTACAO') {
          $l_html.=chr(13).'        <td nowrap>Anotação</td>';
        } else {
          if(strpos(f($row,'despacho'),'***')!==false) {
            $l_html.=chr(13).'        <td nowrap>---</td>';
          } else {
            $l_html.=chr(13).'        <td nowrap>'.Nvl(f($row,'tramite'),'---').'</td>';
          }
        } 
        $l_html.=chr(13).'      </tr>';
      } 
    } 
    $l_html.=chr(13).'         </table></td></tr>';
  } 
  $l_html.=chr(13).'    </table>';
  $l_html.=chr(13).'</table>';

  return $l_html;
} 
?>
