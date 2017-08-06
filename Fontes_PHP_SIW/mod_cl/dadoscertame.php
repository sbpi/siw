<?php
// =========================================================================
// Rotina de geração em array dos dados da solicitacao
// -------------------------------------------------------------------------
function DadosCertame($v_chave,$l_O,$l_usuario,$l_P1,$l_tipo) {
  extract($GLOBALS);
  if ($l_tipo=='WORD') $w_TrBgColor=''; else $w_TrBgColor=$conTrBgColor;
  $l_array = array();
  
  $sql = new db_getCustomerData; $RS = $sql->getInstanceOf($dbms,$w_cliente);
  $w_cliente_arp = f($RS,'ata_registro_preco');

  // Verifica se o cliente tem solicitação de compras no menu
  $sql = new db_getMenuCode; $RS_MenuCode = $sql->getInstanceOf($dbms,$w_cliente,'CLPCCAD');
  if (count($RS_MenuCode)>0) $w_pedido = true; else $w_pedido = false;

  // Recupera os dados da solicitacao
  $sql = new db_getSolicCL; $RS = $sql->getInstanceOf($dbms,$w_menu,$l_usuario,$SG,3,null,null,null,null,null,null,null,null,null,null,
          $v_chave,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
  foreach($RS as $row){$RS=$row; break;}
  $l_tramite        = f($RS,'sq_siw_tramite');
  $l_sigla          = f($RS,'sigla');
  $l_sg_tramite     = f($RS,'sg_tramite');
  $w_tramite_ativo  = f($RS,'ativo');
  $w_certame        = f($RS,'certame');
  $w_gera_contrato  = f($RS,'gera_contrato');
  $w_participantes  = f($RS,'minimo_participantes');
  $w_sb_moeda       = nvl(f($RS,'sb_moeda'),'');
  $w_conclusao      = f($RS,'conclui_sem_proposta');

  // Verifica se o registro pai tem rubricas
  $w_exige_rubrica = false;
  if (nvl(f($RS,'sq_solic_pai'),'')!='' && piece(f($RS,'dados_pai'),null,'|@|',6)=='PJCAD') {
    $sql = new db_getSolicRubrica; $RSQuery = $sql->getInstanceOf($dbms,f($RS,'sq_solic_pai'),null,'S',null,null,null,null,null,'VISUAL');
    if (count($RSQuery)>0) {
      $w_solic_pai = f($RS,'sq_solic_pai');
      $w_exige_rubrica = true;
    }
  }

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
    $l_array['Código'] = f($RS,'codigo_interno');

    $l_array['Situação'] = f($RS,'nm_lcsituacao');
    $l_array['Código Externo'] = f($RS,'codigo_externo');
     
    // Identificação do certame
    $l_array['Protocolo'] = f($RS,'processo');
    $l_array['Modalidade'] = f($RS,'nm_lcmodalidade');
    
    // Exibe a vinculação
    if (f($RS,'dados_pai')!='???') {
      $l_array['Vinculação'] = exibeSolic($w_dir,f($RS,'sq_solic_pai'),f($RS,'dados_pai'),'S','S');
    }

    $l_array['Objeto'] = f($RS,'objeto');
    $l_array['Início da licitação'] = FormataDataEdicao(f($RS,'inicio'),8);

    if(f($RS,'or_tramite')>2 || f($RS,'sg_tramite')=='EE') {
      $l_array['Previsão de conclusão'] = FormataDataEdicao(f($RS,'fim'),8);
      $l_array['Prioridade'] = f($RS,'nm_prioridade');
    }

    if(f($RS,'valor')>0) {
      $l_array['Valor estimado'.(($w_sb_moeda!='') ? ' '.$w_sb_moeda.' ' : '')] = formatNumber(f($RS,'valor'),2);
      if (f($RS,'valor_alt')>0) {
        $l_array['Valor estimado '.f($RS,'sb_moeda_alt')] = formatNumber(f($RS,'valor_alt'),2);
        $l_array['Cotação'] = formatNumber(((f($RS,'sg_moeda_alt')=='BRL') ? (f($RS,'valor_alt')/f($RS,'valor')) : (f($RS,'valor')/f($RS,'valor_alt'))),4).' ('.FormataDataEdicao(nvl(f($RS,'inicio'),f($RS,'inclusao'))).')';
      }
    }
    if ($w_cliente_arp=='S' && nvl(f($RS,'arp'),'')!='') $l_array['Gera ARP'] = RetornaSimNao(f($RS,'arp'));

    $l_array['Responsável pela licitação'] = f($RS,'nm_exec');
    $l_array['Solicitante'] = f($RS,'nm_solic');

    $l_array['Unidade solicitante'] = f($RS,'nm_unidade_resp');

    if(f($RS,'decisao_judicial')=='S') {
      $l_array['Número original'] = f($RS,'numero_original');
      $l_array['Data de recebimento'] = FormataDataEdicao(f($RS,'data_recebimento'),8);
    }

    $l_array['Justificativa (motivo)'] = f($RS,'justificativa');
    $l_array['Observação'] = f($RS,'observacao');

    if (Nvl(f($RS,'justificativa_regra_pesquisas'),'')>'') {
      $l_array['Justificativa para o não cumprimento do número mínimo de pesquisas de preço'] = f($RS,'justificativa_regra_pesquisas');
    } 
    if (Nvl(f($RS,'justificativa_regra_propostas'),'')>'') {
      // Se o campo de justificativa estiver preenchido, exibe
      $l_array['Justificativa para o não cumprimento do número mínimo de propostas'] = f($RS,'justificativa_regra_propostas');
    } 
    
    if (nvl(f($RS,'sq_financeiro'),'')!='') {
      if (nvl(f($RS,'sq_projeto_rubrica'),'')!='') $l_array['Vinculação Orçamentária-Financeira']['Rubrica'] = f($RS,'cd_rubrica').' - '.f($RS,'nm_rubrica');
      if (nvl(f($RS,'sq_tipo_lancamento'),'')!='') $l_array['Vinculação Orçamentária-Financeira']['Classificação financeira'] = f($RS,'nm_lancamento');
      $l_texto = '';
      if (f($RS,'consumo')=='S') $l_texto = 'CONSUMO';
      if (f($RS,'permanente')=='S') (($l_texto=='') ? $l_texto = 'PERMANENTE' : $l_texto.=', PERMANENTE');
      if (f($RS,'servico')=='S')    (($l_texto=='') ? $l_texto = 'SERVIÇOS'   : $l_texto.=', SERVIÇOS');
      if (f($RS,'outros')=='S')     (($l_texto=='') ? $l_texto = 'OUTROS'     : $l_texto.=', OUTROS');
      $l_array['Classe(s) prevista(s)'] = $l_texto;
    }
    
    /*

    // Arquivos vinculados
    $sql = new db_getSolicAnexo; $RS1 = $sql->getInstanceOf($dbms,$v_chave,null,$w_cliente);
    $RS1 = SortArray($RS1,'nome','asc');
    if (count($RS1)>0) {
      $l_array.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ANEXOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
      $l_array.=chr(13).'      <tr><td colspan="2"><div align="center">';
      $l_array.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
      $l_array.=chr(13).'          <tr align="center">';
      $l_array.=chr(13).'             <td bgColor="#f0f0f0" width="40%"><div><b>Título</b></div></td>';
      $l_array.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Descrição</b></div></td>';
      $l_array.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Tipo</b></div></td>';
      $l_array.=chr(13).'            <td bgColor="#f0f0f0"><div><b>KB</b></div></td>';
      $l_array.=chr(13).'          </tr>';
      foreach($RS1 as $row) {
        $l_array.=chr(13).'      <tr valign="top">';
        if($l_tipo=='WORD') $l_array.=chr(13).'        <td>'.f($row,'nome').'</td>';
        else       $l_array.=chr(13).'        <td>'.LinkArquivo('HL',$w_cliente,f($row,'chave_aux'),'_blank','Clique para exibir o arquivo em outra janela.',f($row,'nome'),null).'</td>';
        $l_array.=chr(13).'        <td>'.Nvl(f($row,'descricao'),'---').'</td>';
        $l_array.=chr(13).'        <td>'.f($row,'tipo').'</td>';
        $l_array.=chr(13).'        <td align="right">'.round(f($row,'tamanho')/1024,1).'&nbsp;</td>';
        $l_array.=chr(13).'      </tr>';
      } 
      $l_array.=chr(13).'         </table></td></tr>';
    }
    
    //Dados da análise
    if(f($RS,'or_tramite')>2 || f($RS,'sg_tramite')=='EE') {
      $l_array.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS DA ANÁLISE<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      if (f($RS,'certame')=='S') {
        $l_array.=chr(13).'      <tr><td><b>Mínimo de dias de validade das propostas: </b></td><td>'.f($RS,'dias_validade_proposta').' </td></tr>';
        $l_array.=chr(13).'      <tr><td><b>Critério de '.(($w_cliente==6881) ? 'avaliação' : 'julgamento').': </b></td><td>'.nvl(f($RS,'nm_lcjulgamento'),'---').' </td></tr>';
      }
      if (nvl(f($RS,'certame'),'')!='')  {
        $l_array.=chr(13).'      <tr><td><b>Recebimento das propostas:</b></td><td>'.nvl(str_replace(', 00:00','',substr(FormataDataEdicao(f($RS,'phpdt_data_abertura'),3),0,-3)),'---').' </td></tr>';
        if(nvl(f($RS,'envelope_1'),'')!='') $l_array.=chr(13).'      <tr><td><b>Abertura do envelope 1: </b></td><td>'.substr(str_replace(', 00:00','',formataDataEdicao(f($RS,'phpdt_envelope_1'),3)),0,-3).' </td></tr>';
        if(nvl(f($RS,'envelope_2'),'')!='') $l_array.=chr(13).'      <tr><td><b>Abertura do envelope 2: </b></td><td>'.substr(str_replace(', 00:00','',formataDataEdicao(f($RS,'phpdt_envelope_2'),3)),0,-3).' </td></tr>';
        if(nvl(f($RS,'envelope_3'),'')!='') $l_array.=chr(13).'      <tr><td><b>Abertura do envelope 3: </b></td><td>'.substr(str_replace(', 00:00','',formataDataEdicao(f($RS,'phpdt_envelope_3'),3)),0,-3).' </td></tr>';
      }
      if(nvl(f($RS,'numero_ata'),'')!='') $l_array.=chr(13).'      <tr><td><b>Número da ata: </b></td><td>'.f($RS,'numero_ata').' </td></tr>';
      if (nvl(f($RS,'cd_lcfonterecurso'),'')!='') {
        $l_array.=chr(13).'      <tr><td><b>Fonte de recurso: </b></td><td>'.f($RS,'nm_lcfonterecurso').' ('.f($RS,'cd_lcfonterecurso').')</td></tr>';
      }
      if (nvl(f($RS,'cd_espec_despesa'),'')!='') {
        $l_array.=chr(13).'      <tr><td><b>Especificação de despesa: </b></td><td>'.f($RS,'cd_espec_despesa').' - '.f($RS,'nm_espec_despesa').' </td></tr>';
      }
      if (f($RS,'gera_contrato')=='S') {
        $l_array.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS PARA GERAÇÃO DO CONTRATO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
        $l_array.=chr(13).'      <tr><td><b>Tipo reajuste: </b></td><td>'.f($RS,'nm_tipo_reajuste').' </td></tr>';
        if(f($RS,'tipo_reajuste')==1) {
          $l_array.=chr(13).'      <tr><td><b>Índice base: </b></td><td>'.f($RS,'indice_base').' </td></tr>';
          $l_array.=chr(13).'      <tr><td><b>Indicador: </b></td><td>'.f($RS,'nm_eoindicador').' </td></tr>';  
        }
        $l_array.=chr(13).'      <tr><td><b>Limite de acréscimo/supressão (%): </b></td><td>'.formatNumber(f($RS,'limite_variacao')).' </td></tr>';
      }
    }
    
    // Objetivos estratégicos
    $sql = new db_getSolicObjetivo; $RS1 = $sql->getInstanceOf($dbms,$v_chave,null,null);
    $RS1 = SortArray($RS1,'nome','asc');
    if (count($RS1)>0) {
      $l_array.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>OBJETIVOS ESTRATÉGICOS ('.count($RS1).' )<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_array.=chr(13).'      <tr><td align="center" colspan="2">';
      $l_array.=chr(13).'          <table width=100%  border="1" bordercolor="#00000">';
      $l_array.=chr(13).'          <tr valign="top">';
      $l_array.=chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>Nome</b></div></td>';
      $l_array.=chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>Sigla</b></div></td>';
      $l_array.=chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>Descrição</b></div></td>';
      $l_array.=chr(13).'          </tr>';
      $w_cor=$conTrBgColor;
      foreach ($RS1 as $row) {
        $l_array.=chr(13).'          <tr valign="top">';
        $l_array.=chr(13).'            <td>'.f($row,'nome').'</td>';
        $l_array.=chr(13).'            <td>'.f($row,'sigla').'</td>';
        $l_array.=chr(13).'            <td>'.crlf2br(f($row,'descricao')).'</td>';
        $l_array.=chr(13).'          </tr>';
      } 
      $l_array.=chr(13).'         </table></td></tr>';
    }
        
    // Dados da conclusão da solicitação, se ela estiver nessa situação
    if (nvl(f($RS,'sg_tramite'),'')=='AT') {
      if (f($RS,'sigla')=='CLLCCAD') {
        $l_array.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS DA CONCLUSÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
        if (nvl(f($RS_Cliente,'sg_segmento'),'-')=='OI') {
          $l_array.=chr(13).'   <tr valign="top"><td><b>Data de homologação:</b></font></td><td>'.nvl(formataDataEdicao(f($RS,'data_homologacao')),'---').'</font></td></tr>';
        } elseif ($w_gera_contrato=='S') {
          $l_array.=chr(13).'   <tr valign="top"><td><b>Data de homologação:</b></font></td><td>'.nvl(formataDataEdicao(f($RS,'data_homologacao')),'---').'</font></td></tr>';
          $l_array.=chr(13).'   <tr valign="top"><td><b>Data Diário Oficial:</b></font></td><td>'.nvl(formataDataEdicao(f($RS,'data_diario_oficial')),'---').'</font></td></tr>';
          $l_array.=chr(13).'   <tr valign="top"><td><b>Página Diário Oficial:</b></font></td><td>'.nvl(f($RS,'pagina_diario_oficial'),'---').'</font></td></tr>';
        } else {
          $l_array.=chr(13).'   <tr valign="top"><td><b>Data de autorização:</b></font></td><td>'.nvl(formataDataEdicao(f($RS,'data_homologacao')),'---').'</font></td></tr>';
        }
        $l_array.=chr(13).'   <tr valign="top"><td><b>Nota de conclusão:</b></font></td><td>'.nvl(crlf2br(f($RS,'nota_conclusao')),'---').'</font></td></tr>';
  
        if (nvl(f($RS,'recebedor'),'')!='') {
          $l_array.=chr(13).'    <tr><td><b>Responsável pelo recebimento:<b></td>';
          if (!($l_P1==4 || $l_tipo=='WORD')){
            $l_array.=chr(13).'        <td>'.ExibePessoa(null,$w_cliente,f($RS,'recebedor'),$TP,f($RS,'nm_recebedor')).'</b></td>';
          } else {
            $l_array.=chr(13).'        <td>'.f($RS,'nm_recebedor').'</b></td>';
          }
        }

        if (nvl(f($RS,'executor'),'')!='' and $w_participantes > 0 && $w_cliente!=6881) {
          $l_array.=chr(13).'    <tr><td><b>Responsável pelo pagamento:<b></td>';
          if (!($l_P1==4 || $l_tipo=='WORD')){
            $l_array.=chr(13).'        <td>'.ExibePessoa(null,$w_cliente,f($RS,'executor'),$TP,f($RS,'nm_exec')).'</b></td>';
          } else {
            $l_array.=chr(13).'        <td>'.f($RS,'nm_exec').'</b></td>';
          }
        }

        if (nvl(f($RS,'sg_modalidade_artigo'),'')!='') {
          $l_array.=chr(13).'    <tr><td><b>Enquadramento:<b></td>';
          $l_array.=chr(13).'        <td>'.f($RS,'sg_modalidade_artigo').((nvl(f($RS,'sg_modalidade_artigo'),'')!='') ? ' - '.f($RS,'ds_modalidade_artigo') : '').'</b></td>';
        }
        if ($w_gera_contrato=='N' && $w_conclusao=='N') {
          $l_array.=chr(13).'    <tr><td><b>Pagamento por fundo fixo?<b></td>';
          $l_array.=chr(13).'        <td>'.retornaSimNao(f($RS,'fundo_fixo')).'</b></td>';
        }
        
        if ($w_participantes>0) {
          $sql = new db_getCLSolicItem; $RS1 = $sql->getInstanceOf($dbms,null,$v_chave,null,null,null,null,null,null,null,null,null,null,'VENCEDOR');
          $RS1 = SortArray($RS1,'ordem','asc','nome','asc','valor_unidade','asc');
          $l_array.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>VENCEDORES</b></font></td></tr>';  
          $l_array.=chr(13).'      <tr><td colspan="2">';
          $l_array.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
          $l_array.=chr(13).'        <tr valign="top">';
          $l_array.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Item</td>';
          $l_array.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Material</td>';
          $l_array.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Qtd.</td>';
          $l_array.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Vencedor</td>';
          $l_array.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Dt.Prop.</b></td>';
          $l_array.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Dias Validade</b></td>';
          $l_array.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Valor</td>';
          $l_array.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Total</td>';
          $l_array.=chr(13).'        </tr>';
          // Lista os registros selecionados para listagem
          $w_total = 0;
          foreach($RS1 as $row) { 
            $w_percentual_acrescimo = f($row,'percentual_acrescimo');
            $l_array.=chr(13).'      <tr valign="top">';
            $l_array.=chr(13).'        <td align="center">'.f($row,'ordem').'</td>';
            if($l_tipo=='WORD') $l_array.=chr(13).'        <td>'.f($row,'nome').'</td>';
            else                $l_array.=chr(13).'        <td>'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'sq_material'),$TP,null).'</td>';
            $l_array.=chr(13).'        <td align="right">'.nvl(formatNumber(f($row,'quantidade'),0),'---').'</td>';
            if($l_tipo=='WORD') $l_array.=chr(13).'        <td nowrap>'.f($row,'nm_fornecedor').'</td>';
            else                $l_array.=chr(13).'        <td nowrap>'.ExibePessoa('../',$w_cliente,f($row,'fornecedor'),$TP,f($row,'nm_fornecedor')).'</td>';
            $l_array.=chr(13).'        <td align="center">'.nvl(formataDataEdicao(f($row,'proposta_data'),5),'---').'</td>';
            $l_array.=chr(13).'        <td align="center">'.nvl(f($row,'dias_validade_proposta'),'---').'</td>';
            if(formatNumber(f($row,'valor_unidade'),4)>formatNumber(0,4)) {
              $l_array.=chr(13).'        <td align="right">'.formatNumber(f($row,'valor_unidade'),4).'</td>';
            } else {
              $l_array.=chr(13).'        <td align="right">---</td>';
            }
            if(f($row,'valor_item')>0) {
              $l_array.=chr(13).'        <td align="right">'.formatNumber(f($row,'valor_item'),4).'</td>';
            } else {
              $l_array.=chr(13).'        <td align="right">---</td>';
            }
            $w_total += f($row,'valor_item');
          }
          $l_array.=chr(13).'      <tr align="center">';
          $l_array.=chr(13).'        <td align="right" colspan="7"><b>Total</b></td>';
          $l_array.=chr(13).'        <td align="right"><b>'.formatNumber($w_total,4).'</b></td>';
          $l_array.=chr(13).'      </tr>';
          $l_array.=chr(13).'    </table>';
          if (Nvl(f($RS,'justificativa_preco_maior'),'')>'') {
            $l_array.=chr(13).'      <tr valign="top"><td width="30%"><b>Justificativa para vencedores com preço acima do menor:</b></td><td colspan="12">'.crLf2Br(f($RS,'justificativa_preco_maior'));
            if (f($RS,'sq_arquivo_justificativa')) {
              $l_array.=chr(13).'              <b>'.LinkArquivo('SS',$w_cliente,f($RS,'sq_arquivo_justificativa'),'_blank','Clique para exibir o arquivo atual.','Exibir arquivo',null).'</b>';
            } 
          } 
        }
      }
    } 
    
    //Listagem dos itens da licitação
    $sql = new db_getCLSolicItem; $RS1 = $sql->getInstanceOf($dbms,null,$v_chave,null,null,null,null,null,null,null,null,null,null,'LICITACAO');
    $RS1 = SortArray($RS1,'ordem','asc','valor_unidade','asc');
    if (count($RS1)>0) {
      $RS1 = SortArray($RS1,'ordem','asc','nm_tipo_material_pai','asc','nm_tipo_material','asc','nome','asc','dados_pai','asc'); 
      $l_array.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ITENS ('.count($RS1).')<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
      $l_array.=chr(13).'      <tr><td colspan="2"><div align="center">';
      $l_array.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
      $l_array.=chr(13).'        <tr align="center">';
      $colspan=0;
      $colspan++; $l_array.=chr(13).'          <td bgColor="#f0f0f0" rowspan=2><b>Item</td>';
      $colspan++; $l_array.=chr(13).'          <td bgColor="#f0f0f0" rowspan=2><b>Código</td>';
      if ($w_exige_rubrica) {
        $colspan++; $l_array.=chr(13).'          <td bgColor="#f0f0f0" rowspan=2><b>Rubrica</td>';
      }
      $colspan++; $l_array.=chr(13).'          <td bgColor="#f0f0f0" rowspan=2><b>Nome</td>';
      $colspan++; $l_array.=chr(13).'          <td bgColor="#f0f0f0" rowspan=2><b>U.M.</td>';
      if ($w_pedido) {
        $colspan++; $l_array.=chr(13).'          <td bgColor="#f0f0f0" rowspan=2><b>Pedido</td>';
      }
      $l_array.=chr(13).'          <td bgColor="#f0f0f0" colspan=2><b>Quantidade</td>';
      if ($w_gera_contrato=='S' and $l_sg_tramite=='AT') {
        $l_array.=chr(13).'          <td bgColor="#f0f0f0" rowspan=2><b>Contrato</td>';
      }
      if (f($RS,'ativo')=='S' && $w_pede_valor_pedido=='N') $l_array.=chr(13).'          <td bgColor="#f0f0f0" colspan=2><b>Preço Estimado (*)</td>';
      $l_array.=chr(13).'        </tr>';
      $l_array.=chr(13).'        <tr align="center">';
      $l_array.=chr(13).'          <td bgColor="#f0f0f0"><b>Licitada</td>';
      $l_array.=chr(13).'          <td bgColor="#f0f0f0"><b>Adquirida</td>';
      if (f($RS,'ativo')=='S' && $w_pede_valor_pedido=='N') {
        $l_array.=chr(13).'          <td bgColor="#f0f0f0"><b>Unitário</td>';
        $l_array.=chr(13).'          <td bgColor="#f0f0f0"><b>Total</td>';
      }
      $l_array.=chr(13).'        </tr>';
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
            $l_array.=chr(13).'      <tr><td colspan="'.($colspan-1).'"><td align="right" nowrap><b>Totais do item</td>';
            $l_array.=chr(13).'        <td align="right">'.formatNumber($w_item_lic,0).'</td>';
            $l_array.=chr(13).'        <td align="right">'.formatNumber($w_item_comp,0).'</td>';
            if(f($RS,'ativo')=='S') {
              $l_array.=chr(13).'        <td align="right">'.formatNumber($w_item_unit,4).'</td>';
              $l_array.=chr(13).'        <td align="right">'.formatNumber(($w_item_lic*$w_item_unit),4).'</td>';
            }
          }
          $l_array.=chr(13).'      <tr align="center" valign="top">';
          $l_array.=chr(13).'        <td>'.nvl(f($row,'ordem'),'---').'</td>';
          $l_array.=chr(13).'        <td>'.f($row,'codigo_interno').'</td>';
          if ($w_exige_rubrica) {
            $l_array.=chr(13).'        <td align="left">'.f($row,'cd_rubrica').' - '.f($row,'nm_rubrica').'</td>';
          }
          if($l_tipo=='WORD') $l_array.=chr(13).'        <td align="left">'.f($row,'nome');
          else                $l_array.=chr(13).'        <td align="left">'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'sq_material'),$TP,null);
          if (nvl(f($row,'det_item'),'')!='') {
            $l_array.='<hr><b>DETALHAMENTO</b>: '.crLf2Br(f($row,'det_item'));
          }
          $l_array.=chr(13).'        <td align="center" title="'.f($row,'nm_unidade_medida').'">'.f($row,'sg_unidade_medida').'</td>';
          $w_atual      = f($row,'sq_material');
          $w_exibe      = false;
          $w_item_lic   = 0;
          $w_item_comp  = 0;
          $w_item_unit  = 0;
        } else {
          $l_array.=chr(13).'      <tr align="center">';
          $l_array.=chr(13).'        <td colspan="'.(($w_exige_rubrica) ? 5 : 4).'"></td>';
          $w_exibe = true;
        }
        if ($w_pedido) {
          if($l_tipo!='WORD') $l_array.=chr(13).'        <td align="left" nowrap>'.nvl(exibeSolic($w_dir,f($row,'sq_solic_pai'),f($row,'dados_pai'),'N'),'---').'</td>';
          else                $l_array.=chr(13).'        <td align="left" nowrap>'.nvl(exibeSolic($w_dir,f($row,'sq_solic_pai'),f($row,'dados_pai'),'N','S'),'---').'</td>';
        }
        $l_array.=chr(13).'        <td align="right">'.formatNumber(f($row,'quantidade'),0).'</td>';
        $l_array.=chr(13).'        <td align="right">'.formatNumber(f($row,'quantidade_autorizada'),0).'</td>';
        if (f($RS,'ativo')=='S' && $w_pede_valor_pedido=='N') {
          $l_array.=chr(13).'        <td align="right">'.formatNumber(f($row,'pesquisa_preco_medio'),4).'</td>';
          if($l_sg_tramite=='AT') {
            $l_array.=chr(13).'        <td align="right">'.formatNumber((f($row,'pesquisa_preco_medio')*f($row,'quantidade_autorizada')),4).'</td>';
          } else {
            $l_array.=chr(13).'        <td align="right">'.formatNumber((f($row,'pesquisa_preco_medio')*f($row,'qtd_pedido')),4).'</td>';
          }        
          if($l_sg_tramite=='AT') {
            $w_total_preco += (f($row,'pesquisa_preco_medio')*f($row,'quantidade_autorizada'));
          } else {
            $w_total_preco += (f($row,'pesquisa_preco_medio')*f($row,'qtd_pedido'));
          }
        }        
        if ($w_gera_contrato=='S' and $l_sg_tramite=='AT') {
          if (!($l_P1==4 || $l_tipo=='WORD')) $l_array.=chr(13).'        <td>'.nvl(exibeSolic($w_dir,f($row,'solic_filho'),f($row,'dados_filho'),'N'),'---').'</td></tr>';
          else                                $l_array.=chr(13).'        <td>'.nvl(exibeSolic($w_dir,f($row,'solic_filho'),f($row,'dados_filho'),'N','S'),'---').'</td></tr>';
        }
        $l_array.=chr(13).'        </tr>';
        $w_item_lic   += f($row,'qtd_pedido');
        $w_item_comp  += f($row,'quantidade_autorizada');
        $w_item_unit  = f($row,'pesquisa_preco_medio');
      }
      if ($w_exibe && f($RS,'ativo')=='S' && $w_pede_valor_pedido=='N') {
        $l_array.=chr(13).'      <tr><td colspan="'.($colspan-1).'"><td align="right" nowrap><b>Totais do item</td>';
        $l_array.=chr(13).'        <td align="right">'.formatNumber($w_item_lic,0).'</td>';
        $l_array.=chr(13).'        <td align="right">'.formatNumber($w_item_comp,0).'</td>';
        $l_array.=chr(13).'        <td align="right">'.formatNumber($w_item_unit,4).'</td>';
        if(f($RS,'ativo')=='S') {
          $l_array.=chr(13).'        <td align="right">'.formatNumber(($w_item_lic*$w_item_unit),4).'</td>';
        }

      }
      if (f($RS,'ativo')=='S' && $w_pede_valor_pedido=='N') {
        $l_array.=chr(13).'      <tr align="center">';
        $l_array.=chr(13).'        <td align="right" colspan="8"><b>Total</b></td>';
        $l_array.=chr(13).'        <td align="right"><b>'.formatNumber($w_total_preco,4).'</b></td>';
        $l_array.=chr(13).'      </tr>';
        $l_array.=chr(13).'         </table></td></tr>';
        $l_array.=chr(13).'      <tr><td colspan="2">(*) Calculado a partir do preço médio do item.';
      } else {
        $l_array.=chr(13).'         </table></td></tr>';
      }
    } 
  }

  //Listagem das cotações da licitação
  $sql = new db_getCLSolicItem; $RS1 = $sql->getInstanceOf($dbms,null,$v_chave,null,null,null,null,null,null,null,null,null,null,'COTACAO');
  $RS1 = SortArray($RS1,'ordem','asc','valor_unidade','asc');
  $exibe=false;
  foreach($RS1 as $row) { 
    if(nvl(f($row,'nm_fornecedor'),'')!='') {
      $exibe=true;
      break;
    }
  }  
  if($exibe) {
    $l_array.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>PESQUISAS DE PREÇO ('.count($RS1).')<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
    $l_array.=chr(13).'      <tr><td colspan="2"><div align="center">';
    $l_array.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
    $l_array.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Material</td>';
    $l_array.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Qtd.</td>';
    $l_array.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Fornecedor</td>';
    $l_array.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Fonte</td>';
    $l_array.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Dt.Pesq.</b></td>';
    $l_array.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Dias Valid.</b></td>';
    $l_array.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Valor</td>';
    $l_array.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Total</td>';
    $l_array.=chr(13).'        </tr>';
    // Lista os registros selecionados para listagem
    $w_atual        = 999;
    $rowspan        = 0;
    foreach($RS1 as $row){ 
      if ($w_atual>=f($row,'qtd_proposta')) {
         $l_array.=chr(13).'      <tr valign="top">';
         if($l_tipo=='WORD') $l_array.=chr(13).'        <td rowspan='.f($row,'qtd_proposta').'>'.f($row,'nome').'</td>';
         else                $l_array.=chr(13).'        <td rowspan='.f($row,'qtd_proposta').'>'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'sq_material'),$TP,null).'</td>';
         $l_array.=chr(13).'        <td rowspan='.f($row,'qtd_proposta').' align="right">'.nvl(formatNumber(f($row,'quantidade'),0),'---').'</td>';
         $w_atual      = 0;
         $rowspan      = f($row,'qtd_proposta');
      } else {
        $l_array.=chr(13).'      <tr valign="top">';
      }
      $w_atual++;
      if($l_tipo=='WORD') $l_array.=chr(13).'        <td nowrap>'.f($row,'nm_fornecedor').'</td>';
      else                $l_array.=chr(13).'        <td nowrap>'.ExibePessoa('../',$w_cliente,f($row,'fornecedor'),$TP,f($row,'nm_fornecedor')).'</td>';
      $l_array.=chr(13).'        <td>'.f($row,'nm_origem').'</td>';
      $l_array.=chr(13).'        <td align="center">'.nvl(formataDataEdicao(f($row,'proposta_data'),5),'---').'</td>';
      $l_array.=chr(13).'        <td align="center">'.nvl(f($row,'dias_validade_proposta'),'---').'</td>';
      if(formatNumber(f($row,'valor_unidade'),4)>formatNumber(0,4)) {
        $l_array.=chr(13).'        <td align="right">'.formatNumber(f($row,'valor_unidade'),4).'</td>';
      } else {
        $l_array.=chr(13).'        <td align="right">---</td>';
      }
      if(formatNumber(f($row,'valor_item'),4)>formatNumber(0,4)) {
        $l_array.=chr(13).'        <td align="right">'.formatNumber(f($row,'valor_item'),4).'</td>';
      } else {
        $l_array.=chr(13).'        <td align="right">---</td>';
      }
    }
    $l_array.=chr(13).'      </center>';
    $l_array.=chr(13).'    </table>';
  }
  
  //Listagem dos itens da licitação
  $sql = new db_getCLSolicItem; $RSOrc = $sql->getInstanceOf($dbms,null,$v_chave,null,null,null,null,null,null,null,null,null,null,'LICPREVORC');
  $sql = new db_getCLSolicItem; $RSFin = $sql->getInstanceOf($dbms,null,$v_chave,null,null,null,null,null,null,null,null,null,null,'LICPREVFIN'); 
  if ($RSOrc || $RSFin) {
    $l_array.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>PREVISÃO ORÇAMENTÁRIA-FINANCEIRA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $l_array.=chr(13).'      <tr><td colspan=2><table width="100%" border=0 cellpadding=0 cellspacing=5><tr valign="top">';
    
    // Exibe previsão orçamentária
    $l_array.=chr(13).'        <td align="center" width="50%"><table width=100%  border="1" bordercolor="#00000">';
    $l_array.=chr(13).'          <tr align="center">';
    $l_array.=chr(13).'          <td colspan="3" bgColor="#f0f0f0"><b>ORÇAMENTÁRIO</b></td>';
    $l_array.=chr(13).'          </tr>';
    $l_array.=chr(13).'          <tr align="center">';
    $l_array.=chr(13).'          <td bgColor="#f0f0f0"><b>Projeto</b></td>';
    $l_array.=chr(13).'          <td bgColor="#f0f0f0"><b>Rubrica</b></td>';
    $l_array.=chr(13).'          <td bgColor="#f0f0f0"><b>Valor</b></td>';
    $l_array.=chr(13).'          </tr>';
    $w_cor=$w_TrBgColor;
    $w_tot = 0;
    foreach($RSOrc as $row) {
      $l_array.=chr(13).'      <tr valign="top">';
      if($l_tipo!='WORD') $l_array.=chr(13).'        <td>'.f($row,'cd_projeto').'</td>';
      else                $l_array.=chr(13).'        <td>'.exibeSolic($w_dir,f($row,'sq_projeto'),f($row,'cd_projeto'),'S','S').'</td>';
      $l_array.=chr(13).'          <td>'.f($row,'nm_rubrica').'&nbsp';
      $l_array.=chr(13).'          <td align="right">'.formatNumber(f($row,'vl_pesquisa')).'</td>';
      $w_tot += f($row,'vl_pesquisa');
      $l_array.=chr(13).'      </tr>';
    } 
    $l_array.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'" valign="top">';
    $l_array.=chr(13).'        <td align="right" colspan="2"><b>TOTAIS</b></td>';
    $l_array.=chr(13).'          <td align="right"><b>'.formatNumber($w_tot).'</b></td>';
    $l_array.=chr(13).'      </tr>';
    $l_array.=chr(13).'         </table></td>';

    // Exibe previsão financeira
    $l_array.=chr(13).'        <td align="center" width="50%"><table width=100%  border="1" bordercolor="#00000">';
    $l_array.=chr(13).'          <tr align="center">';
    $l_array.=chr(13).'          <td colspan="2" bgColor="#f0f0f0"><b>FINANCEIRO</b></td>';
    $l_array.=chr(13).'          </tr>';
    $l_array.=chr(13).'          <tr align="center">';
    $l_array.=chr(13).'          <td bgColor="#f0f0f0"><b>Tipo de Lançamento</b></td>';
    $l_array.=chr(13).'          <td bgColor="#f0f0f0"><b>Valor</b></td>';
    $l_array.=chr(13).'          </tr>';
    $w_cor=$w_TrBgColor;
    $w_tot = 0;
    foreach($RSFin as $row) {
      $l_array.=chr(13).'      <tr valign="top">';
      $l_array.=chr(13).'          <td>'.f($row,'nm_lancamento').'&nbsp';
      $l_array.=chr(13).'          <td align="right">'.formatNumber(f($row,'vl_pesquisa')).'</td>';
      $w_tot += f($row,'vl_pesquisa');
      $l_array.=chr(13).'      </tr>';
    } 
    $l_array.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'" valign="top">';
    $l_array.=chr(13).'        <td align="right"><b>TOTAIS</b></td>';
    $l_array.=chr(13).'          <td align="right"><b>'.formatNumber($w_tot).'</b></td>';
    $l_array.=chr(13).'      </tr>';
    $l_array.=chr(13).'         </table></td></tr>';
    $l_array.=chr(13).'       </table></td></tr>';
  }
  
  //Listagem das propostas da licitação
  $sql = new db_getCLSolicItem; $RS1 = $sql->getInstanceOf($dbms,null,$v_chave,null,null,null,null,null,null,null,null,null,null,'PROPOSTA');
  $RS1 = SortArray($RS1,'ordem','asc','nome','asc','valor_unidade','asc');
  $exibe=false;
  foreach($RS1 as $row) { 
    if(nvl(f($row,'nm_fornecedor'),'')!='') {
      $exibe=true;
      break;
    }
  }
  if($exibe) {
    $l_array.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>PROPOSTAS ('.count($RS1).')<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
    $l_array.=chr(13).'      <tr><td colspan="2">';
    $l_array.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
    $l_array.=chr(13).'        <tr>';
    $l_array.=chr(13).'          <td align="center" bgColor="#f0f0f0" rowspan=2><b>Item</td>';
    $l_array.=chr(13).'          <td align="center" bgColor="#f0f0f0" rowspan=2><b>Material</td>';
    $l_array.=chr(13).'          <td align="center" bgColor="#f0f0f0" rowspan=2><b>Qtd.</td>';
    $l_array.=chr(13).'          <td align="center" bgColor="#f0f0f0" rowspan=2><b>U.M.</td>';
    if ($w_pede_valor_pedido=='N') {
      // Exibe preço médio apenas se houver trâmite de pesquisa de preços
      $l_array.=chr(13).'          <td align="center" bgColor="#f0f0f0" rowspan=2><b>$ médio</td>';
    }
    $l_array.=chr(13).'          <td align="center" bgColor="#f0f0f0" rowspan=2><b>Fornecedor</td>';
    $l_array.=chr(13).'          <td align="center" bgColor="#f0f0f0" rowspan=2><b>Dt.Prop.</b></td>';
    $l_array.=chr(13).'          <td align="center" bgColor="#f0f0f0" colspan=2><b>Dias Validade</b></td>';
    $l_array.=chr(13).'          <td align="center" bgColor="#f0f0f0" rowspan=2><b>$ Unitário</td>';
    $l_array.=chr(13).'          <td align="center" bgColor="#f0f0f0" rowspan=2><b>Total</td>';
    $l_array.=chr(13).'        </tr>';
    $l_array.=chr(13).'        <tr valign="top">';
    $l_array.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Exigido</td>';    
    $l_array.=chr(13).'          <td align="center" bgColor="#f0f0f0"><b>Proposto</td>';
    $l_array.=chr(13).'        </tr>';
    // Lista os registros selecionados para listagem
    $w_atual        = 999;
    $rowspan        = 1;
    foreach($RS1 as $row) { 
      $w_percentual_acrescimo = f($row,'percentual_acrescimo');
      if ($w_atual>=$rowspan) {
         $l_array.=chr(13).'      <tr valign="top">';
         $l_array.=chr(13).'        <td align="center" rowspan='.f($row,'qtd_proposta').'>'.f($row,'ordem').'</td>';
         if($l_tipo=='WORD') $l_array.=chr(13).'        <td rowspan='.f($row,'qtd_proposta').'>'.f($row,'nome');
         else                $l_array.=chr(13).'        <td rowspan='.f($row,'qtd_proposta').'>'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'sq_material'),$TP,null);
         if($l_tipo=='WORD') $l_array.=chr(13).'        <td rowspan='.f($row,'qtd_proposta').'>'.f($row,'nome');
         if (nvl(f($row,'fabricante'),'')!=''||nvl(f($row,'marca_modelo'),'')!='') {
           $l_array.='<hr>';
           if (nvl(f($row,'fabricante'),'')!='') $l_array.='<b>FABRICANTE</b>: '.f($row,'fabricante').'&nbsp';
           if (nvl(f($row,'marca_modelo'),'')!='') $l_array.='<b>MARCA/MODELO</b>: '.f($row,'marca_modelo').'&nbsp';
         }
         $l_array.=chr(13).'        <td rowspan='.f($row,'qtd_proposta').' align="right">'.nvl(formatNumber(f($row,'quantidade'),0),'---').'</td>';
         $l_array.=chr(13).'        <td align="center" rowspan='.f($row,'qtd_proposta').' title="'.f($row,'nm_unidade_medida').'">'.f($row,'sg_unidade_medida').'</td>';
         if ($w_pede_valor_pedido=='N') {
           // Exibe preço médio apenas se houver trâmite de pesquisa de preços
           $l_array.=chr(13).'        <td rowspan='.f($row,'qtd_proposta').' align="right">'.nvl(formatNumber(f($row,'pesquisa_preco_medio'),2),'---').'</td>';
         }
         $w_atual = 0;
         $rowspan = f($row,'qtd_proposta');
      } else {
        $l_array.=chr(13).'      <tr valign="top">';
      }
      $w_atual++;
      if ($w_pede_valor_pedido=='S') {
        // Se a validade da proposta for menor que o exigido, destaca em vermelho
        if (nvl(f($row,'dias_validade_item'),0)>nvl(f($row,'dias_validade_proposta'),0)) {
          $w_destaque = ' BGCOLOR="'.$conTrBgColorLightRed1.'"';
        } else {
          $w_destaque = '';
        }
      } else {
        // Se a validade da proposta for menor que o exigido ou o valor fora da faixa aceitável, destaca em vermelho
        if (nvl(f($row,'dias_validade_item'),0)>nvl(f($row,'dias_validade_proposta'),0) ||
            (f($row,'pesquisa_preco_medio')+f($row,'variacao_valor')<f($row,'valor_unidade')) ||
            (f($row,'pesquisa_preco_medio')-f($row,'variacao_valor')>f($row,'valor_unidade'))
          ) {
          $w_destaque = ' BGCOLOR="'.$conTrBgColorLightRed1.'"';
        } else {
          $w_destaque = '';
        }
      }
      if($l_tipo=='WORD') $l_array.=chr(13).'        <td nowrap '.$w_destaque.'>'.f($row,'nm_fornecedor').'</td>';
      else                $l_array.=chr(13).'        <td nowrap '.$w_destaque.'>'.ExibePessoa('../',$w_cliente,f($row,'fornecedor'),$TP,f($row,'nm_fornecedor')).'</td>';
      $l_array.=chr(13).'        <td align="center" '.$w_destaque.'>'.nvl(formataDataEdicao(f($row,'proposta_data'),5),'---').'</td>';
      $l_array.=chr(13).'        <td align="center" '.$w_destaque.'>'.nvl(f($row,'dias_validade_item'),'---').'</td>';
      $l_array.=chr(13).'        <td align="center" '.$w_destaque.'>'.nvl(f($row,'dias_validade_proposta'),'---').'</td>';
      if(formatNumber(f($row,'valor_unidade'),4)>formatNumber(0,4)) {
        $l_array.=chr(13).'        <td align="right" '.$w_destaque.'>'.formatNumber(f($row,'valor_unidade'),4).'</td>';
      } else {
        $l_array.=chr(13).'        <td align="right" '.$w_destaque.'>---</td>';
      }
      if(formatNumber(f($row,'valor_item'),4)>formatNumber(0,4)) {
        $l_array.=chr(13).'        <td align="right" '.$w_destaque.'>'.formatNumber(f($row,'valor_item'),4).'</td>';
      } else {
        $l_array.=chr(13).'        <td align="right" '.$w_destaque.'>---</td>';
      }      
    }
    $l_array.=chr(13).'      </center>';
    $l_array.=chr(13).'    </table>';
    $l_array.=chr(13).'<tr><td colspan="2"><b>Observação: propostas com fundo vermelho indicam descumprimento do prazo de validade'.(($w_pede_valor_pedido=='S') ? '' : ' ou valor fora da faixa aceitável ($ médio +/- '.$w_percentual_acrescimo.'%)');
  }
    
  // Se for listagem dos dados
  if ($l_O=='L' || $l_O=='V') {
    $sql = new db_getSolicList; $RS1 = $sql->getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,'FILHOS',null,
      null,null,null,null,null,null,null,null,null,null,$v_chave, null, null, null, null, null, null,
      null, null, null, null, null, null, null, null, null);
    $RS1 = SortArray($RS1,'inclusao','asc', 'codigo_interno', 'asc');
    if (count($RS1)>0) {
      $l_array.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>EXECUÇÃO FINANCEIRA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';   
      $l_array.=chr(13).'      <tr><td colspan="2">';
      $l_array.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
      $l_array.=chr(13).'        <tr bgcolor="'.$conTrBgColor.'" align="center">';
      $l_array.=chr(13).'          <td><b>Código</td>';
      $l_array.=chr(13).'          <td><b>Histórico</td>';
      $l_array.=chr(13).'          <td><b>Valor</td>';
      $l_array.=chr(13).'          <td><b>Situação atual</td>';
      $l_array.=chr(13).'          <td><b>Data Pagamento</td>';
      $l_array.=chr(13).'        </tr>';
      $w_cor=$conTrBgColor;
      $i             = 1;
      $w_total       = 0;
      foreach ($RS1 as $row) {
        if (f($row,'sigla')=='FNREVENT') {
          $w_total       -= f($row,'valor');
        } else {
          $w_total       += f($row,'valor');
        }
        $l_array.=chr(13).'        <tr valign="middle">';
        $l_array.=chr(13).'        <td>'.exibeSolic($w_dir,f($row,'sq_siw_solicitacao'),f($row,'dados_solic'),'N','S').'</td>';
        $l_array.=chr(13).'           <td>'.f($row,'descricao').'</td>';
        $l_array.=chr(13).'           <td align="right">'.formatNumber(f($row,'valor')).'</td>';
        $l_array.=chr(13).'           <td>'.f($row,'nm_tramite').'</td>';
        $l_array.=chr(13).'           <td align="center">'.nvl(formataDataEdicao(f($row,'conclusao')),'&nbsp;').'</td>';
        $l_array.=chr(13).'        </tr>';
      } 
      $l_array.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'" valign="top">';
      $l_array.=chr(13).'        <td align="right" colspan="2"><b>TOTAL</b></td>';
      $l_array.=chr(13).'        <td align="right"><b>'.formatNumber($w_total).'</b></td>';
      $l_array.=chr(13).'        <td align="right" colspan="2">&nbsp;</td>';
      $l_array.=chr(13).'      </tr>';
      $l_array.=chr(13).'         </table></td></tr>';
    }
  
    // Se for envio, executa verificações nos dados da solicitação
    $w_erro = ValidaCertame($w_cliente,$v_chave,$l_sigla,null,null,null,Nvl($l_tramite,0));
    if ($w_erro>'') {
      $l_array.=chr(13).'<tr><td colspan=2><font size=2>';
      $l_array.=chr(13).'<HR>';
      if (substr($w_erro,0,1)=='0') {
        $l_array.=chr(13).'  <font color="#BC3131"><b>ATENÇÃO:</b></font> Foram identificadas as pendências listadas abaixo, não sendo possível seu encaminhamento para fases posteriores à atual.';
      } elseif (substr($w_erro,0,1)=='1') {
        $l_array.=chr(13).'  <font color="#BC3131"><b>ATENÇÃO:</b></font> Foram identificadas as pendências listadas abaixo. Seu encaminhamento para fases posteriores à atual só pode ser feito por um gestor do sistema ou deste módulo.';
      } else {
        $l_array.=chr(13).'  <font color="#BC3131"><b>ATENÇÃO:</b></font> Foram identificados os alertas listados abaixo. Eles não impedem o encaminhamento para fases posteriores à atual, mas convém sua verificação.';
      } 
      $l_array.=chr(13).'  <ul>'.substr($w_erro,1,1000).'</ul>';
      $l_array.=chr(13).'  </font></td></tr>';
    }
    if ($O!='V') {
      // Encaminhamentos
      include_once($w_dir_volta.'funcoes/exibeLog.php');
      $l_array .= exibeLog($v_chave,$l_O,$l_usuario,$w_tramite_ativo,(($l_tipo=='WORD') ? 'WORD' : 'HTML'));
    }
     * */
  }
  return $l_array;
}
?>