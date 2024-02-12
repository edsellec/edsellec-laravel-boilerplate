<?php

namespace Tests\Feature\Example;

use Tests\BaseFeatureTest;

class ItemFeatureTest extends BaseFeatureTest
{
    private string $endpoint = '/api/admin/example/items';

    /**
     * Get item payload.
     *
     * @return array
     */
    private function getItemPayload(): array
    {
        return [
            'name' => $this->faker->name
        ];
    }

    /**
     * Create item and get the id.
     *
     * @param array $payload
     * @return string|null
     */
    private function createAndGetItemId(array $payload): ?string
    {
        $authData = $this->loginAsAdmin();
        $response = $this->withHeaders($authData->getAuthorizationHeader())->postJson($this->endpoint, $payload);
        $responseJson = $response->json();
        $data = $responseJson['data'];

        return $data['id'] ?? null;
    }

    /**
     * @test
     *
     * A basic test in store item.
     *
     * @return void
     */
    public function testStoreItem()
    {
        $auth = $this->loginAsAdmin();
        $payload = $this->getItemPayload();

        $response = $this->withHeaders($auth->getAuthorizationHeader())->postJson($this->endpoint, $payload);
        $response->assertCreated()->assertJson($payload);
    }

    /**
     * @test
     *
     * A basic test in getting items.
     *
     * @return void
     */
    public function testIndexItem()
    {
        $auth = $this->loginAsAdmin();
        $expectedJsonStructure = ['data', 'links', 'meta'];

        $response = $this->withHeaders($auth->getAuthorizationHeader())->getJson($this->endpoint);
        $response->assertOk()->assertJsonStructure($expectedJsonStructure);
    }

    /**
     * @test
     *
     * A basic test in getting item by id.
     *
     * @return void
     */
    public function testShowItem()
    {
        $auth = $this->loginAsAdmin();
        $payload = $this->getItemPayload();
        $itemId = $this->createAndGetItemId($payload);

        $response = $this->withHeaders($auth->getAuthorizationHeader())->getJson("$this->endpoint/$itemId");
        $response->assertOk()->assertJson($payload);
    }

    /**
     * @test
     *
     * A basic test in updating item by id.
     *
     * @return void
     */
    public function testUpdateItem()
    {
        $auth = $this->loginAsAdmin();
        $payload = $this->getItemPayload();
        $itemId = $this->createAndGetItemId($payload);

        $response = $this->withHeaders($auth->getAuthorizationHeader())->putJson("$this->endpoint/$itemId", $payload);
        $response->assertCreated()->assertJson($payload);
    }

    /**
     * @test
     *
     * A basic test in destroying item by id.
     *
     * @return void
     */
    public function testDestroyItem()
    {
        $auth = $this->loginAsAdmin();
        $payload = $this->getItemPayload();
        $itemId = $this->createAndGetItemId($payload);
        $expectedJsonStructure = ['success'];

        $response = $this->withHeaders($auth->getAuthorizationHeader())->deleteJson("$this->endpoint/$itemId");
        $response->assertOk()->assertJsonStructure($expectedJsonStructure);
    }
}