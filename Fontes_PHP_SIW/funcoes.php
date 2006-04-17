<?
setlocale(LC_ALL, 'pt_BR');

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
// Declaração inicial para páginas OLE com Word
// -------------------------------------------------------------------------
function headerWord() {
  header("Content-type: "."application/msword");
  ShowHTML("<html xmlns:o=\"urn:schemas-microsoft-com:office:office\" ");
  ShowHTML("xmlns:w=\"urn:schemas-microsoft-com:office:word\" ");
  ShowHTML("xmlns=\"http://www.w3.org/TR/REC-html40\"> ");
  ShowHTML("<head> ");
  ShowHTML("<meta http-equiv=Content-Type content=\"text/html; charset=windows-1252\"> ");
  ShowHTML("<meta name=ProgId content=Word.Document> ");
  ShowHTML("<!--[if gte mso 9]><xml> ");
  ShowHTML(" <w:WordDocument> ");
  ShowHTML("  <w:View>Print</w:View> ");
  ShowHTML("  <w:Zoom>BestFit</w:Zoom> ");
  ShowHTML("  <w:SpellingState>Clean</w:SpellingState> ");
  ShowHTML("  <w:GrammarState>Clean</w:GrammarState> ");
  ShowHTML("  <w:HyphenationZone>21</w:HyphenationZone> ");
  ShowHTML("  <w:Compatibility> ");
  ShowHTML("   <w:BreakWrappedTables/> ");
  ShowHTML("   <w:SnapToGridInCell/> ");
  ShowHTML("   <w:WrapTextWithPunct/> ");
  ShowHTML("   <w:UseAsianBreakRules/> ");
  ShowHTML("  </w:Compatibility> ");
  ShowHTML("  <w:BrowserLevel>MicrosoftInternetExplorer4</w:BrowserLevel> ");
  ShowHTML(" </w:WordDocument> ");
  ShowHTML("</xml><![endif]--> ");
  ShowHTML("<style> ");
  ShowHTML("<!-- ");
  ShowHTML(" /* Style Definitions */ ");
  ShowHTML("@page Section1 ");
  ShowHTML("    {size:11.0in 8.5in; ");
  ShowHTML("    mso-page-orientation:landscape; ");
  ShowHTML("    margin:60.85pt 1.0cm 60.85pt 2.0cm; ");
  ShowHTML("    mso-header-margin:35.4pt; ");
  ShowHTML("    mso-footer-margin:35.4pt; ");
  ShowHTML("    mso-paper-source:0;} ");
  ShowHTML("div.Section1 ");
  ShowHTML("    {page:Section1;} ");
  ShowHTML("--> ");
  ShowHTML("</style> ");
  ShowHTML("</head> ");
  $BodyOpenClean("onLoad=document.focus();");
  ShowHTML("<div class=Section1> ");
}

// =========================================================================
// Montagem do cabeçalho de documentos Word
// -------------------------------------------------------------------------
function CabecalhoWord($p_cliente,$p_titulo,$p_pagina) {
  DB_GetCustomerData($p_cliente);
  ShowHTML("<TABLE WIDTH=\"100%\" BORDER=0>");
  ShowHTML("  <TR>");
  ShowHTML("    <TD ROWSPAN=3><IMG ALIGN=\"LEFT\" SRC=\"".$conFileVirtual.$w_cliente."/img/".$RS['LOGO']."\" width=56 height=67>");
  ShowHTML("    <TD ALIGN=\"RIGHT\"><B><FONT SIZE=5 COLOR=\"#000000\">".$p_titulo."</FONT>");
  ShowHTML("  </TR>");
  ShowHTML("  <TR><TD ALIGN=\"RIGHT\"><B><FONT SIZE=2 COLOR=\"#000000\">".DataHora()."</B></TD></TR>");
  ShowHTML("  <TR><TD ALIGN=\"RIGHT\"><B><FONT SIZE=2 COLOR=\"#000000\">Página: ".$p_pagina."</B></TD></TR>");
  ShowHTML("  <TR><TD colspan=2><HR></td></tr>");
  ShowHTML("</TABLE>");
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
  $RS = DB_GetCustomerData($p_cliente);
  ShowHTML('<TABLE WIDTH="100%" BORDER=0><TR><TD ROWSPAN=2><IMG ALIGN="LEFT" SRC="files\\'.$w_cliente.'\\img\\'.$RS['LOGO'].'><TD ALIGN="RIGHT"><B><FONT SIZE=4 COLOR="#000000">');
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
  DB_GetSolicAcesso($p_solicitacao, $p_usuario, $l_acesso);
  return $l_acesso;
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
  if (strtoupper($p_method)=="GET" || strtoupper($p_method)=="POST") {
    $l_string="";
    if (strtoupper($p_method)!="UL") {
      foreach ($_POST as $l_Item => $l_valor) {
        if (substr($l_Item,0,2)=="p_" && $l_valor>'') {
          if (strtoupper($p_method)=="GET") {
            $l_string=$l_string."&".$l_Item."=".$l_valor;
          }
          elseif (strtoupper($p_method)=="POST") {
            $l_string=$l_string.'<INPUT TYPE="HIDDEN" NAME="'.$l_Item.'" VALUE="'.$l_valor.'">';
          }
        }
      }
      foreach ($_GET as $l_Item => $l_valor) {
        if (substr($l_Item,0,2)=="p_" && $l_valor>'') {
          if (strtoupper($p_method)=="GET") {
            $l_string=$l_string."&".$l_Item."=".$l_valor;
          }
          elseif (strtoupper($p_method)=="POST") {
            $l_string=$l_string.'<INPUT TYPE="HIDDEN" NAME="'.$l_Item.'" VALUE="'.$l_valor.'">';
          }
        }
      }
    } else {
      foreach ($ul->Form as $l_Item) {
        if (substr($l_Item,0,2)=="p_" && $ul->Form($l_Item)>'') {
          if (strtoupper($p_method)=="GET") {
            $l_string=$l_string."&".$l_Item."=".$ul->Form($l_Item);
          }
          elseif (strtoupper($p_method)=="POST") {
            $l_string=$l_string.'<INPUT TYPE="HIDDEN" NAME="'.$l_Item.'" VALUE="'.$ul->Form($l_Item).'">';
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
    $l_string=$l_string.'<A class="hl" HREF="#" onClick="window.open(\''.$p_dir.'Seguranca.php?par=TELAUSUARIO&w_cliente='.$p_cliente.'&w_sq_pessoa='.$p_pessoa.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$p_TP.'&SG='.'\',\'Pessoa\',\'width=780,height=300,top=10,left=10,toolbar=no,scrollbars=yes,resizable=yes,status=no\'); return false;" title="Clique para exibir os dados desta pessoa!">'.$p_nome.'</A>';
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
    $l_string=$l_string.'<A class="hl" HREF="#" onClick="window.open(\''.$p_dir.'Seguranca.php?par=TELAUNIDADE&w_cliente='.$p_cliente.'&w_sq_unidade='.$p_sq_unidade.'&P1='.$P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$p_TP.'&SG='.'\',\'Unidade\',\'width=780,height=300,top=10,left=10,toolbar=no,scrollbars=yes,resizable=yes,status=no\'); return false;" title="Clique para exibir os dados desta unidade!">'.$p_unidade.'</A>';
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
    $l_string=$l_string.'<A class="hl" HREF="#" onClick="window.open(\'Projeto.php?par=AtualizaEtapa&w_chave='.$p_chave.'&O='.$O.'&w_chave_aux='.$p_chave_aux.'&w_tipo='.$p_tipo.'&P1='.$p_P1.'&P2='.$P2.'&P3='.$P3.'&P4='.$P4.'&TP='.$p_TP.'&SG='.$p_sg.'\',\'Etapa\',\'width=780,height=350,top=50,left=10,toolbar=no,scrollbars=yes,resizable=yes,status=no\'); return false;" title="Clique para exibir os dados!">'.$p_etapa.'</A>';
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
// Monta uma string para indicar a opção selecionada
// -------------------------------------------------------------------------
function OpcaoMenu($p_sq_menu) {
  extract($GLOBALS);
  $RS = DB_GetMenuUpper($p_sq_menu);
  $l_texto="";
  $l_cont=0;
  while(!$RS->EOF) {
    $l_Cont=$l_Cont+1;
    if ($l_Cont==1) {
      $l_texto="<font color=\"#FF0000\">".$RS['NOME']."</font> -> ".$l_texto;
    } else {
      $l_texto=$RS['NOME']." -> ".$l_texto;
    }
    $RS->MoveNext;
  }
  return $l_texto;
}

// =========================================================================
// Rotina que monta string da opção selecionada
// -------------------------------------------------------------------------
function MontaStringOpcao($p_sq_menu) {
  extract($GLOBALS);
  DB_GetLinkDataParents($RS1, $p_sq_menu);
  $w_texto="";
  $w_Cont=$RS1->RecordCount;
  while(!$RS1->EOF) {
    $w_contaux=$w_contaux+1;
    if ($w_contaux==1) {
      $w_texto="<font color=\"#FF0000\">".$RS1['DESCRICAO']."</font> -> ".$w_texto;
    } else {
      $w_texto=$RS1['DESCRICAO']." -> ".$w_texto;
    }
    $RS1->MoveNext;
  }
  return substr($w_texto,0,strlen($w_texto)-4);
}

// =========================================================================
// Rotina que monta número de ordem da etapa do projeto
// -------------------------------------------------------------------------
function MontaOrdemEtapa($p_chave) {
  extract($GLOBALS);
  DB_GetEtapaDataParents($RSQuery, $p_chave);
  $w_texto="";
  $w_Cont=$RSQuery->RecordCount;
  while(!$RSQuery->EOF) {
    $w_contaux=$w_contaux+1;
    if ($w_contaux==1) {
      $w_texto=$RSQuery['ORDEM'].".".$w_texto;
    } else {
      $w_texto=$RSQuery['ORDEM'].".".$w_texto;
    }
    $RSQuery->MoveNext;
  }
  return substr($w_texto,0,strlen($w_texto)-1);
}

// =========================================================================
// Converte CFLF para <BR>
// -------------------------------------------------------------------------
function CRLF2BR($expressao) { if (!isset($expressao) || $expressao=="") { return ''; } else { return str_replace("\\r\\n","<BR>",$expressao); } }

// =========================================================================
// Trata valores nulos
// -------------------------------------------------------------------------
function Nvl($expressao,$valor) { if (!isset($expressao) || $expressao=="") { return $valor; } else { return $expressao; } }

// =========================================================================
// Retorna valores nulos se chegar cadeia vazia
// -------------------------------------------------------------------------
function Tvl($expressao) { if (!isset($expressao) || $expressao=="") { return  null; } else { return $expressao; } }

// =========================================================================
// Retorna valores nulos se chegar cadeia vazia
// -------------------------------------------------------------------------
function Cvl($expressao) { if (!isset($expressao) || $expressao=='') { return 0; } else { return $expressao; } }

// =========================================================================
// Retorna o caminho físico para o diretório  do cliente informado
// -------------------------------------------------------------------------
function DiretorioCliente($p_Cliente)
{
  extract($GLOBALS);
  $DiretorioCliente=ini_get('APPL_PHYSICAL_PATH').'files\\'.$p_cliente;
  return $function_ret;
}

// =========================================================================
// Montagem de URL a partir da sigla da opção do menu
// -------------------------------------------------------------------------
function MontaURL($p_sigla)
{
  extract($GLOBALS);
  DB_GetLinkData($RS_montaUrl, $_SESSION['P_CLIENTE'], $p_sigla);
  $l_ImagemPadrao='images/folder/SheetLittle.gif';
  if ($RS_montaUrl->EOF)
  { $MontaURL=''; }
    else
  {
    if (Nvl($RS_montaUrl['IMAGEM'],'-')!='-')
    {
      $l_Imagem=$RS_montaUrl['IMAGEM'];
    }
      else
    {
      $l_Imagem=$l_ImagemPadrao;
    }
    $MontaURL=$RS_montaUrl['LINK']."&P1=".$RS_montaUrl['P1']."&P2=".$RS_montaUrl['P2']."&P3=".$RS_montaUrl['P3']."&P4=".$RS_montaUrl['P4']."&TP=<img src=".$l_imagem." BORDER=0>".$RS_montaUrl['NOME']."&SG=".$RS_montaUrl['SIGLA'];
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
// Montagem da seleção de sexo
// -------------------------------------------------------------------------
function SelecaoSexo($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
   if (!isset($hint)) {
      ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
   } else {
      ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
   }
   ShowHTML('          <option value="">---');
   if (Nvl($chave,'')=='M') {
      ShowHTML('          <option value="F">Feminino');
      ShowHTML('          <option value="M" SELECTED>Masculino');
   }
   elseif (Nvl($chave,'')=='F') {
     ShowHTML('          <option value="F" SELECTED>Feminino');
     ShowHTML('          <option value="M">Masculino');
   }
   else {
     ShowHTML('          <option value="F">Feminino');
     ShowHTML('          <option value="M">Masculino');
   }
   ShowHTML('          </select>');
}

// =========================================================================
// Montagem de campo do tipo radio com padrão Não
// -------------------------------------------------------------------------
function MontaRadioNS($label,$Chave,$Campo)
{
  ShowHTML('          <td><font size="1">');
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
function MontaRadioSN($label,$Chave,$Campo)
{
  ShowHTML('          <td><font size="1">');
  if (Nvl($label,'')>'') { ShowHTML($label.'</b><br>'); }
  if ($chave=='N') {
     ShowHTML('              <input '.$w_Disabled.' type="radio" name="'.$campo.'" value="S"> Sim <input '.$w_Disabled.' type="radio" name="'.$campo.'" value="N" checked> Não');
  } else {
     ShowHTML('              <input '.$w_Disabled.' type="radio" name="'.$campo.'" value="S" checked> Sim <input '.$w_Disabled.' type="radio" name="'.$campo.'" value="N"> Não');
  }
}

// =========================================================================
// Montagem da seleção de pessoas
// -------------------------------------------------------------------------
function SelecaoPessoa1($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao)
{
  extract($GLOBALS);
  ShowHTML('<INPUT type="hidden" name="'.$campo.'" value="'.$chave.'">');
  if ($cDbl[nvl($chave,0)]>0) {
     DB_GetPersonList($RS, $w_cliente, $chave, $restricao);
     $RS->Filter='sq_pessoa = '.$chave;
     $RS->Sort='nome_resumido';
     $w_nm_usuario=$RS['NOME_RESUMIDO'].' ('.$RS['SG_UNIDADE'].')';
  }

  if (!isset($hint)) {
     ShowHTML('      <td valign="top"><font size="1"><b>'.$label.'</b><br>');
     ShowHTML('          <input READONLY ACCESSKEY="'.$accesskey.'" CLASS="sti" type="text" name="'.$campo.'_nm'.'" SIZE="20" VALUE="'.$w_nm_usuario.'">');
  } else {
     ShowHTML('      <td valign="top"title="'.$hint.'"><font size="1"><b>'.$label.'</b><br>');
     ShowHTML('          <input READONLY ACCESSKEY="'.$accesskey.'" CLASS="sti" type="text" name="'.$campo.'_nm'.'" SIZE="20" VALUE="'.$w_nm_usuario.'">');
  }

  ShowHTML('              <a class="ss" href="#" onClick="window.open(\''.$w_dir_volta.'Seguranca.php?par=BuscaUsuario&TP='.$TP.'&w_cliente='.$w_cliente.'&ChaveAux='.$ChaveAux.'&restricao='.$restricao.'&campo='.$campo.'\',\'Usuário\',\'top=10,left=10,width=780,height=400,toolbar=yes,status=yes,resizable=yes,scrollbars=yes\'); return false;" title="Clique aqui para selecionar o usuário."><img src=images/Folder/Explorer.gif border=0 height=15 width=15></a>');
  ShowHTML('              <a class="ss" href="#" onClick="document.Form.'.$campo.'_nm'.'.value=\'\'; document.Form.'.$campo.'.value=\'\'; return false;" title="Clique aqui para apagar o valor deste campo."><img src=images/Folder/Recyfull.gif border=0 height=15 width=15></a>');
}

// =========================================================================
// Montagem da seleção de pessoas
// -------------------------------------------------------------------------
function SelecaoPessoa($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao) {
  extract($GLOBALS);
  DB_GetPersonList($RS, $w_cliente, $ChaveAux, $restricao);
  $RS->Sort='nome_resumido';
  if ($restricao=='TTUSURAMAL') { $RS->filter='ativo=\'S\''; }
  if (!isset($hint)) {
     ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  } else {
     ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  }
  ShowHTML('          <option value="">---');
  while(!$RS->EOF) {
    if ($cDbl[nvl($RS['SQ_PESSOA'],0)]==$cDbl[nvl($chave,0)]) {
       ShowHTML('          <option value="'.$RS['SQ_PESSOA'].'" SELECTED>'.$RS['NOME_RESUMIDO'].' ('.$RS['SG_UNIDADE'].')');
    } else {
       ShowHTML('          <option value="'.$RS['SQ_PESSOA'].'">'.$RS['NOME_RESUMIDO'].' ('.$RS['SG_UNIDADE'].')');
    }
    $RS->MoveNext;
  }
  ShowHTML('          </select>');
}

// =========================================================================
// Montagem da seleção de responsáveis por solicitações
// -------------------------------------------------------------------------
function SelecaoSolicResp($label,$accesskey,$hint,$chave,$chaveAux,$chaveAux2,$campo,$restricao) {
  extract($GLOBALS);
  DB_GetSolicResp($RS, $chaveAux, $chaveAux2, $restricao);
  $RS->Sort='nome_resumido';
  if (!isset($hint)) {
     ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  } else {
     ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  }
  ShowHTML('          <option value="">---');
  while(!$RS->EOF) {
    if ($cDbl[nvl($RS['SQ_PESSOA'],0)]==$cDbl[nvl($chave,0)]) {
       ShowHTML('          <option value="'.$RS['SQ_PESSOA'].'" SELECTED>'.$RS['NOME_RESUMIDO'].' ('.$RS['SG_UNIDADE'].')');
    } else {
       ShowHTML('          <option value="'.$RS['SQ_PESSOA'].'">'.$RS['NOME_RESUMIDO'].' ('.$RS['SG_UNIDADE'].')');
    }
    $RS->MoveNext;
  }
  ShowHTML('          </select>');
}

// =========================================================================
// Montagem da seleção do centro de custo
// -------------------------------------------------------------------------
function SelecaoUsuUnid($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao) {
  extract($GLOBALS);
  DB_GetUserList($RS, $w_cliente, $null, $null, $null, $null, $null, $null, 'S');
  $RS->Filter='contratado = \'S\'';
  $RS->Sort='nome_indice';
  if (!isset($hint)) {
     ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  } else {
     ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  }
  ShowHTML('          <option value="">---');
  while(!$RS->EOF) {
    if ($cDbl[nvl($RS['SQ_PESSOA'],0)]==$cDbl[nvl($chave,0)]) {
       ShowHTML('          <option value="'.$RS['SQ_PESSOA'].'" SELECTED>'.$RS['NOME']);
    } else {
       ShowHTML('          <option value="'.$RS['SQ_PESSOA'].'">'.$RS['NOME']);
    }
    $RS->MoveNext;
  }
  ShowHTML('          </select>');
}

// =========================================================================
// Montagem da seleção dos tipos de vínculo
// -------------------------------------------------------------------------
function SelecaoVinculo($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao) {
  extract($GLOBALS);
  DB_GetVincKindList($RS, $w_cliente);
  if (Nvl($restricao,'')>'') { $RS->Filter=$restricao; }
  if (!isset($hint)) {
     ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  } else {
     ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  }
  ShowHTML('          <option value="">---');
  while(!$RS->EOF) {
    if ($cDbl[nvl($RS['SQ_TIPO_VINCULO'],0)]==$cDbl[nvl($chave,0)]) {
       ShowHTML('          <option value="'.$RS['SQ_TIPO_VINCULO'].'" SELECTED>'.$RS['NOME']);
    } else {
       ShowHTML('          <option value="'.$RS['SQ_TIPO_VINCULO'].'">'.$RS['NOME']);
    }
    $RS->MoveNext;
  }
  ShowHTML('          </select>');
}

// =========================================================================
// Montagem da seleção dos tipos de postos
// -------------------------------------------------------------------------
function SelecaoTipoPosto($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao) {
  extract($GLOBALS);
  DB_GetTipoPostoList($RS, $w_cliente, $null);
  if (Nvl($restricao,'')>'') { $RS->Filter=$restricao; }
  if (!isset($hint)) {
     ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br>');
  } else {
     ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br>');
  }
  while(!$RS->EOF) {
     if ($cDbl[nvl($RS['SQ_EO_TIPO_POSTO'],0)]==$cDbl[nvl($chave,0)]) {
        ShowHTML('              <input '.$w_Disabled.' type="radio" name="'.$campo.'" value="'.$RS['SQ_EO_TIPO_POSTO'].'" checked>'.$RS['DESCRICAO'].'<br>');
     } else {
        ShowHTML('              <input '.$w_Disabled.' type="radio" name="'.$campo.'" value="'.$RS['SQ_EO_TIPO_POSTO'].'">'.$RS['DESCRICAO'].'<br>');
     }
     $RS->MoveNext;
  }
  ShowHTML('          </select>');
}

// =========================================================================
// Montagem da seleção do grupo de deficiência
// -------------------------------------------------------------------------
function SelecaoGrupoDef($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao) {
  extract($GLOBALS);
  DB_GetDeficGroupList($RS);
  if (!isset($hint)) {
     ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  } else {
     ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  }
  ShowHTML('          <option value="">---');
  while(!$RS->EOF) {
    if ($cDbl[nvl($RS['SQ_GRUPO_DEFIC'],0)]==$cDbl[nvl($chave,0)]) {
       ShowHTML('          <option value="'.$RS['SQ_GRUPO_DEFIC'].'" SELECTED>'.$RS['NOME']);
    } else {
       ShowHTML('          <option value="'.$RS['SQ_GRUPO_DEFIC'].'">'.$RS['NOME']);
    }
    $RS->MoveNext;
  }
  ShowHTML('          </select>');
}

// =========================================================================
// Montagem da seleção do tipo da pessoa
// -------------------------------------------------------------------------
function SelecaoTipoPessoa($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  DB_GetKindPersonList($RS);
  if ($restricao>'') { $RS->Filter=$restricao; }
  if (!isset($hint)) {
     ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
     ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }
  ShowHTML('          <option value="">---');
  while(!$RS->EOF) {
    if ($cDbl[nvl($RS['SQ_TIPO_PESSOA'],0)]==$cDbl[nvl($chave,0)]) {
       ShowHTML('          <option value="'.$RS['SQ_TIPO_PESSOA'].'" SELECTED>'.$RS['NOME']);
    } else {
       ShowHTML('          <option value="'.$RS['SQ_TIPO_PESSOA'].'">'.$RS['NOME']);
    }
    $RS->MoveNext;
  }
  ShowHTML('          </select>');
}

// =========================================================================
// Montagem da seleção da forma de pagamento
// -------------------------------------------------------------------------
function SelecaoFormaPagamento($label,$accesskey,$hint,$chave,$chave_aux,$campo,$restricao) {
  extract($GLOBALS);
  DB_GetFormaPagamento($RS, $w_cliente, $null, $chave_aux, $restricao);
  $RS->Filter='ativo = \'S\'';
  $RS->Sort='nome';
  if (!isset($hint)) {
     ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  } else {
     ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  }

  ShowHTML('          <option value="">---');
  while(!$RS->EOF) {
    if ($cDbl[nvl($RS['SQ_FORMA_PAGAMENTO'],0)]==$cDbl[nvl($chave,0)]) {
       ShowHTML('          <option value="'.$RS['SQ_FORMA_PAGAMENTO'].'" SELECTED>'.$RS['NOME']);
    } else {
       ShowHTML('          <option value="'.$RS['SQ_FORMA_PAGAMENTO'].'">'.$RS['NOME']);
    }
    $RS->MoveNext;
  }
  ShowHTML('          </select>');
}

// =========================================================================
// Montagem da seleção de país
// -------------------------------------------------------------------------
function SelecaoPais($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  DB_GetCountryList($RS);
  if ($restricao>'') { $RS->Filter=$restricao; }
  $RS->Sort='padrao desc, Nome';
  if (!isset($hint)) {
     ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
     ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }

  ShowHTML('          <option value="">---');
  while(!$RS->EOF) {
    if ($cDbl[nvl($RS['SQ_PAIS'],0)]==$cDbl[nvl($chave,0)]) {
       ShowHTML('          <option value="'.$RS['SQ_PAIS'].'" SELECTED>'.$RS['NOME']);
    } else {
       ShowHTML('          <option value="'.$RS['SQ_PAIS'].'">'.$RS['NOME']);
    }
    $RS->MoveNext;
  }
  ShowHTML('          </select>');
}

// =========================================================================
// Montagem da seleção da região
// -------------------------------------------------------------------------
function SelecaoRegiao($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  DB_GetRegionList($RS, $chaveAux, $null);
  if ($restricao>'') { $RS->Filter=$restricao; }
  $RS->Sort='Ordem';
  if (!isset($hint)) {
     ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
     ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }

  ShowHTML('          <option value="">---');
  while(!$RS->EOF) {
    if ($cDbl[nvl($RS['SQ_REGIAO'],0)]==$cDbl[nvl($chave,0)]) {
       ShowHTML('          <option value="'.$RS['SQ_REGIAO'].'" SELECTED>'.$RS['NOME']);
    } else {
       ShowHTML('          <option value="'.$RS['SQ_REGIAO'].'">'.$RS['NOME']);
    }
    $RS->MoveNext;
  }
  ShowHTML('          </select>');
}

// =========================================================================
// Montagem da seleção de estado
// -------------------------------------------------------------------------
function SelecaoEstado($label,$accesskey,$hint,$chave,$chaveAux,$chaveAux2,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  $RS = db_getStateList::getInstanceOf($dbms, nvl($chaveAux,0));
  if ($restricao>'') { $RS->Filter=$restricao; }
  //$RS->Sort='padrao desc, nome';
  if (!isset($hint)) {
     ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
     ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }

  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if (nvl(f($row,'co_uf'),'')==nvl($chave,'')) {
       ShowHTML('          <option value="'.f($row,'CO_UF').'" SELECTED>'.f($row,'nome'));
    } else {
       ShowHTML('          <option value="'.f($row,'CO_UF').'">'.f($row,'nome'));
    }
  }
  ShowHTML('          </select>');
}

// =========================================================================
// Montagem da seleção de cidade
// -------------------------------------------------------------------------
function SelecaoCidade($label,$accesskey,$hint,$chave,$chaveAux,$chaveAux2,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  DB_GetCityList($RS, Nvl($chaveAux,0), $chaveAux2);
  if ($restricao>'') { $RS->Filter=$restricao; }
  $RS->Sort='capital desc, nome';
  if (!isset($hint)) {
     ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
     ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }

  ShowHTML('          <option value="">---');
  while(!$RS->EOF) {
    if ($cDbl[nvl($RS['SQ_CIDADE'],0)]==$cDbl[nvl($chave,0)]) {
       ShowHTML('          <option value="'.$RS['SQ_CIDADE'].'" SELECTED>'.$RS['NOME']);
    } else {
       ShowHTML('          <option value="'.$RS['SQ_CIDADE'].'">'.$RS['NOME']);
    }
    $RS->MoveNext;
  }
  ShowHTML('          </select>');
}

// =========================================================================
// Montagem da seleção dos endereços da organização
// -------------------------------------------------------------------------
function SelecaoEndereco($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao) {
  extract($GLOBALS);
  $RS = db_getAddressList::getInstanceOf($dbms, $w_cliente, $ChaveAux, $restricao);
  if (!isset($hint)) {
     ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  } else {
     ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  }

  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if (nvl(f($row,'sq_pessoa_endereco'),0)==nvl($chave,0)) {
       ShowHTML('          <option value="'.f($row,'sq_pessoa_endereco').'" SELECTED>'.f($row,'endereco'));
    } else {
       ShowHTML('          <option value="'.f($row,'sq_pessoa_endereco').'">'.f($row,'endereco'));
    }
  }
  ShowHTML('          </select>');
}

// =========================================================================
// Montagem da seleção dos telefones de uma pessoa
// -------------------------------------------------------------------------
function SelecaoTelefone($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao) {
  extract($GLOBALS);
  DB_GetFoneList($RS, $w_cliente, $ChaveAux, $restricao);
  if (!isset($hint)) {
     ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  } else {
     ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  }

  ShowHTML('          <option value="">---');
  while(!$RS->EOF) {
    if ($cDbl[nvl($RS['SQ_PESSOA_TELEFONE'],0)]==$cDbl[nvl($chave,0)]) {
       ShowHTML('          <option value="'.$RS['SQ_PESSOA_TELEFONE'].'" SELECTED>'.$RS['NUMERO']);
    } else {
       ShowHTML('          <option value="'.$RS['SQ_PESSOA_TELEFONE'].'">'.$RS['NUMERO']);
    }
    $RS->MoveNext;
  }
  ShowHTML('          </select>');
}

// =========================================================================
// Montagem da seleção dos módulos contratados pelo cliente
// -------------------------------------------------------------------------
function SelecaoModulo($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  $RS = db_getSiwCliModLis::getInstanceOf($dbms, $chaveAux, $restricao);
  array_key_case_change(&$RS);
  $RS = SortArray($RS,'nome','asc');
  if (!isset($hint)) {
     ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
     ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }
  ShowHTML('          <option value="">---');
  foreach($RS as $row) {
    if (nvl(f($row,'sq_modulo'),0)==nvl($chave,0)) {
       ShowHTML('          <option value="'.f($row,'sq_modulo').'" SELECTED>'.f($row,'nome'));
    } else {
       ShowHTML('          <option value="'.f($row,'sq_modulo').'">'.f($row,'nome'));
    }
  }
  ShowHTML('          </select>');
}

// =========================================================================
// Montagem da seleção de opções do menu que são vinculadas a serviço
// -------------------------------------------------------------------------
function SelecaoServico($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  DB_GetMenuList($RS, $w_cliente, 'I', $null);
  $RS->Filter='tramite=\'S\'';
  if (!isset($hint)) {
     ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
     ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }
  ShowHTML('          <option value="">---');
  while(!$RS->EOF) {
    if ($cDbl[nvl($RS['SQ_MENU'],0)]==$cDbl[nvl($chave,0)]) {
       ShowHTML('          <option value="'.$RS['SQ_MENU'].'" SELECTED>'.$RS['NOME']);
    } else {
       ShowHTML('          <option value="'.$RS['SQ_MENU'].'">'.$RS['NOME']);
    }
    $RS->MoveNext;
  }
  ShowHTML('          </select>');
}

// =========================================================================
// Montagem da seleção de opções existentes no menu
// -------------------------------------------------------------------------
function SelecaoMenu($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  if (!isset($hint))
     { ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>'); }
  else
     { ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>'); }
  ShowHTML('          <option value="">---');
  if ($restricao=='Pesquisa') $l_ultimo_nivel = 'N'; else $l_ultimo_nivel = null;
  $RST = db_getMenuOrder::getInstanceOf($dbms, $w_cliente, null, nvl($chaveAux,0), $l_ultimo_nivel);
  foreach ($RST as $row) {
    if (nvl(f($row,'sq_menu'),0)==nvl($chave,0)) { ShowHTML('          <option value="'.f($row,'sq_menu').'" SELECTED>'.f($row,'nome')); } else { ShowHTML('          <option value="'.f($row,'sq_menu').'">'.f($row,'nome')); }
    $RST1 = db_getMenuOrder::getInstanceOf($dbms, $w_cliente, f($row,'sq_menu'), nvl($chaveAux,0), $l_ultimo_nivel);
    foreach($RST1 as $row1) {
      if (nvl(f($row1,'sq_menu'),0)==nvl($chave,0)) { ShowHTML('          <option value="'.f($row1,'sq_menu').'" SELECTED>&nbsp;&nbsp;&nbsp;'.f($row1,'nome')); } else { ShowHTML ('          <option value="'.f($row1,'sq_menu').'">&nbsp;&nbsp;&nbsp;'.f($row1,'nome')); }
      $RST2 = db_getMenuOrder::getInstanceOf($dbms, $w_cliente, f($row1,'sq_menu'), nvl($chaveAux,0), $l_ultimo_nivel);
      foreach($RST2 as $row2) {
        if (nvl(f($row2,'sq_menu'),0)==nvl($chave,0)) { ShowHTML('          <option value="'.f($row2,'sq_menu').'" SELECTED>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.f($row2,'nome')); } else { ShowHTML('          <option value="'.f($row2,'sq_menu').'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.f($row2,'nome')); }
        $RST3 = db_getMenuOrder::getInstanceOf($dbms, $w_cliente, f($row2,'sq_menu'), nvl($chaveAux,0), $l_ultimo_nivel);
          foreach($RST3 as $row3) {
          if (nvl(f($row3,'sq_menu'),0)==nvl($chave,0)) { ShowHTML('          <option value="'.f($row3,'sq_menu').'" SELECTED>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.f($row3,'nome')); } else { ShowHTML ('          <option value="'.f($row3,'sq_menu').'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.f($row3,'nome')); }
          $RST4 = db_getMenuOrder::getInstanceOf($dbms, $w_cliente, f($row3,'sq_menu'), nvl($chaveAux,0), $l_ultimo_nivel);
          foreach($RST4 as $row4) {
            if (nvl(f($row4,'sq_menu'),0)==nvl($chave,0)) { ShowHTML('          <option value="'.f($row4,'sq_menu').'" SELECTED>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.f($row4,'nome')); } else { ShowHTML('          <option value="'.f($row4,'sq_menu').'">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'.f($row4,'nome')); }
          }
        }
      }
    }
  }
  ShowHTML('          </select>');
  return $function_ret;
}

// =========================================================================
// Montagem da seleção da localização
// -------------------------------------------------------------------------
function SelecaoLocalizacao($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao) {
  extract($GLOBALS);
  $RS = DB_GetLocalList::getInstanceOf($dbms, $w_cliente, $ChaveAux, $restricao);
  //if (!!isset($chaveAux)) $RS->Filter='sq_unidade = '.$chaveAux;

  if (!isset($hint)) {
    ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  } else {
    ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
  }

  ShowHTML('          <option value="">---');

  foreach ($RS as $row)  {
    if (nvl(f($row,'SQ_LOCALIZACAO'),0) == nvl($chave,0)) {
      ShowHTML('          <option value="'.f($row,'SQ_LOCALIZACAO').'" SELECTED>'.f($row,'LOCALIZACAO'));
    } else {
      ShowHTML('          <option value="'.f($row,'SQ_LOCALIZACAO').'">'.f($row,'LOCALIZACAO'));
    }
  }
  ShowHTML('          </select>');
  return $function_ret;
}

// =========================================================================
// Montagem da seleção da localização
// -------------------------------------------------------------------------
function SelecaoSegModulo($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao) {
  extract($GLOBALS);


DB_GetSegModList($RS, $ChaveAux);
$RS->Sort='Nome';
if (!isset($hint))
{

  ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
}
  else
{

  ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.'>');
}

ShowHTML('          <option value="">---');
while(!$RS->EOF)
{

  if ($cDbl[nvl($RS['SQ_MODULO'],0)]==$cDbl[nvl($chave,0)])
  {

    ShowHTML('          <option value="'.$RS['SQ_MODULO'].'" SELECTED>'.$RS['NOME']);
  }
    else
  {

    ShowHTML('          <option value="'.$RS['SQ_MODULO'].'">'.$RS['NOME']);
  }

$RS->MoveNext;
}
ShowHTML('          </select>');
return $function_ret;
}

// =========================================================================
// Montagem da seleção de segmentos de mercado
// -------------------------------------------------------------------------
function SelecaoSegMercado($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);


DB_GetSegList($RS);
$RS->Filter='ativo = \'S\'';
$RS->Sort='Nome';
if (!isset($hint))
{

  ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
}
  else
{

  ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
}

ShowHTML('          <option value="">---');
while(!$RS->EOF)
{

  if ($cDbl[nvl($RS['SQ_SEGMENTO'],0)]==$cDbl[nvl($chave,0)])
  {

    ShowHTML('          <option value="'.$RS['SQ_SEGMENTO'].'" SELECTED>'.$RS['NOME']);
  }
    else
  {

    ShowHTML('          <option value="'.$RS['SQ_SEGMENTO'].'">'.$RS['NOME']);
  }

$RS->MoveNext;
}
ShowHTML('          </select>');
return $function_ret;
}

// =========================================================================
// Montagem da seleção das unidades organizacionais
// -------------------------------------------------------------------------
function SelecaoUnidade($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);

  if (!isset($restricao) || (strpos($restricao,"=") ? strpos($restricao,"=")+1 : 0)==0) {

    $RS = DB_GetUorgList::getInstanceOf($dbms, $w_cliente, $ChaveAux, $restricao, null, null);
    //$RS->Filter="ativo='S'";
  } else {

    $RS = DB_GetUorgList::getInstanceOf($dbms, $w_cliente, $ChaveAux, null, null, null);
    //$RS->Filter="ativo='S' and ".$restricao;
  }

  ShowHTML("<INPUT type=\"hidden\" name=\"".$campo."\" value=\"".$chave."\">");
  if ($chave>'') {

    $RS = DB_GetUorgList::getInstanceOf($dbms, $w_cliente, $chave, null, null, null);
    //$RS->Filter="sq_unidade = ".$chave;
    foreach ($RS as $row) {
      $w_nm_unidade=f($row,'nome');
      $w_sigla=f($row,'sigla');
    }

  }

  if (!isset($hint)) {
    ShowHTML("      <td valign=\"top\"><font size=\"1\"><b>".$label."</b><br>");
    ShowHTML("          <input READONLY ACCESSKEY=\"".$accesskey."\" CLASS=\"sti\" type=\"text\" name=\"".$campo."_nm"."\" SIZE=\"60\" VALUE=\"".$w_nm_unidade."\" ".$atributo.">");
  } else {
    ShowHTML("      <td valign=\"top\" title=\"".$hint."\"><font size=\"1\"><b>".$label."</b><br>");
    ShowHTML("          <input READONLY ACCESSKEY=\"".$accesskey."\" CLASS=\"sti\" type=\"text\" name=\"".$campo."_nm"."\" SIZE=\"60\" VALUE=\"".$w_nm_unidade."\" ".$atributo.">");
  }

  ShowHTML("              <a class=\"ss\" href=\"#\" onClick=\"window.open('".str_replace("/files","",$conFileVirtual)."eo.php?par=BuscaUnidade&TP=".$TP."&w_cliente=".$w_cliente."&ChaveAux=".$ChaveAux."&restricao=".$restricao."&campo=".$campo."','Unidade','top=10,left=10,width=780,height=550,toolbar=yes,status=yes,resizable=yes,scrollbars=yes'); return false;\" title=\"Clique aqui para selecionar a unidade.\"><img src=images/Folder/Explorer.gif border=0 align=top height=15 width=15></a>");
  ShowHTML("              <a class=\"ss\" href=\"#\" onClick=\"document.Form.".$campo."_nm".".value=''; document.Form.".$campo.".value=''; return false;\" title=\"Clique aqui para apagar o valor deste campo.\"><img src=images/Folder/Recyfull.gif border=0 align=top height=15 width=15></a>");
  return $function_ret;
}

// =========================================================================
// Montagem da seleção da unidade pai
// -------------------------------------------------------------------------
function SelecaoUnidadePai($label,$accesskey,$hint,$chave,$Operacao,$chaveAux,$chaveAux2,$campo,$restricao) {
  extract($GLOBALS);


DB_GetEOUnitPaiList($RS, $Operacao, $chaveAux, $chaveAux2);
$RS->Sort="Nome";
if (!isset($hint))
{

  ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>".$label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" CLASS=\"sts\" NAME=\"".$campo."\" ".$w_Disabled.">");
}
  else
{

  ShowHTML("          <td valign=\"top\" title=\"".$hint."\"><font size=\"1\"><b>".$label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" CLASS=\"sts\" NAME=\"".$campo."\" ".$w_Disabled.">");
}

ShowHTML("          <option value=\"\">---");
while(!$RS->EOF)
{

  if ($cDbl[nvl($RS['SQ_UNIDADE'],0)]==$cDbl[nvl($chave,0)])
  {

    ShowHTML("          <option value=\"".$RS['SQ_UNIDADE']."\" SELECTED>".$RS['NOME']);
  }
    else
  {

    ShowHTML("          <option value=\"".$RS['SQ_UNIDADE']."\">".$RS['NOME']);
  }

$RS->MoveNext;
}
ShowHTML("          </select>");
return $function_ret;
}

// =========================================================================
// Montagem da seleção das unidades gestoras
// -------------------------------------------------------------------------
function SelecaoUnidadeGest($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao) {
  extract($GLOBALS);


DB_GetUorgList($RS, $w_cliente, $chaveAux, $null, $null, $null);
$w_filter=" unidade_gestora = 'S' and ativo = 'S'";
if ($chaveAux>'')
{

  $w_filter=$w_filter." and sq_unidade <> ".$chaveAux;
}

$RS->Filter=$w_filter;
$RS->Sort="Nome";
if (!isset($hint))
{

  ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>".$label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" CLASS=\"sts\" NAME=\"".$campo."\" ".$w_Disabled.">");
}
  else
{

  ShowHTML("          <td valign=\"top\" title=\"".$hint."\"><font size=\"1\"><b>".$label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" CLASS=\"sts\" NAME=\"".$campo."\" ".$w_Disabled.">");
}

ShowHTML("          <option value=\"\">---");
while(!$RS->EOF)
{

  if ($cDbl[nvl($RS['SQ_UNIDADE'],0)]==$cDbl[nvl($chave,0)] && $cDbl[nvl($RS['SQ_UNIDADE'],0)]>0)
  {

    ShowHTML("          <option value=\"".$RS['SQ_UNIDADE']."\" SELECTED>".$RS['NOME']);
  }
    else
  {

    ShowHTML("          <option value=\"".$RS['SQ_UNIDADE']."\">".$RS['NOME']);
  }

$RS->MoveNext;
}
ShowHTML("          </select>");
return $function_ret;
}

// =========================================================================
// Montagem da seleção das unidades pagadoras
// -------------------------------------------------------------------------
function SelecaoUnidadePag($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao) {
  extract($GLOBALS);


DB_GetUorgList($RS, $w_cliente, $chaveAux, $null, $null, $null);
$w_filter=" unidade_pagadora = 'S' and ativo = 'S'";
if ($chaveAux>'')
{

  $w_filter=$w_filter." and sq_unidade <> ".$chaveAux;
}

$RS->Filter=$w_filter;
$RS->Sort="Nome";
if (!isset($hint))
{

  ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>".$label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" CLASS=\"sts\" NAME=\"".$campo."\" ".$w_Disabled.">");
}
  else
{

  ShowHTML("          <td valign=\"top\" title=\"".$hint."\"><font size=\"1\"><b>".$label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" CLASS=\"sts\" NAME=\"".$campo."\" ".$w_Disabled.">");
}

ShowHTML("          <option value=\"\">---");
while(!$RS->EOF)
{

  if ($cDbl[nvl($RS['SQ_UNIDADE'],0)]==$cDbl[nvl($chave,0)] && $cDbl[nvl($RS['SQ_UNIDADE'],0)]>0)
  {

    ShowHTML("          <option value=\"".$RS['SQ_UNIDADE']."\" SELECTED>".$RS['NOME']);
  }
    else
  {

    ShowHTML("          <option value=\"".$RS['SQ_UNIDADE']."\">".$RS['NOME']);
  }

$RS->MoveNext;
}
ShowHTML("          </select>");
return $function_ret;
}

// =========================================================================
// Montagem da seleção do centro de custo
// -------------------------------------------------------------------------
function SelecaoCC($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao) {
  extract($GLOBALS);


DB_GetCCList($RS, $w_cliente, $ChaveAux, $restricao);
if (!isset($hint))
{

  ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>".$label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" CLASS=\"sts\" NAME=\"".$campo."\" ".$w_Disabled.">");
}
  else
{

  ShowHTML("          <td valign=\"top\" title=\"".$hint."\"><font size=\"1\"><b>".$label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" CLASS=\"sts\" NAME=\"".$campo."\" ".$w_Disabled.">");
}

ShowHTML("          <OPTION VALUE=\"\">---");
while(!$RS->EOF)
{

  if ($cDbl[nvl($RS['SQ_CC'],0)]==$cDbl[nvl($chave,0)])
  {

    ShowHTML("          <option value=\"".$RS['SQ_CC']."\" SELECTED>".$RS['NOME']);
  }
    else
  {

    ShowHTML("          <option value=\"".$RS['SQ_CC']."\">".$RS['NOME']);
  }

$RS->MoveNext;
}
DesconectaBD();
ShowHTML("          </SELECT></td>");
return $function_ret;
}

// =========================================================================
// Montagem da seleção do centro de custo
// -------------------------------------------------------------------------
function SelecaoCCSubordination($label,$accesskey,$hint,$chave,$pai,$campo,$restricao,$condicao) {
  extract($GLOBALS);


DB_GetCCSubordination($RS, $w_cliente, $chave, $restricao);
if (!isset($hint))
{

  ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>".$label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" CLASS=\"sts\" NAME=\"".$campo."\" ".$w_Disabled.">");
}
  else
{

  ShowHTML("          <td valign=\"top\" title=\"".$hint."\"><font size=\"1\"><b>".$label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" CLASS=\"sts\" NAME=\"".$campo."\" ".$w_Disabled.">");
}

ShowHTML("          <OPTION VALUE=\"\">---");
while(!$RS->EOF)
{

  if ($cDbl[$RS['SQ_CC']]==$cDbl[nvl($pai,0)])
  {

    ShowHTML("          <option value=\"".$RS['SQ_CC']."\" SELECTED>".$RS['NOME']);
  }
    else
  {

    ShowHTML("          <option value=\"".$RS['SQ_CC']."\">".$RS['NOME']);
  }

$RS->MoveNext;
}
DesconectaBD();
ShowHTML("          </SELECT></td>");
return $function_ret;
}

// =========================================================================
// Montagem da seleção do banco
// -------------------------------------------------------------------------
function SelecaoBanco($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);


DB_GetBankList($RS);
$RS->Filter="ativo='S'";
if (!isset($hint))
{

  ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>".$label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" CLASS=\"sts\" NAME=\"".$campo."\" ".$w_Disabled." ".$atributo.">");
}
  else
{

  ShowHTML("          <td valign=\"top\" title=\"".$hint."\"><font size=\"1\"><b>".$label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" CLASS=\"sts\" NAME=\"".$campo."\" ".$w_Disabled." ".$atributo.">");
}

ShowHTML("          <option value=\"\">---");
while(!$RS->EOF)
{

  if ($cDbl[Nvl($chave,-1)]==$cDbl[Nvl($RS['SQ_BANCO'],-1)])
  {

    ShowHTML("          <OPTION VALUE=\"".$RS['SQ_BANCO']."\" SELECTED>".$RS['DESCRICAO']);
  }
    else
  {

    ShowHTML("          <OPTION VALUE=\"".$RS['SQ_BANCO']."\">".$RS['DESCRICAO']);
  }

$RS->MoveNext;
}
DesconectaBD();
ShowHTML("          </SELECT></td>");
return $function_ret;
}

// =========================================================================
// Montagem da seleção de estado
// -------------------------------------------------------------------------
function SelecaoAgencia($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);


DB_GetBankHouseList($RS, $chaveAux, $null, 'padrao desc, codigo');
if ($restricao>'')
{

$RS->Filter=$restricao;
}

if (!isset($hint))
{

  ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>".$label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" CLASS=\"sts\" NAME=\"".$campo."\" ".$w_Disabled." ".$atributo.">");
}
  else
{

  ShowHTML("          <td valign=\"top\" title=\"".$hint."\"><font size=\"1\"><b>".$label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" CLASS=\"sts\" NAME=\"".$campo."\" ".$w_Disabled." ".$atributo.">");
}

ShowHTML("          <option value=\"\">---");
while(!$RS->EOF)
{

  if ($cDbl[nvl($RS['SQ_AGENCIA'],-1)]==$cDbl[nvl($chave,-1)])
  {

    ShowHTML("          <option value=\"".$RS['SQ_AGENCIA']."\" SELECTED>".$RS['CODIGO']." - ".$RS['NOME']);
  }
    else
  {

    ShowHTML("          <option value=\"".$RS['SQ_AGENCIA']."\">".$RS['CODIGO']." - ".$RS['NOME']);
  }

$RS->MoveNext;
}
ShowHTML("          </select>");
return $function_ret;
}

// =========================================================================
// Montagem da seleção do tipo de unidade
// -------------------------------------------------------------------------
function SelecaoTipoUnidade($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao) {
  extract($GLOBALS);


DB_GetUnitTypeList($RS, $chaveAux);
$RS->Filter="ativo = 'S'";
$RS->Sort="Nome";
if (!isset($hint))
{

  ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>".$label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" CLASS=\"sts\" NAME=\"".$campo."\" ".$w_Disabled.">");
}
  else
{

  ShowHTML("          <td valign=\"top\" title=\"".$hint."\"><font size=\"1\"><b>".$label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" CLASS=\"sts\" NAME=\"".$campo."\" ".$w_Disabled.">");
}

ShowHTML("          <option value=\"\">---");
while(!$RS->EOF)
{

  if ($cDbl[nvl($RS['SQ_TIPO_UNIDADE'],0)]==$cDbl[nvl($chave,0)])
  {

    ShowHTML("          <option value=\"".$RS['SQ_TIPO_UNIDADE']."\" SELECTED>".$RS['NOME']);
  }
    else
  {

    ShowHTML("          <option value=\"".$RS['SQ_TIPO_UNIDADE']."\">".$RS['NOME']);
  }

$RS->MoveNext;
}
ShowHTML("          </select>");
return $function_ret;
}

// =========================================================================
// Montagem da seleção do tipo de endereco
// -------------------------------------------------------------------------
function SelecaoTipoEndereco($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);


DB_GetAdressTypeList($RS);
if ($restricao>'')
{

$RS->Filter=$restricao;
}

$RS->Sort="Nome";
if (!isset($hint))
{

  ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>".$label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" CLASS=\"sts\" NAME=\"".$campo."\" ".$w_Disabled." ".$atributo.">");
}
  else
{

  ShowHTML("          <td valign=\"top\" title=\"".$hint."\"><font size=\"1\"><b>".$label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" CLASS=\"sts\" NAME=\"".$campo."\" ".$w_Disabled." ".$atributo.">");
}

ShowHTML("          <option value=\"\">---");
while(!$RS->EOF)
{

  if ($cDbl[nvl($RS['SQ_TIPO_ENDERECO'],0)]==$cDbl[nvl($chave,0)])
  {

    ShowHTML("          <option value=\"".$RS['SQ_TIPO_ENDERECO']."\" SELECTED>".$RS['NOME']);
  }
    else
  {

    ShowHTML("          <option value=\"".$RS['SQ_TIPO_ENDERECO']."\">".$RS['NOME']);
  }

$RS->MoveNext;
}
ShowHTML("          </select>");
return $function_ret;
}

// =========================================================================
// Montagem da seleção do tipo de endereco
// -------------------------------------------------------------------------
function SelecaoTipoFone($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
DB_GetFoneTypeList($RS);
if ($restricao>'')
{

$RS->Filter=$restricao;
}

$RS->Sort="Nome";
if (!isset($hint))
{

  ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>".$label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" CLASS=\"sts\" NAME=\"".$campo."\" ".$w_Disabled." ".$atributo.">");
}
  else
{

  ShowHTML("          <td valign=\"top\" title=\"".$hint."\"><font size=\"1\"><b>".$label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" CLASS=\"sts\" NAME=\"".$campo."\" ".$w_Disabled." ".$atributo.">");
}

ShowHTML("          <option value=\"\">---");
while(!$RS->EOF)
{

  if ($cDbl[nvl($RS['SQ_TIPO_TELEFONE'],0)]==$cDbl[nvl($chave,0)])
  {

    ShowHTML("          <option value=\"".$RS['SQ_TIPO_TELEFONE']."\" SELECTED>".$RS['NOME']);
  }
    else
  {

    ShowHTML("          <option value=\"".$RS['SQ_TIPO_TELEFONE']."\">".$RS['NOME']);
  }

$RS->MoveNext;
}
ShowHTML("          </select>");
return $function_ret;
}

// =========================================================================
// Montagem da seleção do tipo de unidade
// -------------------------------------------------------------------------
function SelecaoEOAreaAtuacao($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao) {
  extract($GLOBALS);


DB_GetEOAAtuac($RS, $chaveAux);
$RS->Filter="ativo = 'S'";
$RS->Sort="Nome";
if (!isset($hint))
{

  ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>".$label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" CLASS=\"sts\" NAME=\"".$campo."\" ".$w_Disabled.">");
}
  else
{

  ShowHTML("          <td valign=\"top\" title=\"".$hint."\"><font size=\"1\"><b>".$label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" CLASS=\"sts\" NAME=\"".$campo."\" ".$w_Disabled.">");
}

ShowHTML("          <option value=\"\">---");
while(!$RS->EOF)
{

  if ($cDbl[nvl($RS['SQ_AREA_ATUACAO'],0)]==$cDbl[nvl($chave,0)])
  {

    ShowHTML("          <option value=\"".$RS['SQ_AREA_ATUACAO']."\" SELECTED>".$RS['NOME']);
  }
    else
  {

    ShowHTML("          <option value=\"".$RS['SQ_AREA_ATUACAO']."\">".$RS['NOME']);
  }

$RS->MoveNext;
}
ShowHTML("          </select>");
return $function_ret;
}

// =========================================================================
// Montagem da seleção da fase de uma solicitação
// -------------------------------------------------------------------------
function SelecaoFase($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
DB_GetTramiteList($RS, $chaveAux, $restricao);
$RS->Filter="ativo = 'S'";
$RS->Sort="Ordem";
if (!isset($hint))
{

  ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>".$label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" CLASS=\"sts\" NAME=\"".$campo."\" ".$w_Disabled." ".$atributo.">");
}
  else
{

  ShowHTML("          <td valign=\"top\" title=\"".$hint."\"><font size=\"1\"><b>".$label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" CLASS=\"sts\" NAME=\"".$campo."\" ".$w_Disabled." ".$atributo.">");
}

while(!$RS->EOF)
{

  if ($cDbl[$RS['SQ_SIW_TRAMITE']]==$cDbl[$chave])
  {

    ShowHTML("          <option value=\"".$RS['SQ_SIW_TRAMITE']."\" SELECTED>".$RS['ORDEM']." - ".$RS['NOME']);
  }
    else
  {

    ShowHTML("          <option value=\"".$RS['SQ_SIW_TRAMITE']."\">".$RS['ORDEM']." - ".$RS['NOME']);
  }

$RS->MoveNext;
}
ShowHTML("          </select>");
return $function_ret;
}

// =========================================================================
// Montagem da seleção da fase de uma solicitação
// -------------------------------------------------------------------------
function SelecaoFaseCheck($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
DB_GetTramiteList($RS, $chaveAux, $null);
$RS->Filter="ativo = 'S' or sigla = 'AT'";
$RS->Sort="Ordem";
ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>".$label."</b>");
while(!$RS->EOF)
{
  if (Nvl($chave,"")=="") { ShowHTML("          <BR><input type=\"CHECKBOX\" name=\"".$campo."\" value=\"".$RS['SQ_SIW_TRAMITE']."\" CHECKED>".$RS['NOME']); }
  else {
    $l_marcado="N";
    $l_chave=$chave.",";
    while((strpos($l_chave,",") ? strpos($l_chave,",")+1 : 0)>0)
    {
      $l_item=trim(substr($l_chave,0,(strpos($l_chave,",") ? strpos($l_chave,",")+1 : 0)-1));
      $l_chave=substr($l_chave,(strpos($l_chave,",") ? strpos($l_chave,",")+1 : 0)+1-1,100);
      if ($l_item>'')
      {
        if ($cDbl[$RS['SQ_SIW_TRAMITE']]==$cDbl[$l_item]) { $l_marcado="S"; };
      }
    }

    if ($l_marcado=="S")
       { ShowHTML("          <BR><input type=\"CHECKBOX\" name=\"".$campo."\" value=\"".$RS['SQ_SIW_TRAMITE']."\" CHECKED>".$RS['NOME']); }
    else
       { ShowHTML("          <BR><input type=\"CHECKBOX\" name=\"".$campo."\" value=\"".$RS['SQ_SIW_TRAMITE']."\" >".$RS['NOME']); }

  }

  $RS->MoveNext;
}
ShowHTML("          </select>");

$l_item=null;
$l_chave=null;
$l_marcado=null;
$l_i=null;
return $function_ret;
}

// =========================================================================
// Montagem da seleção de projetos
// -------------------------------------------------------------------------
function SelecaoProjeto($label,$accesskey,$hint,$chave,$chaveAux,$chaveAux2,$campo,$restricao,$atributo) {
  extract($GLOBALS);
DB_GetSolicList($RS, $chaveAux2, $chaveAux, $restricao, $null, $null, $null, $null, $null, $null, $null, $null, $null, $null, $null, $null, $null, $null, $null, $null, $null, $null, $null, $null, $null, $null, $null, $null, $null, $null, $null);
$RS->Sort="titulo";

if (!isset($hint))
{

  ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>".$label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" CLASS=\"sts\" NAME=\"".$campo."\" ".$w_Disabled." ".$atributo.">");
}
  else
{

  ShowHTML("          <td valign=\"top\" title=\"".$hint."\"><font size=\"1\"><b>".$label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" CLASS=\"sts\" NAME=\"".$campo."\" ".$w_Disabled." ".$atributo.">");
}

ShowHTML("          <option value=\"\">---");
while(!$RS->EOF)
{

  if ($cDbl[nvl($RS['SQ_SIW_SOLICITACAO'],0)]==$cDbl[nvl($chave,0)])
  {

    ShowHTML("          <option value=\"".$RS['SQ_SIW_SOLICITACAO']."\" SELECTED>".$RS['TITULO']);
  }
    else
  {

    ShowHTML("          <option value=\"".$RS['SQ_SIW_SOLICITACAO']."\">".$RS['TITULO']);
  }

$RS->MoveNext;
}
ShowHTML("          </select>");
return $function_ret;
}

// =========================================================================
// Montagem da seleção de tipo de recurso
// -------------------------------------------------------------------------
function SelecaoTipoRecurso($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
if (!isset($hint))
   { ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>".$label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" CLASS=\"sts\" NAME=\"".$campo."\" ".$w_Disabled." ".$atributo.">"); }
else
   { ShowHTML("          <td valign=\"top\" title=\"".$hint."\"><font size=\"1\"><b>".$label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" CLASS=\"sts\" NAME=\"".$campo."\" ".$w_Disabled." ".$atributo.">"); }

ShowHTML("          <option value=\"\">---");
if ($cDbl[nvl($chave,-1)]==0) { ShowHTML("          <option value=\"0\" SELECTED>Financeiro"); } else { ShowHTML("          <option value=\"0\">Financeiro"); }
if ($cDbl[nvl($chave,-1)]==1) { ShowHTML("          <option value=\"1\" SELECTED>Humano"); } else { ShowHTML("          <option value=\"1\">Humano"); }
if ($cDbl[nvl($chave,-1)]==2) { ShowHTML("          <option value=\"2\" SELECTED>Material"); } else { ShowHTML("          <option value=\"2\">Material"); }
if ($cDbl[nvl($chave,-1)]==3) { ShowHTML("          <option value=\"3\" SELECTED>Metodológico"); } else { ShowHTML("          <option value=\"3\">Metodológico"); }
ShowHTML("          </select>");
return $function_ret;
}

// =========================================================================
// Retorna o tipo de recurso a partir do código
// -------------------------------------------------------------------------
function RetornaTipoRecurso($p_chave) {
  extract($GLOBALS);


switch ($cDbl[$p_Chave])
{
  case 0: $RetornaTipoRecurso="Financeiro";
           break;
  case 1: $RetornaTipoRecurso="Humano";
          break;
  case 2: $RetornaTipoRecurso="Material";
          break;
  case 3: $RetornaTipoRecurso="Metodológico";
          break;
  default:$RetornaTipoRecurso="Erro";
          break;
}

return $function_ret;
}

// =========================================================================
// Montagem da seleção de prioridade
// -------------------------------------------------------------------------
function SelecaoPrioridade($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
if (!isset($hint))
{

  ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>".$label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" CLASS=\"sts\" NAME=\"".$campo."\" ".$w_Disabled." ".$atributo.">");
}
  else
{

  ShowHTML("          <td valign=\"top\" title=\"".$hint."\"><font size=\"1\"><b>".$label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" CLASS=\"sts\" NAME=\"".$campo."\" ".$w_Disabled." ".$atributo.">");
}

ShowHTML("          <option value=\"\">---");
if ($cDbl[nvl($chave,-1)]==0) { ShowHTML("          <option value=\"0\" SELECTED>Alta"); } else { ShowHTML("          <option value=\"0\">Alta"); }
if ($cDbl[nvl($chave,-1)]==1) { ShowHTML("          <option value=\"1\" SELECTED>Média"); } else { ShowHTML("          <option value=\"1\">Média" ); }
if ($cDbl[nvl($chave,-1)]==2) { ShowHTML("          <option value=\"2\" SELECTED>Normal"); } else { ShowHTML("          <option value=\"2\">Normal" ); }
ShowHTML("          </select>");
return $function_ret;
}

// =========================================================================
// Montagem da seleção de prioridade
// -------------------------------------------------------------------------
function SelecaoTipoVisao($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
if (!isset($hint))
{

  ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>".$label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" CLASS=\"sts\" NAME=\"".$campo."\" ".$w_Disabled." ".$atributo.">");
}
  else
{

  ShowHTML("          <td valign=\"top\" title=\"".$hint."\"><font size=\"1\"><b>".$label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" CLASS=\"sts\" NAME=\"".$campo."\" ".$w_Disabled." ".$atributo.">");
}

ShowHTML("          <option value=\"\">---");
if ($cDbl[nvl($chave,-1)]==0) { ShowHTML("          <option value=\"0\" SELECTED>Completa"); } else { ShowHTML("          <option value=\"0\">Completa"); }
if ($cDbl[nvl($chave,-1)]==1) { ShowHTML("          <option value=\"1\" SELECTED>Parcial"); } else { ShowHTML("          <option value=\"1\">Parcial"); }
if ($cDbl[nvl($chave,-1)]==2) { ShowHTML("          <option value=\"2\" SELECTED>Resumida"); } else { ShowHTML("          <option value=\"2\">Resumida"); }
ShowHTML("          </select>");
return $function_ret;
}

// =========================================================================
// Montagem da seleção de etapas do projeto
// -------------------------------------------------------------------------
function SelecaoEtapa($label,$accesskey,$hint,$chave,$chaveAux,$chaveAux2,$campo,$restricao,$atributo) {
  extract($GLOBALS);
if (!isset($hint))
   { ShowHTML("          <td valign=\"top\"><font size=\"1\"><b>".$label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" CLASS=\"sts\" NAME=\"".$campo."\" ".$w_Disabled." ".$atributo.">"); }
else
   { ShowHTML("          <td valign=\"top\" title=\"".$hint."\"><font size=\"1\"><b>".$label."</b><br><SELECT ACCESSKEY=\"".$accesskey."\" CLASS=\"sts\" NAME=\"".$campo."\" ".$w_Disabled." ".$atributo.">"); }
ShowHTML("          <option value=\"\">---");

DB_GetSolicEtapa($RST, $chaveAux, $chaveAux2, 'LSTNULL');
if ($restricao=="Pesquisa") { $RST->Filter="sq_projeto_etapa <> ".Nvl($chaveAux2,0); };
$RST->Sort="ordem";
while(!$RST->EOF) {
  if ($restricao=="Grupo" && ($RST['VINCULA_ATIVIDADE']=="N" || $cDbl[$RST['PERC_CONCLUSAO']]>=100))
     { ShowHTML("          <option value=\"\">".$RST['ORDEM'].". ".$RST['TITULO']); }
  else
     { if ($cDbl[nvl($RST['sq_projeto_etapa'],0)]==$cDbl[nvl($chave,0)]) { ShowHTML("          <option value=\"".$RST['sq_projeto_etapa']."\" SELECTED>".$RST['ORDEM'].". ".$RST['TITULO']); } else { ShowHTML("          <option value=\"".$RST['sq_projeto_etapa']."\">".$RST['ORDEM'].". ".$RST['TITULO']); } }
  DB_GetSolicEtapa($RST1, $chaveAux, $RST['sq_projeto_etapa'], 'LSTNIVEL');
  if ($restricao=="Pesquisa") { $RST1->Filter="sq_projeto_etapa <> ".Nvl($chaveAux2,0); }
  $RST1->Sort="ordem";
  while(!$RST1->EOF) {
    if ($restricao=="Grupo" && ($RST1['VINCULA_ATIVIDADE']=="N" || $cDbl[$RST1['PERC_CONCLUSAO']]>=100))
       { ShowHTML("          <option value=\"\">".$RST['ORDEM'].".".$RST1['ORDEM'].". ".$RST1['TITULO']); }
    else
       { if ($cDbl[nvl($RST1['sq_projeto_etapa'],0)]==$cDbl[nvl($chave,0)]) { ShowHTML("          <option value=\"".$RST1['sq_projeto_etapa']."\" SELECTED>".$RST1['ORDEM'].". ".$RST1['TITULO']); } else { ShowHTML("          <option value=\"".$RST1['sq_projeto_etapa']."\">".$RST['ORDEM'].".".$RST1['ORDEM'].". ".$RST1['TITULO']); } }
    DB_GetSolicEtapa($RST2, $chaveAux, $RST1['sq_projeto_etapa'], 'LSTNIVEL');
    $RST2->Sort="ordem";
    while(!$RST2->EOF) {
      if ($restricao=="Grupo" && ($RST2['VINCULA_ATIVIDADE']=="N" || $cDbl[$RST2['PERC_CONCLUSAO']]>=100))
         { ShowHTML("          <option value=\"\">".$RST['ORDEM'].".".$RST1['ORDEM'].".".$RST2['ORDEM'].". ".$RST2['TITULO']); }
      else
         { if ($cDbl[nvl($RST2['sq_projeto_etapa'],0)]==$cDbl[nvl($chave,0)]) { ShowHTML("          <option value=\"".$RST2['sq_projeto_etapa']."\" SELECTED>".$RST2['ORDEM'].". ".$RST2['TITULO']); } else { ShowHTML("          <option value=\"".$RST2['sq_projeto_etapa']."\">".$RST['ORDEM'].".".$RST1['ORDEM'].".".$RST2['ORDEM'].". ".$RST2['TITULO']); } }
      DB_GetSolicEtapa($RST3, $chaveAux, $RST2['sq_projeto_etapa'], 'LSTNIVEL');
      $RST3->Sort="ordem";
      while(!$RST3->EOF) {
        if ($restricao=="Grupo" && ($RST3['VINCULA_ATIVIDADE']=="N" || $cDbl[$RST3['PERC_CONCLUSAO']]>=100))
           { ShowHTML("          <option value=\"\">".$RST['ORDEM'].".".$RST1['ORDEM'].".".$RST2['ORDEM'].".".$RST3['ORDEM'].". ".$RST3['TITULO']); }
        else
           { if ($cDbl[nvl($RST3['sq_projeto_etapa'],0)]==$cDbl[nvl($chave,0)]) { ShowHTML("          <option value=\"".$RST3['sq_projeto_etapa']."\" SELECTED>".$RST3['ORDEM'].". ".$RST3['TITULO']); } else { ShowHTML("          <option value=\"".$RST3['sq_projeto_etapa']."\">".$RST['ORDEM'].".".$RST1['ORDEM'].".".$RST2['ORDEM'].".".$RST3['ORDEM'].". ".$RST3['TITULO']); } }
        DB_GetSolicEtapa($RST4, $chaveAux, $RST3['sq_projeto_etapa'], 'LSTNIVEL');
        $RST4->Sort="ordem";
        while(!$RST4->EOF) {
          if ($restricao=="Grupo" && ($RST4['VINCULA_ATIVIDADE']=="N" || $cDbl[$RST4['PERC_CONCLUSAO']]>=100))
             { ShowHTML("          <option value=\"\">".$RST['ORDEM'].".".$RST1['ORDEM'].".".$RST2['ORDEM'].".".$RST3['ORDEM'].".".$RST4['ORDEM'].". ".$RST4['TITULO']); }
          else
             { if ($cDbl[nvl($RST4['sq_projeto_etapa'],0)]==$cDbl[nvl($chave,0)]) { ShowHTML("          <option value=\"".$RST4['sq_projeto_etapa']."\" SELECTED>".$RST4['ORDEM'].". ".$RST4['TITULO']); } else { ShowHTML("          <option value=\"".$RST4['sq_projeto_etapa']."\">".$RST['ORDEM'].".".$RST1['ORDEM'].".".$RST2['ORDEM'].".".$RST3['ORDEM'].".".$RST4['TITULO']); } }
          $RST4->MoveNext;
        }
          $RST3->MoveNext;
      }
      $RST2->MoveNext;
    }
    $RST1->MoveNext;
  }
  $RST->MoveNext;
}
ShowHTML("          </select>");

$RST=null;
$RST1=null;
$RST2=null;
$RST3=null;
$RST4=null;
return $function_ret;
}

// =========================================================================
// Retorna a prioridade a partir do código
// -------------------------------------------------------------------------
function RetornaPrioridade($p_chave) {
  extract($GLOBALS);
switch ($cDbl[Nvl($p_Chave,999)])
{
  case 0: $RetornaPrioridade='Alta';
          break;
  case 1: $RetornaPrioridade='Média';
          break;
  case 2: $RetornaPrioridade='Normal';
            break;
  default:$RetornaPrioridade='---';
          break;
}
return $function_ret;
}

// =========================================================================
// Retorna o tipo de visao a partir do código
// -------------------------------------------------------------------------
function RetornaTipoVisao($p_chave) {
  extract($GLOBALS);
  switch ($cDbl[$p_Chave]) {
    case 0: $RetornaTipoVisao='Completa';
            break;
    case 1: $RetornaTipoVisao='Parcial';
            break;
    case 2: $RetornaTipoVisao='Resumida';
            break;
    default:$RetornaTipoVisao='Erro';
            break;
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
     DB_GetPersonData($RS, $w_cliente, $w_usuario, $null, $null);
     return $RS['SQ_USUARIO_CENTRAL'];
     DesconectaBD();
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
  if ($_REQUEST['w_ano']>'') {
     return $_REQUEST['w_ano'];
  } elseif ($_SESSION['ANO'] > '') {
     return $_SESSION['ANO'];
  } else {
     return Date('Y');
  }
}

// =========================================================================
// Função que retorna o código do menu
// -------------------------------------------------------------------------
function RetornaMenu($p_cliente,$p_sigla) {
  extract($GLOBALS);
  // Se receber o código do menu do SIW, o código será determinado por parâmetro;
  // caso contrário, retornará o código retornado a partir da sigla.
  if ($_REQUEST['w_menu']>'') {
    $RetornaMenu=$_REQUEST['w_menu'];
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
  // Se receber o código do cliente do SIW, o cliente será determinado por parâmetro;
  // caso contrário, o cliente será a empresa ao qual o usuário logado está vinculado.
  if ($_REQUEST['w_cgccpf']>'' && strlen($_REQUEST['w_cgccpf'])>11) {
     $RS = DB_GetCompanyData($_SESSION['P_CLIENTE'], $_REQUEST('w_cgccpf'));
     if (!$RS->EOF) {
        return $RS['SQ_PESSOA'];
     } else {
        return $_SESSION['P_CLIENTE'];
     }
     DesconectaBD();
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
  return DB_GetGestor($p_solicitacao, $p_usuario);
}

// =========================================================================
// Rotina que encerra a sessão e fecha a janela do SIW
// -------------------------------------------------------------------------
function EncerraSessao() {
  extract($GLOBALS);
  ScriptOpen('JavaScript');
  ShowHTML(' alert("Tempo máximo de inatividade atingido! Autentique-se novamente."); ');
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
function DataHora() { return date('d/m/Y, H:i:s'); }

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
// Função que adiciona dias a uma data
// date: timestamp gerado a partir da funçao toDate()
// inc:  inteiro precedido do sinal de adição ou subtração de dias (+1, -3 etc.)
// -------------------------------------------------------------------------
function addDays($date,$inc) { 
  return mktime(date(H,$date), date(i,$date), date(s,$date), date(m,$date), date(d,$date)+$inc, date(Y,$date));
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
function EnviaMail($w_subject,$w_mensagem,$w_recipients) {
  extract($GLOBALS);
  include_once('classes/mail/class_email.php');
  $_mail = new EMAIL();
  $_mail->setHeader('Content-Type','text/html');
  $_mail->setHeader('Content-Transfer-Encoding','quoted-printable');
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
// Fim da rotina de envio de email
// -------------------------------------------------------------------------


// =========================================================================

// Rotina que extrai a última parte da variável TP

// -------------------------------------------------------------------------

function RemoveTP($TP)
{
  extract($GLOBALS);


$w_TP=$TP;
while((strpos($w_TP,"-") ? strpos($w_TP,"-")+1 : 0)>0)
{

  $w_TP=substr($w_TP,(strpos($w_TP,"-") ? strpos($w_TP,"-")+1 : 0)+1-1,strlen($w_TP));
}
$RemoveTP=str_replace(" -".$w_TP,"",$TP);
return $function_ret;
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
  $ExtractFileName=$fsa;
  return $function_ret;
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
function TrataErro($sp, $Err, $file, $line, $object) {
  extract($GLOBALS);

  if (strpos($Err,'ORA-02292')!==false || strpos($Err,'ORA-02292')!==false ) {
     // REGISTRO TEM FILHOS
     ScriptOpen('JavaScript');
     ShowHTML(' alert("Existem registros vinculados ao que você está excluindo. Exclua-os primeiro.\\n\\n'.substr($Err,0,(strpos($Err,chr(10)) ? strpos($Err,chr(10))+1 : 0)-1).'");');
     ShowHTML(' history.back(1);');
     ScriptClose;
  }
  elseif (strpos($Err,'ORA-02291')!==false || strpos($Err,'ORA-02291')!==false) {
     // REGISTRO NÃO ENCONTRADO
     ScriptOpen('JavaScript');
     ShowHTML(' alert("Registro não encontrado.");');
     ShowHTML(' history.back(1);');
     ScriptClose;
  }
  elseif (strpos($Err,'ORA-00001')!==false) {
     // REGISTRO JÁ EXISTENTE
    ScriptOpen('JavaScript');
    ShowHTML(' alert("Um dos campos digitados já existe no banco de dados e é único.\\n\\n'.substr($Err,0,(strpos($Err,chr(10)) ? strpos($Err,chr(10))+1 : 0)-1).'");');
    ShowHTML(' history.back(1);');
    ScriptClose;
  }
  elseif (strpos($Err,'ORA-03113')!==false ||
    strpos($Err,'ORA-03113')!==false ||
    strpos($Err,'ORA-03114')!==false ||
    strpos($Err,'ORA-03114')!==false ||
    strpos($Err,'ORA-12224')!==false ||
    strpos($Err,'ORA-12224')!==false ||
    strpos($Err,'ORA-12514')!==false ||
    strpos($Err,'ORA-12514')!==false ||
    strpos($Err,'ORA-12541')!==false ||
    strpos($Err,'ORA-12541')!==false ||
    strpos($Err,'ORA-12545')!==false ||
    strpos($Err,'ORA-12545')!==false) {

    ScriptOpen('JavaScript');
    ShowHTML(' alert("Banco de dados fora do ar. Aguarde alguns instantes e tente novamente!");');
    ShowHTML(' history.back(1);');
    ScriptClose;
  }
  else {
    $w_html='<html><BASEFONT FACE="Arial"><body BGCOLOR="#FF5555" TEXT="#FFFFFF">';
    $w_html=$w_html.'<CENTER><H2>ATENÇÃO</H2></CENTER>';
    $w_html=$w_html.'<BLOCKQUOTE>';
    $w_html=$w_html.'<P ALIGN="JUSTIFY">Erro não previsto. <b>Uma cópia desta tela foi enviada por e-mail para os responsáveis pela correção. Favor tentar novamente mais tarde.</P>';
    $w_html=$w_html.'<TABLE BORDER="2" BGCOLOR="#FFCCCC" CELLPADDING="5"><TR><TD><FONT COLOR="#000000">';
    $w_html=$w_html.'<DL><DT>Data e hora da ocorrência: <FONT FACE="courier">'.date('d/m/Y, h:i:s').'<br><br></font></DT>';
    $w_html=$w_html.'<DT>Descrição:<DD><FONT FACE="courier">'.$Err.'<br><br></font>';
    $w_html=$w_html.'<DT>Arquivo:<DD><FONT FACE="courier">'.$file.', linha: '.$line.'<br><br></font>';
    $w_html=$w_html.'<DT>Objeto:<DD><FONT FACE="courier">'.$object.'<br><br></font>';

    //$w_html=$w_html.'<DT>Comando em execução: <FONT FACE="courier">'.$Err.'<br><br></font></DT>';
    //$w_html=$w_html."<DT>Parâmetros do objeto:<DD><FONT FACE=\"courier\" size=1>";
    //foreach ($sp->Parameters as $w_Item) {
    //  $w_html=$w_html.$w_Item->Name." => ['.$w_Item->Value.']<br>";
    //}
    //$w_html=$w_html."   <br><br></font>";

    $w_html=$w_html.'<DT>Dados da querystring:';
    foreach($_GET as $chv => $vlr) { $w_html=$w_html.'<DD><FONT FACE="courier" size=1>'.$chv.' => ['.$vlr.']<br>'; }

    $w_html=$w_html.'</DT>';
    $w_html=$w_html.'<DT>Dados do formulário:';
    foreach($_POST as $chv => $vlr) { $w_html=$w_html.'<DD><FONT FACE="courier" size=1>'.$chv.' => ['.$vlr.']<br>'; }

    $w_html=$w_html.'</DT>';
    $w_html=$w_html.'   <br><br></font>';
    $w_html=$w_html.'</DT>';
    $w_html=$w_html.'<DT>Variáveis de sessão:<DD><FONT FACE="courier" size=1>';
    foreach($_SESSION as $chv => $vlr) { if (strpos(strtoupper($chv),'SENHA') !== true) { $w_html=$w_html.$chv.' => ['.$vlr.']<br>'; } }
    $w_html=$w_html.'</DT>';
    $w_html=$w_html.'   <br><br></font>';
    $w_html=$w_html.'<DT>Variáveis de servidor:<DD><FONT FACE="courier" size=1>';
    $w_html=$w_html.' SCRIPT_NAME => ['.$_SERVER['SCRIPT_NAME'].']<br>';
    $w_html=$w_html.' SERVER_NAME => ['.$_SERVER['SERVER_NAME'].']<br>';
    $w_html=$w_html.' SERVER_PORT => ['.$_SERVER['SERVER_PORT'].']<br>';
    $w_html=$w_html.' SERVER_PROTOCOL => ['.$_SERVER['SERVER_PROTOCOL'].']<br>';
    $w_html=$w_html.' HTTP_ACCEPT_LANGUAGE => ['.$_SERVER['HTTP_ACCEPT_LANGUAGE'].']<br>';
    $w_html=$w_html.' HTTP_USER_AGENT => ['.$_SERVER['HTTP_USER_AGENT'].']<br>';
    $w_html=$w_html.'</DT>';
    $w_html=$w_html.'   <br><br></font>';
    $w_html=$w_html.'</FONT></TD></TR></TABLE><BLOCKQUOTE>';
    /*
    $w_resultado=EnviaMail('ERRO SIW',$w_html,'alex@sbpi.com.br; celso@sbpi.com.br');
    if ($w_resultado>'') {
       $w_html=$w_html.'<SCRIPT LANGUAGE="JAVASCRIPT">';
       $w_html=$w_html.'   alert("Não foi possível enviar o e-mail comunicando sobre o erro. Favor copiar esta página e enviá-la por e-mail aos gestores do sistema.");';
       $w_html=$w_html.'</SCRIPT>';
    }
    */
    $w_html=$w_html.'</body></html>';
    ShowHTML($w_html);
  }
  exit();
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
  if ($cDbl[$l_cliente]==6761) {
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
     DB_GetLinkDataUser($l_RS, $_SESSION['P_CLIENTE'], $_SESSION['SQ_PESSOA'], $null);
     $l_cont=0;
     while(!$l_RS->EOF) {
       $l_titulo=$l_RS['NOME'];
       if ($cDbl[$l_RS['Filho']]>0) {

         $l_cont=$l_cont+1;
         ShowHTML('            <LI class=menubar>::<A class=starter href="#"> '.$l_RS['NOME'].'</A>');
         ShowHTML('            <UL class=menu id=menu'.$l_cont.'>');
         $l_cont1=0;
         DB_GetLinkDataUser($l_RS1, $_SESSION['P_CLIENTE'], $_SESSION['SQ_PESSOA']);
         $l_RS['SQ_MENU'];
         while(!$l_RS1->EOF) {
           $l_titulo=$l_titulo.' - '.$l_RS1['NOME'];
           if ($cDbl[$l_RS1['Filho']]>0) {
              $l_cont1=$l_cont1+1;
              ShowHTML('              <LI><A href="#"><IMG height=12 alt=">" src="/siw/files/'.$w_cliente.'/img/arrows.gif" width=8> '.$l_RS1['NOME'].'</A> ');
              ShowHTML('              <UL class=menu id=menu'.$l_cont.'_'.$l_cont1.'>');
              $l_cont2=0;
              DB_GetLinkDataUser($l_RS2, $_SESSION['P_CLIENTE'], $_SESSION['SQ_PESSOA']);
              $l_RS1['SQ_MENU'];
              while(!$l_RS2->EOF) {
                $l_titulo=$l_titulo.' - '.$l_RS2['NOME'];
                if ($cDbl[$l_RS2['Filho']]>0) {
                   $l_cont2=$l_cont2+1;
                   ShowHTML('                <LI><A href="#"><IMG height=12 alt=">" src="/siw/files/'.$w_cliente.'/img/arrows.gif" width=8> '.$l_RS2['NOME'].'</A> ');
                   ShowHTML('                <UL class=menu id=menu'.$l_cont.'_'.$l_cont1.'_'.$l_cont2.'>');
                   DB_GetLinkDataUser($l_RS3, $_SESSION['P_CLIENTE'], $_SESSION['SQ_PESSOA']);
                   $l_RS2['SQ_MENU'];
                   while(!$l_RS3->EOF) {
                     $l_titulo=$l_titulo.' - '.$l_RS3['NOME'];
                     if ($l_RS3['EXTERNO']=='S') {
                       ShowHTML('                  <LI><A href="'.str_replace('@files',$conFileVirtual.$_SESSION['P_CLIENTE'],$l_RS3['LINK']).'" TARGET="'.$l_RS3['TARGET'].'">'.$l_RS3['NOME'].'</A> ');
                     } else {
                       ShowHTML('                  <LI><A href="'.$l_RS3['LINK'].'&P1='.$l_RS3['P1'].'&P2='.$l_RS3['P2'].'&P3='.$l_RS3['P3'].'&P4='.$l_RS3['P4'].'&TP='.$l_titulo.'&SG='.$l_RS3['SIGLA'].'">'.$l_RS3['NOME'].'</A> ');
                     }
                     $l_titulo=str_replace(' - '.$l_RS3['NOME'],'',$l_titulo);
                     $l_RS3->MoveNext;
                   }
                   ShowHTML('            </UL>');
                   $l_RS3->Close;
                } else {
                   if ($l_RS2['EXTERNO']=='S') {
                      ShowHTML('                <LI><A href="'.str_replace('@files',$conFileVirtual.$_SESSION['P_CLIENTE'],$l_RS2['LINK']).'" TARGET="'.$l_RS2['TARGET'].'">'.$l_RS2['NOME'].'</A> ');
                   } else {
                      ShowHTML('                <LI><A href="'.$l_RS2['LINK'].'&P1='.$l_RS2['P1'].'&P2='.$l_RS2['P2'].'&P3='.$l_RS2['P3'].'&P4='.$l_RS2['P4'].'&TP='.$l_titulo.'&SG='.$l_RS2['SIGLA'].'">'.$l_RS2['NOME'].'</A> ');
                   }
                }
                $l_titulo=str_replace(' - '.$l_RS2['NOME'],'',$l_titulo);
                $l_RS2->MoveNext;
              }
              ShowHTML('            </UL>');
              $l_RS2->Close;
           } else {
              if ($l_RS1['EXTERNO']=='S') {
                 if ($l_RS1['LINK']>'') {
                    ShowHTML('              <LI><A href="'.str_replace('@files',$conFileVirtual.$_SESSION['P_CLIENTE'],$l_RS1['LINK']).'" TARGET="'.$l_RS1['TARGET'].'">'.$l_RS1['NOME'].'</A> ');
                 } else {
                   ShowHTML('              <LI>'.$l_RS1['NOME'].' ');
                 }
              } else {
                 ShowHTML('              <LI><A href="'.$l_RS1['LINK'].'&P1='.$l_RS1['P1'].'&P2='.$l_RS1['P2'].'&P3='.$l_RS1['P3'].'&P4='.$l_RS1['P4'].'&TP='.$l_titulo.'&SG='.$l_RS1['SIGLA'].'">'.$l_RS1['NOME'].'</A> ');
              }
           }
           $l_titulo=str_replace(' - '.$l_RS1['NOME'],'',$l_titulo);
           $l_RS1->MoveNext;
         }
         ShowHTML('            </UL>');
         $l_RS1->Close;

       } else {
          if ($l_RS['EXTERNO']=='S') {
             ShowHTML('            <LI class=menubar>::<A class=starter href="'.str_replace('@files',$conFileVirtual.$_SESSION['P_CLIENTE'],$l_RS['LINK']).'" TARGET="'.$l_RS['TARGET'].'"> '.$l_RS['NOME'].'</A>');
          } else {
            ShowHTML('            <LI class=menubar>::<A class=starter href="'.$l_RS['LINK'].'&P1='.$l_RS['P1'].'&P2='.$l_RS['P2'].'&P3='.$l_RS['P3'].'&P4='.$l_RS['P4'].'&TP='.$l_titulo.'&SG='.$l_RS['SIGLA'].'"> '.$l_RS['NOME'].'</A>');
          }
       }
       $l_RS->MoveNext;
     }
     $l_RS->Close;
     ShowHTML('            <LI class=menubar>::<A class=starter href="'.$w_dir.'Menu.php?par=Sair" & " onClick="return(confirm("Confirma saída do sistema?"));"> Sair</A>');
     ShowHTML('          </UL>');
     ShowHTML('        </DIV>');
     ShowHTML('      </DIV>');
     ShowHTML('    </DIV>');
  }
}

// =========================================================================
// Abre conexão com o banco de dados
// -------------------------------------------------------------------------
function AbreSessao() {
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
   if (db_verificaAssinatura::getInstanceOf($dbms, $_SESSION["P_CLIENTE"],$Usuario,$Senha) ==0)
      return true;
    else
      return false;
}

// =========================================================================
// Função que formata dias, horas, minutos e segundos a partir dos segundos
// -------------------------------------------------------------------------
function FormataDataEdicao($w_dt_grade) {
  $l_dt_grade = $w_dt_grade;
  if ($l_dt_grade > '') {
     if (strlen($l_dt_grade) < 10) {
        if (substr($l_dt_grade,3,1) != '/') { $l_dt_grade = '0'.$l_dt_grade; }
        if (strlen($l_dt_grade) < 10 && (substr($l_dt_grade,6,1) != '/')) {
           $l_dt_grade = substr($l_dt_grade,1,3).'0'.substr($l_dt_grade,4,6);
        }
     }
  }
  else { $l_dt_grade = ''; }
  return $l_dt_grade;
  $l_dt_grade = $null;
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
function BodyOpenMail($cProperties) {
  extract($GLOBALS);
  $l_html=$l_html.'<link rel="stylesheet" type="text/css" href="'.$conRootSIW.'classes/menu/xPandMenu.css">';
  $l_html=$l_html.'<body Text="'.$conBodyText.'" Link="'.$conBodyLink.'" Alink="'.$conBodyALink.'" '.
    'Vlink="'.$conBodyVLink.'" Bgcolor="'.$conBodyBgcolor.'" Background="'.$conBodyBackground.'" '.
    'Bgproperties="'.$conBodyBgproperties.'" Topmargin="'.$conBodyTopmargin.'" '.
    'Leftmargin="'.$conBodyLeftmargin.'" '.$cProperties.'> '.'\r\n';
  $BodyOpenMail=$l_html;
  $l_html=null;
}
?>