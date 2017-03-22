<?php
require_once 'model.php';

/**
 * Getter methods
 */
function getSingle($sql, $inputs = null, $bindingObject = null) {
    global $db;

    try {
        $stmt = $db->prepare($sql);

        if($inputs != null) {
            foreach ($inputs as $key => $val) {
                if(is_numeric($val)) {
                    $stmt->bindValue($key, $val, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue($key, $val);
                }
            }
        }

        $stmt->execute();

        if($bindingObject != null)
            $stmt->setFetchMode(PDO::FETCH_CLASS, $bindingObject);

        return $stmt->fetch();

    } catch (PDOException $e) {
        handleError($e);
    }

    return null;
}

function getMultiple($sql, $inputs = null, $bindingObject = null) {
    global $db;

    try {
        $stmt = $db->prepare($sql);

        if($inputs != null) {
            foreach ($inputs as $key => $val) {
                if(is_numeric($val)) {
                    $stmt->bindValue($key, $val, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue($key, $val);
                }
            }
        }

        $stmt->execute();

        if($bindingObject != null)
            return $stmt->fetchAll(PDO::FETCH_CLASS, $bindingObject);
        else
            return $stmt->fetchAll();

    } catch (PDOException $e) {
        handleError($e);
    }

    return null;
}

function execute($sql, $inputs = null) {
    global $db;

    try {
        $stmt = $db->prepare($sql);

        if($inputs != null) {
            foreach ($inputs as $key => $val) {
                if(is_numeric($val)) {
                    $stmt->bindValue($key, $val, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue($key, $val);
                }
            }
        }

        return $stmt->execute();
    } catch (PDOException $e) {
        handleError($e);
    }

    return null;
}

function insert($sql, $inputs = null) {
    global $db;

    try {
        $stmt = $db->prepare($sql);

        if($inputs != null) {
            foreach ($inputs as $key => $val) {
                if(is_numeric($val)) {
                    $stmt->bindValue($key, $val, PDO::PARAM_INT);
                } else {
                    $stmt->bindValue($key, $val);
                }
            }
        }

        $stmt->execute();
        return $db->lastInsertId();
    } catch (PDOException $e) {
        handleError($e);
    }

    return null;
}

/**
 * Entity Cruds
 */

function getEntity($id) {
    return getSingle(
        "SELECT Id, Name, Description, Parent, Published
          FROM Entities 
          WHERE Id = :id",
        [':id'=>$id],
        'Entity'
    );
}

function getEntityByName($name) {
    return getSingle(
        'SELECT Id, Name, Description, Parent, Published
          FROM Entities
          WHERE Name = :name',
        ['name'=>$name],
        'Entity'
    );
}

function getEntityCreation($id) {
    return getSingle(
        "SELECT UserId, 
          (SELECT Username FROM Users WHERE Id = UserId) AS Username, Time 
          FROM EditLog WHERE Time = (SELECT MIN(Time) FROM EditLog WHERE EntityId = :id)",
        ['id'=>$id],
        'Edit'
    );
}

function getEntityLastEdit($id) {
    return getSingle(
        'SELECT UserId, (SELECT Username FROM Users WHERE Id = UserId) AS Username, Time FROM EditLog WHERE Time = (SELECT MAX(Time) FROM EditLog WHERE EntityId = :id)',
        ['id'=>$id],
        'Edit');
}

function getEntities($num, $page, $includeUnpublished) {
    return getMultiple(
        'SELECT Id, Name, Description, Parent, Published
            FROM Entities ' . ($includeUnpublished ? '' : 'WHERE Published = 1') .
          ' ORDER BY Id LIMIT :page, :num',
        ['num'=>$num, 'page'=>($page*$num)],
        'Entity'
    );
}

function getEntityTags($entityId) {
    return getMultiple(
        'SELECT Id as id, DataType as tagName, Data as tagData, Description as description 
            FROM EntityTags 
            LEFT JOIN Tags ON EntityTags.TagId = Tags.Id 
            WHERE EntityId = :id',
        ['id'=>$entityId],
        'Tag'
    );
}

function getTag($tagName, $tagData) {
    return getSingle(
        'SELECT Id AS id, DataType AS tagName, Data AS tagData, Description AS description 
            FROM Tags 
            WHERE UPPER(Data) = :data AND UPPER(DataType) = :name',
        ['data'=>$tagData, 'name'=>$tagName],
        'Tag'
    );
}

function getTagById($id) {
    return getSingle(
        'SELECT Id AS id, DataType AS tagName, Data AS tagData, Description AS description FROM Tags WHERE Id = :id',
        ['id'=>$id],
        'Tag'
    );
}

function getTagsByName($tagName) {
    return getMultiple(
        'SELECT Id AS id, DataType AS tagName, Data AS tagData, Description AS description FROM Tags WHERE UPPER(DataType) = :name',
        ['name'=>$tagName],
        'Tag'
    );
}

function getRegistrationCodes() {
    return getMultiple(
        'SELECT Code, PermLevel FROM RegistrationCodes', [],
        'RegistrationCode'
    );
}

function getSession($id) {
    return getSingle(
        'SELECT UserId AS userId, Token AS token, SupplyDate AS supplyDate FROM Sessions WHERE UserId = :id',
        ['id'=>$id],
        'Session'
    );
}

function getEdits($num, $page) {
    return getMultiple(
        'SELECT UserId, Time, EntityId, TagId, PictureId, Username 
          FROM EditLog 
          LEFT JOIN Users ON EditLog.UserId = Users.Id 
          ORDER BY Time DESC LIMIT :page, :num',
        ['num'=>$num, 'page'=>($page * $num)],
        'Edit'
    );
}

function searchEntities($query) {
    return getMultiple(
        'SELECT DISTINCT Entities.Name, Entities.Id, GROUP_CONCAT(Tags.Data) Tags
          FROM Entities 
          LEFT JOIN EntityTags ON Entities.Id = EntityTags.EntityId 
          LEFT JOIN Tags ON EntityTags.TagId = Tags.Id 
          GROUP BY Entities.Id HAVING Name LIKE :sterm OR Tags LIKE :sterm',
        ['sterm'=>('%'.$query.'%')]
    );
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
    $children = getMultiple(
        'SELECT Id, Name, Description, Parent, Published FROM Entities WHERE Parent = :id',
        ['id'=>$entity->getId()],
        'Entity'
    );

    $entity->setChildren($children);
}

/**
 * @param Entity $entity
 */
function populatePublicChildren($entity) {
    $children = getMultiple(
        'SELECT Id AS id, Name AS name, Description AS description, Parent AS parent, Published AS published FROM Entities WHERE Parent = :id AND Published = 1',
        ['id'=>$entity->getId()],
        'Entity'
    );

    $entity->setChildren($children);
}

function getUser($id) {
    return getSingle(
        'SELECT Id AS id, Username AS username, Email AS email, PermLevel AS permLevel, RegisterDate AS registerDate FROM Users WHERE Id = :id',
        ['id'=>$id],
        'User'
    );
}

function deleteUser($id) {
    execute(
        'DELETE FROM Users WHERE Id = :id LIMIT 1',
        ['id'=>$id]
    );
}

function getUsers() {
    return getMultiple(
        'SELECT Id AS id, Username AS username, Email AS email, PermLevel AS permLevel, RegisterDate AS registerDate FROM Users', [],
        'User'
    );
}

/**
 * @param $regCode RegistrationCode
 */
function putRegistrationCode($regCode) {
    return execute(
        'INSERT INTO RegistrationCodes (Code, PermLevel) VALUES (:regcode, :clearance)',
        ['regcode'=>$regCode->getCode(), 'clearance'=>$regCode->getPermLevel()]
    );
}

/**
 * @param $entity Entity
 */
function putEntity($entity) {
    return insert(
        'INSERT INTO Entities (Name, Description, Parent, Published) VALUES (:name, :description, :parent, :published)',
        ['name'=>$entity->getName(),
            'description'=>$entity->getDescription(),
            'parent'=>($entity->getParent() != null) ? $entity->getParent() : null,
            'published'=>($entity->isPublished() != null) ? 1 : 0]
    );
}

/**
 * @param $entity Entity
 */
function editEntity($entity) {
    $result = execute(
        'UPDATE Entities SET Name = :name, Description = :description, Parent = :parent, Published = :published WHERE Name = :name',
        ['name'=>$entity->getName(),
            'description'=>$entity->getDescription(),
            'parent'=>($entity->getParent() != null) ? $entity->getParent() : null,
            'published'=>($entity->isPublished() != null) ? 1 : 0]
    );

    if($result == 1) {
        return $entity->getId();
    } else {
        return null;
    }
}

/**
 * @param $edit Edit
 * @return null|string
 */
function putEditEntry($edit) {
    return insert(
        'INSERT INTO EditLog (UserId, EntityId, TagId, PictureId) VALUES (:userid, :entityid, :tagid, :pictureid)',
        [':userid'=>$edit->getUserId(),
            ':entityid'=>($edit->getEntityId() != null) ? $edit->getEntityId() : null,
            ':tagid'=>($edit->getTagId() != null) ? $edit->getTagId() : null,
            ':pictureid'=>($edit->getPictureId() != null) ? $edit->getPictureId() : null]
    );
}

function removeRegistrationCode($regCode) {
    return execute(
        'DELETE FROM RegistrationCodes WHERE Code = :code LIMIT 1',
        ['code'=>$regCode]
    );
}

/**
 * @param PDOException $exception
 */
function handleError($exception) {
    echo $exception->getMessage();
}