<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

// #[Fillable(['name', 'email', 'password'])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $guarded = [];
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function accesses()
    {
        return $this->hasMany(MenuAccess::class, 'menu_id');
    }
    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id', 'id');
    }

    public function company()
    {
        return $this->belongsTo(Company::class, 'company_id', 'id');
    }
    public static function front_table()
    {
        return User::with(['role:id,name', 'company:id,company_name'])
            ->latest();
    }
    public static function role_id($search)
    {
        return Role::when($search, function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        })
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();
    }
    public static function company_id($search)
    {
        return Company::when($search, function ($query) use ($search) {
            $query->where('company_name', 'like', '%' . $search . '%');
        })
            ->select('id', 'company_name')
            ->orderBy('company_name', 'asc')
            ->get();
    }
    public static function menu_list($search)
    {
        return Menu::when($search, function ($query) use ($search) {
            $query->where('name', 'like', '%' . $search . '%');
        })
            ->select('id', 'name')
            ->orderBy('name', 'asc')
            ->get();
    }
}
