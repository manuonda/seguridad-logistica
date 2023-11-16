<?php
namespace App\Models;

use CodeIgniter\Model;


class UserModel extends Model{
  protected $table = 'public.users';
  protected $allowedFields = ['firstname', 'lastname', 'email', 'password', 'updated_at','username'];

}