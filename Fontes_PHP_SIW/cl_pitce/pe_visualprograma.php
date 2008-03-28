<?
// =========================================================================
// Rotina de visualização dos dados do programa
// -------------------------------------------------------------------------
function VisualPrograma($l_chave,$l_o,$l_usuario,$l_p1,$l_formato,$l_identificacao,$l_responsavel,$l_qualitativa,$l_orcamentaria,$l_indicador,$l_recurso,$l_interessado,$l_anexo,$l_meta,$l_ocorrencia,$l_consulta) {
  extract($GLOBALS);
  include_once($w_dir_volta.'classes/sp/db_getIndicador.php');
  include_once($w_dir_volta.'classes/sp/db_getSolicRecursos.php');
  include_once($w_dir_volta.'classes/sp/db_getSolicMeta.php');
  include_once($w_dir_volta.'classes/sp/db_getSolicIndicador.php');
  $l_html='';
  
  // Recupera os dados do programa
  $RS = db_getSolicData::getInstanceOf($dbms,$l_chave,'PEPRGERAL');

  $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
  if ($l_formato=='WORD') $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>PLANO ESTRATÉGICO: '.f($RS,'nm_plano').'</b></font></div></td></tr>';
  else                    $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>PLANO ESTRATÉGICO: '.ExibePlano('../',$w_cliente,f($RS,'sq_plano'),$TP,f($RS,'nm_plano')).'</b></font></div></td></tr>';
  $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>PROGRAMA: '.f($RS,'cd_programa').' - '.f($RS,'titulo').'</b></font></div></td></tr>';
  $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
  $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DESCRIÇÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
  $l_html.=chr(13).'      <tr><td colspan="2" align="justify">'.crlf2br(Nvl(f($RS,'descricao'),'---')).'</td></tr>';

  // Indicadores
  $RS = db_getSolicIndicador::getInstanceOf($dbms,$l_chave,null,null,null);
  $RS = SortArray($RS,'nm_tipo_indicador','asc','nome','asc');
  if (count($RS)>0) {
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>INDICADORES ('.count($RS).' )<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $l_html .= chr(13).'      <tr><td align="center" colspan="2">';
    $l_html .= chr(13).'          <table width=100%  border="1" bordercolor="#00000">';
    $l_html .= chr(13).'          <tr align="center">';
    $l_html .= chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><b>Indicador</b></td>';
    $l_html .= chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><b>U.M.</b></td>';
    $l_html .= chr(13).'            <td rowspan=2 bgColor="#f0f0f0"><b>Fonte</b></td>';
    $l_html .= chr(13).'            <td colspan=2 bgColor="#f0f0f0"><b>Base</b></td>';
    $l_html .= chr(13).'            <td colspan=2 bgColor="#f0f0f0"><b>Última aferição</b></td>';
    $l_html .= chr(13).'          </tr>';
    $l_html .= chr(13).'          <tr align="center">';
    $l_html .= chr(13).'            <td bgColor="#f0f0f0"><b>Valor</b></td>';
    $l_html .= chr(13).'            <td bgColor="#f0f0f0"><b>Referência</b></td>';
    $l_html .= chr(13).'            <td bgColor="#f0f0f0"><b>Valor</b></td>';
    $l_html .= chr(13).'            <td bgColor="#f0f0f0"><b>Referência</b></td>';
    $l_html .= chr(13).'          </tr>';
    $w_cor=$conTrBgColor;
    foreach ($RS as $row) {
      $l_html .= chr(13).'      <tr valign="top">';
      if($l_tipo!='WORD') $l_html .= chr(13).'        <td><A class="HL" HREF="javascript:this.status.value;" onClick="window.open(\''.$conRootSIW.'mod_pe/indicador.php?par=FramesAfericao&R='.$w_pagina.$par.'&O=L&w_troca=p_base&p_tipo_indicador='.f($row,'sq_tipo_indicador').'&p_indicador='.f($row,'chave').'&p_pesquisa=BASE&p_volta=&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\',\'Afericao\',\'width=730,height=500,top=30,left=30,status=no,resizable=yes,scrollbars=yes,toolbar=no\');" title="Exibe informaçoes sobre o indicador.">'.f($row,'nome').'</a></td></td>';
      else       $l_html .= chr(13).'        <td>'.f($row,'nome').'</td></td>';
      $l_html .= chr(13).'        <td nowrap align="center">'.f($row,'sg_unidade_medida').'</td>';
      $l_html .= chr(13).'        <td>'.f($row,'fonte_comprovacao').'</td>';
      if (nvl(f($row,'valor'),'')!='') {
        $l_html .= chr(13).'        <td align="right">'.formatNumber(f($row,'valor'),4).'</td>';
        $p_array = retornaNomePeriodo(f($row,'referencia_inicio'), f($row,'referencia_fim'));
        $l_html .= chr(13).'        <td align="center">';
        if ($p_array['TIPO']=='DIA') {
          $l_html .= chr(13).'        '.date(d.'/'.m.'/'.y,$p_array['VALOR']);
        } elseif ($p_array['TIPO']=='MES') {
          $l_html .= chr(13).'        '.$p_array['VALOR'];
        } elseif ($p_array['TIPO']=='ANO') {
          $l_html .= chr(13).'        '.$p_array['VALOR'];
        } else {
          $l_html .= chr(13).'        '.nvl(date(d.'/'.m.'/'.y,f($row,'referencia_inicio')),'---').' a '.nvl(date(d.'/'.m.'/'.y,f($row,'referencia_fim')),'---');
        }
      } else {
        $l_html .= chr(13).'        <td align="center">&nbsp;</td>';
        $l_html .= chr(13).'        <td align="center">&nbsp;</td>';
      }
      if (nvl(f($row,'base_valor'),'')!='') {
        $l_html .= chr(13).'        <td align="right">'.formatNumber(f($row,'base_valor'),4).'</td>';
        $p_array = retornaNomePeriodo(f($row,'base_referencia_inicio'), f($row,'base_referencia_fim'));
        $l_html .= chr(13).'        <td align="center">';
        if ($p_array['TIPO']=='DIA') {
          $l_html .= chr(13).'        '.date(d.'/'.m.'/'.y,$p_array['VALOR']);
        } elseif ($p_array['TIPO']=='MES') {
          $l_html .= chr(13).'        '.$p_array['VALOR'];
        } elseif ($p_array['TIPO']=='ANO') {
          $l_html .= chr(13).'        '.$p_array['VALOR'];
        } else {
          $l_html .= chr(13).'        '.nvl(date(d.'/'.m.'/'.y,f($row,'base_referencia_inicio')),'---').' a '.nvl(date(d.'/'.m.'/'.y,f($row,'base_referencia_fim')),'---');
        }
      } else {
        $l_html .= chr(13).'        <td align="center">&nbsp;</td>';
        $l_html .= chr(13).'        <td align="center">&nbsp;</td>';
      }
      $l_html .= chr(13).'      </tr>';
    } 
    $l_html .= chr(13).'         </table></td></tr>';
    $l_html .= chr(13).'      <tr><td colspan=6><table border=0>';
    $l_html .= chr(13).'        <tr><td align="right">U.M.<td>Unidade de medida do indicador';
    $l_html .= chr(13).'        </table>';
  }

  // Metas
  $RS = db_getSolicMeta::getInstanceOf($dbms,$w_cliente,$l_usuario,$l_chave,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
  $RS = SortArray($RS,'ordem','asc','titulo','asc');
  if (count($RS)>0) {
    $l_html .= chr(13).'      <tr><td colspan="2"><br><font size="2"><b>METAS ('.count($RS).' )<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $l_html .= chr(13).'      <tr><td align="center" colspan="2">';
    $l_html .= chr(13).'          <table width=100%  border="1" bordercolor="#00000">';     
    $l_html .= chr(13).'          <tr align="center" bgColor="#f0f0f0">';
    $l_html .= chr(13).'            <td rowspan=2><b>Objetivo</b></td>';
    $l_html .= chr(13).'            <td rowspan=2><b>Indicador</b></td>';
    $l_html .= chr(13).'            <td rowspan=2 width="1%" nowrap><b>U.M.</b></td>';
    $l_html .= chr(13).'            <td colspan=2><b>Base</b></td>';
    $l_html .= chr(13).'            <td colspan=2><b>Meta</b></td>';
    $l_html .= chr(13).'          </tr>';
    $l_html .= chr(13).'          <tr align="center" bgColor="#f0f0f0">';
    $l_html .= chr(13).'            <td><b>Data</b></td>';
    $l_html .= chr(13).'            <td><b>Valor</b></td>';
    $l_html .= chr(13).'            <td><b>Data</b></td>';
    $l_html .= chr(13).'            <td><b>Valor</b></td>';
    $l_html .= chr(13).'          </tr>';
    $w_cor=$conTrBgColor;
    foreach ($RS as $row) {
      $l_html .= chr(13).'      <tr valign="top">';
      $l_html .= chr(13).'        <td>'.f($row,'titulo').'</td>';
      if ($l_formato=='WORD') {
        $l_html .= chr(13).'        <td>'.f($row,'nm_indicador').'</td>';
      } else {
        $l_html .= chr(13).'        <td>'.ExibeIndicador($w_dir_volta,$w_cliente,f($row,'nm_indicador'),'&w_troca=p_base&p_tipo_indicador='.f($row,'sq_tipo_indicador').'&p_indicador='.f($row,'sq_eoindicador').'&p_pesquisa=BASE&p_volta=',$TP).'</td>';
      }
      $l_html .= chr(13).'        <td align="center">'.f($row,'sg_unidade_medida').'</td>';        
      $l_html .= chr(13).'        <td align="center">'.date(d.'/'.m.'/'.y,f($row,'inicio')).'</td>';
      $l_html .= chr(13).'        <td align="right">'.formatNumber(f($row,'valor_inicial'),4).'</td>';
      $l_html .= chr(13).'        <td align="center">'.date(d.'/'.m.'/'.y,f($row,'fim')).'</td>';
      $l_html .= chr(13).'        <td align="right">'.formatNumber(f($row,'quantidade'),4).'</td>';
      $l_html .= chr(13).'      </tr>';
    } 
    $l_html .= chr(13).'         </table></td></tr>';
    $l_html .= chr(13).'<tr><td colspan=3><table border=0>';
    $l_html .= chr(13).'  <tr><td align="right">U.M.<td>Unidade de medida do indicador';
    $l_html .= chr(13).'  </table>';
  }

  // Envolvidos na execução do programa
  $RS1 = db_getSolicInter::getInstanceOf($dbms,$l_chave,null,'LISTA');
  $RS1 = SortArray($RS1,'or_tipo_interessado','asc','nome','asc');
  if (count($RS1)>0) {
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ENVOLVIDOS NA EXECUÇÃO ('.count($RS1).' )<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $l_html.=chr(13).'   <tr><td colspan="2"><div align="center">';
    $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
    $l_html.=chr(13).'       <tr><td bgColor="#f0f0f0" width="10%" nowrap><div align="center"><b>Tipo de envolvimento</b></div></td>';
    $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="center"><b>Pessoa</b></div></td>';
    $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="center"><b>Envia e-mail</b></div></td>';
    $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="center"><b>Tipo de visão</b></div></td>';
    $l_html.=chr(13).'       </tr>';
    foreach($RS1 as $row) {
      $l_html.=chr(13).'       <tr><td nowrap>'.f($row,'nm_tipo_interessado').'</td>';
      if ($l_formato=='WORD') $l_html.=chr(13).'           <td>'.f($row,'nome').' ('.f($row,'lotacao').')</td>';
      else                    $l_html.=chr(13).'           <td>'.ExibePessoa('../',$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'nome').' ('.f($row,'lotacao').')').'</td>';
      $l_html.=chr(13).'           <td align="center">'.str_replace('N','Não',str_replace('S','Sim',f($row,'envia_email'))).'</td>';
      $l_html.=chr(13).'           <td>'.RetornaTipoVisao(f($row,'tipo_visao')).'</td>';
      $l_html.=chr(13).'      </tr>';
    }
    $l_html.=chr(13).'         </table></div></td></tr>';
  } 

  // Arquivos vinculados ao programa
  $RS1 = db_getSolicAnexo::getInstanceOf($dbms,$l_chave,null,$w_cliente);
  $RS1 = SortArray($RS1,'nome','asc');
  if (count($RS1)>0 && $l_anexo=='S') {
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ANEXOS ('.count($RS1).' )<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $l_html.=chr(13).'   <tr><td colspan="2"><div align="center">';
    $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
    $l_html.=chr(13).'       <tr><td bgColor="#f0f0f0"><div align="center"><b>Título</b></div></td>';
    $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="center"><b>Descrição</b></div></td>';
    $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="center"><b>Tipo</b></div></td>';
    $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="center"><b>KB</b></div></td>';
    $l_html.=chr(13).'       </tr>';
    foreach($RS1 as $row) {
      if ($l_formato=='WORD') $l_html.=chr(13).'       <tr><td>'.f($row,'nome').'</td>';
      else                    $l_html.=chr(13).'       <tr><td>'.LinkArquivo('HL',$w_cliente,f($row,'chave_aux'),'_blank','Clique para exibir o arquivo em outra janela.',f($row,'nome'),null).'</td>';
      $l_html.=chr(13).'           <td>'.Nvl(f($row,'descricao'),'-').'</td>';
      $l_html.=chr(13).'           <td>'.f($row,'tipo').'</td>';
      $l_html.=chr(13).'         <td><div align="right">'.round(f($row,'tamanho')/1024).'&nbsp;</td>';
      $l_html.=chr(13).'      </tr>';
    }
    $l_html.=chr(13).'         </table></div></td></tr>';
  } 

  // Projetos vinculados ao programa
  $RS = db_getLinkData::getInstanceOf($dbms,$w_cliente,'PJCAD');
  $RS1 = db_getSolicList::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,f($RS,'sigla'),4,
         null,null,null,null,null,null,null,null,null,null,
         null,null,null,null,null,null,null,null,null,null,null,null,$l_chave,null,null,null);
  $RS1 = SortArray($RS1,'titulo','asc','prioridade','asc');

  if (count($RS1) > 0) {
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>'.strtoupper(f($RS,'nome')).' ('.count($RS1).' )<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $l_html.=chr(13).'   <tr><td colspan="2" align="center">';
    $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
    $l_html.=chr(13).'       <tr align="center">';
    $l_html.=chr(13).'         <td bgColor="#f0f0f0" rowspan=2><b>Nº</b></td>';
    $l_html.=chr(13).'         <td bgColor="#f0f0f0" rowspan=2><b>Responsável</b></td>';
    $l_html.=chr(13).'         <td bgColor="#f0f0f0" rowspan=2><b>Título</b></td>';
    $l_html.=chr(13).'         <td bgColor="#f0f0f0" colspan=2><b>Execução</b></td>';
    //$l_html.=chr(13).'         <td bgColor="#f0f0f0" rowspan=2><b>Valor</b></td>';
    if ($l_formato=='WORD') $l_html.=chr(13).'         <td bgColor="#f0f0f0" rowspan=2 colspan=2><b>IDE</b></td>';
    else                    $l_html.=chr(13).'         <td bgColor="#f0f0f0" rowspan=2 colspan=2><b>'.VisualIndicador($w_dir_volta,$w_cliente,'IDE',$TP,'IDE hoje').'</b></td>';
    if ($l_formato=='WORD') $l_html.=chr(13).'         <td bgColor="#f0f0f0" rowspan=2><b>IGE</b></td>';
    else                    $l_html.=chr(13).'         <td bgColor="#f0f0f0" rowspan=2><b>'.VisualIndicador($w_dir_volta,$w_cliente,'IGE',$TP,'IGE').'</b></td>';
    $l_html.=chr(13).'       </tr>';
    $l_html.=chr(13).'       <tr align="center">';
    $l_html.=chr(13).'         <td bgColor="#f0f0f0"><b>De</b></td>';
    $l_html.=chr(13).'         <td bgColor="#f0f0f0"><b>De</b></td>';
    $l_html.=chr(13).'       </tr>';
    $w_cor=$conTrBgColor;
    foreach ($RS1 as $row) {
      $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;
      $l_html .=chr(13).'      <tr bgcolor="'.$w_cor.'" valign="top">';
      $l_html .=chr(13).'        <td width="1%" nowrap>';
      $l_html .=chr(13).ExibeImagemSolic(f($row,'sigla'),f($row,'inicio'),f($row,'fim'),f($row,'inicio_real'),f($row,'fim_real'),f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null);
      if ($l_formato=='WORD') $l_html .=chr(13).'        '.nvl(f($row,'codigo_interno'),f($row,'sq_siw_solicitacao')).'&nbsp;'.exibeImagemRestricao(f($row,'restricao'),'P');
      else                    $l_html .=chr(13).'        <A class="HL" HREF="'.$conRootSIW.'cl_pitce/projeto.php?par=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.f($row,'sigla').MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.nvl(f($row,'codigo_interno'),f($row,'sq_siw_solicitacao')).'&nbsp;</a>'.exibeImagemRestricao(f($row,'restricao'),'P');
      if ($l_formato=='WORD') $l_html .=chr(13).'        <td>'.f($row,'nm_solic').'</td>';
      else                    $l_html .=chr(13).'        <td>'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'solicitante'),$TP,f($row,'nm_solic')).'</td>';
      $l_html .=chr(13).'        <td>'.Nvl(f($row,'titulo'),'-').'</td>';
      $l_html .=chr(13).'        <td align="center">&nbsp;'.FormataDataEdicao(f($row,'inicio'),5).'</td>';
      $l_html .=chr(13).'        <td align="center">&nbsp;'.FormataDataEdicao(f($row,'fim'),5).'</td>';
      /*
      if (f($row,'sg_tramite')=='AT') {
        $l_html .=chr(13).'        <td align="right">'.number_format(f($row,'custo_real'),2,',','.').'&nbsp;</td>';
        $w_parcial += f($row,'custo_real');
      } else {
        $l_html .=chr(13).'        <td align="right">'.number_format(f($row,'valor'),2,',','.').'&nbsp;</td>';
        $w_parcial += f($row,'valor');
      } 
      */
      $l_html .=chr(13).'        <td align="center">'.ExibeSmile('IDE',f($row,'ide')).'</td>';
      $l_html .=chr(13).'        <td align="right">'.formatNumber(f($row,'ide')).'%</td>';
      $l_html .=chr(13).'        <td align="right">'.formatNumber(f($row,'ige')).'%</td>';
    } 
    /*
    if ($w_parcial>0) {
      $l_html .=chr(13).'        <tr bgcolor="'.$conTrBgColor.'">';
      $l_html .=chr(13).'          <td colspan=5 align="right"><b>Total&nbsp;</td>';
      $l_html .=chr(13).'          <td align="right"><b>'.number_format($w_parcial,2,',','.').'&nbsp;</td>';
      $l_html .=chr(13).'          <td colspan=3>&nbsp;</td>';
      $l_html .=chr(13).'        </tr>';
    }
    */
    $l_html.=chr(13).'         </table></td></tr>';
    $l_html.=chr(13).'      <tr><td colspan="2"><font size="1">Observação: a listagem exibe apenas os projetos nos quais você tenha alguma permissão.</font></td></tr>';
  } 

  // Encaminhamentos
  if ($l_ocorrencia=='S') {
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>OCORRÊNCIAS E ANOTAÇÕES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $RS = db_getSolicLog::getInstanceOf($dbms,$l_chave,null,'LISTA');
    $RS = SortArray($RS,'phpdt_data','desc','despacho','asc');
    if (count($RS)>0 && $l_ocorrencia=='S') {
      $l_html.=chr(13).'   <tr><td colspan="2"><div align="center">';
      $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
      $l_html.=chr(13).'       <tr><td bgColor="#f0f0f0"><div align="center"><b>Data</b></div></td>';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="center"><b>Ocorrência/Anotação</b></div></td>';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="center"><b>Responsável</b></div></td>';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="center"><b>Fase/Destinatário</b></div></td>';
      $l_html.=chr(13).'       </tr>';
      $i=0;
      foreach($RS as $row) {
        if ($i==0) {
          $l_html.=chr(13).'       <tr><td colspan="4">Fase Atual: <b>'.f($row,'fase').'</b></td></tr>';
          $i=1;
        }
        $l_html.=chr(13).'    <tr valign="top">';
        $l_html.=chr(13).'      <td nowrap>'.FormataDataEdicao(f($row,'phpdt_data'),3).'</td>';
        if (Nvl(f($row,'caminho'),'')>'' && $l_formato!='WORD') $l_html .= chr(13).'        <td>'.CRLF2BR(Nvl(f($row,'despacho'),'---').'<br>'.LinkArquivo('HL',$w_cliente,f($row,'sq_siw_arquivo'),'_blank','Clique para exibir o anexo em outra janela.','Anexo - '.f($row,'tipo').' - '.round(f($row,'tamanho')/1024,1).' KB',null)).'</td>';
        else                              $l_html .= chr(13).'        <td>'.CRLF2BR(Nvl(f($row,'despacho'),'---')).'</td>';
        if ($l_formato=='WORD') $l_html.=chr(13).'      <td nowrap>'.f($row,'responsavel').'</td>';
        else                    $l_html.=chr(13).'      <td nowrap>'.ExibePessoa('../',$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'responsavel')).'</td>';
        if ((Nvl(f($row,'sq_programa_log'),'')>'') && (Nvl(f($row,'destinatario'),'')>'')) {
          if ($l_formato=='WORD') $l_html.=chr(13).'        <td nowrap>'.f($row,'destinatario').'</td>';
          else                    $l_html.=chr(13).'        <td nowrap>'.ExibePessoa('../',$w_cliente,f($row,'sq_pessoa_destinatario'),$TP,f($row,'destinatario')).'</td>';
        } elseif ((Nvl(f($row,'sq_programa_log'),'')>'')  && (Nvl(f($row,'destinatario'),'')=='')) {
          $l_html.=chr(13).'        <td nowrap>Anotação</td>';
        } else {
          if(strpos(f($row,'despacho'),'***')!==false) {
            $l_html.=chr(13).'        <td nowrap>---</td>';
          } else {
            $l_html.=chr(13).'        <td nowrap>'.Nvl(f($row,'tramite'),'---').'</td>';
          }
        }
        $l_html.=chr(13).'      </tr>';
      } 
      $l_html.=chr(13).'         </table></div></td></tr>';
    } else {
      $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">Não foi encontrado nenhum encaminhamento</div></td></tr>';
    } 
  } 
  $l_html .= chr(13).'</table>';  
  return $l_html;
} ?>
