<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HandlePutFormData
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Si c'est une requête PUT avec form-data
        if ($request->isMethod('PUT') && str_contains($request->header('Content-Type'), 'multipart/form-data')) {
            
            // Récupérer le contenu brut
            $content = $request->getContent();
            
            // Si le contenu est vide, essayer de récupérer depuis $_POST
            if (empty($content) && !empty($_POST)) {
                $request->merge($_POST);
            }
            
            // Log pour debug
            \Log::info('HandlePutFormData Middleware', [
                'method' => $request->method(),
                'content_type' => $request->header('Content-Type'),
                'content_length' => strlen($content),
                'post_data' => $_POST ?? [],
                'request_all' => $request->all(),
            ]);
        }
        
        return $next($request);
    }
} 