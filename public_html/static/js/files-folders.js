var fileList, component;
(function() {
  fileList = new Cortex({});

  var parent = '';

  var lastPath = '';

  $(document).on('navigate:done', function(e, data) {
    fileList.set(JSON.parse(data.tree));
    var name = location.pathname.slice(1);

    var current = fileFinder();
    if(current) parent = current.id.getValue();
    else parent = '';
    component.setProps({parent: parent});
  });

  var $files = $('#files');

  fileList.on('update', function(d) {
    component.setProps({items: d.getValue()});
  });

  component = React.render(FileList({items: fileList.getValue()}), $files.get(0));

  component.componentDidUpdate = function() {
    var current = findInList({id: this.props.parent});
    if(!current) return;
    var path = findPath(current);
    if(path === lastPath) return;
    lastPath = path;

    Navigate({
      url: path
    });
  };

  Navigate({
    url: location.href,
    replace: true
  });
})();


function fileFinder() {
  var tree = location.pathname.slice(1).split('/');

  if(!tree || tree[0] === "") return null;

  return tree.slice(1).reduce(function(prev, current) {
    console.log(prev.getValue(), current);
    return findInList({
      name: current,
      parent: prev.id.getValue()
    });
  }, findInList({name: tree[0], parent: ''}));
}

function findPath(file) {
  var path = file.name.getValue();

  var parent = findInList({id: file.parent.getValue()});
  while(parent) {
    path = parent.name.getValue() + '/' + path;

    parent = findInList({id: parent.parent.getValue()});
  }

  return path;
}

function findInList(object) {
  return fileList.find(function(f) {
    var condition = true;
    for(var i in object) {
      condition = f[i].getValue() === object[i];
    }
    return condition;
  });
}