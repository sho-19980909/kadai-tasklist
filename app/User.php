<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
   protected $fillable = [
        'name', 'email', 'password',
    ];
    
    /**
         * The attributes that should be hidden for arrays.
         *
         * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    
    // UserのインスタンスからそのUserが持つMicropostsをuser->microposts、いう簡単な記述で取得できるようになる。
    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
    
    // このユーザに関係するモデルの件数をロードする
    public function loadRelationshipCounts()
    {
        $this->loadCount('tasks');
    }
}
