<?php

class User
{
    /** @ODM\Id */
    private $_id;

    /** @ODM\String @ODM\Index(unique=true, order="asc")*/
    private $username;

    private $password;

    /** @ODM\String */
    private $email;

    /** @ODM\ReferenceMany(targetDocument="Game", cascade="all") */
    private $finishedGames = array();

    private $playingGames = array();

/*
    $c->insert(array("nombre" => "Pedro", "apellido" => "Ruiz" ));
$nuevosdatos = array('$set' => array("direccion" => "Calle Juan, 1"));
$c->update(array("nombre" => "Pedro"), $nuevosdatos);
*/

    private $role;

    private $collection;

    function __construct($collection) {
       $this->collection = $collection;
    }

    public function getID(){
        return $this->_id;
    }

    public function getUsername(){
        return $this->username;
    }

    public function getPassword(){
        return $this->password;
    }

    public function getEmail(){
        return $this->email;
    }

    public function getFinishedGames(){
        return $this->finishedGames;
    }

    public function getPlayingGames(){
        return $this->playingGames;
    }

    public function getRole(){
        return $this->role;
    }

    public function duplicateUsername($username){
        $result = $this->collection->findOne(array('username' => $username));
        if($result){
            return true;
        }else{
            return false;
        }
    }

    public function duplicateEmail($email){
        $result = $this->collection->findOne(array('email' => $email));
        if($result){
            return true;
        }else{
            return false;
        }
    }

    public function setUser($user){
        $this->username = strtolower($user['username']);
        $this->email = strtolower($user['email']);
        $this->password = $user['password'];
        //$this->finishedGames = $user['finishedGames'];
        //$this->playingGames = $user['playingGames'];
        $this->role = 0;
    }

    public function initUser($username){
        $username = strtolower($username);
        $result = $this->collection->findOne(array('username' => $username));
        if ($result) {
            $this->_id = $result['_id'];
            $this->username = $result['username'];
            $this->password = $result['password'];
            $this->email = $result['email'];
            $this->finishedGames = $result['finishedGames'];
            $this->playingGames = $result['playingGames'];
            $this->role = $result['role'];
            return "usuario iniciado";
        }else{
            return null;
        }
    }

    public function newUser($user){
        $this->setUser($user);
        $this->collection->insert(array(
                    "username" => $this->username,
                    "email" => $this->email,
                    "password" => password_hash($this->password, PASSWORD_DEFAULT),
                    "finishedGames" => array(),
                    "playingGames" => array(),
                    "role" => $this->role
                ));
    }

    /*public function save(){
        $result = $this->collection->findOne(array('username' => $this->username));
        $_id = $result['_id'];
        if (!$result) {
            $this->collection->insert(array(
                    "username" => $this->username,
                    "email" => $this->email,
                    "password" => $this->password,
                    "finishedGames" => $this->finishedGames,
                    "playingGames" => $this->playingGames,
                    "role" => $this->role
                ));
            return "new user";
        }else{
            $newInfo = array('$set' => array(
                "username" => $this->username,
                "email" => $this->email,
                "password" => $this->password,
                "finishedGames" => $this->finishedGames,
                "playingGames" => $this->playingGames
            ));
            $this->collection->update(array("_id"=>$_id), $newInfo);
            return "user updated";
        }
    }*/

    public function setUsername($username) {
        $username = strtolower($username);
        $result = $this->collection->findOne(array('username' => $this->username));
        $newInfo = array('$set' => array(
                "username" => $username
            ));
        $this->collection->update(array("_id"=>$result['_id']), $newInfo);
    }
    
    public function setEmail($email) {
        $email = strtolower($email);
        $result = $this->collection->findOne(array('username' => $this->username));
        $newInfo = array('$set' => array(
                "email" => $email
            ));
        $this->collection->update(array("_id"=>$result['_id']), $newInfo);
    }

    public function setPassword($password) {
        $result = $this->collection->findOne(array('username' => $this->username));
        $newInfo = array('$set' => array(
                "password" => password_hash($password, PASSWORD_DEFAULT)
            ));
        $this->collection->update(array("_id"=>$result['_id']), $newInfo);
    }

    public function gameInPlayingList($gameID){
        $result = $this->collection->findOne(array('username' => $this->username));
        if (in_array($gameID, $result['playingGames'])) {
            return true;
        }else{
            return false;
        }
    }

    public function gameInFinishedList($gameID){
        $result = $this->collection->findOne(array('username' => $this->username));
        if (in_array($gameID, $result['finishedGames'])) {
            return true;
        }else{
            return false;
        }
    }
    
    public function addPlayingGame($playingGame) {
        if (!$this->gameInPlayingList($playingGame)) {
            $result = $this->collection->findOne(array('username' => $this->username));
            $playingGames = $result['playingGames'];
            array_push($playingGames, $playingGame);
            $newInfo = array('$set' => array(
                    "playingGames" => $playingGames
                ));
            $this->collection->update(array("_id"=>$result['_id']), $newInfo);
            //se elimina el juego si está en la lista de juegos terminados
            $this->removeFinishedGame($playingGame);
        }
    }

    public function addFinishedGame($finishedGame) {
        if (!$this->gameInFinishedList($finishedGame)) {
            $result = $this->collection->findOne(array('username' => $this->username));
            $finishedGames = $result['finishedGames'];
            array_push($finishedGames, $finishedGame);
            $newInfo = array('$set' => array(
                    "finishedGames" => $finishedGames
                ));
            $this->collection->update(array("_id"=>$result['_id']), $newInfo);
            //se elimina el juego si está en la lista de juegos sin terminar
            $this->removePlayingGame($finishedGame);
        }
    }

    public function removeFinishedGame($finishedGame){
        $result = $this->collection->findOne(array('username' => $this->username));
        $diff = array_diff($result['finishedGames'], array($finishedGame));
        $newInfo = array('$set' => array(
                "finishedGames" => $diff
            ));
        $this->collection->update(array("_id"=>$result['_id']), $newInfo);
    }

    public function removePlayingGame($playingGame){
        $result = $this->collection->findOne(array('username' => $this->username));
        $diff = array_diff($result['playingGames'], array($playingGame));
        $newInfo = array('$set' => array(
                "playingGames" => $diff
            ));
        $this->collection->update(array("_id"=>$result['_id']), $newInfo);        
    }

    public function deleteUser($username){
        return $this->collection->remove(array("username" => $username), array("justOne" => true));
    }

}

/** @ODM\Document */
class Game
{
    /** @ODM\Id */
    //private $id;

    private $collection;

    /** @ODM\String @ODM\UniqueIndex(order="asc")*/
    private $gameID;

    private $gameIMG;

    /** @ODM\String */
    private $gameName;

    private $description;

    /** @ODM\Collection */
    private $developer;

    /** @ODM\Collection */
    private $genres = array();

    /** @ODM\Collection */
    private $subgenres = array();

    private $release;

    /** @ODM\Collection */
    private $platforms = array();

    /** @ODM\Collection */
    private $voices = array();

    /** @ODM\Collection */
    private $texts = array();

    function __construct($collection) {
       $this->collection = $collection;
    }

    public function setGameID($gameID) { $this->gameID = $gameID; save(); }
    public function setGameIMG($gameIMG) { $this->gameIMG = $gameIMG; save(); }
    public function setGameName($gameName) { $this->gameName = $gameName; save(); }
    public function setDeveloper($developer) { $this->developer = $developer; save(); }
    public function setDescription($description){ $this->description = $description; save(); }
    public function setGenres($genre) { $this->genres = $genres; save(); }
    public function setSubgenres($subgenres) { $this->subgenres = $subgenres; save(); }
    public function setRelease($release){ $this->release = $release; save(); }
    public function setPlatforms($platforms) { $this->platforms = $platforms; save(); }
    public function setVoices($voices) { $this->voices = $voices; save(); }
    public function setText($texts) { $this->texts = $texts; save(); }

    public function getGameID() { return $this->gameID; }
    public function getGameIMG() { return $this->gameIMG; }
    public function getGameName() { return $this->gameName; }
    public function getDeveloper() { return $this->developer; }
    public function getDescription(){ return $this->description; }
    public function getGenres() { return $this->genres; }
    public function getSubgenres() { return $this->subgenres; }
    public function getRelease(){ return $this->release; }
    public function getPlatforms() { return $this->platforms; }
    public function getVoices() { return $this->voices; }
    public function getText() { return $this->texts; }

    public function duplicateGame($gameID){
        $result = $this->collection->findOne(array('gameID' => $gameID));
        if($result){
            return true;
        }else{
            return false;
        }
    }

    public function searchGames($searchString, $field = 'gameName'){
        $where = array($field => array('$regex' => new MongoRegex("/^.*$searchString.*/i")));
        return $this->collection->find($where, array('gameID' => true, 'gameIMG' => true, 'gameName' => true));
    }

    public function setGame($game){
        $this->gameID = $game['gameID'];
        $this->gameIMG = $game['gameIMG'];
        $this->gameName = $game['gameName'];
        $this->description = $game['description'];
        $this->developer = $game['developer'];
        $this->genres = $game['genres'];
        $this->subgenres = $game['subgenres'];
        $this->release = $game['release'];
        $this->platforms = $game['platforms'];
        $this->voices = $game['voices'];
        $this->texts = $game['texts'];
    }

    public function initGame($gameID){
        $result = $this->collection->findOne(array('gameID' => $gameID));
        $this->gameID = $result['gameID'];
        $this->gameIMG = $result['gameIMG'];
        $this->gameName = $result['gameName'];
        $this->description = $result['description'];
        $this->developer = $result['developer'];
        $this->genres = $result['genres'];
        $this->subgenres = $result['subgenres'];
        $this->release = $result['release'];
        $this->platforms = $result['platforms'];
        $this->voices = $result['voices'];
        $this->texts = $result['texts'];
    }
    
    public function save(){
        //$this->setGame($game);
        $result = $this->collection->findOne(array('gameID' => $this->gameID));
        $_id = $result['_id'];
        if (!$_id) {
            $this->collection->insert(array(
                    "gameID" => $this->gameID,
                    "gameIMG" => $this->gameIMG,
                    "gameName" => $this->gameName,
                    "description" => $this->description,
                    "developer" => $this->developer,
                    "genres" => $this->genres,
                    "subgenres" => $this->subgenres,
                    "platforms" => $this->platforms,
                    "release" => $this->release,
                    "voices" => $this->voices,
                    "texts" => $this->texts
                ));
        }else{
            $newInfo = array('$set' => array(
                "gameID" => $this->gameID,
                "gameIMG" => $this->gameIMG,
                "gameName" => $this->gameName,
                "description" => $this->description,
                "developer" => $this->developer,
                "genres" => $this->genres,
                "subgenres" => $this->subgenres,
                "platforms" => $this->platforms,
                "release" => $this->release,
                "voices" => $this->voices,
                "texts" => $this->texts
            ));
            $this->collection->update(array("_id"=>$_id), $newInfo);
        }
    }

    public function deleteGame($gameID){
        return $this->collection->remove(array("gameID" => $gameID), array("justOne" => true));
    }

    public function listGames($limit = 0){
        return $this->collection->find(array(), array('gameID' => true, 'gameIMG' => true, 'gameName' => true))->limit($limit);
    }

    public function getGame($gameID){
        return $this->collection->findOne(array('gameID' => $gameID));
    }
    
}

/** @ODM\Document */
class Notice
{
    /** @ODM\Id */
    private $_id;

    /** @ODM\String */
    private $title;

    /** @ODM\String */
    private $noticeBody;

    /** @ODM\String */
    private $source;

    private $noticeIMG;

    function __construct($collection) {
       $this->collection = $collection;
    }

    public function setTitle($title){ $this->title = $title; }
    public function setNoticeBody($noticeBody){ $this->noticeBody = $noticeBody; }
    public function setSource($source){ $this->source = $source; }
    public function setNoticeIMG($noticeIMG){ $this->noticeIMG = $noticeIMG; }

    public function getNotice($notice){
        return $this->collection->findOne(array('_id' => new MongoId($notice)));
    }

    public function listNotices(){
        return $this->collection->find(array(), array('_id' => true, 'noticeIMG' => true, 'title' => true));
    }

    public function initNotice($notice){
        $this->title = $notice['title'];
        $this->noticeIMG = $notice['noticeIMG'];
        $this->noticeBody = $notice['noticeBody'];
        $this->source = $notice['source'];
    }

    public function save(){
        $result = $this->collection->findOne(array('_id' => $this->_id));
        $_id = $result['_id'];
        if (!$_id) {
            $this->collection->insert(array(
                    "title" => $this->title,
                    "noticeBody" => $this->noticeBody,
                    "noticeIMG" => $this->noticeIMG,
                    "source" => $this->source
                ));
        }else{
            $newInfo = array('$set' => array(
                "title" => $this->title,
                "noticeBody" => $this->noticeBody,
                "noticeIMG" => $this->noticeIMG,
                "source" => $this->source
            ));
            $this->collection->update(array("_id"=>$_id), $newInfo);

        }
    }

    public function searchNotices($searchString, $field = 'title'){
        $where = array($field => array('$regex' => new MongoRegex("/^.*$searchString.*/i")));
        return $this->collection->find($where, array('_id' => true, 'noticeIMG' => true, 'title' => true));
    }

    public function deleteNotice($notice){
        return $this->collection->remove(array('_id' => new MongoId($notice)), array("justOne" => true));
    }
}