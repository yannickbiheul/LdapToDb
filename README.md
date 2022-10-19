# ldapToDb

## Installation
    git clone https://github.com/yannickbiheul/LdapToDb.git
    composer install

## Configuration
- Dupliquer le fichier ".env" en ".env.local"
- Décommenter et/ou modifier la ligne "DATABASE_URL"

## Fonctionnement
- Une tâche planifiée est créée sur le serveur
- Cette tâche lance la commande "app:test-command"
- Ligne complète de cette tâche :

    php C:\Users\yannick.biheul\test\bin\console app:test-command 