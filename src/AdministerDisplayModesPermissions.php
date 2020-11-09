<?php

namespace Drupal\administer_display_modes;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides permissions of the administer_display_modes module.
 */
class AdministerDisplayModesPermissions implements ContainerInjectionInterface {

  use StringTranslationTrait;

  /**
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

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
   * AdministerDisplayModesPermissions constructor.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, ConfigFactoryInterface $config_factory) {
    $this->entityTypeManager = $entity_type_manager;
    $this->configFactory = $config_factory;
    $this->settings = $this->configFactory->get('administer_display_modes.settings')->get('display_modes');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('config.factory')
    );
  }

  /**
   * Get list of permissions.
   *
   * @return array
   *   Collection of permissions.
   */
  public function permissions() {
    $permissions = [];

    $entity_definitions = $this->entityTypeManager->getDefinitions();
    foreach ($this->settings as $entity_id => $display_mode) {
      if ($display_mode['form_mode']) {
        $permissions['add form modes for ' . $entity_id] = [
          'title' => $this->t('Add Form Modes for %entity_label', ['%entity_label' => $entity_definitions[$entity_id]->getLabel()]),
        ];
        $permissions['edit form modes for ' . $entity_id] = [
          'title' => $this->t('Edit Form Modes for %entity_label', ['%entity_label' => $entity_definitions[$entity_id]->getLabel()]),
        ];
        $permissions['delete form modes for ' . $entity_id] = [
          'title' => $this->t('Delete Form Modes for %entity_label', ['%entity_label' => $entity_definitions[$entity_id]->getLabel()]),
        ];
      }
      if ($display_mode['view_mode']) {
        $permissions['add view modes for ' . $entity_id] = [
          'title' => $this->t('Add View Modes for %entity_label', ['%entity_label' => $entity_definitions[$entity_id]->getLabel()]),
        ];
        $permissions['edit view modes for ' . $entity_id] = [
          'title' => $this->t('Edit View Modes for %entity_label', ['%entity_label' => $entity_definitions[$entity_id]->getLabel()]),
        ];
        $permissions['delete view modes for ' . $entity_id] = [
          'title' => $this->t('Delete View Modes for %entity_label', ['%entity_label' => $entity_definitions[$entity_id]->getLabel()]),
        ];
      }
    }

    return $permissions;
  }

}
