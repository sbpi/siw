<?php
// =========================================================================
// Rotina de visualização dos dados do documento
// -------------------------------------------------------------------------
function VisualDocumento($l_chave,$l_o,$l_usuario,$l_p1,$l_formato,$l_identificacao,$l_assunto_princ,$l_orcamentaria,
      $l_indicador,$l_recurso,$l_interessado,$l_anexo,$l_meta,$l_ocorrencia,$l_consulta) {
  extract($GLOBALS);

  //Recupera as informações do sub-menu
  $RS = db_getLinkSubMenu::getInstanceOf($dbms, $w_cliente, f($RS_Menu,'sigla'));
  foreach ($RS as $row) {
    if     (strpos(f($row,'sigla'),'ANEXO')!==false)    $l_nome_menu['ANEXO'] = upper(f($row,'nome'));
    elseif (strpos(f($row,'sigla'),'GERAL')!==false)    $l_nome_menu['GERAL'] = upper(f($row,'nome'));
    elseif (strpos(f($row,'sigla'),'INTERES')!==false)  $l_nome_menu['INTERES'] = upper(f($row,'nome'));
    elseif (strpos(f($row,'sigla'),'ASS')!==false)      $l_nome_menu['ASSUNTO'] = upper(f($row,'nome'));
    else $l_nome_menu[f($row,'sigla')] = upper(f($row,'nome'));
  }

  $l_html='';
  // Recupera os dados do documento
  $RS = db_getSolicData::getInstanceOf($dbms,$l_chave,'PADCAD');
  
  if (nvl(f($RS,'sq_solic_pai'),'')!='') {
    $RS_Pai = db_getSolicData::getInstanceOf($dbms,f($RS,'sq_solic_pai'),'PADCAD');
    if (f($RS,'tipo_juntada')=='A') $w_tipo_juntada = 'ANEXO AO PROCESSO '.f($RS_Pai,'protocolo');
    else                            $w_tipo_juntada = 'APENSO AO PROCESSO '.f($RS_Pai,'protocolo');
  }

  $l_html.=chr(13).'    <table width="100%" border="0" cellspacing="3">';
  if ($O!='T' && $O!='V') {
    if ($l_formato!='WORD') $l_html.=chr(13).'      <tr><td align="right" colspan="2"><br><b><A class="HL" HREF="'.$w_dir.'documento.php?par=Visual&O=T&w_chave='.f($RS,'sq_siw_solicitacao').'&w_tipo=volta&P1=&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe as informações do documento.">Exibir todas as informações</a></td></tr>';
  }
  $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
  if (f($RS,'processo')=='S') $w_tipo = 'PROCESSO'; else $w_tipo='DOCUMENTO';
  $l_html.=chr(13).'      <tr><td colspan="'.((nvl(f($RS,'sq_solic_pai'),'')!='' || f($RS,'ativo')=='N') ? 1 : 2).'"  bgcolor="#f0f0f0" align=justify><font size="2"><b>'.$w_tipo.': '.f($RS,'protocolo').'</b></font></td>';
  if (nvl(f($RS,'sq_solic_pai'),'')!='') {
    $l_html.=chr(13).'          <td bgcolor="#f0f0f0" align=right><font size="2"><b>'.$w_tipo_juntada.'</b></font></td>';
  } elseif (nvl(f($RS,'sq_emprestimo'),'')!='') {
    $l_html.=chr(13).'          <td bgcolor="#f0f0f0" align=right><font size="2"><b>EMPRESTADO ATÉ '.formataDataEdicao(f($RS,'devolucao_prevista')).' (PREVISÃO)</b></font></td>';
  } elseif (nvl(f($RS,'sq_eliminacao'),'')!='' && nvl(f($RS,'dt_eliminacao'),'')=='') {
    $l_html.=chr(13).'          <td bgcolor="#f0f0f0" align=right><font size="2"><b>EM PROCESSO DE ELIMINAÇÃO</b></font></td>';
  } elseif (f($RS,'sg_tramite')=='EL') {
    $l_html.=chr(13).'          <td bgcolor="#f0f0f0" align=right><font size="2"><b>ELIMINADO</b></font></td>';
  } elseif (f($RS,'sg_tramite')=='AS') {
    $l_html.=chr(13).'          <td bgcolor="#f0f0f0" align=right><font size="2"><b>'.((nvl(f($RS,'data_setorial'),'')=='') ? 'EM TRÂNSITO PARA ARQUIVO SETORIAL' : 'ARQUIVADO SETORIAL').'</b></font></td>';
  } elseif (f($RS,'sg_tramite')=='AT') {
    $l_html.=chr(13).'          <td bgcolor="#f0f0f0" align=right><font size="2"><b>'.((nvl(f($RS,'data_central'),'')=='') ? 'EM TRÂNSITO PARA ARQUIVO CENTRAL' : 'ARQUIVADO CENTRAL').'</b></font></td>';
  } elseif (f($RS,'sg_tramite')=='CA') {
    $l_html.=chr(13).'          <td bgcolor="#f0f0f0" align=right><font size="2"><b>CANCELADO</b></font></td>';
  } 
  $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
  // Identificação do documento
  if ($l_identificacao=='S') {
    if (f($RS,'interno')=='S') {
      if ($l_formato=='WORD') {
        $l_html.=chr(13).'   <tr><td width="30%"><b>Procedência:</b></td>';
        $l_html.=chr(13).'       <td>'.f($RS,'nm_unid_origem').'</td></tr>';
      } else {
        $l_html.=chr(13).'   <tr><td width="30%"><b>Procedência:</b></td>';
        $l_html.=chr(13).'       <td>'.ExibeUnidade('../',$w_cliente,f($RS,'nm_unid_origem'),f($RS,'sq_unidade'),$TP).'</td></tr>';
      } 
    } else {
      $l_html.=chr(13).'   <tr><td><b>Procedência:</b></td>';
      $l_html.=chr(13).'       <td>'.f($RS,'nm_pessoa_origem').'</td></tr>';
      $l_html.=chr(13).'   <tr><td><b>Interessado principal:</b></td>';
      $l_html.=chr(13).'       <td>'.f($RS,'nm_pessoa_interes').'</td></tr>';
      $l_html.=chr(13).'   <tr><td><b>Cidade:</b></td>';
      $l_html.=chr(13).'       <td>'.f($RS,'nm_cidade').'</td></tr>';
    }
    $l_html.=chr(13).'   <tr><td><b>Espécie documental:</b></td>';
    $l_html.=chr(13).'       <td>'.f($RS,'nm_especie').'</td></tr>';
    $l_html.=chr(13).'   <tr><td><b>Número:</b></td>';
    $l_html.=chr(13).'       <td>'.f($RS,'numero_original').'</td></tr>';
    $l_html.=chr(13).'   <tr><td><b>Data do documento:</b></td>';
    $l_html.=chr(13).'       <td>'.formataDataEdicao(f($RS,'inicio')).'</td></tr>';
    if (nvl(f($RS,'copias'),'')!='') {
      $l_html.=chr(13).'   <tr><td><b>Nº de cópias:</b></td>';
      $l_html.=chr(13).'       <td>'.formatNumber(f($RS,'copias'),0).'</td></tr>';
    }
    if (nvl(f($RS,'volumes'),'')!='') {
      $l_html.=chr(13).'   <tr><td><b>Nº de volumes:</b></td>';
      $l_html.=chr(13).'       <td>'.formatNumber(f($RS,'volumes'),0).'</td></tr>';
    }
    $l_html.=chr(13).'   <tr><td><b>Natureza:</b></td>';
    $l_html.=chr(13).'       <td>'.f($RS,'nm_natureza').'</td></tr>';
    $l_html.=chr(13).'   <tr><td><b>Data de criação/recebimento:</b></td>';
    $l_html.=chr(13).'       <td>'.formataDataEdicao(f($RS,'data_recebimento')).'</td></tr>';
    $l_html.=chr(13).'   <tr><td><b>Data limite para conclusão:</b></td>';
    $l_html.=chr(13).'       <td>'.nvl(formataDataEdicao(f($RS,'fim')),'---').'</td></tr>';
    $l_html.=chr(13).'   <tr><td valign="top"><b>Classificação:</b></td>';
    $l_html.=chr(13).'       <td align="justify">'.f($RS,'cd_assunto').'-'.f($RS,'ds_assunto').'</td></tr>';
    $l_html.=chr(13).'   <tr><td valign="top"><b>Detalhamento do assunto:</b></td>';
    $l_html.=chr(13).'       <td align="justify">'.crlf2br(Nvl(f($RS,'descricao'),'---')).'</td></tr>';
  } 

  if ($O=='T') {
    // Assunto principal
    if ($l_assunto_princ=='S') {
      $l_html.=chr(13).'   <tr><td colspan="2"><br><font size="2"><b>ASSUNTO PRINCIPAL<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html.=chr(13).'   <tr><td valign="top"><b>Código:</b></td>';
      $l_html.=chr(13).'       <td align="justify">'.crlf2br(Nvl(f($RS,'cd_assunto'),'---')).'</td></tr>';
      $l_html.=chr(13).'   <tr><td valign="top"><b>Descrição:</b></td>';
      $l_html.=chr(13).'       <td align="justify">'.f($RS,'ds_assunto');
      if (nvl(f($RS,'ds_assunto_pai'),'')!='') { 
        $l_html.=chr(13).'<br><span style="text-transform:lowercase">';
        if (nvl(f($RS,'ds_assunto_bis'),'')!='') $l_html.=chr(13).f($RS,'ds_assunto_bis').' &rarr; ';
        if (nvl(f($RS,'ds_assunto_avo'),'')!='') $l_html.=chr(13).f($RS,'ds_assunto_avo').' &rarr; ';
        if (nvl(f($RS,'ds_assunto_pai'),'')!='') $l_html.=chr(13).f($RS,'ds_assunto_pai');
      }
      $l_html.=chr(13).'       </span></td></tr>';
      $l_html.=chr(13).'   <tr><td valign="top"><b>Detalhamento:</b></td>';
      $l_html.=chr(13).'       <td align="justify">'.crlf2br(Nvl(f($RS,'dst_assunto'),'---')).'</td></tr>';
      $l_html.=chr(13).'   <tr><td valign="top"><b>Observação:</b></td>';
      $l_html.=chr(13).'       <td align="justify">'.crlf2br(Nvl(f($RS,'ob_assunto'),'---')).'</td></tr>';
      $l_html.=chr(13).'   <tr><td valign="top"><b>Prazos de guarda:</b></td>';
      $l_html.=chr(13).'       <td align="justify"><table border=1>';
      $l_html.=chr(13).'         <tr valign="top"><td align="center"><b>Fase corrente<td align="center"><b>Fase intermediária<td align="center"><b>Destinação final';
      $l_html.=chr(13).'         <tr valign="top">';
      $l_html.=chr(13).'           '.((strpos(upper(f($RS,'guarda_corrente')),'ANOS')===false) ? '<td>' : '<td align="center">').f($RS,'guarda_corrente').'</td>';
      $l_html.=chr(13).'           '.((strpos(upper(f($RS,'guarda_intermed')),'ANOS')===false) ? '<td>' : '<td align="center">').f($RS,'guarda_intermed').'</td>';
      $l_html.=chr(13).'           '.((strpos(upper(f($RS,'guarda_final')),'ANOS')===false)    ? '<td>' : '<td align="center">').f($RS,'guarda_final').'</td>';
      $l_html.=chr(13).'         </table>';
    } 

    // Assuntos complementares
    $RS1 = db_getDocumentoAssunto::getInstanceOf($dbms,$l_chave,null,'N',null);
    $RS1 = SortArray($RS1,'principal','desc','nome','asc');
    if (count($RS1)>0 && $l_interessado=='S') {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>'.$l_nome_menu['ASSUNTO'].'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html.=chr(13).'   <tr><td colspan="2" align="center">';
      $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
      $l_html.=chr(13).'       <tr valign="top">';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0" width="1%" nowrap align="center"><b>Código</b></td>';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0" align="center"><b>Descrição</b></td>';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0" align="center"><b>Detalhamento</b></td>';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0" align="center"><b>Observação</b></td>';
      $l_html.=chr(13).'       </tr>';
      foreach($RS1 as $row) {
        $l_html.=chr(13).'       <tr valign="top">';
        $l_html.=chr(13).'           <td nowrap>'.f($row,'codigo').'</td>';
        $l_html.=chr(13).'           <td>'.nvl(f($row,'descricao'),'---');
        if (nvl(f($RS,'ds_assunto_pai'),'')!='') { 
          $l_html.=chr(13).'<br>';
          if (nvl(f($RS,'ds_assunto_bis'),'')!='') $l_html.=chr(13).lower(f($RS,'ds_assunto_bis')).' &rarr; ';
          if (nvl(f($RS,'ds_assunto_avo'),'')!='') $l_html.=chr(13).lower(f($RS,'ds_assunto_avo')).' &rarr; ';
          if (nvl(f($RS,'ds_assunto_pai'),'')!='') $l_html.=chr(13).lower(f($RS,'ds_assunto_pai'));
        }
        $l_html.=chr(13).'           </td>';
        $l_html.=chr(13).'           <td>'.nvl(lower(f($row,'detalhamento')),'---').'</td>';
        $l_html.=chr(13).'           <td>'.nvl(lower(f($row,'observacao')),'---').'</td>';
        $l_html.=chr(13).'      </tr>';
      }
      $l_html.=chr(13).'         </table></td></tr>';
    } 
  }
  
  if (f($RS,'processo')=='S') {
    //Dados da autuação
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS DA AUTUAÇÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $l_html.=chr(13).'   <tr><td><b>Data da autuação:</b></td>';
    $l_html.=chr(13).'       <td>'.FormataDataEdicao(f($RS,'data_autuacao')).'</td></tr>';
    $l_html.=chr(13).'   <tr><td><b>Unidade autuadora:</b></td>';
    $l_html.=chr(13).'       <td>'.f($RS,'nm_unidade_resp').'</td></tr>';
  } 

  if (f($RS,'ativo')=='N') {
    //Dados do arquivamento/eliminação
    if (nvl(f($RS,'data_setorial'),'')!='') {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS DO ARQUIVAMENTO SETORIAL<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html.=chr(13).'   <tr><td><b>Data do arquivamento:</b></td>';
      $l_html.=chr(13).'       <td>'.FormataDataEdicao(f($RS,'data_setorial')).'</td></tr>';
      $l_html.=chr(13).'   <tr><td><b>Acondicionamento Arq. Setorial:</b></td>';
      $l_html.=chr(13).'       <td>'.f($RS,'observacao_setorial').'</td></tr>';
      if (nvl(f($RS,'sq_caixa'),'')!='') {
        if ($l_formato=='WORD') {
          $l_html.=chr(13).'   <tr><td width="30%"><b>Unidade:</b></td>';
          $l_html.=chr(13).'       <td>'.f($RS,'nm_unid_caixa').'</td></tr>';
        } else {
          $l_html.=chr(13).'   <tr><td width="30%"><b>Unidade:</b></td>';
          $l_html.=chr(13).'       <td>'.ExibeUnidade('../',$w_cliente,f($RS,'nm_unid_caixa'),f($RS,'sq_unid_caixa'),$TP).'</td></tr>';
        } 
        $l_html.=chr(13).'   <tr><td><b>Caixa:</b></td>';
        $l_html.=chr(13).'       <td><A onclick="window.open (\''.montaURL_JS($w_dir,'relatorio.php?par=ConteudoCaixa'.'&R='.$w_pagina.'IMPRIMIR'.'&O=L&w_chave='.f($RS,'sq_caixa').'&w_formato=HTML&orientacao=PORTRAIT&&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG).'\',\'Imprimir\',\'width=700,height=450, status=1,toolbar=yes,scrollbars=yes,resizable=yes\');" class="HL"  HREF="javascript:this.status.value;" title="Imprime a lista de protocolos arquivados na caixa.">'.f($RS,'nr_caixa').'/'.f($RS,'sg_unid_caixa').'</a></td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Pasta:</b></td>';
        $l_html.=chr(13).'       <td>'.f($RS,'pasta').'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Assunto:</b></td>';
        $l_html.=chr(13).'       <td>'.f($RS,'as_caixa').'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Descrição:</b></td>';
        $l_html.=chr(13).'       <td>'.crlf2br(f($RS,'ds_caixa')).'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Data Limite:</b></td>';
        $l_html.=chr(13).'       <td>'.formataDataEdicao(f($RS,'dt_caixa')).'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Intermediário:</b></td>';
        $l_html.=chr(13).'       <td>'.f($RS,'in_caixa').'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Destinação final:</b></td>';
        $l_html.=chr(13).'       <td>'.f($RS,'df_caixa').'</td></tr>';
      }
    }
    if (f($RS,'sg_tramite')=='AT') {
      if (nvl(f($RS,'data_central'),'')!='') {
        $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS DO ARQUIVAMENTO CENTRAL<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Data do arquivamento:</b></td>';
        $l_html.=chr(13).'       <td>'.FormataDataEdicao(f($RS,'arquivo_data')).'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Localização:</b></td>';
        $l_html.=chr(13).'       <td>'.f($RS,'nm_arquivo_local').'</td></tr>';
      }
    }
    if (f($RS,'sg_tramite')=='EL' || nvl(f($RS,'sq_eliminacao'),'')!='') {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS DA ELIMINAÇÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html.=chr(13).'   <tr><td><b>Solicitação:</b></td>';
      $l_html.=chr(13).'       <td>'.f($RS,'cd_eliminacao').'</td></tr>';
      $l_html.=chr(13).'   <tr><td><b>Data da eliminação:</b></td>';
      $l_html.=chr(13).'       <td>'.nvl(FormataDataEdicao(f($RS,'dt_eliminacao')),'Em andamento').'</td></tr>';
    }
  } 

  // Dados da juntada
  if (nvl(f($RS,'sq_solic_pai'),'')!='') {
    $l_html.=chr(13).'   <tr><td colspan="2"><br><font size="2"><b>DADOS DA '.((f($RS,'tipo_juntada')=='A') ? 'ANEXAÇÃO' : 'APENSAÇÃO').'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $l_html.=chr(13).'   <tr><td valign="top"><b>Data:</b></td>';
    $l_html.=chr(13).'       <td align="justify">'.f($RS,'phpdt_juntada').'</td></tr>';
  }
  
  $RS_Juntado = db_getSolicList::getInstanceOf($dbms,f($RS,'sq_menu'),$w_usuario,'PAD',5,
      $p_ini_i,$p_ini_f,$p_fim_i,$p_fim_f,$p_atraso,$p_solicitante,
      $p_unidade,$p_prioridade,$p_ativo,$p_proponente,
      $p_chave, $p_assunto, $p_pais, $p_regiao, $p_uf, $p_cidade, $p_usu_resp,
      $p_uorg_resp, $p_numero_doc, $p_prazo, $p_fase, $p_sqcc, f($RS,'sq_siw_solicitacao'), $p_atividade, 
      null, null, $p_empenho, $p_numero_orig);

  if (count($RS_Juntado)>0) {
    $l_html.=chr(13).'   <tr><td colspan="2"><br><font size="2"><b>DOCUMENTOS JUNTADOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $l_html.=chr(13).'   <tr><td colspan="2" align="center">';
    $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
    $l_html.=chr(13).'       <tr valign="top" align="center">';
    $l_html.=chr(13).'         <td rowspan=2 bgColor="#f0f0f0" width="1%" nowrap align="center"><b>Situação</b></td>';
    $l_html.=chr(13).'         <td rowspan=2 bgColor="#f0f0f0" align="center"><b>Protocolo</b></td>';
    $l_html.=chr(13).'         <td colspan=4 bgColor="#f0f0f0" align="center"><b>Documento original</b></td>';
    $l_html.=chr(13).'       </tr>';
    $l_html.=chr(13).'       <tr valign="top" align="center">';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Espécie</td>';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Nº</td>';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Data</td>';
    $l_html.=chr(13).'          <td bgColor="#f0f0f0"><b>Procedência</td>';
    $l_html.=chr(13).'        </tr>';
    foreach ($RS_Juntado as $row) {
      $l_html.=chr(13).'      <tr valign="top">';
      $l_html.=chr(13).'        <td>'.f($row,'nm_tipo_juntada').'</td>';
      $l_html.=chr(13).'        <td align="center">'.f($row,'protocolo').'</td>';
      $l_html.=chr(13).'        <td>'.f($row,'nm_especie').'</td>';
      $l_html.=chr(13).'        <td>'.f($row,'numero_original').'</td>';
      $l_html.=chr(13).'        <td align="center">'.date(d.'/'.m.'/'.y,f($row,'inicio')).'</td>';
      $l_html.=chr(13).'        <td>'.nvl(f($row,'nm_origem_doc'),'&nbsp;').'</td>';
      $l_html.=chr(13).'      </tr>';
    } 
    $l_html.=chr(13).'      </center>';
    $l_html.=chr(13).'    </table>';
    $l_html.=chr(13).'  </td>';
    $l_html.=chr(13).'</tr>';
  }

  if ($O=='T') {
    // Interessados na execução do documento
    $RS1 = db_getDocumentoInter::getInstanceOf($dbms,$l_chave,null,'N',null);
    $RS1 = SortArray($RS1,'principal','desc','nome','asc');
    if (count($RS1)>0 && $l_interessado=='S') {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>'.$l_nome_menu['INTERES'].'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html.=chr(13).'   <tr><td colspan="2" align="center">';
      $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
      $l_html.=chr(13).'       <tr valign="top">';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0" width="1%" nowrap align="center"><b>Principal</b></td>';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0" align="center"><b>Pessoa</b></td>';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0" align="center"><b>CPF/CNPJ</b></td>';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0" align="center"><b>RG/Inscrição estadual</b></td>';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0" align="center"><b>Passaporte</b></td>';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0" align="center"><b>Sexo</b></td>';
      $l_html.=chr(13).'       </tr>';
      foreach($RS1 as $row) {
        $l_html.=chr(13).'       <tr valign="top">';
        $l_html.=chr(13).'           <td nowrap>'.f($row,'nm_principal').'</td>';
        if ($l_formato=='WORD') $l_html.=chr(13).'           <td>'.f($row,'nome').'</td>';
        else                    $l_html.=chr(13).'           <td>'.ExibePessoa('../',$w_cliente,f($row,'chave_aux'),$TP,f($row,'nome')).'</td>';
        $l_html.=chr(13).'           <td nowrap>'.nvl(f($row,'identificador_principal'),'---').'</td>';
        $l_html.=chr(13).'           <td>'.nvl(f($row,'identificador_secundario'),'---').'</td>';
        $l_html.=chr(13).'           <td>'.nvl(f($row,'nr_passaporte'),'---').'</td>';
        $l_html.=chr(13).'           <td>'.nvl(f($row,'nm_sexo'),'---').'</td>';
        $l_html.=chr(13).'      </tr>';
      }
      $l_html.=chr(13).'         </table></td></tr>';
    } 

    // Arquivos vinculados ao registro
    $RS1 = db_getSolicAnexo::getInstanceOf($dbms,$l_chave,null,$w_cliente);
    $RS1 = SortArray($RS1,'nome','asc');
    if (count($RS1)>0 && $l_anexo=='S') {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>'.$l_nome_menu['ANEXO'].'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html.=chr(13).'   <tr><td colspan="2" align="center">';
      $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
      $l_html.=chr(13).'       <tr><td bgColor="#f0f0f0" align="center"><b>Título</b></td>';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0" align="center"><b>Descrição</b></td>';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0" align="center"><b>Tipo</b></td>';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0" align="center"><b>KB</b></td>';
      $l_html.=chr(13).'       </tr>';
      foreach($RS1 as $row) {
        if ($l_formato=='WORD') $l_html.=chr(13).'       <tr><td>'.LinkArquivo('HL',$w_cliente,f($row,'chave_aux'),'_blank','Clique para exibir o arquivo em outra janela.',f($row,'nome'),null).'</td>';
        else                    $l_html.=chr(13).'       <tr><td>'.LinkArquivo('HL',$w_cliente,f($row,'chave_aux'),'_blank','Clique para exibir o arquivo em outra janela.',f($row,'nome'),null).'</td>';
        $l_html.=chr(13).'           <td>'.Nvl(f($row,'descricao'),'-').'</td>';
        $l_html.=chr(13).'           <td>'.f($row,'tipo').'</td>';
        $l_html.=chr(13).'         <td><div align="right">'.round(f($row,'tamanho')/1024).'&nbsp;</td>';
        $l_html.=chr(13).'      </tr>';
      }
      $l_html.=chr(13).'         </table></td></tr>';
    } 

    include_once($w_dir_volta.'classes/sp/db_getSolicPA.php');
    $RS1 = db_getLinkData::getInstanceOf($dbms,$w_cliente,'PAEMP');
    $RS1 = db_GetSolicPA::getInstanceOf($dbms,f($RS1,'sq_menu'),$w_usuario,'EMPREST',4,
        null,null,null,null,null,null,null,null,null,null,null, null, null, null, null, null, null,
        null, null, null, null, null, null, null,null, null, null, $l_chave);
    // Empréstimos
    $RS1 = SortArray($RS1,'devolucao','desc','codigo_interno','asc');
    if (count($RS1)>0) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>EMPRÉSTIMOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html.=chr(13).'   <tr><td colspan="2" align="center">';
      $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
      $l_html.=chr(13).'       <tr valign="top" align="center">';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0" width="1%" nowrap rowspan=2><b>Código</b></td>';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0" rowspan=2><b>Justificativa</b></td>';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0" colspan=2><b>Solicitante</b></td>';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0" colspan=2><b>Devolução</b></td>';
      $l_html.=chr(13).'       </tr>';
      $l_html.=chr(13).'       <tr valign="top" align="center">';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0"><b>Pessoa</b></td>';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0"><b>Setor</b></td>';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0"><b>Prevista</b></td>';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0"><b>Real</b></td>';
      $l_html.=chr(13).'       </tr>';
      
      foreach($RS1 as $row) {
        $l_html.=chr(13).'       <tr valign="top">';
        $l_html.=chr(13).'           <td nowrap>'.ExibeImagemSolic(f($row,'sigla'),f($row,'inicio'),f($row,'fim'),null,null,f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null).' '.f($row,'codigo_interno').'&nbsp;</td>';
        $l_html.=chr(13).'           <td>'.htmlspecialchars(f($row,'justificativa')).'</td>';
        if ($l_formato=='WORD') $l_html.=chr(13).'           <td>'.f($row,'nm_solic').'</td>';
        else                    $l_html.=chr(13).'           <td>'.ExibePessoa('../',$w_cliente,f($row,'solicitante'),$TP,f($row,'nm_solic')).'</td>';
        if ($l_formato=='WORD') $l_html.=chr(13).'           <td>'.f($row,'sg_unidade_resp').'</td>';
        else                    $l_html.=chr(13).'           <td>'.ExibeUnidade('../',$w_cliente,f($row,'sg_unidade_resp'),f($row,'sq_unidade'),$TP).'</td>';
        $l_html.=chr(13).'           <td align="center">&nbsp;'.formataDataEdicao(f($row,'fim')).'&nbsp;</td>';
        $l_html.=chr(13).'           <td align="center">&nbsp;'.formataDataEdicao(f($row,'devolucao')).'&nbsp;</td>';
        $l_html.=chr(13).'      </tr>';
      }
      $l_html.=chr(13).'         </table></td></tr>';
    } 

        
    
  }

  // Encaminhamentos
  if ($l_ocorrencia=='S') {
    // Versões
    $RS_Log = db_getSolicLog::getInstanceOf($dbms,$l_chave,2,'LISTA');
    $RS_Log = SortArray($RS_Log,'phpdt_data','desc','sq_siw_solic_log','desc');
    if (count($RS_Log)>0) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>VERSÕES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html.=chr(13).'      <tr><td colspan="2" align="center">';
      $l_html.=chr(13).'        <table width=100%  border="1" bordercolor="#00000">';    
      $l_html.=chr(13).'          <tr align="center">';
      $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Data</b></td>';
      $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Versão</b></td>';
      $l_html.=chr(13).'            <td bgColor="#f0f0f0"><b>Responsável</b></td>';
      $l_html.=chr(13).'          </tr>';
      $w_cor=$conTrBgColor;
      $i = 0;
      foreach($RS_Log as $row) {
        $l_html.=chr(13).'      <tr valign="top">';
        $l_html.=chr(13).'        <td width="1%" nowrap>'.FormataDataEdicao(f($row,'phpdt_data'),3).'</td>';
        if (Nvl(f($row,'caminho'),'')>'' && $l_formato=='HTML') {
          $l_html.=chr(13).'        <td>'.LinkArquivo('HL',$w_cliente,f($row,'sq_siw_arquivo'),'_blank','Clique para exibir o anexo em outra janela.',f($row,'origem'),null).'</td>';
        } else {
          $l_html.=chr(13).'        <td>'.f($row,'origem').'</td>';
        } 
        if ($l_formato=='HTML') $l_html.=chr(13).'        <td width="1%" nowrap>'.ExibePessoa($w_dir_volta,$w_cliente,f($row,'sq_pessoa_resp'),$TP,f($row,'nm_pessoa_resp')).'</td>';
        else                    $l_html.=chr(13).'        <td width="1%" nowrap>'.f($row,'nm_pessoa_resp').'</td>';
        $l_html.=chr(13).'      </tr>';
      } 
      $l_html.=chr(13).'         </table></td></tr>';
    }

    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>OCORRÊNCIAS E ANOTAÇÕES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $RS = db_getSolicLog::getInstanceOf($dbms,$l_chave,0,'LISTA');
    $RS = SortArray($RS,'phpdt_data','desc');
    if (count($RS)>0 && $l_ocorrencia=='S') {
      $i=0;
      foreach($RS as $row) {
        if ($i==0) {
          $l_html.=chr(13).'     <tr><td colspan="2">Fase Atual: <b>'.f($row,'fase').'</b></td></tr>';
          $l_html.=chr(13).'     <tr><td colspan="2" align="center">';
          $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
          $l_html.=chr(13).'       <tr>';
          $l_html.=chr(13).'         <td colspan=3 bgColor="#f0f0f0" align="center"><b>Ocorrência</b></td>';
          $l_html.=chr(13).'         <td colspan=2 bgColor="#f0f0f0" align="center"><b>Recebimento</b></td>';
          $l_html.=chr(13).'         <td rowspan=2 bgColor="#f0f0f0" align="center"><b>Procedência</b></td>';
          $l_html.=chr(13).'         <td rowspan=2 bgColor="#f0f0f0" align="center"><b>Destino</b></td>';
          $l_html.=chr(13).'         <td rowspan=2 bgColor="#f0f0f0" align="center"><b>Despacho / Descrição</b></td>';
          $l_html.=chr(13).'       </tr>';
          $l_html.=chr(13).'       <tr>';
          $l_html.=chr(13).'         <td bgColor="#f0f0f0" align="center"><b>Data</b></td>';
          $l_html.=chr(13).'         <td bgColor="#f0f0f0" align="center"><b>Tipo</b></td>';
          $l_html.=chr(13).'         <td bgColor="#f0f0f0" align="center"><b>Executor</b></td>';
          $l_html.=chr(13).'         <td bgColor="#f0f0f0" align="center"><b>Data</b></td>';
          $l_html.=chr(13).'         <td bgColor="#f0f0f0" align="center"><b>Recebedor</b></td>';
          $l_html.=chr(13).'       </tr>';
          $i=1;
        }
        $l_html.=chr(13).'    <tr valign="top">';
        $l_html.=chr(13).'      <td nowrap>'.FormataDataEdicao(nvl(f($row,'phpdt_envio'),f($row,'phpdt_data')),6).'</td>';

        $l_html.=chr(13).'        <td>'.f($row,'origem').'</td>';
        if ($l_formato!='WORD') $l_html.=chr(13).'        <td>'.ExibePessoa('../',$w_cliente,f($row,'sq_pessoa_resp'),$TP,f($row,'nm_pessoa_resp')).'</td>';
        else                    $l_html.=chr(13).'        <td>'.f($row,'nm_pessoa_resp').'</td>';

        if (nvl(f($row,'recebedor'),'')!='') {
          $l_html.=chr(13).'      <td nowrap>'.FormataDataEdicao(f($row,'phpdt_receb'),6).'</td>';
          if ($l_formato!='WORD') $l_html.=chr(13).'        <td>'.ExibePessoa('../',$w_cliente,f($row,'recebedor'),$TP,f($row,'nm_recebedor'));
          else                    $l_html.=chr(13).'        <td>'.f($row,'nm_recebedor');
        } elseif (f($row,'origem')=='TRÂMITE ORIGINAL' || f($row,'origem')=='TRAMITAÇÃO') {
          $l_html.=chr(13).'      <td colspan=2 align="center">PENDENTE</td>';
        } else {
          $l_html.=chr(13).'      <td colspan=2>&nbsp;</td>';
        }
        
        if ($l_formato!='WORD') $l_html.=chr(13).'        <td>'.ExibeUnidade('../',$w_cliente,f($row,'nm_origem'),f($row,'sq_origem'),$TP).'</td>';
        else                    $l_html.=chr(13).'        <td>'.nvl(f($row,'nm_origem'),'---').'</td>';
        
        if (nvl(f($row,'sq_destinatario'),'')!='') {
          if (f($row,'tipo_destinatario')=='PESSOA') {
            if ($l_formato!='WORD') $l_html.=chr(13).'        <td>'.ExibePessoa('../',$w_cliente,f($row,'sq_destinatario'),$TP,f($row,'nm_destinatario'));
            else                    $l_html.=chr(13).'        <td>'.f($row,'nm_destinatario');
            if (f($row,'interno')=='N') {
              $l_html.='<br>Pessoa: '.nvl(f($row,'pessoa_externa'),'---');
              $l_html.='<br>Unidade: '.nvl(f($row,'unidade_externa'),'---');
            }
          } else {
            if ($l_formato!='WORD') $l_html.=chr(13).'        <td>'.ExibeUnidade('../',$w_cliente,f($row,'nm_destinatario'),f($row,'sq_destinatario'),$TP);
            else                    $l_html.=chr(13).'        <td>'.f($row,'nm_destinatario');
          }
        } else {
          $l_html.=chr(13).'        <td>&nbsp;</td>';
        }
        $l_html.=chr(13).'      <td>'.CRLF2BR(Nvl(f($row,'despacho'),'---')).'</td>';
        $l_html.=chr(13).'      </tr>';
      } 
      $l_html.=chr(13).'         </table></td></tr>';
    } else {
      $l_html.=chr(13).'      <tr><td colspan="2" align="center">Não foi encontrado nenhum encaminhamento</td></tr>';
    } 
  } 

  if ($O=='T' && $l_consulta=='S') {
    //Dados da Consulta
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS DA CONSULTA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $l_html.=chr(13).'   <tr><td><b>Consulta realizada por:</b></td>';
    $l_html.=chr(13).'       <td>'.$_SESSION['NOME_RESUMIDO'].'</td></tr>';
    $l_html.=chr(13).'   <tr><td><b>Data da consulta:</b></td>';
    $l_html.=chr(13).'       <td>'.FormataDataEdicao(time(),6).'</td></tr>';
  } 
  $l_html.=chr(13).'    </table>';
  return $l_html;
} ?>
