<?php
use DarkPHP\HTTPMonster;

require_once 'vendor/autoload.php';
include 'config/database.php';

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST");
header("Access-Control-Allow-Headers: Content-Type");

$message = array();
$text = 'Hello';
$api_check = '';

if ($_SERVER['REQUEST_METHOD'] === "POST") {
    $api_check = $db->select("`keys`", "*", array("`key`" => $_POST['key']));
    if (!isset($_POST['text']) || empty($_POST['text']) && !isset($_POST['key']) || empty($_POST['key'])) {
        $message = [
            'status' => false,
            'code' => 400,
            'message' => 'Please enter text and key parametr'
        ];
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(400);
        echo json_encode($message, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } else if (empty($api_check)) {
        $message = [
            'status' => false,
            'code' => 401,
            'message' => 'API Key not found'
        ];
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(401);
        echo json_encode($message, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } else {

        $text = $_POST['text'];

        $headers = array(
            'Host: us-central1-chat-for-chatgpt.cloudfunctions.net',
            'Connection: keep-alive',
            'Accept: */*',
            'User-Agent: com.tappz.aichat/1.2.2 iPhone/16.3.1 hw/iPhone12_5',
            'Accept-Language: ar',
            'Content-Type: application/json; charset=UTF-8'
        );

        $data = array(
            'data' => array(
                'message' => $text
            )
        );

        $monster = new HTTPMonster();
        $response = $monster->Method('POST')
            ->Url('https://us-central1-chat-for-chatgpt.cloudfunctions.net/basicUserRequestBeta')
            ->Headers($headers)
            ->Body(json_encode($data))
            ->Send();

        $list = json_decode($response, true);
        if ($list["result"]["choices"]["0"]["text"]) {
            $result = $list["result"]["choices"]["0"]["text"];
            $message = [
                'status' => true,
                'code' => 200,
                'message' => $result
            ];
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($message, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } else {
            $message = [
                'status' => false,
                'code' => 403,
                'message' => 'Error connecting to openai'
            ];
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(403);
            echo json_encode($message, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }
} else {
    $api_check = $db->select("`keys`", "*", array("`key`" => $_GET['key']));
    if (!isset($_GET['text']) || empty($_GET['text']) && !isset($_GET['key']) || empty($_GET['key'])) {
        $message = [
            'status' => false,
            'code' => 400,
            'message' => 'Please enter text and key parametr'
        ];
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(400);
        echo json_encode($message, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } else if (empty($api_check)) {
        $message = [
            'status' => false,
            'code' => 401,
            'message' => 'API Key not found'
        ];
        header('Content-Type: application/json; charset=utf-8');
        http_response_code(401);
        echo json_encode($message, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    } else {

        $text = $_GET['text'];

        $headers = array(
            'Host: us-central1-chat-for-chatgpt.cloudfunctions.net',
            'Connection: keep-alive',
            'Accept: */*',
            'User-Agent: com.tappz.aichat/1.2.2 iPhone/16.3.1 hw/iPhone12_5',
            'Accept-Language: ar',
            'Content-Type: application/json; charset=UTF-8'
        );

        $data = array(
            'data' => array(
                'message' => $text
            )
        );

        $monster = new HTTPMonster();
        $response = $monster->Method('POST')
            ->Url('https://us-central1-chat-for-chatgpt.cloudfunctions.net/basicUserRequestBeta')
            ->Headers($headers)
            ->Body(json_encode($data))
            ->Send();

        $list = json_decode($response, true);
        if ($list["result"]["choices"]["0"]["text"]) {
            $result = $list["result"]["choices"]["0"]["text"];
            $message = [
                'status' => true,
                'code' => 200,
                'message' => $result
            ];
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode($message, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        } else {
            $message = [
                'status' => false,
                'code' => 403,
                'message' => 'Error connecting to openai'
            ];
            header('Content-Type: application/json; charset=utf-8');
            http_response_code(403);
            echo json_encode($message, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        }
    }
}
?>