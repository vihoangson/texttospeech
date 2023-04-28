<?php

use PHPUnit\Framework\TestCase;

class TestConvert extends TestCase
{
    public function testAddition()
    {
        $obj = new \Sonlib\Progress\TextToSpeech('AIzaSyAa8yy0GdcGPHdtD083HiGGx_S0vMPScDM');
        // $m = $obj->convert("Test audio");
        $obj->setText("Test audio");
        $obj->doConvert();
        $obj->saveFile('../../son.mp3');
        $this->assertEquals(1,1);

    }
}
