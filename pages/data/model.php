<?php

class Entity {
    private $Id;
    private $Name;
    private $Description;
    private $Published;

    private $tags;
    private $Parent;
    private $children;

    function __construct()
    {
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->Id;
    }

    /**
     * @param mixed $Id
     */
    public function setId($Id)
    {
        $this->Id = $Id;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->Name;
    }

    /**
     * @param mixed $Name
     */
    public function setName($Name)
    {
        $this->Name = $Name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->Description;
    }

    /**
     * @param mixed $Description
     */
    public function setDescription($Description)
    {
        $this->Description = $Description;
    }

    /**
     * @return mixed
     */
    public function isPublished()
    {
        return $this->Published == 1 ? true : false;
    }

    /**
     * @param mixed $Published
     */
    public function setPublished($Published)
    {
        $this->Published = $Published;
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
        return $this->Parent;
    }

    /**
     * @param mixed $Parent
     */
    public function setParent($Parent)
    {
        $this->Parent = $Parent;
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
    private $EntityId;
    private $Tag;

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
    public function getTag()
    {
        return $this->Tag;
    }

    /**
     * @param mixed $Tag
     */
    public function setTag($Tag)
    {
        $this->Tag = $Tag;
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

class Image {
    private $Id;
    private $EntityId;
    private $FileExt;
    private $FileSize;
    private $Caption;
    private $Name;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->Id;
    }

    /**
     * @param mixed $Id
     */
    public function setId($Id)
    {
        $this->Id = $Id;
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
    public function getFileExt()
    {
        return $this->FileExt;
    }

    /**
     * @param mixed $FileExt
     */
    public function setFileExt($FileExt)
    {
        $this->FileExt = $FileExt;
    }

    /**
     * @return mixed
     */
    public function getFileSize()
    {
        return $this->FileSize;
    }

    /**
     * @param mixed $FileSize
     */
    public function setFileSize($FileSize)
    {
        $this->FileSize = $FileSize;
    }

    /**
     * @return mixed
     */
    public function getCaption()
    {
        return $this->Caption;
    }

    /**
     * @param mixed $Caption
     */
    public function setCaption($Caption)
    {
        $this->Caption = $Caption;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->Name;
    }

    /**
     * @param mixed $Name
     */
    public function setName($Name)
    {
        $this->Name = $Name;
    }
}