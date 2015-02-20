var fm;

$('#upload').click(function() {
  fm = new FileManager({
    file: $('#a').prop('files')[0],
    ajax: {
      url: 'http://localhost:8000/'
    }
  });

  fm.upload();
});

