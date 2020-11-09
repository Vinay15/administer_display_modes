<?php

namespace Drupal\administer_display_modes\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Class SettingsForm.
 *
 * @package Drupal\paragraphs_view\Form
 */
class AdministerDisplayModesSettingsForm extends ConfigFormBase {

  /**
   * Entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a AdministerDisplayModesSettingsForm object.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   Entity type manager.
   */
  public function __construct(ConfigFactoryInterface $config_factory, EntityTypeManagerInterface $entity_type_manager) {
    parent::__construct($config_factory);
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'administer_display_modes.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'administer_display_modes_settings_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('administer_display_modes.settings')->getRawData();
    foreach ($this->entityTypeManager->getDefinitions() as $entity_id => $entity_type) {
      if ($entity_type->get('field_ui_base_route')) {
        $entity_type_label = $entity_type->getLabel();
        $form['help'] = [
          '#markup' => $this->t('Enable granular permissions to add, edit and delete display modes.')
        ];
        $form[$entity_id] = [
          '#type' => 'details',
          '#title' => $entity_type_label
        ];
        $form[$entity_id][$entity_id . '__form_mode'] = [
          '#type' => 'checkbox',
          '#title' => $this->t('Enable permissions for %entity_label form modes', ['%entity_label' => $entity_type_label]),
          '#default_value' => !empty($config['display_modes'][$entity_id]['form_mode'])
        ];
        $form[$entity_id][$entity_id . '__view_mode'] = [
          '#type' => 'checkbox',
          '#title' => $this->t('Enable permissions for %entity_label view modes', ['%entity_label' => $entity_type_label]),
          '#default_value' => !empty($config['display_modes'][$entity_id]['view_mode'])
        ];
      }
    }

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Save configs.
    parent::submitForm($form, $form_state);
    $config = $this->config('administer_display_modes.settings');
    $values = [];
    foreach ($form_state->cleanValues()->getValues() as $key => $value) {
      $exploded_key = explode('__', $key);
      $values[$exploded_key[0]][$exploded_key[1]] = $value;
    }
    $config->set('display_modes', $values);
    $config->save();
  }

}
