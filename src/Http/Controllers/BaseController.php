<?php

namespace Continuum\Support\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BaseController extends Controller
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
    protected function respondWithJson($data, $status = 200, $headers = [])
    {
        return response()->json($data, $status, $headers);
    }

    /**
     * Append JS vars to header.
     *
     * @return Illuminate\View\View
     */
    protected function respondWithView($view, array $attrs = [])
    {
        return view()->make($view, $attrs);
    }
}
