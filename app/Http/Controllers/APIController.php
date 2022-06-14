<?php
namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Helpers\MessageConstant;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Monolog\Formatter\LineFormatter;

abstract class APIController extends Controller{

    private $response;
    private $logResponse;
    /**
     * To be overriden
     *
     * @param
     * @return
     */
    abstract public function initialize();

    public function __construct()
    {
      $this->initialize();
      $this->logResponse = new Logger('API-Response'); 
      $handler = new StreamHandler(base_path() . '/storage/logs/api-response.log'); 
      $handler->setFormatter(new LineFormatter("[%datetime%] %channel%.%level_name%: %message% %context%\n")); 
      $this->logResponse->pushHandler($handler);
    }

    public function respond($data, $message = null, $status_code = 200) {
      $response['status'] = true;
      $response['status_code'] = $status_code;
      $response['message'] = $message;

      if (isset($data))
        $response['data'] = $data;

     // $this->logResponse->addInfo('Response', $response);
     // return $this->response->array($response)->setStatusCode($status_code);
      return $response;
    }

    public function respondWithError($message, $status_code = 400, $errors = null) {
      $response['status'] = false;
      $response['status_code'] = $status_code;
      $response['message'] = $message;

      if (isset($errors))
        $response['errors']['details'] = $errors;

      return $response;
      $this->logResponse->addInfo('Response', $response);
      return $this->response->array($response)->setStatusCode($status_code);
    }

    public function respondCreated($data, $message = MessageConstant::RESPONSE_201) {
      return $this->respond($data, $message, 201);
    }

    public function respondOk($message = MessageConstant::RESPONSE_200) {
      return $this->respond(null, $message, 200);
    }

    public function respondUnauthorized($errors, $message = MessageConstant::RESPONSE_401) {
      return $this->respondWithError($message, 401, $errors);
    }

    public function respondNotFound($errors = null, $message = MessageConstant::RESPONSE_404) {
      return $this->respondWithError($message, 404, $errors);
    }

    public function respondInternalError($errors = null, $message = MessageConstant::RESPONSE_500) {
      return $this->respondWithError($message, 500, $errors);
    }

    public function respondConflict($errors = null, $message = MessageConstant::RESPONSE_409){
      return $this->respondWithError($message, 409, $errors);
    }

    public function respondWithValidationErrors($errors = null, $message = MessageConstant::RESPONSE_400) {
      return $this->respondWithError($message, 400, $errors);
    }

}
