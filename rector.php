<?php

use Rector\Config\RectorConfig;
use Rector\Doctrine\Set\DoctrineSetList;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\Symfony\Set\SymfonyLevelSetList;
use Rector\Symfony\Set\SymfonySetList;

return static function (RectorConfig $rectorConfig): void {

    //  Path to fix
    $rectorConfig->paths([
        __DIR__.'/src',
        __DIR__.'/tests',
    ]);

    //  Giving Rector all Symfony info
    $rectorConfig->symfonyContainerXml(__DIR__.'/var/cache/dev/App_KernelDevDebugContainer.xml');

    //  Rules
    $rectorConfig->sets([
        // LevelSetList::UP_TO_PHP_81, //  Type tous les attributs des objets, mais génère bcp de bug
        SetList::CODE_QUALITY,
        // SetList::CODING_STYLE,
        SetList::DEAD_CODE,
        // SetList::PSR_4,
        // SetList::TYPE_DECLARATION,

        // SymfonyLevelSetList::UP_TO_SYMFONY_62,   //  No bug, assert to php attribute, security path, manual change to do
        // SymfonySetList::SYMFONY_CODE_QUALITY,
        // SymfonySetList::ANNOTATIONS_TO_ATTRIBUTES,

        // DoctrineSetList::DOCTRINE_CODE_QUALITY, //  Also calls SetList::TYPE_DECLARATION
        // DoctrineSetList::ANNOTATIONS_TO_ATTRIBUTES,
    ]);
};
