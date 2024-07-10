<?php

namespace App\Models;

use App\Utility\Hash;
use Core\Model;
use App\Core;
use Exception;
use App\Utility;

/**
 * User Model:
 */
class User extends Model {


     /**
     * ??
     * @access public
     * @return string|boolean
     * @throws Exception
     */
    public static function fetchTestDatabaseConnection() {
        // Rajout de l'accès à la base de données pour ArticleTest //
        return static::getDB();
    }

    /**
     * Crée un utilisateur
     */
    public static function createUser($data) {
        $db = static::getDB();

        $stmt = $db->prepare('INSERT INTO users(username, email, password, salt) VALUES (:username, :email, :password,:salt)');

        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':password', $data['password']);
        $stmt->bindParam(':salt', $data['salt']);

        $stmt->execute();

        return $db->lastInsertId();
    }

    public static function getByLogin($login)
    {
        $db = static::getDB();

        $stmt = $db->prepare("
            SELECT * FROM users WHERE ( users.email = :email) LIMIT 1
        ");

        $stmt->bindParam(':email', $login);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }


 /**
     * Login user by ID
     * @param int $id
     * @return array|boolean
     * @throws Exception
     */
    public static function login($id) {
        $db = static::getDB();
        $stmt = $db->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
        $stmt->execute([$id]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Créé un nouveau mot de passe
     * @access public
     * @return string
     * @throws Exception
     */
    public static function resetPassword($email){
        $db = static::getDB();
        $user = static::getByLogin($email);

        $password = Hash::generateUnique();
        $hashed = Hash::generate($password, $user['salt']);
        $stmt = $db->prepare('UPDATE users SET password=? WHERE email=?');

        $stmt->execute(  [$hashed, $email]  );

        return $password;

    }


}
