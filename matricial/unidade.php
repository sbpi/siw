<?php 
include_once('jscript.php');
?>
<HTML>
<HEAD>
  <TITLE>SIW-GP - Roteiro de Integra��o com MatricialNet</TITLE>
<?php
ScriptOpen('Javascript');
ValidateOpen('Validacao');
Validate('unidade','C�digo da unidade','','','1','20','1','1');
Validate('codigo','C�digo do programa','','','1','20','1','1');
ValidateClose();
ScriptClose();
?>
</HEAD>
<link rel="stylesheet" type="text/css" href="xPandMenu.css">
<BODY>
  <H2>Roteiro de Integra��o SIW-GP x MatricialNet</H2>
  <H3>Recupera quantitativos das unidades</H3>
  <UL>
    <LI><a href="default.htm">Volta ao menu principal</a></LI>
    <LI><a href="cadastro.php">Manipula cadastro de programas</a></LI>
    <LI><a href="indicador.php">Recupera quantitativos dos programas</a></LI>
    <LI><a href="visual.php">Abre SIW-GP em outra janela, exibindo relat�rio executivo dos projetos de um programa</a></LI>
  </UL>
  <p>A recupera��o dos quantitativos dos programas no SIW-GP deve ser feita atrav�s de uma chamada Internet, usando o m�todo POST, passando os dados informados abaixo. O nome dos campos deve ser totalmente informado em letras min�sculas, tendo em vista o PHP ser uma linguagem case-sensitive.</p>
  <form name="Form" action="unidade.php" method="POST" onSubmit="return(Validacao(this));">
    <input type="hidden" name="cli" value="16316"></input>
    <input type="hidden" name="uid" value="MATRICIALNET"></input>
    <input type="hidden" name="pwd" value="MATRICIAL1"></input>
    <table width="100%" border=1>
      <tr valign="top"><th>Campo</th><th>Valor</th><th>Observa��es</th></tr>
      <tr valign="top"><td>cli</td><td>16316</td><td>OBRIGAT�RIO. Indica o c�digo da vers�o do SIW-GP para a TERRACAP.</td></tr>
      <tr valign="top"><td>uid</td><td>MATRICIALNET</td><td>OBRIGAT�RIO. Indica o usu�rio que est� fazendo a conex�o. Deve ser sempre esse valor, codificado em BASE64.</td></tr>
      <tr valign="top"><td>pwd</td><td>MATRICIAL1</td><td>OBRIGAT�RIO. Senha do usu�rio que est� fazendo a conex�o. Deve ser sempre esse valor, codificado em BASE64, a menos que seja alterada no SIW-GP.</td></tr>
      <tr valign="top"><td>unidade</td><td><input type="TEXT" class="STI" name="unidade" value="<?php echo $_POST["unidade"]?>" size="20" maxlength="20"></input></td><td>OPCIONAL. C�digo da unidade organizacional a ter seus quantitativos recuperados. Se n�o for informado, a rotina trar� os quantitativos de todos os programas do SIW-GP que tenham sido criados pelo MatricialNet e sejam vinculados � unidade informada.</td></tr>
      <tr valign="top"><td>codigo</td><td><input type="TEXT" class="STI" name="codigo" value="<?php echo $_POST["codigo"]?>" size="20" maxlength="20"></input></td><td>OPCIONAL. C�digo do programa ligado � unidade, a ter seus quantitativos recuperados. Se n�o for informado, a rotina trar� os quantitativos de todos os programas do SIW-GP que tenham sido criados pelo MatricialNet e sejam vinculados � unidade informada.</td></tr>
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
    <b>$campos = array(
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'cli' => '<?php echo $_POST["cli"]?>',
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'uid' => '<?php echo base64_encode($_POST["uid"])?>',
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'pwd' => '<?php echo base64_encode($_POST["pwd"])?>',
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'unidade' => '<?php echo $_POST["unidade"]?>',
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;'codigo' => '<?php echo $_POST["codigo"]?>',
    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;);</b>
    
    // Chamada PHP para uma URL passando vari�veis com o m�todo POST. Em <b>$response</b> ser� armazenado o resultado do processamento.
    <b>$response = http_post_fields(&lt;endere�o-base>/unidade.php, $campos);</font></b></PRE>
    ?>
    </font></TD></TR></table>
    <p>A rotina retornar� para <b>$response</b> um dos valores abaixo:</p>
    <dl>
      <dt>200<dd>A consulta ocorreu com sucesso. Ser� retornada uma segunda linha contendo string separada por ponto-e-virgula com os dados do programa informado.<br>Se n�o foi informado um programa, ser� retornada uma linha para cada programa do SIW-GP que tenha sido criado pelo MatricialNet, ordenadas pelo c�digo do programa.
                 <ol>
                 <li>C�digo da unidade, conforme campo "C�dgo externo" da tela de cadastramento de unidades do SIW-GP</li>
                 <li>C�digo do programa</li>
                 <li>Quantidade de projetos vinculados ao programa que estejam com situa��o verde (data de t�rmino n�o ultrapassada e al�m do n�mero de dias de aviso)</li>
                 <li>Quantidade de projetos vinculados ao programa que estejam com situa��o amarela (data de t�rmino n�o ultrapassada mas dentro do n�mero de dias de aviso)</li>
                 <li>Quantidade de projetos vinculados ao programa que estejam com situa��o vermelha (data de t�rmino ultrapassada)</li>
                 <li>Quantidade de projetos vinculados ao programa que estejam conclu�dos</li>
                 </ol>
                 A quantidade total de projetos � a soma das quatro �ltimas colunas. Exemplo de resultado:
                 <table border=0 bgcolor="F0F0F0"><TR><TD><font face="courier" size="2">
                 200<br>
                 UNID1;PROG1;15;5;1<br>
                 UNID2;PROG2;4;1;1<br>
                 UNID2;PROG3;10;0;0
                 </font></TD></TR></table>
             </dd>
      </dt>
      <dt>500<dd>Autentica��o inv�lida. Verificar se os valores das vari�veis <b>uid</b> e <b>pwd</b> est�o corretamente configurados. Neste caso, apenas uma linha � retornada.</dd></dt>
      <dt>501<dd>Erro de preenchimento. Neste caso, a primeira linha ter� o valor 501 e as demais indicar�o o(s) erro(s). Os erros podem ser causados pelo n�o preenchimento de algum campo ou pelo preenchimento incorreto de um ou mais deles.</dd></dt>
    </dl>
    <?php
    // Cria��o de um array PHP com os pares vari�vel-valor do formul�rio. Os nomes das vari�veis devem ser exatamente iguais aos indicados.
    $campos = array('cli' => utf8_encode($_POST["cli"]),
              'uid' => base64_encode(utf8_encode($_POST["uid"])),
              'pwd' => base64_encode(utf8_encode($_POST["pwd"])),
              'unidade' => utf8_encode($_POST["unidade"]),
              'codigo' => utf8_encode($_POST["codigo"])
             );

    // Chamada PHP para uma URL passando vari�veis com o m�todo POST. Em <b>$response</b> ser� armazenado o resultado do processamento.
    $response = http_post_fields('http://www2.sbpi.com.br/siw/cl_terracap/unidade.php', $campos);
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
