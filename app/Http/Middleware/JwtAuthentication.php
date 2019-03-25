<?php

namespace App\Http\Middleware;
use Illuminate\Contracts\Routing\ResponseFactory;
use Illuminate\Contracts\Events\Dispatcher;
use Closure;
use Tymon\JWTAuth\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;

class JwtAuthentication
{
    /**
     * @var \Illuminate\Contracts\Routing\ResponseFactory
     */
    protected $response;

    /**
     * @var \Illuminate\Contracts\Events\Dispatcher
     */
    protected $events;

    /**
     * @var \Tymon\JWTAuth\JWTAuth
     */
    protected $auth;

    /**
     * Create a new BaseMiddleware instance.
     * @param \Illuminate\Contracts\Events\Dispatcher  $events
     */
    public function __construct(ResponseFactory $response, Dispatcher $events, JWTAuth $auth)
    {
        $this->response = $response;
        $this->events = $events;
        $this->auth = $auth;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {

        if (! $token = $this->auth->setRequest($request)->getToken()) {
            return $this->respond('tymon.jwt.absent', 'token_not_provided', 400);
        }

        try {
            $user = $this->auth->authenticate($token);
        } catch (TokenExpiredException $e) {
            return $this->respond('tymon.jwt.expired', 'token_expired', $e->getStatusCode(), [$e]);
        } catch (JWTException $e) {
            return $this->respond('tymon.jwt.invalid', 'token_invalid', $e->getStatusCode(), [$e]);
        }

        if (! $user) {
            return $this->events->dispatch('tymon.jwt.user_not_found', 'user_not_found', 404);
        }

        $this->events->dispatch('tymon.jwt.valid', $user);

        return $next($request);
    }
    /**
     * Fire event and return the response.
     *
     * @param  string   $event
     * @param  string   $error
     * @param  int  $status
     * @param  array    $payload
     * @return mixed
     */
    protected function respond($event, $error, $status, $payload = [])
    {
        $response = $this->events->dispatch($event, $payload, true);

        return $response ?: $this->response->json(['error' => $error], $status);
    }
}
