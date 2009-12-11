<?php
include_once($w_dir_volta.'classes/sp/db_getFoneList.php');
include_once($w_dir_volta.'classes/sp/db_getAddressList.php');
include_once($w_dir_volta.'classes/sp/db_getContaBancoList.php');

// =========================================================================
// Rotina de visualização do currículo
// -------------------------------------------------------------------------
function visualFicha($p_cliente,$p_usuario,$O,$p_formato=0) {
  extract($GLOBALS);
  if (1==1) {
    // Se for listagem dos dados
    // Identificação pessoal
    $RS = db_getCV::getInstanceOf($dbms,$p_cliente,$p_usuario,'CVIDENT','DADOS');
    foreach ($RS as $row) {$RS=$row; break;}
        
    // Recupera os dados do colaborador a partir do código da pessoa
    $RSDocumentacao = db_getGPColaborador::getInstanceOf($dbms,$p_cliente,$p_usuario,null,null,null,null,null,null,null,null,null,null,null,null,null,null);
    foreach ($RSDocumentacao as $row) {$RSDocumentacao = $row; break;}   
    
    //Recupera os dados dos contratos do colaborador
    $RSContrato = db_getGPContrato::getInstanceOf($dbms,$w_cliente,null,$w_usuario,null,null,null,null,null,null,null,null,null,null);

    if (Nvl(f($RS,'inclusao'),'')=='') {
      $html = '<div align=center><center><br><br><br><br><br><br><br><br><br><br><img src="images/icone/underc.gif" align="center"> <b>Curriculum não informado.</b><br><br><br><br><br><br><br><br><br><br></center></div>';
    } else {
      $w_nome   = f($RS,'nome');
      $html ='<div align=center><center>';
      $html.=chr(13).'<table border="0" cellpadding="0" cellspacing="0" width="100%">';
      $html.=chr(13).'<tr><td align="center">';
      $html.=chr(13).'    <table width="99%" border="0">';
      $html.=chr(13).'      <tr><td align="center" colspan="3"><font size=4><b>'.f($RS,'nome').'</b></font></td></tr>';
      $html.=chr(13).'      <table width="99%" border="0">';
      $html.=chr(13).'      <tr><td colspan="3"><br><font size="2"><b>IDENTIFICACÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>'; 
      $html.=chr(13).'      <tr><td><b>Nome:</b></td>';
      $html.=chr(13).'        <td>'.f($RS,'nome').' </td>';
      if (nvl(f($RS,'sq_siw_arquivo'),'nulo')!='nulo') {
        if ($p_formato=='HTML') {
          $html.=chr(13).'          <td rowspan=8>'.LinkArquivo('HL',$w_cliente,f($RS,'sq_siw_arquivo'),'_blank',null,'<img title="clique para ver em tamanho original." border=1 width=100 length=80 src="'.LinkArquivo(null,$w_cliente,f($RS,'sq_siw_arquivo'),null,null,null,'EMBED').'">',null).'</td>';
        } else {
          $html.=chr(13).'          <td rowspan=8><img border=1 width=100 length=80 src="'.$conFileVirtual.$p_cliente.'/'.f($RS,'sq_siw_arquivo').'"></td>';
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
      $html.=chr(13).'      <tr><td colspan="3"><br><font size="2"><b>LOCAL DE NASCIMENTO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $html.=chr(13).'      <tr valign="top">';
      $html.=chr(13).'          <td><b>País:</b></td>';
      $html.=chr(13).'        <td>'.f($RS,'nm_pais_nascimento').' </td></tr>';
      $html.=chr(13).'          <td><b>Estado:</b></td>';
      $html.=chr(13).'        <td>'.f($RS,'nm_uf_nascimento').' </td></tr>';
      $html.=chr(13).'          <td><b>Cidade:</b></td>';
      $html.=chr(13).'        <td>'.f($RS,'nm_cidade_nascimento').' </td></tr>';
      $html.=chr(13).'      <tr><td colspan="3"><br><font size="2"><b>DOCUMENTAÇÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
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

      //Contratos
      foreach($RSContrato as $row){
        $RS = db_getGPContrato::getInstanceOf($dbms,$w_cliente,f($row,'chave'),$w_usuario,null,null,null,null,null,null,null,null,null,null);
        $html.=chr(13).'      <tr><td valign="top" colspan="3">&nbsp;</td>';
        $html.=chr(13).'      <tr><td colspan="3"><br><font size="2"><b>CONTRATOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
        print_r($row);
        foreach($RS as $row){
          $html.=chr(13).'      <tr><td><b>Matrícula:</b></td>';
          $html.=chr(13).'        <td>'.f($row,'matricula').' </td>';    
          $html.=chr(13).'        </tr>';         
          $html.=chr(13).'      <tr><td><b>Centro de custo:</b></td>';
          $html.=chr(13).'        <td>'.f($row,'cd_cc').' - '.f($row,'nm_cc').' </td>';    
          $html.=chr(13).'        </tr>';
          $html.=chr(13).'      <tr><td><b>Unidade de lotação:</b></td>';
          $html.=chr(13).'        <td>'.f($row,'nm_unidade_lotacao').' </td>';    
          $html.=chr(13).'        </tr>';          
          $html.=chr(13).'      <tr><td><b>Unidade de exercício:</b></td>';
          $html.=chr(13).'        <td>'.f($row,'nm_unidade_exercicio').' </td>';    
          $html.=chr(13).'        </tr>';          
          $html.=chr(13).'      <tr><td><b>Localização:</b></td>';
          $html.=chr(13).'        <td>'.f($row,'local').' </td>';    
          $html.=chr(13).'        </tr>';    
          $html.=chr(13).'      <tr><td><b>Modalidade de contratação:</b></td>';
          $html.=chr(13).'        <td>'.f($row,'nm_unidade_lotacao').' </td>';    
          $html.=chr(13).'        </tr>';             
          $html.=chr(13).'      <tr><td><b>Tipo de vínculo:</b></td>';
          $html.=chr(13).'        <td>'.f($row,'nm_unidade_lotacao').' </td>';    
          $html.=chr(13).'        </tr>';          
          $html.=chr(13).'      <tr><td><b>Início da vigência::</b></td>';
          $html.=chr(13).'        <td>'.formataDataEdicao(f($row,'inicio')).' </td>';    
          $html.=chr(13).'        </tr>';
          //Percentual de desempenho
          $html.=chr(13).'<tr><td align="center" colspan=3>';
          $html.=chr(13).'<h3 align="left"><br>Percentual de desempenho</h3>';
          $RSDesempenho = db_getGpDesempenho::getInstanceOf($dbms, f($row,'chave'),null);
          $html.=chr(13).'<tr><td align="center" colspan=3>';          
          if(count($RSDesempenho) > 0){
            $html.=chr(13).'    <table width=100%  border="1" bordercolor="#00000">';
            $html.=chr(13).'        <tr align="center">';
            $html.=chr(13).'          <td width="15%" bgColor="#f0f0f0"><div><b>Ano</b></div></td>';
            $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Percentual de Desempenho</b></div></td>';
            $html.=chr(13).'        </tr>';          
          } 
          foreach($RSDesempenho as $row){
          //print_r($RS1);
            $html.=chr(13).'<tr>';
            $html.=chr(13).'        <td align="center" align="left">'.f($row,'ano').'</td>';
            $html.=chr(13).'        <td align="center" align="left">'.formatNumber(f($row,'percentual'),2).'</td>';
            $html.=chr(13).'      </tr>';
          } 
          $html.=chr(13).'    </table>';
          
          //Alterações salariais
          $html.=chr(13).'<h3 align="left">Alteração salarial</h3>';
          $RSSalario = db_getGpAlteracaoSalario::getInstanceOf($dbms, f($row,'chave'), null, null, null, null);
          $html.=chr(13).'<tr><td align="center" colspan=3>';
          if(count($RSSalario) > 0){
            $html.=chr(13).'    <table width=100%  border="1" bordercolor="#00000">';
            $html.=chr(13).'        <tr align="center">';
            $html.=chr(13).'          <td width="15%" bgColor="#f0f0f0"><div><b>Data de alteração</b></div></td>';
            $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Função</b></div></td>';
            $html.=chr(13).'          <td width="15%" bgColor="#f0f0f0"><div><b>Valor da remuneração</b></div></td>';
            $html.=chr(13).'        </tr>';          
          }
          foreach($RSSalario as $row){
            $html.=chr(13).'        <tr align="center">';
            $html.=chr(13).'        <td align="center" align="left">'.formataDataEdicao(f($row,'data_alteracao')).'</td>';
            $html.=chr(13).'        <td align="left" align="left">'.f($row,'funcao').'</td>';
            $html.=chr(13).'        <td align="center" align="left">'.formatNumber(f($row,'novo_valor'),2).'</td>';
            $html.=chr(13).'      </tr>';          
          }
          $html.=chr(13).'    </table>';             
        }        
      }
      
      
      
      // Telefones
      $RS = db_getFoneList::getInstanceOf($dbms,$p_usuario,null,null,null);
      $RS = SortArray($RS,'tipo_telefone','asc','numero','asc');
      $html.=chr(13).'      <tr><td valign="top" colspan="3">&nbsp;</td>';
      $html.=chr(13).'      <tr><td colspan="3"><br><font size="2"><b>TELEFONES<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $html.=chr(13).'<tr><td align="center" colspan=3>';
      $html.=chr(13).'    <table width=100%  border="1" bordercolor="#00000">';
      $html.=chr(13).'        <tr align="center">';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Tipo</b></div></td>';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>DDD</b></div></td>';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Número</b></div></td>';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Padrão</b></div></td>';
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
          $html.=chr(13).'        <td align="center">'.retornaSimNao(f($row,'padrao')).'</td>';
          $html.=chr(13).'      </tr>';
        } 
      } 
      $html.=chr(13).'      </center>';
      $html.=chr(13).'    </table>';
      $html.=chr(13).'  </td>';
      $html.=chr(13).'</tr>';
      //Endereços de e-mail e internet
      $RS = db_getAddressList::getInstanceOf($dbms,$p_usuario,null,'EMAILINTERNET',null);
      $RS = SortArray($RS,'tipo_endereco','asc', 'endereco','asc');
      $html.=chr(13).'      <tr><td valign="top" colspan="3">&nbsp;</td>';
      $html.=chr(13).'      <tr><td colspan="3"><br><font size="2"><b>ENDEREÇO DE E-MAIL E INTERNET<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>'; 
      $html.=chr(13).'      <tr><td align="center" colspan="3">';
      $html.=chr(13).'    <table width=100%  border="1" bordercolor="#00000">';
      $html.=chr(13).'          <tr align="center">';
      $html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Endereço</b></div></td>';
      $html.=chr(13).'            <td bgColor="#f0f0f0"><div><b>Padrão</b></div></td>';
      $html.=chr(13).'          </tr>';
      if (count($RS)<=0) {
        $html.=chr(13).'      <tr><td colspan=2 align="center"><b>Não foi informado nenhum endereço de e-Mail ou Internet.</b></td></tr>';
      } else {
        $w_cor = ($w_cor==$conTrBgColor || $w_cor=='') ? $w_cor=$conTrAlternateBgColor : $w_cor=$conTrBgColor;  
        foreach ($RS as $row) {
          $html.=chr(13).'      <tr valign="top">';
            if (f($row,'email')=='S') {
              $html.=chr(13).'        <td><a href="mailto:'.f($row,'logradouro').'">'.f($row,'logradouro').'</a></td>';
            } else {
              $html.=chr(13).'        <td><a href="://'.str_replace('://','',f($row,'logradouro')).'" target="_blank">'.f($row,'logradouro').'</a></td>';
            } 
            $html.=chr(13).'        <td align="center">'.retornaSimNao(f($row,'padrao')).'</td>';
            $html.=chr(13).'      </tr>';
        } 
      } 
      $html.=chr(13).'         </table></td></tr>';
      //Endereços físicos
      $html.=chr(13).'      <tr><td valign="top" colspan="3">&nbsp;</td>';
      $RS = db_getAddressList::getInstanceOf($dbms,$p_usuario,null,'FISICO',null);
      $RS = SortArray($RS,'endereco','asc');
      $html.=chr(13).'      <tr><td colspan="3"><br><font size="2"><b>ENDEREÇO FÍSICOS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>'; 
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
      // Contas bancárias
      $RS = db_getContaBancoList::getInstanceOf($dbms,$p_usuario,null,null);
      $RS = SortArray($RS,'tipo_conta','asc','banco','asc','numero','asc');
      $html.=chr(13).'      <tr><td valign="top" colspan="3">&nbsp;</td>';
      $html.=chr(13).'      <tr><td colspan="3"><br><font size="2"><b>CONTAS BANCÁRIAS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>'; 
      $html.=chr(13).'<tr><td align="center" colspan=3>';
      $html.=chr(13).'    <table width=100%  border="1" bordercolor="#00000">';
      $html.=chr(13).'        <tr align="center">';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Tipo</b></div></td>';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Banco</b></div></td>';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Agência</b></div></td>';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Operação</b></div></td>';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Conta</b></div></td>';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Ativo</b></div></td>';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Padrão</b></div></td>';
      $html.=chr(13).'        </tr>';
      if (count($RS)<=0) {
        // Se não foram selecionados registros, exibe mensagem
        $html.=chr(13).'      <tr><td colspan=7 align="center"><b>Não foram encontrados registros.</b></td></tr>';
      } else {
        // Lista os registros selecionados para listagem
        $w_cor=$conTrBgColor;
        foreach ($RS as $row) {
          $html.=chr(13).'      <tr valign="top">';
          $html.=chr(13).'        <td>'.f($row,'tipo_conta').'</td>';
          $html.=chr(13).'        <td>'.f($row,'banco').'</td>';
          $html.=chr(13).'        <td>'.f($row,'agencia').'</td>';
          $html.=chr(13).'        <td align="center">'.Nvl(f($row,'operacao'),'---').'</td>';
          $html.=chr(13).'        <td>'.f($row,'numero').'</td>';
          $html.=chr(13).'        <td align="center">'.f($row,'ativo').'</td>';
          $html.=chr(13).'        <td align="center">'.retornaSimNao(f($row,'padrao')).'</td>';
          $html.=chr(13).'      </tr>';
        } 
      }  
      $html.=chr(13).'      </center>';
      $html.=chr(13).'    </table>';
      $html.=chr(13).'  </td>';
      $html.=chr(13).'</tr>';
      $html.=chr(13).'</table>';
    } 
  } else {
    ScriptOpen('JavaScript');
    $html.=chr(13).' alert(\'Opção não disponível\');';
    $html.=chr(13).' history.back(1);';
    ScriptClose();
  } 
  ShowHTML(''.$html);
} 
?>
