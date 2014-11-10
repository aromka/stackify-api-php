<?php

/**
 * @TODO log4php does not support namespaces
 * Should we also use old-fashioned way without namespaces?
 */
namespace Stackify\Log\Log4php;

use Stackify\Log\Log4php\MessageBuilder;

class Layout extends \LoggerLayout
{

    /**
     * @var \Stackify\Log\MessageBuilder\BuilderInterface
     */
    private $builder;

    public function __construct()
    {
        $this->builder = new MessageBuilder();
    }

    public function format(\LoggerLoggingEvent $event) {
        return $this->builder->getSyslogMessage($event);
    }

}