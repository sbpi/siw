<?
// =========================================================================
// Rotina de visualização dos dados do convenio
// -------------------------------------------------------------------------
function VisualConvenio($l_chave,$l_O,$l_usuario,$l_P1,$l_P4) {
  extract($GLOBALS);
  if ($l_P4==1) $w_TrBgColor=''; else $w_TrBgColor=$conTrBgColor;
  $w_html='';
  // Carrega o segmento do cliente
  $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente); 
  $w_segmento     = f($RS,'segmento');
  $w_nome_cliente = f($RS,'nome_resumido');
  // Recupera os dados do acordo
  $RS = db_getSolicData::getInstanceOf($dbms,$l_chave,substr($SG,0,3).'GERAL');
  $w_tramite        = f($RS,'sq_siw_tramite');
  $w_valor_inicial  = f($RS,'valor_inicial');
  $w_fim            = f($RS,'fim_real');
  $w_sg_tramite     = f($RS,'sg_tramite');
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
  // Se for listagem dos dados
  if ($l_O=='L' || $l_O=='V') {
    $w_html.=chr(13).'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
    $w_html.=chr(13).'<tr bgcolor="'.$w_TrBgColor.'"><td>';
    $w_html.=chr(13).'    <table width="99%" border="0">';
    // Se a classificação foi informada, exibe.
    if (nvl(f($RS,'sq_cc'),'')>'') {
      $w_html.=chr(13).'      <tr valign="top"><td><font size="1">Classificação:<br><b>'.f($RS,'nm_cc').' </b>';
    } 

    if (!($l_P1==4 || $l_P4==1)) {
      $w_html.=chr(13).'       <td align="right"><font size="1"><b><A class="hl" HREF="'.$w_dir.$w_pagina.'visual&O=T&w_chave='.f($RS,'sq_siw_solicitacao').'&w_tipo=volta&P1=4&P2='.$P2.'&P3='.$P3.'&P4='.$l_P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe todas as informações.">Exibir todas as informações</a></td>';
    } 
    $w_html.=chr(13).'      <tr><td colspan=2><font size=1>Objeto: <b>'.f($RS,'codigo_interno').' ('.$l_chave.')<br>'.CRLF2BR(f($RS,'objeto')).'</b></font></td></tr>';
    // Identificação do convenio
    $w_html.=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Identificação</td>';
    $w_html.=chr(13).'      <tr><td valign="top"><font size="1">Tipo:<br><b>'.f($RS,'nm_tipo_acordo').' </b></td>';
    $w_html.=chr(13).'          <td valign="top"><font size="1">Executor:<br><b>'.$w_nome_cliente.' </b></td>';
    $w_html.=chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
    $w_html.=chr(13).'          <tr valign="top">';
    $w_html.=chr(13).'          <td><font size="1">Cidade de origem:<br><b>'.f($RS,'nm_cidade').' ('.f($RS,'co_uf').')</b></td>';
    $w_html.=chr(13).'          <tr valign="top">';
    if (!$l_P4==1) {
      $w_html.=chr(13).'          <td><font size="1">Responsável monitoramento:<br><b>'.ExibePessoa($w_dir_volta,$w_cliente,f($RS,'solicitante'),$TP,f($RS,'nm_solic')).'</b></td>';
      $w_html.=chr(13).'          <td><font size="1">Unidade responsável monitoramento:<br><b>'.ExibeUnidade($w_dir_volta,$w_cliente,f($RS,'nm_unidade_resp'),f($RS,'sq_unidade'),$TP).'</b></td>';
    } else {
      $w_html.=chr(13).'          <td><font size="1">Responsável monitoramento:<br><b>'.f($RS,'nm_solic').'</b></td>';
      $w_html.=chr(13).'          <td><font size="1">Unidade responsável monitoramento:<br><b>'.f($RS,'nm_unidade_resp').'</b></td>';
    } 
    // Se for visão completa
    if ($w_tipo_visao==0) {
      $w_html.=chr(13).'          <td valign="top"><font size="1">Valor:<br><b>'.number_format(f($RS,'valor'),2,',','.').' </b></td>';
    } 
    if($w_segmento=='Público') {
      $w_html.=chr(13).'          <tr valign="top">';
      $w_html.=chr(13).'          <td><font size="1">Número do empenho:<br><b>'.Nvl(f($RS,'empenho'),'---').' </b></td>';
      $w_html.=chr(13).'          <td><font size="1">Número do processo:<br><b>'.Nvl(f($RS,'processo'),'---').' </b></td>';
      $w_html.=chr(13).'          <tr valign="top">';
      $w_html.=chr(13).'          <td><font size="1">Assinatura:<br><b>'.FormataDataEdicao(f($RS,'assinatura')).' </b></td>';
      $w_html.=chr(13).'          <td><font size="1">Publicação D.O.:<br><b>'.FormataDataEdicao(f($RS,'publicacao')).' </b></td>';    
    }
    $w_html.=chr(13).'          <tr valign="top">';
    $w_html.=chr(13).'          <td><font size="1">Início vigência:<br><b>'.FormataDataEdicao(f($RS,'inicio')).' </b></td>';
    $w_html.=chr(13).'          <td><font size="1">Término vigência:<br><b>'.FormataDataEdicao(f($RS,'fim')).' </b></td>';
    $w_html.=chr(13).'          </table>';
    if ($w_tipo_visao==0 || $w_tipo_visao==1) {
      // Informações adicionais
      if (Nvl(f($RS,'descricao'),'')>'' || Nvl(f($RS,'justificativa'),'')>'') {
        $w_html.=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Informações adicionais</td>';
        if (Nvl(f($RS,'descricao'),'')>'') $w_html.=chr(13).'      <tr><td valign="top" colspan="2"><font size="1">Resultados esperados:<br><b>'.CRLF2BR(f($RS,'descricao')).' </b></td>';
        if ($w_tipo_visao==0 && Nvl(f($RS,'justificativa'),'')>'') {
          $w_html.=chr(13).'      <tr><td valign="top" colspan="2"><font size="1">Observações:<br><b>'.CRLF2BR(f($RS,'justificativa')).' </b></td>';
        } 
      } 
    } 
    // Dados da conclusão da demanda, se ela estiver nessa situação
    if (Nvl(f($RS,'conclusao'),'')>'') {
      $w_html.=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Dados do encerramento</td>';
      $w_html.=chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
      $w_html.=chr(13).'          <tr valign="top">';
      $w_html.=chr(13).'          <td><font size="1">Início da vigência:<br><b>'.FormataDataEdicao(f($RS,'inicio_real')).' </b></td>';
      $w_html.=chr(13).'          <td><font size="1">Término da vigência:<br><b>'.FormataDataEdicao(f($RS,'fim_real')).' </b></td>';
      if ($w_tipo_visao==0) {
        $w_html.=chr(13).'          <td><font size="1">Valor realizado:<br><b>'.number_format(f($RS,'valor_atual'),2,',','.').' </b></td>';
      } 
      $w_html.=chr(13).'          </table>';
      if ($w_tipo_visao==0) {
        $w_html.=chr(13).'      <tr><td valign="top"><font size="1">Nota de conclusão:<br><b>'.CRLF2BR(f($RS,'observacao')).' </b></td>';
      } 
    } 
    // Exibe ficha completa
    if ($l_P1==4) {
      // Termo de referência
      $w_html.=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Termo de referência</b></td>';
      $w_html.=chr(13).'      <tr><td colspan=2><font size="1">Atividades a serem desenvolvidas:<b><br>'.nvl(CRLF2BR(f($RS,'atividades')),'---').'</td>';
      $w_html.=chr(13).'      <tr><td colspan=2><font size="1">Produtos a serem entregues:<b><br>'.nvl(CRLF2BR(f($RS,'produtos')),'---').'</td>';
      $w_html.=chr(13).'      <tr><td colspan=2><font size="1">Qualificação exigida:<b><br>'.nvl(CRLF2BR(f($RS,'requisitos')),'---').'</td>';
      $w_html.=chr(13).'      <tr><td><font size="1">Código para a outra parte:<b><br>'.Nvl(f($RS,'codigo_externo'),'---').'</td>';
      if (Nvl(f($RS,'cd_modalidade'),'')=='F' || Nvl(f($RS,'cd_modalidade'),'')=='I') {
        $w_html.=chr(13).'          <tr><td colspan=2><table border=0 width="100%" cellspacing=0 cellpadding=0><tr valign="top">';
        $w_html.=chr(13).'          <td><font size="1">Pemite vinculação de projetos?<b><br>'.f($RS,'nm_vincula_projeto').'</td>';
        if (Nvl(f($RS,'cd_modalidade'),'')=='F') {
          $w_html.=chr(13).'          <td><font size="1">Pemite vinculação de demandas?<b><br>'.f($RS,'nm_vincula_demanda').'</td>';
          $w_html.=chr(13).'          <td><font size="1">Pemite vinculação de viagens?<b><br>'.f($RS,'nm_vincula_viagem').'</td>';
        }
        $w_html.=chr(13).'          </table>';
      } 
    } 
    // Outra parte
    $RSQuery = db_getBenef::getInstanceOf($dbms,$w_cliente,Nvl(f($RS,'outra_parte'),0),null,null,null,Nvl(f($RS,'sq_tipo_pessoa'),0),null,null);
    $w_html.=chr(13).'      <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Outra parte</td>';
    if (count($RSQuery)==0) {
      $w_html.=chr(13).'      <tr><td colspan=2><font size=2><b>Outra parte não informada';
    } else {
      foreach($RSQuery as $row) {
        $w_html.=chr(13).'      <tr><td colspan=2><font size=2><b>';
        $w_html.=chr(13).'          '.f($row,'nm_pessoa').' ('.f($row,'nome_resumido').')';
        if (Nvl(f($RS,'sq_tipo_pessoa'),0)==1) {
          $w_html.=chr(13).'          - '.f($row,'cpf');
        } else {
          $w_html.=chr(13).'          - '.f($row,'cnpj');
        } 
        // Exibe ficha completa
        if ($l_P1==4) {
          if (f($RS,'sq_tipo_pessoa')==1) {
            $w_html.=chr(13).'      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>';
            $w_html.=chr(13).'          <tr valign="top">';
            $w_html.=chr(13).'          <td><font size="1">Sexo:<b><br>'.f($row,'nm_sexo').'</td>';
            $w_html.=chr(13).'          <td><font size="1">Data de nascimento:<b><br>'.FormataDataEdicao(f($row,'nascimento')).'</td>';
            $w_html.=chr(13).'          <tr valign="top">';
            $w_html.=chr(13).'          <td><font size="1">Identidade:<b><br>'.f($row,'rg_numero').'</td>';
            $w_html.=chr(13).'          <td><font size="1">Data de emissão:<b><br>'.Nvl(f($row,'rg_emissao'),'---').'</td>';
            $w_html.=chr(13).'          <td><font size="1">Órgão emissor:<b><br>'.f($row,'rg_emissor').'</td>';
            $w_html.=chr(13).'          <tr valign="top">';
            $w_html.=chr(13).'          <td><font size="1">Passaporte:<b><br>'.Nvl(f($row,'passaporte_numero'),'---').'</td>';
            $w_html.=chr(13).'          <td><font size="1">País emissor:<b><br>'.Nvl(f($row,'nm_pais_passaporte'),'---').'</td>';
            $w_html.=chr(13).'          </table>';
          } else {
            $w_html.=chr(13).'      <tr><td colspan=2><font size="1">Inscrição estadual:<b><br>'.Nvl(f($row,'inscricao_estadual'),'---').'</td>';
          } 
          if (f($RS,'sq_tipo_pessoa')==1) {
            $w_html.=chr(13).'      <tr><td colspan="2" align="center" style="border: 1px solid rgb(0,0,0);"><font size="1"><b>Endereço comercial, Telefones e e-Mail</td>';
          } else {
            $w_html.=chr(13).'      <tr><td colspan="2" align="center" style="border: 1px solid rgb(0,0,0);"><font size="1"><b>Endereço principal, Telefones e e-Mail</td>';
          } 
          $w_html.=chr(13).'      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>';
          $w_html.=chr(13).'          <tr valign="top">';
          $w_html.=chr(13).'          <td><font size="1">Telefone:<b><br>('.f($row,'ddd').') '.f($row,'nr_telefone').'</td>';
          $w_html.=chr(13).'          <td><font size="1">Fax:<b><br>'.Nvl(f($row,'nr_fax'),'---').'</td>';
          $w_html.=chr(13).'          <td><font size="1">Celular:<b><br>'.Nvl(f($row,'nr_celular'),'---').'</td>';
          $w_html.=chr(13).'          <tr valign="top">';
          $w_html.=chr(13).'          <td><font size="1">Endereço:<b><br>'.f($row,'logradouro').'</td>';
          $w_html.=chr(13).'          <td><font size="1">Complemento:<b><br>'.Nvl(f($row,'complemento'),'---').'</td>';
          $w_html.=chr(13).'          <td><font size="1">Bairro:<b><br>'.Nvl(f($row,'bairro'),'---').'</td>';
          $w_html.=chr(13).'          <tr valign="top">';
          if (f($row,'pd_pais')=='S') {
            $w_html.=chr(13).'          <td><font size="1">Cidade:<b><br>'.f($row,'nm_cidade').'-'.f($row,'co_uf').'</td>';
          } else {
            $w_html.=chr(13).'          <td><font size="1">Cidade:<b><br>'.f($row,'nm_cidade').'-'.f($row,'nm_pais').'</td>';
          } 
          $w_html.=chr(13).'          <td><font size="1">CEP:<b><br>'.f($row,'cep').'</td>';
          if (Nvl(f($row,'email'),'nulo')!='nulo') {
            if (!$l_P4==1) {
              $w_html.=chr(13).'              <td><font size="1">e-Mail:<b><br><a class="hl" href="mailto:'.f($row,'email').'">'.f($row,'email').'</a></td>';
            } else {
              $w_html.=chr(13).'              <td><font size="1">e-Mail:<b><br>'.f($row,'email').'</td>';
            } 
          } else {
            $w_html.=chr(13).'              <td><font size="1">e-Mail:<b><br>---</td>';
          }  
          $w_html.=chr(13).'          </table>';
          $w_html.=chr(13).'      <tr><td colspan="2" align="center" style="border: 1px solid rgb(0,0,0);"><font size="1"><b>Dados para recebimento</td>';
          $w_html.=chr(13).'      <tr><td colspan="2"><font size="1">Forma de recebimento:<b><br>'.f($RS,'nm_forma_pagamento').'</td>';
          $w_html.=chr(13).'      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>';
          if (!(strpos('CREDITO,DEPOSITO',f($RS,'sg_forma_pagamento'))===false)) {
            $w_html.=chr(13).'          <tr valign="top">';
            if (Nvl(f($RS,'cd_banco'),'')>'') {
              $w_html.=chr(13).'          <td><font size="1">Banco:<b><br>'.f($RS,'cd_banco').' - '.f($RS,'nm_banco').'</td>';
              $w_html.=chr(13).'          <td><font size="1">Agência:<b><br>'.f($RS,'cd_agencia').' - '.f($RS,'nm_agencia').'</td>';
              $w_html.=chr(13).'          <td><font size="1">Operação:<b><br>'.Nvl(f($RS,'operacao_conta'),'---').'</td>';
              $w_html.=chr(13).'          <td><font size="1">Número da conta:<b><br>'.Nvl(f($RS,'numero_conta'),'---').'</td>';
            } else {
              $w_html.=chr(13).'          <td><font size="1">Banco:<b><br>---</td>';
              $w_html.=chr(13).'          <td><font size="1">Agência:<b><br>---</td>';
              $w_html.=chr(13).'          <td><font size="1">Operação:<b><br>---</td>';
              $w_html.=chr(13).'          <td><font size="1">Número da conta:<b><br>---</td>';
            } 
          } elseif (f($RS,'sg_forma_pagamento')=='ORDEM') {
            $w_html.=chr(13).'          <tr valign="top">';
            if (Nvl(f($RS,'cd_banco'),'')>'') {
              $w_html.=chr(13).'          <td><font size="1">Banco:<b><br>'.f($RS,'cd_banco').' - '.f($RS,'nm_banco').'</td>';
              $w_html.=chr(13).'          <td><font size="1">Agência:<b><br>'.f($RS,'cd_agencia').' - '.f($RS,'nm_agencia').'</td>';
            } else {
              $w_html.=chr(13).'          <td><font size="1">Banco:<b><br>---</td>';
              $w_html.=chr(13).'          <td><font size="1">Agência:<b><br>---</td>';
            } 
          } elseif (f($RS,'sg_forma_pagamento')=='EXTERIOR') {
            $w_html.=chr(13).'          <tr valign="top">';
            $w_html.=chr(13).'          <td><font size="1">Banco:<b><br>'.f($RS,'banco_estrang').'</td>';
            $w_html.=chr(13).'          <td><font size="1">ABA Code:<b><br>'.Nvl(f($RS,'aba_code'),'---').'</td>';
            $w_html.=chr(13).'          <td><font size="1">SWIFT Code:<b><br>'.Nvl(f($RS,'swift_code'),'---').'</td>';
            $w_html.=chr(13).'          <tr><td colspan=3><font size="1">Endereço da agência:<b><br>'.Nvl(f($RS,'endereco_estrang'),'---').'</td>';
            $w_html.=chr(13).'          <tr valign="top">';
            $w_html.=chr(13).'          <td colspan=2><font size="1">Agência:<b><br>'.Nvl(f($RS,'agencia_estrang'),'---').'</td>';
            $w_html.=chr(13).'          <td><font size="1">Número da conta:<b><br>'.Nvl(f($RS,'numero_conta'),'---').'</td>';
            $w_html.=chr(13).'          <tr valign="top">';
            $w_html.=chr(13).'          <td colspan=2><font size="1">Cidade:<b><br>'.f($RS,'nm_cidade').'</td>';
            $w_html.=chr(13).'          <td><font size="1">País:<b><br>'.f($RS,'nm_pais').'</td>';
          } 
          $w_html.=chr(13).'          </table>';
        } 
        break;
      } 
    }
    // Se outra parte for pessoa jurídica
    if (Nvl(f($RS,'sq_tipo_pessoa'),0)==2 && $l_P1==4) {
      // Preposto
      $RSQuery = db_getBenef::getInstanceOf($dbms,$w_cliente,Nvl(f($RS,'preposto'),0),null,null,null,null,null,null);
      $w_html.=chr(13).'      <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Preposto</td>';
      if (count($RSQuery)==0) {
        $w_html.=chr(13).'      <tr><td colspan=2><font size=2><b>Preposto não informado';
      } else {
        foreach($RSQuery as $row) {
          $w_html.=chr(13).'      <tr><td colspan=2><font size=2><b>';
          $w_html.=chr(13).'          '.f($row,'nm_pessoa').' ('.f($row,'nome_resumido').')';
          $w_html.=chr(13).'          - '.f($row,'cpf');
          // Exibe ficha completa
          if ($l_P1==4) {
            $w_html.=chr(13).'      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0><tr valign="top">';
            $w_html.=chr(13).'          <td><font size="1">Sexo:<b><br>'.f($row,'nm_sexo').'</td>';
            $w_html.=chr(13).'          <td><font size="1">Identidade:<b><br>'.f($row,'rg_numero').'</td>';
            $w_html.=chr(13).'          <td><font size="1">Data de emissão:<b><br>'.Nvl(FormataDataEdicao(f($row,'rg_emissao')),'---').'</td>';
            $w_html.=chr(13).'          <td><font size="1">Órgão emissor:<b><br>'.f($row,'rg_emissor').'</td>';
            $w_html.=chr(13).'          </table>';
          }
          break;
        }
      } 
      // Representantes
      $RSQuery = db_getAcordoRep::getInstanceOf($dbms,f($RS,'sq_siw_solicitacao'),$w_cliente,null,null);
      $RSQuery = SortArray($RSQuery,'nm_pessoa','asc');
      $w_html.=chr(13).'      <tr><td colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Representantes</td>';
      if (count($RSQuery)==0) {
        $w_html.=chr(13).'      <tr><td colspan=2><font size=2><b>Representantes não informados';
      } else {
        $w_html.=chr(13).'      <tr><td align="center" colspan="2">';
        $w_html.=chr(13).'        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
        $w_html.=chr(13).'          <tr bgcolor="'.$w_TrBgColor.'" align="center">';
        $w_html.=chr(13).'            <td><font size="1"><b>CPF</font></td>';
        $w_html.=chr(13).'            <td><font size="1"><b>Nome</font></td>';
        $w_html.=chr(13).'            <td><font size="1"><b>DDD</font></td>';
        $w_html.=chr(13).'            <td><font size="1"><b>Telefone</font></td>';
        $w_html.=chr(13).'            <td><font size="1"><b>Fax</font></td>';
        $w_html.=chr(13).'            <td><font size="1"><b>Celular</font></td>';
        $w_html.=chr(13).'            <td><font size="1"><b>e-Mail</font></td>';
        $w_html.=chr(13).'          </tr>';
        $w_cor=$w_TrBgColor;
        foreach($RSQuery as $row) {
          $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
          $w_html.=chr(13).'      <tr valign="top" bgcolor="'.$w_cor.'">';
          $w_html.=chr(13).'        <td align="center"><font size="1">'.f($row,'cpf').'</td>';
          $w_html.=chr(13).'        <td><font size="1">'.f($row,'nome_resumido').'</td>';
          $w_html.=chr(13).'        <td align="center"><font size="1">'.Nvl(f($row,'ddd'),'---').'</td>';
          $w_html.=chr(13).'        <td><font size="1">'.Nvl(f($row,'nr_telefone'),'---').'</td>';
          $w_html.=chr(13).'        <td><font size="1">'.Nvl(f($row,'nr_fax'),'---').'</td>';
          $w_html.=chr(13).'        <td><font size="1">'.Nvl(f($row,'nr_celular'),'---').'</td>';
          if (Nvl(f($row,'email'),'nulo')!='nulo') {
            if (!$l_P4==1) {
              $w_html.=chr(13).'        <td><font size="1"><a class="hl" href="mailto:'.f($row,'email').'">'.f($row,'email').'</a></td>';
            } else {
              $w_html.=chr(13).'        <td><font size="1">'.f($row,'email').'</td>';
            } 
          } else {
            $w_html.=chr(13).'        <td><font size="1">---</td>';
          } 
          $w_html.=chr(13).'      </tr>';
        } 
        $w_html.=chr(13).'         </table></td></tr>';
      } 
    } 
  } 
  // Se for listagem, exibe os outros dados dependendo do tipo de visão  do usuário
  if ($w_tipo_visao!=2 && ($l_O=='L' || $l_O=='T') && $l_P1==4) {
    if (f($RS,'aviso_prox_conc')=='S') {
      // Configuração dos alertas de proximidade da data limite para conclusão do acordo
      $w_html.=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Alertas</td>';
      $w_html.=chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
      $w_html.=chr(13).'          <td valign="top"><font size="1">Emite aviso:<br><b>'.str_replace('N','Não',str_replace('S','Sim',f($RS,'aviso_prox_conc'))).' </b></td>';
      $w_html.=chr(13).'          <td valign="top"><font size="1">Dias:<br><b>'.f($RS,'dias_aviso').' </b></td>';
      $w_html.=chr(13).'          </table>';
    } 
  } 
  // Parcelas
  $RS = db_getAcordoParcela::getInstanceOf($dbms,$l_chave,null,null,null,null,null,null,null,null);
  $RS = SortArray($RS,'ordem','asc');
  if (count($RS)>0) {
    $w_html.=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Parcelas</td>';
    $w_html.=chr(13).'      <tr><td align="center" colspan="2">';
    $w_html.=chr(13).'        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
    $w_html.=chr(13).'          <tr bgcolor="'.$w_TrBgColor.'" align="center">';
    $w_html.=chr(13).'          <td rowspan=2><font size="1"><b>Ordem</font></td>';
    $w_html.=chr(13).'          <td rowspan=2><font size="1"><b>Vencimento</font></td>';
    $w_html.=chr(13).'          <td rowspan=2><font size="1"><b>Valor</font></td>';
    $w_html.=chr(13).'          <td rowspan=2><font size="1"><b>Observações</font></td>';
    $w_html.=chr(13).'          <td colspan=4><font size="1"><b>Financeiro</font></td>';
    $w_html.=chr(13).'          </tr>';
    $w_html.=chr(13).'          <tr bgcolor="'.$w_TrBgColor.'" align="center">';
    $w_html.=chr(13).'          <td><font size="1"><b>Lançamento</font></td>';
    $w_html.=chr(13).'          <td><font size="1"><b>Vencimento</font></td>';
    $w_html.=chr(13).'          <td><font size="1"><b>Valor</font></td>';
    $w_html.=chr(13).'          <td><font size="1"><b>Quitação</font></td>';
    $w_html.=chr(13).'          </tr>';
    $w_cor=$w_TrBgColor;
    $w_total=0;
    foreach($RS as $row) {
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
      $w_html.=chr(13).'      <tr valign="top" bgcolor="'.$w_cor.'">';
      $w_html.=chr(13).'        <td align="center"><font size="1">';
      if (Nvl($w_sg_tramite,'-')=='CR' && $w_fim-f($row,'vencimento')<0) {
        $w_html.=chr(13).'           <img src="'.$conImgCancel.'" border=0 width=15 heigth=15 align="center" title="Parcela cancelada!">';
      } elseif (Nvl(f($row,'quitacao'),'nulo')=='nulo') {
        if (f($row,'vencimento')<addDays(time(),-1))  {
          $w_html.=chr(13).'           <img src="'.$conImgAtraso.'" border=0 width=15 heigth=15 align="center">';
        } elseif (f($row,'vencimento')-addDays(time(),-1)<=5) {
          $w_html.=chr(13).'           <img src="'.$conImgAviso.'" border=0 width=15 height=15 align="center">';
        } else {
          $w_html.=chr(13).'           <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">';
        } 
      } else {
        if (f($row,'quitacao')>f($row,'vencimento')) {
          $w_html.=chr(13).'           <img src="'.$conImgOkAtraso.'" border=0 width=15 heigth=15 align="center">';
        } else {
          $w_html.=chr(13).'           <img src="'.$conImgOkNormal.'" border=0 width=15 height=15 align="center">';
        } 
      } 
      $w_html.=chr(13).'        '.f($row,'ordem').'</td>';
      $w_html.=chr(13).'        <td align="center"><font size="1">'.FormataDataEdicao(f($row,'vencimento')).'</td>';
      $w_html.=chr(13).'        <td align="right"><font size="1">'.number_format(f($row,'valor'),2,',','.').'</td>';
      $w_html.=chr(13).'        <td><font size="1">'.Nvl(f($row,'observacao'),'---').'</td>';
      if (Nvl(f($row,'cd_lancamento'),'')>'') {
        $w_html.=chr(13).'        <td align="center" nowrap><font size="1"><A class="hl" HREF="mod_fn/lancamento.php?par=Visual&O=L&w_chave='.f($row,'sq_lancamento').'&w_tipo=&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$l_P4.'&TP='.$TP.'&SG=FN'.substr($SG,2,1).'CONT" title="Exibe as informações do lançamento." target="Lancamento">'.f($row,'cd_lancamento').'</a></td>';
        $w_html.=chr(13).'        <td align="center"><font size="1">'.FormataDataEdicao(f($row,'dt_lancamento')).'</td>';
        $w_html.=chr(13).'        <td align="right"><font size="1">'.number_format(f($row,'vl_lancamento'),2,',','.').'</td>';
        $w_real=$w_real+f($row,'vl_lancamento');
      } else {
        $w_html.=chr(13).'        <td align="center"><font size="1">---</td>';
        $w_html.=chr(13).'        <td align="center"><font size="1">---</td>';
        $w_html.=chr(13).'        <td align="center"><font size="1">---</td>';
      } 
      $w_html.=chr(13).'        <td align="center"><font size="1">'.Nvl(FormataDataEdicao(f($row,'quitacao')),'---').'</td>';
      $w_html.=chr(13).'      </tr>';
      $w_total=$w_total+f($row,'valor');
    } 
    if ($w_total>0 || $w_real>0) {
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
      $w_html.=chr(13).'      <tr bgcolor="'.$w_cor.'" valign="top">';
      $w_html.=chr(13).'        <td align="right" colspan=2><font size="1"><b>Previsto</b></td>';
      $w_html.=chr(13).'        <td align="right"><font size="1"><b>'.number_format($w_total,2,',','.').'</b></td>';
      if (round($w_valor_inicial-$w_total,2)!=0) {
        $w_html.=chr(13).'        <td colspan=2><font size=1><b>O valor das parcelas difere do valor contratado ('.number_format($w_valor_inicial-$w_total,2,',','.').')</b></td>';
      } else {
        $w_html.=chr(13).'        <td colspan=2>&nbsp;</td>';
      } 
      $w_html.=chr(13).'        <td align="right"><font size="1"><b>Realizado</b></td>';
      $w_html.=chr(13).'        <td align="right"><font size="1"><b>'.number_format($w_real,2,',','.').'</b></td>';
      $w_html.=chr(13).'        <td>&nbsp;</td>';
      $w_html.=chr(13).'      </tr>';
    } 
    $w_html.=chr(13).'         </table></td></tr>';
  } 
  if ($l_P1==4 && ($l_O=='L' || $l_O=='V' || $l_O=='T')) {
    // Arquivos vinculados
    $RS = db_getSolicAnexo::getInstanceOf($dbms,$l_chave,null,$w_cliente);
    $RS = SortArray($RS,'nome','asc');
    if (count($RS)>0) {
      $w_html.=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Arquivos anexos</td>';
      $w_html.=chr(13).'      <tr><td align="center" colspan="2">';
      $w_html.=chr(13).'        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
      $w_html.=chr(13).'          <tr bgcolor="'.$w_TrBgColor.'" align="center">';
      $w_html.=chr(13).'          <td><font size="1"><b>Título</font></td>';
      $w_html.=chr(13).'          <td><font size="1"><b>Descrição</font></td>';
      $w_html.=chr(13).'          <td><font size="1"><b>Tipo</font></td>';
      $w_html.=chr(13).'          <td><font size="1"><b>KB</font></td>';
      $w_html.=chr(13).'          </tr>';
      $w_cor=$w_TrBgColor;
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        $w_html.=chr(13).'      <tr valign="top" bgcolor="'.$w_cor.'">';
        if ($l_P4!=1) {
          $w_html.=chr(13).'        <td><font size="1">'.LinkArquivo('HL',$w_cliente,f($row,'chave_aux'),'_blank','Clique para exibir o arquivo em outra janela.',f($row,'nome'),null).'</td>';
        } else {
          $w_html.=chr(13).'        <td><font size="1">'.f($row,'nome').'</td>';
        } 
        $w_html.=chr(13).'        <td><font size="1">'.Nvl(f($row,'descricao'),'---').'</td>';
        $w_html.=chr(13).'        <td><font size="1">'.f($row,'tipo').'</td>';
        $w_html.=chr(13).'        <td align="right"><font size="1">'.(round(f($row,'tamanho')/1024,1)).'&nbsp;</td>';
        $w_html.=chr(13).'      </tr>';
      } 
      $w_html.=chr(13).'         </table></td></tr>';
    } 
  } 
  // Se for envio, executa verificações nos dados da solicitação
  $w_erro = ValidaConvenio($w_cliente,$l_chave,substr($SG,0,3).'GERAL',null,null,null,Nvl($w_tramite,0));
  if ($w_erro>'') {
    $w_html.=chr(13).'<tr bgcolor="'.$w_TrBgColor.'"><td colspan=2><font size=2>';
    $w_html.=chr(13).'<HR>';
    if (substr($w_erro,0,1)=='0') {
      $w_html.=chr(13).'  <font color="#BC3131"><b>ATENÇÃO:</b></font> Foram identificados os erros listados abaixo, não sendo possível seu encaminhamento para fases posteriores à atual.';
    } elseif (substr($w_erro,0,1)=='1') {
      $w_html.=chr(13).'  <font color="#BC3131"><b>ATENÇÃO:</b></font> Foram identificados os erros listados abaixo. Seu encaminhamento para fases posteriores à atual só pode ser feito por um gestor do sistema ou do módulo de projetos.';
    } else {
      $w_html.=chr(13).'  <font color="#BC3131"><b>ATENÇÃO:</b></font> Foram identificados os alertas listados abaixo. Eles não impedem o encaminhamento para fases posteriores à atual, mas convém sua verificação.';
    } 
    $w_html.=chr(13).'  <ul>'.substr($w_erro,1,1000).'</ul>';
    $w_html.=chr(13).'  </font></td></tr>';
  } 
  if ($l_P1==4 && ($l_O=='L' || $l_O=='V' || $l_O=='T')) {
    // Encaminhamentos
    $RS = db_getSolicLog::getInstanceOf($dbms,$l_chave,null,'LISTA');
    $RS = SortArray($RS,'phpdt_data','desc','sq_siw_solic_log','desc');
    $w_html.=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Ocorrências e Anotações</td>';
    $w_html.=chr(13).'      <tr><td align="center" colspan="2">';
    $w_html.=chr(13).'        <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
    $w_html.=chr(13).'          <tr bgcolor="'.$conTrBgColor.'" align="center">';
    $w_html.=chr(13).'            <td><font size="1"><b>Data</font></td>';
    $w_html.=chr(13).'            <td><font size="1"><b>Despacho/Observação</font></td>';
    $w_html.=chr(13).'            <td><font size="1"><b>Responsável</font></td>';
    $w_html.=chr(13).'            <td><font size="1"><b>Fase / Destinatário</font></td>';
    $w_html.=chr(13).'          </tr>';
    if (count($RS)<=0) {
      $w_html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'"><td colspan=6 align="center"><font size="1"><b>Não foram encontrados encaminhamentos.</b></td></tr>';
    } else {
      $w_html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'" valign="top">';
      $w_cor=$conTrBgColor;
      $i = 0;
      foreach($RS as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        if ($i==0) {
          $w_html.=chr(13).'        <td colspan=6><font size="1">Fase atual: <b>'.f($row,'fase').'</b></td>';
          $i = 1;
        }
        $w_html.=chr(13).'      <tr valign="top" bgcolor="'.$w_cor.'">';
        $w_html.=chr(13).'        <td nowrap><font size="1">'.FormataDataEdicao(f($row,'phpdt_data'),3).'</td>';
        if (Nvl(f($row,'caminho'),'')>'') {
          $w_html.=chr(13).'        <td><font size="1">'.CRLF2BR(Nvl(f($row,'despacho'),'---').'<br>'.LinkArquivo('HL',$w_cliente,f($row,'sq_siw_arquivo'),'_blank','Clique para exibir o anexo em outra janela.','Anexo - '.f($row,'tipo').' - '.round(f($row,'tamanho')/1024,1).' KB',null)).'</td>';
        } else {
          $w_html.=chr(13).'        <td><font size="1">'.CRLF2BR(Nvl(f($row,'despacho'),'---')).'</td>';
        } 
        $w_html.=chr(13).'        <td nowrap><font size="1">'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'responsavel')).'</td>';
        if (nvl(f($row,'sq_acordo_log'),'')>'' && nvl(f($row,'destinatario'),'')>'') {
          $w_html.=chr(13).'        <td nowrap><font size="1">'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'sq_pessoa_destinatario'),$TP,f($row,'destinatario')).'</td>';
        } elseif (nvl(f($row,'sq_acordo_log'),'')>'' && nvl(f($row,'destinatario'),'')=='') {
          $w_html.=chr(13).'        <td nowrap><font size="1">Anotação</td>';
       } else {
          $w_html.=chr(13).'        <td nowrap><font size="1">'.Nvl(f($row,'tramite'),'---').'</td>';
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