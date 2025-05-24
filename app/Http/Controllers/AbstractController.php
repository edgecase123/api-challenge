<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use JsonException;
use function json_encode;
use function response;

abstract class AbstractController extends Controller
{
    protected Request $request;

    /** @noinspection PhpUnused */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }


    protected function validateInput(array $rules, array $params = null): array
    {
        $params = $params ?? $this->request->all();
        $validator = Validator::make($params, $rules);

        if ($validator->fails()) {
            try {
                $input = 'input: ' . json_encode($params, JSON_THROW_ON_ERROR);
                $errors = json_encode($validator->errors(), JSON_THROW_ON_ERROR);
                Log::info("Request failed validation. Data: $input Errors: $errors", [__FUNCTION__]);
            } catch (JsonException $e) {
                Log::error('Error attempting to validate input', [
                    'params' => $params,
                    'errorMsg' => $e->getMessage(),
                ]);

                return [];
            }

            abort(400, __('Client error'));
        }

        return $params;
    }

    protected function jsonError(string $msg, int $code = 404): JsonResponse
    {
        $result = ['error' => $msg];

        return $this->jsonMessage($result, $code);
    }

    protected function jsonSuccess(string $msg = 'success', $code = 200): JsonResponse
    {
        return $this->jsonMessage($msg, $code);
    }

    protected function jsonMessage(mixed $data, int $code = 200): JsonResponse
    {
        return response()->json(['response' => $data], $code);
    }

    protected function jsonErrorMessage(mixed $data, int $code): JsonResponse
    {
        return response()->json(['error' => $data], $code);
    }
}
