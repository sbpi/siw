<?php
// =========================================================================
// Rotina de visualização dos dados do lançamento
// -------------------------------------------------------------------------
function VisualReembolso($v_chave,$l_O,$w_usuario,$l_P1,$l_tipo) {
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
  $w_sb_moeda      = nvl(f($RS,'sb_moeda'),'');

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
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>IDENTIFICAÇÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $l_html .= chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
    
    // Outra parte
    $sql = new db_getBenef; $RS_Query = $sql->getInstanceOf($dbms,$w_cliente,Nvl(f($RS,'pessoa'),0),null,null,null,null,Nvl(f($RS,'sq_tipo_pessoa'),0),null,null,null,null,null,null,null,null,null,null,null);
    foreach ($RS_Query as $row) {$RS_Query = $row; break;}

    if (count($RS_Query)<=0) {
      $l_html.=chr(13).'      <tr><td colspan=2 align=center><font size=1>Outra parte não informada';
    } else {
      $l_html.=chr(13).'      <tr><td colspan=2 bgColor="#f0f0f0" style="border: 1px solid rgb(0,0,0);" ><b>';
      if (f($RS,'sq_tipo_pessoa')>2) {
        $l_html.=chr(13).'          '.f($RS_Query,'nm_pessoa').' ('.f($RS_Query,'nome_resumido').') - Passaporte: '.f($RS_Query,'passaporte_numero').' '.f($RS_Query,'nm_pais_passaporte').'</b>';
      } else {
        $l_html.=chr(13).'          '.f($RS_Query,'nm_pessoa').' ('.f($RS_Query,'nome_resumido').') - '.f($RS_Query,'identificador_primario').'</b></td></tr>';
      }
    }

    if (!($l_P1==4 || $l_tipo=='WORD')){
      $l_html.=chr(13).'      <tr><td><b>Unidade solicitante: </b></td>';
      $l_html.=chr(13).'        <td>'.ExibeUnidade($w_dir_volta,$w_cliente,f($RS,'nm_unidade_resp'),f($RS,'sq_unidade'),$TP).'</td></tr>';
    } else {
      $l_html.=chr(13).'      <tr><td><b>Unidade solicitante: </b></td>';
      $l_html.=chr(13).'        <td>'.f($RS,'nm_unidade_resp').'</td></tr>';
    }
    $l_html.=chr(13).'      <tr><td><b>Data da solicitação:</b></td><td>'.FormataDataEdicao(f($RS,'inclusao')).'</td></tr>';

    // Verifica o segmento do cliente    
    $sql = new db_getCustomerData; $RS1 = $sql->getInstanceOf($dbms,$w_cliente); 
    $w_segmento = f($RS1,'segmento');
    if ($w_mod_pa=='S' && (nvl(f($RS,'processo'),'')!='' || nvl(f($RS,'protocolo_siw'),'')!='')) {
      if ((!($l_P1==4 || $l_tipo=='WORD')) && nvl(f($RS,'protocolo_siw'),'')!='') {
        $l_html.=chr(13).'      <tr><td><b>Número do protocolo: </b></td><td><A class="HL" HREF="mod_pa/documento.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($RS,'protocolo_siw').'&w_tipo=&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG=PADGERAL'.MontaFiltro('GET').'" title="Exibe as informações deste registro." target="processo">'.f($RS,'processo').'&nbsp;</a>';
      } else {
        $l_html.=chr(13).'      <tr><td><b>Número do protocolo: </b></td><td>'.f($RS,'processo');
      }
    } elseif ($w_segmento=='Público') { 
      $l_html.=chr(13).'      <tr><td><b>Número do protocolo: </b></td>';
      $l_html.=chr(13).'        <td>'.nvl(f($RS,'processo'),'---').' </td></tr>';
    }   
    
    if (nvl(f($RS,'solic_origem'),'')!='') {
      // Recupera dados da solicitação de compra
      $sql = new db_getSolicData; $RS1 = $sql->getInstanceOf($dbms,f($RS,'solic_origem'),null);
      $imp = explode('|@|',f($RS1,'dados_solic'));
      $l_html.=chr(13).'      <tr><td width="30%"><b>Lançamento original: </b></td>';
      $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS,'solic_origem'),$imp,'N',$l_tipo).'</td>';
    } 
    $exibe = true; 
    if (nvl(f($RS,'sq_solic_vinculo'),'')!='') {
      // Recupera dados da solicitação de compra
      $sql = new db_getSolicData; $RS1 = $sql->getInstanceOf($dbms,f($RS,'sq_solic_vinculo'),null);
      $vinc = explode('|@|',f($RS1,'dados_solic'));
      if ($vinc[5]=='PJCAD') { 
        $texto = 'Projeto'; $exibe = false;
      } else {
        $texto = $vinc[4];
      }
      $l_html.=chr(13).'      <tr><td width="30%"><b>'.$texto.': </b></td>';
      $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS,'sq_solic_vinculo'),f($RS1,'dados_solic'),'N',$l_tipo).'</td>';
    }
    if (nvl(f($RS,'dados_pai'),'')!='') {
      $pai = explode('|@|',f($RS,'dados_pai'));
      if ($pai[5]=='PJCAD' && $exibe) {
        $l_html.=chr(13).'      <tr><td width="30%"><b>Projeto: </b></td>';
        if (Nvl(f($RS,'dados_pai'),'')!='') {
          $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS,'sq_solic_pai'),f($RS,'dados_pai'),'S',$l_tipo).'</td>';
        } else {
          $l_html.=chr(13).'        <td>---</td>';
        }
        $exibe = false;
      } elseif($exibe && nvl($pai[4],'')!='') {
        $l_html.=chr(13).'      <tr><td width="30%"><b>'.$pai[4].': </b></td>';
        $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS,'sq_solic_pai'),f($RS,'dados_pai'),'N',$l_tipo).'</td>';
      }
    } 
    if (nvl(f($RS,'dados_avo'),'')!='') {
      $avo = explode('|@|',f($RS,'dados_avo'));
      if ($avo[5]=='PJCAD' && $exibe) {
        $l_html.=chr(13).'      <tr><td width="30%"><b>Projeto: </b></td>';
        if (Nvl(f($RS,'dados_avo'),'')!='') {
          $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS,'sq_solic_avo'),f($RS,'dados_avo'),'N',$l_tipo).'</td>';
        } else {
          $l_html.=chr(13).'        <td>---</td>';
        }
        $exibe = false;
      }
    }
    $l_html.=chr(13).'      <tr><td width="30%"><b>Tipo de lançamento: </b></td><td>'.f($RS,'nm_tipo_lancamento').' </td></tr>';
    $l_html.=chr(13).'      <tr><td><b>Valor do reembolso:</b></td><td>'.(($w_sb_moeda!='') ? $w_sb_moeda.' ' : '').formatNumber(Nvl(f($RS,'valor')+f($RS,'vl_outros')-f($RS,'vl_abatimento'),0)).' </td></tr>';
    $sql = new db_getSolicCotacao; $RS_Moeda_Cot = $sql->getInstanceOf($dbms,$w_cliente, $v_chave,null,null,null,null);
    $RS_Moeda_Cot = SortArray($RS_Moeda_Cot,'sb_moeda','asc');
    foreach($RS_Moeda_Cot as $row) {
      if ($w_sb_moeda!=f($row,'sb_moeda_cot')) {
        $l_html.=chr(13).'          <tr><td></td><td>'.f($row,'sb_moeda_cot').' '.formatNumber(f($row,'vl_cotacao')).' </td></tr>';
      }
    }
    $l_html.=chr(13).'      <tr><td><b>Data de pagamento:</b></td><td>'.FormataDataEdicao(f($RS,'quitacao')).'</td></tr>';
    $l_html.=chr(13).'      <tr><td><b>Mês de referência:</b></td><td>'.FormataDataEdicao(f($RS,'referencia_inicio'),9).'</td></tr>';
    $l_html.=chr(13).'      <tr valign="top"><td><b>Discriminação das despesas: </b></td><td>'.CRLF2BR(f($RS,'descricao')).'</td></tr>';
    $l_html.=chr(13).'      <tr><td><b>Forma de pagamento:</b></td><td>'.f($RS,'nm_forma_pagamento').'</td></tr>';

    if (f($RS,'sg_forma_pagamento')!='ESPECIE') $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS PARA '.upper(f($RS,'nm_forma_pagamento')).'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    if (substr($w_SG,0,3)!='FNR' || Nvl(f($RS,'numero_conta'),'')!='') {
      if (!(strpos('CREDITO,DEPOSITO',f($RS,'sg_forma_pagamento'))===false)) {
        if (Nvl(f($RS,'cd_banco'),'')>'') {
          $l_html.=chr(13).'          <tr><td><b>Banco:</b><td>'.f($RS,'cd_banco').' - '.f($RS,'nm_banco').'</td></tr>';
          $l_html.=chr(13).'          <tr><td><b>Agência:</b><td>'.f($RS,'cd_agencia').' - '.f($RS,'nm_agencia').'</td></tr>';
          if (f($RS,'exige_operacao')=='S') $l_html.=chr(13).'          <tr><td><b>Operação:</b><td>'.Nvl(f($RS,'operacao_conta'),'---').'</td>';
          $l_html.=chr(13).'          <tr><td><b>Número da conta:</b><td>'.Nvl(f($RS,'numero_conta'),'---').'</td></tr>';
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
        $l_html.=chr(13).'              <td>'.Nvl(f($RS,'nr_conta_org'),'---').((nvl(f($RS,'sg_moeda_cc'),'')=='') ? '' : ' ('.f($RS,'sg_moeda_cc').')').'</td></tr>';
      }
    }
    $w_vl_retencao    = Nvl(f($RS,'valor_retencao'),0);
    $w_vl_normal      = Nvl(f($RS,'valor_imposto'),0);
    $w_vl_total       = Nvl(f($RS,'valor_doc'),0);
    $w_valor          = Nvl(f($RS,'valor_liquido'),0);
  }
    
  // Se for envio, executa verificações nos dados da solicitação
  $w_erro=ValidaReembolso($w_cliente,$v_chave,substr($w_SG,0,3).'GERAL',null,null,null,Nvl($w_tramite,0));
  if ($w_erro>'') {
    $l_html.=chr(13).'<tr><td colspan=2><font size=2>';
    $l_html.=chr(13).'<HR>';
    if (substr($w_erro,0,1)=='0') {
      $l_html.=chr(13).'  <font color="#BC3131"><b>ATENÇÃO:</b> Foram identificadas as pendências listadas abaixo, não sendo possível seu encaminhamento para fases posteriores à atual, nem seu pagamento.';
    }elseif (substr($w_erro,0,1)=='1') {
      $l_html.=chr(13).'  <font color="#BC3131"><b>ATENÇÃO:</b> Foram identificadas as pendências listadas abaixo. Seu encaminhamento para fases posteriores à atual só pode ser feito por um gestor do sistema ou deste módulo.';
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
  if ($w_total>0 && count($v_RS3)>1) {
    $v_html.=chr(13).'      <tr valign="top">';
    $v_html.=chr(13).'        <td align="right" colspan="'.((strpos(f($RS_Menu,'sigla'),'VIA')!==false) ? 7 : 5).'"><b>Total</b></td>';
    $v_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_total).'</b>&nbsp;</td>';
    $v_html.=chr(13).'      </tr>';
  }
  $v_html.=chr(13).'    </table>';
  return $v_html;
}
?>