<?php
include_once($w_dir_volta.'classes/sp/db_getSolicFN.php');
// =========================================================================
// Rotina de visualização dos dados do lançamento
// -------------------------------------------------------------------------
function VisualFundoFixo($v_chave,$l_O,$w_usuario,$l_P1,$l_tipo) {
  extract($GLOBALS);
  if ($l_tipo=='WORD') $w_TrBgColor=''; else $w_TrBgColor=$conTrBgColor;
  $l_html='';
  // Recupera os dados do lançamento
  $sql = new db_getSolicData; $RS = $sql->getInstanceOf($dbms,$v_chave,$SG);
  $w_tramite            = f($RS,'sq_siw_tramite');
  $w_tramite_ativo      = f($RS,'ativo');
  $w_SG                 = f($RS,'sigla');
  $w_tipo_rubrica       = f($RS,'tipo_rubrica');
  $w_qtd_rubrica        = nvl(f($RS,'qtd_rubrica'),0);
  $w_sq_projeto         = nvl(f($RS,'sq_projeto'),0);
  $w_sb_moeda           = nvl(f($RS,'sb_moeda'),'');
  $w_entidade           = nvl(f($RS,'entidade'),'');
  $w_data_autorizacao   = formataDataEdicao(f($RS,'data_autorizacao'));
  $w_texto_autorizacao  = nvl(f($RS,'texto_autorizacao'),'');

  // Documentos
  $sql = new db_getLancamentoDoc; $RS_Docs = $sql->getInstanceOf($dbms,$v_chave,null,null,null,null,null,null,'DOCS');
  foreach($RS_Docs as $row) { $RS_Docs = $row; break; }
  $w_emissao = f($RS_Docs,'data');
  
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
    $l_html.=chr(13).'      <tr><td width="30%"><b>Tipo de lançamento: </b></td><td>'.f($RS,'nm_tipo_lancamento').' </td></tr>';
    $l_html.=chr(13).'      <tr><td><b>Finalidade: </b></td><td>'.CRLF2BR(f($RS,'descricao')).'</td></tr>';
    if (!($l_P1==4 || $l_tipo=='WORD')){
      $l_html.=chr(13).'      <tr><td><b>Unidade responsável: </b></td><td>'.ExibeUnidade($w_dir_volta,$w_cliente,f($RS,'nm_unidade_resp'),f($RS,'sq_unidade'),$TP).'</td></tr>';
    } else {
      $l_html.=chr(13).'      <tr><td><b>Unidade responsável: </b></td><td>'.f($RS,'nm_unidade_resp').'</td></tr>';
    }

    $l_html.=chr(13).'          <tr><td><b>Suprido:</b></td><td>'.f($RS,'nm_pessoa').' </td></tr>';
    $l_html.=chr(13).'          <tr><td><b>Forma de suprimento:</b></td><td>'.f($RS_Docs,'nm_tipo_documento').' '.f($RS_Docs,'numero').'</td></tr>';
    $l_html.=chr(13).'          <tr><td><b>Valor:</b></td><td>'.(($w_sb_moeda!='') ? $w_sb_moeda.' ' : '').formatNumber(Nvl(f($RS,'valor'),0)).' </td></tr>';
    $sql = new db_getSolicCotacao; $RS_Moeda_Cot = $sql->getInstanceOf($dbms,$w_cliente, $v_chave,null,null,null,null);
    $RS_Moeda_Cot = SortArray($RS_Moeda_Cot,'sb_moeda','asc');
    foreach($RS_Moeda_Cot as $row) {
      if ($w_sb_moeda!=f($row,'sb_moeda_cot')) {
        $l_html.=chr(13).'          <tr><td></td><td>'.f($row,'sb_moeda_cot').' '.formatNumber(f($row,'vl_cotacao')).' </td></tr>';
      }
    }
    $l_html.=chr(13).'          <tr><td><b>Conta bancária: </b></td><td>'.f($RS,'nm_ban_org').' AG. '.f($RS,'cd_age_org').' C/C '.f($RS,'nr_conta_org').((nvl(f($RS,'sb_moeda'),'')=='') ? '' : ' ('.f($RS,'sg_moeda').')').' </td></tr>';
    $l_html.=chr(13).'          <tr><td><b>Data do saque: </b></td><td>'.FormataDataEdicao($w_emissao).'</td></tr>';
    $l_html.=chr(13).'          <tr><td><b>Limite para utilização:</b></td><td>'.FormataDataEdicao(f($RS,'vencimento')).' </td></tr>';
    $w_inicial = f($RS,'valor');
  }

  // Rubricas
  if($w_qtd_rubrica>0) {
    $sql = new db_getLancamentoItem; $RS_Rub = $sql->getInstanceOf($dbms,null,null,$v_chave,$w_sq_projeto,'RUBRICA');
    $RS_Rub = SortArray($RS_Rub,'rubrica','asc');
    if (count($RS_Rub)>0) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>RUBRICAS E VALORES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html.=chr(13).'      <tr><td align="center" colspan="2">';
      $l_html.=chr(13).'        <table width=100% border="1" bordercolor="#00000">';
      $l_html.=chr(13).'          <tr align="center">';
      $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Rubrica</b></td>';
      if ($w_entidade!='') {
        $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Fonte</b></td>';
      }
      $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Valor total'.(($w_sb_moeda!='') ? ' ('.$w_sb_moeda.')' : '').'</b></td>';
      $l_html.=chr(13).'          </tr>';
      $w_cor=$w_TrBgColor;
      $w_total = 0;
      foreach($RS_Rub as $row) {
        $l_html.=chr(13).'      <tr valign="top">';
        $l_html.=chr(13).'        <td align="left"><A class="hl" HREF="javascript:this.status.value;" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.'mod_fn/lancamento.php?par=Ficharubrica&O=L&w_sq_projeto_rubrica='.f($row,'sq_projeto_rubrica').'&w_tipo=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.' - Extrato Rubrica'.'&SG='.$SG.MontaFiltro('GET')).'\',\'Ficha1\',\'toolbar=no,width=780,height=530,top=30,left=10,scrollbars=yes\');" title="Exibe as informações deste registro.">'.f($row,'rubrica').'</A>&nbsp</td>';
        if ($w_entidade!='') {
          $l_html.=chr(13).'        <td align="left">'.$w_entidade.'</td>';
        }
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
        $l_html.=chr(13).'        <td align="right"'.(($w_entidade!='') ? ' colspan=2' : '').'><b>Total</b></td>';
        $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_total).'</b>&nbsp;&nbsp;</td>';
        $l_html.=chr(13).'      </tr>';
      }      
      $l_html.=chr(13).'         </table></td></tr>';
    }
  }
  
  if (nvl(f($RS,'processo'),'')!='') {
    $l_regiao = intVal(substr(f($RS,'processo'),strpos(f($RS,'processo'),'.')+1,6));
    $l_cidade = intVal(substr(f($RS,'processo'),strpos(f($RS,'processo'),'/')+1,4));
  }
  
  // Pagamentos vinculados
  /*
  $sql = new db_getSolicList; $RS1 = $sql->getInstanceOf($dbms,f($RSL,'sq_menu'),$w_usuario,f($RSL,'sigla'),4,
         null,null,null,null,null,null,null,null,null,null,null,null,null,$l_regiao,null,$l_cidade,null,null,
         null,null,null,null,null,null,null,null);
  */
  $sql = new db_getLinkData; $RSL = $sql->getInstanceOf($dbms,$w_cliente,'FNDFUNDO');
  $sql = new db_getSolicFN; $RS1 = $sql->getInstanceOf($dbms,f($RSL,'sq_menu'),$w_usuario,null,3,
            null,null,null,null,null,null, null,null,null,null, null, null, null, null, null, null, null,
            null, null, null, null, null, $v_chave, null, null, null, null);
  
  //$RS1 = SortArray($RS1,'dt_pagamento','asc','nm_pessoa','asc','vencimento','asc','valor','asc');
  // Gera recordset para montagem da tabela de pagamentos efetuados, combinando o lançamento financeiro com seu comprovante
  $RS3 = array();
  foreach($RS1 as $row) {
    foreach($row as $k => $v) $row1[$k] = $v;
    // Recupera o comprovante ligado ao pagamento. Pagamentos de fundo fixo só podem ter um comprovante ligados a eles
    $sql = new db_getLancamentoDoc; $RS2 = $sql->getInstanceOf($dbms,f($row,'sq_siw_solicitacao'),null,null,null,null,null,null,'DOCS');
    foreach($RS2 as $row2) { $RS2 = $row2; break; }
    $row1['data_lancamento'] = f($RS2,'data');
    $row1['numero'] = f($RS2,'numero');
    $row1['nm_tipo_documento'] = f($RS2,'nm_tipo_documento');
    array_push($RS3,$row1);
  }
  $RS1 = SortArray($RS3,'data_lancamento','asc','sq_siw_solicitacao','asc');
  if (count($RS1)>0) {
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>PAGAMENTOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
    $l_html.=chr(13).'      <tr><td colspan="2" align="center"><table class="tudo" width=100%  border="1" bordercolor="#00000">';
    $l_html.=chr(13).'          <tr bgcolor="'.$conTrBgColor.'" align="center">';
    $l_html.=chr(13).'            <td rowspan="2"><b>Código</td>';
    if ($w_mod_cl=='S') $l_html.=chr(13).'            <td rowspan="2"><b>Compra</td>';
    $l_html.=chr(13).'            <td colspan="2"><b>Documento</td>';
    $l_html.=chr(13).'            <td rowspan="2"><b>Pessoa</td>';
    $l_html.=chr(13).'            <td rowspan="2"><b>Finalidade</td>';
    $l_html.=chr(13).'            <td rowspan="2"><b>Crédito'.(($w_sb_moeda!='') ? ' '.$w_sb_moeda : '').'</td>';
    $l_html.=chr(13).'            <td rowspan="2"><b>Débito'.(($w_sb_moeda!='') ? ' '.$w_sb_moeda : '').'</td>';
    $l_html.=chr(13).'            <td rowspan="2"><b>Saldo'.(($w_sb_moeda!='') ? ' '.$w_sb_moeda : '').'</td>';
    $l_html.=chr(13).'          </tr>';
    $l_html.=chr(13).'          <tr bgcolor="'.$conTrBgColor.'" align="center">';
    $l_html.=chr(13).'            <td><b>Data</td>';
    $l_html.=chr(13).'            <td><b>Tipo e Número</td>';
    $l_html.=chr(13).'          </tr>';
    $w_cor=$w_TrBgColor;
    $w_atual = $w_inicial;
    $w_total = 0;
    $i       = 0;
    foreach ($RS1 as $row) {
      if ($i==0) {
        $l_html.=chr(13).'      <tr valign="top" BGCOLOR="'.$w_cor.'">';
        $l_html.=chr(13).'        <td align="center" width="1%" nowrap>'.ExibeImagemSolic(f($RS,'sigla'),f($RS,'inicio'),f($RS,'vencimento'),f($RS,'inicio'),f($RS,'quitacao'),f($RS,'aviso_prox_conc'),f($RS,'aviso'),f($RS,'sg_tramite'), null).' '.f($RS,'codigo_interno').'</td>';
        if ($w_mod_cl=='S') $l_html.=chr(13).'        <td align="right" width="1%" nowrap>&nbsp;</td>';
        $l_html.=chr(13).'        <td align="center" width="1%" nowrap>&nbsp;'.Nvl(FormataDataEdicao(nvl(f($RS,'inicio'),$w_emissao),5),'-').'</td>';
        $l_html.=chr(13).'        <td>'.f($RS,'nm_forma_pagamento').'&nbsp;'.f($RS,'nr_doc').'</td>';
        $l_html.=chr(13).'        <td colspan="2">'.f($RS,'nm_banco').'&nbsp;C/C '.f($RS,'nr_conta_org').'</td>';
        $l_html.=chr(13).'        <td align="right" width="1%" nowrap>'.formatNumber(f($RS,'valor')).'</td>';
        $l_html.=chr(13).'        <td align="right" width="1%" nowrap>&nbsp;</td>';
        $l_html.=chr(13).'        <td align="right" width="1%" nowrap>'.formatNumber(f($RS,'valor')).'</td>';
      }
      $i++;
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
      $l_html.=chr(13).'      <tr valign="top" BGCOLOR="'.$w_cor.'">';
      $l_html.=chr(13).'        <td width="1%" nowrap>'.ExibeImagemSolic(f($row,'sigla'),f($row,'inicio'),f($row,'vencimento'),f($row,'inicio'),f($row,'quitacao'),f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null);
      if ($w_tipo!='WORD') $l_html.=chr(13).'        <A class="hl" HREF="'.$w_dir.'lancamento.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="'.f($row,'obj_acordo').' ::> '.f($row,'descricao').'">'.f($row,'codigo_interno').'&nbsp;</a>';
      else                 $l_html.=chr(13).'        '.f($row,'codigo_interno').'';
      if ($w_mod_cl=='S') $l_html.=chr(13).'        <td align="center" width="1%" nowrap>'.exibeSolic($w_dir,f($row,'sq_solic_vinculo'),f($row,'dados_vinculo'),'N',$w_tipo).'&nbsp;</td>';
      $l_html.=chr(13).'        <td>&nbsp;'.Nvl(FormataDataEdicao(f($row,'data_lancamento'),5),'-').'&nbsp;</td>';
      $l_html.=chr(13).'        <td nowrap>'.f($row,'nm_tipo_documento').' '.f($row,'numero').'</td>';
      if (Nvl(f($row,'pessoa'),'nulo')!='nulo') {
        if ($w_tipo!='WORD') $l_html.=chr(13).'        <td>'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'pessoa'),$TP,f($row,'nm_pessoa')).'</td>';
        else                 $l_html.=chr(13).'        <td>'.f($row,'nm_pessoa_resumido').'</td>';
      } else {
        $l_html.=chr(13).'        <td align="center">---</td>';
      }
      $l_html.=chr(13).'        <td>'.f($row,'descricao').'</td>';
      $l_html.=chr(13).'        <td align="right">&nbsp;</td>';
      $l_html.=chr(13).'        <td align="right" nowrap>'.formatNumber(f($row,'valor')).'</td>';
      $w_total += Nvl(f($row,'valor'),0);
      $w_atual -= Nvl(f($row,'valor'),0);
      $l_html.=chr(13).'        <td align="right" nowrap>'.(($i==count($RS1)) ? '<b>' : '').formatNumber($w_atual).'</td>';
    } 
    $l_html.=chr(13).'      <tr valign="top" bgcolor="'.$conTrBgColor.'">';
    $l_html.=chr(13).'        <td align="right" colspan="'.(($w_mod_cl=='S') ? '7' : '6').'"><b>Total das despesas</b></td>';
    $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_total).'</b></td>';
    $l_html.=chr(13).'        <td align="right"><b>&nbsp;</b></td>';
    $l_html.=chr(13).'      </tr>';
    $l_html.=chr(13).'      </table></td></tr>';
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
  $w_erro=ValidaFundoFixo($w_cliente,$v_chave,substr($w_SG,0,3).'GERAL',null,null,null,Nvl($w_tramite,0));
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
?>