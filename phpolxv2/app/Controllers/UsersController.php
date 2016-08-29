<?php

namespace App\Controllers;

use App\Database\DatabaseInterface;
use App\Database\EloquentDatabase;
use App\Http\RequestInterface;
use App\Http\ResponseInterface;

class UsersController
{
    private $db;
    private $request;
    private $response;

    public function __construct(DatabaseInterface $db, RequestInterface $request, ResponseInterface $response)
    {
        $this->db = $db;
        $this->request = $request;
        $this->response = $response;
    }

    public function index()
    {
        $users = $this->db->table('users')->get();

        $this->sendJsonResponse(
            $users->map(function ($user) {
                return $this->transformUser($user);
            })
        );
    }

    public function show($id)
    {
        $user = $this->db->table('users')->find($id);

        if (! $user) {
            $this->sendJsonResponse([
                'error' => 'The user cant be found.'
            ]);
        };

        $this->sendJsonResponse(
            $this->transformUser($user)
        );
    }

    public function store()
    {
        $name = $this->request->request->get('name');
        $address = $this->request->request->get('address');
        $picture = $this->request->files->get('picture');

        $id = $this->db->table('users')->insertGetId([
            'name' => $name,
            'address' => $address,
            'picture' => $this->uploadPictureToOlx($picture),
        ]);

        $this->show($id);
    }

    public function update($id)
    {
        $user = $this->db->table('users')->find($id);

        if (! $user) {
            $this->sendJsonResponse([
                'error' => 'The user cant be found.'
            ]);
        };

        $name = $this->request->request->get('name');
        $address = $this->request->request->get('address');
        $picture = $this->request->files->get('picture');

        $dataToUpdate = [];

        if (isset($name)) {
            $dataToUpdate['name'] = $name;
        }

        if (isset($address)) {
            $dataToUpdate['address'] = $address;
        }

        if (isset($picture)) {
            $dataToUpdate['picture'] = $this->uploadPictureToOlx($picture);
        }

        $this->db->table('users')
            ->where('id', $user->id)
            ->update($dataToUpdate);

        $this->show($user->id);
    }

    private function uploadPictureToOlx($picture)
    {
        $folder = './' . getenv('IMAGE_FOLDER') . '/';

        $fileName = md5(uniqid()) . '.' . $picture->guessExtension();
        $picture->move(
                $folder,
                $fileName
            );

        $client = new \GuzzleHttp\Client();
        $response = $client->request('POST', getenv('OLX_UPLOAD_IMAGE_URL'), [
            'multipart' => [
                [
                    'name' => 'picture',
                    'contents' => fopen($folder . $fileName, 'r'),
                ],
            ],
        ]);
        $responseBody = json_decode($response->getBody(), true);

        unlink($folder . $fileName);

        return $responseBody['url'];
    }

    private function transformUser($user)
    {
        return [
            'id' => $user->id,
            'name' => $user->name,
            'address' => $user->address,
            'picture' => getenv('OLX_IMAGE_URL') . $user->picture,
        ];
    }

    private function sendJsonResponse($responseData)
    {
        $this->response->setContent(json_encode($responseData));
        $this->response->headers->set('Content-Type', 'application/json');
        $this->response->prepare($this->request);
        $this->response->send();
    }
}
