<?php

namespace Drupal\ajax_forms\Forms;

use Drupal\ajax_forms\ExternalService;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\OpenModalDialogCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\ReplaceCommand;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * SendToDestinationsForm class.
 */
class AjaxModal extends FormBase {

  /**
    * Class constructor.
    */
  public function __construct(
    protected ExternalService $externalService
  ) {
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    // Create a new form object and inject its services.
    return new static(
    // Load the services required to construct this class.
      $container->get('ajax_forms.external_service'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'ajax_forms_ajax_modal';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $options = NULL) {
    $form['#prefix'] = '<div id="ajax_forms_ajax_modal_wrapper">';
    $form['#suffix'] = '</div>';

    // The status messages that will contain any form errors.
    $form['status_messages'] = [
      '#type' => 'status_messages',
      '#weight' => -10,
    ];

    // This demonstrates how the current timestamp is NOT being stored
    // across requests eg. if the form has errors.
    if ( ! $form_state->has('timestamp') ) {
      $form_state->set('timestamp', microtime(TRUE));
    }

    $form['debug'] = [
      '#markup' => $form_state->get('timestamp'),
      '#prefix' => '<pre>',
      '#suffix' => '</pre>',
    ];
    $form['time'] = [
      '#markup' => microtime(TRUE),
      '#prefix' => '<pre>',
      '#suffix' => '</pre>',
    ];

    $options = [];

    $orders = $this->externalService->getCurrentOrders();

    // @todo Here I would like to store the initial set of orders in the form
    // storage.
    // That way I can compare later and inform the user if the orders have
    // changed while she had the form open.

    // WIP This is just a simple way of displaying a varying number of checkboxes.
    // Not representative of the intended functionality.
    for ( $i = 0; $i < $orders; $i++ ) {
      $option = (String) $i;
      $options[$option] = $option;
    }
    // A required checkboxes field.
    $form['select'] = [
      '#type' => 'checkboxes',
      '#title' => $this->t('Select Order(s)'),
      '#options' => $options,
      '#required' => TRUE,
    ];

    $form['actions'] = ['#type' => 'actions'];
    $form['actions']['send'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit modal form'),
      '#ajax' => [
        'callback' => '::submitModalFormAjax',
        'wrapper' => 'ajax_forms_ajax_modal_wrapper',
      ],
    ];

    $form['#attached']['library'][] = 'core/drupal.dialog.ajax';

    return $form;
  }

  /**
   * AJAX callback handler that displays any errors or a success message.
   */
  public function submitModalFormAjax(array $form, FormStateInterface $form_state) {
    $response = new AjaxResponse();

    // If there are any form errors, AJAX replace the form.
    if ( $form_state->hasAnyErrors() ) {
      $response->addCommand(
        new ReplaceCommand('#ajax_forms_ajax_modal_wrapper', $form)
      );
    }
    else {
      $response->addCommand(
        new OpenModalDialogCommand(
          "Success!", 'The modal form has been submitted.',
          ['width' => 700]
        )
      );
    }

    return $response;
  }

  /**
   * {@inheritdoc}
   * https://drupal.stackexchange.com/questions/215699/how-can-i-get-the-form-validated-with-ajax
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    // Here I need to check if the current orders have changed, and if so I
    // need to rebuild the form to reflect the new state.
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
  }

}
