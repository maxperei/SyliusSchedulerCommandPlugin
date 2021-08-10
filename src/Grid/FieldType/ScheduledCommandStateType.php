<?php

declare(strict_types=1);

namespace Synolia\SyliusSchedulerCommandPlugin\Grid\FieldType;

use Sylius\Component\Grid\Definition\Field;
use Sylius\Component\Grid\FieldTypes\FieldTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;

final class ScheduledCommandStateType implements FieldTypeInterface
{
    /** @var Environment */
    private $twig;

    public function __construct(
        UrlGeneratorInterface $urlGenerator,
        Environment $twig,
        string $logsDir
    ) {
        $this->urlGenerator = $urlGenerator;
        $this->twig = $twig;
        $this->logsDir = $logsDir;
    }

    /**
     * {@inheritdoc}
     */
    public function render(Field $field, $scheduleCommand, array $options): string
    {
        return $this->twig->render(
            $options['template'],
            [
                'schedulerCommand' => $scheduleCommand,
            ]
        );
    }

    /** {@inheritdoc} */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setRequired('template');
    }
}
