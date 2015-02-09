(function() {
  var fileList = new Cortex({});

  $breadcrumbs = React.render(BreadcrumbView({items: []}), document.getElementById('breadcrumb-wrapper'));
  var $files = $('#files');

  fileList.on('update', function(d) {
    component.setProps({items: d.getValue()});

    $(document).trigger('navigate:done');
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

  $('#upload').click(function() {
    fm = new FileManager({
      file: $('#file_input').get(0).files[0],
      ajax: {
        type: 'post',
        contentType: false,
        processData: false,
        dataType: 'text',
        mimeType: 'text/plain',
        url: 'http://localhost/'
      },
    });

    fm.upload();
  });

  $('#resume').click(function() {
    fm.resume();
  });

  $('#pause').click(function() {
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

