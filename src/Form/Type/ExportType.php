<?php


namespace Boldy\SyliusExportPlugin\Form\Type;


use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;

class ExportType extends AbstractType
{

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * ExportType constructor.
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $gabarits = $this->container->getParameter('boldy.gabarits');

        $gabaritsKeys = array_keys($gabarits);

        $gabaritsKeys = array_combine($gabaritsKeys, $gabaritsKeys);

        $builder->add('export_type', ChoiceType::class, [
            'mapped' => false,
            'choices' => $gabaritsKeys,
            'choice_translation_domain' => 'messages',
            'translation_domain' => 'BoldySyliusExportPlugin'
        ])
        ->add('export_format', ChoiceType::class, [
            'mapped' => false,
            'choices' => [],
            'attr' => [
                'data-gabarits' => json_encode($gabarits)
            ]
        ])
        ->add('date_start', DateType::class, [
            'mapped' => false,
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
        ])
        ->add('date_end', DateType::class, [
            'mapped' => false,
            'widget' => 'single_text'
        ])
        ;
    }
}
