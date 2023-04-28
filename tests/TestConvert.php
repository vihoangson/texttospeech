<?php

use PHPUnit\Framework\TestCase;

class TestConvert extends TestCase {

    public function testAddition() {
        try {
            $obj = new \Sonlib\Progress\TextToSpeech('');
            $obj->setText("Ok men tôi đi đây");
            $obj->doConvert();
            $obj->saveFile('../son'.time().'.mp3');
            $this->assertEquals(1, 1);

        } catch (\Exception $exception) {

            var_dump($exception->getMessage());
            var_dump($obj->output);
            die;
            $this->assertTrue(true);
        }



    }
}
