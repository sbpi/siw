<?php 
include_once('jscript.php');
?>
<HTML>
<HEAD>
  <TITLE>SIW-GP - Roteiro de Integra��o com MatricialNet</TITLE>
<?php
ScriptOpen('Javascript');
CheckBranco();
FormataData();
FormataValor();
SaltaCampo();
ValidateOpen('Validacao');
Validate('codigo','C�digo do programa','','1','1','20','1','');
Validate('titulo','T�tulo do programa','','1','5','100','1','');
Validate('inicio','In�cio do programa','DATA','1','10','10','','0123456789/');
Validate('termino','T�rmino do programa','DATA','1','10','10','','0123456789/');
CompData('inicio','In�cio do programa','<','termino','T�rmino do programa');
Validate('valor','Valor or�ado para o programa','VALOR','1',4,18,'','0123456789.,');
ValidateClose();
ScriptClose();
ShowHTML('<script type="text/javascript" src="modal/js/ajax.js"></script>');
ShowHTML('<script type="text/javascript" src="modal/js/ajax-dynamic-content.js"></script> ');
ShowHTML('<script type="text/javascript" src="modal/js/modal-message.js"></script> ');
ShowHTML('<link rel="stylesheet" href="modal/css/modal-message.css" type="text/css" media="screen" />');
ShowHTML('<script language="javascript" type="text/javascript" src="funcoes.js"></script>');
ShowHTML('<script language="javascript" type="text/javascript" src="jquery.js"></script>');
ShowHTML('<link rel="stylesheet" type="text/css" href="xPandMenu.css">');
?>
</HEAD>
<BODY>
  <H2>Roteiro de Integra��o SIW-GP x MatricialNet</H2>
  <H3>Manipula cadastro de programas</H3>
  <UL>
    <LI><a href="default.htm">Volta ao menu principal</a></LI>
    <LI><a href="indicador.php">Recupera quantitativos dos programas</a></LI>
    <LI><a href="visual.php">Abre SIW-GP em outra janela, exibindo relat�rio executivo dos projetos de um programa</a></LI>
  </UL>
  <p>A manipula��o de programas no SIW-GP deve ser feita atrav�s de uma chamada Internet, usando o m�todo POST, passando os dados informados abaixo. O nome dos campos deve ser totalmente informado em letras min�sculas, tendo em vista o PHP ser uma linguagem case-sensitive. Se o programa existir, seu nome ser� alterado; caso contr�rio, ser� inclu�do.</p>
  <form name="Form" action="cadastro.php" method="POST" onSubmit="return(Validacao(this));">
    <input type="hidden" name="cli" value="16316"></input>
    <input type="hidden" name="uid" value="MATRICIALNET"></input>
    <input type="hidden" name="pwd" value="MATRICIAL1"></input>
    <table width="100%" border=1>
      <tr valign="top"><th>Campo</th><th>Valor</th><th>Observa��es</th></tr>
      <tr valign="top"><td>cli</td><td>16316</td><td>OBRIGAT�RIO. Indica o c�digo da vers�o do SIW-GP para a TERRACAP.</td></tr>
      <tr valign="top"><td>uid</td><td>MATRICIALNET</td><td>OBRIGAT�RIO. Indica o usu�rio que est� fazendo a conex�o. Deve ser sempre esse valor, codificado em BASE64.</td></tr>
      <tr valign="top"><td>pwd</td><td>MATRICIAL1</td><td>OBRIGAT�RIO. Senha do usu�rio que est� fazendo a conex�o. Deve ser sempre esse valor, codificado em BASE64, a menos que seja alterada no SIW-GP.</td></tr>
      <tr valign="top"><td>codigo</td><td><input type="TEXT" class="STI" name="codigo" value="<?php echo $_POST["codigo"]?>" size="20" maxlength="20"></input></td><td>OBRIGAT�RIO. C�digo do programa a ser inclu�do ou alterado. O c�digo em si n�o pode ser alterado; apenas seus atributos. Campo alfanum�rico de at� 20 posi��es.</td></tr>
      <tr valign="top"><td>titulo</td><td><input type="TEXT" class="STI" name="titulo" value="<?php echo $_POST["titulo"]?>" size="50" maxlength="100"></input></td><td>OBRIGAT�RIO. T�tulo do programa a ser inclu�do ou alterado. Campo alfanum�rico de at� 100 posi��es.</td></tr>
      <tr valign="top"><td>inicio</td><td><input type="TEXT" class="STI" name="inicio" value="<?php echo $_POST["inicio"]?>" size="10" maxlength="10" onKeyDown="FormataData(this,event);"></input></td><td>OBRIGAT�RIO. In�cio previsto para a execu��o do programa, no formato DD/MM/AAAA.</td></tr>
      <tr valign="top"><td>termino</td><td><input type="TEXT" class="STI" name="termino" value="<?php echo $_POST["termino"]?>" size="10" maxlength="10" onKeyDown="FormataData(this,event);"></input></td><td>OBRIGAT�RIO. T�rmino previsto para a execu��o do programa, no formato DD/MM/AAAA.</td></tr>
      <tr valign="top"><td>valor</td><td><input type="TEXT" class="STI" name="valor" value="<?php echo $_POST["valor"]?>" size="20" maxlength="20" style="text-align:right;" onKeyDown="FormataValor(this,18,2,event);"></input></td><td>OBRIGAT�RIO. Valor or�ado para o programa, no formato brasileiro de valor monet�rio (000.000.000.009,99).</td></tr>
      <tr valign="top"><td>situacao</td><td><input type="RADIO" name="situacao" value="N" CHECKED>Normal</input><br><input type="RADIO" name="situacao" value="E">Encerrado<br><input type="RADIO" name="situacao" value="C">Cancelado/Exclu�do</input></br></td><td>OBRIGAT�RIO. Situa��o do programa. Deve ser informado o valor "N" para programas em andamento, "E" para programas encerrados e "C" para programas cancelados/exclu�dos, que n�o devem mais permitir vincula��o de projetos no SIW-GP.</td></tr>
      <tr valign="top"><td colspan="3"><input type="SUBMIT" class="STB" name="botao" value="PROCESSAR"></input></td></tr>
    </table> 
  </form>
  <?php
  if (isset($_POST['uid'])) {
    ?>
    <p>C�digo PHP gerado para a chamada ao SIW-GP: (o endere�o base para o ambiente de desenvolvimento � <b>http://www2.sbpi.com.br/siw/cl_terracap</b>. Esse endere�o deve estar configurado em alguma constante para poder ser facilmente alterado para o ambiente de produ��o.)</p>
    <table border=0 bgcolor="F0F0F0"><TR><TD>
    <PRE><font face="courier" size="2">&lt;?php
    // Cria��o de um array PHP com os pares vari�vel-valor do formul�rio. Os nomes das vari�veis devem ser exatamente iguais aos indicados.
    <b>$campos = array('cli' => '<?php echo $_POST["cli"]?>',
	            'uid' => '<?php echo base64_encode($_POST["uid"])?>',
	            'pwd' => '<?php echo base64_encode($_POST["pwd"])?>',
	            'codigo' => '<?php echo $_POST["codigo"]?>',
	            'titulo' => '<?php echo $_POST["titulo"]?>',
	            'inicio' => '<?php echo $_POST["inicio"]?>',
	            'termino' => '<?php echo $_POST["termino"]?>',
	            'valor' => '<?php echo $_POST["valor"]?>',
	            'situacao' => '<?php echo $_POST["situacao"]?>'
	           );</b>
    
    // Chamada PHP para uma URL passando vari�veis com o m�todo POST. Em <b>$response</b> ser� armazenado o resultado do processamento.
    <b>$response = http_post_fields(&lt;endere�o-base>/cadastro.php, $campos);</font></b></PRE>
    ?></font></TD></TR></table>
    <p>A rotina de cadastro retornar� para <b>$response</b> um dos valores abaixo:</p>
    <dl>
      <dt>200<dd>A inclus�o de um programa com situa��o normal foi executada com sucesso. Neste caso, apenas uma linha � retornada.</dd></dt>
      <dt>201<dd>A inclus�o de um programa cancelado foi executada com sucesso. Neste caso, apenas uma linha � retornada.</dd></dt>
      <dt>202<dd>A altera��o de um programa com situa��o normal foi executada com sucesso. Neste caso, apenas uma linha � retornada.</dd></dt>
      <dt>203<dd>A altera��o de um programa cancelado foi executada com sucesso. Neste caso, apenas uma linha � retornada.</dd></dt>
      <dt>500<dd>Autentica��o inv�lida. Verificar se os valores das vari�veis <b>uid</b> e <b>pwd</b> est�o corretamente configurados. Neste caso, apenas uma linha � retornada.</dd></dt>
      <dt>501<dd>Erro de preenchimento. Neste caso, a primeira linha ter� o valor 501 e as demais indicar�o o(s) erro(s). Os erros podem ser causados pelo n�o preenchimento de algum campo ou pelo preenchimento incorreto de um ou mais deles.</dd></dt>
    </dl>
    <?php
    // Cria��o de um array PHP com os pares vari�vel-valor do formul�rio. Os nomes das vari�veis devem ser exatamente iguais aos indicados.
    $campos = array('cli' => $_POST["cli"],
              'uid' => base64_encode($_POST["uid"]),
              'pwd' => base64_encode($_POST["pwd"]),
              'codigo' => $_POST["codigo"],
              'titulo' => $_POST["titulo"],
              'inicio' => $_POST["inicio"],
              'termino' => $_POST["termino"],
              'valor' => $_POST["valor"],
              'situacao' => $_POST["situacao"]
             );

    // Chamada PHP para uma URL passando vari�veis com o m�todo POST. Em <b>$response</b> ser� armazenado o resultado do processamento.
    $response = http_post_fields('http://www2.sbpi.com.br/siw/cl_terracap/cadastro.php', $campos);
    $pos      = strpos($response,chr(13).chr(10).chr(13).chr(10));
    $retorno  = substr($response,$pos+4);
    ?>
  <H3>Resultado do processamento no SIW-GP</H3><HR SIZE="1" NOSHADE>
  <P>ATEN��O: o retorno do m�todo sempre � precedido do cabe�alho HTTP, conforme abaixo:</P>
  <table border=0 bgcolor="F0F0F0"><TR><TD><PRE><font face="courier" size="2"><?php echo($response)?></font></pre></table>
  <P>Para remover o cabe�alho da resposta, os comandos PHP s�o:</P>
  <table border=0 bgcolor="F0F0F0"><TR><TD><PRE><font face="courier" size="2">
// Procura pela primeira ocorr�ncia de duas quebras de linha no conte�do da resposta
$pos      = strpos($response,chr(13).chr(10).chr(13).chr(10));

// Soma 4 ao resultado de $pos e pega dessa posi��o em diante
$retorno  = substr($response,$pos+4);</font></pre></table>
  <p>Com isso, o valor de <b>$retorno</b> para os par�metros informados passa a ser o seguinte:</p>
  <table border=0 bgcolor="F0F0F0"><TR><TD><PRE><font face="courier" size="2"><?php echo($retorno)?></font></pre></table>
  <?php 
  }
  ?>
</BODY>
</HTML>
