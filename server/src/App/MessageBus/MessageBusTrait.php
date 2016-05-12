<?php
namespace App\MessageBus;

trait MessageBusTrait
{
    /**
     * Handle command
     *
     * @param $command
     */
    public function handleCommand($command) {
        $this['messageBus.commandBus']->handle($command);
    }

    /**
     * Handle event
     *
     * @param $event
     */
    public function handleEvent($event) {
        $this['messageBus.eventBus']->handle($event);
    }
}