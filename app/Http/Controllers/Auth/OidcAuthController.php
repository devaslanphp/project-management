<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use App\Settings\GeneralSettings;
use Illuminate\Http\Request;
use League\OAuth2\Client\Provider\Exception\IdentityProviderException;
use League\OAuth2\Client\Provider\GenericProvider;

class OidcAuthController extends Controller
{
    private $client;

    public function __construct()
    {
        $this->client = new GenericProvider([
            'clientId' => config('services.oidc.client_id'),
            'clientSecret' => config('services.oidc.client_secret'),
            'redirectUri' => config('services.oidc.redirect_uri'),
            'urlAuthorize' => config('services.oidc.url_authorize'),
            'urlAccessToken' => config('services.oidc.url_access_token'),
            'urlResourceOwnerDetails' => config('services.oidc.url_resource_owner_details'),
            'scopes' => config('services.oidc.scope')
        ]);
    }

    public function redirect()
    {
        $authUrl = $this->client->getAuthorizationUrl();
        return redirect($authUrl);
    }

    public function callback(Request $request)
    {
        try {
            $accessToken = $this->client->getAccessToken('authorization_code', [
                'code' => $request->input('code')
            ]);
            $user = $this->client->getResourceOwner($accessToken);

            // Perform any additional validation or user creation here
            if ($user) {
                $data = $user->toArray();
                $user = User::where('email', $data['email'])->first();
                if (!$user) {
                    $user = User::create([
                        'name' => $data['given_name'] . ' ' . $data['family_name'],
                        'email' => $data['email'],
                        'oidc_username' => $data['preferred_username'],
                        'email_verified_at' => $data['email_verified'] ? now() : null,
                        'type' => 'oidc',
                        'oidc_sub' => $data['sub'],
                        'password' => null
                    ]);
                    $defaultRoleSettings = app(GeneralSettings::class)->default_role;
                    if ($defaultRoleSettings && $defaultRole = Role::where('id', $defaultRoleSettings)->first()) {
                        $user->syncRoles([$defaultRole]);
                    }
                } else {
                    $user->update([
                        'name' => $data['given_name'] . ' ' . $data['family_name'],
                        'email' => $data['email'],
                        'oidc_username' => $data['preferred_username'],
                        'type' => 'oidc',
                        'oidc_sub' => $data['sub'],
                        'password' => null
                    ]);
                    $user->refresh();
                }

                // Log the user in
                auth()->login($user);

                return redirect()->intended();
            }
            session()->flash('oidc_error');
            return redirect()->route('login');
        } catch (IdentityProviderException $e) {
            session()->flash('oidc_error');
            return redirect()->route('login');
        }
    }
}
