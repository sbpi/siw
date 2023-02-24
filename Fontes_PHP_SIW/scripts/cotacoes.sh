#!/bin/sh
# Script para execuç da rotina de envio automáco de e-mails

# ATENÇÃO: O CORRETO FUNCIONAMENTO DEPENDE DA CONSTANTE "_DATABASE_NAME" DO
# ARQUIVO classes/db/db_constants.php TER UM VALOR QUE CORREPONDA
# A UMA ENTRADA EM TNSNAMES.ORA, DEVENDO SER IGUAL ATÉ NAS MAIÚSCULAS E MINÚSCULAS.

# 1 - CONFIGURA VARIÁVEIS DE AMBIENTE DO ORACLE
#     ajustar para a instalação do oracle do servidor onde a rotina for executada

# CONFIGURAÇÃO OTCA
#export ORACLE_BASE=/u01/app/oracle
#export ORACLE_HOME=/u01/app/oracle/product/12.1.0.2/db_1
#export ORACLE_HOSTNAME=siw.otca.org.br
#export ORACLE_SID=siw
#export ORACLE_UNQNAME=siw
#export NLS_LANG='BRAZILIAN PORTUGUESE_BRAZIL.WE8MSWIN1252'
#export CLASSPATH=$ORACLE_HOME/jlib:$ORACLE_HOME/rdbms/jlib
#export LD_LIBRARY_PATH=$ORACLE_HOME/lib:/lib:/usr/lib:/usr/lib/oracle/12.1/client64/lib
#export PATH=$ORACLE_HOMEbin:/usr/sbin:/usr/local/bin:/usr/bin

# CONFIGURAÇÃO DOCKER
export ORACLE_BASE=/usr/lib/oracle
export ORACLE_HOME=$ORACLE_BASE/11.2/client64/lib
export ORACLE_SID=ORCL
export NLS_LANG='BRAZILIAN PORTUGUESE_BRAZIL.WE8MSWIN1252'
export CLASSPATH=$ORACLE_HOME/jlib:$ORACLE_HOME/rdbms/jlib
export LD_LIBRARY_PATH=$ORACLE_HOME/lib:/lib:/usr/lib
export PATH=$ORACLE_HOME/bin:/usr/sbin:/usr/local/bin:/usr/bin

umask 002

# 2 - EXECUTA A ROTINA
#     verificar parâmetros conforme orientações contidas no arquivo mod_eo/cotacoes_bc_auto.php

/usr/local/bin/php -c /usr/local/etc/php/php.ini /var/www/html/siw_otca/mod_eo/cotacoes_bc_auto.php 17305 1 OTCAP 17306

