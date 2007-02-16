<?
// =========================================================================
// Rotina de visualização dos dados do acordo
// -------------------------------------------------------------------------
function VisualAcordo($l_chave,$l_O,$l_usuario,$l_P1,$l_P4) {
  extract($GLOBALS);
  if ($l_P4==1) $w_TrBgColor=''; else $w_TrBgColor=$conTrBgColor;
  $w_html='';
  
  // Carrega o segmento do cliente
  $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente); 
  $w_segmento = f($RS,'segmento');
  
  // Recupera os dados do acordo
  $RS = db_getSolicData::getInstanceOf($dbms,$l_chave,substr($SG,0,3).'GERAL');
  $w_tramite        = f($RS,'sq_siw_tramite');
  $w_valor_inicial  = f($RS,'valor_inicial');
  $w_fim            = f($RS,'fim_real');
  $w_sg_tramite     = f($RS,'sg_tramite');
  $w_sigla          = f($RS,'sigla');

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
    $w_html.=chr(13).'<tr><td>';
    $w_html.=chr(13).'    <table width="99%" border="0">';
    if (!($l_P1==4 || $l_P4==1)) {
      $w_html.=chr(13).'       <tr><td align="right" colspan="2"><font size="1"><b><A class="hl" HREF="'.$w_dir.$w_pagina.'visual&O=T&w_chave='.f($RS,'sq_siw_solicitacao').'&w_tipo=volta&P1=4&P2='.$P2.'&P3='.$P3.'&P4='.$l_P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe todas as informações.">Exibir todas as informações</a></td>';
    } 
    $w_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    if (substr($w_sigla,0,3)=='GCA') $w_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>ACT: '.f($RS,'codigo_interno').' - '.f($RS,'titulo').' ('.$l_chave.')'.'</b></font></div></td></tr>';
    else                        $w_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>CONTRATO: '.f($RS,'codigo_interno').' - '.f($RS,'titulo').' ('.$l_chave.')'.'</b></font></div></td></tr>';
    $w_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    // Identificação do acordo
    $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>IDENTIFICAÇÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
    if (nvl(f($RS,'nm_projeto'),'')>'') {
      $w_html.=chr(13).'      <tr><td width="30%"><font size="1"><b>Projeto: </b></td>';
      $w_html.=chr(13).'          <td><A class="hl" HREF="projeto.php?par=Visual&O=L&w_chave='.f($RS,'sq_solic_pai').'&w_tipo=Volta&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe as informações do projeto." target="blank">'.f($RS,'nm_projeto').' ('.f($RS,'sq_solic_pai').')</a></b></font></td></tr>';
    } 
    if (nvl(f($RS,'nm_etapa'),'')>'') {
      if (substr($w_sigla,0,3)=='GCB') {   
        $w_html.=chr(13).'      <tr valign="top"><td><font size="1"><b>Modalidade: </b></td>';
        $w_html.=chr(13).'          <td>      '.f($RS,'nm_etapa').'</td></tr>';
      } else { 
        $w_html.=chr(13).'      <tr valign="top"><td><font size="1"><b>Etapa: </b></td>';
        $w_html.=chr(13).'          <td>      '.f($RS,'nm_etapa').'</td></tr>';
      }
    } 
    // Se a classificação foi informada, exibe.
    if (nvl(f($RS,'sq_cc'),'')>'') {
      $w_html.=chr(13).'      <tr valign="top"><td width="30%"><font size="1"><b>Classificação:</b></td>';
      $w_html.=chr(13).'                       <td>'.f($RS,'nm_cc').'</td></tr>';
    } 
    if (substr($w_sigla,0,3)=='GCB'){ 
      $w_html.=chr(13).'      <tr><td><b><font size=1>Plano de trabalho: </b></td>';
      $w_html.=chr(13).'          <td>'.f($RS,'codigo_interno').' ('.$l_chave.')<br>'.CRLF2BR(f($RS,'objeto')).'</b></font></td></tr>';
    } else {                        
      $w_html.=chr(13).'      <tr valign="top">';
      $w_html.=chr(13).'        <td><b><font size=1>Objeto: </b></td>';
      $w_html.=chr(13).'        <td>'.CRLF2BR(f($RS,'objeto')).'</td></tr>';
    }
    $w_html.=chr(13).'      <tr><td valign="top"><font size="1"><b>Tipo:</b></td>';
    $w_html.=chr(13).'          <td>'.f($RS,'nm_tipo_acordo').'</td></tr>';
    $w_html.=chr(13).'      <tr><td><font size="1"><b>Cidade de origem:</b></td>';
    $w_html.=chr(13).'          <td>'.f($RS,'nm_cidade').' ('.f($RS,'co_uf').')</td></tr>';
    if (!$l_P4==1) {
      $w_html.=chr(13).'          <tr><td><font size="1"><b>Responsável monitoramento:</b></td>';
      $w_html.=chr(13).'              <td>'.ExibePessoa($w_dir_volta,$w_cliente,f($RS,'solicitante'),$TP,f($RS,'nm_solic')).'</b></td>';
      $w_html.=chr(13).'          <tr><td><font size="1"><b>Unidade responsável monitoramento:</b></td>';
      $w_html.=chr(13).'              <td>'.ExibeUnidade($w_dir_volta,$w_cliente,f($RS,'nm_unidade_resp'),f($RS,'sq_unidade'),$TP).'</b></td>';
    } else {
      $w_html.=chr(13).'          <tr><td><font size="1"><b>Responsável monitoramento:</b></td>';
      $w_html.=chr(13).'              <td>'.f($RS,'nm_solic').'</td></tr>';
      $w_html.=chr(13).'          <tr><td><font size="1"><b>Unidade responsável monitoramento:</b></td>';
      $w_html.=chr(13).'              <td>'.f($RS,'nm_unidade_resp').'</td></tr>';
    } 
    // Se for visão completa
    if ($w_tipo_visao==0 && substr($w_sigla,0,3)!='GCA') {
      $w_html.=chr(13).'          <tr><td valign="top"><font size="1"><b>Valor:</b></td>';
      $w_html.=chr(13).'              <td>'.number_format(f($RS,'valor'),2,',','.').' </td></tr>';
    } 
    if($w_segmento=='Público') {
      $w_html.=chr(13).'          <tr valign="top">';
      if (substr($w_sigla,0,3)!='GCA' && substr($w_sigla,0,3)!='GCB'){   
        $w_html.=chr(13).'          <td><font size="1"><b>Número do empenho:</b></td>';
        $w_html.=chr(13).'          <td>'.Nvl(f($RS,'empenho'),'---').'</td></tr>';
      }
      if (substr($w_sigla,0,3)=='GCA') { 
        $w_html.=chr(13).'          <td><font size="1"><b>Número do processo:</b></td>';
        $w_html.=chr(13).'           <td>'.Nvl(f($RS,'processo'),'---').'</td></tr>';
      }
      if (substr($w_sigla,0,3)=='GCB'){ 
        $w_html.=chr(13).'          <td><font size="1"><b>Número do empenho (modalidade/nível/mensalidade):</b></td>';
        $w_html.=chr(13).'          <td>'.Nvl(f($RS,'processo'),'---').'</td></tr>';
      }
      $w_html.=chr(13).'          <tr valign="top">';
      $w_html.=chr(13).'          <td><font size="1"><b>Assinatura:</b></td>';
      $w_html.=chr(13).'          <td>'.Nvl(FormataDataEdicao(f($RS,'assinatura')),'---').'</td></tr>';
      if (substr($w_sigla,0,3)!='GCB') { 
        $w_html.=chr(13).'          <td><font size="1"><b>Publicação D.O.:</b></td>';
        $w_html.=chr(13).'          <td>'.Nvl(FormataDataEdicao(f($RS,'publicacao')),'---').'</td></tr>';
      }
    }
    $w_html.=chr(13).'          <tr><td><font size="1"><b>Início vigência:</b></td>';
    $w_html.=chr(13).'              <td>'.Nvl(FormataDataEdicao(f($RS,'inicio')),'---').'</td></tr>';
    $w_html.=chr(13).'          <tr><td><font size="1"><b>Término vigência:</b></td>';
    $w_html.=chr(13).'              <td>'.Nvl(FormataDataEdicao(f($RS,'fim')),'---').'</td></tr>';
    if ($w_tipo_visao==0 || $w_tipo_visao==1) {
      // Informações adicionais
      if (Nvl(f($RS,'descricao'),'')>'' || Nvl(f($RS,'justificativa'),'')>'') {
        $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>INFORMAÇÕES ADICIONAIS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
        if (Nvl(f($RS,'descricao'),'')>''){
           $w_html.=chr(13).'      <tr><td valign="top"><font size="1"><b>Resultados esperados:</b></td>';
           $w_html.=chr(13).'          <td>'.CRLF2BR(f($RS,'descricao')).'</td></tr>';
        }
        if ($w_tipo_visao==0 && Nvl(f($RS,'justificativa'),'')>'') {
          $w_html.=chr(13).'      <tr><td valign="top"><font size="1"><b>Observações:</b></td>';
          $w_html.=chr(13).'          <td>'.CRLF2BR(f($RS,'justificativa')).'</td></tr>';
        } 
      } 
    } 
    // Dados da conclusão da demanda, se ela estiver nessa situação
    if (Nvl(f($RS,'conclusao'),'')>'') {
      $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS DO ENCERRAMENTO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';   
      $w_html.=chr(13).'      <tr><td valign="top" colspan="2">';
      $w_html.=chr(13).'      <tr><td><font size="1"><b>Início da vigência:</b></td>';
      $w_html.=chr(13).'        <td>'.FormataDataEdicao(f($RS,'inicio_real')).'</td></tr>';
      $w_html.=chr(13).'      <tr><td><font size="1"><b>Término da vigência:</b></td>';
      $w_html.=chr(13).'        <td>'.FormataDataEdicao(f($RS,'fim_real')).'</td></tr>';
      if ($w_tipo_visao==0 && substr($w_sigla,0,3)!='GCA') {
        $w_html.=chr(13).'    <tr><td><font size="1"><b>Valor realizado:</b></td>';
        $w_html.=chr(13).'      <td>'.number_format(f($RS,'valor_atual'),2,',','.').'</td></tr>';
      } 
      if ($w_tipo_visao==0) {
        $w_html.=chr(13).'      <tr><td valign="top"><font size="1"><b>Nota de conclusão:</b></td>';
        $w_html.=chr(13).'          <td>'.nvl(CRLF2BR(f($RS,'observacao')),'---').'</td></tr>';
      } 
    } 
    // Exibe ficha completa
    if ($l_P1==4) {
      // Termo de referência
      $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>TERMO DE REFERÊNCIA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $w_html.=chr(13).'      <tr valign="top">';
      $w_html.=chr(13).'        <td ><font size="1"><b>Atividades a serem desenvolvidas:</b></td>';
      $w_html.=chr(13).'        <td>'.nvl(CRLF2BR(f($RS,'atividades')),'---').'</td></tr>';
      $w_html.=chr(13).'      <tr valign="top">';
      $w_html.=chr(13).'        <td><font size="1"><b>Produtos a serem entregues:</b></td>';
      $w_html.=chr(13).'        <td>'.nvl(CRLF2BR(f($RS,'produtos')),'---').'</td></tr>';
      $w_html.=chr(13).'      <tr valign="top">';
      $w_html.=chr(13).'        <td ><font size="1"><b>Qualificação exigida:</b></td>';
      $w_html.=chr(13).'        <td>'.nvl(CRLF2BR(f($RS,'requisitos')),'---').'</td></tr>';
      if (substr($w_sigla,0,3)=='GCB'){
        $w_html.=chr(13).'      <tr valign="top">';
        $w_html.=chr(13).'      <td><font size="1"><b>Código para o bolsista:</b></td>';
        $w_html.=chr(13).'      <td>'.Nvl(f($RS,'codigo_externo'),'---').'</td></tr>';
      } else {
        $w_html.=chr(13).'      <tr valign="top">';
        $w_html.=chr(13).'        <td><font size="1"><b>Código para a outra parte:</b></td>';
        $w_html.=chr(13).'        <td>'.Nvl(f($RS,'codigo_externo'),'---').'</td></tr>';
      }
      if ($w_tipo_visao!=2 && Nvl(f($RS,'cd_modalidade'),'')=='F') {
        $w_html.=chr(13).'      <tr><td><font size="1"><b>Pemite vinculação de projetos?</b></td>';
        $w_html.=chr(13).'        <td>'.f($RS,'nm_vincula_projeto').'</td></tr>';
        $w_html.=chr(13).'      <tr><td><font size="1"><b>Pemite vinculação de demandas?</b></td>';
        $w_html.=chr(13).'        <td>'.f($RS,'nm_vincula_demanda').'</td></tr>';
        $w_html.=chr(13).'       <tr><td><font size="1"><b>Pemite vinculação de viagens?</b></td>';
        $w_html.=chr(13).'        <td>'.f($RS,'nm_vincula_viagem').'</td></tr>';
      } 
    } 

    // Outra parte
    $RSQuery = db_getBenef::getInstanceOf($dbms,$w_cliente,Nvl(f($RS,'outra_parte'),0),null,null,null,Nvl(f($RS,'sq_tipo_pessoa'),0),null,null);
    if (substr($w_sigla,0,3)=='GCB')     $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>BOLSISTA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    else                            $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>OUTRA PARTE<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';

    if (count($RSQuery)==0) {
      if (substr($w_sigla,0,3)=='GCB')   $w_html.=chr(13).'      <tr><td colspan=2 align="center"><font size=1>Bolsista não informado';
      else                          $w_html.=chr(13).'      <tr><td colspan=2 align="center"><font size=1>Outra parte não informada';
    } else {
      foreach($RSQuery as $row) {
        if (substr($w_sigla,0,3)=='GCB')  $w_html.=chr(13).'      <tr><td><font size=1><b>Bolsista:</b></td>';     
        else                         $w_html.=chr(13).'      <tr><td><font size=1><b>Outra parte:</b></td>';
        $w_html.=chr(13).'                <td><b>'.f($row,'nm_pessoa').' ('.f($row,'nome_resumido').')</td></tr>';
        if (Nvl(f($RS,'sq_tipo_pessoa'),0)==1) {
          $w_html.=chr(13).'      <tr><td><font size=1><b>CPF:</b></td>';     
          $w_html.=chr(13).'          <td>'.f($row,'cpf').'</td></tr>';
        } else {
          $w_html.=chr(13).'      <tr><td><font size=1><b>CNPJ:</b></td>';     
          $w_html.=chr(13).'          <td>'.f($row,'cnpj').'</td></tr>';
        } 
        // Exibe ficha completa
        if ($l_P1==4) {
          if (f($RS,'sq_tipo_pessoa')==1) {
            $w_html.=chr(13).'      <tr><td colspan="2">';
            $w_html.=chr(13).'          <tr><td><font size="1"><b>Sexo:</b></td>'; 
            $w_html.=chr(13).'              <td>'.f($row,'nm_sexo').'</td></tr>';
            $w_html.=chr(13).'          <tr><td><font size="1"><b>Data de nascimento:</b></td>'; 
            $w_html.=chr(13).'              <td>'.FormataDataEdicao(Nvl(f($row,'nascimento'),'---')).'</td></tr>';
            $w_html.=chr(13).'          <tr><td><font size="1"><b>Identidade:</b></td>'; 
            $w_html.=chr(13).'              <td>'.f($row,'rg_numero').'</td></tr>';
            $w_html.=chr(13).'          <tr><td><font size="1"><b>Data de emissão:</b></td>'; 
            $w_html.=chr(13).'              <td>'.FormataDataEdicao(Nvl(f($row,'rg_emissao'),'---')).'</td>';
            $w_html.=chr(13).'          <tr><td><font size="1"><b>Órgão emissor:</b></td>'; 
            $w_html.=chr(13).'              <td>'.f($row,'rg_emissor').'</td></tr>';
            $w_html.=chr(13).'          <tr><td><font size="1"><b>Passaporte:</b></td>'; 
            $w_html.=chr(13).'              <td>'.Nvl(f($row,'passaporte_numero'),'---').'</td></tr>';
            $w_html.=chr(13).'          <tr><td><font size="1"><b>País emissor:</b></td>'; 
            $w_html.=chr(13).'              <td>'.Nvl(f($row,'nm_pais_passaporte'),'---').'</td></tr>';
          } else {
            $w_html.=chr(13).'      <tr><td><font size="1"><b>Inscrição estadual:</b></td>'; 
            $w_html.=chr(13).'          <td>'.Nvl(f($row,'inscricao_estadual'),'---').'</td></tr>';
          } 
          if (f($RS,'sq_tipo_pessoa')==1) {
            $w_html.=chr(13).'      <tr><td colspan="2" align="center" style="border: 1px solid rgb(0,0,0);"><font size="1"><b>Endereço comercial, Telefones e e-Mail</td>';
          } else {
            $w_html.=chr(13).'      <tr><td colspan="2" align="center" style="border: 1px solid rgb(0,0,0);"><font size="1"><b>Endereço principal, Telefones e e-Mail</td>';
          } 
          $w_html.=chr(13).'      <tr><td colspan="2">';
          $w_html.=chr(13).'          <tr valign="top">';
          $w_html.=chr(13).'            <td><font size="1"><b>Telefone:</b></td>'; 
          if (nvl(f($row,'ddd'),'nulo')!='nulo') {
            $w_html.=chr(13).'            <td>('.f($row,'ddd').') '.f($row,'nr_telefone').'</td></tr>';
          } else {
            $w_html.=chr(13).'            <td>---</td></tr>';
          }
          $w_html.=chr(13).'          <tr><td><font size="1"><b>Fax:</b></td>'; 
          $w_html.=chr(13).'            <td>'.Nvl(f($row,'nr_fax'),'---').'</td></tr>';
          $w_html.=chr(13).'          <tr><td><font size="1"><b>Celular:</b></td>'; 
          $w_html.=chr(13).'            <td>'.Nvl(f($row,'nr_celular'),'---').'</td></tr>';
          $w_html.=chr(13).'          <tr valign="top">';
          $w_html.=chr(13).'             <td><font size="1"><b>Endereço:</b></td>'; 
          $w_html.=chr(13).'            <td>'.f($row,'logradouro').'</td></tr>';
          $w_html.=chr(13).'          <tr><td><font size="1"><b>Complemento:</b></td>'; 
          $w_html.=chr(13).'            <td>'.Nvl(f($row,'complemento'),'---').'</td></tr>';
          $w_html.=chr(13).'          <tr><td><font size="1"><b>Bairro:</b></td>'; 
          $w_html.=chr(13).'            <td>'.Nvl(f($row,'bairro'),'---').'</td></tr>';
          $w_html.=chr(13).'          <tr valign="top">';
          if (f($row,'pd_pais')=='S') {
            $w_html.=chr(13).'          <td><font size="1"><b>Cidade:</b></td>'; 
            $w_html.=chr(13).'          <td>'.f($row,'nm_cidade').'-'.f($row,'co_uf').'</td></tr>';
          } else {
            $w_html.=chr(13).'          <td><font size="1"><b>Cidade:</b></td>'; 
            $w_html.=chr(13).'          <td>'.f($row,'nm_cidade').'-'.f($row,'nm_pais').'</td></tr>';
          } 
          $w_html.=chr(13).'          <tr><td><font size="1"><b>CEP:</b></td>'; 
          $w_html.=chr(13).'            <td>'.f($row,'cep').'</td></tr>';
          if (Nvl(f($row,'email'),'nulo')!='nulo') {
            if (!$l_P4==1) {
              $w_html.=chr(13).'              <tr><td><font size="1"><b>e-Mail:</b></td>';
              $w_html.=chr(13).'                <td><a class="hl" href="mailto:'.f($row,'email').'">'.f($row,'email').'</td></tr>';
            } else {
              $w_html.=chr(13).'              <tr><td><font size="1"><b>e-Mail:</b></td>';
              $w_html.=chr(13).'                <td>'.f($row,'email').'</td></tr>';
            } 
          } else {
            $w_html.=chr(13).'              <tr><td><font size="1"><b>e-Mail:</b></td>';
            $w_html.=chr(13).'                <td>---</td></tr>';
          }  
          if (substr($w_sigla,0,3)=='GCR') {
            $w_html.=chr(13).'      <tr><td colspan="2" align="center" style="border: 1px solid rgb(0,0,0);"><font size="1"><b>Dados para recebimento</td>';
            $w_html.=chr(13).'      <tr><td><font size="1"><b>Forma de recebimento:</b></td>';
            $w_html.=chr(13).'      <td>'.f($RS,'nm_forma_pagamento').'</td></tr>';
          } elseif (substr($w_sigla,0,3)=='GCD') {
            $w_html.=chr(13).'      <tr><td colspan="2" align="center" style="border: 1px solid rgb(0,0,0);"><font size="1"><b>Dados para pagamento</td>';
            $w_html.=chr(13).'      <tr><td><font size="1"><b>Forma de pagamento:</b></td>';
            $w_html.=chr(13).'      <td>'.f($RS,'nm_forma_pagamento').'</td></tr>';
          } else {
            $w_html.=chr(13).'      <tr><td colspan="2" align="center" style="border: 1px solid rgb(0,0,0);"><font size="1"><b>Dados para pagamento/recebimento</td>';
            $w_html.=chr(13).'      <tr><td><font size="1"><b>Forma de pagamento/recebimento:</b></td>';
            $w_html.=chr(13).'      <td>'.f($RS,'nm_forma_pagamento').'</td></tr>';
          } 
          if (substr($w_sigla,0,3)!='GCR') {
            $w_html.=chr(13).'      <tr><td colspan="2">';//<table border=0 width="100%" cellspacing=0>';
            if (!(strpos('CREDITO,DEPOSITO',f($RS,'sg_forma_pagamento'))===false)) {
              if (Nvl(f($RS,'cd_banco'),'')>'') {
                $w_html.=chr(13).'          <tr><td><font size="1"><b>Banco:</b></td>';
                $w_html.=chr(13).'                <td>'.f($RS,'cd_banco').' - '.f($RS,'nm_banco').'</td></tr>';
                $w_html.=chr(13).'          <tr><td><font size="1"><b>Agência:</b></td>';
                $w_html.=chr(13).'              <td>'.f($RS,'cd_agencia').' - '.f($RS,'nm_agencia').'</td></tr>';
                $w_html.=chr(13).'          <tr><td><font size="1"><b>Operação:</b></td>';
                $w_html.=chr(13).'              <td>'.Nvl(f($RS,'operacao_conta'),'---').'</td>';
                $w_html.=chr(13).'          <tr><td><font size="1"><b>Número da conta:</b></td>';
                $w_html.=chr(13).'              <td>'.Nvl(f($RS,'numero_conta'),'---').'</td></tr>';
              } else {
                $w_html.=chr(13).'          <tr><td><font size="1"><b>Banco:</b></td>';
                $w_html.=chr(13).'              <td>---</td></tr>';
                $w_html.=chr(13).'          <tr><td><font size="1"><b>Agência:</b></td>';
                $w_html.=chr(13).'              <td>---</td></tr>';
                $w_html.=chr(13).'          <tr><td><font size="1"><b>Operação:</b></td>';
                $w_html.=chr(13).'              <td>---</td></tr>';
                $w_html.=chr(13).'          <tr><td><font size="1"><b>Número da conta:</b></td>';
                $w_html.=chr(13).'              <td>---</td></tr>';
              } 
            } elseif (f($RS,'sg_forma_pagamento')=='ORDEM') {
              $w_html.=chr(13).'          <tr valign="top">';
              if (Nvl(f($RS,'cd_banco'),'')>'') {
                $w_html.=chr(13).'          <td><font size="1"><b>Banco:<b><br>'.f($RS,'cd_banco').' - '.f($RS,'nm_banco').'</td>';
                $w_html.=chr(13).'          <td><font size="1"><b>Agência:<b><br>'.f($RS,'cd_agencia').' - '.f($RS,'nm_agencia').'</td>';
              } else {
                $w_html.=chr(13).'          <td><font size="1"><b>Banco:<b><br>---</td>';
                $w_html.=chr(13).'          <td><font size="1"><b>Agência:<b><br>---</td>';
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
          } 
        } 
        break;
      } 
    }

    // Se outra parte for pessoa jurídica
    if (Nvl(f($RS,'sq_tipo_pessoa'),0)==2 && $l_P1==4) {
      if ($w_tipo_visao!=2) {
        // Preposto
        $RSQuery = db_getBenef::getInstanceOf($dbms,$w_cliente,Nvl(f($RS,'preposto'),0),null,null,null,null,null,null);
        $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>PREPOSTO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
        if (count($RSQuery)==0) {
          $w_html.=chr(13).'      <tr><td colspan=2 align="center"><font size=1>Preposto não informado';
        } else {
          foreach($RSQuery as $row) {
            $w_html.=chr(13).'      <tr><td><font size=1><b>Nome:</b></td>';
            $w_html.=chr(13).'            <td>'.f($row,'nm_pessoa').' ('.f($row,'nome_resumido').')</td></tr>';
            $w_html.=chr(13).'      <tr><td><font size=1><b>CPF:</b></td>';
            $w_html.=chr(13).'            <td>'.f($row,'cpf').'</td></tr>';
            // Exibe ficha completa
            if ($l_P1==4) {
              $w_html.=chr(13).'      <tr><td colspan="2">';//<table border=0 width="100%" cellspacing=0><tr valign="top">';
              $w_html.=chr(13).'          <tr><td><font size="1"><b>Sexo:</b></td>';
              $w_html.=chr(13).'            <td>'.f($row,'nm_sexo').'</td></tr>';
              $w_html.=chr(13).'          <tr><td><font size="1"><b>Identidade:</b></td>';
              $w_html.=chr(13).'            <td>'.f($row,'rg_numero').'</td></tr>';
              $w_html.=chr(13).'          <tr><td><font size="1"><b>Data de emissão:</b></td>';
              $w_html.=chr(13).'            <td>'.Nvl(FormataDataEdicao(f($row,'rg_emissao')),'---').'</td></tr>';
              $w_html.=chr(13).'          <tr><td><font size="1"><b>Órgão emissor:</b></td>';
              $w_html.=chr(13).'            <td>'.f($row,'rg_emissor').'</td></tr>';
            }
            break;
          }
        } 
      }
      // Representantes
      $RSQuery = db_getAcordoRep::getInstanceOf($dbms,f($RS,'sq_siw_solicitacao'),$w_cliente,null,null);
      $RSQuery = SortArray($RSQuery,'nm_pessoa','asc');
      $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>REPRESENTANTES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      if (count($RSQuery)==0) {
        $w_html.=chr(13).'      <tr><td colspan=2 align="center"><font size=1>Representantes não informados';
      } else {
        $w_html.=chr(13).'      <tr><td colspan="2"><div align="center">';
        $w_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
        $w_html.=chr(13).'          <tr align="center">';
        if ($w_tipo_visao!=2) $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>CPF</b></div></td>';
        $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Nome</b></div></td>';
        $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>DDD</b></div></td>';
        $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Telefone</b></div></td>';
        $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Fax</b></div></td>';
        $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Celular</b></div></td>';
        $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>e-Mail</b></div></td>';
        $w_html.=chr(13).'          </tr>';
        $w_cor=$w_TrBgColor;
        foreach($RSQuery as $row) {
          $w_html.=chr(13).'      <tr valign="top">';
          if ($w_tipo_visao!=2) $w_html.=chr(13).'        <td align="center"><font size="1">'.f($row,'cpf').'</td>';
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
      $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ALERTAS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $w_html.=chr(13).'      <tr><td width="30%"><b>Emite aviso:</b></td>';
      $w_html.=chr(13).'        <td>'.retornaSimNao(f($RS,'aviso_prox_conc')).', a partir de '.formataDataEdicao(f($RS,'aviso')).'.</td></tr>';
    } 
  } 

  // Parcelas
  $RS = db_getAcordoParcela::getInstanceOf($dbms,$l_chave,null,null,null,null,null,null,null,null);
  $RS = SortArray($RS,'ordem','asc');
  if (count($RS)>0) {
//  $w_html.=chr(13).'      <tr><td valign="top" colspan="2" align="center" bgcolor="#D0D0D0" style="border: 2px solid rgb(0,0,0);"><font size="1"><b>Parcelas</td>';
    $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>PARCELAS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $w_html.=chr(13).'      <tr><td colspan="2"><div align="center">';
    $w_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
    $w_html.=chr(13).'          <tr align="center">';
    $w_html.=chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><div><b>Ordem</b></div></td>';
    $w_html.=chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><div><b>Vencimento</b></div></td>';
    $w_html.=chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><div><b>Valor</b></div></td>';
    $w_html.=chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><div><b>Observações</b></div></td>';
    $w_html.=chr(13).'            <td colspan=4 bgColor="#f0f0f0"><div><b>Financeiro</b></div></td>';
    $w_html.=chr(13).'          </tr>';
    $w_html.=chr(13).'          <tr align="center">';
    $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Lançamento</b></div></td>';
    $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Vencimento</b></div></td>';
    $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Valor</b></div></td>';
    $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Quitação</b></div></td>';
    $w_html.=chr(13).'          </tr>';
    $w_cor=$w_TrBgColor;
    $w_total=0;
    foreach($RS as $row) {
      $w_html.=chr(13).'        <tr valign="top">';
      $w_html.=chr(13).'          <td align="center"><font size="1">';
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
        if (Nvl(f($row,'quitacao'),'nulo') <> 'nulo') $w_real += f($row,'vl_lancamento');
      } else {
        $w_html.=chr(13).'        <td align="center"><font size="1">---</td>';
        $w_html.=chr(13).'        <td align="center"><font size="1">---</td>';
        $w_html.=chr(13).'        <td align="center"><font size="1">---</td>';
      } 
      $w_html.=chr(13).'        <td align="center"><font size="1">'.Nvl(FormataDataEdicao(f($row,'quitacao')),'---').'</td>';
      $w_html.=chr(13).'      </tr>';
      $w_total += f($row,'valor');
    } 
    if ($w_total>0 || $w_real>0) {     
      $w_html.=chr(13).'      <tr valign="top">';
      $w_html.=chr(13).'        <td align="right" colspan=2><font size="1"><b>Previsto</b></td>';
      $w_html.=chr(13).'        <td align="right"><font size="1"><b>'.number_format($w_total,2,',','.').'</b></td>';
      if (round($w_valor_inicial-$w_total,2)!=0) {
        $w_html.=chr(13).'        <td colspan=2><font size=1><b>O valor das parcelas difere do valor contratado ('.number_format($w_valor_inicial-$w_total,2,',','.').')</b></td>';
      } else {
        $w_html.=chr(13).'        <td colspan=2>&nbsp;</td>';
      } 
      $w_html.=chr(13).'        <td align="right"><font size="1"><b>Liquidado</b></td>';
      $w_html.=chr(13).'        <td align="right"><font size="1"><b>'.number_format($w_real,2,',','.').'</b></td>';
      $w_html.=chr(13).'        <td>&nbsp;</td>';
      $w_html.=chr(13).'      </tr>';
    } 
    $w_html.=chr(13).'         </table></td></tr>';
  } 

  if ($w_tipo_visao!=2 && $l_P1==4 && ($l_O=='L' || $l_O=='V' || $l_O=='T')) {
    // Arquivos vinculados
    $RS = db_getSolicAnexo::getInstanceOf($dbms,$l_chave,null,$w_cliente);
    $RS = SortArray($RS,'nome','asc');
    if (count($RS)>0) {
      $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ARQUIVOS ANEXOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $w_html.=chr(13).'      <tr><td colspan="2"><div align="center">';
      $w_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
      $w_html.=chr(13).'          <tr align="center">';
      $w_html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Título</b></div></td>';
      $w_html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Descrição</b></div></td>';
      $w_html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Tipo</b></div></td>';
      $w_html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>KB</b></div></td>';
      $w_html.=chr(13).'          </tr>';
      $w_cor=$w_TrBgColor;
      foreach($RS as $row) {
        $w_html.=chr(13).'      <tr valign="top">';
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
  $w_erro = ValidaAcordo($w_cliente,$l_chave,substr($w_sigla,0,3).'GERAL',null,null,null,Nvl($w_tramite,0));
  if ($w_tipo_visao!=2 && $w_erro>'') {
    $w_html.=chr(13).'<tr><td colspan=2><font size=2>';
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
  if ($w_tipo_visao!=2 && $l_P1==4 && ($l_O=='L' || $l_O=='V' || $l_O=='T')) {
    // Encaminhamentos
    $RS = db_getSolicLog::getInstanceOf($dbms,$l_chave,null,'LISTA');
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
