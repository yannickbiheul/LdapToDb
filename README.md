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

- 4 éléments à récupérer de l'annuaire :
    - contact
    - number
    - people
- N'enregistre pas les numéros de liste rouge
- N'enregistre pas les numéros de chambres

## Services non reliés :
Ldap ne comprend pas les parenthèses
- IDE Nord (C) Reanimation
- IDE Sud (A) Reanimation
- IDE Ouest (B) Reanimation
- 6066 Sec. Endocrinologie | Sec. Rhumato/Infectio (CONS)
- Sec. Rhumato/Infectio (CONS)
- Secretariat Dr HUTIN (MIMS)
- Support IDE Sit (Actipidos)