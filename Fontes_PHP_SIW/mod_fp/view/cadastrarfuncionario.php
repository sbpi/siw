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
      <form id="form" name="form" method="post" action="../negocio/funcionario.php?action=cadastrar">
        <p>&nbsp;</p>
        <table border="0" align="center">
          <tr>
            <td colspan="2"><div align="center"><strong>NOVO FUNCION&Aacute;RIO </strong></div></td>
            </tr>
          <tr>
            <td width="107"><div align="right">Nome: </div></td>
            <td width="183"><input name="nome" type="text" id="nome" />            </td>
          </tr>
          <tr>
            <td width="107"><div align="right">CPF: </div></td>
            <td width="183"><input name="cpf" type="text" id="cpf" />            </td>
          </tr>
          <tr>
            <td><div align="right">Matr&iacute;cula: </div></td>
            <td><input name="matricula" type="text" id="matricula" /></td>
          </tr>
          <tr>
            <td><div align="right">Departamento:</div></td>
            <td><select name="id_departamento" id="id_departamento">
              <?php do{?>
              <option value="<?php echo $dataDepartamento->Lista['id_departamento'];?>"><?php echo $dataDepartamento->Lista['departamento'];?></option>
              <?php } while ($dataDepartamento->ViewDatabase());?>
            </select></td>
          </tr>
          <tr>
            <td><div align="right">Cargo: </div></td>
            <td><select name="id_cargo" id="id_cargo">
            		<?php do{?>
            		<option value="<?php echo $data->Lista['id_cargo'];?>"><?php echo $data->Lista['nome'];?></option>
            		<?php } while ($data->ViewDatabase());?>
            	</select></td>
          </tr>
          <tr>
            <td><div align="right">UF: </div></td>
            <td><select name="id_uf" id="uf">
            		<option selected="selected">UF</option>
            		<?php do{?>
              		<option value="<?php echo $dataUf->Lista['id_uf'];?>"><?php echo $dataUf->Lista['uf'];?></option>
              		<?php } while ($dataUf->ViewDatabase());?>
            </select></td>
          </tr>
          <tr>
            <td><div align="right">Cidade: </div></td>
            <td><input name="cidade" type="text" id="cidade" /></td>
          </tr>
          <tr>
            <td><div align="right">Bairro: </div></td>
            <td><input name="bairro" type="text" id="bairro" /></td>
          </tr>
          <tr>
            <td><div align="right">Logradouro:</div></td>
            <td><input name="logradouro" type="text" id="logradouro" /></td>
          </tr>
          <tr>
            <td><div align="right">CEP:</div></td>
            <td><input name="cep" type="text" id="cep" /></td>
          </tr>
          <tr>
            <td><div align="right">DDD:</div></td>
            <td><input name="prefixo_telefone" type="text" id="prefixo_telefone" size="2" maxlength="2" /></td>
          </tr>
          <tr>
            <td><div align="right">Telefone:</div></td>
            <td><input name="telefone" type="text" id="telefone" /></td>
          </tr>
          <tr>
            <td><div align="right">Email: </div></td>
            <td><input name="email" type="text" id="email" /></td>
          </tr>
          <tr>
            <td><div align="right">Sal&aacute;rio Base: </div></td>
            <td><input name="salariobase" type="text" id="salariobase" /></td>
          </tr>
          <tr>
            <td colspan="2"><div align="center">
              <input type="submit" name="Submit" value="Cadastrar" />
            </div></td>
          </tr>
        </table>
        <p>&nbsp;</p>
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
