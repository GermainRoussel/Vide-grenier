<?php
use PHPUnit\Framework\TestCase;
use App\Models\User;

/**
 * UserTest Model
 */
final class UserTest extends TestCase {

    /** @test */
    public function createUserTest(): void {
        $userData = [  // Données de test
            'username' => 'testuser',
            'email' => 'testuser@example.com',
            'password' => 'hashedpassword',
            'salt' => 'randomsalt'
        ];
        $userId = User::createUser($userData); // Création de l'utilisateur
        $this->assertIsNumeric($userId, "L'ID de l'utilisateur retourné doit être numérique."); // Vérification de l'ID

        $db = User::fetchTestDatabaseConnection(); // Connexion à la base de données
        $stmt = $db->prepare("SELECT * FROM users WHERE id = :id"); // Préparation de la requête SQL
        $stmt->bindParam(':id', $userId); // Liaison de l'ID
        $stmt->execute(); // Exécution de la requête
        $result = $stmt->fetch(); // Récupération des données
        $this->assertNotEmpty($result, "L'utilisateur doit être inséré dans la base de données."); // Vérification de l'insertion
        $this->assertEquals($userData['username'], $result['username'], "Le nom d'utilisateur sauvegardé ne correspond pas."); // Vérification du nom d'utilisateur
    }

    /** @test */
    public function getByLoginTest(): void {
        $login = 'testuser@example.com'; // Email de l'utilisateur de test
        $result = User::getByLogin($login); // Récupération de l'utilisateur
        $this->assertNotEmpty($result, "L'utilisateur doit être trouvé par login."); // Vérification de l'utilisateur
        $this->assertEquals($login, $result['email'], "L'email de l'utilisateur trouvé ne correspond pas."); // Vérification de l'email
    }

 /** @test */
public function loginTest(): void {
    $id = 1; // ID de l'utilisateur de test
    $result = User::login($id); // Connexion de l'utilisateur
    $this->assertNotEmpty($result, "L'utilisateur doit être trouvé par ID."); // Vérification de l'utilisateur
    $this->assertSame(["id", "username", "email", "password", "salt", "is_admin"], array_keys($result[0]), "Les clés de l'utilisateur trouvé ne correspondent pas."); // Vérification des clés
}
}