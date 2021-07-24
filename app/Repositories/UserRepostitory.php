<?php

namespace App\Repositories;

use App\Http\Requests\UserRequest;
use App\Interfaces\UserInterface;
use App\Traits\ResponseAPI;
use App\Models\User;
use App\Models\Client;
use DB;
use App\Http\Resources\ApiCollection;

class UserRepository implements UserInterface
{
    // Use ResponseAPI Trait in this repository
    use ResponseAPI;

    public function getAllUsers()
    {
        try {
            $users = Client::paginate(10);

            $collection = new ApiCollection($users);

            return $this->success("All Users", $collection);
        } catch(\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function getUserById($id)
    {
        try {
            $user = User::find($id);

            // Check the user
            if(!$user) return $this->error("No user with ID $id", 404);

            return $this->success("User Detail", $user);
        } catch(\Exception $e) {
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function requestUser(UserRequest $request, $id = null)
    {
        $mapquestapi_key = "9ySA6D5u4JuTWs10tqTJYe9UOoggJviJ";
        DB::beginTransaction();
        try {
            $latitude = $longitude = 0;
            $address = urlencode("$request->address1,$request->city,$request->state,$request->country");
            
            $geo_decode = file_get_contents("http://open.mapquestapi.com/geocoding/v1/address?key=$mapquestapi_key&location=$address");
            if(!empty($geo_decode)){
                $geo_decode = json_decode($geo_decode, true);
                if(isset($geo_decode['results'][0]) && !empty($geo_decode['results'][0])){
                    $result = $geo_decode['results'][0];
                    if(isset($result['locations'][0]) && !empty($result['locations'][0])){
                        $locations = $result['locations'][0];
                        if(isset($locations['latLng']) && !empty($locations['latLng'])){
                            $latitude = $locations['latLng']['lat'];
                            $longitude = $locations['latLng']['lng'];
                        }
                    }
                }
            }

            $client = new Client;

            $client->client_name = $request->name;
            $client->address1 = $request->address1;
            $client->address2 = $request->address2;
            $client->city = $request->city;
            $client->state = $request->state;
            $client->country = $request->country;
            $client->latitude = $latitude;
            $client->longitude = $longitude;
            $client->phone_no1 = $request->phoneNo1;
            $client->phone_no2 = $request->phoneNo2;
            $client->zip = $request->zipCode;
            $client->start_validity = now();
            $client->end_validity = now()->addDays(15);
            $client->status = 'Active';

            #Save the client
            $client->save();

            $user = new User;
            $user->client_id = $client->id;
            $user->first_name = $request->user['firstName'];
            $user->last_name = $request->user['lastName'];
            $user->email = $request->user['email'];
            $user->password = \Hash::make($request->user['password']);
            $user->phone = $request->user['phone'];
            $user->status = 'Active';

            // Save the user
            $user->save();

            DB::commit();
            return $this->success(
                $id ? "User updated"
                    : "User created",
                $user, $id ? 200 : 201);
        } catch(\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), $e->getCode());
        }
    }

    public function deleteUser($id)
    {
        DB::beginTransaction();
        try {
            $user = User::find($id);

            // Check the user
            if(!$user) return $this->error("No user with ID $id", 404);

            // Delete the user
            $user->delete();

            DB::commit();
            return $this->success("User deleted", $user);
        } catch(\Exception $e) {
            DB::rollBack();
            return $this->error($e->getMessage(), $e->getCode());
        }
    }
}