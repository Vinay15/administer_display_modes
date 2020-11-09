<?php

namespace Drupal\administer_display_modes\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;

/**
 * Class DisplayModesAccessController.
 *
 * @package Drupal\administer_display_modes\Controller
 */
class DisplayModesAccessController extends ControllerBase {

  /**
   * Check if permission to access display modes pages.
   *
   * @param string $permission
   *   The permission to check.
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The current user account to check if it has the required permission.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   Return allowed if current user account has permission to access entity.
   */
  public function formModeAccess($entity_type_id, AccountInterface $account) {
    $config = $this->config('administer_display_modes.settings');
    return AccessResult::allowedIfHasPermissions($account, ["administer display modes", "add form modes for $entity_type_id"], 'OR')
      ->addCacheableDependency($config);
  }

  /**
   * Check if permission to access display modes pages.
   *
   * @param string $permission
   *   The permission to check.
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The current user account to check if it has the required permission.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   Return allowed if current user account has permission to access entity.
   */
  public function viewModeAccess($entity_type_id, AccountInterface $account) {
    $config = $this->config('administer_display_modes.settings');
    return AccessResult::allowedIfHasPermissions($account, ["administer display modes", "add view modes for $entity_type_id"], 'OR')
      ->addCacheableDependency($config);
  }

}
