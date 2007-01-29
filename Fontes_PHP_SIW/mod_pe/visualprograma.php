<?
// =========================================================================
// Rotina de visualização dos dados do programa
// -------------------------------------------------------------------------
function VisualPrograma($l_chave,$O,$l_usuario,$P1,$P4,$l_identificacao,$l_responsavel,$l_qualitativa,$l_orcamentaria,$l_indicador,$l_restricao,$l_interessado,$l_anexo,$l_acao,$l_ocorrencia,$l_consulta) {
  extract($GLOBALS);
  $l_html='';
  // Recupera os dados do programa
  $RS = db_getSolicData::getInstanceOf($dbms,$l_chave,'PEPRGERAL');
  //Se for para exibir só a ficha resumo do programa.
  if ($P1==1 || $P1==2 || $P1==3) {
    if (!($P4==1)) $l_html.=chr(13).'      <tr><td align="right" colspan="2"><br><b><A class="HL" HREF="'.$w_dir.'programa.php?par=Visual&O=L&w_chave='.f($RS,'sq_siw_solicitacao').'&w_tipo=volta&P1=&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe as informações do programa.">Exibir todas as informações</a></td></tr>';
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>PLANO ESTRATÉGICO: '.f($RS,'nm_plano').'</b></font></div></td></tr>';
    $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>OBJETIVO: '.f($RS,'nm_objetivo').'</b></font></div></td></tr>';
    $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>PROGRAMA: '.f($RS,'cd_programa').' - '.f($RS,'titulo').'</b></font></div></td></tr>';
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    // Identificação do programa
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>IDENTIFICAÇÃO DO PROGRAMA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    if ($P4==1) {
      $l_html.=chr(13).'   <tr><td width="30%"><b>Unidade executora:</b></td>';
      $l_html.=chr(13).'       <td>'.f($RS,'nm_unidade_adm').'</td></tr>';
    } else {
      $l_html.=chr(13).'   <tr><td width="30%"><b>Unidade executora:</b></td>';
      $l_html.=chr(13).'       <td>'.ExibeUnidade('../',$w_cliente,f($RS,'nm_unidade_adm'),f($RS,'sq_unidade_adm'),$TP).'</td></tr>';
    } 
    if ($P4==1) {
      $l_html.=chr(13).'   <tr><td><b>Área monitoramento:</b></td>';
      $l_html.=chr(13).'       <td>'.f($RS,'nm_unidade_resp').'</td></tr>';
      $l_html.=chr(13).'   <tr><td><b>Responsável monitoramento:</b></td>';
      $l_html.=chr(13).'       <td>'.f($RS,'nome_resumido').'</td></tr>';
    } else {
      $l_html.=chr(13).'   <tr><td><b>Área monitoramento:</b></td>';
      $l_html.=chr(13).'       <td>'.ExibeUnidade('../',$w_cliente,f($RS,'nm_unidade_resp'),f($RS,'sq_unidade_resp'),$TP).'</td></tr>';
      $l_html.=chr(13).'   <tr><td><b>Responsável monitoramento:</b></td>';
      $l_html.=chr(13).'       <td>'.ExibePessoa('../',$w_cliente,f($RS,'solicitante'),$TP,f($RS,'nm_solic')).'</td></tr>';
    } 
    $l_html.=chr(13).'   <tr><td><b>Endereço Internet:</b></td>';
    $l_html.=chr(13).'       <td>'.Nvl(f($RS,'ln_programa'),'-').'</td></tr>';
    $l_html.=chr(13).'   <tr><td><b>Valor previsto:</b></td>';
    $l_html.=chr(13).'       <td>R$ '.number_format(f($RS,'valor'),2,',','.').'</td></tr>';

    // Listagem das restrições do programa
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>RESTRIÇÕES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $RS1 = db_getRestricao_IS::getInstanceOf($dbms,'ISPRRESTR',$l_chave,null);
    $RS1 = SortArray($RS1,'phpdt_inclusao','desc');
    if (count($RS1)>0) {
      $l_cont=1;
      foreach($RS1 as $row) {
        $l_html.=chr(13).'   <tr><td valigin="top" bgcolor="#f0f0f0"><b>'.$l_cont.') Tipo:</b></td>';
        $l_html.=chr(13).'       <td bgcolor="#f0f0f0">'.f($row,'nm_tp_restricao').'</b></td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Descrição:</b></td>';
        $l_html.=chr(13).'       <td>'.f($row,'descricao').'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Providência:</b></td>';
        $l_html.=chr(13).'       <td>'.Nvl(f($row,'providencia'),'-').'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Data de Inclusão:</b></td>';
        $l_html.=chr(13).'       <td>'.FormataDataEdicao(f($row,'phpdt_inclusao'),3).'</td></tr>';
        $l_cont+=1;
      } 
    } else {
      $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">Nenhuma restrição cadastrada</div></td></tr>';
    } 
/*
    // Ações do programa
    // Recupera todos os registros para a listagem
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>AÇÕES DO PROGRAMA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $RS1 = db_getAcaoPPA_IS::getInstanceOf($dbms,$w_cliente,$w_ano,f($RS,'cd_programa'),null,null,null,null,null,null,null,null);
    $RS1 = SortArray($RS1,'chave','asc');
    if (count($RS1)>0) {
      // Se não foram selecionados registros, exibe mensagem  
      $l_html.=chr(13).'   <tr><td colspan="2"><div align="center">';
      $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
      $l_html.=chr(13).'       <tr><td bgColor="#cccccc" colspan="4"><div align="center"><b>Ações</b></div></td></tr>';
      $l_html.=chr(13).'       <tr><td bgColor="#f0f0f0" width="5%" ><div align="center"><b>Cód.</b></div></td>';
      $l_html.=chr(13).'           <td bgColor="#f0f0f0" width="46%"><div align="center"><b>Descrição</b></div></td>';
      $l_html.=chr(13).'           <td bgColor="#f0f0f0" width="30%"><div align="center"><b>Unidade</b></div></td>';
      $l_html.=chr(13).'           <td bgColor="#f0f0f0" width="14%"><div align="center"><b>Fase</b></div></td>';
      $l_html.=chr(13).'       </tr>';
      foreach($RS1 as $row) {
        if (Nvl(f($row,'sq_siw_solicitacao'),'')>'' && $P4!=1) {
          $l_html.=chr(13).'       <tr valign="top"><td align="center"><A class="HL" HREF="'.$w_dir.'acao.php?par='.'Visual&R='.$l_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'cd_acao').'</a></td>';
        } else {
          $l_html.=chr(13).'       <tr valign="top"><td align="center">'.f($row,'cd_acao').'</td>';
        } 
        $l_html.=chr(13).'           <td>'.f($row,'descricao_acao').'</td>';
        $l_html.=chr(13).'           <td>'.f($row,'cd_unidade').' - '.f($row,'ds_unidade').'</td>';
        $l_html.=chr(13).'           <td>'.Nvl(f($row,'nm_tramite'),'Não Cadastrada').'</td>';
        $l_html.=chr(13).'       </tr>';
      }
      $l_html.=chr(13).'     </table></div></td></tr>';
    } else {
      $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">Não existe nenhuma ação para este programa</div></td></tr>';
    } 
*/
    // Encaminhamentos
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>OCORRÊNCIAS E ANOTAÇÕES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $RS = db_getSolicLog::getInstanceOf($dbms,$l_chave,null,'LISTA');
    $RS = SortArray($RS,'phpdt_data','desc');
    if (count($RS)>0) {
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
          $l_html.=chr(13).'       <tr><td colspan="4">Fase atual: <b>'.f($row,'fase').'</b></td></tr>';
          $i+=1;
        }
        $l_html.=chr(13).'    <tr><td nowrap>'.FormataDataEdicao(f($row,'phpdt_data'),3).'</td>';
        $l_html.=chr(13).'        <td>'.CRLF2BR(Nvl(f($row,'despacho'),'---')).'</td>';
        $l_html.=chr(13).'        <td nowrap>'.ExibePessoa('../',$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'responsavel')).'</td>';
        if ((Nvl(f($row,'sq_projeto_log'),'')>'') && (Nvl(f($row,'destinatario'),'')>''))         $l_html.=chr(13).'        <td nowrap>'.ExibePessoa('../',$w_cliente,f($row,'sq_pessoa_destinatario'),$TP,f($row,'destinatario')).'</td>';
        elseif ((Nvl(f($row,'sq_projeto_log'),'')>'')  && (Nvl(f($row,'destinatario'),'')==''))   $l_html.=chr(13).'        <td nowrap>Anotação</td>';
        else $l_html.=chr(13).'        <td nowrap>'.Nvl(f($row,'tramite'),'---').'</td>';
        $l_html.=chr(13).'      </tr>';
      } 
      $l_html.=chr(13).'         </table></div></td></tr>';
    } else {
      $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">Não foi encontrado nenhum encaminhamento</div></td></tr>';
    } 
    $l_html.=chr(13).'</table>';
  } else {
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>PLANO ESTRATÉGICO: '.f($RS,'nm_plano').'</b></font></div></td></tr>';
    $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>OBJETIVO: '.f($RS,'nm_objetivo').'</b></font></div></td></tr>';
    $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0"><div align=justify><font size="2"><b>PROGRAMA: '.f($RS,'cd_programa').' - '.f($RS,'titulo').'</b></font></div></td></tr>';
    $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
    // Identificação do programa
    if (strtoupper($l_identificacao)==strtoupper('sim')) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>IDENTIFICAÇÃO DO PROGRAMA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      if ($P4==1) {
        $l_html.=chr(13).'   <tr><td width="30%"><b>Unidade executora:</b></td>';
        $l_html.=chr(13).'       <td>'.f($RS,'nm_unidade_adm').'</td></tr>';
      } else {
        $l_html.=chr(13).'   <tr><td width="30%"><b>Unidade executora:</b></td>';
        $l_html.=chr(13).'       <td>'.ExibeUnidade('../',$w_cliente,f($RS,'nm_unidade_adm'),f($RS,'sq_unidade_adm'),$TP).'</td></tr>';
      } 
      if ($P4==1) {
        $l_html.=chr(13).'   <tr><td><b>Área monitoramento:</b></td>';
        $l_html.=chr(13).'       <td>'.f($RS,'nm_unidade_resp').'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Responsável monitoramento:</b></td>';
        $l_html.=chr(13).'       <td>'.f($RS,'nm_solic').'</td></tr>';
      } else {
        $l_html.=chr(13).'   <tr><td><b>Área monitoramento:</b></td>';
        $l_html.=chr(13).'       <td>'.ExibeUnidade('../',$w_cliente,f($RS,'nm_unidade_resp'),f($RS,'sq_unidade_resp'),$TP).'</td></tr>';
        $l_html.=chr(13).'   <tr><td><b>Responsável monitoramento:</b></td>';
        $l_html.=chr(13).'       <td>'.ExibePessoa('../',$w_cliente,f($RS,'solicitante'),$TP,f($RS,'nm_solic')).'</td></tr>';
      } 
      $l_html.=chr(13).'   <tr><td><b>Endereço Internet:</b></td>';
      $l_html.=chr(13).'       <td>'.Nvl(f($RS,'ln_programa'),'-').'</td></tr>';
      $l_html.=chr(13).'   <tr><td><b>Valor previsto:</b></td>';
      $l_html.=chr(13).'       <td>R$ '.number_format(f($RS,'valor'),2,',','.').'</td></tr>';
      $l_html.=chr(13).'   <tr><td><b>Natureza:</b></td>';
      $l_html.=chr(13).'       <td>'.f($RS,'nm_natureza').'</td></tr>';
      $l_html.=chr(13).'   <tr><td><b>Horizonte:</b></td>';
      $l_html.=chr(13).'       <td>'.f($RS,'nm_horizonte').'</td></tr>';
      $l_html.=chr(13).'   <tr><td><b>Parcerias:</b></td>';
      $l_html.=chr(13).'       <td>'.CRLF2BR(Nvl(f($RS,'palavra_chave'),'-')).'</td></tr>';
      $l_html.=chr(13).'   <tr><td><b>Fase Atual do Programa:</b></td>';
      $l_html.=chr(13).'       <td>'.Nvl(f($RS,'nm_tramite'),'-').'</td></tr>';
    } 
    // Programação Qualitativa
    if (strtoupper($l_qualitativa)==strtoupper('sim')) {
      $l_html.=chr(13).'   <tr><td colspan="2"><br><font size="2"><b>PROGRAMAÇÃO QUALITATIVA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html.=chr(13).'   <tr><td valign="top"><b>Objetivo do programa:</b></td>';
      $l_html.=chr(13).'       <td><div align="justify">'.crlf2br(Nvl(f($RS,'descricao'),'-')).'</div></td></tr>';
      $l_html.=chr(13).'   <tr><td valign="top"><b>Justificativa:</b></td>';
      $l_html.=chr(13).'       <td><div align="justify">'.crlf2br(Nvl(f($RS,'justificativa'),'-')).'</div></td></tr>';
      $l_html.=chr(13).'   <tr><td valign="top"><b>Público alvo:</b></td>';
      $l_html.=chr(13).'       <td><div align="justify">'.crlf2br(Nvl(f($RS,'publico_alvo'),'-')).'</div></td></tr>';
      $l_html.=chr(13).'   <tr><td valign="top"><b>Estratégia de implementação:</b></td>';
      $l_html.=chr(13).'       <td><div align="justify">'.crlf2br(Nvl(f($RS,'estrategia'),'-')).'</div></td></tr>';
      $l_html.=chr(13).'   <tr><td valign="top"><b>Observações:</b></td>';
      $l_html.=chr(13).'       <td><div align="justify">'.crlf2br(Nvl(f($RS,'observacao'),'-')).'</div></td></tr>';
    } 
    // Listagem das restrições do programa
    if (strtoupper($l_restricao)==strtoupper('sim')) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>RESTRIÇÕES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $RS1 = db_getRestricao_IS::getInstanceOf($dbms,'ISPRRESTR',$l_chave,null);
      $RS1 = SortArray($RS1,'phpdt_inclusao','desc');
      if (count($RS1)>0) {
        $l_cont=1;
        foreach($RS1 as $row) {
          $l_html.=chr(13).'   <tr><td valigin="top" bgcolor="#f0f0f0"><b>'.$l_cont.') Tipo:</b></td>';
          $l_html.=chr(13).'       <td bgcolor="#f0f0f0"><b>'.f($row,'nm_tp_restricao').'</b></td></tr>';
          $l_html.=chr(13).'   <tr><td><b>Descrição:</b></td>';
          $l_html.=chr(13).'       <td>'.f($row,'descricao').'</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>Providência:</b></td>';
          $l_html.=chr(13).'       <td>'.Nvl(f($row,'providencia'),'-').'</td></tr>';
          $l_html.=chr(13).'   <tr><td><b>Data de Inclusão:</b></td>';
          $l_html.=chr(13).'       <td>'.FormataDataEdicao(f($row,'phpdt_inclusao'),3).'</td></tr>';
          $l_cont=$l_cont+1;
        } 
      } else {
        $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">Nenhuma restrição cadastrada</div></td></tr>';
      } 
    } 
    // Envolvidos na execução do programa
    if (strtoupper($l_interessado)==strtoupper('sim')) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ENVOLVIDOS NA EXECUÇÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $RS1 = db_getSolicInter::getInstanceOf($dbms,$l_chave,null,'LISTA');
      $RS1 = SortArray($RS1,'nome_resumido','asc');
      if (count($RS1)>0) {
        $l_html.=chr(13).'   <tr><td colspan="2"><div align="center">';
        $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
        $l_html.=chr(13).'       <tr><td bgColor="#f0f0f0"><div align="center"><b>Pessoa</b></div></td>';
        $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="center"><b>Tipo de envolvimento</b></div></td>';
        $l_html.=chr(13).'       </tr>';
        foreach($RS1 as $row) {
          $l_html.=chr(13).'       <tr><td>'.ExibePessoa('../',$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'nome').' ('.f($row,'lotacao').')').'</td>';
          $l_html.=chr(13).'           <td>'.f($row,'nm_tipo_interessado').'</td>';
          $l_html.=chr(13).'      </tr>';
        }
        $l_html.=chr(13).'         </table></div></td></tr>';
      } else {
        $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">Nenhuma arquivo cadastrado</div></td></tr>';
      } 
    } 
    // Arquivos vinculados ao programa
    if (strtoupper($l_anexo)==strtoupper('sim')) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ANEXOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $RS1 = db_getSolicAnexo::getInstanceOf($dbms,$l_chave,null,$w_cliente);
      $RS1 = SortArray($RS1,'nome','asc');
      if (count($RS1)>0) {
        $l_html.=chr(13).'   <tr><td colspan="2"><div align="center">';
        $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
        $l_html.=chr(13).'       <tr><td bgColor="#f0f0f0"><div align="center"><b>Título</b></div></td>';
        $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="center"><b>Descrição</b></div></td>';
        $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="center"><b>Tipo</b></div></td>';
        $l_html.=chr(13).'         <td bgColor="#f0f0f0"><div align="center"><b>KB</b></div></td>';
        $l_html.=chr(13).'       </tr>';
        foreach($RS1 as $row) {
          $l_html.=chr(13).'       <tr><td>'.LinkArquivo('HL',$w_cliente,f($row,'chave_aux'),'_blank','Clique para exibir o arquivo em outra janela.',f($row,'nome'),null).'</td>';
          $l_html.=chr(13).'           <td>'.Nvl(f($row,'descricao'),'-').'</td>';
          $l_html.=chr(13).'           <td>'.f($row,'tipo').'</td>';
          $l_html.=chr(13).'         <td><div align="right">'.round(f($row,'tamanho')/1024).'&nbsp;</td>';
          $l_html.=chr(13).'      </tr>';
        }
        $l_html.=chr(13).'         </table></div></td></tr>';
      } else {
        $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">Nenhuma arquivo cadastrado</div></td></tr>';
      } 
    } 
/*
    // Ações do programa
    if (strtoupper($l_acao)==strtoupper('sim')) {
      // Recupera todos os registros para a listagem
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>AÇÕES DO PROGRAMA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $RS1 = db_getAcaoPPA_IS::getInstanceOf($dbms,$w_cliente,$w_ano,f($RS,'cd_programa'),null,null,null,null,null,null,null,null);
      $RS1 = SortArray($RS1,'chave','asc');
      if (count($RS1)>0) {
        // Se não foram selecionados registros, exibe mensagem  
        $l_html.=chr(13).'   <tr><td colspan="2"><div align="center">';
        $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
        $l_html.=chr(13).'       <tr><td bgColor="#cccccc" colspan="4"><div align="center"><b>Ações</b></div></td></tr>';
        $l_html.=chr(13).'       <tr><td bgColor="#f0f0f0" width="5%" ><div align="center"><b>Cód.</b></div></td>';
        $l_html.=chr(13).'           <td bgColor="#f0f0f0" width="46%"><div align="center"><b>Descrição</b></div></td>';
        $l_html.=chr(13).'           <td bgColor="#f0f0f0" width="30%"><div align="center"><b>Unidade</b></div></td>';
        $l_html.=chr(13).'           <td bgColor="#f0f0f0" width="14%"><div align="center"><b>Fase</b></div></td>';
        $l_html.=chr(13).'       </tr>';
        foreach($RS1 as $row) {
          if (Nvl(f($row,'sq_siw_solicitacao'),'')>'' && $P4!=1)
            $l_html.=chr(13).'       <tr valign="top"><td align="center"><A class="HL" HREF="'.$w_dir.'acao.php?par='.'Visual&R='.$l_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1=2&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'cd_acao').'</a></td>';
          else
          $l_html.=chr(13).'       <tr valign="top"><td align="center">'.f($row,'cd_acao').'</td>';
          $l_html.=chr(13).'           <td>'.f($row,'descricao_acao').'</td>';
          $l_html.=chr(13).'           <td>'.f($row,'cd_unidade').' - '.f($row,'ds_unidade').'</td>';
          $l_html.=chr(13).'           <td>'.Nvl(f($row,'nm_tramite'),'Não Cadastrada').'</td>';
          $l_html.=chr(13).'       </tr>';
        } 
        $l_html.=chr(13).'     </table></div></td></tr>';
      } else {
        $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">Não existe nenhuma ação para este programa</div></td></tr>';
      } 
    } 
*/
    // Encaminhamentos
    if (strtoupper($l_ocorrencia)==strtoupper('sim')) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>OCORRÊNCIAS E ANOTAÇÕES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $RS = db_getSolicLog::getInstanceOf($dbms,$l_chave,null,'LISTA');
      $RS = SortArray($RS,'phpdt_data','desc');
      if (count($RS)>0) {
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
          $l_html.=chr(13).'    <tr><td nowrap>'.FormataDataEdicao(f($row,'phpdt_data'),3).'</td>';
          $l_html.=chr(13).'        <td>'.CRLF2BR(Nvl(f($row,'despacho'),'---')).'</td>';
          $l_html.=chr(13).'        <td nowrap>'.ExibePessoa('../',$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'responsavel')).'</td>';
          if ((Nvl(f($row,'sq_projeto_log'),'')>'') && (Nvl(f($row,'destinatario'),'')>''))         $l_html.=chr(13).'        <td nowrap>'.ExibePessoa('../',$w_cliente,f($row,'sq_pessoa_destinatario'),$TP,f($row,'destinatario')).'</td>';
          elseif ((Nvl(f($row,'sq_projeto_log'),'')>'')  && (Nvl(f($row,'destinatario'),'')==''))   $l_html.=chr(13).'        <td nowrap>Anotação</td>';
          else $l_html.=chr(13).'        <td nowrap>'.Nvl(f($row,'tramite'),'---').'</td>';
          $l_html.=chr(13).'      </tr>';
        } 
        $l_html.=chr(13).'         </table></div></td></tr>';
      } else {
        $l_html.=chr(13).'      <tr><td colspan="2"><div align="center">Não foi encontrado nenhum encaminhamento</div></td></tr>';
      } 
    } 
    //Dados da Consulta
    if (strtoupper($l_consulta)==strtoupper('sim')) {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS DA CONSULTA<hr NOSHADE color=#000000 SIZE=1></b></td></tr>';
      $l_html.=chr(13).'   <tr><td><b>Consulta realizada por:</b></td>';
      $l_html.=chr(13).'       <td>'.$_SESSION['NOME_RESUMIDO'].'</td></tr>';
      $l_html.=chr(13).'   <tr><td><b>Data da consulta:</b></td>';
      $l_html.=chr(13).'       <td>'.FormataDataEdicao(time(),3).'</td></tr>';
    }
  } 
  return $l_html;
} 
?>