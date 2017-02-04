<?php
/**
 * Created by PhpStorm.
 * User=> Yassine
 * Date=> 04/02/2017
 * Time=> 00=>05
 */

namespace App\Transformers;


use App\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{
    public function transform(User $user)
    {
        $role = $user->roles()->first();
        return [
            'id' => (int)$user->id,
            'username' => $user->username,
            'lastName' => ucfirst($user->lastName),
            'firstName' => ucfirst($user->firstName),
            'role' => [
                'id' => (int)$role->id,
                'name' => $role->name,
                'display_name' => $role->display_name,
                'description' => $role->description,
            ]
        ];
    }
}