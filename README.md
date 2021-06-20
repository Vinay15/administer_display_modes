# Administer Display Modes

This module provides entity-specific granular permissions as an alternative to
the generic "_Add, edit, and delete custom display modes._" permission provided
by Drupal core.

**Installation:**

`composer require drupal/administer_display_modes`

**Configuration:**

To configure, please visit /admin/structure/display-modes/administer:
1. Select whether to enable permissions for a particular entity's form mode, or
   view mode, or even both display modes and save the configuration.
2. Post save, granular permissions to add, edit and delete the enabled display
   mode for that particular entity will be made available on the
   /admin/people/permissions page.
3. Assign permissions to the intended roles.

**Usage:**

Users with the assigned permission are able to add, edit or delete display
modes.
