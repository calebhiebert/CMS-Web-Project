<?php

require_once 'model.php';
require_once 'db.php';

function getEntity($id) {
    global $db;

    try {
        $stmt = $db->prepare('SELECT Id AS id, Name AS name, Description AS description, Parent AS parent, Published AS published FROM Entities WHERE Id = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Entity');
        $stmt->execute();
        return $stmt->fetch();

    } catch (PDOException $e) {
        handleError($e);
    }

    return null;
}

function getEntities($num, $page) {
    global $db;

    try {
        $stmt = $db->prepare('SELECT Id AS id, Name AS name, Description AS description, Parent AS parent, Published AS published FROM Entities ORDER BY Id LIMIT :page, :num');
        $stmt->bindValue(':num', $num, PDO::PARAM_INT);
        $stmt->bindValue(':page', $page * $num, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'Entity');
    } catch (PDOException $e) {
        handleError($e);
    }

    return null;
}

function getEntityTags($entityId) {
    global $db;

    if(isset($db)) {
        try {
            $stmt = $db->prepare('SELECT Id as id, DataType as tagName, Data as tagData, Description as description FROM EntityTags LEFT JOIN Tags ON EntityTags.TagId = Tags.Id WHERE EntityId = :id');
            $stmt->bindValue(':id', $entityId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, 'Tag');
        } catch (PDOException $e) {
            handleError($e);
        }
    }

    return null;
}

function getTag($tagName, $tagData) {
    global $db;

    if (isset($db)) {
        try {
            $stmt = $db->prepare('SELECT Id AS id, DataType AS tagName, Data AS tagData, Description AS description FROM Tags WHERE UPPER(Data) = :data AND UPPER(DataType) = :name');
            $stmt->bindValue(':data', $tagData);
            $stmt->bindValue(':name', $tagName);
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Tag');
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            handleError($e);
        }
    }

    return null;
}

function getTagById($id) {
    global $db;

    if (isset($db)) {
        try {
            $stmt = $db->prepare('SELECT Id AS id, DataType AS tagName, Data AS tagData, Description AS description FROM Tags WHERE Id = :id');
            $stmt->bindValue(':id', $id);
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Tag');
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            handleError($e);
        }
    }

    return null;
}

function getTagsByName($tagName) {
    global $db;

    if (isset($db)) {
        try {
            $stmt = $db->prepare('SELECT Id AS id, DataType AS tagName, Data AS tagData, Description AS description FROM Tags WHERE UPPER(DataType) = :name');
            $stmt->bindValue(':name', $tagName);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, 'Tag');
        } catch (PDOException $e) {
            handleError($e);
        }
    }

    return null;
}

function getRegistrationCodes() {
    global $db;

    try {
        $stmt = $db->prepare('SELECT Code, PermLevel FROM RegistrationCodes');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'RegistrationCode');
    } catch (PDOException $e) {
        handleError($e);
    }

    return null;
}

function getSession($id) {
    global $db;

    if (isset($db)) {
        try {
            $stmt = $db->prepare('SELECT UserId AS userId, Token AS token, SupplyDate AS supplyDate FROM Sessions WHERE UserId = :id');
            $stmt->bindValue(':id', $id);
            $stmt->setFetchMode(PDO::FETCH_CLASS, 'Session');
            $stmt->execute();
            return $stmt->fetch();
        } catch (PDOException $e) {
            handleError($e);
        }
    }

    return null;
}

function getEdits($num, $page) {
    global $db;

    if (isset($db)) {
        try {
            $stmt = $db->prepare('SELECT UserId, Time, EntityId, TagId, PictureId FROM EditLog ORDER BY Time DESC LIMIT :page, :num');
            $stmt->bindValue(':num', $num, PDO::PARAM_INT);
            $stmt->bindValue(':page', $page * $num, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_CLASS, 'Edit');
        } catch (PDOException $e) {
            handleError($e);
        }
    }

    return null;
}

/**
 * @param Entity $entity
 */
function populateParentTree($entity) {
    $curr = $entity;

    while ($curr->getParent() != null) {
        $parent = getEntity($curr->getParent());
        $parent->setTags(getEntityTags($parent->getId()));
        $curr->setParent($parent);
        $curr = $parent;
    }

    return $entity;
}

/**
 * @param Entity $entity
 */
function populateChildren($entity) {
    global $db;

    try {
        $stmt = $db->prepare('SELECT Id AS id, Name AS name, Description AS description, Parent AS parent, Published AS published FROM Entities WHERE Parent = :id');
        $stmt->bindValue(':id', $entity->getId(), PDO::PARAM_INT);
        $stmt->execute();
        $entity->setChildren($stmt->fetchAll(PDO::FETCH_CLASS, 'Entity'));
    } catch (PDOException $e) {
        handleError($e);
    }
}

/**
 * @param Entity $entity
 */
function populatePublicChildren($entity) {
    global $db;

    try {
        $stmt = $db->prepare('SELECT Id AS id, Name AS name, Description AS description, Parent AS parent, Published AS published FROM Entities WHERE Parent = :id AND Published = 1');
        $stmt->bindValue(':id', $entity->getId(), PDO::PARAM_INT);
        $stmt->execute();
        $entity->setChildren($stmt->fetchAll(PDO::FETCH_CLASS, 'Entity'));
    } catch (PDOException $e) {
        handleError($e);
    }
}

function getUser($id) {

    global $db;

    try {
        $stmt = $db->prepare('SELECT Id AS id, Username AS username, Email AS email, PermLevel AS permLevel, RegisterDate AS registerDate FROM Users WHERE Id = :id');
        $stmt->bindValue(':id', $id);
        $stmt->setFetchMode(PDO::FETCH_CLASS, 'User');
        $stmt->execute();
        return $stmt->fetch();
    } catch (PDOException $e) {
        handleError($e);
    }

    return null;
}

function getUsers() {

    global $db;

    try {
        $stmt = $db->prepare('SELECT Id AS id, Username AS username, Email AS email, PermLevel AS permLevel, RegisterDate AS registerDate FROM Users');
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_CLASS, 'User');
    } catch (PDOException $e) {
        handleError($e);
    }

    return null;
}

/**
 * @param $regCode RegistrationCode
 */
function putRegistrationCode($regCode) {
    global $db;

    try {
        $code = $regCode->getCode();
        $perm = $regCode->getPermLevel();

        $stmt = $db->prepare('INSERT INTO RegistrationCodes (Code, PermLevel) VALUES (:regcode, :perm)');
        $stmt->bindParam(':regcode', $code);
        $stmt->bindParam(':perm', $perm, PDO::PARAM_INT);
        return $stmt->execute();
    } catch (PDOException $e) {
        handleError($e);
    }

    return null;
}

/**
 * @param $entity Entity
 */
function putEntity($entity) {
    global $db;

    try {
        $data = [':name'=>$entity->getName(),
            ':description'=>$entity->getDescription(),
            ':parent'=>($entity->getParent() != null) ? $entity->getParent() : null,
            ':published'=>($entity->isPublished() != null) ? 1 : 0];

        $stmt = $db->prepare('INSERT INTO Entities (Name, Description, Parent, Published) VALUES (:name, :description, :parent, :published)');
        $result = $stmt->execute($data);
        if($result == 1) {
            return $db->lastInsertId();
        }
    } catch (PDOException $e) {
        handleError($e);
    }

    return null;
}

/**
 * @param $entity Entity
 */
function editEntity($entity) {
    global $db;

    try {
        $data = [
            ':name'=>$entity->getName(),
            ':description'=>$entity->getDescription(),
            ':parent'=>($entity->getParent() != null) ? $entity->getParent() : null,
            ':published'=>($entity->isPublished() != null) ? 1 : 0,
            ':id'=>$entity->getId()];

        $stmt = $db->prepare('UPDATE Entities SET Name = :name, Description = :description, Parent = :parent, Published = :published WHERE Id = :id');
        $result = $stmt->execute($data);
        if($result == 1) {
            return $entity->getId();
        }
    } catch (PDOException $e) {
        handleError($e);
    }

    return null;
}

/**
 * @param $edit Edit
 * @return null|string
 */
function putEditEntry($edit) {
    global $db;

    try {
        $data = [':userid'=>$edit->getUserId(),
            ':entityid'=>($edit->getEntityId() != null) ? $edit->getEntityId() : null,
            ':tagid'=>($edit->getTagId() != null) ? $edit->getTagId() : null,
            ':pictureid'=>($edit->getPictureId() != null) ? $edit->getPictureId() : null];

        $stmt = $db->prepare('INSERT INTO EditLog (UserId, EntityId, TagId, PictureId) VALUES (:userid, :entityid, :tagid, :pictureid)');
        $result = $stmt->execute($data);
        if($result == 1) {
            return $db->lastInsertId();
        }
    } catch (PDOException $e) {
        handleError($e);
    }

    return null;
}

function removeRegistrationCode($regCode) {
    global $db;

    try {
        $stmt = $db->prepare('DELETE FROM RegistrationCodes WHERE Code = :code LIMIT 1');
        $stmt->bindParam(':code', $regCode);
        return $stmt->execute();
    } catch (PDOException $e) {
        handleError($e);
    }

    return null;
}

/**
 * @param PDOException $exception
 */
function handleError($exception) {
    echo $exception->getMessage();
}