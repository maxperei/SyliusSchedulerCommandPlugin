<?php

declare(strict_types=1);

namespace Synolia\SyliusSchedulerCommandPlugin\Service;

use Doctrine\ORM\EntityManagerInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Synolia\SyliusSchedulerCommandPlugin\Entity\CommandInterface;
use Synolia\SyliusSchedulerCommandPlugin\Entity\ScheduledCommandInterface;

class ScheduledCommandPlanner
{
    /** @var \Sylius\Component\Resource\Factory\FactoryInterface */
    private $scheduledCommandFactory;

    /** @var \Doctrine\ORM\EntityManagerInterface */
    private $entityManager;

    public function __construct(
        FactoryInterface $scheduledCommandFactory,
        EntityManagerInterface $entityManager
    ) {
        $this->scheduledCommandFactory = $scheduledCommandFactory;
        $this->entityManager = $entityManager;
    }

    public function plan(CommandInterface $command): ScheduledCommandInterface
    {
        /** @var ScheduledCommandInterface $scheduledCommand */
        $scheduledCommand = $this->scheduledCommandFactory->createNew();

        $scheduledCommand
            ->setName($command->getName())
            ->setCommand($command->getCommand())
            ->setArguments($command->getArguments())
        ;

        if (null !== $command->getLogFilePrefix()) {
            $scheduledCommand->setLogFile(\sprintf(
                '%s-%s-%s.log',
                $command->getLogFilePrefix(),
                (new \DateTime())->format('Y-m-d'),
                \uniqid(),
            ));
        }

        $this->entityManager->persist($scheduledCommand);
        $this->entityManager->flush();

        return $scheduledCommand;
    }
}
