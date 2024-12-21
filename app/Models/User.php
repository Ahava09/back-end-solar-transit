<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Lcobucci\JWT\Configuration;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'id',
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Create a JWT token with custom claims.
     *
     * @return string
     */
    public function createTokenWithClaims(): string
    {
        $config = Configuration::forSymmetricSigner(
            new \Lcobucci\JWT\Signer\Hmac\Sha256(),
            \Lcobucci\JWT\Signer\Key\InMemory::plainText(env('JWT_SECRET'))
        );

        $now = new \DateTimeImmutable();
        $token = $config->builder()
            ->issuedBy(env('APP_URL')) // Emmetteur du token
            ->permittedFor(env('APP_URL')) // Audience du token
            ->identifiedBy(bin2hex(random_bytes(16))) // ID unique du token
            ->issuedAt($now) // Date de création
            ->canOnlyBeUsedAfter($now) // Token immédiatement utilisable
            ->expiresAt($now->modify('+1 hour')) // Expiration après 1 heure
            ->withClaim('user_id', $this->id) 
            ->withClaim('email', $this->email) 
            ->withClaim('name', $this->name) 
            ->withClaim('role', $this->role) 
            ->getToken($config->signer(), $config->signingKey());

        return $token->toString();
    }

    public function coordinates ()
    {
        return $this->hasMany(GpsCoordinate::class);
    }
}
