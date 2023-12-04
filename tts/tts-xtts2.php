<?php

function tts($textString, $mood = "default", $stringforhash) {
    $startTime = microtime(true);

    $url = "http://localhost:8020/tts_to_audio/";

    // Request headers
    $headers = array(
        'Accept: application/json',
        'Content-Type: application/json'
    );

    // Request data
    $data = array(
        'text' => $textString,
        'speaker_wav' => 'scarlett24000.wav', // Replace with your actual speaker or configuration
        'language' => 'en'
    );

    // Create context options
    $options = array(
        'http' => array(
            'method' => 'POST',
            'header' => implode("\r\n", $headers),
            'content' => json_encode($data)
        )
    );

    // Create context
    $context = stream_context_create($options);

    // Send the request
    $response = file_get_contents($url, false, $context);

    // Handle the response
    if ($response !== false) {
        // Handle the successful response
        $size = strlen($response);
        $audioFilePath = dirname((__FILE__)) . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "soundcache/" . md5(trim($stringforhash)) . ".wav";
        file_put_contents($audioFilePath, $response); // Save the audio response to a file

        $debugInfo = trim($textString) . "\n\rTotal call time: " . (microtime(true) - $startTime) . " ms\n\rSize of wav: " . $size . " bytes\n\rFunction tts($textString,$mood,$stringforhash)";
        file_put_contents($audioFilePath . ".txt", $debugInfo);

        return "soundcache/" . md5(trim($stringforhash)) . ".wav";
    } else {
        // Handle the error response
        $errorInfo = trim($textString) . print_r($http_response_header, true);
        file_put_contents(dirname((__FILE__)) . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "soundcache/" . md5(trim($stringforhash)) . ".err", $errorInfo);
        return false;
    }
}