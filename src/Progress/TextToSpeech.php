<?php

namespace Sonlib\Progress;

use Exception;

/**
 *
 * @property string $text
 */
class TextToSpeech {

    private $key;
    /**
     * @var false|string
     */
    private $source;

    public function __construct($key) {
        $this->key = $key;
    }

    public function setText(string $string) {
        $this->text = $string;
    }

    public function doConvertByOwnKey(){
        if (!$this->key) {
            throw new Exception('Please insert key');
        }

        $access_token = $this->key;

        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL            => 'https://texttospeech.googleapis.com/v1/text:synthesize',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => '',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_POSTFIELDS     => '{
        "input": {
          "text":"'.$this->text.'"
    },
        "voice": {
          "languageCode":"vi-VN",
          "name":"vi-VN-Wavenet-A",
          "ssmlGender":"FEMALE"
    },
        "audioConfig": {
          "audioEncoding":"MP3"
    }
}',
            CURLOPT_HTTPHEADER     => [
                'Authorization: Bearer '.$access_token,
                'Content-Type: application/json; charset=utf-8'
            ],
        ]);

        $response = curl_exec($curl);

        curl_close($curl);
        $this->output = json_decode($response);
        if (empty(json_decode($response)->audioContent)) {
            throw new Exception('Don\'t have response');
        }
        if (isset(json_decode($response)->audioContent)) {
            $f = base64_decode(json_decode($response)->audioContent);
            $this->source = $f;


        } else {
            header("HTTP/1.0 404 Not Found");
        }

    }

    public function doConvert() {
        return $this->doConvertByOwnKey();

    }

    public function saveFile(string $string = null) {

        header('Content-Type: audio/mpeg');
        header('Content-Disposition: inline; filename="mp3_file.mp3"');
        // header('Content-length: '. sizeof($f));
        header('Cache-Control: no-cache');
        header('Content-Transfer-Encoding: chunked');
        if($string === null){
            $fileName = md5($this->text) . '.mp3';
        }else{
            $fileName = $string;
        }
        if (!file_exists($fileName)) {
            file_put_contents($fileName, $this->source);
        } else {

        }

        return $fileName;
    }
}
