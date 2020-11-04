<?php

declare(strict_types=1);

namespace Boldy\SyliusExportPlugin\Service;

use Sylius\Component\Core\Model\AddressInterface;

interface AddressConcatenationInterface
{
    public function getString(AddressInterface $address): string;
}
