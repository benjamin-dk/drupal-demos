CONTENTS OF THIS FILE
---------------------
 * Introduction
 * Requirements
 * Installation
 * Background
 * Relevant links

INTRODUCTION
------------
A collection of Ajax enabled Drupal forms - meant for demoing and debugging.

REQUIREMENTS
------------
None.

INSTALLATION
------------
 * Install as usual:
 See https://www.drupal.org/documentation/install/modules-themes/modules-8
 for further information.

BACKGROUND
-------------
* Currently only one form is available on /ajax-modal-example. The purpose of
this is to explore the best practice for displaying a form where the contents
of the form (ie. checkbox options) is dynamic because it is defined by data
from an external API.

RELEVANT LINKS:
Forum post that started it all:
https://www.drupal.org/forum/support/module-development-and-code-questions/2024-08-20/the-specified-ajax-callback-is-empty-or-not-callable

Some Drupal docs about Ajax Dialog Boxes:
https://www.drupal.org/docs/develop/drupal-apis/ajax-api/ajax-dialog-boxes

An issue that seems relevant to the current issue with the form storage not
being persisted during requests / rebuilds of the form.
https://www.drupal.org/project/drupal/issues/3189550
