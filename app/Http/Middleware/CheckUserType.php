<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckUserType
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string  $userType
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, $userType)
    {
        $user = Auth::guard('usuarios')->user();
        
        if (!$user) {
            return redirect()->route('login');
        }

        $requiredType = $this->getTypeValue($userType);
        
        if ($user->user_tipo != $requiredType) {
            return $this->redirectByUserType($user->user_tipo);
        }

        return $next($request);
    }


    private function getTypeValue($typeText)
    {
        switch (strtolower($typeText)) {
            case 'admin':
            case 'administrador':
                return 0;
            case 'docente':
            case 'teacher':
                return 1;
            case 'estudiante':
            case 'student':
                return 2;
            default:
                return null;
        }
    }

    private function redirectByUserType($userType)
    {
        switch ($userType) {
            case 0: 
                return redirect()->route('usuarios.index')->with('info', 'Acceso como administrador');
            case 1: 
                return redirect()->route('inicio')->with('info', 'Acceso restringido. Redirigido a tu panel de docente.');
            case 2: 
                return redirect()->route('inicio')->with('info', 'Acceso restringido. Redirigido a tu panel de estudiante.');
            default:
                return redirect()->route('login')->with('error', 'Tipo de usuario no válido');
        }
    }
}