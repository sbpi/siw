<html>
  <head>
    <title>PHP 5</title>
  </head>
  <body>
    <!--<IMG SRC="http://io/siw/geragrafico.php?p_genero=M&p_objeto=Novo projeto&p_tipo=&p_grafico=Barra&p_tot=13&p_cad=2&p_tram=6&p_conc=5&p_atraso=4&p_aviso=0&p_acima=0">-->
    <? 
    $cmd = 'c:\\php\\svn ls "http://172.27.2.1/svn/sicof/trunk/"';
    $cmd = 'ping localhost';
    echo $cmd;

    if (!($dir = popen($cmd, 'r'))) { 
       echo "pau";
    } else {
       pclose($dir);
    }
    $output = 'ping localhost';
    echo $output;
    phpinfo(); 
    ?>
  </body>
</html>