<?php

namespace App\Models\Pengguna;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
//use Spatie\Permission\Traits\HasRoles;
use Illuminate\Foundation\Auth\User as Authenticatable;
//use JWTAuth;
use Auth;
use Laravel\Passport\HasApiTokens;
use App\Helpers\ModelsConstant;

class Pengguna extends Authenticatable  {
    use LogsActivity;
    use SoftDeletes, HasApiTokens;
    //use HasRoles;

    #protected $table = 'acc_users';
    protected $table = ModelsConstant::TABEL_PENGGUNA;
    protected $dates = ['deleted_at'];
    protected $guarded = [];
    protected $hidden = ['password', 'remember_token'];

    protected static $logAttributes = ['*'];
    protected static $ignoreChangedAttributes = ['created_at','created_by','updated_at', 'updated_by', 'deleted_at','deleted_by'];
    protected static $logOnlyDirty = true;
    protected static $logName = 'Pengguna';

    protected static function boot() {
        parent::boot();
        static::creating(function($model)  {
            $user = Auth::user();
            $model->created_by = $user->id;
            $model->updated_by = $user->id;
        });

        static::updating(function($model)  {
            $user = Auth::user();
            $model->updated_by = $user->id;
        });

        static::deleting(function($model) {
            $user = Auth::user();
            $model->deleted_by = $user->id;
            $model->save();
        });
    }

    public function menu() {
        return $this->hasMany('App\Models\Pengguna\MenuPengguna', 'user_id');
    }

}
