<?php

namespace App\Controllers;

use App\Models\Articles;
use App\Utility\Email;
use App\Utility\Upload;
use \Core\View;

/**
 * Product controller
 */
class Product extends \Core\Controller
{

    /**
     * Affiche la page d'ajout
     * @return void
     */
    public function indexAction()
    {

        if(isset($_POST['submit'])) {

            try {
                $f = $_POST;

                // TODO: Validation

                $f['user_id'] = $_SESSION['user']['id'];
                $id = Articles::save($f);

                $pictureName = Upload::uploadFile($_FILES['picture'], $id);

                Articles::attachPicture($id, $pictureName);

                header('Location: /product/' . $id);
            } catch (\Exception $e){
                    var_dump($e);
            }
        }

        View::renderTemplate('Product/Add.html');
    }

    /**
     * Affiche la page d'un produit
     * @return void
     */
    public function showAction()
    {
        $id = $this->route_params['id'];

        try {
            Articles::addOneView($id);
            $suggestions = Articles::getSuggest();
            $article = Articles::getOne($id);
        } catch(\Exception $e){
            var_dump($e);
        }

        View::renderTemplate('Product/Show.html', [
            'article' => $article[0],
            'suggestions' => $suggestions
        ]);
    }


    
 /* 
* Affiche la page de recherche
*/
    public function sendEmailAction()
    {
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $data = json_decode(file_get_contents('php://input'), true);

                if (!isset($data['subject']) || !isset($data['content'])) {
                    throw new Exception('Subject and content are required.');
                }

                $subject = $data['subject'];
                $content = $data['content'];
                $email = $data['email'];

                // For debugging
                error_log('Subject: ' . $subject);
                error_log('Content: ' . $content);

                Email::sendMail($email, $content, $subject); // Change to your Yopmail address
                echo json_encode(['message' => 'Email sent successfully']);
            } else {
                throw new Exception('Invalid request method');
            }
        } catch (Exception $e) {
            error_log('Error: ' . $e->getMessage());
            echo json_encode(['message' => 'Failed to send email: ' . $e->getMessage()]);
            http_response_code(500);
        }
    }

}
