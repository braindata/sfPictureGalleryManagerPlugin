<?php use_helper('I18N', 'Date') ?>

<div id="sf_admin_container">
  <h1><?php echo __('Edit Picture - "%%position%%"', array('%%position%%' => $picture->getPosition()), 'messages') ?></h1>

  <div id="sf_admin_content">
    <?php include_partial('sfPictureGalleryManager/form', array('picture' => $picture, 'form' => $form, 'object_class' => $gallery_class, "object_id" => $gallery_id)) ?>
  </div>

</div>
