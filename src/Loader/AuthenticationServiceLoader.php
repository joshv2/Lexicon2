<?php
declare(strict_types=1);

namespace App\Loader;

use Cake\Core\Configure;
use CakeDC\Auth\Authentication\AuthenticationService;
use CakeDC\Auth\Authentication\TwoFactorProcessorLoader;
use Psr\Http\Message\ServerRequestInterface;

class AuthenticationServiceLoader
{
    /**
     * Create the authentication service.
     *
     * This loader avoids calling AuthenticationService::loadIdentifier(), which is deprecated
     * in cakephp/authentication >= 3.3.
     */
    public function __invoke(ServerRequestInterface $request): AuthenticationService
    {
        $processors = TwoFactorProcessorLoader::processors();

        $identifiers = $this->normalizeIdentifiers((array)Configure::read('Auth.Identifiers'));
        $authenticators = (array)Configure::read('Auth.Authenticators');

        $authenticators = $this->injectIdentifierConfig($authenticators, $identifiers);

        $service = new AuthenticationService([
            'processors' => $processors,
            // Keep the global identifier collection empty to avoid deprecation warnings.
            'identifiers' => [],
            // Configure authenticators up-front; each authenticator receives its own identifier config.
            'authenticators' => $authenticators,
        ]);

        // Force authenticator collection initialization while the service identifier registry is still empty.
        // This prevents Authentication 3.3+ from emitting the loadIdentifier() deprecation warning.
        $service->authenticators();

        $this->loadTwoFactorAuthenticator($service, $processors);

        // CakeDC/Users LoginComponent expects certain identifiers (eg. `Password`) to be present
        // in the service identifier registry for password rehashing.
        // Load them *after* authenticators are initialized to avoid the 3.3+ deprecation warnings.
        $this->loadPasswordRehashIdentifiers($service, $identifiers);

        return $service;
    }

    /**
     * Inject `identifier` config into each authenticator so identifiers are not loaded globally.
     *
     * @param array<string, mixed> $authenticators Authenticator configs from `Auth.Authenticators`.
     * @param array<string, mixed> $identifiers Identifier configs from `Auth.Identifiers`.
     * @return array<string, mixed>
     */
    protected function injectIdentifierConfig(array $authenticators, array $identifiers): array
    {
        if ($identifiers === []) {
            return $authenticators;
        }

        foreach ($authenticators as $key => $item) {
            if (!is_array($item)) {
                continue;
            }
            // Respect user-supplied per-authenticator identifier config.
            if (!empty($item['identifier'])) {
                continue;
            }

            // Preserve existing behaviour (shared identifiers available to all authenticators)
            // while avoiding the deprecated global identifier loading.
            $item['identifier'] = $identifiers;
            $authenticators[$key] = $item;
        }

        return $authenticators;
    }

    /**
     * Ensure identifier configs include a className.
     *
     * CakeDC/Users provides this by default, but app overrides can accidentally
     * replace parts of the array and drop `className`, which then causes
     * "Unknown object `Password`" errors at login.
     *
     * @param array<string, mixed> $identifiers
     * @return array<string, mixed>
     */
    protected function normalizeIdentifiers(array $identifiers): array
    {
        $defaults = [
            'Password' => 'Authentication.Password',
            'Token' => 'Authentication.Token',
            'Social' => 'CakeDC/Users.Social',
        ];

        foreach ($defaults as $alias => $className) {
            if (!isset($identifiers[$alias]) || !is_array($identifiers[$alias])) {
                continue;
            }
            if (empty($identifiers[$alias]['className'])) {
                $identifiers[$alias]['className'] = $className;
            }
        }

        return $identifiers;
    }

    /**
     * Load identifiers referenced by CakeDC/Users password rehashing.
     *
     * CakeDC/Users reads `Auth.PasswordRehash.identifiers` (default: ['Password']) and then calls
     * `$service->identifiers()->get($name)`. With the new per-authenticator identifier config approach,
     * the service identifier collection is intentionally empty; we populate just the required identifiers
     * here to keep CakeDC/Users behaviour working.
     *
     * @param \CakeDC\Auth\Authentication\AuthenticationService $service
     * @param array<string, mixed> $identifiers
     * @return void
     */
    protected function loadPasswordRehashIdentifiers(AuthenticationService $service, array $identifiers): void
    {
        $names = (array)Configure::read('Auth.PasswordRehash.identifiers');
        if ($names === []) {
            return;
        }

        $defaults = [
            'Password' => 'Authentication.Password',
            'Token' => 'Authentication.Token',
            'Social' => 'CakeDC/Users.Social',
        ];

        foreach ($names as $name) {
            if (!is_string($name) || $name === '') {
                continue;
            }
            if ($service->identifiers()->has($name)) {
                continue;
            }

            $config = [];
            $className = $defaults[$name] ?? $name;

            if (isset($identifiers[$name]) && is_array($identifiers[$name])) {
                $config = $identifiers[$name];
                $className = $config['className'] ?? $className;
                unset($config['className']);
            }

            // Using the full class name (e.g. Authentication.Password) ensures the loaded alias becomes
            // the short name (e.g. Password), which matches CakeDC/Users' expectations.
            $service->identifiers()->load($className, $config);
        }
    }

    /**
     * Load the CakeDC/Auth.TwoFactor authenticator when any 2FA processor is enabled.
     *
     * @param \CakeDC\Auth\Authentication\AuthenticationService $service Authentication service.
     * @param \CakeDC\Auth\Authentication\TwoFactorProcessorCollection $processors TwoFactorProcessors collection.
     * @return void
     */
    protected function loadTwoFactorAuthenticator(AuthenticationService $service, $processors): void
    {
        if (collection($processors)->some(fn($processor) => $processor->enabled())) {
            $service->loadAuthenticator('CakeDC/Auth.TwoFactor', [
                'skipTwoFactorVerify' => true,
            ]);
        }
    }
}
