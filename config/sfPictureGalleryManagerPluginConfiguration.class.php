<?php

/**
 * sfPictureGalleryManagerPlugin configuration.
 * 
 * @package     sfPictureGalleryManagerPlugin
 * @subpackage  config
 * @author      Your name here
 * @version     SVN: $Id: PluginConfiguration.class.php 17207 2009-04-10 15:36:26Z Kris.Wallsmith $
 */
class sfPictureGalleryManagerPluginConfiguration extends sfPluginConfiguration
{
  const VERSION = '1.0.0-DEV';
  static $registered = false;

  /**
   * @see sfPluginConfiguration
   */
  public function initialize()
  {
    // Yes, this can get called twice. This is Fabien's workaround:
    // http://trac.symfony-project.org/ticket/8026

    if (!self::$registered)
    {
		  $this->dispatcher->connect('routing.load_configuration', array('PictureGalleryManagerRouting', 'listenToRoutingLoadConfigurationEvent'));
      self::$registered = true;
    }
  }
}
