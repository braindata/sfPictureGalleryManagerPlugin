function initFlash()
{
  $('body').ajaxSuccess(function(e, request, settings){
    var $flash = $('.sf_admin_flashes');

    try {
      var data = jQuery.parseJSON(request.responseText);
      if (data.message)
      {
        $flash.show();

        if (data.status){
          $flash.html("<div class='notice ui-state-highlight ui-corner-all'><span class='ui-icon ui-icon-info floatleft'></span>"+data.message+"</div>");
        } else{
          $flash.html("<div class='error ui-state-highlight ui-corner-all'><span class='ui-icon ui-icon-info floatleft'></span>"+data.message+"</div>");
        }

        $flash.stop().delay(4000).fadeOut('slow');
      }

    } catch (e) {}
  })
}

function initButtons($gallery, $trash)
{
  $( "#resort" ).button({
    disabled: true,
    icons: {primary: "ui-icon-refresh"}
  }).click( function(){
    if ($(this).button( "option", "disabled" ) == false)
    {
      $.getJSON($(this).attr("href"), $gallery.sortable('serialize'), function(data) {
        //alert(data);
        updatePositions();
        $( "#resort" ).button("disable");
      });
    }
    return false;
  });

  $( "#delete" ).button({
    disabled: false,
    icons: {primary: "ui-icon-trash"}
  }).click( function(){
    
    if (confirm(_confirm_delete))
    {
      $.getJSON($(this).attr("href"), $gallery.sortable('serialize'), function(data) {
        //console.log(data);
        $( "#remove" ).button("disable");
        $( "#resort" ).button("disable");
        
        $( "li" , $gallery ).each(function(index){
          $(this).delay(500).fadeOut();
        });
        
        $( "li" , $gallery ).remove();
        
      });
    }
    

    return false;
  });


  $( "#remove" ).button({
    disabled: true,
    icons: {primary: "ui-icon-delete"}
  }).click( function(){
    if ($(this).button( "option", "disabled" ) == false)
    {
      if (confirm(_confirm_delete))
      {
        $.getJSON($(this).attr("href"), serializeTrash(), function(data) {
          //alert(data);
          updatePositions();
          $( "#remove" ).button("disable");
          $( "#resort" ).button("disable");
          $( "ul", $trash).fadeOut(function(){
            $(this).remove();
          });
        });
      }
    }

    return false;
  });

  $( "#order_shuffle" ).button({
    icons: {primary: "ui-icon-shuffle"}
  }).click( function(){
    $( "#resort" ).button("enable");

    var items = new Array();
    $( "#gallery > li" ).each(function(index){
      items.push($(this));
      $(this).remove();
    });

    items.shuffle();

    $.each(items, function (key, value){
      $gallery.append(value);
      value.fadeTo('slow', 0.2).delay(500).fadeTo('slow', 1);

    });
  });

  $( "#order_reverse" ).button({
    icons: {primary: "ui-icon-arrowrefresh-1-n"}
  }).click( function(){
    $( "#resort" ).button("enable");

    var items = new Array();
    $( "#gallery > li" ).each(function(index){
      items.push($(this));
      $(this).remove();
    });

    items.reverse();

    $.each(items, function (key, value){
      $gallery.append(value);
      value.fadeTo('slow', 0.2).delay(500).fadeTo('slow', 1);
    });
  });

  function updatePositions(){
    $( "#gallery > li" ).each(function(index){
      $(this).find( "h5" ).text(index + 1);
    });
  }


  function serializeTrash(){
    var a = [];
    $( "li" , $trash ).each(function(index){
      var attr = $(this).attr( "id" );

      var id = attr.slice(attr.lastIndexOf("_")+1, attr.length);
      var list = attr.slice(0, attr.lastIndexOf("_"));
      a.push(list+"[]="+id);
    });

    return a.join("&");

    //alert(a.join("&"));
  }

  function arrayShuffle(){
    var tmp, rand;
    for(var i =0; i < this.length; i++){
      rand = Math.floor(Math.random() * this.length);
      tmp = this[i];
      this[i] = this[rand];
      this[rand] = tmp;
    }
  }

  Array.prototype.shuffle = arrayShuffle;

}


function initGalleryManager($gallery, $trash)
{
  galleryItemActions();

  initList();

  function initList()
  {
    $gallery.sortable({
      placeholder: 'ui-state-highlight',
      distance:    20,
      containment: $( "#sf_fieldset_gallery" ).length ? "#sf_fieldset_gallery" : "document", // stick to demo-frame if present
      update: function(event, ui){
        $( "#resort" ).button("enable");
      }
    });

    $gallery.disableSelection();

    // let the trash be droppable, accepting the gallery items
    $trash.droppable({
      accept: "#gallery > li",
      activeClass: "ui-state-highlight",
      drop: function( event, ui ) {
        deleteImage( ui.draggable );
      }
    });

    // let the gallery be droppable as well, accepting items from the trash
    $gallery.droppable({
      accept: "#trash li",
      activeClass: "custom-state-active",
      drop: function( event, ui ) {
        recycleImage( ui.draggable );
      }
    });
  }

  // image deletion function
  function deleteImage( $item ) {
    var recycle_icon = "<a href='javascript:void(0)' title='Recycle this image' class='ui-icon ui-icon-plus'>Recycle image</a>";
    $item.fadeOut(function() {
      var $list = $( "ul", $trash ).length ?
        $( "ul", $trash ) :
        $( "<ul class='gallery ui-helper-reset'/>" ).appendTo( $trash );

      $item.find( "a.ui-icon-trash" ).remove();
      $item.find( "a.ui-icon-pencil" ).hide();
      $item.append( recycle_icon ).appendTo( $list ).fadeIn(function() {
        $item
          .animate({width: "48px"})
          .find( "img" )
            .animate({height: "36px"});
      });

      $( "#remove" ).button("enable");
    });
  }

  // image recycle function
  function recycleImage( $item ) {
    var trash_icon = "<a href='javascript:void(0)' title='Delete this image' class='ui-icon ui-icon-trash'>Delete image</a>";
    $item.fadeOut(function() {
      $item
        .find( "a.ui-icon-plus" )
          .remove()
        .end()
        .css( "width", "96px")
        .append( trash_icon )
        .find( "img" )
          .css( "height", "72px" )
        .end()
        .appendTo( $gallery )
        .fadeIn();
      $item.find( "a.ui-icon-pencil" ).show();
      $trash.has("li").length ? "" : $( "#remove" ).button("disable");
    });
  }

  // image preview function, demonstrating the ui.dialog used as a modal window
  function editImage( $link ) {
    var src = $link.attr( "href" )
    var $item = $link.parent();
    var $next = $item.next("li");
    var $prev = $item.prev("li");

    // Build and append Div Container for modal dialog
    var div = $( "<div style='display: none;' ></div>")
              .appendTo( "body" );

    div.load(src, function() {
      var $d = $(this);
      var $h1 = $d.find( "h1" );

      $d.dialog({
        width: 500,
        modal: true,
        resizable: false,
        title: $h1.text()
      });

      $h1.remove();
      var $form = $d.find( "form" );
      
      var buttons = new Array();
      buttons.push({ 
        text: _save,
        click: function(){
          var action = $form.attr( "action" );
          // Save Data on Server!
          $.post(action, $form.serialize(), function(data) {
            if (data.status)
            {
              $item.effect("highlight");
              $item.replaceWith(data.content.item);
              
              $item = $(data.content.item);

            }
          });
        }
      });
      
      buttons.push({
        text: _close,
        click: function(){
          $(this).dialog( "close" );
        }
      });

    if ($prev.length != 0)
    {
    buttons.push({
        text: "<",
        click: function() {
            $(this).dialog( "close" );
            $link = $prev.find("a.ui-icon-pencil");
            editImage($link);
        }
    });
    }

    if ($next.length != 0)
    {
    buttons.push({
        text: ">",
        click: function() {
        $(this).dialog( "close" );
        $link = $next.find("a.ui-icon-pencil");
        editImage($link);
        }
    });
    }

      $d.dialog( "option", "buttons", buttons);
    });
  }

  // resolve the icons behavior with event delegation
  function galleryItemActions()
  {
    $( "ul.gallery > li" ).live("click", function( event ) {
      var $item = $( this ),
        $target = $( event.target );

      if ( $target.is( "a.ui-icon-trash" ) ) {
        deleteImage( $item );
      } else if ( $target.is( "a.ui-icon-zoomin" ) ) {
        $target.fancybox();
        $target.triggerHandler('click');
      } else if ( $target.is( "a.ui-icon-pencil" ) ) {
        editImage( $target );
      } else if ( $target.is( "a.ui-icon-plus" ) ) {
        recycleImage( $item );
      }

      return false;
    });

  }

}