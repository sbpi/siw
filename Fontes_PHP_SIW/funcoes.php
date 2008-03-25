<?
setlocale(LC_ALL, 'pt_BR');
mb_language('en');
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
  return '   <a class="ss" href="#" onClick="window.open(\''.$conRootSIW.'calendario.php?nmForm='.$form.'&nmCampo='.$campo.'&vData=\'+document.'.$form.'.'.$campo.'.value,\'dp\',\'toolbar=0,location=0,directories=0,status=0,menubar=0,scrollbars=0,resizable=0,width=250,height=250,left=500,top=200\'); return false;" title="Visualizar calendário"><img src="images/icone/GotoTop.gif" border=0 align=top height=13 width=15></a>';
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
// Gravação da imagem da solicitação no log
// -------------------------------------------------------------------------
function CriaBaseLine($l_chave,$l_html,$l_nome,$l_tramite) {
  extract($GLOBALS);
  include_once($w_dir_volta.'classes/sp/dml_putBaseLine.php');
  $l_caminho  = $conFilePhysical.$w_cliente.'/';
  $l_nome_arq = $l_chave.'_'.time().'.html';
  $l_arquivo  = $l_caminho.$l_nome_arq;
  // Abre o arquivo de log
  $l_arq = @fopen($l_arquivo, 'w');
  fwrite($l_arq,'<HTML>');
  fwrite($l_arq,'<HEAD>');
  fwrite($l_arq,'<TITLE>Visualização de '.$l_nome.'</TITLE>');
  fwrite($l_arq,'</HEAD>');
  fwrite($l_arq,'<BASE HREF="'.$conRootSIW.'">');
  fwrite($l_arq,'<link rel="stylesheet" type="text/css" href="'.$conRootSIW.'classes/menu/xPandMenu.css">');
  fwrite($l_arq,'<BODY>');
  fwrite($l_arq,'<div align="center">');
  fwrite($l_arq,'<table width="95%" border="0" cellspacing="3">');
  fwrite($l_arq,'<tr><td colspan="2">');
  fwrite($l_arq,$l_html);
  fwrite($l_arq,'</table>');
  fwrite($l_arq,'</div>');
  fwrite($l_arq,'</BODY>');
  fwrite($l_arq,'</HTML>');
  @fclose($l_arq);
  dml_putBaseLine::getInstanceOf($dbms,$w_cliente,$l_chave,$w_usuario,$l_tramite,$l_nome_arq,filesize($l_arquivo),'text/html',$l_nome_arq);
}

// =========================================================================
// Gera um link para JavaScript, em função do navegador
// -------------------------------------------------------------------------
function montaURL_JS ($p_dir, $p_link) { 
  extract($GLOBALS);
  $l_link = str_replace($conRootSIW,'',$p_link);
  if (nvl($p_dir,'')!='') $l_link = str_replace($p_dir,'',$l_link);
  return $conRootSIW.$p_dir.$l_link;
}

// =========================================================================
// Gera código de barras para o valor informado
// -------------------------------------------------------------------------
function geraCB ($l_valor, $l_tamanho=6, $l_fator=0.6, $l_formato='C39') { 
  extract($GLOBALS);
  if (strtoUpper($l_formato)=='C39') {
    include_once($w_dir_volta.'classes/graph_barcode/C39Barcode_class.php');
    $cb = new c39Barcode('cb1',$l_valor);
 } else {
    include_once($w_dir_volta.'/classes/graph_barcode/I25Barcode_class.php');
    $cb = new I25Barcode('cb1',$l_valor);
  }
  $cb->setFactor($l_fator);  // Fator de aumento. Quanto maior, mais larga é cada barra do código.
  return '<font size='.intVal($l_tamanho).'">'.$cb->getBarcode().'</font>';
}

// =========================================================================
// Declaração inicial para páginas OLE com Word
// -------------------------------------------------------------------------
function headerWord($p_orientation='LANDSCAPE') {
  extract($GLOBALS);
  header('Content-type: application/msword',false);
  header('Content-Disposition: attachment; filename=arquivo.doc');
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
  //ShowHTML('  <w:DocumentProtection>forms</w:DocumentProtection> ');
  ShowHTML(' </w:WordDocument> ');
  ShowHTML('</xml><![endif]--> ');
  ShowHTML('<style> ');
  ShowHTML('<!-- ');
  ShowHTML(' /* Style Definitions */ ');
  ShowHTML('@page Section1 ');
  if (strtoupper(Nvl($p_orientation,'LANDSCAPE'))=='PORTRAIT') {
     ShowHTML('    {size:8.5in 11.0in; ');
     ShowHTML('    mso-page-orientation:portrait; ');
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
  BodyOpen('onLoad=this.focus();');
  ShowHTML('<div class=Section1> ');
  ShowHTML('<link rel="stylesheet" type="text/css" href="'.$conRootSIW.'classes/menu/xPandMenu.css">');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
}
// =========================================================================
// Declaração inicial para páginas OLE com Word
// -------------------------------------------------------------------------
function headerExcel($p_orientation='LANDSCAPE') {
  extract($GLOBALS);
  header('Content-type: application/excel',false);
  header('Content-Disposition: attachment; filename=arquivo.xls');
  ShowHTML('<html xmlns:o="urn:schemas-microsoft-com:office:office" ');
  ShowHTML('xmlns:w="urn:schemas-microsoft-com:office:excel" ');
  ShowHTML('xmlns="http://www.w3.org/TR/REC-html40"> ');
  ShowHTML('<head> ');
  ShowHTML('<meta http-equiv=Content-Type content="text/html; charset=windows-1252"> ');
  ShowHTML('<meta name=ProgId content=Pdf.Document> ');
  ShowHTML('<!--[if gte mso 9]><xml> ');
  ShowHTML(' <w:ExcelDocument> ');
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
  //ShowHTML('  <w:DocumentProtection>forms</w:DocumentProtection> ');
  ShowHTML(' </w:ExcelDocument> ');
  ShowHTML('</xml><![endif]--> ');
  ShowHTML('<style> ');
  ShowHTML('<!-- ');
  ShowHTML(' /* Style Definitions */ ');
  ShowHTML('@page Section1 ');
  if (strtoupper(Nvl($p_orientation,'LANDSCAPE'))=='PORTRAIT') {
     ShowHTML('    {size:8.5in 11.0in; ');
     ShowHTML('    mso-page-orientation:portrait; ');
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
  BodyOpen('onLoad=this.focus();');
  ShowHTML('<div class=Section1> ');
  ShowHTML('<link rel="stylesheet" type="text/css" href="'.$conRootSIW.'classes/menu/xPandMenu.css">');
  ShowHTML('<BASE HREF="'.$conRootSIW.'">');
}

// =========================================================================
// Montagem do cabeçalho de documentos Word
// -------------------------------------------------------------------------
function CabecalhoWord($p_cliente,$p_titulo,$p_pagina) {
  extract($GLOBALS);
  include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
  $l_RS = db_getCustomerData::getInstanceOf($dbms,$p_cliente);
  ShowHTML('<TABLE WIDTH="100%" BORDER=0>');
  ShowHTML('  <TR>');
  if (nvl($p_pagina,0)>0) $l_colspan = 4; else $l_colspan = 3;
  ShowHTML('    <TD ROWSPAN='.$l_colspan.'><IMG ALIGN="LEFT" SRC="'.$conFileVirtual.$w_cliente.'/img/'.f($l_RS,'LOGO').'">');
  ShowHTML('    <TD ALIGN="RIGHT"><B><FONT SIZE=3 COLOR="#000000">'.$p_titulo.'</FONT>');
  ShowHTML('  </TR>');
  ShowHTML('  <TR><TD ALIGN="RIGHT"><B><FONT COLOR="#000000">'.DataHora().'</B></TD></TR>');
  ShowHTML('  <TR><TD ALIGN="RIGHT"><B><FONT COLOR="#000000">Usuário: '.$_SESSION['NOME_RESUMIDO'].'</B></TD></TR>');
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
        if(is_array($vlr))
          $l_string .= '&'.$chv."=".explodeArray($vlr);
        else
          $l_string .= '&'.$chv."=".$vlr;
      }
    }
  }
  foreach($_GET as $chv => $vlr) {
    if (nvl($vlr,'')>'' && (strtoupper(substr($chv,0,2))=="W_" || strtoupper(substr($chv,0,2))=="P_")) {
      if (strtoupper($chv)=="P_ORDENA") {
        $l_ordena=strtoupper($vlr);
      } else {
        if(is_array($vlr))
          $l_string .= '&'.$chv."=".explodeArray($vlr);
        else
          $l_string .= '&'.$chv."=".$vlr;
      }
    }
  }
  if (strtoupper($p_campo)==str_replace(' DESC','',str_replace(' ASC','',strtoupper($l_ordena)))) {
    if (strpos(strtoupper($l_ordena),' DESC') !== false) {
      $l_string .= '&p_ordena='.$p_campo.' asc&';
      $l_img='&nbsp;<img src="images/down.gif" width=8 height=8 border=0 align="absmiddle">';
    } else {
      $l_string .= '&p_ordena='.$p_campo.' desc&';
      $l_img='&nbsp;<img src="images/up.gif" width=8 height=8 border=0 align="absmiddle">';
    }
  } else {
    $l_string .= '&p_ordena='.$p_campo.' asc&';
  }
  return '<a class="ss" href="'.$w_dir.$w_pagina.$par.'&R='.$R.'&O='.$O.'&P1='.$P1.'&P2='.$P2.'&P3=1'.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.$l_string.'" title="Ordena a listagem por esta coluna.">'.$p_label.'</a>'.$l_img;
}

// =========================================================================
// Montagem do cabeçalho de relatórios
// -------------------------------------------------------------------------
function CabecalhoRelatorio($p_cliente,$p_titulo,$p_rowspan=2,$l_chave=null) {
  extract($GLOBALS);
  include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');
  $RS_Logo = db_getCustomerData::getInstanceOf($dbms,$w_cliente);
  if (f($RS_Logo,'logo')>'') {
    $p_logo='img/logo'.substr(f($RS_Logo,'logo'),(strpos(f($RS_Logo,'logo'),'.') ? strpos(f($RS_Logo,'logo'),'.')+1 : 0)-1,30);
  }
  ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN='.$p_rowspan.'><IMG ALIGN="LEFT" SRC="'.LinkArquivo(null,$p_cliente,$p_logo,null,null,null,'EMBED').'"><TD ALIGN="RIGHT"><B><FONT SIZE=4 COLOR="#000000">'.$p_titulo.'</font>');
  ShowHTML('</FONT><TR><TD ALIGN="RIGHT"><B><FONT COLOR="#000000">'.DataHora().'</B></TD></TR>');
  ShowHTML('<TR><TD ALIGN="RIGHT"><B><font COLOR="#000000">Usuário: '.$_SESSION['NOME_RESUMIDO'].'</B></TD></TR>');
  if (($p_tipo!='WORD' && $w_tipo!='WORD') && ((strpos(strtoupper($w_pagina),'GR_'))===false)) {
    ShowHTML('<TR><TD ALIGN="RIGHT">');
    if(nvl($l_chave,'')>'') {
      if(RetornaGestor($l_chave,$w_usuario)=='S') ShowHTML('&nbsp;<A  class="hl" HREF="#" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.'seguranca.php?par=TelaAcessoUsuarios&w_chave='.$l_chave.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4=1&TP='.$TP.'&SG=').'\',\'Usuarios\',\'width=780,height=550,top=10,left=10,toolbar=no,scrollbars=yes,resizable=yes,status=no\'); return false;"><IMG border=0 ALIGN="CENTER" TITLE="Usuários com acesso a este documento" SRC="images/Folder/User.gif"></a>');
    }
    ShowHTML('&nbsp;<IMG ALIGN="CENTER" TITLE="Imprimir" SRC="images/impressora.jpg" onClick="window.print();">');
    ShowHTML('&nbsp;<a href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O='.$O.'&w_chave='.$l_chave.'&w_acordo='.$l_chave.'&p_plano='.$l_chave.'&w_sq_pessoa='.$l_chave.'&w_ano='.$w_ano.'&p_tipo=WORD&w_tipo=WORD&w_tipo_rel=WORD&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4=1&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><IMG border=0 ALIGN="CENTER" TITLE="Gerar word" SRC="images/word.jpg"></a>');
    //ShowHTML('&nbsp;<a href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=L&w_chave='.$l_chave.'&w_acordo='.$l_chave.'&p_plano='.$l_chave.'&w_ano='.$w_ano.'&p_tipo=EXCEL&w_tipo=EXCEL&w_tipo_rel=EXCEL&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4=1&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><IMG border=0 ALIGN="CENTER" TITLE="Gerar Excel" SRC="images/excel.jpg"></a>');
    //ShowHTML('&nbsp;<a href="'.$w_dir.$w_pagina.$par.'&R='.$w_pagina.$par.'&O=L&w_chave='.$l_chave.'&w_acordo='.$l_chave.'&p_plano='.$l_chave.'&w_ano='.$w_ano.'&p_tipo=PDF&w_tipo=PDF&w_tipo_rel=PDF&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4=1&TP='.$TP.'&SG='.$SG.MontaFiltro('GET').'"><IMG border=0 ALIGN="CENTER" TITLE="Gerar PDF" SRC="images/pdf.jpg"></a>');
    ShowHTML('</TD></TR>');
  }
  ShowHTML('</FONT></B></TD></TR></TABLE>');
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
// Retorna o nome da base geográfica a partir do código
// -------------------------------------------------------------------------
function retornaBaseGeografica($l_chave) {
  extract($GLOBALS);

  switch ($l_chave) {
    case 1: return 'Nacional';       break;
    case 2: return 'Regional';       break;
    case 3: return 'Estadual';       break;
    case 4: return 'Municipal';      break;
    case 5: return 'Organizacional'; break;
    default:return 'Erro';           break;
  }
}
// =========================================================================
// Funçao para retornar o tipo da data
// -------------------------------------------------------------------------
function RetornaTipoData ($l_chave) {
  extract($GLOBALS);
  switch ($l_chave) {
    case 'I': return 'Invariável';        break;
    case 'E': return 'Específica';        break;
    case 'S': return 'Segunda Carnaval';  break;
    case 'C': return 'Terça Carnaval';    break;
    case 'Q': return 'Quarta Cinzas';     break;
    case 'P': return 'Sexta Santa';       break;
    case 'D': return 'Domingo Páscoa';    break;
    case 'H': return 'Corpus Christi';    break;
    default:return 'Erro';                break;
  }
}
// =========================================================================
// Funçao para retornar o expediente da data
// -------------------------------------------------------------------------
function RetornaExpedienteData ($l_chave) {
  extract($GLOBALS);
  switch ($l_chave) {
    case 'S': return 'Sim';             break;
    case 'N': return 'Não';             break;
    case 'M': return 'Somente manhã';   break;
    case 'T': return 'Somente tarde';   break;
    default:  return 'Sim';             break;
  }
}
// =========================================================================
// Retorna uma parte qualquer de uma linha delimitada
// -------------------------------------------------------------------------
function Piece($p_line,$p_delimiter,$p_separator,$p_position) {
  $l_array = explode($p_separator,$p_line);
  return $l_array[($p_position-1)];
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
            $l_string .= '&'.$l_Item.'='.explodeArray($_POST[$l_Item]);
          } else {
            $l_string .= '&'.$l_Item.'='.$l_valor;
          }
        }
        elseif (strtoupper($p_method)=='POST') {
          if (is_array($_POST[$l_Item])) {
            $l_string .= '<INPUT TYPE="HIDDEN" NAME="'.$l_Item.'" VALUE="'.explodeArray($_POST[$l_Item]).'">';
          } else {
            $l_string .= '<INPUT TYPE="HIDDEN" NAME="'.$l_Item.'" VALUE="'.$l_valor.'">';
          }
        }
      }
    }
    foreach ($_GET as $l_Item => $l_valor) {
      if (substr($l_Item,0,2)=='p_' && $l_valor>'') {
        if (strtoupper($p_method)=='GET') {
          if (is_array($_GET[$l_Item])) {
            $l_string .= '&'.$l_Item.'='.explodeArray($_GET[$l_Item]);
          } else {
            $l_string .= '&'.$l_Item.'='.$l_valor;
          }
        }
        elseif (strtoupper($p_method)=='POST') {
          if (is_array($_GET[$l_Item])) {
            $l_string .= '<INPUT TYPE="HIDDEN" NAME="'.$l_Item.'" VALUE="'.explodeArray($_GET[$l_Item]).'">';
          } else {
            $l_string .= '<INPUT TYPE="HIDDEN" NAME="'.$l_Item.'" VALUE="'.$l_valor.'">';
          }
        }
      }
    }
  }
  return $l_string;
}
// =========================================================================
// Montagem de formulário para retorno à página anterior
// rerecebendo o nome do campo
// a ser dado focus no formulário original
// -------------------------------------------------------------------------
function RetornaFormulario($l_troca=null,$l_sg=null,$l_menu=null,$l_o=null,$l_dir=null,$l_pagina=null,$l_par=null,$l_p1=null,$l_p2=null,$l_p3=null,$l_p4=null,$l_tp=null,$l_r=null) {
  extract($GLOBALS);
  $l_form = '';
  // Os parâmetros informados prevalecem sobre os valores default
  if (nvl($l_pagina,'')!='') {
    $l_form .= AbreForm('RetornaDados',$l_dir.$l_pagina.$l_par,'POST',null,null,nvl($l_p1,$_POST['P1']),nvl($l_p2,$_POST['P2']),nvl($l_p3,$_POST['P3']),nvl($l_p4,$_POST['P4']),nvl($l_tp,$_POST['TP']),nvl($l_sg,$_POST['SG']),nvl($l_r,$_POST['R']),nvl($l_o,$_POST['O']),'texto');
  } else {
    $l_form .= AbreForm('RetornaDados',nvl($w_dir.$_POST['R'],$_SERVER['HTTP_REFERER']),'POST',null,null,nvl($l_p1,$_POST['P1']),nvl($l_p2,$_POST['P2']),nvl($l_p3,$_POST['P3']),nvl($l_p4,$_POST['P4']),nvl($l_tp,$_POST['TP']),nvl($l_sg,$_POST['SG']),nvl($l_r,$_POST['R']),nvl($l_o,$_POST['O']),'texto');
  }
  if (nvl($l_troca,'')!='') $l_form .= chr(13).'<INPUT TYPE="HIDDEN" NAME="w_troca" VALUE="'.$l_troca.'">';
  if (nvl($l_menu,'')!='')  $l_form .= chr(13).'<INPUT TYPE="HIDDEN" NAME="w_menu" VALUE="'.$l_menu.'">';
  if (nvl($w_dir.$_POST['R'],'')!='') {
    foreach ($_GET as $l_Item => $l_valor) {
      if ($l_Item!='par') {
        if (is_array($_GET[$l_Item])) {
          $l_form .= chr(13).'<INPUT TYPE="HIDDEN" NAME="'.$l_Item.'" VALUE="'.explodeArray($_GET[$l_Item]).'">';
        } else {
          $l_form .= chr(13).'<INPUT TYPE="HIDDEN" NAME="'.$l_Item.'" VALUE="'.$l_valor.'">';
        }
      }
    }
  }
  foreach ($_POST as $l_Item => $l_valor) {
    if (strpos($l_form,'NAME="'.$l_Item.'"')===false) {
      if ($l_Item!='w_troca' && $l_Item!='w_assinatura' && $l_Item!='Password' && $l_Item!='R' && $l_Item!='P1' && $l_Item!='P2' && $l_Item!='P3' && $l_Item!='P4' && $l_Item!='TP' && $l_Item!='O') {
        if (is_array($_POST[$l_Item])) {
          $l_form .= chr(13).'<INPUT TYPE="HIDDEN" NAME="'.$l_Item.'" VALUE="'.explodeArray($_POST[$l_Item]).'">';
        } else {
          $l_form .= chr(13).'<INPUT TYPE="HIDDEN" NAME="'.$l_Item.'" VALUE="'.$l_valor.'">';
        }
      }
    }
  }
  ShowHTML($l_form);
  ShowHTML('</FORM>');
  ScriptOpen('JavaScript');
  ShowHTML('  document.forms["RetornaDados"].submit();');
  ScriptClose();
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
  ShowHTML('<DT><font face="Verdana" size=1><b>Variáveis do servidor</b></font>:');
  foreach($_SERVER as $chv => $vlr) {
    ShowHTML('<DD><FONT FACE="courier" size=1>'.$chv.' => ['.$vlr.']</font><br>');
  }
  ShowHTML('</DT>');
  $w_item=null;
  exit();
}

// =========================================================================
// Montagem da URL para consulta ao módulo de telefonia
// -------------------------------------------------------------------------
function consultaTelefone($p_cliente) {
  extract($GLOBALS,EXTR_PREFIX_SAME,'l_');
  
  // Verifica se o cliente tem o módulo de telefonia contratado
  include_once($w_dir_volta.'classes/sp/db_getSiwCliModLis.php');
  $l_rs_ac = db_getSiwCliModLis::getInstanceOf($dbms, $p_cliente, null, 'TT');
  $l_mod_tt = false;
  $l_string = '';
  foreach ($l_rs_ac as $row) { 
    $l_mod_tt = true;
    $l_string = '<a href="'.$conRootSIW.montaUrl('LIGACAO').'" target="telefone" title="Procurar na base de ligações telefônicas."><img src="'.$conRootSIW.'/images/icone/fone_1.gif" border=0></a>';
    break; 
  }
  return $l_string;
}

// =========================================================================
// Montagem da URL para visualização de uma solicitação
// -------------------------------------------------------------------------
function ExibeSolic($l_dir,$l_chave,$l_texto=null,$l_exibe_titulo=null,$l_word=null) {
  extract($GLOBALS,EXTR_PREFIX_SAME,'l_');
  if (strpos($l_texto,'|@|')!==false) {
    $l_array = explode('|@|', $l_texto);
    if (nvl($l_word,'')=='') {
      $l_hint = $l_array[4];
      $l_string = '<A class="hl" HREF="'.$conRootSIW.$l_array[10].'&O=L&w_chave='.$l_chave.'&P1='.$l_array[6].'&P2='.$l_array[7].'&P3='.$l_array[8].'&P4='.$l_array[9].'&TP='.$TP.'&SG='.$l_array[5].'" target="_blank" title="'.$l_hint.'">'.$l_array[1].(($l_exibe_titulo=='S') ? ' - '.$l_array[2] : '').'</a>';
    } else {
      $l_string = $l_array[1].(($l_exibe_titulo=='S') ? ' - '.$l_array[2] : '');
    }
  } elseif (nvl($l_chave,'')!='') {
    include_once($w_dir_volta.'classes/sp/db_getSolicData.php');
    $RS = db_getSolicData::getInstanceOf($dbms,$l_chave);
    $l_hint = $l_array[4];
    $l_array = explode('|@|', f($RS,'dados_solic'));
    if (nvl($l_word,'')=='') {
      $l_hint = 'Exibe as informações deste registro.';
      $l_string = '<A class="hl" HREF="'.$conRootSIW.$l_array[10].'&O=L&w_chave='.$l_chave.'&P1='.$l_array[6].'&P2='.$l_array[7].'&P3='.$l_array[8].'&P4='.$l_array[9].'&TP='.$TP.'&SG='.$l_array[5].'" target="_blank" title="'.$l_hint.'">'.$l_array[1].(($l_exibe_titulo=='S') ? ' - '.$l_array[2] : '').'</a>';
    } else {
      $l_string = $l_array[1].(($l_exibe_titulo=='S') ? ' - '.$l_array[2] : '');
    }
  } else {
    $l_string = $l_texto;
  }
  return $l_string;
}

// =========================================================================
// Montagem da URL com os dados de uma pessoa
// -------------------------------------------------------------------------
function ExibePessoa($p_dir,$p_cliente,$p_pessoa,$p_tp,$p_nome) {
  extract($GLOBALS,EXTR_PREFIX_SAME,'l_');
  if (Nvl($p_nome,'')=='') {
    $l_string='---';
  } else {
    $l_string .= '<A class="hl" HREF="#" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.'seguranca.php?par=TELAUSUARIO&w_cliente='.$p_cliente.'&w_sq_pessoa='.$p_pessoa.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$p_tp.'&SG=').'\',\'Pessoa\',\'width=780,height=300,top=10,left=10,toolbar=no,scrollbars=yes,resizable=yes,status=no\'); return false;" title="Clique para exibir os dados desta pessoa!">'.$p_nome.'</A>';
  }
  return $l_string;
}

// =========================================================================
// Montagem da URL com os dados de uma pessoa no relatório de permissões
// -------------------------------------------------------------------------
function ExibePessoaRel($p_dir,$p_cliente,$p_pessoa,$p_nome,$p_nome_resumido,$p_tipo) {
  extract($GLOBALS,EXTR_PREFIX_SAME,'l_');
  if (Nvl($p_nome,'')=='') {
    $l_string='---';
  } else {
    $l_string .= '<A class="hl" HREF="'.$conRootSIW.$p_dir.'relatorios.php?par=TELAUSUARIOREL&w_cliente='.$p_cliente.'&w_sq_pessoa='.$p_pessoa.'&w_tipo='.$p_tipo.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&SG=" title="'.$p_nome.'">'.$p_nome_resumido.'</a>';  
  }
  return $l_string;
}


// =========================================================================
// Montagem da URL com os dados de um fornecedor
// -------------------------------------------------------------------------
function ExibeFornecedor($p_dir,$p_cliente,$p_pessoa,$p_tp,$p_nome) {
  extract($GLOBALS,EXTR_PREFIX_SAME,'l_');
  if (Nvl($p_nome,'')=='') {
    $l_string='---';
  } else {
    $l_string .= '<A class="hl" HREF="#" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.'mod_eo/fornecedor.php?par=Visual&w_sq_pessoa='.$p_pessoa.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$p_tp.'&SG=').'\',\'Fornecedor\',\'width=780,height=300,top=10,left=10,toolbar=no,scrollbars=yes,resizable=yes,status=no\'); return false;" title="Clique para exibir os dados deste fornecedor!">'.$p_nome.'</A>';
  }
  return $l_string;
}

// =========================================================================
// Montagem da URL com os dados de um plano estratégico
// -------------------------------------------------------------------------
function ExibePlano($p_dir,$p_cliente,$p_plano,$p_tp,$p_nome) {
  extract($GLOBALS,EXTR_PREFIX_SAME,'l_');
  if (Nvl($p_nome,'')=='') {
    $l_string='---';
  } else {
    $l_string .= '<A class="hl" HREF="#" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.'mod_pe/tabelas.php?par=TELAPLANO&w_cliente='.$p_cliente.'&w_sq_plano='.$p_plano.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$p_tp.'&SG=').'\',\'plano\',\'width=780,height=500,top=10,left=10,toolbar=no,scrollbars=yes,resizable=yes,status=no\'); return false;" title="Clique para exibir os dados deste plano!">'.$p_nome.'</A>';
  }
  return $l_string;
}

// =========================================================================
// Montagem da URL com os dados de uma pessoa com os pacontes vinculados
// -------------------------------------------------------------------------
function ExibeUnidadePacote($O,$p_cliente,$p_chave,$p_chave_aux,$p_unidade,$p_tp,$p_nome) {
  extract($GLOBALS,EXTR_PREFIX_SAME,'l_');
  if (Nvl($p_nome,'')=='') {
    $l_string='---';
  } else {
    $l_string .= '<A class="hl" HREF="#" onClick="window.open(\''.$conRootSIW.'projeto.php?par=InteressadoPacote&w_chave='.$p_chave.'&O='.$O.'&w_chave_aux='.$p_chave_aux.'&w_sq_unidade='.$p_unidade.'&P1='.$p_P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$p_tp.'&SG='.$p_sg.'\',\'Interessados\',\'width=780,height=550,top=50,left=10,toolbar=no,scrollbars=yes,resizable=yes,status=no\'); return false;" title="Clique para exibir os dados!">'.$p_nome.'</A>';
  }
  return $l_string;
}

// =========================================================================
// Montagem da URL com os dados de uma pessoa
// -------------------------------------------------------------------------
function VisualIndicador($p_dir,$p_cliente,$p_sigla,$p_tp,$p_nome) {
  extract($GLOBALS,EXTR_PREFIX_SAME,'l_');
  $l_RS = db_getIndicador::getInstanceOf($dbms,$w_cliente,$w_usuario,null,null,null,$p_sigla,null,null,null,null,null,null,null,null,null,null,null,"EXISTE");
  if(count($l_RS)>0) {
    if (Nvl($p_nome,'')=='') {
      $l_string='---';
    } else {
      $l_string .= '<A class="hl" HREF="#" onClick="window.open(\''.$conRootSIW.'mod_pe/indicador.php?par=TELAINDICADOR&w_cliente='.$p_cliente.'&w_sigla='.$p_sigla.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$p_tp.'&SG='.'\',\'Indicador\',\'width=780,height=300,top=10,left=10,toolbar=no,scrollbars=yes,resizable=yes,status=no\'); return false;" title="Clique para exibir os dados deste de indicador!">'.$p_nome.'</A>';
    }
  } else {
    $l_string=$p_sigla;
  }
  return $l_string;
}

// =========================================================================
// Montagem da URL com os dados de uma unidade
// -------------------------------------------------------------------------
function ExibeUnidade($p_dir,$p_cliente,$p_unidade,$p_sq_unidade,$p_tp) {
  extract($GLOBALS,EXTR_PREFIX_SAME,'l_');
  if (Nvl($p_unidade,'')=='') {
    $l_string='---';
  } else {
    $l_string .= '<A class="hl" HREF="#" onClick="window.open(\''.montaURL_JS(null,$conRootSIW.'seguranca.php?par=TELAUNIDADE&w_cliente='.$p_cliente.'&w_sq_unidade='.$p_sq_unidade.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$p_tp.'&SG=').'\',\'Unidade\',\'width=780,height=300,top=10,left=10,toolbar=no,scrollbars=yes,resizable=yes,status=no\'); return false;" title="Clique para exibir os dados desta unidade!">'.$p_unidade.'</A>';
  }
  return $l_string;
}

// =========================================================================
// Montagem da URL com os dados de um recurso
// -------------------------------------------------------------------------
function ExibeRecurso($p_dir,$p_cliente,$p_nome,$p_chave,$p_tp,$p_solic) {
  extract($GLOBALS,EXTR_PREFIX_SAME,'l_');
  if (Nvl($p_chave,'')=='') {
    $l_string='---';
  } else {
    $l_string .= '<A class="hl" HREF="#" onClick="window.open(\''.$conRootSIW.'mod_pe/recurso.php?par=TELARECURSO&w_cliente='.$p_cliente.'&w_chave='.$p_chave.'&w_solic='.$p_solic.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$p_tp.'&SG='.'\',\'Telarecurso\',\'width=785,height=570,top=10,left=10,toolbar=no,scrollbars=yes,resizable=yes,status=no\'); return false;" title="Clique para exibir os dados deste recurso!">'.$p_nome.'</A>';
  }
  return $l_string;
}

// =========================================================================
// Montagem da URL com os dados de um material ou serviço
// -------------------------------------------------------------------------
function ExibeMaterial($p_dir,$p_cliente,$p_nome,$p_chave,$p_tp,$p_solic) {
  extract($GLOBALS,EXTR_PREFIX_SAME,'l_');
  if (Nvl($p_chave,'')=='') {
    $l_string='---';
  } else {
    $l_string .= '<A class="hl" HREF="#" onClick="window.open(\''.$conRootSIW.'mod_cl/catalogo.php?par=TELAMATERIAL&w_cliente='.$p_cliente.'&w_chave='.$p_chave.'&w_solic='.$p_solic.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$p_tp.'&SG='.'\',\'Telarecurso\',\'width=785,height=570,top=10,left=10,toolbar=no,scrollbars=yes,resizable=yes,status=no\'); return false;" title="Clique para exibir os dados deste material ou serviço!">'.$p_nome.'</A>';
  }
  return $l_string;
}

// =========================================================================
// Montagem da URL com os dados de uma restricao
// -------------------------------------------------------------------------
function ExibeRestricao($O,$p_dir,$p_cliente,$p_tipo,$p_chave,$p_chave_aux,$p_tp,$p_solic) {
  extract($GLOBALS,EXTR_PREFIX_SAME,'l_');
  if (Nvl($p_tipo,'')=='') {
    $l_string='---';
  } else {
    $l_string .= '<A class="hl" HREF="#" onClick="window.open(\''.$conRootSIW.'mod_pr/restricao.php?par=VisualRestricao&w_cliente='.$p_cliente.'&w_chave='.$p_chave.'&w_chave_aux='.$p_chave_aux.'&O='.$O.'&w_solic='.$p_solic.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$p_tp.'&SG='.'\',\'VisualRestriao\',\'width=785,height=570,top=10,left=10,toolbar=no,scrollbars=yes,resizable=yes,status=no\'); return false;" title="Clique para exibir os dados desta restricao!">'.$p_tipo.'</A>';
  }
  return $l_string;
}

// =========================================================================
// Montagem da URL com os dados de um recurso
// -------------------------------------------------------------------------
function ExibeIndicador($p_dir,$p_cliente,$p_nome,$p_dados,$p_tp) {
  extract($GLOBALS,EXTR_PREFIX_SAME,'l_');
  if (Nvl($p_dados,'')=='') {
    $l_string='---';
  } else {
    $l_string .= '<A class="hl" HREF="#" onClick="window.open(\''.$conRootSIW.'mod_pe/indicador.php?par=FramesAfericao&R='.$w_pagina.$par.'&O=L'.$p_dados.'&P1='.$l_p1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$TP.'&SG='.$SG.'\',\'TelaIndicador\',\'width=785,height=570,top=10,left=10,toolbar=no,scrollbars=yes,resizable=yes,status=no\'); return false;" title="Clique para exibir os dados deste indicador!">'.$p_nome.'</A>';
  }
  return $l_string;
}

// =========================================================================
// Montagem da URL com os dados da etapa
// -------------------------------------------------------------------------
function ExibeEtapa($O,$p_chave,$p_chave_aux,$p_tipo,$p_P1,$p_etapa,$p_tp,$p_sg) {
  extract($GLOBALS,EXTR_PREFIX_SAME,'l_');
  if (Nvl($p_etapa,'')=='') {
    $l_string="---";
  } else {
    $l_string .= '<A class="hl" HREF="#" onClick="window.open(\''.$conRootSIW.'projeto.php?par=AtualizaEtapa&w_chave='.$p_chave.'&O='.$O.'&w_chave_aux='.$p_chave_aux.'&w_tipo='.$p_tipo.'&P1='.$p_P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$p_tp.'&SG='.$p_sg.'\',\'Etapa\',\'width=780,height=550,top=50,left=10,toolbar=no,scrollbars=yes,resizable=yes,status=no\'); return false;" title="Clique para exibir os dados!">'.$p_etapa.'</A>';
  }
  return $l_string;
}

// =========================================================================
// Exibe imagem da restrição conforme tipo e criticidade
// -------------------------------------------------------------------------
function ExibeImagemRestricao($l_tipo,$l_imagem=null,$l_legenda=0) {
  extract($GLOBALS);
  $l_string = '';
  if ($l_legenda) {
    $l_string .= '<tr valign="top">';
    $l_string .= '<td width="1%" nowrap><img src="'.$conRootSIW.$conImgProblem.'" border=0 width=10 height=10 align="center"><td>Problema.';
    $l_string .= '<td width="1%" nowrap><img src="'.$conRootSIW.$conImgRiskHig.'" border=0 width=10 height=10 align="center"><td>Risco de alta criticidade.';
    $l_string .= '<td width="1%" nowrap><img src="'.$conRootSIW.$conImgRiskMed.'" border=0 width=10 height=10 align="center"><td>Risco de moderada ou baixa criticidade. ';
  } else {
    if ($l_imagem=='P') {
      if (Nvl($l_tipo,'N')!='N') {
        switch ($l_tipo) {
          case 'S1': $l_string .= '<img title="Problema de baixa criticidade" src="'.$conRootSIW.$conImgProblem.'" width=10 height=10 border=0 align="center">';   break;
          case 'S2': $l_string .= '<img title="Problema de moderada criticidade" src="'.$conRootSIW.$conImgProblem.'" width=10 height=10 border=0 align="center">';   break;
          case 'S3': $l_string .= '<img title="Problema de alta criticidade" src="'.$conRootSIW.$conImgProblem.'" width=10 height=10 border=0 align="center">';    break;
        }
      }
    } else {
      if (Nvl($l_tipo,'N')!='N') {
        switch ($l_tipo) {
          case 'S1': $l_string .= '<img title="Problema de baixa criticidade" src="'.$conRootSIW.$conImgProblem.'" width=10 height=10 border=0 align="center">';   break;
          case 'S2': $l_string .= '<img title="Problema de moderada criticidade" src="'.$conRootSIW.$conImgProblem.'" width=10 height=10 border=0 align="center">';   break;
          case 'S3': $l_string .= '<img title="Problema de alta criticidade" src="'.$conRootSIW.$conImgProblem.'" width=10 height=10 border=0 align="center">';    break;
          case 'N1': $l_string .= '<img title="Risco de baixa criticidade" src="'.$conRootSIW.$conImgRiskLow.'" width=10 height=10 border=0 align="center">';   break;
          case 'N2': $l_string .= '<img title="Risco de moderada criticidade" src="'.$conRootSIW.$conImgRiskMed.'" width=10 height=10 border=0 align="center">';   break;
          case 'N3': $l_string .= '<img title="Risco de alta criticidade" src="'.$conRootSIW.$conImgRiskHig.'" width=10 height=10 border=0 align="center">';    break;
        }
      }
    }
  }
  return $l_string;
}

// =========================================================================
// Exibe imagem do ícone smile
// -------------------------------------------------------------------------
function ExibeSmile($l_tipo,$l_andamento,$l_legenda=0) {
  extract($GLOBALS);
  $l_tipo       = trim(strtoupper($l_tipo));
  $l_andamento  = nvl($l_andamento,0);
  if ($l_legenda) {
    if ($l_tipo=='IDE') {
      $l_string .= '<tr valign="top">';
      $l_string .= '<td width="1%" nowrap><img src="'.$conRootSIW.$conImgSmAtraso.'" border=0 width=10 height=10 align="center"><td>Fora da faixa desejável (abaixo de 70%).';
      $l_string .= '<td width="1%" nowrap><img src="'.$conRootSIW.$conImgSmAviso.'" border=0 width=10 height=10 align="center"><td>Próximo da faixa desejável (de 70% a 89,99% ou acima de 120%).';
      $l_string .= '<td width="1%" nowrap><img src="'.$conRootSIW.$conImgSmNormal.'" border=0 width=10 height=10 align="center"><td>Na faixa desejável (de 90% a 120%). ';
    } elseif ($l_tipo=='IDC') {
      $l_string .= '<tr valign="top">';
      $l_string .= '<td width="1%" nowrap><img src="'.$conRootSIW.$conImgSmAtraso.'" border=0 width=10 height=10 align="center"><td>Fora da faixa desejável (abaixo de 70%).';
      $l_string .= '<td width="1%" nowrap><img src="'.$conRootSIW.$conImgSmAviso.'" border=0 width=10 height=10 align="center"><td>Próximo da faixa desejável (de 70% a 89,99% ou acima de 120%).';
      $l_string .= '<td width="1%" nowrap><img src="'.$conRootSIW.$conImgSmNormal.'" border=0 width=10 height=10 align="center"><td>Na faixa desejável (de 90% a 120%). ';
    }
  } else {
    if ($l_tipo=='IDE') {
      if ($l_andamento < 70)                           $l_string .= '<img title="IDE fora da faixa desejável." src="'.$conRootSIW.$conImgSmAtraso.'" border=0 width="10" height="10" align="center">';
      elseif ($l_andamento < 90 || $l_andamento > 120) $l_string .= '<img title="IDE próximo da faixa desejável." src="'.$conRootSIW.$conImgSmAviso.'" border=0 width="10" height="10" align="center">';
      else                                             $l_string .= '<img title="IDE na faixa desejável." src="'.$conRootSIW.$conImgSmNormal.'" border=0 width="10" height="10" align="center">';
    } elseif ($l_tipo=='IDC') {
      if ($l_andamento < 70)                           $l_string .= '<img title="IDC fora da faixa desejável." src="'.$conRootSIW.$conImgSmAtraso.'" border=0 width="10" height="10" align="center">';
      elseif ($l_andamento < 90 || $l_andamento > 120) $l_string .= '<img title="IDC próximo da faixa desejável." src="'.$conRootSIW.$conImgSmAviso.'" border=0 width="10" height="10" align="center">';
      else                                             $l_string .= '<img title="IDC na faixa desejável." src="'.$conRootSIW.$conImgSmNormal.'" border=0 width="10" height="10" align="center">';  
    }
  }
  return $l_string;
}

// =========================================================================
// Exibe sinalizador para pesquisa de preço
// -------------------------------------------------------------------------
function ExibeSinalPesquisa($l_legenda,$l_inicio, $l_fim,$l_dias_aviso=0) {
  extract($GLOBALS);
  include_once($w_dir_volta.'classes/sp/db_getCLParametro.php');
  $RS_Parametro = db_getCLParametro::getInstanceOf($dbms,$w_cliente,null,null);
  foreach($RS_Parametro as $row_parametro) { $RS_Parametro = $row_parametro; break; }
  if ($l_legenda) {
    $l_string .= '<tr valign="top">';
    $l_string .= '<td width="1%" nowrap><img src="'.$conRootSIW.$conImgSmAtraso.'" border=0 width=10 height=10 align="center"><td>Pesquisa com mais de '.f($RS_Parametro,'dias_validade_pesquisa').' dias.';
    $l_string .= '<td width="1%" nowrap><img src="'.$conRootSIW.$conImgSmAviso.'" border=0 width=10 height=10 align="center"><td>Pesquisa com '.f($RS_Parametro,'dias_aviso_pesquisa').' dias ou menos de validade.';
    $l_string .= '<td width="1%" nowrap><img src="'.$conRootSIW.$conImgSmNormal.'" border=0 width=10 height=10 align="center"><td>Pesquisa válida. ';
  } else {
    if ($l_fim<addDays(time(),-1)) $l_string .= '<img title="Pesquisa com mais de '.f($RS_Parametro,'dias_validade_pesquisa').' dias." src="'.$conRootSIW.$conImgSmAtraso.'" border=0 width="10" height="10" align="center">';
    elseif ($l_dias_aviso<=time()) $l_string .= '<img title="Pesquisa com '.f($RS_Parametro,'dias_aviso_pesquisa').' dias ou menos de validade." src="'.$conRootSIW.$conImgSmAviso.'" border=0 width="10" height="10" align="center">';
    else                           $l_string .= '<img title="Pesquisa válida." src="'.$conRootSIW.$conImgSmNormal.'" border=0 width="10" height="10" align="center">';
  }
  return $l_string;
}

// =========================================================================
// Exibe sinalizador para pesquisa de preço
// -------------------------------------------------------------------------
function exibeImagemAnexo($l_exibe=0) {
  extract($GLOBALS);
  $l_string = '';
  if ($l_exibe>0) $l_string .= '<img title="Há arquivos disponíveis para download." src="'.$conRootSIW.$conImgDownload.'" border=0 width="14" height="14" align="center">';
  return $l_string;
}

// =========================================================================
// Exibe imagem da solicitação informada
// -------------------------------------------------------------------------
function ExibeImagemSolic($l_tipo,$l_inicio,$l_fim,$l_inicio_real,$l_fim_real,$l_aviso,$l_dias_aviso,$l_tramite, $l_perc, $l_legenda=0, $l_restricao=null) {
  extract($GLOBALS);
  $l_string = '';
  $l_imagem = '';
  $l_title  = '';
  $l_tipo = strtoupper($l_tipo);
  if ($l_legenda) {
    if ($l_tipo=='ETAPA') {
      // Etapas de projeto
      $l_string .= '<tr valign="top">';
      $l_string .= '<td width="1%" nowrap><img src="'.$conImgAtraso.'" border=0 width=10 heigth=10 align="center"><td>Execução não iniciada. Fim previsto superado.';
      $l_string .= '<td width="1%" nowrap><img src="'.$conImgAviso.'" border=0 width=10 heigth=10 align="center"><td>Execução não iniciada. Percentual de conclusão incompatível com os dias transcorridos.';
      $l_string .= '<td width="1%" nowrap><img src="'.$conImgNormal.'" border=0 width=10 heigth=10 align="center"><td>Execução não iniciada. Prazo final dentro do previsto.';
      $l_string .= '<tr valign="top">';
      $l_string .= '<td width="1%" nowrap><img src="'.$conImgStAtraso.'" border=0 width=10 heigth=10 align="center"><td>Em execução. Fim previsto superado.';
      $l_string .= '<td width="1%" nowrap><img src="'.$conImgStAviso.'" border=0 width=10 heigth=10 align="center"><td>Em execução. Percentual de conclusão incompatível com os dias transcorridos.';
      $l_string .= '<td width="1%" nowrap><img src="'.$conImgStNormal.'" border=0 width=10 heigth=10 align="center"><td>Em execução. Prazo final dentro do previsto.';
      $l_string .= '<tr valign="top">';
      $l_string .= '<td width="1%" nowrap><img src="'.$conImgOkAtraso.'" border=0 width=10 heigth=10 align="center"><td>Execução concluída após a data prevista.';
      $l_string .= '<td width="1%" nowrap><img src="'.$conImgOkAcima.'" border=0 width=10 heigth=10 align="center"><td>Execução concluída antes da data prevista.';
      $l_string .= '<td width="1%" nowrap><img src="'.$conImgOkNormal.'" border=0 width=10 heigth=10 align="center"><td>Execução concluída na data prevista.';
    } elseif (substr($l_tipo,0,2)=='GD' || substr($l_tipo,0,2)=='SR' || substr($l_tipo,0,2)=='PJ') {
      // Tarefas e demandas eventuais
      $l_string .= '<tr valign="top">';
      $l_string .= '<td width="1%" nowrap><img src="'.$conImgCancel.'" border=0 width=10 heigth=10 align="center"><td>Registro cancelado.';
      $l_string .= '<tr valign="top">';
      $l_string .= '<td width="1%" nowrap><img src="'.$conImgAtraso.'" border=0 width=10 heigth=10 align="center"><td>Execução não iniciada. Fim previsto superado.';
      $l_string .= '<td width="1%" nowrap><img src="'.$conImgAviso.'" border=0 width=10 heigth=10 align="center"><td>Execução não iniciada. Fim previsto próximo.';
      $l_string .= '<td width="1%" nowrap><img src="'.$conImgNormal.'" border=0 width=10 heigth=10 align="center"><td>Execução não iniciada. Prazo final dentro do previsto.';
      $l_string .= '<tr valign="top">';
      $l_string .= '<td width="1%" nowrap><img src="'.$conImgStAtraso.'" border=0 width=10 heigth=10 align="center"><td>Em execução. Fim previsto superado.';
      $l_string .= '<td width="1%" nowrap><img src="'.$conImgStAviso.'" border=0 width=10 heigth=10 align="center"><td>Em execução. Fim previsto próximo.';
      $l_string .= '<td width="1%" nowrap><img src="'.$conImgStNormal.'" border=0 width=10 heigth=10 align="center"><td>Em execução. Prazo final dentro do previsto.';
      $l_string .= '<tr valign="top">';
      $l_string .= '<td width="1%" nowrap><img src="'.$conImgOkAtraso.'" border=0 width=10 heigth=10 align="center"><td>Execução concluída após a data prevista.';
      $l_string .= '<td width="1%" nowrap><img src="'.$conImgOkAcima.'" border=0 width=10 heigth=10 align="center"><td>Execução concluída antes da data prevista.';
      $l_string .= '<td width="1%" nowrap><img src="'.$conImgOkNormal.'" border=0 width=10 heigth=10 align="center"><td>Execução concluída na data prevista.';
    } elseif (substr($l_tipo,0,2)=='PD') {
      // Viagens
      $l_string .= '<tr valign="top">';
      $l_string .= '<td width="1%" nowrap><img src="'.$conImgCancel.'" border=0 width=10 heigth=10 align="center"><td>Registro cancelado';
      $l_string .= '<td width="1%" nowrap><img src="'.$conImgAviso.'" border=0 width=10 heigth=10 align="center"><td>Início próximo';
      $l_string .= '<td width="1%" nowrap><img src="'.$conImgNormal.'" border=0 width=10 heigth=10 align="center"><td>Não iniciada';
      $l_string .= '<tr valign="top">';
      $l_string .= '<td width="1%" nowrap><img src="'.$conImgStAtraso.'" border=0 width=10 heigth=10 align="center"><td>Tramitação em atraso';
      $l_string .= '<td><td>';
      $l_string .= '<td width="1%" nowrap><img src="'.$conImgStNormal.'" border=0 width=10 heigth=10 align="center"><td>Em andamento';
      $l_string .= '<tr valign="top">';
      $l_string .= '<td width="1%" nowrap><img src="'.$conImgOkAtraso.'" border=0 width=10 heigth=10 align="center"><td>Tramitação em atraso';
      $l_string .= '<td width="1%" nowrap><img src="'.$conImgOkAcima.'" border=0 width=10 heigth=10 align="center"><td>Pendente prestação de contas';
      $l_string .= '<td width="1%" nowrap><img src="'.$conImgOkNormal.'" border=0 width=10 heigth=10 align="center"><td>Encerrada';
    }
  } else {
    if ($l_tipo=='ETAPA') {
      // Etapas de projeto
      if ($l_perc<100) {
        if (nvl($l_inicio_real,'')=='') {
          if ($l_fim<addDays(time(),-1)) {
            $l_imagem = $conImgAtraso;
            $l_title  = 'Execução não iniciada. Fim previsto superado.';
          } elseif (((time()-$l_inicio)/($l_fim-$l_inicio+1))*100>$l_perc) {
            $l_imagem = $conImgAviso;
            $l_title  = 'Execução não iniciada. Percentual de conclusão incompatível com os dias transcorridos.';
          } else {
            $l_imagem = $conImgNormal;
            $l_title  = 'Execução não iniciada. Prazo final dentro do previsto.';
          } 
        } else {
          if ($l_fim<addDays(time(),-1)) {
            $l_imagem = $conImgStAtraso;
            $l_title  = 'Em execução. Fim previsto superado.';
          } elseif (((time()-$l_inicio)/($l_fim-$l_inicio+1))*100>$l_perc) {
            $l_imagem = $conImgStAviso;
            $l_title  = 'Em execução. Percentual de conclusão incompatível com os dias transcorridos.';
          } else {
            $l_imagem = $conImgStNormal;
            $l_title  = 'Em execução. Prazo final dentro do previsto.';
          } 
        }
      } else {
        if ($l_fim<Nvl($l_fim_real,$l_fim)) {
          $l_imagem = $conImgOkAtraso;
          $l_title  = 'Execução concluída após a data prevista.';
        } elseif ($l_fim>Nvl($l_fim_real,$l_fim)) {
          $l_imagem = $conImgOkAcima;
          $l_title  = 'Execução concluída antes da data prevista.';
        } else {
          $l_imagem = $conImgOkNormal;
          $l_title  = 'Execução concluída na data prevista.';
        } 
      } 
    } elseif (substr($l_tipo,0,2)=='GC') {
      // Contratos e convênios
      if ($l_tramite!='AT' && $l_tramite!='CR') {
        if ($l_tramite=='CA') {
          $l_imagem = $conImgCancel;
          $l_title  = 'Registro cancelado.';
        } elseif ($l_tramite=='CI') {
          if ($l_fim<addDays(time(),-1)) {
            $l_imagem = $conImgAtraso;
            $l_title  = 'Execução não iniciada. Vigência prevista ultrapassada.';
          } elseif ($l_aviso=='S' && $l_dias_aviso<=time()) {
            $l_imagem = $conImgAviso;
            $l_title  = 'Execução não iniciada. Vigência prevista próxima do término.';
          } else {
            $l_imagem = $conImgNormal;
            $l_title  = 'Execução não iniciada.';
          } 
        } elseif ($l_tramite=='ER') {
          $l_imagem = $conImgStAcima;
          $l_title  = 'Vigência encerrada, com restos a pagar.';
        } else {
          if ($l_fim<addDays(time(),-1)) {
            $l_imagem = $conImgStAtraso;
            $l_title  = 'Em execução. Vigência prevista ultrapassada.';
          } elseif ($l_aviso=='S' && $l_dias_aviso<=time()) {
            $l_imagem = $conImgStAviso;
            $l_title  = 'Em execução. Vigência prevista próxima do término.';
          } else {
            $l_imagem = $conImgStNormal;
            $l_title  = 'Em execução.';
          } 
        }
      } else {
        if ($l_fim<Nvl($l_fim_real,$l_fim)) {
          $l_imagem = $conImgOkAtraso;
          $l_title  = 'Vigência superior à prevista.';
        } elseif ($l_fim>Nvl($l_fim_real,$l_fim)) {
          $l_imagem = $conImgOkAcima;
          $l_title  = 'Vigência encerrada antes do previsto.';
        } else {
          $l_imagem = $conImgOkNormal;
          $l_title  = 'Vigência encerrada conforme previsão.';
        } 
      } 
    } elseif (substr($l_tipo,0,2)=='GD') {
      // Tarefas, demandas eventuais e demandas de triagem
      if ($l_tramite!='AT') {
        if ($l_tramite=='CA') {
          $l_imagem = $conImgCancel;
          $l_title  = 'Registro cancelado.';
        } elseif ($l_tramite=='CI' || $l_restricao=='SEMEXECUCAO') {
          if ($l_fim<addDays(time(),-1)) {
            $l_imagem = $conImgAtraso;
            $l_title  = 'Execução não iniciada. Fim previsto superado.';
          } elseif ($l_aviso=='S' && $l_dias_aviso<=time()) {
            $l_imagem = $conImgAviso;
            $l_title  = 'Execução não iniciada. Fim previsto próximo.';
          } else {
            $l_imagem = $conImgNormal;
            $l_title  = 'Execução não iniciada. Prazo final dentro do previsto.';
          } 
        } else {
          if ($l_fim<addDays(time(),-1)) {
            $l_imagem = $conImgStAtraso;
            $l_title  = 'Em execução. Fim previsto superado.';
          } elseif ($l_aviso=='S' && $l_dias_aviso<=time()) {
            $l_imagem = $conImgStAviso;
            $l_title  = 'Em execução. Fim previsto próximo.';
          } else {
            $l_imagem = $conImgStNormal;
            $l_title  = 'Em execução. Prazo final dentro do previsto.';
          } 
        }
      } else {
        if ($l_fim<Nvl($l_fim_real,$l_fim)) {
          $l_imagem = $conImgOkAtraso;
          $l_title  = 'Execução concluída após a data prevista.';
        } elseif ($l_fim>Nvl($l_fim_real,$l_fim)) {
          $l_imagem = $conImgOkAcima;
          $l_title  = 'Execução concluída antes da data prevista.';
        } else {
          $l_imagem = $conImgOkNormal;
          $l_title  = 'Execução concluída na data prevista.';
        } 
      } 
    } elseif (substr($l_tipo,0,2)=='FN') {
      // Tarefas e demandas eventuais
      if ($l_tramite!='AT') {
        if ($l_tramite=='CA') {
          $l_imagem = $conImgCancel;
          $l_title  = 'Registro cancelado.';
        } elseif ($l_tramite=='CI') {
          if ($l_fim<addDays(time(),-1)) {
            $l_imagem = $conImgAtraso;
            $l_title  = 'Execução não iniciada. Fim previsto superado.';
          } elseif ($l_aviso=='S' && ($l_dias_aviso<=addDays(time(),-1))) {
            $l_imagem = $conImgAviso;
            $l_title  = 'Execução não iniciada. Fim previsto próximo.';
          } else {
            $l_imagem = $conImgNormal;
            $l_title  = 'Execução não iniciada. Prazo final dentro do previsto.';
          } 
        } else {
          if ($l_fim<addDays(time(),-1)) {
            $l_imagem = $conImgStAtraso;
            $l_title  = 'Em execução. Fim previsto superado.';
          } elseif ($l_aviso=='S' && $l_dias_aviso<=time()) {
            $l_imagem = $conImgStAviso;
            $l_title  = 'Em execução. Fim previsto próximo.';
          } else {
            $l_imagem = $conImgStNormal;
            $l_title  = 'Em execução. Prazo final dentro do previsto.';
          } 
        }
      } else {
        if ($l_fim<Nvl($l_fim_real,$l_fim)) {
          $l_imagem = $conImgOkAtraso;
          $l_title  = 'Execução concluída após a data prevista.';
        } elseif ($l_fim>Nvl($l_fim_real,$l_fim)) {
          $l_imagem = $conImgOkAcima;
          $l_title  = 'Execução concluída antes da data prevista.';
        } else {
          $l_imagem = $conImgOkNormal;
          $l_title  = 'Execução concluída na data prevista.';
        } 
      } 
    } elseif (substr($l_tipo,0,2)=='SR') {
      // Tarefas
      if ($l_tramite!='AT') {
        if ($l_tramite=='CA') {
          $l_imagem = $conImgCancel;
          $l_title  = 'Registro cancelado.';
        } elseif ($l_tramite=='CI') {
          if ($l_fim<time()) {
            $l_imagem = $conImgAtraso;
            $l_title  = 'Execução não iniciada. Fim previsto superado.';
          } elseif ($l_aviso=='S' && $l_dias_aviso<=time()) {
            $l_imagem = $conImgAviso;
            $l_title  = 'Execução não iniciada. Fim previsto próximo.';
          } else {
            $l_imagem = $conImgNormal;
            $l_title  = 'Execução não iniciada. Prazo final dentro do previsto.';
          } 
        } else {
          if ($l_fim<time()) {
            $l_imagem = $conImgStAtraso;
            $l_title  = 'Em execução. Fim previsto superado.';
          } elseif ($l_aviso=='S' && $l_dias_aviso<=time()) {
            $l_imagem = $conImgStAviso;
            $l_title  = 'Em execução. Fim previsto próximo.';
          } else {
            $l_imagem = $conImgStNormal;
            $l_title  = 'Em execução. Prazo final dentro do previsto.';
          } 
        }
      } else {
        if ($l_fim<Nvl($l_fim_real,$l_fim)) {
          $l_imagem = $conImgOkAtraso;
          $l_title  = 'Execução concluída após a data prevista.';
        } elseif ($l_fim>Nvl($l_fim_real,$l_fim)) {
          $l_imagem = $conImgOkAcima;
          $l_title  = 'Execução concluída antes da data prevista.';
        } else {
          $l_imagem = $conImgOkNormal;
          $l_title  = 'Execução concluída na data prevista.';
        } 
      } 
    } elseif (substr($l_tipo,0,2)=='PJ') {
      // Projetos
      if ($l_tramite!='AT') {
        if ($l_tramite=='CA') {
          $l_imagem = $conImgCancel;
          $l_title  = 'Registro cancelado.';
        } elseif ($l_tramite=='CI') {
          if ($l_fim<addDays(time(),-1)) {
            $l_imagem = $conImgAtraso;
            $l_title  = 'Execução não iniciada. Fim previsto superado.';
          } elseif ($l_aviso=='S' && ($l_dias_aviso<=addDays(time(),-1))) {
            $l_imagem = $conImgAviso;
            $l_title  = 'Execução não iniciada. Fim previsto próximo.';
          } else {
            $l_imagem = $conImgNormal;
            $l_title  = 'Execução não iniciada. Prazo final dentro do previsto.';
          } 
        } else {
          if ($l_fim<addDays(time(),-1)) {
            $l_imagem = $conImgStAtraso;
            $l_title  = 'Em execução. Fim previsto superado.';
          } elseif ($l_aviso=='S' && ($l_dias_aviso<=addDays(time(),-1))) {
            $l_imagem = $conImgStAviso;
            $l_title  = 'Em execução. Fim previsto próximo.';
          } else {
            $l_imagem = $conImgStNormal;
            $l_title  = 'Em execução. Prazo final dentro do previsto.';
          } 
        }
      } else {
        if ($l_fim<Nvl($l_fim_real,$l_fim)) {
          $l_imagem = $conImgOkAtraso;
          $l_title  = 'Execução concluída após a data prevista.';
        } elseif ($l_fim>Nvl($l_fim_real,$l_fim)) {
          $l_imagem = $conImgOkAcima;
          $l_title  = 'Execução concluída antes da data prevista.';
        } else {
          $l_imagem = $conImgOkNormal;
          $l_title  = 'Execução concluída na data prevista.';
        } 
      } 
    } elseif (substr($l_tipo,0,2)=='CL') {
      // Projetos
      if ($l_tramite!='AT') {
        if ($l_tramite=='CA') {
          $l_imagem = $conImgCancel;
          $l_title  = 'Registro cancelado.';
        } elseif ($l_tramite=='CI') {
          if ($l_fim<addDays(time(),-1)) {
            $l_imagem = $conImgAtraso;
            $l_title  = 'Execução não iniciada. Fim previsto superado.';
          } elseif ($l_aviso=='S' && ($l_dias_aviso<=addDays(time(),-1))) {
            $l_imagem = $conImgAviso;
            $l_title  = 'Execução não iniciada. Fim previsto próximo.';
          } else {
            $l_imagem = $conImgNormal;
            $l_title  = 'Execução não iniciada. Prazo final dentro do previsto.';
          } 
        } else {
          if ($l_fim<addDays(time(),-1)) {
            $l_imagem = $conImgStAtraso;
            $l_title  = 'Em execução. Fim previsto superado.';
          } elseif ($l_aviso=='S' && ($l_dias_aviso<=addDays(time(),-1))) {
            $l_imagem = $conImgStAviso;
            $l_title  = 'Em execução. Fim previsto próximo.';
          } else {
            $l_imagem = $conImgStNormal;
            $l_title  = 'Em execução. Prazo final dentro do previsto.';
          } 
        }
      } else {
        if ($l_fim<Nvl($l_fim_real,$l_fim)) {
          $l_imagem = $conImgOkAtraso;
          $l_title  = 'Execução concluída após a data prevista.';
        } elseif ($l_fim>Nvl($l_fim_real,$l_fim)) {
          $l_imagem = $conImgOkAcima;
          $l_title  = 'Execução concluída antes da data prevista.';
        } else {
          $l_imagem = $conImgOkNormal;
          $l_title  = 'Execução concluída na data prevista.';
        } 
      } 
    } elseif (substr($l_tipo,0,2)=='PD') {
      // Viagens
      if ($l_tramite=='CA') {
        $l_imagem = $conImgCancel;
        $l_title  = 'Registro cancelado.';
      } elseif ($l_fim<addDays(time(),-1)) {
        if ($l_tramite=='AT') {
          $l_imagem = $conImgOkNormal;
          $l_title  = 'Missão encerrada.';
        } elseif ($l_tramite!='EE') {
          $l_imagem = $conImgOkAtraso;
          $l_title  = 'Missão com tramitação em atraso.';
        } else {
          $l_imagem = $conImgOkAcima;
          $l_title  = 'Missão pendente de prestação de contas.';
        } 
      } elseif ($l_inicio>time()) {
        if ($l_dias_aviso<=time()) {
          $l_imagem = $conImgAviso;
          $l_title  = 'Missão com início próximo.';
        } else {
          $l_imagem = $conImgNormal;
          $l_title  = 'Missão não iniciada.';
        } 
      } else {
        if ($l_tramite!='EE') {
          $l_imagem = $conImgOkAtraso;
          $l_title  = 'Missão em andamento com tramitação em atraso.';
        } else {
          $l_imagem = $conImgStNormal;
          $l_title  = 'Missão em andamento.';
        }
      }
    } elseif (substr($l_tipo,0,2)=='PE') {
      // Projetos
      if ($l_tramite!='AT') {
        if ($l_tramite=='CA') {
          $l_imagem = $conImgCancel;
          $l_title  = 'Registro cancelado.';
        } elseif ($l_tramite=='CI') {
          if ($l_fim<addDays(time(),-1)) {
            $l_imagem = $conImgAtraso;
            $l_title  = 'Execução não iniciada. Fim previsto superado.';
          } elseif ($l_aviso=='S' && ($l_dias_aviso<=addDays(time(),-1))) {
            $l_imagem = $conImgAviso;
            $l_title  = 'Execução não iniciada. Fim previsto próximo.';
          } else {
            $l_imagem = $conImgNormal;
            $l_title  = 'Execução não iniciada. Prazo final dentro do previsto.';
          } 
        } else {
          if ($l_fim<addDays(time(),-1)) {
            $l_imagem = $conImgStAtraso;
            $l_title  = 'Em execução. Fim previsto superado.';
          } elseif ($l_aviso=='S' && ($l_dias_aviso<=addDays(time(),-1))) {
            $l_imagem = $conImgStAviso;
            $l_title  = 'Em execução. Fim previsto próximo.';
          } else {
            $l_imagem = $conImgStNormal;
            $l_title  = 'Em execução. Prazo final dentro do previsto.';
          } 
        }
      } else {
        if ($l_fim<Nvl($l_fim_real,$l_fim)) {
          $l_imagem = $conImgOkAtraso;
          $l_title  = 'Execução concluída após a data prevista.';
        } elseif ($l_fim>Nvl($l_fim_real,$l_fim)) {
          $l_imagem = $conImgOkAcima;
          $l_title  = 'Execução concluída antes da data prevista.';
        } else {
          $l_imagem = $conImgOkNormal;
          $l_title  = 'Execução concluída na data prevista.';
        } 
      } 
    }
 
    if ($l_imagem!='') {
      $l_string = '           <img src="'.$l_imagem.'" title="'.$l_title.'" border=0 width=10 heigth=10>';
    }
  }

  return $l_string;
}

// =========================================================================
// Exibe ícone da solicitação para geo-referenciamento
// -------------------------------------------------------------------------
function ExibeIconeSolic($l_tipo,$l_inicio,$l_fim,$l_inicio_real,$l_fim_real,$l_aviso,$l_dias_aviso,$l_tramite, $l_perc, $l_legenda=0, $l_restricao=null) {
  extract($GLOBALS);
  $l_imagem = '';
  $l_tipo = strtoupper($l_tipo);
    if ($l_tipo=='ETAPA') {
      // Etapas de projeto
      if ($l_perc<100) {
        if (nvl($l_inicio_real,'')=='') {
          if ($l_fim<addDays(time(),-1)) {
            $l_imagem = $conIcoAtraso;
          } elseif (((time()-$l_inicio)/($l_fim-$l_inicio+1))*100>$l_perc) {
            $l_imagem = $conIcoAviso;
          } else {
            $l_imagem = $conIcoNormal;
          } 
        } else {
          if ($l_fim<addDays(time(),-1)) {
            $l_imagem = $conIcoStAtraso;
          } elseif (((time()-$l_inicio)/($l_fim-$l_inicio+1))*100>$l_perc) {
            $l_imagem = $conIcoStAviso;
          } else {
            $l_imagem = $conIcoStNormal;
          } 
        }
      } else {
        if ($l_fim<Nvl($l_fim_real,$l_fim)) {
          $l_imagem = $conIcoOkAtraso;
        } elseif ($l_fim>Nvl($l_fim_real,$l_fim)) {
          $l_imagem = $conIcoOkAcima;
        } else {
          $l_imagem = $conIcoOkNormal;
        } 
      } 
    } elseif (substr($l_tipo,0,2)=='GC') {
      // Contratos e convênios
      if ($l_tramite!='AT' && $l_tramite!='CR') {
        if ($l_tramite=='CA') {
          $l_imagem = $conIcoCancel;
        } elseif ($l_tramite=='CI') {
          if ($l_fim<addDays(time(),-1)) {
            $l_imagem = $conIcoAtraso;
          } elseif ($l_aviso=='S' && $l_dias_aviso<=time()) {
            $l_imagem = $conIcoAviso;
          } else {
            $l_imagem = $conIcoNormal;
          } 
        } elseif ($l_tramite=='ER') {
          $l_imagem = $conIcoStAcima;
        } else {
          if ($l_fim<addDays(time(),-1)) {
            $l_imagem = $conIcoStAtraso;
          } elseif ($l_aviso=='S' && $l_dias_aviso<=time()) {
            $l_imagem = $conIcoStAviso;
          } else {
            $l_imagem = $conIcoStNormal;
          } 
        }
      } else {
        if ($l_fim<Nvl($l_fim_real,$l_fim)) {
          $l_imagem = $conIcoOkAtraso;
        } elseif ($l_fim>Nvl($l_fim_real,$l_fim)) {
          $l_imagem = $conIcoOkAcima;
        } else {
          $l_imagem = $conIcoOkNormal;
        } 
      } 
    } elseif (substr($l_tipo,0,2)=='GD') {
      // Tarefas, demandas eventuais e demandas de triagem
      if ($l_tramite!='AT') {
        if ($l_tramite=='CA') {
          $l_imagem = $conIcoCancel;
        } elseif ($l_tramite=='CI' || $l_restricao=='SEMEXECUCAO') {
          if ($l_fim<addDays(time(),-1)) {
            $l_imagem = $conIcoAtraso;
          } elseif ($l_aviso=='S' && $l_dias_aviso<=time()) {
            $l_imagem = $conIcoAviso;
          } else {
            $l_imagem = $conIcoNormal;
          } 
        } else {
          if ($l_fim<addDays(time(),-1)) {
            $l_imagem = $conIcoStAtraso;
          } elseif ($l_aviso=='S' && $l_dias_aviso<=time()) {
            $l_imagem = $conIcoStAviso;
          } else {
            $l_imagem = $conIcoStNormal;
          } 
        }
      } else {
        if ($l_fim<Nvl($l_fim_real,$l_fim)) {
          $l_imagem = $conIcoOkAtraso;
        } elseif ($l_fim>Nvl($l_fim_real,$l_fim)) {
          $l_imagem = $conIcoOkAcima;
        } else {
          $l_imagem = $conIcoOkNormal;
        } 
      } 
    } elseif (substr($l_tipo,0,2)=='FN') {
      // Tarefas e demandas eventuais
      if ($l_tramite!='AT') {
        if ($l_tramite=='CA') {
          $l_imagem = $conIcoCancel;
        } elseif ($l_tramite=='CI') {
          if ($l_fim<addDays(time(),-1)) {
            $l_imagem = $conIcoAtraso;
          } elseif ($l_aviso=='S' && ($l_dias_aviso<=addDays(time(),-1))) {
            $l_imagem = $conIcoAviso;
          } else {
            $l_imagem = $conIcoNormal;
          } 
        } else {
          if ($l_fim<addDays(time(),-1)) {
            $l_imagem = $conIcoStAtraso;
          } elseif ($l_aviso=='S' && $l_dias_aviso<=time()) {
            $l_imagem = $conIcoStAviso;
          } else {
            $l_imagem = $conIcoStNormal;
          } 
        }
      } else {
        if ($l_fim<Nvl($l_fim_real,$l_fim)) {
          $l_imagem = $conIcoOkAtraso;
        } elseif ($l_fim>Nvl($l_fim_real,$l_fim)) {
          $l_imagem = $conIcoOkAcima;
        } else {
          $l_imagem = $conIcoOkNormal;
        } 
      } 
    } elseif (substr($l_tipo,0,2)=='SR') {
      // Tarefas
      if ($l_tramite!='AT') {
        if ($l_tramite=='CA') {
          $l_imagem = $conIcoCancel;
        } elseif ($l_tramite=='CI') {
          if ($l_fim<time()) {
            $l_imagem = $conIcoAtraso;
          } elseif ($l_aviso=='S' && $l_dias_aviso<=time()) {
            $l_imagem = $conIcoAviso;
          } else {
            $l_imagem = $conIcoNormal;
          } 
        } else {
          if ($l_fim<time()) {
            $l_imagem = $conIcoStAtraso;
          } elseif ($l_aviso=='S' && $l_dias_aviso<=time()) {
            $l_imagem = $conIcoStAviso;
          } else {
            $l_imagem = $conIcoStNormal;
          } 
        }
      } else {
        if ($l_fim<Nvl($l_fim_real,$l_fim)) {
          $l_imagem = $conIcoOkAtraso;
        } elseif ($l_fim>Nvl($l_fim_real,$l_fim)) {
          $l_imagem = $conIcoOkAcima;
        } else {
          $l_imagem = $conIcoOkNormal;
        } 
      } 
    } elseif (substr($l_tipo,0,2)=='PJ') {
      // Projetos
      if ($l_tramite!='AT') {
        if ($l_tramite=='CA') {
          $l_imagem = 'project_red';
        } elseif ($l_tramite=='CI') {
          if ($l_fim<addDays(time(),-1)) {
            $l_imagem = 'project_red';
          } elseif ($l_aviso=='S' && ($l_dias_aviso<=addDays(time(),-1))) {
            $l_imagem = 'project_yellow';
          } else {
            $l_imagem = 'project_green';
          } 
        } else {
          if ($l_fim<addDays(time(),-1)) {
            $l_imagem = 'project_red';
          } elseif ($l_aviso=='S' && ($l_dias_aviso<=addDays(time(),-1))) {
            $l_imagem = 'project_yellow';
          } else {
            $l_imagem = 'project_green';
          } 
        }
      } else {
        if ($l_fim<Nvl($l_fim_real,$l_fim)) {
          $l_imagem = 'project_red';
        } elseif ($l_fim>Nvl($l_fim_real,$l_fim)) {
          $l_imagem = 'project_yellow';
        } else {
          $l_imagem = 'project_green';
        } 
      } 
    } elseif (substr($l_tipo,0,2)=='CL') {
      // Projetos
      if ($l_tramite!='AT') {
        if ($l_tramite=='CA') {
          $l_imagem = $conIcoCancel;
        } elseif ($l_tramite=='CI') {
          if ($l_fim<addDays(time(),-1)) {
            $l_imagem = $conIcoAtraso;
          } elseif ($l_aviso=='S' && ($l_dias_aviso<=addDays(time(),-1))) {
            $l_imagem = $conIcoAviso;
          } else {
            $l_imagem = $conIcoNormal;
          } 
        } else {
          if ($l_fim<addDays(time(),-1)) {
            $l_imagem = $conIcoStAtraso;
          } elseif ($l_aviso=='S' && ($l_dias_aviso<=addDays(time(),-1))) {
            $l_imagem = $conIcoStAviso;
          } else {
            $l_imagem = $conIcoStNormal;
          } 
        }
      } else {
        if ($l_fim<Nvl($l_fim_real,$l_fim)) {
          $l_imagem = $conIcoOkAtraso;
        } elseif ($l_fim>Nvl($l_fim_real,$l_fim)) {
          $l_imagem = $conIcoOkAcima;
        } else {
          $l_imagem = $conIcoOkNormal;
        } 
      } 
    } elseif (substr($l_tipo,0,2)=='PD') {
      // Viagens
      if ($l_tramite=='CA') {
        $l_imagem = $conIcoCancel;
      } elseif ($l_fim<addDays(time(),-1)) {
        if ($l_tramite=='AT') {
          $l_imagem = $conIcoOkNormal;
        } elseif ($l_tramite!='EE') {
          $l_imagem = $conIcoOkAtraso;
        } else {
          $l_imagem = $conIcoOkAcima;
        } 
      } elseif ($l_inicio>time()) {
        if ($l_dias_aviso<=time()) {
          $l_imagem = $conIcoAviso;
        } else {
          $l_imagem = $conIcoNormal;
        } 
      } else {
        if ($l_tramite!='EE') {
          $l_imagem = $conIcoOkAtraso;
        } else {
          $l_imagem = $conIcoStNormal;
        }
      }
    } elseif (substr($l_tipo,0,2)=='PE') {
      // Projetos
      if ($l_tramite!='AT') {
        if ($l_tramite=='CA') {
          $l_imagem = $conIcoCancel;
        } elseif ($l_tramite=='CI') {
          if ($l_fim<addDays(time(),-1)) {
            $l_imagem = $conIcoAtraso;
          } elseif ($l_aviso=='S' && ($l_dias_aviso<=addDays(time(),-1))) {
            $l_imagem = $conIcoAviso;
          } else {
            $l_imagem = $conIcoNormal;
          } 
        } else {
          if ($l_fim<addDays(time(),-1)) {
            $l_imagem = $conIcoStAtraso;
          } elseif ($l_aviso=='S' && ($l_dias_aviso<=addDays(time(),-1))) {
            $l_imagem = $conIcoStAviso;
          } else {
            $l_imagem = $conIcoStNormal;
          } 
        }
      } else {
        if ($l_fim<Nvl($l_fim_real,$l_fim)) {
          $l_imagem = $conIcoOkAtraso;
        } elseif ($l_fim>Nvl($l_fim_real,$l_fim)) {
          $l_imagem = $conIcoOkAcima;
        } else {
          $l_imagem = $conIcoOkNormal;
        } 
      } 
  }

  return $l_imagem;
}
// =========================================================================
// Montagem da URL com os parâmetros de filtragem quando o for UPLOAD
// -------------------------------------------------------------------------
function MontaFiltroUpload($p_Form) {
  extract($GLOBALS);
  $l_string='';
  foreach ($p_Form as $l_Item) {
    if (substr($l_item,0,2)=="p_" && $l_item->value>'') {
      $l_string .= "&".$l_Item."=".$l_item->value;
    }
  }
  return $l_string;
}

// =========================================================================
// Rotina que monta número de ordem da etapa do projeto
// -------------------------------------------------------------------------
function MontaOrdemEtapa($l_chave) {
  extract($GLOBALS);
  include_once($w_dir_volta.'classes/sp/db_getEtapaDataParents.php');
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
// Rotina que monta o código da especificacao
// -------------------------------------------------------------------------
function MontaOrdemEspec($l_chave) {
  extract($GLOBALS);
  $RSQuery = db_getEspecOrdem::getInstanceOf($dbms, $l_chave);
  $w_texto = '';
  $w_contaux = 0;
  foreach($RSQuery as $row) {
    $w_contaux = $w_contaux+1;
    if ($w_contaux==1) {
      $w_texto = f($row,'codigo').'.'.$w_texto;
    } else {
      $w_texto = f($row,'codigo').'.'.$w_texto;
    }
  }
  return substr($w_texto,0,strlen($w_texto)-1);
}

// =========================================================================
// Converte CFLF para <BR>
// -------------------------------------------------------------------------
function CRLF2BR($expressao) { 
  $result = '';
  if (Nvl($expressao,'')=='') { 
    return ''; 
  } else { 
    $result = $expressao;
    if (false!==strpos($result,chr(10).chr(13))) $result = str_replace(chr(10).chr(13),'<br>',$result); 
    if (false!==strpos($result,chr(13).chr(10))) $result = str_replace(chr(13).chr(10),'<br>',$result); 
    if (false!==strpos($result,chr(13)))         $result = str_replace(chr(13),'<br>',$result); 
    if (false!==strpos($result,chr(10)))         $result = str_replace(chr(10),'<br>',$result); 
    //return str_replace('<br><br>','<br>',htmlentities($result)); 
    return str_replace('<br><br>','<br>',$result); 
  } 
}

// =========================================================================
// Trata valores nulos
// -------------------------------------------------------------------------
function Nvl($expressao,$valor) { if ((!isset($expressao)) || $expressao==='') { return $valor; } else { return $expressao; } }

// =========================================================================
// Retorna valores nulos se chegar cadeia vazia
// -------------------------------------------------------------------------
function Tvl($expressao) { if (!isset($expressao) || $expressao==='' || $expressao===false) { return  null; } else { return $expressao; } }

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
// Verifica se um arquivo ou diretório existe, se é possível a leitura 
// e se é possível a escrita
// -------------------------------------------------------------------------
function testFile($l_erro, $l_raiz, $l_leitura = false, $l_escrita = false) {
  if (!file_exists($l_raiz)) {
    $l_erro = 'inexistente';
    return false;
  } elseif (!is_readable($l_raiz)) {
    $l_erro = 'sem permissão de leitura';
    return false;
  } elseif (!is_writable($l_raiz)) {
    $l_erro = 'sem permissão de escrita';
    return false;
  }
  return true;
}

// =========================================================================
// Montagem de URL a partir da sigla da opção do menu
// -------------------------------------------------------------------------
function MontaURL($p_sigla) {
  extract($GLOBALS);
  include_once($w_dir_volta.'classes/sp/db_getLinkData.php');
  $RS_MontaURL = db_getLinkData::getInstanceOf($dbms, $_SESSION['P_CLIENTE'], $p_sigla);
  $l_ImagemPadrao='images/Folder/SheetLittle.gif';
  if (count($RS_MontaURL)<=0) return '';
  else {
    if (nvl(f($RS_MontaURL,'imagem'),'-')!='-') {
      $l_Imagem=f($RS_MontaURL,'imagem');
    } else {
      $l_Imagem=$l_ImagemPadrao;
    }
    return f($RS_MontaURL,'link')."&P1=".f($RS_MontaURL,'p1')."&P2=".f($RS_MontaURL,'p2')."&P3=".f($RS_MontaURL,'p3')."&P4=".f($RS_MontaURL,'p4')."&TP=<img src=".$l_Imagem." BORDER=0>".f($RS_MontaURL,'nome')."&SG=".f($RS_MontaURL,'sigla');
  }
}

// =========================================================================
// Montagem de cabeçalho padrão de formulário
// -------------------------------------------------------------------------
function AbreForm($p_Name,$p_Action,$p_Method,$p_onSubmit,$p_Target,$p_P1,$p_P2,$p_P3,$p_P4,$p_TP,$p_SG,$p_R,$p_O, $p_retorno=null) {
  $l_html = '';
  if (!isset($p_Target)) {
     $l_html .= '<FORM action="'.$p_Action.'" method="'.$p_Method.'" NAME="'.$p_Name.'" onSubmit="'.$p_onSubmit.'">';
  } else {
     $l_html .= '<FORM action="'.$p_Action.'" method="'.$p_Method.'" NAME="'.$p_Name.'" onSubmit="'.$p_onSubmit.'" target="'.$p_Target.'">';
  }
  if (nvl($p_P1,'')!='') $l_html .= chr(13).'<INPUT TYPE="hidden" NAME="P1" VALUE="'.$p_P1.'">';
  if (nvl($p_P2,'')!='') $l_html .= chr(13).'<INPUT TYPE="hidden" NAME="P2" VALUE="'.$p_P2.'">';
  if (nvl($p_P3,'')!='') $l_html .= chr(13).'<INPUT TYPE="hidden" NAME="P3" VALUE="'.$p_P3.'">';
  if (nvl($p_P4,'')!='') $l_html .= chr(13).'<INPUT TYPE="hidden" NAME="P4" VALUE="'.$p_P4.'">';
  if (nvl($p_TP,'')!='') $l_html .= chr(13).'<INPUT TYPE="hidden" NAME="TP" VALUE="'.$p_TP.'">';
  if (nvl($p_SG,'')!='') $l_html .= chr(13).'<INPUT TYPE="hidden" NAME="SG" VALUE="'.$p_SG.'">';
  if (nvl($p_R,'')!='')  $l_html .= chr(13).'<INPUT TYPE="hidden" NAME="R"  VALUE="'.$p_R.'">';
  if (nvl($p_O,'')!='')  $l_html .= chr(13).'<INPUT TYPE="hidden" NAME="O"  VALUE="'.$p_O.'">';
  
  if (nvl($p_retorno,'')=='') ShowHtml($l_html); else return $l_html;
}

// =========================================================================
// Montagem de campo do tipo radio com padrão Não
// -------------------------------------------------------------------------
function MontaRadioNS($label,$chave,$campo,$hint=null,$restricao=null,$atributo=null) {
  extract($GLOBALS);
  ShowHTML('          <td'.((nvl($hint,'')!='') ? ' TITLE="'.$hint.'"': '').'>');
  if (Nvl($label,'')>'') { ShowHTML($label.'</b><br>'); }
  if ($chave=='S') {
     ShowHTML('              <input '.$w_Disabled.' type="radio" name="'.$campo.'" value="S" checked '.$atributo.'> Sim <input '.$w_Disabled.' type="radio" name="'.$campo.'" value="N" '.$atributo.'> Não');
  } else {
     ShowHTML('              <input '.$w_Disabled.' type="radio" name="'.$campo.'" value="S" '.$atributo.'> Sim <input '.$w_Disabled.' type="radio" name="'.$campo.'" value="N" checked '.$atributo.'> Não');
  }
}

// =========================================================================
// Montagem de campo do tipo radio com padrão Sim
// -------------------------------------------------------------------------
function MontaRadioSN($label,$chave,$campo,$hint=null,$restricao=null,$atributo=null) {
  extract($GLOBALS);
  ShowHTML('          <td'.((nvl($hint,'')!='') ? ' TITLE="'.$hint.'"': '').'>');
  if (Nvl($label,'')>'') { ShowHTML($label.'</b><br>'); }
  if ($chave=='N') {
     ShowHTML('              <input '.$w_Disabled.' type="radio" name="'.$campo.'" value="S" '.$atributo.'> Sim <input '.$w_Disabled.' type="radio" name="'.$campo.'" value="N" checked '.$atributo.'> Não');
  } else {
     ShowHTML('              <input '.$w_Disabled.' type="radio" name="'.$campo.'" value="S" checked '.$atributo.'> Sim <input '.$w_Disabled.' type="radio" name="'.$campo.'" value="N" '.$atributo.'> Não');
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
function FormatNumber($p_valor, $p_decimais=2) {
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
     include_once($w_dir_volta.'classes/sp/db_getMenuCode.php');
     $l_RS = db_getMenuCode::getInstanceOf($dbms, $p_cliente, $p_sigla);
     foreach($l_RS as $l_row) {
       if (f($l_row,'ativo')=='S') { return f($l_row,'sq_menu'); break; }
     }
     return null;
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
  extract($GLOBALS);
  include_once($w_dir_volta.'classes/sp/db_getGestor.php');
  $l_acesso = db_getGestor::getInstanceOf($dbms,$p_solicitacao, $p_usuario);
  return $l_acesso;
}

// =========================================================================
// Função que retorna valor maior que 0 se o usuário informado tem acesso à
// opção e trâmite indicados
// -------------------------------------------------------------------------
function RetornaMarcado($p_menu,$p_usuario,$p_endereco,$p_tramite) {
  extract($GLOBALS);
  include_once($w_dir_volta.'classes/sp/db_getMarcado.php');
  $l_acesso = db_getMarcado::getInstanceOf($dbms,$p_menu, $p_usuario,$p_endereco,$p_tramite);
  return $l_acesso;
}

// =========================================================================
// Função que retorna S/N indicando se o usuário informado é gestor do módulo 
// a opção do menu pertence
// -------------------------------------------------------------------------
function RetornaModMaster($p_cliente, $p_usuario, $p_menu) {
  include_once($w_dir_volta.'classes/sp/db_getModMaster.php');
  extract($GLOBALS);
  $l_RS = db_getModMaster::getInstanceOf($dbms,$p_cliente, $p_usuario, $p_menu);
  if (count($l_RS)==0) {
    return 'N';
  } else {
    foreach($l_RS as $l_row) {$l_RS = $l_row; break;}
    return f($l_RS,'gestor_modulo');
  }
}

// =========================================================================
// Rotina que encerra a sessão e fecha a janela do SIW
// -------------------------------------------------------------------------
function EncerraSessao() {
  extract($GLOBALS);
  ScriptOpen('JavaScript');
  ShowHTML(' alert("Tempo máximo de inatividade atingido! Autentique-se novamente."); ');
  ShowHTML(' top.location.href=\'' . $conDefaultPath . '\';');
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
function EnviaMail($w_subject,$w_mensagem,$w_recipients,$w_attachments=null) {
  extract($GLOBALS);
  
  include_once($w_dir_volta.'classes/mail/email_message.php');
  include_once($w_dir_volta.'classes/mail/smtp_message.php');
  include_once($w_dir_volta.'classes/mail/smtp.php');
  include_once($w_dir_volta.'classes/sp/db_getCustomerData.php');

  // Recupera informações para configurar o remetente da mensagem e o serviço de entrega
  $RS_Cliente = db_getCustomerData::getInstanceOf($dbms, $_SESSION['P_CLIENTE']);

  $subject                  = $w_subject;
  $from_name                = f($RS_Cliente,'siw_email_nome');
  $from_address             = f($RS_Cliente,'siw_email_conta');
  $reply_name               = $from_name;
  $reply_address            = $from_address;
  $reply_address            = $from_address;
  $error_delivery_name      = $from_name;
  $error_delivery_address   = $from_address;

  $email_message = new smtp_message_class;

  $email_message->localhost = $_SERVER['HOSTNAME'];
  $email_message->smtp_host = f($RS_Cliente,'smtp_server');
  $email_message->smtp_port=25;
  $email_message->smtp_ssl=0; /* Use SSL to connect to the SMTP server. Gmail requires SSL */
  $email_message->smtp_direct_delivery=0; /* Deliver directly to the recipients destination SMTP server */
  $email_message->smtp_user=''; /* authentication user name */
  $email_message->smtp_realm='';  /* authentication realm or Windows domain when using NTLM authentication */
  $email_message->smtp_workstation=''; /* authentication workstation name when using NTLM authentication */
  $email_message->smtp_password=''; /* authentication password */
  $email_message->smtp_debug=0; /* Output dialog with SMTP server */
  $email_message->smtp_html_debug=0; /* set this to 1 to make the debug output appear in HTML */

  /* if you need POP3 authetntication before SMTP delivery,
  * specify the host name here. The smtp_user and smtp_password above
  * should set to the POP3 user and password*/
  $email_message->smtp_pop3_auth_host='';

  /* In directly deliver mode, the DNS may return the IP of a sub-domain of
   * the default domain for domains that do not exist. If that is your
   * case, set this variable with that sub-domain address. */
  $email_message->smtp_exclude_address="";

  /* If you use the direct delivery mode and the GetMXRR is not functional,
   * you need to use a replacement function. */
  /*
  $_NAMESERVERS=array();
  include("rrcompat.php");
  $email_message->smtp_getmxrr="_getmxrr";
  */

  if (strpos($w_recipients,';')===false) $w_recipients .= ';';
  $l_recipients = explode(';',$w_recipients);
  $l_cont = 0;
  foreach($l_recipients as $k => $v) { 
    if (nvl($v,'')!='') {
      if (strpos($v,'|')!==false) {
        $rec = explode('|',$v);
        $rec_address = trim($rec[0]);
        $rec_name    = trim($rec[1]);
      } else {
        $rec_address = trim($v);
        $rec_name    = trim($v);
      }
      // Efvita a repetição de nomes
      if (count($l_dest[$rec_address])==0) {
        $l_dest[$rec_address] = $rec_name; 
        $l_cont++;
      }
    }
  }
  $i = 0;
  if (is_array($l_dest)) {
    foreach($l_dest as $k => $v) { 
      if ($i==0) {
        // O primeiro destinatário será colocado como "To"
        $email_message->SetEncodedEmailHeader("To",$k,$v);
        unset($l_dest[$k]);
      } elseif ($i==1 && $l_cont==2) {
        // Se só tiver mais um destinatário, coloca header único
        $email_message->SetEncodedEmailHeader("Cc",$k,$v);
        break;
      } else {
        // Se tiver mais um destinatário, além do principal, coloca headers múltiplos
        $email_message->SetMultipleEncodedEmailHeader("Cc",$l_dest);
        break;
      }
      $i++;
    }
  }
  if (is_array($w_attachments)) {
    foreach($w_attachments as $l_attach) $email_message->AddFilePart($l_attach);
  }
  $email_message->SetEncodedEmailHeader('From',$from_address,$from_name);
  $email_message->SetEncodedEmailHeader("Reply-To",$reply_address,$reply_name);
  // Set the Return-Path header to define the envelope sender address to which bounced messages are delivered.
  // If you are using Windows, you need to use the smtp_message_class to set the return-path address.
  if(defined("PHP_OS") && strcmp(substr(PHP_OS,0,3),"WIN")) $email_message->SetHeader("Return-Path",$error_delivery_address);
  $email_message->SetEncodedEmailHeader('Errors-To','desenv@sbpi.com.br','SBPI Suporte');
  $email_message->SetEncodedHeader("Subject",$subject);
  $email_message->AddQuotedPrintableHTMLPart($w_mensagem,'',$html_part);

/*
  // It is strongly recommended that when you send HTML messages,
  // also provide an alternative text version of HTML page,
  // even if it is just to say that the message is in HTML,
  // because more and more people tend to delete HTML only
  // messages assuming that HTML messages are spam.
  $text_message='Esta é uma mensagem no formato HTML. Favor usar um programa capaz de ler mensagens nesse formato';
  $email_message->CreateQuotedPrintableTextPart($email_message->WrapText($text_message),'',$text_part);

  // The complete HTML parts are gathered in a single multipart/related part.
  $related_parts=array(
    $html_part,
    $image_part,
    $background_image_part
  );
  $email_message->CreateRelatedMultipart($related_parts,$html_parts);

  // Multiple alternative parts are gathered in multipart/alternative parts.
  // It is important that the fanciest part, in this case the HTML part,
  // is specified as the last part because that is the way that HTML capable
  // mail programs will show that part and not the text version part.
  $alternative_parts=array(
    $text_part,
    $html_parts
  );
  $email_message->AddAlternativeMultipart($alternative_parts);
*/

  //send your e-mail
  if ($conEnviaMail) {
    $error = $email_message->Send();
    if (strcmp($error,'')) {
      // Solaris (SunOS) sempre retorna falso, mesmo enviando a mensagem.
      if (strtoupper(PHP_OS)!='SUNOS') {
        return 'ERRO: ocorreu algum erro no envio da mensagem.\\SMTP ['.f($RS_Cliente,'smtp_server').']\nPorta ['.ini_get('smtp_port').']\nConta ['.f($RS_Cliente,'siw_email_conta').']\n'.$error;
      } else {
        return null;
      }
    } else {
       return null;
    }
  }
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
  while((strpos($fsa,"/") ? strpos($fsa,"/")+1 : 0)>0) {
    $fsa=substr($fsa,(strpos($fsa,"/") ? strpos($fsa,"/")+1 : 0)+1-1,strlen($fsa));
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
     ShowHTML(' alert("Existem registros vinculados ao que você está excluindo. Exclua-os primeiro.\\n\\n'.substr($Err['message'],0,(strpos($Err['message'],chr(10)) ? strpos($Err['message'],chr(10))+1 : 0)-1).'");');
     ShowHTML(' history.back(1);');
     ScriptClose();
  }
  //elseif (!(strpos($Err['message'],'ORA-02291')===false) || !(strpos($Err['message'],'ORA-02291')===false)) {
     // REGISTRO NÃO ENCONTRADO
  //   ScriptOpen('JavaScript');
  //   ShowHTML(' alert("Registro não encontrado.");');
  //   ShowHTML(' history.back(1);');
  //   ScriptClose();
 // }
  elseif (!(strpos($Err['message'],'ORA-0000x1')===false)) {
     // REGISTRO JÁ EXISTENTE
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Um dos campos digitados já existe no banco de dados e é único.\\n\\n'.substr($Err['message'],0,(strpos($Err['message'],chr(10)) ? strpos($Err['message'],chr(10))+1 : 0)-1).'");');
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
    $w_html .= chr(10).'<head>';
    $w_html .= chr(10).'  <BASEFONT FACE="Arial" SIZE="2">';
    $w_html .= chr(10).'</head>';
    $w_html .= chr(10).'<body BGCOLOR="#FF5555">';
    $w_html .= chr(10).'<CENTER><H2>ATENÇÃO</H2></CENTER>';
    $w_html .= chr(10).'<BLOCKQUOTE>';
    $w_html .= chr(10).'<P ALIGN="JUSTIFY">Erro não previsto. <b>Uma cópia desta tela foi enviada por e-mail para os responsáveis pela correção. Favor tentar novamente mais tarde.</P>';
    $w_html .= chr(10).'<TABLE BORDER="2" BGCOLOR="#FFCCCC" CELLPADDING="5"><TR><TD><FONT COLOR="#000000">';
    $w_html .= chr(10).'<DL><DT>Data e hora da ocorrência: <FONT FACE="courier">'.date('d/m/Y, h:i:s').'<br><br></font></DT>';
    $w_html .= chr(10).'<DT>Descrição:<DD><FONT FACE="courier">'.crlf2br($Err['message']).'<br><br></font>';
    $w_html .= chr(10).'<DT>Arquivo:<DD><FONT FACE="courier">'.$file.', linha: '.$line.'<br><br></font>';
    //$w_html .= chr(10).'<DT>Objeto:<DD><FONT FACE="courier">'.$object.'<br><br></font>';

    $w_html .= chr(10).'<DT>Comando em execução:<blockquote> <FONT FACE="courier">'.nvl($Err['sqltext'],'nenhum').'</blockquote></font></DT>';
    if (is_array($params)) {
      $w_html .= "<DT>Valores dos parâmetros:<DD><FONT FACE=\"courier\" size=1>";
      foreach ($params as $w_chave => $w_valor) {
        $w_html .= chr(10).$w_chave.' ['.$w_valor[0].']<br>';
      }
    }
    $w_html .= "   <br><br></font>";

    $w_html .= chr(10).'<DT>Variáveis de servidor:<DD><FONT FACE="courier" size=1>';
    $w_html .= chr(10).' SCRIPT_NAME => ['.$_SERVER['SCRIPT_NAME'].']<br>';
    $w_html .= chr(10).' SERVER_NAME => ['.$_SERVER['SERVER_NAME'].']<br>';
    $w_html .= chr(10).' SERVER_PORT => ['.$_SERVER['SERVER_PORT'].']<br>';
    $w_html .= chr(10).' SERVER_PROTOCOL => ['.$_SERVER['SERVER_PROTOCOL'].']<br>';
    $w_html .= chr(10).' HTTP_ACCEPT_LANGUAGE => ['.$_SERVER['HTTP_ACCEPT_LANGUAGE'].']<br>';
    $w_html .= chr(10).' HTTP_USER_AGENT => ['.$_SERVER['HTTP_USER_AGENT'].']<br>';
    $w_html .= chr(10).'</DT>';
    $w_html .= chr(10).'   <br><br></font>';

    $w_html .= chr(10).'<DT>Dados da querystring:';
    foreach($_GET as $chv => $vlr) { $w_html .= chr(10).'<DD><FONT FACE="courier" size=1>'.$chv.' => ['.$vlr.']<br>'; }

    $w_html .= chr(10).'</DT>';
    $w_html .= chr(10).'<DT>Dados do formulário:';
    foreach($_POST as $chv => $vlr) { if (strtolower($chv)!='w_assinatura') $w_html .= chr(10).'<DD><FONT FACE="courier" size=1>'.$chv.' => ['.$vlr.']<br>'; }

    $w_html .= chr(10).'</DT>';
    $w_html .= chr(10).'   <br><br></font>';
    $w_html .= chr(10).'</DT>';
    $w_html .= chr(10).'<DT>Variáveis de sessão:<DD><FONT FACE="courier" size=1>';
    foreach($_SESSION as $chv => $vlr) { if (strpos(strtoupper($chv),'SENHA') !== true) { $w_html .= chr(10).$chv.' => ['.$vlr.']<br>'; } }
    $w_html .= chr(10).'</DT>';
    $w_html .= chr(10).'   <br><br></font>';
    $w_html .= chr(10).'</FONT></TD></TR></TABLE><BLOCKQUOTE>';
    $w_html .= '</body></html>';

    $w_resultado = EnviaMail('ERRO '.$conSgSistema,$w_html,'desenv@sbpi.com.br');
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
  	ShowHTML('<LINK  media=screen href="'.$conFileVirtual.$l_cliente.'/css/estilo.css" type=text/css rel=stylesheet>');
    ShowHTML('<LINK media=print href="'.$conFileVirtual.$l_cliente.'/css/print.css" type=text/css rel=stylesheet>');
    ShowHTML('<SCRIPT language=javascript src="'.$conFileVirtual.$l_cliente.'/js/scripts.js" type=text/javascript> ');
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
  ShowHTML('    </center>');
  ShowHTML('    </DIV>');
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
     ShowHTML('      <SCRIPT src="'.$conFileVirtual.$w_cliente.'/js/newcssmenu.js" type=text/javascript></SCRIPT>');
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
              ShowHTML('              <LI><A href="#"><IMG height=12 alt=">" src="'.$conFileVirtual.$w_cliente.'/img/arrows.gif" width=8> '.f(row1,'nome').'</A> ');
              ShowHTML('              <UL class=menu id=menu'.$l_cont.'_'.$l_cont1.'>');
              $l_cont2=0;
              $l_RS2 = db_getLinkDataUser::getInstanceOf($dbms,$_SESSION['P_CLIENTE'], $_SESSION['SQ_PESSOA'], f(row1,'sq_menu'));
              foreach($l_RS2 as $row2) {
                $l_titulo=$l_titulo.' - '.f(row2,'nome');
                if (f(row2,'filho')>0) {
                   $l_cont2=$l_cont2+1;
                   ShowHTML('                <LI><A href="#"><IMG height=12 alt=">" src="'.$conFileVirtual.$w_cliente.'/img/arrows.gif" width=8> '.f(row2,'nome').'</A> ');
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
  if (isset($rs[Strtolower($fld)])) {
    if (strpos(strtoupper($rs[Strtoupper($fld)]),'/SCRIPT')!==false) {
      return str_replace('/SCRIPT','/ SCRIPT',strtoupper($rs[Strtolower($fld)]));
    } else return str_replace('.asp','.php',$rs[Strtolower($fld)]);
  } elseif (isset($rs[Strtoupper($fld)])) {
    if (strpos(strtoupper($rs[Strtoupper($fld)]),'/SCRIPT')!==false) {
      return str_replace('/SCRIPT','/ SCRIPT',strtoupper($rs[Strtoupper($fld)]));
    } else return str_replace('.asp','.php',$rs[Strtoupper($fld)]);
  } elseif (isset($rs[$fld])) {
    if (strpos(strtoupper($rs[Strtoupper($fld)]),'/SCRIPT')!==false) {
      return str_replace('/SCRIPT','/ SCRIPT',strtoupper($rs[$fld]));
    } else return str_replace('.asp','.php',$rs[$fld]);
  } else return null;
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
        case 4: return diaSemana(date('l, d/m/y, H:i:s',$w_dt_grade));    break;
        case 5: return date('d/m/y',$w_dt_grade);                         break;
        case 6: return date('d/m/y, H:i:s',$w_dt_grade);                  break;
      }
    } else {
      return $w_dt_grade;
    }
  } else {
    return null;
  }
}

// =========================================================================
// Função que retorna datetime com o primeiro dia da data informada no formato datetime
// -------------------------------------------------------------------------
function first_day($w_valor) {
  extract($GLOBALS);

  $l_valor  = FormataDataEdicao($w_valor);
  $l_mes    = substr($l_valor,3,2);
  $l_ano    = substr($l_valor,6,4);
  return mktime(0,0,0,$l_mes,1,$l_ano);
} 

// =========================================================================
// Função que retorna datetime com o último dia da data informada no formato datetime
// -------------------------------------------------------------------------
function last_day($w_valor) {
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
// Função que traduz os meses do ano de inglês para português
// -------------------------------------------------------------------------
function mesAno($l_data, $l_formato=null) {
  if (nvl($l_data,'')>'') {
    if (nvl($l_formato,'nulo')=='nulo') {
      switch (strtoupper($l_data)) {
        case 'JANUARY':   return 'Janeiro';   break;
        case 'FEBRUARY':  return 'Fevereiro'; break;
        case 'MARCH':     return 'Março';     break;
        case 'APRIL':     return 'Abril';     break;
        case 'MAY':       return 'Maio';      break;
        case 'JUNE':      return 'Junho';     break;
        case 'JULY':      return 'Julho';     break;
        case 'AUGUST':    return 'Agosto';    break;
        case 'SEPTEMBER': return 'Setembro';  break;
        case 'OCTOBER':   return 'Outubro';   break;
        case 'NOVEMBER':  return 'Novembro';  break;
        case 'DECEMBER':  return 'Dezembro';  break;
      }
    } else {
      switch (strtoupper($l_data)) {
        case 'JANUARY':   return 'Jan'; break;
        case 'FEBRUARY':  return 'Fev'; break;
        case 'MARCH':     return 'Mar'; break;
        case 'APRIL':     return 'Abr'; break;
        case 'MAY':       return 'Mai'; break;
        case 'JUNE':      return 'Jun'; break;
        case 'JULY':      return 'Jul'; break;
        case 'AUGUST':    return 'Ago'; break;
        case 'SEPTEMBER': return 'Set'; break;
        case 'OCTOBER':   return 'Out'; break;
        case 'NOVEMBER':  return 'Nov'; break;
        case 'DECEMBER':  return 'Dez'; break;
      }
    }
  } else {
    return null;
  }
}

// =========================================================================
// Monta string html para montagem de calendário do mês informado
// -------------------------------------------------------------------------
function montaCalendario($p_base, $p_mes, $p_datas, $p_cores) {
  extract($GLOBALS,EXTR_PREFIX_SAME,'ex_');
  // Atribui nomes dos meses
  $l_meses[1] = 'Janeiro';  $l_meses[2] = 'Fevereiro'; $l_meses[3] = 'Março';      $l_meses[4] = 'Abril';
  $l_meses[5] = 'Maio';     $l_meses[6] = 'Junho';     $l_meses[7] = 'Julho';      $l_meses[8] = 'Agosto';
  $l_meses[9] = 'Setembro'; $l_meses[10] = 'Outubro';  $l_meses[11] = 'Novembro';  $l_meses[12] = 'Dezembro';

  // Atribui quantidade de dias em cada mês
  $l_qtd[1] = 31; $l_qtd[2] = 28; $l_qtd[3] = 31; $l_qtd[4] = 30;  $l_qtd[5] = 31;  $l_qtd[6] = 30;
  $l_qtd[7] = 31; $l_qtd[8] = 31; $l_qtd[9] = 30; $l_qtd[10] = 31; $l_qtd[11] = 30; $l_qtd[12] = 31;

  // Atribui sigla para cada dia da semana
  $l_dias[1]  = 'D'; $l_dias[2]  = 'S'; $l_dias[3]  = 'T'; $l_dias[4]  = 'Q';
  $l_dias[5]  = 'Q'; $l_dias[6]  = 'S'; $l_dias[7]  = 'S';

  // Recupera o mês e o ano desejado para montagem do calendário
  $l_mes = substr($p_mes,0,2);
  $l_ano = substr($p_mes,2,4);
   
  // Define cor de fundo padrão para as células de sábado e domingo
  $l_cor_padrao = '#DAEABD';

  // Recupera as datas especiais do ano informado e carrega no array de calendário base
  foreach ($p_base as $row_ano) {
    $l_data   = FormataDataEdicao(f($row_ano,'data_formatada'));
    $x_datas[$l_data] = f($row_ano,'nome').' '.f($row_ano,'nm_expediente');
    $x_cores[$l_data] = $l_cor_padrao;
  } 

  // Define em que dia da semana o mês inicia
  $l_inicio = date('w',toDate('01/'.$l_mes.'/'.$l_ano));

  // Trata o mês de fevereiro anos bissextos
  if (fMod($l_ano,4)==0) $l_qtd[2] = 29;
   
  $l_html  = '<table border=0 cellspacing=1 cellpadding=1>'.$crlf;
  $l_html .= '  <tr><td colspan=7 align="center" bgcolor="'.$l_cor_padrao.'"><b>'.$l_meses[intVal($l_mes)].'/'.$l_ano.'</td></tr>'.$crlf;
  $l_html .= '  <tr align="center">'.$crlf;

  // Monta a linha com a sigla para os dias das semanas
  for ($i = 1; $i <= 7; $i++) $l_html .= '    <td bgcolor="'.$l_cor_padrao.'"><b>'.$l_dias[$i].'</td>'.$crlf;
  $l_html .= '  </tr>'.$crlf;
   
  // Carrega os dias do mês num array que será usado para montagem do calendário, colocando
  // o dia ou um espaço em branco, dependendo do início e do fim do mês
  for ($i = 1; $i <= ($l_inicio); $i++) $l_celulas[$i] = '&nbsp;';
  for ($i = ($l_qtd[intVal($l_mes)]+1); $i <= 42; $i++) $l_celulas[$i] = '&nbsp;';
  for ($i = 1; $i <= ($l_qtd[intVal($l_mes)]); $i++) $l_celulas[($i + $l_inicio)] = $i;
  // Monta o calendário, usando o array $l_celulas
  $l_html .= '  <tr align="center">'.$crlf ;
  for ($i=1; $i<=42; $i++) {
    $l_data = 'x';
    // Se a célula contiver um dia do mês, formata data para busca nos arrays
    if ($l_celulas[$i]!='&nbsp;') $l_data = substr(100+$l_celulas[$i],1,2).'/'.$l_mes.'/'.$l_ano;
    
    // Trata a borda da célula para datas especiais
    $l_borda      = '';
    $l_ocorrencia = '';
    if (isset($x_datas[$l_data])) {
      $l_borda      = ' style="border: 1px solid rgb(0,0,0);"';
      $l_ocorrencia .= $x_datas[$l_data].'\r\n';
    }
    if (isset($p_datas[$l_data])) {
      if ((fMod($i,7)==0) || (fMod($i-1,7)==0) || isset($x_datas[$l_data])) {
        if ($p_datas[$l_data]['dia_util']=='N') $l_ocorrencia .= substr(str_replace($crlf,' ',$p_datas[$l_data]['valor']),0,80).'\r\n';
      } else {
        $l_ocorrencia .= substr(str_replace($crlf,' ',$p_datas[$l_data]['valor']),0,80).'\r\n';
      }
    }
          
    // Trata a cor de fundo da célula
    $l_cor = '';
    if ($i==1 ||($l_celulas[$i]!='&nbsp;' && ((fMod($i,7)==0) || (fMod($i-1,7)==0)))) { 
      // Verifica se a ocorrência deve prevalecer sobre sábados e domingos
      if ($p_cores[$l_data]['dia_util']=='N') {
        $l_cor = ' bgcolor="'.$p_cores[$l_data]['valor'].'"';
      } else {
        $l_cor = ' bgcolor="'.$l_cor_padrao.'"';
      }
    } elseif ($l_celulas[$i]!='&nbsp;') { 
      if (isset($p_cores[$l_data]['valor'])) {
        if (isset($x_datas[$l_data])) {
         if ($p_cores[$l_data]['dia_util']=='N') $l_cor = ' bgcolor="'.$p_cores[$l_data]['valor'].'"';
        } else {
          $l_cor = ' bgcolor="'.$p_cores[$l_data]['valor'].'"';
        }
      }
    }
   
    // Trata a data de hoje
    if ($l_data==formataDataEdicao(time())) {
      if ($l_data==formataDataEdicao(time())) $l_ocorrencia = 'HOJE\r\n'.$l_ocorrencia;
      $l_borda = ' style="border: 2px solid rgb(0,0,0);"';
    }
          
    if ($l_ocorrencia!='') $l_ocorrencia = ' onClick="javascript:alert(\''.$l_ocorrencia.'\')"';

    // Coloca uma célula do calendário
    $l_html .= '    <td'.$l_cor.$l_borda.$l_ocorrencia.'>'.$l_celulas[$i].'</td>'.$crlf;
         
    // Trata a quebra de linha ao final de cada semana
    if (fMod($i,7)==0) {
      $l_html .= '  </tr>'.$crlf;
      // Interrompe a montagem do calendário na última linha que contém datas
      if ($i>$l_qtd[intVal($l_mes)] && $l_celulas[$i+1]=='&nbsp;') {
        break;
      } else { 
        $l_html .= '  <tr align="center">'.$crlf;
      }
    }
     
  }
  $l_html .= '</table>'.$crlf;
   
  // Devolve o calendário montado
  return $l_html;
}

// =========================================================================
// Função para retornar um array com todos os dias de um período
// Recebe o início e o fim do período no formato data
// Todos os elementos do array recebem o valor definido em p_valor
// -------------------------------------------------------------------------
function retornaArrayDias($p_inicio, $p_fim, $p_array, $p_valor, $p_dia_util=null) {
  $l_inicio = date(Ymd,$p_inicio);
  $l_fim    = date(Ymd,$p_fim);
  // Atribui quantidade de dias em cada mês
  $l_qtd[1] = 31; $l_qtd[2] = 28; $l_qtd[3] = 31; $l_qtd[4] = 30;  $l_qtd[5] = 31;  $l_qtd[6] = 30;
  $l_qtd[7] = 31; $l_qtd[8] = 31; $l_qtd[9] = 30; $l_qtd[10] = 31; $l_qtd[11] = 30; $l_qtd[12] = 31;

  // Trata o mês de fevereiro anos bissextos
  if (fMod($l_ano,4)==0) $l_qtd[2] = 29;

  for ($i=$l_inicio; $i<=$l_fim; $i++) {
    $l_ano = substr($i,0,4);
    $l_mes = substr($i,4,2);
    $l_dia = substr($i,6,2);
    if (intVal($l_dia)>$l_qtd[intVal($l_mes)]) {
      if (intVal($l_mes)==12) {
        $i = ($l_ano+1).'0101';
      } else {
        $i = $l_ano.substr((100+intVal($l_mes)+1),1,2).'01';
      }
    }
    $p_array[substr($i,6,2).'/'.substr($i,4,2).'/'.substr($i,0,4)]['valor']=$p_valor;
    $p_array[substr($i,6,2).'/'.substr($i,4,2).'/'.substr($i,0,4)]['dia_util']=$p_dia_util;
  }
  
  return true;
}

// =========================================================================
// Função para retornar array com o tipo do nome e o nome mais adequado para um período de datas
// Recebe o início e o fim do período no formato data
// Devolve array com dois índices: 
//    [TIPO] pode valer ANO, MES_ANO, DIA, OUT
//    [VALOR] tipo=ANO retorna o ano do início informado
//            tipo=MES retorna o nome_mes/ano
//            tipo=DIA retorna datetime do início informado
//            tipo=OUT retorna nulo
// -------------------------------------------------------------------------
function retornaNomePeriodo($p_inicio, $p_fim) {
  if (date(dm,$p_inicio)=='0101' && date(dm,$p_fim)=='3112' && date(Y,$p_inicio)==date(Y,$p_fim)) {
    // se o período compreende totalmente um único ano, devolve o ano
    $p_array['TIPO'] = 'ANO';
    $p_array['VALOR'] = date(Y,$p_inicio);
  } elseif (date(d,$p_inicio)=='01' && last_day($p_inicio)==$p_fim) {
    // se o período compreende um único dia, devolve o dia
    $p_array['TIPO'] = 'MES';
    $p_array['VALOR'] = mesAno(date(F,$p_inicio),'resumido').'/'.date(y,$p_inicio);
  } elseif ($p_inicio==$p_fim) {
    // se o período compreende um único dia, devolve o dia
    $p_array['TIPO'] = 'DIA';
    $p_array['VALOR'] = $p_inicio;
  } else {
    $p_array['TIPO'] = 'OUT';
    $p_array['VALOR'] = null;
  }
  return $p_array;
}

// =========================================================================
// Função para retornar a strig 'Sim' ou 'Não'
// -------------------------------------------------------------------------
function retornaSimNao($chave,$formato=null) {
  extract($GLOBALS);
  if(strtoupper($formato)=='IMAGEM') {
    switch ($chave) {
      case 'S': return '<img src="'.$conImgOkNormal.'" border=0 width=15 height=15 align="center">';  break;
      default:  return '&nbsp;';
    }
  } else {
    switch ($chave) {
      case 'S': return 'Sim';  break;
      case 'N': return 'Não';  break;
      default:  return 'Não';
    }
  }
}

//Limpa Mascara para gravar os dados no banco de dados
function LimpaMascara($Campo) {
   return str_replace(str_replace(str_replace(str_replace(str_replace(str_replace($campo,',',''),';',''),'.',''),'-',''),'/',''),'"','');
}

// Cria a tag Body
function BodyOpen($cProperties) {
   extract($GLOBALS);
   Required();
   $wProperties = $cProperties;
   if (nvl($wProperties,'')!='') {
     if (strpos($wProperties,'"init')!==false) {
       $wProperties = str_replace('init', 'required(); init', $wProperties);
     } elseif (strpos($wProperties,'this.')!==false) {
       $wProperties = str_replace('this.', 'required(); this.', $wProperties);
     } elseif (strpos($wProperties,'"document.')!==false || strpos($wProperties,'=document.')!==false) {
       $wProperties = str_replace('document.', 'required(); document.', $wProperties);
       if (strpos($wProperties,'required()')===false) $wProperties = str_replace('this.focus', 'required(); this.focus', $wProperties);
     } else {
       $wProperties = str_replace('document.', 'required(); document.', $wProperties);
       $wProperties = 'required(); '.$wProperties;
     }
   }
   ShowHTML('<link rel="stylesheet" type="text/css" href="'.$conRootSIW.'classes/menu/xPandMenu.css">');
   if ($_SESSION['P_CLIENTE']=='6761') { ShowHTML('<body Text="'.$conBodyText.'" '.$wProperties.'> '); }
   else {
      ShowHTML('<body Text="'.$conBodyText.'" Link="'.$conBodyLink.'" Alink="'.$conBodyALink.'" '.
            'Vlink="'.$conBodyVLink.'" Bgcolor="'.$conBodyBgColor.'" Background="'.$conBodyBackground.'" ' .
            'Bgproperties="'.$conBodyBgproperties.'" Topmargin="'.$conBodyTopmargin .'" ' .
            'Leftmargin="'.$conBodyLeftmargin.'" '.$wProperties.'> ');
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
  Required();
  $wProperties = $cProperties;
  if (nvl($wProperties,'')!='') {
    $wProperties = str_replace('document.', 'required(); document.', $wProperties);
    if (strpos($wProperties,'required()')===false) $wProperties = str_replace('this.focus', 'required(); this.focus', $wProperties);
  } else {
    $wProperties = ' onLoad=\'required();\' ';
  }
  ShowHTML('<link rel="stylesheet" type="text/css" href="'.$conRootSIW.'classes/menu/xPandMenu.css">');
  ShowHTML('<body Text="'.$conBodyText.'" Link="'.$conBodyLink.'" Alink="'.$conBodyALink.'" '.
  'Vlink="'.$conBodyVLink.'" Background="'.$conBodyBackground.'" '.
  'Bgproperties="'.$conBodyBgproperties.'" Topmargin="'.$conBodyTopmargin.'" '.
  'Leftmargin="'.$conBodyLeftmargin.'" '.$wProperties.'> ');
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

// Cria a tag Body
function BodyOpenWord($cProperties=null) {
  extract($GLOBALS);
  $l_html='';
  $l_html=$l_html.'<link rel="stylesheet" type="text/css" href="'.$conRootSIW.'classes/menu/xPandPrint.css">'.chr(13);
  $l_html=$l_html.'<body Text="'.$conBodyText.'" Link="'.$conBodyLink.'" Alink="'.$conBodyALink.'" '.
    'Vlink="'.$conBodyVLink.'" Bgcolor="'.$conBodyBgcolor.'" Background="'.$conBodyBackground.'" '.
    'Bgproperties="'.$conBodyBgproperties.'" Topmargin="'.$conBodyTopmargin.'" '.
    'Leftmargin="'.$conBodyLeftmargin.'" '.$cProperties.'> '.chr(13);
  return $l_html;
}
?>