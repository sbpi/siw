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
  $w_tramite    =f($RS,'sq_siw_tramite');
  $w_SG         =f($RS,'sigla');
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
    $w_html.=chr(13).'<table border=\'0\' cellpadding=\'0\' cellspacing=\'0\' width=\'100%\'>';
    $w_html.=chr(13).'<tr bgcolor=\''.$w_TrBgColor.'\'><td>';
    $w_html.=chr(13).'    <table width=\'99%\' border=\'0\'>';
    $w_html.=chr(13).'      <tr><td><font size=\'2\'><b>'.strtoupper(f($RS,'nome')).' '.f($RS,'codigo_interno').' ('.$v_chave.')</b></td>';
    $w_html.=chr(13).'      <tr><td align=\'center\' bgcolor=\'#D0D0D0\' style=\'border: 2px solid rgb(0,0,0);\'><font size=\'1\'><b>Identificação</td>';
    // Identificação do lançamento
    if (Nvl(f($RS,'cd_acordo'),'')>'') {
      if (!($l_P1==4 || $l_P4==1))
        $w_html.=chr(13).'      <tr><td><font size=\'1\'>Contrato: <b><A class=\'hl\' HREF=\''.'mod_ac/Contratos.asp?par=Visual&O=L&w_chave='.f($RS,'sq_solic_pai').'&w_tipo=&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$l_P4.'&TP='.$TP.'&SG=GC'.substr($SG,2,1).'CAD\' title=\'Exibe as informações do contrato.\' target=\'Contrato\'>'.f($RS,'cd_acordo').' ('.f($RS,'sq_solic_pai').')</a> </b></td>';
      else
        $w_html.=chr(13).'      <tr><td><font size=\'1\'>Contrato: <b>'.f($RS,'cd_acordo').' ('.f($RS,'sq_solic_pai').') </b></td>';
    } 
    if (Nvl(f($RS,'nm_projeto'),'') > '') {
      if (!($l_P1==4 || $l_P4==1))
        $w_html.=chr(13).'      <tr><td><font size=\'1\'>Projeto: <b><A class=\'hl\' HREF=\''.'Projeto.asp?par=Visual&O=L&w_chave='.f($RS,'sq_solic_pai').'&w_tipo=Volta&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$l_P4.'&TP='.$TP.'&SG='.$SG.'\' title=\'Exibe as informações do projeto.\' target=\'Projeto\'>'.f($RS,'nm_projeto').'</a></b></td>';
      else
        $w_html.=chr(13).'      <tr><td><font size=\'1\'>Projeto: <b>'.f($RS,'nm_projeto').'  ('.f($RS,'sq_solic_pai').')</b></td>';
    } 
    // Se a classificação foi informada, exibe.
    if (Nvl(f($RS,'sq_cc'),'')>'')
      $w_html.=chr(13).'      <tr><td><font size=\'1\'>Classificação: <b>'.f($RS,'nm_cc').' </b>';
    $w_html.=chr(13).'      <tr><td><font size=\'1\'>Tipo de lançamento: <b>'.f($RS,'nm_tipo_lancamento').' </b></td>';
    $w_html.=chr(13).'      <tr><td><font size=1>Finalidade: <b>'.CRLF2BR(f($RS,'descricao')).'</b></font></td></tr>';
    if (!($l_P1==4 || $l_P4==1))
      $w_html.=chr(13).'      <tr><td><font size=\'1\'>Unidade responsável: <b>'.ExibeUnidade($w_dir_volta,$w_cliente,f($RS,'nm_unidade_resp'),f($RS,'sq_unidade'),$TP).'</b></td>';
    else
      $w_html.=chr(13).'      <tr><td><font size=\'1\'>Unidade responsável: <b>'.f($RS,'nm_unidade_resp').'</b></td>';
    $w_html.=chr(13).'      <tr><td><table border=0 width=\'100%\' cellspacing=0>';
    $w_html.=chr(13).'          <tr valign=\'top\'>';
    $w_html.=chr(13).'          <td><font size=\'1\'>Forma de pagamento:<br><b>'.f($RS,'nm_forma_pagamento').' </b></td>';
    $w_html.=chr(13).'          <td><font size=\'1\'>Vencimento:<br><b>'.FormataDataEdicao(f($RS,'vencimento')).' </b></td>';
    $w_html.=chr(13).'          <td><font size=\'1\'>Valor:<br><b>'.number_format(Nvl(f($RS,'valor'),0),2,',','.').' </b></td>';
    $w_html.=chr(13).'          </table>';
    // Dados da conclusão do projeto, se ela estiver nessa situação
    if (Nvl(f($RS,'conclusao'),'')>'' && Nvl(f($RS,'quitacao'),'')>'') {
      $w_html.=chr(13).'      <tr><td colspan=\'2\' align=\'center\' bgcolor=\'#D0D0D0\' style=\'border: 2px solid rgb(0,0,0);\'><font size=\'1\'><b>Dados da liquidação</td>';
      $w_html.=chr(13).'      <tr><td valign=\'top\' colspan=\'2\'><table border=0 width=\'100%\' cellspacing=0>';
      $w_html.=chr(13).'          <tr valign=\'top\'>';
      $w_html.=chr(13).'          <td><font size=\'1\'>Data:<br><b>'.FormataDataEdicao(f($RS,'quitacao')).' </b></td>';
      if (Nvl(f($RS,'codigo_deposito'),'')>'')
        $w_html.=chr(13).'          <td><font size=\'1\'>Código do depósito:<br><b>'.f($RS,'codigo_deposito').' </b></td>';
      $w_html.=chr(13).'          </table>';
      $w_html.=chr(13).'      <tr><td><font size=\'1\'>Observação:<br><b>'.CRLF2BR(Nvl(f($RS,'observacao'),'---')).' </b></td>';
    } 
    // Outra parte
    $RS_Query = db_getBenef::getInstanceOf($dbms,$w_cliente,Nvl(f($RS,'pessoa'),0),null,null,null,Nvl(f($RS,'sq_tipo_pessoa'),0),null,null);
    foreach ($RS_Query as $row) {$RS_Query = $row; break;}
    $w_html.=chr(13).'      <tr><td align=\'center\' bgcolor=\'#D0D0D0\' style=\'border: 2px solid rgb(0,0,0);\'><font size=\'1\'><b>Outra parte</td>';
    if (count($RS_Query)<=0) {
      $w_html.=chr(13).'      <tr><td colspan=2><font size=2><b>Outra parte não informada';
    } else {
      $w_html.=chr(13).'      <tr><td colspan=2><font size=2><b>';
      $w_html.=chr(13).'          '.f($RS_Query,'nm_pessoa').' ('.f($RS_Query,'nome_resumido').')';
      if (Nvl(f($RS,'sq_tipo_pessoa'),0)==1)
        $w_html.=chr(13).'          - '.f($RS_Query,'cpf');
      else
        $w_html.=chr(13).'          - '.f($RS_Query,'cnpj');
      if (f($RS,'sq_tipo_pessoa')==1) {
        $w_html.=chr(13).'      <tr><td><table border=0 width=\'100%\' cellspacing=0>';
        $w_html.=chr(13).'          <tr valign=\'top\'>';
        $w_html.=chr(13).'          <td><font size=\'1\'>Sexo:<b><br>'.f($RS_Query,'nm_sexo').'</td>';
        $w_html.=chr(13).'          <td><font size=\'1\'>Data de nascimento:<b><br>'.FormataDataEdicao(f($RS_Query,'nascimento')).'</td>';
        $w_html.=chr(13).'          <tr valign=\'top\'>';
        $w_html.=chr(13).'          <td><font size=\'1\'>Identidade:<b><br>'.f($RS_Query,'rg_numero').'</td>';
        $w_html.=chr(13).'          <td><font size=\'1\'>Data de emissão:<b><br>'.Nvl(f($RS_Query,'rg_emissao'),'---').'</td>';
        $w_html.=chr(13).'          <td><font size=\'1\'>Órgão emissor:<b><br>'.f($RS_Query,'rg_emissor').'</td>';
        $w_html.=chr(13).'          <tr valign=\'top\'>';
        $w_html.=chr(13).'          <td><font size=\'1\'>Passaporte:<b><br>'.Nvl(f($RS_Query,'passaporte_numero'),'---').'</td>';
        $w_html.=chr(13).'          <td><font size=\'1\'>País emissor:<b><br>'.Nvl(f($RS_Query,'nm_pais_passaporte'),'---').'</td>';
        $w_html.=chr(13).'          </table>';
      } else {
        $w_html.=chr(13).'      <tr><td colspan=2><font size=\'1\'>Inscrição estadual:<b><br>'.Nvl(f($RS_Query,'inscricao_estadual'),'---').'</td>';
      } 
      if (f($RS,'sq_tipo_pessoa')==1)
        $w_html.=chr(13).'      <tr><td align=\'center\' style=\'border: 1px solid rgb(0,0,0);\'><font size=\'1\'><b>Endereço comercial, Telefones e e-Mail</td>';
      else
        $w_html.=chr(13).'      <tr><td align=\'center\' style=\'border: 1px solid rgb(0,0,0);\'><font size=\'1\'><b>Endereço principal, Telefones e e-Mail</td>';
      $w_html.=chr(13).'      <tr><td><table border=0 width=\'100%\' cellspacing=0>';
      $w_html.=chr(13).'          <tr valign=\'top\'>';
      $w_html.=chr(13).'          <td><font size=\'1\'>Telefone:<b><br>('.f($RS_Query,'ddd').') '.f($RS_Query,'nr_telefone').'</td>';
      $w_html.=chr(13).'          <td><font size=\'1\'>Fax:<b><br>'.Nvl(f($RS_Query,'nr_fax'),'---').'</td>';
      $w_html.=chr(13).'          <td><font size=\'1\'>Celular:<b><br>'.Nvl(f($RS_Query,'nr_celular'),'---').'</td>';
      $w_html.=chr(13).'          <tr valign=\'top\'>';
      $w_html.=chr(13).'          <td><font size=\'1\'>Endereço:<b><br>'.f($RS_Query,'logradouro').'</td>';
      $w_html.=chr(13).'          <td><font size=\'1\'>Complemento:<b><br>'.Nvl(f($RS_Query,'complemento'),'---').'</td>';
      $w_html.=chr(13).'          <td><font size=\'1\'>Bairro:<b><br>'.Nvl(f($RS_Query,'bairro'),'---').'</td>';
      $w_html.=chr(13).'          <tr valign=\'top\'>';
      if (f($RS_Query,'pd_pais')=='S')
        $w_html.=chr(13).'          <td><font size=\'1\'>Cidade:<b><br>'.f($RS_Query,'nm_cidade').'-'.f($RS_Query,'co_uf').'</td>';
      else
        $w_html.=chr(13).'          <td><font size=\'1\'>Cidade:<b><br>'.f($RS_Query,'nm_cidade').'-'.f($RS_Query,'nm_pais').'</td>';
      $w_html.=chr(13).'          <td><font size=\'1\'>CEP:<b><br>'.f($RS_Query,'cep').'</td>';
      if (Nvl(f($RS_Query,'email'),'nulo')!='nulo') {
        if (!($l_P4==1))
          $w_html.=chr(13).'              <td><font size=\'1\'>e-Mail:<b><br><a class=\'hl\' href=\'mailto:'.f($RS_Query,'email').'\'>'.f($RS_Query,'email').'</a></td>';
        else
          $w_html.=chr(13).'              <td><font size=\'1\'>e-Mail:<b><br>'.f($RS_Query,'email').'</td>';
      } else {
        $w_html.=chr(13).'              <td><font size=\'1\'>e-Mail:<b><br>---</td>';
      } 
      $w_html.=chr(13).'          </table>';
      if (substr($w_SG,0,3)=='FNR') {
        $w_html.=chr(13).'      <tr><td align=\'center\' style=\'border: 1px solid rgb(0,0,0);\'><font size=\'1\'><b>Dados para recebimento</td>';
        $w_html.=chr(13).'      <tr><td><font size=\'1\'>Forma de recebimento:<b><br>'.f($RS,'nm_forma_pagamento').'</td>';
      } elseif (substr($w_SG,0,3)=='FND') {
        $w_html.=chr(13).'      <tr><td align=\'center\' style=\'border: 1px solid rgb(0,0,0);\'><font size=\'1\'><b>Dados para pagamento</td>';
        $w_html.=chr(13).'      <tr><td><font size=\'1\'>Forma de pagamento:<b><br>'.f($RS,'nm_forma_pagamento').'</td>';
      } else {
        $w_html.=chr(13).'      <tr><td align=\'center\' style=\'border: 1px solid rgb(0,0,0);\'><font size=\'1\'><b>Dados para pagamento/recebimento</td>';
        $w_html.=chr(13).'      <tr><td><font size=\'1\'>Forma de pagamento/recebimento:<b><br>'.f($RS,'nm_forma_pagamento').'</td>';
      }
      if (substr($w_SG,0,3)!='FNR') {
        $w_html.=chr(13).'      <tr><td><table border=0 width=\'100%\' cellspacing=0>';
        if (!(strpos('CREDITO,DEPOSITO',$w_forma_pagamento)===false)) {
          $w_html.=chr(13).'          <tr valign=\'top\'>';
          if (Nvl(f($RS,'cd_banco'),'')>'') {
            $w_html.=chr(13).'          <td><font size=\'1\'>Banco:<b><br>'.f($RS,'cd_banco').' - '.f($RS,'nm_banco').'</td>';
            $w_html.=chr(13).'          <td><font size=\'1\'>Agência:<b><br>'.f($RS,'cd_agencia').' - '.f($RS,'nm_agencia').'</td>';
            $w_html.=chr(13).'          <td><font size=\'1\'>Operação:<b><br>'.Nvl(f($RS,'operacao_conta'),'---').'</td>';
            $w_html.=chr(13).'          <td><font size=\'1\'>Número da conta:<b><br>'.Nvl(f($RS,'numero_conta'),'---').'</td>';
          } else {
            $w_html.=chr(13).'          <td><font size=\'1\'>Banco:<b><br>---</td>';
            $w_html.=chr(13).'          <td><font size=\'1\'>Agência:<b><br>---</td>';
            $w_html.=chr(13).'          <td><font size=\'1\'>Operação:<b><br>---</td>';
            $w_html.=chr(13).'          <td><font size=\'1\'>Número da conta:<b><br>---</td>';
          }
        } elseif (f($RS,'sg_forma_pagamento')=='ORDEM') {
          $w_html.=chr(13).'          <tr valign=\'top\'>';
          if (Nvl(f($RS,'cd_banco'),'')>'') {
            $w_html.=chr(13).'          <td><font size=\'1\'>Banco:<b><br>'.f($RS,'cd_banco').' - '.f($RS,'nm_banco').'</td>';
            $w_html.=chr(13).'          <td><font size=\'1\'>Agência:<b><br>'.f($RS,'cd_agencia').' - '.f($RS,'nm_agencia').'</td>';
          } else {
            $w_html.=chr(13).'          <td><font size=\'1\'>Banco:<b><br>---</td>';
            $w_html.=chr(13).'          <td><font size=\'1\'>Agência:<b><br>---</td>';
          } 
        } elseif (f($RS,'sg_forma_pagamento')=='EXTERIOR') {
          $w_html.=chr(13).'          <tr valign=\'top\'>';
          $w_html.=chr(13).'          <td><font size=\'1\'>Banco:<b><br>'.f($RS,'banco_estrang').'</td>';
          $w_html.=chr(13).'          <td><font size=\'1\'>ABA Code:<b><br>'.Nvl(f($RS,'aba_code'),'---').'</td>';
          $w_html.=chr(13).'          <td><font size=\'1\'>SWIFT Code:<b><br>'.Nvl(f($RS,'swift_code'),'---').'</td>';
          $w_html.=chr(13).'          <tr><td colspan=3><font size=\'1\'>Endereço da agência:<b><br>'.Nvl(f($RS,'endereco_estrang'),'---').'</td>';
          $w_html.=chr(13).'          <tr valign=\'top\'>';
          $w_html.=chr(13).'          <td colspan=2><font size=\'1\'>Agência:<b><br>'.Nvl(f($RS,'agencia_estrang'),'---').'</td>';
          $w_html.=chr(13).'          <td><font size=\'1\'>Número da conta:<b><br>'.Nvl(f($RS,'numero_conta'),'---').'</td>';
          $w_html.=chr(13).'          <tr valign=\'top\'>';
          $w_html.=chr(13).'          <td colspan=2><font size=\'1\'>Cidade:<b><br>'.f($RS,'nm_cidade').'</td>';
          $w_html.=chr(13).'          <td><font size=\'1\'>País:<b><br>'.f($RS,'nm_pais').'</td>';
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
    $w_html.=chr(13).'      <tr><td align=\'center\' bgcolor=\'#D0D0D0\' style=\'border: 2px solid rgb(0,0,0);\'><font size=\'1\'><b>Documentos</td>';
    if ($w_vl_retencao!=0 || $w_vl_normal!=0) {
      $w_html.=chr(13).'          <tr valign=\'top\'><td align=\'center\' style=\'border: 1px solid rgb(0,0,0);\'>';
      $w_html.=chr(13).'            <table border=0 width=\'100%\'>';
      $w_html.=chr(13).'              <tr><td colspan=4><font size=1><b>Resumo da tributação sobre os documentos</b></font></td></tr>';
      $w_html.=chr(13).'              <tr valign=\'top\'>';
      $w_html.=chr(13).'              <td width=\'25%\'><font size=\'1\'>Valor Bruto:<br><b>'.number_format($w_vl_total,2,',','.').' </b></td>';
      $w_html.=chr(13).'              <td width=\'25%\'><font size=\'1\'>Retenção:<br><b>'.number_format($w_vl_retencao,2,',','.').' </b></td>';
      $w_html.=chr(13).'              <td width=\'25%\'><font size=\'1\'>Impostos:<br><b>'.number_format($w_vl_normal,2,',','.').' </b></td>';
      $w_html.=chr(13).'              <td width=\'25%\'><font size=\'1\'>Valor líquido:<br><b>'.number_format(Nvl($w_valor,0),2,',','.').' </b></td>';
      $w_html.=chr(13).'            </table>';
    } 
    $w_html.=chr(13).'      <tr><td align=\'center\'>';
    $w_html.=chr(13).'        <TABLE WIDTH=\'100%\' bgcolor=\''.$conTableBgColor.'\' BORDER=\''.$conTableBorder.'\' CELLSPACING=\''.$conTableCellSpacing.'\' CELLPADDING=\''.$conTableCellPadding.'\' BorderColorDark=\''.$conTableBorderColorDark.'\' BorderColorLight=\''.$conTableBorderColorLight.'\'>';
    $w_html.=chr(13).'          <tr bgcolor=\''.$w_TrBgColor.'\' align=\'center\'>';
    $w_html.=chr(13).'          <td><font size=\'1\'><b>Tipo</font></td>';
    $w_html.=chr(13).'          <td><font size=\'1\'><b>Número</font></td>';
    $w_html.=chr(13).'          <td><font size=\'1\'><b>Data</font></td>';
    $w_html.=chr(13).'          <td><font size=\'1\'><b>Série</font></td>';
    $w_html.=chr(13).'          <td><font size=\'1\'><b>Valor</font></td>';
    $w_html.=chr(13).'          <td><font size=\'1\'><b>Patrimônio</font></td>';
    $w_html.=chr(13).'          </tr>';
    $w_cor=$w_TrBgColor;
    $w_total=0;
    foreach ($RS as $row) {
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;  $w_html.=chr(13).'      <tr valign=\'top\' bgcolor=\''.$w_cor.'\'>';
      $RS2 = db_getImpostoDoc::getInstanceOf($dbms,$w_cliente,$v_chave,f($RS,'sq_lancamento_doc'),$w_SG);
      $RS2 = SortArray($RS2,'calculo','asc','esfera','asc','nm_imposto','asc');
      if (count($RS2)<=0) {
        $w_html.=chr(13).'        <td><font size=\'1\'>'.f($row,'nm_tipo_documento').'</td>';
        $w_html.=chr(13).'        <td align=\'center\'><font size=\'1\'>'.f($row,'numero').'</td>';
        $w_html.=chr(13).'        <td align=\'center\'><font size=\'1\'>'.FormataDataEdicao(f($row,'data')).'</td>';
        $w_html.=chr(13).'        <td align=\'center\'><font size=\'1\'>'.Nvl(f($row,'serie'),'---').'</td>';
        $w_html.=chr(13).'        <td align=\'right\'><font size=\'1\'>'.number_format(f($row,'valor'),2,',','.').'&nbsp;&nbsp;</td>';
        $w_html.=chr(13).'        <td align=\'center\'><font size=\'1\'>'.f($row,'nm_patrimonio').'</td>';
        $w_html.=chr(13).'      </tr>';
      } else {
        $w_html.=chr(13).'        <td rowspan=2><font size=\'1\'>'.f($row,'nm_tipo_documento').'</td>';
        $w_html.=chr(13).'        <td align=\'center\'><font size=\'1\'>'.f($row,'numero').'</td>';
        $w_html.=chr(13).'        <td align=\'center\'><font size=\'1\'>'.FormataDataEdicao(f($row,'data')).'</td>';
        $w_html.=chr(13).'        <td align=\'center\'><font size=\'1\'>'.Nvl(f($row,'serie'),'---').'</td>';
        $w_html.=chr(13).'        <td rowspan=2 align=\'right\'><font size=\'1\'>'.number_format(f($row,'valor'),2,',','.').'&nbsp;&nbsp;</td>';
        $w_html.=chr(13).'        <td rowspan=2 align=\'center\'><font size=\'1\'>'.f($row,'nm_patrimonio').'</td>';
        $w_html.=chr(13).'      </tr>';
        $w_html.=chr(13).'      <tr bgcolor=\''.$w_cor.'\' align=\'center\'><td colspan=3 align=\'center\'>';
        $w_html.=chr(13).'          <table border=1 width=\'100%\'>';
        $w_html.=chr(13).'          <tr valign=\'top\' align=\'center\' bgcolor=\''.$w_cor.'\' >';
        $w_html.=chr(13).'          <td rowspan=2><font size=\'1\'><b>Tributo</td>';
        $w_html.=chr(13).'          <td colspan=2><font size=\'1\'><b>Retenção</td>';
        $w_html.=chr(13).'          <td colspan=2><font size=\'1\'><b>Normal</td>';
        $w_html.=chr(13).'          <td colspan=2><font size=\'1\'><b>Total</td>';
        $w_html.=chr(13).'          <tr bgcolor=\''.$w_cor.'\' align=\'center\'>';
        $w_html.=chr(13).'          <td><font size=\'1\'><b>Valor</td>';
        $w_html.=chr(13).'          <td><font size=\'1\'><b>Alíquota</td>';
        $w_html.=chr(13).'          <td><font size=\'1\'><b>Valor</td>';
        $w_html.=chr(13).'          <td><font size=\'1\'><b>Alíquota</td>';
        $w_html.=chr(13).'          <td><font size=\'1\'><b>Valor</td>';
        $w_html.=chr(13).'          <td><font size=\'1\'><b>Alíquota</td>';
        $w_al_total=0;
        $w_al_retencao=0;
        $w_al_normal=0;
        $w_vl_total=0;
        $w_vl_retencao=0;
        $w_vl_normal=0;
        foreach ($RS2 as $row2) {
          $w_html.=chr(13).'          <tr bgcolor=\''.$w_cor.'\' valign=\'top\'>';
          $w_html.=chr(13).'          <td nowrap align=\'right\'><font size=\'1\'>'.f($row2,'nm_imposto').'</td>';
          $w_html.=chr(13).'          <td align=\'right\'><font size=\'1\'>R$ '.number_format(f($row2,'vl_retencao'),2,',','.').'</td>';
          $w_html.=chr(13).'          <td align=\'center\'><font size=\'1\'>'.number_format(f($row2,'al_retencao'),2,',','.').'%</td>';
          $w_html.=chr(13).'          <td align=\'right\'><font size=\'1\'>R$ '.number_format(f($row2,'vl_normal'),2,',','.').'</td>';
          $w_html.=chr(13).'          <td align=\'center\'><font size=\'1\'>'.number_format(f($row2,'al_normal'),2,',','.').'%</td>';
          $w_html.=chr(13).'          <td align=\'right\'><font size=\'1\'>R$ '.number_format(f($row2,'vl_total'),2,',','.').'</td>';
          $w_html.=chr(13).'          <td align=\'center\'><font size=\'1\'>'.number_format(f($row2,'al_total'),2,',','.').'%</td>';
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
        $w_html.=chr(13).'          <tr bgcolor=\''.$w_cor.'\' valign=\'top\'>';
        $w_html.=chr(13).'          <td align=\'center\'><font size=\'1\'><b>Totais</td>';
        $w_html.=chr(13).'          <td align=\'right\'><font size=1><b><font size=1>R$ '.number_format($w_vl_retencao,2,',','.').'<td align=\'center\'><b><font size=1> '.number_format($w_al_retencao,2,',','.').'%';
        $w_html.=chr(13).'          <td align=\'right\'><font size=1><b><font size=1>R$ '.number_format($w_vl_normal,2,',','.').'<td align=\'center\'><b><font size=1> '.number_format($w_al_normal,2,',','.').'%';
        $w_html.=chr(13).'          <td align=\'right\'><font size=1><b><font size=1>R$ '.number_format($w_vl_total,2,',','.').'<td align=\'center\'><b><font size=1> '.number_format($w_al_total,2,',','.').'%';
        $w_html.=chr(13).'          <tr bgcolor=\''.$w_cor.'\' valign=\'top\'>';
        $w_html.=chr(13).'          <td align=\'center\'><font size=\'1\'><b>Líquido</td>';
        $w_html.=chr(13).'          <td colspan=2 align=\'center\'><font size=1><b><font size=1>R$ '.number_format($w_valor-$w_vl_retencao,2,',','.');
        $w_html.=chr(13).'          <td colspan=2 align=\'center\'><font size=1><b><font size=1>R$ '.number_format($w_valor-$w_vl_retencao-$w_vl_normal,2,',','.');
        $w_html.=chr(13).'          <td colspan=2><font size=1>&nbsp;';
        $w_html.=chr(13).'          </table>';
      } 
      $w_total=$w_total+f($row,'valor');
    } 
    if ($w_total>0) $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
    $w_html.=chr(13).'      <tr bgcolor=\''.$w_cor.'\' valign=\'top\'>';
    $w_html.=chr(13).'        <td align=\'center\' colspan=4><font size=\'1\'><b>Total</b></td>';
    $w_html.=chr(13).'        <td align=\'right\'><font size=\'1\'><b>'.number_format($w_total,2,',','.').'</b>&nbsp;&nbsp;</td>';
    $w_html.=chr(13).'        <td align=\'right\'><font size=\'1\'>&nbsp;</td>';
    $w_html.=chr(13).'      </tr>';
    $w_html.=chr(13).'         </table></td></tr>';
  } 
  // Arquivos vinculados
  $RS = db_getSolicAnexo::getInstanceOf($dbms,$v_chave,null,$w_cliente);
  $RS = SortArray($RS,'nome','asc');
  if (count($RS)>0) {
    $w_html.=chr(13).'      <tr><td align=\'center\' bgcolor=\'#D0D0D0\' style=\'border: 2px solid rgb(0,0,0);\'><font size=\'1\'><b>Arquivos anexos</td>';
    $w_html.=chr(13).'      <tr><td align=\'center\'>';
    $w_html.=chr(13).'        <TABLE WIDTH=\'100%\' bgcolor=\''.$conTableBgColor.'\' BORDER=\''.$conTableBorder.'\' CELLSPACING=\''.$conTableCellSpacing.'\' CELLPADDING=\''.$conTableCellPadding.'\' BorderColorDark=\''.$conTableBorderColorDark.'\' BorderColorLight=\''.$conTableBorderColorLight.'\'>';
    $w_html.=chr(13).'          <tr bgcolor=\''.$w_TrBgColor.'\' align=\'center\'>';
    $w_html.=chr(13).'          <td><font size=\'1\'><b>Título</font></td>';
    $w_html.=chr(13).'          <td><font size=\'1\'><b>Descrição</font></td>';
    $w_html.=chr(13).'          <td><font size=\'1\'><b>Tipo</font></td>';
    $w_html.=chr(13).'          <td><font size=\'1\'><b>KB</font></td>';
    $w_html.=chr(13).'          </tr>';
    $w_cor=$w_TrBgColor;
    foreach($RS as $row) {
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
      $w_html.=chr(13).'      <tr valign=\'top\' bgcolor=\''.$w_cor.'\'>';
      if (!($l_P4==1))
        $w_html.=chr(13).'        <td><font size=\'1\'>'.LinkArquivo('HL',$w_cliente,f($row,'chave_aux'),'_blank','Clique para exibir o arquivo em outra janela.',f($row,'nome'),null).'</td>';
      else
        $w_html.=chr(13).'        <td><font size=\'1\'>'.f($row,'nome').'</td>';
      $w_html.=chr(13).'        <td><font size=\'1\'>'.Nvl(f($row,'descricao'),'---').'</td>';
      $w_html.=chr(13).'        <td><font size=\'1\'>'.f($row,'tipo').'</td>';
      $w_html.=chr(13).'        <td align=\'right\'><font size=\'1\'>'.round(f($row,'tamanho')/1024,1).'&nbsp;</td>';
      $w_html.=chr(13).'      </tr>';
    } 
    $w_html.=chr(13).'         </table></td></tr>';
  } 
  // Se for envio, executa verificações nos dados da solicitação
  $w_erro=ValidaLancamento($w_cliente,$v_chave,substr($w_SG,0,3).'GERAL',null,null,null,Nvl($w_tramite,0));
  if ($w_erro>'') {
    $w_html.=chr(13).'<tr bgcolor=\''.$w_TrBgColor.'\'><td colspan=2><font size=2>';
    $w_html.=chr(13).'<HR>';
    if (substr($w_erro,0,1)=='0') {
      $w_html.=chr(13).'  <font color=\'#BC3131\'><b>ATENÇÃO:</b></font> Foram identificados os erros listados abaixo, não sendo possível seu encaminhamento para fases posteriores à atual, nem sua liquidação.';
    }elseif (substr($w_erro,0,1)=='1') {
      $w_html.=chr(13).'  <font color=\'#BC3131\'><b>ATENÇÃO:</b></font> Foram identificados os erros listados abaixo. Seu encaminhamento para fases posteriores à atual só pode ser feito por um gestor do sistema ou do módulo de projetos.';
    } else {
      $w_html.=chr(13).'  <font color=\'#BC3131\'><b>ATENÇÃO:</b></font> Foram identificados os alertas listados abaixo. Eles não impedem o encaminhamento para fases posteriores à atual, mas convém sua verificação.';
    } 
    $w_html.=chr(13).'  <ul>'.substr($w_erro,1,1000).'</ul>';
    $w_html.=chr(13).'  </font></td></tr>';
  } 
  // Encaminhamentos
  $RS = db_getSolicLog::getInstanceOf($dbms,$v_chave,null,'LISTA');
  $RS = SortArray($RS,'phpdt_data','desc','sq_siw_solic_log','desc');
  $w_html.=chr(13).'      <tr><td align=\'center\' bgcolor=\'#D0D0D0\' style=\'border: 2px solid rgb(0,0,0);\'><font size=\'1\'><b>Ocorrências e Anotações</td>';
  $w_html.=chr(13).'      <tr><td align=\'center\'>';
  $w_html.=chr(13).'        <TABLE WIDTH=\'100%\' bgcolor=\''.$conTableBgColor.'\' BORDER=\''.$conTableBorder.'\' CELLSPACING=\''.$conTableCellSpacing.'\' CELLPADDING=\''.$conTableCellPadding.'\' BorderColorDark=\''.$conTableBorderColorDark.'\' BorderColorLight=\''.$conTableBorderColorLight.'\'>';
  $w_html.=chr(13).'          <tr bgcolor=\''.$w_TrBgColor.'\' align=\'center\'>';
  $w_html.=chr(13).'            <td><font size=\'1\'><b>Data</font></td>';
  $w_html.=chr(13).'            <td><font size=\'1\'><b>Despacho/Observação</font></td>';
  $w_html.=chr(13).'            <td><font size=\'1\'><b>Responsável</font></td>';
  $w_html.=chr(13).'            <td><font size=\'1\'><b>Fase / Destinatário</font></td>';
  $w_html.=chr(13).'          </tr>';
  if (count($RS)<=0) {
    $w_html.=chr(13).'      <tr bgcolor=\''.$w_TrBgColor.'\'><td colspan=4 align=\'center\'><font size=\'1\'><b>Não foram encontrados encaminhamentos.</b></td></tr>';
  } else {
    $w_html.=chr(13).'      <tr bgcolor=\''.$w_TrBgColor.'\' valign=\'top\'>';
    $w_cor=$w_TrBgColor;
    $i=0;
    foreach ($RS as $row) {
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
      if ($i==0) {
        $w_html.=chr(13).'        <td colspan=4><font size=\'1\'>Fase atual: <b>'.f($row,'fase').'</b></td>';
        $i=1;
      }
      $w_html.=chr(13).'      <tr valign=\'top\' bgcolor=\''.$w_cor.'\'>';
      $w_html.=chr(13).'        <td nowrap><font size=\'1\'>'.FormataDataEdicao(f($row,'phpdt_data'),3).'</td>';
      if (Nvl(f($row,'caminho'),'')>'' && (!($l_P4==1))) 
        $w_html.=chr(13).'        <td><font size=\'1\'>'.CRLF2BR(Nvl(f($row,'despacho'),'---').'<br>['.LinkArquivo('HL',$w_cliente,f($row,'sq_siw_arquivo'),'_blank','Clique para exibir o arquivo em outra janela.','Anexo - '.f($row,'tipo').' - '.round(f($row,'tamanho')/1024,1).' KB',null).')').'</td>';
      else
        $w_html.=chr(13).'        <td><font size=\'1\'>'.CRLF2BR(Nvl(f($row,'despacho'),'---')).'</td>';
      if (!($l_P4==1))
        $w_html.=chr(13).'        <td nowrap><font size=\'1\'>'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'responsavel')).'</td>';
      else
        $w_html.=chr(13).'        <td nowrap><font size=\'1\'>'.f($row,'responsavel').'</td>';
      if ((Nvl(f($row,'sq_lancamento_log'),''>'')) && (Nvl(f($row,'destinatario'),'')>'')) {
        if (!($l_P4==1))
          $w_html.=chr(13).'        <td nowrap><font size=\'1\'>'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'sq_pessoa_destinatario'),$TP,f($row,'destinatario')).'</td>';
        else
          $w_html.=chr(13).'        <td nowrap><font size=\'1\'>'.f($row,'destinatario').'</td>';
      } elseif ((Nvl(f($row,'sq_lancamento_log'),''>'')) && (Nvl(f($row,'destinatario'),'')=='')) {
        $w_html.=chr(13).'        <td nowrap><font size=\'1\'>Anotação</td>';
      } else {
        $w_html.=chr(13).'        <td nowrap><font size=\'1\'>'.Nvl(f($row,'tramite'),'---').'</td>';
      } 
      $w_html.=chr(13).'      </tr>';
    } 
  } 
  $w_html.=chr(13).'         </table></td></tr>';
  $w_html.=chr(13).'    </table>';
  $w_html.=chr(13).'</table>';
  return $w_html;
} 
?>