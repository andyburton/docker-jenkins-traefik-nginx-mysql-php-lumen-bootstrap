<?php

class ExampleTest extends TestCase
{
    public function testMyApi()
    {
        $this->get('/api/v1/service')
            ->seeJSON([
                "api_version" => "1.0.0"
            ]);
    }
}