<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Task;

class TasksController extends Controller
{
    // getでtasks/にアクセスされた場合の「一覧表示処理」
    public function index()
    {
        
        // 他人のタスクにアクセスしようとした場合、トップページにリダイレクトさせる条件分岐。
        if(\Auth::check()) {
            
            // 認証済みのユーザを取得
            $user = \Auth::user();
       
            // このユーザのみtaskを取得する。
            // paginate : ページ分け。
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
            
            //task一覧ビューでそれを表示
            return view('tasks.index', [
                'tasks' => $tasks,
            ]);
            
        }else{    
            // resources/views/welcome.blade.php　→　welcome
            // resources/views/tasks/index.blade.php　→　tasks.index

            return view('welcome');
        }

    }

    // getでtasks/createにアクセスされた場合の「新規登録画面表示処理」
    public function create()
    {
        //  $task = Task::findOrFail($id);
        
        if(\Auth::check()) {
            
            $task = new Task;
            
            //タスク作成ビューを表示
            return view('tasks.create', [
                'task' => $task,
            ]);
            
        }else{
            
            return redirect ('/');
        }
    }

    // postでtasks/にアクセスされた場合の「新規登録処理」
    public function store(Request $request)
    {
        //バリデーション
        $request->validate([
            'content' => 'required|max:255',
            'status' => 'required|max:10',
        ]);
        
        //タスクを作成
        // $task = new Task;
        // $task->status = $request->status;
        // $task->content = $request->content;
        // $task->user_id = $request->user_id;
        // $task->save();
        
        // 認証済みユーザ（閲覧者）の投稿として作成（リクエストされた値を元に作成）
        $request->user()->tasks()->create([
            'content' => $request->content,
            'status' => $request->status,
            ]);
        
        //トップページにリダイレクト
        return redirect('/');
    }

    // getでtasks/（任意のid）にアクセスされた場合の「取得表示処理」
    public function show($id)
    {
        //idの値でタスクを検索して取得
        $task = Task::findOrFail($id);
        
        if(\Auth::id() == $task->user_id) {
            
            //タスク詳細ビューでそれを表示
            return view('tasks.show', [
                'task' => $task,
            ]);
        }else{
            
            return redirect('/');
        }
    }

    // getでtasks/（任意のid）/editにアクセスされた場合の「更新画面表示処理」
    public function edit($id)
    {
            //idの値でタスクを検索して取得
            $task = Task::findOrFail($id);
        
        if(\Auth::id() == $task->user_id) {
            
            //タスク編集ビューでそれを表示
            return view('tasks.edit', [
                'task' => $task,
            ]);
        }else{
            
            return redirect('/');
        }
    }

    // putまたはpatchでtasks/（任意のid）にアクセスされた場合の「更新処理」
    public function update(Request $request, $id)
    {
        $task = \App\Task::findOrFail($id);
        
        if(\Auth::id() == $task->user_id) {
            
            // バリデーション
            $request->validate([
                'status' => 'required|max:10',
                'content' => 'required|max:255',
            ]);
            
            //idの値でタスクを検索して取得
            $task = Task::findOrFail($id);
            //タスクを更新
            $task->status = $request->status;
            $task->content = $request->content;
            $task->save();
            
            return redirect('/');
        
        }else{
            
            //トップページにリダイレクト
            return redirect('/');
        }
    }

    // deleteでtasks/（任意のid）にアクセスされた場合の「削除処理」
    public function destroy($id)
    {
        $task = \App\Task::findOrFail($id);
        
        if(\Auth::id() == $task->user_id) {
            
            //idの値でタスクを検索して取得
            $task = Task::findOrFail($id);
            //タスクを削除
            $task->delete();
            
            return redirect('/');
            
        }else{
            // トップページにリダイレクト
            return redirect('/');
            
        }
        
    }
}
