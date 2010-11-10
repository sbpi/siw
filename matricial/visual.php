<?php 
include_once('jscript.php');
?>
<HTML>
<HEAD>
  <TITLE>SIW-GP - Roteiro de Integra��o com MatricialNet</TITLE>
<?php
ScriptOpen('Javascript');
ValidateOpen('Validacao');
Validate('uid','Username','','1','1','20','1','');
Validate('pwd','Senha','','1','1','20','1','');
Validate('nome','Nome do usu�rio','','1','1','60','1','');
Validate('mail','e-Mail do usu�rio','EMAIL','1','1','60','1','');
Validate('codigo','C�digo do programa','','','1','20','1','');
ValidateClose();
ScriptClose();
?>
</HEAD>
<link rel="stylesheet" type="text/css" href="xPandMenu.css">
<BODY>
  <H2>Roteiro de Integra��o SIW-GP x MatricialNet</H2>
  <H3>Abre SIW-GP em outra janela, exibindo relat�rio executivo dos projetos de um programa</H3>
  <UL>
    <LI><a href="default.htm">Volta ao menu principal</a></LI>
    <LI><a href="cadastro.php">Manipula cadastro de programas</a></LI>
    <LI><a href="indicador.php">Recupera quantitativos dos programas</a></LI>
    <LI><a href="unidade.php">Recupera quantitativos das unidades</a></LI>
  </UL>
  <p>A exibi��o do relat�rio executivo de um programa deve ser feita atrav�s de uma chamada Internet, usando o m�todo POST, passando os dados informados abaixo. O nome dos campos deve ser totalmente informado em letras min�sculas, tendo em vista o PHP ser uma linguagem case-sensitive.</p>
  <p><FONT COLOR="#BC3434"><B>ATEN��O: se for passado um c�digo de programa que ainda n�o tenha sido criado no SIW-GP, a janela ser� aberta na tela padr�o, ou seja, na mesa de trabalho.</FONT></b></p>
  <form name="Form" action="visual.php" method="POST" onSubmit="return(Validacao(this));">
    <input type="hidden" name="cli" value="16316"></input>
    <table width="100%" border=1>
      <tr valign="top"><th>Campo</th><th>Valor</th><th>Observa��es</th></tr>
      <tr valign="top"><td>cli</td><td>16316</td><td>OBRIGAT�RIO. Indica o c�digo da vers�o do SIW-GP para a TERRACAP.</td></tr>
      <tr valign="top"><td>uid</td><td><input type="TEXT" class="STI" name="uid" value="<?php echo $_POST["uid"]?>" size="20" maxlength="20"></input></td><td>OBRIGAT�RIO. VARCHAR(20). Username do usu�rio no MatricialNET. Na passagem ao SIW-GP deve ser codificado em Base64.</td></tr>
      <tr valign="top"><td>pwd</td><td><input type="TEXT" class="STI" name="pwd" value="<?php echo $_POST["pwd"]?>" size="20" maxlength="20"></input></td><td>OBRIGAT�RIO. VARCHAR(20). Senha do usu�rio no MatricialNet. Na passagem ao SIW-GP deve ser codificado em Base64.</td></tr>
      <tr valign="top"><td>nome</td><td><input type="TEXT" class="STI" name="nome" value="<?php echo $_POST["nome"]?>" size="20" maxlength="60"></input></td><td>OBRIGAT�RIO. VARCHAR(60). Nome do usu�rio no MatricialNET. Na passagem ao SIW-GP deve ser codificado em Base64.</td></tr>
      <tr valign="top"><td>mail</td><td><input type="TEXT" class="STI" name="mail" value="<?php echo $_POST["mail"]?>" size="20" maxlength="60"></input></td><td>OBRIGAT�RIO. VARCHAR(60). e-Mail do usu�rio no MatricialNET. Na passagem ao SIW-GP deve ser codificado em Base64.</td></tr>
      <tr valign="top"><td>codigo</td><td><input type="TEXT" class="STI" name="codigo" value="<?php echo $_POST["codigo"]?>" size="20" maxlength="20"></input></td><td>OPCIONAL. VARCHAR(20). C�digo do programa a ter seu relat�rio executivo exibido. Se n�o for informado, todos os programas oriundos do MatricialNET ser�o detalhados.</td></tr>
      <tr valign="top"><td colspan="3"><input type="SUBMIT" class="STB" name="botao" value="PROCESSAR"></input></td></tr>
    </table> 
  </form>
  <?php
  if (isset($_POST['uid'])) {
    ?>
    <p>C�digo HTML a ser gerado para a chamada ao SIW-GP: (o endere�o base para o ambiente de desenvolvimento � <b>http://www2.sbpi.com.br/siw/cl_terracap</b>. Esse endere�o deve estar configurado em alguma constante para poder ser facilmente alterado para o ambiente de produ��o.)</p>
    <table border=0 bgcolor="F0F0F0"><TR><TD>
    <PRE><font face="courier" size="2"><b>    &lt;form name="Form1" action="&lt;endere�o-base>/visual.php" method="POST" target="SIW">
      &lt;input type="hidden" name="cli" value="<?php echo $_POST["cli"]?>">&lt;/input>
      &lt;input type="hidden" name="uid" value="<?php echo base64_encode($_POST["uid"])?>">
      &lt;input type="hidden" name="pwd" value="<?php echo base64_encode($_POST["pwd"])?>">
      &lt;input type="hidden" name="nome" value="<?php echo base64_encode($_POST["nome"])?>">
      &lt;input type="hidden" name="mail" value="<?php echo base64_encode($_POST["mail"])?>">
      &lt;input type="hidden" name="codigo" value="<?php echo $_POST["codigo"]?>">
    &lt;/form>
    &lt;SCRIPT LANGUAGE="JAVASCRIPT">&lt;!--
      document.Form1.submit(); 
    -->&lt;/SCRIPT></PRE></b>
    </font></TD></TR></table>
    <p>Em caso de sucesso, a rotina abrir� uma janela do navegador com o SIW-GP logado no usu�rio informado, apresentando o relat�rio executivo do programa informado; se o programa n�o existir, o SIW-GP apresentar� a mesa de trabalho.</p>
	  <form name="Form1" action="http://www2.sbpi.com.br/siw/cl_terracap/visual.php" method="POST" target="SIW">
	    <input type="hidden" name="cli" value="<?php echo utf8_encode($_POST["cli"])?>"></input>
	    <input type="hidden" name="uid" value="<?php echo base64_encode(utf8_encode($_POST["uid"]))?>">
	    <input type="hidden" name="pwd" value="<?php echo base64_encode(utf8_encode($_POST["pwd"]))?>">
	    <input type="hidden" name="nome" value="<?php echo base64_encode(utf8_encode($_POST["nome"]))?>">
	    <input type="hidden" name="mail" value="<?php echo base64_encode(utf8_encode($_POST["mail"]))?>">
	    <input type="hidden" name="codigo" value="<?php echo utf8_encode($_POST["codigo"])?>">
	  </form>
    <SCRIPT LANGUAGE="JAVASCRIPT"><!--
      document.Form1.submit(); 
    --></SCRIPT>
    <?php
  }
  ?>
</BODY>
</HTML>
