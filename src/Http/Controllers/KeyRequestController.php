<?php

namespace Sribna\Licensor\Http\Controllers;

use Closure;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Sribna\Licensor\Models\Key;
use Sribna\Licensor\Models\Secret;
use Sribna\Licensor\Services\KeyService;
use Throwable;

/**
 * Class KeyRequestController
 * @package Sribna\Licensor\Http\Controllers
 */
class KeyRequestController
{

    /**
     * @var KeyService
     */
    protected $key;

    /**
     * KeyRequestController constructor.
     * @param KeyService $keyService
     */
    public function __construct(KeyService $keyService)
    {
        $this->key = $keyService;
    }

    /**
     * Route callback for key verification
     * @param Request $request
     * @return JsonResponse
     */
    public function check(Request $request)
    {
        return $this->process($request, function (Request $request) {
            $data = $this->key->validate($request);
            $this->key->verify(reset($data));
            return $data;
        });
    }

    /**
     * Route callback for key activation
     * @param Request $request
     * @return JsonResponse
     */
    public function activate(Request $request)
    {
        return $this->process($request, function (Request $request) {
            $data = $this->key->validate($request->getContent());
            $this->key->activate(reset($data));
            return $data;
        });
    }

    /**
     * Process a licensee's request
     * @param Request $request
     * @param Closure $function
     * @return JsonResponse
     */
    private function process(Request $request, Closure $function)
    {
        try {
            /** @var Key $key */
            /** @var Secret $secret */
            [$key, $secret] = $function($request);
        } catch (Exception $exception) {
            return $this->error($exception, 400);
        }

        try {
            $this->key->callback($key, $secret);
        } catch (Exception $exception) {
            return $this->error($exception, 500);
        }

        return response()->json(['success' => 'Response has been sent']);
    }

    /**
     * Log and output error
     * @param Throwable $exception
     * @param int $code
     * @return JsonResponse
     */
    private function error(Throwable $exception, int $code)
    {
        logger()->error($exception->getMessage());

        return response()->json([
            'error' => config('app.debug') ? $exception->getMessage() : 'An error occurred'
        ], $code);
    }
}
