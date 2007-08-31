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
  $w_tramite        = f($RS,'sq_siw_tramite');
  $w_sigla          = f($RS,'sigla');
  $w_sg_tramite     = f($RS,'sg_tramite');

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
    // Exibe a vinculação
    $l_html.=chr(13).'      <tr><td valign="top"><b>Vinculação: </b></td>';
    if($l_tipo!='WORD') $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS,'sq_solic_pai'),f($RS,'dados_pai'),'S').'</td></tr>';
    else       $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS,'sq_solic_pai'),f($RS,'dados_pai'),'S','S').'</td></tr>';

    // Se a classificação foi informada, exibe.
    if (Nvl(f($RS,'sq_cc'),'')>'') {
      $l_html .= chr(13).'      <tr><td width="30%"><b>Classificação:<b></td>';
      $l_html .= chr(13).'        <td>'.f($RS,'nm_cc').' </td></tr>';
    }
    $w_html.=chr(13).'      <tr><td><b>Prioridade: </b></td>';
    $w_html.=chr(13).'        <td>'.f($RS,'nm_prioridade').' </td></tr>';
    $w_html.=chr(13).'      <tr><td><b>Data do pedido:</b></td>';
    $w_html.=chr(13).'         <td>'.FormataDataEdicao(f($RS,'inicio')).' </td></tr>';
    $w_html.=chr(13).'      <tr><td><b>Limite para atendimento:</b></td>';
    $w_html.=chr(13).'         <td>'.FormataDataEdicao(f($RS,'fim')).' </td></tr>';
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
    $w_html.=chr(13).'      <tr><td><b>Valor: </b></td>';
    $w_html.=chr(13).'      <td>'.formatNumber(f($RS,'valor'),2).'</td></tr>';
    $w_html.=chr(13).'          </table></td></tr>';    
    
    //Listagem dos itens da licitação
    $RS1 = db_getCLSolicItem::getInstanceOf($dbms,null,$v_chave,null,null,'LICITACAO');
    $RS1 = SortArray($RS1,'nm_tipo_material_pai','asc','nm_tipo_material','asc','nome','asc'); 
    $w_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ITENS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
    $w_html.=chr(13).'      <tr><td colspan="2"><div align="center">';
    $w_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
    $w_html.=chr(13).'        <tr align="center">';
    $w_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Tipo</td>';
    $w_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Código</td>';
    $w_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Nome</td>';
    $w_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Qtd</td>';
    if($w_sg_tramite=='AT') {
      $w_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Qtd. autorizada</td>';
    }
    $w_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Preço(UN)</td>';
    $w_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Total</td>';    
    $w_html.=chr(13).'        </tr>';
    if (count($RS1)<=0) {
      // Se não foram selecionados registros, exibe mensagem
      $w_html.=chr(13).'      <tr><td colspan=7 align="center"><b>Não foram encontrados registros.</b></td></tr>';
    } else {
      // Lista os registros selecionados para listagem
      $w_total         = 0;
      $w_total_inicial = 0;
      $w_total_preco   = 0;
      foreach($RS1 as $row){ 
        $w_html.=chr(13).'      <tr align="center">';
        $w_html.=chr(13).'        <td>'.f($row,'nm_tipo_material_pai').'</td>';
        $w_html.=chr(13).'        <td>'.f($row,'codigo_interno').'</td>';
        if($w_tipo=='WORD') $w_html.=chr(13).'        <td align="left">'.f($row,'nome').'</td>';
        else                $w_html.=chr(13).'        <td align="left">'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'sq_material'),$TP,null).'</td>';
        $w_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'quantidade'),2).'</td>';
        if($w_sg_tramite=='AT') {
         $w_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'quantidade_autorizada'),2).'</td>';
        }
        $w_html.=chr(13).'        <td align="right">'.formatNumber(f($row,'pesquisa_preco_medio'),4).'</td>';
        if($w_sg_tramite=='AT') {
          $w_html.=chr(13).'        <td align="right">'.formatNumber((f($row,'pesquisa_preco_medio')*f($row,'quantidade_autorizada')),4).'</td>';
        } else {
          $w_html.=chr(13).'        <td align="right">'.formatNumber((f($row,'pesquisa_preco_medio')*f($row,'quantidade')),4).'</td>';
        }        
        $w_total += f($row,'quantidade_autorizada');
        $w_total_inicial += f($row,'quantidade');
        if($w_sg_tramite=='AT') {
          $w_total_preco += (f($row,'pesquisa_preco_medio')*f($row,'quantidade_autorizada'));
        } else {
          $w_total_preco += (f($row,'pesquisa_preco_medio')*f($row,'quantidade'));
        }        
        $w_html.=chr(13).'        </tr>';
      }
    } 
    $w_html.=chr(13).'      <tr align="center">';
    $w_html.=chr(13).'        <td align="right" colspan="3"><b>Total</b></td>';
    $w_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_total_inicial,2).'</b></td>';
    if($w_sg_tramite=='AT') {
      $w_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_total,2).'</b></td>';
    }
    $w_html.=chr(13).'        <td align="right">&nbsp;</td>';    
    $w_html.=chr(13).'        <td align="right"><b>'.formatNumber($w_total_preco,4).'</b></td>';
    $w_html.=chr(13).'      </tr>';
    $w_html.=chr(13).'         </table></td></tr>';
  }
  if ($l_O=='L' || $l_O=='V') {
    // Se for listagem dos dados
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
      $w_cor=$conTrBgColor;
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
    $w_erro = ValidaPedido($w_cliente,$v_chave,substr($w_sigla,0,4).'GERAL',null,null,null,Nvl($w_tramite,0));
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
      $w_html.=chr(13).'      <tr><td colspan=6 align="center"><font size="1"><b>Não foram encontrados encaminhamentos.</b></td></tr>';
    } else {
      $w_html.=chr(13).'      <tr valign="top">';
      $w_cor=$conTrBgColor;
      $i = 0;
      foreach($RS as $row) {
        if ($i==0) {
          $w_html.=chr(13).'        <td colspan=6><font size="1">Fase atual: <b>'.f($row,'fase').'</b></td>';
          $i = 1;
        }
        $w_html.=chr(13).'      <tr valign="top">';
        $w_html.=chr(13).'        <td nowrap><font size="1">'.FormataDataEdicao(f($row,'phpdt_data'),3).'</td>';
        if (Nvl(f($row,'caminho'),'')>'' && $w_tipo!='WORD') {
          $w_html.=chr(13).'      <td><font size="1">'.CRLF2BR(Nvl(f($row,'despacho'),'---').'<br>'.LinkArquivo('HL',$w_cliente,f($row,'sq_siw_arquivo'),'_blank','Clique para exibir o anexo em outra janela.','Anexo - '.f($row,'tipo').' - '.round(f($row,'tamanho')/1024,1).' KB',null)).'</td>';
        } else {
          $w_html.=chr(13).'      <td><font size="1">'.CRLF2BR(Nvl(f($row,'despacho'),'---')).'</td>';
        } 
        if($w_tipo=='WORD') $w_html.=chr(13).'        <td nowrap><font size="1">'.f($row,'responsavel').'</td>';
        else       $w_html.=chr(13).'        <td nowrap><font size="1">'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'responsavel')).'</td>';
        if (nvl(f($row,'sq_cl_log'),'')>'' && nvl(f($row,'destinatario'),'')>'') {
          if($w_tipo=='WORD') $w_html.=chr(13).'      <td nowrap><font size="1">'.f($row,'destinatario').'</td>';
          else       $w_html.=chr(13).'      <td nowrap><font size="1">'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'sq_pessoa_destinatario'),$TP,f($row,'destinatario')).'</td>';
        } elseif (nvl(f($row,'sq_cl_log'),'')>'' && nvl(f($row,'destinatario'),'')=='') {
          $w_html.=chr(13).'      <td nowrap><font size="1">Anotação</td>';
        } else {
          if(strpos(f($row,'despacho'),'***')!==false) {
            $w_html.=chr(13).'        <td nowrap>---</td>';
          } else {
            $w_html.=chr(13).'        <td nowrap>'.Nvl(f($row,'tramite'),'---').'</td>';
          }
        } 
        $w_html.=chr(13).'      </tr>';
      } 
    } 
    $w_html.=chr(13).'         </table></td></tr>';
    $w_html.=chr(13).'</table>';
  }
  $w_html .= chr(13).'</table>';
  return $w_html;
}
?>