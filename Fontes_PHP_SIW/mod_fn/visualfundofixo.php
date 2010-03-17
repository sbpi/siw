<?php
// =========================================================================
// Rotina de visualização dos dados do lançamento
// -------------------------------------------------------------------------
function VisualFundoFixo($v_chave,$l_O,$w_usuario,$l_P1,$l_tipo) {
  extract($GLOBALS);
  if ($l_tipo=='WORD') $w_TrBgColor=''; else $w_TrBgColor=$conTrBgColor;
  $l_html='';
  // Recupera os dados do lançamento
  $RS = db_getSolicData::getInstanceOf($dbms,$v_chave,$SG);
  $w_tramite       = f($RS,'sq_siw_tramite');
  $w_tramite_ativo = f($RS,'ativo');
  $w_SG            = f($RS,'sigla');
  $w_tipo_rubrica  = f($RS,'tipo_rubrica');
  $w_qtd_rubrica   = nvl(f($RS,'qtd_rubrica'),0);
  $w_sq_projeto    = nvl(f($RS,'sq_projeto'),0);
  // Recupera o tipo de visão do usuário
  if (Nvl(f($RS,'solicitante'),0)   == $w_usuario || 
      Nvl(f($RS,'executor'),0)      == $w_usuario || 
      Nvl(f($RS,'cadastrador'),0)   == $w_usuario || 
      Nvl(f($RS,'titular'),0)       == $w_usuario || 
      Nvl(f($RS,'substituto'),0)    == $w_usuario || 
      Nvl(f($RS,'tit_exec'),0)      == $w_usuario || 
      Nvl(f($RS,'subst_exec'),0)    == $w_usuario || 
      SolicAcesso($v_chave,$w_usuario)>=8) {
    // Se for solicitante, executor ou cadastrador, tem visão completa
    $w_tipo_visao=0;
  } else {
    if (SolicAcesso($v_chave,$w_usuario)>2) $w_tipo_visao = 1;
  } 
  // Se for listagem ou envio, exibe os dados de identificação do lançamento
  if ($l_O=='L' || $l_O=='V') {
    // Se for listagem dos dados
    $l_html.=chr(13).'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
    $l_html.=chr(13).'<tr><td>';
    $l_html.=chr(13).'    <table width="99%" border="0">';
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    $l_html.=chr(13).'      <tr><td colspan="2" bgcolor="#f0f0f0"><font size="2"><b>'.upper(f($RS,'nome')).' '.f($RS,'codigo_interno').' ('.$v_chave.')</b></td>';
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
     
    // Identificação do lançamento
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>IDENTIFICAÇÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';      // Identificação do lançamento
    $l_html .= chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
    
    // Verifica o segmento do cliente    
    $RS1 = db_getCustomerData::getInstanceOf($dbms,$w_cliente); 
    $w_segmento = f($RS1,'segmento');
    if ($w_mod_pa=='S' && nvl(f($RS,'processo'),'')!='') {
      if ((!($l_P1==4 || $l_tipo=='WORD')) && nvl(f($RS,'protocolo_siw'),'')!='') {
        $l_html.=chr(13).'      <tr><td><b>Número do processo: </b></td><td><A class="HL" HREF="mod_pa/documento.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($RS,'protocolo_siw').'&w_tipo=&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=PADGERAL'.MontaFiltro('GET').'" title="Exibe as informações deste registro." target="processo">'.f($RS,'processo').'&nbsp;</a>';
      } else {
        $l_html.=chr(13).'      <tr><td><b>Número do processo: </b></td><td>'.f($RS,'processo');
      }
    } elseif ($w_segmento=='Público') { 
      $l_html.=chr(13).'      <tr><td><b>Número do processo: </b></td>';
      $l_html.=chr(13).'        <td>'.nvl(f($RS,'processo'),'---').' </td></tr>';
    }   
    
    if (Nvl(f($RS,'cd_acordo'),'')>'') {
      if (!($l_P1==4 || $l_tipo=='WORD')) {
        $l_html.=chr(13).'      <tr><td width="30%"><b>Contrato: </b></td>';
        $l_html.=chr(13).'        <td><A class="hl" HREF="mod_ac/contratos.php?par=Visual&O=L&w_chave='.f($RS,'sq_solic_pai').'&w_tipo=&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$l_tipo.'&TP='.$TP.'&SG=GC'.substr($SG,2,1).'CAD" title="Exibe as informações do contrato." target="Contrato">'.f($RS,'cd_acordo').' ('.f($RS,'sq_solic_pai').')</a> </td></tr>';
      } else {
        $l_html.=chr(13).'      <tr><td><b>Contrato: </b></td><td>'.f($RS,'cd_acordo').' ('.f($RS,'sq_solic_pai').') </td></tr>';
      }
    } else {
      $l_html.=chr(13).'      <tr><td width="30%"><b>Vinculação: </b></td>';
      if (Nvl(f($RS,'dados_pai'),'')!='') {
        $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS,'sq_solic_pai'),f($RS,'dados_pai'),'N',$l_tipo).'</td>';
      } else {
        $l_html.=chr(13).'        <td>---</td>';
      }
    } 
    if (f($RS_Menu,'sigla')=='FNDVIA' || f($RS_Menu,'sigla')=='FNREVENT') {
      $l_html.=chr(13).'      <tr><td width="30%"><b>Projeto: </b></td>';
      if (Nvl(f($RS,'dados_avo'),'')!='') {
        $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS,'sq_solic_avo'),f($RS,'dados_avo'),'N',$l_tipo).'</td>';
      } else {
        $l_html.=chr(13).'        <td>---</td>';
      }
    }
/*
    if (Nvl(f($RS,'nm_projeto'),'') > '') {
      if (!($l_P1==4 || $l_tipo=='WORD')){
        $l_html.=chr(13).'      <tr><td width="30%"><b>Projeto: </b></td><td><A class="hl" HREF="projeto.php?par=Visual&O=L&w_chave='.f($RS,'sq_projeto').'&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$l_tipo.'&TP='.$TP.'&SG='.$SG.'" title="Exibe as informações do projeto." target="Projeto">'.f($RS,'nm_projeto').'</a></td></tr>';
      } else {
        $l_html.=chr(13).'      <tr><td><b>Projeto: </b></td><td>'.f($RS,'nm_projeto').'  ('.f($RS,'sq_solic_pai').')</td></tr>';
      }
    } 
    // Se a classificação foi informada, exibe.
    if (Nvl(f($RS,'sq_cc'),'')>'') {
      $l_html.=chr(13).'      <tr><td width="30%"><b>Classificação: </b></td><td>'.f($RS,'nm_cc').' </td></tr>';
    }
*/
    $l_html.=chr(13).'      <tr><td width="30%"><b>Tipo de lançamento: </b></td><td>'.f($RS,'nm_tipo_lancamento').' </td></tr>';
    if (Nvl(f($RS,'tipo_rubrica'),'')!='') {
      $l_html.=chr(13).'      <tr><td><b>Tipo de movimentação: </b></td>';
      $l_html.=chr(13).'        <td>'.f($RS,'nm_tipo_rubrica').' </td></tr>';
      $l_html.=chr(13).'      <tr><td><b>Finalidade: </b></td>';
      $l_html.=chr(13).'        <td>'.CRLF2BR(f($RS,'descricao')).'</td></tr>';
    }
    if (!($l_P1==4 || $l_tipo=='WORD')){
      $l_html.=chr(13).'      <tr><td><b>Unidade responsável: </b></td>';
      $l_html.=chr(13).'        <td>'.ExibeUnidade($w_dir_volta,$w_cliente,f($RS,'nm_unidade_resp'),f($RS,'sq_unidade'),$TP).'</td></tr>';
    } else {
      $l_html.=chr(13).'      <tr><td><b>Unidade responsável: </b></td>';
      $l_html.=chr(13).'        <td>'.f($RS,'nm_unidade_resp').'</td></tr>';
    }
    $l_html.=chr(13).'          <tr><td><b>Forma de pagamento:</b></td>';
    $l_html.=chr(13).'            <td>'.f($RS,'nm_forma_pagamento').' </td></tr>';
    $l_html.=chr(13).'          <tr><td><b>Vencimennto:</b></td>';
    $l_html.=chr(13).'            <td>'.FormataDataEdicao(f($RS,'vencimento')).' </td></tr>';
    $l_html.=chr(13).'          <tr><td><b>Valor:</b></td>';
    $l_html.=chr(13).'            <td>'.formatNumber(Nvl(f($RS,'valor'),0)).' </td></tr>';
    $w_inicial = f($RS,'valor');
    
    // Dados da conclusão do projeto, se ela estiver nessa situação
    if (Nvl(f($RS,'conclusao'),'')>'' && Nvl(f($RS,'quitacao'),'')>'') {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS DA PRESTAÇÃO DE CONTAS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html.=chr(13).'      <tr><td><b>Data da prestação de contas:</b></td><td>'.FormataDataEdicao(f($RS,'quitacao')).' </td></tr>';
      if (Nvl(f($RS,'codigo_deposito'),'')>''){
        $l_html.=chr(13).'    <tr><td><b>Código do depósito:</b></td><td>'.f($RS,'codigo_deposito').' </td></tr>';
      }
      $l_html.=chr(13).'      <tr valign="top"><td><b>Observação:</b></td>';
      $l_html.=chr(13).'      <td>'.CRLF2BR(Nvl(f($RS,'observacao'),'---')).' </td></tr>';
    } 

    // Outra parte
    $RS_Query = db_getBenef::getInstanceOf($dbms,$w_cliente,Nvl(f($RS,'pessoa'),0),null,null,null,null,Nvl(f($RS,'sq_tipo_pessoa'),0),null,null,null,null,null,null,null);
    foreach ($RS_Query as $row) {$RS_Query = $row; break;}
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>SUPRIDO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    if (count($RS_Query)<=0) {
      $l_html.=chr(13).'      <tr><td colspan=2 align=center><font size=1>Suprido não informado';
    } else {
      $l_html.=chr(13).'      <tr><td colspan=2 bgColor="#f0f0f0"style="border: 1px solid rgb(0,0,0);" ><b>';
      $l_html.=chr(13).'          '.f($RS_Query,'nm_pessoa').' ('.f($RS_Query,'nome_resumido').') - ';
      if (Nvl(f($RS,'sq_tipo_pessoa'),0)==1) {
        $l_html.=chr(13).'      '.f($row,'cpf').'</b></td></tr>';
      } else {
        $l_html.=chr(13).'      '.f($row,'cnpj').'</b></td></tr>';
      } 
      if (f($RS,'sq_tipo_pessoa')==1) {
        $l_html.=chr(13).'      <tr><td><b>Sexo:</b></td><td>'.f($RS_Query,'nm_sexo').'</td></tr>';
        $l_html.=chr(13).'      <tr><td><b>Data de nascimento:</b></td><td>'.Nvl(FormataDataEdicao(f($RS_Query,'nascimento')),'---').'</td></tr>';
        $l_html.=chr(13).'      <tr><td><b>Identidade:</b></td><td>'.f($RS_Query,'rg_numero').'</td></tr>';
        $l_html.=chr(13).'      <tr><td><b>Data de emissão:</b></td><td>'.FormataDataEdicao(Nvl(f($RS_Query,'rg_emissao'),'---')).'</td></tr>';
        $l_html.=chr(13).'      <tr><td><b>Órgão emissor:</b></td><td>'.f($RS_Query,'rg_emissor').'</td></tr>';
        $l_html.=chr(13).'      <tr><td><b>Passaporte:</b></td><td>'.Nvl(f($RS_Query,'passaporte_numero'),'---').'</td></tr>';
        $l_html.=chr(13).'      <tr><td><b>País emissor:</b></td><td>'.Nvl(f($RS_Query,'nm_pais_passaporte'),'---').'</td></tr>';
      } else {
        $l_html.=chr(13).'      <tr><td><b>Inscrição estadual:</b></td>';
        $l_html.=chr(13).'           <td>'.Nvl(f($RS_Query,'inscricao_estadual'),'---').'</td></tr>';
      } 
      if (f($RS,'sq_tipo_pessoa')==1) {
        $l_html.=chr(13).'      <tr><td colspan=2 style="border: 1px solid rgb(0,0,0);"><b>Endereço comercial, Telefones e e-Mail</td>';
      } else {
        $l_html.=chr(13).'      <tr><td colspan=2 align="center" style="border: 1px solid rgb(0,0,0);"><b>Endereço principal, Telefones e e-Mail</td>';
      }
      $l_html.=chr(13).'      <tr><td width="30%"><b>Telefone:</b></td><td>('.f($row,'ddd').') '.f($row,'nr_telefone').'</td></tr>';
      $l_html.=chr(13).'      <tr><td><b>Fax:</b></td><td>'.Nvl(f($row,'nr_fax'),'---').'</td></tr>';
      $l_html.=chr(13).'      <tr><td><b>Celular:</b></td><td>'.Nvl(f($row,'nr_celular'),'---').'</td></tr>';
      $l_html.=chr(13).'      <tr><td><b>Endereço:</b></td><td>'.f($row,'logradouro').'</td></tr>';
      $l_html.=chr(13).'      <tr><td><b>Complemento:</b></td><td>'.Nvl(f($row,'complemento'),'---').'</td></tr>';
      $l_html.=chr(13).'      <tr><td><b>Bairro:</b></td><td>'.Nvl(f($row,'bairro'),'---').'</td></tr>';
      if (f($row,'pd_pais')=='S') {
        $l_html.=chr(13).'      <tr><td><b>Cidade:</b></td><td>'.f($row,'nm_cidade').'-'.f($row,'co_uf').'</td></tr>';
      } else {
        $l_html.=chr(13).'      <tr><td><b>Cidade:</b></td><td>'.f($row,'nm_cidade').'-'.f($row,'nm_pais').'</td></tr>';
      } 
      $l_html.=chr(13).'      <tr><td><b>CEP:</b></td><td>'.f($row,'cep').'</td></tr>';        
      if (Nvl(f($row,'email'),'nulo')!='nulo') {
        if (!$l_tipo=='WORD') {
          $l_html.=chr(13).'      <tr><td><b>e-Mail:</b></td><td><a class="hl" href="mailto:'.f($row,'email').'">'.f($row,'email').'</td></tr>';
        } else {
          $l_html.=chr(13).'      <tr><td><b>e-Mail:</b></td><td>'.f($row,'email').'</td></tr>';
        } 
      } else {
        $l_html.=chr(13).'      <tr><td><b>e-Mail:</b></td><td>---</td></tr>';
      } 
      $l_html.=chr(13).'      <tr><td colspan=2 style="border: 1px solid rgb(0,0,0);"><b>Dados para pagamento</td>';
      $l_html.=chr(13).'      <tr><td><b>Forma de pagamento:</b></td><td>'.f($RS,'nm_forma_pagamento').'</td></tr>';
      if (substr($w_SG,0,3)!='FNR' || Nvl(f($RS,'numero_conta'),'')!='') {
        if (!(strpos('CREDITO,DEPOSITO',f($RS,'sg_forma_pagamento'))===false)) {
          if (Nvl(f($RS,'cd_banco'),'')>'') {
            $l_html.=chr(13).'          <tr><td><b>Banco:</b></td>';
            $l_html.=chr(13).'                <td>'.f($RS,'cd_banco').' - '.f($RS,'nm_banco').'</td></tr>';
            $l_html.=chr(13).'          <tr><td><b>Agência:</b></td>';
            $l_html.=chr(13).'              <td>'.f($RS,'cd_agencia').' - '.f($RS,'nm_agencia').'</td></tr>';
            if (f($RS,'exige_operacao')=='S') $l_html.=chr(13).'          <tr><td><b>Operação:</b></td><td>'.Nvl(f($RS,'operacao_conta'),'---').'</td>';
            $l_html.=chr(13).'          <tr><td><b>Número da conta:</b></td>';
            $l_html.=chr(13).'              <td>'.Nvl(f($RS,'numero_conta'),'---').'</td></tr>';
          } else {
            $l_html.=chr(13).'          <tr><td><b>Banco:</b></td>';
            $l_html.=chr(13).'              <td>---</td></tr>';
            $l_html.=chr(13).'          <tr><td><b>Agência:</b></td>';
            $l_html.=chr(13).'              <td>---</td></tr>';
            if (f($RS,'exige_operacao')=='S') $l_html.=chr(13).'          <tr><td><b>Operação:</b></td><td>---</td></tr>';
            $l_html.=chr(13).'          <tr><td><b>Número da conta:</b></td>';
            $l_html.=chr(13).'              <td>---</td></tr>';
          }
        } elseif (f($RS,'sg_forma_pagamento')=='CHEQUE') {
          if (Nvl(f($RS,'sq_pessoa_conta'),'')>'') {
            $l_html.=chr(13).'          <tr><td><b>Banco:</b></td>';
            $l_html.=chr(13).'                <td>'.f($RS,'cd_banco').' - '.f($RS,'nm_banco').'</td></tr>';
            $l_html.=chr(13).'          <tr><td><b>Agência:</b></td>';
            $l_html.=chr(13).'              <td>'.f($RS,'cd_agencia').' - '.f($RS,'nm_agencia').'</td></tr>';
            $l_html.=chr(13).'          <tr><td><b>Número da conta:</b></td>';
            $l_html.=chr(13).'              <td>'.Nvl(f($RS,'nr_conta_org'),'---').'</td></tr>';
            $l_html.=chr(13).'          <tr><td><b>Número do cheque:</b></td>';
            $l_html.=chr(13).'              <td>'.Nvl(f($RS,'numero_conta'),'---').'</td></tr>';
          } else {
            $l_html.=chr(13).'          <tr><td><b>Banco:</b></td>';
            $l_html.=chr(13).'              <td>---</td></tr>';
            $l_html.=chr(13).'          <tr><td><b>Agência:</b></td>';
            $l_html.=chr(13).'              <td>---</td></tr>';
            $l_html.=chr(13).'          <tr><td><b>Número da conta:</b></td>';
            $l_html.=chr(13).'              <td>---</td></tr>';
            $l_html.=chr(13).'          <tr><td><b>Número do cheque:</b></td>';
            $l_html.=chr(13).'              <td>---</td></tr>';
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
      } 
    } 
    $w_vl_retencao    = Nvl(f($RS,'valor_retencao'),0);
    $w_vl_normal      = Nvl(f($RS,'valor_imposto'),0);
    $w_vl_total       = Nvl(f($RS,'valor_doc'),0);
    $w_valor          = Nvl(f($RS,'valor_liquido'),0);
  }
    
  // Pagamentos vinculados
  $RS1 = db_getLinkData::getInstanceOf($dbms,$w_cliente,'FNDFUNDO');
  $RS1 = db_getSolicList::getInstanceOf($dbms,f($RS1,'sq_menu'),$w_usuario,f($RS1,'sigla'),4,
         null,null,null,null,null,null,null,null,null,null,null,null,null,null,
         null,null,null,null,null,null,null,null,$v_chave,null,null,null);
  $RS1 = SortArray($RS1,'fim','asc');
  if (count($RS1)>0) {
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>LANÇAMENTOS VINCULADOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
    $l_html.=chr(13).'      <tr><td colspan="2" align="center"><table width=100%  border="1" bordercolor="#00000">';
    $l_html.=chr(13).'          <tr bgcolor="'.$conTrBgColor.'" align="center">';
    $l_html.=chr(13).'            <td rowspan="2"><b>Código</td>';
    $l_html.=chr(13).'            <td colspan="3"><b>Comprovante</td>';
    $l_html.=chr(13).'            <td rowspan="2"><b>Pessoa</td>';
    $l_html.=chr(13).'            <td rowspan="2"><b>Finalidade</td>';
    $l_html.=chr(13).'            <td rowspan="2"><b>Crédito</td>';
    $l_html.=chr(13).'            <td rowspan="2"><b>Débito</td>';
    $l_html.=chr(13).'            <td rowspan="2"><b>Saldo</td>';
    $l_html.=chr(13).'          </tr>';
    $l_html.=chr(13).'          <tr bgcolor="'.$conTrBgColor.'" align="center">';
    $l_html.=chr(13).'            <td><b>Data</td>';
    $l_html.=chr(13).'            <td><b>Tipo</td>';
    $l_html.=chr(13).'            <td><b>Número</td>';
    $l_html.=chr(13).'          </tr>';
    $w_cor=$w_TrBgColor;
    $w_atual = $w_inicial;
    $w_total = 0;
    $i       = 0;
    foreach ($RS1 as $row) {
      if ($i==0) {
        $l_html.=chr(13).'      <tr valign="top">';
        $l_html.=chr(13).'        <td align="center">'.ExibeImagemSolic(f($RS,'sigla'),f($RS,'inicio'),f($RS,'vencimento'),f($RS,'inicio'),f($RS,'quitacao'),f($RS,'aviso_prox_conc'),f($RS,'aviso'),f($RS,'sg_tramite'), null).' '.f($RS,'codigo_interno').'</td>';
        $l_html.=chr(13).'        <td align="center">&nbsp;'.Nvl(FormataDataEdicao(f($RS,'inicio'),5),'-').'</td>';
        $l_html.=chr(13).'        <td>'.f($RS,'nm_forma_pagamento').'</td>';
        $l_html.=chr(13).'        <td>'.nvl(f($RS,'numero_conta'),'&nbsp;').'</td>';
        $l_html.=chr(13).'        <td colspan="2">'.f($RS,'nm_banco').'&nbsp;</td>';
        $l_html.=chr(13).'        <td align="right">'.formatNumber(f($RS,'valor')).'</td>';
        $l_html.=chr(13).'        <td align="right">&nbsp;</td>';
        $l_html.=chr(13).'        <td align="right">'.formatNumber(f($RS,'valor')).'</td>';
      }
      // Recupera o comprovante ligado ao pagamento.
      // Pagamentos de fundo fixo só podem ter um comprovante ligados a eles
      $RS2 = db_getLancamentoDoc::getInstanceOf($dbms,f($row,'sq_siw_solicitacao'),null,'DOCS');
      $RS2 = SortArray($RS2,'data','asc');
      foreach($RS2 as $row2) { $RS2 = $row2; break; }
      $l_html.=chr(13).'      <tr valign="top">';
      $l_html.=chr(13).'        <td align="center">'.ExibeImagemSolic(f($row,'sigla'),f($row,'inicio'),f($row,'vencimento'),f($row,'inicio'),f($row,'quitacao'),f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null);
      if ($w_tipo!='WORD') $l_html.=chr(13).'        <A class="hl" HREF="'.$w_dir.'lancamento.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="'.f($row,'obj_acordo').' ::> '.f($row,'descricao').'">'.f($row,'codigo_interno').'&nbsp;</a>';
      else                 $l_html.=chr(13).'        '.f($row,'codigo_interno').'';
      $l_html.=chr(13).'        <td align="center">&nbsp;'.Nvl(FormataDataEdicao(f($RS2,'data'),5),'-').'</td>';
      $l_html.=chr(13).'        <td>'.f($RS2,'nm_tipo_documento').'</td>';
      $l_html.=chr(13).'        <td>'.f($RS2,'numero').'</td>';
      if (Nvl(f($row,'pessoa'),'nulo')!='nulo') {
        if ($w_tipo!='WORD') $l_html.=chr(13).'        <td>'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'pessoa'),$TP,f($row,'nm_pessoa_resumido')).'</td>';
        else                 $l_html.=chr(13).'        <td>'.f($row,'nm_pessoa_resumido').'</td>';
      } else {
        $l_html.=chr(13).'        <td align="center">---</td>';
      }
      $l_html.=chr(13).'        <td>'.f($row,'descricao').'</td>';
      $l_html.=chr(13).'        <td align="right">&nbsp;</td>';
      $l_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'valor')).'</td>';
      $w_total += Nvl(f($row,'valor'),0);
      $w_atual -= Nvl(f($row,'valor'),0);
      $l_html.=chr(13).'        <td align="right">'.formatNumber($w_atual).'</td>';
      $i++;
    } 
    $l_html.=chr(13).'      <tr valign="top" bgcolor="'.$conTrBgColor.'">';
    $l_html.=chr(13).'        <td align="right" colspan=7><b>Total das despesas</b></td>';
    $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_total).'</b></td>';
    $l_html.=chr(13).'        <td align="right"><b>&nbsp;</b></td>';
    $l_html.=chr(13).'      </tr>';
    $l_html.=chr(13).'      </table></td></tr>';
  } 

  // Arquivos vinculados
  $RS = db_getSolicAnexo::getInstanceOf($dbms,$v_chave,null,$w_cliente);
  $RS = SortArray($RS,'nome','asc');
  if (count($RS)>0) {
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ARQUIVOS ANEXOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $l_html.=chr(13).'      <tr><td align="center">';
    $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
    $l_html.=chr(13).'          <tr align="center">';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Título</b></td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Descrição</b></td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Tipo</b></td>';
    $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>KB</b></td>';
    $l_html.=chr(13).'          </tr>';
    $w_cor=$w_TrBgColor;
    foreach($RS as $row) {
      $l_html.=chr(13).'      <tr valign="top">';
      if (!($l_tipo=='WORD'))
        $l_html.=chr(13).'        <td>'.LinkArquivo('HL',$w_cliente,f($row,'chave_aux'),'_blank','Clique para exibir o arquivo em outra janela.',f($row,'nome'),null).'</td>';
      else
        $l_html.=chr(13).'        <td>'.f($row,'nome').'</td>';
      $l_html.=chr(13).'        <td>'.Nvl(f($row,'descricao'),'---').'</td>';
      $l_html.=chr(13).'        <td>'.f($row,'tipo').'</td>';
      $l_html.=chr(13).'        <td align="right">'.round(f($row,'tamanho')/1024,1).'&nbsp;</td>';
      $l_html.=chr(13).'      </tr>';
    } 
    $l_html.=chr(13).'         </table></td></tr>';
  } 
  // Se for envio, executa verificações nos dados da solicitação
  $w_erro=ValidaFundoFixo($w_cliente,$v_chave,substr($w_SG,0,3).'GERAL',null,null,null,Nvl($w_tramite,0));
  if ($w_erro>'') {
    $l_html.=chr(13).'<tr><td colspan=2><font size=2>';
    $l_html.=chr(13).'<HR>';
    if (substr($w_erro,0,1)=='0') {
      $l_html.=chr(13).'  <font color="#BC3131"><b>ATENÇÃO:</b> Foram identificados os erros listados abaixo, não sendo possível seu encaminhamento para fases posteriores à atual, nem seu pagamento.';
    }elseif (substr($w_erro,0,1)=='1') {
      $l_html.=chr(13).'  <font color="#BC3131"><b>ATENÇÃO:</b> Foram identificados os erros listados abaixo. Seu encaminhamento para fases posteriores à atual só pode ser feito por um gestor do sistema ou do módulo de projetos.';
    } else {
      $l_html.=chr(13).'  <font color="#BC3131"><b>ATENÇÃO:</b> Foram identificados os alertas listados abaixo. Eles não impedem o encaminhamento para fases posteriores à atual, mas convém sua verificação.';
    } 
    $l_html.=chr(13).'  <ul>'.substr($w_erro,1,1000).'</ul>';
    $l_html.=chr(13).'  </td></tr>';
  } 
  // Encaminhamentos
  include_once($w_dir_volta.'funcoes/exibeLog.php');
  $l_html .= exibeLog($v_chave,$l_O,$l_usuario,$w_tramite_ativo,(($l_tipo=='WORD') ? 'WORD' : 'HTML'));
  $l_html.=chr(13).'    </table>';
  $l_html.=chr(13).'</table>';
  return $l_html;
} 
function rubricalinha($v_RS3){
  extract($GLOBALS);
  $v_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
  $v_html.=chr(13).'          <tr align="center">';
  $v_html.=chr(13).'          <td width="6%" bgColor="#f0f0f0"><b>Ordem</b></td>';
  $v_html.=chr(13).'          <td width="12%" bgColor="#f0f0f0"><b>Rubrica</b></td>';
  $v_html.=chr(13).'          <td width="45%" bgColor="#f0f0f0"><b>Descrição</b></td>';
  $v_html.=chr(13).'          <td width="7%"  bgColor="#f0f0f0"><b>Qtd</b></td>';
  if (strpos(f($RS_Menu,'sigla'),'VIA')!==false) {
    $v_html.=chr(13).'          <td width="7%"  bgColor="#f0f0f0"><b>Data cotação</b></td>';
    $v_html.=chr(13).'          <td width="7%"  bgColor="#f0f0f0"><b>Valor cotação</b></td>';
  }
  $v_html.=chr(13).'          <td width="15%" bgColor="#f0f0f0"><b>$ Unit</b></td>';
  $v_html.=chr(13).'          <td width="15%" bgColor="#f0f0f0"><b>$ Total</b></td>';
  $v_html.=chr(13).'        </tr>';
  foreach($v_RS3 as $row) {
    $v_html.=chr(13).'      <tr valign="top">';
    $v_html.=chr(13).'        <td align="center">'.f($row,'ordem').'</td>';
    if(nvl(f($row,'codigo_rubrica'),'')>'')
      $v_html.=chr(13).'        <td align="center"><A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.'mod_fn/lancamento.php?par=Ficharubrica&O=L&w_sq_projeto_rubrica='.f($row,'sq_projeto_rubrica').'&w_tipo=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Extrato Rubrica'.'&SG='.$SG.MontaFiltro('GET')).'\',\'Ficha2\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Exibe as informações deste registro.">'.f($row,'codigo_rubrica').' - '.f($row,'nm_rubrica').'</A>&nbsp</td>';
    else
      $v_html.=chr(13).'        <td align="center">???</td>';
    $v_html.=chr(13).'        <td>'.f($row,'descricao').'</td>';
    $v_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'quantidade'),0).'</td>';
    if (strpos(f($RS_Menu,'sigla'),'VIA')!==false) {
      $v_html.=chr(13).'        <td align="center">'.nvl(formataDataEdicao(f($row,'data_cotacao')),'&nbsp;').'</td>';
      $v_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'valor_cotacao'),4).'&nbsp;</td>';
    }
    $v_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'valor_unitario')).'&nbsp;</td>';
    $v_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'valor_total')).'&nbsp;</td>';
    $v_html.=chr(13).'      </tr>';
    $w_total += f($row,'valor_total');
  } 
  if ($w_total>0) {
    $v_html.=chr(13).'      <tr valign="top">';
    $v_html.=chr(13).'        <td align="right" colspan="'.((strpos(f($RS_Menu,'sigla'),'VIA')!==false) ? 7 : 5).'"><b>Total</b></td>';
    $v_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_total).'</b>&nbsp;</td>';
    $v_html.=chr(13).'      </tr>';
  }
  $v_html.=chr(13).'    </table>';
  return $v_html;
}
?>