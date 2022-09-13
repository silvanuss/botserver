<?php
require_once('autoload.php');
if($_GET['query']) {
    $respons = $Killbot->redirect( $config['apikey'] , $_GET['query']);
    $json    = $Killbot->json($respons);
    if($json['data']['block_access'] == true){
        $Killbot->error($json['data']['direct_url']);
    }
    if(empty($json['data']['direct_url'])){
        $Killbot->error('self_404');
    }
    // Redirection with UTM tracking
    list($_, $tracking) = explode('?', $_SERVER['REQUEST_URI']);
    if(empty($tracking)) {
        die(header("Location: " . $json['data']['direct_url']));
    }
    $direction = preg_match('/\?/i', $json['data']['direct_url']) ? $json['data']['direct_url'] . '&' . $tracking : $json['data']['direct_url'] . '?' . $tracking;
    die(header("Location: " . $direction));
} else {
    $Killbot->error('self_404');
}