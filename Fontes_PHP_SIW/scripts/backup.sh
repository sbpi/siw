#!/bin/bash

# Script for automated backup

# 1 - SET THE ENVIRONMENT VARIABLES
# CONFIGURAÇÃO OTCA
export ORACLE_BASE=/u01/app/oracle
export ORACLE_HOME=/u01/app/oracle/product/12.1.0.2/db_1
export ORACLE_HOSTNAME=siw.otca.org.br
export ORACLE_SID=siw
export ORACLE_UNQNAME=siw
export NLS_LANG='BRAZILIAN PORTUGUESE_BRAZIL.WE8MSWIN1252'
export CLASSPATH=$ORACLE_HOME/jlib:$ORACLE_HOME/rdbms/jlib
export LD_LIBRARY_PATH=$ORACLE_HOME/lib:/lib:/usr/lib:/usr/lib/oracle/12.1/client64/lib
export PATH=$ORACLE_HOMEbin:/usr/sbin:/usr/local/bin:/usr/bin

# CONFIGURAÇÃO DOCKER
#export ORACLE_BASE=/usr/lib/oracle
#export ORACLE_HOME=$ORACLE_BASE/11.2/client64/lib
#export ORACLE_SID=ORCL
#export NLS_LANG='BRAZILIAN PORTUGUESE_BRAZIL.WE8MSWIN1252'
#export CLASSPATH=$ORACLE_HOME/jlib:$ORACLE_HOME/rdbms/jlib
#export LD_LIBRARY_PATH=$ORACLE_HOME/lib:/lib:/usr/lib
#export PATH=$ORACLE_HOME/bin:/usr/sbin:/usr/local/bin:/usr/bin
 
umask 002 

# 2 - SET FILENAMES
diretorio=/var/www/html/siw_files
filename=$diretorio/17305/tmp/bkp_$(/bin/date +%Y%m%d_%H%M).zip

# 3 - EXPORT DATABASE
$ORACLE_HOME/bin/exp siw/eeool22012@siw file=siw.dmp log=exp.log 

$ORACLE_HOME/bin/zip -m $filename siw.dmp exp.log
$ORACLE_HOME/bin/zip -r -u $diretorio/17305/tmp/bkp_siw_files.zip  $diretorio/17305 $diretorio/log -x $diretorio/17305/tmp/**\*
