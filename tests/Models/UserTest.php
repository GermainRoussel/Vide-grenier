<?php
use PHPUnit\Framework\TestCase;
use App\Models\User;
use DateTime;

/**
 * UserTest Model
 */
final class UserTest extends TestCase {

    /** @test */
    public function createUserTest(): void {
        $userData = [
            'username' => 'testuser',
            'email' => 'testuser@example.com',
            'password' => 'hashedpassword',
            'salt' => 'randomsalt'
        ];
        $userId = User::createUser($userData);
        $this->assertIsNumeric($userId, "L'ID de l'utilisateur retourné doit être numérique.");

        $db = User::fetchTestDatabaseConnection();
        $stmt = $db->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(':id', $userId);
        $stmt->execute();
        $result = $stmt->fetch();
        $this->assertNotEmpty($result, "L'utilisateur doit être inséré dans la base de données.");
        $this->assertEquals($userData['username'], $result['username'], "Le nom d'utilisateur sauvegardé ne correspond pas.");
    }

    /** @test */
    public function getByLoginTest(): void {
        $login = 'testuser@example.com';
        $result = User::getByLogin($login);
        $this->assertNotEmpty($result, "L'utilisateur doit être trouvé par login.");
        $this->assertEquals($login, $result['email'], "L'email de l'utilisateur trouvé ne correspond pas.");
    }

 /** @test */
public function loginTest(): void {
    $id = 1; // Assuming a user with ID 1 exists
    $result = User::login($id);
    $this->assertNotEmpty($result, "L'utilisateur doit être trouvé par ID.");
    $this->assertSame(["id", "username", "email", "password", "salt", "is_admin"], array_keys($result[0]), "Les clés de l'utilisateur trouvé ne correspondent pas.");
}
}