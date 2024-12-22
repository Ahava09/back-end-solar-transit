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
        'role',
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
            ->issuedBy(env('APP_URL')) 
            ->permittedFor(env('APP_URL')) 
            ->identifiedBy(bin2hex(random_bytes(16)))
            ->issuedAt($now) 
            ->canOnlyBeUsedAfter($now) 
            ->expiresAt($now->modify('+1 hour'))
            ->withClaim('user_id', $this->id) 
            ->withClaim('email', $this->email) 
            ->withClaim('name', $this->name) 
            ->withClaim('role', $this->role) 
            ->getToken($config->signer(), $config->signingKey());

        return $token->toString();
    }

    public static function getUserDataFromToken(string $jwt): array
    {
        $config = Configuration::forSymmetricSigner(
            new \Lcobucci\JWT\Signer\Hmac\Sha256(),
            \Lcobucci\JWT\Signer\Key\InMemory::plainText(env('JWT_SECRET'))
        );

        $parser = $config->parser();
        $token = $parser->parse($jwt);

        $constraints = $config->validationConstraints();
        $constraints[] = new \Lcobucci\JWT\Validation\Constraint\IssuedBy(env('APP_URL'));
        $constraints[] = new \Lcobucci\JWT\Validation\Constraint\PermittedFor(env('APP_URL'));
        $constraints[] = new \Lcobucci\JWT\Validation\Constraint\SignedWith($config->signer(), $config->signingKey());
        $constraints[] = new \Lcobucci\JWT\Validation\Constraint\ValidAt(new \Lcobucci\Clock\SystemClock(new \DateTimeZone('UTC')));

        if (!$config->validator()->validate($token, ...$constraints)) {
            throw new \RuntimeException('Token is invalid or expired.');
        }

        if (!$token->claims()->has('user_id') || !$token->claims()->has('role')) {
            throw new \RuntimeException('Les claims nécessaires (user_id ou role) sont manquants.');
        }

        return [
            'user_id' => $token->claims()->get('user_id'),
            'role' => $token->claims()->get('role'),
        ];
    }


    public function coordinates ()
    {
        return $this->hasMany(GpsCoordinate::class);
    }
}
