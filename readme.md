**API pour un système de suivi des performances sportives avec Symfony 6**

**Description :**
- Vous allez développer une API  pour permettre aux utilisateurs d'enregistrer et de suivre leurs performances sportives.
L'API devra gérer les activités sportives enregistrées par les utilisateurs et leur permettre de consulter leurs propres données de performance.

Vous devrez utiliser des services personnalisés pour gérer les opérations liées aux activités sportives et aux utilisateurs

**Tâches à accomplir :**

- Configuration de Symfony 6 : Mettez en place un nouveau projet Symfony 6 en utilisant la structure recommandée par Symfony.

- Configurez la base de données et les paramètres d'accès en fonction des besoins du système de suivi des performances sportives.

**Modèles de données :**

 **Utilisateur :**
 - _Cette entité doit inclure des champs tels que l'ID utilisateur, le nom, l'adresse e-mail, le mot de passe haché, la date d'inscription._

 **Activité sportive :**
 - _Cette entité doit inclure des champs tels que l'ID de l'activité, l'ID de l'utilisateur associé, le type d'activité, la durée, la distance parcourue, les calories brûlées, la date et l'heure de l'activité._

**Services personnalisés :**
Implémentez des services personnalisés pour gérer les opérations liées aux activités sportives et aux utilisateurs.

Ces services devraient inclure des méthodes pour :
- _Enregistrer les activités._
- _Récupérer les activités d'un utilisateur spécifique._
- _Récupérer les activités d'un utilisateur spécifique de la plus récente à la plus vieille._
- _Récupérer les activités d'un utilisateur spécifique triées par la durée._
- _Récupérer les activités d'un utilisateur spécifique triées par les calories brûlées._
- _Récupérer les activités d'un utilisateur spécifique triées par le type d'activité._
- Rechercher un utilisateur par le nom (like)

**Contrôleurs :**
- _Créez des contrôleurs pour gérer les requêtes HTTP et appeler les services appropriés en fonction des actions demandées, telles que l'enregistrement d'une nouvelle activité, la récupération des performances, la définition des objectifs, etc._

**Sécurité :**
- _Mettez en place des mécanismes de sécurité tels que l'authentification et l'autorisation pour restreindre l'accès à certaines fonctionnalités de l'API, notamment les données sensibles des utilisateurs._