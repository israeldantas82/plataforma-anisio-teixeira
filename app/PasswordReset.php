<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Traits\UserCan;

class PasswordReset extends Model
{
	protected $table = "password_resets";

	public $timestamps = false;
	protected $primaryKey = null;
    public $incrementing = false;

    protected $fillable = [
        'email',
        'token',
        'created_at'
    ];
    
    // Recupera o token pelo email do usuário
    public function getTokenByEmail($email)
    {
    	return $this->where('email', $email)->first();
    }
    
    // Recupera o token pelo proprio token
    public function getToken($token)
    {
    	return $this->where('token', $token)->first();
    }
    
    // Valida o token
    public function tokenValidation($token)
    {
    	$dados = $this->getToken($token);
    	$created_at = date('Y-m-d', strtotime($dados->created_at));

    	$data = date('Y-m-d', strtotime("-1 days", strtotime(date('Y-m-d'))));
    	return $data;

    	if ($data > $created_at) {
    		return false;
    	}

    	return true;
    }
}