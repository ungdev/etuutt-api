<?php

namespace App\OpenApi;

use ApiPlatform\Core\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\Core\OpenApi\Model\PathItem;
use ApiPlatform\Core\OpenApi\OpenApi;

/**
 * This class decorates the api documentation sent to the wagger to remove all operation that contains 'hidden' in their summary. This custom OpenApiFactory allows us to hide a mandatory operation (Get on an item).
 */
class OpenApiFactory implements OpenApiFactoryInterface
{
    public function __construct(private OpenApiFactoryInterface $decorated)
    {
    }

    public function __invoke(array $context = []): OpenApi
    {
        $openApi = $this->decorated->__invoke($context);

        /**
         * We iterate through all paths to remove all that contains 'hidden' in their summary.
         *
         * @var PathItem $path
         */
        foreach ($openApi->getPaths()->getPaths() as $key => $path) {
            if ($path->getGet() && 'hidden' === $path->getGet()->getSummary()) {
                $openApi->getPaths()->addPath($key, $path->withGet(null));
            }
        }

        //  We add a part in the "Authorize" section to let the user give its login.
        $schemas = $openApi->getComponents()->getSecuritySchemes();
        $schemas['CAS-Login'] = new \ArrayObject([
            'type' => 'apiKey',
            'in' => 'header',
            'name' => 'CAS-LOGIN',
        ]);
        //  We add the authentification to the swagger security
        return $openApi->withSecurity([[
            'CAS-Login' => [],
        ]]);

        //  We return the version of the documentation with our modifications
    }
}
