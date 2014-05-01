<?php

class sfPictureGalleryManagerActions extends sfActions
{
  public function preExecute()
  {
    $this->gallery_id = sfContext::getInstance()->getRequest()->getParameter('object_id');
    $this->gallery_class = sfContext::getInstance()->getRequest()->getParameter('object_class');
    
    $this->Gallery = Doctrine::getTable($this->gallery_class)->findOneBy('id', $this->gallery_id);
  }


  public function executeResort(sfWebRequest $request)
  {
    $message = "";
    $this->getResponse()->setContentType('application/json');
    $sortorder = $request->getParameter('picture', false);

    if (is_array($sortorder)){
      $this->Gallery->sortPictures($sortorder);
      $status = true;
      $message = $this->getContext()->getI18N()->__("The gallery was resorted successfully!", null, 'sf_gallery_manager');
    } else {
      $status = false;
    }

    return $this->renderText(json_encode(array("status" => $status, "message" => $message)));
  }

  public function executeRemove(sfWebRequest $request)
  {
    $message = "";
    $this->getResponse()->setContentType('application/json');
    $remove = $request->getParameter('picture', false);

    if (is_array($remove)){
      $count = $this->Gallery->deletePictures($remove);
      $this->Gallery->recountPictures();
      $status = true;
      $message = sprintf($this->getContext()->getI18N()->__("%s picture(s) deleted!", null, 'sf_gallery_manager'), $count);
    } else {
      $status = false;
    }

    return $this->renderText(json_encode(array("status" => $status, "message" => $message)));
  }

  public function executeEdit(sfWebRequest $request)
  {
    $id = $request->getParameter('id');
    $this->picture = $this->Gallery->getPicture($id);
    $this->form = $this->picture->getForm();
  }

  public function executeUpdate(sfWebRequest $request)
  {
    $message = "";
    $content = array();

    $this->getResponse()->setContentType('application/json');
    
    $id = $request->getParameter('id');
    $this->picture = $this->Gallery->getPicture($id);

    $this->form = $this->picture->getForm();
    $this->form->bind($request->getParameter($this->form->getName()));

    if ($this->form->isValid())
    {
      $this->form->save();
      $status = true;
      
      $content['item'] = $this->getPartial('sfPictureGalleryManager/gallery_picture', array(
          "picture" => $this->picture,
          "editRoute" => "@picture_gallery_edit",
          "object_class" => $this->gallery_class,
          "object_id" => $this->gallery_id,
       ));
      
      $message = $this->getContext()->getI18N()->__("The picture was saved successfully!", null, 'sf_gallery_manager');
    }
    else
      $status = false;

    return $this->renderText(json_encode(array("status" => $status, "message" => $message, "content" => $content)));
  }

  public function executeCreate(sfWebRequest $request)
  {
    $message = "";

    $this->setLayout(false);
    $this->getResponse()->setContentType('application/json');

    $this->GalleryPicture = $this->Gallery->newPicture();
    $FileManager = new sfFileManager();
    
    $status = false;

    if ($this->Gallery)
    {
      $user = Doctrine::getTable("sfGuardUser")->find($request->getParameter("user_id"));
      $this->getUser()->signIn($user);

      $file = $request->getFiles("filename");

      $dim = array('w' => sfConfig::get("app_max_upload_dim_w"), 'h' => sfConfig::get("app_max_upload_dim_h"));
      $filename = $FileManager->save($file['tmp_name'], $file['name'], 'image/jpeg', array('dim' => $dim));

      $this->GalleryPicture->setGalleryId($this->gallery_id);
      $this->GalleryPicture->setFilename($filename);

      $this->GalleryPicture->save();

      // If Gallery has no Pictures / Start Picture, set the Start Picture!
      if (!$this->Gallery->getFilename())
      {
        $this->Gallery->setStartPicture();
        $this->Gallery->save();
      }

      $content['item'] = $this->getPartial('sfPictureGalleryManager/gallery_picture', array(
          "picture" => $this->GalleryPicture,
          "editRoute" => "@picture_gallery_edit",
          "object_class" => $this->gallery_class,
          "object_id" => $this->gallery_id,
       ));

      $message = sprintf($this->getContext()->getI18N()->__("The picture %s was created successfully!", null, 'sf_gallery_manager'), $file['name']);
      $status = true;
    }

    return $this->renderText(json_encode(array("status" => $status, "message" => $message, "files" => $request->getFiles(), "content" => $content)));
  }

}
