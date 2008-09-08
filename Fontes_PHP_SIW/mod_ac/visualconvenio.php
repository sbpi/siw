<?
// =========================================================================
// Rotina de visualização dos dados do convenio
// -------------------------------------------------------------------------
function VisualConvenio($l_chave,$l_O,$l_usuario,$l_P1,$l_P4) {
  extract($GLOBALS);
  if ($l_P4==1) $w_TrBgColor=''; else $w_TrBgColor=$conTrBgColor;
  $l_html='';
  // Carrega o segmento do cliente
  $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente); 
  $w_segmento     = f($RS,'segmento');
  $w_nome_cliente = f($RS,'nome_resumido');
  // Recupera os dados do acordo
  $RS = db_getSolicData::getInstanceOf($dbms,$l_chave,substr($SG,0,3).'GERAL');
  $w_tramite        = f($RS,'sq_siw_tramite');
  $w_tramite_ativo  = f($RS,'ativo');
  $w_valor_inicial  = f($RS,'valor');
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
    $l_html.=chr(13).'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
    $l_html.=chr(13).'<tr><td>';
    $l_html.=chr(13).'    <table width="99%" border="0">';
    if (!($l_P1==4 || $l_P4==1)) {
      $l_html.=chr(13).'      <td colspan=2 align="right"><font size="1"><b><A class="hl" HREF="'.$w_dir.$w_pagina.'visual&O=T&w_chave='.f($RS,'sq_siw_solicitacao').'&w_tipo=volta&P1=4&P2='.$P2.'&P3='.$P3.'&P4='.$l_P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe todas as informações.">Exibir todas as informações</a></td>';
    } 
    // Se a classificação foi informada, exibe.
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0" align=justify><font size="2"><b>CONVÊNIO: '.f($RS,'codigo_interno').' - '.f($RS,'titulo').' ('.$l_chave.')'.'</b></font></td></tr>';
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>IDENTIFICAÇÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    // Exibe a vinculação
    $l_html.=chr(13).'      <tr><td valign="top"><b>Vinculação: </b></td>';
    if($l_P4==1) $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS,'sq_solic_pai'),f($RS,'dados_pai'),'S','S').'</td></tr>';
    else         $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS,'sq_solic_pai'),f($RS,'dados_pai'),'S').'</td></tr>';

    if (nvl(f($RS,'nm_etapa'),'')>'') {
      if (substr($w_sigla,0,3)=='GCB') {   
        $l_html.=chr(13).'      <tr valign="top"><td><b>Modalidade: </b></td>';
        $l_html.=chr(13).'          <td>      '.f($RS,'nm_etapa').'</td></tr>';
      } else { 
        $l_html.=chr(13).'      <tr valign="top"><td><b>Etapa: </b></td>';
        $l_html.=chr(13).'          <td>      '.f($RS,'nm_etapa').'</td></tr>';
      }
    } 

    // Se a classificação foi informada, exibe.
    if (Nvl(f($RS,'sq_cc'),'')>'') {
      $l_html .= chr(13).'      <tr><td width="30%"><b>Classificação:<b></td>';
      $l_html .= chr(13).'        <td>'.f($RS,'nm_cc').' </td></tr>';
    }

    $l_html.=chr(13).'      <tr><td width="30%"><font size=1><b>Titulo:</b></td>';
    $l_html.=chr(13).'          <td>'.CRLF2BR(Nvl(f($RS,'titulo'),'---')).'</b></font></td></tr>';
    $l_html.=chr(13).'      <tr><td width="30%"><font size=1><b>Objeto:</b></td>';
    $l_html.=chr(13).'          <td>'.CRLF2BR(f($RS,'objeto')).'</b></font></td></tr>';
    $l_html.=chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
    $l_html.=chr(13).'      <tr><td valign="top" width="30%"><font size="1"><b>Tipo:</b></td>';
    $l_html.=chr(13).'          <td>'.f($RS,'nm_tipo_acordo').' </b></td>';
    $l_html.=chr(13).'      <tr><td valign="top"><font size="1"><b>Executor:</b></td>';
    $l_html.=chr(13).'          <td>'.$w_nome_cliente.' </b></td>';
    $l_html.=chr(13).'      <tr><tr valign="top">';
    $l_html.=chr(13).'      <tr><td><font size="1"><b>Cidade de origem:</b></td>';
    $l_html.=chr(13).'          <td>'.f($RS,'nm_cidade').' ('.f($RS,'co_uf').')</td></tr>';
    if ($l_P4!=1) {
      $l_html.=chr(13).'          <tr><td><font size="1"><b>Responsável monitoramento:</b></td>';
      $l_html.=chr(13).'              <td>'.ExibePessoa($w_dir_volta,$w_cliente,f($RS,'solicitante'),$TP,f($RS,'nm_solic')).'</b></td>';
      $l_html.=chr(13).'          <tr><td><font size="1"><b>Unidade responsável monitoramento:</b></td>';
      $l_html.=chr(13).'              <td>'.ExibeUnidade($w_dir_volta,$w_cliente,f($RS,'nm_unidade_resp'),f($RS,'sq_unidade'),$TP).'</b></td>';
    } else {
      $l_html.=chr(13).'          <tr><td><font size="1"><b>Responsável monitoramento:</b></td>';
      $l_html.=chr(13).'              <td>'.f($RS,'nm_solic').'</td></tr>';
      $l_html.=chr(13).'          <tr><td><font size="1"><b>Unidade responsável monitoramento:</b></td>';
      $l_html.=chr(13).'              <td>'.f($RS,'nm_unidade_resp').'</td></tr>';
    } 
    // Se for visão completa
    if ($w_tipo_visao==0) {
      $l_html.=chr(13).'          <tr><td valign="top"><font size="1"><b>Valor:</b></td>';
      $l_html.=chr(13).'              <td>'.number_format(f($RS,'valor'),2,',','.').' </b></td>';
    } 
    if($w_segmento=='Público') {
      $l_html.=chr(13).'          <tr><td><font size="1"><b>Número do processo:</b></td>';
      $l_html.=chr(13).'              <td>'.Nvl(f($RS,'processo'),'---').'</td></tr>';
      $l_html.=chr(13).'          <tr><td><font size="1"><b>Assinatura:</b></td>';
      $l_html.=chr(13).'              <td>'.FormataDataEdicao(f($RS,'assinatura')).' </b></td>';
      $l_html.=chr(13).'          <tr><td><font size="1"><b>Publicação D.O.:</b></td>';
      $l_html.=chr(13).'              <td>'.FormataDataEdicao(f($RS,'publicacao')).' </b></td>';    
    }
    $l_html.=chr(13).'          <tr><td><font size="1"><b>Início vigência:</b></td>';
    $l_html.=chr(13).'              <td>'.FormataDataEdicao(f($RS,'inicio')).' </b></td>';
    $l_html.=chr(13).'          <tr><td><font size="1"><b>Término vigência:</b></td>';
    $l_html.=chr(13).'              <td>'.FormataDataEdicao(f($RS,'fim')).' </b></td>';
    $l_html.=chr(13).'          </table>';
    if ($w_tipo_visao==0 || $w_tipo_visao==1) {
      // Informações adicionais
      if (Nvl(f($RS,'descricao'),'')>'' || Nvl(f($RS,'justificativa'),'')>'') {
        $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>INFORMAÇÕES ADICIONAIS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
        if (Nvl(f($RS,'descricao'),'')>'') 
          $l_html.=chr(13).'      <tr><td valign="top"><font size="1"><b>Resultados esperados:</b></td>';
          $l_html.=chr(13).'           <td>'.CRLF2BR(f($RS,'descricao')).'</td>';
        if ($w_tipo_visao==0 && Nvl(f($RS,'justificativa'),'')>'') {
          $l_html.=chr(13).'      <tr><td valign="top"><font size="1"><b>Observações:</b></td>';
          $l_html.=chr(13).'          <td>'.CRLF2BR(f($RS,'justificativa')).'</td>';
        } 
      } 
    } 
    // Dados da conclusão da demanda, se ela estiver nessa situação
    if (Nvl(f($RS,'conclusao'),'')>'') {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS DO ENCERRAMENTO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html.=chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
      $l_html.=chr(13).'          <tr valign="top">';
      $l_html.=chr(13).'          <td><font size="1">Início da vigência:<br><b>'.FormataDataEdicao(f($RS,'inicio_real')).' </b></td>';
      $l_html.=chr(13).'          <td><font size="1">Término da vigência:<br><b>'.FormataDataEdicao(f($RS,'fim_real')).' </b></td>';
      if ($w_tipo_visao==0) {
        $l_html.=chr(13).'          <td><font size="1">Valor realizado:<br><b>'.number_format(f($RS,'valor_atual'),2,',','.').' </b></td>';
      } 
      $l_html.=chr(13).'          </table>';
      if ($w_tipo_visao==0) {
        $l_html.=chr(13).'      <tr><td valign="top"><font size="1">Nota de conclusão:<br><b>'.CRLF2BR(f($RS,'observacao')).' </b></td>';
      } 
    } 
    // Exibe ficha completa
    if ($l_P1==4) {
      // Termo de referência
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>TERMO DE REFERÊNCIA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html.=chr(13).'      <tr valign="top"><td><font size="1"><b>Atividades a serem desenvolvidas:</b></td>';
      $l_html.=chr(13).'          <td>'.nvl(CRLF2BR(f($RS,'atividades')),'---').'</td>';
      $l_html.=chr(13).'      <tr valign="top"><td><font size="1"><b>Produtos a serem entregues:</b></td>';
      $l_html.=chr(13).'          <td>'.nvl(CRLF2BR(f($RS,'produtos')),'---').'</td>';
      $l_html.=chr(13).'      <tr><td><font size="1"><b>Código para a outra parte:</b></td>';
      $l_html.=chr(13).'          <td>'.Nvl(f($RS,'codigo_externo'),'---').'</td></tr>';
      $l_html.=chr(13).'      <tr><td><font size="1"><b>Prestação de contas:</b></td>';
      $l_html.=chr(13).'          <td>'.Nvl(f($RS,'nm_prestacao_contas'),'---').'</td></tr>';
      if (Nvl(f($RS,'cd_modalidade'),'')=='F' || Nvl(f($RS,'cd_modalidade'),'')=='I') {
        $l_html.=chr(13).'          <tr><td colspan="2"><table border=0 width="100%" cellspacing=0 cellpadding=0><tr valign="top">';
        $l_html.=chr(13).'          <td width="30%"><font size="1"><b>Pemite vinculação de projetos?</b></td>';
        $l_html.=chr(13).'              <td>'.f($RS,'nm_vincula_projeto').'</td>';
        if (Nvl(f($RS,'cd_modalidade'),'')=='F') {
          $l_html.=chr(13).'          <tr><td><font size="1"><b>Pemite vinculação de demandas?</b></td>';
          $l_html.=chr(13).'              <td>'.f($RS,'nm_vincula_demanda').'</td>';
          $l_html.=chr(13).'          <tr><td><font size="1"><b>Pemite vinculação de viagens?</b></td>';
          $l_html.=chr(13).'              <td>'.f($RS,'nm_vincula_viagem').'</td>';
        }
        $l_html.=chr(13).'          </table>';
      } 
    }           
    // Exibe Dados bancários
    if ($l_P1==4) {
      if (f($RS,'sq_tipo_pessoa')==1) {
        $l_html.=chr(13).'      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>';
        $l_html.=chr(13).'          <tr valign="top">';
        $l_html.=chr(13).'          <td><font size="1">Sexo:<b><br>'.f($row,'nm_sexo').'</td>';
        $l_html.=chr(13).'          <td><font size="1">Data de nascimento:<b><br>'.FormataDataEdicao(f($row,'nascimento')).'</td>';
        $l_html.=chr(13).'          <tr valign="top">';
        $l_html.=chr(13).'          <td><font size="1">Identidade:<b><br>'.f($row,'rg_numero').'</td>';
        $l_html.=chr(13).'          <td><font size="1">Data de emissão:<b><br>'.Nvl(f($row,'rg_emissao'),'---').'</td>';
        $l_html.=chr(13).'          <td><font size="1">Órgão emissor:<b><br>'.f($row,'rg_emissor').'</td>';
        $l_html.=chr(13).'          <tr valign="top">';
        $l_html.=chr(13).'          <td><font size="1">Passaporte:<b><br>'.Nvl(f($row,'passaporte_numero'),'---').'</td>';
        $l_html.=chr(13).'          <td><font size="1">País emissor:<b><br>'.Nvl(f($row,'nm_pais_passaporte'),'---').'</td>';
        $l_html.=chr(13).'          </table>';
      } 
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS PARA RECEBIMENTO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      // Exibe Dados Para Recebimento
      $l_html.=chr(13).'      <tr><td><font size="1"><b>Forma de recebimento:</b></td>';
      $l_html.=chr(13).'          <td>'.f($RS,'nm_forma_pagamento').'</td>';
      $l_html.=chr(13).'      <tr><td colspan="2"><table border=0 width="100%" cellspacing=0>';
      if (!(strpos('CREDITO,DEPOSITO',f($RS,'sg_forma_pagamento'))===false)) {
        if (Nvl(f($RS,'cd_banco'),'')>'') {
          $l_html.=chr(13).'          <tr><td width=30%><font size="1"><b>Banco:</b></td>';        
          $l_html.=chr(13).'              <td>'.f($RS,'cd_banco').' - '.f($RS,'nm_banco').'</td>';
          $l_html.=chr(13).'          <tr><td><font size="1"><b>Agência:</b></td>';
          $l_html.=chr(13).'              <td>'.f($RS,'cd_agencia').' - '.f($RS,'nm_agencia').'</td>';
          if (f($RS,'exige_operacao')=='S') $l_html.=chr(13).'          <tr><td><font size="1"><b>Operação:</b></td><td>'.Nvl(f($RS,'operacao_conta'),'---').'</td>';
          $l_html.=chr(13).'          <tr><td><font size="1"><b>Número da conta:</b></td>';
          $l_html.=chr(13).'              <td>'.Nvl(f($RS,'numero_conta'),'---').'</td>';
        } 
      } 
      $l_html.=chr(13).'          </table>';    
    } 
    // Outra parte
    $RSQuery = db_getConvOutraParte::getInstanceOf($dbms,null,$l_chave,null,null);
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>OUTRA PARTE<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    if (count($RSQuery)==0) {
      $l_html.=chr(13).'      <tr><td colspan=7 align="center"><font size=1>Outra parte não informada';
    } else {
      $l_html.=chr(13).'      <tr><td colspan="2" align="center">';
      $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
      foreach($RSQuery as $row) { 
        $l_html.=chr(13).'      <tr><td colspan=7 bgColor="#f0f0f0"style="border: 1px solid rgb(0,0,0);" ><font size=1>Outra parte:<b>';
        $l_html.=chr(13).'          '.f($row,'nm_pessoa').' ('.f($row,'nome_resumido').')';
        $l_html.=chr(13).'          - '.f($row,'cnpj').'</b>';
        $l_html.=chr(13).'         <br>Tipo:<b> '.f($row,'nm_tipo');
        // Preposto
        $RSQuery1 = db_getConvPreposto::getInstanceOf($dbms,$l_chave,f($row,'sq_acordo_outra_parte'),null);            
        $l_html.=chr(13).'      <tr><td colspan="7"><font size="1"><b>Preposto</td>';
        if (count($RSQuery1)==0) {
          $l_html.=chr(13).'      <tr><td colspan=7><font size=1>Preposto não informado';
        } else {
          $l_html.=chr(13).'      <tr><td align="center" colspan="6">';
          $l_html.=chr(13).'       <tr><td bgColor="#f0f0f0" align="center"><b>Nome</b></td>';
          $l_html.=chr(13).'            <td colspan=2 bgColor="#f0f0f0" align="center"><b>CPF</b></td>';
          $l_html.=chr(13).'            <td bgColor="#f0f0f0" align="center"><b>Sexo</b></td>';
          $l_html.=chr(13).'            <td bgColor="#f0f0f0" align="center"><b>Identidade</b></td>';
          $l_html.=chr(13).'            <td bgColor="#f0f0f0" align="center"><b>Data emissão</b></td>';
          $l_html.=chr(13).'            <td bgColor="#f0f0f0" align="center"><b>Orgão emissão</b></td>';         
          foreach($RSQuery1 as $row1) {
            $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
            $l_html.=chr(13).'      <tr>';
            $l_html.=chr(13).'        <td ><font size="1">'.f($row1,'nm_pessoa').'</td>';
            $l_html.=chr(13).'        <td colspan=2 align="center"><font size="1">'.f($row1,'cpf').'</td>';
            $l_html.=chr(13).'        <td><font size="1">'.f($row1,'nm_sexo').'</td>';
            $l_html.=chr(13).'        <td><font size="1">'.Nvl(f($row1,'rg_numero'),'---').'</td>';
            $l_html.=chr(13).'        <td><font size="1">'.FormataDataEdicao(Nvl(f($row1,'rg_emissao'),'---')).'</td>';
            $l_html.=chr(13).'        <td><font size="1">'.Nvl(f($row1,'rg_emissor'),'---').'</td>';                   
            // Exibe ficha completa
          }
        }             
        // Representantes
        //$RSQuery = db_getAcordoRep::getInstanceOf($dbms,f($RS,'sq_siw_solicitacao'),$w_cliente,null,null);
        $RSQuery = db_getConvOutroRep::getInstanceOf($dbms,$l_chave,null,f($row,'sq_acordo_outra_parte'));
        $RSQuery = SortArray($RSQuery,'nm_pessoa','asc');
        $l_html.=chr(13).'      <tr><td colspan="7"><font size="1"><b>Representantes</td>';
        if (count($RSQuery)==0) {
          $l_html.=chr(13).'      <tr><td colspan=7><font size=1>Representantes não informados';
        } else {
          $l_html.=chr(13).'      <tr><td align="center" colspan="2">';
          $l_html.=chr(13).'          <tr><td bgColor="#f0f0f0" align="center"><b><b>Nome</b></td>';         
          $l_html.=chr(13).'            <td bgColor="#f0f0f0" align="center"><b>CPF</b></td>';         
          $l_html.=chr(13).'            <td bgColor="#f0f0f0" align="center"><b><b>DDD</b></td>';         
          $l_html.=chr(13).'            <td bgColor="#f0f0f0" align="center"><b><b>Telefone</b></td>';         
          $l_html.=chr(13).'            <td bgColor="#f0f0f0" align="center"><b><b>Fax</b></td>';         
          $l_html.=chr(13).'            <td bgColor="#f0f0f0" align="center"><b><b>Celular</b></td>';         
          $l_html.=chr(13).'            <td bgColor="#f0f0f0" align="center"><b><b>e-Mail</b></td>';         
          $l_html.=chr(13).'          </tr>';
          $w_cor=$w_TrBgColor;
          foreach($RSQuery as $row2) {
            $l_html.=chr(13).'      <tr><td><font size="1">'.f($row2,'nm_pessoa').'</td>';
            $l_html.=chr(13).'        <td align="center"><font size="1">'.f($row2,'cpf').'</td>';
            $l_html.=chr(13).'        <td align="center" ><font size="1">'.Nvl(f($row2,'ddd'),'---').'</td>';
            $l_html.=chr(13).'        <td><font size="1">'.Nvl(f($row2,'nr_telefone'),'---').'</td>';
            $l_html.=chr(13).'        <td><font size="1">'.Nvl(f($row2,'nr_fax'),'---').'</td>';
            $l_html.=chr(13).'        <td><font size="1">'.Nvl(f($row2,'nr_celular'),'---').'</td>';
            if (Nvl(f($row2,'email'),'nulo')!='nulo') {
              if (!$l_P4==1) {
                $l_html.=chr(13).'        <td><font size="1"><a class="hl" href="mailto:'.f($row2,'email').'">'.f($row2,'email').'</a></td>';
              } else {
                $l_html.=chr(13).'        <td><font size="1">'.f($row2,'email').'</td>';
              } 
            } else {
              $l_html.=chr(13).'        <td><font size="1">---</td>';
            } 
            $l_html.=chr(13).'      </tr>';
          } 
        } 
        $l_html.=chr(13).'      <tr><td colspan=7 style="border: 1px solid rgb(0,0,0);" ><font size=1>&nbsp;<b>';

      } 

    }
    $l_html.=chr(13).'         </table></td></tr>';
  } 
  // Se for listagem, exibe os outros dados dependendo do tipo de visão  do usuário
  if ($w_tipo_visao!=2 && ($l_O=='L' || $l_O=='T') && $l_P1==4) {
    if (f($RS,'aviso_prox_conc')=='S') {
      // Configuração dos alertas de proximidade da data limite para conclusão do acordo
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ALERTAS DE PROXIMIDADE DA DATA PREVISTA DE TÉRMINO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html.=chr(13).'        <tr><td colspan="2" align="center">';
      $l_html.=chr(13).'          <table width=100%  border="0" >';
      $l_html.=chr(13).'          <tr><td width=30%><font size="1"><b>Emite aviso:</b></td>';    
      $l_html.=chr(13).'             <td>'.str_replace('N','Não',str_replace('S','Sim',f($RS,'aviso_prox_conc'))).' </b></td>';
      $l_html.=chr(13).'          <tr><td><font size="1"><b>Dias:</b></td>';    
      $l_html.=chr(13).'            <td>'.f($RS,'dias_aviso').' </b></td>';
      $l_html.=chr(13).'          </table>';
    } 
  } 
  // Parcelas
  $RS = db_getAcordoParcela::getInstanceOf($dbms,$l_chave,null,null,null,null,null,null,null,null,null);
  $RS = SortArray($RS,'ordem','asc');
  if (count($RS)>0) {
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>PARCELAS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $l_html.=chr(13).'      <tr><td colspan="2" align="center">';
    $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
    $l_html.=chr(13).'          <tr align="center">';
    $l_html.=chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><b>Ordem</b></td>';
    $l_html.=chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><b>Vencimento</b></td>';
    $l_html.=chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><b>Valor</b></td>';
    $l_html.=chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><b>Observações</b></td>';
    $l_html.=chr(13).'            <td colspan=4 bgColor="#f0f0f0"><b>Financeiro</b></td>';
    $l_html.=chr(13).'          </tr>';
    $l_html.=chr(13).'          <tr><td bgColor="#f0f0f0" align="center"><b>Lançamento</b></td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0" align="center"><b>Vencimento</b></td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0" align="center"><b>Valor</b></td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0" align="center"><b>Quitação</b></td>';
    $l_html.=chr(13).'          </tr>';
    $w_cor=$w_TrBgColor;
    $w_total=0;
    foreach($RS as $row) {
      $l_html.=chr(13).'      <tr valign="top">';
      $l_html.=chr(13).'        <td align="center"><font size="1">';
      if (Nvl($w_sg_tramite,'-')=='CR' && $w_fim-f($row,'vencimento')<0) {
        $l_html.=chr(13).'           <img src="'.$conImgCancel.'" border=0 width=15 heigth=15 align="center" title="Parcela cancelada!">';
      } elseif (Nvl(f($row,'quitacao'),'nulo')=='nulo') {
        if (f($row,'vencimento')<addDays(time(),-1))  {
          $l_html.=chr(13).'           <img src="'.$conImgAtraso.'" border=0 width=15 heigth=15 align="center">';
        } elseif (f($row,'vencimento')-addDays(time(),-1)<=5) {
          $l_html.=chr(13).'           <img src="'.$conImgAviso.'" border=0 width=15 height=15 align="center">';
        } else {
          $l_html.=chr(13).'           <img src="'.$conImgNormal.'" border=0 width=15 height=15 align="center">';
        } 
      } else {
        if (f($row,'quitacao')>f($row,'vencimento')) {
          $l_html.=chr(13).'           <img src="'.$conImgOkAtraso.'" border=0 width=15 heigth=15 align="center">';
        } else {
          $l_html.=chr(13).'           <img src="'.$conImgOkNormal.'" border=0 width=15 height=15 align="center">';
        } 
      } 
      $l_html.=chr(13).'        '.f($row,'ordem').'</td>';
      $l_html.=chr(13).'        <td align="center"><font size="1">'.FormataDataEdicao(f($row,'vencimento')).'</td>';
      $l_html.=chr(13).'        <td align="right"><font size="1">'.number_format(f($row,'valor'),2,',','.').'</td>';
      $l_html.=chr(13).'        <td><font size="1">'.Nvl(f($row,'observacao'),'---').'</td>';
      if (Nvl(f($row,'cd_lancamento'),'')>'') {
        if($l_P4==1) $l_html.=chr(13).'        <td align="center" nowrap>'.f($row,'cd_lancamento').'</td>';
        else         $l_html.=chr(13).'        <td align="center" nowrap><A class="hl" HREF="mod_fn/lancamento.php?par=Visual&O=L&w_chave='.f($row,'sq_lancamento').'&w_tipo=&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$l_P4.'&TP='.$TP.'&SG=FN'.substr($SG,2,1).'CONT" title="Exibe as informações do lançamento." target="Lancamento">'.f($row,'cd_lancamento').'</a></td>';
        $l_html.=chr(13).'        <td align="center"><font size="1">'.FormataDataEdicao(f($row,'dt_lancamento')).'</td>';
        $l_html.=chr(13).'        <td align="right"><font size="1">'.number_format(f($row,'vl_lancamento'),2,',','.').'</td>';
        $w_real=$w_real+f($row,'vl_lancamento');
      } else {
        $l_html.=chr(13).'        <td align="center"><font size="1">---</td>';
        $l_html.=chr(13).'        <td align="center"><font size="1">---</td>';
        $l_html.=chr(13).'        <td align="center"><font size="1">---</td>';
      } 
      $l_html.=chr(13).'        <td align="center"><font size="1">'.Nvl(FormataDataEdicao(f($row,'quitacao')),'---').'</td>';
      $l_html.=chr(13).'      </tr>';
      $w_total=$w_total+f($row,'valor');
    } 
    if ($w_total>0 || $w_real>0) {
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
      $l_html.=chr(13).'      <tr valign="top">';
      $l_html.=chr(13).'        <td align="right" colspan=2><font size="1"><b>Previsto</b></td>';
      $l_html.=chr(13).'        <td align="right"><font size="1"><b>'.number_format($w_total,2,',','.').'</b></td>';
      if (round($w_valor_inicial-$w_total,2)!=0) {
        $l_html.=chr(13).'        <td colspan=2><font size=1><b>O valor das parcelas difere do valor contratado ('.number_format($w_valor_inicial-$w_total,2,',','.').')</b></td>';
      } else {
        $l_html.=chr(13).'        <td colspan=2>&nbsp;</td>';
      } 
      $l_html.=chr(13).'        <td align="right"><font size="1"><b>Realizado</b></td>';
      $l_html.=chr(13).'        <td align="right"><font size="1"><b>'.number_format($w_real,2,',','.').'</b></td>';
      $l_html.=chr(13).'        <td>&nbsp;</td>';
      $l_html.=chr(13).'      </tr>';
    } 
    $l_html.=chr(13).'         </table></td></tr>';
  } 
  if ($l_P1==4 && ($l_O=='L' || $l_O=='V' || $l_O=='T')) {
    // Arquivos vinculados
    $RS = db_getSolicAnexo::getInstanceOf($dbms,$l_chave,null,$w_cliente);
    $RS = SortArray($RS,'nome','asc');
    if (count($RS)>0) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ARQUIVOS ANEXOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html.=chr(13).'   <tr><td colspan="2" align="center">';
      $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
      $l_html.=chr(13).'       <tr><td bgColor="#f0f0f0" align="center"><b><b>Título</b></td>';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0" align="center"><b><b>Descrição</b></td>';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0" align="center"><b><b>Tipo</b></td>';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0" align="center"><b><b>KB</b></td>';
      $l_html.=chr(13).'       </tr>';
      $w_cor=$w_TrBgColor;
      foreach($RS as $row) {
        $l_html.=chr(13).'      <tr>';
        if ($l_P4!=1) {
          $l_html.=chr(13).'        <td><font size="1">'.LinkArquivo('HL',$w_cliente,f($row,'chave_aux'),'_blank','Clique para exibir o arquivo em outra janela.',f($row,'nome'),null).'</td>';
        } else {
          $l_html.=chr(13).'        <td><font size="1">'.f($row,'nome').'</td>';
        } 
        $l_html.=chr(13).'        <td><font size="1">'.Nvl(f($row,'descricao'),'---').'</td>';
        $l_html.=chr(13).'        <td><font size="1">'.f($row,'tipo').'</td>';
        $l_html.=chr(13).'        <td align="right"><font size="1">'.(round(f($row,'tamanho')/1024,1)).'&nbsp;</td>';
        $l_html.=chr(13).'      </tr>';
      } 
      $l_html.=chr(13).'         </table></td></tr>';
    } 
  } 

  // Projetos vinculados ao programa
  $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'PJCAD');
  $RS1 = db_getSolicList::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,f($RS,'sigla'),4,
         null,null,null,null,null,null,null,null,null,null,
         null,null,null,null,null,null,null,null,null,null,null,null,$l_chave,null,null,null);
  $RS1 = SortArray($RS1,'titulo','asc','prioridade','asc');
  if (count($RS1) > 0) {
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>'.strtoupper(f($RS,'nome')).' ('.count($RS1).' )<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $l_html.=chr(13).'   <tr><td colspan="2" align="center">';
    $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
    $l_html.=chr(13).'       <tr align="center">';
    $l_html.=chr(13).'         <td bgColor="#f0f0f0" rowspan=2><b>Nº</b></td>';
    $l_html.=chr(13).'         <td bgColor="#f0f0f0" rowspan=2><b>Responsável</b></td>';
    $l_html.=chr(13).'         <td bgColor="#f0f0f0" rowspan=2><b>Título</b></td>';
    $l_html.=chr(13).'         <td bgColor="#f0f0f0" colspan=2><b>Execução</b></td>';
    $l_html.=chr(13).'         <td bgColor="#f0f0f0" rowspan=2><b>Valor</b></td>';
    if ($l_P4!=1) $l_html.=chr(13).'         <td bgColor="#f0f0f0" rowspan=2 colspan=2><b>'.VisualIndicador($w_dir_volta,$w_cliente,'IDE',$TP,'IDE hoje').'</b></td>';
    else          $l_html.=chr(13).'         <td bgColor="#f0f0f0" rowspan=2 colspan=2><b>IDE</b></td>';
    if ($l_P4!=1) $l_html.=chr(13).'         <td bgColor="#f0f0f0" rowspan=2><b>'.VisualIndicador($w_dir_volta,$w_cliente,'IGE',$TP,'IGE').'</b></td>';
    else          $l_html.=chr(13).'         <td bgColor="#f0f0f0" rowspan=2><b>IGE</b></td>';
    $l_html.=chr(13).'       </tr>';
    $l_html.=chr(13).'       <tr align="center">';
    $l_html.=chr(13).'         <td bgColor="#f0f0f0"><b>De</b></td>';
    $l_html.=chr(13).'         <td bgColor="#f0f0f0"><b>De</b></td>';
    $l_html.=chr(13).'       </tr>';
    $w_cor=$conTrBgColor;
    foreach ($RS1 as $row) {
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
      $l_html .=chr(13).'      <tr bgcolor="'.$w_cor.'" valign="top">';
      $l_html .=chr(13).'        <td width="1%" nowrap>';
      $l_html .=chr(13).ExibeImagemSolic(f($row,'sigla'),f($row,'inicio'),f($row,'fim'),f($row,'inicio_real'),f($row,'fim_real'),f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null);
      if ($l_P4!=1) $l_html .=chr(13).'        <A class="HL" HREF="'.$conRootSIW.'projeto.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'sq_siw_solicitacao').'&nbsp;</a>'.exibeImagemRestricao(f($row,'restricao'),'P');
      else          $l_html .=chr(13).'        '.f($row,'sq_siw_solicitacao').'&nbsp;'.exibeImagemRestricao(f($row,'restricao'),'P');
      if ($l_P4!=1) $l_html .=chr(13).'        <td>'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'solicitante'),$TP,f($row,'nm_solic')).'</td>';
      else          $l_html .=chr(13).'        <td>'.f($row,'nm_solic').'</td>';
      $l_html .=chr(13).'        <td>'.Nvl(f($row,'titulo'),'-').'</td>';
      $l_html .=chr(13).'        <td align="center">&nbsp;'.FormataDataEdicao(f($row,'inicio'),5).'</td>';
      $l_html .=chr(13).'        <td align="center">&nbsp;'.FormataDataEdicao(f($row,'fim'),5).'</td>';
      if (f($row,'sg_tramite')=='AT') {
        $l_html .=chr(13).'        <td align="right">'.number_format(f($row,'custo_real'),2,',','.').'&nbsp;</td>';
        $w_parcial += f($row,'custo_real');
      } else {
        $l_html .=chr(13).'        <td align="right">'.number_format(f($row,'valor'),2,',','.').'&nbsp;</td>';
        $w_parcial += f($row,'valor');
      } 
      $l_html .=chr(13).'        <td align="center">'.ExibeSmile('IDE',f($row,'ide')).'</td>';
      $l_html .=chr(13).'        <td align="right">'.formatNumber(f($row,'ide')).'%</td>';
      $l_html .=chr(13).'        <td align="right">'.formatNumber(f($row,'ige')).'%</td>';
    } 
    if ($w_parcial>0) {
      $l_html .=chr(13).'        <tr bgcolor="'.$conTrBgColor.'">';
      $l_html .=chr(13).'          <td colspan=5 align="right"><b>Total&nbsp;</td>';
      $l_html .=chr(13).'          <td align="right"><b>'.number_format($w_parcial,2,',','.').'&nbsp;</td>';
      $l_html .=chr(13).'          <td colspan=3>&nbsp;</td>';
      $l_html .=chr(13).'        </tr>';
    }
    $l_html.=chr(13).'         </table></td></tr>';
    $l_html.=chr(13).'      <tr><td colspan="2"><font size="1">Observação: a listagem exibe apenas os projetos nos quais você tenha alguma permissão.</font></td></tr>';
  }    
  // Acompanhamento Financeiro
  $RS1 = db_getLinkData::getInstanceOf($dbms,$w_cliente,'PJCAD');
  $RS2 = db_getSolicList::getInstanceOf($dbms,f($RS1,'sq_menu'),$l_usuario,'PJCAD',3,
           null,null,null,null,null,null,null,null,null,null,null,null,null,null,
           null,null,null,null,null,null,null,null,null,null,$l_chave,null);
  $RS2 = SortArray($RS2,'titulo','asc');
  if (count($RS2)>0) {
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ACOMPANHAMENTO FINANCEIRO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $l_html.=chr(13).'      <tr><td colspan="2" align="center">';
    $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
    foreach ($RS2 as $row2){
      $RS = db_getSolicRubrica::getInstanceOf($dbms,f($row2,'sq_siw_solicitacao'),null,null,null,null,null,null,null,null);
      $RS = SortArray($RS,'codigo','asc');
      if (count($RS)>0) {
        if($l_P4!=1) $l_html .= chr(13).'          <tr><td colspan=9>Projeto: <b><A class="hl" HREF="projeto.php?par=Visual&O=L&w_chave='.f($row2,'sq_siw_solicitacao').'&w_tipo=Volta&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe as informações do projeto." target="_blank">'.f($row2,'titulo').' ('.f($row2,'sq_siw_solicitacao').')</a></b></td>';
        $l_html .= chr(13).'          <tr WIDTH="30% align="center">';
        $l_html .= chr(13).'            <td rowspan="2" bgColor="#f0f0f0"><b>Código</td>';
        $l_html .= chr(13).'            <td rowspan="2" bgColor="#f0f0f0"><b>Nome</td>';
        $l_html .= chr(13).'            <td rowspan="2" bgColor="#f0f0f0"><b>Valor Inicial</td>';
        $l_html .= chr(13).'            <td colspan="3" bgcolor="'.$conTrBgColorLightBlue1.'"><b>Entrada</td>';
        $l_html .= chr(13).'            <td colspan="3" bgcolor="'.$conTrBgColorLightRed1.'"><b>Saída</td>';
        $l_html .= chr(13).'          </tr>';
        $l_html .= chr(13).'          <tr WIDTH="70% bgcolor="'.$conTrAlternateBgColor.'" align="center">';
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
          $l_html .= chr(13).'      <tr valign="top">';
          if($l_P4!=1) $l_html .= chr(13).'          <td><A class="hl" HREF="javascript:location.href=this.location.href;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.'mod_fn/lancamento.php?par=Ficharubrica&O=L&w_sq_projeto_rubrica='.f($row,'sq_projeto_rubrica').'&w_tipo=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Extrato Rubrica'.'&SG='.$SG.MontaFiltro('GET')).'\',\'Ficha3\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Exibe as informações deste registro.">'.f($row,'codigo').'</A>&nbsp';
          else         $l_html .= chr(13).'          <td>'.f($row,'codigo').'&nbsp';
          $l_html .= chr(13).'          <td>'.f($row,'nome').' </td>';
          $l_html .= chr(13).'          <td align="right">'.number_format(f($row,'valor'),2,',','.').' </td>';
          $l_html .= chr(13).'          <td align="right" bgcolor="'.$w_cor_blue.'">'.number_format(f($row,'entrada_prevista'),2,',','.').' </td>';
          $l_html .= chr(13).'          <td align="right" bgcolor="'.$w_cor_blue.'">'.number_format(f($row,'entrada_real'),2,',','.').' </td>';
          $l_html .= chr(13).'          <td align="right" bgcolor="'.$w_cor_blue.'">'.number_format(f($row,'entrada_pendente'),2,',','.').' </td>';
          $l_html .= chr(13).'          <td align="right" bgcolor="'.$w_cor_red.'">'.number_format(f($row,'saida_prevista'),2,',','.').' </td>';
          $l_html .= chr(13).'          <td align="right" bgcolor="'.$w_cor_red.'">'.number_format(f($row,'saida_real'),2,',','.').' </td>';
          $l_html .= chr(13).'          <td align="right" bgcolor="'.$w_cor_red.'">'.number_format(f($row,'saida_pendente'),2,',','.').' </td>';
          $l_html .= chr(13).'      </tr>';
          $w_valor_inicial    += f($row,'valor');
          $w_entrada_prevista += f($row,'entrada_prevista');
          $w_entrada_real     += f($row,'entrada_real');
          $w_entrada_pendente += f($row,'entrada_pendente');
          $w_saida_prevista   += f($row,'saida_prevista');
          $w_saida_real       += f($row,'saida_real');
          $w_saida_pendente   += f($row,'saida_pendente');
        } 
        $l_html .= chr(13).'      <tr valign="top">';
        $l_html .= chr(13).'          <td align="right" colspan="2"><b>Total</td>';
        $l_html .= chr(13).'          <td align="right"><b>'.number_format($w_valor_inicial,2,',','.').' </b></td>';
        $l_html .= chr(13).'          <td align="right" bgcolor="'.$conTrBgColorLightBlue1.'"><b>'.number_format($w_entrada_prevista,2,',','.').' </b></td>';
        $l_html .= chr(13).'          <td align="right" bgcolor="'.$conTrBgColorLightBlue1.'"><b>'.number_format($w_entrada_real,2,',','.').' </b></td>';
        $l_html .= chr(13).'          <td align="right" bgcolor="'.$conTrBgColorLightBlue1.'"><b>'.number_format($w_entrada_pendente,2,',','.').' </b></td>';
        $l_html .= chr(13).'          <td align="right" bgcolor="'.$conTrBgColorLightRed1.'"><b>'.number_format($w_saida_prevista,2,',','.').' </b></td>';
        $l_html .= chr(13).'          <td align="right" bgcolor="'.$conTrBgColorLightRed1.'"><b>'.number_format($w_saida_real,2,',','.').' </b></td>';
        $l_html .= chr(13).'          <td align="right" bgcolor="'.$conTrBgColorLightRed1.'"><b>'.number_format($w_saida_pendente,2,',','.').' </b></td>';
        $l_html .= chr(13).'      </tr>';
      }       
    }
    $l_html .= chr(13).'         </table></td></tr>';
  }
  
  // Se for envio, executa verificações nos dados da solicitação
  $w_erro = ValidaConvenio($w_cliente,$l_chave,substr($SG,0,3).'GERAL',null,null,null,Nvl($w_tramite,0));
  if ($w_erro>'') {
    $l_html.=chr(13).'<tr><td colspan=6><font size=2>';
    $l_html.=chr(13).'<HR>';
    if (substr($w_erro,0,1)=='0') {
      $l_html.=chr(13).'  <font color="#BC3131"><b>ATENÇÃO:</b></font> Foram identificados os erros listados abaixo, não sendo possível seu encaminhamento para fases posteriores à atual.';
    } elseif (substr($w_erro,0,1)=='1') {
      $l_html.=chr(13).'  <font color="#BC3131"><b>ATENÇÃO:</b></font> Foram identificados os erros listados abaixo. Seu encaminhamento para fases posteriores à atual só pode ser feito por um gestor do sistema ou do módulo de projetos.';
    } else {
      $l_html.=chr(13).'  <font color="#BC3131"><b>ATENÇÃO:</b></font> Foram identificados os alertas listados abaixo. Eles não impedem o encaminhamento para fases posteriores à atual, mas convém sua verificação.';
    } 
    $l_html.=chr(13).'  <ul>'.substr($w_erro,1,1000).'</ul>';
    $l_html.=chr(13).'  </font></td></tr>';
  }
  
  if ($l_P1==4 && ($l_O=='L' || $l_O=='V' || $l_O=='T')) {
    include_once($w_dir_volta.'funcoes/exibeLog.php');
    $l_html .= exibeLog($l_chave,$l_O,$l_usuario,$w_tramite_ativo,(($l_P4==1) ? 'WORD' : 'HTML'));
  } 
  $l_html.=chr(13).'    </table>';
  $l_html.=chr(13).'</table>';
  return $l_html;
}
?>