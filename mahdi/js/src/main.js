$(document).ready(function() {
  var regex = /(?:\?|&)?lang=\w*/g;
  if(regex.test(location.search)) {
    Navigate({
      url: location.pathname + location.search.replace(regex, '') + location.hash,
      replace: true
    });
  }

    /* Blur inputs with ESC key */
  $(document).keydown(function(e) {
    if(e.keyCode === 27) {
      $('input').blur();
    }
  });

  $(document).on('submit', 'form', function(e) {
    e.preventDefault();
    $(this).ajaxify();
  });

  $(document).on('click', '[data-ajaxify]', function(e) {
    e.preventDefault();
    $(this).ajaxify({link: true});
  });

  $(document).on('click', '[data-modal]', function(e) {
    e.preventDefault();
    var $this = $(this);
    var $modal = $('#' + $this.attr('data-modal'));
    $modal.copyData($this, ['modal']);

    $modal.trigger('open', $this);
  });

  $(document).on('click','[data-close]', function(e) {
    $('.modal').trigger('close');
  });

  $(document).on('change', '#langlist', function() {
    var regex = /(?:\?|&)?lang=\w*/g;
    var srch = location.search.replace(regex, ''),
        url = location.pathname + (srch ? srch + '&' : '?') + 'lang='+this.value;

    location.replace(url);
  });

  $(document).on('keypress', 'input[type="date"],\
                            input[type="datetime-local"],\
                            input[type="number"],\
                            input[type="tel"],\
                            input#mobile', function(e) {
                              if(this.getAttribute('data-allowpersian') !== null || e.which < 32) return;
                              e.preventDefault();

                              if(e.which === 32) return;

                              var val = '';
                              var key = String.fromCharCode(e.which);

                              if(e.which <= 1785 && e.which >= 1776) {
                                try {
                                  var start = this.selectionStart,
                                      end = this.selectionEnd;

                                  val = this.value.slice(0, start) + key.toEnglish() + this.value.slice(end);
                                } catch(e) {
                                  val = this.value + key.toEnglish();
                                }
                              } else {
                                val = this.value + key;
                              }

                              if(isNaN(+val)) {
                                $this = $(this);
                                $this.addClass('invalid');
                                setTimeout(function() {
                                  $this.removeClass('invalid');
                                }, 500);
                              } else {
                                this.value = val;
                              }
                              return false;
                            });

    // 'a:not([target="_blank"])\
    // :not([data-ajaxify])\
    // :not([data-action])\
    // :not([data-modal])',
  $(document.body).on('click', 'a', function(e) {
    var $this = $(this);

    if($this.attr('target') === '_blank' || $this.hasAttr('data-ajaxify') ||
       $this.hasAttr('data-action') || $this.hasAttr('data-modal') || $this.isAbsoluteURL()) return;

    e.preventDefault();

    if(!$this.attr('href') || $this.attr('href').indexOf('#') > -1) return;

    var href = $this.attr('href');

    if(href.indexOf('lang=') > -1) return location.replace(href);

    Navigate({
      url: href
    });
  });
});

route('*', function() {
  $('input').prop('lang', 'en');
  /* MODALS */

  $('.modal', this).on('close', function() {
    var $this = $(this);

    $this.removeClass('visible').addClass('hidden');

    $.each($this.data(), function(key) {
      if(key === 'modal') return;
      $(this).removeAttr(key);
    });
  });

  $('.modal', this).on('open', function() {
    var $send = $('[data-ajaxify]', this);

    $.each($send.data(), function(key) {
      if(key === 'modal') return;

      $send.removeAttr(key);
    });

    $send.copyData(this, ['modal']);
  });

  $('.modal', this).on('open', function() {
    $(this).removeClass('hidden').addClass('visible');
  });
});
