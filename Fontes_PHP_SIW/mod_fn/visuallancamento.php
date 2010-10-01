<?php
// =========================================================================
// Rotina de visualização dos dados do lançamento
// -------------------------------------------------------------------------
function VisualLancamento($v_chave,$l_O,$w_usuario,$l_P1,$l_tipo) {
  extract($GLOBALS);
  if ($l_tipo=='WORD') $w_TrBgColor=''; else $w_TrBgColor=$conTrBgColor;
  $l_html='';
  // Recupera os dados do lançamento
  $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$v_chave,substr($SG,0,3).'GERAL');
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
    $sql = new db_getCustomerData; $RS1 = $sql->getInstanceOf($dbms,$w_cliente); 
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
    } elseif (f($RS_Menu,'sigla')=='FNDFUNDO') {
      // Recupera dados do fundo fixo
      $sql = new db_getSolicData; $RS_Solic = $sql->getInstanceOf($dbms,f($RS,'sq_solic_pai'),'FNDFIXO');

      $l_html.=chr(13).'      <tr><td width="30%"><b>Fundo fixo: </b></td>';
      if (Nvl(f($RS,'dados_pai'),'')!='') {
        $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS,'sq_solic_pai'),f($RS,'dados_pai'),'N',$l_tipo);
        if ($w_mod_pa=='S') $l_html.=' (Processo: '.f($RS_Solic,'processo').')</td>';
      } else {
        $l_html.=chr(13).'        <td>---</td>';
      }
      if (nvl(f($RS,'sq_solic_vinculo'),'')!='') {
        // Recupera dados da solicitação de compra
        $sql = new db_getLinkData; $RS_Vinculo = $sql->getInstanceOf($dbms,$w_cliente,'CLPCCAD');
        $sql = new db_getSolicCL; $RS_Vinculo = $sql->getInstanceOf($dbms,f($RS_Vinculo,'sq_menu'),$w_usuario,f($RS_Vinculo,'sigla'),3,
            null,null,null,null,null,null,null,null,null,null,f($RS,'sq_solic_vinculo'), null, null, null, null, null, null,
            null, null, null, null, null, null, null,null, null, null, null);
        foreach($RS_Vinculo as $row) { $RS_Vinculo = $row; break; }
        $l_html.=chr(13).'      <tr><td width="30%"><b>'.f($RS_Vinculo,'nome').': </b></td>';
        $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS_Vinculo,'sq_siw_solicitacao'),f($RS_Vinculo,'codigo_interno'),'N',$l_tipo).'</td>';
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
    if (f($RS_Menu,'sigla')!='FNDREEMB' || (f($RS_Menu,'sigla')=='FNDREEMB' && f($RS,'or_tramite')>2)) {
      $l_html.=chr(13).'      <tr><td width="30%"><b>Tipo de lançamento: </b></td><td>'.f($RS,'nm_tipo_lancamento').' </td></tr>';
    }
    if (f($RS_Menu,'sigla')!='FNDREEMB') {
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
    } else {
      $l_html.=chr(13).'      <tr><td><b>Justificativa: </b></td>';
      $l_html.=chr(13).'        <td>'.CRLF2BR(f($RS,'descricao')).'</td></tr>';
      if (!($l_P1==4 || $l_tipo=='WORD')){
        $l_html.=chr(13).'      <tr><td><b>Unidade solicitante: </b></td>';
        $l_html.=chr(13).'        <td>'.ExibeUnidade($w_dir_volta,$w_cliente,f($RS,'nm_unidade_resp'),f($RS,'sq_unidade'),$TP).'</td></tr>';
      } else {
        $l_html.=chr(13).'      <tr><td><b>Unidade solicitante: </b></td>';
        $l_html.=chr(13).'        <td>'.f($RS,'nm_unidade_resp').'</td></tr>';
      }
    }
    if (substr($w_SG,0,3)=='FNR') {
      if (f($RS,'sg_forma_pagamento')!='ESPECIE') $l_html.=chr(13).'      <tr><td colspan=2 style="border: 1px solid rgb(0,0,0);"><b>Dados para recebimento</td>';
      $l_html.=chr(13).'      <tr><td><b>Forma de recebimento:</b></td><td>'.f($RS,'nm_forma_pagamento').'</td></tr>';
    } elseif (substr($w_SG,0,3)=='FND') {
      if (f($RS,'sg_forma_pagamento')!='ESPECIE') $l_html.=chr(13).'      <tr><td colspan=2 style="border: 1px solid rgb(0,0,0);"><b>Dados para pagamento</td>';
      $l_html.=chr(13).'      <tr><td><b>Forma de pagamento:</b></td><td>'.f($RS,'nm_forma_pagamento').'</td></tr>';
    } else {
      if (f($RS,'sg_forma_pagamento')!='ESPECIE') $l_html.=chr(13).'      <tr><td colspan=2 align="center" style="border: 1px solid rgb(0,0,0);"><b>Dados para pagamento/recebimento</td>';
      $l_html.=chr(13).'      <tr><td><b>Forma de pagamento/recebimento:</b></td><td>'.f($RS,'nm_forma_pagamento').'</td></tr>';
    }
    if (nvl(f($RS,'referencia_inicio'),'')!='') {
      $l_html.=chr(13).'          <tr><td><b>Período de referência:</b></td>';
      $l_html.=chr(13).'            <td>'.FormataDataEdicao(f($RS,'referencia_inicio')).' a '.FormataDataEdicao(f($RS,'referencia_fim')).'</td></tr>';
    }
    $l_html.=chr(13).'          <tr><td><b>Data prevista:</b></td>';
    $l_html.=chr(13).'            <td>'.FormataDataEdicao(f($RS,'vencimento')).' </td></tr>';
    $l_html.=chr(13).'          <tr><td><b>Valor:</b></td>';
    $l_html.=chr(13).'            <td>'.formatNumber(Nvl(f($RS,'valor'),0)).' </td></tr>';
    if (f($RS_Menu,'sigla')!='FNDREEMB') {
      if (Nvl(f($RS,'condicoes_pagamento'),'')!='') {
        $l_html.=chr(13).'      <tr valign="top"><td><b>Condições de pagamento:</b></td>';
        $l_html.=chr(13).'      <td>'.CRLF2BR(Nvl(f($RS,'condicoes_pagamento'),'---')).' </td></tr>';    
      }
    }
    
    
    // Dados da conclusão do projeto, se ela estiver nessa situação
    if (Nvl(f($RS,'conclusao'),'')>'' && Nvl(f($RS,'quitacao'),'')>'') {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS DO PAGAMENTO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html.=chr(13).'      <tr><td><b>Data:</b></td><td>'.FormataDataEdicao(f($RS,'quitacao')).' </td></tr>';
      if (Nvl(f($RS,'codigo_deposito'),'')>''){
        $l_html.=chr(13).'    <tr><td><b>Código do depósito:</b></td><td>'.f($RS,'codigo_deposito').' </td></tr>';
      }
      $l_html.=chr(13).'      <tr valign="top"><td><b>Observação:</b></td>';
      $l_html.=chr(13).'      <td>'.CRLF2BR(Nvl(f($RS,'observacao'),'---')).' </td></tr>';
    } 

    // Outra parte
    $sql = new db_getBenef; $RS_Query = $sql->getInstanceOf($dbms,$w_cliente,Nvl(f($RS,'pessoa'),0),null,null,null,null,Nvl(f($RS,'sq_tipo_pessoa'),0),null,null,null,null,null,null,null);
    foreach ($RS_Query as $row) {$RS_Query = $row; break;}
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>OUTRA PARTE<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    if (count($RS_Query)<=0) {
      $l_html.=chr(13).'      <tr><td colspan=2 align=center><font size=1>Outra parte não informada';
    } else {
      $l_html.=chr(13).'      <tr><td colspan=2 bgColor="#f0f0f0"style="border: 1px solid rgb(0,0,0);" ><b>';
      if (f($RS,'sq_tipo_pessoa')>2) {
        $l_html.=chr(13).'          '.f($RS_Query,'nm_pessoa').' ('.f($RS_Query,'nome_resumido').') - Passaporte: '.f($RS_Query,'passaporte_numero').' '.f($RS_Query,'nm_pais_passaporte').'</b>';
      } else {
        $l_html.=chr(13).'          '.f($RS_Query,'nm_pessoa').' ('.f($RS_Query,'nome_resumido').') - '.f($RS_Query,'identificador_primario').'</b></td></tr>';
      }
      if (f($RS,'sq_tipo_pessoa')==1) {
        if (nvl(f($RS_Query,'nm_sexo'),'')!='') $l_html.=chr(13).'      <tr><td><b>Sexo:</b></td><td>'.f($RS_Query,'nm_sexo').'</td></tr>';
        if (nvl(f($RS_Query,'nascimento'),'')!='') $l_html.=chr(13).'      <tr><td><b>Data de nascimento:</b></td><td>'.Nvl(FormataDataEdicao(f($RS_Query,'nascimento')),'---').'</td></tr>';
        if (nvl(f($RS_Query,'rg_numero'),'')!='') $l_html.=chr(13).'      <tr><td><b>Identidade:</b></td><td>'.f($RS_Query,'rg_numero').'</td></tr>';
        if (nvl(f($RS_Query,'rg_emissao'),'')!='') $l_html.=chr(13).'      <tr><td><b>Data de emissão:</b></td><td>'.FormataDataEdicao(Nvl(f($RS_Query,'rg_emissao'),'---')).'</td></tr>';
        if (nvl(f($RS_Query,'rg_emissor'),'')!='') $l_html.=chr(13).'      <tr><td><b>Órgão emissor:</b></td><td>'.f($RS_Query,'rg_emissor').'</td></tr>';
        if (nvl(f($RS_Query,'passaporte_numero'),'')!='') $l_html.=chr(13).'      <tr><td><b>Passaporte:</b></td><td>'.Nvl(f($RS_Query,'passaporte_numero'),'---').'</td></tr>';
        if (nvl(f($RS_Query,'nm_pais_passaporte'),'')!='') $l_html.=chr(13).'      <tr><td><b>País emissor:</b></td><td>'.Nvl(f($RS_Query,'nm_pais_passaporte'),'---').'</td></tr>';
      } else {
        if (nvl(f($RS_Query,'inscricao_estadual'),'')!='') {
          $l_html.=chr(13).'      <tr><td><b>Inscrição estadual:</b></td><td>'.Nvl(f($RS_Query,'inscricao_estadual'),'---').'</td></tr>';
        }
      } 
      if (nvl(f($RS_Query,'ddd'),'')!='' || nvl(f($RS_Query,'logradouro'),'')!='') {
        if (f($RS,'sq_tipo_pessoa')==1) {
          $l_html.=chr(13).'      <tr><td colspan=2 style="border: 1px solid rgb(0,0,0);"><b>Endereço comercial, Telefones e e-Mail</td>';
        } else {
          $l_html.=chr(13).'      <tr><td colspan=2 align="center" style="border: 1px solid rgb(0,0,0);"><b>Endereço principal, Telefones e e-Mail</td>';
        }
        $l_html.=chr(13).'      <tr><td width="30%"><b>Telefone:</b></td><td>'.((nvl(f($row,'ddd'),'')!='') ? '('.f($row,'ddd').') '.f($row,'nr_telefone') : '---').'</td></tr>';
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
      }
      if (nvl(f($RS_Query,'email'),'')!='') {
        if (!$l_tipo=='WORD') {
          $l_html.=chr(13).'      <tr><td><b>e-Mail:</b></td><td><a class="hl" href="mailto:'.f($row,'email').'">'.f($row,'email').'</td></tr>';
        } else {
          $l_html.=chr(13).'      <tr><td><b>e-Mail:</b></td><td>'.f($row,'email').'</td></tr>';
        } 
      } 
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
      // Conta bancária da organização envolvida com o lançamento financeiro
      // Exibida apenas para gestores
      if (RetornaGestor($v_chave,$w_usuario)=='S') {
        if (Nvl(f($RS,'cd_ban_org'),'')!='') {
          if (substr($w_SG,0,3)=='FNR') {
            $l_html.=chr(13).'      <tr><td colspan=2 style="border: 1px solid rgb(0,0,0);"><b>Conta crédito</td>';
          } elseif (substr($w_SG,0,3)=='FND') {
            $l_html.=chr(13).'      <tr><td colspan=2 style="border: 1px solid rgb(0,0,0);"><b>Conta débito</td>';
          }
          $l_html.=chr(13).'          <tr><td><b>Banco:</b></td>';
          $l_html.=chr(13).'                <td>'.f($RS,'cd_ban_org').' - '.f($RS,'nm_ban_org').'</td></tr>';
          $l_html.=chr(13).'          <tr><td><b>Agência:</b></td>';
          $l_html.=chr(13).'              <td>'.f($RS,'cd_age_org').' - '.f($RS,'nm_age_org').'</td></tr>';
          if (f($RS,'exige_oper_org')=='S') $l_html.=chr(13).'          <tr><td><b>Operação:</b></td><td>'.Nvl(f($RS,'oper_org'),'---').'</td>';
          $l_html.=chr(13).'          <tr><td><b>Número da conta:</b></td>';
          $l_html.=chr(13).'              <td>'.Nvl(f($RS,'nr_conta_org'),'---').'</td></tr>';
        }
      }
    } 
    $w_vl_retencao    = Nvl(f($RS,'valor_retencao'),0);
    $w_vl_normal      = Nvl(f($RS,'valor_imposto'),0);
    $w_vl_total       = Nvl(f($RS,'valor_doc'),0);
    $w_valor          = Nvl(f($RS,'valor_liquido'),0);
  }
    
  // Notas
  $sql = new db_getLancamentoDoc; $RS = $sql->getInstanceOf($dbms,$v_chave,null,'NOTA');
  $RS = SortArray($RS,'data','asc');
  if (count($RS)>0) {
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>NOTAS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
    $l_html.=chr(13).'      <tr><td align="center"><table width=100%  border="1" bordercolor="#00000">';
    $l_html.=chr(13).'          <tr bgcolor="'.$conTrBgColor.'" align="center">';
    $l_html.=chr(13).'            <td rowspan=2><b>Número</td>';
    $l_html.=chr(13).'            <td colspan=4><b>Valores da Parcela</td>';
    $l_html.=chr(13).'            <td colspan=4><b>Valores do Lançamento</td>';
    $l_html.=chr(13).'          </tr>';
    $l_html.=chr(13).'          <tr bgcolor="'.$conTrBgColor.'" align="center">';
    $l_html.=chr(13).'            <td><b>Inicial</td>';
    $l_html.=chr(13).'            <td><b>Excedente</td>';
    $l_html.=chr(13).'            <td><b>Reajuste</td>';
    $l_html.=chr(13).'            <td><b>Total</td>';
    $l_html.=chr(13).'            <td><b>Inicial</td>';
    $l_html.=chr(13).'            <td><b>Excedente</td>';
    $l_html.=chr(13).'            <td><b>Reajuste</td>';
    $l_html.=chr(13).'            <td><b>Total</td>';
    $l_html.=chr(13).'          </tr>';
    $w_cor=$w_TrBgColor;
    $w_total=0;
    foreach ($RS as $row) {
      $l_html.=chr(13).'      <tr valign="top">';
      $l_html.=chr(13).'        <td align="center">'.f($row,'sg_nota').' '.f($row,'numero_nota').'</td>';
      $l_html.=chr(13).'        <td align="right">'.FormatNumber(Nvl(f($row,'parcela_ini'),0)).'</td>';
      $l_html.=chr(13).'        <td align="right">'.FormatNumber(Nvl(f($row,'parcela_exc'),0)).'</td>';
      $l_html.=chr(13).'        <td align="right">'.FormatNumber(Nvl(f($row,'parcela_rea'),0)).'</td>';
      $l_html.=chr(13).'        <td align="right"><b>'.FormatNumber(Nvl(f($row,'parcela_ini'),0)+Nvl(f($row,'parcela_exc'),0)+Nvl(f($row,'parcela_rea'),0)).'</b></td>';
      $l_html.=chr(13).'        <td align="right">'.FormatNumber(Nvl(f($row,'valor_inicial'),0)).'</td>';
      $l_html.=chr(13).'        <td align="right">'.FormatNumber(Nvl(f($row,'valor_excedente'),0)).'</td>';
      $l_html.=chr(13).'        <td align="right">'.FormatNumber(Nvl(f($row,'valor_reajuste'),0)).'</td>';
      $l_html.=chr(13).'        <td align="right"><b>'.FormatNumber(Nvl(f($row,'valor_inicial'),0)+Nvl(f($row,'valor_excedente'),0)+Nvl(f($row,'valor_reajuste'),0)).'</b></td>';
      $w_parc  += Nvl(f($row,'parcela_ini'),0)+Nvl(f($row,'parcela_exc'),0)+Nvl(f($row,'parcela_rea'),0);
      $w_total += Nvl(f($row,'valor_inicial'),0)+Nvl(f($row,'valor_excedente'),0)+Nvl(f($row,'valor_reajuste'),0);
    } 
    $l_html.=chr(13).'      <tr valign="top" bgcolor="'.$conTrBgColor.'">';
    $l_html.=chr(13).'        <td align="right" colspan=4><b>Totais</b></td>';
    $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_parc).'</b></td>';
    $l_html.=chr(13).'        <td colspan=3>&nbsp;</td>';
    $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_total).'</b></td>';
    $l_html.=chr(13).'      </tr>';
    $l_html.=chr(13).'      </table></td></tr>';
  } 

  // Documentos
  $sql = new db_getLancamentoDoc; $RS = $sql->getInstanceOf($dbms,$v_chave,null,'DOCS');
  $RS = SortArray($RS,'data','asc');
  if (count($RS)>0) {
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DOCUMENTOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
    if ($w_vl_retencao!=0 || $w_vl_normal!=0) {
      $l_html.=chr(13).'          <tr valign="top"><td align="center" colspan="2" style="border: 1px solid rgb(0,0,0);">';
      $l_html.=chr(13).'            <table border=0 width="100%">';
      $l_html.=chr(13).'              <tr><td colspan=4><b>Resumo da tributação sobre os documentos</b></td></tr>';
      $l_html.=chr(13).'              <tr valign="top">';
      $l_html.=chr(13).'              <td width="25%">Valor Bruto:<br><b>'.formatNumber($w_vl_total).' </b></td>';
      $l_html.=chr(13).'              <td width="25%">Retenção:<br><b>'.formatNumber($w_vl_retencao).' </b></td>';
      $l_html.=chr(13).'              <td width="25%">Impostos:<br><b>'.formatNumber($w_vl_normal).' </b></td>';
      $l_html.=chr(13).'              <td width="25%">Valor líquido:<br><b>'.formatNumber(Nvl($w_valor,0)).' </b></td>';
      $l_html.=chr(13).'            </table>';
    } 
    $l_html.=chr(13).'      <tr><td align="center" colspan="2">';
    $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
    $l_html.=chr(13).'          <tr align="center">';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Tipo</b></td>';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Número</b></td>';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Data</b></td>';
    //$l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Série</b></td>';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0" colspan=2><b>Valor</b></td>';
    //if(f($RS_Menu,'sigla')!='FNDVIA' && (Nvl($w_tipo_rubrica,'')==''||Nvl($w_tipo_rubrica,0)==5)) {
    //  $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Patrimônio</b></td>';
    //}
    $l_html.=chr(13).'          </tr>';
    $w_cor=$w_TrBgColor;
    $w_total=0;
    foreach ($RS as $row) {
      $sql = new db_getImpostoDoc; $RS2 = $sql->getInstanceOf($dbms,$w_cliente,$v_chave,f($row,'sq_lancamento_doc'),$w_SG);
      $RS2 = SortArray($RS2,'calculo','asc','esfera','asc','nm_imposto','asc');
      if(Nvl($w_tipo_rubrica,0)!=0 && Nvl($w_tipo_rubrica,0)!=4 && Nvl($w_tipo_rubrica,0)!=5) {
        $sql = new db_getLancamentoRubrica; $RS3 = $sql->getInstanceOf($dbms,null,f($row,'sq_lancamento_doc'),null,null);
        $RS3 = SortArray($RS3,'cd_rubrica_origem','asc');
      } else {
        $sql = new db_getLancamentoItem; $RS3 = $sql->getInstanceOf($dbms,null,f($row,'sq_lancamento_doc'),null,null,null);
        $RS3 = SortArray($RS3,'ordem','asc','codigo_rubrica','asc');
      }
      if (count($RS2)<=0) {
        $l_html.=chr(13).'          <tr align="center" valign="top">';
        if (count($RS3)>0)  $l_html.=chr(13).'            <td rowspan=2>'.f($row,'nm_tipo_documento').'</td>';
        else                $l_html.=chr(13).'            <td>'.f($row,'nm_tipo_documento').'</td>';
        $l_html.=chr(13).'            <td>'.f($row,'numero').'</td>';
        $l_html.=chr(13).'            <td>'.FormataDataEdicao(f($row,'data')).'</td>';
        //$l_html.=chr(13).'            <td>'.Nvl(f($row,'serie'),'---').'</td>';
        $l_html.=chr(13).'            <td align="right" colspan=2>'.formatNumber(f($row,'valor')).'&nbsp;&nbsp;</td>';
        //if(f($RS_Menu,'sigla')!='FNDVIA' && (Nvl($w_tipo_rubrica,'')==''||Nvl($w_tipo_rubrica,0)==5)) {
        //  $l_html.=chr(13).'            <td>'.f($row,'nm_patrimonio').'</td>';
        //}
        $l_html.=chr(13).'          </tr>';
        if (count($RS3)>0)   {
          if(Nvl($w_tipo_rubrica,0)!=0 && Nvl($w_tipo_rubrica,0)!=4 && Nvl($w_tipo_rubrica,0)!=5) {
            $l_html.=chr(13).'              <tr align="center"><td colspan=3 align="center">';
            $l_html.=chr(13).documentorubrica($RS3,$w_tipo_rubrica);
          } else {
            $l_html.=chr(13).'              <tr align="center"><td colspan="'.((Nvl($w_tipo_rubrica,0)==4) ? '3' : '4').'" align="center">';
            $l_html.=chr(13).rubricalinha($RS3);            
          }
        }
      } else {
        if (count($RS3)>0) {
           $l_html.=chr(13).'            <td rowspan=3>'.f($row,'nm_tipo_documento').'</td>';
           $l_html.=chr(13).'              <tr align="center"><td colspan=4 align="center">';
           $l_html.=chr(13).rubricalinha($RS3);
        }
        $l_html.=chr(13).'          <tr align="center" valign="top">';
        $l_html.=chr(13).'            <td rowspan=2>'.f($row,'nm_tipo_documento').'</td>';
        $l_html.=chr(13).'            <td>'.f($row,'numero').'</td>';
        $l_html.=chr(13).'            <td>'.FormataDataEdicao(f($row,'data')).'</td>';
        //$l_html.=chr(13).'            <td>'.Nvl(f($row,'serie'),'---').'</td>';
        $l_html.=chr(13).'            <td rowspan=2 align="right">'.formatNumber(f($row,'valor')).'&nbsp;&nbsp;</td>';
        $l_html.=chr(13).'            <td rowspan=2>'.f($row,'nm_patrimonio').'</td>';
        $l_html.=chr(13).'          </tr>';
        $l_html.=chr(13).'      <tr align="center"><td colspan=3 align="center">';
        $l_html.=chr(13).'          <table border=1 width="100%">';
        $l_html.=chr(13).'          <tr valign="top" align="center">';
        $l_html.=chr(13).'          <td rowspan=2><b>Tributo</td>';
        $l_html.=chr(13).'          <td colspan=2><b>Retenção</td>';
        $l_html.=chr(13).'          <td colspan=2><b>Normal</td>';
        $l_html.=chr(13).'          <td colspan=2><b>Total</td>';
        $l_html.=chr(13).'          <tr bgcolor="'.$w_cor.'" align="center">';
        $l_html.=chr(13).'          <td><b>Valor</td>';
        $l_html.=chr(13).'          <td><b>Alíquota</td>';
        $l_html.=chr(13).'          <td><b>Valor</td>';
        $l_html.=chr(13).'          <td><b>Alíquota</td>';
        $l_html.=chr(13).'          <td><b>Valor</td>';
        $l_html.=chr(13).'          <td><b>Alíquota</td>';
        $w_al_total=0;
        $w_al_retencao=0;
        $w_al_normal=0;
        $w_vl_total=0;
        $w_vl_retencao=0;
        $w_vl_normal=0;
        foreach ($RS2 as $row2) {
          $l_html.=chr(13).'          <tr valign="top">';
          $l_html.=chr(13).'          <td nowrap align="right">'.f($row2,'nm_imposto').'</td>';
          $l_html.=chr(13).'          <td align="right">R$ '.formatNumber(f($row2,'vl_retencao')).'</td>';
          $l_html.=chr(13).'          <td align="center">'.formatNumber(f($row2,'al_retencao')).'%</td>';
          $l_html.=chr(13).'          <td align="right">R$ '.formatNumber(f($row2,'vl_normal')).'</td>';
          $l_html.=chr(13).'          <td align="center">'.formatNumber(f($row2,'al_normal')).'%</td>';
          $l_html.=chr(13).'          <td align="right">R$ '.formatNumber(f($row2,'vl_total')).'</td>';
          $l_html.=chr(13).'          <td align="center">'.formatNumber(f($row2,'al_total')).'%</td>';
          $w_vl_total=$w_vl_total+f($row2,'vl_total');
          $w_vl_retencao=$w_vl_retencao+f($row2,'vl_retencao');
          $w_vl_normal=$w_vl_normal+f($row2,'vl_normal');
        } 
        if (Nvl(f($row,'valor'),0)==0) {
          $w_valor=1;
        } else {
          $w_valor=Nvl(f($row,'valor'),0);
        }
        $w_al_total=100-(($w_valor-($w_vl_normal+$w_vl_retencao))*100/$w_valor);
        $w_al_retencao=100-(($w_valor-$w_vl_retencao)*100/$w_valor);
        $w_al_normal=100-(($w_valor-$w_vl_normal)*100/$w_valor);
        $l_html.=chr(13).'          <tr valign="top">';
        $l_html.=chr(13).'          <td align="center"><b>Totais</td>';
        $l_html.=chr(13).'          <td align="right"><b>R$ '.formatNumber($w_vl_retencao).'<td align="center"><b> '.formatNumber($w_al_retencao).'%';
        $l_html.=chr(13).'          <td align="right"><b>R$ '.formatNumber($w_vl_normal).'<td align="center"><b> '.formatNumber($w_al_normal).'%';
        $l_html.=chr(13).'          <td align="right"><b>R$ '.formatNumber($w_vl_total).'<td align="center"><b> '.formatNumber($w_al_total).'%';
        $l_html.=chr(13).'          <tr bgcolor="'.$w_cor.'" valign="top">';
        $l_html.=chr(13).'          <td align="center"><b>Líquido</td>';
        $l_html.=chr(13).'          <td colspan=2 align="center"><b>R$ '.formatNumber($w_valor-$w_vl_retencao);
        $l_html.=chr(13).'          <td colspan=2 align="center"><b>R$ '.formatNumber($w_valor-$w_vl_retencao-$w_vl_normal);
        $l_html.=chr(13).'          <td colspan=2>&nbsp;';
        $l_html.=chr(13).'          </table>';
      } 
      $w_total=$w_total+f($row,'valor');
      
    } 
    if ($w_total>0) $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
    $l_html.=chr(13).'      <tr valign="top">';
    if(Nvl($w_tipo_rubrica,0)!=4 && Nvl($w_tipo_rubrica,0)!=5) {
      $l_html.=chr(13).'        <td align="right" colspan=3><b>Total</b></td>';
    } else {
      $l_html.=chr(13).'        <td align="right" colspan="'.((Nvl($w_tipo_rubrica,0)==4||f($RS_Menu,'sigla')=='FNDVIA') ? '3' : '4').'"><b>Total</b></td>';
    }
    $l_html.=chr(13).'          <td align="right"><b>'.formatNumber($w_total).'</b>&nbsp;&nbsp;</td>';
    if(Nvl($w_tipo_rubrica,0)==0 && Nvl($w_tipo_rubrica,0)==5) $l_html.=chr(13).'          <td align="right">&nbsp;</td>';
    $l_html.=chr(13).'      </tr>';
    $l_html.=chr(13).'         </table></td></tr>';
  } 

  // Rubricas
  if($w_qtd_rubrica>0) {
    $sql = new db_getLancamentoItem; $RS = $sql->getInstanceOf($dbms,null,null,$v_chave,$w_sq_projeto,'RUBRICA');
    $RS = SortArray($RS,'rubrica','asc');
    if (count($RS)>0) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>RUBRICAS E VALORES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html.=chr(13).'      <tr><td align="center">';
      $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
      $l_html.=chr(13).'          <tr align="center">';
      $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Rubrica</b></td>';
      $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Valor total</b></td>';
      $l_html.=chr(13).'          </tr>';
      $w_cor=$w_TrBgColor;
      $w_total = 0;
      foreach($RS as $row) {
        $l_html.=chr(13).'      <tr valign="top">';
        $l_html.=chr(13).'        <td align="left"><A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.'mod_fn/lancamento.php?par=Ficharubrica&O=L&w_sq_projeto_rubrica='.f($row,'sq_projeto_rubrica').'&w_tipo=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Extrato Rubrica'.'&SG='.$SG.MontaFiltro('GET')).'\',\'Ficha1\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Exibe as informações deste registro.">'.f($row,'rubrica').'</A>&nbsp</td>';
        if(Nvl($w_tipo_rubrica,0)!=0 && Nvl($w_tipo_rubrica,0)!=4 && Nvl($w_tipo_rubrica,0)!=5)
          $l_html.=chr(13).'        <td align="right">'.formatNumber(Nvl(f($row,'valor_rubrica'),0)).'&nbsp;&nbsp;</td>';
        else
          $l_html.=chr(13).'        <td align="right">'.formatNumber(Nvl(f($row,'valor_total'),0)).'&nbsp;&nbsp;</td>';
        $l_html.=chr(13).'      </tr>';
        if(Nvl($w_tipo_rubrica,0)!=0 && Nvl($w_tipo_rubrica,0)!=4 && Nvl($w_tipo_rubrica,0)!=5)
          $w_total += nvl(f($row,'valor_rubrica'),0);
        else
          $w_total += nvl(f($row,'valor_total'),0);
      } 
      if ($w_total>0) {
        $l_html.=chr(13).'      <tr valign="top">';
        $l_html.=chr(13).'        <td align="right"><b>Total</b></td>';
        $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_total).'</b>&nbsp;&nbsp;</td>';
        $l_html.=chr(13).'      </tr>';
      }      
      $l_html.=chr(13).'         </table></td></tr>';
    }
  }    
  // Arquivos vinculados
  $sql = new db_getSolicAnexo; $RS = $sql->getInstanceOf($dbms,$v_chave,null,$w_cliente);
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
  $w_erro=ValidaLancamento($w_cliente,$v_chave,substr($w_SG,0,3).'GERAL',null,null,null,Nvl($w_tramite,0));
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
  $v_html.=chr(13).'          <td width="20%" bgColor="#f0f0f0"><b>Rubrica</b></td>';
  $v_html.=chr(13).'          <td width="42%" bgColor="#f0f0f0"><b>Descrição</b></td>';
  $v_html.=chr(13).'          <td width="7%"  bgColor="#f0f0f0"><b>Qtd</b></td>';
  if (strpos(f($RS_Menu,'sigla'),'VIA')!==false) {
    $v_html.=chr(13).'          <td width="7%"  bgColor="#f0f0f0"><b>Data cotação</b></td>';
    $v_html.=chr(13).'          <td width="7%"  bgColor="#f0f0f0"><b>Valor cotação</b></td>';
  }
  $v_html.=chr(13).'          <td width="13%" bgColor="#f0f0f0"><b>$ Unit</b></td>';
  $v_html.=chr(13).'          <td width="13%" bgColor="#f0f0f0"><b>$ Total</b></td>';
  $v_html.=chr(13).'        </tr>';
  foreach($v_RS3 as $row) {
    $v_html.=chr(13).'      <tr valign="top">';
    $v_html.=chr(13).'        <td align="center">'.f($row,'ordem').'</td>';
    if(nvl(f($row,'codigo_rubrica'),'')>'')
      $v_html.=chr(13).'        <td><A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.'mod_fn/lancamento.php?par=Ficharubrica&O=L&w_sq_projeto_rubrica='.f($row,'sq_projeto_rubrica').'&w_tipo=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Extrato Rubrica'.'&SG='.$SG.MontaFiltro('GET')).'\',\'Ficha2\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Exibe as informações deste registro.">'.f($row,'codigo_rubrica').' - '.f($row,'nm_rubrica').'</A>&nbsp</td>';
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