<style>
  label{display: block; margin-top: 10px;}

</style>

<div class="sf_admin_form">
  <form action="<?php echo url_for('@picture_gallery_update?id='.$picture->getId()."&object_class=".$object_class."&object_id=".$object_id) ?>" >
    <?php echo $form->renderHiddenFields(false) ?>

    <?php if ($form->hasGlobalErrors()): ?>
      <?php echo $form->renderGlobalErrors() ?>
    <?php endif; ?>

    <?php echo $form ?>
  </form>
</div>
