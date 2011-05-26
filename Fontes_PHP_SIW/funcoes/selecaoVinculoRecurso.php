<?php
// =========================================================================
// Montagem da seleção de vinculações de recurso.
// -------------------------------------------------------------------------
function selecaoVinculoRecurso($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo,$colspan=1) {
  extract($GLOBALS);
  
  if (nvl($restricao,'')=='') {
    $l_chave = upper($chave);
    include_once($w_dir_volta.'classes/sp/db_getSiwCliModLis.php');
    
    // Verifica se o cliente tem o módulo de recursos logísticos contratado
    $sql = new db_getSiwCliModLis; $RS = $sql->getInstanceOf($dbms, $w_cliente, null, 'SR');
    $w_mod_sr = ''; foreach ($RS as $row) $w_mod_sr = f($row,'nome');

    ShowHTML('          <td colspan="'.$colspan.'"'.((isset($hint)) ? ' title="'.$hint.'"' : '').'>'.((isset($label)) ? '<b>'.$label.'</b><br>' : '').'<SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
    ShowHTML('          <option value=""/>Não vinculado');
    ShowHTML('          <option value="PESSOA"'.((nvl($l_chave,'')=='PESSOA') ? ' SELECTED' : '').'>Vinculado a pessoa');
    if ($w_mod_sr!='') ShowHTML('          <option value="VEÍCULO"'.((nvl($l_chave,'')=='VEÍCULO') ? ' SELECTED' : '').'>Vinculado a veículo');
    ShowHTML('          </select>');
  } else {
    $l_chave = upper($chaveAux);
    // Se restrição for informado, chama exibe seleção do objeto
    switch ($l_chave) {
      case 'PESSOA': 
        include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
        SelecaoPessoa('<u>P</u>essoa:','P','Selecione a pessoa vinculada ao recurso.',$chave,null,'w_ch_vinculo','USUARIOS',$atributo,$colspan=1); 
        break;
      case 'VEÍCULO':
        include_once($w_dir_volta.'funcoes/selecaoVeiculo.php');
        SelecaoVeiculo('<u>V</u>eículo:','V','Selecione o veículo',$w_cliente,$chave,null,'w_ch_vinculo',null,$atributo,$colspan=1);
        break;
      case 'EQUIPAMENTO DE TI': 
        break;
    }
  }
}
?>
