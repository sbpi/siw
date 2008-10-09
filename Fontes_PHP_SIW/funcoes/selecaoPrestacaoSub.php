<?
include_once($w_dir_volta.'classes/sp/db_getPrestacaoContas.php');
// =========================================================================
// Montagem da seleção das prestações de contas
// -------------------------------------------------------------------------
function selecaoPrestacaoSub($label,$accesskey,$hint,$chave,$chave_aux,$campo,$tipo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  $RS = db_getPrestacaoContas::getInstanceOf($dbms, $w_cliente, $chave_aux, null, null, $tipo, 'S', $restricao);
  $RS = SortArray($RS,'nome_completo','asc'); 
  if (!isset($hint)) {
    ShowHTML('          <td colspan="'.$colspan.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  } else {
    ShowHTML('          <td colspan="'.$colspan.'" title="'.$hint.'"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
  }
  ShowHTML('          <OPTION VALUE="">---');
  $w_qtd_projeto = 0;
  foreach($RS as $row)  {
    // Testa se prestacao de contas já tem solicitação vinculada. Se tiver, não pode ser pai de nenhum outro item
    // Garante que as prestações de contas sempre estarão ligados no nível folha da tabela de prestacao de contas
    if(nvl(f($row,'sq_prestacao_pai'),'')=='') {
      $w_qtd_solic = f($row,'qtd_solic');
    } 
    if ($w_qtd_solic==0) {
      if (f($row,'chave')==nvl($chave,0)) {
        ShowHTML('          <option value="'.f($row,'chave').'" SELECTED>'.f($row,'nome_completo'));
      } else {
        ShowHTML('          <option value="'.f($row,'chave').'">'.f($row,'nome_completo'));
      }
    }
  }
  ShowHTML('          </SELECT>');
}
?>
