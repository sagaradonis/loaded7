<?php
  /*
  $Id: activate_free.php v1.0 2013-01-01 datazen $

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
        <form id="form-activate-free" action="<?php echo lc_href_link_admin(FILENAME_DEFAULT, $lC_Template->getModule() . '&action=free_success'); ?>" class="input-wrapper blue-gradient glossy" method="post">
          <h3 class="align-center margin-bottom"><?php echo $lC_Language->get('text_activate_free_features'); ?></h3>
          <p class="align-center no-margin-top"><b><?php echo $lC_Language->get('text_free_core_activation'); ?></b></p>
          <p class="small-margin-left small-margin-right"><?php echo $lC_Language->get('text_domain'); ?>: thisdomain.ext</p>
          <ul class="inputs black-input large">
            <li><span class="icon-mail mid-margin-right"></span><input type="email" name="activation_email" id="activation_email" value="" class="input-unstyled" placeholder="<?php echo $lC_Language->get('placeholder_activation_email'); ?>" autocomplete="off"></li>
          </ul>
          <p class="large-margin-top large-margin-bottom mid-margin-left"><span class="margin-top"><?php echo $lC_Language->get('text_adult_content'); ?>: </span><input type="checkbox" class="switch" data-text-on="YES" data-text-off="NO"></p>
          <p class="align-center small-margin-bottom"><button type="submit" class="button glossy green-gradient full-width" id="submit-password"><?php echo $lC_Language->get('button_submit_activate'); ?></button></p>
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

    // Doors
    topDoor = $('<div id="top-door" class="form-door"><div></div></div>').appendTo(formViewport),
    botDoor = $('<div id="bot-door" class="form-door"><div></div></div>').appendTo(formViewport),
    doors = topDoor.add(botDoor),

    // Switch
    formSwitch = '',

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
    $('#form-activate-free').submit(function(event) {
      // Values
      var email = $.trim($('#activation_email').val());

      // Stop normal behavior
      event.preventDefault();

      // Check inputs
      if (email.length === 0) {
        // Display message
        displayError('<?php echo $lC_Language->get('text_enter_email'); ?>');
        return false;
      } else {
        // Remove previous messages
        formWrapper.clearMessages();

        // Stop normal behavior
        $("#form-activate-free").bind("submit", preventDefault(event));

        var nvp = $("#form-activate-free").serialize();
        alert('activate free');
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        // update me if needed for free activation needs
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        /*var jsonLink = '<?php //echo lc_href_link_admin('rpc.php', $lC_Template->getModule() . '&action=activateFree&NVP'); ?>'; 
        $.getJSON(jsonLink.replace('NVP', nvp),        
          function (data) {  
            if (data.rpcStatus == 1) { 
              $("#form-activate-free").unbind("submit", preventDefault(event)).submit();
              return true;                  
            } 
            displayError('<?php //echo $lC_Language->get('ms_error_login_invalid'); ?>');   
            return false;
          }              
        );*/
        /////////////////////////////////////////////////////////////////////////////////////////////////////
        
        // temporary testing - go to free success page
        $("#form-activate-free").unbind("submit", preventDefault(event)).submit();
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
</script>