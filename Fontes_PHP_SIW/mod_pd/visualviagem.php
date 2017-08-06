<?php
// =========================================================================
// Rotina de visualização dos dados da viagem
// -------------------------------------------------------------------------
function VisualViagem($l_chave,$l_o,$l_usuario,$l_p1,$l_tipo,$l_identificacao='S',$l_proposto='S',$l_deslocamento='S',
      $l_orcamentaria='S',$l_diaria='S',$l_vinculacao='S',$l_anexo='S',$l_ocorrencia='S') {
  extract($GLOBALS);
  
  if ($l_tipo!='WORD') $w_TrBgColor=''; else $w_TrBgColor=$conTrBgColor;
  $l_html='';
  
  // Carrega o segmento do cliente
  $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente); 
  $w_segmento = f($RS,'segmento');
  
  // Recupera os dados do acordo
  $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$l_chave,substr($SG,0,3).'GERAL');
  $w_or_tramite        = f($RS,'or_tramite');
  $w_tramite           = f($RS,'sq_siw_tramite');
  $w_tramite_ativo     = f($RS,'ativo');
  $w_valor_inicial     = f($RS,'valor');
  $w_fim               = f($RS,'fim_real');
  $w_sg_tramite        = f($RS,'sg_tramite');
  $w_sigla             = f($RS,'sigla');
  $w_aditivo           = f($RS,'aditivo');
  $w_forma_pagamento   = f($RS,'sg_forma_pagamento');
  $w_internacional     = f($RS,'internacional');
  $w_complemento_moeda = f($RS,'sg_moeda_complemento');
  $w_complemento_qtd   = f($RS,'complemento_qtd');
  $w_complemento_base  = f($RS,'complemento_base');
  $w_complemento_valor = f($RS,'complemento_valor');
  $w_passagem          = f($RS,'passagem');

  // Execução financeira da viagem
  $sql = new db_getSolicList; $RSF = $sql->getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,'FILHOS',null,
        null,null,null,null,null,null,null,null,'T',null,$l_chave, null, null, null, null, null, null,
        null, null, null, null, null, null, null, null, null);
  
  $w_financeiro = count($RSF);
//  if (count($RSF)>0) {
//    foreach($RSF as $row) {
//      if (f($row,'sg_tramite')!='CA') {
//        $w_financeiro = true;
//        break;
//      }
//    }
//  }
  
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
  if ($l_o=='L' || $l_o=='V') {
    $l_html.=chr(13).'<br />';
    $l_html.=chr(13).'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
    $l_html.=chr(13).'<tr><td>';
    $l_html.=chr(13).'    <table width="99%" border="0">';
    $l_html .= chr(13).'    <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
    $l_html.=chr(13).'      <tr><td colspan="14" height="2" bgcolor="#000000"></td></tr>';
    if ($w_mod_pa=='S' && nvl(f($RS,'protocolo_siw'),'')!='') {
      if ($l_tipo!='WORD') {
        $l_html.=chr(13).'      <tr><td colspan="10" bgcolor="#f0f0f0"><font size=2><b>'.f($RS,'codigo_interno').' ('.$l_chave.')</b></font></td><td colspan="4" bgcolor="#f0f0f0" align="right"><font size="2"><b>PROTOCOLO: <A class="HL" HREF="mod_pa/documento.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($RS,'protocolo_siw').'&w_tipo=&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=PADGERAL'.MontaFiltro('GET').'" title="Exibe as informações deste registro." target="protocolo">'.f($RS,'protocolo').'&nbsp;</a></tr>';
      } else {
        $l_html.=chr(13).'      <tr><td colspan="10" bgcolor="#f0f0f0"><font size=2><b>'.f($RS,'codigo_interno').' ('.$l_chave.')</b></font></td><td colspan="4" bgcolor="#f0f0f0" align="right"><font size="2"><b>PROTOCOLO: '.nvl(f($RS,'protocolo'),'---').'</tr>';
      }
    } else {
      $l_html.=chr(13).'      <tr><td colspan="14" bgcolor="#f0f0f0" align=justify><font size="2"><b>'.f($RS,'codigo_interno').' ('.$l_chave.')</b></font></td></tr>';
    }
    $l_html.=chr(13).'      <tr><td colspan="14" height="2" bgcolor="#000000"></td></tr>';
    
    // Identificação da viagem
    if ($l_identificacao=='S') {
      $l_html.=chr(13).'      <tr><td colspan="14"><br /><font size="2"><b>DADOS GERAIS</b></font><hr NOSHADE color=#000000 SIZE=1 /></td></tr>';
      
      // Exibe a vinculação
      $l_html.=chr(13).'      <tr><td valign="top" width="30%"><b>Vinculação: </b></td>';
      if($l_tipo!='WORD') $l_html.=chr(13).'        <td colspan="12">'.exibeSolic($w_dir,f($RS,'sq_solic_pai'),f($RS,'dados_pai'),'S').'</td></tr>';
      else                $l_html.=chr(13).'        <td colspan="12">'.exibeSolic($w_dir,f($RS,'sq_solic_pai'),f($RS,'dados_pai'),'S','S').'</td></tr>';
      if(nvl(f($RS,'sq_solic_pai'),'')!='' && f($RS,'ativo')=='S') {
        // Exibe saldos das rubricas
        $sql = new db_getPD_Financeiro; $RS_Fin = $sql->getInstanceOf($dbms,$w_cliente,null,f($RS,'sq_solic_pai'),null,null,null,null,null,null,null,null,null,'ORCAM_SIT');
        $RS_Fin = SortArray($RS_Fin,'cd_rubrica','asc','nm_rubrica','asc','nm_lancamento','asc');
        $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Disponibilidade orçamentária:</b></td>';
        $l_html.=chr(13).'      <td colspan="12"><table width=100%  border="1" bordercolor="#00000">';
        $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
        $l_html.=chr(13).'          <td><b>Rubrica</b></td>';
        $l_html.=chr(13).'          <td><b>Descrição</b></td>';
        $l_html.=chr(13).'          <td><b>% Executado</b></td>';
        $l_html.=chr(13).'         <td><b>Saldo (R$)</b></td>';
        $l_html.=chr(13).'        </tr>';
        if (count($RS_Fin)<=0) {
          $l_html.=chr(13).'      <tr><td colspan=4 align="center"><b>Não foram encontrados registros.</b></td></tr>';
        } else {
          foreach ($RS_Fin as $row) {
            $l_html.=chr(13).'      <tr valign="top">';
            $l_html.=chr(13).'        <td>'.Nvl(f($row,'cd_rubrica'),'&nbsp;').'&nbsp;'.Nvl(f($row,'nm_rubrica'),'&nbsp;').'</td>';
            $l_html.=chr(13).'        <td>'.Nvl(f($row,'descricao'),'&nbsp;').'</td>';
            $l_html.=chr(13).'        <td align="center">'.formatNumber(f($row,'perc_exec'))  .'</td>';
            $l_html.=chr(13).'        <td align="center">'.formatNumber(f($row,'saldo')).'</td>';
            $l_html.=chr(13).'      </tr>';
          } 
        } 
        $l_html.=chr(13).'    </table>';
        $l_html.=chr(13).'  </td>';
        $l_html.=chr(13).'</tr>';
      }
      
      if (nvl(f($RS,'nm_etapa'),'')>'') {
        if (substr($w_sigla,0,3)=='GCB') {   
          $l_html.=chr(13).'      <tr valign="top" width="30%"><td><b>Modalidade: </b></td>';
          $l_html.=chr(13).'          <td colspan="12">      '.f($RS,'nm_etapa').'</td></tr>';
        } else { 
          $l_html.=chr(13).'      <tr valign="top" width="30%"><td><b>Etapa: </b></td>';
          $l_html.=chr(13).'          <td colspan="12">      '.f($RS,'nm_etapa').'</td></tr>';
        }
      } 
  
      // Se a classificação foi informada, exibe.
      if (Nvl(f($RS,'sq_cc'),'')>'') {
        $l_html .= chr(13).'      <tr><td width="30%"><b>Classificação:<b></td>';
        $l_html .= chr(13).'        <td colspan="12">'.f($RS,'nm_cc').' </td></tr>';
      }
      
      $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Objetivo/assunto/evento:</b></td><td colspan="12">'.crLf2Br(f($RS,'descricao')).'</td></tr>';
      if ($l_tipo!='WORD') {
        $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Unidade proponente:</b></td><td colspan="12">'.ExibeUnidade($w_dir_volta,$w_cliente,f($RS,'nm_unidade_resp'),f($RS,'sq_unidade_resp'),$TP).'</td>';
      } else {
        $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Unidade proponente:</b></td><td colspan="12">'.f($RS,'nm_unidade_resp').'</td></tr>';
      } 
      $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Período:</b></td><td colspan="12">'.FormataDataEdicao(f($RS,'inicio')).' a '.FormataDataEdicao(f($RS,'fim')).'</td></tr>';
      $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Bilhetes / Passagens:</b></td><td colspan="12">'.Nvl(f($RS,'nm_passagem'),'---').' </td></tr>';
      $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Categoria da diária:</b></td><td colspan="12">'.Nvl(f($RS,'nm_diaria'),'---').' </td></tr>';
      $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Hospedagem:</b></td><td colspan="12">'.Nvl(f($RS,'nm_hospedagem'),'---').'</td></tr>';
      $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Veículo:</b></td><td colspan="12">'.Nvl(f($RS,'nm_veiculo'),'---').' </td></tr>';
      if (f($RS,'internacional')=='N') {
        $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Diária em fim de semana:</b></td><td colspan="12">'.retornaSimNao(f($RS,'diaria_fim_semana')).' </td></tr>';
      }
      $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Contato na ausência:</b></td><td colspan="12">'.nvl(f($RS,'proponente'),'---').' </td></tr>';
      $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Agenda:</b></td><td colspan="12">'.nvl(crLf2Br(f($RS,'assunto')),'---').' </td></tr>';
      if (Nvl(f($RS,'observacao'),'')>'') {
        $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Outras fontes de financiamento:</b></td><td colspan="12">'.crLf2Br(f($RS,'observacao')).' </td></tr>';
      }
      if (Nvl(f($RS,'justificativa_dia_util'),'')>'') {
        // Se o campo de justificativa de dias úteis para estiver preenchido, exibe
        $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Justif. viagem contendo fim de semana/feriado:</b></td><td colspan="12">'.crLf2Br(f($RS,'justificativa_dia_util')).' </td></tr>';
      } 
      if (Nvl(f($RS,'justificativa'),'')>'') {
        // Se o campo de justificativa estiver preenchido, exibe
        $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Justif. pedido com menos de '.f($RS,'dias_antecedencia').' dias:</b></td><td colspan="12">'.crLf2Br(f($RS,'justificativa')).' </td></tr>';
      } 
    }
    
    // Dados do proposto
    if ($l_proposto=='S') {
      $sql = new db_getBenef; $RSQuery = $sql->getInstanceOf($dbms,$w_cliente,Nvl(f($RS,'sq_prop'),0),null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
      
      $l_html.=chr(13).'      <tr><td colspan="14"><br /><font size="2"><b>BENEFICIÁRIO</b></font><hr NOSHADE color=#000000 SIZE=1 /></td></tr>';
      if (count($RSQuery)==0) {
        $l_html.=chr(13).'      <tr><td colspan=14 align="center"><font size=1>Beneficiário não informado';
      } else {
        foreach($RSQuery as $row) { 
          $l_html.=chr(13).'      <tr><td colspan="14"><table width=100%  border="1" bordercolor="#00000">';
          $l_html.=chr(13).'        <tr><td colspan="14" bgColor="#f0f0f0"><b>';
          if (f($row,'sq_tipo_pessoa')==1) {
            $l_html.=chr(13).'            '.f($row,'nm_pessoa').' ('.f($row,'nome_resumido').') - '.f($row,'cpf').'</b>';
          } else {
            $l_html.=chr(13).'            '.f($row,'nm_pessoa').' ('.f($row,'nome_resumido').') - Passaporte: '.f($row,'passaporte_numero').' '.f($row,'nm_pais_passaporte').'</b>';
          }
          if ($w_tramite_ativo=='S') {
            $l_html.=chr(13).'        <tr><td colspan="14" width="30%" style="border-top: 1px solid rgb(0,0,0);"><table border=0 cellpadding=0 cellspacing=0 width="100%">';
            $l_html.=chr(13).'        <tr valign="top"><td colspan="6">Vínculo: <b>'.f($row,'nm_tipo_vinculo').'</b><td colspan="2" width="30%">Interno: <b>'.retornaSimNao(f($row,'interno')).'</b><td colspan="2" width="30%">Contratado: <b>'.retornaSimNao(f($row,'contratado')).'</b></td></tr>';
            $l_html.=chr(13).'          </table>';
          }
          $l_html.=chr(13).'          </table>';
          $l_html.=chr(13).'      <tr><td colspan="14">';
          $l_html.=chr(13).'      <tr><td width="30%"><b>Sexo:</b></td><td colspan="12">'.f($row,'nm_sexo').'</td></tr>';
          $l_html.=chr(13).'      <tr><td width="30%"><b>Identidade:</b></td><td colspan="12" align="left">'.f($row,'rg_numero').'</td></tr>';
          $l_html.=chr(13).'      <tr><td width="30%"><b>Data de emissão:</b></td><td colspan="12" align="left">'.FormataDataEdicao(Nvl(f($row,'rg_emissao'),'---')).'</td>';
          $l_html.=chr(13).'      <tr><td width="30%"><b>Órgão emissor:</b></td><td colspan="12">'.f($row,'rg_emissor').'</td></tr>';
          if (Nvl(f($row,'passaporte_numero'),'')!='' && f($row,'sq_tipo_pessoa')==1) {
            $l_html.=chr(13).'      <tr><td width="30%"><b>Passaporte:</b></td><td colspan="12">'.f($row,'passaporte_numero').' - '.f($row,'nm_pais_passaporte').'</td></tr>';
          }
          $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Telefone:</b></td>';
          if (nvl(f($row,'ddd'),'nulo')!='nulo') {
            $l_html.=chr(13).'            <td colspan="12">('.f($row,'ddd').') '.f($row,'nr_telefone').'</td></tr>';
          } else {
            $l_html.=chr(13).'            <td colspan="12">---</td></tr>';
          }
          $l_html.=chr(13).'      <tr><td width="30%"><b>Fax:</b></td><td colspan="12">'.Nvl(f($row,'nr_fax'),'---').'</td></tr>';
          $l_html.=chr(13).'      <tr><td width="30%"><b>Celular:</b></td> <td colspan="12">'.Nvl(f($row,'nr_celular'),'---').'</td></tr>';
          if (nvl(f($row,'logradouro'),'')!='') {
            $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Endereço:</b></td><td colspan="12">'.f($row,'logradouro').'</td></tr>';
            $l_html.=chr(13).'      <tr><td width="30%"><b>Complemento:</b></td><td colspan="12">'.Nvl(f($row,'complemento'),'---').'</td></tr>';
            $l_html.=chr(13).'      <tr><td width="30%"><b>Bairro:</b></td><td colspan="12">'.Nvl(f($row,'bairro'),'---').'</td></tr>';
            $l_html.=chr(13).'      <tr valign="top">';
            if (f($row,'pd_pais')=='S') {
              $l_html.=chr(13).'          <td width="30%"><b>Cidade:</b></td><td colspan="12">'.f($row,'nm_cidade').'-'.f($row,'co_uf').'</td></tr>';
            } else {
              $l_html.=chr(13).'          <td width="30%"><b>Cidade:</b></td><td colspan="12">'.f($row,'nm_cidade').'-'.f($row,'nm_pais').'</td></tr>';
            } 
            $l_html.=chr(13).'      <tr><td width="30%"><b>CEP:</b></td><td colspan="12">'.f($row,'cep').'</td></tr>';
          }
          if (Nvl(f($row,'email'),'nulo')!='nulo') {
            if ($l_tipo!='WORD') {
              $l_html.=chr(13).'      <tr><td width="30%"><b>e-Mail:</b></td><td colspan="12"><a class="hl" href="mailto:'.f($row,'email').'">'.f($row,'email').'</a></td></tr>';
            } else {
              $l_html.=chr(13).'      <tr><td width="30%"><b>e-Mail:</b></td><td colspan="12">'.f($row,'email').'</td></tr>';
            } 
          } else {
            $l_html.=chr(13).'      <tr><td width="30%"><b>e-Mail:</b></td><td colspan="12">---</td></tr>';
          }  
          if (nvl(f($RS,'sq_forma_pagamento'),'')!='') {
            $l_html.=chr(13).'      <tr><td colspan=14 style="border: 1px solid rgb(0,0,0);"><b>Dados para recebimento das diárias</b></td>';
            $l_html.=chr(13).'      <tr><td width="30%"><b>Forma de recebimento:</b></td><td colspan="12">'.f($RS,'nm_forma_pagamento').'</td></tr>';
            if (!(strpos('CREDITO,DEPOSITO',$w_forma_pagamento)===false)) {
              $l_html.=chr(13).'          <tr><td width="30%"><b>Banco:</b></td><td colspan="12">'.f($RS,'cd_banco').' - '.f($RS,'nm_banco').'</td></tr>';
              $l_html.=chr(13).'          <tr><td width="30%"><b>Agência:</b></td><td colspan="12">'.f($RS,'cd_agencia').' - '.f($RS,'nm_agencia').'</td></tr>';
              if (f($RS,'exige_operacao')=='S') $l_html.=chr(13).'          <tr><td><b>Operação:</b></td><td colspan="12">'.Nvl(f($RS,'operacao_conta'),'---').'</td>';
              $l_html.=chr(13).'          <tr><td width="30%"><b>Número da conta:</b></td><td colspan="12" align="left">'.Nvl(f($RS,'numero_conta'),'---').'</td></tr>';
            } elseif (f($RS,'sg_forma_pagamento')=='ORDEM') {
              $l_html.=chr(13).'          <tr><td width="30%"><b>Banco:</b></td><td colspan="12">'.f($RS,'cd_banco').' - '.f($RS,'nm_banco').'</td></tr>';
              $l_html.=chr(13).'          <tr><td width="30%"><b>Agência:</b></td><td colspan="12">'.f($RS,'cd_agencia').' - '.f($RS,'nm_agencia').'</td></tr>';
            } elseif (f($RS,'sg_forma_pagamento')=='EXTERIOR') {
              $l_html.=chr(13).'          <tr><td width="30%"><b>Banco:</b></td><td colspan="12">'.f($RS,'banco_estrang').'</td>';
              $l_html.=chr(13).'          <tr><td width="30%"><b>ABA Code:</b></td><td colspan="12">'.Nvl(f($RS,'aba_code'),'---').'</td>';
              $l_html.=chr(13).'          <tr><td width="30%"><b>SWIFT Code:</b></td><td colspan="12">'.Nvl(f($RS,'swift_code'),'---').'</td>';
              $l_html.=chr(13).'          <tr><td width="30%"><b>Endereço da agência:</b></td><td colspan="12">'.Nvl(f($RS,'endereco_estrang'),'---').'</td>';
              $l_html.=chr(13).'          <tr><td width="30%"><b>Agência:</b></td><td colspan="12">'.Nvl(f($RS,'agencia_estrang'),'---').'</td>';
              $l_html.=chr(13).'          <tr><td width="30%"><b>Número da conta:</b></td><td colspan="12" align="left">'.Nvl(f($RS,'numero_conta'),'---').'</td>';
              $l_html.=chr(13).'          <tr><td width="30%"><b>Cidade:</b></td><td colspan="12">'.f($RS,'cidade_estrang').'</td>';
              $l_html.=chr(13).'          <tr><td width="30%"><b>País:</b></td><td colspan="12">'.f($RS,'pais_estrang').'</td>';
              $l_html.=chr(13).'          <tr><td width="30%"><b>Informações adicionais:</b></td><td colspan="12">'.nvl(f($RS,'informacoes'),'---').'</td>';
            }
          }
        } 
      }
    }

    // Vinculações a atividades
    $sql = new db_getPD_Vinculacao; $RS1 = $sql->getInstanceOf($dbms,$l_chave,null,null);
    $RS1 = SortArray($RS1,'inicio','asc');
    if (count($RS1)>0) {
      $l_html.=chr(13).'      <tr><td colspan="14"><br /><font size="2"><b>VINCULAÇÕES</b></font><hr NOSHADE color=#000000 SIZE=1 /></td></tr>';
      $l_html.=chr(13).'      <tr><td colspan="14">';
      $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
      $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $l_html.=chr(13).'          <td><b>Nº</b></td>';
      $l_html.=chr(13).'          <td><b>Projeto</b></td>';
      $l_html.=chr(13).'          <td><b>Detalhamento</b></td>';
      $l_html.=chr(13).'          <td><b>Início</b></td>';
      $l_html.=chr(13).'          <td><b>Fim</b></td>';
      $l_html.=chr(13).'          <td><b>Situação</b></td>';
      $l_html.=chr(13).'          </tr>';
      $w_cor=$w_TrBgColor;
      $w_total=0;
      foreach($RS1 as $row) {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
        $l_html.=chr(13).'      <tr valign="top">';
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
          $l_html.=chr(13).'        <A class="HL" TARGET="VISUAL" HREF="mod_dm/demanda.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_volta=fecha&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'sq_siw_solicitacao').'&nbsp;</a>';
        } else {
          $l_html.=chr(13).'        <A class="HL" TARGET="VISUAL" HREF="projetoativ.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_volta=fecha&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'sq_siw_solicitacao').'&nbsp;</a>';
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

    // Deslocamentos
    if($l_deslocamento=='S') {
      $sql = new db_getPD_Deslocamento; $RS1 = $sql->getInstanceOf($dbms,$l_chave,null,'S','PDGERAL');
      $RS1 = SortArray($RS1,'phpdt_saida','asc', 'phpdt_chegada', 'asc');
      $l_html.=chr(13).'      <tr><td colspan="14"><br /><font size="2"><b>ROTEIRO PREVISTO</b></font><hr NOSHADE color=#000000 SIZE=1 /></td></tr>';
      $l_html.=chr(13).'      <tr><td colspan="14"><table width=100%  border="1" bordercolor="#00000">';
      $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $l_html.=chr(13).'          <td><b>Origem</b></td>';
      $l_html.=chr(13).'          <td><b>Aeroporto</b></td>';
      $l_html.=chr(13).'          <td><b>Destino</b></td>';
      $l_html.=chr(13).'          <td><b>Aeroporto</b></td>';
      $l_html.=chr(13).'          <td><b>Saída</b></td>';
      $l_html.=chr(13).'          <td><b>Chegada</b></td>';
      $l_html.=chr(13).'          <td><b>Agenda no<br />dia viagem</b></td>';
      $l_html.=chr(13).'          <td><b>Transp.</b></td>';
      $l_html.=chr(13).'          <td><b>Emite<br />bilhete</b></td>';
      //$l_html.=chr(13).'          <td><b>Valor</b></td>';
      //$l_html.=chr(13).'          <td><b>Cia.</b></td>';
      //$l_html.=chr(13).'          <td><b>Vôo</b></td>';
      $l_html.=chr(13).'        </tr>';
      

      if (count($RS1)==0) {
        // Se não foram selecionados registros, exibe mensagem 
        $l_html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'"><td colspan="9" align="center"><b>Não foram encontrados registros.</b></td></tr>';
      } else {
        // Lista os registros selecionados para listagem 
        $w_tot_bilhete  = 0;
        foreach($RS1 as $row) {
          $l_html.=chr(13).'      <tr valign="top">';
          $l_html.=chr(13).'        <td>'.f($row,'nm_origem').'</td>';
          $l_html.=chr(13).'        <td>'.nvl(f($row,'aeroporto_origem'),'&nbsp;').'</td>';
          $l_html.=chr(13).'        <td>'.f($row,'nm_destino').'</td>';
          $l_html.=chr(13).'        <td>'.nvl(f($row,'aeroporto_destino'),'&nbsp;').'</td>';
          $l_html.=chr(13).'        <td align="center">'.substr(FormataDataEdicao(f($row,'phpdt_saida'),6),0,-3).'</td>';
          $l_html.=chr(13).'        <td align="center">'.substr(FormataDataEdicao(f($row,'phpdt_chegada'),6),0,-3).'</td>';
          $l_html.=chr(13).'        <td align="center">'.f($row,'nm_compromisso').'</td>';
          $l_html.=chr(13).'        <td align="center">'.nvl(f($row,'nm_meio_transporte'),'---').'</td>';
          $l_html.=chr(13).'        <td align="center">'.f($row,'nm_passagem').'</td>';
          //$l_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'valor_trecho')).'</td>';
          //$l_html.=chr(13).'        <td>'.nvl(f($row,'nm_cia_transporte'),'&nbsp;').'</td>';
          //$l_html.=chr(13).'        <td align="center">'.nvl(f($row,'codigo_voo'),'&nbsp;').'</td>';
          $l_html.=chr(13).'      </tr>';
          $w_tot_bilhete  += nvl(f($row,'valor_trecho'),0);
        } 
        //$l_html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'" valign="top">';
        //$l_html.=chr(13).'        <td align="right" colspan=7><b>TOTAL</b></td>';
        //$l_html.=chr(13).'        <td align="right"><b>'.Nvl(formatNumber($w_tot_bilhete),0).'</b></td>';
        //$l_html.=chr(13).'        <td colspan=2>&nbsp;</td>';
      } 
      $l_html.=chr(13).'    </table>';
      $l_html.=chr(13).'  </td>';
      $l_html.=chr(13).'</tr>';
      //$l_html.=chr(13).'    </table>';

      if (f($RS,'cumprimento')!='C' && f($RS,'cumprimento')!='N') {
        $sql = new db_getPD_Deslocamento; $RS1 = $sql->getInstanceOf($dbms,$l_chave,null,'P','PDGERAL');
        $RS1 = SortArray($RS1,'phpdt_saida','asc', 'phpdt_chegada', 'asc');
        $l_html.=chr(13).'      <tr><td colspan="14"><br /><font size="2"><b>ROTEIRO REALIZADO</b></font><hr NOSHADE color=#000000 SIZE=1 /></td></tr>';
        $l_html.=chr(13).'      <tr><td colspan="14">';
        $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
        $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
        $l_html.=chr(13).'          <td><b>Origem</b></td>';
        $l_html.=chr(13).'          <td><b>Aeroporto</b></td>';
        $l_html.=chr(13).'          <td><b>Destino</b></td>';
        $l_html.=chr(13).'          <td><b>Aeroporto</b></td>';
        $l_html.=chr(13).'          <td><b>Saída</b></td>';
        $l_html.=chr(13).'          <td><b>Chegada</b></td>';
        $l_html.=chr(13).'          <td><b>Agenda no<br />dia viagem</b></td>';
        $l_html.=chr(13).'          <td><b>Transp.</b></td>';
        $l_html.=chr(13).'          <td><b>Emite<br />bilhete</b></td>';
        //$l_html.=chr(13).'          <td><b>Valor</b></td>';
        //$l_html.=chr(13).'          <td><b>Cia.</b></td>';
        //$l_html.=chr(13).'          <td><b>Vôo</b></td>';
        $l_html.=chr(13).'        </tr>';
        if (count($RS1)==0) {
          // Se não foram selecionados registros, exibe mensagem 
          $l_html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'"><td colspan=10 align="center"><b>Não foram encontrados registros.</b></td></tr>';
        } else {
          // Lista os registros selecionados para listagem 
          $w_tot_bilhete  = 0;
          foreach($RS1 as $row) {
            $l_html.=chr(13).'      <tr valign="top">';
            $l_html.=chr(13).'        <td>'.f($row,'nm_origem').'</td>';
            $l_html.=chr(13).'        <td>'.nvl(f($row,'aeroporto_origem'),'&nbsp;').'</td>';
            $l_html.=chr(13).'        <td>'.f($row,'nm_destino').'</td>';
            $l_html.=chr(13).'        <td>'.nvl(f($row,'aeroporto_destino'),'&nbsp;').'</td>';
            $l_html.=chr(13).'        <td align="center">'.substr(FormataDataEdicao(f($row,'phpdt_saida'),6),0,-3).'</td>';
            $l_html.=chr(13).'        <td align="center">'.substr(FormataDataEdicao(f($row,'phpdt_chegada'),6),0,-3).'</td>';
            $l_html.=chr(13).'        <td align="center">'.f($row,'nm_compromisso').'</td>';
            $l_html.=chr(13).'        <td align="center">'.nvl(f($row,'nm_meio_transporte'),'---').'</td>';
            $l_html.=chr(13).'        <td align="center">'.f($row,'nm_passagem').'</td>';
            //$l_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'valor_trecho')).'</td>';
            //$l_html.=chr(13).'        <td>'.nvl(f($row,'nm_cia_transporte'),'&nbsp;').'</td>';
            //$l_html.=chr(13).'        <td align="center">'.nvl(f($row,'codigo_voo'),'&nbsp;').'</td>';
            $l_html.=chr(13).'      </tr>';
            $w_tot_bilhete  += nvl(f($row,'valor_trecho'),0);
          } 
          //$l_html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'" valign="top">';
          //$l_html.=chr(13).'        <td align="right" colspan=7><b>TOTAL</b></td>';
          //$l_html.=chr(13).'        <td align="right"><b>'.Nvl(formatNumber($w_tot_bilhete),0).'</b></td>';
          //$l_html.=chr(13).'        <td colspan=2>&nbsp;</td>';
        } 
        $l_html.=chr(13).'    </table>';
        $l_html.=chr(13).'  </td>';
        $l_html.=chr(13).'</tr>';
      }
    }

    // Diárias, hospedagens e veículos
    if($l_diaria=='S') {
      $sql = new db_getPD_Deslocamento; $RS1 = $sql->getInstanceOf($dbms,$l_chave,null,'S','PDDIARIA');
      $RS1 = SortArray($RS1,'phpdt_saida','asc', 'phpdt_chegada', 'asc');
      $i = 0;
      foreach($RS1 as $row) {
        if ($i==0) $w_inicio = f($row,'saida');
        $w_fim = f($row,'chegada');
        $i++;
      }
      reset($RS1);
      $i = 1;
      if (count($RS1)>0) {
        foreach($RS1 as $row) {
          //if (nvl(f($row,'sq_diaria'),0)>0) {
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
            $w_trechos[$i][33] = 'N';
            $w_trechos[$i][34] = formataDataEdicao(f($row,'hospedagem_checkin'));
            $w_trechos[$i][35] = formataDataEdicao(f($row,'hospedagem_checkout'));
            $w_trechos[$i][36] = f($row,'hospedagem_observacao');
            $w_trechos[$i][37] = formataDataEdicao(f($row,'veiculo_retirada'));
            $w_trechos[$i][38] = formataDataEdicao(f($row,'veiculo_devolucao'));
            $w_trechos[$i][39] = f($row,'destino_nacional');
            $w_trechos[$i][40] = f($row,'saida_internacional');
            $w_trechos[$i][41] = f($row,'chegada_internacional');
            $w_trechos[$i][42] = f($row,'origem_nacional');
            
            // Cria array para guardar o valor total por moeda
            if ($w_trechos[$i][13]>'') $w_tot_diaria_S[$w_trechos[$i][13]] = 0;
            if ($w_trechos[$i][18]>'') $w_tot_diaria_S[$w_trechos[$i][18]] = 0;
            if ($w_trechos[$i][12]>'') $w_tot_diaria_S[$w_trechos[$i][23]] = 0;
            if ($i==1) {
              // Se a primeira saída for após as 18:00, deduz meia diária
              if (intVal(str_replace(':','',formataDataEdicao(f($row,'phpdt_saida'),2)))>180000) {
                $w_trechos[$i][32] = 'S';
              }
            } else {
              // Se a última chegada for até 12:00, deduz meia diária
              if ($i==count($RS1) && intVal(str_replace(':','',formataDataEdicao(f($row,'phpdt_chegada'),2)))<=120000) {
                $w_trechos[$i-1][33] = 'S';
              }
              $w_trechos[$i-1][3]  = f($row,'sq_deslocamento');
              $w_trechos[$i-1][7]  = f($row,'phpdt_saida');
              $w_trechos[$i-1][31] = f($row,'compromisso');
            }
            $i += 1;
          //}
        } 
        if ($i>1) {
          $l_html.=chr(13).'      <tr><td colspan="14"><br /><font size="2"><b>PREVISÃO DE DIÁRIAS, HOSPEDAGENS E VEÍCULOS</b></font><hr NOSHADE color=#000000 SIZE=1 /></td></tr>';
          $l_html .= chr(13).'      <tr><td valign="top" colspan="14"><table border=0 width="100%" cellspacing=0>';
        }
        $w_cor          = $conTrBgColor;
        $j              = $i;
        $i              = 1;
        $w_diarias      = 0;
        $w_locacoes     = 0;
        $w_hospedagens  = 0;
        $w_tot_local    = 0;
        while($i<=count($w_trechos)) {
          $w_max_hosp     = ceil((toDate(formataDataEdicao($w_trechos[$i][7]))-toDate(formataDataEdicao($w_trechos[$i][6])))/86400);
          $w_diarias      = nvl($w_trechos[$i][8],0)*nvl($w_trechos[$i][9],0);
          $w_locacoes     = -1*nvl($w_trechos[$i][9],0)*nvl($w_trechos[$i][22],0)/100*nvl($w_trechos[$i][21],0);
          $w_hospedagens  = nvl($w_trechos[$i][16],0)*nvl($w_trechos[$i][17],0);
          
          if ($w_diarias>0)     $w_tot_diaria_S[$w_trechos[$i][13]] += $w_diarias;
          if ($w_locacoes<>0)   $w_tot_diaria_S[$w_trechos[$i][23]] += $w_locacoes;
          //if ($w_hospedagens>0) $w_tot_diaria_S[$w_trechos[$i][18]] += $w_hospedagens;
          
          $w_tot_local = $w_diarias + $w_locacoes;
          
          if ((($i>=1 || ($i==1 && count($w_trechos)==2)) && $i<count($w_trechos) && (($w_trechos[$i][39]=='N' && toDate(FormataDataEdicao($w_trechos[$i][6]))==$w_fim) || ($w_trechos[$i][42]=='S' || toDate(FormataDataEdicao($w_trechos[$i][6]))!=$w_fim))) || 
              ($w_trechos[$i][40]==0 && 
               $w_trechos[$i][41]==0 && 
               ($w_trechos[$i][42]=='S' || toDate(FormataDataEdicao($w_trechos[$i][6]))!=$w_fim) && 
               ($w_tot_local!=0 || $i!=count($w_trechos))
              )
             ) {
            $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
            
            // Configura a quantidade de linhas do trecho
            $rowspan = 1;
            if ($w_trechos[$i][27]>'' && f($RS,'veiculo')=='S' && $w_trechos[$i][21]>0)    $rowspan+=1;
            if ($w_trechos[$i][26]>'' && f($RS,'hospedagem')=='S' && $w_trechos[$i][16]>0) $rowspan+=1;
            $rowspan_local = $rowspan;
            if ($w_trechos[$i][28]>'') $rowspan_local += 1;
            if ($w_trechos[$i][29]>'') $rowspan_local += 1;
            
            $l_html.=chr(13).'      <tr><td colspan="14" bgColor="#f0f0f0" style="border: 1px solid rgb(0,0,0);"><b>'.$w_trechos[$i][5].'</b></td>';
            $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Estada:</b><td colspan="12">'.substr(FormataDataEdicao($w_trechos[$i][6],4),0,-3).' a '.substr(FormataDataEdicao($w_trechos[$i][7],4),0,-3);
            if ($w_trechos[$i][32]=='S' || $w_trechos[$i][33]=='S')  {
              $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Horários:</b><td colspan="12">';
              if ($w_trechos[$i][32]=='S') $l_html.=chr(13).'Saída após  as 18:00';
              if ($w_trechos[$i][32]=='S' && $w_trechos[$i][33]=='S') $l_html.=chr(13).'/';
              if ($w_trechos[$i][33]=='S') $l_html.=chr(13).'Chegada até 12:00';
            }
            if ($w_trechos[$i][30]=='N' || $w_trechos[$i][31]=='N') {
              $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Compromissos:</b><td colspan="12">';
              if ($w_trechos[$i][30]=='N') $l_html.=chr(13).'Sem compromisso na ida';
              if ($w_trechos[$i][30]=='N' && $w_trechos[$i][31]=='N') $l_html.=chr(13).'/';
              if ($w_trechos[$i][31]=='N') $l_html.=chr(13).'Sem compromisso na volta';
            }
            $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Diárias:</b><td colspan="12">';
            if ($w_trechos[$i][12]=='S') {
              $l_html.=chr(13).'Sim. '.((nvl($w_trechos[$i][28],'')!='') ? 'Observações: '.crlf2br($w_trechos[$i][28]) : '').'</td>';
            } else {
              $l_html.=chr(13).'Não.'.((nvl($w_trechos[$i][28],'')!='') ? 'Justificativa: '.crlf2br($w_trechos[$i][28]) : '').'</td>';
            }
            if ($w_trechos[$i][39]=='S') {
              if ($w_trechos[$i][15]=='S') {
                  $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Hospedagem:</b><td colspan="12">'.$w_trechos[$i][34].' a '.$w_trechos[$i][35].'. Observação: '.crlf2br($w_trechos[$i][36]).'</td>';
                } else {
                  $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Hospedagem:</b><td colspan="12">Não. '.((nvl($w_trechos[$i][36],'')!='') ? 'Justificativa: '.crlf2br($w_trechos[$i][36]) : '').'</td>';
                }
            }
            if ($w_trechos[$i][20]=='S' && $w_trechos[$i][27]>'' && f($RS,'veiculo')=='S') {
              $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Veículo:</b><td colspan="12">'.$w_trechos[$i][37].' a '.$w_trechos[$i][38].'. Justificativa: '.crlf2br($w_trechos[$i][29]).'</td>';
            } else {
              $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Veículo:</b><td colspan="12">Não.</td>';
            }
          }
          $i += 1;
        }
         if ($i>1) {
          $l_html .= chr(13).'      </table>';
        }
      }

      if (f($RS,'cumprimento')!='C') {
        unset($w_trechos);
        $sql = new db_getPD_Deslocamento; $RS1 = $sql->getInstanceOf($dbms,$l_chave,null,'P','PDDIARIA');
        $RS1 = SortArray($RS1,'phpdt_saida','asc', 'phpdt_chegada', 'asc');
        $i = 0;
        foreach($RS1 as $row) {
          if ($i==0) $w_inicio = f($row,'saida');
          $w_fim = f($row,'chegada');
          $i++;
        }
        reset($RS1);
        $i = 1;
        if (count($RS1)>0) {
          foreach($RS1 as $row) {
            //if (nvl(f($row,'sq_diaria'),0)>0) {
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
              $w_trechos[$i][33] = 'N';
              $w_trechos[$i][34] = formataDataEdicao(f($row,'hospedagem_checkin'));
              $w_trechos[$i][35] = formataDataEdicao(f($row,'hospedagem_checkout'));
              $w_trechos[$i][36] = f($row,'hospedagem_observacao');
              $w_trechos[$i][37] = formataDataEdicao(f($row,'veiculo_retirada'));
              $w_trechos[$i][38] = formataDataEdicao(f($row,'veiculo_devolucao'));
              $w_trechos[$i][39] = f($row,'destino_nacional');
              $w_trechos[$i][40] = f($row,'saida_internacional');
              $w_trechos[$i][41] = f($row,'chegada_internacional');
              $w_trechos[$i][42] = f($row,'origem_nacional');
              
              // Cria array para guardar o valor total por moeda
              if ($w_trechos[$i][13]>'') $w_tot_diaria_P[$w_trechos[$i][13]] = 0;
              if ($w_trechos[$i][18]>'') $w_tot_diaria_P[$w_trechos[$i][18]] = 0;
              if ($w_trechos[$i][12]>'') $w_tot_diaria_P[$w_trechos[$i][23]] = 0;
              if ($i==1) {
                // Se a primeira saída for após as 18:00, deduz meia diária
                if (intVal(str_replace(':','',formataDataEdicao(f($row,'phpdt_saida'),2)))>180000) {
                  $w_trechos[$i][32] = 'S';
                }
              } else {
                // Se a última chegada for até 12:00, deduz meia diária
                if ($i==count($RS1) && intVal(str_replace(':','',formataDataEdicao(f($row,'phpdt_chegada'),2)))<=120000) {
                  $w_trechos[$i-1][33] = 'S';
                }
                $w_trechos[$i-1][3]  = f($row,'sq_deslocamento');
                $w_trechos[$i-1][7]  = f($row,'phpdt_saida');
                $w_trechos[$i-1][31] = f($row,'compromisso');
              }
              $i += 1;
            //}
          } 
          if ($i>1) {
            $l_html.=chr(13).'      <tr><td colspan="14"><br /><font size="2"><b>REALIZAÇÃO DE DIÁRIAS, HOSPEDAGENS E VEÍCULOS</b></font><hr NOSHADE color=#000000 SIZE=1 /></td></tr>';
            $l_html .= chr(13).'      <tr><td valign="top" colspan="14"><table border=0 width="100%" cellspacing=0>';
          }
          $w_cor          = $conTrBgColor;
          $j              = $i;
          $i              = 1;
          $w_diarias      = 0;
          $w_locacoes     = 0;
          $w_hospedagens  = 0;
          $w_tot_local    = 0;
          while($i<=count($w_trechos)) {
            $w_max_hosp     = ceil((toDate(formataDataEdicao($w_trechos[$i][7]))-toDate(formataDataEdicao($w_trechos[$i][6])))/86400);
            $w_diarias      = nvl($w_trechos[$i][8],0)*nvl($w_trechos[$i][9],0);
            $w_locacoes     = -1*nvl($w_trechos[$i][9],0)*nvl($w_trechos[$i][22],0)/100*nvl($w_trechos[$i][21],0);
            $w_hospedagens  = nvl($w_trechos[$i][16],0)*nvl($w_trechos[$i][17],0);
            
            if ($w_diarias>0)     $w_tot_diaria_P[$w_trechos[$i][13]] += $w_diarias;
            if ($w_locacoes<>0)   $w_tot_diaria_P[$w_trechos[$i][23]] += $w_locacoes;
            
            $w_tot_local = $w_diarias + $w_locacoes;
            
            if ((($i>=1 || ($i==1 && count($w_trechos)==2)) && $i<count($w_trechos) && (($w_trechos[$i][39]=='N' && toDate(FormataDataEdicao($w_trechos[$i][6]))==$w_fim) || ($w_trechos[$i][42]=='S' || toDate(FormataDataEdicao($w_trechos[$i][6]))!=$w_fim))) || 
              ($w_trechos[$i][40]==0 && 
               $w_trechos[$i][41]==0 && 
               ($w_trechos[$i][42]=='S' || toDate(FormataDataEdicao($w_trechos[$i][6]))!=$w_fim) && 
               ($w_tot_local!=0 || $i!=count($w_trechos))
              )
             ) {
              $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
              
              // Configura a quantidade de linhas do trecho
              $rowspan = 1;
              if ($w_trechos[$i][27]>'' && f($RS,'veiculo')=='S' && $w_trechos[$i][21]>0)    $rowspan+=1;
              if ($w_trechos[$i][26]>'' && f($RS,'hospedagem')=='S' && $w_trechos[$i][16]>0) $rowspan+=1;
              $rowspan_local = $rowspan;
              if ($w_trechos[$i][28]>'') $rowspan_local += 1;
              if ($w_trechos[$i][29]>'') $rowspan_local += 1;
              
              $l_html.=chr(13).'      <tr><td colspan="14" bgColor="#f0f0f0" style="border: 1px solid rgb(0,0,0);"><b>'.$w_trechos[$i][5].'</b></td>';
              $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Estada:</b><td colspan="12">'.substr(FormataDataEdicao($w_trechos[$i][6],4),0,-3).' a '.substr(FormataDataEdicao($w_trechos[$i][7],4),0,-3);
              if ($w_trechos[$i][32]=='S' || $w_trechos[$i][33]=='S')  {
                $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Horários:</b><td colspan="12">';
                if ($w_trechos[$i][32]=='S') $l_html.=chr(13).'Saída após  as 18:00';
                if ($w_trechos[$i][32]=='S' && $w_trechos[$i][33]=='S') $l_html.=chr(13).'/';
                if ($w_trechos[$i][33]=='S') $l_html.=chr(13).'Chegada até 12:00';
              }
              if ($w_trechos[$i][30]=='N' || $w_trechos[$i][31]=='N') {
                $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Compromissos:</b><td colspan="12">';
                if ($w_trechos[$i][30]=='N') $l_html.=chr(13).'Sem compromisso na ida';
                if ($w_trechos[$i][30]=='N' && $w_trechos[$i][31]=='N') $l_html.=chr(13).'/';
                if ($w_trechos[$i][31]=='N') $l_html.=chr(13).'Sem compromisso na volta';
              }
              $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Diárias:</b><td colspan="12">';
              if ($w_trechos[$i][12]=='S') {
                $l_html.=chr(13).'Sim.</td>';
              } else {
                $l_html.=chr(13).'Não.'.((nvl($w_trechos[$i][28],'')!='') ? 'Justificativa: '.crlf2br($w_trechos[$i][28]) : '').'</td>';
              }
              if ($w_trechos[$i][39]=='S') {
                if ($w_trechos[$i][15]=='S') {
                  $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Hospedagem:</b><td colspan="12">'.$w_trechos[$i][34].' a '.$w_trechos[$i][35].'. Observação: '.crlf2br($w_trechos[$i][36]).'</td>';
                } else {
                  $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Hospedagem:</b><td colspan="12">Não.'.((nvl($w_trechos[$i][36],'')!='') ? 'Justificativa: '.crlf2br($w_trechos[$i][36]) : '').'</td>';
                }
              }
              if ($w_trechos[$i][20]=='S' && $w_trechos[$i][27]>'' && f($RS,'veiculo')=='S') {
                $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Veículo:</b><td colspan="12">'.$w_trechos[$i][37].' a '.$w_trechos[$i][38].'. Justificativa: '.crlf2br($w_trechos[$i][29]).'</td>';
              } else {
                $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Veículo:</b><td colspan="12">Não.</td>';
              }
            }
            $i += 1;
          }
          if ($i>1) {
            $l_html .= chr(13).'      </table>';
          }
        }
      }
    }

    // Alterações de viagem
    $sql = new db_getPD_Alteracao; $RS1 = $sql->getInstanceOf($dbms,$l_chave,null,null,null,null,null,null);
    $RS1 = SortArray($RS1,'autorizacao_data','asc', 'chave', 'asc');
    if (count($RS1)>0) {
      $l_html.=chr(13).'      <tr><td colspan="14"><br /><font size="2"><b>ALTERAÇÕES DE VIAGEM</b></font><hr NOSHADE color=#000000 SIZE=1 /></td></tr>';
      $l_html.=chr(13).'      <tr><td colspan="14">';
      $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
      $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $l_html.=chr(13).'          <td colspan=5><b>Diferenças</b></td>';
      $l_html.=chr(13).'          <td colspan=3><b>Autorização</b></td>';
      $l_html.=chr(13).'          <td rowspan=2><b>Justificativa</b></td>';
      $l_html.=chr(13).'        </tr>';
      $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $l_html.=chr(13).'          <td><b>Tarifas</b></td>';
      $l_html.=chr(13).'          <td><b>Taxas</b></td>';
      $l_html.=chr(13).'          <td><b>Hospedagens</b></td>';
      $l_html.=chr(13).'          <td><b>Diárias</b></td>';
      $l_html.=chr(13).'          <td><b>Total</b></td>';
      $l_html.=chr(13).'          <td><b>Nome</b></td>';
      $l_html.=chr(13).'          <td><b>Cargo</b></td>';
      $l_html.=chr(13).'          <td><b>Data</b></td>';
      $l_html.=chr(13).'        </tr>';
      $w_cor=$conTrBgColor;
      $i             = 1;
      $w_tot_alt     = 0;
      foreach ($RS1 as $row) {
        $w_tot_alt      = f($row,'diaria_valor')+f($row,'hospedagem_valor')+f($row,'bilhete_tarifa')+f($row,'bilhete_taxa');
        $w_total       += $w_tot_alt;
        $l_html.=chr(13).'        <tr valign="top">';
        $l_html.=chr(13).'          <td align="right">'.formatNumber(f($row,'bilhete_tarifa')).'</td>';
        $l_html.=chr(13).'          <td align="right">'.formatNumber(f($row,'bilhete_taxa')).'</td>';
        $l_html.=chr(13).'          <td align="right">'.formatNumber(f($row,'hospedagem_valor')).'</td>';
        $l_html.=chr(13).'          <td align="right">'.formatNumber(f($row,'diaria_valor')).'</td>';
        $l_html.=chr(13).'          <td align="right">'.formatNumber($w_tot_alt).'</td>';
        $l_html.=chr(13).'          <td>'.f($row,'nm_autorizador').'</td>';
        $l_html.=chr(13).'          <td>'.f($row,'autorizacao_cargo').'</td>';
        $l_html.=chr(13).'          <td align="center">'.FormataDataEdicao(f($row,'autorizacao_data'),5).'</td>';
        $l_html.=chr(13).'          <td>'.nvl(CRLF2BR(f($row,'justificativa')),'---');
        if (nvl(f($row,'sq_siw_arquivo'),'')!='') {
          $l_html.='<br />'.LinkArquivo('HL',$w_cliente,f($row,'sq_siw_arquivo'),'_blank','Clique para exibir o arquivo em outra janela.','Arquivo',null).'</td>';
        }
        $l_html.=chr(13).'        </tr>';
      } 
      $l_html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'" valign="top">';
      $l_html.=chr(13).'        <td align="right" colspan="4"><b>TOTAL</b></td>';
      $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_total).'</b></td>';
      $l_html.=chr(13).'        <td align="right" colspan="4">&nbsp;</td>';
      $l_html.=chr(13).'      </tr>';
      $l_html.=chr(13).'         </table></td></tr>';
    } 
  
    // Pagamento de diárias
    if($l_diaria=='S' && (($w_cliente==10135 && $w_or_tramite>5) || ($w_cliente==17305 && $w_or_tramite>1))) {
      unset($w_trechos);
      unset($w_tot_diaria_S);
      $sql = new db_getPD_Deslocamento; $RS1 = $sql->getInstanceOf($dbms,$l_chave,null,'S','PDDIARIA');
      $RS1 = SortArray($RS1,'phpdt_saida','asc', 'phpdt_chegada', 'asc');
      if (count($RS1)>0) {
        $i = 0;
        foreach($RS1 as $row) {
          if ($i==0) $w_inicio = f($row,'saida');
          $w_fim = f($row,'chegada');
          $i++;
        }
        reset($RS1);
        $i = 1;
        foreach($RS1 as $row) {
          //if (nvl(f($row,'sq_diaria'),0)>0) {
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
            $w_trechos[$i][33] = 'N';
            $w_trechos[$i][34] = f($row,'calculo_diaria_qtd');
            $w_trechos[$i][35] = f($row,'calculo_diaria_texto');
            $w_trechos[$i][36] = f($row,'calculo_hospedagem_qtd');
            $w_trechos[$i][37] = f($row,'calculo_hospedagem_texto');
            $w_trechos[$i][38] = f($row,'calculo_veiculo_qtd');
            $w_trechos[$i][39] = f($row,'calculo_veiculo_texto');
            
            // Cria array para guardar o valor total por moeda
            if (nvl($w_trechos[$i][13],'')!='') $w_tot_diaria_S[$w_trechos[$i][13]] = 0;
            if (nvl($w_trechos[$i][18],'')!='') $w_tot_diaria_S[$w_trechos[$i][18]] = 0;
            if (nvl($w_trechos[$i][23],'')!='') $w_tot_diaria_S[$w_trechos[$i][23]] = 0;
            if ($i==1) {
              // Se a primeira saída for após as 18:00, deduz meia diária
              if (intVal(str_replace(':','',formataDataEdicao(f($row,'phpdt_saida'),2)))>180000) {
                $w_trechos[$i][32] = 'S';
              }
            } else {
              // Se a última chegada for até 12:00, deduz meia diária
              if ($i==count($RS1) && intVal(str_replace(':','',formataDataEdicao(f($row,'phpdt_chegada'),2)))<=120000) {
                $w_trechos[$i-1][33] = 'S';
              }
              $w_trechos[$i-1][3]  = f($row,'sq_deslocamento');
              $w_trechos[$i-1][7]  = f($row,'phpdt_saida');
              $w_trechos[$i-1][31] = f($row,'compromisso');
            }
            $i += 1;
          //}
        }

        $l_html.=chr(13).'      <tr><td colspan="14"><br /><font size="2"><b>PAGAMENTO PREVISTO DE DIÁRIAS, HOSPEDAGENS E VEÍCULOS</b></font><hr NOSHADE color=#000000 SIZE=1 /></td></tr>';
        $l_html.=chr(13).'      <tr><td colspan="14">';
        $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
        $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
        $l_html.=chr(13).'           <td><b>Localidade</b></td>';
        $l_html.=chr(13).'           <td><b>Chegada</b></td>';
        $l_html.=chr(13).'           <td><b>Saída</b></td>';
        $l_html.=chr(13).'           <td><b>Item</b></td>';
        $l_html.=chr(13).'           <td><b>Qtd.</b></td>';
        $l_html.=chr(13).'           <td><b>$ Unitário</b></td>';
        $l_html.=chr(13).'           <td><b>$ Total</b></td>';
        $l_html.=chr(13).'         </tr>';
        $w_cor          = $conTrBgColor;
        $j              = $i;
        $i              = 1;
        $w_diarias      = 0;
        $w_locacoes     = 0;
        $w_hospedagens  = 0;
        $w_tot_local    = 0;
        while($i<=count($w_trechos)) {
          $w_max_hosp     = ceil((toDate(formataDataEdicao($w_trechos[$i][7]))-toDate(formataDataEdicao($w_trechos[$i][6])))/86400);
          $w_diarias      = nvl($w_trechos[$i][8],0)*nvl($w_trechos[$i][9],0);
          $w_locacoes     = -1*nvl($w_trechos[$i][9],0)*nvl($w_trechos[$i][22],0)/100*nvl($w_trechos[$i][21],0);
          $w_hospedagens  = nvl($w_trechos[$i][16],0)*nvl($w_trechos[$i][17],0);
          
          if ($w_diarias>0)     $w_tot_diaria_S[$w_trechos[$i][13]] += $w_diarias;
          if ($w_locacoes!=0)   $w_tot_diaria_S[$w_trechos[$i][23]] += $w_locacoes;
          //if ($w_hospedagens>0) $w_tot_diaria_S[$w_trechos[$i][18]] += $w_hospedagens;
          
          $w_tot_local = $w_diarias + $w_locacoes;
          
          if ($w_tot_local!=0 || $w_hospedagens>0) {
            $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
            
            // Configura a quantidade de linhas do trecho
            $rowspan = 1;
            if ($w_trechos[$i][27]>'' && f($RS,'veiculo')=='S' && $w_trechos[$i][21]>0)    $rowspan+=1;
            if ($w_trechos[$i][26]>'' && f($RS,'hospedagem')=='S' && $w_trechos[$i][16]>0) $rowspan+=1;
            $rowspan_local = $rowspan;
            if ($w_trechos[$i][35]>''||$w_trechos[$i][37]>''||$w_trechos[$i][39]>'') $rowspan_local += 1;
            
            $l_html.=chr(13).'     <tr valign="top">';
            $l_html.=chr(13).'       <td rowspan="'.$rowspan_local.'"><b>'.$w_trechos[$i][5].'</b>';
            $l_html.=chr(13).'<br />'.$w_trechos[$i][13].' '.formatNumber($w_tot_local);
            $l_html.=chr(13).'       <td align="center" rowspan="'.$rowspan.'">'.substr(FormataDataEdicao($w_trechos[$i][6],4),0,-3).'</td>';
            $l_html.=chr(13).'       <td align="center" rowspan="'.$rowspan.'">'.substr(FormataDataEdicao($w_trechos[$i][7],4),0,-3).'</td>';
            if ($w_trechos[$i][25]>'' && nvl(f($RS,'diaria'),'')!='') {
              $l_html.=chr(13).'         <td>Diária ('.$w_trechos[$i][13].')</td>';
              $l_html.=chr(13).'         <td align="right">'.formatNumber($w_trechos[$i][8],2).'</td>';
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
              $l_html.=chr(13).'         <td align="right">'.formatNumber($w_trechos[$i][16],2).'</td>';
              $l_html.=chr(13).'         <td align="right">'.formatNumber($w_trechos[$i][17]).'</td>';
              $l_html.=chr(13).'         <td align="right">'.formatNumber($w_hospedagens,2).'</td>';
              $l_html.=chr(13).'       </tr>';
            }
            if ($w_trechos[$i][35]>''||$w_trechos[$i][37]>''||$w_trechos[$i][39]>'') {
              $l_html.=chr(13).'     <tr><td colspan="6">';
              if ($w_trechos[$i][35]>'') {
                if ($w_trechos[$i][8]!=$w_trechos[$i][34]) $l_html.=chr(13).'         <li>Quantidade calculada de diárias alterada de <b>'.formatNumber($w_trechos[$i][34],1).'</b> para <b>'.formatNumber($w_trechos[$i][8],2).'</b>. Motivo: <b>'.$w_trechos[$i][35].'</b></li>';
                else $l_html.=chr(13).'         <li>Observação sobre as diárias: <b>'.$w_trechos[$i][35].'</b></li>';
              }
              if ($w_trechos[$i][37]>'') {
                if ($w_trechos[$i][16]!=$w_trechos[$i][36]) $l_html.=chr(13).'         <li>Quantidade calculada de hospedagens alterada de <b>'.formatNumber($w_trechos[$i][36],1).'</b> para <b>'.formatNumber($w_trechos[$i][16],1).'</b>. Motivo: <b>'.$w_trechos[$i][37].'</b></li>';
                else  $l_html.=chr(13).'         <li>Observação sobre a hospedagem: <b>'.$w_trechos[$i][37].'</b></li>';
              }
              if ($w_trechos[$i][39]>'') {
                if ($w_trechos[$i][21]!=$w_trechos[$i][38]) $l_html.=chr(13).'         <li>Quantidade calculada de diárias de veículo alterada de <b>'.formatNumber($w_trechos[$i][38],1).'</b> para <b>'.formatNumber($w_trechos[$i][21],1).'</b>. Motivo: <b>'.$w_trechos[$i][39].'</b></li>';
                else $l_html.=chr(13).'         <li>Observação sobre a locação: <b>'.$w_trechos[$i][39].'</b></li>';
              }
            }
          }
          $i += 1;
        }
        if ($w_complemento_qtd>0) {
          $l_html.=chr(13).'     <tr valign="top">';
          $l_html.=chr(13).'       <td colspan="4" align="right">Complemento de diárias ('.$w_complemento_moeda.')&nbsp;&nbsp;&nbsp;</td>';
          $l_html.=chr(13).'       <td align="right">'.formatNumber($w_complemento_qtd,2).'</td>';
          $l_html.=chr(13).'       <td align="right">'.formatNumber($w_complemento_base).'</td>';
          $l_html.=chr(13).'       <td align="right">'.formatNumber($w_complemento_valor).'</td>';
          $w_tot_diaria_S[$w_complemento_moeda] += $w_complemento_valor;
        }
        $l_html.=chr(13).'     <tr bgcolor="'.$conTrBgColor.'"><td colspan="7" align="center"><b>TOTAL DIÁRIAS:';
        foreach($w_tot_diaria_S as $k => $v) {
          $l_html.=chr(13).'       &nbsp;&nbsp;&nbsp;&nbsp;'.$k.' '.formatNumber($v);
        }
        $l_html.=chr(13).'     </b></td></tr>';
        $l_html.=chr(13).'        </table></td></tr>';
      }
    }

    // Pagamento de diárias
    if($l_diaria=='S' && (($w_cliente==10135 && $w_or_tramite>=10) || ($w_cliente==17305 && $w_or_tramite>=6))) {
      unset($w_trechos);
      unset($w_tot_diaria_P);
      $sql = new db_getPD_Deslocamento; $RS1 = $sql->getInstanceOf($dbms,$l_chave,null,'P','PDDIARIA');
      $RS1 = SortArray($RS1,'phpdt_saida','asc', 'phpdt_chegada', 'asc');
      if (count($RS1)>0) {
        $i = 0;
        foreach($RS1 as $row) {
          if ($i==0) $w_inicio = f($row,'saida');
          $w_fim = f($row,'chegada');
          $i++;
        }
        reset($RS1);
        $i = 1;
        foreach($RS1 as $row) {
          //if (nvl(f($row,'sq_diaria'),0)>0) {
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
            $w_trechos[$i][33] = 'N';
            $w_trechos[$i][34] = f($row,'calculo_diaria_qtd');
            $w_trechos[$i][35] = f($row,'calculo_diaria_texto');
            $w_trechos[$i][36] = f($row,'calculo_hospedagem_qtd');
            $w_trechos[$i][37] = f($row,'calculo_hospedagem_texto');
            $w_trechos[$i][38] = f($row,'calculo_veiculo_qtd');
            $w_trechos[$i][39] = f($row,'calculo_veiculo_texto');
            
            // Cria array para guardar o valor total por moeda
            if ($w_trechos[$i][13]>'') $w_tot_diaria_P[$w_trechos[$i][13]] = 0;
            if ($w_trechos[$i][18]>'') $w_tot_diaria_P[$w_trechos[$i][18]] = 0;
            if ($w_trechos[$i][23]>'') $w_tot_diaria_P[$w_trechos[$i][23]] = 0;
            if ($i==1) {
              // Se a primeira saída for após as 18:00, deduz meia diária
              if (intVal(str_replace(':','',formataDataEdicao(f($row,'phpdt_saida'),2)))>180000) {
                $w_trechos[$i][32] = 'S';
              }
            } else {
              // Se a última chegada for até 12:00, deduz meia diária
              if ($i==count($RS1) && intVal(str_replace(':','',formataDataEdicao(f($row,'phpdt_chegada'),2)))<=120000) {
                $w_trechos[$i-1][33] = 'S';
              }
              $w_trechos[$i-1][3]  = f($row,'sq_deslocamento');
              $w_trechos[$i-1][7]  = f($row,'phpdt_saida');
              $w_trechos[$i-1][31] = f($row,'compromisso');
            }
            $i += 1;
          }
        //} 
        $l_html.=chr(13).'      <tr><td colspan="14"><br /><font size="2"><b>PAGAMENTO REALIZADO DE DIÁRIAS, HOSPEDAGENS E VEÍCULOS</b></font><hr NOSHADE color=#000000 SIZE=1 /></td></tr>';
        $l_html.=chr(13).'      <tr><td colspan="14"><table width=100%  border="1" bordercolor="#00000">';
        $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
        $l_html.=chr(13).'           <td><b>Localidade</b></td>';
        $l_html.=chr(13).'           <td><b>Chegada</b></td>';
        $l_html.=chr(13).'           <td><b>Saída</b></td>';
        $l_html.=chr(13).'           <td><b>Item</b></td>';
        $l_html.=chr(13).'           <td><b>Qtd.</b></td>';
        $l_html.=chr(13).'           <td><b>$ Unitário</b></td>';
        $l_html.=chr(13).'           <td><b>$ Total</b></td>';
        $l_html.=chr(13).'         </tr>';
        $w_cor          = $conTrBgColor;
        $j              = $i;
        $i              = 1;
        $w_diarias      = 0;
        $w_locacoes     = 0;
        $w_hospedagens  = 0;
        $w_tot_local    = 0;
        while($i<=count($w_trechos)) {
          $w_max_hosp     = ceil((toDate(formataDataEdicao($w_trechos[$i][7]))-toDate(formataDataEdicao($w_trechos[$i][6])))/86400);
          $w_diarias      = nvl($w_trechos[$i][8],0)*nvl($w_trechos[$i][9],0);
          $w_locacoes     = -1*nvl($w_trechos[$i][9],0)*nvl($w_trechos[$i][22],0)/100*nvl($w_trechos[$i][21],0);
          $w_hospedagens  = nvl($w_trechos[$i][16],0)*nvl($w_trechos[$i][17],0);
          
          if ($w_diarias>0)     $w_tot_diaria_P[$w_trechos[$i][13]] += $w_diarias;
          if ($w_locacoes<>0)   $w_tot_diaria_P[$w_trechos[$i][23]] += $w_locacoes;
          //if ($w_hospedagens>0) $w_tot_diaria_P[$w_trechos[$i][18]] += $w_hospedagens;
          
          $w_tot_local = $w_diarias + $w_locacoes;
          if ($w_diarias!=0 || $w_hospedagens > 0) {
            $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
            
            // Configura a quantidade de linhas do trecho
            $rowspan = 1;
            if ($w_trechos[$i][27]>'' && f($RS,'veiculo')=='S' && $w_trechos[$i][21]>0)    $rowspan+=1;
            if ($w_trechos[$i][26]>'' && f($RS,'hospedagem')=='S' && $w_trechos[$i][16]>0) $rowspan+=1;
            $rowspan_local = $rowspan;
            if ($w_trechos[$i][35]>''||$w_trechos[$i][37]>''||$w_trechos[$i][39]>'') $rowspan_local += 1;
            
            $l_html.=chr(13).'     <tr valign="top">';
            $l_html.=chr(13).'       <td rowspan="'.$rowspan_local.'"><b>'.$w_trechos[$i][5].'</b>';
            $l_html.=chr(13).'<br />'.$w_trechos[$i][13].' '.formatNumber($w_tot_local);
            $l_html.=chr(13).'       <td align="center" rowspan="'.$rowspan.'">'.substr(FormataDataEdicao($w_trechos[$i][6],4),0,-3).'</td>';
            $l_html.=chr(13).'       <td align="center" rowspan="'.$rowspan.'">'.substr(FormataDataEdicao($w_trechos[$i][7],4),0,-3).'</td>';
            if ($w_trechos[$i][25]>'' && nvl(f($RS,'diaria'),'')!='') {
              $l_html.=chr(13).'         <td>Diária ('.$w_trechos[$i][13].')</td>';
              $l_html.=chr(13).'         <td align="right">'.formatNumber($w_trechos[$i][8],2).'</td>';
              $l_html.=chr(13).'         <td align="right">'.formatNumber($w_trechos[$i][9]).'</td>';
              $l_html.=chr(13).'         <td align="right">'.formatNumber($w_diarias,2).'</td>';
              $l_html.=chr(13).'       </tr>';
            }
            if ($w_trechos[$i][27]>'' && f($RS,'veiculo')=='S' && $w_trechos[$i][21]>0) {
              $l_html.=chr(13).'       <tr valign="top">';
              $l_html.=chr(13).'         <td>Veículo ('.$w_trechos[$i][23].') -'.formatNumber($w_trechos[$i][24],0).'%</td>';
              $l_html.=chr(13).'         <td align="right">'.formatNumber($w_trechos[$i][21],1).'</td>';
              $l_html.=chr(13).'         <td align="right">'.formatNumber(-1*$w_trechos[$i][9]*$w_trechos[$i][22]/100).'</td>';
              $l_html.=chr(13).'         <td align="right">'.formatNumber($w_locacoes,1).'</td>';
              $l_html.=chr(13).'       </tr>';
            }
            if ($w_trechos[$i][26]>'' && f($RS,'hospedagem')=='S' && $w_trechos[$i][16]>0) {
              $l_html.=chr(13).'       <tr valign="top">';
              $l_html.=chr(13).'         <td>Hospedagem ('.$w_trechos[$i][18].')</td>';
              $l_html.=chr(13).'         <td align="right">'.formatNumber($w_trechos[$i][16],2).'</td>';
              $l_html.=chr(13).'         <td align="right">'.formatNumber($w_trechos[$i][17]).'</td>';
              $l_html.=chr(13).'         <td align="right">'.formatNumber($w_hospedagens,2).'</td>';
              $l_html.=chr(13).'       </tr>';
            }
            if ($w_trechos[$i][35]>''||$w_trechos[$i][37]>''||$w_trechos[$i][39]>'') {
              $l_html.=chr(13).'     <tr><td colspan="6">';
              if ($w_trechos[$i][35]>'') {
                if ($w_trechos[$i][8]!=$w_trechos[$i][34]) $l_html.=chr(13).'         <li>Quantidade calculada de diárias alterada de <b>'.formatNumber($w_trechos[$i][34],1).'</b> para <b>'.formatNumber($w_trechos[$i][8],2).'</b>. Motivo: <b>'.$w_trechos[$i][35].'</b></li>';
                else $l_html.=chr(13).'         <li>Observação sobre as hospedagens: <b>'.$w_trechos[$i][35].'</b></li>';
              }
              if ($w_trechos[$i][37]>'') {
                if ($w_trechos[$i][16]!=$w_trechos[$i][36]) $l_html.=chr(13).'         <li>Quantidade calculada de hospedagens alterada de <b>'.formatNumber($w_trechos[$i][36],1).'</b> para <b>'.formatNumber($w_trechos[$i][16],1).'</b>. Motivo: <b>'.$w_trechos[$i][37].'</b></li>';
                else  $l_html.=chr(13).'         <li>Observação sobre a hospedagem: <b>'.$w_trechos[$i][37].'</b></li>';
              }
              if ($w_trechos[$i][39]>'') {
                if ($w_trechos[$i][21]!=$w_trechos[$i][38]) $l_html.=chr(13).'         <li>Quantidade calculada de diárias de veículo alterada de <b>'.formatNumber($w_trechos[$i][38],1).'</b> para <b>'.formatNumber($w_trechos[$i][21],1).'</b>. Motivo: <b>'.$w_trechos[$i][39].'</b></li>';
                else $l_html.=chr(13).'         <li>Observação sobre a locação: <b>'.$w_trechos[$i][39].'</b></li>';
              }
            }
          }
          $i += 1;
        }
        if ($w_complemento_qtd > 0) {
          $l_html.=chr(13) . '     <tr valign="top">';
          $l_html.=chr(13) . '       <td colspan="4" align="right">Complemento de diárias ('.$w_complemento_moeda.')&nbsp;&nbsp;&nbsp;</td>';
          $l_html.=chr(13) . '       <td align="right">' . formatNumber($w_complemento_qtd,2) . '</td>';
          $l_html.=chr(13) . '       <td align="right">' . formatNumber($w_complemento_base) . '</td>';
          $l_html.=chr(13) . '       <td align="right">' . formatNumber($w_complemento_valor) . '</td>';
          $w_tot_diaria_P[$w_complemento_moeda] += $w_complemento_valor;
        }
        $l_html.=chr(13).'     <tr bgcolor="'.$conTrBgColor.'"><td colspan="7" align="center"><b>TOTAL DIÁRIAS:';
        foreach($w_tot_diaria_P as $k => $v) {
          $l_html.=chr(13).'       &nbsp;&nbsp;&nbsp;&nbsp;'.$k.' '.formatNumber($v);
        }
        $l_html.=chr(13).'     </b></td></tr>';
        $l_html.=chr(13).'        </table></td></tr>';
      }    
    }
    $w_diferenca = false;
    
    // Dados da prestação de contas
    if ((($w_cliente==10135 && $w_or_tramite>=10) || ($w_cliente==17305 && $w_or_tramite>=6)) && f($RS,'cumprimento')!='N') {
      // Acerto de contas da viagem
      if($l_diaria=='S' && (is_array($w_tot_diaria_P) || $w_financeiro) && (($w_cliente==10135 && $w_or_tramite>=10) || ($w_cliente==17305 && $w_or_tramite>=6)) && (is_array($w_tot_diaria_P) || f($RS,'cumprimento')=='C')) {
        // Garante que os arrays dos pagamentos previsto e realizado têm as mesmas moedas.
        if (count($w_tot_diaria_S)>0) {
          foreach($w_tot_diaria_S as $k => $v) {
            if (!isset($w_tot_diaria_P[$k])) {
              $w_tot_diaria_P[$k] = '0';
            }
          }
          ksort($w_tot_diaria_P);
        }
        if (count($w_tot_diaria_P)>0) {
          foreach($w_tot_diaria_P as $k => $v) {
            if (!isset($w_tot_diaria_S[$k])) {
              $w_tot_diaria_S[$k] = '0';
            }
          }
          ksort($w_tot_diaria_S);
        }
      
        $l_html.=chr(13).'      <tr><td colspan="14"><br /><font size="2"><b>DIFERENÇA DE DIÁRIAS</b></font><hr NOSHADE color=#000000 SIZE=1 /></td></tr>';
        $l_html.=chr(13).'      <tr><td colspan="14">';
        $l_html.=chr(13).'        <table border="1" bordercolor="#00000">';
        $l_html.=chr(13).'          <tr bgcolor="'.$conTrBgColor.'" align="center"><td><b><font SIZE=2>VALOR</font></b></td>';
        foreach($w_tot_diaria_S as $k => $v) $l_html.=chr(13).'       <td><b><font SIZE=2>'.$k.'</font></b></td>';
        
        $l_html.=chr(13).'       </tr>';
        $l_html.=chr(13).'       <tr align="RIGHT"><td><font SIZE=2>Devido</font></td>';
        if (is_array($w_tot_diaria_P)) {
          foreach($w_tot_diaria_P as $k => $v) {
              $l_html.=chr(13).'       <td><font SIZE=2>'.formatNumber($v).'</font></td>';
          }
        } else {
          foreach($w_tot_diaria_S as $k => $v) {
            $l_html.=chr(13).'       <td><font SIZE=2>0,00</font></td>';
          }
        }
        $l_html.=chr(13).'       </tr>';
        $l_html.=chr(13).'       <tr align="RIGHT"><td><font SIZE=2>Recebido</font></td>';
        foreach($w_tot_diaria_S as $k => $v) {
            $l_html.=chr(13).'       <td><font SIZE=2>'.formatNumber($w_tot_diaria_S[$k]).'</font></td>';
        }
        $l_html.=chr(13).'       </tr>';
        $l_html.=chr(13).'       <tr bgcolor="'.$conTrBgColor.'" align="RIGHT"><td><b><font SIZE=2>Diferença</font></b></td>';
        foreach($w_tot_diaria_S as $k => $v) {
          $l_html.=chr(13).'       <td><b><font SIZE=2>'.formatNumber($w_tot_diaria_P[$k]-$v).'</font></b></td>';
          if (($w_tot_diaria_P[$k]-$v)!=0) $w_diferenca = true;
        }
        $l_html.=chr(13).'     </tr></table></td></tr>';
      }
      
      $l_html.=chr(13).'      <tr><td colspan="14"><br /><font size="2"><b>PRESTAÇÃO DE CONTAS</b></font><hr NOSHADE color=#000000 SIZE=1 /></td></tr>';
      $l_html.=chr(13).'      <tr><td valign="top" colspan="14"><table border=0 width="100%" cellspacing=0>';
      $l_html.=chr(13).'      <tr><td width="30%"><b>Alteração no roteiro/horários:</b></td><td colspan="5">'.f($RS,'nm_cumprimento').'</td></tr>';
      if (f($RS,'cumprimento')=='P') {
        $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Motivo da alteração:</b></td><td colspan="12">'.nvl(CRLF2BR(f($RS,'nota_conclusao')),'---').'</td></tr>';
      } elseif (f($RS,'cumprimento')=='C') {
        $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Motivo do cancelamento:</b></td><td colspan="12">'.nvl(CRLF2BR(f($RS,'nota_conclusao')),'---').'</td></tr>';
      } 
      if ($w_diferenca && $w_financeiro) {
        $l_html.=chr(13).'      <tr><td width="30%"><b>Diferença de diárias:</b></td><td colspan="12">';
        foreach($w_tot_diaria_S as $k => $v) {
          $l_html.=$k.' '.formatNumber($w_tot_diaria_P[$k]-$v).'&nbsp;&nbsp;&nbsp;';
        }
        $l_html.='</td>';
      }
      $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Reembolso ao beneficiário:</b></td><td align="left">';
      if (f($RS,'reembolso')=='N') {
        $l_html.='R$ 0,00</td>';
      } else {
        // Valores a serem reembolsados
        $sql = new db_getPD_Reembolso; $RS_Reembolso = $sql->getInstanceOf($dbms,$l_chave,null,null,null);
        $RS_Reembolso = SortArray($RS_Reembolso,'sg_moeda','asc');
        
        $l_html.=chr(13).'      <table border="1" bordercolor="#00000">';
        $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
        $l_html.=chr(13).'          <td colspan=3><b>Solicitação</b></td>';
        $l_html.=chr(13).'          <td colspan=2><b>Autorização</b></td>';
        $l_html.=chr(13).'        </tr>';
        $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center" valign="top">';
        $l_html.=chr(13).'          <td><b>Moeda</b></td>';
        $l_html.=chr(13).'          <td><b>Valor</b></td>';
        $l_html.=chr(13).'          <td><b>Justificativa</b></td>';
        $l_html.=chr(13).'          <td><b>Valor</b></td>';
        $l_html.=chr(13).'          <td><b>Observação</b></td>';
        $l_html.=chr(13).'        </tr>';
        if (count($RS_Reembolso)<=0) {
          $l_html.=chr(13).'      <tr><td colspan=8 align="center"><font color="#BC3131"><b>VALORES NÃO INFORMADOS.</b></b></td></tr>';
        } else {
          foreach($RS_Reembolso as $row) {
            $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
            $l_html.=chr(13).'      <tr valign="top">';
            $l_html.=chr(13).'        <td>'.f($row,'sg_moeda').' ('.f($row,'nm_moeda').')</td>';
            $l_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'valor_solicitado')).'</td>';
            $l_html.=chr(13).'        <td>'.crlf2br(f($row,'justificativa')).'</td>';
            if ((($w_cliente==10135 && $w_or_tramite<=11) || ($w_cliente==17305 && $w_or_tramite<=6))) {
              // No trâmite de prestação de contas
              $l_html.=chr(13).'        <td align="center" colspan="2">&nbsp;</td>';
            } elseif ((($w_cliente==10135 && $w_or_tramite==12) || ($w_cliente==17305 && $w_or_tramite==7)) && f($row,'valor_autorizado')==0 && f($row,'observacao')=='') {
              // No trâmite de verificação da prestação de contas mas sem valor informado.
              $l_html.=chr(13).'        <td align="center" colspan="2">Em análise</td>';
            } else {
              // No trâmite de verificação da prestação de contas e com valor informado, ou em trâmite posterior a PC
              $l_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'valor_autorizado')).'</td>';
              $l_html.=chr(13).'        <td>'.nvl(crlf2br(f($row,'observacao')),'---').'</td>';
            }
            $l_html.=chr(13).'        </td>';
            $l_html.=chr(13).'      </tr>';
          }
        }
        $l_html.=chr(13).'    </table>';
      }
      if (f($RS,'ressarcimento')=='S') {
        $l_html.=chr(13).'      <tr><td width="30%"><b>Data da devolução:</b></td><td colspan="12" align="left">'.formataDataEdicao(f($RS,'ressarcimento_data')).'</td></tr>';
        $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Código do depósito identificado:</b></td><td colspan="12">'.nvl(f($RS,'deposito_identificado'),'---').'</td></tr>';
      }
      $l_html.=chr(13).'      <tr><td width="30%"><b>Valor devolvido:</b></td><td colspan="12" align="left">R$ '.formatNumber(f($RS,'ressarcimento_valor')).'</td></tr>';
      if (f($RS,'ressarcimento')=='S') {
        $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Observação:</b></td><td colspan="12">'.nvl(CRLF2BR(f($RS,'ressarcimento_observacao')),'---').'</td></tr>';
      } 
      if (f($RS,'cumprimento')!='C') $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Relatório de viagem:</b></td><td colspan="12">'.nvl(CRLF2BR(f($RS,'relatorio')),'---').'</td></tr>';

      if (nvl(f($RS,'sq_relatorio_viagem'),'')!='') {
        $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Anexo do relatório:</b></td><td colspan="12">'.LinkArquivo('HL',$w_cliente,f($RS,'sq_relatorio_viagem'),'_blank','Clique para exibir o arquivo em outra janela.',f($RS,'nm_arquivo'),null).'</td>';
      }else{
            // Arquivos vinculados
        $sql = new db_getSolicRelAnexo;
        $RS1 = $sql->getInstanceOf($dbms, $l_chave, null, $w_cliente, '1');
        $RS1 = SortArray($RS1, 'nome', 'asc');
        if (count($RS1) > 0) {
          $l_html.=chr(13) . '      <tr valign="top"><td width="30%"><b>Anexos:</b></td>';
//        $l_html.=chr(13).'      <br /><font size="2"><b>ARQUIVOS ANEXADOS</b></font><hr NOSHADE color=#000000 SIZE=1 /></td></tr>';
          $l_html.=chr(13) . '        <td colspan="12">';
          $w_cor = $w_TrBgColor;
          $l_html . '<ul>';
          foreach ($RS1 as $row) {
            if ($l_tipo != 'WORD') {
              $l_html.=chr(13) . '<li>' . LinkArquivo('HL', $w_cliente, f($row, 'chave_aux'), '_blank', 'Clique para exibir o arquivo em outra janela.', f($row, 'nome'), null);
            } else {
              $l_html.=chr(13) . '<li>' . f($row, 'nome');
            }
            $l_html.=chr(13) . '(' . (round(f($row, 'tamanho') / 1024, 1)) . 'KB)</li>';
          }
          $l_html . '</ul>';
        }
      }

      if (nvl(f($RS,'sq_arquivo_comprovante'),'')!='') {
        $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Comprovantes de viagem:</b></td><td colspan="12">'.LinkArquivo('HL',$w_cliente,f($RS,'sq_arquivo_comprovante'),'_blank','Clique para exibir o arquivo em outra janela.',f($RS,'nm_arquivo_comprovante'),null).'</td>';
      }else{
      // Arquivos vinculados
        $sql = new db_getSolicRelAnexo;
        $RS1 = $sql->getInstanceOf($dbms, $l_chave, null, $w_cliente, '2');
        $RS1 = SortArray($RS1, 'nome', 'asc');
        if (count($RS1) > 0) {
          $l_html.=chr(13) . '      <tr valign="top"><td width="30%"><b>Comprovantes de viagem:</b></td>';
          $l_html.=chr(13) . '        <td colspan="12">';
          $w_cor = $w_TrBgColor;
          $l_html . '<ul>';
          foreach ($RS1 as $row) {
            if ($l_tipo != 'WORD') {
              $l_html.=chr(13) . '<li>' . LinkArquivo('HL', $w_cliente, f($row, 'chave_aux'), '_blank', 'Clique para exibir o arquivo em outra janela.', f($row, 'nome'), null);
            } else {
              $l_html.=chr(13) . '<li>' . f($row, 'nome');
            }
            $l_html.=chr(13) . '(' . (round(f($row, 'tamanho') / 1024, 1)) . 'KB)</li>';
          }
          $l_html . '</ul>';
        }
      }
    }

    // Cotação de passagens
    if($l_deslocamento=='S' && $w_or_tramite>=2 && ($w_cliente==17305 || ($w_cliente!=17305 && $w_internacional=='S'))) {
        $l_html.=chr(13).'      <tr><td colspan="14"><br /><font size="2"><b>COTAÇÃO</b></font><hr NOSHADE color=#000000 SIZE=1 /></td></tr>';
      if ($w_passagem=='S') {
        $l_html.=chr(13).'      <tr><td width="30%"><b>Valor:</b></td><td colspan="12" align="left">'.f($RS,'sb_moeda_cotacao').' '.formatNumber(f($RS,'cotacao_valor')).'</td></tr>';
        $l_html.=chr(13).'      <tr valign="top"><td width="30%"><b>Observação:</b></td><td colspan="12">'.nvl(crlf2br(f($RS,'cotacao_observacao')),'---').'</td>';
      } else {
        $l_html.=chr(13).'      <tr><td colspan="2"><b>Foi indicado que esta viagem não terá gastos com bilhetes / passagens.</b></td></tr>';
      }
    }

    // Bilhete de passagem
    $sql = new db_getPD_Bilhete; $RS1 = $sql->getInstanceOf($dbms,$l_chave,null,null,null,null,null,null,null);
    $RS1 = SortArray($RS1,'data','asc', 'nm_cia_transporte', 'asc', 'numero', 'asc');
    if ((($w_cliente==10135 && $w_or_tramite>5) || ($w_cliente==17305 && $w_or_tramite>=3)) || count($RS1)>0) {
      if (count($RS1)>0) {
        $l_html.=chr(13).'      <tr><td colspan="14"><br /><font size="2"><b>BILHETES EMITIDOS</b></font><hr NOSHADE color=#000000 SIZE=1 /></td></tr>';
        $l_html.=chr(13).'      <tr><td colspan="14">';
        $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
        $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
        $l_html.=chr(13).'          <td rowspan=2><b>Emissão</b></td>';
        $l_html.=chr(13).'          <td rowspan=2><b>Cia.</b></td>';
        $l_html.=chr(13).'          <td rowspan=2><b>Número</b></td>';
        $l_html.=chr(13).'          <td rowspan=2><b>Trecho</b></td>';
        $l_html.=chr(13).'          <td rowspan=2><b>RLOC</b></td>';
        $l_html.=chr(13).'          <td rowspan=2><b>Classe</b></td>';
        $l_html.=chr(13).'          <td colspan=5><b>Valores</b></td>';
        $l_html.=chr(13).'          <td colspan=3><b>Faturamento</b></td>';
        $l_html.=chr(13).'        </tr>';
        $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
        $l_html.=chr(13).'          <td><b>Valor com desconto</b></td>';
        $l_html.=chr(13).'          <td><b>Valor Bilhete</b></td>';
        $l_html.=chr(13).'          <td><b>Embarque</b></td>';
        $l_html.=chr(13).'          <td><b>Taxas</b></td>';
        $l_html.=chr(13).'          <td><b>Total</b></td>';
        $l_html.=chr(13).'          <td><b>Fatura</b></td>';
        $l_html.=chr(13).'          <td nowrap><b>$ Desconto</b></td>';
        $l_html.=chr(13).'          <td nowrap><b>$ Faturado</b></td>';
        $l_html.=chr(13).'        </tr>';
        $w_cor=$conTrBgColor;
        $i             = 1;
        $w_tot_bil     = 0;
        $w_tot_pta     = 0;
        $w_tot_tax     = 0;
        $w_tot_bilhete = 0;
        $w_tot_desconto = 0;
        $w_tot_fatura   = 0;
        $w_total       = 0;
        foreach ($RS1 as $row) {
          $w_tot_bilhete  = f($row,'valor_bilhete_cheio')+f($row,'valor_pta')+f($row,'valor_taxa_embarque');
          $w_tot_cheio    += f($row,'valor_bilhete_cheio');
          $w_tot_bil      += f($row,'valor_bilhete');
          $w_tot_pta      += f($row,'valor_pta');
          $w_tot_tax      += f($row,'valor_taxa_embarque');
          $w_tot_desconto += nvl(f($row,'valor_desconto'),0);
          $w_tot_fatura   += nvl(f($row,'vl_bilhete_fatura'),0);
          $w_total        += $w_tot_bilhete;
          $w_rowspan      = ((nvl(f($row,'observacao'),'')=='') ? 1 : 2);
          $l_html.=chr(13).'        <tr valign="middle">';
          $l_html.=chr(13).'           <td align="center" rowspan="'.$w_rowspan.'">'.FormataDataEdicao(f($row,'data'),5).'</td>';
          $l_html.=chr(13).'           <td rowspan="'.$w_rowspan.'">'.f($row,'nm_cia_transporte').'</td>';
          $l_html.=chr(13).'           <td rowspan="'.$w_rowspan.'">'.f($row,'numero').'</td>';
          $l_html.=chr(13).'           <td>'.f($row,'trecho').'</td>';
          $l_html.=chr(13).'           <td>'.nvl(f($row,'rloc'),'&nbsp;').'</td>';
          $l_html.=chr(13).'           <td align="center">'.f($row,'classe').'</td>';
          $l_html.=chr(13).'           <td align="right">'.formatNumber(f($row,'valor_bilhete_cheio')).'</td>';
          $l_html.=chr(13).'           <td align="right">'.formatNumber(f($row,'valor_bilhete')).'</td>';
          $l_html.=chr(13).'           <td align="right">'.formatNumber(f($row,'valor_taxa_embarque')).'</td>';
          $l_html.=chr(13).'           <td align="right">'.formatNumber(f($row,'valor_pta')).'</td>';
          $l_html.=chr(13).'           <td align="right">'.formatNumber($w_tot_bilhete).'</td>';
          $l_html.=chr(13).'           <td align="center">'.nvl(f($row,'nr_fatura'),'&nbsp;').'</td>';
          $l_html.=chr(13).'           <td align="right">'.((nvl(f($row,'nr_fatura'),'')=='') ? '&nbsp;' : formatNumber(f($row,'valor_desconto'))).'</td>';
          $l_html.=chr(13).'           <td align="right">'.((nvl(f($row,'nr_fatura'),'')=='') ? '&nbsp;' : formatNumber(f($row,'vl_bilhete_fatura'))).'</td>';
          $l_html.=chr(13).'        </tr>';
          if (nvl(f($row,'observacao'),'')!='') $l_html.=chr(13).'        <tr><td colspan=8>Observação: '.crlf2br(f($row,'observacao')).'</td></tr>';
        } 
        $l_html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'" valign="top">';
        $l_html.=chr(13).'        <td align="right" colspan="6"><b>TOTAIS</b></td>';
        $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_tot_cheio).'</b></td>';
        $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_tot_bil).'</b></td>';
        $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_tot_tax).'</b></td>';
        $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_tot_pta).'</b></td>';
        $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_total).'</b></td>';
        if ($w_tot_fatura==0) {
          $l_html.=chr(13).'        <td colspan=3>&nbsp;</td>';
        } else {
          $l_html.=chr(13).'        <td>&nbsp;</td>';
          $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_tot_desconto).'</b></td>';
          $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_tot_fatura).'</b></td>';
        }
        $l_html.=chr(13).'      </tr>';
        $l_html.=chr(13).'         </table></td></tr>';
      }

      // Prepara array de impressão dos dados orçamentários
      $sql = new db_getPD_Financeiro; $RS_Financ = $sql->getInstanceOf($dbms,$w_cliente,null,$l_chave,null,null,null,null,null,null,null,null,null,'ORCAM_PREV');
      $RS_Financ = SortArray($RS_Financ,'cd_rubrica','asc','sg_moeda','asc');
      if (count($RS_Financ)>0) {
        $i       = -1;
        $w_atual = '';
        foreach($RS_Financ as $row) {
          if ($w_atual!=f($row,'cd_rubrica')) {
            $w_atual = f($row,'cd_rubrica');
            $i++;
          }
          $w_orc[$i]['sq_rubrica'] = f($row,'sq_rubrica');
          $w_orc[$i]['cd_rubrica'] = f($row,'cd_rubrica');
          $w_orc[$i]['nm_rubrica'] = f($row,'nm_rubrica');
          $w_orc[$i]['moeda'][f($row,'sg_moeda')] = f($row,'valor');
          if (!is_array($w_orc_moeda[f($row,'sg_moeda')])) {
            $w_orc_moeda[f($row,'sg_moeda')] = f($row,'valor');
          } else {
            $w_orc_moeda[f($row,'sg_moeda')] += f($row,'valor');
          }
        }
      }
      // Prepara array de impressão dos dados financeiros
      $sql = new db_getPD_Financeiro; $RS_Financ = $sql->getInstanceOf($dbms,$w_cliente,null,$l_chave,null,null,null,null,null,null,null,null,null,'FINANC_PREV');
      $RS_Financ = SortArray($RS_Financ,'nm_lancamento','asc','sg_moeda','asc');
      if (count($RS_Financ)>0) {
        $i       = -1;
        $w_atual = '';
        foreach($RS_Financ as $row) {
          if ($w_atual!=f($row,'nm_lancamento')) {
            $w_atual = f($row,'nm_lancamento');
            $i++;
          }
          $w_fin[$i]['sq_lancamento'] = f($row,'sq_lancamento');
          $w_fin[$i]['nm_lancamento'] = f($row,'nm_lancamento');
          $w_fin[$i]['moeda'][f($row,'sg_moeda')] = f($row,'valor');
          if (!is_array($w_fin_moeda[f($row,'sg_moeda')])) {
            $w_fin_moeda[f($row,'sg_moeda')] = f($row,'valor');
          } else {
            $w_fin_moeda[f($row,'sg_moeda')] += f($row,'valor');
          }
        }
      }

      if (count($w_orc)>0 || count($w_fin)>0) {
        $l_html.=chr(13).'      <tr><td colspan="14"><br /><font size="2"><b>PREVISÃO ORÇAMENTÁRIA-FINANCEIRA</b></font><hr NOSHADE color=#000000 SIZE=1 /></td></tr>';
        $l_html.=chr(13).'      <tr><td colspan="14"><table width="100%" border=0 cellpadding=0 cellspacing=5><tr valign="top">';
        
        // Exibe previsão orçamentária
        $l_html.=chr(13).'        <td align="center" width="50%" colspan="7"><table width=97%  border="1" bordercolor="#00000">';
        $l_html.=chr(13).'          <tr align="center">';
        if (count($w_orc_moeda)==1) {
          $l_html.=chr(13).'          <td colspan="2" bgColor="#f0f0f0"><b>ORÇAMENTÁRIO</b></td>';
          $l_html.=chr(13).'          </tr>';
          $l_html.=chr(13).'          <tr align="center">';
          $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Rubrica</b></td>';
          $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Valor</b></td>';
        } else {
          $l_html.=chr(13).'          <td colspan="'.(1+count($w_orc_moeda)).'" bgColor="#f0f0f0"><b>ORÇAMENTÁRIO</b></td>';
          $l_html.=chr(13).'          </tr>';
          $l_html.=chr(13).'          <tr align="center">';
          $l_html.=chr(13).'          <td rowspan="2" bgColor="#f0f0f0"><b>Rubrica</b></td>';
          $l_html.=chr(13).'          <td colspan="'.count($w_orc_moeda).'" bgColor="#f0f0f0"><b>Valor</b></td>';
          $l_html.=chr(13).'          </tr>';
          $l_html.=chr(13).'          <tr align="center">';
          foreach($w_orc_moeda as $k=>$v) $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>'.$k.'</b></td>';
          $l_html.=chr(13).'          </tr>';
        }
        $l_html.=chr(13).'          </tr>';
        $w_cor=$w_TrBgColor;
        $i = 0;
        while ($i<count($w_orc)) {
          $l_html.=chr(13).'      <tr valign="top">';
          $l_html .= chr(13).'          <td>'.$w_orc[$i]['cd_rubrica'].' - '.$w_orc[$i]['nm_rubrica'].'&nbsp';
          foreach($w_orc_moeda as $k=>$v) {
            $l_html.=chr(13).'          <td align="right">'.formatNumber($w_orc[$i]['moeda'][$k]).'</td>';
            $w_tot[$k] += $w_orc[$i]['moeda'][$k];
          }
          $l_html.=chr(13).'      </tr>';
          $i++;
        } 
        $l_html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'" valign="top">';
        $l_html.=chr(13).'        <td align="right"><b>TOTAIS</b></td>';
        foreach($w_orc_moeda as $k=>$v) $l_html.=chr(13).'          <td align="right"><b>'.formatNumber($w_tot[$k]).'</b></td>';
        $l_html.=chr(13).'      </tr>';
        $l_html.=chr(13).'         </table></td>';
        unset($w_tot);

        // Exibe previsão financeira
        $l_html.=chr(13).'        <td align="center" width="50%" colspan="7"><table width=97%  border="1" bordercolor="#00000">';
        $l_html.=chr(13).'          <tr align="center">';
        if (count($w_fin_moeda)==1) {
          $l_html.=chr(13).'          <td colspan="2" bgColor="#f0f0f0"><b>FINANCEIRO</b></td>';
          $l_html.=chr(13).'          </tr>';
          $l_html.=chr(13).'          <tr align="center">';
          $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Tipo de Lançamento</b></td>';
          $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Valor</b></td>';
        } else {
          $l_html.=chr(13).'          <td colspan="'.(1+count($w_fin_moeda)).'" bgColor="#f0f0f0"><b>FINANCEIRO</b></td>';
          $l_html.=chr(13).'          </tr>';
          $l_html.=chr(13).'          <tr align="center">';
          $l_html.=chr(13).'          <td rowspan="2" bgColor="#f0f0f0"><b>Classificação</b></td>';
          $l_html.=chr(13).'          <td colspan="'.count($w_fin_moeda).'" bgColor="#f0f0f0"><b>Valor</b></td>';
          $l_html.=chr(13).'          </tr>';
          $l_html.=chr(13).'          <tr align="center">';
          foreach($w_fin_moeda as $k=>$v) $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>'.$k.'</b></td>';
          $l_html.=chr(13).'          </tr>';
        }
        $l_html.=chr(13).'          </tr>';
        $w_cor=$w_TrBgColor;
        $i = 0;
        while ($i<count($w_fin)) {
          $l_html.=chr(13).'      <tr valign="top">';
          $l_html .= chr(13).'          <td>'.$w_fin[$i]['nm_lancamento'].'&nbsp';
          foreach($w_fin_moeda as $k=>$v) {
            $l_html.=chr(13).'          <td align="right">'.formatNumber($w_fin[$i]['moeda'][$k]).'</td>';
            $w_tot[$k] += $w_fin[$i]['moeda'][$k];
          }
          $l_html.=chr(13).'      </tr>';
          $i++;
        } 
        $l_html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'" valign="top">';
        $l_html.=chr(13).'        <td align="right"><b>TOTAIS</b></td>';
        foreach($w_fin_moeda as $k=>$v) $l_html.=chr(13).'          <td align="right"><b>'.formatNumber($w_tot[$k]).'</b></td>';
        $l_html.=chr(13).'      </tr>';
        $l_html.=chr(13).'         </table></td></tr>';
        $l_html.=chr(13).'       </table></td></tr>';
      } 
      
    } 
    
    // Execução financeira
    if ($w_financeiro) {
      $RSF = SortArray($RSF,'inclusao','asc', 'codigo_interno', 'asc');
      $l_html.=chr(13).'      <tr><td colspan="14"><br /><font size="2"><b>EXECUÇÃO FINANCEIRA</b></font><hr NOSHADE color=#000000 SIZE=1 /></td></tr>';
      $l_html.=chr(13).'      <tr><td colspan="14"><table width=100%  border="1" bordercolor="#00000">';
      $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $l_html.=chr(13).'          <td><b>Código</b></td>';
      $l_html.=chr(13).'          <td><b>Histórico</b></td>';
      $l_html.=chr(13).'          <td><b>Valor</b></td>';
      $l_html.=chr(13).'          <td><b>Situação atual</b></td>';
      $l_html.=chr(13).'          <td><b>Data Pagamento</b></td>';
      $l_html.=chr(13).'        </tr>';
      $w_cor=$conTrBgColor;
      $i             = 1;
      $w_total       = 0;
      foreach ($RSF as $row) {
        $soma = false;
        if ($w_cliente==17305 || f($row,'sg_tramite')=='AT' || strpos(f($row,'descricao'),'(REAL)')!==false  || strpos(f($row,'descricao'),'(')===false) $soma = true;
        if ($soma) {
          if (f($row,'sigla')=='FNREVENT') {
            $w_total       -= f($row,'valor');
          } else {
            $w_total       += f($row,'valor');
          }
          if ((nvl(f($row,'sb_moeda'),'')!='')) {
            if (nvl($w_totais[f($row,'sb_moeda')],'')=='') {
              // Se nao existe valor para a moeda do lançamento
              if (f($row,'sigla')=='FNREVENT') {
                $w_totais[f($row,'sb_moeda')] = -1 * f($row,'valor');
              } else {
                $w_totais[f($row,'sb_moeda')] = f($row,'valor');
              }
            } else {
              if (f($row,'sigla')=='FNREVENT') {
                $w_totais[f($row,'sb_moeda')] -= f($row,'valor');
              } else {
                $w_totais[f($row,'sb_moeda')] += f($row,'valor');
              }
            }
          }
        }
        $l_html.=chr(13).'        <tr valign="middle">';
        if($l_tipo!='WORD') $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($row,'sq_siw_solicitacao'),f($row,'dados_solic'),'N');
        else                $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($row,'sq_siw_solicitacao'),f($row,'dados_solic'),'N','S');
        if (nvl(f($row,'sq_solic_vinculo'),f($row,'sq_siw_solicitacao'))!=f($row,'sq_siw_solicitacao')) {
          if($l_tipo!='WORD') $l_html.=chr(13).' ('.exibeSolic($w_dir,f($row,'sq_solic_vinculo'),f($row,'dados_solic_vinculo'),'N').')';
          else                $l_html.=chr(13).' ('.exibeSolic($w_dir,f($row,'sq_solic_vinculo'),f($row,'dados_solic_vinculo'),'N','S').')';
        }
        $l_html.=chr(13).'           <td>'.f($row,'descricao').'</td>';
        if ($soma) {
          $l_html.=chr(13).'           <td align="right">'.((nvl(f($row,'sb_moeda'),'')!='') ? f($row,'sb_moeda').' ' : '').formatNumber(f($row,'valor')).'</td>';
        } else {
          $l_html.=chr(13).'           <td align="right">&nbsp;</td>';
        }
        $l_html.=chr(13).'           <td>'.f($row,'nm_tramite').'</td>';
        $l_html.=chr(13).'           <td align="center">'.nvl(formataDataEdicao(f($row,'conclusao')),'&nbsp;').'</td>';
        $l_html.=chr(13).'        </tr>';
      } 
      if (is_array($w_totais)) {
        // Se há mais de uma moeda de pagamento
        $l_html.=chr(13).'     <tr bgcolor="'.$conTrBgColor.'"><td colspan="5" align="center"><b>TOTAIS:';
        foreach($w_totais as $k => $v) {
          $l_html.=chr(13).'       &nbsp;&nbsp;&nbsp;&nbsp;'.$k.' '.formatNumber($v);
        }
        $l_html.=chr(13).'      </tr>';
        unset($w_totais);
      } else {
        // Se há apenas uma moeda de pagamento
        $l_html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'" valign="top">';
        $l_html.=chr(13).'        <td align="right" colspan="2"><b>TOTAL</b></td>';
        $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_total).'</b></td>';
        $l_html.=chr(13).'        <td align="right" colspan="2">&nbsp;</td>';
        $l_html.=chr(13).'      </tr>';
      }
      $l_html.=chr(13).'         </table></td></tr>';
    }

    // Exibe outros valores associados à viagem
    $sql = new db_getPD_Fatura; $RS1 = $sql->getInstanceOf($dbms,$w_cliente,null,null, null, null, null, null, $l_chave, null, null,
      null, null, null, null, null, null, null, null, null, 'OUTROS');
    $RS1 = SortArray($RS1,'nr_fatura','asc','nm_tipo_reg','asc', 'inicio_reg', 'asc', 'fim_reg', 'asc');
    if (count($RS1)>0) {
      $l_html.=chr(13).'      <tr><td colspan="14"><br /><font size="2"><b>DESPESAS COM HOSPEDAGENS, LOCAÇÃO DE VEÍCULOS E SEGUROS DE VIAGEM</b></font><hr NOSHADE color=#000000 SIZE=1 /></td></tr>';
      $l_html.=chr(13).'      <tr><td colspan="14">';
      $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
      $l_html.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $l_html.=chr(13).'          <td><b>Fatura</b></td>';
      $l_html.=chr(13).'          <td><b>Agência de Viagens</b></td>';
      $l_html.=chr(13).'          <td><b>Tipo</b></td>';
      $l_html.=chr(13).'          <td><b>Início</b></td>';
      $l_html.=chr(13).'          <td><b>Fim</b></td>';
      $l_html.=chr(13).'          <td><b>Valor</b></td>';
      $l_html.=chr(13).'          <td><b>Hotel/Locadora/Seguradora</b></td>';
      $l_html.=chr(13).'        </tr>';
      $w_cor=$conTrBgColor;
      $i             = 1;
      $w_total       = 0;
      foreach ($RS1 as $row) {
        $w_total       += f($row,'valor_reg');
        $l_html.=chr(13).'        <tr valign="middle">';
        $l_html.=chr(13).'           <td>'.f($row,'nr_fatura').'</td>';
        $l_html.=chr(13).'           <td>'.f($row,'nm_agencia_res').'</td>';
        $l_html.=chr(13).'           <td>'.f($row,'nm_tipo_reg').'</td>';
        $l_html.=chr(13).'           <td align="center">'.nvl(formataDataEdicao(f($row,'inicio_reg')),'&nbsp;').'</td>';
        $l_html.=chr(13).'           <td align="center">'.nvl(formataDataEdicao(f($row,'fim_reg')),'&nbsp;').'</td>';
        $l_html.=chr(13).'           <td align="right">'.formatNumber(f($row,'valor_reg')).'</td>';
        $l_html.=chr(13).'           <td>'.f($row,'nm_hotel').'</td>';
        $l_html.=chr(13).'        </tr>';
      } 
      $l_html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'" valign="top">';
      $l_html.=chr(13).'        <td align="right" colspan="5"><b>TOTAL</b></td>';
      $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_total).'</b></td>';
      $l_html.=chr(13).'        <td align="right" colspan="2">&nbsp;</td>';
      $l_html.=chr(13).'      </tr>';
      $l_html.=chr(13).'         </table></td></tr>';
    }

    // Arquivos vinculados
    $sql = new db_getSolicAnexo; $RS1 = $sql->getInstanceOf($dbms,$l_chave,null,$w_cliente);
    $RS1 = SortArray($RS1,'nome','asc');
    if (count($RS1)>0) {
      $l_html.=chr(13).'      <tr><td colspan="14"><br /><font size="2"><b>ARQUIVOS ANEXOS</b></font><hr NOSHADE color=#000000 SIZE=1 /></td></tr>';
      $l_html.=chr(13).'      <tr><td colspan="14" align="center">';
      $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
      $l_html.=chr(13).'          <tr align="center">';
      $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Título</b></td>';
      $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Descrição</b></td>';
      $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Tipo</b></td>';
      $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>KB</b></td>';
      $l_html.=chr(13).'          </tr>';
      $w_cor=$w_TrBgColor;
      foreach($RS1 as $row) {
        $l_html.=chr(13).'      <tr valign="top">';
        if ($l_tipo!='WORD') {
          $l_html.=chr(13).'        <td>'.LinkArquivo('HL',$w_cliente,f($row,'chave_aux'),'_blank','Clique para exibir o arquivo em outra janela.',f($row,'nome'),null).'</td>';
        } else {
          $l_html.=chr(13).'        <td>'.f($row,'nome').'</td>';
        } 
        $l_html.=chr(13).'        <td>'.Nvl(f($row,'descricao'),'---').'</td>';
        $l_html.=chr(13).'        <td>'.f($row,'tipo').'</td>';
        $l_html.=chr(13).'        <td align="right">'.(round(f($row,'tamanho')/1024,1)).'&nbsp;</td>';
        $l_html.=chr(13).'      </tr>';
      } 
      $l_html.=chr(13).'         </table></td></tr>';
    }

    /* Bloco obsoleto
    // Arquivos gerados para a PCD
    if ($l_anexo = 'S' && $w_or_tramite > 5) {
      $l_html.=chr(13).'      <tr><td colspan="14"><br /><font size="2"><b>ARQUIVOS</b></font><hr NOSHADE color=#000000 SIZE=1 /></td></tr>';
      $l_html.=chr(13).'      <tr><td colspan="14"><table width=100%  border="0" bordercolor="#00000">';
      $l_html.=chr(13).'        <tr><td><a target="Emissao" class="hl" title="Emitir autorização e proposta de concessão." href="'.$w_dir.$w_pagina.'Emissao&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.$l_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&SG='.$SG.'">Autorização para emissão de bilhetes</A>';
      $l_html.=chr(13).'        <tr><td><a target="Relatorio" class="hl" title="Emitir relatório para prestacao de contas." href="'.$w_dir.$w_pagina.'Prestacaocontas&R='.$w_pagina.$par.'&O=L&w_menu='.$w_menu.'&w_chave='.$l_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'">Relatório de viagem</A>';
      $l_html.=chr(13).'      </table></td></tr>';
    }
    */
  }
   
  // Se for envio, executa verificações nos dados da solicitação
  $w_erro = ValidaViagem($w_cliente,$l_chave,'PDGERAL',null,null,null,Nvl($w_tramite,0));
  if ($w_erro>'') {
    $l_html.=chr(13).'<tr bgcolor="'.$w_TrBgColor.'"><td colspan=14>&nbsp;</td></tr>';
    $l_html.=chr(13).'<tr bgcolor="'.$w_TrBgColor.'"><td colspan=14>';
    if (substr($w_erro,0,1)=='0') {
      $l_html.=chr(13).'  <font color="#BC3131"><b>ATENÇÃO:</b> As pendências abaixo devem ser resolvidas antes do encaminhamento para as fases posteriores à atual.</font>';
    } elseif (substr($w_erro,0,1)=='1') {
      $l_html.=chr(13).'  <font color="#BC3131"><b>ATENÇÃO:</b> As pendências abaixo devem ser resolvidas antes do encaminhamento para as fases posteriores à atual. Seu encaminhamento para fases posteriores à atual só pode ser feito por um gestor do sistema ou do módulo de viagens.</font>';
    } else {
      $l_html.=chr(13).'  <font color="#BC3131"><b>ATENÇÃO:</b> Foram identificados os alertas listados abaixo. Eles não impedem o encaminhamento para fases posteriores à atual, mas convém sua verificação.</font>';
    } 
    $l_html.=chr(13).'  <ul>'.substr($w_erro,1,1000).'</ul>';
    $l_html.=chr(13).'  </td></tr>';
  } 
  $l_html.=chr(13).'        </table></td></tr>';
  $l_html.=chr(13).'      </table>';

  // Encaminhamentos
  if ($l_ocorrencia=='S') {
    $l_html.=chr(13).'<tr bgcolor="'.$w_TrBgColor.'"><td colspan=14><table border=0 width="100%"><tr><td>';
    include_once($w_dir_volta.'funcoes/exibeLog.php');
    $l_html .= exibeLog($l_chave,$l_o,$l_usuario,$w_tramite_ativo,(($l_tipo=='WORD') ? 'WORD' : 'HTML'));
    $l_html.=chr(13).'        </table></td></tr>';
  } 
  $l_html.=chr(13).'    </table>';
  return $l_html;
}
?>