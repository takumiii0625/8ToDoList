<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Folder;
use App\Http\Requests\CreateFolder; // ★ 追加
// ★ Authクラスをインポートする
use Illuminate\Support\Facades\Auth;

class FolderController extends Controller
{
    public function showCreateForm()
    {
        return view('folders/create');
    }

    public function create(CreateFolder $request) // ★ 引数の型を変更
    {
        // フォルダモデルのインスタンスを作成する
        $folder = new Folder();

        // タイトルに入力値を代入する
        $folder->title = $request->title;

        // 現在ログインしているユーザーのIDを設定
        $folder->user_id = Auth::id();

        // インスタンスの状態をデータベースに書き込む
        $folder->save();


        return redirect()->route('tasks.index', [
            'id' => $folder->id,
        ]);
    }
}
