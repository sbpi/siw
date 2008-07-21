<?
// =========================================================================
// Rotina de visualiza��o dos dados do documento
// -------------------------------------------------------------------------
function VisualDocumento($l_chave,$l_o,$l_usuario,$l_p1,$l_formato,$l_identificacao,$l_assunto_princ,$l_orcamentaria,$l_indicador,$l_recurso,$l_interessado,$l_anexo,$l_meta,$l_ocorrencia,$l_consulta) {
  extract($GLOBALS);

  //Recupera as informa��es do sub-menu
  $RS = db_getLinkSubMenu::getInstanceOf($dbms, $w_cliente, f($RS_Menu,'sigla'));
  foreach ($RS as $row) {
    if     (strpos(f($row,'sigla'),'ANEXO')!==false)    $l_nome_menu['ANEXO'] = strtoupper(f($row,'nome'));
    elseif (strpos(f($row,'sigla'),'GERAL')!==false)    $l_nome_menu['GERAL'] = strtoupper(f($row,'nome'));
    elseif (strpos(f($row,'sigla'),'INTERES')!==false)  $l_nome_menu['INTERES'] = strtoupper(f($row,'nome'));
    elseif (strpos(f($row,'sigla'),'ASS')!==false)      $l_nome_menu['ASSUNTO'] = strtoupper(f($row,'nome'));
    else $l_nome_menu[f($row,'sigla')] = strtoupper(f($row,'nome'));
  }

  $l_html='';
  // Recupera os dados do documento
  $RS = db_getSolicData::getInstanceOf($dbms,$l_chave,'PADCAD');

  $l_html.=chr(13).'    <table width="100%" border="0" cellspacing="3">';
  if ($O!='T' && $O!='V') {
    if ($l_formato!='WORD') $l_html.=chr(13).'      <tr><td align="right" colspan="2"><br><b><A class="HL" HREF="'.$w_dir.'documento.php?par=Visual&O=T&w_chave='.f($RS,'sq_siw_solicitacao').'&w_tipo=volta&P1=&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'" title="Exibe as informa��es do documento.">Exibir todas as informa��es</a></td></tr>';
  }
  $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
  if (f($RS,'processo')=='S') $w_tipo = 'PROCESSO'; else $w_tipo='DOCUMENTO';
  $l_html.=chr(13).'      <tr><td colspan="2"  bgcolor="#f0f0f0" align=justify><font size="2"><b>'.$w_tipo.': '.f($RS,'protocolo').'</b></font></td></tr>';
  $l_html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=4></td></tr>';
  // Identifica��o do documento
  if ($l_identificacao=='S') {
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
      $l_html.=chr(13).'       <td>'.f($RS,'nm_pessoa_origem').'</td></tr>';
      $l_html.=chr(13).'   <tr><td><b>Interessado principal:</b></td>';
      $l_html.=chr(13).'       <td>'.f($RS,'nm_pessoa_interes').'</td></tr>';
    }
    $l_html.=chr(13).'   <tr><td><b>Cidade:</b></td>';
    $l_html.=chr(13).'       <td>'.f($RS,'nm_cidade').'</td></tr>';
    $l_html.=chr(13).'   <tr><td><b>Esp�cie documental:</b></td>';
    $l_html.=chr(13).'       <td>'.f($RS,'nm_especie').'</td></tr>';
    $l_html.=chr(13).'   <tr><td><b>N�mero:</b></td>';
    $l_html.=chr(13).'       <td>'.f($RS,'numero_original').'</td></tr>';
    $l_html.=chr(13).'   <tr><td><b>Data do documento:</b></td>';
    $l_html.=chr(13).'       <td>'.formataDataEdicao(f($RS,'inicio')).'</td></tr>';
    if (nvl(f($RS,'copias'),'')!='') {
      $l_html.=chr(13).'   <tr><td><b>N� de c�pias:</b></td>';
      $l_html.=chr(13).'       <td>'.formatNumber(f($RS,'copias'),0).'</td></tr>';
    }
    if (nvl(f($RS,'volumes'),'')!='') {
      $l_html.=chr(13).'   <tr><td><b>N� de volumes:</b></td>';
      $l_html.=chr(13).'       <td>'.formatNumber(f($RS,'volumes'),0).'</td></tr>';
    }
    $l_html.=chr(13).'   <tr><td><b>Natureza:</b></td>';
    $l_html.=chr(13).'       <td>'.f($RS,'nm_natureza').'</td></tr>';
    $l_html.=chr(13).'   <tr><td><b>Data de cria��o/recebimento:</b></td>';
    $l_html.=chr(13).'       <td>'.formataDataEdicao(f($RS,'data_recebimento')).'</td></tr>';
    $l_html.=chr(13).'   <tr><td><b>Data limite para conclus�o:</b></td>';
    $l_html.=chr(13).'       <td>'.nvl(formataDataEdicao(f($RS,'fim')),'---').'</td></tr>';
    $l_html.=chr(13).'   <tr><td valign="top"><b>Assunto:</b></td>';
    $l_html.=chr(13).'       <td align="justify">'.f($RS,'cd_assunto').'-'.f($RS,'ds_assunto').'</td></tr>';
    $l_html.=chr(13).'   <tr><td valign="top"><b>Complemento do assunto:</b></td>';
    $l_html.=chr(13).'       <td align="justify">'.crlf2br(Nvl(f($RS,'descricao'),'---')).'</td></tr>';
  } 

  if ($O=='T') {
    // Assunto principal
    if ($l_assunto_princ=='S') {
      $l_html.=chr(13).'   <tr><td colspan="2"><br><font size="2"><b>ASSUNTO PRINCIPAL<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html.=chr(13).'   <tr><td valign="top"><b>C�digo:</b></td>';
      $l_html.=chr(13).'       <td align="justify">'.crlf2br(Nvl(f($RS,'cd_assunto'),'---')).'</td></tr>';
      $l_html.=chr(13).'   <tr><td valign="top"><b>Descri��o:</b></td>';
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
      $l_html.=chr(13).'   <tr><td valign="top"><b>Observa��o:</b></td>';
      $l_html.=chr(13).'       <td align="justify">'.crlf2br(Nvl(f($RS,'ob_assunto'),'---')).'</td></tr>';
      $l_html.=chr(13).'   <tr><td valign="top"><b>Prazos de guarda:</b></td>';
      $l_html.=chr(13).'       <td align="justify"><table border=1>';
      $l_html.=chr(13).'         <tr valign="top"><td align="center"><b>Fase corrente<td align="center"><b>Fase intermedi�ria<td align="center"><b>Destina��o final';
      $l_html.=chr(13).'         <tr valign="top">';
      $l_html.=chr(13).'           '.((strpos(strtoupper(f($RS,'guarda_corrente')),'ANOS')===false) ? '<td>' : '<td align="center">').f($RS,'guarda_corrente').'</td>';
      $l_html.=chr(13).'           '.((strpos(strtoupper(f($RS,'guarda_intermed')),'ANOS')===false) ? '<td>' : '<td align="center">').f($RS,'guarda_intermed').'</td>';
      $l_html.=chr(13).'           '.((strpos(strtoupper(f($RS,'guarda_final')),'ANOS')===false)    ? '<td>' : '<td align="center">').f($RS,'guarda_final').'</td>';
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
      $l_html.=chr(13).'         <td bgColor="#f0f0f0" width="1%" nowrap align="center"><b>C�digo</b></td>';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0" align="center"><b>Descri��o</b></td>';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0" align="center"><b>Detalhamento</b></td>';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0" align="center"><b>Observa��o</b></td>';
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
  }
  
  if (f($RS,'processo')=='S') {
    //Dados da Consulta
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS DA AUTUA��O<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $l_html.=chr(13).'   <tr><td><b>Data da autua��o:</b></td>';
    $l_html.=chr(13).'       <td>'.FormataDataEdicao(f($RS,'data_autuacao')).'</td></tr>';
    $l_html.=chr(13).'   <tr><td><b>Unidade autuadora:</b></td>';
    $l_html.=chr(13).'       <td>'.f($RS,'nm_unidade_resp').'</td></tr>';
  } 

  if ($O=='T') {
    // Interessados na execu��o do documento
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
      $l_html.=chr(13).'         <td bgColor="#f0f0f0" align="center"><b>RG/Inscri��o estadual</b></td>';
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
      $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>'.$l_nome_menu['ANEXO'].'<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $l_html.=chr(13).'   <tr><td colspan="2" align="center">';
      $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
      $l_html.=chr(13).'       <tr><td bgColor="#f0f0f0" align="center"><b>T�tulo</b></td>';
      $l_html.=chr(13).'         <td bgColor="#f0f0f0" align="center"><b>Descri��o</b></td>';
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
    $l_html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>OCORR�NCIAS E ANOTA��ES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
    $RS = db_getSolicLog::getInstanceOf($dbms,$l_chave,null,'LISTA');
    $RS = SortArray($RS,'phpdt_data','desc');
    if (count($RS)>0 && $l_ocorrencia=='S') {
      $i=0;
      foreach($RS as $row) {
        if ($i==0) {
          $l_html.=chr(13).'     <tr><td colspan="2">Fase Atual: <b>'.f($row,'fase').'</b></td></tr>';
          $l_html.=chr(13).'     <tr><td colspan="2" align="center">';
          $l_html.=chr(13).'     <table width=100%  border="1" bordercolor="#00000">';
          $l_html.=chr(13).'       <tr>';
          $l_html.=chr(13).'         <td colspan=3 bgColor="#f0f0f0" align="center"><b>Ocorr�ncia</b></td>';
          $l_html.=chr(13).'         <td rowspan=2 bgColor="#f0f0f0" align="center"><b>Origem</b></td>';
          $l_html.=chr(13).'         <td rowspan=2 bgColor="#f0f0f0" align="center"><b>Destino</b></td>';
          $l_html.=chr(13).'         <td rowspan=2 bgColor="#f0f0f0" align="center"><b>Despacho / Descri��o</b></td>';
          $l_html.=chr(13).'       </tr>';
          $l_html.=chr(13).'       <tr>';
          $l_html.=chr(13).'         <td bgColor="#f0f0f0" align="center"><b>Data</b></td>';
          $l_html.=chr(13).'         <td bgColor="#f0f0f0" align="center"><b>Tipo</b></td>';
          $l_html.=chr(13).'         <td bgColor="#f0f0f0" align="center"><b>Executor</b></td>';
          $l_html.=chr(13).'       </tr>';
          $i=1;
        }
        $l_html.=chr(13).'    <tr valign="top">';
        $l_html.=chr(13).'      <td nowrap>'.FormataDataEdicao(f($row,'phpdt_data'),3).'</td>';

        $l_html.=chr(13).'        <td>'.f($row,'origem').'</td>';
        if ($l_formato!='WORD') $l_html.=chr(13).'        <td>'.ExibePessoa('../',$w_cliente,f($row,'sq_pessoa_resp'),$TP,f($row,'nm_pessoa_resp')).'</td>';
        else                    $l_html.=chr(13).'        <td>'.f($row,'nm_pessoa_resp').'</td>';
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
          $l_html.=chr(13).'        <td>---</td>';
        }
        $l_html.=chr(13).'      <td>'.CRLF2BR(Nvl(f($row,'despacho'),'---')).'</td>';
        $l_html.=chr(13).'      </tr>';
      } 
      $l_html.=chr(13).'         </table></td></tr>';
    } else {
      $l_html.=chr(13).'      <tr><td colspan="2" align="center">N�o foi encontrado nenhum encaminhamento</td></tr>';
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
  $l_html.=chr(13).'    </table>';
  return $l_html;
} ?>
