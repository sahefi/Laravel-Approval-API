<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;



class AuthController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('auth.index');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $username = $request->username;
        $password = $request->password;

        try {
            $client = new Client();
            $url = "http://localhost:8000/api/auth/login";
            $response = $client->request('POST', $url, [
                'headers' => ['Authorization' => 'Bearer ' . session()->get('token.acces_token')],
                'json' => [
                    'username' => $username,
                    'password' => $password,
                ],
            ]);

            $content = $response->getBody()->getContents();
            $contentArray = json_decode($content, true);

            return redirect()->to('dashboard')->with('success',$contentArray['message']);

        } catch (\GuzzleHttp\Exception\ClientException $e) {
            // Tangani kesalahan dari respons 400 Bad Request
            $response = $e->getResponse();
            $errorContent = json_decode($response->getBody()->getContents(), true);

            if ($response->getStatusCode()!== 200) {
                // Kirim pesan kesalahan ke view
                return redirect()->to('auth/login')->withErrors($errorContent['error']);
            }
        }
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
