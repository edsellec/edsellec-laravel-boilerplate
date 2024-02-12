<?php

namespace Tests;

use App\Data\Auth\AuthData;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Testing\TestResponse as FoundationTestResponse;

class BaseFeatureTest extends TestCase
{
    use WithFaker;

    /**
     * Login and retrieve authentication data.
     *
     * @param string $endpoint The login endpoint.
     * @param array $payload The login payload.
     * @return AuthData The authentication data.
     */
    protected function loginAndGetAuthData(string $endpoint, array $payload): AuthData
    {
        $response = $this->postJson($endpoint, $payload);
        return $this->generateAuthData($response);
    }

    /**
     * Get request headers with Bearer token for authenticated requests.
     *
     * @param string $endpoint The endpoint for which headers are needed.
     * @param array $payload The login payload.
     * @return array The request headers.
     */
    protected function getRequestHeaders(string $endpoint, array $payload): array
    {
        $authData = $this->loginAndGetAuthData($endpoint, $payload);
        return [
            'Authorization' => "Bearer {$authData->accessToken}"
        ];
    }

    /**
     * Login as an admin and retrieve authentication data.
     *
     * @return AuthData The authentication data for the admin.
     */
    protected function loginAsAdmin(): AuthData
    {
        $adminCredentials = [
            'email' => env('ADMIN_USER_EMAIL'),
            'password' => env('ADMIN_USER_PASSWORD'),
        ];

        return $this->loginAndGetAuthData('/api/auth/login', $adminCredentials);
    }

    /**
     * Login as a client and retrieve authentication data.
     *
     * @return AuthData The authentication data for the client.
     */
    protected function loginAsClient(): AuthData
    {
        $clientCredentials = [
            'email' => env('CLIENT_USER_EMAIL'),
            'password' => env('CLIENT_USER_PASSWORD'),
        ];

        return $this->loginAndGetAuthData('/api/auth/login', $clientCredentials);
    }

    /**
     * Generate AuthData object from the response.
     *
     * @param FoundationTestResponse $response The response object.
     * @return AuthData The generated AuthData object.
     */
    private function generateAuthData(FoundationTestResponse $response): AuthData
    {
        $response->assertSuccessful(); // Asserts that the response has a 2xx status code

        $responseContent = json_decode($response->getContent());
        $responseData = $responseContent->data;
        return AuthData::from([
            'id' => $responseData->id,
            'accessToken' => $responseData->accessToken,
        ]);
    }
}
