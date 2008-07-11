<?
// =========================================================================
// Rotina de visualização dos dados da solicitacao
// -------------------------------------------------------------------------
function VisualARP($v_chave,$l_O,$l_usuario,$l_P1,$l_P4) {
  extract($GLOBALS);
  if ($l_P4==1) $w_TrBgColor=''; else $w_TrBgColor=$conTrBgColor;
  $l_html='';
  $w_erro='';
  // Recupera os dados da solicitacao
  $RS = db_getSolicCL::getInstanceOf($dbms,null,$l_usuario,$SG,5,
          null,null,null,null,null,null,null,null,null,null,
          $v_chave,null,null,null,null,null,null,
          null,null,null,null,null,null,null,null,null,null,null);
  foreach($RS as $row){$RS=$row; break;}
  $w_tramite        = f($RS,'sq_siw_tramite');
  $w_sigla          = f($RS,'sigla');
  $w_sg_tramite     = f($RS,'sg_tramite');
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
    $l_html.=chr(13).'      <tr><td colspan="2" bgcolor="#f0f0f0"><font size="2"><b>'.f($RS,'codigo_interno').' ('.$v_chave.')</b></td>';
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
     
    // Identificação do lançamento
    $l_html .= chr(13).'      <tr><td valign="top" colspan="2"><table border=0 width="100%" cellspacing=0>';
    // Exibe a vinculação
    $l_html.=chr(13).'      <tr><td width="30%"><b>Vinculação: </b></td>';
    if (!($l_P1==4 || $l_P4==1)) $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS,'sq_solic_pai'),f($RS,'dados_pai'),'S').'</td></tr>';
    else                         $l_html.=chr(13).'        <td>'.exibeSolic($w_dir,f($RS,'sq_solic_pai'),f($RS,'dados_pai'),'S','S').'</td></tr>';
    $l_html.=chr(13).'      <tr><td><b>Prioridade: </b></td>';
    $l_html.=chr(13).'        <td>'.f($RS,'nm_prioridade').' </td></tr>';
    $l_html.=chr(13).'      <tr><td><b>Data do pedido:</b></td>';
    $l_html.=chr(13).'         <td>'.FormataDataEdicao(f($RS,'inicio')).' </td></tr>';
    $l_html.=chr(13).'      <tr><td><b>Limite para atendimento:</b></td>';
    $l_html.=chr(13).'         <td>'.FormataDataEdicao(f($RS,'fim')).' </td></tr>';
    $l_html.=chr(13).'      <tr><td><b>Valor estimado: </b></td>';
    $l_html.=chr(13).'      <td>'.formatNumber(f($RS,'valor'),4).'</td></tr>';
    $l_html .= chr(13).'    <tr><td><b>Solicitante:<b></td>';
    if (!($l_P1==4 || $l_P4==1)){
      $l_html .= chr(13).'        <td>'.ExibePessoa(null,$w_cliente,f($RS,'solicitante'),$TP,f($RS,'nm_solic')).'</b></td>';
    } else {
      $l_html .= chr(13).'        <td>'.f($RS,'nm_solic').'</b></td>';
    }
    if (!($l_P1==4 || $l_P4==1)){
      $l_html.=chr(13).'      <tr><td><b>Unidade solicitante: </b></td>';
      $l_html.=chr(13).'        <td>'.ExibeUnidade($w_dir_volta,$w_cliente,f($RS,'nm_unidade_resp'),f($RS,'sq_unidade'),$TP).'</td></tr>';
    } else {
      $l_html.=chr(13).'      <tr><td><b>Unidade solicitante: </b></td>';
      $l_html.=chr(13).'        <td>'.f($RS,'nm_unidade_resp').'</td></tr>';
    }
    $l_html.=chr(13).'      <tr><td><b>Espécie documental:</b></td>';
    $l_html.=chr(13).'         <td>'.f($RS,'nm_especie_documento').' </td></tr>'; 
    $l_html.=chr(13).'      <tr><td><b>Número original: </b></td>';
    $l_html.=chr(13).'        <td>'.f($RS,'numero_original').' </td></tr>';
    $l_html.=chr(13).'      <tr><td><b>Data de recebimento:</b></td>';
    $l_html.=chr(13).'         <td>'.FormataDataEdicao(f($RS,'data_recebimento')).' </td></tr>'; 
    $l_html.=chr(13).'      <tr><td><b>Justificativa:</b></td>';
    $l_html.=chr(13).'         <td>'.f($RS,'justificativa').' </td></tr>';
    $l_html.=chr(13).'      <tr><td><b>Observação:</b></td>';
    $l_html.=chr(13).'      <td>'.CRLF2BR(Nvl(f($RS,'observacao'),'---')).' </td></tr>';
    $l_html.=chr(13).'          </table></td></tr>';    
    
    // Objetivos estratégicos
    $RS1 = db_getSolicObjetivo::getInstanceOf($dbms,$v_chave,null,null);
    $RS1 = SortArray($RS1,'nome','asc');
    if (count($RS1)>0) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>OBJETIVOS ESTRATÉGICOS ('.count($RS1).' )<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html .= chr(13).'      <tr><td align="center" colspan="2">';
      $l_html.=chr(13).'          <table width=100%  border="1" bordercolor="#00000">';
      $l_html .= chr(13).'          <tr valign="top">';
      $l_html .= chr(13).'            <td bgColor="#f0f0f0"><b>Nome</b></td>';
      $l_html .= chr(13).'            <td bgColor="#f0f0f0"><b>Sigla</b></td>';
      $l_html .= chr(13).'            <td bgColor="#f0f0f0"><b>Descrição</b></td>';
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
    
    //Listagem dos itens do pedido de compra
    $RS1 = db_getCLSolicItem::getInstanceOf($dbms,null,$v_chave,null,null,null,null,null,null,null,null,null,null,'ARP');
    $RS1 = SortArray($RS1,'numero_ata','asc','ordem_ata','asc','nome','asc'); 
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ITENS ('.count($RS1).')<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
    if (count($RS1)==0) {
      // Se não foram selecionados registros, exibe mensagem
      $l_html.=chr(13).'      <tr><td colspan=2 align="center"><b>Nenhum item encontrado.</b></td></tr>';
    } else {
      // Configura texto para as quantidades e valores dos itens
      if (strpos('EE,AT',$w_sg_tramite)!==false) {
        $w_txt_qtd   = 'Quantidade autorizada';
        $w_txt_valor = '$ Autorizado';
      } else {
        $w_txt_qtd   = 'Quantidade solicitada';
        $w_txt_valor = '$ Solicitado';
      }
      $l_html.=chr(13).'      <tr><td colspan="2" align="center">';
      $l_html.=chr(13).'        <table width=100%  border="0" bordercolor="#00000">';
      // Lista os registros selecionados para listagem
      $w_total_preco = 0;
      $i             = 0;
      $w_arp_atual   = '';
      foreach($RS1 as $row){ 
        if (f($row,'cancelado')=='S') $w_cor = ' BGCOLOR="'.$conTrBgColorLightRed2.'" '; else $w_cor = '';
        if ($w_arp_atual!=f($row,'numero_ata')) {
          $l_html.=chr(13).'      <tr valign="top">';
          $l_html.=chr(13).'        <td width="1%" nowrap><font size="2"><b>ARP '.f($row,'numero_ata').'</b></font></td>';
          $l_html.=chr(13).'        <td>Validade:<br><b>'.formataDataEdicao(f($row,'fim'),5).'</b></td>';
          if (!($l_P1==4 || $l_P4==1)){
            $l_html.=chr(13).'        <td colspan=3>Detentor:<br><b>'.ExibePessoa(null,$w_cliente,f($row,'sq_detentor'),$TP,f($row,'nm_detentor')).'</b></td>';
          } else {
            $l_html.=chr(13).'        <td colspan=3>Detentor:<br><b>'.f($row,'nm_detentor').'</b></td>';
          }
          $l_html.=chr(13).'      <tr><td><td colspan="4"><hr NOSHADE color=#000000 SIZE=1></td></tr>'; 
          $w_arp_atual = f($row,'numero_ata');
        }
        $l_html.=chr(13).'      <tr valign="top" '.$w_cor.'>';
        if (f($row,'cancelado')=='S') {
          $l_html.=chr(13).'        <td rowspan="6"><font size="2"><b>ITEM '.f($row,'ordem_ata').'</b></font></td>';
        } else {
          $l_html.=chr(13).'        <td rowspan="5"><font size="2"><b>ITEM '.f($row,'ordem_ata').'</b></font></td>';
        }
        $l_html.=chr(13).'        <td>Código:<br><b>'.f($row,'codigo_interno').'</b></td>';
        if ($l_P4!=1){
          $l_html.=chr(13).'        <td colspan="3">Nome:<br><b>'.ExibeMaterial($w_dir_volta,$w_cliente,f($row,'nome'),f($row,'sq_material'),$TP,null).'</b></td>';
        } else {
          $l_html.=chr(13).'        <td colspan="3">Nome:<br><b>'.f($row,'nome').'</b></td>';
        }
        $l_html.=chr(13).'      </tr>';
        $l_html.=chr(13).'      <tr valign="top">';
        $l_html.=chr(13).'        <td>Fabricante:<br><b>'.nvl(f($row,'fabricante'),'---').'</b></td>';
        $l_html.=chr(13).'        <td>Marca/Modelo:<br><b>'.nvl(f($row,'marca_modelo'),'---').'</b></td>';
        $l_html.=chr(13).'        <td>Embalagem:<br><b>'.nvl(f($row,'embalagem'),'---').'</b></td>';
        $l_html.=chr(13).'        <td>Fator de embalagem:<br><b>'.nvl(f($row,'fator_embalagem'),'---').'</b></td>';
        $l_html.=chr(13).'      </tr>';
        $l_html.=chr(13).'      <tr valign="top">';
        $l_html.=chr(13).'        <td>CMM:<br><b>'.formatNumber(f($row,'cmm'),0).'</b></td>';
        $l_html.=chr(13).'        <td>$ Unitário:<br><b>'.formatNumber(f($row,'valor_unidade'),4).'</b></td>';
        $l_html.=chr(13).'      </tr>';
        if (strpos('EE,AT',$w_sg_tramite)!==false) {
          $l_html.=chr(13).'      <tr valign="top">';
          $l_html.=chr(13).'        <td>'.$w_txt_qtd.':<br><b>'.formatNumber(f($row,'quantidade_autorizada'),0).'</b></td>';
          $l_html.=chr(13).'        <td>'.$w_txt_valor.':<br><b>'.formatNumber(f($row,'quantidade_autorizada')*f($row,'valor_unidade'),4).'</b></td>';
          $w_total_preco += f($row,'quantidade_autorizada')*f($row,'valor_unidade');
        } else {
          $l_html.=chr(13).'      <tr valign="top">';
          $l_html.=chr(13).'        <td>'.$w_txt_qtd.':<br><b>'.formatNumber(f($row,'quantidade'),0).'</b></td>';
          $l_html.=chr(13).'        <td>'.$w_txt_valor.':<br><b>'.formatNumber(f($row,'quantidade')*f($row,'valor_unidade'),4).'</b></td>';
          $w_total_preco += f($row,'quantidade')*f($row,'valor_unidade');
        }
        $l_html.=chr(13).'      </tr>';
        if (f($row,'cancelado')=='S') {
          $l_html.=chr(13).'      <tr>';
          $l_html.=chr(13).'        <td valign="center"><font size="2"><b>INDISPONÍVEL</b></font></td>';
          $l_html.=chr(13).'        <td colspan=3>Motivo da indisponibilidade:<br><b>'.f($row,'motivo_cancelamento').'</b></td>';
          $l_html.=chr(13).'      </tr>';
        }
        $l_html.=chr(13).'      <tr><td colspan=4><table border=0 width="100%">';


        // Exibe pesquisas de preço
        $l_rs = db_getMatServ::getInstanceOf($dbms,$w_cliente,$w_usuario,f($row,'sq_material'),null,null,null,null,null,null,null,null,null,null,(($w_tramite_ativo=='S' && $w_sg_tramite!='EE') ? 'S' : null),null,null,$v_chave,null,null,'PESQSOLIC');
        $l_rs = SortArray($l_rs,'phpdt_fim','desc','valor_unidade','asc','nm_fornecedor','asc'); 
        $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DEMONSTRATIVO DE PESQUISAS DE PREÇO ('.count($l_rs).')<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
        if (count($l_rs)==0) {
          $l_html.=chr(13).'      <tr><td colspan="2">Nenhuma pesquisa encontrada</td></tr>';
        } else {
          $v_html = '';
          $v_html.=chr(13).'      <tr><td colspan="2" align="center">';
          $v_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';    
          $v_html.=chr(13).'          <tr align="center">';
          if (!($l_P1==4 || $l_P4==1)) $v_html.=chr(13).'            <td></td>';
          $v_html.=chr(13).'            <td bgColor="#f0f0f0" width="40%"><b>Fornecedor</b></td>';
          $v_html.=chr(13).'            <td bgColor="#f0f0f0" width="20%"><b>Fonte</b></td>';
          $v_html.=chr(13).'            <td bgColor="#f0f0f0" width="13%"><b>Cotação</b></td>';
          $v_html.=chr(13).'            <td bgColor="#f0f0f0" width="13%"><b>Validade</b></td>';
          $v_html.=chr(13).'            <td bgColor="#f0f0f0" width="13%"><b>Preço</b></td>';
          $v_html.=chr(13).'          </tr>';
          $w_cor=$conTrBgColor;
          $w_menor = 999999999;
          $w_maior = 0;
          $w_cont  = 0;
          $w_total = 0;
          foreach($l_rs as $row1) {
            $v_html.=chr(13).'      <tr valign="top">';
            if (!($l_P1==4 || $l_P4==1)) {
              $v_html.=chr(13).'        <td width="1%" nowrap>'.ExibeSinalPesquisa(false,f($row1,'phpdt_inicio'),f($row1,'phpdt_fim'),f($row1,'aviso')).'</td>';
              $v_html.=chr(13).'        <td>'.ExibePessoa($w_dir_volta,$w_cliente,f($row1,'fornecedor'),$TP,f($row1,'nm_fornecedor')).'</td>';
            } else {
              $v_html.=chr(13).'        <td>'.f($row1,'nm_fornecedor').'</td>';
            }
            $v_html.=chr(13).'        <td>'.f($row1,'nm_origem').'</td>';
            $v_html.=chr(13).'        <td align="center">'.FormataDataEdicao(f($row1,'inicio'),5).'</td>';
            $v_html.=chr(13).'        <td align="center">'.FormataDataEdicao(f($row1,'fim'),5).'</td>';
            $v_html.=chr(13).'        <td align="right" nowrap>'.formatNumber(f($row1,'valor_unidade'),4).'</td>';
            $v_html.=chr(13).'      </tr>';
            if (f($row1,'valor_unidade')<$w_menor) $w_menor = f($row1,'valor_unidade');
            if (f($row1,'valor_unidade')>$w_maior) $w_maior = f($row1,'valor_unidade');
            $w_total += f($row1,'valor_unidade');
            $w_cont++;
          } 
          
          $v_html.=chr(13).'         </table></td></tr>';
          $l_html.=chr(13).'      <tr><td colspan=2><table border=0 width="100%" cellpadding="0" cellspacing="0"><tr valign="top">';
          $l_html.=chr(13).'        <td align="center">$ Médio: <b>'.formatNumber(($w_total/$w_cont),4).'</b></td>';
          $l_html.=chr(13).'        <td align="center">$ Menor: <b>'.formatNumber($w_menor,4).'</b></td>';
          $l_html.=chr(13).'        <td align="center">$ Maior: <b>'.formatNumber($w_maior,4).'</b></td>';
          $l_html.=chr(13).'        </tr></table>';
          $l_html .=$v_html;
          if (!($l_P1==4 || $l_P4==1)) $l_html.=chr(13).'      <tr><td colspan=2><table border=0><tr><td colspan=3><b>Legenda:</b><tr><td>'.ExibeSinalPesquisa(true,null,null,null).'</td></tr></table>';
        }

        $l_html.=chr(13).'        </table>';



        $l_html.=chr(13).'      <tr><td><td colspan="4"><hr NOSHADE color=#000000 SIZE=1></td></tr>'; 
      }
      $l_html.=chr(13).'      <tr>';
      $l_html.=chr(13).'        <td align="right" colspan="2"><b>Total do pedido:&nbsp;&nbsp;</b></td>';
      $l_html.=chr(13).'        <td><b>'.formatNumber($w_total_preco,4).'</b></td>';
      $l_html.=chr(13).'      </tr>';
      $l_html.=chr(13).'    </table></td></tr>';
    } 

  }
  if ($l_O=='L' || $l_O=='V') {
    // Se for listagem dos dados
    // Arquivos vinculados
    $RS1 = db_getSolicAnexo::getInstanceOf($dbms,$v_chave,null,$w_cliente);
    $RS1 = SortArray($RS1,'nome','asc');
    if (count($RS1)>0) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ANEXOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';  
      $l_html.=chr(13).'      <tr><td colspan="2">';
      $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';
      $l_html.=chr(13).'          <tr align="center">';
      $l_html.=chr(13).'             <td bgColor="#f0f0f0" width="40%"><div><b>Título</b></td>';
      $l_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Descrição</b></td>';
      $l_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Tipo</b></td>';
      $l_html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>KB</b></td>';
      $l_html.=chr(13).'          </tr>';
      foreach($RS1 as $row) {
        $l_html.=chr(13).'      <tr valign="top">';
        if (!($l_P1==4 || $l_P4==1)) $l_html.=chr(13).'        <td>'.LinkArquivo('HL',$w_cliente,f($row,'chave_aux'),'_blank','Clique para exibir o arquivo em outra janela.',f($row,'nome'),null).'</td>';
        else                         $l_html.=chr(13).'        <td>'.f($row,'nome').'</td>';
        $l_html.=chr(13).'        <td>'.Nvl(f($row,'descricao'),'---').'</td>';
        $l_html.=chr(13).'        <td>'.f($row,'tipo').'</td>';
        $l_html.=chr(13).'        <td align="right">'.round(f($row,'tamanho')/1024,1).'&nbsp;</td>';
        $l_html.=chr(13).'      </tr>';
      } 
      $l_html.=chr(13).'         </table></td></tr>';
    }

    // Encaminhamentos
    include_once($w_dir_volta.'funcoes/exibeLog.php');
    $l_html .= exibeLog($v_chave,$l_O,$l_usuario,$w_tramite_ativo,(($l_P4==1) ? 'WORD' : 'HTML'));
    
    // Se for envio, executa verificações nos dados da solicitação
    if ($w_tramite_ativo=='S') $w_erro = ValidaARP($w_cliente,$v_chave,substr($w_sigla,0,4).'GERAL',null,null,null,Nvl($w_tramite,0));
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

    $l_html.=chr(13).'</table>';
  }
  $l_html .= chr(13).'</table>';
  return $l_html;
}
?>