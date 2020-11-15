<?php

namespace Drupal\administer_display_modes;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Session\AccountProxy;
use Drupal\field_ui\EntityDisplayModeListBuilder as CoreEntityDisplayModeListBuilder;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Overrides listing of form mode entities provided by field_ui module.
 */
class EntityDisplayModeListBuilder extends CoreEntityDisplayModeListBuilder {

  protected $config;

  protected $currentUser;

  public function __construct(EntityTypeInterface $entity_type, EntityStorageInterface $storage, array $entity_types, ConfigFactoryInterface $config_factory, AccountProxy $account) {
    parent::__construct($entity_type, $storage, $entity_types);
    $this->config = $config_factory;
    $this->currentUser = $account;
  }

  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    $entity_type_manager = $container->get('entity_type.manager');
    return new static(
      $entity_type,
      $entity_type_manager->getStorage($entity_type->id()),
      $entity_type_manager->getDefinitions(),
      $container->get('config.factory'),
      $container->get('current_user')
    );
  }

  public function load() {
    return $this->getAllowedEntities('view');
  }

  protected function getAllowedEntities($option) {
    $allowed_entities = [];
    $display_modes = $this->config->get('administer_display_modes.settings')
      ->get('display_modes');
    $current_user = $this->currentUser;
    foreach (parent::load() as $entity_id => $entity) {
      if ($display_modes[$entity_id][$option . '_mode']
        && ($current_user->hasPermission("add $option modes for $entity_id")
          || $current_user->hasPermission("edit $option modes for $entity_id")
          || $current_user->hasPermission("delete $option modes for $entity_id"))) {
        $allowed_entities[$entity_id] = $entity;
      }
    }
    return $allowed_entities;
  }

}
