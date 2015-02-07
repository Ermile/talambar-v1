var fileList, component;
(function() {
  fileList = new Cortex({});

  var $breadcrumbs = $('.fbreadcrumb');
  var $files = $('#files');

  fileList.on('update', function(d) {
    component.setProps({items: d.getValue()});

    $(document).trigger('navigate:done');
  });

  component = React.render(FileList({items: []}), $files.get(0));

  Navigate({
    url: location.pathname,
    replace: true
  }).done(function() {
    fileList.set(JSON.parse(history.state.tree));
    filterByURL();
  });

  $(window).on('statechange', function() {
    filterByURL();

    var $newcrumbs = generateBreadcrumbs();
    $breadcrumbs.html($newcrumbs);
  });

})();

function filterByURL() {
  var url = location.pathname.slice(1).split('/');

  var current = findInList({name: url[0], parent: ''});
  (function() {
    if(!current) return;

    for(var i = 1, len = url.length; i < len; i++) {
      current = findInList({name: url[i], parent: current.id.getValue()});
    }
  })();
  var parent = current ? current.id.getValue() : '';

  component.setProps({
    parent: parent
  });
}

function findInList(object) {
  return fileList.find(function(f) {
    var condition = true;
    for(var i in object) {
      condition = condition && f[i].getValue() === object[i];
    }
    return condition;
  });
}

function generateBreadcrumbs(path) {
  var tree = location.pathname.slice(1).split('/');
  var gen = '';

  var $li = $('<li><i class="fa fa-arrow-right"></i><a></a></li>');

  var $home = $('<li><a href="/">Home</a></li>');

  if(!tree || tree[0] === "") return $home;

  var $els = tree.map(function(segment) {
    gen += '/' + segment;
    var $clone = $li.clone();

    $clone.find('a').attr('href', gen).html(segment);
    return $clone.get(0);
  });

  return $home.add($els);
}