<?php

namespace Drupal\administer_display_modes;

/**
 * Overrides listing of form mode entities provided by field_ui module.
 */
class EntityFormModeListBuilder extends EntityDisplayModeListBuilder {

  public function load() {
    return $this->getAllowedEntities('form');
  }

  /**
   * Filters entities based on their form mode handlers.
   *
   * @param $entity_type
   *   The entity type of the entity that needs to be validated.
   *
   * @return bool
   *   TRUE if the entity has any forms, FALSE otherwise.
   */
  protected function isValidEntity($entity_type) {
    return $this->entityTypes[$entity_type]->hasFormClasses();
  }

}
