<?php

declare(strict_types=1);

namespace Boldy\SyliusExportPlugin;

use Sylius\Bundle\CoreBundle\Application\SyliusPluginTrait;
use Symfony\Component\HttpKernel\Bundle\Bundle;

final class BoldySyliusExportPlugin extends Bundle
{
    use SyliusPluginTrait;
}
