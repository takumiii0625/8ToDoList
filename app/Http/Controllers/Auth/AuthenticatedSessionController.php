<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     *
     * @param  \App\Http\Requests\Auth\LoginRequest  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        //ユーザーが所有する最初のフォルダを取得
        $folder = Auth::user()->folders()->first();

        if ($folder) {
            // フォルダが存在すれば、そのフォルダのタスク一覧にリダイレクト
            return redirect()->route('tasks.index', ['id' => $folder->id]);
        } else {
            // フォルダが存在しなければ、ダッシュボード（または任意のページ）にリダイレクト
            //return redirect()->intended(RouteServiceProvider::HOME);

            // フォルダが存在しなければ、フォルダ作成ページにリダイレクト
            return redirect()->route('folders.create');
        }
        //  return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
