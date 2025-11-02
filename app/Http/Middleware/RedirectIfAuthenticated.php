<?php
// app/Http/Middleware/RedirectIfAuthenticated.php

// namespace App\Http\Middleware;

// use App\Providers\RouteServiceProvider;
// use Closure;
// use Illuminate\Http\Request;
// use Illuminate\Support\Facades\Auth;
// use Symfony\Component\HttpFoundation\Response;

// class RedirectIfAuthenticated
// {
//     /**
//      * Handle an incoming request.
//      *
//      * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
//      */
//     public function handle(Request $request, Closure $next, string ...$guards): Response
//     {
//         $guards = empty($guards) ? [null] : $guards;

//         foreach ($guards as $guard) {
//             if (Auth::guard($guard)->check()) {
                
//                 // --- CUSTOM LOGIC ADDITION ---
//                 // Agar 'admin' guard check ho raha hai aur Admin logged in hai, toh admin dashboard par bhejo.
//                 if ($guard === 'admin') {
//                     return redirect()->route('admin.dashboard');
//                 }

//                 // Agar 'web' (default) guard check ho raha hai aur web user logged in hai, 
//                 // BUT request admin login page ke liye nahi hai, toh home par bhejo.
//                 if ($guard === null && $request->is('admin/*') === false) {
//                     return redirect(RouteServiceProvider::HOME);
//                 }

//                 // Agar Admin login page hai, toh isko ignore karke next step (yaani Login Page) par jaane do.
//                 if ($request->is('admin/login')) {
//                     // Do nothing, proceed to login controller.
//                 } else {
//                     return redirect(RouteServiceProvider::HOME);
//                 }
//             }
//         }

//         return $next($request);
//     }
// }
