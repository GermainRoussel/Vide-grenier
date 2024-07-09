<?php
use PHPUnit\Framework\TestCase;
use App\Models\Articles;
use DateTime;


/**
 * ArticlesTest Model
 */
final class ArticlesTest extends TestCase {

 
    /** @test */
    public function getAllTest():void {
        $filter="data"; 
        $result = Articles::getAll($filter); // appel de la méthode getAll de Article
        //fwrite(STDOUT, print_r($result, TRUE)); // affiche les résultats de la recherche, si besoin pour le débogage
      
        $this->assertGreaterThan(1, count($result)); // vérifie que le nombre de résultats est supérieur à 1
        $keys = array_keys($result[0]); // récupère les clés du premier élément du tableau
        $this->assertSame(["id", "name", "description", "published_date", "user_id", "views", "picture"], $keys); // vérifie que les clés correspondent à celles attendues
    }

    /**
     * ?
     * @access public
     * @return string|boolean
     * @throws Exception
     */
    /** @test */
    public function getOneTest() {
        $result = Articles::getOne(1); // appel de la méthode getOne de Article
        $this->assertSame(1, count([$result])); // vérifie que le nombre de résultats est égal à 1
        
        $keys = array_keys($result[0]); // récupère les clés du premier élément du tableau
        $this->assertSame(["id", "name", "description", "published_date", "user_id", "views", "picture","username", "email", "password", "salt", "is_admin"], $keys);// vérifie que les clés correspondent à celles attendues
    }

    /** @test */
    public function addOneViewTest() {
        // Préparation : Créer un article pour tester
        $newArticleData = [ // Données de l'article
            'name' => 'Mappemonde à gratter',
            'description' => 'Carte du monde à gratter. Neuve dans son emballage d’origine',
            'user_id' => 1, 
        ];
        $articleId = Articles::save($newArticleData); // Sauvegarder l'article dans la base de données
        Articles::addOneView($articleId); // Incrémenter les vues de l'article
        $updatedArticle = Articles::getOne($articleId); // Récupérer l'article mis à jour
        $viewsAfterIncrement = $updatedArticle[0]['views']; // Récupérer le nombre de vues après l'incrémentation
    
        // Nettoyage de la base de données de l'article créé pour les test à rajouter //
        $this->assertEquals(1, $viewsAfterIncrement, "Les vues de l'article n'ont pas été incrémentées correctement."); // Vérifier si les vues ont été incrémentées correctement
    }

     /** @test */
    public function getByUserTest() {
        $result = Articles::getByUser(1); // appel de la méthode getByUser de Article
        $keys = array_keys($result[0]); // récupère les clés du premier élément du tableau
        $this->assertSame(["id", "name", "description", "published_date", "user_id", "views", "picture", "username", "email", "password", "salt", "is_admin"], $keys); // vérifie que les clés correspondent à celles attendues

    }

     /** @test */
    public function getSuggestTest() {
        $result = Articles::getSuggest(); // appel de la méthode getSuggest de Article
        $this->assertGreaterThan(0, count($result)); // vérifie que le nombre de résultats est supérieur à 0
        $result = $result[0]; // récupère le premier élément du tableau
        $keys = array_keys($result); // récupère les clés du premier élément du tableau
        $this->assertSame(["id", "name", "description", "published_date", "user_id", "views", "picture", "username", "email", "password", "salt", "is_admin"], $keys); // vérifie que les clés correspondent à celles attendues
    }



    
    /** @test */
    public function saveTest() {
        $data = [ // Données de l'article
            'name' => 'Test Article', 
            'description' => 'This is a test description',
            'user_id' => 1, 
            'published_date' => (new DateTime())->format('Y-m-d')
        ];
        $articleId = Articles::save($data); // Sauvegarder l'article dans la base de données
        $this->assertIsNumeric($articleId, "L'ID de l'article retourné doit être numérique."); // Vérifier si l'ID de l'article retourné est numérique
        $db = Articles::fetchTestDatabaseConnection(); // Récupérer la connexion à la base de données
        $stmt = $db->prepare("SELECT * FROM articles WHERE id = :id"); // Préparer une requête pour récupérer l'article sauvegardé
        $stmt->bindParam(':id', $articleId); // Lier l'ID de l'article à la requête
        $stmt->execute(); // Exécuter la requête
        $result = $stmt->fetch(); // Récupérer le résultat de la requête
        $this->assertNotEmpty($result, "L'article doit être inséré dans la base de données."); // Vérifier si l'article a été inséré dans la base de données
        $this->assertEquals($data['name'], $result['name'], "Le nom de l'article sauvegardé ne correspond pas."); // Vérifier si le nom de l'article sauvegardé correspond
    }



    /** @test */
    // Test pour la méthode attachPicture
    public function testAttachPicture() {
        $articleData = [ // Données de l'article
            'name' => 'Test Article for Picture',
            'description' => 'Test Description',
            'user_id' => 1, 
            'published_date' => (new DateTime())->format('Y-m-d')
        ];
        $articleId = Articles::save($articleData); // Sauvegarder l'article dans la base de données
        $pictureName = 'test_picture_2' . time() . '.jpg'; // Création d'un nom de fichier unique pour la photo
    
       
        try {
            Articles::attachPicture($articleId, $pictureName); // Attacher la photo à l'article
            $updatedArticle = Articles::getOne($articleId); // Récupérer l'article mis à jour
            $this->assertEquals($pictureName, $updatedArticle[0]['picture'], "The picture name should match the one that was attached."); // Vérifier si le nom de la photo correspond à celui qui a été attaché
        } catch (Exception $e) {
            $this->fail("Failed to attach picture: " . $e->getMessage()); // Afficher un message d'erreur si l'attachement de la photo a échoué
        }
        // Nettoyage de la base de données de l'article créé pour les test à rajouter //
    }



}