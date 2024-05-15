<?php

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table('user')]
class User
{
    #[Id]
    #[Column, GeneratedValue]
    private int $id;

    #[Column]
    private string $email;

    #[Column]
    private string $password;

    #[Column]
    private string $username;

    #[Column]
    private string $name;

    #[Column]
    private string $surname;

    #[Column]
    private string $dietPreference;

    public function getId(): int
    {
        return $this->id;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): User
    {
        $this->email = $email;
        return $this;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): User
    {
        $this->password = $password;
        return $this;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): User
    {
        $this->username = $username;
        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): User
    {
        $this->name = $name;
        return $this;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }

    public function setSurname(string $surname): User
    {
        $this->surname = $surname;
        return $this;
    }

    public function getDietPreference(): string
    {
        return $this->dietPreference;
    }

    public function setDietPreference(string $dietPreference): User
    {
        $this->dietPreference = $dietPreference;
        return $this;
    }



}