<?
setlocale(LC_ALL, 'ptb');
date_default_timezone_set('America/Sao_Paulo');
//$locale_info = localeconv();
//echo "<pre>\n";
//echo "--------------------------------------------\n";
//echo "  Monetary information for current locale:  \n";
//echo "--------------------------------------------\n\n";
//echo "int_curr_symbol:   {$locale_info["int_curr_symbol"]}\n";
//echo "currency_symbol:   {$locale_info["currency_symbol"]}\n";
//echo "mon_decimal_point: {$locale_info["mon_decimal_point"]}\n";
//echo "mon_thousands_sep: {$locale_info["mon_thousands_sep"]}\n";
//echo "positive_sign:     {$locale_info["positive_sign"]}\n";
//echo "negative_sign:     {$locale_info["negative_sign"]}\n";
//echo "int_frac_digits:   {$locale_info["int_frac_digits"]}\n";
//echo "frac_digits:       {$locale_info["frac_digits"]}\n";
//echo "p_cs_precedes:     {$locale_info["p_cs_precedes"]}\n";
//echo "p_sep_by_space:    {$locale_info["p_sep_by_space"]}\n";
//echo "n_cs_precedes:     {$locale_info["n_cs_precedes"]}\n";
//echo "n_sep_by_space:    {$locale_info["n_sep_by_space"]}\n";
//echo "p_sign_posn:       {$locale_info["p_sign_posn"]}\n";
//echo "n_sign_posn:       {$locale_info["n_sign_posn"]}\n";
//echo "</pre>\n";

// =========================================================================
// Função garante que as chaves de um array estarão no caso indicado
// -------------------------------------------------------------------------
function array_key_case_change(&$array, $mode = 'CASE_LOWER') { 
  // Make sure $array is really an array 
   if (!is_array($array)) return false; 
   
   $temp = $array; 
   while (list($key, $value) = each($temp)) { 
       // First we unset the original so it's not lingering about 
       
       unset($array[$key]); 
       // Then modify the $key 
       switch($mode) { 
           case 'CASE_UPPER': $value = array_change_key_case($value,CASE_UPPER); break; 
           case 'CASE_LOWER': $value = array_change_key_case($value,CASE_LOWER); break; 
       } 

       // Lastly read to the array using the new $key 
       $array[$key] = $value; 
   } 
   return true; 
}

// =========================================================================
// Função para classificação de arrays
// -------------------------------------------------------------------------
function SortArray() {
  $arguments = func_get_args();
  $array = $arguments[0];
  $code = '';
  for ($c = 1; $c < count($arguments); $c += 2) {
    if (in_array($arguments[$c + 1], array("asc", "desc"))) {
      $code .= 'if ($a["'.$arguments[$c].'"] != $b["'.$arguments[$c].'"]) {';
      if ($arguments[$c + 1] == "asc") {
        $code .= 'return ($a["'.$arguments[$c].'"] < $b["'.$arguments[$c].'"] ? -1 : 1); }';
      } else {
        $code .= 'return ($a["'.$arguments[$c].'"] < $b["'.$arguments[$c].'"] ? 1 : -1); }';
      }
    }
  }
  $code .= 'return 0;';
  $compare = create_function('$a,$b', $code);
  usort($array, $compare);
  return $array;
}

// =========================================================================
// Montagem do link para abrir o calendário
// -------------------------------------------------------------------------
function exibeCalendario ($form, $campo) {
  extract($GLOBALS);
  return '   <a class="ss" href="#" onClick="window.open(\''.$w_dir_volta.'calendario.php?nmForm='.$form.'&nmCampo='.$campo.'&vData=\'+document.'.$form.'.'.$campo.'.value,\'dp\',\'toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=0,width=250,height=250,left=500,top=200\'); return false;" title="Visualizar calendário"><img src=images/Icone/goToTop.gif border=0 align=top height=13 width=15></a>';
}

// =========================================================================
// Gera um link chamando o arquivo desejado
// -------------------------------------------------------------------------
function LinkArquivo ($p_classe, $p_cliente, $p_arquivo, $p_target, $p_hint, $p_descricao, $p_retorno) {

  // Monta a chamada para a página que retorna o arquivo
  $l_link = 'file.php?force=false&cliente='.$p_cliente.'&id='.$p_arquivo;

  If (strtoupper(Nvl($p_retorno,'')) == 'WORD') { // Se for geraçao de Word, dispensa sessão ativa
     // Altera a chamada padrão, dispensando a sessão
     $l_link = 'file.php?force=false&sessao=false&cliente=' & $p_cliente & '&id=' & $p_arquivo;
  } ElseIf (strtoupper(Nvl($p_retorno,'')) <> 'EMBED') { // Se não for objeto incorporado, monta tag anchor
     // Trata a possibilidade da chamada ter passado classe, target e hint
     If (Nvl($p_classe,'') > '') $l_classe = ' class="' . $p_classe . '" ';  Else $l_classe = '';
     If (Nvl($p_target,'') > '') $l_target = ' target="' . $p_target . '" '; Else $l_target = '';
     If (Nvl($p_hint,'')   > '') $l_hint   = ' title="' . $p_hint . '" ';    Else $l_hint   = '';

     // Montagem da tag anchor
     $l_link = '<a'.$l_classe.'href="'.str_replace('force=false','force=true',$l_link).'"'.$l_target.$l_hint.'>'.$p_descricao.'</a>';
  }
  
  // Retorno ao chamador
  return $l_link;
}

// =========================================================================
// Declaração inicial para páginas OLE com Word
// -------------------------------------------------------------------------
function headerWord($p_orientation='LANDSCAPE') {
  extract($GLOBALS);
  header('Content-type: application/msword',false);
  ShowHTML('<html xmlns:o="urn:schemas-microsoft-com:office:office" ');
  ShowHTML('xmlns:w="urn:schemas-microsoft-com:office:word" ');
  ShowHTML('xmlns="http://www.w3.org/TR/REC-html40"> ');
  ShowHTML('<head> ');
  ShowHTML('<meta http-equiv=Content-Type content="text/html; charset=windows-1252"> ');
  ShowHTML('<meta name=ProgId content=Word.Document> ');
  ShowHTML('<!--[if gte mso 9]><xml> ');
  ShowHTML(' <w:WordDocument> ');
  ShowHTML('  <w:View>Print</w:View> ');
  ShowHTML('  <w:Zoom>BestFit</w:Zoom> ');
  ShowHTML('  <w:SpellingState>Clean</w:SpellingState> ');
  ShowHTML('  <w:GrammarState>Clean</w:GrammarState> ');
  ShowHTML('  <w:HyphenationZone>21</w:HyphenationZone> ');
  ShowHTML('  <w:Compatibility> ');
  ShowHTML('   <w:BreakWrappedTables/> ');
  ShowHTML('   <w:SnapToGridInCell/> ');
  ShowHTML('   <w:WrapTextWithPunct/> ');
  ShowHTML('   <w:UseAsianBreakRules/> ');
  ShowHTML('  </w:Compatibility> ');
  ShowHTML('  <w:BrowserLevel>MicrosoftInternetExplorer4</w:BrowserLevel> ');
  ShowHTML(' </w:WordDocument> ');
  ShowHTML('</xml><![endif]--> ');
  ShowHTML('<style> ');
  ShowHTML('<!-- ');
  ShowHTML(' /* Style Definitions */ ');
  ShowHTML('@page Section1 ');
  if (strtoupper(Nvl($p_orientation,'LANDSCAPE'))=='PORTRAIT') {
     ShowHTML('    {size:8.5in 11.0in; ');
     ShowHTML('    mso-page-orientation:landscape; ');
     ShowHTML('    margin:2.0cm 2.0cm 2.0cm 2.0cm; ');
     ShowHTML('    mso-header-margin:35.4pt; ');
     ShowHTML('    mso-footer-margin:35.4pt; ');
     ShowHTML('    mso-paper-source:0;} ');
  } else {
     ShowHTML('    {size:11.0in 8.5in; ');
     ShowHTML('    mso-page-orientation:landscape; ');
     ShowHTML('    margin:60.85pt 1.0cm 60.85pt 2.0cm; ');
     ShowHTML('    mso-header-margin:35.4pt; ');
     ShowHTML('    mso-footer-margin:35.4pt; ');
     ShowHTML('    mso-paper-source:0;} ');
  }
  ShowHTML('div.Section1 ');
  ShowHTML('    {page:Section1;} ');
  ShowHTML('--> ');
  ShowHTML('</style> ');
  ShowHTML('</head> ');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
  BodyOpen('onLoad=document.focus();');
  ShowHTML('<div class=Section1> ');
}

// =========================================================================
// Montagem do cabeçalho de documentos Word
// -------------------------------------------------------------------------
function CabecalhoWord($p_cliente,$p_titulo,$p_pagina) {
  extract($GLOBALS);
  include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
  $RS = db_getCustomerData::getInstanceOf($dbms,$p_cliente);
  ShowHTML('<TABLE WIDTH="100%" BORDER=0>');
  ShowHTML('  <TR>');
  if (nvl($p_pagina,0)>0) $l_colspan = 3; else $l_colspan = 2;
  ShowHTML('    <TD ROWSPAN='.$l_colspan.'><IMG ALIGN="LEFT" SRC="'.$conFileVirtual.$w_cliente.'/img/'.f($RS,'LOGO').'" width=56 height=67>');
  ShowHTML('    <TD ALIGN="RIGHT"><B><FONT SIZE=4 COLOR="#000000">'.$p_titulo.'</FONT>');
  ShowHTML('  </TR>');
  ShowHTML('  <TR><TD ALIGN="RIGHT"><B><FONT SIZE=2 COLOR="#000000">'.DataHora().'</B></TD></TR>');
  if (nvl($p_pagina,0)>0) ShowHTML('  <TR><TD ALIGN="RIGHT"><B><FONT SIZE=2 COLOR="#000000">Página: '.$p_pagina.'</B></TD></TR>');
  ShowHTML('  <TR><TD colspan=2><HR></td></tr>');
  ShowHTML('</TABLE>');
}

// =========================================================================
// Montagem de link para ordenação, usada nos títulos de colunas
// -------------------------------------------------------------------------
function LinkOrdena($p_label,$p_campo) {
  extract($GLOBALS);
  foreach($_POST as $chv => $vlr) {
    if (nvl($vlr,'')>'' && (strtoupper(substr($chv,0,2))=="W_" || strtoupper(substr($chv,0,2))=="P_")) {
      if (strtoupper($chv)=="P_ORDENA") {
        $l_ordena=strtoupper($vlr);
      } else {
        $l_string=$l_string.'&'.$chv."=".$vlr;
      }
    }
  }
  foreach($_GET as $chv => $vlr) {
    if (nvl($vlr,'')>'' && (strtoupper(substr($chv,0,2))=="W_" || strtoupper(substr($chv,0,2))=="P_")) {
      if (strtoupper($chv)=="P_ORDENA") {
        $l_ordena=strtoupper($vlr);
      } else {
        $l_string=$l_string.'&'.$chv."=".$vlr;
      }
    }
  }
  if (strtoupper($p_campo)==str_replace(' DESC','',str_replace(' ASC','',strtoupper($l_ordena)))) {
    if (strpos(strtoupper($l_ordena),' DESC') !== false) {
      $l_string=$l_string.'&p_ordena='.$p_campo.' asc&';
      $l_img='&nbsp;<img src=images/down.gif width=8 height=8 border=0 align="absmiddle">';
    } else {
      $l_string=$l_string.'&p_ordena='.$p_campo.' desc&';
      $l_img='&nbsp;<img src=images/up.gif width=8 height=8 border=0 align="absmiddle">';
    }
  } else {
    $l_string=$l_string.'&p_ordena='.$p_campo.' asc&';
  }
  return '<a class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&P3=1'.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.$l_string.'" title="Ordena a listagem por esta coluna.">'.$p_label.'</a>'.$l_img;
}

// =========================================================================
// Montagem do cabeçalho de relatórios
// -------------------------------------------------------------------------
function CabecalhoRelatorio($p_cliente,$p_titulo) {
  extract($GLOBALS);
  $RS = db_getCustomerData::getInstanceOf($dbms,$p_cliente);
  ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" SRC="files\\'.$w_cliente.'\\img\\'.f($RS,'logo').'><TD ALIGN="RIGHT"><B><FONT SIZE=4 COLOR="#000000">');
  ShowHTML($p_titulo);
  ShowHTML('</FONT><TR><TD ALIGN="RIGHT"><B><FONT SIZE=2 COLOR="#000000">'.DataHora().'</B></TD></TR>');
  ShowHTML('</FONT></B></TD></TR></TABLE>');
  ShowHTML('<HR>');
}

// =========================================================================
// Montagem da barra de navegação de recordsets
// -------------------------------------------------------------------------
function MontaBarra($p_link,$p_PageCount,$p_AbsolutePage,$p_PageSize,$p_RecordCount) {
  extract($GLOBALS);
  ShowHTML('<SCRIPT LANGUAGE="JAVASCRIPT">');
  ShowHTML('  function pagina (pag) {');
  ShowHTML('    document.Barra.P3.value = pag;');
  ShowHTML('    document.Barra.submit();');
  ShowHTML('  }');
  ShowHTML('</SCRIPT>');
  ShowHTML('<FORM ACTION="'.$p_link.'" METHOD="POST" name="Barra">');
  ShowHTML('<input type="Hidden" name="P4" value="'.$p_PageSize.'">');
  ShowHTML('<input type="Hidden" name="P3" value="">');
  ShowHTML(MontaFiltro('POST'));
  if ($p_PageSize<$p_RecordCount) {
    if ($p_PageCount==$p_AbsolutePage) {
      ShowHTML('<span class="STC"><br>'.($p_RecordCount-(($p_PageCount-1)*$p_PageSize)).' linhas apresentadas de '.$p_RecordCount.' linhas');
    } else {
      ShowHTML('<span class="STC"><br>'.$p_PageSize.' linhas apresentadas de '.$p_RecordCount.' linhas');
    }
    ShowHTML('<br>na página '.$p_AbsolutePage.' de '.$p_PageCount.' páginas');
    if ($p_AbsolutePage>1) {
      ShowHTML('<br>[<A class="ss" TITLE="Primeira página" HREF="javascript:pagina(1)" onMouseOver="window.status=\'Primeira (1/'.$p_PageCount.')\'; return true" onMouseOut="window.status=\'\'; return true;">Primeira</A>]&nbsp;');
      ShowHTML('[<A class="ss" TITLE="Página anterior" HREF="javascript:pagina('.($p_AbsolutePage-1).')" onMouseOver="window.status=\'Anterior ('.($p_AbsolutePage-1).'/'.$p_PageCount.')\'; return true;" onMouseOut="window.status=\'\'; return true;">Anterior</A>]&nbsp;');
    } else {
      ShowHTML('<br>[Primeira]&nbsp;');
      ShowHTML('[Anterior]&nbsp;');
    }
    if ($p_PageCount==$p_AbsolutePage) {
      ShowHTML('[Próxima]&nbsp;');
      ShowHTML('[Última]');
    } else {
      ShowHTML('[<A class="ss" TITLE="Página seguinte" HREF="javascript:pagina('.($p_AbsolutePage+1).')"  onMouseOver="window.status=\'Próxima ('.($p_AbsolutePage+1).'/'.$p_PageCount.')\'; return true" onMouseOut="window.status=\'\'; return true">Próxima</A>]&nbsp;');
      ShowHTML('[<A class="ss" TITLE="Última página" HREF="javascript:pagina('.$p_PageCount.')"  onMouseOver="window.status=\'Última ('.$p_PageCount.'/'.$p_PageCount.')\'; return true" onMouseOut="window.status=\'\'; return true">Última</A>]');
    }
    ShowHTML('</span>');
  }
  ShowHtml('</FORM>');
}

// =========================================================================
// Retorna o nível de acesso que o usuário tem à solicitação informada
// -------------------------------------------------------------------------
function SolicAcesso($p_solicitacao,$p_usuario) {
  extract($GLOBALS);
  $l_acesso = db_getSolicAcesso::getInstanceOf($dbms, $p_solicitacao, $p_usuario);
  return $l_acesso;
}

// =========================================================================
// Função que retorna S/N indicando se há expediente na data informada
// -------------------------------------------------------------------------
function RetornaExpediente($p_data, $p_cliente, $p_pais, $p_uf, $p_cidade) {
  extract($GLOBALS);
  include_once($w_dir_volta.'classes/sp/db_VerificaDataEspecial.php');
  $l_expediente = db_VerificaDataEspecial::getInstanceOf($dbms,$p_data, $p_cliente, $p_pais, $p_uf, $p_cidade);
  return $l_expediente;
}

// =========================================================================
// Retorna o tipo de recurso a partir do código
// -------------------------------------------------------------------------
function RetornaTipoRecurso($l_chave) {
  extract($GLOBALS);

  switch ($l_chave) {
    case 0: return 'Financeiro';   break;
    case 1: return 'Humano';       break;
    case 2: return 'Material';     break;
    case 3: return 'Metodológico'; break;
    default:return 'Erro';        break;
  }
}

// =========================================================================
// Retorna uma parte qualquer de uma linha delimitada
// -------------------------------------------------------------------------
function Piece($p_line,$p_delimiter,$p_separator,$p_position) {
  $l_actual=$p_line;
  $l_result=$p_line;
  if (Nvl($p_separator,'')>'') {
    for ($l_i=1; $l_i<=$p_position; $l_i=$l_i+1) {
      if ((strpos($l_actual,$p_separator) ? strpos($l_actual,$p_separator)+1 : 0)>0) {
        $l_result=substr($l_actual,0,(strpos($l_actual,$p_separator) ? strpos($l_actual,$p_separator)+1 : 0)-1);
        $l_actual=substr($l_actual,(strpos($l_actual,$p_separator) ? strpos($l_actual,$p_separator)+1 : 0)+1-1,strlen($l_actual));
        if ($l_i==$p_position-1 && (strpos($l_actual,$p_separator) ? strpos($l_actual,$p_separator)+1 : 0)==0) {
          $l_actual=$l_actual.';';
        }
      } else {
        $Piece='';
        break;
      }
    }
  }
  return $l_result;
}

// =========================================================================
// Montagem da URL com os parâmetros de filtragem
// -------------------------------------------------------------------------
function MontaFiltro($p_method) {
  extract($GLOBALS);
  if (strtoupper($p_method)=='GET' || strtoupper($p_method)=='POST') {
    $l_string='';
    foreach ($_POST as $l_Item => $l_valor) {
      if (substr($l_Item,0,2)=='p_' && $l_valor>'') {
        if (strtoupper($p_method)=='GET') {
          if (is_array($_POST[$l_Item])) {
            $l_string=$l_string.'&'.$l_Item.'='.explodeArray($_POST[$l_Item]);
          } else {
            $l_string=$l_string.'&'.$l_Item.'='.$l_valor;
          }
        }
        elseif (strtoupper($p_method)=='POST') {
          if (is_array($_POST[$l_Item])) {
            $l_string=$l_string.'<INPUT TYPE="HIDDEN" NAME="'.$l_Item.'" VALUE="'.explodeArray($_POST[$l_Item]).'">';
          } else {
            $l_string=$l_string.'<INPUT TYPE="HIDDEN" NAME="'.$l_Item.'" VALUE="'.$l_valor.'">';
          }
        }
      }
    }
    foreach ($_GET as $l_Item => $l_valor) {
      if (substr($l_Item,0,2)=='p_' && $l_valor>'') {
        if (strtoupper($p_method)=='GET') {
          if (is_array($_GET[$l_Item])) {
            $l_string=$l_string.'&'.$l_Item.'='.explodeArray($_GET[$l_Item]);
          } else {
            $l_string=$l_string.'&'.$l_Item.'='.$l_valor;
          }
        }
        elseif (strtoupper($p_method)=='POST') {
          if (is_array($_GET[$l_Item])) {
            $l_string=$l_string.'<INPUT TYPE="HIDDEN" NAME="'.$l_Item.'" VALUE="'.explodeArray($_GET[$l_Item]).'">';
          } else {
            $l_string=$l_string.'<INPUT TYPE="HIDDEN" NAME="'.$l_Item.'" VALUE="'.$l_valor.'">';
          }
        }
      }
    }
  }
  return $l_string;
}

// =========================================================================
// Exibe o conteúdo da querystring, do formulário e das variáveis de sessão
// -------------------------------------------------------------------------
function ExibeVariaveis() {
  extract($GLOBALS);
  ShowHTML('<DT><font face="Verdana" size=1><b>Dados da querystring:</b></font>');
  foreach($_GET as $chv => $vlr) {
    if (strpos(strtoupper($chv),'W_ASSINATURA') === false ) {
       ShowHTML('<DD><FONT FACE="courier" size=1>'.$chv.' => ['.$vlr.']</font><br>');
    }
  }
  ShowHTML('</DT>');
  ShowHTML('<DT><font face="Verdana" size=1><b>Dados do formulário:</b></font>');
  foreach($_POST as $chv => $vlr) {
    if (strpos(strtoupper($chv),'W_ASSINATURA') === false ) {
       ShowHTML('<DD><FONT FACE="courier" size=1>'.$chv.' => ['.$vlr.']</font><br>');
    }
  }
  ShowHTML('</DT>');
  ShowHTML('<DT><font face="Verdana" size=1><b>Variáveis de sessão</b></font>:');
  foreach($_SESSION as $chv => $vlr) {
    if (strpos(strtoupper($chv),'W_ASSINATURA') === false ) {
       ShowHTML('<DD><FONT FACE="courier" size=1>'.$chv.' => ['.$vlr.']</font><br>');
    }
  }
  ShowHTML('</DT>');
  $w_item=null;
  exit();
}

// =========================================================================
// Montagem da URL com os dados de uma pessoa
// -------------------------------------------------------------------------
function ExibePessoa($p_dir,$p_cliente,$p_pessoa,$p_tp,$p_nome) {
  if (Nvl($p_nome,'')=='') {
    $l_string='---';
  } else {
    $l_string=$l_string.'<A class="hl" HREF="#" onClick="window.open(\''.$p_dir.'seguranca.php?par=TELAUSUARIO&w_cliente='.$p_cliente.'&w_sq_pessoa='.$p_pessoa.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$p_tp.'&SG='.'\',\'Pessoa\',\'width=780,height=300,top=10,left=10,toolbar=no,scrollbars=yes,resizable=yes,status=no\'); return false;" title="Clique para exibir os dados desta pessoa!">'.$p_nome.'</A>';
  }
  return $l_string;
}

// =========================================================================
// Montagem da URL com os dados de uma pessoa
// -------------------------------------------------------------------------
function ExibeUnidade($p_dir,$p_cliente,$p_unidade,$p_sq_unidade,$p_tp) {
  if (Nvl($p_unidade,'')=='') {
    $l_string='---';
  } else {
    $l_string=$l_string.'<A class="hl" HREF="#" onClick="window.open(\''.$p_dir.'seguranca.php?par=TELAUNIDADE&w_cliente='.$p_cliente.'&w_sq_unidade='.$p_sq_unidade.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$p_tp.'&SG='.'\',\'Unidade\',\'width=780,height=300,top=10,left=10,toolbar=no,scrollbars=yes,resizable=yes,status=no\'); return false;" title="Clique para exibir os dados desta unidade!">'.$p_unidade.'</A>';
  }
  return $l_string;
}

// =========================================================================
// Montagem da URL com os dados da etapa
// -------------------------------------------------------------------------
function ExibeEtapa($O,$p_chave,$p_chave_aux,$p_tipo,$p_P1,$p_etapa,$p_tp,$p_sg) {
  if (Nvl($p_etapa,'')=='') {
    $l_string="---";
  } else {
    $l_string=$l_string.'<A class="hl" HREF="#" onClick="window.open(\'projeto.php?par=AtualizaEtapa&w_chave='.$p_chave.'&O='.$O.'&w_chave_aux='.$p_chave_aux.'&w_tipo='.$p_tipo.'&P1='.$p_P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$p_tp.'&SG='.$p_sg.'\',\'Etapa\',\'width=780,height=350,top=50,left=10,toolbar=no,scrollbars=yes,resizable=yes,status=no\'); return false;" title="Clique para exibir os dados!">'.$p_etapa.'</A>';
  }
  return $l_string;
}

// =========================================================================
// Montagem da URL com os parâmetros de filtragem quando o for UPLOAD
// -------------------------------------------------------------------------
function MontaFiltroUpload($p_Form) {
  extract($GLOBALS);
  $l_string='';
  foreach ($p_Form as $l_Item) {
    if (substr($l_item,0,2)=="p_" && $l_item->value>'') {
      $l_string=$l_string."&".$l_Item."=".$l_item->value;
    }
  }
  return $l_string;
}

// =========================================================================
// Rotina que monta número de ordem da etapa do projeto
// -------------------------------------------------------------------------
function MontaOrdemEtapa($l_chave) {
  extract($GLOBALS);
  $RSQuery = db_getEtapaDataParents::getInstanceOf($dbms, $l_chave);
  $w_texto = '';
  $w_contaux = 0;
  foreach($RSQuery as $row) {
    $w_contaux = $w_contaux+1;
    if ($w_contaux==1) {
      $w_texto = f($row,'ordem').'.'.$w_texto;
    } else {
      $w_texto = f($row,'ordem').'.'.$w_texto;
    }
  }
  return substr($w_texto,0,strlen($w_texto)-1);
}

// =========================================================================
// Converte CFLF para <BR>
// -------------------------------------------------------------------------
function CRLF2BR($expressao) { if (!isset($expressao) || $expressao=='') { return ''; } else { return str_replace(chr(10),'<br>',str_replace(chr(13),'',$expressao)); } }

// =========================================================================
// Trata valores nulos
// -------------------------------------------------------------------------
function Nvl($expressao,$valor) { if ((!isset($expressao)) || $expressao==='') { return $valor; } else { return $expressao; } }

// =========================================================================
// Retorna valores nulos se chegar cadeia vazia
// -------------------------------------------------------------------------
function Tvl($expressao) { if (!isset($expressao) || $expressao==='') { return  null; } else { return $expressao; } }

// =========================================================================
// Retorna valores nulos se chegar cadeia vazia
// -------------------------------------------------------------------------
function Cvl($expressao) { if (!isset($expressao) || $expressao=='') { return 0; } else { return $expressao; } }

// =========================================================================
// Retorna o caminho físico para o diretório  do cliente informado
// -------------------------------------------------------------------------
function DiretorioCliente($p_cliente) {
  extract($GLOBALS);
  return $conFilePhysical.$p_cliente;
}

// =========================================================================
// Montagem de URL a partir da sigla da opção do menu
// -------------------------------------------------------------------------
function MontaURL($p_sigla) {
  extract($GLOBALS);
  $RS_MontaURL = db_getLinkData::getInstanceOf($dbms, $_SESSION['P_CLIENTE'], $p_sigla);
  $l_ImagemPadrao='images/folder/SheetLittle.gif';
  if (count($RS_MontaURL)<=0) return '';
  else {
    if (nvl(f($RS_MontaURL,'imagem'),'-')!='-') {
      $l_Imagem=f($RS_MontaURL,'imagem');
    } else {
      $l_Imagem=$l_ImagemPadrao;
    }
    return f($RS_MontaURL,'link')."&P1=".f($RS_MontaURL,'p1')."&P2=".f($RS_MontaURL,'p2')."&P3=".f($RS_MontaURL,'p3')."&P4=".f($RS_MontaURL,'p4')."&TP=<img src=".$l_Imagem." BORDER=0>".f($RS_MontaURL,'nome')."&SG=".f($RS_MontaURL,'sigla');
  }
  return $function_ret;
}

// =========================================================================
// Montagem de cabeçalho padrão de formulário
// -------------------------------------------------------------------------
function AbreForm($p_Name,$p_Action,$p_Method,$p_onSubmit,$p_Target,$p_P1,$p_P2,$p_P3,$p_P4,$p_TP,$p_SG,$p_R,$p_O) {
  if (!isset($p_Target)) {
     ShowHTML('<FORM action="'.$p_Action.'" method="'.$p_Method.'" name="'.$p_Name.'" onSubmit="'.$p_onSubmit.'">');
  } else {
     ShowHTML('<FORM action="'.$p_Action.'" method="'.$p_Method.'" name="'.$p_Name.'" onSubmit="'.$p_onSubmit.'" target="'.$p_Target.'">');
  }

  ShowHTML('<INPUT type="hidden" name="P1" value="'.$p_P1.'">');
  ShowHTML('<INPUT type="hidden" name="P2" value="'.$p_P2.'">');
  ShowHTML('<INPUT type="hidden" name="P3" value="'.$p_P3.'">');
  if (!!isset($p_P4)) {
     ShowHTML('<INPUT type="hidden" name="P4" value="'.$p_P4.'">');
  }

  ShowHTML('<INPUT type="hidden" name="TP" value="'.$p_TP.'">');
  ShowHTML('<INPUT type="hidden" name="SG" value="'.$p_SG.'">');
  ShowHTML('<INPUT type="hidden" name="R"  value="'.$p_R.'">');
  ShowHTML('<INPUT type="hidden" name="O"  value="'.$p_O.'">');
}

// =========================================================================
// Montagem de campo do tipo radio com padrão Não
// -------------------------------------------------------------------------
function MontaRadioNS($label,$chave,$campo) {
  extract($GLOBALS);
  ShowHTML('          <td>');
  if (Nvl($label,'')>'') { ShowHTML($label.'</b><br>'); }
  if ($chave=='S') {
     ShowHTML('              <input '.$w_Disabled.' type="radio" name="'.$campo.'" value="S" checked> Sim <input '.$w_Disabled.' type="radio" name="'.$campo.'" value="N"> Não');
  } else {
     ShowHTML('              <input '.$w_Disabled.' type="radio" name="'.$campo.'" value="S"> Sim <input '.$w_Disabled.' type="radio" name="'.$campo.'" value="N" checked> Não');
  }
}

// =========================================================================
// Montagem de campo do tipo radio com padrão Sim
// -------------------------------------------------------------------------
function MontaRadioSN($label,$chave,$campo) {
  extract($GLOBALS);
  ShowHTML('          <td>');
  if (Nvl($label,'')>'') { ShowHTML($label.'</b><br>'); }
  if ($chave=='N') {
     ShowHTML('              <input '.$w_Disabled.' type="radio" name="'.$campo.'" value="S"> Sim <input '.$w_Disabled.' type="radio" name="'.$campo.'" value="N" checked> Não');
  } else {
     ShowHTML('              <input '.$w_Disabled.' type="radio" name="'.$campo.'" value="S" checked> Sim <input '.$w_Disabled.' type="radio" name="'.$campo.'" value="N"> Não');
  }
}

// =========================================================================
// Retorna a prioridade a partir do código
// -------------------------------------------------------------------------
function RetornaPrioridade($p_chave) {
  switch (Nvl($p_chave,999)) {
  case 0:  return 'Alta';   break;
  case 1:  return 'Média';  break;
  case 2:  return 'Normal'; break;
  default: return '---';    break;
  }
}

// =========================================================================
// Retorna o tipo de visao a partir do código
// -------------------------------------------------------------------------
function RetornaTipoVisao($p_chave) {
  switch ($p_chave) {
    case 0: return 'Completa';  break;
    case 1: return 'Parcial';   break;
    case 2: return 'Resumida';  break;
    default:return 'Erro';      break;
  }
}

// =========================================================================
// Função que formata dias, horas, minutos e segundos a partir dos segundos
// -------------------------------------------------------------------------
function FormataTempo($p_segundos) {
  $l_horas=intval($p_segundos/3600);
  $l_minutos=intval(($p_segundos-($l_horas*3600))/60);
  $l_segundos=$p_segundos-($l_horas*3600)-($l_minutos*60);
  return substr(1000+$l_horas,1,3).":".substr(100+$l_minutos,1,2).":".substr(100+$l_segundos,1,2);
}

// =========================================================================
// Função que formata valores com separadores de milhar e decimais
// -------------------------------------------------------------------------
function FormatNumber($p_valor, $p_decimais) {
  return number_format($p_valor,$p_decimais,',','.');
}

// =========================================================================
// Função que retorna o código de tarifação telefônica do usuário logado
// -------------------------------------------------------------------------
function RetornaUsuarioCentral() {
  extract($GLOBALS);
  // Se receber o código do usuario do SIW, o usuário será determinado por parâmetro;
  // caso contrário, retornará o código do usuário logado.
  if ($_REQUEST['w_sq_usuario_central']>'') {
     return $_REQUEST['w_sq_usuario_central'];
  } else {
     $RS = db_getPersonData::getInstanceOf($dbms, $w_cliente, $w_usuario, null, null);
     return f($RS,'sq_usuario_central');
  }
}

// =========================================================================
// Função que retorna o código do usuário logado
// -------------------------------------------------------------------------
function RetornaUsuario() {
  extract($GLOBALS);
  // Se receber o código do usuario do SIW, o usuário será determinado por parâmetro;
  // caso contrário, retornará o código do usuário logado.
  if ($_REQUEST['w_usuario']>'') {
     return $_REQUEST['w_usuario'];
  } else {
     return $_SESSION['SQ_PESSOA'];
  }
}

// =========================================================================
// Função que retorna o ano a ser utilizado para recuperação de dados
// -------------------------------------------------------------------------
function RetornaAno() {
  extract($GLOBALS);
  if ($_REQUEST['w_ano']>'')     return $_REQUEST['w_ano'];
  elseif ($_SESSION['ANO'] > '') return $_SESSION['ANO'];
  else                           return Date('Y');
}

// =========================================================================
// Função que retorna o código do menu
// -------------------------------------------------------------------------
function RetornaMenu($p_cliente,$p_sigla) {
  extract($GLOBALS);
  // Se receber o código do menu do SIW, o código será determinado por parâmetro;
  // caso contrário, retornará o código retornado a partir da sigla.
  if ($_REQUEST['w_menu']>'') {
    return $_REQUEST['w_menu'];
  } else {
     $RS = db_getMenuCode::getInstanceOf($dbms, $p_cliente, $p_sigla);
     return f($RS,'SQ_MENU');
  }
}

// =========================================================================
// Função que retorna o código do cliente
// -------------------------------------------------------------------------
function RetornaCliente() {
  extract($GLOBALS);
  include_once($w_dir_volta.'classes/sp/db_getCompanyData.php');
  // Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
  // caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
  if ($_REQUEST['w_cgccpf']>'' && strlen($_REQUEST['w_cgccpf'])>11) {
     $RS = db_getCompanyData::getInstanceOf($dbms,$_SESSION['P_CLIENTE'], $_REQUEST['w_cgccpf']);
     if (count($RS) > 0) {
        return f($RS,'sq_pessoa');
     } else {
        return $_SESSION['P_CLIENTE'];
     }
  }
  elseif ($_REQUEST['w_cliente']>'') {
     return $_REQUEST['w_cliente'];
  }
  else {
     return $_SESSION['P_CLIENTE'];
  }
}

// =========================================================================
// Função que retorna S/N indicando se o usuário informado é gestor do sistema
// ou do módulo ao qual a solicitação pertence
// -------------------------------------------------------------------------
function RetornaGestor($p_solicitacao,$p_usuario) {
  include_once($w_dir_volta.'classes/sp/db_getGestor.php');
  extract($GLOBALS);
  $l_acesso = db_getGestor::getInstanceOf($dbms,$p_solicitacao, $p_usuario);
  return $l_acesso;
}

// =========================================================================
// Rotina que encerra a sessão e fecha a janela do SIW
// -------------------------------------------------------------------------
function EncerraSessao() {
  extract($GLOBALS);
  ScriptOpen('JavaScript');
  ShowHTML(' alert("Tempo máximo de inatividade atingido! Autentique-se novamente."); ');
  ShowHTML(' top.location.href=\'' . $conRootSIW . '\';');
  ScriptClose();
  exit();
}

// =========================================================================
// Função que formata um texto para exibição em HTML
// -------------------------------------------------------------------------
function ExibeTexto($p_texto) { return str_replace('  ','&nbsp;&nbsp;',str_replace('\r\n','<br>',$p_texto)); }

// =========================================================================
// Função que retorna a data/hora do banco
// -------------------------------------------------------------------------
function DataHora() { return diaSemana(date('l, d/m/Y, H:i:s')); }

// =========================================================================
// Função que retorna um timestamp da string informada
// date: string contendo a data no formato DD/MM/YYYY, HH24:MI:SS
// -------------------------------------------------------------------------
function toDate($date) { 
  if (strlen($date)!=20 && strlen($date)!=10) return nil;
  else {
    if (strlen($date)==10) return mktime(0,0,0,substr($date,3,2),substr($date,0,2),substr($date,6,4));
    else return mktime(substr($date,12,2),substr($date,15,2),substr($date,18,2),substr($date,3,2),substr($date,0,2),substr($date,6,4));
  }
}

// =========================================================================
// Função que retorna um valor da string informada
// valor: string contendo o valor
// -------------------------------------------------------------------------
function toNumber($valor) { return str_replace('.','',$valor); } 

// =========================================================================
// Função que retorna uma data como string para manipulação em formulários
// -------------------------------------------------------------------------
function FormatDateTime($date) { 
  if (strlen($date)!=8 && strlen($date)!=10) return null;
  else {
    if (strlen($date)==10) return $date;
    else {
      $l_date = substr($date,0,2).'/'.substr($date,3,2).'/';
      if (substr($date,6,2) < 30) $l_date = $l_date.'20'.substr($date,6,2);
      else                        $l_date = $l_date.'19'.substr($date,6,2);
    }
    return $l_date;
  }
}

// =========================================================================
// Função que adiciona dias a uma data
// date: timestamp gerado a partir da funçao toDate()
// inc:  inteiro precedido do sinal de adição ou subtração de dias (+1, -3 etc.)
// -------------------------------------------------------------------------
function addDays($date,$inc) { 
  return mktime(date(H,$date), date(i,$date), date(s,$date), date(m,$date), date(d,$date)+$inc, date(Y,$date));
}

// =========================================================================
// Função que transforma um array numa lista separada por vírgulas
// array: array de entrada
// -------------------------------------------------------------------------
function explodeArray($array) { 
  if (is_array($array)) {
    $lista = '';
    foreach ($array as $key => $val) $lista = $lista.','.trim($val);
    return substr($lista,1,strlen($lista)+1);
  } else {
    return $array;
  }
}

// =========================================================================
// Rotina que monta a máscara do beneficiário
// -------------------------------------------------------------------------
function MascaraBeneficiario($cgccpf) {
  // Se o campo tiver máscara, retira
  if ((strpos($cgccpf,'.') ? strpos($cgccpf,'.')+1 : 0)>0) {
     return str_replace('/','',str_replace('-','',str_replace('.','',$cgccpf)));
  } // Caso contrário, aplica a máscara, dependendo do tamanho do parâmetro
  elseif (strlen($cgccpf)==11) {
     return substr($cgccpf,0,3).'.'.substr($cgccpf,3,3).'.'.substr($cgccpf,6,3).'-'.substr($cgccpf,9,2);
  }
  elseif (strlen($cgccpf)==14) {
     return substr($cgccpf,0,2).'.'.substr($cgccpf,2,3).'.'.substr($cgccpf,5,3).'/'.substr($cgccpf,8,4).'-'.substr($cgccpf,12,2);
  }
}

// =========================================================================
// Rotina de envio de e-mail
// -------------------------------------------------------------------------
function EnviaMail($w_subject,$w_mensagem,$w_recipients,$w_attachments = null) {
  extract($GLOBALS);
  include_once('classes/mail/class_email.php');
  $_mail = new EMAIL();
  $_mail->setHeader('Content-Type','text/html; charset=iso-8859-1');
  $_mail->setDestino(str_ireplace(';',',',$w_recipients));
  $_mail->setAssunto($w_subject);
  $_mail->setMensagem($w_mensagem);
  return $_mail->enviar();
  //}
}

// =========================================================================
// Rotina de envio de e-mail
// -------------------------------------------------------------------------
function EnviaMailSender($w_subject,$w_mensagem,$w_recipients,$w_from,$w_from_name) {
  extract($GLOBALS);
  $w_recipients=$w_recipients.';';
  // $_mail is of type "JMail.Message"
  $_mail->Silent=true;
  $_mail->From=$w_from;
  $_mail->FromName=$w_from_name;
  if ($_SESSION['SIW_EMAIL_SENHA']>'') {
     $_mail->Logging=true;
     $_mail->MailServerUserName=$_SESSION['SIW_EMAIL_CONTA'];
     $_mail->MailServerPassWord=$_SESSION['SIW_EMAIL_SENHA'];
  }
  else {
     $_mail->Logging=false;
     $_mail->MailServerUserName=$_SESSION['SIW_EMAIL_CONTA'];
  }

  $_mail->Subject=$w_subject;
  $_mail->HtmlBody=$w_mensagem;
  $_mail->ClearRecipients();
  $i=0;
  while((strpos($w_recipients,';') ? strpos($w_recipients,';')+1 : 0)>0) {
    if (strlen($w_recipients)>2) { $Recipients[$i]=substr($w_recipients,0,(strpos($w_recipients,";") ? strpos($w_recipients,";")+1 : 0)-1); }
    $w_recipients=substr($w_recipients,(strpos($w_recipients,";") ? strpos($w_recipients,";")+1 : 0)+1-1,strlen($w_recipients));
    $i=$i+1;
  }
  for ($j=0; $j<=$i-1; $j=$j+1) {
    if ($j==0) // Se for o primeiro, coloca como destinatário principal. Caso contrário, coloca como CC.
       { $_mail->AddRecipient($Recipients[$j]); }
    else
       { $_mail->AddRecipientCC($Recipients[$j]); }
  }
  $_mail->Send($_SESSION['SMTP_SERVER']);
  $function_ret="";
  return $function_ret;
}


// =========================================================================
// Rotina que extrai a última parte da variável TP
// -------------------------------------------------------------------------
function RemoveTP($TP) {
  $w_TP=$TP;
  while(!(strpos($w_TP,'-')===false)) {
    $w_TP = substr($w_TP,strpos($w_TP,'-')+1,strlen($w_TP));
  }
  return str_replace(' -'.$w_TP,'',$TP);
}

// =========================================================================
// Rotina que extrai o nome de um arquivo, removendo o caminho
// -------------------------------------------------------------------------
function ExtractFileName($arquivo) {
  extract($GLOBALS);
  $fsa=$arquivo;
  while((strpos($fsa,"\\") ? strpos($fsa,"\\")+1 : 0)>0) {
    $fsa=substr($fsa,(strpos($fsa,"\\") ? strpos($fsa,"\\")+1 : 0)+1-1,strlen($fsa));
  }
  return $fsa;
}

// =========================================================================
// Rotina de deleção de arquivos em disco
// -------------------------------------------------------------------------
function DeleteAFile($filespec) {
  extract($GLOBALS);


$fso=$CreateObject['Scripting.FileSystemObject'];
$fso->DeleteFile($filespec);
return $function_ret;
}

// =========================================================================
// Rotina de tratamento de erros
// -------------------------------------------------------------------------
function TrataErro($sp, $Err, $params, $file, $line, $object) {
  extract($GLOBALS);

  if (!(strpos($Err['message'],'ORA-02292')===false) || !(strpos($Err['message'],'ORA-02292')===false) ) {
     // REGISTRO TEM FILHOS
     ScriptOpen('JavaScript');
     ShowHTML(' alert("Existem registros vinculados ao que você está excluindo. Exclua-os primeiro.\\n\\n'.substr($Err,0,(strpos($Err,chr(10)) ? strpos($Err,chr(10))+1 : 0)-1).'");');
     ShowHTML(' history.back(1);');
     ScriptClose();
  }
  elseif (!(strpos($Err['message'],'ORA-02291')===false) || !(strpos($Err['message'],'ORA-02291')===false)) {
     // REGISTRO NÃO ENCONTRADO
     ScriptOpen('JavaScript');
     ShowHTML(' alert("Registro não encontrado.");');
     ShowHTML(' history.back(1);');
     ScriptClose();
  }
  elseif (!(strpos($Err['message'],'ORA-00001')===false)) {
     // REGISTRO JÁ EXISTENTE
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Um dos campos digitados já existe no banco de dados e é único.\\n\\n'.substr($Err,0,(strpos($Err,chr(10)) ? strpos($Err,chr(10))+1 : 0)-1).'");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  }
  elseif (!(strpos($Err['message'],'ORA-03113')===false) ||
    !(strpos($Err['message'],'ORA-03113')===false) ||
    !(strpos($Err['message'],'ORA-03114')===false) ||
    !(strpos($Err['message'],'ORA-03114')===false) ||
    !(strpos($Err['message'],'ORA-12224')===false) ||
    !(strpos($Err['message'],'ORA-12224')===false) ||
    !(strpos($Err['message'],'ORA-12514')===false) ||
    !(strpos($Err['message'],'ORA-12514')===false) ||
    !(strpos($Err['message'],'ORA-12541')===false) ||
    !(strpos($Err['message'],'ORA-12541')===false) ||
    !(strpos($Err['message'],'ORA-12545')===false) ||
    !(strpos($Err['message'],'ORA-24327')===false) ||
    !(strpos($Err['message'],'ORA-12545')===false)) {

    ScriptOpen('JavaScript');
    ShowHTML(' alert("Banco de dados fora do ar. Aguarde alguns instantes e tente novamente!");');
    ShowHTML(' history.back(1);');
    ScriptClose();
  }
  else {
    $w_html='<html>';
    $w_html=$w_html.chr(10).'<head>';
    $w_html=$w_html.chr(10).'  <BASEFONT FACE="Arial" SIZE="2">';
    $w_html=$w_html.chr(10).'</head>';
    $w_html=$w_html.chr(10).'<body BGCOLOR="#FF5555" TEXT="#FFFFFF">';
    $w_html=$w_html.chr(10).'<CENTER><H2>ATENÇÃO</H2></CENTER>';
    $w_html=$w_html.chr(10).'<BLOCKQUOTE>';
    $w_html=$w_html.chr(10).'<P ALIGN="JUSTIFY">Erro não previsto. <b>Uma cópia desta tela foi enviada por e-mail para os responsáveis pela correção. Favor tentar novamente mais tarde.</P>';
    $w_html=$w_html.chr(10).'<TABLE BORDER="2" BGCOLOR="#FFCCCC" CELLPADDING="5"><TR><TD><FONT COLOR="#000000">';
    $w_html=$w_html.chr(10).'<DL><DT>Data e hora da ocorrência: <FONT FACE="courier">'.date('d/m/Y, h:i:s').'<br><br></font></DT>';
    $w_html=$w_html.chr(10).'<DT>Descrição:<DD><FONT FACE="courier">'.$Err['message'].'<br><br></font>';
    $w_html=$w_html.chr(10).'<DT>Arquivo:<DD><FONT FACE="courier">'.$file.', linha: '.$line.'<br><br></font>';
    //$w_html=$w_html.chr(10).'<DT>Objeto:<DD><FONT FACE="courier">'.$object.'<br><br></font>';

    $w_html=$w_html.chr(10).'<DT>Comando em execução:<blockquote> <FONT FACE="courier">'.$Err['sqltext'].'</blockquote></font></DT>';
    if (is_array($params)) {
      $w_html=$w_html."<DT>Valores dos parâmetros:<DD><FONT FACE=\"courier\" size=1>";
      foreach ($params as $w_Item) {
        $w_html=$w_html.chr(10).'['.$w_Item[0].']<br>';
      }
    }
    $w_html=$w_html."   <br><br></font>";

    $w_html=$w_html.chr(10).'<DT>Variáveis de servidor:<DD><FONT FACE="courier" size=1>';
    $w_html=$w_html.chr(10).' SCRIPT_NAME => ['.$_SERVER['SCRIPT_NAME'].']<br>';
    $w_html=$w_html.chr(10).' SERVER_NAME => ['.$_SERVER['SERVER_NAME'].']<br>';
    $w_html=$w_html.chr(10).' SERVER_PORT => ['.$_SERVER['SERVER_PORT'].']<br>';
    $w_html=$w_html.chr(10).' SERVER_PROTOCOL => ['.$_SERVER['SERVER_PROTOCOL'].']<br>';
    $w_html=$w_html.chr(10).' HTTP_ACCEPT_LANGUAGE => ['.$_SERVER['HTTP_ACCEPT_LANGUAGE'].']<br>';
    $w_html=$w_html.chr(10).' HTTP_USER_AGENT => ['.$_SERVER['HTTP_USER_AGENT'].']<br>';
    $w_html=$w_html.chr(10).'</DT>';
    $w_html=$w_html.chr(10).'   <br><br></font>';

    $w_html=$w_html.chr(10).'<DT>Dados da querystring:';
    foreach($_GET as $chv => $vlr) { $w_html=$w_html.chr(10).'<DD><FONT FACE="courier" size=1>'.$chv.' => ['.$vlr.']<br>'; }

    $w_html=$w_html.chr(10).'</DT>';
    $w_html=$w_html.chr(10).'<DT>Dados do formulário:';
    foreach($_POST as $chv => $vlr) { $w_html=$w_html.chr(10).'<DD><FONT FACE="courier" size=1>'.$chv.' => ['.$vlr.']<br>'; }

    $w_html=$w_html.chr(10).'</DT>';
    $w_html=$w_html.chr(10).'   <br><br></font>';
    $w_html=$w_html.chr(10).'</DT>';
    $w_html=$w_html.chr(10).'<DT>Variáveis de sessão:<DD><FONT FACE="courier" size=1>';
    foreach($_SESSION as $chv => $vlr) { if (strpos(strtoupper($chv),'SENHA') !== true) { $w_html=$w_html.chr(10).$chv.' => ['.$vlr.']<br>'; } }
    $w_html=$w_html.chr(10).'</DT>';
    $w_html=$w_html.chr(10).'   <br><br></font>';
    $w_html=$w_html.chr(10).'</FONT></TD></TR></TABLE><BLOCKQUOTE>';
    $w_html=$w_html.'</body></html>';

    $w_resultado = EnviaMail('ERRO SIW',$w_html,'alex@sbpi.com.br; celso@sbpi.com.br');
    if ($w_resultado>'') {
       ShowHTML('<SCRIPT LANGUAGE="JAVASCRIPT">');
       ShowHTML('   alert("Não foi possível enviar o e-mail comunicando sobre o erro. Favor copiar esta página e enviá-la por e-mail aos gestores do sistema.");');
       ShowHTML('</SCRIPT>');
    }

    ShowHTML($w_html);
  }
  exit;
}
// =========================================================================
// Fim da rotina de tratamento de erros
// -------------------------------------------------------------------------

// =========================================================================
// Rotina de cabeçalho
// -------------------------------------------------------------------------
function Cabecalho() {
  extract($GLOBALS);
  if ($_SESSION['P_CLIENTE']==6761) {
    ShowHTML('<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3c.org/TR/1999/REC-html401-19991224/loose.dtd">');
    ShowHTML('<HTML xmlns="http://www.w3.org/1999/xhtml">');
  }
  else { ShowHTML('<HTML>'); }
}

// =========================================================================
// Rotina de rodapé
// -------------------------------------------------------------------------
function Rodape() {
  extract($GLOBALS);
  if ($_SESSION['P_CLIENTE']==6761) {
    ShowHTML('</center>');
    ShowHTML('<center>');
    ShowHTML('<DIV id=rodape>');
    ShowHTML('  <DIV id=endereco>');
    ShowHTML('    <P>Setor Comercial Sul, Ed. Denasa - Salas 901/902 - Brasília-DF <BR>Tel : (61) 225 6302 (61) 321 8938 | Fax (61) 225 7599| email: <A href="mailto:pbf@cespe.unb.br">bresil2005@minc.gov.br</A>');
    ShowHTML('    </P>');
    ShowHTML('  </DIV>');
    ShowHTML('</DIV>');
    ShowHTML('</center>');
  }
  else { ShowHTML('<HR>'); }
  ShowHTML('</BODY>');
  ShowHTML('</HTML>');
}

// =========================================================================
// Montagem da estrutura do documento
// -------------------------------------------------------------------------
function Estrutura_Topo() {
  extract($GLOBALS);
  if ($_SESSION['P_CLIENTE']==6761) {
    ShowHTML('<DIV id=container>');
    ShowHTML('  <DIV id=cab>');
  }
}

// =========================================================================
// Definição dos arquivos de CSS
// -------------------------------------------------------------------------
function Estrutura_CSS($l_cliente) {
  extract($GLOBALS);
  if ($l_cliente==6761) {
    ShowHTML('<LINK  media=screen href="/siw/files/'.$l_cliente.'/css/estilo.css" type=text/css rel=stylesheet>');
    ShowHTML('<LINK media=print href="/siw/files/'.$l_cliente.'/css/print.css" type=text/css rel=stylesheet>');
    ShowHTML('<SCRIPT language=javascript src="/siw/files/'.$l_cliente.'/js/scripts.js" type=text/javascript> ');
    ShowHTML('</SCRIPT>');
  }
}

// =========================================================================
// Montagem da estrutura do documento
// -------------------------------------------------------------------------

function Estrutura_Topo_Limpo() {
  extract($GLOBALS);
  if ($_SESSION['P_CLIENTE']==6761) {
     ShowHTML('<center>');
     ShowHTML('<DIV id=container_limpo>');
     ShowHTML('  <DIV id=cab>');
  }
}

// =========================================================================
// Montagem do corpo do documento
// -------------------------------------------------------------------------
function Estrutura_Fecha() {
  extract($GLOBALS);
  if ($_SESSION['P_CLIENTE']==6761) {
     ShowHTML('  </DIV>');
  }
}

// =========================================================================
// Montagem do corpo do documento
// -------------------------------------------------------------------------
function Estrutura_Corpo_Abre() {
  extract($GLOBALS);
  if ($_SESSION['P_CLIENTE']==6761) {
     ShowHTML('  <DIV id=corpo>');
  }
}

// =========================================================================
// Montagem do texto do corpo
// -------------------------------------------------------------------------
function Estrutura_Texto_Abre() {
  extract($GLOBALS);
  if ($_SESSION['P_CLIENTE']==6761) {
     ShowHTML('    <DIV id=texto>');
     ShowHTML('        <DIV class=retranca>'.$TP.'</DIV>');
  } else {
     ShowHTML('<B><FONT COLOR="#000000">'.$w_TP.'</FONT></B>');
     ShowHTML('<HR>');
     ShowHTML('<div align=center><center>');
  }
}

// =========================================================================
// Encerramento do texto do corpo
// -------------------------------------------------------------------------
function Estrutura_Texto_Fecha() {
  extract($GLOBALS);
  if ($_SESSION['P_CLIENTE']==6761) {
     ShowHTML('    </center>');
     ShowHTML('    </DIV>');
  }
}

// =========================================================================
// Montagem da estrutura do menu à esquerda
// -------------------------------------------------------------------------
function Estrutura_Menu_Esquerda() {
  extract($GLOBALS);
  if ($_SESSION['P_CLIENTE']==6761) {
     ShowHTML('    <DIV id=menuesq>');
  }
}

// =========================================================================
// Montagem da estrutura do menu à esquerda
// -------------------------------------------------------------------------
function Estrutura_Menu_Direita() {
  extract($GLOBALS);
  if ($_SESSION['P_CLIENTE']==6761) {
     ShowHTML('    <DIV id=menudir>');
  }
}

// =========================================================================
// Montagem do menu à esquerda
// -------------------------------------------------------------------------
function Estrutura_Menu_Separador() {
  extract($GLOBALS);
  if ($_SESSION['P_CLIENTE']==6761) {
     ShowHTML('      <DIV id=menusep><HR></DIV>');
  }
}

// =========================================================================
// Montagem do menu à esquerda
// -------------------------------------------------------------------------
function Estrutura_Menu_Gov_Abre() {
  extract($GLOBALS);
  if ($_SESSION['P_CLIENTE']==6761) {
     Estrutura_Menu_Separador();
     ShowHTML('      <UL id=menugov>');
  }
}

// =========================================================================
// Montagem do menu à esquerda
// -------------------------------------------------------------------------
function Estrutura_Menu_Nav_Abre() {
  extract($GLOBALS);
  if ($_SESSION['P_CLIENTE']==6761) {
     Estrutura_Menu_Separador();
     ShowHTML('      <DIV id=menunav>');
     ShowHTML('        <UL>');
  }
}

// =========================================================================
// Montagem do menu à esquerda
// -------------------------------------------------------------------------
function Estrutura_Menu_Fecha() {
  extract($GLOBALS);
  if ($_SESSION['P_CLIENTE']==6761) {
     ShowHTML('      </UL>');
  }
}

// =========================================================================
// Montagem do sub-menu à esquerda alternativo
// -------------------------------------------------------------------------
function Estrutura_Corpo_Menu_Esquerda() {
  extract($GLOBALS);
  if ($_SESSION['P_CLIENTE']==6761) {
     ShowHTML('    <DIV id=menuesq>');
     ShowHTML('      <DIV id=logomenuesq><H3>BresilBresils</H3></DIV>');
  }
}

// =========================================================================
// Montagem da estrutura do documento
// -------------------------------------------------------------------------
function Estrutura_Menu() {
  extract($GLOBALS);
  if ($_SESSION['P_CLIENTE']==6761) {
     ShowHTML('    <DIV id=cabtopo>');
     ShowHTML('      <DIV id=logoesq>');
     ShowHTML('        <H1>Ministério da Cultura</H1>');
     ShowHTML('        <br>');
     ShowHTML('        <select name="opcoes" onChange="if(options[selectedIndex].value) window.location.href= (options[selectedIndex].value)" class="pr">');
     ShowHTML('          <option>Destaques do governo</option>');
     ShowHTML('          <option value="javascript:nova_jan("http://www.brasil.gov.br")">Portal do Governo Federal</option>');
     ShowHTML('          <option value="javascript:nova_jan("http://www.e.gov.br")">Portal de Servi&ccedil;os do Governo</option>');
     ShowHTML('          <option value="javascript:nova_jan("http://www.radiobras.gov.br")">Portal da Ag&ecirc;ncia de Not&iacute;cias</option>');
     ShowHTML('          <option value="javascript:nova_jan("http://www.brasil.gov.br/emquestao")">Em Questão</option>');
     ShowHTML('          <option value="javascript:nova_jan("http://www.fomezero.gov.br")">Programa Fome Zero</option>');
     ShowHTML('        </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;');
     ShowHTML('      </DIV>');
     ShowHTML('      <DIV id=logodir><H2>Projeto Ano do Brasil na França</H2></DIV>');
     ShowHTML('    </DIV>');
     ShowHTML('');
     ShowHTML('    <DIV id=menutxt>');
     ShowHTML('      <SCRIPT src="/siw/files/'.$w_cliente.'/js/newcssmenu.js" type=text/javascript></SCRIPT>');
     ShowHTML('      ');
     ShowHTML('      <DIV id=menutexto>');
     ShowHTML('        <DIV id=mainMenu>');
     ShowHTML('          <UL id=menuList>');
     $l_cont=0;
     $l_RS = db_getLinkDataUser::getInstanceOf($dbms,$_SESSION['P_CLIENTE'], $_SESSION['SQ_PESSOA'], null);
     $l_cont=0;
     foreach($l_RS as $row) {
       $l_titulo=f(row,'nome');
       if (f(row,'filho')>0) {
         $l_cont=$l_cont+1;
         ShowHTML('            <LI class=menubar>::<A class=starter href="#"> '.f(row,'nome').'</A>');
         ShowHTML('            <UL class=menu id=menu'.$l_cont.'>');
         $l_cont1=0;
         $l_RS1 = db_getLinkDataUser::getInstanceOf($dbms,$_SESSION['P_CLIENTE'], $_SESSION['SQ_PESSOA'], f(row,'sq_menu'));
         foreach($l_RS1 as $row1) {
           $l_titulo=$l_titulo.' - '.f(row1,'nome');
           if (f(row1,'filho')>0) {
              $l_cont1=$l_cont1+1;
              ShowHTML('              <LI><A href="#"><IMG height=12 alt=">" src="/siw/files/'.$w_cliente.'/img/arrows.gif" width=8> '.f(row1,'nome').'</A> ');
              ShowHTML('              <UL class=menu id=menu'.$l_cont.'_'.$l_cont1.'>');
              $l_cont2=0;
              $l_RS2 = db_getLinkDataUser::getInstanceOf($dbms,$_SESSION['P_CLIENTE'], $_SESSION['SQ_PESSOA'], f(row1,'sq_menu'));
              foreach($l_RS2 as $row2) {
                $l_titulo=$l_titulo.' - '.f(row2,'nome');
                if (f(row2,'filho')>0) {
                   $l_cont2=$l_cont2+1;
                   ShowHTML('                <LI><A href="#"><IMG height=12 alt=">" src="/siw/files/'.$w_cliente.'/img/arrows.gif" width=8> '.f(row2,'nome').'</A> ');
                   ShowHTML('                <UL class=menu id=menu'.$l_cont.'_'.$l_cont1.'_'.$l_cont2.'>');
                   $l_RS3 = db_getLinkDataUser::getInstanceOf($dbms,$_SESSION['P_CLIENTE'], $_SESSION['SQ_PESSOA'], f(row2,'sq_menu'));
                   foreach($l_RS3 as $row3) {
                     $l_titulo=$l_titulo.' - '.f(row3,'nome');
                     if (f(row3,'externo')=='S') {
                       ShowHTML('                  <LI><A href="'.str_replace('@files',$conFileVirtual.$_SESSION['P_CLIENTE'],f(row3,'link')).'" TARGET="'.f(row3,'target').'">'.f(row3,'nome').'</A> ');
                     } else {
                       ShowHTML('                  <LI><A href="'.f(row3,'link').'&P1='.f(row3,'p1').'&P2='.f(row3,'p2').'&P3='.f(row3,'p3').'&P4='.f(row3,'p4').'&TP='.$l_titulo.'&SG='.f(row3,'sigla').'">'.f(row3,'nome').'</A> ');
                     }
                     $l_titulo=str_replace(' - '.f(row3,'nome'),'',$l_titulo);
                   }
                   ShowHTML('            </UL>');
                } else {
                   if (f(row2,'externo')=='S') {
                      ShowHTML('                <LI><A href="'.str_replace('@files',$conFileVirtual.$_SESSION['P_CLIENTE'],f(row2,'link')).'" TARGET="'.f(row2,'target').'">'.f(row2,'nome').'</A> ');
                   } else {
                      ShowHTML('                <LI><A href="'.f(row2,'link').'&P1='.f(row2,'p1').'&P2='.f(row2,'p2').'&P3='.f(row2,'p3').'&P4='.f(row2,'p4').'&TP='.$l_titulo.'&SG='.f(row2,'sigla').'">'.f(row2,'nome').'</A> ');
                   }
                }
                $l_titulo=str_replace(' - '.f(row2,'nome'),'',$l_titulo);
              }
              ShowHTML('            </UL>');
           } else {
              if (f(row1,'externo')=='S') {
                 if (f(row1,'link')>'') {
                    ShowHTML('              <LI><A href="'.str_replace('@files',$conFileVirtual.$_SESSION['P_CLIENTE'],f(row1,'link')).'" TARGET="'.f(row1,'target').'">'.f(row1,'nome').'</A> ');
                 } else {
                   ShowHTML('              <LI>'.f(row1,'nome').' ');
                 }
              } else {
                 ShowHTML('              <LI><A href="'.f(row1,'link').'&P1='.f(row1,'p1').'&P2='.f(row1,'p2').'&P3='.f(row1,'p3').'&P4='.f(row1,'p4').'&TP='.$l_titulo.'&SG='.f(row1,'sigla').'">'.f(row1,'nome').'</A> ');
              }
           }
           $l_titulo=str_replace(' - '.f(row1,'nome'),'',$l_titulo);
         }
         ShowHTML('            </UL>');
       } else {
          if (f(row,'externo')=='S') {
             ShowHTML('            <LI class=menubar>::<A class=starter href="'.str_replace('@files',$conFileVirtual.$_SESSION['P_CLIENTE'],f(row,'link')).'" TARGET="'.f(row,'target').'"> '.f(row,'nome').'</A>');
          } else {
            ShowHTML('            <LI class=menubar>::<A class=starter href="'.f(row,'link').'&P1='.f(row,'p1').'&P2='.f(row,'p2').'&P3='.f(row,'p3').'&P4='.f(row,'p4').'&TP='.$l_titulo.'&SG='.f(row,'sigla').'"> '.f(row,'nome').'</A>');
          }
       }
     }
     ShowHTML('            <LI class=menubar>::<A class=starter href="'.$w_dir.'menu.php?par=Sair" & " onClick="return(confirm("Confirma saída do sistema?"));"> Sair</A>');
     ShowHTML('          </UL>');
     ShowHTML('        </DIV>');
     ShowHTML('      </DIV>');
     ShowHTML('    </DIV>');
  }
}

// =========================================================================
// Abre conexão com o banco de dados
// -------------------------------------------------------------------------
function abreSessao() {
  return abreSessao::getInstanceOf($_SESSION["DBMS"]);
}

// =========================================================================
// Fecha conexão com o banco de dados
// -------------------------------------------------------------------------
function FechaSessao($dbms) {
  extract($GLOBALS);
  unset($objConnectionClass);
}

// =========================================================================
// Rotina de Fechamento do BD
// -------------------------------------------------------------------------
function DesConectaBD() {
  return true;
}

// -------------------------------------------------------------------------
// Torna a chamada a um campo de recordset case insensitive
// =========================================================================
function f($rs, $fld) {
  if     (isset($rs[Strtolower($fld)])) return str_replace('.asp','.php',$rs[Strtolower($fld)]);
  elseif (isset($rs[Strtoupper($fld)])) return str_replace('.asp','.php',$rs[Strtoupper($fld)]);
  elseif (isset($rs[$fld]))             return str_replace('.asp','.php',$rs[$fld]);
  else                                  return null;
}

// -------------------------------------------------------------------------
// Verifica se a senha de acesso do usuário está correta
// =========================================================================
function VerificaSenhaAcesso($Usuario,$Senha) {
   extract($GLOBALS);
   if (db_verificaSenha::getInstanceOf($dbms, $_SESSION["P_CLIENTE"],$Usuario,$Senha) ==0)
      return true;
    else
      return false;
}

// -------------------------------------------------------------------------
// Verifica se a Assinatura Eletronica do usuário está correta
// =========================================================================
function VerificaAssinaturaEletronica($Usuario,$Senha) {
   extract($GLOBALS);
   if ($Senha>'') {
      if (db_verificaAssinatura::getInstanceOf($dbms, $_SESSION["P_CLIENTE"],$Usuario,$Senha)==0)
         return true;
       else
         return false;
   } else {
     return true;
   }
}

// =========================================================================
// Função que formata dias, horas, minutos e segundos a partir dos segundos
// -------------------------------------------------------------------------
function FormataDataEdicao($w_dt_grade, $w_formato=1) { 
  if (nvl($w_dt_grade,'')>'') {
    if (is_numeric($w_dt_grade)) {
      switch ($w_formato){
        case 1: return date('d/m/Y',$w_dt_grade);                         break;
        case 2: return date('H:i:s',$w_dt_grade);                         break;
        case 3: return date('d/m/Y, H:i:s',$w_dt_grade);                  break;
        case 4: return diaSemana(date('l, d/m/Y, H:i:s',$w_dt_grade));    break;
      }
    } else {
      return $w_dt_grade;
    }
  } else {
    return null;
  }
}

// =========================================================================
// Função que retorna o primeiro dia da data informada
// -------------------------------------------------------------------------
function first_day($w_valor) {
  extract($GLOBALS);

  $l_valor  = FormataDataEdicao($w_valor);
  $l_mes    = substr($l_valor,3,2);
  $l_ano    = substr($l_valor,6,4);
  return mktime(0,0,0,$l_mes,1,$l_ano);
} 

// =========================================================================
// Função que retorna o último dia da data informada
// -------------------------------------------------------------------------
function Last_Day($w_valor) {
  extract($GLOBALS);

  $l_valor  = FormataDataEdicao($w_valor);
  $l_dia    = substr($l_valor,0,2);
  $l_mes    = substr($l_valor,3,2);
  $l_ano    = substr($l_valor,6,4);
  
  $l_result = mktime(0,0,0,($l_mes + 1),0,$l_ano);

  return $l_result;
} 

// =========================================================================
// Função que retorna data indicando o domingo de páscoa de um ano
// -------------------------------------------------------------------------
function DomingoPascoa($p_ano) {
  extract($GLOBALS);

  $a = intval($p_ano%19);
  $b = intval($p_ano/100);
  $c = intval($p_ano%100);
  $d = intval($b/4);
  $e = intval($b%4);
  $f = intval(($b+8)/25);
  $g = intval(($b-$f+1)/3);
  $h = intval(((19*$a)+$b-$d-$g+15)%30);
  $i = intval($c/4);
  $k = intval($c%4);
  $l = intval((32+(2*$e)+(2*$i)-$h-$k)%7);
  $m = intval(($a+(11*$h)+(22*$l))/451);
  $p = intval(($h+$l-(7*$m)+114)/31);
  $q = intval(($h+$l-(7*$m)+114)%31);
  return mktime(0,0,0,$p,$q+1,$p_ano);
} 

// =========================================================================
// Função que retorna data indicando a sexta-feira santa de um ano
// Sexta-feira Santa é 2 dias antes do Domingo de Páscoa
// -------------------------------------------------------------------------
function SextaSanta($p_ano) {
  extract($GLOBALS);

  return addDays(DomingoPascoa($p_ano),-2);
} 

// =========================================================================
// Função que retorna data de Corpus Christi de um ano
// Corpus Chirsti é 60 dias depois do Domingo de Páscoa
// -------------------------------------------------------------------------
function CorpusChristi($p_ano) {
  extract($GLOBALS);

  return addDays(DomingoPascoa($p_ano),60);
} 

// =========================================================================
// Função que retorna data indicando a terça-feira de carnaval de um ano
// Terça-feira de carnaval é a primeira terça-feira 42 dias antes do domingo
// de páscoa
// -------------------------------------------------------------------------
function TercaCarnaval($p_ano) {
  extract($GLOBALS);

  $l_dia = addDays(DomingoPascoa($p_ano),-42);
  if (date('w',$l_dia)>2) {
    return addDays($l_dia,(-1*date('w',addDays($l_dia,-2))));
  } else {
    return addDays($l_dia,(-1*date('w',addDays($l_dia,-4))));
  } 
} 

// =========================================================================
// Função que traduz os dias da semana de inglês para português
// -------------------------------------------------------------------------
function diaSemana($l_data) {
  if (nvl($l_data,'')>'') {
    $l_texto = substr($l_data,strpos($l_data,',')); 
    switch (strtoupper(substr($l_data,0,strpos($l_data,',')))) {
      case 'SUNDAY':    return 'Domingo'.$l_texto;       break;
      case 'MONDAY':    return 'Segunda-feira'.$l_texto; break;
      case 'TUESDAY':   return 'Terça-feira'.$l_texto;   break;
      case 'WEDNESDAY': return 'Quarta-feira'.$l_texto;  break;
      case 'THURSDAY':  return 'Quinta-feira'.$l_texto;  break;
      case 'FRIDAY':    return 'Sexta-feira'.$l_texto;   break;
      case 'SATURDAY':  return 'Sábado'.$l_texto;        break;
    }
  } else {
    return null;
  }
}

// =========================================================================
// Função para retornar a strig 'Sim' ou 'Não'
// -------------------------------------------------------------------------
function retornaSimNao($chave) {
  extract($GLOBALS);
  switch ($chave) {
    case 'S': return 'Sim';  break;
    case 'N': return 'Não';  break;
    default:  return 'Não';
  }
}

//Limpa Mascara para gravar os dados no banco de dados
function LimpaMascara($Campo) {
   return str_replace(str_replace(str_replace(str_replace(str_replace(str_replace($campo,',',''),';',''),'.',''),'-',''),'/',''),'"','');
}

// Cria a tag Body
function BodyOpen($cProperties) {
   extract($GLOBALS);
   ShowHTML('<link rel="stylesheet" type="text/css" href="'.$conRootSIW.'classes/menu/xPandMenu.css">');
   if ($_SESSION['P_CLIENTE']=='6761') { ShowHTML('<body Text="'.$conBodyText.'" '.$cProperties.'> '); }
   else {
      ShowHTML('<body Text="'.$conBodyText.'" Link="'.$conBodyLink.'" Alink="'.$conBodyALink.'" '.
            'Vlink="'.$conBodyVLink.'" Bgcolor="'.$conBodyBgColor.'" Background="'.$conBodyBackground.'" ' .
            'Bgproperties="'.$conBodyBgproperties.'" Topmargin="'.$conBodyTopmargin .'" ' .
            'Leftmargin="'.$conBodyLeftmargin.'" '.$cProperties.'> ');
   }
}

function BodyOpenImage($cProperties, $cImage, $cFixed) {
   extract($GLOBALS);
   ShowHTML('<link rel="stylesheet" type="text/css" href="'.$conRootSIW.'classes/menu/xPandMenu.css">');
   if ($_SESSION['P_CLIENTE']=='6761') { ShowHTML('<body Text="'.$conBodyText.'" '.$cProperties.'> '); }
   else {
      ShowHTML('<body Text="'.$conBodyText.'" Link="'.$conBodyLink.'" Alink="'.$conBodyALink.'" '.
            'Vlink="'.$conBodyVLink.'" Bgcolor="'.$conBodyBgcolor.'" Background="'.$cImage.'" ' .
            'Bgproperties="'.$cFixed.'" Topmargin="'.$conBodyTopmargin .'" ' .
            'Leftmargin="'.$conBodyLeftmargin.'" '.$cProperties.'> ');
   }
}

// Imprime uma linha HTML
function ShowHtml($Line) { print $Line.chr(13).chr(10); }

// Cria a tag Body
function BodyOpenClean($cProperties) {
  extract($GLOBALS);
  ShowHTML('<link rel="stylesheet" type="text/css" href="'.$conRootSIW.'classes/menu/xPandMenu.css">');
  ShowHTML('<body Text="'.$conBodyText.'" Link="'.$conBodyLink.'" Alink="'.$conBodyALink.'" '.
  'Vlink="'.$conBodyVLink.'" Background="'.$conBodyBackground.'" '.
  'Bgproperties="'.$conBodyBgproperties.'" Topmargin="'.$conBodyTopmargin.'" '.
  'Leftmargin="'.$conBodyLeftmargin.'" '.$cProperties.'> ');
}

// Cria a tag Body
function BodyOpenMail($cProperties=null) {
  extract($GLOBALS);
  $l_html='';
  $l_html=$l_html.'<link rel="stylesheet" type="text/css" href="'.$conRootSIW.'classes/menu/xPandMenu.css">'.chr(13);
  $l_html=$l_html.'<body Text="'.$conBodyText.'" Link="'.$conBodyLink.'" Alink="'.$conBodyALink.'" '.
    'Vlink="'.$conBodyVLink.'" Bgcolor="'.$conBodyBgcolor.'" Background="'.$conBodyBackground.'" '.
    'Bgproperties="'.$conBodyBgproperties.'" Topmargin="'.$conBodyTopmargin.'" '.
    'Leftmargin="'.$conBodyLeftmargin.'" '.$cProperties.'> '.chr(13);
  return $l_html;
}
?>