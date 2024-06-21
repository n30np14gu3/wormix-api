<?php

namespace App\Modules\SessionController;

use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;

class GameSessionController
{
     private User $sessionUser;
     public function __construct(User $user) {
         $this->sessionUser = $user;
     }

     public function createSession(string $auth_key)
     {

     }

     public function getSession(string $session_key)
     {

     }

     public function closeSession(string $session_key)
     {

     }
}
