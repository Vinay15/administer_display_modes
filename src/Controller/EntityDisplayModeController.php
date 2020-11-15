<?php

namespace Drupal\administer_display_modes\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;

/**
 * Provides methods for entity display mode routes.
 */
class EntityDisplayModeController extends ControllerBase {

  /**
   * Provides a list of eligible entity types for adding view modes.
   *
   * @return array
   *   A list of entity types to add a view mode for.
   */
  public function viewModeTypeSelection() {
    $entity_types = [];
    foreach ($this->entityTypeManager()->getDefinitions() as $entity_type_id => $entity_type) {
      if ($entity_type->get('field_ui_base_route') && $entity_type->hasViewBuilderClass()) {
        $entity_types[$entity_type_id] = [
          'title' => $entity_type->getLabel(),
          'url' => Url::fromRoute('entity.entity_view_mode.add_form', ['entity_type_id' => $entity_type_id]),
          'localized_options' => [],
        ];
      }
    }
    $entity_types = $this->getAllowedEntities($entity_types, 'view');
    return [
      '#theme' => 'admin_block_content',
      '#content' => $entity_types,
    ];
  }

  /**
   * Provides a list of eligible entity types for adding form modes.
   *
   * @return array
   *   A list of entity types to add a form mode for.
   */
  public function formModeTypeSelection() {
    $entity_types = [];
    foreach ($this->entityTypeManager()->getDefinitions() as $entity_type_id => $entity_type) {
      if ($entity_type->get('field_ui_base_route') && $entity_type->hasFormClasses()) {
        $entity_types[$entity_type_id] = [
          'title' => $entity_type->getLabel(),
          'url' => Url::fromRoute('entity.entity_form_mode.add_form', ['entity_type_id' => $entity_type_id]),
          'localized_options' => [],
        ];
      }
    }
    $entity_types = $this->getAllowedEntities($entity_types, 'form');
    return [
      '#theme' => 'admin_block_content',
      '#content' => $entity_types,
    ];
  }

  protected function getAllowedEntities($entity_types, $option) {
    $allowed_entities = [];
    $current_user = $this->currentUser();
    $display_modes = $this->config('administer_display_modes.settings')
      ->get('display_modes');
    foreach ($entity_types as $entity_type_id => $value) {
      if ($display_modes[$entity_type_id][$option . '_mode']
        && ($current_user->hasPermission("add $option modes for $entity_type_id")
          || $current_user->hasPermission("edit $option modes for $entity_type_id")
          || $current_user->hasPermission("delete $option modes for $entity_type_id"))) {
        $allowed_entities[$entity_type_id] = $value;
      }
    }
    return $allowed_entities;
  }

}
