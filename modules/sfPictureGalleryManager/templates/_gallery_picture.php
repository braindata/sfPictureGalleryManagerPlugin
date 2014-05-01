<li class="ui-widget-content ui-corner-tr <?php if ($picture->getDescription()) echo "ui-state-hover" ?>" id="picture_<?php echo $picture->getId(); ?>">
  <h5 class="ui-widget-header"><?php echo $picture->getPosition(); ?></h5>
  <img src="<?php echo $picture->getImageRoute("thumb"); ?>"  title="<?php echo $picture->getOrigin(); ?> | <?php echo $picture->getNumViews(); ?> | <?php echo $picture->getDescription(); ?>" width="96" height="72" />
  <a href="<?php echo $picture->getImageRoute("normal"); ?>" rel="gallery" title="<?php echo $picture->getPosition(); ?> " class="ui-icon ui-icon-zoomin"><?php echo __('View larger image', null, 'sf_gallery_manager') ?></a>
  <a href="<?php echo url_for($editRoute.'?id='.$picture->getId()."&object_class=".$object_class."&object_id=".$object_id) ?>" title="<?php echo __('Edit image', null, 'sf_gallery_manager') ?>" class="ui-icon ui-icon-pencil"><?php echo __('Edit image', null, 'sf_gallery_manager') ?></a>
  <a href="javascript:void(0)" title="<?php echo __('Delete this image', null, 'sf_gallery_manager') ?>" class="ui-icon ui-icon-trash"><?php echo __('Delete this image', null, 'sf_gallery_manager') ?></a>
</li>