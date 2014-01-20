git pull
./composer.phar update
cat config/autoload/odm.local.php.dist \
    | sed "s/\/\/                'server'    => 'localhost',/                'server'    => 'dev0.in',/" \
    | sed "s/\/\/                'dbname'    => null,/                'dbname'    => 'mosbot',/" \
> config/autoload/odm.local.php
