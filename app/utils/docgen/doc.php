Section configuration:
- DocGenerator

Chemin: app/config/docgen.php

Vous trouverez dans ce fichier un array servant à définir les options de la docGenerator

'folder' => (par défaut array('controllers','factories')) tableau contenant les chemins des dossiers
où se trouvents les fichiers à parser
'route' => (par défaut '/documentation') string contenant le nom de la route où accéder à la documentation générée
'file' => (par défaut 'doc.php') string contenant le chemin ou placer le fichier de la documentation générée.

______________________________________

Section DocGenerator:

La docGenerator permet de générer une documentation html en parsant les commentaires liées
aux méthodes et aux attributs d'une ou plusieurs classe(s).

Conventions de nommage:
    - Classe:
    /**
     * docgen
     *
     * Class UserController
     *
     * @type: class
     * @range: controller

    - Method:
<code>
        /**
         * Your description
         * ... Your Description
         *
         * @type: method visibility
         * @param: type $name (description)
         * @return: type $name (description)
         */
         (static) visibility (static) function hello($name) { OR
         (static) visibility (static) function hello($name)
         {
         }
</code>

    - Attribute:
<code>
        /**
         * Your description
         * ... Your Description
         *
         * @type: attribute visibility (static) type
         * @name: $name
         */
        (static) visibility (static) $hello = value;
</code>

        notice every arguments with () are not mandatory

    - Skip a comment:
<code>
        You can add a "!" at the second line of your comment to skip it
        /**
         * !
         * Your description
         * ... Your Description
         *
         * @type: method (visibility(public, protected or private))
         * @param: type $name (description)
         * @return: type $name (description)
         */
</code>
