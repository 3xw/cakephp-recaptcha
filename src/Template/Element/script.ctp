<script>
(function($, scope)
{
  var recaptcha = {

    target: null,

    submit: function(e)
    {
      e.preventDefault();
      recaptcha.target = e.currentTarget
      //console.log(recaptcha.target);
      grecaptcha.ready(recaptcha.submitRecaptcha)
    },

    submitRecaptcha: function()
    {
      grecaptcha.execute('<?= $siteKey ?>', {action: 'submit'})
      .then(recaptcha.submitDataWithToken)
    },

    submitDataWithToken: function(token)
    {
      $(recaptcha.target)
      .find('.g-recaptcha-response')
      .val(token)
      $(recaptcha.target)
      .unbind('submit', recaptcha.submit)
      .submit()
    },

    init: function()
    {
      $('.g-recaptcha-response').each(function(i, elem){
        $(elem).closest('form').bind('submit', recaptcha.submit)
      })
    }
  }

  // boostrap
  $(document).ready(recaptcha.init)

})(jQuery, window)
</script>
