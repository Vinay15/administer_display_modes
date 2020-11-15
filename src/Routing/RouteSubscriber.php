<?php

namespace Drupal\administer_display_modes\Routing;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Routing\RouteSubscriberBase;
use Drupal\Core\Routing\RoutingEvents;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Subscriber for Field UI routes.
 */
class RouteSubscriber extends RouteSubscriberBase {
//
//  /**
//   * The config factory.
//   *
//   * @var \Drupal\Core\Config\ConfigFactoryInterface
//   */
//  protected $configFactory;
//
//  /**
//   * The settings of this module.
//   *
//   * @var \Drupal\Core\Site\Settings
//   */
//  protected $settings;
//
//  /**
//   * Constructs a RouteSubscriber object.
//   *
//   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
//   *   The config factory.
//   */
//  public function __construct(ConfigFactoryInterface $config_factory) {
//    $this->configFactory = $config_factory;
//    $this->settings = $this->configFactory->get('administer_display_modes.settings')->get('display_modes');
//  }

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
//    $logger = \Drupal::logger('debug_foreach_route_alter');
//    $logger->debug('<pre>' . print_r(array_keys($collection->all()), TRUE) . '</pre>');
//    if ($this->settings) {
//      $current_user_account = \Drupal::currentUser()->getAccount();
//      foreach ($this->settings as $entity_id => $display_mode) {
//        if ($route = $collection->get('entity.entity_view_mode.add_form')) {
//          $exist_perm = $route->getRequirement('_permission');
//          $new_perm = 'add view modes for ' . $entity_id;
//          $perm = $exist_perm ? $exist_perm . '+' . $new_perm : $new_perm;
//          $route->setRequirement('_permission', $new_perm);
//          $route->setRequirements(['_custom_access' => '\Drupal\administer_display_modes\Access\DisplayModesAccessCheck::access']);
//          $logger->debug('<pre>inside if of entity.entity_view_mode.add_form ' . print_r($route, TRUE) . '</pre>');
//        }
//        if ($route = $collection->get('entity.entity_view_mode.edit_form')) {
//          $exist_perm = $route->getRequirement('_permission');
//          $new_perm = 'edit view modes for ' . $entity_id;
//          $perm = $exist_perm ? $exist_perm . '+' . $new_perm : $new_perm;
//          $route->setRequirement('_custom_access', '\Drupal\administer_display_modes\Controller\DisplayModesAccessController::access');
//          $route->setRequirements(['_custom_access' => '\Drupal\administer_display_modes\Access\DisplayModesAccessCheck::access']);
//          $logger->debug('<pre>inside if of entity.entity_view_mode.edit_form ' . print_r($route, TRUE) . '</pre>');
//        }
//        if ($route = $collection->get('entity.entity_view_mode.delete_form')) {
//          $exist_perm = $route->getRequirement('_permission');
//          $new_perm = 'delete view modes for ' . $entity_id;
//          $perm = $exist_perm ? $exist_perm . '+' . $new_perm : $new_perm;
//          $route->setRequirement('_custom_access', $this->displayModeAccessCheck->access($perm, $current_user_account));
//          $route->setRequirements(['_custom_access' => '\Drupal\administer_display_modes\Access\DisplayModesAccessCheck::access']);
//          $logger->debug('<pre>inside if of entity.entity_view_mode.delete_form ' . print_r($route, TRUE) . '</pre>');
//        }
//        if ($route = $collection->get('entity.entity_form_mode.add_form')) {
//          $exist_perm = $route->getRequirement('_permission');
//          $new_perm = 'add form modes for ' . $entity_id;
//          $perm = $exist_perm ? $exist_perm . '+' . $new_perm : $new_perm;
//          $route->setRequirement('_permission', $new_perm);
//          $route->setRequirements(['_custom_access' => '\Drupal\administer_display_modes\Access\DisplayModesAccessCheck::access']);
//          $logger->debug('<pre>inside if of entity.entity_form_mode.add_form ' . print_r($route, TRUE) . '</pre>');
//        }
//        if ($route = $collection->get('entity.entity_form_mode.edit_form')) {
//          $exist_perm = $route->getRequirement('_permission');
//          $new_perm = 'edit form modes for ' . $entity_id;
//          $perm = $exist_perm ? $exist_perm . '+' . $new_perm : $new_perm;
//          $route->setRequirement('_custom_access', $this->displayModeAccessCheck->access($perm, $current_user_account));
//          $route->setRequirements(['_custom_access' => '\Drupal\administer_display_modes\Access\DisplayModesAccessCheck::access']);
//          $logger->debug('<pre>inside if of entity.entity_form_mode.edit_form ' . print_r($route, TRUE) . '</pre>');
//        }
//        if ($route = $collection->get('entity.entity_form_mode.delete_form')) {
//          $exist_perm = $route->getRequirement('_permission');
//          $new_perm = 'delete form modes for ' . $entity_id;
//          $perm = $exist_perm ? $exist_perm . '+' . $new_perm : $new_perm;
//          $route->setRequirement('_custom_access', $this->displayModeAccessCheck->access($perm, $current_user_account));
//          $route->setRequirements(['_custom_access' => '\Drupal\administer_display_modes\Access\DisplayModesAccessCheck::access']);
//          $logger->debug('<pre>inside if of entity.entity_form_mode.delete_form ' . print_r($route, TRUE) . '</pre>');
//        }
//      }
//    }

    $routes_to_alter = [
      'field_ui.display_mode',
      'entity.entity_form_mode.collection',
      'field_ui.entity_form_mode_add',
      'entity.entity_form_mode.add_form',
      'entity.entity_view_mode.collection',
      'field_ui.entity_view_mode_add',
      'entity.entity_view_mode.add_form',
    ];
    foreach ($routes_to_alter as $route_to_alter) {
      if ($route = $collection->get($route_to_alter)) {
        $route->setRequirements(['_custom_access' => 'administer_display_modes.access_checker::access']);
//        \Drupal::logger($route_to_alter)->debug('<pre>' . print_r($route_to_alter, TRUE) . '</pre></br><pre>'. print_r($route, TRUE) . '</pre>');
      }
    }
    if ($route = $collection->get('field_ui.entity_form_mode_add')) {
      $route->setDefault('_controller', '\Drupal\administer_display_modes\Controller\EntityDisplayModeController::formModeTypeSelection');
    }
    if ($route = $collection->get('field_ui.entity_view_mode_add')) {
      $route->setDefault('_controller', '\Drupal\administer_display_modes\Controller\EntityDisplayModeController::viewModeTypeSelection');
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events = parent::getSubscribedEvents();
    $events[RoutingEvents::ALTER] = ['onAlterRoutes', -100];
    return $events;
  }

}
