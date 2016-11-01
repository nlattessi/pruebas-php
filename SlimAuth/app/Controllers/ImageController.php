<?php

namespace App\Controllers;

use App\Models\Image;
use Slim\Views\Twig as View;

class ImageController extends Controller
{
    public function index($request, $response) 
    {
        $images = Image::all();
        return $this->view->render($response, 'images/index.twig', [
            'images' => $images,
        ]);
    }

    public function getUpload($request, $response)
    {
        return $this->view->render($response, 'images/upload.twig');
    }

    public function postUpload($request, $response)
    {
        $files = $request->getUploadedFiles();

        if (empty($files['newFile'])) {
            throw new Exception('Expected a newFile');
        }

        $newfile = $files['newFile'];

        if ($newfile->getError() === UPLOAD_ERR_OK) {
            $filename = $this->getFilename($newfile);
            $filepath = $this->getFilepath($filename);
            $newfile->moveTo($filepath);

            $user = Image::create([
                'name' => $request->getParam('name', null),
                'filename' => $filename,
            ]);
            
            $this->flash->addMessage('info', 'Your file has been uploaded!');
        } else {
            $this->flash->addMessage('error', 'The file can not be uploaded...!');    
        }

        return $response->withRedirect($this->router->pathFor('image.index'));
    }

    private function getFilename($file)
    {
        $timestamp = (new \DateTime)->getTimeStamp();
        return "{$timestamp}_{$file->getClientFilename()}";
    }

    private function getFilepath($filename)
    {
        $uploadPath = $this->container['settings']['files']['uploadPath'];
        return "{$uploadPath}/{$filename}";
    }
}
