<?php

namespace Core\Controllers\Api;

use Core\Helpers\ApiResponse;
use Core\Models\Entities\User;
use Core\Models\ValueObjects\Email;
use Core\Models\ValueObjects\Password;
use Core\Models\ValueObjects\UUID;
use Core\Models\Interfaces\Repositories\UserRepositoryInterface;
use Core\Models\ValueObjects\SqlPaginator;
use Exception;
use Slim\Psr7\Request;
use Slim\Psr7\Response;

class UsersController {

    public function __construct(
        private UserRepositoryInterface $userRepository
    ) {}

    public function getAll(Request $req, Response $res) {

        @[ "page" => $page, "limit" => $limit ] = $req->getQueryParams();

        $users = $this->userRepository->findAll(new SqlPaginator($page, $limit));

        $res->getBody()->write(ApiResponse::return(
            data: array_map(fn ($u) => $u->toArray(), $users),
            code: 200,
            message: "Consulta realizada com sucesso!"
        ));

        return $res;

    }

    public function createAccount(Request $req, Response $res) {

        try {

            @[
                "name" => $name,
                "email" => $email,
                "password" => $password
            ] = $req->getParsedBody();
    
            $user = new User(
                id: UUID::generate(),
                name: $name,
                email: new Email($email),
                password: new Password($password)
            );

            if($this->userRepository->findByEmail($user->getEmail())) {
                throw new Exception("E-mail já cadastrado!", 409);
            }

            while($this->userRepository->findById($user->getId())) {
                $user->changeId(UUID::generate());
            }
            
            $userCreated = $this->userRepository->create($user);
    
            $res->getBody()->write(ApiResponse::return(
                data: $userCreated->toArray(),
                code: 200,
                message: "Usuário criado com sucesso!"
            ));
    
            return $res;

        } catch(Exception $e) {

            throw $e;

        }

    }

}