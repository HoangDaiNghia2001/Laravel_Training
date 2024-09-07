<?php

namespace App\Traits;

use App\Response\ApiResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

trait MethodTrait {
    //SUCCES
    public function buildApiResponse(bool $success, string $message, $data, int $statusCode) {
        $response = new ApiResponse();
        $response->setSuccess($success);
        $response->setMessage($message);
        $response->setData($data);

        return response()->json($response->toArray(), $statusCode);
    }

    //SUCCES
    public function buildErrorResponse(string $message, int $statusCode) {
        $response = new ApiResponse();
        $response->setSuccess(false);
        $response->setMessage($message);
        $response->setData($statusCode);

        return response()->json($response->toArray(), $statusCode);
    }

    //SUCCES
    public function handleTransaction(callable $callback) {
        try {
            DB::beginTransaction();
            $response = $callback();
            DB::commit();

            return $response;
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->buildErrorResponse($e->getMessage(), $e->getCode());
        }
    }

    public function SnakeCase(array $array): array {
        $snakeCase = [];
        foreach ($array as $key => $value) {
            $snakeCase[Str::snake($key)] = $value;
        }
        return $snakeCase;
    }
}
