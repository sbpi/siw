<?
// =========================================================================
// Rotina de visualização dos dados da solicitacao
// -------------------------------------------------------------------------
function VisualCertame($v_chave,$l_O,$l_usuario,$l_P1,$l_tipo) {
  extract($GLOBALS);
  if ($l_tipo=='WORD') $w_TrBgColor=''; else $w_TrBgColor=$conTrBgColor;
  $l_html='';

  $RS = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
  $w_cliente_arp = f($RS,'ata_registro_preco');
  
  // Recupera os dados da solicitacao
  $RS = db_getSolicCL::getInstanceOf($dbms,null,$l_usuario,$SG,3,
          null,null,null,null,null,null,null,null,null,null,
          $v_chave,null,null,null,null,null,null,
          null,null,null,null,null,null,null,null,null,null,null);
  foreach($RS as $row){$RS=$row; break;}
  $l_tramite        = f($RS,'sq_siw_tramite');
  $l_sigla          = f($RS,'sigla');
  $l_sg_tramite     = f($RS,'sg_tramite');
  $w_tramite_ativo  = f($RS,'ativo');
  $w_certame        = f($RS,'certame');
  $w_gera_contrato  = f($RS,'gera_contrato');
  
  // Recupera o tipo de visão do usuário
  if (Nvl(f($RS,'solicitante'),0)   == $l_usuario || 
      Nvl(f($RS,'executor'),0)      == $l_usuario || 
      Nvl(f($RS,'cadastrador'),0)   == $l_usuario || 
      Nvl(f($RS,'titular'),0)       == $l_usuario || 
      Nvl(f($RS,'substituto'),0)    == $l_usuario || 
      Nvl(f($RS,'tit_exec'),0)      == $l_usuario || 
      Nvl(f($RS,'subst_exec'),0)    == $l_usuario || 
      SolicAcesso($v_chave,$l_usuario)>=8) {
    // Se for solicitante, executor ou cadastrador, tem visão completa
    $w_tipo_visao=0;
  } else {
    if (SolicAcesso($v_chave,$l_usuario)>2) $w_tipo_visao = 1;
  } 
  // Se for listagem ou envio, exibe os dados de identificação do lançamento
  if ($l_O=='L' || $l_O=='V') {
    // Se for listagem dos dados
    $l_html.=chr(13).'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
    $l_html.=chr(13).'<tr><td>';
    $l_html.=chr(13).'    <table width="99%" border="0">';
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    $l_html.=chr(13).'      <tr valign=""top"">';
    $l_html.=chr(13).'        <td bgcolor="#f0f0f0" width="50%"><font size="2"><b>'.f($RS,'codigo_interno').' ('.$v_chave.')</b></td>';
    $l_html.=chr(13).'        <td bgcolor="#f0f0f0" width="50%" align="right"><font size="2"><b>'.f($RS,'nm_lcsituacao').'</b></td>';
    $l_html.=chr(13).'      </tr>';
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
     
    // Identificação do certame
    $l_html .= chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
    // Exibe a vinculação
    $l_html.=chr(13).'      <tr><td valign="top" width="30%"><b>Vinculação: </b></td>';
    if($l_tipo!='WORD') $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS,'sq_solic_pai'),f($RS,'dados_pai'),'S').'</td></tr>';
    else                $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS,'sq_solic_pai'),f($RS,'dados_pai'),'S','S').'</td></tr>';
    if (nvl(f($RS,'nm_prioridade'),'')!='') $l_html.=chr(13).'      <tr><td><b>Prioridade: </b></td><td>'.f($RS,'nm_prioridade').' </td></tr>';
    $l_html.=chr(13).'      <tr><td><b>Data do pedido:</b></td><td>'.FormataDataEdicao(f($RS,'inicio')).' </td></tr>';
    if (nvl(f($RS,'fim'),'')!='') $l_html.=chr(13).'      <tr><td><b>Previsão de conclusão:</b></td><td>'.FormataDataEdicao(f($RS,'fim')).' </td></tr>';
    $l_html.=chr(13).'      <tr><td><b>Valor estimado: </b></td><td>'.formatNumber(f($RS,'valor'),2).'</td></tr>';
    if ($w_cliente_arp=='S' && nvl(f($RS,'arp'),'')!='') $l_html.=chr(13).'      <tr><td><b>Gera ARP?</b></td><td>'.RetornaSimNao(f($RS,'arp')).' </td></tr>';
    $l_html .= chr(13).'    <tr><td><b>Solicitante:<b></td>';
    if (!($l_P1==4 || $l_tipo=='WORD')){
      $l_html .= chr(13).'        <td>'.ExibePessoa(null,$w_cliente,f($RS,'solicitante'),$TP,f($RS,'nm_solic')).'</b></td>';
    } else {
      $l_html .= chr(13).'        <td>'.f($RS,'nm_solic').'</b></td>';
    }
    if (!($l_P1==4 || $l_tipo=='WORD')){
      $l_html.=chr(13).'      <tr><td><b>Unidade solicitante: </b></td><td>'.ExibeUnidade($w_dir_volta,$w_cliente,f($RS,'nm_unidade_resp'),f($RS,'sq_unidade'),$TP).'</td></tr>';
    } else {
      $l_html.=chr(13).'      <tr><td><b>Unidade solicitante: </b></td><td>'.f($RS,'nm_unidade_resp').'</td></tr>';
    }
    if(f($RS,'decisao_judicial')=='S') {
      $l_html.=chr(13).'      <tr><td><b>Número original: </b></td><td>'.f($RS,'numero_original').' </td></tr>';
      $l_html.=chr(13).'      <tr><td><b>Data de recebimento:</b></td><td>'.FormataDataEdicao(f($RS,'data_recebimento')).' </td></tr>'; 
    }
    $l_html.=chr(13).'      <tr><td><b>Justificativa:</b></td><td>'.CRLF2BR(f($RS,'justificativa')).' </td></tr>';
    $l_html.=chr(13).'      <tr><td><b>Observação:</b></td><td>'.CRLF2BR(Nvl(f($RS,'observacao'),'---')).' </td></tr>';
    $l_html.=chr(13).'          </table></td></tr>';    
    
    //Dados da análise
    if(nvl(f($RS,'sq_lcmodalidade'),'')!='') {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS DA ANÁLISE<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html.= chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
      $l_html.=chr(13).'      <tr><td width="30%"><b>Modalidade: </b></td><td>'.f($RS,'nm_lcmodalidade').' </td></tr>';
      if(nvl(f($RS,'processo'),'')!='') {
        $l_html.=chr(13).'      <tr><td><b>Número do protocolo: </b></td>';
        if ($w_embed!='WORD' && f($RS,'protocolo_siw')>'') {
          $l_html.=chr(13).'        <td><A class="HL" HREF="mod_pa/documento.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($RS,'protocolo_siw').'&w_tipo=&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro." target="processo">'.f($RS,'processo').'&nbsp;</a>';
        } else {
          $l_html.=chr(13).'        <td>'.f($RS,'processo');
        }
      }
      if (f($RS,'certame')=='S') {
        $l_html.=chr(13).'      <tr><td><b>Abertura das propostas:</b></td><td>'.nvl(FormataDataEdicao(f($RS,'data_abertura')),'---').' </td></tr>';
        $l_html.=chr(13).'      <tr><td><b>Julgamento: </b></td><td>'.nvl(f($RS,'nm_lcjulgamento'),'---').' </td></tr>';
        $l_html.=chr(13).'      <tr><td><b>Mínimo de dias de validade das propostas: </b></td><td>'.f($RS,'dias_validade_proposta').' </td></tr>';
      }
      if(nvl(f($RS,'numero_ata'),'')!='') $l_html.=chr(13).'      <tr><td><b>Número da ata: </b></td><td>'.f($RS,'numero_ata').' </td></tr>';
      if (nvl(f($RS,'cd_lcfonterecurso'),'')!='') {
        $l_html.=chr(13).'      <tr><td><b>Fonte de recurso: </b></td><td>'.f($RS,'nm_lcfonterecurso').' ('.f($RS,'cd_lcfonterecurso').')</td></tr>';
      }
      if (nvl(f($RS,'cd_espec_despesa'),'')!='') {
        $l_html.=chr(13).'      <tr><td><b>Especificação de despesa: </b></td><td>'.f($RS,'cd_espec_despesa').' - '.f($RS,'nm_espec_despesa').' </td></tr>';
      }
      if (f($RS,'gera_contrato')=='S') {
        $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS PARA GERAÇÃO DO CONTRATO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
        $l_html.=chr(13).'      <tr><td><b>Tipo reajuste: </b></td><td>'.f($RS,'nm_tipo_reajuste').' </td></tr>';
        if(f($RS,'tipo_reajuste')==1) {
          $l_html.=chr(13).'      <tr><td><b>Índice base: </b></td><td>'.f($RS,'indice_base').' </td></tr>';
          $l_html.=chr(13).'      <tr><td><b>Indicador: </b></td><td>'.f($RS,'nm_eoindicador').' </td></tr>';  
        }
        $l_html.=chr(13).'      <tr><td><b>Limite de acréscimo/supressão (%): </b></td><td>'.formatNumber(f($RS,'limite_variacao')).' </td></tr>';
      }
    }
    
    if (nvl(f($RS,'sq_financeiro'),'')!='') {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>VINCULAÇÃO ORÇAMENTÁRIA-FINANCEIRA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      if (nvl(f($RS,'sq_projeto_rubrica'),'')!='') $l_html.=chr(13).'      <tr><td width="30%"><b>Rubrica: </b></td><td>'.f($RS,'cd_rubrica').' - '.f($RS,'nm_rubrica').'</td></tr>';
      if (nvl(f($RS,'sq_tipo_lancamento'),'')!='') $l_html.=chr(13).'      <tr><td width="30%"><b>Classificação financeira: </b></td><td>'.f($RS,'nm_lancamento').'</td></tr>';
      $l_html.=chr(13).'      <tr><td width="30%"><b>Classe(s) prevista(s): </b></td><td>';
      $l_texto = '';
      if (f($RS,'consumo')=='S') $l_texto = 'CONSUMO';
      if (f($RS,'permanente')=='S') (($l_texto=='') ? $l_texto = 'PERMANENTE' : $l_texto.=', PERMANENTE');
      if (f($RS,'servico')=='S')    (($l_texto=='') ? $l_texto = 'SERVIÇOS'   : $l_texto.=', SERVIÇOS');
      if (f($RS,'outros')=='S')     (($l_texto=='') ? $l_texto = 'OUTROS'     : $l_texto.=', OUTROS');
      $l_html.=$l_texto.'</td></tr>';
    }
    
    // Objetivos estratégicos
    $RS1 = db_getSolicObjetivo::getInstanceOf($dbms,$v_chave,null,null);
    $RS1 = SortArray($RS1,'nome','asc');
    if (count($RS1)>0) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>OBJETIVOS ESTRATÉGICOS ('.count($RS1).' )<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html .= chr(13).'      <tr><td align="center" colspan="2">';
      $l_html.=chr(13).'          <table width=100%  border="1" bordercolor="#00000">';
      $l_html .= chr(13).'          <tr valign="top">';
      $l_html .= chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>Nome</b></div></td>';
      $l_html .= chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>Sigla</b></div></td>';
      $l_html .= chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>Descrição</b></div></td>';
      $l_html .= chr(13).'          </tr>';
      $w_cor=$conTrBgColor;
      foreach ($RS1 as $row) {
        $l_html .= chr(13).'          <tr valign="top">';
        $l_html .= chr(13).'            <td>'.f($row,'nome').'</td>';
        $l_html .= chr(13).'            <td>'.f($row,'sigla').'</td>';
        $l_html .= chr(13).'            <td>'.crlf2br(f($row,'descricao')).'</td>';
        $l_html .= chr(13).'          </tr>';
      } 
      $l_html .= chr(13).'         </table></td></tr>';
    }
        
    // Dados da conclusão da solicitação, se ela estiver nessa situação
    if (nvl(f($RS,'sg_tramite'),'')=='AT') {
      if (f($RS,'sigla')=='CLLCCAD') {
        $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS DA CONCLUSÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
        if ($w_gera_contrato=='S') {
          $l_html.=chr(13).'   <tr valign="top"><td><b>Data de homologação:</b></font></td><td>'.nvl(formataDataEdicao(f($RS,'data_homologacao')),'---').'</font></td></tr>';
          $l_html.=chr(13).'   <tr valign="top"><td><b>Data Diário Oficial:</b></font></td><td>'.nvl(formataDataEdicao(f($RS,'data_diario_oficial')),'---').'</font></td></tr>';
          $l_html.=chr(13).'   <tr valign="top"><td><b>Página Diário Oficial:</b></font></td><td>'.nvl(f($RS,'pagina_diario_oficial'),'---').'</font></td></tr>';
        } else {
          $l_html.=chr(13).'   <tr valign="top"><td><b>Data de autorização:</b></font></td><td>'.nvl(formataDataEdicao(f($RS,'data_homologacao')),'---').'</font></td></tr>';
        }
        $l_html.=chr(13).'   <tr valign="top"><td><b>Nota de conclusão:</b></font></td><td>'.nvl(crlf2br(f($RS,'nota_conclusao')),'---').'</font></td></tr>';
  
        $RS1 = db_getCLSolicItem::getInstanceOf($dbms,null,$v_chave,null,null,null,null,null,null,null,null,null,null,'VENCEDOR');
        $RS1 = SortArray($RS1,'ordem','asc','nome','asc','valor_unidade','asc');
        $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>VENCEDORES</b></font></td></tr>';  
        $l_html.=chr(13).'      <tr><td colspan="2">';
        $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
        $l_html.=chr(13).'        <tr valign="top">';
        $l_html.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Item</td>';
        $l_html.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Material</td>';
        $l_html.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Qtd.</td>';
        $l_html.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Vencedor</td>';
        $l_html.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Dt.Prop.</b></td>';
        $l_html.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Dias Validade</b></td>';
        $l_html.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Valor</td>';
        $l_html.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Total</td>';
        $l_html.=chr(13).'        </tr>';
        // Lista os registros selecionados para listagem
        $w_total = 0;
        foreach($RS1 as $row) { 
          $w_percentual_acrescimo = f($row,'percentual_acrescimo');
          $l_html.=chr(13).'      <tr valign="top">';
          $l_html.=chr(13).'        <td align="center" rowspan='.f($row,'qtd_proposta').'>'.f($row,'ordem').'</td>';
          if($l_tipo=='WORD') $l_html.=chr(13).'        <td rowspan='.f($row,'qtd_proposta').'>'.f($row,'nome').'</td>';
          else                $l_html.=chr(13).'        <td rowspan='.f($row,'qtd_proposta').'>'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'sq_material'),$TP,null).'</td>';
          $l_html.=chr(13).'        <td rowspan='.f($row,'qtd_proposta').' align="right">'.nvl(formatNumber(f($row,'quantidade'),0),'---').'</td>';
          if($l_tipo=='WORD') $l_html.=chr(13).'        <td nowrap>'.f($row,'nm_fornecedor').'</td>';
          else                $l_html.=chr(13).'        <td nowrap>'.ExibePessoa('../',$w_cliente,f($row,'fornecedor'),$TP,f($row,'nm_fornecedor')).'</td>';
          $l_html.=chr(13).'        <td align="center">'.nvl(formataDataEdicao(f($row,'proposta_data'),5),'---').'</td>';
          $l_html.=chr(13).'        <td align="center">'.nvl(f($row,'dias_validade_proposta'),'---').'</td>';
          if(formatNumber(f($row,'valor_unidade'),4)>formatNumber(0,4)) {
            $l_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'valor_unidade'),4).'</td>';
          } else {
            $l_html.=chr(13).'        <td align="right">---</td>';
          }
          if(f($row,'valor_item')>0) {
            $l_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'valor_item'),4).'</td>';
          } else {
            $l_html.=chr(13).'        <td align="right">---</td>';
          }
          $w_total += f($row,'valor_item');
        }
        $l_html.=chr(13).'      <tr align="center">';
        $l_html.=chr(13).'        <td align="right" colspan="7"><b>Total</b></td>';
        $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_total,4).'</b></td>';
        $l_html.=chr(13).'      </tr>';
        $l_html.=chr(13).'    </table>';
      }
    } 

    //Listagem dos itens da licitação
    $RS1 = db_getCLSolicItem::getInstanceOf($dbms,null,$v_chave,null,null,null,null,null,null,null,null,null,null,'LICITACAO');
    $RS1 = SortArray($RS1,'ordem','asc','nm_tipo_material_pai','asc','nm_tipo_material','asc','nome','asc','dados_pai','asc'); 
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ITENS ('.count($RS1).')<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
    $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">';
    $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
    $l_html.=chr(13).'        <tr align="center">';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0" rowspan=2><b>Item</td>';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0" rowspan=2><b>Código</td>';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0" rowspan=2><b>Nome</td>';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0" rowspan=2><b>Pedido</td>';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0" colspan=2><b>Quantidade</td>';
    if (f($RS,'ativo')=='S') $l_html.=chr(13).'          <td bgColor="#f0f0f0" colspan=2><b>Preço Estimado (*)</td>';
    $l_html.=chr(13).'        </tr>';
    $l_html.=chr(13).'        <tr align="center">';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Licitada</td>';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Comprada</td>';
    if (f($RS,'ativo')=='S') {
      $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Unitário</td>';
      $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Total</td>';
    }
    $l_html.=chr(13).'        </tr>';
    if (count($RS1)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      $l_html.=chr(13).'      <tr><td colspan=8 align="center"><b>Não foram encontrados registros.</b></td></tr>';
    } else {
      $w_total_preco  = 0;
      $w_atual        = 0;
      $w_exibe        = false;
      $w_item_lic     = 0;
      $w_item_comp    = 0;
      $w_item_unit    = 0;
      $w_cor=$conTrBgColor;
      foreach($RS1 as $row) { 
        if ($w_atual!=f($row,'sq_material')) {
          if ($w_exibe) {
            $l_html.=chr(13).'      <tr><td colspan=3><td align="right" nowrap><b>Totais do item</td>';
            $l_html.=chr(13).'        <td align="right">'.formatNumber($w_item_lic,2).'</td>';
            $l_html.=chr(13).'        <td align="right">'.formatNumber($w_item_comp,2).'</td>';
            if(f($RS,'ativo')=='S') {
              $l_html.=chr(13).'        <td align="right">'.formatNumber($w_item_unit,4).'</td>';
              $l_html.=chr(13).'        <td align="right">'.formatNumber(($w_item_lic*$w_item_unit),4).'</td>';
            }
          }
          $l_html.=chr(13).'      <tr align="center">';
          $l_html.=chr(13).'        <td>'.nvl(f($row,'ordem'),'---').'</td>';
          $l_html.=chr(13).'        <td>'.f($row,'codigo_interno').'</td>';
          if($l_tipo=='WORD') $l_html.=chr(13).'        <td align="left">'.f($row,'nome').'</td>';
          else                $l_html.=chr(13).'        <td align="left">'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'sq_material'),$TP,null).'</td>';
          $w_atual      = f($row,'sq_material');
          $w_exibe      = false;
          $w_item_lic   = 0;
          $w_item_comp  = 0;
          $w_item_unit  = 0;
        } else {
          $l_html.=chr(13).'      <tr align="center">';
          $l_html.=chr(13).'        <td colspan=3></td>';
          $w_exibe = true;
        }
        if($l_tipo!='WORD') $l_html.=chr(13).'        <td align="left" nowrap>'.exibeSolic($w_dir,f($row,'sq_solic_pai'),f($row,'dados_pai'),'N').'</td>';
        else                $l_html.=chr(13).'        <td align="left" nowrap>'.exibeSolic($w_dir,f($row,'sq_solic_pai'),f($row,'dados_pai'),'N','S').'</td>';
        $l_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'qtd_pedido'),0).'</td>';
        $l_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'quantidade_autorizada'),0).'</td>';
        if (f($RS,'ativo')=='S') {
          $l_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'pesquisa_preco_medio'),4).'</td>';
          if($l_sg_tramite=='AT') {
            $l_html.=chr(13).'        <td align="right">'.formatNumber((f($row,'pesquisa_preco_medio')*f($row,'quantidade_autorizada')),4).'</td>';
          } else {
            $l_html.=chr(13).'        <td align="right">'.formatNumber((f($row,'pesquisa_preco_medio')*f($row,'qtd_pedido')),4).'</td>';
          }        
          if($l_sg_tramite=='AT') {
            $w_total_preco += (f($row,'pesquisa_preco_medio')*f($row,'quantidade_autorizada'));
          } else {
            $w_total_preco += (f($row,'pesquisa_preco_medio')*f($row,'qtd_pedido'));
          }
        }        
        $l_html.=chr(13).'        </tr>';
        $w_item_lic   += f($row,'qtd_pedido');
        $w_item_comp  += f($row,'quantidade_autorizada');
        $w_item_unit  = f($row,'pesquisa_preco_medio');
      }
      if ($w_exibe && f($RS,'ativo')=='S') {
        $l_html.=chr(13).'      <tr><td colspan=3><td align="right" nowrap><b>Totais do item</td>';
        $l_html.=chr(13).'        <td align="right">'.formatNumber($w_item_lic,2).'</td>';
        $l_html.=chr(13).'        <td align="right">'.formatNumber($w_item_comp,2).'</td>';
        $l_html.=chr(13).'        <td align="right">'.formatNumber($w_item_unit,4).'</td>';
        if(f($RS,'ativo')=='S') {
          $l_html.=chr(13).'        <td align="right">'.formatNumber(($w_item_lic*$w_item_unit),4).'</td>';
        }

      }
    } 
    if (f($RS,'ativo')=='S') {
      $l_html.=chr(13).'      <tr align="center">';
      $l_html.=chr(13).'        <td align="right" colspan="7"><b>Total</b></td>';
      $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_total_preco,4).'</b></td>';
      $l_html.=chr(13).'      </tr>';
      $l_html.=chr(13).'         </table></td></tr>';
      $l_html.=chr(13).'      <tr><td colspan="2">(*) Calculado a partir do preço médio do item.';
    } else {
      $l_html.=chr(13).'         </table></td></tr>';
    }
  }

  //Listagem das cotações da licitação
  $RS1 = db_getCLSolicItem::getInstanceOf($dbms,null,$v_chave,null,null,null,null,null,null,null,null,null,null,'COTACAO');
  $RS1 = SortArray($RS1,'nome','asc','valor_unidade','asc');
  $exibe=false;
  foreach($RS1 as $row) { 
    if(nvl(f($row,'nm_fornecedor'),'')!='') {
      $exibe=true;
      break;
    }
  }  
  if($exibe) {
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>PESQUISAS DE PREÇO ('.count($RS1).')<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
    $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">';
    $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
    $l_html.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Material</td>';
    $l_html.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Qtd.</td>';
    $l_html.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Fornecedor</td>';
    $l_html.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Dt.Pesq.</b></td>';
    $l_html.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Dias Valid.</b></td>';
    $l_html.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Valor</td>';
    $l_html.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Total</td>';
    $l_html.=chr(13).'        </tr>';
    // Lista os registros selecionados para listagem
    $w_atual        = 0;
    foreach($RS1 as $row){ 
      if ($w_atual!=f($row,'sq_material')) {
         $l_html.=chr(13).'      <tr valign="top">';
         if($l_tipo=='WORD') $l_html.=chr(13).'        <td rowspan='.f($row,'qtd_proposta').'>'.f($row,'nome').'</td>';
         else                $l_html.=chr(13).'        <td rowspan='.f($row,'qtd_proposta').'>'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'sq_material'),$TP,null).'</td>';
         $l_html.=chr(13).'        <td rowspan='.f($row,'qtd_proposta').' align="right">'.nvl(formatNumber(f($row,'quantidade'),0),'---').'</td>';
         $w_atual      = f($row,'sq_material');
      } else {
        $l_html.=chr(13).'      <tr valign="top">';
      }
      if($l_tipo=='WORD') $l_html.=chr(13).'        <td nowrap>'.f($row,'nm_fornecedor').'</td>';
      else                $l_html.=chr(13).'        <td nowrap>'.ExibePessoa('../',$w_cliente,f($row,'fornecedor'),$TP,f($row,'nm_fornecedor')).'</td>';
      $l_html.=chr(13).'        <td align="center">'.nvl(formataDataEdicao(f($row,'proposta_data'),5),'---').'</td>';
      $l_html.=chr(13).'        <td align="center">'.nvl(f($row,'dias_validade_proposta'),'---').'</td>';
      if(formatNumber(f($row,'valor_unidade'),4)>formatNumber(0,4)) {
        $l_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'valor_unidade'),4).'</td>';
      } else {
        $l_html.=chr(13).'        <td align="right">---</td>';
      }
      if(formatNumber(f($row,'valor_item'),4)>formatNumber(0,4)) {
        $l_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'valor_item'),4).'</td>';
      } else {
        $l_html.=chr(13).'        <td align="right">---</td>';
      }
    }
    $l_html.=chr(13).'      </center>';
    $l_html.=chr(13).'    </table>';
  }
  
  //Listagem das propostas da licitação
  $RS1 = db_getCLSolicItem::getInstanceOf($dbms,null,$v_chave,null,null,null,null,null,null,null,null,null,null,'PROPOSTA');
  $RS1 = SortArray($RS1,'ordem','asc','nome','asc','valor_unidade','asc');
  $exibe=false;
  foreach($RS1 as $row) { 
    if(nvl(f($row,'nm_fornecedor'),'')!='') {
      $exibe=true;
      break;
    }
  }
  if($exibe) {
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>PROPOSTAS ('.count($RS1).')<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
    $l_html.=chr(13).'      <tr><td colspan="2">';
    $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
    $l_html.=chr(13).'        <tr valign="top">';
    $l_html.=chr(13).'          <td align="center" bgColor="#f0f0f0" rowspan=2><b>Item</td>';
    $l_html.=chr(13).'          <td align="center" bgColor="#f0f0f0" rowspan=2><b>Material</td>';
    $l_html.=chr(13).'          <td align="center" bgColor="#f0f0f0" rowspan=2><b>Qtd.</td>';
    $l_html.=chr(13).'          <td align="center" bgColor="#f0f0f0" rowspan=2><b>$ médio</td>';
    $l_html.=chr(13).'          <td align="center" bgColor="#f0f0f0" rowspan=2><b>Fornecedor</td>';
    $l_html.=chr(13).'          <td align="center" bgColor="#f0f0f0" rowspan=2><b>Dt.Prop.</b></td>';
    $l_html.=chr(13).'          <td align="center" bgColor="#f0f0f0" colspan=2><b>Dias Validade</b></td>';
    $l_html.=chr(13).'          <td align="center" bgColor="#f0f0f0" rowspan=2><b>Valor</td>';
    $l_html.=chr(13).'          <td align="center" bgColor="#f0f0f0" rowspan=2><b>Total</td>';
    $l_html.=chr(13).'        </tr>';
    $l_html.=chr(13).'        <tr valign="top">';
    $l_html.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Exigido</td>';    
    $l_html.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Proposto</td>';
    $l_html.=chr(13).'        </tr>';
    // Lista os registros selecionados para listagem
    $w_atual        = 0;
    foreach($RS1 as $row) { 
      $w_percentual_acrescimo = f($row,'percentual_acrescimo');
      if ($w_atual!=f($row,'sq_material')) {
         $l_html.=chr(13).'      <tr valign="top">';
         $l_html.=chr(13).'        <td align="center" rowspan='.f($row,'qtd_proposta').'>'.f($row,'ordem').'</td>';
         if($l_tipo=='WORD') $l_html.=chr(13).'        <td rowspan='.f($row,'qtd_proposta').'>'.f($row,'nome').'</td>';
         else                $l_html.=chr(13).'        <td rowspan='.f($row,'qtd_proposta').'>'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'sq_material'),$TP,null).'</td>';
         $l_html.=chr(13).'        <td rowspan='.f($row,'qtd_proposta').' align="right">'.nvl(formatNumber(f($row,'quantidade'),0),'---').'</td>';
         $l_html.=chr(13).'        <td rowspan='.f($row,'qtd_proposta').' align="right">'.nvl(formatNumber(f($row,'pesquisa_preco_medio'),2),'---').'</td>';
         $w_atual      = f($row,'sq_material');
      } else {
        $l_html.=chr(13).'      <tr valign="top">';
      }
      // Se a validade da proposta for menor que o exigido, destaca em vermelho
      if (nvl(f($row,'dias_validade_item'),0)>nvl(f($row,'dias_validade_proposta'),0) ||
          (f($row,'pesquisa_preco_medio')+f($row,'variacao_valor')<f($row,'valor_unidade')) ||
          (f($row,'pesquisa_preco_medio')-f($row,'variacao_valor')>f($row,'valor_unidade'))
         ) {
        $w_destaque = ' BGCOLOR="'.$conTrBgColorLightRed1.'"';
      } else {
        $w_destaque = '';
      }
      if($l_tipo=='WORD') $l_html.=chr(13).'        <td nowrap '.$w_destaque.'>'.f($row,'nm_fornecedor').'</td>';
      else                $l_html.=chr(13).'        <td nowrap '.$w_destaque.'>'.ExibePessoa('../',$w_cliente,f($row,'fornecedor'),$TP,f($row,'nm_fornecedor')).'</td>';
      $l_html.=chr(13).'        <td align="center" '.$w_destaque.'>'.nvl(formataDataEdicao(f($row,'proposta_data'),5),'---').'</td>';
      $l_html.=chr(13).'        <td align="center" '.$w_destaque.'>'.nvl(f($row,'dias_validade_item'),'---').'</td>';
      $l_html.=chr(13).'        <td align="center" '.$w_destaque.'>'.nvl(f($row,'dias_validade_proposta'),'---').'</td>';
      if(formatNumber(f($row,'valor_unidade'),4)>formatNumber(0,4)) {
        $l_html.=chr(13).'        <td align="right" '.$w_destaque.'>'.formatNumber(f($row,'valor_unidade'),4).'</td>';
      } else {
        $l_html.=chr(13).'        <td align="right" '.$w_destaque.'>---</td>';
      }
      if(formatNumber(f($row,'valor_item'),4)>formatNumber(0,4)) {
        $l_html.=chr(13).'        <td align="right" '.$w_destaque.'>'.formatNumber(f($row,'valor_item'),4).'</td>';
      } else {
        $l_html.=chr(13).'        <td align="right" '.$w_destaque.'>---</td>';
      }      
    }
    $l_html.=chr(13).'      </center>';
    $l_html.=chr(13).'    </table>';
    $l_html.=chr(13).'<tr><td colspan="2"><b>Observação: propostas com fundo vermelho indicam descumprimento do prazo de validade ou valor fora da faixa aceitável ($ médio +/- '.$w_percentual_acrescimo.'%).';
  }
    
  // Se for listagem dos dados
  if ($l_O=='L' || $l_O=='V') {
    // Arquivos vinculados
    $RS1 = db_getSolicAnexo::getInstanceOf($dbms,$v_chave,null,$w_cliente);
    $RS1 = SortArray($RS1,'nome','asc');
    if (count($RS1)>0) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ANEXOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
      $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">';
      $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
      $l_html.=chr(13).'          <tr align="center">';
      $l_html.=chr(13).'             <td bgColor="#f0f0f0" width="40%"><div><b>Título</b></div></td>';
      $l_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Descrição</b></div></td>';
      $l_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Tipo</b></div></td>';
      $l_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>KB</b></div></td>';
      $l_html.=chr(13).'          </tr>';
      foreach($RS1 as $row) {
        $l_html.=chr(13).'      <tr valign="top">';
        if($l_tipo=='WORD') $l_html.=chr(13).'        <td>'.f($row,'nome').'</td>';
        else       $l_html.=chr(13).'        <td>'.LinkArquivo('HL',$w_cliente,f($row,'chave_aux'),'_blank','Clique para exibir o arquivo em outra janela.',f($row,'nome'),null).'</td>';
        $l_html.=chr(13).'        <td>'.Nvl(f($row,'descricao'),'---').'</td>';
        $l_html.=chr(13).'        <td>'.f($row,'tipo').'</td>';
        $l_html.=chr(13).'        <td align="right">'.round(f($row,'tamanho')/1024,1).'&nbsp;</td>';
        $l_html.=chr(13).'      </tr>';
      } 
      $l_html.=chr(13).'         </table></td></tr>';
    }
    // Se for envio, executa verificações nos dados da solicitação
    $w_erro = ValidaCertame($w_cliente,$v_chave,substr($l_sigla,0,4).'GERAL',null,null,null,Nvl($l_tramite,0));
    if ($w_erro>'') {
      $l_html.=chr(13).'<tr><td colspan=2><font size=2>';
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
    // Encaminhamentos
    include_once($w_dir_volta.'funcoes/exibeLog.php');
    $l_html .= exibeLog($v_chave,$l_O,$l_usuario,$w_tramite_ativo,(($l_tipo=='WORD') ? 'WORD' : 'HTML'));
  }
  $l_html .= chr(13).'</table>';
  $l_html .= chr(13).'</table>';
  return $l_html;
}
?>