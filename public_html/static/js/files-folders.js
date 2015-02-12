(function() {
  var fileList = new Cortex([]);
  var filesTree = new Cortex({});

  window.fileList = fileList;

  var $breadcrumbs = React.render(BreadcrumbView({items: []}), document.getElementById('breadcrumb-wrapper'));
  var $tree = React.render(TreeView({items:{}}), document.getElementById('tree-wrapper'));
  var $files = $('#files');

  var trigger = true;

  fileList.on('update', function(d) {
    if(!trigger) return;
    var parent = filterByURL(fileList);

    trigger = false;
    setChildren(fileList, parent);
    trigger = true;

    component.setProps({items: d.getValue()});

    filesTree.set(objectifyFiles(fileList, getChildren(fileList, "")));

    $(document).trigger('navigate:done');
  });

  filesTree.on('update', function(d) {
    $tree.setProps({items: d.val()});
  });

  var component = React.render(FileList({items: []}), $files.get(0));

  Navigate({
    url: location.pathname,
    replace: true
  }).done(function() {
    var obj = JSON.parse(history.state.tree);
    fileList.set(obj);

    var parent = filterByURL(fileList);

    setChildren(fileList, parent);

    component.setProps({
      parent: parent
    });
  });


  $(window).on('statechange', function() {
    var parent = filterByURL(fileList);

    setChildren(fileList, parent);

    component.setProps({
      parent: parent
    });

    $breadcrumbs.setProps({
      'items': location.pathname.slice(1).split('/')
    });
  });

  $(document).ready(function() {
    var filemanager = $('.filemanager');

    var fm;

    $('#upload_form').submit(function(e) {
      e.preventDefault();
    });

    $('#upload').click(function(e) {
      e.preventDefault();
      fm = new FileManager({
        file: $('#file_input').get(0).files[0],
        ajax: {
          url: 'http://files.talambar.dev'
        }
      });

      fm.upload();
    });

    $('#resume').click(function(e) {
      e.preventDefault();
      fm.resume();
    });

    $('#pause').click(function(e) {
      e.preventDefault();
      fm.pause();
    });

    var $newform = $('#newform');

    $('#newfolder').click(function(e) {
      e.preventDefault();
      $newform.removeClass('hidden');
    });

    $newform.find('form').submit(function(e) {
      $newform.find('[name="address"]').val(location.pathname);
      $newform.addClass('hidden');

      fileList.push({
        name: $newform.find('[name="name"]').val(),
        parent: component.props.parent,
        type: 'folder',
        disabled: true
      });
    });
  });
})();

function filterByURL(files) {
  var url = location.pathname.slice(1).split('/');

  if(url[0] === '') return '';

  var current = files.findObject({name: url[0], parent: ''});
  (function() {
    if(!current) return;

    for(var i = 1, len = url.length; i < len; i++) {
      current = files.findObject({name: url[i], parent: current.id.getValue()});
    }
  })();

  var parent = current ? current.id.getValue() : null;

  return parent;
}

function objectifyFiles(files, filtered, url) {
  var tree = {};

  filtered.forEach(function(file) {
    var obj = file.val();
    obj.url = (url || '') + '/' + obj.name;
    obj.children = objectifyFiles(files, getChildren(files, obj.id), obj.url);

    tree[obj.id] = obj;
  });

  return tree;
}

function setChildren(files, parent) {
  var children = getChildren(files, parent);

  children.forEach(function(child, i) {
    if(child.type.val() !== 'folder' || child.hasKey('disabled')) return;

    var subchilds = getChildren(files, child.id.val()).count();

    var ref = files.findObject({id: child.id.val()});

    ref.add('children', subchilds);
  });
}

function getChildren(files, id) {
  return new Cortex(files.filterObject({parent: id}).map(function(a) {
    return a.val();
  }));
}