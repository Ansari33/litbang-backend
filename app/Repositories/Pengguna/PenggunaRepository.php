<?php

namespace App\Repositories\Pengguna;

use App\Repositories\BaseRepository;
use Validator;

use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Passport\Client as OClient;
use GuzzleHttp\Exception\ClientException;
use App\Repositories\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Http;


class PenggunaRepository extends BaseRepository
{

    public function validate($request)
    {
        $validator = Validator::make($request->only(
            'email',
            'password',
            'username',
            'full_name',
            'status'
        ), [
                'email' => 'required|unique:acc_users,email,NULL,id,deleted_at,NULL',
                'password' => 'required',
                'username' => 'required|unique:acc_users,username,NULL,id,deleted_at,NULL',
                'full_name' => 'required',
                'status' => 'required'
            ]
        );
        return $validator;
    }

    public function validate_update($request)
    {
        $validator = Validator::make($request->only(
            'email',
            'username',
            'full_name',
            'avatar'
        ), [
                #'email' => 'required|unique:acc_users,email,'.$request->id.',id,deleted_at,NULL',
                'email' => 'required',
                #'username' => 'required|unique:acc_users,username,'.$request->id.',id,deleted_at,NULL',
                'username' => 'required',
                'full_name' => 'required'
            ]
        );
        return $validator;
    }

    public function validate_password($request)
    {
        $validator = Validator::make($request->only(
            'password'
        ), [
                'password' => 'required',
            ]
        );
        return $validator;
    }


    const SUCCUSUS_STATUS_CODE = 200;
    const UNAUTHORISED_STATUS_CODE = 401;
    const BASE_URL = "http://localhost:8080";

    public function register(Request $request) {
        $email = $request->email;
        $password = $request->password;
        $input = $request->all();
        $input['password'] = bcrypt($input['password']);
        User::create($input);
        $response = $this->getTokenAndRefreshToken($email, $password);
        return $this->response(json_decode($response->getContent(), true), 200);
    }

    public function login(Request $request) {
        $email = $request->email;
        $password = $request->password;

        if (Auth::attempt(['email' => $email, 'password' => $password])) {
            $response = $this->getTokenAndRefreshToken($email, $password);
            $data = json_decode($response->getContent());
            $data->user = Auth::user();
            $statusCode =  self::SUCCUSUS_STATUS_CODE;
        } else {
            $data = new Request(['access_token' => null]);
            $statusCode =  self::UNAUTHORISED_STATUS_CODE;
        }
        return $data;
        return $this->response($data, $statusCode);
    }

    public function refreshToken(Request $request) {
        if (is_null($request->header('Refreshtoken'))) {
            return $this->response(['error'=>'Unauthorised'], self::UNAUTHORISED_STATUS_CODE);
        }

        $refresh_token = $request->header('Refreshtoken');
        $Oclient = $this->getOClient();
        $formParams = [ 'grant_type' => 'refresh_token',
            'refresh_token' => $refresh_token,
            'client_id' => $Oclient->id,
            'client_secret' => $Oclient->secret,
            'scope' => '*'];
        $request = Request::create('/oauth/token', 'POST', $formParams);
        return json_decode(app()->handle($request)->getContent(),true);
        return $this->sendRequest("/oauth/token", $formParams);
    }

    public function details() {
        $user = Auth::user();
        return $this->response($user, self::SUCCUSUS_STATUS_CODE);
    }

    public function logout(Request $request) {
        $request->user()->token()->revoke();
        return $this->response(['message' => 'Successfully logged out'], self::SUCCUSUS_STATUS_CODE);
    }

    public function response($data, int $statusCode) {
        $response = ["data"=>$data, "statusCode"=>$statusCode];
        return $response;
    }

    public function getTokenAndRefreshToken(string $email, string $password) {
        //eturn 'satu';
        $Oclient = $this->getOClient();
        $formParams = [ 'grant_type' => 'password',
            'client_id' => $Oclient->id,
            'client_secret' => $Oclient->secret,
            'username' => $email,
            'password' => $password,
            'scope' => '*'
        ];

        $request = Request::create('/oauth/token', 'POST', $formParams);
        return app()->handle($request);
        //return $this->sendRequest("/oauth/token", $formParams);
    }

    public function sendRequest(string $route, array $formParams) {
        try {
            $url = self::BASE_URL.$route;
            //return $url;
            $response = $this->http->request('POST', $url, ['form_params' => $formParams]);

            $statusCode = self::SUCCUSUS_STATUS_CODE;
            $data = json_decode((string) $response->getBody(), true);
        } catch (ClientException $e) {
            echo $e->getMessage();
            $statusCode = $e->getCode();
            $data = ['error'=>'OAuth client error'];
        }

        return ["data" => $data, "statusCode"=>$statusCode];
    }

    public function getOClient() {
        return OClient::where('password_client', 1)->first();
    }
}
