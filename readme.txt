SISTEMA SIIM WEB - QMAP MODULO IMPOSITIVO


********************************************************************************
 POSIBLES ERRORES
********************************************************************************
1. En caso de no generar reportes por barrio (Todos), modificar el archivo de configuracion de php (nano /etc/php/7.2/apache2/php.ini).
 * Error: Fatal error: Allowed memory size of 134217728 bytes exhausted (tried to allocate 20480 bytes) in ...
 * Cambiar, memory_limit = 256M por memory_limit = 2G (Según recursos de hardware)

2. Paquetes para la generacion de reportes con DOMPdf, ejecutar:
 * sudo apt-get install -y php-dompdf
 * sudo apt-get install php-mbstring
 * sudo apt-get install -y php7.1-xml php7.1-xsl php7.1-mbstring php7.1-readline php7.1-zip php7.1-mysql php7.1-phpdbg php7.1-interbase php7.1-sybase php7.1 php7.1-sqlite3 php7.1-tidy php7.1-opcache php7.1-pspell php7.1-json php7.1-xmlrpc php7.1-curl php7.1-ldap php7.1-bz2 php7.1-cgi php7.1-imap php7.1-cli php7.1-dba php7.1-dev php7.1-intl php7.1-fpm php7.1-recode php7.1-odbc php7.1-gmp php7.1-common php7.1-pgsql php7.1-bcmath php7.1-snmp php7.1-soap php7.1-mcrypt php7.1-gd php7.1-enchant libapache2-mod-php7.1 libphp7.1-embed
 * sudo apt-get install php-xml
 * sudo apt-get install elkarbackup
