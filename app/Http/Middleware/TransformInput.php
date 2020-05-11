<?php

namespace App\Http\Middleware;

use Closure;
use Dotenv\Exception\ValidationException;

class TransformInput
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $transformer)
    {
        $transformedInput = [];

        foreach ($request->request->all() as $input => $value) {
            $transformedInput[$transformer::originalAttribute($input)] = $value;
        }

        $request->replace($transformedInput);

        $repsonse = $next($request);

        if (isset($repsonse->exception) && $repsonse->exception instanceof ValidationException) {
            $data = $repsonse->getData();

            $transformedErrors = [];

            foreach ($data->error as $field => $error) {
                
                $transformedFields = $transformer::transformedAttributes($field);

                $transformedErrors[$transformedFields] = str_replace($field, $transformedFields, $error);
            }

            $data->error = $transformedErrors;

            $repsonse->setData($data);
        }

        return $repsonse;
    }
}
