<?
// =========================================================================
// Rotina de visualização dos dados da solicitacao
// -------------------------------------------------------------------------
function VisualCertame($v_chave,$l_O,$w_usuario,$l_P1,$l_P4) {
  extract($GLOBALS);
  if ($l_P4==1) $w_TrBgColor=''; else $w_TrBgColor=$conTrBgColor;
  $w_html='';
  // Recupera os dados da solicitacao
  $RS = db_getSolicCL::getInstanceOf($dbms,null,$w_usuario,$SG,3,
          null,null,null,null,null,null,null,null,null,null,
          $v_chave,null,null,null,null,null,null,
          null,null,null,null,null,null,null,null,null,null,null);
  foreach($RS as $row){$RS=$row; break;}
  $l_tramite        = f($RS,'sq_siw_tramite');
  $l_sigla          = f($RS,'sigla');
  $l_sg_tramite     = f($RS,'sg_tramite');

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
     
    // Identificação do certame
    $w_html .= chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
    // Exibe a vinculação
    $w_html.=chr(13).'      <tr><td valign="top" width="30%"><b>Vinculação: </b></td>';
    if($w_tipo!='WORD') $w_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS,'sq_solic_pai'),f($RS,'dados_pai'),'S').'</td></tr>';
    else                $w_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS,'sq_solic_pai'),f($RS,'dados_pai'),'S','S').'</td></tr>';
    $w_html.=chr(13).'      <tr><td><b>Prioridade: </b></td>';
    $w_html.=chr(13).'        <td>'.f($RS,'nm_prioridade').' </td></tr>';
    $w_html.=chr(13).'      <tr><td><b>Data do pedido:</b></td>';
    $w_html.=chr(13).'         <td>'.FormataDataEdicao(f($RS,'inicio')).' </td></tr>';
    $w_html.=chr(13).'      <tr><td><b>Limite para atendimento:</b></td>';
    $w_html.=chr(13).'         <td>'.FormataDataEdicao(f($RS,'fim')).' </td></tr>';
    $w_html.=chr(13).'      <tr><td><b>Valor estimado: </b></td>';
    $w_html.=chr(13).'      <td>'.formatNumber(f($RS,'valor'),2).'</td></tr>';
    $w_html.=chr(13).'      <tr><td><b>Gera ARP?</b></td>';
    $w_html.=chr(13).'         <td>'.RetornaSimNao(f($RS,'arp')).' </td></tr>';
    $w_html .= chr(13).'    <tr><td><b>Solicitante:<b></td>';
    if (!($l_P1==4 || $l_P4==1)){
      $w_html .= chr(13).'        <td>'.ExibePessoa(null,$w_cliente,f($RS,'solicitante'),$TP,f($RS,'nm_solic')).'</b></td>';
    } else {
      $w_html .= chr(13).'        <td>'.f($RS,'nm_solic').'</b></td>';
    }
    if (!($l_P1==4 || $l_P4==1)){
      $w_html.=chr(13).'      <tr><td><b>Unidade solicitante: </b></td>';
      $w_html.=chr(13).'        <td>'.ExibeUnidade($w_dir_volta,$w_cliente,f($RS,'nm_unidade_resp'),f($RS,'sq_unidade'),$TP).'</td></tr>';
    } else {
      $w_html.=chr(13).'      <tr><td><b>Unidade solicitante: </b></td>';
      $w_html.=chr(13).'        <td>'.f($RS,'nm_unidade_resp').'</td></tr>';
    }
    if(f($RS,'decisao_judicial')=='S') {
      $w_html.=chr(13).'      <tr><td><b>Número original: </b></td>';
      $w_html.=chr(13).'        <td>'.f($RS,'numero_original').' </td></tr>';
      $w_html.=chr(13).'      <tr><td><b>Data de recebimento:</b></td>';
      $w_html.=chr(13).'         <td>'.FormataDataEdicao(f($RS,'data_recebimento')).' </td></tr>'; 
    }
    $w_html.=chr(13).'          <tr><td><b>Justificativa:</b></td>';
    $w_html.=chr(13).'            <td>'.f($RS,'justificativa').' </td></tr>';
    $w_html.=chr(13).'      <tr><td><b>Observação:</b></td>';
    $w_html.=chr(13).'      <td>'.CRLF2BR(Nvl(f($RS,'observacao'),'---')).' </td></tr>';
    $w_html.=chr(13).'          </table></td></tr>';    
    
    //Dados da análise
    if(nvl(f($RS,'sq_lcmodalidade'),'')!='') {
      $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS DA ANÁLISE<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $w_html.= chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
      $w_html.=chr(13).'      <tr><td width="30%"><b>Modalidade: </b></td>';
      $w_html.=chr(13).'        <td>'.f($RS,'nm_lcmodalidade').' </td></tr>';
      if(nvl(f($RS,'processo'),'')!='') {
        $w_html.=chr(13).'      <tr><td><b>Número do processo: </b></td>';
        $w_html.=chr(13).'        <td>'.f($RS,'processo').' </td></tr>';
      }
      $w_html.=chr(13).'      <tr><td><b>Número do certame: </b></td>';
      $w_html.=chr(13).'        <td>'.f($RS,'numero_certame').' </td></tr>';
      if(nvl(f($RS,'numero_ata'),'')!='') {
        $w_html.=chr(13).'      <tr><td><b>Número da ata: </b></td>';
        $w_html.=chr(13).'        <td>'.f($RS,'numero_ata').' </td></tr>';
      }
      $w_html.=chr(13).'      <tr><td><b>Tipo reajuste: </b></td>';
      $w_html.=chr(13).'        <td>'.f($RS,'nm_tipo_reajuste').' </td></tr>';
      if(f($RS,'tipo_reajuste')==1) {
        $w_html.=chr(13).'      <tr><td><b>Índice base: </b></td>';
        $w_html.=chr(13).'        <td>'.f($RS,'indice_base').' </td></tr>';
        $w_html.=chr(13).'      <tr><td><b>Indicador: </b></td>';
        $w_html.=chr(13).'        <td>'.f($RS,'nm_eoindicador').' </td></tr>';  
      }
      $w_html.=chr(13).'      <tr><td><b>Limite de acréscimo/supressão (%): </b></td>';
      $w_html.=chr(13).'        <td>'.formatNumber(f($RS,'limite_variacao')).' </td></tr>';
      $w_html.=chr(13).'      <tr><td><b>Fonte de recurso: </b></td>';
      $w_html.=chr(13).'        <td>'.f($RS,'nm_lcfonterecurso').' ('.f($RS,'cd_lcfonterecurso').')</td></tr>';
      $w_html.=chr(13).'      <tr><td><b>Especificação de despesa: </b></td>';
      $w_html.=chr(13).'        <td>'.f($RS,'cd_espec_despesa').' - '.f($RS,'nm_espec_despesa').' </td></tr>';
      $w_html.=chr(13).'      <tr><td><b>Julgamento: </b></td>';
      $w_html.=chr(13).'        <td>'.f($RS,'nm_lcjulgamento').' </td></tr>';
      $w_html.=chr(13).'      <tr><td><b>Situação: </b></td>';
      $w_html.=chr(13).'        <td>'.f($RS,'nm_lcsituacao').' </td></tr>';
      $w_html.=chr(13).'      <tr><td><b>Mínimo de dias de validade das propostas: </b></td>';
      $w_html.=chr(13).'        <td>'.f($RS,'dias_validade_proposta').' </td></tr>';
    }
    
    // Objetivos estratégicos
    $RS1 = db_getSolicObjetivo::getInstanceOf($dbms,$v_chave,null,null);
    $RS1 = SortArray($RS1,'nome','asc');
    if (count($RS1)>0) {
      $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>OBJETIVOS ESTRATÉGICOS ('.count($RS1).' )<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $w_html .= chr(13).'      <tr><td align="center" colspan="2">';
      $w_html.=chr(13).'          <table width=100%  border="1" bordercolor="#00000">';
      $w_html .= chr(13).'          <tr valign="top">';
      $w_html .= chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>Nome</b></div></td>';
      $w_html .= chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>Sigla</b></div></td>';
      $w_html .= chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>Descrição</b></div></td>';
      $w_html .= chr(13).'          </tr>';
      $w_cor=$conTrBgColor;
      foreach ($RS1 as $row) {
        $w_html .= chr(13).'          <tr valign="top">';
        $w_html .= chr(13).'            <td>'.f($row,'nome').'</td>';
        $w_html .= chr(13).'            <td>'.f($row,'sigla').'</td>';
        $w_html .= chr(13).'            <td>'.crlf2br(f($row,'descricao')).'</td>';
        $w_html .= chr(13).'          </tr>';
      } 
      $w_html .= chr(13).'         </table></td></tr>';
    }
        
    //Listagem dos itens da licitação
    $RS1 = db_getCLSolicItem::getInstanceOf($dbms,null,$v_chave,null,null,null,null,null,null,null,null,null,null,'LICITACAO');
    $RS1 = SortArray($RS1,'ordem','asc','nm_tipo_material_pai','asc','nm_tipo_material','asc','nome','asc','dados_pai','asc'); 
    $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ITENS ('.count($RS1).')<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
    $w_html.=chr(13).'      <tr><td colspan="2"><div align="center">';
    $w_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
    $w_html.=chr(13).'        <tr align="center">';
    $w_html.=chr(13).'          <td bgColor="#f0f0f0" rowspan=2><b>Ordem</td>';
    $w_html.=chr(13).'          <td bgColor="#f0f0f0" rowspan=2><b>Código</td>';
    $w_html.=chr(13).'          <td bgColor="#f0f0f0" rowspan=2><b>Nome</td>';
    $w_html.=chr(13).'          <td bgColor="#f0f0f0" rowspan=2><b>Pedido</td>';
    $w_html.=chr(13).'          <td bgColor="#f0f0f0" colspan=2><b>Quantidade</td>';
    $w_html.=chr(13).'          <td bgColor="#f0f0f0" colspan=2><b>Preço Estimado (*)</td>';
    $w_html.=chr(13).'        </tr>';
    $w_html.=chr(13).'        <tr align="center">';
    $w_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Licitada</td>';
    $w_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Comprada</td>';
    $w_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Unitário</td>';
    $w_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Total</td>';
    $w_html.=chr(13).'        </tr>';
    if (count($RS1)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      $w_html.=chr(13).'      <tr><td colspan=8 align="center"><b>Não foram encontrados registros.</b></td></tr>';
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
            $w_html.=chr(13).'      <tr><td colspan=3><td align="right" nowrap><b>Totais do item</td>';
            $w_html.=chr(13).'        <td align="right">'.formatNumber($w_item_lic,2).'</td>';
            $w_html.=chr(13).'        <td align="right">'.formatNumber($w_item_comp,2).'</td>';
            $w_html.=chr(13).'        <td align="right">'.formatNumber($w_item_unit,4).'</td>';
            if($l_sg_tramite=='AT') {
              $w_html.=chr(13).'        <td align="right">'.formatNumber(($w_item_comp*$w_item_unit),4).'</td>';
            } else {
              $w_html.=chr(13).'        <td align="right">'.formatNumber(($w_item_lic*$w_item_unit),4).'</td>';
            }
          }
          $w_html.=chr(13).'      <tr align="center">';
          $w_html.=chr(13).'        <td>'.nvl(f($row,'ordem'),'---').'</td>';
          $w_html.=chr(13).'        <td>'.f($row,'codigo_interno').'</td>';
          if($w_tipo=='WORD') $w_html.=chr(13).'        <td align="left">'.f($row,'nome').'</td>';
          else                $w_html.=chr(13).'        <td align="left">'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'sq_material'),$TP,null).'</td>';
          $w_atual      = f($row,'sq_material');
          $w_exibe      = false;
          $w_item_lic   = 0;
          $w_item_comp  = 0;
          $w_item_unit  = 0;
        } else {
          $w_html.=chr(13).'      <tr align="center">';
          $w_html.=chr(13).'        <td colspan=3></td>';
          $w_exibe = true;
        }
        if($w_tipo!='WORD') $w_html.=chr(13).'        <td align="left" nowrap>'.exibeSolic($w_dir,f($row,'sq_solic_pai'),f($row,'dados_pai'),'N').'</td>';
        else                $w_html.=chr(13).'        <td align="left" nowrap>'.exibeSolic($w_dir,f($row,'sq_solic_pai'),f($row,'dados_pai'),'N','S').'</td>';
        $w_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'qtd_pedido'),2).'</td>';
        $w_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'quantidade_autorizada'),2).'</td>';
        $w_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'pesquisa_preco_medio'),4).'</td>';
        if($l_sg_tramite=='AT') {
          $w_html.=chr(13).'        <td align="right">'.formatNumber((f($row,'pesquisa_preco_medio')*f($row,'quantidade_autorizada')),4).'</td>';
        } else {
          $w_html.=chr(13).'        <td align="right">'.formatNumber((f($row,'pesquisa_preco_medio')*f($row,'qtd_pedido')),4).'</td>';
        }        
        if($l_sg_tramite=='AT') {
          $w_total_preco += (f($row,'pesquisa_preco_medio')*f($row,'quantidade_autorizada'));
        } else {
          $w_total_preco += (f($row,'pesquisa_preco_medio')*f($row,'qtd_pedido'));
        }        
        $w_html.=chr(13).'        </tr>';
        $w_item_lic   += f($row,'qtd_pedido');
        $w_item_comp  += f($row,'quantidade_autorizada');
        $w_item_unit  = f($row,'pesquisa_preco_medio');
      }
      if ($w_exibe) {
        $w_html.=chr(13).'      <tr><td colspan=3><td align="right" nowrap><b>Totais do item</td>';
        $w_html.=chr(13).'        <td align="right">'.formatNumber($w_item_lic,2).'</td>';
        $w_html.=chr(13).'        <td align="right">'.formatNumber($w_item_comp,2).'</td>';
        $w_html.=chr(13).'        <td align="right">'.formatNumber($w_item_unit,4).'</td>';
        if($l_sg_tramite=='AT') {
          $w_html.=chr(13).'        <td align="right">'.formatNumber(($w_item_comp*$w_item_unit),4).'</td>';
        } else {
          $w_html.=chr(13).'        <td align="right">'.formatNumber(($w_item_lic*$w_item_unit),4).'</td>';
        }

      }
    } 
    $w_html.=chr(13).'      <tr align="center">';
    $w_html.=chr(13).'        <td align="right" colspan="7"><b>Total</b></td>';
    $w_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_total_preco,4).'</b></td>';
    $w_html.=chr(13).'      </tr>';
    $w_html.=chr(13).'         </table></td></tr>';
    $w_html.=chr(13).'      <tr><td colspan="2">(*) Calculado a partir do preço médio do item.';
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
    $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>PESQUISAS DE PREÇO ('.count($RS1).')<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
    $w_html.=chr(13).'      <tr><td colspan="2"><div align="center">';
    $w_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
    $w_html.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Material</td>';
    $w_html.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Qtd.</td>';
    $w_html.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Fornecedor</td>';
    $w_html.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Dt.Pesq.</b></td>';
    $w_html.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Dias Valid.</b></td>';
    $w_html.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Valor</td>';
    $w_html.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Total</td>';
    $w_html.=chr(13).'        </tr>';
    // Lista os registros selecionados para listagem
    $w_atual        = 0;
    foreach($RS1 as $row){ 
      if ($w_atual!=f($row,'sq_material')) {
         $w_html.=chr(13).'      <tr valign="top">';
         if($w_tipo=='WORD') $w_html.=chr(13).'        <td rowspan='.f($row,'qtd_proposta').'>'.f($row,'nome').'</td>';
         else                $w_html.=chr(13).'        <td rowspan='.f($row,'qtd_proposta').'>'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'sq_material'),$TP,null).'</td>';
         $w_html.=chr(13).'        <td rowspan='.f($row,'qtd_proposta').' align="right">'.nvl(formatNumber(f($row,'quantidade'),2),'---').'</td>';
         $w_atual      = f($row,'sq_material');
      } else {
        $w_html.=chr(13).'      <tr valign="top">';
      }
      if($w_tipo=='WORD') $w_html.=chr(13).'        <td nowrap>'.f($row,'nm_fornecedor').'</td>';
      else                $w_html.=chr(13).'        <td nowrap>'.ExibePessoa('../',$w_cliente,f($row,'fornecedor'),$TP,f($row,'nm_fornecedor')).'</td>';
      $w_html.=chr(13).'        <td align="center">'.nvl(formataDataEdicao(f($row,'proposta_data'),5),'---').'</td>';
      $w_html.=chr(13).'        <td align="center">'.nvl(f($row,'dias_validade_proposta'),'---').'</td>';
      if(formatNumber(f($row,'valor_unidade'),4)>formatNumber(0,4)) {
        $w_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'valor_unidade'),4).'</td>';
      } else {
        $w_html.=chr(13).'        <td align="right">---</td>';
      }
      if(formatNumber(f($row,'valor_item'),4)>formatNumber(0,4)) {
        $w_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'valor_item'),4).'</td>';
      } else {
        $w_html.=chr(13).'        <td align="right">---</td>';
      }
    }
    $w_html.=chr(13).'      </center>';
    $w_html.=chr(13).'    </table>';
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
    $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>PROPOSTAS ('.count($RS1).')<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
    $w_html.=chr(13).'      <tr><td colspan="2">';
    $w_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
    $w_html.=chr(13).'        <tr valign="top">';
    $w_html.=chr(13).'          <td align="center" bgColor="#f0f0f0" rowspan=2><b>Ordem</td>';
    $w_html.=chr(13).'          <td align="center" bgColor="#f0f0f0" rowspan=2><b>Material</td>';
    $w_html.=chr(13).'          <td align="center" bgColor="#f0f0f0" rowspan=2><b>Qtd.</td>';
    $w_html.=chr(13).'          <td align="center" bgColor="#f0f0f0" rowspan=2><b>$ médio</td>';
    $w_html.=chr(13).'          <td align="center" bgColor="#f0f0f0" rowspan=2><b>Fornecedor</td>';
    $w_html.=chr(13).'          <td align="center" bgColor="#f0f0f0" rowspan=2><b>Dt.Prop.</b></td>';
    $w_html.=chr(13).'          <td align="center" bgColor="#f0f0f0" colspan=2><b>Dias Validade</b></td>';
    $w_html.=chr(13).'          <td align="center" bgColor="#f0f0f0" rowspan=2><b>Valor</td>';
    $w_html.=chr(13).'          <td align="center" bgColor="#f0f0f0" rowspan=2><b>Total</td>';
    $w_html.=chr(13).'        </tr>';
    $w_html.=chr(13).'        <tr valign="top">';
    $w_html.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Exigido</td>';    
    $w_html.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Proposto</td>';
    $w_html.=chr(13).'        </tr>';
    // Lista os registros selecionados para listagem
    $w_atual        = 0;
    foreach($RS1 as $row) { 
      $w_percentual_acrescimo = f($row,'percentual_acrescimo');
      if ($w_atual!=f($row,'sq_material')) {
         $w_html.=chr(13).'      <tr valign="top">';
         $w_html.=chr(13).'        <td align="center" rowspan='.f($row,'qtd_proposta').'>'.f($row,'ordem').'</td>';
         if($w_tipo=='WORD') $w_html.=chr(13).'        <td rowspan='.f($row,'qtd_proposta').'>'.f($row,'nome').'</td>';
         else                $w_html.=chr(13).'        <td rowspan='.f($row,'qtd_proposta').'>'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'sq_material'),$TP,null).'</td>';
         $w_html.=chr(13).'        <td rowspan='.f($row,'qtd_proposta').' align="right">'.nvl(formatNumber(f($row,'quantidade'),2),'---').'</td>';
         $w_html.=chr(13).'        <td rowspan='.f($row,'qtd_proposta').' align="right">'.nvl(formatNumber(f($row,'pesquisa_preco_medio'),2),'---').'</td>';
         $w_atual      = f($row,'sq_material');
      } else {
        $w_html.=chr(13).'      <tr valign="top">';
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
      if($w_tipo=='WORD') $w_html.=chr(13).'        <td nowrap '.$w_destaque.'>'.f($row,'nm_fornecedor').'</td>';
      else                $w_html.=chr(13).'        <td nowrap '.$w_destaque.'>'.ExibePessoa('../',$w_cliente,f($row,'fornecedor'),$TP,f($row,'nm_fornecedor')).'</td>';
      $w_html.=chr(13).'        <td align="center" '.$w_destaque.'>'.nvl(formataDataEdicao(f($row,'proposta_data'),5),'---').'</td>';
      $w_html.=chr(13).'        <td align="center" '.$w_destaque.'>'.nvl(f($row,'dias_validade_item'),'---').'</td>';
      $w_html.=chr(13).'        <td align="center" '.$w_destaque.'>'.nvl(f($row,'dias_validade_proposta'),'---').'</td>';
      if(formatNumber(f($row,'valor_unidade'),4)>formatNumber(0,4)) {
        $w_html.=chr(13).'        <td align="right" '.$w_destaque.'>'.formatNumber(f($row,'valor_unidade'),4).'</td>';
      } else {
        $w_html.=chr(13).'        <td align="right" '.$w_destaque.'>---</td>';
      }
      if(formatNumber(f($row,'valor_item'),4)>formatNumber(0,4)) {
        $w_html.=chr(13).'        <td align="right" '.$w_destaque.'>'.formatNumber(f($row,'valor_item'),4).'</td>';
      } else {
        $w_html.=chr(13).'        <td align="right" '.$w_destaque.'>---</td>';
      }      
    }
    $w_html.=chr(13).'      </center>';
    $w_html.=chr(13).'    </table>';
    $w_html.=chr(13).'<tr><td colspan="2"><b>Observação: propostas com fundo vermelho indicam descumprimento do prazo de validade ou valor fora da faixa aceitável ($ médio +/- '.$w_percentual_acrescimo.'%).';
  }
    
  // Se for listagem dos dados
  if ($l_O=='L' || $l_O=='V') {
    // Arquivos vinculados
    $RS1 = db_getSolicAnexo::getInstanceOf($dbms,$v_chave,null,$w_cliente);
    $RS1 = SortArray($RS1,'nome','asc');
    if (count($RS1)>0) {
      $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ANEXOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
      $w_html.=chr(13).'      <tr><td colspan="2"><div align="center">';
      $w_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
      $w_html.=chr(13).'          <tr align="center">';
      $w_html.=chr(13).'             <td bgColor="#f0f0f0" width="40%"><div><b>Título</b></div></td>';
      $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Descrição</b></div></td>';
      $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Tipo</b></div></td>';
      $w_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>KB</b></div></td>';
      $w_html.=chr(13).'          </tr>';
      foreach($RS1 as $row) {
        $w_html.=chr(13).'      <tr valign="top">';
        if($w_tipo=='WORD') $w_html.=chr(13).'        <td>'.f($row,'nome').'</td>';
        else       $w_html.=chr(13).'        <td>'.LinkArquivo('HL',$w_cliente,f($row,'chave_aux'),'_blank','Clique para exibir o arquivo em outra janela.',f($row,'nome'),null).'</td>';
        $w_html.=chr(13).'        <td>'.Nvl(f($row,'descricao'),'---').'</td>';
        $w_html.=chr(13).'        <td>'.f($row,'tipo').'</td>';
        $w_html.=chr(13).'        <td align="right">'.round(f($row,'tamanho')/1024,1).'&nbsp;</td>';
        $w_html.=chr(13).'      </tr>';
      } 
      $w_html.=chr(13).'         </table></td></tr>';
    }
    // Se for envio, executa verificações nos dados da solicitação
    $w_erro = ValidaCertame($w_cliente,$v_chave,substr($l_sigla,0,4).'GERAL',null,null,null,Nvl($l_tramite,0));
    if ($w_erro>'') {
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
    // Encaminhamentos
    $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>OCORRÊNCIAS E ANOTAÇÕES<hr NOSHADE color=#000000 SIZE=1></b></td></tr>';
    $RS1 = db_getSolicLog::getInstanceOf($dbms,$v_chave,null,'LISTA');
    $RS1 = SortArray($RS1,'phpdt_data','desc','sq_siw_solic_log','desc');
    $w_html.=chr(13).'   <tr><td colspan="2"><div align="center">';
    $w_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
    $w_html.=chr(13).'       <tr valign="top">';
    $w_html.=chr(13).'         <td align="center"><b>Data</b></td>';
    $w_html.=chr(13).'         <td align="center"><b>Ocorrência/Anotação</b></td>';
    $w_html.=chr(13).'         <td align="center"><b>Responsável</b></td>';
    $w_html.=chr(13).'         <td align="center"><b>Fase</b></td>';
    $w_html.=chr(13).'       </tr>';
    $i=0;
    if (count($RS1)==0) {
      $w_html.=chr(13).'      <tr><td colspan=4 align="center"><b>Não foram encontrados encaminhamentos.</b></td></tr>';
    } else {
      $i = 0;
      foreach ($RS1 as $row1) {
        $w_html.=chr(13).'      <tr valign="top">';
        if ($i==0) {
          $w_html.=chr(13).'     <td colspan=4>Fase atual: <b>'.f($row1,'fase').'</b></td></tr>';
          if ($w_ativo=='S') {
            // Recupera os responsáveis pelo tramite
            $RS2 = db_getTramiteResp::getInstanceOf($dbms,$v_chave,null,null);
            $w_html .= chr(13).'      <tr bgcolor="'.$w_TrBgColor.'" valign="top">';
            $w_html .= chr(13).'        <td colspan=4>Responsáveis pelo trâmite: <b>';
            if (count($RS2)>0) {
              $j = 0;
              foreach($RS2 as $row2) {
                if ($j==0) {
                  $l_tramite_resp = f($row2,'nome_resumido');
                  if (!($l_P1==4 || $l_P4==1)) $w_html .= chr(13).ExibePessoa($w_dir_volta,$w_cliente,f($row2,'sq_pessoa'),$TP,f($row2,'nome_resumido'));
                  else                         $w_html .= chr(13).f($row2,'nome_resumido');
                  $j = 1;
                } else {
                  if (strpos($l_tramite_resp,f($row,'nome_resumido'))===false) {
                    if (!($l_P1==4 || $l_P4==1)) $w_html .= chr(13).', '.ExibePessoa($w_dir_volta,$w_cliente,f($row2,'sq_pessoa'),$TP,f($row2,'nome_resumido'));
                    else                         $w_html .= chr(13).', '.f($row2,'nome_resumido');
                  }
                  $l_tramite_resp .= f($row2,'nome_resumido');
                }
              } 
            } 
            $w_html .= chr(13).'</b></td>';
          } 
          $w_html.=chr(13).'      <tr valign="top">';
          $i=1;
        }
        $w_html.=chr(13).'        <td nowrap align="center">'.FormataDataEdicao(f($row1,'phpdt_data'),3).'</td>';
        if (Nvl(f($row1,'caminho'),'')>'') {
          if (!($l_P1==4 || $l_P4==1)) $w_html.=chr(13).'        <td>'.CRLF2BR(Nvl(f($row1,'observacao'),'---').'<br>'.LinkArquivo('HL',$w_cliente,f($row1,'sq_siw_arquivo'),'_blank','Clique para exibir o anexo em outra janela.','Anexo - '.f($row1,'tipo').' - '.round(f($row1,'tamanho')/1024,1).' KB',null)).'</td>';
          else                         $w_html.=chr(13).'        <td>'.CRLF2BR(Nvl(f($row1,'observacao'),'---').'<br>Anexo - '.f($row1,'tipo').' - '.round(f($row1,'tamanho')/1024,1).' KB').'</td>';
        } else {
          $w_html.=chr(13).'        <td>'.CRLF2BR(Nvl(f($row1,'observacao'),'---')).'</td>';
        }         
        if (!($l_P1==4 || $l_P4==1)) $w_html.=chr(13).'        <td nowrap>'.ExibePessoa('../',$w_cliente,f($row1,'sq_pessoa'),$TP,f($row1,'responsavel')).'</td>';
        else                         $w_html.=chr(13).'        <td nowrap>'.f($row1,'responsavel').'</td>';
        if ((Nvl(f($row1,'sq_demanda_log'),'')>'')  && (Nvl(f($row1,'destinatario'),'')==''))   $w_html.=chr(13).'        <td nowrap>Anotação</td>';
        else $w_html.=chr(13).'        <td nowrap>'.Nvl(f($row1,'tramite'),'---').'</td>';
        $w_html.=chr(13).'      </tr>';
      } 
      $w_html.=chr(13).'         </table></td></tr>';
    } 
    $w_html.=chr(13).'</table>';
  }
  $w_html .= chr(13).'</table>';
  return $w_html;
}
?>