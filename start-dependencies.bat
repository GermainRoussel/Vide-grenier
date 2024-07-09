
echo -- Installation des dépendances  --
docker exec -t videgrenier-phpapache composer install
echo -- Fin de l'installation des dépendances -- 

echo -- Lancement des tests PhpUnits -- 
docker exec -t videgrenier-phpapache ./vendor/bin/phpunit --testdox
echo -- Fin des test PhpUnits -- 


