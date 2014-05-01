<?php

class PictureGalleryManagerRouting extends sfPatternRouting
{
  static public function listenToRoutingLoadConfigurationEvent(sfEvent $event)
  {
    $r = $event->getSubject();

    $r->prependRoute('picture_gallery_resort',
      new sfRoute('/admin/picture_gallery_resort/:object_class/:object_id',
        array('module' => 'sfPictureGalleryManager', 'action' => 'resort')
    ));

    $r->prependRoute('picture_gallery_remove',
      new sfRoute('/admin/picture_gallery_remove/:object_class/:object_id',
        array('module' => 'sfPictureGalleryManager', 'action' => 'remove')
    ));

    $r->prependRoute('picture_gallery_create',
      new sfRoute('/admin/picture_gallery_create/:object_class/:object_id/:user_id',
        array('module' => 'sfPictureGalleryManager', 'action' => 'create')
    ));

    $r->prependRoute('picture_gallery_update',
      new sfRoute('/admin/picture_gallery_update/:object_class/:object_id/:id',
        array('module' => 'sfPictureGalleryManager', 'action' => 'update')
    ));

    $r->prependRoute('picture_gallery_edit',
      new sfRoute('/admin/picture_gallery_edit/:object_class/:object_id/:id',
        array('module' => 'sfPictureGalleryManager', 'action' => 'edit')
    ));


  }
}
