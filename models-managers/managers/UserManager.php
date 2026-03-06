<?php

class UserManager extends AbstractManager
{
    public function __construct()
    {
        parent::__construct();
    }

    //----------------------------------------------

    public function create(User $user): User
    {
        $query = $this->db->prepare('INSERT INTO users (firstName, lastName, email, password, created_at) VALUES (:firstName, :lastName, :email, :password, :created_at)');
        $parameters = [
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'created_at' => $user->getCreatedAt()
        ];
        $query->execute($parameters);
        $user->setId((int) $this->db->lastInsertId());

        return $user;
    }
    
    
    //----------------------------------------------
    

    public function update(User $user): User
    {
        $query = $this->db->prepare('UPDATE users SET firstName = :firstName, lastName = :lastName, email = :email, password = :password, created_at = :created_at WHERE id = :id');
        $parameters = [
            'firstName' => $user->getFirstName(),
            'lastName' => $user->getLastName(),
            'email' => $user->getEmail(),
            'password' => $user->getPassword(),
            'created_at' => $user->getCreatedAt(),
            'id' => $user->getId()
        ];
        $query->execute($parameters);

        return $user;
    }

    //----------------------------------------------

    public function delete(User $user): void
    {
        $query = $this->db->prepare('DELETE FROM users WHERE id = :id');
        $parameters = [
            'id' => $user->getId()
        ];
        $query->execute($parameters);
    }

    //----------------------------------------------

    public function findOne(int $id): User
    {
        $query = $this->db->prepare('SELECT * FROM users WHERE id = :id');
        $parameters = [
            'id' => $id
        ];
        $query->execute($parameters);
        $result = $query->fetch(PDO::FETCH_ASSOC);

        $user = new User($result["firstName"], $result["lastName"], $result["email"], $result["password"], $result["created_at"], $result["id"]);

        return $user;
    }

    //----------------------------------------------

    public function findAll(): array
    {
        $query = $this->db->prepare('SELECT * FROM users');
        $parameters = [

        ];
        $query->execute($parameters);
        $results = $query->fetchAll(PDO::FETCH_ASSOC);
        $users = [];

        foreach($results as $result)
        {
            $user = new User($result["firstName"], $result["lastName"], $result["email"], $result["password"], $result["created_at"], $result["id"]);
            $users[] = $user;
        }

        return $users;
    }
}