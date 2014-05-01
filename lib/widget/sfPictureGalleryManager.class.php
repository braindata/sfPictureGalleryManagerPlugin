<?php
/**
 * Description of sfPictureGalleryManager
 *
 * @author Johannes
 */
class sfPictureGalleryManager extends sfWidgetForm {
  
  public function configure( $options = array(), $attributes = array() )
  {
    $this->addOption(  "object" );
  }

  public function getStylesheets()
  {
    return array(
        '/sfPictureGalleryManagerPlugin/css/picture_gallery.css' => 'all',
        '/sfPictureGalleryManagerPlugin/css/uploadify.css' => 'all',
    );
  }

  public function getJavascripts()
  {
    return array(
        '/sfPictureGalleryManagerPlugin/js/picture_gallery.js',
        '/sfPictureGalleryManagerPlugin/js/jquery.uploadify.v2.1.0.min.js',
        '/sfPictureGalleryManagerPlugin/js/swfobject'
    );
  }

  public function render($name, $value = null, $attributes = array(), $errors = array())
  {
    //load partial Helper as we want to outsource the Template
    sfContext::getInstance()->getConfiguration()->loadHelpers('Partial');

    $object = $this->getOption('object');
    $controller = sfContext::getInstance()->getController();
    $param = array("object_class" => get_class($object), "object_id" => $object->getId());

    $options = array(
        'object_class' => get_class($object),
        'object_id'    => $object->getId(),

        'resortUrl'   => $controller->genUrl(array_merge(array("sf_route" => 'picture_gallery_resort'), $param)),
        'removeUrl'   => $controller->genUrl(array_merge(array("sf_route" => 'picture_gallery_remove'), $param)),
        'createUrl'   => $controller->genUrl(array_merge(array("sf_route" => 'picture_gallery_create'), $param, array("user_id" => sfContext::getInstance()->getUser()->getId()))),
        'updateRoute' => "@picture_gallery_update",
        'editRoute'   => "@picture_gallery_edit",
        'pictures'    => $object->getPictures("ASC"),
        'Gallery'     => $object,

    );

    return get_partial( 'sfPictureGalleryManager/gallery', $options);
  }
}
?>
