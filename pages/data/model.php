<?php

class Entity {
    private $id;
    private $name;
    private $description;
    private $published;

    private $tags;

    private $parent;

    private $children;

    function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param mixed $name
     */
    public function setName($name)
    {
        $this->name = $name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function isPublished()
    {
        return $this->published == 1 ? true : false;
    }

    /**
     * @param mixed $published
     */
    public function setPublished($published)
    {
        $this->published = $published;
    }

    /**
     * @return mixed
     */
    public function getTags()
    {
        return $this->tags;
    }

    /**
     * @param mixed $tags
     */
    public function setTags($tags)
    {
        $this->tags = $tags;
    }

    /**
     * @return mixed
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * @param mixed $parent
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
    }

    /**
     * @return mixed
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * @param mixed $children
     */
    public function setChildren($children)
    {
        $this->children = $children;
    }
}

class Tag {
    private $id;
    private $tagName;
    private $tagData;
    private $description;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getTagName()
    {
        return $this->tagName;
    }

    /**
     * @param mixed $tagName
     */
    public function setTagName($tagName)
    {
        $this->tagName = $tagName;
    }

    /**
     * @return mixed
     */
    public function getTagData()
    {
        return $this->tagData;
    }

    /**
     * @param mixed $tagData
     */
    public function setTagData($tagData)
    {
        $this->tagData = $tagData;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }
}

class User {
    private $id;
    private $username;
    private $password;
    private $email;
    private $permLevel;
    private $registerDate;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * @param mixed $username
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * @return mixed
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param mixed $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @return mixed
     */
    public function getPermLevel()
    {
        return $this->permLevel;
    }

    /**
     * @param mixed $permLevel
     */
    public function setPermLevel($permLevel)
    {
        $this->permLevel = $permLevel;
    }

    /**
     * @return mixed
     */
    public function getRegisterDate()
    {
        return $this->registerDate;
    }

    /**
     * @param mixed $registerDate
     */
    public function setRegisterDate($registerDate)
    {
        $this->registerDate = $registerDate;
    }
}

class Session {
    private $userId;
    private $token;
    private $supplyDate;

    /**
     * Session constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * @param mixed $userId
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;
    }

    /**
     * @return mixed
     */
    public function getToken()
    {
        return $this->token;
    }

    /**
     * @param mixed $token
     */
    public function setToken($token)
    {
        $this->token = $token;
    }

    /**
     * @return mixed
     */
    public function getSupplyDate()
    {
        return $this->supplyDate;
    }

    /**
     * @param mixed $supplyDate
     */
    public function setSupplyDate($supplyDate)
    {
        $this->supplyDate = $supplyDate;
    }


}

class Edit {
    private $UserId;
    private $Username;
    private $Time;
    private $EntityId;
    private $TagId;
    private $PictureId;

    /**
     * Edit constructor.
     */
    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->UserId;
    }

    /**
     * @param mixed $UserId
     */
    public function setUserId($UserId)
    {
        $this->UserId = $UserId;
    }

    /**
     * @return mixed
     */
    public function getTime()
    {
        return $this->Time;
    }

    /**
     * @param mixed $Time
     */
    public function setTime($Time)
    {
        $this->Time = $Time;
    }

    /**
     * @return mixed
     */
    public function getEntityId()
    {
        return $this->EntityId;
    }

    /**
     * @param mixed $EntityId
     */
    public function setEntityId($EntityId)
    {
        $this->EntityId = $EntityId;
    }

    /**
     * @return mixed
     */
    public function getTagId()
    {
        return $this->TagId;
    }

    /**
     * @param mixed $TagId
     */
    public function setTagId($TagId)
    {
        $this->TagId = $TagId;
    }

    /**
     * @return mixed
     */
    public function getPictureId()
    {
        return $this->PictureId;
    }

    /**
     * @param mixed $PictureId
     */
    public function setPictureId($PictureId)
    {
        $this->PictureId = $PictureId;
    }

    /**
     * @return mixed
     */
    public function getUsername()
    {
        return $this->Username;
    }

    /**
     * @param mixed $UserName
     */
    public function setUsername($UserName)
    {
        $this->Username = $UserName;
    }
}

class RegistrationCode {
    private $Code;
    private $PermLevel;

    /**
     * RegistrationCode constructor.
     * @param $Code
     * @param $PermLevel
     */
    public function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getCode()
    {
        return $this->Code;
    }

    /**
     * @param mixed $Code
     */
    public function setCode($Code)
    {
        $this->Code = $Code;
    }

    /**
     * @return mixed
     */
    public function getPermLevel()
    {
        return $this->PermLevel;
    }

    /**
     * @param mixed $PermLevel
     */
    public function setPermLevel($PermLevel)
    {
        $this->PermLevel = $PermLevel;
    }


}