<?php

namespace EclipseGc\CommonConsole;

use EclipseGc\CommonConsole\Event\CreateInputEvent;
use EclipseGc\CommonConsole\Event\OutputFormatterStyleEvent;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Input\InputDefinition;
use Symfony\Component\Console\Output\ConsoleOutput;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;

/**
 * Class OutputFactory
 *
 * @package EclipseGc\CommonConsole
 */
class IoFactory {

  /**
   * Creates a new ArgvInput object and applies input definition.
   *
   * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
   *   The event dispatcher.
   * @param \Symfony\Component\Console\Application $application
   *   The console application to retrieve the default input definition.
   *
   * @return \Symfony\Component\Console\Input\ArgvInput
   */
  public static function createInput(EventDispatcherInterface $dispatcher, Application $application) {
    $definition = $application->getDefinition();
    $event = new CreateInputEvent($definition);
    $dispatcher->dispatch(CommonConsoleEvents::CREATE_INPUT_DEFINITION, $event);
    return new ArgvInput(NULL, $event->getDefinition());
  }

  /**
   * Creates a new ConsoleOutput object and applies formatter styles.
   *
   * @param \Symfony\Component\EventDispatcher\EventDispatcherInterface $dispatcher
   *   The event dispatcher.
   *
   * @return \Symfony\Component\Console\Output\ConsoleOutput
   *   The ConsoleOutput with full formatter styling applied.
   */
  public static function createOutput(EventDispatcherInterface $dispatcher) {
    $event = new OutputFormatterStyleEvent();
    $dispatcher->dispatch(CommonConsoleEvents::OUTPUT_FORMATTER_STYLE, $event);
    $output = new ConsoleOutput();
    foreach ($event->getFormatterStyles() as $name => $style) {
      if (!$output->getFormatter()->hasStyle($name)) {
        $output->getFormatter()->setStyle($name, $style);
      }
    }
    return $output;
  }

}
