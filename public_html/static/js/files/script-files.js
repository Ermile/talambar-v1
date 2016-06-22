// global variable definition
var CURRENTPATH;
var CURRENTDOMAIN;
var CLIPBOARD;
var STARTPATH;
var BASEURL = window.location.protocol + "//" + window.location.host + "/" + window.location.pathname.split('/')[1] + "/";


$(document).ready(function()
{
  // Run intro.js for first time login
  if ($('body').hasClass('first-time')) { ex_intro(); }

  // call from menu or static menu
  $('#newfolder')           .click(function()  { ex_newFolder();         });
  $('#more-selectall')      .click(function()  { ex_selectAll();         });
  $('#more-rename')         .click(function()  { ex_rename();            });
  $('#more-move')           .click(function()  { ex_clipboard('cut');    });
  $('#more-copy')           .click(function()  { ex_clipboard('copy');   });
  $('#more-show')           .click(function()  { ex_showTrash();         });
  $('#more-remove')         .click(function()  { ex_delete(false);       });
  $('#paste')               .click(function()  { ex_paste();             });
  $('#remove')              .click(function()  { ex_delete(false);       });
  $('#prop-submit')         .click(function()  { ex_addProp();           });
  $('#ifaq')                .click(function()  { ex_intro();             });
  $('#prop-box h3')         .click(function()  { ex_showPropAdd();       });
  $('#prop-box-tags .span4').click(function()  { ex_showAddTag();        });
  $('#tag-add-btn')         .click(function()  { addTag();               });
  $("#search i")            .click(function()  { ex_search();            });
  $("#download-link")       .click(function()  { ex_download();          });
  $("#send-to-app a")       .click(function(e) { ex_sendToApps(this, e); });


  // prevent closing modal result
  // $(document.body).children('.modal#modal_result').addBack().off("click");
  // on click buttons redirect to root
  $('#modal_result .button').click(function() { reDraw('/'); });



  $("#search").focusin(function()
  {
    $('body').addClass('search');
  }).focusout(function(){
    $('body').removeClass('search');
  });



  $('#prop-box-ul').on('click', '.span4', function( ) {
    if ( $(this).children().length > 0 )
    {
      $(this).parent().ajaxify({
        ajax: {
          data: {
            items: $('#prop-box').attr('data-id'),
            id: $(this).parent().data('id')
          }
        }
      });

      $(this).parent().fadeOut(300).remove();
    }
  });

  // handle all keydown on keyboard
  $(document).keydown(function(e) { event_corridor.call(this, e, $('#explorer>ul li.focused')[0], e.which ); });

  $('#explorer').on("click", ".btn-fa-times",    function(e) { e.preventDefault(); ex_inputSubmit(false); });
  $('#explorer').on("click", ".btn-fa-check",    function(e) { e.preventDefault(); ex_inputSubmit(true); });
  $('#explorer').on("click", "#item-new-name",   function(e) { e.preventDefault(); });
  $('#explorer').on("click", ".fav i",           function(e) { ex_favorites(this); });
  // remove item on click times icon
  $('#prop-box').on('click', '#tag-list span i', function( ) { ex_tagRemove($(this));});

  ex_tagInit();
});


/**
 * this function running after each pushstate and navigate in pages
 */
route('*', function()
{
  CURRENTDOMAIN = window.location.protocol + "//" + window.location.host + "/";
  CURRENTPATH   = (location.pathname).replace(/^\/+/, '');
  if(CURRENTPATH == undefined)
  {
    CURRENTPATH = "";
  }
  // if we use files as fake subdomain
  if(CURRENTPATH.indexOf("files") > -1)
  {
    CURRENTDOMAIN = CURRENTDOMAIN + "files/";
    CURRENTPATH   = CURRENTPATH.substring(6, CURRENTPATH.length);
  }


  // $(".light-gallery", this).lightGallery();
  var explorer = this instanceof Document ? $('#explorer') : $(this).parents('#explorer');
  $('ul li', explorer).first().addClass('zero focused');

  // handle all click, dbl click
  $('ul li', explorer).click(function(e)    { event_corridor(e, e.currentTarget, 'click');    });
  $('ul li', explorer).dblclick(function(e) { event_corridor(e, e.currentTarget,  'dblclick'); });

  // call on click menu items

  // Update hidden input value in upload modal to CURRENTPATH
  $('#form_uploader input[name="location"]').val(CURRENTPATH);

  $(window).off('statechange');
  $(window).on('statechange', function()
  {
    ex_setButtons();
    ex_checkLocation();
  });
});


/**
 * check current location and highligh it
 */
function ex_checkLocation()
{
  $('#page-sidebar a').removeClass('active');
  if(CURRENTPATH.indexOf("favorites") > -1)
  {
    $('#page-sidebar #ifavorites').addClass('active');
  }
  else if(CURRENTPATH.indexOf("tags") > -1)
  {
    $('#page-sidebar #itags').addClass('active');
  }
  else if(!CURRENTPATH)
  {
    $('#page-sidebar #ihome').addClass('active');
  }
}
ex_checkLocation();


/**
 * set button status and disable on system locations
 */
function ex_setButtons()
{
  if(CURRENTPATH.substr(0, 1) === '$')
  {
    $('#upload').parent().fadeOut(300, function() { $(this).addClass('hide') });
    $('#newfolder').fadeOut(300, function() { $(this).addClass('hide') });
    $('#paste').addClass('hide');
  }
  else
  {
    $('#upload').parent().fadeIn(300).removeClass('hide').css('display', 'inline-block');
    $('#newfolder').fadeIn(300).removeClass('hide').css('display', 'inline-block');
  }
}
ex_setButtons();


/**
 * Navigate user to search page
 */
function ex_search()
{
  var q = $("#search input").val();
  if(q)
  {
    reDraw('$/search?q=' + q);
  }
}


function addTag()
{
  var tag = $('#tag-add');
  var newTag = tag.val().trim();
  if(newTag && newTag.length > 0)
  {
    var exist = false;
    $.each($('#sp-tags').val().split(', '),function(t, item)
    {
      if(item == newTag) {exist = t+1;}
    });

    if(exist)
    {
      existEl = $("#tag-list span:nth-child("+exist+")" );
      bg = existEl.css('background-color');
      existEl.css('background-color', '#B34E4D');
      setTimeout(function () { existEl.css("background-color",bg) }, 500);
    }
    else
    {
      $('#tag-list').append( "<span><i class='fa fa-times'></i>"+newTag+"</span>" );
      $('#sp-tags').val( $('#sp-tags').val() + newTag+', ' );

      $('#tagInput').ajaxify({
        ajax:
        {
          data:
          {
            tags:  newTag,
            items: $('#prop-box').attr('data-id')
          }
        }
      });
    }
  }
  tag.val('');
}

function ex_tagRemove(_self)
{
  var span = _self.parent();
  $('#sp-tags').val($('#sp-tags').val().replace(span.text()+', ', ''));
  $('#tag-list').ajaxify({
    ajax: {
      data: {
        tags: span.text(),
        items: $('#prop-box').attr('data-id')
      }
    }
  });
  span.remove();
}


/**
 * Navigate to new location with redraw func by giving item
 * @param  {[type]} _self item element
 */
function ex_navigate(_self)
{
  // first init, if pass up going one level up
  var newLocation = CURRENTPATH;
  if(_self === 'up')
  {
    // if url has / remove it from end of url
    if (CURRENTPATH.substr(-1) == '/')
    {
      newLocation = newLocation.substr(0, newLocation.length - 2);
    }
    // splite url with slash into array
    newLocation = newLocation.split('/');
    // remove last element of array
    newLocation.pop();
    // if has $ in url remove another level
    if (/^(\$)(.*)/g.test(CURRENTPATH))
    {
      newLocation.pop();
    }
    // convert array to string wigh slash seperator
    newLocation = newLocation.join('/');
  }
  else
  {
    // name of the double clicked folder
    newLocation = CURRENTPATH + "/" + $('.name', _self).text();
  }

  // redraw if new location and old one is different!
  if(CURRENTPATH !== newLocation)
  {
    if(newLocation.length == 0 )
    {
      newLocation = '/';
    }
    // redraw with new address
    reDraw(newLocation);
  }
}


/**
 * redraw explorer items
 * @return {[type]} [description]
 */
function reDraw(_path)
{
  ex_propHide();
  ex_hideOpr();

  $("#explorer ul").fadeOut(100);
  if (_path == undefined)
  {
    _path = '/'+CURRENTPATH;
  }
  if(_path.indexOf("http") < 0)
  {
    _path = '/files' + _path;
  }

  Navigate({ url: _path });
}


/**
 * set or unset favorites for items
 * @return {[type]} [description]
 */
function ex_favorites(_self)
{
  $(_self).toggleClass('fa-star-o fa-star');
  var ajx = $(_self).ajaxify({
    ajax: {
      data: {
        location: CURRENTPATH,
        items: $(_self).parents('li').data('id'),
        status: $(_self).hasClass('fa-star-o') ? status = 0 : status = 1
      }
    }
  });
}


/**
 * when user submit form with submit btn or cancel it
 * @param  {[type]} _submit type of submit, for cancel need to pass false
 * @return {[type]}         [description]
 */
function ex_inputSubmit(_submit)
{
  // user press submit and want to proceed
  if(typeof _submit === 'undefined' || _submit == true)
  {
    var myInputVal    = $('#item-new-name').val();
    var myInputOldVal = $('#item-new-name').attr('data-value');
    myInputVal        = $.trim(myInputVal);
    myInputOldVal     = $.trim(myInputOldVal);

    if(myInputVal == myInputOldVal || myInputVal.length < 1)
    {
      // console.log('not changed!');
      ex_inputSubmit(false);
      return;
    }

    if(myInputVal == 'undefined')
    {
    }
    if(myInputVal == '' )
    {
      myInputVal = 'Untitled Folder';
    }

console.log(myInputVal);
    // send item name as ajax, then redraw items
    $('#edit-item').ajaxify({
      ajax:
      {
        data :
        {
          location: CURRENTPATH,
          value: myInputVal,
          items: $('#prop-box').attr('data-id')
        }
      }
    });
    $('#new-folder .name').html(myInputVal);
    $('#new-folder').removeClass('selected').removeAttr('id');
    reDraw();
  }
  // user want to cancel form
  else
  {
    // console.log('cancel');
    // return;
    // if user cancel creating new folder
    if ( $('#explorer>ul li').is('#new-folder') )
    {
      $('#explorer #new-folder').hide(300).remove();
    }
    // else if canceling rename
    else
    {
      var myOldVal = $('#explorer input').attr('value');
      $('#explorer .item-rename .name').html(myOldVal);
      ex_removeClass('item-rename');

    }
  }

  $('body').removeClass('editing');
}


/**
 * running after deleting some items
 * @param  {[type]} trash [description]
 * @return {[type]}       [description]
 */
function ex_delete(_trash)
{
  var myDelete = [];

  if (_trash)
  {
    $('#explorer>ul li.selected').hide(300, function() { $(this).remove(); });
  }
  else
  {
    $('#explorer>ul li.selected.trash').hide(300, function() { $(this).remove(); });
    $('#explorer>ul li.selected').addClass('trash');
  }

  $('#explorer>ul li.selected').each(function() {
    myDelete.push($(this).data('id'));
  });

  $('#more-remove').ajaxify({
    ajax: {
      data: {
        location: CURRENTPATH,
        items: myDelete,
        shift: _trash
      }
    }
  });
}


/**
 * move some item
 */
function ex_clipboard(_action)
{
  CLIPBOARD = [];

  if ( $('#explorer>ul li.selected').length > 0)
  {
    if ( _action == 'copy' )
    {
      $('body').addClass('copy');
    }
    else if ( _action == 'cut' )
    {
      $('body').addClass('cut');
      $('#explorer>ul li.selected').addClass('cutted');
    }

    $('#explorer>ul li.selected').each(function() {
      CLIPBOARD.push( $(this).data('id') );
    });
    STARTPATH = CURRENTPATH;
  }

  $('#paste').fadeIn(300).removeClass('hide').css('display', 'inline-block');
}


/**
 * paste some item to new location
 */
function ex_paste()
{
  var myType;

  if ( $('body').hasClass('cut') )
  {
    myType = 'cut';
    $('body').removeClass('cut');

    $('#explorer > ul li.cutted').removeClass('cutted');
  }
  else if ( $('body').hasClass('copy') )
  {
    myType = 'copy';
    $('body').removeClass('copy');
  }

  if ( myType != 'undefined' && CURRENTPATH != STARTPATH )
  {
    $('#paste').ajaxify({
      ajax: {
        data: {
          location: CURRENTPATH,
          items: CLIPBOARD,
          type: myType
        }
      }
    });
  }

  $('#paste').fadeOut(300, function() { $(this).addClass('hide'); reDraw(); });
}


/**
 * click on some item in explorer
 */
function ex_dblClickItems(_self)
{
  if(!$(_self).hasClass('selected'))
    return;

  if($(_self).hasClass('folder'))
  {
    if($('body').hasClass('cut'))
    {
      var index   = CLIPBOARD.indexOf($(_self).attr('data-id'));
      if (index > -1)
      {
        CLIPBOARD.splice(index, 1);
      }
    }

    ex_navigate(_self);
  }
  else if($(_self).hasClass('up'))
  {
    ex_navigate(_self);
  }
  else
  {
    // Trigger download link on dbl click items
    document.getElementById("download-link").click();
  }
}

/**
 * before click download, fill href from selected item for dl button
 */
function ex_download()
{
  var myItem;
  if ( $('#explorer>ul>li.selected').length > 1 )
  {
    myItem = $('#explorer>ul>li.focused').data('id');
  }
  else
  {
    myItem = $('#explorer>ul>li.selected').data('id');
  }

  $('#download-link').attr("href", BASEURL+"$/dl?id=" + myItem);
}

/**
 * shwo property of selected item
 */
function ex_showProp()
{
  if ( $('#explorer>ul>li.selected').length > 1 )
  {
    // console.log(123);
    var items = [];
    $('#explorer>ul>li.selected').each(function() {
      items.push($(this).data('id'))
    });
    console.log(items.toString());
    $('#prop-box').attr('data-id', items.toString());
  }
  else
  {
    if($('body').hasClass('editing'))
    {
      return;
    }

    if ($('#explorer>ul>li.selected').hasClass('up'))
    {
      ex_propHide();
      return;
    }

    // $('#prop-box > h3').removeClass('hide');
    // $('#prop-box').hide(300);
    // ex_propHide();
    ex_propShow();

    var myItem = $('#explorer>ul>li.selected').data('id');

    $('#prop-box-ul').ajaxify(
    {
      ajax:
      {
        data:
        {
          location: CURRENTPATH,
          items: myItem
        },
        abort: true,
        success: function(e, data, x)
        {
          var myData = x.responseJSON.datatable;
          var elements = '';
          // console.log(myData);
          if(myData == undefined || myData == '')
          {
            return;
          }

          for (var key in myData)
          {
            var el_new = null;
            switch (key)
            {
              // for thumb create thumb shower element
              case 'thumb':
                if ( ImageExist(myData[key]) )
                {
                  el_new = '<li class="media-container"><img src="' + myData[key] + '" /></li>';
                }
                else
                {
                  myData[key] = 'static/images/error.png';
                  // var el_new = '<li class="media-container"><span class="fa-stack fa-lg fa-3x"><i class="fa fa-picture-o fa-stack-1x"></i><i class="fa fa-ban fa-stack-2x text-danger"></i></span></li>';
                  el_new = '<li class="media-container"><img src="' + myData[key] + '" /></li>';
                }
                elements = el_new + elements;
                break;

              // skip on these two type
              case 'audio-type':
              case 'video-type':
                continue;
                break;

              // on audio type create audio element
              // on video type create video element
              case 'audio':
              case 'video':
                el_new = '<' + key + ' controls id="player"><source src="' + myData[ key ] + '" type="' + myData[ key +'-type'] + '"></' + key + '>';
                elements = el_new + elements;
                break;

              // copy id to propbox data-id attribute
              case 'id':
                $('#prop-box').attr('data-id', myData[key]);
                break;

              // for porp box show the data of propbox
              case 'prop':
                var prop = myData[key];
                for (var p in prop)
                {
                  var _prop = prop[p];
                  for (var pp in _prop)
                  {
                    el_new = '<li class="row auto" data-action='+BASEURL+'"$/propremove" data-method="post" data-id="' + p + '"><span class="span4">' + pp + ' <i class="fa fa-times"></i></span><span class="span8"> ' + _prop[pp] + ' </span></li>';
                    elements += el_new;
                  }
                }
                break;

              // fill tags and init tag func
              case 'tags':
                $('#sp-tags').val( myData['tags'] );
                ex_tagInit();
                break;

              case 'description':
              case 'title':
                break;


              default:
                el_new = '<li class="row auto"><span class="span4">' + key + '</span><span class="span8">' + myData[key] + '</span></li>';
                elements += el_new;
                break;
            }

          }
          // $('#prop-box-ul').hide().html(elements).fadeIn();
          $('#prop-box-ul').hide().html(elements).fadeIn();
          ex_propShow();

          // if prop box has image, after load showing with fade
          $('.media-container img').on('load', function ()
          {
            $(this).parent().fadeIn(300);
          });
        }
      }
    });
  }
}

/**
 * add new property
 */
function ex_addProp()
{
  var _name  = $.trim($('#prop-box-new input[name="name"]').val());
  var _value = $.trim($('#prop-box-new input[name="value"]').val());

  if ( _name.length === 0 )
  {
    $('#prop-box-new input[name="name"]').addClass('input-error').select();

    var backgroundInterval = setTimeout(function(){
      $('#prop-box-new input[name="name"]').removeClass('input-error');
    }, 300);
  }
  else if ( _value.length === 0 )
  {
    $('#prop-box-new input[name="value"]').addClass('input-error').select();

    var backgroundInterval = setTimeout(function(){
      $('#prop-box-new input[name="value"]').removeClass('input-error');
    }, 300);
  }
  else if ( _name.length !== 0 && _value.length !== 0 )
  {
    var element = '<li class="row auto" style="display:none"><span class="span4">' + _name + '</span><span class="span8">' + _value + '</span></li>'
    $(element).appendTo('#prop-box-ul').slideDown(300);

    $('#prop-box-new').ajaxify({
      ajax: {
        data: {
          name: _name,
          value: _value,
          items: $('#prop-box').attr('data-id')
        }
      }
    });

    // After adding property
    $('#prop-box-new input[name="name"]').val('');
    $('#prop-box-new input[name="value"]').val('');
  }
}

/**
 * prepare form to send to apps
 */
function ex_sendToApps(_this, _e)
{
  // prevent link to fire!
  _e.preventDefault();

  // set form action dynamically
  var appLoc = $(_this).attr('href');
  if(appLoc)
  {
    $('#send-to-app form').attr('action', appLoc);

    // set form input value dynamically from selected app
    $('#send-to-app form input[name="app"]').val( $(_this).children('span').text().trim());
    // add data- dynamically to form for sending to app
    $('#send-to-app form div').text("");
    var dataAttrs = $(_this).get(0).attributes;
    for (i = 0; i < dataAttrs.length; i++)
    {
      if ( 'data-' === dataAttrs[i].name.substring(0,5) )
      {
        var myEl = "<input type='hidden' name='" + dataAttrs[i].name.substring(5)
          + "' value='" + dataAttrs[i].value + "'>";
        $('#send-to-app form div').append(myEl);
      }
    }

    var myItem;
    if ( $('#explorer>ul>li.selected').length > 1 )
    {
      myItem = $('#explorer>ul>li.focused').data('id');
    }
    else
    {
      myItem = $('#explorer>ul>li.selected').data('id');
    }


    $('#send-to-app form div').ajaxify(
    {
      ajax:
      {
        data:
        {
          items: myItem,
        },
        abort: true,
        success: function(e, data, x)
        {
          var myAddr   = x.responseJSON.addr;
          var myExt    = x.responseJSON.ext;
          var myCode   = x.responseJSON.authcode;
          var propCode = $('#prop-box').attr('data-id');

          if(myCode && myAddr)
          {
            if(myCode == propCode && propCode == myItem )
            {
              // set auth code and addr of file
              $('#send-to-app form input[name="authcode"]').val(myCode);
              $('#send-to-app form input[name="addr"]').val(myAddr);
              $('#send-to-app form input[name="ext"]').val(myExt);
              // console.log(myExt);
              $('#send-to-app form').submit();
            }
            else
            {
              // something is wrong!
              console.log('something is wrong!');
              return;
            }
          }
          else
          {
            // cancel, because can't get authcode and addr of file
            return;
          }
        }
      }
    });
  }
}

