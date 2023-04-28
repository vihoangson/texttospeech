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

    public function doConvert() {
        if (!$this->key) {
            throw new Exception('Please insert key');
        }
        $text = $this->text;
        $curl = curl_init();
        $text = trim($text);
        if ($text === '') {
            return null;
        }
        $text_name = time() . rand(0, 10000);
        $lang      = 'vi-VN';
        // $lang = 'en';
        // $lang = 'ja';

        $key = $this->key;

        curl_setopt_array($curl, [
            CURLOPT_URL            => "https://content-texttospeech.googleapis.com/v1beta1/text:synthesize?alt=json&key=" . $key,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => "POST",
            CURLOPT_POSTFIELDS     => "{\"audioConfig\":{\"audioEncoding\":\"MP3\",\"speakingRate\":1,\"volumeGainDb\":0},\"input\":{\"text\":\"" . $text . "\"},\"voice\":{\"languageCode\":\"vi-VN\",\"name\":\"vi-VN-Wavenet-D\"}}",
            CURLOPT_HTTPHEADER     => [
                "authority: content-texttospeech.googleapis.com",
                "x-goog-encode-response-if-executable: base64",
                "x-origin: https://explorer.apis.google.com",
                "x-clientdetails: appVersion=5.0%20(Windows%20NT%2010.0%3B%20Win64%3B%20x64)%20AppleWebKit%2F537.36%20(KHTML%2C%20like%20Gecko)%20Chrome%2F85.0.4183.102%20Safari%2F537.36&platform=Win32&userAgent=Mozilla%2F5.0%20(Windows%20NT%2010.0%3B%20Win64%3B%20x64)%20AppleWebKit%2F537.36%20(KHTML%2C%20like%20Gecko)%20Chrome%2F85.0.4183.102%20Safari%2F537.36",
                "user-agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/85.0.4183.102 Safari/537.36",
                "content-type: application/json",
                "x-requested-with: XMLHttpRequest",
                "x-javascript-user-agent: apix/3.0.0 google-api-javascript-client/1.1.0",
                "x-referer: https://explorer.apis.google.com",
                "accept: */*",
                "origin: https://content-texttospeech.googleapis.com",
                "x-client-data: CI22yQEIorbJAQjBtskBCKmdygEImbXKAQj1x8oBCOfIygEI6cjKAQisycoBCLTLygEY6r/KAQ==",
                "sec-fetch-site: same-origin",
                "sec-fetch-mode: cors",
                "sec-fetch-dest: empty",
                "referer: https://content-texttospeech.googleapis.com/static/proxy.html?usegapi=1&jsh=m%3B%2F_%2Fscs%2Fapps-static%2F_%2Fjs%2Fk%3Doz.gapi.vi.GvGQknA8E1g.O%2Fam%3DwQE%2Fd%3D1%2Fct%3Dzgms%2Frs%3DAGLTcCMsrxwKQn_wxHYgIxdxdRd5qnmjng%2Fm%3D__features__",
                "accept-language: vi-VN,vi;q=0.9,fr-FR;q=0.8,fr;q=0.7,en-US;q=0.6,en;q=0.5"
            ],
        ]);
        $response = curl_exec($curl);

        curl_close($curl);

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
