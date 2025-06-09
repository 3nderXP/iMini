<?php

namespace Core\Services;

use Core\Models\Interfaces\Services\TokenServiceInterface;
use Exception;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;

class JWTService implements TokenServiceInterface {

    public function encode(array $payload): string {

        return JWT::encode($payload, $_ENV["JWT_SECRET"], $_ENV["JWT_ALG"]);

    }
    
    public function decode(string $token): object {

        return JWT::decode($token, new Key($_ENV["JWT_SECRET"], $_ENV["JWT_ALG"]));

    }

    public function isValid(string $token, ?array $claims = null): bool {

        try {

            $tokenDecoded = $this->decode($token);

            if(!empty($claims)) {

                foreach($claims as $k => $value) {

                    $claim = is_int($k) ? $value : $k;

                    if(!isset($tokenDecoded->{$claim})) {
                        throw new Exception("Invalid token", 401);
                    }

                    if(!is_int($k)) {

                        $claimValue = $tokenDecoded->{$claim};

                        if(is_array($value)){
                            
                            if(!in_array($claimValue, $value)) {
                                throw new Exception("Invalid token", 401);
                            }

                        } else if($claimValue !== $value) {
                            throw new Exception("Invalid token", 401);
                        }


                    }
                    
                }
                
            }

            return true;

        } catch(Exception $e) {

            return false;

        }
        
    }

}