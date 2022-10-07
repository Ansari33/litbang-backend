<?php

namespace App\Http\Controllers\Pengguna;

use App\Helpers\MessageConstant;
use App\Http\Controllers\APIController;
use App\Models\Pengguna\Pengguna;
use Hash;
use Illuminate\Http\Request;
//use JWTAuth;
use Response;
use Str;
use DB;
use Auth;

class PenggunaController extends APIController
{

    private $PenggunaRepository;
    private $MenuPenggunaRepository;
    private $RoutesUserRepository;
    private $RoutesRepository;
    private $AksesAndroidRepository;

    public function initialize()
    {
//        $this->ViewRoutesUserRepository = \App::make('\App\Repositories\Contracts\Pengaturan\ViewRoutesUserInterface');
//        $this->RoutesUserRepository = \App::make('\App\Repositories\Contracts\Pengaturan\RoutesUserInterface');
//        $this->RoutesRepository = \App::make('\App\Repositories\Contracts\Pengaturan\RoutesInterface');
        $this->PenggunaRepository = \App::make('\App\Repositories\Contracts\Pengguna\PenggunaInterface');
        $this->AksesAndroidRepository = \App::make('\App\Repositories\Contracts\Litbang\AksesAndroidInterface');
//        $this->MenuPenggunaRepository = \App::make('\App\Repositories\Contracts\Pengguna\MenuPenggunaInterface');
    }

    public function list()
    {
        $result = $this->PenggunaRepository->all();
        return $this->respond($result);
    }

    public function getById(Request $request)
    {
        $result = $this->PenggunaRepository->where('id',$request->id)->first();
        if ($result) {
            return $this->respond($result);
        } else {
            return $this->respondNotFound('Id Pengguna Tidak Ditemukan!');
        }
    }

    public function routeAvailableCheck(Request $request)
    {
        $result = $this->ViewRoutesUserRepository->where('id_pengguna',$request->id_pengguna)->where('route',$request->route)->first();
        if ($result) {
            return $this->respond($result);
        } else {
            return $this->respondUnauthorized('Id Pengguna Tidak Ditemukan!');
        }
    }

    public function create(Request $request)
    {
        $validator = $this->PenggunaRepository->validate($request);
        if ($validator->fails()) {
            return $this->respondWithValidationErrors($validator->errors()->all(), MessageConstant::VALIDATION_FAILED_MSG);
        } else {
            DB::beginTransaction();
            $data = [
                'uuid' => Str::random(4).'-'.Str::random(4).'-'.Str::random(4).'-'.Str::random(4),
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'username' => $request->username,
                'full_name' => $request->full_name,
                'status' => $request->status,
                'avatar' => '',
                'ip_address' => request()->ip()
            ];
            $result = $this->PenggunaRepository->create($data);
            if ($result->count()) {
                $all_routes = $this->RoutesRepository->all();

                foreach ($all_routes as $key => $route) {
                    $add_akses = $this->RoutesUserRepository->create([
                        'user_id'  => $result->id,
                        'route_id' => $route['id']
                    ]);
                }
                DB::commit();
                return $this->respondCreated($result, 'Pengguna Berhasil Ditambah!');
            } else {
                DB::rollback();
                return $this->respondConflict();
            }
        }
    }

    public function update(Request $request)
    {
        $validator = $this->PenggunaRepository->validate_update($request);
        if ($validator->fails()) {
            return $this->respondWithValidationErrors($validator->errors()->all(), MessageConstant::VALIDATION_FAILED_MSG);
        } else {
            $result = $this->PenggunaRepository->find($request->id);
            if ($result) {
                $result->email = $request->input('email');
                $result->username = $request->input('username');
                $result->full_name = $request->input('full_name');
                $result->status = $request->input('status');
                $result->avatar = $request->input('avatar');
                if ($request->input('password') != '' or $request->input('password') != null) {
                    $result->password = Hash::make($request->input('password'));
                }
                $result->save();
                return $this->respond($result, 'Pengguna Berhasil Diupdate!');
            } else {
                return $this->respondNotFound();
            }
        }
    }

    public function updateAkses(Request $request)
    {	DB::beginTransaction();
        //return $this->respondOk($request->akses);
        if (count($request->akses) > 0) {
            $this->RoutesUserRepository->where('user_id',$request->id)->delete();
            foreach ($request->akses as $key => $value) {
                $data = ['user_id' => $request->id,'route_id' => $value];
                $valid_data = new Request($data);
                $validator = $this->RoutesUserRepository->validate($valid_data);
                if ($validator->fails()) {
                    return $this->respondWithValidationErrors($validator->errors()->all(), MessageConstant::VALIDATION_FAILED_MSG);
                }else{

                    $result = $this->RoutesUserRepository->create($data);
                }
            }
            if ($result) {
                DB::commit();
                return $this->respond($result, 'Hak Akses Tersimpan!');
            }else{
                DB::rollback();
                return $this->respond($result, 'Hak Akses Tersimpan!');
            }
        }
    }


    public function updatePassword(Request $request)
    {
        $validator = $this->PenggunaRepository->validate_password($request);
        if ($validator->fails()) {
            return $this->respondWithValidationErrors($validator->errors()->all(), MessageConstant::VALIDATION_FAILED_MSG);
        } else {
            $result = $this->PenggunaRepository->find($request->id);
            if ($result) {
                $result->password = Hash::make($request->password);
                $result->save();
                return $this->respond($result, 'Update Password Berhasil!');
            } else {
                return $this->respondNotFound();
            }
        }
    }

    public function delete(Request $request)
    {
        $result = $this->PenggunaRepository->delete($request->id);
        if ($result) {
            $this->RoutesUserRepository->where('user_id',$request->id)->forceDelete();
            return $this->respondOk('Pengguna Berhasil Terhapus!');
        } else {
            return $this->respondNotFound('Pengguna Tidak Ditemukan!');
        }
    }

    public function login(Request $request) {
        $response = $this->PenggunaRepository->login($request);
        $result = $this->respond($response);
        if ($result['data']->access_token === null) {
            return $this->respondUnauthorized(MessageConstant::LOGIN_FAILED_MSG);
        }else{
            return $this->respond($response);
        }

        //return response()->json($response["data"], $response["statusCode"]);
    }

    public function register(UserRegisterRequest $request) {
        $response = $this->userRepository->register($request);
        return response()->json($response["data"], $response["statusCode"]);
    }

    public function details() {
        $response = $this->userRepository->details();
        return response()->json($response["data"], $response["statusCode"]);
    }

    public function logout(Request $request) {
        $response = $this->userRepository->logout($request);
        return response()->json($response["data"], $response["statusCode"]);
    }

    public function refreshToken(Request $request) {
        $response = $this->PenggunaRepository->refreshToken($request);
        $response['user'] = Auth::guard('api')->user();
        if (array_key_exists('access_token',$response)) {
            return $this->respond($response);
        }else{
            return $this->respondUnauthorized('Refresh Token Gagal');
        }

        //return response()->json($response["data"], $response["statusCode"]);
    }

    public function cekAksesAndroid (Request $request){
        $result = $this->AksesAndroidRepository
            ->where('user_id',$request->user_id)
            ->where('menu_akses',$request->menu)
            ->first();
        if ($result) {
            return $this->respond($result);
        }else{
            return $this->respondUnauthorized('Anda Tidak Memiliki Akses');
        }
    }
}
