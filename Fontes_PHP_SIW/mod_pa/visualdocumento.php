<?
// =========================================================================
// Rotina de visualização dos dados do documento
// -------------------------------------------------------------------------
function VisualDocumento($l_chave,$l_o,$l_usuario,$l_p1,$l_formato,$l_identificacao,$l_assunto_princ,$l_orcamentaria,$l_indicador,$l_recurso,$l_interessado,$l_anexo,$l_meta,$l_ocorrencia,$l_consulta) {
  extract($GLOBALS);
  include_once($w_dir_volta.'classes/sp/db_getSolicRecursos.php');
  include_once($w_dir_volta.'classes/sp/db_getSolicMeta.php');
  include_once($w_dir_volta.'classes/sp/db_getSolicIndicador.php');
  $l_html='';
  // Recupera os dados do documento
  $RS = db_getSolicData::getInstanceOf($dbms,$l_chave,'PADCAD');

  if ($O!='T' && $O!='V') $l_html.=chr(13).'      <tr><td align="right" colspan="2"><br><b><A class="HL" HREF="'.$w_dir.'documento.php?par=Visual&O=T&w_chave='.f($RS,'sq_siw_solicitacao').'&w_tipo=volta&P1=&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe as informações do documento.">Exibir todas as informações</a></td></tr>';
  $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
  $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0" align=justify><font size="2"><b>PROTOCOLO: '.f($RS,'protocolo').'</b></font></td></tr>';
  $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
  // Identificação do documento
  if ($l_identificacao=='S') {
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DOCUMENTO DE ORIGEM<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    if (f($RS,'interno')=='S') {
      if ($l_formato=='WORD') {
        $l_html.=chr(13).'   <tr><td width="30%"><b>Unidade:</b></td>';
        $l_html.=chr(13).'       <td>'.f($RS,'nm_unid_origem').'</td></tr>';
      } else {
        $l_html.=chr(13).'   <tr><td width="30%"><b>Unidade:</b></td>';
        $l_html.=chr(13).'       <td>'.ExibeUnidade('../',$w_cliente,f($RS,'nm_unid_origem'),f($RS,'sq_unidade'),$TP).'</td></tr>';
      } 
    } else {
      $l_html.=chr(13).'   <tr><td><b>Pessoa:</b></td>';
      $l_html.=chr(13).'       <td>'.f($RS,'nm_pessoa').'</td></tr>';
    }
    $l_html.=chr(13).'   <tr><td><b>Cidade:</b></td>';
    $l_html.=chr(13).'       <td>'.f($RS,'nm_cidade').'</td></tr>';
    $l_html.=chr(13).'   <tr><td><b>Espécie documental:</b></td>';
    $l_html.=chr(13).'       <td>'.f($RS,'nm_especie').'</td></tr>';
    $l_html.=chr(13).'   <tr><td><b>Número:</b></td>';
    $l_html.=chr(13).'       <td>'.f($RS,'numero_original').'</td></tr>';
    $l_html.=chr(13).'   <tr><td><b>Data do documento:</b></td>';
    $l_html.=chr(13).'       <td>'.formataDataEdicao(f($RS,'inicio')).'</td></tr>';
    $l_html.=chr(13).'   <tr><td><b>Natureza:</b></td>';
    $l_html.=chr(13).'       <td>'.nvl(f($RS,'nm_natureza'),'---').'</td></tr>';
    $l_html.=chr(13).'   <tr><td><b>Data de recebimento:</b></td>';
    $l_html.=chr(13).'       <td>'.formataDataEdicao(f($RS,'data_recebimento')).'</td></tr>';
    $l_html.=chr(13).'   <tr><td><b>Data limite para conclusão:</b></td>';
    $l_html.=chr(13).'       <td>'.nvl(formataDataEdicao(f($RS,'fim')),'---').'</td></tr>';
  } 

  // Assunto principal
  if ($l_assunto_princ=='S') {
    $l_html.=chr(13).'   <tr><td colspan="2"><br><font size="2"><b>ASSUNTO PRINCIPAL<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $l_html.=chr(13).'   <tr><td valign="top"><b>Código:</b></td>';
    $l_html.=chr(13).'       <td align="justify">'.crlf2br(Nvl(f($RS,'cd_assunto'),'---')).'</td></tr>';
    $l_html.=chr(13).'   <tr><td valign="top"><b>Descrição:</b></td>';
    $l_html.=chr(13).'       <td align="justify">'.f($RS,'ds_assunto');
    if (nvl(f($RS,'ds_assunto_pai'),'')!='') { 
      $l_html.=chr(13).'<br>';
      if (nvl(f($RS,'ds_assunto_bis'),'')!='') $l_html.=chr(13).strtolower(f($RS,'ds_assunto_bis')).' &rarr; ';
      if (nvl(f($RS,'ds_assunto_avo'),'')!='') $l_html.=chr(13).strtolower(f($RS,'ds_assunto_avo')).' &rarr; ';
      if (nvl(f($RS,'ds_assunto_pai'),'')!='') $l_html.=chr(13).strtolower(f($RS,'ds_assunto_pai'));
    }
    $l_html.=chr(13).'       </td></tr>';
    $l_html.=chr(13).'   <tr><td valign="top"><b>Detalhamento:</b></td>';
    $l_html.=chr(13).'       <td align="justify">'.crlf2br(Nvl(f($RS,'dst_assunto'),'---')).'</td></tr>';
    $l_html.=chr(13).'   <tr><td valign="top"><b>Observação:</b></td>';
    $l_html.=chr(13).'       <td align="justify">'.crlf2br(Nvl(f($RS,'ob_assunto'),'---')).'</td></tr>';
    $l_html.=chr(13).'   <tr><td valign="top"><b>Complemento:</b></td>';
    $l_html.=chr(13).'       <td align="justify">'.crlf2br(Nvl(f($RS,'descricao'),'---')).'</td></tr>';
    $l_html.=chr(13).'   <tr><td valign="top"><b>Prazos de guarda:</b></td>';
    $l_html.=chr(13).'       <td align="justify"><table border=1>';
    $l_html.=chr(13).'         <tr valign="top"><td align="center"><b>Fase corrente<td align="center"><b>Fase intermediária<td align="center"><b>Destinação final';
    $l_html.=chr(13).'         <tr valign="top">';
    $l_html.=chr(13).'           '.((strpos(strtoupper(f($RS,'guarda_corrente')),'ANOS')===false) ? '<td>' : '<td align="center">').f($RS,'guarda_corrente').'</td>';
    $l_html.=chr(13).'           '.((strpos(strtoupper(f($RS,'guarda_intermed')),'ANOS')===false) ? '<td>' : '<td align="center">').f($RS,'guarda_intermed').'</td>';
    $l_html.=chr(13).'           '.((strpos(strtoupper(f($RS,'guarda_final')),'ANOS')===false)    ? '<td>' : '<td align="center">').f($RS,'guarda_final').'</td>';
    $l_html.=chr(13).'         </table>';
  } 

  if ($O=='T') {
    // Assuntos complementares
    $RS1 = db_getDocumentoAssunto::getInstanceOf($dbms,$l_chave,null,'N',null);
    $RS1 = SortArray($RS1,'principal','desc','nome','asc');
    if (count($RS1)>0 && $l_interessado=='S') {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ASSUNTOS COMPLEMENTARES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
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
          if (nvl(f($RS,'ds_assunto_bis'),'')!='') $l_html.=chr(13).strtolower(f($RS,'ds_assunto_bis')).' &rarr; ';
          if (nvl(f($RS,'ds_assunto_avo'),'')!='') $l_html.=chr(13).strtolower(f($RS,'ds_assunto_avo')).' &rarr; ';
          if (nvl(f($RS,'ds_assunto_pai'),'')!='') $l_html.=chr(13).strtolower(f($RS,'ds_assunto_pai'));
        }
        $l_html.=chr(13).'           </td>';
        $l_html.=chr(13).'           <td>'.nvl(strtolower(f($row,'detalhamento')),'---').'</td>';
        $l_html.=chr(13).'           <td>'.nvl(strtolower(f($row,'observacao')),'---').'</td>';
        $l_html.=chr(13).'      </tr>';
      }
      $l_html.=chr(13).'         </table></td></tr>';
    } 

    // Interessados na execução do documento
    $RS1 = db_getDocumentoInter::getInstanceOf($dbms,$l_chave,null,null,null);
    $RS1 = SortArray($RS1,'principal','desc','nome','asc');
    if (count($RS1)>0 && $l_interessado=='S') {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>INTERESSADOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
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

    // Arquivos vinculados ao programa
    $RS1 = db_getSolicAnexo::getInstanceOf($dbms,$l_chave,null,$w_cliente);
    $RS1 = SortArray($RS1,'nome','asc');
    if (count($RS1)>0 && $l_anexo=='S') {
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ANEXOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
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
  }

  // Encaminhamentos
  if ($l_ocorrencia=='S') {
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>OCORRÊNCIAS E ANOTAÇÕES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $RS = db_getSolicLog::getInstanceOf($dbms,$l_chave,null,'LISTA');
    $RS = SortArray($RS,'phpdt_data','desc');
    if (count($RS)>0 && $l_ocorrencia=='S') {
      $l_html.=chr(13).'   <tr><td colspan="2" align="center">';
      $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
      $l_html.=chr(13).'       <tr><td bgColor="#f0f0f0" align="center"><b>Data</b></td>';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0" align="center"><b>Ocorrência/Anotação</b></td>';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0" align="center"><b>Responsável</b></td>';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0" align="center"><b>Fase/Destinatário</b></td>';
      $l_html.=chr(13).'       </tr>';
      $i=0;
      foreach($RS as $row) {
        if ($i==0) {
          $l_html.=chr(13).'       <tr><td colspan="4">Fase Atual: <b>'.f($row,'fase').'</b></td></tr>';
          $i=1;
        }
        $l_html.=chr(13).'    <tr valign="top">';
        $l_html.=chr(13).'      <td nowrap>'.FormataDataEdicao(f($row,'phpdt_data'),3).'</td>';
        $l_html.=chr(13).'      <td>'.CRLF2BR(Nvl(f($row,'despacho'),'---')).'</td>';
        if ($l_formato=='WORD') $l_html.=chr(13).'      <td nowrap>'.f($row,'responsavel').'</td>';
        else                    $l_html.=chr(13).'      <td nowrap>'.ExibePessoa('../',$w_cliente,f($row,'sq_pessoa'),$TP,f($row,'responsavel')).'</td>';
        if ((Nvl(f($row,'sq_projeto_log'),'')>'') && (Nvl(f($row,'destinatario'),'')>''))         $l_html.=chr(13).'        <td nowrap>'.ExibePessoa('../',$w_cliente,f($row,'sq_pessoa_destinatario'),$TP,f($row,'destinatario')).'</td>';
        elseif ((Nvl(f($row,'sq_projeto_log'),'')>'')  && (Nvl(f($row,'destinatario'),'')==''))   $l_html.=chr(13).'        <td nowrap>Anotação</td>';
        else $l_html.=chr(13).'        <td nowrap>'.Nvl(f($row,'tramite'),'---').'</td>';
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
    $l_html.=chr(13).'       <td>'.FormataDataEdicao(time(),3).'</td></tr>';
  } 
  return $l_html;
} ?>
