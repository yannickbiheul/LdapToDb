# Fonctionnement du composant Symfony Ldap
## Se connecter au serveur Ldap sécurisé par StartTls
    $ldap = Ldap::create('ext_ldap', [
        'host' => 'my-server',
        'encryption' => 'ssl',
    ]);

Ou : 

    $ldap = Ldap::create('ext_ldap', ['connection_string' => 'ldaps://my-server:636']);

## Ajouter le dn et le password
    $dn = "dn";
    $password = "password";
    $ldap->bind($dn, $password);

## Faire des requêtes au serveur
    $query = $ldap->query('dc=symfony,dc=com', '&(objectclass=person)(ou=Maintainers))');
    $results = $query->execute();

    foreach ($results as $entry) {
        // Do something with the results
    }

### Pour retourner directement un tableau
    $results = $query->execute()->toArray();

### Utiliser l'option "filter" pour récupérer des attributs spécifiques
    $query = $ldap->query('dc=symfony,dc=com', '...', ['filter' => ['cn', 'mail']);