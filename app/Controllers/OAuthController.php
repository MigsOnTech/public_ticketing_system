<?php

namespace App\Controllers;

use stdClass;
use App\Models\User;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use CodeIgniter\HTTP\Response;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\RESTful\ResourceController;

class OAuthController extends ResourceController
{
    use ResponseTrait;
    public function index()
    {
        $key = getenv('TOKEN_SECRET');
        $header = $this->request->getServer('HTTP_AUTHORIZATION');
        if(!$header) return $this->failUnauthorized('Token Required');
        $token = explode(' ', $header)[1];
        try {
            $decoded = JWT::decode($token, new Key($key, 'HS256'));
            $response = [
                'user_id' => $decoded->user_id,
                'email' => $decoded->email
            ];
            return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($decoded);
        } catch (\Throwable $th) {
            return $this->fail($th);
        }
    }

    public function register()
    {
        // helper(['form']);
        $rules = [
            'fullname'          => 'required|min_length[2]|max_length[50]',
            'username'          => 'required|min_length[2]|max_length[50]',
            'email'             => 'required|min_length[4]|max_length[100]|valid_email|is_unique[user_account.email]',
            'password'          => 'required|min_length[4]|max_length[50]'
        ];
          
        if($this->validate($rules)){
            // $userModel = new UserModel();
            $user = new \App\Models\User();

            $data = array(
                'fullname'     => $this->request->getVar('fullname'),
                'username'     => $this->request->getVar('username'),
                'email'    => $this->request->getVar('email'),
                'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
                "role" => "USER"
            );
            if($user->insert($data)){
                $response = array(
                    'status' => true,
                    'message' => 'Account Registered Successfully'
                );
                return $this->response->setStatusCode(Response::HTTP_CREATED)->setJSON($response);
            }else{
                $response = array(
                    'status' => false,
                    'message' =>$user->errors()
                );
                return $this->response->setStatusCode(Response::HTTP_CREATED)->setJSON($response);
            }
           
            // return redirect()->to('/signin');
        }else{
            $data['validation'] = $this->validator;
            $response = array(
                'status' => false,
                'message' => $data
            );
            return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($response);

        }
    }

    // Post
    public function login()
    {
        $session = session();
        $user = new \App\Models\User();
        $email = $this->request->getVar('email');
        $password = $this->request->getVar('password');
        
        $data = $user->where('email', $email)->first();
        if($data){
            $pass = $data['password'];
            $authenticatePassword = password_verify($password, $pass);
            if($authenticatePassword){
                $key = getenv('TOKEN_SECRET');
                $payload = array(
                    "user_id" => $data['user_id'],
                    "email" => $data['email'],
                    "role"  => $data["role"],
                    "fullname"  => $data["fullname"]
                );
        
                $token = JWT::encode($payload, $key, 'HS256');

                $response = array(
                    'status' => true,
                    'message' =>"User logged in successfully",
                    "token" => $token,
                    "role"  => $data["role"],
                    "fullname"  => $data["fullname"]
                );
                
 
                return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($response);
            }else{
                $response = array(
                    'status' => false,
                    'message' =>"Invalid Email or Password",
                );
                return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($response);
            }
        }else{
            $response = array(
                'status' => false,
                'message' =>"Invalid Email or Password",
            );
            return $this->response->setStatusCode(Response::HTTP_OK)->setJSON($response);
        }
    
    }

    public function oAuth(){
        
    }
}
