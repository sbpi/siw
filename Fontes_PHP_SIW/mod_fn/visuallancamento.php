<?
// =========================================================================
// Rotina de visualização dos dados do lançamento
// -------------------------------------------------------------------------
function VisualLancamento($v_chave,$l_O,$w_usuario,$l_P1,$l_P4) {
  extract($GLOBALS);
  if ($l_P4==1) $w_TrBgColor=''; else $w_TrBgColor=$conTrBgColor;
  $w_html='';
  // Recupera os dados do lançamento
  $RS = db_getSolicData::getInstanceOf($dbms,$v_chave,substr($SG,0,3).'GERAL');
  $w_tramite      = f($RS,'sq_siw_tramite');
  $w_SG           = f($RS,'sigla');
  $w_tipo_rubrica = f($RS,'tipo_rubrica');
  $w_qtd_rubrica  = nvl(f($RS,'qtd_rubrica'),0);
  $w_sq_projeto   = nvl(f($RS,'sq_projeto'),0);
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
    $w_html.=chr(13).'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
    $w_html.=chr(13).'<tr><td>';
    $w_html.=chr(13).'    <table width="99%" border="0">';
    $w_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    $w_html.=chr(13).'      <tr><td colspan="2" bgcolor="#f0f0f0"><font size="2"><b>'.strtoupper(f($RS,'nome')).' '.f($RS,'codigo_interno').' ('.$v_chave.')</b></td>';
    $w_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
     
    // Identificação do lançamento
    $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>IDENTIFICAÇÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';      // Identificação do lançamento
    $w_html .= chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
    if (Nvl(f($RS,'cd_acordo'),'')>'') {
      if (!($l_P1==4 || $l_P4==1)) {
        $w_html.=chr(13).'      <tr><td width="30%"><b>Contrato: </b></td>';
        $w_html.=chr(13).'        <td><A class="hl" HREF="mod_ac/contratos.php?par=Visual&O=L&w_chave='.f($RS,'sq_solic_pai').'&w_tipo=&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$l_P4.'&TP='.$TP.'&SG=GC'.substr($SG,2,1).'CAD" title="Exibe as informações do contrato." target="Contrato">'.f($RS,'cd_acordo').' ('.f($RS,'sq_solic_pai').')</a> </td></tr>';
      } else {
        $w_html.=chr(13).'      <tr><td><b>Contrato: </b></td>';
        $w_html.=chr(13).'        <td>'.f($RS,'cd_acordo').' ('.f($RS,'sq_solic_pai').') </td></tr>';
      }
    } 
    if (Nvl(f($RS,'nm_projeto'),'') > '') {
      if (!($l_P1==4 || $l_P4==1)){
        $w_html.=chr(13).'      <tr><td width="30%"><b>Projeto: </b></td>';
        $w_html.=chr(13).'        <td><A class="hl" HREF="projeto.php?par=Visual&O=L&w_chave='.f($RS,'sq_projeto').'&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$l_P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe as informações do projeto." target="Projeto">'.f($RS,'nm_projeto').'</a></td></tr>';
      } else {
        $w_html.=chr(13).'      <tr><td><b>Projeto: </b></td>';
        $w_html.=chr(13).'        <td>'.f($RS,'nm_projeto').'  ('.f($RS,'sq_solic_pai').')</td></tr>';
      }
    } 
    // Se a classificação foi informada, exibe.
    if (Nvl(f($RS,'sq_cc'),'')>'') {
      $w_html.=chr(13).'      <tr><td><b>Classificação: </b></td>';
      $w_html.=chr(13).'        <td>'.f($RS,'nm_cc').' </td></tr>';
    }
    $w_html.=chr(13).'      <tr><td><b>Tipo de lançamento: </b></td>';
    $w_html.=chr(13).'        <td>'.f($RS,'nm_tipo_lancamento').' </td></tr>';
    // Verifica o segmento do cliente    
    $RS1 = db_getCustomerData::getInstanceOf($dbms,$w_cliente); 
    $w_segmento = f($RS1,'segmento');
    if ($w_segmento=='Público') { 
      $w_html.=chr(13).'      <tr><td><b>Númenro do processo: </b></td>';
      $w_html.=chr(13).'        <td>'.nvl(f($RS,'processo'),'---').' </td></tr>';
    }   
    if (Nvl(f($RS,'tipo_rubrica'),'')>'') {
      $w_html.=chr(13).'      <tr><td><b>Tipo de movimentação: </b></td>';
      $w_html.=chr(13).'        <td>'.f($RS,'nm_tipo_rubrica').' </td></tr>';
      $w_html.=chr(13).'      <tr><td><b>Finalidade: </b></td>';
      $w_html.=chr(13).'        <td>'.CRLF2BR(f($RS,'descricao')).'</td></tr>';
    }
    if (!($l_P1==4 || $l_P4==1)){
      $w_html.=chr(13).'      <tr><td><b>Unidade responsável: </b></td>';
      $w_html.=chr(13).'        <td>'.ExibeUnidade($w_dir_volta,$w_cliente,f($RS,'nm_unidade_resp'),f($RS,'sq_unidade'),$TP).'</td></tr>';
    } else {
      $w_html.=chr(13).'      <tr><td><b>Unidade responsável: </b></td>';
      $w_html.=chr(13).'        <td>'.f($RS,'nm_unidade_resp').'</td></tr>';
    }
    $w_html.=chr(13).'          <tr><td><b>Forma de pagamento:</b></td>';
    $w_html.=chr(13).'            <td>'.f($RS,'nm_forma_pagamento').' </td></tr>';
    $w_html.=chr(13).'          <tr><td><b>Data prevista:</b></td>';
    $w_html.=chr(13).'            <td>'.FormataDataEdicao(f($RS,'vencimento')).' </td></tr>';
    $w_html.=chr(13).'          <tr><td><b>Valor:</b></td>';
    $w_html.=chr(13).'            <td>'.number_format(Nvl(f($RS,'valor'),0),2,',','.').' </td></tr>';
    // Dados da conclusão do projeto, se ela estiver nessa situação
    if (Nvl(f($RS,'conclusao'),'')>'' && Nvl(f($RS,'quitacao'),'')>'') {
      $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS DA LIQUIDAÇÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $w_html.=chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
      $w_html.=chr(13).'      <tr><td width="30%"><b>Data:</b></td>';
      $w_html.=chr(13).'        <td>'.FormataDataEdicao(f($RS,'quitacao')).' </td></tr>';
      if (Nvl(f($RS,'codigo_deposito'),'')>''){
        $w_html.=chr(13).'    <tr><td><b>Código do depósito:</b></td>';
        $w_html.=chr(13).'      <td>'.f($RS,'codigo_deposito').' </td></tr>';
      }
      $w_html.=chr(13).'      <tr><td><b>Observação:</b></td>';
      $w_html.=chr(13).'      <td>'.CRLF2BR(Nvl(f($RS,'observacao'),'---')).' </td></tr>';
      $w_html.=chr(13).'      </table>';
    } 
    $w_html.=chr(13).'          </table></td></tr>';    
    // Outra parte
    $RS_Query = db_getBenef::getInstanceOf($dbms,$w_cliente,Nvl(f($RS,'pessoa'),0),null,null,null,Nvl(f($RS,'sq_tipo_pessoa'),0),null,null);
    foreach ($RS_Query as $row) {$RS_Query = $row; break;}
    $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>OUTRA PARTE<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    if (count($RS_Query)<=0) {
      $w_html.=chr(13).'      <tr><td colspan=2 align=center><font size=1>Outra parte não informada';
    } else {
      $w_html.=chr(13).'      <tr><td><table border=0 width="100%" cellspacing=0>';
      $w_html.=chr(13).'      <tr><td width="30%"><font size=1><b>Pessoa: </b></td>';
      $w_html.=chr(13).'      <td>'.f($RS_Query,'nm_pessoa').' ('.f($RS_Query,'nome_resumido').')</td></tr>';
      if (Nvl(f($RS,'sq_tipo_pessoa'),0)==1) {
        $w_html.=chr(13).'      <tr><td><font size=1><b>CPF:</b></td>';     
        $w_html.=chr(13).'          <td>'.f($row,'cpf').'</td></tr>';
      } else {
        $w_html.=chr(13).'      <tr><td><font size=1><b>CNPJ:</b></td>';     
        $w_html.=chr(13).'          <td>'.f($row,'cnpj').'</td></tr>';
      } 
      if (f($RS,'sq_tipo_pessoa')==1) {
        $w_html.=chr(13).'          <td><b>Sexo:</b></td>';
        $w_html.=chr(13).'           <td>'.f($RS_Query,'nm_sexo').'</td></tr>';
        $w_html.=chr(13).'          <td><b>Data de nascimento:</b></td>';
        $w_html.=chr(13).'           <td>'.Nvl(FormataDataEdicao(f($RS_Query,'nascimento')),'---').'</td></tr>';
        $w_html.=chr(13).'          <td><b>Identidade:</b></td>';
        $w_html.=chr(13).'           <td>'.f($RS_Query,'rg_numero').'</td></tr>';
        $w_html.=chr(13).'          <td><b>Data de emissão:</b></td>';
        $w_html.=chr(13).'           <td>'.FormataDataEdicao(Nvl(f($RS_Query,'rg_emissao'),'---')).'</td></tr>';
        $w_html.=chr(13).'          <td><b>Órgão emissor:</b></td>';
        $w_html.=chr(13).'           <td>'.f($RS_Query,'rg_emissor').'</td></tr>';
        $w_html.=chr(13).'          <td><b>Passaporte:</b></td>';
        $w_html.=chr(13).'           <td>'.Nvl(f($RS_Query,'passaporte_numero'),'---').'</td></tr>';
        $w_html.=chr(13).'          <td><b>País emissor:</b></td>';
        $w_html.=chr(13).'           <td>'.Nvl(f($RS_Query,'nm_pais_passaporte'),'---').'</td></tr>';
        $w_html.=chr(13).'          </table>';
      } else {
        $w_html.=chr(13).'      <tr><td><b>Inscrição estadual:</b></td>';
        $w_html.=chr(13).'           <td>'.Nvl(f($RS_Query,'inscricao_estadual'),'---').'</td></tr>';
        $w_html.=chr(13).'          </table>';
      } 
      if (f($RS,'sq_tipo_pessoa')==1)
        $w_html.=chr(13).'      <tr><td colspan=2 align="center" style="border: 1px solid rgb(0,0,0);"><b>Endereço comercial, Telefones e e-Mail</td>';
      else
        $w_html.=chr(13).'      <tr><td colspan=2 align="center" style="border: 1px solid rgb(0,0,0);"><b>Endereço principal, Telefones e e-Mail</td>';
      $w_html.=chr(13).'        <tr><td colspan=2><table border=0 width="100%" cellspacing=0>';
      $w_html.=chr(13).'          <tr><td width="30%"><font size="1"><b>Telefone:</b></td>'; 
      $w_html.=chr(13).'            <td>('.f($row,'ddd').') '.f($row,'nr_telefone').'</td></tr>';
      $w_html.=chr(13).'          <tr><td><font size="1"><b>Fax:</b></td>'; 
      $w_html.=chr(13).'            <td>'.Nvl(f($row,'nr_fax'),'---').'</td></tr>';
      $w_html.=chr(13).'          <tr><td><font size="1"><b>Celular:</b></td>'; 
      $w_html.=chr(13).'            <td>'.Nvl(f($row,'nr_celular'),'---').'</td></tr>';
      $w_html.=chr(13).'          <tr><td><font size="1"><b>Endereço:</b></td>'; 
      $w_html.=chr(13).'            <td>'.f($row,'logradouro').'</td></tr>';
      $w_html.=chr(13).'          <tr><td><font size="1"><b>Complemento:</b></td>'; 
      $w_html.=chr(13).'            <td>'.Nvl(f($row,'complemento'),'---').'</td></tr>';
      $w_html.=chr(13).'          <tr><td><font size="1"><b>Bairro:</b></td>'; 
      $w_html.=chr(13).'            <td>'.Nvl(f($row,'bairro'),'---').'</td></tr>';
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
      if (substr($w_SG,0,3)=='FNR') {
        $w_html.=chr(13).'      <tr><td colspan=2 align="center" style="border: 1px solid rgb(0,0,0);"><b>Dados para recebimento</td>';
        $w_html.=chr(13).'      <tr><td><b>Forma de recebimento:</b></td>';
        $w_html.=chr(13).'          <td>'.f($RS,'nm_forma_pagamento').'</td></tr>';
        $w_html.=chr(13).'    </table>';            
      } elseif (substr($w_SG,0,3)=='FND') {
        $w_html.=chr(13).'      <tr><td colspan=2 align="center" style="border: 1px solid rgb(0,0,0);"><b>Dados para pagamento</td>';
        $w_html.=chr(13).'      <tr><td><b>Forma de pagamento:</b></td>';
        $w_html.=chr(13).'                <td>'.f($RS,'nm_forma_pagamento').'</td></tr>';
      } else {
        $w_html.=chr(13).'      <tr><td colspan=2 align="center" style="border: 1px solid rgb(0,0,0);"><b>Dados para pagamento/recebimento</td>';
        $w_html.=chr(13).'      <tr><td><b>Forma de pagamento/recebimento:</b></td>';
        $w_html.=chr(13).'          <td>'.f($RS,'nm_forma_pagamento').'</td></tr>';
      }
      if (substr($w_SG,0,3)!='FNR') {
        if (!(strpos('CREDITO,DEPOSITO',f($RS,'sg_forma_pagamento'))===false)) {
          if (Nvl(f($RS,'cd_banco'),'')>'') {
            $w_html.=chr(13).'          <tr><td><font size="1"><b>Banco:</b></td>';
            $w_html.=chr(13).'                <td>'.f($RS,'cd_banco').' - '.f($RS,'nm_banco').'</td></tr>';
            $w_html.=chr(13).'          <tr><td><font size="1"><b>Agência:</b></td>';
            $w_html.=chr(13).'              <td>'.f($RS,'cd_agencia').' - '.f($RS,'nm_agencia').'</td></tr>';
            if (f($RS,'exige_operacao')=='S') $w_html.=chr(13).'          <tr><td><font size="1"><b>Operação:</b></td><td>'.Nvl(f($RS,'operacao_conta'),'---').'</td>';
            $w_html.=chr(13).'          <tr><td><font size="1"><b>Número da conta:</b></td>';
            $w_html.=chr(13).'              <td>'.Nvl(f($RS,'numero_conta'),'---').'</td></tr>';
          } else {
            $w_html.=chr(13).'          <tr><td><font size="1"><b>Banco:</b></td>';
            $w_html.=chr(13).'              <td>---</td></tr>';
            $w_html.=chr(13).'          <tr><td><font size="1"><b>Agência:</b></td>';
            $w_html.=chr(13).'              <td>---</td></tr>';
            if (f($RS,'exige_operacao')=='S') $w_html.=chr(13).'          <tr><td><font size="1"><b>Operação:</b></td><td>---</td></tr>';
            $w_html.=chr(13).'          <tr><td><font size="1"><b>Número da conta:</b></td>';
            $w_html.=chr(13).'              <td>---</td></tr>';
          }
        } elseif (f($RS,'sg_forma_pagamento')=='ORDEM') {
          $w_html.=chr(13).'          <tr valign="top">';
          if (Nvl(f($RS,'cd_banco'),'')>'') {
            $w_html.=chr(13).'          <td>Banco:<b><br>'.f($RS,'cd_banco').' - '.f($RS,'nm_banco').'</td>';
            $w_html.=chr(13).'          <td>Agência:<b><br>'.f($RS,'cd_agencia').' - '.f($RS,'nm_agencia').'</td>';
          } else {
            $w_html.=chr(13).'          <td>Banco:<b><br>---</td>';
            $w_html.=chr(13).'          <td>Agência:<b><br>---</td>';
          } 
        } elseif (f($RS,'sg_forma_pagamento')=='EXTERIOR') {
          $w_html.=chr(13).'          <tr valign="top">';
          $w_html.=chr(13).'          <td>Banco:<b><br>'.f($RS,'banco_estrang').'</td>';
          $w_html.=chr(13).'          <td>ABA Code:<b><br>'.Nvl(f($RS,'aba_code'),'---').'</td>';
          $w_html.=chr(13).'          <td>SWIFT Code:<b><br>'.Nvl(f($RS,'swift_code'),'---').'</td>';
          $w_html.=chr(13).'          <tr><td colspan=3>Endereço da agência:<b><br>'.Nvl(f($RS,'endereco_estrang'),'---').'</td>';
          $w_html.=chr(13).'          <tr valign="top">';
          $w_html.=chr(13).'          <td colspan=2>Agência:<b><br>'.Nvl(f($RS,'agencia_estrang'),'---').'</td>';
          $w_html.=chr(13).'          <td>Número da conta:<b><br>'.Nvl(f($RS,'numero_conta'),'---').'</td>';
          $w_html.=chr(13).'          <tr valign="top">';
          $w_html.=chr(13).'          <td colspan=2>Cidade:<b><br>'.f($RS,'nm_cidade').'</td>';
          $w_html.=chr(13).'          <td>País:<b><br>'.f($RS,'nm_pais').'</td>';
        } 
        $w_html.=chr(13).'          </table>';
      } 
    } 
    $w_vl_retencao    = Nvl(f($RS,'valor_retencao'),0);
    $w_vl_normal      = Nvl(f($RS,'valor_imposto'),0);
    $w_vl_total       = Nvl(f($RS,'valor_total'),0);
    $w_valor          = Nvl(f($RS,'valor_liquido'),0);
  }
    
  // Documentos
  $RS = db_getLancamentoDoc::getInstanceOf($dbms,$v_chave,null,'LISTA');
  $RS = SortArray($RS,'data','asc');
  if (count($RS)>0) {
    $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DOCUMENTOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
    if ($w_vl_retencao!=0 || $w_vl_normal!=0) {
      $w_html.=chr(13).'          <tr valign="top"><td align="center" style="border: 1px solid rgb(0,0,0);">';
      $w_html.=chr(13).'            <table border=0 width="100%">';
      $w_html.=chr(13).'              <tr><td colspan=4><b>Resumo da tributação sobre os documentos</b></td></tr>';
      $w_html.=chr(13).'              <tr valign="top">';
      $w_html.=chr(13).'              <td width="25%">Valor Bruto:<br><b>'.number_format($w_vl_total,2,',','.').' </b></td>';
      $w_html.=chr(13).'              <td width="25%">Retenção:<br><b>'.number_format($w_vl_retencao,2,',','.').' </b></td>';
      $w_html.=chr(13).'              <td width="25%">Impostos:<br><b>'.number_format($w_vl_normal,2,',','.').' </b></td>';
      $w_html.=chr(13).'              <td width="25%">Valor líquido:<br><b>'.number_format(Nvl($w_valor,0),2,',','.').' </b></td>';
      $w_html.=chr(13).'            </table>';
    } 
    $w_html.=chr(13).'      <tr><td align="center">';
    $w_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
    $w_html.=chr(13).'          <tr align="center">';
    $w_html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Tipo</b></div></td>';
    $w_html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Número</b></div></td>';
    $w_html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Data</b></div></td>';
    $w_html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Série</b></div></td>';
    $w_html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Valor</b></div></td>';
    if((Nvl($w_tipo_rubrica,'')>'') && (Nvl($w_tipo_rubrica,0)==5))
      $w_html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Patrimônio</b></div></td>';
    $w_html.=chr(13).'          </tr>';
    $w_cor=$w_TrBgColor;
    $w_total=0;
    foreach ($RS as $row) {
      $RS2 = db_getImpostoDoc::getInstanceOf($dbms,$w_cliente,$v_chave,f($row,'sq_lancamento_doc'),$w_SG);
      $RS2 = SortArray($RS2,'calculo','asc','esfera','asc','nm_imposto','asc');
      if(Nvl($w_tipo_rubrica,'')>'' && Nvl($w_tipo_rubrica,0)<>5) {
        $RS3 = db_getLancamentoRubrica::getInstanceOf($dbms,null,f($row,'sq_lancamento_doc'),null,null);
        $RS3 = SortArray($RS3,'cd_rubrica_origem','asc');
      } else {
        $RS3 = db_getLancamentoItem::getInstanceOf($dbms,null,f($row,'sq_lancamento_doc'),null,null,null);
        $RS3 = SortArray($RS3,'codigo_rubrica','asc','ordem','asc');
      }
      if (count($RS2)<=0) {
        $w_html.=chr(13).'          <tr align="center" valign="top">';
        if (count($RS3)>0)  $w_html.=chr(13).'            <td rowspan=2>'.f($row,'nm_tipo_documento').'</td>';
        else                $w_html.=chr(13).'            <td>'.f($row,'nm_tipo_documento').'</td>';
        $w_html.=chr(13).'            <td>'.f($row,'numero').'</td>';
        $w_html.=chr(13).'            <td>'.FormataDataEdicao(f($row,'data')).'</td>';
        $w_html.=chr(13).'            <td>'.Nvl(f($row,'serie'),'---').'</td>';
        $w_html.=chr(13).'            <td align="right">'.number_format(f($row,'valor'),2,',','.').'&nbsp;&nbsp;</td>';
        if(Nvl($w_tipo_rubrica,'')>'' && Nvl($w_tipo_rubrica,0)==5)
          $w_html.=chr(13).'            <td>'.f($row,'nm_patrimonio').'</td>';
        $w_html.=chr(13).'          </tr>';
        if (count($RS3)>0)   {
          if(Nvl($w_tipo_rubrica,'')>'' && Nvl($w_tipo_rubrica,0)<>5) {
             $w_html.=chr(13).'              <tr align="center"><td colspan=4 align="center">';
             $w_html.=chr(13).documentorubrica($RS3,$w_tipo_rubrica);
          } else {
             $w_html.=chr(13).'              <tr align="center"><td colspan=5 align="center">';
             $w_html.=chr(13).rubricalinha($RS3);            
          }
        }
      } else {
        if (count($RS3)>0) {
           $w_html.=chr(13).'            <td rowspan=3>'.f($row,'nm_tipo_documento').'</td>';
           $w_html.=chr(13).'              <tr align="center"><td colspan=5 align="center">';
           $w_html.=chr(13).rubricalinha($RS3);
        } else {
          $w_html.=chr(13).'            <td rowspan=2>'.f($row,'nm_tipo_documento').'</td>';
        }
        $w_html.=chr(13).'          <tr align="center" valign="top">';
        $w_html.=chr(13).'            <td>'.f($row,'numero').'</td>';
        $w_html.=chr(13).'            <td>'.FormataDataEdicao(f($row,'data')).'</td>';
        $w_html.=chr(13).'            <td>'.Nvl(f($row,'serie'),'---').'</td>';
        $w_html.=chr(13).'            <td rowspan=2 align="right">'.number_format(f($row,'valor'),2,',','.').'&nbsp;&nbsp;</td>';
        $w_html.=chr(13).'            <td rowspan=2>'.f($row,'nm_patrimonio').'</td>';
        $w_html.=chr(13).'          </tr>';
        $w_html.=chr(13).'      <tr align="center"><td colspan=3 align="center">';
        $w_html.=chr(13).'          <table border=1 width="100%">';
        $w_html.=chr(13).'          <tr valign="top" align="center">';
        $w_html.=chr(13).'          <td rowspan=2><b>Tributo</td>';
        $w_html.=chr(13).'          <td colspan=2><b>Retenção</td>';
        $w_html.=chr(13).'          <td colspan=2><b>Normal</td>';
        $w_html.=chr(13).'          <td colspan=2><b>Total</td>';
        $w_html.=chr(13).'          <tr bgcolor="'.$w_cor.'" align="center">';
        $w_html.=chr(13).'          <td><b>Valor</td>';
        $w_html.=chr(13).'          <td><b>Alíquota</td>';
        $w_html.=chr(13).'          <td><b>Valor</td>';
        $w_html.=chr(13).'          <td><b>Alíquota</td>';
        $w_html.=chr(13).'          <td><b>Valor</td>';
        $w_html.=chr(13).'          <td><b>Alíquota</td>';
        $w_al_total=0;
        $w_al_retencao=0;
        $w_al_normal=0;
        $w_vl_total=0;
        $w_vl_retencao=0;
        $w_vl_normal=0;
        foreach ($RS2 as $row2) {
          $w_html.=chr(13).'          <tr valign="top">';
          $w_html.=chr(13).'          <td nowrap align="right">'.f($row2,'nm_imposto').'</td>';
          $w_html.=chr(13).'          <td align="right">R$ '.number_format(f($row2,'vl_retencao'),2,',','.').'</td>';
          $w_html.=chr(13).'          <td align="center">'.number_format(f($row2,'al_retencao'),2,',','.').'%</td>';
          $w_html.=chr(13).'          <td align="right">R$ '.number_format(f($row2,'vl_normal'),2,',','.').'</td>';
          $w_html.=chr(13).'          <td align="center">'.number_format(f($row2,'al_normal'),2,',','.').'%</td>';
          $w_html.=chr(13).'          <td align="right">R$ '.number_format(f($row2,'vl_total'),2,',','.').'</td>';
          $w_html.=chr(13).'          <td align="center">'.number_format(f($row2,'al_total'),2,',','.').'%</td>';
          $w_vl_total=$w_vl_total+f($row2,'vl_total');
          $w_vl_retencao=$w_vl_retencao+f($row2,'vl_retencao');
          $w_vl_normal=$w_vl_normal+f($row2,'vl_normal');
        } 
        if (Nvl(f($row,'valor'),0)==0)
          $w_valor=1;
        else
          $w_valor=Nvl(f($row,'valor'),0);
        $w_al_total=100-(($w_valor-($w_vl_normal+$w_vl_retencao))*100/$w_valor);
        $w_al_retencao=100-(($w_valor-$w_vl_retencao)*100/$w_valor);
        $w_al_normal=100-(($w_valor-$w_vl_normal)*100/$w_valor);
        $w_html.=chr(13).'          <tr valign="top">';
        $w_html.=chr(13).'          <td align="center"><b>Totais</td>';
        $w_html.=chr(13).'          <td align="right"><b>R$ '.number_format($w_vl_retencao,2,',','.').'<td align="center"><b> '.number_format($w_al_retencao,2,',','.').'%';
        $w_html.=chr(13).'          <td align="right"><b>R$ '.number_format($w_vl_normal,2,',','.').'<td align="center"><b> '.number_format($w_al_normal,2,',','.').'%';
        $w_html.=chr(13).'          <td align="right"><b>R$ '.number_format($w_vl_total,2,',','.').'<td align="center"><b> '.number_format($w_al_total,2,',','.').'%';
        $w_html.=chr(13).'          <tr bgcolor="'.$w_cor.'" valign="top">';
        $w_html.=chr(13).'          <td align="center"><b>Líquido</td>';
        $w_html.=chr(13).'          <td colspan=2 align="center"><b>R$ '.number_format($w_valor-$w_vl_retencao,2,',','.');
        $w_html.=chr(13).'          <td colspan=2 align="center"><b>R$ '.number_format($w_valor-$w_vl_retencao-$w_vl_normal,2,',','.');
        $w_html.=chr(13).'          <td colspan=2>&nbsp;';
        $w_html.=chr(13).'          </table>';
      } 
      $w_total=$w_total+f($row,'valor');
      
    } 
    if ($w_total>0) $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
    $w_html.=chr(13).'      <tr valign="top">';
    if(Nvl($w_tipo_rubrica,'')>'' && Nvl($w_tipo_rubrica,0)<>5) 
      $w_html.=chr(13).'        <td align="right" colspan=3><b>Total</b></td>';
    else
      $w_html.=chr(13).'        <td align="right" colspan=4><b>Total</b></td>';
    $w_html.=chr(13).'          <td align="right"><b>'.number_format($w_total,2,',','.').'</b>&nbsp;&nbsp;</td>';
    $w_html.=chr(13).'          <td align="right">&nbsp;</td>';
    $w_html.=chr(13).'      </tr>';
    $w_html.=chr(13).'         </table></td></tr>';
  } 
  // Rubricas
  if($w_qtd_rubrica>0) {
    $RS = db_getLancamentoItem::getInstanceOf($dbms,null,null,$v_chave,$w_sq_projeto,'RUBRICA');
    $RS = SortArray($RS,'rubrica','asc');
    if (count($RS)>0) {
      $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>RUBRICAS E VALORES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $w_html.=chr(13).'      <tr><td align="center">';
      $w_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
      $w_html.=chr(13).'          <tr align="center">';
      $w_html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Rubrica</b></div></td>';
      $w_html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Valor total</b></div></td>';
      $w_html.=chr(13).'          </tr>';
      $w_cor=$w_TrBgColor;
      $w_total = 0;
      foreach($RS as $row) {
        $w_html.=chr(13).'      <tr valign="top">';
        $w_html.=chr(13).'        <td align="left"><A class="hl" HREF="javascript:location.href=this.location.href;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.'mod_fn/lancamento.php?par=Ficharubrica&O=L&w_sq_projeto_rubrica='.f($row,'sq_projeto_rubrica').'&w_tipo=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Extrato Rubrica'.'&SG='.$SG.MontaFiltro('GET')).'\',\'Ficha1\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Exibe as informações deste registro.">'.f($row,'rubrica').'</A>&nbsp</td>';
        if(Nvl($w_tipo_rubrica,'')>'' && Nvl($w_tipo_rubrica,0)<>5)
          $w_html.=chr(13).'        <td align="right">'.number_format(Nvl(f($row,'valor_rubrica'),0),2,',','.').'&nbsp;&nbsp;</td>';
        else
          $w_html.=chr(13).'        <td align="right">'.number_format(Nvl(f($row,'valor_total'),0),2,',','.').'&nbsp;&nbsp;</td>';
        $w_html.=chr(13).'      </tr>';
        if(Nvl($w_tipo_rubrica,'')>'' && Nvl($w_tipo_rubrica,0)<>5)
          $w_total += nvl(f($row,'valor_rubrica'),0);
        else
          $w_total += nvl(f($row,'valor_total'),0);
      } 
      if ($w_total>0) {
        $w_html.=chr(13).'      <tr valign="top">';
        $w_html.=chr(13).'        <td align="right"><b>Total</b></td>';
        $w_html.=chr(13).'        <td align="right"><b>'.number_format($w_total,2,',','.').'</b>&nbsp;&nbsp;</td>';
        $w_html.=chr(13).'      </tr>';
      }      
      $w_html.=chr(13).'         </table></td></tr>';
    }
  }    
  // Arquivos vinculados
  $RS = db_getSolicAnexo::getInstanceOf($dbms,$v_chave,null,$w_cliente);
  $RS = SortArray($RS,'nome','asc');
  if (count($RS)>0) {
    $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ARQUIVOS ANEXOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $w_html.=chr(13).'      <tr><td align="center">';
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
      if (!($l_P4==1))
        $w_html.=chr(13).'        <td>'.LinkArquivo('HL',$w_cliente,f($row,'chave_aux'),'_blank','Clique para exibir o arquivo em outra janela.',f($row,'nome'),null).'</td>';
      else
        $w_html.=chr(13).'        <td>'.f($row,'nome').'</td>';
      $w_html.=chr(13).'        <td>'.Nvl(f($row,'descricao'),'---').'</td>';
      $w_html.=chr(13).'        <td>'.f($row,'tipo').'</td>';
      $w_html.=chr(13).'        <td align="right">'.round(f($row,'tamanho')/1024,1).'&nbsp;</td>';
      $w_html.=chr(13).'      </tr>';
    } 
    $w_html.=chr(13).'         </table></td></tr>';
  } 
  // Se for envio, executa verificações nos dados da solicitação
  $w_erro=ValidaLancamento($w_cliente,$v_chave,substr($w_SG,0,3).'GERAL',null,null,null,Nvl($w_tramite,0));
  if ($w_erro>'') {
    $w_html.=chr(13).'<tr><td colspan=2><font size=2>';
    $w_html.=chr(13).'<HR>';
    if (substr($w_erro,0,1)=='0') {
      $w_html.=chr(13).'  <font color="#BC3131"><b>ATENÇÃO:</b> Foram identificados os erros listados abaixo, não sendo possível seu encaminhamento para fases posteriores à atual, nem sua liquidação.';
    }elseif (substr($w_erro,0,1)=='1') {
      $w_html.=chr(13).'  <font color="#BC3131"><b>ATENÇÃO:</b> Foram identificados os erros listados abaixo. Seu encaminhamento para fases posteriores à atual só pode ser feito por um gestor do sistema ou do módulo de projetos.';
    } else {
      $w_html.=chr(13).'  <font color="#BC3131"><b>ATENÇÃO:</b> Foram identificados os alertas listados abaixo. Eles não impedem o encaminhamento para fases posteriores à atual, mas convém sua verificação.';
    } 
    $w_html.=chr(13).'  <ul>'.substr($w_erro,1,1000).'</ul>';
    $w_html.=chr(13).'  </td></tr>';
  } 
  // Encaminhamentos
  $RS = db_getSolicLog::getInstanceOf($dbms,$v_chave,null,'LISTA');
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
    $w_html.=chr(13).'      <tr><td colspan=4 align="center"><b>Não foram encontrados encaminhamentos.</b></td></tr>';
  } else {
    $w_html.=chr(13).'      <tr valign="top">';
    $w_cor=$w_TrBgColor;
    $i=0;
    foreach ($RS as $row) {
      if ($i==0) {
        $w_html.=chr(13).'        <td colspan=4>Fase atual: <b>'.f($row,'fase').'</b></td>';
        $i=1;
      }
      $w_html.=chr(13).'      <tr valign="top">';
      $w_html.=chr(13).'        <td nowrap>'.FormataDataEdicao(f($row,'phpdt_data'),3).'</td>';
      if (Nvl(f($row,'caminho'),'')>'' && (!($l_P4==1))) 
        $w_html.=chr(13).'        <td>'.CRLF2BR(Nvl(f($row,'despacho'),'---').'<br>['.LinkArquivo('HL',$w_cliente,f($row,'sq_siw_arquivo'),'_blank','Clique para exibir o arquivo em outra janela.','Anexo - '.f($row,'tipo').' - '.round(f($row,'tamanho')/1024,1).' KB',null).')').'</td>';
      else
        $w_html.=chr(13).'        <td>'.CRLF2BR(Nvl(f($row,'despacho'),'---')).'</td>';
      if (!($l_P4==1))
        $w_html.=chr(13).'        <td nowrap>'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'responsavel')).'</td>';
      else
        $w_html.=chr(13).'        <td nowrap>'.f($row,'responsavel').'</td>';
      if ((Nvl(f($row,'sq_lancamento_log'),''>'')) && (Nvl(f($row,'destinatario'),'')>'')) {
        if (!($l_P4==1))
          $w_html.=chr(13).'        <td nowrap>'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'sq_pessoa_destinatario'),$TP,f($row,'destinatario')).'</td>';
        else
          $w_html.=chr(13).'        <td nowrap>'.f($row,'destinatario').'</td>';
      } elseif ((Nvl(f($row,'sq_lancamento_log'),''>'')) && (Nvl(f($row,'destinatario'),'')=='')) {
        $w_html.=chr(13).'        <td nowrap>Anotação</td>';
      } else {
        $w_html.=chr(13).'        <td nowrap>'.Nvl(f($row,'tramite'),'---').'</td>';
      } 
      $w_html.=chr(13).'      </tr>';
    } 
  } 
  $w_html.=chr(13).'         </table></td></tr>';
  $w_html.=chr(13).'    </table>';
  $w_html.=chr(13).'</table>';
  return $w_html;
} 
function rubricalinha($v_RS3){
  extract($GLOBALS);
  $v_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
  $v_html.=chr(13).'          <tr align="center">';
  $v_html.=chr(13).'          <td width="6%" bgColor="#f0f0f0"><div><b>Ordem</b></div></td>';
  $v_html.=chr(13).'          <td width="12%" bgColor="#f0f0f0"><div><b>Rubrica</b></div></td>';
  $v_html.=chr(13).'          <td width="45%" bgColor="#f0f0f0"><div><b>Descrição</b></div></td>';
  $v_html.=chr(13).'          <td width="7%"  bgColor="#f0f0f0"><div><b>Qtd</b></div></td>';
  $v_html.=chr(13).'          <td width="15%" bgColor="#f0f0f0"><div><b>$ Unit</b></div></td>';
  $v_html.=chr(13).'          <td width="15%" bgColor="#f0f0f0"><div><b>$ Total</b></div></td>';
  $v_html.=chr(13).'        </tr>';
  foreach($v_RS3 as $row) {
    $v_html.=chr(13).'      <tr valign="top">';
    $v_html.=chr(13).'        <td align="center">'.f($row,'ordem').'</td>';
    if(nvl(f($row,'codigo_rubrica'),'')>'')
      $v_html.=chr(13).'        <td align="center"><A class="hl" HREF="javascript:location.href=this.location.href;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.'mod_fn/lancamento.php?par=Ficharubrica&O=L&w_sq_projeto_rubrica='.f($row,'sq_projeto_rubrica').'&w_tipo=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Extrato Rubrica'.'&SG='.$SG.MontaFiltro('GET')).'\',\'Ficha2\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Exibe as informações deste registro.">'.f($row,'codigo_rubrica').'</A>&nbsp</td>';
    else
      $v_html.=chr(13).'        <td align="center">???</td>';
    $v_html.=chr(13).'        <td>'.f($row,'descricao').'</td>';
    $v_html.=chr(13).'        <td align="right">'.number_format(f($row,'quantidade'),2,',','.').'</td>';
    $v_html.=chr(13).'        <td align="right">'.number_format(f($row,'valor_unitario'),2,',','.').'&nbsp;&nbsp;</td>';
    $v_html.=chr(13).'        <td align="right">'.number_format(f($row,'valor_total'),2,',','.').'&nbsp;&nbsp;</td>';
    $v_html.=chr(13).'      </tr>';
    $w_total += f($row,'valor_total');
  } 
  if ($w_total>0) {
    $v_html.=chr(13).'      <tr valign="top">';
    $v_html.=chr(13).'        <td align="right" colspan=5><b>Total</b></td>';
    $v_html.=chr(13).'        <td align="right"><b>'.number_format($w_total,2,',','.').'</b>&nbsp;&nbsp;</td>';
    $v_html.=chr(13).'      </tr>';
  }
  $v_html.=chr(13).'    </table>';
  return $v_html;
}
?>