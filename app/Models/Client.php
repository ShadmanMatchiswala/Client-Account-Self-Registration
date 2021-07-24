<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Client extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'client_name',
        'addresss1',
        'addresss2',
        'city',
        'state',
        'country',
        'latitude',
        'longitude',
        'phone_no1',
        'phone_no2',
        'zip',
        'start_validity',
        'end_validity',
        'status'
    ];

    // protected $appends = [
    //     'totalUser'
    // ];

    public function getTotalUserAttribute()
    {
        $allUsers = $this->users;
        $activeUsers = $this->activeUsers;
        $inActiveUsers = $this->inActiveUsers;
        return ['all' => $allUsers->count(),'active'=>$activeUsers->count(), 'inactive' => $inActiveUsers->count()];
    }


    /**
     * Get the users associated with the client.
     */
    public function users()
    {
        return $this->hasMany(User::class, 'client_id');
    }

    /**
     * Get the active users associated with the client.
     */
    public function activeUsers()
    {
        return $this->hasMany(User::class, 'client_id')->where('status', 'Active');
    }

    /**
     * Get the active users associated with the client.
     */
    public function inActiveUsers()
    {
        return $this->hasMany(User::class, 'client_id')->where('status', 'Inactive');
    }
}
