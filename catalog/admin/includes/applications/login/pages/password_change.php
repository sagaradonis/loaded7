<?php
  /*
  $Id: password_change.php v1.0 2013-01-01 datazen $

  LoadedCommerce, Innovative eCommerce Solutions
  http://www.loadedcommerce.com

  Copyright (c) 2013 Loaded Commerce, LLC

  @author     LoadedCommerce Team
  @copyright  (c) 2013 LoadedCommerce Team
  @license    http://loadedcommerce.com/license.html
*/
?>
<div id="container">
  <hgroup id="login-title" class="margin-bottom">
    <h1 class="login-title-image"><?php echo STORE_NAME; ?></h1>
  </hgroup>
  <div id="form-wrapper">
    <div id="form-block" class="scratch-metal">
      <div id="form-viewport">
        <form id="form-password-change" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&action=password_success'); ?>" class="input-wrapper blue-gradient glossy" method="post">
          <h3 class="align-center"><?php echo $lC_Language->get('heading_change_password'); ?></h3>
          <p class="mid-margin-bottom small-margin-left"><?php echo $lC_Language->get('text_for_login'); ?>: <?php echo $_SESSION['user_confirmed_email']; ?></p>
          <ul class="inputs black-input medium">
            <i id="cpInput1" class="icon-cross icon-red align-right" style="position:absolute; top:85px; right:25px;"></i>
            <li class="with-small-padding small-margin-left small-margin-right">
              <span class="icon-lock small-margin-right"></span>
              <input type="password" name="password" id="password" value="" class="input-unstyled with-small-padding" placeholder="<?php echo $lC_Language->get('placeholder_enter_password'); ?>" autocomplete="off" onkeyup="validateRequirements1(this.value);">
            </li>
            <i id="cpInput2" class="icon-cross icon-red align-right" style="position:absolute; top:125px; right:25px;"></i>
            <li class="with-small-padding small-margin-left small-margin-right">
              <span class="icon-lock small-margin-right"></span>
              <input type="password" name="passwordconfirm" id="passwordconfirm" value="" class="input-unstyled" placeholder="<?php echo $lC_Language->get('placeholder_confirm_password'); ?>" autocomplete="off" onkeyup="comparePass(this.value);validateRequirements2(this.value);">
            </li>
          </ul>
          <input type="hidden" name="email" id="email" value="<?php echo $_SESSION['user_confirmed_email']; ?>">
          <p class="margin-bottom small-margin-left align-center"><?php echo $lC_Language->get('text_password_instructions_1') . ' ' . ACCOUNT_PASSWORD . ' ' . $lC_Language->get('text_password_instructions_2'); ?></p>
          <p class=" align-center mid-margin-bottom"><button type="submit" class="button glossy green-gradient full-width" id="submit-password" disabled><?php echo $lC_Language->get('button_submit'); ?></button></p>
        </form>
      </div>
    </div>
  </div>
  <p class="anthracite" align="center" style="line-height:1.5;">Copyright &copy; <?php echo @date("Y"); ?> <a class="anthracite" href="http://www.loaded7.com">Loaded Commerce</a><br /><?php echo $lC_Language->get('text_version') . ' ' . utility::getVersion(); ?></p>
</div>
<script>
  $(document).ready(function() {
    // Elements
    $('body').removeClass('clearfix with-menu with-shortcuts');
    $('html').addClass('linen');

    var doc = $('html').addClass('js-login'),
    container = $('#container'),
    formWrapper = $('#form-wrapper'),
    formBlock = $('#form-block'),
    formViewport = $('#form-viewport'),
    forms = formViewport.children('form'),

    // Current form
    hash = (document.location.hash.length > 1) ? document.location.hash.substring(1) : false,

    // If layout is centered
    centered,

    // Store current form
    currentForm,

    // Animation interval
    animInt,

    // Work vars
    maxHeight = false,
    blocHeight;
    
    /******* EDIT THIS SECTION *******/

    /*
    * Change Password
    * These functions will handle the login process through AJAX
    */
    $('#form-password-change').submit(function(event) {
      // Values
      var pass = $.trim($('#password').val()),
          passconfirm = $.trim($('#passwordconfirm').val());

      // Stop normal behavior
      event.preventDefault();

      /////////////////////////////////////////////////////////////////////////////////////////////////////
      // need to determine if we need configuration settings to validate password formatting against.... //
      // ie, minimum length, uppercase, sybols, numbers etc etc, none exist to my knowledge currently??? //
      /////////////////////////////////////////////////////////////////////////////////////////////////////
      
      var containsDigits = /[0-9]/.test(pass);
      var containsUpper = /[A-Z]/.test(pass);
      var containsLower = /[a-z]/.test(pass);
      
      // Check inputs
      if (pass.length < '<?php echo ACCOUNT_PASSWORD; ?>' || !containsDigits || !containsUpper || !containsLower) {
        // Remove empty login message if displayed
        formWrapper.clearMessages();
        displayError('<?php echo $lC_Language->get('text_password_requirements'); ?>');
        return false;
      } else {
        // Remove previous messages
        formWrapper.clearMessages();

        // Stop normal behavior
        $("#form-password-change").bind("submit", preventDefault(event));

        var nvp = $("#form-password-change").serialize();
        
        // change the password, log the user in and continue to success page
        var jsonLink = '<?php echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=passwordChange&NVP'); ?>'; 
        $.getJSON(jsonLink.replace('NVP', nvp),        
          function (data) {  
            if (data.rpcStatus == 1) { 
              $("#form-password-change").unbind("submit", preventDefault(event)).submit();
              return true;                  
            } 
            displayError('<?php echo $lC_Language->get('ms_error_password_change'); ?>');   
            return false;
          }              
        );
        /////////////////////////////////////////////////////////////////////////////////////////////////////
      }
    });
    /******* END OF EDIT SECTION *******/ 
    
    // Prepare forms
    forms.each(function(i) {
      var form = $(this),
      height = form.outerHeight(),
      active = (hash === false && i === 0) || (hash === this.id),
      color = this.className.match(/[a-z]+-gradient/) ? ' '+(/([a-z]+)-gradient/.exec(this.className)[1])+'-active' : '';

      // Store size
      form.data('height', height);

      // Min-height for mobile layout
      if (maxHeight === false || height > maxHeight) {
        maxHeight = height;
      }

      // If active
      if (active) {
        // Store
        currentForm = form;

        // Height of viewport
        formViewport.height(height);
      } else {
        // Hide for now
        form.hide();
      }
    });

    // Main bloc height (without form height)
    blocHeight = formBlock.height()-currentForm.data('height');

    // Handle resizing (mostly for debugging)
    function handleLoginResize() {
      // Detect mode
      centered = (container.css('position') === 'absolute');

      // Set min-height for mobile layout
      if (!centered) {
        formWrapper.css('min-height', (blocHeight+maxHeight+20)+'px');
        container.css('margin-top', '');
      } else {
        formWrapper.css('min-height', '');
        if (parseInt(container.css('margin-top'), 10) === 0) {
          centerForm(currentForm, false);
        }
      }
    };

    // Register and first call
    $(window).bind('normalized-resize', handleLoginResize);
    handleLoginResize();

    // Initial vertical adjust
    centerForm(currentForm, false);

    /*
    * Center function
    * @param jQuery form the form element whose height will be used
    * @param boolean animate whether or not to animate the position change
    * @param string|element|array any jQuery selector, DOM element or set of DOM elements which should be ignored
    * @return void
    */
    function centerForm(form, animate, ignore) {
      // If layout is centered
      if (centered) {
        var siblings = formWrapper.siblings().not('.closing'),
        finalSize = blocHeight+form.data('height');

        // Ignored elements
        if (ignore) {
          siblings = siblings.not(ignore);
        }

        // Get other elements height
        siblings.each(function(i) {
          finalSize += $(this).outerHeight(true);
        });

        // Setup
        container[animate ? 'animate' : 'css']({ marginTop: -Math.round(finalSize/2)+'px' });
      }
    };

    /**
    * Function to display error messages
    * @param string message the error to display
    */
    function displayError(message) {
      // clear any other messages
      formWrapper.clearMessages();
      // Show message
      var message = formWrapper.message(message, {
        append: false,
        arrow: 'bottom',
        classes: ['red-gradient', 'align-center'],
        closable: false,            
        animate: false          // We'll do animation later, we need to know the message height first
      });

      // Vertical centering (where we need the message height)
      centerForm(currentForm, true, 'fast');

      // Watch for closing and show with effect
      message.bind('endfade', function(event) {
        // This will be called once the message has faded away and is removed
        centerForm(currentForm, true, message.get(0));

      }).hide().slideDown('fast');
    };

    /**
    * Function to display loading messages
    * @param string message the message to display
    */
    function displayLoading(message) {
      // Show message
      var message = formWrapper.message('<strong>'+message+'</strong>', {
        append: false,
        arrow: 'bottom',
        classes: ['blue-gradient', 'align-center'],
        stripes: true,
        darkStripes: false,
        closable: false,
        animate: false          // We'll do animation later, we need to know the message height first
      });

      // Vertical centering (where we need the message height)
      centerForm(currentForm, true, 'fast');

      // Watch for closing and show with effect
      message.bind('endfade', function(event) {
        // This will be called once the message has faded away and is removed
        centerForm(currentForm, true, message.get(0));

      }).hide().slideDown('fast');
    };

    /**
    * Function to prevent default action
    * @param object the event 
    */      
    function preventDefault(e) {
      e.preventDefault();
    }
  });
    
  function comparePass(val) {
    var pass = $("#password").val();
    if (val == pass) {
      $("#submit-password").removeAttr("disabled");
    } else {
      $("#submit-password").attr("disabled", "disabled");
    }
  }
  
  function validateRequirements1(val) {
    var containsDigits = /[0-9]/.test(val);
    var containsUpper = /[A-Z]/.test(val);
    var containsLower = /[a-z]/.test(val);
    if (val.length >= '<?php echo ACCOUNT_PASSWORD; ?>' && containsDigits && containsUpper && containsLower) {
      $("#cpInput1").removeClass("icon-cross icon-red").addClass("icon-tick icon-green");
    }
  }
  
  function validateRequirements2(val) {
    var containsDigits = /[0-9]/.test(val);
    var containsUpper = /[A-Z]/.test(val);
    var containsLower = /[a-z]/.test(val);
    if (val.length >= '<?php echo ACCOUNT_PASSWORD; ?>' && containsDigits && containsUpper && containsLower) {
      $("#cpInput2").removeClass("icon-cross icon-red").addClass("icon-tick icon-green");
    }
  }
</script>