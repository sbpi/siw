<?php
include_once($w_dir_volta.'classes/sp/db_getFoneList.php');
include_once($w_dir_volta.'classes/sp/db_getVincKindData.php');
include_once($w_dir_volta.'classes/sp/db_getAddressList.php');
include_once($w_dir_volta.'classes/sp/db_getContaBancoList.php');
include_once($w_dir_volta.'classes/sp/db_getGPFolhaPontoMensal.php');

// =========================================================================
// Rotina de visualização do currículo
// -------------------------------------------------------------------------
function visualFicha($l_cliente,$l_usuario,$O,$p_formato=0) {
  extract($GLOBALS);
  $html=chr(13). "<script>";
  $html.=chr(13). "jQuery(document).ready(function(){";
  $html.=chr(13). "$('.accordion .head').click(function() {";
  $html.=chr(13). "$(this).next().toggle('slow');";
  $html.=chr(13). "return false;";
  $html.=chr(13). "}).next().hide();";
  $html.=chr(13). "});";
  $html.=chr(13). "</script>";
  if (1==1) {
    // Se for listagem dos dados
    // Identificação pessoal
    $sql = new db_getCV; $RS = $sql->getInstanceOf($dbms,$l_cliente,$l_usuario,'CVIDENT','DADOS');
    foreach ($RS as $row) {$RS=$row; break;}
        
    // Recupera os dados do colaborador a partir do código da pessoa
    $sql = new db_getGPColaborador; $RSDocumentacao = $sql->getInstanceOf($dbms,$l_cliente,$l_usuario,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
    foreach ($RSDocumentacao as $row) {$RSDocumentacao = $row; break;}   
    
   
    //Recupera os dados dos contratos do colaborador
    $sql = new db_getGPContrato; $RSContrato = $sql->getInstanceOf($dbms,$w_cliente,null,$l_usuario,null,null,null,null,null,null,null,null,null,null);
    $RSContrato = SortArray($RSContrato,'inicio','desc');
    if (Nvl(f($RS,'inclusao'),'')=='') {
      $html.= '<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Curriculum não informado.</b><br><br><br><br><br><br><br><br><br><br></center></div>';
    } else {
      $w_nome   = f($RS,'nome');
      $html.='<div align=center><center>';
      $html.=chr(13).'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
      $html.=chr(13).'<tr><td align="center">';
      $html.=chr(13).'    <table id="pai" width="100%" border="0">';
      $html.=chr(13).'      <tr><td align="center" colspan="3"><font size=3><b>'.f($RS,'nome').'</b></font></td></tr>';
      $html.=chr(13).'      <tr><td><table width="99%" border="0">';
      $html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DADOS PESSOAIS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';      
      $html.=chr(13).'      <tr valign="top"><td width="30%"><b>Nome:</b></td>';
      $html.=chr(13).'        <td>'.f($RS,'nome').' </td>';
      if (nvl(f($RS,'sq_siw_arquivo'),'nulo')!='nulo') {
        if ($p_formato=='HTML') {
          $html.=chr(13).'          <td rowspan=8>'.LinkArquivo('HL',$w_cliente,f($RS,'sq_siw_arquivo'),'_blank',null,'<img title="clique para ver em tamanho original." border=1 width=100 src="'.LinkArquivo(null,$w_cliente,f($RS,'sq_siw_arquivo'),null,null,null,'EMBED').'">',null).'</td>';
        } else {
          $html.=chr(13).'          <td rowspan=8><img border=1 width=100 height=133 src="'.$conFileVirtual.$l_cliente.'/'.f($RS,'ln_foto').'"></td>';
        } 
      }
      $html.=chr(13).'      <tr><td><b>Nome resumido:</b></td>';
      $html.=chr(13).'        <td>'.f($RS,'nome_resumido').' </td></tr>';
      $html.=chr(13).'      <tr><td><b>Data nascimento:</b></td>';
      $html.=chr(13).'        <td>'.FormataDataEdicao(f($RS,'nascimento')).' </td></tr>';
      $html.=chr(13).'      <tr><td><b>Sexo:</b></td>';
      $html.=chr(13).'        <td>'.f($RS,'nm_sexo').' </td></tr>';
      $html.=chr(13).'      <tr><td><b>Estado civil:</b></td>';
      $html.=chr(13).'        <td>'.f($RS,'nm_estado_civil').'</td></tr>';
      $html.=chr(13).'      <tr><td><b>Formação acadêmica:</b></td>';
      $html.=chr(13).'        <td>'.f($RS,'nm_formacao').' </td></tr>';
      $html.=chr(13).'      <tr><td><b>Etnia:</b></td>';
      $html.=chr(13).'        <td>'.f($RS,'nm_etnia').' </td></tr>';
      $html.=chr(13).'      <tr><td><b>Deficiência:</b></td>';
      $html.=chr(13).'        <td>'.Nvl(f($RS,'nm_deficiencia'),'---').' </td></tr>';    
      $html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>LOCAL DE NASCIMENTO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $html.=chr(13).'      <tr valign="top">';
      $html.=chr(13).'          <td><b>País:</b></td>';
      $html.=chr(13).'          <td>'.f($RS,'nm_pais_nascimento').' </td></tr>';
      $html.=chr(13).'      <tr><td><b>Estado:</b></td>';
      $html.=chr(13).'      <td>'.f($RS,'nm_uf_nascimento').' </td></tr>';
      $html.=chr(13).'      <tr><td><b>Cidade:</b></td>';
      $html.=chr(13).'          <td>'.f($RS,'nm_cidade_nascimento').' </td></tr>';
      $html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>DOCUMENTAÇÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $html.=chr(13).'      <tr valign="top">';
      $html.=chr(13).'          <td><b>Identidade:</b></td>';
      $html.=chr(13).'      <td>'.f($RS,'rg_numero').' </td></tr>';
      $html.=chr(13).'          <td><b>Emissor:</b></td>';
      $html.=chr(13).'      <td>'.f($RS,'rg_emissor').' </td></tr>';
      $html.=chr(13).'          <td><b>Data de emissão:</b></td>';
      $html.=chr(13).'      <td>'.FormataDataEdicao(f($RS,'rg_emissao')).' </td></tr>';
      $html.=chr(13).'      <tr valign="top">';
      $html.=chr(13).'          <td><b>CPF:</b></td>';
      $html.=chr(13).'      <td>'.f($RS,'cpf').'</td></tr>';
      $html.=chr(13).'          <td><b>Passaporte:</b></td>';
      $html.=chr(13).'      <td>'.Nvl(f($RS,'passaporte_numero'),'---').' </td></tr>';
      $html.=chr(13).'          <td valign="top"><b>País emissor:</b></td>';
      $html.=chr(13).'      <td>'.Nvl(f($RS,'nm_pais_passaporte'),'---').' </td></tr>';
      $html.=chr(13).'          <td valign="top"><b>Número CTPS:</b></td>';
      $html.=chr(13).'      <td>'.Nvl(f($RSDocumentacao,'ctps_numero'),'---').' </td></tr>';          
      $html.=chr(13).'          <td valign="top"><b>Série:</b></td>';
      $html.=chr(13).'      <td>'.Nvl(f($RSDocumentacao,'ctps_serie'),'---').' </td></tr>';          
      $html.=chr(13).'          <td valign="top"><b>Emissor:</b></td>';
      $html.=chr(13).'      <td>'.Nvl(f($RSDocumentacao,'ctps_emissor'),'---').' </td></tr>';          
      $html.=chr(13).'          <td valign="top"><b>Data de emissão da CTPS:</b></td>';
      $html.=chr(13).'      <td>'.FormataDataEdicao(Nvl(f($RSDocumentacao,'ctps_emissao_data'),'---')).' </td></tr>';
      $html.=chr(13).'          <td valign="top"><b>Optante pelo:</b></td>';
      if (Nvl(f($RSDocumentacao,'pis_pasep'),'---')=='I') {
        $html.=chr(13).'      <td>PIS</td></tr>';
      } else {
         $html.=chr(13).'      <td>PASEP</td></tr>';
      } 
      $html.=chr(13).'          <td valign="top"><b>Número PIS/PASEP:</b></td>';
      $html.=chr(13).'      <td>'.Nvl(f($RSDocumentacao,'pispasep_numero'),'---').' </td></tr>';                    
      $html.=chr(13).'          <td valign="top"><b>Emissão PIS/PASEP:</b></td>';
      $html.=chr(13).'      <td>'.FormataDataEdicao(Nvl(f($RSDocumentacao,'pispasep_cadastr'),'---')).' </td></tr>';
      $html.=chr(13).'          <td valign="top"><b>Número título eleitor:</b></td>';
      $html.=chr(13).'      <td>'.Nvl(f($RSDocumentacao,'te_numero'),'---').' </td></tr>';
      $html.=chr(13).'          <td valign="top"><b>Zona:</b></td>';
      $html.=chr(13).'      <td>'.Nvl(f($RSDocumentacao,'te_zona'),'---').' </td></tr>';
      $html.=chr(13).'          <td valign="top"><b>Seção:</b></td>';
      $html.=chr(13).'      <td>'.Nvl(f($RSDocumentacao,'te_secao'),'---').' </td></tr>';      
      $html.=chr(13).'          <td valign="top"><b>Certificado reservista:</b></td>';
      $html.=chr(13).'      <td>'.Nvl(f($RSDocumentacao,'reservista_numero'),'---').' </td></tr>';      
      $html.=chr(13).'          <td valign="top"><b>CSM:</b></td>';
      $html.=chr(13).'      <td>'.Nvl(f($RSDocumentacao,'reservista_csm'),'---').' </td></tr>';
      $html.=chr(13).'          <td valign="top"><b>Tipagem sangüínea:</b></td>';
      $html.=chr(13).'      <td>'.Nvl(f($RSDocumentacao,'tipo_sangue'),'---').' </td></tr>';            
      $html.=chr(13).'          <td valign="top"><b>Doador de sangue?</b></td>';
      if(Nvl(f($RSDocumentacao,'doador_sangue'),'N')=='S'){
        $html.=chr(13).'      <td>Sim</td></tr>';
      }else{
        $html.=chr(13).'      <td>Não</td></tr>';
      }      
      $html.=chr(13).'          <td valign="top"><b>Doador de órgãos?</b></td>';
      if(Nvl(f($RSDocumentacao,'doador_orgaos'),'N')=='S'){
        $html.=chr(13).'      <td>Sim</td></tr>';
      }else{
        $html.=chr(13).'      <td>Não</td></tr>';
      }
      $html.=chr(13).'          <td valign="top"><b>Observações</b></td>';
      $html.=chr(13).'      <td>'.Nvl(f($RSDocumentacao,'observacoes'),'---').' </td></tr>';
      
      $sql = new db_getGpPensionista; $RSPensao = $sql->getInstanceOf($dbms,null,$w_cliente,$w_usuario);
      $RSPensao = SortArray($RSPensao,'nome','asc');
      if (count($RSPensao)) {
        $html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>PENSIONISTAS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
        foreach($RSPensao as $row) {
          $html.=chr(13).'      <tr><td colspan=2 bgColor="#f0f0f0"style="border: 1px solid rgb(0,0,0);" ><b>';
          $html.=chr(13).'          '.f($row,'nome').' ('.f($row,'nome_resumido').') - '.f($row,'cpf').'</b>';
          $html.=chr(13).'          <tr><td><b>Tipo de pensão:</b></td>'; 
          $html.=chr(13).'              <td>'.f($row,'tipo_pensao').': '.formatNumber(f($row,'valor')).'</td></tr>';
          $html.=chr(13).'          <tr><td><b>Período de pagamento:</b></td>'; 
          $html.=chr(13).'              <td>'.formataDataEdicao(f($row,'inicio')).((nvl(f($row,'fim'),'')=='') ? ' em diante' : ' a '.formataDataEdicao(f($row,'fim'))).'</td></tr>';
          $sql = new db_getBenef; $RSQuery1 = $sql->getInstanceOf($dbms,$w_cliente,Nvl(f($row,'chave'),0),null,null,null,null,1,null,null,null,null,null,null,null);
          foreach($RSQuery1 as $row1){$RSQuery1=$row1; break;}
          $html.=chr(13).'      <tr><td colspan="2">';
          $html.=chr(13).'          <tr><td><b>Sexo:</b></td>'; 
          $html.=chr(13).'              <td>'.f($RSQuery1,'nm_sexo').'</td></tr>';
          $w_rg = '---';
          if (nvl(f($RSQuery1,'rg_numero'),'')!='') {
            $w_rg = f($RSQuery1,'rg_numero').'&nbsp;'.f($RSQuery1,'rg_emissor');
            if (nvl(f($RSQuery1,'rg_emissor'),'')!='') {
              $w_rg .= '&nbsp;de '.FormataDataEdicao(f($RSQuery1,'rg_emissao'));
            }
          } 
          $html.=chr(13).'          <tr><td><b>Identidade:</b></td>'; 
          $html.=chr(13).'              <td>'.$w_rg.'</td></tr>';
          $html.=chr(13).'      <tr><td colspan="2">';
          $w_telefone = '---';
          if (nvl(f($RSQuery1,'ddd'),'')!='') {
            $w_telefone = '('.f($RSQuery1,'ddd').') ';
          }
          if (nvl(f($RSQuery1,'nr_telefone'),'')!='') {
            $w_telefone .= '&nbsp;&nbsp;Fone: '.f($RSQuery1,'nr_telefone');
          }
          if (nvl(f($RSQuery1,'nr_fax'),'')!='') {
            $w_telefone .= '&nbsp;&nbsp;Fax: '.f($RSQuery1,'nr_fax');
          }
          if (nvl(f($RSQuery1,'nr_celular'),'')!='') {
            $w_telefone .= '&nbsp;&nbsp;Cel: '.f($RSQuery1,'nr_celular');
          }
          $html.=chr(13).'          <tr valign="top">';
          $html.=chr(13).'            <td><b>Telefones:</b></td><td>'.$w_telefone.'</td></tr>';
          // Recupera os dados bancários do pensionista
          $sql = new db_getContaBancoList; $RSConta = $sql->getInstanceOf($dbms,f($row,'chave'),null,null);
          if (count($RSConta)>0) {
            foreach($RSConta as $row2) { 
              if (f($row2,'padrao')=='S') {
                $html.=chr(13).'      <tr><td colspan="2"><hr NOSHADE color=#000000 size=1></td></tr>';
                $html.=chr(13).'          <tr><td><b>Banco:</b></td>';
                $html.=chr(13).'                <td>'.f($row2,'banco').'</td></tr>';
                $html.=chr(13).'          <tr><td><b>Agência:</b></td>';
                $html.=chr(13).'              <td>'.f($row2,'agencia').'</td></tr>';
                if (f($row2,'operacao')!='') $html.=chr(13).'          <tr><td><b>Operação:</b></td><td>'.Nvl(f($row2,'operacao'),'---').'</td>';
                $html.=chr(13).'          <tr><td><b>Número da conta:</b></td>';
                $html.=chr(13).'              <td>'.Nvl(f($row2,'numero'),'---').'</td></tr>';
              }
            }
          }
          $html.=chr(13).'          <tr><td><font size="1">&nbsp;</font></td>'; 
        }
      }
      
      $html.=chr(13).'      <tr><td  colspan="2"><br><font size="2"><b>CONTRATOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';

      //Contratos
      $html.=chr(13). "<script type='text/javascript'>";
      $html.=chr(13). "$(function(){";
      $html.=chr(13). "    $('#contratos_antigos').css('display','none');";
      $html.=chr(13). '      $(\'#colxpand\').html(\'<img src="images/expandir.gif">\');';      
      $html.=chr(13). "$('#contratos').click(function(event) {";
      $html.=chr(13). "    event.preventDefault();";
      $html.=chr(13). "    $('#contratos_antigos').slideToggle('slow');";
      $html.=chr(13). '    if($(\'#colxpand\').html() == \'<img src="images/expandir.gif">\'){';
      $html.=chr(13). '      $(\'#colxpand\').html(\'<img src="images/colapsar.gif">\');';
      $html.=chr(13). '    }else{';
      $html.=chr(13). '      $(\'#colxpand\').html(\'<img src="images/expandir.gif">\');';
      $html.=chr(13). '    }';
      $html.=chr(13). '  });';
      $html.=chr(13). '});';
      $html.=chr(13). '</script>';
      $i = 0;
      $j = 0;
      if (count($RSContrato)) {
	      foreach($RSContrato as $row){
	        if(Nvl(formataDataEdicao(f($row,'fim')),'')==''){
	          $html.=chr(13).'      </table><tr><td colspan="2"><table width="99%" border="0">';
	          $html.=chr(13).'      <tr><td colspan="2" bgColor="#f0f0f0"style="border: 1px solid rgb(0,0,0);" ><b>CONTRATO VIGENTE</b></td></tr>';
	          $j++;
	        } elseif ($i==0) {
	          $html.=chr(13).'      <tr><br><td id="contratos" colspan=2 bgColor="#f0f0f0"style="border: 1px solid rgb(0,0,0);" ><b><span id="colxpand"></span> CONTRATOS ANTIGOS</b></td></tr>';
	          $html.=chr(13).'      </table><tr><td colspan="2"><table id="contratos_antigos" width="99%" border="0">';        
	          $i++;
	        }                
	
	        //Recupera os dados do vínculo do colaborador
	        $sql = new db_getVincKindData; $RSVinculo = $sql->getInstanceOf($dbms, f($row,'sq_tipo_vinculo'));
	
	        //Recupera os dados da modalidade do contrato
	        $sql = new db_getGPModalidade; $RSModalidade = $sql->getInstanceOf($dbms,$w_cliente,f($row,'sq_modalidade_contrato'),null,null,'S',null,null);
	        foreach($RSModalidade as $row1){$RSModalidade = $row1; break;}
	        
          $html.=chr(13).'        <tr><td width="30%"><br><b>Matrícula:</b></td>';
          $html.=chr(13).'          <td>'.f($row,'matricula').' </td>';    
          $html.=chr(13).'        </tr>';         
          $html.=chr(13).'        <tr><td><b>Cargo:</b></td>';
          $html.=chr(13).'          <td>'.f($row,'nm_posto_trabalho').' </td>';    
          $html.=chr(13).'        </tr>';          
          $html.=chr(13).'        <tr><td><b>Centro de custo:</b></td>';
          $html.=chr(13).'          <td>'.f($row,'cd_cc').' - '.f($row,'nm_cc').' </td>';    
          $html.=chr(13).'        </tr>';
          $html.=chr(13).'        <tr><td><b>Unidade de lotação:</b></td>';
          $html.=chr(13).'          <td>'.f($row,'nm_unidade_lotacao').' </td>';    
          $html.=chr(13).'        </tr>';          
          $html.=chr(13).'        <tr><td><b>Unidade de exercício:</b></td>';
          $html.=chr(13).'          <td>'.f($row,'nm_unidade_exercicio').' </td>';    
          $html.=chr(13).'        </tr>';          
          $html.=chr(13).'        <tr><td><b>Localização:</b></td>';
          $html.=chr(13).'          <td>'.f($row,'local').' </td>';    
          $html.=chr(13).'        </tr>';    
          $html.=chr(13).'        <tr><td><b>Modalidade de contratação:</b></td>';
          $html.=chr(13).'          <td>'.f($RSModalidade,'nome').' </td>';    
          $html.=chr(13).'        </tr>';             
          $html.=chr(13).'        <tr><td><b>Tipo de vínculo:</b></td>';
          $html.=chr(13).'          <td>'.f($RSVinculo,'nome').'</td>';              
          $html.=chr(13).'        </tr>';          
          $html.=chr(13).'        <tr><td><b>Início da vigência:</b></td>';
          $html.=chr(13).'          <td>'.formataDataEdicao(f($row,'inicio')).' </td>';    
          $html.=chr(13).'        </tr>';
          If(Nvl(formataDataEdicao(f($row,'fim')),'')!=''){
            $html.=chr(13).'      <tr><td><b>Término da vigência:</b></td>';
            $html.=chr(13).'        <td>'.formataDataEdicao(f($row,'fim')).' </td>';    
            $html.=chr(13).'      </tr>';             
          }
          
          $html.=chr(13).'      <tr><td><b>Remuneração inicial:</b></td>';
          $html.=chr(13).'        <td>R$'.nvl(formatNumber(f($row,'remuneracao_inicial'),2),'0,00').' </td>';    
          $html.=chr(13).'        </tr>';
          $html.=chr(13).'      <tr><td><b>Horário de expediente:</b></td>';
          //ShowHTML('      <tr valign="top"><td>Carga horária:<td align="center">&nbsp;'.$w_carga_diaria.'&nbsp;');
          $html.=chr(13).'      <td>';
          $k = 0;
          If(nvl(f($row,'entrada_manha'),'')!=''){
            $html.=chr(13).'      '.f($row,'entrada_manha').' às '.f($row,'saida_manha');
            $k++;
          }
          If(nvl(f($row,'entrada_tarde'),'')!=''){
            $html.=($k>0?' - ':'');  
            $html.=chr(13).'      '.f($row,'entrada_tarde').' às '.f($row,'saida_tarde');
            
            $k++;
          }
          If(nvl(f($row,'entrada_noite'),'')!=''){
            $html.=($k>0?' - ':'');  
            $html.=chr(13).'      '.f($row,'entrada_noite').' às '.f($row,'saida_noite');
            $html.=($k>0?'-':'');
          }
                    
          //Alterações salariais
          $html.=chr(13).'<tr><td valign="top"><b>Alterações salariais</b>';
          $html.=chr(13).'    <td><table width=100%  border="1" bordercolor="#00000">';
          $html.=chr(13).'        <tr align="center">';
          $html.=chr(13).'          <td width="15%" bgColor="#f0f0f0"><div><b>Data</b></div></td>';
          $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Função</b></div></td>';
          $html.=chr(13).'          <td width="15%" bgColor="#f0f0f0"><div><b>Valor</b></div></td>';
          $html.=chr(13).'        </tr>';
                    
          $sql = new db_getGpAlteracaoSalario; $RSSalario = $sql->getInstanceOf($dbms, f($row,'chave'), null, null, null, null);
          if(count($RSSalario) > 0){
            foreach($RSSalario as $row){
              $html.=chr(13).'        <tr align="center">';
              $html.=chr(13).'        <td align="center" align="left">'.formataDataEdicao(f($row,'data_alteracao')).'</td>';
              $html.=chr(13).'        <td align="left" align="left">'.f($row,'funcao').'</td>';
              $html.=chr(13).'        <td align="center" align="left">'.formatNumber(f($row,'novo_valor'),2).'</td>';
              $html.=chr(13).'      </tr>';          
            }
          }else{
            $html.=chr(13).'      <tr><td colspan="3" align="center"><b>Não foram encontrados registros.</b></td></tr>';
          }
          $html.=chr(13).'    </table>';

          //Percentual de desempenho
          $html.=chr(13).'<tr valign="top"><td><b>Percentuais de desempenho</b>';
          $html.=chr(13).'    <td><table width=100%  border="1" bordercolor="#00000">';
          $html.=chr(13).'        <tr align="center">';
          $html.=chr(13).'          <td width="15%" bgColor="#f0f0f0"><div><b>Ano</b></div></td>';
          $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Percentual</b></div></td>';
          $html.=chr(13).'        </tr>';                    
          
          $sql = new db_getGpDesempenho; $RSDesempenho = $sql->getInstanceOf($dbms, f($row,'chave'),null);        
          if(count($RSDesempenho) > 0){
            foreach($RSDesempenho as $row){
              $html.=chr(13).'<tr>';
              $html.=chr(13).'        <td align="center" align="left">'.f($row,'ano').'</td>';
              $html.=chr(13).'        <td align="center" align="left">'.formatNumber(f($row,'percentual'),2).'</td>';
              $html.=chr(13).'      </tr>';
            }            
          }else{
            $html.=chr(13).'      <tr><td colspan="2" align="center"><b>Não foram encontrados registros.</b></td></tr>';
          } 
          $html.=chr(13).'    </table><br>';
          
          //Resumo da folha de ponto mensal
          $sql = new db_getGPFolhaPontoMensal; $RSMensal = $sql->getInstanceOf($dbms,f($row,'chave'),null,null);
          $RSMensal = SortArray($RSMensal,'mes','desc');
          if (count($RSMensal)) {
	          $html.=chr(13).'      </tr>';
	          $html.=chr(13).'  <tr><td colspan="2"><table width="100%" border="1">';
	          $html.=chr(13).'      <tr align="center" valign="top"><td colspan="5"><b>RESUMO DA FOLHA DE PONTO';
	          $html.=chr(13).'      <td colspan="6"><b>BANCO DE HORAS';
	          $html.=chr(13).'      <tr align="center" valign="top"><td bgcolor="#f0f0f0"><b>Período';
	          $html.=chr(13).'      <td bgcolor="#f0f0f0"><b>Horas trabalhadas';
	          $html.=chr(13).'      <td bgcolor="#f0f0f0"><b>Horas Extras';
	          $html.=chr(13).'      <td bgcolor="#f0f0f0"><b>Atrasos';
	          $html.=chr(13).'      <td bgcolor="#f0f0f0"><b>Banco de horas do mês';            
	          $html.=chr(13).'      <td bgcolor="#f0f0f0"><b>Saldo inicial';
	          $html.=chr(13).'      <td bgcolor="#f0f0f0"><b>Movimentações mensais';
	          $html.=chr(13).'      <td bgcolor="#f0f0f0"><b>Total';          
	          $html.=chr(13).'      <td bgcolor="#f0f0f0"><b>Horas autorizadas';
	          foreach($RSMensal as $row3) { 
	            $html.=chr(13).'      <tr align="center" valign="top">';
	            if ($p_formato=='HTML') {
                $html.=chr(13).'      <td nowrap><A class="HL" HREF="'.$w_dir.'folha.php?par=Visual&O=V&w_usuario='.f($row3,'sq_pessoa').'&w_chave='.f($row3,'sq_contrato_colaborador').'&w_mes='.substr(f($row3,'mes'),4,2).'/'.substr(f($row3,'mes'),0,4).'&R='.$w_pagina.$par.'&SG='.$SG.'&TP='.$TP.MontaFiltro('GET').'" target="visualFolha" title="Exibe detalhamento da folha de ponto">'.nomeMes(substr(f($row3,'mes'),4,2)).'/'.substr(f($row3,'mes'),0,4).'</a>&nbsp;';	              
	            }else{
	              $html.=chr(13).'      <td nowrap>'.nomeMes(substr(f($row3,'mes'),4,2)).'/'.substr(f($row3,'mes'),0,4);
	            }	            //
	            $html.=chr(13).'      <td>'.Nvl(f($row3,'horas_trabalhadas'),'00:00');
	            $html.=chr(13).'      <td>'.Nvl(f($row3,'horas_extras'),'00:00');
	            $html.=chr(13).'      <td>'.Nvl(f($row3,'horas_atrasos'),'00:00');
	            $html.=chr(13).'      <td>'.Nvl(f($row3,'horas_banco'),'00:00');
	            $html.=chr(13).'      <td>'.Nvl(f($row,'banco_horas_saldo'),'00:00');
	            $html.=chr(13).'      <td>'.Nvl(f($row,'banco_horas_mensal'),'00:00');
	            $html.=chr(13).'      <td>'.Nvl(f($row3,'horas_banco'),'00:00');
	            $html.=chr(13).'      <td>'.Nvl(f($row3,'horas_autorizadas'),'00:00');
	          }          
	          $html.=chr(13).'        </table><br><br><br>';
          }
	      }

        // Exibe as viagens do colaborador
	      $RSMenu_Viagem = new db_getLinkData; $RSMenu_Viagem = $RSMenu_Viagem->getInstanceOf($dbms,$w_cliente,'PDINICIAL');
        $sql = new db_getSolicList; $RS_Viagem = $sql->getInstanceOf($dbms,f($RSMenu_Viagem,'sq_menu'),$l_usuario,'PD',4,
            formataDataEdicao($w_inicio),formataDataEdicao($w_fim),null,null,null,null,null,null,null,null,null,
            null, null, null, null, null, null, null,null, null, null, null, null, null, null, $l_usuario);
        $RS_Viagem = SortArray($RS_Viagem,'inicio', 'desc', 'fim', 'desc');
        if (count($RS_Viagem)>0){
	        $html.=chr(13).'      </table><table width="99%"><tr><td colspan="2"><table width="100%" border="0">';
	        $html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>VIAGENS A SERVIÇO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';        
	        // Exibe as viagens a serviço do usuário logado
          $html.=chr(13).'                <table width="100%" bordercolor="#000000" border="1">';
          $html.=chr(13).'                  <tr align="center" valign="middle">';
          $html.=chr(13).'                    <td bgColor="#f0f0f0"><b>Início</td>';
          $html.=chr(13).'                    <td bgColor="#f0f0f0"><b>Término</td>';
          $html.=chr(13).'                    <td bgColor="#f0f0f0"><b>Nº</td>';
          $html.=chr(13).'                    <td bgColor="#f0f0f0"><b>Destinos</td>';
          reset($RS_Viagem);
          $w_cor = $w_cor=$conTrBgColor;
          foreach($RS_Viagem as $row) {
            $html.=chr(13).'                  <tr valign="top">';
            $html.=chr(13).'                    <td align="center">'.Nvl(date(d.'/'.m.', '.H.':'.i,f($row,'phpdt_saida')),'-').'</td>';
            $html.=chr(13).'                    <td align="center">'.Nvl(date(d.'/'.m.', '.H.':'.i,f($row,'phpdt_chegada')),'-').'</td>';
            $html.=chr(13).'                    <td nowrap>';
            $html.=chr(13).ExibeImagemSolic(f($row,'sigla'),f($row,'inicio'),f($row,'fim'),f($row,'inicio_real'),f($row,'fim_real'),f($row,'aviso_prox_conc'),f($row,'aviso'),f($row,'sg_tramite'), null);
            if ($p_formato=='HTML') {
              $html.=chr(13).'                      <A class="HL" HREF="'.substr(f($RSMenu_Viagem,'link'),0,strpos(f($RSMenu_Viagem,'link'),'=')).'=Visual&R='.$w_pagina.$par.'&O=L&w_chave='.f($row,'sq_siw_solicitacao').'&w_tipo=Volta&P1='.f($RSMenu_Viagem,'p1').'&P2='.f($RSMenu_Viagem,'p2').'&P3='.f($RSMenu_Viagem,'p3').'&P4='.f($RSMenu_Viagem,'p4').'&TP='.$TP.'&SG='.f($RSMenu_Viagem,'sigla').MontaFiltro('GET').'" title="Exibe as informações deste registro.">'.f($row,'codigo_interno').'&nbsp;</a>';
            }else{
              $html.=chr(13).'                      '.f($row,'codigo_interno');
            }
            $html.=chr(13).'                    <td nowrap>'.f($row,'trechos').'&nbsp;</td>';
            $html.=chr(13).'                  </tr>';
          }
          $html.=chr(13).'    </table>';
        }
	
        // Exibe afastamentos do usuário logado
	      $sql = new db_getAfastamento; $RS_Afast = $sql->getInstanceOf($dbms,$w_cliente,$l_usuario,null,null,null,formataDataEdicao($w_inicio),formataDataEdicao($w_fim),null,null,null,null);
        $RS_Afast = SortArray($RS_Afast,'inicio_data','desc','inicio_periodo','asc','fim_data','desc','inicio_periodo','asc');
        if (count($RS_Afast)>0) {
          // Mostra os períodos de indisponibilidade
          $html.=chr(13).'      </table><table width="99%"><tr><td colspan="2"><table width="99%" border="0">';
          $html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>AFASTAMENTOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
          $html.=chr(13).'                <table width="100%" bordercolor="#000000" border="1">';
          $html.=chr(13).'                  <tr align="center" valign="top">';
          $html.=chr(13).'                    <td bgColor="#f0f0f0"><b>Início';
          $html.=chr(13).'                    <td bgColor="#f0f0f0"><b>Término';
          $html.=chr(13).'                    <td bgColor="#f0f0f0"><b>Dias';
          $html.=chr(13).'                    <td bgColor="#f0f0f0"><b>Tipo';
          $html.=chr(13).'                  </tr>';
          reset($RS_Afast);
          $w_cor = $w_cor=$conTrBgColor;
          foreach($RS_Afast as $row) {
            $html.=chr(13).'                <tr bgcolor="#FFFFFF" valign="top">';
            $html.=chr(13).'                    <td align="center">'.date(d.'/'.m,f($row,'inicio_data')).' ('.f($row,'nm_inicio_periodo').')';
            $html.=chr(13).'                    <td align="center">'.date(d.'/'.m,f($row,'fim_data')).' ('.f($row,'nm_fim_periodo').')';
            $html.=chr(13).'                    <td align="center">'.crlf2br(f($row,'dias'));
            $html.=chr(13).'                    <td>'.f($row,'nm_tipo_afastamento');
          }
          $html.=chr(13).'                </table></tr>';
	      }
        $html.=chr(13).'        </table>';
      }
      
      // Contas bancárias do usuário
      $sql = new db_getContaBancoList; $RS = $sql->getInstanceOf($dbms,$l_usuario,null,null);
      $RS = SortArray($RS,'tipo_conta','asc','banco','asc','numero','asc');
      $html.=chr(13).'      <table width="99%"><tr><td colspan="2"><br><font size="2"><b>CONTAS BANCÁRIAS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $html.=chr(13).'<tr><td align="center" colspan=3>';
      $html.=chr(13).'    <table width=100%  border="1" bordercolor="#00000">';
      $html.=chr(13).'        <tr align="center">';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Tipo</b></div></td>';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Banco</b></div></td>';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Agência</b></div></td>';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Operação</b></div></td>';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Conta</b></div></td>';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Padrão</b></div></td>';
      $html.=chr(13).'        </tr>';
      if (count($RS)<=0) {
        // Se não foram selecionados registros, exibe mensagem
        $html.=chr(13).'      <tr><td colspan=7 align="center"><b>Não foram encontrados registros.</b></td></tr>';
      } else {
        // Lista os registros selecionados para listagem
        $w_cor=$conTrBgColor;
        foreach ($RS as $row) {
          if(f($row,'ativo')=='S'){
            $html.=chr(13).'      <tr valign="top">';
            $html.=chr(13).'        <td>'.f($row,'tipo_conta').'</td>';
            $html.=chr(13).'        <td>'.f($row,'banco').'</td>';
            $html.=chr(13).'        <td>'.f($row,'agencia').'</td>';
            $html.=chr(13).'        <td align="center">'.Nvl(f($row,'operacao'),'---').'</td>';
            $html.=chr(13).'        <td>'.f($row,'numero').'</td>';
            $html.=chr(13).'        <td align="center">'.retornaSimNao(f($row,'padrao')).'</td>';
            $html.=chr(13).'      </tr>';        
          }else{
            $html.=chr(13).'      <tr><td colspan=7 align="center"><b>Não foram encontrados registros.</b></td></tr>';
          }
        } 
      }  
      $html.=chr(13).'      </center>';
      $html.=chr(13).'    </table>';
      
      
      // Telefones
      $sql = new db_getFoneList; $RS = $sql->getInstanceOf($dbms,$l_usuario,null,null,null);
      $RS = SortArray($RS,'tipo_telefone','asc','numero','asc');
      $html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>TELEFONES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $html.=chr(13).'<tr><td align="center" colspan=3>';
      $html.=chr(13).'    <table width=100%  border="1" bordercolor="#00000">';
      $html.=chr(13).'        <tr align="center">';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Tipo</b></div></td>';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>DDD</b></div></td>';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Número</b></div></td>';
      $html.=chr(13).'        </tr>';
      if (count($RS)<=0) {
        // Se não foram selecionados registros, exibe mensagem
        $html.=chr(13).'      <tr><td colspan=6 align="center"><b>Não foram encontrados registros.</b></td></tr>';
      } else {
        // Lista os registros selecionados para listagem
        $w_cor=$conTrBgColor;
        foreach ($RS as $row) {
          $html.=chr(13).'      <tr valign="top">';
          $html.=chr(13).'        <td>'.f($row,'tipo_telefone').'</td>';
          $html.=chr(13).'        <td align="center">'.f($row,'ddd').'</td>';
          $html.=chr(13).'        <td>'.f($row,'numero').'</td>';
          $html.=chr(13).'      </tr>';
        } 
      } 
      $html.=chr(13).'      </center>';
      $html.=chr(13).'    </table>';
      $html.=chr(13).'  </td>';
      $html.=chr(13).'</tr>';
      //Endereços de e-mail e internet
      $sql = new db_getAddressList; $RS = $sql->getInstanceOf($dbms,$l_usuario,null,'EMAILINTERNET',null);
      $RS = SortArray($RS,'tipo_endereco','asc', 'endereco','asc');
      $html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ENDEREÇOS DE E-MAIL E INTERNET<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $html.=chr(13).'      <tr><td align="center" colspan="3">';
      $html.=chr(13).'    <table width=100%  border="1" bordercolor="#00000">';
      $html.=chr(13).'          <tr align="center">';
      $html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Endereço</b></div></td>';
      $html.=chr(13).'          </tr>';
      if (count($RS)<=0) {
        $html.=chr(13).'      <tr><td colspan=2 align="center"><b>Não foi informado nenhum endereço de e-Mail ou Internet.</b></td></tr>';
      } else {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;  
        foreach ($RS as $row) {
          $html.=chr(13).'      <tr valign="top">';
            if (f($row,'email')=='S') {
              if ($p_formato=='HTML') {
                $html.=chr(13).'        <td><a href="mailto:'.f($row,'logradouro').'">'.f($row,'logradouro').'</a></td>';  
              }else{
                $html.=chr(13).'        <td>'.f($row,'logradouro').'</td>';
              }              
            } else {
              $html.=chr(13).'        <td><a href="://'.str_replace('://','',f($row,'logradouro')).'" target="_blank">'.f($row,'logradouro').'</a></td>';
            } 
            $html.=chr(13).'      </tr>';
        } 
      } 
      $html.=chr(13).'         </table>';
      //Endereços físicos
      $html.=chr(13).'      <tr><td valign="top" colspan="3">&nbsp;</td>';
      $sql = new db_getAddressList; $RS = $sql->getInstanceOf($dbms,$l_usuario,null,'FISICO',null);
      $RS = SortArray($RS,'endereco','asc');
      $html.=chr(13).'      <tr><td colspan="2"><br><font size="2"><b>ENDEREÇOS FÍSICOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      if (count($RS)<=0) {
          $html.=chr(13).'  <tr bgcolor="'.$conTrBgColor.'"><td valign="top" colspan="2" align="center"><b>Não foi encontrado nenhum endereço.</b></td></tr>';
      } else {
        $html.=chr(13).'    <tr><td align="center" colspan="3"><TABLE WIDTH="100%" BORDER="0" CELLSPACING="0" CELLPADDING="0">';
        foreach($RS as $row) {
          $html.=chr(13).'  <tr><td colspan=5><b>'.f($row,'tipo_endereco').'</td>';
          $html.=chr(13).'    <tr><td width="5%">&nbsp<td colspan=3 width="25%"><b>Logradouro:</b></td>';
          $html.=chr(13).'      <td>'.f($row,'logradouro').'</td></tr>';
          $html.=chr(13).'    <tr><td><td colspan=3><b>Complemento:</b></td>';
          $html.=chr(13).'      <td>'.Nvl(f($row,'complemento'),'---').' </td></tr>';
          $html.=chr(13).'    <tr><td><td colspan=3><b>Bairro:</b></td>';
          $html.=chr(13).'      <td>'.f($row,'bairro').' </td></tr>';
          $html.=chr(13).'    <tr><td><td colspan=3><b>CEP:</b></td>';
          $html.=chr(13).'      <td>'.f($row,'cep').' </td></tr>';
          $html.=chr(13).'    <tr><td><td colspan=3><b>Cidade:</b></td>';
          $html.=chr(13).'      <td>'.f($row,'cidade').' </td></tr>';
          $html.=chr(13).'    <tr><td><td colspan=3><b>País:</b></td>';
          $html.=chr(13).'      <td>'.f($row,'nm_pais').' </td></tr>';
          $html.=chr(13).'    <tr><td><td colspan=3><b>Padrão?</b></td>';
          $html.=chr(13).'      <td>'.retornaSimNao(f($row,'padrao')).'</td></tr>';
          $html.=chr(13).'          <tr><td colspan="5"><hr>';
        } 
        $html.=chr(13).'          </table></td></tr>';
      } 
    } 
  } else {
    ScriptOpen('JavaScript');
    $html.=chr(13).' alert(\'Opção não disponível\');';
    $html.=chr(13).' history.back(1);';
    ScriptClose();
  }
  $html.=chr(13).'  <tr>';
  $html.=chr(13).'  <td>&nbsp;</td>';
  $html.=chr(13).'  <td>&nbsp;</td>';  
  $html.=chr(13).'</tr>';
  $html.=chr(13).'</table>'; 
  ShowHTML(''.$html);
} 
?>
