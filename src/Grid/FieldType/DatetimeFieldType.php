<?php

declare(strict_types=1);

namespace Synolia\SyliusSchedulerCommandPlugin\Grid\FieldType;

use Sylius\Component\Grid\DataExtractor\DataExtractorInterface;
use Sylius\Component\Grid\Definition\Field;
use Sylius\Component\Grid\FieldTypes\FieldTypeInterface;
use Sylius\Component\Locale\Context\LocaleContextInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

final class DatetimeFieldType implements FieldTypeInterface
{
    /** @var DataExtractorInterface */
    private $dataExtractor;

    /** @var LocaleContextInterface */
    private $localeContext;

    public function __construct(DataExtractorInterface $dataExtractor, LocaleContextInterface $localeContext)
    {
        $this->dataExtractor = $dataExtractor;
        $this->localeContext = $localeContext;
    }

    /**
     * {@inheritDoc}
     */
    public function render(Field $field, $data, array $options): string
    {
        $value = $this->dataExtractor->get($field, $data);
        if (!$value instanceof \DateTimeInterface) {
            return '';
        }

        /** @var \IntlDateFormatter|null $fmt */
        $fmt = \datefmt_create($this->localeContext->getLocaleCode(), $options['date_format'], $options['time_format']);

        if (!$fmt instanceof \IntlDateFormatter) {
            return '';
        }

        /** @phpstan-ignore-next-line  */
        return $fmt->format($value) ?: '';
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'format' => 'Y-m-d H:i:s',
            'date_format' => \IntlDateFormatter::SHORT,
            'time_format' => \IntlDateFormatter::SHORT,
        ]);
        $resolver->setAllowedTypes('format', 'string');
        $resolver->setAllowedTypes('date_format', 'integer');
        $resolver->setAllowedTypes('time_format', 'integer');
    }
}
