<?php

namespace App\OpenApi;

use ApiPlatform\OpenApi\Factory\OpenApiFactoryInterface;
use ApiPlatform\OpenApi\Model\PathItem;
use ApiPlatform\OpenApi\OpenApi;

/**
 * This class decorates and add modifications to the api documentation sent to the swagger. We use it to remove all operation that contains `hidden` in their summary, to hide a mandatory operation (Get on an item), and to add a way to log by the swagger.
 */
class OpenApiFactory implements OpenApiFactoryInterface
{
    public function __construct(private readonly OpenApiFactoryInterface $decorated)
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
        //  We add the authentification to the swagger security, and we return this version of the documentation with our modifications.
        return $openApi->withSecurity([[
            'CAS-Login' => [],
        ]]);
    }
}
