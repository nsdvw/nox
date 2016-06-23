<?php
namespace PricklyNut\NoxChallenge\Mapper;

use PricklyNut\NoxChallenge\Entity\User;

class UserMapper
{
    protected $connection;

    /**
     * @var string
     */
    protected $entityName;

    public function __construct(\PDO $connection)
    {
        $this->connection = $connection;
        $this->entityName = "\\PricklyNut\\NoxChallenge\\Entity\\User";
    }

    /**
     * @return string
     */
    public function getEntityName()
    {
        return $this->entityName;
    }

    /**
     * @param string $entityName
     */
    public function setEntityName($entityName)
    {
        $this->entityName = $entityName;
    }

    public function findById($id)
    {
        $sql = "SELECT id, name, surname, salt, hash, email
                FROM user WHERE id = :id";
        $sth = $this->connection->prepare($sql);
        $sth->bindValue(':id', $id, \PDO::PARAM_INT);
        $sth->execute();

        return $sth->fetchObject($this->entityName);
    }

    public function deleteById($id)
    {
        $sql = "DELETE FROM user WHERE id = :id";
        $sth = $this->connection->prepare($sql);
        $sth->bindValue(':id', $id, \PDO::PARAM_INT);
        $sth->execute();
    }

    public function findByEmail($email)
    {
        $sql = "SELECT id, name, surname, salt, hash, email
                FROM user WHERE email = :email";
        $sth = $this->connection->prepare($sql);
        $sth->bindValue(':email', $email, \PDO::PARAM_STR);
        $sth->execute();

        return $sth->fetchObject($this->entityName);
    }

    public function insert(User $user)
    {
        $sql = "INSERT INTO user (name, surname, email, salt, hash)
                VALUES (:name, :surname, :email, :salt, :hash)";
        $sth = $this->connection->prepare($sql);
        $sth->bindValue(':name', $user->getName(), \PDO::PARAM_STR);
        $sth->bindValue(':surname', $user->getSurname(), \PDO::PARAM_STR);
        $sth->bindValue(':email', $user->getEmail(), \PDO::PARAM_STR);
        $sth->bindValue(':salt', $user->getSalt(), \PDO::PARAM_STR);
        $sth->bindValue(':hash', $user->getHash(), \PDO::PARAM_STR);
        $sth->execute();
        $user->setId($this->connection->lastInsertId());
    }

    public function update(User $user)
    {
        $sql = "UPDATE user
                SET name=:name, surname=:surname, email=:email, salt=:salt, hash=:hash
                WHERE id=:id";
        $sth = $this->connection->prepare($sql);
        $sth->bindValue(':id', $user->getId(), \PDO::PARAM_INT);
        $sth->bindValue(':name', $user->getName(), \PDO::PARAM_STR);
        $sth->bindValue(':surname', $user->getSurname(), \PDO::PARAM_STR);
        $sth->bindValue(':email', $user->getEmail(), \PDO::PARAM_STR);
        $sth->bindValue(':salt', $user->getSalt(), \PDO::PARAM_STR);
        $sth->bindValue(':hash', $user->getHash(), \PDO::PARAM_STR);
        $sth->execute();
    }
}
