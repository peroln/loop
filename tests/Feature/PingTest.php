<?php

namespace Tests\Feature;

use Tests\TestCase;

class PingTest extends TestCase
{
    private string $pingUrl = '/api/ping';

    private string $testParamValue = 'test_param_value';

    public function testPingGet(): void
    {
        $response = $this->get($this->pingUrl);

        $response->assertOk();
    }

    public function testPingPost(): void
    {
        $response = $this->post($this->pingUrl, ['test_param' => $this->testParamValue]);

        $response->assertCreated();
        $this->assertEquals($this->testParamValue, $response->json('test_param'));
    }

    public function testPingPut(): void
    {
        $response = $this->put($this->pingUrl, ['test_param' => $this->testParamValue]);

        $response->assertOk();
        $this->assertEquals($this->testParamValue, $response->json('test_param'));
    }

    public function testPingDelete(): void
    {
        $response = $this->delete($this->pingUrl);

        $response->assertNoContent();
    }
}
