<?php

declare(strict_types=1);

namespace Drupal\ajax_forms;

use Drupal\Core\Logger\LoggerChannelFactoryInterface;

/**
 * A very rude mock of an external web service API
 */
final class ExternalService {

  /**
   * Constructs an ExternalService object.
   */
  public function __construct(
    private readonly LoggerChannelFactoryInterface $loggerFactory,
  ) {}

  /**
   * Fetch current "orders" from external db. Idea is that the
   * current orders is a fluctuating set.
   */
  public function getCurrentOrders(): int {
    return rand(1, 10);
  }

}
