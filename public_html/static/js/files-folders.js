(function() {
  var fileList = new Cortex({});
  var filesTree = new Cortex({});

  var $breadcrumbs = React.render(BreadcrumbView({items: []}), document.getElementById('breadcrumb-wrapper'));

  var $tree = React.render(TreeView({items:{}}), document.getElementById('tree-wrapper'));

  var $files = $('#files');

  fileList.on('update', function(d) {
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
    fileList.set(JSON.parse(history.state.tree));

    var parent = filterByURL(fileList);
    component.setProps({
      parent: parent
    });
  });

  $(window).on('statechange', function() {
    var parent = filterByURL(fileList);
    component.setProps({
      parent: parent
    });

    $breadcrumbs.setProps({
      'items': location.pathname.slice(1).split('/')
    });
  });
})();

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
});

function filterByURL(files) {
  var url = location.pathname.slice(1).split('/');

  var current = files.findObject({name: url[0], parent: ''});
  (function() {
    if(!current) return;

    for(var i = 1, len = url.length; i < len; i++) {
      current = files.findObject({name: url[i], parent: current.id.getValue()});
    }
  })();
  var parent = current ? current.id.getValue() : '';

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

function getChildren(files, id) {
  return new Cortex(files.filterObject({parent: id}).map(function(a) {
    return a.val();
  }));
}