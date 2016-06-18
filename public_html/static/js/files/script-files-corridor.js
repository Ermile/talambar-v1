/**
 * corridor of all events on keyboard and mouse
 * @param  {[type]} e     the element that event doing on that
 * @param  {[type]} _self seperated element for doing jobs on it
 * @param  {[type]} _key  the key pressed or click or another events
 * @return {[type]}       void func not returning value! only doing job
 */
function event_corridor(e, _self, _key)
{
  _self = $(_self);
  var cid    = parseInt(_self.index());
  var lastid = parseInt($('#explorer>ul li').length) - 1;

  var ctrl   = e.ctrlKey  ? 'ctrl'  : '';
  var shift  = e.shiftKey ? 'shift' : '';
  var alt    = e.altKey   ? 'alt'   : '';
  var mytxt  = String(_key) + ctrl + alt + shift;
  // console.log(mytxt);

  switch(mytxt)
  {
    // ---------------------------------------------------------- BackSpace
    case '8':               // Back Space
      switch (ex_editing())
      {
        // edit explorer
        case 1:
        // edit property
        case 2:
        // edit tag
        case 3:
        // search
        case 4:
          break;

        default:
          // ex_navigate(_self);
          // history.back();
          e.preventDefault();
          break;
      }
      break;


    // ---------------------------------------------------------- Enter
    case '13':              // Enter
      switch (ex_editing())
      {
        // edit explorer
        case 1:
          $('#edit-item .btn-fa-check').trigger('click');
          break;

        // edit property
        case 2:
          ex_addProp();
          preventDefault();
          break;

        // edit tag
        case 3:
          addTag();
          e.preventDefault();
          break;
        // search
        case 4:
          ex_search();
          e.preventDefault();
          break;

        default:
          ex_dblClickItems(_self);
          break;
      }
      break;


    // ---------------------------------------------------------- Escape
    case '27':              //Escape
      ex_escape();
      break;


    // ---------------------------------------------------------- Space
    case '32':              // space
    case '32shift':         // space + shift
      // if player exist do right thing!
      ex_player('space');

      if(!_self.hasClass('selected'))
      {
        ex_removeClass('selected focused zero');
        _self.addClass('selected');
        ex_showProp();
      }
      break;

    case '32ctrl':          // space + ctrl
    case '32ctrlshift':     // space + ctrl + shift
      _self.toggleClass('selected');
      if(_self.hasClass('selected'))
      {
        ex_showProp();
      }
      break;


    // ---------------------------------------------------------- Page Up
    case '33':              // PageUP
      if(ex_editing())
        return;

      next = cid > 10 ? cid - 10 : 0;
      if(cid !== next)
      {
        ex_removeClass('selected focused zero');
        ex_itemSelectedFocusedZero(next);
        ex_showProp();
      }
      break;

    case '33ctrl':          // PageUP + ctrl
      // does not work!
      // if(ex_editing())
      //   return;

      // next = cid > 10 ? cid - 10 : 0;
      // ex_removeClass('focused');
      // ex_itemFocus(next);
      break;

    case '33shift':         // PageUP + Shift
      if(ex_editing())
        return;

      next = cid > 10 ? cid - 10 : 0;
      ex_removeClass('selected focused');
      ex_items_select_focus_until(next);
      break;

    case '33ctrlshift':     // PageUP + Ctrl + Shift
      if(ex_editing())
        return;

      next = cid > 10 ? cid - 10 : 0;
      ex_removeClass('focused');
      ex_items_select_focus_until(next);
      break;


    // ---------------------------------------------------------- Page Down
    case '34':              // PageDown
      if(ex_editing())
        return;

      next = cid + 10 >= lastid ? lastid : cid + 10;
      if(cid !== next)
      {
        ex_removeClass('selected focused zero');
        ex_itemSelectedFocusedZero(next);
        ex_showProp();
      }
      break;

    case '34ctrl':          // PageDown + Ctrl
      // does not work!
      // if(ex_editing())
      //   return;

      // next = cid + 10 >= lastid ? lastid : cid + 10;
      // ex_removeClass('focused');
      // ex_itemFocus(next);
      break;

    case '34shift':         // PageDown + Shift
      if(ex_editing())
        return;

      next = cid + 10 >= lastid ? lastid : cid + 10;
      ex_removeClass('selected focused');
      ex_items_select_focus_until(next);
      break;

    case '34ctrlshift':     // PageDown + Ctrl + Shift
      if(ex_editing())
        return;

      next = cid + 10 >= lastid ? lastid : cid + 10;
      ex_removeClass('focused');
      ex_items_select_focus_until(next);
      break;


    // ---------------------------------------------------------- End
    case '35':              // End
      if(ex_editing())
        return;

      if(cid !== lastid)
      {
        ex_removeClass('selected focused zero');
        ex_itemSelectedFocusedZero(lastid);
        ex_showProp();
      }
      break;

    case '35ctrl':          // End + Ctrl
      if(ex_editing())
        return;

      ex_removeClass('focused');
      ex_itemFocus(lastid);
      break;

    case '35shift':         // End + Shift
      if(ex_editing())
        return;

      ex_removeClass('selected focused');
      ex_items_select_focus_until(lastid);
      break;

    case '35ctrlshift':     // End + Ctrl + Shift
      if(ex_editing())
        return;

      ex_removeClass('focused');
      ex_items_select_focus_until(lastid);
      break;


    // ---------------------------------------------------------- Home
    case '36':              // Home
      if(ex_editing())
        return;
      if(cid !== 0)
      {
        ex_removeClass('selected focused zero');
        ex_itemSelectedFocusedZero(0);
        ex_showProp();
      }
      break;

    case '36ctrl':          // Home + Ctrl
      if(ex_editing())
        return;

      ex_removeClass('focused');
      ex_itemFocus(0);
      break;

    case '36shift':         // Home + Ctrl
      if(ex_editing())
        return;

      ex_removeClass('selected focused');
      ex_items_select_focus_until(0);
      break;

    case '36ctrlshift':     // Home + Ctrl + Shift
      if(ex_editing())
        return;

      ex_removeClass('focused');
      ex_items_select_focus_until(0);
      break;


    // ---------------------------------------------------------- Left
    case '37':              // left
      // if player exist do right thing!
      ex_player('backward');
      break;

    case '37alt':           // left + Alt
      history.back();
      e.preventDefault();
      break;

    case '37ctrl':          // left + ctrl
      // if player exist do right thing!
      ex_player('fastbackward');
      break;

    // ---------------------------------------------------------- Up
    case '38':              // up
      if(ex_editing())
        return;

      next = cid > 0 ? cid - 1 : 0;
      if(cid !== next)
      {
        ex_removeClass('selected focused zero');
        ex_itemSelectedFocusedZero(next);
        ex_showProp();
      }
      break;

    case '38alt':           // up + alt
      ex_navigate('up');
      break;

    case '38ctrl':          // up + ctrl
      if(ex_editing())
        return;

      next = cid > 0 ? cid - 1 : 0;
      ex_removeClass('focused');
      ex_itemFocus(next);
      break;

    case '38shift':         // up + shift
      if(ex_editing())
        return;

      next = cid > 0 ? cid - 1 : 0;
      ex_removeClass('focused selected');
      ex_items_select_focus_until(next);
      break;

    case '38ctrlshift':     // up + ctrl + shift
      if(ex_editing())
        return;

      next = cid > 0 ? cid - 1 : 0;
      ex_removeClass('focused zero');
      ex_itemSelectedFocusedZero(next);
      break;


    // ---------------------------------------------------------- Right
    case '39':              // right
      // if player exist do right thing!
      ex_player('forward');
      break;

    case '39alt':           // right + Alt
      history.forward();
      e.preventDefault();
      break;

    case '39ctrl':          // right + ctrl
      // if player exist do right thing!
      ex_player('fastforward');
      break;

    // ---------------------------------------------------------- Down
    case '40':              // down
      if(ex_editing())
        return;

      next = cid >= lastid ? lastid : cid + 1;
      if(cid !== next)
      {
        ex_removeClass('selected focused zero');
        ex_itemSelectedFocusedZero(next);
        ex_showProp();
      }
      break;

    case '40ctrl':          // down + ctrl
      if(ex_editing())
        return;

      next = cid >= lastid ? lastid : cid + 1;
      ex_removeClass('focused');
      ex_itemFocus(next);
      break;

    case '40shift':         // down + shift
      if(ex_editing())
        return;

      next = cid >= lastid ? lastid : cid + 1;
      ex_removeClass('focused selected');
      ex_items_select_focus_until(next);
      break;

    case '40ctrlshift':     // down + shift
      if(ex_editing())
        return;

      next = cid >= lastid ? lastid : cid + 1;
      ex_removeClass('focused zero');
      ex_itemSelectedFocusedZero(next);
      break;


    // ---------------------------------------------------------- Delete
    case '46':              // delete
      if(ex_editing())
        return;

      ex_delete(false);
      break;

    case '46shift':         // delete + shift
      if(ex_editing())
        return;

      ex_delete(true);
      break;



    // ---------------------------------------------------------------------- shortcut
    case '65ctrl':          // a + ctrl
      ex_selectAll();
      break;

    case '67shift':
    case '67ctrl':          // c + ctrl
      // ex_clipboard('copy');
      break;

    case '68shift':         // d + shift
      if(ex_editing())
        return;

      __self = $(_self).children('.fav').children('.fa');
      ex_favorites(__self);
      break;

    case '68altshift':      // d + alt + shift (favorites page)
      reDraw('$/favorites');
      break;

    case '70':              // f
      ex_player('fullscreen')
      break;

    case '72shift':         // h + shift (Home page)
      if(ex_editing())
        return;

      reDraw('/');
      break;

    // case '76shift':         // l + shift (show intro.js)
    //   ex_intro();
    //   break;

    case '77':              // m
      ex_player('muted');
      break;

    case '78shift':         // n + shift
      if(ex_editing())
        return;

      e.preventDefault();
      ex_newFolder();
      break;

    case '80shift':         // p + shift
      if(ex_editing())
        return;

      e.preventDefault();
      ex_showPropAdd();
      break;

    case '81ctrl':          // q + ctrl
    case '81shift':         // q + shift
    case '83shift':         // s + shift
      if(ex_editing())
        return;

      e.preventDefault();
      $("#search input").focus().select();
      break;

    case '82shift':         // r + shift
      if(ex_editing())
        return;

      reDraw();
      break;

    case '84shift':         // t + shift
      if(ex_editing())
        return;

      e.preventDefault();
      ex_showAddTag();
      break;

    case '84ctrl':          // w + ctrl     close current tab
      console.log("you can't close this tab")
      e.preventDefault();
      break;

    case '85shift':         // u + shift
      if(ex_editing())
        return;

      $('#modal_upload').toggleClass('visible');
      break;

    case '86shift':         // v + shift
    case '86ctrl':          // v + ctrl
      if(ex_editing())
        return;

      ex_paste();
      break;

    case '88shift':         // x + shift
    case '88ctrl':          // x + ctrl
      ex_clipboard('cut');
      break;

    case '112':             // f1
      e.preventDefault();
      ex_intro();
      break;

    case '113':             // f2
      ex_rename();
      break;

    case '114':             // f3
      e.preventDefault();
      if(ex_editing())
        return;

      $("#search input").focus().select();
      break;

    case '116':             // f5
      e.preventDefault();
      reDraw();
      break;

   case '122shift':         // f11 + shift
      ex_player('fullscreen');
      break;

   case '123':              // f12
      e.preventDefault();
      break;

    // ---------------------------------------------------------------------- mouse
    case 'click':           // click
      if($(e.toElement).parent().hasClass('btn-fa-check'))
      {
        // press submit
      }
      else if($(e.toElement).parent().hasClass('btn-fa-times'))
      {
        // press x
      }
      else if(typeof $(e.toElement)[0] == 'undefined')
      {
        // press enter or another
      }
      else if(! $(e.toElement).parent().hasClass('fav'))
      {
        // if(!_self.hasClass('selected'))
        // {
          if(ex_editing())
          {
            ex_checkBody();
          }
          ex_removeClass('selected focused zero');
          ex_itemSelectedFocusedZero(cid);
          ex_showProp();
        // }
      }
      break;


    case 'clickctrl':       // click + ctrl
      ex_removeClass('focused zero');
      _self.toggleClass('selected focused zero');
      ex_checkBody();
      ex_showProp();
      break;


    case 'clickshift':      // click + shift
      ex_removeClass('selected focused');
      ex_items_select_focus_until(cid);
      ex_checkBody();
      break;


    case 'clickctrlshift':  // click + ctrl + shift
      ex_removeClass('focused');
      ex_items_select_focus_until(cid);
      ex_checkBody();
      break;


    case 'dblclick':        // Double click
      if ($(e.toElement).parent().hasClass('fav'))
      {
        // console.log('fav');
      }
      else
      {
        ex_checkBody();
        ex_dblClickItems(_self);
      }
      // ex_removeClass('selected focused zero');
      // _self.addClass('selected focused zero');
      break;


    default:                // exit this handler for other keys
      return;
  }


  if(_key == 'click')
  {
    $('#explorer>ul').removeClass('select-all');
  }
}

