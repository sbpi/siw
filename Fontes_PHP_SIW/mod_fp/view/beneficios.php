<?php
session_start();
if (!isset ($_SESSION['SQ_PESSOA'])) { // (est� definida a sess�o nome_usuario) 
	echo "<script language='javascript'>";
	echo "alert('Voc� precisa est� logado para acessar essa p�gina !!!');";
	echo "window.location='../view/login.html';";
	echo "</script>";
	exit;
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>ABDI - Ag&ecirc;ncia Nacional de Desenvolvimento Industrial</title>
<style type="text/css">
<!--
body {
	margin-top: 0px;
	background-color: #E9E9E9;
}
body,td,th {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
a {
	font-family: Arial, Helvetica, sans-serif;
	font-size: 12px;
}
a:link {
	text-decoration: none;
}
a:visited {
	text-decoration: none;
}
a:hover {
	text-decoration: none;
}
a:active {
	text-decoration: none;
}
.style2 {font-size: 12px}
-->
</style></head>

<body>
<table width="770" border="0" align="center" cellpadding="0" cellspacing="0" bgcolor="#FFFFFF">
  <tr>
    <td height="130" colspan="5" bgcolor="#E9E9E9"><img src="../view/img/abdi.png" /></td>
  </tr>
  <tr>
    <td width="160" height="20" bgcolor="#CCCCCC"><div align="center"><a href="../view/painel.php">P&aacute;gina Inicial</a></div></td>
    <td width="205" height="20" bgcolor="#CCCCCC"><div align="center"><a href="../negocio/funcionario.php?action=buscar">Folha de pagamentos </a></div></td>
    <td width="158" bgcolor="#CCCCCC"><div align="center"><a href="../negocio/funcionario.php?action=listar">Funcion&aacute;rios</a></div></td>
	<td width="140" bgcolor="#CCCCCC"><div align="center"><a href="../negocio/usuario.php?action=listar">Usu�rios</a></div></td>
    <td width="107" height="20" bgcolor="#CCCCCC"><div align="center"><a href="../negocio/usuario.php?action=logout">Sair </a></div></td>
  </tr>
  <tr>
    <td height="300" colspan="5"><div align="center">
      <form id="form2" name="form2" method="post" action="../negocio/login.php?action=login">
        <table width="533" border="0" align="center">
          <?php if($data->Lista['id_beneficio']){?>
		  <tr>
            <td width="180" bgcolor="#CCCCCC"><div align="center">Benef&iacute;cio</div></td>
            <td width="91" bgcolor="#CCCCCC"><div align="center">Periodicidade</div></td>
            <td width="81" bgcolor="#CCCCCC"><div align="center">Desconto</div></td>
            <td width="89" bgcolor="#CCCCCC"><div align="center">Valor</div></td>
            <td bgcolor="#CCCCCC"><div align="center">A&Ccedil;&Atilde;O</div></td>
          </tr>
          <?php do{?>
          <tr>
            <td bgcolor="#F4F4F4"><div align="center"><a href="../negocio/beneficio.php?action=alteracao&amp;id_beneficio=<?php echo $data->Lista['id_beneficio'];?>"><?php echo $data->Lista['nome'];?></a></div></td>
            <td bgcolor="#F4F4F4"><div align="center"><?php echo $data->Lista['periodicidade'];?></div></td>
            <td bgcolor="#F4F4F4"><div align="center"><?php echo $data->Lista['desconto'];?></div></td>
            <td bgcolor="#F4F4F4"><div align="center"><?php echo $data->Lista['valor_beneficio'];?></div></td>
            <td width="70" bgcolor="#F4F4F4"><div align="center"><a href="../negocio/beneficio.php?action=excluir&amp;id_beneficio=<?php echo $data->Lista['id_beneficio'];?>&amp;id_funcionario=<?php echo $_GET['id_funcionario'];?>">DELETAR</a></div></td>

		  </tr>
          <?php }while( $data->ViewDatabase() );?>
		            <?php }?>
          <tr>
            <td colspan="5" bgcolor="#F4F4F4"><div align="right"><a href="../negocio/beneficio.php?action=cadastro&amp;id_funcionario=<?php echo $_GET['id_funcionario'];?>">+ Incluir benef&iacute;cio </a></div></td>
            </tr>
        </table>
      </form>
    </div></td>
  </tr>
  <tr>
    <td colspan="5" bgcolor="#E9E9E9">&nbsp;</td>
  </tr>
  <tr>
    <td colspan="5"><div id="apoio_institucional" style="margin-left:25px;">
      <div>
        <p class="style2" id="endereco_pe"><strong>Ag&ecirc;ncia Brasileira de Desenvolvimento Industrial</strong><br />
          SBN Quadra 1 - Bloco B - Ed. CNC - 14&ordm; andar - Bras&iacute;lia/DF - Brasil - CEP: 70041-902<br />
          Contato: TEL: +55 61 3962.8700 | +55 61 3962.8700<br />
          FAX: +55 61 3962.8715 | E-mail: <a href="mailto:abdi@abdi.com.br">abdi@abdi.com.br</a></p>
      </div>
    </div></td>
  </tr>
</table>
</body>
</html>
