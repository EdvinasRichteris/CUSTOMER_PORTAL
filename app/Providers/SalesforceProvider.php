<?php

namespace App\Services\Provider;

use Laravel\Socialite\Two\AbstractProvider;
use Laravel\Socialite\Two\ProviderInterface;
use Laravel\Socialite\Two\User;

class SalesforceProvider extends AbstractProvider implements ProviderInterface
{
    protected $scopes = ['YOUR_DEFAULT_SALESFORCE_SCOPES_HERE'];

    protected function getAuthUrl($state)
    {
        return $this->buildAuthUrlFromBase(
            'https://login.salesforce.com/services/oauth2/authorize',
            $state
        );
    }

    protected function getTokenUrl()
    {
        return 'https://login.salesforce.com/services/oauth2/token';
    }

    protected function getUserByToken($token)
    {
        $response = $this->getHttpClient()->get('YOUR_SALESFORCE_API_ENDPOINT_TO_GET_USER_INFO', [
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
        ]);

        return json_decode($response->getBody(), true);
    }

    protected function mapUserToObject(array $user)
    {
        return (new User)->setRaw($user)->map([
            'id' => $user['id'], // Assuming Salesforce sends the user ID in 'id'
            'name' => $user['name'], // Or any other field Salesforce provides
            'email' => $user['email'], // And so on...
            'avatar' => $user['picture'], // This is just an example. Adjust based on actual data.
        ]);
    }

    protected function getTokenFields($code)
    {
        return array_merge(parent::getTokenFields($code), [
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => $this->redirectUrl,
            'grant_type' => 'authorization_code',
        ]);
    }
}
