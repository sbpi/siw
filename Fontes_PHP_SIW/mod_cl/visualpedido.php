<?php
// =========================================================================
// Rotina de visualização dos dados da solicitacao
// -------------------------------------------------------------------------
function VisualPedido($v_chave,$l_O,$l_usuario,$l_P1,$l_tipo) {
  extract($GLOBALS);
  if ($l_tipo=='WORD') $w_TrBgColor=''; else $w_TrBgColor=$conTrBgColor;
  $l_html='';
  // Recupera os dados da solicitacao
  $RS = db_getSolicCL::getInstanceOf($dbms,null,$l_usuario,$SG,5,
          null,null,null,null,null,null,null,null,null,null,
          $v_chave,null,null,null,null,null,null,
          null,null,null,null,null,null,null,null,null,null,null);
         
  foreach($RS as $row){$RS=$row; break;}
  $w_tramite        = f($RS,'sq_siw_tramite');
  $w_sigla          = f($RS,'sigla');
  $w_sg_tramite     = f($RS,'sg_tramite');
  $w_fundo_fixo     = f($RS,'fundo_fixo');
  $w_tramite_ativo  = f($RS,'ativo');

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
    $l_html.=chr(13).'      <tr><td colspan="2" bgcolor="#f0f0f0"><font size="2"><b>'.upper(f($RS,'nome')).' '.f($RS,'codigo_interno').' ('.$v_chave.')</b></td>';
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
     
    // Identificação do lançamento
    $l_html.=chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
    if(nvl(f($RS,'processo'),'')!='') {
      $l_html.=chr(13).'      <tr><td><b>Número do protocolo: </b></td>';
      if ($w_embed!='WORD' && f($RS,'protocolo_siw')>'') {
        $l_html.=chr(13).'        <td><A class="HL" HREF="mod_pa/documento.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($RS,'protocolo_siw').'&w_tipo=&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro." target="processo">'.f($RS,'processo').'&nbsp;</a>';
      } else {
        $l_html.=chr(13).'        <td>'.f($RS,'processo');
      }
    }
    // Exibe a vinculação
    $l_html.=chr(13).'      <tr><td width="30%"><b>Vinculação: </b></td>';
    if (!($l_P1==4 || $l_tipo=='WORD')) $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS,'sq_solic_pai'),f($RS,'dados_pai'),'S').'</td></tr>';
    else                         $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS,'sq_solic_pai'),f($RS,'dados_pai'),'S','S').'</td></tr>';
    if (f($RS,'decisao_judicial')=='S') $l_html.=chr(13).'      <tr><td><b>Decisão judicial: </b></td><td>'.RetornaSimNao(f($RS,'decisao_judicial')).' </td></tr>';
    //$l_html.=chr(13).'      <tr><td><b>Prioridade: </b></td><td>'.f($RS,'nm_prioridade').' </td></tr>';
    $l_html.=chr(13).'      <tr><td><b>Data do pedido:</b></td><td>'.FormataDataEdicao(f($RS,'inicio')).' </td></tr>';
    if (nvl(f($RS,'fim'),'')!='' && $w_cliente!=10135) $l_html.=chr(13).'      <tr><td><b>Limite para atendimento:</b></td><td>'.FormataDataEdicao(f($RS,'fim')).' </td></tr>';
    $l_html.=chr(13).'      <tr><td><b>Valor estimado: </b></td><td>'.formatNumber(f($RS,'valor'),4).'</td></tr>';
    $l_html.=chr(13).'    <tr><td><b>Solicitante:<b></td>';
    if (!($l_P1==4 || $l_tipo=='WORD')){
      $l_html.=chr(13).'        <td>'.ExibePessoa(null,$w_cliente,f($RS,'solicitante'),$TP,f($RS,'nm_solic')).'</b></td>';
    } else {
      $l_html.=chr(13).'        <td>'.f($RS,'nm_solic').'</b></td>';
    }
      $l_html.=chr(13).'      <tr><td><b>Unidade solicitante: </b></td>';
    if (!($l_P1==4 || $l_tipo=='WORD')){
      $l_html.=chr(13).'        <td>'.ExibeUnidade($w_dir_volta,$w_cliente,f($RS,'nm_unidade_resp'),f($RS,'sq_unidade'),$TP).'</td></tr>';
    } else {
      $l_html.=chr(13).'        <td>'.f($RS,'nm_unidade_resp').'</td></tr>';
    }
    if(f($RS,'decisao_judicial')=='S') {
      $l_html.=chr(13).'      <tr><td><b>Número original: </b></td><td>'.f($RS,'numero_original').' </td></tr>';
      $l_html.=chr(13).'      <tr><td><b>Data de recebimento:</b></td><td>'.FormataDataEdicao(f($RS,'data_recebimento')).' </td></tr>'; 
      $l_html.=chr(13).'      <tr><td><b>Espécie documental:</b></td><td>'.f($RS,'nm_especie_documento').' </td></tr>'; 

    }
    $l_html.=chr(13).'      <tr><td><b>Justificativa:</b></td><td>'.crlf2br(f($RS,'justificativa')).' </td></tr>';
    $l_html.=chr(13).'      <tr><td><b>Observações:</b></td><td>'.CRLF2BR(Nvl(f($RS,'observacao'),'---')).' </td></tr>';
    $l_html.=chr(13).'      <tr><td><b>Pagamento por fundo fixo?<b></td><td>'.retornaSimNao(f($RS,'fundo_fixo')).'</b></td>';
    // Dados da conclusão da solicitação, se ela estiver nessa situação
    if (nvl(f($RS,'nota_conclusao'),'')!='') {
      //$l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS DA CONCLUSÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html.=chr(13).'      <tr><td><b>Nota de conclusão:</b></font></td><td>'.nvl(crlf2br(f($RS,'nota_conclusao')),'---').'</font></td></tr>';
    } 

    $l_html.=chr(13).'          </table></td></tr>';    
    
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
      $l_html.=chr(13).'      <tr><td align="center" colspan="2">';
      $l_html.=chr(13).'          <table width=100%  border="1" bordercolor="#00000">';
      $l_html.=chr(13).'          <tr valign="top">';
      $l_html.=chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>Nome</b></div></td>';
      $l_html.=chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>Sigla</b></div></td>';
      $l_html.=chr(13).'            <td bgColor="#f0f0f0"><div align="center"><b>Descrição</b></div></td>';
      $l_html.=chr(13).'          </tr>';
      $w_cor=$conTrBgColor;
      foreach ($RS1 as $row) {
        $l_html.=chr(13).'          <tr valign="top">';
        $l_html.=chr(13).'            <td>'.f($row,'nome').'</td>';
        $l_html.=chr(13).'            <td>'.f($row,'sigla').'</td>';
        $l_html.=chr(13).'            <td>'.crlf2br(f($row,'descricao')).'</td>';
        $l_html.=chr(13).'          </tr>';
      } 
      $l_html.=chr(13).'         </table></td></tr>';
    }
    
    //Listagem dos itens do pedido de compra
    $RS1 = db_getCLSolicItem::getInstanceOf($dbms,null,$v_chave,null,null,null,null,null,null,null,null,null,null,null);
    $RS1 = SortArray($RS1,'nm_tipo_material','asc','nm_tipo_material','asc','nome','asc');
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ITENS ('.count($RS1).')<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
    $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">';
    $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
    $l_html.=chr(13).'        <tr align="center">';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0" rowspan=2><b>Tipo</td>';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0" rowspan=2><b>Código</td>';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0" rowspan=2><b>Nome</td>';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0" rowspan=2><b>U.M.</td>';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0" colspan=2><b>Quantidade</td>';
    if ($w_fundo_fixo=='N') {
      $l_html.=chr(13).'          <td bgColor="#f0f0f0" rowspan=2><b>Compra/Certame</td>';
    } else {
      $l_html.=chr(13).'          <td bgColor="#f0f0f0" rowspan=2><b>Pagamento</td>';
    }
    if ($w_pede_valor_pedido=='N') $l_html.=chr(13).'          <td bgColor="#f0f0f0" colspan=2><b>Preço Estimado (*)</td>';
    $l_html.=chr(13).'        </tr>';
    $l_html.=chr(13).'        <tr align="center">';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Solicitada</td>';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Autorizada</td>';
    if ($w_pede_valor_pedido=='N') {
      $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Unitário</td>';
      $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Total</td>';
    }
    $l_html.=chr(13).'        </tr>';
    if (count($RS1)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      $l_html.=chr(13).'      <tr><td colspan=6 align="center"><b>Não foram encontrados registros.</b></td></tr>';
    } else {
      // Lista os registros selecionados para listagem
      $w_total_preco = 0;
      foreach($RS1 as $row){ 
        $l_html.=chr(13).'      <tr align="center">';
        $l_html.=chr(13).'        <td align="left">'.f($row,'nm_tipo_material').'</td>';
        $l_html.=chr(13).'        <td>'.f($row,'codigo_interno').'</td>';
        if (!($l_P1==4 || $l_tipo=='WORD')){
          $l_html.=chr(13).'        <td align="left">'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'sq_material'),$TP,null).'</td>';
        } else {
          $l_html.=chr(13).'        <td align="left">'.f($row,'nome').'</td>';
        }
        $l_html.=chr(13).'        <td align="center" title="'.f($row,'nm_unidade_medida').'">'.f($row,'sg_unidade_medida').'</td>';
        $l_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'quantidade'),0).'</td>';
        if($w_sg_tramite=='AF' || $w_sg_tramite=='EE' || $w_sg_tramite=='AT') {
          $l_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'quantidade_autorizada'),0).'</td>';
        } else {
          $l_html.=chr(13).'        <td align="center">---</td>';
        }
        if ($w_pede_valor_pedido=='N') {
          $l_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'pesquisa_preco_medio'),4).'</td>';
          if($w_sg_tramite=='AF' || $w_sg_tramite=='EE' || $w_sg_tramite=='AT') {
            $l_html.=chr(13).'        <td align="right">'.formatNumber((f($row,'pesquisa_preco_medio')*f($row,'quantidade_autorizada')),4).'</td>';
          } else {
            $l_html.=chr(13).'        <td align="right">'.formatNumber((f($row,'pesquisa_preco_medio')*f($row,'quantidade')),4).'</td>';
          }
          if($w_sg_tramite=='AF' || $w_sg_tramite=='EE' || $w_sg_tramite=='AT') {
            $w_total_preco += (f($row,'pesquisa_preco_medio')*f($row,'quantidade_autorizada'));
          } else {
            $w_total_preco += (f($row,'pesquisa_preco_medio')*f($row,'quantidade'));
          }
        }
        if (!($l_P1==4 || $l_tipo=='WORD')) $l_html.=chr(13).'        <td>'.nvl(exibeSolic($w_dir,f($row,'solic_filho'),f($row,'codigo_filho'),'N'),'---').'</td></tr>';
        else                         $l_html.=chr(13).'        <td>'.nvl(exibeSolic($w_dir,f($row,'solic_filho'),f($row,'codigo_filho'),'N','S'),'---').'</td></tr>';
        $l_html.=chr(13).'        </tr>';
      }
    } 
    if ($w_pede_valor_pedido=='N') {
      $l_html.=chr(13).'      <tr align="center">';
      $l_html.=chr(13).'        <td align="right" colspan="7"><b>Total</b></td>';
      $l_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_total_preco,4).'</b></td>';
      $l_html.=chr(13).'      </tr>';
    }
    $l_html.=chr(13).'         </table></td></tr>';
    if ($w_pede_valor_pedido=='N') $l_html.=chr(13).'      <tr><td colspan="2">(*) Calculado a partir do preço médio do item.';
  }
  if ($l_O=='L' || $l_O=='V') {
    // Se for listagem dos dados
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
        if (!($l_P1==4 || $l_tipo=='WORD')) $l_html.=chr(13).'        <td>'.LinkArquivo('HL',$w_cliente,f($row,'chave_aux'),'_blank','Clique para exibir o arquivo em outra janela.',f($row,'nome'),null).'</td>';
        else                         $l_html.=chr(13).'        <td>'.f($row,'nome').'</td>';
        $l_html.=chr(13).'        <td>'.Nvl(f($row,'descricao'),'---').'</td>';
        $l_html.=chr(13).'        <td>'.f($row,'tipo').'</td>';
        $l_html.=chr(13).'        <td align="right">'.round(f($row,'tamanho')/1024,1).'&nbsp;</td>';
        $l_html.=chr(13).'      </tr>';
      } 
      $l_html.=chr(13).'         </table></td></tr>';
    }
    
    // Se for envio, executa verificações nos dados da solicitação
    $w_erro = ValidaPedido($w_cliente,$v_chave,substr($w_sigla,0,4).'GERAL',null,null,null,Nvl($w_tramite,0));
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

    if ($O!='V') {
      // Encaminhamntos
      include_once($w_dir_volta.'funcoes/exibeLog.php');
      $l_html .= exibeLog($v_chave,$l_O,$l_usuario,$w_tramite_ativo,(($l_tipo=='WORD') ? 'WORD' : 'HTML'));
    }
  }
  $l_html.=chr(13).'</table>';
  return $l_html;
}
?>