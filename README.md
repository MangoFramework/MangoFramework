## Mango PHP Framework

Mango est un framework MVC d’API RESTful, il permet la mise en place rapide et simple d’une API pouvant communiquer avec un client par requête HTTP et en suivant l’architecture REST. Son moteur puissant vous permet de vous concentrer sur vos données et votre conception, et d’utiliser votre API pour communiquer avec un client intelligent. Nous reposons notre force sur plusieurs composants, nous utilisons l’ORM Eloquent, utilisé par Laravel, avec une prise en main facile et un système de migrations expressif, vous faites évoluer votre schéma de données en parfaite harmonie avec le REST de votre architecture ! Mango framework vous permettra de créer une Web application, une application mobile, ou même d’être utilisé en tant que Middleware.

## Official Documentation

Documentation for the entire framework can be found on the [Mango website](http://mango-framework.com/documentation.html).


### License

The Mango Framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)

### TUTO

TUTO :

-Install
	-composer
	-database config ( db vierge)
créer une database vierge « bibliotheque »
		<code>
		'host' => 'localhost',
         		   'database' => 'bibliotheque',
       		     'username' => 'root',
          		  'password' => '',
		</code>
- Eloquent migration
	Commencer par générer le fichier de migration :
Commande line :
<code>php console.php orm generate</code>

C’est la fonction up() situé dans ce fichier qui sera effectuer lors de la migration.
Nous allons créer trois table afin de vous montrer efficacement la prise en main du framework.
Voici le code nécessaire :

<code>
$this->get('schema')->create('users', function($table){
            $table->increments('id');
            $table->string('email')->unique();
            $table->string('password');
            $table->timestamps();
            $table->softDeletes();
        });
        $this->get('schema')->create('authors', function($table){
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
            $table->softDeletes();
        });
        $this->get('schema')->create('books', function($table){
            $table->increments('id');
            $table->string('name');
            $table->text('content');
            $table->integer('author_id')->unsigned()->index();
            $table->foreign('author_id')->references('id')->on('authors');
            $table->timestamps();
            $table->softDeletes();
        });
<code>

Rest Test client

url /debugger

3 min


Custom Route

modele author :
    public function books()
    {
        return $this->hasMany('\models\Book');
    }
author controler :
    public $routes = array(
        '/author/:id/books' => 'booksRelation'
    );

    public function booksRelation($id)
    {
        $books = models\Author::find($id)->books;

        $arr = array();
        foreach($books as $book)
        {
            $arr[] = $book->getAttributes();
        }

        self::$response->setType('json');
        return $arr;
    }


 - Custom Controller

php console.php generate controller toto

show method :

return array('toto show') ;


public $routes = array(
        '/toto/myRoute/:nombre' => array(
            'method' => 'helloWorld',
            'cond' => array(
               ':nombre' => '\d+'
            )
        )
    );

    public function helloWorld($nombre)
    {
	return array('here');
    }