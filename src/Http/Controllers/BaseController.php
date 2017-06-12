<?php

namespace Continuum\Support\Http\Controllers;

use Exception;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Continuum\Support\Eloquent\Paginate;
use Continuum\Support\Transformer\AbstractTransformer;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * Throw a 404 error.
     *
     * @return Response
     */
    public function throw404(Request $request, $message = null)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'message' => $message ? $message : 'Not found'
            ], 404);
        }

        throw new NotFoundHttpException;
    }

    /**
     * Return the current presented user.
     *
     * @return string
     */
    protected function trans($key, $attrs = [])
    {
        return trans($key, $attrs);
    }

    /**
     * Return a JSON response.
     *
     * @return Illuminate\Http\Response
     */
    protected function respondWithJson($data, $status = 200, $headers = []): Response
    {
        return response()->json($data, $status, $headers);
    }

    /**
     * Append JS vars to header.
     *
     * @return Illuminate\View\View
     */
    protected function respondWithView($view, array $attrs = []): View
    {
        return view()->make($view, $attrs);
    }
}