<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'vat_number',
        'address',
        'city',
        'province',
        'zip_code',
        'phone',
        'pec'
    ];

    /**
     * Relationship
     */
    public function users()
    {
        return $this->hasMany(User::class, 'id_company');
    }

    public function worksites()
    {
        return $this->hasMany(Worksite::class, 'id_company');
    }

    /**
     * Override delete method
     */
    public function delete()
    {
        $this->users()->update(
            ['id_company' => null]
        );
        return parent::delete();
    }
}
