# ldapToDb

## Installation
    git clone https://github.com/yannickbiheul/LdapToDb.git
    composer install

## Configuration
- Dupliquer le fichier ".env" en ".env.local"
- Remplir les variables d'environnement
- Décommenter et/ou modifier la ligne "DATABASE_URL"

## Fonctionnement
- Une tâche planifiée est créée sur le serveur
- Cette tâche lance la commande "app:main" :

    php {chemin_du_projet}\bin\console app:main 

- Cette commande récupère :
    - contactRecord
    - numberRecord
    - peopleRecord

- Ensuite une autre commande est lancée pour remplir les tables :

    php {chemin_du_projet}\bin\console app:second

- Remplie les tables : 
    - Hopital
    - Batiment
    - Pole
    - Metier
    - Service
    - Personne
- N'enregistre pas les numéros de liste rouge
- N'enregistre pas les numéros de chambres
