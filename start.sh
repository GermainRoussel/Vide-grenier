!/bin/bash


# Attendre que la base de données soit prête (exemple simplifié)
# until mysql -u webapplication -p"653rag9T" -h db -e "SELECT 1"; do
#     echo "En attente de la base de données..."
#     sleep 1
# done

# echo "La base de données est prête."

# Importer la base de données
mysql -u webapplication -p"653rag9T" -h db videgrenierenligne < /var/www/html/sql/import.sql

# Démarrer Apache en mode premier plan
exec apache2-foreground