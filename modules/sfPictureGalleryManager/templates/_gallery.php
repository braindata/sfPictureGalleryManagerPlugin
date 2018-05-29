<script>
  jQuery(document).ready(function() {
    var $gallery = $( "#gallery" );
    var $trash = $( "#trash" );

    _close = '<?php echo __('Close', null, 'sf_gallery_manager') ?>';
    _restore = '<?php echo __('Restore', null, 'sf_gallery_manager') ?>';
    _resize = '<?php echo __('Resize', null, 'sf_gallery_manager') ?>';
    _save = '<?php echo __('Save', null, 'sf_gallery_manager') ?>';
    _upload_files = '<?php echo __('Upload Files', null, 'sf_gallery_manager') ?>';
    _clear_files = '<?php echo __('Clear Files', null, 'sf_gallery_manager') ?>';
    _selected_files = '<?php echo __('File Queue', null, 'sf_gallery_manager') ?>';
    _confirm_delete = '<?php echo __('Really remove items?', null, 'sf_gallery_manager') ?>';

    webdir = '<?php echo sfConfig::get("app_webdir"); ?>';
    create_url = '<?php echo $createUrl ?>';

    initGalleryManager($gallery, $trash);
    initButtons($gallery, $trash);

    Dropzone.autoDiscover = false;

    var myDropzone = new Dropzone("div#dropzone-area", {
      url: create_url,
      parallelUploads: 1,
      dictDefaultMessage: "Bilder für den Upload hier ablegen",
      acceptedFiles: "image/*"
    });

    myDropzone.on("success", function(file, response) {
      console.log(file);
      console.log(response);
      if (response.status){
          console.log(response.message);
          $gallery.append(response.content.item);
          myDropzone.removeFile(file);
      }
    });
  });

</script>

<div style="margin-top: 10px;" id="dropzone-area" class="dropzone needsclick dz-clickable"></div>

<div id="gallery_picture_actions" class="clearfix">
  <a href="<?php echo $resortUrl ?>" id="resort" class="fg-button fg-button-icon-left"><?php echo __('Update List', null, 'sf_gallery_manager') ?></a>
  <a href="javascript:void(0)" id="order_shuffle" class="fg-button fg-button-icon-left"><?php echo __('Mischen', null, 'sf_gallery_manager') ?></a>
  <a href="javascript:void(0)" id="order_reverse" class="fg-button fg-button-icon-left"><?php echo __('Umkehren', null, 'sf_gallery_manager') ?></a>

  <a href="<?php echo $removeUrl ?>" id="delete" class="fg-button fg-button-icon-left"><?php echo __('Delete Items', null, 'sf_gallery_manager') ?></a>
</div>

<div style="margin-top: 10px;">

<div id="gallery_container" class="ui-widget-content ui-state-default">
<ul id="gallery" class="gallery ui-helper-reset ui-helper-clearfix">
<?php foreach ($pictures as $picture): ?>
<?php include_partial('sfPictureGalleryManager/gallery_picture', array('picture' => $picture, 'editRoute' => $editRoute, 'object_class' => $object_class, 'object_id' => $object_id)) ?>
<?php endforeach; ?>
</ul>
</div>

<div id="trash_container" class="ui-widget-content ui-state-default">
  <h4 class="ui-widget-header"><span class="ui-icon ui-icon-trash">Trash</span> <?php echo __('Trash', null, 'sf_gallery_manager') ?></h4>
  <div id="trash"></div>
  <div style="clear: both;"></div>
    <div style="margin-top: 20px;">
    <a href="<?php echo $removeUrl ?>" id="remove" class="fg-button fg-button-icon-left"><?php echo __('Remove Items', null, 'sf_gallery_manager') ?></a>
  </div>
</div>

<div style="clear: both;"></div>

</div>

<!--<textarea id="testing" style="width: 600px; height: 500px;"></textarea>-->