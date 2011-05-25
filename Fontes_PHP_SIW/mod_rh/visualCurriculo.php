<?php
// =========================================================================
// Rotina de visualização do currículo
// -------------------------------------------------------------------------
function visualCurriculo($p_cliente,$p_usuario,$O,$p_formato=0) {
  extract($GLOBALS);
  if ($O=='L') {
    // Se for listagem dos dados
    // Identificação pessoal
    $sql = new db_getCV; $RS = $sql->getInstanceOf($dbms,$p_cliente,$p_usuario,'CVIDENT','DADOS');
    foreach ($RS as $row) {$RS=$row; break;}
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
      $html.=chr(13).'      <tr valign="top"><td><b>Nome:</b></td>';
      $html.=chr(13).'        <td>'.f($RS,'nome').' </td>';
      if (nvl(f($RS,'sq_siw_arquivo'),'nulo')!='nulo') {
        if ($p_formato==0) {
          $html.=chr(13).'          <td rowspan=8>'.LinkArquivo('HL',$w_cliente,f($RS,'sq_siw_arquivo'),'_blank',null,'<img title="clique para ver em tamanho original." border=1 width=100 src="'.LinkArquivo(null,$w_cliente,f($RS,'sq_siw_arquivo'),null,null,null,'EMBED').'">',null).'</td>';
        } else {
          $html.=chr(13).'          <td rowspan=8><img border=1 width=100 height=133 src="'.$conFileVirtual.$p_cliente.'/'.f($RS,'ln_foto').'"></td>';
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
     //$html.=chr(13).'          </table>';
      // Histórico Pessoal
      $html.=chr(13).'      <tr><td colspan="3"><br><font size="2"><b>HISTÓRICO PESSOAL<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>';
      $html.=chr(13).'      <tr><td valign="top" colspan="3"><TABLE WIDTH="100%">';
      $html.=chr(13).'      <tr><td valign="top" width="80%">Você já fixou residência permanente legal em país estrangeiro?</td><td valign="top"><b>'.f($RS,'nm_residencia').' </b></td>';
      $html.=chr(13).'      <tr><td valign="top">Você já tomou alguma medida para mudar de nacionalidade?</td><td valign="top"><b>'.f($RS,'nm_mudanca');
      if (f($RS,'mudanca_nacionalidade')=='S') {
        $html.=chr(13).'                          , '.f($RS,'mudanca_nacionalidade_medida').'</b></td>';
      } 
      $html.=chr(13).'      <tr><td valign="top">Você aceitaria um emprego por menos de 6 meses?</td><td valign="top"><b>'.f($RS,'nm_emprego').' </b></td>';
      $html.=chr(13).'      <tr><td valign="top">Você possui algum impedimento para efetuar viagens aéreas?</td><td valign="top"><b>'.f($RS,'nm_impedimento').' </b></td>';
      $html.=chr(13).'      <tr><td valign="top">Você tem algum parente trabalhando nesta organização?</td><td valign="top"><b>'.f($RS,'nm_familiar').' </b></td>';
      $html.=chr(13).'      <tr><td valign="top">Você tem alguma objeção a fazer em relação à solicitação de informações a seu respeito para seu último empregador?</td><td valign="top"><b>'.f($RS,'nm_objecao').' </b></td>';
      $html.=chr(13).'      <tr><td valign="top">Você alguma vez já foi preso, acusado ou convocado pela Corte como réu em algum processo criminal ou sentenciado, penalizado ou aprisionado por violação de alguma lei? (excluem-se violações menores de trânsito)</td><td valign="top"><b>'.f($RS,'nm_prisao');
      if (f($RS,'prisao_envolv_justica')=='S') {
        $html.=chr(13).'                          , '.f($RS,'motivo_prisao').'</b></td>';
      } 
      $html.=chr(13).'      <tr><td valign="top">Exponha algum outo fato relevante. Inclua informações relacionadas a qualquer residência fora do país de origem:</td><td valign="top"><b>'.Nvl(f($RS,'fato_relevante_vida'),'---').' </b></td>';
      $html.=chr(13).'      <tr><td valign="top">Você é ou foi Funcionário Público?</td><td valign="top"><b>'.f($RS,'nm_servidor');
      if (f($RS,'servidor_publico')=='S') {
        $html.=chr(13).'                          , de '.FormataDataEdicao(f($RS,'servico_publico_inicio')).' a '.FormataDataEdicao(f($RS,'servico_publico_fim')).'</b></td>';
      } 
      $html.=chr(13).'      <tr><td valign="top">Sociedade profissional ou atividades ligadas a assuntos cívicos, públicos ou internacionais das quais faz parte:</td><td valign="top"><b>'.Nvl(f($RS,'atividades_civicas'),'---').' </b></td>';
      $html.=chr(13).'          </table>';
      // Telefones
      $sql = new db_getFoneList; $RS = $sql->getInstanceOf($dbms,$p_usuario,null,null,null);
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
      $sql = new db_getAddressList; $RS = $sql->getInstanceOf($dbms,$p_usuario,null,'EMAILINTERNET',null);
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
      $sql = new db_getAddressList; $RS = $sql->getInstanceOf($dbms,$p_usuario,null,'FISICO',null);
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
      // Escolaridade
      $sql = new db_getCVAcadForm; $RS = $sql->getInstanceOf($dbms,$p_usuario,null,'ACADEMICA');
      $RS = SortArray($RS,'ordem','desc', 'inicio','desc');
      $html.=chr(13).'      <tr><td colspan="3"><br><font size="2"><b>ESCOLARIDADE<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>'; 
      $html.=chr(13).'<tr><td align="center" colspan=3>';
      $html.=chr(13).'    <table width=100%  border="1" bordercolor="#00000">';
      $html.=chr(13).'        <tr align="center">';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Nível</b></div></td>';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Área</b></div></td>';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Instituição</b></div></td>';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Curso</b></div></td>';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Início</b></div></td>';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Término</b></div></td>';
      $html.=chr(13).'        </tr>';
      if (count($RS)<=0) {
        // Se não foram selecionados registros, exibe mensagem
        $html.=chr(13).'      <tr><td colspan=6 align="center"><b>Não foram encontrados registros.</b></td></tr>';
      } else {
        // Lista os registros selecionados para listagem
        $w_cor = $conTrBgColor;
        foreach($RS as $row) {
          $html.=chr(13).'      <tr valign="top">';
          $html.=chr(13).'        <td>'.f($row,'nm_formacao').'</td>';
          $html.=chr(13).'        <td>'.Nvl(f($row,'nm_area'),'---').'</td>';
          $html.=chr(13).'        <td>'.Nvl(f($row,'instituicao'),'---').'</td>';
          $html.=chr(13).'        <td>'.Nvl(f($row,'nome'),'---').'</td>';
          $html.=chr(13).'        <td align="center">'.f($row,'inicio').'</td>';
          $html.=chr(13).'        <td align="center">'.Nvl(f($row,'fim'),'---').'</td>';
          $html.=chr(13).'      </tr>';
        } 
      } 
      $html.=chr(13).'      </center>';
      $html.=chr(13).'    </table>';
      $html.=chr(13).'  </td>';
      $html.=chr(13).'</tr>';
      // Extensão acadêmica
      $sql = new db_getCVAcadForm; $RS = $sql->getInstanceOf($dbms,$p_usuario,null,'CURSO');
      $RS = SortArray($RS,'ordem','desc','carga_horaria','desc');
      $html.=chr(13).'      <tr><td valign="top" colspan="3">&nbsp;</td>';
      $html.=chr(13).'      <tr><td colspan="3"><br><font size="2"><b>EXTENSÃO ACADÊMICA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>'; 
      $html.=chr(13).'<tr><td align="center" colspan=3>';
      $html.=chr(13).'    <table width=100%  border="1" bordercolor="#00000">';
      $html.=chr(13).'        <tr align="center">';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Nível</b></div></td>';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Área</b></div></td>';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Instituição</b></div></td>';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Curso</b></div></td>';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>C.H.</b></div></td>';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Conclusão</b></div></td>';
      $html.=chr(13).'        </tr>';
      if (count($RS)<=0) {
        // Se não foram selecionados registros, exibe mensagem
        $html.=chr(13).'      <tr><td colspan=6 align="center"><b>Não foram encontrados registros.</b></td></tr>';
      } else {
        // Lista os registros selecionados para listagem
        $w_cor=$conTrBgColor;
        foreach ($RS as $row){
          $html.=chr(13).'      <tr valign="top">';
          $html.=chr(13).'        <td>'.f($row,'nm_formacao').'</td>';
          $html.=chr(13).'        <td>'.f($row,'nm_area').'</td>';
          $html.=chr(13).'        <td>'.f($row,'instituicao').'</td>';
          $html.=chr(13).'        <td>'.f($row,'nome').'</td>';
          $html.=chr(13).'        <td align="center">'.f($row,'carga_horaria').'</td>';
          $html.=chr(13).'        <td align="center">'.Nvl(FormataDataEdicao(f($row,'conclusao')),'---').'</td>';
          $html.=chr(13).'      </tr>';
        } 
      } 
      $html.=chr(13).'      </center>';
      $html.=chr(13).'    </table>';
      $html.=chr(13).'  </td>';
      $html.=chr(13).'</tr>';
      // Produção técnica
      $sql = new db_getCVAcadForm; $RS = $sql->getInstanceOf($dbms,$p_usuario,null,'PRODUCAO');
      $RS = SortArray($RS,'ordem','desc','data','desc');
      $html.=chr(13).'      <tr><td valign="top" colspan="3">&nbsp;</td>';
      $html.=chr(13).'      <tr><td colspan="3"><br><font size="2"><b>PRODUÇÃO TÉCNICA<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>'; 
      $html.=chr(13).'<tr><td align="center" colspan=3>';
      $html.=chr(13).'    <table width=100%  border="1" bordercolor="#00000">';
      $html.=chr(13).'        <tr align="center">';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Tipo</b></div></td>';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Área</b></div></td>';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Nome</b></div></td>';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Meio</b></div></td>';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Data</b></div></td>';
      $html.=chr(13).'        </tr>';
      if (count($RS)<=0) {
        // Se não foram selecionados registros, exibe mensagem
        $html.=chr(13).'      <tr><td colspan=6 align="center"><b>Não foram encontrados registros.</b></td></tr>';
      } else {
        // Lista os registros selecionados para listagem
        $w_cor=$conTrBgColor;
        foreach($RS as $row) {
          $html.=chr(13).'      <tr valign="top">';
          $html.=chr(13).'        <td>'.f($row,'nm_formacao').'</td>';
          $html.=chr(13).'        <td>'.f($row,'nm_area').'</td>';
          $html.=chr(13).'        <td>'.f($row,'nome').'</td>';
          $html.=chr(13).'        <td>'.f($row,'meio').'</td>';
          $html.=chr(13).'        <td align="center">'.f($row,'data').'</td>';
          $html.=chr(13).'      </tr>';
        } 
      } 
      $html.=chr(13).'      </center>';
      $html.=chr(13).'    </table>';
      $html.=chr(13).'  </td>';
      $html.=chr(13).'</tr>';
      // Idiomas
      $sql = new db_getCVIdioma; $RS = $sql->getInstanceOf($dbms,$p_usuario,null);
      $RS = SortArray($RS,'nome','acs');
      $html.=chr(13).'      <tr><td valign="top" colspan="3">&nbsp;</td>';
      $html.=chr(13).'      <tr><td colspan="3"><br><font size="2"><b>IDIOMAS<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>'; 
      $html.=chr(13).'<tr><td align="center" colspan=3>';
      $html.=chr(13).'    <table width=100%  border="1" bordercolor="#00000">';
      $html.=chr(13).'        <tr align="center">';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Idioma</b></div></td>';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Leitura</b></div></td>';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Escrita</b></div></td>';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Conversação</b></div></td>';
      $html.=chr(13).'          <td bgColor="#f0f0f0"><div><b>Compreensão</b></div></td>';
      $html.=chr(13).'        </tr>';
      if (count($RS)<=0) {
        // Se não foram selecionados registros, exibe mensagem
        $html.=chr(13).'      <tr><td colspan=6 align="center"><b>Não foram encontrados registros.</b></td></tr>';
      } else {
        // Lista os registros selecionados para listagem
        $w_cor = $conTrBgColor;
        foreach($RS as $row) {
          $html.=chr(13).'      <tr valign="top">';
          $html.=chr(13).'        <td>'.f($row,'nome').'</td>';
          $html.=chr(13).'        <td align="center">'.f($row,'nm_leitura').'</td>';
          $html.=chr(13).'        <td align="center">'.f($row,'nm_escrita').'</td>';
          $html.=chr(13).'        <td align="center">'.f($row,'nm_conversacao').'</td>';
          $html.=chr(13).'        <td align="center">'.f($row,'nm_compreensao').'</td>';
          $html.=chr(13).'      </tr>';
        } 
      } 
      $html.=chr(13).'      </center>';
      $html.=chr(13).'    </table>';
      $html.=chr(13).'  </td>';
      $html.=chr(13).'</tr>';
      // Contas bancárias
      $sql = new db_getContaBancoList; $RS = $sql->getInstanceOf($dbms,$p_usuario,null,null);
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
      // Experiencia profissional
      $html.=chr(13).'      <tr><td valign="top" colspan="3">&nbsp;</td>';
      $html.=chr(13).'      <tr><td colspan="3"><br><font size="2"><b>EXPERIÊNCIA PROFISSIONAL<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>'; 
      $sql = new db_getCVAcadForm; $RS = $sql->getInstanceOf($dbms,$p_usuario,null,'EXPERIENCIA');
      $RS = SortArray($RS,'entrada','desc');
      $html.=chr(13).'      <tr><td align="center" colspan="3">';
      $html.=chr(13).'        <TABLE WIDTH="99%" border="0">';
      if (count($RS)<=0) {
        $html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'"><td colspan="3" align="center"><b>Não foi informada nenhuma experiência profissional.</b></td></tr>';
      } else {
        foreach ($RS as $row) {
          $html.=chr(13).'          <tr> ';
          $html.=chr(13).'          <tr><td width="30%"><b>Empregador:</b></td>';
          $html.=chr(13).'            <td>'.f($row,'empregador').'</td></tr>';
          $html.=chr(13).'              <td valign="top"><b>Área de conhecimento:</b></td>';
          $html.=chr(13).'            <td>'.f($row,'nm_area').'</td></tr>';
          $html.=chr(13).'          <tr><td valign="top"><b>Entrada: </b></td>';
          $html.=chr(13).'            <td>'.FormataDataEdicao(f($row,'entrada')).'</td></tr>';
          $html.=chr(13).'              <td valign="top"><b>Saida: </b></td>';
          $html.=chr(13).'            <td>'.Nvl(FormataDataEdicao(f($row,'saida')),'---').'</td></tr>';
          $html.=chr(13).'              <td valign="top"><b>Último salário mensal: </b></td>';
          $html.=chr(13).'            <td>'.number_format(Nvl(f($row,'ultimo_salario'),0),2,',','.').'</td></tr>';
          $html.=chr(13).'          <tr><td valign="top"><b>Motivo saída: </b></td>';
          $html.=chr(13).'            <td>'.Nvl(f($row,'motivo_saida'),'---').'</td></tr>';
          $html.=chr(13).'          <tr><td valign="top"><b>País: </b></td>';
          $html.=chr(13).'            <td>'.f($row,'nm_pais').'</td></tr>';
          $html.=chr(13).'              <td valign="top"><b>Estado: </b></td>';
          $html.=chr(13).'            <td>'.f($row,'nm_estado').'</td></tr>';
          $html.=chr(13).'              <td valign="top"><b>Cidade: </b></td>';
          $html.=chr(13).'            <td>'.f($row,'nm_cidade').'</b></td></tr>';
          $html.=chr(13).'          <tr> ';
          $html.=chr(13).'          <tr><td valign="top" colspan=3><b>Principal atividade desempenhada: <br></b>'.f($row,'ds_tipo_posto').'</td></tr>';
          $html.=chr(13).'          <tr> ';
          $html.=chr(13).'          <tr><td valign="top" colspan=3><b>Atividades desempenhadas: <br></b>'.f($row,'atividades').'</td></tr>';
          // Cargos da experiência profissional
          $sql = new db_getCVAcadForm; $RS = $sql->getInstanceOf($dbms,$RS1,f($RS,'sq_cvpesexp'),null,'CARGO');
          if (!count($RS)<=0){
            $html.=chr(13).'      <tr><td valign="top">Cargos:<br></td></tr>';
            $html.=chr(13).'      <tr><td align="center" colspan="3">';
            $html.=chr(13).'        <TABLE class="tudo" WIDTH="100%" bgcolor="'.$conTableBgColor.'" BORDER="'.$conTableBorder.'" CELLSPACING="'.$conTableCellSpacing.'" CELLPADDING="'.$conTableCellPadding.'" BorderColorDark="'.$conTableBorderColorDark.'" BorderColorLight="'.$conTableBorderColorLight.'">';
            $html.=chr(13).'          <tr bgcolor="'.$conTrBgColor.'" align="center">';
            $html.=chr(13).'            <td><b>Área</td>';
            $html.=chr(13).'            <td><b>Especialidades</td>';
            $html.=chr(13).'            <td><b>Início</td>';
            $html.=chr(13).'            <td><b>Fim</td>';
            $html.=chr(13).'          </tr>';
            foreach ($RS1 as $row) {
              $html.=chr(13).'      <tr bgcolor="'.$conTrBgColor.'" valign="top">';
              $html.=chr(13).'        <td>'.f($RS1,'nm_area').'</td>';
              $html.=chr(13).'        <td>'.f($RS1,'especialidades').'</td>';
              $html.=chr(13).'        <td align="center">'.FormataDataEdicao(f($RS1,'inicio')).'</td>';
              $html.=chr(13).'        <td align="center">'.Nvl(FormataDataEdicao(f($RS1,'fim')),'---').'</td>';
              $html.=chr(13).'      </tr>';
            } 
            $html.=chr(13).'         </table></td></tr>';
          } 
          $html.=chr(13).'          <tr><td colspan="3"><hr>';
        } 
      } 
      $html.=chr(13).'         </table></td></tr>';
      // Se for formato Word, exibe declaração
      if ($p_formato!=0) {
        $html.=chr(13).'      <tr><td valign="top" colspan="3">&nbsp;</td>';
        $html.=chr(13).'      <tr><td colspan="3"><br><font size="2"><b>DECLARAÇÃO<hr NOSHADE color=#000000 SIZE=1></b></font></td></tr>'; 
        $html.=chr(13).'      <tr><td valign="top" colspan="3"><font size="3"><blockquote><p align="justify"><br>Eu, <b>'.$w_nome.'</b>, declaro que as informações aqui constantes estão atualizadas, são verdadeiras e passíveis de comprovação.</p><p><br></p><p align="center">'.substr(DataHora(),0,-10).'</p></blockquote></td>';
      }
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
