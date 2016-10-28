<?php

namespace App\Controllers;

use App\Models\User;
use Slim\Views\Twig as View;

class ImageController extends Controller
{
    public function index($request, $response) 
    {
        return $this->view->render($response, 'images/index.twig');
    }

    public function upload($request, $response)
    {
        $files = $request->getUploadedFiles();

        if (empty($files['newFile'])) {
            throw new Exception('Expected a newFile');
        }

        $newfile = $files['newFile'];

        if ($newfile->getError() === UPLOAD_ERR_OK) {
            $path = $this->container['settings']['files']['uploadPath'];
            $timestamp = (new \DateTime)->getTimeStamp();
            $uploadFileName = $newfile->getClientFilename();
            $newfile->moveTo("{$path}/{$timestamp}_{$uploadFileName}");
            $this->flash->addMessage('info', 'Your file has been uploaded!');
            
        } else {
            $this->flash->addMessage('error', 'The file can not be uploaded...!');    
        }

        return $response->withRedirect($this->router->pathFor('image.index'));
    }
}
