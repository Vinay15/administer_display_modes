<?php

namespace Drupal\administer_display_modes\Access;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\Entity\EntityFormMode;
use Drupal\Core\Entity\Entity\EntityViewMode;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Routing\Access\AccessInterface;
use Drupal\Core\Routing\RouteMatch;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Session\AccountProxy;
use Symfony\Component\Routing\Route;

/**
 * Class DisplayModesAccessCheck.
 *
 * @package Drupal\administer_display_modes\Access
 */
class DisplayModesAccessCheck implements AccessInterface {

  /**
   * The config factory.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The settings of this module.
   *
   * @var \Drupal\Core\Site\Settings
   */
  protected $settings;

  /**
   * Constructs a RouteSubscriber object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory;
    $this->settings = $this->configFactory->get('administer_display_modes.settings');
  }

  public function access(Route $route, RouteMatch $route_match, AccountInterface $account) {
    $new_perm = '';
    $route_name = $route_match->getRouteName();
    $parameters = $route_match->getParameters();
    $display_modes = $this->settings->get('display_modes');
    $fm_perm_group = $vm_perm_group = ['administer display modes'];
    foreach ($display_modes as $entity_id => $display_mode) {
      if ($display_mode['form_mode']) {
        array_push($fm_perm_group, "add form modes for $entity_id", "edit form modes for $entity_id",  "delete form modes for $entity_id");
      }
      if ($display_mode['view_mode']) {
        array_push($vm_perm_group,"add view modes for $entity_id", "edit view modes for $entity_id", "delete view modes for $entity_id");
      }
    }
    if ($route_name == 'field_ui.display_mode') {
      return AccessResult::allowedIfHasPermissions($account, array_merge($fm_perm_group, $vm_perm_group), 'OR')
        ->addCacheableDependency($this->settings);
    }
    if ($route_name == 'entity.entity_form_mode.collection' || $route_name == 'field_ui.entity_form_mode_add') {
      \Drupal::logger($route_name)->debug('<pre>' . print_r($fm_perm_group, TRUE) . '</pre>');
      return AccessResult::allowedIfHasPermissions($account, $fm_perm_group, 'OR')
        ->addCacheableDependency($this->settings);
    }
    if ($route_name == 'entity.entity_view_mode.collection' || $route_name == 'field_ui.entity_view_mode_add') {
      \Drupal::logger($route_name)->debug('<pre>' . print_r($vm_perm_group, TRUE) . '</pre>');
      return AccessResult::allowedIfHasPermissions($account, $vm_perm_group, 'OR')
        ->addCacheableDependency($this->settings);
    }
    if ($route_name == 'entity.entity_form_mode.add_form') {
      if ($parameters->has('entity_type_id')) {
//        \Drupal::logger('form_mode_debug_access')->debug('<pre>' . print_r($parameters->get('entity_type_id'), TRUE) . '</pre>');
        $new_perm = 'add form modes for ' . $parameters->get('entity_type_id');
      }
    }
    if ($route_name == 'entity.entity_form_mode.edit_form' || $route_name == 'entity.entity_form_mode.delete_form') {
      if ($parameters->has('entity_form_mode')) {
        $entity_form_mode = $parameters->get('entity_form_mode');
        if ($entity_form_mode instanceof EntityFormMode) {
//          \Drupal::logger('form_mode_debug_config_target')->debug('<pre>' . print_r($entity_form_mode->getTargetType(), TRUE) . '</pre>');
          if ($route_name == 'entity.entity_form_mode.edit_form') {
            $new_perm = 'edit form modes for ' . $entity_form_mode->getTargetType();
          }
          if ($route_name == 'entity.entity_form_mode.delete_form') {
            $new_perm = 'delete form modes for ' . $entity_form_mode->getTargetType();
          }
        }
      }
    }
    if ($route_name == 'entity.entity_view_mode.add_form') {
      if ($parameters->has('entity_type_id')) {
//        \Drupal::logger('view_mode_debug_access')->debug('<pre>' . print_r($parameters->get('entity_type_id'), TRUE) . '</pre>');
        $new_perm = 'add view modes for ' . $parameters->get('entity_type_id');
      }
    }
    if ($route_name == 'entity.entity_view_mode.edit_form' || $route_name == 'entity.entity_view_mode.delete_form') {
      if ($parameters->has('entity_view_mode')) {
        $entity_view_mode = $parameters->get('entity_view_mode');
        if ($entity_view_mode instanceof EntityViewMode) {
//          \Drupal::logger('view_mode_debug_config_target')->debug('<pre>' . print_r($entity_view_mode->getTargetType(), TRUE) . '</pre>');
          if ($route_name == 'entity.entity_view_mode.edit_form') {
            $new_perm = 'edit view modes for ' . $entity_view_mode->getTargetType();
          }
          if ($route_name == 'entity.entity_view_mode.delete_form') {
            $new_perm = 'delete view modes for ' . $entity_view_mode->getTargetType();
          }
        }
      }
    }
    if ($new_perm) {
      return AccessResult::allowedIfHasPermissions($account, ["administer display modes", $new_perm], 'OR')
        ->addCacheableDependency($this->settings);
    }
    return AccessResult::allowedIfHasPermission($account, 'administer display modes');
  }

  /**
   * Check if permission to access display modes pages.
   *
   * @param string $entity_type_id
   *   The permission to check.
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The current user account to check if it has the required permission.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   Return allowed if current user account has permission to access entity.
   */
//  public static function addFormModeAccess($entity_type_id, AccountInterface $account) {
//    \Drupal::logger('access_check_debug_form')->debug('<pre>' . print_r($entity_type_id, TRUE) . '</pre>');
//    return AccessResult::allowedIfHasPermissions($account, ["administer display modes", "add form modes for $entity_type_id"], 'OR');
//  }

  /**
   * Check if permission to access display modes pages.
   *
   * @param string $entity_type_id
   *   The permission to check.
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The current user account to check if it has the required permission.
   *
   * @return \Drupal\Core\Access\AccessResultInterface
   *   Return allowed if current user account has permission to access entity.
   */
//  public static function addViewModeAccess($entity_type_id, AccountInterface $account) {
//    \Drupal::logger('access_check_debug_view')->debug('<pre>' . print_r($entity_type_id, TRUE) . '</pre>');
//    return AccessResult::allowedIfHasPermissions($account, ["administer display modes", "add view modes for $entity_type_id"], 'OR');
//  }

}
