<?php
define("ROOT", dirname(__DIR__));
require_once ROOT."/bootstrap.php";
use \Firebase\JWT\JWT;

/**
 * takes jwt token and returns the decoded associotive array
 * @param string $token
 * @return array
 */
function decodeJWTToken(string $token)
{
    $key = "iets wat nog kan ofzo";
    return (array) JWT::decode($token, $key, array('HS256'));
}

/**
 * returns encoded assoc array as a jwt token
 * @param array $token
 * @return string
 */
function encodeToken(array $token)
{
    $key = "iets wat nog kan ofzo";
    return JWT::encode($token, $key);
}

function encodeJWTUser(int $id){
    $key = "iets wat nog kan ofzo";
    return JWT::encode(array("userId" => $id), $key);
}

/**
 * decodes jwt token and returns null on not found or failure or returns user in case of succes.
 * The user is decoded from the jwt token on the userId value in the token.
 * The entitymanagers is supplied because when we want to use the same as a relation in another object we have to use
 * the same manager as the one that retrieved the user.
 * @param string $token
 * @param \Doctrine\ORM\EntityManager $em
 * @return object|null
 */
function decodeJWTUser(string $token, \Doctrine\ORM\EntityManager $em)
{
    $key = "iets wat nog kan ofzo";
    $decodedTokenAssoc =  (array) JWT::decode($token, $key, array('HS256'));
    if(isset($decodedTokenAssoc["userId"])){
        try {

            return $em->find("User", $decodedTokenAssoc["userId"]);
        } catch(Exception $e) {
            return null;
        }
    }
    return null;
}