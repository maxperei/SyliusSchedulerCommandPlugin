<?php

declare(strict_types=1);

namespace Synolia\SyliusSchedulerCommandPlugin\Grid\FieldType;

use Sylius\Component\Grid\Definition\Field;
use Sylius\Component\Grid\FieldTypes\FieldTypeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ScheduledCommandExecutionTimeType implements FieldTypeInterface
{
    private const HOUR_IN_SECONDES = 3600;

    private const MINUTE_IN_SECONDES = 60;

    /**
     * {@inheritdoc}
     */
    public function render(Field $field, $scheduleCommand, array $options): string
    {
        if ($scheduleCommand->getCommandEndTime() === null || $scheduleCommand->getExecutedAt() === null) {
            return '';
        }

        $time = $scheduleCommand->getCommandEndTime()->getTimestamp() - $scheduleCommand->getExecutedAt()->getTimestamp();

        if ($time > self::HOUR_IN_SECONDES) {
            $hours = (int) ($time / self::HOUR_IN_SECONDES) . 'h ';
            $minutes = (($time % self::HOUR_IN_SECONDES) / self::MINUTE_IN_SECONDES) . 'm ';
            $secondes = (($time % self::HOUR_IN_SECONDES) % self::MINUTE_IN_SECONDES) . 's';

            return $hours . $minutes . $secondes;
        }

        if ($time > self::MINUTE_IN_SECONDES) {
            $minutes = (int) ($time / self::MINUTE_IN_SECONDES) . 'm ';
            $secondes = $time % self::MINUTE_IN_SECONDES . 's';

            return $minutes . $secondes;
        }

        return $time . 's';
    }

    /** {@inheritdoc} */
    public function configureOptions(OptionsResolver $resolver): void
    {
    }
}
