# SfForum
Exercice avec le Framework Symfony pour créer un forum

Je vais m'inspirer du forum d'un studio de jeux vidéos : https://forums.totalwar.com/

## Consigne

Vous êtes en charge de développer un forum sous le framework Symfony
Ce forum sera composé de catégories (thématiques) principales desquelles découleront des sujets (topics) et des messages (posts).
Les topics seront classés du plus récent au plus ancien tandis que pour les posts, ce sera l'inverse.
Que ce soit pour les topics ou les posts, on devra connaître leur date de publication ainsi que l'auteur. Ce dernier aura d'ailleurs la possibilité de verrouiller / déverrouiller un topic lui appartenant (dans un sujet verrouillé, aucune possibilité d'ajouter un message).
L'auteur aura également le droit de déclarer un topic comme "résolu". Là aussi, aucune possibilité d'ajouter un message supplémentaire une fois que le topic est considéré comme résolu.
L'auteur est identifié par un pseudo. Le mot de passe doit au moins faire 8 caractères
On ne doit pas connecter l'utilisateur directement après son enregistrement. Il devra forcément se connecter manuellement post-enregistrement via le formulaire de login dédié.



Nous pouvons consulter le forum en étant déconnecté / non inscrit.
En revanche pour toute interaction avec les fonctionnalités suivantes, il faudra être connecté pour :
- créer un nouveau topic dans une catégorie spécifique (avec le 1er message)
- ajouter un nouveau post dans un topic existant
- éditer un topic / post (dont on est l'auteur)
- verrouiller / déverrouiller un sujet (dont on est l'auteur)
- marquer comme résolu un sujet (dont on est l'auteur)



Pas besoin de gérer le rôle admin dans ce forum.



Options :
- Grâce à KNPPaginator (bundle), vous pouvez mettre facilement en place une pagination pour la liste des topics ou la liste des posts (voir la doc de KnpPaginator)

