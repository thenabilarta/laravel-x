<?php

namespace App\Http\Controllers;

use App\Xibo;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class XiboController extends Controller
{
    public $access_token = 'NE3OH3Nrt9oDImhTMcDKvhQSgnVM9X0gs8ihjGQ7';

    public function index()
    {
        $xibo = Xibo::all();

        return view('xibo.index', compact('xibo'));
    }

    public function media()
    {
        $client = new Client(['base_uri' => 'http://192.168.44.127']);

        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token,
            'Accept' => 'application/json'
        ];

        $response = $client->request('GET', '/xibo-cms/web/api/library?length=20', [
            'headers' => $headers
        ]);

        $contents = $response->getBody();
        $content = json_decode($contents, true);
    }

    public function image()
    {
        $client = new Client(['base_uri' => 'http://192.168.44.127']);

        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token,
        ];

        $response = $client->request('GET', '/xibo-cms/web/api/library/download/19/image', [
            'headers' => $headers
        ]);

        $image = $response->getBody()->getContents();

        $form_data = array(
            'name' => 'Jambu',
            'image' => $image
        );

        Xibo::create($form_data);

        echo '<img src="data:image/jpeg;base64,' . base64_encode($image) . '"/>';
    }

    public function store(Request $request)
    {
        $files = $request->file('file');

        if($request->hasFile('file'))
        {
            foreach ($files as $file) {
                // $imagePath = $file->store('users/' . $this->user->id . '/messages');
                $fileName = $file->getClientOriginalName();
                $imagePath = $file->store('uploads', 'public');
                // dd($imagePath);
                $imageName = explode("/", $imagePath);

                $form_data = array(
                    'image_database_name' => $imageName[1],
                    'image_name' => $fileName,
                    'image' => $imagePath,
                    'media_id' => ''
                );

                $client = new Client(['base_uri' => 'http://192.168.44.127']);

                $headers = [
                    'Authorization' => 'Bearer ' . $this->access_token,
                    'Accept' => 'application/json'
                ];

                $multipart = [
                    [
                        'name' => 'name',
                        'contents' => $fileName
                    ],
                    [
                        'name' => 'files',
                        'contents' => fopen('C:/Users/thena/Desktop/laravel-blob/storage/app/public/' . $imagePath, 'r')
                    ]
                ];

                $response = $client->request('POST', '/xibo-cms/web/api/library', [
                    'headers' => $headers,
                    'multipart' => $multipart
                ]);

                $contents = $response->getBody();
                $content = json_decode($contents, true);
                
                if ( !isset($content["files"][0]["error"])) {
                    $xibo = Xibo::create($form_data);

                    $xibos = $xibo->getAttributes();

                    $xibomediaid = Xibo::find($xibos["id"]);

                    if($xibomediaid) {
                        $xibomediaid->media_id = $content["files"][0]["mediaId"];
                        $xibomediaid->save();
                    }
                }
            }
        }

        // $file = $request->files->get('file');

        // $fileName = $file->getClientOriginalName();

        // $imagePath = request('file')->store('uploads', 'public');

        // $imageName = explode("/", $imagePath);

        // $form_data = array(
        //     'image_database_name' => $imageName[1],
        //     'image_name' => $fileName,
        //     'image' => $imagePath,
        //     'media_id' => ''
        // );

        // $xibo = Xibo::create($form_data);

        // $xibos = $xibo->getAttributes();

        // dd($xibos["id"]);

        // $fileBlob = file_get_contents($file->getRealPath());

        // dd($fileBlob);

        // echo '<img src="data:image/jpeg;base64,' . base64_encode($fileBlob) . '"/>';

        // $image = 'data:image/jpeg;base64, ' . base64_encode($fileBlob);

        // dd($image);

        // $client = new Client(['base_uri' => 'http://192.168.44.127']);

        // $headers = [
        //     'Authorization' => 'Bearer ' . $this->access_token,
        //     'Accept' => 'application/json'
        // ];

        // $multipart = [
        //     [
        //         'name' => 'name',
        //         'contents' => $fileName
        //     ],
        //     [
        //         'name' => 'files',
        //         'contents' => fopen('C:/Users/thena/Desktop/laravel-blob/storage/app/public/' . $imagePath, 'r')
        //     ]
        // ];

        // $response = $client->request('POST', '/xibo-cms/web/api/library', [
        //     'headers' => $headers,
        //     'multipart' => $multipart
        // ]);

        // $contents = $response->getBody();
        // $content = json_decode($contents, true);

        // dd($content["files"][0]["mediaId"]);

        // $xibomediaid = Xibo::find($xibos["id"]);

        // Make sure you've got the Page model
        // if($xibomediaid) {
        //     $xibomediaid->media_id = $content["files"][0]["mediaId"];
        //     $xibomediaid->save();
        // }

        return redirect('/');
    }

    public function edit($id)
    {
        $xibo = Xibo::findOrFail($id);

        return view('xibo.edit', compact('xibo'));
    }

    public function editstore(Request $request)
    {
        $xiboimagename = Xibo::find($request->id);

        // Make sure you've got the model
        if($xiboimagename) {
            $xiboimagename->image_name = $request->image_name . '.' . $request->image_type;
            $xiboimagename->save();
        }

        $client = new Client(['base_uri' => 'http://192.168.44.127']);

        $headers = [
            'Authorization' => 'Bearer ' . $this->access_token,
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];

        $formparams = [
            'name' => $request->image_name . '.' . $request->image_type,
            'duration' => '10',
            'retired' => '0',
            'tags' => '0',
            'updateInLayouts' => '0'
        ];

        $response = $client->request('PUT', '/xibo-cms/web/api/library/' . $request->media_id , [
            'headers' => $headers,
            'form_params' => $formparams
        ]);

        return redirect('/');
    }

    public function delete($id)
    {
        $xibo = Xibo::findOrFail($id);

        $xibo->delete();

        $client = new Client();

        $url = 'http://192.168.44.127/xibo-cms/web/api/library/' . $xibo->media_id;

        $response = $client->delete($url, [
            'headers'  => [
                'Authorization' => 'Bearer ' . $this->access_token,
                'Accept' => 'application/json'
            ]
        ]);

        return redirect('/');
    }
}