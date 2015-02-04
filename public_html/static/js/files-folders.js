route('*', function() {
  var $files = $('#files', this);

  var fileList = new Cortex([{name: 'Salam', type: 'folder'}]);

  $(document).on('navigate:done', function(e, data) {
    fileList.set(data.files);
  });

  fileList.on('update', function(d) {
    componen.setProps({items: d.getValue()});
  });

  var component = React.render(FileList({items: fileList.getValue()}), $files.get(0));
});