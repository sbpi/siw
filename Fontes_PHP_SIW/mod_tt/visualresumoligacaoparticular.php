<?php
// =========================================================================
// Rotina de visualização das ligações particulares
// -------------------------------------------------------------------------
function ResumLigPart($l_sq_usuario,$p_inicio,$p_fim,$p_ativo,$O){
  extract($GLOBALS);
  global $l_Disabled;
  global $l_soma;
  if ($O=='L') {
    $sql = new db_getCall; $RS = $sql->getInstanceOf($dbms,$w_cliente,null,$l_sq_usuario,"1",null,null,null,null,$p_inicio,$p_fim,'N');
    $RS = SortArray($RS,'phpdt_ordem','asc');
    ShowHTML('<tr><td><font size="2">');
    ShowHTML('    <td align="right"><font size="1"><b>Registros: '.count($RS));
    ShowHTML('<tr><td align="center" colspan="3">');
    ShowHTML('    <TABLE WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">');
    ShowHTML('        <tr bgcolor="'.$conTrBgColor.'" align="center">');
    ShowHTML('          <td><font size="1"><b>Tipo    </font></td>');
    ShowHTML('          <td><font size="1"><b>Data    </font></td>');
    ShowHTML('          <td><font size="1"><b>Número  </font></td>');
    ShowHTML('          <td><font size="1"><b>Duração </font></td>');
    ShowHTML('          <td><font size="1"><b>RM      </font></td>');
    ShowHTML('          <td><font size="1"><b>Local   </font></td>');
    ShowHTML('          <td><font size="1"><b>Nome    </font></td>');
    ShowHTML('        </tr>');
    if (count($RS) <= 0){
      ShowHTML('      <tr bgcolor="'.$conTrBgColor.'"><td colspan="7" align="center"><font size="2"><b>Não foram encontrados registros.</b></td></tr>');
    } else {
      foreach($RS as $row) {
        $l_responsavel = f($row,'responsavel_nome');
        if (f($row,'tipo')=='ORI' || $P2==0) {
          if ($l_cor==$conTrBgColor || $l_cor=='')$l_cor=$conTrAlternateBgColor; else $l_cor=$conTrBgColor;
          $l_cor_fonte='';                     
          if (nvl(f($row,'trabalho'),'')=='' && f($row,'sq_usuario_central')>'') {
            $l_negrito='<b>';
            if (nvl(f($row,'sq_usuario_central'),0)!=nvl($l_sq_usuario_central,0)) {
              $l_cor_fonte='color="#0011FF"';
            }  
          } else {  
            $l_negrito='';
          }
          ShowHTML('      <tr bgcolor="'.$l_cor.'">');
          ShowHTML('      <td align="center"><font size="1"'.$l_cor_fonte.'>'.f($row,'tipo').'</td>');
          ShowHTML('      <td nowrap align="center"><font size="1"'.$l_cor_fonte.'>'.$l_negrito.FormataDataEdicao(f($row,'phpdt_ordem'),3).'</td>');
          ShowHTML('      <td><font size="1"'.$l_cor_fonte.'>'.f($row,'numero').'</td>');
          ShowHTML('      <td align="center"><font size="1" '.$l_cor_fonte.'>'.FormataTempo(f($row,'duracao')).'&nbsp;</td>');
          ShowHTML('      <td align="center"><font size="1" '.$l_cor_fonte.'>'.f($row,'sq_ramal').'</td>');
          ShowHTML('      <td><font size="1" '.$l_cor_fonte.'>'.f($row,'localidade').'</td>');
          ShowHTML('      <td><font size="1" '.$l_cor_fonte.'>'.f($row,'d_nome').'</td>');
          ShowHTML('      </td>');
          ShowHTML('      </tr>');
          $l_soma=$l_soma+f($row,'duracao');
        }
      }   
      if ($l_cor==$conTrBgColor || $l_cor=='') $l_cor=$conTrAlternateBgColor; else $l_cor=$conTrBgColor;
      ShowHTML('      <tr bgcolor="'.$l_cor.'">');
      ShowHTML('        <td align="right" colspan=3><font size="1"><b>Duração total:</font></td>');
      ShowHTML('        <td align="center"><font size="1"><b>'.FormataTempo($l_soma).'&nbsp;</td>');
      ShowHTML('        <td colspan=7><font size="1">&nbsp;</font></td>');
      ShowHTML('      </tr>');
    }
    ShowHTML('      </center>');
    ShowHTML('    </table>');   
    // Se for impressão, coloca autorização para desconto em folha
    if ($P2==1) {
      ShowHTML('      <tr><td valign="top" colspan=3><font size="3"><blockquote><p align="justify">Eu, <b>'.$l_responsavel.'</b>, autorizo o débito do valor em meu pagamento.</p></blockquote></td>');
    }
    ShowHTML('  </td>');
    ShowHTML('</tr>');
    ShowHTML('<tr><td align="center" colspan=3>');
    ShowHTML('</tr>');
  }  
  ShowHTML('      </center>');
  ShowHTML('    </table>');
  ShowHTML('  </td>');
  ShowHTML('</tr>');
} 
?>