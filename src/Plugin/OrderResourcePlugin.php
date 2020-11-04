<?php


namespace Boldy\SyliusExportPlugin\Plugin;


use Boldy\SyliusExportPlugin\ORM\Hydrator\HydratorInterface;
use Boldy\SyliusExportPlugin\Service\AddressConcatenationInterface;
use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Core\Model\OrderInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Shipping\Model\ShipmentInterface;
use Symfony\Component\Intl\Countries;
use Symfony\Component\PropertyAccess\PropertyAccessorInterface;


class OrderResourcePlugin extends ResourcePlugin
{
    /** @var AddressConcatenationInterface */
    private $addressConcatenation;

    public function __construct(
        RepositoryInterface $repository,
        PropertyAccessorInterface $propertyAccessor,
        EntityManagerInterface $entityManager,
        AddressConcatenationInterface $addressConcatenation
    )
    {
        parent::__construct($repository, $propertyAccessor, $entityManager);

        $this->addressConcatenation = $addressConcatenation;
    }

    public function init(array $idsToExport): void
    {
        parent::init($idsToExport);

        /** @var OrderInterface $resource */
        foreach ($this->resources as $resource)
        {
            // insert general fields
            $this->addGeneralData($resource);

            // insert customer information to specific fields
            $this->addCustomerData($resource);

            $this->addShippingMethodsData($resource);

            // insert shippingaddress to the specific field
            $this->addShippingAddressData($resource);

            // insert billingaddress to the specific field
            $this->addBillingAddressData($resource);

            // insert lastpayment to the specific field
            $this->addLastPaymentData($resource);

            // insert promotion to the specific field
            $this->addPromotionData($resource);
        }
    }

    private function addGeneralData(OrderInterface $resource): void
    {
        $this->addDataForResource($resource, 'Id', $resource->getId());
        $this->addDataForResource($resource, 'Number', $resource->getNumber());
        $this->addDataForResource($resource, 'Total', $resource->getTotal());
        $this->addDataForResource($resource, 'Shipping_total', $resource->getShippingTotal());
        $this->addDataForResource($resource, 'Shipping_state', $resource->getShippingState());
    }

    private function addCustomerData(OrderInterface $resource): void
    {
        $customer = $resource->getCustomer();

        if (null === $customer) {
            return;
        }

        $this->addDataForResource($resource, 'Customer_id', $customer->getId());
        $this->addDataForResource($resource, 'Customer_groups', $customer->getGroup()->getCode());
        $this->addDataForResource($resource, 'Customer_new', 0);
    }

    private function addShippingAddressData(OrderInterface $resource): void
    {
        $shippingAddress = $resource->getShippingAddress();

        if (null === $shippingAddress) {
            return;
        }

        $shippingInfoString = $this->addressConcatenation->getString($shippingAddress);

        $this->addDataForResource($resource, 'Shipping_address', $shippingInfoString);
        $this->addDataForResource($resource, 'Shipping_postCode', $shippingAddress->getPostcode());
        $this->addDataForResource($resource, 'Shipping_city', $shippingAddress->getCity());
        $this->addDataForResource($resource, 'Shipping_country', Countries::getName($shippingAddress->getCountryCode()));
        $this->addDataForResource($resource, 'Shipping_company', $shippingAddress->getCompany());
    }

    private function addBillingAddressData(OrderInterface $resource): void
    {
        $billingAddress = $resource->getBillingAddress();

        if (null === $billingAddress) {
            return;
        }

        $billingInfoString = $this->addressConcatenation->getString($billingAddress);

        $this->addDataForResource($resource, 'Billing_address', $billingInfoString);
    }

    private function addLastPaymentData(OrderInterface $resource)
    {
        $lastPayment = $resource->getLastPayment();

        if (null === $lastPayment) {
            return;
        }

        $this->addDataForResource($resource, 'Payment_date', $lastPayment->getCreatedAt());
        $this->addDataForResource($resource, 'Payment_type', $lastPayment->getMethod()->getName());
    }

    private function addPromotionData(OrderInterface $resource)
    {
        $promotions = $resource->getPromotions();

        if ($promotions->isEmpty())
        {
            return;
        }

        $promotionsName = array_map(function ($promotion) {
            return $promotion->getName();
        }, $promotions->toArray());

        $this->addDataForResource($resource, 'Voucher_name', implode(',', $promotionsName));
        $this->addDataForResource($resource, 'Voucher_total', $resource->getOrderPromotionTotal());
    }

    private function addShippingMethodsData(OrderInterface $resource)
    {
        $shipments = $resource->getShipments();

        if ($shipments->isEmpty())
        {
            return;
        }

        $shipmentsName = array_map(function (ShipmentInterface $shipment) {
            return $shipment->getMethod()->getName();
        }, $shipments->toArray());

        $this->addDataForResource($resource, 'Shipping_method', implode(',', $shipmentsName));

    }
}
