<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use PowerComponents\LivewirePowerGrid\Cache;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'surname',
        'date_birth',
        'id_company',
        'email',
        'role',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Relationship
     */
    public function employee()
    {
        return $this->hasOne(Employee::class, 'id_user');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'id_company');
    }

    public function presences()
    {
        return $this->hasMany(Presence::class, 'id_user');
    }

    /**
     * Override delete method
     */
    public function delete()
    {
        $this->employee?->delete();
        return parent::delete();
    }

    /**
     * Scope
     */
    public function getEmployeeId()
    {
        return Employee::where('id_user', $this->id)->first()->id;
    }
}
