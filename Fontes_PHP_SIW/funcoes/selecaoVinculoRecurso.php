<?
// =========================================================================
// Montagem da sele��o de vincula��es de recurso.
// -------------------------------------------------------------------------
function selecaoVinculoRecurso($label,$accesskey,$hint,$chave,$chaveAux,$campo,$restricao,$atributo) {
  extract($GLOBALS);
  
  if (nvl($restricao,'')=='') {
    $l_chave = strtoupper($chave);
    include_once($w_dir_volta.'classes/sp/db_getSiwCliModLis.php');
    
    // Verifica se o cliente tem o m�dulo de recursos log�sticos contratado
    $RS = db_getSiwCliModLis::getInstanceOf($dbms, $w_cliente, null, 'SR');
    $w_mod_sr = ''; foreach ($RS as $row) $w_mod_sr = f($row,'nome');

    // Se restri��o n�o for informado, exibe sele��o dos objetos aos quais o recurso pode ser ligado
    if (!isset($hint)) {
      ShowHTML('          <td valign="top"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
    } else {
      ShowHTML('          <td valign="top" title="'.$hint.'"><font size="1"><b>'.$label.'</b><br><SELECT ACCESSKEY="'.$accesskey.'" CLASS="sts" NAME="'.$campo.'" '.$w_Disabled.' '.$atributo.'>');
    }
    ShowHTML('          <option value="">N�o vinculado');
    if (nvl($l_chave,'')=='PESSOA') ShowHTML('          <option value="PESSOA" SELECTED>Vinculado a pessoa'); else ShowHTML('          <option value="PESSOA">Vinculado a pessoa');
    //if (nvl($l_chave,'')=='EQUIPAMENTO DE TI')  ShowHTML('          <option value="EQUIPAMENTO DE TI" SELECTED>Equipamento de TI');   else ShowHTML('          <option value="EQUIPAMENTO DE TI">Equipamento de TI');
    if ($w_mod_sr!='') if (nvl($l_chave,'')=='VE�CULO') ShowHTML('          <option value="VE�CULO" SELECTED>Vinculado a ve�culo'); else ShowHTML('          <option value="VE�CULO">Vinculado a ve�culo');
    ShowHTML('          </select>');
  } else {
    $l_chave = strtoupper($chaveAux);
    // Se restri��o for informado, chama exibe sele��o do objeto
    switch ($l_chave) {
      case 'PESSOA': 
        include_once($w_dir_volta.'funcoes/selecaoPessoa.php');
        SelecaoPessoa('<u>P</u>essoa:','P','Selecione a pessoa vinculada ao recurso.',$chave,null,'w_ch_vinculo','USUARIOS',$atributo); 
        break;
      case 'VE�CULO':
        include_once($w_dir_volta.'funcoes/selecaoVeiculo.php');
        SelecaoVeiculo('<u>V</u>e�culo:','V','Selecione o ve�culo',$w_cliente,$chave,null,'w_ch_vinculo',null,$atributo);
        break;
      case 'EQUIPAMENTO DE TI': 
        break;
    }
  }
}
?>
