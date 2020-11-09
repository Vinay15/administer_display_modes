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
    $this->settings = $this->configFactory->get('administer_display_modes.settings')->get('display_modes');
  }

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    if ($this->settings) {
//      $current_user_account = \Drupal::currentUser()->getAccount();
      foreach ($this->settings as $entity_id => $display_mode) {
        if ($route = $collection->get('entity.entity_view_mode.add_form')) {
//          $exist_perm = $route->getRequirement('_permission');
//          $new_perm = 'add view modes for ' . $entity_id;
//          $perm = $exist_perm ? $exist_perm . '+' . $new_perm : $new_perm;
          $route->setRequirement('_custom_access', '\Drupal\administer_display_modes\Controller\DisplayModesAccessController::viewModeAccess');
        }
//        if ($route = $collection->get('entity.entity_view_mode.edit_form')) {
//          $exist_perm = $route->getRequirement('_permission');
//          $new_perm = 'edit view modes for ' . $entity_id;
//          $perm = $exist_perm ? $exist_perm . '+' . $new_perm : $new_perm;
//          $route->setRequirement('_custom_access', '\Drupal\administer_display_modes\Controller\DisplayModesAccessController::access');
//        }
//        if ($route = $collection->get('entity.entity_view_mode.delete_form')) {
//          $exist_perm = $route->getRequirement('_permission');
//          $new_perm = 'delete view modes for ' . $entity_id;
//          $perm = $exist_perm ? $exist_perm . '+' . $new_perm : $new_perm;
//          $route->setRequirement('_custom_access', $this->displayModeAccessCheck->access($perm, $current_user_account));
//        }
        if ($route = $collection->get('entity.entity_form_mode.add_form')) {
//          $exist_perm = $route->getRequirement('_permission');
//          $new_perm = 'add form modes for ' . $entity_id;
//          $perm = $exist_perm ? $exist_perm . '+' . $new_perm : $new_perm;
          $route->setRequirement('_custom_access', '\Drupal\administer_display_modes\Controller\DisplayModesAccessController::formModeAccess');
        }
//        if ($route = $collection->get('entity.entity_form_mode.edit_form')) {
//          $exist_perm = $route->getRequirement('_permission');
//          $new_perm = 'edit form modes for ' . $entity_id;
//          $perm = $exist_perm ? $exist_perm . '+' . $new_perm : $new_perm;
//          $route->setRequirement('_custom_access', $this->displayModeAccessCheck->access($perm, $current_user_account));
//        }
//        if ($route = $collection->get('entity.entity_form_mode.delete_form')) {
//          $exist_perm = $route->getRequirement('_permission');
//          $new_perm = 'delete form modes for ' . $entity_id;
//          $perm = $exist_perm ? $exist_perm . '+' . $new_perm : $new_perm;
//          $route->setRequirement('_custom_access', $this->displayModeAccessCheck->access($perm, $current_user_account));
//        }
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public static function getSubscribedEvents() {
    $events = parent::getSubscribedEvents();
    $events[RoutingEvents::ALTER] = ['onAlterRoutes'];
    return $events;
  }

}
