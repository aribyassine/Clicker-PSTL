PSTL
========
#### Server Side API (RESTful)

- Apache
- PHP 7
- MySQL

Update config file in .env 

    $ composer update
    $ php artisan migrate
    $ php db:seed
    
Description
========
Diverses formations de l’UPMC distribuent en amphi des « clikeurs » qui permettent aux étudiants de répondre à des questions posées pendant le cours, puis à l’enseignant de visualiser en direct l’ensemble des réponses, voire de les afficher en temps réel dans ses transparents. Ces solutions « propriétaires » ont un coût significatif et ne peuvent pas être customisées en fonction du besoin de chacun.

Nous souhaitons proposer un système aux propriétés similaires, mais qui soit ouvert et qui repose sur l’utilisation du smartphone des étudiants. Le projet d’ensemble est découpé en 3 sous-parties.

L’objectif de cette partie est de réaliser la partie serveur et base de données du projet. Le binôme concerné devra mettre en place le serveur, définir les bases de données permettant de stocker les questionnaires définis par les enseignants et les réponses saisies par les étudiants et assurer la communication entre ces bases de données et les services (côté étudiants et côté enseignants) qui ont besoin d’y accéder.
