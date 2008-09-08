<?
// =========================================================================
// Rotina de visualiza��o dos dados da miss�o
// -------------------------------------------------------------------------
function VisualViagem($l_chave,$l_O,$l_usuario,$l_P1,$l_tipo) {
  extract($GLOBALS);

  if ($l_tipo=='WORD') $w_TrBgColor=''; else $w_TrBgColor=$conTrBgColor;
  $w_html='';

// Recupera os dados da viagem
  $RS = db_getSolicData::getInstanceOf($dbms,$l_chave,substr($SG,0,3).'GERAL');
  $w_tramite    = f($RS,'sq_siw_tramite');
  $w_valor      = f($RS,'valor');
  $w_fim        = f($RS,'fim');
  $w_sg_tramite = f($RS,'sg_tramite');
  $w_or_tramite = f($RS,'or_tramite');
  $w_tramite_ativo      = f($RS,'ativo');

  // Recupera o tipo de vis�o do usu�rio
  if (Nvl(f($RS,'solicitante'),0)==$l_usuario || 
      Nvl(f($RS,'executor'),0)==$l_usuario || 
      Nvl(f($RS,'cadastrador'),0)==$l_usuario || 
      Nvl(f($RS,'titular'),0)==$l_usuario || 
      Nvl(f($RS,'substituto'),0)==$l_usuario || 
      Nvl(f($RS,'tit_exec'),0)==$l_usuario || 
      Nvl(f($RS,'subst_exec'),0)==$l_usuario || 
      SolicAcesso($l_chave,$l_usuario)>=8) {
    // Se for solicitante, executor ou cadastrador, tem vis�o completa
    $w_tipo_visao=0;
  } else {
    if (SolicAcesso($l_chave,$l_usuario)>2) $w_tipo_visao=1;
  } 

  // Se for listagem ou envio, exibe os dados de identifica��o do acordo
  if ($l_O=='L' || $l_O=='V') {
    // Se for listagem dos dados
    $w_html .= chr(13).'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
    $w_html .= chr(13).'<tr bgcolor="'.$w_TrBgColor.'"><td>';
    $w_html .= chr(13).'    <table width="99%" border="0">';
    $w_html .= chr(13).'      <tr><td colspan=2>N�mero: <b>'.f($RS,'codigo_interno').' ('.$l_chave.')<br>'.'</b></td></tr>';
    // Identifica��o da PCD
    $w_html .= chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Identifica��o</td>';
    $w_html .= chr(13).'      <tr><td>Descri��o:<br><b>'.f($RS,'descricao').'</b></td>';
    $w_html .= chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
    $w_html .= chr(13).'          <tr valign="top">';
    if (!$l_tipo=='WORD') {
      $w_html .= chr(13).'          <td>Unidade proponente:<br><b>'.ExibeUnidade($w_dir_volta,$w_cliente,f($RS,'nm_unidade_resp'),f($RS,'sq_unidade_resp'),$TP).'</b></td>';
    } else {
      $w_html .= chr(13).'          <td>Unidade proponente:<br><b>'.f($RS,'nm_unidade_resp').'</b></td>';
    } 
    $w_html .= chr(13).'          <td valign="top" colspan="2">Tipo:<br><b>'.f($RS,'nm_tipo_missao').' </b></td>';
    $w_html .= chr(13).'          <td>Primeira sa�da:<br><b>'.FormataDataEdicao(f($RS,'inicio')).' </b></td>';
    $w_html .= chr(13).'          <td>�ltimo retorno:<br><b>'.FormataDataEdicao(f($RS,'fim')).' </b></td>';
    $w_html .= chr(13).'          </table>';
    if (Nvl(f($RS,'justificativa_dia_util'),'')>'') {
      // Se o campo de justificativa de dias �teis para estiver preenchido, exibe
      $w_html .= chr(13).'      <tr><td valign="top" colspan="2">Justificativa para in�cio e t�rmino de viagens em sextas-feiras, s�bados, domingos e feriados:<br><b>'.CRLF2BR(f($RS,'justificativa_dia_util')).' </b></td>';
    } 
    if (Nvl(f($RS,'justificativa'),'')>'') {
      // Se o campo de justificativa estiver preenchido, exibe
      $w_html .= chr(13).'      <tr><td valign="top" colspan="2">Justificativa do n�o cumprimento do prazo de solicita��o:<br><b>'.CRLF2BR(f($RS,'justificativa')).' </b></td>';
    } 
    // Dados da conclus�o da demanda, se ela estiver nessa situa��o
    if (Nvl(f($RS,'conclusao'),'')>'') {
      $w_html .= chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Dados do encerramento</td>';
      $w_html .= chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
      $w_html .= chr(13).'          <tr valign="top">';
      $w_html .= chr(13).'          <td>In�cio da vig�ncia:<br><b>'.FormataDataEdicao(f($RS,'inicio_real')).' </b></td>';
      $w_html .= chr(13).'          <td>T�rmino da vig�ncia:<br><b>'.FormataDataEdicao(f($RS,'fim_real')).' </b></td>';
      if ($w_tipo_visao==0) {
        $w_html .= chr(13).'          <td>Valor realizado:<br><b>'.formatNumber(f($RS,'custo_real')).' </b></td>';
      } 
      $w_html .= chr(13).'          </table>';
      if ($w_tipo_visao==0) {
        $w_html .= chr(13).'      <tr><td valign="top">Nota de conclus�o:<br><b>'.CRLF2BR(f($RS,'observacao')).' </b></td>';
      } 
    } 
    // Vincula��es a atividades
    $RS1 = db_getPD_Vinculacao::getInstanceOf($dbms,$l_chave,null,null);
    $RS1 = SortArray($RS1,'inicio','asc');
    if (count($RS1)>0) {
      $w_html .= chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Vincula��es</td>';
      $w_html .= chr(13).'      <tr><td align="center" colspan="2">';
      $w_html .= chr(13).'        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
      $w_html .= chr(13).'          <tr bgcolor="'.$w_TrBgColor.'" align="center">';
      $w_html .= chr(13).'          <td><b>N�</td>';
      $w_html .= chr(13).'          <td><b>Projeto</td>';
      $w_html .= chr(13).'          <td><b>Detalhamento</td>';
      $w_html .= chr(13).'          <td><b>In�cio</td>';
      $w_html .= chr(13).'          <td><b>Fim</td>';
      $w_html .= chr(13).'          <td><b>Situa��o</td>';
      $w_html .= chr(13).'          </tr>';
      $w_cor=$w_TrBgColor;
      $w_total=0;
      foreach($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        $w_html .= chr(13).'      <tr valign="top" bgcolor="'.$w_cor.'">';
        $w_html .= chr(13).'        <td nowrap>';
        if (f($row,'concluida')=='N') {
          if (f($row,'fim')<addDays(time(),-1)) {
            $w_html .= chr(13).'           <img src="'.$conImgAtraso.'" border=0 width=15 heigth=15 align="center">';
          } elseif (f($row,'aviso_prox_conc')=='S' && (f($row,'aviso')<=addDays(time(),-1))) {
            $w_html .= chr(13).'           <img src="'.$conImgAviso.'" border=0 width=15 height=15 align="center">';
          } else {
            $w_html .= chr(13).'           <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">';
          } 
        } else {
          if (f($row,'fim')<Nvl(f($row,'fim_real'),f($row,'fim'))) {
            $w_html .= chr(13).'           <img src="'.$conImgOkAtraso.'" border=0 width=15 heigth=15 align="center">';
          } else {
            $w_html .= chr(13).'           <img src="'.$conImgOkNormal.'" border=0 width=15 height=15 align="center">';
          } 
        } 
        if (nvl(f($row,'sq_projeto'),'')=='') {
          $w_html .= chr(13).'        <A class="HL" TARGET="VISUAL" HREF="demanda.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informa��es deste registro.">'.f($row,'sq_siw_solicitacao').'&nbsp;</a>';
        } else {
          $w_html .= chr(13).'        <A class="HL" TARGET="VISUAL" HREF="projetoativ.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informa��es deste registro.">'.f($row,'sq_siw_solicitacao').'&nbsp;</a>';
        }
        $w_html .= chr(13).'        <td>'.nvl(f($row,'nm_projeto'),'---').'</td>';
        if (strlen(Nvl(f($row,'assunto'),'-'))>50) $w_assunto=substr(Nvl(f($row,'assunto'),'-'),0,50).'...'; else $w_assunto=Nvl(f($row,'assunto'),'-');
        if (f($row,'sg_tramite')=='CA') {
          $w_html .= chr(13).'        <td title="'.htmlspecialchars(f($row,'assunto')).'"><strike>'.$w_assunto.'</strike></td>';
        } else {
          $w_html .= chr(13).'        <td title="'.htmlspecialchars(f($row,'assunto')).'">'.$w_assunto.'</td>';
        } 
        if (f($row,'concluida')=='N') {
          $w_html .= chr(13).'        <td align="center">'.FormataDataEdicao(f($row,'inicio')).'</td>';
          $w_html .= chr(13).'        <td align="center">'.FormataDataEdicao(f($row,'fim')).'</td>';
        } else {
          $w_html .= chr(13).'        <td align="center">'.FormataDataEdicao(f($row,'inicio_real')).'</td>';
          $w_html .= chr(13).'        <td align="center">'.FormataDataEdicao(f($row,'fim_real')).'</td>';
        } 
        $w_html .= chr(13).'        <td>'.f($row,'nm_tramite').'</td>';
        $w_html .= chr(13).'      </tr>';
      } 
      $w_html .= chr(13).'         </table></td></tr>';
    } 

    // Outra parte
    $RSQuery = db_getBenef::getInstanceOf($dbms,$w_cliente,Nvl(f($RS,'sq_prop'),0),null,null,null,null,1,null,null,null,null,null,null,null);
    $w_html .= chr(13).'      <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Proposto</td>';
    if (count($RSQuery)==0) {
      $w_html .= chr(13).'      <tr><td colspan=2><b>Proposto n�o informado.';
    } else {
      foreach($RSQuery as $row) { $RSQuery = $row; break; }
      $w_html .= chr(13).'      <tr><td colspan=2><table border=0 width="100%">';
      $w_html .= chr(13).'      <tr valign="top">';
      $w_html .= chr(13).'          <td>CPF:<b><br>'.f($RSQuery,'cpf').'</td>';
      $w_html .= chr(13).'          <td>Nome:<b><br>'.f($RSQuery,'nm_pessoa').'</td>';
      $w_html .= chr(13).'          <td>Nome resumido:<b><br>'.f($RSQuery,'nome_resumido').'</td>';
      $w_html .= chr(13).'          <td>Sexo:<b><br>'.f($RSQuery,'nm_sexo').'</td>';
      $w_html .= chr(13).'      <tr valign="top">';
      $w_html .= chr(13).'          <td>Matr�cula:<b><br>'.Nvl(f($RS,'matricula'),'---').'</td>';
      $w_html .= chr(13).'          <td>Identidade:<b><br>'.Nvl(f($RSQuery,'rg_numero'),'---').'</td>';
      $w_html .= chr(13).'          <td>Data de emiss�o:<b><br>'.FormataDataEdicao(Nvl(f($RSQuery,'rg_emissao'),'---')).'</td>';
      $w_html .= chr(13).'          <td>�rg�o emissor:<b><br>'.Nvl(f($RSQuery,'rg_emissor'),'---').'</td>';
      $w_html .= chr(13).'      <tr><td colspan="4" align="center" style="border: 1px solid rgb(0,0,0);"><b>Telefones</td>';
      $w_html .= chr(13).'      <tr valign="top">';
      $w_html .= chr(13).'          <td>Telefone:<b><br>('.Nvl(f($RSQuery,'ddd'),'---').') '.Nvl(f($RSQuery,'nr_telefone'),'---').'</td>';
      $w_html .= chr(13).'          <td>Fax:<b><br>'.Nvl(f($RSQuery,'nr_fax'),'---').'</td>';
      $w_html .= chr(13).'          <td>Celular:<b><br>'.Nvl(f($RSQuery,'nr_celular'),'---').'</td>';
      $w_html .= chr(13).'      <tr><td colspan="4" align="center" style="border: 1px solid rgb(0,0,0);"><b>Dados banc�rios</td>';
      if (true) { //(!(strpos('CREDITO,DEPOSITO',$w_forma_pagamento)===false)) {
        $w_html .= chr(13).'          <tr valign="top">';
        if (Nvl(f($RS,'cd_banco'),'')>'') {
          $w_html .= chr(13).'          <td>Banco:<b><br>'.f($RS,'cd_banco').' - '.f($RS,'nm_banco').'</td>';
          $w_html .= chr(13).'          <td>Ag�ncia:<b><br>'.f($RS,'cd_agencia').' - '.f($RS,'nm_agencia').'</td>';
          if (f($RS,'exige_operacao')=='S') $w_html .= chr(13).'          <td>Opera��o:<b><br>'.Nvl(f($RS,'operacao_conta'),'---').'</td>';
          $w_html .= chr(13).'          <td>N�mero da conta:<b><br>'.Nvl(f($RS,'numero_conta'),'---').'</td>';
        } else {
          $w_html .= chr(13).'          <td>Banco:<b><br>---</td>';
          $w_html .= chr(13).'          <td>Ag�ncia:<b><br>---</td>';
          if (f($RS,'exige_operacao')=='S') $w_html .= chr(13).'          <td>Opera��o:<b><br>---</td>';
          $w_html .= chr(13).'          <td>N�mero da conta:<b><br>---</td>';
        } 
      } elseif (f($RS,'sg_forma_pagamento')=='ORDEM') {
        $w_html .= chr(13).'          <tr valign="top">';
        if (Nvl(f($RS,'cd_banco'),'')>'') {
          $w_html .= chr(13).'          <td>Banco:<b><br>'.f($RS,'cd_banco').' - '.f($RS,'nm_banco').'</td>';
          $w_html .= chr(13).'          <td>Ag�ncia:<b><br>'.f($RS,'cd_agencia').' - '.f($RS,'nm_agencia').'</td>';
        } else {
          $w_html .= chr(13).'          <td>Banco:<b><br>---</td>';
          $w_html .= chr(13).'          <td>Ag�ncia:<b><br>---</td>';
        } 
      } elseif (f($RS,'sg_forma_pagamento')=='EXTERIOR') {
        $w_html .= chr(13).'          <tr valign="top">';
        $w_html .= chr(13).'          <td>Banco:<b><br>'.f($RS,'banco_estrang').'</td>';
        $w_html .= chr(13).'          <td>ABA Code:<b><br>'.Nvl(f($RS,'aba_code'),'---').'</td>';
        $w_html .= chr(13).'          <td>SWIFT Code:<b><br>'.Nvl(f($RS,'swift_code'),'---').'</td>';
        $w_html .= chr(13).'          <tr><td colspan=3>Endere�o da ag�ncia:<b><br>'.Nvl(f($RS,'endereco_estrang'),'---').'</td>';
        $w_html .= chr(13).'          <tr valign="top">';
        $w_html .= chr(13).'          <td colspan=2>Ag�ncia:<b><br>'.Nvl(f($RS,'agencia_estrang'),'---').'</td>';
        $w_html .= chr(13).'          <td>N�mero da conta:<b><br>'.Nvl(f($RS,'numero_conta'),'---').'</td>';
        $w_html .= chr(13).'          <tr valign="top">';
        $w_html .= chr(13).'          <td colspan=2>Cidade:<b><br>'.f($RS,'nm_cidade').'</td>';
        $w_html .= chr(13).'          <td>Pa�s:<b><br>'.f($RS,'nm_pais').'</td>';
      } 
      $w_html .= chr(13).'        </table>';
    } 
  } 
  // Se for listagem, exibe os outros dados dependendo do tipo de vis�o  do usu�rio
  if ($w_tipo_visao!=2 && ($l_O=='L' || $l_O=='T')) {
    if (f($RS,'aviso_prox_conc')=='S') {
      // Configura��o dos alertas de proximidade da data limite para conclus�o do acordo
      $w_html .= chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Alertas</td>';
      $w_html .= chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
      $w_html .= chr(13).'          <td valign="top">Emite aviso:<br><b>'.str_replace('N','N�o',str_replace('S','Sim',f($RS,'aviso_prox_conc'))).' </b></td>';
      $w_html .= chr(13).'          <td valign="top">Dias:<br><b>'.f($RS,'dias_aviso').' </b></td>';
      $w_html .= chr(13).'          </table>';
    } 
  } 

  // Deslocamentos
  $RS = db_getPD_Deslocamento::getInstanceOf($dbms,$l_chave,null,'PDGERAL');
  $RS = SortArray($RS,'phpdt_saida','asc', 'phpdt_chegada', 'asc');
  if (count($RS)>0) {
    $w_html .= chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Deslocamentos</td>';
    $w_html .= chr(13).'      <tr><td align="center" colspan="2">';
    $w_html .= chr(13).'        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
    $w_html .= chr(13).'          <tr bgcolor="'.$w_TrBgColor.'" align="center">';
    $w_html .= chr(13).'          <td><b>Origem</td>';
    $w_html .= chr(13).'          <td><b>Destino</td>';
    $w_html .= chr(13).'          <td><b>Saida</td>';
    $w_html .= chr(13).'          <td><b>Chegada</td>';
    $w_html .= chr(13).'          </tr>';
    $w_cor=$w_TrBgColor;
    $w_total=0;
    foreach($RS as $row) {
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
      $w_html .= chr(13).'      <tr valign="top" bgcolor="'.$w_cor.'">';
      $w_html .= chr(13).'        <td>'.f($row,'nm_origem').'</td>';
      $w_html .= chr(13).'        <td>'.f($row,'nm_destino').'</td>';
      $w_html .= chr(13).'        <td align="center">'.substr(FormataDataEdicao(f($row,'phpdt_saida'),3),0,-3).'</td>';
      $w_html .= chr(13).'        <td align="center">'.substr(FormataDataEdicao(f($row,'phpdt_chegada'),3),0,-3).'</td>';
      $w_html .= chr(13).'      </tr>';
    } 
    $w_html .= chr(13).'         </table></td></tr>';
  } 

  // Benef�cios servidor
  $RS = db_getSolicData::getInstanceOf($dbms,$l_chave,'PDGERAL');
  if (count($RS)>0) {
    $w_html .= chr(13).'        <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Benef�cios recebidos pelo proposto</td>';
    $w_html .= chr(13).'        <tr><td align="center" colspan="2">';
    $w_html .= chr(13).'          <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
    $w_html .= chr(13).'            <tr bgcolor="'.$w_TrBgColor.'">';
    if (Nvl(f($RS,'valor_alimentacao'),0)>0) $w_html .= chr(13).'           <td>Aux�lio-alimenta��o: <b>Sim</b></td>'; else $w_html .= chr(13).'           <td>Aux�lio-alimenta��o: <b>N�o</b></td>';
    $w_html .= chr(13).'              <td>Valor R$: <b>'.formatNumber(Nvl(f($RS,'valor_alimentacao'),0)).'</b></td>';
    $w_html .= chr(13).'            </tr>';
    $w_html .= chr(13).'            <tr bgcolor="'.$w_TrBgColor.'">';
    if (Nvl(f($RS,'valor_transporte'),0)>0) $w_html .= chr(13).'           <td>Aux�lio-transporte: <b>Sim</b></td>'; else $w_html .= chr(13).'           <td>Aux�lio-transporte: <b>N�o</b></td>';
    $w_html .= chr(13).'              <td>Valor R$: <b>'.formatNumber(Nvl(f($RS,'valor_transporte'),0)).'</b></td>';
    $w_html .= chr(13).'            </tr>';
    $w_html .= chr(13).'          </table></td></tr>';
  } 

  //Dados da viagem
  $w_html .= chr(13).'        <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Dados da viagem/c�lculo das di�rias</td>';

  $RSQuery = db_getPD_Deslocamento::getInstanceOf($dbms,$l_chave,null,'DADFIN');
  $RSQuery = SortArray($RSQuery,'phpdt_saida','asc', 'phpdt_chegada', 'asc');
  if (count($RSQuery)>0) {
    $i = 1;
    foreach($RSQuery as $row) {
      $w_vetor_trechos[$i][1] = f($row,'sq_diaria');
      $w_vetor_trechos[$i][2] = f($row,'cidade_dest');
      $w_vetor_trechos[$i][3] = f($row,'nm_destino');
      $w_vetor_trechos[$i][4] = FormataDataEdicao(f($row,'phpdt_chegada'));
      $w_vetor_trechos[$i][5] = FormataDataEdicao(f($row,'phpdt_saida'));
      $w_vetor_trechos[$i][6] = formatNumber(Nvl(f($row,'quantidade'),0),1,',','.');
      $w_vetor_trechos[$i][7] = formatNumber(Nvl(f($row,'valor'),0));
      $w_vetor_trechos[$i][8] = Nvl(f($row,'quantidade'),0);
      $w_vetor_trechos[$i][9] = Nvl(f($row,'valor'),0);
      if ($i>1) {
        $w_vetor_trechos[$i-1][5] = FormataDataEdicao(f($row,'phpdt_saida'));
      }
      $i += 1;
    } 
    $j       = $i;
    $i       = 1;
    $w_total = 0;
    $w_html .= chr(13).'     <tr><td align="center" colspan="2">';
    $w_html .= chr(13).'       <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
    $w_html .= chr(13).'         <tr bgcolor="'.$conTrBgColor.'" align="center">';
    $w_html .= chr(13).'         <td><b>Destino</td>';
    $w_html .= chr(13).'         <td><b>Saida</td>';
    $w_html .= chr(13).'         <td><b>Chegada</td>';
    $w_html .= chr(13).'         <td><b>Quantidade de di�rias</td>';
    $w_html .= chr(13).'         <td><b>Valor unit�rio R$</td>';
    $w_html .= chr(13).'         <td><b>Total por localidade - R$</td>';
    $w_html .= chr(13).'         </tr>';
    $w_cor=$conTrBgColor;
    while($i!=($j-1)) {
      $w_html .= chr(13).'     <tr valign="top" bgcolor="'.$conTrBgColor.'">';
      $w_html .= chr(13).'       <td>'.$w_vetor_trechos[$i][3].'</td>';
      $w_html .= chr(13).'       <td align="center">'.$w_vetor_trechos[$i][4].'</td>';
      $w_html .= chr(13).'       <td align="center">'.$w_vetor_trechos[$i][5].'</td>';
      $w_html .= chr(13).'       <td align="right">'.$w_vetor_trechos[$i][6].'</td>';
      $w_html .= chr(13).'       <td align="right">'.$w_vetor_trechos[$i][7].'</td>';
      $w_html .= chr(13).'       <td align="right" bgcolor="'.$conTrAlternateBgColor.'">'.formatNumber(($w_vetor_trechos[$i][8]*$w_vetor_trechos[$i][9])).'</td>';
      $w_html .= chr(13).'     </tr>';
      $w_total += ($w_vetor_trechos[$i][8]*$w_vetor_trechos[$i][9]);
      $i += 1;
    }

    $w_html .= chr(13).'        <tr bgcolor="'.$conTrBgColor.'">';
    $w_html .= chr(13).'          <td rowspan="5" align="right" colspan="3">&nbsp;</td>';
    $w_html .= chr(13).'          <td colspan="2"><b>(a) subtotal:</b></td>';
    $w_html .= chr(13).'          <td align="right" bgcolor="'.$conTrAlternateBgColor.'">'.formatNumber(Nvl($w_total,0)).'</td>';
    $w_html .= chr(13).'        </tr>';
    $w_html .= chr(13).'        <tr bgcolor="'.$conTrBgColor.'">';
    $w_html .= chr(13).'          <td colspan="2"><b>(b) adicional:</b></td>';
    $w_html .= chr(13).'          <td align="right" bgcolor="'.$conTrBgColor.'">'.formatNumber(Nvl(f($RS,'valor_adicional'),0)).'</td>';
    $w_html .= chr(13).'        </tr>';
    $w_html .= chr(13).'        <tr bgcolor="'.$conTrBgColor.'">';
    $w_html .= chr(13).'          <td colspan="2"><b>(c) desconto aux�lio-alimenta��o:</b></td>';
    $w_html .= chr(13).'          <td align="right" bgcolor="'.$conTrBgColor.'">'.formatNumber(Nvl(f($RS,'desconto_alimentacao'),0)).'</td>';
    $w_html .= chr(13).'        </tr>';
    $w_html .= chr(13).'        <tr bgcolor="'.$conTrBgColor.'">';
    $w_html .= chr(13).'          <td colspan="2"><b>(d) desconto aux�lio-transporte:</b></td>';
    $w_html .= chr(13).'          <td align="right" bgcolor="'.$conTrBgColor.'">'.formatNumber(Nvl(f($RS,'desconto_transporte'),0)).'</td>';
    $w_html .= chr(13).'        </tr>';
    $w_html .= chr(13).'        <tr bgcolor="'.$conTrBgColor.'">';
    $w_html .= chr(13).'          <td colspan="2"><b>Total(a + b - c - d):</b></td>';
    $w_html .= chr(13).'          <td align="right" bgcolor="'.$conTrAlternateBgColor.'">'.formatNumber(Nvl($w_total,0)+Nvl(f($RS,'valor_adicional'),0)-Nvl(f($RS,'desconto_alimentacao'),0)-Nvl(f($RS,'desconto_transporte'),0)).'</td>';
    $w_html .= chr(13).'        </tr>';
    $w_html .= chr(13).'        </table></td></tr>';
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
          $w_html .= chr(13).'        <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Bilhete de passagem</td>';
          $w_html .= chr(13).'        <tr><td align="center" colspan="2"><TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
          $w_html .= chr(13).'         <tr bgcolor="'.$conTrBgColor.'" align="center">';
          $w_html .= chr(13).'         <td><b>Origem</td>';
          $w_html .= chr(13).'         <td><b>Destino</td>';
          $w_html .= chr(13).'         <td><b>Saida</td>';
          $w_html .= chr(13).'         <td><b>Chegada</td>';
          $w_html .= chr(13).'         <td><b>Cia. transporte</td>';
          $w_html .= chr(13).'         <td><b>C�digo v�o</td>';
          $w_html .= chr(13).'         </tr>';
          $w_cor=$conTrBgColor;
          $i=1;
        }
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        $w_html .= chr(13).'     <tr valign="middle" bgcolor="'.$w_cor.'">';
        $w_html .= chr(13).'       <td>'.Nvl(f($row,'nm_origem'),'---').'</td>';
        $w_html .= chr(13).'       <td>'.Nvl(f($row,'nm_destino'),'---').'</td>';
        $w_html .= chr(13).'       <td align="center">'.substr(FormataDataEdicao(f($row,'phpdt_saida'),3),0,-3).'</td>';
        $w_html .= chr(13).'       <td align="center">'.substr(FormataDataEdicao(f($row,'phpdt_chegada'),3),0,-3).'</td>';
        $w_html .= chr(13).'       <td>'.Nvl(f($row,'nm_cia_transporte'),'---').'</td>';
        $w_html .= chr(13).'       <td>'.Nvl(f($row,'codigo_voo'),'---').'</td>';
        $w_html .= chr(13).'     </tr>';
        $j=1;
      }
    } 
    if ($j==1) {
      $w_html .= chr(13).'        </tr>';
      $w_html .= chr(13).'        </table></td></tr>';
      $RS = db_getSolicData::getInstanceOf($dbms,$l_chave,'PDGERAL');
      $w_html .= chr(13).'        <tr><td align="center" colspan="2"><TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
      $w_html .= chr(13).'        <tr><td colspan="2"><b>N� do PTA/Ticket: </b>'.f($RS,'PTA').'</td>';
      $w_html .= chr(13).'        <tr><td><b>Data da emiss�o: </b>'.FormataDataEdicao(f($RS,'emissao_bilhete')).'</td>';
      $w_html .= chr(13).'            <td><b>Valor das passagens R$: </b>'.formatNumber(Nvl(f($RS,'valor_passagem'),0)).'</td>';
      $w_html .= chr(13).'      </table>';
      $w_html .= chr(13).'    </td>';
    }
  } 

  // Arquivos gerados para a PCD
  if ($w_or_tramite > 4) {
    $w_html .= chr(13).'        <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Arquivos</td>';
    $w_html .= chr(13).'        <tr><td align="center" colspan="2">';
    $w_html .= chr(13).'          <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
    $w_html .= chr(13).'            <tr bgcolor="'.$w_TrBgColor.'"><td><a target="Emissao" class="hl" title="Emitir autoriza��o e proposta de concess�o." href="'.$w_dir.$w_pagina.'Emissao&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.$l_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&SG='.$SG.'">Autoriza��o para emiss�o de bilhetes</A>';
    $w_html .= chr(13).'            <tr bgcolor="'.$w_TrBgColor.'"><td><a target="Relatorio" class="hl" title="Emitir relat�rio para prestacao de contas." href="'.$w_dir.$w_pagina.'Prestacaocontas&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.$l_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Relat�rio de viagem</A>';
    $w_html .= chr(13).'            </tr>';
    $w_html .= chr(13).'          </table></td></tr>';
  }

  // Se for envio, executa verifica��es nos dados da solicita��o
  $w_erro = ValidaViagem($w_cliente,$l_chave,'PDGERAL',null,null,null,Nvl($w_tramite,0));
  if ($w_erro>'') {
    $w_html .= chr(13).'<tr bgcolor="'.$w_TrBgColor.'"><td colspan=2>';
    $w_html .= chr(13).'<HR>';
    if (substr($w_erro,0,1)=='0') {
      $w_html .= chr(13).'  <font color="#BC3131"><b>ATEN��O:</b> Foram identificados os erros listados abaixo, n�o sendo poss�vel seu encaminhamento para fases posteriores � atual.</font>';
    } elseif (substr($w_erro,0,1)=='1') {
      $w_html .= chr(13).'  <font color="#BC3131"><b>ATEN��O:</b> Foram identificados os erros listados abaixo. Seu encaminhamento para fases posteriores � atual s� pode ser feito por um gestor do sistema ou do m�dulo de projetos.</font>';
    } else {
      $w_html .= chr(13).'  <font color="#BC3131"><b>ATEN��O:</b> Foram identificados os alertas listados abaixo. Eles n�o impedem o encaminhamento para fases posteriores � atual, mas conv�m sua verifica��o.</font>';
    } 
    $w_html .= chr(13).'  <ul>'.substr($w_erro,1,1000).'</ul>';
    $w_html .= chr(13).'  </td></tr>';
  } 

  if ($l_O=='L' || $l_O=='V' || $l_O=='T') {
    // Se for listagem dos dados
    // Encaminhamentos
    $RS = db_getSolicLog::getInstanceOf($dbms,$l_chave,null,'LISTA');
    $RS = SortArray($RS,'phpdt_data','desc', 'sq_siw_solic_log', 'desc');

    $w_html .= chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><b>Ocorr�ncias e Anota��es</td>';
    $w_html .= chr(13).'      <tr><td align="center" colspan="2">';
    $w_html .= chr(13).'        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
    $w_html .= chr(13).'          <tr bgcolor="'.$w_TrBgColor.'" align="center">';
    $w_html .= chr(13).'            <td><b>Data</td>';
    $w_html .= chr(13).'            <td><b>Despacho/Observa��o</td>';
    $w_html .= chr(13).'            <td><b>Respons�vel</td>';
    $w_html .= chr(13).'            <td><b>Fase</td>';
    $w_html .= chr(13).'          </tr>';
    if (count($RS)<=0) {
      $w_html .= chr(13).'      <tr bgcolor="'.$w_TrBgColor.'"><td colspan=6 align="center"><b>N�o foram encontrados encaminhamentos.</b></td></tr>';
    } else {
      $w_html .= chr(13).'      <tr bgcolor="'.$w_TrBgColor.'" valign="top">';
      $i = 0;
      foreach($RS as $row) {
        if ($i==0) {
          $w_html .= chr(13).'        <td colspan=6>Fase atual: <b>'.f($row,'fase').'</b></td>';
          $w_cor = $w_TrBgColor;
          if ($w_tramite_ativo=='S') {
            // Recupera os respons�veis pelo tramite
            $RS1 = db_getTramiteResp::getInstanceOf($dbms,$l_chave,null,null);
            $w_html .= chr(13).'      <tr bgcolor="'.$w_TrBgColor.'" valign="top">';
            $w_html .= chr(13).'        <td colspan=6>Respons�veis pelo tr�mite: <b>';
            if (count($RS1)>0) {
              $j = 0;
              foreach($RS1 as $row1) {
                if ($j==0) {
                  $w_tramite_resp = f($row1,'nome_resumido');
                  if($l_tipo!='WORD') $w_html .= chr(13).ExibePessoa($w_dir_volta,$w_cliente,f($row1,'sq_pessoa'),$TP,f($row1,'nome_resumido'));
                  else                $w_html .= chr(13).f($row1,'nome_resumido');
                  $j = 1;
                } else {
                  if (strpos($w_tramite_resp,f($row,'nome_resumido'))===false) {
                    if($l_tipo!='WORD') $w_html .= chr(13).', '.ExibePessoa($w_dir_volta,$w_cliente,f($row1,'sq_pessoa'),$TP,f($row1,'nome_resumido'));
                    else                $w_html .= chr(13).', '.f($row1,'nome_resumido');
                  }
                  $w_tramite_resp .= f($row1,'nome_resumido');
                }
              } 
            } 
            $w_html .= chr(13).'</b></td>';
          } 
          $i = 1;
        }
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        $w_html .= chr(13).'      <tr valign="top" bgcolor="'.$w_cor.'">';
        $w_html .= chr(13).'        <td nowrap>'.FormataDataEdicao(f($row,'phpdt_data'),3).'</td>';
        if (Nvl(f($row,'caminho'),'')>'' && $l_tipo!='WORD') {
          $w_html .= chr(13).'        <td>'.CRLF2BR(Nvl(f($row,'despacho'),'---').'<br>'.LinkArquivo('HL',$w_cliente,f($row,'sq_siw_arquivo'),'_blank','Clique para exibir o anexo em outra janela.','Anexo - '.f($row,'tipo').' - '.round(f($row,'tamanho')/1024,1).' KB',null)).'</td>';
        } else {
          $w_html .= chr(13).'        <td>'.CRLF2BR(Nvl(f($row,'despacho'),'---')).'</td>';
        } 
        if ($l_tipo!='WORD') {
          $w_html .= chr(13).'        <td nowrap>'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'responsavel')).'</td>';
        } else {
          $w_html .= chr(13).'        <td nowrap>'.f($row,'responsavel').'</td>';
        } 
        if (nvl(f($row,'chave_log'),'')>'' && nvl(f($row,'destinatario'),'')>'') {
          if ($l_tipo!='WORD') {
            $w_html .= chr(13).'        <td nowrap>'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'sq_pessoa_destinatario'),$TP,f($row,'destinatario')).'</td>';
          } else {
            $w_html .= chr(13).'        <td nowrap>'.f($row,'destinatario').'</td>';
          } 
        } elseif (f($row,'origem')=='ANOTACAO') {
          $w_html .= chr(13).'        <td nowrap>Anota��o</td>';
        } else {
          if(strpos(f($row,'despacho'),'***')!==false) {
            $w_html.=chr(13).'        <td nowrap>---</td>';
          } else {
            $w_html.=chr(13).'        <td nowrap>'.Nvl(f($row,'tramite'),'---').'</td>';
          }
        } 
        $w_html .= chr(13).'      </tr>';
      } 
    } 
    $w_html .= chr(13).'         </table></td></tr>';
  } 
  $w_html .= chr(13).'    </table>';
  $w_html .= chr(13).'</table>';

  return $w_html;
} 
?>
