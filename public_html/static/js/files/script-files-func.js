/**
 * create new folder start
 */
function ex_newFolder()
{
  ex_checkBody();
  ex_removeClass('selected focused zero');
  var myElement = ex_inputCreate('createfolder', null);

  if ( $('#explorer>ul li').is('#new-folder') )
  {
    $('#explorer #new-folder').stop().fadeTo(100, 0.1).fadeTo(200, 1.0);
    $('#explorer #new-folder').addClass('selected');
  }
  else
  {
    $(myElement).hide().prependTo('#explorer>ul').fadeIn(300);
  }

  $('#explorer #item-new-name').focus().select();
}


/**
 * rename file or folder start
 */
function ex_rename()
{
  ex_checkBody();
  // rename multiple file and folder
  if($('#explorer .selected').length > 1)
  {
    console.log('We are not handle rename multiple item!');
  }
  // rename one file or folder
  else
  {
    if ($('#explorer>ul li').hasClass('selected'))
    {
      $('#explorer>ul li.selected').addClass('item-rename');
      var myName    = $('#explorer>ul li.selected span.name').text();
      var myItem    = $('#explorer>ul li.selected').data('id');
      var myElement = ex_inputCreate('rename', myName, myItem);

      $('#explorer>ul li.selected span.name').html(myElement).select();
      $('#explorer #item-new-name').focus().select();
    }
  }
}


/**
 * create input for new folder or rename an item
 * @param  {[type]} _type  type of creating
 * @param  {[type]} _value default value if exist for rename
 * @return {[type]}        created element
 */
function ex_inputCreate(_type, _value, _item)
{
  $('body').addClass('editing');

  var myDefaultValue = _value? 'value="' + _value + '"'      : '';
  var myOldValue     = _value? ' data-value="' + _value + '"': '';
  var myItem         = _type == 'rename'? '<input type="hidden" name="items" value ="' + _item + '" />': '';


  var myElement =
    '<div id="edit-item" data-method="post" data-action="/$/' + _type + '" action="/$/' + _type + '">' +
      '<input id="item-new-name" type="text" name="fname" placeholder="Untitled Folder" ' + myDefaultValue + myOldValue + ' autocomplete="off" />' +
      myItem +
      '<button class="btn-fa-check"><i class="fa fa-check"></i></button>' +
      '<button class="btn-fa-times"><i class="fa fa-times"></i></button>' +
    '</div>';

  if(_type == 'createfolder')
  {
    myElement =
    '<li data-id="' + _type + '" id="new-folder" class="folder selected focused zero">' +
      '<span class="type fa fa-folder"></span> ' +
      '<span class="name">' +
        myElement +
      '</span> ' +
      '<span class="size">-</span> ' +
      '<span class="date">-</span>' +
    '</li>';
  }
  return myElement;
}


/**
 * check body class status and if needed update it
 * remove and cancel all operation
 */
function ex_checkBody()
{
  // console.log($('body').attr('class'));

  var classList = $('body').attr('class').split(/\s+/);
  $.each(classList, function(index, item)
  {
    switch (item)
    {
      case 'selectall':
        ex_selectAll();
        break;

      // do nothing
      case 'cut':
      case 'copy':
        // $('body').removeClass(item);
        // $('#explorer>ul li.cutted').removeClass('cutted');
        // $('#paste').fadeOut(100, function() { $(this).addClass('hide') });
        break;

      case 'editing':
        $('body').removeClass(item);
        ex_inputSubmit(false);
        break;

      case 'prop-edit':
        ex_showPropAdd();
        break;

      case 'tag-edit':
        ex_showAddTag();
        break;
    }
  });
}


/**
 * Escape function when esc pressed
 */
function ex_escape()
{
  if($('body').hasClass('editing'))
  {
    $('.btn-fa-times').trigger('click');
  }
  else if ( $('body').hasClass('prop-edit') )
  {
    ex_checkBody();
  }
  else if ( $('body').hasClass('tag-edit') )
  {
    ex_checkBody();
  }

  else if ( $('body').hasClass('cut') )
  {
    $('body').removeClass('cut');
    $('#explorer>ul li.cutted').removeClass('cutted');
    $('#paste').fadeOut(100, function() { $(this).addClass('hide') });
  }
  // at the end empty propbox
  else
  {
    ex_propHide();
    ex_hideOpr();
    $('#explorer>ul li.selected').removeClass('selected');
  }
}

/**
 * hide top menu operator btn
 */
function ex_hideOpr()
{
    $('#download').fadeOut(300, function() { $(this).addClass('hide') });
    $('#send-to-app').fadeOut(300, function() { $(this).addClass('hide') });
    $('#more').fadeOut(300, function() { $(this).addClass('hide') });
}


/**
 * select and deselect all items in explorer
 */
function ex_selectAll()
{
  if(ex_editing())
    return;

  if ($('#explorer>ul').hasClass('select-all'))
  {
    ex_removeClass('selected');
  }
  else
  {
    $('#explorer>ul li').not('.up').addClass('selected');
  }

  if ($('#explorer>ul li').is('#new-folder'))
  {
    $('#new-folder').fadeOut().remove();
  }

  $('#explorer>ul').toggleClass('select-all');
}

/**
 * return false if not editing else return number for each type of editin
 * @return false or number for each type
 */
function ex_editing()
{
  if ($('body').hasClass('editing'))
    return 1;

  if ($('body').hasClass('prop-edit'))
    return 2;

  if ($('body').hasClass('tag-edit'))
    return 3;

  if ($('body').hasClass('search'))
    return 4;

  return false;
}

// remove input class from all of items
function ex_removeClass(_className)
{
  $('#explorer>ul li').removeClass(_className);

}

// add a class to item
function ex_addClass(_className, _id)
{
  $('#explorer>ul li:eq('+ _id +')').addClass(_className);
  $('#prop-box-ul').html($('#explorer>ul>li.focused .meta').html());
}


function ex_itemFocus(_id)
{
  $('#explorer>ul li:eq('+ _id +')').addClass('focused');
}


function ex_itemSelectedFocusedZero(_id)
{
  ex_addClass('selected focused zero', _id);

  if($('#explorer>ul li:eq('+ _id +')').hasClass('file'))
  {
    $('#download').fadeIn(300).removeClass('hide').css('display', 'inline-block');
    $('#send-to-app').fadeIn(300).removeClass('hide').css('display', 'inline-block');

  }
  else
  {
    $('#download').fadeOut(300, function() { $(this).addClass('hide') });
    $('#send-to-app').fadeOut(300, function() { $(this).addClass('hide') });
  }

  // if user select a file then fill file addr to sent-app value for future use
  // var myAddr = $('#explorer>ul li:eq('+ _id +')').data('addr');
  // if(myAddr)
  // {
  //   myAddr = location.protocol + "//" + location.host + '/'+ myAddr;

  //   $('#download a').attr('href', myAddr);
  //   var myName = $('#explorer>ul li:eq('+ _id +') .name').html();
  //   var myID = $('#explorer>ul li:eq('+ _id +')').data('id');
  //   // console.log(myName);

  //   $('#download a').attr('download', myName);
  //   $('#send-to-app #sent-addr').attr('value', myAddr);
  //   $('#send-to-app #sent-id')  .attr('value', myID);
  // }
  // else
  // {
  //   $('#download').fadeOut(300, function() { $(this).addClass('hide') });
  //   $('#download a').attr('href', null);
  //   $('#send-to-app').fadeOut(300, function() { $(this).addClass('hide') });
  // }

  $('#more').fadeIn(300).removeClass('hide').css('display', 'inline-block');
}


function ex_items_select_focus_until(_id)
{
  var zero = $('#explorer>ul li.zero').index();

  var start = zero < _id ? zero : _id;
  start = parseInt(start);
  var end   = zero > _id ? zero : _id;
  end = parseInt(end);

  for (var i = start; i <= end; i++)
  {
    $('#explorer>ul li:eq('+ i +')').addClass('selected');
  }
  $('#explorer>ul li:eq('+ _id +')').addClass('focused');
}

function ex_showTrash()
{
  $('body').toggleClass('hide-trash');
}


/**
 * Empty prop box with effect
 */
function ex_propHide()
{
  $('#prop-box').children().each( function() {
      // $(this).fadeOut(100, function() { $(this).addClass('hide') });
      if($(this).is('#prop-box-ul'))
      {
        $(this).fadeOut(300, function()
          {
            $(this).children().remove();
          });
      }
      if(! $(this).is('#upload-notify'))
      {
        $(this).fadeOut(300);
      }
    });
}


/**
 * Fill prop box with effect
 */
function ex_propShow()
{
  $('#prop-box').children().each( function() {
      $(this).fadeIn(100);
    });
}


// check images exist or not
function ImageExist(_url)
{
  return true;
  // var img = new Image();
  // img.src = _url;
  // return img.height != 0;
}


/**
 * Run intro.js
 */
function ex_intro()
{
  introJs().exit();
  var str = 'introJs()' + $('#ifaq').data('options').toString() + '.start()';
  var fn = new Function(str);
  fn();
}


/**
 * init tag and fill taglist
 * @return {[type]} [description]
 */
function ex_tagInit()
{
  var tagDefault = $('#sp-tags').val();
  $('#tag-list').text('');
  if(tagDefault)
  {
    $.each(tagDefault.split(', '),function(t, item)
    {
      if(item.trim())
        $('#tag-list').append( "<span><i class='fa fa-times'></i>"+item+"</span>" );
    });
  }
}


/**
 * click on add tag
 * @return {[type]} [description]
 */
function ex_showAddTag()
{
  if($('body').hasClass('prop-edit'))
  {
    ex_showPropAdd();
  }


  if ($('#explorer>ul li.selected').length > 0)
  {
    $('body').toggleClass('tag-edit');
    $('#prop-box-tags .span4 i').toggleClass('fa-plus fa-times');
    $('#prop-box-tags #tagInput').slideToggle(300, function()
    {
      $('#tag-add').focus().select();
      if($('body').hasClass('tag-edit'))
      {
        $("#prop-box").animate({ scrollTop: $('#prop-box').prop("scrollHeight")}, 500);
      }
    });
  }
}


/**
 * Toggle add property form
 */
function ex_showPropAdd()
{
  if ($('body').hasClass('tag-edit'))
  {
    ex_showAddTag();
  }
  if ($('#explorer>ul li.selected').length > 0)
  {
    $('body').toggleClass('prop-edit');
    $('#prop-box h3 i').toggleClass('fa-plus fa-times');
    $('#prop-box-new').slideToggle(300, function()
    {
      $('#prop-box-new input[name="name"]').focus().select();
    });
  }
}


function ex_player(_key)
{
  if(ex_editing())
    return;

  // if player exist do right thing!
  if($('#prop-box-ul #player').length)
  {
    var player = $('#prop-box-ul #player').get(0);
    switch (_key)
    {
      case 'space':
        if(player.paused)
          player.play();
        else
          player.pause();
        break;

      case 'muted':
        if(player.muted)
          player.muted = false;
        else
          player.muted = true;
        break;

      case 'fullscreen':
        ex_fullscreen(player);
        break;

      case 'fastforward':
        var sTime = 10;
      case 'forward':
        sTime = sTime? sTime: 1;
      case 'fastbackward':
        sTime = sTime? sTime: -10;
      case 'backward':
        sTime = sTime? sTime: -1;

        var seekToTime = player.currentTime + sTime;
        if( seekToTime < 0 || seekToTime > player.duration )
            return;

        player.currentTime = seekToTime;
        break;

    }
    return true;
  }
  // if player does not exist
  else
  {
    return false;
  }
}

/**
 * Fullscreen requested element
 */
function ex_fullscreen(_el)
{
  if (_el.requestFullscreen)
  {
    _el.requestFullscreen();
  }
   else if (_el.mozRequestFullScreen)
  {
    _el.mozRequestFullScreen();
  }
  else if (_el.webkitRequestFullscreen)
  {
    _el.webkitRequestFullscreen();
  }
}


// intro js v1.1.1
(function(B,n){"object"===typeof exports?n(exports):"function"===typeof define&&define.amd?define(["exports"],n):n(B)})(this,function(B){function n(a){this._targetElement=a;this._options={nextLabel:"Next &rarr;",prevLabel:"&larr; Back",skipLabel:"Skip",doneLabel:"Done",tooltipPosition:"bottom",tooltipClass:"",highlightClass:"",exitOnEsc:!0,exitOnOverlayClick:!0,showStepNumbers:!0,keyboardNavigation:!0,showButtons:!0,showBullets:!0,showProgress:!1,scrollToElement:!0,overlayOpacity:0.8,positionPrecedence:["bottom",
"top","right","left"],disableInteraction:!1}}function M(a){var b=[],c=this;if(this._options.steps)for(var e=0,d=this._options.steps.length;e<d;e++){var f=H(this._options.steps[e]);f.step=b.length+1;"string"===typeof f.element&&(f.element=document.querySelector(f.element));if("undefined"===typeof f.element||null==f.element){var h=document.querySelector(".introjsFloatingElement");null==h&&(h=document.createElement("div"),h.className="introjsFloatingElement",document.body.appendChild(h));f.element=h;
f.position="floating"}null!=f.element&&b.push(f)}else{d=a.querySelectorAll("*[data-intro]");if(1>d.length)return!1;e=0;for(f=d.length;e<f;e++){var h=d[e],q=parseInt(h.getAttribute("data-step"),10);0<q&&(b[q-1]={element:h,intro:h.getAttribute("data-intro"),step:parseInt(h.getAttribute("data-step"),10),tooltipClass:h.getAttribute("data-tooltipClass"),highlightClass:h.getAttribute("data-highlightClass"),position:h.getAttribute("data-position")||this._options.tooltipPosition})}e=q=0;for(f=d.length;e<
f;e++)if(h=d[e],null==h.getAttribute("data-step")){for(;"undefined"!=typeof b[q];)q++;b[q]={element:h,intro:h.getAttribute("data-intro"),step:q+1,tooltipClass:h.getAttribute("data-tooltipClass"),highlightClass:h.getAttribute("data-highlightClass"),position:h.getAttribute("data-position")||this._options.tooltipPosition}}}e=[];for(d=0;d<b.length;d++)b[d]&&e.push(b[d]);b=e;b.sort(function(a,b){return a.step-b.step});c._introItems=b;N.call(c,a)&&(x.call(c),a.querySelector(".introjs-skipbutton"),a.querySelector(".introjs-nextbutton"),
c._onKeyDown=function(b){if(27===b.keyCode&&!0==c._options.exitOnEsc)void 0!=c._introExitCallback&&c._introExitCallback.call(c),y.call(c,a);else if(37===b.keyCode)C.call(c);else if(39===b.keyCode)x.call(c);else if(13===b.keyCode){var d=b.target||b.srcElement;d&&0<d.className.indexOf("introjs-prevbutton")?C.call(c):d&&0<d.className.indexOf("introjs-skipbutton")?(c._introItems.length-1==c._currentStep&&"function"===typeof c._introCompleteCallback&&c._introCompleteCallback.call(c),void 0!=c._introExitCallback&&
c._introExitCallback.call(c),y.call(c,a)):x.call(c);b.preventDefault?b.preventDefault():b.returnValue=!1}},c._onResize=function(a){t.call(c,document.querySelector(".introjs-helperLayer"));t.call(c,document.querySelector(".introjs-tooltipReferenceLayer"))},window.addEventListener?(this._options.keyboardNavigation&&window.addEventListener("keydown",c._onKeyDown,!0),window.addEventListener("resize",c._onResize,!0)):document.attachEvent&&(this._options.keyboardNavigation&&document.attachEvent("onkeydown",
c._onKeyDown),document.attachEvent("onresize",c._onResize)));return!1}function H(a){if(null==a||"object"!=typeof a||"undefined"!=typeof a.nodeType)return a;var b={},c;for(c in a)b[c]="undefined"!=typeof jQuery&&a[c]instanceof jQuery?a[c]:H(a[c]);return b}function x(){this._direction="forward";"undefined"===typeof this._currentStep?this._currentStep=0:++this._currentStep;if(this._introItems.length<=this._currentStep)"function"===typeof this._introCompleteCallback&&this._introCompleteCallback.call(this),
y.call(this,this._targetElement);else{var a=this._introItems[this._currentStep];"undefined"!==typeof this._introBeforeChangeCallback&&this._introBeforeChangeCallback.call(this,a.element);I.call(this,a)}}function C(){this._direction="backward";if(0===this._currentStep)return!1;var a=this._introItems[--this._currentStep];"undefined"!==typeof this._introBeforeChangeCallback&&this._introBeforeChangeCallback.call(this,a.element);I.call(this,a)}function y(a){var b=a.querySelector(".introjs-overlay");if(null!=
b){b.style.opacity=0;setTimeout(function(){b.parentNode&&b.parentNode.removeChild(b)},500);var c=a.querySelector(".introjs-helperLayer");c&&c.parentNode.removeChild(c);(c=a.querySelector(".introjs-tooltipReferenceLayer"))&&c.parentNode.removeChild(c);(a=a.querySelector(".introjs-disableInteraction"))&&a.parentNode.removeChild(a);(a=document.querySelector(".introjsFloatingElement"))&&a.parentNode.removeChild(a);if(a=document.querySelector(".introjs-showElement"))a.className=a.className.replace(/introjs-[a-zA-Z]+/g,
"").replace(/^\s+|\s+$/g,"");if((a=document.querySelectorAll(".introjs-fixParent"))&&0<a.length)for(c=a.length-1;0<=c;c--)a[c].className=a[c].className.replace(/introjs-fixParent/g,"").replace(/^\s+|\s+$/g,"");window.removeEventListener?window.removeEventListener("keydown",this._onKeyDown,!0):document.detachEvent&&document.detachEvent("onkeydown",this._onKeyDown);this._currentStep=void 0}}function J(a,b,c,e){var d="",f,h;b.style.top=null;b.style.right=null;b.style.bottom=null;b.style.left=null;b.style.marginLeft=
null;b.style.marginTop=null;c.style.display="inherit";"undefined"!=typeof e&&null!=e&&(e.style.top=null,e.style.left=null);if(this._introItems[this._currentStep]){d=this._introItems[this._currentStep];d="string"===typeof d.tooltipClass?d.tooltipClass:this._options.tooltipClass;b.className=("introjs-tooltip "+d).replace(/^\s+|\s+$/g,"");h=this._introItems[this._currentStep].position;if(("auto"==h||"auto"==this._options.tooltipPosition)&&"floating"!=h){d=h;f=this._options.positionPrecedence.slice();
h=F();var q=v(b).height+10,r=v(b).width+20,k=v(a),l="floating";k.left+r>h.width||0>k.left+k.width/2-r?(s(f,"bottom"),s(f,"top")):(k.height+k.top+q>h.height&&s(f,"bottom"),0>k.top-q&&s(f,"top"));k.width+k.left+r>h.width&&s(f,"right");0>k.left-r&&s(f,"left");0<f.length&&(l=f[0]);d&&"auto"!=d&&-1<f.indexOf(d)&&(l=d);h=l}d=v(a);a=v(b);f=F();switch(h){case "top":c.className="introjs-arrow bottom";z(d,15,a,f,b);b.style.bottom=d.height+20+"px";break;case "right":b.style.left=d.width+20+"px";d.top+a.height>
f.height?(c.className="introjs-arrow left-bottom",b.style.top="-"+(a.height-d.height-20)+"px"):c.className="introjs-arrow left";break;case "left":!0==this._options.showStepNumbers&&(b.style.top="15px");d.top+a.height>f.height?(b.style.top="-"+(a.height-d.height-20)+"px",c.className="introjs-arrow right-bottom"):c.className="introjs-arrow right";b.style.right=d.width+20+"px";break;case "floating":c.style.display="none";b.style.left="50%";b.style.top="50%";b.style.marginLeft="-"+a.width/2+"px";b.style.marginTop=
"-"+a.height/2+"px";"undefined"!=typeof e&&null!=e&&(e.style.left="-"+(a.width/2+18)+"px",e.style.top="-"+(a.height/2+18)+"px");break;case "bottom-right-aligned":c.className="introjs-arrow top-right";K(d,0,a,b);b.style.top=d.height+20+"px";break;case "bottom-middle-aligned":c.className="introjs-arrow top-middle";c=d.width/2-a.width/2;K(d,c,a,b)&&(b.style.right=null,z(d,c,a,f,b));b.style.top=d.height+20+"px";break;default:c.className="introjs-arrow top",z(d,0,a,f,b),b.style.top=d.height+20+"px"}}}
function z(a,b,c,e,d){if(a.left+b+c.width>e.width)return d.style.left=e.width-c.width-a.left+"px",!1;d.style.left=b+"px";return!0}function K(a,b,c,e){if(0>a.left+a.width-b-c.width)return e.style.left=-a.left+"px",!1;e.style.right=b+"px";return!0}function s(a,b){-1<a.indexOf(b)&&a.splice(a.indexOf(b),1)}function t(a){if(a&&this._introItems[this._currentStep]){var b=this._introItems[this._currentStep],c=v(b.element),e=10;"floating"==b.position&&(e=0);a.setAttribute("style","width: "+(c.width+e)+"px; height:"+
(c.height+e)+"px; top:"+(c.top-5)+"px;left: "+(c.left-5)+"px;")}}function O(){var a=document.querySelector(".introjs-disableInteraction");null===a&&(a=document.createElement("div"),a.className="introjs-disableInteraction",this._targetElement.appendChild(a));t.call(this,a)}function I(a){"undefined"!==typeof this._introChangeCallback&&this._introChangeCallback.call(this,a.element);var b=this,c=document.querySelector(".introjs-helperLayer"),e=document.querySelector(".introjs-tooltipReferenceLayer"),
d="introjs-helperLayer";v(a.element);"string"===typeof a.highlightClass&&(d+=" "+a.highlightClass);"string"===typeof this._options.highlightClass&&(d+=" "+this._options.highlightClass);if(null!=c){var f=e.querySelector(".introjs-helperNumberLayer"),h=e.querySelector(".introjs-tooltiptext"),q=e.querySelector(".introjs-arrow"),r=e.querySelector(".introjs-tooltip"),k=e.querySelector(".introjs-skipbutton"),l=e.querySelector(".introjs-prevbutton"),p=e.querySelector(".introjs-nextbutton");c.className=d;
r.style.opacity=0;r.style.display="none";if(null!=f){var g=this._introItems[0<=a.step-2?a.step-2:0];if(null!=g&&"forward"==this._direction&&"floating"==g.position||"backward"==this._direction&&"floating"==a.position)f.style.opacity=0}t.call(b,c);t.call(b,e);if((g=document.querySelectorAll(".introjs-fixParent"))&&0<g.length)for(d=g.length-1;0<=d;d--)g[d].className=g[d].className.replace(/introjs-fixParent/g,"").replace(/^\s+|\s+$/g,"");g=document.querySelector(".introjs-showElement");g.className=g.className.replace(/introjs-[a-zA-Z]+/g,
"").replace(/^\s+|\s+$/g,"");b._lastShowElementTimer&&clearTimeout(b._lastShowElementTimer);b._lastShowElementTimer=setTimeout(function(){null!=f&&(f.innerHTML=a.step);h.innerHTML=a.intro;r.style.display="block";J.call(b,a.element,r,q,f);e.querySelector(".introjs-bullets li > a.active").className="";e.querySelector('.introjs-bullets li > a[data-stepnumber="'+a.step+'"]').className="active";e.querySelector(".introjs-progress .introjs-progressbar").setAttribute("style","width:"+L.call(b)+"%;");r.style.opacity=
1;f&&(f.style.opacity=1);-1===p.tabIndex?k.focus():p.focus()},350)}else{var n=document.createElement("div"),l=document.createElement("div"),c=document.createElement("div"),m=document.createElement("div"),s=document.createElement("div"),D=document.createElement("div"),E=document.createElement("div"),u=document.createElement("div");n.className=d;l.className="introjs-tooltipReferenceLayer";t.call(b,n);t.call(b,l);this._targetElement.appendChild(n);this._targetElement.appendChild(l);c.className="introjs-arrow";
s.className="introjs-tooltiptext";s.innerHTML=a.intro;D.className="introjs-bullets";!1===this._options.showBullets&&(D.style.display="none");for(var n=document.createElement("ul"),d=0,B=this._introItems.length;d<B;d++){var z=document.createElement("li"),A=document.createElement("a");A.onclick=function(){b.goToStep(this.getAttribute("data-stepnumber"))};d===a.step-1&&(A.className="active");A.href="javascript:void(0);";A.innerHTML="&nbsp;";A.setAttribute("data-stepnumber",this._introItems[d].step);
z.appendChild(A);n.appendChild(z)}D.appendChild(n);E.className="introjs-progress";!1===this._options.showProgress&&(E.style.display="none");d=document.createElement("div");d.className="introjs-progressbar";d.setAttribute("style","width:"+L.call(this)+"%;");E.appendChild(d);u.className="introjs-tooltipbuttons";!1===this._options.showButtons&&(u.style.display="none");m.className="introjs-tooltip";m.appendChild(s);m.appendChild(D);m.appendChild(E);!0==this._options.showStepNumbers&&(g=document.createElement("span"),
g.className="introjs-helperNumberLayer",g.innerHTML=a.step,l.appendChild(g));m.appendChild(c);l.appendChild(m);p=document.createElement("a");p.onclick=function(){b._introItems.length-1!=b._currentStep&&x.call(b)};p.href="javascript:void(0);";p.innerHTML=this._options.nextLabel;l=document.createElement("a");l.onclick=function(){0!=b._currentStep&&C.call(b)};l.href="javascript:void(0);";l.innerHTML=this._options.prevLabel;k=document.createElement("a");k.className="introjs-button introjs-skipbutton";
k.href="javascript:void(0);";k.innerHTML=this._options.skipLabel;k.onclick=function(){b._introItems.length-1==b._currentStep&&"function"===typeof b._introCompleteCallback&&b._introCompleteCallback.call(b);b._introItems.length-1!=b._currentStep&&"function"===typeof b._introExitCallback&&b._introExitCallback.call(b);y.call(b,b._targetElement)};u.appendChild(k);1<this._introItems.length&&(u.appendChild(l),u.appendChild(p));m.appendChild(u);J.call(b,a.element,m,c,g)}!0===this._options.disableInteraction&&
O.call(b);l.removeAttribute("tabIndex");p.removeAttribute("tabIndex");0==this._currentStep&&1<this._introItems.length?(l.className="introjs-button introjs-prevbutton introjs-disabled",l.tabIndex="-1",p.className="introjs-button introjs-nextbutton",k.innerHTML=this._options.skipLabel):this._introItems.length-1==this._currentStep||1==this._introItems.length?(k.innerHTML=this._options.doneLabel,l.className="introjs-button introjs-prevbutton",p.className="introjs-button introjs-nextbutton introjs-disabled",
p.tabIndex="-1"):(l.className="introjs-button introjs-prevbutton",p.className="introjs-button introjs-nextbutton",k.innerHTML=this._options.skipLabel);p.focus();a.element.className+=" introjs-showElement";g=w(a.element,"position");"absolute"!==g&&"relative"!==g&&(a.element.className+=" introjs-relativePosition");for(g=a.element.parentNode;null!=g&&"body"!==g.tagName.toLowerCase();){c=w(g,"z-index");m=parseFloat(w(g,"opacity"));u=w(g,"transform")||w(g,"-webkit-transform")||w(g,"-moz-transform")||w(g,
"-ms-transform")||w(g,"-o-transform");if(/[0-9]+/.test(c)||1>m||"none"!==u&&void 0!==u)g.className+=" introjs-fixParent";g=g.parentNode}P(a.element)||!0!==this._options.scrollToElement||(m=a.element.getBoundingClientRect(),g=F().height,c=m.bottom-(m.bottom-m.top),m=m.bottom-g,0>c||a.element.clientHeight>g?window.scrollBy(0,c-30):window.scrollBy(0,m+100));"undefined"!==typeof this._introAfterChangeCallback&&this._introAfterChangeCallback.call(this,a.element)}function w(a,b){var c="";a.currentStyle?
c=a.currentStyle[b]:document.defaultView&&document.defaultView.getComputedStyle&&(c=document.defaultView.getComputedStyle(a,null).getPropertyValue(b));return c&&c.toLowerCase?c.toLowerCase():c}function F(){if(void 0!=window.innerWidth)return{width:window.innerWidth,height:window.innerHeight};var a=document.documentElement;return{width:a.clientWidth,height:a.clientHeight}}function P(a){a=a.getBoundingClientRect();return 0<=a.top&&0<=a.left&&a.bottom+80<=window.innerHeight&&a.right<=window.innerWidth}
function N(a){var b=document.createElement("div"),c="",e=this;b.className="introjs-overlay";if("body"===a.tagName.toLowerCase())c+="top: 0;bottom: 0; left: 0;right: 0;position: fixed;",b.setAttribute("style",c);else{var d=v(a);d&&(c+="width: "+d.width+"px; height:"+d.height+"px; top:"+d.top+"px;left: "+d.left+"px;",b.setAttribute("style",c))}a.appendChild(b);b.onclick=function(){!0==e._options.exitOnOverlayClick&&(void 0!=e._introExitCallback&&e._introExitCallback.call(e),y.call(e,a))};setTimeout(function(){c+=
"opacity: "+e._options.overlayOpacity.toString()+";";b.setAttribute("style",c)},10);return!0}function v(a){var b={};b.width=a.offsetWidth;b.height=a.offsetHeight;for(var c=0,e=0;a&&!isNaN(a.offsetLeft)&&!isNaN(a.offsetTop);)c+=a.offsetLeft,e+=a.offsetTop,a=a.offsetParent;b.top=e;b.left=c;return b}function L(){return 100*(parseInt(this._currentStep+1,10)/this._introItems.length)}var G=function(a){if("object"===typeof a)return new n(a);if("string"===typeof a){if(a=document.querySelector(a))return new n(a);
throw Error("There is no element with given selector.");}return new n(document.body)};G.version="1.1.1";G.fn=n.prototype={clone:function(){return new n(this)},setOption:function(a,b){this._options[a]=b;return this},setOptions:function(a){var b=this._options,c={},e;for(e in b)c[e]=b[e];for(e in a)c[e]=a[e];this._options=c;return this},start:function(){M.call(this,this._targetElement);return this},goToStep:function(a){this._currentStep=a-2;"undefined"!==typeof this._introItems&&x.call(this);return this},
nextStep:function(){x.call(this);return this},previousStep:function(){C.call(this);return this},exit:function(){y.call(this,this._targetElement);return this},refresh:function(){t.call(this,document.querySelector(".introjs-helperLayer"));t.call(this,document.querySelector(".introjs-tooltipReferenceLayer"));return this},onbeforechange:function(a){if("function"===typeof a)this._introBeforeChangeCallback=a;else throw Error("Provided callback for onbeforechange was not a function");return this},onchange:function(a){if("function"===
typeof a)this._introChangeCallback=a;else throw Error("Provided callback for onchange was not a function.");return this},onafterchange:function(a){if("function"===typeof a)this._introAfterChangeCallback=a;else throw Error("Provided callback for onafterchange was not a function");return this},oncomplete:function(a){if("function"===typeof a)this._introCompleteCallback=a;else throw Error("Provided callback for oncomplete was not a function.");return this},onexit:function(a){if("function"===typeof a)this._introExitCallback=
a;else throw Error("Provided callback for onexit was not a function.");return this}};return B.introJs=G});
