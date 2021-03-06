<?php
namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\MyResetPassword;

class User extends Authenticatable{        
    use Notifiable;        
    /**          
     * * * The attributes that are mass assignable.          
     * *      
     * * *          
     * * * @var array          
     */        
    protected $fillable = [                
        'name', 'email', 'password', 'rutEmpresa', 'api_token','phone_number',            
        ];        
    /**          
     * * * The attributes that should be hidden for arrays.          
     * * *          
     * * * @var array          
     */    
    protected $hidden = [                 
        'password', 'remember_token',             
        ];         
    /**           
     * * * The attributes that should be cast to native types.           
     * * *           
     * * * @var array           
     */         
    protected $casts = [                 
        'email_verified_at' => 'datetime',             
        ];         
    
    public function roles(){

        return $this

            ->belongsToMany('App\Role')

            ->withTimestamps();

    }



    public function authorizeRoles($roles)

    {

        abort_unless($this->hasAnyRole($roles), 401);



        return true;

    }



    public function hasAnyRole($roles)

    {

        if (is_array($roles)) {

            foreach ($roles as $role) {

                if ($this->hasRole($role)) {

                    return true;

                }

            }

        } else {

            if ($this->hasRole($roles)) {

                return true;

            }

        }



        return false;

    }



    public function hasRole($role)

    {

        if ($this->roles()->where('nombre', $role)->first()) {

            return true;

        }



        return false;

    }



    public function sendPasswordResetNotification($token)

    {

        $this->notify(new MyResetPassword($token));        

    }

}
